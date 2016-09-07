<?
$all_line="
<tr id='{$f_id}' rel='shop_categories-filters'  rel2='filtr_id'>
	<td>
		{$f_id}
	</td>
	<td id='filtr_name' class='cl_edit'>
		{$f_name}
	</td>
	<td id='filtr_desc' class='cl_edit'>
		{$f_desc}
	</td>
	<td id='fitr_catid' class='cl_edit2'>
		<span>[{$cat_name2}]</span> {$cat_lines}
	</td>	
	<td id='filtr_typeid' class='cl_edit2'>
		<span>{$f_type}</span> {$type_lines}
	</td>
	<td class='acty'>
		{$man_list}
	</td>
	<td>
		<table style='border:0px!important;'>
			<tr style='border:0px!important;'>
				<td style='border:0px!important;'>
					<form method='POST' action='{$request_url}'>
						<input type='image' src='/templates/{$theme_name}/img/down.gif' name='submit'/>
						<input type='hidden' name='move' value='down' />
						<input type='hidden' name='item_id' value='{$f_id}' />
					</form>
				</td>
				<td style='border:0px!important;'>
					{$f_order}
				</td>
				<td style='border:0px!important;'>
					<form method='POST' action='{$request_url}'>
						<input type='image' src='/templates/{$theme_name}/img/up.gif' name='submit' />
						<input type='hidden' name='move' value='up' />
						<input type='hidden' name='item_id' value='{$f_id}' />
					</form>
				</td>
			</tr>
		</table>
	</td>
	<td class='acty'>
		<a href='/?admin=edit_filters&filterID={$f_id}'><img src='/templates/{$theme_name}/img/btnbar_edit.png'></a>
	</td>
	<td class='acty'>
		<a href='/?admin=delete_filter&filterID={$f_id}'><img src='/templates/{$theme_name}/img/btnbar_del.png'></a>
	</td>
</tr>
";
?>