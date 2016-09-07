<?
$all_head="
<tr>
	<th style='width:40px;' ><input type='checkbox' class='cl_all_yy' >
		<script>
		 $(document).ready(function() {
			
			jQuery('.cl_all_yy').click(function(){
				rrrr=$('.cl_all_yy').is(':checked');
				jQuery('.cl_trt').each(function()
				{
					if(rrrr)
					{
						jQuery(this).attr('checked',true);
						jQuery('.cl_delll').show();
						jQuery('.cl_edittt').show();
						
					}else
					{
						jQuery(this).attr('checked',false);
						jQuery('.cl_delll').hide();
						jQuery('.cl_edittt').hide();
					}
				});
			});
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
					location.href='/?admin=delete_commodity&commodityID='+urlid;
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
					location.href='/?admin=edit_commodity2&commodityID='+urlid;
				}
				
			});
		});
		</script>
	</th>
	<th style='width:40px;'>#</th>
	<th style='width:40px;'>Код</th>
	<th style='width:80px;'>Фото</th>
	<th style='width:80px;'>Действие</th>
	<th>Название</th>
	<th>Свойства</th>
	<th style='width:40px;'>Цена</th>
	<!--<th style='width:40px;'>Старая цена</th>-->
	<th style='width:40px;'>Оптовая цена</th>
	<th >Атрибуты</th>
	<!--<th style='width:40px;'>Активн.</th>-->
	<th style='width:40px;'>
		<div style='display:table'>
			<div style='display:table-cell;'>
				Позиция
			</div>
			<div style='display:table-cell;cursor:pointer;padding-left: 3px;' class='but_sort sort_up' rel-sort='7'>
				<div class='block_down2'></div>
				<div class='block_up2'></div>
			</div>
		</div>
	</th>
	<th class='acty'>Редакт.</th>
	<th class='acty'>Удалить</th>
</tr>
";
?>