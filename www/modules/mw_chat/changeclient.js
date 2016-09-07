$(document).ready(function(){
	$('.liuser ul li').click(function(){
		var rel=$(this).attr('rel');
		$('.liuser ul li').removeClass('tb_active');
		$(this).addClass('tb_active');


		var cid=$(this).attr('rel-cid');
		
		$('.read_chat').html('');

		$(this).find('.signal_num').remove();
		var name=$(this).text();


		$.ajax({
			method:'GET',
			url:'/modules/mw_chat/ajax/readclient.php',
			data:{client:name, cid:cid},
			dataType:'json'
		})
		.done(function(data){
			// alert(data.length);
			var txt='';

			for(i=0; i<data.length; i++){
				var time=data[i].date;
				time=time.split(' ');

				if(data[i].to=='admin'){
					var cid=data[i].from.replace("cid","");
				 	txt+='<div class="client_chat" rel-cid='+cid+'><div class="client_chat_read" style="width: 100%;padding-right: 0px;"><div id="put">';
				 	txt+=data[i].message;
				 	txt+='<span>'+time[1]+'</span>';
				 	txt+='</div></div>';
				 	txt+='<div class="client_chat_img">';
				 	txt+='</div></div>';
				}

				if(data[i].to!='admin'){
					txt+='<div class="admin_chat">';
					txt+='<div class="admin_chat_img"><img id="imgfirst" src="/online/images/avatar/op_avatar_'+data[i].from+'.jpg" /></div>';
					txt+='<div class="admin_chat_read">';
					txt+='<div id=\'putchat\'>';
					txt+=data[i].message;
					txt+='<span>'+time[1]+'</span>';
					txt+='</div></div></div>';
				}
			}

			$('.read_chat').append(txt);
			$('.read_chat').scrollTop($('.read_chat').prop('scrollHeight'));

		//	$(this).find('.signal_num').remove();
		});
	});
});