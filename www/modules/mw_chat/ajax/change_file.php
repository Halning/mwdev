<?php
	
	$file=$_POST['file'];
	$username=$_POST['username'];
	$format_file=end(explode(".",$file));

	// unlink("../../../online/images/avatar/op_avatar_".$username.".jpg");
	// unlink("../../../online/images/avatar/op_avatar_".$username.".png");
	// unlink("../../../online/images/avatar/op_avatar_".$username.".gif");
	$filename = '../../../online/images/avatar/op_avatar_'.$username.'.jpg';
	$filenamepng = '../../../online/images/avatar/op_avatar_'.$username.'.png';
	$filenamegif = '../../../online/images/avatar/op_avatar_'.$username.'.gif';

 	// if (file_exists($filename)) {
	// 	unlink($filename);
	// }elseif(file_exists($filenamepng)){
	// 	unlink($filenamepng);
	// }elseif(file_exists($filenamegif)){
	// 	unlink($filenamegif);
	// }

	rename("../../../online/images/avatar/".$file ,"../../../online/images/avatar/op_avatar_".$username.".".$format_file);

	echo "op_avatar_".$username.".".$format_file;
?>