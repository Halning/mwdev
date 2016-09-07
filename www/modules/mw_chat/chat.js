$(document).ready(function(){
	var socket = io.connect('http://localhost:8264');

	//alert("Work js");
	$('.select_admin span').click(function(){
		//alert('admin'); 
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

			$.getScript('/modules/mw_chat/chat.js');
		});

	});
	$('.liuser ul li').click(function(){
		var rel=$(this).attr('rel');
		$('.liuser ul li').removeClass('tb_active');
		$(this).addClass('tb_active');
	});
	$('.head_img').mouseover(function(){
		$(this).find('.icon_img').show();
	})
	.mouseout(function(){
		$(this).find('.icon_img').hide();
	});
	$('.head_img .fa-cloud-upload').click(function(){
		$('.body_upload').show();
		var img=$('.chat_img').attr('src');
		$('.upload_img img').attr('src', img);
		$('.upprogress .see_progress').css({'width':'0%'});
		$('.text_pro').text('0%');

	});
	$('.body_upload .fa-times').click(function(){
		$('.body_upload').hide();
	})

	//---Upload image for chat operator----

	$('.div_form input[type=file]').on('change',function(event){

		$(".upload_img").html("<div class='loading_img'><div><i class=\"fa fa-spinner fa-pulse\"></i></div></div>");

		var files=event.target.files;
		var data = new FormData();
		$.each(files,function(key, value){
			console.log(value);
			data.append(key, value);
		})
		$.ajax({
			xhr: function () {
				//console.log("Upload");
		        var xhr = new window.XMLHttpRequest();
		        xhr.upload.addEventListener("progress", function (evt) {
		            if (evt.lengthComputable) {
		                var percentComplete = evt.loaded / evt.total;
		               // console.log(percentComplete);
		                $('.upprogress .see_progress').css({
		                    width: percentComplete * 100 + '%'
		                });
		                $('.text_pro').text(Math.floor(percentComplete * 100) + '%');
		            }
		        }, false);
		        xhr.addEventListener("progress", function (evt) {
		            if (evt.lengthComputable) {
		                var percentComplete = evt.loaded / evt.total;
		              //  console.log(percentComplete);
		                $('.upprogress .see_progress').css({
		                    width: percentComplete * 100 + '%'
		                });
		                $('.text_pro').text(Math.floor(percentComplete * 100) + '%');
		            }
		        }, false);
		        return xhr;
		    },
		    beforeSend:function(){
		    	$('.upprogress .see_progress').css({'background-color': '#337AB7'}); // #D9534F is red
		    },
		    complete: function(){
		    	$('.text_pro').text("Complete!");
		    	$('.upprogress .see_progress').css({'background-color': '#5CB85C'});
		    },
			type:'POST',
			url:'/modules/mw_chat/ajax/upload.php',
			cache:false,
			processData:false,
			contentType:false,
			data:data
		})
		.done(function(ddd){
			var upload=ddd.split("@");
			if(parseInt(upload[1])==1){
				// alert(upload[0]);
				//$('.upload_img img, .chat_img').attr('src','online/images/avatar/'+upload[0]);

				var username=$('.select_admin .active').text();
				$.ajax({
					type:'POST',
					url:'/modules/mw_chat/ajax/change_file.php',
					data:{'file':upload[0], 'username':username}
				})
				.done(function(data){
					$('.upload_img').html("<img src='online/images/avatar/"+data+"' >");
					$('.upload_img img, .chat_img').attr('src','online/images/avatar/'+data);
				});
				//$('.div_form').html('<div class="up_name but_ok" onclick="but_ok()" > OK </div>');
			}
			if(parseInt(upload[1])==0){
				alert(upload[0]);
			}

		});
	})

	// ---Add name---
	$('.but_add_name').click(function(){
		var pro=prompt('How are you name?');
		if(pro!=false){
			if(pro!=null){
				//alert('ok'+pro);
				$.ajax({
					method:'get',
					url:'modules/commodities/admin/online_ajax.php',
					data:{set_name:pro}
				})
				.done(function(dat){
					//alert(dat)
					location.reload();
				})
			}
		}else{
			alert('Please, you write here');
		}
	});

	$(".line_name").click(function(){
		var rr=$(this).attr('rel-in');
		$(".line_name").removeClass("ln_active");
		$(this).addClass("ln_active");

		$('.liuser').hide();
		$('.include'+rr).show();
	});
	$(".send_chat, .edit_tab").click(function(){
		$(this).attr("contenteditable","true");
	});
		
	$('.edit_tab').keyup(function(){
		var id=$('.select_admin .active').attr('rel-id');
		var write=$(this).text();
		var tab=$(this).attr('rel-tab');
		//alert(tab+", "+write);
		$.ajax({
			type:'POST',
			url:'/modules/mw_chat/ajax/edit.php',
			data:{id:id, tab:tab, write:write}
		});
	});

	$('.send_chat').keypress(function(event){
		if(event.which==13){
			sendChat();
		}
	});
	$('.but_send').click(function(){
		sendChat();
	});


		function sendChat(){
			var write=$('.send_chat').val();
			var img=$('.chat_img').attr('src');
			var txt='';
			txt+='<div class="admin_chat">';
			txt+='<div class="admin_chat_img"><img src="'+img+'" /></div>';
			txt+='<div class="admin_chat_read"><div>';
			txt+=write;
			txt+='</div></div></div>';
			if(write!=''){
				$('.read_chat').append(txt);
				$('.send_chat').val('');
			}
			//var bot=$('.chatbottom').offset().top-$('.read_chat').offset().top;
			//alert($('.read_chat').prop('scrollHeight'));
			$('.read_chat').scrollTop($('.read_chat').prop('scrollHeight'));

		}
		$('.traingle').click(function(){
			$('.body_chat').slideUp().remove();
		});

});
