<?
if ($_SESSION['status']=="admin")
{
	$modul_name="comments";
	$mod_name1=$$modul_name->name1;
	$mod_name2=$$modul_name->name2;
	$mod_name3=$$modul_name->name3;
	$mod_name4=$$modul_name->name4;
	
	if(is_numeric($_POST["add_new"]))
	{
		$date=date("Y-m-d H:i:c");
		$sql="
		INSERT INTO `comments` 
		SET `comment_date`='{$date}',
		`parent_id`='{$_POST["par1"]}',
		`item_id`='{$_POST["par2"]}',
		`item_prefix`='{$_POST["par3"]}'
		;";
		mysql_query($sql);
	}
	
	$sql="
	SELECT * FROM `comments` 
	ORDER BY `comment_date` DESC;";
	$res=mysql_query($sql);
	while($row=mysql_fetch_assoc($res))
	{
		$line_id=$row["comment_id"];
		require("modules/comments/templates/admin.all.line.php"); 
		$all_lines.=$all_line;
	}

	
	$its_name="Все {$mod_name3}";
	$additions_buttons=get_new_buttons2("Добавить {$mod_name2}");
	require("modules/{$modul_name}/templates/admin.all.head.php");
	require_once("templates/{$theme_name}/admin.all.php");
}
?>