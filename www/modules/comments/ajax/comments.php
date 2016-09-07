<?php
/**
 * Created by PhpStorm.
 * Date: 21.01.16
 * Time: 12:20
 */

namespace Modules;

require_once("../../../vendor/autoload.php");
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");
require_once("../../../settings/main.php");

bd_session_start();

global $glb;

$comments = new Comments($glb['templates']);

//якщо метод гет то повертаємі всі коменти, якщо пост то записуєм комент
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $comments->itemId = filter_input(INPUT_GET, 'item_id', FILTER_VALIDATE_INT);

    if ($comments->itemId) {
        $comments->get();
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //провірка вхідних даних
    if ($comments->initPostData()) {
        $comments->add();
    }
}

$comments->showResult();
