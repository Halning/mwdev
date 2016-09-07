<?php

namespace Modules\Basket;

use Modules\User;
use Modules\MySQLi;
use Modules\Basket\BasketDb;
use Modules\Basket\DiscountGift;
use Modules\Stikers\Stikers;
use Modules\Basket\PlatieGift;

/**
 * Class BasketPjaxFull.
 * @package Modules\Basket
 */
class BasketPjaxFull
{
    /**
     * str_replase from id itemBasket
     * @var array
     */
    private $wovels = array(";", "(", ")", "undefined", "/", ' ', '-', '.',
        '+');

    /**
     *  db connect
     * @var object
     */
    private $db;

    /**
     * temolates object from glb
     * @var object
     */
    private $templates;

    /**
     * index of cur val
     * @var string
     */
    private $curVal;

    /**
     * array with currency symbols
     * @var array
     */
    private $curr;

    /**
     * id of currency
     * @var string
     */
    private $curId;

    /**
     * count of item for opt price2
     * @var int
     */
    private $countPrice2 = 4;

    /**
     * from define action brand string
     * @var array
     */
    private $actionBrandsArr;

    /**
     * class users for user data
     * @var object
     */
    private $userData;

    /**
     * class Stikers
     * @var type
     */
    private $stikeObj;

    /**
     * for playie gift in basket
     * @var object
     */
    private $plFiftObj;

    /**
     * for saving items in DB for basket
     * @var object
     */
    private $basketSaveDb;

    /**
     * basket items in array input from js
     * @var array
     */
    public $basketItems;

    /**
     * prop items array explode from basketItems array
     * @var array
     */
    private $comPropArray;

    /**
     * prop items array from comPropArray sort by item id
     * @var array
     */
    private $comArr = array();

    /**
     * count of every item from basketItems array
     * @var array
     */
    private $totalCount = array();

    /**
     * templates lines to template 'basket full'
     * @var string
     */
    private $fullLines = '';

    /**
     * GTM
     * @var string
     */
    private $idsForGTM = '';

    /**
     * com prop array fro DB init in function selectComPropFromDb()
     * @var array
     */
    private $comDataDb = array();

    /**
     * baskets amount
     * @var string
     */
    private $totalSum;

    /**
     * all number of goods basket
     * @var int
     */
    private $totalQuantity;

    public function __construct($templates, $curVal, $curr, $curId)
    {
        $this->db        = MySQLi::getInstance()->getConnect();
        $this->templates = $templates;
        $this->curVal    = $curVal;
        $this->curr      = $curr;
        $this->curId     = $curId;

        // init classes for basket
        $this->stikeObj     = new Stikers($this->templates, $this->curVal);
        $this->basketSaveDb = new BasketDb(session_id());
        $this->basketItems  = $this->basketSaveDb->getBasketData();

        // difine var to actions diskount
        if (EXIST_ACTION_BRANDS != '') {
            $this->actionBrandsArr = explode(',', EXIST_ACTION_BRANDS);
        }

        // user data from DB
        if (is_numeric($_SESSION['user_id'])) {
            $this->userData = new User($_SESSION['user_id'], $this->db);
            $this->userData->getData();
        }
    }

    /**
     * if basket is empty show 'basket.full.empty' template
     *
     * @return type
     */
    public function showEmptyBasket()
    {
        $this->templates->set_tpl('{$info}',
            $this->templates->get_tpl('main.info'));
        $panel = $this->templates->get_tpl('basket.full.empty');
        return $panel;
    }

    /**
     * save and sort prop basket items and count items from basketItems array (color, size, count)
     */
    public function getPropItems()
    {
        foreach ($this->basketItems as $key => $value) {
            $this->comPropArray = explode(";", $key);
            $comId              = $this->comPropArray[0];
            $comIdSB            = str_replace($this->wovels, "", $key);

            if ($comId != 'undefined') {
                $this->ids .= $this->ids == "" ? "{$comId}" : ",{$comId}";
                $this->comArr[$comIdSB]["size"]  = str_replace("undefined", "",
                    $this->comPropArray[1]);
                $this->comArr[$comIdSB]["color"] = str_replace("undefined", "",
                    $this->comPropArray[2]);
                $this->totalCount[$comIdSB] += $value;
            }
        }
        $this->totalQuantity = array_sum($this->basketItems);
    }

    /**
     * select from db table 'shop_commodity' all items
     */
    public function selectComPropFromDb()
    {
        $this->getPropItems();

        $sql  = "
		SELECT commodity_ID, brand_id, com_name, cat_name, commodity_price, commodity_price2, cod, size_count, sc.alias, `com_fulldesc`, category_id
		FROM shop_commodity sc
		INNER JOIN shop_categories cat
		ON cat.categories_of_commodities_ID = sc.brand_id
		WHERE commodity_ID IN ({$this->ids});";
        if (!($stmt = $this->db->prepare($sql))) {
            die('Error выборки getComPtopFromFullBasket('.$this->db->errno.') '.$this->db->error);
        } else {
            $stmt->execute();
            $stmt->bind_result($comId, $brandId, $comName, $catName, $comPrice,
                $comPrice2, $code, $sizeCount, $alias, $comFulldesc, $categoryId);

            // Выбрать значения
            while ($stmt->fetch()) {
                $this->comDataDb[$comId]['brand_id']         = $brandId;
                $this->comDataDb[$comId]['com_name']         = $comName;
                $this->comDataDb[$comId]['commodity_price']  = $comPrice;
                $this->comDataDb[$comId]['commodity_price2'] = $comPrice2;
                $this->comDataDb[$comId]['cat_name']         = $catName;
                $this->comDataDb[$comId]['size_count']       = $sizeCount;
                $this->comDataDb[$comId]['cod']              = $code;
                $this->comDataDb[$comId]['alias']            = $alias;
                $this->comDataDb[$comId]['com_fulldesc']     = $comFulldesc;
                $this->comDataDb[$comId]['category_id']      = $categoryId;
            }
            $stmt->close();
        }
    }

    /**
     * function get vars and set them in template 'basket.full.lines'
     */
    private function getVarForTemplateLines()
    {
        // for platie git
        $giftCount  = 0;
        $giftCount2 = 0;

        $this->plFiftObj = new PlatieGift($this->basketItems, $this->curVal,
            $this->totalCount);
        $this->plFiftObj->getMainGift();

        foreach ($this->basketItems as $key => $value) {
            $this->comPropArray = explode(";", $key);
            $comId              = $this->comPropArray[0];
            $comIdSB            = str_replace($this->wovels, "", $key);
            $this->idsForGTM .= "'$comId', ";

            $alias = $this->comDataDb[$comId]['alias'];
            $src   = PHOTO_DOMAIN."{$comId}/s_title.jpg";

            $comColor = $this->comArr[$comIdSB]['color'] == "" ? $this->getColorToBasket(
                    $this->comDataDb[$comId]['com_fulldesc']) : $this->comArr[$comIdSB]['color'];
            $comSize  = $this->comArr[$comIdSB]['size'];

            $this->totalCount[$comIdSB] = $this->checkSizeCount($this->totalCount[$comIdSB],
                $comSize, $this->comArr[$comIdSB]['color'],
                $this->comDataDb[$comId]['size_count'], $comId,
                $this->basketItems, $this->basketSaveDb);

            $price       = ceil($this->comDataDb[$comId]['commodity_price'] * $this->curVal);
            $price2      = ceil($this->comDataDb[$comId]['commodity_price2'] * $this->curVal);
            $priceBasket = $price;

            // action discount 40%
            if (($this->totalQuantity > $this->countPrice2 && $price != $price2 && $price2
                != 0 && $this->comDataDb[$comId]['brand_id'] != 1 && $this->comDataDb[$comId]['brand_id']
                != 16 && $this->comDataDb[$comId]['brand_id'] != 49) || (isset($this->actionBrandsArr)
                && in_array($this->comDataDb[$comId]['brand_id'],
                    $this->actionBrandsArr))) {
                $priceBasket = $price2;
            }

            // platie gift
            $existPlGiftArr = $this->plFiftObj->mainPlGift($giftCount2,
                $giftCount, $comId);

            if ($existPlGiftArr[0] == TRUE) {
                $giftCount2++;
                $idHtmlPlGift = $comIdSB;
            }

            if ($existPlGiftArr[1] == TRUE) {
                $giftCount++;
                $sumOld      = ceil($priceBasket * $this->totalCount[$comIdSB]);
                $sumOldClass = '';
                $sum         = ceil($priceBasket * ($this->totalCount[$comIdSB] - $giftCount));
            } else {
                $sumOld      = 0;
                $sumOldClass = 'class="hidden"';
                $sum         = ceil($priceBasket * $this->totalCount[$comIdSB]);
            }
            // end platie gift

            $this->totalSum += $sum;

            // set var to templates
            $this->templates->set_tpl('{$commodityId}', $comIdSB);
            $this->templates->set_tpl('{$commodityUrl}',
                "/product/{$comId}/{$alias}.html");
            $this->templates->set_tpl('{$Imgsrc}', $src);
            $this->templates->set_tpl('{$brandName}',
                $this->comDataDb[$comId]['cat_name']);
            $this->templates->set_tpl('{$commodityName}',
                htmlspecialchars($this->comDataDb[$comId]["com_name"]));
            $this->templates->set_tpl('{$commodityCod}',
                htmlspecialchars($this->comDataDb[$comId]["cod"]));
            $this->templates->set_tpl('{$commodityColor}', $comColor);
            $this->templates->set_tpl('{$commoditySize}', $comSize);
            $this->templates->set_tpl('{$commodityCount}',
                $this->totalCount[$comIdSB]);
            $this->templates->set_tpl('{$curSelect}', $this->curr[$this->curId]);
            $this->templates->set_tpl('{$commodityPrice}', $priceBasket);
            $this->templates->set_tpl('{$commoditySumPrice}', $sum);

            // pl gift
            $this->templates->set_tpl('{$commoditySumOldPrice}', $sumOld);
            $this->templates->set_tpl('{$SumOldPriceHidden}', $sumOldClass);
            $this->templates->set_tpl('{$giftToken}', $idHtmlPlGift);

            // stiker gift
            $this->stikeObj->getStikerGift('{$stringGift}',
                $this->comDataDb[$comId]['commodity_price2'],
                $this->comDataDb[$comId]['brand_id'],
                $this->comDataDb[$comId]['category_id']);
            // end

            $this->fullLines .= $this->templates->get_tpl('basket.full.lines');
        }
    }

    /**
     * main function get all template 'basket.full' return all basket
     * @return object
     */
    public function getMainTemplate()
    {
        $this->selectComPropFromDb();

        $this->getVarForTemplateLines();

        // select country in basket form
        if ($this->userData->country == 2) {
            $this->templates->set_tpl('{$countryChecked2}', 'checked');
        } else {
            $this->templates->set_tpl('{$countryChecked1}', 'checked');
        }

        // set var to templates
        $this->templates->set_tpl('{$basketFullLines}', $this->fullLines);
        $this->templates->set_tpl('{$commodityTotalCount}', $this->totalQuantity);
        $this->templates->set_tpl('{$totalSumm}', $this->totalSum);
        $this->templates->set_tpl('{$basketUserName}',
            (isset($_SESSION["basket_user_realname"]) && $_SESSION["basket_user_realname"]
            != "") ? $_SESSION["basket_user_realname"] : (isset($this->userData->name)
                        ? $this->userData->name." ".$this->userData->realName : ""));
        $this->templates->set_tpl('{$basketUserTel}',
            (isset($_SESSION["basket_user_phone"]) && $_SESSION["basket_user_phone"]
            != "") ? $_SESSION["basket_user_phone"] : $this->userData->phone);
        $this->templates->set_tpl('{$basketUserEmail}',
            (isset($_SESSION["basket_user_email"]) && $_SESSION["basket_user_email"]
            != "") ? $_SESSION["basket_user_email"] : $this->userData->email);
        $this->templates->set_tpl('{$basketUserCity}',
            (isset($_SESSION["basket_user_city"]) && $_SESSION["basket_user_city"]
            != "") ? $_SESSION["basket_user_city"] : $this->userData->city);
        $this->templates->set_tpl('{$basketUserDelivery}',
            $this->userData->delivery);
        $this->templates->set_tpl('{$basketUserWarehouse}',
            (isset($this->userData->warehouse)) ? $this->userData->warehouse : '');
        $this->templates->set_tpl('{$basketUserAddress}',
            (isset($this->userData->address)) ? $this->userData->address : '');
        $this->templates->set_tpl('{$basketUserComments}',
            $_SESSION["user_comments"]);

        // DiscountGift150
        $discGift = new DiscountGift($this->templates, $this->curVal,
            $this->userData->discountGift);
        $discGift->getTemplateForBasketFull();
        // end DiscountGift150

        $this->templates->set_tpl('{$idsForGTM}',
            substr($this->idsForGTM, 0, -2));
        $this->templates->set_tpl('{$info}',
            $this->templates->get_tpl('main.info'));
        return $panel = $this->templates->get_tpl('basket.full');
    }

    /**
     * get color for basket from description com or filter-color
     *
     * @param string $comFulldesc
     * @return string
     */
    private function getColorToBasket($comFulldesc)
    {
        if (isset($comFulldesc)) {
            $comDescString = str_replace("&nbsp;", '',
                htmlspecialchars_decode($comFulldesc));
        }

        $needle    = "Цвет:";
        $findColor = strstr($comDescString, $needle);

        if ($findColor !== false) {
            $wovels   = array('Цвет:</span>', 'Цвет:');
            $comColor = str_replace($wovels, "",
                strstr($findColor, "</p>", true));
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
    private function checkSizeCount($totalCountInt, $comSize, $comColor,
                                    $sizeCount, $idCom, $basketItems,
                                    $basketSaveDb)
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
}