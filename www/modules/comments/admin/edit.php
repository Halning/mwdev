<?
admin_names("comments");
require_once("{$bbcode_path}bbcode.lib.php");
if ($_SESSION['status']=="admin")
{


if(is_numeric($_GET["{$modul_name}_id"]))
{
	$line_id=$_GET["{$modul_name}_id"];

if (isset($_POST["add_{$modul_name}"]))
{
		$name=$_POST["name"];
		$city=$_POST["city"];
		$email=$_POST["email"];
		$text=$_POST["text"];
		$par_id=$_GET["par_id"];
		$comment_access=$_POST["comment_access"];
		$item_prefix=$_POST["item_prefix"];
		$date=$_POST["date"];
		$par_opt=is_numeric($par_id)?"`parent_id`='{$par_id}',":"";
	$query="
UPDATE `comments` 
SET 
`comment_email`='{$email}',
`comment_date`='{$date}',
{$par_opt}
`item_prefix`='{$item_prefix}'
WHERE `comment_id`='{$line_id}'
;";
	@mysql_query($query);

//Обновление текстов на определенном языке
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
	@mysql_query($query);
//Обновление текстов на определенном языке

//Обновление прав доступа
	$query="
DELETE FROM `comments_access` 
WHERE `comm_id`='{$line_id}'
;";

	@mysql_query($query);
	if($comment_access!="")
	{
		$query="
INSERT `comments_access` 
SET 
`comm_id`='{$line_id}',
`dom_id`='{$comment_access}'
;";
		@mysql_query($query);

	}

/*
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
*/

	;
//Обновление прав доступа
	
	$center="<br />Успешно изменено<br /> <br /><a href='/?admin=all_{$modul_name}'>список всех</a>";
	require_once("templates/{$theme_name}/mess.php"); 
	

}else
{
	//Чтение текущих параметров ссылки
	$query="
SELECT * FROM `comments` 
WHERE
`comment_id`='{$line_id}'
;";
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_object($result);
		$item_id=$row->comment_id;
		$email=$row->comment_email;
		$item_prefix=$row->item_prefix;
		$par_id=$row->parent_id;
		$date=$row->comment_date;
	}

	$query="
SELECT * FROM `comments_descriptions`
WHERE
`comm_id`='{$line_id}' AND `lng_id`='{$sel_lang}'
;";
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_object($result);


			$line_name=$row->comment_name;
			$bb1=new bbcode($line_name);
			$line_name=$bb1 -> get_html();

			$line_city=$row->comment_city;
			$bb2=new bbcode($line_city);
			$line_city=$bb2 -> get_html();


			$line_text=$row->comment_text;
			//$bb3=new bbcode($line_text);
			//$line_text=$bb3 -> get_html();

		$name=$line_name;
		$city=$line_city;
		$text=$line_text;
	}
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
			$disabled=($par_id!=0)?"disabled":"";
			$disabled=($item_prefix==$type_prefix)?"":$disabled;
			$types_options.=" <option value='{$type_prefix}' {$selected} {$disabled}>{$r_name}</option>";
		}
	}
	//Все области

	//Доступ

	$xx_doms=array();
	$query = "
SELECT * FROM `comments_access` 
WHERE 
`comm_id`='{$line_id}';
	";
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		for($i=1;$i<=mysql_num_rows($result);$i++)
		{
			$row = mysql_fetch_object($result);
			$dom_id=$row->dom_id;
			$xx_doms[$dom_id]=1;
		}
	}

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
			$checked=($xx_doms[$did]==1)?"checked":"";
			$domen="Показывать отзыв на сайте";
			$sites.="<br /><input name='comment_access' id='domen{$did}' value='{$did}' {$checked} type='checkbox'><label for='domen{$did}'>{$domen}</label>
";
		}
	}
	//Доступ

	$it_item="Редактирование {$mod_name4}";
	$additions_buttons=get_edit_buttons("/?admin=delete_{$modul_name}&{$modul_name}_id={$line_id}");
	require_once("modules/{$modul_name}/templates/admin.edit.php"); 
	require_once("templates/{$theme_name}/admin.edit.php");
}
}
}
?>