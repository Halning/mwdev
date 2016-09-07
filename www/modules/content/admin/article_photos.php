<?php
if ($_SESSION['status']=="admin")
{
	if(is_numeric($_GET["articleID"]))
	{
		$articleID=$_GET["articleID"];

		if(isset($_POST["move"]))
		{
			$item_id=$_POST["item_id"];
			$sing=$_POST["move"]=="down"?"-":"+";
			$query = "
UPDATE `content_images` 
SET `order`=(`order`{$sing}1)
WHERE `img_id`='{$item_id}';
";
			mysql_query($query);
		}

		$query = "
SELECT * FROM `content_images` 
WHERE `img_artid`='{$articleID}' ORDER BY `order`;
";
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0) 
		{
			$all_photos_lines="";

			for($i=1;$i<=mysql_num_rows($result);$i++)
			{
				$row = mysql_fetch_object($result);
				$img_id=$row->img_id;
				$order=$row->order;
				$photo_name="<img src='/images/articles/{$articleID}/s_{$img_id}.jpg' border='0' width='75px'>";
				require("modules/content/templates/admin.photos.all.line.php");  		
				$all_lines.=$all_line;
			}
		
		}
		$its_name="Все изображения этой статьи";
		$additions_buttons=get_new_buttons("/?admin=add_article_photo&articleID={$articleID}","Добавить изображение");
		require("modules/content/templates/admin.photos.all.head.php"); 
		require_once("templates/{$theme_name}/admin.all.php");
	}
}
?>