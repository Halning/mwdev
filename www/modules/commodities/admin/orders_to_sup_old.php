<?
if ($_SESSION['status']=="admin"){

	$sql = "SELECT *
	FROM  `sup_group` 
	INNER JOIN  `shop_orders_coms` ON  `sup_group`.`group_id` =  `shop_orders_coms`.`group_id` 
	WHERE  `status` >3";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)){
		$id = $row["id"];
		$group_id = $row["group_id"];
		$com_id = $row["com_id"];
		$com_sum2 = $row["price"];
		$size = $row["com"];
		$count = $row["count"];
		$cur_id = $row["cur"];
		$comment = $row["man_comment"];
		$com_status = $row["com_status"];
		$color = get_color_to_order($com_id);
		$sup_id[$group_id] = $row["sup_id"];

		$group_line[$group_id] ="";
		$com_selected1 = "";
		$com_selected2 = "";
		$com_selected3 = "";
		$com_selected4 = "";
		$com_selected5 = "";
		$com_selected0 = "";
		$com_selected6 = "";

		$linecolor = "";
		$status = $row["com_status"];
		if($status == 1){
			$com_selected1 = "selected";
			$linecolor = "greenline";
		} elseif($status == 2){
			$com_selected2 = "selected";
			$linecolor = "redline";
		} elseif($status == 3){
			$com_selected3 = "selected";
		} elseif($status == 0){
			$com_selected0 ="selected";
		} elseif($status == 4){
			$com_selected4 ="selected";
		} elseif($status == 5){
			$com_selected5 ="selected";
		} elseif($status == 6){
			$com_selected6 ="selected";
		} 

		$status_group = $row["status"];
			if($status_group == 1){
				$selected_group1[$group_id] = "selected";
			} elseif($status_group == 2) {
				$selected_group2[$group_id] = "selected";
			} elseif($status_group == 3) {
				$selected_group3[$group_id] = "selected";
			} elseif($status_group == 4) {
				$selected_group4[$group_id] = "selected";
			} elseif($status_group == 5) {
				$selected_group5[$group_id] = "selected";
			} elseif($status_group == 6){
				$selected_group6[$group_id] = "selected";
			}
		
		$sql2 = "SELECT  `commodity_price` ,  `commodity_price2` ,  `com_name` ,  `from_url` ,  `cod` 
		FROM  `shop_commodity`  WHERE `commodity_ID` = {$com_id}";
		$res2 = mysql_query($sql2);
		if($row2 = mysql_fetch_assoc($res2)){
			$cod = $row2["cod"];
			$from_url = $row2["from_url"];
			$com_name = $row2["com_name"];
			$com_price_opt = $row2["commodity_price2"];
			$com_sum = $com_price_opt*$count;
		}
		
		$lines[$group_id].="
		<tr>
		<td>{$cod}</td>
		<td>{$color}</td>
		<td>{$size}</td>
		<td>{$count}</td>
		<td>{$cur_id}</td>
		<td>{$com_price_opt}</td>
		<td>{$com_sum}</td>
		<td><a href ='{$from_url}'>Источник</a></td>
		<td>
			<select size='1' name='status' id = 'select_status_com' rel = '{$row['id']}' disabled>
					<option value= '0' {selected0}></option>
					<option value='1' rel='{$row2['id']};1' {$com_selected1}>Есть в наличии</option>
    				<option value='2' rel='{$row2['id']};2' {$com_selected2}>Нет в наличии</option>
    				<option value='3' rel='{$row2['id']};3'{$com_selected3}>Замена</option>
    				<option value ='4' {$com_selected4}>оплачен</option>
    				<option value ='5' {$com_selected5}>ожидается</option>
    				<option value ='6' {$com_selected6}>на складе MW</option>
    			</select>
		</select></td></td>
		<td>{$comment}</td>
		</tr>";
		$sql3 = "SELECT * FROM `suppliers` WHERE `sup_id` = $sup_id[$group_id]";
		$res3 = mysql_query($sql3);
		if($row3 = mysql_fetch_assoc($res3)){
			$sup_name[$group_id] = $row3["sup_name"];
			$sup_margin = $row3["sup_margin"];
		}
		$total_price_to_pay[$group_id] += $com_sum*(100-$sup_margin)/100;
		$total_price[$group_id] +=$com_sum2;
		$total_count[$group_id] ++;

	}
	$center = "
	<table class = 'sortable'>
	<tr>
	<th></th>
	<th>НОМЕР ЗАКАЗА</th>
	<th>БРЕНД</th>
	<th>ЕДИНИЦ</th>
	<th>СУММА</th>
	<th>К ОПЛАТЕ</th>
	<th>ВЫРУЧКА</th>
	<th>ПРИМЕЧАНИЕ</th>
	<th>ДЕЙСТВИЯ</th>
	<th>СТАТУС</th>
	</tr>
	";
	foreach ($group_line as $key => $value) {
		$profit = $total_price[$key] - $total_price_to_pay[$key];
		$group_line[$key].="<tr class = 'c2_group_line' id='{$row0['group_id']}' rel='sup_group' rel2='group_id'>
			<td></td>
			<td>№{$key}</td>
			<td>{$sup_name[$key]}</td>
			<td>{$total_count[$key]}</td>
			<td>{$total_price[$key]}</td>
			<td>{$total_price_to_pay[$key]}</td>
			<td>{$profit}</td>
			<td id ='comment' class = 'cl_edit'>{$row0['comment']}</td>
			<td><a href = '?admin=mail_to_sup2&id={$key}' target = '_blank'>Почта</a></td>
			<td>
			
			<select size='1' name='status' id = 'select_group_status' rel='{$key}'>
					<option value='3' {$selected_group3[$key]}>Готов к оплате</option>
    				<option value='4' {$selected_group4[$key]}>Оплачен поставщику</option>
    				<option value='5' {$selected_group5[$key]}>Доставка на склад MW</option>
    				<option value='6' {$selected_group6[$key]}>Доставлен на склад</option>
    				
        		</select></td>
			</tr>
			";
			$group_line[$key].="
			<td colspan ='12'><span class = 'opener3'>+ </span>
			<table class ='c2_group_hide sortable'>
			<tr>
			<th>Артикул</th>
			<th>Цвет</th>
			<th>Размер</th>
			<th>Кол-во</th>
			<th>Валюта</th>
			<th>Цена</th>
			<th>Сумма</th>
			<th>Ссылка</th>
			<th>Статус</th>
			<th>Комментарий</th>
			</tr>

			";
			$group_line[$key].=$lines["{$key}"];
			$group_line[$key] .="</table></td></tr>";
			$center.=$group_line[$key];
	}
	$center.="</table>";
	



}

