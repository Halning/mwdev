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
		$id2=$_GET["id"];
		
		if(isset($_POST["submit"]))
		{
			$rrrr=explode(",",$id2);
			foreach($rrrr as $key=>$value)
			{	
				$id=$value;	
				$query="DELETE FROM `shop_orders` WHERE `id`='{$id}';";
				mysql_query($query);
				mysql_query("DELETE FROM `shop_orders_coms` WHERE `offer_id`='{$id}';");
				$query="DELETE FROM `offer_id` WHERE `offer_id`='{$id}';";
				mysql_query($query);
				$center="Заказ успешно удален <br><br><a href='/?admin=all_orders'>Перейти к списку всех заказов</a>";
				require_once("templates/$theme_name/mess.php"); 
			}
		}else
		{
			$it_name="заказ";
			require_once("templates/{$theme_name}/admin.delete.php");
		}
	}
}


?>