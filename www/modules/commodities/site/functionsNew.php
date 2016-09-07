<?php

/**
 * Created by PhpStorm.
 * Date: 10/1/15
 * Time: 2:01 PM
 */
use Modules\Categories;
use Modules\Cache;
use Modules\Stikers;
use Modules\Response;
use Modules\Products;

/**
 * @global array $glb
 * @global string $theme_name
 * @param int $comId id продукта
 * @return string $center html сторінки продукта
 */
function getCommodityFull($comId)
{

    global $glb;

    $_SESSION["last_page"]   = $_SESSION["currentpage"] != $_SERVER['REQUEST_URI']
            ? $_SESSION["currentpage"] : $_SESSION["last_page"];
    $_SESSION["currentpage"] = $_SERVER['REQUEST_URI'];

    //begin caching
    $cache = new Cache(
        "cache/static/products/{$comId}_{$glb['cur_name']}.html",
        CACHE_TIME_PRODUCT
    );

    if ($cache->check()) {
        return $cache->fileContent;
    } else {

        //TODO добавити що компентані підраховувались тільки видимі (AND com.visible = 1)
        //вибирає продукт
        $result = $glb['mysqli']->query(<<<QUERYGCF2
        SELECT
          commodity_ID, cod, commodity_price2, commodity_price, com_name, sc.alias, commodity_select,
	      commodity_order, com_sizes, size_count, count(com.item_id) comments_count, com_fulldesc,
	      sum(com.comment_rat) rating, scc.categoryID catId, cats.cat_name catName, cats.images_title,
	      cats.images_alt, cats.title, brand_id brandId, brands.cat_name brandName, sc.commodity_visible comVisible
		FROM shop_commodity sc
		INNER JOIN  `shop_commodities-categories` `scc`
		  ON scc.commodityID = sc.commodity_ID
		INNER JOIN shop_categories cats
		  ON cats.categories_of_commodities_ID = scc.categoryID
		  AND cats.categories_of_commodities_parrent IN (
		    264, 209, 212, 213, 261, 211, 266, 267, 210, 268
		  )
		INNER JOIN shop_categories brands
		  ON brands.categories_of_commodities_ID = sc.brand_id
        LEFT JOIN comments com ON com.item_id = sc.commodity_ID
		WHERE commodity_visible = 1
        AND commodity_ID = {$comId}
        LIMIT 1
QUERYGCF2
        );

        if ($result && $row = $result->fetch_assoc()) {

            //якщо товар not visible 301 redirect to brand page
            if (!$row['comVisible'] || !$row['commodity_ID']) {

                $product      = new Products();
                $productBrand = $product->getProductBrand($comId);

                Response::redirect(
                    $productBrand && $productBrand['brandId'] ?
                        "/c{$productBrand['brandId']}-{$productBrand['alias']}/"
                            :
                        '/c1-cardo/', '301'
                );
            }

            $alias         = $row['alias'];
            $commodityName = $row['com_name']; //getCommodityName($row['com_name'], '');
            $brandId       = $row['brandId'];
            $brandName     = $row['brandName'];
            $categoryId    = $row['catId'];
            $categoryName  = mb_strtolower($row['catName'], 'utf-8');
            $imagesTitle   = $row['images_title'];
            $imagesAlt     = $row['images_alt'];
            $categoryTitle = $row['title'];
            $cod           = str_replace("\r\n", '', $row['cod']);
            $cod           = str_replace(' ', '', $cod);

            //meta
            $glb['title']       = "Купить $commodityName $cod $brandName || MakeWear";
            $glb['description'] = "$commodityName $cod $brandName";
            $glb['description'] .= " оптом и в розницу по низкой цене с доставкой по Украине";
            $glb['keywords']    = "$commodityName $cod $brandName, $commodityName $cod купить";

            //sizes
            if ($row['commodity_select']) {
                $sizes = generateSizesColors($row['commodity_select'], $comId,
                    true);
            } else {
                $sizes = generateSizes($row['com_sizes'], $row['size_count'],
                    $comId, true);
            }

            $categoryUrl   = $glb['cat_aliases'][$categoryId] != "" ? "/c{$categoryId}-{$glb['cat_aliases'][$categoryId]}/"
                    : "/c{$categoryId}/";
            $categoryUrl2  = $glb['cat_aliases'][$categoryId] != "" ?
                "/c{$categoryId}-{$glb['cat_aliases'][$categoryId]}".urlencode("&action=filter&category={$brandId}").'/'
                    : "/c{$categoryId}".urlencode("&action=filter&category={$brandId}").'/';
            $categoryTitle = $categoryTitle != '' ? " title='$categoryTitle'" : '';


            //мои желания
            $wishText      = $_SESSION['liked'] && in_array($comId,
                    $_SESSION['liked']) ? 'ДОБАВЛЕНО' : 'в мои желания';
            $wishLiChecked = $_SESSION['liked'] && in_array($comId,
                    $_SESSION['liked']) ? ' class="checked"' : '';

            $imgBrand     = __DIR__."/../../../templates/shop/image/categories/$brandId/logo.jpg";
            $srcBrand     = file_exists($imgBrand) ? "/templates/shop/image/categories/$brandId/logo.jpg"
                    : "/templates/shop/image/categories/0/logo.jpg";
            $getShareDesc = "Розница:".round($row['commodity_price'] * $glb["cur_val"],
                    0).$glb["cur"][$glb["cur_id"]]."<br/> Опт:".round($row['commodity_price2']
                    * $glb["cur_val"], 0).$glb["cur"][$glb["cur_id"]]."<br/> ".$row["com_fulldesc"];
//            $glb["templates"]->set_tpl('{$sliderRecomended}', getRecomendatedCommodities(array($categoryId, $brandId)));
//            $glb["templates"]->set_tpl('{$sliderSeeing}', getSliderTemplate(getLastViewCommodities()));
            $glb["templates"]->set_tpl('{$watchPrice}', getWatchPrice());
            $glb["templates"]->set_tpl('{$getUrl}',
                "http://".$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI']);
            $glb["templates"]->set_tpl('{$getShareDesc}', $getShareDesc);
            $glb["templates"]->set_tpl('{$srcBrand}', $srcBrand);
            $glb["templates"]->set_tpl('{$categoryUrl}', $categoryUrl);
            $glb["templates"]->set_tpl('{$categoryUrl2}', $categoryUrl2);
            $glb["templates"]->set_tpl('{$id}', $comId);
            $glb["templates"]->set_tpl('{$rating}',
                generateRating($row['rating'], $row['comments_count'])[0]);
            $glb["templates"]->set_tpl('{$comDesc}', $row["com_fulldesc"]);
            $glb['templates']->set_tpl('{$sizes}', $sizes);
            $glb['templates']->set_tpl('{$wishLiChecked}', $wishLiChecked);
            $glb['templates']->set_tpl('{$wishText}', $wishText);
            $glb['templates']->set_tpl(
                '{$photo}',
                getAdditionalPhoto(
                    $comId, $alias, $imagesTitle, $imagesAlt
                )
            );
            $glb["templates"]->set_tpl('{$priceRozn}',
                round($row['commodity_price'] * $glb["cur_val"], 0));
            $glb["templates"]->set_tpl('{$priceOpt}',
                getOptPrice($row['commodity_price2'], $brandId));
            $glb["templates"]->set_tpl('{$cur_v}', $glb["cur"][$glb["cur_id"]]); //Показати валют
            $glb['templates']->set_tpl('{$cod}', $cod);
            $glb['templates']->set_tpl('{$commodityName}', $commodityName);
            $glb['templates']->set_tpl('{$categoryName}', $categoryName);
            $glb['templates']->set_tpl('{$categoryTitle}', $categoryTitle);
            $glb['templates']->set_tpl('{$brandName}', $brandName);
            $glb['templates']->set_tpl('{$brandId}', $brandId);
            $glb['templates']->set_tpl('{$breadCrumb}',
                getBreadCrumb($categoryId));
            $glb['templates']->set_tpl('{$info}',
                $glb['templates']->get_tpl('main.info'));
            $glb['templates']->set_tpl(
                '{$yaSrc}', PHOTO_DOMAIN."{$comId}btitle/{$alias}.jpg"
            );
            $glb['templates']->set_tpl('{$metaForYaSare}',
                createMetaForYaShare(
                    $comId, $alias, $row["com_fulldesc"], $commodityName,
                    $brandName, $row['cod'], $row['commodity_price'],
                    $row['commodity_price2']
            ));

            //схожі товари
            getSimilar($categoryId, $brandId);
        } else {

            $product      = new Products();
            $productBrand = $product->getProductBrand($comId);

            Response::redirect(
                $productBrand && $productBrand['brandId'] ?
                    "/c{$productBrand['brandId']}-{$productBrand['alias']}/" :
                    '/c1-cardo/', '301'
            );
        }

        $curLastView = array($comId => $comId);
        $last_view   = $_SESSION["last_view"];

        //помещает просмотренный товар в начало массива.
        $_SESSION["last_view"] = $last_view ? $curLastView + $last_view : $curLastView;

        $center = $glb['templates']->get_tpl('commodity.full');

        // запись кеша
        if ($cache->isOn()) {
            $cache->write('product', $center, array($comId));
        }

        return $center;
    }
}

/**
 * Вибирає з БД два рандомних продукта тогож бренда що й поточний товар.
 * Записує результат в змінну в шаблоні.
 * @param id $brandId
 * @param string $brandName
 */
function getSimilar($categoryId, $brandId)
{
    global $glb;

    $similar    = '';
    $categoryId = $categoryId ? $categoryId : $brandId;

    $result = $glb['mysqli']->query(<<<QUERYGS1
        SELECT
          commodity_ID id, commodity_price price, commodity_price2 price2, sc.alias, cod, com_name name,
          cats.images_title imageTitle, cats.images_alt imageAlt, brand_id brandId,
          brands.cat_name brandName, cats.cat_name catName
        FROM `shop_commodity` sc
        INNER JOIN `shop_commodities-categories` `sc-c`
          ON `sc-c`.commodityID = sc.commodity_ID
        INNER JOIN shop_categories cats
          ON cats.categories_of_commodities_ID = `sc-c`.categoryID
        INNER JOIN shop_categories brands
          ON brands.categories_of_commodities_ID = sc.brand_id
        WHERE `commodity_visible` = 1
        AND cats.categories_of_commodities_ID = {$categoryId}
        ORDER BY RAND()
        LIMIT 2
QUERYGS1
    );

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {

            $optPrice = getOptPrice($row->price2, $row->brandId);

            if (!in_array($row->price2, array(0, $row->price))) {
                $optPriceForSimilar = <<<HTMLGS1
                <div class="d-table-cell">
                    <span>Опт: <b class="green-text">$optPrice</b> {$glb['cur'][$glb['cur_id']]}</span>
                </div>
HTMLGS1;
            } else {
                $optPriceForSimilar = '';
            }

            $imageTitle = $row->imageTitle ? " title='{$row->imageTitle}'" : '';
            $imageAlt   = $row->imageAlt ? " alt='{$row->imageAlt}'" : '';

            $glb['templates']->set_tpl('{$comIdSimilar}', $row->id);
            $glb['templates']->set_tpl('{$alias}', $row->alias);
            $glb['templates']->set_tpl('{$price}',
                round($row->price * $glb['cur_val'], 0));
            $glb['templates']->set_tpl('{$optPriceForSimilar}',
                $optPriceForSimilar);
            $glb['templates']->set_tpl('{$codSimilar}', $row->cod);
            $glb['templates']->set_tpl('{$imageTitle}', $imageTitle);
            $glb['templates']->set_tpl('{$imageAlt}', $imageAlt);
            $glb['templates']->set_tpl('{$photoDomain}', PHOTO_DOMAIN);
            $glb['templates']->set_tpl('{$brandNameSimilar}', $row->brandName);
            $glb['templates']->set_tpl('{$name}',
                getCommodityName($row->name, $row->catName));

            $similar .= $glb['templates']->get_tpl('commodity.similar');
        }
    }

    $glb['templates']->set_tpl('{$similar_commodity}', $similar);
}

/**
 * @global array $glb
 * @param int $id
 * @param array $sizesFilters
 * @param array $filters
 * @return string
 */
function getTopFilters($id, $filters)
{
    global $glb;

    $colors              = $sizes               = $seasonOptions       = $andSeason
        = $joinSeasonForColors = $joinSeasonForSizes  = '';
    $andColor            = $joinColorForSeason  = $joinColorForSizes   = $andSize
        = $joinSizesForSeason  = $joinSizesForColors  = '';
    $season              = $sizesFinal          = array();

    if (isset($filters['GET'])) {

        //якщо філтри включені
        //season
        if ($filters['GET']['season']) {
            $andSeason           = "AND sfseason.ticket_value in ({$filters['GET']['season']})";
            $joinSeasonForColors = 'INNER JOIN `shop_filters-values` sfseason ON sfseason.ticket_id = sfcolor.ticket_id';
            $joinSeasonForSizes  = 'INNER JOIN `shop_filters-values` sfseason ON sfseason.ticket_id = sfsizes.ticket_id';
        }

        //color
        if ($filters['GET']['color']) {
            $andColor           = "AND sfcolor.ticket_value in ({$filters['GET']['color']})";
            $joinColorForSeason = 'INNER JOIN `shop_filters-values` sfcolor ON sfcolor.ticket_id = sfseason.ticket_id';
            $joinColorForSizes  = 'INNER JOIN `shop_filters-values` sfcolor ON sfcolor.ticket_id = sfsizes.ticket_id';
        }

        //size
        if ($filters['GET']['size']) {
            $andSize            = "AND sfsizes.ticket_value in ({$filters['GET']['size']})";
            $joinSizesForColors = 'INNER JOIN `shop_filters-values` sfsizes ON sfsizes.ticket_id = sfcolor.ticket_id';
            $joinSizesForSeason = 'INNER JOIN `shop_filters-values` sfsizes ON sfsizes.ticket_id = sfseason.ticket_id';
        }


        $query = <<<QUERY
            SELECT distinct sfseason.ticket_value val, list_name, sfseason.ticket_filterid filter, list_realname
            FROM `shop_filters-values` sfseason
            INNER JOIN `shop_filters-lists` ON sfseason.ticket_value = id
            INNER JOIN shop_commodity sc ON sc.commodity_ID = sfseason.ticket_id
            INNER JOIN `shop_commodities-categories`  scc ON sc.commodity_ID = scc.commodityID
            {$joinColorForSeason}
            {$joinSizesForSeason}
            {$filters['categoryJoin']}
            WHERE scc.categoryID = {$id}
            AND sfseason.ticket_filterid = 7
            AND sc.commodity_visible = 1
            {$andColor}
            {$andSize}
            {$filters['filtersAnd']['categoryFilters']}
            {$filters['filtersAnd']['priceFilters']}
        UNION
            SELECT distinct sfcolor.ticket_value val, list_name, sfcolor.ticket_filterid filter, list_realname
            FROM `shop_filters-values` sfcolor
            INNER JOIN `shop_filters-lists` ON sfcolor.ticket_value = id
            INNER JOIN shop_commodity sc ON sc.commodity_ID = sfcolor.ticket_id
            INNER JOIN `shop_commodities-categories`  scc ON sfcolor.ticket_id = scc.commodityID
            {$joinSizesForColors}
            {$joinSeasonForColors}
            {$filters['categoryJoin']}
            WHERE scc.categoryID = {$id}
            AND sfcolor.ticket_filterid = 9
            AND sc.commodity_visible = 1
            {$andSeason}
            {$andSize}
            {$filters['filtersAnd']['categoryFilters']}
            {$filters['filtersAnd']['priceFilters']}
        UNION
            SELECT distinct sfsizes.ticket_value val, list_name, sfsizes.ticket_filterid filter, list_realname
            FROM `shop_filters-values` sfsizes
            INNER JOIN `shop_filters-lists` ON sfsizes.ticket_value = id
            INNER JOIN shop_commodity sc ON sc.commodity_ID = sfsizes.ticket_id
            INNER JOIN `shop_commodities-categories`  scc ON sfsizes.ticket_id = scc.commodityID
            {$joinColorForSizes}
            {$joinSeasonForSizes}
            {$filters['categoryJoin']}
            WHERE scc.categoryID = {$id}
            AND sfsizes.ticket_filterid = 5
            AND sc.commodity_visible = 1
            {$andSeason}
            {$andColor}
            {$filters['filtersAnd']['categoryFilters']}
            {$filters['filtersAnd']['priceFilters']}
QUERY;
    } else {

        //if filters disabled select all filters current category
        $query = <<<GTF
            SELECT
              distinct ticket_value val, list_name, ticket_filterid filter, list_realname
            FROM `shop_filters-values` sfv
            INNER JOIN `shop_commodities-categories` scc ON scc.commodityID = ticket_id
            INNER JOIN `shop_filters-lists` ON ticket_value = id
            INNER JOIN shop_commodity sc ON sc.commodity_ID = ticket_id
            WHERE scc.categoryID = {$id}
            AND ticket_filterid in (5, 7, 9)
            AND sc.commodity_visible = 1
GTF;
    }

    //color and season
    $result = $glb['mysqli']->query($query);
    while ($row    = $result->fetch_assoc()) {
        switch ($row['filter']) {

            //sizes
            case 5:
                $checked = isset($filters['GET']['size']) &&
                    strpos($filters['GET']['size'], $row['val']) !== false ?
                    'checked' :
                    '';
                $sizes .= <<<GTMHTML1
            <div class='size-box {$checked}' data-value='{$row['val']}'>
                <span>{$row['list_name']}</span>
            </div>
GTMHTML1;
                break;

            //season 1-st part
            case 7:
                $season[] = $row['val'];
                break;

            //color
            case 9:
                $checked = isset($filters['GET']) && in_array($row['val'],
                        explode(',', $filters['GET']['color'])) ?
                    'checked' : '';
                $colors .= "<div data-id='{$row['val']}' data-name='{$row['list_realname']}' class='{$row['list_name']} color-box {$checked}'>"
                    ."<span class='glyphicon glyphicon-ok hidden'></span></div>";
                break;
        }
    }

    //seasons 2-nd part
    if (in_array(77, $season) || in_array(78, $season)) {
        $selected = isset($filters['GET']['season']) &&
            strpos($filters['GET']['season'], '77') !== false &&
            strpos($filters['GET']['season'], '78') !== false ?
            "selected" :
            '';
        $seasonOptions .= "<option value='77,78' {$selected}>Весна-лето</option>";
    }
    if (in_array(61, $season) || in_array(62, $season)) {
        $selected = isset($filters['GET']['season']) &&
            strpos($filters['GET']['season'], '61') !== false &&
            strpos($filters['GET']['season'], '62') !== false ?
            "selected" :
            '';
        $seasonOptions .= "<option value='61,62' {$selected}>Зима-осень</option>";
    }
    if (in_array(62, $season) || in_array(77, $season)) {
        $selected = isset($filters['GET']['season']) &&
            strpos($filters['GET']['season'], '77') !== false &&
            strpos($filters['GET']['season'], '62') !== false ?
            "selected" :
            '';
        $seasonOptions .= "<option value='62,77' {$selected}>Весно-осень</option>";
    }

    $glb['templates']->set_tpl('{$colors}', $colors);
    $glb['templates']->set_tpl('{$sizes}', $sizes);
    $glb['templates']->set_tpl('{$season}', $seasonOptions);

    return $glb['templates']->get_tpl('catalog.topFilters');
}

/**
 * @global type $glb
 * @param type $id
 * @param type $filters
 * @return type
 */
function getLeftMenu($id, $filters)
{
    global $glb;

    $brandsList       = '';
    $categoriesList   = '';
    $parentCategories = array(264, 209, 212, 213, 261, 211, 266, 267, 210, 268);

    //if brand
    if ($glb['cat_parents'][$id] == 10) {

        //categoriesList
        $query = <<<GLMSQL1
            SELECT
              count(sc.commodity_ID) quantity, cat_name catName, category_id categoryId
            FROM shop_commodity sc
            JOIN shop_categories cat
              ON sc.category_id = cat.categories_of_commodities_ID
              AND cat.visible = 1
            {$filters['filtersJoin']}
            WHERE sc.commodity_visible = 1
            AND sc.brand_id = {$id}
            {$filters['filtersAnd']['colorFilters']}
            {$filters['filtersAnd']['seasonFilters']}
            {$filters['filtersAnd']['sizeFilters']}
            {$filters['filtersAnd']['priceFilters']}
            GROUP BY categoryID
GLMSQL1;

        $result = $glb['mysqli']->query($query);

        $filterCats       = array();
        $parentCategories = array();

        while ($row = $result->fetch_object()) {

            //filter parent categories
            addParentCategories($parentCategories, $row->categoryId);

            $filterCats[$row->categoryId] = array(
                'name' => $row->catName,
                'quantity' => $row->quantity,
                'checked' => isset($filters['GET']) && in_array(
                    $row->categoryId, explode(',', $filters['GET']['category'])
                ),
            );
        }

        //generate categories filtetrs tree
        $categoriesList = generateFiltersCategoriesTree($parentCategories,
            $filterCats);

        //brandsList
        foreach ($glb['com_counter'] as $catId => $count) {
            if ($glb['cat_parents'][$catId] == 10) {
                $catName = $glb['cat_names'][$catId];
                $alias   = $glb['cat_aliases'][$catId];
                $url     = $alias != "" ? "/c{$catId}-{$alias}/" : "/c{$catId}/";
                $count   = is_numeric($count) ? $count : 0;
                $checked = $id == $catId ? 'checked' : '';
                $brandsList .= <<<GLMHTML2
                    <li>
                        <div class="checkbox">
                            <input type="checkbox" class="check" {$checked}/>
                            <label for="check">
                                <a href='{$url}'>{$catName} ({$count})</a>
                            </label>
                        </div>
                    </li>
GLMHTML2;
            }
        }

        $glb['templates']->set_tpl('{$brandsList}', $brandsList);
        $glb['templates']->set_tpl('{$categoriesList}', $categoriesList);

        $linesBottom = $glb['templates']->get_tpl('catalog.leftMenu');
        $linesTop    = $glb['templates']->get_tpl('catalog.leftMenuBrand');
    } elseif (in_array($glb['cat_parents'][$id], $parentCategories) || 61 == $id) {

        //if category
        //1 - generate brands filters list
        $query = <<<GLMSQL2
            SELECT count(DISTINCT sc.commodity_ID) quant, sc.brand_id id
            FROM `shop_commodities-categories` scc
            INNER JOIN shop_commodity sc ON sc.commodity_ID = scc.commodityID
            {$filters['filtersJoin']}
            WHERE sc.commodity_visible = 1
            AND scc.categoryID = {$id}
            {$filters['filtersAnd']['colorFilters']}
            {$filters['filtersAnd']['seasonFilters']}
            {$filters['filtersAnd']['sizeFilters']}
            {$filters['filtersAnd']['priceFilters']}
            GROUP BY brand_id
GLMSQL2;

        $result = $glb['mysqli']->query($query);

        while ($row = $result->fetch_assoc()) {
            $checked = isset($filters['GET']) && in_array($row['id'],
                    explode(',', $filters['GET']['category'])) ?
                'checked' : '';
            $brandsList .= <<<GLMHTML3
                <li>
                    <div class="checkbox list-brands">
                        <input type="checkbox" class="check brands" {$checked} data-name='{$glb['cat_names'][$row['id']]}' data-id='{$row['id']}'/>
                        <label for="check">
                            <a href='#' data-id='{$row['id']}'>{$glb['cat_names'][$row['id']]} ({$row['quant']})</a>
                        </label>
                    </div>
                </li>
GLMHTML3;
        }

        //2 - generate categories tree
        //active category for tree
        $activeCats = array();
        $index      = $id;
        while ($glb['cat_parents'][$index] != 0) {
            $activeCats[] = $glb['cat_parents'][$index];
            $index        = $glb['cat_parents'][$index];
        }

        $categoriesList = generateCategoriesTree($glb['cat_parents'], $id,
            $activeCats);


        $glb['templates']->set_tpl('{$brandsList}', $brandsList);
        $glb['templates']->set_tpl('{$categoriesList}', $categoriesList);
        $linesTop    = $glb['templates']->get_tpl('catalog.leftMenu');
        $linesBottom = $glb['templates']->get_tpl('catalog.leftMenuBrand');
    }

    return array($linesTop, $linesBottom);
}

function addParentCategories(&$parentCategories, $catId)
{
    global $glb;

    $parentCategories[$catId] = $glb['cat_parents'][$catId];
    if (0 != $glb['cat_parents'][$catId]) {
        addParentCategories($parentCategories, $glb['cat_parents'][$catId]);
    }
}

function generateFiltersCategoriesTree($parentCategories, $filterCats,
                                       $catId = 0, $level = 0)
{
    global $glb;

    $level++;

    if (in_array($catId, $parentCategories)) {

        $subCats = array_keys($parentCategories, $catId);
        $class   = " lvl-$level";
        $lines   = "<ul class='left-menu-products filters-tree $class'>";

        foreach ($subCats as $subCat) {

            $lines .= '<li>';
            $lines .= in_array($subCat, $parentCategories) ?
                "<div class='filters-toggle'>
                     <span>{$glb['cat_names'][$subCat]}</span>
                 </div>" :
                '';
            $lines .= generateFiltersCategoriesTree($parentCategories,
                $filterCats, $subCat, $level);
            $lines .= '</li>';
        }

        $lines .= 0 == $catId ? '' : '</ul>';
    } else {

        $checked = $filterCats[$catId]['checked'] ? ' checked' : '';

        $catName = mb_strlen($filterCats[$catId]['name'], 'utf8') > 14 ?
            mb_substr($filterCats[$catId]['name'], 0, 14).' ...' :
            $filterCats[$catId]['name'];
        $lines   = <<<GFCT
                    <div class="checkbox list-category">
                        <input type="checkbox" class="check category"$checked  data-name='{$filterCats[$catId]['name']}' data-id='{$catId}'/>
                        <label for="check">
                            <a href='#' data-id='{$catId}'>$catName ({$filterCats[$catId]['quantity']})</a>
                        </label>
                    </div>
GFCT;
    }

    return $lines;
}

function generateCategoriesTree($parentCategories, $id, $activeCats, $catId = 0,
                                $level = 0)
{
    global $glb;

    $level++;

    if (in_array($catId, $parentCategories)) {

        $subCats = array_keys($parentCategories, $catId);
        $class   = " lvl-$level";
        $display = $level > 1 && !in_array($catId, $activeCats) ? " style='display:none;'"
                : '';
        $lines   = "<ul class='left-menu-products filters-tree $class'$display>";

        foreach ($subCats as $subCat) {

            if (10 == $subCat ||
                (!in_array($subCat, $parentCategories) && $glb['com_counter'][$subCat]
                < 1)
            ) {
                continue;
            }
            $list = generateCategoriesTree($parentCategories, $id, $activeCats,
                $subCat, $level);

            if (preg_match('/checkbox/', $list)) {
                $lines .= '<li>';
                $lines .= in_array($subCat, $parentCategories) ?
                    "<div class='filters-toggle'>
                     <span>{$glb['cat_names'][$subCat]}</span>
                 </div>" :
                    '';
                $lines .= $list;
                $lines .= '</li>';
            }
        }

        $lines .= 0 == $catId ? '' : '</ul>';
    } else {
        $alias   = $glb['cat_aliases'][$catId];
        $url     = $alias != "" ? "/c{$catId}-{$alias}/" : "/c{$catId}/";
        $active  = $id == $catId ? "checked" : '';
        $catName = mb_strlen($glb['cat_names'][$catId], 'utf8') > 13 ?
            mb_substr($glb['cat_names'][$catId], 0, 13).' ...' :
            $glb['cat_names'][$catId];
        $lines   = <<<GCT1
            <div class='checkbox'>
                            <input type='checkbox' class='check' $active/>
                            <label for='check'>
                                <a href='$url'>$catName ({$glb['com_counter'][$catId]})</a>
                            </label>
                        </div>
GCT1;
    }

    return $lines;
}

/**
 * Генерує html сторінку каталогу з фільтрами.
 * @param $id id of brand or category
 * @param $page int|null number of page if exists
 * @param $pjax bool, true if pjax request
 * @return string html cod of page
 */
function getCategory($id, $page, $pjax)
{
    $parentCategories = array(10, 12, 89, 84, 264, 265, 209, 212, 213, 261, 211,
        266, 267, 210, 268);
    if (in_array($id, $parentCategories)) {
        $categories = new Categories();
        return $categories->render();
    }

    global $glb;

    $items     = $prices    = array();
    $filters   = getFilters($id);
    $perPage   = $filters['filtersPerPage'] ? $filters['filtersPerPage'] : 30;
    $startPage = $page == 1 || $page === false ? 0 : $perPage * ($page - 1);
    $limit     = $page == 1 || $page === false ? '' : "LIMIT {$startPage}, {$perPage}";

    $query = <<<GCSQL
        SELECT SQL_CALC_FOUND_ROWS
          count(com.item_id) comments_count, commodity_ID, cod, commodity_price2, commodity_price,
          com_name, sc.alias, commodity_order, scc.categoryID, sum(com.comment_rat) rating,
          commodity_add_date, category_id,
          commodity_select, com_sizes, size_count, cat_name, brand_id, images_title, images_alt,
          cat.categories_of_commodities_parrent parentCatId, cat.alias catAlias
        FROM shop_commodity sc
		INNER JOIN `shop_commodities-categories`  scc ON sc.commodity_ID = scc.commodityID
		INNER JOIN shop_categories cat ON cat.categories_of_commodities_ID = scc.categoryID
        LEFT JOIN comments com ON com.item_id = sc.commodity_ID
        {$filters['filtersJoin']}
        {$filters['categoryJoin']}
        WHERE commodity_visible = '1'
		AND cat.categories_of_commodities_ID = {$id}
		{$filters['filtersAnd']['colorFilters']}
		{$filters['filtersAnd']['seasonFilters']}
		{$filters['filtersAnd']['categoryFilters']}
		{$filters['filtersAnd']['sizeFilters']}
		{$filters['filtersAnd']['priceFilters']}
        GROUP BY sc.commodity_ID
        {$filters['filtersOrder']}
        {$limit}
GCSQL;

    $result = $glb['mysqli']->query($query);
    if ($result->num_rows > 0) {
        $i           = 0;
        $totalAmount = $glb['mysqli']
                ->query("SELECT FOUND_ROWS() as rows")
                ->fetch_object()
            ->rows;
        $pagesCount  = ceil($totalAmount / $perPage);
        while ($row         = $result->fetch_assoc()) {
            if ($i < $perPage) {
                $items[] = $row;
            }
            $prices[] = $row['commodity_price'];
            $i++;
        }

        //set meta
        setMeta($items[0]['parentCatId'], $items[0]['cat_name']);

        if ($page > 1) {
            $glb['canonical'] = "/c$id-{$items[0]['catAlias']}/";
        }

        if (count($filters['GET'])) {
            $glb['canonical'] = "/c$id-{$items[0]['catAlias']}/";
        }
    }
    $lines = getSliderTemplate(
        $items, $pagesCount, $page == false ? 1 : $page
    );

    //if pjax - return current lines
    if ($pjax && $page != false) {
        return $lines;
    }

    //per page select
    $perPageSelect = '';
    foreach (array(30, 60, 120) as $pages) {
        $selected = $pages == $perPage ? ' selected' : '';
        $perPageSelect .= "<option value='$pages'{$selected}>$pages</option>";
    }

    $minPrice         = isset($filters['GET']['priceM1']) || count($prices) == 0
            ? $filters['GET']['priceM1'] : min($prices);
    $maxPrice         = isset($filters['GET']['priceM2']) || count($prices) == 0
            ? $filters['GET']['priceM2'] : max($prices);
    $roznFilterPrice1 = isset($filters['GET']['price1']) && $filters['GET']['price1']
        != '' ?
        $filters['GET']['price1'] :
        $minPrice;
    $roznFilterPrice2 = isset($filters['GET']['price2']) && $filters['GET']['price2']
        != '' ?
        $filters['GET']['price2'] :
        $maxPrice;
    $sliderChecked    = isset($filters['GET']['price2']) && $filters['GET']['price2']
        != '' ? 'class="checked"' : '';

    $glb['templates']->set_tpl('{$breadCrumb}', getBreadCrumb($id));
    $glb['templates']->set_tpl('{$perPageSelect}', $perPageSelect);
    $glb['templates']->set_tpl('{$totalAmount}', $totalAmount);
    $glb['templates']->set_tpl('{$roznMaxPrice}', $maxPrice);
    $glb['templates']->set_tpl('{$roznMinPrice}', $minPrice);
    $glb['templates']->set_tpl('{$roznFilterPrice1}', $roznFilterPrice1);
    $glb['templates']->set_tpl('{$roznFilterPrice2}', $roznFilterPrice2);
    $glb['templates']->set_tpl('{$sliderChecked}', $sliderChecked);
    $glb['templates']->set_tpl('{$topFilters}', getTopFilters($id, $filters));
    $filterLeftTopBottom = getLeftMenu($id, $filters);
    $glb['templates']->set_tpl('{$topMenuFilter}', $filterLeftTopBottom[0]);
    $glb['templates']->set_tpl('{$bottomMenuFilter}', $filterLeftTopBottom[1]);
    $glb['templates']->set_tpl('{$products}', $lines);
    $glb['templates']->set_tpl('{$categoryId}', $id);
    $glb['templates']->set_tpl('{$categoryAlias}', $glb['cat_aliases'][$id]);
    $glb['templates']->set_tpl('{$info}',
        $glb['templates']->get_tpl('main.info'));

    $lines = $glb['templates']->get_tpl('catalog.main');

    return $lines;
}

/**
 *
 * @global array $glb
 * @param int $id
 * @return array
 */
function getFilters($id)
{
    if (filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) === 'filter') {
        global $glb;
        $seasonFilters   = $colorFilters    = $filtersJoin     = $categoryJoin    = $categoryFilters
            = $sizeFilters     = $filtersOrder    = $priceFilters    = '';
        $prices          = $pricesM         = array('', '');
        $color           = filter_input(INPUT_GET, 'color',
            FILTER_SANITIZE_STRING);
        $category        = filter_input(INPUT_GET, 'category',
            FILTER_SANITIZE_STRING);
        $season          = filter_input(INPUT_GET, 'season',
            FILTER_SANITIZE_STRING);
        $sizes           = filter_input(INPUT_GET, 'sizes',
            FILTER_SANITIZE_STRING);
        $order           = filter_input(INPUT_GET, 'order',
            FILTER_SANITIZE_STRING);
        $perPage         = filter_input(INPUT_GET, 'perPage',
            FILTER_VALIDATE_INT);
        $price           = filter_input(INPUT_GET, 'price',
            FILTER_SANITIZE_STRING);
        $priceM          = filter_input(INPUT_GET, 'priceM',
            FILTER_SANITIZE_STRING);

        //order
        switch ($order) {
            case "price_asc" :
                $filtersOrder = "ORDER BY commodity_price";
                break;
            case "price_desc" :
                $filtersOrder = "ORDER BY commodity_price DESC";
                break;
            default:
                $filtersOrder = "ORDER BY sc.commodity_add_date DESC";
                break;
        }

        //color
        if (preg_match('/[0-9,?]{2,}/', $color, $match)) {
            $colorFilters .= reset($match);
            $colorFilters = "AND sfcolor.ticket_value IN ({$colorFilters})";
            $filtersJoin .= " INNER JOIN `shop_filters-values` sfcolor ON sfcolor.ticket_id = sc.commodity_ID";
        }

        //season
        if (preg_match('/[0-9,]{5}/', $season, $match)) {
            $seasonFilters .= reset($match);
            $seasonFilters = "AND sfseason.ticket_value IN ({$seasonFilters})";
            $filtersJoin .= " INNER JOIN `shop_filters-values` sfseason ON sfseason.ticket_id = sc.commodity_ID";
        }

        //sizes
        if (preg_match('/[0-9,]{2,}/', $sizes, $match)) {
            $sizeFilters .= reset($match);
            $sizeFilters = "AND sfsizes.ticket_value IN ($sizeFilters)";
            $filtersJoin .= " INNER JOIN `shop_filters-values` sfsizes ON sfsizes.ticket_id = sc.commodity_ID";
        }

        //price
        if (preg_match('/[0-9\-?]{2,}/', $price, $match)) {
            $prices       = explode('-', reset($match));
            $priceFilters = "AND commodity_price BETWEEN {$prices[0]} AND {$prices[1]}";
        }

        if (preg_match('/[0-9\-?]{2,}/', $priceM, $match)) {
            $pricesM = explode('-', reset($match));
        }

        //category
        if (preg_match('/[0-9,?]{1,}/', $category, $match)) {
            if ($glb['cat_parents'][$id] == 10) {
                $categoryJoin    = " INNER JOIN `shop_commodities-categories` sccat ON sccat.commodityID = sc.commodity_ID";
                $categoryFilters = "AND sccat.categoryID  IN ({$match[0]})";
            } else {
                $categoryFilters = "AND sc.brand_id IN ({$match[0]})";
            }
        }

        return array(
            'filtersJoin' => $filtersJoin,
            'categoryJoin' => $categoryJoin,
            'filtersAnd' => array(
                'colorFilters' => $colorFilters,
                'seasonFilters' => $seasonFilters,
                'categoryFilters' => $categoryFilters,
                'sizeFilters' => $sizeFilters,
                'priceFilters' => $priceFilters
            ),
            'filtersOrder' => $filtersOrder,
            'filtersPerPage' => $perPage,
            'GET' => array(
                'color' => $color,
                'season' => $season,
                'category' => $category,
                'size' => $sizes,
                'price1' => $prices[0],
                'price2' => $prices[1],
                'priceM1' => $pricesM[0],
                'priceM2' => $pricesM[1]
            )
        );
    } else {
        return array('', '', '', '', '');
    }
}

/**
 * Generates html cod for brands carusel on main page.
 * @return string, list of brands in brands slider.
 */
function getBrandsForCarusel($get_catid = 1)
{
    global $glb, $theme_name;

    $lines    = '';
    $jj       = 0;
    $sw_count = 0;
    $ch_arr1  = "s0";

    foreach ($glb['cat_parents'] as $catId => $catParentId) {
        if ($catParentId == 10) {
            $sw_count++;
            $ch_arr1.=",".$catId;
        }
    }
    $ch_arr = str_replace("s0", "", $ch_arr1);
    $ch_arr = explode(",", $ch_arr);

    if (isset($_GET["ch_brands"])) {
        $ccc = $_GET["ch_brands"];
        $ccc = explode(";", $ccc);

        if ($ccc[1] == "all") $div = floor($sw_count / $ccc[0]);
        else {
            $ch_arr   = explode(",", $ccc[1]);
            $sw_count = count($ch_arr);
        }

        if ($ccc[1] == "like") {
            $ch_arr = array();
            foreach ($glb['cat_parents'] as $catId => $catParentId) {
                if ($catParentId == 10) {
                    if ($glb['rating'][$catId][3] != 0)
                            array_push($ch_arr, $catId);
                }
            }
        }

        if ($ccc[1] == "star") {
            $ch_arr = array();
            foreach ($glb['cat_parents'] as $catId => $catParentId) {
                if ($catParentId == 10) {
                    if (generateRating($glb['rating'][$catId][0],
                            $glb['rating'][$catId][1]) != 0)
                            array_push($ch_arr, $catId);
                }
            }
        }
    }

    foreach ($glb['cat_parents'] as $catId => $catParentId) {

        if ($catParentId == 10) {
            if (in_array($catId, $ch_arr)) {
//            $catDescription = $glb['rating'][$cat_chl_id][2];
                $alias    = $glb['cat_aliases'][$catId];
                $url      = $alias != "" ? "/c{$catId}-{$alias}/" : "/c{$catId}/";
                $quantity = is_numeric($glb['com_counter'][$catId]) ? $glb['com_counter'][$catId]
                        : 0;
                $img      = __DIR__."/../../../templates/shop/image/categories/{$catId}/main.jpg";
                $src      = file_exists($img) ? "/templates/shop/image/categories/{$catId}/main.jpg"
                        : "/templates/{$theme_name}/image/nophoto.jpg";
                $rating   = generateRating($glb['rating'][$catId][0],
                    $glb['rating'][$catId][1]);

                //change icon heart, if already liked.
                if (is_array($_SESSION['liked']) && in_array($catId,
                        $_SESSION['liked'])) {
                    $active = ' active';
                } else {
                    $active = '';
                }
                $glb['templates']->set_tpl('{$catId}', $catId);
                $glb['templates']->set_tpl('{$rating}', $rating[0]);
                $glb['templates']->set_tpl('{$ratingValue}', $rating[1]);
                $glb['templates']->set_tpl('{$countLike}',
                    $glb['rating'][$catId][3]);
                $glb['templates']->set_tpl('{$active}', $active);
                $glb['templates']->set_tpl('{$src}', $src);
                $glb['templates']->set_tpl('{$catName}',
                    $glb['cat_names'][$catId]);
                $glb['templates']->set_tpl('{$url}', $url);
                $glb['templates']->set_tpl('{$quantity}', $quantity);

                if ($sw_count <= 6) {
                    $lines .= "<div class='slick-slide'>".$glb['templates']->get_tpl('main.brands')."</div>";
                } else {
                    if (($jj % 2) == 0) {
                        $lines .= "<div class='slick-slide'>".$glb['templates']->get_tpl('main.brands');
                    } else {
                        $lines .= $glb['templates']->get_tpl('main.brands')."</div>";
                    }
                }
                $jj++;
            }
        }
    }

    return $lines;
}

/**
 * міняє слово 'отзыв' відповідно до кількості - 1 отзыв, 2 отзыва, 6 отзывов
 * @param $commentsCount int.
 * @return string - X отзывXX.
 */
function generateQuantityOfResponse($commentsCount)
{
    $commentsCount = $commentsCount ? $commentsCount : 0;
    $lastDigit     = substr($commentsCount, -1);
    $preLastDigit  = strlen($commentsCount) > 1 ? substr($commentsCount, -2, 1) : 0;
    if ($lastDigit == 1 && $preLastDigit != 1) {
        $suffix = '';
    } elseif (in_array($lastDigit, [2, 3, 4]) && $preLastDigit != 1) {
        $suffix = 'а';
    } else {
        $suffix = 'ов';
    }
    return $commentsCount." отзыв".$suffix;
}

/**
 * @param $summRating int.
 * @param $numVotes int.
 * @return list of ul,  with active tags li conformity to rating.
 */
function generateRating($summRating, $numVotes)
{
    $rating = "";
    if ($numVotes > 0) {
        $trueRating = round($summRating / $numVotes, 0, PHP_ROUND_HALF_UP);
        $trueRating = in_array($trueRating, range(1, 5)) ? $trueRating : 0;
    } else {
        $trueRating = 0;
    }
    for ($i = 1; $i <= 5; $i++) {
        $active = $trueRating >= $i ? " class='active'" : "";
        $rating .= "<li{$active}></li>";
    }
    return array($rating, $trueRating);
}

/**
 * Generate size for items when color not bound with size.
 * @param bool $productCard - if true, functions calls for product card and use tag select with
 * changed attributes.
 * @param $stringSizes string from com_sizes field in shop_commodity.
 * @param $id int.
 * @return string, converted to html cod for size select options.
 */
function generateSizes($stringSizes, $stringSizeCount, $id, $productCard = false)
{
    $listSizes = '';
    if ($stringSizes) {
        if ($productCard) {
            $listSizes = '<select class="sel_size" data-placeholder="Выберите размер"><option></option>';
        } else {
            $listSizes = '<select class="sel_size" id="select_size'.$id.'" data-placeholder="Размер"><option></option>';
        }
        $arraySizes      = explode(';', $stringSizes);
        $arraySizeCounty = explode(';', $stringSizeCount);
        foreach ($arraySizes as $stringSize) {
            foreach ($arraySizeCounty as $value) {
                if (strpos($value, $stringSize) !== false) {
                    $sizeCount = str_replace('=', '', strstr($value, '='));
                }
            }
            $listSizes .= "<option value='{$stringSize}' data-count='{$sizeCount}'>{$stringSize}</option>";
        }
        $listSizes .= '</select>';
    }
    return $listSizes;
}

/**
 * Generate size for items when color are bound with size (Glem..).
 * @param bool $productCard - if true, functions calls for product card and use tag select with
 * changed attributes.
 * @param $stringColorsSizes from commodity_select field in shop commodity.
 * @return string, converted to html cod for size select options.
 */
function generateSizesColors($stringColorsSizes, $id, $productCard = false)
{
    $arrayColorsSizes = explode(';', $stringColorsSizes);
    if (count($arrayColorsSizes) > 1) {
        if ($productCard) {

            //if product card use select with changed attributes.
            $listColors = '<select class="color-set" data-placeholder="Выберите цвет"><option></option>';
        } else {
            $listColors = '<select class="color-set" data-placeholder="Цвет"><option></option>';
        }
        foreach ($arrayColorsSizes as $stringColorSizes) {
            if (strstr($stringColorSizes, '=', true)) {
                $stringColor = strstr($stringColorSizes, '=', true);
                $stringSizes = substr(strstr($stringColorSizes, '='), 1);
                $listColors .= "<option value='{$stringColor}' data-val='{$stringSizes}'>{$stringColor}</option>";
            } else {
                $stringSizes = "";
                $listColors .= "<option value='{$stringColorSizes}' data-val='{$stringSizes}'>{$stringColorSizes}</option>";
            }
        }
        $listColors .= '</select>';
    } elseif (count($arrayColorsSizes == 1)) {
        $stringSizes = substr(strstr($arrayColorsSizes[0], '='), 1);
    }
    $listSizes = '';
    if ($stringSizes) {
        if ($productCard) {

            //if product card use select with changed attributes.
            $listSizes = '<select class="sel_size" id="select_size'.$id.'" data-placeholder="Выберите размер">
                                    <option></option>';
        } else {
            $listSizes = '<select class="sel_size" data-placeholder="Размер"><option></option>';
        }

        //set sizes from last colors data-val.
        $arraySizes = explode(',', $stringSizes);
        foreach ($arraySizes as $singleSize) {
            $listSizes .= "<option value = '{$singleSize}'>{$singleSize}</option>";
        }
        $listSizes .= "</select>";
    }

    return $listColors.$listSizes;
}

/**
 * Формується назва товару в списку блоків
 * відбувається провірка в назві товарі на наявність
 * елемента масива wrightCommodities, якщо збігів не знайдено тоді
 * назвою товару буде назва категорії змінену через масив translit.
 * @param string, raw $commodityName, which need to translate into right name.
 * @param  string $categoryName.
 * @return string, right name.
 */
function getCommodityName($commodityName, $categoryName)
{
    $rightNames = array(
        'футболка', 'майка', 'кофточка', 'блуза', 'брюки', 'юбка', 'свитшот',
        'кофта', 'туника', 'платье', 'костюм', 'ветровка', 'рубашка', 'блуза',
        'свитшот', 'пиджак', 'куртка', 'кардиган', 'комбинезон', 'жилет',
        'сарафан', 'шапка', 'майка', 'ремень', 'сумка', 'шарф', 'брелок',
        'футляр', 'чехол', 'костюмчик', 'костм', 'шорты', 'бриджи', 'леггинсы',
        'болеро', 'жакет', 'гольф', 'лосины', 'плащ', 'пальто', 'блузка', 'комплект',
        'легинсы', 'купальник', 'свитер', 'джинсы', 'штаны', 'баска',
        'комбидресс', 'кафта', 'воротник', 'джемпер', 'пончо', 'пояс', 'браслет',
        'колье', 'комбинeзон', 'бомбер', 'рюкзак', 'клатч', 'кошелек', 'камни',
        'подвеска', 'серьги', 'боди', 'парка', 'браслет', 'брошь', 'автопятки',
        'ботинки', 'сапоги', 'туфли', 'балетки', 'вьетнамки', 'пижама'
    );
    $translit   = array(
        "кафта" => "Кофта",
        "Штаны" => "Брюки",
        "костюмчик" => "Костюм",
        "костм" => "Костюм",
        "Платья" => "Платье",
        "Костюмы" => "Костюм",
        "Рубашки" => "Рубашка",
        "Юбки" => "Юбка",
        "Блузы" => "Блуза",
        "Свитшоты" => "Свитшот",
        "Пиджаки" => "Пиджак",
        "Спортивные костюмы" => "Костюм",
        "Куртки" => "Куртка",
        "Футболки" => "Футболка",
        "Кофты" => "Кофта",
        "Кардиганы" => "Кардиган",
        "Комбинезоны" => "Комбинезон",
        "Жилеты" => "Жилет",
        "Туники" => "Туника",
        "Сарафаны" => "Сарафан",
        "Шапки" => "Шапка",
        "Майки" => "Майка",
        "Ремни" => "Ремень",
        "Сумки" => "Сумка",
        "Шарфы" => "Шарф",
        "Брелки" => "Брелок",
        "Кошельки" => "Кошелёк",
        "Футляры, чехлы" => "Футляр"
    );

    $charsToReplace         = array("&#34;", ",", "-");
    $u                      = 'utf-8';
    $commodityName          = mb_strtolower($commodityName, $u);
    $commodityName          = str_replace($charsToReplace, " ", $commodityName);
    $commodityNameFirstWord = mb_strtolower(explode(" ", $commodityName)[0], $u);

    foreach ($rightNames as $rightName) {
        if (stristr($commodityName, $rightName) || $rightName == $commodityName || $rightName
            == $commodityNameFirstWord) {
            $nameFirstLetter = mb_substr(mb_strtoupper($rightName, $u), 0, 1, $u);
            $nameRestLetters = mb_substr($rightName, 1, strlen($rightName), $u);
            return $nameFirstLetter.$nameRestLetters;
        }
    }

    $commodityName = $categoryName;
    if (array_key_exists($commodityName, $translit)) {
        $commodityName = strtr($commodityName, $translit);
    }
    $nameFirstLetter = mb_substr(mb_strtoupper($commodityName, $u), 0, 1, $u);
    $nameRestLetters = mb_substr($commodityName, 1, strlen($commodityName), $u);

    return $nameFirstLetter.$nameRestLetters;
}

/**
 * Generate html with photo, selects additional photos and add them to html.
 * @param int $comId - commodity id.
 * @param string $alias commodity alias.
 * @return string html list of big and small photos, and additional photos.
 */
function getAdditionalPhoto($comId, $alias, $imageTitle, $imageAlt)
{
    global $glb;

    $photoDomen  = PHOTO_DOMAIN;
    $srcTitle    = "{$photoDomen}{$comId}/title.jpg";
    $srcSTitle   = "{$photoDomen}{$comId}/s_title.jpg";
    $imageTitle  = $imageTitle ? " title='{$imageTitle}'" : '';
    $imageAlt    = $imageAlt ? " alt='{$imageAlt}'" : '';
    $photosBig   = "
<div class='big-product-slider gallery'>
    <div>
        <div class='d-table'>
            <div class='d-table-cell'>
                <a href='{$srcTitle}' data-effect='mfp-zoom-in'>
                    <img src='{$srcTitle}' data-zoom-image='{$srcTitle}'{$imageTitle}{$imageAlt} onerror='this.src=\"/templates/shop/image/nophotoproductbig.jpg\"'/>
                </a>
            </div>
        </div>
    </div>
    ";
    $photosSmall = "
<div class='small-product-slider'>
    <div>
        <div class='small-slide-img'><img src='{$srcSTitle}'{$imageTitle}{$imageAlt} onerror='this.src=\"/templates/shop/image/nophotosproduct.jpg\"'/>
        </div>
    </div>
    ";

    //gets additional photos.
    $sql    = "SELECT * FROM shop_images 	WHERE com_id = {$comId}";
    $result = $glb['mysqli']->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $srcImg  = "{$photoDomen}{$comId}/{$row['img_id']}.jpg";
            $srcSImg = "{$photoDomen}{$comId}/s_{$row['img_id']}.jpg";
            $photosBig .= "
<div>
    <a href='{$srcImg}' data-effect='mfp-zoom-in'>
        <img src='{$srcImg}' data-zoom-image='{$srcImg}'{$imageTitle}{$imageAlt} />
    </a>
</div>
";
            $photosSmall .= "
<div><div class='small-slide-img'><img src='{$srcSImg}'{$imageTitle}{$imageAlt} /></div></div>
";
        }
    }
    $photosBig .= "</div>";
    $photosSmall .= "</div>";
    return $photosBig.$photosSmall;
}

/**
 * Настроює попап силедить за ценой.
 * @return string html
 */
function getWatchPrice()
{
    $userEmail = empty($_SESSION['user_email']) ? '' : $_SESSION['user_email'];
    return "<div class='popup-title'><p>добавлено в лист наблюдений</p></div>
<span class='message'>
     Уведомления о новых ценах придут на электронную почту
     <span class='message-mail'>{$userEmail}</span>
</span>
<span class='message'>
     Укажите адрес своей электронной почты для уведомлений о снижении цены<br><br>
     <input type='text' placeholder='e-mail'>
</span>
<button type='submit' class='btn btn-green btn-3d'>Ок</button>
<a class='go-to-list' href='#'>Перейти в лист наблюдений</a>
";
}

/**
 * Generates html cod for hit carusel on main page.
 * @global array $glb
 * @return string $lines list of commodities for hit slider
 */
function getCommoditiesForCarusel()
{
    global $glb;

    //провірка кешу
    $cache = new Cache("cache/static/slider-main-page-{$glb['cur_name']}.html",
        CACHE_TIME_SLIDER);
    if ($cache->check()) {
        return $cache->fileContent;
    } else {
        $saleHits = array();

        $result = $glb['mysqli']->query(<<<QUERYGCFC
            SELECT
              count(com.item_id) comments_count, commodity_ID, cod, commodity_price2, commodity_price,
              com_name, sc.alias, commodity_order, categoryID, sum(com.comment_rat) rating,
              commodity_add_date, category_id,
              commodity_select, com_sizes, size_count, cat_name, images_title, images_alt, brand_id
            FROM shop_commodity sc
	    	INNER JOIN `shop_commodities-categories`  scc
	    	  ON sc.commodity_ID = scc.commodityID
		    INNER JOIN shop_categories cat
		      ON cat.categories_of_commodities_ID = scc.categoryID
            LEFT JOIN comments com
              ON com.item_id = sc.commodity_ID
            WHERE commodity_visible = '1'
		    AND commodity_hit = '1'
		    AND cat.categories_of_commodities_parrent IN (264, 209, 212, 213, 261, 211, 266, 267, 210, 268)
            GROUP BY sc.commodity_ID
 		    ORDER BY RAND() LIMIT {$glb['slider_limit']}
QUERYGCFC
        );

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $saleHits[] = $row;
            }
        }

        $lines = getSliderTemplate($saleHits, 0, 1, 1);

        //запись кеша
        if ($cache->isOn()) {
            $cache->write('slider', $lines);
        }

        return $lines;
    }
}

/**
 * @param $catIds array
 * @return array with recomended commodities (30 items) from database, based on:
 *  - brand of current comm.
 *  - category of curent comm. // 20 items
 *  - most visited commodities (10 items) by all user, selected from shop_visited table.
 */
function getRecomendatedCommodities($catIds)
{

    global $glb;

    // провірка кеша
    $cache = new Cache(
        "cache/static/slider-recommendations_{$catIds[0]}_{$catIds[1]}.html",
        60 * 60 * 24
    );
    if ($cache->check()) {
        return $cache->fileContent;
    } else {
        $cat = '';
        foreach ($catIds as $catId) {
            $cat .= $catId.',';
        }
        $cat    = substr($cat, 0, -1);
        $sql    = "
(SELECT COUNT(com.item_id) comments_count, commodity_ID, cod, commodity_price2, commodity_price, com_name,
commodity_order, sc.alias, brand_id, commodity_select, com_sizes, size_count, sum(com.comment_rat) rating,
commodity_add_date, category_id
FROM shop_commodity sc
INNER JOIN `shop_commodities-categories`  scc ON sc.commodity_ID = scc.commodityID
LEFT JOIN comments com ON com.item_id = sc.commodity_ID
WHERE commodity_visible='1'
AND categoryID IN  ({$cat})
GROUP BY commodity_ID
LIMIT 20)
    UNION
(SELECT COUNT(com.item_id) comments_count, commodity_ID, cod, commodity_price2, commodity_price, com_name,
commodity_order, sc.alias, brand_id, commodity_select, com_sizes, size_count, sum(com.comment_rat) rating
FROM shop_commodity sc
INNER JOIN shop_visited sv ON sv.com_id = sc.commodity_ID
LEFT JOIN comments com ON com.item_id = sc.commodity_ID
WHERE commodity_visible='1'
GROUP BY commodity_ID)
";
        $result = $glb['mysqli']->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['cat_name'] = $glb['cat_names'][$row['brand_id']];
                $recomendate[]   = $row;
            }
        }
        shuffle($recomendate);
        $lines = getSliderTemplate($recomendate);

        //запись кеша
        if ($cache->isOn()) {
            $cache->write('rec', $lines, $catIds);
        }

        return $lines;
    }
}

/**
 * @return array with lastview commodities from database, based on sessions.
 */
function getLastViewCommodities()
{
    global $glb;

    if (count($_SESSION['last_view']) > 0) {
        $lastViewCommodities = $_SESSION['last_view'];
        if (count($lastViewCommodities) > 30) {
            $lastViewCommodities = array_slice($lastViewCommodities, 30);
        }
        $lastViewCommoditiesToString = '';
        foreach ($lastViewCommodities as $lastViewCommodity) {
            if ($lastViewCommodity) {
                $lastViewCommoditiesToString .= $lastViewCommodity.',';
            }
        }
        $lastViewCommoditiesToString = substr($lastViewCommoditiesToString, 0,
            -1);
        $sql                         = "
    SELECT COUNT(com.item_id) comments_count, commodity_ID, cod, commodity_price2, commodity_price, com_name,
    commodity_order, sc.alias, brand_id, commodity_select, com_sizes, size_count, sum(com.comment_rat) rating
	FROM shop_commodity sc
	LEFT JOIN comments com ON com.item_id = sc.commodity_ID
	WHERE commodity_visible='1'
	AND commodity_ID IN  ({$lastViewCommoditiesToString})
    GROUP BY commodity_ID
    ";
        $result                      = $glb['mysqli']->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['cat_name'] = $glb['cat_names'][$row['brand_id']];
                $lastView[]      = $row;
            }
        }
    } else {
        $lastView = false;
    }

    return $lastView;
}

/**
 * Convert commodities to template.
 * @param $commodities - array with commodities, selected from database.
 * @param $pagesCount int -
 * @param $page int
 * @param $mainSlider int
 * @return string html - string with needle commodities
 */
function getSliderTemplate($commodities, $pagesCount = 0, $page = 1,
                           $mainSlider = 0)
{
    if ($commodities) {

        global $glb;

        // stiker adapter
        $stiker = new Stikers\Stikers($glb["templates"], $glb["cur_val"]);

        $lines = '';

        // action discount 40%
        if (EXIST_ACTION_BRANDS != '') {
            $actionBrandsArr = explode(',', EXIST_ACTION_BRANDS);
        }
        // end

        foreach ($commodities as $row) {

            $id          = $row['commodity_ID'];
            $brandName   = isset($row['brand_id']) ? $glb['cat_names'][$row['brand_id']]
                    : $row['cat_name'];
            $alias       = $row['alias'];
            $url         = "/product/$id/$alias.html";
            $src         = PHOTO_DOMAIN."{$id}/s_title.jpg";
            $imagesTitle = $row['images_title'] ? ' title="'.$row['images_title'].'"'
                    : '';
            $imagesAlt   = $row['images_alt'] ? ' alt="'.$row['images_alt'].'"' : '';

            //like
            if (is_array($_SESSION['liked']) && in_array($id, $_SESSION['liked'])) {
                $active = ' active';
            } else {
                $active = '';
            }

            //sizes
            if ($row['commodity_select']) {
                $sizes = generateSizesColors($row['commodity_select'], $id);
            } else {
                $sizes = generateSizes($row['com_sizes'], $row['size_count'],
                    $id);
            }

            $rating  = generateRating($row['rating'], $row['comments_count']);
            $comCode = mb_strlen($row['cod'], 'utf8') > 12 ?
                mb_substr($row['cod'], 0, 12, 'utf8') : $row['cod'];

            $glb["templates"]->set_tpl('{$id}', $id);
            $glb["templates"]->set_tpl('{$pagesCount}', $pagesCount);
            $glb["templates"]->set_tpl('{$currentPage}', $page);
            $glb["templates"]->set_tpl('{$active}', $active);
            $glb['templates']->set_tpl('{$trueRating}', $rating[1]);
            $glb["templates"]->set_tpl('{$rating}', $rating[0]);
            $glb["templates"]->set_tpl('{$commentsCount}',
                generateQuantityOfResponse($row['comments_count']));
            $glb["templates"]->set_tpl('{$sizes}', $sizes);
            $glb["templates"]->set_tpl('{$src}', $src);
            $glb["templates"]->set_tpl('{$imagesTitle}', $imagesTitle);
            $glb["templates"]->set_tpl('{$imagesAlt}', $imagesAlt);
            $glb["templates"]->set_tpl('{$comName}',
                getCommodityName($row['com_name'], $brandName));
            $glb["templates"]->set_tpl('{$cod}', $comCode);
            $glb["templates"]->set_tpl('{$brandName}', $brandName);
            $glb["templates"]->set_tpl('{$priceRozn}',
                round($row['commodity_price'] * $glb["cur_val"], 0));
            $glb["templates"]->set_tpl('{$priceOpt}',
                getOptPrice($row['commodity_price2'], $row['brand_id']));
            $glb["templates"]->set_tpl('{$cur_v}', $glb["cur"][$glb["cur_id"]]); //Показати валют
            $glb["templates"]->set_tpl('{$url}', $url);
            $glb["templates"]->set_tpl('{$classSlider}',
                $mainSlider ? ' slick-slide' : '');

            $stiker->getStikersSave('{$stikerSave}', $row['commodity_price'],
                getOptPrice($row['commodity_price2'], $row['brand_id']));

            // action discount 40%
            if (isset($actionBrandsArr) && in_array($row['brand_id'],
                    $actionBrandsArr)) {
                $glb["templates"]->set_tpl('{$bgColor}',
                    'style="background-color: #FF006E;"');
                $glb["templates"]->set_tpl('{$colorOpt}',
                    'style="font-size: 17px;color: #FF006E;font-weight: 900;"');
                $glb["templates"]->set_tpl('{$TDLineTr}',
                    'style="text-decoration: line-through;"');
            } else {

                $glb["templates"]->set_tpl('{$bgColor}', '');
                $glb["templates"]->set_tpl('{$colorOpt}', '');
                $glb["templates"]->set_tpl('{$classGlem}', '');
                $glb["templates"]->set_tpl('{$TDLineTr}', '');
            }
            // end
            // stiker new
            $stiker->getStikrersNew('{$stikerNew}', $row['commodity_add_date']);

            // stiker gift
            $stiker->getStikerGift('{$stikerGift}', $row['commodity_price2'],
                $row['brand_id'], $row['category_id']);

            $lines .= $glb["templates"]->get_tpl('commodity.block');
        }
    } else {

        $lines = '<div style="height: 363px"></div>';
    }

    return $lines;
}

/**
 *
 * @global type $glb
 * @param type $categoryId
 * @return type
 */
function getBreadCrumb($categoryId)
{
    global $glb;
    $categories = array();
    $breadCrumb = '';

    while ($categoryId != 0) {
        $categories[] = $categoryId;
        $categoryId   = $glb['cat_parents'][$categoryId];
    }
    $categories = array_reverse($categories);

    foreach ($categories as $categoryId) {
        $sufix = '';

        if (10 == $categoryId) {
            $sufix = '&menu=brands';
        } elseif (in_array($categoryId, array(12, 264, 265, 209, 212, 213))) {
            $sufix = '&menu=wear';
        } elseif (in_array($categoryId, array(89, 211, 266, 261))) {
            $sufix = '&menu=shoes';
        } elseif (in_array($categoryId, array(84, 267, 210, 268))) {
            $sufix = '&menu=accessories';
        }

        $url = $glb['cat_aliases'][$categoryId] != "" ? "/c{$categoryId}-{$glb['cat_aliases'][$categoryId]}$sufix/"
                : "/c{$categoryId}$sufix/";

        $title = $glb['titles'][$categoryId] != '' ? " title='{$glb['titles'][$categoryId]}'"
                : '';
        $breadCrumb .= "<li><a href='$url'$title>{$glb['cat_names'][$categoryId]}</a></li>";
    }

    return "<li>
                <a href='{$_SESSION['last_page']}'>Назад</a>
            </li>
            <li>
                <a href='/'>Главная</a>
            </li>
            {$breadCrumb}";
}

/**
 * Set meta for category page
 * @param $parentCatId int
 * @param $catName string
 */
function setMeta($parentCatId, $catName)
{
    global $glb;

    if (10 == $parentCatId) {
        $glb['title']       = "Одежда $catName купить оптом и в розницу | MakeWear";
        $glb['description'] = "Лучшие цены на одежду $catName от производителя с доставкой по Украине.";
        $glb['description'] .= " Оптовая и розничная продажа";
        $glb['keywords']    = "$catName купить, $catName опт, $catName поставщик, $catName производитель";
    } elseif (in_array($parentCatId,
            array(264, 209, 212, 213, 261, 211, 266, 267, 210, 268))) {
        if (in_array($parentCatId, array(209, 210, 211))) {
            $sex = 'мужские';
        } elseif (in_array($parentCatId, array(212, 213, 266, 268))) {
            $sex = 'детские';
        } else {
            $sex = 'женские';
        }

        if ('детские' === $sex) {
            $glb['description'] = "Огромный выбор: $catName $sex по ценам от производителя с доставкой по Украине";
        } else {
            $glb['description'] = "Купите $catName $sex по лучшим ценам от производителя.";
            $glb['description'] .= " Опт и розница с доставкой по Украине";
        }

        $glb['title']    = "$catName купить оптом и в розницу | MakeWear";
        $glb['keywords'] = "$catName купить, $catName $sex, $catName оптом";
    } elseif (in_array($parentCatId, array(12, 89, 84, 265))) {

    }
}

/**
 * @param type $comId
 * @param type $alias
 * @param type $description
 * @param type $name
 * @param type $brandName
 * @param type $cod
 * @param type $priceRozn
 * @param type $priceOpt
 * @return string
 */
function createMetaForYaShare(
$comId, $alias, $description, $name, $brandName, $cod, $priceRozn, $priceOpt
)
{
    $image = PHOTO_DOMAIN."{$comId}stitle/{$alias}.jpg";
    $title = $name." ".$brandName." ".$cod;
    $desc  = str_replace(
        array('/', '<', '>'), '', trim(stripslashes(strip_tags($description)))
    );
    $price = 'Розничаная цена - '.$priceRozn.' грн.
    ';
    if ($priceOpt && $priceOpt > 0 && $priceOpt != $priceRozn) {
        $price = 'Оптовая цена - '.$priceOpt.' грн.
    ';
    }
    $desc = $price.$desc;
    $meta = '<meta property="og:image" content="'.$image.'" />';
    $meta .= '<meta property="og:description" content="'.$desc.'"/>';
    $meta .= '<meta property="og:title" content="'.$title.'"/>';
    return $meta;
}

/**
 *
 *
 * @global array $glb
 * @return type
 */
function getSearch()
{
    global $glb;

    $items           = array();
    $categoryFilter  = $inCategory      = $nothingNotFound = '';
    $pagesCount      = 1;
    $page            = 1;
    $perPage         = 30;
    $categoryName    = filter_input(INPUT_POST, 'search_cat_name',
        FILTER_SANITIZE_STRING);
    $needle          = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $page         = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
        $perPage      = filter_input(INPUT_GET, 'perPage',
            FILTER_SANITIZE_STRING);
        $categoryName = filter_input(INPUT_GET, 'search_cat_name',
            FILTER_SANITIZE_STRING);
        $needle       = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
    }

    $startPage = $page == 1 || $page === false ? 0 : $perPage * ($page - 1);
    $limit     = $page == 1 || $page === false ? '' : "LIMIT {$startPage}, {$perPage}";


    if ($categoryName) {

        $ids            = array_keys($glb['cat_names'], $categoryName);
        $id             = reset($ids);
        $ids            = implode(',', $ids);
        $categoryFilter = "AND cat.categories_of_commodities_ID IN ({$ids})";
        $inCategory     = 'в категории "<span class="search-category-name">'.$categoryName.'</span>"';
    }

    $query = <<<QUERYGS1
    SELECT SQL_CALC_FOUND_ROWS
      count(com.item_id) comments_count, commodity_ID, cod, commodity_price2, commodity_price,
      com_name, sc.alias, commodity_order, categoryID, sum(com.comment_rat) rating,
      commodity_select, com_sizes, size_count, cat.cat_name, cat.images_title, cat.images_alt, brand_id,
      commodity_add_date, category_id
    FROM shop_commodity sc
	INNER JOIN `shop_commodities-categories`  scc ON sc.commodity_ID = scc.commodityID
	INNER JOIN shop_categories cat ON cat.categories_of_commodities_ID = scc.categoryID
	INNER JOIN shop_categories brand ON brand.categories_of_commodities_ID = sc.brand_id
    LEFT JOIN comments com ON com.item_id = sc.commodity_ID
    WHERE commodity_visible = '1'
    $categoryFilter
    AND (cod LIKE '%$needle%'
      OR com_name LIKE '%$needle%'
      OR brand.cat_name LIKE '%$needle%'
    )
    GROUP BY sc.commodity_ID
    $limit
QUERYGS1;

    $result = $glb['mysqli']->query($query);
    if ($result->num_rows > 0) {
        $i           = 0;
        $totalAmount = $glb['mysqli']
                ->query("SELECT FOUND_ROWS() as rows")
                ->fetch_object()
            ->rows;
        $pagesCount  = ceil($totalAmount / $perPage);
        $page        = $page > $pagesCount ? $pagesCount : $page;

        while ($row = $result->fetch_assoc()) {

            if ($perPage > $i) {
                $row['cat_name'] = $glb['cat_names'][$row['brand_id']];
                $items[]         = $row;
            }

            $i++;
        }
    } else {
        $nothingNotFound = ' - ничего не найдено';
    }

    $lines = getSliderTemplate($items, $pagesCount, $page);

    if (filter_input(INPUT_SERVER, 'HTTP_X_PJAX', FILTER_VALIDATE_BOOLEAN) === TRUE) {
        return $lines;
    }

    //per page select
    $perPageSelect = '';
    foreach (array(30, 60, 120) as $pages) {
        $selected = $pages == $perPage ? ' selected' : '';
        $perPageSelect .= "<option value='$pages'{$selected}>$pages</option>";
    }

    $breadCrumbs = <<<HTMLGS
        <div id="search-status">Поиск
        &#171;<span class="search-text">$needle</span>&#187;
            $inCategory
            $nothingNotFound
        </div>
HTMLGS;

    if ($nothingNotFound) {
        return $breadCrumbs;
    }


    $glb['templates']->set_tpl('{$totalAmount}', (int) $totalAmount);
    $glb['templates']->set_tpl('{$breadCrumb}', $breadCrumbs);
    $glb['templates']->set_tpl('{$perPageSelect}', $perPageSelect);
    $glb['templates']->set_tpl('{$topFilters}', '');
    $glb['templates']->set_tpl('{$topMenuFilter}', '');
    $glb['templates']->set_tpl('{$bottomMenuFilter}', '');
    $glb['templates']->set_tpl('{$products}', $lines);
    $glb['templates']->set_tpl('{$categoryId}', $id);
    $glb['templates']->set_tpl('{$categoryAlias}', $glb['cat_aliases'][$id]);
    $glb['templates']->set_tpl('{$info}',
        $glb['templates']->get_tpl('main.info'));


    return $glb['templates']->get_tpl('catalog.main');
}

/**
 * Возвращаем оптовый прайс для отображения в шаблонах
 *
 * @global array $glb
 * @param string $priceOpt - opt price for DB
 * @param int $brandId
 * @return int - optPrice
 */
function getOptPrice($priceOpt, $brandId)
{
    global $glb;
    if ($brandId == 16 || $brandId == 1 || $brandId == 49) {
        return 0;
    }
    return round($priceOpt * $glb["cur_val"], 0);
}

function getProductBlock($parameter, $page = null, $perPage = null)
{
    global $glb;

    $items     = array();
    $perPage   = $perPage ? $perPage : 30;
    $startPage = $page > 1 ? $perPage * ($page - 1) : 0;
    $limit     = $page > 1 ? "LIMIT {$startPage}, {$perPage}" : '';

    if ('platie-v-podarok' === $parameter) {

        $query = <<<GCSQL
        SELECT SQL_CALC_FOUND_ROWS
          count(com.item_id) comments_count, commodity_ID, cod, commodity_price2, commodity_price,
          com_name, sc.alias, commodity_order, category_id, sum(com.comment_rat) rating,
          commodity_add_date,
          commodity_select, com_sizes, size_count, brands.cat_name, brand_id, cats.images_title,
          cats.images_alt, cats.alias catAlias, @name:='Платье в подарок' name, @url:='platie-v-podarok' url, @blockId:=5 blockId
        FROM shop_commodity sc
		JOIN shop_categories cats
		  ON cats.categories_of_commodities_ID = sc.category_id
		JOIN shop_categories brands
		  ON brands.categories_of_commodities_ID = sc.brand_id
        LEFT JOIN comments com
          ON com.item_id = sc.commodity_ID
        WHERE commodity_visible = '1'
        AND brands.visible = '1'
        AND cats.visible = '1'
        AND sc.category_id = 8
        AND sc.commodity_price2 BETWEEN 1 AND 200
        AND sc.brand_id NOT IN (1, 49)
        GROUP BY sc.commodity_ID
        {$limit}
GCSQL;
    } else {
        $query = <<<GCSQL
        SELECT SQL_CALC_FOUND_ROWS
          count(com.item_id) comments_count, commodity_ID, cod, commodity_price2, commodity_price,
          com_name, sc.alias, commodity_order, category_id, sum(com.comment_rat) rating,
          commodity_add_date,
          commodity_select, com_sizes, size_count, brands.cat_name, brand_id, cats.images_title,
          cats.images_alt, cats.alias catAlias, sb.name, sb.url, sb.id blockId
        FROM shop_commodity sc
		JOIN shop_categories cats
		  ON cats.categories_of_commodities_ID = sc.category_id
		JOIN shop_categories brands
		  ON brands.categories_of_commodities_ID = sc.brand_id
        JOIN shop_blocks_products sbp
          ON sc.commodity_ID = sbp.com_id
        JOIN shop_blocks sb
          ON sb.id = sbp.block_id AND sb.url = '$parameter'
        LEFT JOIN comments com
          ON com.item_id = sc.commodity_ID
        WHERE commodity_visible = '1'
        AND brands.visible = '1'
        AND cats.visible = '1'
        GROUP BY sc.commodity_ID
        {$limit}
GCSQL;
    }

    $result = $glb['mysqli']->query($query);

    if ($result && $result->num_rows > 0) {

        $i = 0;

        $totalAmount = $glb['mysqli']
                ->query("SELECT FOUND_ROWS() as rows")
                ->fetch_object()
            ->rows;

        $pagesCount = ceil($totalAmount / $perPage);

        while ($row = $result->fetch_assoc()) {

            $breadCrumbLinkName = $row['name'];
            $breadCrumbLink     = $row['url'];
            $blockId            = $row['blockId'];
//            $metaTitle          = $row['blockId'];
//            $metaDescription    = $row['blockId'];

            if ($i < $perPage) {
                $items[] = $row;
            }

            $i++;
        }

        $lines = getSliderTemplate(
            $items, $pagesCount, $page == false ? 1 : $page
        );

        //якщо пагинация то повертаєм тільки список товарів
        if (filter_input(INPUT_SERVER, 'HTTP_X_PJAX', FILTER_VALIDATE_BOOLEAN) === TRUE
            &&
            $page
        ) {
            return $lines;
        }

        //set meta
//        $glb['title']       = $metaTitle;
//        $glb['description'] = $metaDescription;
        //canonical on 1 page
        /*  if ($page > 1) {
          $glb['canonical'] = "/c$id-{$items[0]['catAlias']}/";
          }
         */

        //хлебные крошки
        $breadCrumb = <<<HTMLGBC
        <li>
            <a href='{$_SESSION['last_page']}'>Назад</a>
        </li>
        <li>
            <a href='/'>Главная</a>
        </li>
        <li>
            <a href='/catalog/$breadCrumbLink'>$breadCrumbLinkName</a>
        </li>
HTMLGBC;

        //select per page
        $perPageSelect = '';
        foreach (array(30, 60, 120) as $pages) {
            $selected = $pages == $perPage ? ' selected' : '';
            $perPageSelect .= "<option value='$pages'{$selected}>$pages</option>";
        }

        //list blocks
        $listBlocks = getListBlocks($blockId);

        $glb['templates']->set_tpl('{$breadCrumb}', $breadCrumb);
        $glb['templates']->set_tpl('{$perPageSelect}', $perPageSelect);
        $glb['templates']->set_tpl('{$totalAmount}', $totalAmount);
        $glb['templates']->set_tpl('{$listBlocks}', $listBlocks);
        $glb['templates']->set_tpl('{$products}', $lines);
        $glb['templates']->set_tpl('{$info}',
            $glb['templates']->get_tpl('main.info'));

        $lines = $glb['templates']->get_tpl('product.block.main');
    } else {
        $lines = getContentMessage();
    }

    return $lines;
}

/**
 *
 * @param type $id
 * @return type
 */
function getListBlocks($id)
{
    $lines  = '<div class="list-blocks"><ul>';
    $blocks = new \Modules\Blocks();
    $blocks->getBlocks();

    foreach ($blocks->blocks as $block) {
        if ($block->position < 10) {
            $active = $block->id == $id ? " class='active'" : '';
            $lines .= "<li><a href='/catalog/{$block->url}'{$active}>$block->name</a></li>";
        }
    }

    return $lines.'</ul></div>';
}
