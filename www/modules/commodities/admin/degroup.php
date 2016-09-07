<?
if ($_SESSION['status']=="admin")
{
	if(isset($_GET["id"]))
	{
		$id2=$_GET["id"];
		$rrrr=explode(",",$id2);
			foreach($rrrr as $key=>$value)
			{	
				$id=$value;	
				$query="UPDATE `shop_orders_coms` SET `group_id`= 0 WHERE `id`={$id}";
				mysql_query($query);
			
				
				$center="Заказ успешно разгруппирован <br><br><a href='/?admin=orders_brands20'>Перейти к списку всех заказов</a>";
				require_once("templates/$theme_name/mess.php"); 

				header("Location: /?admin=orders_brands20");
			}
		}
	}
