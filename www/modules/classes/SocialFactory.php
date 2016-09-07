<?php
/**
 * Created by PhpStorm.
 * Date: 11/19/15
 * Time: 2:18 PM
 */

namespace Modules;


class SocialFactory
{
    public static function createUrl($provider)
    {
        switch ($provider) {
            case 'vk':
                return self::getVkUrl();
            case 'fb':
                return self::getFbUrl();
            case 'ok':
                return self::getOkUrl();
            case 'go':
                return self::getGoUrl();
            default:
                return false;
        }
    }

    private static function getVkUrl()
    {
        $clientId = "5151626";
        $redirectUri = "http://".$_SERVER['HTTP_HOST']."/modules/socialls_network/vk_api.php";
        $url = 'http://oauth.vk.com/authorize';
        $params = array(
            'redirect_uri'  => $redirectUri,
            'client_id'     => $clientId,
            'response_type' => 'code'
        );
        return $url . '?' . urldecode(http_build_query($params));
    }

    private static function getFbUrl()
    {
        $clientId = '1699070286981354';
        $redirectUri = 'http://'.$_SERVER['HTTP_HOST'].'/modules/socialls_network/fb_api.php';
        $url = 'https://www.Facebook.com/dialog/oauth';
        return $url . "?client_id=" . $clientId . "&redirect_uri=" . urlencode($redirectUri) . "&response_type=code";
    }

    private static function getOkUrl()
    {
        $clientId = '1234604800'; // Application ID
        $redirectUri = 'http://'.$_SERVER['HTTP_HOST'].'/modules/socialls_network/ok_api.php'; // Ссылка на приложение
        $url = 'http://www.odnoklassniki.ru/oauth/authorize';
        $params = array(
            'client_id'     => $clientId,
            'response_type' => 'code',
            'redirect_uri'  => $redirectUri
        );
        return $url . '?' . urldecode(http_build_query($params));
    }

    private static function getGoUrl()
    {
        $clientId="1054802775236-hravmmufc7bq8ggsmfpekhmk8sahhm32.apps.googleusercontent.com";
        $redirectUri="http://".$_SERVER['HTTP_HOST']."/modules/socialls_network/go_api.php";
        $url = 'https://accounts.google.com/o/oauth2/auth';
        $params = array(
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'client_id'     => $clientId,
            'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
        );
        return $url . '?' . urldecode(http_build_query($params));
    }
}