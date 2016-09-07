<?php

session_destroy();
//ini_set('display_errors',0);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");
bd_session_start();
require_once("../../../settings/main.php");
require_once("../../phpmailer/PHPMailerAutoload.php");

	$res=mysql_query("SELECT * FROM `chat_online` GROUP BY `chat_name`");
	$arr=array();
	$i=0;

	while($row=mysql_fetch_assoc($res)){
		$arr[$i]=array('name'=>$row['chat_name'], 'status'=>$row['c_status'], 'from'=>$row['chat_from'], 'to'=>$row['chat_to']);
		$i++;
	}

	echo json_encode($arr);
?>