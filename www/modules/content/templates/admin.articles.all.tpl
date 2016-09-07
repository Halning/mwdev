	

<table class="sortable">

				<tr>
					<th style='width:100px;'>
						
					</th>
					<th style='width:30px;'>
						
					</th>		
					<th>
						Название
					</th>
					<th style='width:50px;'>
						Позиция
					</th>
					<th style='width:120px;'>
						Дата публикации
					</th>
				</tr>
			
	{$all_lines}
</table>
<form method='POST' action='{$request_url}' id='id_change_st'>
<input type='hidden' name='st_con_id' value='0' id='id_st_id' />
<input type='hidden' name='st_con_st' value='0' id='id_st_st' />
</form>

<style>
	.cl_dd_{$p_id} td
	{
		display: table-cell;
	}
</style>