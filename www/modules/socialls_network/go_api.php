<?php

require_once('../../vendor/autoload.php');
require_once('../../settings/functionsNew.php');

use Modules\SocialUser;

bd_session_start();

$client_id = "1054802775236-hravmmufc7bq8ggsmfpekhmk8sahhm32.apps.googleusercontent.com";
$client_secret = "CBHR8Zk6vLQYCqjrUiQ8GV3D";
$redirect_uri = "http://".$_SERVER['HTTP_HOST']."/modules/socialls_network/go_api.php";
$url = 'https://accounts.google.com/o/oauth2/auth';

$code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);

if ($code) {

    $params = array(
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri'  => $redirect_uri,
        'grant_type'    => 'authorization_code',
        'code'          => $_GET['code']
    );

    $url = 'https://accounts.google.com/o/oauth2/token';
}

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($curl);
curl_close($curl);

$tokenInfo = json_decode($result, true);

if (isset($tokenInfo['access_token'])) {

    $params['access_token'] = $tokenInfo['access_token'];
    $userInfo = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo' . '?' . urldecode(http_build_query($params))), true);

    $user = array();
    $user['image'] = $userInfo['picture'];
    $user['name'] = explode(' ', $userInfo['name'])[0];
    $user['email'] = $userInfo['email'];
    $user['realname'] = explode(' ', $userInfo['name'])[1];
    $user['soc_page'] = $userInfo['link'];
    $user['soc_id'] = $userInfo["id"];
    $user['soc_provider'] = 'go';
    $user['birthday'] = '';

    if ($userInfo["gender"] == 'female') {
        $user['sex'] = 2;
    } elseif ($userInfo["gender"] == 'male') {
        $user['sex'] = 1;
    } else {
        $user['sex'] = 0;
    }

    $socUser = new SocialUser($user);

    if (is_numeric($socUser->checkExistUser())) {

        $page = !empty($_SESSION['login_refer_page']) ? $_SESSION['login_refer_page'] :
            $socUser::PAGE_MAIN;
        $socUser->logIn()
            ->redirectTo($page);
    } else {
        if ($socUser->getUsersByEmail()) {

            //якщо вже мейл існує то добавити дані з см до існуючого
            $socUser->updateExistsUser()
                ->logIn()
                ->deleteUserByEmail()
                ->redirectTo($socUser::PAGE_CABINET);
        } else {

            //якщо ні то generate pass, create user, актівейт,
            //плюс відправити згенерований пасс, редірект в лк.
            $socUser->generatePassword()
                ->createUser(true)
                ->createEmailMessageWithPassword('../../')
                ->sendMail($socUser::MAIL_THEME_PASS)
                ->logIn()
                ->deleteUserByEmail()
                ->redirectTo($socUser::PAGE_CABINET);
        }
    };
}