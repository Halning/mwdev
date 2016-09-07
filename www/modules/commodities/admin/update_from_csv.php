<?
if ($_SESSION['status']=="admin")
{
		
		$prices=array();
		$alf1=array();
		$alf2=array();
		$fields=get_csv_fields();
		foreach($fields as $key=>$value){
			$rows_num[$value['name']]=$key;
		}
		if(isset($_FILES["myfile1"]))
		{
			$center="<br>";
			$fine=true;
			$file=@fopen($_FILES["myfile1"]["tmp_name"], "r") or $fine=false;
			if($fine)
			{
			
			//while ($data=fgets($file, 1000, "\r\n"))
			while (!feof ($file))
			{
				$buffer = fgetcsv($file, 4096,';'); 
				if(!is_array($buffer))
					continue;
				//$arr_data = explode(',', $buffer);			
				//$name=trim($arr_data[0]);
				foreach(array_keys($buffer) as $key){
					$buffer[$key] = iconv('CP1251','UTF-8', $buffer[$key]);
				}  
				$price=(float) str_replace(',','.',$buffer[$rows_num['commodity_price']]);
				$cod=str_replace('##','',$buffer[$rows_num['cod']]);
				$id=(int) $buffer[$rows_num['commodity_ID']];
				if($id and !empty($cod)){
					$prices[$cod]=$price;
					$names[$cod]=$buffer[$rows_num['com_name']];
				}
				//$alf1[$name]=(($alf1[$name]>$price_sklad)||(!isset($alf1[$name])))?$price_sklad:$alf1[$name];
				//$alf2[$name]=(($alf2[$name]>$price_site)||(!isset($alf2[$name])))?$price_site:$alf2[$name];
				

				//$center.="`price_USD`='{$price1}', `price_UAH`='{$price2}', `price_EUR`='{$price3}', `price_RUB`='{$price4}' WHERE `id`='{$id}'<br>";
			}
	
			foreach ($prices as $cod=>$price)
			{
				//$price_site=$alf2[$name];
				$name=$names[$cod];
				//=====================================
				$query = "
SELECT * FROM `shop_commodity` 
INNER JOIN `shop_commodity_description` ON `shop_commodity_description`.`com_id`=`shop_commodity`.`commodity_ID`
WHERE `cod`='{$cod}'
AND `lng_id`='{$sys_lng}';
";
				
				$result = mysql_query($query);
				if (mysql_num_rows($result) > 0) 
				{
					
					$row = mysql_fetch_object($result);
					$commodity_ID=$row->commodity_ID;
					$query2="
UPDATE `shop_commodity` SET
`commodity_virt_price`='{$price}',
`commodity_price`='{$price}',
`commodity_status`=1
WHERE 
`commodity_ID`='{$commodity_ID}';
";
					mysql_query($query2) or die(mysql_error());
					$tabs.="
<tr style='background-color:#D1E9D1;'>
	<td>
		{$name}
	</td>
	
	<td>
		{$price}
	</td>
	<td>
		Цена изменена
	</td>
</tr>
";

				}else
				{
					$tabs.="
<tr style='background-color:yellow;'>
	<td>
		{$name}
	</td>
	<td>
		{$price}
	</td>
	<td>
		Товар не найден
	</td>
</tr>
";
				}
				//=====================================
			}
			//func_update_com_cats("all");
			$tab_all="
<table class='tab_all'>
	<tr>
		<th>
			Название товара
		</th>
		
		<th>
			Цена на сайте
		</th>
		<th>
			Статус
		</th>
	</tr>
	{$tabs}
</table>
";
			
			$center=$tab_all."<br /><br /><br />Успешно выполнено<br /><br />Перейти к <a href='/?admin=shop'>списку всех товаров</a>";
			
			}else
			{
				$back_url="/?admin=shop";
			$it_item="Обновление цен с файла CSV";
			$fields=get_csv_fields();
			foreach($fields as $num=>$val)
				$labels[]=$val['label'];
			$labels_str=implode(';',$labels);
			require_once("modules/commodities/templates/admin.update_price_from_csv.php"); 
			require_once("templates/{$theme_name}/admin.edit.php"); 
			$center.="<h2>Выберите файл</h2>";
			}
		}
		else
		{
			$back_url="/?admin=shop";
			$it_item="Обновление цен с файла CSV";
			$fields=get_csv_fields();
			foreach($fields as $num=>$val)
				$labels[]=$val['label'];
			$labels_str=implode(';',$labels);
			require_once("modules/commodities/templates/admin.update_price_from_csv.php"); 
			require_once("templates/{$theme_name}/admin.edit.php"); 
		}
		
}
?>