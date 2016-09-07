<?php

namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();

	if(isset($_GET['update_group'])){
		$gr=$_GET['gr'];
		$comid=$_GET['comid'];
		// mysql_query("UPDATE  `shop_orders_coms`  SET `group_id`='{$gr}' WHERE `id`='{$comid}'; ");
		$db->query("UPDATE  `shop_orders_coms`  SET `group_id`='{$gr}' WHERE `id`='{$comid}'; ");
		//echo "group";
	}

?>