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

						$(".tab_up").each(function(){
							var rel=$(this).attr("id");
							rel=parseInt(rel.replace("gh",""));
							var wid=$(".bor_i2"+rel).width();
							$("#sizeWidth"+rel).css({"width":wid+"px"});
							//alert(wid);
						});

						$('.maill').click(function(){
							autoClosePopUp();
							var rel=$(this).attr('rel');

							var name2=$('.cli_info_open'+rel+' tr:nth-child(2) td:nth-child(2)').text();
							var email=$('.cli_info_open'+rel+' tr:nth-child(3) td:nth-child(2)').text();
							var city=$('.cli_info_open'+rel+' tr:nth-child(5) td:nth-child(2)').text();

							var waysDelivery=$('.city_info_open'+rel+' #addWayDelivery').text();
							var depart=$('.city_info_open'+rel+' #addDepart').text();
							var address=$('.city_info_open'+rel+' #addAddress').text();
							var phone=$('.city_info_open'+rel+' #addPhone').text();


							$('body').css({'overflow':'hidden'});

							$('.tab_send_towhere').val(email);
							$('.tab_send_subject').val('Запрос поставки № '+rel);
							//alert(rel);
							
							$('.sent_order').attr('rel',rel);		
							
							var t = new Date();
							var tt=t.getDate()+'.'+(t.getMonth()+1)+'.'+t.getFullYear();
							var time=t.getHours()+':'+t.getMinutes()+':'+t.getSeconds();
							
							var tab_com='';
							
							

							//----For clip file--------
							var clip='';
							if($('.change_clip'+rel+' div').hasClass('icon_clip_was')){
								var size=$('.change_clip'+rel+' div').attr('rel-size');
								var fff=$('.change_clip'+rel+' div').attr('rel-file');
								var name=$('.change_clip'+rel+' div').attr('rel-name');

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
								txt+='<div style="margin-bottom:10px;" ><a href=\"http://www.makewear.com.ua/\" target=\"_blank\"><img src=\"http://makewear.com.ua/templates/shop/image/logo.png\" alt=\"makewear.com\" border=\"0\"></a></div>';
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
								$('.send_window').css({'height':h+'px'});
								$('.sent_html').css({'height':h1+'px'});
						});
						$('.close_window, .icon_close2 ').click(function(){
							$('.send_body').hide();
							$('body').css({'overflow':'auto'});
						});

						$('.sent_order').click(function(){
							var where=$('.tab_send_towhere').val();
							var whom=$('.tab_send_whom').val();
							var sub=$('.tab_send_subject').val();

							var txt=$('.sent_html').html();
							
							var id=$(this).attr('rel');
							var sent=$('#gh'+id+" .maill .icon_sent");
							var countSent=sent.attr("rel-was-sent");
							countSent++;

							$.ajax({
								type:'POST',
								url: 'http://'+window.location.hostname+'/modules/commodities/ajax/ajax_send.php',
								data:{where:where, whom:whom, sub:sub, txt:txt, id:id, sent_mw_k:1 }
							}).done(function(dd){
								alert(dd);
								$('.send_body').hide();
								if(dd.indexOf('Отправил')!=-1)
									$('#gh'+id+" .maill").html('<div class=\'icon_sent\' rel-was-sent='+countSent+' ></div>');
							});
						});	
						 
						$('.icc').click(function(){
							var rel=$(this).attr('rel');
							if($(this).hasClass('active')){
								$(this).removeClass('active');
								$('.cli_info_open'+rel).slideUp();
			
									$('.bor_i'+rel).removeClass('bor_info');
				
									$('#bc'+rel).removeClass('block_up1');
									$('#bc'+rel).addClass('block_down1');
				
									$('#bc1'+rel).removeClass('bcc1');
									$('#bc1'+rel).addClass('bcc');
				
									revClass('#ic'+rel, 'icon_cont1', 'icon_cont');	
							//	});
			
							}else{
								
								$('.cli_info_open'+rel).slideDown();
								$(this).addClass('active');
			
								$('.bor_i'+rel).addClass('bor_info');
			
								$('#bc'+rel).removeClass('block_down1');
								$('#bc'+rel).addClass('block_up1');
			
								$('#bc1'+rel).removeClass('bcc');
								$('#bc1'+rel).addClass('bcc1');
			
								revClass('#ic'+rel, 'icon_cont', 'icon_cont1');
							}
						});
						$('.city_open').click(function(){
							var rel=$(this).attr('rel');
						//	$('.city_info_open'+rel).show();
							if($(this).hasClass('active')){
								$(this).removeClass('active');
								$('.city_info_open'+rel).slideUp();
			
									$('.bor_i2'+rel).removeClass('bor_info2');
				
									$('#bc2'+rel).removeClass('block_up1');
									$('#bc2'+rel).addClass('block_down1');

									$('#icb'+rel).removeClass('icon_city_blue');
									$('#icb'+rel).addClass('icon_city');
			
							}else{
								
								$('.city_info_open'+rel).slideDown();
								$(this).addClass('active');
			
								$('.bor_i2'+rel).addClass('bor_info2');
			
								$('#bc2'+rel).removeClass('block_down1');
								$('#bc2'+rel).addClass('block_up1');

								$('#icb'+rel).removeClass('icon_city');
								$('#icb'+rel).addClass('icon_city_blue');
							}
						});
						screen_w();
						$(window).resize(function(){
							screen_w();
						});

						$('.icon_info').click(function(){
							show_rez();
						});
						$('.close_rek').click(function(){
							$('.rekvizit').hide();
							$('.rekvizit .dell').remove();
						});

						$('.add_row').click(function(){
							$.ajax({
								method: 'GET',
							 	url:'/modules/commodities/ajax/rezvisit.php',
							 	data:{rez:'true'},
							 	dataType:'json'
							})
							.done(function(dd){
								var txt='<tr class=\'dell del'+dd[dd.length-1]['id']+'\' >';
									txt+='<td onclick=\'del_row('+dd[i]['id']+')\' style=\'color:gray;cursor:pointer;font-weight: bold;\'>X</td>';
									txt+='<td contenteditable=\'true\'  onkeyup=\'key_press(this.id,'+dd[dd.length-1]['id']+',\"in_name\")\' id=\'enn'+dd[dd.length-1]['id']+'\'>'+dd[dd.length-1]['name']+'</td>';
									txt+='<td contenteditable=\'true\'  onkeyup=\'key_press(this.id,'+dd[dd.length-1]['id']+',\"in_write\")\' id=\'wnn'+dd[dd.length-1]['id']+'\'>'+dd[dd.length-1]['write']+'</td></tr>';
								$('.tab_rez').append(txt);
							});
						});

						$('.edit_rek div').click(function(){
							alert('edit');
							$(this).attr('contenteditable','true');
						});
						$('.cli_open').click(function(){
							var rel=$(this).attr('rel');
							//alert(rel);
							if($('#gh'+rel).hasClass('tab_up')){
								$('#open_comm'+rel).slideDown(function(){
									$('#bb'+rel).removeClass('block_down');
									$('#bb'+rel).addClass('block_up');
								});
								$('#gh'+rel).removeClass('tab_up');
								$('#gh'+rel).addClass('tab_down');
	
							}else{
								$('#open_comm'+rel).slideUp(200, function(){
									$('#gh'+rel).removeClass('tab_down');
									$('#gh'+rel).addClass('tab_up');
									$('#bb'+rel).removeClass('block_up');
									$('#bb'+rel).addClass('block_down');
								});
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
								// if(d==1){
								// 	//alert('Загрузил документ');
								// 	open_file(rel, name, size);	
								// 	var ffile=name.split('.');
								// 	// if(ffile[1]=='pdf' || ffile[1]=='PDF'){
								// 	// 	$('.see_file').html('<object data=\"/uploads/delivery_MW_K/'+rel+'.'+ffile[1]+'\" type=\"application/pdf\" width=\"800px\" height=\"700px\"> </object>');
								// 	// }else{
							 //  //   		$('.see_file').html('<img src=\"/uploads/delivery_MW_K/'+rel+'.'+ffile[1]+'\" style=\'width:600px\' />');	
							 //  //   	}
								// 	$('.set_size').text((size/1024).toFixed(1)+' KB');	
								// 	$('.set_name').text(name);	

								// 	$('.sentt'+rel+' div').css({'display':'block'});
								// 	$('.sentt'+rel+' .icon_send_blue').remove();				
								// }else if(d==0){
								// 	alert('Error');	
								// }
							 	if(d==2){
									alert('Допустимые форматы: JPG, PDF ');							 	
							 	}else{
							 		var file=d.split("@@");
							 		// alert(file[1]);
							 		// var ssrc=location.hostname;
									//alert('Загрузил документ');
									open_file(rel, file[1], size);	
									var srcImg="https://makewear.blob.core.windows.net/delivery-mwk/"+file[1];
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

						$('.delivery_mw_k tr').each(function(){
							var mm=0;
							$(this).find('.hdiv').each(function(){
								var tt=$(this).height();
								if(mm<tt){
									mm=tt;
								}
							});
							$(this).find('.hdiv').css({'height':mm+'px'});
						});
						$('.write_db').click(function(){
							$(this).attr('contenteditable','true');
						});
						$('.write_db').dblclick(function(){
							var ttn=($(this).text()).trim();
							if(ttn=='000000000000'){
								alert('Не можу, переносит на сайт Нова Почты.\n Пожалуйте, поставить ТТН');
							}else{
								window.open("https://novaposhta.ua/tracking/?cargo_number="+ttn,"_blank");
							}
						});
						$('.write_db').keyup(function(event){
							var rel_id=$(this).attr('rel-id');
							var rel_db_tab=$(this).attr('rel-db-tab');
							var txt=$(this).text();
							$.ajax({
								method:'GET',
								url:'/modules/commodities/ajax/ajax_write.php',
								data:{rel_id2:rel_id, txt:txt, rel_db_tab:rel_db_tab}
							});//.done(function(d){alert(d);});

							if(event.which==13 && event.ctrlKey){
								$('body').css({'cursor':'wait'});
								var ttn=parseInt($(this).text());
								//alert(ttn);
								$.ajax({
									method:'GET',
									url:'/modules/commodities/ajax/apiNP2.php',
									data:{ttn:ttn, delv:2, rel:rel_id},
									dataType: 'json'
								})
								.done(function(data){
									$('body').css({'cursor':'default'});
									if(data['status']==1){
										$('.gett_del'+rel_id).text(data['sum']+' грн');
										var ddd=data['dateReceived'].split(' ');
										$('.set_date'+rel_id).text(ddd[0]);

										var depart=data['addressUA'].split(':');
										var depart1=depart[0].split('(');
										var depart2=depart1[0].split('№');

										$('.city_info_open'+rel_id+' #addDepart').text('№'+depart2[1]);
										$('.city_info_open'+rel_id+' #addAddress').text(depart[1]);
										//alert(data['sum']);
										alert('Отримали ТТН');
									}
									if(data['status']==0){
										alert('Номер не знайдено');
									}
								});
							}
						});
						$('.icon_print').click(function(){
							autoClosePopUp();
						});
						
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
							// var namee=d;
							// $('.see_file').empty();
							// if(namee.indexOf('pdf')!=-1 || namee.indexOf('PDF')!=-1){
							// 	$('.see_file').html('<object data=\"'+namee+'\" type=\"application/pdf\" width=\"800px\" height=\"700px\"> </object>');
							// }else{
							// 	$('.see_file').html('<img src=\"'+name+'\" style=\'width:600px\' />');
							// }	
						});
					}
					function f_icon_clip_will(rel){
						$('.body_upload').show();
						$('body').css({'overflow':'hidden'});
						//var rel=$(this).attr('rel');
						$('.upload').attr('rel',rel);
						var count=$('#gh'+rel+' td:nth-child(7)').text();
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
						var srcImg="https://makewear.blob.core.windows.net/delivery-mwk/"+name2;
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
					function screen_w(){
						var w=$(window).width();
						//$('.rees').text(w);
						
						if(w>=1000){
							$('.w_cli').removeAttr('style');
						}else{
							$('.w_cli').width('1200px');
						}
					
					}
					function revClass(c, a, a1){
						$(c).removeClass(a);
						$(c).addClass(a1);
					}

					function edit_rek(ddd){
						$('#'+ddd+' div').attr('contenteditable','true');
					}
					function del_row(ii){
						$('.del'+ii).remove();
						$.ajax({
							type: 'GET',
							url:'/modules/commodities/ajax/rezvisit.php',
							data:{del_rez:ii}
						});
					}
					function show_rez(){
						 $.ajax({
							 	method: 'GET',
							 	url:'/modules/commodities/ajax/rezvisit.php',
							 	data:{get_rez:'true'},
							 	dataType:'json'
							})
							.done(function(dd){
								//alert(dd.length);

								for(i=0; i<dd.length; i++){
									var txt='<tr class=\'dell del'+dd[i]['id']+'\' >';
										txt+='<td onclick=\'del_row('+dd[i]['id']+')\' style=\'color:gray;cursor:pointer;font-weight: bold;\'>X</td>';
										txt+='<td contenteditable=\'true\'  onkeyup=\'key_press(this.id,'+dd[i]['id']+',\"in_name\")\' id=\'enn'+dd[i]['id']+'\'>'+dd[i]['name']+'</td>';
										txt+='<td contenteditable=\'true\'  onkeyup=\'key_press(this.id,'+dd[i]['id']+',\"in_write\")\' id=\'wnn'+dd[i]['id']+'\'>'+dd[i]['write']+'</td></tr>';
									$('.tab_rez').append(txt);
								}
								$('.rekvizit').show();
							});	
					}
					function key_press(aa, id, cc){
						//alert($('#'+aa).text()+'='+id+'='+cc);
						var tt=$('#'+aa).text();
						$.ajax({
							type: 'GET',
							url:'/modules/commodities/ajax/rezvisit.php',
							data:{up_rez:tt, id:id, cc:cc}
						});
					}
					function autoClosePopUp(){
						$(".icc").each(function(){
							var rel=$(this).attr("rel");
							$(this).removeClass('active');
							$('.cli_info_open'+rel).hide();
			
							$('.bor_i'+rel).removeClass('bor_info');
				
							$('#bc'+rel).removeClass('block_up1');
							$('#bc'+rel).addClass('block_down1');
				
							$('#bc1'+rel).removeClass('bcc1');
							$('#bc1'+rel).addClass('bcc');
				
							revClass('#ic'+rel, 'icon_cont1', 'icon_cont');	
						});


						$(".city_open").each(function(){
							var rel=$(this).attr("rel");
							$(this).removeClass('active');
							$('.city_info_open'+rel).hide();
			
							$('.bor_i2'+rel).removeClass('bor_info2');
				
							$('#bc2'+rel).removeClass('block_up1');
							$('#bc2'+rel).addClass('block_down1');

							$('#icb'+rel).removeClass('icon_city_blue');
							$('#icb'+rel).addClass('icon_city');
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