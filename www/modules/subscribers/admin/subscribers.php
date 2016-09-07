<?php
/**
 * Created by PhpStorm.
 * Date: 26.03.16
 * Time: 21:20
 */

namespace Modules;

if ('admin' === $_SESSION['status']) {

    $subscribe = new Subscribe();

    //init page number
    if (filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT) &&
        !filter_input(INPUT_POST, 'subscribers-per-page', FILTER_VALIDATE_INT) &&
        !filter_input(INPUT_POST, 'subscribers-filter-group', FILTER_VALIDATE_INT)
    ) {
        $subscribe->currentPage = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT);
    }

    //init filter by user type (group)
    if (filter_input(INPUT_POST, 'subscribers-filter-group', FILTER_SANITIZE_STRING)) {
        $subscribe->filterGroup = filter_input(INPUT_POST, 'subscribers-filter-group', FILTER_SANITIZE_STRING);
    } elseif (filter_input(INPUT_GET, 'group', FILTER_VALIDATE_INT)) {
        $subscribe->filterGroup = filter_input(INPUT_GET, 'group', FILTER_VALIDATE_INT);
    }

    //per page
    if (filter_input(INPUT_POST, 'subscribers-per-page', FILTER_VALIDATE_INT)) {
        $subscribe->subscribersPerPage = filter_input(INPUT_POST, 'subscribers-per-page', FILTER_VALIDATE_INT);
    } elseif (filter_input(INPUT_GET, 'per-page', FILTER_VALIDATE_INT)) {
        $subscribe->subscribersPerPage = filter_input(INPUT_GET, 'per-page', FILTER_VALIDATE_INT);
    }

    $subscribe->getSubscribers();

    //amount of pages
    $all_lines .= $subscribe->generatePages();

    //select for filter
    $groupFilterSelect = $subscribe->getFilters();

    foreach ($subscribe->subscribers as $subscriber) {
        $editLink = 2 == $subscriber->sub_type || 7 == $subscriber->sub_type ? '' :
            "<a href='/?admin=subscribers_edit&id={$subscriber->sub_id}'>Редактировать</a>&nbsp;";
        include(__DIR__ . '/../templates/admin.subscriber.line.php');
    }

    include(__DIR__ . '/../templates/admin.subscribers.php');
}
