<?php

namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/functionsNew.php');

bd_session_start();

global $theme_name;

$theme_name = 'shop';
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

    //ресетить сесію зологіненого К.
    if ($action === 'quit') {
        $user->logOut()->ajaxResult = 1;
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
    $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $userStatus = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
//    $rememberMe = filter_input(INPUT_POST, 'remember_me', FILTER_VALIDATE_BOOLEAN);

    $user->mysqli = MySQLi::getInstance()->getConnect();

    if ($action == 'sign_in') {

        $user->email = $email;
        if ($email &&
            $password &&
            $user->getUsersByEmail() &&
            $user->existsUser->user_password === md5($password)
        ) {

            $user->id = $user->existsUser->user_id;
            $user->name = $user->existsUser->user_name;
            $user->realName = $user->existsUser->user_realname;
            $user->status = $user->existsUser->user_admin == 1 ? 'admin' : 'user';

            $user->logIn()->ajaxResult = 1;
        } else {
            $user->ajaxMessage = 'Комбинация пароль логин не совпадают.';
        }

    } elseif ($action == 'register') {


        if ($email &&
            $password &&
            $firstName &&
            $lastName
        ) {

            //captcha
            if ($user->isCaptchaChecked()) {

                $user->email = $email;

                if ($user->getUsersByEmail()) {
                    $user->ajaxMessage = 'Email адрес уже занят.';
                } elseif ($user->getUsersByEmailNotActivated()) {
                    $user
                        ->updateUser($firstName, $lastName, $password)
                        ->getId()
                        ->createEmailMessageWithConfirmLink()
                        ->sendMail($user::MAIL_THEME_CONFIRM)
                        ->ajaxMessage = $user->getMessageToConfirmRegistration();
                } else {
                    $user->createUser($firstName, $lastName, $password, $userStatus)
                        ->createEmailMessageWithConfirmLink()
                        ->sendMail($user::MAIL_THEME_CONFIRM)
                        ->ajaxMessage = $user->getMessageToConfirmRegistration();
                }
            } else {
                $user->ajaxMessage = 'Каптча не выбрана.';
            }
        } else {
            $user->ajaxMessage = 'Пожалуйста, заполните все поля!';
        }
    }
}

$user->showResult();
