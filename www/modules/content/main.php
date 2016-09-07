<?php
if (($_SESSION['status'] == "admin") && (isset($_GET["admin"]))) {
    $url_admin["articles"]                  = "modules/content/admin/all_articles.php";
    $url_admin["all_articles_cats"]         = "modules/content/admin/all_categories.php";
    $url_admin["edit_articles_cats"]        = "modules/content/admin/edit_category.php";
    $url_admin["add_articles_category"]     = "modules/content/admin/add_category.php";
    $url_admin["delete_articles_category"]  = "modules/content/admin/delete_category.php";
    $url_admin["all_articles"]              = "modules/content/admin/all_articles.php";
    //тестовая страница дерева категорий
    $url_admin["all_articles_2"]            = "modules/content/admin/all_articles_2.php";
    $url_admin["main_page_edit"]            = "modules/content/admin/main_page_edit.php";
    //тестовая страница всех типов страниц
    $url_admin["all_content_types"]         = "modules/content/admin/all_content_types.php";
    $url_admin["edit_types"]                = "modules/content/admin/edit_types.php";
    $url_admin["delete_fields"]             = "modules/content/admin/delete_fields.php";
    $url_admin["add_articles"]              = "modules/content/admin/add_articles.php";
    $url_admin["article_edit"]              = "modules/content/admin/article_edit.php";
    $url_admin["delete_articles"]           = "modules/content/admin/delete_articles.php";
    $url_admin["article_photos"]            = "modules/content/admin/article_photos.php";
    $url_admin["edit_article_photo"]        = "modules/content/admin/article_photo_edit.php";
    $url_admin["add_article_photo"]         = "modules/content/admin/article_photo_add.php";
    $url_admin["delete_article_photo"]      = "modules/content/admin/article_photo_delete.php";
    $url_admin["enable_cache"]              = "modules/content/admin/enable_cache.php";
    $url_admin_menu["delete_articles"]      = "content";
    $url_admin_menu["articles"]             = "content";
    $url_admin_menu["all_articles"]         = "content";
    $url_admin_menu["all_content_types"]    = "content";
    $url_admin_menu["add_articles"]         = "content";
    $url_admin_menu["article_edit"]         = "content";
    $url_admin_menu["article_photos"]       = "content";
    $url_admin_menu["edit_article_photo"]   = "content";
    $url_admin_menu["add_article_photo"]    = "content";
    $url_admin_menu["delete_article_photo"] = "content";
    require_once("modules/content/admin/functions.php");
    //$cat_tree=get_tree_cat_and_articles();
    $templates->set_tpl('{$content_tree}',
        $url_admin_menu[$_GET["admin"]] == "content" ? $cat_tree : "");
    $glb["templates"]->set_tpl('<!--cl_shop-->',
        ".cl_shop{display:none!important;}");
    $lines                                  = '';
} else if (!isset($_GET['admin'])) {

    require_once("modules/content/site/functionsNew.php");

    $url_page['about-company']      = 'getAboutCompanyPage';
    $url_page['payment-delivery']   = 'getOplataAndDostavkaPage';
    $url_page['articles']           = 'getArticlesPage';
    $url_page['unsubscribe']        = 'unSubscribe';
    $url_page['basket']             = 'getPjaxBasketPage';
    $url_page['myaccount']          = 'getCabinet';
    $url_page['show-confirm-email'] = 'showConfirmEmail';
    $url_page['send-confirm-email'] = 'sendConfirmEmail';
    $url_page['confirmation']       = 'confirmUser';

    $userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
    $userName  = isset($_SESSION['user_loginname']) ? $_SESSION['user_loginname']
            : '';
    $glb["templates"]->set_tpl('{$topMenu}', getTopMenu());
    $glb["templates"]->set_tpl('{$userEmail}', $userEmail);
    $glb["templates"]->set_tpl('{$userName}', $userName);

    // якщо pjax то не викликати дані функції
    if (!filter_input(INPUT_SERVER, 'HTTP_X_PJAX', FILTER_VALIDATE_BOOLEAN)) {
        $glb["templates"]->set_tpl('{$users}', users());
        $glb["templates"]->set_tpl('{$categoriesForSearch}',
            getCategoriesForSearch());
    }

    $glb["templates"]->set_tpl('{$cur_showw}', $glb["cur_name"]);
    $currency = $glb['templates']->get_tpl('currency');
    $glb['templates']->set_tpl('{$currency}', $currency);
    $glb['templates']->set_tpl('{$metaForYaSare}', '');
    $glb["templates"]->set_tpl('{$googleKey}',
        (GOOGLE_API_KEY == 'GOOGLE_API_KEY') ? '' : 'key='.GOOGLE_API_KEY.'&');
}