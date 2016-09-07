<?
header("Content-Type: application/x-javascript; charset=UTF-8");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
session_destroy();
//ini_set('display_errors',0);
require_once("../../settings/conf.php");
require_once("../../settings/connect.php");
require_once("../../settings/functions.php");
// -----------------------------------------------------------------------  СЕССИИ

// --------
	bd_session_start();
	if ($_SESSION['status']=="admin")
	{
		
		$table=$_GET["table"];
		$id=$_GET["id"];
		$field=$_GET["field"];
		$idfield=$_GET["idfield"];
		$text=$_GET["text"];
		$text=str_replace("http://"," http://",$text);
		$text=str_replace("|-|","#",$text);
		$sql="
		UPDATE `{$table}` 
		SET `{$field}`='{$text}'
		WHERE `{$idfield}`='{$id}'
		;";
		$res=mysql_query($sql);
		
		
		
		
		echo $_GET['callback']."({lng:'ok'})";
	}

?>