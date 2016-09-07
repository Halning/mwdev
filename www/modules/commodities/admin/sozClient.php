<?php

//TODO исключить запросы в цикле, использовать оператор IN() 
if ($_SESSION['status']=="admin"){

include "soz.php";

	$group_client_active=array();
	$arr_c=array();
	//`status` in (1,2,3,4,6,7)
	$sql = "SELECT * 
			FROM  `shop_orders`
			WHERE `status` in (1,2,3,4,5,6,7, 12)
			ORDER BY `id`  DESC {$limit}";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)){
		$user_id = $row["user_id"];
		$offer_id = $row["id"];
		$cur_id= $row["cur_id"];
		$namee[$offer_id]=$row["name"];
		$emaill[$offer_id]=$row["email"];

		if($group_client_active[$row["email"]]==0){
			$group_client_active[$row["email"]]=1;
			$group_client[$row["email"]]=array();
		}
		array_push($group_client[$row["email"]], $offer_id);


		$tell[$offer_id]=$row["tel"];
		$cityy[$offer_id]=$row["city"];
		$addresss[$offer_id]=$row["address"];

		$client[$offer_id]="{$namee[$offer_id]}<br>{$tell[$offer_id]}<br>{$emaill[$offer_id]}<br>{$cityy[$offer_id]}<br>{$addresss[$offer_id]}";

		$comission[$user_id] = $row["commission"];


		$delivery=$row["delivery"];
		if($delivery==0){
			$delivery=$row["name_delivery"];
		}else{
			$delSql=mysql_query("SELECT * FROM `shop_delivery` WHERE `id`='{$delivery}'; ");
			$delRes=mysql_fetch_assoc($delSql);
			$delivery=$delRes["name"];
		}
		
		$delivery_price[$user_id] = $row["delivery_price"];
		$offer_cur_name = show_cur_for_admin($row["cur_id"]);

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


		$rowatus_selected1 = "";
		$rowatus_selected2 = "";
		$rowatus_selected3 = "";
		$rowatus_selected4 = "";
		$rowatus_selected5 = "";
		$rowatus_selected6 = "";
		$rowatus_selected7 = "";
		$rowatus_selected12 = "";
				
		
		$address = $row["address"];
		$cod =$row["cod"];
		$art[$row["id"]] = $row["cod"];
		$date = $row["date"];
		$client_count["{$user_id}"] +=1;
		$lines = '';

		// $active[$offer_id]="display:grid;";
		// $but_active[$offer_id]="but_active";

		$rowatus = $row["status"];
		//echo $rowatus.", ";
		if($rowatus == 1){
			$rowatus_selected1 = "selected";
		} elseif($rowatus == 2) {
			$rowatus_selected2 = "selected";
		} elseif($rowatus == 3){
			$rowatus_selected3 = "selected";
		} elseif($rowatus == 4){
			$rowatus_selected4 = "selected";
		} elseif($rowatus == 5){
			$rowatus_selected5 = "selected";
		} elseif($rowatus == 6){
			$rowatus_selected6 = "selected";
		} elseif($rowatus == 7){
			$rowatus_selected7 = "selected";
		} elseif($rowatus == 8){
			$rowatus_selected7 = "selected";
		} elseif($rowatus == 9){
			$rowatus_selected7 = "selected";
		} elseif($rowatus == 10){
			$rowatus_selected10 = "selected";
		} elseif($rowatus == 12){
			$rowatus_selected12 = "selected";
		}
		if( $rowatus < 3 || $rowatus == 10){
			$select_client="
			<select size='1' name='status' id = 'select_order_status' class='color_select'  rel='{$offer_id}'>
    			<option value='1' rel='{$offer_id}' {$rowatus_selected1}>Новый заказ</option>
				<option value='2' rel='{$offer_id}' {$rowatus_selected2}>Обрабатывается</option>
				<option value='3' rel='{$offer_id}' {$rowatus_selected3}>Подтвержден</option>
				<option value='10' rel='{$offer_id}' {$rowatus_selected10}>Отменен</option>		
            </select>";
        }else{
        	$payment="";
        	if($row["payment_MW"]==1){
        		$payment=" и оплачен";
        	}
        	$select_client="
			<select id = 'select_order_status' class='discolor_select'  rel='{$offer_id}' disabled>
				<option value='3' rel='{$offer_id}' {$rowatus_selected3}>Готов к оплате клиентом</option>
				<option value='12' rel='{$offer_id}' {$rowatus_selected12}>Оплачен MW</option>
				<option value='4' rel='{$offer_id}' {$rowatus_selected4}>Оплачен клиентом</option>
				<option value='8' rel='{$offer_id}' {$rowatus_selected8}>На складе</option>
				<option value='5' rel='{$offer_id}' {$rowatus_selected5}>Собран</option>
				<option value='6' rel='{$offer_id}' {$rowatus_selected6}>Отправлен</option>
				<option value='7' rel='{$offer_id}' {$rowatus_selected7}>Доставлен {$payment}</option>		
            </select>";
        }

		$current_date = strtotime(date("Y-m-d H:i:s"));  
		$the_date_of_offer = strtotime($row["date"]);
		if(($current_date - $the_date_of_offer) > 86400)
		{
			$does_it_open = "none";
		}
		
		$client_sum=0;
		$ed=0;
		$jj=0;
		$num=0;
		$sql2="
			SELECT * FROM `shop_orders_coms`
			LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
			WHERE `offer_id`='{$row["id"]}' AND `count`>0;";
			$res2=mysql_query($sql2);
			while($row2=mysql_fetch_assoc($res2))
			{
				
				$com_id = $row2["com_id"];
				$iddd=$row2["commodity_ID"];
				$size = $row2["com"];
				$count = $row2["count"];
				$cur_id_com = $row2["cur_id"];
				$cod2 = $row2["cod"];
				
				$from_url = $row2["from_url"];
				$com_name = $row2["com_name"];
				

				$group_id=$row2["group_id"];
				$addOption="";
				$grres=mysql_query("SELECT `group_id`, `status` FROM `sup_group` WHERE `group_id`={$group_id}; ");
				$grrow=mysql_fetch_assoc($grres);

				$groupName=SOZ::getStatusCommodity($rowatus,$grrow["status"]);
				//$addOption="<option selected>{$groupName}</option>";
				
				if($groupName==""){
					$addOption="";
				}else{
					$addOption="<option selected>{$groupName}</option>";
				}
				
				//$cur_name_com = show_cur_for_admin($row2["cur_id"]);
				if($row2["com_color"] != "")
				{
					$color = $row2["com_color"];
				}
				else
				{
					$color = strip_tags(get_color_to_order($com_id));
				}
				
				$comment = $row2["man_comment"];
				$com_status = $row2["com_status"];
				
				$price = $row2["price"]*$count;
				$com_sum = $row2["price"];

				$order_com_id = $row2["id"];
				$basket_com_cat = $row2["com_brand_name"];
				$com_selected1 = "";
				$com_selected2 = "";
				$com_selected3 = "";
				$com_selected4 = "";
				$com_selected5 = "";
				$com_selected0 = "";
				$com_selected6 = "";
				$order_com_id = $row2["id"];
				$linecolor = "";

				$client_sum += $price;
				$ed+=$count;

				$rowatus_name="";
				$rowatus_com = $row2["com_status"];
				if($rowatus_com == 1){
					$com_selected1 = "selected";
					$linecolor = "greenline";
					$rowatus_name="Есть в наличии";
				} elseif($rowatus_com == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
					$client_sum-=$com_sum*$count;
					$ed-=$count;
					$rowatus_name="Нет в наличии";
					$addOption="";
				} elseif($rowatus_com == 3){
					$com_selected3 = "selected";
					$rowatus_name="Замена";
					$addOption="";
				} elseif($rowatus_com == 0){
					$com_selected0 ="selected";
				} elseif($rowatus_com == 4){
					$com_selected4 ="selected";
					$rowatus_name="оплачен";
				} elseif($rowatus_com == 5){
					$com_selected5 ="selected";
				} elseif($rowatus_com == 6){
					$com_selected6 ="selected";
				} 
				
				$basket_com_cat=SOZ::getBrandeName($iddd);
				$cat_name2=SOZ::getCategoryName($iddd);
				
				$lines[$offer_id].="
				<tr id='{$row2["id"]}' rel='shop_orders_coms' rel2='id' class ='{$linecolor}'>
				<td>{$basket_com_cat}</td>
				<td>{$cod2}</td>
				<td id = 'com_color' class='cl_edit'>{$color}</td>
				<td id = 'com' class='cl_edit'>{$size}</td>
				<td>{$count}</td>
				<td>{$cat_name2}</td>
				<td>{$com_sum}</td>
				<td>{$price}</td>
				<td><a href ='{$from_url}'>источник</a></td>
				<td><select size='1' name='status' id = 'select_status_com' rel = '{$order_com_id}' disabled>
							<option value= '0' {$com_selected0}></option>
							<option value='1'  {$com_selected1}>Есть в наличии</option>
    						<option value='2'  {$com_selected2}>Нет в наличии</option>
    						<option value='3'  {$com_selected3}>Замена</option>
    						{$addOption}
    					</select></td>
				<td id = 'man_comment' class='cl_edit'>{$row2["man_comment"]}</td>
				</tr>
				";
				//if($rowatus_com != 2){
				$arr_c[$offer_id][$jj]=array(
					"brands"=>$basket_com_cat, 
					"art"=>$cod2, 
					"color"=>$color, 
					"size"=>$size,
					"count"=>$count, 
					"price"=>$com_sum, 
					"all_price"=>$price, 
					"cur"=>$offer_cur_name,
					"url"=>$from_url,
					"cat"=>$cat_name2,
					"name"=>$com_name,
					"status"=>$rowatus_name,
					"delivery"=>$delivery
					);
					$jj++;
				//}
					$tab_com[$offer_id].='<tr>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$num.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$basket_com_cat.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$cod2.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$color.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$size.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$count.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$com_sum.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$glb["cur"][$cur_id].'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$price.'</td><tr>';
				$tab_com2[$offer_id][$num]=array('num'=>$num,'com_cat'=>$basket_com_cat,'cod'=>$cod2,'color'=>$color,'size'=>$size,'count'=>$count,'price'=>$com_sum,'cur'=>$glb["cur"][$cur_id],'com_sum'=>$price);
				$num++;
			}
		
			if($row["sent_mail"]==0){
				$sendd="<div style='margin-top: 3px;' >
							<span class='i_sent sentt{$offer_id}' rel='{$offer_id}' >
								<div class='icon_send'></div>
								подтв.
							</span>
						</div>";
			}elseif($row["sent_mail"]>=1){
				$sendd="<div style='margin-top: 3px;' >
							<span class='i_sent sentt{$offer_id}' rel='{$offer_id}' >
								<div class='icon_sent' rel-was-sent='{$row["sent_mail"]}' ></div>
								<span style='text-decoration:underline;color: rgb(248, 106, 5);'>подтв.</span>
							</span>
						</div>";
			}	
			// доставка
			if($row["sent_mail_mw_k"]==0){
				$sendd2="<span class='send_mail maill2 set_sent{$offer_id}' rel='{$offer_id}' rel2={$emaill[$offer_id]} style='cursor:pointer;text-decoration:underline;'>
							<div class='icon_send'></div>
							<span>дост.</span>
						</span>";
			}elseif($row["sent_mail_mw_k"]>=1){
				$sendd2="<span class='send_mail maill2' rel='{$offer_id}' rel2={$emaill[$offer_id]} style='cursor:pointer;text-decoration:underline;color: #FF6C01;'>
							<div class='icon_sent' rel-was-sent={$row["payment_sent_mail"]} ></div>
							<span>дост.</span>
						</span>	";
			}
			// $offer_id22 = end($group_client[$row["email"]]);
			$offer_id22 = $group_client[$row["email"]][0];
			// счет 
			if($row["payment_sent_mail"]==0){
				$sendd1="<span class='send_mail maill set_sent{$offer_id}' rel='{$offer_id22}' rel2={$emaill[$offer_id]} style='cursor:pointer;text-decoration:underline;'>
							<div class='icon_send'></div>
							<span>счет</span>
						</span>";
			}elseif($row["payment_sent_mail"]>=1){
				$sendd1="<span class='send_mail maill' rel='{$offer_id22}' rel2={$emaill[$offer_id]} style='cursor:pointer;text-decoration:underline;color: #FF6C01;'>
							<div class='icon_sent' rel-was-sent={$row["payment_sent_mail"]} ></div>
							<span>счет</span>
						</span>	";
			}	

			$cclip=$row["mw_k_clip"];
			$clip_name=$row["mw_k_clip_file"];	 
			
			if($cclip==1){
				$file2=end(explode(".",$row["mw_k_clip_file"]));
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
		if($row["mw_k_clip"]==0) {
			$clip="<div class='icon_clip_will' style='cursor:pointer;padding: 0px 1px;' rel='{$row["id"]}'></div>";
		}elseif($row["mw_k_clip"]==1) {
			$clip="<div class='icon_clip_was icw{$row["id"]}' style='cursor:pointer;padding: 0px 1px;' rel='{$row["id"]}' rel-file='{$clip_file}' rel-size='{$size_kb}' rel-name='{$clip_name}'></div>";	
		}
		$delivery2=$row["delivery"];
		if($delivery2==0){
			$delivery2=$row["name_delivery"];
		}else{
			$delSql=mysql_query("SELECT * FROM `shop_delivery` WHERE `id`='{$delivery2}'; ");
			$delRes=mysql_fetch_assoc($delSql);
			$delivery2=$delRes["name"];
		}

		$depart=$row['ttn-depart'];

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

			if($offer_id<433){
				$commission=round($client_sum/100*3);
				$client_sum+=$commission;
			}

			$client_sum+=$delivery_price[$user_id];
			$commisia[$offer_id]=array("commision"=> $commission,"del_price"=>$row["delivery_price"]);
		
		$cur_name=$glb["cur"][$cur_id];
		$offer_sum2[$offer_id] = $client_sum." {$cur_name}";
		$del[$offer_id]=$delivery_price[$user_id]." {$cur_name}";;
		$commissia[$offer_id]=$commission." {$cur_name}";;
		$sum_pri[$offer_id]=$client_sum." {$cur_name}";

		$rowatus = $row["status"];
		//echo $rowatus.", ";
		if($rowatus == 1){
			$rowatus_selected1 = "selected";
		} elseif($rowatus == 2) {
			$rowatus_selected2 = "selected";
		} elseif($rowatus == 3){
			$rowatus_selected3 = "selected";
		} elseif($rowatus == 4){
			$rowatus_selected3 = "selected";
		} elseif($rowatus == 5){
			$rowatus_selected5 = "selected";
		} elseif($rowatus == 6){
			$rowatus_selected6 = "selected";
		} elseif($rowatus == 7){
			$rowatus_selected7 = "selected";
		} elseif($rowatus == 10){
			$rowatus_selected10 = "selected";
		}

			$order[$offer_id] = "
				<div class='orders_head tab_up oc{$offer_id}' rel='{$cod}' rel_data='{$date}' style='margin: 3px 0px 0px 0px;' >
					<div class='tab_td' style='padding: 8px;'>
						<span class='open_commodity' rel='{$offer_id}'>
							<div class='block_down' id='bb{$offer_id}'></div>
						</span>
					</div>
					<div class='tab_td' style='width:12%'>
						№{$cod}
					</div>
					<div class='tab_td' style='width:7%'>
						ID:{$offer_id}
					</div>
					<div class='tab_td' style='width:19%'>
						{$date}
					</div>
					<div class='tab_td' style='width:19%'>
						{$select_client}
					</div>
					<div class='tab_td' style='width:12%'>
						{$ed}ед
					</div>
					<div class='tab_td' style='width:13%'>
						{$client_sum} {$offer_cur_name}
					</div>
					<div class='tab_td'>
						<div style='width:70px;'>
							{$sendd}
						</div>
					</div>
					<div class='tab_td'>
						<div style='width:57px;'>
							{$sendd1}
						</div>
					</div>
					<div class='tab_td'>
						<div style='width:61px;'>
							{$sendd2}
						</div>
					</div>
					<div class='tab_td'>
						{$clip}
					</div>
					<div class='tab_td'>
						<div class='cli_xls' style='padding: 0px 3px;'>
							<div class='bor_xls'>
								<a href='/email/download_excel.php?exportIdd={$offer_id}'>
									<div class='icon_xls'></div>
								</a>
							</div>
						</div>

					</div>
					<div  class='tab_td' style='cursor: pointer;position: relative;'  >
					<div style='margin-right: 6px;'>
						<div class='{$putInfo} open_backg' rel='{$offer_id}' style='display:table;'>
							<div style='display:table-cell'>
								<div class='{$putInfoColor} iiw{$offer_id}'></div>
							</div>
							<div style='display:table-cell;vertical-align: middle;padding-left: 2px;'>
								<div class='{$putDownColor} bbc{$offer_id}' ></div>
							</div>
						</div>	
						<div class='wind_names' id='open_win2{$offer_id}' style='display:none;right:4px;margin-top:0px;width: 300px;'>
							<table>
								<tr><td></td></tr>
								<tr>
									<td style='font-weight:100;text-align: left;width: 300px;height: 90px;' contenteditable='true' class='write_tab' rel='{$offer_id}'>
										{$write_deli}
									</td>
								</tr>
								<tr><td></td></tr>
							</table>
						</div>	
					</div>
					</div>
					<div class='tab_td' style='width: 10px;'></div>
				</div>
			";
			$order[$offer_id].="<div class='win_line' id='wl{$offer_id}' style='display:none'>
				<table  class='sortable' >
					<tr>
						<th>Бренд</th>
						<th>Артикул</th>
						<th>Цвет</th>
						<th>Размер</th>
						<th>Кол-во</th>
						<th>Товар</th>
						<th>Цена</th>
						<th>Сумма</th>
						<th>Ссылка</th>
						<th>Статус</th>
						<th>Комментарий</th>
					</tr>
				".$lines[$offer_id]."</table>";
				
			$order[$offer_id] .="</div>";
	}
	
	//echo json_encode($arr_c);
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
	$clientRes2=mysql_query("SELECT * FROM `soc_client_all_stock` WHERE 1");
	while ($cliRow2=mysql_fetch_assoc($clientRes2)) {
			

		$scj_id=$cliRow2["scas_id"];
		$scj_name=$cliRow2["scas_name"];
		

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
	$center .= "
		<div class='allActsia' >
			<div class='allActsiaName'> ОБЩИЕ АКЦИИ</div>
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
		</div>
		<div class='rees'></div><br/><br/>
			<table class = 'sortable w_cli'>
				<tr>
					<th style='width: 0px;'></th>
					<th>ID</th>
					<th>Профиль клиента</th>
					<th>Электронная почта</th>
					<th>Номер телефон</th>
					<th>Город</th>
					<th>Адрес для отправки</th>
					<th>Диалог с клиентом</th>
				</tr>
	";

	//var_dump($group_client);
	if($group_client)
	foreach ($group_client as $key => $value) {
		$offer_id2 = end($group_client[$key]);
		$name = $namee[$value[0]];
		$name=trim($name);
		$arr_name=explode(' ', $name);
		$name_end=mb_substr($name, -2);
				
		switch ($name_end) {
			case 'я':
			case 'а':
				$icon_sex="<div class='icon_women'></div>";
				break;	
			default:
				$icon_sex="<div class='icon_men'></div>";
				break;
		}

				
		$tel = $tell[$value[0]];
		$city= $cityy[$value[0]];
		$address=$addresss[$value[0]];

		$tel=change_phone($tel);
		$email = $key;

		// <div class='tab_td' style='padding: 8px;'>
		// 				<span class='open_commodity' rel='{$offer_id2}'>
		// 					<div class='block_down' id='bb{$offer_id2}'></div>
		// 				</span>
		// 			</div>
				
		$orders_head = "
			<tr class='client tr_client' id='cli{$offer_id2}' rel-tr='$offer_id2' >
				<td style='border-bottom: 0px none;'></td>
				<td style='border-bottom: 0px none;' >
					<div class='hdiv'><div class='hdiv2'>
							<div style='display:table;'>
								<div style='display:table-cell;'>
									<div class='butOpenClient block_down' id='bb{$offer_id2}' rel='{$offer_id2}' style='cursor:pointer;'></div>
								</div>
								<div style='display:table-cell;'>
									<div class='cli_id'>
										{$offer_id2} 
									</div>
								</div>
							</div>
					</div></div>
				</td>
				<td style='border-bottom: 0px none;' >
					<div class='hdiv'><div class='hdiv2'>
								<div style='display:table;'>
							<div style='display:table-cell;'>
								{$icon_sex}
							</div>
							<div style='display:table-cell;vertical-align: middle;padding-left: 5px;'>
								<div class='tab_td go_href' date_href='/?admin=cardClient&id={$offer_id2}' style='width:15%'>
									<div class='cli_name'>
										<span class='get_name'>{$name}</span>
									</div>
								</div>
							</div>
						</div>
					</div></div>
				</td>
				<td style='border-bottom: 0px none;' >
					<div class='hdiv'><div class='hdiv2'>
						<div style='display:table;cursor:pointer;' class='write_email' rel='{$offer_id2}' >
							<div style='display:table-cell;'>
								<div class='icon_email'></div>
							</div>
							<div style='display:table-cell;vertical-align: middle;padding-left: 5px;'>
								<div class='cli_email'>
									<span class='get_mail'>{$email}</span>
								</div>
							</div>
						</div>
					</div></div>
				</td>
				<td style='border-bottom: 0px none;' >	
					<div class='hdiv'><div class='hdiv2'>		
						<div style='display:table;'>
							<div style='display:table-cell;'>
								<div class='icon_phone'></div>
							</div>
							<div style='display:table-cell;vertical-align: middle;padding-left: 5px;'>
								<div class='cli_tel'>
									<span class='get_tel'>{$tel}</span>
								</div>
							</div>
						</div>	
					</div></div>
				</td>
				<td style='border-bottom: 0px none;' >	
					<div class='hdiv'><div class='hdiv2'>		
						<div style='display:table;'>
							<div style='display:table-cell;vertical-align: middle;'>
								<div class='icon_city'></div>
							</div>
							<div style='display:table-cell;vertical-align: middle;padding-left: 5px;'>
								<div class='cli_city'>
									<span class='get_city'>{$city}</span>
								</div>
							</div>
						</div>	
					</div></div>			
				</td>
				<td style='border-bottom: 0px none;' >
					<div class='hdiv'><div class='hdiv2'>
						<div class='cli_address'>
							<span class='get_add' rel-add='{$depart2}' rel-delivery='{$delivery2}' >{$address}</span>
						</div>
					</div></div>
				</td>
				<td style='border-bottom: 0px none;' >
					<div class='hdiv'><div class='hdiv2'>
						<div class='cli_chat'>
							<div class='icon_chat' rel='{$offer_id2}'></div>
						</div>
					</div></div>
				</td>
			</tr>
			<tr class='tr_client openCline{$offer_id2}' >
					<td  style='border-bottom: 0px none;'></td>
					<td colspan=7  style='border-bottom: 0px none;padding-bottom: 0px;'>
					<div class='occc{$offer_id2}' style='display:none;margin-top: -2px;margin-bottom: 3px;'>";

			for($i=0; $i<count($value); $i++){
					// $orders_head.="<tr class='tr_client openCline{$offer_id2}'  style='display:none;'>
					// <td  style='border-bottom: 0px none;'></td>
					// <td colspan=7  style='border-bottom: 0px none;padding-bottom: 2px;'>".$order[$value[$i]]."</td>
					// </tr>";
					$orders_head.=$order[$value[$i]];
			}
		$center.="</div></td></tr>".$orders_head;	
	}

	$center.="</table>{$links}";

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

	$art_json=json_encode($art);
	$cli_json=json_encode($client);
	$tab_json=json_encode($tab_com);
	$tab2_json=json_encode($tab_com2);
	$off_sum=json_encode($offer_sum2);

	$dost=json_encode($del);
	$comm=json_encode($commissia);
	$sum_price=json_encode($sum_pri);

	$center.="
		<link href='/templates/admin/soz/style/orders_by_client20.css' type='text/css' rel='stylesheet' />
		<link href='/templates/admin/soz/style/sozClient.css' type='text/css' rel='stylesheet' />
		<link href='/templates/admin/soz/style/cardClient.css' type='text/css' rel='stylesheet' />
		<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css\">
		<script src='/templates/admin/js/i18n/datepicker-uk.js' type='text/javascript'></script>
		<script src='/templates/admin/js/i18n/datepicker-ru.js' type='text/javascript'></script>
		<script src=\"http://zoond-test.cloudapp.net:8264/socket.io/socket.io.js\"></script>	
			
		<script>
			var arr=".json_encode($arr_c).";
			var del=".json_encode($commisia).";
			var artt={$art_json};
			var client={$cli_json};
			var tab={$tab_json};
			var tab2={$tab2_json};
			var off_sum={$off_sum};
			var sum={$sum_price};
			var dost={$dost};
			var comm={$comm};

		</script>
		<script src='/templates/admin/soz/js/orders_by_client20.js'></script>
		<script src='/templates/admin/soz/js/sozClient.js'></script>
		<script src='/templates/admin/soz/js/client.js' ></script>
	";

}
