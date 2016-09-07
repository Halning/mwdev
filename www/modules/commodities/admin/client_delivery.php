<?php

//TODO Исключить запросы к бд в цикле, использовать IN()
if ($_SESSION['status'] == "admin") {
    $sql = "SELECT * FROM  `shop_orders` WHERE  `status` IN (4) ORDER BY `id` DESC";
    $res = mysql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {
        $offer_id = $row["id"];
        $cod = $row["cod"];
        $date = $row["date"];
        $name = $row["name"];
        $address = $row["address"];
        $email = $row["email"];
        $tel = $row["tel"];
        $city = $row["city"];
        $total_sum = 0;
        $total_count = 0;
        $lines = "";
        $status = $row['status'];
        $delivery_price = $row["delivery_price"];

        $current_date = strtotime(date("Y-m-d H:i:s"));
        $the_date_of_offer = strtotime($row["date"]);
        if (($current_date - $the_date_of_offer) > 86400) {
            $does_it_open = "none";
        }

        $status_selected1 = "";
        $status_selected2 = "";
        $status_selected3 = "";
        $status_selected4 = "";
        $status_selected5 = "";
        $status_selected6 = "";
        $status_selected7 = "";
        $status_selected8 = "";
        $status = $row["status"];
        if ($status == 1) {
            $status_selected1 = "selected";
        } elseif ($status == 2) {
            $status_selected2 = "selected";
        } elseif ($status == 3) {
            $status_selected3 = "selected";
        } elseif ($status == 4) {
            $status_selected4 = "selected";
        } elseif ($status == 5) {
            $status_selected5 = "selected";
        } elseif ($status == 6) {
            $status_selected6 = "selected";
        } elseif ($status == 7) {
            $status_selected7 = "selected";
        } elseif ($status == 8) {
            $status_selected8 = "selected";
        }



        $sql2 = "SELECT * 
		FROM  `shop_orders_coms` 
		INNER JOIN  `sup_group` ON  `shop_orders_coms`.`group_id` =  `sup_group`.`group_id` 
		WHERE `shop_orders_coms`.`offer_id` = {$offer_id} AND `sup_group`.`status` IN (5,6)";

        $res2 = mysql_query($sql2);
        while ($row2 = mysql_fetch_assoc($res2)) {
            $id = $row2["id"];
            $com_id = $row2["com_id"];
            $size = $row2["com"];
            $count = $row2["count"];
            $cur_id = $row2["cur"];
            $sql4 = "
			SELECT * FROM `shop_orders_coms`
			LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
			WHERE `id` = {$id} AND `count`>0  ";
            $res4 = mysql_query($sql4);
            if ($row4 = mysql_fetch_assoc($res4)) {
                $cod2 = $row4["cod"];
            }

            $price_opt = $row2["commodity_price2"];
            $from_url = $row2["from_url"];
            $com_name = $row2["com_name"];
            $color = get_color_to_order($com_id);
            $comment = $row2["man_comment"];
            $com_status = $row2["status"];
            $price = $row2["price"] / $count;
            $com_sum = $row2["price"];
            $total_sum +=$com_sum;
            $total_count +=$count;

            $group_status = $row2["status"];
            if ($group_status == 1) {
                $status1 = "Новый заказ";
            } elseif ($group_status == 2) {
                $status1 = "Обрабатывается";
            } elseif ($group_status == 3) {
                $status1 = "Подтвержден";
            } elseif ($group_status == 4) {
                $status1 = "Оплачен поставщику";
            } elseif ($group_status == 5) {
                $status1 = "Доставка на склад";
            } elseif ($group_status == 6) {
                $status1 = "Доставлен на склад MW";
            }
            $basket_com_cat = $row2["com_brand_name"];
            /* $sql3="SELECT * FROM `shop_commodities-categories`
              INNER JOIN `shop_categories` ON `shop_categories`.`categories_of_commodities_ID`=`shop_commodities-categories`.`categoryID`
              WHERE `commodityID`='{$row2['com_id']}' AND `categories_of_commodities_parrent`='10' ";
              $res3=mysql_query($sql3);

              if($row3=mysql_fetch_assoc($res3))
              {

              } */
            $lines.="
			<tr id='{$row2["id"]}' rel='shop_orders_coms' rel2='id'>
				<td>{$basket_com_cat}</td>
				<td>{$cod2}</td>
				<td>{$color}</td>
				<td>{$size}</td>
				<td>{$count}</td>
				<td>Грн</td>
				<td>{$price}</td>
				<td>{$com_sum}</td>
				<td><a href ='{$from_url}'>источник</a></td>
				<td>{$status1}</td>
				<td id = 'man_comment' class='cl_edit'>{$comment}</td>
			</tr>";
        }
        $order["{$offer_id}"] = "
				<tr class = 'c3_th_group' id='{$row['id']}' rel='shop_orders' rel2='id'>
				<td></td>
				<td>№ {$cod}</td>
				<td>{$date}</td>
				<td>{$total_count}</td>
				<td>{$total_sum}</td>
				<td>{$city}<br/>
				{$address}<br/>{$email}</br>{$tel}</td>
				<td>{$delivery_price}</td>
				<td>{$name}</td>
				<td><select size='1' name='status' id = 'select_order_status' rel='{$offer_id}'>
					<option value='4' rel='{$offer_id}' {$status_selected4}>Оплачен</option>
    				<option value='5' rel='{$offer_id}' {$status_selected5}>Собран</option>
    				<option value='6' rel='{$offer_id}' {$status_selected6}>Отправлен</option>
            		</select></td>
            	<td id ='ttn' class = 'cl_edit'>{$row['ttn']}</td>
            	<td></td>
				</tr>
				<tr><td colspan ='12'><span class = 'opener3'>+ </span>
							<table class ='c2_group_hide' style = 'display:{$does_it_open}'>
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
        $order["{$offer_id}"].=$lines;
        $order["{$offer_id}"] .="</table></td></tr>";
    }
    $center = "
	
	<table class = 'sortable'>
	<tr>
	<th></th>
	<th>№ Заказа</th>
	<th>Дата</th>
	<th>Единиц</th>
	<th>Сумма</th>
	<th>Реквизиты</th>
	<th>Бюджет доставки</th>
	<th>Клиент</th>
	<th>Статус</th>
	<th>ТТН</th>
	<th>Уведомить</th>
	</tr>
	";
    if ($order) {
        foreach ($order as $key => $value) {
            $center.= $value;
        }
    }
    $center.="</table>";
}