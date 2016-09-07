<?php

//TODO исключить запросы в цикле, использовать оператор IN() 
if ($_SESSION['status']=="admin"){

include "soz.php";

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
		$status="`status` in (7,8,10)";
		$but_orr2="but_nn_active";
		$but_style2="border-bottom: 0px solid gray;color: black;";
	}else{
		$status="`status` in (1,2,3,4,5,6, 12)";
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
	$group_client_active=array();
	$arr_c2=array();
	//`status` in (1,2,3,4,6,7)
	$sql = "SELECT * 
			FROM  `shop_orders`
			WHERE {$status}
			ORDER BY `id`  DESC {$limit}";
	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)){
		$user_id = $row["user_id"];
		$offer_id = $row["id"];
		$discount = $row["discount"];
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


		$status_selected1 = "";
		$status_selected2 = "";
		$status_selected3 = "";
		$status_selected4 = "";
		$status_selected5 = "";
		$status_selected6 = "";
		$status_selected7 = "";
		$status_selected12 = "";
				
		
		$address = $row["address"];
		$cod =$row["cod"];
		$date = $row["date"];
		$client_count["{$user_id}"] +=1;
		$lines = '';

		// $active[$offer_id]="display:grid;";
		// $but_active[$offer_id]="but_active";

		$status = $row["status"];
		//echo $status.", ";
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
		} elseif($status == 8){
			$status_selected7 = "selected";
		} elseif($status == 9){
			$status_selected7 = "selected";
		} elseif($status == 10){
			$status_selected10 = "selected";
		} elseif($status == 12){
			$status_selected12 = "selected";
		}
		if( $status < 3 || $status == 10){
			$select_client="
			<select size='1' name='status' id = 'select_order_status' class='color_select'  rel='{$offer_id}'>
    			<option value='1' rel='{$offer_id}' {$status_selected1}>Новый заказ</option>
				<option value='2' rel='{$offer_id}' {$status_selected2}>Обрабатывается</option>
				<option value='3' rel='{$offer_id}' {$status_selected3}>Подтвержден</option>
				<option value='10' rel='{$offer_id}' {$status_selected10}>Отменен</option>		
            </select>";
        }else{
        	$payment="";
        	if($row["payment_MW"]==1){
        		$payment=" и оплачен";
        	}
        	$select_client="
			<select id = 'select_order_status' class='discolor_select'  rel='{$offer_id}' disabled>
				<option value='3' rel='{$offer_id}' {$status_selected3}>Готов к оплате клиентом</option>
				<option value='12' rel='{$offer_id}' {$status_selected12}>Оплачен MW</option>
				<option value='4' rel='{$offer_id}' {$status_selected4}>Оплачен клиентом</option>
				<option value='8' rel='{$offer_id}' {$status_selected8}>На складе</option>
				<option value='5' rel='{$offer_id}' {$status_selected5}>Собран</option>
				<option value='6' rel='{$offer_id}' {$status_selected6}>Отправлен</option>
				<option value='7' rel='{$offer_id}' {$status_selected7}>Доставлен {$payment}</option>		
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
				
				// $from_url = "http://makewear.com.ua/pr{$iddd}/";
				$from_url="/product/".$com_id."/".$row2["alias"].".html";
				$com_name = $row2["com_name"];
				

				$group_id=$row2["group_id"];
				$addOption="";
				$grres=mysql_query("SELECT `group_id`, `status` FROM `sup_group` WHERE `group_id`={$group_id}; ");
				$grrow=mysql_fetch_assoc($grres);

				$groupName=SOZ::getStatusCommodity($status,$grrow["status"]);
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

				$status_name="";
				$status_com = $row2["com_status"];
				if($status_com == 1){
					$com_selected1 = "selected";
					$linecolor = "greenline";
					$status_name="Есть в наличии";
				} elseif($status_com == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
					$client_sum-=$com_sum*$count;
					$ed-=$count;
					$status_name="Нет в наличии";
					$addOption="";
				} elseif($status_com == 3){
					$com_selected3 = "selected";
					$status_name="Замена";
					$addOption="";
					$ed-=$count;
				} elseif($status_com == 0){
					$com_selected0 ="selected";
				} elseif($status_com == 4){
					$com_selected4 ="selected";
					$status_name="оплачен";
				} elseif($status_com == 5){
					$com_selected5 ="selected";
				} elseif($status_com == 6){
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
				<td><a href ='{$from_url}'>{$from_url}</a></td>
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
				//if($status_com != 2){
				$arr_c2[$offer_id][$jj]=array(
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
					"status"=>$status_name,
					"delivery"=>$delivery,
					"comment"=>$row2["man_comment"] 
					);
					$jj++;
				//}
			}
		
			if($row["sent_mail"]==0){
				$sendd="<div style='margin-top: 3px;' >
							<span class='i_sent sentt{$offer_id}' rel='{$offer_id}' >
								<div class='icon_send'></div>
								отправить
							</span>
						</div>";
			}elseif($row["sent_mail"]>=1){
				$sendd="<div style='margin-top: 3px;' >
							<span class='i_sent sentt{$offer_id}' rel='{$offer_id}' >
								<div class='icon_sent' rel-was-sent='{$row["sent_mail"]}' ></div>
								<span style='text-decoration:underline;color: rgb(248, 106, 5);'>отправлен</span>
							</span>
						</div>";
			}		
			$ski='';
			$gift="";
			$delivery_price=null;
			if(1<=$discount && $discount<=3){
				//echo $client_sum."=".$row['delivery_price']."<br/>";
				if($discount==1 && $ed>=5){
					// $ski='-150 грн';
					// $client_sum-=150;
					if($row["cur_id"]==1){
						$price3-=150;
						$ski='-150 грн';
					}
					if($row["cur_id"]==3){
						$price3-=500;
						$ski='-500 руб';
					}
					$delivery_price=$row['delivery_price'];
					$client_sum+=$row['delivery_price'];
				}elseif($discount==2 && $ed>=5){ 
					$ski='-10%';
					$client_sum-=$client_sum/100*10;
					$delivery_price=$row['delivery_price'];
					$client_sum+=$row['delivery_price'];
				}
				elseif($discount==3 && $ed>=5){
					$delivery_price="Бесплатная";
					$delivery_price2=0;
				}
				if($client_sum>=1000){
					$gift="Платья";
				}
			}else{
				if($offer_id<433){
					$commission=round($client_sum/100*3);
					$client_sum+=$commission;
				}
				$client_sum+=$delivery_price[$user_id];
			}
			$commisia[$offer_id]=array("gift"=> $gift, "ski"=>$ski, "discount"=> $discount,"commision"=> $commission,"del_price"=>$row["delivery_price"]);
	$status = $row["status"];
		//echo $status.", ";
		if($status == 1){
			$status_selected1 = "selected";
		} elseif($status == 2) {
			$status_selected2 = "selected";
		} elseif($status == 3){
			$status_selected3 = "selected";
		} elseif($status == 4){
			$status_selected3 = "selected";
		} elseif($status == 5){
			$status_selected5 = "selected";
		} elseif($status == 6){
			$status_selected6 = "selected";
		} elseif($status == 7){
			$status_selected7 = "selected";
		} elseif($status == 10){
			$status_selected10 = "selected";
		}
			$order[$offer_id] = "
				<div class='orders_head tab_up oc{$offer_id}' rel='{$cod}' rel_data='{$date}' style='margin-left: -1px;margin-top: 0px;margin-bottom: 1px;' >
					<div class='tab_td' style='padding: 8px;'>
						<span class='open_commodity' rel='{$offer_id}'>
							<div class='block_down' id='bb{$offer_id}'></div>
						</span>
					</div>
					<div class='tab_td' style='width:12%'>
						Заказ №{$cod}
					</div>
					<div class='tab_td' style='width:7%'>
						ID: {$offer_id}
					</div>
					<div class='tab_td' style='width:19%'>
						Дата: {$date}
					</div>
					<div class='tab_td' style='width:19%'>
						Статус: 
						{$select_client}
					</div>
					<div class='tab_td' style='width:12%'>
						Единиц: {$ed}
					</div>
					<div class='tab_td' style='width:13%'>
						Сумма: {$client_sum} {$offer_cur_name}
					</div>
					<div class='tab_td'>
						{$sendd}
					</div>
					<div  class='tab_td' style='cursor: pointer;position: relative;width: 30px;'  >
						<div class='{$putInfo} open_backg' rel='{$offer_id}' style='display:table;'>
							<div style='display:table-cell'>
								<div class='{$putInfoColor} iiw{$offer_id}'></div>
							</div>
							<div style='display:table-cell;vertical-align: middle;padding-left: 2px;'>
								<div class='{$putDownColor} bbc{$offer_id}' ></div>
							</div>
						</div>	
						<div class='wind_names' id='open_win2{$offer_id}' style='display:none;right:-2px;margin-top:0px;width: 300px;'>
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
	$center .= "
		<div class='rees'></div><br/><br/>
			<div style='position: relative;height: 28px;'>
				<div style='display:table;position: absolute;right: 2px;' class='but_nn'>
					<div class='{$but_orr1} tab_nn' style='display:table-cell;{$but_style1}' rel=1 >
						<div>
							Заказы по клиентам
						</div>
					</div>
					<div class='{$but_orr2} tab_nn' style='display:table-cell;{$but_style2}' rel=2 >
						<div>
							Архив
						</div>
					</div>
				</div>
			</div>
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
	if($group_client)
	foreach ($group_client as $key => $value) {
		$offer_id2 = $value[0];
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

				
		$orders_head = "
			<tr class='client tr_client' id='cli{$offer_id2}' rel-tr='$offer_id2' >
				<td style='border-bottom: 0px none;'></td>
				<td style='border-bottom: 0px none;' >
					<div class='hdiv'><div class='hdiv2'>
							<div class='cli_id'>
								{$offer_id2} 
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
								<div class='tab_td go_href' date_href='/?admin=edit_order20&id={$offer_id2}' style='width:15%'>
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
							<span class='get_add'>{$address}</span>
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
			</tr>";
			for($i=0; $i<count($value); $i++){
					$orders_head.="<tr class='tr_client'>
					<td  style='border-bottom: 0px none;'></td>
					<td colspan=7  style='border-bottom: 0px none;padding-bottom: 2px;'>".$order[$value[$i]]."</td>
					</tr>";
			}
		$center.=$orders_head;	
	}

	$center.="</table>{$links}";
	$center.="
		<link href='/templates/admin/soz/style/orders_by_client20.css' type='text/css' rel='stylesheet' />
		<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css\">
		<script src='/templates/admin/js/i18n/datepicker-uk.js' type='text/javascript'></script>
		<script src='/templates/admin/js/i18n/datepicker-ru.js' type='text/javascript'></script>
		<script src=\"http://zoond-test.cloudapp.net:8264/socket.io/socket.io.js\"></script>	
		<script src=\"/modules/mw_chat/onload.js\"></script>	
		<script>
			var arr=".json_encode($arr_c2).";
			var del=".json_encode($commisia).";
		</script>
		<script src='/templates/admin/soz/js/orders_by_client20.js'></script>
	";

}
