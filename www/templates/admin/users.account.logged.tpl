<div class="bodytext" id="hello">Здравствуйте, <a href="#">{$user_name}</a> 
(<a href='#' onclick='javascript:document.forms.logout_form.submit();'>Выход</a>)</div>
<form action='{$request_url}' method='post' name='logout_form'>
	<input type='hidden' name='users_option' value='logoff'>
</form>