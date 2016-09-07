				$(document).ready(function(){
					$(".forClient").change(function(){
						var rel=$(this).attr("rel");
						var rel_client=$(this).attr("rel-client");
						var relGroup=$(this).attr("rel-group");
						$.ajax({
							method:"GET",
							url:'http://'+window.location.hostname+'/modules/commodities/admin/auto_status_client.php',
							data:{rel:rel,rel_client:rel_client, relGroup:relGroup}
						});
						//$(".auto_set"+rel+' option[value="2"]').attr("selected", "selected");

						$(".auto_set"+relGroup+' option').removeAttr("selected");
						$(".auto_set"+relGroup+' option').eq(1).attr("selected", true)
						$(".auto_set"+relGroup).css({"background" : "rgb(255, 204, 0)"});
					});
					$(".changeCommodity").click(function(){
						var rel=$(this).attr("rel");
						var relGroup=$(this).attr("rel-group");
						var vall=$(this).val();
						if(vall==3){
							var count=$("#"+rel+" td").eq(5).text();
							var price=$("#"+rel+" td").eq(7).text();
							var summ=$("#"+rel+" td").eq(8).text();

							var getCount=$("#gr_set_count"+relGroup).text();
							var getSumm=$("#gr_set_summ"+relGroup).text();

							$.ajax({
								method:"GET",
								url:'http://'+window.location.hostname+'/modules/commodities/ajax/changeSelect.php',
								data:{rel:rel, count:0, price:0 }
							});

							$("#gr_set_count"+relGroup).text(getCount-count);
							$("#gr_set_summ"+relGroup).text(getSumm-summ);
							
							$("#"+rel+" td").eq(5).text(0);
							$("#"+rel+" td").eq(7).text(0);
							$("#"+rel+" td").eq(8).text(0);
						}
					});
//------------------------------------------------------------------------------------------------------
					$('.all_change').click(function(){
						var rel=$(this).attr('rel');
						
						if($(this).prop('checked')){
							$('#but22_tab'+rel+' .c2_trt').each(function(){
								$(this).prop('checked',true);
							});
						}else{
							$('#but22_tab'+rel+' .c2_trt').each(function(){
								$(this).prop('checked',false);
							});
						}
					});
					$('.change_price_opt').change(function(){
						var rel=$(this).attr('rel');
						var vall=$(this).val();
						var cur=$(this).attr('rel-cur');


						if(vall==1 || vall==2){
							$('body').css({'cursor':'wait'});
							$('#but22_tab'+rel+' .c2_trt:checked').each(function(){
								var rell=$(this).attr('rel');
								var relId=$(this).attr('rel-id');
								$.ajax({
									method:'GET',
									url:'http://'+window.location.hostname+'/modules/commodities/ajax/change_price_opt.php',
									data:{comid:relId, cliid:rell, change:vall, curid:cur},
									dataType:'json'
								})
								.done(function(data){
									//alert(data['price']);
									$('body').css({'cursor':'default'});
									var count=parseInt($('#'+rell+' td').eq(5).text());
									$('#'+rell+' td').eq(7).text(data['price']);
									$('#'+rell+' td').eq(8).text(data['price']*count);
								});
								//alert(rell+'='+relId);
							});
						}
					});

					$('.open_cont').click(function(){
						var rel=$(this).attr('rel');
						if($(this).hasClass('bu1')){
							close_win();
							
							var wp=$('.tab').height();
							var wo='';
							var tin=$('#ow1_'+rel+' .tin');	
							
							$('#ow1_'+rel).css({'z-index':'50'});
							$('#window_cont'+rel).animate({'max-height': '550px', 'height':'none'}, 200, function(){
								$('#bo1_'+rel).removeClass('block_down1');
								$('#bo1_'+rel).addClass('block_up1');
							//	$('#ow1_'+rel).css({'box-shadow': '2px 3px 7px black' });
								
								var wh=$('#ow1_'+rel).height();
								tin.fadeIn('5000');
								tin.css({'margin-top': (wp-3)+'px', 'height':(wh-wp+3)+'px'});
								
							});
							
							$(this).removeClass('bu1');
							$(this).addClass('bu2');
						}else{
							$('#window_cont'+rel).animate({'max-height': '80px', 'height':'83px'}, 300, function(){
								$('#ow1_'+rel).css({'z-index':'0', 'box-shadow': '0px 0px 0px'});
								$('#bo1_'+rel).removeClass('block_up1');
								$('#bo1_'+rel).addClass('block_down1');
							});
							$(this).removeClass('bu2');
							$(this).addClass('bu1');
							tin.fadeOut();
						}
					});
					$('.open_us').click(function(){
						var rel=$(this).attr('rel');
						
						
						var wp=$('.tab').height();
						var wo='';
						var tin=$('#ow2_'+rel+' .tin');	
										
						
						
						if($(this).hasClass('bu1')){
							close_win();
							$('#ow2_'+rel).css({'z-index':'50'});
							$('#window_us'+rel).animate({'max-height': '550px'},{duration: 1000, step:function(){
								$('#bo2_'+rel).removeClass('block_down1');
								$('#bo2_'+rel).addClass('block_up1');
								
								var wh=$('#ow2_'+rel).height();
								tin.fadeIn('1000');
								tin.css({'margin-top': (wp-3)+'px', 'height':(wh-wp+3)+'px'});
								
							}});
							
							$(this).removeClass('bu1');
							$(this).addClass('bu2');
						
						}else{
							$('#window_us'+rel).animate({'max-height': '80px'}, 300, function(){
								$('#ow2_'+rel).css({'z-index':'0', 'box-shadow': '0px 0px 0px'});

								$('#bo2_'+rel).removeClass('block_up1');
								$('#bo2_'+rel).addClass('block_down1');
							});
							$(this).removeClass('bu2');
							$(this).addClass('bu1');

							tin.fadeOut();
						}
						
						//$('.rees').text('wp:'+wp+', wo:'+wo);
					});
					$('.open_rez').click(function(){
						var rel=$(this).attr('rel');
						
						var wp=$('.tab').height();
						var wo='';
						var tin=$('#ow3_'+rel+' .tin');							
						
						if($(this).hasClass('bu1')){
							close_win();
							$('#ow3_'+rel).css({'z-index':'50'});
							$('#window_rez'+rel).animate({'max-height': '300px'},{duration: 1000, step: function(){
								$('#bo3_'+rel).removeClass('block_down1');
								$('#bo3_'+rel).addClass('block_up1');
								
								var wh=$('#ow3_'+rel).height();
								tin.fadeIn('5000');
								tin.css({'margin-top': (wp-3)+'px', 'height':(wh-wp+3)+'px'});
							}});
							
							$(this).removeClass('bu1');
							$(this).addClass('bu2');
						}else{
							$('#window_rez'+rel).animate({'max-height': '80px'}, 300, function(){
								$('#bo3_'+rel).removeClass('block_up1');
								$('#bo3_'+rel).addClass('block_down1');
								$('#ow3_'+rel).css({'z-index':'0', 'box-shadow': '0px 0px 0px'});
							});
							$(this).removeClass('bu2');
							$(this).addClass('bu1');
							tin.fadeOut();
						}
					});
					$('.but_open_win').click(function(){
						var rel=$(this).attr('rel');
						if($('#gh'+rel).hasClass('tab_up')){
							$('.open_commodity'+rel).slideDown(function(){
								$('#bb'+rel).removeClass('block_down');
								$('#bb'+rel).addClass('block_up');
							});
							$('#gh'+rel).removeClass('tab_up');
							$('#gh'+rel).addClass('tab_down');

						}else{
							$('.open_commodity'+rel).slideUp(200, function(){
								$('#gh'+rel).removeClass('tab_down');
								$('#gh'+rel).addClass('tab_up');
								$('#bb'+rel).removeClass('block_up');
								$('#bb'+rel).addClass('block_down');
							});
						}
					});
					$('.select_open').click(function(){
						var rel=$(this).attr('rel');

					//	$('.scc'+rel).toggle();

						$('.sc'+rel).toggle();

						if($(this).hasClass('select_open_line')){
							$(this).removeClass('select_open_line');
						}else{
							$(this).addClass('select_open_line');
						}
					});
					$('.select_change li').click(function(){

						var rel=$(this).attr('rel');
						var rel2=$(this).attr('rel2');
						var a='';
						$('.bb'+rel2).each(function(){
							if($(this).is(':checked')){
								var rr=$(this).attr('rel');
								$('.ssc'+rr).val(rel);	

								var data = {};
						        data['id'] = rel;
						        
						        data['comid'] = rr;
						        data['status'] = 'admin';
						        $.ajax({
						        	url: 'http://'+window.location.hostname+'/modules/commodities/admin/add_status_com.php',
						        	method:'GET',
						        	data: data,
						            success: function(data){
						            	// alert(data);
						            },
						        	error: function(data){
						                return(false);
						            }
						        });
							}
						});
						
						$('.select_change').hide();
						$('.select_open').removeClass('select_open_line');
					});
					$('.add_commodity').click(function(){
						var rel_gr=$(this).attr('rel');
						var rel_com=$(this).attr('rel_com');

						$('.bb'+rel_com).each(function(){
							if($(this).is(':checked')){
								var dddd='<tr class = \'group_td gr_tab\' id=\'\' rel=\'shop_orders_coms\' rel2=\'id\'>';
								//alert($(this).attr('rel'));
								var rrr=$(this).attr('rel');
								var data = {};
						        data['gr'] = rel_gr;
						        data['comid'] = rrr;
						        data['update_group'] = 'true';
						        $.ajax({
						        	url: 'http://'+window.location.hostname+'/modules/commodities/ajax/add_group.php',
						        	method:'GET',
						        	data: data,
						            success: function(data){
						            	// alert(data);
						            },
						        	error: function(data){
						                return(false);
						            }
						        });

						        $('#get_com_cols'+rrr+' .tab_td2').each(function(){
						        	if($(this).find('.ssc'+rrr)){
						        		dddd+='<td>'+$(this).html()+'</td>';
						        	}else{
						        		dddd+='<td>'+$(this).text()+'</td>';
						        	}
						        	
						        });
								$('#get_com_cols'+rrr).remove();

								$('.open_commodity'+rel_gr+' table').append(dddd+'</tr>');
							}
							$('.cll'+rel_com).hide();

							var gr_set_count=0;
							$('#but22_tab'+rel_gr+' td:nth-child(6)').each(function(){
								gr_set_count+=parseInt($(this).text());
							});
							$('#gr_set_count'+rel_gr).text(gr_set_count);

							var gr_set_sum=0;
							$('#but22_tab'+rel_gr+' td:nth-child(9)').each(function(){
								gr_set_sum+=parseInt($(this).text());
							});
							$('#gr_set_summ'+rel_gr).text(gr_set_sum);

						});

						
						//$(location).attr('href','http://'+window.location.hostname+'/?admin=orders_brands20');
					});
					
					//---Send mail order----------

					$('.send_mail').click(function(){
						$('body').css({'overflow':'hidden'});

						var rel=$(this).attr('rel');
						var rel2=$(this).attr('rel2');

						var rell=rel.split("_");
						
						$('.sent_order').attr('rel',rel);	

						var getOpt=$('#getSkidkaOpt'+rel2).text();
						getOpt=parseInt(getOpt.replace('%', ''));					
						var getRoz=$('#getSkidkaRoz'+rel2).text();					
						getRoz=parseInt(getRoz.replace('%', ''));


						var t = new Date();
						var tt=t.getDate()+'.'+(t.getMonth()+1)+'.'+t.getFullYear();
						var ti=t.getHours()+':'+t.getMinutes()+':'+t.getSeconds();

						$('.tab_send_subject').val('Заказ от MakeWear №'+rell[0]+' от '+tt);

						$.ajax({
							method:'GET',
							url: 'http://'+window.location.hostname+'/modules/commodities/ajax/ajax_send.php',
							data: {id:rel2}
						})
						.done(function(data){
							var who="";
							if(data.indexOf(";")!=-1){
								var arr=data.split(";");
								who=arr[0];
							}else{
								who=data;
							}
							$('.tab_send_towhere').val(who);
							$('.send_body').show();
						});

						var arr_p=[];
						$('.open_commodity'+rel+' table .c2_trt').each(function(){
							arr_p.push($(this).attr('rel'));
						});

						var tab='<div style=\'display:table;border-collapse: collapse;width: 100%;\'>';
							tab+='<div style=\'display:table-row\'>';
							tab+='<div style=\'display:table-cell;border:1px solid;text-align:center;vertical-align:middle;padding:7px;font-weight: bold;\'>Товар</div>';
							tab+='<div style=\'display:table-cell;border:1px solid;text-align:center;vertical-align:middle;padding:7px;font-weight: bold;\'>Артикул</div>';
							tab+='<div style=\'display:table-cell;border:1px solid;text-align:center;vertical-align:middle;padding:7px;font-weight: bold;\'>Цвет</div>';
							tab+='<div style=\'display:table-cell;border:1px solid;text-align:center;vertical-align:middle;padding:7px;font-weight: bold;\'>Размер</div>';
							tab+='<div style=\'display:table-cell;border:1px solid;text-align:center;vertical-align:middle;padding:7px;font-weight: bold;\'>Кол-во</div>';
							tab+='<div style=\'display:table-cell;border:1px solid;text-align:center;vertical-align:middle;padding:7px;font-weight: bold;\'>Валюта</div>';
							tab+='<div style=\'display:table-cell;border:1px solid;text-align:center;vertical-align:middle;padding:7px;font-weight: bold;\'>Цена</div>';
							tab+='<div style=\'display:table-cell;border:1px solid;text-align:center;vertical-align:middle;padding:7px;font-weight: bold;\'>Сумма</div>';
							tab+='<div style=\'display:table-cell;border:1px solid;text-align:center;vertical-align:middle;padding:7px;font-weight: bold;\'>Ссылка на товар</div>';
							tab+='</div>';
						
						var l=$('#but22_tab'+rel+' .gr_tab').length;
						var count=0;
						var price=0;
							$('#but22_tab'+rel+' .gr_tab').each(function(){
								var tabCount=parseInt($(this).find('td:eq(5)').text());
								var tabPrice=parseInt($(this).find('td:eq(7)').text());
								var PriceOrOpt=parseInt($(this).find('td:eq(8)').attr("rel-opt-roz"));
								var status=$(this).find('td:eq(11) select option:selected').val();
								
								if(status!=2){
									
									if(PriceOrOpt==1){
										tabPrice=tabPrice-(tabPrice/100*getRoz);
									}

									if(PriceOrOpt==2){
										tabPrice=tabPrice-(tabPrice/100*getOpt);
									}

									var tabSumm=tabPrice*tabCount;
									
									tab+='<div style=\'display:table-row\'>';
									tab+='<div style=\'display:table-cell;border:1px solid;vertical-align:middle;padding:7px;\'>'+$(this).find('td:eq(1)').text()+'</div>';
									tab+='<div style=\'display:table-cell;border:1px solid;vertical-align:middle;padding:7px;\'>'+$(this).find('td:eq(2)').text()+'</div>';
									tab+='<div style=\'display:table-cell;border:1px solid;vertical-align:middle;padding:7px;\'>'+$(this).find('td:eq(3)').text()+'</div>';
									tab+='<div style=\'display:table-cell;border:1px solid;vertical-align:middle;padding:7px;\'>'+$(this).find('td:eq(4)').text()+'</div>';
									tab+='<div style=\'display:table-cell;border:1px solid;vertical-align:middle;padding:7px;\'>'+tabCount+'</div>';
									tab+='<div style=\'display:table-cell;border:1px solid;vertical-align:middle;padding:7px;\'>'+$(this).find('td:eq(6)').text()+'</div>';
									tab+='<div style=\'display:table-cell;border:1px solid;vertical-align:middle;padding:7px;\'>'+tabPrice+'</div>';
									tab+='<div style=\'display:table-cell;border:1px solid;vertical-align:middle;padding:7px;\'>'+tabSumm+'</div>';
									tab+='<div style=\'display:table-cell;border:1px solid;vertical-align:middle;padding:7px;\'><a href='+$(this).attr('rel-url')+' >'+$(this).attr('rel-nameurl')+'</a></div>';
									count+=tabCount;
									price+=tabSumm;
									tab+='</div>';
								}
							});
							
							price=price.toFixed(2);

							tab+='<div style=\'display:table-row;font-weight: bold;\'>';
							tab+='<div style=\'display:table-cell;text-align:center;vertical-align:middle;padding:7px;\'>ИТОГО:</div>';
							tab+='<div style=\'display:table-cell;text-align:center;vertical-align:middle;padding:7px;\'></div>';
							tab+='<div style=\'display:table-cell;text-align:center;vertical-align:middle;padding:7px;\'></div>';
							tab+='<div style=\'display:table-cell;text-align:center;vertical-align:middle;padding:7px;\'></div>';
							tab+='<div style=\'display:table-cell;text-align:center;vertical-align:middle;padding:7px;\'>'+count+' ед</div>';
							tab+='<div style=\'display:table-cell;text-align:center;vertical-align:middle;padding:7px;\'></div>';
							tab+='<div style=\'display:table-cell;text-align:center;vertical-align:middle;padding:7px;\'></div>';
							tab+='<div style=\'display:table-cell;text-align:center;vertical-align:middle;padding:7px;\'>'+price+' '+$('#but22_tab'+rel).find('td:eq(6)').text()+'</div>';
							tab+='<div style=\'display:table-cell;text-align:center;vertical-align:middle;padding:7px;\'></div>';
							tab+='</div>';

							tab+='</div>';

						var txt='';
							txt+='<div style=\'margin: 13px;margin-left: 28px;margin-right: 28px;font-size: 13px;\'>';
							txt+='<div><a style=\"margin-left:10px;\" href=\"http://www.makewear.com.ua/\" target=\"_blank\"><img src=\"http://makewear.com.ua/templates/shop1/images/makewear290x1.jpg\" alt=\"makewear.com\" border=\"0\" style=\"width: 262px;\"></a></div>';
							txt+='<div style=\'font-size: 14px;\'><p style=\"margin-bottom: -10px;\"><b>Заказ №'+rell[0]+'</b></p><br><p style=\"margin-bottom: -10px;\">Дата: '+tt+'</p><br/><p style=\"margin-bottom:7px;\">Время: '+ti+'</p></div>';
							txt+='<hr width=100% /><br>';
							txt+='Добрый день, пожалуйста, подтвердите наличие данных моделей:<br/><br/>';
							txt+=tab;
							txt+='<br/><br/><br/><hr width=100% /><br/>';
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

						$('.sent_html').html(txt);

						//-----Resize-----
						var h=$(window).height();
						var h1=h-250;
						h-=50;
						$('.send_window').css({'height':h+'px'});
						$('.sent_html').css({'height':h1+'px'});
					});
					$('.close_window, .icon_close').click(function(){
						$('body').css({'overflow':'auto'});
						$('.send_body').hide();
					});
					$(window).resize(function(){
						var h=$(window).height();
						var h1=h-250;
						h-=50;
						$('.send_window').css({'height':h+'px'});
						$('.sent_html').css({'height':h1+'px'});
					});

					$('.sent_order').click(function(){
						var where=$('.tab_send_towhere').val();
						var whom=$('.tab_send_whom').val();
						var sub=$('.tab_send_subject').val();

						var txt=$('.sent_html').html();
						var id=$(this).attr('rel');

						$.ajax({
							type:'POST',
							url: 'http://'+window.location.hostname+'/modules/commodities/ajax/ajax_send.php',
							data:{where:where, whom:whom, sub:sub, txt:txt, status_ob:1, id:id}
						}).done(function(dd){
							alert(dd);
							$('.send_body').hide();
							if(dd.indexOf('Отправил')!=-1)
							$('.sentt'+id).html('<div class=\'icon_sent\'></div><span style=\'text-decoration:underline\'>отправлен</span>');
						});
					});
					
					$('.sent_html').click(function(){
						$(this).attr('contenteditable','true');
					});						
					
					
					screen_w();
					$(window).resize(function(){
						screen_w();
					});


				});
				function screen_w(){
					var w=$(window).width();
					var ow=$('.w_brands').width();
					var t2w=$('.w_brands th:nth-child(3)').width();
					var t3w=$('.w_brands th:nth-child(4)').width();
					var t4w=$('.w_brands th:nth-child(5)').width();
					//$('.rees').text(t2w);
					
					if(w>=1100){
						$('.w_brands').css({'width':(w-180)+'px'});
						$('.open_line').css({'width':(w-205)+'px'});
						$('.tab').css({'width':(w-202)+'px'});
					//	$('.open_windows1').css({'width':(t2w+17)+'px'});
					//	$('.open_windows1 div div').css({'width':(t2w-34)+'px'});
					//	$('.open_windows2').css({'width':(t3w+15)+'px'});
					//	$('.open_windows2 div div').css({'width':(t3w-37)+'px'});
					//	$('.open_windows3').css({'width':(t4w-8)+'px'});
					//	$('.open_windows3 div div').css({'width':(t4w-57)+'px'});
					}else{
						$('.w_brands').css({'width':'1085px'});
						$('.tab').css({'width':'1060px'});
						$('.open_line').css({'width':'1063px'});
						// $('.open_windows1').css({'width':'216px'});
						// $('.open_windows2').css({'width':'268px'});
						// $('.open_windows3').css({'width':'406px'});
					}	
				}
				function close_win(){
					$('.br_cont, .br_us, .br_rez').animate({'max-height': '80px'},'slow');
					$('.open_cont, .open_us, .open_rez').removeClass('bu2');
					$('.open_cont, .open_us, .open_rez').addClass('bu1');
					$('.open_windows1, .open_windows2, .open_windows3').css({'z-index':'0', 'box-shadow': '0px 0px 0px'});
					$('.tin').fadeOut();
				}
				var stop_time;
				var i=0;
				function fun_rotate(aa, bb, s){
					//$('#bo2_'+rel).css({'transform':'rotate(25deg)'});
					if(bb==1){
						stop_time=setInterval(function(){
							fun.rotate1(aa);
						},s);
					}
					if(bb==2){
						stop_time=setInterval(function(){
							fun.rotate2(aa);
						},s);
					}
				};
				fun={
					rotate1: function(aa){
						$(aa).css({'transform':'rotate('+i+'deg)'});
						if(i>=180){
							clearInterval(stop_time);
						}
						i+=36;
					},
					rotate2: function(aa){
						$(aa).css({'transform':'rotate('+i+'deg)'});
						if(i<=0){
							clearInterval(stop_time);
						}
						i-=36;
					}
				}