<?php


require_once('../../vendor/autoload.php');
require_once('../../settings/functionsNew.php');

use Modules\SocialUser;

bd_session_start();

$code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
if ($code) {

    $params = array(
        'client_id' => '5151626',
        'client_secret' => 'mwQ33xeFBnqdihyu7SXP',
        'code' => $code,
        'redirect_uri' => 'http://'.$_SERVER['HTTP_HOST'].'/modules/socialls_network/vk_api.php'
    );

    $token = json_decode(
        file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))),
        true
    );

    if (is_array($token) && count($token) > 0) {

        $params2 = array(
            'user_id' => $token["user_id"],
            'v' => "5.37",
            'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
            'access_token' => $token["access_token"]
        );

        $userInfo = json_decode(
            file_get_contents("https://api.vk.com/method/users.get?".urldecode(http_build_query($params2))),
            true
        );

        $user = array();
        $user['image'] = $userInfo["response"][0]["photo_big"];
        $user['name'] = $userInfo["response"][0]["first_name"];
        $user['email'] = '';
        $user['realname'] = $userInfo["response"][0]["last_name"];
        $user['soc_page'] = $userInfo["response"][0]["screen_name"];
        $user['soc_id'] = $userInfo["response"][0]["id"];
        $user['soc_provider'] = 'vk';
        $user['birthday'] = (new DateTime($userInfo["response"][0]["bdate"]))->format('d.m.Y');

        if ($userInfo["response"][0]["sex"] == 1) {
            $user['sex'] = 2;
        } elseif ($userInfo["response"][0]["sex"] == 2) {
            $user['sex'] = 1;
        } else {
            $user['sex'] = 0;
        }


        $socUser = new SocialUser($user);

        //провіряєм чи в БД вже є користувач з даним соц. ід
        if (is_numeric($socUser->checkExistUser())) {

            //якщо існує  то провіряєм чи він активиований
            if ($socUser->isActivated()) {
                $page = !empty($_SESSION['login_refer_page']) ? $_SESSION['login_refer_page'] :
                    $socUser::PAGE_MAIN;
                //якщо активований то логіним і редіректим в ЛК.
                $socUser->logIn()
                    ->redirectTo($page);
            }
        } else {

            ///якщо по соц. ід користувачів не знайдено то створюєм в БД нового користувача.
            $socUser->createUser();
        };

        //сетим ід користувача в сесію, редіректим на форму запиту емейла
        $socUser->setSessionsConfirmId()
            ->redirectTo($socUser::PAGE_SHOW_CONFIRM);
    }
}
