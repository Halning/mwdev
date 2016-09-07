<?
if ($_SESSION['status']=="admin")
{
	if(isset($_GET["site_id"]))
	{
		$site_id=$_GET["site_id"];
		if(isset($_POST["submit"]))
		{			
			$query="
DELETE FROM `domens` 
WHERE `domenID`='{$site_id}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());
			$query="
DELETE FROM `domens_description` 
WHERE `dom_id`='{$site_id}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());
			


			$center="Сайт успешно удален <br><br><a href='/?admin=all_sites'>Перейти к списку сайтов</a>";
			require_once("templates/{$theme_name}/mess.php"); 

		}else
		{
			if($_GET["site_id"]==0)
			{
				$center="Вы не можете удалить настройки по умолчанию <br><br><a href='/?admin=all_sites'>Перейти к списку сайтов</a>";
				require_once("templates/{$theme_name}/mess.php"); 
			}else
			{
				$it_name="домен";
				$it_back="/?admin=all_sites";
				require_once("templates/{$theme_name}/admin.delete.php");
			}
		}
	}
}
?>