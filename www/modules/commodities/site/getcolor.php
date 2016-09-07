<?
function get_color_to_order($com_id){
	if(!$com_id || $com_id=="undefined"){
		return false;
	}
	$sql = "SELECT * FROM `shop_commodity` WHERE `commodity_ID` = {$com_id}";
	$res=mysql_query($sql);
	if($row=mysql_fetch_assoc($res))
	{
		$str_color = $row["com_fulldesc"];
	}
	$needle = "Цвет:";
	$findspan = strpos($str_color, $needle);

	if($findspan !== false){

		

		$begin = $findspan + 9;
		$keyword = "</span>";
		$check = strpos($str_color, $keyword, $begin);
		if($check !== false){
			$begin = $check + strlen($keyword);
			$end = strpos($str_color, "</", $begin);
			
		} else{
			$end = strpos($str_color, "</", $begin);
		}
		$end = strpos($str_color, "</", $begin);
		
		$lenght = $end - $begin;
		$color = substr($str_color, $begin, $lenght);
		

	} elseif($findspan === false) {

		$sql2="SELECT * FROM `shop_filters-values`
		INNER JOIN `shop_filters-lists` ON `shop_filters-lists`.`id`=`shop_filters-values`.`ticket_value`
		WHERE `ticket_id`='{$com_id}' AND `ticket_filterid`='9' ";
		$res2=mysql_query($sql2);
		while($row2=mysql_fetch_assoc($res2))
		{
			$basket_com_color=$row2["list_name"];			
		}
		if ($basket_com_color !="")
		{
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
	}

	return $color;
}
