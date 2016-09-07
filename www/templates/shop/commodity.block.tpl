<div class="commodity-one{$classSlider}">
    <div class="product" data-count="{$pagesCount}" data-page="{$currentPage}">
        <a class="product__like" id="pro_like{$id}" data-effect="mfp-zoom-in" href="#wishesPopup" data-id="{$id}" data-type="c">
            <i class="icon icon-hert{$active}"></i>
        </a>
        
        <!-- stikers -->
        <div class="stickers" data-hide="{$stikerNew}">
            <div class="stiker-new top-1-posision">
                <div class="transparency">&nbsp;<div></div></div>
                <div class="stiker-text">NEW</div>
            </div>
        </div>
          
        <div class="stickers" data-hide="{$stikerGift}">
            <div class="stiker-gift top-2-posision" title=" ">
                <div class="transparency">&nbsp;</div>
                <div class="stiker-text"><p>&nbsp;</p></div>
            </div>
        </div> 
            
        <div class="stickers">
            <div class="stiker-save top-3-posision">
                <div class="transparency" {$bgColor}>&nbsp;</div>
                <div class="stiker-text">-{$stikerSave}%</div>
            </div>
        </div>   
        <!-- end stikers -->    
            
        <div class="product__image">
            <a href="{$url}">
                <img src="{$src}"{$imagesTitle}{$imagesAlt} onerror="this.src='/templates/shop/image/nophotoproduct.jpg'">
            </a>
        </div>
        <div class="product__info">
            <div class="custom-border">
                <div class="product-info-popup">
                <div class="product__info__top">
                    <p class="title">{$brandName}</p>
                    <div class="columns clearfix">
                        <div class="column">
                            <p>{$comName}</p>
                            <p>{$cod}</p>
                        </div>
                        <div class="column" title=" ">
                            <p>Опт:  <b id="pri_opt_{$id}" {$colorOpt}>{$priceOpt}</b> {$cur_v}</p>
                            <p {$TDLineTr}>Розн: <b id="pri_rozn_{$id}">{$priceRozn}</b> {$cur_v}</p>
                        </div>
                    </div>
                    <a href="#writeReview" class="rating-info review-popup" data-effect="mfp-zoom-in">
                    <ul class="rating-small available" data-id="{$id}" data-type="com" data-rating="{$trueRating}">
                            {$rating}
                    </ul>
                    </a>
                    <a href="{$url}#comments" class="comments-link">{$commentsCount}</a>
                </div>
                <div class="product__info__bottom clearfix">
                    <i class="shape"></i>
                    <div class="column big-column">
                        <div class="column__in clearfix">
                            {$sizes}
                            <div class="counter-group">
                                <div class="minus-btn"></div>
                                <input type="number" id="count_{$id}" value="1" min="1" max="99" maxlength="2"/>
                                <div class="plus-btn"></div>
                            </div>
                        </div>
                        <a href="#fastOrder" data-id="{$id}" class="fast-order btn-gray btn-gray-3d" data-effect="mfp-zoom-in" title=" ">Быстрый заказ</a>
                    </div>
                    <div class="column">
                        <a href="#addToCartPopup" class="btn btn-green btn-3d add-to-cart" data-id="{$id}"><i class="icon icon-basket"></i></a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>