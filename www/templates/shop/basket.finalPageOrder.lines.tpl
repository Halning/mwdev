<tr>
    <td>
        <a href='{$comUrl}' target='_blank'><img src='{$Imgsrc}' class='basket-img' /></a>
    </td>
    <td>
        <p>{$catName}</p>
    </td>
    <td>
        <p>{$comName}</p>
    </td>
    <td>
        <p>{$comCod}</p>
    </td>
    <td>
        <p><span>{$comColor}</span></p>
    </td>
    <td>
        <p><span>{$comSize}</span></p>
    </td>
    <td>
        <p><span>{$comCount}</span></p>
    </td>
    <td>
        <p id="price-{$comId}">{$comPrice} <i id="currency">{$curSelect}</i></p>
    </td>
    <td>
        <strike><p style="color: red"{$SumOldPriceHidden}>{$commoditySumOldPrice} <i>{$curSelect}</i></p></strike>
        <p class="total-price" id="sum-price-{$comId}">{$comSumPrice} <i>{$curSelect}</i></p> 
    </td>
</tr>

