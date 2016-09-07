<?php
//header("Content-Type: application/x-javascript; charset=UTF-8");
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//session_destroy();
//ini_set('display_errors',1);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");
bd_session_start();

if(isset($_GET["vote-id"])&&!empty($_GET["score"]))
{
	if(isset($_GET["score"])&&!empty($_GET["score"]))
	{
		$rating = new ratings($_GET["vote-id"], $_GET["score"]);
		$rating->vote();
		
	}
}
class ratings {
	public $com_id =0;
	public $result = 0;
	public $num_votes =0;
	public $rating = 0;

	function __construct($com_id, $result) {
		
		$this->com_id = $com_id;
		$this->result = $result;
		
		$sql = "SELECT  `rating`, `num_votes` FROM `shop_commodity` WHERE `commodity_ID` = {$this->com_id}";
		$res = mysql_query($sql);
		if($row=mysql_fetch_assoc($res)){
			$this->num_votes = $row["num_votes"];
			
			$this->rating  = $row["rating"];
		}
		$this->rating = $this->rating + $this->result;
		$this->num_votes = $this->num_votes+1;
		echo($this->result);
		echo("<br>".$this->rating);
		
	}
	function vote(){
		
		$querya = "UPDATE `shop_commodity` 
		SET `rating` = '{$this->rating}', `num_votes`= '{$this->num_votes}' WHERE `commodity_ID` = '{$this->com_id}';";
		$res = mysql_query($querya);
	}
}
