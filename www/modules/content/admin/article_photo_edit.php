<?
if ($_SESSION['status']=="admin")
{
		$photo_id=is_numeric($_GET["photo_id"])?$_GET["photo_id"]:0;
		$photoID=$photo_id;
		

		if(isset($_POST["add_photo"]))
		{
			$query="
SELECT `img_artid`
FROM `content_images` 
WHERE `img_id`='{$photo_id}';
";

			$result = mysql_query($query);			
			if (mysql_num_rows($result) > 0) 
			{
				$row = mysql_fetch_object($result);

				$img_artid=$row->img_artid;

			}		
		
			$order=$_POST["photo_order"];
			$photo_name=$_POST['photo_name'];
			$photo_desc=$_POST['photo_desc'];			
			
			$an_bp= getnewimg(1,1024,1024,"articles",$img_artid,"{$photoID}.jpg",1) && getnewimg(1,300,600,"articles",$img_artid,"s_{$photoID}.jpg",2);

			$query="UPDATE `content_images` SET `order`='{$order}', `img_name`='{$photo_name}', `img_desc`='{$photo_desc}' {$photo} WHERE `img_id`='{$photo_id}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());



			$center="Фотография успешно изменена<br><br>
			<a href='?admin=article_photos&articleID={$img_artid}'>Перейти к списку фотографий</a>
			";
			require_once("templates/{$theme_name}/mess.php"); 
	
		}else
		{
		

		
			$query="
SELECT *
FROM `content_images` 
WHERE `img_id`='{$photo_id}';
";
//echo $query;die();
			$result = mysql_query($query);			
			if (mysql_num_rows($result) > 0) 
			{
				$row = mysql_fetch_object($result);
				$photoID=$row->img_id;
				$img_artid =$row->img_artid ;
				$photo_order=$row->order;
				$photo_name=$row->img_name;
				$photo_desc=$row->img_desc;
				$photo_desc=fix_replace($photo_desc);  
		
				$img="<img src='/images/articles/{$img_artid}/s_{$photoID}.gif'>";
			}	

			$it_item="Редактирование фотографии \"{$photo_name}\" ";
		
		$additions_buttons=get_edit_buttons("/?admin=delete_article_photo&photo_id={$photo_id}");
			require_once("modules/content/templates/admin.articles_photo_edit.php");
			require_once("templates/{$theme_name}/admin.edit.php");   
		}
}
?>