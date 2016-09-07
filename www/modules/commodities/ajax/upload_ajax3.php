<?php
require_once('../../../vendor/autoload.php');

define('BLOB_STORAGE', getenv('BLOB_STORAGE'));

$blob = new \Modules\BlobStorage();

	$format=end(explode(".",$_FILES[0]["name"]));
	
	if($format=='jpeg' || $format=='jpg' || $format=='JPG' || $format=='png' || $format=='PNG' || $format=='pdf' || $format=='PDF' ){ 

		$blob->uploadBlob($_FILES[0]['tmp_name'], $_FILES[0]["name"], 'delivery-mwk');
		var_dump($blob->getListBlobsInContainer('delivery-mwk')); 
		echo "@@".$_FILES[0]["name"];
		// $sourcePath = $_FILES[0]['tmp_name'];   
		// $targetPath = "../../../uploads/delivery_MW_K/".$_FILES[0]["name"]; 
	//	echo $targetPath;
	//	$targetPath = "../../../uploads/delivery/".$format[0].".".$format[1];
		// if(move_uploaded_file($sourcePath,$targetPath)) {
		// 	echo 1;
		// }else{
		// 	echo 0;
		// }
	}else{
		echo 2;
	}

?>