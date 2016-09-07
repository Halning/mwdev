<?php
$edit="
<script type='text/javascript' src='/templates/{$theme_name}/js/tabs.js'></script>
<script type='text/javascript' src='/templates/{$theme_name}/js/edit_com.js'></script>
<form ENCTYPE='multipart/form-data' method='POST' action='{$request_url}' name='main_form'>
<style>
.add-categ{
display:none;
}
</style>
<table style='width:100%;'>
<tr>
<td>
	<div class='section'>
		<ul class='tabs'>
			<li class='current'>Основные</li>
			<li>SEO</li>
		</ul>
		<div class='box visible'>
		
			
		
				<!---new cats-->
				<div class='categ-prod-wrap' id='ctl00_ctl00_cphContent_cphCenter_divChooseCategory'>
				<script>var tree_init = {$categories['json_tree']}</script>
				<label>Родительская категория:</label>	
					
					{$categories['lines']}	
					<div style='display: none' id='add-category'>
						<div class='inp-categ-wrap inp-line'>
						<div class='categ-block-wrap'>
							<div class='inp-categ-block'>
							<span>Корневая категория</span> <a class='select-arrow' href='javascript:;'></a>
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
		
			<br><br><br>
			Название категории:<br>
			<input type='text' name='name' value='{$e_name}' style='width:500px;' id='name' />
			<br><br>Описание:<br>
			<textarea name='cat_desc' id='cat_desc'>{$cat_desc}</textarea>
			<script type='text/javascript'>
			jQuery(document).ready(function() {
			CKEDITOR.replace('cat_desc', { language: 'ru' });
			});
			</script>
		</div>
		<div  class='box'>
			Адрес категории:<br>
			<input type='text' name='alias' value='{$alias}' style='width:500px;' id='alias' /><br>
			<input type='checkbox' name='use_alias' id='id_use_alias' value='1' {$use_alias_checked}>
			<label style='color:#777777;font-size:11px;' for='id_use_alias'>Генерировать автоматически</label><br><br>
			H1:<br>
			<input type='text' name='h1' value='{$h1}' style='width:500px;' /><br><br>
			Title:<br>
			<input type='text' name='title' value='{$seotitle}' style='width:500px;' />
			<br><br>Description:<br>
			<textarea name='description' style='width:90%;height:100px;'>{$seodescription}</textarea>
			<br><br>
			<h2>Картинки товаров категории</h2>
			Title:<br>
			<input type='text' name='images_title' value='{$seoImagesTitle}' style='width:500px;' />
			<br><br>Alt:<br>
			<input type='text' name='images_alt' value='{$seoImagesAlt}' style='width:500px;' />
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
			<h3>Атрибуты категории</h3>
			<table>
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
			</table>		
		</div>
	</td>
</table>
	<input type='hidden' name='add_category' value='ok' />
</form>
";
?>