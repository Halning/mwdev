<?
if ($_SESSION['status']=="admin")
{

{
	$site_id=$domenID;

$_SESSION["lastpage2"]="/?admin=edit_site_default";
if (isset($_POST["add_site"]))
{ 
	$domen=$_POST['domen'];
	$title=$_POST['title'];
	$description=$_POST['description'];
	$keywords=$_POST['keywords'];
	$content=$_POST['content'];
	$def_cur=$_POST['def_cur'];
	$def_lng=$_POST['def_lng'];
	$watermark=$_POST['watermark'];
	$comitemst=$_POST['comitemst'];
	$comitemsx=$_POST['comitemsx'];
	$comitemsy=$_POST['comitemsy'];
	$catitemscount=$_POST['catitemscount'];
	$catitemsx=$_POST['catitemsx'];
	$catitemsy=$_POST['catitemsy'];
	$catitemst=$_POST['catitemst'];
	$addcomimgx=$_POST['addcomimgx'];
	$addcomimgy=$_POST['addcomimgy'];
	$addcomimgt=$_POST['addcomimgt'];
	$artimgaddx=$_POST['artimgaddx'];
	$artimgaddy=$_POST['artimgaddy'];
	$artimgaddt=$_POST['artimgaddt'];
	$artimgx=$_POST['artimgx'];
	$artimgy=$_POST['artimgy'];
	$artimgt=$_POST['artimgt'];
	$email=$_POST['email'];
	$temp=$_POST['temp'];
	
	$query="
UPDATE `domens` 
SET 
`watermark`='{$watermark}',
`cur_id`='{$def_cur}',
`lng_id`='{$def_lng}',
`comitemst`='{$comitemst}',
`comitemsx`='{$comitemsx}',
`comitemsy`='{$comitemsy}',
`catitemscount`='{$catitemscount}',
`catitemsx`='{$catitemsx}',
`catitemsy`='{$catitemsy}',
`catitemst`='{$catitemst}',
`artimgx`='{$artimgx}',
`artimgy`='{$artimgy}',
`artimgt`='{$artimgt}',
`artimgaddx`='{$artimgaddx}',
`artimgaddy`='{$artimgaddy}',
`artimgaddt`='{$artimgaddt}',
`addcomimgx`='{$addcomimgx}',
`addcomimgy`='{$addcomimgy}',
`addcomimgt`='{$addcomimgt}',
`email`='{$email}'
WHERE `domenID`='{$site_id}'
;";
	mysql_query($query); 

	$query="
UPDATE `domens_description` 
SET
`title`='{$title}', 
`description`='{$description}', 
`keywords`='{$keywords}',
`content`='{$content}' WHERE 
`lng_id`='{$sel_lang}' AND
`dom_id`='{$site_id}'
;";
	@mysql_query($query);
	
	if(isset($_FILES["myfile1"]))
	{
		if($glb["use_ftp"])
		{
			$ftp_conn=ftp_connect($gallery_domen);
			$ftp_log=ftplogin($ftp_conn);
		}
		$dest="images/watermark1.png";
		$myfile = $_FILES["myfile1"]["tmp_name"];
		$myfile_name = $_FILES["myfile1"]["name"];
		$myfile_size = $_FILES["myfile1"]["size"];
		$myfile_type = $_FILES["myfile1"]["type"];
		$error_flag = $_FILES["myfile1"]["error"];
		if($glb["use_ftp"])
		{
			ftp_put($ftp_conn,$parrent_dir."/".$dest, $myfile, FTP_BINARY);
		}else
		{
			move_uploaded_file($myfile,$dest);
		}
	}
		
	$center="<br />Сайт успешно изменен<br /> <br /><a href='/?admin=edit_site_default'>Настройки сайта</a>";
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
		$watermark=$row->watermark;
		$comitemst=$row->comitemst;
		$comitemsx=$row->comitemsx;
		$comitemsy=$row->comitemsy;
		$catitemscount=$row->catitemscount;
		$catitemsx=$row->catitemsx;
		$catitemsy=$row->catitemsy;
		$catitemst=$row->catitemst;
		$addcomimgx=$row->addcomimgx;
		$addcomimgy=$row->addcomimgy;
		$addcomimgt=$row->addcomimgt;
		
		$artimgx=$row->artimgx;
		$artimgy=$row->artimgy;
		$artimgt=$row->artimgt;
		$artimgaddx=$row->artimgaddx;
		$artimgaddy=$row->artimgaddy;
		$artimgaddt=$row->artimgaddt;
		
		$email=$row->email;
	}
$query="
SELECT * FROM `domens`
WHERE 
`lng_id`='{$sel_lang}' AND
`domenID`='{$site_id}'
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
/*	$_SESSION['sel_lang']=isset($_POST["sel_lang"])?$_POST["sel_lang"]:$_SESSION['sel_lang'];
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
			$langs_options3.=" <option value='{$languages_id}' {$selected}>{$lng_name}</option>";						
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
*/
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
	
	
		<select name='def_lng'>
			{$langs_options3}
		</select> - язык по умолчанию<br>
	
";
$def_cur_select="

	
		<select name='def_cur'>
			{$curses}
		</select> - валюта по умолчанию<br>
	
";
$watermark1=$watermark==1?"checked":"";
$watermark2=$watermark==1?"<img src='/images/watermark1.png'>":"";


	/*foreach($cimgm as $key=>$value)
	{
		$imgtpeline1.=$key==$comitemst?"<option value='{$key}' selected>{$value}</option>":"<option value='{$key}'>{$value}</option>";
		$imgtpeline2.=$key==$catitemst?"<option value='{$key}' selected>{$value}</option>":"<option value='{$key}'>{$value}</option>";
		$imgtpeline3.=$key==$addcomimgt?"<option value='{$key}' selected>{$value}</option>":"<option value='{$key}'>{$value}</option>";
		$imgtpeline4.=$key==$artimgt?"<option value='{$key}' selected>{$value}</option>":"<option value='{$key}'>{$value}</option>";
		$imgtpeline5.=$key==$artimgaddt?"<option value='{$key}' selected>{$value}</option>":"<option value='{$key}'>{$value}</option>";
	}*/

	$it_item="Настройки сайта";
	$additions_buttons=get_add_buttons();
	require_once("modules/sites/templates/admin.edit_site.php"); 
	require_once("templates/{$theme_name}/admin.edit.php");
}
}
}
?>