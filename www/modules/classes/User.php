<?php
/**
 * Created by PhpStorm.
 * User: webler
 * Date: 27.01.16
 * Time: 17:17
 */

namespace Modules;

/**
 * Class User.
 * @package Modules
 */
class User
{
    const MAIL_THEME_CONFIRM  = 'Подтверждение регистрации.';
    const MAIL_THEME_PASS     = 'Пароль к учетной записи';
    const PAGE_CABINET        = '/myaccount/profile/';
    const PAGE_MAIN           = '/';
    const PAGE_SHOW_CONFIRM   = '/show-confirm-email/';
    const PAGE_SEND_CONFIRM   = '/send-confirm-email/';
    const REGEXP_PASSWORD     = '/(?=^.{5,25}$)(?!.*\s).*$/';
    const REGEXP_PHONE        = '/^[0-9\+\-\s\)\(]{8,25}$/';
    const REGEXP_CITY_COUNTRY = '/^[а-я А-Яїі]{4,25}$/';
    const REGEXP_BIRTHDAY     = '/^(0[1-9]|1[0-9]|2[0-9]|3[01])\/(0[1-9]|1[012])\/[0-9]{4}$/';

    public $id;
    public $email;
    public $existsUser;
    public $status               = 'user';
    public $ajaxResult           = 0;
    public $ajaxMessage;
    public $mysqli;
    public $name;
    public $realName;
    public $socProvider;
    public $birthDay;
    public $delivery;
    public $warehouse;
    public $country;
    public $city;
    public $address;
    public $phone;
    public $site;
    public $skype;
    public $discountGift;
    public $fieldsColums         = array(
        'status' => 'user_status',
        'user_birthday' => 'birthday',
        'user_country' => 'country',
        'user_delivery' => 'delivery',
        'user_warehouse' => 'warehouse',
        'user_city' => 'city',
        'user_adr' => 'address',
        'user_tel' => 'phone',
        'user_skype' => 'skype',
        'user_site' => 'site',
        'discount_gift' => 'discountGift'
    );
    protected $image;
    protected $sex;
    protected $socPage;
    protected $socId;
    protected $activated;
    protected $generatedPassword = '';
    protected $emailMessage;
    protected $headers           = array(
        "MIME-Version: 1.0\r\n",
        "Content-type: text/html; charset=UTF-8\r\n"
    );
    private $password;
    protected $template;

    /**
     * User constructor.
     * @param null $id
     * @param null $mysqli
     */
    public function __construct($id = null, $mysqli = null)
    {
        $this->id       = $id;
        $this->mysqli   = $mysqli;
        $this->template = new \templ();
        $this->template->set_tpl('{$hostName}', $_SERVER['HTTP_HOST']);
    }

    /**
     * Створення сесії для зареєстрованого користувача.
     * @return $this
     */
    public function logIn()
    {
        unset($_SESSION['confirm_id']);

        $_SESSION['user_loginname'] = $this->name;
        $_SESSION['user_id']        = $this->id;
        $_SESSION['status']         = $this->status;
        $_SESSION['user_email']     = $this->email;
        $_SESSION['user_realname']  = $this->realName;

        return $this;
    }

    /**
     * Удаляє сесію К.
     * @return $this
     */
    public function logOut()
    {
        unset($_SESSION['users']);
        unset($_SESSION['user_realname']);
        unset($_SESSION['status']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_loginname']);

        if (isset($_SESSION['user_discount'])) {
            $_SESSION['user_discount'] = 0;
        }

        return $this;
    }

    /**
     * Активація користувача в БД, запис хеш вже згенерованого пароля.
     * @param $password bool якщо true то додатково записує парль.
     * @return $this
     */
    public function confirm($password = true)
    {
        $password = $password ? ", user_password = MD5('{$this->generatedPassword}')"
                : '';
        $query    = <<<QUERY
            UPDATE users
            SET
              activated = 1
              {$password}
            WHERE user_id = {$this->id}
QUERY;
        $this->mysqli->query($query);

        $_SESSION['salutation'] = true;

        return $this;
    }

    /**
     * Запис соц. ід до вже існуючого користувача з одинаковою ел. адресою. Активація.
     * @return $this
     */
    public function updateExistsUser()
    {
        $this->mysqli->query(<<<QUERY2
            UPDATE users
            SET
              soc_{$this->socProvider}_id = {$this->socId},
              activated = 1
            WHERE user_id = {$this->existsUser->user_id}
QUERY2
        );
        $this->name     = $this->existsUser->user_name;
        $this->id       = $this->existsUser->user_id;
        $this->realName = $this->existsUser->user_realname;

        return $this;
    }

    public function updateUser($firstName, $lastName, $password)
    {
        $stmt           = $this->mysqli->prepare(<<<QUERYUU
            UPDATE users
            SET
              user_name = ?,
              user_realname = ?,
              user_password = (?)
            WHERE user_email = ?
QUERYUU
        );
        $password       = md5($password);
        $stmt->bind_param('ssss', $firstName, $lastName, $password, $this->email);
        $stmt->execute();
        $this->name     = $firstName;
        $this->realName = $lastName;

        return $this;
    }

    public function getId()
    {
        $result = $this->mysqli->query(<<<QUERYGI
            SELECT user_id
            FROM users
            WHERE user_email = '{$this->email}'
QUERYGI
        );

        if ($result && $result->num_rows > 0) {
            $this->id = $result->fetch_row()[0];
        }

        return $this;
    }

    /**
     * Створення корестувача.
     * @param string $firstName
     * @param string $lastName
     * @param string $password
     * @param string $status
     * @return $this
     */
    public function createUser($firstName, $lastName, $password, $status)
    {
        $query            = <<<QUERY2
            INSERT INTO users (
              user_name, user_realname, user_password, user_email, user_registred_date, status
            ) VALUES (
              '{$firstName}', '{$lastName}', MD5('{$password}'), '{$this->email}', NOW(), '{$status}'
            )
QUERY2;
        $this->ajaxResult = $this->mysqli->query($query);
        $this->id         = $this->mysqli->insert_id;
        return $this;
    }

    /**
     * Видалення не октивованих користувачів з БД по ел. адресі.
     * @return $this
     */
    public function deleteUserByEmail()
    {
        $query = <<<QUERY
            DELETE
            FROM users
            WHERE user_email = '{$this->email}'
            AND activated = 0
QUERY;
        $this->mysqli->query($query);

        return $this;
    }

    /**
     * Видалення користувача з БД.
     * @param $id int
     * @return $this
     */
    public function deleteUserById($id)
    {
        $query = <<<QUERY
            DELETE
            FROM users
            WHERE user_id = '{$id}'
QUERY;
        $this->mysqli->query($query);

        return $this;
    }

    /**
     * Запис в ел. адресу в БД.
     * @param $email string
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->mysqli->query(<<<QUERY
            UPDATE users
            SET
              user_email = '{$email}'
            WHERE user_id = {$this->id}
QUERY
        );

        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $stmt             = $this->mysqli->prepare(<<<QUERY
            UPDATE users
            SET
              user_password = ?
            WHERE user_id = {$this->id}
QUERY
        );
        $stmt->bind_param('s', md5($password));
        $this->ajaxResult = $stmt->execute();
        $stmt->close();

        return $this;
    }

    /**
     * Сетить в БД у вказане поле вказане значення.
     * @param string $fieldName назва пола в базі даних.
     * @param string $value
     * @return $this
     */
    public function setValue($fieldName, $value)
    {
        $stmt             = $this->mysqli->prepare(<<<QUERY
            UPDATE users
            SET
              $fieldName = ?
            WHERE user_id = {$this->id}
QUERY
        );
        $stmt->bind_param('s', $value);
        $this->ajaxResult = $stmt->execute();
        $stmt->close();

        return $this;
    }

    /**
     * Вибір активованого користувача.
     * @return $this|bool
     */
    public function getUsersByEmail()
    {
        $result = $this->mysqli->query(<<<QUERY1
            SELECT
              user_id, user_name, user_realname, user_password, user_admin
            FROM users
            WHERE user_email = '{$this->email}'
            AND activated = 1
            LIMIT 1
QUERY1
        );
        if ($row    = $result->fetch_object()) {
            $this->existsUser = $row;
            return $this;
        }

        return false;
    }

    /**
     * Вибір не активованого користувача.
     * @return $this|bool
     */
    public function getUsersByEmailNotActivated()
    {
        $result = $this->mysqli->query(<<<QUERY1
            SELECT
              user_id
            FROM users
            WHERE user_email = '{$this->email}'
            AND (activated IS NULL
            OR users.activated <> 1)
            LIMIT 1
QUERY1
        );
        if ($row    = $result->fetch_object()) {
            $this->id = $row->id;
            return $this;
        }

        return false;
    }

    /**
     * Вибір з БД необхідних полів користувача по його ід.
     * @return $this
     */
    public function getData()
    {
        $result = $this->mysqli->query(<<<QUERY1
            SELECT
              user_email, user_name, user_realname, activated, user_soc_provider,
              soc_vk_id, soc_fb_id, soc_ok_id, soc_go_id, status, user_password,
              user_birthday, user_delivery, user_warehouse, user_country, user_city,
              user_adr, user_tel, user_skype, user_site, discount_gift
            FROM users
            WHERE user_id = {$this->id}
            LIMIT 1
QUERY1
        );

        if ($result && $row = $result->fetch_assoc()) {
            $this->email        = $row['user_email'];
            $this->realName     = $row['user_realname'];
            $this->name         = $row['user_name'];
            $this->socProvider  = $row['user_soc_provider'];
            $this->activated    = $row['activated'];
            $socId              = 'soc_'.$row['user_soc_provider'].'_id';
            $this->socId        = $row[$socId];
            $this->status       = $row['status'];
            $this->password     = $row['user_password'];
            $this->birthDay     = $row['user_birthday'];
            $this->delivery     = $row['user_delivery'];
            $this->warehouse    = $row['user_warehouse'];
            $this->country      = $row['user_country'];
            $this->city         = $row['user_city'];
            $this->address      = $row['user_adr'];
            $this->phone        = $row['user_tel'];
            $this->skype        = $row['user_skype'];
            $this->site         = $row['user_site'];
            $this->discountGift = $row['discount_gift'];
        }

        return $this;
    }

    /**
     * Провіряє назву полів які прийшли від користувача, чи поле є в списку дозволених полів.
     * @param string $fieldName
     * @return bool
     */
    public function checkField($fieldName)
    {
        return in_array($fieldName, $this->fieldsColums);
    }

    /**
     * Провіряє чи збігаються паролі з БД і з форми.
     * @param string $password
     * @return bool
     */
    public function checkPassword($password)
    {
        return md5($password) === $this->password;
    }

    /**
     * Провіряє новий пароль на наявність пробілів і довжину.
     * @param string $password
     * @return int 1|0
     */
    public function validatePassword($password)
    {
        return preg_match(self::REGEXP_PASSWORD, $password);
    }

    /**
     * Провіряє телефон чи нема лишніх симфолів. Дозволені 0-9 + ) ( -
     * @param $phone
     * @return int 0|1
     */
    public function validatePhone($phone)
    {
        return preg_match(self::REGEXP_PHONE, $phone);
    }

    /**
     * Провіряє на наявність тільки букв кирилиці.
     * @param string $city
     * @return int 0|1
     */
    public function validateCity($city)
    {
        return preg_match(self::REGEXP_CITY_COUNTRY, $city);
    }

    /**
     * Провіряє чи да в форматі YYYY-MM-DD.
     * @param string $birthDay
     * @return int 0|1
     */
    public function validateBirthDay($birthDay)
    {
        return preg_match(self::REGEXP_BIRTHDAY, $birthDay);
    }

    /**
     * Відправляє ел. повідомлення.
     * @param $theme string тема повідомлення
     * @return $this
     */
    public function sendMail($theme)
    {
        Mail::send($this->email, $theme, $this->emailMessage);

        return $this;
    }

    /**
     * Генерує пароль довжиною 7-9 символів, в діапазон входять A-Z, a-z, 0-9.
     * @return $this
     */
    public function generatePassword()
    {
        $desiredLength = rand(7, 9);

        for ($length = 0; $length < $desiredLength; $length++) {
            $random = rand(1, 3);
            switch ($random) {
                case 1:
                    $this->generatedPassword .= chr(rand(48, 57));
                    break;
                case 2:
                    $this->generatedPassword .= chr(rand(65, 90));
                    break;
                case 3:
                    $this->generatedPassword .= chr(rand(97, 122));
                    break;
            }
        }

        return $this;
    }

    /**
     * Створення тексту ел. повідомлення з паролем.
     * @return $this
     */
    public function createEmailMessageWithPassword($prefix = '../../../')
    {
        $this->template->set_tpl(
            '{$generatedPassword}', $this->generatedPassword
        );

        $this->template->set_tpl(
            '{$mailContent}',
            $this->template->get_tpl(
                'mail.userPassword', $prefix
            )
        );

        $this->emailMessage = $this->template->get_tpl('mail.main', $prefix);

        return $this;
    }

    /**
     * Створення тексту ел. повідомлення з лінком на підтвердження реєстрації.
     * @return $this
     */
    public function createEmailMessageWithConfirmLink($prefix = '../../../')
    {
        $this->template->set_tpl(
            '{$generateConfirmLink}', $this->generateConfirmLink()
        );

        $this->template->set_tpl(
            '{$mailContent}',
            $this->template->get_tpl(
                'mail.confirmEmail', $prefix
            )
        );

        $this->emailMessage = $this->template->get_tpl('mail.main', $prefix);

        return $this;
    }

    /**
     * Редірект на іншу сторінку.
     * @param $page string адреса сторінки
     */
    public function redirectTo($page)
    {
        header('Location: http://'.$_SERVER['HTTP_HOST'].$page);
        exit;
    }

    /**
     * Провіряє чи каптча була вибрана.
     * @return bool
     */
    public function isCaptchaChecked()
    {
        $response   = filter_input(
            INPUT_POST, 'g-recaptcha-response', FILTER_SANITIZE_STRING
        );
        $secret     = '6LcTARcTAAAAAHsH5RIaL1j6SQLe-La0dw2kP32O';
        $parameters = 'secret='.$secret.'&response='.$response;
        $url        = 'https://www.google.com/recaptcha/api/siteverify';

        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result);

        return $result->success;
    }

    /**
     * Повідомлення про підтвердження ел. пошти.
     * @return string
     */
    public function getMessageToConfirmRegistration()
    {
        return <<<MESSAGE
            Для того чтобы завершить регистрацию, пожалуйста,
            перейдите по ссылке, которую мы Вам только что выслали.
            Если не найдёте письма в почте, проверьте Спам.

MESSAGE;
    }

    /**
     * Виводить вже сформований резултат в json форматі
     * @return $this
     */
    public function showResult()
    {
        echo json_encode(array(
            'success' => (int) $this->ajaxResult,
            'message' => $this->ajaxMessage
        ));

        return $this;
    }

    /**
     * Створення лінка на базі ід користувача та його ел. адреси, для підтвердження реєстрації.
     * @return string
     */
    protected function generateConfirmLink()
    {
        $url = md5($this->email).md5($this->id);
        $url = "&user={$this->id}&code={$url}";
        return $_SERVER['HTTP_HOST'].'/confirmation'.urlencode($url).'/';
    }
}