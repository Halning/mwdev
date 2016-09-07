<?
$all_line="
<tr id='{$r_commodity_id}' rel='shop_commodity'  rel2='commodity_ID' style='height:73px;'>
	<td >
		<input type='checkbox' class='cl_trt' rel='{$r_commodity_id}'>

	</td>
	<td>
		#{$r_commodity_id}
	</td>
	<td id='cod' class='cl_edit'>
		{$r_commodity_cod}
	</td>
	<td>
		<a href='/?admin=edit_commodity&commodityID={$r_commodity_id}'>{$photo}</a>
	</td>
	<td>
		
					<div class='hover-actions'>
						
						<a href='/?admin=edit_commodity&commodityID={$r_commodity_id}' target='_blank'>Изменить</a>&nbsp;
						<a href='{$url}' target='_blank'>Просмотр</a>&nbsp;
						
						<a href='/?admin=delete_commodity&commodityID={$r_commodity_id}'>Удалить</a>
					</div>
	</td>
	<td id='filtr_desc' class='cl_edit'>
		{$r_commodity_name}
	</td>
	<td id='filtr_desc' class='cl_edit'>
		{$f}
	</td>
	<td id='commodity_price' class='cl_edit'>
		{$r_commodity_price}
	</td>
<!--	<td id='commodity_old_price' class='cl_edit'>
		{$r_commodity_old_price}
	</td>-->
	<td id='commodity_price2' class='cl_edit'>
		{$r_commodity_price2}
	</td>
	<td>
		<table>
				<tbody>	
				<tr id='art'>
					<td id='art1'>
						Опубликовать:
					</td>
					<td>
						<input onClick='new_check2({$r_commodity_id},{$com_vis});' type='checkbox' {$vis_check}>
					</td>
				</tr>
				<tr>
					<td>
						<!--Лидер продаж:--> Слайдер ХП: 
					</td>
					<td>
						<input onClick='new_check({$r_commodity_id},{$com_hit});' type='checkbox' {$hit_check}>
					</td>
				</tr>
				<tr>
				    <td colspan='2'>
					    {$selectBlock}
					</td>
					</tr>
			<!--	<tr>
					<td>
						Супер цена:
					</td>
					<td>
						<input onClick='new_check4({$r_commodity_id},{$com_act});' type='checkbox' {$act_check}>
					</td>
				</tr>
				<tr>
					<td>
						Новинка:
					</td>
					<td>
						<input onClick='new_check3({$r_commodity_id},{$com_new});' type='checkbox' {$new_check}>
					</td>
				</tr>-->
			</tbody></table>
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
	<td class='acty'>
		<a href='/?admin=edit_commodity&commodityID={$r_commodity_id}'><img src='/templates/{$theme_name}/img/btnbar_edit.png'></a>
	</td>
	<td class='acty'>
		<a href='/?admin=delete_commodity&commodityID={$r_commodity_id}'><img src='/templates/{$theme_name}/img/btnbar_del.png'></a>
	</td>
</tr>
";

?>