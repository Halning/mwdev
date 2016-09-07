<?   
$edit= "
<font color='red'>{$reg_error}</font>


		
<form method='post' action='{$request_url}' name='main_form'>
<div class='classss'>
	 Логин: 
	<br /><input name='user_loginname' id='name' style='width: 165px;' class='f_input' type='text' value='{$user_loginname}'>
	<br /><br />
		
	<br /> Имя и фамилия:
	<br /><input name='user_realname' id='name' style='width: 165px;' class='f_input' type='text' value='{$user_realname}'>
	<br /><br />

	<br /> E-Mail:
	<br /><input name='new_email' class='f_input' type='text' style='width: 165px;'  value='{$user_email}'>
	<br /><br />
		
	<br /> Пароль:			
	<br /><input name='new_password1' class='f_input' type='text' style='width: 165px;' value='{$user_realpassword}'>
	<br /><br />
	<br />Права:
	<table>
		<tr>
			<td>
				<br />Админ:			
				<br /><input name='admin_checkbox'  type='checkbox' value='1' {$admin_checkbox}>
				<br /><br />
			</td>
		

		</tr>
	</table>
	
	

	<br />Скидка (%):
	<br /><input name='user_discount' id='user_discount' style='width: 165px;' class='f_input' type='text' value='{$user_discount}'>
	<br /><br />

	<br />Телефон:
	<br /><input name='user_tel' id='name' style='width: 165px;' class='f_input' type='text' value='{$user_tel}'>
	<br /><br />

	<br />Дата рождения:
	<br /><input name='birthday' id='name' style='width: 165px;' class='f_input' type='text' value='{$birthday}'>
	<br /><br />
				
	<br />Пол:
	<br /><input name='user_sex' value='1' type='radio' {$user_sex_male}>Муж. <input name='user_sex' value='2' type='radio' {$user_sex_famale}>Жен.
			
	<br /><br />Страна:
	<br /><input name='user_country' id='name' style='width: 165px;' class='f_input' type='text' value='{$user_country}'>

	<br /><br />		
	<br />Город:
	<br /><input name='user_city' id='name' style='width: 165px;' class='f_input' type='text' value='{$user_city}'>
			
	<br /><br />Адрес:
	<br /><textarea name='user_adr' style='width: 300px; height: 100px;'>{$user_adr}</textarea>
	<br /><br />	
			
	
</div>
</form>

";
?>