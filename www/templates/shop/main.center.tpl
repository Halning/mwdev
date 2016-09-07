<!--main-container-->
<div class="main-container">
    <!--main-content-->
    <div class="main-content row main-width">   
        <!--baner Slider-->
    <div class="col-md-12 col-sm-12 col-xs-12" id="banner-slider" dir='rtl'>
        <div><img src="{$photoDomain}banners/main-page-banner_0005.jpg" alt="main-page-banner-5"></div>
        <div><img src="{$photoDomain}banners/main-page-banner_0003.jpg" alt="main-page-banner-3"></div>
        <!-- <div><img src="/templates/shop/image/baner_comment_slider/main-page-banner_0000.jpg" alt="main-page-banner-1"></div>
         <div><img src="/templates/shop/image/baner_comment_slider/main-page-banner_0001.jpg" alt="main-page-banner-2"></div>-->
    </div>
    <!-- end baner slider-->
        
        <div class="col-md-12 col-sm-12 col-xs-12 top-slider" style="min-height: 300px">
            <!--Product-Carusel-->
            {$hitBlock}
        </div>       
        
        <!-- share blocks -->
        <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0 5px;margin: 5px 0;">
            <img style="width: 100%;" src="{$photoDomain}banners/action_suggest.png">
        </div>
        
        <div class="col-md-12 col-sm-12 col-xs-12" id="share-conteiner">
            {$blockShares}
        </div>
        <!-- end share blocks-->

        <div class="col-md-12 col-sm-12 col-xs-12 brands">
            <div class="title clearfix d-table">
                <div class="line-brands-search">
                    <div class="line-brands-search-title">
                        <p>
                            БРЕНДЫ
                        </p>
                    </div>

                    <div class="line-brands-search-search">
                        <div class="brand-search">
                            <input type="text" placeholder="введите название бренда" />
                            <div class="search-btn">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div id="sea_txt" style="display: none;"></div>
                    </div>

                    <div class="line-brands-search-person">
                        <ul class="person">
                            <li>
                                <a href="javascript:butPerson('man')">MAN</a>
                            </li>
                            <li>
                                <a href="javascript:butPerson('woman')">WOMAN</a>
                            </li>
                            <li>
                                <a href="javascript:butPerson('kids')">KIDS</a>
                            </li>
                        </ul>
                    </div>

                    <div class="line-brands-search-all-icons"> 
                        <div style="display: table;">
                            <div style="display: table-cell;">
                                <div class="icon-star-circle"></div>
                            </div>
                            <div style="display: table-cell;">
                                <div class="icon-favorite"></div>
                            </div>
                        </div>
                    </div>
                    <div class="see-all-brands">
                        <a href="/c10-brendy&menu=brands/">Смотреть все бренды<span class="see-all-brands-arrows"></span></a>
                    </div>
                </div>
            </div>

            <div class="brands-category">
                <div class="brands-slider">
                    {$brandsSlider}

                    <!--Brand items-->

                </div>
            </div>

            <!-- pupup about brand -->
            <div id="aboutBrand" class="mfp-with-anim mfp-hide">
                <div class="text-wrap">
                </div>
            </div>
        </div>

        <!-- about company -->
        <div class="col-md-12 col-sm-12 col-xs-12 company-container main-company">
            <div class="payment-and-delivery-text">О компании</div>
            <div class="company-content">
                <div class="wrapper">
                    <div class="tab-content">
                        <div class="tab-item clearfix">
                            <div class="first_cnt">
                                <h4>MAKEWEAR - это альянс производителей одежды, обуви и аксессуаров.</h4>
                                <p>Лучшие фабрики и ателье объединили свои предложения на MakeWear, чтобы Вам стало проще найти необходимый товар и заказать его без наценок.<br>
                                   Впервые все основные производители Украины полноценно представлены на одном сайте.<br>
                                   Уникальные технические решения в сфере e-commerce определили неоспоримые преимущества MakeWear.</p>
                            </div>
                            <div class="second_cnt clearfix">
                                <div class="left_block">
                                    <img src="{$photoDomain}images/company_1.jpg" height="219" width="494" alt="">
                                </div>
                                <div class="right_block">
                                    <h3 class="title">УДОБСТВО</h3>
                                    <p>Работать с MakeWear удобно: закупайте любые<br>
                                    категории одежды, обуви и аксессуаров, не затрудняя себя взаимодействием со множеством поставщиков отдельных категорий и брендов. </p>
                                </div>
                            </div>
                            <div class="third_cnt clearfix">
                                <div class="left_block">
                                    <h3 class="title">Цена</h3>
                                    <p>Одним из главных преимуществ MakeWear являются цены, установленные непосредственно производителем, поэтому выбирая нас, вы не только получаете отличный сервис и широкий ассортимент, но и не теряете ни копейки на самой закупке товара.</p>
                                </div>
                                <div class="right_block">
                                    <img src="{$photoDomain}images/company_3.jpg" height="219" width="494" alt="">
                                </div>
                            </div>
                            <div class="four_cnt clearfix">
                                <div class="left_block">
                                    <img src="{$photoDomain}images/company_4.jpg" height="219" width="494" alt="">
                                </div>
                                <div class="right_block">
                                    <h3 class="title">Польза для СП</h3>
                                    <p>MakeWear ориентирован на продуктивное сотрудничество с Организаторами Совместных Покупок, для которых предусмотрены специальные условия и ряд функциональных возможностей сайта. Опт от 5 товаров разных брендов, выгрузка товарной базы и фото, парсинг товаров на любой сайт либо аккаунт в социальной сети – всё это для Организаторов СП!</p>
                                    <p>Для отдельных, наиболее результативных партнёров, MakeWear предоставляет индивидуальные условия сотрудничества, подстраиваясь под особенности работы и пожелания Организатора. Также, предоставляются дополнительные скидки, бонусы, более выгодные условия опта и доставки.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END about company -->

        <!-- comments-Slider -->
        <div class="col-md-12 col-sm-12 col-xs-12" id="comments-slider" dir='rtl'>
            <div><img  src="{$photoDomain}images/main-page-banner_0.jpg"></div>
            <div><img  src="{$photoDomain}images/main-page-banner_1.jpg"></div>
            <div><img  src="{$photoDomain}images/main-page-banner_2.jpg"></div>
        </div>
        <!-- end comments slider -->

        <!-- paymenth delivery -->
        <div class="col-md-12 col-sm-12 col-xs-12 oplata-delivery-container">
            <div class="payment-and-delivery-text">Оплата и доставка</div>
            <div class="oplata-delivery-content clearfix">
                <div class="oplata-delivery-content__heading">В интернет-магазине MakeWear Вы можете покупать товар как в розницу, так и оптом.</div>

                <p>Минимальный оптовый заказ составляет 5 единиц товара (различных брендов и категорий) для Украины и 10 единиц для России.<br>
                    Для того чтобы осуществить покупку, прежде всего, выберите наиболее подходящий для Вас товар. Определите
                    необходимое количество и цвет, после чего кликнув на кнопку «Добавить в корзину», Вы можете перейти к оформлению заказа.</p>

                <ul>
                    <li>- при необходимости скорректируйте данные в таблице заказа;</li>
                    <li>- нажмите кнопку &laquo;Оформить&raquo;;</li>
                    <li>- в появившуюся форму введите личные данные и уточните детали заказа;</li>
                    <li>- нажмите кнопку &laquo;Заказать&raquo;</li>
                </ul>

                <p>После того, как процесс оформления завершён, на указанный Вами почтовый ящик придет письмо с информацией о заказе, а в 
                    течение нескольких часов с Вами свяжется представитель компании MakeWear. В следующем письме Вы получите счёт с реквизитами для оплаты.</p>

                <div class="image-block__wrapper">
                    <div class="image-block">
                        <div class="img-cont">
                            <img id="flag-ukr" src="{$photoDomain}images/ukr.png" />
                            <img id="flag-pedo" src="{$photoDomain}images/rus.png" />
                            <img src="{$photoDomain}images/center_delivery.jpg" alt="" id="main">
                            <a href="https://www.privat24.ua/" target="_blank"><img src="{$photoDomain}images/privat.png" height="63" width="62" alt=""></a>
                            <a href="http://www.mastercard.com/" target="_blank"><img src="{$photoDomain}images/master.png" height="63" width="63" alt=""></a>
                            <a href="http://visa.com.ua/" target="_blank"><img src="{$photoDomain}images/visa.png" height="63" width="64" alt=""></a>
                            <a href="http://www.intime.ua/" target="_blank"><img src="{$photoDomain}images/intaim.png" height="64" width="64" alt=""></a>
                            <a href="http://novaposhta.ua" target="_blank"><img src="{$photoDomain}images/novaposta.png" height="63" width="65" alt=""></a>
                            <a href="http://www.ukrposhta.com/" target="_blank"><img src="{$photoDomain}images/ua_post.png" height="64" width="64" alt=""></a>
                            <a href="http://www.perevod-korona.com/" target="_blank"><img src="{$photoDomain}images/king.png" height="64" width="63" alt=""></a>
                            <a href="https://www.sberbank.ru/" target="_blank"><img src="{$photoDomain}images/sberbank.png" height="63" width="65" alt=""></a>
                            <a href="https://www.contact-sys.com/" target="_blank"><img src="{$photoDomain}images/kontakt.png" height="64" width="64" alt=""></a>
                            <a href="http://pecom.ru/ru/" target="_blank"><img src="{$photoDomain}images/pek.png" height="64" width="64" alt=""></a>
                            <a href="http://www.jde.ru/" target="_blank"><img src="{$photoDomain}images/gds.png" height="64" width="65" alt=""></a>
                            <a href="http://www.dellin.ru/" target="_blank"><img src="{$photoDomain}images/yel.png" height="64" width="64" alt=""></a>
                        </div>
                    </div>
                </div>

                <div class="left-info">
                    <h3 class="title">По Украине</h3>
                    <div class="wrapper">
                        <p><b>Оплата</b> производится после подтверждения заказа, по указанным в счёте резвизитам.</p>
                        <p><b>Доставка</b> осуществляется транспортными компаниями "Новая
                            почта", «Автолюкс», Укрпочтой либо другим, удобным для Вас способом. После осуществления 100% предоплаты заказ комплектуется и отправляется на адрес, указанный при регистрации.</p>
                        <p>В течение срока комплектации заказа с Вами поддерживает связь менеджер, информируя Вас обо всех нюансах процесса. Поскольку товар предоставляется непосредственными производителями продукции, сроки отправки могут колебаться от 3 до 7 дней, в зависимости от складского наличия и сроков изготовления.</p>
                    </div>
                </div>
                <div class="right-info">
                    <h3 class="title">По России</h3>
                    <div class="wrapper">
                        <p><b>Оплата</b> производится после подтверждения заказа, по указанным в счёте резвизитам.</p>
                        <p><b>Доставка</b> осуществляется с помощью почтовых компаний
                            «ЖелДорЭкспедиция», «АвтодорЭкспресс», "ТК ПЭК", Почтой России или другим удобным Вам способом.</p>
                        <p>После осуществления 100% предоплаты заказ собирается в течение 1-3 дней, после чего отправляется в Белгород, что может занять 3-7 дней, где товар передаётся транспортным компаниям России.</p>
                    </div>
                    <div class="warning clearfix">
                        <img src="{$photoDomain}images/warning2.png" alt="warning">
                        <p>В зависимости от колебания валютных курсов окончательная стоимость товаров может меняться</p>
                    </div>
                </div>
            </div>
        </div>    
        <!-- END paymenth delivery -->

        <!--<div class="col-md-12 col-sm-12 col-xs-12">
            <div class="instagram-gallery">
                <div class="instagram-head">
                    <p>#makewear</p>

                    <div class="instagram-label">Акция</div>
                </div>
                <div class="instagram-info">Добавь тег к фото <i class="fa fa-instagram"></i> или <i
                        class="fa fa-vk"></i> и получи шанс попасть нашу галерею.
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12" id="instagram_image">
            <!-- Append from instagramm (verstka/js/instagram_api.js)-->
        <!--</div>
        <div class="col-md-12 col-sm-12 col-xs-12 instagram-bottom">
            <a href="https://instagram.com/makewear.com.ua/" class="btn-dark-3d" target="_blank">смотреть все</a>
        </div>-->
    </div>
    {$info}
</div>
