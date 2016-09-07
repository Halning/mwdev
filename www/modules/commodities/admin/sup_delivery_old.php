<?
if ($_SESSION['status']=="admin"){
	$center = "
	
	<table class = 'sortable'>
	<tr>
	<th></th>
	<th>Заказ</th>
	<th>Бренд</th>
	<th>Единиц</th>
	<th>Цена доставки</th>
	<th>Вес</th>
	<th>ТТН</th>
	<th>Примечание</th>
	<th>От куда</th>
	<th>Куда</th>
	<th>Контакты</th>
	<th>Статус</th>
	</tr>
	";
	$sql0 ="SELECT * FROM `sup_group` WHERE `status` = 6 ORDER BY `group_id` DESC";
	$res0 = mysql_query($sql0);
	while($row0 = mysql_fetch_assoc($res0)){
		$group_id = $row0["group_id"];
		$status = $row0["status"];
		$sup_id = $row0["sup_id"];
		$sum_opt = 0;
		$total_count = 0;
		$lines = "";
		$total_com_price = 0;

		$selected_group4 = "";
		$selected_group5 ="";
		$selected_group6 ="";
		if($status == 4) {
			$selected_group4 = "selected";
		} elseif($status == 5) {
			$selected_group5 = "selected";
		} elseif($status == 6){
			$selected_group6 = "selected";
		}

		
		
		$sql3 = "SELECT * FROM `suppliers` WHERE `sup_id` = {$sup_id}";
		$res3 = mysql_query($sql3);
		if($row3 = mysql_fetch_assoc($res3)){
			$sup_name = $row3["sup_name"];
		}
		$sql2 = "SELECT * FROM `shop_orders_coms`
		LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
		WHERE `group_id` = {$group_id}";
		$res2 = mysql_query($sql2);
		while($row2 = mysql_fetch_assoc($res2)){
				$com_id = $row2["com_id"];
				$size = $row2["com"];
				$count = $row2["count"];
				$cur_id = $row2["cur"];
				$cod = $row2["cod"];
				$price_opt = $row2["commodity_price2"];
				$from_url = $row2["from_url"];
				$com_name = $row2["com_name"];
				$color = get_color_to_order($com_id);
				$comment = $row2["man_comment"];
				$com_status = $row2["status"];
				$total_count +=$count;
				$com_price = $row2["price"];
				$com_price_one = $com_price/$count;
				$lines.="
				<tr>
				<td>{$cod}</td>
				<td>{$color}</td>
				<td>{$size}</td>
				<td>{$count}</td>
				<td>Грн</td>
				<td>{$com_price_one}</td>
				<td>{$com_price}</td>
				<td><a href ='{$from_url}'>Источник</a></td>
				<td>{$com_status}</td>
				<td>{$comment}</td>
				</tr>";
		}
		$group_head["{$group_id}"] = "<tr class ='c2_group_line' id='{$row0['group_id']}' rel='sup_group' rel2='group_id'>
		<td></td>
		<td>№{$group_id}</td>
		<td>{$sup_name}</td>
		<td>{$total_count}</td>
		<td id ='del_price' class = 'cl_edit'>{$row0['del_price']}</td>
		<td id ='weight' class = 'cl_edit'>{$row0['weight']}</td>
		<td id ='ttn' class = 'cl_edit'>{$row0['ttn']}</td>
		<td id ='del_comment' class = 'cl_edit'>{$row0['del_comment']}</td>
		<td id ='from' class = 'cl_edit'>{$row0['from']}</td>
		<td id ='to' class = 'cl_edit'>{$row0['to']}</td>
		<td id ='contact' class = 'cl_edit'>{$row0['contact']}</td>
		<td><select size='1' name='status' id = 'select_group_status' rel='{$group_id}'>
					<option value='4' rel='{$group_id};4' {$selected_group4}>Оплачен поставщику</option>
    				<option value='5' rel='{$group_id};5' {$selected_group5}>Доставка на склад MW</option>
    				<option value='6' rel='{$group_id};6' {$selected_group6}>Доставлен на склад</option>
    				
        		</select></td>
		</tr>
		<tr><td colspan ='12'><span class = 'opener3'>+ </span>
							<table class ='c2_group_hide'>
								<tr class = 'c2_th_group'>
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
						</tr>";
		$group_head["{$group_id}"].=$lines;
		$group_head["{$group_id}"].="</table></td></tr>";
	}
	foreach ($group_head as $key => $value) {
		$center.=$group_head["{$key}"];
	}
	$center.="</table>";
}


