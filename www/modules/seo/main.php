<?
if (($_SESSION['status']=="admin")&&(isset($_GET["admin"])))
{

$admin_left_menu.="
<li><div class='cl_menu_div'><div class='icon-32-link cl_left_bt'></div><a href='/?admin=all_articles'>Перелинковка</a></div>
<ul>
	<li><a href='/?admin=all_pages'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Карта сайта</a></li>
</ul>
</li>
<style>
	.icon-32-refresh 	{ background-image: url(/modules/seo/templates/img/refresh.png); }
	.icon-32-link		{ background-image: url(/modules/seo/templates/img/link.png); }
	#cl_newwindow
	{
	position:absolute!important;
	top:0px;
	left:0px;
	width:400px;
	height:100px;
	margin:100px 0px 0px 0px!important;
	background:#ffffff;
	border-radius:15px;
	-webkit-box-shadow: 1px 1px 5px 0px rgba(50, 50, 50, 0.75);
	-moz-box-shadow: 1px 1px 5px 0px rgba(50, 50, 50, 0.75);
	box-shadow: 1px 1px 5px 0px rgba(50, 50, 50, 0.75);
	border:none!important;
	display:none;
	}
	.cl_edit2 select 
	{
	display: none;
	}
</style>
<script>
jQuery(document).ready(function()
{
		jQuery('#id_torefresh').click(function()
		{
			jQuery('#cl_newwindow').slideDown();
			
			jQuery.getJSON('/modules/seo/ajax/admin_index.php?start=1&callback=?', {}, function(resr)
			{
				jQuery('.cl_r_text').html('Осталось: '+resr.ost+'<br>Проиндексировано: '+resr.index);
			});
			setInterval(ui9io, 2000);
		});
				
		function ui9io()
		{
			jQuery.getJSON('/modules/seo/ajax/admin_index.php?callback=?', {}, function(resr)
			{
				jQuery('.cl_r_text').html('Осталось: '+resr.ost+'<br>Проиндексировано: '+resr.index);
				if(resr.ost==0)
				{
				    location.reload();
				}
			});
		}
		
		jQuery('.cl_ttwq').click(function()
		{
			jQuery('.sel_commodity').each(function()
			{
				jQuery(this).prop('checked', jQuery('.cl_ttwq').prop('checked'));
			});  
				
		});
});
</script>
";

	$url_local=array();

	foreach($url_local as $key=>$value)
	{
		$url_admin_menu[$key]="seo";
		$url_admin[$key]=$value;
	}
	require_once("modules/seo/admin/functions.php");
	
	$paget[1]="Продвигаеммая";
	$paget[2]="Промежуточная";
	$paget[3]="Обычная";
	$paget[4]="Не продвигаеммая";
	$paget[5]="Не индексируемая";
	
}elseif(!isset($_GET["admin"]))
{
	require_once("modules/seo/site/functions.php");
}
?>