<tr class="{$commodityId}">
    <td>
        <a href='{$commodityUrl}' target='_blank'>
            <img src='{$Imgsrc}' class='basket-img' alt="product-img">
        </a>
        
    </td>
    <td>
        <p>{$brandName}</p>
    </td>
    <td> 
        <p>{$commodityName}</p>
        <p class="string-gift" title=" " style="cursor: help">{$stringGift}</p>
    </td>
    <td>
        <p>{$commodityCod}</p>
    </td>
    <td>
        <p><span>{$commodityColor}</span></p>
    </td>
    <td>
        <p><span>{$commoditySize}</span></p>
    </td>
    <td>
        <div class="basket-counter-group">
            <div class="basket-minus-btn" name="{$commodityId}"></div>
            <input type="number" class='basket-com-count' id="{$commodityId}" value='{$commodityCount}' data-count= "{$commodityCount}"/>
            <div class="basket-plus-btn" name="{$commodityId}"></div>
        </div>
    </td>
    <td>
        <p id="price-{$commodityId}">{$commodityPrice} <i id="currency">{$curSelect}</i></p>
    </td>
    <td>
        <strike><p id="total-price__old--{$commodityId}" style="color: red"{$SumOldPriceHidden}>{$commoditySumOldPrice} <i>{$curSelect}</i></p></strike>
        <p class="total-price" id="sum-price-{$commodityId}">{$commoditySumPrice} <i>{$curSelect}</i></p> 
    </td>
    <td id ="delete-laber">
        <span class="glyphicon glyphicon-remove basket-com-delete"></span>
    </td>
</tr>

