<?php
	$getId=$id;
		$sql="SELECT * FROM `shop_orders` 
		WHERE `id`='{$id}'";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			//$commission=$row["commission"];
			$delivery_price=$row["delivery_price"];
			$discount=$row["discount"];

			$sql2="
			SELECT * FROM `shop_orders_coms`
			LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
			WHERE `offer_id`='{$row["id"]}' AND `count`>0;";
			$res2=mysql_query($sql2);
			while($row2=mysql_fetch_assoc($res2))
			{
				

				$sql3="SELECT * FROM `shop_commodities-categories`
				INNER JOIN `shop_categories` ON `shop_categories`.`categories_of_commodities_ID`=`shop_commodities-categories`.`categoryID`
				WHERE `commodityID`='{$row2['com_id']}' AND `categories_of_commodities_parrent`='10' ";
				$res3=mysql_query($sql3);

				while($row3=mysql_fetch_assoc($res3))
				{
					$basket_com_cat=$row3["cat_name"];
				}

			//	$glb["templates"]->set_tpl('{$basket_com_cat}',$basket_com_cat);

				$sql4="SELECT * FROM `shop_filters-values`
				INNER JOIN `shop_filters-lists` ON `shop_filters-lists`.`id`=`shop_filters-values`.`ticket_value`
				WHERE `ticket_id`='{$com_id}' AND `ticket_filterid`='9' ";
				$res4=mysql_query($sql4);
				while($row4=mysql_fetch_assoc($res4))
				{
					$basket_com_color=$row4["list_name"];
				}
			//	$glb["templates"]->set_tpl('{$basket_com_color}',$basket_com_color);

			//    $price=get_true_price($row2["price"],$row2["cur_id"]);
				$url=$row2["alias"]!=""?"/pr{$row2["com_id"]}_{$row2["alias"]}/":"/pr{$row2["com_id"]}/";
				$src=$row2["alias"]!=""?"<img src='/{$row2["com_id"]}stitle/{$row2["alias"]}.jpg' style='height:30px;'>":"";
				$lines.="<tr id='{$row2["id"]}' rel='shop_orders_coms' rel2='id'><td>{$basket_com_cat}</td><td>{$row2["cod"]}</td><td>{$row2["com"]}, {$row4["list_name"]}</td><td>{$row2["count"]}</td><td>{$glb["cur"][$row2["cur_id"]]}</td><td>{$row2["commodity_price"]}</td><td>{$row2["commodity_price2"]}</td><td>{$row2["price"]}</td><td><a href='{$url}'>{$url}</a></td></tr>";
			}
		//	$price=get_oreder_sum($row["id"]);
			$sql="
			SELECT * FROM `shop_payments_methods` 
			ORDER BY `order`;";
			$res=mysql_query($sql);
			while($row3=mysql_fetch_assoc($res))
			{
				$payment_name=$row3['id']==$row['payment']?$row3['name']:$payment_name;
				$selected=$row3['id']==$row['payment']?"selected":"";
				$payments_lines.="<option value='{$row3['id']}' {$selected}>{$row3['name']}</option>";
			}
			$payments_lines=$payments_lines!=""?"<select id='id_sb_opl'>{$payments_lines}</select>":"";

			
			$sql="
			SELECT * FROM `shop_delivery` 
			ORDER BY `order`;";
			$res=mysql_query($sql);
			while($row3=mysql_fetch_assoc($res))
			{
				$delivery_name=$row3['id']==$row['delivery']?$row3['name']:$delivery_name;
			//	$selected=$row3['id']==$row['delivery']?"selected":"";
			//	$delivery_lines.="<option value='{$row3['id']}' {$selected}>{$row3['name']}</option>";
			}
			//$delivery_lines=$delivery_lines!=""?"<select id='id_sb_dost'>{$delivery_lines}</select>":"";
			
			// foreach($offer_status as $key=>$value)
			// {
			// 	$selected=$key==$row["status"]?"selected":"";
			// 	$status_name=$key==$row["status"]?$value:$status_name;
			// 	$status_lines.="<option value='{$key}' {$selected}>{$value}</option>";
			// }
			//$status_lines="<select id='id_status'>{$status_lines}</select>";
			$cur=$glb["cur"][$row["cur_id"]];
		//	$additions_buttons=get_edit_buttons2("/?admin=delete_order&id={$id}");
		//	$it_item="Редактирование заказа";
		//	require_once("modules/commodities/templates/admin.order_edit.php"); 
		//	require_once("templates/$theme_name/admin.edit.php"); 
		}
	
//-----------------------------------------------------------------------------------------------------

		require_once "includes/phpexcel/Classes/PHPExcel.php";
		require_once "includes/phpexcel/Classes/PHPExcel/Writer/Excel2007.php";

//	require_once '../includes/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';
	
	function cellColor($cells,$color){
    global $exc;

    $exc->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
	}	
	
	
	$id=2;	
	
	$exc = new PHPExcel();
	$exc->setActiveSheetIndex(0);
	$exc2=$exc->getActiveSheet();
	
	$a=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N");	
	$zak=array("Детали заказа2","Заказ №","Дата:","Имя:","Email:","Телефон:","Адрес:","Комментарий:");	
	$zakGet=array("", $row["cod"], $row["date"], $row["name"], $row["email"], $row["tel"], $row["address"], $row["user_comments"] ); 
	
	$tab=array("№","Бренд","Артикул","Цвет","Размер","Кол-во","Валюта","Цена","Сумма");	
	for($i=0; $i<count($a); $i++){
		$exc2->getColumnDimension($a[$i])->setAutoSize(true);
	}
	
	for($i=0; $i<count($zak); $i++){
		$exc2->SetCellValue('B'.$id, $zak[$i]);
		$exc2->getStyle('B'.$id)->getFont()->setBold(true);
		$exc2->getStyle('B'.$id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$exc2->SetCellValue('C'.$id, $zakGet[$i]);
		$exc2->getStyle('C'.$id)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$exc2->getRowDimension($id)->setRowHeight(15);
		//$exc2->getRowDimension('9')->setRowHeight(30);
		$id++;
	}
	$id+=2;

	for($i=0; $i<count($tab); $i++){
		$exc2->SetCellValue($a[$i].$id, $tab[$i]);
		$exc2->getStyle($a[$i].$id)->getFont()->setBold(true);
		$exc2->getStyle($a[$i].$id)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
		$exc2->getStyle($a[$i].$id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		cellColor($a[$i].$id,'5FC0D1');
		$exc2->getRowDimension($id)->setRowHeight(15);
	}
	$id++;
	$startF=$id;
	 	$sql2="
			SELECT * FROM `shop_orders_coms`
			LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
			WHERE `offer_id`='{$row["id"]}' AND `count`>0;";
			$res2=mysql_query($sql2);
			$j=1;	    	
	    	while($row2=mysql_fetch_assoc($res2))
			{
				$status = $row2["com_status"];
				
				$sql3="SELECT * FROM `shop_commodities-categories`
				INNER JOIN `shop_categories` ON `shop_categories`.`categories_of_commodities_ID`=`shop_commodities-categories`.`categoryID`
				WHERE `commodityID`='{$row2['com_id']}' AND `categories_of_commodities_parrent`='10' ";
				$res3=mysql_query($sql3);

				while($row3=mysql_fetch_assoc($res3))
				{
					$basket_com_cat=$row3["cat_name"];
				}
				$sql4="SELECT * FROM `shop_filters-values`
				INNER JOIN `shop_filters-lists` ON `shop_filters-lists`.`id`=`shop_filters-values`.`ticket_value`
				WHERE `ticket_id`='{$row2['com_id']}' AND `ticket_filterid`='9' ";
				$res4=mysql_query($sql4);
				while($row4=mysql_fetch_assoc($res4))
				{

					$basket_com_color=$row4["list_name"];
					
				}
				$color = "";
				if ($basket_com_color !=""){
					if($basket_com_color=="colorblack"){
						$color = "Черный";
					} elseif ($basket_com_color=="colorgray"){
						$color ="Серый";

					} elseif ($basket_com_color=="colorwhite"){
						$color ="Белый";

					} elseif ($basket_com_color=="colorred"){
						$color ="Красный";
					} elseif ($basket_com_color=="colorcoral"){
						$color ="Кораловый";
					} elseif ($basket_com_color=="colorgold"){
						$color ="Золотой";
					} elseif ($basket_com_color=="coloryellowgreen"){
						$color ="Светло-зеленый";
					} elseif ($basket_com_color=="colorgreen"){
						$color ="Зеленый";
					} elseif ($basket_com_color=="colorteal"){
						$color ="Бирюзовый";
					} elseif ($basket_com_color=="coloraqua"){
						$color ="Аква";
					} elseif ($basket_com_color=="colorskyblue"){
						$color ="Голубой";
					} elseif ($basket_com_color=="colorblue"){
						$color ="Синий";
					} elseif ($basket_com_color=="colornavy"){
						$color ="Темно-синий";
					} elseif ($basket_com_color=="colormagenta"){
						$color ="Малиновый";
					} elseif ($basket_com_color=="colordarkmagenta"){
						$color ="Темно-фиолетовый";
					} elseif ($basket_com_color=="colorthistle"){
						$color ="Сиреневый";
					} elseif ($basket_com_color=="colorlightpink"){
						$color ="Розовый";
					} elseif ($basket_com_color=="colorburlywood"){
						$color ="Бежевый";
					} elseif ($basket_com_color=="colorsienna"){
						$color ="Коричневый";
					} elseif ($basket_com_color=="colororange"){
						$color ="Оранжевый";
					} elseif ($basket_com_color=="colorprint"){
						$color ="Принт";
					} else {
						$color = $basket_com_color;
					}
				}
				$url=$row2["alias"]!=""?"/pr{$row2["com_id"]}_{$row2["alias"]}/":"/pr{$row2["com_id"]}/";
			if($status !=2){
				$tabSet=array($j,$basket_com_cat, $row2["cod"], $color, $row2["com"], $row2["count"], $glb["cur"][$row2["cur_id"]], $row2["price"],$row2["price"]*$row2["count"]);
				for($i=0; $i<count($tabSet); $i++){
					$exc2->SetCellValue($a[$i].$id, $tabSet[$i]);
					
					$exc2->getStyle($a[$i].$id)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
					$exc2->getStyle($a[$i].$id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
					$exc2->getCell($a[10].$id)->getHyperlink()->setUrl('http://makewear.com.ua'.$tabSet[10]);
					cellColor($a[0].$id,'5FC0D1');
					$exc2->getRowDimension($id)->setRowHeight(15);			
				}
					$sum+=$row2["count"];
					$sumPrice+=$row2["price"]*$row2["count"];
					$j++;
					$id++;
					$endF=$id-1;
				}
	}
	
	$exc2->SetCellValue('B'.$id, "ИТОГО:");
	$exc2->getStyle('B'.$id)->getFont()->setBold(true);	

	$exc2->SetCellValue('F'.$id, $sum);	
	$exc2->getStyle('F'.$id)->getFont()->setBold(true);
	
	$exc2->SetCellValue('I'.$id, $sumPrice);
	$exc2->getStyle('I'.$id)->getFont()->setBold(true);
	$exc2->getRowDimension($id)->setRowHeight(15);
	$id+=2;	


	if($getId>=441){
		$ski='';
		$gift="";
		$price3=$sumPrice;
		if($discount==1 && $sum>=5){
					// $ski='-150 грн';
					// $client_sum-=150;
			if($row["cur_id"]==1){
				$price3-=150;
				$ski='-150 грн';
			}
			if($row["cur_id"]==3){
				$price3-=500;
				$ski='-500 руб';
			}
			$client_sum+=$delivery_price;
		}elseif($discount==2 && $sum>=5){ 
			$ski='-10%';
			$client_sum-=$client_sum/100*10;
			$client_sum+=$delivery_price;
		}
		elseif($discount==3 && $sum>=5){
			$delivery_price="Бесплатная";
		}

		if($sumPrice>=1000){
			$gift="Платья";
		}

		$sp=array("Доставка","Скидка","Подарок","Всего к оплате:");	
		$spSet=array($delivery_price,$ski,$gift,$price3);	
	}else{
		$commission=round($sumPrice/100*3);

		$sumPrice+=$commission;
		$sumPrice+=$delivery_price;
		$sp=array("Коммиссия","Доставка","Всего к оплате:");		
		$spSet=array($commission, $delivery_price,$sumPrice);
	}
		
	for($i=0; $i<count($sp); $i++) {
		$exc2->SetCellValue('B'.$id, $sp[$i]);
		$exc2->SetCellValue('C'.$id, $spSet[$i]);
		
		$exc2->getStyle('B'.$id)->getFont()->setBold(true);		
		
		$exc2->getStyle('B'.$id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
		$exc2->getStyle('C'.$id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);		
		$exc2->getRowDimension($id)->setRowHeight(15);	
		$exc2->getStyle('C'.$id)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	

		if($i==count($sp)-1)
		$exc2->getStyle('C'.$id)->getFont()->setBold(true);	
		$id++;
	}
	$id++;
	
//	$exc2->SetCellValue('A'.($id+2), $delivery_name);
	
//	$writer = new PHPExcel_Writer_Excel2007($exc);
//	$writer->save('excel28.xlsx');

	$cod=str_replace("/","_",$row["cod"]);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="zakaz'.$cod.'.xls"');
	header('Cache-Control: max-age=0');
	
	$writer = PHPExcel_IOFactory::createWriter($exc, 'Excel5');
	$writer->save('php://output');
//	echo "Work excel";


?>