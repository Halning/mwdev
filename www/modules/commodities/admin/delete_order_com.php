<?
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
					$query="DELETE FROM `shop_orders_coms` WHERE `id`='{$id}';";
					mysql_query($query);
			
					$center="Заказанный товар успешно удален <br><br><a href='/?admin=orders_brands'>Перейти к списку всех заказов о брендам</a>";
					require_once("templates/$theme_name/mess.php"); 
				}
			}else
				{
					
					require_once("templates/$theme_name/admin.delete.php");
				}
		}
	}