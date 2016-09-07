<?php
/**
 * Created by PhpStorm.
 * User: webler
 * Date: 22.05.16
 * Time: 14:32
 */

namespace Modules;

class Products
{
    public $id;
    public $order;
    public $article;
    public $name;
    public $alias;
    public $retailPrice;
    public $salePrice;
    public $description;
    public $brandId;
    public $sizes = '';
    public $selectColorSizes = '';
    public $duplicate = '';
    public $fromUrl = '';
    public $commodityAddDate;
    public $sizeCount = '';
    public $additionalImagesIds = array();

    private $_db;

    public function __construct()
    {
        $this->_db = MySQLi::getInstance()->getConnect();
    }

    public function getProductByArticle()
    {
        $result = $this->_db->query(<<<QUERYGPBA
            SELECT commodity_ID id
            FROM shop_commodity
            WHERE cod = '{$this->article}'
            LIMIT 1
QUERYGPBA
        );

        if ($row = $result->fetch_object()) {
            $this->id = $row->id;
        }

        return $this;
    }

    public function persist()
    {
        $query = <<<QUERYAP
            INSERT INTO shop_commodity(
              from_url, cod, com_name, alias, commodity_price, commodity_price2, commodity_add_date,
              commodity_order, size_count, com_fulldesc, com_sizes, brand_id, commodity_select, duplicate
            ) VALUES(?,?,?,?,?,?, NOW(),?,?,?,?,?,?,?)
QUERYAP;

        $stmt = $this->_db->stmt_init();
        if (!$stmt->prepare($query)) {
            die('Insert shop_commodity s Error ('.$this->_db->errno.') '.$this->_db->error);
        }

        if (!$stmt->bind_param(
            'ssssddisssiss',
            $this->fromUrl,
            $this->article,
            $this->name,
            $this->alias,
            $this->retailPrice,
            $this->salePrice,
            $this->order,
            $this->sizeCount,
            $this->description,
            $this->sizes,
            $this->brandId,
            $this->selectColorSizes,
            $this->duplicate
        )) {
            die('Insert shop_commodity Error ('.$stmt->errno.') '.$stmt->error);
        }

        if (!$stmt->execute()) {
            die('Insert shop_commodity Error ('.$stmt->errno.') '.$stmt->error);
        }

        $this->id = $stmt->insert_id;

        if (!$stmt->close()) {
            die('Insert shop_commodity Error ('.$stmt->errno.') '.$stmt->error);
        }

        return $this;
    }

    public function addProductToCommodityCategories()
    {
        $stmt = $this->_db->prepare(<<<QUERYAPCC
            INSERT INTO `shop_commodities-categories`(
              commodityID, categoryID
            ) VALUES (?, ?)
QUERYAPCC
        );

        $stmt->bind_param('ii', $this->id, $this->brandId);
        $stmt->execute();
        $stmt->close();

        return $this;
    }

    public function setCategory($comId, $categoryId)
    {
        $stmt = $this->_db->prepare(<<<QUERYSC
            UPDATE shop_commodity
            SET category_id = ?
            WHERE commodity_ID = ?
QUERYSC
        );

        $stmt->bind_param('ii', $categoryId, $comId);
        $stmt->execute();
        $stmt->close();

        return $this;
    }

    /**
     * @return array $articles
     */
    public function getArticleOfAllProducts()
    {
        $articles = array();

        $result = $this->_db->query(<<<QUERYGAPA
            SELECT cod
            FROM shop_commodity
            ORDER BY commodity_ID
QUERYGAPA
        );

        while ($row = $result->fetch_object()) {
            $articles[] = $row->cod;
        }

        return $articles;
    }

    public function setAdditionalProductImage()
    {
        $stmt = $this->_db->prepare(<<<QUERYSAPI
            INSERT INTO shop_images SET com_id=?
QUERYSAPI
        );

        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $this->additionalImagesIds[] = $stmt->insert_id;
        $stmt->close();

        return $this;
    }

    /**
     * Delete additional images from shop_images
     * @return $this
     */
    public function deleteAdditionalProductImage()
    {
        $stmt = $this->_db->prepare(<<<QUERYDAPI
            DELETE FROM shop_images
            WHERE com_id = ?
QUERYDAPI
        );

        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $stmt->close();

        return $this;
    }

    public function getProductBrand($comId)
    {
        $stmt = $this->_db->prepare(<<<QUERYGPB
            SELECT
              spb.brand_id brandId, cat.alias
            FROM shop_products_brands spb
            JOIN shop_categories cat
              ON cat.categories_of_commodities_ID = spb.brand_id
            WHERE spb.product_id = ?
            LIMIT 1
QUERYGPB
        );

        $stmt->bind_param('i', $comId);
        $stmt->execute();
        $stmt->bind_result($brandId, $alias);

        while ($stmt->fetch()) {
            $productBrand = array(
                'brandId' => $brandId,
                'alias'   => $alias,
            );
        }

        $stmt->close();

        return $productBrand;
    }

    public function setBrandIntoProductBrands($comId, $brandId)
    {
        $stmt = $this->_db->prepare(<<<QUERYSBP
            INSERT IGNORE INTO shop_products_brands (product_id, brand_id)
            VALUES (?, ?)
QUERYSBP
        );

        $stmt->bind_param('ii', $comId, $brandId);
        $stmt->execute();
        $stmt->close();

        return $this;
    }

    public function setCategoryIntoProductBrands($comId, $categoryId)
    {
        $stmt = $this->_db->prepare(<<<QUERYSCP
            UPDATE shop_products_brands
            SET category_id = ?
            WHERE product_id = ?
QUERYSCP
        );

        $stmt->bind_param('ii', $categoryId, $comId);
        $stmt->execute();
        $stmt->close();

        return $this;
    }
}
