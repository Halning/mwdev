<?
if ($_SESSION['status']=="admin")
		{
			
		if(isset($_GET["id"]))
			{
			$id2=$_GET["id"];
			echo($id2);
			$today = date("Y-m-d H:i:s"); 
			$query2 = "INSERT INTO `sup_group`(`group_id`, `status`, `date`) VALUES (null,1, '{$today}')";
			mysql_query($query2);
			
				$group_id=mysql_insert_id();
				echo("groupid__".$group_id);

				$rrrr=explode(",",$id2);
				foreach($rrrr as $key=>$value)
				{
					$id=$value;	
					echo($id);
					$sql = "
					SELECT * FROM `shop_orders_coms`
					LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
					WHERE `id`='{$id}';";
					$res=mysql_query($sql);
					if($row=mysql_fetch_assoc($res)){
						$com_id = $row["com_id"];
						echo("____com_id".$com_id);

						$sql2="
						SELECT * FROM `shop_commodities-categories`
						INNER JOIN `shop_categories` ON `shop_categories`.`categories_of_commodities_ID`=`shop_commodities-categories`.`categoryID`
						WHERE `commodityID`='{$com_id}' AND `categories_of_commodities_parrent`='10' ";
						$res2=mysql_query($sql2);

						if($row2=mysql_fetch_assoc($res2))
						{

							$basket_com_cat=$row2["cat_name"];
							$sup_id = $row2["categories_of_commodities_ID"];
							echo("____sup_id".$sup_id);
							echo($basket_com_cat);
						}
					$query3 = "UPDATE `shop_orders_coms` SET `group_id`={$group_id} , `com_status`=0  WHERE `id` = {$id};";
					mysql_query($query3);

					$query4 = "UPDATE `sup_group` SET `sup_id` = {$sup_id} WHERE `group_id` = {$group_id};";
					mysql_query($query4);


						
				}
			}
				//$center = "<h2>Заказы успешно сформирваны <a href = '/?admin=orders_brands'>Вернуться к заказам по брендам</a></h2>";
				header("Location: /?admin=orders_brands20");
		}

	}






						