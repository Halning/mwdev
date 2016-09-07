<?

use Modules\Blocks;

if ($_SESSION['status']=="admin"){
	$getId=$_GET["getId"];

	$center.="{$id}";

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
			$q_end.=$q_end!=""?" AND `cod` LIKE '%{$code_search}%'":" WHERE `cod` LIKE '%{$code_search}%' AND `block_id`='{$getId}'";
		}

		$all_params="
		
		<br />
		<form action='/?admin=commodity_blocks_edit&getId={$getId}' method='POST'>
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
		</form>";

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
		LEFT JOIN shop_blocks_products sbp
        ON sbp.com_id = shop_commodity.commodity_ID
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
			//die($_GET["sort"]);
			if(intval($_GET["sortBlock"])!=1){
				$sel_page="`commodity_order` ASC LIMIT {$page_num}, {$com_count}";
			}else{
				$sel_page="`commodity_order` DESC LIMIT {$page_num}, {$com_count}";
			}
			$new_com="`commodity_add_date` DESC LIMIT {$page_num}, {$com_count}";
		}else
		if($get_p=="last")
		{
			$page_num=$page_count;
			$page_num2=$get_p;
			$page_num=$page_num*$com_count-$com_count;
			$sel_page="`commodity_order` LIMIT {$page_num}, {$com_count}";
			$new_com="`commodity_add_date` DESC LIMIT {$page_num}, {$com_count}";
		}else
		{
			$sel_page="`commodity_order` LIMIT 0, {$com_count}";
			$new_com="`commodity_add_date` DESC LIMIT 0, {$com_count}";
			$page_num2=isset($_GET["p"])?"":1;
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

// echo $q_end."<br/>";
//AND `commodity_ID` IN ('11775','11781') `block_id`='{$getId}'
	
	if($q_end==''){
		$q_end="WHERE `block_id`='{$getId}'";
	}

	$blocks = new Blocks();
	$blocks->getBlocks();

//======================
	$query = "SELECT * FROM `shop_commodity`
              LEFT JOIN shop_blocks_products sbp
              ON sbp.com_id = shop_commodity.commodity_ID
              {$q_end}
              ORDER BY {$sel_page}";
 // echo $query;
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
			$photo=$commodity_bigphoto=="1"?"<img src='/images/commodities/{$r_commodity_id}/s_title.jpg' style='max-width:80px;max-height:80px;'>":"";
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
	function canelBlock(){
		window.location.href='/?admin=commodity_blocks';
	}
	function saveBlock(){
		if(confirm('Сохранить?')){
			ajaxBlock(1);
		}
	}
	function applyBlock(){
		if(confirm('Применить?')){
			ajaxBlock(0);
		}
	}

	function ajaxBlock(flag){
		var id=$('.bodyBlocks').attr('rel');
		var name=$('#name').val();
		var text=$('#short_text').val();

		var url=$('#alias').val();
		var seo_title=$('#seo_title').val();
		var seo_desc=$('#seo_desc').val();

		$.ajax({
			method:'get',
			url:'/modules/commodities/ajax/ajax_block.php',
			data:{id:id, name:name, text:text, url:url, seo_title:seo_title, seo_desc:seo_desc}
		})
		.done(function(data){
			//alert(data);
			
			if(flag==1){
				window.location.href='/?admin=commodity_blocks';
			}
		});

	}
	$(document).ready(function(){
		$('#uploadBlock').on('change',function(event){
			//alert('upload');

			var files=event.target.files;
			var data = new FormData();
			$.each(files,function(key, value){
				console.log(value);
				data.append(key, value);
			})
			$.ajax({
				xhr: function () {
					//console.log('Upload');
			        var xhr = new window.XMLHttpRequest();
			        xhr.upload.addEventListener('progress', function (evt) {
			            if (evt.lengthComputable) {
			                var percentComplete = evt.loaded / evt.total;
			               // console.log(percentComplete);
			                $('.see_progress').css({
			                    width: percentComplete * 100 + '%'
			                });
			                // $('.text_pro').text(Math.floor(percentComplete * 100) + '%');
			            }
			        }, false);
			        xhr.addEventListener('progress', function (evt) {
			            if (evt.lengthComputable) {
			                var percentComplete = evt.loaded / evt.total;
			              //  console.log(percentComplete);
			                $('.see_progress').css({
			                    width: percentComplete * 100 + '%'
			                });
			                // $('.text_pro').text(Math.floor(percentComplete * 100) + '%');
			            }
			        }, false);
			        return xhr;
			    },
			    beforeSend:function(){
			    	$('.see_progress').css({'background-color': '#337AB7'}); // #D9534F is red
			    },
			    complete: function(){
			    	// $('.text_pro').text(Complete!');
			    	$('.see_progress').css({'background-color': '#5CB85C'});
			    },
				type:'POST',
				url:'/modules/commodities/ajax/uploadBlock.php',
				cache:false,
				processData:false,
				contentType:false,
				data:data
			}).done(function(data){
				//alert(data);
				if(data==''){
					alert('Error file!');
				}else{	
					$('.cl_edit_phoo img').attr('src', '/templates/shop/image/block_share/'+data);
				}
			})
		});

		$('.but_sort').click(function(){
			var getUrl=window.location.search;
			// alert(getUrl);

			if(getUrl.indexOf('sortBlock=')!=-1){
				getUrl=getUrl.replace('&sortBlock=1','');
			}else{
				getUrl+='&sortBlock=1';
			}
			window.location.href='/'+getUrl;
		});
	});
</script>

";	

//------------BLOCKS-----------------------------
	$resBlock=mysql_query("SELECT * FROM `shop_blocks` WHERE `id`='{$getId}'; ");
	$rowBlock=mysql_fetch_assoc($resBlock);
	$urlBlock="http://".$_SERVER["HTTP_HOST"]."/catalog/".$rowBlock["url"];
	$short_text=$rowBlock["title"];
	$alias=$rowBlock["url"];
	$seo_title=$rowBlock["seo_title"];
	$seo_desc=$rowBlock["seo_desc"];
	$block=$rowBlock["position"];

	$addBlock="
		<style>
			.bodyBlocks{
				border: 1px solid gray;
			    margin: 5px;
			    background: #EFEFEF;
			    padding: 8px;			
			}
			.block_down2 {
			    border-top: 7px solid #366AB8;
			    border-left: 5px solid transparent;
			    border-right: 5px solid transparent;
			    width: 0px;
			    height: 0px;
			    float: left;
			}
			.block_up2 {
			    border-bottom: 7px solid #366AB8;
			    border-left: 5px solid transparent;
			    border-right: 5px solid transparent;
			    width: 0px;
			    height: 0px;
			    float: left;
			}
		</style>

		<br/><br/>
		<div class='bodyBlocks' rel='{$getId}' >
			<div style='display:table;width: 100%;'>
				<div style='display:table-cell'>
					<div class=\"cl_sidebar\" style='padding: 11px;padding-top: 0px;'>
						<h3>Изображение</h3>
						<div class=\"cl_edit_phoo\" style='width: initial;'>
							<img src=\"/templates/shop/image/block_share/block-{$block}.jpg\">
							<br><br>
							<input type=\"checkbox\" name=\"use_photo\" id=\"id_use_photo\" value=\"1\" checked><label for=\"id_use_photo\">Отображать изображение</label><br>
							<input name=\"myfile1\" type=\"file\" id='uploadBlock' >
							<div style='height: 0px;margin-top: -5px;width: 246px;margin-left: 16px;'>
								<div style='height: 2px;' class='see_progress' ></div>
							</div>
							<br>
						</div>	
					</div>	
				</div>
				<div style='display:table-cell;padding: 0px 10px;'>
					Название блока:<br>
					<input type=\"text\" name=\"name\" value=\"{$rowBlock['name']}\" style=\"width:500px;\" id=\"name\"><br>
					Ссылка:<br>
					<input type=\"text\" name=\"from_url\" value=\"{$urlBlock}\" style=\"width:500px;\" id=\"name\">
					<div id=\"butUrl\"><a href=\"{$urlBlock}\" target=\"_blank\">Открыть ссылку в новой вкладке</a></div>
					<br>Анотация (короткое описание):<br>
					<textarea name='short_text' id='short_text'>{$short_text}</textarea>
					<script type='text/javascript'>
					jQuery(document).ready(function() {
					CKEDITOR.replace('short_text', { language: 'ru' });
						timer = setInterval(updateDiv,100);
						function updateDiv(){
						    var editorText = CKEDITOR.instances.short_text.getData();
						    $('#short_text').val(editorText);
						}
					});
					</script>
				</div>
				<div style='display:table-cell'>
					<div>
						Адрес товара:<br>
						<input type='text' name='alias' value=\"{$alias}\" style='width:400px;' id='alias' /><br>
						<input type='checkbox' name='use_alias' id='id_use_alias' value='1' {$use_alias_checked}>
						<label style='color:#777777;font-size:11px;' for='id_use_alias'>Генерировать автоматически</label><br><br>
						Title:<br>
						<input type='text' name='title' value='{$seo_title}' style='width:400px;' id='seo_title' />
						<br><br>Description:<br>
						<textarea name='description' style='width:400px;height:100px;' id='seo_desc'>{$seo_desc}</textarea>

						<br/><br/>
						<table class=\"noborder\" style=\"margin-left:3px;float:left;border:1px solid #ccc;background:#fff;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;\">
							<tbody><tr>
								<td></td>
									<td class=\"button\" id=\"toolbar-save\">
									<div onclick=\"javascript:saveBlock();\" class=\"toolbar\">
									<span class=\"icon-32-save\" title=\"Сохранить\">
									</span>
									Сохранить
									</div>
									</td>

									<td class=\"button\" id=\"toolbar-apply\">
									<div onclick=\"javascript:applyBlock();\" class=\"toolbar\">
									<span class=\"icon-32-apply\" title=\"Применить\">
									</span>
									Применить
									</div>
									</td>

									<td class=\"button\" id=\"toolbar-cancel\">
									<div onclick=\"javascript:canelBlock();\" class=\"toolbar\">
									<span class=\"icon-32-cancel\" title=\"Отменить\">&nbsp;
									</span>
									Отменить
									</div>
									</td>

									</tr>
							</tbody></table>

					</div>	
				</div>
			</div>
		</div>
	";
	$global_get_cat = (is_numeric($show_admin_cat) and $show_admin_cat>0)?"&cat_id=".$show_admin_cat:'';
	// $its_name="Все товары";
	// $additions_buttons=get_new_buttons("/?admin=edit_commodity&commodityID={$new_id}","Добавить");
	require("modules/commodities/templates/admin.com.all.head.php"); 
	require_once("templates/$theme_name/admin.all.php"); 

}

?>