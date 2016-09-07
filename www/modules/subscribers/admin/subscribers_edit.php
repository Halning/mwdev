<?php
/**
 * Created by PhpStorm.
 * Date: 29.03.16
 * Time: 11:10
 */

namespace Modules;

global $templates, $center;

$subscribe = new Subscribe();

if ('POST' === $_SERVER['REQUEST_METHOD']) {

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $group = filter_input(INPUT_POST, 'group', FILTER_VALIDATE_INT);
    $store = filter_input(INPUT_POST, 'store', FILTER_SANITIZE_STRING);

    if ($id) {
        $subscribe->updateSubscriber($id, $email, $name, $group);
    }

} elseif ('GET' === $_SERVER['REQUEST_METHOD']) {

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        $subscriber = $subscribe->getSubscriber($id);
        $templates->set_tpl('{$id}', $id);
        $templates->set_tpl('{$email}', $subscriber['email']);
        $templates->set_tpl('{$name}', $subscriber['name']);
        $templates->set_tpl('{$group}', $subscriber['group']);
        $templates->set_tpl('{$store}', $subscriber['store']);
        $templates->set_tpl('{$selected1}', 1 == $subscriber['group'] ? ' selected' : '');
        $templates->set_tpl('{$selected3}', 3 == $subscriber['group'] ? ' selected' : '');
        $templates->set_tpl('{$selected4}', 4 == $subscriber['group'] ? ' selected' : '');

        $center .= $templates->get_tpl('subscribers.edit');
    }
}
