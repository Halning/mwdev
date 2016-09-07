<?php
namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();

	$com_id=$_GET['comid'];
	$cli_id=$_GET['cliid'];
	$change=$_GET['change'];
	$cur=$_GET['curid'];

	// $res=mysql_query("SELECT * FROM `shop_commodity` WHERE `commodity_ID`='{$com_id}'; ");
	$res=$db->query("SELECT * FROM `shop_commodity` WHERE `commodity_ID`='{$com_id}';");
	//$row=mysql_fetch_assoc($res);
	$row = $res->fetch_assoc();

	// $res2=mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`='{$cur}'; ");
	$res2=$db->query("SELECT * FROM `shop_cur` WHERE `cur_id`='{$cur}'; ");
	//$row2=mysql_fetch_assoc($res2);
	$row2 = $res2->fetch_assoc();

	$price=0;

	if($change==1){
		$price=$row["commodity_price"]*$row2["cur_val"];
	}elseif ($change==2) {
		$price=$row["commodity_price2"]*$row2["cur_val"];
	}

	if($cur==2 || $cur==3){
		$price=round($price);
	}

	//mysql_query("UPDATE `shop_orders_coms` SET `price`='{$price}' WHERE `id`='{$cli_id}'; ");

	$db->query(<<<QUERY1
        UPDATE `shop_orders_coms` SET `price`='{$price}' WHERE `id`='{$cli_id}';
QUERY1
    );

	$arr=array('price'=>$price);

	echo json_encode($arr);

?>