<?
if ($_SESSION['status']=="admin"){

include "soz.php";

	$id=$_GET["id"];

	$res=mysql_query("SELECT * FROM `shop_orders` WHERE `id`={$id}");
	$row=mysql_fetch_assoc($res);

	$name=$row["name"];
	$user_id=$row["user_id"];
	$email=$row["email"];
	$phone=$row["tel"];
	$city=$row["city"];
	$address=$row["address"];
	$arr_c=array();

	$user_id_name="";
	if($user_id!=0 || $user_id!=1){
		$user_id_name="Регистрация";
	}else{
		$user_id_name="Оформление заказа";
	}

	$clientRes=mysql_query("SELECT * FROM `soc_client` WHERE `order_id`={$id}");
	$cliRow=mysql_num_rows($clientRes);
	if($cliRow==0){
		mysql_query("INSERT INTO `soc_client`(`order_id`) VALUES ({$id}); ");
		mysql_query("INSERT INTO `soc_client_join`(`scj_name`,`scj_order_id`) VALUES ('head', {$id}); ");
		header("Location: {$_SERVER['REQUEST_URI']} ");
	}else{
		$cliRow=mysql_fetch_assoc($clientRes);

		$birthday=$cliRow["birthday"];
		if($birthday==""){
			$birthday="1.01.1900";
		}
		$skype=$cliRow["skype"];
		$web_site=$cliRow["web_site"];

		$countryStatus=$cliRow["country"];
		if($countryStatus==1){
			$countryStatus1="class='active'";
		}elseif($countryStatus==2){
			$countryStatus2="class='active'";
		}
		// ----ДАННЫЕ ДЛЯ ДОСТАВКИ----
		$delivery_select=null;
		$delivery_select=$cliRow["delivery_select"];
		$delivery_select_val1="";
		$delivery_select_val2="";
		$delivery_select_val3="";
		if($delivery_select==1){
			$delivery_select_val1="selected";
		}elseif($delivery_select==2){
			$delivery_select_val2="selected";
		}elseif($delivery_select==3){
			$delivery_select_val3="selected";
		}
		$delivery_number=$cliRow["delivery_number"];
		$delivery_address=$cliRow["delivery_address"];
		// ----ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ----
		$note_info=$cliRow["note_info"];

		$clientRes2=mysql_query("SELECT * FROM `soc_client_join` WHERE `scj_order_id`={$id}");
	while ($cliRow2=mysql_fetch_assoc($clientRes2)) {
			

		$scj_id=$cliRow2["scj_id"];
		$scj_name=$cliRow2["scj_name"];
		

		// ----БОНУС----
		$bonus_delivery=null;
		$bonus_delivery=$cliRow2["bonus_delivery"];
		$bonus_delivery_val1="";
		$bonus_delivery_val2="";
		if($bonus_delivery==1){
			$bonus_delivery_val1="selected";
		}elseif($bonus_delivery==2){
			$bonus_delivery_val2="selected";
		}
		$bonus_skidka_input=null;
		$bonus_skidka_input=$cliRow2["bonus_skidka_input"];
		if($bonus_skidka_input==""){
			$bonus_skidka_input=0;
		}
		$bonus_skidka_select=null;
		$bonus_skidka_select=$cliRow2["bonus_skidka_select"];
		$bonus_skidka_select_val1="";
		$bonus_skidka_select_val2="";
		$bonus_skidka_select_val3="";
		if($bonus_skidka_select==1){
			$bonus_skidka_select_val1="selected";
		}elseif($bonus_skidka_select==2){
			$bonus_skidka_select_val2="selected";
		}elseif($bonus_skidka_select==3){
			$bonus_skidka_select_val3="selected";
		}
		$bonus_gift=null;
		$bonus_gift=$cliRow2["bonus_gift"];
		$bonus_gift_val1="";
		$bonus_gift_val2="";
		$bonus_gift_val3="";
		if($bonus_gift==1){
			$bonus_gift_val1="selected";
		}elseif($bonus_gift==2){
			$bonus_gift_val2="selected";
		}elseif($bonus_gift==3){
			$bonus_gift_val3="selected";
		}

		$cond_delivery_input=null;
		$cond_delivery_input=$cliRow2["cond_delivery_input"];
		if($cond_delivery_input==""){
			$cond_delivery_input=0;
		}

		$cond_skidka_input=null;
		$cond_skidka_input=$cliRow2["cond_skidka_input"];
		if($cond_skidka_input==""){
			$cond_skidka_input=0;
		}

		$cond_gift_input=null;
		$cond_gift_input=$cliRow2["cond_gift_input"];
		if($cond_gift_input==""){
			$cond_gift_input=0;
		}

		$period_delivery_input=null;
		$period_delivery_input=$cliRow2["period_delivery_input"];
		if($period_delivery_input==""){
			$period_delivery_input=0;
		}

		$period_skidka_input=null;
		$period_skidka_input=$cliRow2["period_skidka_input"];
		if($period_skidka_input==""){
			$period_skidka_input=0;
		}

		$period_gift_input=null;
		$period_gift_input=$cliRow2["period_gift_input"];
		if($period_gift_input==""){
			$period_gift_input=0;
		}

		$cond_delivery_select=null;
		$cond_delivery_select=$cliRow2["cond_delivery_select"];
		$cond_delivery_select_val1="";
		$cond_delivery_select_val2="";
		$cond_delivery_select_val3="";
		$cond_delivery_select_val4="";
		if($cond_delivery_select==1){
			$cond_delivery_select_val1="selected";
		}elseif($cond_delivery_select==2){
			$cond_delivery_select_val2="selected";
		}elseif($cond_delivery_select==3){
			$cond_delivery_select_val3="selected";
		}elseif($cond_delivery_select==4){
			$cond_delivery_select_val4="selected";
		}

		$cond_skidka_select=null;
		$cond_skidka_select=$cliRow2["cond_skidka_select"];
		$cond_skidka_select_val1="";
		$cond_skidka_select_val2="";
		$cond_skidka_select_val3="";
		if($cond_skidka_select==1){
			$cond_skidka_select_val1="selected";
		}elseif($cond_skidka_select==2){
			$cond_skidka_select_val2="selected";
		}elseif($cond_skidka_select==3){
			$cond_skidka_select_val3="selected";
		}

		$cond_gift_select=null;
		$cond_gift_select=$cliRow2["cond_gift_select"];
		$cond_gift_select_val1="";
		$cond_gift_select_val2="";
		$cond_gift_select_val3="";
		$cond_gift_select_val4="";
		if($cond_gift_select==1){
			$cond_gift_select_val1="selected";
		}elseif($cond_gift_select==2){
			$cond_gift_select_val2="selected";
		}elseif($cond_gift_select==3){
			$cond_gift_select_val3="selected";
		}elseif($cond_gift_select==4){
			$cond_gift_select_val4="selected";
		}

		$period_delivery_select=null;
		$period_delivery_select=$cliRow2["period_delivery_select"];
		$period_delivery_select_val1="";
		$period_delivery_select_val2="";
		$period_delivery_select_val3="";
		$period_delivery_select_val4="";
		if($period_delivery_select==1){
			$period_delivery_select_val1="selected";
		}elseif($period_delivery_select==2){
			$period_delivery_select_val2="selected";
		}elseif($period_delivery_select==3){
			$period_delivery_select_val3="selected";
		}elseif($period_delivery_select==4){
			$period_delivery_select_val4="selected";
		}

		$period_skidka_select=null;
		$period_skidka_select=$cliRow2["period_skidka_select"];
		$period_skidka_select_val1="";
		$period_skidka_select_val2="";
		$period_skidka_select_val3="";
		if($period_skidka_select==1){
			$period_skidka_select_val1="selected";
		}elseif($period_skidka_select==2){
			$period_skidka_select_val2="selected";
		}elseif($period_skidka_select==3){
			$period_skidka_select_val3="selected";
		}

		$period_gift_select=null;
		$period_gift_select=$cliRow2["period_gift_select"];
		$period_gift_select_val1="";
		$period_gift_select_val2="";
		$period_gift_select_val3="";
		if($period_gift_select==1){
			$period_gift_select_val1="selected";
		}elseif($period_gift_select==2){
			$period_gift_select_val2="selected";
		}elseif($period_gift_select==3){
			$period_gift_select_val3="selected";
		}

		$styleStatus='';
		$statusActive="";
		$dell="";
		$scj_name=trim($scj_name);
		if($scj_name=="head"){
			$scj_name="Подарок за кождый заказ";
			$statusActive="active";
		}else{
			$styleStatus='style="display:none;"';
			$dell="<i class='fa fa-times' onclick='deleteLine({$scj_id})'></i>";
		}
		$addTableLine.="<div style='display:table-cell;position: relative;' id='bonusLine{$scj_id}' >
									<div class='changeLineBut {$statusActive}' rel='{$scj_id}'> {$scj_name}</div>
									{$dell}
								</div>";
		$addTable.="<table class='allTab' id='ss{$scj_id}' {$styleStatus}>
								<th></th><th colspan=2 >Бонус</th><th colspan=2 >Условие</th><th colspan=2 >Период</th><th></th>
								<tr>
									<td>
										Доставка:
									</td>
									<td colspan=2 >
										<select class='selectBonus pressSelectS' id='bonus_delivery' disabled rel-real={$bonus_delivery} >
											<option></option>
											<option value=1 {$bonus_delivery_val1}>Бесплатная</option>
											<option value=2 {$bonus_delivery_val2}>Платная</option>
										</select>
									</td>
									<td>
										<input type='text' class='inputConditional pressKeyS' id='cond_delivery_input' value='{$cond_delivery_input}' disabled rel-real='{$cond_delivery_input}' />
									</td>
									<td>
										<select class='selectConditional pressSelectS' id='cond_delivery_select' disabled rel-real='{$cond_delivery_select}'>
											<option></option>
											<option value=1 {$cond_delivery_select_val1}>ед/заказ</option>
											<option value=2 {$cond_delivery_select_val2}>грн/заказ</option>
											<option value=3 {$cond_delivery_select_val3}>дней</option>
											<option value=4 {$cond_delivery_select_val4}>заказ</option>
										</select>
									</td>
									<td>
										<input type='text' class='inputConditional pressKeyS' id='period_delivery_input' value='{$period_delivery_input}' disabled rel-real='{$period_delivery_input}' />
									</td>
									<td>
										<select class='selectConditional pressSelectS' id='period_delivery_select' disabled rel-real='{$period_delivery_select}'>
											<option></option>
											<option value=1 {$period_delivery_select_val1}>ед/заказ</option>
											<option value=2 {$period_delivery_select_val2}>грн/заказ</option>
											<option value=3 {$period_delivery_select_val3}>дней</option>
											<option value=4 {$period_delivery_select_val4}>заказ</option>
										</select>
									</td>

									<!--Button write and save-->
									<td rowspan=3 class='butWStd'> 
										<div class='butWS butWrite' >
											<div>W</div>
										</div>
										<div class='butWS active butSave' >
											<div>S</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										Скидка:
									</td>
									<td>
										<input type='text' style='width: 55px;' value='{$bonus_skidka_input}' class='pressKeyS' id='bonus_skidka_input' disabled rel-real='{$bonus_skidka_input}' />
									</td>
									<td>
										<select  class='pressSelectS' id='bonus_skidka_select' disabled rel-real='{$bonus_skidka_select}'>
											<option></option>
											<option value=1 {$bonus_skidka_select_val1}>%</option>
											<option value=2 {$bonus_skidka_select_val2}>грн</option>
											<option value=3 {$bonus_skidka_select_val3}>руб</option>
										</select>
									</td>
									<td>
										<input type='text' class='inputConditional pressKeyS' id='cond_skidka_input' value='{$cond_skidka_input}' disabled rel-real='{$cond_skidka_input}'/>
									</td>
									<td>
										<select class='selectConditional pressSelectS' id='cond_skidka_select' disabled rel-real='{$cond_skidka_select}'>
											<option></option>
											<option value=1 {$cond_skidka_select_val1}>ед/заказ</option>
											<option value=2 {$cond_skidka_select_val2}>грн/заказ</option>
											<option value=3 {$cond_skidka_select_val3}>заказ</option>
										</select>
									</td>
									<td>
										<input type='text' class='inputConditional pressKeyS' id='period_skidka_input' value='{$period_skidka_input}' disabled rel-real='{$period_skidka_input}' />
									</td>
									<td>
										<select class='selectConditional pressSelectS' id='period_skidka_select' disabled rel-real='{$period_skidka_select}'>
											<option></option>
											<option value=1 {$period_skidka_select_val1}>ед/заказ</option>
											<option value=2 {$period_skidka_select_val2}>грн/заказ</option>
											<option value=3 {$period_skidka_select_val3}>заказ</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										Подарок:
									</td>
									<td colspan=2 >
										<select class='selectBonus pressSelectS' id='bonus_gift' disabled rel-real='{$bonus_gift}'>
											<option></option>
											<option value=1 {$bonus_gift_val1}>Платье</option>
											<option value=2 {$bonus_gift_val2}>Футболка</option>
											<option value=3 {$bonus_gift_val3}>150 грн </option>
										</select>
									</td>
									<td>
										<input type='text' class='inputConditional pressKeyS' id='cond_gift_input' value='{$cond_gift_input}' disabled rel-real='{$cond_gift_input}'/>
									</td>
									<td>
										<select class='selectConditional pressSelectS' id='cond_gift_select' disabled rel-real='{$cond_gift_select}'>
											<option></option>
											<option value=1 {$cond_gift_select_val1}>ед/заказ</option>
											<option value=2 {$cond_gift_select_val2}>грн/заказ</option>
											<option value=3 {$cond_gift_select_val3}>заказ</option>
											<option value=4 {$cond_gift_select_val4}>Регистрация</option>
										</select>
										
									</td>
									<td>
										<input type='text' class='inputConditional pressKeyS' id='period_gift_input' value='{$period_gift_input}' disabled rel-real='{$period_gift_input}'/>
									</td>
									<td>
										<select class='selectConditional pressSelectS' id='period_gift_select' disabled rel-real='{$period_gift_select}'>
											<option></option>
											<option value=1 {$period_gift_select_val1}>ед/заказ</option>
											<option value=2 {$period_gift_select_val2}>грн/заказ</option>
											<option value=3 {$period_gift_select_val3}>заказ</option>
										</select>
										
									</td>
								</tr>

								</table>"; 
		}
		
	}


	$storyy=mysql_query("SELECT * FROM `shop_orders` WHERE `email`='{$email}';");
	while($st=mysql_fetch_array($storyy)){
		$idd=$st["id"];
		$art[$idd] = $row["cod"];
		$client[$idd]="{$name}<br>{$phone}<br>{$email}<br>{$city}<br>{$address}";
		$cur_id=$st["cur_id"];
		$order_count=0;
		$summm=0;
		$tab_lines="";

		$delivery=$st["delivery_price"];
		if($idd>=433){
			$commission=0;
		}else{
			$commission=$st["commission"];
		}

		$delivery2=$st["delivery"];
		if($delivery2==0){
			$delivery2=$st["name_delivery"];
		}else{
			$delSql=mysql_query("SELECT * FROM `shop_delivery` WHERE `id`='{$delivery2}'; ");
			$delRes=mysql_fetch_assoc($delSql);
			$delivery2=$delRes["name"];
		}

		$depart=$st['ttn-depart'];

		$depart2="";
		$ddd2=null;

		if(strpos($depart,':')===false){
			$ddd2=explode("(", $depart);
		}else{
			$ddd2=explode(":", $depart);
			if(strpos($ddd2[0],'(')!==false){
				$ddd2=explode("(", $ddd2[0]);
			}
		}

		$num_depart=explode("№", $ddd2[0]);
		if($num_depart[1]!=null){
			$depart2="№".$num_depart[1];
		}else{
			$depart2="";
		}
	
		if(strpos($st['address'],':')!==false){
			$ddd=explode(':', $st['address']);
			$address=$ddd[1];
		}else{
			if(strpos($st['name_delivery'],'Киев')!==false || $st['name_delivery']==null){
				$address=$st['address'];
			}else{
				$ddd=explode(':', $depart);
				$address=$ddd[1];
			}
		}

		if($depart==null){
			$address2=$city;
		}else{
			$address2=$depart.", ".$city;
		}

		// доставка
		if($st["sent_mail_mw_k"]==0){
			$sendd2="<span class='send_mail maill2 set_sent{$idd}' rel='{$idd}' rel2={$email} style='cursor:pointer;text-decoration:underline;'>
									<div class='icon_send'></div>
									<span>дост.</span>";
		}elseif($st["sent_mail_mw_k"]>=1){
			$sendd2="<span class='send_mail maill2' rel='{$idd}' rel2={$email} style='cursor:pointer;text-decoration:underline;color: #FF6C01;'>
						<div class='icon_sent' rel-was-sent={$st["payment_sent_mail"]} ></div>
						<span>дост.</span>
					</span>	";
		}
		// подтверд.
		if($st["sent_mail"]==0){
			$sendd1="<span class='send_mail maill1 set_sent{$idd}' rel='{$idd}' rel2={$email} style='cursor:pointer;text-decoration:underline;'>
									<div class='icon_send'></div>
									<span>подтв.</span>";
		}elseif($st["sent_mail"]>=1){
			$sendd1="<span class='send_mail maill1' rel='{$idd}' rel2={$email} style='cursor:pointer;text-decoration:underline;color: #FF6C01;'>
						<div class='icon_sent' rel-was-sent={$st["payment_sent_mail"]} ></div>
						<span>подтв.</span>
					</span>	";
		}
		// счет 
		if($st["payment_sent_mail"]==0){
			$sendd="<span class='send_mail maill set_sent{$idd}' rel='{$idd}' rel2={$email} style='cursor:pointer;text-decoration:underline;'>
									<div class='icon_send'></div>
									<span>счет</span>";
		}elseif($st["payment_sent_mail"]>=1){
			$sendd="<span class='send_mail maill' rel='{$idd}' rel2={$email} style='cursor:pointer;text-decoration:underline;color: #FF6C01;'>
						<div class='icon_sent' rel-was-sent={$st["payment_sent_mail"]} ></div>
						<span>счет</span>
					</span>	";
		}
		$cclip=$st["mw_k_clip"];
		$clip_name=$st["mw_k_clip_file"];	 
		
		if($cclip==1){
			$file2=end(explode(".",$st["mw_k_clip_file"]));
			$urlFile="http://".$_SERVER["HTTP_HOST"]."/uploads/delivery_MW_K/".$idd.".".$file2;
			$ghead=get_headers($urlFile);
			$head=substr($ghead[0], 9, 3);
			
			if($head!="200"){
				$size_kb = "File is empty";
			}else{
				$size_kb = filesize("uploads/delivery_MW_K/".$idd.".".$file2);
			}
		
			$clip_file= $idd.".".$file2;
		}
		if($st["mw_k_clip"]==0) {
			$clip="<div class='icon_clip_will' style='cursor:pointer;' rel='{$st["id"]}'></div>";
		}elseif($st["mw_k_clip"]==1) {
			$clip="<div class='icon_clip_was icw{$st["id"]}' style='cursor:pointer;' rel='{$st["id"]}' rel-file='{$clip_file}' rel-size='{$size_kb}' rel-name='{$clip_name}'></div>";	
		}
		$status = $st["status"];
		$status_selected1 = "";
		$status_selected2 = "";
		$status_selected3 = "";
		$status_selected4 = "";
		$status_selected5 = "";
		$status_selected6 = "";
		$status_selected7 = "";
		$status_selected12 = "";

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
		} elseif($status == 12){
			$status_selected12 = "selected";
		} 

		if($status <= 5 || $status == 12 ){
		$select_client="
			<select size='1' name='status' id = 'select_order_status' class='color_select2' rel='{$offer_id}'>
				<option value='3' rel='{$offer_id}' {$status_selected3}>Подтвержден</option>
    			<option value='4' rel='{$offer_id}' {$status_selected4}>Оплачен клиентом</option>
    			<option value='12' rel='{$offer_id}' {$status_selected12}>Оплачен MW</option>	
            </select>";
        }else{
        	$payment="";
        	if($row["payment_MW"]==1){
        		$payment=" и оплачен";
        	}
 	 		$select_client="
        	<select id = 'select_order_status' class='discolor_select2' disabled style='color:white' >
        		<option >Оплачен клиентом</option>
				<option value=\"6\" {$status_selected6}>Отправлен</option>
				<option value=\"7\" {$status_selected7}>Доставлен {$payment}</option>
			</select>";
		}


		$jj=0;
		$sql2 = "
		SELECT * FROM `shop_orders_coms`
		LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
		WHERE `offer_id`='{$idd}' AND `count`>0;";
		$res2 = mysql_query($sql2);
		while($row2=mysql_fetch_assoc($res2)){
			$com_id = $row2["com_id"];
			$size = $row2["com"];
			if($size=="(undefined)"){$size="";} 
			$count = $row2["count"];
			$cur_id2 = $row2["cur_id"];

			$order_count += $count;

			$cod2 = $row2["cod"];
			$from_url = $row2["from_url"];
			$com_name = $row2["com_name"];
			if($row2["com_color"] != "")
			{
				$color = $row2["com_color"];
			}
			else
			{
				$color = strip_tags(get_color_to_order($com_id));
			}

			if($status!=2 || $status!=3){
				$group_id=$row2["group_id"];
				$addOption="";
				$grres=mysql_query("SELECT `group_id`, `status` FROM `sup_group` WHERE `group_id`={$group_id}; ");
				$grrow=mysql_fetch_assoc($grres);

				$groupName=SOZ::getStatusCommodity($status,$grrow["status"]);
				if($groupName==""){
					$addOption="";
				}else{
					$addOption="<option selected>{$groupName}</option>";
				}
			}

			$comment = $row2["man_comment"];
			
			$price = $row2["price"]*$count;
			$com_sum = $row2["price"];

			$com_selected1 = "";
			$com_selected2 = "";
			$com_selected3 = "";
			$com_selected4 = "";
			$com_selected5 = "";
			$com_selected0 = "";
			$com_selected6 = "";
			$order_com_id = $row2["id"];

			$status_name="";
				$linecolor = "";
				$status_com = $row2["com_status"];

				if($status_com == 1){
					$com_selected1 = "selected";
					$linecolor = "greenline";
					//$payment+=$com_sum;
					$status_name='Есть в наличии';
				} elseif($status_com == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
					$offer_sum[$idd] -=$price;
					$order_count -= $row2["count"];
					$addOption="";
					$status_name="Нет в наличии";
				} elseif($status_com == 3){
					$com_selected3 = "selected";
					$addOption="";
					$status_name="Замена";
				} elseif($status_com == 0){
					$com_selected0 ="selected";
				} elseif($status_com == 4){
					$com_selected4 ="selected";
				} elseif($status_com == 5){
					$com_selected5 ="selected";
				} elseif($status_com == 6){
					$com_selected6 ="selected";
				} 		

				$offer_sum[$idd] +=$price;

				$basket_com_cat=SOZ::getBrandeName($row2['com_id']);
				$cat_name2=SOZ::getCategoryName($row2['com_id']);
				
				
				
				$tab_lines.="
				<tr id='{$row2["id"]}' rel='shop_orders_coms' rel2='id' class ='{$linecolor}'>
					<td>
						<input type=\"checkbox\" class=\"cl_trt\" rel=\"{$row2["id"]}\" rel-id=\"{$com_id}\">
					</td>
					<td>{$basket_com_cat}</td>
					<td>{$row2["cod"]}</td>
					<td>{$color}</td>
					<td>{$row2["com"]}</td>
					<td>{$row2["count"]}</td>
					<td>{$cat_name2}</td>
					<td>{$com_sum}</td>
					<td>{$price}</td>
					<td><a href='/pr{$row2["commodity_ID"]}/'>/pr{$row2["commodity_ID"]}/</a></td>
					<td><a href = '{$row2['from_url']}' target = '_blank'>Источник</a></td>
					<td id = 'man_comment' class='cl_edit'>{$row2["man_comment"]}</td>
					<td>
						<select size='1' name='status' id = 'select_status_com' rel = '{$c['id']}' disabled>
							<option value= '0' {$selected0}></option>
							<option value='1' rel='{$row2['id']};1' {$com_selected1}>Есть в наличии</option>
		    				<option value='2' rel='{$row2['id']};2' {$com_selected2}>Нет в наличии</option>
		    				<option value='3' rel='{$row2['id']};3'{$com_selected3}>Замена</option>
		    				{$addOption}
		    			</select>
					</td>
				</tr>";

				//--For mail---	
			if($status_com!=2){
				$tab_com[$idd].='<tr>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$num.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$basket_com_cat.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$row2["cod"].'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$color.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$size.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$count.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$com_sum.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$glb["cur"][$cur_id].'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$price.'</td><tr>';
				$tab_com2[$idd][$num]=array('num'=>$num,'com_cat'=>$basket_com_cat,'cod'=>$cod2,'color'=>$color,'size'=>$size,'count'=>$count,'price'=>$com_sum,'cur'=>$glb["cur"][$cur_id],'com_sum'=>$price);
				$num++;
			}
			$arr_c[$idd][$jj]=array(
					"brands"=>$basket_com_cat, 
					"art"=>$row2["cod"], 
					"color"=>$color, 
					"size"=>$row2["com"],
					"count"=>$count, 
					"price"=>$com_sum, 
					"all_price"=>$price, 
					"cur"=>$glb["cur"][$cur_id],
					"url"=>$from_url,
					"cat"=>$cat_name2,
					"name"=>$com_name,
					"status"=>$status_name,
					"delivery"=>$delivery
					);
					$jj++;
		}
		$cur_name=$glb["cur"][$cur_id];
		$sum[$idd]=$offer_sum[$idd]+$delivery+$commission;
		$offer_sum2[$idd] = $offer_sum[$idd]." {$cur_name}";
		$del[$idd]=$delivery." {$cur_name}";;
		$commissia[$idd]=$commission." {$cur_name}";;
		$sum_pri[$idd]=$sum[$idd]." {$cur_name}";
		$all_line.="
				<tr class='forsearch tab_up' style='{$active}' id='client_open{$st["id"]}' >
				<td class='clear_bottom_line' style='border-bottom: 0px solid #CCC;' ></td>
					<td style='border-bottom: 0px solid #CCC;'  >
						<div class='hdiv'><div class='hdiv2'>
							<div style='display:table;'>
								<div style='display:table-cell;padding-right: 3px;'>
									<span class='cli_open' rel='{$st["id"]}' >
										<div class='block_down' id='bb{$st["id"]}'></div>
									</span>
								</div>
								<div style='display:table-cell;'>
									<span class=' cli_cod go_href' date_href='/?admin=edit_order20&id={$st["id"]}' >{$st["cod"]} </span>
								</div>
							</div>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_id go_href' date_href='/?admin=edit_order20&id={$st["id"]}' >{$st["id"]} </span>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_date go_href' date_href='/?admin=edit_order20&id={$st["id"]}' >{$st["date"]} </span>
						</div></div>	
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_status'>
								{$select_client}						
							</span>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_count go_href' date_href='/?admin=edit_order20&id={$st["id"]}' >{$order_count}</span>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_summa go_href' date_href='/?admin=edit_order20&id={$st["id"]}' >{$sum[$idd]} {$glb["cur"][$cur_id]}</span>
						</div></div>	
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_commodity go_href' date_href='/?admin=edit_order20&id={$st["id"]}' >{$offer_sum[$idd]}</span>							
						</div></div>	
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_commission go_href' date_href='/?admin=edit_order20&id={$st["id"]}' >{$commission}</span>
						</div></div>
					</td>		
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_delivery go_href' date_href='/?admin=edit_order20&id={$st["id"]}' >{$delivery}</span>							
						</div></div>
					</td>		
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<div style='display:table;'>
								<div style='display:table-cell;vertical-align: middle;width: 60px;'>
									<div style='margin: 0px auto;display: inline-block;'>
										{$sendd2}						
									</div>	
								</div>
								<div style='display:table-cell;vertical-align: middle;width: 74px;'>
									<div style='margin: 0px auto;display: inline-block;'>
										{$sendd1}						
									</div>	
								</div>
								<div style='display:table-cell;vertical-align: middle;width: 53px;'>
									<div style='margin: 0px auto;display: inline-block;'>
										{$sendd}						
									</div>	
								</div>
								<div style='display:table-cell;vertical-align: middle;width: 30px;'>
									<div style='margin: 0px auto;display: inline-block;'>
										{$clip}						
									</div>	
								</div>
								<div style='display:table-cell;width: 34px;padding-right: 11px;position: relative;vertical-align: middle;'>	
								<div style='display: inline-block;margin: 0px auto;'>			
									<span class='cli_cont coo{$st["id"]}' rel='{$st["id"]}'>
										<div class='cli_info cli_info_open{$st["id"]}' style='display:none'>
											<table width='100%' >
												<tr><td class='info_td'></td><td></td></tr>
												<tr><td class='info_td'>E-mail:</td><td>{$email}</td></tr>
												<tr><td class='info_td'>Телефон:</td><td>{$phone}</td></tr>
												<tr><td class='info_td'>Город:</td><td>{$city}</td></tr>
												<tr><td class='info_td'>Адрес:</td><td>{$address}</td></tr>
											</table>
										</div>
										<div class='bor_i{$st["id"]}' style='position: absolute;top: 4px;left: 5px;'>
											<div class='icon_cont icc' id='ic{$st["id"]}' style='float:left;'></div>
											<span class='bcc' id='bc1{$st["id"]}'> 
												<span class='block_down1 b_cont' id='bc{$st["id"]}' ></span>
											</span>
										</div>
									</span>
								</div>
								</div>
								<div style='display:table-cell;width: 28px;vertical-align: middle;'>
									<div class=' cli_xls'>
										<div class='bor_xls'>
											<a href='/email/download_excel.php?exportIdd={$st["id"]}'>
												<div class='icon_xls'></div>
											</a>
										</div>
									</div >
								</div>
								<div style='display:table-cell;position: relative;'>
									<div class='order_note' >
										<div class='{$putInfo} open_backg' rel='{$st["id"]}' style='display:table;'>
											<div style='display:table-cell'>
												<div class='{$putInfoColor} iiw{$st["id"]}'></div>
											</div>
											<div style='display:table-cell;vertical-align: middle;padding-left: 2px;'>
												<div class='{$putDownColor} bbc{$st["id"]}' ></div>
											</div>
										</div>	
										<div class='wind_names' id='open_win2{$st["id"]}' style='display:none;right:8px;margin-top:0px;margin-right: -10px;width: 300px;'>
											<table>
												<tr><td></td></tr>
												<tr>
													<td style='font-weight:100;text-align: left;width: 300px;height: 90px;' contenteditable='true' class='write_tab' rel='{$st["id"]}'>
														{$write_deli}
													</td>
												</tr>
												<tr><td></td></tr>
											</table>
										</div>	
									</div>
								</div>
							</div>
						</div></div>
					</td>
				</tr>
				<tr class='{$but_active}' style='{$active}' >
				<td class='clear_bottom_line' style='border-bottom: 0px solid #CCC;' ></td>
					<td colspan=11 style='border-bottom: 0px solid #CCC;padding-top: 0px;' >
						<div class='table_line table_commodity{$st["id"]}' style='display:none'>
							<table class='sortable'>
								<tr>
									<th></th>
									<th>Бренд</th>
									<th>Артикул</th>
									<th>Цвет</th>
									<th>Размер</th>
									<th>Кол-во</th>
									<th>Товар</th>
									<th>
										Цена
										<select class='change_price_opt' rel='{$st["id"]}' rel-cur='{$cur_id}' >
											<option></option>
											<option value=1 >Розница</option>
											<option value=2 >Опт</option>
										</select>
									</th>
									<th>Сумма</th>
									<th>Ссылка на товар</th>
									<th>Источник</th>
									<th>Комментарий</th>
									<th>Статус</th>
								</tr>
								{$tab_lines}
							</table>
						</div>
					</td>
				</tr>
			";
	}

	$center.="
		<br/><br/>
		<div class='bodyCardClient' >

			<div class='idClient' rel='{$id}' >ID {$id} {$name}</div>

			<div class='tab butCard'>
				<div class='tab-td active' rel-card='1' >
					Профиль
				</div>
				<div class='tab-td' rel-card='2' >
					История заказов
				</div>
				<div class='tab-td' rel-card='3' >
					Мои желания
				</div>
				<div class='tab-td' rel-card='4' >
					Лист наблюдений
				</div>
			</div>

			<div id='card1' class='lineCard' >
				<div class='tab' style='width: 50%;'>
					<div class='tab-td' style='vertical-align: top;'>
						<div class='nameTable' >
							ПЕРСОНАЛЬНАЯ ИНФОРМАЦИЯ: {$user_id_name}
						</div>
						<div class='cardTable'>
							<div id='linetable' >
								<div class='idClient2' >ID {$id}</div>
								<div class='client'>
									<label>Фамилия:</label><br/>
									<input type='text' class='get_name' value='{$name}' /><br/>
									<label>Имя:</label><br/>
									<input type='text' value='Елена' /><br/>
									<label>Пароль:</label><br/>
									<input type='text' value='a12345' />
								</div>
							</div>
						</div>
						<!--<div class='cardTable'>
							<div id='linetable' >
								<div class='idClient3' >
									При оформлении заказ<br/>
									ID {$id} {$name}
								</div>
							</div>
						</div>-->
						<div class='nameTable' >
							КОНТАКТЫ
						</div>
						<div class='cardTable'>
							<div id='linetable' >
								<div class='contact'>
									<div class='tab'>
										<div class='tab-tr'>
											<div class='tab-td tab-right'>
												Страна:
											</div>
											<div class='tab-td'>
												<div class='tab butStrana' style='width: 100%;' >
													<div class='tab-td'>
														<div id='but' rel-country='1' rel-id='country' {$countryStatus1}>Украина</div>
													</div>
													<div class='tab-td'>
														<div id='but' style='float: right;margin-right: 5px;' rel-country='2' rel-id='country' {$countryStatus2}>Россия</div>
													</div>
												</div>
											</div>
										</div>
										<div class='tab-tr'>
											<div class='tab-td tab-right'>
												Дата рождения:
											</div>
											<div class='tab-td'>
												<input type='text' value='{$birthday}' class='pressKey' id='birthday' />
											</div>
										</div>
										<div class='tab-tr'>
											<div class='tab-td tab-right'>
												Электроная Почта:
											</div>
											<div class='tab-td'>
												<input type='text' class='get_mail' value='{$email}' />
											</div>
										</div>
										<div class='tab-tr'>
											<div class='tab-td tab-right'>
												Город:
											</div>
											<div class='tab-td'>
												<input type='text' class='get_city' value='{$city}' />
											</div>
										</div>
										<div class='tab-tr'>
											<div class='tab-td tab-right'>
												Адрес:
											</div>
											<div class='tab-td'>
												<input type='text' class='get_add' value='{$address}' rel-add='{$depart2}' rel-delivery='{$delivery2}' />
											</div>
										</div>
										<div class='tab-tr'>
											<div class='tab-td tab-right'>
												Телефон:
											</div>
											<div class='tab-td'>
												<input type='text' class='get_tel' value='{$phone}' />
											</div>
										</div>
										<div class='tab-tr'>
											<div class='tab-td tab-right'>
												Skype:
											</div>
											<div class='tab-td'>
												<input type='text' class='pressKey' id='skype' value='{$skype}' />
											</div>
										</div>
										<div class='tab-tr'>
											<div class='tab-td tab-right'>
												Web-сайт:
											</div>
											<div class='tab-td'>
												<input type='text' class='pressKey' id='web_site' value='{$web_site}' />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class='nameTable' >
							ДАННЫЕ ДЛЯ ДОСТАВКИ
						</div>
						<div class='cardTable'>
							<div id='linetable' >
								<div class='delivery'>
									<div class='tab'>
										<div class='tab-tr'>
											<div class='tab-td tab-right'>
												Перевозчик:
											</div>
											<div class='tab-td'>
												<select class='pressSelect' id='delivery_select'>
													<option value='1' {$delivery_select_val1}>Выберете перевозчик</option>
													<option value='2' {$delivery_select_val2}>Выберете перевозчик1</option>
													<option value='3' {$delivery_select_val3}>Выберете перевозчик2</option>
												</select>
											</div>
										</div>
										<div class='tab-tr'>
											<div class='tab-td tab-right'>
												Номер склада и улица:
											</div>
											<div class='tab-td'>
												<input type='text' class='pressKey' id='delivery_number' value='{$delivery_number}' />
											</div>
										</div>
										<div class='tab-tr'>
											<div class='tab-td tab-right'>
												Адрес:
											</div>
											<div class='tab-td'>
												<input type='text' class='pressKey' id='delivery_address' value='{$delivery_address}' />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<div class='tab-td' style='padding-left: 15%;vertical-align: top;' >
						<div style='position: relative;height: 30px;'>
						<div class='changeLine' >
							<div style='display:table;height: 30px;'>
								{$addTableLine}
							
								<div class='addLine' style='display:table-cell;position:relative;'>
									<button>+</button>
								</div>
							</div>
						</div>
						</div>
						<div class='cardTable'>
							<div id='linetable' class='bonus'>
								{$addTable}
							</div>
						</div>
						<div class='nameTable' >
							ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ
						</div>
						<div class='cardTable' style='width: 100%;'>
							<div id='linetable' >
								<div class='info'>
									<div class='infoType pressKey' id='note_info' >{$note_info}</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id='card2' class='lineCard' style='display:none;' >
				<table class = 'sortable tab_center_th payment_client'>
					<tr>
						<th></th>
						<th>№</th>
						<th>ID</th>
						<th>Дата</th>
						<th>Статус</th>
						<th>Единиц</th>
						<th>Сумма</th>
						<th>Товар</th>
						<th>Скидка</th>
						<th>Доставка</th>
						<th></th>
					</tr>
					{$all_line}
				</table>
			</div>
			<div id='card3' class='lineCard' style='display:none;' >
				Мои желания
			</div>
			<div id='card4' class='lineCard' style='display:none;' >
				Лист наблюдений
			</div>

		</div>
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
	$today=date("d")." ".$monthes[date("n")]." ".date("Y");
		$send_mail='
				<div style="font-family: Arial,Helvetica,sans-serif; color: black;border: 1px solid #54A5B2;padding: 3px;margin-bottom: 3px;" >
					<div class=mw_logo >
						<img src="http://www.makewear.com.ua/email/images/mw_logo.jpg" style="width: 110px;margin-left: 20px;" />
						<span style="font-size: 25px; float: right; margin-right: 5px; margin-top: 35px; color: #54A5B2;">СЧЕТ</span>
					</div>
					<table style="width:100%">
						<tr><td>
							<div id="chetrez" style="display: inline-block; color: black; font-size: 11px; border: 1px solid; padding: 5px; border-color: #8A8A9B;" >
								
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
					<table style="width:100%">
						<tr><td>
							<div style="color: #000; font-size: 11px; border: 3px solid #8A8A9B; width: 266px; padding: 5px; background: #DDD;" >
								Все чеки подлежат уплате компании MakeWear. По<br/>
								всем вопросам, касающимся этого  счета-фактуры,<br/>
								обращайтесь по телефонам<br/>
								<span id="inphone"></span>
								или адресу <span id="inemail"></span><br/>
								<br/>
								<center><b>Благодарим за сотрудничество!</b></center>
								<br/>
							</div>
						</td><td align="right">
							<table style="color:black; border-collapse: collapse; margin-right: 20px; font-size: 14px; font-weight: bold;" >
								<tr><td style="padding: 5px;" align="right">Доставка</td><td style="padding: 5px;" class="dost" align="center"></td></tr>
								<tr><td style="padding: 5px;" align="right">Комиссия(3%)</td><td style="padding: 5px;" class="comm" align="center"></td></tr>
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
				<hr/>
				<!--<div style=\'color: black;\'>
					С уважением, MakeWear<br/>
					Телефон: +380 (099) 647-73-23<br/>
					<a href=\'http://makewear.com.ua\'>www.mekawear.com.ua</a><br/>
				</div>-->

				';
	//--end--Send Mail----------------------------------
	$center.="<div id='pay_body' style='display: none'>
					<div id='pay_mail'>
					<div class='topDiv' >
						<div class='icon_close pay_close' ></div>
					</div>
					<br/><br/>
						<label>От кого: </label><input type='text' value='sales@makewear.com.ua' name='' class='in_text' /><br/>
						<label>Кому: </label><input type='text' name='' class='in_text' id='from_to' /><br/>
						<label>Тема: </label><input type='text' name='' class='in_text' id='from_sub' /><br/>
						
						<div class='html_mail' >
							{$send_mail}
						</div>

						<div style='height: 40px;'>
							<button class='pay_close2'>Отмена</button>
							<div class='sent_order' >Отправить</div>
						</div>
					</div>
				</div>";
	$center.="
		<div class='send_body' style='display:none'>
			<div class='send_window'>
				<div id='size_w'>
					<div class='icon_close'></div>
				</div>
				<table class='tab_send' >
					<tr>
						<td>
							<label>Кому</label>
							<input type='text' class='tab_send_towhere'  />
						</td>
						<td rowspan=3 style='width: 16%;'>
							<img src='http://www.makewear.com.ua/email/images/mw_logo.jpg' style='width: 91px;' />
						</td>
					</tr>
					<tr>
						<td>
							<label>От кого</label>
							<input type='text' class='tab_send_whom' value='sales@makewear.com.ua' />
						</td>
					</tr>
					<tr>
						<td>
						<label  style='text-align: center;'>Тема</label>
							<input type='text' class='tab_send_subject' value='Ваш заказ на сайте makewear.com.ua' />
						</td>
					</tr>
				</table>
				<hr style='width: 99%;' />
					<div class='sent_html'>
						Body
					</div>
				<hr style='width: 99%;' />
				<div style='height: 50px;'>
					<button class='close_window'>Отмена</button>
					<span class='sent_order'> Отправить</span>	
				</div>
			</div>
		</div>
		";
	$center.="
		<div class='body_upload' style='display:none'>
			<div class='bbb2'>
			<div style='overflow-y: scroll;height: 100%;'>
				<div class='wind_upload' style='display:block'>
				
						<div class='close_upload icon_close' ></div>
						<div>ТОРГОВО-ТРАНСПОРТНАЯ НАКЛАДНАЯ</div>
						<div class='names' style='text-align:left;'>Заказ №<span id='get_upload_order'></span> </div>	
						<div class='names' style='text-align:left;'>Товаров: <span id='get_count'></span> ед </div>
						<div class='names' style='text-align:left;'>Способ доставки: <span id='get_deli'></span> </div>
							<div id='uupp' >
								<div>
									ЗАГРУЗИТЬ ДОКУМЕНТ
								</div>
								<input type='file' name='file' class='upload' />
							</div>				
						<div>
							Загрузите скан-копию документа, подтверждающего осуществление оплаты.<br/>
							Допустимые форматы: JPG, PNG, PDF;
						</div>
					
				</div>
				<div class='show_file' style='display:none' >
					<div class='close_upload icon_close' ></div>
					<div class='see_file'>
						
					</div>
					
					<div style='display:table;width: 100%;'>	
						<div style='display:table-cell' >				
							<div class='any_file'>
								<div class='icon_file_upload' ></div>
								Загрузить другой файл
								<input type='file' name='file' class='upload' />
							</div>
							<div class='delete_file'  >
								<i class=\"fa fa-times\" aria-hidden=\"true\"></i>Удалить
							</div>
						</div>
						<div style='display:table-cell;position: relative;'>
														
							<div class='name_file' >
								<div style='display:table;width: 100%;'>
									<div style='display:table-row' style='padding:2px;' >
										<table>
											<tr>
												<td>
													<div class='icon_file_jpg'></div>
												</td>
												<td>
													<span class='set_name' style='font-size: 11px; color: black;' ></span><br/>
													<span class='set_size' style='font-size:10px; color:gray;' /></span>
												</td>
											</tr>
										</table>
										<br/>
									
									</div>
									<div style='display:table-row;background: #E8E7E7;color: #5F5F5F;'>
										<div style='padding: 4px;' >								 		
								 		Прикреплено: 1файл. Общий размер:<span class='set_size' />
								 		</div>
								 	</div>
							 	</div> 
							</div>
						</div>
					</div>				
				</div>
			</div>
			</div>
		</div>
		";	

	$art_json=json_encode($art);
	$cli_json=json_encode($client);
	$tab_json=json_encode($tab_com);
	$tab2_json=json_encode($tab_com2);
	$off_sum=json_encode($offer_sum2);

	$dost=json_encode($del);
	$comm=json_encode($commissia);
	$sum_price=json_encode($sum_pri);

	$center.="
		<link href='/templates/admin/soz/style/cardClient.css' type='text/css' rel='stylesheet' />
		<link href='/templates/admin/soz/style/payment_by_client20.css' type='text/css' rel='stylesheet' />
		<link href='/templates/admin/soz/style/orders_by_client20.css' type='text/css' rel='stylesheet' />
		<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css\">
		<script src='/templates/admin/soz/js/cardClient.js' ></script>
		<script src='/templates/admin/soz/js/client.js' ></script>
		<script src='/templates/admin/soz/js/payment_by_client20.js'></script>
		<script> 
			var rel='';
			var artt={$art_json};
			var client={$cli_json};
			var tab={$tab_json};
			var tab2={$tab2_json};
			var off_sum={$off_sum};
			var sum={$sum_price};
			var dost={$dost};
			var comm={$comm};

			// Для почта Подтвержден.
			var arr=".json_encode($arr_c).";
			var del=".json_encode($commissia).";
		
		</script>
		";

}

?>