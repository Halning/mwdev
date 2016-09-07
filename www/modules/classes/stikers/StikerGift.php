<?php

namespace Modules\Stikers;

/**
 * Class StikerGift.
 * @package Modules\Stikers
 */
class StikerGift extends Stikers
{
    const STRING_GIFT_BASKET = 'Подарок<sup>*</sup>';

    /**
     * category_id from db table 'shop_commondity'
     *
     * @var string
     */
    protected $categoryId;

    /**
     * brand_id from db table 'shop_commondity'
     * @var string
     */
    protected $brandId;

    /**
     * com opt price from db table 'shop_commodity'
     * @var type
     */
    protected $commodityPrice2;

    /**
     * set var to templates
     */
    protected function setVarToTemplates()
    {
        $this->templates->set_tpl($this->varName, $this->getGiftExist());
    }

    /**
     * set var to templates for basket
     */
    protected function setVarToTemplatesBasket()
    {
        $this->templates->set_tpl($this->varName,
            ($this->getGiftExist() == 1) ? self::STRING_GIFT_BASKET : '');
    }

    /**
     * check show stiker gift or not
     *
     * @return bollean
     */
    private function getGiftExist()
    {
        if ($this->brandId == 16 || $this->brandId == 49 || $this->commodityPrice2
            < 1 || $this->commodityPrice2 > 200 || $this->categoryId != 8) {
            return 0;
        }
        return 1;
    }
}