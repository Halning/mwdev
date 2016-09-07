<?php

if ($_SESSION['status'] == "admin") {

    $_SESSION["category"] = is_numeric($_POST["category"]) ? $_POST["category"] : $_SESSION["category"];
    $_SESSION["category"] = is_numeric($_GET["category"]) ? $_GET["category"] : $_SESSION["category"];
    $_SESSION["category"] = is_numeric($_SESSION["category"]) ? $_SESSION["category"] : 0;
    if (isset($_POST["move"])) {
        $item_id = $_POST["item_id"];
        $sql = "SELECT `filtr_order`,`filtr_id`,`fitr_catid` FROM `shop_categories-filters` 
		WHERE `filtr_id`='{$item_id}';";
        $row = mysql_fetch_assoc(mysql_query($sql));
        if ($row) {
            $ccorder = $row["filtr_order"];
            $ccparent = $row["fitr_catid"];
        }
        $sing = $_POST["move"] == "down" ? ">" : "<";
        $sing2 = $_POST["move"] == "down" ? "" : "DESC";
        $sql = "SELECT `filtr_order`,`filtr_id` FROM `shop_categories-filters` 
		WHERE `filtr_order`{$sing}'{$ccorder}' AND `fitr_catid`='{$ccparent}'
		ORDER BY `filtr_order` {$sing2}
		LIMIT 0,1;";
        $row = mysql_fetch_assoc(mysql_query($sql));
        if ($row) {
            $order = $row["filtr_order"];
            $articleID = $row["filtr_id"];

            $sql = "UPDATE `shop_categories-filters` 
			SET `filtr_order`='{$order}'
			WHERE `filtr_id`='{$item_id}';";
            mysql_query($sql);

            $sql = "UPDATE `shop_categories-filters` 
			SET `filtr_order`='{$ccorder}'
			WHERE `filtr_id`='{$articleID}';";
            mysql_query($sql);
        } else {
            $sing = $_POST["move"] == "down" ? "+" : "-";
            $query = "
			UPDATE `shop_categories-filters` 
			SET `filtr_order`=(`filtr_order`{$sing}1)
			WHERE `filtr_id`='{$item_id}';
			";
            mysql_query($query);
        }
    }

    $sql = "
		SELECT * FROM `shop_categories` ORDER BY `categories_of_commodities_order`;";
    $res = mysql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {
        $parents[$row["categories_of_commodities_ID"]] = $row["categories_of_commodities_parrent"];
        $orders[$row["categories_of_commodities_ID"]] = $row["categories_of_commodities_order"];
        $orders2[$row["categories_of_commodities_ID"]] = $row["categories_of_commodities_order"];
        $upprices[$row["categories_of_commodities_ID"]] = $row["upprice"];
        $description[$row["categories_of_commodities_ID"]] = $row["cat_name"];
        $aliases[$row["categories_of_commodities_ID"]] = $row["alias"];
    }

    unset($orders2[0]);

    function ss_nap($cat_id, $st, $curcat) {
        global $theme_name, $sys_lng, $description, $aliases, $parents, $orders, $orders2, $upprices, $categoriesss, $show_admin_cat;
        $st++;
        if (count($orders2))
            foreach ($orders2 as $keys => $values) {
                if ($parents[$keys] == $cat_id) {
                    $r_category_id = $keys;
                    $r_category_name = $description[$keys];
                    $alias = $aliases[$keys];
                    $order = $orders[$keys];
                    $upprice = $upprices[$keys];
                    $st_t = "";
                    for ($j = 2; $j <= $st; $j++) {
                        $st_t.="&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                    $r_category_name = $st_t . $r_category_name;
                    $selected = $r_category_id == $curcat ? "selected" : "";
                    $r_category_name = strip_tags($r_category_name);
                    $all_lines.="<option value='{$r_category_id}' {$selected}>{$r_category_name}</option>";
                    $all_lines.=ss_nap($keys, $st, $curcat);
                }
            }

        return $all_lines;
    }

    $comm_category = "<option value='0'>Кореневой каталог</option>";
    $comm_category.=ss_nap(0, 0, $_SESSION["category"]);
    $all_params = "<form action='/?admin=all_filters' method='POST'>Категория:<br><select name='category' onChange  = 'this.form.submit()'>{$comm_category}</select></form>

		
		<div style='overflow:hidden;'><form action='{$reauest_url}' method='POST'>{$filters_panel}</form></div>
		";


    $ftype[1] = "Строка";
    $ftype[2] = "Фильтр";
    $ftype[3] = "Число";
    $ftype[4] = "Текст";
    $cat_id = $_SESSION["category"];
    get_shop_categories_lines($parents, $cat_id, $cat_chl_name, $cat_chl_alias, $bstep);
    $sel_cat = is_numeric($cat_id) ? "WHERE (`fitr_catid`='{$cat_id}' {$glb["filter_string"]} )" : "WHERE (`fitr_catid`='0' )";
    $cat_name = is_numeric($cat_id) ? get_cat_name($cat_id) : '';
    $sql = "
	SELECT 	`filtr_id`, `filtr_order`, `fitr_catid`, 
	`filtr_typeid`, `necessarily`, `filtr_name`, `filtr_desc` 
	FROM `shop_categories-filters`  {$sel_cat} ORDER BY `filtr_order`;
	";
    $result = mysql_query($sql);
    $all_lines = '';
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $cat_name2 = $row['fitr_catid'] > 0 ? $description[$row['fitr_catid']] : "Кореневой каталог";
            $f_id = $row['filtr_id'];
            $f_order = $row['filtr_order'];
            $f_catid = $row['fitr_catid'];
            $f_type = $ftype[$row['filtr_typeid']];
            $man_list = '';//$row['filtr_typeid'] == 2 ? "<a style='font-size:11px;' href='/?admin=maneger_list&filterID={$f_id}'><img src='/templates/{$theme_name}/images/list.png' style='height:32px;'></a>" : '';
            $f_neces = $row['necessarily'];
            $f_name = $row['filtr_name'];
            $f_desc = $row['filtr_desc'];
            $cat_lines = "<select id='id_select_{$f_id}'><option value='0'>Кореневой каталог</option>" . ss_nap(0, 0, $row['fitr_catid']) . "</select>";
            $type_lines = "";
            foreach ($ftype as $key => $value) {
                $type_lines.=$key == $row['filtr_typeid'] ? "<option value='{$key}' selected>{$value}</option>" : "<option value='{$key}'>{$value}</option>";
            }
            $type_lines = "<select id='id_select2_{$f_id}'>{$type_lines}</select>";
            require("modules/commodities/templates/admin.filter.all.line.php");
            $all_lines .= $all_line;
        }
    }


    $its_name = "Все дополнительные поля";


    $additions_buttons = get_new_buttons("/?admin=add_filters&catID=" . $_SESSION["category"], "Добавить поле");

    require("modules/commodities/templates/admin.filter.all.head.php");

    require_once("templates/$theme_name/admin.all.php");
}
?>