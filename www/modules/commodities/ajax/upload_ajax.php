<?php

require_once('../../../vendor/autoload.php');

define('BLOB_STORAGE', getenv('BLOB_STORAGE'));

$blob = new \Modules\BlobStorage();

// 1 - шлях до файла, 2 - назва файла як він буде назв. в сторейджі, 3 - назва папки в сторейджі
// $blob->uploadBlob('title', 'base1.jpg', 'payment-p');
// var_dump($blob->getListBlobsInContainer('payment-p'));

	//	var_dump($_FILES);
	//echo $_GET['files'];	
	//echo "uppl".$_FILES[0]["name"]."\n";
	//var_dump($_FILES);

	$format=end(explode(".",$_FILES[0]["name"]));
	
	
	if($format=='jpeg' || $format=='jpg' || $format=='JPG' || $format=='pdf' || $format=='PDF' || $format=='png' || $format=='PNG' ){
		//$file=str_replace("_", "", $_FILES[0]["name"]);
		$blob->uploadBlob($_FILES[0]['tmp_name'], $_FILES[0]["name"], 'payment-p');
		var_dump($blob->getListBlobsInContainer('payment-p')); 
		echo "@@".$_FILES[0]["name"];
		// $sourcePath = $_FILES[0]['tmp_name'];  
		//$targetPath = "../../../uploads/payment_P/".$_FILES[0]["name"]; 
		//$targetPath = "../../../uploads/order/eee.".$format;
		// if(move_uploaded_file($sourcePath,$targetPath)) {
		// 	echo 1;
		// }else{
		// 	echo 0;
		// }
	}else{
		echo 2;
	}

?>