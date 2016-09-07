<?
$edit=<<<HERE
<script type="text/javascript">
$(document).ready(function() {
$(".photo_items_container").delegate("a.colorbox:visible", "click", function(event){
				event.preventDefault();
				 $(this).closest(".photo_items_container").find("a.colorbox:visible").colorbox({"transition":"none","current":"","scrolling":false,"opacity":0,"loop":false,"maxWidth":"100%","maxHeight":"100%","initialWidth":"300","initialHeight":"300","rel":"photos_group"});
			});
});

$(function(){

$('#file_upload').uploadify({

'swf' : '/includes/flash/uploadify.swf', //Путь к файлу uploadify.swf
'uploader' : '/modules/content/ajax/uploadify_articles.php',


'cancelImg' : '/templates/{$theme_name}/images/uploadify-cancel.png', //Путь к файлу cancel.png

 //Путь к папке, в которой будут храниться загруженные файлы

'auto' : true, //Автоматически начинать загрузку (bool)

'buttonText' : '', //Текст кнопки загрузчика

'fileTypeExts' : '*.jpg;*.gif;*.png', // Допустимые форматы файлов

'multi' : true, //Разрешить загрузку нескольких файлов сразу
'width' : 150,
'onUploadSuccess' : function(file, data, response) {
           content=$('#photos_container').html()
		   $('#photos_container').html(content+data);
        },
formData : { 'articleID' : '{$articleID}',
			 'ses_id' : '{$ses_id}'},
'onerror' : function (event,ID,fileObj,errorObj) {

alert(errorObj.type + ' Ошибка: ' + errorObj.info);

}

});

});
$(function() {

	$("#photos_container").sortable({
		start : function(event, ui) {
			ui.item.addClass('active');
		},
		stop : function(event, ui) {
			ui.item.removeClass('active').effect("highlight", { color: '#000' }, 1000, function() {
				var i=0;
				$.each($('#photos_container div.photo_item'), function() {
					$(this).children('.order').val(++i);
				});
			});			
		}
	});
	//$("#photos_container").disableSelection();
	
});


function del_photo(id){
	$("#photo_item-"+id+" .delete").val('1');
	$("#photo_item-"+id).hide();
}
</script>
<script type='text/javascript' src='/templates/{$theme_name}/js/tabs.js'></script>
<script type='text/javascript' src='/templates/{$theme_name}/js/edit_com.js'></script>


<form ENCTYPE='multipart/form-data' method='POST' action='{$request_url}' name='main_form'>




<table style='width:100%;'>
<tr>
<td>
	<div class='section'>
	<ul class='tabs'>
		<li class='current'>Основные</li>
		<li>Дополнительные фото</li>
		<li>Дополнительные поля</li>
		<li>SEO</li>
	</ul>
	<div class='box visible'>
		Название страницы:<br />
		<input type='text' name='name' value='{$name}' style='width:500px;' id='name' />
		<br /><br />
		Адрес страницы: <a id='id_real_url' href='http://{$ass}/{$alias}/' rel='http://{$ass}/' target='_blank' style='font-size:11px;'>http://{$ass}/{$alias}/</a><br />
		<input type='text' name='alias' value='{$alias}' style='width:500px;' id='alias' /><br /> 
		<input type='checkbox' name='use_alias' id='id_use_alias' value='1' {$use_alias_checked}><label style='color:#777777;font-size:11px;' for='id_use_alias'>Генерировать автоматически</label>
		<br /><br />
		Родительская (раздел):
		<div class='categ-prod-wrap' id='ctl00_ctl00_cphContent_cphCenter_divChooseCategory'>
		<script>var tree_init = {$categories['json_tree']}</script>
		{$categories['lines']}		
		<div style='display: none' id='add-category'>
			<div class='inp-categ-wrap inp-line'>
				<div class='categ-block-wrap'>
					<div class='inp-categ-block'>
					<span>Выберите категорию</span> <a class='select-arrow' href='javascript:;'></a>
					<input type='hidden' name='category[]' value='' class='cat_ids'>
					<div class='clear'></div>
					</div>
				</div>
				<a class='remove-product-category remove-categ categ-navig png24' href='javascript:;'></a>
				<div class='clear'></div>
			</div>
		</div>
		<input type='hidden' class='hfCategories' id='ctl00_ctl00_cphContent_cphCenter_hfCategories' name='ctl00$ctl00$cphContent$cphCenter$hfCategories'>
		<br /><br />
		<br />Текст страницы:<br />
		<textarea name='text' id='text'>{$text}</textarea>
		<script type='text/javascript'>
		jQuery(document).ready(function() {
		CKEDITOR.replace('text', {  height : '450',
       
            language : 'ru' });
		});
		</script>
		
		
		
		<input name='add_articles' value='true' type='hidden'>

	

		
		</div>
	</div>
	<div class='box'>
		
		<div class="uploadifyQueue" id="photos_multiQueue"></div>
		<input id="file_upload" name="file_upload" type="file" />
		<div id="photos_container" class="photo_items_container photo_order photo_desc ui-sortable">
		{$articles_images}
		</div>
	</div>
	<div class='box'>
	{$dop_fields}
	</div>
	<div class='box'>
		H1: <span id='textareaFeedback0'></span><br /><br />
		<textarea class='cl_textarea' rel='textareaFeedback0' maxlength='70' type='text' name='h1' = style='width:100%;'>{$h1}</textarea>
		
		<br /><br />Title: <span id='textareaFeedback1'></span><br /><br />
		<textarea class='cl_textarea' rel='textareaFeedback1' maxlength='70' type='text' name='title'  style='width:100%;'>{$title}</textarea>
		
		<br /><br />Description: <span id='textareaFeedback2'></span><br /><br />
		<textarea class='cl_textarea' rel='textareaFeedback2' maxlength='150' name='description' style='width:100%;height:40px;'>{$description}</textarea>
		 <!--
		<br /><br />Content: <span id='textareaFeedback4'></span><br />
		<textarea class='cl_textarea' rel='textareaFeedback4' maxlength='150' name='content' style='width:100%;height:40px;'>{$content}</textarea>
		-->
	</div>



</div>
</td>
<td style='width:300px;'>

		
<div class='cl_sidebar'>
		<h3>Изображение</h3>
			<div class='cl_edit_phoo'>
				
				{$img}<br />

				<input name='myfile1' type='file' >
			</div>
		<h3>Атрибуты страницы</h3>
		<table>
		<tr>
			<td>
				Дата:
			</td>
			<td>
				<link rel='stylesheet' type='text/css' media='all' href='/includes/calendar/calendar-win2k-cold-1.css' title='win2k-cold-1' />
				<script type='text/javascript' src='/includes/calendar/calendar.js'></script>
				<script type='text/javascript' src='/includes/calendar/lang/calendar-rus.js'></script>
				<script type='text/javascript' src='/includes/calendar/calendar-setup.js'></script>
				<input type='text' name='add_date' value='{$add_date}' id='f_date_c' readonly='1' style='width:65px!important;' />
				<img src='/templates/{$theme_name}/img/cal.gif' width='16' border='0' height='16' id='f_trigger_c'onmouseover='this.style.background='red';' onmouseout='this.style.background=''' />
				<script type='text/javascript'>
				Calendar.setup({
						inputField     :    'f_date_c',     // id of the input field
						ifFormat       :    '%Y-%m-%d %k:%M:%S',      // format of the input field
						button         :    'f_trigger_c',  // trigger for the calendar (button ID)
						align          :    'Tl',           // alignment (defaults to 'Bl')
						singleClick    :    true
				});
				</script>
			</td>
		</tr>
		<tr>
			<td>
				Позиция:
			</td>
			<td>
				<input type='text' name='order' value='{$order}' style='width:50px;'/>
			</td>
		</tr>
		<tr>
			<td>
				Опубликовать:
			</td>
			<td>
				<input type='checkbox' name='visible' value='1' {$checkbox_visible} />
			</td>
		</tr>
		<tr>
			<td>
				В меню:
			</td>
			<td>
				<input type='checkbox' name='menu' value='1' {$checkbox_menu} />
			</td>
		</tr>
		<tr>
			<td>
				Блок:
			</td>
			<td>
				<input type='checkbox' name='block' value='1' {$checkbox_block} />
			</td>
		</tr>
		</table>
		<h3>Тип страницы</h3>
		<select name='type_id' onchange='func_apply();'>{$type_category}</select>
		<br /><br />
			
		</div>
</td>
</table>
</form>

HERE;
?>