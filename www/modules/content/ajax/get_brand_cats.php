<?php
/**
 * Created by PhpStorm.
 * Date: 11/5/15
 * Time: 4:19 PM
 */

namespace Modules;

include('../../../vendor/autoload.php');

$products = filter_input(INPUT_GET, 'products', FILTER_VALIDATE_INT);

$items = new BrandsCategories($products);
echo json_encode($items->getItems());
