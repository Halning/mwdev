<?
if ($_SESSION['status']=="admin")
{
if(is_numeric($_GET["discount_id"]))
{
	$discount_id=$_GET["discount_id"];




if (isset($_POST["add_discount"]))
{

	$dis_val1=$_POST['discount_val1'];
	$dis_val2=$_POST['discount_val2'];
	$query="
UPDATE `shop_discount` 
SET 
`dis_val1`='{$dis_val1}', 
`dis_val2`='{$dis_val2}'
WHERE `dis_id`='{$discount_id}'
;";
	@mysql_query($query); 

	
	$center="<br />Скидка успешно изменена<br /> <br /><a href='/?admin=all_discount'>список всех скидок</a>";
	require_once("templates/{$theme_name}/mess.php"); 
	

}else
{
	//Чтение текущих параметров скидки
	$query="
SELECT * FROM `shop_discount` 
WHERE
`dis_id`='{$discount_id}'
;";
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_object($result);
		$dis_val1=$row->dis_val1;
		$dis_val2=$row->dis_val2;
	}
	//Чтение текущих параметров скидки


	$it_item="Редактирование скидки";
	$additions_buttons=get_edit_buttons("/?admin=delete_discount&discount_id={$discount_id}");
	require_once("modules/commodities/templates/admin.edit_discount.php"); 
	require_once("templates/{$theme_name}/admin.edit.php");
}
}
}
?>