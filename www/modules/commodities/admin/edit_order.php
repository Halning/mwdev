<?

include 'brenda_name.php';

if ($_SESSION['status']=="admin")
{
	$_SESSION["lastpage2"]="/?admin=all_orders";
	if(isset($_GET["id"]))
	{
		$id=$_GET["id"];
		$glb["templates"]->set_tpl('{$id}',$id);
		$total_count = 0;
		$sql="SELECT * FROM `shop_orders` 
		WHERE `id`='{$id}'";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$sql2="
			SELECT * FROM `shop_orders_coms`
			LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
			WHERE `offer_id`='{$row["id"]}' AND `count`>0;";
			$res2=mysql_query($sql2);
			while($row2=mysql_fetch_assoc($res2))
			{
				$basket_com_price_one = 0;
				$com_id = $row2["com_id"];
				$com_selected1 = "";
				$com_selected2 = "";
				$com_selected3 = "";
				$com_selected4 = "";
				$com_selected5 = "";
				$com_selected0 = "";
				$com_selected6 = "";
				$price2=$row2["price"];
				$linecolor = "";
				$status = $row2["com_status"];
				if($status == 1){
					$com_selected1 = "selected";
					$linecolor = "greenline";
				} elseif($status == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
					$price -= $price2;
					$total_count --;
				} elseif($status == 3){
					$com_selected3 = "selected";
					$price -= $price2;
					$total_count --;
				} elseif($status == 0){
					$com_selected0 ="selected";
				} elseif($status == 4){
					$com_selected4 ="selected";
				} elseif($status == 5){
					$com_selected5 ="selected";
				} elseif($status == 6){
					$com_selected6 ="selected";
				} 
			
				$basket_com_cat =brenda_name($row2["cod"]);

				

				$glb["templates"]->set_tpl('{$basket_com_cat}',$basket_com_cat);
					if($row2["com_color"] == ""){
						$color = get_color_to_order($com_id);
					} else{
						$color = $row2["com_color"];
					}
					
				
					
				$glb["templates"]->set_tpl('{$color}',$color);
				if ($row2["count"]> 0){
					$basket_com_price_one =  $price2/$row2["count"];
				}

			    
			    $total_price +=$price2;
			    
				$total_count += $row2["count"];
				$price += $price2;
				$url=$row2["alias"]!=""?"/pr{$row2["com_id"]}_{$row2["alias"]}/":"/pr{$row2["com_id"]}/";
				$src=$row2["alias"]!=""?"<img src='/{$row2["com_id"]}stitle/{$row2["alias"]}.jpg' style='height:30px;'>":"";
				$lines.="
				<tr id='{$row2["id"]}' rel='shop_orders_coms' rel2='id' class ='{$linecolor}'>
				<td><input type='checkbox' class='cl_trt' rel='{$row2["id"]}'/></td>
				<td>{$basket_com_cat}</td><td>{$row2["cod"]}</td>
				<td id = 'com_color' class='cl_edit'>{$color}</td>
				<td id = 'com' class='cl_edit'>{$row2["com"]}</td>
				<td>{$row2["count"]}</td>
				<td>{$glb["cur"][$glb["cur_id"]]}</td>
				<td>{$basket_com_price_one}</td>
				<td>{$price2}</td>
				<td><a href='{$url}'>{$url}</a></td>
				<td><a href = '{$row2['from_url']}' target = '_blank'>Источник</a></td>
				<td id = 'man_comment' class='cl_edit'>{$row2["man_comment"]}</td>
				<td>
				<select size='1' name='status' id = 'select_status_com' rel = '{$row2['id']}'>
					<option value= '0' {selected0}></option>
					<option value='1' rel='{$row2['id']};1' {$com_selected1}>Есть в наличии</option>
    				<option value='2' rel='{$row2['id']};2' {$com_selected2}>Нет в наличии</option>
    				<option value='3' rel='{$row2['id']};3'{$com_selected3}>Замена</option>
    				<option value ='4' {$com_selected4}>оплачен</option>
    				<option value ='5' {$com_selected5}>ожидается</option>
    				<option value ='6' {$com_selected6}>на складе MW</option>
    			</select>
				

				</td>

				</tr>";
			}
			//$price=get_oreder_sum($id);
			$sql="
			SELECT * FROM `shop_payments_methods` 
			ORDER BY `order`;";
			$res=mysql_query($sql);
			while($row3=mysql_fetch_assoc($res))
			{
				$payment_name=$row3['id']==$row['payment']?$row3['name']:$payment_name;
				$selected=$row3['id']==$row['payment']?"selected":"";
				$payments_lines.="<option value='{$row3['id']}' {$selected}>{$row3['name']}</option>";
			}
			$payments_lines=$payments_lines!=""?"<select id='id_sb_opl'>{$payments_lines}</select>":"";

			
			$sql="
			SELECT * FROM `shop_delivery` 
			ORDER BY `order`;";
			$res=mysql_query($sql);
			while($row3=mysql_fetch_assoc($res))
			{
				$delivery_name=$row3['id']==$row['delivery']?$row3['name']:$delivery_name;
				$selected=$row3['id']==$row['delivery']?"selected":"";
				$delivery_lines.="<option value='{$row3['id']}' {$selected}>{$row3['name']}</option>";
			}
			$delivery_lines=$delivery_lines!=""?"<select id='id_sb_dost'>{$delivery_lines}</select>":"";
			
			foreach($offer_status as $key=>$value)
			{
				$selected=$key==$row["status"]?"selected":"";
				$status_name=$key==$row["status"]?$value:$status_name;
				$status_lines.="<option value='{$key}' {$selected}>{$value}</option>";
			}
                        $total_price1 = $price + $row['commission'] + $row['delivery_price'];
			$status_lines="<select id='id_status'>{$status_lines}</select>";
			$cur=$glb["cur"][$row["cur_id"]];
			$additions_buttons=get_edit_buttons2("/?admin=delete_order&id={$id}");
			$it_item="Редактирование заказа";
			require_once("modules/commodities/templates/admin.order_edit.php"); 
			require_once("templates/$theme_name/admin.edit.php"); 
			$glb["templates"]->set_tpl('{$total_count}',$total_count);
		}		
		
	}
	
	//===================Export XLS===============================================	
		if(isset($_GET["import"])){
			$id=$_GET["id"];
			require_once 'modules/commodities/admin/import_xls.php';
		}	
}
?>
