<?php

use  Modules\MySQLi;

if ('' != $_GET['admin']) {

    include('connect.php');

    $_SESSION["upload_dir"] = "uploads";
    if (!get_magic_quotes_gpc()) {
        fix_magic_quotes_gpc();
    }
    $_SESSION["lastpage2"] = $_SESSION["lastpage2"] != $_SESSION["lastpage"] ? $_SESSION["lastpage"] : $_SESSION["lastpage2"];
    $_SESSION["lastpage"] = $_SESSION["currentpage"] != $_SESSION["lastpage"] ? $_SESSION["currentpage"] : $_SESSION["lastpage"];
    $_SESSION["currentpage"] = $glb["request_url"];
}

$glb['mysqli'] = MySQLi::getInstance()->getConnect();

$result = $glb['mysqli']->query(<<<QUERY1
              SELECT * FROM domens
              WHERE domenID = 0
              LIMIT 1
QUERY1
        );

$row = $result->fetch_object();
$doman_name = $row->domen;
$sys_lng = $row->lng_id;
$theme_name = $_GET["admin"] != "" ? "admin" : $row->theme_name;
$glb["theme_name"] = $_GET["admin"] != "" ? "admin" : $row->theme_name;
$glb["domen_id"] = $domenID = $domen_ID = $row->domenID;
$glb["main_page_title"] = $row->main_page_title;
$glb["sys_lng"] = $row->lng_id;
$glb["urlend"] = $row->urlend;
$glb["title"] = $row->title;
$glb["description"] = $row->description;
$glb["keywords"] = $row->keywords;
$glb["content"] = $row->content;
$glb["enable_cache"] = $row->enable_cache;
$glb['slider_limit'] = $row->slider_limit;

$templates = new templ();
$templates->set_tpl('{$urlend}', $row->urlend);
$templates->set_tpl('{$theme_name}', $theme_name);
$templates->set_tpl('{$request_url}', $request_url);
$templates->set_tpl('{$cms_ver}', "4.7.23b");
$glb["templates"] = $templates;

cur_function();
//countr();
