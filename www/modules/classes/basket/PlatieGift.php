<?php

namespace Modules\Basket;

use Modules\MySQLi;

/**
 * Class PlatieGift.
 * @package Modules\Basket
 */
class PlatieGift
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
     * count of item for opt price2
     * @var int
     */
    private $countPrice2 = 4;

    /**
     * index of cur val
     * @var string
     */
    private $curVal;

    /**
     * basket items in array input from js
     * @var array
     */
    private $basketItems;

    /**
     * count of every item from basketItems array
     * @var array
     */
    private $totalCount = array();

    /**
     * all items ides
     * @var string
     */
    private $ids;

    /**
     * com prop array fro DB init in function selectComPropFromDb()
     * @var array
     */
    private $comDataDb = array();

    /**
     * baskets amount for ol gift
     * @var string
     */
    private $totalSumGift;

    /**
     * all number of goods basket
     * @var int
     */
    private $totalQuantity;

    /**
     *
     * @param array $basketItems
     * @param string $curVal
     * @param array $totalCount
     */
    public function __construct($basketItems, $curVal, $totalCount)
    {
        $this->db            = MySQLi::getInstance()->getConnect();
        $this->basketItems   = $basketItems;
        $this->curVal        = $curVal;
        $this->totalQuantity = array_sum($this->basketItems);
        $this->totalCount    = $totalCount;
    }

    /**
     * create com ids for select db selectComPropFromDb()
     */
    private function getAllComIds()
    {
        foreach ($this->basketItems as $key => $value) {
            $comPropArray = explode(";", $key);
            $comId        = $comPropArray[0];

            if ($comId != 'undefined') {
                $this->ids .= $this->ids == "" ? "{$comId}" : ",{$comId}";
                $this->totalCount[$comId] += $value;
            }
        }
    }

    /**
     * select from db table 'shop_commodity' all items
     */
    private function selectComPropFromDb()
    {
        $this->getAllComIds();

        //выбираем данные
        $sql  = "
		SELECT commodity_ID, brand_id, commodity_price, commodity_price2, category_id
		FROM shop_commodity sc
		INNER JOIN shop_categories cat
		ON cat.categories_of_commodities_ID = sc.brand_id
		WHERE commodity_ID IN ({$this->ids});";
        if (!($stmt = $this->db->prepare($sql))) {
            die('Error выборки getComPtopFromPlGift('.$this->db->errno.') '.$this->db->error);
        } else {
            $stmt->execute();
            $stmt->bind_result($comId, $brandId, $comPrice, $comPrice2,
                $categoryId);

            // Выбрать значения
            while ($stmt->fetch()) {
                $this->comDataDb[$comId]['commodity_price']  = $comPrice;
                $this->comDataDb[$comId]['commodity_price2'] = $comPrice2;
                $this->comDataDb[$comId]['brand_id']         = $brandId;
                $this->comDataDb[$comId]['category_id']      = $categoryId;
            }
            $stmt->close();
        }
    }

    /**
     *
     */
    public function getMainGift()
    {
        $this->selectComPropFromDb();

        foreach ($this->basketItems as $key => $value) {
            $this->comPropArray = explode(";", $key);
            $comId              = $this->comPropArray[0];
            $comIdSB            = str_replace($this->wovels, "", $key);
            $pricePlGift        = ceil($this->comDataDb[$comId]['commodity_price']
                * $this->curVal);
            $pricePlGift2       = ceil($this->comDataDb[$comId]['commodity_price2']
                * $this->curVal);
            $priceBasketPlGift  = $pricePlGift;

            //opt price
            if ($this->totalQuantity > $this->countPrice2 && $pricePlGift != $pricePlGift2
                && $pricePlGift2 != 0 && $this->comDataDb[$comId]['brand_id'] != 1
                && $this->comDataDb[$comId]['brand_id'] != 16 && $this->comDataDb[$comId]['brand_id']
                != 49) {
                $priceBasketPlGift = $pricePlGift2;
            }

            $this->totalSumGift += (int) ceil($priceBasketPlGift * $this->totalCount[$comIdSB]);
        }
    }

    /**
     * Main function for pl gift
     *
     * @param int $giftCount2
     * @param int $giftCount
     * @param string $comId
     * @return array
     */
    public function mainPlGift($giftCount2, $giftCount, $comId)
    {
        if (($this->comDataDb[$comId]['brand_id'] != 16 && $this->comDataDb[$comId]['brand_id']
            != 49 && $this->comDataDb[$comId]['commodity_price2'] >= 1 && $this->comDataDb[$comId]['commodity_price2']
            <= 200 && $this->comDataDb[$comId]['category_id'] == 8) && $giftCount2
            == 0) {
            $resultArr[0] = TRUE;
        } else {
            $resultArr[0] = FALSE;
        }

        if ($this->comDataDb[$comId]['brand_id'] != 16 && $this->comDataDb[$comId]['brand_id']
            != 49 && $this->comDataDb[$comId]['commodity_price2'] >= 1 && $this->comDataDb[$comId]['commodity_price2']
            <= 200 && $this->comDataDb[$comId]['category_id'] == 8 && $giftCount
            == 0 && $this->totalSumGift >= 1000 * $this->curVal) {
            $resultArr[1] = TRUE;
        } else {
            $resultArr[1] = FALSE;
        }
        return $resultArr;
    }
}