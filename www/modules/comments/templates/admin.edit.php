<?   
$edit= "
<form method='POST' action='{$request_url}' name='main_form'>
	Имя:<br>
	<input type='text' name='name' value='{$name}' style='width:400px;'/>
	<br><br>
	Город:<br>
	<input type='text' name='city' value='{$city}' style='width:400px;'/>
	<br><br>
	e-mail:<br>
	<input type='text' name='email' value='{$email}' style='width:400px;'/>
	<br><br>	
	Дата добавления:<br>
	<input type='text' name='date' value='{$date}' style='width:150px;' id='f_date_c' />	<img src='/templates/{$theme_name}/img/cal.gif' width='16' border='0' height='16' id='f_trigger_c' onmouseover='this.style.background='red';' onmouseout='this.style.background=''' />
	<br><br>
	<link rel='stylesheet' type='text/css' media='all' href='/includes/calendar/calendar-win2k-cold-1.css' title='win2k-cold-1' />
	<script type='text/javascript' src='/includes/calendar/calendar.js'></script>
	<script type='text/javascript' src='/includes/calendar/lang/calendar-rus.js'></script>
 	<script type='text/javascript' src='/includes/calendar/calendar-setup.js'></script>
	<script type='text/javascript'>
    	Calendar.setup({
        		inputField     :    'f_date_c',     // id of the input field
        		ifFormat       :    '%Y-%m-%d ".rand(10,23).":".rand(10,59).":".rand(10,59)."',      // format of the input field
        		button         :    'f_trigger_c',  // trigger for the calendar (button ID)
        		align          :    'Tl',           // alignment (defaults to 'Bl')
        		singleClick    :    true
	});
	</script>
	Тип отзыва:<br>
	<select name='item_prefix'>
		{$types_options}
	</select>
	<br><br>Текст комментария:<br>

	<textarea name='text' id='my_textarea_id'>{$text}</textarea>
	<script type='text/javascript'>XBB.init();</script>
	<br />
	<input type='hidden' name='add_{$modul_name}' value='ok' />
<!--
	Показывать на сайтах:
-->
	{$sites}

	
</form>
";
?>