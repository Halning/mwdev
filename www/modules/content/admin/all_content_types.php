<?
if($_SESSION['status']=="admin"){
	$sql = "SELECT * FROM `content_types`";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0){
		while($row = mysql_fetch_assoc($result)){
			$type_name = $row['type_name'];
			$type_id = $row['type_id'];
			$count = $row['count'];
			require("modules/content/templates/admin.types.all.line.php");  
			$all_lines .= $all_line;
		}
	
	}
	
	$all_params = "";
	
	require("modules/content/templates/admin.types.all.head.php"); 

	require_once("templates/{$theme_name}/admin.all.php"); 
	


}

?>