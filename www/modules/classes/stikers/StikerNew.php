<?php

namespace Modules\Stikers;

use DateTime;

/**
 * Class StikerNew.
 * @package Modules\Stikers
 */
class StikerNew extends Stikers
{
    const TIME_INTERVAL = '+7 day';

    /**
     * commodyti add date from db table 'shop_commodity'
     * @var string
     */
    protected $addDate;

    /**
     * set var to templates
     */
    protected function setVarToTemplates()
    {
        $this->templates->set_tpl($this->varName, $this->checkNewCommondaty());
    }

    /**
     * if com is new we return 1 and javascript don't hide stick-new
     *
     * @return int
     */
    private function checkNewCommondaty()
    {
        $date    = new DateTime($this->addDate);
        $date->modify(self::TIME_INTERVAL);
        $date->format('Y-m-d H:i:s');
        $dateNow = new DateTime();
        $dateNow->format('Y-m-d H:i:s');

        if ($dateNow < $date) {
            return 1;
        }
        return 0;
    }
}