<?
if ($_SESSION['status']=="admin")
{
	$_SESSION["lastpage2"]="/?admin=all_categories";
	if(isset($_GET["categoryID"]))
	{
//		creatfolder("images/commodities/{$commodityID}");
		$categoryID=$_GET["categoryID"];
		if(isset($_POST["add_category"]))
		{
			$_SESSION["lastpage"]=$request_url;
			$an_n=$_POST['name'];
			$visible=$_POST['visible'];
			$cat_desc=$_POST['cat_desc'];
			$an_c=array_shift($_POST['category']);
			$alias=$_POST['alias'];
			$order=$_POST['order'];
			$url=$_POST['url'];
			$use_alias=$_POST["use_alias"];
			$seotitle=$_POST['title'];
			$seodescription=$_POST['description'];
			$seokeywords=$_POST['keywords'];
			$h1=$_POST['h1'];
			$use_photo=$_POST["use_photo"];
			$seoImagesTitle = filter_input(INPUT_POST, 'images_title', FILTER_SANITIZE_STRING);
			$seoImagesAlt = filter_input(INPUT_POST, 'images_alt', FILTER_SANITIZE_STRING);
			$an_bp= getnewimg(1,1024,1024,"categories",$categoryID,"main.jpg") && getnewimg($catitemst,$catitemsx,$catitemsy,"categories",$categoryID,"s_main.jpg");

			$cat_visible=$_POST["cat_visible"];
			
			$query="
			UPDATE `shop_categories` 
			SET 
			`categories_of_commodities_parrent`='{$an_c}',
			`categories_of_commodities_order`='{$order}',
			`title`='{$seotitle}',
			`description`='{$seodescription}',
			`keywords`='{$seokeywords}',
			`alias`='{$alias}',
			`h1`='{$h1}',
			`images_title` = '{$seoImagesTitle}',
			`images_alt` = '{$seoImagesAlt}',
			`visible`='{$visible}',
			`cat_name`='{$an_n}', 
			`cat_desc`='{$cat_desc}'
			WHERE `categories_of_commodities_ID`='{$categoryID}'
			;
			";
			$aa = mysql_query($query);

			$center="Категория успешно изменена<br><br>
			<a href='/?admin=all_categories'>Перейти к списку категорий</a>
			";
			require_once("templates/$theme_name/mess.php"); 
	
		}else
		{
			$query = "
			SELECT * FROM `shop_categories` 
			WHERE `categories_of_commodities_ID`='{$categoryID}';
			";
			$result = mysql_query($query);
			if (mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_object($result);
				$r_category_id=$row->cat_id;
				$alias=$row->alias;
				$seotitle=$row->title;
				$seodescription=$row->description;
				$seokeywords=$row->keywords;
				$e_name=$row->cat_name;
				$cat_desc=$row->cat_desc;
				$h1=$row->h1;
				$cat_visible=$row->cat_visible==1?"checked":"";
				$p_category_id=$row->categories_of_commodities_parrent;
				$e_visible=$row->visible==1?"checked='checked'":"";
				$url=$row->url;
				$order=$row->categories_of_commodities_order;
				$use_alias_checked=$row->use_alias==1?"checked='checked'":"";
				$categories_of_commodities_photo=$row->categories_of_commodities_photo;
				$img=$categories_of_commodities_photo!=0?"<img src='/images/categories/{$categoryID}/s_main.jpg'>
				<br /><br />
				<input type='checkbox' name='use_photo' id='id_use_photo' value='1' checked><label for='id_use_photo'>Отображать изображение</label>":"";
				$seoImagesTitle = $row->images_title;
				$seoImagesAlt = $row->images_alt;
			}else
			{
				$e_name="Новая категоря {$categoryID}";
				$add_date=date("Y-m-d H:i:s");
				$query="
				INSERT INTO `shop_categories` 
				SET 
				`categories_of_commodities_parrent`='0',
				`categories_of_commodities_order`='{$categoryID}',
				`cat_name`='{$e_name}', 
				`lng_id`='{$sys_lng}',
				`categories_of_commodities_ID`='{$categoryID}'
				;
				";
				mysql_query($query);
				$use_alias_checked="checked";
				$e_visible="checked='checked'";
				$p_category_id=0;
				$order=$categoryID;
			}
			$active_cats[$p_category_id]=1;
			$categories=get_commodity_categories($active_cats,-1,$categoryID);

			$additions_buttons=get_edit_buttons("/?admin=delete_category&categoryID={$categoryID}");
			$it_item="Редактирование категории товаров";
			require_once("modules/commodities/templates/admin.category_edit.php"); 
			require_once("templates/$theme_name/admin.edit.php"); 
		}
	}
}
?>