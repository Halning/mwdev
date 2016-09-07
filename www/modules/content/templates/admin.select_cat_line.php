<?php
$line=<<<HERE
<div class="inp-categ-wrap{$root_class}">
	<div class="categ-block-wrap">
		<div class="inp-categ-block">
			<span category_id="{$cat_id}">{$name}</span> <a href="javascript:;" class="select-arrow"></a>
			<input type="hidden" class="cat_ids" value="{$cat_id}" name="parent">
			<div class="clear">
			</div>
		</div>
	</div>
	{$tree}
	
	<div class="clear">
	</div>
</div>

HERE;


?>