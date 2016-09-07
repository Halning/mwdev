<?php
/**
 * Created by PhpStorm.
 * User: webler
 * Date: 06.04.16
 * Time: 11:46
 */

namespace Modules;


class SubscribeUpload
{
    public $arraySubscribersLength;
    private $nameOrder;
    private $phoneOrder;
    private $emailOrder;
    private $amountOrders;
    private $sumOrders;
    private $supplierId;
    private $_db;
    private $subscribeType = 3;

    public function __construct($nameOrder, $phoneOrder, $emailOrder, $amountOrders, $sumOrders)
    {
        $this->_db = MySQLi::getInstance()->getConnect();
        $this->nameOrder = $nameOrder;
        $this->phoneOrder = $phoneOrder;
        $this->emailOrder = $emailOrder;
        $this->amountOrders = $amountOrders;
        $this->sumOrders = $sumOrders;
    }

    public function addSubscribers($subscribers)
    {
        $stmt = $this->_db->prepare("
            INSERT INTO subscribe (
              sub_email, user_name, sub_type, supplier_id, phone, amount_orders, summ_orders, priority
            ) VALUES (?,?, {$this->subscribeType}, 4,?,?,?,?)
        ");

        $nth = 0;
        foreach ($subscribers as $subscriber) {

            $nth++;
            $sumOrders = $this->validateSum($subscriber[$this->sumOrders]);
            $priority = $this->getPriority($nth);
            $name = $subscriber[$this->nameOrder] ? $subscriber[$this->nameOrder] : '';
            $phone = $subscriber[$this->phoneOrder] ? $subscriber[$this->phoneOrder] : '';
            $amountOrders = $subscriber[$this->amountOrders] ? $subscriber[$this->amountOrders] : 0;
            $stmt->bind_param(
                'sssiii',
                $subscriber[$this->emailOrder],
                $name,
                $phone,
                $amountOrders,
                $sumOrders,
                $priority
            );

            $result = $stmt->execute();
            echo $result.' - '.$nth.' - '.$subscriber[$this->emailOrder].' - '.$subscriber[$this->nameOrder].' - ';
            echo $subscriber[$this->amountOrders].' - '.$sumOrders.' - '.$priority.' - '.$phone.'<br>';
            flush();
            ob_flush();
        }

        return $this;
    }

    public function getSupplierId($supplierName)
    {
        $result = $this->_db->query(<<<QUERY2
            SELECT id
            FROM subscribe_suppliers
            WHERE name LIKE '%$supplierName%'
            LIMIT 1
QUERY2
        );

        $this->supplierId = $result->fetch_row()[0];
    }

    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function validateSum($sum)
    {
        $sum = strpos($sum, '.') ? strstr($sum, '.', true) : $sum;
        return preg_replace('/[^0-9]/', '', $sum);
    }

    private function getPriority($nth)
    {
        $thirdPart = floor($this->arraySubscribersLength / 3);

        if ($nth < $thirdPart) {
            return 1;
        } elseif ($nth < 2 * $thirdPart) {
            return 2;
        }

        return 3;
    }
}
