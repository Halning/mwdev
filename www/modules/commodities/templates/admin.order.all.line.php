<?
$all_line="
<tr id='{$row["id"]}' rel='shop_orders'  rel2='id'>
	<td><input type='checkbox' class='cl_trt' rel='{$row["id"]}'/></td>
	<td>
		<a href='/?admin=edit_order&id={$row["id"]}'>{$row["id"]}</a>
	</td>
	<td style='width:180px;'>
		<a href='/?admin=edit_order&id={$row["id"]}'>{$row["date"]}</a>
	</td>
	<td>
		<a href='/?admin=edit_order&id={$row["id"]}'>{$row["cod"]}</a>
	</td>
	<td>
		<a href='/?admin=edit_order&id={$row["id"]}'>{$row["name"]}</a>
	</td>
	<td>{$status_of_order}</td>	
	<td>
		<a href='?admin=all_orders&exportId={$row["id"]}'><div id='xls'>XLS</div></a>
	</td>	
	<td>
		<a href='/?admin=edit_order&id={$row["id"]}'><nobr>{$price} {$ccur_show}</nobr></a>
	</td>
	<td class='acty'>
		<a href='/?admin=edit_order&id={$row["id"]}'><img src='/templates/{$theme_name}/img/btnbar_edit.png'></a>
	</td>
	<td class='acty'>
		<a href='/?admin=delete_order&id={$row["id"]}'><img src='/templates/{$theme_name}/img/btnbar_del.png'></a>
	</td>
</tr>
";

?>