<?php

session_destroy();
//ini_set('display_errors',0);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");
bd_session_start();
require_once("../../../settings/main.php");

	//============Upload photo===============================================

	if(isset($_FILES[0]["name"])){

			// файл и новый размер
			$tmpFile = $_FILES[0]["tmp_name"];
			// $filename = "online/images/avatar/op_avatar_{$add_name}.jpg";
			$filename = "../../../online/images/avatar/".$_FILES[0]["name"];

			//header('Content-Type: image/jpeg');

			// получение нового размера
			 list($width, $height) = getimagesize($tmpFile);

			// загрузка
			if($width<$height){
				$pol=($height-$width)/2;
				$image = new Imagick($tmpFile);
	       		$image->cropImage($width,$width,0,0);
	        	$image->writeImage($filename);
			}else{
				$pol=($width-$height)/2;
				$image = new Imagick($tmpFile);
	       		$image->cropImage($height,$height,0,0);
	        	$image->writeImage($filename);
			}

			echo $_FILES[0]["name"]."@1";

	}

?>