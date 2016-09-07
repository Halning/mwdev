<?
if ($_SESSION['status']=="admin")
{
	if(isset($_GET["id"]))
	{
		$id2=$_GET["id"];
		$rrrr=explode(",",$id2);
		foreach($rrrr as $key=>$value){
			$today = date("Y-m-d H:i:s");
			$group_id = $value;
			$sql = "SELECT * FROM `sup_group` WHERE `group_id`={$group_id}";
			$res = mysql_query($sql);
			if($row = mysql_fetch_assoc($res)){
				$sup_id = $row["sup_id"];
			}
			$sql3 = "SELECT * FROM `suppliers` WHERE `sup_id` = {$sup_id}";
			$res3 = mysql_query($sql3);
			if($row3 = mysql_fetch_assoc($res3)){
				$sup_name = $row3["sup_name"];
				$sup_email = $row3["sup_email"];
				$sup_margin = $row3["sup_margin"];
			}

			$sql2 = "SELECT * FROM `shop_orders_coms`
			LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
			WHERE `group_id` = {$group_id} AND `count`>0 AND `com_status` > 3";
			$res2 = mysql_query($sql2);
			$sum = 0;
			$total_count = 0;
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
				$true_price = $price_opt*(100-$sup_margin)/100;
				$sum = $true_price*$count;

				$summ += $true_price;
				$total_count += $count;

				$lines.="
				<tr>
				<td>{$com_name}</td>
				<td>{$cod}</td>
				<td>{$color}</td>
				<td>{$size}</td>
				<td>{$count}</td>
				<td>{$true_price}</td>
				<td>{$sum}</td>
				<td>Грн</td>
				<td><a href ='{$from_url}'>Источник</a></td>

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
		<p>Добрый день, заказ от сайта makewear.com.ua, предоплата поступит в течении дня</p>
		<p>Адрес доставки: г. Киев, отделение Новой Почты №57, Чужаков Андрей 096-766-62-80</p>
		
		<h2>Дата {$today}</h2>
		<table class ='sup_table'>
		<tr>
		<th>Название</th>
		<th>Артикул</th>
		<th>Цвет</th>
		<th>Размер</th>
		<th>Кол-во</th>
		<th>Цена</th>
		<th>Сумма</th>
		<th>Валюта</th>
		<th>Ссылка</th>
		</tr>
		";
		$mail.=$lines;
		$mail.="</table>
		<h2>Общее количество товаров: {$total_count}</h2>
		<h2>Общая сумма: {$summ}</h2>";
		$center = $mail;
		

		send_mime_mail_html($glb["dom_mail"],$glb["sys_mail"],$sup_name, "{$sup_email}","utf-8","utf-8","Заказ от сайта {$glb["dom_mail"]}",$mail);

	}
}