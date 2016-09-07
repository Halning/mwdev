<?
header("Content-Type: application/x-javascript; charset=UTF-8");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
session_destroy();
//ini_set('display_errors',0);
require_once("../../settings/conf.php");
require_once("../../settings/connect.php");
require_once("../../settings/functions.php");
require_once("../../modules/commodities/admin/functions.php");
// -----------------------------------------------------------------------  СЕССИИ

// --------
	bd_session_start();
	if ($_SESSION['status']=="admin")
	{
		
		if($_GET["ids"]!="")
		{
			$efwf=str_replace(",,",",",$_GET["ids"].",0");
			$sql="
			SELECT * FROM `shop_categories` WHERE `categories_of_commodities_ID` IN ({$efwf}) ORDER BY `categories_of_commodities_order`;";
			$res=mysql_query($sql);
			while($row=mysql_fetch_assoc($res))
			{
				$parents[$row["categories_of_commodities_ID"]]=$row["categories_of_commodities_parrent"];
				$rrr.=",{$row["categories_of_commodities_parrent"]}";
			}
		
			
			if(count($parents))
			foreach($parents as $key=>$value)
			{
				$fff.=$fff!=""?",{$key}":"{$key}";
			}
						$sql="
			SELECT * FROM `shop_categories` WHERE `categories_of_commodities_ID` IN ({$fff});";//echo $sql;die();
			$res=mysql_query($sql);
			while($row=mysql_fetch_assoc($res))
			{
				$parents[$row["categories_of_commodities_ID"]]=$row["categories_of_commodities_parrent"];
				$rrr.=",{$row["categories_of_commodities_parrent"]}";
			}
			
			//echo $rrr;die();
			
			$com_id=$_GET["com_id"];//echo str_replace(",,",",",$_GET["ids"].",".$rrr);die();
			$arr_data = explode(',',str_replace(",,",",",$_GET["ids"].$rrr));
			if(count($arr_data))
			foreach($arr_data as $key=>$value)
			{
				//if(is_numeric($parents[$value]))
				$arr_data2[$parents[$value]]=1;//echo $parents[$value].",";
				//if(is_numeric($value))
				$arr_data2[$value]=1;
			}
			$filters = get_filters_list($com_id,$arr_data2);
			$filters=str_replace("'","\"",$filters);
			$filters=str_replace("\r\n","",$filters);
			$filters=str_replace("\n","",$filters);
			$filters=str_replace("	","",$filters);
			echo $_GET['callback']."({textr:'{$filters}'})";
		
		}
	}

?>