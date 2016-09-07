<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>Административная панель</title>	
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />	
	<link rel='icon' type='image/ico' href='/templates/{$theme_name}/img/ico.ico'/> 	
	<link href='/templates/{$theme_name}/css/style.css' type='text/css' rel='stylesheet'/>
	<link href="/templates/{$theme_name}/css/jquery.selectBox.css" media="screen" rel="stylesheet" type="text/css" />
	<link href='http://fonts.googleapis.com/css?family=Comfortaa:400,700&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
	<link rel='stylesheet' type='text/css' media='all' href='/templates/{$theme_name}/css/uploadify.css' title='win2k-cold-1' />
	<link rel='stylesheet' type='text/css' href='/templates/{$theme_name}/css/colorbox.css' />
	<link rel='stylesheet' type='text/css' href='/templates/{$theme_name}/css/jquery-ui.css' />
	<link rel='stylesheet' href='/includes/graph/prettify.css'>
	<link rel='stylesheet' href='/includes/graph/morris.css'>
</head>
<body onLoad="InitTree();">
	<!--{$time_generate}-->
	<script type='text/javascript' src='/templates/{$theme_name}/js/jquery.js'></script>
	<script type="text/javascript" src="/templates/{$theme_name}/js/jquery-ui-1.8.16.custom.min.js"></script>
	<script type='text/javascript' src='/templates/{$theme_name}/js/jquery.uploadify-3.1.min.js'></script>
	<script type="text/javascript" src="/includes/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="/templates/{$theme_name}/js/sorttable.js"></script>
	<script type="text/javascript" src="/templates/{$theme_name}/js/admin_dropmenu.js"></script> 
	<script type="text/javascript" src="/templates/{$theme_name}/js/admin_sprinkle.js"></script> 
	<script type="text/javascript" src="/templates/{$theme_name}/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="/templates/{$theme_name}/js/jquery.contextmenu.r2.js" ></script>
	<script type="text/javascript" src="/templates/{$theme_name}/js/js.js"></script>
	<script type="text/javascript" src="/templates/{$theme_name}/js/mnu3.js"></script>
	<script type='text/javascript' src='/templates/{$theme_name}/js/jquery.selectBox.js'></script>
	<script type="text/javascript" src="/templates/{$theme_name}/js/jquery.jstree.js"></script>	
	<script type='text/javascript' src='/templates/{$theme_name}/js/jquery.colorbox-min.js'></script>
<style>
<!--cl_shop-->
</style>
<div class='cl_body'>
	<div class='cl_header'>
	
		<div id="logo">	
			<a href='/cmsadmin/' ><img src="/templates/{$theme_name}/images/ower.png" alt="logo"  class='cl_logo' /> </a>
			<a href='/cmsadmin/' class='cl_logo_text'>OWER CMS</a>
			<span id='id_ver'>ver. {$cms_ver}</span>
		</div>
		<div class='cl_topright'>
			{$account}
			<div class='cl_top_but'>
				<a href='/' target='_blank'><img src="/templates/{$theme_name}/images/agt_web.png" alt="big_visitsite"  /></a>	
				<a href='/' target='_blank'>На сайт</a>		
			</div>
			<div class='cl_top_but'>
				<a href='/cmsadmin/'><img src="/templates/{$theme_name}/images/1400869135_20.png" alt="big_visitsite"  /></a>	
				<a href='/cmsadmin/'>Посещение</a>		
			</div>
			<div class='cl_top_but'>
				<a href='{$request_url}&print' target='_blank'><img src="/templates/{$theme_name}/img/toolbar/icon-32-print.png" alt="big_visitsite"  /></a>	
				<a href='{$request_url}&print' target='_blank'>Печать</a>		
			</div>
			<div class='cl_top_but'>
				<a href='/?admin=edit_site_default'><img src="/templates/{$theme_name}/images/big_settings.png" alt="big_visitsite"  /></a>	
				<a href='/?admin=edit_site_default'>Настройки</a>		
			</div>
			<div class='cl_top_but'>
				<a href="https://siteheart.com/webconsultation/545348?" target="siteheart_sitewindow_545348" onclick="o=window.open;o('https://siteheart.com/webconsultation/545348?', 'siteheart_sitewindow_545348', 'width=550,height=400,top=30,left=30,resizable=yes'); return false;"><img src="http://webindicator.siteheart.com/webindicator/image/1370424021?ent=545348&company=528942" alt="big_visitsite"  /></a>	
				<a href="https://siteheart.com/webconsultation/545348?" target="siteheart_sitewindow_545348" onclick="o=window.open;o('https://siteheart.com/webconsultation/545348?', 'siteheart_sitewindow_545348', 'width=550,height=400,top=30,left=30,resizable=yes'); return false;">Помощь</a>		
			</div>
			<div class='cl_top_but'>
				<a href="#" onClick="document.forms.logout_form.submit();return '';"><img src="/templates/{$theme_name}/images/big_logout.png" alt="big_visitsite"  /></a>	
				<a href="#" onClick="document.forms.logout_form.submit();return '';">Выход</a>		
			</div>	
		</div>
	</div>
	<table style='width:100%;height:100%;margin-bottom:-75px;' cellspacing='0px'>
		<tr>		
			<td valign='top' style='width:180px;' class='cl_left_bg' >			
				<div id="leftcolumn">			
					<ul id="my-menu" >
						{$admin_left_menu}
					</ul>
				</div>
			</td>
			<td valign='top'>
				<div>
					<table class='noborder' style='margin-left:3px;float:left;border:1px solid #ccc;background:#fff;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;'>
							<tr>
								<td></td>{$additions_buttons}
							</tr>
					</table>
					<h1 style='float:left;'>{$its_name}{$it_item}</h1>
				</div>
				{$center}
				<table class='noborder' style='border:1px solid #ccc;background:#fff;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;'>
					<tr>
						<td></td>{$additions_buttons}
					</tr>
				</table>					
			</td>		
		</tr>
	</table>
</div>
<script type="text/javascript"> _shcp = []; _shcp.push({widget_id : 545348, widget : "Chat"}); (function() { var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true; hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://widget.siteheart.com/apps/js/sh.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hcc, s.nextSibling); })();</script>
</body>
</html>