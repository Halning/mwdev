<?php
$admin = filter_input(INPUT_GET, 'admin', FILTER_SANITIZE_STRING);
if (($_SESSION['status'] == "admin") && (isset($admin))) {

} else if (!isset($admin)) {
    require_once("modules/basket_fastOrder/site/functionsNew.php");
    $url_page["basket"] = "getPjaxBasketPage";
}