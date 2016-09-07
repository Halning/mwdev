<?php
if ($_SESSION['status']=="admin")
{

include "soz.php";

	$links=SOZ::setLinks("(1,2,3)","(10)");

	$sql=mysql_query("SELECT * FROM `shop_orders` WHERE {$links['status']}");

	while($row=mysql_fetch_assoc($sql)){

		$id=$row["id"];
		$status=$row["status"];
		$curClient=$row["cur_id"];
		// $countClient[$id]=0;
		// $summClient[$id]=0;

		// it is auto supplier, put Mysql Table `shop_order_supplier`
		if($status<=2){
			SOZ::autoOrderSupplier($id);
		}

		$sqlSupplier=mysql_query("SELECT * FROM `shop_order_supplier` WHERE `order_id`='{$id}'");
		while($row2=mysql_fetch_assoc($sqlSupplier)){
			$brendaId=$row2["supplier_name_id"];
			$brande[$brendaId]=$row2["order_id"];

			$clientOrder[$id][$brendaId]="";
			$countClient[$id][$brendaId]=0;
			$summClient[$id][$brendaId]=0;
			$currClient[$id][$brendaId]='грн';

			if(!$brandeContact[$brendaId]){
				$brandeContact[$brendaId]=array();
			}
			array_push($brandeContact[$brendaId], $row2["order_id"]);

			$group_sent[$id][$brendaId]=$row2["sent_email"];

			// ------Select client ID with supplier----------------
			$status_group=$row2["supp_status"];
			$arr_status_group[$id][$brendaId]=$row2["supp_status"];

			if($status_group == 1){
				$selected_group1[$id][$brendaId] = "selected";
			} elseif($status_group == 2) {
				$selected_group2[$id][$brendaId] = "selected";
			} elseif($status_group == 3) {
				$selected_group3[$id][$brendaId] = "selected";
			} elseif($status_group == 4) {
				$selected_group4[$id][$brendaId] = "selected";
			} elseif($status_group == 5) {
				$selected_group5[$id][$brendaId] = "selected";
			} elseif($status_group == 6){
				$selected_group6[$id][$brendaId] = "selected";
			}elseif($status_group == 10){
				$selected_group10[$id][$brendaId] = "selected";
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
			$man_comment=$shop['man_comment'];

			$cur=$glb["cur"][$shop["cur_id"]];

			$curSql=mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`={$cup_id}; ");
			$curRes=mysql_fetch_assoc($curSql);

			$price = round($price/$curRes["cur_val"]);
			if($cup_id==2 || $cup_id==3){
				$cur='грн';
				$currClient[$id][$brendaIdd]='грн';
			}
			$summ=$price*$count;


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


			$changeRozSelected="";
			$changeOptSelected="";
			if($art["commodity_price"]==$price){
				$changeRozSelected='selected';
			}
			if($art["commodity_price2"]==$price){
				$changeOptSelected='selected';
			}

			$idWindow=$id."_".$brandeIdd;
			if(!$clientOrder[$id][$brandeIdd]){
				$clientOrder[$id][$brandeIdd]="<div class='open_line open_commodity{$idWindow}' style='display:none;' >
					<table class = 'sortable tab_brenda' id='but22_tab{$idWindow}' >
						<th>
							<input type=\"checkbox\" class=\"all_change\" rel=\"{$idWindow}\">
						</th>
						<th>Товар</th>
						<th>Артикул</th>
						<th>Цвет</th>
						<th>Размер</th>
						<th>Кол-во</th>
						<th>Валюта</th>
						<th>
							Цена
							<select class=\"change_price_opt\" rel=\"{$idWindow}\" rel-cur=\"{$curClient}\">
								<option value=\"1\" {$changeRozSelected}>Розница</option>
								<option value=\"2\" {$changeOptSelected}>Опт</option>
							</select>
						</th>
						<th>Сумма</th>
						<th>Ссылка на товар</th>
						<th>Источник</th>
						<th>Комментарий</th>
						<th>Статус</th>";
			}
			$clientOrder[$id][$brandeIdd].="
				<tr class = 'group_td gr_tab' id='{$cId}' rel='shop_orders_coms' rel2='id' rel-url='{$from_url}' rel-nameurl='{$com_name}' >
					<td class = 'group_td'>
					<input type='checkbox' class='c2_trt' rel='{$cId}' rel-id='{$comId}' style='margin-bottom: 0px;' /></td>
					<td class = 'group_td'>{$cat_name}</td>
					<td class = 'group_td'>{$art['cod']}</td>
					<td class = 'group_td'>{$color}</td>
					<td class = 'group_td' style='text-align:center'>{$size}</td>
					<td class = 'group_td' style='text-align:center'>{$count}</td>
					<td class = 'group_td' style='text-align:center'>{$cur}</td>
					<td class = 'group_td' style='text-align:center'>{$price}</td>
					<td class = 'group_td' style='text-align:center' rel-opt-roz='{$price_or_opt[$cId]}' >{$summ}</td>
					<td class = 'group_td'><a href ='{$url_group}' target='_blank'>{$url_group}</a></td>
					<td class = 'group_td'><a href ='{$url_site}' target='_blank'>Источник</a></td>
					<td id = 'man_comment' class='cl_edit'>{$man_comment}</td>
					<td><select size='1' name='status' id = 'select_status_com' class = 'forClient changeCommodity' rel = '{$cId}' rel-client='{$order_id[$cId]}' rel-group='{$idWindow}' >
						<option value='0' {$com_selected0}></option>
						<option value='1' {$com_selected1}>Есть в наличии</option>
					    <option value='2' {$com_selected2}>Нет в наличии</option>
					    <option value='3' {$com_selected3}>Замена</option>
					    </select>
					</td>
				</tr>";

		}

	}


	if($brandeContact){
		foreach ($brandeContact as $k => $v) {
			$bbb=mysql_query("SELECT * FROM `brenda_contact` WHERE `com_id`='{$k}';");
			$b=mysql_fetch_assoc($bbb);
			$name="";
			if($b["cont_name"]){
				$name_a=explode(";", $b["cont_name"]);
				for($i=0; $i<count($name_a); $i++){
					if($i==1){
						$name.="<span class='border_gray'>{$name_a[$i]}</span>";
					}
					if($i>1){
						$phone.="<br/><span class='border_gray' style='margin-left: 67px;'>{$name_a[$i]}</span>";
					}
				}
			}
			$phone="";
			if($b["cont_phone"]){
				$phone_a=explode(";", $b["cont_phone"]);
				for($i=0; $i<count($phone_a); $i++){
					if($i==1){
						$phone.="<span class='border_gray'>{$phone_a[$i]}</span>";
					}
					if($i>1){
						$phone.="<br/><span class='border_gray' style='margin-left: 67px;'>{$phone_a[$i]}</span>";
					}
				}
			}
			$email="";
			if($b["cont_mail"]){
				$email_a=explode(";", $b["cont_mail"]);
				for($i=0; $i<count($email_a); $i++){
					if($i==1){
						$email.="<span class='border_gray'>{$email_a[$i]}</span>";
					}
					if($i>1){
						$phone.="<br/><span class='border_gray' style='margin-left: 67px;'>{$email_a[$i]}</span>";
					}
				}
			}


		if($b['rek_pa_plat'])
			$rek_plat=$b['rek_pa_plat'];
		else
			$rek_plat=" --- ";
		$payment_plat="<span class='border_gray2' style='width:28%;' >".$rek_plat."</span>";
		
		if($b['rek_pa_name'])
			$rek_name=$b['rek_pa_name'];
		else
			$rek_name=" --- ";
		$payment_name="<span class='border_gray2' style='width:28%;' >".$rek_name."</span>";
		
		if($b['rek_pa_bank'])
			$rek_bank=$b['rek_pa_bank'];
		else
			$rek_bank=" --- ";
		$payment_bank="<span class='border_gray2' style='width:28%;'  >".$rek_bank."</span>";
		
		if($b['rek_pa_shet'])
			$rek_shet=$b['rek_pa_shet'];
		else
			$rek_shet=" --- ";
		$payment_shet="<span class='border_gray2' style='width:28%;'  >".$rek_shet."</span>";
		
		if($b['rek_pa_dop'])
			$rek_dop=$b['rek_pa_dop'];
		else
			$rek_dop=" --- ";
		$payment_dop="<span class='border_gray2' style='width:28%;' >".$rek_dop."</span>";
	

		if($b['rek_de_sity'])
			$rek_de_sity=$b['rek_de_sity'];
		else
			$rek_de_sity=" --- ";
		$delivery_sity="<span class='border_gray2' style='width:29%;'  >".$rek_de_sity."</span>";
		
		if($b['rek_de_sposib'])
			$rek_de_sposib=$b['rek_de_sposib'];
		else
			$rek_de_sposib=" --- ";
		$delivery_sposib="<span class='border_gray2' style='width:29%;' >".$rek_de_sposib."</span>";
		
		if($b['rek_de_address'])
			$rek_de_address=$b['rek_de_address'];
		else
			$rek_de_address=" --- ";
		$delivery_add="<span class='border_gray2' style='width:29%;' >".$rek_de_address."</span>";
		
		if($b['rek_de_get'])
			$rek_de_get=$b['rek_de_get'];
		else
			$rek_de_get=" --- ";
		$delivery_get="<span class='border_gray2' style='width:29%;' >".$rek_de_get."</span>";
		
		if($b['rek_de_dop'])
			$rek_de_dop=$b['rek_de_dop'];
		else
			$rek_de_dop=" --- ";
		$delivery_dop="<span class='border_gray2' style='width:29%;' >".$rek_de_dop."</span>";

		$ret_b[$k]="<div class='tab'>
					<div class='tab_td' style='width:77px'>
						<div class='br_name br_div' style='margin: 4px;margin-right: 3px; width: 154px;'>
							<a href='{$b['bc_site']}' target='_blank'>
								<img src='http://makewear.com.ua/images/categories/{$k}/main.jpg' />
							</a>
						</div>
					</div>
					<div class='tab_td' style='width:21%'>
						<div class='open_windows1' id='ow1_{$k}' style='margin-top: 2px;'>
							<div class='tin' style='display:none;' ></div>
							<div class='br_cont br_div' id='window_cont{$k}' style='padding-top: 1px;'>
								<div class='div_center'>
									<span class='border_blue' >ИМЯ</span>{$name}<br/>
									<span class='border_blue' >ТЕЛЕФОН</span>$phone<br/>
									<span class='border_blue' >ПОЧТА</span>$email<br/>
								</div>
							</div>
							<center>
								<span class='open_cont bu1' style='cursor:pointer;' rel='{$k}'>
									<div class='block_down1' id='bo1_{$k}' style='margin: 5px;'></div>
								</span>
							</center>
						</div>
					</div>
					<div class='tab_td' style='width:26%'>
						<div class='open_windows2' id='ow2_{$k}' style='margin-top: 4px; margin-left:0px;' >
							<div class='tin' style='display:none;' ></div>	
							<div class='br_us br_div div_center' id='window_us{$k}' style='padding-top: 1px;'>						
								<span class='border_blue' style='text-align:center;margin-left: 33%;' ><b>ОПТ</b></span><span class='border_blue' style='text-align:center;margin-left: 6%;' ><b>РОЗНИЦА</b></span><br/>
								<span class='border_blue' >СКИДКА</span><span class='border_gray' style='width: 30%;' id='getSkidkaOpt{$k}'>{$b["uc_opt_skidka"]} %</span><span class='border_gray' style='width: 30%;' id='getSkidkaRoz{$k}'>{$b["uc_pr_skidka"]} %</span><br/>
								<span class='border_blue' >НАЦЕНКА</span><span class='border_gray' style='width: 30%;'>{$b["uc_opt_natsenka"]}</span><span class='border_gray' style='width: 30%;'>{$b["uc_pr_natsenka"]}</span><br/>
								<span class='border_blue' >ОТГРУЗКА</span><span class='border_gray' style='width: 30%;'>{$b["uc_opt_otgruz"]} мин/ед</span><span class='border_gray' style='width: 30%;'>{$b["uc_pr_otgruz"]} мин/ед</span><br/>
								<span class='border_blue' >ДОСТАВКА</span><span class='border_gray' style='width: 30%;'>{$b["uc_opt_delivery"]}</span><span class='border_gray' style='width: 30%;'>{$b["uc_pr_delivery"]}</span><br/>
								<span class='border_blue' >ЦЕНА</span><span class='border_gray' style='width: 30%;'>{$b["uc_opt_price"]}</span><span class='border_gray' style='width: 30%;'>{$b["uc_pr_price"]}</span>
							</div>
							<center>
								<span class='open_us bu1' style='cursor:pointer;' rel='{$k}'>
									<div class='block_down1' id='bo2_{$k}' style='margin: 5px;'></div>
								</span>
							</center>
						</div>
					</div>
					<div class='tab_td' style='width:39%'>
						<div class='open_windows3' id='ow3_{$k}' style='margin-top: 4px; margin-left: 0px;'>
							<div class='tin' style='display:none;' ></div>
							<div class='br_rez br_div div_center' id='window_rez{$k}' style='padding-top: 1px;'>							
								<span class='border_blue' style='text-align:center;margin-left: 95px;' ><b>ОПЛАТА</b></span><span class='border_blue' style='text-align:center;margin-left: 31%;' ><b>ДОСТАВКА</b></span><br/>
								<span class='border_blue' >ПЛАТЕЖ</span>{$payment_plat}<span class='border_blue' >ГОРОД</span>{$delivery_sity}<br/>
								<span class='border_blue' >Ф.И.О.</span>{$payment_name}<span class='border_blue' >СПОСОБ</span>{$delivery_sposib}<br/>
								<span class='border_blue' >БАНК</span>{$payment_bank}<span class='border_blue' >№СКЛАДА</span>{$delivery_add}<br/>
								<span class='border_blue' >№СЧЕТА</span>{$payment_shet}<span class='border_blue' >ПОЛУЧАТЕЛЬ</span>{$delivery_get}<br/>
								<span class='border_blue' >ПРИЧЕМ.</span>{$payment_dop}<span class='border_blue' >ПРИЧЕМ.</span>{$delivery_dop}	
													
							</div>
							<center>
								<span class='open_rez bu1' style='cursor:pointer;' rel='{$k}'>
									<div class='block_down1' id='bo3_{$k}' style='margin: 5px;'></div>
								</span>
							</center>
						</div>
					</div>
				</div>";
			for($i=0; $i<count($brandeContact[$k]); $i++){
				//$ret_b[$k].=$brandeContact[$k][$i].", ";
				$clientID=$brandeContact[$k][$i];
				$idWindow=$clientID."_".$k;


				if($arr_status_group[$clientID][$k] < 3){
					$brand_select='
					<select size="1" name="status" class="select_group_status color_select_group auto_set'.$idWindow.'" rel="'.$idWindow.'" style="margin-top: 4px;margin-bottom: 4px;">
							<option value="1" '.$selected_group1[$clientID][$k].'>Новый заказ</option>
							<option value="2" '.$selected_group2[$clientID][$k].'>Обрабатывается</option>
							<option value="3" '.$selected_group3[$clientID][$k].'>Подтвержден</option>
							<option value="10" '.$selected_group10[$clientID][$k].'>Отменен</option>
		    		</select>
					';
				}else{
					$brand_select='
					<select style="margin-top: 4px;margin-bottom: 4px;color: white;" class="discolor_select_group" disabled >
							<option value="3" '.$selected_group3[$clientID][$k].'>Готов к оплате</option>
							<option value="4" '.$selected_group4[$clientID][$k].'>Оплачен поставщику</option>
							<option value="5" '.$selected_group5[$clientID][$k].'>Отправлен</option>
							<option value="6" '.$selected_group6[$clientID][$k].'>Доставлен</option>
		    		</select>
					';
				}

				if($group_sent[$clientID][$k]==0){
					$sendd="<div style='margin-top: 3px;'>
								<span class='send_mail sentt{$idWindow}' rel='{$idWindow}' rel2={$k} style='cursor:pointer'>
									<div class='icon_send'></div>
									отправить
								</span>
							</div>";
				}elseif($group_sent[$clientID][$k]==1){
					$sendd="<div style='margin-top: 3px;'>
								<span class='send_mail sentt{$idWindow}' rel='{$idWindow}' rel2={$k} style='cursor:pointer'>
									<div class='icon_sent'></div>
									<span style='text-decoration:underline'>отправлен</span>
								</span>
							</div>";
				}
				$ret_b[$k].="
					<div class='group_head tab_up {$but_active[$clientID]}' id='gh{$idWindow}'  >
						<div class='tab_td2' style='padding: 9px;width: 9px;'>
							<span class='but_open_win' rel='{$idWindow}'>
								<div class='block_down' id='bb{$idWindow}' ></div>
							</span>
						</div>
						<div class='tab_td2'>Заказ №{$clientID}</div>
						<div class='tab_td2'>
							{$brand_select}
						</div>
						<div class='tab_td2'>
							Единиц: <span id='gr_set_count{$clientID}'>{$countClient[$clientID][$k]}</span>
						</div>
						<div class='tab_td2'>
							Сумма:  <span id='gr_set_summ{$idWindow}'>{$summClient[$clientID][$k]}</span> {$currClient[$clientID][$k]}
						</div>
						<div class='tab_td2'>
							
						</div>
						<div class='tab_td2'>
							{$gr_date[$clientID]}
						</div>
						<div class='tab_td2 under_line'>
							{$sendd}
						</div>
					</div>";
				$ret_b[$k].=$clientOrder[$clientID][$k]."</table></div>";
				//$ret_b[$k].=$clientOrder[$clientID][$k];
			}
		}
	}

	// --------- Include JS and CSS-------------
	$center.="
		<link href='/templates/admin/soz/style/orders_brands20.css' type='text/css' rel='stylesheet' />
		<script src='/templates/admin/soz/js/orders_brands21.js' ></script>	
	";

	// ---------Mail-------------
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
							<input type='text' class='tab_send_subject' value='' />
						</td>
					</tr>
				</table>
				<hr width='100%' />
					<div class='sent_html'>
						Body
					</div>
				<hr width='100%' id='bottom_hr' />
				<div>
					<button class='close_window'>Отмена</button>
					<span class='sent_order'> Отправить</span>	
				</div>
			</div>
		</div>
		";

	// ------- Print site SOZ-------
	$center.="<div class='rees'></div><br/><br/>
			<div style='position: relative;height: 28px;'>
				<div style='display:table;position: absolute;right: 2px;' class='but_nn'>
					<div class='{$links['but_orr1']} tab_nn' style='display:table-cell;{$links['but_style1']}' rel=1 >
						<div>
							Заказы по брендам
						</div>
					</div>
					<div class='{$links['but_orr2']} tab_nn' style='display:table-cell;{$links['but_style2']}' rel=2 >
						<div>
							Архив
						</div>
					</div>
				</div>
			</div>
			<table class='sortable w_brands'>
				<th style='width:8px;'></th>
				<th style='width:15%;text-align:center;'>Бренд</th>
				<th style='width:20%;text-align:center;'>Контакты</th>
				<th style='width:25%;text-align:center;'>Условия</th>
				<th style='width:40%;text-align:center;'>Резвизиты</th>
		";

	if($brande){
		ksort($brande);

		foreach ($brande as $key => $val) {
			$center.="<tr>
						<td></td>
						<td colspan=4 >".$ret_b[$key]."</td>
					</tr>";
		}
	}
	$center.="</table>".$links['links'];
}
?>
