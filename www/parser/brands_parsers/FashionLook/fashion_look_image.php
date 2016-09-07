<?php

namespace Parser;

use Modules\Products;
use Parser\Report\ReportParser;
use Modules\BlobStorage;

require_once('../vendor/autoload.php');
require_once('import_verify_functions.php');

ini_set("max_execution_time", "99999");
set_time_limit(0);

define('TMP_IMG_NAME', 'uploads/fashion-look');
define('CONTAINER', 'fashion-look');

//$exelDoc = new PHPExcelParser(44);
//$exelDoc->writeJsonFile();

//get data from json file
$excelJson = file_get_contents('brands_parsers/FashionLook/data.json');

if ($excelJson) {
    $excelJsonArray = json_decode($excelJson, true);
} else {
    die('Con`t open Json file');
}

//init product
$product = new Products();
$product->order = 0;
$product->brandId = 312;

//get all articles for check on duplicates
$articles = $product->getArticleOfAllProducts();

//get list of all images from blob storage
$blobStorage = new BlobStorage();
$images = $blobStorage->getListBlobsInContainer('fashion-look');

//report
$report = new ReportParser(312, 0, 0, "", 20);
$report->createFileReport();
$report->reportStart();
$report->echoStart(20);
$report->reportEnd();

if ($excelJsonArray) {

    $excelJsonArrayFiltered = array_filter(
        $excelJsonArray,
        function ($item) use ($articles) {
            return in_array($item[1], array(
                'О-105737-3',
                'О-105757-3',
                'О-105762-4',
                'О-9135-1',
                'О-105774-3',
                'О-105775-1',
                'О-105775-3',
                'О-2508-1',
                'О-2517-1',
                'О-13040-1',
                'О-12011-2',
                'О-105798-4',
                'О-105816-5',
                'О-51030-1',
                'О-105867-3',
                'О-105879-1',
                'О-5727-5',
                'О-51194-2',
                'О-105889-3',
                'О-105897-3',
                'О-2068-1',
                'О-31002-6',
                'О-31011-1',
                'О-31016-3',
                'О-51062-2',
                'О-51226-1',
                'О-A0453',
                'О-8255-3',
                'ОЧС-502',
                'ОЧС-5803-3',
                'О-3049-1',
                'О-3059-1',
                'О-9074-2',
                'О-9081-1',
                'О-51097-3',
                'DR3291-4',
                'MM2333-1',
                'О-51080-3',
                'BF520-1',
                'DB1088-4',
                'RB4015-1'
            ));

            // return is_numeric($item[14]) && is_numeric($item[15]);
//            return is_numeric($item[14]) && is_numeric($item[15]) && !in_array($item[1], $articles);
        }
    );

    foreach ($excelJsonArrayFiltered as $excelJsonItem) {

        $product->order++;
        // if ($product->order < 445) continue;
        // if ($product->order >75) continue;
        $product->article = $excelJsonItem[1];


        //перезаливка фото
        $product->getProductByArticle();
        if ($product->id) {
            $product->deleteAdditionalProductImage();
            $blobStorage->deleteAllBlobsInContainer((string)$product->id);
        }

        /*
        $product->name = $excelJsonItem[5] ? $excelJsonItem[5] : $excelJsonItem[3];
        $product->alias = transliterate($product->name) . '_' . transliterate($product->article);
        $product->salePrice = ceil($excelJsonItem[14] * 1.2);
        $product->retailPrice = $excelJsonItem[15];
        $product->sizes = $excelJsonItem[6] ? $excelJsonItem[6] : '';

        //description
        $composition = str_replace('пластик,', 'пластик', $excelJsonItem[11]);
        $composition = str_replace('металл.', 'металл', $composition);
        $description = $excelJsonItem[12];
        $wovels = array('оправа:', 'линза:', 'материал линзы', '. материал оправы');
        $searchArray = array('оправа:', 'линза:', 'материал линзы:', 'материал оправы:');
        $product->description = str_replace('-', '', getDesc($composition, '', $wovels, $searchArray));

        if ('ж' === $excelJsonItem[9]) {
            $product->description .= '<p><span>Пол:</span> женский</p>';
        } elseif (strpos($excelJsonItem[9], 'ni')) {
            $product->description .= '<p><span>Пол:</span> Uni</p>';
        }

        if (strpos($composition, 'олиэст') || strpos($composition, 'олома')) {
            $product->description = '<p><span>Склад:</span> ' . $composition . '</p>';
        }

        if (strpos($description, 'тепень защиты')) {
            $product->description .= '<p><span>Степень защиты:</span>' . strstr($description, ' UV') . '</p>';
        }

        if ($product->sizes) {
            $product->description .= '<p><span>Размер:</span>' . $product->sizes . '</p>';
        }*/

        //images
        $product->additionalImagesIds = array();
        $srcProdArray = array('mainSrcImg' => "", 'dopSrcImg' => "");
        $article = '/' . $excelJsonItem[1] . '/';
        $productImages = array_filter($images, function ($image) use ($article) {
            return preg_match($article, $image);
        });

        if (count($productImages) > 0) {
            $imagesFined = 'Fined';
            $srcProdArray['mainSrcImg'] = TMP_IMG_NAME;
            $imgContent = $blobStorage->getBlob(CONTAINER, reset($productImages));
            file_put_contents(TMP_IMG_NAME, $imgContent);

            //save product
//            $product
//                ->persist()
//                ->addProductToCommodityCategories();


            if ($product->id) {

                //additional photo
                if (count($productImages) > 1) {
                    $i = 0;
                    foreach ($productImages as $key => $productImageAdditional) {

                        $i++;

                        //continue for main photo
                        if (1 === $i) {
                            continue;
                        }

                        //copy image from blob storage to temp file on file system
                        $imgContent = $blobStorage->getBlob(CONTAINER, $productImageAdditional);
                        file_put_contents(TMP_IMG_NAME . '-add' . $i, $imgContent);

                        $srcProdArray['dopSrcImg'][] = TMP_IMG_NAME . '-add' . $i;

                        //add add img ot DB
                        $product->setAdditionalProductImage();
                    }
                }

                $nameImArray = array('title', 's_title', $product->additionalImagesIds);

                cropAndWriteImageBegin($srcProdArray, $product->id, $nameImArray, '', $product->brandId);
            } else {
                $product->id = '<p style="color: red">Error adding product to database</p>';
            }


        } else {
            $imagesFined = '<p style="color: red">images not fined</p>';
        }

        //echo result
        $reportContent = <<<REPORT
            <hr>
<b>Step</b>        - $product->order<br>
<b>Id</b>          - $product->id<br>
<b>Article</b>     - $product->article<br>
<b>Name</b>        - $product->name<br>
<b>SalePrice</b>   - $product->salePrice<br>
<b>RetailPrice</b> - $product->retailPrice<br>
<b>Description</b> - <em>$product->description</em>
<b>Sizes</b>       - $product->sizes<br>
<b>Images</b>      - $imagesFined
REPORT;

        $report->reportStart();

        echo $reportContent;

        $report->reportEnd();
        usleep(500000);
        if ($imagesFined == 'Fined') {
            // break;
        }
    }
}
