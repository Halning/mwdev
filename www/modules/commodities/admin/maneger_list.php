<?
if ($_SESSION['status']=="admin"){
if(is_numeric($_GET['filterID']))
{
	$filter_id = intval($_GET['filterID']);
	
	if(is_numeric($_POST["add_new"]))
	{
		$sql="
		INSERT INTO `shop_filters-lists` 
		SET `list_filterid`='{$filter_id}';";
		mysql_query($sql);
		$last_id=mysql_insert_id();
		$sql="
		UPDATE `shop_filters-lists` 
		SET `list_order`='{$last_id}', `list_name`='Новый вариант выбора {$last_id}'
		WHERE `id`='{$last_id}';";
		mysql_query($sql);
	}
	if(isset($_POST["move"]))
	{
	
		
		$item_id=$_POST["item_id"];
		$sql="SELECT `list_order`,`id`,`list_filterid` FROM `shop_filters-lists` 
		WHERE `id`='{$item_id}';";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$ccorder=$row["list_order"];
			$ccparent=$row["list_filterid"];
		}
		
		$sing=$_POST["move"]=="down"?">":"<";
		$sing2=$_POST["move"]=="down"?"":"DESC";
		$sql="SELECT `list_order`,`id`,`list_filterid` FROM `shop_filters-lists` 
		WHERE `list_order`{$sing}'{$ccorder}' AND `list_filterid`='{$ccparent}'
		ORDER BY `list_order` {$sing2}
		LIMIT 0,1;";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$order=$row["list_order"];
			$articleID=$row["id"];
			
			$sql="UPDATE `shop_filters-lists` 
			SET `list_order`='{$order}'
			WHERE `id`='{$item_id}';";
			mysql_query($sql);
			
			$sql="UPDATE `shop_filters-lists` 
			SET `list_order`='{$ccorder}'
			WHERE `id`='{$articleID}';";
			mysql_query($sql);
			
		}else
		{
			$sing=$_POST["move"]=="down"?"+":"-";
			$query = "
			UPDATE `shop_filters-lists` 
			SET `list_order`=(`list_order`{$sing}1)
			WHERE `id`='{$item_id}';
			";
			mysql_query($query);
		}


	}
	$selected_par = is_numeric($_GET['parent'])?$_GET['parent']:0;
	$sql = "SELECT * FROM  `shop_filters-lists` WHERE `list_filterid`='{$filter_id}' 
	AND `list_parentid`='{$selected_par}' ORDER BY `list_order`
	";
	
	$result = mysql_query($sql);
	$forms='';
	if(mysql_num_rows($result) > 0){
		$forms = '<h3>Редактировать элементы списка</h3>';
		while($row = mysql_fetch_assoc($result)){
			$l_parentid_new[] = $row['list_parentid'];
			$l_parentfiltrid_new[] = $row['list_parentfiltrid'];
			$l_name = $row['list_name'];
			$l_id = $row['id'];
			$l_filterid = $row['list_filterid']; 
			$l_order = $row['list_order'];
			$l_parentid = $row['list_parentid'];
			//$l_parentfiltrid = $row['list_parentfiltrid'];
			require("modules/commodities/templates/admin.filter.list.all.line.php"); 
			$all_lines.=$all_line;
		}	
	}
	$select_parent = select_parent($l_parentfiltrid);
	$additions_buttons=get_new_buttons2("Добавить");
	require_once("modules/commodities/templates/admin.filter.list.all.head.php");
	require_once("templates/$theme_name/admin.all.php"); 
}
}

?>