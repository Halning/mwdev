<?
if ($_SESSION['status']=="admin")
{

if (isset($_POST["add_discount"]))
{
	$dis_val1=$_POST['discount_val1'];
	$dis_val2=$_POST['discount_val2'];
	$query="
INSERT INTO `shop_discount` 
SET 
`dis_val1`='{$dis_val1}', 
`dis_val2`='{$dis_val2}'
;";
	@mysql_query($query); 

	
	$center="<br />Скидка успешно добавлена<br /> <br /><a href='/?admin=all_discount'>список всех скидок</a>";
	require_once("templates/{$theme_name}/mess.php"); 
	

}else
{
	$it_item="Добавление скидки";
	$additions_buttons=get_add_buttons();
	require_once("modules/commodities/templates/admin.edit_discount.php");
	require_once("templates/{$theme_name}/admin.edit.php");
}

}
?>