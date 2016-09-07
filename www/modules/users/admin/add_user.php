<?
$reg_error="";
$comp=is_numeric($_GET["company_id"])?"&company_id=".$_GET["company_id"]:"";
$month = array ();
$month[1]="Январь";
$month[2]="Февраль";
$month[3]="Март";
$month[4]="Апрель";
$month[5]="Май";
$month[6]="Июнь";
$month[7]="Июль";
$month[8]="Август";	
$month[9]="Сентябрь";
$month[10]="Октябрь";
$month[11]="Ноябрь";
$month[12]="Декабрь";

if (isset($_POST["user_loginname"]))
{
	$user_loginname=$_POST['user_loginname'];
	$user_realname=$_POST['user_realname'];
	$password=$_POST['new_password1'];
	$new_email=$_POST['new_email'];
	$user_password=md5($password);
	$user_tel=$_POST['user_tel'];
	$user_adr=$_POST['user_adr'];
	$user_work=$_POST['user_work'];
	$user_company_id=$_GET['company_id'];
	
	$birthday=$_POST['birthday'];
	$user_sex=$_POST['user_sex'];
	$user_country=$_POST['user_country'];
	$user_city=$_POST['user_city'];
	$user_accept=$_POST['user_accept'];
	$user_registred_date=date("Y-m-d");
	$user_admin=$_POST['admin_checkbox'];
	$user_director=$_POST['director_checkbox'];
	$user_artist=$_POST['artist_checkbox'];
	$user_organizer=$_POST['organizer_checkbox'];
	$user_manager=$_POST['manager_checkbox'];
	if($user_sex==2)
	{
		$user_sex_famale="checked='checked'";
	}
	if($user_sex==1)
	{
		$user_sex_male="checked='checked'";
	}
	if($user_accept==1)
	{
		$user_accept_ok="checked='checked'";
	}

	

	


		$query="
		INSERT INTO `users` 
		SET 
		`user_name`='{$user_loginname}', 
		`user_password`='{$user_password}',
		`user_registred_code`='', 
		`user_realpassword`='{$password}', 
		`user_email`='{$user_email}', 
		`user_tel`='{$user_tel}', 
		`user_adr`='{$user_adr}',
		`user_admin`='{$user_admin}',
		`user_realname`='{$user_realname}', 
		`user_work`='{$user_work}', 
		`user_sex`='{$user_sex}', 
		`user_birthday`='{$birthday}', 
		`user_city`='{$user_city}', 
		`user_country`='{$user_country}', 
		`user_registred_date`='{$user_registred_date}';
		";


		@mysql_query($query) or die ("Ошибка регистрации ".mysql_error()); 
		$center="Пользователь добавлен<br /> <a href='/?admin=users'>список пользователей</a>";
					require_once("templates/{$theme_name}/mess.php"); 
		
	
}else
{



	$user_adr="";
	$user_work="";
	$user_tel="";
	$new_email="";
	$user_realsoname="";
	$user_realname="";
	$user_country="";
	$user_loginname="";
	$it_item="Добавление пользователя";
			$additions_buttons=get_add_buttons();
	require_once("modules/users/templates/admin.edit_user.php"); 
	require_once("templates/{$theme_name}/admin.edit.php"); 	

}
?>