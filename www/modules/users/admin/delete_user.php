<?
if ($_SESSION['status']=="admin")
{
	if(isset($_GET["user_id"]))
	{
		$user_id=$_GET["user_id"];
		if(isset($_POST["submit"]))
		{			
			$query="
DELETE FROM `users` 
WHERE `user_id`='{$user_id}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());

			


			$center="Пользователь успешно удален <br><br><a href='/?admin=all_users'>Перейти к списку пользователей</a>";
			require_once("templates/{$theme_name}/mess.php"); 

		}else
		{
			$it_name="этого пользователя";
			$it_back="/?admin=all_users";
			require_once("templates/{$theme_name}/admin.delete.php");
		}
	}
}
?>