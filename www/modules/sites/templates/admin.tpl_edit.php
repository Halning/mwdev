<?php
$edit=<<<HERE
<script type='text/javascript' src='/templates/{$theme_name}/js/edit_area/edit_area_full.js'></script>
  <link rel="stylesheet" href="/templates/{$theme_name}/js/codemirror-3.13/lib/codemirror.css">
    <script src="/templates/{$theme_name}/js/codemirror-3.13/lib/codemirror.js"></script>
    <script src="/templates/{$theme_name}/js/codemirror-3.13/addon/hint/show-hint.js"></script>
    <link rel="stylesheet" href="/templates/{$theme_name}/js/codemirror-3.13/addon/hint/show-hint.css">
    <script src="/templates/{$theme_name}/js/codemirror-3.13/addon/edit/closetag.js"></script>
    <script src="/templates/{$theme_name}/js/codemirror-3.13/addon/hint/html-hint.js"></script>
    <script src="/templates/{$theme_name}/js/codemirror-3.13/mode/xml/xml.js"></script>
    <script src="/templates/{$theme_name}/js/codemirror-3.13/mode/javascript/javascript.js"></script>
    <script src="/templates/{$theme_name}/js/codemirror-3.13/mode/css/css.js"></script>
    <script src="/templates/{$theme_name}/js/codemirror-3.13/mode/htmlmixed/htmlmixed.js"></script>
        <ul class='cl_ul_edit_tpl'>
		<li><a href='/?admin=edit_tpl'>Основной шаблон</a></li>
		<li><a href='/?admin=edit_tpl&folder=css&file=style.css'>Файл CSS</a></li>
		<li><a href='/?admin=edit_tpl&file=users.registration.tpl'>Страница регистрации</a></li>
		<li><a href='/?admin=edit_tpl&file=users.registration.rules.tpl'>Правила регистрации</a></li>
		<li class='cl_shop'><a href='/?admin=edit_tpl&file=shop.category.full.tpl'>Категория товаров</a></li>
		<li class='cl_shop'><a href='/?admin=edit_tpl&file=shop.commodity.short.tpl'>Короткое описание товара</a></li>
		<li class='cl_shop'><a href='/?admin=edit_tpl&file=shop.commodity.full.tpl'>Полное описание товара</a></li>
		<li class='cl_shop'><a href='/?admin=edit_tpl&file=shop.basket.full.tpl'>Страница корзины</a></li>
		<li class='cl_shop'><a href='/?admin=edit_tpl&file=shop.basket_panel.tpl'>Панель корзины</a></li>
	</ul>
<form ENCTYPE='multipart/form-data' method='POST' action='{$request_url}' name='main_form'>
	<div style='max-width:1000px;border:1px solid #ccc;margin:5px;margin-right:10px;'>
	<textarea id="tpl_content" name='tpl_content' style='width:100%;'>{$tpl_content}</textarea><br><br>
	</div>
	<input type='hidden' name='edit_tpl' value='ok' />
</form>
<script type="text/javascript">
      CodeMirror.commands.autocomplete = function(cm) {
          CodeMirror.showHint(cm, CodeMirror.htmlHint);
      }
        
      var editor = CodeMirror.fromTextArea(document.getElementById("tpl_content"), {
        mode: 'text/html',
        autoCloseTags: true,
        extraKeys: {"Ctrl-Space": "autocomplete"}
      });
  
       jQuery(document).ready(function() {
         
            var url='{$request_url}';
	jQuery('.cl_ul_edit_tpl li a').each(function() 
	{ 
		if($(this).attr('href')==url)
		{ 
			$(this).addClass('curr');
		}
	});
        });       
    </script>
HERE;
?>