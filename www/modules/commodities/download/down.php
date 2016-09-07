<?php

require_once "../../../includes/phpexcel/Classes/PHPExcel.php";
require_once "../../../includes/phpexcel/Classes/PHPExcel/Writer/Excel2007.php";
include "commodity.class.php";


	$order=commodity::getOrders();

	$exc = new PHPExcel();
	$exc->setActiveSheetIndex(0);
	$exc2=$exc->getActiveSheet();
	$ii=1;

	$a=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","S","T","U","V","W","X");	
	$tab=array("ID","Дата","Email", "Телефон", "Ф.И.О", "Cумма");	
	for($i=0; $i<count($a); $i++){
		$exc2->getColumnDimension($a[$i])->setAutoSize(true);
	}

	for($i=0; $i<count($tab); $i++){
		$exc2->SetCellValue($a[$i].$ii, $tab[$i]);
		$exc2->getStyle($a[$i].$ii)->getFont()->setBold(true);
	}
	$ii=2;

		// $res=$db->query("SELECT a.`id` , a.`email` , a.`tel` , a.`name` , b.`offer_id` , b.`count` , b.`price` , b.`com_status` , SUM( b.`price` * b.`count` ) AS summa
		// 	FROM  `shop_orders` AS a
		// 	INNER JOIN  `shop_orders_coms` AS b ON a.`id` = b.`offer_id` 
		// 	WHERE a.`id` > 446
		// 	AND b.`com_status` <> 2
		// 	GROUP BY a.`id`");
		// while($v=$res->fetch_assoc()){
		// 	// $arr[$v['id']]=array(
		// 	// 	'id'=> $v['id'],
		// 	// 	'tel'=> $v['tel'],
		// 	// 	'eamil'=> $v['eamil'],
		// 	// 	'name'=> $v['name'],
		// 	// 	'summa'=> $v['summa']
		// 	// );
		// 	if($v['summa'] > 1000){
		// 		echo $v['id'].", ".$v["email"].", ".$v['tel'].", ".$v['name'].", ".$v['summa']."<br/>";
		// 	}
		// }

	foreach ($order as $k => $v) {
		if($v['summa'] > 1000){
			//echo $v['id'].", ".$v["email"].", ".$v['tel'].", ".$v['name'].", ".$v['summa']."<br/>";
			$exc2->SetCellValue('A'.$ii, $v['id']);
			$exc2->SetCellValue('B'.$ii, $v['date']);
			$exc2->SetCellValue('C'.$ii, $v['email']);
			$exc2->SetCellValue('D'.$ii, $v['tel']);
			$exc2->SetCellValue('E'.$ii, $v['name']);
			$exc2->SetCellValue('F'.$ii, $v['summa']);
			$ii++;
		}
	}


	$today = date("d/m/Y"); 
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="orders'.$today.'.xls"');
	header('Cache-Control: max-age=0');
	
	$writer = PHPExcel_IOFactory::createWriter($exc, 'Excel5');
	$writer->save('php://output');

?>