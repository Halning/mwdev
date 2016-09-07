<?
if ($_SESSION['status']=="admin"){

include "soz.php";

	if(isset($_GET["sel"])){
		$upsel=$_GET["sel"];
		$upgroup=$_GET['group'];
	}
 

	$result_sum=0;
	$numssd=0;
	$numssd2=array();
	$result_quantity=0;
	$result_sum_to_pay=0;
	$result_sum_to_pay2=0;
	$result_sum_to_pay3=0;
	$result_profit=0;
	

	$client_count_all=0;
	$client_count_buy=0;
	$client_count_wait=0;
	
	$add_price_all=0;
	$add_price_buy=0;
	$add_price_wait=0;

	$add_price_all_usd=0;
	$add_price_buy_usd=0;
	$add_price_wait_usd=0;

	$add_price_all_rub=0;
	$add_price_buy_rub=0;
	$add_price_wait_rub=0;


// Pagination LIMIT


	if(isset($_POST["sel_link"])){
		$_SESSION["select_link"]=$_POST["sel_link"];
	}
	if(isset($_SESSION["select_link"])){
		$limit_rows=$_SESSION["select_link"];
		switch ($limit_rows) {
			case 10:
				$limitSelected10="selected";
				break;
			case 25:
				$limitSelected25="selected";
				break;
			case 50:
				$limitSelected50="selected";
				break;
			case 75:
				$limitSelected75="selected";
				break;
			case 100:
				$limitSelected100="selected";
				break;
			default:

				break;
		}
	}else{
		$limit_rows=100;
	}


	$pagge=$_GET['p'];
	$archive=$_GET['archive'];

	//echo $archive.": ".$_GET["p"];
	if($archive=='true'){
		$status="`status` in (5,6,7)";
		$status2="`status` < 11";
		$but_orr2="but_nn_active";
		$but_style2="border-bottom: 0px solid gray;color: black;";

		$fromDate=$_GET["fromData"];
		$toDate=$_GET["toData"];
		if(isset($fromDate)){
			if($fromDate!="0" && $toDate=="0"){
				$fromToDate="`date` > '{$fromDate}' ";
			}
			if($fromDate=="0" && $toDate!="0"){
				$fromToDate="`date` <= '{$toDate}' ";
			}
			if($fromDate!="0" && $toDate!="0"){
				$fromToDate=" `date` > '{$fromDate}' AND `date` <= '{$toDate}' ";
			}

			$fromToDate=" AND ".$fromToDate;
		}else{
			$fromToDate=" ";
		}

	}else{
		$status="`status` in (3,4,11)";
		$status2="a.`status` in (4,12,13) ";
		$but_orr1="but_nn_active";
		$but_style1="border-bottom: 0px solid gray;color: black;";
	}

	$mysql_len=mysql_query("
		SELECT * 
		FROM  `sup_group` 
		WHERE {$status}
		ORDER BY `sup_group`.`group_id` DESC
	");
	$lengths=mysql_num_rows($mysql_len);


	if(isset($archive)){
	if($lengths>0){
		if($pagge){
			$page=$pagge;
		}else{
			$page=1;
		} 
		$pages=$limit_rows*($page-1);
		$limit="LIMIT {$pages}, {$limit_rows}";
		$length_links=ceil($lengths/$limit_rows);

		$server=$_SERVER["REQUEST_URI"];
		if(strpos($server, "&p=")!==flase){
			$server=str_replace("&p={$page}","",$server);
		}


		$links="<div class='links'><div class='links_div'><ul class='links_li'>";
		$page1=$page-1;
		if($page<=1){
			// $links.="<li style='color:gray' ><<</li>";
		}else{
			$links.="<a href='{$server}&p={$page1}' ><li><<</li></a>";
		}

		if($length_links>10){
			if($page!=1){
				$links.="<a href='{$server}&p=1' ><li>1</li></a>";
			}
			$page_prev=$page-1;
			$page_prev2=$page-2;
			$page_prev3=$page-3;

			$page_next=$page+1;
			$page_next2=$page+2;
			$page_next3=$page+3;

			if($page > 4){
				$links.="<li class='leng_span'> <span>. . .</span> </li>";
			}
			if($page > 3){
				$links.="<a href='{$server}&p={$page_prev2}' ><li>{$page_prev2}</li></a>";
			}
			if($page > 2){
				$links.="<a href='{$server}&p={$page_prev}' ><li>{$page_prev}</li></a>";
			}	

			$links.="<li class='active'>{$page}</li>"; // active

			if($page < $page+1 && $page <= $length_links-2){
				$links.="<a href='{$server}&p={$page_next}' ><li>{$page_next}</li></a>";
			}
			if($page < $page+2 && $page <= $length_links-3){
				$links.="<a href='{$server}&p={$page_next2}' ><li>{$page_next2}</li></a>";
			}
			if($page < $length_links-3){
				$links.="<li class='leng_span'> <span>. . .</span> </li>";
			}
			if($page != $length_links){
				$links.="<a href='{$server}&p={$length_links}' ><li>{$length_links}</li></a>";
			}
			
		}else{
			for($i=1; $i<=$length_links; $i++){
				if($page==$i){
					$links.="<li class='active'>{$i}</li>";
				}else{
					$links.="<a href='{$server}&p={$i}' ><li>{$i}</li></a>";
				}
			}
		}

		$page2=$page+1;
		if($page2 == $length_links+1){
			//$links.="<li style='color:gray' >>></li>";
		}else{
			$links.="<a href='{$server}&p={$page2}' ><li>>></li></a>";
		}
		$links.="</ul></div></div>";
		$links.="<form active='{$server}' method='POST' id='links_select'>
						<select onchange=\"this.form.submit()\" name='sel_link' style='margin-left: 10px;' >
							<option value='10' {$limitSelected10}>10</option>
							<option value='25' {$limitSelected25}>25</option>
							<option value='50' {$limitSelected50}>50</option>
							<option value='75' {$limitSelected75}>75</option>
							<option value='100' {$limitSelected100}>100</option>
						</select>
					</form>
					<br/>";
	}
	}else{
		$limit="LIMIT 200";
	}
//-----End Pagination------	

	$ress=mysql_query("
		SELECT * 
		FROM  `sup_group`
		WHERE {$status}{$fromToDate}
		ORDER BY `group_id` DESC {$limit};
	");
	while($roww=mysql_fetch_assoc($ress)){
		$group_id=$roww["group_id"];
		$date[$group_id]=$roww["date"];

		$statusGroup=$roww["status"];
		// $groupName=SOZ::getStatusGroup($statusGroup);
		// $addOption="<option value='{$statusGroup}' selected>{$groupName}</option>";
		//echo SOZ::getStatusGroup($statusGroup).", ";

	$sql = "SELECT * 
	FROM  `shop_orders` AS a
	INNER JOIN  `shop_orders_coms` AS b ON a.`id` = b.`offer_id` 
	WHERE b.`group_id`={$group_id} AND {$status2}; ";

	//$sql = "SELECT *
	//		FROM  `sup_group` 
	//		INNER JOIN  `shop_orders_coms` 
	//		ON  `sup_group`.`group_id` =  `shop_orders_coms`.`group_id`
	//		WHERE  `com_status` in (1,3,4,5) AND `status` in (3,4,5,6,7) ORDER BY `sup_group`.`group_id` DESC";
	

	$res = mysql_query($sql);
	while($row = mysql_fetch_assoc($res)){
		$id = $row["id"];
		//$group_id = $row["group_id"];

		$ssent[$group_id] = $roww["payment_sent_mail"];
		$write_delii[$group_id]=$roww["write_payment"];
		$cclip[$group_id]=$roww["payment_clip"];
		$clip_file[$group_id]=$roww["payment_clip_file"];
		$clip_name[$group_id]=$roww["payment_clip_file"];
		

		if($cclip[$group_id]==1){
			$file2=end(explode(".",$roww["payment_clip_file"])); 
			$urlFile="http://".$_SERVER["HTTP_HOST"]."/uploads/payment_P/".$group_id.".".$file2;
			$ghead=get_headers($urlFile);
			$head=substr($ghead[0], 9, 3);
			

			if($head!="200"){
				$size_kb[$group_id] = "File is empty";
			}else{
				$size_kb[$group_id] = filesize("uploads/payment_P/".$group_id.".".$file2);
			}
			$clip_file[$group_id] = $group_id.".".$file2;
		}
		
		if(!$numssd2[$group_id]){
			$numssd++;
			$numssd2=array($group_id=>1);
		}

		$com_id = $row["com_id"];
		$com_sum2 = $row["price"];
		$size = $row["com"];
		$count = $row["count"];
		$cur_id = $glb["cur"][$row["cur_id"]];
		$comment = $row["man_comment"];
		$com_status = $row["com_status"];
		if($row["com_color"] != "")
		{
			$color = $row["com_color"];
		}else{
			$color = strip_tags(get_color_to_order($com_id));
		}
		//$color = $row["com_color"];

		$select_cup[$group_id]=$row["cur_id"];
		$select_cupReal[$group_id]=$row["cur_id"];
		
		
		$sup_id[$group_id] = $roww["sup_id"];


		$off_id=$row['offer_id'];
		$of=mysql_query("SELECT * FROM `shop_orders` WHERE `id`='{$off_id}'");
		$off=mysql_fetch_assoc($of);
		$mail_o[$group_id]=$off['email'];
		$codd[$group_id]=$off['cod'];


		$status_group = $roww["status"];
		$status_groupp[$group_id] = $roww["status"];
		if($status_group == 1){
			$selected_group1[$group_id] = "selected";
		} elseif($status_group == 2) {
			$selected_group2[$group_id] = "selected";
		} elseif($status_group == 3) {
			$selected_group3[$group_id] = "selected";
		} elseif($status_group == 4) {
			$selected_group4[$group_id] = "selected";
		} elseif($status_group == 5) {
			$selected_group5[$group_id] = "selected";
		} elseif($status_group == 6){
			$selected_group6[$group_id] = "selected";
		} elseif($status_group == 7){
			$selected_group7[$group_id] = "selected";
		}elseif($status_group == 11){
			$selected_group11[$group_id] = "selected";
		}

		if($status_group<4 || $status_group == 11){
			$select_brande[$group_id]="
				<select size='1' name='status' id = 'select_group_status' class = 'color_select_group2' rel='{$group_id}'>
					<option value='3'  {$selected_group3[$group_id]}>Оплачен клиентом</option>
					<option value='11'  {$selected_group11[$group_id]}>Готов к оплате</option>
				    <option value='4'  {$selected_group4[$group_id]}>Оплачен поставщику</option>
				</select>";
		}else{
			$select_brande[$group_id]="
				<select style='color:white;' id = 'select_group_status' class = 'discolor_select_group2' disabled >
					<option value='3'  {$selected_group3[$group_id]}>Готов к оплате</option>
				    <option value='4'  {$selected_group4[$group_id]}>Оплачен поставщику</option>
				    <option value='6'  {$selected_group6[$group_id]}>Отправлен</option>
				    <option value='7'  {$selected_group7[$group_id]}>Доставлен</option>
				</select>";
		 }

		$groupName=SOZ::getStatusGroup($statusGroup);
		$addOption="<option value='{$statusGroup}' selected>{$groupName}</option>";

		$group_line[$group_id] ="";
		$com_selected1 = "";
		$com_selected2 = "";
		$com_selected3 = "";
		$com_selected4 = "";
		$com_selected5 = "";
		$com_selected0 = "";
		$com_selected6 = "";

		$linecolor = "";
		$status = $row["com_status"];
		if($status == 1){
			$com_selected1 = "selected";
			$linecolor = "greenline";
		} elseif($status == 2){
			$com_selected2 = "selected";
			$linecolor = "redline";
			$addOption="";
		} elseif($status == 3){
			$com_selected3 = "selected";
			$addOption="";
		} elseif($status == 0){
			$com_selected0 ="selected";
		} elseif($status == 4){
			$com_selected4 ="selected";
		} elseif($status == 5){
			$com_selected5 ="selected";
		} elseif($status == 6){
			$com_selected6 ="selected";
		}

		$bbb=mysql_query("SELECT * FROM `shop_categories` WHERE `categories_of_commodities_ID`='$sup_id[$group_id]'; ");
		while($b=mysql_fetch_assoc($bbb)){
		//	$sup_name[$group_id] = $b["cat_name"];
			$sup_name = $b["cat_name"];
		}

		$sql2 = "
			SELECT  `commodity_price` ,  `commodity_price2` ,  `com_name` ,  `from_url` ,  `cod` 
			FROM  `shop_commodity`  
			WHERE `commodity_ID` = {$com_id}";
		$res2 = mysql_query($sql2);
		if($row2 = mysql_fetch_assoc($res2)){
			//$com_price_set=0;

			$curSql=mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`={$row["cur_id"]}; ");
      		$curSqlVal=mysql_fetch_assoc($curSql);


			$cod = $row2["cod"];
			$from_url = $row2["from_url"];
			$com_name = $row2["com_name"];


			if($row["cur_id"]==2 || $row["cur_id"]==3){
				$com_price_set = round($com_sum2/$curSqlVal["cur_val"]);
				$realPrice=round($com_sum2/$curSqlVal["cur_val"]);
				$cur_id='грн';
				$select_cup[$group_id]=1;
			}else{
				$com_price_set = $com_sum2;
				$realPrice=$com_sum2;
			}
			$changeOptRoz[$group_id]=1;
			if($com_price_set==$row2["commodity_price"]){
				$changeOptRoz[$group_id]=1;
				$changePrice1[$group_id]='selected';
			}elseif($com_price_set==$row2["commodity_price2"]){
				$changeOptRoz[$group_id]=2;
				$changePrice2[$group_id]='selected';
			}

			$com_sum = $com_price_set*$count;
		}

		$brenda_flag[$group_id]=array();
		$sql3 = "SELECT * FROM `brenda_contact` WHERE `com_id`=$sup_id[$group_id]";
		$res3 = mysql_query($sql3);
		if($row3 = mysql_fetch_assoc($res3)){
			//$sup_name[$group_id] = $row3["sup_name"];
			$sup_margin_opt = $row3["uc_opt_skidka"];
			if(!$sup_margin_opt){
				$sup_margin_opt=0;
			}
			$sup_margin_roz = $row3["uc_pr_skidka"];
			if(!$sup_margin_roz){
				$sup_margin_roz=0;
			}

			$fromOpt=intval($row3["ot_min_price"]);
			$fromRoz=intval($row3["ot_min_pr_price"]);

			// if(is_numeric($fromOpt) && $fromOpt!=0){
			// 	$sup_margin_opt=0;
			// }
			// if(is_numeric($fromRoz) && $fromRoz!=0){
			// 	$sup_margin_roz=0;
			// }
			
			$skidka[$group_id]= $row3["uc_opt_skidka"];

			$skidka_opt[$group_id]= $row3["uc_opt_skidka"];
			$skidka_roz[$group_id]= $row3["uc_pr_skidka"];

			if($changeOptRoz[$group_id]==1){
				if($sup_margin_roz==0){
					$hideSkid[$group_id]=" style='display:none' ";
					$brenda_flag[$group_id]=0;
				}else{
					$brenda_flag[$group_id]=1;
				}
			}

			if($changeOptRoz[$group_id]==2){
				if($sup_margin_opt==0){
					$hideSkid[$group_id]=" style='display:none' ";
					$brenda_flag[$group_id]=0;
				}else{
					$brenda_flag[$group_id]=1;
				}
			}
		}
		$com_sum_our=0;
		$com_price_set_our=0;
		
		if($changeOptRoz[$group_id]==1){
			$com_sum_our = $com_sum*(100-$sup_margin_roz)/100;
	      	$com_price_set_our = $com_price_set*(100-$sup_margin_roz)/100;
		}
		if($changeOptRoz[$group_id]==2){
			$com_sum_our = $com_sum*(100-$sup_margin_opt)/100;
	      	$com_price_set_our = $com_price_set*(100-$sup_margin_opt)/100;
		}

		$lines[$group_id].="
			<tr id='{$row["id"]}' >
				<td>
					<input type=\"checkbox\" class=\"cl_trt\" rel=\"{$row["id"]}\" rel-id=\"{$com_id}\">
				</td>
				<td>{$cod}</td>
				<td>{$color}</td>
				<td>{$size}</td>
				<td>{$count}</td>
				<td  rel-real-price={$realPrice}>{$cur_id}</td>
				<td>{$com_price_set}</td>
				<td {$hideSkid[$group_id]} class='closeTdSki{$group_id}' >{$com_price_set_our}</td>
				<td>{$com_sum}</td>
				<td {$hideSkid[$group_id]} class='closeTdSki{$group_id}' >{$com_sum_our}</td>
				<td><a href ='{$from_url}'>Источник</a></td>
				<td>
					<select size='1' name='status' id = 'select_status_com' rel = '{$row['id']}' disabled>
						<option value='0' {selected0}></option>
						<option value='1' rel='{$row2['id']};1' {$com_selected1}>Есть в наличии</option>
		    			<option value='2' rel='{$row2['id']};2' {$com_selected2}>Нет в наличии</option>
		    			<option value='3' rel='{$row2['id']};3'{$com_selected3}>Замена</option>
		    			{$addOption}
					</select>
				</td></td>
				<td>{$comment}</td>
			</tr>";
		
		$add_line[$group_id]=$sup_name; 
		$cur[$group_id]=$cur_id;

		$total_price_to_pay[$group_id] += $com_sum*(100-$sup_margin_opt)/100;
		
		$bcc=mysql_query("SELECT * FROM `brenda_contact` WHERE `com_id`='{$sup_id[$group_id]}';") or die(mysql_error());
		
		if($bcc) {
			$bb=mysql_fetch_assoc($bcc);
			$name[$group_id]=$bb["rek_pa_name"];
			$bank[$group_id]=$bb["rek_pa_bank"];
			$fl[$group_id]=$bb["rek_pa_plat"];
			$chet[$group_id]=$bb["rek_pa_shet"];
			$tel[$group_id]=$bb["cont_phone"];
			$tel[$group_id]=str_replace(";",",",$tel[$group_id]);
			$tel[$group_id]=substr($tel[$group_id],2,strlen($tel[$group_id]));
			$emaill[$group_id]=$bb["cont_mail"];
			$emaill[$group_id]=str_replace(";",",",$emaill[$group_id]);
			$emaill[$group_id]=substr($emaill[$group_id],2,strlen($emaill[$group_id]));
			$prim[$group_id]=$bb["rek_pa_dop"];

		}
		
		if($status!=2){	
			if($changeOptRoz[$group_id]==1){
				$total_price_to_pay[$group_id] += $com_sum*(100-$sup_margin_roz)/100;
			}
			if($changeOptRoz[$group_id]==2){
				$total_price_to_pay[$group_id] += $com_sum*(100-$sup_margin_opt)/100;
			}

			if($brenda_flag[$group_id]==0){
				$total_price[$group_id] += $com_sum;
			}
			if($brenda_flag[$group_id]==1){
				$total_price[$group_id] += $com_sum_our;
			}

			$total_count[$group_id] += $count;

			$result_sum += $com_sum;
			if($changeOptRoz[$group_id]==1){
				$result_sum_to_pay += $com_sum*(100-$sup_margin_roz)/100;
			}
			if($changeOptRoz[$group_id]==2){
				$result_sum_to_pay += $com_sum*(100-$sup_margin_opt)/100;
			}
			$result_quantity += $count;


			if($sup_id[$group_id]==48 || $sup_id[$group_id]==47){
				$total_price[$group_id] += $total_price[$group_id] / 100 * 0.5;
			}
		}
		
	}

}
	$result_profit = $result_sum - $result_sum_to_pay;

				
$center.="<div id='pay_body' style='display: none'>
					<div id='pay_mail'>
						<div class='topDiv' >
							<div class='icon_close pay_close' ></div>
						</div>
						<label>Кому: </label><input type='text' name='' class='in_text' id='from_to' /><br/>
						<label>От кого: </label><input type='text' value='sales@makewear.com.ua' name='' class='in_text' id='from_whom' /><br/>
						<label>Тема: </label><input type='text' name='' class='in_text' id='from_sub' /><br/>
						
						<div class='html_mail' >
						</div>
						<div style='position: relative;height: 50px;'>
							<div class='send but_send'>Отправить</div>
							<button class='pay_close2 but_close'>Отмена</button>
						</div>
					</div>
				</div>";

	if($group_line){
	foreach ($group_line as $key => $value) {
			$selectCup1=null;
			$selectCup2=null;
			$selectCup3=null;
			$val1=null;
			$val2=null;
			$val3=null;
			switch ($select_cup[$key]) {
				case 1:
					$selectCup1='selected';
					break;
				case 2:
					$selectCup2='selected';
					break;
				case 3:
					$selectCup3='selected';
					break;
			}
			$curSql=mysql_query("SELECT * FROM `shop_cur` WHERE 1; ");
      		while($curSqlVal=mysql_fetch_assoc($curSql)){
      			
      			switch ($curSqlVal["cur_id"]) {
					case 1:
						$val1=$curSqlVal["cur_val"];
						$curname1=$curSqlVal["cur_show"];
						break;
					case 2:
						$val2=$curSqlVal["cur_val"];
						$curname2=$curSqlVal["cur_show"];
						break;
					case 3:
						$val3=$curSqlVal["cur_val"];
						$curname3=$curSqlVal["cur_show"];
						break;
				}
      		}

			$group_line[$key].="
			<table class ='tab_cat sortable'>
			<tr>
				<th><input type='checkbox' class='changeAllBox' rel='{$key}' /></th>
				<th>Артикул</th>
				<th>Цвет</th>
				<th>Размер</th>
				<th>Кол-во</th>
				<th>
					Валюта
					<select class='change_tab_cup' id='getCup{$key}' rel='{$key}' rel-skidki='{$skidka[$key]}'  >
						<option value='1' {$selectCup1} rel-val='{$val1}' rel-name-cur='{$curname1}' >UAH</option>
						<option value='2' {$selectCup2} rel-val='{$val2}' rel-name-cur='{$curname2}' >USD</option>
						<option value='3' {$selectCup3} rel-val='{$val3}' rel-name-cur='{$curname3}' >RUB</option>
					</select>	
				</th>
				<th style='width:125px'>
					Цена
					<select class='change_price_opt' id='getSki{$key}' rel='{$key}' rel-cur='{$select_cup[$key]}' rel-real-cur='{$select_cupReal[$key]}' >
						<option value=1 {$changePrice1[$key]} rel-skidki='{$skidka_roz[$key]}' >Розница</option>
						<option value=2 {$changePrice2[$key]} rel-skidki='{$skidka_opt[$key]}'>Опт</option>
					</select>		
				</th>
				<th {$hideSkid[$key]} class='closeTdSki{$key}' >Цена со скидкой</th>
				<th>Сумма</th>
				<th {$hideSkid[$key]} class='closeTdSki{$key}' >Сумма со скидкой</th>
				<th>Ссылка</th>
				<th>Статус</th>
				<th>Комментарий</th>
			</tr>

			";
			$group_line[$key].=$lines[$key];
			$group_line[$key] .="</table></td></tr>";
	}
}
	
	$add_line2="";
	if($add_line){
		foreach($add_line as $k=>$v){
			$names="";
			if($name[$k]){
				$name_a=explode(" ", $name[$k]);	
						
				$names=mb_ucfirst($name_a[0], "utf-8");
				if($name_a[1]){
					$names.=" ".mb_ucfirst(substr($name_a[1],0,2), "utf-8").".";
				}
				if($name_a[2]){
					$names.=mb_ucfirst(substr($name_a[2],0,2), "utf-8").".";	
				}
			}

			 if($ssent[$k]==0){
				$signal_sent="<div class='icon_send'></div>";
			 }	elseif($ssent[$k]>=1){
			 	$signal_sent="<div class='icon_sent' rel-was-sent='{$ssent[$k]}'></div>";	
			 }	
			
			if($cclip[$k]==0) {
				$clip="<div class='icon_clip_will' style='cursor:pointer;' rel='{$k}'></div>";
			}elseif($cclip[$k]==1) {
				$clip="<div class='icon_clip_was icw{$k}' style='cursor:pointer;' rel='{$k}' rel-file='{$clip_file[$k]}' rel-size='{$size_kb[$k]}' rel-name='{$clip_name[$k]}' ></div>";	
			}	

			$write_deli=$write_delii[$k];
			if($write_deli==""){
				$putInfoColor="icon_info_white";
				$putDownColor="block_down";
				$putInfo="wind_o2";
			}else{
				$putInfoColor="icon_info_orange";
				$putDownColor="block_down1_orange";
				$putInfo="wind_o2_orange";
			}

// echo $k."=".$total_price[$k].", ";
			$curSql=mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`={$select_cup[$k]}; ");
			$curRes=mysql_fetch_assoc($curSql);
			
				$client_all[$k]=1;
				if($status_groupp[$k] == 4){
					$client_count_buy++;
					$add_price_buy+=round($total_price[$k]/$curRes["cur_val"], 2);
				}
				if($status_groupp[$k] == 11){
					$client_count_all++;		
					$add_price_all+=round($total_price[$k]/$curRes["cur_val"], 2);
				}
				if($status_groupp[$k] == 3){
					$client_count_wait++;
					//if($curRes["cur_val"])
					$add_price_wait+=round($total_price[$k]/$curRes["cur_val"], 2);
					//echo $total_price[$k]."-".$curRes["cur_val"]."<br/>";
				}
			
			$curSqlUsd=mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`=2; ");
			$curResUsd=mysql_fetch_assoc($curSqlUsd);
				
				if($status_groupp[$k] == 4){
					$add_price_buy_usd=round($add_price_buy[$k]*$curResUsd["cur_val"], 2);
				}
				if($status_groupp[$k] == 11){
					$add_price_all_usd=round($add_price_all[$k]*$curResUsd["cur_val"], 2);
				}
				if($status_groupp[$k] == 3){
					$add_price_wait_usd=round($add_price_wait[$k]*$curResUsd["cur_val"], 2);
				}

			$curSqlRub=mysql_query("SELECT * FROM `shop_cur` WHERE `cur_id`=3; ");
			$curResRub=mysql_fetch_assoc($curSqlRub);
				
				if($status_groupp[$k] == 4){
					$add_price_buy_rub+=round($add_price_buy[$k]*$curResRub["cur_val"], 2);
				}
				if($status_groupp[$k] == 11){
					$add_price_all_rub=round($add_price_all[$k]*$curResRub["cur_val"], 2);
				}
				if($status_groupp[$k] == 3){
					$add_price_wait_rub+=round($add_price_wait[$k]*$curResRub["cur_val"], 2);
				}

			$total_price[$k]=round($total_price[$k], 2);
			$add_line2.="<tr class='tab_up forsearch' style='{$active[$k]}' id='gh{$k}'>
				<td style='border-bottom: 0px solid;'></td>
				<td style='border-bottom: 0px solid;'>
					<div class='hdiv'><div class='hdiv2'>
						<div style='display:table;'>
							<div style='display:table-cell;padding-right: 5px;'>
								<span class='but_open_win' rel='{$k}' style='cursor:pointer'>
									<div class='block_down' id='bb{$k}' ></div>
								</span>
							</div>
							<div style='display:table-cell;'>
								{$k}
							</div>
						</div>
					</div></div>
				</td><td style='border-bottom: 0px solid;'>
					<div class='hdiv'><div class='hdiv2 cli_date'>
						{$date[$k]}
					</div></div>
				</td><td style='border-bottom: 0px solid;'>
					<div class='hdiv'><div class='hdiv2'>
						{$add_line[$k]}
					</div></div>
				</td><td style='border-bottom: 0px solid;'>
					<div class='hdiv'><div class='hdiv2'>
						{$select_brande[$k]}
			    	</div></div>
				</td><td style='border-bottom: 0px solid;'>
					<div class='hdiv'><div class='hdiv2'>
						{$total_count[$k]}
					</div></div>
				</td><td style='border-bottom: 0px solid;'>
					<div class='hdiv'><div class='hdiv2' id='addPrice'>
						{$total_price[$k]} {$cur[$k]}
					</div></div>
				</td><td style='border-bottom: 0px solid;'>
					<div class='hdiv'><div class='hdiv2'>
						
								<div class='wind_o open_backg' rel='{$k}' style='cursor:pointer;display:table;position: absolute;margin-top: -9px;'>
									<div style='display:table-cell;padding-left: 5px;padding-bottom: 9px;' rel-real-name='$name[$k]'>
										{$names}
									</div>
									<div style='display:table-cell;padding-left:3px;padding-right: 5px;'>
										<div class='block_down bc{$k}'></div>
									</div>
								</div>
								<div class='wind_names' id='open_win{$k}' style='display:none;max-height: 500px;'>
									<table>
										<tr><td></td><td></td></tr>
										<tr><td>Телефон:</td><td>{$tel[$k]}</td></tr>
										<tr><td></td><td></td></tr>
										<tr><td>E-mail:</td><td>{$emaill[$k]}</td></tr>
										<tr><td></td><td></td></tr>
										<tr><td>Примечание:</td><td class='getpri{$k}'>{$prim[$k]}</td></tr>
										<tr><td></td><td></td></tr>
									</table>
								</div>
						
					</div></div>
				</td><td style='border-bottom: 0px solid;'>
					<div class='hdiv'><div class='hdiv2'>
							{$bank[$k]}
					</div></div>
				</td><td style='border-bottom: 0px solid;'>
					<div class='hdiv'><div class='hdiv2'>
							{$fl[$k]}
					</div></div>
				</td><td style='border-bottom: 0px solid;'>
					<div class='hdiv'><div class='hdiv2'>
						<div class='get_shet{$k}' >
							{$chet[$k]}
						</div>
					</div></div>
				</td><td style='border-bottom: 0px solid;'>
					<div class='hdiv'><div class='hdiv2'>
						<div style='display:table;margin-top: -4px;margin-bottom: -4px;margin-right: 10px;'>
							<div class='send_ac{$k}' style='display:table-cell;padding-right: 4px;vertical-align: middle;' >
								<span class='maill sentt$k' rel='{$k}' rel2='{$emaill[$k]}' art='{$codd[$k]}' rel-cat='{$catt[$k]}' >
									{$signal_sent}
								</span>
							</div>

							<div class='change_clip{$k}'   style='display:table-cell;padding-right: 4px;vertical-align: middle;' >
								{$clip}
							</div>
							<div  style='display:table-cell;'  >
								<div class='{$putInfo} open_backg' rel='{$k}' style='display:table;'>
									<div style='display:table-cell'>
										<div class='{$putInfoColor} iiw{$k}'></div>
									</div>
									<div style='display:table-cell;vertical-align: middle;padding-left: 2px;'>
										<div class='{$putDownColor} bbc{$k}' ></div>
									</div>
								</div>	
									
								<div class='wind_names' id='open_win2{$k}' style='display:none;right:16px;margin-top:0px;width: 300px;'>
									<table>
										<tr><td></td></tr>
										<tr>
											<td style='font-weight:100;text-align: left;width:300px;height: 90px;' contenteditable='true' class='write_tab' rel='{$k}'>
											{$write_deli}
											</td>
										</tr>
										<tr><td></td></tr>
									</table>
								</div>
								
							</div>
						</div>
					</div></div>
				</td>
		</tr>
		<tr>
			<td style='border-bottom: 0px solid #CCC;'></td>
			<td colspan=11 style='padding-top: 0px;border-bottom: 0px solid #CCC;' >
			<div class='open_line open_commodity{$k}' style='display:none;' >
				{$group_line[$k]}
		 	</div>
		 	</td>
		</tr>";
		}
	}
	
	// $client_count_all1=0;
	// $client_count_buy1=0;
	// $client_count_wait1=0;	
	
	// $add_price_all1=0;
	// $add_price_buy1=0;
	// $add_price_wait1=0;

	// $add_price_allUsd=0;
	// $add_price_buyUsd=0;
	// $add_price_waitUsd=0;

	// $add_price_allRub=0;
	// $add_price_buyRub=0;
	// $add_price_waitRub=0;
	
	// if($client_count_all) {
	// 	foreach($client_all as $k=>$v){
	// 		$client_count_all1+=$client_count_all[$v];
	// 		$client_count_buy1+=$client_count_buy[$k];
	// 		$client_count_wait1+=$client_count_wait[$k];
			
	// 		$add_price_all1+=$add_price_all[$k];
	// 		$add_price_buy1+=$add_price_buy[$k];
	// 		$add_price_wait1+=$add_price_wait[$k];

	// 		$add_price_allUsd+=$add_price_all_usd[$k];
	// 		$add_price_buyUsd+=$add_price_buy_usd[$k];
	// 		$add_price_waitUsd+=$add_price_wait_usd[$k];

	// 		$add_price_allRub+=$add_price_all_rub[$k];
	// 		$add_price_buyRub+=$add_price_buy_rub[$k];
	// 		$add_price_waitRub+=$add_price_wait_rub[$k];
	// 	}
	// }
		$center.="<br/>
		<div class='table_line2_gr' >
		<div class='table_line2_white' >
			<table class='tab_info' border=1 >
				<tr>
					<td>
						<div style='display:table'>
							<div style='display:table-cell;vertical-align: middle;padding: 5px;cursor:pointer' class='open_calendar' >
								<div class='icon_calendar' style='float: left;' ></div>
							</div>
							<div style='display:table-cell;vertical-align: middle;padding-top: 4px;'>
								<input type='text' id='datepicker_form' style='width: 63px;' /> 
							</div>
							<div style='display:table-cell;vertical-align: middle;padding-right: 5px;padding-left: 4px;'>
							   -
							</div>
							<div style='display:table-cell;vertical-align: middle;padding-top: 4px;'>
								<input type='text' id='datepicker_to' style='width: 63px;' />
							</div>
						</div>
					</td>
					<td style='color: white;background: #313131;border: 1px solid white;' >ОПЛАЧЕНО<br/> КЛИЕНТОМ</td>
					<td style='color: white;background: #313131;border: 1px solid white;' >ГОТОВО<br/> К ОПЛАТЕ</td>
					<td style='color: white;background: #313131;border: 1px solid white;' >ОПЛАЧЕНО</td>
					<td style='color: white;background: #313131;border: 1px solid white;border-right: 1px solid #313131;' >ОЖИДАЕТСЯ</td>
				</tr>
				<tr>
					<td>ПОСТУПИЛО ЗАКАЗОВ</td>
					<td>{$client_count_wait}</td>
					<td>{$client_count_all}</td>
					<td>{$client_count_buy}</td>
					<td>{$client_count_wait}</td>
				</tr>
				<tr>
					<td style='border-bottom: 1px solid #313131;'>
						СУММА ПЛАТЕЖА
						<select id='selectCur'>
							<option value=1 >UAH</option>
							<option value=2 >USD</option>
							<option value=3 >RUB</option>
						<select>
					</td>
					<td class='changeCur' rel-uah='{$add_price_wait}' rel-usd='{$add_price_all_usd}' rel-rub='{$add_price_all_rub}' >{$add_price_wait}</td>
					<td class='changeCur' rel-uah='{$add_price_all}' rel-usd='{$add_price_all_usd}' rel-rub='{$add_price_all_rub}' >{$add_price_all}</td>
					<td class='changeCur' rel-uah='{$add_price_buy}' rel-usd='{$add_price_buy_usd}' rel-rub='{$add_price_buy_rub}' >{$add_price_buy}</td>
					<td class='changeCur' rel-uah='{$add_price_wait}' rel-usd='{$add_price_wait_usd}' rel-rub='{$add_price_wait_rub}' >{$add_price_wait}</td>
				</tr>
			</table>
		</div></div>
		<div class='left_icon' >
			<div class='tab_td2'>
				<div class='back_icon'>
		 			<div class='icon_calc' style='cursor:pointer;' ></div>
		 		</div>
			</div>		 	
		</div>
			<br/>
		";	
	$center.="
		<link rel='stylesheet' href='/modules/calc/calc.css'>
		<script type='text/javascript' src='/modules/calc/calc.js'></script>
		<div class='calc' style='display:none;width: 186px;'>
			<div class='mousee'>
				<span class='icon_red' title='Закрить'></span>
			</div>
			<div class='read_calc'>0</div>
			<table class='but_calc'>
				<tr><td rel='7'><div>7</div></td><td rel='8'><div>8</div></td><td rel='9'><div>9</div></td><td rel='+'><div>+</div></td></tr>
				<tr><td rel='4'><div>4</div></td><td rel='5'><div>5</div></td><td rel='6'><div>6</div></td><td rel='-'><div>-</div></td></tr>
				<tr><td rel='1'><div>1</div></td><td rel='2'><div>2</div></td><td rel='3'><div>3</div></td><td rel='/'><div>/</div></td></tr>
				<tr><td rel='0' colspan='2' ><div style='width: 86px;''>0</div></td><td rel='.'><div>.</div></td><td rel='*'><div>*</div></td></tr>
				<tr><td rel='C'><div>C</div></td><td rel='CE'><div>CE</div></td><td colspan='2' rel='='><div style='width: 86px;font-size: 28px;'>=</div></td></tr>
			</table>
		</div>";
	$center.="
		<div class='body_upload' style='display:none'>
			<div class='bbb2'>
			<div style='overflow-y: scroll;height: 100%;'>
				<div class='wind_upload' style='display:block'>
				
						<div class='close_upload icon_close' ></div>
						<div>ПОДТВЕРЖДЕНИЕ ОПЛАТЫ</div>
						<div class='names'>Заказ №<span id='get_upload_order'></span>; Сумма<span id='get_upload_price' ></span></div>	
							<div id='uupp' >
								<div>
									ЗАГРУЗИТЬ ДОКУМЕНТ
								</div>
								 <input type='file' name='file' class='upload' />
							</div>				
						<div>
							Загрузите скан-копию документа, подтверждающего осуществление оплаты.<br/>
							Допустимые форматы: JPG; PNG; PDF
						</div>
					
				</div>
				<div class='show_file' style='display:none' >
					<div class='close_upload icon_close' ></div>
					<div class='see_file'>
						
					</div>
					
					<div style='display:table;width: 100%;'>	
						<div style='display:table-cell;width: 10px;' >				
							<div class='any_file'>
								<div class='icon_file_upload' ></div>
								Загрузить другой файл
								<input type='file' name='file' class='upload' />
							</div>
							<div class='delete_file'  >
								<i class=\"fa fa-times\" aria-hidden=\"true\"></i>Удалить
							</div>
						</div>
						<!--<div style='display:table-cell;text-align: left;' >
							<div id='singal_upload'>OK</div>
						</div>-->
						<div style='display:table-cell;position: relative;'>
														
							<div class='name_file' >
								<div style='display:table;width: 100%;'>
									<div style='display:table-row' style='padding:2px;' >
										<table>
											<tr>
												<td>
													<div class='icon_file_jpg'></div>
												</td>
												<td>
													<span class='set_name' style='font-size: 11px; color: black;' ></span><br/>
													<span class='set_size' style='font-size:10px; color:gray;' /></span>
												</td>
											</tr>
										</table>
										<br/>
									</div>
									<div style='display:table-row;background: #E8E7E7;color: #5F5F5F;'>
										<div style='padding: 4px;' >								 		
								 		Прикреплено: 1файл. Общий размер:<span class='set_size' />
								 		</div>
								 	</div>
							 	</div> 
							</div>
						</div>
					</div>				
				</div>
			</div>
			</div>
		</div>
		";
	$center.="
			<br/>
			<div style='position: relative;height: 28px;'>
				<div style='display:table;position: absolute;right: 2px;' class='but_nn'>
					<div class='{$but_orr1} tab_nn' style='display:table-cell;{$but_style1}' rel=1 >
						<div>
							Оплата-Поставщики
						</div>
					</div>
					<div class='{$but_orr2} tab_nn' style='display:table-cell;{$but_style2}' rel=2 >
						<div>
							Архив
						</div>
					</div>
				</div>
			</div>			
			<table class='sortable order_to_sup'>
				<th style='width: 1px;' ></th>
				<th>№</th>
				<th>Дата</th>
				<th>Бренд</th>
				<th>Статус</th>
				<th>Единиц</th>
				<th>Сумма</th>
				<th style='width: 15%;'>Контрагент</th>
				<th>Банк</th>
				<th>Вид платежа</th>
				<th>№ счета/карт</th>
				<th style='width:88px;'></th>
				{$add_line2}
		</table>{$links}";
	$center.="
		<script src='/templates/admin/js/i18n/datepicker-uk.js' type='text/javascript'></script>
		<script src='/templates/admin/js/i18n/datepicker-ru.js' type='text/javascript'></script>	
		<script src='/templates/admin/soz/js/orders_to_sup20.js'></script>
		<link href='/templates/admin/soz/style/order20.css' type='text/css' rel='stylesheet' />
		<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css\">
			";

}

