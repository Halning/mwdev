<?
$all_line="
<tr>
	<td>
		#{$r_domenID}
	</td>
	<td>
		{$www_domen}
	</td>
	<td>
		{$title}
	</td>	
	<td>
		{$lng_name}
	</td>
	<td>
		{$cur_name}
	</td>
	<td>
		{$r_theme_name}
	</td>
	<td class='acty'>
		<a href='/?admin=edit_site&site_id={$r_domenID}'><img src='/templates/{$theme_name}/img/btnbar_edit.png'></a>
	</td>
	<td class='acty'>
		<a href='/?admin=delete_site&site_id={$r_domenID}'><img src='/templates/{$theme_name}/img/btnbar_del.png'></a>
	</td>
</tr>
";
?>