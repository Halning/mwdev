<?
if ($_SESSION['status']=="admin")
{



function func_module_menu_comments($url_menu)
{
	$category_category="

<input type='radio' class='radiob' name='radiob' onclick='do_five(\"comments_div\");selChange(this.form.comments_sel);' id='comments_div' {$gl_check}>
<label for='main_page_div'>Отзывы<br /></span>
<div class='radio_div' id='comments_div' >
	<select name='comments_sel' onChange='do_five(\"comments_div\");selChange(this.form);' style='display:none;'>
		<option value='/responses.html-///-Отзывы'>Отзывы</option> 
	</select>
</div>

";


	return $category_category;
}

}
?>