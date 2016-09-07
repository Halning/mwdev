<?
/**
* SimpleCMS
* Функции административной части модуля управления товарами
* @package SimpleCMS
* @author Ower
* @version 0.10.29
* @since engine v.0.10
* @link http://www.ower.com.ua
* @copyright (c) 2010+ by Ower
*/

if ($_SESSION['status']=="admin")
{

	function get_refresh_buttons($name)
	{
		$additions_buttons="
	<td class='button' id='toolbar-new'>
	<div class='toolbar' id='id_torefresh' style='width:100px!important;'> 
	<span class='icon-32-refresh' title='{$name}'>
	</span>
	{$name}
	</div>
		<div id='cl_newwindow'>
			<h3>Сканирование сайта</h3>
			<span class='cl_r_text'></span>
		</div>
	</td>
	";

	return $additions_buttons;
	}

}
?>