<?php

namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();

	if(isset($_GET["id"])){
		$id=$_GET["id"];
		$txt=$_GET["text"];
		$txt=trim($txt);
		$row=$_GET["row"];
		$rel_id=$_GET["rel_id"];

		$db->query("UPDATE `soc_client_join` SET `{$row}`='{$txt}' WHERE `scj_order_id`='{$id}' AND `scj_id`='{$rel_id}'; ");
	}

	if(isset($_GET["idIn"])){
		$id=$_GET["idIn"];
		$txt=$_GET["text"];
		$txt=trim($txt);
		$row=$_GET["row"];

		$db->query("UPDATE `soc_client` SET `{$row}`='{$txt}' WHERE `order_id`='{$id}'; ");
	}

	if(isset($_GET["add_id"])){

		$db->query("INSERT INTO `soc_client_join`(`scj_name`,`scj_order_id`) VALUES ('Add Name', {$_GET["add_id"]}); ");

		$maxRes=$db->query("SELECT MAX(  `scj_id` ) max_id FROM  `soc_client_join`");
		$maxRow = $maxRes->fetch_assoc();
		echo $maxRow["max_id"];
	}

	if(isset($_GET["delete_id"])){
		$del_id=$_GET["delete_id"];
		$id=$_GET["id2"];

		$db->query("DELETE FROM `soc_client_join` WHERE `scj_id` = '{$del_id}' AND `scj_order_id`='{$id}'; ");
	}


?>