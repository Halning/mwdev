<?
if ($_SESSION['status']=="admin"){


	$res=mysql_query("SELECT * FROM `shop_blocks` WHERE 1");

	while($row=mysql_fetch_assoc($res)){
		$scb_id=$row["id"];
		$scb_name=$row["name"];
		$annotation=$row["title"];
		$url="/catalog/".$row["url"];
		//$cat_id=0;
		$scb_date=$row["date"];

		$countResBlock=mysql_query("SELECT COUNT(  `block_id` ) AS count
			FROM  `shop_blocks_products` 
			WHERE  `block_id` ='{$scb_id}'; ");
		$countBlock=mysql_fetch_assoc($countResBlock);

		$add_line.="
			<tr id='{$scb_id}' rel='shop_blocks' rel2='id' >
				<td>{$scb_id}</td>
				<td>{$scb_name}
				<div class=\"hover-actions\">
						<a href=\"/?admin=commodity_blocks_edit&getId={$scb_id}\">Редактировать</a>&nbsp;
						<a href=\"/?admin=commodity_blocks_edit&getId={$scb_id}\" target=\"_blank\">Просмотр</a>&nbsp;
						<!--<a href=\"\">Удалить</a>-->
					</div>
				</td>
				<td id='title' class='cl_edit'>{$annotation}</td>
				<td><a href='{$url}' target='_blank' >{$url}</a></td>
				<td style='text-align: center;'>{$countBlock['count']}</td>
				<td>{$scb_date}</td>
			</tr>";
	} 


	$center.="
		<br/><br/>
		<table style='width: 100%;' class='sortable scb'>
			<th>ID</th>
			<th>Наименование</th>
			<th>Аннотация</th>
			<th>Ссилка</th>
			<th>Товаров</th>
			<th>Последнее изменение</th>
			{$add_line}
		</table>
		<style>
			.scb th:first-child, .scb td:first-child{
				text-align: center;
				height: 30px;
			}
			.scb td{
				vertical-align: middle;
			}
		</style>
	";


}

?>