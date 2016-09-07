<?php

	//============Upload photo===============================================

	if(isset($_FILES[0]["name"])){

		$tmpFile = $_FILES[0]["tmp_name"];

		$filename = "../../../templates/shop/image/block_share/".$_FILES[0]["name"];
		if(move_uploaded_file($tmpFile,$filename)) {
			echo $_FILES[0]["name"];
		}

		// echo "1: ".$_FILES[0]["name"];

	}

?>