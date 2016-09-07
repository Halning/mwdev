<?php

namespace Modules;

/**
 * Compare old and actual prices.
 */
class InformUsers
{
    const SUBJECT = 'Цена изменилась!';

    public $emailsToInform = array();
    public $daysDuration   = 7;
    private $time;
    private $timeDuration;
    private $db;
    private $template;
    private $currentPrices = array();
    private $oldPrices     = array();
    private $headers       = array(
        "MIME-Version: 1.0\r\n",
        "Content-type: text/html; charset=UTF-8\r\n",
        "From: makewear.com.ua<sales@makewear.com.ua>\r\n"
    );

    /**
     * Initialize theme, template.
     */
    public function __construct()
    {
        $this->db           = MySQLi::getInstance()->getConnect();
        $this->template     = new \templ();
        $this->time         = time();
        $this->timeDuration = $this->daysDuration * 24 * 60 * 60;
    }

    /**
     * Gets all items from shop_commodities table.
     * @return $this
     */
    public function getCurrentPrices()
    {
        $result = $this->db->query(<<<QUERY1
            SELECT
              commodity_ID, com_name, cod, sc.alias, commodity_price,
              commodity_price2, cat.cat_name brand
            FROM shop_commodity sc
            INNER JOIN `shop_commodities-categories` scc
              ON sc.commodity_ID = scc.commodityID
            INNER JOIN shop_categories cat
              ON scc.categoryID = cat.categories_of_commodities_ID
            WHERE commodity_visible = 1
            AND cat.categories_of_commodities_parrent = 10
QUERY1
        );

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->currentPrices[$row['commodity_ID']] = array(
                    'id' => $row['commodity_ID'],
                    'brand' => $row['brand'],
                    'name' => $row['com_name'],
                    'cod' => $row['cod'],
                    'price' => $row['commodity_price'],
                    'price2' => $row['commodity_price2'],
                    'url' => "/product/{$row['commodity_ID']}/{$row['alias']}.html"
                );
            }
        }

        return $this;
    }

    /**
     * Gets all items from shop_watch_price table.
     * @return $this
     */
    public function getOldPrices()
    {
        $result = $this->db->query(<<<'QUERY5'
            SELECT * FROM shop_watch_price
QUERY5
        );

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->oldPrices[] = $row;
            }
        }

        return $this;
    }

    /**
     * Compare actual and old prices, and if prices different - return emails with differ prices.
     * @return array with emails and commodities.
     */
    public function comparePrices()
    {
        foreach ($this->oldPrices as $prices) {
            $commodity = $this->currentPrices[(int) $prices['com_id']];
            if (!empty($commodity)) {
                if (ceil($prices['price']) > ceil($this->currentPrices[$prices['com_id']]['price'])
                    &&
                    floor($prices['price']) > floor($this->currentPrices[$prices['com_id']]['price'])) {
                    $commodity['oldPrice'] = ceil($prices['price']);
                    $commodity['hash']     = $prices['hash'];

                    if (!$prices['informed']) {
                        $this->emailsToInform[$prices['email']][] = $commodity;
                    }

                    if (($this->time - strtotime($prices['inform_date'])) > $this->timeDuration) {
                        $this->updateOldPrices(
                            $prices['com_id'], $commodity['price'],
                            $commodity['price2']
                        );
                    }
                }
//            if (ceil($prices['price2']) != ceil($this->currentPrices[$prices['com_id']]['price2'])) {
//                $difference = true;
//                $commodity['oldPrice2'] = $prices['price2'];
//            }
            }
        }

        return $this;
    }

    /**
     * Updates prices in table shop_watch_price after checks with actual prices.
     * @param $com_id
     * @param $price
     * @param $price2
     * @return bool result of query.
     */
    private function updateOldPrices($com_id, $price, $price2)
    {
        $this->db->query(<<<QUERY3
            UPDATE shop_watch_price
            SET
              price = {$price},
              price2 = {$price2},
              informed = 0
            WHERE com_id = {$com_id}
QUERY3
        );

        return $this;
    }

    /**
     * Generates lines for table.
     * @param array $fields  of properties of commodity (price, name...).
     * @return string, lines for table in email body.
     */
    public function generateLines($fields)
    {
        $domain  = 'http://makewear.azurewebsites.net';
        $through = "style='text-decoration:line-through;";
        $through .= " text-align:center; background: #CAE2E6; border: 1px solid #8A8A9B;'";
        $styles  = "style='text-align:center; background: #CAE2E6; border: 1px solid #8A8A9B;'";
        $lines   = <<<HTML1
            <tr>
                <td $styles>{$fields['cod']}</td>
                <td $styles>{$fields['brand']}</td>
                <td $through>{$fields['oldPrice']}</td>
                <td $styles>{$fields['price']}</td>
                <!--<td $through>{$fields['oldPrice2']}</td>
                <td $styles>{$fields['price2']}</td>-->
                <td $styles>
                    <a href='$domain{$fields['url']}' target='_blank'>{$fields['name']}</a>
                </td>
                <td $styles>
                    <a href='$domain/unsubscribe/{$fields['hash']}' target='_blank'>
                        <img src='$domain/email/images/cancel.png'/>
                    </a>
                </td>
            </tr>
HTML1;

        return $lines;
    }

    /**
     * Generates message, used lines and template.
     * @param string $lines lines for table.
     * @return string ready body for email.
     */
    public function generateMessage($lines)
    {
        $this->template->set_tpl('{$linesWatchList}', $lines);
        $this->template->set_tpl('{$mailContent}',
            $this->template->get_tpl('mail.watchList', "../../"));

        $message = $this->template->get_tpl('mail.main', '../../');

        return $message;
    }

    public function setInformed()
    {
        $itemsIds = array();
        foreach ($this->emailsToInform as $items) {
            foreach ($items as $item) {
                $itemsIds[] = $item['id'];
            }
        }

        $itemsIds = implode(',', array_unique($itemsIds));
        $this->db->query(<<<QUERY3
            UPDATE shop_watch_price
            SET
              informed = 1,
              inform_date = NOW()
            WHERE com_id IN ($itemsIds)
QUERY3
        );

        return $this;
    }
}