<?php
/**
 * Created by PhpStorm.
 * Date: 8/27/15
 * Time: 1:15 PM
 */

namespace  Modules;

require_once('../../../vendor/autoload.php');
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");

bd_session_start();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
if ($id && $type) {
    $like = new Like($id, $type);
    $like->putLike();
    echo json_encode($like->getCounter());
}