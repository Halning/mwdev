<?
$all_line="
<tr>
	<td>
		<input name='mess_to_{$user_id}' value='1' class='mess_to' type='checkbox'>
	</td>
	<td>
		{$user_name} {$user_realname}
				<div class='hover-actions'>
						<a href='/?admin=edit_user&user_id={$user_id}'>Редактировать</a>&nbsp;
						<a href='/?admin=delete_user&user_id={$user_id}'>Удалить</a>
				</div>
	</td>
	
	<td>
		{$user_email}
	</td>

	<td style='padding-left:5px;padding-right:5px;'>
		{$checkedq}
	</td>
	<td style='padding-left:5px;padding-right:5px;'>
		{$status}
	</td>
	<td style='padding-left:5px;padding-right:5px;'>
		{$user_soc_url}
	</td>
	
</tr>
";
?>