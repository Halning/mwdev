


	function createCookie(name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	}

	function readCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}
	function update(com_id,color_id,size_id)
{
	var url=location.host;
	var st=0;
	
	if(document.getElementById('lab-'+com_id+'-'+color_id+'-'+size_id).checked)
	{
		st=1;
	}
	var uurl='http://'+url+'/ajax.php?com_id='+com_id+'&color_id='+color_id+'&size_id='+size_id+'&st='+st+'&callback=?';
	jQuery.getJSON(uurl, {}, function(type_text)
		{
			
		});   	
		
}
	function gotospec (step)
	{
		history.go(-step);
	}

	function go_to_page(page_url)
	{
		top.location.href=page_url;
	}

	function func_save()
	{
		createCookie('spec',2);
		if (document.getElementById('my_textarea_id')){XBB.form_submit();}
		document.forms.main_form.submit();
	}

	function func_apply()
	{
		createCookie('spec',1);
		if (document.getElementById('my_textarea_id')){XBB.form_submit();}
		document.forms.main_form.submit();
	}

	function response_check()
	{

		var warn_text;
		warn_text=0;
		jQuery('#form_name_warn').html('');
		jQuery('#form_city_warn').html('');
		jQuery('#form_email_warn').html('');
		jQuery('#form_text_warn').html('');
		jQuery('#form_code_warn').html('');

		if (jQuery('#form_name').attr('value')=='')
		{
			warn_text=1;
			jQuery('#form_name_warn').html('Введите имя');
		}

		if (jQuery('#form_city').attr('value')=='')
		{
			warn_text=1;
			jQuery('#form_city_warn').html('Введите город');
		}

		if (jQuery('#form_email').attr('value')=='')
		{
			warn_text=1;
			jQuery('#form_email_warn').html('Введите Ваш e-mail');
		}else
		{
			value=jQuery('#form_email').attr('value');
			reg = /[a-z0-9!#jQuery%&'*+/=?^_`{|}~-]+(?:.[a-z0-9!#jQuery%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
			if (!value.match(reg))
			{
				warn_text=1;
				jQuery('#form_email_warn').html('Введите корректный e-mail');
			}
		}

		if ( jQuery('#form_text').attr('value')=='')
		{
			warn_text=1;
			jQuery('#form_text_warn').html('Введите текст');
		}
		if ( jQuery('#form_code').attr('value')=='')
		{
			warn_text=1;
			jQuery('#form_code_warn').html('Введите защитный код');
		}

		if(warn_text==0)
		{
			document.forms.response_form.submit();
		}


	}



var ru2en = {
  ru_str : "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя :«»'?@$%(),/+\\\"",
  en_str : ['a','b','v','g','d','e','jo','zh','z','i','j','k','l','m','n','o','p','r','s','t',
    'u','f','h','c','ch','sh','shh','','y','','je','ju',
    'ja','a','b','v','g','d','e','jo','zh','z','i','j','k','l','m','n','o','p','r','s','t','u','f',
    'h','c','ch','sh','shh','','y','','je','ju','ja','-','-','-','-','-','-','-','-','-','-','-','-','-','','-',''],
  translit : function(org_str) {
    var tmp_str = "";
    for(var i = 0, l = org_str.length; i < l; i++) {
      var s = org_str.charAt(i), n = this.ru_str.indexOf(s);
      if(n >= 0) { tmp_str += this.en_str[n]; }
      else { tmp_str += s; }
    }
    return tmp_str.toLowerCase();
  }
}

var ru2ru = {
  ru_str : "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ :«»'?@$%,+/+\\\"",
  en_str : ['а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т',
    'у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю',
    'я','-','-','-','-','-','-','-','-','-','-','','-','-','-',''],
  translit : function(org_str) {
    var tmp_str = "";
    for(var i = 0, l = org_str.length; i < l; i++) {
      var s = org_str.charAt(i), n = this.ru_str.indexOf(s);
      if(n >= 0) { tmp_str += this.en_str[n]; }
      else { tmp_str += s; }
    }
   return tmp_str.toLowerCase();
  }
}

function trans_ru2en()
{

	{
		var foralias=document.getElementById('name').value;
		document.getElementById('alias').value=ru2en.translit(foralias);
	}
}

function trans_ru2ru()
{

	{
		var foralias=document.getElementById('name').value;
		var rutext=ru2ru.translit(foralias);
		document.getElementById('alias').value=rutext;
		
		
	}
}


function lng_select(url)
{
	var objSel = document.getElementById("lng_selecting").value;
	var url2=location.host;
	var url3="http://"+url2+url;

	jQuery.getJSON('http://'+url2+'/includes/ajax/adminlng.php?sel_lang='+objSel+'&callback=?', {}, function(booking)
	{
		 go_to_page('http://'+url2+url);
	});
}

function replaceAll(find, replace, str) {
  return str.replace(new RegExp(find, 'g'), replace);
}
	
	function chacge_status_con(id,st)
	{
		jQuery("#id_st_id").attr('value',id);
		jQuery("#id_st_st").attr('value',st);
		jQuery('#id_change_st').submit();
	}
	var rell,rell2,childrel,childrel2;

		function close_child(childrel,childrel2)
		{
			if(childrel2=="open")
			{
				jQuery('#id_i'+childrel).attr('rel2','closed');	
				jQuery('#id_i'+childrel).attr('src','/templates/admin/images/folderopen.png');	
			
			
				jQuery('.cl_dd_'+childrel+' .cl_first img').each(function()
				{
					lchildrel=jQuery(this).attr('rel');
					lchildrel2=jQuery(this).attr('rel2');			
					close_child(lchildrel,lchildrel2);
				});  
			}	
				
			
			jQuery('.cl_dd_'+childrel+' td').hide();
		}
		
	jQuery(document).ready(function()
	{	
		
		var maxLength;
		var curLength;
		var remaning;
		var relfield;
		var ttt;
		var com_id;
		var rrell;
		
		var maxLength = $('#textarea').attr('maxlength');        //(1)
	
		if($("#search").length) {
			var auto=jQuery( "#search" ).autocomplete({
			source: "/modules/commodities/ajax/ajax_search2.php",
			minLength: 2
				
			}).data( "autocomplete" )._renderItem = function( ul, item ) {
					var img=(item.image)?'<div class="image"><img src="' + item.image + '"></div>':'';
					var inner_html = '<a href="'+item.url+'"><div class="list_item_container">'+img+'<div class="label">' + item.label + '</div></div></a>';
			return jQuery( "<li class='choose-rec'>" )
				.append( inner_html )
				.appendTo( ul );
			};
		}
	
		if($("#searchpur").length) {
			var auto=jQuery( "#searchpur" ).autocomplete({
			source: "/modules/commodities/ajax/ajax_search2.php",
			minLength: 2
				
			}).data( "autocomplete" )._renderItem = function( ul, item ) {
					var img=(item.image)?'<div class="image"><img src="' + item.image + '"></div>':'';
					var inner_html = '<a href="'+item.url+'"><div class="list_item_container">'+img+'<div class="label">' + item.label + '</div></div></a>';
			return jQuery( "<li class='choose-purpose'>" )
				.append( inner_html )
				.appendTo( ul );
			};
		}
	
		
		jQuery('.cl_dir').each(function()
		{
			rrell=jQuery(this).attr('rel');
			if(readCookie(rrell)==1)
			{
				jQuery(rrell).children('td').show();
				if(jQuery(this).children('td').children('.cl_folder_img').attr('rel2')=="closed")
				{
					jQuery(this).children('td').children('.cl_folder_img').attr('rel2','open');
					jQuery(this).children('td').children('.cl_folder_img').attr('src','/templates/admin/images/folder2.png');	
				}
				
			}
		}); 
		
		$('#textarea').keyup(function()
		{
			var curLength = $('#textarea').val().length;         //(2)
			$(this).val($(this).val().substr(0, maxLength));     //(3)
			var remaning = maxLength - curLength;
			if (remaning < 0) remaning = 0;
			$('#textareaFeedback').html(remaning + ' осталось символов'); //(4)
			if (remaning < 10)                                           //(5)
			{
			$('#textareaFeedback').addClass('warning')
			}
			else
			{
			$('#textareaFeedback').removeClass('warning')
			}
		});
		
		jQuery('.cl_folder_img').click(function()
		{
			rell=jQuery(this).attr('rel');
			rell2=jQuery(this).attr('rel2');
			if(rell2=="closed")
			{
				jQuery('.cl_dd_'+rell+' td').show();
				createCookie('.cl_dd_'+rell,1);
				jQuery(this).attr('rel2','open');
				jQuery(this).attr('src','/templates/admin/images/folder2.png');
				
				
			}else
			if(rell2=="open")
			{
				close_child(rell,rell2);
				createCookie('.cl_dd_'+rell,0);
			}
			
			
		});

		jQuery('.cl_for_imit').click(function()
		{
			rell=jQuery(this).attr('rel');
			jQuery('#'+rell).click();

		});
		
		jQuery(document).on('click','.notshow', function(){
			ttt="";
			nid="id_"+jQuery(this).attr("id");
			rel=jQuery(this).attr("rel");
			jQuery("#"+nid).show();
		
			CKEDITOR.replace('short_text'+rel, { language: 'ru' });
		});
				
		jQuery(document).on('click','.jstree-no-icons a', function(){
			ttt="";
			jQuery('.cat_ids').each(function()
			{	
				if(jQuery(this).attr('value')!="")
				{
					ttt=ttt+jQuery(this).attr('value')+',';
				}
				
			});  
			com_id=jQuery('.com_id').attr('value');
			console.log('/includes/ajax/admin_getfield.php?ids='+ttt+'&com_id='+com_id+'&callback=?');
			jQuery.getJSON('/includes/ajax/admin_getfield.php?ids='+ttt+'&com_id='+com_id+'&callback=?', {}, function(resr)
			{
				jQuery('.cl_addition_fields').html(resr.textr);
			});
			
		});
		
		jQuery(document).on('click','.choose-rec .ui-corner-all', function(){
			ttt=jQuery(this).attr("href");
			firsid=jQuery("#search").attr("rel");
			jQuery(".ui-autocomplete").hide();
		
			//com_id=jQuery('.com_id').attr('value');
			
			jQuery.getJSON('/modules/commodities/ajax/site.php?add_rec=1&ttt='+ttt+'&firsid='+firsid+'&callback=?', {}, function(resr)
			{
				jQuery('.cl_addition_div').html(resr.res);
			});
			
		});
		
		
		jQuery(document).on('click','.choose-purpose .ui-corner-all', function(){
			ttt=jQuery(this).attr("href");
			firsid=jQuery("#searchpur").attr("rel");
			jQuery(".ui-autocomplete").hide();
		
			//	com_id=jQuery('.com_id').attr('value');
			
			jQuery.getJSON('/modules/commodities/ajax/site.php?add_pur=1&ttt='+ttt+'&firsid='+firsid+'&callback=?', {}, function(resr)
			{
				jQuery('.cl_purposes_div').html(resr.res);
			});
			
		});
		
		
		jQuery(document).on('click','.rec-i .cl_t_del', function(){
			ttt=jQuery(this).attr("rel");
			firsid=jQuery("#search").attr("rel");
			jQuery(".ui-autocomplete").hide();
		
			com_id=jQuery('.com_id').attr('value');
			
			jQuery.getJSON('/modules/commodities/ajax/site.php?del_rec=1&ttt='+ttt+'&firsid='+firsid+'&callback=?', {}, function(resr)
			{
				jQuery('.cl_addition_div').html(resr.res);
			});
			
		});
		
		jQuery(document).on('click','.pur-i .cl_t_del', function(){
			ttt=jQuery(this).attr("rel");
			firsid=jQuery("#search").attr("rel");
			jQuery(".ui-autocomplete").hide();
		
			com_id=jQuery('.com_id').attr('value');
			$(this).parents('.cl_rec_img').detach();
			
			jQuery.getJSON('/modules/commodities/ajax/site.php?del_pur=1&ttt='+ttt+'&firsid='+firsid+'&callback=?', {}, function(resr)
			{
				jQuery('.cl_addition_div').html(resr.res);
			});
			
		});		
		

		jQuery('#name').keyup(function()
		{
			if(jQuery("#id_use_alias").attr("checked")=="checked")
			{
				trans_ru2ru();
			}

		});
		
		jQuery('.cl_textarea').keyup(function()

		{
			maxLength = jQuery(this).attr('maxlength'); 
			curLength = jQuery(this).val().length;         //(2)

			jQuery(this).val(jQuery(this).val().substr(0, maxLength));     //(3)

			remaning = maxLength - curLength;
			relfield=jQuery(this).attr('rel'); 
			if (remaning < 0) remaning = 0;
			
			jQuery('#'+relfield).html(remaning + ' осталось символов');//(4)

			if (remaning < 10)          //(5)

			{

			jQuery('#'+relfield).addClass('cl_red')

			}
			else
			{
				jQuery('#textareaFeedback').removeClass('cl_red')
			}
		});
	
		jQuery( ".cl_edit2" ).dblclick(function() 
		{
			jQuery(this).children("span").hide();
			jQuery(this).children("select").show();
			

			
		});
		
		jQuery(document).on('change','.cl_edit2 select', function(){
			var texta = jQuery(this).attr('value');	
			var textid = jQuery(this).attr('id');	
			var textb = jQuery("#"+textid+' :selected').text();			
			var qtable = jQuery(this).parent('td').parent('tr').attr('rel');
			var qtableid = jQuery(this).parent('td').parent('tr').attr('rel2');
			var qrowid = jQuery(this).parent('td').parent('tr').attr('id');
			var qfield = jQuery(this).parent('td').attr('id');
			texta=texta.replaceAll('#','|-|',texta);
			jQuery.getJSON('/includes/ajax/admin_editfield.php?table='+qtable+'&id='+qrowid+'&field='+qfield+'&idfield='+qtableid+'&text='+texta+'&callback=?', {}, function(booking)
			{
					
					
			});
			textb=textb.replaceAll('|-|','#',textb);
			jQuery(this).parent('td').children('span').html(textb);
			jQuery(this).parent('td').children('select').hide();
			
			jQuery(this).parent('td').children('span').show();
			location.reload();

		});
				
		jQuery( ".cl_edit" ).click(function() {
			if(jQuery(this).find('textarea').length == 0)
			{
				var thifiledvalue=jQuery(this).html();
				thifiledvalue=replaceAll('	','',thifiledvalue);
				thifiledvalue=thifiledvalue.replace(/[\r\n]/g, '');
				jQuery(this).html('<textarea>'+thifiledvalue+'</textarea>');
				jQuery(this).children('textarea').focus();
			}

			
		});	

		jQuery( ".cl_edit3" ).click(function() {
			if(jQuery(this).find('textarea').length == 0)
			{
				var thifiledvalue=jQuery(this).html();
				thifiledvalue=replaceAll('	','',thifiledvalue);
				thifiledvalue=thifiledvalue.replace(/[\r\n]/g, '');
				jQuery(this).html('<textarea>'+thifiledvalue+'</textarea>');
				jQuery(this).children('textarea').focus();
			}

			
		});		
		
		jQuery(document).on('blur','.cl_edit textarea', function(){
			var texta = jQuery(this).val();
			var qtable = jQuery(this).parent('td').parent('tr').attr('rel');
			var qtableid = jQuery(this).parent('td').parent('tr').attr('rel2');
			var qrowid = jQuery(this).parent('td').parent('tr').attr('id');
			var qfield = jQuery(this).parent('td').attr('id');
			texta=texta.replaceAll('+','%2B',texta);
			texta=texta.replaceAll('&','%26',texta);
			texta=texta.replaceAll('#','|-|',texta);
			var str='/includes/ajax/admin_editfield.php?table='+qtable+'&id='+qrowid+'&field='+qfield+'&idfield='+qtableid+'&text='+texta+'&callback=?';
			jQuery.getJSON(str, {}, function(booking){});
			texta=texta.replaceAll('%2B','+',texta);
			texta=texta.replaceAll('%26','&',texta);
			texta=texta.replaceAll('|-|','#',texta);
			jQuery(this).parent('td').html(texta);
		});
		
		jQuery(document).on('blur','.cl_edit3 textarea', function(){
			var texta = jQuery(this).val();
			var qtable = jQuery(this).parent('td').parent('tr').attr('rel');
			var qtableid = jQuery(this).parent('td').parent('tr').attr('rel2');
			var qrowid = jQuery(this).parent('td').parent('tr').attr('id');
			var qfield = jQuery(this).parent('td').attr('id');
			texta=texta.replaceAll('+','%2B',texta);
			texta=texta.replaceAll('&','%26',texta);
			var str='/includes/ajax/admin_editfield.php?table='+qtable+'&id='+qrowid+'&field='+qfield+'&idfield='+qtableid+'&text='+texta+'&callback=?';
			jQuery.getJSON(str, {}, function(booking){});
			texta=texta.replaceAll('%2B','+',texta);
			texta=texta.replaceAll('%26','&',texta);
			jQuery(this).parent('td').html(texta);
			location.reload();
		});
		
		

})	
