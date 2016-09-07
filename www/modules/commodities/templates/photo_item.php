<?php
$photo_item=<<<HERE
 <div class="photo_item" id="photo_item-{$photo_id}">
				<div class="photo_block"><a rel="photos_group" class="colorbox cboxElement" href="{$photo_src}"><img class="photo_image" src="{$small_photo_src}"></a><a href="javascript:del_photo('{$photo_id}')" class="upload_del_icon"></a></div>
				<input type="hidden" id="" class="id" value="{$photo_id}" name="photos[{$photo_id}][id]">
				<input type="hidden" id="" class="delete" value="0" name="photos[{$photo_id}][delete]">
				<input type="hidden" id="" class="order" value="{$order}" name="photos[{$photo_id}][order]">
				<span title="Этот блок можно перемещать" class="list_images sortable_but" name="photos[{$photo_id}][order]"></span>
				<div class="photo_input_desc">
				<input type="text"class="desc form_style" value="{$photo_name}"  name="photos[{$photo_id}][name]"><br>
				<textarea style='height:35px;' name="photos[{$photo_id}][desc]">{$photo_desc}</textarea>
				</div><br style="clear: both">
			</div>

HERE;

 
?>