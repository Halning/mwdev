<?php

if (($_SESSION['status'] == "admin") && (isset($_GET["admin"]))) {
    $admin_top_menu.="
<td class='button' id='toolbar-popup-Popup'>
<a class='modal' href='/?admin=all_commodities'>
<span class='icon-32-shop' title='Магазин'>
</span>
Магазин
</a>
</td>

";
    $admin_left_menu.="
	<li><div class='cl_menu_div'><div class='icon-32-shop cl_left_bt'></div><a href='/?admin=all_articles'>Магазин</a></div>
	<ul>
		<li>
            <a href='/?admin=all_commodities&unset=1'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>
                &nbsp;Товары
            </a>
        </li>
        <li>
            <a href='/?admin=all_categories'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>
                &nbsp;Категории
            </a>
        </li>
        <li>
            <a href='/?admin=all_filters'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>
                &nbsp;Свойства
            </a>
        </li>
        <li>
            <a href='/?admin=commodity_blocks'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>
                &nbsp;Товарные блоки
            </a>
        </li>
        <li style='border-bottom: 1px solid #ccc;padding-bottom: 5px;'>
            <a href='/?admin=download-commodities&type=feeds'>
                <img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>
                &nbsp;Выгрузка товаров
            </a>
        </li>

        <li>
            <a href='/?admin=payment'>
                <img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>
                &nbsp;Поставщики
            </a><br>
        </li>
        <li style='border-bottom: 1px solid #ccc;padding-bottom: 5px;'>
            <a href='/?admin=sozClient'>
                <img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>
                &nbsp;СОЗ Клиенты
            </a>
        </li>
        <li>
            <a href='/?admin=currency_new'>
                <img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>
                &nbsp;Курсы, доставка
            </a><br>
        </li>
        <li><br></li>
	</ul>
	</li>
	<li><div class='cl_menu_div'><a href='/?admin=all_articles'>Парсер</a></div>
		<ul>
		<li><a href='/?admin=parser'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'> Таблица</a><br></li>
		<li><a href='/?admin=parser_interface'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'> Інтерфейс</a><br></li>	
		</ul>
	</li>
	<li><div class='cl_menu_div'><a href='/?admin=all_articles'>Заказы</a></div>
    <ul>
        <li><a href='/?admin=all_orders20'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Заказы</a><br></li>
        <li><a href='/?admin=orders_brands20'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;По брендам</a><br></li>
        <li style='border-bottom: 1px solid #ccc;'><a href='/?admin=orders_by_client20'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;По клиентам</a><br></li>
        <li><a href='/?admin=payment_by_client20'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Оплата - К</a><br></li>     
        <li style='border-bottom: 1px solid #ccc;'><a href='/?admin=orders_to_sup20'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Оплата - П</a><br></li>  
        <li><a href='/?admin=sup_delivery20'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Доставка П -> MW</a><br></li>
        <li><a href='/?admin=client_delivery20'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Доставка MW -> K</a><br></li>
        
    </ul>
    </li>
	<li><div class='cl_menu_div'><a href='/?admin=all_articles'>Архив</a></div>
	<ul>
		<li><a href='/?admin=orders_by_brend_old'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;По брендам</a><br></li>
		<li style='border-bottom: 1px solid #ccc;'><a href='/?admin=orders_by_client_old'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Заказы по клиентам</a><br></li>
		<li><a href='/?admin=payment_by_client_old'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Оплата - клиенты</a><br></li>
		<li style='border-bottom: 1px solid #ccc;'><a href='/?admin=orders_to_sup_old'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Оплата - поставщики</a><br></li>
		<li><a href='/?admin=sup_delivery_old'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Доставка П-MW</a><br></li>
		<li><a href='/?admin=client_delivery_old'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Доставка MW-K</a><br></li>
		<li><a href='/?admin=canceled_by_client'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Отмененные заказы</a><br></li>
	</ul>
	</li>
	<li><div class='cl_menu_div div_singal'><a href='/?admin=online'>Чат <div class='stat_chat'></div></a></div>
    <!--    <ul>
        <li><a href='/?admin=online'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'>&nbsp;Чат <span class='stat_chat'></span></a><br></li>
        </ul>-->
    </li>
	<li><div class='cl_menu_div'><a href='/?admin=all_articles'>Подписаться </a></div>
		<ul>
		    <li><a href='/?admin=subscribe&action=send'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'> Рассилки</a><br></li>
		    <li><a href='/?admin=subscribe&action=letters'><img src='/templates/{$theme_name}/img/list-remove.png' class='ico'> Письма</a><br></li>
		    <li>
		        <a href='/?admin=subscribers'>
		            <img src='/templates/{$theme_name}/img/list-remove.png' class='ico'> Базы
		        </a>
		    </li>
		    <br>
		</ul>
	</li>
";
    $url_commodities = array();
    $url_commodities["all_orders20"]     = "modules/commodities/admin/all_orders20.php";
    $url_commodities["edit_order20"]     = "modules/commodities/admin/edit_order20.php";
    $url_commodities["orders_by_client20"]      = "modules/commodities/admin/orders_by_client20.php";
    $url_commodities["payment_by_client20"]     = "modules/commodities/admin/payment_by_client20.php";
    $url_commodities["sup_delivery20"]          = "modules/commodities/admin/sup_delivery20.php";
    $url_commodities["client_delivery20"]       = "modules/commodities/admin/client_delivery20.php";
    $url_commodities["orders_brands20"]         = "modules/commodities/admin/orders_brands20.php";
    $url_commodities["orders_to_sup20"]         = "modules/commodities/admin/orders_to_sup20.php";

    //Online-Chat
    $url_commodities["online"] = "modules/commodities/admin/online.php";
    $url_commodities["parser"] = "modules/commodities/admin/parser.php";
    $url_commodities["parser_interface"] = "modules/commodities/admin/parser_interface.php";
    $url_commodities["parser_ajax"] = "modules/commodities/admin/parser_ajax.php";
    $url_commodities["delete_parser"] = "modules/commodities/admin/delete_parser.php";
    $url_commodities["edit_parser"] = "modules/commodities/admin/edit_parser.php";
    $url_commodities["all_orders"] = "modules/commodities/admin/all_orders.php";
    $url_commodities["delete_order"] = "modules/commodities/admin/delete_order.php";
    $url_commodities["edit_order"] = "modules/commodities/admin/edit_order.php";
    $url_commodities["edit_vendor"] = "modules/commodities/admin/edit_vendor.php";
    $url_commodities["add_vendor"] = "modules/commodities/admin/add_vendor.php";
    $url_commodities["delete_vendor"] = "modules/commodities/admin/delete_vendor.php";
    $url_commodities["shop"] = "modules/commodities/admin/all_commodities.php";
    $url_commodities["currency_new"] = "modules/commodities/admin/currency_new.php";
    $url_commodities["update_from_csv"] = "modules/commodities/admin/update_from_csv.php";
    $url_commodities["all_commodities"] = "modules/commodities/admin/all_commodities.php";
    $url_commodities["add_commodity"] = "modules/commodities/admin/add_commodity.php";
    $url_commodities["edit_commodity"] = "modules/commodities/admin/edit_commodity.php";
    $url_commodities["edit_commodity2"] = "modules/commodities/admin/edit_commodity2.php";
    $url_commodities["delete_commodity"] = "modules/commodities/admin/delete_commodity.php";
    $url_commodities["enabled_commodities"] = "modules/commodities/admin/enabled_commodities.php";
    $url_commodities["all_modifycation"] = "modules/commodities/admin/all_modifycation.php";
    $url_commodities["all_categories"] = "modules/commodities/admin/all_categories.php";
    $url_commodities["add_category"] = "modules/commodities/admin/add_category.php";
    $url_commodities["edit_category"] = "modules/commodities/admin/edit_category.php";
    $url_commodities["delete_category"] = "modules/commodities/admin/delete_category.php";
    $url_commodities["commodity_photos"] = "modules/commodities/admin/commodity_photos.php";
    $url_commodities["edit_commodity_photo"] = "modules/commodities/admin/commodity_photo_edit.php";
    $url_commodities["add_commodity_photo"] = "modules/commodities/admin/commodity_photo_add.php";
    $url_commodities["delete_commodity_photo"] = "modules/commodities/admin/commodity_photo_delete.php";
    $url_commodities["commodity_photos2"] = "modules/commodities/admin/commodity_photo2.php";
    $url_commodities["edit_commodity_photo2"] = "modules/commodities/admin/commodity_photo2_edit.php";
    $url_commodities["add_commodity_photo2"] = "modules/commodities/admin/commodity_photo2_add.php";
    $url_commodities["delete_commodity_photo2"] = "modules/commodities/admin/commodity_photo2_delete.php";
    $url_commodities["all_recommendation"] = "modules/commodities/admin/all_recommendation.php";
    $url_commodities["delete_recommendation"] = "modules/commodities/admin/delete_recommendation.php";
    $url_commodities["add_recommendation"] = "modules/commodities/admin/add_recommendation.php";
    $url_commodities["all_action"] = "modules/commodities/admin/all_action.php";
    $url_commodities["delete_action"] = "modules/commodities/admin/delete_action.php";
    $url_commodities["add_action"] = "modules/commodities/admin/add_action.php";
    $url_commodities["all_discount"] = "modules/commodities/admin/all_discount.php";
    $url_commodities["delete_discount"] = "modules/commodities/admin/delete_discount.php";
    $url_commodities["add_discount"] = "modules/commodities/admin/add_discount.php";
    $url_commodities["edit_discount"] = "modules/commodities/admin/edit_discount.php";
    $url_commodities["all_discount2"] = "modules/commodities/admin/all_discount2.php";
    $url_commodities["delete_discount2"] = "modules/commodities/admin/delete_discount2.php";
    $url_commodities["add_discount2"] = "modules/commodities/admin/add_discount2.php";
    $url_commodities["edit_discount2"] = "modules/commodities/admin/edit_discount2.php";
    $url_commodities["edit_filters"] = "modules/commodities/admin/edit_filters.php";
    $url_commodities["add_filters"] = "modules/commodities/admin/add_filters.php";
    $url_commodities["all_filters"] = "modules/commodities/admin/all_filters.php";
//    $url_commodities["maneger_list"] = "modules/commodities/admin/maneger_list.php";
    $url_commodities["all_color"] = "modules/commodities/admin/all_color.php";
    $url_commodities["delete_color"] = "modules/commodities/admin/delete_color.php";
    $url_commodities["add_color"] = "modules/commodities/admin/add_color.php";
    $url_commodities["edit_color"] = "modules/commodities/admin/edit_color.php";
    $url_commodities["all_size"] = "modules/commodities/admin/all_size.php";
    $url_commodities["delete_size"] = "modules/commodities/admin/delete_size.php";
    $url_commodities["add_size"] = "modules/commodities/admin/add_size.php";
    $url_commodities["edit_size"] = "modules/commodities/admin/edit_size.php";
    $url_commodities["delete_filter_list"] = "modules/commodities/admin/delete_filter_list.php";
    $url_commodities["delete_filter"] = "modules/commodities/admin/delete_filter.php";
    $url_commodities["orders_brands"] = "modules/commodities/admin/orders_brands.php";
    $url_commodities["orders_brands_new"] = "modules/commodities/admin/orders_brands_new.php";
    $url_commodities["delete_order_com"] = "modules/commodities/admin/delete_order_com.php";
    $url_commodities["sup_group"] = "modules/commodities/admin/sup_group.php";
    $url_commodities["degroup"] = "modules/commodities/admin/degroup.php";
    $url_commodities["add_order_com"] = "modules/commodities/admin/add_order_com.php";
    $url_commodities["add_status_com"] = "modules/commodities/admin/add_status_com.php";
    $url_commodities["add_status_group"] = "modules/commodities/admin/add_status_group.php";
    $url_commodities["mail_to_sup"] = "modules/commodities/admin/mail_to_sup.php";
    $url_commodities["orders_to_sup"] = "modules/commodities/admin/orders_to_sup.php";
    $url_commodities["orders_by_client"] = "modules/commodities/admin/orders_by_client.php";
    $url_commodities["payment_by_client"] = "modules/commodities/admin/payment_by_client.php";
    $url_commodities["sup_delivery"] = "modules/commodities/admin/sup_delivery.php";
    $url_commodities["client_delivery"] = "modules/commodities/admin/client_delivery.php";
    $url_commodities["mail_to_customer"] = "modules/commodities/admin/mail_to_customer.php";
    $url_commodities["add_order_status"] = "modules/commodities/admin/add_order_status.php";
    $url_commodities["mail_to_sup2"] = "modules/commodities/admin/mail_to_sup2.php";
    $url_commodities["orders_by_brend_old"] = "modules/commodities/admin/orders_by_brend_old.php";
    $url_commodities["payment_by_client_old"] = "modules/commodities/admin/payment_by_client_old.php";
    $url_commodities["orders_by_client_old"] = "modules/commodities/admin/orders_by_client_old.php";
    $url_commodities["orders_to_sup_old"] = "modules/commodities/admin/orders_to_sup_old.php";
    $url_commodities["sup_delivery_old"] = "modules/commodities/admin/sup_delivery_old.php";
    $url_commodities["client_delivery_old"] = "modules/commodities/admin/client_delivery_old.php";
    $url_commodities["admin_add_order_2"] = "modules/commodities/admin/admin_add_order_2.php";
    $url_commodities["canceled_by_client"] = "modules/commodities/admin/canceled_by_client.php";
    $url_commodities["fun_ajax"] = "modules/commodities/admin/fun_ajax.php";
    $url_commodities["mail_payment"] = "modules/commodities/admin/mail.payment.php";
    $url_commodities["subscribe"]               = "modules/subscribers/admin/subscribe.php";
    $url_commodities["subscribers"]             = "modules/subscribers/admin/subscribers.php";
    $url_commodities["subscribers_edit"]        = "modules/subscribers/admin/subscribers_edit.php";
    $url_commodities["download-commodities"]    = "modules/commodities/admin/download_commodities.php";
    $url_commodities["products-blocks"]         = "modules/commodities/admin/product_blocks.php";
    $url_commodities["sozClient"]               = "modules/commodities/admin/sozClient.php";
    $url_commodities["cardClient"]              = "modules/commodities/admin/cardClient.php";
    $url_commodities["payment"]                 = "modules/commodities/admin/payment.php";
    $url_commodities["brenda_contact"]          = "modules/commodities/admin/brenda_contact.php";
    $url_commodities["commodity_blocks"]        = "modules/commodities/admin/commodity_blocks.php";
    $url_commodities["commodity_blocks_edit"]   = "modules/commodities/admin/commodity_blocks_edit.php";
    
    foreach ($url_commodities as $key => $value) {
        $url_admin[$key] = $value;
        $url_admin_menu[$key] = "commodities";
    }
    require_once("modules/commodities/admin/functions.php");
    $glb["templates"]->set_tpl('<!--cl_shop-->', ".cl_shop{display:block;}.tabs li.cl_shop{display:inline;}");
    $cat_tree = get_admin_categories_tree();
    $templates->set_tpl('{$content_tree_com}', $url_admin_menu[$_GET["admin"]] == "commodities" ? $cat_tree : "");
} else if (!isset($_GET['admin'])) {
    
    require_once('modules/commodities/site/functionsNew.php');

    $url_page['search'] = 'getSearch';
    $url_page['c'] = 'getCategory';
    $url_page['product'] = 'getCommodityFull';
    $url_page['subscribe'] = 'modules/subscribers/ajax/controller.php';
}
