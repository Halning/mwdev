<?php
/**
 * Created by PhpStorm.
 * User: webler
 * Date: 30.03.16
 * Time: 10:51
 */

namespace Modules;


class SubscribeLetters
{
    const MESSAGE_SUCCESS_ID = 'positive';
    const MESSAGE_ERROR_ID   = 'tip';
    const STORAGE_CONTAINER  = 'email-letters';

    public $letter;
    public $letters = array();
    public $currentPage = 1;
    public $itemsPerPage = 10;
    public $ajaxResult = 0;
    public $id;
    private $amountPages;
    private $db;

    public function __construct()
    {
        $this->db = MySQLi::getInstance()->getConnect();
    }

    public function addLetter($name)
    {
        $stmt = $this->db->prepare(<<<QUERYADD
            INSERT INTO subscribe_letters (name)
            VALUES (?)
QUERYADD
);
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->close();
        $this->id = $this->db->insert_id;

        return $this;
    }

    public function setTemplateFile($content)
    {
        $filePath = "modules/subscribers/templates/mail.template{$this->id}.tpl";
        $this->ajaxResult = @file_put_contents($filePath, $content);
        $blobStorage = new BlobStorage();
        $blobStorage->uploadBlob($filePath, $this->id.'.tpl', self::STORAGE_CONTAINER);
    }

    public function deleteTemplateFile($id)
    {
        $filePath = "../templates/mail.template$id.tpl";
        unlink($filePath);
        $blobStorage = new BlobStorage();
        $blobStorage->deleteBlob(self::STORAGE_CONTAINER, $id.'.tpl');

        return $this;
    }

    public function getLetterContent($id)
    {
        return @file_get_contents("modules/subscribers/templates/mail.template$id.tpl");
    }

    public function getLetter($id)
    {
        $result = $this->db->query(<<<QUERYL
            SELECT
              id, name
            FROM subscribe_letters
            WHERE id = $id
            LIMIT 1
QUERYL
        );

        if ($result && $result->num_rows > 0) {
            $this->letter = $result->fetch_object();
        }

        return $this;
    }

    public function getLetters($pager = true)
    {
        $startPage = 1 == $this->currentPage ? 0 : $this->itemsPerPage * ($this->currentPage - 1);
        $limit = $pager ? "LIMIT {$startPage}, {$this->itemsPerPage}" : '';

        $result = $this->db->query(<<<QUERYL
            SELECT
              SQL_CALC_FOUND_ROWS id, name, path
            FROM subscribe_letters
            $limit
QUERYL
        );

        $totalAmount = $this->db
            ->query("SELECT FOUND_ROWS() as rows")
            ->fetch_object()
            ->rows;
        $this->amountPages = ceil($totalAmount/$this->itemsPerPage);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $row->path = '/templates/mail.template' . $row->id . '.tpl';
                $this->letters[] = $row;
            }
        }

        return $this;
    }

    public function updateLetter($id, $name)
    {
        $stmt = $this->db->prepare(<<<QUERYUP
            UPDATE subscribe_letters
            SET
              name = ?
            WHERE id = ?
QUERYUP
        );

        $stmt->bind_param('si', $name, $id);
        $stmt->execute();
        $stmt->close();

        return $this;
    }

    public function deleteLetter($id)
    {
        $stmt = $this->db->prepare(
            <<<QUERYDEL
                DELETE FROM subscribe_letters
                WHERE id = ?
QUERYDEL
        );

        $stmt->bind_param('i', $id);
        $this->ajaxResult = $stmt->execute();
        $stmt->close();

        return $this;
    }

    public function getLettersOptions()
    {
        $lettersOptions = '';
        foreach ($this->letters as $letter) {
            $lettersOptions .= "<option value='{$letter->id}'>{$letter->name}</option>";
		}

        return $lettersOptions;
    }

    public function generatePages()
    {
        $pages = '';

        if ($this->amountPages > 1) {
            for ($i = 1; $i <= $this->amountPages; $i++) {
                $pages .= $this->currentPage == $i ?
                    "<span style='text-decoration: underline'> $i </span>" :
                    "<a href='/?admin=subscribe&action=templates&p={$i}'> $i </a>";
            }
        }

        return "<div class='subscribers-pages'>$pages</div>";
    }

    public function getResultMessage($message, $type, $returnUrl = 'send')
    {
        return <<<HTMLSUCC
<div id='$type'>
    <table width='450' cellpadding='0' cellspacing='12'>
        <tr>
            <td width='52'>
                <div align='center'>
                    <img src='/templates/admin/images/positive.png' alt='positive' width='22' height='16' />
                </div>
            </td>
            <td width='388' class='bodytext style3'>
                {$message}
                <br />
                <a href='/cmsadmin/'><b>Вернуться на главную страницу<b></a>
            </td>
        </tr>
    </table>
</div>
<script>
    setTimeout(function() {
        location.pathname = '?admin=subscribe&action=$returnUrl';
        }, 10000);
</script>
HTMLSUCC;
    }
}
