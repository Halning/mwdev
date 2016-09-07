<?php

ini_set("max_execution_time", "99999");
set_time_limit(99999);
error_reporting(E_ALL^E_NOTICE);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");

	if(isset($_GET["rel"])){
		$id=$_GET["rel"];
		$id_client=$_GET["rel_client"];
		$clientSupp=$_GET["relGroup"];
		mysql_query("UPDATE `shop_orders` SET `status`=2 WHERE `id`={$id_client}; ");
		mysql_query("UPDATE `sup_group` SET `status`=2 WHERE `group_id`={$id}; ");
echo $clientSupp;
		if(strpos($clientSupp,"_")!==false){
			$so=explode("_", $clientSupp);
			
			mysql_query("UPDATE `shop_order_supplier` SET `supp_status`=2 WHERE `order_id`='{$so[0]}' AND `supplier_name_id`='{$so[1]}'; "); 
		}
	}

?>