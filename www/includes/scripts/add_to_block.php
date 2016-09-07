<?php
/**
 * Created by PhpStorm.
 * User: webler
 * Date: 18.05.16
 * Time: 16:08
 */


namespace Modules;

require_once('../../vendor/autoload.php');

$_db = MySQLi::getInstance()->getConnect();

$items = array();

$result = $_db->query(<<<QUERY1
    SELECT commodity_ID id
FROM shop_commodity
WHERE category_id = 32
AND commodity_ID NOT IN (select DISTINCT com_id FROM shop_blocks_products)
QUERY1
);

while ($row = $result->fetch_object()) {
    $items[] = $row->id;
}

//var_dump($items);
//exit;
foreach ($items as $item) {
    $query = <<<QUERY2
        INSERT INTO shop_blocks_products (block_id, com_id)
        VALUES (7, $item);
QUERY2;
    $result = $_db->query($query);
    echo "$item - $result<br>";
    flush();
    ob_flush();
}
