<?
namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();

	if(isset($_GET["group_id"]))
	{
		
		$group_id = $_GET["group_id"];
		$status = $_GET["id"];
		$id=explode("_", $group_id);
		
		// mysql_query("UPDATE `shop_order_supplier` SET `supp_status`='{$status}' WHERE `order_id`='{$id[0]}' AND `supplier_name_id`='{$id[1]}'; ");

		$db->query("UPDATE `shop_order_supplier` SET `supp_status`='{$status}' WHERE `order_id`='{$id[0]}' AND `supplier_name_id`='{$id[1]}'; ");
	}

