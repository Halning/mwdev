<?
$all_line="
<tr id='{$l_id}' rel='shop_filters-lists'  rel2='id'>
	<td id='list_name' class='cl_edit'>
		{$l_name}
	</td>
	<td>
		<table style='border:0px!important;'>

			<tr style='border:0px!important;'>

				<td style='border:0px!important;'>

					<form method='POST' action='{$request_url}'>

						<input type='image' src='/templates/{$theme_name}/img/down.gif' name='submit'/>

						<input type='hidden' name='move' value='down' />

						<input type='hidden' name='item_id' value='{$l_id}' />

					</form>
				</td>
				<td style='border:0px!important;'>
					{$l_order}
				</td>
				<td style='border:0px!important;'>
					<form method='POST' action='{$request_url}'>
						<input type='image' src='/templates/{$theme_name}/img/up.gif' name='submit' />
						<input type='hidden' name='move' value='up' />
						<input type='hidden' name='item_id' value='{$l_id}' />
					</form>
				</td>
			</tr>
		</table>
	</td>
	<td class='acty'>
		<a href='/?admin=delete_filter_list&id={$l_id}&filterID={$filter_id}'><img src='/templates/{$theme_name}/img/btnbar_del.png'></a>
	</td>
</tr>
"

?>