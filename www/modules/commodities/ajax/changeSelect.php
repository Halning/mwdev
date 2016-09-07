<?php


namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();

	if(isset($_GET["rel"])){
		$id=$_GET["rel"];
		$count=$_GET["count"];
		$price=$_GET["price"];

		//mysql_query("UPDATE `shop_orders_coms` SET `count`={$count}, `price`={$price}  WHERE `id`={$id}; ");
		$db->query(<<<QUERY1
        	UPDATE `shop_orders_coms` SET `count`={$count}, `price`={$price}  WHERE `id`={$id};
QUERY1
    );
	}

?>