<?php
if (($_SESSION['status']=="admin")&&(isset($_GET["admin"])))
{
    $module_name="comments";
//$$module_name = new modul;
//$$module_name->name1="Комментарии";
//$$module_name->name2="комментарий";
//$$module_name->name3="комментарии";
//$$module_name->name4="комментария";
    admin_names("comments");
    $admin_left_menu.="
	<li><div class='cl_menu_div'><div class='icon-32-{$module_name} cl_left_bt'></div><a href='/?admin=all_articles'>Комментарии</a></div>
	<ul>
		<li><a href='/?admin=all_{$module_name}'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Все {$mod_name3}</a></li>
	</ul>
	</li>
";
	$url_admin[$module_name]="modules/{$module_name}/admin/all.php";
	$url_admin["all_{$module_name}"]="modules/{$module_name}/admin/all.php";
	$url_admin["add_{$module_name}"]="modules/{$module_name}/admin/add.php";
	$url_admin["delete_{$module_name}"]="modules/{$module_name}/admin/delete.php";
	$url_admin["edit_{$module_name}"]="modules/{$module_name}/admin/edit.php";
	$url_admin_menu[$module_name]=$module_name;
	$url_admin_menu["all_{$module_name}"]=$module_name;
	$url_admin_menu["add_{$module_name}"]=$module_name;
	$url_admin_menu["delete_{$module_name}"]=$module_name;
	$url_admin_menu["edit_{$module_name}"]=$module_name;	

	require_once("modules/{$module_name}/admin/functions.php");

} elseif(!isset($_GET["admin"])) {
//	require_once("modules/{$module_name}/site/functions.php");
}
