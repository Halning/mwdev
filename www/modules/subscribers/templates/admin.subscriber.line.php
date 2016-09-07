<?php
/**
 * Created by PhpStorm.
 * Date: 26.03.16
 * Time: 23:48
 */

$all_lines .= "
<tr>
	<td>
		<input name='{$subscriber->sub_id}' value='{$subscriber->sub_type}' class='mess_to' type='checkbox'>
	</td>
	<td>
	    {$subscriber->sub_id}
	</td>
	<td>
	    {$subscriber->user_name}
	    <div class='hover-actions'>
	    {$editLink}
			<a href='#' id='subscriber-delete' data-id='$subscriber->sub_id' data-type='$subscriber->sub_type'>Удалить</a>
		</div>
    </td>
	<td>
	    {$subscriber->sub_email}
    </td>
    <td>
        {$subscriber->phone}
    </td>
    <td>
        {$subscriber->city}
    </td>
	<td>
		{$subscriber->type}
    </td>
</tr>
";
