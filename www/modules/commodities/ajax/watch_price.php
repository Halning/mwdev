<?php
/**
 * Created by PhpStorm.
 * User: volodini
 * Date: 9/16/15
 * Time: 3:37 PM
 */
namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/functionsNew.php');


bd_session_start();

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$event = filter_input(INPUT_POST, 'event', FILTER_SANITIZE_STRING);

if ($id && $email && in_array($event, array('check', 'add', 'del'))) {

    $watch = new WatchPrice($email, $id);

    switch ($event) {
        case 'add':
            //записуєм ел. пошту в сесію
            if (!$_SESSION['user_email']) {
                $_SESSION['user_email'] = $email;
            }

            $watch->checkOnWatch();
            if (!$watch->alreadyWatched) {
                $watch->addToWatches();
            }
            break;
        case 'del':
            $watch->deleteFromWatched();
            break;
        case 'check':
            $watch->checkOnWatch();
            break;
    }

    $watch->showResult();

} else {
    echo json_encode(array(
        'active' => false,
        'success' => false,
    ));
}
