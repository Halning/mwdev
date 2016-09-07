<?

if ($_SESSION['status']=="admin"){

include "soz.php";

	$client_count_all=array();
	$client_count_buy=array();
	$client_count_wait=array();

	$add_price_all=0;
	$add_price_buy=0;
	$add_price_wait=0;

	$del_all=array();
	$del_buy=array();
	$del_wait=array();


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
		$status="`status` in (7)";
		$status2="`status` in (7)";
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
		$status="`status` IN (4,5,6)";
		$status2="`status` in (3,4,5,6, 12,13)"; //`status` in (3,4,5,6)
		$but_orr1="but_nn_active";
		$but_style1="border-bottom: 0px solid gray;color: black;";
	}

	$mysql_len=mysql_query("
		SELECT * FROM `sup_group` WHERE {$status} ORDER BY `group_id` DESC
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

//`status` IN (4,5,6,7)
	$sql0 ="SELECT * FROM `sup_group` WHERE {$status}{$fromToDate} ORDER BY `group_id` DESC {$limit}"; 
	$res0 = mysql_query($sql0) or die(mysql_error());
	while($r=mysql_fetch_assoc($res0)){
		$group_id = $r["group_id"];
		$date[$group_id]=$r['date'];
		$status = $r["status"];
		$sup_id = $r["sup_id"];
		$sum_opt = 0;
		$total_count = 0;
		//$lines = "";
		$total_com_price = 0;

		$write_delii[$group_id]=$r["write_payment"];
		$ssent[$group_id]=$r["delivery_sent"];
		$cclip[$group_id]=$r["payment_clip2"];
		$clip_name[$group_id]=$r["payment_clip_file2"];
		$ttnn[$group_id]=$r["ttn"];
		$ttnd[$group_id]=$r["ttn-date"];
		
		
		if($cclip[$group_id]==1){
			$file2=end(explode(".",$r["payment_clip_file2"])); 
			$urlFile="http://".$_SERVER["HTTP_HOST"]."/uploads/delivery_P_MW/".$group_id.".".$file2;
			$ghead=get_headers($urlFile);
			$head=substr($ghead[0], 9, 3);
			

			if($head!="200"){
				$size_kb[$group_id] = "File is empty";
			}else{
				$size_kb[$group_id] = filesize("uploads/delivery_P_MW/".$group_id.".".$file2);
			}
			//$size_kb[$group_id] = filesize("uploads/delivery_P_MW/".$group_id.".".$file2);
			$clip_file[$group_id] = $group_id.".".$file2;
		}

		// $active[$group_id]="display:grid;";
		// $but_active[$group_id]="but_active";

		$selected_group4 = "";
		$selected_group5 ="";
		$selected_group6 ="";

		//echo $group_id."=".$status."<br/>";
		$client_count_all++;
		if($status == 4) {
			$selected_group4 = "selected";
		} elseif($status == 5) {
			$selected_group5 = "selected";
		} elseif($status == 6){
			$selected_group6 = "selected";
		}
		
		// $sql3 = "SELECT * FROM `suppliers` WHERE `sup_id` = {$sup_id}";
		// $res3 = mysql_query($sql3);
		// if($row3 = mysql_fetch_assoc($res3)){
		// 	$sup_name = $row3["sup_name"];
		// }
		$bbb=mysql_query("SELECT * FROM `shop_categories` WHERE `categories_of_commodities_parrent`=10 AND `categories_of_commodities_ID`={$sup_id}; ");
		$b=mysql_fetch_assoc($bbb);
		$sup_name = $b["cat_name"];
		$sup_name_id = $b["categories_of_commodities_ID"];

			$bcc=mysql_query("SELECT * FROM `brenda_contact` WHERE `com_id`='{$sup_name_id}';") or die(mysql_error());
		
				if($bcc) {
					$bb=mysql_fetch_assoc($bcc);
					$name[$group_id]=$bb["rek_pa_name"];
					$bank[$group_id]=$bb["rek_pa_bank"];
					$fl[$group_id]=$bb["rek_pa_plat"];
					$chet[$group_id]=$bb["rek_pa_shet"];
					$priceSk[$group_id]=$bb["uc_pr_skidka"];
					$optSk[$group_id]=$bb["uc_opt_skidka"];
					$tel[$group_id]=explode(";",$bb["cont_phone"]);
					//$tel[$group_id]=str_replace(";",",",$tel[$group_id]);
					//$tel[$group_id]=substr($tel[$group_id][1],2,strlen($tel[$group_id][1]));
					$emaill[$group_id]=$bb["cont_mail"];
					$emaill[$group_id]=str_replace(";",",",$emaill[$group_id]);
					$emaill[$group_id]=substr($emaill[$group_id],2,strlen($emaill[$group_id]));
					$prim[$group_id]=$bb["rek_pa_dop"];

					// if(strpos($bb["uc_pr_delivery"],"MW")!==false){
					//	$rrr=0;
					// 	if(is_numeric($r['del_price'])){
					//		$rrr=$r['del_price'];
					// 	}
					// 	$delivery[$group_id]=$bb["uc_pr_delivery"]." - ".$rrr." грн";
					// }else{
					//	$delivery[$group_id]=$rrr." грн";;
					// }
					
					if($r['del_price']){
						$delivery_price=$r['del_price'];
						$delivery[$group_id]=$r['del_price']." грн";;
					}else{
						$delivery_price=0;
						$delivery[$group_id]="0 грн";;
					}
					if($r['ttn-send-city']){
						$ttnCity[$group_id]=$r['ttn-send-city'];
					}else{
						$ttnCity[$group_id]="City";
					}
				}

				$jj=0;

				$sql2 = "SELECT *, `shop_orders_coms`.`cur_id` AS 'curr' 
					FROM `shop_orders` 
					INNER JOIN `shop_orders_coms` ON `shop_orders`.`id`=`shop_orders_coms`.`offer_id`
					LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
					WHERE `group_id` = {$group_id} AND {$status2} ";
				$res2 = mysql_query($sql2);
				while($row2 = mysql_fetch_assoc($res2)){
						$com_id = $row2["com_id"];
						$size = $row2["com"];
						$count = $row2["count"];
						$price = $row2["price"];
						$cur_id = $row2["curr"];
						$cod = $row2["cod"];
					
						$from_url = $row2["from_url"];
						$com_name = $row2["com_name"];
						
						if($row2["com_color"]==""){
							$color = strip_tags(get_color_to_order($com_id));
						}else{
							$color = $row2["com_color"];
						}

					$curSql=mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`={$cur_id}; ");
					$curRes=mysql_fetch_assoc($curSql);
					// `uc_opt_skidka`,`uc_pr_skidka`


						$comment = $row2["man_comment"];
						$com_status = $row2["com_status"];

						$stPrice[$group_id]=0;

						if($com_status != 2){
							$total_count +=$count;
							$com_price = round($price/$curRes["cur_val"]);
							$fromOpt=intval($bb["ot_min_price"]);
							$fromRoz=intval($bb["ot_min_pr_price"]);
							if($com_price==$row2["commodity_price"]){
								$com_price-=$com_price/100*$priceSk[$group_id];
							}
							if($com_price==$row2["commodity_price2"]){
								$com_price-=$com_price/100*$optSk[$group_id];
								$stPrice[$group_id]=1;
							}
							$com_price_one = $com_price*$count;
						}


						$commodityID=$row2['commodity_ID'];
						$name_com=$row2["com_name"];
						
						$basket_com_cat=SOZ::getBrandeName($commodityID);
						$cat_name2=SOZ::getCategoryName($commodityID);

						$addOption="";
						$groupName=SOZ::getStatusGroup($status);
						$addOption="<option selected>{$groupName}</option>";
					
						$com_selected0='';
						$com_selected1='';
						$com_selected2='';
						$com_selected3='';
						$com_selected4='';
						$com_selected5='';
						$com_selected6='';

						if($com_status == 1){
							$com_selected1 = "selected";
							$linecolor = "greenline";
						} elseif($com_status == 2){
							$com_selected2 = "selected";
							$linecolor = "redline";
							$addOption="";
						} elseif($com_status== 3){
							$com_selected3 = "selected";
							$addOption="";
						} elseif($com_status == 0){
							$com_selected0 ="selected";
						} elseif($com_status == 4){
							$com_selected4 ="selected";
						} elseif($com_status == 5){
							$com_selected5 ="selected";
						} elseif($com_status == 6){
							$com_selected6 ="selected";
						} 

				$lines[$group_id].="
				<tr cat_name='{$cat_name2}' brenda_name='{$sup_name}' >
					<td>{$basket_com_cat}</td>
					<td>{$cod}</td>
					<td>{$color}</td>
					<td>{$size}</td>
					<td>{$count}</td>
					<td>грн</td>
					<td>{$com_price}</td>
					<td>{$com_price_one}</td>
					<td><a href ='{$from_url}'>Источник</a></td>
					<td>
						<select size='1' name='status' id = 'select_status_com' disabled>
							<option value='0' {$com_selected0}></option>
							<option value='1' {$com_selected1}>Есть в наличии</option>
			    			<option value='2' {$com_selected2}>Нет в наличии</option>
			    			<option value='3' {$com_selected3}>Замена</option>
			    			{$addOption}
						</select>
					</td>
					<td>{$comment}</td>
				</tr>";
			if($com_status != 2){
				$arr[$group_id][$jj]=array(
						"cat_name"=>$cat_name2,
						"name_com"=>$name_com,
						'art'=>$cod,
						'color'=>$color,
						'size'=>$size,
						"count"=>$count,
						"cur"=>"грн",
						"com_price_one"=>$com_price,
						"com_price"=>$com_price_one,
						"from_url"=>$from_url
					);
				$jj++;

				$client_count_all[$group_id]=1;
				$add_price_all+=$count;
				$del_all[$group_id]=intval($delivery_price);
				if($status==4){
					$client_count_wait[$group_id]=1;
					$add_price_wait+=$count;
					$del_wait[$group_id]=intval($delivery_price);
				}elseif ($status==5 || $status==6) {
					$add_price_buy+=$count;
					$client_count_buy[$group_id]=1;
					$del_buy[$group_id]=intval($delivery_price);
				}
			}
				

			if($name[$group_id]){
				$names="";
				$name_a=explode(" ", $name[$group_id]);	
						
				$names=mb_ucfirst($name_a[0], "utf-8");
				if($name_a[1]){
					$names.=" ".mb_ucfirst(substr($name_a[1],0,2), "utf-8").".";
				}
				if($name_a[2]){
					$names.=mb_ucfirst(substr($name_a[2],0,2), "utf-8").".";	
				}
			}

			if($ssent[$group_id]==0){
				$signal_sent="<div class='icon_send'></div>";
			}	elseif($ssent[$group_id]>=1){
				$signal_sent="<div class='icon_sent' rel-was-sent='{$ssent[$group_id]}' ></div>";	
			}	
			
			if($cclip[$group_id]==0) {
				$clip="<div class='icon_clip_will' style='cursor:pointer;' rel='{$group_id}'></div>";
			}elseif($cclip[$group_id]==1) {
				$clip="<div class='icon_clip_was icw{$group_id}' style='cursor:pointer;' rel='{$group_id}' rel-file='{$clip_file[$group_id]}' rel-size='{$size_kb[$group_id]}' rel-name='{$clip_name[$group_id]}'></div>";	
			}
			// if($write_delii[$group_id]!=""){
			$write_deli=$write_delii[$group_id];
			if($write_deli==""){
				$putInfoColor="icon_info_white";
				$putDownColor="block_down";
				$putInfo="wind_o2";
			}else{
				$putInfoColor="icon_info_orange";
				$putDownColor="block_down1_orange";
				$putInfo="wind_o2_orange";
			}
			// }else{
			// 	$write_deli="Примечание к заказу. Сюда информация вписиваеться вручно";
			// }
			if($ttnn[$group_id]!=""){
				$ttn=$ttnn[$group_id];
			}else{
				$ttn="000000000000";
			}
			// if($ttnd[$group_id]!=""){
			// 	$ddd=explode(" ", $ttnd[$group_id]);
			// 	$ttn_date=$ddd[0];
			// }else{
			// 	$ttn_date="01.01.2001";
			// }

			$group_head[$group_id] ="<tr class='tab_trr tab_up forsearch' id='gh{$group_id}' style='{$active[$group_id]}' >
							<td class='tab_tdd' style='border-bottom: 0px solid #ccc;'></td>
			 				<td class='tab_tdd' style='border-bottom: 0px solid #ccc;border-left: 0px solid;'>
			 					<div class='hdiv'><div class='hdiv2'>
								<div style='display:table'>
									<div style='display:table-cell;padding-right: 3px;'>
										<span class='but_open_win' rel='{$group_id}' style='cursor:pointer'>
											<div class='block_down' id='bb{$group_id}' ></div>
										</span>
									</div>
									<div style='display:table-cell'>
										{$group_id}
									</div>
								</div>
								</div></div>
			 				</td>
			 				<td class='tab_tdd' style='border-bottom: 0px solid #ccc;border-left: 0px solid;'>
			 					<div class='hdiv'><div class='hdiv2 cli_date'>
			 						{$date[$group_id]}
			 					</div></div>
			 				</td>

			 				<td class='tab_tdd' style='border-bottom: 0px solid #ccc;border-left: 0px solid;'>
			 					<div class='hdiv'><div class='hdiv2'>
			 					<select size='1' name='status' id = 'select_group_status' class='color_select_group3' rel='{$group_id}'>
									<option value='4' rel='{$group_id}' {$selected_group4}>Оплачен поставщику</option>
				    			<!--	<option value='5' rel='{$group_id}' {$selected_group5}>Доставка на склад MW</option>-->	
				    				<option value='5' rel='{$offer_id}' {$selected_group5}>Отправлен</option>
				    				<option value='6' rel='{$offer_id}' {$selected_group6}>Доставлен</option>		
				        		</select>
				        		</div></div>
			 				</td>

			 				<td class='tab_tdd' style='border-bottom: 0px solid #ccc;border-left: 0px solid;'>
			 				<div class='hdiv'><div class='hdiv2'>
			 					<div class='line_div gett_cou{$group_id}'>
			 						{$total_count}
			 					</div></div>
			 				</td>

			 				<td class='tab_tdd' style='border-bottom: 0px solid #ccc;border-left: 0px solid;'>
			 				<div class='hdiv'><div class='hdiv2'>
			 					<div class='line_div gett_del{$group_id}'>
			 						{$delivery[$group_id]}
			 					</div></div>
			 				</td>

			 				<td class='tab_tdd' style='border-bottom: 0px solid #ccc;border-left: 0px solid;'>
			 					<div class='hdiv'><div class='hdiv2 write_db' rel-id='{$group_id}' rel-db-tab='ttn' style='cursor: pointer;' >{$ttn}</div></div>
			 				</td>

			 				<td class='tab_tdd' style='border-bottom: 0px solid #ccc;border-left: 0px solid;'>
			 				<div class='hdiv'><div class='hdiv2'>
			 					<div class='wind_o open_backg' rel='{$group_id}' style='cursor:pointer;display:table;'>
									<div style='display:table-cell;padding-left: 5px;vertical-align: middle;'>
										<div class='icon_city' id='ch{$group_id}'></div>
									</div>
									<div style='display:table-cell;padding-left: 5px;vertical-align: middle;' id='addCity{$group_id}' >
										{$ttnCity[$group_id]}
									</div>
								</div>
								</div></div>
			 				</td>

			 				<td class='tab_tdd' style='border-bottom: 0px solid #ccc;border-left: 0px solid;'>
			 					<div class='hdiv'><div class='hdiv2'>
			 						{$names}
			 					</div></div>
			 				</td>

			 				<td class='tab_tdd' style='border-bottom: 0px solid #ccc;border-left: 0px solid;'>
			 					<div class='hdiv'><div class='hdiv2'>
			 						{$tel[$group_id][1]}
			 					</div></div>
			 				</td>

			 				<!--<td class='tab_tdd' style='border-bottom: 0px solid #ccc;border-left: 0px solid;'>
			 					<div class='hdiv'><div class='hdiv2 write_db set_date{$group_id}' rel-id='{$group_id}' rel-db-tab='ttn-date' >{$ttn_date}</div></div>
			 				</td>-->

			 				<td class='tab_tdd' style='border-bottom: 0px solid #ccc;border-left: 0px solid;'>
			 					<div class='hdiv'><div class='hdiv2'>
			 					<div style='display:table; margin: -3px;margin-right: 3px;'>
			 						<div class='tab_td2 send_ac{$group_id}' style='padding-right: 4px;' >
										<span class='maill sentt$group_id' rel='{$group_id}' rel2='{$emaill[$group_id]}' rel-status={$stPrice[$group_id]} >
											{$signal_sent}
										</span>
									</div>
									<div class='tab_td2 change_clip{$group_id}'  style='padding-right: 4px;' >
										{$clip}
									</div>
									<div class='tab_td2 '  style='padding-right: 4px;' >
										<div class='icon_print' rel='{$group_id}' rel-cat='{$sup_id}' ></div>
									</div>
									<div class='tab_td2' style='padding-right: 4px;position: relative;' >
										<div class='{$putInfo} open_backg' rel='{$group_id}' style='display:table;'>
											<div style='display:table-cell;padding-left: 3px;'>
												<div class='{$putInfoColor} iiw{$group_id}'></div>
											</div>
											<div style='display:table-cell;vertical-align: middle;padding-left: 2px;padding-right:6px;'>
												<div class='{$putDownColor} bbc{$group_id}' ></div>
											</div>
										</div>		
										<div class='wind_names2' id='open_win2{$group_id}' style='display:none;right: 0px;margin-right: 2px;max-height: 100px;width: 300px;height: 100px;'>
											<table>
												<tr><td></td></tr>
												<tr>
													<td style='font-weight:100;width:300px;height: 90px;' contenteditable='true' class='write_tab' rel='{$group_id}'>{$write_deli}</td>
												</tr>
												<tr><td></td></tr>
											</table>
										</div>
									</div>
			 					</div>
			 					</div></div>
			 				</td>
			 	";
		}
	}

	$cca=count($client_count_all);
	$ccb=count($client_count_buy);
	$ccw=count($client_count_wait);

	$da=array_sum($del_all);
	$db=array_sum($del_buy);
	$dw=array_sum($del_wait);

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
					<td style='color: white;background: #313131;border: 1px solid white;' >ПОСТУПИЛЬ</td>
					<td style='color: white;background: #313131;border: 1px solid white;' >ОТПРАВЛЕНО</td>
					<td style='color: white;background: #313131;border: 1px solid white;border-right: 1px solid #313131;' >ОЖИДАЕТ</td>
				</tr>
				<tr>
					<td>ПОСТАВОК</td>
					<td>{$cca}</td>
					<td>{$ccb}</td>
					<td>{$ccw}</td>
				</tr>
				<tr>
					<td>ТОВАРОВ</td>
					<td>{$add_price_all}</td>
					<td>{$add_price_buy}</td>
					<td>{$add_price_wait}</td>
				</tr>
				<tr>
					<td style='border-bottom: 1px solid #313131;' >СТОИМОСТЬ ДОСТАВКИ</td>
					<td>{$da}</td>
					<td>{$db}</td>
					<td>{$dw}</td>
				</tr>
			</table>
		</div></div>
			<br/>

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
	$center .= "
		<div class='left_icon' >
			<div class='tab_td2'>
				<div class='back_icon'>
		 			<div class='icon_calc' style='cursor:pointer;' ></div>
		 		</div>
			</div>		 	
		 	<div class='tab_td2'>
		 		<div class='back_icon'>
		 			<div class='icon_info' style='cursor:pointer;' rel-get='1' ></div>
		 		</div>
		 	</div>
		</div>
		<div style='position: relative;height: 28px;'>
			<div style='display:table;position: absolute;right: 2px;' class='but_nn'>
				<div class='{$but_orr1} tab_nn' style='display:table-cell;{$but_style1}' rel=1 >
					<div>
						Доставка П-MW
					</div>
				</div>
				<div class='{$but_orr2} tab_nn' style='display:table-cell;{$but_style2}' rel=2 >
					<div>
						Архив
					</div>
				</div>
			</div>
		</div>
		<table class = 'sortable delivery_p_mw'>
			<tr>
				<th></th>
				<th>№</th>
				<th>Дата</th>
				<th>Статус</th>
				<th>Единиц</th>
				<th>Оплата доставки</th>
				<th>ТТН</th>
				<th>Пункт отправки</th>
				<th>Отправитель</th>
				<th>Контакты</th>
				<!--<th>Дата прибытия</th>-->
				<th style='width:120px;'></th>
			</tr>
	";

	if($group_head){
		foreach ($group_head as $group_idey => $value) {
				$center.=$group_head[$group_idey]."</tr>";
				$center.="<tr class='{$but_active[$group_idey]}' style='{$active[$group_idey]}'><td style='border-bottom: 0px solid #ccc;'></td><td colspan='10' style='padding:0px;border-bottom: 0px solid #ccc;' >
							<div class='line_b open_commodity{$group_idey}' style='display:none;margin-bottom: 3px;' >
								<table class ='c2_group_hide sortable' style='border:0px solid;'>
								<tr>
									<th>Бренд</th>
									<th>Артикул</th>
									<th>Цвет</th>
									<th>Размер</th>
									<th>Кол-во</th>
									<th>Валюта</th>
									<th>Цена</th>
									<th>Сумма</th>
									<th>Ссылка</th>
									<th>Статус</th>
									<th>Комментарий</th>";
				$center.=$lines[$group_idey]."</table></div></td></tr>";
				// $center.="</td></tr>";
		}
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
						<div class='names' style='text-align:left;'>Оплата доставки: <span id='get_deli'></span> </div>
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
	$center.="
		<div class='send_body' style='display:none'>
			<div class='send_window'>
				<div id='size_w'>
					<div class='icon_close icon_close2'></div>
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
							<input type='text' class='tab_send_subject' value=' ' />
						</td>
					</tr>
				</table>
				<hr style='width: 878px;' />
					<div class='sent_html'>
						Body
					</div>
				<hr style='width: 878px;' />
				<div>				
					<button class='close_window'>Отмена</button>
					<span class='sent_order'> Отправить</span>	
				</div>
			</div>
		</div>
		";
	//--For print----
	$center.="
		<div class='pri_body' style='display:none;'>
			<div class='pri_head'>
				<div class='print_close'></div>
				<div class='paperA4'>
					<center>
			 			Print MW
			 		</center>
		 		</div>
			</div>
			<div class='but_pri'>
				<div class='pri_click'>Print</div>
			</div>
		</div>
	";

	$get_tab=json_encode($arr);
	$center.="
		<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css\">
		<!--for print-->
		<link href='/templates/admin/print/mw_print.css' type='text/css' rel='stylesheet' />
		<script src='/templates/admin/print/mw_print.js' type='text/javascript'></script>
		<script src='/templates/admin/print/jQuery.print-master/jQuery.print.js' type='text/javascript'></script>
		
		<link href='/templates/admin/soz/style/order20.css' type='text/css' rel='stylesheet' />
		<script src='/templates/admin/js/i18n/datepicker-uk.js' type='text/javascript'></script>
		<script src='/templates/admin/js/i18n/datepicker-ru.js' type='text/javascript'></script>					
					
		<script>
			var get_com=".$get_tab.";
		</script>
		<script src='/templates/admin/soz/js/sup_delivery20.js' ></script>
	";
}


