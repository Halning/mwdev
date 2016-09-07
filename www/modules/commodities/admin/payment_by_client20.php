<?
if ($_SESSION['status']=="admin"){

include "soz.php";
	
	$del_all=0;	
	$del_buy=0;	
	$del_wait=0;	
	
	$comisia_all=0; 
	$comisia_buy=0; 
	$comisia_wait=0;
	
	$real_price_all=0;
	$real_price_buy=0;
	$real_price_wait=0;

	$add_price_all=0;
	$add_price_buy=0;
	$add_price_wait=0;

	$del_all_usd=0;	
	$del_buy_usd=0;	
	$del_wait_usd=0;	
	
	$comisia_all_usd=0; 
	$comisia_buy_usd=0; 
	$comisia_wait_usd=0;
	
	$real_price_all_usd=0;
	$real_price_buy_usd=0;
	$real_price_wait_usd=0;

	$add_price_all_usd=0;
	$add_price_buy_usd=0;
	$add_price_wait_usd=0;

	$del_all_rub=0;	
	$del_buy_rub=0;	
	$del_wait_rub=0;	
	
	$comisia_all_rub=0; 
	$comisia_buy_rub=0; 
	$comisia_wait_rub=0;
	
	$real_price_all_rub=0;
	$real_price_buy_rub=0;
	$real_price_wait_rub=0;

	$add_price_all_rub=0;
	$add_price_buy_rub=0;
	$add_price_wait_rub=0;
	
	$client_count_all=0;
	$client_count_buy=0;
	$client_count_wait=0;

	$client_count_all_usd=0;
	$client_count_buy_usd=0;
	$client_count_wait_usd=0;

	$client_count_all_rub=0;
	$client_count_buy_rub=0;
	$client_count_wait_rub=0;

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
		$status="`status` in (7,8, 13)";
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
		$status="`status` in (3,4,5,6, 12)";
		$but_orr1="but_nn_active";
		$but_style1="border-bottom: 0px solid gray;color: black;";
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


	$num=1;
	$tab_com2[]=array();
	$orders_head ="";
	$sql = "SELECT * FROM `shop_orders` WHERE {$status}{$fromToDate} ORDER BY `id` DESC {$limit}";
	$res = mysql_query($sql);
	while($row=mysql_fetch_assoc($res)){
		$price=0;
		$num=0;
		$tab_lines="";
		$offer_id = $row["id"];
		$discount = $row["discount"];

		$user_id[$offer_id]=$row["user_id"];
		$date = $row["date"];
		$name = $row["name"];
		$cur_id = $row["cur_id"];
		$order_cod = $row["cod"];
		$art[$offer_id] = $row["cod"];
		$status = $row["status"];
		$email = $row['email'];
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
		$phone = change_phone($row['tel']);
		$order_count = 0;
		//$comission[$offer_id] = $row["commission"];
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

		$status_selected12 = "";
		$status_selected13 = "";
		
		$com2_selected1 = "";
		$com2_selected2 = "";
		$com2_selected3 = "";
		$com2_selected4 = "";
		$com2_selected5 = "";
		$com2_selected0 = "";
		$com2_selected6 = "";

		// $active="display:grid;";
		// $but_active="but_active";
			
		$order_comment = $row["comment_for_order"];	

	//echo $row["id"]."=".$status."<br/>";

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
		} elseif($status == 13){
			$status_selected13 = "selected";
		} 

		if($status <= 5 || $status >= 12 ){
		$select_client="
			<select size='1' name='status' id = 'select_order_status' class='color_select2' rel='{$offer_id}'>
				<option value='3' rel='{$offer_id}' {$status_selected3}>Подтвержден</option>
    			<option value='4' rel='{$offer_id}' {$status_selected4}>Оплачен клиентом</option>
    			<option value='12' rel='{$offer_id}' {$status_selected12}>Оплачен MW</option>	
    			<option value='13' rel='{$offer_id}' {$status_selected13}>Возврат</option>	
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

		$sql2 = "
		SELECT * FROM `shop_orders_coms`
		LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
		WHERE `offer_id`='{$row["id"]}' AND `count`>0;";
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
				//$groupName=SOZ::getStatusGroup($grrow["status"]);
				//$addOption="<option value='{$grrow["status"]}' selected>{$groupName}</option>";
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


				$linecolor = "";
				$status_com = $row2["com_status"];

				if($status_com == 1){
					$com_selected1 = "selected";
					$linecolor = "greenline";
					//$payment+=$com_sum;
				} elseif($status_com == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
					$offer_sum[$offer_id] -=$price;
					$order_count -= $row2["count"];
					$addOption="";
				} elseif($status_com == 3){
					$com_selected3 = "selected";
					$addOption="";
				} elseif($status_com == 0){
					$com_selected0 ="selected";
				} elseif($status_com == 4){
					$com_selected4 ="selected";
				} elseif($status_com == 5){
					$com_selected5 ="selected";
				} elseif($status_com == 6){
					$com_selected6 ="selected";
				} 		

				$offer_sum[$offer_id] +=$price;

				$basket_com_cat=SOZ::getBrandeName($row2['com_id']);
				$cat_name2=SOZ::getCategoryName($row2['com_id']);
				
				$url="/product/".$com_id."/".$row2["alias"].".html";
				
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
					<td><a href='{$url}'>{$url}</a></td>
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

		}
		
		if($offer_id<433){
			$comission[$offer_id]=round($offer_sum[$offer_id]/100*3);
		}else{
			$comission[$offer_id]=0;
		}


		$cur_name=$glb["cur"][$cur_id];
		$skidka[$offer_id]='';
		$gift2[$offer_id]='';
		$gift="";

		if($offer_id>=441){
			$delivery_price2=$delivery_price[$offer_id];
			$delivery_price3=$delivery_price[$offer_id]." {$cur_name}";
			$price3=$offer_sum[$offer_id];
			$ski='';
			if($discount==1 && $order_count>=5){
				// $ski='-150 грн';
				// $price3-=150;
				//echo $price;
				if($row["cur_id"]==1){
					$price3-=150;
					$ski='-150 грн';
				}
				if($row["cur_id"]==3){
					$price3-=500;
					$ski='-500 руб';
				}
			}elseif($discount==2 && $order_count>=5){
				$ski='-10%';
				$price3-=$price3/100*10;
			}elseif($discount==3 && $order_count>=5){
				$delivery_price3="Бесплатная";
				$delivery_price2=0;
			}
			if($offer_sum[$offer_id]>=1000){
				$gift="Платья";
			}
			$comission[$offer_id]=$ski;
			$summm=$price3+$delivery_price2;
			$del[$offer_id]=$delivery_price3;
			$skidka[$offer_id]=$ski;
			$gift2[$offer_id]=$gift;
		}else{
			$del[$offer_id]=$delivery_price[$offer_id]." {$cur_name}";
			$commissia[$offer_id]=$comission[$offer_id]." {$cur_name}";
			$summm=$offer_sum[$offer_id]+$delivery_price[$offer_id]+$comission[$offer_id];
		}
		$offer_sum2[$offer_id] = $offer_sum[$offer_id]." {$cur_name}";
		$sum_pri[$offer_id]=$summm." {$cur_name}";

		$curSql=mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`={$cur_id}; ");
		$resCur=mysql_fetch_assoc($curSql);
			if($resCur["cur_val"]!=""){
			//echo $delivery_price[$offer_id]."=".$resCur["cur_val"]."<br/>";
				$del_all+=round($delivery_price[$offer_id]/$resCur["cur_val"], 2);
				$comisia_all+=round($comission[$offer_id]/$resCur["cur_val"], 2);
				$real_price_all+=round($offer_sum[$offer_id]/$resCur["cur_val"], 2);
				$add_price_all+=round($summm/$resCur["cur_val"], 2);
			}	
			$client_count_all++;		
			if($status == 4){			
				$del_buy+=round($delivery_price[$offer_id]/$resCur["cur_val"], 2);
				$comisia_buy+=round($comission[$offer_id]/$resCur["cur_val"], 2);
				$real_price_buy+=round($offer_sum[$offer_id]/$resCur["cur_val"], 2);
				$add_price_buy+=round($summm/$resCur["cur_val"], 2);
				$client_count_buy++;
			}elseif($status == 3){
				$del_wait+=round($delivery_price[$offer_id]/$resCur["cur_val"], 2);
				$comisia_wait+=round($comission[$offer_id]/$resCur["cur_val"], 2);
				$real_price_wait+=round($offer_sum[$offer_id]/$resCur["cur_val"], 2);
				$add_price_wait+=round($summm/$resCur["cur_val"], 2);
				$client_count_wait++;
			}

		$curSqlUsd=mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`=2; ");
		$resCurUsd=mysql_fetch_assoc($curSqlUsd);

			$del_all_usd=round($del_all*$resCurUsd["cur_val"], 2);
			$comisia_all_usd=round($comisia_all*$resCurUsd["cur_val"], 2);
			$real_price_all_usd=round($real_price_all*$resCurUsd["cur_val"], 2);
			$add_price_all_usd=round($add_price_all*$resCurUsd["cur_val"], 2);
			$client_count_all_usd++;
			if($status == 4){			
				$del_buy_usd=round($del_buy*$resCurUsd["cur_val"], 2);
				$comisia_buy_usd=round($comisia_buy*$resCurUsd["cur_val"], 2);
				$real_price_buy_usd=round($real_price_buy*$resCurUsd["cur_val"], 2);
				$add_price_buy_usd=round($add_price_buy*$resCurUsd["cur_val"], 2);
				$client_count_buy_usd++;
			}elseif($status == 3){
				$del_wait_usd=round($del_wait*$resCurUsd["cur_val"], 2);
				$comisia_wait_usd=round($comisia_wait*$resCurUsd["cur_val"], 2);
				$real_price_wait_usd=round($real_price_wait*$resCurUsd["cur_val"], 2);
				$add_price_wait_usd=round($add_price_wait*$resCurUsd["cur_val"], 2);
				$client_count_wait_usd++;
			}
		$curSqlRub=mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`=3; ");
		$resCurRub=mysql_fetch_assoc($curSqlRub);

			$del_all_rub=round($del_all*$resCurRub["cur_val"], 2);
			$comisia_all_rub=round($comisia_all*$resCurRub["cur_val"], 2);
			$real_price_all_rub=round($real_price_all*$resCurRub["cur_val"], 2);
			$add_price_all_rub=round($add_price_all*$resCurRub["cur_val"], 2);
			$client_count_all_rub++;
			if($status == 4){			
				$del_buy_rub=round($del_buy*$resCurRub["cur_val"], 2);
				$comisia_buy_rub=round($comisia_buy*$resCurRub["cur_val"], 2);
				$real_price_buy_rub=round($real_price_buy*$resCurRub["cur_val"], 2);
				$add_price_buy_rub=round($add_price_buy*$resCurRub["cur_val"], 2);
				$client_count_buy_rub++;
			}elseif($status == 3){
				$del_wait_rub=round($del_wait*$resCurRub["cur_val"], 2);
				$comisia_wait_rub=round($comisia_wait*$resCurRub["cur_val"], 2);
				$real_price_wait_rub=round($real_price_wait*$resCurRub["cur_val"], 2);
				$add_price_wait_rub=round($add_price_wait*$resCurRub["cur_val"], 2);
				$client_count_wait_rub++;
			}

// echo intval($row["payment_old_price"])."=".intval($summm);

		// if(intval($row["payment_old_price"])!=intval($summm)){
		// 	$sendd="<span class='send_mail maill set_sent{$offer_id}' rel='{$offer_id}' rel2={$email} style='cursor:pointer;text-decoration:underline'>
		// 							<div class='icon_send'></div>
		// 						</span>	";
		// }else{
		// 	$sendd="<span class='send_mail maill' rel='{$offer_id}' rel2={$email} style='cursor:pointer;text-decoration:underline'>
		// 				<div class='icon_sent'></div>
		// 				<!--<span style='text-decoration:underline'>отправлен</span>-->
		// 			</span>	";
		// }
		if($row["payment_sent_mail"]==0){
			$sendd="<span class='send_mail maill set_sent{$offer_id}' rel='{$offer_id}' rel2={$email} style='cursor:pointer;text-decoration:underline'>
									<div class='icon_send'></div>";
		}elseif($row["payment_sent_mail"]>=1){
			$sendd="<span class='send_mail maill' rel='{$offer_id}' rel2={$email} style='cursor:pointer;text-decoration:underline'>
						<div class='icon_sent' rel-was-sent={$row["payment_sent_mail"]} ></div>
					</span>	";
		}	
		
		$all_line.="
				<tr class='forsearch {$but_active}' style='{$active}' id='client_open{$row["id"]}' >
				<td class='clear_bottom_line' style='border-bottom: 0px solid #CCC;' ></td>
					<td style='border-bottom: 0px solid #CCC;'  >
						<div class='hdiv'><div class='hdiv2'>
							<div style='display:table;'>
								<div style='display:table-cell;padding-right: 3px;'>
									<span class='cli_open' rel='{$row["id"]}' >
										<div class='block_down' id='bb{$row["id"]}'></div>
									</span>
								</div>
								<div style='display:table-cell;'>
									<span class=' cli_cod go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$row["cod"]} </span>
								</div>
							</div>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_id go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$row["id"]} </span>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_date go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$row["date"]} </span>
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
							<span class='cli_name go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$row["name"]}</span>
						</div></div>	
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_count go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$order_count}</span>
						</div></div>
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_summa go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$summm} {$glb["cur"][$cur_id]}</span>
						</div></div>	
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_commodity go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$offer_sum[$offer_id]}</span>							
						</div></div>	
					</td>
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_commission go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$comission[$offer_id]}</span>
						</div></div>
					</td>		
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='cli_delivery go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$delivery_price[$offer_id]}</span>							
						</div></div>
					</td>		
					<td style='border-bottom: 0px solid #CCC;' >
						<div class='hdiv'><div class='hdiv2'>
							<div style='display:table;'>
								<div style='display:table-cell;vertical-align: middle;width: 35px;'>
									<div style='margin: 0px auto;display: inline-block;'>
										{$sendd}						
									</div>	
								</div>
								<div style='display:table-cell;width: 34px;padding-right: 11px;position: relative;vertical-align: middle;'>	
								<div style='display: inline-block;margin: 0px auto;'>			
									<span class='cli_cont coo{$row["id"]}' rel='{$row["id"]}'>
										<div class='cli_info cli_info_open{$row["id"]}' style='display:none'>
											<table width='100%' >
												<tr><td class='info_td'></td><td></td></tr>
												<tr><td class='info_td'>E-mail:</td><td>{$email}</td></tr>
												<tr><td class='info_td'>Телефон:</td><td>{$phone}</td></tr>
												<tr><td class='info_td'>Город:</td><td>{$city}</td></tr>
												<tr><td class='info_td'>Адрес:</td><td>{$address}</td></tr>
											</table>
										</div>
										<div class='bor_i{$row["id"]}' style='position: absolute;top: 7px;left: 5px;'>
											<div class='icon_cont icc' id='ic{$row["id"]}' style='float:left;'></div>
											<span class='bcc' id='bc1{$row["id"]}'> 
												<span class='block_down1 b_cont' id='bc{$row["id"]}' ></span>
											</span>
										</div>
									</span>
								</div>
								</div>
								<div style='display:table-cell;width: 28px;vertical-align: middle;'>
									<div class=' cli_xls'>
										<div class='bor_xls'>
											<a href='/email/download_excel.php?exportIdd={$row["id"]}'>
												<div class='icon_xls'></div>
											</a>
										</div>
									</div >
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
				<tr class='{$but_active}' style='{$active}' >
				<td class='clear_bottom_line' style='border-bottom: 0px solid #CCC;' ></td>
					<td colspan=11 style='border-bottom: 0px solid #CCC;padding-top: 0px;' >
						<div class='table_line table_commodity{$row["id"]}' style='display:none'>
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

	}	
	//var_dump($lines);

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
								<tr><td style="padding: 5px;" align="right">Скидка</td><td style="padding: 5px;" class="skidka" align="center"></td></tr>
								<tr><td style="padding: 5px;" align="right">Подарок</td><td style="padding: 5px;" class="gift" align="center"></td></tr>
								<!--<tr><td style="padding: 5px;" align="right">Комиссия(3%)</td><td style="padding: 5px;" class="comm" align="center"></td></tr>-->
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

	//$payment_wait=$payment_all-$payment;
	/*$center .="<div style='padding: 20px; font-size: 17px;'>
					ИТОГО К ОПЛАТЕ: <span class='pay'>{$payment_all} грн</span> <span style='margin-left: 30px;'/> ОПЛАЧЕНО:  <span class='pay'>{$payment} грн</span> <span style='margin-left: 30px;'/> ОЖИДАЕТСЯ:  <span class='pay'>{$payment_wait} грн</span>     
				</div>";*/
				
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
							<div style='display:table-cell;vertical-align: middle;padding-left: 9px;'>
								<select class='change_tab_cup'  >
									<option value='1'>UAH</option>
									<option value='2'>USD</option>
									<option value='3'>RUB</option>
								</select>
							</div>
						</div>
					</td>
					<td style='color: white;background: #313131;border: 1px solid white;' >К ОПЛАТЕ</td>
					<td style='color: white;background: #313131;border: 1px solid white;' >ОПЛАЧЕНО</td>
					<td style='color: white;background: #313131;border: 1px solid white;border-right: 1px solid #313131;' >ОЖИДАЕТСЯ</td>
				</tr>
				<tr>
					<td>ПОСТУПИЛО ЗАКАЗОВ</td>
					<td class='tabCur' rel-uah='{$client_count_all}' rel-usd='{$client_count_all_usd}' rel-rub='{$client_count_all_rub}' >{$client_count_all}</td>
					<td class='tabCur' rel-uah='{$client_count_buy}' rel-usd='{$client_count_buy_usd}' rel-rub='{$client_count_buy_rub}' >{$client_count_buy}</td>
					<td class='tabCur' rel-uah='{$client_count_wait}' rel-usd='{$client_count_wait_usd}' rel-rub='{$client_count_wait_rub}' >{$client_count_wait}</td>
				</tr>
				<tr>
					<td>СУММА К ОПЛАТЕ</td>
					<td class='tabCur' rel-uah='{$add_price_all}' rel-usd='{$add_price_all_usd}' rel-rub='{$add_price_all_rub}' >{$add_price_all}</td>
					<td class='tabCur' rel-uah='{$add_price_buy}' rel-usd='{$add_price_buy_usd}' rel-rub='{$add_price_buy_rub}' >{$add_price_buy}</td>
					<td class='tabCur' rel-uah='{$add_price_wait}' rel-usd='{$add_price_wait_usd}' rel-rub='{$add_price_wait_rub}' >{$add_price_wait}</td>
				</tr>
				<tr>
					<td>СТОИМОСТЬ ТОВАРА</td>
					<td class='tabCur' rel-uah='{$real_price_all}' rel-usd='{$real_price_all_usd}' rel-rub='{$real_price_all_rub}' >{$real_price_all}</td>
					<td class='tabCur' rel-uah='{$real_price_buy}' rel-usd='{$real_price_buy_usd}' rel-rub='{$real_price_buy_rub}' >{$real_price_buy}</td>
					<td class='tabCur' rel-uah='{$real_price_wait}' rel-usd='{$real_price_wait_usd}' rel-rub='{$real_price_wait_rub}' >{$real_price_wait}</td>
				</tr>
				<tr>
					<td>КОМИССИЯ (3%)</td>
					<td class='tabCur' rel-uah='{$comisia_all}' rel-usd='{$comisia_all_usd}' rel-rub='{$comisia_all_rub}' >{$comisia_all}</td>
					<td class='tabCur' rel-uah='{$comisia_buy}' rel-usd='{$comisia_buy_usd}' rel-rub='{$comisia_buy_rub}' >{$comisia_buy}</td>
					<td class='tabCur' rel-uah='{$comisia_wait}' rel-usd='{$comisia_wait_usd}' rel-rub='{$comisia_wait_rub}' >{$comisia_wait}</td>
				</tr>
				<tr>
					<td style='border-bottom: 1px solid #313131;' >СТОИМОСТЬ ДОСТАВКИ</td>
					<td class='tabCur' rel-uah='{$del_all}' rel-usd='{$del_all_usd}' rel-rub='{$del_all_rub}' >{$del_all}</td>
					<td class='tabCur' rel-uah='{$del_buy}' rel-usd='{$del_buy_usd}' rel-rub='{$del_buy_rub}' >{$del_buy}</td>
					<td class='tabCur' rel-uah='{$del_wait}' rel-usd='{$del_wait_usd}' rel-rub='{$del_wait_rub}' >{$del_wait}</td>
				</tr>
			</table>
		</div></div>
			<br/>
		";
	$center .= "
		<div class='left_icon' >
			<div class='tab_td2'>
				<div class='back_icon'>
		 			<div class='icon_calc' style='cursor:pointer;' ></div>
		 		</div>
			</div>		 	
		 	<div class='tab_td2'>
		 		<div class='back_icon'>
		 			<div class='icon_info' rel-get='0' ></div>
		 		</div>
		 	</div>
		</div>
		<div style='position: relative;height: 28px;'>
			<div style='display:table;position: absolute;right: 2px;' class='but_nn'>
				<div class='{$but_orr1} tab_nn' style='display:table-cell;{$but_style1}' rel=1 >
					<div>
						Оплата-Клиенты
					</div>
				</div>
				<div class='{$but_orr2} tab_nn' style='display:table-cell;{$but_style2}' rel=2 >
					<div>
						Архив
					</div>
				</div>
			</div>
		</div>
	<table class = 'sortable tab_center_th payment_client'>
		<tr>
			<th></th>
			<th>№</th>
			<th>ID</th>
			<th>Дата</th>
			<th>Статус</th>
			<th>Клиент</th>
			<th>Единиц</th>
			<th>Сумма</th>
			<th>Товар</th>
			<th>Скидка</th>
			<th>Доставка</th>
			<th></th>
		</tr>
		";
		$art_json=json_encode($art);
		$cli_json=json_encode($client);
		$tab_json=json_encode($tab_com);
		$tab2_json=json_encode($tab_com2);
		$off_sum=json_encode($offer_sum2);
		$skidka_json=json_encode($skidka);
		$gift_json=json_encode($gift2);


		$dost=json_encode($del);
		$comm=json_encode($commissia);
		$sum_price=json_encode($sum_pri);

		//echo $tab2_json;

		//var_dump($tab_com2);

		$center.="
					<link href='/templates/admin/soz/style/payment_by_client20.css' type='text/css' rel='stylesheet' />
					<script src='/templates/admin/js/i18n/datepicker-uk.js' type='text/javascript'></script>
					<script src='/templates/admin/js/i18n/datepicker-ru.js' type='text/javascript'></script>
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
						var skidka_json={$skidka_json};
						var gift_json={$gift_json};
					</script>					
					<script src='/templates/admin/soz/js/payment_by_client20.js'></script>";
	$center.=$all_line."</table>{$links}";
	$center.="
		<div class='rekvizit' style='display:none'>
			<div class='line_rek'>
				<div class='close_rek icon_close'></div>
				<div style='display:table;width: 100%;' class='tab_rek'>
					<div style='display:table-row' >
						<div style='display:table-cell;width: 12px;' >
						</div>
						<div style='display:table-cell' class='add_row' >
							<div style='display:table;width: 100%;'>
								<div style='display:table-cell;padding-left: 15px;'>
									<div class='icon_addrow'></div>
								</div>
								<div style='display:table-cell;vertical-align: middle;padding-right: 15px;'>
									Добавить строку
								</div>
							</div>
						</div>
						<div style='display:table-cell;' class='rek_n'>
							РЕЗВИКИТИ ПОЛУЧАТЕЛЯ
						</div>
					</div>
				</div>
				<table class='tab_rez' style='width:100%;margin-top: 3px;'></table>
			</div>
		</div>
	";
}
		
