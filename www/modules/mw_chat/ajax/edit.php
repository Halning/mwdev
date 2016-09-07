<?php
	

session_destroy();
//ini_set('display_errors',0);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");
bd_session_start();
require_once("../../../settings/main.php");
require_once("../../phpmailer/PHPMailerAutoload.php");

//	$db = MySQLi::getInstance()->getConnect();


	$id=$_POST["id"];
	$tab=$_POST["tab"];
	$write=$_POST["write"];

	// $db->query("
	//     UPDATE `chat_operator` SET `{$tab}`='{$write}' WHERE `co_id`={$id}
	// ");

	mysql_query("
		UPDATE `chat_operator` SET `{$tab}`='{$write}' WHERE `co_id`={$id}
			");

	


?>