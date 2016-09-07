			$(document).ready(function(){
				var client_rel=0;
				$(".w_cli .tr_client").each(function(){
					if($(this).hasClass('client')){
						client_rel=$(this).attr('rel-tr');	
					}else{
						$(this).find(".i_sent").attr("rel-client",client_rel);
					}
					
				});


				$('.w_cli tr').each(function(){
					var mm=0;
					$(this).find('.hdiv').each(function(){
						var tt=$(this).height();
						if(mm<tt){
							mm=tt;
						}
					});
					$(this).find('.hdiv').css({'height':mm+'px'});
				});
				$('.open_commodity').click(function(){
					var rel=$(this).attr('rel');
					if($('.oc'+rel).hasClass('tab_up')){
						$('.win_line').slideUp();
						$('.orders_head').removeClass('tab_down');
						$('.orders_head').addClass('tab_up');

						$('.oc'+rel).removeClass('tab_up');
						$('.oc'+rel).addClass('tab_down');
						$('#wl'+rel).slideDown(function(){
							$('#bb'+rel).removeClass('block_down');
							$('#bb'+rel).addClass('block_up');
						});
					}else{
						$('#wl'+rel).slideUp(function(){
							$('.oc'+rel).removeClass('tab_down');
							$('.oc'+rel).addClass('tab_up');
							$('#bb'+rel).removeClass('block_up');
							$('#bb'+rel).addClass('block_down');
						});
					}

				});
				$('.go_href').click(function(){
					var hh=$(this).attr('date_href');
					window.open(hh, '_blank');	
				});
				$('.i_sent').click(function(){

					autoClosePopUp();

					$('body').css({'overflow':'hidden'});
					var rel=$(this).attr('rel');
					
					$('.sent_order').attr('rel',rel);

					var cli_rel=$(this).attr("rel-client");
					
					var name=$('#cli'+cli_rel+' .get_name').text();
					var tel=$('#cli'+cli_rel+' .get_tel').text();
					var city=$('#cli'+cli_rel+' .get_city').text();
					var address=$('#cli'+cli_rel+' .get_add').text();
					var mail=$('#cli'+cli_rel+' .get_mail').text();
					$('.tab_send_towhere').val(mail);
					
					var ways_delivery=arr[rel][0]['delivery'];
					if(ways_delivery==null){
						ways_delivery='';
					}
					
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
						tab+='<th style=\'border: 1px solid black;text-align: center;vertical-align: middle;padding: 7px;font-weight: bold;color: white;\'>Комментарий</th>';
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
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'>'+arr[rel][i]['comment']+'</td>';
							tab+='<td style=\'border:1px solid;vertical-align:middle;padding:7px;\'><a href='+arr[rel][i]['url']+' target=\'_blank\' >'+arr[rel][i]['name']+'</a></td>';
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
					var discount=del[rel]['discount'];
					var delivery2=del[rel]['del_price']+' '+arr[rel][0]['cur'];
					var delivery3=parseInt(del[rel]['del_price']);
					var price2=price;
					if(discount==1){
						price2-=150;
					}
					if(discount==2){
						price2-=price2/100*10;
					}
					if(discount==3){
						delivery2="Бесплатная";
						delivery3=0;
					}
					price2+=delivery3;
					var ski=del[rel]['ski'];
					if(ski==null){
						ski="";
					}
					if(isNaN(commision) || commision==null){
						commision=0;
					}	
					var rez='<table>';
						rez+='<tr><td style=\'width: 150px;padding-bottom: 7px;\'><b>Количество товаров:</b></td><td>'+count+' единиц</td></tr>';
						rez+='<tr><td style=\'padding-bottom: 7px;\'><b>Стоимость товаров:</b></td><td>'+price+' '+arr[rel][0]['cur']+'</td></tr>';
						rez+='<tr><td style=\'padding-bottom: 7px;\'><b>Скидка:</b></td><td>'+ski+'</td></tr>';
						rez+='<tr><td style=\'padding-bottom: 7px;\'><b>Подарок:</b></td><td>'+del[rel]['gift']+'</td></tr>';
						rez+='<tr><td style=\'padding-bottom: 7px;\'><b>Стоимость доставки:</b></td><td>'+delivery2+'</td></tr>';
						price+=parseInt(del[rel]['del_price'])+parseFloat(commision);				
						rez+='<tr><td style=\'padding-bottom: 7px;\'><b>Сумма к оплате:</b></td><td><div style=\'background: gray;color: white;padding: 4px;\'><b>'+price2+' '+arr[rel][0]['cur']+'</b></div></td></tr>';
						rez+='</table>';
							
					var txt='';
						txt+='<div style=\'margin: 13px;margin-left: 28px;margin-right: 28px;font-size: 13px;\'>';
						txt+='<div style="margin-bottom: 10px;" ><a href=\"http://www.makewear.com.ua/\" target=\"_blank\"><img src=\"http://makewear.com.ua/templates/shop/image/logo.png\" alt=\"makewear.com\"></a></div>';
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
						txt+='<tbody><tr><td><img id=\"footer-img\" src=\"http://makewear.com.ua/templates/shop/image/email/mw_logo.jpg\" alt=\"makewear.com\" style=\"margin: 10px;width: 100px;\"></td>';
						txt+='<td style=\'vertical-align: middle;\'><img class=\"footer-i\" src=\"http://makewear.com.ua/templates/shop/image/email/call_fot.png\" alt=\"phone\"></td>';
						txt+='<td>';
						txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">+38 (099) 098-00-82</font></p>';
						txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">+38 (098) 474-22-82</font></p>';
						txt+='<p class=\"footer-href\" style=\"color: #696969; font-size: 14px; margin: 10px 0; text-decoration: none;\"><font face=\"CenturyGothic\">info@makewear.com.ua</font></p></td>';
						txt+='<td style=\'vertical-align: middle;\'><img class=\"footer-i\" src=\"http://makewear.com.ua/templates/shop/image/email/time_fot.png\" alt=\"phone\"></td>';
						txt+='<td><p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">Пн-пт с 9:00 до 20:00</font></p>';
						txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">Сб-вс с 10:00 до 18:00</font></p>';
						txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">Доставка осуществляется <br>в режиме работы служб доставки</font></p></td>';
						txt+='<td style=\'vertical-align: middle;\'><img class=\"footer-i\" src=\"http://makewear.com.ua/templates/shop/image/email/info_fot.png\" alt=\"phone\"></td>';
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

					// if(rel>=433){	

					// if($(this).find("div").hasClass("icon_sent")){
					// 	var wasSent=$(this).find(".icon_sent").attr("rel-was-sent");
					// 	$(".sent_order").text("Отправить повторно ("+wasSent+")");

					// 	$.ajax({
					// 		type:'POST',
					// 		url: 'http://'+window.location.hostname+'/modules/commodities/ajax/ajax_get_mail.php',
					// 		data:{id:rel}
					// 	}).done(function(data){
					// 		//alert(data);
					// 		$('.sent_html').html(data);
					// 		$('.send_body').show();
					// 	});
					// }else{
					// 	$(".sent_order").text("Отправить");
					// 	$('.send_body').show();
					// }	

					// }else{
					// 	$('.send_body').show();
					// }				
					
					$('.send_body').show();
								
					//-----Resize-----
					var h=$(window).height();
					var h1=h-250;
					h-=50;
					//$('.send_window').css({'height':h+'px'});
					//$('.sent_html').css({'height':h1+'px'});
				});
				
				$('.write_email').click(function(){

					$('body').css({'overflow':'hidden'})
					var rel=$(this).attr('rel');
					//alert(rel);
					
					//$('.sent_order').attr('rel',rel);
					var mail=$('#cli'+rel+' .get_mail').text();
					$('.tab_send_towhere').val(mail);
							
					 var txt='';
					 	txt+='<div style=\' margin: 10px 28px;font-size: 13px;\'>';
						txt+=''	
						txt+='<br/><br/><hr width=100% /><br/>';
						txt+='С увамением MakeWear<br/>Телефон: +38 (098) 474-22-82, +38 (099) 098-00-82<br/> <a target=\'_blank\' href=\'http://www.makewear.com.ua\'>www.makewear.com.ua</a>';
						txt+='</div>';
					 $('.sent_html').html(txt);	
					 $(".sent_order").text("Отправить");				
					
					$('.send_body').show();
								
					//-----Resize-----
						var h=$(window).height();
						var h1=h-250;
						h-=50;
						//$('.send_window').css({'height':h+'px'});
						//$('.sent_html').css({'height':h1+'px'});
				})

				$(window).resize(function(){
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

					var sent=$('.sentt'+id+" .icon_sent");
					var countSent=sent.attr("rel-was-sent");
					countSent++;

					$.ajax({
						type:'POST',
						url: 'http://'+window.location.hostname+'/modules/commodities/ajax/ajax_send.php',
						data:{where:where, whom:whom, sub:sub, txt:txt, status_oc:1, id:id }
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
					
				$('.open_commodity').each(function(){
					var rel=$(this).attr('rel');
					var ttt=1;
					$('#wl'+rel+' table td select option:selected').each(function(){
						if($(this).text()==''){
							ttt=0;
						}
					});
					if(ttt==0){
						$('.sentt'+rel).html('<span style=\'color:#00455C\' ><u><div class=icon_send_blue style=\'float:left;\' ></div>отправить</u></span>');
					}
					
				});	
				$('.wind_o2').click(function(){
					var rel=$(this).attr('rel');
		
					if($(this).hasClass('open_backg')){
						$('#open_win2'+rel).slideDown();		
						$(this).removeClass('open_backg');
						$(this).addClass('close_backg');
						$('.iiw'+rel).removeClass('icon_info_white');
						$('.iiw'+rel).addClass('icon_info_blue');
						$('.bbc'+rel).removeClass('block_down1');
						$('.bbc'+rel).addClass('block_up1');
					}else{
						$('#open_win2'+rel).slideUp();
						$(this).addClass('open_backg');
						$(this).removeClass('close_backg');
						$('.iiw'+rel).removeClass('icon_info_blue');
						$('.iiw'+rel).addClass('icon_info_white');
						$('.bbc'+rel).addClass('block_down1');
						$('.bbc'+rel).removeClass('block_up1');
					}
				});
				$('.wind_o2_orange').click(function(){
					var rel=$(this).attr('rel');

					if($(this).hasClass('open_backg')){
						$('#open_win2'+rel).slideDown();		
						$(this).removeClass('open_backg');
						$(this).addClass('close_backg');
						$('.iiw'+rel).removeClass('icon_info_orange');
						$('.iiw'+rel).addClass('icon_info_blue');
						$('.bbc'+rel).removeClass('block_down1_orange');
						$('.bbc'+rel).addClass('block_up1');
					}else{
						$('#open_win2'+rel).slideUp();
						$(this).addClass('open_backg');
						$(this).removeClass('close_backg');
						$('.iiw'+rel).removeClass('icon_info_blue');
						$('.iiw'+rel).addClass('icon_info_orange');
						$('.bbc'+rel).addClass('block_down1_orange');
						$('.bbc'+rel).removeClass('block_up1');
					}
				});
				$('.write_tab').keyup(function(){
					var rel=$(this).attr('rel');
					var txt=$(this).text();
					$.ajax({
						method:'GET',
						url:'/modules/commodities/ajax/ajax_write.php',
						data:{rel_id2:rel, txt:txt, rel_db_tab:'note'},
					}); //.done(function(d){alert(d);});
				});
//----CHAT--------
			$('.icon_chat').click(function(){
				var rel=$(this).attr('rel');
				$('body').css({'cursor':'wait'});
				$(document).css({'cursor':'wait'});
				$.ajax({
					url:'/modules/mw_chat/chat.html',
				//	context: document.body
				})
				.done(function(data){
					$('body').append(data);
					$('.line_name:nth-child(1)').addClass('ln_active');
					$('.include1').show();
					$('.cli_id').each(function(ii){
						var rel2=$(this).text();
						rel2=parseInt(rel2);
						var cod=$('.oc'+rel2).attr('rel');
						var name=$('.get_name:eq('+ii+')').text();
						//alert($('.oc'+rel2).attr('rel'));
						// var dhref=$(this).attr('date_href');
						// dhref=dhref.split('id');
						// var rel2=dhref[1];
						// rel2=rel2.replace('=','');
						// var cod=$('.cli_cod').eq(ii).text();
						 $('.include1 ul').append('<li id=\"uuser'+rel2+'\" rel=\"'+rel2+'\" ><i class=\"fa fa-user fauser\" ></i> №'+cod+' '+name+'</li>');
					});
					$(document).css({'cursor':'default'});
					$('#uuser'+rel).addClass('tb_active');

					var ll=$('.include1').offset().top;
					var oftop=$('#uuser'+rel).offset().top;
					var upo=30;
					if((oftop-ll-upo)<=0){
						$('.liuser').scrollTop(oftop-ll);
					}else{
						$('.liuser').scrollTop( oftop-ll-upo );
					}
				
				});
				$.ajax({
					method:'POST',
					url:'/modules/mw_chat/ajax/chat_operator.php',
					dataType:'json',
					data:{chat_operator:'1'}
				})
				.done(function(data){
					//alert(data);
					for(i=0; i<data.length; i++){
						var status='';
						var txt='';
						var email='';
						if(data[i]['status']==1){
							status='class=\"active\"';
							// txt=data[i]['text'];
							// email=data[i]['email'];
							$('.table_top tr:nth-child(1) td:last-child').text(data[i]['email']);
							$('.table_top tr:nth-child(2) td:last-child, #putchat').text(data[i]['text']);

						}
						
						$('.select_admin').append('<span '+status+' rel-id=\"'+data[i]['id']+'\" >'+data[i]['name']+'</span>');
					}

					$.getScript('/modules/mw_chat/chat.js');
					$('body').css({'cursor':'default'});
				});

				//if(){
					$.getScript('/modules/mw_chat/chat.js');
					$('body').css({'cursor':'default'});
				//}
			});

				screen_w();
				$(window).resize(function(){
					screen_w();
				});		
			});
			function screen_w(){
				var w=$(window).width();
				//$('.rees').text(w);
				
				if(w>=1100){
					$('.w_cli').removeAttr('style');
				}else{
					$('.w_cli').width('1200px');
				}
			}

			function autoClosePopUp(){
				$(".wind_o2").each(function(){
					var rel=$(this).attr("rel");
					$('#open_win2'+rel).hide();
					$(this).addClass('open_backg');
					$(this).removeClass('close_backg');
					$('.iiw'+rel).removeClass('icon_info_blue');
					$('.iiw'+rel).addClass('icon_info_white');
					$('.bbc'+rel).addClass('block_down1');
					$('.bbc'+rel).removeClass('block_up1');
				});
				$(".wind_o2_orange").each(function(){
					var rel=$(this).attr("rel");
					$('#open_win2'+rel).hide();
					$(this).addClass('open_backg');
					$(this).removeClass('close_backg');
					$('.iiw'+rel).removeClass('icon_info_blue');
					$('.iiw'+rel).addClass('icon_info_orange');
					$('.bbc'+rel).addClass('block_down1_orange');
					$('.bbc'+rel).removeClass('block_up1');
				});
			}
