<?php 

namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();


if(isset($_GET["ofid"]))
{
	$offer_id = $_GET["ofid"];
	$status = $_GET["id"];

	// mysql_query("UPDATE `shop_orders` SET `status`= {$status} WHERE `id` = {$offer_id}");
	$db->query("UPDATE `shop_orders` SET `status`= {$status} WHERE `id` = {$offer_id}");
}


