<?php
/**
 * Created by PhpStorm.
 * Date: 18.02.16
 * Time: 14:56
 */

namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/functionsNew.php');

bd_session_start();

if (is_numeric($_SESSION['user_id'])) {

    $user = new User($_SESSION['user_id']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {


        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

        $user->mysqli = MySQLi::getInstance()->getConnect();
        $user->getData();

        switch ($action) {

            //зміна паролю
            case 'change-password':
                $currentPassword    = filter_input(INPUT_POST,
                    'current-password', FILTER_SANITIZE_STRING);
                $newPassword        = filter_input(INPUT_POST, 'new-password',
                    FILTER_SANITIZE_STRING);
                $confirmNewPassword = filter_input(INPUT_POST,
                    'confirm-new-password', FILTER_SANITIZE_STRING);

                if (!$user->checkPassword($currentPassword)) {
                    $user->ajaxMessage = 'Введен неверный текущий пароль';
                } elseif ($newPassword !== $confirmNewPassword) {
                    $user->ajaxMessage = 'Пароли не совпадают';
                } elseif (!$user->validatePassword($newPassword)) {
                    $user->ajaxMessage = 'Ваш пароль должен быть не меннее 5 символов и не содержать пробелы';
                } else {
                    $user
                            ->setPassword($newPassword)
                        ->ajaxMessage = 'Пароль успешно изменен';
                }
                break;

            //зміна інших полів
            case 'update-field':
                $notValidate = false;
                $fieldName  = filter_input(INPUT_POST, 'name',
                    FILTER_SANITIZE_STRING);
                $fieldValue = filter_input(INPUT_POST, 'value',
                    FILTER_SANITIZE_STRING);

                if ($fieldName && $fieldValue && $user->checkField($fieldName)) {

                    //валідація дати і телефону
                    if (($fieldName === 'phone' && !$user->validatePhone($fieldValue)) ||
                        ($fieldName === 'birthday' && !$user->validateBirthDay($fieldValue))
                    ) {
                        $notValidate = true;
                    }

                    $fieldName = array_search($fieldName, $user->fieldsColums);
                    if ($fieldName && !$notValidate) {
                        $user->setValue($fieldName, $fieldValue);
                    }
                }

                break;
        }
    }

    $user->showResult();
}
