<?php
/**
 * Created by PhpStorm.
 * Date: 24.03.16
 * Time: 17:34
 */

namespace Modules\Prices;

class YMLAllBiz extends YMLAbstract
{
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
		WHERE `commodity_visible`='1'
		AND cats.visible = 1
		AND cats.categories_of_commodities_parrent IN (
		  264, 209, 212, 213, 261, 211, 266, 267, 210, 268
		)
		ORDER BY RAND()
		LIMIT 1000
        ");

        $i = 1;
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $i++;
//                if ($i < 950) continue;
                $offer = $this->xml->createElement('offer');
                $offer->setAttribute('id', $row->comId);
                $offer->setAttribute('available', 'true');

                //url
                $alias = preg_replace('/\s+/', '', $row->comAlias);
                $offer->appendChild(
                    $this->xml->createElement('url', self::HOST."product/{$row->comId}/{$alias}.html")
                );

                //retail price
                $offer->appendChild(
                    $this->xml->createElement('price', ceil($row->retailPrice))
                );

                //wholesale price
                if ($row->salePrice && ceil($row->retailPrice) != ceil($row->salePrice)) {

                    //wholesale price
                    $salePrice = $this->xml->createElement('param', ceil($row->salePrice));
                    $salePrice->setAttribute('name', 'Оптовая цена в нац. валюте');
                    $offer->appendChild($salePrice);

                    //wholesale amount item
                    $amountItemsForSale = $this->xml->createElement('param', '10');
                    $amountItemsForSale->setAttribute('name', 'Мин заказ для опта(шт.)');
                    $offer->appendChild($amountItemsForSale);
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
                $offer->appendChild(
                    $this->xml->createElement('vendor', htmlspecialchars(strip_tags($row->brandName)))
                );

                //cod
                $offer->appendChild(
                    $this->xml->createElement('vendorCode', htmlspecialchars(strip_tags($row->cod)))
                );

                //name
                if ($row->comName) {
                    $offer->appendChild(
                        $this->xml->createElement('name', htmlspecialchars($row->comName))
                    );
                }

                //description
                $description = htmlspecialchars(strip_tags($row->description));
                $description = str_replace('&nbsp;', '', $description);
                $offer->appendChild(
                    $this->xml->createElement('description', $description)
                );

                //country
                $offer->appendChild(
                    $this->xml->createElement('country_of_origin', 'Украина')
                );

                $this->offers->appendChild($offer);
//                if ($i > 1000) break;
            }
        }

        return $this;
    }
}
