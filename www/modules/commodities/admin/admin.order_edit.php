<?php
$edit="
<script>
		 $(document).ready(function() {
			
			
			jQuery('.cl_trt').click(function(){
				jQuery('.cl_delll').show();
				jQuery('.cl_edittt').show();
			});
			jQuery('.cl_delll').click(function(){
				urlid=0;
				jQuery('.cl_trt').each(function()
				{
					if($(this).is(':checked'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				if(urlid!=0)
				{
					location.href='/?admin=delete_order_com&id='+urlid;
				}
				
			});
			jQuery('.cl_edittt').click(function(){
				urlid=0;
				jQuery('.cl_trt').each(function()
				{
					if($(this).is(':checked'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				if(urlid!=0)
				{
					location.href='/?admin=edit_order_comment&commodityID='+urlid;
				}
				
			});
			jQuery('.c2_status').click(function(){
				urlid=0;
				jQuery('.c3_trt').each(function()
				{
					if($(this).is(':checked'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				jQuery('.c4_trt').each(function()
				{
					if($(this).is(':checked'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				jQuery('.c5_trt').each(function()
				{
					if($(this).is(':checked'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				if(urlid!=0)
				{
					location.href='/?admin=add_status_com&id='+urlid;
				}
				
			});
			
		});
		</script>
		<span class='cl_delll'>Удалить <img src='/templates/admin/img/btnbar_del.png'></span>
		<span class='cl_edittt'>Редактировать <img src='/templates/admin/img/btnbar_edit.png'></span>
		<span class='c2_status'>Изменить статус</span>;
<table style='width:100%;'>
<tr>
<td>
<table class='cl_big_table'>

</table>
<table class='sortable cl_need_reload'>
<tr><td></td><th>Бренд</th><th>Артикул</th><th>Цвет</th><th>Размер</th><th>Кол-во</th><th>Валюта</th><th>Цена</th><th>Сумма</th><th>Ссылка на товар</th><th>Источник</th><th>Комментарий</th></tr>

	{$lines}
</table>
<table>
<tr>
<td style='width:500px;'>
	<table class='cl_big_table cl_need_reload'>
		<tr  id='{$row["id"]}' rel='shop_orders'  rel2='id'>
			<th>
				Скидка
			</th>
			<td id='discount' class='cl_edit3'>
				{$row["discount"]}
			</td>
			<td>
				%
			</td>
		</tr>
		<tr  id='{$row["id"]}' rel='shop_orders'  rel2='id'>
			<th>
				Коммиссия
			</th>
			<td id='commission' class='cl_edit3'>
				{$row["commission"]}
			</td>
			<td>
				%
			</td>
		</tr>
		<tr  id='{$row["id"]}' rel='shop_orders'  rel2='id'>
			<th>
				Доставка
			</th>
			<td id='delivery_price' class='cl_edit3'>
				{$row["delivery_price"]}
			</td>
			<td>
				{$cur}
			</td>
		</tr>
	</table>
</td>
<td>
	<table class='cl_big_table'>
		<tr  id='{$row["id"]}' rel='shop_orders'  rel2='id' >
			<th style='width:200px!important;'>
				Способ оплаты
			</th>
			<td id='payment' class='cl_edit2'>
				<span>{$payment_name}</span> {$payments_lines}
			</td>
			
		</tr>
		<tr  id='{$row["id"]}' rel='shop_orders'  rel2='id'>
			<th>
				Способ доставки
			</th>
			<td id='delivery' class='cl_edit2'>
				<span>{$delivery_name}</span> {$delivery_lines}
			</td>
			
		</tr>
		<tr >
			<th>
				<h2>Всего к оплате:</h2>
			</th>
			<td >
				<h2>{$price} {$cur}</h2>
			</td>
			
		</tr>
	</table>
</td>
</tr>
<tr>
			<td><a href='/?admin=edit_order&id={$row["id"]}&import=yes'><div id='xls2'>Import XLS</div></a></td>
		</tr>
		<tr>
			<td><form action='/?admin=add_order_com' method='post'>
				<table>
				<tr>
 					<input type = 'hidden' value = '{$id}'name = 'offer_id'/>
 					<input type = 'hidden' value = '{$total_count}'name = 'total_count'/>
 					<td>ID товара</td> <td><input type =' text'name='com_id'/></td>
 					</tr>
 					<tr><td>Валюта </td><td><input type='text' name='cur_id'/></td>
 					</tr>
 					<tr><td>Размер </td><td><input type='text' name='com'/></td></tr>
 					<tr><td>кол-во</td><td><input type='text' name='count'/></td></tr>
 					<tr><td></td><td><input type='submit'/></td></tr>
				</table></form></td>
		</tr>
</table>
</td>
<td style='width:300px;'>
		<div class='cl_sidebar'>
			<h3>Детали заказа</h3>
			<table class='cl_big_table'>
				<tr  id='{$row["id"]}' rel='shop_orders'  rel2='id'>
					<td>
						Заказ №
					</td>
					<td id='cod' class='cl_edit'>
						{$row["cod"]}
					</td>
				</tr>
				<tr id='{$row["id"]}' rel='shop_orders'  rel2='id'>
					<td>
						Дата:
					</td>
					<td id='date' class='cl_edit'>
						{$row["date"]}
					</td>
				</tr>
				<tr id='{$row["id"]}' rel='shop_orders'  rel2='id'>
					<td>
						Имя:
					</td>
					<td id='name' class='cl_edit'>
						{$row["name"]}
					</td>
				</tr>
				<tr id='{$row["id"]}' rel='shop_orders'  rel2='id'>
					<td>
						Email:
					</td>
					<td id='email' class='cl_edit'>
						{$row["email"]}
					</td>
				</tr>
				<tr id='{$row["id"]}' rel='shop_orders'  rel2='id'>
					<td>
						Телефон:
					</td>
					<td id='tel' class='cl_edit'>
						{$row["tel"]}
					</td>
				</tr>
				<tr id='{$row["id"]}' rel='shop_orders'  rel2='id'>
					<td>
						Город:
					</td>
					<td>
						{$row["city"]}
					</td>
				</tr>
				<tr id='{$row["id"]}' rel='shop_orders'  rel2='id'>
					<td>
						Адрес:
					</td>
					<td id='address' class='cl_edit'>
						{$row["address"]}
					</td>
				</tr>
				<tr id='{$row["id"]}' rel='shop_orders'  rel2='id'>
					<td>
						Комментарий пользователя:
					</td>
					<td id='user_comments' class='cl_edit'>
						{$row["user_comments"]}
					</td>
				</tr>
				<tr id='{$row["id"]}' rel='shop_orders'  rel2='id'>
					<td>
						Статус:
					</td>
					<td id='status' class='cl_edit2'>
						<span>{$status_name}</span> {$status_lines}
					</td>
				</tr>
			</table>		
		</div>
	</td>
</table>
";
?>