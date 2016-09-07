<?php
//routing
if (isset($_GET["admin"])) {
    $center = "";
    $admin  = $_GET["admin"];
    if (isset($url_admin[$admin])) {
        $admin_menus = $admin_menu[$url_admin_menu[$admin]];
        require_once($url_admin[$admin]);
    } else {
        $center = "";
    }
} elseif (isset($_GET['url'])) {

    $url       = strtolower($_GET['url']);
    $id        = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $parameter = filter_input(INPUT_GET, 'parameter', FILTER_SANITIZE_STRING);
    $page      = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);

    if (function_exists($url_page[$url]) && $parameter && $page) {

//якщо вказана функція існує з параметер and page (напр. product blocks)
        $p       = false === strpos($page, '=') ?
            $page :
            strstr($page, '=', true);
        $perPage = false === strpos($page, '=') ?
            null :
            str_replace('=', '', strstr($page, '='));
        $center  = $url_page[$url]($parameter, $p, $perPage);
    } elseif (function_exists($url_page[$url]) && $parameter) {

//якщо вказана функція існує з параметер (напр. кабінет)
        $center = $url_page[$url]($parameter);
    } elseif (function_exists($url_page[$url]) && $id) {

//якщо вказана функція існує з id
        $center = $url_page[$url]($id);
    } elseif (function_exists($url_page[$url])) {

//якщо вказана функція існує без id
        $center = $url_page[$url]();
    } else {

//Проверка сложной URL
//Для страниц с пейджером
        preg_match('/(.*)([0-9]+?)-([0-9]+?)/Us', $url, $matches);

//Для страниц без пейджера
        preg_match('/(.*)([0-9]+?)/Us', $url, $matches2);

        if ($url_page[$matches[1]] != '' &&
            is_numeric($matches[2]) &&
            is_numeric($matches[3]) &&
            function_exists($url_page[$matches[1]])
        ) {

//если пейджер
            $center = $url_page[$matches[1]](
                $matches[2], $matches[3], isset($_SERVER['HTTP_X_PJAX'])
            );
        } elseif ($url_page[$matches2[1]] != '' &&
            is_numeric($matches2[2]) &&
            function_exists($url_page[$matches2[1]])
        ) {

//если без пейджера
            $center = $url_page[$matches2[1]](
                $matches2[2], false, isset($_SERVER['HTTP_X_PJAX'])
            );
        } elseif (file_exists($url_page[$url])) {

//если указан файл существует
            require_once($url_page[$url]);
        } else {

//404 not found
            header('Status: 404 Not Found');
            $glb['teg_robots'] = true;
            $center            = getContentMessage();
            $super_title       = "404 {$request_url}";
            $headtitle         = "404 {$request_url}";
            $super_description = "404 {$request_url}";
            $super_keywords    = "404 {$request_url}";
            $super_content     = "404 {$request_url}";
        }
    }
} else {

    $_SESSION['last_page'] = '/';

    $glb["templates"]->set_tpl('{$hitBlock}', getCommoditiesForCarusel());
    $glb["templates"]->set_tpl('{$brandsSlider}', getBrandsForCarusel());

    $glb["templates"]->set_tpl('{$info}',
        $glb['templates']->get_tpl('main.info'));

//blocks share on main page
    $blocks    = new \Modules\Blocks();
    $blocks->getBlocks();
    $getBlocks = $blocks->blocks;

    foreach ($getBlocks as $block) {

        if (in_array($block->position,
                array(1, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 14, 15, 16, 17))) {
            if ($block->position >= 10) {
                $glb["templates"]->set_tpl('{$blockLink'.$block->position.'}',
                    '/'.$block->url);
            } else {
                $glb["templates"]->set_tpl('{$blockLink'.$block->position.'}',
                    '/catalog/'.$block->url);
            }

            $glb["templates"]->set_tpl('{$blockName'.$block->position.'}',
                $block->name);
            $glb["templates"]->set_tpl('{$blockTitle'.$block->position.'}',
                $block->title);
            $glb["templates"]->set_tpl('{$blockDay'.$block->position.'}',
                getExpiresTime($block->expires_date, 'day'));
            $glb["templates"]->set_tpl('{$blockHour'.$block->position.'}',
                getExpiresTime($block->expires_date, 'hour'));
            $glb["templates"]->set_tpl('{$visibl'.$block->position.'}',
                $block->visibl);
        }
    }

    $glb["templates"]->set_tpl('{$photoDomain}', PHOTO_DOMAIN);

    $glb["templates"]->set_tpl(
        '{$blockShares}', $glb['templates']->get_tpl('main.blokOfShares')
    );

    $center = $templates->get_tpl("main.center");
}

/**
 * get time left when action end
 *
 * @param string $expDate from db 'shop_block'
 * @param string $daysOrHours
 * @return string 
 */
function getExpiresTime($expDate, $daysOrHours)
{
    $date     = new DateTime($expDate);
    $dateNow  = new DateTime();
    $interval = $dateNow->diff($date);

    if ($daysOrHours === 'day') {
        return generateDeclensionOfResponse($interval->d,
            array('день', 'дня', 'дней'));
    }
    return generateDeclensionOfResponse($interval->h,
        array('час', 'часа', 'часов'));
}

/**
 * міняє слово 'час' 'день' відповідно до кількості - 1 час(день), 2 часа(дня), 6 часов(дней)
 * @param $daysCount int.
 * @return string .
 */
function generateDeclensionOfResponse($daysCount, $sufArr)
{
    $daysCount    = $daysCount ? $daysCount : 0;
    $lastDigit    = substr($daysCount, -1);
    $preLastDigit = strlen($daysCount) > 1 ? substr($daysCount, -2, 1) : 0;
    if ($lastDigit == 1 && $preLastDigit != 1) {
        $suffix = $sufArr[0];
    } elseif (in_array($lastDigit, [2, 3, 4]) && $preLastDigit != 1) {
        $suffix = $sufArr[1];
    } else {
        $suffix = $sufArr[2];
    }
    return $daysCount.' '.$suffix;
}
