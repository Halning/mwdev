<?php
/**
 * Created by PhpStorm.
 * User: volodini
 * Date: 6/25/15
 * Time: 2:43 PM
 */
class Glem
{

    const URL = 'http://www.glem.com.ua/eshop/ym4.php';
    const DSN = 'mysql:dbname=zoond_make;host=localhost';
    const USER = 'zoond_make';
    const PWD = 'SJ2GHrMG2zcARCHm';
//    const DSN = 'mysql:dbname=makewear;host=localhost';
//    const USER = 'root';
//    const PWD = '123123q';
    const MSG_HIDE = '<span style = "color:blue">hide</span><br>';
    const MSG_CANT_HIDE = '<span style = "color:red">can_t hide</span><br>';


    public $xml;

    private $dbh;

    public $mwOffers = array();

    public $offers = array();

    public $mwColors = array();

    private $mwPrices = array();

    public $hide = 0;

    public $updated = 0;

    public function __construct()
    {
        $this->xml = simplexml_load_file(self::URL);
        $this->dbh = new PDO(self::DSN, self::USER, self::PWD);
    }

    /**
     * Get all visible offers from Makewear database
     * @set $mwOffers
     */
    public function getMwOffers()
    {
        $sql = "SELECT * FROM `shop_commodity` as `item` INNER JOIN `shop_commodities-categories` as `cat` ON
 item.commodity_ID = cat.commodityID
 WHERE cat.categoryID = 15 AND item.commodity_visible = 1";
        $this->dbh->query('SET charset utf8');
        $stmt = $this->dbh->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->mwOffers[] = $row['from_url'];
            $this->mwColors[$row['from_url']] = $row['commodity_select'];
            $this->mwPrices[$row['from_url']] = array(
                $row['commodity_price'],
                $row['commodity_price2']
            );
        }
  //      $this->mwOffers = [];
  //      $this->mwOffers[] = 'http://www.glem.com.ua/zhenskie-futbolki-mayki-optom/adorable-futbolka-kivi-br.html';
    }

    /**
     * Check price, color, size from xml - glem
     */
    public function getOffers()
    {
        $this->getMwOffers();
        foreach ($this->mwOffers as $url) {
            foreach ($this->xml->shop->offers->offer as $offer) {
                if ((string)$offer->url == $url) {
                    $this->offers[$url]['price'] = $offer->price->__toString();
                    $this->offers[$url]['price2'] = $offer->prices->price->value->__toString();
                    $this->offers[$url]['colors'][$offer->param[0]->__toString()][] =  $offer->param[1]->__toString();
                }
            }
        }
    }

    public function setSizes()
    {
        $this->getOffers();
        foreach ($this->offers as $url => $item) {
            $colors = '';
            foreach($item['colors'] as $color => $sizes) {
                $colorSingle = '';
                $colorSingle .= $color."=";
                foreach ($sizes as $size) {
                    $colorSingle .= $size.',';
                }
                $colorSingle = substr($colorSingle, 0, strlen($colorSingle)- 1);
                $colors .= $colorSingle.';';
            }
            $colors = substr($colors, 0, strlen($colors)- 1);
            if (!$this->checkColors($url, $colors)) {
                $dbsn = $this->setColors($url, $colors);
                $this->showNotActualColors($dbsn, $url, $colors);
                $this->updated++;
            }
            if ($item['price'] != '' && $item['price2'] != '') {
                if (!$this->checkPrices($url, $item['price'], $item['price2'])) {
                    $dbsn = $this->setPrices($url, $item['price'], $item['price2']);
                    $this->showNotActualPrices($dbsn, $url, $this->mwPrices[$url], $item);
                    $this->updated++;
                }
            }
        }
    }

    /**
     * Set field - commodity_select in Database
     * @param $url
     * @param $colors
     */
    public function setColors($url, $colors) {
        $colors = $this->dbh->quote($colors);
        $url = $this->dbh->quote($url);
        $sql = "UPDATE `shop_commodity`
SET `commodity_select` = $colors
 WHERE `from_url` = $url";
        $this->dbh->query('SET names utf8');
        $dbsh = $this->dbh->exec($sql);
        return $dbsh;
    }

     /***
     * set fields price and price1 in database.
     * @param $url
     * @param $price
     * @param $optPrice
     */
    public function setPrices($url, $price, $optPrice) {
        $url = $this->dbh->quote($url);
        $sql = "UPDATE `shop_commodity`
SET `commodity_price` = $price, `commodity_price2` = $optPrice
WHERE `from_url` = $url";
        $dbsh = $this->dbh->exec($sql);
        return $dbsh;
    }

    /**
     * Displays items which present in as, but absent in Glem site!!
     * Sets visibiliti 0 for this items.
     */
    public function hideNotActualOffers()
    {
        $this->getMwOffers();
        $offers = array();
        foreach ($this->xml->shop->offers->offer as $offer) {
            $offers[] = (string)$offer->url;
        }
        $not_actuals = array();
        foreach (array_diff_key(array_count_values($this->mwOffers), array_count_values($offers)) as $key => $value) {
            $not_actuals[] = $key;
        }
        echo "<h2>Відсутні товари!!!!!!!!!!</h2>";
        $this->hide = count($not_actuals);
        var_dump($not_actuals);
        foreach ($not_actuals as $not_actual) {
            $not_actual_quote = $this->dbh->quote($not_actual);
            $sql_set_unvisible = "UPDATE `shop_commodity` SET `commodity_visible` = 0
WHERE `from_url` = $not_actual_quote";
            $stmt = $this->dbh->exec($sql_set_unvisible);
            if ($stmt) {
                echo self::MSG_HIDE;
            } else {
                echo self::MSG_CANT_HIDE;
            }
        }
    }

    public function checkColors ($url, $colors) 
    {
        if ($this->mwColors[$url] == $colors) {
            return true;
        } else {
            return false;
        }
    }

    public function checkPrices ($url, $price, $optPrice)
    {
        if ($this->mwPrices[$url][0] == $price && $this->mwPrices[$url][1] == $optPrice) {
            return true;
        } else {
            return false;
        }
    }
    
    private function showActual ($url) 
    {
        echo '<a href="'.$url.'">'.$url.'</a> - Цвет и размер актуален!<br>';
    }    
    
    private function showNotActualColors ($dbst, $url, $colors)
    {
        echo '<a href="'.$url.'"><span style = "color:green">'.$url.'</span></a> - Цвет и размер не актуален! - '.$dbst.'<br>';
    }

    private function showNotActualPrices ($dbst, $url, $oldPrices, $prices)
    {
        echo '<a href="'.$url.'"><span style = "color:brown">Цена изменилась! - '
            .$oldPrices[0].' -> '.$prices['price'].'; '
            .$oldPrices[1].' -> '.$prices['price2'].'; - '
            .$dbst.'</span></a><br>';
    }

    public function setInterfaceComplete()
    {
        $count = count($this->mwOffers);
        $hide = $this->hide;
        $updated = $this->updated;
        $today = date("d-m-Y H:i:s");
        $result = "Complete! - ".$today."<br>Обновлено - ".$updated."<br>Скрыто - ".$hide;
        $sql = "
UPDATE `parser_interface`
SET `update_prog`=100, `check_prog`='{$count}',`text`='{$result}', `update_add`='{$updated}', `update_date`='{$today}'
WHERE `par_id`='6'";
        $this->dbh->query('SET names utf8');
        $this->dbh->exec($sql);
    }
}