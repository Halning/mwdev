<?php
/**
 * Created by PhpStorm.
 * Date: 10.02.16
 * Time: 13:32
 */

namespace CMS;

use Modules\MySQLi;

/**
 * Class Sizes Переносить розміра з таблиці shop_commodity в shop_filter_list.
 * @package CMS
 */
class Sizes
{
    public $sizesFromShopCommodity = array();

    /**
     * Список розмірів, які витягнулись з товарів, але відсутні в таблиці filter_list.
     * Це або фейлові розміра і їх потрібно видалити з таблиці товарів, або нові, їх треба добавити в фільтр.
     * @var array
     */
    public $notEstablishedSizes = array();

    public $sizesFromFilterList = array();

    public $sizesFromFilterValues = array();

    public $sizesToAdd = array();

    public $sizesToDelete = array();

    protected $db;

    /**
     * Sizes constructor. Init db.
     */
    public function __construct()
    {
        $this->db = MySQLi::getInstance()->getConnect();
    }

    /**
     * Витягує всі розміра з товарів.
     * @param bool|int $id brand id
     * @return $this
     */
    public function getSizesFromShopCommodity($id = false)
    {
        $andBrandId = $id ? "AND brand_id = $id" : '';
        $result = $this->db->query(<<<QUERY1
        SELECT
          commodity_ID id, commodity_select colorSizes, com_sizes sizes
        FROM shop_commodity
        WHERE commodity_visible = 1
        {$andBrandId}
QUERY1
        );

        while ($row = $result->fetch_object()) {
            if ($row->sizes) {

                $sizes = explode(';', $row->sizes);
                $this->transformSizes($row->id, $sizes, $this->notEstablishedSizes);

            } elseif ($row->colorSizes) {

                $sizes = explode(';', $row->colorSizes);

                if (count($sizes) > 0) {
                    foreach ($sizes as $stringColorSizes) {
                        if (strstr($stringColorSizes, '=', true)) {
                            $stringSizes = substr(strstr($stringColorSizes, '='), 1);
                            $stringSizes = explode(',', $stringSizes);
                            $this->transformSizes($row->id, $stringSizes, $this->notEstablishedSizes);
                        }
                    }

                    if ($this->sizesFromShopCommodity[$row->id]) {
                        $this->sizesFromShopCommodity[$row->id] = array_unique($this->sizesFromShopCommodity[$row->id]);
                    } else {
//                        echo $row->id . '<br>';
                    }
                }
            } else {
//                echo $row->id . '<br>';
            }
        };

        return $this;
    }

    /**
     * Витягує всі значеня фільтрів розмірів з таблиці filters_list.
     * @return object $this
     */
    public function getSizesFromFilterList()
    {
        $result = $this->db->query(<<<QUERY3
        SELECT
          id, list_name size
         FROM `shop_filters-lists`
         WHERE list_filterid = 5
QUERY3
        );

        while ($row = $result->fetch_object()) {
            $this->sizesFromFilterList[$row->id] = $row->size;
        };

        return $this;
    }

    /**
     * Витягує всі фільтри з табл. filters_values і
     * записує в масив sizesFromFilterList.
     * @return object $this
     */
    public function getSizesFromFilterValues()
    {
        $result = $this->db->query(<<<QUERY4
        SELECT
          ticket_id id, ticket_value value, list_name name
        FROM `shop_filters-values` sfv
        INNER JOIN `shop_filters-lists` sfl
          ON sfv.ticket_value = sfl.id
        WHERE ticket_filterid = 5
QUERY4
        );

        while ($row = $result->fetch_object()) {
            $this->sizesFromFilterValues[$row->id][$row->value] = $row->name;
        };

        return $this;
    }

    /**
     * Видаляє фільтри по ід продукта.
     * @param int $ids product id
     * @return object $this
     */
    public function deleteSizesByCommodityId($ids)
    {
        echo '<div style="background-color:#FF7B4C"><hr><h2 align="center">УДАЛЕНИЕ ФИЛЬТРОВ ПО ticket_id</h2>';

        $stmt = $this->db->prepare(<<<QUERY3
            DELETE from `shop_filters-values`
            WHERE ticket_id = ?
QUERY3
        );
        foreach ($ids as $id) {
            $stmt->bind_param('i', $id);
            echo $id.' - '.$stmt->execute().'<br>';
            flush();
            ob_flush();
        }
        $stmt->close();
        echo '<hr></div>';

        return $this;
    }

    /**
     * Видаляє фільтри по ід фільтра, використовується масив sizesToDelete.
     * @return object $this
     */
    public function deleteSizesByFilterListId()
    {
        echo '<div style="background-color:#ff8e79"><hr><h2 align="center">УДАЛЕНИЕ ФИЛЬТРОВ (sizes)</h2>';

        $stmt = $this->db->prepare(<<<QUERY3
            DELETE from `shop_filters-values`
            WHERE ticket_id = ?
            AND ticket_value = ?
QUERY3
        );

        $counter = 1;
        foreach ($this->sizesToDelete as $ticketId => $sizes) {
            foreach ($sizes as $valueId => $size) {
                $stmt->bind_param('ii', $ticketId, $valueId);
                echo '№'.$counter.'. '.$ticketId.'*'.$valueId.'*'.$size.' - '.$stmt->execute().'<br>';
                $counter++;
                flush();
                ob_flush();
            }
        }
        $stmt->close();
        echo '<hr></div>';

        return $this;
    }

    /**
     * Порівнює два масива, розміри з витягнуті з товарів і витягнуті з фільтрів.
     * В результаті отримуєм два масива з новими розмірами та вже неактуальними.
     * @return object $this
     */
    public function compareSizes()
    {
        foreach ($this->sizesFromShopCommodity as $commodityId => $fromCommodities) {

            if (array_key_exists($commodityId, $this->sizesFromFilterValues)) {
                $sizeToDelete = array_diff(
                    $this->sizesFromFilterValues[$commodityId],
                    $fromCommodities
                );

                if (count($sizeToDelete) > 0) {
                    $this->sizesToDelete[$commodityId] = $sizeToDelete;
                }

                $sizeToAdd = array_diff(
                    $fromCommodities,
                    $this->sizesFromFilterValues[$commodityId]
                );

                if (count($sizeToAdd) > 0) {
                    $this->sizesToAdd[$commodityId] = $sizeToAdd;
                }
            } else {
                $this->sizesToAdd[$commodityId] = $fromCommodities;
            }
        }

        return $this;
    }

    /**
     * Добавляє нові фільтри в таблицю filters_values.
     * Дані беруться з свойства sizesToAdd.
     * @return object $this
     */
    public function addSizeToFilterValues()
    {
        echo '<div style="background-color:#beff98"><hr><h2 align="center">ДОБАВЛЕНИЕ ФИЛЬТРОВ (sizes)</h2>';

        $stmt = $this->db->prepare(<<<QUERY5
            INSERT INTO `shop_filters-values`(
              ticket_id,
              ticket_filterid,
              ticket_value
            ) VALUES (?, 5, ?)
QUERY5
        );

        $counter = 1;
        foreach ($this->sizesToAdd as $id => $sizes) {

            foreach ($sizes as $size) {

                $valueId = array_search($size, $this->sizesFromFilterList);

                if ($valueId) {
                    $stmt->bind_param('is', $id, $valueId);
                    echo '№'.$counter.'. '.$id.'*'.$valueId.'*'.$size.' - '.$stmt->execute().'<br>';
                    $counter++;
                } else {
                    echo $id.'*'.$size.' - Не найдено в листе фильтров<br>';
                }

                flush();
                ob_flush();
            }
        }
        $stmt->close();
        echo '<hr></div>';

        return $this;
    }

    /**
     * Добавляє новий розмір в таблицю filter_list
     * @param string $size
     * @return object $this
     */
    public function addSizeToFilterList($size)
    {
        $stmt = $this->db->prepare(<<<QUERY2
        INSERT INTO `shop_filters-lists`(
          list_name,
          list_filterid,
          list_filter_name
        ) VALUES (?, 5, 'Размер')
QUERY2
        );

        $stmt->bind_param('s', trim($size));
        $stmt->execute();
        $stmt->close();

        return $this;
    }

    /**
     * @param int $id product id
     * @param array $sizes
     * @param array $notEstablishedSizes
     */
    private function transformSizes($id, $sizes, &$notEstablishedSizes)
    {
        foreach ($sizes as $size) {

            //проблені розміри
            if ($size === '098') {
                $size = str_replace('098', '98', $size);
            }
            if ($size === 'XХL') {
                $size = str_replace('XХL', 'XXL', $size);
            }

            $size = trim($size);
            $size = preg_replace('/см/', '', $size);

            if (strstr($size, 'нивер') || strstr($size, 'ЕДИН')) {
                $size = 'F';
            }

            preg_match('/(.*)\(/', $size, $match);
            if ($match[1]) {
                $size = $match[1];
            }

            if (strpos($size, '-')) {
                $this->transformSizes($id, explode('-', $size), $notEstablishedSizes);
                continue;
            }

            if (strpos($size, '/') && (
                    strpos($size, 'L') ||
                    strpos($size, 'X') ||
                    strpos($size, 'S') ||
                    strpos($size, 'M')
                )
            ) {
                $sizesDouble = explode('/', $size);
                $this->transformSizes($id, $sizesDouble, $notEstablishedSizes);
                continue;
            }

            if (!in_array($size, $this->sizesFromFilterList) &&
                !in_array($size, $notEstablishedSizes) &&
                $size
            ) {
                $notEstablishedSizes[] = $size;
            } elseif ($size) {
                $this->sizesFromShopCommodity[$id][] = $size;
            }
        }
    }

    public function showNotEstablishedItems()
    {
        echo '<div style="background-color:#FFE1A3"><hr><h2 align="center" >ПОЛЯ ФІЛЬТРІВ ЯКІ НЕ ЗНАЙДЕНО В shop_filter_list</h2>';

        $counter = 1;
        foreach ($this->notEstablishedSizes as $brandName => $commodityId) {
            echo '№'.$counter.'. '.$brandName.' - '.$commodityId.'<br>';
            $counter++;
        }
        echo '<hr></div>';

        return $this;
    }
}
