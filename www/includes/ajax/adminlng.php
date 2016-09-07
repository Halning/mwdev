<?
header("Content-Type: application/x-javascript; charset=UTF-8");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
session_destroy();
//ini_set('display_errors',0);
require_once("../../settings/conf.php");
require_once("../../settings/connect.php");
$urlbb="../../includes/bbcode/bbcode.lib.php";
require_once("../../settings/functions.php");
// -----------------------------------------------------------------------  СЕССИИ

// --------
	bd_session_start();
	$_SESSION['sel_lang']=isset($_POST["sel_lang"])?$_POST["sel_lang"]:$_SESSION['sel_lang'];
	$_SESSION['sel_lang']=isset($_GET["sel_lang"])?$_GET["sel_lang"]:$_SESSION['sel_lang'];
	$sys_lng=is_numeric($_SESSION['sel_lang'])?$_SESSION['sel_lang']:$sys_lng;
	$sel_lang=is_numeric($_SESSION['sel_lang'])?$_SESSION['sel_lang']:1;
	echo $_GET['callback']."({lng:'{$sys_lng}'})";

?>