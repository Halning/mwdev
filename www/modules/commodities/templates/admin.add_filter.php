<?
$edit="
<form method='POST' action='{$request_url}' name='main_form'>
<table>
<tr>
	<td>
		Имя свойства:
	</td>
	<td>
		<input type='text' name='filter_name' value='{$f_name}'> 
	</td>
</tr>
<tr>
	<td>
		Тип: 
	</td>
	<td>
		<select name='filter_type'>	
		{$f_type_sel}
		</select>
	</td>
</tr>
<tr>
	<td>
		Описание:
	</td>
	<td>
		<input type='text' name='filter_description' value='{$f_desc}'>
	</td>
</tr>
<tr>
	<td>
		Позиция:
	</td>
	<td>
		<input type='text' name='filter_order' value={$f_order}>
	</td>
</tr>
<tr>
	<td>
		Размер:
	</td>
	<td>
		<input type='checkbox' name='necessarily' value='1' {$necessarilychecked}>
	</td>
</tr>
<tr>
	<td>
		Цвет:
	</td>
	<td>
		<input type='checkbox' name='necessarily2' value='1' {$necessarilychecked2}>
	</td>
</tr>
</table>
<input type='hidden' name='cat_id' value='{$f_catid}'> 
<input type='hidden' name='add_filter' value='true'>
<input type='hidden' name='filter_id' value='{$filter_id}'>
</form>";
?>