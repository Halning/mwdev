<?php
/**
 * Created by PhpStorm.
 * User: webler
 * Date: 07.05.16
 * Time: 22:34
 */

namespace Modules;

/**
 * Class Blocks Блоки товаров.
 * @package Modules
 */
class Blocks
{

    public $blocks = array();
    private $db;

    /**
     * Blocks constructor.
     */
    public function __construct()
    {
        $this->db = MySQLi::getInstance()->getConnect();
    }

    /**
     * Вибір всіх блоків товарів з БД.
     * @return $this
     */
    public function getBlocks()
    {
        $query = <<<QUERYGB
        SELECT
          id, name, url, count(com_id) amount, title, position, expires_date, visibl
        FROM shop_blocks sb
        LEFT JOIN shop_blocks_products sbp
          ON sbp.block_id = sb.id
        GROUP BY id
QUERYGB;

        $result = $this->db->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
                $this->blocks[] = $row;
            }
        }

        return $this;
    }

    /**
     * Видалення товару по ід з таблиці shop_blocks_products
     * @param $id
     * @return $this
     */
    public function deleteProductFromBlock($id)
    {
        $this->db->query(<<<QUERYDPFB
            DELETE FROM shop_blocks_products
            WHERE com_id = $id
QUERYDPFB
        );

        return $this;
    }

    /**
     * Добавляє товар в тпбл. shop_blocks_products.
     * @param $comId
     * @param $blockId
     * @return $this
     */
    public function addProductToBlock($comId, $blockId)
    {
        $query = <<<QUERYAPTB
            INSERT INTO shop_blocks_products (
              com_id, block_id
            ) VALUES (
              $comId, $blockId
            )
QUERYAPTB;

        $this->db->query($query);

        return $this;
    }
}
