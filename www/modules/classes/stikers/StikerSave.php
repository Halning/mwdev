<?php

namespace Modules\Stikers;

/**
 * Class StikerSave.
 * @package Modules\Stikers
 */
class StikerSave extends Stikers
{
    /**
     * commodity rozn price from db table 'shop_commodity'
     * @var type
     */
    protected $commondityPrice;

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
        $this->templates->set_tpl($this->varName, $this->getSavePrcent());
    }

    /**
     * Get precent difference of opt and rozn
     *
     * @return int - present  difference of opt and rozn
     */
    private function getSavePrcent()
    {
        $priceRoz = round($this->commondityPrice * $this->curVal, 0);
        return round((($priceRoz - $this->commodityPrice2) * 100) / $priceRoz, 0);
    }
}