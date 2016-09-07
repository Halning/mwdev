<?
if ($_SESSION['status']=="admin")
{
if(is_numeric($_GET["site_id"]))
{
	$site_id=$_GET["site_id"];


if (isset($_POST["add_site"]))
{
	$domen=$_POST['domen'];
	$title=$_POST['title'];
	$description=$_POST['description'];
	$keywords=$_POST['keywords'];
	$content=$_POST['content'];
	$def_cur=$_POST['def_cur'];
	$def_lng=$_POST['def_lng'];
	
	$temp=$_POST['temp'];
	
	$query="
UPDATE `domens` 
SET 
`domen`='{$domen}', 
`www_domen`='www.{$domen}', 
`theme_name`='{$temp}',
`cur_id`='{$def_cur}',
`lng_id`='{$def_lng}'
WHERE `domenID`='{$site_id}'
;";
	@mysql_query($query); 

	$query="
DELETE FROM `domens_description` 
WHERE
`lng_id`='{$sel_lang}' AND
`dom_id`='{$site_id}'
;";
	@mysql_query($query);	
	$query="
INSERT INTO `domens_description` 
SET
`title`='{$title}', 
`description`='{$description}', 
`keywords`='{$keywords}',
`content`='{$content}', 
`lng_id`='{$sel_lang}',
`dom_id`='{$site_id}'
;";
	@mysql_query($query);
	$center="<br />Сайт успешно изменен<br /> <br /><a href='/?admin=all_sites'>список всех сайтов</a>";
	require_once("templates/{$theme_name}/mess.php"); 
	

}else
{
	//Чтение текущих параметров домена
	$query="
SELECT * FROM `domens` 
WHERE
`domenID`='{$site_id}'
;";
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_object($result);
		$r_theme_name=$row->theme_name;
		$r_lng_id=$row->lng_id;
		$r_cur_id=$row->cur_id;
		$domen=$row->domen;
	}
$query="
SELECT * FROM `domens_description` 
WHERE
`dom_id`='{$site_id}'
;";
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_object($result);
		$title=$row->title;
		$keywords=$row->keywords;
		$description=$row->description;
		$content=$row->content;
	}
	//Чтение текущих параметров домена

//Выбор языка2
	$_SESSION['sel_lang']=isset($_POST["sel_lang"])?$_POST["sel_lang"]:$_SESSION['sel_lang'];
	$sel_lang=is_numeric($_SESSION['sel_lang'])?$_SESSION['sel_lang']:1;
	$query2 = "SELECT * FROM `languages` ORDER BY `languages_id`;";
	$result2 = mysql_query($query2);
	if (mysql_num_rows($result2) > 0)
	{
		for($i=1;$i<=mysql_num_rows($result2);$i++)
		{
			$row2 = mysql_fetch_object($result2);
			$languages_id=$row2->languages_id;
			$lng_name=$row2->name;			
			$selected=$r_lng_id==$languages_id?"selected='selected'":"";
			$langs_options2.=" <option value='{$languages_id}' {$selected}>{$lng_name}</option>";						
		}			
	}
//Выбор языка2
	$dir = opendir("templates") or die("Не могу открыть папку");
	while ($file = readdir($dir))
	{
		if ($file != "." && $file != ".."&&is_dir("templates/$file")&& $file != "admin")
		{
			$selected=$r_theme_name==$file?"selected":"";
			$temp_options.=" <option value='{$file}' {$selected}>{$file}</option>";
		}
	}

$query = "SELECT * FROM `shop_cur`;";
$result=mysql_query($query) or die (mysql_error()); 
if (mysql_num_rows($result) > 0)
{
	$curses="";
	for($ij=1;$ij<=mysql_num_rows($result);$ij++)
	{
		$row = mysql_fetch_object($result);
		$cur_id=$row->cur_id;
		$cur_name=$row->cur_name;
		$full_name=$row->full_name;
		
		$selected=$cur_id==$r_cur_id?"selected='selected'":"";
		$curses=$curses."<option value='{$cur_id}' {$selected}>{$full_name}</option>";
	}
}

	$temp_select="
	Шаблон:<br>
	
		<select name='temp'>
			{$temp_options}
		</select>
	
";
$def_lng_select="
	Язык по умолчанию:<br>
	
		<select name='def_lng'>
			{$langs_options2}
		</select>
	
";
$def_cur_select="
	Валюта по умолчанию:<br>
	
		<select name='def_cur'>
			{$curses}
		</select>
	
";
	$it_item="Редактирование сайта";
	$additions_buttons=get_edit_buttons("/?admin=delete_site&site_id={$site_id}");
	require_once("modules/sites/templates/admin.edit_site.php"); 
	require_once("templates/{$theme_name}/admin.edit.php");
}
}
}
?>