<?
if ($_SESSION['status']=="admin")
{

	$query="
SELECT * FROM `domens`
LEFT JOIN `languages` ON `languages`.`languages_id`=`domens`.`lng_id`
LEFT JOIN `shop_cur` ON `shop_cur`.`cur_id`=`domens`.`cur_id`
ORDER BY `domenID`
;
";
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		for($i=1;$i<=mysql_num_rows($result);$i++)
		{
			$row = mysql_fetch_object($result);
			$r_domenID=$row->domenID;
			$www_domen=$row->www_domen;
			$lng_name=$row->name;
			$cur_name=$row->full_name;
			$r_theme_name=$row->theme_name;
			$query2="
SELECT `title` FROM `domens_description`
WHERE `lng_id`='{$sel_lang}' AND `dom_id`='{$r_domenID}'
;
";
			$result2=mysql_query($query2);
			if (mysql_num_rows($result2) > 0) 
			{
				$row2 = mysql_fetch_object($result2);
				$title=$row2->title;
			}
			require("modules/sites/templates/admin.sites.all.line.php"); 		
			$all_lines.=$all_line;
		}
	}


	$its_name="Все сайты";
	$additions_buttons=get_new_buttons("/?admin=add_site","Добавить сайт");
	require("modules/sites/templates/admin.sites.all.head.php");
	require_once("templates/{$theme_name}/admin.all.php");
}
?>