<?php
$center = "
<table width='100%'>
    <tr>
        <td>
            <h2>Все подписчики</h2>
        </td>
    </tr>
	<tr>
		<td>
            <form method='POST' action='{$request_url}' name='user_activ'>
                <input  name='user_activ' type='hidden' value='-' id='user_activ'>
            </form>
            <br>
            <br>
			{$pages_links}
			<form method='POST' action='{$request_url}' name='main_form'>
			<table class='tab_all sortable subscribers'>
				<tr>
                    <th>
                        <input id='all_mess_to' value='1' type='checkbox' onclick='setChecked(this)'>
                    </th>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>e-mail</th>
                    <th>Телефон</th>
                    <th>Город</th>
                    <th><span>Группа</span>{$groupFilterSelect}</th>
                </tr>
				{$all_lines}
			</table>
			{$all_params2}
			</form>
		</td>
	</tr>
</table>
<div class='subscribers-delete'>Удалить</div>
<br>
<br>
";