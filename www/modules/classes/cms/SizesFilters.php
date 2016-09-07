<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.16
 * Time: 15:37
 */

namespace CMS;

/**
 * Class SizesFilters
 * @package Modules\
 */
class SizesFilters extends Sizes
{
    public $sizesFromFilterListById = array();

    public $colorsFromFilterList = array();

    /**
     * @param int $id
     * @return $this
     */
    public function getSizesFromFilterListByFilterId($id)
    {
        $result = $this->db->query(<<<QUERY3
        SELECT
          id, list_name size
         FROM `shop_filters-lists`
         WHERE list_filterid = $id
QUERY3
        );

        while ($row = $result->fetch_object()) {
            $this->sizesFromFilterListById[$row->id] = $row->size;
        };

        return $this;
    }

    /**
     * @param $listName
     * @return bool
     */
    public function isFilterValueExists($listName)
    {
        return in_array($listName, $this->colorsFromFilterList);
    }

    /**
     * @param $oldFilterId
     * @param $oldTicketValue
     * @param $newTicketValue
     * @return $this
     */
    public function updateFilterListValues($oldFilterId, $oldTicketValue, $newTicketValue)
    {
        $stmt = $this->db->prepare(<<<QUERY2
        UPDATE `shop_filters-values`
        SET
          `ticket_filterid`= 9,
          `ticket_value`= ?
        WHERE `ticket_filterid`= ?
        AND `ticket_value`= ?
QUERY2
        );

        $stmt->bind_param('iii', $newTicketValue, $oldFilterId, $oldTicketValue);

        echo 'Update filter_lists, oldFilterId '.$oldFilterId.', set new ticket value from '.
            $oldTicketValue.' to '.$newTicketValue.'. Result = '.$stmt->execute().'<br>';

        flush();
        ob_flush();
        $stmt->close();

        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function deleteFilterId($id)
    {
        $stmt = $this->db->prepare(<<<QUERY3
            DELETE from `shop_filters-lists`
            WHERE id = ?
QUERY3
        );

        $stmt->bind_param('i', $id);
        echo ' - Delete form filter_lists where id = '.$id.'. Result = '.$stmt->execute().'<br>';

        flush();
        ob_flush();

        $stmt->close();

        return $this;
    }

    /**
     * @param string $size
     * @return int
     */
    public function addColorToFilterList($colorIndex, $colorRealName)
    {
        $stmt = $this->db->prepare(<<<QUERY8
        INSERT INTO `shop_filters-lists`(
          list_name,
          list_filterid,
          list_realname,
          list_filter_name
        ) VALUES (?, 9, ?, 'Цвет')
QUERY8
        );

        $stmt->bind_param('ss', trim($colorIndex), trim($colorRealName));
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();

        return $id;
    }

    public function getColorsFromFilterList()
    {
        $result = $this->db->query(<<<QUERY3
        SELECT
          id, list_name color
         FROM `shop_filters-lists`
         WHERE list_filterid = 9
QUERY3
        );

        while ($row = $result->fetch_object()) {
            $this->colorsFromFilterList[$row->id] = $row->color;
        };

        return $this;
    }
}
