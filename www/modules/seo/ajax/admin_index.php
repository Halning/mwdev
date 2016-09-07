<?
header("Content-Type: application/x-javascript; charset=UTF-8");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
session_destroy();
//ini_set('display_errors',0);
$urlbb="../../../includes/bbcode/bbcode.lib.php";
require_once("../../../settings/conf.php");
require_once("../../../settings/connect.php");
require_once("../../../settings/functions.php");
require_once ('../../../includes/simplehtmldom/simple_html_dom.php');
// -----------------------------------------------------------------------  СЕССИИ

// --------
	bd_session_start();
	if ($_SESSION['status']=="admin")
	{
		$count=0;
		$sql="SELECT COUNT(*) as `count` FROM `seo_stock` WHERE `use`='0';";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$count=$row["count"];
		}

		if($_GET["start"]==1&&$count==0)
		{
			$sql="DELETE FROM `seo_stock` 
			WHERE `use`='1';";
			mysql_query($sql);
			$sql="DELETE FROM `seo_links`;";
			mysql_query($sql);
			$sql="INSERT INTO `seo_stock` 
			SET `url`='/', `lvl`='1';";
			mysql_query($sql);
			$sql="UPDATE `seo_pages` SET `lvl`='999';";
			mysql_query($sql);
		}
		
		
		$sql="SELECT * FROM `seo_stock` 
		WHERE `use`='0'
		ORDER BY `lvl`
		LIMIT 0,1
		";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$lvl=$row["lvl"];
			$url=$row["url"];
			$lvl2=$lvl+1;
			$total=(substr_count($url, '.jpg')==0)?@file_get_contents("http://{$ass}{$url}"):"<html></html>";
			if(substr_count($total, 'noindex,nofollow')==0&&$total!="")
			{
				$html=str_get_html($total);
				foreach($html->find("a") as $a)
				{
					$url2=$a->href;
					if($url2[0]=="/"&&$url!=$url2)
					{
						
						
						$sql="SELECT * FROM `seo_stock` 
						WHERE `url`='{$url2}';";
						$row=mysql_fetch_assoc(mysql_query($sql));
						if($row)
						{
					
						}else{
							$sql="INSERT INTO `seo_stock` SET `url`='{$url2}', `lvl`='{$lvl2}';";
							mysql_query($sql);
						}

						$sql="SELECT * FROM `seo_links` 
						WHERE `from`='{$url}' AND `to`='{$url2}';;";
						$row=mysql_fetch_assoc(mysql_query($sql));
						if($row)
						{
					
						}else{
							$sql="INSERT INTO `seo_links` SET `from`='{$url}', `to`='{$url2}';";
							mysql_query($sql);
						}
						
						
					}
				}
				
				$sql="SELECT * FROM `seo_pages` 
				WHERE `url`='{$url}';";
				$row=mysql_fetch_assoc(mysql_query($sql));
				if($row)
				{
					$sql="UPDATE `seo_pages` SET  `lvl`='{$lvl}' WHERE `url`='{$url}';";
					mysql_query($sql);
				}else{
					$sql="INSERT INTO `seo_pages` SET `url`='{$url}', `lvl`='{$lvl}';";
					mysql_query($sql);
				}
			}
			
			
	
			$sql="UPDATE `seo_stock` SET `use`='1' WHERE `url`='{$url}';";
			mysql_query($sql);
		}
		
		$sql="SELECT COUNT(*) as `count` FROM `seo_stock` WHERE `use`='0';";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$count=$row["count"];
		}
		$sql="SELECT COUNT(*) as `count` FROM `seo_stock` WHERE `use`='1';";
		$row=mysql_fetch_assoc(mysql_query($sql));
		if($row)
		{
			$count2=$row["count"];
		}		
		if($count==0)
		{
			$sql="DELETE FROM `seo_stock`;";
			mysql_query($sql);

			$sql="
			SELECT * FROM `seo_links`;";
			$res=mysql_query($sql);
			while($row=mysql_fetch_assoc($res))
			{
				$pagefrom[$row["from"]]++;
				$pageto[$row["to"]]++;

			}
			if(count($pageto))
			foreach($pageto as $key=>$value)
			{
				$weight1[$key]=$value/($pagefrom[$key]+1);
			}
			
			$sql="
			SELECT * FROM `seo_links`;";
			$res=mysql_query($sql);
			while($row=mysql_fetch_assoc($res))
			{
				$pageto2[$row["to"]]+=1+1*$weight1[$row["from"]];
			}
			if(count($pageto))
			foreach($pageto as $key=>$value)
			{
				$weight2[$key]=$pageto2[$key]/($pagefrom[$key]+1);
			}			
			
			$sql="
			SELECT * FROM `seo_pages` 
			WHERE `lvl`>0;";
			$res=mysql_query($sql);
			while($row=mysql_fetch_assoc($res))
			{

				$sql="
				UPDATE `seo_pages` 
				SET 
				`in`='{$pageto[$row["url"]]}',
				`out`='{$pagefrom[$row["url"]]}',
				`weight1`='{$weight1[$row["url"]]}',
				`weight2`='{$weight2[$row["url"]]}'
				WHERE `url`='{$row["url"]}';";
				mysql_query($sql);
			}			
			
		}
		echo $_GET['callback']."({ost:'{$count}',index:'{$count2}'})";
	}
	
	

?>