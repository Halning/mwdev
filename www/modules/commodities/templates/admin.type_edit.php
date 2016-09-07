<?php
$edit="
<form method='POST' action='{$request_url}' name='main_form'>
	Название назначения:<br>
	<input type='text' name='type_name' value='{$type_name}' style='width:300px;' /><br><br>
	Позиция:<br>
	<input type='text' name='order' value='{$order}' style='width:80px;' /><br><br>
	<input type='hidden' name='add_type' value='ok' />
</form>
";
?>