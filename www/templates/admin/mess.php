<?
$center="
  <div id='positive'> <!--// Positive message -->
    <table width='450' cellpadding='0' cellspacing='12'>
      <tr>
        <td width='52'><div align='center'><img src='/templates/{$theme_name}/images/positive.png' alt='positive' width='22' height='16' /></div></td>
        <td width='388' class='bodytext style3'> {$center}<br /><a href='/cmsadmin/'><b>Вернуться на главную страницу<b></a><br><br><br></td> <!--// positive message -->
      </tr>
    </table>
  </div>


<br>
<br>
<script type='text/javascript'>
function next_page_open(c_spec)
{	
	if (1==c_spec)
	{
		createCookie(\"spec\",0);
		window.setInterval(\"loadpage('{$_SESSION["lastpage"]}')\", 1000);
	}else 
	if (2==c_spec)
	{
		createCookie(\"spec\",0);
		window.setInterval(\"loadpage('{$_SESSION["lastpage2"]}')\", 2000);
		
	}
}
next_page_open(readCookie(\"spec\"));
function loadpage(url)
{
	location.href=url;
}
</script>
";
?>