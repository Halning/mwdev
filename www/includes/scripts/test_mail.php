<?php
/**
 * Created by PhpStorm.
 * Tests mail sending.
 * Date: 15.02.16
 * Time: 18:30
 */
namespace Modules;

include('../../vendor/autoload.php');

define('SEND_GRID_KEY', getenv('SEND_GRID_KEY'));

var_dump(Mail::send(
    'v.chupovsky@makewear.com.ua',
    'test',
    '<p><a href="http://makewear.com.ua">http://makewear.com.ua</a></p>'
));
