<?php

use Modules\MySQLi;

//MySQLi::getInstance()->getConnect()->close();

$templates->set_tpl('{$center}', $center);
$glb["sys_tegs"] = $glb["teg_robots"] ?
    "<meta name='robots' content='noindex,follow' />" :
    "<meta name='robots' content='index,follow' />";
$glb["sys_tegs"] .= isset($glb["canonical"]) ?
    "<link rel='canonical' href='http://{$_SERVER['HTTP_HOST']}{$glb["canonical"]}' >" :
    "";
$glb["sys_tegs"] .= isset($glb["pagination_prev"]) ?
    "<link rel='prev' href='http://{$ass}{$glb["pagination_prev"]}' >" :
    "";
$glb["sys_tegs"] .= isset($glb["pagination_next"]) ?
    "<link rel='next' href='http://{$ass}{$glb["pagination_next"]}' >" :
    "";

if (!isset($_GET["admin"])) {
    $templates->set_tpl('{$cur_v}', $glb["cur"][$glb["cur_id"]]); //Показати валют
    $templates->set_tpl('{$sys_tegs}', $glb["sys_tegs"]);
    $templates->set_tpl('{$main_title}', $glb["title"]);
    $templates->set_tpl('{$main_keywords}', $glb["keywords"]);
    $templates->set_tpl('{$main_description}', $glb["description"]);

    //час роботи сервера  - якщо середовище dev
    $templates->set_tpl(
        '{$time_generate}',
        'dev' === MODE ?
            '<div>'.round((microtime(1) - $time1), 3).'</div>' :
            ''
    );

    if (substr_count($request_url2, "?print") > 0) {
        $center = cleanstring($center);
        $filters_panel = cleanstring($glb["filters_panel"]);
        $filters_panel = $filters_panel != "" ? $filters_panel : "test";
        $ret = end(explode('callback=', $request_url2));
        $ret = current(explode("&_=", $ret));
        echo $ret . "({'text':'{$center}','filters':'{$filters_panel}'})";
        exit();
    }
    echo $templates->get_tpl("main");

} else {
    $templates->set_tpl('{$admin_top_menu}', $admin_top_menu);
    $templates->set_tpl('{$admin_left_menu}', $admin_left_menu . $admin_left_menu_last);
    $templates->set_tpl('{$admin_menus}', $admin_menus);
    $templates->set_tpl('{$its_name}', $its_name);
    $templates->set_tpl('{$it_item}', $it_item);
    $templates->set_tpl('{$additions_buttons}', $additions_buttons);
    $templates->set_tpl('{$main_tab}', $main_tab);
    $templates->set_tpl('{$langs_select}', $langs_select);
    $templates->set_tpl('{$time_generate}', microtime(1) - $time1);
    //$templates->set_tpl('{$admin_content_tree}',get_admin_content_tree());
    $templates->set_tpl('{$admin_content_tree}', "");

    if ($_SESSION['status'] == "admin") {
        echo $templates->get_tpl("{$ending}main");
    } else {
        echo $templates->get_tpl("enter");
    }
}

