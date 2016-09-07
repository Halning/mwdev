<?php

session_destroy();
//ini_set('display_errors',0);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");
bd_session_start();
require_once("../../../settings/main.php");
require_once("../../phpmailer/PHPMailerAutoload.php");

	$client=trim($_GET['client']);
	$cid=$_GET['cid'];
	mysql_query("UPDATE `chat_online` SET `c_status`=0 WHERE `chat_from`='{$cid}' OR `chat_to`='{$cid}'; ");

	$res=mysql_query("SELECT * FROM `chat_online` WHERE `chat_name` = '{$client}'; ");
	$arr=array();
	$i=0;

	while($row=mysql_fetch_assoc($res)){
		$arr[$i]=array('name'=>$row['chat_name'], 'from'=>$row['chat_from'], 'to'=>$row['chat_to'], 'message'=>$row['messagebox'], 'date'=>$row['date'], 'status'=>$row['c_status'] );
		$i++;
	}

	echo json_encode($arr);
?>