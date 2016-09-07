<?
if ($_SESSION['status']=="admin")
{
	

	$sql0 ="
	SELECT * 
	FROM  `shop_categories` 
	WHERE  `categories_of_commodities_parrent` =  '10'";
	$res0 = mysql_query($sql0);
	while($row0=mysql_fetch_assoc($res0)){
		$head_id = $row0["categories_of_commodities_ID"];
		$brand_title = $row0["cat_name"];
		
		$obj_group_count_total = 0;
		$obj_group_sum_total = 0;
		
		$ret[$head_id]= "<tr>
								<td colspan = '12'>
							
								<h1> {$brand_title}</h1>
								<span class='cl_delll'>Удалить <img src='/templates/admin/img/btnbar_del.png'></span>
								<span class='cl_edittt'> Группировать<img src='/templates/admin/img/btnbar_edit.png'></span>
								<span class='obj_edittt{$head_id} obj_but' rel='{$head_id}' style='display:none; cursor:pointer'> Группировать \"Объединение групп\"<img src='/templates/admin/img/btnbar_edit.png'></span>
								</td>
								</tr>
								";		
		//$ret[$head_id].= "<tr><td colspan = '12'>Заказ№{$gro}</td></tr>";			
		$size1[$head_id] =strlen($ret[$head_id]);
		
		$sql10  ="
		SELECT * FROM `sup_group` WHERE `sup_id` = {$head_id} AND `status` < 4 ORDER BY `group_id` DESC 
		";
		$res10 = mysql_query($sql10);		
		
		while($row10 = mysql_fetch_assoc($res10)){
			$group_id = $row10["group_id"];
			
			//echo $head_id.", ";			
			
			$obj_gr=mysql_query("SELECT * FROM `brenda_object_group` WHERE `object_group_id`='{$group_id}'; ");
			$obb=$ob=mysql_fetch_assoc($obj_gr);	
			
			if(!$obb){
				$add_obb="<input type='checkbox' class='in_group{$head_id} in_gr' rel='{$group_id}' rel2='{$head_id}' >";
				$group_id2[$head_id]=$group_id;
			}else{
				$add_obb="";
				
				if($group_id2[$head_id]==false){
					$group_id2[$head_id]=$group_id;
				}else{
					$group_id2[$head_id].="/".$group_id;
				}		
				$gro=$group_id2[$head_id];	
			}			
		//	echo $gro.",";
			
			$status_group = $row10["status"];
			if($status_group == 1){
				$group_status_text = "Новый заказ";
			} elseif($status_group == 2) {
				$group_status_text = "Обрабатывается";
			} elseif($status_group == 3) {
				$group_status_text = "Подтвержден";
			} 
			if($row10["sended"] == 1)
			{
				$send_remember = "отправлено";
			}


			$group_count_total = 0;
			$group_sum_total = 0;
			$price_opt_group =0;
			
			
			$sql11="
			SELECT * FROM `shop_orders_coms`
			LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
			WHERE `group_id` = {$group_id} AND `count`>0 AND `com_status` IN (1,2,3,4) ORDER BY `group_id`  DESC";
			$res11=mysql_query($sql11);
			while($row11=mysql_fetch_assoc($res11)){
				if($row11["com_color"] == ""){
						$color = get_color_to_order($row11["com_id"]);
					} else{
						$color = $row11["com_color"];
					}
					
				

				$com_price = $row11["commodity_price2"];
				$price_group = $com_price*$row11["count"];
				$status_com = $row11["com_status"];
				$com_selected1 = "";
				$com_selected2 = "";
				$com_selected3 = "";
				$com_selected4 = "";
				$com_selected5 = "";
				$com_selected0 = "";
				$com_selected6 = "";
				$order_com_id = $row11["id"];

				$linecolor = "";
				$status_com = $row11["com_status"];
				
				if($status_com == 1){
					$com_selected1 = "selected";
					$linecolor = "greenline";
				} elseif($status_com == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
					$group_count_total -=$count_group;
					$group_sum_total -=$price_group;
					$count_not_exist[$group_id]++;
				} elseif($status_com == 3){
					$com_selected3 = "selected";
					$group_count_total -=$count_group;
					$group_sum_total -=$price_group;
				} elseif($status_com == 0){
					$com_selected0 ="selected";
				} elseif($status_com == 4){
					$com_selected4 ="selected";
				} elseif($status_com == 5){
					$com_selected5 ="selected";
				} elseif($status_com == 6){
					$com_selected6 ="selected";
				} 
				$offer_id = $row11["offer_id"];
				$sql14 = "
				SELECT * FROM `shop_orders` WHERE `id`={$offer_id}
				";
				$res14 = mysql_query($sql14);
				if($row14 = mysql_fetch_assoc($res14)){
					$date_group = $row14["date"];
					$date1 = strtotime($date_group); 
				}
				$sql12="SELECT * FROM `shop_commodities-categories`
				INNER JOIN `shop_categories` ON `shop_categories`.`categories_of_commodities_ID`=`shop_commodities-categories`.`categoryID`
				WHERE `commodityID`='{$row11['com_id']}' AND `categories_of_commodities_parrent`='10' ";
				$res12=mysql_query($sql12);

				if($row12=mysql_fetch_assoc($res12))
				{
					$basket_com_cat_group=$row12["cat_name"];
					$cod_group = $row11["cod"];
					$size_group = $row11["com"];
					$count_group = $row11["count"];
					//$price_group = $row11["price"];
					$price_opt_group += $row11["commodity_price2"];
					
					
					if ($row11["count"]> 0)
				    {
						$basket_com_price_one_group =  $price_group/$row11["count"];
					}
					$url_group=$row11["alias"]!=""?"/pr{$row11["com_id"]}_{$row11["alias"]}/":"/pr{$row11["com_id"]}/";
				//echo $group_id.", ";
				
					$lines[$group_id].="
					<tr class = 'group_td' id='{$row11['id']}' rel='shop_orders_coms' rel2='id'>
					<td class = 'group_td'><input type='checkbox' class='c2_trt' rel='{$row11["id"]}'/></td>
					<td class = 'group_td date2'><span style = 'display:none;' id='date'>{$date1}</span>{$date_group}</td>
					<td class = 'group_td'>{$cod_group}</td>
					<td class = 'group_td'>{$color}</td>
					<td class = 'group_td'>{$size_group}</td>
					<td class = 'group_td'>{$count_group}</td>
					<td class = 'group_td'>{$glb["cur"][$row11["cur_id"]]}</td>
					<td class = 'group_td'>{$com_price}</td>
					<td class = 'group_td'>{$price_group}</td>
					<td class = 'group_td'><a href ='{$url_group}'>{$url_group}</a></td>
					<td id = 'man_comment' class='cl_edit'>{$row11["man_comment"]}</td>
					<td><select size='1' name='status' id = 'select_status_com' rel = '{$row11['id']}'>
								<option value='0' {$com_selected0}></option>
								<option value='1' {$com_selected1}>Есть в наличии</option>
	    						<option value='2' {$com_selected2}>Нет в наличии</option>
	    						<option value='3' {$com_selected3}>Замена</option>
	    						<option  {$com_selected4}>оплачен</option>
	    						<option  {$com_selected5}>ожидается</option>
	    						<option  {$com_selected6}>на складе MW</option>
	    					</select>
	        		</td>
					</tr>
					";
	
				
				$group_count_total +=$count_group;
				$group_sum_total +=$price_group;
			}
		}
		$obj_group_count_total +=$group_count_total;
		$obj_group_sum_total +=$price_group;
		if(count($lines["{$group_id}"])!=0 && $group_count_total != 0){
			
				$ret[$head_id].="
				<tr class='group{$head_id}' ><td colspan = '13'>
				<span class ='opener1'>+ </span>
				<div class = 'group_line'>
				{$add_obb}
				<span class = 'group_th'>Заказ №{$group_id}</span>			
				<span class = 'group_th'>
					{$group_status_text}
	        	</span>
				
				<span class = 'group_th'><a href = '?admin=mail_to_sup&id={$group_id}' target ='_blank'><img src = '/templates/admin/img/pochta.png' class = 'mail_to_sup_img'></a>{$send_remember}</span>
				<span class = 'group_th'>Единиц: {$group_count_total}</span>
				<span class = 'group_th'>На сумму: {$group_sum_total}</span>
				<span class = 'c2_degroup'>Разгруппировать</span>
				
				</div>
				<table class = 'group_hide' style = 'width:100%; display:{$show_or_not};'> 
				<tr>
					<th></th>
					<th>Дата</th>
					<th>Артикул</th>
					<th>Цвет</th>
					<th>Размер</th>
					<th>Кол-во</th>
					<th>Валюта</th>
					<th>Цена</th>
					<th>Сумма</th>
					<th>Ссылка на товар</th>
					<th>Комментарий</th>
					<th>Статус</th>
				</tr>
				";
				$ret[$head_id].=$lines[$group_id];
				$ret[$head_id].="</table>
										</td></tr>";
			if($obb){
					$ret[$head_id].="</td></tr>";		
			}
		}
	}

	/*$ret[$head_id].= "<tr><td colspan = '12'>Заказ№{$gro}
		<span class = 'group_th'><a href = '?admin=mail_to_sup&id={$group_id}' target ='_blank'><img src = '/templates/admin/img/pochta.png' class = 'mail_to_sup_img'></a>{$send_remember}</span>
		<span class = 'group_th'>Единиц: {$obj_group_count_total}</span>
		<span class = 'group_th'>На сумму: {$obj_group_sum_total}</span>	
		</td></tr>";	
		*/
	//$size1[$head_id] =strlen($ret[$head_id]);
}



	$sql="
	SELECT * FROM `shop_orders`
	";
	$res = mysql_query($sql);
	while($row=mysql_fetch_assoc($res)){
		$offer_id = $row["id"];
		$date = $row["date"];
		$date1 = strtotime($date); 
					
		$sql2="
			SELECT * FROM `shop_orders_coms`
			LEFT JOIN `shop_commodity` ON `shop_commodity`.`commodity_ID`=`shop_orders_coms`.`com_id`
			WHERE `offer_id`='{$row["id"]}' AND `count`>0 AND `group_id` = 0 AND `com_status` in (1,2,3,4) ORDER BY `id`  DESC";


		$res2=mysql_query($sql2);
		while($row2=mysql_fetch_assoc($res2))
			{
				$com_selected1 = "";
				$com_selected2 = "";
				$com_selected3 = "";
				$com_selected4 = "";
				$com_selected5 = "";
				$com_selected0 = "";
				$com_selected6 = "";
				$order_com_id = $row2["id"];
				$linecolor = "";
				$status_com = $row2["com_status"];
				if($status_com == 1){
					$com_selected1 = "selected";
					$linecolor = "greenline";
				} elseif($status_com == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
				} elseif($status_com == 3){
					$com_selected3 = "selected";
				} elseif($status_com == 0){
					$com_selected0 ="selected";
				} elseif($status_com == 4){
					$com_selected4 ="selected";
				} elseif($status_com == 5){
					$com_selected5 ="selected";
				} elseif($status_com == 6){
					$com_selected6 ="selected";
				} 
				$sql3="SELECT * FROM `shop_commodities-categories`
				INNER JOIN `shop_categories` ON `shop_categories`.`categories_of_commodities_ID`=`shop_commodities-categories`.`categoryID`
				WHERE `commodityID`='{$row2['com_id']}' AND `categories_of_commodities_parrent`='10'";
				$res3=mysql_query($sql3);

				if($row3=mysql_fetch_assoc($res3))
				{
					$basket_com_cat=$row3["cat_name"];
					$brand_id = $row3["categories_of_commodities_ID"];

					if($row2["com_color"] == ""){
						$color = get_color_to_order($row2["com_id"]);
					} else{
						$color = $row2["com_color"];
					}
					$glb["templates"]->set_tpl('{$color}',$color);
				    $price=get_true_price($row2["price"],$row2["cur_id"]);

				    if ($row2["count"]> 0)
				    {
						$basket_com_price_one =  $price/$row2["count"];
					}
					$url=$row2["alias"]!=""?"/pr{$row2["com_id"]}_{$row2["alias"]}/":"/pr{$row2["com_id"]}/";
					$src=$row2["alias"]!=""?"<img src='/{$row2["com_id"]}stitle/{$row2["alias"]}.jpg' style='height:30px;'>":"";
					
									
					$ret[$brand_id].="<tr id='{$row2["id"]}' rel='shop_orders_coms' rel2='id'>
					<td><input type='checkbox' class='cl_trt' rel='{$row2["id"]}'/></td>
					<td ><span style = 'display:none;' id='date'>{$date1}</span>{$date}</td>
					<td>{$row2["cod"]}</td>
					<td>{$color}</td>
					<td>{$row2["com"]}</td>
					<td>{$row2["count"]}</td>
					<td>{$glb["cur"][$row2["cur_id"]]}</td>
					<td>{$basket_com_price_one}</td>
					<td>{$price}</td>
					<td><a href='{$url}'>{$url}</a></td>
					<td id = 'man_comment' class='cl_edit'>{$row2["man_comment"]}</td>
					<td>
						<select size='1' name='status' id = 'select_status_com' rel = '{$order_com_id}'>
							<option value= '0' {selected0}></option>
							<option value='1'  {$com_selected1}>Есть в наличии</option>
    						<option value='2'  {$com_selected2}>Нет в наличии</option>
    						<option value='3'  {$com_selected3}>Замена</option>
    						<option value ='4' {$com_selected4}>оплачен</option>
    						<option value ='5' {$com_selected5}>ожидается</option>
    						<option value ='6' {$com_selected6}>на складе MW</option>
    					</select>

        			
        			</td>
        			<td><span class ='select_status_com'>Изменить статус товара</span></td>
					</tr>";
				}
			}

		}
		$center = "
		<script>
		 $(document).ready(function() {
			
			
			jQuery('.cl_trt').click(function(){
				jQuery('.cl_delll').show();
				jQuery('.cl_edittt').show();
				
			});
			jQuery('.cl_delll').click(function(){
				urlid=0;
				jQuery('.cl_trt').each(function()
				{
					if($(this).is(':checked'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				if(urlid!=0)
				{
					location.href='/?admin=delete_order_com&id='+urlid;
				}
				
			});
			jQuery('.cl_edittt').click(function(){
				urlid=0;
				jQuery('.cl_trt').each(function()
				{
					if($(this).is(':checked'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				if(urlid!=0)
				{
					location.href='/?admin=sup_group&id='+urlid;
				}
				
			});
			jQuery('.c2_degroup').click(function(){
				urlid=0;
				jQuery('.c2_trt').each(function()
				{
					if($(this).is(':checked'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				if(urlid!=0)
				{
					location.href='/?admin=degroup&id='+urlid;
				}

			});
			jQuery('.c2_status').click(function(){
				urlid=0;
				jQuery('.select_status option').each(function()
				{
					if($(this).is(':selected'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				if(urlid!=0)
				{
					location.href='/?admin=add_status_com&id='+urlid;
				}
				
			});

			$('.opener1').on('click', function(e) {  
			if ($(this).parent().children('table').hasClass('opened1') && e.target == this ) {
				$(this).parent().children('table').removeClass('opened1');
			
				$(this).parent().children('table').css('display','none');
				$(this).html('+ ');
			
			} else {
				$(this).parent().children('table').addClass('opened1');
				$(this).parent().children('table').css('display','table');
				$(this).html('- ');
			}
			});
				var now = new Date()
				var today = new Date(now.getFullYear(), now.getMonth(), now.getDate()).valueOf();
				var today1 = today - 86400000;
			
			jQuery('span#date').each(function(){
				var other_text = $(this).text();
				var other = Number(other_text);
				
		
				
							
			if (today - other*1000 < 86400000) {
    			$(this).parent('td').parent('tr').css('background-color','#5EFF96');
    			//#0094FF
    			$(this).parent('td').parent('tr').parent('tbody').parent('table').css('display','table');
    			
			} else if (today - other*1000 < 86400000*2){
   				 $(this).parent('td').parent('tr').css('background-color','#0094FF');
   				 // вчера E382FF
   				 
			} else if(today - other*1000 > 86400000*2){
  			  $(this).parent('td').parent('tr').css('background-color','#E382FF');// сегодня или потом
  			 	 
			}
			});
			
			jQuery('.c3_status').click(function(){
				urlid=0;
				jQuery('.select_status2 option').each(function()
				{
					if($(this).is(':selected'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				if(urlid!=0)
				{
					location.href='/?admin=add_status_group&id='+urlid;
				}
				
			});
			
			jQuery('.in_gr').click(function(){
				var rel2=$(this).attr('rel2');
				$('.obj_edittt'+rel2).show();
			});
			
			jQuery('.obj_but').click(function(){
				var rel=$(this).attr('rel');
				var obj='';
				$('.in_group'+rel).each(function(key,val){
					var s=$(this).prop('checked');
					if(s==true){
						var rr=$(this).attr('rel');
						obj=rr+',';
					}
				});	
				obj=obj.substr(0,obj.length-1);
				//alert(obj);	
				$.get('?admin=add_status_group',{obj_group:obj, brenda_id:rel})
				.done(function(){
					$(location).attr('href','?admin=orders_brands');				
				});
			});
			
			jQuery('.group_brenda').click(function(){
				var rel=$(this).attr('rel');
				$('.group'+rel).toggle();
				$('.group2'+rel).toggle();
				//alert('ok'+rel);			
			});
			jQuery('.group_brenda').click(function(){
				var a=$(this).text();
				if(a=='+Объединение групп')
					$(this).text('- Объединение групп');
				else
					$(this).text('+Объединение групп');		
			});			
		});
		</script>
		
		";
		
		$center .= "<table class ='sortable'>";
		if($ret){
			foreach ($ret as $key => $value) {
			$size2[$key] =strlen($ret[$key]);
				if($size1[$key] < $size2[$key]){
					$center .= $ret[$key];
				}
			}
		}
		$center.="</table>";
}
