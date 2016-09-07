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
		$status = intval($_GET["id"]);
		echo $status.":".$group_id;
		
		// mysql_query("UPDATE `sup_group` SET `status`={$status} WHERE `group_id`={$group_id}; ");
		$db->query("UPDATE `sup_group` SET `status`={$status} WHERE `group_id`={$group_id}; ");
	}
	
	
	if(isset($_GET["limits"])){
		$limit = $_GET['limits'];
		// mysql_query("UPDATE `slider_limit` SET `limits`='{$limit}' WHERE `sli_id`=1");
		$db->query("UPDATE `slider_limit` SET `limits`='{$limit}' WHERE `sli_id`=1");
	}

