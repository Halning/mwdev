<?
$all_line = "
<tr id='{$type_id}' rel='content_types'  rel2='type_id'>
	<td>
		ID {$type_id}
	</td>
	<td id='type_name' class='cl_edit'>
		{$type_name}
	</td>
	<td id='count' class='acty cl_edit'>
		{$count}
	</td>
	<td class='acty'>
		<a href='/?admin=edit_tpl&file=content.short_page.{$type_id}.tpl'>
			<img src='/templates/{$theme_name}/img/template.png'>
		</a>
	</td>
	<td class='acty'>
		<a href='/?admin=edit_tpl&file=content.full_page.{$type_id}.tpl'>
			<img src='/templates/{$theme_name}/img/template.png'>
		</a>
	</td>
	<td class='acty'>
		<a href='/?admin=edit_types&typeID={$type_id}'>
			<img src='/templates/{$theme_name}/img/btnbar_edit.png'>
		</a>
	</td>
	<td class='acty'>
		<a href='/?admin=#'>
			<img src='/templates/{$theme_name}/img/btnbar_del.png'>
		</a>
	</td>
</tr>
";


?>