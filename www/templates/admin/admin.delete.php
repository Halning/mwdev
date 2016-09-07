<?php
$center="
<div id='tip'> <!--// tip message starts -->
    <table width='450' cellpadding='0' >
      <tbody><tr>
        <td width='52'><div align='center'><img src='/templates/{$theme_name}/images/attention.png' alt='attention' width='22' height='18'></div></td>
        <td width='388' class='bodytext style4'><span class=''><strong>Удаление!</strong> Вы действительно хотите удалить {$it_name}?</span>
			<table width='200px' style='border:0px!important;'>
				<tr style='border:0px!important;' align='center'>
					<td style='border:0px!important;'>
						<form method='POST' action='{$request_url}'>
							<input type='submit' name='submit' value='Удалить' onclick='javascript:createCookie(\"spec\",2);' />
						</form>
					</td>
					<td style='border:0px!important;' align='center'>
						
							<input type='submit' name='submit2' value='Отмена' onclick='javascript:gotospec(1);' />
						
					</td>
				</tr>
			</table>
</td> <!--// tip message -->
      </tr>
    </tbody></table>
  </div>

";
?>