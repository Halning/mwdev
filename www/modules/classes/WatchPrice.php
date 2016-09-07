<?php
/**
 * Created by PhpStorm.
 * Date: 10/20/15
 * Time: 3:29 PM
 */

namespace Modules;

/**
 * Class WatchPrice.
 * @package Modules
 */
class WatchPrice
{
    public $ajaxResult = false;
    public $alreadyWatched = false;
    public $itemsToCheck;
    public $itemsToCheckFull = array();
    private $db;
    private $email;
    private $itemId;
    private $prices;


    /**
     * Takes email and item_id and checks its on already watched, if not then add to watches.
     * @param $email
     * @param $itemId
     */
    public function __construct($email, $itemId = 0)
    {
        $this->db = MySQLi::getInstance()->getConnect();
        $this->email = trim(strip_tags($email));
        $this->itemId = trim(strip_tags($itemId));
        $this->itemsToCheck = $this->getWatchedItems($this->email);
        $this->prices = $this->getPrices();
    }

    /**
     * Check current com_id on already exists in table shop_watch_prices.
     * @return bool.
     */
    public function checkOnWatch()
    {
        if (count($this->itemsToCheck) > 0 && in_array($this->itemId, $this->itemsToCheck)) {
            $this->alreadyWatched = true;
        }

        return $this;
    }

    /**
     * @param $email
     * @return array of com_id, which belong to email.
     */
    private function getWatchedItems($email)
    {
        $itemsToCheck = array();

        $result = $this->db->query(<<<QUERY1
            SELECT
              com_id, price, price2
            FROM shop_watch_price swp
            INNER JOIN shop_commodity sc
              ON sc.commodity_ID = swp.com_id
            WHERE email = '{$email}'
            AND sc.commodity_visible = 1
QUERY1
        );

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $itemsToCheck[] = $row->com_id;
                $this->itemsToCheckFull[$row->com_id] = array(
                    $row->price,
                    $row->price2
                );
            }
        }

        return $itemsToCheck;
    }

    /***
     * Gets price, price2 from shop commodities.
     * @return array prices.
     */
    private function getPrices()
    {
        $result = $this->db->query(<<<QUERY2
            SELECT commodity_price, commodity_price2
            FROM shop_commodity
            WHERE commodity_ID = {$this->itemId}
QUERY2
        );

        if ($row = $result->fetch_object()) {
            $price = $row->commodity_price;
            $price2 = $row->commodity_price2;
        }

        return array($price, $price2);
    }

    /***
     * inserts into shop_watch_price email, prices, com_id.
     * @return bool.
     */
    public function addToWatches()
    {
        $hash = $this->getHash($this->itemId, $this->email);
        $this->ajaxResult = $result = $this->db->query(<<<QUERY3
            INSERT INTO shop_watch_price (
              com_id,
              price,
              price2,
              email,
              hash
            ) VALUES (
              '{$this->itemId}',
              '{$this->prices[0]}',
              {$this->prices[1]},
              '{$this->email}',
              '$hash'
            )
QUERY3
        );

        return $this;
    }

    /***
     * Generates md5 hash string only digits, basic on timestamp and id.
     * @param $id int from table shop_watch_price.
     * @param $timestamp
     * @return string hash md5.
     */
    private function getHash($id, $timestamp)
    {
        return preg_replace('/[^0-9]/', '', md5($id.$timestamp));
    }

    /**
     * Видаляє продукт з листа наблюдений.
     * @return $this
     */
    public function deleteFromWatched()
    {
        $this->ajaxResult = $this->db->query(<<<QUERY4
             DELETE FROM shop_watch_price
             WHERE com_id = '{$this->itemId}'
             AND email = '{$this->email}'
QUERY4
        );

        return $this;
    }

    /**
     * Displays ajax result in json format.
     */
    public function showResult()
    {
        echo json_encode(array(
            'active' => (int) $this->alreadyWatched,
            'success' => (int) $this->ajaxResult
        ));
    }
}
