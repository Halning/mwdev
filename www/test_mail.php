<?php

error_reporting(E_ALL);

	if(isset($_POST['send'])) {
		$to=$_POST['send'];
		$sub=$_POST['subject'];
		$message=$_POST['text'];
		$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
		mail($to, $sub, $message,$headers);
	}


 print_r(error_get_last());
?>
<html>
	<body>
	<form action="mail.php" method="post">
		Send:<input type="text" name="send" value="m.kosovec@makewear.com.ua" /><br>
		Subject:<input type="text" name="subject" value="Test" /><br>
		<textarea name="text" >Test Mail</textarea><br>
		<input type="submit" value="Send" />
	</form>
	</body>
</html>