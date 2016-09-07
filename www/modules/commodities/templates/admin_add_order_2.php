<?php
if ($_SESSION['status']=="admin")
{
	$center = "
		<form action='action_page.php' method="POST">
		Имя:<br>
		<input type='text' name= 'name' >
		<br>
		Телефон:<br>
		<input type='text' name='phone' >
		<br>
		email<br>
		<input type='text' name='email' >
		<br>
		<input type='submit' value='Submit'>
		</form>
	";
	if(isset($_POST["submit"]))
	{
		$name = $_POST["name"];
		$email = $_POST["email"];
		$phone = $_POST["phone"];

		$sql = "INSERT INTO `shop_orders` SET `name` = {$name}, `email` = {$email}, `tel` = {$phone}";
		$res = mysql_query($sql);

		echo("all done");

	}
}