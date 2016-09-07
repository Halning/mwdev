<?php

session_destroy();
//ini_set('display_errors',0);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");
bd_session_start();
require_once("../../../settings/main.php");
	
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		echo "ajax".$id;
	}

?>