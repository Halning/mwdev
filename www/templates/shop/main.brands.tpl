    <div class="brand-item brand-slider-{$catId}" rel="{$catId}">
        <a href="{$url}"></a>
        <div class="info">
            <div class="info__top clearfix">
                <div class="info__top__column">
                    <p class="name">
                        {$catName}
                    </p>
                    <p class="quantity">
                        Товаров: <span>{$quantity}</span>
                    </p>
                </div>
                <div class="info__top__column">
                    <p class="like">
                        <a href="" class="like_event" data-id="{$catId}" data-type="b">
                            <i class="icon icon-heart {$active}"></i>
                        </a>
                    </p>
                    <p class="number">
                        <a href="" class="like_event" data-id="{$catId}" data-type="b">
                            <span>{$countLike}</span>
                        </a>
                    </p>
                </div>
            </div>
            <div class="info__bottom">
                <div class="rating-info">
                    <ul class="rating-small available" data-rating="{$ratingValue}">
                       {$rating}
                    </ul>
                </div>
                <div class="about">
                    <a class="link-categoties">
                        Выбрать категории
                    </a>
                    <a href="#aboutBrand" class="about-brand" data-id="{$catId}" data-effect="mfp-zoom-in">
                    </a>
                </div>
            </div>
        </div>
    </div>
