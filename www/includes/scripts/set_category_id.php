<?php

namespace Modules;

require_once('../../vendor/autoload.php');

$_db = MySQLi::getInstance()->getConnect();

$items = array();

/*
$result = $_db->query(<<<QUERY1
    SELECT
      categories_of_commodities_ID catId
    FROM shop_categories
    WHERE categories_of_commodities_parrent IN (
        264, 209, 212, 213, 261, 211, 266, 267, 210, 268
      )
    AND categories_of_commodities_ID NOT IN (26, 27);
QUERY1
);

while ($row = $result->fetch_object()) {
    $items[] = $row->catId;
}

//var_dump($items);

foreach ($items as $item) {
    $query = <<<QUERY2
        UPDATE shop_commodity
          SET category_id = {$item}
        WHERE commodity_ID IN (
          SELECT commodityID
          FROM `shop_commodities-categories`
          WHERE categoryID = {$item}
        )
QUERY2;
    $result = $_db->query($query);
    echo "$item - $result<br>";
    flush();
    ob_flush();
}*/


$result = $_db->query(<<<QUERY222
    SELECT commodity_ID, brand_id, category_id
    FROM shop_commodity
QUERY222
);
while ($row = $result->fetch_object()) {
    $items[] = $row;
}
$stmt = $_db->prepare(<<<QUER333
    INSERT IGNORE INTO shop_products_brands (product_id, brand_id, category_id) VALUES (?, ?, ?)
QUER333
);
foreach ($items as $key => $item) {
$stmt->bind_param('iii', $item->commodity_ID, $item->brand_id, $item->category_id);
    echo "$key. {$item->commodity_ID} - {$item->brand_id} - {$item->category_id} - {$stmt->execute()}<br>";
//    break;
    flush();
    ob_flush();
}













