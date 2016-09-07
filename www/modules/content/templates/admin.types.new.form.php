<?
$opt = sel_kind('field_kind');
$field_typeid = isset($_GET['typeID'])?'':"<select name='field_typeid'>".napalm_type($_GET['typeID'])."</select>";
$new_form = "
<h3>Добавить новое поле</h3>
<form name='add_types' method='POST' action='{$request_url}'>
	<label>Имя поля: <input type='text' name='field_name'></label>
	Тип поля: 
	
		{$opt}
	
	{$field_typeid}
	
	<input type='hidden' name='do' value='add'>
	<input type='submit' name='okbutton' value='Добавить'>
</form>

"

?>