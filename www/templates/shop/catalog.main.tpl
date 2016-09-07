<div class="main-container">
    <div class="main-content row main-width category-products" >
        <div class="col-md-12 col-sm-12 col-xs-12 content-wrap catalog-conteiner">
            <div class='breadcrumb'>       
                <ul>
                    {$breadCrumb}
                </ul>
            </div> 
            <div class="row">
                {$topFilters}
                <div class="col-md-12 col-sm-12 col-xs-12 my-wish-state hidden">
                    <div id="filters-state" class="row">
                        <div class="col-md-3 col-sm-3 col-xs-6 price-state filt-state hidden">
                            <p>Цена:</p>
                            <span class="hidden">{$roznFilterPrice1} - </span>
                            <span class="hidden">{$roznFilterPrice2}</span>
                            <span class="glyphicon glyphicon-remove hidden price-remove"></span>
                            </div>
                        <div class="col-md-3 col-sm-3 col-xs-6 color-state filt-state hidden">
                            <p>Цвет:</p>
                            </div>
                        <div class="col-md-3 col-sm-3 col-xs-6 size-state filt-state hidden">
                            <p>Размер:</p>
                            </div>
                        <div class="col-md-3 col-sm-3 col-xs-6 wiew-state filt-state hidden">
                            <p>Категории:</p>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="row lower-conteiner">
                <div class="col-md-2 col-sm-2 col-xs-2 left-filter">
                    {$topMenuFilter}
                    {$bottomMenuFilter}
                </div>
                <div class="col-md-10 col-sm-12 col-xs-12 right-commodities">
                    <div id="commodities-count">
                        <p class="p-top">Всего товаров: {$totalAmount}</p>
                    </div>
                    <div id="page-commodities-count">
                        <p class="p-top">Просмотр:</p>
                        <select>
                            {$perPageSelect}
                        </select>
                    </div>
                    <div class="pagination-conteiner-up">  
                        <ul class='pagination pagination-sm' data-id='{$categoryId}' data-alias='{$categoryAlias}'></ul>
                    </div>  
                    <div id="pjax-container">
                        {$products}
                    </div>
                    <div class="pagination-conteiner-down"> 
                        <ul class='pagination pagination-sm' data-id='{$categoryId}' data-alias='{$categoryAlias}'></ul>
                    </div>  
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2 right-filter hidden">
                    {$bottomMenuFilter}
                </div>
            </div>      
        </div>
    </div>
    {$info} 
</div>