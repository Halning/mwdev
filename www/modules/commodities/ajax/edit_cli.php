<?php

namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();

		// For edit order
	if(isset($_POST["com_id"])){
		$com_id=$_POST["com_id"];
		$m_name=$_POST["mname"];
		$g_name=$_POST["gname"];
	//	echo $com_id;
		//mysql_query("UPDATE `shop_orders` SET `{$m_name}`='{$g_name}' WHERE `id`={$com_id}");
		$db->query(<<<QUERY1
       	UPDATE `shop_orders` SET `{$m_name}`='{$g_name}' WHERE `id`={$com_id} 
QUERY1
    );
	}
	
?>