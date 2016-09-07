<?php
/**
 * Created by PhpStorm.
 * Date: 21.01.16
 * Time: 12:36
 * Додає коменти та ответи до них в БД. Якщо комент має парент ід то він оприділ як ответ і повертає
 * додатково html даного ответа.
 * Дістає всі коменти з БД і повертає згенерований html код для них. Шаблон вибирається в залежності
 * від типу сущності до якого був написаний комент (магазин, товар, бренд).
 */

namespace Modules;

class Comments
{
    const MESSAGE_SUCCESS = 'Спасибо, Ваш комментарий добавлен';
    const MESSAGE_FAIL = 'Извините, сейчас мы не можем обработать Ваш запрос';

    //    private $notify;

    /**
     * @var int Ід сущності до якої відноситься комент.
     */
    public $itemId;

    /**
     * @var string Тип сущності до якої відноситься комент.
     */
    private $itemType;

    /**
     * @var int Значення рейтингу комента.
     */
    private $rating;

    /**
     * @var string Недостатки.
     */
    private $flaw;

    /**
     * @var string Переваги.
     */
    private $advantage;

    /**
     * @var string Ім’я користувача з сесії.
     */
    private $currentUserName;

    /**
     * @var int Ід родителя комента.
     */
    public $parentId;

    /**
     * @var string email користувача з форми.
     */
    public $userEmail;

    /**
     * @var string ім’я користувача з форми.
     */
    public $userName;

    /**
     * @var string Текст комента.
     */
    public $comment;

    /**
     * @var екземпляр класу шаблона.
     */
    public $templates;

    /**
     * @var string html код всіх коментів або одного ответа.
     */
    public $lines = '';

    /**
     * @var int Кількіть коментів.
     */
    public $commentsCount = 0;

    /**
     * @var int статус виконня скрипта: 1 - успіх, 0 - помилка
     */
    public $result = 0;

    /**
     * Comments constructor.
     * Ініціалізація ім’я користувача, якщо він залогінений, на ініц. класа глобального шаблона.
     * @param $templates object
     */
    public function __construct($templates)
    {
        $this->currentUserName = is_numeric($_SESSION['user_id']) ? $_SESSION['user_realname'] : '';
        $this->templates = $templates;
    }

    /**
     * Провірка вхідних пост даних та ініціалізація їх в змінні.
     * @return bool
     */
    public function initPostData()
    {
        $this->itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
        $this->parentId   = filter_input(INPUT_POST, 'parent_id', FILTER_VALIDATE_INT);
        $this->comment    = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
        $this->userEmail  = filter_input(INPUT_POST, 'user_email', FILTER_VALIDATE_EMAIL);
        $this->userName   = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
        $this->flaw       = filter_input(INPUT_POST, 'flaw', FILTER_SANITIZE_STRING);
        $this->advantage  = filter_input(INPUT_POST, 'advantage', FILTER_SANITIZE_STRING);
        $this->rating     = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
//        $this->notify     = filter_input(INPUT_POST, 'notify', FILTER_VALIDATE_BOOLEAN);

        if (
            $this->itemId &&
            $this->comment &&
            $this->userName
        ) {
            return true;
        }

        return false;
    }

    /**
     * Рендерить html для 1 ответа.
     * @param $answer array масив з параметрами одого ответа
     * @return string html
     */
    public function renderAnswers($answer)
    {
        $time = (new \DateTime($answer['comment_date']))->format('d.m.Y H:i');

        return <<<HTML1
            <div class="comments-wrap">
                <div class="user-name">
                    <i class="fa fa-user"></i>{$answer['user_name']}
                </div>
                <span class="date">({$time})</span>
                <p>{$answer['comment_text']}</p>
           </div>
HTML1;
    }

    /**
     * Запис в БД комент.
     * @return $this
     */
    public function add()
    {
        $this->result = MySQLi::getInstance()
            ->getConnect()
            ->query( <<<QUERY
            INSERT INTO comments
			SET
			  item_id = '{$this->itemId}',
 			  parent_id = '{$this->parentId}',
			  comment_date = NOW(),
			  comment_email = '{$this->userEmail}',
			  comment_ip = '{$_SERVER['REMOTE_ADDR']}',
			  user_id ='{$_SESSION["user_id"]}',
			  user_name = '{$this->userName}',
			  comment_text = '{$this->comment}',
			  comment_minus = '{$this->flaw}',
			  comment_plus = '{$this->advantage}',
			  comment_rat = '{$this->rating}',
			  url = '{$_SERVER["HTTP_REFERER"]}',
			  visible = '1'
QUERY
    );
        $this->lines = $this->result ? self::MESSAGE_SUCCESS : self::MESSAGE_FAIL;

        return $this;
    }

    /**
     * Витягуєм з БД всі коменти та рендерим для них html
     * @return $this
     */
    public function get()
    {
        $result = MySQLi::getInstance()->getConnect()->query(
            <<<QUERY1
                SELECT
                  comment_id, comment_plus, comment_minus, comment_text, comment_date,
                  comment_rat, user_name, parent_id
                FROM comments
	            WHERE item_id = '{$this->itemId}'
	            AND visible = '1'
	            ORDER BY comment_date DESC
QUERY1
        );

        if ($result->num_rows > 0) {

            $parents = array();
            $children = array();
            $childrenParents = array();

            //розділяєм коменти і ответ в різні масиви
            //оприділяєм ще один масив з ключами ід ответов та значеннями ід їх парентів(коментів)
            while($row = $result->fetch_assoc()) {
                if ($row['parent_id'] > 0) {
                    $childrenParents[$row['comment_id']] = $row['parent_id'];
                    $children[$row['comment_id']] = $row;
                    continue;
                }
                $parents[] = $row;
            }

            $this->commentsCount = count($parents);

            foreach ($parents as $row) {

                $rating = '';
                $answers = '';

                //рендер зірки оцінки комента
                for ($i = 1; $i <= 5; $i++) {
                    $active = $row['comment_rat'] >= $i ? " class='active'" : "";
                    $rating .= "<li{$active}></li>";
                }

                //рендер ответов для коментів
                $answersKeys = array_reverse(array_keys($childrenParents, $row['comment_id']));
                foreach ($answersKeys as $key) {
                    $answers .= $this->renderAnswers($children[$key]);
                }

                $this->templates->set_tpl('{$commentId}', $row['comment_id']);
                $this->templates->set_tpl('{$commentsPlus}', $row['comment_plus']);
                $this->templates->set_tpl('{$commentsMinus}', $row['comment_minus']);
                $this->templates->set_tpl('{$commentsText}', $row['comment_text']);
                $this->templates->set_tpl('{$commentsDate}', $row['comment_date']);
                $this->templates->set_tpl('{$commentsRating}', $rating);
                $this->templates->set_tpl('{$userName}', $row['user_name']);
                $this->templates->set_tpl('{$commentUserName}', $this->currentUserName);
                $this->templates->set_tpl('{$answers}', $answers);
                $this->templates->set_tpl('{$answersAmount}', count($answersKeys));
                $this->lines .= $this->templates->get_tpl('comments.all.line', '../../../');
            }
            $this->result = 1;
        }

        return $this;
    }

    /**
     * Виводить вже сформований резултат в json форматі
     * @return $this
     */
    public function showResult()
    {
        echo json_encode(array(
            'success' => (int)$this->result,
            'lines' => $this->lines,
            'count' => $this->commentsCount
        ));

         return $this;
    }
}