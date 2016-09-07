<?php

namespace Modules\Basket;

use Modules\MySQLi;

/**
 * Class BasketDb.
 * @package Modules\Basket
 */
class BasketDb
{
    /**
     *  db connect
     * @var object
     */
    private $db;

    /**
     * session_id real
     * @var string
     */
    private $ssesionId;

    /**
     * data included basket items
     * @var string
     */
    private $basketData;

    /**
     * data basket in array for basket modul
     * @var array
     */
    private $basketItems;

    /**
     *
     * @param  $sessionId
     */
    public function __construct($sessionId)
    {
        $this->db        = MySQLi::getInstance()->getConnect();
        $this->ssesionId = $sessionId;
    }

    /**
     * Return Basket data in type of array for basket modul
     *
     * @return array
     */
    public function getBasketData()
    {
        $this->selectData();
        $this->basketItems = unserialize($this->basketData);

        return $this->basketItems;
    }

    /**
     * Insert or update data when user add item to basket
     *
     * @param array $basketItems
     * @param boolean $first
     * @return type
     */
    public function insertDataOrUpdate($basketItems, $first)
    {
        $this->basketData = serialize($basketItems);
        if ($first == TRUE) {
            $this->InsertData();

            return;
        }
        $this->updateData($this->basketData);
    }

    /**
     * Select data from Db
     */
    private function selectData()
    {
        $sql      = "SELECT `data_basket` FROM `session_basket` WHERE `session_id` = '{$this->ssesionId}'";
        $response = $this->db->query($sql);
        if ($response) {
            $row              = $response->fetch_assoc();
            $this->basketData = $row["data_basket"];
        }
    }

    /**
     * update data serialaze string
     *
     * @param  string $basketItems
     */
    public function updateData($basketItems)
    {
        if (!($stmt = $this->db->prepare("UPDATE `session_basket` SET `data_basket`=?
                                                    WHERE `session_id`=?"))) {
            die('Update session_basket Error ('.$this->db->errno.') '.$this->db->error);
        }
        $stmt->bind_param("ss", $basketItems, $this->ssesionId);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Insert new session data when user add firts item
     */
    private function InsertData()
    {
        $this->deleteOLdRows();
        //$basketDate = date("Y-m-d H:i:s");
        $stmt = $this->db->stmt_init();
        if (!($stmt = $this->db->prepare("
                INSERT INTO `session_basket`
                SET `session_id`=?, `data_basket`=?"))) {
            echo('Insert session_basket Error ('.$this->db->errno.') '.$this->db->error);
        }
        if (!$stmt->bind_param("ss", $this->ssesionId, $this->basketData
            )) {
            echo('Insert session_basket Error ('.$stmt->errno.') '.$stmt->error);
        }
        if (!$stmt->execute() || !$stmt->close()) {
            echo('Insert session_basket Error ('.$stmt->errno.') '.$stmt->error);
        }
    }

    /**
     * Delete row from Db
     */
    public function deleteData()
    {
        $this->db->query("DELETE FROM `session_basket` WHERE `session_id`='{$this->ssesionId}'");
        if ($this->db->errno) {
            die('DELETE session_basket Error ('.$this->db->errno.') '.$this->db->error);
        }
    }

    /**
     * Delete rows who life more then 3 minuts
     */
    private function deleteOLdRows()
    {
        $this->db->query("DELETE FROM `session_basket` WHERE `add_date` < NOW() - INTERVAL 72 HOUR");
        if ($this->db->errno) {
            die('DELETE session_basket-oldrow Error ('.$this->db->errno.') '.$this->db->error);
        }
    }
}