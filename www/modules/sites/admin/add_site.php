<?
if ($_SESSION['status']=="admin")
{

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
INSERT INTO `domens` 
SET 
`domen`='{$domen}', 
`www_domen`='www.{$domen}', 
`theme_name`='{$temp}',
`cur_id`='{$def_cur}',
`lng_id`='{$def_lng}'
;";
	@mysql_query($query); 
	$query="
SELECT * FROM `domens` 
WHERE
`domen`='{$domen}' AND
`www_domen`='www.{$domen}' AND 
`theme_name`='{$temp}'
;";
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_object($result);
		$r_domenID=$row->domenID;
	}
	$query="
INSERT INTO `domens_description` 
SET
`title`='{$title}', 
`description`='{$description}', 
`keywords`='{$keywords}',
`content`='{$content}', 
`lng_id`='{$sel_lang}',
`dom_id`='{$r_domenID}'
;";
	@mysql_query($query);
	$center="<br />Сайт успешно добавлен<br /> <br /><a href='/?admin=all_sites'>список всех сайтов</a>";
	require_once("templates/{$theme_name}/mess.php"); 
	

}else
{
	$dir = opendir("templates") or die("Не могу открыть папку");
	while ($file = readdir($dir))
	{
		if ($file != "." && $file != ".."&&is_dir("templates/$file")&& $file != "admin")
		{
			$temp_options.=" <option value='{$file}'>{$file}</option>";
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
		$$cur_name=$row->cur_val;
		if($cur_name==$cur)
		{
			$selected="selected='selected'";
			$cur_show=$row->cur_show;
		}else
		{
			$selected="";
		}
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
			{$langs_options}
		</select>
	
";
$def_cur_select="
	Валюта по умолчанию:<br>
	
		<select name='def_cur'>
			{$curses}
		</select>
	
";
	$it_item="Добавление сайта";
	$additions_buttons=get_add_buttons();
	require_once("modules/sites/templates/admin.edit_site.php"); 
	require_once("templates/{$theme_name}/admin.edit.php");
}
}
?>