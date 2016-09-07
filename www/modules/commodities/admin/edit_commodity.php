<?php

use Modules\Products;

if ($_SESSION['status'] == "admin") {
	$_SESSION["lastpage2"] = "/?admin=all_commodities";
	if (isset($_GET["commodityID"])) {
		$commodityID = $_GET["commodityID"];
//        creatfolder("images/commodities/{$commodityID}");
		if (isset($_POST["add_commodity"])) {
			$_SESSION["lastpage"] = $request_url;
			$an_n                 = $_POST['name'];
			$an_d                 = $_POST['date'];
			$commodity_old_price  = $_POST['commodity_old_price'];
			$an_status            = $_POST['status'];
			$use_alias            = $_POST["use_alias"];
			$an_f                 = $_POST['full_text'];
			$an_v                 = $_POST['visible'];
			$an_price             = $_POST['price'];
			$an_price2            = $_POST['price2'];
			$vendor               = $_POST['vendor'];
			$order                = is_numeric($_POST['order']) ? $_POST['order']
				: $commodityID;
			$alias                = $_POST['alias'];
			$title                = $_POST['title'];
			$seodescription       = $_POST['description'];
			$keywords             = $_POST['keywords'];
			$content              = $_POST['content'];
			$commodity_action     = $_POST['commodity_action'];
			$commodity_hit        = $_POST['commodity_hit'];
			$commodity_new        = $_POST['commodity_new'];
			$cod                  = $_POST['cod'];
			$from_url             = $_POST["from_url"];
			$cur_id               = $_POST["cur_id"];
			$alvaColors           = '';
			$use_photo            = $_POST["use_photo"];
			$an_bp                = getnewimg(1, 1024, 1024, "commodities",
					$commodityID, "title.jpg", 1) && getnewimg($comitemst,
					$comitemsx, $comitemsy, "commodities", $commodityID,
					"s_title.jpg", 1);
			$bp_update            = ($an_bp) || ($use_photo == 1) ? ", `commodity_bigphoto`='1'"
				: ", `commodity_bigphoto`=''";
			if ($_POST["imgurl"] != "") {
				$an_bp     = getnewimg(1, 1024, 1024, "commodities",
						$commodityID, "title.jpg", 1, $_POST["imgurl"]) && getnewimg($comitemst,
						$comitemsx, $comitemsy, "commodities", $commodityID,
						"s_title.jpg", 1, $_POST["imgurl"]);
				$bp_update = ($an_bp) || ($use_photo == 1) ? ", `commodity_bigphoto`='1'"
					: ", `commodity_bigphoto`=''";
			}
			if ($_POST["frominternet"] == 1) {
				require_once ('includes/simplehtmldom/simple_html_dom.php');
				$total     = file_get_contents("http://www.owercms.com.ua/pr.php?name={$an_n}");
				$html      = str_get_html($total);
				$an_f      = $html->find(".cl_desc", 0);
				$teh       = $html->find(".cl_tech", 0);
				$teh       = $html->find(".cl_tech", 0);
				$cl_img    = $html->find(".cl_img", 0)->plaintext;
				$an_bp     = getnewimg(1, 1024, 1024, "commodities",
						$commodityID, "title.jpg", 1, $cl_img) && getnewimg($comitemst,
						$comitemsx, $comitemsy, "commodities", $commodityID,
						"s_title.jpg", 1, $cl_img);
				$bp_update = ($an_bp) || ($use_photo == 1) ? ", `commodity_bigphoto`='1'"
					: ", `commodity_bigphoto`=''";

				if (count($html->find(".cl_add_img"))) {
					$sql    = "SELECT * FROM `shop_images` WHERE `com_id`='{$commodityID}';";
					$result = mysql_query($sql);
					while ($row    = mysql_fetch_assoc($result)) {
						delete_img('commodities', $commodityID,
							$row['img_id'].'.jpg');
						delete_img('commodities', $commodityID,
							's_'.$row['img_id'].'.jpg');
					}
					$sql = "DELETE FROM `shop_images` WHERE `com_id`='{$commodityID}';";
					mysql_query($sql);
					foreach ($html->find(".cl_add_img") as $a) {

						$sql      = "INSERT INTO `shop_images` SET `com_id`='{$commodityID}';";
						mysql_query($sql) or die(mysql_error());
						$photo_id = mysql_insert_id();
						getnewimg(1, 1024, 1024, "commodities", $commodityID,
							"{$photo_id}.jpg", 1, $a->plaintext);
						getnewimg($addcomimgt, $addcomimgx, $addcomimgy,
							"commodities", $commodityID, "s_{$photo_id}.jpg", 0,
							$a->plaintext);
					}
				}
			}

			if ($_POST['alva-colors'] && count($_POST['alva-colors']) > 0) {
				foreach ($_POST['alva-colors'] as $alvaColor) {
					$alvaColors .= "$alvaColor;";
				}

				$alvaColors = "commodity_select = '".substr($alvaColors, 0, -1)."', ";
			}

			$query = "
			UPDATE `shop_commodity`
			SET
			`commodity_order`='{$order}',
			`commodity_status`='{$an_status}',
			`commodity_visible`='{$an_v}',
			`cur_id`='{$cur_id}',
			`cod`='{$cod}',
			`from_url`='{$from_url}',
			`vendor`='{$vendor}',
			`commodity_price`='{$an_price}',
			`commodity_price2`='{$an_price2}',
			`commodity_old_price`='{$commodity_old_price}',
			`com_name`='{$an_n}',
			`com_fulldesc`='{$an_f}',
			`alias`='{$alias}',
			`lng_id`='{$sys_lng}',
			`use_alias`='{$use_alias}',
			`commodity_action`='{$commodity_action}',
			`commodity_new`='{$commodity_new}',
			`commodity_hit`='{$commodity_hit}',
			{$alvaColors}
			`title`='{$title}',
			`description`='{$seodescription}',
			`keywords`='{$keywords}'
			{$bp_update}
			WHERE `commodity_ID`='{$commodityID}';
			";
			$aa = mysql_query($query);


			$query = "
			DELETE FROM `shop_commodities-categories`
			WHERE `commodityID`='{$commodityID}';";
			mysql_query($query); {
				if (isset($_POST["category"]))
					foreach ($_POST['category'] as $keys => $values) {
						if ($values != 0) {
							$query = "
						INSERT INTO `shop_commodities-categories`
						SET `commodityID`='{$commodityID}', `categoryID`='{$values}';";
							mysql_query($query);

							//set category_id into shop_commodity
							if (in_array($cat_parrent[$values], array(264, 209, 212, 213, 261, 211, 266, 267, 210, 268))) {
								(new Products())
									->setCategory($commodityID, $values)
									->setCategoryIntoProductBrands($commodityID, $values);
							}
						}
					}
				$insert_filter = insert_filter_values($commodityID, $teh);
				$comimgs       = update_comodity_images($commodityID);

				$center = "
				Товар успешно отредактирован<br><br>
				<a href='/?admin=all_commodities'>Список всех товаров</a>
				";
				require_once("templates/$theme_name/mess.php");
			}
		} else {


			$query  = "
			SELECT * FROM `shop_commodity`
			WHERE `commodity_ID`='{$commodityID}';
			";
			$result = mysql_query($query);
			if (mysql_num_rows($result) > 0) {
				$row      = mysql_fetch_object($result);
				$cod      = $row->cod;
				$cur_id   = $row->cur_id;
				$from_url = $row->from_url;

				$brandId  = $row->brand_id;
				$comColors = $row->commodity_select;
				$e_price             = $row->commodity_price;
				$e_price2            = $row->commodity_price2;
				$recomandate         = $row->recomandate;
				$vendor              = $row->vendor;
				$commodity_old_price = $row->commodity_old_price;
				$order               = $row->commodity_order;
				$an_status           = $row->commodity_status;
				$commodity_bigphoto  = $row->commodity_bigphoto;
				$commodity_old_price = $row->commodity_old_price;
				$short_text          = $row->com_desc;
				$full_text           = $row->com_fulldesc;
				$com_fulldesc2       = $row->com_fulldesc2;
				$e_name              = $row->com_name;
				$from_url            = $row->from_url;
				$alias               = $row->alias;
				$title               = $row->title;
				$keywords            = $row->keywords;
				$seodescription      = $row->description;
				$img                 = $commodity_bigphoto != 0 ? "<img src='/images/commodities/{$commodityID}/s_title.jpg'>
				<br /><br />
				<input type='checkbox' name='use_photo' id='id_use_photo' value='1' checked><label for='id_use_photo'>Отображать изображение</label>"
					: "";
				$commodity_action    = $row->commodity_action;
				$commodity_hit       = $row->commodity_hit;
				$commodity_new       = $row->commodity_new;
				$e_visible           = $row->commodity_visible == 1 ? "checked='checked'"
					: "";
				$commodity_action    = $commodity_action == 1 ? "checked='checked'"
					: "";
				$commodity_hit       = $commodity_hit == 1 ? "checked='checked'"
					: "";
				$commodity_new       = $commodity_new == 1 ? "checked='checked'"
					: "";
				$use_alias_checked   = $row->use_alias == 1 ? "checked='checked'"
					: "";
			} else {
				$e_name                             = "Новый товар {$commodityID}";
				$add_date                           = date("Y-m-d H:i:s");
				$query                              = "
				INSERT INTO `shop_commodity` SET
				`commodity_order`='{$commodityID}',
				`lng_id`='{$sys_lng}',
				`com_name`='{$e_name}'
				;";
				mysql_query($query);
				$checkbox_visible                   = "checked";
				$use_alias_checked                  = "checked";
				$active_cats[$_SESSION["category"]] = 1;
				$e_visible                          = "checked='checked'";
				$cod                                = numberFormat($commodityID,
					7);
			}


			//рекомендовані товари, покищо не використовуєм
			/*$sql  = "SELECT * FROM `shop_recommendation`
                INNER JOIN `shop_commodity` ON `commodity_ID`=`rec_other_com_id`
                WHERE `rec_com_id`='{$commodityID}';";
            $res2 = mysql_query($sql) or die(mysql_error());
            while ($row  = mysql_fetch_assoc($res2)) {
                $src1 = $row["commodity_bigphoto"] == 1 ? "/{$row["commodity_ID"]}stitle/{$row["alias"]}.jpg"
                        : "/templates/{$glb["theme_name"]}/img/nophoto.jpg";
                $recommendedCommodities.="<div class='cl_rec_img rec-i'>{$row["com_name"]}<br><img src='{$src1}'><img class='cl_t_del' src='/templates/admin/img/btnbar_del.png'  rel='{$row["commodity_ID"]}'></div>";
            }

            $sql  = "SELECT * FROM `shop_purposes`
                INNER JOIN `shop_commodity` ON `commodity_ID`=`pur_other_com_id`
                WHERE `pur_com_id`='{$commodityID}';";
            $res3 = mysql_query($sql) or die(mysql_error());
            while ($row  = mysql_fetch_assoc($res3)) {
                $src1 = $row["commodity_bigphoto"] == 1 ? "/{$row["commodity_ID"]}stitle/{$row["alias"]}.jpg"
                        : "/templates/{$glb["theme_name"]}/img/nophoto.jpg";
                $purposeCommodities.="<div class='cl_rec_img pur-i'>{$row["com_name"]}<br><img src='{$src1}'><img class='cl_t_del' src='/templates/admin/img/btnbar_del.png'  rel='{$row["commodity_ID"]}'></div>";
            }
            */


			$sql = "
			SELECT * FROM `shop_commodities-categories`
			WHERE `commodityID`='{$commodityID}' AND `categoryID`<>'0';";
			$res = mysql_query($sql);
			while ($row = mysql_fetch_assoc($res)) {
				$active_cats[$row["categoryID"]] = 1;
			}
			if (!count($active_cats)) $active_cats[0] = 1;
			$categories     = get_commodity_categories($active_cats);

			//валюта і наявність товара не використовуєм, тому коментую
			/*if (count($glb["cur_aviable"]))
                    foreach ($glb["cur_aviable"] as $key => $value) {
                    $currsss.=$cur_id == $key ? "<option value='{$key}' selected>{$value}</option>"
                            : "<option value='{$key}'>{$value}</option>";
                }
            if (count($glb["cstatus"]))
                    foreach ($glb["cstatus"] as $key => $value) {
                    $status_options.=$key == $an_status ? "<option value='{$key}' selected>{$value}</option>"
                            : "<option value='{$key}'>{$value}</option>";
                }
            */

			foreach ($active_cats as $categoryId => $enable) {
				while (0 != $categoryId) {
					$active_cats[$categoryId] = 1;
					$categoryId = $cat_parrent[$categoryId];
				}
			}
			$active_cats[0] = 1;

			//далі говнокод, тому закоментив
			/*
            foreach ($active_cats as $key => $value) {
                $fff.=$fff != "" ? ",{$key}" : "{$key}";
            }
            $sql = "
            SELECT * FROM `shop_categories` WHERE `categories_of_commodities_ID` IN ({$fff});";
            $res = mysql_query($sql);
            while ($row = mysql_fetch_assoc($res)) {
                $active_cats[$row["categories_of_commodities_parrent"]] = 1;
            }
            if (count($active_cats))
                    foreach ($active_cats as $key => $value) {
                    $fff.=$fff != "" ? ",{$key}" : "{$key}";
                }
            $sql = "
            SELECT * FROM `shop_categories` WHERE `categories_of_commodities_ID` IN ({$fff});";
            $res = mysql_query($sql);
            while ($row = mysql_fetch_assoc($res)) {
                $active_cats[$row["categories_of_commodities_parrent"]] = 1;
            }

            if (count($active_cats))
                    foreach ($active_cats as $key => $value) {
                    $fff.=$fff != "" ? ",{$key}" : "{$key}";
                }
            $sql = "
            SELECT * FROM `shop_categories` WHERE `categories_of_commodities_ID` IN ({$fff});";
            $res = mysql_query($sql);
            while ($row = mysql_fetch_assoc($res)) {
                $active_cats[$row["categories_of_commodities_parrent"]] = 1;
            }*/

			$alvaColors = $brandId == 43 ? getAlvaColors($comColors) : '';
			$filters            = $alvaColors.get_filters_list($commodityID, $active_cats);
			$commodities_images = '';//get_commodities_images($commodityID);
			$ses_id             = session_id();

			$it_item            = "Редактирование товара";
			$additions_buttons  = get_edit_buttons("/?admin=delete_commodity&commodityID={$commodityID}");
			require_once("modules/commodities/templates/admin.commodity_edit.php");
			require_once("templates/$theme_name/admin.edit.php");
		}
	}
}
