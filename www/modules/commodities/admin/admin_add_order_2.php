<?php
if ($_SESSION['status']=="admin")
{
	$center = "
		<form method='POST' action = 'http://www.makewear.com.ua/?admin=admin_add_order_2'>
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
	if(isset($_POST["name"]))
	{
		$name = $_POST["name"];
		$email = $_POST["email"];
		$phone = $_POST["phone"];
		$date = date("Y-m-d H:i:s");
		//echo($sql);
		$query ="INSERT INTO `users` (`user_realname`, `user_tel`, `user_email`, `user_name`) VALUES ('{$name}', '{$phone}', '{$email}', '{$name}');";
		$res = mysql_query($query)or die("Err1, ".mysql_error());
		$user_id = mysql_insert_id();
		$sql = "INSERT INTO `shop_orders` 
		SET `name` = '{$name}',
		 `email` = '{$email}', 
		 `tel` = '{$phone}', 
		 `date` = '{$date}', 
		 `user_id`=	{$user_id};";
//		echo($sql);
		$res = mysql_query($sql) or die("Err2, ".mysql_error());
		
		echo("all done");
		
	}
}