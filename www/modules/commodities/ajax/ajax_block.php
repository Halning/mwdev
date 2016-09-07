<?php

namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$db = MySQLi::getInstance()->getConnect();

	if (isset($_GET['name'])) {
		$id=$_GET['id'];
		$name=$_GET['name'];
		$text=$_GET['text'];
		//$text=strip_tags($text);
		$url=$_GET['url'];
		$seo_title=$_GET['seo_title'];
		$seo_desc=$_GET['seo_desc'];

		$db -> query("UPDATE `shop_blocks` SET `name`='{$name}', `url`='{$url}', `title`='{$text}', `seo_title`='{$seo_title}', `seo_desc`='{$seo_desc}' WHERE `id`='{$id}' ");
	}

//echo "Mysql Block {$_GET['name']}";

?>