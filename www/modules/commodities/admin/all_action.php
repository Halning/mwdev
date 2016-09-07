<?php
if ($_SESSION['status']=="admin")
{

	
if(is_numeric($_GET["com_id"]))
{
	$com_id=$_GET["com_id"];
	stars_update($com_id);
	if(isset($_POST["move"]))
	{
		$item_id=$_POST["item_id"];
		$sing=$_POST["move"]=="up"?"-":"+";
		$query = "
UPDATE `shop_action` 
SET `act_order`=(`act_order`{$sing}1)
WHERE `act_id`='{$item_id}';
";
		
		mysql_query($query);

	}

	$_SESSION["category"]=is_numeric($_POST["category"])?$_POST["category"]:$_SESSION["category"];
	$show_admin_cat=is_numeric($_SESSION["category"])?$_SESSION["category"]:0;


		

	$query = "
SELECT * FROM `shop_action` 
WHERE `act_com_id`='{$com_id}' 
ORDER BY `act_order`
";
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		$categories="";
		for($i=1;$i<=mysql_num_rows($result);$i++)
		{
			$row = mysql_fetch_object($result);
			$act_other_com_id=$row->act_other_com_id;
			$order=$row->act_order;
			$act_id=$row->act_id;

			$query2 = "
SELECT * FROM `shop_commodity_description` 
WHERE `com_id` ='{$act_other_com_id}' AND `lng_id`='{$sel_lang}' 
";	
			$result2 = mysql_query($query2);
			if (mysql_num_rows($result2) > 0)
			{
				$row2 = mysql_fetch_object($result2);
				$r_commodity_name=$row2->com_name;
				$c_shorttext=$row2->com_desc;
				$r_commodity_code=$row2->cod;
				$c_fulltext=$row2->com_fulldesc;
				$query2 = "
SELECT * FROM `shop_commodity` 
WHERE `commodity_ID` ='{$act_other_com_id}';
";	
				$result2 = mysql_query($query2);
				if (mysql_num_rows($result2) > 0)
				{
					$row2 = mysql_fetch_object($result2);
					$r_commodity_code=$row2->cod;
				}
			}else
			{
				$r_commodity_name="нет описания на этом языке";
			}
			require("modules/commodities/templates/admin.act.all.line.php"); 
			$all_lines.=$all_line;
		}
		
	}
$query2 = "
SELECT * FROM `shop_commodity_description` 
WHERE `com_id` ='{$com_id}' AND `lng_id`='{$sel_lang}' 
";	
			$result2 = mysql_query($query2);
			if (mysql_num_rows($result2) > 0)
			{
				$row2 = mysql_fetch_object($result2);
				$r_commodity_name=$row2->com_name;

			}
	$its_name="Акционные товары - ".$r_commodity_name;
	$additions_buttons=get_new_buttons("/?admin=add_action&com_id={$com_id}","Добавить акционный товар");
	require("modules/commodities/templates/admin.act.all.head.php"); 
	require_once("templates/$theme_name/admin.all.php"); 
}
}
?>