<?
header("Content-type: application/json; charset=utf-8");
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
session_start();
error_reporting(E_ALL^E_NOTICE);
$search=mysql_real_escape_string($_GET['term']);
$result=array();


	$sql = "SELECT *
	FROM `shop_commodity`
	WHERE (`commodity_visible`='1' AND (`com_name` LIKE '%{$search}%' OR `cod` LIKE '%{$search}%')) ORDER BY `commodity_order`
	LIMIT 0,10";
	$result_com = mysql_query($sql);
	
	while($row=mysql_fetch_assoc($result_com))
	{	
		$c_id=$row['commodity_ID'];
		$com_url=$row['alias']!=""?"/pr{$c_id}_".$row['alias'].'/':"/pr{$c_id}/";
		$com_src=($row['commodity_bigphoto']!=0)?"/images/commodities/{$c_id}/s_title.jpg":"/templates/test1/img/nophoto.jpg";
		$result[]=array(
		'label'=>mb_strlen($row['com_name'],'utf-8')>150?mb_substr($row['com_name'],0,150,'utf-8').'...':$row['com_name'],
		'image'=>$com_src,
		'url'=>$com_url,
		);
	}
	if(count($result)){
	echo json_encode($result);
	exit();
	}

	echo json_encode(array(array(
	'label'=>"По запросу '{$search}' ничего не найдено",
	'image'=>null,
	'url'=>'#',
	)));
?>