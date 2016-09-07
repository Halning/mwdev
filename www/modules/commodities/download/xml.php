<?php

include "commodity.class.php";

header("Content-Type: text/xml");

echo "<?xml version='1.0' standalone='yes'?>
	<mw_commodities>";

	$shop=commodity::getCommodity();
	foreach ($shop as $k => $v) {
		$img=commodity::getPhoto($v['id']);
		$putImg='';
		for($i=0; $i<count($img); $i++){
			if(strpos($img[$i], "s_")!==false){
				$putImg.="http://makewear.com.ua/images/commodities/{$v['id']}/{$img[$i]},\n ";
			}
		}
		echo "
			<commodity>
				<id>
					{$v['id']}
				</id>
				<art>
					{$v['art']}
				</art>
				<categoty_name>
					{$v['cat_name']}
				</categoty_name>
				<name>
					{$v['name']}
				</name>
				<price>
					{$v['price']}
				</price>
				<opt>
					{$v['opt']}
				</opt>
				<size>
					{$v['size']}
				</size>
				<desc>
					{$v['desc']}
				</desc>
				<mw_url>
					http://makewear.com.ua/product/{$v['id']}/{$v['alias']}.html
				</mw_url>
				<from_url>
					{$v['from_url']}
				</from_url>
			</commodity>

		";
// 		fputcsv($fp, array(
// 				$v['id'],
// 				$v['art'],
// 				$v['cat_name'],
// 				$v['name'],
// 				$v['price'],
// 				$v['opt'],
// 				$v['size'],
// 				$v['desc'],
// 				"http://makewear.com.ua/product/{$v['id']}/{$v['alias']}.html",
// 				$v['from_url'],
// 				$putImg
// 				));	
	}

	echo "</mw_commodities>";
?>

