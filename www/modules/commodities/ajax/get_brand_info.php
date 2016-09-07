<?php
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");

bd_session_start();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id) {
    $error = false;
    $query = "
        SELECT cat_desc
        FROM shop_categories
		WHERE categories_of_commodities_ID = $id
	";
    $result = mysql_query($query);
    if($row = mysql_fetch_assoc($result)){
        $text = $row['cat_desc'];
    } else {
        $error = true;
        $text = 'query error!';
    }
} else {
    $error = true;
    $text = 'brand id not specify!';
}
echo json_encode(array(
    'error' => $error,
    'text' => $text
));
