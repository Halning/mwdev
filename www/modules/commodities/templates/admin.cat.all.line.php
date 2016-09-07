<?
$all_line="
<tr id='{$r_category_id}' rel='shop_categories'  rel2='categories_of_commodities_ID'>
	<td>{$r_category_id}</td>
	<td>
		{$r_category_photo}
	</td>
	<td>
		{$r_category_name}
		<div class='hover-actions'>
			<a href='/?admin=edit_category&categoryID={$r_category_id}'>Редактировать</a>&nbsp;
			<a href='{$url}' target='_blank'>Просмотр</a>&nbsp;
			<a href='/?admin=delete_category&categoryID={$r_category_id}'>Удалить</a>
		</div>
	</td>
	<td><a href='{$alias}' target='_blank'>{$alias}</a></td>		
	<td>
		<table style='border:0px!important;'>
			<tr style='border:0px!important;'>			
				<td style='border:0px!important;'>				
				<form method='POST' action='{$request_url}'>					
				<input type='image' src='/templates/{$theme_name}/img/down.gif' name='submit'/>	
				<input type='hidden' name='move' value='down' />				
				<input type='hidden' name='item_id' value='{$r_category_id}' />					</form>				</td>	
				<td style='border:0px!important;'>					{$order}				</td>		
				<td style='border:0px!important;'>				
				<form method='POST' action='{$request_url}'>			
				<input type='image' src='/templates/{$theme_name}/img/up.gif' name='submit' />			
				<input type='hidden' name='move' value='up' />					
				<input type='hidden' name='item_id' value='{$r_category_id}' />					</form>		
				</td>			
			</tr>		
		</table>

	</td>
	<td class='acty'>
		<a href='/?admin=edit_category&categoryID={$r_category_id}'><img src='/templates/{$theme_name}/img/btnbar_edit.png'></a>
	</td>
	<td class='acty'>
		<a href='/?admin=delete_category&categoryID={$r_category_id}'><img src='/templates/{$theme_name}/img/btnbar_del.png'></a>
	</td>
</tr>";?>