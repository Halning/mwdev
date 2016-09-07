<?php
/**
 * Created by PhpStorm.
 * Date: 19.01.16
 * Time: 18:06
 */

namespace Modules;

class Cabinet
{
    private $user;
    private $db;
    private $templates;
    private $curName;
    private $curVal;
    private $brandCatArr = array('1', '2', '3', '15', '16');
    private $comCatArr   = array('8', '9', '22', '26');
    private $watchList;

    public function __construct($id, $templates, $curName, $curVal)
    {
        $this->db   = MySQLi::getInstance()->getConnect();
        $this->user = new User($id, $this->db);

        //для показу кількості в листе наблюдений витягєм інфу з користувача в конструкторі
        $this->user->getData();
        $this->watchList = new WatchPrice($this->user->email);

        $this->templates = $templates;
        $this->templates->set_tpl('{$watchedAmount}',
            count($this->watchList->itemsToCheckFull));
        $this->templates->set_tpl('{$activeMenuMain}', '');
        $this->templates->set_tpl('{$activeMenuProfile}', '');
        $this->templates->set_tpl('{$activeMenuWish}', '');
        $this->templates->set_tpl('{$activeMenuWatch}', '');
        $this->templates->set_tpl('{$activeHistory}', '');
        $this->templates->set_tpl('{$activeConfirmPayment}', '');

        $this->curName = $curName;
        $this->curVal  = $curVal;
    }

    public function main()
    {
        $this->userDataToTemplates();
        $this->templates->set_tpl('{$activeMenuMain}', ' class="active"');
        $this->templates->set_tpl('{$sliderRecomended}',
            getRecomendatedCommodities(array(array_rand($this->brandCatArr), array_rand($this->comCatArr))));
        $this->templates->set_tpl('{$sliderSeeing}',
            getSliderTemplate(getLastViewCommodities()));

        return $this->templates->get_tpl('cabinet.main');
    }

    /**
     *
     * @return html
     */
    public function profile()
    {
        $this->userDataToTemplates();
        if ($this->user->country == 2) {
            $this->templates->set_tpl('{$countryChecked2}', 'checked');
        } else {
            $this->templates->set_tpl('{$countryChecked1}', 'checked');
        }

        $this->templates->set_tpl('{$userBirthDay}', $this->user->birthDay);

        $this->templates->set_tpl('{$activeMenuProfile}', ' class="active"');

        $this->templates->set_tpl('{$curSelect}', $this->curName);

        return $this->templates->get_tpl('cabinet.profil');
    }

    public function wish()
    {
        $lines          = '<p id="empty-wish">СПИСОК ЖЕЛАНИЙ ПУСТ</p>';
        $wishListResult = array();

        if (isset($_SESSION['liked']) && count($_SESSION['liked']) > 0) {
            $wishListIds = implode(',', $_SESSION['liked']);

            $this->templates->set_tpl('{$activeMenuWish}', ' class="active"');
            $query = <<<QUERY1
                SELECT
                  COUNT(com.item_id) comments_count, commodity_ID, cod, commodity_price2, commodity_price,
                  com_name, commodity_order, sc.alias, brand_id, commodity_select, com_sizes,
                  sum(com.comment_rat) rating
	            FROM shop_commodity sc
	            LEFT JOIN comments com ON com.item_id = sc.commodity_ID
	            WHERE commodity_visible='1'
	            AND commodity_ID IN  ({$wishListIds})
                GROUP BY commodity_ID
QUERY1;

            $result = $this->db->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $wishListResult[] = $row;
                }
            }

            $lines = getSliderTemplate($wishListResult);
        }
        $this->templates->set_tpl('{$comBlockWish}', $lines);
        return $this->templates->get_tpl('cabinet.wishes');
    }

    public function watch()
    {
        $watchedItems = '<p id="empty-watch">ЛИСТ НАБЛЮДЕНИЙ ПУСТ</p>';
        $this->templates->set_tpl('{$activeMenuWatch}', ' class="active"');

        if (count($this->watchList->itemsToCheck) > 0) {
            $watchedItemIds = implode(',', $this->watchList->itemsToCheck);

            $result = $this->db->query(<<<QUERY2
            SELECT
              COUNT(com.item_id) commentsAmount, commodity_ID comId, cod, commodity_price2 salePrice,
              commodity_price retailPrice,
              com_name, commodity_order, sc.alias, brand_id, commodity_select, com_sizes,
              sum(com.comment_rat) rating, cat_name brandName
	        FROM shop_commodity sc
	        INNER JOIN shop_categories cat
	          ON brand_id = categories_of_commodities_ID
	        LEFT JOIN comments com
	          ON com.item_id = sc.commodity_ID
	        WHERE commodity_visible='1'
	        AND commodity_ID IN  ({$watchedItemIds})
            GROUP BY commodity_ID
QUERY2
            );

            if ($result && $result->num_rows > 0) {
                $watchedItems = '';
                while ($row          = $result->fetch_object()) {
                    $id    = $row->comId;
                    $alias = $row->alias;
                    $url   = "/product/$id/$alias.html";
                    $src   = PHOTO_DOMAIN."{$id}stitle/{$alias}.jpg";

                    //sizes
                    if ($row->commodity_select) {
                        $sizes = generateSizesColors($row->commodity_select, $id);
                    } else {
                        $sizes = generateSizes($row->com_sizes, $id);
                    }

                    //if price changed
                    if (ceil($this->watchList->itemsToCheckFull[$id][0]) > ceil($row->retailPrice)
                        ||
                        ceil($this->watchList->itemsToCheckFull[$id][1]) > ceil($row->salePrice)
                    ) {
                        $newPrice = <<<HTML1
                            <div class="new-price opacity-1">
                                <p>Новая цена</p>
                                <div class="price-block">
                                    <p>Опт: <span class="bold-text pink">{$row->salePrice} </span>грн; </p>
                                    <p>Розн: <span class="bold-text pink">{$row->retailPrice} </span>грн </p>
                                </div>
                                <div class="add-basket">добавить в корзину</div>
                            </div>
HTML1;
                    } else {
                        $newPrice = <<<HTML2
                            <div class="new-price clearfix">
                                <p>Новая цена</p>
                                <div class="price-block">
                                    <p>Опт: <span class="bold-text">--- </span>грн; </p>
                                    <p>Розн: <span class="bold-text">--- </span>грн </p>
                                </div>
                                <div class="add-basket">добавить в корзину</div>
                            </div>
HTML2;
                    }

                    $rating  = generateRating($row->rating, $row->commentsAmount);
                    $comCode = strlen($row->cod) > 12 ? substr($row->cod, 0, 12)
                            : $row->cod;

                    $this->templates->set_tpl('{$id}', $id);
                    $this->templates->set_tpl('{$salePrice}',
                        $this->watchList->itemsToCheckFull[$id][1]);
                    $this->templates->set_tpl('{$retailPrice}',
                        $this->watchList->itemsToCheckFull[$id][0]);
                    $this->templates->set_tpl('{$newPrice}', $newPrice);
                    $this->templates->set_tpl('{$brandName}', $row->brandName);
                    $this->templates->set_tpl('{$src}', $src);
                    $this->templates->set_tpl('{$url}', $url);
                    $this->templates->set_tpl('{$comCode}', $comCode);
                    $this->templates->set_tpl('{$comName}',
                        getCommodityName($row->com_name, $row->brandName));
                    $this->templates->set_tpl('{$rating}', $rating[0]);
                    $this->templates->set_tpl('{$trueRating}', $rating[1]);
                    $this->templates->set_tpl('{$commentsCount}',
                        generateQuantityOfResponse($row->commentsAmount));
                    $watchedItems .= $this->templates->get_tpl('cabinet.watches.block');
                }
            }
        }
        $this->templates->set_tpl('{$watchedItems}', $watchedItems);

        return $this->templates->get_tpl('cabinet.watches');
    }

    public function history()
    {
        $this->templates->set_tpl('{$activeHistory}', ' class="active"');

        return $this->templates->get_tpl('cabinet.history');
    }

    public function confirmPayment()
    {
        $this->templates->set_tpl('{$activeConfirmPayment}', ' class="active"');

        return $this->templates->get_tpl('cabinet.payment');
    }

    private function userDataToTemplates()
    {
        $this->templates->set_tpl('{$userId}', $this->user->id);
        $this->templates->set_tpl('{$userFirstName}', $this->user->name);
        $this->templates->set_tpl('{$userLastName}', $this->user->realName);
        $this->templates->set_tpl('{$userEmail}', $this->user->email);
        $this->templates->set_tpl('{$userDelivery}', $this->user->delivery);
        $this->templates->set_tpl('{$userWarehouse}', $this->user->warehouse);
        $this->templates->set_tpl('{$userCountry}', $this->user->country);
        $this->templates->set_tpl('{$userCity}', $this->user->city);
        $this->templates->set_tpl('{$userAddress}', $this->user->address);
        $this->templates->set_tpl('{$userPhone}', $this->user->phone);
        $this->templates->set_tpl('{$userSkype}', $this->user->skype);
        $this->templates->set_tpl('{$userSite}', $this->user->site);

        $this->templates->set_tpl('{$discountGift}',
            ceil($this->user->discountGift * $this->curVal));
    }
}