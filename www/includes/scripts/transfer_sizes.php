<?php
/**
 * Created by PhpStorm.
 * 1. Спочатку витягуєм всі доступні назви розмірів з таблиці filter_list, для майбутньої
 * перевірки чи не появились нові назви розмірів.
 * 2. Потім витягуєм всі розміра з продуктів.
 * 3. Витягуєм всі розміра з фільтрів.
 * 4. Порівнюєм отримані масиви, і результати записуєм в інші
 * масиви для подалого видалення старих чи додавання нових.
 * 5. Добавляєм отримані нові розміра в фільтр.
 * 6. Видаляєм старі не актувальні фільтри.
 * Розміра, які відсутні в списку назв розмірів (табл. filter_lists) - записані
 * в масиві $notEstablishedSizes.
 * Date: 10.02.16
 * Time: 13:33
 */

namespace CMS;

require('../../vendor/autoload.php');

$sizes = new Sizes();

$sizes->getSizesFromFilterList()
    ->getSizesFromShopCommodity()
    ->getSizesFromFilterValues()
    ->compareSizes()
    ->addSizeToFilterValues()
    ->deleteSizesByFilterListId()
    ->showNotEstablishedItems();


//удаляєм фільтри по ід тоавра(не актуальних), які присутні в фільтрах, але відсутні в таблиці товарів
$sizesToDelete = array_keys(array_diff_key($sizes->sizesFromFilterValues, $sizes->sizesFromShopCommodity));
$sizes->deleteSizesByCommodityId($sizesToDelete);

//var_dump($sizes->notEstablishedSizes);
//var_dump($sizes->sizesFromFilterList);
//var_dump($sizes->sizesFromShopCommodity);
//var_dump($sizes->sizesFromFilterValues);
//var_dump($sizes->sizesToAdd);
//var_dump($sizes->sizesToDelete);

exit;

$sizesListToAdd = array(
    '54/48', '40/34', '170', '130', '150', '128/134', '92/98', '116/122', '75B',
    '80B', '70B', '80C', '85B', '70C', '75C', '85C', '90B', '5XL', '158');
foreach ($sizesListToAdd as $item) {
    $sizes->addSizeToFilterList($item);
}
