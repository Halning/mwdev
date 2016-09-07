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
	if(isset($_GET["id"]))
	{
		$filterID=$_GET["filterID"];
		$id=$_GET["id"];
		if(isset($_POST["submit"]))
		{		
			$query="DELETE FROM `shop_filters-lists` WHERE `id`='{$id}';";
			mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());
			$center = '';
//			$center="Элемент успешно удален <br><br><a href='/?admin=maneger_list&filterID={$filterID}'>Перейти к списку пунктов</a>";
			require_once("templates/$theme_name/mess.php");
		}else
		{
			$it_name="элемент";
			require_once("templates/{$theme_name}/admin.delete.php");
		}
	}
}

?>