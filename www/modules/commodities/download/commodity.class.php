<?php

//namespace Modules;

use Modules\MySQLi;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');


bd_session_start();

// $db = MySQLi::getInstance()->getConnect();

//require_once("../../phpmailer/PHPMailerAutoload.php");

class commodity{


	// function getCommodity(){
	// 	$arr=array();
	// 	$res=mysql_query("SELECT 
	// 			`commodity_ID`,
	// 			`brand_id`,
	// 			`cod`,
	// 			`com_name`,
	// 			a.`alias`,
	// 			`commodity_price`,
	// 			`commodity_price2`,
	// 			`from_url`,
	// 			`cur_id`,
	// 			`com_fulldesc`,
	// 			`com_sizes`,
	// 			`categories_of_commodities_ID`,
	// 			`cat_name`
	// 		FROM `shop_commodity` AS a
	// 		INNER JOIN `shop_categories` AS b
	// 		ON a.`brand_id`=b.`categories_of_commodities_ID`
	// 		ORDER BY `commodity_ID` ASC LIMIT 10");
	// 		// $row=mysql_fetch_assoc($res);

	// 		while($row=mysql_fetch_assoc($res)){
	// 			$arr[$row['commodity_ID']]=array(
	// 				'id'=>$row['commodity_ID'],
	// 				'cat_name'=>$row['cat_name'],
	// 				'art'=>$row['cod'],
	// 				'name'=>$row['com_name'],
	// 				'price'=>$row['commodity_price'],
	// 				'opt'=>$row['commodity_price2'],
	// 				'size'=>$row['com_sizes'],
	// 				'desc'=>$row['com_fulldesc'],
	// 				'from_url'=>$row['from_url'],
	// 				'alias'=>$row['alias'],
	// 			);
	// 		}
	// 	return $arr;
	// }

	function getOrders(){
		$db = MySQLi::getInstance()->getConnect();
		$arr=array();

		$res=$db->query("SELECT a.`id` , a.`email` , a.`tel` , a.`name` , a.`status` , a.`date` , b.`offer_id` , b.`count` , b.`price` , b.`com_status` , SUM( b.`price` * b.`count` ) AS summa
			FROM  `shop_orders` AS a
			INNER JOIN  `shop_orders_coms` AS b ON a.`id` = b.`offer_id` 
			WHERE b.`com_status` <>2
			AND  `status` 
			IN ( 1, 2, 3, 4, 5, 6, 7, 8, 12 ) 
			GROUP BY a.`id` ");
		while($row=$res->fetch_assoc()){
			$arr[$row['id']]=array(
				'id'=> $row['id'],
				'date'=> $row['date'],
				'tel'=> $row['tel'],
				'email'=> $row['email'],
				'name'=> $row['name'],
				'summa'=> $row['summa']
			);
		}

		return $arr;
	}

	// function getPhoto($id){
	// 	$dir="/home/zoond_make/zoond/images/commodities/".$id;
	// 	$arr=array();
	// 	if (is_dir($dir)) {
	// 	    if ($dh = opendir($dir)) {
	// 	        while (($file = readdir($dh)) !== false) {
	// 	        	if($file!='.' && $file!='..'){
	// 	           		array_push($arr, $file);
	// 	        	}
	// 	        }
	// 	        closedir($dh);
	// 	    }
	// 	}

	// 	return $arr;
	// }



 }


?>
