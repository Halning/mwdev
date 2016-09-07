<?php
/**
 * Created by PhpStorm.
 * Date: 07.05.16
 * Time: 22:11
 */

namespace Modules;

global $templates, $center;

if ('admin' === $_SESSION['status']) {
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
        switch ($action) {
            case 'edit-block':
                $block = new Blocks();

                if ('XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH']) {
                    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                    $comId = filter_input(INPUT_POST, 'com_id', FILTER_VALIDATE_INT);

                    if ($comId) {

                        $block->deleteProductFromBlock($comId);

                        if ($id > 0) {
                            $block->addProductToBlock($comId, $id);
                        }
                    }

                    exit;
                }

                break;
        }

    } elseif ('GET' === $_SERVER['REQUEST_METHOD']) {

        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

        switch ($action) {

//            case 'add-letter':
//                $center = $templates->get_tpl('subscribe.letter.add');
//                break;


            default:

                //show products blocks
                $allLines = '';
                $center   = '';

                $blocks   = new Blocks();
                $blocks->getBlocks();

                if (count($blocks->blocks) > 0) {

                    foreach ($blocks->blocks as $block) {
                        $templates->set_tpl('{$id}', $block->id);
                        $templates->set_tpl('{$name}', $block->name);
                        $templates->set_tpl('{$url}', $block->url);
                        $templates->set_tpl('{$amount}', $block->amount);
                        $templates->set_tpl('{$title}', $block->title);
                        $allLines .= $templates->get_tpl('products.blocks.line');
                    }

                    $templates->set_tpl('{$allLines}', $allLines);
                    $templates->set_tpl('{$pages}', '');
                    $center = $templates->get_tpl('products.blocks');
                    break;
                }
        }
    }
}
