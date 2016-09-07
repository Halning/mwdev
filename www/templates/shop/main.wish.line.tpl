<div class="item" id="item_wish{$id}" >
    <input type="checkbox" id="wishCheckbox{$counter}" class="wish_check" rel="{$id}" />
    <label for="wishCheckbox{$counter}">
        <div class="item__in">
            <div class="item__in__wrap clearfix">
                <div class="image">
                    <img src="{$photoDomain}{$id}/s_title.jpg" alt="" onerror="this.src='/templates/shop/image/nophotoproduct.jpg'">
                </div>
                <div class="info">
                    <p class="title">
                        {$catName}
                    </p>
                    <p class="name">
                        {$comName}
                    </p>
                </div>
                <div class="coast">
                    <p>
                        <span>{$price}</span> {$currency}
                    </p>
                </div>
            </div>
        </div>
    </label>
</div>