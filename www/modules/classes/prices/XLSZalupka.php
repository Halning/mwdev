<?php
/**
 * Created by PhpStorm.
 * User: webler
 * Date: 08.04.16
 * Time: 11:17
 */

namespace Modules\Prices;


class XLSZalupka extends FeedsLoad
{
    public function __construct()
    {
        $this->_db = MySQLi::getInstance()->getConnect();
        $this->products[] = array(
            'Название_позиции',         //name
            'Цена',                     //price
            'Валюта',                   // 'UAH'
            'Единица_измерения',        // 'шт.'
            'Ссылка_изображения',       // image url
            'Наличие',                  // '+'
            'Адрес_подраздела',
            'Идентификатор_подраздела',
            'Детальное_описание',       // description
            'Минимальный_объем_заказа',
            'Возможность_поставки',
            'Срок_поставки',
            'Способ_упаковки',
            'Уникальный_идентификатор',
            'Идентификатор_товара',     // id
            'Производитель',            // brand ????
            'Страна_производства',
            'Код_товара',               // cod
            'Оптовая_цена_1',
            'Оптовый_мин_заказ_1',
            'Оптовая_цена_2',
            'Оптовый_мин_заказ_2',
            'Оптовая_цена_3',
            'Оптовый_мин_заказ_3',
            'Адрес_товара',              //url zalupka
            'Метка',
            'Внешняя ссылка',            // our url
            'Мета_Заголовок',
            'Мета_Ключевые_слова',
            'Мета_Описание',
            'Лучший_товар_в_рубрике',
            'Только_оптом',
            'Статус',                    // 'опубликован'
            'Длина',
            'Ширина',
            'Высота',
            'Вес',
            'Моя_характеристика_Название', // 'Производитель'
            'Моя_характеристика_Значение', // brand
            'Моя_характеристика_Единица',
        );
    }

    public function getAllProducts()
    {
        $result = $this->_db->query(<<<QUERY1
        SELECT
		  DISTINCT `commodity_ID` id, `cod`, `com_name` name, sc.alias comAlias,
		  brands.cat_name brandName, cats.cat_name categoryName, commodity_price price,
		  commodity_price2 price2, com_fulldesc description, brands.cat_name brandName
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
                $url = self::DOMEN."product/{$row->id}/{$alias}.html";
                $imageSrc = self::DOMEN."{$row->id}stitle/{$alias}.jpg";
                $priceOpt = $row->price2 && ceil($row->price2) < ceil($row->price) ?
                    ceil($row->price2) : '';
                $optAmount = $row->price2 && ceil($row->price2) < ceil($row->price) ? 5 : '';
                $this->products[] = array(
                    $name,                     //name
                    ceil($row->price),         //price
                    'UAH',                     //'UAH'
                    'шт.',                     //'шт.'
                    $imageSrc,                 //image url
                    '+',                       //'+'
                    '',                        //Адрес_подраздела
                    '',                        //Идентификатор_подраздела
                    htmlspecialchars(strip_tags($row->description)), // description
                    '',                        //Минимальный_объем_заказа
                    '',                        //Возможность_поставки
                    '',                        //Срок_поставки
                    '',                        //Способ_упаковки
                    '',                        //Уникальный_идентификатор
                    $row->id,                  //id
                    $row->brandName,           //brand ????
                    'Украина',
                    $cod,                      //cod
                    $priceOpt,
                    $optAmount,
                    '',                        //Оптовая_цена_2
                    '',                        //Оптовый_мин_заказ_2
                    '',                        //Оптовая_цена_3
                    '',                        //Оптовый_мин_заказ_3
                    '',                        //url zalupka
                    '',                        //Метка
                    $url,                      //our url
                    '',                        //Мета_Заголовок
                    '',                        //Мета_Ключевые_слова
                    '',                        //Мета_Описание
                    '',                        //Лучший_товар_в_рубрике
                    '',                        //Только_оптом
                    'опубликован',             //'опубликован'
                    '',                        //Длина
                    '',                        //Ширина
                    '',                        //Высота
                    '',                        //Вес
                    'Производитель',           //'Производитель'
                    $row->brandName,           //brand
                    '',                        //Моя_характеристика_Единица
                );

//                if ($i > 1000) break;
            }
        }

        return $this->products;
    }

}
