<?php

class SOZ{


	function getCategoryName($id){
		$commodityCategories = mysql_query("
                SELECT categoryID, cat_name, categories_of_commodities_parrent
                FROM `shop_commodities-categories` AS b
                INNER JOIN shop_categories AS c
                ON c.categories_of_commodities_ID = b.categoryID
                WHERE commodityID = {$id} 
                AND `categories_of_commodities_parrent` IN ( 209, 264, 212, 213, 210, 56, 268, 211, 261, 266 ) 
            ");
            if($commodityCategories)
				$rrr=mysql_fetch_assoc($commodityCategories);	
				
				$cat_name=$rrr["cat_name"];
				
				$cat_name=str_replace("упальники","упальник",$cat_name);
				$cat_name=str_replace("офты","офта",$cat_name);
				$cat_name=str_replace("убы","уба",$cat_name);
				$cat_name=str_replace("зы","за",$cat_name);
				$cat_name=str_replace("риджи","риджа",$cat_name);

				$cat_name=str_replace("ие","ой",$cat_name);
				$cat_name=str_replace("ые","ый",$cat_name);
				$cat_name=str_replace("ки","ка",$cat_name);
				$cat_name=str_replace("ы","",$cat_name);


				// $arr_cat=array(
				// 	"Юбки"=>"Юбка",
				// 	"Рубашки"=>"Рубашка",
				// 	"Блузы"=>"Блуза",
				// 	"Свитшоты"=>"Свитшот",
				// 	"Большие размеры"=>"Большой размер",
				// 	"Пиджаки"=>"Пиджака",
				// 	"Спортивные костюмы"=>"Спортивный костюм",
				// 	"Куртки"=>"Куртка",
				// 	"Пальто"=>"Пальто",
				// 	"Футболки"=>"Футболка",
				// 	"Кофты"=>"Кофта",
				// 	"Треггинсы"=>"Треггинс",
				// 	"Кардиганы"=>"Кардиган",
				// 	"Костюмы"=>"Костюм",
				// 	"Комбинезоны"=>"Комбинезон",
				// 	"Брюки"=>"Брюка",
				// 	"Леггинсы"=>"Леггинс",
				// 	"Шорты"=>"Шорт",
				// 	"Жилеты"=>"Жилет",
				// 	"Туники"=>"Туника",
				// 	"Джинсы"=>"Джинс",
				// 	"Сарафаны"=>"Сарафан",
				// 	"Купальники"=>"Купальник",
				// 	"Майки"=>"Майка",
				// 	"Плащи"=>"Плащ",
				// 	"Бриджи"=>"Бриджа",
				// 	"Шубы"=>"Шуба",
				// 	"Дубленки"=>"Дубленка",
				// 	"Свитеры"=>"Свитер"
				// );		
				// if($arr_cat[$cat_name]){
				// 	return $arr_cat[$cat_name];
				// }else{
				// 	return $cat_name;
				// }

			return $cat_name;
	}

	function getBrandeName($com_id){
		$cat=mysql_query("SELECT `categories_of_commodities_parrent`,`categories_of_commodities_ID`,`cat_name`,`categoryID`,`commodityID`
				FROM `shop_categories` AS a
				INNER JOIN `shop_commodities-categories` AS b
				ON a.`categories_of_commodities_ID`=b.`categoryID`
				WHERE `categories_of_commodities_parrent`=10 
				AND `commodityID`='{$com_id}'");	
		$gr=mysql_fetch_assoc($cat);
		return $gr['cat_name'];
	}
	function getBrandeId($com_id){
		$cat=mysql_query("SELECT `categories_of_commodities_parrent`,`categories_of_commodities_ID`,`cat_name`,`categoryID`,`commodityID`
				FROM `shop_categories` AS a
				INNER JOIN `shop_commodities-categories` AS b
				ON a.`categories_of_commodities_ID`=b.`categoryID`
				WHERE `categories_of_commodities_parrent`=10 
				AND `commodityID`='{$com_id}'");	
		$gr=mysql_fetch_assoc($cat);
		return $gr['categories_of_commodities_ID'];
	}
	function getBrandeId2($com_id){
		$cat=mysql_query("SELECT `categories_of_commodities_parrent`,`categories_of_commodities_ID`,`cat_name`,`categoryID`,`commodityID`
				FROM `shop_categories` AS a
				INNER JOIN `shop_commodities-categories` AS b
				ON a.`categories_of_commodities_ID`=b.`categoryID`
				WHERE `categories_of_commodities_parrent`=10 
				AND `commodityID`='{$com_id}'");	
		$gr=mysql_fetch_assoc($cat);
		return $gr['categoryID'];
	}

	function getStatusGroup($status){
		$statusName="";
		switch($status){
			case 1:
				$statusName="Новый заказ";
			break;
			case 2:
				$statusName="Обрабатывается";
			break;
			case 3:
				$statusName="Оплачен клиентом";
			break;
			case 4:
				$statusName="Оплачен поставщику";
			break;
			case 5:
				$statusName="Отправлен";
			break;
			case 6:
				$statusName="Доставлен";
			break;
			case 11:
				$statusName="Готов к оплате ";
			break;
		}
		return $statusName;
	}

	function getStatusCommodity($statusClient, $statusSupplier){
		$k=$statusClient;
		$s=$statusSupplier;
		$statusName="error status!";
		$color='255, 255, 255';

		if($s<=3 && $k<=2){
			$statusName="";
		}

		if($s<=3 && $k==3){
			$statusName="Готов к оплате клиентом";
			$color='169, 208, 142';
		}
		if($s<=3 && $k==4){
			$statusName="Оплачен клиентом";
			$color='84, 130, 53';
		}

		if(($k==4 || $k>=12 || $k==6 || $k==8) && $s==11){
			$statusName="Готов к оплате поставщику";
			$color='169, 208, 142';
		}
		if(($k==4||$k>=12||$k==6||$k==8) && $s==4){
			$statusName="Оплачен поставщику";
			$color='84, 130, 53';
		}
		if(($k==4||$k>=12||$k==6||$k==8) && $s==5){
			$statusName="Отправлен поставщиком";
			$color='255, 102, 255';
		}
		if(($k==4||$k>=12||$k==6||$k==8) && $s==6){
			$statusName="На складе";
			$color='255, 204, 204';
		}
		// if($s>=6 && $k==13){
		// 	$statusName="Возврат";
		// 	$color='220, 98, 98';
		// }
		if($s==6 && $k==6){
			$statusName="Отправлен клиенту";
			$color='255, 102, 255';
		}
		if($s==6 && $k==7){
			$statusName="Доставлен клиенту";
			$color='204, 0, 155';
		}

		// return array('nameStatus'=>$statusName, 'colorStatus'=>$color);
		return $statusName;
	}


	function setLinks($status1, $status2){

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
			$status="`status` in ".$status2;
			$but_orr2="but_nn_active";
			$but_style2="border-bottom: 0px solid gray;color: black;";
		}else{
			$status="`status` in ".$status1;
			$but_orr1="but_nn_active";
			$but_style1="border-bottom: 0px solid gray;color: black;";
		}

		$mysql_len=mysql_query("
			SELECT *
			FROM `shop_orders`
			WHERE {$status}
			ORDER BY `date` DESC 
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

		return array('links'=> $links, 'status'=>$status, 'but_orr1'=>$but_orr1, "but_orr2"=>$but_orr2, "but_style1"=>$but_style1, "but_style2"=>$but_style2 );

	}
// ==================== SUPPLIER ====================================================

	function autoOrderSupplier($id){

		$res=mysql_query("SELECT * FROM `shop_order_supplier` WHERE `order_id`={$id}; ");
		$num_rows = mysql_num_rows($res);

		if($num_rows==0){
			$res1=mysql_query("SELECT * FROM `shop_orders_coms` WHERE `offer_id`={$id}; ");
			while($row1=mysql_fetch_assoc($res1)){
				$brandeId=SOZ::getBrandeId($row1["com_id"]);
				$socId=$row1["id"];
				// echo $brandeId.", ";

				$res2=mysql_query("SELECT * FROM `shop_order_supplier` WHERE `order_id`={$id} AND `supplier_name_id` = {$brandeId}; ");
				$num_rows2 = mysql_num_rows($res2);

				if($num_rows2==0){
					mysql_query("INSERT INTO `shop_order_supplier`(`order_id`, `supp_status`, `supplier_name_id`) VALUES ('{$id}', 1, '$brandeId')");
				}

				mysql_query("UPDATE `shop_orders_coms` SET `has_supplier`=1 WHERE `id`='{$socId}'; ");
			}
		}else{
			$res1=mysql_query("SELECT * FROM `shop_orders_coms` WHERE `offer_id`={$id} AND `has_supplier`=0; ");
			while($row1=mysql_fetch_assoc($res1)){
				$brandeId=SOZ::getBrandeId($row1["com_id"]);
				$socId=$row1["id"];
				//echo "add commodity: ".$brandeId;

				$res2=mysql_query("SELECT * FROM `shop_order_supplier` WHERE `order_id`={$id} AND `supplier_name_id` = {$brandeId}; ");
				$num_rows2 = mysql_num_rows($res2);

				if($num_rows2==0){
					mysql_query("INSERT INTO `shop_order_supplier`(`order_id`, `supp_status`, `supplier_name_id`) VALUES ('{$id}', 1, '$brandeId')");
				}

				mysql_query("UPDATE `shop_orders_coms` SET `has_supplier`=1 WHERE `id`='{$socId}'; ");
			}
		}

	}

	function getCommodityOne($comId){
		$res=mysql_query("SELECT * FROM `shop_commodity` WHERE `commodity_ID`={$comId};");
		return mysql_fetch_assoc($res);
	}

// ==================== THE END SUPPLIER ====================================================

	function bonus($order_id, $user_id=0, $sum_price=0, $count=0){
		// $bonRes=mysql_query("SELECT * FROM `soc_client_join` WHERE `scj_order_id`='{$order_id}' AND `scj_name`='head'; ") or die(mysql_error());

		$couRow=mysql_query("SELECT  `user_id` FROM  `shop_orders` WHERE  `user_id` = '{$user_id}'; ");
		$userCount=mysql_num_rows($couRow);

		// $bonRes=mysql_query("SELECT * FROM `soc_client_all_stock` WHERE `scas_name`='head' ") or die(mysql_error());
		// $bonRow=mysql_fetch_assoc($bonRes);

		if($userCount==1){
			$bonRes=mysql_query("SELECT * FROM `soc_client_all_stock` WHERE `scas_name`='head' AND `period_gift_select`=3 ") or die(mysql_error());
			$bonRow=mysql_fetch_assoc($bonRes);
		}

		if($userCount==2){
			$bonRes=mysql_query("SELECT * FROM `soc_client_all_stock` WHERE 	period_gift_input='{$userCount}' AND `period_gift_select`=3 ") or die(mysql_error());
			$bonRow=mysql_fetch_assoc($bonRes);
		}

		if($userCount>=3){
			$bonRes=mysql_query("SELECT * FROM `soc_client_all_stock` WHERE 	period_gift_input='{$userCount}' AND `period_gift_select`=3 ") or die(mysql_error());
			$bonRow=mysql_fetch_assoc($bonRes);
		}

		// $orderRes=mysql_query("SELECT `id`,`delivery_price`,`cur_id` FROM `shop_orders` WHERE `id`={$order_id}; ");
		// $orderRow=mysql_fetch_assoc($orderRes);


		// die("User_id count: ".$userCount);

		//=============СКИДКА======================================
		// cond_skidka_select,cond_skidka_input

		$bonus_delivery=$bonRow["bonus_delivery"];
		$bonus_skidka_input=intval($bonRow["bonus_skidka_input"]);
		$bss=$bonRow["bonus_skidka_select"];
		$bonus_skidka_select="";

		$csi=intval($bonRow["cond_skidka_input"]);
		$css=$bonRow["cond_skidka_select"];

		$psi=intval($bonRow["period_skidka_input"]);
		$pss=$bonRow["period_skidka_select"];

		$flag2=0;
		switch ($pss) {
			case 1:
				if($psi<=$count){
					$flag2=1;
				}
				break;
			case 2:
				if($psi<=$sum_price){
					$flag2=1;
				}
				break;
			case 3:
				if($psi>=$userCount){
					$flag2=1;
				}
				break;
			default:
				$flag2=1;
				break;
		}

		$flag=0;
		switch ($css) {
			case 1:
				if($csi<=$count){
					$flag=1;
				}
				break;
			case 2:
				if($csi<=$sum_price){
					$flag=1;
				}
				break;
			case 3:
				if($csi>=$userCount){
					$flag=1;
				}
				break;
			default:
				$flag=0;
				break;
		}

		if($flag!=0 && $flag2!=0){
		// die("Select: ".$bss);
			switch ($bss) {
				case 1:
					$bonus_skidka_select=" %";
					$sum_price-=$sum_price/100*$bonus_skidka_input;
					break;
				case 2:
					$bonus_skidka_select=" грн";
					$sum_price-=$bonus_skidka_input;
					break;
				case 3:
					$bonus_skidka_select=" руб";
					$sum_price-=$bonus_skidka_input;
					break;
				default:
					$bonus_skidka_select="";
					break;
			}
			$bonus_skidka="-".$bonus_skidka_input."".$bonus_skidka_select;
		}else{
			$bonus_skidka="";
		}


		//========END=====СКИДКА======================================
		
		//=============Подарок======================================
		$bonus_gift="";

		
		$cgi=intval($bonRow["cond_gift_input"]);
		$psi=intval($bonRow["period_gift_input"]);
		$pss=$bonRow["period_gift_select"];

		$flag4=0;
		switch ($pss) {
			case 1:
				if($psi>=$count){
					$flag4=1;
				}
				break;
			case 2:
				if($psi<=$sum_price){
					$flag4=1;
				}
				break;
			case 3:
				if($psi<=$userCount){
					$flag4=1;
				}
				break;
			default:
				$flag4=1;
				break;
		}

		$flag3=0;
		switch ($bonRow["cond_gift_select"]) {
			case 1:
				if($cgi>=$count){
					$flag3=1;
				}
				break;
			case 2:
				if($cgi<=$sum_price){
					$flag3=1;
				}
				break;
			case 3:
				if($cgi<=$userCount){
					$flag3=1;
				}
				break;
			default:
				$flag3=0;
				break;
		}


		if($cgi<=$sum_price && $flag3!=0 && $flag4!=0){
			switch ($bonRow["bonus_gift"]) {
				case 1:
					$bonus_gift="Платье";
					break;
				case 2:
					$bonus_gift="Футболка";
					break;
				// case 3:
				// 	$bonus_gift="150 грн";
				// 	break;
				default:
					$bonus_gift="";
					break;
			}
		}

		return array(
			'price'=>$sum_price,
			'bonus_delivery'=>$bonus_delivery, 
			'bonus_skidka_select'=>$bonus_skidka_select, 
			'skidka'=>$bonus_skidka, 
			'gift'=>$bonus_gift
		);
	}

	function bonus2($id){
		$bonus='';
		switch ($id) {
			case 1:
				break;
			case 2:
				break;
			case 3:
				break;
			
			default:
				break;
		}

		return $bonus;
	}

}

?>