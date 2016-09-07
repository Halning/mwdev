<?php
ini_set("max_execution_time", "99999");
set_time_limit(99999);
error_reporting(E_ALL^E_NOTICE);
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");

	if(isset($_GET['stat_c'])){
		$c_status=0;
		$cc=mysql_query("SELECT * FROM `chat_online` WHERE `chat_from`='admin' OR `chat_to`='admin'");
		while($c=mysql_fetch_assoc($cc)){
			$clinet[$c['chat_to']]=array($c['c_status'],$c['messagebox']);
		}
		//var_dump($clinet);
		foreach ($clinet as $key => $val) {
			if($val[0]==1){
				$c_status++;
			}
		}

		if($c_status==0){
		 	$t_status="";
		}else{
		 	$t_status="(".$c_status.")";
		}
		echo $t_status;
	}

	if(isset($_GET['stat_r'])){
		$arr = array();
		$ad=$_GET['admin'];
		$user=$_GET['us'];
		$c_status=0;
		$j=0;

		$opp=mysql_query("SELECT * FROM `chat_operator` WHERE `co_status`=1");
		$op=mysql_fetch_assoc($opp);
		$arr[0]=array("operator_name"=>$op['co_name'],"operator_text"=>$op['co_text'],"st"=>1);

		$cc=mysql_query("SELECT * FROM `chat_online` WHERE `chat_to` IN ('{$ad}','{$user}','admin');");
		while($c=mysql_fetch_assoc($cc)){
			if($ad==$c['chat_from'] || $user==$c['chat_from']){
				$arr[$j]=array("name"=>$c['chat_name'],"user"=> $c['chat_from'],"message"=>$c['messagebox'],"date"=>$c['date'],"operator_name"=>$op['co_name'],"operator_text"=>$op['co_text'],"local"=>$c['link_chat']);

				if($_GET['read']=='read')
					mysql_query("UPDATE `chat_online` SET `c_status`=0 WHERE `chat_to`='{$c['chat_to']}';");
			
				$j++;
			}
		}

		echo json_encode($arr);
	}
	if(isset($_GET['stat_w'])){
		$arr = array();
		$ad=$_GET['admin'];
		$user=$_GET['us'];
		$name=$_GET['name'];
		$name=str_replace(" ","",$name);
		$name=str_replace("/r","",$name);
		$name=str_replace("/t","",$name);

		$link=$_GET['local'];
		$msg=$_GET['msg'];
		$msg=str_replace("\n","<br/>",$msg);

		$stat=intval($_GET['status']);

		$today = date("d.m.Y H:i:s"); 

		mysql_query("INSERT INTO `chat_online`(`chat_name`,`chat_from`, `chat_to`, `messagebox`, `date`,`c_status`, `link_chat`) VALUES ('{$name}','{$ad}','{$user}','{$msg}','{$today}','{$stat}','{$link}');");
		$arr=array("user"=> $ad,"message"=>$msg,"date"=>$today);

		echo json_encode($arr);
	}

	//For site chat
	if(isset($_GET['chat'])){

		echo "Online";
	}

	//For site chat
	if(isset($_GET['operator'])){
		$arr = array();
		$opp=mysql_query("SELECT * FROM `chat_operator` WHERE `co_status`=1");
		$op=mysql_fetch_assoc($opp);
		$arr=array("op_name"=>$op['co_name'], "op_txt"=>$op['co_text']);
		echo json_encode($arr);
	}

	if(isset($_GET['set_name'])){
		$name=$_GET['set_name'];
		mysql_query("INSERT INTO  `chat_operator` (`co_name`) VALUES ('{$name}'); ");
		echo "Add name";
	}
	if(isset($_GET['select_name_id'])){
		$iid=$_GET['select_name_id'];
		mysql_query("UPDATE `chat_operator` SET `co_status`=0 ");
		mysql_query("UPDATE `chat_operator` SET `co_status`=1 WHERE `co_id`='{$iid}' ");

		$co=mysql_query("SELECT * FROM `chat_operator` WHERE `co_id`='{$iid}'");
		$c=mysql_fetch_assoc($co);
		$arr=array("iid"=>$iid, "co_text"=>$c['co_text'],"co_email"=>$c['co_email']);

		echo json_encode($arr);
	}
?>