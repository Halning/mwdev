<?php
/**
 * Created by PhpStorm.
 * Date: 05.04.16
 * Time: 13:38
 */

namespace Modules\Prices;

use Modules\MySQLi;

class FeedsLoad
{
    const DOMEN = 'http://www.makewear.com.ua/';

    public $error = false;
    public $products = array();
    protected $_db;

    public function __construct()
    {
        $this->_db = MySQLi::getInstance()->getConnect();
        $this->products[] = array(
            'ID',
            'Item title',
            'Destination URL',
            'Image URL',
            'Price'
        );
    }

    public function getAllProducts()
    {
        $result = $this->_db->query(<<<QUERY1
        SELECT
		  DISTINCT `commodity_ID` id, `cod`, `com_name` name, sc.alias comAlias,
		  brands.cat_name brandName, cats.cat_name categoryName, commodity_price price
        FROM `shop_commodity` sc
		INNER JOIN `shop_commodities-categories` scc
		  ON `sc`.`commodity_ID` = `scc`.`commodityID`
		INNER JOIN `shop_categories` cats
		  ON `cats`.`categories_of_commodities_ID` = `scc`.categoryID
		INNER JOIN `shop_categories` brands
		  ON `brands`.`categories_of_commodities_ID` = `sc`.brand_id
		WHERE `commodity_visible`='1'
		AND cats.visible = 1
        AND cats.categories_of_commodities_ID NOT IN (26, 27)
		AND cats.categories_of_commodities_parrent IN (
		  264, 209, 212, 213, 261, 211, 266, 267, 210, 268
		)
		GROUP BY commodity_ID
        ORDER BY categoryName
QUERY1
        );

        if ($result && $result->num_rows > 0) {
            $i = 1;
            while ($row = $result->fetch_object()) {
                $i++;
                $alias = str_replace("\r\n", '', $row->comAlias);
                $cod = str_replace("\r\n", '', $row->cod);
                $name = $this->getFullName($row->name, $row->categoryName).' '.$row->brandName;
                $name .= ' '.$cod;
                $price = ceil($row->price).' UAH';
                $url = self::DOMEN."product/{$row->id}/{$alias}.html";
                $imageSrc = self::DOMEN."{$row->id}stitle/{$alias}.jpg";
                $this->products[] = array(
                    $row->id,
                    $name,
                    $url,
                    $imageSrc,
                    $price
                );

//                if ($i > 1000) break;
            }
        }

        return $this->products;
    }

    public function getAllProducts1()
    {
        $result = $this->_db->query(<<<QUERY1
        SELECT
		  DISTINCT `commodity_ID` id, `com_name` name, com_fulldesc description,
		  cats.cat_name categoryName, com_sizes size, commodity_select color
        FROM `shop_commodity` sc
		INNER JOIN `shop_commodities-categories` scc
		  ON `sc`.`commodity_ID` = `scc`.`commodityID`
		INNER JOIN `shop_categories` cats
		  ON `cats`.`categories_of_commodities_ID` = `scc`.categoryID
		INNER JOIN `shop_categories` brands
		  ON `brands`.`categories_of_commodities_ID` = `sc`.brand_id
		WHERE `commodity_visible`='1'
		AND cats.visible = 1
        AND cats.categories_of_commodities_ID NOT IN (26, 27)
		AND cats.categories_of_commodities_parrent IN (
		  264, 209, 212, 213, 261, 211, 266, 267, 210, 268
		)
		GROUP BY commodity_ID
        ORDER BY categoryName
QUERY1
        );

        if ($result && $result->num_rows > 0) {
            $i = 1;
            while ($row = $result->fetch_object()) {
                $i++;
                $description = htmlspecialchars(strip_tags($row->description));
                $description = str_replace('&nbsp;', '', $description);
                $color = '';
                if ($row->color) {
                    $colors = explode(';', $row->color);
                    foreach ($colors as $col) {
                        $color .= strstr($col, '=', true).'; ';
                    }
                }

                $this->products[] = array(
                    $row->categoryName,
                    $row->name,
                    $description,
                    $color,
                    $row->size
                );

                if ($i > 1000) break;
            }
        }

        return $this->products;
    }

    protected function getFullName($name, $categoryName)
    {
        $wrightCommodities = array(
            "футболка", "майка", "кофточка", "блуза", "брюки",
            "юбка", "свитшот", "кофта", "туника", "платье",
            "костюм", "ветровка", "рубашка", "блуза", "свитшот",
            "пиджак", "куртка", "кардиган", "комбинезон", "жилет",
            "сарафан", "шапка", "майка", "ремень", "сумка", "шарф",
            "брелок", "футляр", "чехол", "костюмчик", "костм",
            "шорты", "бриджи", "леггинсы", "болеро", "жакет",
            "гольф", "лосины", "плащ", "пальто","блузка", "комплект",
            "легинсы", "купальник", "свитер", "джинсы", "штаны",
            "баска","комбидресс", "кафта", "воротник", "джемпер",
            "пончо", "пояс", "браслет", "колье", "комбинeзон",
            "бомбер", "рюкзак", "клатч", "кошелек", "камни",
            "подвеска", "серьги", "боди", "парка", "браслет",
            "брошь", "автопятки", "ботинки", "сапоги", "туфли",
            "балетки", "вьетнамки", "ботильоны", "ботфорты",
            "балетка", "сникерсы", "пинетки", "босоножки", "сукня",
            "штани", "толстовка",
        );

        $translate          = array(
            "кафта" => "Кофта",
            "Штаны" => "Брюки",
            "костюмчик" => "Костюм",
            "костм" => "Костюм",
            "Платья" => "Платье",
            "Костюмы" => "Костюм",
            "Рубашки" => "Рубашка",
            "Юбки" => "Юбка",
            "Блузы" => "Блуза",
            "Свитшоты" => "Свитшот",
            "Пиджаки" => "Пиджак",
            "Спортивные костюмы" => "Костюм",
            "Куртки" => "Куртка",
            "Футболки" => "Футболка",
            "Кофты" => "Кофта",
            "Кардиганы" => "Кардиган",
            "Комбинезоны" => "Комбинезон",
            "Жилеты" => "Жилет",
            "Туники" => "Туника",
            "Сарафаны" => "Сарафан",
            "Шапки" => "Шапка",
            "Майки" => "Майка",
            "Ремни" => "Ремень",
            "Сумки" => "Сумка",
            "Шарфы" => "Шарф",
            "Брелки"         => "Брелок",
            "Кошельки"       => "Кошелёк",
            "Футляры, чехлы" => "Футляр",
            "балетка"        => "Балетки",
        );

        $u                 = 'utf-8';
        $fullName             = '';
        $comName           = mb_strtolower($name, $u);
        $comName           = str_replace("&#34;", " ", $comName);
        $comName           = str_replace(",", " ", $comName);
        $comName           = str_replace("-", " ", $comName);
        $comName           = str_replace('"', " ", $comName);
        $comNameFirst      = explode(" ", $comName)[0];
        $comNameFirst      = mb_strtolower($comNameFirst, $u);

        foreach ($wrightCommodities as $writeCom) {
            if (stristr($comName, $writeCom) ||
                $writeCom == $comName ||
                $writeCom == $comNameFirst
            ) {
                $fullName = $writeCom;
                break;
            }
        }

        if ('' == $fullName) {
            $fullName = $categoryName;
        }

        if (array_key_exists($fullName, $translate)) {
            $fullName = strtr($fullName, $translate);
        }

        $nameFirstLater = mb_substr(mb_strtoupper($fullName, $u), 0, 1, $u);
        $nameRestLetters = mb_substr($fullName, 1, strlen($fullName), $u);

        return $nameFirstLater . $nameRestLetters;
    }
}
