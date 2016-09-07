$(document).ready(function(){
	$(".butOpenClient").click(function(){
		var rel=$(this).attr("rel");

		if($(this).hasClass("block_down")){
			$(this).removeClass("block_down").addClass("block_up");
			// $(".openCline"+rel).show();
			$(".occc"+rel).slideDown();
		}else{
			$(this).removeClass("block_up").addClass("block_down");
			//$(".openCline"+rel).slideUp();
			$(".occc"+rel).slideUp();
		}
	});

	$(".client").each(function(){
		var rel=$(this).attr("rel-tr");
		$(".openCline"+rel+' .maill2').attr("rel", rel);
		//alert(rel);
	});

	onloadLine();

	
	var i=1;
	$(".changeLine button").click(function(){
		var line=$(".addLine div").length;
		// $(".addLine").before("<div id='line"+i+"' style='display:table-cell;position:relative;'><div class='changeLineBut'>Name</div><i class='fa fa-times' onclick='deleteLine("+i+")'></i></div>");
		$("body").css({"cursor":"wait"});
		//var id=$('.idClient').attr("rel"); 
		$.ajax({
			method:"GET",
			url:"/modules/commodities/ajax/ajax_SOCClinet2.php",
			data:{add_id:1}
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
				url:"/modules/commodities/ajax/ajax_SOCClinet2.php",
				data:{id:id, text:text, row:'scas_name', rel_id:rel_id}
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

					// var id=$('.idClient').attr("rel");
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
							url:"/modules/commodities/ajax/ajax_SOCClinet2.php",
							data:{text:text, row:row, rel_id:rel_id}
						})
					});
					$('#ss'+rel_id+' .pressSelectS').each(function(){
						var t=$(this).val();
						var row=$(this).attr('id');
						// var id=$('.idClient').attr("rel"); 
						$.ajax({
							method:"GET",
							url:"/modules/commodities/ajax/ajax_SOCClinet2.php",
							data:{text:t, row:row, rel_id:rel_id}
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

	if(confirm(" Удалить \""+del.trim()+"\"?")){
		$.ajax({
			method:"GET",
			url:"/modules/commodities/ajax/ajax_SOCClinet2.php",
			data:{delete_id:d}
		});
		if($("#bonusLine"+d+" div").hasClass("active")){
			$(".changeLineBut").eq(0).addClass("active");
			$(".allTab").eq(0).show();
		}

		$("#bonusLine"+d+", #ss"+d).remove();
	}
	
}