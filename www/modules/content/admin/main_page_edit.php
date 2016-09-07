<?php
if ($_SESSION['status']=="admin")
{
	if($_POST['add_text']==true){
		$main_page_text = $_POST['text'];
		$main_page_title = $_POST['title'];
		$sql = "UPDATE `domens_description` SET 
		`main_page_title`='{$main_page_title}',
		`main_page_text`='{$main_page_text}' 
		WHERE `dom_id`='{$domenID}'
		";
		mysql_query($sql) or die ("ошибка запроса ($sql)...".mysql_error());
		$center="Описание главной страницы изменено<br><br>

			<a href='/?admin=all_articles'>Перейти на главную</a>

		";

		require_once("templates/$theme_name/mess.php"); 
	}else{
		/*$sql = "SELECT * FROM `domens_description` WHERE `dom_id`='{$domenID}'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			$main_page_title = $row['main_page_title'];
			$main_page_text = $row['main_page_text'];
			$additions_buttons=get_add_buttons();
			
			require_once("modules/content/templates/admin.main_page_edit.php"); 
			require_once("templates/{$theme_name}/admin.edit.php");
		}*/
	}
	
	
}
?>