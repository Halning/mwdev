<?
admin_names("comments");
if ($_SESSION['status']=="admin")
{
	if(isset($_GET["{$modul_name}_id"]))
	{
		$line_id=$_GET["{$modul_name}_id"];
		if(isset($_POST["submit"]))
		{			
			$query="
			DELETE FROM `comments` 
			WHERE `comment_id`='{$line_id}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());

			$center="Успешно удалено <br><br><a href='/?admin=all_{$modul_name}'>Перейти к списку</a>";
			require_once("templates/{$theme_name}/mess.php"); 

		}else
		{
			
			$it_name=$mod_name2;
			$it_back="/?admin=all_{$modul_name}";
			require_once("templates/{$theme_name}/admin.delete.php");
			
		}
	}
}
?>