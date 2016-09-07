<?php
header("Content-Type: application/x-javascript; charset=UTF-8");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
session_destroy();
//ini_set('display_errors',0);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");

require_once("../../../settings/functions.php");
require_once("../../../settings/main.php");
// -----------------------------------------------------------------------  СЕССИИ

// -----------------------------------------------------------------------  //СЕССИИ

$com_id=intval($_POST['com_id']);
$ses_id=$_POST['ses_id'];
session_id($ses_id);
bd_session_start();
//print_r($_SESSION);
if($_SESSION['status']!='admin')
	die('Hacking attempt!');

if (!empty($_FILES) and $com_id) {
	$fileParts = pathinfo($_FILES['Filedata']['name']);	 
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png','JPG','JPEG','PNG','GIF'); // File extensions
	
	if (in_array($fileParts['extension'],$fileTypes)) {		
		$add_date=date("Y-m-d H:i:s");
		$sql="INSERT INTO `shop_images` SET `com_id`='{$com_id}'";
		mysql_query($sql) or die(mysql_error());
		$photo_id=mysql_insert_id();
		$result=getnewimg_uploadify(1,1280,728,'commodities',$com_id,$photo_id.'.jpg',1) && getnewimg_uploadify($addcomimgt,$addcomimgx,$addcomimgy,'commodities',$com_id,"s_{$photo_id}.jpg");
		if(!$result){
			$sql="DELETE FROM `shop_images` WHERE `img_id`='{$photo_id}'";
			mysql_query($sql) or die(mysql_error());
			die('Ошибка добавления картинки!');
		}
		$photo_src="/images/commodities/{$com_id}/".$photo_id.'.jpg';
		$small_photo_src="/images/commodities/{$com_id}/s_".$photo_id.'.jpg';
		
		require("../../../modules/commodities/templates/photo_item.php");
		$answer = $photo_item;
	} else {
		$answer = 'Invalid file type.';
	}
	echo $answer;
}
?>