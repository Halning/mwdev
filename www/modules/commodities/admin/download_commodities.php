<?php
/**
 * Created by PhpStorm.
 * Date: 05.04.16
 * Time: 12:31
 */

namespace Modules\Prices;

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="products.xls"');
header('Cache-Control: max-age=0');
$feeds = new FeedsLoad();

if ('feeds' === filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING)) {
    $products = $feeds->getAllProducts();
} else {
    $feeds->products[0] = array(
        'Категория',
        'Название',
        'Описание',
        'Цвет',
        'Размер'
    );
    $products = $feeds->getAllProducts1();
}

$objPHPExcel = new \PHPExcel();
$objPHPExcel->getActiveSheet()->fromArray($products);

$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
