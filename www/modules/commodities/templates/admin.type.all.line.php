<?
$all_line="
<tr>
	<td>
		#{$type_id}
	</td>
	<td>
		{$type_name}
	</td>
	<td>
		<table style='border:0px!important;'>
			<tr style='border:0px!important;'>
				<td style='border:0px!important;'>
					<form method='POST' action='{$request_url}'>
						<input type='image' src='/templates/{$theme_name}/img/down.gif' name='submit'/>
						<input type='hidden' name='move' value='down' />
						<input type='hidden' name='item_id' value='{$type_id}' />
					</form>
				</td>
				<td style='border:0px!important;'>
					{$order}
				</td>
				<td style='border:0px!important;'>
					<form method='POST' action='{$request_url}'>
						<input type='image' src='/templates/{$theme_name}/img/up.gif' name='submit' />
						<input type='hidden' name='move' value='up' />
						<input type='hidden' name='item_id' value='{$type_id}' />
					</form>
				</td>
			</tr>
		</table>
	</td>
	<td class='acty'>
		<a href='/?admin=edit_type&type_id={$type_id}'><img src='/templates/{$theme_name}/img/btnbar_edit.png'></a>
	</td>
	<td class='acty'>
		<a href='/?admin=delete_type&type_id={$type_id}'><img src='/templates/{$theme_name}/img/btnbar_del.png'></a>
	</td>
</tr>
";
?>