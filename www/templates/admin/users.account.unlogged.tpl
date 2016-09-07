<div id='id_loginform' >
	<!-- <form method='post' action='{$request_url}'>
		<table width='186' border='0' cellspacing='0' cellpadding='0' style='margin-top:-10px;'>
			<tr>
				<td height='20'>
					Логин:
				</td>
				<td>
					<input name='login' type='text' style='width:90px'  tabindex="1" />
				</td>
				<td height='20'>
					Пароль: 
				</td>
				<td>
					<input name='password' type='password' style='width:90px'  tabindex="2" />
				</td>
				<td rowspan='2'>
					<input value='Войти' type='submit' style='margin-left:10px;' class='myButton' />
					<input name='users_option' value='login' type='hidden'>
					<input name='option' type='hidden'>
					<input type='hidden' id='login' value='submit' />
				</td>
			</tr>
			<tr>	
				<td style='width:200px;padding-top:20px;padding-bottom:20px;' class='cl_login_buttons' colspan='5'> <div style='float:left;margin-right:10px;'>или</div>
				<noindex>
					<a rel="nofollow" href='http://www.owercms.com.ua/auth.php?provider=google&login=1' re><img src='http://www.owercms.com.ua/images/google.png' /></a>
					<a rel="nofollow" href='http://www.owercms.com.ua/auth.php?provider=yandex&login=1'><img src='http://www.owercms.com.ua/images/yandex.png' /></a>
					<a rel="nofollow" href='http://www.owercms.com.ua/auth.php?provider=facebook&login=1'><img src='http://www.owercms.com.ua/images/facebook.png' /></a>
					<a rel="nofollow" href='http://www.owercms.com.ua/auth.php?provider=vk&login=1'><img src='http://www.owercms.com.ua/images/vkontakte.png' /></a>
					<a rel="nofollow" href='http://www.owercms.com.ua/auth.php?provider=odnoklassniki&login=1'><img src='http://www.owercms.com.ua/images/odnoklassniki.png' /></a>
				</noindex>
				</td>
			</tr>
		</table> 
	</form> -->
	<form method='post' action='{$request_url}' style="text-align: center;">
		<label>Логин:</label> <input class="in_login" name='login' type='text'  tabindex="1" /><br/>
		<label>Пароль:</label> <input class="in_login" name='password' type='password' tabindex="2" /><br/>

		<center>
			<input value='Войти' type='submit' style='margin-left:10px;' class='myButton' />
			<input name='users_option' value='login' type='hidden'>
			<input name='option' type='hidden'>
			<input type='hidden' id='login' value='submit' />
		</center>
	</form>
</div>
<style>
#id_loginform {
color:#000;
	width:390px;
	
	border:1px solid #fff;
	position:relative;
	padding: 17px;
	margin: 0 auto;
	margin-top:50px;
	background: #d8e0de; /* Old browsers */
background: -moz-linear-gradient(top, #d8e0de 0%, #aebfbc 22%, #99afab 33%, #8ea6a2 57%, #829d98 77%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#d8e0de), color-stop(22%,#aebfbc), color-stop(33%,#99afab), color-stop(57%,#8ea6a2), color-stop(77%,#829d98)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #d8e0de 0%,#aebfbc 22%,#99afab 33%,#8ea6a2 57%,#829d98 77%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #d8e0de 0%,#aebfbc 22%,#99afab 33%,#8ea6a2 57%,#829d98 77%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, #d8e0de 0%,#aebfbc 22%,#99afab 33%,#8ea6a2 57%,#829d98 77%); /* IE10+ */
background: linear-gradient(to bottom, #d8e0de 0%,#aebfbc 22%,#99afab 33%,#8ea6a2 57%,#829d98 77%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d8e0de', endColorstr='#829d98',GradientType=0 ); /* IE6-9 */

-webkit-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 1);
-moz-box-shadow:    0px 0px 7px 0px rgba(50, 50, 50, 1);
box-shadow:         0px 0px 7px 0px rgba(50, 50, 50, 1);
}
body
{
	background:#c0cecb!important;
}
#id_loginform table td{
padding-left:10px;
}

#id_loginform label{
	display:inline-block;
    width:60px;
    text-align: right;
	padding-right: 5px;
}
#id_loginform .in_login{
	width: 250px;
}
</style>