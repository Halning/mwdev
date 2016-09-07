<?php

ini_set("max_execution_time", "99999");
set_time_limit(99999);
error_reporting(E_ALL^E_NOTICE);
require_once("../settings/conf.php");
require_once("../settings/connect.php");
require_once("../modules/commodities/site/getcolor.php");

	if(isset($_GET['cli_id'])){
		$id=$_GET['cli_id'];
		$num=1;

		$monthes = array(1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
				    5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
				    9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря');
		$today=date("d")." ".$monthes[date("n")]." ".date("Y");

		$sql = "SELECT * FROM `shop_orders` WHERE `id`='{$id}'; ";
		$res = mysql_query($sql);
		while($row=mysql_fetch_assoc($res)){
			$offer_id = $row["id"];
			$date = $row["date"];
			$name = $row["name"];
			$discount = $row["discount"];
			$order_cod = $row["cod"];
			$art[$offer_id] = $row["cod"];
			$status = $row["status"];
			$email = $row['email'];
			$order_count = 0;
			$orders_head ="";
			//$comission = $row["commission"];
			$delivery_price = $row["delivery_price"];
			$tel=$row['tel'];
			$tel=str_replace(" ", "", $tel);
			$city=$row['city'];
			$address=$row['address'];
			$client="{$name}<br>{$tel}<br>{$email}<br>{$city}<br>{$address}";
		}

		$sql2 = "
		SELECT * FROM `shop_orders_coms`
		LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
		WHERE `offer_id`='{$id}' AND `count`>0;";
		$res2 = mysql_query($sql2);
		while($row2=mysql_fetch_assoc($res2)){
			$com_id = $row2["com_id"];
			$size = $row2["com"];
			if($size=="(undefined)"){
                           $size="";
                        } 
			$count = $row2["count"];
			$cur_id2 = $row2["cur_id"];
			
			$cur_q=mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`={$cur_id2}");
			$curr=mysql_fetch_assoc($cur_q);
			$cur_name=$curr['cur_name'];
			$cur_id =$curr['cur_show'];

			$cod2 = $row2["cod"];
			$price_opt = $row2["commodity_price2"];
			$from_url = $row2["from_url"];
			$com_name = $row2["com_name"];
			$color = get_color_to_order($com_id);
			$comment = $row2["man_comment"];
			
			$price = $row2["price"]*$count;
			$com_sum = $row2["price"];
			$total_count+=$count;
			// if($com_sum==0){
			// 	$com_sum=$row2["commodity_price2"]*$count;
			// 	$price=$row2["commodity_price2"];
			// 	if($com_sum==0){
			// 		$com_sum=$row2["commodity_price"]*$count;
   //                              	$price=$row2["commodity_price"];
			// 	}
			// }
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
					$offer_sum[$offer_id] -=$price;
					$total_count-=$count;
				} elseif($status_com == 3){
					$com_selected3 = "selected";
					$com2_selected3 = "selected";
					$total_count-=$count;
				} elseif($status_com == 0){
					$com_selected0 ="selected";
				} elseif($status_com == 4){
					$com_selected4 ="selected";
					$com2_selected4 ="selected";
					$payment+=$com_sum;
				} elseif($status_com == 5){
					$com_selected5 ="selected";
					$payment_wait+=$com_sum;
				} elseif($status_com == 6){
					$com_selected6 ="selected";
				} 
				// $payment_all+=$com_sum;			
				
			$sql3="SELECT * FROM `shop_commodities-categories`
			INNER JOIN `shop_categories` ON `shop_categories`.`categories_of_commodities_ID`=`shop_commodities-categories`.`categoryID`
			WHERE `commodityID`='{$row2['com_id']}' AND `categories_of_commodities_parrent`='10' ";
			$res3=mysql_query($sql3);

			if($row3=mysql_fetch_assoc($res3))
				{
					$basket_com_cat=$row3["cat_name"];
				}
			$offer_sum[$offer_id] +=$price;
			$order_count += $count;
			//--For mail---	
			if($status_com != 2){
				$tab_com.='<tr>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$num.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$basket_com_cat.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$cod2.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$color.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$size.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$count.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$com_sum.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$cur_id.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$price.'</td><tr>';
				$tab_com2[$offer_id][$num]=array('num'=>$num,'com_cat'=>$basket_com_cat,'cod'=>$cod2,'color'=>$color,'size'=>$size,'count'=>$count,'price'=>$com_sum,'cur'=>$cur_id,'com_sum'=>$price);
				$num++;
			}
		}

		if($id>=441){
			$ski='';
			$gift="";
			$price=$offer_sum[$offer_id];
			if($discount==1 && $total_count>=5){
					// $ski='-150 грн';
					// $price-=150;
					if($row["cur_id"]==1){
						$price-=150;
						$ski='-150 грн';
					}
					if($row["cur_id"]==3){
						$price-=500;
						$ski='-500 руб';
					}
					$price3+=$row['delivery_price'];
				}elseif($discount==2 && $total_count>=5){ 
					$ski='-10%';
					$price-=$price/100*10;
					$price+=$delivery_price;
				}
				elseif($discount==3 && $total_count>=5){
					$delivery_price="Бесплатная";
				}
				if($offer_sum[$offer_id]>=1000){
					$gift="Платья";
				}
			$skidka=$ski;
			$summm=$price;

		}else{
			$comission=round($offer_sum[$offer_id]/100*3);
			$summm = $offer_sum[$offer_id];
			$summm +=$comission;
			$summm +=$delivery_price;
			$payment_all+=$summm;
			$commissia=$comission." {$cur_name}";
		}
		$offer_sum2= $offer_sum["{$offer_id}"]." {$cur_name}";
		$del=$delivery_price." {$cur_name}";
		$sum_pri=$summm." {$cur_name}";

		//---Резвизита---
		$rezvizita="";
		$rezphone="";
		$ress=mysql_query("SELECT * 
			FROM  `rekvizit` 
			WHERE  `re_status` =0");
		while ($roww=mysql_fetch_assoc($ress)) {
			$rezvizita.=$roww["in_name"].": ".$roww["in_write"]."<br/>";
			if(strpos($roww["in_name"], "фон")!==false){
				$rezphone=$roww["in_write"];
			}
		}
		$arrPhone=explode(";", $rezphone);

		for($i=0; $i<count($arrPhone); $i++){
			$rezphonee.=$arrPhone[$i]."<br/>";
		}

	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title></title>
	<style>
	body{
		width: 90%;
		margin-right: auto;
		margin-left: auto;
	}
	</style>
	<script type="text/javascript">
		function printer(){
			//alert('printer');
			window.print();
		}
	</script>
</head>
<body onload="printer()">
<div style="font-family: Arial,Helvetica,sans-serif; color: black;" >
					<div class=mw_logo >
						<img src="http://www.makewear.com.ua/email/images/mw_logo.jpg" style="width: 110px;margin-left: 20px;" />
						<span style="font-size: 43px; float: right; margin-right: 5px; margin-top: 35px; color: #54A5B2;">СЧЕТ</span>
					</div>
					<table style="width:100%">
						<tr><td>
						<div style="color: black; font-size: 11px; border: 1px solid;display: inline-block; padding: 5px; border-color: #8A8A9B;" >
							<?php echo $rezvizita; ?>
						</div>
						</td><td align="right">
							<table style="border-collapse: collapse; color: black;">
								<tr><td style="width:115px"><b>Дата</b></td><td style="border: 1px solid #8A8A9B; padding: 3px;" ><?php echo $today ?></td></tr>
								<tr><td><b>Номер счета</b></td><td style="border: 1px solid #8A8A9B; padding: 3px;" ><?php echo $order_cod; ?></td></tr>
								<tr><td><b>Основание:</b></td><td style="border: 1px solid #8A8A9B; padding: 3px;" >Заказ №<?php echo $order_cod; ?></td></tr>
							</table>
						</td></tr>
					</table>
					<br/>
					<table style="border-collapse: collapse; width: 100%">
						<tr style="border: 1px solid #8A8A9B;"><td style="background: #54A5B2; color: white; font-size: 14px; padding: 3px;" ><b>Плательщик:</b></td></tr>
						<tr style="border: 1px solid #8A8A9B;"><td class="pl_client2" style="font-size: 17px; padding: 3px; color: black;" ><?php echo $client; ?></td></tr>
					</table>
					<br/>
					<table class="tab_order2" style="border-collapse: collapse; width: 100%; color: black; font-size: 14px;" >
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">№</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Бренда</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Артикул</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Цвет</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Размер</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Кол-во</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Цена</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Валюта</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Сумма</th>
						<?php echo $tab_com; ?>
					</table>
					<div style="color:black; margin-left: 30px; padding-top: 10px; font-size: 14px;">Итого <span class="all_price2" style="float: right; padding-right: 20px;"><?php echo $offer_sum2; ?></span></div>
					<br/>
					<table style="width:100%">
						<tr><td>
							<div style="color: #000; font-size: 11px; border: 3px solid #8A8A9B; width: 266px; padding: 5px; background: #DDD;" >
								Все чеки подлежат уплате компании MakeWear. По<br/>
								всем вопросам, касающимся этого  счета-фактуры,<br/>
								обращайтесь по телефонам<br/>
								<?php echo $rezphonee; ?>
								или адресу connect@makewear.com.ua<br/>
								<br/>
								<center><b>Благодарим за сотрудничество!</b></center>
								<br/>
							</div>
						</td><td align="right">
							<table style="color:black; border-collapse: collapse; margin-right: 20px; font-size: 14px; font-weight: bold;" >
								<tr><td style="padding: 5px;" align="right">Доставка</td><td style="padding: 5px;" class="dost" align="center"><?php echo $del; ?></td></tr>
								<tr><td style="padding: 5px;" align="right">Скидка</td><td style="padding: 5px;" class="dost" align="center"><?php echo $skidka; ?></td></tr>
								<tr><td style="padding: 5px;" align="right">Подарок</td><td style="padding: 5px;" class="dost" align="center"><?php echo $gift; ?></td></tr>
								<!-- <tr><td style="padding: 5px;" align="right">Комиссия</td><td style="padding: 5px;" class="comm" align="center"><?php echo $commissia; ?></td></tr> -->
								<tr><td style="padding: 5px;" align="right">Итого к оплате</td><td class="sum" style="padding: 5px; background: #355177; color: white;" align="center"><?php echo $sum_pri; ?></td></tr>
							</table>
						</td></tr>
					</table>
				</div>

</body>
</html>
