<?
if ($_SESSION['status']=="admin")
{
	$file_path="/templates/{$glb["theme_name"]}/main.tpl";
	$filename=$_GET["folder"]!=""?$_GET["folder"]."/".$_GET["file"]:$_GET["file"];
	$file_path=$filename!=""?"/templates/{$glb["theme_name"]}/{$filename}":$file_path;
	if(file_exists($_SERVER['DOCUMENT_ROOT'].$file_path))
	{
		if(isset($_POST["edit_tpl"]))
		{
			$context = stream_context_create(array(
				'ftp'=>array(
						'overwrite' => true,
				),
				'file'=>array(
					'overwrite' => true,
				),
			));
			$tpl_content=stripslashes($_POST['tpl_content']);
			if($glb['use_ftp'])
				$handler=fopen("ftp://{$glb["ftp_login"]}:{$glb["ftp_password"]}@{$gallery_domen}/{$parrent_dir}{$file_path}",'w',false,$context);
			else
				$handler=fopen($_SERVER['DOCUMENT_ROOT'].$file_path,'w',false,$context);
			if(!$handler)
				die("Ошибка записи файла!");
			fwrite($handler,$tpl_content);
			fclose($handler);

			$center="Шаблон изменен<br><br>
			";
			require_once("templates/$theme_name/mess.php"); 
	
		}else
		{
			$tpl_content=file_get_contents($_SERVER['DOCUMENT_ROOT'].$file_path);

			$it_item="Редактирование шаблона";
			$additions_buttons=get_edit_buttons("");
			require_once("modules/sites/templates/admin.tpl_edit.php"); 
			require_once("templates/{$theme_name}/admin.edit.php"); 
		}
	}else{
		$center="Файл {$file_path} не существует!";
	}
}
?>