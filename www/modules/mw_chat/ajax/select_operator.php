<?php

session_destroy();
//ini_set('display_errors',0);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");
bd_session_start();
require_once("../../../settings/main.php");


	//$db = MySQLi::getInstance()->getConnect();

	$id=$_POST["chat_operator"];

	mysql_query("
		UPDATE `chat_operator` SET `co_status`=0 WHERE 1
			");
	mysql_query("
		UPDATE `chat_operator` SET `co_status`=1 WHERE `co_id`={$id}
			");
	$res=mysql_query("
		SELECT * FROM `chat_operator` WHERE `co_id`={$id}
			");


	$arr=array();
	$i=0;
	while($row = mysql_fetch_assoc($res)){

		$filename = '../../../online/images/avatar/op_avatar_'.$row['co_name'].'.jpg';
		$filenamepng = '../../../online/images/avatar/op_avatar_'.$row['co_name'].'.png';
		$filenamegif = '../../../online/images/avatar/op_avatar_'.$row['co_name'].'.gif';

		if (file_exists($filename)) {
			$filename='online/images/avatar/op_avatar_'.$row['co_name'].'.jpg';
		}elseif(file_exists($filenamepng)){
			$filename='online/images/avatar/op_avatar_'.$row['co_name'].'.png';
		}elseif(file_exists($filenamegif)){
			$filename='online/images/avatar/op_avatar_'.$row['co_name'].'.gif';
		}else{
		    $filename="online/images/avatar.jpg";
		}

		$arr[$i]=array('id'=>$row['co_name'], 'name'=>$row['co_name'],'text'=>$row['co_text'],'status'=>$row['co_status'], 'email'=>$row['co_email'], 'file'=>$filename);
		$i++;
	}
	echo json_encode($arr);

	


?>