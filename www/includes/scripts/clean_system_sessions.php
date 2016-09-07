<?php

namespace Modules;

require_once('../../vendor/autoload.php');

$mysqli = MySQLi::getInstance()->getConnect();

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

if ($action === 'delete_old') {

    //delete session which are older then 40 days.
    $result = $mysqli->query(<<<QUERY3
    DELETE FROM system_sessions
    WHERE date_touched <  (select DATE_ADD(UTC_TIMESTAMP(), INTERVAL -40 DAY))
QUERY3
    );
    echo 'Delete, session which are older then 40 days - ' .  $result;

} elseif ($action === 'delete_spum') {

    $currentSpumIps = array();

    //gets all current spum ip.
    $result = $mysqli->query('SELECT ip FROM system_spum_ip');
    while ($row = $result->fetch_object()) {
        $currentSpumIps[] = $row->ip;
    }

    //gets all ips which have more than 50 sessions.
    $result = $mysqli->query(<<<QUERY
    SELECT  count(*) quantity, user_ip FROM system_sessions
    WHERE user_ip NOT IN ('91.90.23.126', '127.0.0.1')
    GROUP BY user_ip
    HAVING quantity > 50
QUERY
    );

    while ($row = $result->fetch_object()) {
        $ip = $row->user_ip;

        //compare and if new ip absent in system_spum_ip table adds it there.
        if (!in_array($ip, $currentSpumIps)) {

            $resultSpum = $mysqli->query(<<<QUERY1
            INSERT INTO system_spum_ip (ip)
            VALUES ('{$ip}')
QUERY1
            );
            echo 'Add to spum - ' . $ip . ' ' . $resultSpum . '<br>';
        }

        //delete ips which have more than 50 sessions.
        $resultDel = $mysqli->query(<<<QUERY2
        DELETE from system_sessions
        WHERE user_ip = '{$ip}'
QUERY2
        );
        echo 'Delete, which have more than 50 sessions' . $ip . ' ' . $resultDel . '<br>';

        flush();
        ob_flush();
    }
}
