<?
if ($_SESSION['status']=="admin"){

	$id=$_GET['id'];

	if($id)
		$img="<img src='/templates/shop/image/categories/{$id}/main.jpg' width='200px' style='margin-bottom: -62px;margin-top: -62px;' >";
	else
		$img="<span>LOGO</span>";




	$txt='
</head>
<body>
	<div class="body_bc" rel='.$id.' >
		<table>
			<tr><td><span class="line_name" >БРЕНД</span></td><td></td><td><span class="line_name" >КОНТРАГЕНТ</span></td></tr>
			<tr><td>
				<div class="body_logo_img">
					'.$img.'
				</div>
			</td><td id="ta_tr">
				<div class="logo_info">
					<label style="width: 115px;" >НАВЗАНИЕ:</label><input type="text" id="bc_name" value="'.$name.'" /><br/>
					<label style="width: 115px;" >ВЕБ-САЙТ:</label><input type="text" id="bc_site" value="'.$site.'" /><br/>
					<label style="width: 115px;" >О БРЕНДЕ:</label><textarea id="bc_info" style="height: 120px;">'.$info.'</textarea>
				</div>
			</td>
			<td>
				<div class="logo_info">
					<label style="width: 115px;" >ФЛП</label><input type="text" class="con_flp" id="bc_name" value="" style="width: 50%;" /><br/>
					<label style="width: 115px;" >ЕГРПОУ</label><input type="text" class="con_egp"  id="bc_name" value="" style="width: 50%;" /><br/>
					<label style="width: 115px;" >ИНН</label><input type="text" class="con_inn"  id="bc_name" value="" style="width: 50%;" /><br/>
					<label style="width: 115px;" >АДРЕС</label><input type="text"  class="con_add" id="bc_name" value="" style="width: 50%;" /><br/>
					<label style="width: 115px;" >РС</label><input type="text"  class="con_pc" id="bc_name" value="" style="width: 50%;" /><br/>
					<label style="width: 115px;" >БАНК</label><input type="text" class="con_bank"  id="bc_name" value="" style="width: 50%;" /><br/>
					<label style="width: 115px;" >МФО</label><input type="text" class="con_mfo"  id="bc_name" value="" style="width: 50%;" /><br/>
				</div>
				<br/>
					<span style="font-size:16px;">Скан-копии докумментов</span>
				<br/>
					<div style="display:table">
						<div style="display:table-cell">
							<div class="upload_up">
								<input type="file" class="upfile" />
								<div class="icon_upload"></div>
							</div>
						</div>
						<div style="display:table-cell">
							<div class="upload_up">
								<input type="file" class="upfile" />
								<div class="icon_upload"></div>
							</div>
						</div>
						<div style="display:table-cell">
							<div class="upload_up">
								<input type="file" class="upfile" />
								<div class="icon_upload"></div>
							</div>
						</div>
						<div style="display:table-cell">
							<div class="upload_up">
								<input type="file" class="upfile" />
								<div class="icon_upload"></div>
							</div>
						</div>
						<div style="display:table-cell">
							<div class="upload_up">
								<input type="file" class="upfile" />
								<div class="icon_upload"></div>
							</div>
						</div>
					</div>
			</td></tr>

		</table>
		<br/>
		<span class="line_name">ТОВАР</span>	
		<hr class="line" />
		<table>
		<tr><td>
		<div class="brenda_body">
			<div class="bre0">
			<span class="but_add add_commodity" style="float: left;margin: 0 4px;margin-top: 3px;" ></span>
				<select>
					<option>ОДЕЖДА</option>
					<option>ОБУВЬ</option>
					<option>АКСЕССУАРЫ</option>
				</select>
				<span class="but_sex sex_M" style="margin-left: -1px;">М</span>
				<span class="but_sex sex_W" style="margin-left: -1px;">Ж</span>
				<span class="but_sex sex_K" style="margin-left: -1px;">Д</span>
				<span class="but_sex sex_U" style="margin-left: -1px;">У</span>
			</div>
		</div>
		</td><td id="tab_tr">
			<div class="size_brenda">ТАБЛИЦА РАЗМЕРОВ<span id="size_plus"></span></div>
			<div class="tab_size" style="display:block">
				<button id="add_tr" title="Добавить строку">Добавить строку</button>
				<button id="add_last_td" title="Добавить столбцы">Добавить столбцы</button>
				<div style="display:table;height: 14px;" id="butDeleteRow"></div>
				<table id="table_size" class="brendaTableSize">
				</table>
			</div>
		</td></tr>
		</table>
		<br/>
		<br/>
		<span class="line_name">УСЛОВИЯ</span>	
		<hr class="line" />
		<table class="tab_po">
			<th>ОПТ</th><th>РОЗНИЦА</th>
			<tr><td>
				<label class="bor_label">СКИДКА:</label>
					<select class="sel_sk sel_skk"></select> %
				<br>
				<label class="bor_label">НАЦЕНКА:</label>
				<!--<select class="sel_na sel_skk"></select> %-->
				<input type="text" style="width:50px;" class="input_bor sel_na sel_skk" />
				<br>
				<label class="bor_label">ОТГРУЗКА:</label>
					<select class="sel_ot sel_skk"></select> мин/ед
					<input type="text" id="bc_name" style="width:50px;" class="input_bor ot_min_price" /> мин/грн
				<br>
				<label class="bor_label">ДОСТАВКА:</label>
				<select class="sel_do" style="width: 260px;">
						<option></option>
					</select><br>
				<label class="bor_label">ЦЕНА:</label>
					<select class="sel_pr" style="width: 260px;">
						<option></option>
					</select>
				<br>
			</td>

			<td style="display: inline-block;">
				<!--<label class="bor_label">СКИДКА:</label>-->
				<select class="sel_pr_sk sel_skk" style="margin: 7px;"></select> %
				<br>

				<!--<label class="bor_label">НАЦЕНКА:</label>-->
				<!--	<select class="sel_pr_na sel_skk"></select> -->
					<input type="text" style="width:50px;margin:7px;" class="input_bor sel_pr_na sel_skk" />
				<br>
				<!--<label class="bor_label">ОТГРУЗКА:</label>-->
					<select class="sel_pr_ot sel_skk" style="margin: 7px;"></select> мин/ед
					<input type="text" id="bc_name" style="width:50px;margin:7px;" class="input_bor ot_min_pr_price" /> мин/грн
				<br>

				<!--<label class="bor_label">ДОСТАВКА:</label>-->
				<select class="sel_pr_do" style="width: 260px;margin:7px;">
						<option></option>
					</select><br>
				<!--<label class="bor_label">ЦЕНА:</label>-->
					<select class="sel_pr_price" style="width: 260px;margin:7px;">
						<option></option>

					</select>
				<br>
				
			</td></tr>

		</table>
		<br/>
		<span class="line_name">КОНТАКТЫ</span>	
		<hr class="line" />
		<table class="tab_contact">
			<tr>
				<td>
					<div id="push_name">
					<label class="bor_label">ИМЯ:</label><input type="text" id="bc_name" class="input_bor cont_name" style="width: 250px;" />
					<span class="but_add add_name" style="position: absolute;margin-top: -38px;margin-left: 450px;"></span>
					</div><br>

					<div id="push_phone">
					<label class="bor_label">ТЕЛЕФОН:</label><input type="text" id="bc_name" class="input_bor cont_phone" style="width: 250px;" />
					<span class="but_add add_phone" style="position: absolute;margin-top: -38px;margin-left: 450px;"></span></div><br>
					
					<div id="push_mail">
					<label class="bor_label">ПОЧТА:</label><input type="text" id="bc_name" class="input_bor cont_mail" style="width: 250px;" />
					<span class="but_add add_mail" style="position: absolute;margin-top: -38px;margin-left: 450px;"></span></div><br>
				</td>
				<td style="display: inline-block;">
					<label class="bor_label">ДОПОЛНИТЕЛЬНО:</label>
					<textarea class="textarea_bor" id="cont_dop" style="height: 200px;" ></textarea>
				</td>
			</tr>
		</table>
		<br/>
		<span class="line_name">РЕКВИЗИТЫ</span>	
		<hr class="line" />
		<table class="tab_contact">
			<th>ОПЛАТА</th>
			<th>ДОСТАВКА</th>
			<tr>
				<td>
					<label class="bor_label">ВИД ПЛАТЕЖА:</label><input type="text" id="op_pl" class="input_bor" /><br>
					<label class="bor_label">Ф.И.О.</label><input type="text" id="op_name" class="input_bor" /><br>
					<label class="bor_label">БАНК:</label><input type="text" id="op_bank" class="input_bor" /><br>
					<label class="bor_label">№ СЧЕТА:</label><input type="text" id="op_chet" class="input_bor" /><br>
					<label class="bor_label">ДОПОЛНИТЕЛЬНО:</label>
					<textarea class="textarea_bor" id="op_dop"></textarea>
				</td>
				<td style="display: inline-block;">
					<label class="bor_label">ГОРОД:</label><input type="text" id="de_city" class="input_bor" /><br>
					<label class="bor_label">СПОСОБ</label><input type="text" id="de_cpo" class="input_bor" /><br>
					<label class="bor_label">№СКЛАДА/АДРЕС:</label><input type="text" id="de_address" class="input_bor" /><br>
					<label class="bor_label">ПОЛУЧАТЕЛЬ</label><input type="text" id="de_get" class="input_bor" /><br>
					<label class="bor_label">ДОПОЛНИТЕЛЬНО:</label>
					<textarea class="textarea_bor" id="de_dop"></textarea>
				</td>
			</tr>
		</table>
		<hr class="line" />
		<center><button id="save_brenda" >Сохранить</button></center>
	</div>
</body>
</html>



	';


	$center="<div style='background: white;margin-top: 33px;' >".$txt."</div>";
	$center.='<script type="text/javascript">
				var id='.($id).';
			</script>
	<script type="text/javascript" src="/templates/admin/soz/js/brenda_contact.js" ></script>
	<link href="/templates/admin/css/brenda_contact.css" type="text/css" rel="stylesheet" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

';



}
?>