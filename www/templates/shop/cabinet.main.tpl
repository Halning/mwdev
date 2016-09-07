<div class="profile-container profile-container-all">
    <div class="content clearfix row">
        <div class="namehead col-md-12 col-md-offset-0 col-sm-10 col-sm-offset-1 col-xs-8 col-xs-offset-2">
            <p>{$userFirstName} {$userLastName}
                <span>ID: {$userId}</span>
            </p>
        </div>
            
        <div class="col-lg-7 col-lg-offset-0 col-md-7 col-md-offset-0 col-sm-10 col-sm-offset-1 col-xs-12 row-block">
            <div class="profile-title">ЛИЧНАЯ Информация</div>
            <div class="custom-border">
                <div class="avatar">
                    <a href=""><img src="/templates/shop/image/profile_imgs/glavnaja_ava.jpg" alt=""></a>
                </div>
                <div class="inform-block">
                    <fieldset disabled>
                        <div class="input_block">
                            <label for="email">Почта:</label>
                            <input type="text" name="email" value="{$userEmail}">
                        </div>
                    </fieldset>
                    <fieldset disabled>
                        <div class="input_block ">
                            <label for="phone">Телефон: </label>
                            <input type="text"  name="phone" value="{$userPhone}">
                        </div>
                    </fieldset>
                    <fieldset disabled>
                        <div class="input_block">
                            <label for="city">Город:</label>
                            <input type="text" name="city" value="{$userCity}">
                        </div>
                    </fieldset>
                    <fieldset disabled>
                        <div class="input_block">
                            <label for="address">Адрес:</label>
                            <input type="text" name="address" value="{$userAddress}">
                        </div>
                    </fieldset>    
                    <!--<div class="input_block">
                        <label for="delivery_method">Способ доставки:</label>
                        <input type="text"  name="delivery_method">
                    </div>  
                    <div class="input_block">
                        <label for="warehouse-number">Номер склада/адрес:</label>
                        <input type="text"  name="warehouse-number">
                    </div>--> 
                </div>
            </div>
        </div>
                        
        <div class="col-lg-5 col-lg-offset-0 col-md-5 col-md-offset-0 col-sm-10 col-sm-offset-1 col-xs-12 row-block">
            <div class="profile-title">ПРИВЯЗКА СОЦИАЛЬНЫХ СЕТЕЙ</div>
            <div class="custom-border">
                <div class="inform_block inform-social">
                    <p class="social-links-text bg-color">Привяжите учётную запись социальной сети для авторизации</p>
                    <p>
                        <a href="" class="social-vkl"><img  src="/templates/shop/image/profile_imgs/fb_ico.png" alt=""><b>Феисбук </br><i>отключить</i></b></a>
                        <a href="" class="social-vkl"><img src="/templates/shop/image/profile_imgs/ok_ico.png" alt=""><b>Одноклассники</br><i>отключить</i></b></a>
                        <a href="" class="social-vkl"><img src="/templates/shop/image/profile_imgs/vk_ico.png" alt=""><b>Вконтакте</br><i>отключить</i></b></a>
                        <!--<a href="" class="social-vkl"><img src="/templates/shop/image/profile_imgs/inst.png" alt=""><b>Инстанграм</br><i>отключить</i></b></a>-->
                        <a href="" class="social-vkl"><img src="/templates/shop/image/profile_imgs/g+.png" alt=""><b>Гугл плюс</br><i>отключить</i></b></a>
                        <!--<a href="" class="social-vkl"><img src="/templates/shop/image/profile_imgs/yan.png" alt=""><b>Яндекс</br><i>отключить</i></b></a>-->
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="content table">
        <div class="content_block glavnaja">
            <article class="full">
                <div class="profile-title">Последний заказ</div>
                <div class="inform border">
                    <div class="inform_block">
                        <p>
                            <span>№ Заказа</span>
                            <span>Дата</span>
                            <span>Единиц</span>
                            <span>Сумма</span>
                            <span>Статус</span>
                        </p>
                        <div class="table-block table-block-active">
                            <div class="gray">
                                <div class="items click-arrow">
                                    <img src="/templates/shop/image/profile_imgs/arrow_down.png" style="display:none;" class="gray-arrow-down" height="8" width="10" alt="">
                                    <img src="/templates/shop/image/profile_imgs/arrow.png"  class="gray-arrow-up" height="8" width="10" alt="">

                                </div>
                                <div class="items">
                                    654984
                                </div>
                                <div class="items">
                                    2015-04-25
                                </div>
                                <div class="items">
                                    9
                                </div>
                                <div class="items">
                                    2070
                                </div>
                                <div class="items send-person">
                                    Отправлен получателю
                                </div>
                                <div class="items item_hov">
                                    Все детали заказа
                                    <img src="/templates/shop/image/profile_imgs/arrow.png" style="display:none" class="arrow-up" height="6" width="8" alt="">
                                    <img src="/templates/shop/image/profile_imgs/arrow_down.png"  class="arrow-down" height="6" width="8" alt="">

                                    <div class="hide_hov">
                                        <div class="hide_hov_border">
                                            <i><span>Заказ №:</span>726/1</i>
                                            <i><span>Заказ №:</span>Оксана</i>
                                            <i><span>Заказ №:</span>Оксана</i>
                                            <i><span>Заказ №:</span>Оксана</i>
                                            <i><span>Заказ №:</span>Оксана</i>
                                            <i><span>Заказ №:</span>Оксана</i>
                                            <i><span>Заказ №:</span>Оксана</i>
                                            <i><span>Заказ №:</span>Оксана</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-block">
                                <div class="rte rte-first">
                                    <table class="col-md-12 table-bordered table-striped table-condensed" cellspacing="0">
                                        <thead class="clearfix">
                                            <tr>
                                                <th>Фото</th>
                                                <th>Бренд</th>
                                                <th >Артикул</th>
                                                <th >Цвет</th>
                                                <th >Размер</th>
                                                <th >Количество</th>
                                                <th >Валюта</th>
                                                <th >Цена</th>
                                                <th >Сумма</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><img src="/templates/shop/image/profile_imgs/tbl_img.jpg" alt=""></td>
                                                <td>SwirlBySwirl</td>
                                                <td>$1.38</td>
                                                <td>-0.01</td>
                                                <td>-0.36%</td>
                                                <td>$1.39</td>
                                                <td>$1.39</td>
                                                <td>$1.38</td>
                                                <td>9,395</td>
                                            </tr>
                                            <tr>
                                                <td><img src="/templates/shop/image/profile_imgs/tbl_img.jpg" alt=""></td>
                                                <td>SwirlBySwirl</td>
                                                <td>$1.15</td>
                                                <td>+0.02</td>
                                                <td>1.32%</td>
                                                <td>$1.14</td>
                                                <td>$1.15</td>
                                                <td>$1.13</td>
                                                <td>56,431</td>
                                            </tr>



                                        </tbody>
                                    </table>

                                </div>
                                <div class="rte rte-second hide-product">
                                    <table class="col-md-12 table-bordered table-striped table-condensed" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td><img src="/templates/shop/image/profile_imgs/tbl_img.jpg" alt=""></td>
                                                <td>SwirlBySwirl</td>
                                                <td>$1.38</td>
                                                <td>-0.01</td>
                                                <td>-0.36%</td>
                                                <td>$1.39</td>
                                                <td>$1.39</td>
                                                <td>$1.38</td>
                                                <td>9,395</td>
                                            </tr>
                                            <tr>
                                                <td><img src="/templates/shop/image/profile_imgs/tbl_img.jpg" alt=""></td>
                                                <td>SwirlBySwirl</td>
                                                <td>$1.15</td>
                                                <td>+0.02</td>
                                                <td>1.32%</td>
                                                <td>$1.14</td>
                                                <td>$1.15</td>
                                                <td>$1.13</td>
                                                <td>56,431</td>
                                            </tr>

                                            <tr>
                                                <td><img src="/templates/shop/image/profile_imgs/tbl_img.jpg" alt=""></td>
                                                <td>SwirlBySwirl</td>
                                                <td>$1.15</td>
                                                <td>+0.02</td>
                                                <td>1.32%</td>
                                                <td>$1.14</td>
                                                <td>$1.15</td>
                                                <td>$1.13</td>
                                                <td>56,431</td>
                                            </tr>


                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="see-more-link">
                        <div class="see-more-1">
                            <p>Смотреть все товары</p>
                            <img src="/templates/shop/image/profile_imgs/arrow_down.png" height="6" width="8" alt="">
                        </div>
                        <div class="see-more-2">	
                            <p>Скрыть все товары</p>
                            <img src="/templates/shop/image/profile_imgs/arrow.png" height="6" width="8" alt="">
                        </div>	
                    </div>
                </div>

            </article>

        </div>
    </div>
    <div class="content sliders">
        <div class="content_block glavnaja row">
            <article class="col-md-6">
                <div class="profile-title slider-title">новости</div>
                <div class="inform border">
                    <div class="inform_block ">
                        <p class="slider-text">Следите за обновлениями на сайте</p>
                        <div class="slider slider_left">
                            <div class="slide_cnt">
                                <div class="img_block">
                                    <a href=""><img src="/templates/shop/image/profile_imgs/slide_sma_img.jpg" alt=""></a>
                                    <div class="brand_name">GLEM</div>
                                    <div class="brand_ab">новый бренд</div>
                                    <div class="brand_more">Посмотреть<span>&nbsp&nbsp>>></span></div>
                                </div>

                            </div>
                            <div class="slide_cnt">
                                <div class="img_block">
                                    <a href=""><img src="/templates/shop/image/profile_imgs/slide_img.jpg" alt=""></a>
                                    <span class="otzyv otzyv_left"><b>480грн</b>320грн</span>
                                </div>
                                <div class="desc_block">
                                    <b>SK HOUSE</b>
                                    <b>Сарафан</b>
                                    <b>PL-1136F</b>
                                </div>
                            </div>
                            <div class="slide_cnt">
                                <div class="img_block">
                                    <a href=""><img src="/templates/shop/image/profile_imgs/slide_sma_img.jpg" alt=""></a>
                                    <div class="brand_name">GLEM</div>
                                    <div class="brand_ab">новый бренд</div>
                                    <div class="brand_more">Посмотреть<span>&nbsp&nbsp>>></span></div>
                                </div>

                            </div>
                            <div class="slide_cnt">
                                <div class="img_block">
                                    <a href=""><img src="/templates/shop/image/profile_imgs/slide_img.jpg" alt=""></a>
                                    <span class="otzyv otzyv_left"><b>480грн</b>320грн</span>
                                </div>
                                <div class="desc_block">
                                    <b>SK HOUSE</b>
                                    <b>Сарафан</b>
                                    <b>PL-1136F</b>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </article>
            <article class="col-md-6">
                <div class="profile-title slider-title">Оставьте свой отзыв</div>
                <div class="inform border">
                    <div class="inform_block">
                        <p class="slider-text">Сделайте наш сервис еще лучше</p>
                        <div class="slider slider_right">
                            <div class="slide_cnt">
                                <div class="img_block">
                                    <a href=""><img src="/templates/shop/image/profile_imgs/slide_img.jpg" alt=""></a>
                                    <span class="otzyv">Оставить отзыв</span>
                                </div>
                                <div class="desc_block">
                                    <b>SK HOUSE</b>
                                    <b>Сарафан</b>
                                    <b>PL-1136F</b>
                                </div>
                            </div>
                            <div class="slide_cnt">
                                <div class="img_block">
                                    <a href=""><img src="/templates/shop/image/profile_imgs/slide_img.jpg" alt=""></a>
                                    <span class="otzyv">Оставить отзыв</span>
                                </div>
                                <div class="desc_block">
                                    <b>SK HOUSE</b>
                                    <b>Сарафан</b>
                                    <b>PL-1136F</b>
                                </div>
                            </div>
                            <div class="slide_cnt">
                                <div class="img_block">
                                    <a href=""><img src="/templates/shop/image/profile_imgs/slide_img.jpg" alt=""></a>
                                    <span class="otzyv">Оставить отзыв</span>
                                </div>
                                <div class="desc_block">
                                    <b>SK HOUSE</b>
                                    <b>Сарафан</b>
                                    <b>PL-1136F</b>
                                </div>
                            </div>
                            <div class="slide_cnt">
                                <div class="img_block">
                                    <a href=""><img src="/templates/shop/image/profile_imgs/slide_img.jpg" alt=""></a>
                                    <span class="otzyv">Оставить отзыв</span>
                                </div>
                                <div class="desc_block">
                                    <b>SK HOUSE</b>
                                    <b>Сарафан</b>
                                    <b>PL-1136F</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </article>
        </div>
    </div>-->
    <div class="content">                           
        <div class="recomended-wrap">
            <div class="recomended-title">
                <h3><span class="active" data-slider="recomended">Рекомендации</span><span data-slider="last-seeing">Недавно просмотренные</span></h3>
            </div>
            <div class="top-slider profil-slider in-prod-page recomended">
                <!-- slider recomended-->
                {$sliderRecomended}
            </div>
            <div class="top-slider profil-slider in-prod-page last-seeing">
                {$sliderSeeing}
            </div>
        </div>
    </div>
            
    <div class="content">
        <div class="SEO-text">
            <p>Личный кабинет - это Ваше индивидуальное рабочее пространство на нашем сайте.</p>
            <p>Из Личного кабинета вы можете оперативно менять свои данные, следить за балансом счета,
                подключать и отключать дополнительные сервисы и, главное, безопасно управлять депозитом торгового счета.</p>
            <p>Работа с Личным кабинетом ведется по повышенным стандартам безопасности.</p>
            <p>Все данные шифруются и передаются по защищенному соединению, значит, вы можете быть уверены
                в конфиденциальности информации и сохранности средств.</p>
        </div>
    </div>		

</div>