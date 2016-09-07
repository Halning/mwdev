<div class="one-similar-wrap" style="cursor:pointer">
    <a href="/product/{$comIdSimilar}/{$alias}.html">
        <img src="{$photoDomain}{$comIdSimilar}/title.jpg"{$imageTitle}{$imageAlt}
             onerror="this.src='/templates/shop/image/nophotoproduct.jpg'"/>
    </a>
    <div class="custom-border">
        <p class="title">{$brandNameSimilar}</p>
        <div class="d-table">
            <div class="d-table-row">
                <div class="d-table-cell">
                    <span>{$name}</span>
                </div>
                {$optPriceForSimilar}
            </div>
            <div class="d-table-row">
                <div class="d-table-cell">
                    <span>{$codSimilar}</span>
                </div>
                <div class="d-table-cell">
                    <span>Розн: <b>{$price}</b> {$cur_v}</span>
                </div>
            </div>
        </div>
    </div>
</div>