<?php

namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();
	
	if(isset($_GET['rel'])){
		$id=$_GET['rel'];
		$txt=$_GET['txt'];
		$wr=$_GET['wr_name'];

		if(strpos($id,"_")!==false){
			$so=explode("_", $id);
			
			// mysql_query("UPDATE `shop_order_supplier` SET `{$wr}`='{$txt}' WHERE `order_id`='{$so[0]}' AND `supplier_name_id`='{$so[1]}'; "); 
			$db->query(<<<QUERY1
        	UPDATE `shop_order_supplier` SET `{$wr}`='{$txt}' WHERE `order_id`='{$so[0]}' AND `supplier_name_id`='{$so[1]}';
QUERY1
    );
		}else{
			// mysql_query("UPDATE `sup_group` SET `{$wr}`='{$txt}' WHERE `group_id`='{$id}'; ") or die(mysql_error());
			$db->query(<<<QUERY1
        	UPDATE `sup_group` SET `{$wr}`='{$txt}' WHERE `group_id`='{$id}';
QUERY1
    );
		}
	}

	if(isset($_GET['rel_id'])){
		$id=$_GET['rel_id'];
		$txt=$_GET['txt'];
		$wr=$_GET['rel_db_tab'];
		// mysql_query("UPDATE `sup_group` SET `{$wr}`='{$txt}' WHERE `group_id`='{$id}'; ") or die(mysql_error());

		$db->query(<<<QUERY1
        UPDATE `sup_group` SET `{$wr}`='{$txt}' WHERE `group_id`='{$id}';
QUERY1
    );
	}
	if(isset($_GET['rel_id2'])){
		$id=$_GET['rel_id2'];
		$txt=$_GET['txt'];
		$wr=$_GET['rel_db_tab'];
		// mysql_query("UPDATE `shop_orders` SET `{$wr}`='{$txt}' WHERE `id`='{$id}'; ") or die(mysql_error());

		$db->query(<<<QUERY1
        UPDATE `shop_orders` SET `{$wr}`='{$txt}' WHERE `id`='{$id}';
QUERY1
    );
	}

?>