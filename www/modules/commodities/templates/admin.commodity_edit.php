<?
$edit=<<<HERE
<link rel='stylesheet' type='text/css' media='all' href='/templates/{$theme_name}/css/uploadify.css' title='win2k-cold-1' />
<link rel='stylesheet' type='text/css' href='/templates/{$theme_name}/css/colorbox.css' />
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
'uploader' : '/modules/commodities/ajax/uploadify_commodities.php',
'cancelImg' : '/templates/{$theme_name}/images/uploadify-cancel.png', //Путь к файлу cancel.png
 //Путь к папке, в которой будут храниться загруженные файлы
'auto' : true, //Автоматически начинать загрузку (bool)
'buttonText' : '', //Текст кнопки загрузчика
'fileTypeExts' : '*.JPG;*.jpg;*.gif;*.png', // Допустимые форматы файлов
'multi' : true, //Разрешить загрузку нескольких файлов сразу
'width' : 150,
'onUploadSuccess' : function(file, data, response) {
           content=$('#photos_container').html()
		   $('#photos_container').html(content+data);
        },
formData : { 'com_id' : '{$commodityID}',
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
<form ENCTYPE='multipart/form-data' method='POST' action='{$request_url}' name='main_form' id='id_main_form'>
<script type='text/javascript' src='/templates/{$theme_name}/js/tabs.js'></script>
<script type='text/javascript' src='/templates/{$theme_name}/js/edit_com.js'></script>


<!-- ================================================================================================================================ -->
<table style='width:100%;'>
<tr>
<td>
<div class='section'>
	<ul class='tabs'>
		<li class='current'>Основные</li>
		<!--<li>Дополнительные фото</li>-->
		<li>Дополнительные поля</li>
		<!--<li>Рекомендуем</li>
		<li>Группа товаров</li>-->
		<li>SEO</li>
	</ul>
	<div class='box visible'>
	<img src='/templates/{$theme_name}/images/download.png' style='width:30px;float:right;cursor:pointer;' title='загрузить из интернета' onclick="jQuery('#id_frominternet').attr('value','1');func_apply();;">
	<input type='hidden' value='0' name='frominternet' id='id_frominternet'>
	<br/>Название товара:<br>
	<input type='text' name='name' value='{$e_name}' style='width:500px;' id='name' /><br>
	Источник:<br>
	<input type='text' name='from_url' value='{$from_url}' style='width:500px;' id='name' />
	<div id='butUrl'><a href='{$from_url}' target="_blank">Открыть ссылку в новой вкладке</a></div><br>
	<input type='hidden' name='com_id' value="{$commodityID}" style='width:500px;' class='com_id' />
	<br>
	<table class='cl_noborder'>
		<tr>
			<td>
				<!---new cats-->
				<div class='categ-prod-wrap' id='ctl00_ctl00_cphContent_cphCenter_divChooseCategory'>
				<script>var tree_init = {$categories['json_tree']}</script>
				<label>Категория</label>

					{$categories['lines']}
					<div style='display: none' id='add-category'>
						<div class='inp-categ-wrap inp-line'>
						<div class='categ-block-wrap'>
							<div class='inp-categ-block'>
							<span>Выберите категорию</span> <a class='select-arrow' href='javascript:;'></a>
												<input type='hidden' name='category[]' value='' class='cat_ids'>
							<div class='clear'>
							</div>
							</div>
						</div>
						<a class='remove-product-category remove-categ categ-navig png24' href='javascript:;'></a>
						<div class='clear'>
						</div>
						</div>
					</div>
					<input type='hidden' class='hfCategories' id='ctl00_ctl00_cphContent_cphCenter_hfCategories' name='ctl00$ctl00$cphContent$cphCenter$hfCategories'>
					</div>
				<!---new cats-->
			</td>

		</tr>
	</table><br>

	<table>
		<tr>
			<td>
				Код товара:<br>
				<input type='text' name='cod' value="{$cod}" style='width:150px;' />
			</td>
		</tr>
	</table>
	<table>
		<tr>
			<td>
				Цена на сайте:<br>
				<input type='text' name='price' value='{$e_price}'/>
			</td>
			<td>
				Старая цена:<br>
				<input type='text' name='commodity_old_price' value='{$commodity_old_price}'/>
			</td>
			<td>
				Оптовая цена:<br>
				<input type='text' name='price2' value='{$e_price2}'/>
			</td>
			<!--<td>
				Валюта:<br>
				<select name='cur_id'>{$currsss}</select>
			</td>-->
		</tr>
	</table>
	<br>Анотация (короткое описание):<br>
	<textarea name='short_text' id='short_text'>{$short_text}</textarea>
	<script type='text/javascript'>
	jQuery(document).ready(function() {
	CKEDITOR.replace('short_text', { language: 'ru' });
	});
	</script>

	<br>Полное описание товара:<br>
	<textarea name='full_text' id='full_text'>{$full_text}</textarea>
	<script type='text/javascript'>
	jQuery(document).ready(function() {
	CKEDITOR.replace('full_text', { language: 'ru' });
	});
	</script>

	</div>
	<!--<div class='box'>
		<div class="uploadifyQueue" id="photos_multiQueue"></div>
		<input id="file_upload" name="file_upload" type="file" />
		<div id="photos_container" class="photo_items_container photo_order photo_desc ui-sortable">
			{$commodities_images}
		</div>
	</div>	-->
	<div class='box'>
		<link rel='stylesheet' type='text/css' media='all' href='/includes/calendar/calendar-win2k-cold-1.css' title='win2k-cold-1' />
		<script type='text/javascript' src='/includes/calendar/calendar.js'></script>
		<script type='text/javascript' src='/includes/calendar/lang/calendar-rus.js'></script>
		<script type='text/javascript' src='/includes/calendar/calendar-setup.js'></script>
		<div class='cl_addition_fields'>
			{$filters}
		</div>
	</div>
	<!--<div  class='box'>
		Добавить из поиска:<br>
		<input type='text' name='addrec' value="" style='width:500px;' id='search' rel='{$commodityID}' /><br>
		<div class='cl_addition_div'>
			{$recommendedCommodities}
		</div>


	</div>
	<div  class='box'>
		Добавить из поиска:<br>
		<input type='text' name='addpur' value="" style='width:500px;' id='searchpur' rel='{$commodityID}' /><br>
		<div class='cl_purposes_div'>
			{$purposeCommodities}
		</div>

	</div>-->
	<div  class='box'>
		Адрес товара:<br>
		<input type='text' name='alias' value="{$alias}" style='width:500px;' id='alias' /><br>
		<input type='checkbox' name='use_alias' id='id_use_alias' value='1' {$use_alias_checked}>
		<label style='color:#777777;font-size:11px;' for='id_use_alias'>Генерировать автоматически</label><br><br>
		Title:<br>
		<input type='text' name='title' value='{$title}' style='width:500px;' />
		<br><br>Description:<br>
		<textarea name='description' style='width:90%;height:100px;'>{$seodescription}</textarea>

	</div>

	</div>

</div>
	</td>
	<td style='width:300px;'>
		<div class='cl_sidebar'>
			<h3>Изображение</h3>
			<div class='cl_edit_phoo'>
				{$img}<br />
				<input name='myfile1' type='file' >
				<br>
				Или URL<br />
				<input type=text' value='' name='imgurl' style='width:95%;'>
			</div>
			<h3>Атрибуты товара</h3>
			<table>
				<!--<tr>
					<td>
						Статус:
					</td>
					<td>
						<select name='status'>
							{$status_options}
						</select>
					</td>
				</tr>-->
				<tr>
					<td>
						Позиция:
					</td>
					<td>
						<input type='text' name='order' value='{$order}' style='width:50px;' />
					</td>
				</tr>
				<tr>
					<td>
						Опубликовать:
					</td>
					<td>
						<input name='visible' value='1' {$e_visible} type='checkbox'>
					</td>
				</tr>
				<tr>
					<td>
						<!--Лидер продаж:-->Слайдер ХП: 
					</td>
					<td>
						<input name='commodity_hit' value='1' {$commodity_hit} type='checkbox'>
					</td>
				</tr>
		<!--		<tr>
					<td>
						Супер цена:
					</td>
					<td>
						<input name='commodity_action' value='1' {$commodity_action} type='checkbox'>
					</td>
				</tr>
				<tr>
					<td>
						Новинка:
					</td>
					<td>
						<input name='commodity_new' value='1' {$commodity_new} type='checkbox'>
					</td>
				</tr>-->
			</table>		
		</div>
	</td>
	</table>
		<input type='hidden' name='add_commodity' value='ok' />
	</form>					
HERE;
?>