<?
if ($_SESSION['status']=="admin")
{
	$result = mysql_query("SHOW TABLE STATUS LIKE 'content_articles'");
	$row = mysql_fetch_array($result);
	$new_id = $row['Auto_increment'];

	if(isset($_POST["move"]))
	{
	
		
		$item_id=$_POST["item_id"];
		$sql="SELECT `order`,`articleID`,`parent` FROM `content_articles` 
		WHERE `articleID`='{$item_id}';";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$ccorder=$row["order"];
			$ccparent=$row["parent"];
		}
		
		$sing=$_POST["move"]=="down"?"<":">";
		$sing2=$_POST["move"]=="down"?"DESC":"";
		$sql="SELECT `order`,`articleID` FROM `content_articles` 
		WHERE `order`{$sing}'{$ccorder}' AND `parent`='{$ccparent}'
		ORDER BY `order` {$sing2}
		LIMIT 0,1;";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$order=$row["order"];
			$articleID=$row["articleID"];
			
			$sql="UPDATE `content_articles` 
			SET `order`='{$order}'
			WHERE `articleID`='{$item_id}';";
			mysql_query($sql);
			
			$sql="UPDATE `content_articles` 
			SET `order`='{$ccorder}'
			WHERE `articleID`='{$articleID}';";
			mysql_query($sql);
			
		}else
		{
			$sing=$_POST["move"]=="down"?"-":"+";
			$query = "
			UPDATE `content_articles` 
			SET `order`=(`order`{$sing}1)
			WHERE `articleID`='{$item_id}';
			";
			mysql_query($query);
		}


	}
	if(isset($_POST["st_con_id"]))
	{
		$query = "
		UPDATE `content_articles` 
		SET `visible`='{$_POST["st_con_st"]}'
		WHERE `articleID`='{$_POST["st_con_id"]}';
		";
		mysql_query($query);
	}
	$sites="";

	
	if(is_numeric($_GET['type']) and $_SESSION["type"] != $_GET['type'])
	{
		$_POST["cat_id"]="";
	}
	
	$_SESSION["cat_id"]=isset($_POST["cat_id"])?$_POST["cat_id"]:$_SESSION["cat_id"];
	$cat_id2=is_numeric($_SESSION["cat_id"])?$_SESSION["cat_id"]:"";		
	$sel_cat=is_numeric($cat_id2)?"AND `content_articlescategories`.`categoryID`='{$cat_id2}' ":"";
	$sel_type = "";
	if(is_numeric($_GET['type']))
	{
		$sel_type = "AND `content_categories`.`type_id`='{$_GET['type']}'";
		$_SESSION['type']=$_GET['type'];
	}
	
	/*$sql="
	SELECT `articleID`,`parent`,`name`,`add_date`,`order`,`visible`,`image`
	FROM `content_articles` 
	WHERE `content_articles`.`lng_id`='{$sel_lang}'
	ORDER BY `order` DESC;";
	$res=mysql_query($sql);
	while($row=mysql_fetch_assoc($res))
	{
		$pages_name[$row["articleID"]]=$row["name"];
		$pages_parent[$row["articleID"]]=$row["parent"];
		$pages_add_date[$row["articleID"]]=$row["add_date"];
		$pages_order[$row["articleID"]]=$row["order"];
		$pages_visible[$row["articleID"]]=$row["visible"];
		$pages_img[$row["articleID"]]=$row["image"];
	}
	$glb_pages["pages_name"]=$pages_name;	
	$glb_pages["pages_parent"]=$pages_parent;	
	$glb_pages["pages_add_date"]=$pages_add_date;	
	$glb_pages["pages_order"]=$pages_order;
	$glb_pages["pages_visible"]=$pages_visible;
	$glb_pages["pages_img"]=$pages_img;
	$par=is_numeric($_GET["p_id"])?$_GET["p_id"]:0;
	$glb["templates"]->set_tpl('{$p_id}',$par);
	$all_lines=get_pages_tree($par,0,$glb_pages);*/

	$its_name="Все страницы";
	$additions_buttons=get_new_buttons("/?admin=article_edit&articleID={$new_id}","Добавить");
	$glb["templates"]->set_tpl('{$all_lines}',$all_lines);
	$center=$glb["templates"]->get_tpl("modules/content/templates/admin.articles.all");
}