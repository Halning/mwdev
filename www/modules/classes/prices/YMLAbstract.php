<?php
/**
 * Created by PhpStorm.
 * Date: 23.03.16
 * Time: 18:24
 */

namespace Modules\Prices;

use Modules\MySQLi;

abstract class YMLAbstract
{
    const HOST = 'http://www.makewear.com.ua/';
    const UAH = 'UAH';

    protected $db;
    protected $date;
    protected $xml;
    protected $currencies;
    protected $catalogue;
    protected $shop;
    protected $categories;
    protected $offers;
    protected $deliverOptions;

    public function __construct()
    {
        $this->db = MySQLi::getInstance()->getConnect();
        $date = new \DateTime('now');
        $this->date = $date->format('Y-m-d H:m');
        $this->xml = new \DOMDocument('1.0', 'UTF-8');
        $implementation = new \DOMImplementation();
        $this->xml->appendChild($implementation->createDocumentType('yml_catalog', '', 'shops.dtd'));
        $this->catalogue = $this->xml->createElement('yml_catalog');
        $this->catalogue->setAttribute('date', $this->date);
        $this->shop = $this->xml->createElement('shop');
        $this->shop->appendChild(
            $this->xml->createElement('name', 'Makewear')
        );
        $this->shop->appendChild(
            $this->xml->createElement('company', 'Makewear')
        );
        $this->shop->appendChild(
            $this->xml->createElement('url', 'http://makewear.com.ua/')
        );
        $this->currencies = $this->xml->createElement('currencies');
        $this->categories = $this->xml->createElement('categories');
        $this->offers = $this->xml->createElement('offers');
        $this->deliverOptions = $this->xml->createElement('delivery-options');
        $deliveryOption = $this->xml->createElement('option');
        $deliveryOption->setAttribute('cost', '30');
        $deliveryOption->setAttribute('dats', '4-3');
        $this->deliverOptions->appendChild($deliveryOption);

    }

    abstract public function getOffers();

    public function getCurrencies()
    {
        $result = $this->db->query("
            SELECT cur_name curName, cur_val curRate
            FROM shop_cur"
        );

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $currency = $this->xml->createElement('currency');
                $currency->setAttribute('id', $row->curName);
                $currency->setAttribute('rate', round(1/floatval($row->curRate), 2));
                $this->currencies->appendChild($currency);
            }
        }

        return $this;
    }

    public function getCategories()
    {
        $result = $this->db->query("
            SELECT
              categories_of_commodities_ID catId, categories_of_commodities_parrent parentId, cat_name catName
            FROM shop_categories
            WHERE visible = 1
            AND categories_of_commodities_parrent <> 10
            AND shop_categories.categories_of_commodities_ID NOT IN (61, 26, 19, 10, 0)
        ");

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $category = $this->xml->createElement('category', $row->catName);
                $category->setAttribute('id', $row->catId);
                if ($row->parentId != 0) {
                    $category->setAttribute('parentId', $row->parentId);
                }
                $this->categories->appendChild($category);
            }
        }

        return $this;
    }

    public function show()
    {
        $this->shop->appendChild($this->currencies);
        $this->shop->appendChild($this->categories);
        $this->shop->appendChild($this->deliverOptions);
        $this->shop->appendChild($this->offers);
        $this->catalogue->appendChild($this->shop);
        $this->xml->appendChild($this->catalogue);

        print $this->xml->saveXML();
    }
}
