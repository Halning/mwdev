<?php
/**
 * Created by PhpStorm.
 * Date: 02.02.16
 * Time: 18:32
 */

namespace Modules;

require_once('../../../vendor/autoload.php');
require_once('../../../settings/conf.php');
require_once('../../../settings/connect.php');
require_once('../../../settings/functions.php');
require_once('../../../settings/main.php');

bd_session_start();

$html = '<p class="desires-empty">Список желаний пуст</p>';
$ajaxResult = 1;
$count = 0;
$comIds = '';

if (isset($_SESSION['liked'])) {

    //удаляє лайкнуті товари з сесії по ід
    $itemsToDelete = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_STRING);
    if ($itemsToDelete) {
        $itemsToDelete = explode(',', $itemsToDelete);

        foreach ($itemsToDelete as $item) {
            $key = array_search($item, $_SESSION['liked']);
            unset($_SESSION['liked'][$key]);
        }
    }

    foreach ($_SESSION['liked'] as $id) {
        if ($id > 500) {
            $comIds .= $id . ', ';
        }
    }

    if ($comIds) {
        $comIds = substr($comIds, 0, -2);
        $mysqli = MySQLi::getInstance()->getConnect();

        $query = <<<QUERY
            SELECT commodity_ID, com_name, cat_name, commodity_price, brand_id
            FROM shop_commodity sc
            INNER JOIN shop_categories cat
              ON sc.brand_id = cat.categories_of_commodities_ID
            WHERE sc.commodity_ID IN ({$comIds})
QUERY;
        $result = $mysqli->query($query);
        $count = $result->num_rows;
        if ($count) {

            $html = '';
            $i = 1;
            global $glb;

            while ($row = $result->fetch_object()) {
                $glb['templates']->set_tpl('{$id}', $row->commodity_ID);
                $glb['templates']->set_tpl('{$catName}', $row->cat_name);
                $glb['templates']->set_tpl('{$comName}', $row->com_name);
                $glb['templates']->set_tpl('{$price}', $row->commodity_price);
                $glb['templates']->set_tpl('{$currency}', $glb["cur"][$glb["cur_id"]]);
                $glb['templates']->set_tpl('{$counter}', $i);
                $glb['templates']->set_tpl('{$photoDomain}', PHOTO_DOMAIN);
                $html .= $glb['templates']->get_tpl('main.wish.line', '../../../');
                $i++;
            }
            
            $html .= $glb['templates']->get_tpl('main.wish', '../../../');
        } else {
            $ajaxResult = 0;
        }
    }
}

echo json_encode(
    array(
        'success' => $ajaxResult,
        'html' => $html,
        'count' => $count
    )
);



