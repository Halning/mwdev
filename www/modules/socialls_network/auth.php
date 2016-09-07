<?php

namespace Modules;

require_once '../../vendor/autoload.php';
require_once '../../settings/functionsNew.php';

bd_session_start();

$provider = filter_input(INPUT_GET, 'provider', FILTER_SANITIZE_STRING);

if ($provider && in_array($provider, array('vk', 'fb', 'ok', 'go'))) {

    //сетим сесію для редіректа юзера на сторінку з якої він прийшов
    preg_match('/http:\/\/[^\/]+(.*)/', $_SERVER['HTTP_REFERER'], $math);
    if (count($math) > 1) {
        $_SESSION['login_refer_page'] = $math[1];
    }

    //видаляєм з БД не активованих користувачів, які були створені більше тижня тому.
    MySQLi::getInstance()->getConnect()->query(
        <<<QUERY
            DELETE FROM users
            WHERE activated = 0
            AND (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(user_registred_date)) > 3600 * 24 * 7
QUERY
    );

    $url = SocialFactory::createUrl($provider);
    header("Location: " . $url);
} else {
    header("Location: http://".$_SERVER["HTTP_HOST"]);
}
