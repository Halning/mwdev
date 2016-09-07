<?php
header("Content-Type: application/x-javascript; charset=UTF-8");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

session_destroy();
//ini_set('display_errors',0);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");
bd_session_start();
require_once("../../../settings/main.php");


if ($_GET["getbasketfull"] == 1) {

    if (count($_SESSION["basket_items"]) > 0) {
        $glb["templates"]->set_tpl('{$offer_city}', $_POST["offer_city"]);


        if (is_numeric($_SESSION["user_id"])) {
            $sql = "SELECT * FROM `users`
		WHERE `user_id`='{$_SESSION["user_id"]}'";
            $row = mysql_fetch_assoc(mysql_query($sql));
            if ($row) {
                $_SESSION["basket_user_email"]   = $_SESSION["basket_user_email"]
                    != "" ? $_SESSION["basket_user_email"] : $row["user_email"];
                $_SESSION["basket_user_name"]    = $_SESSION["basket_user_name"]
                    != "" ? $_SESSION["basket_user_name"] : $row["user_realname"];
                $_SESSION["basket_user_tel"]     = $_SESSION["basket_user_tel"] != ""
                        ? $_SESSION["basket_user_tel"] : $row["user_tel"];
                $_SESSION["basket_user_adsress"] = $_SESSION["basket_user_adsress"]
                    != "" ? $_SESSION["basket_user_adsress"] : $row["user_adr"];
                $_SESSION["basket_user_city"]    = $_SESSION["basket_user_city"]
                    != "" ? $_SESSION["basket_user_city"] : $row["user_city"];
                $_SESSION["discount_val"]        = $row["user_discount"];
            }
            $sql = "SELECT * FROM `shop_orders`
		WHERE `user_id`='{$_SESSION["user_id"]}'
		ORDER BY `date` DESC
		LIMIT 0,1
		";
            $row = mysql_fetch_assoc(mysql_query($sql));
            if ($row) {
                if ($_POST["basket_user_tel2"] == "")
                        $glb["templates"]->set_tpl('{$basket_user_tel2}',
                        $row["offer_housetel"]);

                if ($_POST["offer_city"] == "")
                        $glb["templates"]->set_tpl('{$basket_address}',
                        $row["address"]);
            }
        }else {
            $_SESSION["discount_val"] = 0;
        }

        $glb["templates"]->set_tpl('{$basket_user_email}',
            $_SESSION["basket_user_email"]);
        $glb["templates"]->set_tpl('{$basket_user_name}',
            $_SESSION["basket_user_name"]);
        $glb["templates"]->set_tpl('{$basket_user_tel}',
            $_SESSION["basket_user_tel"]);
        $glb["templates"]->set_tpl('{$basket_user_adsress}',
            $_SESSION["basket_user_adsress"]);
        $glb["templates"]->set_tpl('{$basket_user_comments}',
            $_SESSION["basket_user_comments"]);
        $glb["templates"]->set_tpl('{$basket_user_city}',
            $_SESSION["basket_user_city"]);



        if (count($_SESSION["basket_items"]) > 0)
                foreach ($_SESSION["basket_items"] as $key => $value) {
                $ccc+=$value;
            }

        /* $sql="
          SELECT * FROM `shop_commodities-categories`
          INER JOIN `shop_categories` ON `categories_of_commodities_ID`=`categoryID`
          WHERE `categories_of_commodities_parrent`='10'
          ;";
          $res=mysql_query($sql);
          while($row=mysql_fetch_assoc($res))
          {
          $brends[$row["commodityID"]]=$row["cat_name"];
          }
         */
        if (count($_SESSION["basket_items"]) > 0) {
            $total_amount = 0;

            if (count($_SESSION["basket_items"]))
                    foreach ($_SESSION["basket_items"] as $key => $value) {
                    $arr_data    = explode(';', $key);
                    $arr_data[1] = $arr_data[1] == 'undefined' ? '' : $arr_data[1];
                    $com_id      = trim($arr_data[0]);
                    $lines.=" OR `commodity_ID`='{$com_id}'";
                    $check[$com_id]+=$value;

                    $sql = "SELECT * FROM `shop_commodity`
			WHERE `commodity_ID`='{$com_id}'   ";
                    $row = mysql_fetch_assoc(mysql_query($sql));

                    $pri = 0;
                    if ($ccc >= 10) {
                        $pri = $row['commodity_price2'];
                        if ($row['commodity_price2'] == 0) {
                            $pri = $row['commodity_price'];
                        }
                    } else {
                        $pri = $row['commodity_price'];
                    }

//			$price=get_true_price($ccc>=10?$row['commodity_price2']:$row['commodity_price'],$row['cur_id']);
                    $price    = get_true_price($pri, $row['cur_id']);
                    $priceall = $price * $value;
                    $basket_total_price1+=$priceall;
                    $fullcount+=$value;
                    $height   = $row["height"];
                    $width    = $row["width"];
                    $depth    = $row["depth"];
                    $obem+=( ($height != 0 ? $height : 20) * ($width != 0 ? $width
                                : 20) * ($depth != 0 ? $depth : 20) ) * $value;
                    $ves+=($row["weight"] != 0 ? $row["weight"] : 0.5) * $value;

                    if ($value > 0) {
                        $basket_com_price_one = $priceall / $value;
                    }

                    if (isset($arr_data[2]) && $arr_data[2] != "undefined") {
                        $color = $arr_data[2];
                    } else {
                        require_once("../../../modules/commodities/site/getcolor.php");
                        $color = get_color_to_order($com_id);
                    }
                    $art_null = 0;
                    //--For Cardo size contral count--
                    $art_size = cardo_size($arr_data[0], $arr_data[1]);
                    if (is_numeric($art_size)) {
                        if ($art_size >= $value) {
                            $art_size2 = '';
                            $art_null  = 0;
                        } else {
                            $art_null  = 1;
                            $art_size2 = "Доступно только ".$art_size." ед";
                        }
                    } else {
                        $art_size2 = '';
                        $art_null  = 0;
                    }
                    //	$art_size="";
                    require_once("../../../modules/commodities/admin/brenda_name.php");
                    $brenda = brenda_name($row["cod"]);
                    $glb["templates"]->set_tpl('{$basket_com_url}',
                        $row["alias"] != "" ? "/pr{$com_id}-{$row["alias"]}/" : "/pr{$com_id}/");
                    $glb["templates"]->set_tpl('{$basket_com_src}',
                        $row["commodity_bigphoto"] == 1 || true ? "/{$com_id}stitle/{$alias}.jpg"
                                : "/templates/{$theme_name}/img/nophoto.jpg");
                    $glb["templates"]->set_tpl('{$basket_com_price}', $price);
                    $glb["templates"]->set_tpl('{$basket_com_price_all}',
                        $priceall);
                    $glb["templates"]->set_tpl('{$basket_com_count}', $value);
                    $glb["templates"]->set_tpl('{$basket_com_name}',
                        $row["com_name"]);
                    $glb["templates"]->set_tpl('{$basket_com_cod}', $row["cod"]);
                    $glb["templates"]->set_tpl('{$basket_com_id}', "{$key}");
                    $glb["templates"]->set_tpl('{$basket_com_id2}',
                        "{$arr_data[0]}");
                    $glb["templates"]->set_tpl('{$max_id}', $arr_data[3]);

                    $glb["templates"]->set_tpl('{$art_size}', $art_size2);
                    $glb["templates"]->set_tpl('{$art_null}', $art_null);
                    $glb["templates"]->set_tpl('{$basket_com_desc}',
                        $glb["use_colors_and_sizes"] ? "<b>цвет:</b> {$color_name[$arr_data[1]]}<br /><b>размер:</b> {$size_name[$arr_data[2]]}"
                                : reg_text($row["com_desc"])."<br><b>".$arr_data[1].$color2."</b><br>");

                    $glb["templates"]->set_tpl('{$color}', $color);
                    $glb["templates"]->set_tpl('{$basket_com_price_one}',
                        $basket_com_price_one);
                    $glb["templates"]->set_tpl('{$basket_com_price_opt}',
                        $row['commodity_price2']);
                    $glb["templates"]->set_tpl('{basket_com_price_rozn}',
                        $row['commodity_price']);
                    $glb["templates"]->set_tpl('{$basket_brend}', $brenda);

                    $total_amount += $value;

                    $glb["templates"]->set_tpl('{$basket_total_amount}',
                        $total_amount);

                    $glb["templates"]->set_tpl('{$basket_lines}', $basket_lines);
                    $basket_lines.=$glb["templates"]->get_tpl('shop.basket.full.com_line',
                        "../../../");
                }
        } else {
            return "<h1>Ваша корзина пуста</h1>оформление заказа невозможно";
        }

        /* for($i = 1; $i < 3; $i++)
          {
          if($i == 1){$country_name = "Украина";}else{$country_name = "Россия";}
          if($_SESSION["country"] === $i)
          {
          $select_country_block.= "<option value = '$i' selected>{$country_name}</option>";
          }
          else
          {
          $select_country_block.= "<option value = '$i'>{$country_name}</option>";
          }

          } */


        if ($_SESSION["country"] == 1) {
            $dom_id                      = 0;
            $check1                      = "checked";
            $_SESSION["delivery_method"] = is_numeric($_SESSION["delivery_method"])
                    ? $_SESSION["delivery_method"] < 6 ? $_SESSION["delivery_method"]
                        : 3 : 3;
        } elseif ($_SESSION["country"] == 2) {
            $dom_id                      = 2;
            $check2                      = "checked";
            $_SESSION["delivery_method"] = $_SESSION["delivery_method"] > 5 ? $_SESSION["delivery_method"]
                    : 6;
        } else {
            $dom_id                      = 0;
            $_SESSION["delivery_method"] = is_numeric($_SESSION["delivery_method"])
                    ? $_SESSION["delivery_method"] < 6 ? $_SESSION["delivery_method"]
                        : 3 : 3;
        }
        $sql_dollar = "SELECT  `cur_val`
					   FROM  `shop_cur`";
        $res_dollar = mysql_query($sql_dollar);
        $i          = 0;
        while ($row_dollar = mysql_fetch_assoc($res_dollar)) {
            $cur_val[$i] = $row_dollar["cur_val"];
            $i++;
        }
        $sql = "
		SELECT * FROM `shop_delivery`
		WHERE `dom_id` = {$dom_id} 
		ORDER BY `order`;";
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            if ($row["dom_id"] == 0) {

                $name2                    = $row["price"] > 0 ? " - ".get_true_price($row["price"])." {$cur_show}"
                        : "";
                $name2.=$row["free"] > 0 ? " (бесплатно от ".get_true_price($row["free"])." {$cur_show})"
                        : "";
                $sposoby_dostavki.=$_SESSION["delivery_method"] == $row["id"] ? "<option value='{$row["id"]}' selected>{$row["name"]}{$name2}</option>"
                        : "<option value='{$row["id"]}'>{$row["name"]}{$name2}</option>";
                $prices["delivery_price"] = $_SESSION["delivery_method"] == $row["id"]
                        ? $row["price"] : $prices["delivery_price"];
                if ($_SESSION["delivery_method"] == 3 || $_SESSION["delivery_method"]
                    == 4 || $_SESSION["delivery_method"] == 5) {
                    if ($fullcount <= 2) {
                        $prices["delivery_price"] = 25;
                    } elseif ($fullcount <= 4) {
                        $prices["delivery_price"] = 30;
                    } elseif ($fullcount <= 10) {
                        $prices["delivery_price"] = 40;
                    } elseif ($fullcount <= 20) {
                        $prices["delivery_price"] = 50;
                    } elseif ($fullcount <= 30) {
                        $prices["delivery_price"] = 65;
                    } elseif ($fullcount <= 60) {
                        $prices["delivery_price"] = 85;
                    }

                    //echo $prices["delivery_price"];
                }
            } elseif ($row["dom_id"] == 2) {
                $sposoby_dostavki.=$_SESSION["delivery_method"] == $row["id"] ? "<option value='{$row["id"]}' selected>{$row["name"]}</option>"
                        : "<option value='{$row["id"]}'>{$row["name"]}</option>";

                if ($_SESSION["delivery_method"] == $row["id"]) {
                    $prices["delivery_price"] = round($fullcount * $cur_val[2] / $cur_val[1],
                        0);
                }
            }
            //echo "{$_SESSION["delivery_method"]}=={$row["id"]}";
            //$prices["delivery_price"]=($_SESSION["delivery_method"]==$row["id"] && get_true_price($row["free"])<$basket_total_price1) && $row["free"]>0?0:$prices["delivery_price"];
        }

        $basket_final_price_full += $basket_total_price1;
        $comission                  = $basket_final_price_full * 0.05;
        $basket_final_price_full2 += $basket_final_price_full;
        $_SESSION["delivery_price"] = $prices["delivery_price"];
        $basket_final_price_full += $_SESSION["delivery_price"];
        //$_SESSION["delivery_price2"] = $prices["delivery_price2"];
        //$basket_final_price_full2 +=$comission;
        $sposob_dostavki_block      = "<select id='id_delivery_method'><option>Укажите способ доставки</option>{$sposoby_dostavki}</select>";
        $sposob_dostavki_block2     = "<select id='id_delivery_method'>{$sposoby_dostavki2}<select>";
        //$select_country_block_full = "<select id = 'select_country'><option>Укажите страну</option>".$select_country_block."</select>";
        $basket_final_price_full += $comission;
        /* $sql="
          SELECT * FROM `shop_payments_methods`
          ORDER BY `order`;";
          $res=mysql_query($sql);
          while($row=mysql_fetch_assoc($res))
          {
          $name2=$row["commision"]>0?" -  (коммиссия {$row["commision"]}%)":"";

          $sposoby_oplaty.=$_SESSION["payment_method"]==$row["id"]?"<option value='{$row["id"]}' selected>{$row["name"]}{$name2}</option>":"<option value='{$row["id"]}'>{$row["name"]}{$name2}</option>";
          $prices["payment_commission"]=$_SESSION["payment_method"]==$row["id"]?$row["commision"]:$prices["payment_commission"];
          } */

        $_SESSION["payment_commission"] = $comission;
        $sposob_oplaty_block            = "<select id='id_payment_method'>{$sposoby_oplaty}</select>";


        $basket_total_price2       = round($basket_total_price1 - $basket_total_price1
            * $_SESSION["discount_val"] / 100); //Финальная стоимость с учетом скидки
        $basket_discount_val       = round($basket_total_price1 * $_SESSION["discount_val"]
            / 100);
        $basket_payment_commission = round($basket_total_price2 / 100 * $_SESSION["payment_commission"]);
        $basket_total_price_final  = $basket_total_price2 + $_SESSION["delivery_price"]
            + $basket_payment_commission; //Финальная стоимость с учетом скидки и цены на доставку
        $glb["templates"]->set_tpl('{$sposob_dostavki_block}',
            $sposob_dostavki_block);
        $glb["templates"]->set_tpl('{$check1}', $check1);
        $glb["templates"]->set_tpl('{$check2}', $check2);
        $glb["templates"]->set_tpl('{$sposob_oplaty_block}',
            $sposob_oplaty_block);
        $glb["templates"]->set_tpl('{$basket_total_price1}',
            $basket_total_price1);
        $glb["templates"]->set_tpl('{$basket_lines}', $basket_lines);
        $glb["templates"]->set_tpl('{$basket_discount_val}',
            $basket_discount_val);
        $glb["templates"]->set_tpl('{$basket_discount}',
            $_SESSION["discount_val"] > 0 ? "Ваша скидка {$_SESSION["discount_val"]} %"
                    : "У Вас нет активных скидок");
        $glb["templates"]->set_tpl('{$basket_delivery_price}',
            $_SESSION["delivery_price"]);
        $glb["templates"]->set_tpl('{$basket_payment_commission}',
            $basket_payment_commission);
        $glb["templates"]->set_tpl('{$basket_total_price_final}',
            $basket_total_price_final);

        //$glb["templates"]->set_tpl('{$basket_final_price_full2}',$basket_final_price_full2);
        //$glb["templates"]->set_tpl('{$basket_delivery_price}',$_SESSION["delivery_price"]);
        $glb["templates"]->set_tpl('{$basket_final_price_full}',
            $basket_final_price_full);
        $glb["templates"]->set_tpl('{$comission}', $comission);

        $glb["templates"]->set_tpl('{basket_order_id}', $row["id"]);
        $res = $glb["templates"]->get_tpl('shop.basket.full', "../../../");
    } else {
        $res = "<nobr>Корзина пока пуста</nobr><style>
	.main-content
	{
		padding:0px;
	}
	.content-box
	{
		max-width:1197px;
	}

	.left {
		display: none;
	}
	
	.right {
		float: none;
		width: 100%;
	}

</style>";
    }
    $res = str_replace("\"", "'", $res);
    $res = ereg_replace("
", "", $res);
    $res = str_replace("\r", "", $res);
    $res = str_replace("\n", "", $res);
    $res = str_replace("	", " ", $res);
    echo $_GET['callback']."({res:\"{$res}\"})";
} elseif ($_GET["change_price"] == 1) {
    if ($_GET["p1"] != "" || $_GET["p2"] != "" || $_GET["pr1"] != "" || $_GET["pr2"]
        != "") {
        $uuurkl2      = $_GET["url2"];
        $request_url2 = substr($uuurkl2, 0, strlen($uuurkl2) - 1);
        if (strpos($request_url2, ";p1=") === false) {
            $request_url2.=";p1={$_GET["p1"]}";
        } else {
            $response         = "{$request_url2};";
            $match_expression = "/p1=(.*);/US";
            preg_match($match_expression, $response, $matches);
            if ($matches[1] != "") {
                $request_url2 = str_replace(";p1={$matches[1]}",
                    ";p1={$_GET["p1"]}", $request_url2);
            } else {
                $request_url2.=";p1={$_GET["p1"]}";
            }
        }

        if (strpos($request_url2, ";p2=") === false) {
            $request_url2.=";p2={$_GET["p2"]}";
        } else {
            $response         = "{$request_url2};";
            $match_expression = "/p2=(.*);/US";
            preg_match($match_expression, $response, $matches);
            if ($matches[1] != "") {
                $request_url2 = str_replace(";p2={$matches[1]}",
                    ";p2={$_GET["p2"]}", $request_url2);
            } else {
                $request_url2.=";p2={$_GET["p2"]}";
            }
        }

        if (isset($_GET['rozn'])) {
            if (!strpos($request_url2, 'rozn')) {
                $request_url2 .= ";rozn";
            }
        } else {
            $request_url2 = str_replace(";rozn", "", $request_url2);
        }
        $output = "({text:'{$request_url2}/'})";
    }
    echo $_GET['callback'].$output;
} elseif ($_GET["generate_offer"] == 1) {
    require_once("../../../modules/users/site/functions.php");
    require_once("../../../modules/commodities/site/functions.php");
    generate_offer();
    echo $_GET['callback']."({res:\"1\"})";
} elseif ($_GET["canceloffer"] == 1) {
    $id  = is_numeric($_GET["id"]) ? $_GET["id"] : 0;
    $sql = "
	UPDATE `shop_orders`
	SET `status`='4'
	WHERE `id`='{$id}';";
    mysql_query($sql);
    echo $_GET['callback']."({res:\"1\"})";
} elseif ($_GET["add_rec"] == 1) {
    $ttt    = is_numeric($_GET["ttt"]) ? $_GET["ttt"] : 0;
    $firsid = is_numeric($_GET["firsid"]) ? $_GET["firsid"] : 0;
    if ($ttt > 0) {
        $sql = "
		REPLACE `shop_recommendation`
		SET `rec_com_id`='{$firsid}',`rec_other_com_id`='{$ttt}';";
        mysql_query($sql);
    }

    $sql  = "SELECT * FROM `shop_recommendation`
		INNER JOIN `shop_commodity` ON `commodity_ID`=`rec_other_com_id`
		WHERE `rec_com_id`='{$firsid}';";
    $res2 = mysql_query($sql) or die(mysql_error());
    while ($row  = mysql_fetch_assoc($res2)) {
        $src1 = $row["commodity_bigphoto"] == 1 ? "/{$row["commodity_ID"]}stitle/{$row["alias"]}.jpg"
                : "/templates/{$glb["theme_name"]}/img/nophoto.jpg";
        $res.="<div class='cl_rec_img rec-i'>{$row["com_name"]}<br><img src='{$src1}'  rel='{$row["commodity_ID"]}'><img class='cl_t_del' src='/templates/admin/img/btnbar_del.png'  rel='{$row["commodity_ID"]}'></div>";
    }

    echo $_GET['callback']."({res:\"{$res}\"})";
} elseif ($_GET["del_rec"] == 1) {
    $ttt    = is_numeric($_GET["ttt"]) ? $_GET["ttt"] : 0;
    $firsid = is_numeric($_GET["firsid"]) ? $_GET["firsid"] : 0;
    if ($ttt > 0) {
        $sql = "
		DELETE FROM `shop_recommendation`
		WHERE `rec_com_id`='{$firsid}' AND `rec_other_com_id`='{$ttt}';";
        mysql_query($sql);
    }

    $sql  = "SELECT * FROM `shop_recommendation`
		INNER JOIN `shop_commodity` ON `commodity_ID`=`rec_other_com_id`
		WHERE `rec_com_id`='{$firsid}';";
    $res2 = mysql_query($sql) or die(mysql_error());
    while ($row  = mysql_fetch_assoc($res2)) {
        $src1 = $row["commodity_bigphoto"] == 1 ? "/{$row["commodity_ID"]}stitle/{$row["alias"]}.jpg"
                : "/templates/{$glb["theme_name"]}/img/nophoto.jpg";
        $res.="<div class='cl_rec_img rec-i'>{$row["com_name"]}<br><img src='{$src1}'  rel='{$row["commodity_ID"]}'><img class='cl_t_del' src='/templates/admin/img/btnbar_del.png'  rel='{$row["commodity_ID"]}'></div>";
    }

    echo $_GET['callback']."({res:\"{$res}\"})";
} elseif ($_GET["add_pur"] == 1) {
    $ttt    = is_numeric($_GET["ttt"]) ? $_GET["ttt"] : 0;
    $firsid = is_numeric($_GET["firsid"]) ? $_GET["firsid"] : 0;
    if ($ttt > 0) {
        $sql = "
		REPLACE `shop_purposes`
		SET `pur_com_id`='{$firsid}',`pur_other_com_id`='{$ttt}';";
        mysql_query($sql);
    }

    $sql  = "SELECT * FROM `shop_purposes`
		INNER JOIN `shop_commodity` ON `commodity_ID`=`pur_other_com_id`
		WHERE `pur_com_id`='{$firsid}';";
    $res2 = mysql_query($sql) or die(mysql_error());
    while ($row  = mysql_fetch_assoc($res2)) {
        $src1 = $row["commodity_bigphoto"] == 1 ? "/{$row["commodity_ID"]}stitle/{$row["alias"]}.jpg"
                : "/templates/{$glb["theme_name"]}/img/nophoto.jpg";
        $res.="<div class='cl_rec_img pur-i'>{$row["com_name"]}<br><img src='{$src1}'  rel='{$row["commodity_ID"]}'><img class='cl_t_del' src='/templates/admin/img/btnbar_del.png'  rel='{$row["commodity_ID"]}'></div>";
    }

    echo $_GET['callback']."({res:\"{$res}\"})";
} elseif ($_GET["del_pur"] == 1) {
    $ttt    = is_numeric($_GET["ttt"]) ? $_GET["ttt"] : 0;
    $firsid = is_numeric($_GET["firsid"]) ? $_GET["firsid"] : 0;
    if ($ttt > 0) {
        $sql = "
		DELETE FROM `shop_purposes`
		WHERE `pur_com_id`='{$firsid}' AND `pur_other_com_id`='{$ttt}';";
        mysql_query($sql);
    }

    $sql  = "SELECT * FROM `shop_purposes`
		INNER JOIN `shop_commodity` ON `commodity_ID`=`pur_other_com_id`
		WHERE `pur_com_id`='{$firsid}';";
    $res2 = mysql_query($sql) or die(mysql_error());
    while ($row  = mysql_fetch_assoc($res2)) {
        $src1 = $row["commodity_bigphoto"] == 1 ? "/{$row["commodity_ID"]}stitle/{$row["alias"]}.jpg"
                : "/templates/{$glb["theme_name"]}/img/nophoto.jpg";
        $res.="<div class='cl_rec_img pur-i'>{$row["com_name"]}<br><img src='{$src1}'  rel='{$row["commodity_ID"]}'><img class='cl_t_del' src='/templates/admin/img/btnbar_del.png'  rel='{$row["commodity_ID"]}'></div>";
    }

    echo $_GET['callback']."({res:\"{$res}\"})";
} elseif ($_GET["smena_parolya"] == 1) {
    $user_password = md5($_GET["id"]);
    $sql           = "
	UPDATE `users`
	SET `user_password`='{$user_password}', `user_realpassword`='{$_GET["id"]}'
	WHERE `user_id`='{$_SESSION["user_id"]}';";
    mysql_query($sql);

    echo $_GET['callback']."({res:\"1\"})";
} elseif ($_GET["save_field"] == 1) {
    $sql = "
	UPDATE `users`
	SET `{$_GET["name"]}`='{$_GET["value"]}'
	WHERE `user_id`='{$_SESSION["user_id"]}';";
    mysql_query($sql);

    echo $_GET['callback']."({res:\"1\"})";
} elseif ($_GET["select_country"] == 1) {

    $_SESSION["country"] = $_GET["id"];
    echo $_GET['callback']."({res:\"1\"})";
} elseif ($_GET["select_payment_method"] == 1) {
    $_SESSION["payment_method"] = $_GET["id"];
    echo $_GET['callback']."({res:\"1\"})";
} elseif ($_GET["select_delivery_method"] == 1) {
    $_SESSION["delivery_method"] = $_GET["id"];
    echo $_GET['callback']."({res:\"1\"})";
} elseif ($_GET["basket_save_field"] == 1) {
    $_SESSION[$_GET["name"]] = $_GET["value"];
    echo $_GET['callback']."({res:\"{$_GET["name"]}\"})";
} elseif ($_GET["addtobasket"] == 1) {
    unset($basket_items);
    $itemscount = $_GET["itemcount"];
    if (isset($_GET["basket_com_id"])) {
        if (count($_SESSION["basket_items"]) > 0)
                foreach ($_SESSION["basket_items"] as $key => $value) {
                $basket_items[$key]+=$value;
            }


        $count                    = is_numeric($_GET["basket_com_count"]) ? $_GET["basket_com_count"]
                : 1;
        $count                    = is_numeric($itemscount) ? $itemscount : $count;
        $basket_items[$_GET["basket_com_id"]]+=$count;
        $_SESSION["basket_items"] = $basket_items;
        $res                      = 1;
    }
    $_SESSION['basket_max'] = 5;
    echo $_GET['callback']."({res:\"{$res}\"})";
} elseif ($_GET["select_item"] == 1) {
    if (isset($_GET["basket_com_id"])) {
        $test                     = explode(";", $_GET["basket_com_id"]);
        $test                     = explode(" - ", $test[1]);
        $choosed_item             = $test[0];
        $_SESSION["choosed_item"] = $choosed_item;
        $res                      = 1;
    }
    echo $_GET['callback']."({res:\"{$res}\"})";
} elseif ($_GET["updatebasket"] == 1) {
    unset($basket_items);
    if (isset($_GET["basket_com_id"])) {
        if (count($_SESSION["basket_items"]) > 0)
                foreach ($_SESSION["basket_items"] as $key => $value) {
                $basket_items[$key]+=$value;
            }
        $count                                = is_numeric($_GET["basket_com_count"])
                ? $_GET["basket_com_count"] : 1;
        $basket_items[$_GET["basket_com_id"]] = $count;
        if ($basket_items[$_GET["basket_com_id"]] == 0)
                unset($basket_items[$_GET["basket_com_id"]]);
        $_SESSION["basket_items"]             = $basket_items;
        $res                                  = 1;
    }
    echo $_GET['callback']."({res:\"{$res}\"})";
}elseif ($_GET["getbasket"] == 1) {

    if (count($_SESSION["basket_items"]) > 0)
            foreach ($_SESSION["basket_items"] as $key => $value) {
            $ccc+=$value;
        }
    if (count($_SESSION["basket_items"]) > 0) {
        $res      = "<nobr>Some Error</nobr>";
        if (count($_SESSION["basket_items"])) $erroKeys = '';
        foreach ($_SESSION["basket_items"] as $key => $value) {
            //if(is_numeric($key))
            $key2 = array_shift(explode(";", $key));
            if ($key2 != 'undefined') {
                $ids.=$ids == "" ? "{$key2}" : ",{$key2}";
                $count+=$value;
                $cnts[$key2]+=$value;
            } else {
                $errorKeys .= $key;
            }
        }
        $sql = "
		SELECT * FROM `shop_commodity`
		WHERE `commodity_ID` IN ({$ids});";
        $res = mysql_query($sql);
        //get mysql error, and write it to log file.
        if (!$res) {
            $time  = date('Y-m-d H:i:s');
            $msg   = mysql_error();
            $error = "---------------------------{$time}---------------------------
{$_COOKIE['PHPSESSID']} - '{$ids}'\n{$msg}\n{$errorKeys}\n\n";
            file_put_contents('../../../cache/basket_error_log', $error,
                FILE_APPEND);
        }
        while ($row = mysql_fetch_assoc($res)) {
            $src1                       = $row["commodity_bigphoto"] == 1 ? "/{$row["commodity_ID"]}stitle/{$row["alias"]}.jpg"
                    : "/templates/{$theme_name}/img/nophoto.jpg";
            $bname                      = $row["com_name"];
            //$bname = htmlspecialchars($bname, ENT_QUOTES);
            //$bname = htmlentities($bname, null, 'utf-8');
            $bname                      = htmlspecialchars($bname);
            //$bname=str_replace('&amp;quot;','"',$bname);
            $com_price_offer            = get_true_price($ccc > 4 ? $row["commodity_price2"]
                        : $row["commodity_price"], $row["cur_id"],
                $_SESSION['user_discount']);
            $row["commodity_old_price"] = $row["commodity_old_price"] == 0 ? $row['commodity_price']
                    : $row["commodity_old_price"];
            $com_price_offer_old        = get_true_price($row["commodity_old_price"],
                $row["cur_id"]);
            $glb["templates"]->set_tpl('{$com_cod}', $row["cod"]);
            $glb["templates"]->set_tpl('{$com_src_offer}', $src1);
            $glb["templates"]->set_tpl('{$com_src2}', $src2);
            $cur_price                  = $com_price_offer * $cnts[$row["commodity_ID"]];
            $basket_items_price+=$cur_price;
            $op                         = $com_price_offer_old > $com_price_offer
                    ? "<span class='list-price-old'>{$com_price_offer_old} {$cur_show}</span> "
                    : "";
            $lines.="<li><span class='bp-cell'><img src='{$src1}'></span><span class='cl_basket_inner_items_name bp-cell'>{$bname}<br><span style='color:#000;'>{$op}{$com_price_offer} {$cur_show}</span></span> <span class='bp-cell total-cell'>{$cur_price} {$cur_show} <br>за {$cnts[$row["commodity_ID"]]} шт.</span></li>";
        }
        $lines.=$lines != "" ? "<li class='cl_noborder'><span class='bp-cell' style='text-align: right;'>Итого:</span> <span class='bp-cell total-cell' style='font-size: 1.2em;'>{$basket_items_price} {$cur_show}</span><span class='bp-cell'><a href='/basket/' class='button'>Оформить<br> заказ</a></span></li><li class='cl_noborder'></li>"
                : "";
        $res = "<ul class='cl_basket_inner_items'>{$lines}</ul>";
    } else {
        $res = "<nobr>Корзина пока пуста</nobr>";
    }
    switch ($count) {
        case 0: {
                $countname    = "";
                $ret["count"] = "";
                break;
            }
        case 1: {
                $countname = "товар";
                break;
            }
        case ($ret["count"] > 1 && $ret["count"] < 5): {
                $countname = "товара";
                break;
            }
        default: {
                $countname = "товаров";
            }
    }

    $panel = $count > 0 ? "<a href='/basket/' class='korzina'>Корзина</a><div class='summa'>{$basket_items_price}  <span>{$cur_show}</span></div><div class='items'><b class='cl-summa'>{$count}</b> <span> предметов</span></div>"
            : "<a href='/basket/' class='korzina'>Корзина</a><div class='summa'>0  <span>{$cur_show}</span></div><div class='items'><b class='cl-summa'>0</b> <span> предметов</span></div>";
    echo $_GET['callback']."({res:\"{$res}\",panel:\"{$panel}\"})";
} elseif ($_GET["order"] == 1) {
    echo $_GET['callback']."({res:'{$res}'})";
} elseif ($_GET["quickorder"] == 1) {
    $name  = $_GET["name"];
    $tel   = $_GET["tel"];
    $fmail = $_GET["fmail"];
    if ($name != "") {

        $email   = $fmail;
        $name    = $name;
        $city    = $_GET['city'];
        $phone   = $tel;
        $address = $_GET['address'];
        $sel     = $_GET['select'];

        $comment = $_GET["comment"];

        $idd   = $_GET['idd'];
        $price = $_GET['price'];
        $count = $_GET['count'];

        $sum = $price * $count;

        $cur_id = $_GET['cur'];
        $color  = $_GET['color'];
        $size   = $_GET['size'];

        $today = date("Y-m-d H:i:s");

        $sss = mysql_query("SELECT `cod` FROM `shop_orders` ORDER BY `cod` DESC LIMIT 1;");
        $s   = mysql_fetch_assoc($sss);
        $a   = explode("/", $s['cod']);
        $ans = $a[0] + 1;
        if ($ans < 1000) $ans2.="0".$ans;
        else $ans2.=$ans;
        $ans2.="/1";
        // $res= $ans2;

        mysql_query("
				INSERT INTO `shop_orders`(`status`, `date`, `name`, `email`, `tel`, `city`, `address`,`cod`,`user_comments`,`cur_id`) VALUES (1,'{$today}','{$name}','{$email}','{$phone}','{$city}','{$address}','{$ans2}', '{$comment}','{$cur_id}');
			")or die(mysql_error());
        mysql_query("
				INSERT INTO  `shop_orders_coms` (`offer_id`,`com_id`,`count`,`price`,`cur_id`,`com_color`,`com`) VALUES (last_insert_id(),'{$idd}','{$count}','{$price}','{$cur_id}','{$color}','{$size}');
			")or die(mysql_error());

        //--------For send to mail-----------------
        $_SESSION["last_page"] = "";
        $res                   = "Спасибо! Наши сотрудники свяжутся с вами в ближайшее время.";
        $r_ip                  = $_SERVER['REMOTE_ADDR'];
        $offer_date            = date("Y-m-d H:i:s");
        $mail_text1            = "
						На странице {$_SERVER["HTTP_REFERER"]} сделан быстрый заказ
						Имя: {$name}
						Телефон: {$tel}
						Мейл: {$fmail}
						Время: {$offer_date}
					";
        // Always set content-type when sending HTML email
        $headers               = "MIME-Version: 1.0"."\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8"."\r\n";
        // More headers
        $headers .= 'From: '."noreply@".$glb["dom_mail"]."\r\n";

        send_mime_mail("CMS", "noreply@".$glb["dom_mail"], "Shop Manager",
            $glb["sys_mail"], "utf-8", "utf-8", "Быстрый заказ заказ",
            $mail_text1);
        mail($glb["sys_mail"], "Быстрый заказ заказ", $mail_text1, $headers);
        //echo('error'); die();
    }else {
        $res = "";
    }

    echo $_GET['callback']."({res:'{$res}'})";
} elseif ($_GET["basketclean"] == 1) {
    unset($_SESSION["basket_items"]);
    echo $_GET['callback']."({res:\"1\"})";
}

function cardo_size($id, $size)
{
    $down = "";
    $res  = mysql_query("SELECT *
			FROM `shop_cardo_sizes`
			WHERE `commodity_id` ='{$id}'
			AND `size` = '{$size}'");
    $f    = mysql_fetch_assoc($res);
    if ($f) {
        $down = $f['quantity'];
    } else {
        
    }

    return $down;
}
