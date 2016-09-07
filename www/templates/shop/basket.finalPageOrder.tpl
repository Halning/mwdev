
<div id="final-basket-conteiner">
    <p class="p-strong" >Ваш заказ принят. Мы свяжемся с Вами в ближайшее время!</p>
    <hr>
    <div class="dl-conteiner">
        <p>Заказ номер:  {$orderCode}</p><br>
        <p class="p-strong" >Контактная информация:</p><br>
        <dl class="dl-horizontal">
            <dt>Имя:</dt>
            <dd>{$userName}</dd>
            <dt>Мобильный:</dt>
            <dd>{$userTel}</dd>
            <dt>Email:</dt>
            <dd>{$userEmail}</dd>
            <dt>Способ доставки:</dt>
            <dd>{$deliveryMethodName}</dd>
            <dt>Город:</dt>
            <dd>{$userCity}</dd>
            <dt>Адрес, склад:</dt>
            <dd>{$userAddress}</dd>
            <!--<dt>Комментарий:</dt>
            <dd>{$userComments}</dd>-->
            <dt>Дата заказа:</dt>
            <dd>{$orderDate}</dd>
        </dl>
    </div>
    <hr>
    <p class="p-strong">Подробности заказа:</p><br>
    <div class="table-res">
        <table class="basket-table table table-striped table-condensed table-bordered">
            <tr>
                <th>
                    <p class="basket-items-header">Фото </p>
                </th>
                <th>
                    <p class="basket-items-header">Бренд</p>
                </th>
                <th>
                    <p class="basket-items-header">Название</p>
                </th>
                <th>
                    <p class="basket-items-header">Артикул</p>
                </th>
                <th>
                    <p class="basket-items-header">Цвет</p>
                </th>
                <th>
                    <p class="basket-items-header">Размер</p>
                </th>
                <th>
                    <p class="basket-items-header">Количество</p>
                </th>
                <th>
                    <p class="basket-items-header">Цена</p>
                </th>
                <th>
                    <p class="basket-items-header">Сумма</p>
                </th>
            </tr>
            {$linesFinalOrder}
        </table>
    </div>
    <hr>
    <table class="table-final-result table table-bordered table-striped">
        <tr>
            <td> 
                <span>Итого</span>
            </td>
            <td> 
                <span>{$comSumPrice}<span></span> {$cur_show}</span>
            </td>
        </tr> 
        <tr>
            <td> 
                <span>Стоимость доставки</span>
            </td>
            <td> 
                <span>{$deliveryPrice}<span></span> {$cur_show}</span>
            </td>
        </tr>    
        <!--<tr>     
            <td>                       
                <span class="commision" title="">Комиссия (3%)<i>*</i></span>
            </td>
            <td>
                <span id="commision">{$commisionPrice}<span></span> {$cur_show}</span>
            </td>
        </tr>-->
        <tr {$hidden}>     
            <td>                       
                <span>Подарок</span>
            </td>
            <td>
                <span id="discountGift">{$discountGift}<span></span> {$cur_show}</span>
            </td>
        </tr>
        <tr> 
            <td> 
                <span class="result-order">Итого к оплате</span>
            </td>
            <td>
                <span class="result-order" id='full-price'>{$totalSumm}<span></span> {$cur_show}</span>
            </td>
        </tr>
    </table>
</div>