<?php

use Modules\Cache;
use Modules\Cabinet;
use Modules\User;
use Modules\Prices\YMLFactory;

/**
 * Generate main menu - part of html code for main page.
 * Calls in modules/commodities/main.php
 * @return string, list of categories in menu.
 */
function getTopMenu()
{
    global $glb;

    $cache = new Cache('cache/static/main-menu.html', CACHE_TIME_MENU);

    // провіряєм чи включений кеш, чи не застарів, чи доступний файл кешу
    // передаєм true для зчитання файлу global json
    if ($cache->check(true)) {

        //повертаєм закешований файл
        return $cache->fileContent;
    } else {
        $cat_id      = 0;
        $parents     = $orders      = $names       = $aliases     = $titles      = $com_counter
            = array();
        $query       = <<<QUERY1
            SELECT
              categories_of_commodities_ID id, categories_of_commodities_parrent parent_id, title,
              categories_of_commodities_order cat_order, cat_name, alias, rating, num_votes, cat_desc, count_like
            FROM shop_categories
            WHERE visible = 1
	        ORDER BY categories_of_commodities_order
QUERY1;

        $result = $glb['mysqli']->query($query);
        while ($row    = $result->fetch_object()) {
            if ($row->id == 61) {
                continue;
            }
            $cat_id               = $row->id;
            $parents[$row->id]    = $row->parent_id;
            $orders[$row->id]     = $row->cat_order;
            $names[$row->id]      = $row->cat_name;
            $aliases[$row->id]    = $row->alias;
            $titles[$row->id]     = $row->title;
            $trueRating[$row->id] = [
                $row->rating,
                $row->num_votes,
                $row->cat_desc,
                $row->count_like
            ];
        }

        $query = <<<QUERY2
            SELECT
              count(cc.commodityID) quan, categoryID cat_id
            FROM `shop_commodities-categories` cc
            INNER JOIN `shop_commodity` c
              ON c.commodity_ID = cc.commodityID
            WHERE c.commodity_visible = 1
            GROUP BY categoryID
QUERY2;

        //Counting quantity items for every category and subcategory.
        $result             = $glb['mysqli']->query($query);
        while ($quantityProdsInCat = $result->fetch_object()) {
            $com_counter[$quantityProdsInCat->cat_id] = $quantityProdsInCat->quan;
        }
        foreach ($parents as $childCat => $parentCat) {
            $subCategories[$parentCat][$childCat] = $com_counter[$childCat];
        }
        foreach ($subCategories as $parentCategory => $subCategory) {
            $com_counter[$parentCategory] = array_sum($subCategory);
        }

        //Generates list of child categories converted to html cod, for brands.
        $lines = '';
        foreach ($orders as $catId => $order) {
            if ($parents[$catId] == 10) {
                $catName  = $names[$catId] != "" ? $names[$catId] : "нет описания";
                $alias    = $aliases[$catId];
                $url      = $alias != "" ? "/c{$catId}-{$alias}/" : "/c{$catId}/";
                $quantity = is_numeric($com_counter[$catId]) ? $com_counter[$catId]
                        : 0;
                $lines .= "<p><a href='{$url}' data-attr='{$catId}' class='active'>{$catName} ({$quantity})</a></p>";
            }
        }

        $glb['rating']      = $trueRating;
        $glb['cat_names']   = $names;
        $glb['cat_aliases'] = $aliases;
        $glb['cat_parents'] = $parents;
        $glb['com_counter'] = $com_counter;
        $glb['titles']      = $titles;

        //sets variables for template.
        $glb['templates']->set_tpl('{$cat_id}', $cat_id);
        $glb['templates']->set_tpl('{$brands}', $lines);
        $glb['templates']->set_tpl('{$wearWoman}',
            getLinesTopMenu(12, 'енская', $subCategories));
        $glb['templates']->set_tpl('{$wearMan}',
            getLinesTopMenu(12, 'ужская', $subCategories));
        $glb['templates']->set_tpl('{$wearKidsBoys}',
            getLinesTopMenu(12, 'етска', $subCategories));
        $glb['templates']->set_tpl('{$wearKidsGirls}',
            getLinesTopMenu(12, 'етска', $subCategories, 1));
        $glb['templates']->set_tpl('{$shoesWoman}',
            getLinesTopMenu(89, 'енская', $subCategories));
        $glb['templates']->set_tpl('{$shoesMan}',
            getLinesTopMenu(89, 'ужская', $subCategories));
        $glb['templates']->set_tpl('{$shoesKids}',
            getLinesTopMenu(89, 'етска', $subCategories));
        $glb['templates']->set_tpl('{$accessoriesWoman}',
            getLinesTopMenu(84, 'енск', $subCategories));
        $glb['templates']->set_tpl('{$accessoriesMan}',
            getLinesTopMenu(84, 'ужск', $subCategories));
        $glb['templates']->set_tpl('{$accessoriesKids}',
            getLinesTopMenu(84, 'етск', $subCategories));

        $userComment = is_numeric($_SESSION['user_id']) ?
            '<input type="radio" name="reviewGroup" id="remName" checked disabled/>'.
            '<label for="remName">Оставить отзыв как <b>'.$_SESSION['user_realname'].'</b></label>'
                :
            '<input type="radio" name="reviewGroup" id="putName" checked disabled/>
                <label for="putName">
                    <input type="text" placeholder="Ваше имя"/>
                    <input type="email" placeholder="Ваша почта"/>
                </label>';

        $glb['templates']->set_tpl('{$userComment}', $userComment);
        $allLines = $glb['templates']->get_tpl('main.menu');

        //записати кеш, якщо він включений
        if ($cache->isOn()) {
            $cache->writeGlobal()
                ->write('menu', $allLines);
        }

        return $allLines;
    }
}

/**
 * Generates list of child categories converted to html cod, by parent catId.
 * Calls in getTopMenu() function.
 * @param $parentCatId int, id of highest level category.
 * @param $kind string, type categories - man, woman, kids.
 * @param $subCategories array, categies => quantity items in category.
 * @param int $kidsGender, value for kids wears, 0 -boys; 1 - girls.
 * @return string, list of categories in menu.
 */
function getLinesTopMenu($parentCatId, $kind, $subCategories, $kidsGender = 0)
{

    global $glb;

    $lines      = '';
    $kindCatIds = array_keys($glb['cat_parents'], $parentCatId);

    //cycle for every type - man, woman, kids.
    foreach ($kindCatIds as $kindCatId) {

        //if math with specified kind
        if (strstr($glb['cat_names'][$kindCatId], $kind)) {

            //if cattype - kid and wear, use subcategory
            if (strstr('Детская', $kind) && $parentCatId == 12) {
                $kindCatId = array_keys($glb['cat_parents'], $kindCatId)[$kidsGender];
            }

            if (is_array($subCategories[$kindCatId])) {

                //for every child category generate list - html cod.
                foreach ($subCategories[$kindCatId] as $childCatId => $quantity) {
                    $childCatName = $glb['cat_names'][$childCatId] != "" ? $glb['cat_names'][$childCatId]
                            : "нет описания";
                    $alias        = $glb['cat_aliases'][$childCatId];
                    $url          = $alias != "" ? "/c{$childCatId}-{$alias}/" : "/c{$childCatId}/";
                    $quantity     = is_numeric($quantity) ? $quantity : 0;
                    $nonActive    = $quantity == 0 ? ' class="non-active"' : '';
                    $lines .= "<li>
                                <a href='{$url}'{$nonActive}>
                                    {$childCatName}<span> ({$quantity})</span>
                                </a>
                            </li>";
                }
            }

            //resolve bug - when quantity subcategories less then 8 in main menu
            $countSubCategories = count($subCategories[$kindCatId]);
            if ($countSubCategories < 8) {
                while ($countSubCategories < 8) {
                    $lines .= "<li><a href='#'></a></li>";
                    $countSubCategories++;
                }
            }
            break;
        }
    }
    return $lines;
}

/**
 *
 * @global type $glb
 * @return type
 */
function users()
{
    global $glb;

    $userName = mb_strlen($_SESSION["user_loginname"], 'utf8') > 12 ?
        substr($_SESSION["user_loginname"], 0, 12) :
        $_SESSION["user_loginname"];

    if (is_numeric($_SESSION['user_id'])) {
        $lines = <<<HTML1
            <div class="pop-title">
                <a href="/myaccount/profile/" id="username" title="Перейти в личный кабинет">$userName</a>
            </div>
            <div class="pop-title">
                <a href="#" id="s_quit" >Выход</a>
            </div>
HTML1;
    } else {
        $lines = $glb['templates']->get_tpl('main.registration');
    }

    return $lines;
}

/**
 *
 * @global type $glb
 * @return type
 */
function getCategoriesForSearch()
{
    global $glb;

    $parentCategories = array(264, 209, 212, 213, 261, 211, 266, 267, 210, 268);
    $categories       = $categoriesNames  = array();
    $lines            = '';

    foreach ($parentCategories as $parentCategory) {
        $categories = array_merge($categories,
            array_keys($glb['cat_parents'], $parentCategory));
    }

    foreach ($categories as $category) {
        $categoriesNames[$glb['cat_names'][$category]] += $glb['com_counter'][$category];
    }

    foreach ($categoriesNames as $categoryName => $quantity) {
        $lines .= "
<li>
    <a href='#' data-val='{$categoryName}'>{$categoryName}<span> ($quantity)</span></a>
</li>
";
    }
    return $lines;
}

function getContentMessage($message = '<b>404</b> страница не найдена')
{
    global $glb;

    $glb['templates']->set_tpl('{$message}', $message);
    $glb['templates']->set_tpl('{$info}',
        $glb['templates']->get_tpl('main.info'));

    return $glb['templates']->get_tpl('content.message');
}

/**
 * Форма для запиту на емейл користувача для підтвердження реєстрації.
 * @return mixed html
 */
function showConfirmEmail()
{
    global $glb;
    return $glb['templates']->get_tpl('content.confirm.email');
}

/**
 * @return string1
 */
function sendConfirmEmail()
{
    if (is_numeric($_SESSION['confirm_id']) && 'POST' === $_SERVER['REQUEST_METHOD']) {

        global $glb;

        $user  = new User($_SESSION['confirm_id'], $glb['mysqli']);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        if ($email) {

            //добавляє емейл БД
            $user->setEmail($email);
        } else {

            //якщо постом не прийшла емейл адреса, то гетим його з БД.
            if (!$user->getData()->email) {

                //якщо емейл пустий то повертаєм повідомлення про помилку.
                return getContentMessage('Во время регистрации произошла ошибка.');
            }
        }

        $user->createEmailMessageWithConfirmLink('')
            ->sendMail($user::MAIL_THEME_CONFIRM);

        return getContentMessage($user->getMessageToConfirmRegistration());
    }
}

/**
 * Активує аккаунт користувача, після того як він перейшов по лінку з "Активировать учетную запись".
 */
function confirmUser()
{
    global $glb;

    $code   = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
    $userId = filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT);

    if ($userId && $code) {
        $user = new User($userId, $glb['mysqli']);

        // витягує всю інфу про юзера, по ід. з БД.
        $user->getData();

        // якщо хеш співпадає то активуєм.
        if (md5($user->email).md5($user->id) === $code) {

            if ($user->socProvider) {

                //якщо з даним милом вже існує активований користувач, то перекидаєм соц. ід до вже інуючого юзера.
                //логіним юзера, видаляєм всі не активні рядки з даним милом, редіректим юзера в ЛК. Exit.
                if ($user->getUsersByEmail()) {
                    $user->updateExistsUser()
                        ->logIn()
                        ->deleteUserByEmail()
                        ->redirectTo($user::PAGE_CABINET);
                }

                //генеруєм пароль для юзера, активуєм, мейл лист з паролем, відправляєм мило, логіним,
                //видаляєм не активовані рядки з даним мейлом, редіректим в ЛК.
                $user->generatePassword()
                    ->confirm()
                    ->createEmailMessageWithPassword('')
                    ->sendMail($user::MAIL_THEME_PASS)
                    ->logIn()
                    ->deleteUserByEmail()
                    ->redirectTo($user::PAGE_CABINET);
            }

            //підтвердження реєстрації К через форму реєстрації.
            $user->confirm(false)
                ->logIn()
                ->redirectTo($user::PAGE_CABINET);
        }
    }
}

/**
 * Delete string from shop_watch_price by hash.
 * @param int $hash
 * @return string message with result deleting.
 */
function unSubscribe($hash)
{
    global $glb;

    if (!empty($hash) && ctype_digit($hash)) {
        $result = $glb['mysqli']->query(<<<QUERY10
            DELETE FROM shop_watch_price
            WHERE hash = '{$hash}'
QUERY10
        );

        if ($result) {
            return getContentMessage('Товар успешно удален из списка ожидания');
        }
    }

    return getContentMessage('Извините, сейчас мы не можем обработать Ваш запрос');
}

/**
 *
 * @global type $glb
 * @return type html-шаблон
 */
function getAboutCompanyPage()
{
    global $glb;
    return $glb['templates']->get_tpl('content.company');
}

/**
 * 
 * @global type $glb
 * @return type html-шаблон
 */
function getOplataAndDostavkaPage()
{
    global $glb;
    return $glb['templates']->get_tpl('content.oplata.dostavka');
}

/**
 *  Страница статьи в footer
 *
 * @global type $glb
 * @return type html-шаблон
 */
function getArticlesPage()
{
    global $glb;
    return $glb['templates']->get_tpl('content.articles');
}

/**
 * Страница акционные предложения
 * @param null $parameter
 * @return string
 */
function getActionListPage()
{
    global $glb;

    $lines = '';

    $lines = $glb['templates']->get_tpl('content.actions');

    return $lines;
}

/**
 *
 * @global type $glb
 * @param type $parameter
 * @return type
 */
function getCabinet($parameter = null)
{
    global $glb;

    $page = '';

    if (is_numeric($_SESSION['user_id'])) {

        $parameter = 'confirm-payment' === $parameter ? 'confirmPayment' : $parameter;
        $cabinet   = new Cabinet($_SESSION['user_id'], $glb['templates'],
            $glb["cur"][$glb["cur_id"]], $glb["cur_val"]);


        if (method_exists($cabinet, $parameter)) {

            $lines = call_user_func(array($cabinet, $parameter));

            $glb['templates']->set_tpl('{$cabineLeftMenu}',
                $glb['templates']->get_tpl('cabinet.leftMenu'));

            $glb['templates']->set_tpl('{$profileContent}', $lines);

            $glb['templates']->set_tpl('{$info}',
                $glb['templates']->get_tpl('main.info'));

            $salutation = '';
            if (true === $_SESSION['salutation']) {

                unset($_SESSION['salutation']);
                $salutation = '1';
            }

            $glb['templates']->set_tpl('{$salutation}', $salutation);

            $page = $glb['templates']->get_tpl('cabinet.full');
        } else {
            $page = getContentMessage();
        }
    } else {
        User::redirectTo(User::PAGE_MAIN);
    }

    return $page;
}

/**
 * Функія для вигрузки товарів на торгові площадки (Prom, Zalupka, All-biz)
 * @param $parameter string - prom.yml, zalupka.xls ...
 */
function getPricesYML($parameter)
{
    header("Content-type: text/xml; charset=UTF-8");

    $yml = YMLFactory::factory($parameter);

    if ($yml) {
        $yml->getCurrencies()
            ->getCategories()
            ->getOffers()
            ->show();
    }
    /*
     header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="products.xls"');
header('Cache-Control: max-age=0');

$document = new XLSZalupka();
$products = $document->getAllProducts();

$objPHPExcel = new \PHPExcel();
$objPHPExcel->getActiveSheet()->fromArray($products);

$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
      */

    exit;
}
