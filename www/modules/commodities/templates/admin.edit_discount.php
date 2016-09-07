<?   
$edit= "
<form method='POST' action='{$request_url}' name='main_form'>
	Сума покупок:<br>
	<input type='text' name='discount_val1' value='{$dis_val1}' style='width:50px;'/>
	<br><br>% Скидки:<br>
	<input type='text' name='discount_val2' value='{$dis_val2}' style='width:50px;'/>
	<input type='hidden' name='add_discount' value='ok' />
</form>
";
?>