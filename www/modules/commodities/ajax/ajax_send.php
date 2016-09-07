<?php

//namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = $glb['mysqli'];

require_once("../../phpmailer/PHPMailerAutoload.php");


	if(isset($_GET['id'])){
		$id=$_GET['id'];

		// $iii=mysql_query("SELECT `com_id`,`cont_mail`
		// 		FROM `brenda_contact`
		// 		WHERE `com_id` ={$id}
		// 	");
		// $i=mysql_fetch_assoc($iii);

		$result=$db -> query(<<<QUERY1
				SELECT `com_id`,`cont_mail`
				FROM `brenda_contact`
				WHERE `com_id` ={$id};
QUERY1
		);

		$row = $result->fetch_assoc();

		echo str_replace("0;","",$row['cont_mail']);
	}
	if(isset($_POST['where'])){
		$to=$_POST['where'];
		$whom=$_POST['whom'];
		$subject=$_POST['sub'];
		$massega=$_POST['txt'];


		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.mail.ru';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'sales@makewear.com.ua';                 // SMTP username
		$mail->Password = 'a12345';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to
		$mail->CharSet = "UTF-8";
		$mail->setFrom($whom, 'MakeWear');
		$mail->addAddress($to);     // Add a recipient
		$mail->isHTML(true);                              


		$mail->Subject = $subject;
		$mail->Body    = $massega;


		$massega=str_replace("'", "\'", $massega);
		$massega=str_replace('"', '\"', $massega);

		//$flag=0;

		if(!$mail->send()) {
		    echo "Message could not be sent.\n";
		    echo 'Mailer Error: ' . $mail->ErrorInfo."\n";
		} else {
		   // echo "Message has been sent\n";
		   	echo "Отправил в {$to}";
		   //	$flag=1

			$today = date("d.m.Y H:i:s");
			 
			if($_POST["status_ob"]==1){
			 	$db->query(<<<QUERY1
        		UPDATE `sup_group` SET `sent_email`=`sent_email`+1, `sent_date`='{$today}' WHERE `group_id`='{$_POST["id"]}';
QUERY1
	);
			}
			if($_POST["status_oc"]==1){
			 	// mysql_query("UPDATE `shop_orders` SET `sent_mail`=`sent_mail`+1, `sent_mail_save`='{$massega}'  WHERE `id`='{$_POST["id"]}'; ")or die(mysql_error());
			 	$db->query(<<<QUERY1
        		UPDATE `shop_orders` SET `sent_mail`=`sent_mail`+1, `sent_mail_save`='{$massega}'  WHERE `id`='{$_POST["id"]}';
QUERY1
    );
			}
			if($_POST["deli_sent"]==1){
			 	// mysql_query("UPDATE `sup_group` SET `delivery_sent`=`delivery_sent`+1 WHERE `group_id`='{$_POST["id"]}'; ")or die(mysql_error()); 
			 	$db->query(<<<QUERY1
        		UPDATE `sup_group` SET `delivery_sent`=`delivery_sent`+1 WHERE `group_id`='{$_POST["id"]}';
QUERY1
    );
			}
			if($_POST["payment_dd"]==1){
			 	// mysql_query("UPDATE `sup_group` SET `payment_sent_mail`=`payment_sent_mail`+1 WHERE `group_id`='{$_POST["id"]}'; ")or die(mysql_error()); 
			 	$db->query(<<<QUERY1
        		UPDATE `sup_group` SET `payment_sent_mail`=`payment_sent_mail`+1 WHERE `group_id`='{$_POST["id"]}';
QUERY1
    );
			}
			if($_POST["payment_k"]==1){
			 	// mysql_query("UPDATE `shop_orders` SET `payment_sent_mail`=`payment_sent_mail`+1, `payment_old_price`='{$_POST["price"]}' WHERE `id`='{$_POST["id"]}'; ")or die(mysql_error()); 
			 	$db->query(<<<QUERY1
        		UPDATE `shop_orders` SET `payment_sent_mail`=`payment_sent_mail`+1, `payment_old_price`='{$_POST["price"]}' WHERE `id`='{$_POST["id"]}';
QUERY1
    );
			}
			 
			if($_POST["sent_mw_k"]==1){
			 	// mysql_query("UPDATE `shop_orders` SET `sent_mail_mw_k`=`sent_mail_mw_k`+1 WHERE `id`='{$_POST["id"]}'; ");
			 	$db->query(<<<QUERY1
        		UPDATE `shop_orders` SET `sent_mail_mw_k`=`sent_mail_mw_k`+1 WHERE `id`='{$_POST["id"]}';
QUERY1
    );
			}
			//echo $massega;

			$idNew=$_POST["id"];
			echo " - ".$idNew." ";
			if(strpos($idNew,"_")!==false){
				$so=explode("_",$idNew);
				echo " ".$so[0].":".$so[1];
				if($_POST["status_ob"]==1){
			 		// mysql_query("UPDATE `shop_order_supplier` SET `sent_email`=1 WHERE `order_id`='{$so[0]}' AND `supplier_name_id`='{$so[1]}'; "); 
			 		$db->query(<<<QUERY1
        		dbplus_update(relation, old, new) `shop_order_supplier` SET `sent_email`=1 WHERE `order_id`='{$so[0]}' AND `supplier_name_id`='{$so[1]}'; 
QUERY1
    );
				}
				if($_POST["payment_dd"]==1){
				 	// mysql_query("UPDATE `shop_order_supplier` SET `payment_sent_mail`=1 WHERE `order_id`='{$so[0]}' AND `supplier_name_id`='{$so[1]}'; "); 
				 	$db->query(<<<QUERY1
        			UPDATE `shop_order_supplier` SET `payment_sent_mail`=1 WHERE `order_id`='{$so[0]}' AND `supplier_name_id`='{$so[1]}';
QUERY1
    );
				}
			}
		}
	}

?>