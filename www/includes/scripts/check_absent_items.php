<?php
require_once ("../phpexcel/Classes/PHPExcel.php");
require_once ('../simplehtmldom/simple_html_dom.php');
include 'Glem.php';
include 'Vitality.php';
echo "<meta charset='utf-8'";
$glem = new Glem();
$glem->setSizes();
$glem->hideNotActualOffers();
$glem->setInterfaceComplete();
ini_set("max_execution_time", "99999");
set_time_limit(99999);
$ids = [
    [88, 30],
    [205, 31]
];
foreach ($ids as $id) {
    $vitality = new Vitality($id[0], $id[1]);
    $vitality->getAllSizes();
    $vitality->hideNotActualOffers();
    $vitality->initNotDefined();
    $vitality->setInterfaceComplete();
}