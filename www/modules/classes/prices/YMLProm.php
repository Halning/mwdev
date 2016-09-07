<?php
/**
 * Created by PhpStorm.
 * Date: 24.03.16
 * Time: 17:34
 */

namespace Modules\Prices;

class YMLProm extends YMLAbstract
{
     /**
      * B1
      * Art Millano
      * Meggi
      * Sergio TorrY
      * Jhiva
      * Dajs
      * Sellin
      * Helen Laven
      */
    private $manufacturers = array(
        'Aliya'         => 'Aliya Shoes',
        'Fashion Up'    => 'FashionUp',
        'Olis-style'    => 'Olis Style',
        'FL-Fashion'    => 'The First Land of Fashion',
        'Vitality KIDS' => 'Vitality',
    );

    public function getOffers()
    {
        $result = $this->db->query("
        SELECT
		  DISTINCT `commodity_ID` comId, `cod`, `commodity_price` retailPrice, `commodity_price2` salePrice,
		  `com_fulldesc` description, `com_name` comName, sc.alias comAlias,
		  categoryID catId, brands.cat_name brandName
        FROM `shop_commodity` sc
		INNER JOIN `shop_commodities-categories` scc
		  ON `sc`.`commodity_ID` = `scc`.`commodityID`
		INNER JOIN `shop_categories` cats
		  ON `cats`.`categories_of_commodities_ID` = `scc`.categoryID
		INNER JOIN `shop_categories` brands
		  ON `brands`.`categories_of_commodities_ID` = `sc`.brand_id
		  AND sc.brand_id NOT IN (219, 42)
		WHERE `commodity_visible`='1'
		AND cats.visible = 1
        AND cats.categories_of_commodities_ID NOT IN (26, 27)
		AND cats.categories_of_commodities_parrent IN (
		  264, 209, 212, 213, 261, 211, 266, 267, 210, 268
		)
		GROUP BY commodity_ID
        ORDER BY brand_id
        ");

        $ss = "SELECT id, sum(soc.price) FROM shop_orders so
JOIN shop_orders_coms soc
  ON so.id = soc.offer_id
GROUP by so.id
  ";
        $i = 1;
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $i++;
//                if ($i < 950) continue;
                if (!$row->comName) {
                    continue;
                }

                $offer = $this->xml->createElement('offer');
                $offer->setAttribute('id', $row->comId);
                $offer->setAttribute('available', 'true');

                //провірка чи є оптова ціна, якщо є то товар продається тільки в розницу
                if ($row->salePrice && ceil($row->retailPrice) != ceil($row->salePrice)) {
                    $offer->setAttribute('selling_type', 'u');
                } else {
                    $offer->setAttribute('selling_type', 'r');
                }


                //url
                $alias = preg_replace('/\s+/', '', $row->comAlias);
                $offer->appendChild(
                    $this->xml->createElement('url', self::HOST."product/{$row->comId}/{$alias}.html")
                );

                //retail price
                $offer->appendChild(
                    $this->xml->createElement('price', $row->retailPrice)
                );

                //wholesale price
                if ($row->salePrice && ceil($row->retailPrice) != ceil($row->salePrice)) {
                    $prices = $this->xml->createElement('prices');
                    $salePrice = $this->xml->createElement('price');
                    $salePriceValue = $this->xml->createElement('value', $row->salePrice);
                    $salePriceQuantity = $this->xml->createElement('quantity', 5);
                    $salePrice->appendChild($salePriceValue);
                    $salePrice->appendChild($salePriceQuantity);
                    $prices->appendChild($salePrice);
                    $offer->appendChild($prices);
                }

                //currency
                $offer->appendChild(
                    $this->xml->createElement('currencyId', self::UAH)
                );

                //category
                $offer->appendChild(
                    $this->xml->createElement('categoryId', $row->catId)
                );

                //picture
                $offer->appendChild(
                    $this->xml->createElement(
                        'picture',
                        self::HOST."{$row->comId}btitle/$row->alias.jpg"
                    )
                );

                //vendor
                $brandName = array_key_exists(trim($row->brandName), $this->manufacturers) ?
                    strtr($row->brandName, $this->manufacturers) :
                    trim($row->brandName);
                $offer->appendChild(
                    $this->xml->createElement('vendor', htmlspecialchars(strip_tags($brandName)))
                );

                //cod
                $cod = mb_strlen($row->cod, 'utf-8') > 23 ? substr($row->cod, 0, 23) : $row->cod;
                $offer->appendChild(
                    $this->xml->createElement('vendorCode', htmlspecialchars(strip_tags($cod)))
                );

                //name
                $offer->appendChild(
                    $this->xml->createElement('name', htmlspecialchars($row->comName))
                );

                //description
                $description = htmlspecialchars(strip_tags($row->description));
                $description = str_replace('&nbsp;', '', $description);
                $offer->appendChild(
                    $this->xml->createElement('description', $description)
                );

                //country
                $offer->appendChild(
                    $this->xml->createElement('country', 'Украина')
                );

                $this->offers->appendChild($offer);
//                if ($i > 10) break;
            }
        }

        return $this;
    }
}
