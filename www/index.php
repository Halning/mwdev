<?php

header('Content-Type: text/html; charset=utf-8');
$time1 = microtime(1);

require_once('vendor/autoload.php');
require_once('settings/conf.php');
require_once('settings/functionsNew.php');

bd_session_start();

require_once('settings/main.php');
require_once('modules/modules.php');
include('settings/select_page.php');

//if pjax, echo only needle HTML cod
if (true === filter_input(INPUT_SERVER, 'HTTP_X_PJAX', FILTER_VALIDATE_BOOLEAN)) {
    echo $center;
    exit;
}

require_once('settings/generate_page.php');
