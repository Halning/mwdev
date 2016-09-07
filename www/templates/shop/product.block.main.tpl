<div class="main-container">
    <div class="main-content row main-width category-products" >
        <div class="col-md-12 col-sm-12 col-xs-12 content-wrap catalog-conteiner">
            <div class='breadcrumb'>       
                <ul>
                    {$breadCrumb}
                </ul>
            </div>
            <div class="row lower-conteiner">
                <div class="col-md-2 col-sm-2 col-xs-2 left-filter">
                    {$listBlocks}
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
            </div>      
        </div>
    </div>
    {$info} 
</div>