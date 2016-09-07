<?php

	function brenda_name($codd) {
		$arr="";
		$res=mysql_query("SELECT `commodity_ID` , `cod` , `commodityID` , `categoryID`
							FROM `shop_commodity` AS a
							INNER JOIN `shop_commodities-categories` AS b ON a.commodity_ID = b.commodityID
							WHERE `cod` ='{$codd}';");
		while($f=mysql_fetch_assoc($res)){
			$arr=$f['commodity_ID'];
		}
		$up='';
		$ress=mysql_query("SELECT `categories_of_commodities_ID`,`categories_of_commodities_parrent`,`cat_name`,`categoryID`,`commodityID` 
		FROM `shop_categories` AS a INNER JOIN `shop_commodities-categories` AS b ON a.categories_of_commodities_ID=b.categoryID 
		WHERE commodityID={$arr} AND categories_of_commodities_parrent=10; ") or die(mysql_error());
		
		$s=mysql_fetch_assoc($ress);
			$up.=$s['cat_name'];
	
		return $up;
	}

?>