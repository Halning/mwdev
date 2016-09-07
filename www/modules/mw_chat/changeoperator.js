$(document).ready(function(){
	$('.select_admin span').click(function(){
		// alert('admin'); 
		var id=$(this).attr('rel-id');
		$('.select_admin span').removeClass("active");
		$(this).addClass("active");
		$('body').css({'cursor':'wait'});

		$.ajax({
			type:'POST',
			url:'/modules/mw_chat/ajax/select_operator.php',
			dataType:'json',
			data:{chat_operator:id}
		})
		.done(function(data){
			for(i=0; i<data.length; i++){
				var status='';
				var txt='';
				var email='';
				if(data[i]['status']==1){
					status='class=\"active\"';
					$('.table_top tr:nth-child(1) td:last-child').text(data[i]['email']);
					$('.table_top tr:nth-child(2) td:last-child').text(data[i]['text']);
					$('.chat_img, .admin_chat_img img:nth-child(1)').attr('src',data[i]['file']);
					$('body').css({'cursor':'default'});
				}
				//$('.select_admin').append('<span '+status+' >'+data[i]['name']+'</span>');
			}
		});

	});
});