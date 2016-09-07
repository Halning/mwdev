<?
if ($_SESSION['status']=="admin"){

	$sea=$_GET['search'];
	$ssea="";
	if($sea){
		$ssea="AND `cat_name` LIKE '%{$sea}%' ";
	}else{
		$ssea="";
	}
	$bbb=mysql_query("SELECT * FROM `shop_categories` AS a
					LEFT JOIN `brenda_contact` AS b
					ON a.`categories_of_commodities_ID`=b.`com_id`
					WHERE `categories_of_commodities_parrent` =10 {$ssea}");
	while($b=mysql_fetch_assoc($bbb)){
		$cat_id=$b['categories_of_commodities_ID'];
		$ppp=mysql_query("SELECT * FROM `parser_interface` AS a INNER JOIN `parser` AS b ON a.`par_id`=b.`id` WHERE `cat_id`='{$cat_id}'; ")or die(mysql_error());
		$p=mysql_fetch_assoc($ppp);
			$par_start_time[$b['categories_of_commodities_ID']]=$p['start_time'];
			$par_end_time[$b['categories_of_commodities_ID']]=$p['end_time'];
			$par_time_darution[$b['categories_of_commodities_ID']]=$p['time_duration'];
			$par_hide[$b['categories_of_commodities_ID']]=$p['par_hide'];
			$check_prog[$b['categories_of_commodities_ID']]=$p['check_prog'];
			$update_add[$b['categories_of_commodities_ID']]=$p['update_add'];
			$add_new_com[$b['categories_of_commodities_ID']]=$p['add_new_com'];

		$brend[$b['categories_of_commodities_ID']]=$b['cat_name'];
		$site[$b['categories_of_commodities_ID']]=$b['bc_site'];

		$name[$b['categories_of_commodities_ID']]=$b['cont_name'];
		$phone[$b['categories_of_commodities_ID']]=$b['cont_phone'];
		$mail[$b['categories_of_commodities_ID']]=$b['cont_mail'];

		if($b['rek_pa_plat'])
			$rek_plat=$b['rek_pa_plat'];
		else
			$rek_plat=" --- ";
		$payment_plat[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 24%;' >".$rek_plat."</span>";
		
		if($b['rek_pa_name'])
			$rek_name=$b['rek_pa_name'];
		else
			$rek_name=" --- ";
		$payment_name[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 24%;' >".$rek_name."</span>";
		
		if($b['rek_pa_bank'])
			$rek_bank=$b['rek_pa_bank'];
		else
			$rek_bank=" --- ";
		$payment_bank[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 24%;' >".$rek_bank."</span>";
		
		if($b['rek_pa_shet'])
			$rek_shet=$b['rek_pa_shet'];
		else
			$rek_shet=" --- ";
		$payment_shet[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 24%;' >".$rek_shet."</span>";
		
		if($b['rek_pa_dop'])
			$rek_dop=$b['rek_pa_dop'];
		else
			$rek_dop=" --- ";
		$payment_dop[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 24%;' >".$rek_dop."</span>";
	

		if($b['rek_de_sity'])
			$rek_de_sity=$b['rek_de_sity'];
		else
			$rek_de_sity=" --- ";
		$delivery_sity[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 24%;' >".$rek_de_sity."</span><br/>";
		
		if($b['rek_de_sposib'])
			$rek_de_sposib=$b['rek_de_sposib'];
		else
			$rek_de_sposib=" --- ";
		$delivery_sposib[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 24%;' >".$rek_de_sposib."</span><br/>";
		
		if($b['rek_de_address'])
			$rek_de_address=$b['rek_de_address'];
		else
			$rek_de_address=" --- ";
		$delivery_add[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 24%;' >".$rek_de_address."</span><br/>";
		
		if($b['rek_de_get'])
			$rek_de_get=$b['rek_de_get'];
		else
			$rek_de_get=" --- ";
		$delivery_get[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 24%;' >".$rek_de_get."</span><br/>";
		
		if($b['rek_de_dop'])
			$rek_de_dop=$b['rek_de_dop'];
		else
			$rek_de_dop=" --- ";
		$delivery_dop[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 24%;' >".$rek_de_dop."</span><br/>";

		$uc_opt_skidka[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 28%;' >".$b['uc_opt_skidka']."%</span>";

		$uc_opt_natsenka[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 28%;' >".$b['uc_opt_natsenka']."</span>";
	
		$uc_opt_otgruz[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 28%;' >".$b['uc_opt_otgruz']." мин/ед, {$b['ot_min_price']} мин/грн</span>";
		if($b['uc_opt_delivery'])
			$uc_opt_delivery2=$b['uc_opt_delivery'];
		else
			$uc_opt_delivery2=" --- ";
		$uc_opt_delivery[$b['categories_of_commodities_ID']]="<span class='border_gray2' >".$uc_opt_delivery2."</span>";
		if($b['uc_opt_price'])
			$uc_opt_price2=$b['uc_opt_price'];
		else
			$uc_opt_price2=" --- ";
		$uc_opt_price[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 28%;' >".$uc_opt_price2."</span>";

		$uc_pr_skidka[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 28%;' >".$b['uc_pr_skidka']."%</span><br/>";

		$uc_pr_natsenka[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 28%;' >".$b['uc_pr_natsenka']."</span><br/>";


		$uc_pr_otgruz[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 28%;' >".$b['uc_pr_otgruz']." мин/ед, {$b['ot_min_pr_price']} мин/грн</span><br/>";

	// if($b['uc_pr_skidka'])
	// 		$uc_pr_skidka2=$b['uc_pr_skidka'];
	// 	else
	// 		$uc_pr_skidka2=" --- ";
	// 	$uc_pr_skidka[$b['categories_of_commodities_ID']]="<span class='border_gray2' >".$uc_pr_skidka2."</span><br/><span class='border_gray2' > --- </span><br/><span class='border_gray2' > --- </span><br/>";




		if($b['uc_pr_delivery'])
			$uc_pr_delivery2=$b['uc_pr_delivery'];
		else
			$uc_pr_delivery2=" --- ";
		$uc_pr_delivery[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 28%;' >".$uc_pr_delivery2."</span><br/>";

		if($b['uc_pr_price'])
			$uc_pr_price2=$b['uc_pr_price'];
		else
			$uc_pr_price2=" --- ";
		$uc_pr_price[$b['categories_of_commodities_ID']]="<span class='border_gray2' style='width: 28%;' >".$uc_pr_price2."</span><br/>";

	}

	$txt="<div class='screen'></div>
		<input type='text' id='search_brenda' value='введите название бренда'>
		 <table class = 'sortable tab_bre' style='width: 100%;' >
		 	<th style='width:5px'>№</th>
		 	<th style='width:14%'>БРЕНД</th>
		 	<th style='width:20%'>КОНТАКТЫ</th>
		<!--<th style='width:250px'>ОПЛАТА</th>
		 	<th>ДОСТАВКА</th>-->
			<th style='width:25%'>УСЛОВИЯ</th>
			<th>РЕКВИЗИТЫ</th>
		<!--<th style='width:150px'>ОТЧЕТ САП</th>-->
			<th style='width:5px'>Ред.</th>
		 ";
	$ii=1;
	foreach ($brend as $k => $v) {
		$t_name="";
		if($name[$k]){
			$a_name=explode(";",$name[$k]);
			for($i=1; $i<count($a_name); $i++){
				if($i==1)
					$t_name.="<span class='border_gray' style='width: 53%;' >{$a_name[$i]}</span><br/>";
				else
					$t_name.="<span class='border_gray' style='margin-left: 36%;width: 53%;' >{$a_name[$i]}</span><br/>";
			}
		}else{
			$t_name="<span class='border_gray' > --- </span><br/>";
		}

		$t_phone="";
		if($phone[$k]){
			$a_phone=explode(";",$phone[$k]);
			for($i=1; $i<count($a_phone); $i++){
				if($i==1)
					$t_phone.="<span class='border_gray' style='width: 53%;' >{$a_phone[$i]}</span><br/>";
				else
					$t_phone.="<span class='border_gray' style='margin-left: 36%;width: 53%;' >{$a_phone[$i]}</span><br/>";
			}
		}else{
			$t_phone="<span class='border_gray' > --- </span><br/>";
		}

		$t_mail="";
		if($mail[$k]){
			$a_mail=explode(";",$mail[$k]);
			for($i=1; $i<count($a_mail); $i++){
				if($i==1)
					$t_mail.="<span class='border_gray' style='width: 53%;' >{$a_mail[$i]}</span><br/>";
				else
					$t_mail.="<span class='border_gray' style='margin-left: 36%;width: 53%;' >{$a_mail[$i]}</span><br/>";
			}
		}else{
			$t_mail="<span class='border_gray' > --- </span><br/>";
		}

		// $txt.="<tr>
		// 		<td class='id_b' style='vertical-align: middle;font-size: 18px;'>{$ii}</td>
		// 		<td colspan=5 >
		// 			<table width='100%' class='tabd' >
		// 				<tr>
		// 					<td style='text-align:center;width:160px;' rowspan='2'><img src='/images/categories/{$k}/main.jpg' width='90px' style='cursor:pointer;' class='but_brenda' rel='{$k}'><br>
		// 						<a href='{$site[$k]}' target='_blank' id='site{$k}'>{$site[$k]}</a>
		// 				 	</td>
		// 				 	<td style='width:189px;' rowspan='2'>
		// 				 		<div class='over_max'>
		// 				 			<span class='border_blue'> ИМЯ:</span>{$t_name}
		// 				 			<span class='border_blue'> ТЕЛЕФОН:</span>{$t_phone}
		// 				 			<span class='border_blue'> ПОЧТА:</span>{$t_mail}
		// 			 			</div>
		// 			 		</td>
		// 			 		<td style='width:190px;height:20px;text-align:center;'><b>ОПТ</b></td>
		// 			 		<td style='width:190px;height:20px;text-align:center;'><b>РОЗНИЦА</b></td>
		// 			 		<td style='width:190px;height:20px;text-align:center;'><b>ОПЛАТА</b></td>
		// 			 		<td style='width:190px;height:20px;text-align:center;'><b>ДОСТАВКА</b></td>
		// 			 		<td style='width:191px;' rowspan='2'>
		// 			 			<span class='gran_sp'>ПРОВЕРЕНО:</span> <span class='blue_sp' id='par_check{$p['id']}' >$check_prog[$k]</span>
		// 						<span class='gran_sp'>ОБНАРУЖЕНО:</span> <span class='blue_sp' id='par_obnar{$p['id']}'>$add_new_com[$k]</span><br/>
		// 						<span class='gran_sp'>ОБНОВЛЕНО:</span> <span class='blue_sp' id='par_update{$p['id']}'>$update_add[$k]</span>
		// 						<span class='gran_sp'>НОВЫЕ URL:</span> <span class='blue_sp' id='par_new_url{$p['id']}'>$add_new_com[$k]</span><br/>
		// 						<span class='gran_sp'>СКРЫТО:</span> <span class='blue_sp' id='par_hide{$p['id']}'>$par_hide[$k]</span>
		// 						<span class='gran_sp'>ИМПОРТИРОВАНО:</span> <span class='blue_sp' id='par_import{$p['id']}' >$add_new_com[$k]</span><br/>
		// 						<img src='/templates/admin/img/time.png' style='width: 40px;' />
		// 						<span style='float: right;' >
		// 						 	<span class='gran_sp'>Начало </span> <span id='par_start_time{$p['id']}'>{$par_start_time[$k]}</span><br/>
		// 						 	<span class='gran_sp'>Окончание </span> <span id='par_end_time{$p['id']}'>$par_end_time[$k]</span><br/>
		// 						 	<span class='gran_sp'>Длительность </span> <span id='par_time_darution{$p['id']}'>$par_time_darution[$k]</span><br/>
		// 						</span>
		// 			 		</td>
		// 				</tr>
		// 				<tr>
		// 					<td>
		// 						<div class='over_max'>
		// 							<span class='border_blue'><b>СКИДКА:</b></span>{$uc_opt_skidka[$k]}
		// 							<span class='border_blue'><b>НАЦЕНКА:</b></span>{$uc_opt_natsenka[$k]}
		// 							<span class='border_blue'><b>ОТГРУЗКА:</b></span>{$uc_opt_otgruz[$k]}
		// 							<span class='border_blue'><b>ДОСТАВКА:</b></span>{$uc_opt_delivery[$k]}
		// 							<span class='border_blue'><b>ЦЕНА:</b></span>{$uc_opt_price[$k]}
		// 						</div>
		// 					</td>
		// 					<td>
		// 						<div class='over_max'>
		// 							".$uc_pr_skidka[$k].$uc_pr_natsenka[$k].$uc_pr_otgruz[$k].$uc_pr_delivery[$k].$uc_pr_price[$k]."
		// 						</div>
		// 					</td>
		// 					<td>
		// 						<div class='over_max'>
		// 							<span class='border_blue'><b>ПЛАТЕЖ:</b></span>{$payment_plat[$k]}
		// 							<span class='border_blue'><b>Ф.И.О.:</b></span>{$payment_name[$k]}
		// 							<span class='border_blue'><b>БАНК:</b></span>{$payment_bank[$k]}
		// 							<span class='border_blue'><b>№СЧЕТА:</b></span>{$payment_shet[$k]}
		// 							<span class='border_blue'><b>ПРИМЕЧ.:</b></span>{$payment_dop[$k]}
		// 						</div>
		// 					</td>
		// 					<td>
		// 						<div class='over_max'>
		// 							<span class='border_blue'><b>ГОРОД:</b></span>{$delivery_sity[$k]}
		// 							<span class='border_blue'><b>СПОСОБ:</b></span>{$delivery_sposib[$k]}
		// 							<span class='border_blue'><b>№СКЛАДА:</b></span>{$delivery_add[$k]}
		// 							<span class='border_blue'><b>ПОЛУЧАТЕЛЬ:</b></span>{$delivery_get[$k]}
		// 							<span class='border_blue'><b>ПРИМЕЧ.:</b></span>{$delivery_dop[$k]}
		// 						</div>
		// 					</td>
		// 				</tr>
		// 			</table>
		// 		</td>
		// 		<td style='text-align:center;'><img src='/templates/admin/img/btnbar_edit.png' style='cursor:pointer;' class='but_brenda' rel='{$k}' /></td>	
		// 	</tr>";
		$txt.="
			<tr>
			<td class='id_b' style='vertical-align: middle;font-size: 18px;'>{$ii}</td>
			<td colspan=4 >
			<div class='tab'>
					<div class='tab_td' style='width:15%'>
						<div class='br_name br_div' style='margin: 4px;text-align: center;'>
							<a href='{$b['bc_site']}' target='_blank'>
								<img src='http://makewear.com.ua/templates/shop/image/categories/{$k}/main.jpg' />
							</a>
						</div>
					</div>
					<div class='tab_td' style='width:20%'>
						<div class='open_windows1' id='ow1_{$k}' style='margin-top: 3px;'>
							<div class='br_cont br_div' id='window_cont{$k}' style='padding-top: 1px;'>
								<div class='div_center'>
									<span class='border_blue' >ИМЯ</span>{$t_name}
									<span class='border_blue' >ТЕЛЕФОН</span>{$t_phone}
									<span class='border_blue' >ПОЧТА</span>{$t_mail}
								</div>
							</div>
							<center>
								<span class='open_cont bu1' style='cursor:pointer;' rel='{$k}'>
									<div class='block_down1' id='bo1_{$k}' style='margin: 5px;'></div>
								</span>
							</center>
						</div>
					</div>
					<div class='tab_td' style='width:25%'>
						<div class='open_windows2' id='ow2_{$k}' style='margin-top: 3px; margin-left:12px;' >
							<div class='br_us br_div div_center' id='window_us{$k}' style='padding-top: 1px;'>
								<span class='border_blue' style='text-align:center;margin-left: 31%;' ><b>ОПТ</b></span><span class='border_blue' style='text-align:center;margin-left: 10%;' ><b>РОЗНИЦА</b></span><br/>
								<span class='border_blue' >СКИДКА</span>{$uc_opt_skidka[$k]}{$uc_pr_skidka[$k]}
								<span class='border_blue' >НАЦЕНКА</span>{$uc_opt_natsenka[$k]}{$uc_pr_natsenka[$k]}
								<span class='border_blue' >ОТГРУЗКА</span>{$uc_opt_otgruz[$k]}{$uc_pr_otgruz[$k]} 
								<span class='border_blue' >ДОСТАВКА</span>{$uc_opt_delivery[$k]}{$uc_pr_delivery[$k]}
								<span class='border_blue' >ЦЕНА</span>{$uc_opt_price[$k]}{$uc_pr_price[$k]}
							</div>
							<center>
								<span class='open_us bu1' style='cursor:pointer;' rel='{$k}'>
									<div class='block_down1' id='bo2_{$k}' style='margin: 5px;'></div>
								</span>
							</center>
							<div></div>
						</div>
					</div>
					<div class='tab_td' style='width:40%'>
						<div class='open_windows3' id='ow3_{$k}' style='margin-top:3px; margin-left: 34px;'>
							<div class='br_rez br_div div_center' id='window_rez{$k}' style='padding-top: 1px;'>
								<span class='border_blue' style='text-align:center;margin-left: 24%;' ><b>ОПЛАТА</b></span><span class='border_blue' style='text-align:center;margin-left: 30%;' ><b>ДОСТАВКА</b></span><br/>
								<span class='border_blue' >ПЛАТЕЖ</span>{$payment_plat[$k]}<span class='border_blue' >ГОРОД</span>{$delivery_sity[$k]}
								<span class='border_blue' >Ф.И.О.</span>{$payment_name[$k]}<span class='border_blue' >СПОСОБ</span>{$delivery_sposib[$k]}
								<span class='border_blue' >БАНК</span>{$payment_bank[$k]}<span class='border_blue' >№СКЛАДА</span>{$delivery_add[$k]}
								<span class='border_blue' >№СЧЕТА</span>{$payment_shet[$k]}<span class='border_blue' >ПОЛУЧАТЕЛЬ</span>{$delivery_get[$k]}
								<span class='border_blue' >ПРИЧЕМ.</span>{$payment_dop[$k]}<span class='border_blue' >ПРИЧЕМ.</span>{$delivery_dop[$k]}	
							</div>
							<center>
								<span class='open_rez bu1' style='cursor:pointer;' rel='{$k}'>
									<div class='block_down1' id='bo3_{$k}' style='margin: 5px;'></div>
								</span>
							</center>
						</div>
					</div>
				</div>
			</td>
			<td style='text-align:center;'>
				<img src='/templates/admin/img/btnbar_edit.png' style='cursor:pointer;' class='but_brenda' rel='{$k}' />
			</td>
		</tr>";
		$ii++;
	}
	$txt.="</table>";


	$center="<style>
		#search_brenda{
			margin: 10px;
			width: 200px;
			color: rgba(128, 128, 128, 0.52);
		}
		.tabd{
			border-collapse: collapse;
			border: 3px solid gray;
		}
		.tabd tr td{
			border:1px solid gray;
		}
		#sea_img{
			margin: 10px;
		}

		.tab_bre th{
			text-align: center;
		}
		.id_b{
			text-align: center;
			vertical-align: middle;
		}
		/*.border_blue{
			background:#54A5B2;
			color: white;
			display:inline-block;
			border-radius: 15px;
			pagging:1px;
			margin:1px;
			width:75px;
			padding-left: 7px;
			padding-right: 7px;
			font-size:10px;
		}
		.border_gray, .border_gray2{
			background: rgba(128, 128, 128, 0.26) none repeat scroll 0% 0%;
			color: black;
			display:inline-block;
			border-radius: 15px;
			pagging:1px;
			margin:1px;
			width:57%;
			padding-left: 7px;
			padding-right: 7px;
			font-size:10px;
		}*/
		.border_blue {
			background: #54A5B2 none repeat scroll 0% 0%;
			color: #FFF;
			display: inline-block;
			border-radius: 15px;
			margin: 1px;
			width: 50px;
			padding-left: 8px;
			padding-right: 8px;
			padding-bottom: 2px;
			padding-top: 4px;
			font-size: 7.5pt;
			text-align: center;
			font-weight: bold;
			margin-top:1px;
		}
		.border_gray, .border_gray2 {
			background: rgba(128, 128, 128, 0.13) none repeat scroll 0% 0%;
			color: #000;
			display: inline-block;
			border-radius: 15px;
			margin: 1px;
			width: 56%;
			padding-left: 7px;
			padding-right: 7px;
			padding-bottom: 2px;
			padding-top: 4px;
			font-size: 10px;
			vertical-align: top;
		}
		.border_gray2{
			width:54%;
			vertical-align: top;
			font-size:10px;
		}
		.blue_sp{
			background: #355177 none repeat scroll 0% 0%;
			color: #FFF;
			width: 32px;
			font-size:10px;
			text-align: center;
		}
		.gran_sp{
			background: rgba(128, 128, 128, 0.31) none repeat scroll 0% 0%;
			width:100px;
			/*padding: 5px;*/
			color: black;
			font-size:10px;
		}
		.over_max{
			overflow: hidden;
			max-height: 40px;
		}
		.tab{
			display:table;
			width:100%;
			background: #A1A1A1;
			border-radius: 8px;
			/*height: 50px;*/
			box-shadow: 1px 1px 3px black; 
		}
		.tab_td{
			display:table-cell;
			vertical-align: top;
		}
		.tab_up{
					border-radius: 7px;
				}
				.tab_down{
					border-top-left-radius: 7px;
					border-top-right-radius: 7px;
				}
				.br_name img{
					width: 95px;
					height: 95px;
				}
				.br_name a{
					font-size:10px;
					color: blue;
					text-decoration: underline;
				}
				.br_div{
					margin: 2px;
					border-radius: 8px;
					background: white;
				}
				.br_cont{
					overflow: hidden;
					max-height: 83px;
					height: 83px;
				}
				.br_us{
					overflow: hidden;
					max-height: 80px;
				}
				.br_rez{
					overflow: hidden;
					max-height: 80px;
				}
				.block_up1{
					border-bottom: 5px solid white;
					border-left: 5px solid transparent;
					border-right: 5px solid transparent;
					width: 0px;
					height: 0px;
				}
				.block_down1{
					border-top: 5px solid white;
					border-left: 5px solid transparent;
					border-right: 5px solid transparent;
					width: 0px;
					height: 0px;
				}
				.open_windows1{
					/*border: 1px dotted;*/
					position: absolute;
					width: 16%;
					background: #A1A1A1;
					border-radius: 7px;
				}
				.open_windows2{
					/*border: 1px dotted;*/
					position: absolute;
					width: 21%;
					background: #A1A1A1;
					border-radius: 7px;
				}
				.open_windows3{
					/*border: 1px dotted;*/
					position: absolute;
					width: 28%;
					background: #A1A1A1;
					border-radius: 7px;
				}
		</style>";
		$center.="<script>
				$(document).ready(function(ev){

					$(window).resize(function(){
						var a='Y: '+$(window).height()+', X: '+$(window).width();
						$('.screen').text(a);
					});
				

					$('#search_brenda').click(function(){
						$(this).val('');
					});
					$('.but_brenda').click(function(){
						var rel=$(this).attr('rel');
						location.href='/?admin=brenda_contact&id='+rel;
					});	


					$('.open_cont').click(function(){
						var rel=$(this).attr('rel');
						if($(this).hasClass('bu1')){
							close_win();
							$('#window_cont'+rel).animate({'max-height': '550px', 'height':'none'}, 900, function(){
								$('#bo1_'+rel).removeClass('block_down1');
								$('#bo1_'+rel).addClass('block_up1');
								$('#ow1_'+rel).css({'box-shadow': '2px 3px 7px black' });
								// fun_rotate('#bo1_'+rel, 1, 50);
							});
							$('#ow1_'+rel).css({'z-index':'50'});
							$(this).removeClass('bu1');
							$(this).addClass('bu2');
						}else{
							$('#window_cont'+rel).animate({'max-height': '83px', 'height':'83px'}, 300, function(){
								$('#ow1_'+rel).css({'z-index':'0', 'box-shadow': '0px 0px 0px'});

								$('#bo1_'+rel).removeClass('block_up1');
								$('#bo1_'+rel).addClass('block_down1');
								// fun_rotate('#bo1_'+rel, 2, 50);
							});
							$(this).removeClass('bu2');
							$(this).addClass('bu1');
						}
					});
					$('.open_us').click(function(){
						var rel=$(this).attr('rel');
						if($(this).hasClass('bu1')){
							close_win();
							
							$('#window_us'+rel).animate({'max-height': '550px'}, 900, function(){
								$('#ow2_'+rel).css({'box-shadow': '2px 3px 7px black'});

								$('#bo2_'+rel).removeClass('block_down1');
								$('#bo2_'+rel).addClass('block_up1');
								// fun_rotate('#bo2_'+rel, 1, 50);
							});
							$('#ow2_'+rel).css({'z-index':'50'});
							$(this).removeClass('bu1');
							$(this).addClass('bu2');
						}else{
							$('#window_us'+rel).animate({'max-height': '80px'}, 300, function(){
								$('#ow2_'+rel).css({'z-index':'0', 'box-shadow': '0px 0px 0px'});

								$('#bo2_'+rel).removeClass('block_up1');
								$('#bo2_'+rel).addClass('block_down1');
								// fun_rotate('#bo2_'+rel, 2, 50);
							});
							$(this).removeClass('bu2');
							$(this).addClass('bu1');
						}
					});
					$('.open_rez').click(function(){
						var rel=$(this).attr('rel');
						if($(this).hasClass('bu1')){
							close_win();
							$('#window_rez'+rel).animate({'max-height': '300px'}, 900, function(){
								$('#bo3_'+rel).removeClass('block_down1');
								$('#bo3_'+rel).addClass('block_up1');
								$('#ow3_'+rel).css({'box-shadow': '2px 3px 7px black'});
								// fun_rotate('#bo3_'+rel, 1, 50);
							});
							$('#ow3_'+rel).css({'z-index':'50'});
							$(this).removeClass('bu1');
							$(this).addClass('bu2');
						}else{
							$('#window_rez'+rel).animate({'max-height': '80px'}, 300, function(){
								$('#bo3_'+rel).removeClass('block_up1');
								$('#bo3_'+rel).addClass('block_down1');
								$('#ow3_'+rel).css({'z-index':'0', 'box-shadow': '0px 0px 0px'});
								//fun_rotate('#bo3_'+rel, 2, 50);
							});
							$(this).removeClass('bu2');
							$(this).addClass('bu1');

						}
					});
				});
				function close_win(){
					$('.br_cont, .br_us, .br_rez').animate({'max-height': '80px'},'slow');
					$('.open_cont, .open_us, .open_rez').removeClass('bu2');
					$('.open_cont, .open_us, .open_rez').addClass('bu1');
					$('.open_windows1, .open_windows2, .open_windows3').css({'z-index':'0', 'box-shadow': '0px 0px 0px'});
				}
			</script>";
	$center.=$txt;

}
?>