<?php
/**
 * Created by PhpStorm.
 * Date: 24.03.16
 * Time: 17:33
 */

namespace Modules\Prices;

class YMLFactory
{
    private static $instance;

    public static function factory($requestURI)
    {
        switch ($requestURI) {
            case '/all-biz.yml':
                self::$instance = new YMLAllBiz();
                break;
            case 'prom.yml':
                self::$instance = new YMLProm();
                break;
            case '/zakupka.yml';
                self::$instance = new YMLZalupka();
                break;
        }

        return self::$instance;
    }
}
