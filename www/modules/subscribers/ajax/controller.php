<?php
/**
 * Created by PhpStorm.
 * Date: 07.05.16
 * Time: 19:15
 */

namespace Modules;

require_once('vendor/autoload.php');

if ('POST' === $_SERVER['REQUEST_METHOD'] &&
    $event = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING)
) {
    switch ($event) {
        case 'subscribe':
            if ('XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH']) {

                //TODO - захист від роботів
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

                $success = 0;

                if ($email && mb_strlen($email, 'utf-8') < 64 ) {

                    $subscribe = new Subscribe(Subscribe::SUBSCRIBE_LANDING);
                    $success = $subscribe
                        ->subscribe($email)
                        ->result;
                }

                echo json_encode(
                    array(
                        'success' => $success,
                    )
                );

                exit;
            }

            break;
    }
} else {
    header('HTTP/1.0 404 Not Found');

    if ('XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH']) {
        exit;
    }

    $center = '';
}
