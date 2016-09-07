<?


include 'soz.php';

if ($_SESSION['status']=="admin")
{
	$_SESSION["lastpage2"]="/?admin=all_orders";
	if(isset($_GET["id"]))
	{
		$id=$_GET["id"];
		$price=0;
		$total_count = 0;

		$glb["templates"]->set_tpl('{$id}',$id);
		$sql="SELECT * FROM `shop_orders` 
		WHERE `id`='{$id}'";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$cod=$row["cod"];
			$date=$row["date"];
			$tel=$row["tel"];
			$user_id=$row["user_id"];

			$discount=$row["discount"];

			$delivery=$row["delivery"];
			if($delivery==0){
				$delivery=$row["name_delivery"];
			}else{
				$delSql=mysql_query("SELECT * FROM `shop_delivery` WHERE `id`='{$delivery}'; ");
				$delRes=mysql_fetch_assoc($delSql);
				$delivery=$delRes["name"];
			}

			$order_status='';

			$ssel_status=$row["status"];
			if($ssel_status==1){
				$sel_status1="selected";
				$order_status='Новый заказ';
			}elseif($ssel_status==2){
				$sel_status2="selected";
				$order_status='Обрабатывается';
			}elseif($ssel_status==3){
				$sel_status3="selected";
				$order_status='Подтвержден';
			}elseif($ssel_status==4){
				$sel_status4="selected";
				$order_status='Оплачен';
			}elseif($ssel_status==5){
				$sel_status5="selected";
				$order_status='Собран';
			}elseif($ssel_status==6){
				$sel_status6="selected";
				$order_status='Отправлен клиенту';
			}elseif($ssel_status==7){
				$sel_status7="selected";
				$order_status='Доставлен клиенту';
			}elseif($ssel_status==8){
				$sel_status8="selected";
				$order_status='На складе';
			}elseif($ssel_status==9){
				$sel_status9="selected";
				$order_status='Закрыт';
			}elseif($ssel_status==10){
				$sel_status10="selected";
				$order_status='Отменен';
			}

			$sql2="
			SELECT * FROM `shop_orders_coms`
			LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
			WHERE `offer_id`='{$row["id"]}' AND `count`>0;";
			$res2=mysql_query($sql2);
			while($row2=mysql_fetch_assoc($res2))
			{
				$ress=mysql_query("SELECT `cur_id` FROM `shop_orders_coms` WHERE `id`='{$row2["id"]}' AND `offer_id`='{$row["id"]}'; ");
				$coms_cur=mysql_fetch_assoc($ress);
				$basket_com_price_one = 0;
				$com_id = $row2["com_id"];
				$group_id=$row2["group_id"];
				$com_selected1 = "";
				$com_selected2 = "";
				$com_selected3 = "";
				$com_selected4 = "";
				$com_selected5 = "";
				$com_selected0 = "";
				$com_selected6 = "";
				$price2=$row2["price"];
				$alisa=$row2["alisa"];


				$linecolor = "";
				$status = $row2["com_status"];
				$addOption="";
				$grres=mysql_query("SELECT `group_id`, `status` FROM `sup_group` WHERE `group_id`={$group_id}; ");
				$grrow=mysql_fetch_assoc($grres);
				$groupName=SOZ::getStatusCommodity($ssel_status,$grrow["status"]);
				// $groupName=SOZ::getStatusGroup($grrow["status"]);
				$addOption="<option status='{$grrow["status"]}' selected>{$groupName}</option>";

				if($status == 1){
					$com_selected1 = "selected";
					$linecolor = "greenline";
				} elseif($status == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
					$price -= $price2*$row2["count"];;
					$total_count -=$row2["count"];
					$addOption="";
				} elseif($status == 3){
					$com_selected3 = "selected";
					$price -= $price2*$row2["count"];;
					$total_count -=$row2["count"];
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

				if($ssel_status<3){
					$addSelect="<select size='1' name='status' id = 'select_status_com' rel = '{$row2['id']}'>
					<option value= '0' {selected0}></option>
					<option value='1' rel='{$row2['id']};1' {$com_selected1}>Есть в наличии</option>
    				<option value='2' rel='{$row2['id']};2' {$com_selected2}>Нет в наличии</option>
    				<option value='3' rel='{$row2['id']};3'{$com_selected3}>Замена</option>
    				</select>";
				}else{
					$addSelect="<select disabled>{$addOption}</select>";
				}
			
				//$basket_com_cat =brenda_name($row2["cod"]);

				$basket_com_cat=SOZ::getBrandeName($com_id);

				

				$glb["templates"]->set_tpl('{$basket_com_cat}',$basket_com_cat);
					if($row2["com_color"] == ""){
						$color = strip_tags(get_color_to_order($com_id));
					} else{
						$color = $row2["com_color"];
					}
					
				
					
				$glb["templates"]->set_tpl('{$color}',$color);
				if ($row2["count"]> 0){
						$basket_com_price_one =  $price2*$row2["count"];
				}

			 
			    $total_price +=$basket_com_price_one;
			    
				$total_count += $row2["count"];
				$price += $basket_com_price_one;

				$url="/product/".$com_id."/".$row2["alias"].".html";
				$src=$row2["alias"]!=""?"<img src='/{$row2["com_id"]}stitle/{$row2["alias"]}.jpg' style='height:30px;'>":"";
				$lines.="
				<tr id='{$row2["id"]}' rel='shop_orders_coms' rel2='id' class ='{$linecolor}'>
				<td><input type='checkbox' class='cl_trt' rel='{$row2["id"]}'/></td>
				<td>{$basket_com_cat}</td><td>{$row2["cod"]}</td>
				<td id = 'com_color' class='cl_edit'>{$color}</td>
				<td id = 'com' class='cl_edit'>{$row2["com"]}</td>
				<td>{$row2["count"]}</td>
				<td>{$glb["cur"][$coms_cur["cur_id"]]}</td>
				<td>{$price2}</td>
				<td>{$basket_com_price_one}</td>
				<td><a href='{$url}'>{$url}</a></td>
				<td><a href = '{$row2['from_url']}' target = '_blank'>Источник</a></td>
				<td id = 'man_comment' class='cl_edit'>{$row2["man_comment"]}</td>
				<td>
					{$addSelect}
				</td>

				</tr>";
				
			}

			
			$sql="
			SELECT * FROM `shop_delivery` 
			ORDER BY `order`;";
			$res=mysql_query($sql);
			while($row3=mysql_fetch_assoc($res))
			{
				$delivery_name=$row3['id']==$row['delivery']?$row3['name']:$delivery_name;
				$selected=$row3['id']==$row['delivery']?"selected":"";
				$delivery_lines.="<option value='{$row3['id']}' {$selected}>{$row3['name']}</option>";
			}
			$delivery_lines=$delivery_lines!=""?"<select id='id_sb_dost'>{$delivery_lines}</select>":"";
			
			// foreach($offer_status as $key=>$value)
			// {
			// 	$selected=$key==$row["status"]?"selected":"";
			// 	$status_name=$key==$row["status"]?$value:$status_name;
			// 	$status_lines.="<option value='{$key}' {$selected}>{$value}</option>";
			// }

			
			$commisia=round($price/100*3);

			if($row["commission"]==0){
				mysql_query("UPDATE `shop_orders` SET `commission`='{$commisia}' WHERE `id`='{$id}'; ");
			}


			// $bonus=SOZ::bonus($id, $user_id, $price, $total_count);

			// $delivery_price=$row['delivery_price']." ".$glb["cur"][$row["cur_id"]];
			// if($bonus['bonus_delivery']==1){
			// 	$delivery_price="Бесплатная";
			// }
			// $bonus_skidka=$bonus['gift'];


         	if($id<433){
				$addComission="<tr><td>Комиссия(3%):</td><td id='commission' class='edit_cli' rel='{$id}' contenteditable='true' >{$commisia}{$glb["cur"][$row["cur_id"]]}</td></tr>";
			}else{
				$total_price1-=$commisia;
			}
			$ski='';
			$delivery_price=$row['delivery_price']." ".$glb["cur"][$row["cur_id"]];
			$delivery_price2=$row['delivery_price'];
			$price3=$price;
			if($discount==1 && $total_count>=5){
				if($row["cur_id"]==1){
					$price3-=150;
					$ski='-150 грн';
				}
				if($row["cur_id"]==3){
					$price3-=500;
					$ski='-500 руб';
				}
				//echo $price;
			}elseif($discount==2 && $total_count>=5){
				$ski='-10%';
				$price3-=$price3/100*10;
			}
			elseif($discount==3 && $total_count>=5){
				$delivery_price="Бесплатная";
				$delivery_price2=0;
			}
			$gift="";
			if($price>=1000){
				$gift="Платья";
			}

			$total_price1 = $price3 + $delivery_price2;

			$status_lines="<select id='id_status'>{$status_lines}</select>";
			$select_status="
				<select id='select_order_status' rel='{$id}'>
					<option value='1' {$sel_status1}>Новый заказ</option>
					<option value='2' {$sel_status2}>Обрабатывается</option>
					<option value='3' {$sel_status3}>Подтвержден</option>
					<option value='4' {$sel_status4}>Оплачен</option>
					<option value='8' {$sel_status8}>На складе</option>
					<option value='5' {$sel_status5}>Собран</option>
					<option value='6' {$sel_status6}>Отправлен клиенту</option>
					<option value='7' {$sel_status7}>Доставлен клиенту</option>
					<option value='9' {$sel_status8}>Закрыт</option>
					<option value='10' {$sel_status10}>Отменен</option>
				</select>
			";

			$center.='<style>
						.edit_body{
							background:#FBFBFB;
							width:100%;
							height:100%;	
							margin-top:25px;
						}
						.order_num{
							/* width: 100%;*/
						    border-top: 1px solid #D9D9DA;
    						 border-bottom: 1px solid #D9D9DA;
						    padding: 5px;
						    padding-left: 30px;
						    font-size: 20px;
						    font-weight: bold;	
						    background: linear-gradient(#fff, #efefef) repeat 0 0;
						}
						.order_num a{
							font-size:14px;	
						}
						.order_info{
							padding: 7px;	
						}
						#suma{
							font-size:14px;
							font-weight: bold;
						}
						#suma td{
							border:0px solid;						
						}
						.stoi{
							 border: 1px solid darkgrey;
						    background: #EFEFEF;
						    width: 245px;
						    font-size: 14px;	
						    padding: 10px;
						}
						.stoi td{
							 padding-bottom: 3px;
    						 padding-top: 1px;						
						}
						.stoi td:nth-child(2){
							 border: 1px solid gray;
						    padding-left: 10px;
						    width: 100px;
						}
						.stoi td:focus{background: white;}
						.stoi2 td:nth-child(2){
							 border: 1px solid gray;
						    padding-left: 10px;
						    width: 270px;
						}
						.stoi3 td:nth-child(2){
							 border: 1px solid gray;
						    padding-left: 10px;
						    width: 200px;
						    background: white;
						    color:black;
						}
						.stoi3 button{
							 text-align: center;
						    width: 100%;
						    height: 30px;	
						}
						.wind_chat{
							border:1px solid gray;
							width:100%;	
							background:white;
						   overflow: auto;
						   height: 137px;
						   color:black;
						   position: relative;
						}
						.write_chat{
							border:1px solid gray;
							width:100%;	
							background:white;
							height: 65px;
							color:black;
						}
						.wind_chat .line_chat{
							padding:5px;
						}
						.wind_chat .line_chat span:nth-child(1){
							font-weight: bolder;
    						color: #00455c;
    						padding-left: 10px;	
						}
						.wind_chat .line_chat span:nth-child(2){
							 right: 5px;
						    position: absolute;
						    font-size: 11px;
						    color: #545454;
						}
						.hr_center{
							width: 97%;
							margin-left: auto;
    						margin-right: auto;						
						}
						.icon_xls {
						    background: url(http://makewear.com.ua/templates/admin/img/soz2.png);
						    width: 17px;
						    height: 20px;
						    background-size: 500px 600px;
						    background-repeat: no-repeat;
						    background-attachment: scroll;
						    background-position: -338px -251px;
						    margin-top: 2px;
						}
					</style>
					<script>
						$(document).ready(function(){
							
							//--Добавить товар--
							$(".add_commodity").click(function(){
								var id=$(".get_id").text();
								// var cur=$(".get_cur").text();
								var cur=$(".get_cur").val();
								var size=$(".get_size").text();
								var count=$(".get_count").text();
								var offer_id=$(this).attr("rel");
								
								$.ajax({
									type:"POST",
									url: "http://"+window.location.hostname+"/modules/commodities/ajax/add_commodity_client.php",
									data:{offer_id:offer_id, id:id, cur:cur, size:size, count:count}
								})
								.done(function(dat){
									//alert(dat);
									if(dat==1){
										$(location).attr("href","http://"+window.location.hostname+"/?admin=edit_order20&id="+offer_id);
									}else if(dat==0){
										alert("Error!");
									}
								})
							});
							$(".but_chat").click(function(){
								var t = new Date();
								var td=t.getDate()+"."+(t.getMonth()+1)+"."+t.getFullYear();
								var tt=t.getHours()+":"+t.getMinutes()+":"+t.getSeconds();
							
								var gg=$(".write_chat").html();
								var txt="";
								txt="<div class=line_chat ><div><span>MakeWear</span><span>"+td+" "+tt+"</span></div>"+gg+"</div>"
								
								if(gg!=""){
									$(".wind_chat").append(txt+"<hr class=hr_center />");
									$(".write_chat").html("");
								}

							});
							
							$(".edit_cli").on("keyup", function(){
								var mm=$(this).attr("id");
								var id=$(this).attr("rel");
								var tx=$(this).text();
								
								//alert("id: "+id+", "+tx+", "+mm);
								
								$.ajax({
								    type:"POST",
									  url:"http://"+window.location.hostname+"/modules/commodities/ajax/edit_cli.php",
								     data:{com_id:id, mname:mm, gname:tx }
										//data:{idd:"maxim"}
								})
								.done(function(dat){
									//	alert(dat);							
								});
								
							});
						});
					</script>	
			';
			$r_date=$date;
			$date2=explode(" ",$date);
			$date3=explode("-",$date2[0]);
			$date=$date3[2].".".$date3[1].".".$date3[0];
			
			$tel=change_phone($row["tel"]);

			$center.="
				<div class='edit_body'>
					<div class='order_num'>
						<div style='display:table'>
							<div style='display:table-cell;vertical-align: middle;'>
								<span>Заказ №{$cod} от {$date}</span>
							</div>
							<div style='display:table-cell;vertical-align: middle;'>
								<span style='margin-left: 20px;margin-right: 20px;'>{$select_status}</span>
							</div>
							<div style='display:table-cell';vertical-align: middle;>
								<a href='?admin=all_orders20&exportId={$id}'>
									<div class='icon_xls'></div>
								</a>
							</div>
						</div>
					</div>
					
					<div class='order_info'>
						
						<div style='display:table'>
							<div style='display:table-cell'>					
							<table class='stoi stoi2' style='width:420px' >
								<tr><td colspan=2 style='color: gray;font-weight: bold;padding-bottom: 12px;'>ДЕТАЛИ ЗАКАЗА</td></tr>
								<tr><td>Заказ №:</td><td>{$cod}</td></tr>
								<tr><td>Дата:</td><td>{$r_date}</td></tr>
								<tr><td>Статус:</td><td id='status' contenteditable='true'>{$order_status}</td></tr>
								<tr><td>Имя:</td><td id='name' class='edit_cli' rel='$id' contenteditable='true'>{$row["name"]}</td></tr>
								<tr><td>Email:</td><td id='email' class='edit_cli' rel='$id' contenteditable='true'>{$row["email"]}</td></tr>
								<tr><td>Телефон:</td><td id='tel' class='edit_cli' rel='$id' contenteditable='true'>{$tel}</td></tr>
								<tr><td>Доставка:</td><td id='name_delivery' class='edit_cli' rel='$id' contenteditable='true'>{$delivery}</td></tr>
								<tr><td>Город:</td><td id='city' class='edit_cli' rel='$id' contenteditable='true'>{$row["city"]}</td></tr>
								<tr><td>Адрес:</td><td id='address' class='edit_cli' rel='$id' contenteditable='true'>{$row["address"]}</td></tr>
								<tr><td>Комментарий пользователя:</td><td id='user_comments' class='edit_cli' rel='$id' contenteditable='true'>{$row["user_comments"]}</td></tr>
							</table>
							</div>
							
							<div style='display:table-cell;padding-left:10px;vertical-align:bottom;'>
							<table class='stoi stoi3' style='width:450px' >
								<tr><td style='color: gray;font-weight: bold;padding-bottom: 12px;'>КОММЕНТАРИИ</td></tr>
								<tr><td>
									<div class='wind_chat'></div>								
								</td></tr>
								<tr><td class='write_chat' contenteditable='true'>
																	
								</td></tr>
								<tr><td colspan=2 ><button style='text-align:center' class='but_chat'>ОСТАВИТЬ КОММЕНТАРИЙ</button></td></tr>
							</table>	
							</div>
								
							<div style='display:table-cell;padding-left:10px;vertical-align:bottom;'>
							<table class='stoi stoi3' style='width:350px' >
								<tr><td colspan=2 style='color: gray;font-weight: bold;padding-bottom: 12px;'>ДОБАВИТЬ ТОВАР</td></tr>
								<tr><td>ID товара:</td><td contenteditable='true' class='get_id' ></td></tr>
								<!--<tr><td>Валюта:</td><td contenteditable='true' class='get_cur'></td></tr>-->
								<tr><td>Валюта:</td>
									<td style='border: 0px solid gray;background: none;'>
										<select class='get_cur' style='width: 105%;margin-left: -10px;'>
											<option></option>
											<option value=1 >грн</option>
											<option value=2 >$</option>
											<option value=3 >руб</option>
										</select>
									</td>
								</tr>
								<tr><td>Размер:</td><td contenteditable='true' class='get_size'></td></tr>
								<tr><td>Количество:</td><td contenteditable='true' class='get_count'></td></tr>
								<tr><td colspan=2 ><button style='text-align:center' class='add_commodity' rel='{$id}' >ПРИМЕНИТЬ</button></td></tr>
							</table>	
							</div>
						</div>					
						<br/>
						<table class='sortable cl_need_reload'>
							<tr>
								<th></th>
								<th>Бренд</th>
								<th>Артикул</th>
								<th>Цвет</th>
								<th>Размер</th>
								<th>Кол-во</th>
								<th>Валюта</th>
								<th>Цена</th>
								<th>Сумма</th>
								<th>Ссылка на товар</th>
								<th>Источник</th>
								<th>Комментарий</th>
								<th>Статус</th>
							</tr>	
							{$lines}
							<tr id='suma'>
								<td></td>
								<td>Итого:</td>
								<td></td>
								<td></td>
								<td></td>
								<td>{$total_count} ед</td>
								<td></td>
								<td></td>
								<td>{$price} {$glb["cur"][$row["cur_id"]]}</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</table>
						<br/>
						<table class='stoi' >
							<tr><td colspan=2 style='color: gray;font-weight: bold;padding-bottom: 12px;'>СТОИМОСТЬ</td></tr>
							<tr><td>Товар:</td><td>{$price} {$glb["cur"][$row["cur_id"]]}</td></tr>
								<td>Скидка:</td>
								<td>{$ski}</td>
							</tr>
							<tr>
								<td>Подарок:</td>
								<td>{$gift}</td>
							</tr>
							{$addComission}
							<tr><td>Доставка:</td><td id='delivery_price' class='edit_cli' rel='{$id}' contenteditable='true' >{$delivery_price}</td></tr>
							<tr>
							<tr style='font-weight: bold;'><td>К оплате:</td><td>{$total_price1} {$glb["cur"][$row["cur_id"]]}</td></tr>
						</table>						
						
					</div>
				</div>			
			";
		}		
		
	}
		
}
?>
