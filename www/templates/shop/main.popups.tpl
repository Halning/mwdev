<!--  Popups  -->
<!--  Popup  Квадратный чек  -->
<div class="super_nav_1 good">
    <div class="super_seting">
        <ul>
            <li><a class="choose-all">Выделить все</a></li>
            <li><a class="clear-all">Очистить все</a></li>
            <li><a href="#" class="btn-green btn-3d">Применить</a></li>
        </ul>
    </div>
    <div class="item-wrap"></div>
</div>

<!--  review popup  -->
<div id="writeReview" class="mfp-with-anim mfp-hide write-review-popup">
    <div class="write-review">
        <div class="tab-head">
            <span>Оставьте отзыв о товаре</span>
            <ul class="rating" data-type="com">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <form action="" class="coments-form" role="form">
            <div class="form-group">
                <textarea name="coment" class="form-control comment-field comment-necessary" maxlength="300" placeholder="Комментарий *"></textarea>
            </div>
            <div class="form-group">
                <textarea name="dignity" class="form-control comment-field dig-lim" maxlength="200" placeholder="Достоинства"></textarea>
            </div>
            <div class="form-group">
                <textarea name="limitations" class="form-control comment-field dig-lim" maxlength="200" placeholder="Недостатки"></textarea>
            </div>    

            <div class="input-area">
                <div class="form-group">
                    <input type="text" class="form-control comment-field comment-necessary" id="comment-user-name"  value="{$userName}" maxlength="20" placeholder="Имя *">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control comment-field comment-necessary" id="comment-user-email" value="{$userEmail}" name="email" placeholder="Email *">
                </div>
            </div>    
            <div class="submit">
                <button class="btn btn-lg btn-block btn-primary btn-coments-form" disabled="disabled" value="">Оставить отзыв</button>
                <!--<input type="checkbox">
                <p>Уведомить об ответах по e-mail</p>-->
            </div>
        </form>
    </div>
</div> 

<!--  add to cart popup popup  -->
<div id="addToCartPopup" class="add-to-cart-popup mfp-with-anim mfp-hide">
    <div class="custom-border">
        <div class="popup-title"><p>Добавлено в мою корзину</p></div>
        <div class="d-table">
            <div class="d-table-row">
                <div class="d-table-cell">
                    <p class="item-title">
                        swirl by swirl
                    </p>
                    <span>купальник "Polo Style"</span>
                </div>
                <div class="d-table-cell">
                    <b>10</b><i>шт</i>
                </div>
                <div class="d-table-cell">
                    <b>1250</b><i>{$cur_v}</i>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-green btn-3d">Ок</button>             
        <a href='' class='btn btn-green btn-3d issue pjax-basket'>Оформление заказа</a>               
        <div class="clear"></div>
    </div>
</div>

<!--  fast order popup  -->
<div id="fastOrder" class="mfp-with-anim mfp-hide">
    <div class="order-wrap custom-border row">
        <p id="fast-order-title">
            быстрый заказ
        </p>
        <form action="" class="fast-order-form col-md-6 col-sm-6 col-xs-6 clearfix" role="form">
            <div class="country-selector">
                <span>Укажите страну</span><br>
                <div id="fast-order-country-radio">
                    <input type="radio" name = "fast_order_country" value = "1" checked class="fast-order-country" id = "fast-order-country-radio1"><label for="fast-order-country-radio1">Украина</label>
                    <input type="radio" name = "fast_order_country" value = "2" class="fast-order-country" id = "fast-order-country-radio2"><label for="fast-order-country-radio2">Россия</label>
                </div>
            </div>

            <div class="form-group">
                <input type="email" name="fast_order_email" class="form-control fast-order-field fast-order-necessary" placeholder="Email *" value="{$userEmail}"/>                              
            </div>
            <div class="form-group">
                <input type="text" name="fast_order_name" class="form-control fast-order-field fast-order-necessary" maxlength="70" value="{$userName}" placeholder="Имя и Фамилия *"/>
            </div>
            <div class="form-group">
                <input type="text" name="fast_order_city" class="form-control fast-order-field" maxlength="50" placeholder="Город"/>
            </div>
            <div class="form-group">
                <input type="tel" name="fast_order_phone" id="fast-order-phone" class="form-control fast-order-field fast-order-necessary" maxlength="20" placeholder="Мобильный телефон *"/>
            </div>
            <hr>
            <select data-placeholder="Способ доставки *" name="fast_order_delivery_method" class="fast-order-field fast-order-necessary" id="fast-order-delivery-method">
                <option></option>
            </select>
            <div class="form-group">
                <input type="text" name="fast_order_address" class="form-control fast-order-field" maxlength="100" placeholder="Номер склада и улица"/>
            </div>
            <p id="fast-order-comment">Добавить комментарий к заказу</p>
            <div class="form-group" style="display: none">
                <textarea name="fast_order_comment" class="form-control fast-order-field" maxlength="200"></textarea>
            </div>
            <div id="fast-order-submit">
                <button class="btn btn-green btn-3d" id="fast-order-btn" disabled="disabled">ОФОРМИТЬ</button>
            </div>
        </form>
        <div id="fast-order-product" class="col-md-6 col-sm-6 col-xs-6 clearfix"> 
            <img id="fast-order-image" src="" alt="" onerror="this.src='/templates/shop/image/nophotoproduct.jpg'">
            <hr>
            <p id="fast-order-brend" class="title text-left"></p>
            <p id="fast-order-name" class="text-left"></p>
            <hr>
            <p id="fast-order-size" class="text-left">Размер: <span></span></p>
            <p id="fast-order-color" class="text-left">Цвет: <span></span></p>
            <hr>
            <span id="fast-order-count"><span></span> ед.</span>
            <span id="fast-order-price"><b></b> {$cur_v}</span>
        </div>  
    </div>
</div>

<!--  my favourite popup   -->
<div id="wishesPopup" class="popup-wish mfp-with-anim mfp-hide">
    <div class="custom-border">
        <div class="popup-title"><p>Добавлено в мои желания</p></div>
        <span class="message">Поделиться своими желаниями в социальных сетях</span>

        <div class="d-table">
            <div class="d-table-cell">
                <div class="social-share">
                    <script type="text/javascript" src="https://yastatic.net/share/share.js"
                    charset="utf-8"></script>
                    <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none"
                         data-yashareQuickServices="vkontakte,facebook,odnoklassniki,gplus"></div>
                </div>
            </div>
            <div class="d-table-cell">
                <button type="submit" class="btn btn-green btn-3d">Ок</button>
            </div>
        </div>
    </div>
</div> 
