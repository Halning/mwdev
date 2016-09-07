<?
if ($_SESSION['status']=="admin"){

include "soz.php";
	
	$payment_sklad=0;
	$payment_sobr=0;
	$payment_send=0;
	$payment_wait=0;

	$comm_sklad=0;
	$comm_sobr=0;
	$comm_send=0;
	$comm_wait=0;

	$devi_sklad=0;
	$devi_sobr=0;
	$devi_send=0;
	$devi_wait=0;

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
		$status2="";
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
		$status="`status` in (4,5,6,8, 12)";
		$status2="AND c.`status` IN (1,2,3,4,5,6)";
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
	$sql = "SELECT * FROM `shop_orders` WHERE {$status}{$fromToDate} ORDER BY `id` DESC {$limit};";
	$res = mysql_query($sql);
	while($row=mysql_fetch_assoc($res)){
		$tab_lines="";
		$offer_id = $row["id"];
		$flag[$offer_id]=0;

		$date = $row["date"];
		$name = $row["name"];
		$order_cod = $row["cod"];
		$art[$offer_id] = $row["cod"];
		$status = $row["status"];
		$email = $row['email'];
		$phone = change_phone($row['tel']);
		$order_count = 0;
		$comission[$offer_id] = $row["commission"];
		$delivery_price[$offer_id] = $row["delivery_price"];

		$delivery=$row["delivery"];
		if($delivery==0){
			$delivery=$row["name_delivery"];
		}else{
			$delSql=mysql_query("SELECT * FROM `shop_delivery` WHERE `id`='{$delivery}'; ");
			$delRes=mysql_fetch_assoc($delSql);
			$delivery=$delRes["name"];
		}

		$payment="";
        if($row["payment_MW"]==1){
        	$payment=" и оплачен";
        }

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

		if(strpos($city, ",")!==false){
			$arr_city=explode(",",$city);
			$city2=$arr_city[0];
		}else{
			$city2=$city;
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
	
		if(strpos($row['address'],':')!==false){
			$ddd=explode(':', $row['address']);
			$address=$ddd[1];
		}else{
			if(strpos($row['name_delivery'],'Киев')!==false || $row['name_delivery']==null){
				$address=$row['address'];
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


		if($status == 5 || $status == 4){
			if($status_com != 2){
				$devi_sobr+=$row["payment_ttn"];
			}
		}elseif($status == 6){
			if($status_com != 2){
				$devi_send+=$row["payment_ttn"];
			}
		}elseif($status == 8){
			if($status_com != 2){
				$devi_sklad+=$row["payment_ttn"];
			}
		}


		$client[$offer_id]="{$name}<br>{$tel}<br>{$email}<br>{$city}<br>{$address}";

//AND c.`status` IN (6,7)
		$res2=mysql_query("SELECT *, c.`status` AS group_status
						FROM `shop_orders_coms`
						LEFT JOIN `shop_commodity` 
						ON `shop_commodity`.`commodity_ID` = `shop_orders_coms`.`com_id`
						INNER JOIN `sup_group` AS c 
						ON `shop_orders_coms`.`group_id` = c.`group_id`
						WHERE `offer_id` = '{$row["id"]}'
						{$status2}
					");

		while($row2=mysql_fetch_assoc($res2)){

			$statusGroup=$row2["group_status"];

			// if($statusGroup<6){
			// 	$flag[$row["id"]]=1;
			// }
			//echo $row2["group_id"]."=".$row2["group_status"].", ".$flag[$row["id"]].", ";
			//echo $row2["group_id"]."=".$row2["status"]."<br/>";
			$addOption="";
			$groupName=SOZ::getStatusCommodity($status,$statusGroup);
			//$groupName=SOZ::getStatusGroup($statusGroup);
			$addOption="<option value='{$statusGroup}' selected>{$groupName}</option>";

			$com_id = $row2["com_id"];

			$client_id[$row["id"]]=$row2["status"];

			$size = $row2["com"];
			if($size=="(undefined)"){
				$size="";
			}
			$count = $row2["count"];
			$cur_id2 = $row2["cur_id"];

			if($status!=2){
				$order_count += $row2["count"];
			}

			$cod2 = $row2["cod"];
			//$price_opt = $row2["commodity_price2"];
			$from_url = $row2["from_url"];
			$com_name = $row2["com_name"];
			$color = get_color_to_order($com_id);
			if($row2["com_color"] != ""){
				$color = $row2["com_color"];
			}else{
				$color = strip_tags(get_color_to_order($com_id));
			}
			$comment = $row2["man_comment"];
			
			$price = $row2["price"]*$count;
			$com_sum = $row2["price"];
			if($com_sum==0){
				$com_sum=$row2["commodity_price2"]*$count;
				$price=$row2["commodity_price2"];
				if($com_sum==0){
					$com_sum=$row2["commodity_price"]*$count;
					$price=$row2["commodity_price"];
				}
			}
			$com_selected1 = "";
			$com_selected2 = "";
			$com_selected3 = "";
			$com_selected4 = "";
			$com_selected5 = "";
			$com_selected0 = "";
			$com_selected6 = "";
			$order_com_id = $row2["id"];

			$display_active[$row["id"]]=$row2["status"];


				$linecolor = "";
				$status_com = $row2["com_status"];
				if($status_com == 1){
					$com_selected1 = "selected";
					$linecolor = "greenline";
				} elseif($status_com == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
					$addOption="";
					$order_count-=$count;
				} elseif($status_com == 3){
					$com_selected3 = "selected";
					$com2_selected3 = "selected";
					$addOption="";
				} elseif($status_com == 0){
					$com_selected0 ="selected";
				} elseif($status_com == 4){
					$com_selected4 ="selected";
					$com2_selected4 ="selected";
				} elseif($status_com == 5){
					$com_selected5 ="selected";
					$com2_selected5 ="selected";
				} elseif($status_com == 6){
					$com_selected6 ="selected";
					$com2_selected6 ="selected";
				} 
				elseif($status_com == 8){
					$com_selected8 ="selected";
					$com2_selected8 ="selected";
				} 
					
				$offer_sum["{$offer_id}"] +=$com_sum;

				$basket_com_cat=SOZ::getBrandeName($row2['com_id']);
				$cat_name2=SOZ::getCategoryName($row2['com_id']);
				$url="/product/".$com_id."/".$row2["alias"].".html";	
				
				$tab_lines.="
					<tr id='{$c["id"]}' rel='shop_orders_coms' rel2='id' class ='{$linecolor}'>
						<td>{$basket_com_cat}</td>
						<td>{$row2["cod"]}</td>
						<td>{$color}</td>
						<td>{$size}</td>
						<td>{$count}</td>
						<td>{$cat_name2}</td>
						<td>{$com_sum}</td>
						<td>{$price}</td>
						<td style='text-align:center;'><a href='{$url}'>{$url}</a></td>
						<td style='text-align:center;'><a href = '{$row2['from_url']}' target = '_blank'>Источник</a></td>
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
		}
		
		if($client_id[$row["id"]]>=6){
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
			// } elseif($status == 4){
			// 	$status_selected4 = "selected";
			} elseif($status == 5 || $status == 4){
				$status_selected5 = "selected";
			} elseif($status == 6){
				$status_selected6 = "selected";
			} elseif($status == 7){
				$status_selected7 = "selected";
			}elseif($status == 8){
				$status_selected8 = "selected";
			}


			$summm = $offer_sum[$offer_id];
			$summm +=$comission[$offer_id];
			$summm +=$delivery_price[$offer_id];
		}

		$offer_sum2["{$offer_id}"] = $offer_sum["{$offer_id}"]." {$cur_name}";
		$del[$offer_id]=$delivery_price[$offer_id]." {$cur_name}";;
		$commissia[$offer_id]=$comission[$offer_id]." {$cur_name}";;
		$sum_pri[$offer_id]=$summm." {$cur_name}";


		$cclip[$row["id"]]=$row["mw_k_clip"];
		$clip_name[$row["id"]]=$row["mw_k_clip_file"];
		$ttnn[$row["id"]]=$row["ttn"];
		$ttnd[$row["id"]]=$row["ttn-date"];
		 
		
		if($cclip[$row["id"]]==1){
			$file2=end(explode(".",$row["mw_k_clip_file"]));
			$urlFile="http://".$_SERVER["HTTP_HOST"]."/uploads/delivery_MW_K/".$group_id.".".$file2;
			$ghead=get_headers($urlFile);
			$head=substr($ghead[0], 9, 3);
			
			if($head!="200"){
				$size_kb[$group_id] = "File is empty";
			}else{
				$size_kb[$row["id"]] = filesize("uploads/delivery_MW_K/".$row["id"].".".$file2);
			}
		
			$clip_file[$row["id"]] = $row["id"].".".$file2;
		}
		
		if($row["sent_mail_mw_k"]==0){
			$sendd="<span class='send_mail maill sentt{$offer_id}' rel='{$offer_id}' rel2={$email} style='cursor:pointer;text-decoration:underline'>
						<div class='icon_send'></div>
					</span>	";
		}elseif($row["sent_mail_mw_k"]>=1){
			$sendd="<span class='send_mail maill' rel='{$offer_id}' rel2={$email} style='cursor:pointer;text-decoration:underline'>
						<div class='icon_sent' rel-was-sent='{$row["sent_mail_mw_k"]}'></div>
					</span>	";
		}	
		if($row["mw_k_clip"]==0) {
			$clip="<div class='icon_clip_will' style='cursor:pointer;' rel='{$row["id"]}'></div>";
		}elseif($row["mw_k_clip"]==1) {
			$clip="<div class='icon_clip_was icw{$row["id"]}' style='cursor:pointer;' rel='{$row["id"]}' rel-file='{$clip_file[$row["id"]]}' rel-size='{$size_kb[$row["id"]]}' rel-name='{$clip_name[$row["id"]]}'></div>";	
		}

		if(strpos($delivery, "Киев")!==false){
			$ttn="Доставка по Киеву";
		}else{
			if($ttnn[$row["id"]]!=""){
				$ttn=$ttnn[$row["id"]];
			}else{
				$ttn="000000000000";
			}
		}
		if($ttnd[$row["id"]]!=""){
			$rrr=explode(" ", $ttnd[$row["id"]]);
			$ttn_date=$rrr[0];
		}else{
			$ttn_date="01.01.2001";
		}
		$pay_ttn=0;
		$pay_ttn_price=0;
		if($row["payment_ttn"]!=0){
			$pay_ttn_price=intval($row["payment_ttn"]);
			$pay_ttn=$row["payment_ttn"]." грн";
		}

		if( $flag[$offer_id]==0 &&($display_active[$row["id"]]==6 || $display_active[$row["id"]]==7)){

			// if($status == 4){
			// 	$comm_wait+=$order_count;
			// 	$payment_wait++;
			// 	$devi_wait=$pay_ttn_price;
			// }else
			if($status == 5 || $status == 4){
				$comm_sobr+=$order_count;
				$payment_sobr++;
				$devi_sobr=$pay_ttn_price;
			}elseif($status == 6){
				$comm_send+=$order_count;
				$payment_send++;
				$devi_send=$pay_ttn_price;
			}elseif($status == 8){
				$comm_sklad+=$order_count;
				$payment_sklad++;
				$devi_sklad=$pay_ttn_price;
			}

			$all_line.=" 
				<tr class='tab_trr {$but_active} tab_up forsearch' style='{$active}' id='gh{$row["id"]}' >
					<td class='tab_tdd' style='border-bottom: 0px solid #CCC;' ></td>
					<td class='tab_tdd' style='border-bottom:0px solid;' >
						<div class='hdiv'><div class='hdiv2'>
							<span class='tab_td cli_open' rel='{$row["id"]}' >
								<div class='block_down' id='bb{$row["id"]}'></div>
							</span>
							<span class='tab_td cli_cod go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$row["cod"]} </span>
						</div></div>
					</td>
					<td class='tab_tdd' style='border:0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
							<span class='tab_td cli_id go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$row["id"]} </span>
						</div></div>
					</td>
					<td class='tab_tdd' style='border:0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
							<span class='tab_td cli_date go_href' date_href='/?admin=edit_order20&id={$row["id"]}' >{$row["date"]} </span>
						</div></div>
					</td>
					<td class='tab_tdd' style='border:0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
            			<select size='1' name='status' id = 'select_order_status' class='color_select3' rel='{$offer_id}'>
            				<option value='8' rel='{$offer_id}' {$status_selected8}>На складе</option>
							<!--<option value='4' rel='{$offer_id}' {$status_selected4}>Оплачен</option>-->
		    				<option value='5' rel='{$offer_id}' {$status_selected5}>Собран</option>
		    				<option value='6' rel='{$offer_id}' {$status_selected6}>Отправлен</option>
		    				<option value='7' rel='{$offer_id}' {$status_selected7}>Доставлен {$payment}</option>
		            	</select>	
		            	</div></div>						
					</td>
					<td class='tab_tdd' style='border:0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
							{$row["name"]}
						</div></div>
					</td>
					<td class='tab_tdd' style='border:0px solid;'>
						<div class='hdiv'><div class='hdiv2'>
							{$order_count}
						</div></div>
					</td>
					<td class='tab_tdd' style='border:0px solid;'>
						<div class='hdiv'><div class='hdiv2 write_db' rel-id='{$row["id"]}' rel-db-tab='ttn' style='cursor: pointer;' >{$ttn}</div></div>
					</td>
					<td class='tab_tdd' style='border:0px solid;'>
						<div class='hdiv'><div class='hdiv2 gett_del{$row["id"]}'>
							{$pay_ttn}
						</div></div>
					</td>
					<td class='tab_tdd' style='border:0px solid;'>	
						<div class='hdiv'><div class='hdiv2'>
						<div id='sizeWidth{$row["id"]}' style='position: relative;'>
							<div class='bor_i2{$row["id"]}' style='position: absolute;top: -9px;left: 0px;'>
							<div style='display:table;' class='city_info'>
								<div style='display:table-cell;padding-right: 5px;padding-top: 1px;padding-left: 3px;'>
									<div id='icb{$row["id"]}' class='icon_city'></div>
								</div>
								<div style='display:table-cell;cursor:pointer;' class='city_open' rel='{$row["id"]}' >
								{$city2}
								</div>
								<div style='display:table-cell;padding-left:5px;padding-right:5px;'>
									<div id='bc2{$row["id"]}' class='block_down1'></div>
								</div>
							</div>	
							</div>
						
								<div class='cli_info city_info_open{$row["id"]}' style='display:none;'>
									<table width='100%' >
										<tr><td class='info_td'></td><td></td></tr>
										<tr><td class='info_td'>Способ доставки:</td><td id='addWayDelivery' >{$delivery}</td></tr>
										<tr><td class='info_td'>№ отделения:</td><td id='addDepart'>{$depart2}</td></tr>
										<tr><td class='info_td'>Адрес:</td><td id='addAddress'>{$address2}</td></tr>
										<tr><td class='info_td'>Получатель:</td><td>{$row["name"]}</td></tr>
										<tr><td class='info_td'>Телефон:</td><td id='addPhone' >{$phone}</td></tr>
										<tr><td class='info_td'></td><td></td></tr>
									</table>
								</div>
						</div>			
						</div></div>	
					</td>
					<!--<td class='tab_tdd' style='border:0px solid;'>
						<div class='hdiv'><div class='hdiv2 write_db set_date{$row["id"]}' rel-id='{$row["id"]}' rel-db-tab='ttn-date' >{$ttn_date}</div></div>	
					</td>-->
					<td class='tab_tdd' style='border:0px solid;border-right: 1px solid #CCC;'>
						<div class='hdiv'><div class='hdiv2' style='position: relative;'>	
						<div style='display:table;width: 100%;cursor:pointer;' >
							<div style='display:table-cell;vertical-align: middle;position: relative;'>

							<div style='display:table;'>
							<div style='display:table-cell;'>
								<div class='cli_info cli_info_open{$row["id"]}' style='display:none;right: -11px;top: 27px;'>
									<table width=100%; >
										<tr><td class='info_td'></td><td></td></tr>
										<tr><td class='info_td'>Имя:</td><td>{$row["name"]}</td></tr>
										<tr><td class='info_td'>E-mail:</td><td>{$email}</td></tr>
										<tr><td class='info_td'>Телефон:</td><td>{$phone}</td></tr>
										<tr><td class='info_td'>Город:</td><td>{$city}</td></tr>
										<tr><td class='info_td'>Адрес:</td><td>{$address}</td></tr>
										<tr><td class='info_td'></td><td></td></tr>
									</table>
								</div>
							</div>
							<div style='display:table-cell;width: 20px;position: relative;'>
								<div class='bor_i{$row["id"]}'>
									<div class='icon_cont icc' id='ic{$row["id"]}' rel='{$row["id"]}' style='float:left;'></div>
									<span class='bcc' id='bc1{$row["id"]}'> 
										<span class='block_down1 b_cont' id='bc{$row["id"]}' ></span>
									</span>
								</div>
							</div>	
							</div>

							</div>	

							<div style='display:table-cell;vertical-align: middle;padding-left:12px;'>
								{$sendd}						
							</div>	

							<div class='change_clip{$row["id"]}' style='display:table-cell;vertical-align: middle;padding-left:4px;'>
								{$clip}
							</div>

							<div style='display:table-cell;vertical-align: middle;padding-left:4px;'>
								<div class='icon_print' rel='{$row["id"]}' is='1'></div>
							</div>
						</div>	
						</div></div>
					</td>
				</tr>

				<tr class='tab_trr {$but_active}' style='{$active}' >
					<td class='tab_tdd' style='border-bottom: 0px solid #CCC;' ></td>
					<td colspan=11 class='tab_tdd' style='padding: 0px;padding-bottom: 3px;' >
						<div class='line_b' id='open_comm{$row['id']}' style='display:none' >
							<table class='sortable'>
								<tr>
									<th>Бренд</th>
									<th>Артикул</th>
									<th>Цвет</th>
									<th>Размер</th>
									<th>Кол-во</th>
									<th>Товар</th>
									<th>Цена</th>
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
	}	
	//echo $flag[$offer_id].", ";
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
	$today=date("d")." ".$monthes[date("n")];
		$send_mail='
				<div style="font-family: Arial,Helvetica,sans-serif; color: black;border: 1px solid #54A5B2;padding: 3px;margin-bottom: 3px;" >
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
				<hr/>
				<div style=\'color: black;\'>
					С уважением, MakeWear<br/>
					Телефон: +380 (099) 647-73-23<br/>
					<a href=\'http://makewear.com.ua\'>www.mekawear.com.ua</a><br/>
				</div>

				';
	//--end--Send Mail----------------------------------
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
	//--For print----
	$center.="
		<div class='pri_body' style='display:none;'>
			<div class='pri_head'>
				<div class='print_close'></div>
				<div class='paperA4' >
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
					<td style='color: white;background: #313131;border: 1px solid white;' >НА СКЛАДЕ</td>
					<td style='color: white;background: #313131;border: 1px solid white;' >СОБРАНЕ</td>
					<td style='color: white;background: #313131;border: 1px solid white;border-right: 1px solid #313131;' >ОТПРАВЛЕНО</td>
					<!--<td style='color: white;background: #313131;border: 1px solid white;border-right: 1px solid #313131;' >ОЖИДАЕТ</td>-->
				</tr>
				<tr>
					<td>ПОСТАВОК</td>
					<td>{$payment_sklad}</td>
					<td>{$payment_sobr}</td>
					<td>{$payment_send}</td>
					<!--<td>{$payment_wait}</td>-->
				</tr>
				<tr>
					<td>ТОВАРОВ</td>
					<td>{$comm_sklad}</td>
					<td>{$comm_sobr}</td>
					<td>{$comm_send}</td>
					<!--<td>{$comm_wait}</td>-->
				</tr>
				<tr>
					<td style='border-bottom: 1px solid #313131;' >СТОИМОСТЬ ДОСТАВКИ</td>
					<td>{$devi_sklad}</td>
					<td>{$devi_sobr}</td>
					<td>{$devi_send}</td>
					<!--<td>{$devi_wait}</td>-->
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
		</div>
		<div style='position: relative;height: 28px;'>
			<div style='display:table;position: absolute;right: 2px;' class='but_nn'>
				<div class='{$but_orr1} tab_nn' style='display:table-cell;{$but_style1}' rel=1 >
					<div>
						Доставка MW-K 
					</div>
				</div>
				<div class='{$but_orr2} tab_nn' style='display:table-cell;{$but_style2}' rel=2 >
					<div>
						Архив
					</div>
				</div>
			</div>
		</div>
	<table class = 'sortable delivery_mw_k'>
		<tr>
			<th></th>
			<th>№ Заказа</th>
			<th>ID</th>
			<th>Дата и вермя</th>
			<th>Статус</th>
			<th>Клиент</th>
			<th>Единиц</th>
			<th>TTH</th>
			<th>Стоимость</th>
			<th>Пункт-доставки</th>
			<!--<th>Дата прибытия</th>-->
			<th></th>
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

		$center.="
				<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css\">
				<link href='/templates/admin/soz/style/client_delivery20.css' type='text/css' rel='stylesheet' />
				<link href='/templates/admin/print/mw_print.css' type='text/css' rel='stylesheet' />
				<script src='/templates/admin/print/mw_print.js' type='text/javascript'></script>
				<script src='/templates/admin/print/jQuery.print-master/jQuery.print.js' type='text/javascript'></script>
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
				</script>					
				<script src='/templates/admin/soz/js/client_delivery20.js' ></script>";
	$center.=$all_line."</table>{$links}";
	
}
		
