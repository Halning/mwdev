<?php

namespace Modules;

class FastOrder
{
    /**
     * @var string fast order cod generete by getCodFastOrder()
     */
    private $orderCod;

    /**
     * @var string date of order 
     */
    private $orderDate;

    /**
     * @var int country user from form
     */
    public $userCountry;

    /**
     * @var string email user from form
     */
    public $userEmail;

    /**
     * @var string user name and lastname from form
     */
    public $userName;

    /**
     * @var string user city from form
     */
    public $userCity;

    /**
     * @var string user phone from form
     */
    public $userPhone;

    /**
     * @var string user address from form
     */
    public $userAddress;

    /**
     * @var string user comment from form
     */
    public $userComment;

    /**
     * @var string user id from db get by getUserPropByEmail
     */
    private $userId;

    /**
     * @var string user discount from db get by getUserPropByEmail
     */
    private $userDiscount;

    /**
     * @var int commond price from form(fast-order) and after from db
     */
    private $comPrice;

    /**
     * @var int price2 from db
     */
    private $comPrice2;

    /**
     * @var int price or price2 from email and finalFAstOrder 
     */
    private $comPriceFinal;

    /**
     * @var int  commond price * count commodity
     */
    private $comSumPrice;

    /**
     * @var string  from form(fast-order)
     */
    public $comId;

    /**
     * @var int count of commodity
     */
    public $comCount;

    /**
     * @var string commond color from form(fast-order)
     */
    public $comColor;

    /**
     * @var string commond size from form(fast-order)
     */
    public $comSize;

    /**
     * @var string com name from db
     */
    private $comName;

    /**
     * @var string com cod from db
     */
    private $comCod;

    /**
     * @var string comSizeCount from db
     */
    private $comSizeCount;

    /**
     * @var string alias for src from db
     */
    private $comAlias;

    /**
     * @var string get from $glb["cur_id"]
     */
    private $currencyId;

    /**
     * @var string get from $glb["cur_val"]
     */
    private $currencyVal;

    /**
     * @var string get from $glb["cur_val_bax"]
     */
    private $currencyValBax;

    /**
     * @var float Percent of commition from all price
     */
    private $commisionPercent = 0;

    /**
     * @var int  ceil($this->comSumPrice * $this->commisionPercent)
     */
    private $commisionPrice;

    /**
     * @var int deliveryMethodId from form(fast-order)
     */
    public $deliveryMethodId;

    /**
     * @var int from getDeliveryPrice()
     */
    private $deliveryPrice;

    /**
     * @var string deliveryMethodName from form(fast-order)
     */
    public $deliveryMethodName;

    /**
     * @var string brend name from db
     */
    private $catName;

    /**
     * @var екземпляр класу шаблона.
     */
    private $templates;

    /**
     * @var html(tpl) final page insert in popup 
     */
    public $finalPageFastOrder;

    /**
     * @var string from $glb["dom_mail"] 
     */
    private $domEmail;

    /**
     * @var int $this->comSumPrice + $this->deliveryPrice + $this->commisionPrice
     */
    private $totalSum;

    /**
     * @var object connect to db
     */
    private $db;

    /**
     * @var int from errors
     */
    public $success = 1;

    /**
     * @var string email to send Fast order
     */
    private $adminEmail = 'sales@makewear.com.ua'; //'halning@inbox.ru';

    /**
     * @param glb all
     */

    public function __construct($currencyId, $currencyVal, $currencyValBax,
                                $domEmail, $templates)
    {
        $this->db             = MySQLi::getInstance()->getConnect();
        $this->orderDate      = date("Y-m-d H:i:s");
        $this->currencyId     = $currencyId;
        $this->currencyVal    = $currencyVal;
        $this->currencyValBax = $currencyValBax;
        $this->domEmail       = $domEmail;
        $this->templates      = $templates;
    }

    /**
     * main function call all functions
     *
     * @return comSumPrice, commisionPrice, totalSum
     */
    public function mainFastOrderAction()
    {
        //secondary functions
        $this->getComPtopertiesFromDb();
        $this->getDeliveryPrice();
        $this->parseUserPhone();
        $this->getCodFastOrder();
        $this->getUserPropByEmail();

        //final price var
        $this->comSumPrice    = ceil($this->comCount * $this->comPriceFinal * $this->currencyVal);
        $this->commisionPrice = ceil($this->comSumPrice * $this->commisionPercent);
        $this->totalSum       = $this->comSumPrice + $this->deliveryPrice + $this->commisionPrice;

        //call function insertFastOrderIntoDb and insertComPropIntoDb
        $this->insertFastOrderIntoDb();

        //select comColor getColorOfCom
        if ($this->comColor == "") {
            $this->getColorOfCom();
        }

        //подготовка шаблона товаров для email
        $this->templates->set_tpl('{$orderCode}', $this->orderCod);
        $this->templates->set_tpl('{$userName}', $this->userName);
        $this->templates->set_tpl('{$userEmail}', $this->userEmail);
        $this->templates->set_tpl('{$userTel}', $this->userPhone);
        $this->templates->set_tpl('{$userCity}', $this->userCity);
        $this->templates->set_tpl('{$userAddress}', $this->userAddress);
        $this->templates->set_tpl('{$deliveryMethodName}',
            $this->deliveryMethodName);
        $this->templates->set_tpl('{$userComments}', $this->userComment);
        $this->templates->set_tpl('{$orderDate}', $this->orderDate);
        $this->templates->set_tpl('{$comCount}', $this->comCount);
        $this->templates->set_tpl('{$comTotalCount}', $this->comCount);
        $this->templates->set_tpl('{$comPrice}', $this->comPriceFinal);
        $this->templates->set_tpl('{$comSumPrice}', $this->comSumPrice);
        $this->templates->set_tpl('{$deliveryPrice}', $this->deliveryPrice);
        $this->templates->set_tpl('{$commisionPrice}', $this->commisionPrice);
        $this->templates->set_tpl('{$totalSumm}', $this->totalSum);
        $this->templates->set_tpl('{$catName}', $this->catName);
        $this->templates->set_tpl('{$comCod}', $this->comCod);
        $this->templates->set_tpl('{$comColor}', $this->comColor);
        $this->templates->set_tpl('{$comSize}', $this->comSize);
        $this->templates->set_tpl('{$comName}', $this->comName);
        $this->templates->set_tpl('{$comUrl}',
            "/product/{$this->comId}/{$this->comAlias}.html");
        $this->templates->set_tpl('{$userIp}',
            filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING));

        //show fastOrder.finalPage in fast-order popup
        $this->finalPageFastOrder = $this->templates->get_tpl('fastOrder.finalPage',
            "../../../");

        //send mails to user and manager
        $this->sendMailsOrderFast();
    }

    /**
     * select propierties of comId from shop_commodity and shop_categories
     *
     * @return comId, comName, catName, comPrice, comPrice2, comCod ,comSizeCount,
     * comAlias, generate comPriceFinal
     */
    private function getComPtopertiesFromDb()
    {
        $sql  = "
      SELECT commodity_ID, com_name, cat_name, commodity_price, commodity_price2, cod, size_count, sc.alias
      FROM shop_commodity sc
      INNER JOIN shop_categories cat
      ON cat.categories_of_commodities_ID = sc.brand_id
      WHERE commodity_ID=?";
        if (!($stmt = $this->db->prepare($sql))) {
            echo('Error выборки getCommoditiesPtopertiesFromDb('.$this->db->errno.') '.$this->db->error);
            $this->success = 0;
        } else {
            $stmt->bind_param("s", $this->comId);
            $stmt->execute();
            $stmt->bind_result($this->comId, $this->comName, $this->catName,
                $this->comPrice, $this->comPrice2, $this->comCod,
                $this->comSizeCount, $this->comAlias);
            $stmt->fetch();
            $this->comPriceFinal = $this->comPrice;
            if ($this->comCount > 9 && $this->comPrice != $this->comPrice2 && $this->comPrice2
                != 0) {
                $this->comPriceFinal = $this->comPrice2;
            }
        }
        $stmt->close();
    }

    /**
     * generate DeliveryPrice
     *
     * @return deliveryPrice
     */
    private function getDeliveryPrice()
    {
        //Формирования цены доставки
        if ($this->deliveryMethodId == 3 || $this->deliveryMethodId == 4 || $this->deliveryMethodId
            == 5) {
            if ($this->comCount <= 2) {
                $this->deliveryPrice = 25 * $this->currencyVal;
            } else if ($this->comCount <= 4) {
                $this->deliveryPrice = 30 * $this->currencyVal;
            } else if ($this->comCount <= 10) {
                $this->deliveryPrice = 40 * $this->currencyVal;
            } else if ($this->comCount <= 20) {
                $this->deliveryPrice = 50 * $this->currencyVal;
            } else if ($this->comCount <= 30) {
                $this->deliveryPrice = 65 * $this->currencyVal;
            } else if ($this->comCount <= 60) {
                $this->deliveryPrice = 85 * $this->currencyVal;
            }
        } else if ($this->deliveryMethodId == 6 || $this->deliveryMethodId == 7) {
            $curGrnforDol        = 100 / ($this->currencyValBax * 100);
            $this->deliveryPrice = ($curGrnforDol * $this->currencyVal) * $this->comCount;
        } else if ($this->deliveryMethodId == 2/* && $this->comSumPrice < $freePrice */) {
            $this->deliveryPrice = 50 * $this->currencyVal;
        }
        $this->deliveryPrice = ceil($this->deliveryPrice);
    }

    /**
     * parse user phone from form
     *
     * @return userPhone
     */
    private function parseUserPhone()
    {
        if ($this->userCountry == 1) {
            $this->userPhone = "+38 (".substr($this->userPhone, 0, -7)
                .") ".substr($this->userPhone, 3, -4)
                ."-".substr($this->userPhone, 6, -2)
                ."-".substr($this->userPhone, -2);
        } else {
            $this->userPhone = "+7 ("
                .substr($this->userPhone, 0, -7)
                .") ".substr($this->userPhone, 3, -4)
                ."-".substr($this->userPhone, 6, -2)
                ."-".substr($this->userPhone, -2);
        }
    }

    /**
     * generate orderCod
     *
     * @return orderCod
     */
    private function getCodFastOrder()
    {
        $date     = date("Y-m-d");
        $response = $this->db->query(
            <<<QUERY1
                SELECT COUNT(*) AS `c` FROM `shop_orders`
                WHERE `date`>'{$date} 00:00:00' AND `date`<'{$date} 23:59:59'
                LIMIT 1
QUERY1
        );
        if ($response) {
            $row            = $response->fetch_assoc();
            $c              = $row["c"] + 1;
            $this->orderCod = date("md")."/{$c}";
            $response->free();
        } else {
            $this->success = 0;
        }
    }

    /**
     * select userId and userDiscount from user table
     *
     * @return userId, userDiscount
     */
    private function getUserPropByEmail()
    {
        $query    = "SELECT * FROM `users`
	WHERE `user_email`='{$this->userEmail}'";
        $response = $this->db->query($query);
        if ($response) {
            $row = $response->fetch_assoc();
            if ($row) {
                $this->userId       = $row["user_id"];
                $this->userDiscount = $row["user_discount"];
            } else {
                $this->userId       = 1;
                $this->userDiscount = 0;
            }
            $response->free();
        } else {
            $this->success = 0;
        }
    }

    /**
     * insert order in table shop_orders
     *
     * @return orderId
     */
    private function insertFastOrderIntoDb()
    {
        $stmt = $this->db->stmt_init();
        if (!($stmt = $this->db->prepare("
          INSERT INTO `shop_orders`
          SET
          `cod`=?,
          `date`=?,
          `user_id`=?,
          `name`=?,
          `email`=?,
          `tel`=?,
          `city`=?,
          `address`=?,
          `user_comments`=?,
          `delivery`=?,
          `delivery_price`=?,
          `commission`=?,
          `cur_id`=?"))) {
            echo('Insert shop_orders Error ('.$this->db->errno.') '.$this->db->error);
            $this->success = 0;
        }
        if (!$stmt->bind_param("ssissssssiiii", $this->orderCod,
                $this->orderDate, $this->userId, $this->userName,
                $this->userEmail, $this->userPhone, $this->userCity,
                $this->userAddress, $this->userComment, $this->deliveryMethodId,
                $this->deliveryPrice, $this->commisionPrice, $this->currencyId)) {
            echo('Insert shop_orders Error ('.$stmt->errno.') '.$stmt->error);
            $this->success = 0;
        }
        if (!$stmt->execute() || !$stmt->close()) {
            echo('Insert shop_orders Error ('.$stmt->errno.') '.$stmt->error);
            $this->success = 0;
        }
        $orderId = $this->db->insert_id;
        $this->insertComPropIntoDb($orderId);
    }

    /**
     * insert com into shop_orders_coms table
     *
     * @param type $orderId
     */
    private function insertComPropIntoDb($orderId)
    {
        if (!($stmt = $this->db->prepare("
          INSERT INTO `shop_orders_coms`
          SET
          `offer_id`=?,
          `com_id`=?,
          `cur_id`=?,
          `count`=?,
          `com`=?,
          `price`=?,
          `com_color`=?
          "))) {
            echo('Insert shop_orders_coms Error ('.$this->db->errno.') '.$this->db->error);
            $this->success = 0;
        }
        if (!$stmt->bind_param("iiiisis", $orderId, $this->comId,
                $this->currencyId, $this->comCount, $this->comSize,
                $this->comPrice, $this->comColor)) {
            echo('Insert shop_orders_coms Error ('.$stmt->errno.') '.$stmt->error);
            $this->success = 0;
        }
        if (!$stmt->execute() || !$stmt->close()) {
            echo('Insert shop_orders_coms Error ('.$stmt->errno.') '.$stmt->error);
            $this->success = 0;
        }
    }

    /**
     * select comColor from shop_commodity(com_fulldesc) or shop_filters-values(list_realname)
     * 
     * @return comColor
     */
    private function getColorOfCom()
    {
        $sql1     = "SELECT `com_fulldesc` FROM `shop_commodity` WHERE `commodity_ID` = {$this->comId}";
        $response = $this->db->query($sql1);
        if ($response) {
            $row           = $response->fetch_assoc();
            $comDescString = $row["com_fulldesc"];
        } else {
            $this->comColor = "";
            return;
        }
        $needle    = "Цвет:";
        $findColor = strstr($comDescString, $needle);
        if ($findColor !== false) {
            $this->comColor = str_replace("Цвет:</span>", "",
                strstr($findColor, "</p>", true));
        } else {
            $sql2     = "SELECT `list_realname` FROM `shop_filters-values` INNER JOIN
                    `shop_filters-lists`
                    ON `ticket_value`=`id`
                    WHERE `ticket_id`={$this->comId} AND `ticket_filterid`=9 ";
            $response = $this->db->query($sql2);
            if ($response) {
                $row            = $response->fetch_assoc();
                $this->comColor = $row["list_realname"];
            } else {
                $this->comColor = "";
            }
        }
    }

    /**
     * send mails to user and manager
     */
    private function sendMailsOrderFast()
    {
        $this->templates->set_tpl('{$hostName}',
            filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_STRING));
        $this->templates->set_tpl('{$basketFastOrderMailLines}',
            $this->templates->get_tpl('mail.basketFastOrder.line', "../../../"));

        //send email user
        $this->templates->set_tpl('{$mailContent}',
            $this->templates->get_tpl('mail.basketFastOrder.userMail',
                "../../../"));
        $mailToUser    = $this->templates->get_tpl('mail.main', "../../../");
        $sendUser      = Mail::send($this->userEmail,
                "Ваш заказ на сайте {$this->domEmail}", $mailToUser);
        $this->success = ($sendUser) ? 1 : 0;

        //send email manager
        $this->templates->set_tpl('{$mailContent}',
            $this->templates->get_tpl('mail.basketFastOrder.adminMail',
                "../../../"));
        $mailToAdmin   = $this->templates->get_tpl('mail.main', "../../../");
        $sendManager   = Mail::send($this->adminEmail, "Новый заказ",
                $mailToAdmin);
        $this->success = ($sendManager) ? 1 : 0;
    }
}