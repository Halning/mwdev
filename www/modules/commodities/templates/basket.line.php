<?php
$basket_line="
<tr>
	<td class='col-1'>
		<span class='order_table_img'><a href='{$com_url}'>{$c_sphoto}</a><a name='com{$c_id}'></a></span>
	</td>
	<td class='col-2'>
		<p class='order_table_kod'><strong>Код:</strong> {$c_cod}</p>
		<p class='order_table_description'>{$c_name}</p>
	</td>
	<td class='col-3'>
		<form action='/basket{$urlend}#com{$c_id}' method='post' id='form_style'>
			<select class='order_table_num' name='add' onchange='this.form.submit();'>
				{$opt_count}
			</select>
			<input name='id' value='{$c_id}' type='hidden'>
		</form>
		
	</td>
	<td class='col-4'><span class='order_table_price'>{$new_price} {$cur_show}</span></td>
	<td class='col-5'>

		<span class='order_table_del_item'  onClick='document.getElementById(\"delete_from_basket\").value={$c_id};document.forms.form_del.submit();'></span>
	</td>
</tr>
";
?>