<?php
/**
 * Created by PhpStorm.
 * Date: 16.01.16
 * Time: 20:26
 * Читає, записує, провіряє на наявність файли кешу.
 */


namespace Modules;

/**
 * Class Cache записує кєш.
 * @package Modules
 */
class Cache
{
    /**
     * index для кешу глобальної змінної.
     */
    const GLOBAL_INDEX = 'glb';

    /**
     * назва файлу кешу глобальної змінної. З глобальної змінної записуються тільки деякі ключі масива,
     * rating, cat_names, cat_parents, cat_aliases, com_counter.
     */
    const GLOBAL_FILE = 'cache/static/global.html';

    /**
     * @var bool показує чи включений в налаштування кеш.
     */
    private static $on;

    /**
     * @var int тривалість актуальності файлу кешу в секундах, після чого він буде перезаписуватись.
     */
    private $duration;

    /**
     * @var string назва файлу кешу.
     */
    private $fileName;

    /**
     * @var string html контент кешу.
     */
    public $fileContent;

    /**
     * @var string current currency index (UAH, RUB...)
     */
    public $currency;

    /**
     * @var array список ключів, які потрібно записати з глобальної змінної.
     */
    private $globalVariables = array(
        'rating',
        'cat_names',
        'cat_parents',
        'cat_aliases',
        'com_counter',
        'titles',
    );

    /**
     * Cache constructor.
     * @param string $file
     * @param int    $duration
     */
    public function __construct($file, $duration)
    {
        $this->fileName = $file;
        $this->duration = $duration;
    }

    /**
     * Провіряє, чи включений кеш, чи ще актуальний по часі, чи існує і доступий файл, читає файл.
     * Якщо параметр true, то ще додатково зчитує файл global.
     * @param bool $useGlobal
     * @return bool
     */
    public function check($useGlobal = false)
    {
        if (!$this->isOn()) {
            return false;
        }

        if (!$this->fileExists()) {
            return false;
        }

        if (!$this->isActual()) {
            return false;
        }

        if (!$this->fileContent = $this->getFileContent()) {
            return false;
        }

        if ($useGlobal) {
            if (!$this->getGlobalJsonFileContent()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param bool $on
     */
    public static function setOnOff($on)
    {
        self::$on = $on;
    }

    /**
     * Записує необхідні ключі з глобальнох зміннох в кеш файл в json форматі.
     * @return $this
     */
    public function writeGlobal()
    {
        global $glb;
        $globalJson = json_encode(
            array(
                'rating' => $glb['rating'],
                'cat_names' => $glb['cat_names'],
                'cat_parents' => $glb['cat_parents'],
                'cat_aliases' => $glb['cat_aliases'],
                'com_counter' => $glb['com_counter'],
                'titles' => $glb['titles'],
            )
        );

        $this->write(self::GLOBAL_INDEX, $globalJson);

        return $this;
    }

    /**
     * Повертає true, якщо в налаштуваннях системи включений кеш.
     * @return bool
     */
    public function isOn()
    {
        return self::$on;
    }

    /**
     * Записує контент в файл кеша з назвою відповідною до індекса. Якщо переданий параметр $ids,
     * то добавляє до назви файла ще ids товара чи товарів чи категорій.
     * @param string $index
     * @param string $content
     * @param string $ids
     * @return $this
     */
    public function write($index, $content, $ids = '')
    {
        $translate = array(
            'rec'     => 'slider-recommendations',
            'cat'     => 'cached-catalogue',
            'menu'    => 'main-menu',
            'glb'     => 'global',
            'cur'     => 'currency',
        );

        $fileName = '';

        if (is_array($ids) && count($ids) > 0) {
            foreach ($ids as $id) {
                $fileName .= '_'.$id;
            }
        }

        $separator = strpos($_SERVER['DOCUMENT_ROOT'],'wwwroot') ? '/' : '';
        $cacheFile = 'product' === $index || 'slider' === $index ?
            $_SERVER['DOCUMENT_ROOT'].$separator.$this->fileName :
            $_SERVER['DOCUMENT_ROOT'].$separator."cache/static/{$translate[$index]}{$fileName}.html";
        $cached = fopen($cacheFile, 'w');
        fwrite($cached, $content);
        fclose($cached);

        return $this;
    }

    /**
     * Читає файл кеша global, повертає true і присвоює в глобальну змінну необхідні ключі,
     * якщо вдалось прочитати файл.
     * @return bool
     */
    private function getGlobalJsonFileContent()
    {
        global $glb;

        $globalJson = json_decode(@file_get_contents(self::GLOBAL_FILE), true);

        if (count($globalJson) > 1) {
            foreach ($this->globalVariables as $key) {
                $glb[$key] = $globalJson[$key];
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Повертає true, якщо кеш файл існує.
     * @return bool
     */
    private function fileExists()
    {
        return file_exists($this->fileName);
    }

    /**
     * Повертає контент кеш файла або false.
     * @return string
     */
    private function getFileContent()
    {
        return @file_get_contents($this->fileName);
    }

    /**
     * Повертає true, якщо кеш файл ще актуальний і не застарів.
     * @return bool
     */
    private function isActual()
    {
        return time() - $this->duration < filemtime($this->fileName);
    }
}
