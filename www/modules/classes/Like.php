<?php
/**
 * Created by PhpStorm.
 * User: webler
 * Date: 02.02.16
 * Time: 17:50
 */

namespace Modules;


class Like
{
    private $success = false;
    private $id;
    private $parameters;
    private $counter;
    private $visitorAlreadyPutsLike;
    private $commodityParameters = array(
        'table' => 'shop_commodity',
        'id' => 'commodity_ID'
    );
    private $brandParameters = array(
        'table' => 'shop_categories',
        'id' => 'categories_of_commodities_ID'
    );

    /**
     * @param $id int, brand or commodity id.
     * @param $type string, 'c' - commodity, 'b' - brand.
     */
    public function __construct($id, $type)
    {
        $this->id = $id;
        if ($type == 'b') {
            $this->parameters = $this->brandParameters;
        } elseif ($type == 'c') {
            $this->parameters = $this->commodityParameters;
        }
        $this->visitorAlreadyPutsLike = $this->checkLiked();
    }

    /**
     * @return bool, true if user already puts like on current item.
     */
    private function checkLiked()
    {
        if (isset($_SESSION['liked'])) {
            if (in_array($this->id, $_SESSION['liked'])) {
                return true;
            } else {
                return false;
            }
        } else {
            $_SESSION['liked'] = array();
        }
    }

    /**
     * Puts or delete id of current item from SESSIONS, call metod setCounter.
     */
    public function putLike()
    {
        $operator = $this->visitorAlreadyPutsLike ? '-' : '+';
        $this->success = $this->setCounter($operator);
        if ($this->success) {
            if ($this->visitorAlreadyPutsLike) {
                //удаляем id категории с массива liked;
                $_SESSION['liked'] = array_diff($_SESSION['liked'], array($this->id));
            } else {
                $_SESSION['liked'][] = $this->id;
            }
        }
    }

    /**
     * Gets quantity of likes of current item from DB.
     * @return array with success, count, active status.
     */
    public function getCounter()
    {
        $query = "SELECT count_like FROM {$this->parameters['table']}
		WHERE {$this->parameters['id']} = '{$this->id}'
		LIMIT 1";
        $res = mysql_query($query);
        if ($row = mysql_fetch_assoc($res)) {
            $this->counter = $row['count_like'];
            $this->success = 1;
        }

        return array(
            'success' => (int) $this->success,
            'count' => $this->counter,
            'active' => $this->visitorAlreadyPutsLike
        );
    }

    /**
     * Adds or minuses quantity of likes of current item in DB.
     * @param $operator string, + or -.
     * @return bool.
     */
    private function setCounter($operator)
    {
        $query = "UPDATE {$this->parameters['table']}
		SET count_like = count_like{$operator}1
		WHERE {$this->parameters['id']} = {$this->id}";
        return mysql_query($query);
    }
}