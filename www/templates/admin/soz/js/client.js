$(document).ready(function(){
		$('.maill2').click(function(){
							var rel=$(this).attr('rel');

							var name2=$('.cli_info_open'+rel+' tr:nth-child(2) td:nth-child(2)').text();
							var email=$('.cli_info_open'+rel+' tr:nth-child(3) td:nth-child(2)').text();
							var city=$('.cli_info_open'+rel+' tr:nth-child(5) td:nth-child(2)').text();

							var waysDelivery=$('.get_add').attr("rel-delivery");
							var depart=$('.get_add').attr("rel-add");
							// var address=$('.city_info_open'+rel+' #addAddress').text();
							// var phone=$('.city_info_open'+rel+' #addPhone').text();

							var name=$('.get_name').val();
								if(name==''){
									name2=$('#cli'+rel+' .get_name').text();
								}
							var phone=$('.get_tel').val();
								if(phone==''){
									phone=$('#cli'+rel+' .get_tel').text();
								}
							var city=$('.get_city').val();
								if(city==''){
									city=$('#cli'+rel+' .get_city').text();
									var acity=city.split(",");
									city=acity[0];
								}
							var address=$('.get_add').val();
								if(address==''){
									address=$('#cli'+rel+' .get_add').text();
								}


							$('body').css({'overflow':'hidden'});

							var mail=$(this).attr("rel2");
							$('.tab_send_towhere').val(mail);
							$('.tab_send_subject').val('Запрос поставки № '+rel);
							//alert(rel);
							
							$('.sent_order').attr('rel',rel);	
							$('.sent_order').attr('rel-status',2);

							
							var t = new Date();
							var tt=t.getDate()+'.'+(t.getMonth()+1)+'.'+t.getFullYear();
							var time=t.getHours()+':'+t.getMinutes()+':'+t.getSeconds();
							
							var tab_com='';
							
							

							//----For clip file--------
							var clip='';
							if($('.icw'+rel).hasClass('icon_clip_was')){
								var size=$('.icw'+rel).attr('rel-size');
								var fff=$('.icw'+rel).attr('rel-file');
								var name=$('.icw'+rel).attr('rel-name');

								size=(size/1024).toFixed(1)+' KB';	
								//$('.set_name').text(fff);
								var hreff=window.location.hostname+'/uploads/delivery_MW_K/'+fff;
									
									clip+='<div style=\'border: 10px solid transparent;border-bottom: 10px solid white;width: 0px;position: absolute;top: -19px;left: 24px;z-index: 3;\'></div>';
									clip+='<div style=\'border: 10px solid transparent;border-bottom: 10px solid gray;width: 0px;position: absolute;top: -20px;left: 24px;\'></div>';
									clip+='<div style=\'border:1px solid #B7B7B7;width: 300px;right: 6px;top: 9px;\' >';
									clip+='<div style=\'display:table;width: 100%;\'>';
									clip+='<div style=\'display:table-row\' style=\'padding:2px;\' >';
									clip+='<a href=\'http://'+hreff+'\' target=\'_blank\'><table>';
									clip+='<tr>';
									clip+='<td>';
									clip+='<div class=\'icon_file_jpg\'></div>';
									clip+='</td>';
									clip+='<td style=\'font-size: 12px;\'>';
									clip+='<span class=\'set_name\' style=\'font-size: 11px; color: black;\' >'+name+'</span><br/>';
									clip+='<span class=\'set_size\' style=\'font-size:10px; color:gray;\' />'+size+'</span>';
									clip+='</td>';
									clip+='</tr>';
									clip+='</table></a>';
									clip+='<br/>';
									clip+='</div>';
									clip+=''
									clip+='<div style=\'display:table-row;background: #E8E7E7;color: #5F5F5F;font-size: 12px;\'>';
									clip+='<div style=\'padding: 4px;\' >					';			 		
									clip+='Прикреплено: 1файл. Общий размер: '+size;
									clip+='</div>';
									clip+='</div>';
									clip+='</div>';
									clip+='</div>';
							}else{
								//alert('no')
							}
							
							
							var txt ='<div style=\'font-size: 15px;padding: 12px;\'>';
								txt+='<div><a style=\"margin-left:10px;\" href=\"http://www.makewear.com.ua/\" target=\"_blank\"><img src=\"http://makewear.com.ua/templates/shop1/images/makewear290x1.jpg\" alt=\"makewear.com\" border=\"0\" style=\"width: 262px;\"></a></div>';
								txt+='Здравствуйте, '+name2+'<br/>';
								txt+='Ваш заказ был отправлен Вам по реквизитам: <br/><br/>';
								txt+='<table border=1 style=\"border-collapse: collapse;border-color: black;\">';
								txt+='<tr><td style=\"padding:5px;font-weight: bold;\">Город:</td><td style=\"padding:5px;width: 60%;\">'+city+'</td></tr>';
								txt+='<tr><td style=\"padding:5px;font-weight: bold;\">Получатель:</td><td style=\"padding:5px;width: 60%;\">'+name2+'</td></tr>';
								txt+='<tr><td style=\"padding:5px;font-weight: bold;\">Телефон:</td><td style=\"padding:5px;width: 60%;\">'+phone+'</td></tr>';
								txt+='<tr><td style=\"padding:5px;font-weight: bold;\">Способ доставки:</td><td style=\"padding:5px;width: 60%;\">'+waysDelivery+'</td></tr>';
								txt+='<tr><td style=\"padding:5px;font-weight: bold;\">№ отделения:</td><td style=\"padding:5px;width: 60%;\">'+depart+'</td></tr>';
								txt+='<tr><td style=\"padding:5px;font-weight: bold;\">Адрес отделения:</td><td style=\"padding:5px;width: 60%;\">'+address+'</td></tr>';
								txt+='<tr><td style=\"padding:5px;font-weight: bold;\">Примечание:</td><td style=\"padding:5px;width: 60%;\"></td></tr></table><br />';
								txt+='Во вложении скан-копия торгово-транспортной накладной';
								txt+='<br/><table class=\'rezz\' border=1 style=\'border-collapse: collapse;border-color: black;\' ></table>';
								txt+='<hr color=\'black\' style=\'margin-top:10px;margin-bottom:10px;\' />';
								txt+='<table id=\"footer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-bottom:none;border-left:none;border-right:none;border-color:#000;border-collapse:collapse;margin:20px auto;padding:5px;\">';
								txt+='<tbody><tr><td><img id=\"footer-img\" src=\"http://makewear.com.ua/templates/shop1/images/email/mw_logo.jpg\" alt=\"makewear.com\" style=\"margin: 10px;width: 100px;\"></td>';
								txt+='<td style=\'vertical-align: middle;\'><img class=\"footer-i\" src=\"http://makewear.com.ua/templates/shop1/images/email/call_fot.png\" alt=\"phone\"></td>';
								txt+='<td>';
								txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">+38 (099) 098-00-82</font></p>';
								txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">+38 (098) 474-22-82</font></p>';
								txt+='<p class=\"footer-href\" style=\"color: #696969; font-size: 14px; margin: 10px 0; text-decoration: none;\"><font face=\"CenturyGothic\">info@makewear.com.ua</font></p></td>';
								txt+='<td style=\'vertical-align: middle;\'><img class=\"footer-i\" src=\"http://makewear.com.ua/templates/shop1/images/email/time_fot.png\" alt=\"phone\"></td>';
								txt+='<td><p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">Пн-пт с 9:00 до 20:00</font></p>';
								txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">Сб-вс с 10:00 до 18:00</font></p>';
								txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">Доставка осуществляется <br>в режиме работы служб доставки</font></p></td>';
								txt+='<td style=\'vertical-align: middle;\'><img class=\"footer-i\" src=\"http://makewear.com.ua/templates/shop1/images/email/info_fot.png\" alt=\"phone\"></td>';
								txt+='<td><p style=\"color: #696969; font-size: 14px; margin: 10px 0;\">';
								txt+='<font face=\"CenturyGothic\">';
								txt+='<a href=\"http://makewear.com.ua/\">www.makewear.com.ua</a></font></p>';
								txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\">';
								txt+='<font face=\"CenturyGothic\">';
								txt+='<a class=\"footer-href\" href=\"http://makewear.com.ua/company/\" style=\"color: #696969; text-decoration: none;\">О компании</a></font></p>';
								txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\">';
								txt+='<font face=\"CenturyGothic\">';
								txt+='<a class=\"footer-href\" href=\"http://makewear.com.ua/oplata_dostavka/\" style=\"color: #696969; text-decoration: none;\">Оплата доставка</a>';
								txt+='</font></p></td></tr></tbody></table>';
								txt+='<div style=\'position: relative;\'>';
								txt+=clip+'</div>';
								txt+='</div>';
							
							// //alert(rel);
							$('.sent_html').html(txt);	
							if($(this).find("div").hasClass("icon_sent")){
								var wasSent=$(this).find(".icon_sent").attr("rel-was-sent");
								$(".sent_order").text("Отправить повторно ("+wasSent+")");
							}else{
								$(".sent_order").text("Отправить");
							}				
							
							$('.send_body').show();
										
							//-----Resize-----
								var h=$(window).height();
								var h1=h-250;
								h-=50;
								// $('.send_window').css({'height':h+'px'});
								// $('.sent_html').css({'height':h1+'px'});
						});
						$('.close_window, .icon_close2 ').click(function(){
							$('.send_body').hide();
							$('body').css({'overflow':'auto'});
						});

		$('.maill1').click(function(){
					$('body').css({'overflow':'hidden'});
					var rel=$(this).attr('rel');
					
					$('.sent_order').attr('rel',rel);
					$('.sent_order').attr('rel-status',1);

					// var cli_rel=$(this).attr("rel-client");
					var cli_rel=rel;
					
					var name=$('.get_name').val();
					var tel=$('.get_tel').val();
					var city=$('.get_city').val();
					var address=$('.get_add').val();
					var mail=$('.get_mail').val();
					$('.tab_send_towhere').val(mail);

					// alert(rel+"= d"+arr[rel].length);
					// alert(rel+"= d"+arr[rel][0]['delivery']);
					var ways_delivery=arr[rel][0]['delivery'];
					if(ways_delivery==null){
						ways_delivery='';
					}

					// alert(rel+"="+city);
					
					var offer=$('.oc'+rel).attr('rel');		
					var date=$('.oc'+rel).attr('rel_data');				
					
					var t = new Date();
					var tt=t.getDate()+'.'+(t.getMonth()+1)+'.'+t.getFullYear();
					
					var cli_text='';
						cli_text+='<table border=1 style=\'border-collapse: collapse;\'>';
						cli_text+='<tr><td colspan=2 style=\'background:gray;color:white;padding: 3px;\' ><b>Детали заказа</b></td></tr>';
						cli_text+='<tr><td style=\'padding:3px;\'><b>Время заказа</b></td><td style=\'padding: 3px;\'>'+date+'</td></tr>';
						cli_text+='<tr><td style=\'padding:3px;\'><b>Статус</b></td><td style=\'padding: 3px;\'> Подтвержден </td></tr>';
						cli_text+='<tr><td style=\'padding:3px;\'><b>Получатель</b></td><td style=\'padding: 3px;\'>'+name+'</td></tr>';
						cli_text+='<tr><td style=\'padding:3px;\'><b>Город</b></td><td style=\'padding: 3px;\'>'+city+'</td></tr>';
						cli_text+='<tr><td style=\'padding:3px;\'><b>Курьерская служба</b></td><td style=\'padding: 3px;\'>'+ways_delivery+'</td></tr>';
						cli_text+='<tr><td style=\'padding:3px;\'><b>Адрес доставка</b></td><td style=\'padding: 3px;\'>'+address+'</td></tr>';
						cli_text+='<tr><td style=\'padding:3px;\'><b>Телефон</b></td><td style=\'padding: 3px;\'>'+tel+'</td></tr>';
						cli_text+='<tr><td style=\'padding:3px;\'><b>E-mail</b></td><td style=\'padding: 3px;\'>'+mail+'</td></tr>';
						cli_text+='</table>';
									
					var tab='<table style=\'width:100%;border-collapse:collapse;\'>';
						tab+='<tr style=\'background: gray;\'>';
						tab+='<th style=\'border: 1px solid black;text-align: center;vertical-align: middle;padding: 7px;font-weight: bold;color: white;\'>Бренд</th>';
						tab+='<th style=\'border: 1px solid black;text-align: center;vertical-align: middle;padding: 7px;font-weight: bold;color: white;\'>Артикул</th>';
						tab+='<th style=\'border: 1px solid black;text-align: center;vertical-align: middle;padding: 7px;font-weight: bold;color: white;\'>Цвет</th>';
						tab+='<th style=\'border: 1px solid black;text-align: center;vertical-align: middle;padding: 7px;font-weight: bold;color: white;\'>Размер</th>';
						tab+='<th style=\'border: 1px solid black;text-align: center;vertical-align: middle;padding: 7px;font-weight: bold;color: white;\'>Категория</th>';
						tab+='<th style=\'border: 1px solid black;text-align: center;vertical-align: middle;padding: 7px;font-weight: bold;color: white;\'>Кол-во</th>';
						tab+='<th style=\'border: 1px solid black;text-align: center;vertical-align: middle;padding: 7px;font-weight: bold;color: white;\'>Цена</th>';
						tab+='<th style=\'border: 1px solid black;text-align: center;vertical-align: middle;padding: 7px;font-weight: bold;color: white;\'>Сумма</th>';
						tab+='<th style=\'border: 1px solid black;text-align: center;vertical-align: middle;padding: 7px;font-weight: bold;color: white;\'>Валют</th>';
						tab+='<th style=\'border: 1px solid black;text-align: center;vertical-align: middle;padding: 7px;font-weight: bold;color: white;\'>Ссылка на товар</th>';
						tab+='<th style=\'border: 1px solid black;text-align: center;vertical-align: middle;padding: 7px;font-weight: bold;color: white;\'>Статус</th>';
						tab+='</tr>';
					var count=0;
					var price=0;
						//alert(arr[rel][0]['art']);
						for(i=0; i<arr[rel].length; i++){
							var sts='';
							if(arr[rel][i]['status']=='Нет в наличии'){
								sts='background: #FFD6D6;';
							}

							tab+='<tr style=\''+sts+'\' >';
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'>'+arr[rel][i]['brands']+'</td>';
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'>'+arr[rel][i]['art']+'</td>';
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'>'+arr[rel][i]['color']+'</td>';
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'>'+arr[rel][i]['size']+'</td>';
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'>'+arr[rel][i]['cat']+'</td>';
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'>'+arr[rel][i]['count']+'</td>';
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'>'+arr[rel][i]['price']+'</td>';
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'>'+arr[rel][i]['all_price']+'</td>';
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'>'+arr[rel][i]['cur']+'</td>';
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'><a href='+arr[rel][i]['url']+' >'+arr[rel][i]['name']+'</a></td>';
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'>'+arr[rel][i]['status']+'</td>';
							tab+='</tr>';						

							if(arr[rel][i]['status']!='Нет в наличии'){
								price+=parseInt(arr[rel][i]['all_price']);
								count+=parseInt(arr[rel][i]['count']);
							}
							
						}	
						
					tab+='<tr style=\'font-weight: bold;\'>';
							tab+='<td style=\'text-align:center;vertical-align:middle;padding:7px;\'>ИТОГО:</td>';
							tab+='<td style=\'text-align:center;vertical-align:middle;padding:7px;\'></td>';
							tab+='<td style=\'text-align:center;vertical-align:middle;padding:7px;\'></td>';
							tab+='<td style=\'text-align:center;vertical-align:middle;padding:7px;\'></td>';
							tab+='<td style=\'text-align:center;vertical-align:middle;padding:7px;\'></td>';
							tab+='<td style=\'text-align:center;vertical-align:middle;padding:7px;\'>'+count+'</td>';
							tab+='<td style=\'text-align:center;vertical-align:middle;padding:7px;\'></td>';
							tab+='<td style=\'text-align:center;vertical-align:middle;padding:7px;\'>'+price+'</td>';
							tab+='<td style=\'text-align:center;vertical-align:middle;padding:7px;\'></td>';
							tab+='<td style=\'text-align:center;vertical-align:middle;padding:7px;\'></td>';
							tab+='</tr>';

							tab+='</table>';
					var commision=del[rel]['commision'];
					if(isNaN(commision) || commision==null){
						commision=0;
					}	
					var rez='<table>';
						rez+='<tr><td style=\'width: 150px;padding-bottom: 7px;\'><b>Количество товаров:</b></td><td>'+count+' единиц</td></tr>';
						rez+='<tr><td style=\'padding-bottom: 7px;\'><b>Стоимость товаров:</b></td><td>'+price+' '+arr[rel][0]['cur']+'</td></tr>';
						rez+='<tr><td style=\'padding-bottom: 7px;\'><b>Комиссия (3%):</b></td><td>'+commision+' '+arr[rel][0]['cur']+'</td></tr>';
						rez+='<tr><td style=\'padding-bottom: 7px;\'><b>Стоимость доставки:</b></td><td>'+del[rel]['del_price']+' '+arr[rel][0]['cur']+'</td></tr>';
						price+=parseInt(del[rel]['del_price'])+parseFloat(commision);				
						rez+='<tr><td style=\'padding-bottom: 7px;\'><b>Сумма к оплате:</b></td><td><div style=\'background: gray;color: white;padding: 4px;\'><b>'+price+' '+arr[rel][0]['cur']+'</b></div></td></tr>';
						rez+='</table>';
							
					var txt='';
						txt+='<div style=\'margin: 13px;margin-left: 28px;margin-right: 28px;font-size: 13px;\'>';
						txt+='<div><a style=\"margin-left:10px;\" href=\"http://www.makewear.com.ua/\" target=\"_blank\"><img src=\"http://makewear.com.ua/templates/shop1/images/makewear290x1.jpg\" alt=\"makewear.com\" border=\"0\" style=\"width: 262px;\"></a></div>';
					//	txt+='Здравствуйте, '+name+'<br/> Ваш заказ принят в обработку, за его состоянием Ви можете наблюдать в <a href=\'#\'>личном кабинет</a><br/><br/>';
						txt+='Здравствуйте.<div style=\'padding-top: 6px;padding-bottom: 6px;\'>Произведено уточнение складского наличия товаров Вашего заказа.</div>Пожалуйста, подтвердите Ваш заказ, ответив на это письмо, либо свяжитесь с нами любым, удобным для Вас, способом.<br/><br/>';
						txt+='<b> Заказ №'+offer+' от '+tt+'</b>'						
						txt+='<hr width=100% /><br>';
						txt+=cli_text;
						txt+='<br/>';
						txt+=tab;
						txt+='<br/>';
						txt+=rez;	
						txt+='<br/><hr width=100% /><br/>';
						txt+='<table id=\"footer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-bottom:none;border-left:none;border-right:none;border-color:#000;border-collapse:collapse;margin:20px auto;padding:5px;\">';
						txt+='<tbody><tr><td><img id=\"footer-img\" src=\"http://makewear.com.ua/templates/shop1/images/email/mw_logo.jpg\" alt=\"makewear.com\" style=\"margin: 10px;width: 100px;\"></td>';
						txt+='<td style=\'vertical-align: middle;\'><img class=\"footer-i\" src=\"http://makewear.com.ua/templates/shop1/images/email/call_fot.png\" alt=\"phone\"></td>';
						txt+='<td>';
						txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">+38 (099) 098-00-82</font></p>';
						txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">+38 (098) 474-22-82</font></p>';
						txt+='<p class=\"footer-href\" style=\"color: #696969; font-size: 14px; margin: 10px 0; text-decoration: none;\"><font face=\"CenturyGothic\">info@makewear.com.ua</font></p></td>';
						txt+='<td style=\'vertical-align: middle;\'><img class=\"footer-i\" src=\"http://makewear.com.ua/templates/shop1/images/email/time_fot.png\" alt=\"phone\"></td>';
						txt+='<td><p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">Пн-пт с 9:00 до 20:00</font></p>';
						txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">Сб-вс с 10:00 до 18:00</font></p>';
						txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">Доставка осуществляется <br>в режиме работы служб доставки</font></p></td>';
						txt+='<td style=\'vertical-align: middle;\'><img class=\"footer-i\" src=\"http://makewear.com.ua/templates/shop1/images/email/info_fot.png\" alt=\"phone\"></td>';
						txt+='<td><p style=\"color: #696969; font-size: 14px; margin: 10px 0;\">';
						txt+='<font face=\"CenturyGothic\">';
						txt+='<a href=\"http://makewear.com.ua/\">www.makewear.com.ua</a></font></p>';
						txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\">';
						txt+='<font face=\"CenturyGothic\">';
						txt+='<a class=\"footer-href\" href=\"http://makewear.com.ua/company/\" style=\"color: #696969; text-decoration: none;\">О компании</a></font></p>';
						txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\">';
						txt+='<font face=\"CenturyGothic\">';
						txt+='<a class=\"footer-href\" href=\"http://makewear.com.ua/oplata_dostavka/\" style=\"color: #696969; text-decoration: none;\">Оплата доставка</a>';
						txt+='</font></p></td></tr></tbody></table>';
					
					//alert(rel);
					$('.sent_html').html(txt);	

					if(rel>=433){	

					if($(this).find("div").hasClass("icon_sent")){
						var wasSent=$(this).find(".icon_sent").attr("rel-was-sent");
						$(".sent_order").text("Отправить повторно ("+wasSent+")");

						$.ajax({
							type:'POST',
							url: 'http://'+window.location.hostname+'/modules/commodities/ajax/ajax_get_mail.php',
							data:{id:rel}
						}).done(function(data){
							//alert(data);
							$('.sent_html').html(data);
							$('.send_body').show();
						});
					}else{
						$(".sent_order").text("Отправить");
						$('.send_body').show();
					}	

					}else{
						$('.send_body').show();
					}				
					
				//	$('.send_body').show();
								
					//-----Resize-----
					var h=$(window).height();
					var h1=h-250;
					h-=50;
					//$('.send_window').css({'height':h+'px'});
					//$('.sent_html').css({'height':h1+'px'});
				});
				$('.close_window, .icon_close').click(function(){
					$('.send_body').hide();
					$('body').css({'overflow':'auto'});
				});
					
				
				$('.sent_order').click(function(){
					var where=$('.tab_send_towhere').val();
					var whom=$('.tab_send_whom').val();
					var sub=$('.tab_send_subject').val();

					var txt=$('.sent_html').html();
					
					var id=$(this).attr('rel');
					var relStatus=$(this).attr('rel-status');

					var sent=$('.sentt'+id+" .icon_sent");
					var countSent=sent.attr("rel-was-sent");
					countSent++;

					if(relStatus=='1')
						var data2={where:where, whom:whom, sub:sub, txt:txt, status_oc:1, id:id };
					if(relStatus=='2')
						var data2={where:where, whom:whom, sub:sub, txt:txt, id:id, sent_mw_k:1 };

					$.ajax({
						type:'POST',
						url: 'http://'+window.location.hostname+'/modules/commodities/ajax/ajax_send.php',
						data:data2
					}).done(function(dd){
						alert(dd);
						$('.send_body').hide();
						if(dd.indexOf('Отправил')!=-1)
							$('.sentt'+id).html('<div class=\'icon_sent\' rel-was-sent='+countSent+' ></div><span style=\'text-decoration:underline;color: rgb(248, 106, 5);\'>отправлен</span>');
						$('body').css({'overflow':'auto'});
					});
				});	
				
				$('.sent_html').click(function(){
					$(this).attr('contenteditable','true');
				});				
$('.icon_clip_will').click(function(){
							var rel=$(this).attr('rel');

							f_icon_clip_will(rel);
							$(".any_file input[type=file], .delete_file").attr("rel",rel);
							
						});
						$('.close_upload').click(function(){
							$('.body_upload').hide();
							$('.wind_upload').show();
							$('.show_file').hide();
							$('body').css({'overflow':'auto'});
						});
						$('.upload').on('change', function(event){
							var rel=$(this).attr('rel');
						 	var files = event.target.files;
							var data = new FormData();
							
							var name='';
							var size='';							
							
							$.each(files, function(key, value)
							{
								name=value.name;
								size=value.size;
								
							    data.append(key, value);
							   
							});
							
							$.ajax({
								type:'POST',
								url:'modules/commodities/ajax/upload_ajax3.php',
								cache: false,
						      	processData: false,
						      	contentType: false,
								data:data
							})
							.done(function(d){
								//alert(d);
								if(d==1){
									//alert('Загрузил документ');
									open_file(rel, name, size);	
									var ffile=name.split('.');
									// if(ffile[1]=='pdf' || ffile[1]=='PDF'){
									// 	$('.see_file').html('<object data=\"/uploads/delivery_MW_K/'+rel+'.'+ffile[1]+'\" type=\"application/pdf\" width=\"800px\" height=\"700px\"> </object>');
									// }else{
							  //   		$('.see_file').html('<img src=\"/uploads/delivery_MW_K/'+rel+'.'+ffile[1]+'\" style=\'width:600px\' />');	
							  //   	}
									$('.set_size').text((size/1024).toFixed(1)+' KB');	
									$('.set_name').text(name);	

									$('.sentt'+rel+' div').css({'display':'block'});
									$('.sentt'+rel+' .icon_send_blue').remove();				
								}else if(d==0){
									alert('Error');	
								}
							 	if(d==2){
									alert('Допустимые форматы: JPG, PDF ');							 	
							 	}
							});

						 });
						$(".delete_file").click(function(){
							var rel=$(this).attr("rel");
							var file_name=$(".set_name").text();
							var pro=confirm("Удалить файл "+file_name+"?");
							if(pro){
								$.ajax({
									type:'POST',
									url:'modules/commodities/ajax/delete_file.php',
									data:{path:'delivery_MW_K', rel:rel, file:file_name}
								})
								.done(function(data){
									$(".change_clip"+rel).empty().html("<div class=\"icon_clip_will\" style=\"cursor:pointer;\" rel=\""+rel+"\" onclick='f_icon_clip_will("+rel+")'></div>");
									$('.body_upload').hide();
									$('.wind_upload').show();
									$('.show_file').hide();
									$('body').css({'overflow':'auto'});
								});
							}
						});
						$('.icon_clip_was').click(function(){
							autoClosePopUp();
							var rel=$(this).attr('rel');
						 	f_icon_clip_was('icw'+rel);
						 	$(".any_file input[type=file], .delete_file").attr("rel",rel);
						});


						function open_file(rel,f_name, s_size){
					//	alert('db '+f_name);
						$('.wind_upload').hide();
						$('.show_file').show();
						var ffile=f_name.split('.');
						$('.change_clip'+rel).html('<div class=\'icon_clip_was icw'+rel+'\' onclick=f_icon_clip_was(\'icw'+rel+'\') style=\'cursor:pointer;\' rel='+rel+' rel-file=\''+rel+'.'+ffile[1]+'\' rel-size='+s_size+' rel-name='+f_name+' ></div>');
						$.ajax({
							type:'POST',
							url:'modules/commodities/ajax/upload_db.php',
							data:{path:'delivery_MW_K' , id:rel, file_name:f_name}	
						})
						.done(function(d){
						//		alert(d)
							var namee=d;
							$('.see_file').empty();
							if(namee.indexOf('pdf')!=-1 || namee.indexOf('PDF')!=-1){
								$('.see_file').html('<object data=\"'+namee+'\" type=\"application/pdf\" width=\"800px\" height=\"700px\"> </object>');
							}else{
								$('.see_file').html('<img src=\"'+name+'\" style=\'width:600px\' />');
							}	
						});
					}
					function f_icon_clip_will(rel){
						$('.body_upload').show();
						$('body').css({'overflow':'hidden'});
						//var rel=$(this).attr('rel');
						$('.upload').attr('rel',rel);
						var count=$('#client_open'+rel+' .cli_count').text();
						var deli=$('#gh'+rel+' td:nth-child(9)').text();
						
						$('#get_upload_order').text(rel);
						$('#get_count').text(count);
						$('#get_deli').text(deli);
					}
					function f_icon_clip_was(ttt){
						$('body').css({'overflow':'hidden'});
						$('.body_upload').show();
						$('.wind_upload').hide();
						$('.show_file').show();
									
						var name=$('.'+ttt).attr('rel-file');
						var name2=$('.'+ttt).attr('rel-name');
						var size=$('.'+ttt).attr('rel-size');							
						if(name.indexOf('pdf')!=-1 || name.indexOf('PDF')!=-1){
							$('.see_file').html('<object data=\"/uploads/delivery_MW_K/'+name+'\" type=\"application/pdf\" width=\"800px\" height=\"700px\"> </object>');
						}else{		
							$('.see_file').html('<img src=\"/uploads/delivery_MW_K/'+name+'\" style=\'width:600px\' />');
						}
						$('.set_size').text((size/1024).toFixed(1)+' KB');	
						$('.set_name').text(name2);
					}
				});

$('.html_mail').click(function(){
						$(this).attr('contenteditable','true');
					});

						$('.maill').click(function(){
							
							autoClosePopUp();
							$('body').css({"overflow": "hidden"});

							var rel2=$(this).attr('rel2');
							rel2=rel2.replace(' ', '');
							$('#from_to').val(rel2);


							rel=$(this).attr('rel');

							$('.sent_order').attr('rel',rel);
						
							$('.art').text(artt[rel]);

							$('#from_sub').val('Счёт-фактура. Заказ №'+artt[rel]);

							$('.pl_client2').html(client[rel]);	
	
							$('.ttbody').html(tab[rel]);
							
							$('.all_price2').text(off_sum[rel]);

							$('.sum').text(sum[rel]);
							$('.sent_order').attr('rel-price',parseFloat(sum[rel].trim()));

							$('.dost').text(dost[rel]);

							$('.comm').text(comm[rel]);

							$('.down_xls').attr('href','http://makewear.com.ua/email/download_excel.php?exportIdd='+rel);
							$('.down_print').attr('href','http://makewear.com.ua/email/print_client.php?cli_id='+rel);

							var relget=$('.icon_info').attr('rel-get');
							$.ajax({
							 	method: 'GET',
							 	url:'/modules/commodities/ajax/rezvisit.php',
							 	data:{get_rez:'true', relget:relget},
							 	dataType:'json'
							})
							.done(function(dd){
								$('#chetrez').html('');
								for(i=0; i<dd.length; i++){
									$('#chetrez').append(dd[i]['name']+': '+dd[i]['write']+'<br/>');

									if(dd[i]['name'].indexOf('елефон')!=-1){
										$('#inphone').html('');
										var ph=dd[i]['write'].split('+');
										for(j=1; j<ph.length; j++){
											$('#inphone').append('+'+ph[j]+'<br/>');
										}
									}
									if(dd[i]['name'].indexOf('mail')!=-1){
										$('#inemail').text(dd[i]['write']);
									}
								}

								$('#pay_body').show();
							});

							if($(this).find("div").hasClass("icon_sent")){
								var wasSent=$(this).find(".icon_sent").attr("rel-was-sent");
								$(".sent_order").text("Отправить повторно ("+wasSent+")");
							}	

						});	


						$('.pay_close, .pay_close2').click(function(){
							$('#pay_body').hide();
							$('body').removeAttr("style");
						});	
						


						$('.sent_order').click(function(){
							var rel=$(this).attr('rel');
							var up_to=$('#from_to').val();
							var up_whom=$('.in_text').val();
							var up_sub=$('#from_sub').val();

							var msg=$('.html_mail').html();	

							var price=$(this).attr('rel-price');

							var sent=$('#client_open'+rel+" .maill .icon_sent");
							var countSent=sent.attr("rel-was-sent");
							countSent++;

							$.ajax({
								type:'POST',
								url: 'http://'+window.location.hostname+'/modules/commodities/ajax/ajax_send.php',
								data:{where:up_to, whom:up_whom, sub:up_sub, txt:msg, payment_k:1, id:rel, price:price}
							}).done(function(dd){
								alert(dd);
								$('#pay_body').hide();
								if(dd.indexOf('Отправил')!=-1)
									$('#client_open'+rel+" .maill").html('<span><div class=\'icon_sent\' rel-was-sent='+countSent+' ></div></span>');		
							});

						 });
