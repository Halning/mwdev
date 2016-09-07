<?
if ($_SESSION['status']=="admin"){
	$offer_id = $_POST["offer_id"];
	$com_id= $_POST["com_id"];
	$com = $_POST["com"];
	$count = $_POST["count"];
	$cur_id = $_POST["cur_id"];
	$total_count = $_POST["total_count"];

	$sql = "
	SELECT * 
	FROM  `shop_commodity` WHERE `commodity_ID` = {$com_id} ";

	$res = mysql_query($sql);
	if($row=mysql_fetch_assoc($res)){
		$price_opt = $row["commodity_price2"];
		$price_rozn = $row["commodity_price"];
		if($total_count > 4){
			$price = $price_opt*$count;
		} else{
			$price = $price_rozn*$count;
		}
		$query1 = "
		INSERT INTO `shop_orders_coms`
		SET 
		`offer_id` = '{$offer_id}',
		`com_id` = '{$com_id}',
		`cur_id` ='{$cur_id}',
		`count` = '{$count}',
		`price` = '{$price}',
		`com` = '{$com}'
		";
		mysql_query($query1);
		echo("all good");

	} else{
		echo("EROR");
	}
}
