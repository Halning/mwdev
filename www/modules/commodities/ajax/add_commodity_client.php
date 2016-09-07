<?php

namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();

	if(isset($_POST["id"])){
		$id=$_POST["id"];
		$offer_id=$_POST["offer_id"];
		$cur=$_POST["cur"];
		$count=$_POST["count"];
		$size=$_POST["size"];

		$res=$db -> query("SELECT `commodity_ID`, `commodity_price2`, `commodity_price`
			FROM  `shop_commodity` 
			WHERE `commodity_ID` = {$id} ");
		
		// if($row=mysql_fetch_assoc($res)){
		if($row=$res->fetch_assoc()){
			$price_opt = $row["commodity_price2"];
			$price_rozn = $row["commodity_price"];
			
			if($count >= 5){
				$price = $price_opt;
			} else{
				$price = $price_rozn;
			}

			$db -> query("INSERT INTO `shop_orders_coms`
				SET 
				`offer_id` = '{$offer_id}',
				`com_id` = '{$id}',
				`cur_id` ='{$cur}',
				`count` = '{$count}',
				`price` = '{$price}',
				`com` = '{$size}'
			");
			echo 1;
		} else{
			echo 0;
		}		
		
	}


?>