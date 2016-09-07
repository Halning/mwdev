<?php
/**
 * Created by PhpStorm.
 * Конвертує імена продуктів і категорій з кирилиці в латиницю і записує їх в аліас.
 * Date: 12.02.16
 * Time: 16:23
 */

include('../../settings/functions.php');
include('../../vendor/autoload.php');

use Modules\MySQLi;

$db = MySQLi::getInstance()->getConnect();

//commodity alias
$result = $db->query(<<<QUERY
    SELECT commodity_ID id, com_name name, alias, cod
    FROM shop_commodity
QUERY
);

$stmt = $db->prepare(<<<QRERY2
    UPDATE shop_commodity
    SET alias = ?
    WHERE commodity_ID = ?
QRERY2
);

$counter = 1;
while ($row = $result->fetch_object()) {

    $alias = transliterate($row->name).'_'.transliterate($row->cod);

    $stmt->bind_param('si', $alias, $row->id);
    echo '№'.$counter.'. #'.$row->id.' = '.$alias.' - > '.$row->name.' - '.$stmt->execute().'<br>';
//    echo '№'.$counter.'. #'.$row->id.' = '.$alias.' - > '.$row->name.'<br>';
    $counter++;
    flush();
    ob_flush();
//    if ($counter > 50) exit;
}


//category alias
/*$result = $db->query(<<<QUERY
    SELECT categories_of_commodities_ID id, cat_name name, alias
    FROM shop_categories
QUERY
);

$stmt = $db->prepare(<<<QRERY2
    UPDATE shop_categories
    SET alias = ?
    WHERE categories_of_commodities_ID = ?
QRERY2
);

$counter = 1;
while ($row = $result->fetch_object()) {

    $alias = transliterate($row->name);

    $stmt->bind_param('si', $alias, $row->id);
//    echo '№'.$counter.'. #'.$row->id.' = '.$alias.' - > '.$row->name.' - '.$stmt->execute().'<br>';
    echo '№'.$counter.'. #'.$row->id.' = '.$alias.' - > '.$row->name.'<br>';
    $counter++;
    flush();
    ob_flush();
}*/
