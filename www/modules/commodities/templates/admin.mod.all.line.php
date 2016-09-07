<?
$all_line="
<tr>
	<td>
		{$r_commodity_id}
	</td>
	<td>
		{$r_commodity_cod}
	</td>
	<td>
		{$r_commodity_name}
	</td>
	<td>
		{$alias}
	</td>
	<td>
		<table style='border:0px!important;'>
			<tr style='border:0px!important;'>
				<td style='border:0px!important;'>
					<form method='POST' action='{$request_url}'>
						<input type='image' src='/templates/{$theme_name}/img/down.gif' name='submit'/>
						<input type='hidden' name='move' value='down' />
						<input type='hidden' name='item_id' value='{$r_commodity_id}' />
					</form>
				</td>
				<td style='border:0px!important;'>
					{$order}
				</td>
				<td style='border:0px!important;'>
					<form method='POST' action='{$request_url}'>
						<input type='image' src='/templates/{$theme_name}/img/up.gif' name='submit' />
						<input type='hidden' name='move' value='up' />
						<input type='hidden' name='item_id' value='{$r_commodity_id}' />
					</form>
				</td>
			</tr>
		</table>
	</td>
	<td>
		{$r_commodity_virt_price}
	</td>
	<td>
		{$r_commodity_price}
	</td>
	<td>
		<form method='POST' action='{$request_url}'>
			<table style='border:0px!important;'>
				<tr style='border:0px!important;'>
					<td style='border:0px!important;'>
						<input type='text' name='upprice' value='{$commodity_up_price2}' style='width:30px;' />
					</td>
					<td style='border:0px!important;'>
						<input type='image' src='/templates/{$theme_name}/img/reload.gif' name='submit' style='width:15px;margin-top:5px;'/>
						<input type='hidden' name='up_com_id' value='{$r_commodity_id}' />
					</td>
				</tr>
			</table>
		</form>
	</td>
	<td class='acty'>
		<a href='/?admin=edit_commodity&commodityID={$r_commodity_id}'><img src='/templates/{$theme_name}/img/btnbar_edit.png'></a>
	</td>
	<td class='acty'>
		<a href='/?admin=delete_commodity&commodityID={$r_commodity_id}'><img src='/templates/{$theme_name}/img/btnbar_del.png'></a>
	</td>
</tr>
";
?>