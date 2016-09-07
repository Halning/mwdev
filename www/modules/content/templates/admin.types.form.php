<?
$opt = sel_kind("field_kind", $field_kind);
$form = "
<form name='form{$fileld_id}' method='POST' action='{$request_url}'>
	<label>Имя поля: <input type='text' name='field_name' value='{$field_name}'></label>
	Тип поля: 
	
		{$opt}
	Переменая шаблона: {\$field{$fileld_id}}
	<input type='hidden' name='do' value='update'>
	<input type='hidden' name='id' value='{$fileld_id}'></label>
	<input type='hidden' name='field_typeid' value='{$field_typeid}'></label>
	<input type='submit' name='okbutton' value='Изменить'>
	<a id='delete_button' href='http://tak-to.net/?admin=delete_fields&id={$fileld_id}'>Удалить</a>
</form>
"

?>