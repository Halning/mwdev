<?php

namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();

	if(isset($_GET["rel_id"])){
		$txt=$_GET["text"];
		$txt=trim($txt);
		$row=$_GET["row"];
		$rel_id=$_GET["rel_id"];

		// mysql_query("UPDATE `soc_client_all_stock` SET `{$row}`='{$txt}' WHERE `scas_id`='{$rel_id}'; ")or die(mysql_error());
		$db->query("UPDATE `soc_client_all_stock` SET `{$row}`='{$txt}' WHERE `scas_id`='{$rel_id}'; ");
	}

	if(isset($_GET["add_id"])){
		// mysql_query("INSERT INTO `soc_client_all_stock`(`scas_name`) VALUES ('Add Name'); ");
		$db->query("INSERT INTO `soc_client_all_stock`(`scas_name`) VALUES ('Add Name'); ");

		// $maxRes=mysql_query("SELECT MAX(  `scas_id` ) max_id FROM  `soc_client_all_stock`");
		$maxRes=$db->query("SELECT MAX(  `scas_id` ) max_id FROM  `soc_client_all_stock`");
		// $maxRow=mysql_fetch_assoc($maxRes);
		$maxRow = $maxRes->fetch_assoc();
		echo $maxRow["max_id"];
	}

	if(isset($_GET["delete_id"])){
		$del_id=$_GET["delete_id"]; 

		// mysql_query("DELETE FROM `soc_client_all_stock` WHERE `scas_id` = '{$del_id}' ")or die(mysql_error());
		$db->query("DELETE FROM `soc_client_all_stock` WHERE `scas_id` = '{$del_id}' ");
	}


?>