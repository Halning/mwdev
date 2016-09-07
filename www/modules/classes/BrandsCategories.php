<?php
/**
 * Created by PhpStorm.
 * Date: 1/7/16
 * Time: 3:01 PM
 */

namespace Modules;

/**
 * Class BrandsCategories
 * Використовується для підгрузки аяксом категорій брендів чи брендів категорів і кількістю товарів в кожному бренді
 * чи категорії. На головній сторінці для слайдера брендів і на сторінці товарів.
 * @package Modules
 */
class BrandsCategories
{
    /**
     * @var object mysqli instance
     */
    private $mysqli;

    /**
     * @var string mysql query
     */
    private $query;

    /**
     * Вибирає відповідний запит до бази даних.
     * @param int $switch or null
     */
    public function __construct($switch)
    {
        $this->mysqli = MySQLi::getInstance()->getConnect();
        if ($switch === 1) {
            $this->setQueryForCategoryBrands();
        } else {
            $this->setQueryForBrandCategories();
        }

        return $this;
    }

    /**
     * Робить запит до бази даних і повертає масив з брендами категорій чи категоріями брендів
     * @return array
     */
    public function getItems()
    {
        $items = array();
        $result = $this->mysqli->query($this->query);
        while ($row = $result->fetch_assoc()) {
            $items[$row['brand_id']][] = array(
                'count' => $row['quantity'],
                'name' => $row['cat_name'],
                'id' => $row['catId'],
            );
        }

        return $items;
    }

    /**
     * Присвоює запит для категорії брендів
     * @return $this
     */
    private function setQueryForCategoryBrands()
    {
        $this->query = <<<QUERY1
            SELECT
                count(c.commodity_ID) quantity, sc.categories_of_commodities_ID brand_id, cat.cat_name, brand_id catId
            FROM `shop_commodities-categories` scc
            INNER JOIN shop_commodity c ON c.commodity_ID = scc.commodityID
            INNER JOIN shop_categories sc ON scc.categoryID = sc.categories_of_commodities_ID
            inner JOIN shop_categories cat ON c.brand_id = cat.categories_of_commodities_ID
            WHERE c.commodity_visible = 1
            AND sc.categories_of_commodities_parrent IN (264, 209, 212, 213, 261, 211, 266, 267, 210, 268)
            GROUP BY categoryID, brand_id
                UNION
            SELECT
                count(c.commodity_ID) quantity, brand_id, cat_name, categories_of_commodities_ID catId
            FROM `shop_commodities-categories` scc
            INNER JOIN shop_commodity c ON c.commodity_ID = scc.commodityID
            INNER JOIN shop_categories sc ON scc.categoryID = sc.categories_of_commodities_ID
            WHERE c.commodity_visible = 1
            AND sc.categories_of_commodities_parrent IN (264, 209, 212, 213, 261, 211, 266, 267, 210, 268)
            GROUP BY brand_id, categoryID
QUERY1;

        return $this;
    }

    /**
     * Присвоює запит для брендів категорій
     * @return $this
     */
    private function setQueryForBrandCategories()
    {
        $this->query = <<<QUERY2
            SELECT
                count(c.commodity_ID) quantity, brand_id, cat_name, sc.categories_of_commodities_ID catId
            FROM `shop_commodities-categories` scc
            INNER JOIN shop_commodity c ON c.commodity_ID = scc.commodityID
            INNER JOIN shop_categories sc ON scc.categoryID = sc.categories_of_commodities_ID
            WHERE c.commodity_visible = 1
            AND sc.categories_of_commodities_parrent IN (264, 209, 212, 213, 261, 211, 266, 267, 210, 268)
            GROUP BY brand_id, categoryID
QUERY2;

        return $this;
    }
}
