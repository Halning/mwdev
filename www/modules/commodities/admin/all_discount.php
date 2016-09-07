<?
if ($_SESSION['status']=="admin")
{

	$query="
SELECT * FROM `shop_discount`
ORDER BY `dis_val1`
;
";
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		for($i=1;$i<=mysql_num_rows($result);$i++)
		{
			$row = mysql_fetch_object($result);
			$dis_id=$row->dis_id;
			$dis_val1=$row->dis_val1;
			$dis_val2=$row->dis_val2;
			require("modules/commodities/templates/admin.discount.all.line.php"); 		
			$all_lines.=$all_line;
		}
	}

	$its_name="Все склады";
	$additions_buttons=get_new_buttons("/?admin=add_discount","Добавить скидку");
	require("modules/commodities/templates/admin.discount.all.head.php");
	require_once("templates/{$theme_name}/admin.all.php");
}
?>