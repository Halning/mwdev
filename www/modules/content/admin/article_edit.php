<?
if ($_SESSION['status']=="admin")
{
	if(isset($_GET["articleID"]))
	{
		$_SESSION["lastpage2"]="/?admin=all_articles";
		$articleID=$_GET["articleID"];	
		$_SESSION["upload_dir"]="images/articles/{$articleID}";
		if(isset($_POST["add_articles"]))
		{
			$name=$_POST['name'];
			$text=$_POST['text'];
			$alias=$_POST['alias'];
			$short_text=$_POST['short_text'];
			$alias=$_POST['alias'];
			$title=$_POST['title'];
			$description=$_POST['description'];
			$keywords=$_POST['keywords'];
			$content=$_POST['content'];
			$parent=$_POST['parent'];
			$parent=$parent==-1?0:$parent;
			$order=is_numeric($_POST['order'])?$_POST['order']:$articleID;
			$use_photo=$_POST["use_photo"];
			$an_bp= getnewimg(1,1024,1024,"articles",$articleID,"title.jpg") && getnewimg($artimgt,$artimgx,$artimgy,"articles",$articleID,"s_title.jpg");
			$img=$an_bp||$use_photo==1?1:0;
			$add_date=$_POST["add_date"];
			$visible=$_POST["visible"];
			$h1=$_POST["h1"];
			$use_alias=$_POST["use_alias"];
			$menu=$_POST["menu"];
			$block=$_POST["block"];
			$type_id=$_POST["type_id"];
			$query="
			UPDATE `content_articles` SET
			`h1`='{$h1}',
			`title`='{$title}',
			`description`='{$description}',
			`keywords`='{$keywords}',
			`content`='{$content}',
			`order`='{$order}',
			`name`='{$name}',
			`text`='{$text}',
			`alias`='{$alias}',
			`type_id`='{$type_id}',
			`use_alias`='{$use_alias}',
			`menu`='{$menu}',
			`block`='{$block}',
			`lng_id`='{$sel_lang}',
			`visible`='{$visible}',
			`parent`='{$parent}',
			`add_date`='{$add_date}',
			`image`='{$img}'
			WHERE `articleID`='{$articleID}';";
			@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());
			insert_fields_values($articleID);
			update_content_images($articleID);
			$center="Страница успешно изменена <br><br><a href='/?admin=all_articles'>Перейти к списку всех страниц</a>";
			require_once("templates/{$theme_name}/mess.php");
		}else
		{
			$query = "SELECT * FROM `content_articles` WHERE `articleID`='{$articleID}'";
			$result = mysql_query($query);
			if (mysql_num_rows($result) > 0) 
			{
				$row = mysql_fetch_object($result);
				$title=$row->title;
				$description=$row->description;
				$keywords=$row->keywords;
				$content=$row->content;
				$h1=$row->h1;
				
				$name=$row->name;
				$text=$row->text;
				$alias=$row->alias;
				$order=$row->order;
				$image=$row->image;
				$add_date=$row->add_date;
				$visible=$row->visible;
				$use_alias_checked=$row->use_alias==1?"checked":"";
				$checkbox_visible=$row->visible==1?"checked":"";
				$checkbox_menu=$row->menu==1?"checked":"";
				$checkbox_block=$row->block==1?"checked":"";
				$parent=$row->parent;
				$type_id=$row->type_id;
				$img=$image==1?"<img src='http://{$gallery_domen}/images/articles/{$articleID}/s_title.jpg' style='max-width:100px;'><br /><br />
				<input type='checkbox' name='use_photo' id='id_use_photo' value='1' checked><label for='id_use_photo'>Отображать изображение</label>":"
				<img src='/templates/admin/img/default-image.png' style='max-width:100px;'>
				";
			}else
			{
				$name="Новая страница {$articleID}";
				$add_date=date("Y-m-d H:i:s");
				$query="
				INSERT INTO `content_articles` SET			
				`order`='{$order}',
				`name`='{$name}',
				`text`='',
				`alias`='',
				`lng_id`='{$sel_lang}',
				`image`='',
				`articleID`='{$articleID}';";
				mysql_query($query);
				$checkbox_visible="checked";
				$use_alias_checked="checked";
				$type_id=1;
			}
			
			$active_cats[$parent]=1;
			$ses_id=session_id();
			$categories=get_contents_categories($active_cats,$articleID);
			$type_category=napalm_type($type_id);
			$dop_fields = get_fields_list($articleID,$type_id);
			$articles_images = get_articles_images($articleID,$type_id);
			$dop_fields=$dop_fields==""?"Дополнительных полей нет":$dop_fields;
			$name2=mb_substr($name,0,20,'utf-8');
			$it_item="Редактирование \"{$name2}\" ";
			$additions_buttons=get_edit_buttons("/?admin=delete_articles&article_id={$articleID}");
			require_once("modules/content/templates/admin.articles_edit.php");
			require_once("templates/{$theme_name}/admin.edit.php");   
		}
	}
}

?>