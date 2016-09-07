<?
if ($_SESSION['status']=="admin"){

	$sql="
	SELECT 	*
	FROM `modules` ORDER BY `module_order`;
	";
	$result = mysql_query($sql);
	$all_lines = '';
	if(mysql_num_rows($result) > 0){
		while($row = mysql_fetch_assoc($result)){
			require("modules/sites/templates/admin.modules.all.line.php"); 
			$all_lines .= $all_line;
		}
	}
	
	$its_name="Все модули";

	
	require("modules/sites/templates/admin.modules.all.head.php"); 
	require_once("templates/$theme_name/admin.all.php"); 
}

?>