<?

$mess_text=$_POST["text"];
$tema=$_POST["tema"];
if ($_SESSION['status']=="admin")
{
	if($_POST["tema"]!="")
	{
		
		$mess_text=$_POST["text"];
		$tema=$_POST["tema"];

		
		$mess_text=ereg_replace("'","\'",$mess_text);
		$mess_text=ereg_replace("«","\"",$mess_text);
		$mess_text=ereg_replace("»","\"",$mess_text);
		$query="
SELECT * FROM `users` 
ORDER BY `user_id`;
";
		$result=mysql_query($query);
		if (mysql_num_rows($result) > 0) 
		{
			$users="";
			
			for($i=1;$i<=mysql_num_rows($result);$i++)
			{
				$row = mysql_fetch_object($result);
				$user_id=$row->user_id;
				$user_name=$row->user_name;
				$user_email=$row->user_email;
				$user_realname=$row->user_realname;
				$user_realsoname=$row->user_realsoname;
				
				if($_POST["mess_to_{$user_id}"]==1)
				{
					
				
					send_mime_mail("Администрация сайта {$ass}",
						"noreply@".$ass,
						$user_name,
						$user_email,
						'UTF-8',  // кодировка, в которой находятся передаваемые строки
						'UTF-8', // кодировка, в которой будет отправлено письмо
						$tema,
						"{$mess_text}");
					$users.=" {$user_name} ($user_email), ";
				}
			}
		}


		$center="Сообщение успешно отправлено пользователям {$users} <br><br><a href='{$request_url}'>Перейти к списку всех пользователей</a>";
		require_once("templates/$theme_name/mess.php"); 
	
//==================================================
	}else
	{
		$_SESSION['user_count']=is_numeric($_POST["user_count"])?$_POST["user_count"]:$_SESSION['user_count'];
		$user_count=is_numeric($_SESSION['user_count'])?$_SESSION['user_count']:10;

		for($i=5;$i<=50;$i+=5)
		{
			$user_count_opt.=$i==$user_count?"<option value='$i' selected='selected'>$i</option>":"<option value='$i'>$i</option>";
		}
		if(is_numeric($_POST["user_id"]))
		{
			$user_id=$_POST["user_id"];
			$user_status=$_POST["user_status"];
			$query="
UPDATE `users` SET  
`user_admin`='0',
WHERE `user_id`='{$user_id}'
";
			$result = mysql_query($query);
			$query="
UPDATE `users` SET  
`{$user_status}`='1'
WHERE `user_id`='{$user_id}'
";
			$result = mysql_query($query);
		}			
		
if(is_numeric($_POST["user_activ"]))
		{
			$user_activ=$_POST["user_activ"];
			$query="
UPDATE `users` SET  
`user_password`=`user_registred_code`, `user_registred_code`=''
WHERE `user_id`='{$user_activ}'
";
			mysql_query($query);
		}	

		$admin_all_articles_lines="";
		$query="
SELECT * FROM `users` 

";
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0) 
		{

			$page_count=ceil(mysql_num_rows($result)/$user_count);
			if($page_count>1)
			{
				if(is_numeric($_GET["p"]))
				{
					$page_num=$_GET["p"];
					$page_num2=$_GET["p"];
					$page_num=$page_num*$user_count-$user_count;
				$sel_page="LIMIT {$page_num}, $user_count";
			
				}else
				if($_GET["p"]=="last")
				{
					$page_num=$page_count;
					$page_num2=$_GET["p"];
					$page_num=$page_num*$user_count-$user_count;
					$sel_page="LIMIT {$page_num}, $user_count";
				}else
				{
					$sel_page="LIMIT 0, $user_count";
					$page_num2=1;
				}
				$pages_links="";
				for($i=1;$i<=$page_count;$i++)
				{
					$pages_links.=$page_num2==$i?"<u>{$i}</u> ":"<a href='/?admin=all_users&p={$i}'>{$i}</a> ";
				}
			}
	

			$admin_all_articles_lines="";
			$query="
SELECT * FROM `users` 
ORDER BY `user_admin` DESC,
 `user_registred_date`

{$sel_page};
";
			$result=mysql_query($query);
			if (mysql_num_rows($result) > 0) 
			{
		
				for($i=1;$i<=mysql_num_rows($result);$i++)
				{
					$row = mysql_fetch_object($result);
					$user_id=$row->user_id;
					$user_1c_id=$row->user_1c_id;
					$user_name=$row->user_name;
					$user_realname=$row->user_realname;
					$user_realname=$user_realname!=""?"({$user_realname})":"";
					$user_last_been=$row->user_last_been;
					$user_soc_provider=$row->user_soc_provider;
					$user_soc_page=$row->user_soc_page;
					$user_soc_url=$user_soc_page!=""?"<a href='{$user_soc_page}' target='_blank'>{$user_soc_provider}</a>":"{$user_soc_provider}";
					$user_price_cat_id=$row->user_price_cat_id;
					$time=strtotime(date("Y-m-d H:i:s"))-strtotime(date($user_last_been));
					$status2=ceil($time/60)<10?"Он-лайн":$user_last_been;
					//$status=timediff($user_last_been,date("Y-m-d H:i:s"),4);
					$user_email=$row->user_email;
					$user_admin=$row->user_admin;
					$user_password=$row->user_password;
					$activ=$user_password=="0011"?"<b style='cursor:pointer;' onClick='f_activ_user({$user_id});'>Активировать</b>":"Активирован";
					$user_manager=$row->user_manager;
					$user_logistic=$row->user_logistic;
					$user_director=$row->user_director;$checkedq="Покупатель";
					$checkedq=$user_admin==1?"Админ":$checkedq;
				
					$checkedq=$user_manager==1?"Курьер":$checkedq;
				
					$checked5=(($checked1=="")&&($checked2=="")&&($checked3=="")&&($checked4==""))?"checked":"";
					require("modules/users/templates/admin.users.all.line.php"); 		
					$all_lines.=$all_line;
				}
			}
		
		
			$all_params="
<script type='text/javascript'>
function setChecked(obj)
   {
   var check = document.getElementsByClassName('mess_to');
   for (var i=0; i<check.length; i++)
      {
      check[i].checked = obj.checked;
      }
   }
function f_activ_user(user_id)
{
	document.getElementById('user_activ').value=user_id;
	document.forms.user_activ.submit();
}
</script>
Показывать по:<br />
<form method='POST' action='{$request_url}'>
<select  name='user_count' onchange='this.form.submit()'>
					{$user_count_opt}
				</select>
</form>
<form method='POST' action='{$request_url}' name='user_activ'>
<input  name='user_activ' type='hidden' value='-' id='user_activ'>
</form>
";	
			$all_params2="

Тема:<br />
<input name='tema' value='{$tema}' type='text'><br />
Текст сообщения:<br />
<textarea name='text' style='width:500px;height:150px;' />{$mess_text}</textarea>
<br /><br />
<input type='submit' value='Отправить сообщение' /><br /><br />

";
			$its_name="Все пользователи";
			$additions_buttons=get_new_buttons("/?admin=add_user","Добавить пользователя");
			require("modules/users/templates/admin.users.all.head.php");
			require_once("templates/{$theme_name}/admin.all_form.php"); 

		}
	}
}

?>