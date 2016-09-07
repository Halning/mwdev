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
					$(".color_select2").change(function(){
						var rel=$(this).attr("rel");
						var vall=$(this).val();
						if(vall==12){
							$.ajax({
								maethod:'GET',
								url: 'http://'+window.location.hostname+'/modules/commodities/ajax/addPaymentMW.php',
								data:{rel:rel, cha:1}
							})
						}else{
							$.ajax({
								maethod:'GET',
								url: 'http://'+window.location.hostname+'/modules/commodities/ajax/addPaymentMW.php',
								data:{rel:rel, cha:0}
							})
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
							//alert('pay');
							$('.dost').text(dost[rel]);
							
							$('.skidka').text(skidka_json[rel]);
							$('.gift').text(gift_json[rel]);

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

						 $('.cli_open').click(function(){
							var rel=$(this).attr('rel');
							if($('#client_open'+rel).hasClass('tab_up')){
								$('#client_open'+rel).removeClass('tab_up');
								$('#client_open'+rel).addClass('tab_down');
			
								$('.table_commodity'+rel).slideDown(function(){
									$('#bb'+rel).removeClass('block_down');
									$('#bb'+rel).addClass('block_up');
								});
							}else{
								$('.table_commodity'+rel).slideUp(function(){
									$('#client_open'+rel).removeClass('tab_down');
									$('#client_open'+rel).addClass('tab_up');
									$('#bb'+rel).removeClass('block_up');
									$('#bb'+rel).addClass('block_down');
								});
							}
							$('.cli_info').slideUp();
						});
						$('.cli_cont').click(function(){
							var rel=$(this).attr('rel');
							if($(this).hasClass('active')){
								$('.cli_info_open'+rel).animate({'max-height':'0px'},200,function(){
									$(this).hide();
									$('.cli_cont').removeClass('active');
			
									//$('.coo'+rel).css({'background':''});
									$('.bor_i'+rel).removeClass('bor_info');
				
									$('#bc'+rel).removeClass('block_up1');
									$('#bc'+rel).addClass('block_down1');
				
									$('#bc1'+rel).removeClass('bcc1');
									$('#bc1'+rel).addClass('bcc');
				
									revClass('#ic'+rel, 'icon_cont1', 'icon_cont');	
								});
			
							}else{
								
								$('.cli_info_open'+rel).show().css({'overflow':'hidden','max-height':'0px'}).animate({'max-height':'90px'},500);
								$(this).addClass('active');
			
								$('.bor_i'+rel).addClass('bor_info');
			
								$('#bc'+rel).removeClass('block_down1');
								$('#bc'+rel).addClass('block_up1');
			
								$('#bc1'+rel).removeClass('bcc');
								$('#bc1'+rel).addClass('bcc1');
			
								revClass('#ic'+rel, 'icon_cont', 'icon_cont1');
							}
			
			
						});
						screen_w();
						$(window).resize(function(){
							screen_w();
						});

						$('.icon_info').click(function(){
							$('body').css({'cursor':'wait'});

							show_rez();
						});
						$('.close_rek').click(function(){
							$('.rekvizit').hide();
							$('.rekvizit .dell').remove();
						});

						$('.add_row').click(function(){
							var relget=$('.icon_info').attr('rel-get');
							$.ajax({
								method: 'GET',
							 	url:'/modules/commodities/ajax/rezvisit.php',
							 	data:{rez:'true', relget:relget},
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
						$('.payment_client tr').each(function(){
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
							var vall=$(this).val();
							if(vall==1){
								$('.tabCur').each(function(){
									$(this).text($(this).attr('rel-uah'));
								});
							}
							if(vall==2){
								$('.tabCur').each(function(){
									$(this).text($(this).attr('rel-usd'));
								});
							}
							if(vall==3){
								$('.tabCur').each(function(){
									$(this).text($(this).attr('rel-rub'));
								});
							}
						});
						$('.change_price_opt').change(function(){
							var rel=$(this).attr('rel');
							var vall=$(this).val();
							var cur=$(this).attr('rel-cur');

							if(vall==1 || vall==2){
								$('.table_commodity'+rel+' .cl_trt:checked').each(function(){
									$('body').css({'cursor':'wait'});
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

					});
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
						var relget=$('.icon_info').attr('rel-get');
						 $.ajax({
							 	method: 'GET',
							 	url:'/modules/commodities/ajax/rezvisit.php',
							 	data:{get_rez:'true', relget:relget},
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
								$('body').css({'cursor':'default'});
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
						$(".cli_cont").each(function(){
							var rel=$(this).attr("rel");
							$('.cli_info_open'+rel).animate({'max-height':'0px'},1,function(){
								$(this).hide();
								$('.cli_cont').removeClass('active');
			
								//$('.coo'+rel).css({'background':''});
								$('.bor_i'+rel).removeClass('bor_info');
				
								$('#bc'+rel).removeClass('block_up1');
								$('#bc'+rel).addClass('block_down1');
				
								$('#bc1'+rel).removeClass('bcc1');
								$('#bc1'+rel).addClass('bcc');
				
								revClass('#ic'+rel, 'icon_cont1', 'icon_cont');	
							});
						});

						$('.wind_o2').each(function(){
							var rel=$(this).attr('rel');
							$('#open_win2'+rel).hide();
							$(this).addClass('open_backg');
							$(this).removeClass('close_backg');
							$('.iiw'+rel).removeClass('icon_info_blue');
							$('.iiw'+rel).addClass('icon_info_white');
							$('.bbc'+rel).addClass('block_down1');
							$('.bbc'+rel).removeClass('block_up1');
						});
						$('.wind_o2_orange').each(function(){
							var rel=$(this).attr('rel');
							$('#open_win2'+rel).hide();
							$(this).addClass('open_backg');
							$(this).removeClass('close_backg');
							$('.iiw'+rel).removeClass('icon_info_blue');
							$('.iiw'+rel).addClass('icon_info_orange');
							$('.bbc'+rel).addClass('block_down1_orange');
							$('.bbc'+rel).removeClass('block_up1');
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