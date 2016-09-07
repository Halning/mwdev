<?php
$line=<<<HERE
<div class="inp-categ-wrap{$root_class}">
	<div class="categ-block-wrap">
		<div class="inp-categ-block">
			<span category_id="{$cat_id}">{$name}</span> <a href="javascript:;" class="select-arrow"></a>
			<input type="hidden" class="cat_ids" value="{$cat_id}" name="category[]">
			<div class="clear">
			</div>
		</div>
	</div>
	{$tree}
	<a href="javascript:;" class="{$button_classes}"></a>
	<div class="clear">
	</div>
</div>

HERE;


?>