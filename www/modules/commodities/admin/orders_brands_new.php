<?php
if ($_SESSION['status']=="admin")
{
	
	$comm=mysql_query("SELECT * FROM `shop_orders_coms` AS a 
							INNER JOIN `shop_orders` AS b ON a.`offer_id`=b.`id` 
							WHERE `status` IN (1,3) AND `com_status` IN (1,2,3)");
	while($com=mysql_fetch_assoc($comm)) {
		$com_id=$com['com_id'];
		$gro_id=$com['group_id'];
		$iid=$com['id'];
		
			
		$gr=mysql_query("SELECT * FROM `sup_group` WHERE `status` IN (1,3) AND `group_id`='{$gro_id}' ");
		$gro=mysql_fetch_assoc($gr);
		$gro2=$gro['sup_id'];
		if($gro_id!=0 && $gro2==true){
			$group_id[$gro_id]=$gro2;
			
			$ob=mysql_query("SELECT * FROM `brenda_object_group` WHERE `object_group_id`='{$gro_id}' ");
			$obj=mysql_fetch_assoc($ob);
			$obj_group_id[$gro_id]=$obj['brenda_id'];			
				
		}
				
		$status_group = $gro["status"];
			if($status_group == 1){
				$group_status_text[$gro_id] = "Новый заказ";
			} elseif($status_group == 2) {
				$group_status_text[$gro_id] = "Обрабатывается";
			} elseif($status_group == 3) {
				$group_status_text[$gro_id] = "Подтвержден";
			} 			
		
		$cat=mysql_query("SELECT `categories_of_commodities_parrent`,`categories_of_commodities_ID`,`cat_name`,`categoryID`,`commodityID`
							FROM `shop_categories` AS a
							INNER JOIN `shop_commodities-categories` AS b
							ON a.`categories_of_commodities_ID`=b.`categoryID`
							WHERE `categories_of_commodities_parrent`=10 
							AND `commodityID`='{$com_id}'");	
		$gr=mysql_fetch_assoc($cat);
			$cat_id=$gr['categories_of_commodities_ID'];
			$cat_name[$cat_id]=$gr['cat_name'];
		
			$commodity[$com_id]=$cat_id;	
			$group_cat[$com_id]=$gro_id;
			
			
			$ret[$cat_id]="<tr class='tab_b' >
									<td>
										<h1 id='bre_name{$cat_id}'>{$cat_name[$cat_id]}</h1>
										<span class='cl_delll cll{$cat_id}'>Удалить <img src='/templates/admin/img/btnbar_del.png'></span>
										<span class='cl_edittt cll{$cat_id}'> Группировать<img src='/templates/admin/img/btnbar_edit.png'></span>
										<span class='obj_edittt{$cat_id} obj_but' rel='{$cat_id}' style='display:none; cursor:pointer'> Объединить группи<img src='/templates/admin/img/btnbar_edit.png'></span>	
									</td>
									<td align='center' class='up_site{$cat_id}'></td>
									<td align='center' class='up_contact{$cat_id}'></td>
									<td align='center' class='up_payment{$cat_id}'></td>
									<td align='center' class='up_delivery{$cat_id}'></td>
									<td align='center'>
										ЗАКАЗОВ<br>
										1									
									</td>
									<td align='center'>
										ЕДИНИЦ<br>
										2									
									</td>
									<td align='center'>
										ЗАКАЗОВ<br>
										1										
									</td>
									<td align='center'>
										ЕДИНИЦ<br>
										2
									</td>
									<td align='center'><span class='rating' >99,9</span></td>
									<td align='center'><span class='edit_brenda' rel='{$cat_id}' style='cursor: pointer;' ><img src='/templates/admin/img/btnbar_edit.png'></span></td>
								</tr>";	
								
			$add_group_cat[$cat_id]="<table class = 'sortable tab_brenda' id='but22_tab{$gro_id}' style='display:none' >
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
											<th>Статус</th>";
											
			$add_group_cat2[$cat_id]="<table class = 'sortable tab_brenda tab_top' >
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
											<th>Статус</th>";

	
		
	}	
	
	if($commodity){	
		$group_count_total=array();
		$group_sum_total=array();
		
		foreach($commodity as $k_comid=>$v_catid){
			$groupid=$group_cat[$k_comid];

			$shop_com=mysql_query("SELECT * FROM `shop_commodity` AS a 
										INNER JOIN `shop_orders_coms` AS b ON a.`commodity_ID`=b.`com_id`
										INNER JOIN `shop_orders` AS c ON c.`id`=b.`offer_id`
										WHERE `com_status` IN (1,2,3) AND `commodity_ID`='{$k_comid}' ");
			while($shop=mysql_fetch_assoc($shop_com)){
				$s_id=$shop['id'];
				$man_comment=$shop['man_comment'];
				$offer_id=$shop['offer_id'];
				$cod_group=$shop['cod'];
				$size_group = $shop["com"];
				$count_group = $shop["count"];
				$com_price = $shop["commodity_price2"];
				$price_group = $com_price*$shop["count"];
				$group_null=$shop['group_id'];
				
				$group_count_total[$v_catid]+=$count_group;
				$group_sum_total[$v_catid]+=$price_group;
				
				//------Select---------------------------
				$status_com = $shop["com_status"];
				$com_selected1 = "";
				$com_selected2 = "";
				$com_selected3 = "";
				$com_selected4 = "";
				$com_selected5 = "";
				$com_selected0 = "";
				$com_selected6 = "";

				$linecolor = "";
				if($status_com == 1){
					$com_selected1 = "selected";
					$linecolor = "greenline";
				} elseif($status_com == 2){
					$com_selected2 = "selected";
					$linecolor = "redline";
					$group_count_total[$v_catid] -=$count_group;
					$group_sum_total[$v_catid] -=$price_group;
					//$count_not_exist[$group_id]++;
				} elseif($status_com == 3){
					$com_selected3 = "selected";
					$group_count_total[$v_catid] -=$count_group;
					$group_sum_total[$v_catid] -=$price_group;
				} elseif($status_com == 0){
					$com_selected0 ="selected";
				} elseif($status_com == 4){
					$com_selected4 ="selected";
				} elseif($status_com == 5){
					$com_selected5 ="selected";
				} elseif($status_com == 6){
					$com_selected6 ="selected";
				} 
				//------Color----------------------------------
				if($shop["com_color"] == ""){
				//	$color = get_color_to_order($k);
				} else{
					$color = $shop["com_color"];
				}
				//-------Date---------------------
				$res14 = mysql_query("SELECT * FROM `shop_orders` WHERE `id`={$offer_id}");
				if($row14 = mysql_fetch_assoc($res14)){
					$date_group = $row14["date"];
					$date1 = strtotime($date_group); 
				}
				$url_group=$row11["alias"]!=""?"/pr{$shop["com_id"]}_{$shop["alias"]}/":"/pr{$shop["com_id"]}/";								
				$cur=$glb["cur"][$shop["cur_id"]];
				
				$add_comm[$k_comid]="
							<tr class = 'group_td' id='{$s_id}' rel='shop_orders_coms' rel2='id'>
								<td class = 'group_td'><input type='checkbox' class='c2_trt' rel='{$s_id}'/></td>
								<td class = 'group_td date2'><span style = 'display:none;' id='date'>{$date1}</span>{$date_group}</td>
								<td class = 'group_td'>{$cod_group}</td>
								<td class = 'group_td'>{$color}</td>
								<td class = 'group_td'>{$size_group}</td>
								<td class = 'group_td'>{$count_group}</td>
								<td class = 'group_td'>{$cur}</td>
								<td class = 'group_td'>{$com_price}</td>
								<td class = 'group_td'>{$price_group}</td>
								<td class = 'group_td'><a href ='{$url_group}'>{$url_group}</a></td>
								<td id = 'man_comment' class='cl_edit'>{$man_comment}</td>
								<td><select size='1' name='status' id = 'select_status_com' rel = '{$s_id}'>
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
							
				$add_comm2[$k_comid]="
							<tr class = 'group_td' id='{$s_id}' rel='shop_orders_coms' rel2='id'>
								<td class = 'group_td'><input type='checkbox' class='cl_trt' rel='{$s_id}' rel2='{$v_catid}' /></td>
								<td class = 'group_td date2'><span style = 'display:none;' id='date'>{$date1}</span>{$date_group}</td>
								<td class = 'group_td'>{$cod_group}</td>
								<td class = 'group_td'>{$color}</td>
								<td class = 'group_td'>{$size_group}</td>
								<td class = 'group_td'>{$count_group}</td>
								<td class = 'group_td'>{$cur}</td>
								<td class = 'group_td'>{$com_price}</td>
								<td class = 'group_td'>{$price_group}</td>
								<td class = 'group_td'><a href ='{$url_group}'>{$url_group}</a></td>
								<td id = 'man_comment' class='cl_edit'>{$man_comment}</td>
								<td><select size='1' name='status' id = 'select_status_com' rel = '{$s_id}'>
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
				$add_group_cat[$v_catid].=$add_comm[$k_comid]; // Gorup
				$add_group_cat2[$v_catid].=$add_comm2[$k_comid];
		
			}
		}
		
	}

/*	echo "commodity:";
	var_dump($commodity);
	echo "<br>group_id:";
	var_dump($group_id);
	echo "<br>group_cat: ";
	var_dump($group_cat);
	echo "<br>obj_group_id: ";
	var_dump($obj_group_id);
*/
	
if($group_id){
	foreach($group_id as $k_groid=>$v_catid){
		
		$group_id_name[$v_catid]="<tr><td colspan='11'>
								<div class = 'group_line2' id='but_tab{$k_groid}'>
									<input type='checkbox' class='in_group{$v_catid} in_gr' rel='{$k_groid}' rel2='{$v_catid}' style='margin-left: 5px;' />
									<span class = 'group_th plus_but' rel={$k_groid} > + Заказ №{$k_groid}</span>			
									<span class = 'group_th'>
										{$group_status_text[$k_groid]}
						        	</span>
									<span class = 'group_th'><a href = '?admin=mail_to_sup&id={$k_groid}' target ='_blank'><img src = '/templates/admin/img/pochta.png' class = 'mail_to_sup_img'></a>{$send_remember}</span>
									<span class = 'group_th'>Единиц: {$group_count_total[$v_catid]}</span>
									<span class = 'group_th'>На сумму: {$group_sum_total[$v_catid]}</span>
									<span class = 'c2_degroup'>Разгруппировать</span>
								</div>
								<div class = 'group_line' id='but2_tab{$k_groid}' style='display:none'>
									<input type='checkbox' class='in_group{$cat_id} in_gr' rel='{$gro_id}' rel2='{$cat_id}' style='margin-left: 5px;' />
									<span class = 'group_th plus_but' rel={$k_groid} > - Заказ №{$k_groid}</span>			
									<span class = 'group_th'>
										{$group_status_text[$k_groid]}
						        	</span>
									<span class = 'group_th'><a href = '?admin=mail_to_sup&id={$k_groid}' target ='_blank'><img src = '/templates/admin/img/pochta.png' class = 'mail_to_sup_img'></a>{$send_remember}</span>
									<span class = 'group_th'>Единиц: {$group_count_total[$v_catid]}</span>
									<span class = 'group_th'>На сумму: {$group_sum_total[$v_catid]}</span>
									<span class = 'c2_degroup'>Разгруппировать</span>
								</div>";
		$group_id_name[$v_catid].=$add_group_cat[$v_catid]."</table></td></tr>";
		
		
		//-----Object group--------------------	
		$group_id_name2[$v_catid]="<tr><td colspan='11'>
								<div class = 'group_line2' id='but_tab{$k_groid}'>
									<span class = 'group_th plus_but' rel={$k_groid} > + Заказ OBJ №{$k_groid}</span>			
									<span class = 'group_th'>
										{$group_status_text[$k_groid]}
						        	</span>
									<span class = 'group_th'><a href = '?admin=mail_to_sup&id={$k_groid}' target ='_blank'><img src = '/templates/admin/img/pochta.png' class = 'mail_to_sup_img'></a>{$send_remember}</span>
									<span class = 'group_th'>Единиц: {$group_count_total[$v_catid]}</span>
									<span class = 'group_th'>На сумму: {$group_sum_total[$v_catid]}</span>
									<span class = 'c2_degroup'>Разгруппировать</span>
								</div>
								<div class = 'group_line' id='but2_tab{$k_groid}' style='display:none'>
									<span class = 'group_th plus_but' rel={$k_groid} > - Заказ OBJ №{$k_groid}</span>			
									<span class = 'group_th'>
										{$group_status_text[$k_groid]}
						        	</span>
									<span class = 'group_th'><a href = '?admin=mail_to_sup&id={$k_groid}' target ='_blank'><img src = '/templates/admin/img/pochta.png' class = 'mail_to_sup_img'></a>{$send_remember}</span>
									<span class = 'group_th'>Единиц: {$group_count_total[$v_catid]}</span>
									<span class = 'group_th'>На сумму: {$group_sum_total[$v_catid]}</span>
									<span class = 'c2_degroup'>Разгруппировать</span>
								</div>";
		$group_id_name2[$v_catid].=$add_group_cat[$v_catid]."</table></td></tr>";						
		
		if($obj_group_id[$k_groid]==null){
			$ret[$v_catid].=$group_id_name[$v_catid];
		}else{
			//$ret[$v_catid].=$group_id_name2[$v_catid];		
		}
	}
}

//-------------Object group--------------------------------------------
		if($obj_group_id)
		foreach($obj_group_id as $ok=>$vk){
		
		$group_id_name2[$vk]="<tr><td colspan='11'>
								<div class = 'group_line2' id='but_tab{$ok}'>
									<span class = 'group_th plus_but' rel={$ok} > + Заказ №{$ok}</span>			
									<span class = 'group_th'>
										{$group_status_text[$ok]}
						        	</span>
									<span class = 'group_th'><a href = '?admin=mail_to_sup&id={$ok}' target ='_blank'><img src = '/templates/admin/img/pochta.png' class = 'mail_to_sup_img'></a>{$send_remember}</span>
									<span class = 'group_th'>Единиц: {$group_count_total[$vk]}</span>
									<span class = 'group_th'>На сумму: {$group_sum_total[$vk]}</span>
									<span class = 'c2_degroup'>Разгруппировать</span>
								</div>
								<div class = 'group_line' id='but2_tab{$ok}' style='display:none'>
									<span class = 'group_th plus_but' rel={$ok} > - Заказ №{$ok}</span>			
									<span class = 'group_th'>
										{$group_status_text[$ok]}
						        	</span>
									<span class = 'group_th'><a href = '?admin=mail_to_sup&id={$ok}' target ='_blank'><img src = '/templates/admin/img/pochta.png' class = 'mail_to_sup_img'></a>{$send_remember}</span>
									<span class = 'group_th'>Единиц: {$group_count_total[$vk]}</span>
									<span class = 'group_th'>На сумму: {$group_sum_total[$vk]}</span>
									<span class = 'c2_degroup'>Разгруппировать</span>
								</div>";
		$group_id_name2[$vk].=$add_group_cat[$vk]."</table></td></tr>";
		
		if($obj_group_id[$ok]!=null)
			$ret[$vk].=$group_id_name2[$vk];

	}		
	//foreach($ret as $k=>$v){
		//$ojg=$group_cat[$k];
	//	if($group_cat[$k]!="0" && $obj_group_id[$ojg]!=null){
	//		$ret[$k].="<tr><td colspan=11 >".$add_group_cat2[$k]."</table></td></tr>";
	//	}
	//}
//--------------------------------------------------------------------------------------	

	
	foreach($ret as $k=>$v){
		foreach($group_id as $kj=>$vj){
			if($k==$vj){
					$flag[$k]=1;		
			}
		}
		if($flag[$k]==0)
		$ret[$k].="<tr><td colspan=11 >".$add_group_cat2[$k]."</table></td></tr>";	
	}

	$center.="
			<style>
				.cat_name{
					width: 98%;
					font-size: 20px;
					background: white none repeat scroll 0% 0%;
					border: 1px solid;
					padding: 7px;
					padding-left: 23px;				
				}
				.plus_but{
					font-weight: bold;
					margin-left: 6px;
					cursor:pointer;
				}
				
				.tab_brenda{
					border-left: 2px solid #366AB8;
					border-right: 2px solid #366AB8;
					border-bottom: 2px solid #366AB8;
					margin-bottom: 3px;				
				}
				.tab_top{border-top: 2px solid #366AB8;}
				.tab_b td{
					vertical-align: middle;				
				}
				.rating{
					text-align: center;
					border: 1px solid;
					border-radius: 10px;				
				}
				.body_edit{
					background: rgba(0, 0, 0, 0.67) none repeat scroll 0% 0%;
					width: 100%;
					height: 100%;
					margin: -72px 0px 0px -180px;
					padding: 0px;
					position: fixed;
					z-index: 5;			
				}
				.text_edit{
					width: 600px;
					padding: 20px;
					background: #FFF none repeat scroll 0% 0%;
					border: 1px solid;
					margin-left: auto;
					margin-right: auto;
					margin-top: 5%;
				}
				
				.text_e{
					width: 100%;
					height: 110px;				
				}
				.set_name{
					font-weight: bold;
					font-size: 23px;
				}
				.set_name2{font-size: 17px;}
			</style>	
			<script>
				$(document).ready(function(){
					jQuery('.cl_trt').click(function(){
						var re=$(this).attr('rel2');
						$('.cll'+re).toggle();
						//jQuery('.cl_delll').show();
						//jQuery('.cl_edittt').show();
						
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
					$('.plus_but').click(function(){
						var r=$(this).attr('rel');
						$('#but_tab'+r).toggle();
						$('#but2_tab'+r).toggle();
						$('#but22_tab'+r).toggle();
						
					});
					jQuery('.in_gr').click(function(){
						var rel2=$(this).attr('rel2');
						$('.obj_edittt'+rel2).toggle();
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
							$(location).attr('href','?admin=orders_brands_new');				
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
					$('.edit_brenda').click(function(){
						var r=$(this).attr('rel');
						var brenda=$('#bre_name'+r).text();
						var d_site=$('.up_site'+r).text();						
						var d_contcat=$('.up_contact'+r).html();						
						var d_payment=$('.up_payment'+r).html();						
						var d_delivery=$('.up_delivery'+r).html();						
						
						
						$('.set_name').text(brenda);
						$('.set_name').attr('rel',r)
						$('.e_site').val(d_site);
						$('.e_contact').val(d_contcat);
						$('.e_payment').val(d_payment);
						$('.e_delivery').val(d_delivery);
						
						$('.body_edit').show();
						//alert(r+'. '+brenda);
					});
					
					$('.down_edit').click(function(){
						var re=$('.set_name').attr('rel');
						var site=$('.e_site').val();
						var payment=$('.e_payment').val();
						var contact=$('.e_contact').val();
						var delivery=$('.e_delivery').val();
						//alert(re+'-'+site);
						$.get('modules/commodities/admin/fun_ajax.php',{down_edit_id:re, down_site:site, down_contact:contact, down_payment:payment, down_delivery:delivery })
						.done(function(d){
							//alert(d);
							cont();
						});
						$('.body_edit').hide();
						
					});										
										
					$('.close_edit').click(function(){
						$('.body_edit').hide();
					});
				});
				
				window.onload=function(){
					cont();				
				}
				function cont(){
					$(document).ready(function(){
						$.getJSON('modules/commodities/admin/fun_ajax.php',{json:true})
						.done(function(up_js){
							for(var i=0; i<up_js.length; i++){
								var ii=up_js[i].comid;
								
								var aa=up_js[i].site;
								$('.up_site'+ii).html('<a href=\"'+aa+'\" target=_blank >'+aa+'</a>');
								
								var con=up_js[i].cont;
								$('.up_contact'+ii).html(con);
								$('.up_payment'+ii).html(up_js[i].pay);
								$('.up_delivery'+ii).html(up_js[i].deli);
							}
							
						});
					
					});
				}
			</script>
	";	
	
	$center.="<div class='body_edit' style='display: none;'>
					<div class='text_edit'>
						
						<span class='set_name' ></span><br>
						<span class='set_name2' >Сайт:</span><br>
						<input type='text' class='e_site' style='width: 100%;' ><br>
						<span class='set_name2' >Контакте:</span><br>
						<textarea class='e_contact text_e' ></textarea><br>
						<span class='set_name2' >Оплата:</span><br>
						<textarea class='e_payment text_e' ></textarea><br>
						<span class='set_name2' >Доставка:</span><br>
						<textarea class='e_delivery text_e' ></textarea><br>
						<br>
						<center>
							<button class='down_edit'>Сохранить</button>
							<button class='close_edit'>Отмена</button>
						</center>
						
					</div>
				</div>";
	
	$center.="<br><table class='sortable'>
				<tr>
					<th>
						Бренда
					</th>
					<th>
						Сайт
					</th>
					<th>
						Контакты
					</th>
					<th>
						Оплата
					</th>
					<th>
						Доставка
					</th>
					<th colspan=2 >
						Заявлено
					</th>
					<th colspan=2 >
						Обработано
					</th>
					<th>
						Рейтинг
					</th>
					<th style='width:30px' >
						Редакт.
					</th>
				</tr>
				
				";	
	ksort($ret);
	if($ret){
		foreach($ret as $key=>$val){
			$center.=$ret[$key];
		}
	}
	$center.="</table>";
}
?>