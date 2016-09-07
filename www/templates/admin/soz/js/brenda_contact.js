$(document).ready(function(){
			//var id='.($id).';
			
			// alert("ddd: "+id);
			var iiTab=0; // for table row
			var getTab;


			$(".but_sex").click(function(){
				if($(this).hasClass("but_sex_line"))
					$(this).removeClass("but_sex_line");
				else
					$(this).addClass("but_sex_line");
			});
			var ii=1;
			$(".add_commodity").click(function(){
				// if(ii==3){
				// 	return false;
				// }
				var txt="<div class=bre"+ii+" ><span class=but_del id="+ii+" onclick=\'delete_brenda(this.id)\' ></span>";
				txt+="<select>";
				txt+="	<option>ОДЕЖДА</option>";
				txt+="	<option>ОБУВЬ</option>";
				txt+="	<option>АКСЕССУАРЫ</option>";
				txt+="</select>";
				txt+="<span class=\'but_sex sex_M\' id=\'sex_M"+ii+"\' onclick=\'change_mw(this.id)\' >М</span>";
				txt+="<span class=\'but_sex sex_W\' id=\'sex_W"+ii+"\' onclick=\'change_mw(this.id)\' >Ж</span>";
				txt+="<span class=\'but_sex sex_K\' id=\'sex_K"+ii+"\' onclick=\'change_mw(this.id)\' >Д</span>";
				txt+="<span class=\'but_sex sex_U\' id=\'sex_U"+ii+"\' onclick=\'change_mw(this.id)\' >У</span></div>";
				 $(".brenda_body").append(txt);
				 ii++;
			});
			var ii1=1;
			$(".add_name").click(function(){
				// if(ii1==3){
				// 	return false;
				// }
				var txt="<br id=\'bdelete_name\' /><label id=\'ldelete_name\' class=bor_label2></label><input type=text id=\'idelete_name\' class=\'input_bor2 cont_name\' style=\"width: 250px;\" /><span class=\'but_del\' id=\'delete_name\' style=\'position: absolute;margin-top: -38px;margin-left: 450px;\' onclick=\'del_but(this.id)\'  ></span>";
				$("#push_name").append(txt);
				ii1++;
			});
			var ii2=1;
			$(".add_phone").click(function(){
				// if(ii2==5){
				// 	return false;
				// }
				var txt="<br id=\'bdelete_phone\' /><label id=\'ldelete_phone\' class=bor_label2></label><input type=text id=\'idelete_phone\' class=\'input_bor2 cont_phone\' style=\"width: 250px;\" /><span class=but_del id=\'delete_phone\' style=\'position: absolute;margin-top: -38px;margin-left: 450px;\' onclick=\'del_but(this.id)\'></span>";
				$("#push_phone").append(txt);
				ii2++;
			});
			var ii3=1;
			$(".add_mail").click(function(){
				// if(ii3==3){
				// 	return false;
				// }
				var txt="<br id=\'bdelete_mail\' /><label id=\'ldelete_mail\' class=bor_label2 ></label><input type=text id=\'idelete_mail\' class=\'input_bor2 cont_mail\' style=\'width: 250px;\' /><span class=but_del id=\'delete_mail\' style=\'position: absolute;margin-top: -38px;margin-left: 450px;\' onclick=\'del_but(this.id)\'></span>";
				$("#push_mail").append(txt);
				ii3++;
			});

			$(".size_brenda").click(function(){
				$(".tab_size").slideToggle();
			});
			$(".but_del").click(function(){
				alert("dddd");
			});
			//alert(id);
			$.ajax({
				method:"GET",
				url:"http://"+window.location.hostname+"/modules/commodities/admin/fun_ajax.php",
				data:{contact_id:id},
				dataType:"json"
			})
			.done(function(dat){
				// alert("sss: "+dat);
				$("#bc_name").val(dat["name"]);
				$("#bc_site").val(dat["site"]);
				$("#bc_info").val(dat["info"]);

				$(".con_flp").val(dat["con_flp"]);
				$(".con_egp").val(dat["con_egp"]);
				$(".con_inn").val(dat["con_inn"]);
				$(".con_add").val(dat["con_add"]);
				$(".con_pc").val(dat["con_pc"]);
				$(".con_bank").val(dat["con_bank"]);
				$(".con_mfo").val(dat["con_mfo"]);
							
				funJSON(dat["t_tab"]);

				var uc_opt_skidka=dat["uc_opt_skidka"]; 
				var uc_opt_natsenka=dat["uc_opt_natsenka"]; 
				var uc_opt_otgruz=dat["uc_opt_otgruz"]; 
				var uc_opt_delivery=dat["uc_opt_delivery"]; 
				var uc_opt_price=dat["uc_opt_price"]; 

				 for(i=0; i<=100; i++){
				 	if(i!=uc_opt_skidka){
						if(i==0)
				 			$(".sel_sk").append("<option></option>");
				 		else
				 			$(".sel_sk").append("<option>"+i+"</option>");
				 	}else{
				 		$(".sel_sk").append("<option selected>"+i+"</option>");
				 	}
				 }
				 // for(i=0; i<=100; i++){
				 // 	if(i!=uc_opt_natsenka){
					// 	if(i==0)
				 // 			$(".sel_na").append("<option></option>");
				 // 		else
				 // 			$(".sel_na").append("<option>"+i+"</option>");
				 // 	}else{
				 // 		$(".sel_na").append("<option selected>"+i+"</option>");
				 // 	}
				 // }
				 $(".sel_na").val(uc_opt_natsenka);
				 for(i=0; i<=100; i++){
				 	if(i!=uc_opt_otgruz){
						if(i==0)
				 			$(".sel_ot").append("<option></option>");
				 		else
				 			$(".sel_ot").append("<option>"+i+"</option>");
				 	}else{
				 		$(".sel_ot").append("<option selected>"+i+"</option>");
				 	}
				 }
				
				$(".ot_min_price").val(dat["ot_min_price"]);

				if(uc_opt_delivery=="за счет MW")
					var dosel1="<option selected >за счет MW</option>";
				else
					var dosel1="<option>за счет MW</option>";

				if(uc_opt_delivery=="за счет поставщика")
					var dosel2="<option selected>за счет поставщика</option>";
				else
					var dosel2="<option>за счет поставщика</option>";

				// if(uc_opt_delivery=="ЕКСПРЕСС")
				// 	var dosel3="<option selected >ЕКСПРЕСС</option>";
				// else
				// 	var dosel3="<option>ЕКСПРЕСС</option>";

				$(".sel_do").append(dosel1+dosel2+dosel3);


				if(uc_opt_price=="НА САЙТЕ")
					var prsel1="<option selected>НА САЙТЕ</option>";
				else
					var prsel1="<option>НА САЙТЕ</option>";

				if(uc_opt_price=="ИМПОРТ ИЗ ПРАЙС-ЛИСТ")
					var prsel2="<option selected>ИМПОРТ ИЗ ПРАЙС-ЛИСТA</option>";
				else
					var prsel2="<option>ИМПОРТ ИЗ ПРАЙС-ЛИСТA</option>";

				if(uc_opt_price=="ФОРМИРУЕТСЯ ИЗ РОЗНИЧНОЙ")
					var prsel3="<option selected>ФОРМИРУЕТСЯ ИЗ РОЗНИЧНОЙ</option>";
				else
					var prsel3="<option>ФОРМИРУЕТСЯ ИЗ РОЗНИЧНОЙ</option>";

				$(".sel_pr").append(prsel1+prsel2+prsel3);

				var uc_pr_skidka=dat["uc_pr_skidka"];
				var uc_pr_natsenka=dat["uc_pr_natsenka"];
				var uc_pr_otgruz=dat["uc_pr_otgruz"]; 
				var uc_pr_delivery=dat["uc_pr_delivery"]; 
				var uc_pr_price=dat["uc_pr_price"]; 
				$(".ot_min_pr_price").val(dat["ot_min_pr_price"]); 

				for(i=0; i<=100; i++){
				 	if(i!=uc_pr_skidka){
						if(i==0)
				 			$(".sel_pr_sk").append("<option></option>");
				 		else
				 			$(".sel_pr_sk").append("<option>"+i+"</option>");
				 	}else{
				 		$(".sel_pr_sk").append("<option selected>"+i+"</option>");
				 	}
				 }
				 // for(i=0; i<=100; i++){
				 // 	if(i!=uc_pr_natsenka){
					// 	if(i==0)
				 // 			$(".sel_pr_na").append("<option></option>");
				 // 		else
				 // 			$(".sel_pr_na").append("<option>"+i+"</option>");
				 // 	}else{
				 // 		$(".sel_pr_na").append("<option selected>"+i+"</option>");
				 // 	}
				 // }
				 $(".sel_pr_na").val(uc_pr_natsenka);
				 for(i=0; i<=100; i++){
				 	if(i!=uc_pr_otgruz){
						if(i==0)
				 			$(".sel_pr_ot").append("<option></option>");
				 		else
				 			$(".sel_pr_ot").append("<option>"+i+"</option>");
				 	}else{
				 		$(".sel_pr_ot").append("<option selected>"+i+"</option>");
				 	}
				 }

				if(uc_pr_delivery=="за счет MW")
					var dosel1="<option selected >за счет MW</option>";
				else
					var dosel1="<option>за счет MW</option>";

				if(uc_pr_delivery=="за счет поставщика")
					var dosel2="<option selected>за счет поставщика</option>";
				else
					var dosel2="<option>за счет поставщика</option>";

				if(uc_pr_delivery=="клиенту: за счет п-ка")
					var dosel3="<option selected >клиенту: за счет п-ка</option>";
				else
					var dosel3="<option>клиенту: за счет п-ка</option>";

				$(".sel_pr_do").append(dosel1+dosel2+dosel3);

				if(uc_pr_price=="НА САЙТЕ")
					var prsel1="<option selected >НА САЙТЕ</option>";
				else
					var prsel1="<option>НА САЙТЕ</option>";

				if(uc_pr_price=="ИМПОРТ ИЗ ПРАЙС-ЛИСТA")
					var prsel2="<option selected >ИМПОРТ ИЗ ПРАЙС-ЛИСТA</option>";
				else
					var prsel2="<option>ИМПОРТ ИЗ ПРАЙС-ЛИСТA</option>";

				if(uc_pr_price=="НАЦЕНКА НА ОПТ")
					var prsel3="<option selected >НАЦЕНКА НА ОПТ</option>";
				else
					var prsel3="<option>НАЦЕНКА НА ОПТ</option>";

				$(".sel_pr_price").append(prsel1+prsel2+prsel3);

				$("#op_pl").val(dat["op_pl"]);
				$("#op_name").val(dat["op_name"]);
				$("#op_bank").val(dat["op_bank"]);
				$("#op_chet").val(dat["op_chet"]);
				$("#op_dop").val(dat["op_dop"]);
				$("#de_city").val(dat["de_city"]);
				$("#de_cpo").val(dat["de_cpo"]);
				$("#de_address").val(dat["de_address"]);
				$("#de_get").val(dat["de_get"]);
				$("#de_dop").val(dat["de_dop"]);

				$("#cont_dop").val(dat["cont_dop"]);

				var cont_name=dat["cont_name"].split(";");
				for(i=1; i<cont_name.length; i++){
					if(i==1){
						$(".cont_name").val(cont_name[i]);
					}else{
						var txt="<br id=\'bdelete_name\' /><label id=\'ldelete_name\' class=bor_label2></label><input type=text id=\'idelete_name\' class=\'input_bor2 cont_name\' value="+cont_name[i]+" /><span class=\'but_del\' id=\'delete_name\' style=\"position: absolute;margin-top: -38px;margin-left: 450px;\" onclick=\'del_but(this.id)\' ></span>";
						$("#push_name").append(txt);
					}
				}
				var cont_phone=dat["cont_phone"].split(";");
				for(i=1; i<cont_phone.length; i++){
					if(i==1){
						$(".cont_phone").val(cont_phone[i]);
					}else{
						var txt="<br id=\'bdelete_phone\' /><label id=\'ldelete_phone\' class=bor_label2></label><input type=text id=\'idelete_phone\' class=\'input_bor2 cont_phone\' value="+cont_phone[i]+" /><span class=but_del id=\'delete_phone\' style=\'position: absolute;margin-top: -38px;margin-left: 450px;\' onclick=\'del_but(this.id)\' ></span>";
						$("#push_phone").append(txt);
					}
				}
				var cont_mail=dat["cont_mail"].split(";");
				for(i=1; i<cont_mail.length; i++){
					if(i==1){
						$(".cont_mail").val(cont_mail[i]);
					}else{
						var txt="<br id=\'bdelete_mail\' /><label id=\'ldelete_mail\' class=bor_label2></label><input type=text id=\'idelete_mail\' class=\'input_bor2 cont_mail\' value="+cont_mail[i]+" /><span class=but_del id=\'delete_mail\' style=\'position: absolute;margin-top: -38px;margin-left: 450px;\' onclick=\'del_but(this.id)\' ></span>";
						$("#push_mail").append(txt);
					}
				}

				var coom=dat["com_sex"].split(",");
				for(j=0; j<coom.length; j++){
					if(j>0){
						var txt="<div class=bre"+j+" ><span class=but_del id="+j+" onclick=\'delete_brenda(this.id)\' ></span>";
						txt+="<select>";
						txt+="	<option>ОДЕЖДА</option>";
						txt+="	<option>ОБУВЬ</option>";
						txt+="	<option>АКСЕССУАРЫ</option>";
						txt+="</select>";
						txt+="<span class=\'but_sex sex_M\' id=\'sex_M"+j+"\'  onclick=\'change_mw(this.id)\' >М</span>";
						txt+="<span class=\'but_sex sex_W\' id=\'sex_W"+j+"\' onclick=\'change_mw(this.id)\' >Ж</span>";
						txt+="<span class=\'but_sex sex_K\' id=\'sex_K"+j+"\' onclick=\'change_mw(this.id)\' >Д</span>";
						txt+="<span class=\'but_sex sex_U\' id=\'sex_U"+j+"\' onclick=\'change_mw(this.id)\' >У</span></div>";
						 $(".brenda_body").append(txt);
					}
					var coom_sex=coom[j].split("=");
					$(".bre"+j+" select option").each(function(){
						if($(this).text()==coom_sex[0]){
							//alert($(this).text());
							$(this).attr("selected","true");
						}
					});
					
					var bir_sex=coom_sex[1].split(";");
					for(i=1; i<bir_sex.length; i++){
						var c="";
						switch (bir_sex[i]) {
							case "М":
								c="M";
								break;
							case "Ж":
								c="W";
								break;
							case "Д":
								c="K";
								break;
							case "У":
								c="U";
								break;
						}
						if(j==0)
							$(".sex_"+c).addClass("but_sex_line");
						else
							$("#sex_"+c+j).addClass("but_sex_line");
						//alert(c);
					}
				}
				
			});

			//alert(getTab);
			// $.get("http://www.makewear.com.ua/modules/commodities/admin/fun_ajax.php",{contact_id:id})
			// .done(function(as){
			// 	alert(as);
			// });

			$("#save_brenda").click(function(){
				var rel=$(".body_bc").attr("rel");
				var name=$("#bc_name").val();
				var site=$("#bc_site").val();
				var info=$("#bc_info").val();

				//--ТОВАР----
				var com_sex="";
				var com_sex1="";
				var com_sel="";
				 $(".brenda_body div").each(function(index){
					com_sel=$(".bre"+index+" select option:selected").text();
					com_sex1+=com_sel+"=0";
					$(".bre"+index+" .but_sex").each(function(){
						if($(this).hasClass("but_sex_line"))
							com_sex1+=";"+$(this).text();
					});	
					com_sex1+=","; 	
				 });
			
				com_sex=com_sex1;
			//	alert(com_sex1);

				//---КОНТРАГЕНТ----
				var con_flp=$(".con_flp").val();
				var con_egp=$(".con_egp").val();
				var con_inn=$(".con_inn").val();
				var con_add=$(".con_add").val();
				var con_pc=$(".con_pc").val();
				var con_bank=$(".con_bank").val();
				var con_mfo=$(".con_mfo").val();

				var t_tab="";

				t_tab+="{";
				var pushTab="";
				var pushTab2="";
				$("#table_size tr").each(function(i){
					if(i==0){
						pushTab='"head":[';
						$("#table_size tr:nth-child("+(i+1)+") td").each(function(j){
							var pu=$(this).text();
							if(pu!=""){
								pushTab+='"'+pu+'",';
							}
							//alert($(this).text());
						});
						pushTab+='],';
					}else{
						pushTab2+="[";
						$("#table_size tr:nth-child("+(i+1)+") td").each(function(j){
							var pu=$(this).text();
							if(pu!=""){
								pushTab2+='"'+pu+'",';
							}
							// alert($(this).text());
						});
						pushTab2+="],";
					}
					if(i==0){
						t_tab+=pushTab+'"tr":[';
					}
					if((i+1)==$("#table_size tr").length){
						t_tab+=pushTab2+"]";
					}
				});
				t_tab+="}";
				t_tab=t_tab.replace(/,]/g, ']');
				//alert(t_tab);

				//--УСЛОВИЯ----
				var sel_sk=$(".sel_sk option:selected").text();
				var sel_ot=$(".sel_ot option:selected").text();
				// var sel_na=$(".sel_na option:selected").text();
				var sel_na=$(".sel_na").val();
				var sel_do=$(".sel_do option:selected").text();
				var sel_pr=$(".sel_pr option:selected").text();
				var sel_pr_sk=$(".sel_pr_sk option:selected").text();
				var sel_pr_do=$(".sel_pr_do option:selected").text();
				var sel_pr_price=$(".sel_pr_price option:selected").text();

				//var sel_pr_na=$(".sel_pr_na option:selected").text();
				var sel_pr_na=$(".sel_pr_na").val();
				var sel_pr_ot=$(".sel_pr_ot option:selected").text();

				var ot_min_price=$(".ot_min_price").val();
				var ot_min_pr_price=$(".ot_min_pr_price").val();

				//--КОНТАКТЫ----
				var cont_dop=$("#cont_dop").val();
				var cont_name="0";
				$(".cont_name").each(function(){
					if($(this).val()!="")
						cont_name+=";"+$(this).val();
				});
				var cont_phone="0";
				$(".cont_phone").each(function(){
					if($(this).val()!="")
						cont_phone+=";"+$(this).val();
				});
				var cont_mail="0";
				$(".cont_mail").each(function(){
					if($(this).val()!="")
						cont_mail+=";"+$(this).val();
				});

				//--РЕКВИЗИТЫ----
				var op_pl=$("#op_pl").val();
				var op_name=$("#op_name").val();
				var op_bank=$("#op_bank").val();
				var op_chet=$("#op_chet").val();
				var op_dop=$("#op_dop").val();
				var de_city=$("#de_city").val();
				var de_cpo=$("#de_cpo").val();
				var de_address=$("#de_address").val();
				var de_get=$("#de_get").val();
				var de_dop=$("#de_dop").val();


				$.ajax({
					method:"GET",
					url:"http://"+window.location.hostname+"/modules/commodities/admin/fun_ajax.php",
					data:{con_payment:"true", rel:rel, name:name, site:site, info:info, sel_sk:sel_sk, sel_ot:sel_ot, sel_na:sel_na, sel_do:sel_do, sel_pr:sel_pr, sel_pr_sk:sel_pr_sk, sel_pr_do:sel_pr_do, sel_pr_price:sel_pr_price, op_pl:op_pl, op_name:op_name, op_bank:op_bank, op_chet:op_chet, op_dop:op_dop, de_city:de_city, de_cpo:de_cpo, de_address:de_address, de_get:de_get, de_dop:de_dop, cont_dop:cont_dop, cont_mail:cont_mail, cont_name:cont_name, cont_phone:cont_phone, com_sex:com_sex, ot_min_price:ot_min_price, sel_pr_na:sel_pr_na, sel_pr_ot:sel_pr_ot, ot_min_pr_price:ot_min_pr_price,t_tab:t_tab, con_flp:con_flp, con_egp:con_egp, con_inn:con_inn, con_add:con_add, con_pc:con_pc, con_bank:con_bank, con_mfo:con_mfo}
				})
				.done(function(dat){
					alert(dat);
					location.href="http://"+window.location.hostname+"/?admin=payment";
				});
			});

		
			// ==============Table Size====================

			$("#add_tr").click(function(){
				var hasTd=$('#table_size td').html();
				if(hasTd==null){
					$('#table_size').append("<tr><td>head</td><td>head</td><td><i class='fa fa-times del_tr' onclick='del_tr(this.id)'></i></td></tr>");
				}else{
					var first=$('#table_size tr:first').html();
	
					$('#table_size').append("<tr>"+first+"</td></tr>");
				}
				onloadTable();
			});

			$("#add_last_td").click(function(){
				var hasTd=$('#table_size td').html();
				if(hasTd==null){
					$('#table_size').append("<tr><td>last</td><td><i class='fa fa-times del_tr' onclick='del_tr(this.id)'></i></td></tr>");
				}else{
					$('#table_size tr td:last-child').before("<td>last</td>");
				}
				onloadTable();
			});
		});
	

		function onloadTable(){
			var trLeng=$("#table_size tr:first-child td").length;
			$("#table_size tr").each(function(i){
				$(this).attr("id","trr"+i);
				$(this).find(".del_tr").attr("id",i);
				if(i==0){
					$(this).css({"font-weight":"bold"});
					// $(this).css({"background-color":"#5fb0ba", "color": "#fff"});
				}

				$("#table_size tr:nth-child("+(i+1)+") td").each(function(j){
					if(trLeng!=(j+1))
					$(this).addClass("tdd"+j);
				});
			});

			$("#butDeleteRow").empty();
			for(var i=0; i<trLeng-1; i++){
				var wid=$("#table_size tr:first-child td").eq(i).width();
				$("#butDeleteRow").append("<div style='display:table-cell;width:"+wid+"px;' class='tdd"+i+"' ><i class='fa fa-times delRow' rel='"+i+"' ></div>");
			}
			$("#butDeleteRow").append("<div style='display:table-cell;width:13px;'  ></div>");

			$("#table_size td").click(function(){
				$(this).attr("contenteditable","true");
			});


			$(".delRow").click(function(){
				var rel=parseInt($(this).attr("rel"));
				$(".tdd"+rel).remove();
				onloadTable();
			});

			$("#butDeleteRow").width($("#table_size").width());
		}
		function del_tr(id){
			$("#table_size #trr"+id).remove();
		}

		function del_but(sid){
			$(document).ready(function(){
				$("#"+sid).remove();
				$("#i"+sid).remove();
				$("#l"+sid).remove();
				$("#b"+sid).remove();
			});
		}
		function del_th(tdel){
			$("#th_head"+(tdel-1)).remove();
			$("#table_size tbody tr").each(function(index){
				$("#t"+(index+1)+"_td"+(tdel-1)).remove();
			});
		}
		function add_size(as){
			$(document).ready(function(){
				$("#table_size .t_td").each(function(){
					var tt=$(this).find("input").val();
					$(this).find("input").remove();
					$(this).text(tt);
					$(this).attr("onclick","add_size(this.id)");
				});
				$("#table_size thead td").each(function(){
					var tt=$(this).find("input").val();
					if(tt!=undefined){
						$(this).find("input").remove();
						$(this).attr("onclick","add_head(this.id)");
						$(this).html(tt+"<i onclick=del_th() class=\'head_th_del fa fa-times\'></i>");
					}
				});
				var text=$("#"+as).text();
				$("#"+as).removeAttr("onclick");
				$("#"+as).html("<input type=\'text\' value=\'"+text+"\' class=\'input_size "+as+"\' autofocus />");
			});
		}
		function add_head(as){
			//$(document).ready(function(){
				$("#table_size thead td").each(function(){
					var tt=$(this).find("input").val();
					if(tt!=undefined){
						$(this).find("input").remove();
						$(this).attr("onclick","add_head(this.id)");
						$(this).html(tt+"<i onclick=del_th() class=\'head_th_del fa fa-times\'></i>");
					}
				});
				$("#table_size .t_td").each(function(){
					var tt=$(this).find("input").val();
					$(this).find("input").remove();
					$(this).text(tt);
					$(this).attr("onclick","add_size(this.id)");
				});
				var text=$("#"+as).text();
				$("#"+as).removeAttr("onclick");
				$("#"+as).html("<input type=\'text\' value=\'"+text+"\' class=\'input_head "+as+"\' autofocus />");
			//});
		}
		function delete_brenda(ssi){
			$(".bre"+ssi).remove();
		}
		function change_mw(ch){
			if($("#"+ch).hasClass("but_sex_line"))
					$("#"+ch).removeClass("but_sex_line");
				else
					$("#"+ch).addClass("but_sex_line");
		}

		function funJSON(js){
			//alert(JSON.stringify(js));
			// var numbers = "[0, 1, 2, 3]";
			// numbers = JSON.parse(numbers);
			// alert( numbers[1] ); // 1

			// var jsonTab="{'head':[{'head':'head1'},{'head':'head2'},{'head':'head2'}]}";
			// var showTab=JSON.parse(JSON.stringify(jsonTab));
			// alert( showTab.head[0].head );
			// var str='{"head":["head1","head2","head3"],"tr":[["body1","body2","body3"],["body4","body5","body6"]]}';
			// var showTab=$.parseJSON(str);
			var showTab=$.parseJSON(js);
			if(showTab.head.length!=0){
			var head="<tr>";
			for(var i=0; i<showTab.head.length; i++){
				head+="<td>"+showTab.head[i]+"</td>";
			}
			head+="<td><i class='fa fa-times del_tr' onclick='del_tr(this.id)'></i></td></tr>";
			$('#table_size').append(head);
			//alert(head);
			//alert(showTab.tr[0][0])

			var tr="";
			for(var i=0; i<showTab.tr.length; i++){
				tr+="<tr>"
				for(var j=0; j<showTab.tr[i].length; j++){
					tr+="<td>"+showTab.tr[i][j]+"</td>";
				}
				tr+="<td><i class='fa fa-times del_tr' onclick='del_tr(this.id)'></i></td></tr>";
			}
			$('#table_size').append(tr);
		}

			onloadTable();
		}