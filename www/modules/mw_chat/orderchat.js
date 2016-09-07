$(document).ready(function(){
	var socket = io.connect('http://zoond-test.cloudapp.net:8264');

	socket.on("mw_admin",function(msg){
		//alert('sennn: '+msg.message);
		var time=msg.date;
		time=time.split(' ');
		var txt='';

		var cidReal=$('.tb_active').attr("rel-cid");

		if(cidReal==("cid"+msg.cid)){

			if(msg.to!='admin'){
				txt+='<div class="admin_chat" rel-cid='+msg.cid+' >';
				txt+='<div class="admin_chat_img"><img id="imgfirst" src="/online/images/avatar/op_avatar_'+msg.from+'.jpg" /></div>';
				txt+='<div class="admin_chat_read">';
				txt+='<div id=\'putchat\'>';
				txt+=msg.message;
				txt+='<span>'+time[1]+'</span>';
				txt+='</div></div></div>';
			}
			if(msg.to=='admin'){
				var cid=msg.from.replace("cid","");
				txt+='<div class="client_chat" rel-cid='+cid+'><div class="client_chat_read" style="width: 100%;padding-right: 0px;"><div id="put">';
				txt+=msg.message;
				txt+='<span>'+time[1]+'</span>';
				txt+='</div></div>';
				txt+='<div class="client_chat_img">';
				txt+='</div></div>';



			}

		}
		if(msg.to=='admin'){
			if(parseInt($(".ln_active").attr('rel-in'))==2){
				var f=0;
				$(".liuser ul li").each(function(){
					var relcid=$(this).attr("rel-cid");
					if(relcid==msg.from){
								//alert('has ');
						var countsingal=parseInt($(this).find('.signal_num').text());
						if(isNaN(countsingal)){
							countsingal=1;
							$(this).append('<span class="signal_num">'+countsingal+'</span>');
						}else{
							countsingal+=1;
							$(this).find('.signal_num').text(countsingal);
						}
								
						f=1
					}
				});
				if(f==0){
					$(".include2 ul").prepend('<li rel-cid='+msg.from+'><i class=\"fa fa-user fauser\" ></i> '+msg.name+' <span class="signal_num">1</span></li>');
				}
			}
		}

		$('.read_chat').append(txt);
		$('.read_chat').scrollTop($('.read_chat').prop('scrollHeight'));
	});

	$('.icon_chat').click(function(){
		var rel=$(this).attr('rel');

		$(".liuser ul li").removeClass('tb_active');
		$("#uuser"+rel).addClass('tb_active');

		$('.body_chat').slideDown();
	});

	$('.traingle').click(function(){
		$('.body_chat').slideUp();
	});

	$(".c_head")
	.mousedown(function(){
		$(".body_chat").draggable({
			zIndex: 2700,
			containment: 'window',
			cursor:"move",
			snap:"main-width"
		});
	})
	.mouseup(function(){
		$(".body_chat").droppable();
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

		if(rr==parseInt(2) && $( ".include2 ul" ).has( "li" ).length==0){

			$(".include2").prepend("<div id='longing_client' ><i class=\"fa fa-spinner fa-pulse\"></i></div>");
			$.ajax({
				method:'POST',
				url:'/modules/mw_chat/ajax/chatclient.php',
				dataType:'json'
			})
			.done(function(data){
				// var status=[];
				// var signal=[];
				for(i=0; i<data.length; i++){
					// signal[data[i].name]=0;
					// status[data[i].name]=0;
					
					// if(data[i].status==1){
					//  	signal[data[i].name]++;
					// 	status[data[i].name]="<span class=\"signal_num\">"+signal[data[i].name]+"</span>";
					// }
					var cidd='';
					if(data[i].from.indexOf('cid')!=-1){
						cidd=data[i].from;
					}
					if(data[i].to.indexOf('cid')!=-1){
						cidd=data[i].to;
					}
					$(".include2 ul").append('<li rel-cid='+cidd+'><i class=\"fa fa-user fauser\" ></i> '+data[i].name+' '+'</li>');
				}

				$(".include2 #longing_client").remove();
				$.getScript("/modules/mw_chat/changeclient.js");
			});
		}
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

	$('.send_chat').click(function(){
		$('.tb_active .signal_num').remove();
	});
	$('.send_chat').keypress(function(event){
		// if(event.which==13){ //key: enter
		// 	sendChat();
		// }
		// var isCtrl;
		if(event.ctrlKey){ // key ctrl+enter
			//sendChat(socket);
			//alert("sent")
			sendClient(socket);
		}
	});
	$(".but_send").click(function(){
		//sendChat(socket);
		//alert("sent")
		sendClient(socket);
	});

	function sendClient(socket){
		var operaname=$(".select_admin .active").text();
		var name=$('.liuser ul .tb_active').text();
		name=name.replace( /[0-9№/]/g ,"");
		name=$.trim(name);

		var cidReal=$('.tb_active').attr("rel-cid");
		var cid=cidReal.replace("cid","");


		var write=$('.send_chat').val();
		write=write.replace(/\n/g,"<br/>");

		var time=new Date();
		var minut=time.getMinutes();
		if(minut<10){
			minut='0'+minut;
		}
		var month=time.getMonth();
		if(month<10){
			month="0"+month;
		}

		var nowTime= time.getDay()+"."+month+"."+time.getFullYear()+" "+time.getHours()+":"+minut;


		if(write==''){
			alert("Пожалуйста, заполните текст сообщения");
		}else{
			socket.emit("mw_server", { cid:cid, name:name, from:operaname, to:cidReal, message:write, date:nowTime });
			$.ajax({
				method:'GET',
				url:'/online/ajax/write.php',
				data:{ cid:cid, name:name, from:operaname, to:cidReal, message:write, date:nowTime }
			});
			$('.send_chat').val("");
			$('.tb_active .signal_num').remove();
		}
	}

});