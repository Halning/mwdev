$(document).ready(function() {
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

			
			jQuery('.cl_trt').click(function(){
				jQuery('.cl_delll').show();
				jQuery('.cl_edittt').show();
			});
			jQuery('.cl_delll').click(function(){
				urlid=0;
				jQuery('.cl_trt').each(function()
				{
					if($(this).is(':checked'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				if(urlid!=0)
				{
					location.href='/?admin=delete_order&id='+urlid;
				}
				
			});
			jQuery('.cl_edittt').click(function(){
				urlid=0;
				jQuery('.cl_trt').each(function()
				{
					if($(this).is(':checked'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				if(urlid!=0)
				{
					location.href='/?admin=edit_commodity2&commodityID='+urlid;
				}
				
			});
			
			$('.go_href').click(function(){
				var hh=$(this).attr('date_href');
				window.open(hh, '_blank');	
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

			$('.icc').click(function(){
				var rel=$(this).attr('rel');
				if($(this).hasClass('active')){
					$(this).removeClass('active');
					$('.cli_info_open'+rel).animate({'max-height':'0px'},200,function(){
						$(this).hide();
			
						//$('.coo'+rel).css({'background':''});
						$('.bor_i'+rel).removeClass('bor_info');
				
						$('#bc'+rel).removeClass('block_up1');
						$('#bc'+rel).addClass('block_down1');
				
						$('#bc1'+rel).removeClass('bcc1');
						$('#bc1'+rel).addClass('bcc');
				
						revClass('#ic'+rel, 'icon_cont1', 'icon_cont');	
					});
				}else{				
					$('.cli_info_open'+rel).show().css({'overflow':'hidden','max-height':'0px'}).animate({'max-height':'120px'},500);
					$(this).addClass('active');
			
					$('.bor_i'+rel).addClass('bor_info');
			
					$('#bc'+rel).removeClass('block_down1');
					$('#bc'+rel).addClass('block_up1');
			
					$('#bc1'+rel).removeClass('bcc');
					$('#bc1'+rel).addClass('bcc1');
			
					revClass('#ic'+rel, 'icon_cont', 'icon_cont1');
				}
			});


			// });
			screen_w();
			$(window).resize(function(){
				screen_w();
			});	
			$('.but_nn').click(function(){
				$('.open_search').hide();
			});

			$('.sea_but').click(function(){
				f_search(1);	
			});

			$('.search_but input').keyup(function(event){
				f_search(0);
			});

			$('.all_change').click(function(){
				var rel=$(this).attr('rel');
				
				if($(this).prop('checked')){
					$('.table_commodity'+rel+' .cl_trt').each(function(){
						$(this).prop('checked',true);
					});
				}else{
					$('.table_commodity'+rel+' .cl_trt').each(function(){
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
					$('.table_commodity'+rel+' .cl_trt:checked').each(function(){
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
			$('.wind_o2').click(function(){
					//$('body').css({'overflow':'hidden'});
					var rel=$(this).attr('rel');
				//	alert(rel);
					//$('#open_win'+rel).slideToggle();
							
					if($(this).hasClass('open_backg')){
						$('#open_win2'+rel).slideDown();
						//$('#open_win2'+rel).animate({'max-height':'100px'}, 600);			
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
					var txt=($(this).text()).trim();
					$.ajax({
						method:'GET',
						url:'/modules/commodities/ajax/ajax_write.php',
						data:{rel_id2:rel, txt:txt, rel_db_tab:'note'},
					}); //.done(function(d){alert(d);});
				});

//-------SORT-------------------------
			$('.but_sort').click(function(){ 
				$('body').css({'cursor':'wait'});
				//3=date, 7=count, 8=price
				//var sa=3;
				var sa=$(this).attr('rel-sort');

				var act=$('.but_nn .but_nn_active').attr('rel');
				var sort=0;
				var par=0;
				if(sa==7 || sa==8){
					par=1;
				}
				var sa2='';

				if(sa==3){
					sa2='cli_date';
				}
				if(sa==7){
					sa2='cli_count';
				}
				if(sa==8){
					sa2='cli_summa';
				}

				if($(this).hasClass('sort_up')){
					$(this).removeClass('sort_up');
					$(this).addClass('sort_down');
					sort=1;
				}else{
					$(this).removeClass('sort_down');
					$(this).addClass('sort_up');
					sort=2;
				}
				
				var class_act='';
				var act_l=0;
				if(act=='1'){
					class_act='but_active';
				}
				if(act=='2'){
					class_act='but_active7';
					act_l=$('.but_active').length;

				}

				var mmm='';
				var arr_id=[];
				var arr_push=[];
				var arr_html=[];
				$('.'+class_act+' .'+sa2).each(function(ii){
					if(par==1){
						var iid=parseInt($('.'+class_act+':nth-child('+(ii*2+1)+') .cli_id').text());
						var pushh=parseInt($(this).text());
					}else{
						var iid=$('.'+class_act+':nth-child('+(ii*2+1)+') .cli_id').text();
						var pushh=$(this).text();
					}
				 	mmm+=iid+':'+pushh+', ';
				 	arr_id[ii]=iid;
				 	arr_push[ii]=pushh;
				 	arr_html[ii]='<tr class=\"cli'+iid+' but_active tab_up forsearch\" style=\"display:grid;\" id=\"client_open'+iid+'\">'+$('.'+class_act+':nth-child('+(ii*2+1)+')').html()+'<tr class=\"cli'+iid+' but_active\" style=\"display:grid;\">'+$('.'+class_act+':nth-child('+(ii*2+2)+')').html()+'</tr>';
				});

			//	alert(class_act+' = '+mmm);
				//alert($('.'+class_act+':nth-child(1) .'+sa2 ).text());

				var f=0;
				do{
					f=0;
					for(i=1; i<arr_id.length; i++){
						//alert(arr_id[i]+'='+arr_html[i]);
						var iff;
						if(sort==1){
							iff=arr_push[i-1]>arr_push[i];
						}
						if(sort==2){
							iff=arr_push[i-1]<arr_push[i];
						}
						if(iff){
							f=1;

							var copy=arr_push[i-1];
							arr_push[i-1]=arr_push[i];
							arr_push[i]=copy;

							var copy1=arr_id[i-1];
							arr_id[i-1]=arr_id[i];
							arr_id[i]=copy1;

							var copy2=arr_html[i-1];
							arr_html[i-1]=arr_html[i];
							arr_html[i]=copy2;
						}

					}
					var arr_up='';
					for(i=0; i<arr_html.length; i++){
						arr_up+=arr_html[i];
					}

					$('.w_cli tbody').html('').html(arr_up);
				}while(f==1);
				var ff=0;
				var alll='';
				for(i=0; i<arr_push.length; i++){
					alll+=arr_push[i]+', ';
					ff=1;
				}
				if(ff==1){
					$('body').css({'cursor':'default'});
				}
				//alert(alll);

			});
//------------------------------
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

			var w_table=$('.tab_info').width();
			//$('.table_line2_white').width(w_table+8);
			
			
		});

		function f_search(ai){
				var rr=$('.search_but input').val();
				var sel=$('.search_but select').val();
				var push_sea='';
				var num_sel=0;
				var get_sel='';

				var act=$('.but_nn .but_nn_active').attr('rel');
				//alert(act);
				var class_act='';
				if(act=='1'){
					class_act='but_active';
					//class_act='forsearch';
				}
				if(act=='2'){
					class_act='but_active7'
				}

				if(sel=='1'){
					num_sel=6;
				}
				if(sel=='2'){
					num_sel=3;
				}
				if(sel=='3'){
					num_sel=2;
				}
				if(sel=='4'){
					num_sel=5;
					get_sel='select option:selected';
				}
				if(sel=='5'){
					num_sel=4;
				}

				var al='';

				$('.'+class_act).removeAttr('style');

				$('.'+class_act+' td:nth-child('+num_sel+') span '+get_sel).each(function(ii){
					
					var get_text=$(this).text();

					if(get_text.indexOf(rr)>-1){
						if(ai==0){
							push_sea+='<div onclick=\'but_search(\"'+get_text+'\", '+ii+')\'>'+get_text+'</div>';
						}
						if(ai==1){
							$('.'+class_act+':nth-child('+(2*ii+1)+')').css({'display':'grid'});
						 	$('.'+class_act+':nth-child('+(2*ii+2)+')').css({'display':'grid'});
						}
					}else{
						$('.open_search').css({'display':'none'});
						if(ai==1){
							$('.'+class_act+':nth-child('+(2*ii+1)+')').css({'display':'none'});
							$('.'+class_act+':nth-child('+(2*ii+2)+')').css({'display':'none'});
						}
					}
				});
				
				if(ai==0){
					$('.open_search').html(push_sea);

					if(rr=='' || rr==' ' || rr=='  ' ){
						$('.open_search').css({'display':'none'});
						$('.'+class_act+'').removeAttr('style');
					}else{
						$('.open_search').css({'display':'block'});
					}
				}
		}

		function but_search(aa){
			$('.search_but input').val(aa);
			$('.open_search').css({'display':'none'});
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