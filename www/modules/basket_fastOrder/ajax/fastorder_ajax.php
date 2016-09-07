<?php

namespace Modules;

require_once("../../../vendor/autoload.php");
require_once("../../../settings/functionsNew.php");
bd_session_start();
require_once("../../../settings/main.php");

global $glb;

$fastOrder = new FastOrder($glb["cur_id"], $glb['cur_val'], $glb['cur_val_bax'],
    $glb["dom_mail"], $glb['templates']);

$fastOrder->userEmail = filter_input(INPUT_POST, 'fast_order_email',
    FILTER_SANITIZE_STRING);

if ($fastOrder->userEmail) {
    $fastOrder->userCountry        = filter_input(INPUT_POST,
        'fast_order_country', FILTER_VALIDATE_INT);
    $fastOrder->userName           = filter_input(INPUT_POST, 'fast_order_name',
        FILTER_SANITIZE_STRING);
    $fastOrder->userCity           = filter_input(INPUT_POST, 'fast_order_city',
        FILTER_SANITIZE_STRING);
    $fastOrder->userPhone          = filter_input(INPUT_POST,
        'fast_order_phone', FILTER_SANITIZE_STRING);
    $fastOrder->userAddress        = filter_input(INPUT_POST,
        'fast_order_address', FILTER_SANITIZE_STRING);
    $fastOrder->userComment        = filter_input(INPUT_POST,
        'fast_order_comment', FILTER_SANITIZE_STRING);
    $fastOrder->comColor           = filter_input(INPUT_POST,
        'fast_order_color', FILTER_SANITIZE_STRING);
    $fastOrder->comSize            = filter_input(INPUT_POST, 'fast_order_size',
        FILTER_SANITIZE_STRING);
    $fastOrder->comCount           = filter_input(INPUT_POST,
        'fast_order_count', FILTER_VALIDATE_INT);
    $fastOrder->comId              = filter_input(INPUT_POST,
        'fast_order_comid', FILTER_VALIDATE_INT);
    $fastOrder->deliveryMethodId   = filter_input(INPUT_POST,
        'fast_order_delivery_method', FILTER_SANITIZE_STRING);
    $fastOrder->deliveryMethodName = filter_input(INPUT_POST,
        'delivery_method_name', FILTER_SANITIZE_STRING);

    $fastOrder->mainFastOrderAction();
} else {
    $fastOrder->success = 0;
}

echo json_encode(array(
    'success' => $fastOrder->success,
    'finalPage' => $fastOrder->finalPageFastOrder,
));
