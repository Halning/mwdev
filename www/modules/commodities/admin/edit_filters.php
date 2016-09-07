<?
if ($_SESSION['status']=="admin")
{
	if(isset($_POST['add_filter']) and is_numeric($_POST['filter_id'])){
		$filter_id = $_POST['filter_id'];
		$f_name = $_POST['filter_name'];
		$f_desc = $_POST['filter_description'];
		$f_type = $_POST['filter_type'];
		$f_order = $_POST['filter_order'];
		$f_cat = $_POST['cat_id'];
		$necessarily=$_POST["necessarily"];
		$necessarily2=$_POST["necessarily2"];
		$f_nes = $_POST['filter_necessarily'];
		$f_parent_list = $f_type==2?intval($_POST['parent_list']):0;
		$sql = "UPDATE `shop_categories-filters` SET
		`filtr_order`='{$f_order}',
		`filtr_typeid`='{$f_type}',
		`list_parent`={$f_parent_list},
		`filtr_name`='{$f_name}',
		`filtr_desc`='{$f_desc}',
		`necessarily`='{$necessarily}',
		`necessarily2`='{$necessarily2}'
		WHERE `filtr_id`='{$filter_id}';
		";
		
		mysql_query($sql) or die ("ошибка запроса ($sql)...".mysql_error());
			
		$center="Фильтр успешно изменен<br><br>

		<a href='/?admin=all_categories'>Список категорий</a>";

		require_once("templates/$theme_name/mess.php"); 
		
	}elseif(is_numeric($_GET['filterID'])){
		$filter_id = $_GET['filterID'];
		$sql="
		SELECT 	`filtr_id`, `filtr_order`, `fitr_catid`, 
		`filtr_typeid`, `necessarily2`,`necessarily`, `list_parent`, `filtr_name`, `filtr_desc` 
		FROM `shop_categories-filters` 
		WHERE `filtr_id`='{$filter_id}';
		";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$cat_name2 = empty($cat_id)?get_cat_name($row['fitr_catid']):$cat_name;
		$f_id = $row['filtr_id'];
		$f_order = $row['filtr_order'];
		$necessarilychecked=$row['necessarily']==1?"checked":"";
		$necessarilychecked2=$row['necessarily2']==1?"checked":"";
		$f_catid = $row['fitr_catid'];
		$f_type_sel = get_select_type($row['filtr_typeid']);
		if($row['filtr_typeid']==2){
			$parent=$row['list_parent'];
			$f_parent_sel = get_parent_filter_list($f_id, $f_catid, $parent);
		}
		$f_neces = $row['necessarily'];
		$f_name = $row['filtr_name'];
		$f_desc = $row['filtr_desc'];
		if($f_neces==1){
			$selected_yes = "selected";
			$selected_no = "";
		}else{
			$selected_yes = "";
			$selected_no = "selected";
		}
		$it_item="Редактирование свойства  \"".$f_name."\" ";
		$additions_buttons=get_edit_buttons("/?admin=delete_commodity&commodityID={$commodityID}");
		require_once("modules/commodities/templates/admin.add_filter.php"); 
		require_once("templates/$theme_name/admin.edit.php"); 
		

	}

}

?>