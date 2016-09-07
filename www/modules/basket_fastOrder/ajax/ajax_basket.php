<?php

use \Modules\MySQLi;
use \Modules\Mail;
use Modules\User;
use Modules\Basket\BasketDb;
use Modules\Basket\DiscountGift;
use LisDev\Delivery\NovaPoshtaApi2;

require_once("../../../vendor/autoload.php");
require_once("../../../settings/functionsNew.php");
bd_session_start();
$basketSaveDb = new BasketDb(session_id());
require_once("../../../settings/main.php");

//получаем basket с js кода аяксом
$getBasket = filter_input(INPUT_GET, 'basket', FILTER_SANITIZE_STRING);
if (!isset($getBasket)) {
    $getBasket = filter_input(INPUT_POST, 'basket', FILTER_SANITIZE_STRING);
}

//создаем соединение с БД
$mysqli = MySQLi::getInstance()->getConnect();

//Свитч по функциям по данным из аякса
switch ($getBasket) {
    case "miniBasket":
        getMiniBasket($mysqli, $basketSaveDb);
        break;
    case "addtobasket":
        addToBasket($basketSaveDb);
        break;
    case "basketclean":
        basketClean($basketSaveDb);
        break;
    case "deleteitemfrombasket":
        deleteItemFromBasket($mysqli, $basketSaveDb);
        break;
    case "updateCountItem":
        updateCountItem($mysqli, $basketSaveDb);
        break;
    case "selectCountryAndCheckout":
        selectCountryAndCheckout($mysqli);
        break;
    case "getCurrency":
        getCurrency();
        break;
    case "sendOrder":
        sendOrder($mysqli, $basketSaveDb);
        break;
    case "getEmptyBasket":
        getEmptyBasket();
        break;
    case "saveFieldsValue":
        saveFieldsValue();
        break;
    case "getWarehouseNovaPosta":
        getWarehouseNovaPosta();
        break;
}

/**
 * Получить отделения новой почты в своем городe
 */
function getWarehouseNovaPosta()
{
    $userCity   = filter_input(INPUT_GET, 'userCity', FILTER_SANITIZE_STRING);
    $userRegion = filter_input(INPUT_GET, 'userRegion', FILTER_SANITIZE_STRING);
    $np         = new NovaPoshtaApi2(
        NOVA_POSHTA_KEY,
        'ru', // Язык возвращаемых данных: ru (default) | ua | en
        FALSE, // При ошибке в запросе выбрасывать Exception: FALSE (default) | TRUE
        'curl' // Используемый механизм запроса: curl (defalut) | file_get_content);
    );

    // В параметрах указывается город и область (для более точного поиска)
    $city  = $np->getCity($userCity, $userRegion);
    $error = 1;

    if ($city['success'] == true) {
        $ref        = (isset($city['data'][0][0]['Ref'])) ? $city['data'][0][0]['Ref']
                : $city['data'][0]['Ref'];
        $warehouses = $np->getWarehouses($ref);
        $error      = 0;
    }

    echo json_encode(array(
        'error' => $error,
        'warehouses' => $warehouses['data']
    ));
}

//==============================================================================
//                           Функции
//==============================================================================
/**
 * Функция добавления товара в корзину
 */
function addToBasket($basketSaveDb)
{
    $comId = filter_input(INPUT_GET, 'basket_com_id', FILTER_SANITIZE_STRING);
    if (isset($comId)) {
        $basketItems = $basketSaveDb->getBasketData();
        if ($basketItems !== FALSE) {
            $first = false;
        } else {
            $basketItems = array();
            $first       = true;
        }
        $itemsCount = filter_input(INPUT_GET, 'itemcount',
            FILTER_SANITIZE_NUMBER_INT);
        $comSize    = filter_input(INPUT_GET, 'size', FILTER_SANITIZE_STRING);
        $comColor   = filter_input(INPUT_GET, 'color', FILTER_SANITIZE_STRING);
        $item       = isset($comSize) ? $comId.';'.$comSize : $comId.';';

        if (isset($comColor)) {
            $item .= ';'.$comColor;
        }
        $basketItems[$item] += $itemsCount;
        $basketSaveDb->insertDataOrUpdate($basketItems, $first);
        $error = 0;
    } else {
        $error = 1;
    }
    echo json_encode(array('error' => $error));
}

/**
 * Функция-показать маленькую корзину
 * @global type $glb
 * @param type $mysqli
 */
function getMiniBasket($mysqli, $basketSaveDb)
{
    global $glb;
    //Со скольки едениц считать оптовые цены
    $countPrice2 = 4;

    // action discount 40%
    if (EXIST_ACTION_BRANDS != '') {
        $actionBrandsArr = explode(',', EXIST_ACTION_BRANDS);
    }
    // end

    $basketItems = $basketSaveDb->getBasketData();
    //var_dump($basketItems);
    if ($basketItems !== FALSE) {
        $lines         = '';
        $result        = getComPtopFromDbMiniBasket($mysqli, $basketItems);
        $totalQuantity = $result[3];

        if (isset($result[0])) {
            foreach ($result[0] as $key => $value) {
                $id          = $key;
                $alias       = $value['alias'];
                $src         = PHOTO_DOMAIN."{$id}/s_title.jpg";
                $price       = ceil($value['commodity_price'] * $glb["cur_val"]);
                $price2      = ceil($value['commodity_price2'] * $glb["cur_val"]);
                $priceBasket = $price;
                //opt price
                // action discount 40%
                if (($totalQuantity > $countPrice2 && $price != $price2 && $price2
                    != 0 && $value['brand_id'] != 1 && $value['brand_id'] != 16 && $value['brand_id']
                    != 49) || (isset($actionBrandsArr) && in_array($value['brand_id'],
                        $actionBrandsArr))) {
                    $priceBasket = $price2;
                }

                $sum = ceil($priceBasket * $result[1][$id]);
                $totalSum += $sum;
                $glb["templates"]->set_tpl('{$src}', $src);
                $glb["templates"]->set_tpl('{$brandName}', $value['cat_name']);
                $glb["templates"]->set_tpl('{$commodityName}',
                    htmlspecialchars($value["com_name"]));
                $glb["templates"]->set_tpl('{$commodityQuantity}',
                    $result[1][$id]);
                $glb["templates"]->set_tpl('{$cur_v}',
                    $glb["cur"][$glb["cur_id"]]);
                $glb["templates"]->set_tpl('{$price}', $priceBasket);
                $glb["templates"]->set_tpl('{$sum}', $sum);
                $lines .= $glb["templates"]->get_tpl('basket.hidden.lines',
                    '../../../');
            }
            $glb["templates"]->set_tpl('{$lines}', $lines);
            $glb["templates"]->set_tpl('{$totalQuantity}', $totalQuantity);
            $glb["templates"]->set_tpl('{$totalSumm}', $totalSum);
            $panel = $glb['templates']->get_tpl('basket.hidden', '../../../');
            $error = 0;
        } else {
            $error = 1;
        }
    } else {
        $panel         = $glb['templates']->get_tpl('basket.hidden.empty',
            '../../../');
        $error         = 0;
        $totalQuantity = 0;
        $totalSum      = 0;
    }

    $result = json_encode(array(
        'error' => $error,
        'qty' => $totalQuantity,
        'sum' => $totalSum,
        'panel' => $panel
    ));
    echo $result;
}

/**
 *  Функция полной очистки корзины
 */
function basketClean($basketSaveDb)
{
    $basketSaveDb->deleteData();
    $error = 0;
    echo json_encode(array('error' => $error));
}

/**
 * Функция удаления одного товара из корзины
 * @global type $glb
 * @param type $mysqli
 */
function deleteItemFromBasket($mysqli, $basketSaveDb)
{
    global $glb;
    $comId = filter_input(INPUT_GET, 'basket_com_id', FILTER_SANITIZE_STRING);

    // pl gift
    $plToken = filter_input(INPUT_GET, 'plGiftExist', FILTER_SANITIZE_STRING);
    // end

    $basketItems = $basketSaveDb->getBasketData();

    if (isset($comId)) {
        foreach ($basketItems as $key => $value) {
            $wovels = array(";", "(", ")", "undefined", "/", ' ', '-', '.', '+');
            if (str_replace($wovels, "", $key) == $comId) {

                unset($basketItems[$key]);
                if (!empty($basketItems)) {
                    $basketSaveDb->updateData(serialize($basketItems));
                } else {
                    $basketSaveDb->deleteData();
                }
            }
        }
        $error = 0;
    } else {
        $error = 1;
    }
    $result = getCommoditiesPtopertiesFromDb($mysqli, $basketItems);

    // pl gift
    $giftExist[$plToken] = $result[3];
    // end

    echo json_encode(array(
        'error' => $error,
        'comId' => $comId,
        "currency" => $glb["cur_val"],
        "priceBasket" => $result[1],
        "plGift" => $giftExist,
        "countItemArr" => $result[0]
    ));
}

/**
 * Функция уменьшения или уменьшения количества товара в корзине
 * @global type $glb
 * @param type $mysqli
 */
function updateCountItem($mysqli, $basketSaveDb)
{
    global $glb;
    $comId      = filter_input(INPUT_GET, 'basket_com_id',
        FILTER_SANITIZE_STRING);
    $arithmetic = filter_input(INPUT_GET, 'arithmetic', FILTER_SANITIZE_STRING);
    $countItem  = filter_input(INPUT_GET, 'countItem', FILTER_SANITIZE_STRING);

    // pl gift
    $plToken = filter_input(INPUT_GET, 'plGiftExist', FILTER_SANITIZE_STRING);
    // end

    $basketItems = $basketSaveDb->getBasketData();

    $resultBetwen = getCommoditiesPtopertiesFromDb($mysqli, $basketItems);

    $countItemCheck = checkSizeCount($countItem, $resultBetwen[2], $comId);

    if (isset($comId)) {
        foreach ($basketItems as $key => $value) {
            $wovels = array(";", "(", ")", "undefined", "/", ' ', '-', '.', '+');
            if (str_replace($wovels, "", $key) == $comId) {
                $basketItems[$key] = $countItemCheck;
                $basketSaveDb->updateData(serialize($basketItems));
            }
        }

        $limitSizeCount = 0;
        if ($countItem != $countItemCheck) {
            $limitSizeCount = 1;
        }
        $error = 0;
    } else {
        $error = 1;
    }
    $result = getCommoditiesPtopertiesFromDb($mysqli, $basketItems
    );

    // pl gift
    $giftExist[$plToken] = $result[3];
    // end

    echo json_encode(array(
        'error' => $error,
        'arithmetic' => $arithmetic,
        'comId' => $comId,
        "countItem" => $countItemCheck,
        "currency" => $glb["cur_val"],
        "priceBasket" => $result[1],
        "countItemArr" => $result[0],
        "plGift" => $giftExist,
        "limitSizeCount" => $limitSizeCount
    ));
}

/**
 * Функция проверки количества по размеру
 * @param int $totalCountInt - com count input
 * @param type $comArr
 * @param type $comId
 * @return int com count out
 */
function checkSizeCount($totalCountInt, $comArr, $comId)
{
    foreach ($comArr as $key => $value) {
        $wovels          = array(";", "(", ")", "undefined", "/", ' ', '-', '.',
            '+');
        $key             = str_replace($wovels, '', $key);
        $comArrNew[$key] = $value;
    }

    $comSize = str_replace($comArrNew[$comId]['com_id'], "", $comId);

    if ($comSize !== "" && $comArr['size_count'] !== "") {
        foreach ($comArr as $key => $value) {
            $wovels       = array(";", "(", ")", "undefined", "/", ' ', '-', '.',
                '+');
            $key          = str_replace($wovels, "", $key);
            $comArr[$key] = $value;
        }
        $sizeCount = $comArr[$comId]['size_count'];

        $sizeCountArr = explode(";", $sizeCount);
        foreach ($sizeCountArr as $value) {
            $wovels    = array(";", "(", ")", "undefined", "/", ' ', '-', '.', '+');
            $value     = str_replace($wovels, "", $value);
            $countAval = (int) str_replace('=', '', strstr($value, "="));
            if (strpos($value, $comSize) !== false && $countAval < $totalCountInt) {
                $totalCountInt = $countAval;
            }
        }
    }
    return (int) $totalCountInt;
}

/**
 * Функция Выбор страны
 * @global type $glb
 * @param type $mysqli
 */
function selectCountryAndCheckout($mysqli)
{
    global $glb;
    $country = filter_input(INPUT_GET, 'country', FILTER_SANITIZE_STRING);
    if (isset($country)) {
        if ($country == 1) {
            $domId          = 0;
            $deliveryMethod = 0;
        } else if ($country == 2) {
            $domId = 2;
        }
        $result = getDeliveryMetFromDb($domId, $mysqli);
        $error  = 0;
    } else {
        $error = 1;
    }
    echo json_encode(array(
        'error' => $error,
        'delivery' => $result['delivery'],
        'deliveryMethod' => $deliveryMethod,
        "currency" => $glb["cur_val"]
    ));
}

/**
 *  Функция сохранения введеных пользователем данных
 */
function saveFieldsValue()
{
    $fieldValue = filter_input(INPUT_GET, 'value', FILTER_SANITIZE_STRING);
    $fieldName  = filter_input(INPUT_GET, 'fieldName', FILTER_SANITIZE_STRING);
    if (isset($fieldValue)) {
        switch ($fieldName) {
            case "realname":
                $_SESSION["basket_user_realname"] = $fieldValue;
                break;
            case "phone":
                $_SESSION["basket_user_phone"]    = $fieldValue;
                break;
            case "email":
                $_SESSION["basket_user_email"]    = $fieldValue;
                break;
            case "city":
                $_SESSION["basket_user_city"]     = $fieldValue;
                break;
            case "comments":
                $_SESSION["user_comments"]        = $fieldValue;
                break;
        }
        $error = 0;
    } else {
        $error = 1;
    }
    echo json_encode(array(
        'error' => $error
    ));
}

/**
 * Функция оформления заказа
 * @global type $glb
 * @param type $mysqli
 */
function sendOrder($mysqli, $basketSaveDb)
{
    global $glb;

    if (is_numeric($_SESSION['user_id'])) {
        $userData = new User($_SESSION['user_id'], $mysqli);
        $userData->getData();
    }
    $discGift = new DiscountGift($glb["templates"], $glb["cur_val"],
        $userData->discountGift);

    $userEmail = filter_input(INPUT_POST, 'basket_user_email',
        FILTER_SANITIZE_STRING);
    if (isset($userEmail)) {

        //Записываем данные из формы
        $date                  = date("Y-m-d");
        $userCountry           = filter_input(INPUT_POST, 'country',
            FILTER_SANITIZE_STRING);
        $userName              = filter_input(INPUT_POST, 'basket_user_name',
            FILTER_SANITIZE_STRING);
        $deliveryMethodId      = filter_input(INPUT_POST,
            'basket_delivery_method', FILTER_SANITIZE_STRING);
        $userTel               = filter_input(INPUT_POST, 'basket_user_tel',
            FILTER_SANITIZE_STRING);
        $userCity              = filter_input(INPUT_POST, 'basket_user_city',
            FILTER_SANITIZE_STRING);
        $userAdressOrWarehouse = filter_input(INPUT_POST,
            'basket_user_warehouse', FILTER_SANITIZE_STRING);
        $userComments          = filter_input(INPUT_POST,
            'basket_user_comments', FILTER_SANITIZE_STRING);

        // pl gift
        $plGiftExist = filter_input(INPUT_POST, 'plGiftExist',
            FILTER_SANITIZE_STRING);
        //end

        $resultArr = getUserPropByEmail($userEmail, $mysqli);
        if ($userCountry == 1) {
            $domId   = 0;
            $userTel = "+38 (".substr($userTel, 0, -7)
                .") ".substr($userTel, 3, -4)
                ."-".substr($userTel, 6, -2)
                ."-".substr($userTel, -2);
        } else {
            $domId   = 2;
            $userTel = "+7 ("
                .substr($userTel, 0, -7)
                .") ".substr($userTel, 3, -4)
                ."-".substr($userTel, 6, -2)
                ."-".substr($userTel, -2);
        }

        //Выборка данных по доставке
        $resultDevelory = getDeliveryMetFromDb($domId, $mysqli);

        //Генерируем код заказа
        $query    = "SELECT COUNT(*) AS `c` FROM `shop_orders`
	WHERE `date`>'{$date} 00:00:00' AND `date`<'{$date} 23:59:59' LIMIT 1";
        if ($response = $mysqli->query($query)) {
            $row = $response->fetch_assoc();
            $c   = $row["c"] + 1;
            $cod = date("md")."/{$c}";
            $response->free();
        }

        $basketItems = $basketSaveDb->getBasketData();

        //Запись заказа в БД
        //Выборка данных по товарам
        if ($basketItems !== FALSE) {
            $error         = 1;
            $orderDate     = date("Y-m-d H:i:s");
            $freePrice     = $resultDevelory['deliveryPrice'][$deliveryMethodId]
                * $glb["cur_val"];
            $result        = getCommoditiesPtopertiesFromDb($mysqli,
                $basketItems);
            $totalQuantity = array_sum($basketItems);

            if (isset($result[0])) {
                foreach ($result[1] as $key => $value) {

                    // pl gift
                    if ($result[3] == 1 && $plGiftExist == $key) {
                        $sum = ceil(ceil($value * $glb["cur_val"]) * ($result[0][$key]
                            - 1));
                    } else {
                        $sum = ceil(ceil($value * $glb["cur_val"]) * $result[0][$key]);
                    }
                    $sumPrice += $sum;
                }

                //Формирования цены доставки
                if ($deliveryMethodId == 3 || $deliveryMethodId == 4 || $deliveryMethodId
                    == 5) {
                    if ($totalQuantity <= 2) {
                        $deliveryPrice = 25 * $glb["cur_val"];
                    } else if ($totalQuantity <= 4) {
                        $deliveryPrice = 30 * $glb["cur_val"];
                    } else if ($totalQuantity <= 10) {
                        $deliveryPrice = 40 * $glb["cur_val"];
                    } else if ($totalQuantity <= 20) {
                        $deliveryPrice = 50 * $glb["cur_val"];
                    } else if ($totalQuantity <= 30) {
                        $deliveryPrice = 65 * $glb["cur_val"];
                    } else if ($totalQuantity <= 60) {
                        $deliveryPrice = 85 * $glb["cur_val"];
                    }
                } else if ($deliveryMethodId == 6 || $deliveryMethodId == 7) {
                    $curGrnforDol  = 100 / ($glb['cur_val_bax'] * 100);
                    $deliveryPrice = ($curGrnforDol * $glb["cur_val"]) * $totalQuantity;
                } else if ($deliveryMethodId == 2 /* && $sumPrice < $freePrice */) {
                    $deliveryPrice = 50 * $glb["cur_val"];
                }

                // discountGift
                $discountGift = $discGift->getDiscountGiftForSendOrder($totalQuantity);
                $discCod      = ($discountGift != 0) ? 1 : 0;

                $commision     = 0; //ceil($sumPrice * 0.03);
                $deliveryPrice = ceil($deliveryPrice);
                $totalSum      = $sumPrice + $deliveryPrice - $discountGift; //+ $commision;
                //Запись заказа в БД
                $stmt          = $mysqli->stmt_init();
                if (!($stmt          = $mysqli->prepare("
                    INSERT INTO `shop_orders`
                    SET
                    `cod`=?,
                    `date`=?,
                    `name`=?,
                    `email`=?,
                    `tel`=?,
                    `city`=?,
                    `address`=?,
                    `user_comments`=?,
                    `user_id`=?,		
                    `delivery`=?,
                    `delivery_price`=?,
                    `discount`=?,
                    `commission`=?,
                    `cur_id`=?"))) {
                    die('Insert shop_orders Error ('.$mysqli->errno.') '.$mysqli->error);
                }
                if (!$stmt->bind_param("ssssssssiiiiii", $cod, $orderDate,
                        $userName, $userEmail, $userTel, $userCity,
                        $userAdressOrWarehouse, $userComments,
                        $resultArr['userId'], $deliveryMethodId, $deliveryPrice,
                        $discCod, $commision, $glb["cur_id"])) {
                    die('Insert shop_orders Error ('.$stmt->errno.') '.$stmt->error);
                }
                if (!$stmt->execute() || !$stmt->close()) {
                    die('Insert shop_orders Error ('.$stmt->errno.') '.$stmt->error);
                }

                $orderId   = $mysqli->insert_id;
                $inTheList = 0;

                // pl gift
                $giftCount = 0;
                // end
                //Подготовка перемнных для шаблона после отправки заказа
                foreach ($result[2] as $key => $value) {
                    $alias        = $value['alias'];
                    $comPropArray = explode(";", $key);
                    $comID        = $comPropArray[0];
                    $src          = PHOTO_DOMAIN."{$comID}/s_title.jpg";

                    if ($comID != 'undefined') {
                        $comSize  = str_replace('undefined', "",
                            $comPropArray[1]);
                        $comColor = str_replace('undefined', "",
                                $comPropArray[2]) == "" ? getColorToBasket(
                                $value['com_fulldesc']) : str_replace('undefined',
                                "", $comPropArray[2]);
                    }
                    $wovels      = array(";", "(", ")", "undefined", "/", ' ', '-',
                        '.', '+');
                    $key         = str_replace($wovels, "", $key);
                    $priceBasket = ceil($result[1][$key] * $glb["cur_val"]);
                    $sum         = ceil($priceBasket * $result[0][$key]);
                    $inTheList ++;

                    // pl gift
                    if ($result[3] == 1 && $plGiftExist == $key) {
                        $giftCount++;
                        $sumOld          = ceil($priceBasket * $result[0][$key]);
                        $sumOldClass     = '';
                        $sumOldClassMail = 'class="hidden"';
                        $sum             = ceil($priceBasket * ($result[0][$key]
                            - $giftCount));
                    } else {
                        $sumOld          = 0;
                        $sumOldClass     = 'class="hidden"';
                        $sumOldClassMail = 'display:none;';
                        $sum             = ceil($priceBasket * $result[0][$key]);
                    }
                    // end

                    $glb["templates"]->set_tpl('{$numberInTheList}', $inTheList);
                    $glb["templates"]->set_tpl('{$comId}', $value['com_id']);
                    $glb["templates"]->set_tpl('{$comUrl}',
                        "/product/{$value['com_id']}/{$alias}.html");
                    $glb["templates"]->set_tpl('{$Imgsrc}', $src);
                    $glb["templates"]->set_tpl('{$catName}', $value['cat_name']);
                    $glb["templates"]->set_tpl('{$comName}',
                        htmlspecialchars($value["com_name"]));
                    $glb["templates"]->set_tpl('{$comCod}',
                        htmlspecialchars($value["cod"]));
                    $glb["templates"]->set_tpl('{$comColor}', $comColor);
                    $glb["templates"]->set_tpl('{$comSize}', $comSize);
                    $glb["templates"]->set_tpl('{$curSelect}',
                        $glb["cur"][$glb["cur_id"]]);
                    $glb["templates"]->set_tpl('{$comPrice}', $priceBasket);
                    $glb["templates"]->set_tpl('{$comSumPrice}', $sum);
                    $glb["templates"]->set_tpl('{$comCount}', $result[0][$key]);

                    // pl gift
                    $glb["templates"]->set_tpl('{$commoditySumOldPrice}',
                        $sumOld);
                    $glb["templates"]->set_tpl('{$SumOldPriceHidden}',
                        $sumOldClass);
                    $glb["templates"]->set_tpl('{$SumOldPriceHiddenMail}',
                        $sumOldClassMail);
                    // end

                    $basketLines.=$glb["templates"]->get_tpl('mail.basketFastOrder.line',
                        "../../../");
                    $linesFinalOrder .= $glb["templates"]->get_tpl('basket.finalPageOrder.lines',
                        "../../../");

                    //Запись товаров по отдельности
                    $stmt = $mysqli->stmt_init();
                    if (!($stmt = $mysqli->prepare("
                        INSERT INTO `shop_orders_coms`
			SET
			`offer_id`=?,
                        `com_id`=?,
			`cur_id`=?,
			`count`=?,
			`com`=?,
			`price`=?,
			`com_color`=?
			"))) {
                        die('Insert shop_orders_coms Error ('.$mysqli->errno.') '.$mysqli->error);
                    }
                    if (!$stmt->bind_param("iiiisis", $orderId,
                            $value['com_id'], $glb["cur_id"], $result[0][$key],
                            $comSize, $priceBasket, $comColor)) {
                        die('Insert shop_orders_coms Error ('.$stmt->errno.') '.$stmt->error);
                    }
                    if (!$stmt->execute() || !$stmt->close()) {
                        die('Insert shop_orders_coms Error ('.$stmt->errno.') '.$stmt->error);
                    }
                }

                //подготовка шаблона товаров для email
                $glb["templates"]->set_tpl('{$linesFinalOrder}',
                    $linesFinalOrder);
                $glb["templates"]->set_tpl('{$basketFastOrderMailLines}',
                    $basketLines);
                $glb["templates"]->set_tpl('{$orderCode}', $cod);
                $glb["templates"]->set_tpl('{$userName}', $userName);
                $glb["templates"]->set_tpl('{$userEmail}', $userEmail);
                $glb["templates"]->set_tpl('{$userTel}', $userTel);
                $glb["templates"]->set_tpl('{$userCity}', $userCity);
                $glb["templates"]->set_tpl('{$userAddress}',
                    $userAdressOrWarehouse);
                $glb["templates"]->set_tpl('{$deliveryMethodName}',
                    $resultDevelory['delivery'][$deliveryMethodId]);
                $glb["templates"]->set_tpl('{$userComments}', $userComments);
                $glb["templates"]->set_tpl('{$orderDate}', $orderDate);
                $glb["templates"]->set_tpl('{$comTotalCount}', $totalQuantity);
                $glb["templates"]->set_tpl('{$comSumPrice}', $sumPrice);
                $glb["templates"]->set_tpl('{$deliveryPrice}', $deliveryPrice);
                //$glb["templates"]->set_tpl('{$commisionPrice}', $commision);
                $glb["templates"]->set_tpl('{$totalSumm}', $totalSum);
                $glb["templates"]->set_tpl('{$userIp}',
                    filter_input(INPUT_SERVER, 'REMOTE_ADDR',
                        FILTER_SANITIZE_STRING));
                //$glb["templates"]->set_tpl('{$userCommision}', $commision);
                // discountGift
                $discGift->getTemplateForOrderSend($totalQuantity);

                $finalPageOrder = $glb["templates"]->get_tpl('basket.finalPageOrder',
                    "../../../");
                $glb["templates"]->set_tpl('{$hostName}',
                    filter_input(INPUT_SERVER, 'HTTP_HOST',
                        FILTER_SANITIZE_STRING));

                //отправка email
                //send email user
                $glb["templates"]->set_tpl('{$mailContent}',
                    $glb["templates"]->get_tpl('mail.basketFastOrder.userMail',
                        "../../../"));
                $mailToUser  = $glb["templates"]->get_tpl('mail.main',
                    "../../../");
                $sendUser    = Mail::send($userEmail,
                        "Ваш заказ на сайте {$glb["sys_mail"]}", $mailToUser,
                        'sales@makewear.com.ua');
                $error       = ($sendUser) ? 0 : 1;
                //send email manager
                $glb["templates"]->set_tpl('{$mailContent}',
                    $glb["templates"]->get_tpl('mail.basketFastOrder.adminMail',
                        "../../../"));
                $mailToAdmin = $glb["templates"]->get_tpl('mail.main',
                    "../../../");
                $sendManager = Mail::send($userEmail,
                        "Новый заказ", $mailToAdmin, 'sales@makewear.com.ua');
                $error       = ($sendManager) ? 0 : 1;

                //удаление товаров с корзины
                if ($error == 0) {
                    //$basketSaveDb->deleteData();
                }

                //discountGift
                if ($error == 0 && $discountGift != 0) {
                    $userData->setValue('discount_gift', 0);
                }
            } else {
                $error = 1;
            }
        } else {
            $error = 1;
        }
    } else {
        $error = 1;
    }

    echo json_encode(array(
        'error' => $error,
        "finalPageOrder" => $finalPageOrder
    ));
}

/**
 * Функция get пустую корзину
 * @global type $glb
 */
function getEmptyBasket()
{
    global $glb;
    $panel  = $glb['templates']->get_tpl('basket.full.empty', '../../../');
    $result = json_encode(array('panel' => $panel));
    echo $result;
}

/**
 * Функция get текущий курс
 * @global type $glb
 */
function getCurrency()
{
    global $glb;
    $error = isset($glb) ? 0 : 1;
    echo json_encode(array(
        'error' => $error,
        "currency" => $glb["cur_val"],
        'baxCurrency' => $glb['cur_val_bax']
    ));
}

//==============================================================================
//                          Вспомогательные функции
//==============================================================================
/**
 * Функция выборки данных по доставкам
 * @param type $domId
 * @param type $mysqli
 * @return type array
 */
function getDeliveryMetFromDb($domId, $mysqli)
{
    //выбираем данные
    if (!($stmt = $mysqli->prepare("
                SELECT id, name, price, free, `order` FROM `shop_delivery`
		WHERE `dom_id` = ?
		ORDER BY `order`;"))) {
        die('Error выборки getDeliveryMetFromDb('.$mysqli->errno.') '.$mysqli->error);
    } else {
        $stmt->bind_param("i", $domId);
        $stmt->execute();
        $stmt->bind_result($id, $name, $price, $free, $order);

        // Выбрать значения
        while ($stmt->fetch()) {
            if (/* $price != 0 && $free != 0 */ $id == 1) {
                //$delivery[$id] = $name.' - '.$price.' грн. (бесплатно от '.$free.' грн.)';
                //$sumPrice[$id] = $free;
            } else {
                $delivery[$id] = $name;
                $sumPrice[$id] = $free;
            }
        }
        $stmt->close();
    }
    return $result = array(
        "delivery" => $delivery,
        "deliveryPrice" => $sumPrice
    );
}

/**
 * Функция выборки данных по товару для мини корзины
 * @param type $mysqli
 * @return type array
 */
function getComPtopFromDbMiniBasket($mysqli, $basketItems)
{
    $totalCount = array();
    $comArr     = array();
    foreach ($basketItems as $key => $value) {
        $comPropArray = explode(";", $key);
        $comID        = $comPropArray[0];
        if ($comID != 'undefined') {
            $comArr[$comID]["size"]  = $comPropArray[1];
            $comArr[$comID]["color"] = $comPropArray[2];
            $comArr[$comID]["count"] = $value;
            $ids .= $ids == "" ? "{$comID}" : ",{$comID}";
            $totalCount[$comID] += $value;
        }
    }
    $totalQuantity = array_sum($basketItems);

    //выбираем данные
    $sql  = "
		SELECT commodity_ID, brand_id, com_name, cat_name, commodity_price, commodity_price2, cod, size_count, sc.alias
		FROM shop_commodity sc
		INNER JOIN shop_categories cat
		ON cat.categories_of_commodities_ID = sc.brand_id
		WHERE commodity_ID IN ({$ids});";
    if (!($stmt = $mysqli->prepare($sql))) {
        die('Error выборки getComPtopFromDbMiniBasket('.$mysqli->errno.') '.$mysqli->error);
    } else {
        $stmt->execute();
        $stmt->bind_result($comId, $brandId, $comName, $catName, $comPrice,
            $comPrice2, $code, $sizeCount, $alias);

        // Выбрать значения
        while ($stmt->fetch()) {
            $updateData[$comId]['com_name']         = $comName;
            $updateData[$comId]['commodity_price']  = $comPrice;
            $updateData[$comId]['commodity_price2'] = $comPrice2;
            $updateData[$comId]['cat_name']         = $catName;
            $updateData[$comId]['size_count']       = $sizeCount;
            $updateData[$comId]['cod']              = $code;
            $updateData[$comId]['alias']            = $alias;
            $updateData[$comId]['brand_id']         = $brandId;
        }
        $stmt->close();
    }
    return $result = array($updateData, $totalCount, $comArr, $totalQuantity);
}

/**
 * Функция выборки данных по товару
 * @param type $mysqli
 * @return type array
 */
function getCommoditiesPtopertiesFromDb($mysqli, $basketItems)
{
    global $glb;
    //Со скольки едениц считать оптовые цены
    $countPrice2    = 4;
    $totalQuantity  = array_sum($basketItems);
    $priceBasketArr = array();
    $countItemArr   = array();

    // action discount 40%
    if (EXIST_ACTION_BRANDS != '') {
        $actionBrandsArr = explode(',', EXIST_ACTION_BRANDS);
    }
    // end

    foreach ($basketItems as $key => $value) {
        if ($key != 'undefined') {
            $wovels = array(';', "(", ")", "undefined", "/", ' ', '-', '.', '+');
            $countItemArr[str_replace($wovels, "", $key)] += $value;
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
            if ($totalQuantityPlGift > $countPrice2 && $price != $pricePlGift2 && $pricePlGift2
                != 0 && $row['brand_id'] != 1 && $row['brand_id'] != 16 && $row['brand_id']
                != 49) {
                $priceBasketPlGift = $pricePlGift2;
            }

            $sumPlGift = ceil($priceBasketPlGift * $value);
            $totalSumGift += $sumPlGift;
            //var_dump($totalSumGift);
        }
    }
    $giftCount = 0;
    //end

    $sql = "
            SELECT commodity_ID, brand_id, com_name, cat_name, commodity_price, commodity_price2, cod, size_count, sc.alias, `com_fulldesc`, category_id
            FROM shop_commodity sc
            INNER JOIN shop_categories cat
            ON cat.categories_of_commodities_ID = sc.brand_id
            WHERE commodity_ID=?";

    if (!($stmt = $mysqli->prepare($sql))) {
        die('Error выборки getCommoditiesPtopertiesFromDb('.$mysqli->errno.') '.$mysqli->error);
    } else {
        ksort($basketItems);
        foreach ($basketItems as $key => $value) {
            $comPropArray = explode(";", $key);
            $comID        = $comPropArray[0];
            $stmt->bind_param("s", $comID);
            $stmt->execute();
            $stmt->bind_result($comId, $brandId, $comName, $catName, $price,
                $price2, $code, $sizeCount, $alias, $comFullDsc, $catId);
            while ($stmt->fetch()) {
                $updateData[$key]['com_id']       = $comId;
                $updateData[$key]['com_name']     = $comName;
                $updateData[$key]['cat_name']     = $catName;
                $updateData[$key]['size_count']   = $sizeCount;
                $updateData[$key]['cod']          = $code;
                $updateData[$key]['alias']        = $alias;
                $updateData[$key]['com_fulldesc'] = $comFullDsc;
            }
            $wovels              = array(";", "(", ")", "undefined", "/", ' ', '-',
                '.', '+');
            $id                  = str_replace($wovels, "", $key);
            $priceBasketArr[$id] = $price;

            //opt price
            // action discount 40%
            if (($totalQuantity > $countPrice2 && $price != $price2 && $price2 != 0
                && $brandId != 1 && $brandId != 16 && $brandId != 49) || (isset($actionBrandsArr)
                && in_array($brandId, $actionBrandsArr))) {
                $priceBasketArr[$id] = $price2;
            }

            // pl gift
            if (($brandId != 16 && $brandId != 49 && $price2 >= 1 && $price2 <= 200
                && $catId == 8) && $giftCount == 0 && $totalSumGift >= 1000 * $glb["cur_val"]) {
                $giftCount++;
            }
            // end
        }
    }
    $stmt->close();
    return $result = array($countItemArr, $priceBasketArr, $updateData, $giftCount);
}

/**
 * Функция выборки данных по user
 * @param type $email
 * @param type $mysqli
 * @return type array
 */
function getUserPropByEmail($email, $mysqli)
{
    $query    = "SELECT * FROM `users`
	WHERE `user_email`='{$email}'";
    if ($response = $mysqli->query($query)) {
        $row = $response->fetch_assoc();
        if ($row) {
            $userId       = $row["user_id"];
            $userDiscount = $row["user_discount"];
        } else {
            $userId       = 1; //user_auto_reg($_POST["basket_user_name"], $_POST["basket_user_tel"], $email, $_POST["offer_city"]);
            $userDiscount = 0;
        }
    }
    return $result = array(
        "userId" => $userId,
        "userDiscount" => $userDiscount
    );
}

/**
 * get color for basket from description com or filter-color
 * @global type $glb
 * @param type $comID
 * @return string commodity color
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
//===============================конец==========================================
