<?php

// namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = $glb['mysqli'];
// $db = MySQLi::getInstance()->getConnect();
// $db2 = MySQLi::getInstance()->getConnect();

	
	$relget=$_GET["relget"];

	if(isset($_GET["get_rez"])){
		$arr=array();
		$i=0;
		$result=$db -> query(<<<QUERY2
		SELECT * FROM `rekvizit` WHERE `re_status`='{$relget}'
QUERY2
		);
		while($s=$result->fetch_assoc()){
			$arr[$i]=array(
				"id"=>$s['id'],
				"name"=>$s['in_name'],
				"write"=>$s['in_write']
			);
			$i++;
		}
		echo json_encode($arr);
	}

	if(isset($_GET['rez'])){
		// mysql_query("INSERT INTO `rekvizit`(`in_name`, `in_write`, `re_status`) VALUES ('name','name2','{$relget}'); ");
		$db -> query(<<<QUERY1
			INSERT INTO `rekvizit`(`in_name`, `in_write`, `re_status`) VALUES ('name','name2','{$relget}');
QUERY1
		);
		$arr=array();
		$i=0;
		$result=$db -> query(<<<QUERY2
		SELECT * FROM `rekvizit` WHERE `re_status`='{$relget}'
QUERY2
		);
		while($s=$result->fetch_assoc()){
			$arr[$i]=array(
				"id"=>$s['id'],
				"name"=>$s['in_name'],
				"write"=>$s['in_write']
			);
			$i++;
		}
		echo json_encode($arr);
		//echo get_js($relget);
	}

	if(isset($_GET['del_rez'])){
		$del=$_GET['del_rez'];
		// mysql_query("DELETE FROM `rekvizit` WHERE `id`='{$del}' ");
		$db -> query(<<<QUERY1
			DELETE FROM `rekvizit` WHERE `id`='{$del}'
QUERY1
		);
	}

	if(isset($_GET["up_rez"])){
		$rez=$_GET["up_rez"];
		$id=$_GET["id"];
		$cc=$_GET["cc"];
		//mysql_query("UPDATE `rekvizit` SET `{$cc}`='{$rez}' WHERE `id`='{$id}'; ");
		$db -> query(<<<QUERY1
			UPDATE `rekvizit` SET `{$cc}`='{$rez}' WHERE `id`='{$id}'; 
QUERY1
		);
	}

	//echo "res";

// 	function get_js($relget){
// 		$arr=array();
// 		$i=0;
// 		$result=$db -> query(<<<QUERY2
// 		SELECT * FROM `rekvizit` WHERE `re_status`='{$relget}'
// QUERY2
// 		);
// 		while($s=$result->fetch_assoc()){
// 			$arr[$i]=array(
// 				"id"=>$s['id'],
// 				"name"=>$s['in_name'],
// 				"write"=>$s['in_write']
// 			);
// 			$i++;
// 		}
// 		return json_encode($arr);
// 	}
?>