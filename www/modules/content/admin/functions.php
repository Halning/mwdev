<?php

function update_content_images($item_id){
	if(is_array($_POST['photos'])){
		$delete_photo_ids=array();
		foreach($_POST['photos'] as $photo){
			if(is_numeric($photo['id'])){
				$photo_id=$photo['id'];
				$photo_delete=$photo['delete']==1?1:0;
				$photo_order=$photo['order'];
				$photo_desc=$photo['desc'];
				$photo_name=$photo['name'];
				if($photo_delete){
					$delete_photo_ids[]=intval($photo['id']);
				}else{
					$sql="UPDATE `content_images` SET 	`img_name`='{$photo_name}',
														`order`='{$photo_order}',
														`img_desc`='{$photo_desc}' 
												WHERE `img_id`='{$photo_id}'";
					mysql_query($sql) or die(mysql_error());
				}
				
			}
			
		}
		
		if(count($delete_photo_ids)>0){
			$delete_sql_end=implode(',',$delete_photo_ids);
		}
		$today=date("Y-m-d");
		if(!empty($delete_sql_end)){
			$delete_sql_end=" `img_id` IN ({$delete_sql_end}) AND `img_artid` IN('0','{$item_id}')";
			$sql="SELECT * FROM `content_images` WHERE {$delete_sql_end}";
			$result=mysql_query($sql);
			while($row=mysql_fetch_assoc($result)){
				delete_img('articles',$item_id,$row['img_id'].'.jpg');
				delete_img('articles',$item_id,'s_'.$row['img_id'].'.jpg');
			}
			$sql="DELETE FROM `content_images` WHERE {$delete_sql_end}";
			mysql_query($sql) or die('Ошибка запроса!');
		}
	}
	return true;
}
function get_articles_images($item_id){
	$photo_items='';
	$sql="SELECT * FROM `content_images` WHERE `img_artid`='{$item_id}' ORDER BY `order`";
	$result=mysql_query($sql);
	while($row=mysql_fetch_assoc($result)){
		$photo_id=$row['img_id'];
		$photo_name=$row['img_name'];
		$photo_desc=$row['img_desc'];
		$photo_src="/images/articles/{$item_id}/".$photo_id.'.jpg';
		$small_photo_src="/images/articles/{$item_id}/s_".$photo_id.'.jpg';
		require("modules/content/templates/photo_item.php");
		$photo_items.=$photo_item;
	}
	return $photo_items;
}

function get_contents_categories(array $active_com_cats,$articleID=0)
{
	global $glb,$sys_lng,$con_desc,$aliases,$parents,$orders,$orders2,$upprices;
		$parents[-1]=0;
		$orders[-1]=-1;
		$orders2[-1]=-1;
		$con_desc[-1]="[Нет раздела]";
		$aliases[-1]="";	
	$sql="
	SELECT * FROM `content_articles` 
	WHERE `articleID`<>'{$articleID}'
	ORDER BY `order` DESC;";
	$res=mysql_query($sql) or die(mysql_error());
	while($row=mysql_fetch_assoc($res))
	{
		$parents[$row["articleID"]]=$row["parent"];
		$orders[$row["articleID"]]=$row["order"];
		$orders2[$row["articleID"]]=$row["order"];
		$con_desc[$row["articleID"]]=$row["name"];
		$aliases[$row["articleID"]]=$row["alias"];
		
		
	}
	
	$cat_tree_array=array(
		'current'=>'NULL',
		'items'=>array_content_category_napalm(0,0)
	);
	$result=array(
		'json_tree'=>json_encode($cat_tree_array),
		'lines'=>get_active_con_cats($active_com_cats)
	);
	
	return $result;
}


function get_active_con_cats($active_cats){
	global $sys_lng,$con_desc,$aliases,$parents,$orders,$orders2,$upprices;
	$i=0;
	if(!count($active_cats))
		$active_cats[0]=1;
	if(count($active_cats))
	foreach($active_cats as $cat_id=>$active){
		if($active!==1)
			continue;
		$i++;
		
		$name=$con_desc[$cat_id]!=""?$con_desc[$cat_id]:"нет описания";
		$name=$cat_id==0?'Выберите раздел':$name;
		$root_class=' inp-line';
		$tree='';
		$button_classes="remove-product-category remove-categ categ-navig png24";
		if($i==1){
			$root_class=" root-category";
			$button_classes="add-categ categ-navig png24 add-product-categeroy";
			$tree='<div class="filetree-block">
						<div class="tree"></div>
                    </div>';
		}
		require('modules/content/templates/admin.select_cat_line.php');
		$all_lines.=$line;
	}
	return $all_lines;
}
function array_content_category_napalm($cat_id,$st,$active_cats=array()){
	global $sys_lng,$con_desc,$aliases,$parents,$orders,$orders2,$upprices;
	$st++; 
	$result=array();
	foreach($orders2 as $keys=>$values)
	{
		if($parents[$keys]==$cat_id)
		{
			$r_category_id=$keys;
			$r_category_name=$con_desc[$keys]!=""?$con_desc[$keys]:"нет описания";
			$alias=$aliases[$keys];
			$order=$orders[$keys];
			$url=$alias!=""?"/c{$r_category_id}_{$alias}/":"/c{$r_category_id}/";
			$selected=$active_cats[$r_category_id]==1?' selected':'';
			$children=array_content_category_napalm($r_category_id,$st,$active_cats);
			$state=count($children[0])>0?'closed':'';
			$result[]=array(
				'attr'=> array(
					'id'=>"node_{$r_category_id}",
				),
				'data'=>$r_category_name,
				'state'=>$state,
				'children'=>$children,
			);
			
		}
	}
	
	return array($result);
}

function get_pages_tree($page_id,$ii,$glb_pages)
{
	global $request_url;
	if(count($glb_pages))
	foreach($glb_pages as $keys=>$values)
	{
		$$keys=$values;
	}
	$ii++;
	if(count($pages_parent))
	foreach($pages_parent as $keys=>$values)
	{
		if($values==$page_id)
		{
			$marginleft=$ii*20-20;
			$inner=get_pages_tree($keys,$ii,$glb_pages);
			$img=$inner!=""?"/templates/admin/images/folderopen.png":"/templates/admin/images/file.png";
			$img_rel=$inner!=""?"closed":"";
			$imgs=$pages_img[$keys]==1?"<a href='/?admin=article_edit&articleID={$keys}'><img src='/images/articles/{$keys}/s_title.jpg' style='height:40px;'></a>":"";
			$status=$pages_visible[$keys]==1?"<span class='cl_green' style='cursor:pointer;' onclick='chacge_status_con({$keys},0)'>Опубликовано</span>":"<span class='cl_black' style='cursor:pointer;'  onclick='chacge_status_con({$keys},1)'>Черновик</span>";
			$all_lines.="
			<tr class='cl_dir cl_dd_{$page_id}' rel='.cl_dd_{$keys}'>
				<td class='cl_first'>
					<img src='{$img}' class='cl_folder_img' style='margin-left:{$marginleft}px;' id='id_i{$keys}' rel='{$keys}' rel2='{$img_rel}'>
				</td>
				<td>
					{$imgs}
				</td>
				<td>
					<a href='#' class='cl_for_imit' rel='id_i{$keys}'>{$pages_name[$keys]}</a>
					<div class='hover-actions'>
						<a href='/?admin=article_edit&articleID={$keys}'>Редактировать</a>&nbsp;
						<a href='/p{$keys}/' target='_blank'>Просмотр</a>&nbsp;
						
						<a href='/?admin=delete_articles&article_id={$keys}'>Удалить</a>
					</div>
				</td>
				<td>
					<table class='cl_table_order'>
						<tr>
							<td>
								<form method='POST' action='{$request_url}'>
									<input type='image' src='/templates/admin/img/down.gif' name='submit'/>
									<input type='hidden' name='move' value='down' />
									<input type='hidden' name='item_id' value='{$keys}' />
								</form>
							</td>
							<td>
								{$pages_order[$keys]}
							</td>
							<td>
								<form method='POST' action='{$request_url}'>
									<input type='image' src='/templates/admin/img/up.gif' name='submit' />
									<input type='hidden' name='move' value='up' />
									<input type='hidden' name='item_id' value='{$keys}' />
								</form>
							</td>
						</tr>
					</table>
				</td>
				<td align='center'>
					{$pages_add_date[$keys]}<br />
					{$status}
				</td>
			</tr>".$inner;	
			
		}
	}
	//return $page_id==0||$all_lines==""?$all_lines:"<ul>{$all_lines}</ul>";
	return $all_lines;
}

function get_field_values($article_id, $field_id, $kind){
	$v_arr = array(1 => "value1", 2 => "value2", 3 => "value3", 4 => "value4");
	if(isset($v_arr[$kind])){
		$sel_value = $v_arr[$kind];
		$sql = "SELECT {$sel_value} FROM `content_fields_values` WHERE `article_id`='{$article_id}' AND `field_id`='{$field_id}'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			return $row[$sel_value];
		}
		else
			return '';
	}
		else
			return '';
}
function insert_fields_values($articleID){
	if(is_array($_POST['field'])){
		foreach ($_POST['field'] as $key1=>$content1){
			$valueN = is_numeric($key2);
			$field_id = $key1;
			foreach($content1 as $key2=>$content2){
				if($key2 == 0)
					continue;
				$valueN = is_numeric($key2)?"value".$key2:"value1";
				$sql_delete = "DELETE FROM `content_fields_values` 
					WHERE `field_id`='{$field_id}' 
					AND `article_id`='{$articleID}';
					";
				$sql_insert = "INSERT INTO `content_fields_values` SET 
					`field_id`='{$field_id}',
					`article_id`='{$articleID}',
					`{$valueN}`='{$content2}';
					";
				mysql_query($sql_delete) or die ("ошибка запроса ($sql_delete)...".mysql_error());
				mysql_query($sql_insert) or die ("ошибка запроса ($sql_insert)...".mysql_error());
			}
		
		}
		
		return true;
	}
	else
		return false;
}
function get_fields_list($articleID = "", $type_id=""){
global $theme_name;
	$result_str = "";
	//if(empty($articleID) and empty($type_id))
		//return '';
	
	
		
		$sql2 = "
				SELECT `fileld_id`, 
				`field_name`, 
				`field_kind` 
				FROM `content_types_fields` 
				WHERE `field_typeid`='{$type_id}';
				";
		$result2 = mysql_query($sql2);	
		if(mysql_num_rows($result2) > 0){
			
			while($row2 = mysql_fetch_assoc($result2)){
				$value = !empty($articleID)?get_field_values($articleID, $row2['fileld_id'], $row2['field_kind']):'';
				switch($row2['field_kind']){
					case 1: $result_str .= "
						{$row2['field_name']}:<br />
						<input value='{$value}' name='field[{$row2['fileld_id']}][{$row2['field_kind']}]'
						type='text'><br /><br />";break;
					case 2: $result_str .= "
						{$row2['field_name']}:
						<br />
						<textarea name='field[{$row2['fileld_id']}][{$row2['field_kind']}]' id='field{$row2['fileld_id']}{$row2['field_kind']}'>{$value}</textarea>
		<script type='text/javascript'>
		jQuery(document).ready(function() {
		CKEDITOR.replace('field{$row2['fileld_id']}{$row2['field_kind']}', {  height : '250',
       
            language : 'ru' });
		});
		</script>
		
						
						<br /><br />";break;
					case 3: $result_str .= "
						{$row2['field_name']}:<br />
						<input value='{$value}' name='field[{$row2['fileld_id']}][{$row2['field_kind']}]'
						type='text'><br /><br />";break;
					case 4: $result_str .= "
						{$row2['field_name']}:<br />
						<input value='{$value}' type='text' name='field[{$row2['fileld_id']}][{$row2['field_kind']}]' id='f_date_c{$row2['fileld_id']}' readonly='1'/>
						<img src='/templates/admin/img/cal.gif' width='16' border='0' height='16' id='f_trigger_c{$row2['fileld_id']}' onmouseover='this.style.background='red';' onmouseout='this.style.background=''' />

						<script type='text/javascript'>

							Calendar.setup({

									inputField     :    'f_date_c{$row2['fileld_id']}',     // id of the input field

									ifFormat       :    '%Y-%m-%d',      // format of the input field

									button         :    'f_trigger_c{$row2['fileld_id']}',  // trigger for the calendar (button ID)

									align          :    'Tl',           // alignment (defaults to 'Bl')

									singleClick    :    true

						});

						</script><br /><br />";
						break;
				}
			}
		
		}
	
	
	return $result_str;
}

function sel_kind($name, $kind = ''){
	$types_arr = array('1' => 'Строка', '2' => 'Текст', '3' => 'Число', '4' => 'Дата'); //массив типов дополнительных полей
	$sel_kind ='<option selected disabled>Выберите тип поля</option>';
	if(count($types_arr))
	foreach($types_arr as $key=>$value){
		$selected=$kind == $key?"selected":"";
		$sel_kind .= "<option $selected value='$key'>$value</option>";
		}
	return "<select name='$name'>".$sel_kind."</select>";
}

function get_sites_list()
{
global $theme_name;
	$sql = "SELECT * FROM `content_types`;";
	$result = mysql_query($sql);
	$result_str = "";
	if(mysql_num_rows($result) > 0){
		while($row = mysql_fetch_assoc($result)){
			$result_str .= "<li><a href='{$_SEREVER['HTTP_HOST']}/?admin=all_articles&type={$row['type_id']}'><img src='/templates/admin/img/search.png' class='ico'>&nbsp;{$row['type_name']}</a></li>";
		}
	
	}
	return $result_str;

}

function napalm_type($type_id="")
{
global $theme_name;
global $sys_lng,$sel_lang;

			$query = "
SELECT * FROM `content_types`;
";

		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0) 
		{
			for($i=1;$i<=mysql_num_rows($result);$i++)
			{
				$selected = "";	
				$row = mysql_fetch_object($result);
				$type_category_name=$row->type_name;
				$type_category_id=$row->type_id;
				
				if(!empty($type_id) and $type_id==$type_category_id)
					$selected = "selected";
				$type_category=$type_category." <option value='{$type_category_id}' $selected>{$type_category_name}</option>";
				//=============
				
					
				
				//=============
			}
			
			//$e_visible="checked='checked'";
		}
		
	return $type_category;
}

function napalm_for_tree_cat($cat_id)

{

global $theme_name, $sys_lng, $sel_lang,$art_alias,$concat,$concat2,$art_name,$cat_name,$cat_parent;

$st++;
if(count($cat_parent))
foreach($cat_parent as $key2=>$value2)
{
	if($value2==$cat_id)
	{	
		if(count($concat))
		foreach($concat as $key=>$value)
		{
			if($value==$key2)
			{
				$result_lines.= "<li><span><a href='#'>{$art_name[$concat2[$key]]}</a>
				
				<div class='cl_butt1'><a href='http://tak-to.net/?admin=article_edit&articleID={$concat2[$key]}'><img src='/templates/admin/img/btnbar_edit.png'></a><a href='http://tak-to.net/?admin=delete_articles&article_id={$concat2[$key]}'><img src='/templates/admin/img/btnbar_del.png'></a></div>
				
				</span></li>";
				unset($cat_parent[$key2]);
			}
		}
		$inner_categories=napalm_for_tree_cat($key2);
		$result_lines=$result_lines!=""||$inner_categories!=""?"<ul>{$inner_categories} {$result_lines}</ul>":"<ul><li></li></ul>";
		$categories .= "<li><span>
		<a href='#'  class='cl_menu1' id='{$key2}'>{$cat_name[$key2]}</a><div class='cl_butt1'><a href='http://tak-to.net/?admin=add_articles&selected_cat=$key2'><img  src='/templates/admin/img/btnbar_add.png'  style='margin-left: 16px;'></a></div>
		</span>{$result_lines} </li>";
		$result_lines="";

		}


	}
	return $categories;

}

function get_tree_cat_and_articles()
{
	global $sel_lang,$sys_lng,$theme_name,$art_alias,$concat,$concat2,$art_name,$cat_name,$cat_parent;

	$sql="
SELECT * FROM `content_articlescategories`;";
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res))
{
	$concat[$row["articleID"]."-".$row["categoryID"]]=$row["categoryID"];
	$concat2[$row["articleID"]."-".$row["categoryID"]]=$row["articleID"];
}
	$sql="
SELECT `articleID`,`name`,`alias` FROM `content_articles`
WHERE `lng_id`='{$sys_lng}' ORDER BY `order` DESC;";
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res))
{
	$art_alias[$row["articleID"]]=$row["alias"];
	$art_name[$row["articleID"]]=$row["name"];
}

	$sql="
SELECT `id`, `parent`, `alias`, `name` FROM `content_categories`
WHERE `lng_id`='{$sys_lng}' ORDER BY `cat_order` ;";
//$res=mysql_query($sql);
//while($row=mysql_fetch_assoc($res))
{
	$cat_parent[$row["id"]]=$row["parent"];
	$cat_alias[$row["id"]]=$row["alias"];
	$cat_name[$row["id"]]=$row["name"];
}

//$result_lines = napalm_for_tree_cat(0);

	return "<h3>Дерево контента</h3><div id='multi-derevo'><ul>{$result_lines}</ul></div>";
}

function get_tree_content()
{
	global $theme_name,$gl_men_count;
	$gl_men_count++;
	$ret="
<div class=node id='node_{$gl_men_count}'>
	<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td>
				<a onClick='changeNode({$gl_men_count});'><img name='conerimage_{$gl_men_count}' src='/templates/admin/images/nav_cconer.gif' width=20 height=20 border=0></a>
			</td>
			<td colspan=99 style='padding-top:5px!important;'>
				<nobr>
					<img name='folderimage_{$gl_men_count}' src='/templates/admin/images/nav_cfolder.gif' width=20 height=20  align='absmiddle' border=0 >
					<a href='/?admin=all_articles_cats' onClick='openFolder({$gl_men_count});' ><span id='nname_{$gl_men_count}' onDblClick='changeNode({$gl_men_count});' >&nbsp;Головна сторінка [top]&nbsp;</span></a>
				</nobr>
			</td>
		</tr>
	</table>
</div>
";
	$gl_men_count++;
$ret.="
<div class=node id='node_{$gl_men_count}'>
	<table border='0' cellspacing='0' cellpadding='0'>
		<tr>
			<td>
				<img src='templates/admin/images/nav_line.gif' width='20' height='20' border='0' align='absmiddle' alt=''>
			</td>
			<td>
				<a onClick='changeNode({$gl_men_count});'>
					<img 'name=conerimage_{$gl_men_count}' src='templates/admin/images/nav_cconer.gif' width='20' height='20' border='0'>
				</a>
			</td>
			<td colspan='99'>
				<nobr>
					<img name='folderimage_{$gl_men_count}' src='templates/admin/images/nav_cfolder.gif' width='20' height='20'  align='absmiddle' border='0'>
					<a href='#{$gl_men_count}' onClick='openFolder({$gl_men_count});'><span id='nname_{$gl_men_count}' onDblClick='changeNode({$gl_men_count});'>&nbsp;Підприємство&nbsp;</span></a>
				</nobr>
			</td>
		</tr>
	</table>
</div>

";
	return $ret;
}

function napalm2($cat_id,$st)
{
global $p_category_id,$sel_lang,$menu_url,$sys_lng,$urlend,$gl_check_content;
$urlend=$urlend==""?"/":$urlend;
$st++;

			$query = "
SELECT * FROM `content_articles`
INNER JOIN `content_articlescategories` ON `content_articles`.`articleID`=`content_articlescategories`.`articleID`
WHERE  `categoryID`='{$cat_id}' AND `lng_id`='{$sel_lang}'
ORDER BY `content_articles`.`articleID`
";
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0)
		{
			for($i=1;$i<=mysql_num_rows($result);$i++)
			{
				$row = mysql_fetch_object($result);
				$r_category_name=substr($row->name, 0, 70);
				$r_category_id=$row->articleID;
				$cat_id=$r_category_id;
				$alias=$row->alias;
				$st_t="";
				for($j=1;$j<=$st;$j++)
				{
					$st_t.="——";
				}
				$value=$alias!=""?"/".$alias.$urlend:"/content/{$r_category_id}{$urlend}";
				$selected=$menu_url==$value?"selected":"";
				$gl_check_content=$menu_url==$value?"checked":$gl_check_content;
				$category_category.=" <option value='{$value}-///-{$r_category_name}' {$selected}>$st_t{$r_category_name} (статья)</option>";

			}
			$e_visible="checked='checked'";
		}



	return $category_category;
}

function napalm3($cat_id,$st)
{
global $p_category_id,$urlend,$sel_lang,$menu_url,$sys_lng,$gl_check_content;
$urlend=$urlend==""?"/":$urlend;
$st++;

			$query = "
SELECT * FROM `content_categories`
WHERE  `parent`='{$cat_id}' AND `lng_id`='{$sel_lang}'
ORDER BY `id`;
";
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0)
		{
			for($i=1;$i<=mysql_num_rows($result);$i++)
			{
				$row = mysql_fetch_object($result);
				$r_category_name=substr($row->name, 0, 70);
				$r_category_id=$row->id;
				$cat_id=$r_category_id;
				$alias=$row->alias;
				$st_t="";
				for($j=1;$j<=$st;$j++)
				{
					$st_t.="——";
				}
				$value=$alias!=""?"/".$alias.$urlend:"/content/category/{$r_category_id}/1{$urlend}";
				$selected=$menu_url==$value?"selected":"";
				$gl_check_content=$menu_url==$value?"checked":$gl_check_content;
				$category_category=$category_category." <option value='{$value}-///-{$r_category_name}' {$selected}>{$st_t}{$r_category_name} (категория)</option>";
				//=============

					$category_category.=napalm3($cat_id,$sys_lng,$st,$p_category_id);
					$category_category.=napalm2($cat_id,$st);
				//=============
			}
			$e_visible="checked='checked'";
		}



	return $category_category;
}


function func_module_menu_content($url_menu)
{
	global $gl_check_content,$gl_check,$opti;
	$options=napalm3(0,0);
	$gl_check=$gl_check==""?$gl_check_content:$gl_check;
	$opti=$gl_check_content!=""?"do_five(\"content_div\");":"";
	$category_category="

<input type='radio' name='radiob' class='radiob' onclick='do_five(\"content_div\");selChange(this.form.content_sel);' id='content_div' {$gl_check_content}>
<label for='content_div'>Категории и статьи\страницы</label><br />
<div class='radio_div' id='content_div'>

	<select name='content_sel' onChange='do_five(\"content_div\");selChange(this.form.content_sel);'>
		{$options}
	</select>

</div>

";
	return $category_category;
}

?>