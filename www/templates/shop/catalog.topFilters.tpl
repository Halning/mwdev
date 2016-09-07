<div class="col-md-12 col-sm-12 col-xs-12 my-wish">
    <div class="top-filter">
        <div class="price filters">
            <!--<p>Цена</p>-->
            <div id="slider" {$sliderChecked}>
                <div id="price-inputs">
                    <span>Цена от</span><input type="text" id="minCost" value="{$roznFilterPrice1}" data-value="{$roznMinPrice}">
                    <span>до</span><input type="text" id="maxCost" value="{$roznFilterPrice2}" data-value="{$roznMaxPrice}">
                </div>
            </div>
        </div>
        <div class="color filters jq-selectbox jqselect">
            <!--<p>Цвет</p>-->
            <div class="selection-color jq-selectbox__select">
                <div class="jq-selectbox__trigger">
                    <div class="jq-selectbox__trigger-arrow"></div>
                    <div class="jq-selectbox__dropdown-color-size">
                        {$colors}
                    </div>
                </div>
            </div>
        </div>
        <div class="size filters jq-selectbox jqselect">
            <!--<p>Размер</p>-->
            <div class="selection-size jq-selectbox__select">
                <div class="jq-selectbox__trigger">
                    <div class="jq-selectbox__trigger-arrow"></div>
                    <div class="jq-selectbox__dropdown-color-size">
                        {$sizes}
                    </div>    
                </div>
            </div>
        </div>
        <div class="season filters">
            <!--<p>Сезон:</p>-->
            <select data-placeholder="Сезон">
                <option></option>
                {$season}
            </select>
        </div>      
        <div class="order filters">
        <!--<p>Сортировка:</p>-->      
            <select>
                <option value="newest" selected>по новизне</option>
                <option value="popular">по популярности</option>
                <option value="price_asc">от дешевых к дорогим</option>
                <option value="price_desc">от дорогих к дешевым</option>
            </select>
        </div>
    </div>
</div>