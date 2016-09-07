<?php
ini_set("max_execution_time", "99999");
set_time_limit(99999);
error_reporting(E_ALL^E_NOTICE);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");

	if(isset($_GET['down_edit_id'])){
		$id=$_GET['down_edit_id'];
		$site=$_GET['down_site'];
		$cont=$_GET['down_contact'];
		$pay=$_GET['down_payment'];
		$deli=$_GET['down_delivery'];
		
		$cont=str_replace("\n","<br/>",$cont);		
		
		$ss=mysql_query("SELECT * FROM `shop_orders_contact` WHERE `com_id`={$id}");
		$soc=mysql_fetch_assoc($ss);		
		
		if($soc){
			mysql_query("UPDATE `shop_orders_contact` SET `site`='{$site}',`contact`='{$cont}',`payment`='{$pay}',`delivery`='{$deli}' WHERE `com_id`={$id} ");
		}else{
			mysql_query("INSERT INTO `shop_orders_contact` (`com_id`,`site`,`contact`,`payment`,`delivery`) VALUES ('{$id}','{$site}','{$cont}','{$pay}','{$deli}'); ");
		}
		echo "work222";
	}

	if(isset($_GET['down_edit_id2'])){
		$id=$_GET['down_edit_id2'];
		$cont=$_GET['down_contact'];
		$pay=$_GET['down_payment'];
		$deli=$_GET['down_delivery'];
		
		$cont=str_replace("\n","<br/>",$cont);		
		
		$ss=mysql_query("SELECT * FROM `shop_orders_contact` WHERE `com_id`={$id}");
		$soc=mysql_fetch_assoc($ss);		
		
		if($soc){
			mysql_query("UPDATE `shop_orders_contact` SET `contact`='{$cont}',`payment`='{$pay}',`delivery`='{$deli}' WHERE `com_id`={$id} ");
		}else{
			mysql_query("INSERT INTO `shop_orders_contact` (`com_id`,`contact`,`payment`,`delivery`) VALUES ('{$id}','{$cont}','{$pay}','{$deli}'); ");
		}
		echo "work";
	}

	if(isset($_GET['json'])){
		$arr=array();
		$i=0;
		$js=mysql_query("SELECT * FROM `shop_orders_contact`");
		while($jj=mysql_fetch_assoc($js)){
			$id=$jj['com_id'];
			$site=$jj['site'];			
			$cont=$jj['contact'];
			$pay=$jj['payment'];
			$deli=$jj['delivery'];
			
			$arr[$i]=array("comid"=>$id, "site"=>$site, "cont"=>$cont, "pay"=>$pay, "deli"=>$deli);
			$i++;
		}
		echo json_encode($arr);	
		//echo "json work";	
	}

	if(isset($_GET['con_payment'])){
		$rel=$_GET["rel"];
		$name=$_GET["name"];
		$site=$_GET["site"];
		$info=$_GET["info"];

		$com_sex=$_GET["com_sex"];
		$com_sex=substr($com_sex, 0,strlen($com_sex)-1);
		$t_tab=$_GET["t_tab"];
		//$t_tab=substr($t_tab, 0,strlen($t_tab)-1);

		$sel_sk=$_GET["sel_sk"];
		$sel_ot=$_GET["sel_ot"];
		$sel_na=$_GET["sel_na"];
		$sel_do=$_GET["sel_do"];
		$sel_pr=$_GET["sel_pr"];
		$sel_pr_sk=$_GET["sel_pr_sk"];
		$sel_pr_do=$_GET["sel_pr_do"];
		$sel_pr_price=$_GET["sel_pr_price"];
		$ot_min_price=$_GET["ot_min_price"];
		$sel_pr_ot=$_GET["sel_pr_ot"];
		$sel_pr_na=$_GET["sel_pr_na"];
		$ot_min_pr_price=$_GET["ot_min_pr_price"];

		$cont_name=$_GET["cont_name"];
		$cont_phone=$_GET["cont_phone"];
		$cont_mail=$_GET["cont_mail"];
		$cont_dop=$_GET["cont_dop"];

		$op_pl=$_GET["op_pl"];
		$op_name=$_GET["op_name"];
		$op_bank=$_GET["op_bank"];
		$op_chet=$_GET["op_chet"];
		$op_dop=$_GET["op_dop"];
		$de_city=$_GET["de_city"];
		$de_cpo=$_GET["de_cpo"];
		$de_address=$_GET["de_address"];
		$de_get=$_GET["de_get"];
		$de_dop=$_GET["de_dop"];




		$bbb=mysql_query("SELECT * FROM `brenda_contact` WHERE `com_id`= '{$rel}' ")or die(mysql_error());
		$b=mysql_fetch_assoc($bbb);

		if(!$b){
			mysql_query("INSERT INTO `brenda_contact`(
				`com_id`,
				`bc_name`, 
				`bc_site`, 
				`bc_info`, 
				`uc_opt_skidka`, 
				`uc_opt_natsenka`, 
				`uc_opt_otgruz`, 
				`uc_opt_delivery`, 
				`uc_opt_price`, 
				`uc_pr_skidka`, 
				`uc_pr_delivery`, 
				`uc_pr_price`, 
				`rek_pa_plat`, 
				`rek_pa_name`, 
				`rek_pa_bank`, 
				`rek_pa_shet`, 
				`rek_pa_dop`, 
				`rek_de_sity`, 
				`rek_de_sposib`, 
				`rek_de_address`, 
				`rek_de_get`, 
				`rek_de_dop`, 
				`cont_dop`, 
				`cont_name`, 
				`cont_phone`, 
				`cont_mail`, 
				`bc_commodity`,
				`ot_min_price`,
				`uc_pr_natsenka`, 
				`uc_pr_otgruz`, 
				`ot_min_pr_price`,
				`bc_table_size`) 
			VALUES (
				'{$rel}',
				'{$name}',
				'{$site}',
				'{$info}',
				'{$sel_sk}',
				'{$sel_ot}',
				'{$sel_na}',
				'{$sel_do}',
				'{$sel_pr}',
				'{$sel_pr_sk}',
				'{$sel_pr_do}',
				'{$sel_pr_price}', 
				'{$op_pl}', 
				'{$op_name}', 
				'{$op_bank}', 
				'{$op_chet}', 
				'{$op_dop}', 
				'{$de_city}', 
				'{$de_cpo}', 
				'{$de_address}', 
				'{$de_get}', 
				'{$de_dop}', 
				'{$cont_dop}', 
				'{$cont_name}', 
				'{$cont_phone}', 
				'{$cont_mail}', 
				'{$com_sex}',
				'{$ot_min_price}',
				'{$sel_pr_na}',
				'{$sel_pr_ot}',
				'{$ot_min_pr_price}',
				'{$t_tab}'
				)") or die(mysql_error());
			echo "Сохранить";
		}
		else{
			mysql_query("UPDATE `brenda_contact` SET `bc_name`='{$name}',`bc_site`='{$site}',`bc_info`='{$info}', `uc_opt_skidka`='{$sel_sk}', `uc_opt_natsenka`='{$sel_na}', `uc_opt_otgruz`='{$sel_ot}', `uc_opt_delivery`='{$sel_do}', `uc_opt_price`='{$sel_pr}', `uc_pr_skidka`='{$sel_pr_sk}', `uc_pr_delivery`='{$sel_pr_do}', `uc_pr_price`='{$sel_pr_price}', `rek_pa_plat`='{$op_pl}', `rek_pa_name`='{$op_name}', `rek_pa_bank`='{$op_bank}', `rek_pa_shet`='{$op_chet}', `rek_pa_dop`='{$op_dop}', `rek_de_sity`='{$de_city}', `rek_de_sposib`='{$de_cpo}', `rek_de_address`='{$de_address}', `rek_de_get`='{$de_get}', `rek_de_dop`='{$de_dop}', `cont_dop`='{$cont_dop}', `cont_name`='{$cont_name}', `cont_phone`='{$cont_phone}', `cont_mail`='{$cont_mail}', `bc_commodity`='{$com_sex}', `ot_min_price`='{$ot_min_price}', `uc_pr_otgruz`='{$sel_pr_ot}', `uc_pr_natsenka`='{$sel_pr_na}', `ot_min_pr_price`='{$ot_min_pr_price}', `bc_table_size`='{$t_tab}' WHERE `com_id`= '{$rel}' ")or die(mysql_error());
			echo "Обновить";
		}
		
	}
	if(isset($_GET['contact_id'])){
		$arr=array();
		$id=$_GET['contact_id'];
		$ass=mysql_query("SELECT * FROM `brenda_contact` WHERE `com_id`='{$id}' ");
		$a=mysql_fetch_assoc($ass);

		$name=$a['bc_name'];
		$site=$a['bc_site'];
		$info=$a['bc_info'];

		$uc_opt_skidka=$a['uc_opt_skidka'];
		$uc_opt_natsenka=$a['uc_opt_natsenka'];
		$uc_opt_otgruz=$a['uc_opt_otgruz'];
		$uc_opt_delivery=$a['uc_opt_delivery'];
		$uc_opt_price=$a['uc_opt_price'];

		$uc_pr_skidka=$a['uc_pr_skidka'];
		$uc_pr_delivery=$a['uc_pr_delivery'];
		$uc_pr_price=$a['uc_pr_price'];
		$uc_pr_natsenka=$a['uc_pr_natsenka'];
		$uc_pr_otgruz=$a['uc_pr_otgruz'];

		$op_pl=$a['rek_pa_plat'];
		$op_name=$a['rek_pa_name'];
		$op_bank=$a['rek_pa_bank'];
		$op_chet=$a['rek_pa_shet'];
		$op_dop=$a['rek_pa_dop'];
		$de_city=$a['rek_de_sity'];
		$de_cpo=$a['rek_de_sposib'];
		$de_address=$a['rek_de_address'];
		$de_get=$a['rek_de_get'];
		$de_dop=$a['rek_de_dop'];

		$cont_name=$a['cont_name'];
		$cont_phone=$a['cont_phone'];
		$cont_mail=$a['cont_mail'];
		$cont_dop=$a['cont_dop'];

		$com_sex=$a['bc_commodity'];
		$ot_min_price=$a['ot_min_price'];
		$ot_min_pr_price=$a['ot_min_pr_price'];

		$t_tab=$a["bc_table_size"];

		$arr=array("name"=>$name, "t_tab"=>$t_tab, "site"=>$site, "info"=>$info,"ot_min_pr_price"=>$ot_min_pr_price, "uc_pr_otgruz"=>$uc_pr_otgruz,"uc_pr_natsenka"=>$uc_pr_natsenka,"uc_opt_skidka"=>$uc_opt_skidka,"uc_opt_natsenka"=>$uc_opt_natsenka,"uc_opt_otgruz"=>$uc_opt_otgruz,"uc_opt_delivery"=>$uc_opt_delivery,"uc_opt_price"=>$uc_opt_price, "uc_pr_skidka"=>$uc_pr_skidka,"uc_pr_delivery"=>$uc_pr_delivery,"uc_pr_price"=>$uc_pr_price, "op_pl"=>$op_pl, "op_bank"=>$op_bank, "op_name"=>$op_name, "op_chet"=>$op_chet, "op_dop"=>$op_dop, "de_city"=>$de_city, "de_cpo"=>$de_cpo, "de_address"=>$de_address, "de_get"=>$de_get, "de_dop"=>$de_dop, "cont_dop"=>$cont_dop, "cont_name"=>$cont_name, "cont_phone"=>$cont_phone, "cont_mail"=>$cont_mail, "com_sex"=>$com_sex, "ot_min_price"=>$ot_min_price);
		echo json_encode($arr);

	}

?>