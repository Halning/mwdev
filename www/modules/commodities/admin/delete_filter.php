<?
/**
* OwerCMS
* Функции пользовательской части модуля управления товарами
* @package OwerCMS
* @author Ower
* @version 0.11.1
* @since engine v.0.11
* @link http://www.ower.com.ua
* @copyright (c) 2010+ by Ower
*/

if ($_SESSION['status']=="admin")
{
	if(isset($_GET["filterID"]))
	{
		$filterID=$_GET["filterID"];
		if(isset($_POST["submit"]))
		{			
			$query="DELETE FROM `shop_categories-filters` WHERE `filtr_id`='{$filterID}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());
			$query="DELETE FROM `shop_filters-descriptions` WHERE `filtr_id`='{$filterID}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());
			$query="DELETE FROM `shop_filters-values` WHERE `ticket_filterid`='{$filterID}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());
			

			$center="Фильтр успешно удален <br><br><a href='/?admin=all_categories'>Перейти к списку категорий</a>";
			require_once("templates/$theme_name/mess.php"); 

		}else
		{
			$it_name="фильтр";
			require_once("templates/{$theme_name}/admin.delete.php");
		}
	}
}

?>