<?php

/**
 * @param $parserId int, parser id.
 * @param $begin object of DateTime, time when verify started.
 * @param $end object of DateTime, time when verify completed.
 * @param $countChecked int - quantity of commodities checked.
 * @param $countUpdated int - quantity of commodities updated(changed).
 * @param $countHide int - quantity of commodities hided.
 * @param $countImported - quantity of commodities imported.
 * @return string summery HTML.
 */
function getSummery(
$parserId, $begin, $end, $countChecked, $countUpdated, $countHide,
$countFoundUrl, $countNewUrl, $countImported
)
{
    $timeBeginChecking = $begin->format('H:i:s'); //врема старта проверки.
    $timeEndChecking   = $end->format('H:i:s'); //врема завершения проверки.
    $timeDuration      = " {$end->diff($begin)->h}час : {$end->diff($begin)->i}хв : {$end->diff($begin)->s}сек"; //продолжительность проверки.
    return "
<div class='summery'>
	<table>
		<tr>
			<td>ПРОВЕРЕНО:</td>
			<td><span id='par_check{$parserId}' > {$countChecked} </span></td>
			<td></td>
			<td>ОБНАРУЖЕНО:</td>
			<td><span id='par_obnar{$parserId}'> {$countFoundUrl} </span></td>
		</tr>
		<tr>
			<td>ОБНОВЛЕНО:</td>
			<td><span id='par_update{$parserId}'> {$countUpdated} </span></td>
			<td></td>
			<td>НОВЫЕ URL:</td>
			<td><span id='par_new_url{$parserId}'> {$countNewUrl} </span></td>
		</tr>
		<tr>
    		<td>СКРЫТО:</td>
			<td><span id='par_hide{$parserId}'> {$countHide} </span></td>
			<td></td>
			<td>ИМПОРТИРОВАНО:</td>
			<td><span id='par_import{$parserId}' > {$countImported} </span></td>
		</tr>
    </table>
</div>
<div class='summery'>
	<table>
		<tr>
			<td>Начало</td>
			<td><span id='par_start_time{$parserId}'>{$timeBeginChecking}</span></td>
		</tr>
		<tr>
			<td>Окончание</td>
			<td></span><span id='par_end_time{$parserId}'>{$timeEndChecking}</span></td>
		</tr><tr>
			<td>Длительность</td>
			<td><span id='par_time_darution{$parserId}'>{$timeDuration}</span></td>
		</tr>
	</table>
</div>
";
}
