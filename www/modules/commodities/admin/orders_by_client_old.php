<?
if ($_SESSION['status']=="admin"){

	$sql = "SELECT * 
			FROM  `shop_orders` 
			WHERE `status` in (6,7) ORDER BY `id`  DESC";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)){
		$user_id = $row["user_id"];
		$offer_id = $row["id"];
		

		$status_selected1 = "";
		$status_selected2 = "";
		$status_selected3 = "";
		$status_selected4 = "";
		$status_selected5 = "";
		$status_selected6 = "";
		$status_selected7 = "";
		
		
		$address = $row["address"];
		$cod =$row["cod"];
		$date = $row["date"];
		$client_count["{$user_id}"] +=1;
		$lines = '';
		$status = $row["status"];
		if($status == 1){
			$status_selected1 = "selected";
		} elseif($status == 2) {
			$status_selected2 = "selected";
		} elseif($status == 3){
			$status_selected3 = "selected";
		} elseif($status == 4){
			$status_selected4 = "selected";
		} elseif($status == 5){
			$status_selected5 = "selected";
		} elseif($status == 6){
			$status_selected6 = "selected";
		} elseif($status == 7){
			$status_selected7 = "selected";
		} elseif($status == 10){
			$status_selected10 = "selected";
		} 

		

		$sql2="
			SELECT * FROM `shop_orders_coms`
			LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
			WHERE `offer_id`='{$row["id"]}' AND `count`>0;";
			$res2=mysql_query($sql2);
			while($row2=mysql_fetch_assoc($res2))
			{
				$com_id = $row2["com_id"];
				$size = $row2["com"];
				$count = $row2["count"];
				$cur_id = $row2["cur"];
				$cod2 = $row2["cod"];
				$price_opt = $row2["commodity_price2"];
				$from_url = $row2["from_url"];
				$com_name = $row2["com_name"];

				if($row2["com_color"] == ""){
						$color = get_color_to_order($com_id);
					} else{
						$color = $row2["com_color"];
					}
				$comment = $row2["man_comment"];
				$com_status = $row2["com_status"];
				$price = $row2["price"]/$count;
				$com_sum = $row2["price"];
				$order_com_id = $row2["id"];
				$com_selected1 = "";
				$com_selected2 = "";
				$com_selected3 = "";
				$com_selected4 = "";
				$com_selected5 = "";
				$com_selected0 = "";
				$com_selected6 = "";
				$order_com_id = $row2["id"];
				$linecolor = "";
				$status_com = $row2["com_status"];
				if($status_com == 1){
					$com_selected1 = "selected";
					$linecolor = "greenline";
				} elseif($status_com == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
					$client_sum["{$user_id}"]-=$com_sum;
					
				} elseif($status_com == 3){
					$com_selected3 = "selected";
				} elseif($status_com == 0){
					$com_selected0 ="selected";
				} elseif($status_com == 4){
					$com_selected4 ="selected";
				} elseif($status_com == 5){
					$com_selected5 ="selected";
				} elseif($status_com == 6){
					$com_selected6 ="selected";
				} 
				

				$sql3="SELECT * FROM `shop_commodities-categories`
				INNER JOIN `shop_categories` ON `shop_categories`.`categories_of_commodities_ID`=`shop_commodities-categories`.`categoryID`
				WHERE `commodityID`='{$row2['com_id']}' AND `categories_of_commodities_parrent`='10' ";
				$res3=mysql_query($sql3);

				if($row3=mysql_fetch_assoc($res3))
				{
					$basket_com_cat=$row3["cat_name"];
				}
				$client_sum["{$user_id}"] +=$com_sum;
				

				$lines["{$offer_id}"].="
				<tr id='{$row2["id"]}' rel='shop_orders_coms' rel2='id' class ='{$linecolor}'>
				<td>{$basket_com_cat}</td>
				<td>{$cod2}</td>
				<td>{$color}</td>
				<td>{$size}</td>
				<td>{$count}</td>
				<td>{$cur_id}</td>
				<td>{$price}</td>
				<td>{$com_sum}</td>
				<td><a href ='{$from_url}'>источник</a></td>
				<td><select size='1' name='status' id = 'select_status_com' rel = '{$order_com_id}' disabled>
							<option value= '0' {$com_selected0}></option>
							<option value='1'  {$com_selected1}>Есть в наличии</option>
    						<option value='2'  {$com_selected2}>Нет в наличии</option>
    						<option value='3'  {$com_selected3}>Замена</option>
    						<option value ='4' {$com_selected4}>оплачен</option>
    						<option value ='5' {$com_selected5}>ожидается</option>
    						<option value ='6' {$com_selected6}>на складе MW</option>
    					</select></td>
				<td id = 'man_comment' class='cl_edit'>{$row2["man_comment"]}</td>
				</tr>
				";
			}
			$order["{$offer_id}"] = "
				<tr><td colspan ='12'><span class = 'opener3'>+ </span>
				<table class ='c2_group_hide'>
				<tr class = 'c3_th_group'>
				<td></td>
				<td>{$offer_id}</td>
				<td>{$date}</td>
				<td>{$cod}</td>
				<td><a href = '?admin=mail_to_customer&id={$offer_id}'>Почта</a></td>
				<td><select size='1' name='status' id = 'select_order_status'  rel='{$offer_id}'>
					<option value='1' rel='{$offer_id};1' {$status_selected1}>Новый заказ</option>
    				<option value='2' rel='{$offer_id};2' {$status_selected2}>Обрабатывается</option>
    				<option value='3' rel='{$offer_id};3' {$status_selected3}>Подтвержден</option>
    				<option {$status_selected4}>оплачен </option>
    				<option {$status_selected5}>собран</option>
    				<option {$status_selected6}>отправлен клиенту</option>
    				<option {$status_selected7}>закрыт</option>
    				<option value='10' rel='{$offer_id}' {$status_selected10}>Oтменен</option>
            		</select></td>
				</tr>
				<tr><td colspan ='12'><span class = 'opener3'>+ </span>
							<table class ='c2_group_hide'>
								<tr class = 'c2_th_group'>
								<td>Бренд</td>
								<td>Артикул</td>
								<td>Цвет</td>
								<td>Размер</td>
								<td>Кол-во</td>
								<td>Валюта</td>
								<td>Цена</td>
								<td>Сумма</td>
								<td>Ссылка</td>
								<td>Статус</td>
								<td>Комментарий</td>
						</tr>
				";
				$order["{$offer_id}"].=$lines["{$offer_id}"];
				
				$order["{$offer_id}"] .="
				</table></td></tr></table></td></tr>";



			
			

	}
	$center = "
	<span class ='add_order_status'>Применить статусы</span>
	<table class = 'sortable'>
	<tr>
	<th></th>
	<th>КЛИЕНТ</th>
	<th>ЗАКАЗОВ</th>
	<th>НА СУММУ</th>
	<th>ТЕЛЕФОН</th>
	<th>ПОЧТА</th>
	<th>ГОРОД</th>
	<th>ПРИМЕЧАНИЕ</th>
	</tr>
	";
	foreach ($client_count as $key => $value) {
		$sql5 = "SELECT * 
				FROM  `shop_orders` 
				INNER JOIN  `users` ON  `shop_orders`.`user_id` =  `users`.`user_id` 
				WHERE  `shop_orders`.`user_id` ={$key}";
			$res5 = mysql_query($sql5);
			while($row5 = mysql_fetch_assoc($res5)){
				$offer_id2 = $row5["id"];
				$name = $row5["name"];
				$email = $row5["email"];
				$tel = $row5["tel"];
				$city= $row5["city"];
				$summm = $client_sum["{$key}"];
				$orders_head = "
				<tr class = 'c2_group_line'>
					<td></td>
					<td>{$name}</td>
					<td>{$value}</td>
					<td>{$summm }</td>
					<td>{$tel}</td>
					<td>{$email}</td>
					<td>{$city}</td>
					<td></td>
				</tr>";
				$orders_head .=$order["{$offer_id2}"];
				
				
			

		}
		$center.=$orders_head;	
	}
	
	
}