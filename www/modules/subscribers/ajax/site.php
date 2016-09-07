<?php

/**
 * Created by PhpStorm.
 * Date: 25.03.16
 * Time: 15:00
 */

namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');

bd_session_start();

$success = 0;
$message = '';

if ('GET' === $_SERVER['REQUEST_METHOD']) {
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

    switch ($action) {

        case 'letter-preview':

            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if ($id) {
//                echo @file_get_contents("../templates/mail.template$id.tpl");
                $blobStorage = new BlobStorage();
                echo $blobStorage->getBlob(SubscribeLetters::STORAGE_CONTAINER, $id.'.tpl');
            } else {
                echo 'Не верный id.';
            }

            exit;
            break;
    }



} elseif ('POST' === $_SERVER['REQUEST_METHOD']) {
    $event = filter_input(INPUT_POST, 'event', FILTER_SANITIZE_STRING);
    switch ($event) {
        case 'delete-subscribers':
            if (is_array($_POST['items']) && count($_POST['items'] > 0) && 'admin' === $_SESSION['status']) {
                $usersIds = array();
                $subscribersIds = array();
                $subscribe = new Subscribe();

                foreach ($_POST['items'] as $item) {

                    if (2 === (int)$item[1]) {
                        $usersIds[] = $item[0];
                        continue;
                    }

                    $subscribersIds[] = $item[0];
                }

                if (count($usersIds) > 0) {
                    $subscribe->unsubscribeUsers($usersIds);
                }

                if (count($subscribersIds) > 0) {
                    $subscribe->deleteSubscribers($subscribersIds);
                }
            }
            break;

        case 'delete-letter':

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $letters = new SubscribeLetters();
                $success = $letters
                    ->deleteTemplateFile($id)
                    ->deleteLetter($id)->ajaxResult;

            }
            break;
    }
}

echo json_encode(
    array(
        'success' => $success,
        'message' => $message,
    )
);
