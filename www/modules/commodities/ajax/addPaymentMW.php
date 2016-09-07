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
		$change=$_GET["cha"];

		//mysql_query("UPDATE `shop_orders` SET `payment_MW`={$change}  WHERE `id`={$id}; ");
		$db->query(<<<QUERY1
        	UPDATE `shop_orders` SET `payment_MW`={$change}  WHERE `id`={$id};
QUERY1
    );
	}


?>