<?
if ($_SESSION['status']=="admin"){

include "soz.php";

	$client_count_all=0;
	$client_count_buy=0;
	$client_count_wait=0;

	$add_price_all=0;
	$add_price_buy=0;
	$add_price_wait=0;	

	$sql=mysql_query("SELECT * FROM `shop_orders` WHERE `status` IN (3,4); ");

	while($row=mysql_fetch_assoc($sql)){

		$id=$row["id"];
		$status=$row["status"];
		$curClient=$row["cur_id"];
		$artt=$row["cod"];
		// $countClient[$id]=0;
		// $summClient[$id]=0;

		// it is auto supplier, put Mysql Table `shop_order_supplier`
		if($status<=2){
			SOZ::autoOrderSupplier($id);
		}

		$sqlSupplier=mysql_query("SELECT * FROM `shop_order_supplier` WHERE `order_id`='{$id}' AND `supp_status` in (3,4,11)");
		while($row2=mysql_fetch_assoc($sqlSupplier)){
			$brendaId=$row2["supplier_name_id"];
			$brande[$brendaId]=$row2["order_id"];

			$codd[$id][$brendaId]=$artt;

			$supplierStatus[$id][$brendaId]=$row2["supp_status"];

			if($supplierStatus[$id][$brendaId]==3 || $supplierStatus[$id][$brendaId]==4 || $supplierStatus[$id][$brendaId]==11){
				$clientOrder[$id][$brendaId]="";
				$countClient[$id][$brendaId]=0;
				$summClient[$id][$brendaId]=0;
				$currClient[$id][$brendaId]='грн';
			}

			$client_count_all++;
			if($supplierStatus[$id][$brendaId]==3){
				$client_count_wait++;
			}elseif($supplierStatus[$id][$brendaId]==4){
				$client_count_buy++;
			}

			$group_id=$id."_".$brendaId;
			$write_deli[$id][$brendaId]=$row2["write_payment"];
			$ssent[$id][$brendaId]=$row2["payment_sent_mail"];
			$cclip[$id][$brendaId]=$row2["payment_clip"];
			$clip_file[$id][$brendaId]=$row2["payment_clip_file"];
			$clip_name[$id][$brendaId]=$row2["payment_clip_file"];

			if($cclip[$id][$brendaId]==1){
				$file2=end(explode(".",$row2["payment_clip_file"])); 
				$urlFile="http://".$_SERVER["HTTP_HOST"]."/uploads/payment_P/".$group_id.".".$file2;
				$ghead=get_headers($urlFile);
				$head=substr($ghead[0], 9, 3);
				

				if($head!="200"){
					$size_kb[$id][$brendaId] = "File is empty";
				}else{
					$size_kb[$id][$brendaId] = filesize("uploads/payment_P/".$group_id.".".$file2);
				}
				$clip_file[$id][$brendaId] = $group_id.".".$file2;
			}

			if(!$brandeContact[$brendaId]){
				$brandeContact[$brendaId]=array();
			}
			array_push($brandeContact[$brendaId], $row2["order_id"]);

			$group_sent[$id][$brendaId]=$row2["sent_email"];

			// ------Select client ID with supplier----------------
			$selected_group1 = "";
			$selected_group2 = "";
			$selected_group3 = "";
			$selected_group4 = "";
			$selected_group5 = "";
			$selected_group6 = "";
			$selected_group7 = "";
			$selected_group8 = "";
			$selected_group10 = "";
			$selected_group11 = "";

			$status_group = $row2["supp_status"];
			$status_groupp[$id][$brendaId] = $row2["supp_status"];
			if($status_group == 1){
				$selected_group1 = "selected";
			} elseif($status_group == 2) {
				$selected_group2 = "selected";
			} elseif($status_group == 3) {
				$selected_group3 = "selected";
			} elseif($status_group == 4) {
				$selected_group4 = "selected";
			} elseif($status_group == 5) {
				$selected_group5 = "selected";
			} elseif($status_group == 6){
				$selected_group6 = "selected";
			} elseif($status_group == 7){
				$selected_group7 = "selected";
			}elseif($status_group == 11){
				$selected_group11 = "selected";
			}

			if($status_group<=4 || $status_group == 11){
				$select_brande[$id][$brendaId]="
					<select size='1' name='status' class = 'select_group_status color_select_group2' rel='{$group_id}'>
						<option value='3'  {$selected_group3}>Оплачен клиентом</option>
						<option value='11'  {$selected_group11}>Готов к оплате</option>
					    <option value='4'  {$selected_group4}>Оплачен поставщику</option>
					</select>";
			}else{
				$select_brande[$id][$brendaId]="
					<select style='color:white;' class = 'select_group_status discolor_select_group2' disabled >
						<option value='3'  {$selected_group3}>Готов к оплате</option>
					    <option value='4'  {$selected_group4}>Оплачен поставщику</option>
					    <option value='6'  {$selected_group6}>Отправлен</option>
					    <option value='7'  {$selected_group7}>Доставлен</option>
					</select>";
			 }

		}

		$sqlCommodity=mysql_query("SELECT * FROM `shop_orders_coms` WHERE `offer_id`='{$id}'; ");
		while($shop=mysql_fetch_assoc($sqlCommodity)){
			$cId=$shop["id"];
			$comId=$shop["com_id"];

			$brandeIdd=SOZ::getBrandeId2($comId);
			$cat_name=SOZ::getCategoryName($comId);
			$art=SOZ::getCommodityOne($comId);

			$count=$shop["count"];
			$price=$shop["price"];
			$size=$shop["com"];
			$cup_id=$shop["cur_id"];
			$comment=$shop['man_comment'];

			$cur=$glb["cur"][$shop["cur_id"]];

			$curSql=mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`={$cup_id}; ");
			$curRes=mysql_fetch_assoc($curSql);
			$select_cup=$curRes["cur_id"];


			$price = round($price/$curRes["cur_val"]);
			if($cup_id==2 || $cup_id==3){
				$cur='грн';
				$currClient[$id][$brendaIdd]='грн';
				$select_cup=1;
			}
			$summ=$price*$count;
			//---------------------------
			$curSql=mysql_query("SELECT * FROM `shop_cur` WHERE 1; ");
      		while($curSqlVal=mysql_fetch_assoc($curSql)){
      			
      			switch ($curSqlVal["cur_id"]) {
					case 1:
						$val1=$curSqlVal["cur_val"];
						$curname1=$curSqlVal["cur_show"];
						break;
					case 2:
						$val2=$curSqlVal["cur_val"];
						$curname2=$curSqlVal["cur_show"];
						break;
					case 3:
						$val3=$curSqlVal["cur_val"];
						$curname3=$curSqlVal["cur_show"];
						break;
				}
      		}

			// ------Price or Opt------------------------
			$skidkiStatus=0;
			$changeRozSelected="";
			$changeOptSelected="";
			if($art["commodity_price"]==$price){
				$changeRozSelected='selected';
				$skidkiStatus=1;
			}
			if($art["commodity_price2"]==$price){
				$changeOptSelected='selected';
				$skidkiStatus=2;
			}

			//-------------Skidki--------------------------
			$skidka_roz=0;
			$skidka_opt=0;
			$supSql=mysql_query("SELECT * FROM `brenda_contact` WHERE `com_id`='{$brandeIdd}';");
			$supRow=mysql_fetch_assoc($supSql);

			$sup_margin_opt = $supRow["uc_opt_skidka"];
			$sup_margin_roz = $supRow["uc_pr_skidka"];

			$skidka_roz=$sup_margin_roz;
			$skidka_opt=$sup_margin_opt;
			$skidka=0;

			if($skidkiStatus==0){
				$price_skidki=$price;
				$summ_skidki=$summ;
			}
			if($skidkiStatus==1){
				$price_skidki=$price-($price/100*$sup_margin_roz);
				$summ_skidki=$summ-($summ/100*$sup_margin_roz);
				$skidka=$sup_margin_roz;
			}
			if($skidkiStatus==2){
				$price_skidki=$price-($price/100*$sup_margin_opt);
				$summ_skidki=$summ-($summ/100*$sup_margin_opt);
				$skidka=$sup_margin_opt;
			}

			//------Color----------------------------------
			if($shop["com_color"] == ""){
				//$color = strip_tags(get_color_to_order($v_comid));
			} else{
				$color = $shop["com_color"];
			}
			//------Select---------------------------
			$status_com = $shop["com_status"];
			$com_selected0[$s_id] = "";
			$com_selected1[$s_id] = "";
			$com_selected2[$s_id] = "";
			$com_selected3[$s_id] = "";

			$countClient[$id][$brandeIdd]+=$count;
			$summClient[$id][$brandeIdd]+=$summ;
			
			if($status_com == 1){
				$com_selected1 = "selected";
			} elseif($status_com == 2){
				$com_selected2 = "selected";
				$countClient[$id][$brandeIdd]-=$count;
				$summClient[$id][$brandeIdd]-=$summ;
			} elseif($status_com == 3){
				$com_selected3 = "selected";
				$countClient[$id][$brandeIdd]-=$count;
				$summClient[$id][$brandeIdd]-=$summ;
			}

			$url_site=$art['from_url'];
			$url_group="/pr{$shop["com_id"]}/";


			$idWindow=$id."_".$brandeIdd;

			if(!$clientOrder[$id][$brandeIdd]){
				$clientOrder[$id][$brandeIdd]="
				<table class ='tab_cat sortable'>
				<tr>
					<th></th>
					<th>Артикул</th>
					<th>Цвет</th>
					<th>Размер</th>
					<th>Кол-во</th>
					<th>
						Валюта
						<select class='change_tab_cup' id='getCup{$idWindow}' rel='{$idWindow}' rel-skidki='{$skidka}'  >
							<option value='1' {$selectCup1} rel-val='{$val1}' rel-name-cur='{$curname1}' >UAH</option>
							<option value='2' {$selectCup2} rel-val='{$val2}' rel-name-cur='{$curname2}' >USD</option>
							<option value='3' {$selectCup3} rel-val='{$val3}' rel-name-cur='{$curname3}' >RUB</option>
						</select>	
					</th>
					<th style='width:125px'>
						Цена
						<select class='change_price_opt' id='getSki{$idWindow}' rel='{$idWindow}' rel-cur='{$select_cup}' rel-real-cur='{$curClient}' >
							<option value=1 {$changeRozSelected} rel-skidki='{$skidka_roz}' >Розница</option>
							<option value=2 {$changeOptSelected} rel-skidki='{$skidka_opt}'>Опт</option>
						</select>		
					</th>
					<th {$hideSkid[$idWindow]} class='closeTdSki{$idWindow}' >Цена со скидкой</th>
					<th>Сумма</th>
					<th {$hideSkid[$$idWindow]} class='closeTdSki{$idWindow}' >Сумма со скидкой</th>
					<th>Ссылка</th>
					<th>Статус</th>
					<th>Комментарий</th>
				</tr>

				";
			}
			$clientOrder[$id][$brandeIdd].="
			<tr id='{$cId}' >
				<td>
					<input type=\"checkbox\" class=\"cl_trt\" rel=\"{$cId}\" rel-id=\"{$comId}\">
				</td>
				<td>{$art['cod']}</td>
				<td>{$color}</td>
				<td>{$size}</td>
				<td>{$count}</td>
				<td  rel-real-price={$price}>{$cur}</td>
				<td>{$price}</td>
				<td {$hideSkid} class='closeTdSki{$idWindow}' >{$price_skidki}</td>
				<td>{$summ}</td>
				<td {$hideSkid} class='closeTdSki{$idWindow}' >{$summ_skidki}</td>
				<td><a href ='{$url_site}'>Источник</a></td>
				<td>
					<select size='1' name='status' id = 'select_status_com' rel = '{$row['id']}' disabled>
						<option value='0' {selected0}></option>
						<option value='1' rel='{$row2['id']};1' {$com_selected1}>Есть в наличии</option>
		    			<option value='2' rel='{$row2['id']};2' {$com_selected2}>Нет в наличии</option>
		    			<option value='3' rel='{$row2['id']};3'{$com_selected3}>Замена</option>
		    			{$addOption}
					</select>
				</td></td>
				<td>{$comment}</td>
			</tr>";
		}

	}
// var_dump($clientOrder);

	if($clientOrder)
	foreach ($clientOrder as $key => $val) {

		foreach ($clientOrder[$key] as $jkey => $jval) {

			$supSql=mysql_query("SELECT * FROM `brenda_contact` WHERE `com_id`='{$jkey}';");
			$supRow=mysql_fetch_assoc($supSql);

			$name=$supRow["rek_pa_name"];

			$names="";
			if($name){
				$name_a=explode(" ", $name);	
						
				$names=mb_ucfirst($name_a[0], "utf-8");
				if($name_a[1]){
					$names.=" ".mb_ucfirst(substr($name_a[1],0,2), "utf-8").".";
				}
				if($name_a[2]){
					$names.=mb_ucfirst(substr($name_a[2],0,2), "utf-8").".";	
				}
			}
			
			$bank=$supRow["rek_pa_bank"];
			$fl=$supRow["rek_pa_plat"];
			$chet=$supRow["rek_pa_shet"];
			$tel=$supRow["cont_phone"];
			$tel=str_replace(";",",",$tel);
			$tel=substr($tel,2,strlen($tel));
			$emaill=$supRow["cont_mail"];
			$emaill=str_replace(";",",",$emaill);
			$emaill=substr($emaill,2,strlen($emaill));
			$prim=$supRow["rek_pa_dop"];

			$k=$key."_".$jkey;

			if($ssent[$key][$jkey]==0){
				$signal_sent="<div class='icon_send'></div>";
			}	elseif($ssent[$key][$jkey]==1){
			 	$signal_sent="<div class='icon_sent'></div>";	
			}	
			
			if($cclip[$key][$jkey]==0) {
				$clip="<div class='icon_clip_will' style='cursor:pointer;' rel='{$k}'></div>";
			}elseif($cclip[$key][$jkey]==1) {
				$clip="<div class='icon_clip_was icw{$k}' style='cursor:pointer;' rel='{$k}' rel-file='{$clip_file[$key][$jkey]}' rel-size='{$size_kb[$key][$jkey]}' rel-name='{$clip_name[$key][$jkey]}' ></div>";	
			}

			$resCat=mysql_query("SELECT `categories_of_commodities_ID`,`cat_name` FROM `shop_categories` WHERE `categories_of_commodities_ID`='{$jkey}'; ");
			$rowCat=mysql_fetch_assoc($resCat);
			if($supplierStatus[$key][$jkey]==3 || $supplierStatus[$key][$jkey]==4 || $supplierStatus[$key][$jkey]==11){

				$add_price_all+=$summClient[$key][$jkey];
				if($supplierStatus[$key][$jkey]==3){
					$add_price_wait+=$summClient[$key][$jkey];
				}elseif($supplierStatus[$key][$jkey]==4){
					$add_price_buy+=$summClient[$key][$jkey];
				}

				$add_line.="<tr class='{$but_active[$k]} tab_up' style='{$active[$k]}' id='gh{$k}'>
					<td style='border-bottom: 0px solid;'></td>
					<td style='border-bottom: 0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
							<div style='display:table;'>
								<div style='display:table-cell;padding-right: 5px;'>
									<span class='but_open_win' rel='{$k}' style='cursor:pointer'>
										<div class='block_down' id='bb{$k}' ></div>
									</span>
								</div>
								<div style='display:table-cell;'>
									{$key}
								</div>
							</div>
						</div></div>
					</td><td style='border-bottom: 0px solid;'>
						<div class='hdiv'><div class='hdiv2'>

						</div></div>
					</td><td style='border-bottom: 0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
							{$rowCat["cat_name"]}
						</div></div>
					</td><td style='border-bottom: 0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
							{$select_brande[$key][$jkey]}
				    	</div></div>
					</td><td style='border-bottom: 0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
							{$countClient[$key][$jkey]}
						</div></div>
					</td><td style='border-bottom: 0px solid;'>
						<div class='hdiv'><div class='hdiv2' id='addPrice'>
							{$summClient[$key][$jkey]} {$currClient[$key][$jkey]}
						</div></div>
					</td><td style='border-bottom: 0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
							
									<div class='wind_o open_backg' rel='{$k}' style='cursor:pointer;display:table;position: absolute;margin-top: -9px;'>
										<div style='display:table-cell;padding-left: 5px;padding-bottom: 9px;' rel-real-name='$name'>
											{$names}
										</div>
										<div style='display:table-cell;padding-left:3px;padding-right: 5px;'>
											<div class='block_down bc{$k}'></div>
										</div>
									</div>
									<div class='wind_names' id='open_win{$k}' style='display:none;max-height: 500px;'>
										<table>
											<tr><td></td><td></td></tr>
											<tr><td>Телефон:</td><td>{$tel}</td></tr>
											<tr><td></td><td></td></tr>
											<tr><td>E-mail:</td><td>{$emaill}</td></tr>
											<tr><td></td><td></td></tr>
											<tr><td>Примечание:</td><td class='getpri{$k}'>{$prim}</td></tr>
											<tr><td></td><td></td></tr>
										</table>
									</div>
							
						</div></div>
					</td><td style='border-bottom: 0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
								{$bank}
						</div></div>
					</td><td style='border-bottom: 0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
								{$fl}
						</div></div>
					</td><td style='border-bottom: 0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
							<div class='get_shet{$k}' >
								{$chet}
							</div>
						</div></div>
					</td><td style='border-bottom: 0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
							<div style='display:table;margin-top: -4px;margin-bottom: -4px;margin-right: 10px;'>
								<div class='send_ac{$k}' style='display:table-cell;padding-right: 4px;vertical-align: middle;' >
									<span class='maill sentt$k' rel='{$k}' rel2='{$emaill}' art='{$codd[$key][$jkey]}' rel-cat='{$catt[$k]}' >
										{$signal_sent}
									</span>
								</div>

								<div class='change_clip{$k}'   style='display:table-cell;padding-right: 4px;vertical-align: middle;' >
									{$clip}
								</div>
								<div  style='display:table-cell;'  >
									<div class='wind_o2 open_backg' rel='{$k}' style='display:table;'>
										<div style='display:table-cell'>
											<div class='icon_info_white iiw{$k}'></div>
										</div>
										<div style='display:table-cell;vertical-align: middle;padding-left: 2px;'>
											<div class='block_down bbc{$k}' ></div>
										</div>
									</div>	
										
									<div class='wind_names' id='open_win2{$k}' style='display:none;right:16px;margin-top:0px;width: 300px;'>
										<table>
											<tr><td></td></tr>
											<tr>
												<td style='font-weight:100;text-align: left;width:300px;height: 90px;' contenteditable='true' class='write_tab' rel='{$k}'>
												{$write_deli[$key][$jkey]}
												</td>
											</tr>
											<tr><td></td></tr>
										</table>
									</div>
									
								</div>
							</div>
						</div></div>
					</td>
			</tr>
			<tr>
				<td style='border-bottom: 0px solid #CCC;'></td>
				<td colspan=11 style='padding-top: 0px;border-bottom: 0px solid #CCC;' >
				<div class='open_line open_commodity{$k}' style='display:none;' >
					{$clientOrder[$key][$jkey]}
					</table>
			 	</div>
			 	</td>
			</tr>";
			}
		}
	}

	$center.="
		<script src='/templates/admin/js/i18n/datepicker-uk.js' type='text/javascript'></script>
		<script src='/templates/admin/js/i18n/datepicker-ru.js' type='text/javascript'></script>	
		<script src='/templates/admin/soz/js/orders_to_sup20.js'></script>
		<link href='/templates/admin/soz/style/order20.css' type='text/css' rel='stylesheet' />
		<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css\">
			";
	$center.="<br/>
		<div class='table_line2_gr' >
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
					<td style='color: white;background: #313131;border: 1px solid white;' >К ОПЛАТЕ</td>
					<td style='color: white;background: #313131;border: 1px solid white;' >ОПЛАЧЕНО</td>
					<td style='color: white;background: #313131;border: 1px solid white;border-right: 1px solid #313131;' >ОЖИДАЕТСЯ</td>
				</tr>
				<tr>
					<td>ПОСТУПИЛО ЗАКАЗОВ</td>
					<td>{$client_count_all}</td>
					<td>{$client_count_buy}</td>
					<td>{$client_count_wait}</td>
				</tr>
				<tr>
					<td style='border-bottom: 1px solid #313131;'>
						СУММА ПЛАТЕЖА
						<select id='selectCur'>
							<option value=1 >UAH</option>
							<option value=2 >USD</option>
							<option value=3 >RUB</option>
						<select>
					</td>
					<td class='changeCur' rel-uah='{$add_price_all}' rel-usd='{$add_price_allUsd}' rel-rub='{$add_price_allRub}' >{$add_price_all}</td>
					<td class='changeCur' rel-uah='{$add_price_buy}' rel-usd='{$add_price_buyUsd}' rel-rub='{$add_price_buyRub}' >{$add_price_buy}</td>
					<td class='changeCur' rel-uah='{$add_price_wait}' rel-usd='{$add_price_waitUsd}' rel-rub='{$add_price_waitRub}' >{$add_price_wait}</td>
				</tr>
			</table>
		</div></div>
		<div class='left_icon' >
			<div class='tab_td2'>
				<div class='back_icon'>
		 			<div class='icon_calc' style='cursor:pointer;' ></div>
		 		</div>
			</div>		 	
		</div>
			<br/>
		";	
	$center.="
		<link rel='stylesheet' href='/modules/calc/calc.css'>
		<script type='text/javascript' src='/modules/calc/calc.js'></script>
		<div class='calc' style='display:none;width: 186px;'>
			<div class='mousee'>
				<span class='icon_red' title='Закрить'></span>
			</div>
			<div class='read_calc'>0</div>
			<table class='but_calc'>
				<tr><td rel='7'><div>7</div></td><td rel='8'><div>8</div></td><td rel='9'><div>9</div></td><td rel='+'><div>+</div></td></tr>
				<tr><td rel='4'><div>4</div></td><td rel='5'><div>5</div></td><td rel='6'><div>6</div></td><td rel='-'><div>-</div></td></tr>
				<tr><td rel='1'><div>1</div></td><td rel='2'><div>2</div></td><td rel='3'><div>3</div></td><td rel='/'><div>/</div></td></tr>
				<tr><td rel='0' colspan='2' ><div style='width: 86px;''>0</div></td><td rel='.'><div>.</div></td><td rel='*'><div>*</div></td></tr>
				<tr><td rel='C'><div>C</div></td><td rel='CE'><div>CE</div></td><td colspan='2' rel='='><div style='width: 86px;font-size: 28px;'>=</div></td></tr>
			</table>
		</div>";
	$center.="
		<div class='body_upload' style='display:none'>
			<div class='bbb2'>
			<div style='overflow-y: scroll;height: 100%;'>
				<div class='wind_upload' style='display:block'>
				
						<div class='close_upload icon_close' ></div>
						<div>ПОДТВЕРЖДЕНИЕ ОПЛАТЫ</div>
						<div class='names'>Заказ №<span id='get_upload_order'></span>; Сумма<span id='get_upload_price' ></span></div>	
							<div id='uupp' >
								<div>
									ЗАГРУЗИТЬ ДОКУМЕНТ
								</div>
								 <input type='file' name='file' class='upload' />
							</div>				
						<div>
							Загрузите скан-копию документа, подтверждающего осуществление оплаты.<br/>
							Допустимые форматы: JPG; PNG; PDF
						</div>
					
				</div>
				<div class='show_file' style='display:none' >
					<div class='close_upload icon_close' ></div>
					<div class='see_file'>
						
					</div>
					
					<div style='display:table;width: 100%;'>	
						<div style='display:table-cell;width: 10px;' >				
							<div class='any_file'>
								<div class='icon_file_upload' ></div>
								Загрузить другой файл
								<input type='file' name='file' class='upload' />
							</div>
							<div class='delete_file'  >
								<i class=\"fa fa-times\" aria-hidden=\"true\"></i>Удалить
							</div>
						</div>
						<!--<div style='display:table-cell;text-align: left;' >
							<div id='singal_upload'>OK</div>
						</div>-->
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
	$center.="<div id='pay_body' style='display: none'>
					<div id='pay_mail'>
						<div class='topDiv' >
							<div class='icon_close pay_close' ></div>
						</div>
						<label>Кому: </label><input type='text' name='' class='in_text' id='from_to' /><br/>
						<label>От кого: </label><input type='text' value='sales@makewear.com.ua' name='' class='in_text' id='from_whom' /><br/>
						<label>Тема: </label><input type='text' name='' class='in_text' id='from_sub' /><br/>
						
						<div class='html_mail' >
						</div>
						<div style='position: relative;height: 50px;'>
							<div class='send but_send'>Отправить</div>
							<button class='pay_close2 but_close'>Отмена</button>
						</div>
					</div>
				</div>";
	$center.="
			<br/>
			<div style='position: relative;height: 28px;'>
				<div style='display:table;position: absolute;right: 2px;' class='but_nn'>
					<div class='{$but_orr1} tab_nn' style='display:table-cell;{$but_style1}' rel=1 >
						<div>
							Оплата-Поставщики
						</div>
					</div>
					<div class='{$but_orr2} tab_nn' style='display:table-cell;{$but_style2}' rel=2 >
						<div>
							Архив
						</div>
					</div>
				</div>
			</div>			
			<table class='sortable order_to_sup'>
				<th style='width: 1px;' ></th>
				<th>№</th>
				<th>Дата</th>
				<th>Бренд</th>
				<th>Статус</th>
				<th>Единиц</th>
				<th>Сумма</th>
				<th style='width: 15%;'>Контрагент</th>
				<th>Банк</th>
				<th>Вид платежа</th>
				<th>№ счета/карт</th>
				<th style='width:88px;'></th>
				{$add_line}
		</table>{$links}";

}

