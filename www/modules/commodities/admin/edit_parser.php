<?
if ($_SESSION['status']=="admin")
{
	
	if(isset($_GET["id"]))
	{
		
		$id=$_GET["id"];
		if(isset($_POST["add"]))
		{
			$links11=$_POST["links11"];
			
			$links11=str_replace("\r", "", $links11);
			$links11=str_replace("\n", " ", $links11);
			$links11=str_replace("  ", " ", $links11);
			$query="
			UPDATE `parser` 
			SET 
			`links11`='{$links11}'
			WHERE `id`='{$id}'
			;
			";
			mysql_query($query);

			$center="Правило успешно изменено<br><br>
			<a href='/?admin=parser'>Перейти к списку правил</a>
			";
			require_once("templates/$theme_name/mess.php"); 
	
		}else
		{
			$query = "
			SELECT * FROM `parser` 
			WHERE `id`='{$id}';
			";
			$result = mysql_query($query);
			if (mysql_num_rows($result) > 0) 
			{
				$row = mysql_fetch_object($result);
				$links11=$row->links11;
			}
			

			$additions_buttons=get_edit_buttons("/?admin=delete_parser&id={$id}");
			$it_item="Редактирование правила";
			require_once("modules/commodities/templates/admin.parser_edit.php"); 
			require_once("templates/$theme_name/admin.edit.php"); 
		}
	}
}
?>