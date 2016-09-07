<?php

if ($_SESSION['status'] == "admin") {
    $result = mysql_query("SHOW TABLE STATUS LIKE 'shop_categories'");
    $row = mysql_fetch_array($result);
    $new_id = $row['Auto_increment'];
    if (isset($_POST["move"])) {
        $item_id = $_POST["item_id"];
        $sql = "SELECT `categories_of_commodities_order`,`categories_of_commodities_ID`,`categories_of_commodities_parrent` FROM `shop_categories` 
		WHERE `categories_of_commodities_ID`='{$item_id}';";
        $row = mysql_fetch_assoc(mysql_query($sql));
        if ($row) {
            $ccorder = $row["categories_of_commodities_order"];
            $ccparent = $row["categories_of_commodities_parrent"];
        }

        $sing = $_POST["move"] == "down" ? ">" : "<";
        $sing2 = $_POST["move"] == "down" ? "" : "DESC";
        $sql = "SELECT `categories_of_commodities_order`,`categories_of_commodities_ID`,`categories_of_commodities_parrent` FROM `shop_categories` 
		WHERE `categories_of_commodities_order`{$sing}'{$ccorder}' AND `categories_of_commodities_parrent`='{$ccparent}'
		ORDER BY `categories_of_commodities_order` {$sing2}
		LIMIT 0,1;";
        $row = mysql_fetch_assoc(mysql_query($sql));
        if ($row) {
            $order = $row["categories_of_commodities_order"];
            $articleID = $row["categories_of_commodities_ID"];

            $sql = "UPDATE `shop_categories` 
			SET `categories_of_commodities_order`='{$order}'
			WHERE `categories_of_commodities_ID`='{$item_id}';";
            mysql_query($sql);

            $sql = "UPDATE `shop_categories` 
			SET `categories_of_commodities_order`='{$ccorder}'
			WHERE `categories_of_commodities_ID`='{$articleID}';";
            mysql_query($sql);
        } else {
            $sing = $_POST["move"] == "down" ? "+" : "-";
            $query = "
			UPDATE `shop_categories` 
			SET `categories_of_commodities_order`=(`categories_of_commodities_order`{$sing}1)
			WHERE `categories_of_commodities_ID`='{$item_id}';
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
        $cphoto[$row["categories_of_commodities_ID"]] = $row["categories_of_commodities_photo"];
    }

    unset($orders2[0]);

    function ss_nap($cat_id, $st) {
        global $theme_name, $sys_lng, $description, $aliases, $parents, $parents2, $orders, $orders2, $upprices, $cphoto;
        $st++;
        if (count($orders2))
            foreach ($orders2 as $keys => $values) {
                if ($parents[$keys] == $cat_id) {
                    $r_category_id = $keys;
                    $r_category_name = $description[$keys];
                    $alias = $aliases[$keys];
                    $order = $orders[$keys];
                    $upprice = $upprices[$keys];
                    $r_category_photo = $cphoto[$keys] == 1 ? "<img src='/images/categories/{$r_category_id}/s_main.jpg' style='width:50px;'>" : "";
                    $st_t = "";
                    for ($j = 2; $j <= $st; $j++) {
                        $st_t.="&mdash;&mdash;";
                    }
                    $r_category_name = $st_t . $r_category_name;
                    $alias = $alias != "" ? "/c{$r_category_id}_{$alias}/" : "/c{$r_category_id}/";
                    require("modules/commodities/templates/admin.cat.all.line.php");
                    $all_lines.=$all_line;
                    $all_lines.=ss_nap($r_category_id, $st);
                }
            }

        return $all_lines;
    }

    $all_lines = ss_nap(0, 0);
    $its_name = "Все категории товаров";
    $additions_buttons = get_new_buttons("/?admin=add_category", "Добавить категорию");
    $additions_buttons = get_new_buttons("/?admin=edit_category&categoryID={$new_id}", "Добавить");
    require("modules/commodities/templates/admin.cat.all.head.php");
    require_once("templates/$theme_name/admin.all.php");
}
?>