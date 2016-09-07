<?   
$edit= "
<script type='text/javascript' src='/templates/{$theme_name}/js/tabs.js'></script>
<form method='POST' action='{$request_url}' name='main_form' ENCTYPE='multipart/form-data'>

	<div class='section'>
		<ul class='tabs'>
			<li class='current'>Основные</li>
			<li class='cl_shop'>Настройки магазина</li>
			<li>SEO</li>
		</ul>
		<div class='box visible'>
			<input type='text' name='email' value='{$email}' style='width:450px;' /> - e-mail для уведомлений<br><br>
			{$def_lng_select}
			<h2>Изображения страниц</h2>
		
			<input type='text' name='artimgx' value='{$artimgx}' style='width:50px;' /> px	- ширина основного изображения<br />
			<input type='text' name='artimgy' value='{$artimgy}' style='width:50px;' /> px	- высота основного изображения<br />
			<select name='artimgt'>{$imgtpeline4}</select> - метод формирования миниатюры основного изображения<br /><br />
			
			<input type='text' name='artimgaddx' value='{$artimgaddx}' style='width:50px;' /> px	- ширина дополнительного изображения<br />
			<input type='text' name='artimgaddy' value='{$artimgaddy}' style='width:50px;' /> px	- высота дополнительного изображения<br />
			<select name='artimgaddt'>{$imgtpeline5}</select> - метод формирования миниатюры дополнительного изображения<br /><br />			
			<a href='/?admin=enable_cache&event=disable'>Отключить кэш</a><br/>
			<a href='/?admin=enable_cache&event=enable'>Включить кэш</a><br/>
			<a href='/?admin=enable_cache&event=clear'>Очистить кэш</a><br/>
			<a href='/includes/scripts/clean_system_sessions.php?action=delete_spum'>Очистить сессии - спам</a><br/>
			<a href='/includes/scripts/clean_system_sessions.php?action=delete_old'>Очистить сессии - старше 40 дней</a><br/>
		</div>
		<div class='box'>
			<h2>Изображения товаров</h2>
			<div class='cl_watermark'>{$watermark2}</div><br /><input name='myfile1' type='file' ><br>
			<label for='fff'>Использовать ватермарк:</label> <input type='checkbox' name='watermark' id='fff' value='1' {$watermark1}><br /><br />
			<input type='text' name='comitemsx' value='{$comitemsx}' style='width:50px;' /> px	- ширина изображения товара<br />
			<input type='text' name='comitemsy' value='{$comitemsy}' style='width:50px;' /> px	- высота изображения товара<br />
			<select name='comitemst'>{$imgtpeline1}</select> - метод формирования миниатюры товара<br /><br />
			
			<input type='text' name='addcomimgx' value='{$addcomimgx}' style='width:50px;' /> px	- ширина дополнительного изображения<br />
			<input type='text' name='addcomimgy' value='{$addcomimgy}' style='width:50px;' /> px	- высота дополнительного изображения<br />
			<select name='addcomimgt'>{$imgtpeline3}</select> - метод формирования миниатюры дополнительного изображения<br /><br />
			
			<h2>Настройки каталога</h2>
			{$def_cur_select}<br />
			<input type='text' name='catitemscount' value='{$catitemscount}' style='width:50px;' />	- товаров на странице сайта<br />
			<input type='text' name='catitemsx' value='{$catitemsx}' style='width:50px;' /> px	- ширина изображения категории<br />
			<input type='text' name='catitemsy' value='{$catitemsy}' style='width:50px;' /> px	- высота изображения категории<br />
			<select name='catitemst'>{$imgtpeline2}</select> - метод формирования миниатюры категории
		</div>
		<div class='box'>
			Title:<br>
			<input type='text' name='title' value='{$title}' style='width:500px;' />
			<br><br>Description:<br>
			<textarea name='description' style='width:90%;height:100px;'>{$description}</textarea>
		</div>
	<input type='hidden' name='add_site' value='ok' />
</form>
";
?>