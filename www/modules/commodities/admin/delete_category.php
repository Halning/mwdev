<?
if ($_SESSION['status']=="admin")
{
	if(is_numeric($_GET["categoryID"]))
	{
		$categoryID=$_GET["categoryID"];
		if(isset($_POST["submit"]))
		{
			$query="
			DELETE FROM `shop_categories` 
			WHERE `categories_of_commodities_ID`='{$categoryID}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());

			$center="Категория успешно удалена <br><br><a href='/?admin=all_categories'>Перейти к списку категорий</a>";
			require_once("templates/$theme_name/mess.php"); 
	
		}else
		{
			$it_name="категорию товаров";
			
			$it_back="/?admin=all_categories";
			require_once("templates/{$theme_name}/admin.delete.php");
		}
	}
}
?>