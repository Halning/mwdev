<?php

require_once('../../vendor/autoload.php');
require_once('../../settings/functionsNew.php');

use Modules\SocialUser;

bd_session_start();

define("CLIENT_ID","1699070286981354");
define("SECRET","0319dbd855e548cda676bc5c1be1b2dc");
define("REDIRECT","http://".$_SERVER['HTTP_HOST']."/modules/socialls_network/fb_api.php");
define("TOKEN","https://graph.Facebook.com/oauth/access_token");
define("GET_DATA","https://graph.Facebook.com/me");

function get_token($code) {

    $ku = curl_init();
    $query = "client_id=".CLIENT_ID."&redirect_uri=".urlencode(REDIRECT)."&client_secret=".SECRET."&code=".$code;
    curl_setopt($ku,CURLOPT_URL,TOKEN."?".$query);
    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
    curl_setopt($ku, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ku);

    if (!$result) {
        exit(curl_error($ku));
    }

    if ($i = json_decode($result)) {
        if ($i->error) {
            exit($i->error->message);
        }
    } else {
        parse_str($result,$token);

        if ($token['access_token']) {
            return $token['access_token'];
        }
    }
}

function get_data($token) {
    $ku = curl_init();
    $query = "fields=name,email,birthday,gender,cover&access_token=".$token;
    curl_setopt($ku,CURLOPT_URL,GET_DATA."?".$query);
    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
    curl_setopt($ku, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ku);

    if(!$result) {
        exit(curl_error($ku));
    }

    return json_decode($result, true);
}

$code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);

if (isset($code)) {
    $token =  get_token($code);
    $userInfo = get_data($token);
    $user = array();
    $user['image'] = '';
    $user['name'] = explode(' ', $userInfo['name'])[0];
    $user['email'] = $userInfo['email'];
    $user['realname'] = explode(' ', $userInfo['name'])[1];
    $user['soc_page'] = '';
    $user['soc_id'] = $userInfo["id"];
    $user['soc_provider'] = 'fb';
    $user['birthday'] = (new DateTime($userInfo["birthday"]))->format('d.m.Y');

    if ($userInfo["gender"] == 'female') {
        $user['sex'] = 2;
    } elseif ($userInfo["gender"] == 'male') {
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
        if ($socUser->emailExists()) {
            ///якщо по соц. ід користувачів не знайдено то провіряєм чи є в БД активований користувач з даною ел. адресою
            if ($socUser->getUsersByEmail()) {

                //якщо якщо існує то добавити дані з см до існуючого
                $socUser->updateExistsUser()
                    ->logIn()
                    ->deleteUserByEmail()
                    ->redirectTo($socUser::PAGE_CABINET);
            } else {

                if ($socUser->getUsersByEmailNotActivated()) {
                    $socUser->deleteUserByEmail();
                }

                //якщо ні то generate pass, create user, актівейт, відправити згенерований пасс,
                //логін, удаляєм всі не активовані рядки з даною ел. адресою, редірект в лк.
                $socUser->generatePassword()
                    ->createUser(true)
                    ->createEmailMessageWithPassword('../../')
                    ->sendMail($socUser::MAIL_THEME_PASS)
                    ->logIn()
                    ->deleteUserByEmail()
                    ->redirectTo($socUser::PAGE_CABINET);
            }
        } else {

            ///якщо по соц. ід користувачів не знайдено то створюєм в БД нового користувача.
            $socUser->createUser();
        }
    }

    //сетим ід користувача в сесію, редіректим на форму запиту емейла
    $socUser->setSessionsConfirmId()
        ->redirectTo($socUser::PAGE_SHOW_CONFIRM);
}