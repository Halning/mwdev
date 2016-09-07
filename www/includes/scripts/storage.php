<?php
/**
 * Created by PhpStorm.
 * Date: 10.05.16
 * Time: 18:24
 */

require_once '../../vendor/autoload.php';

define('BLOB_STORAGE', getenv('BLOB_STORAGE'));

use Modules\MySQLi;

set_time_limit(0);

$blob = new \Modules\BlobStorage();

//foreach ($blob->getListAllContainers()->getContainers() as $key => $value) {
//    $blob->deleteContainer($value->getName());
//}

//$blob->createContainer('email-letters');

// 1 - шлях до файла, 2 - назва файла як він буде назв. в сторейджі, 3 - назва папки в сторейджі
$blob->uploadBlob(
    'fashion_look.jpg',
    'actions9.jpg',
    'banners'
);
// 1 - шлях до файла, 2 - назва файла як він буде назв. в сторейджі, 3 - назва папки в сторейджі
$blob->uploadBlob(
    'ghazel.jpg',
    'actions10.jpg',
    'banners'
);
die;
// 1 - шлях до файла, 2 - назва файла як він буде назв. в сторейджі, 3 - назва папки в сторейджі
$blob->uploadBlob(
    '3333.jpg',
    'actions3.jpg',
    'banners'
);
// 1 - шлях до файла, 2 - назва файла як він буде назв. в сторейджі, 3 - назва папки в сторейджі
$blob->uploadBlob(
    '4444.jpg',
    'actions4.jpg',
    'banners'
);


//var_dump($blob->getBlob('fashion-look', ''));
//$blob->deleteBlob('email-letters', 'sp3.html');
var_dump($blob->getListBlobsInContainer('33506'));
//$blob->setBlobCacheControl('11132', 'title.jpg');
//$blob->deleteContainer('container');
//var_dump($blob->isContainer('email-letters'));
//$blob->deleteAllBlobsInContainer('container1');

/*
$db   = MySQLi::getInstance()->getConnect();

$result = $db->query(<<<QUERY
    SELECT DISTINCT commodity_ID comId
    FROM shop_commodity
    WHERE commodity_visible = 1
QUERY
);

if ($result && $result->num_rows > 0) {
    $containers = array();

    while ($row = $result->fetch_object()) {
        $containers[] = $row->comId;
    }

    foreach ($containers as $key => $container) {

        if ($key < 176) continue;

        $blobsInContainer = $blob->getListBlobsInContainer((string)$container);

        if (count($blobsInContainer > 1)) {

            echo "--- $key ---<br>";

            foreach ($blobsInContainer as $blobInContainer) {
                $blob->setBlobCacheControl((string)$container, $blobInContainer);

                echo $container.' - '.$blobInContainer.'<br>';

                usleep(20000);
            }

            echo '<hr>';
            flush();
            ob_flush();
        }

//        if ($key > 10) break;
    }
} else {
    echo 'MySQLi Error!';
}
*/
/*$images = scandir('../../parser/uploads/images_fl');
foreach ($images as $key => $image) {

    if ('.' == $image || '..' == $image) {
        continue;
    }

    $blob->uploadBlob(
        '../../parser/uploads/images_fl/'.$image,
        $image,
        'fashion-look'
    );
    echo $key.'<br>';
    flush();
    ob_flush();
}*/
