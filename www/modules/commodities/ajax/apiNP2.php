<?

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

require_once("NovaPoshtaApi2.php");

$np=new NovaPoshtaApi2(NOVA_POSHTA_KEY);

$get=$_GET['ttn'];
$ttn=$np->documentsTracking($get);

//echo $ttn['data'][0]['Sum'];
if($_GET['delv']==1){
	$id=$_GET['rel'];
	$sum=$ttn['data'][0]['Sum'];
	$data=$ttn['data'][0]['DateReceived'];
	$sendCity=$ttn['data'][0]['CitySenderUA'];
	// mysql_query("
	// 	UPDATE `sup_group` 
	// 		SET 
	// 		`del_price`='{$sum}', 
	// 		`ttn-date`='{$data}',
	// 		`ttn-send-city`='{$sendCity}' 
	// 		WHERE `group_id`='{$id}' 
	// 	") or die(mysql_error());
	$glb['mysqli'] -> query(<<<QUERY1
			UPDATE `sup_group` 
			SET 
			`del_price`='{$sum}', 
			`ttn-date`='{$data}',
			`ttn-send-city`='{$sendCity}' 
			WHERE `group_id`='{$id}' 
QUERY1
		);
	if($ttn['data'][0]['Sum']!=0){
		$arr = array(
			'status' => 1,
			'sum'=>$sum, 
			'dateReceived'=>$data,
			'addressUA'=>$ttn['data'][0]['AddressUA'],
			'sendCity'=>$sendCity,
		);
		echo json_encode($arr);
	}else{
		echo json_encode(array('status' => 0));
	}
}

if($_GET['delv']==2){
	$id=$_GET['rel'];
	$sum=$ttn['data'][0]['Sum'];
	$data=$ttn['data'][0]['DateReceived'];
	$sendCity=$ttn['data'][0]['CitySenderUA'];
	$department=$ttn['data'][0]['AddressUA'];
	$department=str_replace("'","\'", $department);
	// mysql_query("
	// 	UPDATE `shop_orders` 
	// 		SET 
	// 		`payment_ttn`='{$sum}', 
	// 		`ttn-depart`='{$department}'
	// 		WHERE `id`='{$id}' 
	// 	") or die(mysql_error());
	$glb['mysqli'] -> query(<<<QUERY1
			UPDATE `shop_orders` 
			SET 
			`payment_ttn`='{$sum}', 
			`ttn-depart`='{$department}'
			WHERE `id`='{$id}'
QUERY1
		);
	if($ttn['data'][0]['Sum']!=0){
		$arr = array(
			'status' => 1,
			'sum'=>$sum, 
			'dateReceived'=>$data,
			'addressUA'=>$department,
			'sendCity'=>$sendCity,
		);
		echo json_encode($arr);
	}else{
		echo json_encode(array('status' => 0));
	}
}

//print_r($ttn);