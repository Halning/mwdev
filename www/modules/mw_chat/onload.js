$(document).ready(function(){
	$.ajax({
		url:'/modules/mw_chat/chat.html',
		//	context: document.body
	})
	.done(function(data){
		$('body').append(data);
		$('.line_name:nth-child(1)').addClass('ln_active');
		$('.include1').show();
		$('.cli_name').each(function(ii){
			var u=$(this).text();
			var dhref=$(this).attr('date_href');
			dhref=dhref.split('id');
			var rel2=dhref[1];
			rel2=rel2.replace('=','');
			var cod=$('.cli_cod').eq(ii).text();
			$('.include1 ul').append('<li id=\"uuser'+rel2+'\" rel=\"'+rel2+'\" ><i class=\"fa fa-user fauser\" ></i> №'+cod+' '+u+'</li>');
		});
		$(document).css({'cursor':'default'});
		$('#uuser'+rel).addClass('tb_active');

		var ll=$('.include1').offset().top;
		var oftop=$('#uuser'+rel).offset().top;
		var upo=30;
		if((oftop-ll-upo)<=0){
			$('.liuser').scrollTop(oftop-ll);
		}else{
			$('.liuser').scrollTop( oftop-ll-upo );
		}
				
	});
	$.ajax({
		method:'POST',
		url:'/modules/mw_chat/ajax/chat_operator.php',
		dataType:'json',
		data:{chat_operator:'1'}
	})
	.done(function(data){
					//alert(data);
		for(i=0; i<data.length; i++){
			var status='';
			var txt='';
			var email='';
			if(data[i]['status']==1){
				status='class=\"active\"';
				$('.table_top tr:nth-child(1) td:last-child').text(data[i]['email']);
				$('.table_top tr:nth-child(2) td:last-child, #putchat').text(data[i]['text']);
				$('.chat_img, .admin_chat_img img').attr('src',data[i]['file']);			
			}
						
			$('.select_admin').append('<span '+status+' rel-id=\"'+data[i]['id']+'\" >'+data[i]['name']+'</span>');
		}
		$.getScript("/modules/mw_chat/changeoperator.js");
	});

	$.getScript("/modules/mw_chat/orderchat.js");
});