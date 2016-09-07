<?
$all_line="
<tr id='{$row["comment_id"]}' rel='comments'  rel2='comment_id'>
	<td>
		#{$row["comment_id"]}
	</td>
	<td id='item_prefix' class='cl_edit acty'>
		{$row["item_prefix"]}
	</td>
	<td id='item_id' class='cl_edit'>
		{$row["item_id"]}
	</td>
	<td id='parent_id' class='cl_edit'>
		{$row["parent_id"]}
	</td>
	<td id='comment_date' class='cl_edit'>
		{$row["comment_date"]}
	</td>
	<td id='comment_date' class='cl_edit'>
		{$row["user_name"]}
	</td>
	<td id='comment_email' class='cl_edit'>
		{$row["comment_email"]}
	</td>
	<td id='comment_text' class='cl_edit'>
		{$row["comment_text"]}
	</td>
	<td id='comment_minus' class='cl_edit'>
		{$row["comment_minus"]}
	</td>
	<td id='comment_plus' class='cl_edit'>
		{$row["comment_plus"]}
	</td>
	<td id='comment_rat' class='cl_edit'>
		{$row["comment_rat"]}
	</td>
	<td>
		<a href='{$row["url"]}' target='_blank'>{$row["url"]}</a>
	</td>
	<td class='acty'>
		<a href='#' onclick=\"jQuery('#par1').attr('value','{$row["comment_id"]}');jQuery('#par2').attr('value','{$row["item_id"]}');jQuery('#par3').attr('value','{$row["item_prefix"]}');jQuery('#id_new_adda').submit();\"><img src='/templates/{$theme_name}/img/add.png'></a>
	</td>
	<td class='acty'>
		<a href='/?admin=delete_{$module_name}&{$module_name}_id={$line_id}'><img src='/templates/{$theme_name}/img/btnbar_del.png'></a>
	</td>
</tr>
";
?>