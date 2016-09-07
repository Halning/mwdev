<?php
require_once("../../settings/conf.php");
require_once("../../settings/connect.php");
require_once("../../settings/functions.php");

bd_session_start();

if ($_SESSION["chat_id"]) {
    echo $_SESSION["chat_id"];
} else {
    $today               = date("Y-m-d H:i:s");
    $today               = str_replace(" ", "", $today);
    $today               = str_replace("-", "", $today);
    $today               = str_replace(":", "", $today);
    $_SESSION["chat_id"] = $today;
    echo $_SESSION["chat_id"];
}