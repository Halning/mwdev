<?php
if ($_SESSION['status']=="admin")
{

	function get_http_error($theurl) {
   		$head = get_headers($theurl);
    	return substr($head[0], 9, 3);
	}

	$u=mysql_query("SELECT `user_name`,`user_realname` FROM `users` WHERE `user_name`='{$_SESSION['status']}';");
	$uu=mysql_fetch_assoc($u);
	$user=$uu['user_realname'];
	$client=array();
	$c_status=0;
	$flag=array();
	$adm="Мария";
	//$cc=mysql_query("SELECT * FROM `chat_online` WHERE `chat_from`='{$_SESSION['status']}' OR `chat_to`='{$_SESSION['status']}';");
	$cc=mysql_query("SELECT * FROM `chat_online` ");
	while($c=mysql_fetch_assoc($cc)){

		//if($c['chat_from']==$_SESSION['status']){
		if(strpos($c['chat_to'],"cid")!==false){
			if($c['chat_from']==$_SESSION['status'])
				$clinet[$c['chat_to']]=array($c['chat_name'],$c['c_status'],$c['messagebox'],$c['date'],"Алиса",1);
			else
				$clinet[$c['chat_to']]=array($c['chat_name'],$c['c_status'],$c['messagebox'],$c['date'],$c['chat_from'],1);
		}else{
			$clinet[$c['chat_from']]=array($c['chat_name'],$c['c_status'],$c['messagebox'],$c['date'],$c['chat_name'],0);
		}
		// if(strpos($c['chat_to'],"cid")===false){
		// 	$clinet[$c['chat_from']]=array($c['chat_name'],$c['c_status'],$c['messagebox'],$c['date'],$c['chat_name'],0);
		// }

	}
	
	$co_idd="";
	$nn=mysql_query("SELECT * FROM `chat_operator` WHERE 1");
	while($n=mysql_fetch_assoc($nn)){
		$status=$n['co_status'];
		$co_id=$n['co_id'];
		if($status==1){
			$html_oparetor.="<span class='av_name online_status' rel='{$co_id}' >{$n['co_name']}</span>";
			$co_text=$n['co_text'];
			$co_email=$n['co_email'];
			$add_id=$co_id;
			$add_name=$n['co_name'];
		}else{
			$html_oparetor.="<span class='av_name' rel='{$co_id}'>{$n['co_name']}</span>";
		}
		
	}

	//============Upload photo===============================================
	if(isset($_FILES["up_photo"]["name"])){

		// файл и новый размер
		$tmpFile = $_FILES["up_photo"]["tmp_name"];
		$filename = "online/images/avatar/op_avatar_{$add_name}.jpg";

		//header('Content-Type: image/jpeg');

		// получение нового размера
		list($width, $height) = getimagesize($tmpFile);

		// загрузка
		if($width<$height){
			$pol=($height-$width)/2;
			$image = new Imagick($tmpFile);
       		$image->cropImage($width,$width,0,0);
        	$image->writeImage($filename);
		}else{
			$pol=($width-$height)/2;
			$image = new Imagick($tmpFile);
       		$image->cropImage($height,$height,0,0);
        	$image->writeImage($filename);
		}
	}

	//===========================================================
	//if(get_http_error("http://makewear.com.ua/online/images/avatar/op_avatar_{$add_name}.jpg")=='200'){
	if($add_name){
		$add_img="<img src='online/images/avatar/op_avatar_{$add_name}.jpg' id='img_sel' style='border-radius: 2px;width: 130px;' />";
	}else{
		$add_img="<span>PHOTO</span>";
	}
	$tab_operator="<table>

				<tr><td>
					<table class='sortable' style='border: 1px solid;width:500px;'>
						<th colspan=2 style='font-size:18px;text-align:center;' >Оператор</th>
						<tr id='{$add_id}' class='add_id' rel='chat_operator' rel2='co_id' ><td style='width: 50px;'>Имя:</td>
						<td style='border: 1px solid;height: 20px;vertical-align: middle;'>
							{$html_oparetor}
							<button id='but_add_name' style='font-weight: bold;width: 25px;'> + </button>
						</td></tr>
						<tr id='{$add_id}' class='add_id' rel='chat_operator' rel2='co_id' >
							<td>Описание:</td>
							<td style='border: 1px solid;height: 20px;vertical-align: middle;' id='co_text' class='cl_edit'>{$co_text}</td>
						</tr>
						<tr id='{$add_id}' class='add_id' rel='chat_operator' rel2='co_id' >
							<td>E-mail:</td>
							<td style='border: 1px solid;height: 20px;vertical-align: middle;' id='co_email' class='cl_edit'>{$co_email}</td>
						</tr>
					</table>
				</td>

				<td>
					<div class='b_avatar'>
						<div class='av_photo'>
							{$add_img}
						</div>
						<center><button id='upload_photo'>ЗАГРУЗИТЬ</button></center>
					</div>
				</td></tr>

				</table>
				<br>";

	// $center="<style>
				
	// 		</style>";
	$center.="
	<link rel=\"stylesheet\" type=\"text/css\" href=\"/templates/admin/soz/style/online.css\">

	<script src=\"http://zoond-test.cloudapp.net:8264/socket.io/socket.io.js\"></script>
	<script src=\"/online/adminchat.js\"></script>
	<script src=\"/online/adminoperator.js\"></script>";
	
	$center.="<div class='online_body' style='display:none;'>
				<div class='online_chat' style='display:none;'>
				<span class='close_online'>Закрить</span>
				<span class='cli_name'></span>
					<div class='read_on'>
						<table class='tab_on' ></table>
					</div>
					<textarea class='write_on' placeholder='Введите текст сообщения...' ></textarea>
					<center>
						<button class='butt send_chat'>Отправить</button>
						<button class='close_online2 butt'>Отмена</button>
					</center>
				</div>
			</div>

			<div class='upload_body' style='display:none;'>
				<div class='up_window' style='display:none;'>
					<center>
						<span style='font-size: 20px;'>Загрузить фото</span><br/><br/>
						<form name='myfrom' action='/?admin=online' method='post' enctype='multipart/form-data'>
							<input type='file' name='up_photo' id='format_file' />
							<input type='button' value='Загрузить' id='upload_file' />
						</form>
						<button id='close_upload'>Отмена</button>
					</center>
				</div>
			</div>
			";
	// $center.="Онлайн чат<br>";
	// $center.="<span class='client_on'>Push chat</span><br>";
	$center.="<span class='title_chat'>Помощь онлайн</span><br>";
	$center.=$tab_operator;
	// $center.="<div style='border: 1px solid;width:500px;padding: 5px;'>
	// 			<span style='font-weight: bold;font-size: 18px;'>Оператор</span><br/>
	// 			Імя:<span id='co_name' class='cl_edit'>as</span><br/>
	// 			Опис:<span id='co_text' class='cl_edit'>ass</span>
	// 		</div><br/>";

	$center.="<table class='tab_chat'>";
	$center.="<th>№</th><th style='width:250px;' >Клиент</th><th>Сообщение</th><!--<th>Удалить</th>-->";
		$clinet=array_reverse($clinet);
		if($clinet)
		$ii=1;
		foreach ($clinet as $key => $val) {
			if($val[5]==1){
				$add_chat_img="<img src='online/images/avatar/op_avatar_{$val[4]}.jpg' style='width:50px;display: table-cell;margin-right: 5px;' />";
			}elseif($val[5]==0){
				$add_chat_img="<img src='online/images/avatar.jpg' style='width:50px;display: table-cell;margin-right: 5px;' />";
			}

			if($val[1]==1){
				$center.="<tr class='m_tr client_on c_bold' id='cliadd{$key}' rel='{$key}'>
							<td class='mid_v'>{$ii}</td>
							<td class='o_name get_name_{$key}' >
								<img src='/online/images/avatar.jpg' style='width:50px;' />
								<span style='vertical-align:top'>{$val[0]}</span>
							</td>
							<td class='set_msg_{$key}'>
								<div style='color:black; display:table'>
									{$add_chat_img}
									<span style='vertical-align:top;display: table-cell;width: 100%;'>
										<span class='o_name'>{$val[4]}</span>
										<span class='o_date'>{$val[3]}</span><br>
										{$val[2]}
									</span>
								</div>
							</td>
							<!--<td id='delchat' rel='{$key}' >X</td>-->
						</tr>";
			}else{
				$center.="<tr class='m_tr client_on' id='cliadd{$key}' rel='{$key}'>
							<td class='mid_v'>{$ii}</td>
							<td class='o_name get_name_{$key}' >
								<img src='/online/images/avatar.jpg' style='width:50px;' />
								<span style='vertical-align:top'>{$val[0]}</span>
							</td>
							<td class='set_msg_{$key}'>
								<div style='color:black; display:table'>
									{$add_chat_img}
									<span style='vertical-align:top;display: table-cell;width: 100%;'>
										<span class='o_name'>{$val[4]}</span>
										<span class='o_date'>{$val[3]}</span><br/>
										{$val[2]}
									</span>
								</div>
							</td>
							<!--<td id='delchat' rel='{$key}' >X</td>-->
						</tr>";
			}
			$ii++;
		}
	$center.="</table>";

}
?>