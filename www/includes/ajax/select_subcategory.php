<?
header("Content-Type: application/x-javascript; charset=UTF-8");
require_once("../../settings/connect.php");
session_start();
function get_filter_values($comodityID, $filterID){
	$sql = "SELECT `ticket_value` FROM `shop_filters-values` 
	WHERE `ticket_id`='{$comodityID}' 
	AND `ticket_filterid`='{$filterID}'
	";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)>0){
		$row=mysql_fetch_assoc($result);
		return $row['ticket_value'];
	
	}
}
$_SESSION['sel_lang']=is_numeric($_SESSION['sel_lang'])?$_SESSION['sel_lang']:1;

if(is_numeric($_GET["cat_id"]))
{
	
	$cat_id=$_GET["cat_id"];
	$sql="
SELECT * FROM `shop_filters-lists`  
WHERE `lng_id`='{$_SESSION['sel_lang']}' AND `list_parentid`='{$cat_id}'
ORDER BY `list_name`;"; 
	$res=mysql_query($sql);
	while($row=mysql_fetch_assoc($res))
	{
		if(!isset($first_run)){
			$sel_filtr = get_filter_values($_GET['comodityID'], $row["list_filterid"]);
			$first_run = 1;
		}
		$filter_id=$row["list_filterid"];
		$selected=$sel_filtr==$filter_id?"selected":"";
		$options.="<option value='{$row['list_id']}' {$selected}>{$row['list_name']}</option>";
		
	}
	
}$options="<select name='filter[{$filter_id}]' id='id_selopt_{$filter_id}'><option value='-'>-</option>{$options}</select>";
$options=str_replace("'","\"",$options);//$options=ereg_replace(" "," ",$options);
$options=str_replace("\r\n","",$options);
echo $_GET['callback']."({res:'{$options}'})";
?>