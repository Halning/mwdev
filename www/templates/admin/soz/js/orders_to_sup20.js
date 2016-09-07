					$(document).ready(function(){
						//$('.open_calendar').datepicker();
						//$.datepicker.setDefaults($.datepicker.regional['']);
	$('#datepicker_form').datepicker({
		dateFormat: 'dd/mm/yy',
		changeMonth: true,
      	changeYear: true,
		onClose: function( selectedDate ) {
			$( '#datepicker_to' ).datepicker( 'option', 'minDate', selectedDate );
		},
		onSelect: function(dateText, inst) {
			var getUrl=window.location.search;
			if(getUrl.indexOf("archive")!=-1){
				//alert(getUrl);
				getUrlDate(getUrl);
			}else{
				searchDate();
			}
		}
	});

	$('#datepicker_to').datepicker({
		dateFormat: 'dd/mm/yy',
		changeMonth: true,
      	changeYear: true,
		onClose: function( selectedDate ) {
			$('#datepicker_form').datepicker( 'option', 'maxDate', selectedDate );
		},
		onSelect: function(dateText, inst) {
			var getUrl=window.location.search;
			if(getUrl.indexOf("archive")!=-1){
				//alert(getUrl);
				getUrlDate(getUrl);
			}else{
				searchDate();
			}
		}
	});

	var getUrl=window.location.search;

	if(getUrl.indexOf("fromData")!=-1){
		var arrSite=getUrl.split("&");
		for(var i=0; i<arrSite.length; i++){
			var aSite=arrSite[i].split("=");
			var setDate="";

			if(parseInt(aSite[1])!=0){
				var aaa=aSite[1].split("-");
				setDate=aaa[2]+"/"+aaa[1]+"/"+aaa[0];
			}else{
				setDate="";
			}

			if(arrSite[i].indexOf("fromData")!=-1){
				$("#datepicker_form").val(setDate);
			}
			if(arrSite[i].indexOf("toData")!=-1){
				$("#datepicker_to").val(setDate);
			}
		}
	}

	$(".changeAllBox").click(function(){
		var rel=$(this).attr("rel");
		
		var box=$(".open_commodity"+rel+" .cl_trt");
		if($(this).prop("checked")){
			box.prop("checked",true);
		}else{
			box.prop("checked",false);
		}
	});
						$('.write_tab').click(function(){
							$(this).css({'width':'300px','text-align':'left'});
						});
						$('.write_tab').keyup(function(){
							var rel=$(this).attr('rel');
							var txt=$(this).text();
							$.ajax({
								method:'GET',
								url:'/modules/commodities/ajax/ajax_write.php',
								data:{rel:rel, txt:txt, wr_name:'write_payment'},
							}); //.done(function(d){alert(d);});
						});
						$('.maill').click(function(){
							autoClosePopUp();
							$('body').css({'overflow':'hidden'});
							$('#pay_body').show();
							var rel=$(this).attr('rel');
							var rel2=$(this).attr('rel2');
							var art=$(this).attr('art');
							$('.send').attr('rel',rel);	
							rel2=rel2.replace(' ', '');
							$('#from_to').val(rel2);
							$('#from_sub').val('Оплата заказа №'+art);
						
							var getPrice=$('#gh'+rel+' td:nth-child(7)  ').text();
							//alert(getPrice);
							var chet=$('.get_shet'+rel).text();

							var namee=$('#gh'+rel+' td:nth-child(8) .wind_o div:nth-child(1)').attr('rel-real-name');
							var bank=$('#gh'+rel+' td:nth-child(9) ').text();
							var vid=$('#gh'+rel+' td:nth-child(10) ').text();

							var t = new Date();
							var tt=t.getDate()+'.'+(t.getMonth()+1)+'.'+t.getFullYear();
							var ti=t.getHours()+':'+t.getMinutes()+':'+t.getSeconds();

							var pri=$('.getpri'+rel).text();

							//----For clip file--------
							var clip='';
							if($('.change_clip'+rel+' div').hasClass('icon_clip_was')){
								var size=$('.change_clip'+rel+' div').attr('rel-size');
								var fff=$('.change_clip'+rel+' div').attr('rel-file');
								var name=$('.change_clip'+rel+' div').attr('rel-name');

								size=(size/1024).toFixed(1)+' KB';	
								//$('.set_name').text(fff);
								var hreff=window.location.hostname+'/uploads/payment_P/'+fff;
									
									clip+='<div style=\'border: 10px solid transparent;border-bottom: 10px solid white;width: 0px;position: absolute;top: -4px;left: 24px;z-index: 3;\'></div>';
									clip+='<div style=\'border: 10px solid transparent;border-bottom: 10px solid gray;width: 0px;position: absolute;top: -5px;left: 24px;\'></div>';
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

							var txt='<div style=\'color:black;margin: 20px;font-size: 15px;\'>';
								txt+='<div style="margin-bottom: 10px;"><a href=\"http://www.makewear.com.ua/\" target=\"_blank\"><img src=\"http://makewear.com.ua/templates/shop/image/logo.png\" alt=\"makewear.com\" ></a></div>';
								txt+='Здравствуйте.<br/><br/>Вам была произведена оплата заказа №'+art+'<br/><br/>';
								txt+='<table border=1 style=\'border-collapse: collapse;\'>';
								txt+='<tr><td style=\'width: 120px;padding: 3px;padding-left: 12px;padding-right: 18px;\' ><b>Сумма оплаты:</b></td><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' >'+getPrice+'</td></tr>';
								txt+='<tr><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' ><b>Получатель:</b></td><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' >'+namee+'</td></tr>';
								txt+='<tr><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' ><b>Банк:</b></td><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' >'+bank+'</td></tr>';
								txt+='<tr><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' ><b>Вид платежа:</b></td><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' >'+vid+'</td></tr>';
								txt+='<tr><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' ><b>№ счета:</b></td><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' >'+chet+'</td></tr>';
								txt+='<tr><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' ><b>Время оплаты:</b></td><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' >'+tt+' '+ti+'</td></tr>';
								txt+='<tr><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' ><b>Примечание:</b></td><td style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' >'+pri+'</td></tr>';
								txt+='</table style=\'padding: 3px;padding-left: 12px;padding-right: 18px;\' ><br/>';
								txt+='Во вложении скан-копия подтверждающего документа.<br/><br/>'
								txt+='<br/>';
								txt+='<table id=\"footer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-bottom:none;border-left:none;border-right:none;border-color:#000;border-collapse:collapse;margin:20px auto;padding:5px;\">';
								txt+='<tbody><tr><td><img id=\"footer-img\" src=\"http://makewear.com.ua/templates/shop/image/email/mw_logo.jpg\" alt=\"makewear.com\" style=\"margin: 10px;width: 100px;\"></td>';
								txt+='<td style=\'vertical-align: middle;\'><img class=\"footer-i\" src=\"http://makewear.com.ua/templates/shop/image/email/call_fot.png\" alt=\"phone\"></td>';
								txt+='<td><p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">+38 (063) 029-70-22</font></p>';
								txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">+38 (099) 098-00-82</font></p>';
								txt+='<p style=\"color: #696969; font-size: 14px; margin: 10px 0;\"><font face=\"CenturyGothic\">+38 (098) 615-39-19</font></p>';
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
								txt+='<div style=\'position: relative;padding-top: 15px;\'>';
								txt+=clip+'</div>';
								txt+='</div>';

							$('.html_mail').html(txt);
							if($(this).find("div").hasClass("icon_sent")){
							var wasSent=$(this).find(".icon_sent").attr("rel-was-sent");
								$(".sent_order").text("Отправить повторно ("+wasSent+")");
							}else{
								$(".sent_order").text("Отправить");
							}				
						});	
						$('.html_mail').click(function(){
							$(this).attr('contenteditable','true');
						});
						$('.pay_close').click(function(){
							$('#pay_body').hide();	
							$('body').css({'overflow':'auto'});					
						});	
						$('.pay_close2').click(function(){
							$('#pay_body').hide();	
							$('body').css({'overflow':'auto'});					
						});	

						$('.send').click(function(){
							var rel=$(this).attr('rel');
							var up_to=$('#from_to').val();
							var up_whom=$('#from_whom').val();
							var up_sub=$('#from_sub').val();

							var msg=$('.html_mail').html();	

							var sent=$('.sentt'+rel+" .icon_sent"); 
							var countSent=sent.attr("rel-was-sent");
							countSent++;



							$.ajax({
								type:'POST',
								url: 'http://'+window.location.hostname+'/modules/commodities/ajax/ajax_send.php',
								data:{where:up_to, whom:up_whom, sub:up_sub, txt:msg, payment_dd:1, id:rel}
							}).done(function(dd){
								alert(dd);
								$('#pay_body').hide();
								if(dd.indexOf('Отправил')!=-1){
									$('.sentt'+rel).html('<div class=\'icon_sent\' rel-was-sent='+countSent+' ></div>');
								}
								
							});

						});

						//-------------------------------------------------
						$('.window_con').click(function(){
							$('.body_edit').show();
							var rel=$(this).attr('rel');
							var cat=$('.cat_name').text();
							$('.set_name').text(cat);
							$('.set_name').attr('rel',rel);

							var con=$('.con'+rel).text();
							$('.e_contact').val(con);
							var payy=$('.payy'+rel).text();
							$('.e_payment').text(payy);
							var deli=$('.deli'+rel).text();
							$('.e_delivery').text(deli);
						});
						$('.close_edit').click(function(){
							$('.body_edit').hide();
						});
						$('.down_edit').click(function(){
							var re=$('.set_name').attr('rel');
							var payment=$('.e_payment').val();
							var contact=$('.e_contact').val();
							var delivery=$('.e_delivery').val();
							//alert(re+'-'+payment);
							$.get('modules/commodities/admin/fun_ajax.php',{down_edit_id2:re, down_contact:contact, down_payment:payment, down_delivery:delivery })
							.done(function(d){
								//alert(d);
								cont();
							});
							$('.body_edit').hide();
						
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
						$('.wind_o').click(function(){
						//	$('body').css({'overflow':'hidden'});
							var rel=$(this).attr('rel');
						//	alert(rel);
							//$('#open_win'+rel).slideToggle();
							
							if($(this).hasClass('open_backg')){
								$('#open_win'+rel).slideDown();
								//$('#open_win'+rel).animate({'max-height':'100px'}, 600);
								
								$(this).removeClass('open_backg');
								$(this).addClass('close_backg');
								$('.bc'+rel).removeClass('block_down');
								$('.bc'+rel).addClass('block_up1');
							}else{
								
								//$('#open_win'+rel).animate({'max-height':'0px'}, 400, function(){
									$('#open_win'+rel).slideUp();
								//});
								
								$(this).addClass('open_backg');
								$(this).removeClass('close_backg');
								$('.bc'+rel).addClass('block_down');
								$('.bc'+rel).removeClass('block_up1');
							}
						});
						$('.wind_o2').click(function(){
							//$('body').css({'overflow':'hidden'});
							var rel=$(this).attr('rel');
						//	alert(rel);
							//$('#open_win'+rel).slideToggle();
							
							if($(this).hasClass('open_backg')){
								$('#open_win2'+rel).show();
								$('#open_win2'+rel).animate({'max-height':'100px'}, 600);
								
								$(this).removeClass('open_backg');
								$(this).addClass('close_backg');
								$('.iiw'+rel).removeClass('icon_info_white');
								$('.iiw'+rel).addClass('icon_info_blue');
								$('.bbc'+rel).removeClass('block_down');
								$('.bbc'+rel).addClass('block_up1');
							}else{
								//$('#open_win2'+rel).slideUp();
								$('#open_win2'+rel).animate({'max-height':'0px'}, 400, function(){
									$('#open_win2'+rel).hide();
								});
								$(this).addClass('open_backg');
								$(this).removeClass('close_backg');
								$('.iiw'+rel).removeClass('icon_info_blue');
								$('.iiw'+rel).addClass('icon_info_white');
								$('.bbc'+rel).addClass('block_down');
								$('.bbc'+rel).removeClass('block_up1');
							}
						});
						$('.wind_o2_orange').click(function(){
							var rel=$(this).attr('rel');

							if($(this).hasClass('open_backg')){
								// $('#open_win2'+rel).slideDown();
								$('#open_win2'+rel).show();
								$('#open_win2'+rel).animate({'max-height':'100px'}, 600);

								$(this).removeClass('open_backg');
								$(this).addClass('close_backg');
								$('.iiw'+rel).removeClass('icon_info_orange');
								$('.iiw'+rel).addClass('icon_info_blue');
								$('.bbc'+rel).removeClass('block_down1_orange');
								$('.bbc'+rel).addClass('block_up1');
							}else{
								// $('#open_win2'+rel).slideUp();
								$('#open_win2'+rel).animate({'max-height':'0px'}, 400, function(){
									$('#open_win2'+rel).hide();
								});
								$(this).addClass('open_backg');
								$(this).removeClass('close_backg');
								$('.iiw'+rel).removeClass('icon_info_blue');
								$('.iiw'+rel).addClass('icon_info_orange');
								$('.bbc'+rel).addClass('block_down1_orange');
								$('.bbc'+rel).removeClass('block_up1');
							}
						});

						$('.icon_clip_will').click(function(){

							autoClosePopUp();
				
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
							$('body').css({'overflow':'hidden'});
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
								url:'modules/commodities/ajax/upload_ajax.php',
								cache: false,
						      	processData: false,
						      	contentType: false,
								data:data
							})
							.done(function(d){
								//alert(d);
								// if(d==1){
								// 	var ssrc=location.hostname;
								// 	//alert('Загрузил документ');
								// 	open_file(rel, name, size);	
								// 	var ffile=name.split('.');
								// 	// $('.see_file').empty();
								// 	// if(ffile[1]=='pdf' || ffile[1]=='PDF'){
								// 	// 	$('.see_file').html('<object data=\"http://'+ssrc+'/uploads/payment_P/'+rel+'.'+ffile[1]+'\" type=\"application/pdf\" width=\"800px\" height=\"700px\"> </object>');
								// 	// }else{
								// 	// 	$('.see_file').html('<img src=\"http://'+ssrc+'/uploads/payment_P/'+rel+'.'+ffile[1]+'\" style=\'width:600px\' />');
								// 	// }	
								// 	$('.set_size').text((size/1024).toFixed(1)+' KB');	
								// 	$('.set_name').text(name);

								// 	$('.send_ac'+rel+' div').css({'display':'block'});
								// 	$('.send_ac'+rel+' .icon_send_blue').remove();
										
								// }else if(d==0){
								// 	alert('Error'+d);	
								// }
							 	if(d==2){
									alert('Допустимые форматы: JPG, PNG, PDF ');							 	
							 	}else{
							 		var file=d.split("@@");
							 		// alert(file[1]);

							 		// var ssrc=location.hostname;
									//alert('Загрузил документ');
									open_file(rel, file[1], size);	
									var srcImg="https://makewear.blob.core.windows.net/payment-p/"+file[1];
									var ffile=file[1].split('.');
									// $('.see_file').empty();
									if(ffile[1]=='pdf' || ffile[1]=='PDF'){
										// $('.see_file').html('<object data="'+srcImg+'" type="application/pdf" width="800px" height="700px"> </object>');
										$('.see_file').html('<iframe src="http://docs.google.com/gview?url='+srcImg+'&embedded=true" style="width:800px; height:700px;" frameborder="0"></iframe>');
									}else{
										$('.see_file').html('<img src="'+srcImg+'" style="width:600px;" />');
									}	
									$('.set_size').text((size/1024).toFixed(1)+' KB');	
									$('.set_name').text(name);

									$('.send_ac'+rel+' div').css({'display':'block'});
									$('.send_ac'+rel+' .icon_send_blue').remove();
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
									data:{path:'payment_P', rel:rel, file:file_name}
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

						$('.order_to_sup tr').each(function(){
							var mm=0;
							$(this).find('.hdiv').each(function(){
								var tt=$(this).height();
								if(mm<tt){
									mm=tt;
								}
							});
							$(this).find('.hdiv').css({'height':mm+'px'});
						});
						$('.change_tab_cup').change(function(){
							var rel=$(this).attr('rel');
							var cup=$(this).val();
							var skid=$("#getSki"+rel+" option:selected").attr('rel-skidki');
							var vall=parseFloat($(this).find('option:selected').attr('rel-val'));
							var nameCur=$(this).find('option:selected').attr('rel-name-cur');
							var sumPrice=0;
							$('.open_commodity'+rel+' .tab_cat tr').each(function(i){
								if(i!=0){
									var getCount=parseInt($(this).find('td').eq(4).text());
									var real=parseInt($(this).find('td').eq(5).attr('rel-real-price'));
									
									$(this).find('td').eq(5).text(nameCur);
									if(cup==3 || cup==2){
										var addd=parseInt(real*vall);
									}else{
										var addd=real*vall;
									}
								
									$(this).find('td').eq(6).text(addd);
									$(this).find('td').eq(8).text(addd*getCount);

									if(skid==0){
										$(this).find('td').eq(7).text(addd);
										$(this).find('td').eq(9).text(addd*getCount);
										sumPrice+=addd*getCount;
									}else{
										$(this).find('td').eq(7).text(addd-(addd/100*skid));
										$(this).find('td').eq(9).text(addd*getCount-(addd*getCount)/100*skid);
										sumPrice+=addd*getCount-(addd*getCount)/100*skid;
									}
									
								}
							});
							sumPrice=sumPrice.toFixed(2);
							$('#gh'+rel+' #addPrice').text(sumPrice+' '+nameCur);
						});

						$('#selectCur').change(function(){
							var cur=$(this).val();
							if(cur==1){
								$('.changeCur').each(function(){
									var us=$(this).attr('rel-uah');
									$(this).text(us);
								});
							}
							if(cur==2){
								$('.changeCur').each(function(){
									var us=$(this).attr('rel-usd');
									$(this).text(us);
								});
							}
							if(cur==3){
								$('.changeCur').each(function(){
									var us=$(this).attr('rel-rub');
									$(this).text(us);
								});
							}
						});
						$('.change_price_opt').change(function(){
							var rel=$(this).attr('rel');
							var vall=$(this).val();
							var cur=$(this).attr('rel-real-cur');
							var addSum=0;

							if(vall==1 || vall==2){
								$('.open_commodity'+rel+' .cl_trt:checked').each(function(){
									$('body').css({'cursor':'wait'});
									var rell=$(this).attr('rel');
									var relId=$(this).attr('rel-id');
									$.ajax({
										method:'GET',
										url:'http://'+window.location.hostname+'/modules/commodities/ajax/change_price_opt.php',
										data:{comid:relId, cliid:rell, change:vall, curid:cur, payment:1},
										dataType:'json'
									})
									.done(function(data){
										
										$('body').css({'cursor':'default'});
										var skidka=parseFloat($('#getSki'+rel+' option:selected').attr('rel-skidki'));
										var count=parseInt($('#'+rell+' td').eq(4).text());
										var price=parseFloat(data['price']);
										var com_cur = price-(price/100 *skidka);

										//alert(price+"="+skidka+"="+com_cur);

										if(skidka==0){
											$('.closeTdSki'+rel).css({'display':'none'});
										}else{
											$('.closeTdSki'+rel).removeAttr("style");
										}

										var getCur=$('#getCup'+rel+' option').eq(cur-1).attr('rel-val');
										var getSelCur=$('#getCup'+rel+' option:checked').attr('rel-val');
										var nameCur=$('#getCup'+rel+' option:checked').attr('rel-name-cur');
										var status=$('#'+rell+' td').eq(11).find("select option:selected").val();
										


										$('#'+rell+' td').eq(5).attr('rel-real-price',(price/getCur).toFixed());
										$('#'+rell+' td').eq(6).text((price/getCur*getSelCur).toFixed());
										$('#'+rell+' td').eq(7).text((com_cur/getCur*getSelCur).toFixed(2));
										$('#'+rell+' td').eq(8).text(((price/getCur*getSelCur).toFixed())*count);
										$('#'+rell+' td').eq(9).text(((com_cur/getCur*getSelCur).toFixed(2))*count);

										
										if(status!=2){
											if(skidka==0){
												addSum+=((price/getCur*getSelCur).toFixed())*count;
											}else{
												addSum+=((com_cur/getCur*getSelCur).toFixed(2))*count;
											}
										}
										$('#gh'+rel+' #addPrice').text(addSum+' '+nameCur);
										//alert(skidka);
									});
								});
							}
						});
						// $('.but_open_win').each(function(){
						// 	var rel=$(this).attr('rel');
							
						// 	if($('.change_clip'+rel+' div').hasClass('icon_clip_will')){
						// 		$('.send_ac'+rel+' div').css({'display':'none'});
						// 		$('.send_ac'+rel).append('<div class=\'icon_send_blue\' style=\'float:left;\' ></div>');
						// 	}
						// });
					});
					function open_file(rel,f_name, s_size){
						//alert(rel+", "+f_name)
						$('.wind_upload').hide();
						$('.show_file').show();
						var ffile=f_name.split('.');
						$('.change_clip'+rel).html('<div class=\'icon_clip_was icw'+rel+'\' onclick=f_icon_clip_was(\'icw'+rel+'\') style=\'cursor:pointer;\' rel='+rel+' rel-file=\''+rel+'.'+ffile[1]+'\' rel-size='+s_size+' rel-name='+f_name+' ></div>');
						$.ajax({
							type:'POST',
							url:'modules/commodities/ajax/upload_db.php',
							data:{path:'payment_P', id:rel, file_name:f_name}	
						})
						.done(function(d){
							//alert(d)
							// $('.see_file').empty();
							// var namee=d;
							// if(namee.indexOf('pdf')!=-1 || namee.indexOf('PDF')!=-1){
							// 	$('.see_file').html('<object rel-max=1 data=\"'+namee+'\" type=\"application/pdf\" width=\"800px\" height=\"700px\"> </object>');
							// }else{
							// 	$('.see_file').html('<img src=\"'+name+'\" style=\'width:600px\' />');
							// }
						});
					}
					function f_icon_clip_will(rel){
						var rell=rel.split("_");
						$('body').css({'overflow':'hidden'});
						$('.body_upload').show();
						//var rel=$(this).attr('rel');
						$('.upload').attr('rel',rel);
							
						var pricee=$('#gh'+rel+' td:nth-child(7) .hdiv2').text();
						$('#get_upload_order').text(rell[0]);
						$('#get_upload_price').text(pricee);
					}
					function f_icon_clip_was(ttt){
						$('body').css({'overflow':'hidden'});
						$('.body_upload').show();
						$('.wind_upload').hide();
						$('.show_file').show();

						//var rel=$('.'+ttt).attr('rel');
							
						var name=$('.'+ttt).attr('rel-file');
						var name2=$('.'+ttt).attr('rel-name');
						var size=$('.'+ttt).attr('rel-size');	
						var srcImg="https://makewear.blob.core.windows.net/payment-p/"+name2;
						// alert(srcImg);
						$('.see_file').empty();						
						if(name.indexOf('pdf')!=-1 || name.indexOf('PDF')!=-1){
							// $('.see_file').html('<object data="'+srcImg+'" type="application/pdf" width="800px" height="700px"> </object>');
							$('.see_file').html('<iframe src="http://docs.google.com/gview?url='+srcImg+'&embedded=true" style="width:800px; height:700px;" frameborder="0"></iframe>');
						}else{
							$('.see_file').html('<img src="'+srcImg+'" style="width:600px;" />');
						}
						$('.set_size').text((size/1024).toFixed(1)+' KB');	
						$('.set_name').text(name2);
					}

					function cont(){
						$(document).ready(function(){
							$.getJSON('modules/commodities/admin/fun_ajax.php',{json:true})
							.done(function(up_js){
								for(var i=0; i<up_js.length; i++){
									var ii=up_js[i].comid;
										
									var con=up_js[i].cont;
									$('.con'+ii).html(con);
									$('.payy'+ii).html(up_js[i].pay);
									$('.deli'+ii).html(up_js[i].deli);
								}	
							});	
						});
					}
					function autoClosePopUp(){
						$(".wind_o2").each(function(){
							var rel=$(this).attr("rel");
							$('#open_win2'+rel).animate({'max-height':'0px'}, 1, function(){
								$('#open_win2'+rel).hide();
							});
							$(this).addClass('open_backg');
							$(this).removeClass('close_backg');
							$('.iiw'+rel).removeClass('icon_info_blue');
							$('.iiw'+rel).addClass('icon_info_white');
							$('.bbc'+rel).addClass('block_down');
							$('.bbc'+rel).removeClass('block_up1');
						});
						$(".wind_o2_orange").each(function(){
							var rel=$(this).attr("rel");
							$('#open_win2'+rel).animate({'max-height':'0px'}, 1, function(){
								$('#open_win2'+rel).hide();
							});
							$(this).addClass('open_backg');
							$(this).removeClass('close_backg');
							$('.iiw'+rel).removeClass('icon_info_blue');
							$('.iiw'+rel).addClass('icon_info_orange');
							$('.bbc'+rel).addClass('block_down1_orange');
							$('.bbc'+rel).removeClass('block_up1');
						});

						$('.wind_o').each(function(){
							var rel=$(this).attr('rel');
							$('#open_win'+rel).hide();
							$(this).addClass('open_backg');
							$(this).removeClass('close_backg');
							$('.bc'+rel).addClass('block_down');
							$('.bc'+rel).removeClass('block_up1');
							
						});
					}

		function searchDate(){
			var fromData=$("#datepicker_form").val();
			var toData=$("#datepicker_to").val();
			// alert(fromData+"-"+toData);
			console.log(fromData);
			console.log(toData);

			var arrFromData=fromData.split("/");
			var arrToData=toData.split("/");

			var fromDay=parseInt(arrFromData[0]);
			var fromMonth=parseInt(arrFromData[1]);
			var fromYear=parseInt(arrFromData[2]);

			var toDay=parseInt(arrToData[0]);
			var toMonth=parseInt(arrToData[1]);
			var toYear=parseInt(arrToData[2]);

			var flag=0;

			if(fromData!="" && toData==""){
				flag=1;
			}
			if(fromData=="" && toData!=""){
				flag=2;
			}
			if(fromData!="" && toData!=""){
				flag=3;
			}

			$(".forsearch").each(function(){
				var clientDate=$(this).find(".cli_date").text();
				var cli=clientDate.split(" ");
				var cliDate2=cli[0].split("-");
				var cliDate2Year=parseInt(cliDate2[0]);
				var cliDate2Month=parseInt(cliDate2[1]);
				var cliDate2Day=parseInt(cliDate2[2]);

				if(flag==1){
					$(this).css({"display":"none"});
					if((fromDay<cliDate2Day)&&(fromMonth<=cliDate2Month) && (fromYear<=cliDate2Year)){	
						$(this).removeAttr("style");
					}
				}
				if(flag==2){
					$(this).removeAttr("style");
					if((toDay<cliDate2Day)&&(toMonth<=cliDate2Month) && (toYear<=cliDate2Year)){
						$(this).css({"display":"none"});
					}
				}
				if(flag==3){
					$(this).css({"display":"none"});
					if(((fromDay<=cliDate2Day) && (cliDate2Day<=toDay)) && ((fromMonth<=cliDate2Month) && (cliDate2Month<=toMonth))&& ((fromYear<=cliDate2Year) && (cliDate2Year<=toYear))){
						$(this).removeAttr("style");
					}
				}
				
			});
		}
		function getUrlDate(getUrl){
			var fromData=$("#datepicker_form").val();
			var toData=$("#datepicker_to").val();
			// alert(fromData+"-"+toData);
			console.log(fromData);
			console.log(toData);

			if(getUrl.indexOf("fromData")!=-1){
				var arrSite=getUrl.split("&");

				for(var i=0; i<arrSite.length; i++){

					if(arrSite[i].indexOf("fromData")!=-1 || arrSite[i].indexOf("toData")!=-1){
						getUrl=getUrl.replace("&"+arrSite[i],"");
					}
				}

			}

			var arrFromData=fromData.split("/");
			var arrToData=toData.split("/");

			var fromDay=arrFromData[0];
			var fromMonth=arrFromData[1];
			var fromYear=arrFromData[2];

			var toDay=arrToData[0];
			var toMonth=arrToData[1];
			var toYear=arrToData[2];

			getUrlFrom=fromYear+"-"+fromMonth+"-"+fromDay;
			getUrlTo=toYear+"-"+toMonth+"-"+toDay;


			if(fromData!="" && toData==""){
				getUrl+="&fromData="+getUrlFrom+"&toData=0";
			}
			if(fromData=="" && toData!=""){
				getUrl+="&fromData=0&toData="+getUrlTo;
			}
			if(fromData!="" && toData!=""){
				getUrl+="&fromData="+getUrlFrom+"&toData="+getUrlTo;
			}

			location.href=getUrl;

			//alert(getUrl);
		}