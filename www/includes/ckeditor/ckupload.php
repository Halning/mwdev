<? 
	require_once("../../settings/conf.php");
	$urlbb="../../includes/bbcode/bbcode.lib.php";
	mysql_connect($glb["db_host"],$glb["db_user"],$glb["db_password"]) or die ("Ошибка соединения с базой, проверьте настройки".mysql_error());
	mysql_select_db($glb["db_basename"]);
	mysql_query("SET NAMES UTF8");
	require_once("../../settings/functions.php");
	bd_session_start(); 
global $site_root, $version, $site_root, $server_site_root, $file_site_root;
		
$site_root = "";

$file_site_root = $_SERVER['DOCUMENT_ROOT']."/".$site_root;
//$file_site_root = $_SERVER['DOCUMENT_ROOT']."/";
$server_site_root = "http://".$_SERVER["HTTP_HOST"]."/".$site_root;
$server_site_root="";
ini_set("include_dir", ini_get("include_dir").":".$file_site_root);

$version = "0.10";

$file = time()."_".$_FILES['upload']['name'];
if($_SESSION["upload_dir"]!="")
{
	$url = "../../{$_SESSION["upload_dir"]}/".$file;
	$dest="/{$_SESSION["upload_dir"]}/".$file;
}else
{
	$url = '../../uploads/'.$file;
	$dest='/uploads/'.$file;
	
}
$dir=str_replace("/".$file,"",$url);
$dir2=str_replace("/".$file,"",$dest);
 //extensive suitability check before doing anything with the file...
    if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name'])) )
    {
       $message = "No file uploaded.";
    }
    else if ($_FILES['upload']["size"] == 0)
    {
       $message = "The file is of zero length.";
    }
  
    else if (!is_uploaded_file($_FILES['upload']["tmp_name"]))
    {
       $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
    }
    else {
      $message = "";
      
      
      	if($glb["use_ftp"])
	{
		$ftp_conn=ftp_connect($gallery_domen);
		$ftp_log=ftplogin($ftp_conn);
		
	}
	
      		if(is_dir($dir)==false)
		{
			if($glb["use_ftp"])
			{
				ftp_mkdir($ftp_conn,$parrent_dir.$dir2);
			}else
			{
				mkdir($dir);
			}
		}
		
	if($glb["use_ftp"])
	{
		ftp_put($ftp_conn,$parrent_dir."/".$dest,$_FILES['upload']['tmp_name'], FTP_BINARY);
	}else
	{
		$move = move_uploaded_file($_FILES['upload']['tmp_name'], $url);
		if(!$move)
		{
			$message = "{$url} (permissions)";
		}
	}
      $url = $server_site_root.$dest;
    }
 
$funcNum = $_GET['CKEditorFuncNum'] ;
echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
?>