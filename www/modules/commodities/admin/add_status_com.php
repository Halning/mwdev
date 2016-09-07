<?php
namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();

// if ($_SESSION['status']=="admin")
// {
	if(isset($_GET["id"]))
	{
		
		$id = $_GET["comid"];
		$status = $_GET["id"];
		// mysql_query("UPDATE `shop_orders_coms` SET `com_status` = '{$status}' WHERE `id` = '{$id}';");

		$db->query("UPDATE `shop_orders_coms` SET `com_status` = '{$status}'
				WHERE `id` = '{$id}';");

		// $sql2 = "SELECT `com_status`, `group_id`, `com_id` FROM `shop_orders_coms` WHERE `id` = {$id}";
		// $res2 = mysql_query($sql2);

		$res2 = $db->query("SELECT `com_status`, `group_id`, `com_id` FROM `shop_orders_coms` WHERE `id` = {$id}");

		// if($row2 = mysql_fetch_assoc($res2)){
		if($row2 = $res2->fetch_assoc()){
			$current = $row2["com_status"];
			$group_id = $row2["group_id"];


			if($current <= 5){

				// $sql11 = "SELECT * FROM `old_orders_price` WHERE `id` = {$id}";
				// $res11 = mysql_query($sql11);

				// $sql11 = $db->query("SELECT * FROM `old_orders_price` WHERE `id` = {$id}");
				// // $row11 = mysql_fetch_assoc($res11);
				// $row11 = $sql11->fetch_assoc();
				// if(empty($row11)){
				// 	// echo("all_ok!");
				// 	// $sql10 = "SELECT `commodity_price`, `commodity_price2` FROM `shop_orders_coms`
				// 	// 		  LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
				// 	// 		  WHERE `id` = {$id}";
				// 	// $row10 = mysql_fetch_assoc(mysql_query($sql10));

				// 	$sql10 = $db->query("SELECT `commodity_price`, `commodity_price2` FROM `shop_orders_coms`
				// 			  LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
				// 			  WHERE `id` = {$id}");
				// 	$row10 = $sql10->fetch_assoc();
				// 	$price1 = $row10["commodity_price"];
				// 	echo($price1."price");
				// 	$price2 = $row10["commodity_price2"];
				// 	echo($price2."<br>");
				// 	echo($id);
				// 	// $query4 = "INSERT INTO `old_orders_price` SET 
				// 	// 		`id` = '{$id}', `commodity_price` = '{$price1}', `commodity_price2` = '{$price2}'";
				// 	// mysql_query($query4);
				// 	$db->query("INSERT INTO `old_orders_price` SET 
				// 			`id` = '{$id}', `commodity_price` = '{$price1}', `commodity_price2` = '{$price2}'");
				// }
			
				


				// $sql3 = "SELECT * FROM  `sup_group` WHERE `group_id` = {$group_id}";
				// $res3 = mysql_query($sql3);
				$res3 = $db->query("SELECT * FROM  `sup_group` WHERE `group_id` = {$group_id}");
				// $sql8 = "SELECT `id`
				// FROM `shop_orders_coms` WHERE `group_id` = {$group_id}";
				// $res8 = mysql_query($sql8);
				$res8 = $db->query("SELECT `id`	FROM `shop_orders_coms` WHERE `group_id` = {$group_id}");
				// while($row8 = mysql_fetch_assoc($res8)){
				while($row8 = $res8->fetch_assoc()){
					$group_count++;
				}

				// if($row3 = mysql_fetch_assoc($res3)){
				if($row3 = $res3->fetch_assoc()){
					if($row3["status"] == 1 && $group_count>1){
						// $sql4 = "UPDATE `sup_group` SET `status` = 2 WHERE `group_id` = {$group_id}";
						// mysql_query($sql4);
						$db->query("UPDATE `sup_group` SET `status` = 2 WHERE `group_id` = {$group_id}");
					} else{
						// $sql5 = "SELECT `com_status` FROM `shop_orders_coms` WHERE `group_id` = {$group_id}";
						// $res5 = mysql_query($sql5);
						$res5 = $db->query("SELECT `com_status` FROM `shop_orders_coms` WHERE `group_id` = {$group_id}");
						// while($row5 = mysql_fetch_assoc($res5)){
						while($row5 = $res5->fetch_assoc()){
							$count ++;
							if($row5["com_status"] > 0 && $row5["com_status"] <4){
								$count_true ++;
							}
						}
						if($count == $count_true){
							// $sql6 = "UPDATE `sup_group` SET `status` = 3 WHERE `group_id` = {$group_id}";
							// mysql_query($sql6);
						}
					}
				}


			} 
		}
		
		


}
// }
?>