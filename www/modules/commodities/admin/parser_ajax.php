<?php
ini_set("max_execution_time", "99999");
set_time_limit(99999);
error_reporting(E_ALL ^ E_NOTICE);

require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once('../../../templates/admin/admin_parser_interface.php');

$arr = array();
$j   = 0;
$up  = mysql_query("SELECT * FROM parser_interface");
while ($row = mysql_fetch_assoc($up)) {
    $id            = $row['par_id'];
    $countChecked  = $row['check_prog'];
    $countUpdated  = $row['update_add'];
    $countHide     = $row['par_hide']; // количество скрытых товаров.
    $countAdd      = $row['add_new_com']; //кількість імпортованих.
    $countFoundUrl = $row['count_found_url']; //кількість всіх знайдених ссилок.
    $countNewUrl   = $row['count_new_url']; //кількість нових товарів.
    $prog          = $row['update_prog'];
    $up_date       = date("d-m-Y H:i:s", strtotime($row['update_date']));
    $up_date2      = date("d-m-Y H:i:s", strtotime($row['add_date']));
    $addProg       = $row['add_prog'];
    if ($prog < 100) {
        $text = $row['text'];
    } else {
        $begin = new \DateTime($row['start_time']); //object, врема старта проверки.
        $end   = new \DateTime($row['update_date']); //object, врема завершения проверки.
        $text  = getSummery(
            $id, $begin, $end, $countChecked, $countUpdated, $countHide,
            $countFoundUrl, $countNewUrl, $countAdd
        );
    }
    $arr[$j] = array(
        "i" => $id,
        "a" => $countChecked,
        "b" => $countUpdated,
        "p" => $prog,
        "data" => $up_date,
        "a_data" => $up_date2,
        "ap" => $addProg,
        "an" => $countAdd,
        "txt" => $text
    );
    $j++;
}
echo json_encode($arr);
