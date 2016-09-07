<?php

include 'brenda_name.php';
//TODO исключить запросы в цикле, использовать оператор IN() 
if ($_SESSION['status'] == "admin") {

    $sql = "SELECT * 
			FROM  `shop_orders`
			WHERE `status` = 10 
			ORDER BY `id`  DESC ";
    $res = mysql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {
        $user_id = $row["user_id"];
        $offer_id = $row["id"];
        $offer_cur_name = show_cur_for_admin($row["cur_id"]);
        $status_selected1 = "";
        $status_selected2 = "";
        $status_selected3 = "";
        $status_selected4 = "";
        $status_selected5 = "";
        $status_selected6 = "";
        $status_selected7 = "";


        $address = $row["address"];
        $cod = $row["cod"];
        $date = $row["date"];
        $client_count["{$user_id}"] +=1;
        $lines = '';
        $status = $row["status"];
        if ($status == 1) {
            $status_selected1 = "selected";
        } elseif ($status == 2) {
            $status_selected2 = "selected";
        } elseif ($status == 3) {
            $status_selected3 = "selected";
        } elseif ($status == 4) {
            $status_selected3 = "selected";
        } elseif ($status == 5) {
            $status_selected5 = "selected";
        } elseif ($status == 6) {
            $status_selected6 = "selected";
        } elseif ($status == 7) {
            $status_selected7 = "selected";
        } elseif ($status == 10) {
            $status_selected10 = "selected";
        }

        $current_date = strtotime(date("Y-m-d H:i:s"));
        $the_date_of_offer = strtotime($row["date"]);
        if (($current_date - $the_date_of_offer) > 86400) {
            $does_it_open = "none";
        }



        $sql2 = "
			SELECT * FROM `shop_orders_coms`
			LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
			WHERE `offer_id`='{$row["id"]}' AND `count`>0;";
        $res2 = mysql_query($sql2);
        while ($row2 = mysql_fetch_assoc($res2)) {
            $com_id = $row2["com_id"];
            $size = $row2["com"];
            $count = $row2["count"];
            $cur_id_com = $row2["cur_id"];
            $cod2 = $row2["cod"];
            $price_opt = $row2["commodity_price2"];
            $from_url = $row2["from_url"];
            $com_name = $row2["com_name"];
            /* if($row2["offer_id"] == 222){
              echo($row2["cur_id"]);
              } */

            //$cur_name_com = show_cur_for_admin($row2["cur_id"]);
            if ($row2["com_color"] != "") {
                $color = $row2["com_color"];
            } else {
                $color = get_color_to_order($com_id);
            }

            $comment = $row2["man_comment"];
            $com_status = $row2["com_status"];
            $price = $row2["price"] / $count;
            $com_sum = $row2["price"];
            $order_com_id = $row2["id"];
            $basket_com_cat = $row2["com_brand_name"];
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
            if ($status_com == 1) {
                $com_selected1 = "selected";
                $linecolor = "greenline";
            } elseif ($status_com == 2) {
                $com_selected2 = "selected";
                $linecolor = "redline";
                $client_sum["{$user_id}"]-=$com_sum;
            } elseif ($status_com == 3) {
                $com_selected3 = "selected";
            } elseif ($status_com == 0) {
                $com_selected0 = "selected";
            } elseif ($status_com == 4) {
                $com_selected4 = "selected";
            } elseif ($status_com == 5) {
                $com_selected5 = "selected";
            } elseif ($status_com == 6) {
                $com_selected6 = "selected";
            }


            $basket_com_cat = brenda_name($cod2);
            $client_sum["{$user_id}"] +=$com_sum;

            $lines["{$offer_id}"].="
				<tr id='{$row2["id"]}' rel='shop_orders_coms' rel2='id' class ='{$linecolor}'>
				<td>{$basket_com_cat}</td>
				<td>{$cod2}</td>
				<td id = 'com_color' class='cl_edit'>{$color}</td>
				<td id = 'com' class='cl_edit'>{$size}</td>
				<td>{$count}</td>
				<td>{$offer_cur_name}</td>
				<td>{$price}</td>
				<td>{$com_sum}</td>
				<td><a href ='{$from_url}'>источник</a></td>
				<td><select size='1' name='status' id = 'select_status_com' rel = '{$order_com_id}'>
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
				<table class ='c2_group_hide' style = 'display:{$does_it_open};'>
				<tr class = 'c3_th_group'>
				<td></td>
				<td>{$offer_id}</td>
				<td>{$date}</td>
				<td>{$cod}</td>
				<td><a href = '?admin=mail_to_customer&id={$offer_id}'><img src = '/templates/admin/img/pochta.png' class = 'mail_to_customer_img'></a></td>
				<td><select size='1' name='status' id = 'select_order_status'  rel='{$offer_id}'>
					<option value='1' rel='{$offer_id};1' {$status_selected1}>Новый заказ</option>
    				<option value='2' rel='{$offer_id};2' {$status_selected2}>Обрабатывается</option>
    				<option value='3' rel='{$offer_id};3' {$status_selected3}>Подтвержден</option>
    				<option value='10' rel='{$offer_id};10' {$status_selected10}>Отменен</option>
    				<!--<option {$status_selected4}>оплачен </option>
    				<option {$status_selected5}>собран</option>
    				<option {$status_selected6}>отправлен клиенту</option>
    				<option {$status_selected7}>закрыт</option>-->
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

    if ($client_count) {
        foreach ($client_count as $key => $value) {
            $sql5 = "SELECT * 
				FROM  `shop_orders` AS a
				INNER JOIN  `users` AS b ON  a.`user_id` =  b.`user_id` 
				WHERE  a.`user_id` ={$key} AND a.`status` = 10 ";
            $res5 = mysql_query($sql5);
            while ($row5 = mysql_fetch_assoc($res5)) {
                $offer_id2 = $row5["id"];
                $name = $row5["name"];
                $email = $row5["email"];
                $tel = $row5["tel"];
                $city = $row5["city"];
                $summm = $client_sum["{$key}"];

                $orders_head = "
				<tr class = 'c2_group_line' id='{$row5['user_id']}' rel='users' rel2='user_id'>
					<td></td>
					<td>{$name}</td>
					<td>{$value}</td>
					<td>{$summm }</td>
					<td>{$tel}</td>
					<td>{$email}</td>
					<td>{$city}</td>
					<td id ='about_customer' class='cl_edit'>{$row5["about_customer"]}</td>
				</tr>";
                $orders_head .=$order["{$offer_id2}"];
            }
            $center.=$orders_head;
        }
    }
}
?>
