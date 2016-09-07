<?php


session_destroy();
//ini_set('display_errors',0);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");
bd_session_start();
require_once("../../../settings/main.php");
require_once("../../phpmailer/PHPMailerAutoload.php");

	// $db = MySQLi::getInstance()->getConnect();

	// $result = $db->query("
	//     SELECT * FROM `chat_operator` WHERE 1
	// ");
	$result = mysql_query("
		SELECT * FROM `chat_operator` WHERE 1
			");
	$arr=array();
	$i=0;
	while($row = mysql_fetch_assoc($result)){
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

		$arr[$i]=array('id'=>$row['co_id'], 'name'=>$row['co_name'],'text'=>$row['co_text'],'status'=>$row['co_status'], 'email'=>$row['co_email'], 'file'=>$filename);
		$i++;
	}
	echo json_encode($arr);


?>