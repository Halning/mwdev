<?

if($_SESSION['status']=="admin"){
	if($_POST['do'] == 'add'){
	
		$name = $_POST["field_name"];
		$field_kind = $_POST["field_kind"];
		$field_typeid = $_GET['typeID'];
		$sql = "
		INSERT INTO `content_types_fields` 
		SET
		`field_name` = '{$name}',
		`field_kind` = '{$field_kind}',
		`field_typeid` = '{$field_typeid}';
		";
		mysql_query($sql) or die ("ошибка запроса ($sql)...".mysql_error());
		$center="Поле успешно добавлено<br><br>
		<a href='/?admin=edit_types&typeID={$field_typeid}'>Перейти к списку полей</a>";
		require_once("templates/{$theme_name}/mess.php");
	}else
	{
		if($_POST['do'] == 'update'){	
			$name = $_POST["field_name"];
			$field_kind = $_POST["field_kind"];
			$field_typeid = $_POST["field_typeid"];
			$fileld_id = $_POST["id"];
			$sql = "
			UPDATE `content_types_fields`
			SET
			`field_name` = '{$name}',
			`field_kind` = '{$field_kind}',
			`field_typeid` = '{$field_typeid}'
			WHERE `fileld_id`='{$fileld_id}';
			";
			mysql_query($sql) or die ("ошибка запроса ($sql)...".mysql_error());
		}
		if(is_numeric($_GET['typeID']))
		{
			$sql = "SELECT `fileld_id`, `field_name`, `field_kind`, `field_typeid` FROM content_types_fields
			WHERE `field_typeid`='{$_GET['typeID']}'";
			$result = mysql_query($sql);


			if(mysql_num_rows($result) > 0){

				while($row = mysql_fetch_assoc($result))
				{
					$opt = sel_kind($field_kind);
					$fileld_id = $row['fileld_id'];
					$field_name = $row['field_name'];
					$field_kind = $row['field_kind'];
					$field_typeid = $row['field_typeid'];
					require("modules/content/templates/admin.types.form.php");
					$forms .=$form;
				}
			}
			require_once("modules/content/templates/admin.types.new.form.php");
			$edit = $forms.$new_form;
			require_once("templates/{$theme_name}/admin.edit.php");
		}
	}

}
?>