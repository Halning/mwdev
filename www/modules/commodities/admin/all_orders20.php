<?
if ($_SESSION['status']=="admin"){

include "soz.php";

	$new_oreder_or=0;
	$new_oreder_comm=0;
	$new_oreder_summ=0;
	$new_oreder_summ_rub=0;
	$new_oreder_summ_usd=0;

	$obr_or=0;
	$obr_comm=0;
	$obr_summ=0;
	$obr_summ_rub=0;
	$obr_summ_usd=0;

	$podt_or=0;
	$podt_comm=0;
	$podt_summ=0;
	$podt_summ_rub=0;
	$podt_summ_usd=0;

	$pay_client_or=0;
	$pay_client_comm=0;
	$pay_client_summ=0;
	$pay_client_summ_rub=0;
	$pay_client_summ_usd=0;

	$sob_or=0;
	$sob_comm=0;
	$sob_summ=0;
	$sob_summ_rub=0;
	$sob_summ_usd=0;

	$sklad_or=0;
	$sklad_comm=0;
	$sklad_summ=0;
	$sklad_summ_rub=0;
	$sklad_summ_usd=0;

	$send_client_or=0;
	$send_client_comm=0;
	$send_client_summ=0;
	$send_client_summ_rub=0;
	$send_client_summ_usd=0;

	$close_or=0;
	$close_comm=0;
	$close_summ=0;
	$close_summ_rub=0;
	$close_summ_usd=0;

// Pagination LIMIT


	if(isset($_POST["sel_link"])){
		$_SESSION["select_link"]=$_POST["sel_link"];
	}
	if(isset($_SESSION["select_link"])){
		$limit_rows=$_SESSION["select_link"];
		switch ($limit_rows) {
			case 10:
				$limitSelected10="selected";
				break;
			case 25:
				$limitSelected25="selected";
				break;
			case 50:
				$limitSelected50="selected";
				break;
			case 75:
				$limitSelected75="selected";
				break;
			case 100:
				$limitSelected100="selected";
				break;
			default:

				break;
		}
	}else{
		$limit_rows=100;
	}


	$pagge=$_GET['p'];
	$archive=$_GET['archive'];

	//echo $archive.": ".$_GET["p"];
	if($archive=='true'){
		$status="`status` in (7,9,10,13)";
		$but_orr2="but_nn_active";
		$but_style2="border-bottom: 0px solid gray;color: black;";

		$fromDate=$_GET["fromData"];
		$toDate=$_GET["toData"];
		if(isset($fromDate)){
			if($fromDate!="0" && $toDate=="0"){
				$fromToDate="`date` > '{$fromDate}' ";
			}
			if($fromDate=="0" && $toDate!="0"){
				$fromToDate="`date` <= '{$toDate}' ";
			}
			if($fromDate!="0" && $toDate!="0"){
				$fromToDate=" `date` > '{$fromDate}' AND `date` <= '{$toDate}' ";
			}

			$fromToDate=" AND ".$fromToDate;
		}else{
			$fromToDate=" ";
		}
	}else{
		$status="`status` in (1,2,3,4,5,6,8, 12)";
		$but_orr1="but_nn_active";
		$but_style1="border-bottom: 0px solid gray;color: black;";

		$fromToDate='';
	}

	$mysql_len=mysql_query("
		SELECT *
		FROM `shop_orders`
		WHERE {$status}
		ORDER BY `date` DESC 
		");
	$lengths=mysql_num_rows($mysql_len);

	if(isset($archive)){
	if($lengths>0){
		if($pagge){
			$page=$pagge;
		}else{
			$page=1;
		} 
		$pages=$limit_rows*($page-1);
		$limit="LIMIT {$pages}, {$limit_rows}";
		$length_links=ceil($lengths/$limit_rows);

		$server=$_SERVER["REQUEST_URI"];
		if(strpos($server, "&p=")!==flase){
			$server=str_replace("&p={$page}","",$server);
		}


		$links="<div class='links'><div class='links_div'><ul class='links_li'>";
		$page1=$page-1;
		if($page<=1){
			// $links.="<li style='color:gray' ><<</li>";
		}else{
			$links.="<a href='{$server}&p={$page1}' ><li><<</li></a>";
		}

		if($length_links>10){
			if($page!=1){
				$links.="<a href='{$server}&p=1' ><li>1</li></a>";
			}
			$page_prev=$page-1;
			$page_prev2=$page-2;
			$page_prev3=$page-3;
			$page_next=$page+1;
			$page_next2=$page+2;
			$page_next3=$page+3;

			if($page > 4){
				$links.="<li class='leng_span'> <span>. . .</span> </li>";
			}
			if($page > 3){
				$links.="<a href='{$server}&p={$page_prev2}' ><li>{$page_prev2}</li></a>";
			}
			if($page > 2){
				$links.="<a href='{$server}&p={$page_prev}' ><li>{$page_prev}</li></a>";
			}	

			$links.="<li class='active'>{$page}</li>"; // active

			if($page < $page+1 && $page <= $length_links-2){
				$links.="<a href='{$server}&p={$page_next}' ><li>{$page_next}</li></a>";
			}
			if($page < $page+2 && $page <= $length_links-3){
				$links.="<a href='{$server}&p={$page_next2}' ><li>{$page_next2}</li></a>";
			}
			if($page < $length_links-3){
				$links.="<li class='leng_span'> <span>. . .</span> </li>";
			}
			if($page != $length_links){
				$links.="<a href='{$server}&p={$length_links}' ><li>{$length_links}</li></a>";
			}
			
		}else{
			for($i=1; $i<=$length_links; $i++){
				if($page==$i){
					$links.="<li class='active'>{$i}</li>";
				}else{
					$links.="<a href='{$server}&p={$i}' ><li>{$i}</li></a>";
				}
			}
		}

		$page2=$page+1;
		if($page2 == $length_links+1){
			//$links.="<li style='color:gray' >>></li>";
		}else{
			$links.="<a href='{$server}&p={$page2}' ><li>>></li></a>";
		}
		$links.="</ul></div></div>";
		$links.="<form active='{$server}' method='POST' id='links_select'>
						<select onchange=\"this.form.submit()\" name='sel_link' style='margin-left: 10px;' >
							<option value='10' {$limitSelected10}>10</option>
							<option value='25' {$limitSelected25}>25</option>
							<option value='50' {$limitSelected50}>50</option>
							<option value='75' {$limitSelected75}>75</option>
							<option value='100' {$limitSelected100}>100</option>
						</select>
					</form>
					<br/>";
	}
	}else{
		$limit="LIMIT 200";
	}
//-----End Pagination------





	$sql="SELECT *
		FROM `shop_orders`
		WHERE {$status} {$fromToDate}
		ORDER BY `date` DESC {$limit};
	";
	$result = mysql_query($sql);
	

	$all_lines = '';
	if(mysql_num_rows($result) > 0){

		while($row = mysql_fetch_assoc($result)){
			$price=0;
			$total_count=0;
			$cur_id=$row["cur_id"];
			$email=$row["email"];
			$phone=$row["tel"];
			$phone=change_phone($phone);
			$city=$row["city"];
			$address=$row["address"];
			$discount=$row["discount"];

			$sql2 = "SELECT SUM(`price`) AS all_price, COUNT( `offer_id` ) AS count_id 
			FROM  `shop_orders_coms` 
			WHERE  `offer_id` = {$row["id"]}
                        AND `com_status` <>2";
			//$price=get_oreder_sum($row["id"]);
			$res2 = mysql_query($sql2);
			
			if($row2 = mysql_fetch_assoc($res2))
			
			$write_deli=$row['note'];

			if($write_deli==""){
				$putInfoColor="icon_info_white";
				$putDownColor="block_down1";
				$putInfo="wind_o2";
			}else{
				$putInfoColor="icon_info_orange";
				$putDownColor="block_down1_orange";
				$putInfo="wind_o2_orange";
			}

			$active="display:grid;";
			$but_active="but_active";
			
			$sel_status1="";
			$sel_status2="";
			$sel_status3="";
			$sel_status4="";
			$sel_status5="";
			$sel_status6="";
			$sel_status7="";
			$sel_status8="";
			$sel_status10="";
			$sel_status12="";
			$sel_status13="";
			$sstatus=$row["status"];

			if($sstatus == 1){
				$status_of_order = "Новый заказ";
				$sel_status1="selected";
			}
			elseif($sstatus == 2)
			{
				$status_of_order = "Обрабатывается";
				$sel_status2="selected";
			}
			elseif($sstatus == 3)
			{
				$status_of_order = "Подтвержден";
				$sel_status3="selected";
			}
			elseif($sstatus == 4)
			{
				$status_of_order = "оплачен ";
				$sel_status4="selected";
			}
			elseif($sstatus == 5)
			{
				$status_of_order = "собран";
				$sel_status5="selected";
			}
			elseif($sstatus == 6)
			{
				$status_of_order = "отправлен клиенту";
				$sel_status6="selected";
			}
    		elseif($sstatus == 7)
    		{
    			$status_of_order = "закрыт";
    			$sel_status7="selected";
    		}
    		elseif($sstatus == 10)
    		{
    			$status_of_order = "Отменен";
    			$sel_status10="selected";
    		}
    		elseif($sstatus == 8)
    		{
    			$status_of_order = "На складе";
    			$sel_status8="selected";
    		}
    		elseif($sstatus == 9)
    		{
    			$status_of_order = "Закрыт";
    			$sel_status8="selected";
    		}
    		elseif($sstatus == 12)
    		{
    			$status_of_order = "отправлен MW";
    			$sel_status12="selected";
    		}
    		elseif($sstatus == 13)
    		{
    			$status_of_order = "Возврат";
    			$sel_status13="selected";
    		}			


			$ccur_show=$glb["cur"][$row["cur_id"]];

			$tab_lines="";
			$res2=mysql_query("SELECT * FROM `shop_orders_coms`
					LEFT JOIN `shop_commodity` 
					ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
					WHERE `offer_id`='{$row["id"]}' 
					AND `count`>0;");
			while($c=mysql_fetch_assoc($res2))
			{
				$group_id=$c["group_id"];
				

				$basket_com_price_one = 0;
				$com_id = $c["com_id"];
				$iddd = $c["commodity_ID"];
				$count = $c["count"];
				$com_selected1 = "";
				$com_selected2 = "";
				$com_selected3 = "";
				$com_selected4 = "";
				$com_selected5 = "";
				$com_selected0 = "";
				$com_selected6 = "";
				$price2=$c["price"];
				if($price2==0){
					$price2=$c["commodity_price2"]*$count;
					if($price2==0){
						$price2=$c["commodity_price"]*$count;
					}
				}

				$status = $c["com_status"];
				$addOption="";
				$grres=mysql_query("SELECT `group_id`, `status` FROM `sup_group` WHERE `group_id`={$group_id}; ");
				$grrow=mysql_fetch_assoc($grres);
				$groupName=SOZ::getStatusCommodity($sstatus,$grrow["status"]);
				// $groupName=SOZ::getStatusGroup($grrow["status"]);
				//$addOption="<option status='{$grrow["status"]}' selected>{$groupName}</option>";
				if($groupName==""){
					$addOption="";
				}else{
					$addOption="<option selected>{$groupName}</option>";
				}
				if($grrow["status"]==1){
					$linecolor = "";
				}

				$linecolor = "";
				if($status == 1){
					$com_selected1 = "selected";
					$linecolor = "greenline";
				} elseif($status == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
					$price -= $price2*$c["count"];
					$total_count -=$c["count"];
					$addOption="";
				} elseif($status == 3){
					$com_selected3 = "selected";
					$price -= $price2*$c["count"];
					$total_count -=$c["count"];
					$addOption="";
				} elseif($status == 0){
					$com_selected0 ="selected";
				} elseif($status == 4){
					$com_selected4 ="selected";
				} elseif($status == 5){
					$com_selected5 ="selected";
				} elseif($status == 6){
					$com_selected6 ="selected";
				}
				// if($status != 2 || $status != 3){
				// 	$addOption="";
				// 	$grres=mysql_query("SELECT `group_id`, `status` FROM `sup_group` WHERE `group_id`={$group_id}; ");
				// 	$grrow=mysql_fetch_assoc($grres);

				// 	$groupName=SOZ::getStatusCommodity($status,$grrow["status"]);
				// 	// $groupName=SOZ::getStatusGroup($grrow["status"]);
				// 	$addOption="<option status='{$grrow["status"]}' selected>{$groupName}</option>";
				// 	if($grrow["status"]==1){
				// 		$linecolor = "";
				// 	}

				// }

				$basket_com_cat=SOZ::getBrandeName($com_id);
				$cat_name2=SOZ::getCategoryName($com_id);

				$glb["templates"]->set_tpl('{$basket_com_cat}',$basket_com_cat);
					if($c["com_color"] == ""){
						$color = strip_tags(get_color_to_order($com_id));
					} else{
						$color = $c["com_color"];
					}
					
				
					
				$glb["templates"]->set_tpl('{$color}',$color);
				if ($c["count"]> 0){
					$basket_com_price_one =  $price2*$c["count"];
				}

			    
			    	$total_price +=$basket_com_price_one;
			    
				$total_count += $c["count"];
				$price += $basket_com_price_one;

				// $url=$c["alias"]!=""?"/pr{$c["com_id"]}_{$c["alias"]}/":"/pr{$c["com_id"]}/";
				// $url="/pr{$c["com_id"]}/";
				$url="/product/".$com_id."/".$c["alias"].".html";
				$src=$c["alias"]!=""?"<img src='/{$c["com_id"]}stitle/{$c["alias"]}.jpg' style='height:30px;'>":"";
				
				
				$tab_lines.="
				<tr id='{$c["id"]}' rel='shop_orders_coms' rel2='id' class ='{$linecolor}'>
					<td><input type='checkbox' class='cl_trt' rel='{$c["id"]}' rel-id='{$com_id}' /></td>
					<td>{$basket_com_cat}</td><td>{$c["cod"]}</td>
					<td id = 'com_color' class='cl_edit'>{$color}</td>
					<td id = 'com' class='cl_edit'>{$c["com"]}</td>
					<td>{$c["count"]}</td>
					<td>{$cat_name2}</td>
					<td>{$price2}</td>
					<td>{$basket_com_price_one}</td>
					<td><a href='{$url}'>{$url}</a></td>
					<td><a href = '{$c['from_url']}' target = '_blank'>Источник</a></td>
					<td id = 'man_comment' class='cl_edit'>{$c["man_comment"]}</td>
					<td>
						<select size='1' name='status' id = 'select_status_com' rel = '{$c['id']}' disabled>
							<option value= '0' {$selected0}></option>
							<option value='1' rel='{$c['id']};1' {$com_selected1}>Есть в наличии</option>
		    				<option value='2' rel='{$c['id']};2' {$com_selected2}>Нет в наличии</option>
		    				<option value='3' rel='{$c['id']};3'{$com_selected3}>Замена</option>
		    				{$addOption}
		    			</select>
					</td>
				</tr>";
			}
			$payment="";
        	if($row["payment_MW"]==1){
        		$payment=" и оплачен";
        	}
			$select_status="
				<select id='select_order_status' class='color_select' rel='{$row["id"]}' disabled>
					<option value='1' {$sel_status1}>Новый заказ</option>
					<option value='2' {$sel_status2}>Обрабатывается</option>
					<!--<option value='3' {$sel_status3}>Подтвержден</option>-->
					<option value='3' {$sel_status3}>Готов к оплате клиентом</option>
					<option value='12' {$sel_status12}>Оплачен MW</option>
					<option value='13' {$sel_status13}>Возврат</option>
					<option value='4' {$sel_status4}>Оплачен клиентом</option>
					<option value='8' {$sel_status8}>На складе</option>
					<option value='5' {$sel_status5}>Собран</option>
					<option value='6' {$sel_status6}>Отправлен</option>
					<option value='7' {$sel_status7}>Доставлен {$payment}</option>
					<option value='10' {$sel_status10}>Отменен</option>
				</select>
			";

			//href='/?admin=edit_order&id={$row["id"]}'
			//if($row["commission"]==0){
			//	$commisia=$price/100*3;
			//}else{
			//	$commisia=$row["commission"];
			//}

			
			if(1<=$discount && $discount<=3 ){
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
					$price+=$row['delivery_price'];
				}elseif($discount==2 && $total_count>=5){ 
					$ski='-10%';
					$price-=$price/100*10;
					$price+=$row['delivery_price'];
				}elseif($discount==3 && $total_count>=5){
					$delivery_price="Бесплатная";
					$delivery_price2=0;
				}
			}else{
				if($row["id"]<433){
					$commisia=round($price/100*3);
					$price += $commisia + $row['delivery_price'];
				}else{
					$price += $row['delivery_price'];
				}
			}




			if($sstatus==1){
				$new_oreder_or++;
				$new_oreder_comm+=$total_count;

				if($cur_id==1){ // uan
					$new_oreder_summ+=$price;
				}elseif($cur_id==2){ // usd
					$new_oreder_summ_usd+=$price;
				}elseif($cur_id==3){ // rub
					$new_oreder_summ_rub+=$price;
				}
			}
			if($sstatus==2){
				$obr_or++;
				$obr_comm+=$total_count;

				if($cur_id==1){ // uan
					$obr_summ+=$price;
				}elseif($cur_id==2){ // usd
					$obr_summ_usd+=$price;
				}elseif($cur_id==3){ // rub
					$obr_summ_rub+=$price;
				}
			}
			if($sstatus==3){
				$podt_or++;
				$podt_comm+=$total_count;

				if($cur_id==1){ // uan
					$podt_summ+=$price;
				}elseif($cur_id==2){ // usd
					$podt_summ_usd+=$price;
				}elseif($cur_id==3){ // rub
					$podt_summ_rub+=$price;
				}
			}
			if($sstatus==4){
				$pay_client_or++;
				$pay_client_comm+=$total_count;

				if($cur_id==1){ // uan
					$pay_client_summ+=$price;
				}elseif($cur_id==2){ // usd
					$pay_client_summ_usd+=$price;
				}elseif($cur_id==3){ // rub
					$pay_client_summ_rub+=$price;
				}
			}
			if($sstatus==5){
				$sob_or++;
				$sob_comm+=$total_count;

				if($cur_id==1){ // uan
					$sob_summ+=$price;
				}elseif($cur_id==2){ // usd
					$sob_summ_usd+=$price;
				}elseif($cur_id==3){ // rub
					$sob_summ_rub+=$price;
				}
			}
			if($sstatus==6){
				$send_client_or++;
				$send_client_comm+=$total_count;

				if($cur_id==1){ // uan
					$send_client_summ+=$price;
				}elseif($cur_id==2){ // usd
					$send_client_summ_usd+=$price;
				}elseif($cur_id==3){ // rub
					$send_client_summ_rub+=$price;
				}
			}
			if($sstatus==7){
				$close_or++;
				$close_comm+=$total_count;

				if($cur_id==1){ // uan
					$close_summ+=$price;
				}elseif($cur_id==2){ // usd
					$close_summ_usd+=$price;
				}elseif($cur_id==3){ // rub
					$close_summ_rub+=$price;
				}
			}
			if($sstatus==8){
				$sklad_or=0;
				$sklad_comm+=$total_count;

				if($cur_id==1){ // uan
					$sklad_summ+=$price;
				}elseif($cur_id==2){ // usd
					$sklad_summ_usd+=$price;
				}elseif($cur_id==3){ // rub
					$sklad_summ_rub+=$price;
				}
			}

			$all_line="
				<!--<tr class='cli{$row["id"]} {$but_active} tab_up forsearch' style='{$active}' id='client_open{$row["id"]}' >-->
				<tr class='cli{$row["id"]} {$but_active} tab_up forsearch' id='client_open{$row["id"]}' >
					<td style='border-bottom: 0px solid #CCC;' ></td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<div style='display:table;'>
								<div style='display:table-cell;'>
									<span class='cli_open' rel='{$row["id"]}' >
										<div class='block_down' id='bb{$row["id"]}'></div>
									</span>
								</div>
								<div style='display:table-cell;padding-left: 5px;'>
									<span class='cli_cod go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$row["cod"]} </span>
								</div>
							</div>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_date go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$row["date"]} </span>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_id go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$row["id"]} </span>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_status'>{$select_status}</span>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >	
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_name go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$row["name"]}</span>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_count go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$total_count}</span>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_summa go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$price} {$ccur_show}</span>
						</div></div>
					</td>

					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2' style='position: relative;' >
							<div style='display:table;width:100%' id='brow_line2'>
								<div style='display:table-cell;'>
										<div class='bor_i{$row["id"]}' id='brow_line' >
											<div class='icon_cont icc' id='ic{$row["id"]}' rel='{$row["id"]}' style='float:left;cursor:pointer;'></div>
											<span class='bcc' id='bc1{$row["id"]}'> 
												<span class='block_down1 b_cont' id='bc{$row["id"]}' ></span>
											</span>
										</div>
										<div class='cli_info cli_info_open{$row["id"]}' style='display:none'>
											<table width='100%' >
												<tr><td class='info_td'></td><td></td></tr>
												<tr><td class='info_td'>E-mail:</td><td>{$email}</td></tr>
												<tr><td class='info_td'>Телефон:</td><td>{$phone}</td></tr>
												<tr><td class='info_td'>Город:</td><td>{$city}</td></tr>
												<tr><td class='info_td'>Адрес:</td><td>{$address}</td></tr>
											</table>
										</div>
								</div>
								<div style='display:table-cell;'>
									<div class='bor_xls'>
										<a href='?admin=all_orders20&exportId={$row["id"]}'>
											<div class='icon_xls'></div>
										</a>
									</div>
								</div>
								<div style='display:table-cell;'>
									<div class='bor_chat'>
										<div class='icon_chat' rel='{$row["id"]}' ></div>
									</div>
								</div>
								<div style='display:table-cell;position: relative;'>
									<div class='order_note' >
										<div class='{$putInfo} open_backg' rel='{$row["id"]}' style='display:table;'>
											<div style='display:table-cell'>
												<div class='{$putInfoColor} iiw{$row["id"]}'></div>
											</div>
											<div style='display:table-cell;vertical-align: middle;padding-left: 2px;'>
												<div class='{$putDownColor} bbc{$row["id"]}' ></div>
											</div>
										</div>	
										<div class='wind_names' id='open_win2{$row["id"]}' style='display:none;right:8px;margin-top:0px;margin-right: -10px;width: 300px;'>
											<table>
												<tr><td></td></tr>
												<tr>
													<td style='font-weight:100;text-align: left;width: 300px;height: 90px;' contenteditable='true' class='write_tab' rel='{$row["id"]}'>
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
				<tr class='cli{$row["id"]} {$but_active}' >
					<td style='border-bottom: 0px solid #CCC;' ></td>
					<td colspan=8 style='border-bottom: 0px solid #CCC;padding-top: 0px;' >
						<div class='table_line table_commodity{$row["id"]}' style='display:none'>
							<table class='sortable'>
								<tr>
									<td>
									<input type='checkbox' class='all_change' rel='{$row["id"]}' />
									</td>
									<th>Бренд</th>
									<th>Артикул</th>
									<th>Цвет</th>
									<th>Размер</th>
									<th style='width: 44px;'>Кол-во</th>
									<th>Товар</th>
									<th>
										Цена
										<select class='change_price_opt' rel='{$row["id"]}' rel-cur='{$row["cur_id"]}' >
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


 
			$all_lines .= $all_line;
		}
	}
	
	// $its_name="Все заказы <br>
	// <b>Выбранные:</b> 
	// 	<span class='cl_delll'>Удалить <img src='/templates/admin/img/btnbar_del.png'></span>
	// 	<span class='cl_edittt'>Редактировать <img src='/templates/admin/img/btnbar_edit.png'></span>;";
	
	
	$all_lines .="
	<link href='/templates/admin/soz/style/all_orders20.css' type='text/css' rel='stylesheet' />
	<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css\">
	<script src='/templates/admin/js/i18n/datepicker-uk.js' type='text/javascript'></script>
	<script src='/templates/admin/js/i18n/datepicker-ru.js' type='text/javascript'></script>
	<script src=\"http://zoond-test.cloudapp.net:8264/socket.io/socket.io.js\"></script>					
	<script src='/templates/admin/soz/js/all_orders20.js' ></script>	
	<script src=\"/modules/mw_chat/onload.js\"></script>";

//	$additions_buttons=get_new_buttons("/?admin=admin_add_order_2" ,"Добавить заказ"); //$_SESSION["category"]
	$all_head="
		<tr>
			<th style='width:1px;' ></th>
			<th>№ заказа</th>
			<th>
				<div style='display:table;'>
					<div style='display:table-cell;'>
						Дата
					</div>
					<div style='display:table-cell;cursor:pointer;padding-left: 3px;' class='but_sort sort_up' rel-sort='3'>
						<div class='block_down2' ></div>
						<div class='block_up2' ></div>
					</div>
				</div>
			</th>
			<th>ID</th>
			<th>Статус</th>
			<th>Клиент</th>
			<th>
				<div style='display:table;'>
					<div style='display:table-cell;'>
						Единиц
					</div>
					<div style='display:table-cell;cursor:pointer;padding-left: 3px;' class='but_sort sort_up' rel-sort='7' >
						<div class='block_down2' ></div>
						<div class='block_up2' ></div>
					</div>
				</div>
			</th>
			<th>
				<div style='display:table;'>
					<div style='display:table-cell;'>
						Сумма
					</div>
					<div style='display:table-cell;cursor:pointer;padding-left: 3px;' class='but_sort sort_up' rel-sort='8'>
						<div class='block_down2' ></div>
						<div class='block_up2' ></div>
					</div>
				</div>
			</th>
			<th style='width: 200px;'></th>
		</tr>
		";

	$all_or=$new_oreder_or+$obr_or+$podt_or+$pay_client_or+$sklad_or+$send_client_or+$sob_or+$close_or;
	$all_comm=$new_oreder_comm+$obr_comm+$podt_comm+$pay_client_comm+$sklad_comm+$sob_comm+$send_client_comm+$close_comm;
	$all_summ=$new_oreder_summ+$obr_summ+$podt_summ+$pay_client_summ+$sklad_summ+$sob_summ+$send_client_summ+$close_summ;
	$all_summ_rub=$new_oreder_summ_rub+$obr_summ_rub+$podt_summ_rub+$pay_client_summ_rub+$sklad_summ_rub+$sob_summ_rub+$send_client_summ_rub+$close_summ_rub;
	$all_summ_usd=$new_oreder_summ_usd+$obr_summ_usd+$podt_summ_usd+$pay_client_summ_usd+$sklad_summ_usd+$sob_summ_usd+$send_client_summ_usd+$close_summ_usd;

	$center.="<br/>
		
		<div class='table_line2_white' >
			<table class='tab_info' border=1 >
				<tr>
					<td>
						<div style='display:table'>
							<div style='display:table-cell;vertical-align: middle;padding: 5px;cursor:pointer' class='open_calendar' >
								<div class='icon_calendar' style='float: left;' ></div>
							</div>
							<div style='display:table-cell;vertical-align: middle;padding-top: 4px;'>
								<input type='text' id='datepicker_form' style='width: 63px;' /> 
							</div>
							<div style='display:table-cell;vertical-align: middle;padding-right: 5px;padding-left: 4px;'>
							   -
							</div>
							<div style='display:table-cell;vertical-align: middle;padding-top: 4px;'>
								<input type='text' id='datepicker_to' style='width: 63px;' />
							</div>
						</div>
					</td>
					<td style='color: white;background: #313131;border: 1px solid white;' >ЗАКАЗЬІ</td>
					<td style='color: white;background: #313131;border: 1px solid white;' >ТОВАРЬІ</td>
					<td style='color: white;background: #313131;border: 1px solid white;' >СУММА грн</td>
					<td style='color: white;background: #313131;border: 1px solid white;' >СУММА руб</td>
					<td style='color: white;background: #313131;border: 1px solid white;border-right: 1px solid #313131;' >СУММА usd</td>
				</tr> 
				<tr>
					<td>НОВЬІЙ ЗАКАЗ</td>
					<td>{$new_oreder_or}</td>
					<td>{$new_oreder_comm}</td>
					<td>{$new_oreder_summ}</td>
					<td>{$new_oreder_summ_rub}</td>
					<td>{$new_oreder_summ_usd}</td>
				</tr>
				<tr>
					<td>ОБРАБАТЬІВАЕТСЯ</td>
					<td>{$obr_or}</td>
					<td>{$obr_comm}</td>
					<td>{$obr_summ}</td>
					<td>{$obr_summ_rub}</td>
					<td>{$obr_summ_usd}</td>
				</tr>
				<tr>
					<td>ПОДТВЕРЖДЕН</td>
					<td>{$podt_or}</td>
					<td>{$podt_comm}</td>
					<td>{$podt_summ}</td>
					<td>{$podt_summ_rub}</td>
					<td>{$podt_summ_usd}</td>
				</tr>
				<tr>
					<td>ОПЛАЧЕН КЛИЕНТОМ</td>
					<td>{$pay_client_or}</td>
					<td>{$pay_client_comm}</td>
					<td>{$pay_client_summ}</td>
					<td>{$pay_client_summ_rub}</td>
					<td>{$pay_client_summ_usd}</td>
				</tr>
				<tr>
					<td>НА СКЛАДЕ</td>
					<td>{$sklad_or}</td>
					<td>{$sklad_comm}</td>
					<td>{$sklad_summ}</td>
					<td>{$sklad_summ_rub}</td>
					<td>{$sklad_summ_usd}</td>
				</tr>
				<tr>
					<td>СОБРАН</td>
					<td>{$sob_or}</td>
					<td>{$sob_comm}</td>
					<td>{$sob_summ}</td>
					<td>{$sob_summ_rub}</td>
					<td>{$sob_summ_usd}</td>
				</tr>
				<tr>
					<td>ОТПРАВЛЕН КЛИЕНТУ</td>
					<td>{$send_client_or}</td>
					<td>{$send_client_comm}</td>
					<td>{$send_client_summ}</td>
					<td>{$send_client_summ_rub}</td>
					<td>{$send_client_summ_usd}</td>
				</tr>
				<tr>
					<td>ЗАКРЬІТЬ</td>
					<td>{$close_or}</td>
					<td>{$close_comm}</td>
					<td>{$close_summ}</td>
					<td>{$close_summ_rub}</td>
					<td>{$close_summ_usd}</td>
				</tr>
				<tr>
					<td style='border-bottom: 1px solid #313131;font-size: 17px;'>ИТОГО:</td>
					<td>{$all_or}</td>
					<td>{$all_comm}</td>
					<td>{$all_summ}</td>
					<td>{$all_summ_rub}</td>
					<td>{$all_summ_usd}</td>
				</tr>
			</table>
		</div></div>
			<br/>
			<div style='position:relative'>
				<div class='search_but' style='display:table;' >
                    <div style='display:table-cell;'>
					   <input type='text' />
                    </div>
                    <div style='display:table-cell;position:relative;'>
                        <select>
                            <option value=1 >по клиентам</option>
                            <option value=2 >по дате</option>
                            <option value=3 >по деталям заказа</option>
                            <option value=4 >по статусу</option>
                            <option value=5 >по ID заказа</option>
                        </select>
                    </div>
                    <div style='display:table-cell;'>
                        <span class='sea_but'>
                            search
                        </span>
                    </div>
				</div>
				<div class='open_search'>ddddd</div>
			</div>
		";
	$center.="<div style='position: relative;height: 28px;'>
						<div style='display:table;position: absolute;right: 2px;' class='but_nn'>
							<div class='{$but_orr1} tab_nn' style='display:table-cell;{$but_style1}' rel=1 >
								<div>
									Заказы
								</div>
							</div>
							<div class='{$but_orr2} tab_nn' style='display:table-cell; {$but_style2}' rel=2 >
								<div>
									Архив
								</div>
							</div>
						</div>
					</div>
					<table style='width: 100%;' class='sortable w_cli'>
						{$all_head}
						{$all_lines}
					</table>
					{$links}
					";	
	
	
//===================Export XLS===============================================	
	if(isset($_GET["exportId"])){
		$id=$_GET["exportId"];
		require_once 'modules/commodities/admin/import_xls.php';
		//echo "<script>window.open('/?admin=all_orders','_self');</script>";
	}
}

?>
