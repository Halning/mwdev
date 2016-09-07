<?php
	// Send Mail
	$tab="<table class='tab_order2'>
			<th>№</th>
			<th>Бренд</th>
			<th>Артикул</th>
			<th>Цвет</th>
			<th>Размер</th>
			<th>Кол-во</th>
			<th>Цена</th>
			<th>Валюта</th>
			<th>Сумма</th>
		</table>";

	$monthes = array(1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
				    5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
				    9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря');
	$today=date("d")." ".$monthes[date("n")];


	if (isset($_GET['mail'])) {
		
		$to=$_GET['to'];
		$to=str_replace(" ","", $to);
		//$to.=", m.kosovec@makewear.com.ua";
		//$to.=", k.beregovsky@makewear.com.ua";
		$subject=$_GET['subject'];
		$off_id=$_GET['off_id'];
		$art=$_GET['art'];
		$cli=$_GET['cli'];
		$tab=$_GET['tab2'];
		$sum=$_GET['off_sum'];

		$sump=$_GET['sump'];
		$dost=$_GET['dost'];
		$comm=$_GET['comm'];

		//$tab=json_decode($tab);
		echo $tab['com_cat'];
	//	$massega=$send_mail;
		foreach ($tab as $i => $value) {
			$tab_com.='<tr>
				<td style="border: 1px solid #8A8A9B; text-align: center;">'.$tab[$i]['num'].'</td>
				<td style="border: 1px solid #8A8A9B; text-align: center;">'.$tab[$i]['com_cat'].'</td>
				<td style="border: 1px solid #8A8A9B; text-align: center;">'.$tab[$i]['cod'].'</td>
				<td style="border: 1px solid #8A8A9B; text-align: center;">'.$tab[$i]['color'].'</td>
				<td style="border: 1px solid #8A8A9B; text-align: center;">'.$tab[$i]['size'].'</td>
				<td style="border: 1px solid #8A8A9B; text-align: center;">'.$tab[$i]['count'].'</td>
				<td style="border: 1px solid #8A8A9B; text-align: center;">'.$tab[$i]['price'].'</td>
				<td style="border: 1px solid #8A8A9B; text-align: center;">'.$tab[$i]['cur'].'</td>
				<td style="border: 1px solid #8A8A9B; text-align: center;">'.$tab[$i]['com_sum'].'</td><tr>';	
		}

		$massega='<div tyle="font-family: Arial,Helvetica,sans-serif; color: black;" >
					<div class=mw_logo >
						<img src="http://www.makewear.com.ua/email/images/mw_logo.jpg" style="width: 110px;margin-left: 20px;" />
						<span style="font-size: 25px; float: right; margin-right: 5px; margin-top: 35px; color: #54A5B2;">СЧЕТ</span>
					</div>
					<table style="width:100%"">
						<tr><td>
						<div style="color: black; font-size: 11px; border: 1px solid; width: 248px; padding: 5px; border-color: #8A8A9B;" >
							Киев<br/>
							Телефон: +38(099)098-00-82<br/>
									+38(098)615-39-19<br/>
							connect@makewear.com.ua<br/>
							Карта ПриватБанка 4149 6258 0147 0848<br/>
							(Береговский Кирилл Валериевич)
						</div>
						</td><td align="right">
							<table style="border-collapse: collapse; color: black;">
								<tr><td style="width:95px"><b>Дата</b></td><td style="border: 1px solid #8A8A9B; width: 120px; padding: 3px;" >'.$today.'</td></tr>
								<tr><td><b>Номер счета</b></td><td style="border: 1px solid #8A8A9B; width: 120px; padding: 3px;" ><span class="art">'.$art.'</span></td></tr>
								<tr><td><b>Основание:</b></td><td style="border: 1px solid #8A8A9B; width: 120px; padding: 3px;" >Заказ № '.$art.'</td></tr>
							</table>
						</td></tr>
					</table>
					<br/>
					<table style="border-collapse: collapse; width: 100%">
						<tr style="border: 1px solid #8A8A9B;"><td style="background: #54A5B2; color: white; font-size: 14px; padding: 3px;" ><b>Плательщик:</b></td></tr>
						<tr style="border: 1px solid #8A8A9B;"><td class="pl_client2" style="font-size: 17px; padding: 3px; color: black;" >'.$cli.'</td></tr>
					</table>
					<br/>
					<table class="tab_order2" style="border-collapse: collapse; width: 100%; color: black; font-size: 14px;" >
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">№</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Бренд</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Артикул</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Цвет</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Размер</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Кол-во</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Цена</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Валюта</th>
						<th style="padding: 4px; background: #54A5B2; border: 1px solid #8A8A9B;">Сумма</th>
						<tbody>'.$tab_com.'</tbody>
					</table>
					<div style="color:black; margin-left: 30px; padding-top: 10px; font-size: 14px;">Итого <span class="all_price2" style="float: right; padding-right: 20px;">'.$sum.'</span></div>
					<br/>
					<table style="width:100%"">
						<tr><td>
							<div style="color: #000; font-size: 11px; border: 3px solid #8A8A9B; width: 266px; padding: 5px; background: #DDD;" >
								Все чеки подлежат уплате компании MakeWear. По<br/>
								всем вопросам, касающимся этого  счета-фактуры,<br/>
								обращайтесь по телефонам<br/>
								+38(099)098-00-82<br/>
								+38(098)615-39-19<br/>
								или адресу connect@makewear.com.ua<br/>
								<br/>
								<center><b>Благодарим за сотрудничество!</b></center>
								<br/>
							</div>
						</td><td align="right">
							<table style="color:black; border-collapse: collapse; margin-right: 20px; font-size: 14px; font-weight: bold;" >
								<tr><td style="padding: 5px;" align="right">Доставка</td><td style="padding: 5px;" class="dost" align="center">'.$dost.'</td></tr>
								<tr><td style="padding: 5px;" align="right">Комиссия</td><td style="padding: 5px;" class="comm" align="center">'.$comm.'</td></tr>
								<tr><td style="padding: 5px;" align="right">Итого к оплате</td><td class="sum" style="padding: 5px; background: #355177; color: white;" align="center">'.$sump.'</td></tr>
							</table>
						</td></tr>
					</table>
					<hr>
					<div style="margin:12px">
						<center>
							<a href="http://makewear.com.ua/email/download_excel.php?exportIdd='.$off_id.'" style="border: 1px solid #3F2020;padding: 5px;font-size: 13px;background: #9A9A9E;border-radius: 5px;color: white;">Скачать XLS</a>
							<a href="http://makewear.com.ua/email/print_client.php?cli_id='.$off_id.'" style="border: 1px solid #3F2020;padding: 5px;font-size: 13px;background: #9A9A9E;border-radius: 5px;color: white;">Печать</a>
						</center>
					</div>
				</div>';

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
	

		// More headers
		$headers .= 'From: connect@makewear.com.ua' . "\r\n";
		//$headers .= 'Cc: myboss@example.com' . "\r\n";

		if(mail($to,$subject,$massega,$headers))
			echo "Отправил в {$to}";
		else
			echo "Ощибка! Ел.пошта. ";

	}

	if (isset($_GET['mail2'])) {
		
		$to=$_GET['to'];
		$to=str_replace(" ","", $to);
		//$to.=", m.kosovec@makewear.com.ua";
		$to.=", k.beregovsky@makewear.com.ua";
		$subject=$_GET['subject'];
		$massega=$_GET['message'];

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
	

		// More headers
		$headers .= 'From: connect@makewear.com.ua' . "\r\n";
		//$headers .= 'Cc: myboss@example.com' . "\r\n";

		if(mail($to,$subject,$massega,$headers))
			echo "Отправил в {$to}";
		else
			echo "Ощибка! Ел.пошта. ";

	}
	
?>