<div class="profile-payment-container profile-container-all">
    <div class="content">
        <div class="content_block">
            <article>
                <div class="title">ОПОВЕЩЕНИЕ ОБ ОПЛАТЕ</div>
                <div class="inform_wrapper">
                    <div class="inform inform_long">
                        <div class="inform_block">
                            <div class="input_block form-group">
                                <label for="payment-date">ВРЕМЯ ОПЛАТЫ ПО ЧЕКУ: </label>
                                <div class="payment-time-wrapper">
                                    <input type="text" class="form-control payment-date" name="payment-date">
                                    <input type="text" class="form-control payment-time-hour" name="payment-time-hour">
                                    :
                                    <input type="text" class="form-control payment-time-minute">
                                </div>
                            </div>
                            <div class="input_block form-group">
                                <label for="payment-method">СПОСОБ ОПЛАТЫ: </label>
                                <div class="payment-method-wrapper">
                                    <select data-placeholder="Способ оплаты" id='profile-payment-method' name='payment-method' data-payment-method="{$userPaymentMethod}">
                                        <option>Способ оплаты</option>
                                        <option value="1">Карта Приватбанка</option>
                                        <option value="2">Наличные</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input_block form-group">
                                <label for="sum">СУММА:</label>
                                <input type="text" class="form-control" name="sum">
                            </div>
                            <div class="input_block form-group payment-comment-block">
                                <label for="payment-comment">КОММЕНТАРИЙ:</label>
                                <input type="text" class="form-control" id="payment-comment" name="payment-comment">
                            </div>
                            <button type="submit" class="btn btn-primary" id="payment-time-btn" data-loading-text="Loading..." disabled="disabled">ОТПРАВИТЬ</button>
                        </div>
                    </div>
                </div>
            </article>                  
            <article>
                <div class="title">ЗАКАЗЫ</div>
                <div class="inform_wrapper">
                    <div class="inform inform_long">
                        <div class="inform_block">
                            <table class="payment-orders-table">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>№ ЗАКАЗА</td>
                                        <td>ВАЛЮТА</td>
                                        <td>СУММА</td>
                                        <td>ДАТА</td>
                                    </tr>
                                    <tr>
                                        <td><input type="radio" name="orders"></td>
                                        <td>42345</td>
                                        <td>UAN</td>
                                        <td>5448.00</td>
                                        <td>16.02.2016</td>
                                    </tr>
                                    <tr>
                                        <td><input type="radio" name="orders"></td>
                                        <td>42660</td>
                                        <td>UAN</td>
                                        <td>670.00</td>
                                        <td>25.02.2016</td>
                                    </tr>
                                    <tr>
                                        <td><input type="radio" name="orders"></td>
                                        <td>43022</td>
                                        <td>UAN</td>
                                        <td>1880.00</td>
                                        <td>04.02.2016</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </article>
            <article class="article-with-table">
                <div class="title">АКТИВНЫЕ ЗАКАЗЫ</div>
                <div class="inform_wrapper">
                    <div class="inform inform_long">
                        <div class="inform_block">
                            <table class="payment-active-orders-table">
                                    <tr>
                                        <td>ID</td>
                                        <td>ДАТА ЗАКАЗА</td>
                                        <td>ДАТА ОПЛАТЫ</td>
                                        <td>ВАЛЮТА</td>
                                        <td>ЕДИНИЦ</td>
                                        <td>СУММА</td>
                                        <td>СТАТУС</td>
                                        <td>К</td>
                                    </tr>
                                    <tr>
                                        <td>3586</td>
                                        <td>02.02.2016 12:02</td>
                                        <td>11.02.2015 13:56</td>
                                        <td>UAN</td>
                                        <td>8</td>
                                        <td>350.00</td>
                                        <td>Оплачено</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>4567</td>
                                        <td>15.02.2016 13:34</td>
                                        <td></td>
                                        <td>UAN</td>
                                        <td>1</td>
                                        <td>560.00</td>
                                        <td>Проверка</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>4234</td>
                                        <td>03.03.2016 19:46</td>
                                        <td></td>
                                        <td>USD</td>
                                        <td>2</td>
                                        <td>68.00</td>
                                        <td>Ожидает оплаты</td>
                                        <td>2</td>
                                    </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </article>
            <article class="article-with-table">
                <div class="title">ОПОВЕЩЕНИЯ ОБ ОПЛАТЕ (<span class="notif-amount"></span>)</div>
                <div class="inform_wrapper">
                    <div class="inform inform_long">
                        <div class="inform_block">
                            <table class="payment-notifications-table">
                                    <tr>
                                        <td>ID</td>
                                        <td>ДАТА ЗАКАЗА</td>
                                        <td>ДАТА ОПЛАТЫ</td>
                                        <td>ВАЛЮТА</td>
                                        <td>ЕДИНИЦ</td>
                                        <td>СУММА</td>
                                        <td>СТАТУС</td>
                                        <td>К</td>
                                    </tr>
                                    <tr>
                                        <td>3586</td>
                                        <td>02.02.2016 12:02</td>
                                        <td>11.02.2015 13:56</td>
                                        <td>UAN</td>
                                        <td>8</td>
                                        <td>350.00</td>
                                        <td>Оплачено</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>4567</td>
                                        <td>15.02.2016 13:34</td>
                                        <td></td>
                                        <td>UAN</td>
                                        <td>1</td>
                                        <td>560.00</td>
                                        <td>Проверка</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>4234</td>
                                        <td>03.03.2016 19:46</td>
                                        <td></td>
                                        <td>USD</td>
                                        <td>2</td>
                                        <td>68.00</td>
                                        <td>Ожидает оплаты</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>1258</td>
                                        <td>09.03.2016 11:36</td>
                                        <td>14.03.2015 9:22</td>
                                        <td>UAN</td>
                                        <td>4</td>
                                        <td>880.00</td>
                                        <td>Оплачено</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>2234</td>
                                        <td>12.03.2016 10:28</td>
                                        <td>15.03.2015 17:44</td>
                                        <td>UAN</td>
                                        <td>3</td>
                                        <td>589.00</td>
                                        <td>Оплачено</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>3048</td>
                                        <td>16.03.2016 20:45</td>
                                        <td>16.03.2015 21:04</td>
                                        <td>UAN</td>
                                        <td>1</td>
                                        <td>1449.00</td>
                                        <td>Оплачено</td>
                                        <td>1</td>
                                    </tr>
                            </table>
                        </div>
                        <p class="show-all-notif">Показать все оповещения...</p>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>
