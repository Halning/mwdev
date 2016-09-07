<?php
/**
 * Date: 9/23/15
 * Time: 8:50 PM
 */

namespace Modules;

header("Content-Type: application/x-javascript; charset=UTF-8");

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/functionsNew.php');

bd_session_start();

global $glb;

$result = '';
$email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);
$message = filter_input(INPUT_GET, 'message', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

if ($email && $message) {

    $ip = $_SERVER['REMOTE_ADDR'];
    $result = MySQLi::getInstance()->getConnect()->query(<<<QUERY
        INSERT INTO messages
        SET
          email = '{$email}',
          comment_ip = '{$ip}',
          user_id = '{$_SESSION['user_id']}',
          message = '{$message}',
          url = '{$_SERVER['HTTP_REFERER']}',
          data = NOW()
QUERY
    );

    if ($result) {
        $date = (new \DateTime('now'))->format('Y-m-d H:i:s');
        $resultMessage = 'Спасибо, ваше сообщение отправлено нам.';
        $mailText = <<<MAIL
            На странице {$_SERVER["HTTP_REFERER"]} добавлен отзыв.<br>
            -------------------------------------------------------<br>
            E-mail: {$email}<br>
            Комментарий: {$message}<br>
            -------------------------------------------------------<br>
            IP: {$ip}<br>
            Время: {$date}<br>
MAIL;
        Mail::send(
            'info@makewear.com.ua',
            'Добавлен новый отзыв',
            $mailText,
            'noreply@'.$glb['dom_mail']
        );
    } else {
        $resultMessage = 'Извините, сейчас мы не можем обработать Ваш запрос.';
    }
} else {
    $resultMessage = 'Пожалуйста, заполните все поля!';
}

echo json_encode(
    array(
        'success' => (int) $result,
        'message' => $resultMessage,
    )
);
