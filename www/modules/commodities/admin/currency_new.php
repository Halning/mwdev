<?
if ($_SESSION['status']=="admin")
{
	$center="";

 $xml=simplexml_load_file("https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=5");

if ( isset($_POST["submit"]))
{
  //=================Update valut kurs api privatbank=================================================================
	//   $xml=simplexml_load_file("https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=5");
	  
		for($i=0; $i<count($xml->row); $i++){
			$curname=$xml->row[$i]->exchangerate['ccy'];
			$buy=$xml->row[$i]->exchangerate['buy'];
			$sale=$xml->row[$i]->exchangerate['sale'];
			
			$a=1/floatval($sale);
			
			$query="UPDATE `shop_cur` SET cur_val='{$a}' WHERE cur_name='{$curname}'";
			mysql_query($query);			
					
		}	
	
	
	
	/*$query2="SELECT * FROM `shop_cur`;";
	$result2=mysql_query($query2);
	if (mysql_num_rows($result2) > 0) 
	{
		for($i=1;$i<=mysql_num_rows($result2);$i++)
		{
			$row2 = mysql_fetch_object($result2);
			$cur_name=$row2->cur_name;
			$$cur_name=round(ereg_replace(",",".",$_POST[$cur_name]),2);
			$query4="UPDATE `shop_cur` SET `cur_val`='{$$cur_name}' WHERE `cur_name`='{$cur_name}';";
			$result4=mysql_query($query4);

				mysql_query($query) or die ("Ошибка выполнения запроса!! ".mysql_error()); 

				$center="<br><br>Запрос Успешно выполнен";
		}
	}
	$price=$_POST["price"];
	$limit=$_POST["limit"];
	$nadbavka=$_POST["nadbavka"];
*/
}

{
	$lines="";
	$query2="SELECT * FROM `shop_cur`;";
	$result2=mysql_query($query2);
	if (mysql_num_rows($result2) > 0) 
	{
		for($i=1;$i<=mysql_num_rows($result2);$i++)
		{
			$row2 = mysql_fetch_object($result2);
			$cur_name=$row2->cur_name;
			$$cur_name=$row2->cur_val;
$lines.="
<tr>
		<td>
			{$cur_name}
		</td>
		<td>
			<input name='{$cur_name}' size='10' value='{$$cur_name}' type='text' disabled>
		</td>
	</tr>
";

		}
	}
	//------Valut kurs----------------------------------
	$val.="<tr>
	<th></th><th>Покупка</th><th>Продажа</th>
	 </tr>";
	for($i=0; $i<count($xml->row); $i++){
		$val.="<tr>
		<td>".$xml->row[$i]->exchangerate[ccy]."</td>
		<td><input type='text' size='10' value=".$xml->row[$i]->exchangerate[buy]." disabled></td>
		<td><input type='text' size='10' value=".$xml->row[$i]->exchangerate[sale]." disabled></td>
		<tr>";
	}
	//----------------------------------------------
	require("modules/commodities/templates/admin.currency_new.php"); 		
	$center=$center.$admin_currency;
}

}else
{
	echo "Сюда низя! Доступа не хватает... Ыыы";
}
?>