<?
if ($_SESSION['status']=="admin")
{
	if(isset($_GET["id"]))
	{
		$id=$_GET["id"];
		if(isset($_POST["submit"]))
		{			
			$query="
DELETE FROM `parser` 
WHERE `id`='{$id}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());


			$center="Скидка успешно удалена <br><br><a href='/?admin=parser'>Перейти к списку правил</a>";
			require_once("templates/{$theme_name}/mess.php"); 

		}else
		{
			
			$it_name="правило";
			$it_back="/?admin=parser";
			require_once("templates/{$theme_name}/admin.delete.php");
			
		}
	}
}
?>