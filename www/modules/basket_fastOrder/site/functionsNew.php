<?php
/**
 * Функция страницы корзины
 * @global type $glb
 * @return type html-шаблон
 */
//use Modules\Basket\BasketPjaxFull;
//
//require_once("vendor/autoload.php");
//
///**
// *
// * @global type $glb
// * @return type
// */
//function getPjaxBasketPage()
//{
//    global $glb;
//
//    $basketFull = new BasketPjaxFull($glb["templates"], $glb["cur_val"],
//        $glb["cur"], $glb["cur_id"]);
//
//
//    if ($basketFull->basketItems !== FALSE) {
//        ksort($basketFull->basketItems);
//        $panel = $basketFull->getMainTemplate();
//    } else {
//        $panel = $basketFull->showEmptyBasket();
//    }
//    return $panel;
//}
use Modules\User;
use Modules\MySQLi;
use Modules\Basket\BasketDb;
use Modules\Basket\DiscountGift;
use Modules\Stikers\Stikers;

require_once("vendor/autoload.php");
function getPjaxBasketPage()
{
    global $glb;
    //Со скольки едениц считать оптовые цены
    $countPrice2 = 4;

    // action discount 40%
    if (EXIST_ACTION_BRANDS != '') {
        $actionBrandsArr = explode(',', EXIST_ACTION_BRANDS);
    }
    // end
    // stiker adapter
    $stiker = new Stikers($glb["templates"], $glb["cur_val"]);

    //создаем соединение с БД
    $mysqli       = MySQLi::getInstance()->getConnect();
    $basketSaveDb = new BasketDb(session_id());

    if (is_numeric($_SESSION['user_id'])) {
        $userData = new User($_SESSION['user_id'], $mysqli);
        $userData->getData();
    }

    $basketItems = $basketSaveDb->getBasketData();

    if ($basketItems !== FALSE) {
        ksort($basketItems);
        $fullLines  = '';
        $idsForGTM  = '';
        $totalCount = array();
        $comArr     = array();
        foreach ($basketItems as $key => $value) {
            $comPropArray = explode(";", $key);
            if ($key != 'undefined') {
                $comArr[$key]["size"]  = str_replace("undefined", "",
                    $comPropArray[1]);
                $comArr[$key]["color"] = str_replace("undefined", "",
                    $comPropArray[2]);
                $comArr[$key]["count"] = $value;
                $totalCount[$key] += $value;
            }
        }

        // pl gift
        foreach ($basketItems as $key => $value) {
            $comPropArray   = explode(";", $key);
            $comID          = $comPropArray[0];
            $sqlPlgift      = "
		SELECT commodity_ID, brand_id, com_name, sc.alias, cat_name, commodity_price, commodity_price2, cod, size_count, `com_fulldesc`, category_id
		FROM shop_commodity sc
		INNER JOIN shop_categories cat
		ON cat.categories_of_commodities_ID = sc.brand_id
		WHERE commodity_ID IN ({$comID});";
            $responsePlGift = $mysqli->query($sqlPlgift);
            if ($responsePlGift) {
                $row                 = $responsePlGift->fetch_assoc();
                $totalQuantityPlGift = array_sum($basketItems);
                $pricePlGift         = ceil($row['commodity_price'] * $glb["cur_val"]);
                $pricePlGift2        = ceil($row['commodity_price2'] * $glb["cur_val"]);
                $priceBasketPlGift   = $pricePlGift;
                //opt price
                if ($totalQuantityPlGift > $countPrice2 && $price != $pricePlGift2
                    && $pricePlGift2 != 0 && $row['brand_id'] != 1 && $row['brand_id']
                    != 16 && $row['brand_id'] != 49) {
                    $priceBasketPlGift = $pricePlGift2;
                }

                $sumPlGift = ceil($priceBasketPlGift * $totalCount[$key]);
                $totalSumGift += $sumPlGift;
            }
        }
        $giftCount = 0;
        $ggg       = 0;
        //end

        foreach ($basketItems as $key => $value) {
            $comPropArray = explode(";", $key);
            $comID        = $comPropArray[0];
            $sql          = "
		SELECT commodity_ID, brand_id, com_name, sc.alias, cat_name, commodity_price, commodity_price2, cod, size_count, `com_fulldesc`, category_id
		FROM shop_commodity sc
		INNER JOIN shop_categories cat
		ON cat.categories_of_commodities_ID = sc.brand_id
		WHERE commodity_ID IN ({$comID});";
            $response     = $mysqli->query($sql);
            if ($response) {
                $row    = $response->fetch_assoc();
                $id     = $row['commodity_ID'];
                $idsForGTM .= "'$id', ";
                $wovels = array(";", "(", ")", "undefined", "/", ' ', '-', '.',
                    '+');
                $idHtml = str_replace($wovels, "", $key);

                $alias    = $row['alias'];
                $src      = PHOTO_DOMAIN."{$id}/s_title.jpg";
                $comColor = $comArr[$key]['color'] == "" ? getColorToBasket(
                        $row['com_fulldesc']) : $comArr[$key]['color'];
                $comSize  = $comArr[$key]['size'];

                $totalCount[$key] = checkSizeCount($totalCount[$key], $comSize,
                    $comArr[$key]['color'], $row['size_count'], $id,
                    $basketItems, $basketSaveDb);

                $basketItems   = $basketSaveDb->getBasketData();
                $totalQuantity = array_sum($basketItems);

                $price       = ceil($row['commodity_price'] * $glb["cur_val"]);
                $price2      = ceil($row['commodity_price2'] * $glb["cur_val"]);
                $priceBasket = $price;
                //opt price
                // action discount 40%
                if (($totalQuantity > $countPrice2 && $price != $price2 && $price2
                    != 0 && $row['brand_id'] != 1 && $row['brand_id'] != 16 && $row['brand_id']
                    != 49) || (isset($actionBrandsArr) && in_array($row['brand_id'],
                        $actionBrandsArr))) {
                    $priceBasket = $price2;
                }

                // pl gift
                if (($row['brand_id'] != 16 && $row['brand_id'] != 49 && $row['commodity_price2']
                    >= 1 && $row['commodity_price2'] <= 200 && $row['category_id']
                    == 8) && $giftCount == 0 && $totalSumGift >= 1000 * $glb["cur_val"]) {
                    $giftCount++;
                    $sumOld      = ceil($priceBasket * $totalCount[$key]);
                    $sumOldClass = '';
                    $sum         = ceil($priceBasket * ($totalCount[$key] - $giftCount));
                } else {
                    $sumOld      = 0;
                    $sumOldClass = 'class="hidden"';
                    $sum         = ceil($priceBasket * $totalCount[$key]);
                }

                if (($row['brand_id'] != 16 && $row['brand_id'] != 49 && $row['commodity_price2']
                    >= 1 && $row['commodity_price2'] <= 200 && $row['category_id']
                    == 8) && $ggg == 0) {
                    $ggg++;
                    $idHtmlPlGift = $idHtml;
                }
                // end


                $totalSum += $sum;
                $glb["templates"]->set_tpl('{$commodityId}', $idHtml);
                $glb["templates"]->set_tpl('{$commodityUrl}',
                    "/product/{$id}/{$alias}.html");
                $glb["templates"]->set_tpl('{$Imgsrc}', $src);
                $glb["templates"]->set_tpl('{$brandName}', $row['cat_name']);
                $glb["templates"]->set_tpl('{$commodityName}',
                    htmlspecialchars($row["com_name"]));
                $glb["templates"]->set_tpl('{$commodityCod}',
                    htmlspecialchars($row["cod"]));
                $glb["templates"]->set_tpl('{$commodityColor}', $comColor);
                $glb["templates"]->set_tpl('{$commoditySize}', $comSize);
                $glb["templates"]->set_tpl('{$commodityCount}',
                    $totalCount[$key]);
                $glb["templates"]->set_tpl('{$curSelect}',
                    $glb["cur"][$glb["cur_id"]]);
                $glb["templates"]->set_tpl('{$commodityPrice}', $priceBasket);
                $glb["templates"]->set_tpl('{$commoditySumPrice}', $sum);

                // pl gift
                $glb["templates"]->set_tpl('{$commoditySumOldPrice}', $sumOld);
                $glb["templates"]->set_tpl('{$SumOldPriceHidden}', $sumOldClass);
                $glb["templates"]->set_tpl('{$giftToken}', $idHtmlPlGift);

                // stiker gift
                $stiker->getStikerGift('{$stringGift}',
                    $row['commodity_price2'], $row['brand_id'],
                    $row['category_id']);
                // end

                $fullLines .= $glb["templates"]->get_tpl('basket.full.lines');
            }
        }
        if ($userData->country == 2) {
            $glb["templates"]->set_tpl('{$countryChecked2}', 'checked');
        } else {
            $glb["templates"]->set_tpl('{$countryChecked1}', 'checked');
        }
        $glb["templates"]->set_tpl('{$basketFullLines}', $fullLines);
        $glb["templates"]->set_tpl('{$commodityTotalCount}', $totalQuantity);
        $glb["templates"]->set_tpl('{$totalSumm}', $totalSum);
        $glb["templates"]->set_tpl('{$basketUserName}',
            (isset($_SESSION["basket_user_realname"]) && $_SESSION["basket_user_realname"]
            != "") ? $_SESSION["basket_user_realname"] : (isset($userData->name)
                        ? $userData->name." ".$userData->realName : ""));
        $glb["templates"]->set_tpl('{$basketUserTel}',
            (isset($_SESSION["basket_user_phone"]) && $_SESSION["basket_user_phone"]
            != "") ? $_SESSION["basket_user_phone"] : $userData->phone);
        $glb["templates"]->set_tpl('{$basketUserEmail}',
            (isset($_SESSION["basket_user_email"]) && $_SESSION["basket_user_email"]
            != "") ? $_SESSION["basket_user_email"] : $userData->email);
        $glb["templates"]->set_tpl('{$basketUserCity}',
            (isset($_SESSION["basket_user_city"]) && $_SESSION["basket_user_city"]
            != "") ? $_SESSION["basket_user_city"] : $userData->city);
        $glb["templates"]->set_tpl('{$basketUserDelivery}', $userData->delivery);
        $glb["templates"]->set_tpl('{$basketUserWarehouse}',
            (isset($userData->warehouse)) ? $userData->warehouse : '');
        $glb["templates"]->set_tpl('{$basketUserAddress}',
            (isset($userData->address)) ? $userData->address : '');

        // DiscountGift
        $discGift = new DiscountGift($glb["templates"], $glb["cur_val"],
            $userData->discountGift);
        $discGift->getTemplateForBasketFull();

        $glb["templates"]->set_tpl('{$basketUserComments}',
            $_SESSION["user_comments"]);
        $glb["templates"]->set_tpl('{$idsForGTM}', substr($idsForGTM, 0, -2));
        $glb['templates']->set_tpl('{$info}',
            $glb['templates']->get_tpl('main.info'));
        $panel = $glb['templates']->get_tpl('basket.full');
    } else {
        $glb['templates']->set_tpl('{$info}',
            $glb['templates']->get_tpl('main.info'));
        $panel         = $glb['templates']->get_tpl('basket.full.empty');
        $totalQuantity = 0;
        $totalSum      = 0;
    }
    return $panel;
}

/**
 * get color for basket from description com or filter-color
 *
 * @param string $comFulldesc
 * @return string
 */
function getColorToBasket($comFulldesc)
{
    if (isset($comFulldesc)) {
        $comDescString = str_replace("&nbsp;", '',
            htmlspecialchars_decode($comFulldesc));
    }

    $needle    = "Цвет:";
    $findColor = strstr($comDescString, $needle);

    if ($findColor !== false) {
        $wovels   = array('Цвет:</span>', 'Цвет:');
        $comColor = str_replace($wovels, "", strstr($findColor, "</p>", true));
    } else {
        $comColor = "Цвет как на фото";
    }
    return $comColor;
}

/**
 * Функция проверки количества по размеру
 * @param type $totalCountInt
 * @param type $comSize
 * @param type $sizeCount
 * @param type $idCom
 * @return int count of com
 */
function checkSizeCount($totalCountInt, $comSize, $comColor, $sizeCount, $idCom,
                        $basketItems, $basketSaveDb)
{
    if ($comSize !== "" && $sizeCount !== "") {
        $sizeCountArr = explode(";", $sizeCount);
        foreach ($sizeCountArr as $value) {
            $countAval = (int) str_replace('=', '', strstr($value, "="));
            if (strpos($value, $comSize) !== false && $countAval < $totalCountInt) {
                $totalCountInt = $countAval;
            }
        }

        foreach ($basketItems as $key => $value) {
            if (strpos($key, $idCom) !== false && $comColor === '' && strpos($key,
                    $comSize) !== false) {
                $basketItems[$key] = $totalCountInt;
                $basketSaveDb->updateData(serialize($basketItems));
            } else if (strpos($key, $idCom) !== false && $comColor !== '' && strpos($key,
                    $comColor) !== false && strpos($key, $comSize) !== false) {
                $basketItems[$key] = $totalCountInt;
                $basketSaveDb->updateData(serialize($basketItems));
            }
        }
    }
    return $totalCountInt;
}
