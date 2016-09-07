<?
if ($_SESSION['status']=="admin")
{
	$_SESSION["lastpage2"]="/?admin=all_commodities";
	if(isset($_GET["commodityID"]))
	{
		if(isset($_POST["add_commodity"]))
		{
			$ids=explode(",",$_GET["commodityID"]);
			foreach($ids as $key=>$value)
			{
				if($value!=0)
				{
					$commodityID=$value;
					$_SESSION["lastpage"]=$request_url;
					
					$commodity_old_price=$_POST['commodity_old_price'];
					if($commodity_old_price!="")
					{
						$query="UPDATE `shop_commodity` SET `commodity_old_price`='{$commodity_old_price}' WHERE `commodity_ID`='{$commodityID}';";
						mysql_query($query);
					}
					
					$an_price=$_POST['price'];
					if($an_price!="")
					{
						$query="UPDATE `shop_commodity` SET `commodity_price`='{$an_price}' WHERE `commodity_ID`='{$commodityID}';";
						mysql_query($query);
					}

					$an_price2=$_POST['price2'];
					if($an_price2!="")
					{
						$query="UPDATE `shop_commodity` SET `commodity_price2`='{$an_price2}' WHERE `commodity_ID`='{$commodityID}';";
						mysql_query($query);
					}
					$cur_id=$_POST["cur_id"];
					if($cur_id!="")
					{
						$query="UPDATE `shop_commodity` SET `cur_id`='{$cur_id}' WHERE `commodity_ID`='{$commodityID}';";
						mysql_query($query);
					}
					
					$an_status=$_POST['status'];
					if($an_status>0)
					{
						$query="UPDATE `shop_commodity` SET `commodity_status`='{$an_status}' WHERE `commodity_ID`='{$commodityID}';";
						mysql_query($query);
					}
					
					$an_v=$_POST['visible'];
					if($an_v!="")
					{
						$query="UPDATE `shop_commodity` SET `commodity_visible`='{$an_v}' WHERE `commodity_ID`='{$commodityID}';";
						mysql_query($query);
					}					
					
					$commodity_action=$_POST['commodity_action'];	
					if($commodity_action!="")
					{
						$query="UPDATE `shop_commodity` SET `commodity_action`='{$commodity_action}' WHERE `commodity_ID`='{$commodityID}';";
						mysql_query($query);
					}	
					
					$commodity_hit=$_POST['commodity_hit'];
					if($commodity_hit!="")
					{
						$query="UPDATE `shop_commodity` SET `commodity_hit`='{$commodity_hit}' WHERE `commodity_ID`='{$commodityID}';";
						mysql_query($query);
					}	
					
					$commodity_new=$_POST['commodity_new'];	
					if($commodity_hit!="")
					{
						$query="UPDATE `shop_commodity` SET `commodity_new`='{$commodity_new}' WHERE `commodity_ID`='{$commodityID}';";
						mysql_query($query);
					}					

					$query = "
					DELETE FROM `shop_commodities-categories` 
					WHERE `commodityID`='{$commodityID}';";
					//mysql_query($query);	
					//{
						if(isset($_POST["category"]))
						foreach ($_POST['category'] as $keys=>$values) 
						{
							if($values!=0)
							{
								$query="
								INSERT INTO `shop_commodities-categories` 
								SET `commodityID`='{$commodityID}', `categoryID`='{$values}';";
								mysql_query($query);
							}
						}
					//}
					$insert_filter = insert_filter_values2($commodityID,$teh);
				}
				$center="
				Товары успешно отредактированы<br><br>
				<a href='/?admin=all_commodities'>Список всех товаров</a>
				";
				require_once("templates/$theme_name/mess.php"); 
			}
		}else
		{
			$e_visible="checked";
			
			if(!count($active_cats))
			$active_cats[0]=1;
			$categories=get_commodity_categories($active_cats);
			
			if(count($glb["cur_aviable"]))
			foreach($glb["cur_aviable"] as $key=>$value)
			{
				$currsss.=$cur_id==$key?"<option value='{$key}' selected>{$value}</option>":"<option value='{$key}'>{$value}</option>";
			}
			if(count($glb["cstatus"]))
			foreach($glb["cstatus"] as $key=>$value)
			{
				$status_options.=$key==$an_status?"<option value='{$key}' selected>{$value}</option>":"<option value='{$key}'>{$value}</option>";
			}

			$active_cats[0]=1;
			$filters = get_filters_list($commodityID,$active_cats);
			$commodities_images = get_commodities_images($commodityID);
			$ses_id=session_id();
			
			$it_item="Редактирование товара";
			$additions_buttons=get_edit_buttons("/?admin=delete_commodity&commodityID={$commodityID}");
			require_once("modules/commodities/templates/admin.commodity_edit2.php"); 
			require_once("templates/$theme_name/admin.edit.php"); 
		}
	}
}
?>