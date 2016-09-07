<?php

require_once("../../../settings/connect_new.php");

//---------rewrite description for Seventeen----------------------
/*$sql = "
SELECT commodity_ID, com_fulldesc FROM shop_commodity
WHERE brand_id = 47
";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    $id = $row['commodity_ID'];
    echo $id . '<br>';
    $description = preg_replace('/^<div>/', '', $row['com_fulldesc']);
    $sql = "
UPDATE shop_commodity SET com_fulldesc = '{$description}'
WHERE commodity_ID = {$id}
";
    var_dump($description);
    var_dump($mysqli->query($sql));
}
echo("done");
return;*/

$sql = "
SELECT categories_of_commodities_ID id FROM shop_categories
WHERE categories_of_commodities_parrent = 10
ORDER BY id
";

$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    echo $id . '<br>';
    $sql = "
UPDATE shop_commodity SET brand_id = {$id}
WHERE commodity_ID IN (
    SELECT commodityID  FROM `shop_commodities-categories`
    WHERE categoryID = {$id}
)";
    echo $mysqli->query($sql);
}
echo("done");