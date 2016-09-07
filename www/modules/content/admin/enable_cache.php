<?php
/**
 * Created by PhpStorm.
 * Date: 12/7/15
 * Time: 1:57 PM
 */

namespace Modules;

$event = filter_input(INPUT_GET, 'event', FILTER_SANITIZE_STRING);
if (
    $event &&
    in_array($event, array('disable', 'enable', 'clear')) &&
    $_SESSION['status'] == 'admin'
) {
    $mysqli = MySQLI::getInstance()->getConnect();
    if ($event == 'clear') {

        $cacheFiles = scandir('cache/static');
        $ignoredFiles = array('.', '..', '.gitignore');
        $quantity = 0;

        foreach ($cacheFiles as $key => $cacheFile) {
            if (is_file('cache/static/' . $cacheFile) && !in_array($cacheFile, $ignoredFiles)) {
                unlink('cache/static/' . $cacheFile);
                $quantity = $key;
            }
        }

        $cacheFiles = scandir('cache/static/products');
        $ignoredFiles = array('.', '..', 'ignore.txt');

        foreach ($cacheFiles as $key => $cacheFile) {
            if (is_file('cache/static/products/' . $cacheFile) && !in_array($cacheFile, $ignoredFiles)) {
                unlink('cache/static/products/' . $cacheFile);
                $quantity++;
            }
        }

        $message = "Кэш удален! {$quantity} файлов удалено";
    } else {
        $value = $event == 'enable' ? 1 : 0;
        $sql = "UPDATE  domens SET  enable_cache = {$value}";
        $result = $mysqli->query($sql);
        $mysqli->close();
        if ($result) {
            $message = $value ?
                'Кэш включен!' :
                'Кэш отключен!';
        } else {
            $message = 'Произошла ошыбка!';
        }
    }
    $center = "<h1>$message</h1>";
}