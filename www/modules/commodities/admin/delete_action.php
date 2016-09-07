<?
/**
* OwerCMS
* Функции пользовательской части модуля управления товарами
* @package OwerCMS
* @author Ower
* @version 0.11.18
* @since engine v.0.11
* @link http://www.ower.com.ua
* @copyright (c) 2010+ by Ower
*/

if ($_SESSION['status']=="admin")
{
	if(isset($_GET["act_id"]))
	{
		$act_id=$_GET["act_id"];
		if(isset($_POST["submit"]))
		{			
			$query="DELETE FROM `shop_action` WHERE `act_id`='{$act_id}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());
			$com_id=$_GET["com_id"];
			stars_update($com_id);
			$center="Товар успешно удален <br><br><a href='/?admin=all_action&com_id={$com_id}'>Перейти к списку акционных товаров</a>";
			require_once("templates/$theme_name/mess.php"); 

		}else
		{
			$it_name="акционный товар";
			require_once("templates/{$theme_name}/admin.delete.php");
		}
	}
}

?>