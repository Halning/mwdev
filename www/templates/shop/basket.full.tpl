<!--main-container-->
<div class="main-container">
    <!--main-content-->
    <div class="main-content row main-width">       
        <div class="col-md-12 col-sm-12 col-xs-12 basket-conteiner">
            <div class="table-res">
            <table class='basket-table table table-striped table-bordered' data-plgift="{$giftToken}">
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
                        <p class="basket-items-header" id="th-count" title="">Количество<i>*</i></p>
                    </th>
                    <th> 
                        <p class="basket-items-header">Цена</p>
                    </th>										
                    <th> 
                        <p class="basket-items-header">Сумма</p>
                    </th>
                    <th id = "delete-laber"></th>
                </tr>
                {$basketFullLines}
                <tr>
                    <td><span>Итого:</span></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><span value='{$commodityTotalCount}' id ="res-num">{$commodityTotalCount}</span></td>
                    <td></td>
                    <td><span value='{$totalSumm}' id ="res-sum">{$totalSumm} <nobr>{$cur_show}</nobr></span></td>
                </tr>
            </table>
            </div>
            <button type="submit" class="btn btn-lg btn-primary submit-show" onclick="yaCounter28493466.reachGoal('toOrder'); return true;">Оформить</button>        
            <button type="submit" class="btn btn-lg submit-clear-all pull-right">Очистить корзину</button>
            <hr>
            <div>
                <form action="#" class="basket-form" role="form">
                    <div class="country-selector">
                        <span>Выберите страну:</span><br>
                        <div id="select-country-radio">
                            <input type="radio" name = "country" value = "1" {$countryChecked1} id = "select-country-radio1"><label for="select-country-radio1">Украина</label>
                            <input type="radio" name = "country" value = "2" {$countryChecked2} id = "select-country-radio2"><label for="select-country-radio2">Россия</label>
                        </div>
                    </div>

                    <input name='basket' value='sendOrder' class='form-control hidden'>
                    <div class="form-group">
                        <input name='basket_user_name' value='{$basketUserName}' class='form-control cl-necessary cl-basket-field' maxlength="70" placeholder="Имя и фамилия*" >
                        <span class="glyphicon glyphicon-ok form-control-feedback hidden"></span>
                    </div> 
                    <div class="form-group">    
                        <input name='basket_user_tel' value='{$basketUserTel}' id="phone" class='form-control cl-necessary cl-basket-field' placeholder="Контактый телефон *" >
                        <span class="glyphicon glyphicon-ok form-control-feedback hidden"></span>
                    </div>
                    <div class="form-group"> 
                        <input type="email" name='basket_user_email' value='{$basketUserEmail}' class='form-control cl-necessary cl-basket-field' placeholder="Email *">
                        <span class="glyphicon glyphicon-ok form-control-feedback hidden"></span>
                    </div>
                    <div class="form-group">    
                        <input name='basket_user_city' id="autocomplete_city" class='form-control cl-basket-field' value='{$basketUserCity}' placeholder="Город">
                        <span class="glyphicon glyphicon-ok form-control-feedback hidden"></span>
                    </div>
                    <select data-placeholder="Способ доставки товара *" data-delivery="{$basketUserDelivery}" name='basket_delivery_method' class='cl-necessary cl-basket-field' id='id-delivery-method'>
                        <option></option>
                    </select>
                    <span class="control-hide">    
                        <select data-placeholder="Номер склада и улица *" name='basket_user_warehouse' id='autocom_warehouse' class='cl-basket-field war-adr-select'>
                            <option></option>
                        </select> 
                    </span>
                    <div class="form-group control-hide">    
                        <input name='basket_user_warehouse' id="write_warehouse" class='form-control cl-basket-field war-adr-select' value='{$basketUserWarehouse}' maxlength="100" placeholder="Номер склада и улица *">
                        <span class="glyphicon glyphicon-ok form-control-feedback hidden"></span>
                    </div>
                    <div class="form-group control-hide">    
                        <input name='basket_user_warehouse' id="autocom_adres" class='form-control cl-basket-field war-adr-select' value='{$basketUserAddress}' maxlength="100" placeholder="Адрес доставки *">
                        <span class="glyphicon glyphicon-ok form-control-feedback hidden"></span>
                    </div> 
                    <div class="form-group">      
                        <textarea name='basket_user_comments' value='' rows="5" class='form-control textarea cl-basket-field' maxlength="300" placeholder="Примечание к заказу">{$basketUserComments}</textarea>
                    </div> 
                    <hr> 
                    <table class="table table-bordered">
                        <tr>
                            <td> 
                                <span>Стоимость доставки</span>
                            </td>
                            <td> 
                                <span value="{$cur_show}" id="delivery-price"><span></span> {$cur_show}</span>
                            </td>
                        </tr>    
                        <!--<tr>     
                            <td>                       
                                <span class="commision" title="">Комиссия (3%)<i>*</i></span>
                            </td>
                            <td>
                                <span id="commision"><span></span> {$cur_show}</span>
                            </td>
                        </tr>-->
                        <tr class="hidden">     
                            <td>                       
                                <span>Подарок</span>
                            </td>
                            <td>
                                <span id="discountGift" data-val="{$discountGift}" data-exist="{$existAction}" data-gift="{$discountGift}"><span></span> {$cur_show}</span>
                            </td>
                        </tr>
                        <tr> 
                            <td> 
                                <span class="result-order">Итого к оплате</span>
                            </td>
                            <td>
                                <span class="result-order" id='full-price'><span></span> {$cur_show}</span>
                            </td>
                        </tr>
                    </table>
                     
                    <!-- pl gift -->
                    <input name="plGiftExist" value="{$giftToken}" class="hidden">
                    <!-- end -->
                    
                    <div id="check-nesesary__field">        
                        <button type="submit" class="btn btn-lg btn-block btn-primary btn-submit-order" disabled="disabled" onclick="yaCounter28493466.reachGoal('sucOrder'); return true;">Оформить заказ</button>
                    </div>    
                </form>
            </div>                      
        </div>
    </div>
    {$info}
</div>
<div id="white-popup" class="mfp-with-anim mfp-hide">
    <div class="custom-border-tooltip">
        Сообщение об ошибки корзины
    </div>
</div> 
    
<script type="text/javascript">
    var google_tag_params = {
        dynx_itemid: [{$idsForGTM}],
        dynx_pagetype: 'conversionintent',
        dynx_totalvalue: '{$totalSumm}',
    };
</script>