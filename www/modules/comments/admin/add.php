<?
admin_names("comments");
if ($_SESSION['status']=="admin")
{

{
		$name=$_POST["name"];
		$city=$_POST["city"];
		$email=$_POST["email"];
		$text=$_POST["text"];
		$par_id=$_GET["par_id"];
		$date=$_POST["date"];
		$par_opt=is_numeric($par_id)?"`parent_id`='{$par_id}',":"`parent_id`='0',";
		$item_prefix=$_POST["item_prefix"];	
		$user_id=$_SESSION['user_id'];
		$comment_access=$_POST["comment_access"];
if (isset($_POST["add_{$modul_name}"]))
{


	$query="
INSERT INTO `comments` 
SET 
`comment_email`='{$email}',
`comment_date`='{$date}',
`user_id`='{$user_id}',
{$par_opt}
`item_prefix`='{$item_prefix}'
;";
	mysql_query($query);

	$query="
SELECT * FROM `comments` 
WHERE
`comment_email`='{$email}' AND
`comment_date`='{$date}' AND
`user_id`='{$user_id}' AND
`item_prefix`='{$item_prefix}'
;";
	
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_object($result);
		$line_id=$row->comment_id;
	}


	$query="
DELETE FROM `comments_descriptions` 
WHERE `comm_id`='{$line_id}' AND `lng_id`='{$sel_lang}'
;";
	@mysql_query($query);
	$query="
INSERT `comments_descriptions` 
SET 
`comm_id`='{$line_id}',
`comment_text`='{$text}',
`comment_name`='{$name}', 
`comment_city`='{$city}', 
`lng_id`='{$sel_lang}'
;";
mysql_query($query);
//Обновление прав доступа
	$query="
DELETE FROM `comments_access` 
WHERE `comm_id`='{$line_id}'
;";
	@mysql_query($query);
		
	if($comment_access!="")
	foreach ($comment_access as $value)
	{
		$query="
INSERT `comments_access` 
SET 
`comm_id`='{$line_id}',
`dom_id`='{$value}'
;";
		@mysql_query($query);
	}

	;
//Обновление прав доступа
	

	$center="<br />Успешно добавлено<br /> <br /><a href='/?admin=all_{$modul_name}'>список всех</a>";
	require_once("templates/{$theme_name}/mess.php"); 
	

}else
{
	

	//Чтение текущих параметров ссылки
if(is_numeric($par_id))
{
	$query="
SELECT * FROM `comments` 
WHERE
`comment_id`='{$par_id}'
;";
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_object($result);
		$item_prefix=$row->item_prefix;
	}
}
	//Чтение текущих параметров ссылки

	//Все области
	$query="
SELECT * FROM `comments_types` 
;";
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		for($i=1;$i<=mysql_num_rows($result);$i++)
		{
			$row = mysql_fetch_object($result);
			$r_name=$row->type_name;
			$type_prefix=$row->type_prefix;
			$selected=$type_prefix==$item_prefix?"selected":"";
			$disabled=($item_prefix!="")?"disabled":"";
			$disabled=($item_prefix==$type_prefix)?"":$disabled;
			$types_options.=" <option value='{$type_prefix}' {$selected} {$disabled}>{$r_name}</option>";
		}
	}
	//Все области
		
	//Доступ


	$query = "
SELECT * FROM `domens` 
ORDER BY `domenID`;
";
	$sites="";
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		for($i=1;$i<=mysql_num_rows($result);$i++)
		{
			$row = mysql_fetch_object($result);
			$domen=$row->domen;
			$did=$row->domenID;	
			$checked=($did==0)?"checked":"";
			$sites.="<br /><input name='comment_access[]' id='domen{$did}' value='{$did}' {$checked} type='checkbox'><label for='domen{$did}'>{$domen}</label>
";
		}
	}
	//Доступ
	$date=date("Y-m-d H:i:s");
	if(is_numeric($_GET["par_id"]))
	{
		$name="Интернет-магазин Альказар";
		$email="info@alkazar.com.ua";

	}
	$it_item="Добавление {$mod_name4}";
	$additions_buttons=get_add_buttons("/?admin=delete_{$modul_name}&{$modul_name}_id={$line_id}");
	require_once("modules/{$modul_name}/templates/admin.edit.php"); 
	require_once("templates/{$theme_name}/admin.edit.php");
}
}
}
?>