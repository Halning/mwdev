$(document).ready(function(){

	// $("body").click(function(){
	// 	alert("aaa");
	// });

	$(".butCard .tab-td").click(function(){
		var relCard=$(this).attr("rel-card");

		$(".butCard .tab-td").removeClass("active");
		$(this).addClass("active");

		$(".lineCard").css({"display":"none"});
		$("#card"+relCard).css({"display":"block"});

		if(relCard=='2'){
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
		}
	});

	$(".butStrana #but").click(function(){
		$(".butStrana #but").removeClass("active");
		$(this).addClass("active");

		var id=$('.idClient').attr("rel"); 
		var relCountry=$(this).attr("rel-country");
		var row=$(this).attr("rel-id");

		$.ajax({
			method:"GET",
			url:"/modules/commodities/ajax/ajax_SOCClinet.php",
			data:{idIn:id, text:relCountry, row:row}
		})
	});

	$(".infoType").click(function(){
		$(this).attr("contenteditable","true");
	});
	$(".bonus input").keyup(function(){
		var a=parseInt($(this).val());
		
		if(a<0){
			$(this).val(1);
		}
		if(a>5000){
			$(this).val(5000);
		}
	});

	$('.pressKey').keyup(function(){
		var text=$(this).val();
		var row=$(this).attr('id');
		var id=$('.idClient').attr("rel");
		if($(this).hasClass("infoType")){
			text=$(".infoType").text();
		}
		$.ajax({
			method:"GET",
			url:"/modules/commodities/ajax/ajax_SOCClinet.php",
			data:{idIn:id, text:text, row:row}
		})
	});

	$('.pressSelect').change(function(){
		var t=$(this).val();
		var row=$(this).attr('id');
		var id=$('.idClient').attr("rel"); 

		$.ajax({
			method:"GET",
			url:"/modules/commodities/ajax/ajax_SOCClinet.php",
			data:{idIn:id, text:t, row:row}
		})
	});
	// $(".changeLineBut").click(function(){
	// 	$(".changeLine .changeLineBut").removeClass("active");
	// 	$(this).addClass("active");
	// });
	// $(".changeLineBut").dblclick(function(){
	// 	$(this).attr("contenteditable",true);
	// });
	onloadLine();

	
	var i=1;
	$(".changeLine button").click(function(){
		var line=$(".addLine div").length;
		// $(".addLine").before("<div id='line"+i+"' style='display:table-cell;position:relative;'><div class='changeLineBut'>Name</div><i class='fa fa-times' onclick='deleteLine("+i+")'></i></div>");
		$("body").css({"cursor":"wait"});
		var id=$('.idClient').attr("rel"); 
		$.ajax({
			method:"GET",
			url:"/modules/commodities/ajax/ajax_SOCClinet.php",
			data:{add_id:id}
		})
		.done(function(data){
			$(".addLine").before("<div style='display:table-cell;position:relative;' id='bonusLine"+data+"'><div class='changeLineBut ' rel='"+data+"'> Add Name</div><i class='fa fa-times' onclick='deleteLine("+data+")'></i></div>");
			$.get("/templates/admin/soz/js/viem/bonusTable.html",function(txt){
				$(".cardTable .bonus").append(txt);
				$(".viemAdd").attr("id","ss"+data);
				$("#ss"+data).removeClass("viemAdd");

				$("body").css({"cursor":"default"});

				onloadLine();
			});
		});

		i++;
	});
	function onloadLine(){
		$(".changeLineBut").click(function(){
			var rel=$(this).attr("rel");

			if($(".butSave").hasClass("active")){
				$(".changeLine .changeLineBut").removeClass("active");
				$(this).addClass("active");

				$(".allTab").css({'display':'none'});
				$("#ss"+rel).css({'display':'block'});
				//$("#bonusLine"+rel).append("<i class='fa fa-times' onclick='deleteLine("+rel+")'></i>")
			}else{
				alert("Нажми на кнопку \"Сохранить\"");
			}
		});
		$(".changeLineBut").dblclick(function(){
			$(this).attr("contenteditable",true);
		});
		$(".changeLineBut").keyup(function(){
			var text=$(this).text();
			var rel_id=$(this).attr("rel");
			var id=$('.idClient').attr("rel");
			$.ajax({
				method:"GET",
				url:"/modules/commodities/ajax/ajax_SOCClinet.php",
				data:{id:id, text:text, row:'scj_name', rel_id:rel_id}
			})
		});
		$(".butWrite").click(function(){
			$(".butSave").removeClass("active");
			$(".bonus .pressKeyS, .bonus .pressSelectS").prop("disabled", false).css({"color":"black"});
		});
		$(".butSave").click(function(){
			if($(this).hasClass("active")){

			}else{
				if(confirm("Сохранить?")){
					$(".bonus .pressKeyS, .bonus .pressSelectS").prop("disabled", true).css({"color":""});

					var id=$('.idClient').attr("rel");
					var rel_id=$(".changeLine .active").attr("rel");

					$('#ss'+rel_id+' .pressKeyS').each(function(){
						var text=$(this).val();
						var row=$(this).attr('id');
						// var id=$('.idClient').attr("rel");
						if($(this).hasClass("infoType")){
							text=$(".infoType").text();
						}
						$.ajax({
							method:"GET",
							url:"/modules/commodities/ajax/ajax_SOCClinet.php",
							data:{id:id, text:text, row:row, rel_id:rel_id}
						})
					});
					$('#ss'+rel_id+' .pressSelectS').each(function(){
						var t=$(this).val();
						var row=$(this).attr('id');
						// var id=$('.idClient').attr("rel"); 
						$.ajax({
							method:"GET",
							url:"/modules/commodities/ajax/ajax_SOCClinet.php",
							data:{id:id, text:t, row:row, rel_id:rel_id}
						})
					});
					$(".butSave").addClass("active");
				}else{

				}
				// $(".butSave").addClass("active");
			}
		});
	}
});

function deleteLine(d){
	var del=$("#bonusLine"+d+" .changeLineBut").text();
	var id=$('.idClient').attr("rel");
	if(confirm(" Удалить \""+del.trim()+"\"?")){
		$.ajax({
			method:"GET",
			url:"/modules/commodities/ajax/ajax_SOCClinet.php",
			data:{id2:id, delete_id:d}
		});
		if($("#bonusLine"+d+" div").hasClass("active")){
			$(".changeLineBut").eq(0).addClass("active");
			$(".allTab").eq(0).show();
		}

		$("#bonusLine"+d+", #ss"+d).remove();
	}
	
}
