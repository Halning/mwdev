<?php
ini_set("max_execution_time", "99999");
set_time_limit(99999);
error_reporting(E_ALL^E_NOTICE);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");

	if(isset($_GET['sea'])){
		$arr=array();

		$sea=$_GET['sea'];
		$search="`cat_name` LIKE '{$sea}%' AND";


		$bb=mysql_query("SELECT `categories_of_commodities_parrent`,`cat_name`,categories_of_commodities_ID, `visible`
						FROM `shop_categories` 
						WHERE {$search} `categories_of_commodities_parrent`=10 
						AND `visible`=1 ");
		$i=0;
		while($b=mysql_fetch_assoc($bb)){
			$arr[$i]=array("cat_name"=>$b["cat_name"],"cat_id"=>$b["categories_of_commodities_ID"]);
			$i++;
		}


		echo json_encode($arr);
	}


	if(isset($_GET["ch_sea"])){
		$ch_sea=$_GET["ch_sea"];
		$ssql="";
		if(is_numeric($ch_sea)){
			$ssql="SELECT `categories_of_commodities_ID` , `categories_of_commodities_parrent` , `categoryID` , `commodityID`, `visible`
					FROM `shop_categories` AS a
					INNER JOIN `shop_commodities-categories` AS b ON a.`categories_of_commodities_ID` = b.`categoryID`
					WHERE `categories_of_commodities_parrent` ='{$ch_sea}'
					AND `visible` = 1; ";
			$chh=mysql_query($ssql);
			$arr="";
			$i=0;
			while($c=mysql_fetch_assoc($chh)){
				$arr[$i]=$c["commodityID"];
				$i++;
			}
			$arr_im=implode(",", $arr);
			$imm=mysql_query("SELECT `categories_of_commodities_ID` , `categories_of_commodities_parrent` , `categoryID` , `commodityID`
				FROM `shop_commodities-categories` AS a
				INNER JOIN `shop_categories` AS b ON a.`categoryID` = b.`categories_of_commodities_ID`
				WHERE `categories_of_commodities_parrent` =10
				AND `commodityID` in ({$arr_im})
				AND `visible` = 1
			");
			$arr_up="";
			$i=0;
			while($im=mysql_fetch_assoc($imm)){
				$arr_up[$i]=$im["categories_of_commodities_ID"];
				$i++;
				$cat_parents[$im["categories_of_commodities_ID"]]=$im["categories_of_commodities_parrent"];
			}

			$save=array();
			$save1=array();

			for($i=0; $i<count($arr_up); $i++){	
				$save[intval($arr_up[$i])]=intval($arr_up[$i]);
			}
			for($i=0; $i<count($save); $i++){	
				$save1[$i]=intval($save[$i]);
			//	array_push($save1, $save[$i])
			}
			$dddd=array_values($save);
		}elseif ($ch_sea=="like") {
			$arr_l=array();
			$j=0;
			$ssql="SELECT *
					FROM `shop_categories`
					WHERE `categories_of_commodities_parrent` =10
					AND `count_like` > 0
					AND `visible` = 1
					ORDER BY `count_like` DESC ";
			$chh=mysql_query($ssql);
			while($c=mysql_fetch_assoc($chh)){
				$arr_l[$j]=$c["categories_of_commodities_ID"];
				$j++;
			}
			$dddd=$arr_l;
		}elseif ($ch_sea=="star") {
			$arr_l=array();
			$j=0;
			$ssql="SELECT *
					FROM `shop_categories`
					WHERE `categories_of_commodities_parrent` =10
					AND `rating` > 0
					AND `visible` = 1
					ORDER BY `rating` DESC ";
			$chh=mysql_query($ssql);
			while($c=mysql_fetch_assoc($chh)){
				$arr_l[$j]=$c["categories_of_commodities_ID"];
				$j++;
			}
			$dddd=$arr_l;
		}elseif($ch_sea=="kids" || $ch_sea=="man" || $ch_sea=="woman") {
			$arrCatId=array();
			switch ($ch_sea) {
				case 'kids':
					$arrCatId=array(265, 266, 268);
					break;
				case 'man':
					$arrCatId=array(209, 210, 211);
					break;
				case 'woman':
					$arrCatId=array(261, 264, 267);
					break;
			}

			$impCatId=implode(", ", $arrCatId);

			$arrPush=array();
			$ip=0;
			$perRes=mysql_query("SELECT * FROM `shop_commodities-categories` WHERE `categoryID` IN ({$impCatId}); ");

			while($perRow=mysql_fetch_assoc($perRes)){
				$arrPush[$ip]=$perRow["commodityID"];
				$ip++;
			}
			$imp=implode(", ", $arrPush);

			$arr_l=array();
			$j=0;
			$ssql="SELECT  `categoryID` ,  `commodityID` ,  `categories_of_commodities_ID` ,  `cat_name` ,  `categories_of_commodities_parrent`, `visible`
				FROM  `shop_commodities-categories` AS a
				INNER JOIN  `shop_categories` AS b ON a.`categoryID` = b.`categories_of_commodities_ID` 
				WHERE  `commodityID` 
				IN ({$imp}) 
				AND `categories_of_commodities_parrent` = 10
				AND `visible` = 1
				GROUP BY  `categoryID` ";
			$chh=mysql_query($ssql);
			while($c=mysql_fetch_assoc($chh)){
				$arr_l[$j]=$c["categories_of_commodities_ID"];
				$j++;
			}
			$dddd=$arr_l;
		}elseif($ch_sea=="all" || $ch_sea=="a_all"){
			$arr_l=array();
			$j=0;
			$ssql="SELECT *
					FROM `shop_categories`
					WHERE `categories_of_commodities_parrent` =10
					AND `visible`=1
					ORDER BY  `shop_categories`.`categories_of_commodities_order` ASC ;
				";
			$chh=mysql_query($ssql);
			while($c=mysql_fetch_assoc($chh)){
				$arr_l[$j]=$c["categories_of_commodities_ID"];
				$j++;
			}
			$dddd=$arr_l;
		}

		echo json_encode($dddd);
	}

	if(isset($_GET["commodity"])){
		$catid=$_GET["rel"];
		$ssql="";
		if(is_numeric($catid)){
			$ssql="WHERE `categories_of_commodities_ID`='{$catid}';";
		}
		
		$qqq=mysql_query("SELECT * FROM `shop_categories` {$ssql} ");
		$q=mysql_fetch_assoc($qqq);
		$cat_name=$q["cat_name"];

		$ccc=mysql_query("
			SELECT count( * ) count_bb, `categoryID` , `commodityID` , `commodity_visible` , `commodity_ID`
			FROM `shop_commodities-categories` AS a
			INNER JOIN `shop_commodity` AS b ON a.`commodityID` = b.`commodity_ID`
			WHERE `categoryID` ='{$catid}'
			AND `commodity_visible` =1
			");
		$c=mysql_fetch_assoc($ccc);



		$arr=array("id"=>$catid ,"cat_name"=>$cat_name, "count"=>$c["count_bb"], 'rating'=>$q["rating"], "like"=>$q["count_like"]);
		echo json_encode($arr);
	}
	
?>