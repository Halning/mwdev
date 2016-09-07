<?php

require_once('../../vendor/autoload.php');
require_once('../../settings/functionsNew.php');

use Modules\SocialUser;

bd_session_start();

$code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
$client_id = '1234604800'; // Application ID
$public_key = 'CBAEJHJKEBABABABA'; // Публичный ключ приложения
$client_secret = '754BF02770A2001D758B7729'; // Секретный ключ приложения
$redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].'/modules/socialls_network/ok_api.php'; // Ссылка на приложение

if ($code) {

    $params = array(
        'code' => $code,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code',
        'client_id' => $client_id,
        'client_secret' => $client_secret
    );

    $url = 'http://api.odnoklassniki.ru/oauth/token.do';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url); // url, куда будет отправлен запрос
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params))); // передаём параметры
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);
    curl_close($curl);

    $tokenInfo = json_decode($result, true);

    if (isset($tokenInfo['access_token']) && isset($public_key)) {
        $sign = md5("application_key={$public_key}format=jsonmethod=users.getCurrentUser" . md5("{$tokenInfo['access_token']}{$client_secret}"));

        $params = array(
            'method'          => 'users.getCurrentUser',
            'access_token'    => $tokenInfo['access_token'],
            'application_key' => $public_key,
            'format'          => 'json',
            'sig'             => $sign
        );

        $userInfo = json_decode(file_get_contents('http://api.odnoklassniki.ru/fb.do' . '?' . urldecode(http_build_query($params))), true);

        $user = array();
        $user['image'] = $userInfo["pic_1"];
        $user['name'] = explode(' ', $userInfo['name'])[0];
        $user['email'] = '';
        $user['realname'] = explode(' ', $userInfo['name'])[1];
        $user['soc_page'] = '';
        $user['soc_id'] = $userInfo["uid"];
        $user['soc_provider'] = 'ok';
        $user['birthday'] = (new DateTime($userInfo["birthday"]))->format('d.m.Y');

        if ($userInfo["gender"] == 'female') {
            $user['sex'] = 2;
        } elseif ($userInfo["gender"] == 'male') {
            $user['sex'] = 1;
        } else {
            $user['sex'] = 0;
        }

        $socUser = new SocialUser($user);

        if (is_numeric($socUser->checkExistUser())) {

            if ($socUser->isActivated()) {

                $page = !empty($_SESSION['login_refer_page']) ? $_SESSION['login_refer_page'] :
                    $socUser::PAGE_MAIN;
                $socUser->logIn()
                    ->redirectTo($page);
            }

        } else {
            $socUser->createUser();
        };

        $socUser->setSessionsConfirmId()
            ->redirectTo($socUser::PAGE_SHOW_CONFIRM);
    }
}