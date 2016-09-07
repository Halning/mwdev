<?php
/**
* OwerCMS
* Функции модуль управления пользователями
* @package OwerCMS
* @author Ower
* @version 3.6.25
* @since engine v.0.10
* @link http://www.ower.com.ua
* @copyright (c) 2010+ by Ower
*/

function edit_profile($user_id="")
{
	global $glb;
	if(!is_numeric($_SESSION["user_id"])) return "";
	$user_id=$_SESSION["user_id"];
	$reg_error=""; 


	$not_registred=true;
	$user_loginname=$_POST['user_loginname'];
	$user_realsoname=$_POST['user_realsoname'];
	$user_realfathername=$_POST['user_realfathername'];
	$user_realname=$_POST['user_realname'];
	$password=$_POST['password1'];
	$email=$_POST['email'];
	$user_password=md5($password);
	$user_tel=$_POST['user_tel'];
	$user_adr=$_POST['user_adr'];
	$user_work=$_POST['user_work'];
	$birthday=$_POST['birthday'];
	
	$user_sex=$_POST['user_sex'];
	$user_country=$_POST['user_country'];
	$user_region=$_POST['user_region'];
	$user_city=$_POST['user_city'];
	$user_accept=$_POST['user_accept'];
	$user_registred_date=date("Y-m-d");
	
	$user_sex_famale=$user_sex==2?"selected":"";	
	$user_sex_male=$user_sex==1?"selected":"";
	$user_accept_ok=$user_accept==1?"checked":"";

	if (isset($_POST["action"]))
	{


		$an_bp= getnewimg(1,200,300,"users",$user_id,"avatar.jpg");
		if($an_bp)
		$img="`user_image`='/images/users/{$user_id}/avatar.jpg',";

$wdwd=$password!=""?"
`user_registred_code`='{$user_password}', 
`user_realpassword`='{$password}', 
":"";
		
		$user_last_been=date("Y-m-d H:i:s");
		if ($reg_error=="")
		{
			$query="
			UPDATE `users` 
			SET 
			{$wdwd}
			`user_email`='{$email}', 
			`user_tel`='{$user_tel}', 
			{$img}
			`user_realname`='{$user_realname}', 
			`user_sex`='{$user_sex}', 
			`user_birthday`='{$birthday}'
			WHERE `user_id`='{$user_id}'
			;
			";

			mysql_query($query);
			$text="Ваш профайл успешно изменен <style>.cl_filt2{display:block!important;}.cl_filt{display:none!important;}</style>";
			$glb["templates"]->set_tpl('{$text}',$text);
			$ret=$glb["templates"]->get_tpl("mess");

			$not_registred=false;
		}
	}else
	{
		$sql="SELECT * FROM `users` 
		WHERE `user_id`='{$user_id}'";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$cat_parrentid=$row["cat_parrentid"];
			$user_realname=$row["user_realname"];
			$user_sex_famale=$row["user_sex"]==2?"selected":"";	
			$user_sex_male=$row["user_sex"]==1?"selected":"";
			$email=$row["user_email"];
			$user_tel=$row["user_tel"];
			$birthday=$row["user_birthday"];
			$reg_error="Изменения сохранены";
		}
	}	
	$glb["templates"]->set_tpl('{$reg_error_js}',$reg_error_js);
	$glb["templates"]->set_tpl('{$reg_error}',$reg_error);
	$glb["templates"]->set_tpl('{$user_loginname}',$user_loginname);
	$glb["templates"]->set_tpl('{$user_realname}',$user_realname);
	$glb["templates"]->set_tpl('{$user_sex_male}',$user_sex_male);
	$glb["templates"]->set_tpl('{$user_sex_famale}',$user_sex_famale);
	$glb["templates"]->set_tpl('{$birthday}',$birthday);
	$glb["templates"]->set_tpl('{$user_city}',$user_city);
	$glb["templates"]->set_tpl('{$countries}',$countries);
	$glb["templates"]->set_tpl('{$user_region}',$user_region);
	$glb["templates"]->set_tpl('{$user_city}',$user_city);
	$glb["templates"]->set_tpl('{$email}',$email);
	$glb["templates"]->set_tpl('{$user_tel}',$user_tel);
	$glb["templates"]->set_tpl('{$user_accept_ok}',$user_accept_ok);
	$glb["templates"]->set_tpl('{$rules}',$glb["templates"]->get_tpl("users.registration.rules"));
	$ret=$glb["templates"]->get_tpl("users.edit_profile");
	
	return $ret;
}
function days_count($date1,$date2)
{
	$ans=round((strtotime("$date2") - strtotime("$date1"))/86400);
	return $ans;
}
function get_profile($user_id="")
{
	global $glb;
	$user_id=is_numeric($user_id)?$user_id:$_SESSION["user_id"];
	if(!is_numeric($user_id)) return "";
	$query="SELECT * FROM `users` WHERE `user_id`='{$user_id}';";
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_object($result);
		$user_id=$row->user_id;
		$user_image=$row->user_image;
		$user_name=$row->user_name;
		$user_realname=$row->user_realname;
		$user_realsoname=$row->user_realsoname;
		$user_last_been=$row->user_last_been;
		$user_city=$row->user_city;
		$user_country=$row->user_country;
		$user_birthday=$row->user_birthday;
		$user_sex=$row->user_sex;
		$user_discount=$row->user_discount;
		$cart=$row->cart;
		$glb["templates"]->set_tpl('{$cart}',$cart);
		$glb["templates"]->set_tpl('{$user_id}',$row->user_id);
		$glb["templates"]->set_tpl('{$user_image}',$row->user_image);
		$glb["templates"]->set_tpl('{$user_name}',$row->user_name);
		$glb["templates"]->set_tpl('{$user_realname}',$row->user_realname);
		$glb["templates"]->set_tpl('{$user_realsoname}',$row->user_realsoname);
		$glb["templates"]->set_tpl('{$user_last_been}',$row->user_last_been);
		$glb["templates"]->set_tpl('{$user_city}',$row->user_city);
		$glb["templates"]->set_tpl('{$user_country}',$row->user_country);
		$glb["templates"]->set_tpl('{$user_birthday}',$row->user_birthday);
		
		if($user_sex==2)
		{
			$user_sex="Женский";
		}else
		{
			$user_sex="Мужской";
		}
		$glb["templates"]->set_tpl('{$user_sex}',$user_sex);
		$user_age=floor(days_count($user_birthday,date("Y-m-d"))/(365.4));
		$user_registred_date=$row->user_registred_date;
		if(($user_id==$_SESSION['user_id'])||($_SESSION['status']=='admin'))
		{
			$edit_profile="<a href='/edit_profile/' class='cl_booking_button'>Редактировать профиль</a>";
		}else
		{
			$edit_profile="";
		}
		$glb["templates"]->set_tpl('{$user_registred_date}',$user_registred_date);
		$glb["templates"]->set_tpl('{$edit_profile}',$edit_profile);
		$glb["templates"]->set_tpl('{$user_discount}',$user_discount);
		$glb["templates"]->set_tpl('{$user_age}',$user_age);
		$glb["templates"]->set_tpl('{$user_birthday}',$row->user_birthday);
		return $glb["templates"]->get_tpl("users.viewprofile")."<style>.cl_filt2{display:block!important;}.cl_filt{display:none!important;}</style>";
			
	}
	
}

function func_remind_password()
{
/**
* func_registration
* Выводит форму логина
* @version 1.3.25
* @param integer нет входных параметров
* @return string
*/
	global $glb;

	if(isset($_POST["email"]))
	{
		$email=$_POST["email"];
		if(!preg_match("/[0-9a-z_]+@[0-9a-z_^\.\-]+\.[a-z]{2,3}/i",$email)) 
		{
			$reg_error="Не правильно введен e-mail";
			$glb["templates"]->set_tpl('{$reg_error}',"<span style='color:red;'>{$reg_error}</span>");
			$ret=$glb["templates"]->get_tpl("users.remind_password");
		}else
		{
			$query="
			SELECT * FROM `users` WHERE `user_email`= '{$email}';
			";
			$result = mysql_query($query);
			if (mysql_num_rows($result) > 0) 
			{
				$row = mysql_fetch_object($result);
				$glb["templates"]->set_tpl('{$user_name}',$row->user_name);
				$glb["templates"]->set_tpl('{$user_realpassword}',$row->user_realpassword);
				$glb["templates"]->set_tpl('{$user_ip}',$_SERVER['REMOTE_ADDR']);
				$glb["templates"]->set_tpl('{$ass}',$ass);
				$text_mail=$glb["templates"]->get_tpl("users.remind_password.mail_text");
				send_mime_mail("noreply","noreply@{$ass}",$row->user_name,$_POST['email'],'UTF-8','UTF-8',"Восстановление пароля на {$_SERVER['HTTP_HOST']}",$text_mail);
				$text="На Ваш e-mail:\"{$email}\" отправлены параметры доступа";
				$glb["templates"]->set_tpl('{$text}',$text);
				$ret=$glb["templates"]->get_tpl("mess");
			}else
			{
				$reg_error="Пользователь с e-mail:\"{$email}\" не зарегистрирован";
				$glb["templates"]->set_tpl('{$reg_error}',"<span style='color:red;'>{$reg_error}</span>");
				$ret=$glb["templates"]->get_tpl("users.remind_password");
			}
		}
	}else
	{
		$glb["templates"]->set_tpl('{$reg_error}',"");
		$ret=$glb["templates"]->get_tpl("users.remind_password");
	}
	return $ret;
}
function check_user_login($socialID)
{
	if($_POST["status"]!=1)
	{
		$sql="
		UPDATE `users` 
		SET `user_admin`='0';";
		mysql_query($sql);
	}
}

function func_registration()
{
/**
* func_registration
* Выводит форму регистрации пользователя
* @version 3.6.25
* @param integer нет входных параметров
* @return string
*/
	global $glb;

	$reg_error=""; 

	$not_registred=true;
	$user_loginname=$_POST['user_loginname'];
	$user_realname=$_POST['user_realname'];
	$password=$_POST['password1'];
	$email=$_POST['email'];
	$user_password=md5($password);
	$user_tel=$_POST['user_tel'];
	$user_adr=$_POST['user_adr'];
	$user_work=$_POST['user_work'];
	$user_birth_day=$_POST['user_birth_day'];
	$user_birth_year=$_POST['user_birth_year'];
	$user_birth_month=$_POST['user_birth_month'];
	$birthday=$_POST['birthday'];
	$user_sex=$_POST['user_sex'];
	$user_country=$_POST['user_country'];
	$user_region=$_POST['user_region'];
	$user_city=$_POST['user_city'];
	$user_registred_date=date("Y-m-d");
	$user_sex=$user_sex=="male"?1:$user_sex;
	$user_sex=$user_sex=="female"?2:$user_sex;
	$user_sex_famale=$user_sex==2?"selected":"";	
	$user_sex_male=$user_sex==1?"selected":"";
	$user_socialId=$_POST["socialId"];
	$user_avatar=$_POST["avatar"];
	if (isset($_POST["socialId"]))
	{
		check_user_login($_POST["socialId"]);
		$query="
		INSERT INTO `users` 
		SET 
		`user_name`='{$user_loginname}', 
		`user_password`='{$user_password}',
		`user_registred_code`='', 
		`user_realpassword`='{$password}', 
		`user_email`='{$email}', 
		`user_realname`='{$user_realname}', 
		`user_sex`='{$user_sex}', 
		`user_birthday`='{$birthday}', 
		`user_last_been`='{$user_registred_date}',
		`user_soc_id`='{$user_socialId}', 
		`user_soc_pass`='{$user_password}', 
		`user_image`='{$user_avatar}',
		`user_registred_date`='{$user_registred_date}';
		";
		
		mysql_query($query); 
		$not_registred=false;
	}
	if (isset($_POST["action"]))
	{
		if(empty($_POST['password1'])) 
		{
			$reg_error_js.="jQuery('#id_user_password1').addClass('cred');";
		}
		if($_POST['password1']!=$_POST['password2'])
		{
			$reg_error_js.="jQuery('#id_user_password2').addClass('cred');";
		}
		if(($_SESSION['security_code'] != $_POST['security_code']) || (empty($_SESSION['security_code'])) ) 
		{
			$reg_error_js.="jQuery('#id_user_cod').addClass('cred');";
		}

		
		$user_loginname=empty($_POST['user_loginname'])?"r".date("U"):$_POST['user_loginname'];
				
		$query="SELECT * FROM `users` WHERE `user_name`='{$user_loginname}';";
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0) 
		{
			$reg_error="Пользователь с таким логином уже зарегистрирован<br />";
			$reg_error_js.="jQuery('#id_user_loginname').addClass('cred');";
		}

		$query="SELECT * FROM `users` WHERE `user_email`='{$email}';";
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0) 
		{
			$reg_error.="Пользователь с таким e-mail уже зарегистрирован<br />";
			$reg_error_js.="jQuery('#id_user_email').addClass('cred');";	
		}
		
		$user_last_been=date("Y-m-d H:i:s");
		if ($reg_error=="")
		{
			$query="
			INSERT INTO `users` 
			SET 
			`user_name`='{$user_loginname}', 
			`user_password`='{$user_password}',
			`user_registred_code`='', 
			`user_realpassword`='{$password}', 
			`user_email`='{$email}', 
			`user_realname`='{$user_realname} {$user_realsoname}', 
			`user_sex`='{$user_sex}', 
			`user_birthday`='{$birthday}',
			`user_last_been`='{$user_last_been}',
			`user_registred_date`='{$user_last_been}';
			";
			mysql_query($query); 
			
			reg_mail($user_realname,$user_loginname,$password,$email);
			$not_registred=false;
			
		}
	}
	if($not_registred)
	{
		$glb["templates"]->set_tpl('{$reg_error_js}',$reg_error_js);
		$glb["templates"]->set_tpl('{$reg_error}',$reg_error);
		$glb["templates"]->set_tpl('{$user_loginname}',$user_loginname);
		$glb["templates"]->set_tpl('{$user_realname}',$user_realname);
		$glb["templates"]->set_tpl('{$user_sex_male}',$user_sex_male);
		$glb["templates"]->set_tpl('{$user_sex_famale}',$user_sex_famale);
		$glb["templates"]->set_tpl('{$birthday}',$birthday);
		$glb["templates"]->set_tpl('{$user_city}',$user_city);
		$glb["templates"]->set_tpl('{$countries}',$countries);
		$glb["templates"]->set_tpl('{$user_region}',$user_region);
		$glb["templates"]->set_tpl('{$user_city}',$user_city);
		$glb["templates"]->set_tpl('{$email}',$email);
		$glb["templates"]->set_tpl('{$user_tel}',$user_tel);
		$glb["templates"]->set_tpl('{$rules}',$glb["templates"]->get_tpl("users.registration.rules"));
		$ret=$glb["templates"]->get_tpl("users.registration");
	}else
	{
		//Пользователь зарегистрировался - присылается уведомление администратору
		$user_registred_date2=date("Y-m-d H:i:s");
		$mail_text2="
На сайте {$_SERVER['HTTP_HOST']} зарегистрировался новый пользоатель {$user_realname} !
Регистрация была произведена с IP адреса {$_SERVER['REMOTE_ADDR']} (время {$user_registred_date2})
";
		send_mime_mail("Noreply","noreply@{$glb["mail_host"]}",$user_realname,$glb["sys_mail"],'UTF-8','UTF-8',"Регистрация на {$glb["mail_host"]}",$mail_text2);
		$text="Спасибо за регистрацию! Вы автоматически авторизованы на сайте {$user_loginname}";
		$glb["templates"]->set_tpl('{$text}',$text);
		$ret=$glb["templates"]->get_tpl("mess");
		auto_auth($user_loginname);
	}
	return $ret;
}

function reg_mail($user_realname,$user_loginname,$password,$email)
{
	global $glb;
	$text_mail="Здраствуйте, {$user_realname}!
Вы зарегистрировались на {$_SERVER['HTTP_HOST']} и указали этот e-mail ({$email}).
Здесь представлена информация, для входа на {$_SERVER['HTTP_HOST']}:

Логин: {$user_loginname}
Пароль: {$password}


Если вы этого не делали, просто проигнорируйте данное письмо.
Для вашего сведения, попытка регистрации была произведена с
IP адреса {$_SERVER['REMOTE_ADDR']}

На это письмо отвечать не нужно.

С уважением, Администрация {$_SERVER['HTTP_HOST']}.
";
				
	send_mime_mail("admin {$_SERVER['HTTP_HOST']}","noreply@{$glb["mail_host"]}",$user_realname,$email,'UTF-8','UTF-8',"Регистрация на {$_SERVER['HTTP_HOST']}",$text_mail);

}

function auto_auth($user_loginname)
{
	global $glb;
	$result = mysql_query("SELECT * FROM `users` WHERE `user_name`= '{$user_loginname}';");
	if (mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_object($result);
		$r2=$row->user_admin;
		$r_user_name=$row->user_name;
		$r_user_id=$row->user_id;
		$email=$row->user_email;
		$user_tel=$row->user_tel;
		$user_adr=$row->user_adr;
		$user_work=$row->user_work;
		$user_realname=$row->user_realname;
		$user_realsoname=$row->user_realsoname;
		$session_login=$login;
		
		$_SESSION['user_loginname']=$row->user_name; 
		$_SESSION['user_id']=$row->user_id;	
		$_SESSION['user_discount']=$row->user_discount;
		$_SESSION['status']=$row->user_admin==1?"admin":"";
		$_SESSION['user_email']=$email;
		$_SESSION['user_realname']=$user_realname;
				
	}
}
function get_account()
{
/**
* func_registration
* Выводит форму регистрации пользователя
* @version 1.3.25
* @param integer нет входных параметров
* @return string
*/
	global $glb;
	
	if (is_numeric($_SESSION['user_id'])) 
	{
		$user_id=$_SESSION['user_id'];
		$admin=$_SESSION['status']=='admin'?$glb["templates"]->get_tpl("users.account.admin"):"";
		$glb["templates"]->set_tpl('{$user_id}',$user_id);
	
				$result = mysql_query("SELECT * FROM `users` WHERE `user_id`='{$user_id}'");
				if (mysql_num_rows($result) > 0) 
				{
					$r3="";
					$row = mysql_fetch_object($result);
					$user_realname=$row->user_realname;
				
				}
				
		$glb["templates"]->set_tpl('{$user_name}',$user_realname);
		$glb["templates"]->set_tpl('{$user_email}',$row->user_email);
		$ret=$glb["templates"]->get_tpl("users.account.logged");
		$date=date("Y-m-d H:i:s");
	
		$query="UPDATE `users` SET `user_last_been`='{$date}' WHERE `user_id`= '{$user_id}';";
		mysql_query($query);
	}else
	{
		$glb["templates"]->set_tpl('{$user_name}',"");
		$glb["templates"]->set_tpl('{$user_email}',"");
		$ret=$glb["templates"]->get_tpl("users.account.unlogged");
	}
	return $ret;
}

function user_auto_reg ($user_realname="",$user_tel="",$email="",$user_adr="") 
{
/**
* func_registration
* Выводит форму регистрации пользователя
* @version 1.3.25
* @param integer нет входных параметров
* @return string
*/
	global $glb;
	$user_loginname="a".date("U");
	$user_realsoname="";

	$possible = 'abcdefgwz23456789'; 
	while ($i < 8)
	{ 
		$password.=substr($possible, mt_rand(0, strlen($possible)-1), 1);
		$i++;
	}
	
	$user_password=md5($password);
	
	
	$user_birth_day="";
	$user_birth_year="";
	$user_birth_month="";
	$user_sex=1;
	$user_country="";
	$user_city="";
	$user_registred_date=date("Y-m-d");

	if(is_numeric($_SESSION['user_id']))
	{
		$sql="SELECT * FROM `users` 
		WHERE `user_id`='{$_SESSION['user_id']}'";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			if($row["user_email"]=="")
			{
				$sql="UPDATE `users` 
				SET `user_email`='{$email}'
				WHERE `user_id`='{$_SESSION['user_id']}'";
				mysql_query($sql);
				return $_SESSION['user_id'];
			}
		}
	}
	
	$query="
	INSERT INTO `users` 
	SET `user_name`='{$user_loginname}', 
	`user_password`='0011',`user_registred_code`='{$user_password}', `user_realpassword`='{$password}', 
	`user_email`='{$email}', `user_tel`='{$user_tel}', `user_adr`='{$user_adr}',`user_realname`='{$user_realname}', 
	`user_work`='{$user_work}', `user_sex`='{$user_sex}', `user_birthday`='', `user_city`='{$user_city}', 
	`user_country`='{$user_country}', `user_registred_date`='{$user_registred_date}';";


		@mysql_query($query) or die ($query); 
		$result = mysql_query("SELECT * FROM `users` WHERE `user_name`= '{$user_loginname}' ;");
			if (mysql_num_rows($result) > 0) 
			{
				
				reg_mail($user_realname,$user_loginname,$password,$email);
				auto_auth($user_loginname);
				return  $_SESSION['user_id'];
			}
}


function account_options() 
{
/**
* func_registration
* Выводит форму регистрации пользователя
* @version 1.3.25
* @param integer нет входных параметров
* @return string
*/
	global $ass;
	
	if (isset($_POST["users_option"]))
	{
		
		$option=$_POST["users_option"];
		if ($option=="logoff")
		{
			$_SESSION['user']=""; 
			$_SESSION['status']="";
			$_SESSION['user_id']="";
			$_SESSION['user_email']="";
			$_SESSION['user_realname']="";
			$_SESSION['user_discount']=0;
			$_SESSION['logged']=false;
		}elseif ($option=="login")
		{
			$login = substr($_POST['login'], 0, 30);
			$psw = substr($_POST['password'], 0, 30); 
			$psw =md5($psw);
			if ($login!=""&&$psw!="")
			{ 
				$result = mysql_query("SELECT * FROM `users` WHERE ((`user_email`= '{$login}') OR (`user_name`= '{$login}')) and (`user_password` = '{$psw}' OR `user_soc_pass` = '{$psw}')");
				if (mysql_num_rows($result) > 0) 
				{
					auto_auth($login);	
				}elseif(isset($_POST["socialId"]))
				{
					check_user_login($_POST["socialId"]);
					auto_social_reg();
				}
			}
		}
	}
}

function auto_social_reg()
{
	$user_loginname=$_POST['user_loginname'];
	$user_realname=$_POST['user_realname'];
	$password=$_POST['password1'];
	$email=$_POST['email'];
	$user_password=md5($password);
	$birthday=$_POST['birthday'];
	$user_registred_date=date("Y-m-d");
	$user_sex=$_POST['user_sex'];
	$user_sex=$user_sex=="male"?1:$user_sex;
	$user_sex=$user_sex=="female"?2:$user_sex;
	$user_socialId=$_POST["socialId"];
	$user_avatar=$_POST["avatar"];
	$socialPage=$_POST["socialPage"];
	$provider=$_POST["provider"];
	if (isset($_POST["socialId"]))
	{
		check_user_login($_POST["socialId"]);
		$sql="SELECT * FROM `users`
		WHERE `user_email`='{$email}';";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row && $email!="")
		{
			$user_loginname=$row["user_name"];
			$query="
			UPDATE `users` 
			SET
			`user_registred_code`='', 
			`user_realname`='{$user_realname}', 
			`user_sex`='{$user_sex}', 
			`user_birthday`='{$birthday}', 
			`user_last_been`='{$user_registred_date}',
			`user_soc_id`='{$user_socialId}', 
			`user_soc_pass`='{$user_password}', 
			`user_image`='{$user_avatar}',
			`user_soc_page`='{$socialPage}',
			`user_soc_provider`='{$provider}'
			WHERE `user_email`='{$email}'
			;
			";
		}else
		{
			$query="
			INSERT INTO `users` 
			SET 
			`user_name`='{$user_loginname}', 
			`user_password`='{$user_password}',
			`user_registred_code`='', 
			`user_realpassword`='{$password}', 
			`user_email`='{$email}', 
			`user_realname`='{$user_realname}', 
			`user_sex`='{$user_sex}', 
			`user_birthday`='{$birthday}', 
			`user_last_been`='{$user_registred_date}',
			`user_soc_id`='{$user_socialId}', 
			`user_soc_pass`='{$user_password}', 
			`user_image`='{$user_avatar}',
			`user_soc_page`='{$socialPage}',
			`user_soc_provider`='{$provider}',
			`user_registred_date`='{$user_registred_date}';
			";
		}
		mysql_query($query); 
	}
	auto_auth($user_loginname);	
}