<?php

/**
 * Created by PhpStorm.
 * User: volodini
 * Date: 9/15/15
 * Time: 3:35 PM
 * Повертає 10 найбільш відвідуваних товарів усіма користувачами, та записує результат в таблицю
 * shop_visited.
 * Дані витягуються з сесій.
 */
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");


$comMostViews = array();
$sql = "SELECT sess_data
	FROM system_sessions";
$result = mysql_query($sql);
if(mysql_num_rows($result)>0) {
    while($row = mysql_fetch_assoc($result)) {
        $sessions[] = $row['sess_data'];
    }
    foreach ($sessions as $session) {
        $session = str_replace('&quot;', '"', $session);
        $sessionExplode = explode('|', $session);
        foreach ($sessionExplode as $key => $value) {
            if (strstr($value, 'last_view')) {
                $keyLastView = $key + 1;
                break;
            }
        }
        if ($keyLastView) {
            preg_match_all('/[\d]{3,5}/', $sessionExplode[$keyLastView], $ids);
        }
        $ids = is_array($ids[0]) ? array_unique($ids[0]) : array();
        $sessionsLastView[] = $ids;
        $ids = array();
        $keyLastView = false;
    }
    foreach ($sessionsLastView as $sessionLastView) {
        foreach ($sessionLastView as $sessionLastViewId) {
            if (isset($comMostViews[$sessionLastViewId])) {
                $comMostViews[$sessionLastViewId]++;
            } else {
                $comMostViews[$sessionLastViewId] = 1;
            }
        }
    }
}
arsort($comMostViews);
//    $comMostViews = array_slice($comMostViews, 0, 10);
$id = 1;
foreach ($comMostViews as $com_id => $comMostView) {
    $sql = "UPDATE shop_visited SET com_id= $com_id, visited= $comMostView WHERE  id = $id";
    $result = mysql_query($sql);
    echo "{$com_id} - {$comMostView} - {$result}<br>";
    if ($id >= 10) {
        break;
    } else {
        $id++;
    }
}