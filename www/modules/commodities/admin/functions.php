<?php

function get_oreder_sum($id) {
    $full_prices = 0;
    global $glb;
    $sql2 = "SELECT * FROM `shop_orders` 
	WHERE `id`='{$id}';";
    $row2 = mysql_fetch_assoc(mysql_query($sql2));

    $sql = "
	SELECT * FROM `shop_orders_coms` 
	WHERE `offer_id`='{$id}';";
    $res = mysql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {

        if ($row["status"] != 2 && $row["status"] != 3) {
            $full_prices+=get_true_price($row["price"] * $row["count"], $row2["cur_id"]);
        }
    }
    $discount_price = round($full_prices / 100 * $row2["discount"]);
    $commission_price = round(($full_prices - $discount_price) / 100);
    $finalprice = get_true_price($row2["delivery_price"], $row2["cur_id"]) + $full_prices + $commission_price - $discount_price;
    return $finalprice;
}

function get_shop_categories_lines($array_categories, $cat_id, $cat_chl_name, $cat_chl_alias, $bstep, $com_url = "", $com_name = "") {
    global $glb;

    $bstep++;
    $ret2 = $cat_id > 0 ? get_shop_categories_lines($array_categories, $array_categories[$cat_id], $cat_chl_name, $cat_chl_alias, $bstep) : "";
    $glb["filter_string"].=" OR `fitr_catid`='{$cat_id}' ";

    return "";
}

function get_filters_panel($array_categories, $cat_id, $cat_url = "", $req_url = '') {
    global $glb, $request_url;
    $request_url = $request_url == "/" ? "/c1_каталог/" : $request_url;
    $sql2 = "
			SELECT 
			`filtr_id`, `filtr_order`, 
			`fitr_catid`, `filtr_typeid`, 
			`necessarily`, `add_date`, 
			`filtr_id`, `lng_id`, 
			`filtr_name`, `filtr_desc` 
			FROM `shop_categories-filters`
			WHERE (`fitr_catid`='{$cat_id}' {$glb["filter_string"]} ) AND `filtr_typeid`='2'
			";

    $result2 = mysql_query($sql2);
    if (mysql_num_rows($result2) > 0) {

        while ($row = mysql_fetch_assoc($result2)) {

            $value = !empty($comodityID) ? get_filter_values($comodityID, $row['filtr_id']) : '';

            switch ($row['filtr_typeid']) {

                case 2: $list = get_parent_filter2($row['filtr_id'], (is_numeric($value) or is_array($value)) ? $value : '');
                    $final_str .= ($row['list_parentfiltrid'] == 0) ? "<div class='cl_minblock'><table><tr><td><div class='cl_tr1'><br /><span class='title_lic'>{$row['filtr_name']}</span>:<br />{$list}<div style='clean:both;'></div></div></td></tr></table></div>" : '';
                    break;
            }
        }
    } else {
        return "<style>.for_check{display:none;}</style>";
    }
    $response = str_replace("/", ";", "{$request_url};");
    $match_expression = "/p1=(.*);/US";
    preg_match($match_expression, $response, $matches);
    if ($matches[1] != "") {
        $glb["p1"] = $matches[1];
    } else {
        $glb["p1"] = 0;
    }
    $match_expression = "/p2=(.*);/US";
    preg_match($match_expression, $response, $matches);
    if ($matches[1] != "") {
        $glb["p2"] = $matches[1];
    } else {
        $glb["p2"] = 5000;
    }

    $ret.="

<style>
.cl_hhhide
{
	display:block;
}
</style>
	";
    $ret.="<div class='list_check clearfix'>" . $final_str . "</div><div style='clean:both;'></div>";
    $ret.=$cat_url != "" ? "
	
	<input type='submit' class='button_check bat' value='Сбросить фильтр' onclick=\"location.href='{$cat_url}';\" />	
	
	" : "";
    return $ret;
}

if ($_SESSION['status'] == "admin" || true) {

    function update_comodity_images($item_id) {
        if (is_array($_POST['photos'])) {
            $delete_photo_ids = array();
            foreach ($_POST['photos'] as $photo) {
                if (is_numeric($photo['id'])) {
                    $photo_id = $photo['id'];
                    $photo_delete = $photo['delete'] == 1 ? 1 : 0;
                    $photo_order = $photo['order'];
                    $photo_desc = $photo['desc'];
                    $photo_name = $photo['name'];
                    if ($photo_delete) {
                        $delete_photo_ids[] = intval($photo['id']);
                    } else {
                        $sql = "UPDATE `shop_images` SET 	`img_name`='{$photo_name}',
														`order`='{$photo_order}',
														`img_desc`='{$photo_desc}' 
												WHERE `img_id`='{$photo_id}'";
                        mysql_query($sql) or die(mysql_error());
                    }
                }
            }

            if (count($delete_photo_ids) > 0) {
                $delete_sql_end = implode(',', $delete_photo_ids);
            }
            $today = date("Y-m-d");
            if (!empty($delete_sql_end)) {
                $delete_sql_end = " `img_id` IN ({$delete_sql_end}) AND `com_id` IN('0','{$item_id}')";
                $sql = "SELECT * FROM `shop_images` WHERE {$delete_sql_end}";
                $result = mysql_query($sql);
                while ($row = mysql_fetch_assoc($result)) {
                    delete_img('commodities', $item_id, $row['img_id'] . '.jpg');
                    delete_img('commodities', $item_id, 's_' . $row['img_id'] . '.jpg');
                }
                $sql = "DELETE FROM `shop_images` WHERE {$delete_sql_end}";
                mysql_query($sql) or die('Ошибка запроса!');
            }
        }
        return true;
    }

    function get_commodities_images($item_id) {
        $photo_items = '';
        $sql = "SELECT * FROM `shop_images` WHERE `com_id`='{$item_id}' ORDER BY `order`";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_assoc($result)) {
            $photo_id = $row['img_id'];
            $photo_name = $row['img_name'];
            $photo_desc = $row['img_desc'];
            $photo_src = "/images/commodities/{$item_id}/" . $photo_id . '.jpg';
            $small_photo_src = "/images/commodities/{$item_id}/s_" . $photo_id . '.jpg';
            require("modules/commodities/templates/photo_item.php");
            $photo_items.=$photo_item;
        }
        return $photo_items;
    }

    function get_commodity_categories(array $active_com_cats, $rootcat = 0, $without = -1) {
        global $glb, $sys_lng, $description, $aliases, $parents, $orders, $orders2, $upprices;

        $sql = "
	SELECT * FROM `shop_categories` 
	WHERE `categories_of_commodities_ID`<>'{$without}'
	ORDER BY `categories_of_commodities_order`;";
        $res = mysql_query($sql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($res)) {
            $parents[$row["categories_of_commodities_ID"]] = $row["categories_of_commodities_parrent"];
            $orders[$row["categories_of_commodities_ID"]] = $row["categories_of_commodities_order"];
            $orders2[$row["categories_of_commodities_ID"]] = $row["categories_of_commodities_order"];
            $upprices[$row["categories_of_commodities_ID"]] = $row["upprice"];
            $description[$row["categories_of_commodities_ID"]] = $row["cat_name"];
            $aliases[$row["categories_of_commodities_ID"]] = $row["alias"];
            $meta_title[$row["categories_of_commodities_ID"]] = $row["title"];
            $meta_description[$row["categories_of_commodities_ID"]] = $row["description"];
            $meta_keywords[$row["categories_of_commodities_ID"]] = $row["keywords"];
            $images[$row["categories_of_commodities_ID"]] = $row["categories_of_commodities_photo"];
        }

        $parents[0] = -1;
        $orders[0] = -1;
        $orders2[0] = -1;
        $description[0] = "Коренвая категория";


        $glb["cat_names"] = $description;
        $glb["cat_aliases"] = $aliases;
        $glb["cat_meta_title"] = $meta_title;
        $glb["cat_meta_description"] = $meta_description;
        $glb["cat_meta_keywords"] = $meta_keywords;
        $glb["cat_parrents"] = $parents;
        $glb["cat_images"] = $images;
        $cat_tree_array = array(
            'current' => 'NULL',
            'items' => array_category_napalm($rootcat, 0, $active_com_cats),
        );
        $result = array(
            'json_tree' => json_encode($cat_tree_array),
            'lines' => get_active_com_cats($active_com_cats, $rootcat),
        );

        return $result;
    }

    function array_category_napalm($cat_id, $st, $active_cats = array()) {
        global $sys_lng, $description, $aliases, $parents, $orders, $orders2, $upprices;
        $st++;
        $result = array();
        foreach ($orders2 as $keys => $values) {
            if ($parents[$keys] == $cat_id) {
                $r_category_id = $keys;
                $r_category_name = $description[$keys];
                $alias = $aliases[$keys];
                $order = $orders[$keys];
                $url = $alias != "" ? "/c{$r_category_id}_{$alias}/" : "/c{$r_category_id}/";
                $selected = $active_cats[$r_category_id] == 1 ? ' selected' : '';
                $children = array_category_napalm($r_category_id, $st, $active_cats);
                $state = count($children[0]) > 0 ? 'closed' : '';
                $result[] = array(
                    'attr' => array(
                        'id' => "node_{$r_category_id}",
                    ),
                    'data' => $r_category_name,
                    'state' => $state,
                    'children' => $children,
                );
            }
        }

        return array($result);
    }

    function get_active_com_cats(array $active_cats, $rootcat = 0) {
        global $sys_lng, $description, $aliases, $parents, $orders, $orders2, $upprices;
        $i = 0;
        if (!count($active_cats))
            $active_cats[0] = 1;

        foreach ($active_cats as $cat_id => $active) {
            if ($active !== 1)
                continue;
            $i++;

            $name = $description[$cat_id] != "" ? $description[$cat_id] : "нет описания";
            $name = $cat_id == 0 && $rootcat == 0 ? 'Выберите категорию' : $name;
            $root_class = ' inp-line';
            $tree = '';
            $button_classes = "remove-product-category remove-categ categ-navig png24";
            if ($i == 1) {
                $root_class = " root-category";
                $button_classes = "add-categ categ-navig png24 add-product-categeroy";
                $tree = '<div class="filetree-block">
						<div class="tree"></div>
                    </div>';
            }
            require('modules/commodities/templates/admin.select_cat_line.php');
            $all_lines.=$line;
        }
        return $all_lines;
    }

    function get_admin_categories_tree2() {
        global $cat_name, $cat_parrent, $theme_name;
        $sql = "SELECT `categories_of_commodities_ID`, `cat_name`,`categories_of_commodities_parrent` FROM `shop_categories` ORDER BY `categories_of_commodities_order`";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $cat_parrent[$row['categories_of_commodities_ID']] = $row['categories_of_commodities_parrent'];
                $cat_name[$row['categories_of_commodities_ID']] = $row['cat_name'];
            }
        }

        $result = napalm_for_admin_cat_tree(0);
        return "<h3>Дерево категорий</h3><div id='multi-derevo'><ul>{$result}</ul></div>";
    }

    function napalm_for_admin_cat_tree2($cat_id) {
        global $cat_name, $cat_parrent, $theme_name;
        if (count($cat_parrent))
            foreach ($cat_parrent as $key => $value) {
                if ($value == $cat_id) {
                    $children = napalm_for_admin_cat_tree($key);
                    $categories .= "<li><span><a class='cl_menu1' id='{$key}' href='#'>{$cat_name[$key]}</a><div class='cl_butt1'><a href='/?admin=add_commodity&cat_id={$key}'><img  src='/templates/{$theme_name}/img/btnbar_add.png'  style='margin-left: 16px;'></a></div></span>{$children}</li>";
                }
            }
        return "<ul>" . $categories . "</ul>";
    }

    function napalm6($cat_id, $st) {
        global $p_category_id;
        global $sys_lng, $urlend;
        $st++;

        $query = "
SELECT * FROM `shop_commodity` 
INNER JOIN `shop_commodities-categories` ON `shop_commodities-categories`.`commodityID`=`shop_commodity`.`commodity_ID`
WHERE `lng_id`='{$sys_lng}' 
AND `categoryID`='{$cat_id}'
ORDER BY `commodity_order`
";
        //die($query);
        $result = mysql_query($query);
        if (mysql_num_rows($result) > 0) {
            for ($i = 1; $i <= mysql_num_rows($result); $i++) {
                $row = mysql_fetch_object($result);
                $r_category_name = $row->com_name;
                $r_category_id = $row->commodity_ID;
                $cat_id = $r_category_id;
                $alias = $row->alias;
                $st_t = "";
                for ($j = 1; $j <= $st; $j++) {
                    $st_t.="——";
                }
                $value = $alias != "" ? "/" . $alias . $urlend : "/commodity/{$r_category_id}{$urlend}";
                $category_category.=" <option value='{$value}-///-{$r_category_name}' ";
                if ($p_category_id == $r_category_id) {
                    $category_category = $category_category . "selected";
                }
                $category_category = $category_category . ">$st_t{$r_category_name} (товар)</option>";
            }
            $e_visible = "checked='checked'";
        }



        return $category_category;
    }

    function napalm5($cat_id, $st) {
        global $p_category_id, $gl_check_commodities, $menu_url, $urlend;
        global $sys_lng;
        $st++;

        $query = "
SELECT * FROM `shop_categories`
WHERE `lng_id`='{$sys_lng}' 
AND `categories_of_commodities_parrent`='{$cat_id}'
ORDER BY `categories_of_commodities_order`;
";
        //die($query);
        $result = mysql_query($query);
        if (mysql_num_rows($result) > 0) {
            for ($i = 1; $i <= mysql_num_rows($result); $i++) {
                $row = mysql_fetch_object($result);
                $r_category_name = $row->cat_name;
                $r_category_id = $row->categories_of_commodities_ID;
                $cat_id = $r_category_id;
                $alias = $row->alias;
                $st_t = "";
                for ($j = 1; $j <= $st; $j++) {
                    $st_t.="——";
                }
                $value = $alias != "" ? "/" . $alias . $urlend : "/commodity/category{$r_category_id}{$urlend}";
                $selected = $menu_url == $value ? "selected" : "";
                $gl_check_commodities = $menu_url == $value ? "checked" : $gl_check_commodities;
                $category_category = $category_category . " <option value='{$value}-///-{$r_category_name}' {$selected}>{$st_t}{$r_category_name} (категория)</option>";
                //=============

                $category_category.=napalm5($cat_id, $sys_lng, $st, $p_category_id);
                //$category_category.=napalm6($cat_id,$st);
                //=============
            }
            $e_visible = "checked='checked'";
        }



        return $category_category;
    }

    function func_module_menu_commodities($url_menu) {
        global $gl_check_commodities, $gl_check, $opti, $urlend;
        $options = napalm5(0, 0);
        $gl_check = $gl_check == "" ? $gl_check_commodities : $gl_check;
        $opti = $gl_check_commodities != "" ? "do_five(\"commodities_div\");" : "";
        $category_category = "

<input type='radio' name='radiob' class='radiob' onclick='do_five(\"commodities_div\");selChange(this.form.commodities_sel);' id='commodities_div' {$gl_check_commodities}>
<label for='commodities_div'>Категории товаров и товары</label><br />
<div class='radio_div' id='commodities_div'>

	<select name='commodities_sel' onChange='do_five(\"commodities_div\");selChange(this.form.commodities_sel);'>
		<option value='/hits{$urlend}-///-Хиты продаж'>Хиты продаж</option>
		<option value='/novelties{$urlend}-///-Новинки'>Новинки</option>
		<option value='/shop-///-Интернет-магазин'>Интернет-магазин (главная)</option> 
		{$options}
	</select>
	
</div>

";

        return $category_category;
    }

    function func_update_com_cats($cat_id) {
        if (is_numeric($cat_id)) {
            $query = "
SELECT * FROM `shop_categories` 
WHERE `categories_of_commodities_ID`='{$cat_id}';
";
            $result = mysql_query($query);
            if (mysql_num_rows($result) > 0) {
                $row = mysql_fetch_object($result);
                $upprice = $row->upprice;
            }
            if (is_numeric($upprice)) {

                $query = "
UPDATE `shop_commodity`
INNER JOIN `shop_commodities-categories` ON  `shop_commodity`.`commodity_ID`=`shop_commodities-categories`.`commodityID`
SET `commodity_price`=(`commodity_virt_price`+`commodity_virt_price`*{$upprice}/100), `commodity_up_price`='{$upprice}'
WHERE `shop_commodities-categories`.`categoryID` ='{$cat_id}' AND `commodity_up_price2`=0
;
";
                mysql_query($query);
            }
        } elseif ($cat_id == "all") {
            $query = "
SELECT * FROM `shop_categories` ;
";
            $result = mysql_query($query);
            if (mysql_num_rows($result) > 0) {
                for ($i = 1; $i <= mysql_num_rows($result); $i++) {
                    $row = mysql_fetch_object($result);
                    $r_category_id = $row->categories_of_commodities_ID;
                    func_update_com_cats($r_category_id);
                }
            }
        }

        return $cat_id;
    }

    function stars_update($com_id) {
        $query = "
SELECT * FROM `shop_images` 
WHERE `com_id`='{$com_id}'
";
        $result = mysql_query($query);
        $fl1 = (mysql_num_rows($result) > 0) ? 1 : 0;

        $query = "
SELECT * FROM `shop_recommendation` 
WHERE `rec_com_id`='{$com_id}'
";
        $result = mysql_query($query);
        $fl2 = (mysql_num_rows($result) > 0) ? 1 : 0;

        $query = "
SELECT * FROM `shop_action` 
WHERE `act_com_id`='{$com_id}'
";
        $result = mysql_query($query);
        $fl3 = (mysql_num_rows($result) > 0) ? 1 : 0;

        $query = "
SELECT * FROM `shop_images2` 
WHERE `com_id`='{$com_id}'
";
        $result = mysql_query($query);
        $fl4 = (mysql_num_rows($result) > 0) ? 1 : 0;

        $query = "
UPDATE `shop_commodity` 
SET
`fl1`='{$fl1}',
`fl2`='{$fl2}',
`fl3`='{$fl3}',
`fl4`='{$fl4}'
WHERE `commodity_ID`='{$com_id}'
";
        mysql_query($query);
    }

    function get_filter_type($filter_type_id) {
        $arr = array(1 => "Текстовое поле", 2 => "Элемент из списка", 3 => "Числовое поле", 4 => "Текст");
        if (isset($arr[$filter_type_id]))
            return $arr[$filter_type_id];
        else
            return '';
    }

    function get_cat_name($cat_id) {
        $sql = "SELECT `cat_name` FROM `shop_categories` WHERE `categories_of_commodities_ID`='{$cat_id}'";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            $row = mysql_num_rows($result);
            return $row['cat_name'];
        } else
            return '';
    }

    function get_select_type($type_id = '') {
        $arr = array(1 => "Строка", 2 => "Фильтр", 3 => "Число", 4 => "Текст");
        $ret = '';
        if (count($arr))
            foreach ($arr as $key => $value) {
                $selected = (!empty($type_id) && $type_id == $key) ? 'selected' : '';
                $ret .= "<option value='{$key}' $selected>{$value}</option>";
            }
        return $ret;
    }

    function get_parent_filter($list_parentfiltrid, $value='', $sel_name='list_parentid')
    {
        $ret = "";
        $options2=0;
        $sql = "SELECT * FROM `shop_filters-lists` WHERE `list_filterid`='{$list_parentfiltrid}' ";
        $result = mysql_query($sql);
        if(mysql_num_rows($result) > 0) {
            while($row = mysql_fetch_assoc($result)){

                if(is_array($value)){
                    $selected=in_array($row['id'], $value)?'selected':'';

                }else{
                    $selected = (is_numeric($value) and $value==$row['id'])?'selected':'';
                }

                $id[0] = $row['id'];
                $ret .= $row["list_parentid"]==0?"<option value='{$row['id']}' {$selected}>{$row['list_name']}</option>":"";
                $options2=$row["list_parentid"]>0?$row["list_parentid"]:$options2;

                $options3=$row["list_parentfiltrid"];
            }
        }

        $selectSize = $list_parentfiltrid == 9 ? 15 : 5;
        $selectDisabled = $list_parentfiltrid == 9 || $list_parentfiltrid == 7 ? '' : ' disabled';
        $ret = <<<HTMLFILTERS
<div id='id_div_{$list_parentfiltrid}'>
    <select class='filter_selecter' name='filter[{$list_parentfiltrid}][]' multiple='multiple' size='$selectSize' id='id_selopt_{$list_parentfiltrid}' $selectDisabled>
        <option value='-'>-</option>
	    {$ret}
	</select>
</div>
HTMLFILTERS;

        $drr=$options2!=""?"

	cat_id2=jQuery('select#id_selopt_{$options3}').val();

	jQuery.getJSON('/plugins/ajax/select_subcategory.php?cat_id='+cat_id2+'&callback=?', {}, function(res1)

	{

		jQuery('#id_div_{$list_parentfiltrid}').html(res1.res);
		jQuery(\"#id_selopt_{$list_parentfiltrid} [value='{$value}']\").attr('selected', 'selected');
	});

":"";

        $ret.=$options2>0?"

<script>

	jQuery('#id_selopt_{$options3}').change(function() {

	cat_id=jQuery('select#id_selopt_{$options3}').val();

	jQuery.getJSON('/plugins/ajax/select_subcategory.php?cat_id='+cat_id+'&callback=?', {}, function(res1)

		{

			jQuery('#id_div_{$list_parentfiltrid}').html(res1.res);

		});

});

{$drr}

</script>":'';

        return $ret;
    }

    function getAlvaColors($colors)
    {
        $listAllColors = array(
            'Аква', 'Красный', 'Синий', 'Желтый', 'Бирюзовый', 'Розовый',
            'Малиновый', 'Коричневый', 'Серый', 'Черный', 'Сиреневый',
            'Голубой', 'Фиолетовый', 'Бежевый', 'Зеленый', 'Оранжевый',
            'Коралловый', 'Белый', 'Бордовый', 'Темно-синий'
        );

        $listComColors = array();
        $options  = '';

        if ($colors) {
            $colors = explode(';', $colors);

            foreach ($colors as $color) {
                $listComColors[] = $color;
            }
        }

        foreach ($listAllColors as $listAllColor) {
            $selected = in_array($listAllColor, $listComColors) ? ' selected' : '';
            $options .= "<option value='$listAllColor'{$selected}>$listAllColor</option>";
        }

        return <<<ALVA
<div>
    <h4>ЦВЕТА АЛЬВА</h4>
    <select class='filter_selecter' name='alva-colors[]' multiple='multiple' size='10'>
        <option value='-'>-</option>
	    $options
	</select>
</div>
ALVA;

    }

    function get_parent_filter2($list_parentfiltrid, $value = '', $sel_name = 'list_parentid') {

        /* global $rek;

          if(!isset($rek)){
          $ret = '';
          $rekurse_str = '';
          $rek = 1;
          }else
          $rek++;
         */

        $_SESSION["filter22"] = $_POST["filter22"] != "" ? $_POST["filter22"] : $_SESSION["filter22"];

        if (count($_SESSION["filter22"]))
            foreach ($_SESSION["filter22"] as $key => $value) {
                $ticheck[$key] = $value;
            }
        $ret = "";
        $options2 = 0;
        $sql = "SELECT * FROM `shop_filters-lists` WHERE `list_filterid`='{$list_parentfiltrid}' ";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {

                if (is_array($value)) {
                    $selected = in_array($row['id'], $value) ? 'selected' : '';
                } else {
                    $selected = (is_numeric($value) and $value == $row['id']) ? 'selected' : '';
                }
                $id[0] = $row['id'];
                $selected = $ticheck[$list_parentfiltrid] == $row['id'] ? "selected" : "";
                $ret .= $row["list_parentid"] == 0 ? "<option value='{$row['id']}' {$selected}>{$row['list_name']}</option>" : "";
                $options2 = $row["list_parentid"] > 0 ? $row["list_parentid"] : $options2;

                $options3 = $row["list_parentfiltrid"];
            }
        }

        $ret = "<div id='id_div_{$list_parentfiltrid}'>

<select class='filter_selecter' name='filter22[{$list_parentfiltrid}]'  id='id_selopt_{$list_parentfiltrid}' onchange=\"this.form.submit();\"> 

	<option value='-'>-</option>
	{$ret}
	</select></div>";

        $drr = $options2 != "" ? "

	cat_id2=jQuery('select#id_selopt_{$options3}').val();
	
	jQuery.getJSON('/plugins/ajax/select_subcategory.php?cat_id='+cat_id2+'&callback=?', {}, function(res1)

	{

		jQuery('#id_div_{$list_parentfiltrid}').html(res1.res);
		jQuery(\"#id_selopt_{$list_parentfiltrid} [value='{$value}']\").attr('selected', 'selected');
	});

" : "";

        $ret.=$options2 > 0 ? "

<script>

	jQuery('#id_selopt_{$options3}').change(function() {

	cat_id=jQuery('select#id_selopt_{$options3}').val();
 
	jQuery.getJSON('/plugins/ajax/select_subcategory.php?cat_id='+cat_id+'&callback=?', {}, function(res1)

		{

			jQuery('#id_div_{$list_parentfiltrid}').html(res1.res);

		});

});

{$drr}

</script>" : '';


        return $ret;
        /* //проверка на наличие детей
          $sql2 = "SELECT * FROM `shop_filters-lists` WHERE `list_parentfiltrid`='{$list_parentfiltrid}'";
          $result2 = mysql_query($sql2);
          if(mysql_num_rows($result2) > 0) {
          $row2 = mysql_fetch_assoc($result2);
          $rekurse_str .= get_parent_filter($row2['list_filterid']);
          }
          return $ret.$rekurse_str;
         */
    }

    function select_parent($l_parentfiltrid) {
        $p_id = array();
        $p_name = array();
        $sql = "SELECT DISTINCT `id` FROM `shop_filters-lists` WHERE `list_filterid`='{$l_parentfiltrid}'";
        $result = mysql_query($sql);
        $final_str = "<option value='0'>Без родителя</option>";
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $p_id[$row['id']] = $row['id'];
            }
            $sql2 = "SELECT `id`, `list_name` FROM `shop_filters-lists`";
            $result2 = mysql_query($sql2);
            if (mysql_num_rows($result2) > 0) {
                while ($row2 = mysql_fetch_assoc($result2)) {
                    $p_name[$row2['id']] = $row2['list_name'];
                }
            }
            if (count($p_id))
                foreach ($p_id as $key => $value) {
                    if ($value == 0)
                        continue;
                    $selected = ($_GET['parent'] == $value) ? 'selected' : '';
                    $final_str .= "<option $selected value='{$value}'>{$p_name[$value]}</option>";
                }
        }
        return "<select  onchange=\"this.form.submit()\" name='parent'>" . $final_str . "</select>";
    }

    function get_filters_list($comodityID = '', $cat_id = '') {
        global $used_cats;

        if (is_array($cat_id)) {
            $linn = " 0=1 ";
            foreach ($cat_id as $key => $value) {
                $linn.=$value != "" ? " OR `fitr_catid`='{$key}' " : "";
            }
        } else {
            $linn.=" OR `fitr_catid`='{$cat_id}' ";
        }
        $linn.=$cat_id2 > 0 ? " OR `fitr_catid`='{$cat_id2}' " : "";

        //echo $linn;die();
        $sql2 = "
	SELECT 
	`filtr_id`, `filtr_order`, 
	`fitr_catid`, `filtr_typeid`, 
	`necessarily`, `add_date`, 
	`filtr_id`, `lng_id`, 
	`filtr_name`, `filtr_desc` 
	FROM `shop_categories-filters`
	WHERE {$linn}
	ORDER BY `filtr_order`
	";
        //echo $sql2;die();
        $result2 = mysql_query($sql2);
        if (mysql_num_rows($result2) > 0) {

            while ($row = mysql_fetch_assoc($result2)) {
                if (10 == $row['filtr_id'] || 12 == $row['filtr_id']) {
                    continue;
                }

                $value = is_numeric($comodityID) ? get_filter_values($comodityID, $row['filtr_id']) : ''; //wwwww
                $checked = get_filter_values2($comodityID, $row['filtr_id']) == 1 ? "checked" : "";
                switch ($row['filtr_typeid']) {
                    case 1: $final_str .= "{$row['filtr_name']}:<br /> <input name='filter[{$row['filtr_id']}]' value='{$value}'> <br />";
                        break;
                    case 2: $list = get_parent_filter($row['filtr_id'], (is_numeric($value) or is_array($value)) ? $value : '');
                        $final_str .= ($row['list_parentfiltrid'] == 0) ? "{$row['filtr_name']}:{$list}<br />" : '';
                        break;
                    case 3: $final_str .= "{$row['filtr_name']}:<br /> <input name='filter[{$row['filtr_id']}]' value='{$value}'><br />";
                        break;
                    case 4: if ($value != "" && $checked) {
                            $final_str .="<b>{$row['filtr_name']}</b> <input type='checkbox' name='filtershow[{$row['filtr_id']}]' rel='{$row['filtr_id']}' value='1' {$checked} id='iddf{$row['filtr_id']}' /> <label for='iddf{$row['filtr_id']}'>отображать поле</label>:<br />
				
					<textarea name='filter[{$row['filtr_id']}]' id='short_text{$row['filtr_id']}'>{$value}</textarea>
					<script type='text/javascript'>
					jQuery(document).ready(function() {
					CKEDITOR.replace('short_text{$row['filtr_id']}', { language: 'ru' });
					});
					</script><br />";
                        } else {
                            $final_str2 .="
					<b>{$row['filtr_name']}</b> <input class='notshow' type='checkbox' name='filtershow[{$row['filtr_id']}]' rel='{$row['filtr_id']}' value='1' {$checked} id='iddf{$row['filtr_id']}' />
					<label for='iddf{$row['filtr_id']}'>отображать поле</label>:<br />
					<div id='id_iddf{$row['filtr_id']}' style='display:none;'><textarea name='filter[{$row['filtr_id']}]' id='short_text{$row['filtr_id']}'>{$value}</textarea></div>		
					";
                        }
                        break;
                }
            }
        }

        //if(!is_numeric($used_cats[$cat_id]))
        //{
        //$sql_parent = "
        //SELECT `categories_of_commodities_parrent` FROM `shop_categories` 
        //WHERE `categories_of_commodities_ID`='{$cat_id}' AND `categories_of_commodities_parrent`>0
        //";
        //$result_parent = mysql_query($sql_parent);
        //if(mysql_num_rows($result_parent) > 0){
        //$row_parent = mysql_fetch_assoc($result_parent);
        //$used_cats[$cat_id]=1;
        //$final_str .=get_filters_list($comodityID, $row_parent['categories_of_commodities_parrent']);
        //}




        return $final_str . $final_str2;
    }

    function insert_filter_values($comodityID, $teh = "") {
        if (is_array($_POST['filter'])) {


            foreach ($_POST['filter'] as $key => $value) {
                if (!is_numeric($key))
                    continue;
                $sql_delete = "DELETE FROM `shop_filters-values` 
			WHERE `ticket_filterid`='{$key}' 
			AND `ticket_id`='{$comodityID}';
			";
                mysql_query($sql_delete) or die("ошибка запроса ($sql_delete)..." . mysql_error());
                if ($value != "") {
                    if (is_array($value)) {
                        foreach ($value as $key2 => $value2) {
                            $sql_insert = "INSERT INTO `shop_filters-values` SET 
						`ticket_filterid`='{$key}', 
						`ticket_id`='{$comodityID}', 
						`ticket_value`='{$value2}'
						;
						";
                            mysql_query($sql_insert) or die("ошибка запроса ($sql_insert)..." . mysql_error());
                        }
                    } else {
                        $visible2 = $_POST["filtershow"];
                        $visible = $visible2[$key];
                        $sql_insert = "INSERT INTO `shop_filters-values` SET 
					`ticket_filterid`='{$key}', 
					`ticket_id`='{$comodityID}', 
					`ticket_value`='{$value}',
					`visible`='{$visible}';
					";
                        mysql_query($sql_insert) or die("ошибка запроса ($sql_insert)..." . mysql_error());
                    }
                }
            }
        }
        if ($teh != "") {
            $sql = "SELECT * FROM `shop_categories-filters` 
			WHERE `filtr_name`='Технические характеристики'";
            $row = mysql_fetch_assoc(mysql_query($sql));
            if ($row) {
                $id = $row["filtr_id"];
                $sql_delete = "DELETE FROM `shop_filters-values` 
				WHERE `ticket_filterid`='{$id}' 
				AND `ticket_id`='{$comodityID}';
				";
                mysql_query($sql_delete);

                $sql_insert = "INSERT INTO `shop_filters-values` SET 
				`ticket_filterid`='{$id}', 
				`ticket_id`='{$comodityID}', 
				`ticket_value`='{$teh}',
				`visible`='1';
				";
                mysql_query($sql_insert);
            }
        }
    }

    function insert_filter_values2($comodityID, $teh = "") {
        if (is_array($_POST['filter'])) {


            foreach ($_POST['filter'] as $key => $value) {
                if (!is_numeric($key))
                    continue;

                if ($value != "") {
                    if (is_array($value)) {
                        foreach ($value as $key2 => $value2) {
                            $sql_insert = "INSERT INTO `shop_filters-values` SET 
						`ticket_filterid`='{$key}', 
						`ticket_id`='{$comodityID}', 
						`ticket_value`='{$value2}'
						;
						";
                            mysql_query($sql_insert) or die("ошибка запроса ($sql_insert)..." . mysql_error());
                        }
                    } else {
                        $visible2 = $_POST["filtershow"];
                        $visible = $visible2[$key];
                        $sql_insert = "INSERT INTO `shop_filters-values` SET 
					`ticket_filterid`='{$key}', 
					`ticket_id`='{$comodityID}', 
					`ticket_value`='{$value}',
					`visible`='{$visible}';
					";
                        mysql_query($sql_insert) or die("ошибка запроса ($sql_insert)..." . mysql_error());
                    }
                }
            }
        }
        if ($teh != "") {
            $sql = "SELECT * FROM `shop_categories-filters` 
			WHERE `filtr_name`='Технические характеристики'";
            $row = mysql_fetch_assoc(mysql_query($sql));
            if ($row) {
                $id = $row["filtr_id"];


                $sql_insert = "INSERT INTO `shop_filters-values` SET 
				`ticket_filterid`='{$id}', 
				`ticket_id`='{$comodityID}', 
				`ticket_value`='{$teh}',
				`visible`='1';
				";
                mysql_query($sql_insert);
            }
        }
    }

    function get_filter_values($comodityID, $filterID) {
        $sql = "SELECT `ticket_value` FROM `shop_filters-values` 
	WHERE `ticket_id`='{$comodityID}' 
	AND `ticket_filterid`='{$filterID}'
	";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) == 1) {
            $row = mysql_fetch_assoc($result);
            return $row['ticket_value'];
        } elseif (mysql_num_rows($result) > 1) {
            while ($row = mysql_fetch_assoc($result)) {
                $arr[] = $row['ticket_value'];
            }
            return $arr;
        }
    }

    function get_filter_values2($comodityID, $filterID) {
        $sql = "SELECT `visible` FROM `shop_filters-values` 
	WHERE `ticket_id`='{$comodityID}' 
	AND `ticket_filterid`='{$filterID}'
	";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) == 1) {
            $row = mysql_fetch_assoc($result);
            return $row['visible'];
        }
    }

    function get_parent_filter_list($filter_id, $f_catid, $parent) {
        $filter_id = intval($filter_id);
        $parent = intval($parent);
        $f_catid = intval($f_catid);
        $final_str = '<option value="0">Без родителя</option>';
        $sql = "
	SELECT 	`filtr_id`, `filtr_order`, `fitr_catid`, 
	`filtr_typeid`, `necessarily`, `list_parent`, `filtr_name`, `filtr_desc` 
	FROM `shop_categories-filters` 
	WHERE `filtr_typeid`=2 AND `filtr_id`!={$filter_id} AND `fitr_catid`={$f_catid}
	";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_assoc($result)) {
            $selected = $row['filtr_id'] == $parent ? 'selected' : '';
            $final_str.="<option value='{$row['filtr_id']}' {$selected}>{$row['filtr_name']}</option>";
        }
        return 'Родительский фильтр: <select name="parent_list">' . $final_str . '</select>';
    }

    function get_admin_categories_tree() {
        global $cat_name, $cat_parrent, $theme_name;
        $sql = "SELECT `categories_of_commodities_ID`, `cat_name`,`categories_of_commodities_parrent` FROM `shop_categories` ORDER BY `categories_of_commodities_order`";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $cat_parrent[$row['categories_of_commodities_ID']] = $row['categories_of_commodities_parrent'];
                $cat_name[$row['categories_of_commodities_ID']] = $row['cat_name'];
            }
        }

        $result = napalm_for_admin_cat_tree(0);
        return "<h3>Дерево категорий</h3><div id='multi-derevo'><ul>{$result}</ul></div>";
    }

    function napalm_for_admin_cat_tree($cat_id) {
        global $cat_name, $cat_parrent, $theme_name;
        if (count($cat_parrent))
            foreach ($cat_parrent as $key => $value) {
                if ($value == $cat_id) {
                    $children = napalm_for_admin_cat_tree($key);
                    $categories .= "<li><span><a class='cl_menu1' id='{$key}' href='#'>{$cat_name[$key]}</a><div class='cl_butt1'><a href='/?admin=add_commodity&cat_id={$key}'><img  src='/templates/{$theme_name}/img/btnbar_add.png'  style='margin-left: 16px;'></a></div></span>{$children}</li>";
                }
            }
        return "<ul>" . $categories . "</ul>";
    }

    function get_color_to_order($com_id) {

        $sql = "SELECT * FROM `shop_commodity` WHERE `commodity_ID` = {$com_id}";
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            $str_color = $row["com_fulldesc"];
        }
        $needle = "Цвет:";
        $findspan = strpos($str_color, $needle);

        if ($findspan !== false) {



            $begin = $findspan + 9;
            $keyword = "</span>";
            $check = strpos($str_color, $keyword, $begin);
            if ($check !== false) {
                $begin = $check + strlen($keyword);
                $end = strpos($str_color, "</", $begin);
            } else {
                $end = strpos($str_color, "</", $begin);
            }
            $end = strpos($str_color, "</", $begin);

            $lenght = $end - $begin;
            $color = substr($str_color, $begin, $lenght);
        } elseif ($findspan === false) {

            $sql2 = "SELECT * FROM `shop_filters-values`
		INNER JOIN `shop_filters-lists` ON `shop_filters-lists`.`id`=`shop_filters-values`.`ticket_value`
		WHERE `ticket_id`='{$com_id}' AND `ticket_filterid`='9' ";
            $res2 = mysql_query($sql2);
            while ($row2 = mysql_fetch_assoc($res2)) {
                $basket_com_color = $row2["list_name"];
            }
            if ($basket_com_color != "") {
                if ($basket_com_color == "colorblack") {
                    $color = "Черный";
                } elseif ($basket_com_color == "colorgray") {
                    $color = "Серый";
                } elseif ($basket_com_color == "colorwhite") {
                    $color = "Белый";
                } elseif ($basket_com_color == "colorred") {
                    $color = "Красный";
                } elseif ($basket_com_color == "colorcoral") {
                    $color = "Кораловый";
                } elseif ($basket_com_color == "colorgold") {
                    $color = "Золотой";
                } elseif ($basket_com_color == "coloryellowgreen") {
                    $color = "Светло-зеленый";
                } elseif ($basket_com_color == "colorgreen") {
                    $color = "Зеленый";
                } elseif ($basket_com_color == "colorteal") {
                    $color = "Бирюзовый";
                } elseif ($basket_com_color == "coloraqua") {
                    $color = "Аква";
                } elseif ($basket_com_color == "colorskyblue") {
                    $color = "Голубой";
                } elseif ($basket_com_color == "colorblue") {
                    $color = "Синий";
                } elseif ($basket_com_color == "colornavy") {
                    $color = "Темно-синий";
                } elseif ($basket_com_color == "colormagenta") {
                    $color = "Малиновый";
                } elseif ($basket_com_color == "colordarkmagenta") {
                    $color = "Темно-фиолетовый";
                } elseif ($basket_com_color == "colorthistle") {
                    $color = "Сиреневый";
                } elseif ($basket_com_color == "colorlightpink") {
                    $color = "Розовый";
                } elseif ($basket_com_color == "colorburlywood") {
                    $color = "Бежевый";
                } elseif ($basket_com_color == "colorsienna") {
                    $color = "Коричневый";
                } elseif ($basket_com_color == "colororange") {
                    $color = "Оранжевый";
                } elseif ($basket_com_color == "colorprint") {
                    $color = "Принт";
                } else {
                    $color = $basket_com_color;
                }
            }
        }

        return $color;
    }

}

function show_cur_for_admin($cur_id) {
    $cur_name = "";
    if ($cur_id == 1) {
        $cur_name = "грн";
    } elseif ($cur_id == 3) {
        $cur_name = "руб";
    } elseif ($cur_id == 2) {
        $cur_name = "usd";
    }
    return $cur_name;
}
