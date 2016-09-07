<?php
/**
 * Created by PhpStorm.
 * User: volodini
 * Date: 12/29/15
 * Time: 10:10 PM
 * Генерує сторінку товарів
 */

namespace Modules;

class Categories
{
    private $templates;

    private $db;

    private $categories = array();

    private $accessoriesWomen = '';

    private $accessoriesMen = '';

    private $accessoriesChildren = '';

    private $wearWomen = '';

    private $wearMen = '';

    private $wearChildren = '';

    private $shoesWomen = '';

    private $shoesMen = '';

    private $shoesChildren = '';

    private $brands = '';

    private $menus = array('brands', 'wear', 'shoes', 'accessories');

    private $menu;

    private $genders = array('men', 'women', 'children');

    private $gender = 'women';


    /**
     * Use global template. Connect to DB.
     */
    public function __construct()
    {
        global $glb;

        $this->templates = $glb['templates'];
        $this->db = $glb['mysqli'];
        $menu = filter_input(INPUT_GET, 'menu', FILTER_SANITIZE_STRING);
        $this->menu = in_array($menu, $this->menus) ? $menu : false;

        if (preg_match('/mujsk/', $_SERVER['REQUEST_URI'])) {
            $this->gender = 'men';
        } elseif (preg_match('/dlya_devochek/', $_SERVER['REQUEST_URI']) ||
            preg_match('/dlya_malchikov/', $_SERVER['REQUEST_URI']) ||
            preg_match('/detsk/', $_SERVER['REQUEST_URI'])
        ) {
            $this->gender = 'children';
        }

        return $this;
    }

    /**
     * @return string html сторінки товарів.
     */
    public function render()
    {
        $this->getCategories();
        foreach ($this->categories as $category) {
            switch ($category['parent_id']) {
                case 264:
                    $this->wearWomen .= $this->getSingleCategory(
                        $category['id'],
                        $category['cat_name'],
                        $category['alias']
                    );
                    break;
                case 261:
                    $this->shoesWomen .= $this->getSingleCategory(
                        $category['id'],
                        $category['cat_name'],
                        $category['alias']
                    );
                    break;
                case 267:
                    $this->accessoriesWomen .= $this->getSingleCategory(
                        $category['id'],
                        $category['cat_name'],
                        $category['alias']
                    );
                    break;
                case 209:
                    $this->wearMen .= $this->getSingleCategory(
                        $category['id'],
                        $category['cat_name'],
                        $category['alias']
                    );
                    break;
                case 210:
                    $this->accessoriesMen .= $this->getSingleCategory(
                        $category['id'],
                        $category['cat_name'],
                        $category['alias']
                    );
                    break;
                case 211:
                    $this->shoesMen .= $this->getSingleCategory(
                        $category['id'],
                        $category['cat_name'],
                        $category['alias']
                    );
                    break;
                case 212:
                    $this->wearChildren .= $this->getSingleCategory(
                        $category['id'],
                        $category['cat_name'],
                        $category['alias']
                    );
                    break;
                case 213:
                    $this->wearChildren .= $this->getSingleCategory(
                        $category['id'],
                        $category['cat_name'],
                        $category['alias']
                    );
                    break;
                case 266:
                    $this->shoesChildren .= $this->getSingleCategory(
                        $category['id'],
                        $category['cat_name'],
                        $category['alias']
                    );
                    break;
                case 268:
                    $this->accessoriesChildren .= $this->getSingleCategory(
                        $category['id'],
                        $category['cat_name'],
                        $category['alias']
                    );
                    break;
                case 10:
                    $this->brands .= $this->getSingleCategory(
                        $category['id'],
                        $category['cat_name'],
                        $category['alias'],
                        true
                    );
                    break;
            }
        }

        $this->templates->set_tpl('{$wearWomen}', $this->wearWomen);
        $this->templates->set_tpl('{$shoesWomen}', $this->shoesWomen);
        $this->templates->set_tpl('{$accessoriesWomen}', $this->accessoriesWomen);
        $this->templates->set_tpl('{$wearMen}', $this->wearMen);
        $this->templates->set_tpl('{$shoesMen}', $this->shoesMen);
        $this->templates->set_tpl('{$accessoriesMen}', $this->accessoriesMen);
        $this->templates->set_tpl('{$wearChildren}', $this->wearChildren);
        $this->templates->set_tpl('{$shoesChildren}', $this->shoesChildren);
        $this->templates->set_tpl('{$accessoriesChildren}', $this->accessoriesChildren);
        $this->templates->set_tpl('{$brands}', $this->brands);

        foreach ($this->menus as $menu) {
            $active = $this->menu === $menu ? ' active' : '';
            $this->templates->set_tpl('{$' . $menu . 'Active}', $active);
        }

        foreach ($this->genders as $gender) {
            $active = $this->gender === $gender ? ' active' : '';
            $this->templates->set_tpl('{$' . $gender . 'Active}', $active);
        }

        return $this->templates->get_tpl('category.commodities');
    }

    /**
     * Гет з БД всіх категорій товарів
     * @return $this
     */
    public function getCategories()
    {
        $query = <<<SQL
            SELECT
                count(commodityID), shop_categories.alias, categories_of_commodities_ID id,
                categories_of_commodities_parrent parent_id,  cat_name
            FROM `shop_commodities-categories`
            INNER JOIN shop_categories
                ON categoryID = categories_of_commodities_ID
            INNER JOIN shop_commodity
                ON commodity_ID = commodityID
            WHERE  categories_of_commodities_parrent IN (264, 209, 212, 213, 261, 211, 266, 267, 210, 268, 10)
            AND commodity_visible = 1
            AND visible = 1
            GROUP BY categoryID
SQL;

        $this->db->set_charset('utf8');
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->categories[] = $row;
        }
        return $this;
    }

    /**
     * @param $id id of category
     * @param $name name of category
     * @param $alias string alias of category
     * @return string html of single category
     */
    public function getSingleCategory($id, $name, $alias = '', $isBrands = false)
    {
        $alias = $alias === '' ? '' : '-' . $alias;
        $url = "/c$id$alias/";
        $classFontSize = $this->getClassFontSize(mb_strlen($name, 'utf-8'));
        $select = $isBrands ? 'категории' : 'бренды';

        return <<<LINE
            <div class="catalog_item{$classFontSize}">
                <div class="catalog_image" id="catalogue{$id}">
                    <a href="{$url}"></a>
                </div>
                <h2>{$name}</h2>
                <a class="link-categoties" data-id="{$id}">Выбрать {$select}</a>
            </div>
LINE;

    }

    private function getClassFontSize($length)
    {
        switch ($length) {
            case 4:
            case 5:
                $size = 'largest';
                break;
            case 6:
                $size = 'large';
                break;
            case 7:
                $size = 'medium';
                break;
            case 8:
                $size = 'normal';
                break;
            case 9:
                $size = 'little';
                break;
            default:
                $size = '';
                break;
        }

        if ($size) {
            $size = ' category-name-font-' . $size;
        } else {
            $size = '';
        }

        return $size;
    }
}
