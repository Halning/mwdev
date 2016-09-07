<?php
/**
 * Created by PhpStorm.
 * Date: 1/7/16
 * Time: 3:49 PM
 * Використовує глобальні налаштування для з’єднання з базую даних. Повертає об’єкт Mysqli для роботи з БД.
 * Клас написаний по шаблону Singleton.
 */

namespace Modules;

class MySQLi
{
    /**
     * Екземпляр даного класу
     * @var object
     */
    private static $_instance;

    /**
     * Об’єкт mysqli
     * @var \mysqli
     */
    private $mysqli;

    /**
     * Приватний метод. Створює об’єкт mysqli
     * return $this
     */
    private function __construct()
    {
        global $glb;

        require_once($_SERVER['DOCUMENT_ROOT'] . '/settings/conf.php');

        $this->mysqli = new \mysqli($glb['db_host'], $glb['db_user'], $glb['db_password'], $glb['db_basename']);
        if ($this->mysqli->connect_error) {
            die('Connect Error (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
        }
        if (!$this->mysqli->set_charset('utf8')) {
            die("Error loading character set utf8:" . $this->mysqli->error);
        }

        return $this;
    }

    /**
     * Заборонаєм клонування
     */
    private function __clone() {}

    /**
     * Дозволяє сторити лише 1 екземпляр даного класу, повертає створений екземпляр даного класу
     * @return object|MySQLi
     */
    public static function getInstance()
    {
        // проверяем актуальность экземпляра
        if (null === self::$_instance) {
            // создаем новый экземпляр
            self::$_instance = new self();
        }
        // возвращаем созданный или существующий экземпляр
        return self::$_instance;
    }

    /**
     * Гет об’єкт mysqli
     * @return \mysqli
     */
    public function getConnect()
    {
        return $this->mysqli;
    }
}