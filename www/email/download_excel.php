<?php
ini_set("max_execution_time", "99999");
set_time_limit(99999);
error_reporting(E_ALL ^ E_NOTICE);
require_once("../settings/conf.php");
require_once("../settings/connect.php");
require_once("../modules/commodities/site/getcolor.php");

//===================Export XLS===============================================
if (isset($_GET["exportIdd"])) {
    $id = $_GET["exportIdd"];

    //echo $id;

    require_once '../includes/phpexcel/Classes/PHPExcel.php';
    require_once '../includes/phpexcel/Classes/PHPExcel/Writer/Excel2007.php';

    $num    = 1;
    $tabSet = array();

    $monthes = array(1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
        5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
        9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря');
    $today   = date("d")." ".$monthes[date("n")]." ".date("Y");

    $sql = "SELECT * FROM `shop_orders` WHERE `id`='{$id}' ORDER BY `id` DESC";
    $res = mysql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {
        $offer_id       = $row["id"];
        $date           = $row["date"];
        $name           = $row["name"];
        $order_cod      = $row["cod"];
        $art[$offer_id] = $row["cod"];
        $status         = $row["status"];
        $email          = $row['email'];
        $order_count    = 0;
        $orders_head    = "";
       // $comission      = $row["commission"];
        $delivery_price = $row["delivery_price"];
        $tel            = $row['tel'];
        $tel            = str_replace(" ", "", $tel);
        $city           = $row['city'];
        $address        = $row['address'];
        $client         = "{$name}<br>{$tel}<br>{$email}<br>{$city}<br>{$address}";
        $client_xls     = "{$name}\r\n{$tel}\r\n{$email}\r\n{$city}\r\n{$address}";
    }

    $sql2 = "
		SELECT * FROM `shop_orders_coms`
		LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
		WHERE `offer_id`='{$id}' AND `count`>0;";
    $res2 = mysql_query($sql2);
    while ($row2 = mysql_fetch_assoc($res2)) {
        $com_id = $row2["com_id"];
        $size   = $row2["com"];
        if ($size == "(undefined)") {
            $size = "";
        }
        $count   = $row2["count"];
        $cur_id2 = $row2["cur_id"];

        $cur_q    = mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`={$cur_id2}");
        $curr     = mysql_fetch_assoc($cur_q);
        $cur_name = $curr['cur_name'];
        $cur_id   = $curr['cur_show'];

        $cod2      = $row2["cod"];
        $price_opt = $row2["commodity_price2"];
        $from_url  = $row2["from_url"];
        $com_name  = $row2["com_name"];
        $color     = get_color_to_order($com_id);
        $comment   = $row2["man_comment"];

        $price   = $row2["price"] * $count;
        $com_sum = $row2["price"];

        $com_selected1 = "";
        $com_selected2 = "";
        $com_selected3 = "";
        $com_selected4 = "";
        $com_selected5 = "";
        $com_selected0 = "";
        $com_selected6 = "";
        $order_com_id  = $row2["id"];
        $linecolor     = "";
        $status_com    = $row2["com_status"];
        if ($status_com == 1) {
            $com_selected1 = "selected";
            $linecolor     = "greenline";
        } elseif ($status_com == 2) {
            $com_selected2 = "selected";
            $linecolor     = "redline";
            $offer_sum["{$offer_id}"] -=$price;
        } elseif ($status_com == 3) {
            $com_selected3  = "selected";
            $com2_selected3 = "selected";
        } elseif ($status_com == 0) {
            $com_selected0 = "selected";
        } elseif ($status_com == 4) {
            $com_selected4  = "selected";
            $com2_selected4 = "selected";
            $payment+=$com_sum;
        } elseif ($status_com == 5) {
            $com_selected5 = "selected";
            $payment_wait+=$com_sum;
        } elseif ($status_com == 6) {
            $com_selected6 = "selected";
        }


        $sql3 = "SELECT * FROM `shop_commodities-categories`
			INNER JOIN `shop_categories` ON `shop_categories`.`categories_of_commodities_ID`=`shop_commodities-categories`.`categoryID`
			WHERE `commodityID`='{$row2['com_id']}' AND `categories_of_commodities_parrent`='10' ";
        $res3 = mysql_query($sql3);

        if ($row3 = mysql_fetch_assoc($res3)) {
            $basket_com_cat = $row3["cat_name"];
        }
        $offer_sum["{$offer_id}"] +=$price;
        $order_count += $count;
        //--For mail---
		if($status_com != 2){
        $tab_com.='<tr>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$num.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$basket_com_cat.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$cod2.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$color.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$size.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$count.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$com_sum.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$cur_id.'</td>
					<td style="border: 1px solid #8A8A9B; text-align: center;">'.$price.'</td><tr>';
        $tab_com2[$offer_id][$num] = array('num' => $num, 'com_cat' => $basket_com_cat,
            'cod' => $cod2, 'color' => $color, 'size' => $size, 'count' => $count,
            'price' => $com_sum, 'cur' => $cur_id, 'com_sum' => $price);
        $tabSet[$num]              = array($num, $basket_com_cat, $cod2, $color,
            $size, $count, $com_sum, $cur_id, $price);

        $num++;
		}
    }

    if($id>=441){
            $ski='';
            $gift="";
            $price=$offer_sum[$offer_id];
            if($discount==1 && $total_count>=5){
                    // $ski='-150 грн';
                    // $price-=150;
                    if($row["cur_id"]==1){
                        $price-=150;
                        $ski='-150 грн';
                    }
                    if($row["cur_id"]==3){
                        $price-=500;
                        $ski='-500 руб';
                    }
                    $price3+=$row['delivery_price'];
                }elseif($discount==2 && $total_count>=5){ 
                    $ski='-10%';
                    $price-=$price/100*10;
                    $price+=$delivery_price;
                }
                elseif($discount==3 && $total_count>=5){
                    $delivery_price="Бесплатная";
                }
                if($offer_sum[$offer_id]>=1000){
                    $gift="Платья";
                }
            $skidka=$ski;
            $summm=$price;

        }else{
            $comission=round($offer_sum[$offer_id]/100*3);
            $summm = $offer_sum[$offer_id];
            $summm +=$comission;
            $summm +=$delivery_price;
            $payment_all+=$summm;
            $commissia=$comission." {$cur_name}";
        }
        $offer_sum2= $offer_sum["{$offer_id}"]." {$cur_name}";
        $del=$delivery_price." {$cur_name}";
        $sum_pri=$summm." {$cur_name}";
    
    // $comission=round($offer_sum["{$offer_id}"]/100*3);

    // $summm = $offer_sum["{$offer_id}"];
    // $summm +=$comission;
    // $summm +=$delivery_price;


    // $offer_sum2 = $offer_sum["{$offer_id}"]." {$cur_name}";
    // $del        = $delivery_price." {$cur_name}";
    // $commissia  = $comission." {$cur_name}";
    // $sum_pri    = $summm." {$cur_name}";


    $id = 2;

    $exc  = new PHPExcel();
    $exc->setActiveSheetIndex(0);
    $exc2 = $exc->getActiveSheet();

    $a   = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M",
        "N");
    $tab = array("№", "Бренд", "Артикул", "Цвет", "Размер", "Кол-во", "Цена", "Валют",
        "Сумма");
    foreach (range('A', 'Z') as $key) {
        $exc2->getColumnDimension($key)->setAutoSize(true);
    }

    //for($i=0; $i<count($zak); $i++){
    $exc2->SetCellValue('B'.$id, "Плательщик:");
    cellColor('B'.$id, '5FC0D1');
    $exc2->getStyle('B'.$id)->getFont()->setBold(true);
    $exc2->getStyle('B'.$id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $exc2->getRowDimension($id)->setRowHeight(15);
    $id++;

    $exc2->SetCellValue('B'.$id, $client_xls);
    $exc2->getStyle('B'.$id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $exc2->getRowDimension($id)->setRowHeight(75);
    $id++;
    //}

    $id++;
    for ($i = 0; $i < count($tab); $i++) {
        $exc2->SetCellValue($a[$i].$id, $tab[$i]);
        $exc2->getStyle($a[$i].$id)->getFont()->setBold(true);
        $exc2->getStyle($a[$i].$id)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $exc2->getStyle($a[$i].$id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        cellColor($a[$i].$id, '5FC0D1');
        $exc2->getRowDimension($id)->setRowHeight(15);
    }
    $id++;
    for ($i = 1; $i <= count($tabSet); $i++) {
        for ($j = 0; $j < count($tabSet[$i]); $j++) {
            $exc2->SetCellValue($a[$j].$id, $tabSet[$i][$j]);
            $exc2->getStyle($a[$j].$id)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $exc2->getStyle($a[$j].$id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            //$exc2->getCell($a[10].$id)->getHyperlink()->setUrl('http://makewear.com.ua'.$tabSet[10]);
            cellColor($a[0].$id, '5FC0D1');
            $exc2->getRowDimension($id)->setRowHeight(15);
        }
        $id++;
    }
    $id++;

    $exc2->SetCellValue('B'.$id, "ИТОГО:");
    $exc2->getStyle('B'.$id)->getFont()->setBold(true);

    //	$exc2->SetCellValue('F'.$id, $sum);
    //	$exc2->getStyle('F'.$id)->getFont()->setBold(true);

    $exc2->SetCellValue('I'.$id, $offer_sum2);
    $exc2->getStyle('I'.$id)->getFont()->setBold(true);
    $exc2->getRowDimension($id)->setRowHeight(15);

    $id+=2;

    $sp    = array("Доставка", "Комиссия", "Итого к оплате:");
    $spSet = array($del, $commissia, $sum_pri);
    for ($i = 0; $i < count($sp); $i++) {
        $exc2->SetCellValue('B'.$id, $sp[$i]);
        $exc2->SetCellValue('C'.$id, $spSet[$i]);

        $exc2->getStyle('B'.$id)->getFont()->setBold(true);

        $exc2->getStyle('B'.$id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $exc2->getStyle('C'.$id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $exc2->getRowDimension($id)->setRowHeight(15);
        $id++;
    }
    $id--;
    $phpColor = new PHPExcel_Style_Color();
    $phpColor->setRGB('FFFFFF');

    $exc2->getStyle('C'.$id)->getFont()->setColor($phpColor);
    cellColor('C'.$id, '355177');
    $id++;


    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="zakaz'.$order_cod.'.xls"');
    header('Cache-Control: max-age=0');

    $writer = PHPExcel_IOFactory::createWriter($exc, 'Excel5');
    $writer->save('php://output');
    //require_once '../modules/commodities/admin/import_xls.php';
    //echo "<script>window.open('/?admin=all_orders','_self');</script>";
}

function cellColor($cells, $color)
{
    global $exc;

    $exc->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'rgb' => $color
        )
    ));
}
