<div class="observations-product  border">
    <div class="observations-close-btn"></div>
    <div class="product-img left border">
        <!--checkbox begin-->
        <input type="checkbox" id="check1" />
        <label for="check1"><span></span></label> <!--span необходим для нашего checkbox-->
        <!--checkbox end-->

        <a href="{$url}">
            <img class="girls" src="{$src}">
        </a>
    </div>

    <div class="brand-info left">
        <div class="info-about-product">
            <label for="surname">Бренд:</label>
            <p><a class="brand" href="#">{$brandName}</a></p>
        </div>
        <div class="info-about-product">
            <label for="name">товар:</label>
            <p class="lower"> {$comName} </p>
        </div>
        <div class="info-about-product">
            <label for="secondname">артикул: </label>
            <p class="lower"> {$comCode}</p>
        </div>
        <div class="info-about-product">
            <label for="secondname">Рейтинг:</label>
            <ul class="rating-small available" data-id="{$id}" data-type="com" data-rating="{$trueRating}">
                {$rating}
            </ul>
            <a href="{$url}#comments" class="review">{$commentsCount}</a>
        </div>
    </div>

    <div class="old-price clearfix">
        <p>Цена</p>
        <div class="price-block">
            <p>Опт: <span class="bold-text">{$salePrice} </span>грн; </p>
            <p>Розн: <span class="bold-text">{$retailPrice} </span>грн </p>
        </div>
        <div class="fast-order">
            быстрый заказ
        </div>
    </div>

    {$newPrice}



</div>