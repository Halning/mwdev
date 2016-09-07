<?

function get_new_links($text)
{
	global $glb;
	
	require_once ('includes/simplehtmldom/simple_html_dom.php');
	$html=str_get_html($text);
	foreach($html->find("a") as $a)
	{
		$href=$a->href;
		if($href[0]=="/")
		{	
			$links[$href]=$href;
			$sqlline.=" OR `url`='{$href}' ";
		}
	}
	
	$sql="
	SELECT * FROM `seo_pages` 
	WHERE `url`='{$glb["request_url_encode"]}' {$sqlline};";
	$res=mysql_query($sql);
	while($row=mysql_fetch_assoc($res))
	{
		$localtype=$glb["request_url_encode"]==$row['url']?$row['type']:$localtype;
		$linkstyle[$row['url']]=$row['type'];
	}
	
	foreach($links as $key=>$value)
	{
		if( ($localtype==1&&$linkstyle[$key]>2) OR ($localtype==2&&$linkstyle[$key]>3)  OR ($localtype==3&&$linkstyle[$key]>3) )
		{
			$text=str_replace("\"{$key}\"","\"#{$key}\"",$text);
			$text=str_replace("'{$key}'","'#{$key}'",$text);		
		}
		
	}
	if($localtype==1)
	{
		foreach($html->find("a") as $a)
		{
			$href=$a->href;
			if($href[0]=="h")
			{	
				$text=str_replace("\"{$href}\"","\"#{$href}\"",$text);
				$text=str_replace("'{$href}'","'#{$href}'",$text);
			}
		}
	}elseif($localtype==5)
	{
		$text=str_replace("index,follow","noindex,nofollow",$text);
	}
	return $text;
}

?>