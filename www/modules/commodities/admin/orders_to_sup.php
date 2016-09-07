<?
if ($_SESSION['status']=="admin"){
	
	if(isset($_GET["sel"])){
		$upsel=$_GET["sel"];
		$upgroup=$_GET['group'];
	}


	$result_sum=0;
	$numssd=0;
	$result_quantity=0;
	$result_sum_to_pay=0;
	$result_sum_to_pay2=0;
	$result_sum_to_pay3=0;
	$result_profit=0;

	$sql = "SELECT *
			FROM  `sup_group` 
			INNER JOIN  `shop_orders_coms` 
			ON  `sup_group`.`group_id` =  `shop_orders_coms`.`group_id`
			WHERE  `com_status`=4 AND `status` in (3,4)";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)){

		$id = $row["id"];
		$group_id = $row["group_id"];
	//	echo $group_id.", ";
		$com_id = $row["com_id"];
		$com_sum2 = $row["price"];
		$size = $row["com"];
		$count = $row["count"];
		$cur_id = $row["cur"];
		$comment = $row["man_comment"];
		$com_status = $row["com_status"];
		$color = get_color_to_order($com_id);
		$sup_id[$group_id] = $row["sup_id"];

		$off_id=$row['offer_id'];
		$of=mysql_query("SELECT * FROM `shop_orders` WHERE `id`='{$off_id}'");
		$off=mysql_fetch_assoc($of);
		$o_id[$group_id]=$off['email'];
		$codd[$group_id]=$off['cod'];

		$ca=mysql_query("SELECT * FROM `shop_orders_contact` WHERE `com_id`='{$row['sup_id']}'");
		$cat=mysql_fetch_assoc($ca);
		$schet[$group_id]=$cat['payment'];
		$con=$cat['contact'];
		$deli=$cat['delivery'];

		$cat_q=mysql_query("SELECT `categories_of_commodities_ID`,`categories_of_commodities_parrent`,`cat_name` 
							FROM `shop_categories` 
							WHERE `categories_of_commodities_parrent`=10 
							AND `categories_of_commodities_ID`='{$row['sup_id']}' ");
		$category=mysql_fetch_assoc($cat_q);

		$ret[$group_id]="<table class='tab_b'>
							<th class='cat_name'>{$category['cat_name']}</th>
							<th class='con{$row['sup_id']}'>{$con}</th>
							<th class='payy{$row['sup_id']}'>{$schet[$group_id]}</th>
							<th class='deli{$row['sup_id']}'>{$deli}</th>
							<th style='width:30px;' ><span class='window_con' style='cursor:pointer' rel='{$row['sup_id']}'><img src='/templates/admin/img/btnbar_edit.png' /></span></th>
						</table>";
		
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

		$numssd++;
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
      $sql3 = "SELECT * FROM `suppliers` WHERE `sup_id` = $sup_id[$group_id]";
		$res3 = mysql_query($sql3);
		if($row3 = mysql_fetch_assoc($res3)){
			$sup_name[$group_id] = $row3["sup_name"];
			$sup_margin = $row3["sup_margin"];
		}
		
		$sql2 = "SELECT  `commodity_price` ,  `commodity_price2` ,  `com_name` ,  `from_url` ,  `cod` 
		FROM  `shop_commodity`  WHERE `commodity_ID` = {$com_id}";
		$res2 = mysql_query($sql2);
		if($row2 = mysql_fetch_assoc($res2)){
			$cod = $row2["cod"];
			$from_url = $row2["from_url"];
			$com_name = $row2["com_name"];
			$com_price_opt = $row2["commodity_price2"];
			$com_price = $row2["commodity_price"];
			
			if($upgroup==$group_id){
				if($upsel==1){
					$com_price_set=$com_price;
				}else{
					$com_price_set=$com_price_opt;				
				}
				mysql_query("UPDATE `shop_orders_coms` SET `price`={$com_price_set} WHERE `com_id`={$com_id};");
			}else{
				$com_price_set=$com_sum2;			
			}
			
				if($com_price_set==$com_price_opt){
					$com_sel2[$group_id]='selected';
				}elseif($com_price_set==$com_price){
					$com_sel1[$group_id]='selected';
				}
			
				$com_sum = $com_price_set*$count;
				
			}
		$com_sum_our = $com_sum*(100-$sup_margin)/100;
      $com_price_set_our = $com_price_set*(100-$sup_margin)/100;
		$lines[$group_id].="
		<tr>
		<td>{$cod}</td>
		<td>{$color}</td>
		<td>{$size}</td>
		<td>{$count}</td>
		<td>{$cur_id}</td>
		<td>{$com_price_set}</td>
		<td>{$com_price_set_our}</td>
		<td>{$com_sum}</td>
		<td>{$com_sum_our}</td>
		<td><a href ='{$from_url}'>Источник</a></td>
		<td>
			<select size='1' name='status' id = 'select_status_com' rel = '{$row['id']}' disabled>
					<option value= '0' {selected0}></option>
					<option value='1' rel='{$row2['id']};1' {$com_selected1}>Есть в наличии</option>
    				<option value='2' rel='{$row2['id']};2' {$com_selected2}>Нет в наличии</option>
    				<option value='3' rel='{$row2['id']};3'{$com_selected3}>Замена</option>
    				<option value ='4' {$com_selected4}>оплачен</option>
    			</select>
		</select></td></td>
		<td>{$comment}</td>
		</tr>";
		
		$total_price_to_pay[$group_id] += $com_sum*(100-$sup_margin)/100;
		$total_price[$group_id] +=$com_sum;
		$total_count[$group_id] +=$count;

		$result_sum +=$com_sum;
		$result_sum_to_pay += $com_sum*(100-$sup_margin)/100;
		$result_quantity +=$count;
	}
	$result_profit = $result_sum - $result_sum_to_pay;
	
	// var_dump($lines);
	$center.="<div class='body_edit' style='display: none;'>
					<div class='text_edit'>
						
						<span class='set_name' ></span><br>
						<span class='set_name2' >Контакте:</span><br>
						<textarea class='e_contact text_e' ></textarea><br>
						<span class='set_name2' >Оплата:</span><br>
						<textarea class='e_payment text_e' ></textarea><br>
						<span class='set_name2' >Доставка:</span><br>
						<textarea class='e_delivery text_e' ></textarea><br>
						<br>
						<center>
							<button class='down_edit'>Сохранить</button>
							<button class='close_edit'>Отмена</button>
						</center>
						
					</div>
				</div>";
	$center.="<div id='pay_body' style='display: none'>
					<div id='pay_mail'><span class='pay_close'><u>Закрить</u></span><br/><br/>
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
	$center .="
			<style>
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
					margin-top: 15%;
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
				#maill{cursor:pointer}
				.cat_name{
					width: 300px;
					text-align: center;
					vertical-align: middle;
					font-size: 20px;
					font-weight: bold;
					padding: 19px;
				}
				.tab_b{
					width:100%;
					background: transparent linear-gradient(#FFF, #EFEFEF) repeat scroll 0px 0px;
					border-collapse: collapse;
				}
				.tab_b th{
					border: 1px solid #CCC;
					vertical-align: middle;
				}
				.body_edit{
					background: rgba(0, 0, 0, 0.67) none repeat scroll 0% 0%;
					width: 100%;
					height: 100%;
					margin: -72px 0px 0px -180px;
					padding: 0px;
					position: fixed;
					z-index: 5;			
				}
				.text_edit{
					width: 600px;
					padding: 20px;
					background: #FFF none repeat scroll 0% 0%;
					border: 1px solid;
					margin-left: auto;
					margin-right: auto;
					margin-top: 5%;
				}
				
				.text_e{
					width: 100%;
					height: 110px;				
				}
				.set_name{
					font-weight: bold;
					font-size: 23px;
				}
				.set_name2{font-size: 17px;}
				</style>
			</style>
			<div style='padding: 20px; font-size: 17px;'>
				Сумма: <span class='pay'>{$result_sum}</span>  Заказов: <span class='pay'>{$numssd}</span>  Единиц: <span class='pay'>{$result_quantity}</span>  К оплате: <span class='pay'>{$result_sum_to_pay}</span>  Оплачено: <span class='pay'>{$result_sum_to_pay2}</span> Осталось оплатить: <span class='pay'>{$result_sum_to_pay3}</span>
			</div>";
	// var_dump($lines);
	if($group_line){
	foreach ($group_line as $key => $value) {
		// echo $key."=".$value."<br>";
		$profit = $total_price[$key] - $total_price_to_pay[$key];
		$center.=$ret[$key];
		$center .= "
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
		<!--<th>ДЕЙСТВИЯ</th>-->
		<th>Отправить уведомление</th>
		<th>СТАТУС</th>
		</tr>
		";
		$group_line[$key].="<tr class = 'c2_group_line' id='{$row0['group_id']}' rel='sup_group' rel2='group_id'>
			<td></td>
			<td>№{$key}</td>
			<td>{$sup_name[$key]}</td>
			<td>{$total_count[$key]}</td>
			<td class='getPrice{$key}'>{$total_price[$key]}</td>
			<td>{$total_price_to_pay[$key]}</td>
			<td>{$profit}</td>
			<td id ='comment' class = 'cl_edit'>{$row0['comment']}</td>
			<!--<td><a href = '?admin=mail_to_sup2&id={$key}' target = '_blank'>Почта</a></td>-->
			<td>
				<span id='maill' rel='{$key}' rel2='{$o_id[$key]}' art='{$codd[$key]}' >Mail</span>
				<input type='hidden' class='mail2' schet='$schet[$key]' />
			</td>
			<td>
			
			<select size='1' name='status' id = 'select_group_status' rel='{$key}'>
					<option value='3'  {$selected_group3[$key]}>Готов к оплате</option>
    				<option value='4'  {$selected_group4[$key]}>Оплачен поставщику</option>
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
			<th style='width:125px'>Цена
			<select id='select_pri{$key}' size='1' name='status' onchange='sel_pri({$key})' >
				<option value='1' {$com_sel1[$key]}>Розница</option>
				<option value='2' {$com_sel2[$key]}>Опт</option>
			</select>			
			</th>
			<th>Цена со скидкой</th>
			<th>Сумма</th>
			<th>Сумма со скидкой</th>
			<th>Ссылка</th>
			<th>Статус</th>
			<th>Комментарий</th>
			</tr>

			";
			$group_line[$key].=$lines["{$key}"];
			$group_line[$key] .="</table></td></tr>";
			$center.=$group_line[$key];
	}
}
	$center.="</table>";
	$center.="<script>
					$(document).ready(function(){

						
						$('#maill').click(function(){
							$('#pay_body').show();
							var rel=$(this).attr('rel');
							var rel2=$(this).attr('rel2');
							var art=$(this).attr('art');	
							rel2=rel2.replace(' ', '');
							$('#from_to').val(rel2);
							$('#from_sub').val('Оплата заказа №'+art);

							var getPrice=$('.getPrice'+rel).text();
							var schet=$('.mail2').attr('schet');
							var num=schet.replace(/[^0-9 ]/g, '');
							var val=schet.replace(/[0-9.]/g, '');
							val=val.replace('Пб ','');

							var txt='<div style=\'color:black;margin: 20px;font-size: 15px;font-weight: bold;\'>Здравствуйте.<br/>Вам была произведена оплата заказа №'+art+' на счёт '+num+'('+val+').<br/>Сумма: '+getPrice+'грн  </div>';
							$('.html_mail').html(txt);

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

							var msg=$('.html_mail').html();	

							var request =$.ajax({
							 	  url: 'modules/commodities/admin/mail_payment.php',
							 	  method: 'GET',
							 	  data: {mail2:true, to:up_to, subject:up_sub, message:msg },
							 	  dataType: 'html'
							 	})
								 
								request.done(function( msg ) {
								  alert(msg);
								  $('#pay_body').hide();
								});
								 
								request.fail(function( jqXHR, textStatus ) {
								  alert( 'Request failed: ' + textStatus );
								});

						 });

						//-------------------------------------------------
						$('.window_con').click(function(){
							$('.body_edit').show();
							var rel=$(this).attr('rel');
							var cat=$('.cat_name').text();
							$('.set_name').text(cat);
							$('.set_name').attr('rel',rel);

							var con=$('.con'+rel).text();
							$('.e_contact').val(con);
							var payy=$('.payy'+rel).text();
							$('.e_payment').text(payy);
							var deli=$('.deli'+rel).text();
							$('.e_delivery').text(deli);
						});
						$('.close_edit').click(function(){
							$('.body_edit').hide();
						});
						$('.down_edit').click(function(){
							var re=$('.set_name').attr('rel');
							var payment=$('.e_payment').val();
							var contact=$('.e_contact').val();
							var delivery=$('.e_delivery').val();
							//alert(re+'-'+payment);
							$.get('modules/commodities/admin/fun_ajax.php',{down_edit_id2:re, down_contact:contact, down_payment:payment, down_delivery:delivery })
							.done(function(d){
								//alert(d);
								cont();
							});
							$('.body_edit').hide();
						
						});	
						function cont(){
							$(document).ready(function(){
								$.getJSON('modules/commodities/admin/fun_ajax.php',{json:true})
								.done(function(up_js){
									for(var i=0; i<up_js.length; i++){
										var ii=up_js[i].comid;
										
										var con=up_js[i].cont;
										$('.con'+ii).html(con);
										$('.payy'+ii).html(up_js[i].pay);
										$('.deli'+ii).html(up_js[i].deli);
									}
									
								});
							
							});
						}
					});
				</script>";



}

