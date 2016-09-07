<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.16
 * Time: 15:39
 * Переміщення фільтрів кольорів з старої бази на нову.
 */

namespace CMS;

include('../../vendor/autoload.php');

exit;

$oldFilterId = 36;

$sizesFilters = new SizesFilters();
/*
$colorIndex = '';
$colorRealName = '';
echo $sizesFilters->addColorToFilterList($colorIndex, $colorRealName);*/

//витягуєм список всіх імен старого фільтра
$sizesFilters->getSizesFromFilterListByFilterId($oldFilterId);
var_dump($sizesFilters->sizesFromFilterListById);



//витягуєм список всіх фільтрів
$sizesFilters->getColorsFromFilterList();



foreach ($sizesFilters->sizesFromFilterListById as $listId => $listValue) {

    //назва філтра вже існує то удаляєм його і обновляє значення в таблиці filters_values
    if ($sizesFilters->isFilterValueExists($listValue)) {

        echo 'List name - '.$listValue;
        $newListId = array_search($listValue, $sizesFilters->colorsFromFilterList);
        echo '. New list id - '.$newListId.'<br>';
        $sizesFilters->deleteFilterId($listId);
        $sizesFilters->updateFilterListValues($oldFilterId, $listId, $newListId);
//        exit;
    } else {
        echo  $listId;
        echo  'List value not found - '.$listValue.'<br>';
    }
}
