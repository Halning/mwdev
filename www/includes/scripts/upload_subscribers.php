<?php
/**
 * Created by PhpStorm.
 * Date: 05.04.16
 * Time: 16:41
 */

namespace Modules;

require_once('../../vendor/autoload.php');


$i = 11;
$nameOrder = 0;
$phoneOrder = 0;
$emailOrder = 1;
$amountOrders = 0;
$sumOrders = 0;
$uploader = new SubscribeUpload($nameOrder, $phoneOrder, $emailOrder, $amountOrders, $sumOrders);

$orderr = array(
//   74
);
foreach ($orderr as $i) {
    $file = 'table' . $i . 'xlsx';
    $inputFileType      = \PHPExcel_IOFactory::identify($file);
    $objReader          = \PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel        = $objReader->load($file);
    $name = $objPHPExcel->getSheetNames()[0];
    $uploader->getSupplierId($name);
    $workSheet = $objPHPExcel->getSheet(0)->toArray();

    $workSheetFiltered = array_filter($workSheet, function($item) use ($emailOrder) {
        return filter_var($item[$emailOrder], FILTER_VALIDATE_EMAIL);
    });

    $uploader->arraySubscribersLength = count($workSheetFiltered);
//    $uploader->addSubscribers($workSheetFiltered);
}
































