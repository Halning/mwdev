<?php
/**
 * Created by PhpStorm.
 * User: webler
 * Date: 23.05.16
 * Time: 13:26
 */

namespace Modules;


class Response
{
    public static function redirect($url, $code = '200')
    {
        switch ($code) {
            case '301':
                header("HTTP/1.1 301 Moved Permanently");
                break;
        }

        exit(header("Location: http://{$_SERVER['HTTP_HOST']}$url"));
    }
}
