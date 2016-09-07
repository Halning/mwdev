<?
$all_line="
<tr id='{$row["id"]}' rel='seo_pages'  rel2='id'>
	<td class='acty'>
		<input type='checkbox' class='sel_commodity' name='tochange[]' value='{$row["id"]}'>
	</td>
	<td>
		{$row["url"]}
	</td>
	<td class='acty'>
		{$row["lvl"]}
	</td>
	<td id='type' class='acty cl_edit2'>
		<span>{$f_type}</span> {$type_lines}
	</td>
	<td class='acty'>
		{$row["in"]}
	</td>
	<td class='acty'>
		{$row["out"]}
	</td>
	<td class='acty'>
		{$row["weight1"]}
	</td>
	<td class='acty'>
		{$row["weight2"]}
	</td>
</tr>
";
?>