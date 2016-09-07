<?
if($_SESSION['status']=="admin"){
$_SESSION["lastpage2"]="/?admin=all_filters";
	if(isset($_POST['add_filter'])){
		$f_name = $_POST['filter_name'];
		$f_desc = $_POST['filter_description'];
		$f_type = $_POST['filter_type'];
		$f_order = $_POST['filter_order'];
		$f_cat = $_POST['cat_id'];
		$f_nes = $_POST['filter_necessarily'];
		$f_date = date("Y-m-d H:i:s");
		$necessarily=$_POST["necessarily"];
				
		if(!is_numeric($f_order))
		{
			$q  = mysql_query("SHOW TABLE STATUS LIKE 'shop_categories-filters'");
			$f_order = mysql_result($q, 0, 'Auto_increment'); 
		}
		
		$sql = "INSERT INTO `shop_categories-filters` SET
		`filtr_order`='{$f_order}',
		`fitr_catid`='{$f_cat}',
		`filtr_typeid`='{$f_type}',
		`add_date`='{$f_date}',
		`lng_id`='{$sys_lng}',
		`filtr_name`='{$f_name}',
		`necessarily`='{$necessarily}',
		`filtr_desc`='{$f_desc}'
		";
		mysql_query($sql) or die ("ошибка запроса ($sql)...".mysql_error());
		
		$center="Фильтр успешно добавлен<br><br>

		<a href='{$request_url}'>Добавить ещё</a>
		<br /><a href='/?admin=all_filters'>Список всех фильтров категории</a>
		
		";

		require_once("templates/$theme_name/mess.php"); 
		
	}elseif(is_numeric($_GET['catID'])){
	
		$selected_no = "selected";
		$f_type_sel = get_select_type();
		$f_catid = $_GET['catID'];
		//$additions_buttons=get_add_buttons();
		$it_item="Добавление фильтра товаров";
		$additions_buttons=get_add_buttons();
		require_once("modules/commodities/templates/admin.add_filter.php"); 
		require_once("templates/$theme_name/admin.edit.php"); 
		
	}
}

?>