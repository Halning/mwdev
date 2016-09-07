<?
$reg_error="";


if(is_numeric($_GET["user_id"]))
{
	$user_id=$_GET["user_id"];

if(isset($_POST["user_loginname"]))
{


	$user_loginname=$_POST['user_loginname'];
	$user_realsoname=$_POST['user_realsoname'];
	$user_realname=$_POST['user_realname'];
	$password=$_POST['new_password1'];
	$user_email=$_POST['new_email'];
	$user_password=md5($password);
	$user_tel=$_POST['user_tel'];
	$user_adr=$_POST['user_adr'];
	$user_work=$_POST['user_work'];
	$user_company_id=$_GET['company_id'];
	$birthday=$_POST['birthday'];
	$user_birth_day=$_POST['user_birth_day'];
	$user_birth_year=$_POST['user_birth_year'];
	$user_birth_month=$_POST['user_birth_month'];
	$user_sex=$_POST['user_sex'];
	$user_country=$_POST['user_country'];
	$user_admin=$_POST['admin_checkbox'];
	$user_director=$_POST['director_checkbox'];
	$user_artist=$_POST['artist_checkbox'];
	$user_organizer=$_POST['organizer_checkbox'];
	$user_manager=$_POST['manager_checkbox'];
	$user_city=$_POST['user_city'];
	$user_accept=$_POST['user_accept'];
	$user_registred_date=date("Y-m-d");
	$user_discount=$_POST["user_discount"];
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
	if ($reg_error!="")
	{
		
		$reg_error=$reg_error."<br>";
		require_once("modules/users/templates/admin.add_user.php"); 
	}else
	{

	
//echo "'{$cashier_checkbox}'";
		$query="
		UPDATE `users` 
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
		`user_discount`='{$user_discount}',
		`user_city`='{$user_city}', 
		`user_country`='{$user_country}', 
		`user_registred_date`='{$user_registred_date}'
		WHERE `user_id`='{$user_id}';";


		mysql_query($query); 
		$center="Пользователь изменен<br /> <a href='/?admin=users'>список пользователей</a>";
		require_once("templates/{$theme_name}/mess.php"); 
		
	}
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
	$query="SELECT * FROM `users` WHERE `user_id`='{$user_id}';";
	$result=mysql_query($query);
	if (mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_object($result);
		$post_user_id=$row->post_user_id;
		$user_image=$row->user_image;
		$user_name=$row->user_name;
		$user_realname=$row->user_realname;
		$user_realsoname=$row->user_realsoname;
		$user_last_been=$row->user_last_been;
		$user_registred_date=$row->user_registred_date;
		$user_tel=$row->user_tel;
		$user_loginname=$row->user_name;
		$birthday=$row->user_birthday;
		$user_sex=$row->user_sex;
		$user_discount=$row->user_discount;
		$user_country=$row->user_country;
		$user_city=$row->user_city;
		$user_adr=$row->user_adr;
		$user_realpassword=$row->user_realpassword;
		$user_email=$row->user_email;
		$user_work=$row->user_work;
		$user_name=$row->user_name;
		$cashier_checkbox=$row->user_cashier;
		for($key=0;$key<=strlen($cashier_checkbox)-1;$key++)
		{
			$cashier_checked[$key]=$cashier_checkbox[$key]==1?"checked":"";
			$cashier_checked_all=$cashier_checkbox[$key]==1?"checked":$cashier_checked_all;
		}
		$manager_checkbox=$row->user_manager;
		for($key=0;$key<=strlen($manager_checkbox)-1;$key++)
		{
			$manager_checked[$key]=$manager_checkbox[$key]==1?"checked":"";
			$manager_checked_all=$manager_checkbox[$key]==1?"checked":$manager_checked_all;
		}
		$accountant_checkbox=$row->user_accountant;
		for($key=0;$key<=strlen($accountant_checkbox)-1;$key++)
		{
			$accountant_checked[$key]=$accountant_checkbox[$key]==1?"checked":"";
			$accountant_checked_all=$accountant_checkbox[$key]==1?"checked":$accountant_checked_all;
		}
		$user_birth_day=substr($user_birthday,8,2);
		$user_birth_year=substr($user_birthday,0,4);
		$user_birth_month=substr($user_birthday,5,2);
		$admin_checkbox=$row->user_admin==1?"checked":"";
		$director_checkbox=$row->user_director==1?"checked":"";
		$artist_checkbox=$row->user_artist==1?"checked":"";
		$organizer_checkbox=$row->user_organizer==1?"checked":"";
		if($user_sex==2)
		{
			$user_sex_famale="checked='checked'";
		}
		if($user_sex==1)
		{
			$user_sex_male="checked='checked'";
		}		

			
	}
	$it_item="Редактирование пользователя";
	$additions_buttons=get_edit_buttons("/?admin=delete_user&user_id={$user_id}");
	require_once("modules/users/templates/admin.edit_user.php"); 
	require_once("templates/{$theme_name}/admin.edit.php"); 
}
}
?>