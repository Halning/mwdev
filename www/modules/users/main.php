<?php
/**
* OwerCMS
* Модуль управления пользователями
* @package OwerCMS
* @author Ower
* @version 1.3.25
* @since engine v.0.10
* @link http://www.ower.com.ua
* @copyright (c) 2010+ by Ower
*/

	$admin_left_menu_last.="
	<li><div class='cl_menu_div'><div class='icon-32-users cl_left_bt'></div><a href='/?admin=all_users'>Пользователи</a></div>
	<ul>
		<li><a href='/?admin=all_users'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Все пользователи</a></li>
		<li><a href='/?admin=add_user'><img src='/templates/{$theme_name}/img/add.png' class='ico'>&nbsp;Добавить</a></li>
	</ul>
	</li>
";

if ((isset($_GET["admin"])))
{
	$url_admin["users"]="modules/users/admin/all_users.php";
	$url_admin["all_users"]="modules/users/admin/all_users.php";
	$url_admin["add_user"]="modules/users/admin/add_user.php";
	$url_admin["edit_user"]="modules/users/admin/edit_user.php";
	$url_admin["delete_user"]="modules/users/admin/delete_user.php";
	$url_admin_menu["users"]="users";
	$url_admin_menu["all_users"]="users";
	$url_admin_menu["add_user"]="users";
	$url_admin_menu["delete_user"]="users";
	$url_admin_menu["edit_user"]="users";
    require_once("modules/users/site/functions.php");
    account_options();
    $templates->set_tpl('{$account}',get_account());
} elseif (!isset($_GET["admin"])) {
//	$url_page["remind_password"]="func_remind_password";
//	$url_page["registration"]="func_registration";
//	$url_page["activate_user"]="reg_confirmation";
//	$url_page["signin"]="func_signin";
//	$url_page["cabinet"]="cabinet";
//  $url_page["profile"]="get_profile";
//  $url_page["edit_profile"]="edit_profile";
//	$url_page["user"]="modules/users/site/user.php";
//	$url_page["read_mess"]="modules/users/site/read_mess.php";
//	$url_page["delete_mess"]="modules/users/site/delete_mess.php";
//	$url_page["answer_mess"]="modules/users/site/answer_mess.php";
//	$url_page["mess_outbox"]="modules/users/site/mess_outbox.php";
//	$url_page["mess_inbox"]="modules/users/site/mess_inbox.php";
//	$url_page["changeavatar"]="modules/users/site/changeavatar.php";
}