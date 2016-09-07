<?php

namespace Modules\Stikers;

/**
 * Class Stikers. Pattern Adapter
 * @package Modules\Stikers
 */
class Stikers
{
    /**
     * templates
     * @var object
     */
    protected $templates;

    /**
     *  index of currency selected
     * @var string
     */
    protected $curVal;

    /**
     * name of templates var
     * @var string
     */
    protected $varName;

    /**
     * create class
     *
     * @param object $templates
     * @param string $curVal
     */
    public function __construct($templates, $curVal)
    {
        $this->templates = $templates;
        $this->curVal    = $curVal;
    }

    /**
     * create and show stiker save on com block
     *
     * @param string $varName
     * @param string $comPrice
     * @param string $comPrice2
     */
    public function getStikersSave($varName, $comPrice, $comPrice2)
    {
        $stiker                  = new StikerSave($this->templates,
            $this->curVal);
        $stiker->varName         = $varName;
        $stiker->commondityPrice = $comPrice;
        $stiker->commodityPrice2 = $comPrice2;
        $stiker->setVarToTemplates();
    }

    /**
     * create and show stiker new on com block
     *
     * @param string $varName
     * @param string $addDate
     */
    public function getStikrersNew($varName, $addDate)
    {
        $stiker          = new StikerNew($this->templates, $this->curVal);
        $stiker->varName = $varName;
        $stiker->addDate = $addDate;
        $stiker->setVarToTemplates();
    }

    /**
     * create and show stiker gift on com block
     *
     * @param string $varName
     * @param string $comPrice2
     * @param string $brandId
     * @param string $categoryId
     */
    public function getStikerGift($varName, $comPrice2, $brandId, $categoryId)
    {
        $stiker                  = new StikerGift($this->templates,
            $this->curVal);
        $stiker->varName         = $varName;
        $stiker->categoryId      = $categoryId;
        $stiker->brandId         = $brandId;
        $stiker->commodityPrice2 = $comPrice2;

        if ($varName !== '{$stringGift}') {
            $stiker->setVarToTemplates();
        } else {
            $stiker->setVarToTemplatesBasket();
        }
    }
}