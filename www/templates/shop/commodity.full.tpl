<div class='interface_chat'>
    <div class="body_chat ui-widget-content"  id="body_chat" style="display:none; bottom: 10px; left: 10px;z-index: 100">
        <div class="title_chat">
            <img src="../../modules/online/images/mw_logo.jpg" class="title_img" />
            <span class="title_chat2" >Помощь онлайн    </span>
            <img src="../../modules/online/images/chat_close.png" style="width:12px; margin:5px;" class="close_chat" />
        </div>
        <div class="read_chat"></div>
        <div class="file_body">
            <input type="text" class="name_chat" value="Ваше имя" />
        </div>
        <textarea class="write_txt">Введите текст сообщения...</textarea>
        <input type="hidden" id="maxim" />
        <input type="button" class="but_send" value="Отправить" />
    </div>
</div>
<div class="main-container">
    <div class="main-content row main-width">
        <div class="content-wrap">
            <div class='breadcrumb'>
                <ul>
                    {$breadCrumb}
                </ul>
            </div>
            <div class="product-card">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12 slider-col">
                        <div class="slider-wrap">
                            <div class="custom-border">
                                {$photo}
                            </div>
                        </div>
                        <div class="rating-info">
                            <ul class="rating">
                                {$rating}
                            </ul>
                            <a href="#"><span>Оставить отзыв</span> (0)</a>
                        </div>
                        <div class="social-share">
                            <!--      <input type="hidden" class="share_social" share-url="{$getUrl}" share-title="{$commodityName}" share-id="{$id}" share-desc="{$getShareDesc}" />
                                     <span class="share-vk" style="cursor:pointer;">VK</span>
                                     <span class="share-fb" style="cursor:pointer;">FB</span>
                                     <span class="share-ok" style="cursor:pointer;">OK</span>
                                     <span class="share-go" style="cursor:pointer;">GO</span> -->
                            <script type="text/javascript" src="https://yastatic.net/share/share.js" charset="utf-8"></script>
                            <div class="yashare-auto-init" id="ya_share"></div>
                            <script type="text/javascript">
                                var url = "http://makewear.azurewebsites.net" + document.location.pathname;
                                var YaShareInstance = new Ya.share({
                                    element: 'ya_share',
                                    elementStyle: {
                                        'type': 'none',
                                        'quickServices': [
                                            'vkontakte',
                                            'facebook',
                                            'odnoklassniki',
                                            'gplus'
                                        ]
                                    },
                                    l10n: 'ru',
                                    link: url,
                                    title: $('.product-head .title').text(),
                                    serviceSpecific: {
                                        facebook: {
                                            link: url
                                        },
                                        odnoklassniki: {
                                            link: url
                                        },
                                        gplus: {
                                            link: url}
                                    }
                                });
                            </script>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-8 col-xs-12 product-info">
                        <div class="row product-head">
                            <div class="product-brand">
                                <img src="{$srcBrand}"/>
                            </div>
                            <div class="title">
                                <h1>{$commodityName}</h1><br><br>
                                <span class="model">{$cod}</span>
                            </div>
                            <div class="similar-categories">
                                <a href="{$categoryUrl}"{$categoryTitle}>Все {$categoryName}</a>
                                <a href="{$categoryUrl2}"{$categoryTitle}>Все {$categoryName} {$brandName}</a>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="row product-body">
                            <div class="btns-group">
                                <ul>
                                    <li><a class="icon-1 btns-group-popup watch-price" href="#watchPrice" data-effect="mfp-zoom-in">следить за ценой</a></li>
                                    <li{$wishLiChecked}><a class="icon-2 btns-group-popup" href="#wishesPopup" data-effect="mfp-zoom-in">{$wishText}</a></li>
                                    <li>
                                        <a class="icon-3" href="#">задать вопрос</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-5 no-padding price-opt">
                                <div class="d-table">
                                    <div class="d-table-row">
                                        <div class="d-table-cell">
                                            <p class="opt">Опт:</p>
                                        </div>
                                        <div class="d-table-cell">
                                            <span class="opt-price"><b class="green-text">{$priceOpt}</b><i>{$cur_v}</i></span>
                                        </div>
                                    </div>
                                    <div class="d-table-row">
                                        <div class="d-table-cell">
                                            <p class="rozn">Розница:</p>
                                        </div>
                                        <div class="d-table-cell">
                                            <span class="rozn-price"><b>{$priceRozn}</b><i>{$cur_v}</i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="emount">
                                    <span>Количество:</span>
                                    <div class="counter-group">
                                        <div class="minus-btn"></div>
                                        <input type="number" value="1" min="1" max="99" maxlength="2"/>
                                        <div class="plus-btn"></div>
                                    </div>
                                    <!-- {$colors} -->
                                    {$sizes}
                                </div>
                            </div>
                            <div class="col-md-7 col-sm-7 col-xs-7 no-padding btns-group">

                                <a href="#addToCartPopup" class="btn btn-green add-to-cart btn-3d">добавить в корзину</a>
                                <a href="#fastOrder" class="fast-order btn btn-green quick-add btn-gray-3d" data-effect="mfp-zoom-in" title=" ">быстрый заказ</a>
                                <div id="watchPrice" class="popup-info mfp-with-anim mfp-hide">
                                    <div class="custom-border">
                                        {$watchPrice}
                                        <div class="clear"></div>
                                    </div>
                                </div>                                      
                            </div>
                        </div>
                                        
                        <a name="comments"></a>
                        <div class="row">
                            <div class="tabs-wrap">
                                <ul>
                                    <li class="characteristic-tab my-inset active" data-inset="open-inset1">
                                        <h2 class="tab-title">Характеристики</h2>
                                        <div class="one-tab">

                                        </div>
                                    </li>
                                    <li class="size-tab my-inset" data-inset="open-inset2">
                                        <h2 class="tab-title">Таблица размеров</h2>
                                        <div class="one-tab">

                                        </div>
                                    </li>
                                    <li class="review-tab my-inset" data-inset="open-inset3">
                                        <h2 class="tab-title">Отзывы (0)</h2>
                                    </li>
                                </ul>
                                <div class="insets-wrap">
                                    <div class="open-inset1 all-insets">
                                        <p><span>Бренд:</span>{$brandName}</p>
                                        <p><span>Артикул:</span>{$cod}</p>
                                        {$comDesc}
                                    </div>
                                    <div class="open-inset2 all-insets">
                                        <div class="d-table">
                                            <div class="d-table-row">
                                                <div class="d-table-cell">Размер</div>
                                                <div class="d-table-cell">Объем груди</div>
                                                <div class="d-table-cell">Объем талии</div>
                                                <div class="d-table-cell">Объем бедер</div>
                                            </div>
                                            <div class="d-table-row">
                                                <div class="d-table-cell">42</div>
                                                <div class="d-table-cell">84-86</div>
                                                <div class="d-table-cell">66</div>
                                                <div class="d-table-cell">88-90</div>
                                            </div>
                                            <div class="d-table-row">
                                                <div class="d-table-cell">44</div>
                                                <div class="d-table-cell">86-90</div>
                                                <div class="d-table-cell">70</div>
                                                <div class="d-table-cell">90-94</div>
                                            </div>
                                            <div class="d-table-row">
                                                <div class="d-table-cell">46</div>
                                                <div class="d-table-cell">90-94</div>
                                                <div class="d-table-cell">74</div>
                                                <div class="d-table-cell">94-98</div>
                                            </div>
                                            <div class="d-table-row">
                                                <div class="d-table-cell">48</div>
                                                <div class="d-table-cell">94-98</div>
                                                <div class="d-table-cell">78</div>
                                                <div class="d-table-cell">98-100</div>
                                            </div>
                                            <div class="d-table-row">
                                                <div class="d-table-cell">50</div>
                                                <div class="d-table-cell">98-102</div>
                                                <div class="d-table-cell">82</div>
                                                <div class="d-table-cell">100-104</div>
                                            </div>
                                            <div class="d-table-row">
                                                <div class="d-table-cell">52</div>
                                                <div class="d-table-cell">102-106</div>
                                                <div class="d-table-cell">86</div>
                                                <div class="d-table-cell">104-108</div>
                                            </div>
                                            <div class="d-table-row">
                                                <div class="d-table-cell">54</div>
                                                <div class="d-table-cell">106-110</div>
                                                <div class="d-table-cell">92</div>
                                                <div class="d-table-cell">108-112</div>
                                            </div>
                                            <div class="d-table-row">
                                                <div class="d-table-cell">56</div>
                                                <div class="d-table-cell">110-114</div>
                                                <div class="d-table-cell">98</div>
                                                <div class="d-table-cell">112-116</div>
                                            </div>
                                            <div class="d-table-row">
                                                <div class="d-table-cell">58</div>
                                                <div class="d-table-cell">114-118</div>
                                                <div class="d-table-cell">102</div>
                                                <div class="d-table-cell">116-120</div>
                                            </div>
                                            <div class="d-table-row">
                                                <div class="d-table-cell">58</div>
                                                <div class="d-table-cell">114-118</div>
                                                <div class="d-table-cell">102</div>
                                                <div class="d-table-cell">116-120</div>
                                            </div>
                                            <div class="d-table-row">
                                                <div class="d-table-cell">58</div>
                                                <div class="d-table-cell">114-118</div>
                                                <div class="d-table-cell">102</div>
                                                <div class="d-table-cell">116-120</div>
                                            </div>
                                            <div class="d-table-row">
                                                <div class="d-table-cell">58</div>
                                                <div class="d-table-cell">114-118</div>
                                                <div class="d-table-cell">102</div>
                                                <div class="d-table-cell">116-120</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="open-inset3 all-insets">
                                        <div class="users-reviews">
                                            <div class="tab-head">
                                                <!--<span>Последние отзывы пользователей</span>-->
                                                <div class="to-write-review toggle-review"><div class="to-write-review-text">Оставить отзыв<div class="to-write-review-img"></div></div></div>
                                            </div>

                                            <div class="write-review">
                                                <div class="tab-head">
                                                    <div class="toggle-review review-back">Назад</div>
                                                    <span>Оцените товар:</span>
                                                    <ul class="rating available" data-id="{$id}" data-type="com">
                                                        <li></li>
                                                        <li></li>
                                                        <li></li>
                                                        <li></li>
                                                        <li></li>
                                                    </ul>
                                                </div>
                                                <div class="comments-wrap">
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
                                                                <input type="text" class="form-control comment-field comment-necessary" id="comment-user-name"  value="{$userName}" maxlength="30" placeholder="Имя *">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding similar-responce">
                        <div class="similar-goods">
                            <div class="similar-title">Похожие товары</div>
                            {$similar_commodity}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--
        <div class="recomended-wrap">
            <div class="recomended-title">
                <h3><span class="active" data-slider="recomended">Рекомендации</span><span data-slider="last-seeing">Недавно просмотренные</span></h3>
            </div>
            <div class="top-slider in-prod-page recomended">
                {$sliderRecomended}
            </div>
            <div class="top-slider in-prod-page last-seeing">
                {$sliderSeeing}
            </div>
        </div>-->
    </div>
    {$info}
</div>
<script type="text/javascript">
    var google_tag_params = {
        dynx_itemid: '{$id}',
        dynx_pagetype: 'offerdetail',
        dynx_totalvalue: '{$priceRozn}',
    };
</script>
