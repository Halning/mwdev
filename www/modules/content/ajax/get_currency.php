<?php
/**
 * Created by PhpStorm.
 * Date: 12/5/15
 * Time: 10:35 AM
 */


require('../../../vendor/autoload.php');
require('../../../settings/functionsNew.php');

bd_session_start();

$currency = isset($_SESSION['cur']) ? $_SESSION['cur'] : 'UAH';
$cacheFile = $_SERVER['DOCUMENT_ROOT'] . "/cache/static/currency_{$currency}.html";
$cacheTime = 24 * 60 * 60;

global $glb;

if (
    file_exists($cacheFile) &&
    time() - $cacheTime < filemtime($cacheFile) &&
    $glb['enable_cache']
) {
    $currr = file_get_contents($cacheFile);
} else {
    $arr = array();
    $i = 0;
    $active_uan = "";
    $active_uan = $currency == 'UAH' ?
        "active" :
        "";
    $currr = "<a href='#' rel='UAH' class='d-table-row {$active_uan}'>
            <div class='d-table-cell'>UAH</div>
            <div class='d-table-cell'>1.00</div>
            <div class='d-table-cell'>1.00</div>
            <div class='d-table-cell'></div>
        </a>";
    $xml = simplexml_load_file("https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=5");
    for ($i = 0; $i < count($xml->row); $i++) {
        $ccy = $xml->row[$i]->exchangerate['ccy']->__toString();
        $sale = $xml->row[$i]->exchangerate['sale']->__toString();
        $buy = $xml->row[$i]->exchangerate['buy']->__toString();
        $sale = round(floatval($sale), 2);
        $buy = round(floatval($buy), 2);
        $arr[$i] = array('ccy' => $ccy, 'sale' => $sale, 'buy' => $buy);
        if ($ccy == 'BTC') continue;
        if ($ccy == 'RUR') $ccy = 'RUB';
        $active = $currency == $ccy ? "active" : "";
        $currr .= "<a href='#' rel='{$ccy}' class='d-table-row {$active}'>
            <div class='d-table-cell'>{$ccy}</div>
            <div class='d-table-cell'>{$buy}</div>
            <div class='d-table-cell'>{$sale}</div>
            <div class='d-table-cell'></div>
        </a>";
        if ($glb['enable_cache']) {
            writeCache('cur', $currr, array($currency));
        }
    }
    echo $currr;
}
