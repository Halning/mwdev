<?
if ($_SESSION['status']=="admin"){

	$payment_all=0;
	$payment=0;
	$payment_wait=0;
	$num=1;
	$tab_com2[]=array();
	$orders_head ="";
	$sql = "SELECT * FROM `shop_orders` WHERE `status` in (3,4) ORDER BY `id` ASC";
	$res = mysql_query($sql);
	while($row=mysql_fetch_assoc($res)){
		$offer_id = $row["id"];
		$date = $row["date"];
		$name = $row["name"];
		$order_cod = $row["cod"];
		$art[$offer_id] = $row["cod"];
		$status = $row["status"];
		$email = $row['email'];
		$order_count = 0;
		$comission[$offer_id] = $row["commission"];
		$delivery_price[$offer_id] = $row["delivery_price"];
		$tel=$row['tel'];
		$tel=str_replace(" ", "", $tel);
		if(strpos($tel,'-')===false){
		//	$tel[8].="-";
		//	$tel=str_replace($tel[8], $tel[8]."-", $tel);
		}
		if(strpos($tel,'+')===false){
			//$tel='+'.$tel;
		}
		$city=$row['city'];
		$address=$row['address'];
		$client[$offer_id]="{$name}<br>{$tel}<br>{$email}<br>{$city}<br>{$address}";

		$status_selected1 = "";
		$status_selected2 = "";
		$status_selected3 = "";
		$status_selected4 = "";
		$status_selected5 = "";
		$status_selected6 = "";
		$status_selected7 = "";
		
		$com2_selected1 = "";
		$com2_selected2 = "";
		$com2_selected3 = "";
		$com2_selected4 = "";
		$com2_selected5 = "";
		$com2_selected0 = "";
		$com2_selected6 = "";
			
		$order_comment = $row["comment_for_order"];		
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
		} 

	/*	$status_com2 = $row["status"];
			if($status_com2 == 1){
				$com2_selected1 = "selected";
				$linecolor = "greenline";
			} elseif($status_com2 == 2){
				$com2_selected2 = "selected";
				$linecolor = "redline";
				$offer_sum["{$offer_id}"] -=$com_sum;
			} elseif($status_com2 == 3){
				$com2_selected3 = "selected";
			} elseif($status_com2 == 0){
				$com2_selected0 ="selected";
			} elseif($status_com2 == 4){
				$com2_selected4 ="selected";
			} elseif($status_com2 == 5){
				$com2_selected5 ="selected";
			} elseif($status_com2 == 6){
				$com2_selected6 ="selected";
			}	*/

		$sql2 = "
		SELECT * FROM `shop_orders_coms`
		LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
		WHERE `offer_id`='{$row["id"]}' AND `count`>0;";
		$res2 = mysql_query($sql2);
		while($row2=mysql_fetch_assoc($res2)){
			$com_id = $row2["com_id"];
			$size = $row2["com"];
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
			
			$price = $row2["price"]/$count;
			$com_sum = $row2["price"];
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
					//$payment+=$com_sum;
				} elseif($status_com == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
					$offer_sum["{$offer_id}"] -=$com_sum;
				} elseif($status_com == 3){
					$com_selected3 = "selected";
					$com2_selected3 = "selected";
					
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
			$offer_sum["{$offer_id}"] +=$com_sum;

			//echo $offer_sum["{$offer_id}"]."=".$comission[$offer_id]."=".$delivery_price[$offer_id]."<br/>";

			$lines["{$offer_id}"].="
			<tr id='{$row2["id"]}' rel='shop_orders_coms' rel2='id'>
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
							<option value='1'  {$com_selected1}>Есть в наличии</option>
    						<option value='2'  {$com_selected2}>Нет в наличии</option>
    						<option value='3'  {$com_selected3}>Замена</option>
    					</select></td>
    			<td id = 'man_comment' class='cl_edit'>{$row2["man_comment"]}</td>
				</tr>";
			//--For mail---	
			if($status_com==1){
				$tab_com[$offer_id].='<tr>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$num.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$basket_com_cat.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$cod2.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$color.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$size.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$count.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$price.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$cur_id.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$com_sum.'</td><tr>';
				$tab_com2[$offer_id][$num]=array('num'=>$num,'com_cat'=>$basket_com_cat,'cod'=>$cod2,'color'=>$color,'size'=>$size,'count'=>$count,'price'=>$price,'cur'=>$cur_id,'com_sum'=>$com_sum);
				$num++;
			}

		}
		
		$summm = $offer_sum["{$offer_id}"];
		$summm +=$comission[$offer_id];
		$summm +=$delivery_price[$offer_id];
		$payment_all += $summm;
		$order_count += $count;

		$offer_sum2["{$offer_id}"] = $offer_sum["{$offer_id}"]." {$cur_name}";
		$del[$offer_id]=$delivery_price." {$cur_name}";;
		$commissia[$offer_id]=$comission." {$cur_name}";;
		$sum_pri[$offer_id]=$summm." {$cur_name}";

		$orders_head["{$offer_id}"] .= "
		<tr class = 'c3_th_group' id = '{$offer_id}' rel = 'shop_orders' rel2 = 'id'>
		<td></td>
		<td>{$offer_id}</td>
		<td>{$date}</td>
		<td>{$order_cod}</td>
		<td>{$name}</td>
		<td>{$order_count}</td>
		<td>{$summm}</td>
		<td id = 'comment_for_order' class='cl_edit'>{$order_comment}</td>
		<td><select size='1' name='status' id = 'select_order_status' rel='{$offer_id}'>
					<option value='3' rel='{$offer_id}' {$com2_selected3}>Подтвержден</option>
    				<option value='4' rel='{$offer_id}' {$com2_selected4}>Оплачен клиентом</option>	
            	</select></td>
		<!--<td><a href = '?admin=mail_to_customer&id={$offer_id}'>Mail</a></td>-->
		<td><span style='color:white; cursor:pointer' class='maill' rel='{$offer_id}' rel2='{$email}'>Mail</span></td>
		<!--<td align='center' ><div class='xlss' rel={$offer_id} >XLS</div></td>-->
		<td align='center' >
			<a href='?admin=all_orders&amp;exportId={$offer_id}' class='xlss'><div id='xls'>XLS</div></a>		
		</td>
		
		
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
						</tr>";
		$orders_head["{$offer_id}"].=$lines["{$offer_id}"];
		$orders_head["{$offer_id}"].="</table></td></tr>";
	//	$center.=$orders_head["{$offer_id}"];
		

	}	
	//var_dump($lines);

	$center.="<style>
				#xls{
					color:white;
					
				}
				.pay{
					background: #355177;
					padding: 5px;
					color: white;				
				}
				#pay_body{
					    position: fixed;
					    width: 100%;
					    height: 100%;
					    margin-left: -180px;
					    margin-top: -73px;
					    background: rgba(0, 0, 0, 0.61);
					    z-index: 5;				
				}
				#pay_mail{
					background: white none repeat scroll 0% 0%;
					border: 1px solid;
					width: 850px;
					margin-right: auto;
					margin-left: auto;
					height: 100%;
					overflow: auto;
				}
				.pay_close{
					float: right;
					margin-right: 10px;
					margin-top: 5px;
					cursor:pointer;
				}
				.html_mail{
					border: 1px solid;
					margin: 8px;
					padding: 3px;

				}
				label{
					display:inline-block;
					width: 50px;
					margin-left: 8px;
				}
				.in_text{width:500px}
				</style>
				";
// Send Mail
	$tab="<table class='tab_order2'>
			<th>№</th>
			<th>Бренда</th>
			<th>Артикул</th>
			<th>Цвет</th>
			<th>Размер</th>
			<th>Кол-во</th>
			<th>Цена</th>
			<th>Валюта</th>
			<th>Сумма</th>
		</table>";

	$monthes = array(1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
				    5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
				    9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря');
	$today=date("d")." ".$monthes[date("n")];
		$send_mail='
				<div tyle="font-family: Arial,Helvetica,sans-serif; color: black;" >
					<div class=mw_logo >
						<img src="http://www.makewear.com.ua/email/images/mw_logo.jpg" style="width: 110px;margin-left: 20px;" />
						<span style="font-size: 25px; float: right; margin-right: 5px; margin-top: 35px; color: #54A5B2;">СЧЕТ</span>
					</div>
					<table style="width:100%"">
						<tr><td>
						<div style="color: black; font-size: 11px; border: 1px solid; width: 248px; padding: 5px; border-color: #8A8A9B;" >
							Киев<br/>
							Телефон: +38(099)098-00-82<br/>
									+38(098)615-39-19<br/>
							connect@makewear.com.ua<br/>
							Карта ПриватБанка 4149 6258 0147 0848<br/>
							(Береговский Кирилл Валериевич)
						</div>
						</td><td align="right">
							<table style="border-collapse: collapse; color: black;">
								<tr><td style="width:95px"><b>Дата</b></td><td style="border: 1px solid #8A8A9B; width: 120px; padding: 3px;" >'.$today.'</td></tr>
								<tr><td><b>Номер счета</b></td><td style="border: 1px solid #8A8A9B; width: 120px; padding: 3px;" ><span class="art"></span></td></tr>
								<tr><td><b>Основание:</b></td><td style="border: 1px solid #8A8A9B; width: 120px; padding: 3px;" >Заказ № <span class="art"></span></td></tr>
							</table>
						</td></tr>
					</table>
					<br/>
					<table style="border-collapse: collapse; width: 100%">
						<tr style="border: 1px solid #8A8A9B;"><td style="background: #54A5B2; color: white; font-size: 14px; padding: 3px;" ><b>Плательщик:</b></td></tr>
						<tr style="border: 1px solid #8A8A9B;"><td class="pl_client2" style="font-size: 17px; padding: 3px; color: black;" ></td></tr>
					</table>
					<br/>
					<table class="tab_order2" style="border-collapse: collapse; width: 100%; color: black; font-size: 14px;" >
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">№</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Бренд</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Артикул</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Цвет</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Размер</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Кол-во</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Цена</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Валюта</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Сумма</th>
						<tbody class="ttbody"></tbody>
					</table>
					<div style="color:black; margin-left: 30px; padding-top: 10px; font-size: 14px;">Итого <span class="all_price2" style="float: right; padding-right: 20px;"></span></div>
					<br/>
					<table style="width:100%"">
						<tr><td>
							<div style="color: #000; font-size: 11px; border: 3px solid #8A8A9B; width: 266px; padding: 5px; background: #DDD;" >
								Все чеки подлежат уплате компании MakeWear. По<br/>
								всем вопросам, касающимся этого  счета-фактуры,<br/>
								обращайтесь по телефонам<br/>
								+38(099)098-00-82<br/>
								+38(098)615-39-19<br/>
								или адресу connect@makewear.com.ua<br/>
								<br/>
								<center><b>Благодарим за сотрудничество!</b></center>
								<br/>
							</div>
						</td><td align="right">
							<table style="color:black; border-collapse: collapse; margin-right: 20px; font-size: 14px; font-weight: bold;" >
								<tr><td style="padding: 5px;" align="right">Доставка</td><td style="padding: 5px;" class="dost" align="center"></td></tr>
								<tr><td style="padding: 5px;" align="right">Комиссия</td><td style="padding: 5px;" class="comm" align="center"></td></tr>
								<tr><td style="padding: 5px;" align="right">Итого к оплате</td><td class="sum" style="padding: 5px; background: #355177; color: white;" align="center"></td></tr>
							</table>
						</td></tr>
					</table>
					<hr>
					<div style="margin:12px">
						<center>
							<a href="#" target="_blank" class="down_xls" style="border: 1px solid #3F2020;padding: 5px;font-size: 13px;background: #9A9A9E;border-radius: 5px;color: white;">Скачать XLS</a>
							<a href="#" target="_blank" class="down_print" style="border: 1px solid #3F2020;padding: 5px;font-size: 13px;background: #9A9A9E;border-radius: 5px;color: white;">Печать</a>
						</center>
					</div>
				</div>

				';
	//--end--Send Mail----------------------------------
	$center.="<div id='pay_body' style='display: none'>
					<div id='pay_mail'><span class='pay_close'><u>Закрыть</u></span><br/><br/>
						<label>От кого: </label><input type='text' value='connect@makewear.com.ua' name='' class='in_text' /><br/>
						<label>Кому: </label><input type='text' name='' class='in_text' id='from_to' /><br/>
						<label>Тема: </label><input type='text' name='' class='in_text' id='from_sub' /><br/>
						
						<div class='html_mail' >
							{$send_mail}
						</div>

						<center style='margin:20px'>
							<button class='send'>Отправить</button>
							<button class='pay_close2'>Отмена</button>
						</center>
					</div>
				</div>";


	$payment_wait=$payment_all-$payment;
	$center .="<div style='padding: 20px; font-size: 17px;'>
					ИТОГО К ОПЛАТЕ: <span class='pay'>{$payment_all} грн</span> <span style='margin-left: 30px;'/> ОПЛАЧЕНО:  <span class='pay'>{$payment} грн</span> <span style='margin-left: 30px;'/> ОЖИДАЕТСЯ:  <span class='pay'>{$payment_wait} грн</span>     
				</div>";
	$center .= "
	
	<table class = 'sortable'>
		<tr>
		<th></th>
		<th>ID</th>
		<th>ДАТА</th>
		<th>НОМЕР</th>
		<th>КЛИЕНТ</th>
		<th>ЕДИНИЦ</th>
		<th>СУММА</th>
		<th>КОММЕНТАРИЙ</th>
		<th>СТАТУС</th>
		<th>Отправить счет</th>
		<th>Импорт</th>
		</tr>
		";
		$art_json=json_encode($art);
		$cli_json=json_encode($client);
		$tab_json=json_encode($tab_com);
		$tab2_json=json_encode($tab_com2);

		$off_sum=json_encode($offer_sum2);

		$dost=json_encode($del);
		$comm=json_encode($commissia);
		$sum_price=json_encode($sum_pri);

		//echo $tab2_json;

		//var_dump($tab_com2);

		$center.="<script>
					$(document).ready(function(){
						var rel='';
						var artt={$art_json};
						var client={$cli_json};
						var tab={$tab_json};
						var tab2={$tab2_json};
						var off_sum={$off_sum};
						var sum={$sum_price};
						var dost={$dost};
						var comm={$comm};

						
						$('.maill').click(function(){
							$('#pay_body').show();
							var rel2=$(this).attr('rel2');
							rel2=rel2.replace(' ', '');
							$('#from_to').val(rel2);


							rel=$(this).attr('rel');
						
							$('.art').text(artt[rel]);

							$('#from_sub').val('Счёт-фактура. Заказ №'+artt[rel]);

							$('.pl_client2').html(client[rel]);	
	
							$('.ttbody').html(tab[rel]);

							$('.all_price2').text(off_sum[rel]);

							$('.sum').text(sum[rel]);

							$('.dost').text(dost[rel]);

							$('.comm').text(comm[rel]);

							$('.down_xls').attr('href','http://makewear.com.ua/email/download_excel.php?exportIdd='+rel);
							$('.down_print').attr('href','http://makewear.com.ua/email/print_client.php?cli_id='+rel);

						});	
						$('.pay_close').click(function(){
							$('#pay_body').hide();						
						});	
						$('.pay_close2').click(function(){
							$('#pay_body').hide();						
						});	

						$('.send').click(function(){
							var up_to=$('#from_to').val();
							var up_sub=$('#from_sub').val();			

							var request =$.ajax({
							 	  url: 'modules/commodities/admin/mail_payment.php',
							 	  method: 'GET',
							 	  data: {mail:true, off_id:rel, to:up_to, subject:up_sub, art:artt[rel], cli:client[rel], tab2:tab2[rel], off_sum:off_sum[rel], sump:sum[rel], dost:dost[rel], comm:comm[rel] },
							 	  dataType: 'html'
							 	})
								 
								request.done(function( msg ) {
								  alert(msg);
								});
								 
								request.fail(function( jqXHR, textStatus ) {
								  alert( 'Request failed: ' + textStatus );
								});

								$('#pay_body').hide();
						 });
					});
				</script>";
	if($orders_head){
		foreach($orders_head as $k=>$v){
			$center.=$orders_head[$k];
		}
	}
	$center.="</table>";
	
}
		
