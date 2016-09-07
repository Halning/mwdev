<?
if ($_SESSION['status']=="admin")
{
	if(isset($_GET["discount_id"]))
	{
		$discount_id=$_GET["discount_id"];
		if(isset($_POST["submit"]))
		{			
			$query="
DELETE FROM `office_discount` 
WHERE `dis_id`='{$discount_id}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());


			$center="Скидка успешно удалена <br><br><a href='/?admin=all_discount'>Перейти к списку скидок</a>";
			require_once("templates/{$theme_name}/mess.php"); 

		}else
		{
			
			$it_name="скидку";
			$it_back="/?admin=all_discount";
			require_once("templates/{$theme_name}/admin.delete.php");
			
		}
	}
}
?>