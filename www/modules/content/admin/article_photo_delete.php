<?
if ($_SESSION['status']=="admin")
{
	$articleID=$_GET["articleID"];
	if(isset($_GET["photo_id"]))
	{
		$photo_id=$_GET["photo_id"];
		if(isset($_POST["submit"]))
		{			
			$query="
DELETE FROM `content_images` 
WHERE `img_id`='{$photo_id}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());

			

			$center="Изображение успешно удалено <br><br><a href='/?admin=article_photos&articleID={$articleID}'>Перейти к списку фотографий</a>";
			require_once("templates/{$theme_name}/mess.php"); 

		}else
		{
			$it_name="изображение";
			$it_back="/?admin=article_photos&articleID={$articleID}";
			require_once("templates/{$theme_name}/admin.delete.php");
		}
	}
}
?>