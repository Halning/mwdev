<?php
if ($_SESSION['status'] == "admin") {
    require_once "templates/admin/admin_parser_interface.php";
    $table     = "
<br>
<br>
<table width='95%' class='sortable'>
    <tr>
        <th style='width:20px'>№</th>
		<th style='width:100px'>Бренда</th>
		<th style='width:300px'>Обновление</th>
		<th style='width:300px'>Добавление</th>
		<th style='width:300px; max-width: 350px'>Info</th>
	</tr>
";
    $tableRows = '';
    $result    = mysql_query("
SELECT parser.id, name, cat_id, new_links, im_url, count(*) count, update_prog, check_prog, update_add,
update_date, add_prog, add_new_com, add_date, par_hide, start_time, count_new_url, count_found_url, text
FROM parser
INNER JOIN parser_interface pi ON parser.id = pi.par_id
INNER JOIN `shop_commodities-categories` scc ON scc.categoryID = parser.cat_id
INNER JOIN shop_commodity sc ON sc.commodity_ID = scc.commodityID
WHERE sc.commodity_visible = 1
GROUP BY id
");
    while ($row       = mysql_fetch_assoc($result)) {
        $parserId = $row['id'];
        //hide Agio-z
        if ($parserId == 12) {
            continue;
        }
        $brandId                 = $row['cat_id'];
        $countCommodity          = $row['count']; //количество товаров в бренде, которые отображаются на сайте.
        $progressUpdate          = $row['update_prog'].'%'; // прогрес проверки в процентах.
        $countChecked            = $row['check_prog']; //кількість провіренхи.
        $countUpdated            = $row['update_add']; //кількість обновлених.
        $countFoundUrl           = $row['count_found_url']; //кількість всіх знайдених ссилок.
        $countAdd                = $row['add_new_com']; //кількість заімпортованих товарів.
        $countNewUrl             = $row['count_new_url']; //кількість нових товарів.
        $countHide               = $row['par_hide']; // количество скрытых товаров.
        $begin                   = new \DateTime($row['start_time']); //object, врема старта проверки.
        $end                     = new \DateTime($row['update_date']); //object, врема завершения проверки.
        $dateUpdated             = $end->format('d-m-Y H:i:s'); //последняя дата добавления.
        $progressAdd             = round($row['add_prog']).'%'; // прогрес імпорту в процентах.
        $dateAdded               = (new \DateTime($row['add_date']))->format('d-m-Y H:i:s'); // последняя дата добавления.
        $dateForUrlSummeryUpdate = date("d_M", strtotime($dateUpdated)); // дата для ссилки на отчёт по обновлению.
        $dateForUrlSummeryAdd    = date("d_M", strtotime($dateAdded)); // дата для ссилки на отчёт по добавлению.
        $urlSummeryUpdate        = $domain."/parser/reports/{$dateForUrlSummeryUpdate}/catid_{$brandId}_verify.html"; // ссилка на последний отчёт по проверке товаров.
        $urlSummeryAdd           = $domain."/parser/reports/{$dateForUrlSummeryAdd}/catid_{$brandId}_import.html"; // ссилка на последний отчёт по довлению товаров.
        if ($row['im_url']) {
            $addHtml = "
Сканирование<span id='pro2{$parserId}'>...</span> <span class='col_prog' id='add2_prog{$parserId}' >{$progressAdd}</span>
<p/>
<div id='progress'>
	<div class = 'scale' style='width: {$progressAdd}' id='add_prog{$parserId}'></div>
</div>
Добавлено товарoв:<span class='coun_tab' id='new_com{$parserId}'>{$countAdd}</span>
<br/>
<br/>
Последнее добавление: <span id='add_date{$parserId}' class='coun_tab_date'>{$dateAdded}</span>
<br/>
<br/>
<a href='{$urlSummeryAdd}' target='_blank'>Посмотреть подробный отчёт</a>
";
        } else {
            $addHtml = "<span style='color:red'>Товар отсутствует на сайте</span>";
        }
        if ((int) $progressUpdate == 100) {
            $summery = getSummery(
                $parserId, $begin, $end, $countChecked, $countUpdated,
                $countHide, $countFoundUrl, $countNewUrl, $countAdd
            );
        } else {
            $summery = $row['text'];
        }
        $tableRows .= "
<tr>
	<td style='width:20px'>{$parserId}</td>
	<td align='center'><img width='70px' src='/templates/shop/image/categories/{$row['cat_id']}/main.jpg'><br>{$row['name']} <span class='coun_tab' >{$countCommodity}</span></td>
	<td>
		Сканирование<span id='pro{$parserId}'>...</span> <span class='col_prog' id='ani2_prog{$parserId}' >{$progressUpdate}</span>
		<div id='progress'>
			<div class='scale' style='width: {$progressUpdate}' id='ani_prog{$parserId}'></div>
		</div>
		Проверено: <span class='coun_tab' id='check_count{$parserId}'>{$countChecked}</span>
		Обновленo: <span class='coun_tab' id='update_count{$parserId}'>{$countUpdated}</span>
		<br/>
		<br/>
		Последнее обновление: <span id='up_date{$parserId}' class='coun_tab_date'> {$dateUpdated}</span>
		<br/>
		<br/>
		<a href='{$urlSummeryUpdate}' target='_blank'>Посмотреть подробный отчёт</a>
	</td>
	<td>{$addHtml}</td>
	<td style='max-width: 350px' id='text{$parserId}'>{$summery}</td>
</tr>
";
    }
    $table .= $tableRows;
    $table .= "</table>";
    $js     = '<script type="text/javascript" src="/templates/admin/js/parser_inetrface.js "></script>';
    $center = $js.$table;
}