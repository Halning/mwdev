

$(document).ready(function(){
		var clear='';
		var flag=0;

			$('.close_chat').click(function(){
				$('.body_chat').slideUp();
				clearInterval(clear);
				flag=0;
			});
			$(".title_chat").mousedown(function(){
				$(this).css({"cursor":"move"});
			}).mouseup(function(){
				$(this).css({"cursor":"pointer"});
			});
			$('.icon-3').click(function(){
				ajax_read();
				$('.body_chat').slideToggle()
				.draggable({ 
					scroll: true, 
					scrollSpeed: 500,
					containment: "window",
					cursor: 'move',
					snap: ".main-width"
				});
				if(flag==0){
					clear=setInterval(function(){ajax_read();},5000);
					flag=1;
				}else{
					clearInterval(clear);
					flag=0;
				}
				var nn=$("#username").text();
				if(nn!=""){
					$(".name_chat").val(nn);
				}
			});
			$('.name_chat').click(function(){
				$(this).val("");
				$(this).css({color:'black'});
			});
			$('.write_txt').click(function(){
				if("Введите текст сообщения..."==$(this).val()){
					$(this).val("");
				}
				$(this).css({color:"black"});
			});
			$(".write_txt").keypress(function(e){
				//alert("ddd"+e.which);
				if(e.which==13 && e.ctrlKey){
					$(this).val($(this).val()+"\n");
					return false;
				}else if(e.which==13){
					send_chat();
					return false;
				}
			});
			$('.but_send').click(function(){
				send_chat();
			});

			$.get('http://'+window.location.hostname+'/modules/online/id.php')
			.done(function(sd){
				$('.body_chat').attr('rel',"ID_"+sd);
			}); 
			ajax_read();
});

function ajax_read(){
	var user=$('.body_chat').attr('rel');
	var adm=$('.cli_c').attr('rel');
	$.getJSON('http://'+window.location.hostname+'/modules/commodities/ajax/online_ajax.php',
		{stat_r:true, admin:adm, us:user})
	.done(function(da){

		//TODO(Вова) закоментував це, бо викидає помилку!!!
		//readd(da);
	});
}

function readd(da){ 
	$(document).ready(function(){ 
		var txt='';
		var user='';
		var chat2='';
		var add_img='';
		$.getJSON('http://www.makewear.com.ua/modules/commodities/admin/online_ajax.php',{operator:true})
		.done(function(da){
			chat2+='<tr><td class=left_a ><img src=/templates/admin/images/avatar.jpg class=avatar /></td><td class=rig_a ><span class=cli_c >Оператор1 '+da['op_name']+'</span><span class=date_c ></span><br/>';
			chat2+='Здравствуйте! Задайте мне вопрос и я подскажу Вам наилучшее решение.';
			chat2+='</td></tr>';						
		});
			chat2+='<tr><td class=left_a ><img src=\'../../modules/online/images/avatar/op_avatar_'+da[0]['operator_name']+'.jpg\' class=avatar /></td><td class=rig_a ><span class=cli_c rel='+da[0]['operator_name']+' >Оператор '+da[0]['operator_name']+'</span><span class=date_c ></span><br/>';
			//chat2+='Здравствуйте! Задайте мне вопрос и я подскажу Вам наилучшее решение.';
			chat2+=da[0]['operator_text'];
			chat2+='</td></tr>';

		txt+=chat2;
		if(da[0]['st']!=1)
		for(i=0; i<da.length; i++){
			if(da[i]['user'].indexOf('ID')){
				user='Оператор '+da[i]['user'];
				add_img=src='src=\'../../modules/online/images/avatar/op_avatar_'+da[i]['user']+'.jpg\'';
			}else{
				user=da[i]['name'];
				$('.name_chat').val(user);
				add_img=src='src=\'../../modules/online/images/avatar.jpg\'';
			}
			txt+='<tr><td class=left_a ><img '+add_img+' class=avatar /></td><td class=rig_a ><span class=cli_c >'+user+'</span><span class=date_c >'+da[i]['date']+'</span><br/>'+da[i]['message']+'</td></tr>';
		}

		var txt2='<table class=tab_on >'+txt+'</table>';
		$('.read_chat').html(txt2);
	});
}

function send_chat(){
	var admin=$('.body_chat').attr('rel');
				var user=$('.cli_c').attr('rel');
				var user2=user;
				var txt=$('.write_txt').val();
				var txtt=$('.read_chat').html();
				var txt22='';
				var link=location.href;
				var name=$('.name_chat').val(); 
				if(txt=='Введите текст сообщения...' || txt=='' || name=='Ваше имя' || name==''){
					if(name=='Ваше имя' || name==''){
						alert("Пожалуйста, заполните ваше имя");
					}
					if(txt=='Введите текст сообщения...' || txt==''){
						alert("Пожалуйста, заполните текст сообщения");
					}
				}else{
					$.getJSON('http://'+window.location.hostname+'/modules/commodities/ajax/online_ajax.php',
						{stat_w:true, name:name, admin:admin, local:link, us:user, msg:txt, status:'1'})
					.done(function(da){
						
						if(da['user']=='admin'){
							user='Оператор '+da['operator_name'];
						}else{
							user=da['user'];
							if(user.indexOf("ID")>-1){
								user='';
							}
						}
						txt22+='<tr><td class=left_a ><img src=/templates/admin/images/avatar.jpg class=avatar /></td><td class=rig_a ><span class=cli_c >'+user+'</span><span class=date_c >'+da['date']+'</span><br/>'+da['message']+'</td></tr>';	
						var txt2='<table class=tab_on >'+txt22+'</table>';
						txtt+=txt2;
						$('.read_chat').html(txtt);
					});
					$('.write_txt').val("");
				}

}

$('.icon-3').click(function () {
// запрашиваем имя пользователя
if (name === "") {
name = prompt("Введите ваше имя:", "Гость");
if (!name || name === ' ') {
name = "Гость";
}
name = name.replace(/(<([^>]+)>)/ig, "");
}
setInterval(function () {
//updateChat();
}, 1000);
$('.body_chat').slideDown(500).resizable().draggable({
containment: "window",
cursor: 'move',
snap: ".main-width"
});
$('.body_chat').resizable().position({
my: "right center", // место на позиционируемом элементе
at: "right bottom", // место на элементе относительно которого будет позиционирование
of: '.title' // элемент относительно которого будет позиционирование
});
$('.read_chat').append("<div class='massIt'><p>Алиса : Здравствуйте, " + name + " чем я могу вам помочь?</p></div>");
$(function () {
$('.body_chat').resizable({
maxHeight: 335,
maxWidth: 470,
minHeight: 335,
minWidth: 350
}).css({position: "fixed"});
});

});
$('.close_chat').click(function () {
// $('.body_chat').slideUp();
$('.body_chat').effect('explode', {times: 3}, 500);
});
$('.addMassage').click(function () {
var toAdd = $("textarea[name=message]").val();

$('.read_chat').append("<div class='massUser'><p>Вы : " + toAdd + "</p></div>");
$("textarea[name=message]").val("");
});
$('.addMassage').hover(function () {
$(this).css({border: "outset"});
}, function () {
$(this).css({border: "inset"});
});

// отслеживаем ввод текста в окне чата
$("#sendie").keydown(function () {
$(".writeImg").animate({opacity: 1}, 500).animate({opacity: 0}, 100);
});