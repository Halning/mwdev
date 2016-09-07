<?
$edit="	
<form method='POST' action='{$request_url}' name='main_form'>
<h1 align='left'>Все страницы</h1>
	Заголовок статьи:<br>
	<input type='text' name='title' value='{$main_page_title}' style='width:500px;' id='name' /><br>


					
	

	<br>Полный текст:<br>
	<textarea name='text' id='text'>{$main_page_text}</textarea>
		<script type='text/javascript'>
		jQuery(document).ready(function() {
		CKEDITOR.replace('text', {  height : '500',
       
            language : 'ru' });
		});
		</script>
		
	
	<input name='add_text' value='true' type='hidden'>
	
</form>
";
?>