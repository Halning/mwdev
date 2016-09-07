<?
/**
* OwerCMS
* Функции пользовательской части модуля управления товарами
* @package OwerCMS
* @author Ower
* @version 0.11.1
* @since engine v.0.11
* @link http://www.ower.com.ua
* @copyright (c) 2010+ by Ower
*/

use Modules\BlobStorage;

if ($_SESSION['status']=="admin")
{
	$_SESSION["lastpage2"]="/?admin=all_commodities";
	if(isset($_GET["commodityID"]))
	{
		$blobStorage = new BlobStorage();

		$commodityID2=$_GET["commodityID"];
		if(isset($_POST["submit"]))
		{	
			$rrrr=explode(",",$commodityID2);
			foreach($rrrr as $key=>$value)
			{
				$commodityID=$value;
				$query="DELETE FROM `shop_commodity` WHERE `commodity_ID`='{$commodityID}';";
				@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());
				$query="DELETE FROM `shop_commodities-categories` WHERE `commodityID`='{$commodityID}';";
				@mysql_query($query) or die ("ошибка запроса ($query)...".mysql_error());
				
				if ($commodityID!=0) {
					removeDirectory("images/commodities/{$commodityID}");
					$blobStorage->deleteContainer((string)$commodityID);
				}
			}
			$center="Товар успешно удален <br><br><a href='/?admin=all_commodities'>Перейти к списку товаров</a>";
			require_once("templates/$theme_name/mess.php"); 

		}else
		{
			$it_name="товар";
			require_once("templates/{$theme_name}/admin.delete.php");
		}
	}
}

?>