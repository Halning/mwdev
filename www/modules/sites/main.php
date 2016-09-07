<?
if (($_SESSION['status']=="admin")&&(isset($_GET["admin"])))
{
		$admin_top_menu_res.="
<td class='button' id='toolbar-popup-Popup'>
<a class='modal' href='/?admin=sites'>
<span class='icon-32-config' title='Сайты'>
</span>
Сайты
</a>
</td>
";
		$admin_top_menu.="
<td class='button' id='toolbar-popup-Popup'>
<a class='modal' href='/?admin=edit_site_default'>
<span class='icon-32-config' title='Настройки и Keywords'>
</span>
Настройки
</a>
</td>
";

	$admin_menu["sites"]="
	<span class='head' align='center'>Настройки</span>

	<a href='/?admin=edit_site_default'><img src='/templates/{$theme_name}/img/toolbar/icon-32-config.png' class='ico'>&nbsp;Настройки сайта</a>
";

	$admin_left_menu.="
	<li><div class='cl_menu_div'><div class='icon-32-config cl_left_bt'></div><a href='/?admin=sites'>Сайты</a></div>
	<ul>

	<li><a href='/?admin=edit_site_default'><img src='/templates/{$theme_name}/img/toolbar/icon-32-config.png' class='ico'>&nbsp;Настройки сайта</a></li>
	<li><a href='/?admin=edit_tpl'><img src='/templates/{$theme_name}/img/toolbar/icon-32-config.png' class='ico'>&nbsp;Файл шаблона</a></li>
	</ul>
	</li>
";
	$url_admin["modules"]="modules/sites/admin/all_modules.php";
	$url_admin["start"]="modules/sites/admin/start.php";
	$url_admin["sites"]="modules/sites/admin/all_sites.php";
	$url_admin["all_sites"]="modules/sites/admin/all_sites.php";
	$url_admin["add_site"]="modules/sites/admin/add_site.php";
	$url_admin["delete_site"]="modules/sites/admin/delete_site.php";
	$url_admin["edit_site"]="modules/sites/admin/edit_site.php";
	$url_admin["edit_site_default"]="modules/sites/admin/edit_site_default.php";
	$url_admin["edit_tpl"]="modules/sites/admin/edit_tpl.php";
	$url_admin_menu["sites"]="sites";
	$url_admin_menu["all_sites"]="sites";
	$url_admin_menu["add_site"]="sites";
	$url_admin_menu["delete_site"]="sites";
	$url_admin_menu["edit_site"]="sites";
	$url_admin_menu["edit_site_default"]="sites";
} elseif (!isset($_GET["admin"])) {
//	$url_page["cms_setdefault"]="cms_setdefault";
//	$url_page["cms_info"]="cms_info";
//	$url_page["googlecod"]="googlecod";
//	require_once("modules/sites/site/functions.php");
}