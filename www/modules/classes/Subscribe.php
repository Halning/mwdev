<?php
/**
 * Created by PhpStorm.
 * Date: 25.03.16
 * Time: 15:26
 */

namespace Modules;


class Subscribe
{
    const SUBSCRIBE_LANDING = 1;

    public $result = 0;
    public $subscribers = array();
    public $subscribersPerPage = 100;
    public $amountPages;
    public $currentPage = 1;
    public $filterGroup = 'all';
    public $amountSubscribersByType = array();
    private $db;
    private $subscribeType;
    private $perPagesVariants = array(50, 100, 200, 500);
    public static $filterGroupList = array(
        'all' => ' --Все-- ',
        1 => 'Подписчики',
        2 => 'Зарегистрированы',
        3 => 'Prom.ua 1',
        4 => 'Prom.ua 2',
        5 => 'Prom.ua 3.1',
        6 => 'Prom.ua 3.2',
        7 => 'Сделавшие заказ',
    );

    public function __construct($type = 0)
    {
        $this->db = MySQLi::getInstance()->getConnect();
        $this->subscribeType = $type;
        $this->getAmountSubscribersByType();
    }

    public function subscribe($email)
    {
        $stmt = $this->db->prepare("
            INSERT INTO subscribe (
              sub_email, sub_type
            ) VALUES (?,?)
        ");

        $stmt->bind_param('si', $email, $this->subscribeType);
        $this->result = (int)$stmt->execute();

        return $this;
    }

    public function getSubscriber($id)
    {
        $stmt = $this->db->prepare("
            SELECT
              sub_email, user_name, sub_type
            FROM subscribe
            WHERE sub_id = ?
            LIMIT 1
        ");

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($email, $name, $group);
        $stmt->fetch();

        return array(
            'email' => $email,
            'name' => $name,
            'group' => $group,
            'store' => 'store',
        );
    }

    public function getSubscribers($pager = true)
    {
        $startPage = 1 == $this->currentPage ? 0 : $this->subscribersPerPage * ($this->currentPage - 1);
        $filter = 'all' === $this->filterGroup ? '' : "WHERE sub_type={$this->filterGroup}";
        $limit = $pager ? "LIMIT {$startPage}, {$this->subscribersPerPage}" : '';

        $filterRegistered = 'all' === $this->filterGroup || 2 == $this->filterGroup ?
            'UNION SELECT
              user_id as sub_id, user_email as sub_emil, user_name, @sub_type:=2,
              user_tel phone, user_city city
            FROM users
            WHERE notify = 1' : '';

        $filterMakesOrders = 'all' === $this->filterGroup || 7 == $this->filterGroup ?
            'UNION SELECT
              id as sub_id, email as sub_emil, name, @sub_type:=7, tel phone, city
            FROM shop_orders' : '';

        $result = $this->db->query("
            SELECT
              SQL_CALC_FOUND_ROWS DISTINCT sub_id, sub_email, user_name, sub_type,
              phone, @city city
            FROM subscribe
            {$filter}
            {$filterRegistered}
            {$filterMakesOrders}
            {$limit}
        ");

        $totalAmount = $this->db
            ->query("SELECT FOUND_ROWS() as rows")
            ->fetch_object()
            ->rows;
        $this->amountPages = ceil($totalAmount/$this->subscribersPerPage);
        $this->amountSubscribersByType['all'] = $totalAmount;

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $row->type = self::$filterGroupList[$row->sub_type];
                $this->subscribers[] = $row;
            }
        }

        return $this;
    }

    private function getAmountSubscribersByType()
    {
        $result = $this->db->query(<<<QUERYCOUNT
            SELECT count(sub_id) amount, sub_type type
            FROM subscribe
            GROUP BY sub_type
QUERYCOUNT
        );

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $this->amountSubscribersByType[$row->type] = $row->amount;
            }
        }

        $result = $this->db->query(<<<QUERYCOUNT2
            SELECT count(user_id) amount
            FROM users
            WHERE notify = 1
QUERYCOUNT2
        );

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $this->amountSubscribersByType[2] = $row->amount;
            }
        }

        $result = $this->db->query(<<<QUERYCOUNT3
            SELECT count(id) amount
            FROM shop_orders
QUERYCOUNT3
        );

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $this->amountSubscribersByType[7] = $row->amount;
            }
        }

        return $this;
    }

    public function deleteSubscribers($ids)
    {
        $stmt = $this->db->prepare(
            <<<QUERYD
                DELETE FROM subscribe
                WHERE sub_id IN (?)
QUERYD
        );

        foreach ($ids as $id) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
        }

        $stmt->close();

        return $this;
    }

    public function unSubscribeUsers($ids)
    {
        $stmt = $this->db->prepare(<<<QUERYU
            UPDATE users
            SET notify = 0
            WHERE user_id = ?
QUERYU
        );

        foreach ($ids as $id) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
        }

        $stmt->close();

        return $this;
}

    public function updateSubscriber($id, $email, $name, $group)
    {
        $stmt = $this->db->prepare(<<<QUERYUP
            UPDATE subscribe
            SET
              sub_email = ?,
              user_name = ?,
              sub_type = ?
            WHERE sub_id = ?
QUERYUP
        );

        $stmt->bind_param('ssii', $email, $name, $group, $id);
        $stmt->execute();
        $stmt->close();

        return $this;
    }

    public function getFilters()
    {
        $options = '';

        foreach (self::$filterGroupList as $filterGroupIndex => $filterGroupName) {
            $selected = $this->filterGroup == $filterGroupIndex ? ' selected' : '';
            $options .= "<option value='$filterGroupIndex'$selected>";
            $options .= "$filterGroupName ({$this->amountSubscribersByType[$filterGroupIndex]})</option>";
        }

        return <<<FILTERS
<select style='margin-left: 20px' name='subscribers-filter-group' onchange='this.form.submit()'>
    $options
</select>
FILTERS;
    }

    public function generatePages()
    {
        $pages = '';
        $perPagesOptions = '';

        if ($this->amountPages > 1) {
            for ($i = 1; $i <= $this->amountPages; $i++) {
                $pages .= $this->currentPage == $i ? "<span style='text-decoration: underline'> $i </span>" :
                    "<a href='/?admin=subscribers&p={$i}&per-page={$this->subscribersPerPage}&group={$this->filterGroup}'> $i </a>";

            }
        }

        foreach ($this->perPagesVariants as $perPagesVariant) {
            $checked = $this->subscribersPerPage == $perPagesVariant ? ' selected' : '';
            $perPagesOptions .= "<option value='$perPagesVariant' $checked>$perPagesVariant</option>";
        }

        return "
<div class='subscribers-per-page'>
     <span>Показывать по:</span>
     <form method='POST' action='/?admin=subscribers'>
         <select  name='subscribers-per-page' onchange='this.form.submit()'>
             $perPagesOptions
         </select>
</form>
</div>
<div class='subscribers-pages'>$pages</div>
";
    }
}
