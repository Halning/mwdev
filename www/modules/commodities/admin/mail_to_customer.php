<?
if ($_SESSION['status']=="admin")
{
	if(isset($_GET["id"]))
	{
		if(isset($_POST["submit"]))
			{
			$message = $_POST["text"];
			$id2=$_GET["id"];
			$rrrr=explode(",",$id2);
			foreach($rrrr as $key=>$value){
				$today = date("Y-m-d H:i:s");
				$offer_id = $value;
				$sql3 = "SELECT * FROM `shop_orders` WHERE `id` = {$offer_id}";
				$res3 = mysql_query($sql3);
				if($row3 = mysql_fetch_assoc($res3)){
					$email = $row3["email"];
					$name = $row3["name"];
					$of_date = $row3["date"];
					$delivery = $row3["delivery_price"] ? $row3["delivery_price"] : 0;
				}
				$mail="";
				$sql2 = "SELECT * FROM `shop_orders_coms`
				LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
				WHERE `offer_id` = {$offer_id} AND `count`>0";
				$res2 = mysql_query($sql2);
				$sum = 0;
				$total_count = 0;
				while($row2 = mysql_fetch_assoc($res2)){
					$com_id = $row2["com_id"];
					$size = $row2["com"];
					$count = $row2["count"];
					$cur_id = $row3["cur_id"];
					$cod = $row2["cod"];
					$from_url = $row2["alias"]!=""?"/pr{$row11["com_id"]}_{$row11["alias"]}/":"/pr{$row11["com_id"]}/";
					$url = "<a href = 'www.makewear.com.ua.{$from_url}'>Ссылка</a>";
					$com_name = $row2["com_name"];
					if($row2["com_color"] != ""){
						$color = $row2["com_color"];
					}
					else
					{
						$color = get_color_to_order($com_id);
					}
					
					$sum = $row2["price"];
					$price = $sum/$count;
					$summ += $sum;
					$background ="";
					$total_count += $count;
					$sql4="SELECT * FROM `shop_commodities-categories`
					INNER JOIN `shop_categories` ON `shop_categories`.`categories_of_commodities_ID`=`shop_commodities-categories`.`categoryID`
					WHERE `commodityID`='{$row2['com_id']}' AND `categories_of_commodities_parrent`='10' ";
					$res4=mysql_query($sql4);

					while($row4=mysql_fetch_assoc($res4))
					{
						$basket_com_cat=$row4["cat_name"];
					}

					$com_status = $row2["com_status"];
					if ($com_status == 1){
						$status = "Есть в наличии";
					} elseif ($com_status == 2){
						$status = "Нет в наличии";
						$total_count -=$count;
						$summ -=$sum;
						$background = "#FF0000;";
					} elseif ($com_status == 3){
						$status = "Замена";
					} elseif ($com_status == 4){
						$status = "Оплачен";
					} elseif($com_status == 0){
						$status = "Уточняется";
					}
					if($cur_id == 1){
						$curt = "Грн";
					} elseif ($cur_id == 2){
						$curt = "Дол";
					} elseif ($cur_id ==3){
						$curt = "Руб";
					}
					$url=$row2["alias"]!=""?"/pr{$row2["com_id"]}_{$row2["alias"]}/":"/pr{$row2["com_id"]}/";

					$lines.="
					<tr>
					<td>{$basket_com_cat}</td>
					<td>{$cod}</td>
					<td>{$color}</td>
					<td>{$size}</td>
					<td>{$count}</td>
					<td>{$price}</td>
					<td>{$sum}</td>
					<td>{$curt}</td>
					<td>{$url}</td>
					<td style = 'background:{$background}'>{$status}</td>
					</tr>";

				}
			}

			
			$mail ="
			<style type ='text/css'>
			table.sup_table{
				border: 1px solid Black;
				width: 100%;
				border-collapse: collapse;
			}
			.sup_table th{
				border:1px solid black;
			}
			.sup_table td{
				border:1px solid black;
			}
			.sup_table th{
				background-color: #54a5b2;
				font-weight: bold;
			}
			td.numbertd{
				background-color: #54a5b2;
			}
			</style>
			<p>Здравствуйте, {$name}. <br>
			Пожалуйста, подтвердите Ваш заказ</p>
			<p>{$message}</p>
			<h2>Заказ № {$offer_id}</h2>
			<h2>Дата {$today}</h2>
			<table class ='sup_table' style = 'border: 1px solid Black;
				width: 100%;
				border-collapse: collapse;'>
			<tr>
				
				<th>Бренд</th>
				<th>Артикул</th>
				<th>Цвет</th>
				<th>Размер</th>
				<th>Кол-во</th>
				<th>Цена</th>
				<th>Сумма</th>
				<th>Валюта</th>
				<th>Ссылка</th>
				<th>Статус</th>
			</tr>
			";

			//$delivery = 51*$total_count;
			//$commision = round($summ*0.01, 0);
                        $commision = $row3['commission'] ? $row3['commission'] : 0;
			$total = $summ + $delivery + $commision;
			$mail.=$lines; 
			$mail.="</table>
			<h2>Общее количество товаров: {$total_count}</h2>
			<h2>Общая сумма заказа: {$summ} {$curt}</h2>
			<h2>Стоимость доставки: {$delivery} {$curt}</h2>
			<h2>Комиссия: {$commision} {$curt}</h2>
			<h2>Итого: {$total} {$curt}</h2>";

			$center = $mail;

			send_mime_mail_html($glb["dom_mail"],$glb["sys_mail"],$sup_name,"{$email}","utf-8","utf-8","Подтверждение заказа {$glb["dom_mail"]}",$mail);
			send_mime_mail_html($glb["dom_mail"],$glb["sys_mail"],$sup_name,"sales@makewear.com.ua","utf-8","utf-8","Подтверждение заказа {$glb["dom_mail"]}",$mail);
		}else
		{
			$it_name="заказ";
			require_once("templates/{$theme_name}/admin_send_mail.php");
			$id2=$_GET["id"];
			$rrrr=explode(",",$id2);
			foreach($rrrr as $key=>$value){
				$today = date("Y-m-d H:i:s");
				$offer_id = $value;
				$sql3 = "SELECT * FROM `shop_orders` WHERE `id` = {$offer_id}";
				$res3 = mysql_query($sql3);
				if($row3 = mysql_fetch_assoc($res3)){
					$email = $row3["email"];
					echo($email);

				}
				$sql2 = "SELECT * FROM `shop_orders_coms`
				LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
				WHERE `offer_id` = {$offer_id} AND `count`>0";
				$res2 = mysql_query($sql2);
				$sum = 0;
				$total_count = 0;
				while($row2 = mysql_fetch_assoc($res2)){
					$number++;
					$com_id = $row2["com_id"];
					$size = $row2["com"];
					$count = $row2["count"];
					$cur_id = $row3["cur_id"];
					$cod = $row2["cod"];
					$from_url = $row2["alias"]!=""?"/pr{$row11["com_id"]}_{$row11["alias"]}/":"/pr{$row11["com_id"]}/";
					$com_name = $row2["com_name"];
					if($row2["com_color"] != ""){
						$color = $row2["com_color"];
					}
					else
					{
						$color = get_color_to_order($com_id);
					}
					
					$sum = $row2["price"];
					$price = $sum/$count;
					$summ += $sum;
					$background ="";
					$total_count += $count;
					$sql4="SELECT * FROM `shop_commodities-categories`
					INNER JOIN `shop_categories` ON `shop_categories`.`categories_of_commodities_ID`=`shop_commodities-categories`.`categoryID`
					WHERE `commodityID`='{$row2['com_id']}' AND `categories_of_commodities_parrent`='10' ";
					$res4=mysql_query($sql4);

					while($row4=mysql_fetch_assoc($res4))
					{
						$basket_com_cat=$row4["cat_name"];
					}

					$com_status = $row2["com_status"];
					if ($com_status == 1){
						$status = "Есть в наличии";
					} elseif ($com_status == 2){
						$status = "Нет в наличии";
						$total_count -=$count;
						$summ -=$sum;
						$background = "#FF0000;";
					} elseif ($com_status == 3){
						$status = "Замена";
					} elseif ($com_status == 4){
						$status = "Оплачен";
					} elseif($com_status == 0){
						$status = "Уточняется";
					}
					if($cur_id == 1){
						$curt = "Грн";
					} elseif ($cur_id == 2){
						$curt = "Дол";
					} elseif ($cur_id ==3){
						$curt = "Руб";
					}
					$url=$row2["alias"]!=""?"/pr{$row2["com_id"]}_{$row2["alias"]}/":"/pr{$row2["com_id"]}/";

					$lines.="
					<tr>
					<td class = 'numbertd'>{$number}</td>
					<td>{$basket_com_cat}</td>
					<td>{$cod}</td>
					<td>{$color}</td>
					<td>{$size}</td>
					<td>{$count}</td>
					<td>{$price}</td>
					<td>{$sum}</td>
					<td>{$curt}</td>
					<td>{$url}</td>
					<td style = 'background:{$background}'>{$status}</td>
					</tr>";


				}

				//$delivery = $total_count*20/0.45;
			$mail1 ="
			<style type ='text/css'>
			table.sup_table{
				border: 1px solid Black;
				width: 100%;
				border-collapse: collapse;
			}
			.sup_table th{
				border:1px solid black;
			}
			.sup_table td{
				border:1px solid black;
			}
			.sup_table th{
				background-color: #54a5b2;
				font-weight: bold;
			}
			td.numbertd{
				background-color: #54a5b2;
			}
			</style>
			<h2>Заказ № {$offer_id}</h2>
			<h2>Дата {$today}</h2>
			<table class ='sup_table'>
			<tr>
				<th>№</th>
				<th>Бренд</th>
				<th>Артикул</th>
				<th>Цвет</th>
				<th>Размер</th>
				<th>Кол-во</th>
				<th>Цена</th>
				<th>Сумма</th>
				<th>Валюта</th>
				<th>Ссылка</th>
				<th>Статус</th>
			</tr>
			";

			//$delivery = *$total_count;
			//$commision = round($summ*0.01, 0);
                        $commision = $row3['commission'] ? $row3['commission']: 0 ;
                        $delivery = $row3["delivery_price"] ? $row3["delivery_price"] : 0;			
                        $total = $summ + $delivery + $commision;
			//echo($delivery);
			$mail1.=$lines;
			$mail1.="</table>
			<h2>Общее количество товаров: {$total_count}</h2>
			<h2>Общая сумма заказа: {$summ} {$curt}</h2>
			<h2>Стоимость доставки: {$delivery} {$curt}</h2>
			<h2>Комиссия: {$commision} {$curt}</h2>
			<h2>Итого: {$total} {$curt}</h2>";
			

			$center.= $mail1;
		}
	}
}
}
			

			
		
	


