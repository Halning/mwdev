<?php

use Modules\Blocks;

if ($_SESSION['status']=="admin")
{


function ggg($id)
{
	$sql="	
	SELECT * FROM `shop_filters-values`
	INNER JOIN `shop_filters-lists` ON `id`=`ticket_value`
	WHERE `ticket_id`='{$id}';";	
	$res=mysql_query($sql);	
	while($row=mysql_fetch_assoc($res))	
	{	
		$lines.="- ".$row["list_name"]."<br>";
	}	
	return $lines;
}

	function generateSelectBlock($id, $block_id, $blocks) {
		$select = "<select style='width: 115px' data-id='$id' onchange='editBlock(this)'><option value='0'>--Выберите блок -- </option>";

		foreach ($blocks as $block) {
			$selected = $block->id == $block_id ? ' selected' : '';
			$select .= "<option$selected value='{$block->id}'>{$block->name}</option>";
		}

		return $select.'</$select>';
	}


//----------------------------------------------------
$sql="	
SELECT  from_url,`commodity_ID`, Count(from_url) as Count FROM `shop_commodity`
WHERE from_url<>''
group by from_url 
 HAVING COUNT(*)>1
 ;";	
$res=mysql_query($sql);	
while($row=mysql_fetch_assoc($res))	
{	
	$dub.=",".$row["commodity_ID"];
}	if($dub!="")
	$dub="<a href='?admin=delete_commodity&commodityID=0{$dub}'>Удалить дубликаты</a><br><br>";


$sql="	
SELECT * FROM `shop_commodity`
WHERE from_url=''
 ;";	
$res=mysql_query($sql);	
while($row=mysql_fetch_assoc($res))	
{	
	$dub2.=",".$row["commodity_ID"];
}	
	//if($dub2!="")
	//$dub.="<a href='?admin=delete_commodity&commodityID=0{$dub2}'>Удалить без источника</a><br><br>";

	$result = mysql_query("SHOW TABLE STATUS LIKE 'shop_commodity'");
	$row = mysql_fetch_array($result);
	$new_id = $row['Auto_increment'];
	$_SESSION["category"]=is_numeric($_POST["category"])?$_POST["category"]:$_SESSION["category"];
	$_SESSION["category"]=is_numeric($_GET["category"])?$_GET["category"]:$_SESSION["category"];
	if (isset($_POST['category'])) {
        unset($_SESSION["not_publish"]);
    }
    if (isset($_POST['not_publish'])) {
        $_SESSION["not_publish"] = $_POST["not_publish"];
    }

	if(is_numeric($_POST["category"]))
	{
		unset($_SESSION["filter22"]);
		
	}
	if($_GET["unset"]==1)
	{
		unset($_SESSION["filter22"]);
		unset($_SESSION["not_publish"]);
		unset($_SESSION["category"]);
	}
	
	$sql="
	SELECT * FROM `shop_categories` ORDER BY `categories_of_commodities_order`;";
	$res=mysql_query($sql);
	while($row=mysql_fetch_assoc($res))
	{
		$parents[$row["categories_of_commodities_ID"]]=$row["categories_of_commodities_parrent"];
	}
	$glb["cat_parrents"]=$parents;
	
	get_shop_categories_lines($glb["cat_parrents"],$_SESSION["category"],$cat_chl_name,$cat_chl_alias,$bstep);
	$filters_panel=get_filters_panel($glb["cat_parrents"],$_SESSION["category"],$cat_url);
	
	$show_admin_cat=is_numeric($_SESSION["category"])?$_SESSION["category"]:0;
	if(is_numeric($_POST["stat_item_id"]))
	{
		$item_id=$_POST["stat_item_id"];
		$item_val=$_POST["item_val"];		
		$query = "
		UPDATE `shop_commodity` 
		SET `commodity_hit`='{$item_val}'
		WHERE `commodity_ID`='{$item_id}';
		";
		mysql_query($query);
	}
	
	if(is_numeric($_POST["vis_item_id"]))
	{
		$item_id=$_POST["vis_item_id"];
		$item_val=$_POST["item_val"];		
		$query = "
		UPDATE `shop_commodity` 
		SET `commodity_visible`='{$item_val}'
		WHERE `commodity_ID`='{$item_id}';
		";
		mysql_query($query);
	}
	if(is_numeric($_POST["hit_item_id"]))
	{
		$item_id=$_POST["hit_item_id"];
		$item_val=$_POST["item_val"];		
		$query = "
		UPDATE `shop_commodity` 
		SET `commodity_hit`='{$item_val}'
		WHERE `commodity_ID`='{$item_id}';
		";
		mysql_query($query);
	}
	
	if(is_numeric($_POST["new_item_id"]))
	{
		$item_id=$_POST["new_item_id"];
		$item_val=$_POST["item_val"];		
		$query = "
		UPDATE `shop_commodity` 
		SET `commodity_new`='{$item_val}'
		WHERE `commodity_ID`='{$item_id}';
		";
		mysql_query($query);
	}
	
	if(is_numeric($_POST["action_item_id"]))
	{
		$item_id=$_POST["action_item_id"];
		$item_val=$_POST["item_val"];		
		$query = "
		UPDATE `shop_commodity` 
		SET `commodity_action`='{$item_val}'
		WHERE `commodity_ID`='{$item_id}';
		";
		mysql_query($query);
	}

	//Позиции на сайте, в зависимости от категории
	if(isset($_POST["move"]))
	{
		$item_id=$_POST["item_id"];
		$q_end=$show_admin_cat>0?" INNER JOIN `shop_commodities-categories` ON `shop_commodities-categories`.`commodityID`=`shop_commodity`.`commodity_ID` WHERE `categoryID`='{$show_admin_cat}' ":"WHERE 1=1 ";
		$not_publish = is_numeric($_SESSION["not_publish"]) ? $_SESSION["not_publish"] : 0 ;
        if ($show_admin_cat>0 && $not_publish > 0) {
            $q_end.="AND `commodity_visible`='0' ";
        }
		$sql="SELECT `commodity_order`,`commodity_ID` FROM `shop_commodity` 
		{$q_end}
		AND  `commodity_ID`='{$item_id}';";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$ccorder=$row["commodity_order"];
			
		}
		$sing=$_POST["move"]=="up"?"<":">";
		$sing2=$_POST["move"]=="up"?"DESC":"";
		$sql="SELECT `commodity_order`,`commodity_ID` FROM `shop_commodity` 
		{$q_end}
		AND `commodity_order`{$sing}'{$ccorder}' 
		ORDER BY `commodity_order` {$sing2}
		LIMIT 0,1;";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$order=$row["commodity_order"];
			$articleID=$row["commodity_ID"];
			
			$sql="UPDATE `shop_commodity` 
			SET `commodity_order`='{$order}'
			WHERE `commodity_ID`='{$item_id}';";
			mysql_query($sql);
			
			$sql="UPDATE `shop_commodity` 
			SET `commodity_order`='{$ccorder}'
			WHERE `commodity_ID`='{$articleID}';";
			mysql_query($sql);
			
		}else
		{
			$sing=$_POST["move"]=="up"?"-":"+";
			$query = "
			UPDATE `shop_commodity` 
			SET `commodity_order`=(`commodity_order`{$sing}1)
			WHERE `commodity_ID`='{$item_id}';
			";
			mysql_query($query);
		}
	}


		if($show_admin_cat>0)
		{
			$q_end=" INNER JOIN `shop_commodities-categories` ON `shop_commodities-categories`.`commodityID`=`shop_commodity`.`commodity_ID`
			WHERE `categoryID`='{$show_admin_cat}' ";
		}
	

		$sql="
		SELECT * FROM `shop_commodities-categories` 
		WHERE `commodityID`='{$show_admin_cat}';";
		$res=mysql_query($sql);
		while($row=mysql_fetch_assoc($res))
		{
			$categoriesss[$row["categoryID"]]=1;
		}

		$sql="
		SELECT * FROM `shop_categories` ORDER BY `categories_of_commodities_order`;";
		$res=mysql_query($sql);
		while($row=mysql_fetch_assoc($res))
		{
			$parents[$row["categories_of_commodities_ID"]]=$row["categories_of_commodities_parrent"];
			$orders[$row["categories_of_commodities_ID"]]=$row["categories_of_commodities_order"];
			$orders2[$row["categories_of_commodities_ID"]]=$row["categories_of_commodities_order"];
			$upprices[$row["categories_of_commodities_ID"]]=$row["upprice"];
			$description[$row["categories_of_commodities_ID"]]=$row["cat_name"];
			$aliases[$row["categories_of_commodities_ID"]]=$row["alias"];
		}

		unset($orders2[0]);
		function ss_nap($cat_id,$st)
		{
			global $theme_name,$sys_lng,$description,$aliases,$parents,$orders,$orders2,$upprices,$categoriesss,$show_admin_cat;
			$st++;
			if(count($orders2))
			foreach($orders2 as $keys=>$values)
			{
				if($parents[$keys]==$cat_id)
				{
					$r_category_id=$keys;
					$r_category_name=$description[$keys];
					$alias=$aliases[$keys];
					$order=$orders[$keys];
					$upprice=$upprices[$keys];
					$st_t="";
					for($j=2;$j<=$st;$j++)
					{
						$st_t.="&nbsp;&nbsp;&nbsp;&nbsp;";
					}
					$r_category_name=$st_t.$r_category_name;
					$selected=$r_category_id==$show_admin_cat?"selected":"";
					$r_category_name=strip_tags($r_category_name);
					$all_lines.="<option value='{$r_category_id}' {$selected}>{$r_category_name}</option>";
					$all_lines.=ss_nap($keys,$st);
				}
			}

			return $all_lines;
		}

		


		//Фильтр по коду
		$code_search=$_POST["code_search"];
		$code_search=$_GET["code_search"]!=""?$_GET["code_search"]:$code_search;
		if($code_search!="")
		{
			$q_end.=$q_end!=""?" AND `cod` LIKE '%{$code_search}%'":" WHERE `cod` LIKE '%{$code_search}%'";
		}
		//Фильтр по дополнительному параметру
		$stype=$_GET["stype"];
		if($stype!="")
		{
			$q_end.=$q_end!=""?" AND `commodity_{$stype}`='1'":" WHERE `commodity_{$stype}`='1' ";
		}	

		//Фильтр по назначению
		$_SESSION["vtype_id"]=is_numeric($_POST["vtype_id"])?$_POST["vtype_id"]:$_SESSION["vtype_id"];
		$vtype_id=is_numeric($_SESSION["vtype_id"])?$_SESSION["vtype_id"]:0; 
	
		//Фильтр по назначению
		$ne_opub=999999999==$_SESSION["category"]?"selected":"";
		$ne_opub2=11111==$_SESSION["category"]?"selected":"";
		$ne_opub3=22222==$_SESSION["category"]?"selected":"";
	
		if(isset($_GET['noteg'])){
			$teg="Все тегов";
		}else{
			$teg="Без тегов";
		}		
		
		if($_SESSION["category"]==11111){
			$slidetHIT="<div class='slider'>Слайдер товари. Лимит: 
			<input type='number' class='in_slider' name='quantity' min='1' value='{$limit}'></div>";
		}		
		
		
		$comm_category="<option value='0'>Все</option><option value='999999999' {$ne_opub}>Не опубликованные</option>
		<option value='11111' {$ne_opub2}>Слайдер ХП</option>
		<option value='22222' {$ne_opub3}>Новые товары(верхние)</option>";
		$comm_category.=ss_nap(0,0);
		$all_params="<form action='/?admin=all_commodities' method='POST'>Категория:<br><select name='category' onChange  = 'this.form.submit()'>{$comm_category}</select> <!--Не опубликованные <input type='checkbox' name='not_publish' value=1>--></form>

		
		<div style='overflow:hidden;'><form action='{$reauest_url}' method='POST'>{$filters_panel}</form></div>
		<br />
		<form action='/?admin=all_commodities' method='POST'>
		<table>
			<tr>
				<td>
					<input type='text' name='code_search' value='{$code_search}' />
				</td>
				<td>
					<input type='submit' value='Фильтр по коду' />
				</td>
			</tr>
		</table>
		</form>
		
		<b onclick='data_add()' class='data_add' style='cursor:pointer' rel=0 ><u>По дате добавления</u></b> 
		<b onclick='no_teg()' class='no_teg' style='cursor:pointer' rel=0 ><u>{$teg}</u></b> 
		{$slidetHIT}
		<span class='cl_delll'>Удалить <img src='/templates/admin/img/btnbar_del.png'></span>
		<span class='cl_edittt'>Редактировать <img src='/templates/admin/img/btnbar_edit.png'></span>
		<br><br>{$dub}
		";


	
	

//======================
	if(isset($_POST["com_count"]))
	{
		$com_count=$_POST["com_count"];
		$_SESSION['com_count']=$com_count;
	}
	$com_count=(isset($_SESSION['com_count']))?$_SESSION['com_count']:"100";
	for($i=25;$i<=100;$i+=25)
	{
		$com_count_opt.=$i==$com_count?"<option value='$i' selected='selected'>$i</option>":"<option value='$i'>$i</option>";
	}
	for($i=150;$i<=500;$i+=50)
	{
		$com_count_opt.=$i==$com_count?"<option value='$i' selected='selected'>$i</option>":"<option value='$i'>$i</option>";
	}
//$com_count_opt.=$com_count==1000000?"<option selected value='1000000'>Все</option>":"<option value='1000000'>Все</option>";
	$all_params.="<form action='{$request_url}' method='POST'>Выводить по:<br><select name='com_count' onChange  = 'this.form.submit()'>{$com_count_opt}</select></form>";
	
	if(count($_SESSION["filter22"]))
	{
		
		foreach($_SESSION["filter22"] as $key=>$value)
		{
			$ticheck[$key]=$value;
			$llld=$value!="-"?" (`ticket_filterid`='{$key}' AND `ticket_value`='{$value}')":"";
			if($value!="-")
			{
				$shlf2=$shlf!=""?" 1=0 {$shlf}":"1=1";
				$shlf="";
				$sql="
				SELECT * FROM `shop_filters-values` 
				WHERE  {$llld} AND ({$shlf2})
				;";
				$res=mysql_query($sql);
				$llld2=" 1=0 ";
				while($row=mysql_fetch_assoc($res))
				{
					$llld2.=" OR `commodity_ID`='{$row[ticket_id]}' ";
					$shlf.=" OR `ticket_id`='{$row[ticket_id]}' ";
				}
			}
		}
		
		if($llld2!="")
		{
		$q_end.=$q_end!=""?" AND ({$llld2}) ":" WHERE {$llld2}";
		}
	}
	//Отображать неопубликованные
	$q_end=$_SESSION["category"]==999999999?"WHERE `commodity_visible`='0' ":$q_end;
	if($_SESSION["not_publish"]==1) {	
	$q_end.="AND `commodity_visible`='0' ";
	}	
	
	
//	echo "as: ".$q_end."<br>";
	$query = "
SELECT COUNT(*) AS `counts` FROM `shop_commodity` 
{$q_end} 
";
	$get_p=is_numeric($_GET["p"])?$_GET["p"]:1;
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0)
	{
		$row = mysql_fetch_object($result);
		$counts=$row->counts;
		$page_count=ceil($counts/$com_count);
		
		if(is_numeric($get_p))
		{

			$page_num=$get_p;
			$page_num2=$get_p;
			$page_num=$page_num*$com_count-$com_count;
			$sel_page="`commodity_order` LIMIT {$page_num}, $com_count";
			$new_com="`commodity_add_date` DESC LIMIT {$page_num}, $com_count";
		}else
		if($get_p=="last")
		{
			$page_num=$page_count;
			$page_num2=$get_p;
			$page_num=$page_num*$com_count-$com_count;
			$sel_page="`commodity_order` LIMIT {$page_num}, $com_count";
			$new_com="`commodity_add_date` DESC LIMIT {$page_num}, $com_count";
		}else
		{
			$sel_page="`commodity_order` LIMIT 0, $com_count";
			$new_com="`commodity_add_date` DESC LIMIT 0, $com_count";
			$page_num2=isset($_GET["p"])?"":1;
		}
		$pages_links="";
		for($i=1;$i<=$page_count;$i++)
		{
			$sts1=$i*$com_count-($com_count-1);	
			$sts2=$i*$com_count;
			if($page_num2==$i)
			{
				$pages_links.="<u><b>{$sts1}-{$sts2}</b></u> &nbsp;";
			}else
			{
				if($i==1&&$page_count>5)
				{
					$pages_links.="<a href='/?admin=all_commodities&p={$i}'>В начало</a>&nbsp; ";
				}elseif($i==$page_count&&$page_count>5)
				{
					$pages_links.="<a href='/?admin=all_commodities&p={$i}'>В конец</a>&nbsp; ";
				}elseif(($page_count>5)&&(($page_num2-5<$i)&&($page_num2+5)>$i))
				{
					
					$pages_links.="<a href='/?admin=all_commodities&p={$i}'>{$sts1}-{$sts2}</a>&nbsp; ";
				}elseif($page_count>5)
				{
					
				}else
				{
					$pages_links.="<a href='/?admin=all_commodities&p={$i}'>{$sts1}-{$sts2}</a>&nbsp; ";
				}
			}
		}
		
	}

$q_end=$_SESSION["category"]==11111?"WHERE `commodity_hit`='1' ":$q_end;

//Пошукати новий товарів і без тегів
if($_SESSION["category"]==22222 || isset($_GET['noteg'])){
	$sel_page=$new_com;
	//$q_end='';
	
	$in=array();
	$query = mysql_query("SELECT * FROM `shop_commodity` ORDER BY `commodity_add_date` DESC LIMIT $com_count;");
	while($n=mysql_fetch_assoc($query)){
		$f=ggg($n['commodity_ID']);
		if($f==false){
			$in[$ii]=$n['commodity_ID'];
			$ii++;
		}
	}
	$arrIN=implode("','",$in);
	
	$mpos=strpos($q_end,"`categoryID`='22222'");
	$mpos2=strpos($q_end,"WHERE");
	
	if($mpos!==false){
		$q_end="WHERE `commodity_ID` IN ('{$arrIN}')";
	}else{
		$q_end.="AND `commodity_ID` IN ('{$arrIN}')";
	}
	if($mpos2===false){
		$q_end="WHERE `commodity_ID` IN ('{$arrIN}')";	
	}	
}



if(isset($_GET['sort'])){
	$sel_page=$new_com;
}

//echo $q_end;
//AND `commodity_ID` IN ('11775','11781')

	$blocks = new Blocks();
	$blocks->getBlocks();

//======================
	$query = "SELECT * FROM `shop_commodity`
              LEFT JOIN shop_blocks_products sbp
                ON sbp.com_id = shop_commodity.commodity_ID
              {$q_end}
              ORDER BY {$sel_page}";
//echo $query;
	$ff='';
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		$categories="";
		
		for($i=1;$i<=mysql_num_rows($result);$i++)
		{
			$row = mysql_fetch_object($result);
			$r_commodity_name=$row->com_name;
			$r_commodity_id=$row->commodity_ID;
			$r_commodity_cod=$row->cod;
			$r_commodity_category=$row->categoryID;
			
			$star=is_numeric($addimgs[$r_commodity_id])?$addimgs[$r_commodity_id]:0;
			$commodity_bigphoto=$row->commodity_bigphoto;
			$imageSrc = PHOTO_DOMAIN."{$r_commodity_id}/s_title.jpg";
			$photo="<img src='$imageSrc' style='max-width:80px;max-height:80px;'>";
			$f=ggg($r_commodity_id);
			
			//$commodity_hit=$row->commodity_hit;
			//$com_stat=$commodity_status==1?"2":"1";
			//$status_check=$commodity_hit==1?"checked":"";
			//$action_check=$row->commodity_action==1?"checked":"";
			//$new_check=$row->commodity_new==1?"checked":"";
			$commodity_up_price2=$row->commodity_up_price2;
			$commodity_visible=$row->commodity_visible;
			$commodity_hit=$row->commodity_hit;
			$commodity_action=$row->commodity_action;
			$commodity_new=$row->commodity_new;
			
			$com_vis=$commodity_visible==1?"0":"1";
			$com_hit=$commodity_hit==1?"0":"1";
			$com_act=$commodity_action==1?"0":"1";
			$com_new=$commodity_new==1?"0":"1";

			$vis_check=$commodity_visible==1?"checked":"";
			$hit_check=$commodity_hit==1?"checked":"";
			$act_check=$commodity_action==1?"checked":"";
			$new_check=$commodity_new==1?"checked":"";
			$order=$row->commodity_order ;
			$r_commodity_price=round($row->commodity_price,2);
			$r_commodity_price2=round($row->commodity_price2,2);
		   $r_commodity_old_price=$row->commodity_old_price;
			
			$c_shorttext=$row->com_desc;
			$c_fulltext=$row->com_fulldesc;
			$r_commodity_alias=$row->alias;
			$url=$r_commodity_alias != "" ? "/pr{$r_commodity_id}-{$r_commodity_alias}/":"/p{$r_commodity_id}/";
			$selectBlock = generateSelectBlock($r_commodity_id, $row->block_id, $blocks->blocks);
			require("modules/commodities/templates/admin.com.all.line.php");
			$all_lines.=$all_line;
		}
		
	}


	$all_params.="
<script type='text/javascript' src='/templates/admin/js/all_coms.js'></script>
<form method='POST' action='{$request_url}' name='check_form'>
<input name='stat_item_id' type='hidden' value='0' id='stat_item_id'>
<input name='item_val' type='hidden' value='0' id='item_val'>
</form>
<form method='POST' action='{$request_url}' name='check_form2'>
<input name='vis_item_id' type='hidden' value='0' id='vis_item_id'>
<input name='item_val' type='hidden' value='0' id='viz_item_val'>
</form>
<form method='POST' action='{$request_url}' name='check_form3'>
<input name='new_item_id' type='hidden' value='0' id='new_item_id'>
<input name='item_val' type='hidden' value='0' id='new_item_val'>
</form>
<form method='POST' action='{$request_url}' name='check_form4'>
<input name='action_item_id' type='hidden' value='0' id='action_item_id'>
<input name='item_val' type='hidden' value='0' id='action_item_val'>
</form>
<script>
	function new_check(i_id,i_val)
	{
		document.getElementById('stat_item_id').value=i_id;
		document.getElementById('item_val').value=i_val;
		document.forms.check_form.submit();
	}
	function new_check2(i_id,i_val)
	{
		document.getElementById('vis_item_id').value=i_id;
		document.getElementById('viz_item_val').value=i_val;
		document.forms.check_form2.submit();
	}
	function new_check3(i_id,i_val)
	{
		document.getElementById('new_item_id').value=i_id;
		document.getElementById('new_item_val').value=i_val;
		document.forms.check_form3.submit();
	}
	function new_check4(i_id,i_val)
	{
		document.getElementById('action_item_id').value=i_id;
		document.getElementById('action_item_val').value=i_val;
		document.forms.check_form4.submit();
	}
	function data_add(){
		$(document).ready(function(){
			var url=window.location.href;
			var u=url.indexOf('sort=true');
			if(u==-1){
				$(location).attr('href',url+'&sort=true');
			}else{
				url=url.replace('&sort=true','');
				$(location).attr('href',url);
			}
		});	
	}
	function no_teg(){
		$(document).ready(function(){
			var url=window.location.href;
			var u=url.indexOf('noteg=true');
			if(u==-1){
				$(location).attr('href',url+'&noteg=true');
			}else{
				url=url.replace('&noteg=true','');
				$(location).attr('href',url);
			}
		});	
	}
	function editBlock(el) {
	    $.post('/?admin=products-blocks', {
	        action: 'edit-block',
	        id: $(el).find(':selected').val(),
	        com_id: $(el).data('id')
    	});
	}
</script>

";
	$global_get_cat = (is_numeric($show_admin_cat) and $show_admin_cat>0)?"&cat_id=".$show_admin_cat:'';
	$its_name="Все товары";
	$additions_buttons=get_new_buttons("/?admin=edit_commodity&commodityID={$new_id}","Добавить");
	require("modules/commodities/templates/admin.com.all.head.php"); 
	require_once("templates/$theme_name/admin.all.php"); 
}?>