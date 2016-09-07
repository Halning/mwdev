<?php
if ($_SESSION['status'] == "admin") {

    if (is_numeric($_POST["add_new"])) {
        $date = date("Y-m-d H:i:s");
        $sql  = "
		INSERT INTO `parser` SET
		`date`='{$date}';";
        mysql_query($sql);
    }

    //======================
    $query  = "
	SELECT * FROM `parser` 
	ORDER BY `id`
	";
    $result = mysql_query($query);
    if (mysql_num_rows($result) > 0) {
        $categories = "";
        for ($i = 1; $i <= mysql_num_rows($result); $i++) {
            $row     = mysql_fetch_object($result);
            $id      = $row->id;
            $cat_id  = $row->cat_id;
            $h1      = $row->h1;
            $name    = $row->name;
            $img     = $row->img;
            $price   = $row->price;
            $price2  = $row->price2;
            $nonal   = $row->no_nal;
            $sizeCol = $row->sizeColor;
            $desc    = $row->desc;
            $cod     = $row->cod;
            $dopimg  = $row->dopimg;
            $links11 = $row->new_links;
            $per     = $row->per;
            $date    = $row->date;
            $url     = $row->url;
            $from    = $row->from;
            $to      = $row->to;
            $im_url  = $row->im_url;
            $ahref   = $row->a_href;
            require("modules/commodities/templates/admin.parser.line.php");
            $all_lines.=$all_line;
        }
    }

    $global_get_cat    = (is_numeric($show_admin_cat) and $show_admin_cat > 0) ? "&cat_id=".$show_admin_cat
            : '';
    $its_name          = "Все товары";
    $additions_buttons = get_new_buttons2("/?admin=edit_commodity&commodityID={$new_id}",
        "Добавить");
    require("modules/commodities/templates/admin.parser.head.php");
    require_once("templates/$theme_name/admin.all.php");
}
