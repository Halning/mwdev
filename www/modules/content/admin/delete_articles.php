<?
if ($_SESSION['status']=="admin")
{
	if(isset($_GET["article_id"]))
	{
		$article_id=$_GET["article_id"];
		if(isset($_POST["submit"]))
		{			
			$query="
DELETE FROM `content_articles` 
WHERE `articleID`='{$article_id}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());

			$center="Статья успешно удалена <br><br><a href='/?admin=all_articles'>Перейти к списку всех статьей</a>";
			require_once("templates/$theme_name/mess.php"); 

		}else
		{
			$it_name="статью";
			$it_back="/?admin=all_articles";
			require_once("templates/{$theme_name}/admin.delete.php");
		}
	}
}
?>