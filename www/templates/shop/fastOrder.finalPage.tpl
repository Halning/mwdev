<div id="confirmation-page-conteiner">
    <p class="p-strong" >Ваш заказ принят. Вам на почту отправленно письмо с деталями</p>
    <hr>
    <div class="dl-conteiner">
        <p>Контактная информация:</p><br>
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
            <dt>Адрес:</dt>
            <dd>{$userAddress}</dd>
            <!--<dt>Комментарий:</dt>
            <dd>{$userComments}</dd>-->
            <dt>Дата заказа:</dt>
            <dd>{$orderDate}</dd>
        </dl>
    </div>
    <hr>
    <table class="table table-bordered table-striped">
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
        <tr>     
            <td>                       
                <span class="fasr-order-commision" title="">Комиссия (3%)<i>*</i></span>
            </td>
            <td>
                <span>{$commisionPrice}<span></span> {$cur_show}</span>
            </td>
        </tr>
        <tr> 
            <td> 
                <span class="result-order">Итого к оплате</span>
            </td>
            <td>
                <span class="result-order">{$totalSumm}<span></span> {$cur_show}</span>
            </td>
        </tr>
    </table>     
</div>