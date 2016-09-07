<?php
namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

define('BLOB_STORAGE', getenv('BLOB_STORAGE'));

$blob = new \Modules\BlobStorage();

$db = MySQLi::getInstance()->getConnect();

	if(isset($_POST['path'])){
		$id=$_POST['rel'];
		$file=$_POST['file'];
		$path=$_POST['path'];

		if($path=='payment_P'){

			$db->query(<<<QUERY1
       		UPDATE `sup_group` SET `payment_clip`=0, `payment_clip_file`='' WHERE `group_id`='{$id}'; 
QUERY1
    );
			$blob->deleteBlob('payment-p', $file);
		}
		if($path=='delivery_MW_K'){
			$db->query(<<<QUERY1
       	 	UPDATE `shop_orders` SET `mw_k_clip`=0, `mw_k_clip_file`='' WHERE `id`='{$id}';
QUERY1
    );
			$blob->deleteBlob('delivery-mwk', $file);
		}
		if($path=='delivery_P_MW'){
			$db->query(<<<QUERY1
        	UPDATE `sup_group` SET `payment_clip2`=0, `payment_clip_file2`='' WHERE `group_id`='{$id}';
QUERY1
    );
			$blob->deleteBlob('delivery-pmw', $file);
		}

		// $file2=end(explode(".",$file));

		// $urlFile="http://".$_SERVER["HTTP_HOST"]."/uploads/".$path."/".$id.".".$file2;
		// $ghead=get_headers($urlFile);
		// $head=substr($ghead[0], 9, 3);
		// echo $head."="."uploads/".$path."/".$id.".".$file2;		
		// if($head=="200"){
		// 	unlink("../../../uploads/".$path."/".$id.".".$file2);
		// 	echo " delete".$id.".".$file2;
		// }
	}

// payment_P, delivery_P_MW, delivery_MW_K




?>