<?php
/**
 * Created by PhpStorm.
 * User: webler
 * Date: 05.02.16
 * Time: 0:48
 */

namespace Modules;


class Rating
{
    private $id;
    private $score;
    private $error = false;
    private $table = 'shop_commodity';
    private $target = 'commodity_ID';


    /**
     * @param int $id, commodity or brand id.
     * @param int $score.
     * @param bool $brand, if true then process as brand.
     */
    public function __construct($id, $score, $type)
    {
        $this->id = $id;
        $this->score = $score;
        //switch between commodity and brand.
        if ($type == 'brand') {
            $this->table = "shop_categories";
            $this->target = 'categories_of_commodities_ID';
        }
    }

    /**
     * Adds scores to rating and increment numVotes in DB.
     */
    public function vote()
    {
        $query = "UPDATE {$this->table}
		SET rating = rating + {$this->score}, num_votes = num_votes + 1
		WHERE {$this->target} = {$this->id}";
        $this->error = !mysql_query($query);
    }

    /**
     * @return array - error status, total rating, total quantity of votes.
     */
    public function getRating()
    {
        $query = "
            SELECT  rating, num_votes
            FROM {$this->table}
            WHERE {$this->target} = {$this->id}";
        $res = mysql_query($query);
        if ($row = mysql_fetch_assoc($res)) {
            $numVotes = $row["num_votes"];
            $sumRating  = $row["rating"];
            if ($numVotes > 0) {
                $rating = round($sumRating/$numVotes, 0, PHP_ROUND_HALF_UP);
            } else {
                $rating = 0;
            }
        } else {
            $this->error = true;
        }
        return array(
            'error' => $this->error,
            'rating' => $rating
        );
    }
}