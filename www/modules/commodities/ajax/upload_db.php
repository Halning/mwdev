<?php
namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();

	if(isset($_POST['path'])){
		$path=$_POST['path'];
		$id=$_POST['id'];
		$file=$_POST['file_name'];

		if(strpos($id,"_")!==false){
			$so=explode("_", $id);
			
			if($path=='payment_P'){
				$db->query(<<<QUERY1
        		UPDATE `shop_order_supplier` SET `payment_clip`=1, `payment_clip_file`='{$file}' WHERE `order_id`='{$so[0]}' AND `supplier_name_id`='{$so[1]}';
QUERY1
    );

			}
			if($path=='delivery_P_MW'){
				$db->query(<<<QUERY1
        		UPDATE `shop_order_supplier` SET `payment_clip2`=1, `payment_clip_file2`='{$file}' WHERE `order_id`='{$so[0]}' AND `supplier_name_id`='{$so[1]}'; 
QUERY1
    );
			}
		}else{
			if($path=='payment_P'){
				$db->query(<<<QUERY1
        		UPDATE `sup_group` SET `payment_clip`=1, `payment_clip_file`='{$file}' WHERE `group_id`='{$id}';
QUERY1
    );
			}
			if($path=='delivery_P_MW'){
				$db->query(<<<QUERY1
        		UPDATE `sup_group` SET `payment_clip2`=1, `payment_clip_file2`='{$file}' WHERE `group_id`='{$id}';
QUERY1
    );
			}
			if($path=='delivery_MW_K'){
				$db->query(<<<QUERY1
        		UPDATE `shop_orders` SET `mw_k_clip`=1, `mw_k_clip_file`='{$file}' WHERE `id`='{$id}';
QUERY1
    );
			}
		}
		

		// $file2=end(explode(".",$file));

		// $urlFile="http://".$_SERVER["HTTP_HOST"]."/uploads/".$path."/".$id.".".$file2;
		// $ghead=get_headers($urlFile);
		// $head=substr($ghead[0], 9, 3);
			
		// if($head=="200"){
		// 	unlink("../../../uploads/".$path."/".$id.".".$file2);
		// 	//echo "delete: ".$id.".".$file2;
		// }else{
		// 	//echo "not file ";
		// }

		echo $file;
		// rename("../../../uploads/".$path."/".$file ,"../../../uploads/".$path."/".$id.".".$file2);
	}

?>