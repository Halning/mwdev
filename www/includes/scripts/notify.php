<?php

/**
 *Compare old and actual prices and sends inform users about price is change.
 */

namespace Modules;

header('Content-type: text/html; charset=utf-8');
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';

require_once('../../vendor/autoload.php');
require_once('../../settings/conf.php');
require_once('../../settings/functionsNew.php');

global $theme_name;

$theme_name = 'shop1';
$informUsers = new InformUsers();
$informUsers->getCurrentPrices()
    ->getOldPrices()
    ->comparePrices();

if (count($informUsers->emailsToInform) > 0) {
    foreach ($informUsers->emailsToInform as $email => $commodities) {
        $lines = '';

        foreach ($commodities as $commodity) {
            $lines .= $informUsers->generateLines($commodity);
        }

        $message = $informUsers->generateMessage($lines);
        $send = Mail::send($email, $informUsers::SUBJECT, $message);

        if ($send) {
            echo $message.$email.'<hr>';
        } else {
            echo '<h3>ERROR! - '.$email.'</h2><hr>';
        }
    }

    $informUsers->setInformed();
} else {
    echo 'Ціни не помінялись';
}
