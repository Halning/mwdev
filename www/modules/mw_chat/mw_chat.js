$(document).ready(function(){
	$(".but").click(function(){
		// if(!$(".open_chat").hasClass("active")){
		// 	$.get("chat.html",function(data){
		// 		$(".open_chat").append(data).addClass('active');

		// 	});
		// }else{
		// 	$(".open_chat").removeClass("active").text('');
		// }
	});
	$("body").load('chat.html');

	$(".line_name").click(function(){
		$(".line_name").removeClass("ln_active");
		$(this).addClass("ln_active");
	});

	//$("#drap").draggable();
});