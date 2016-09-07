<?php

namespace Modules;

//$glb['mysqli'] = MySQLi::getInstance()->getConnect();
Cache::setOnOff($glb['enable_cache']);

if (isset($_GET['admin'])) {
    $query = <<<QUERY
        SELECT
          module_name
        FROM modules
        WHERE module_enabled = '1'
        ORDER BY module_order
QUERY;

    $result = $glb['mysqli']->query($query);
    while ($row    = $result->fetch_object()) {
        require("modules/{$row->module_name}/main.php");
    }
} else {

    //routes
    $url_page['catalog'] = 'getProductBlock';
    $url_page['akcionnie-predlojeniya'] = 'getActionListPage';
    $url_page['prices'] = 'getPricesYML';

    //include modules
    require("modules/commodities/main.php");
    require("modules/content/main.php");
    require("modules/basket_fastOrder/main.php");
}