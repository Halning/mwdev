<?php

namespace Modules\Basket;

use Modules\MySQLi;

/**
 * Class DiscountGift.
 * @package Modules\Basket
 */
class DiscountGift
{
    /**
     * count opt order
     */
    const COUNT_PRICE_2 = 4;

    /**
     *  db connect
     * @var object
     */
    private $db;

    /**
     * templates
     * @var object
     */
    private $template;

    /**
     * index of currency selected
     * @var string
     */
    private $curVal;

    /**
     *  discount gift from db table 'users'
     * @var string
     */
    private $discountGift;

    /**
     *
     * @param object $template
     * @param string $curVal
     * @param string $discountGift
     */
    public function __construct($template, $curVal, $discountGift)
    {
        $this->db           = MySQLi::getInstance()->getConnect();
        $this->template     = $template;
        $this->curVal       = $curVal;
        $this->discountGift = $discountGift;
    }

    /**
     * set var for 'basket.full.tpl'
     */
    public function getTemplateForBasketFull()
    {
        $this->template->set_tpl('{$discountGift}',
            (isset($this->discountGift)) ? ceil($this->discountGift * $this->curVal)
                    : '0');
        $this->template->set_tpl('{$existAction}',
            (isset($this->discountGift) && $this->discountGift != 0) ? '1' : '0');
    }

    /**
     *  if order is opt return discount gift for sendOrder(ajax_basket.php)
     *
     * @param int $totalQuantity
     * @return int
     */
    public function getDiscountGiftForSendOrder($totalQuantity)
    {
        if ($totalQuantity > self::COUNT_PRICE_2) {
            return (int) ceil($this->discountGift * $this->curVal);
        }

        return 0;
    }

    /**
     * set var for 'basket.finalPageOrder', 'mail.basketFastOrder.userMail', 'mail.basketFastOrder.adminMail' in function orderSend(ajax_basket.php)
     */
    public function getTemplateForOrderSend($totalQuantity)
    {
        $this->template->set_tpl('{$discountGift}', ceil($this->discountGift));
        $this->template->set_tpl('{$hidden}',
            ($totalQuantity > self::COUNT_PRICE_2 && isset($this->discountGift) && $this->discountGift
            != 0) ? '' : 'style="display: none"');
    }
}