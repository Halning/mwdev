<?
if ($_SESSION['status']=="admin")
{
		
		
		$articleID=$_GET["articleID"];

		if(isset($_POST["add_photo"]))
		{
			$tr=12345;
			$query="
INSERT INTO `content_images`
SET `order`='{$tr}', `img_artid`='{$articleID}';
";

			$result = mysql_query($query);	
			$query="
SELECT *
FROM `content_images` 
WHERE `order`='{$tr}';
";

			$result = mysql_query($query);			
			if (mysql_num_rows($result) > 0) 
			{
				$row = mysql_fetch_object($result);

				$img_id=$row->img_id;
				$img_artid=$articleID;
				$photoID=$img_id;
			}		
		
			$order=$_POST["photo_order"];
			$photo_name=$_POST['photo_name'];
			$photo_desc=$_POST['photo_desc'];			
			
			$an_bp= getnewimg(1,1024,1024,"articles",$img_artid,"{$photoID}.jpg",1) && getnewimg(1,300,600,"articles",$img_artid,"s_{$photoID}.jpg",2);
			

			$query="UPDATE `content_images` SET `order`='{$order}', `img_name`='{$photo_name}', `img_desc`='{$photo_desc}'  WHERE `img_id`='{$img_id}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());



			$center="Фотография успешно Добавлена<br><br>
			<a href='?admin=article_photos&articleID={$img_artid}'>Перейти к списку фотографий</a>
			";
			require_once("templates/{$theme_name}/mess.php"); 
	
		}else
		{
			$img="<img src='NULL'>";
				
			$it_item="Добавление изображения";
			$additions_buttons=get_add_buttons();
			require_once("modules/content/templates/admin.articles_photo_edit.php");
			require_once("templates/{$theme_name}/admin.edit.php");   
		}
}
?>