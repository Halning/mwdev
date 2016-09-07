<?php
/**
 * Created by PhpStorm.
 * User: volodini
 * Date: 8/17/15
 * Time: 6:11 PM
 * В конструкторі класа відбувається з'днання з базою даних та читання даних з excel файлу з сайту Vitaliti.
 * В методі getAllSizes отримуються всі видимі товари з бази даних, кожний товар провіряється на збіг з excel по артикулі,
 * потім по кольору, потім по назві. В методі convertSizesToString товари які присутні в excel файлі провіряються та
 * записуються їх розміра в базу даних, а товари які не знайдені в excel файлі в методі hideNotActualOffers
 * діляться на notActuals і notDefined, notActuals - удаляються або скриваються, а notDefined - парсяться методами
 * hideNotActualOffers i initNotDefined.
 */

class Vitality
{
    const DSN = 'mysql:dbname=zoond_make;host=localhost';
    const USER = 'zoond_make';
    const PWD = 'SJ2GHrMG2zcARCHm';
//    const DSN = 'mysql:dbname=makewear;host=localhost';
//    const USER = 'root';
//    const PWD = '123123q';
    const MSG_HIDE = '<span style = "color:blue">hide</span><br>';
    const MSG_CANT_HIDE = '<span style = "color:red">can_t hide</span><br>';
    const FILE_PATH = "../../excel/pricelist.csv";
    const FILE_URL = 'http://vitality-opt.com.ua/pricelistexport/export/index/format/csv/';
    const UTF = 'utf-8';
    const MSG_DEL = '<span style = "color:blue">Del</span><br>';
    const MSG_CANT_DEL = '<span style = "color:red">can_t Del</span><br>';
    const ERROR = 'an error processing';
    /***
     * @var PDO - database handler.
     */
    private $dbh;

    /***
     * @var - category id.
     */
    public $catId;

    public $parId;

    /***
     * @var array - all visible items from database.
     */
    public $mwOffers = array();

    /***
     * @var array - all row items from excel file.
     */
    public $excelOffers = array();

    /***
     * @var array - all id's of items with their sizes which was get from excel file.
     */
    public $arraySizes = array();

    /***
     * @var array - sizes from database.
     */
    public $mwSizes = array();

    /***
     * @var array - colors of items.
     */
    public $colors = array(
        "красный", "черный", "голубой", "малина", "белый", "желтый", "беж", "розовый", "серый", "бордо", "синий",
        "коралл", "бирюза", "оранж", "зеленый", "голубой", "мята", "алатовы", "персик", "фиолет", "сирень", "горчица",
        "коричневый", "какао", "гранит", "цветочный",
    );

    /***
     * @var array - id's with their urls.
     */
    public $urls;

    /***
     * @var array - items with empty sizes.
     */
    public $itemsToHide = array();

    /***
     * items which not initealized in excel file.
     */
    public $notDefined = array();

    /***
     * @var int - number of hiden items.
     */
    public $hide = 0;

    /***
     * @var int - number of uptadet item.
     */
    public $updated = 0;

    /***
     * @var int - quantity of mwOffers;
     */
    public $count = 0;

    /***
     * init's dbh and excelOffers.
     */
    public function __construct($catId, $parId)
    {
        $this->dbh = new PDO(self::DSN, self::USER, self::PWD);
        unlink(self::FILE_PATH);
        copy(self::FILE_URL, self::FILE_PATH);
        $this->excelOffers = $this->readExcel();
        $this->catId = $catId;
        $this->parId = $parId;
    }

    /***
     * @return array with all items from excel file.
     * @throws PHPExcel_Reader_Exception
     */
    public function readExcel()
    {
        $inputFileType = PHPExcel_IOFactory::identify(self::FILE_PATH);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load(self::FILE_PATH);
        return $objPHPExcel->getActiveSheet()->toArray();
    }

    /***
     * @param $mwOfferName string - name which search colors.
     * @return bool|int|string - index of needed color or false.
     */
    public function getMwColorIndex($mwOfferName) {
        foreach ($this->colors as $key => $color) {
            if (stristr($mwOfferName, $color)) {
                return $key;
            }
        }
        return false;
    }

    /***
     * converts sizes from array view to string view.
     */
    public function convertSizesToString()
    {
        foreach ($this->arraySizes as $keySize => $arraySize) {
            $stringSize = '';
            if (count($arraySize) != 0) {
                foreach ($arraySize as $size) {
                    $stringSize .= ";" . $size;
                }
                $stringSize = substr($stringSize, 1);
                if (!$this->checkSizes($keySize, $stringSize)) {
                    $this->setSizes($keySize, $stringSize);
                } else {
                    $this->showActual($this->urls[$keySize]);
                }
            } else {
                $this->itemsToHide[$keySize] = $this->urls[$keySize];
            }
        }
        echo '<br>';
    }

    /***
     * search needed item from excel file and sets appropriate values.
     */
    public function getAllSizes()
    {
        $this->getMwOffers();
        $this->count = count($this->mwOffers);
        $this->setInterface($this->count, 1, $this->updated, 'Reading, parsing and processing xls file...');
        foreach ($this->mwOffers as $x => $mwOffer) {
            $cod = $mwOffer['cod'];
            $mwOfferName = mb_strtolower($mwOffer['com_name'], self::UTF);
            $mwOfferName = str_replace("Розница ", "", $mwOfferName);
            $mwColorIndex = $this->getMwColorIndex($mwOfferName);
            foreach ($this->excelOffers as $key => $excelOffer) {
                preg_match('/[0-9\-]+/', $excelOffer[0], $vitalityMatches);
                if (count($vitalityMatches) > 0) {
                    $vitalityMatches =   str_replace('-', '', $vitalityMatches[0]);
                    if ($vitalityMatches == $cod) {
                        $excelOfferName = mb_strtolower($excelOffer[0], self::UTF);
                        if (stristr($excelOfferName, $this->colors[$mwColorIndex])) {
                            if (!in_array($excelOffer[1], $this->arraySizes[$mwOffer['commodity_ID']])) {
                                $this->arraySizes[$mwOffer['commodity_ID']][] = $excelOffer[1];
                            }
                        } elseif (stristr($excelOfferName, $mwOfferName)) {
                            if (!in_array($excelOffer[1], $this->arraySizes[$mwOffer['commodity_ID']])) {
                                $this->arraySizes[$mwOffer['commodity_ID']][] = $excelOffer[1];
                            }
                        }
                    }
                }
            }
        }
        $this->convertSizesToString();
    }

    /**
     * Get all visible offers from Makewear database
     * @set $mwOffers
     */
    public function getMwOffers()
    {
        $sql = "SELECT * FROM `shop_commodity` as `item` INNER JOIN `shop_commodities-categories` as `cat` ON
 item.commodity_ID = cat.commodityID
 WHERE cat.categoryID = {$this->catId} AND item.commodity_visible = 1";
        $this->dbh->query('SET charset utf8');
        $stmt = $this->dbh->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//            if ($row['commodity_ID'] < 13500) continue;
//            if ($row['commodity_ID'] > 13500) break;
            $this->mwOffers[] = $row;
            $this->arraySizes[$row['commodity_ID']] = array();
            $this->urls[$row['commodity_ID']] = $row['from_url'];
            $this->mwSizes[$row['commodity_ID']] = $row['com_sizes'];
        }
    }

    /**
     * Set field - com_sizes in Database
     * @param $id
     * @param $sizes
     */
    public function setSizes($id, $sizes) {
        $sizes = $this->dbh->quote($sizes);
        $sql = "UPDATE `shop_commodity`
SET `com_sizes` = $sizes
WHERE `commodity_ID` = $id";
        $this->dbh->query('SET names utf8');
        $dbsh = $this->dbh->exec($sql);
        $this->showNotActual($dbsh, $id);
        $this->updated++;
    }

    public function checkAvailableItem($url)
    {
        $head=get_headers($url);
        if (!$head) {
            return false;
        } else {
            return substr($head[0],9,3);
        }
    }

    /**
     * Divide items on notActual and notDefined and displays it's.
     * Also hide or delete notActual items.
     * Sets visibiliti 0 for this items.
     */
    public function hideNotActualOffers()
    {
        $notActuals = array();
        foreach ($this->itemsToHide as $key => $url) {
            sleep(1);
            $header = $this->checkAvailableItem($url);
            if (!$header || $header == '503') {
                $notActuals[$key] = $url;
                $this->showNotAvailable($header, $url);
            }elseif ($header == '301') {
                if ($this->checkAvailable301($url)) {
                    $notActuals[$key] = $url;
                    $this->showNotAvailable($header, $url);
                } else {
                    $this->notDefined[$key] = $url;
                    $this->showNotDefined($header, $url);
                }
            } else {
                $this->notDefined[$key] = $url;
                $this->showNotDefined($header, $url);
            }
            ob_flush();
            flush();
            $this->setInterface(
                $this->count, $this->getStep($key),
                $this->updated, 'Checking itemsToHide on Accessibility...'
            );
        }
        echo "<h2>Відсутні товари!!!!!!!!!!</h2>";
        $this->hide = count($notActuals);
        var_dump($notActuals);
        echo "<h2>Неоприділені товари!!!!!!!!!!</h2>";
//        var_dump($notDefined);
//        return;
        foreach ($notActuals as $id => $notActual) {
//            $this->deleteNotActualOffers($id);
            $sqlUnvisible = "UPDATE `shop_commodity` SET `commodity_visible` = 0
WHERE `commodity_ID` = $id";
            $stmt = $this->dbh->exec($sqlUnvisible);
            if ($stmt) {
                echo self::MSG_HIDE;
            } else {
                echo self::MSG_CANT_HIDE;
            }
            ob_flush();
            flush();
        }
    }

    /***
     * for each item which have size sets it.
     */
    public function initNotDefined()
    {
        foreach ($this->notDefined as $key => $url) {
            $sizes = $this->parseItem($url);
            sleep(1);
            if ($sizes) {
                if (!$this->checkSizes($key, $sizes)) {
                    $this->setSizes($key, $sizes);
                } else {
                    $this->showActual($url);
                }
            }
            ob_flush();
            flush();
            $this->setInterface($this->count, $this->getStep($key), $this->updated, $url);
        }
    }

    /***
     * Gets headers from url.
     * @param $url
     * @return bool
     */
    public function checkAvailable301($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); // отправляем на
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:23.0) Gecko/20100101 Firefox/23.0");
        curl_setopt($ch, CURLOPT_HEADER, 0); // пустые заголовки
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// таймаут4
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// просто отключаем проверку сертификата
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/my_cookies.txt'); // сохранять куки в файл
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/my_cookies.txt');
        $total = curl_exec($ch);
        if (curl_errno($ch))
        {
            print curl_error($ch);
            return false;
        }
        curl_close($ch);
        if (stristr($total, self::ERROR)) {
            return true;
        } else {
            return false;
        }
    }

    /***
     * parse item sizes from url
     * @param $url
     * @return bool|string
     */
    public function parseItem($url)
    {
        $sizes = '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); // отправляем на
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:23.0) Gecko/20100101 Firefox/23.0");
        curl_setopt($ch, CURLOPT_HEADER, 0); // пустые заголовки
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// таймаут4
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// просто отключаем проверку сертификата
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/my_cookies.txt'); // сохранять куки в файл
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/my_cookies.txt');
        $total = curl_exec($ch);
        if (curl_errno($ch)) {
            var_dump(curl_error($ch));
            return false;
        } else {
            preg_match('/Product.Config\((.*)\)/', $total, $matches);
            if (isset($matches[1])) {
                $json = json_decode($matches[1], true);
                $keyId = array_keys($json['attributes'])[0];
//                var_dump($keyId);
                foreach ($json['attributes'][$keyId]['options'] as $size) {
                    $sizes .= ";" . $size['label'];
//                    var_dump($size);
                }
                $sizes = substr($sizes, 1);
                return $sizes;
            } else {
                return false;
            }
        }
    }

    /***
     * Delete item from database.
     * @param $id
     */
    public function deleteNotActualOffers($id)
    {
        $sqlUnvisible = "DELETE FROM `shop_commodity`
WHERE `commodity_ID` = $id";
        $stmt = $this->dbh->exec($sqlUnvisible);
        if ($stmt) {
            echo self::MSG_DEL;
        } else {
            echo self::MSG_CANT_DEL;
        }
    }

    public function checkSizes ($id, $sizes)
    {
        if ($this->mwSizes[$id] == $sizes) {
            return true;
        } else {
            return false;
        }
    }

    public function setInterface($count, $step, $updated, $result)
    {
        $a=$count/100;
        $a2=round($step/$a, 2);
        $andSql = '';
        if ($step == 1) {
            $andSql .=', `update_add`=0';
        }
        if ($updated) {
            $andSql .=', `update_add`=`update_add`+1';
        }
        $sql = "
UPDATE `parser_interface`
 SET `update_prog`='{$a2}', `check_prog`='{$step}', `text`='{$result}'{$andSql}
 WHERE `par_id`='{$this->parId}'";
        $this->dbh->query('SET names utf8');
        $stmt = $this->dbh->exec($sql);
        if ($stmt > 0) {
            echo "Good";
        } else {
            echo "Not Good";
        }
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
WHERE `par_id`='{$this->parId}'";
        $this->dbh->query('SET names utf8');
        $this->dbh->exec($sql);
    }

    public function getStep($id)
    {
        $stepKeys = array_keys($this->arraySizes);
        $stepId = array_search($id, $stepKeys);
        return $stepId + 1;
    }

    private function showActual ($url)
    {
        echo '<a href="'.$url.'" target="_blank">'.$url.'</a> - Цвет и размер актуален!<br>';
    }

    private function showNotAvailable ($header, $url)
    {
        echo '<a href="'.$url.'" target="_blank">'.$header.' - '.$url.'</a> - Не доступно!<br>';
    }

    private function showNotDefined ($header, $url)
    {
        echo '<a href="'.$url.'" target="_blank">'.$header.' - '.$url.'</a><span style = "color:green"> - Не определено!</span><br>';
    }

    private function showNotActual ($dbst, $id)
    {
        echo '<a href="'.$this->urls[$id].'" target="_blank"><span style = "color:green">'.$id.'</span></a>
         - Размер не актуален! - '.$dbst.'<br>';
        ob_flush();
        flush();
    }
}