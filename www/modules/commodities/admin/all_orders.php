<?php

if ($_SESSION['status'] == "admin") {

    $sql = "
	SELECT 	*
	FROM `shop_orders` ORDER BY `date` DESC;
	";
    $result = mysql_query($sql);
    $all_lines = '';
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $sql2 = "SELECT SUM(  `price` ) 
			FROM  `shop_orders_coms` 
			WHERE  `offer_id` = {$row["id"]}
                        AND `com_status` <>2";
            //$price=get_oreder_sum($row["id"]);
            $res2 = mysql_query($sql2);
            if ($row2 = mysql_fetch_assoc($res2))
                $price = $row2["SUM(  `price` )"] + $row['commission'] + $row['delivery_price'];
            if ($row["status"] == 1) {
                $status_of_order = "Новый заказ";
            } elseif ($row["status"] == 2) {
                $status_of_order = "Обрабатывается";
            } elseif ($row["status"] == 3) {
                $status_of_order = "Подтвержден";
            } elseif ($row["status"] == 4) {
                $status_of_order = "оплачен ";
            } elseif ($row["status"] == 5) {
                $status_of_order = "собран";
            } elseif ($row["status"] == 6) {
                $status_of_order = "отправлен клиенту";
            } elseif ($row["status"] == 7) {
                $status_of_order = "закрыт";
            } elseif ($row["status"] == 10) {
                $status_of_order = "Отменен";
            }




            $ccur_show = $glb["cur"][$row["cur_id"]];
            require("modules/commodities/templates/admin.order.all.line.php");
            $all_lines .= $all_line;
        }
    }

    $its_name = "Все заказы <br>
	<b>Выбранные:</b> 
		<span class='cl_delll'>Удалить <img src='/templates/admin/img/btnbar_del.png'></span>
		<span class='cl_edittt'>Редактировать <img src='/templates/admin/img/btnbar_edit.png'></span>;";

    $all_lines .="
	<script>
		 $(document).ready(function() {
			
			
			jQuery('.cl_trt').click(function(){
				jQuery('.cl_delll').show();
				jQuery('.cl_edittt').show();
			});
			jQuery('.cl_delll').click(function(){
				urlid=0;
				jQuery('.cl_trt').each(function()
				{
					if($(this).is(':checked'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				if(urlid!=0)
				{
					location.href='/?admin=delete_order&id='+urlid;
				}
				
			});
			jQuery('.cl_edittt').click(function(){
				urlid=0;
				jQuery('.cl_trt').each(function()
				{
					if($(this).is(':checked'))
					{
						urlid=urlid+','+$(this).attr('rel');
					}
				});
				if(urlid!=0)
				{
					location.href='/?admin=edit_commodity2&commodityID='+urlid;
				}
				
			});
		});
		</script>	";

    $additions_buttons = get_new_buttons("/?admin=admin_add_order_2", "Добавить заказ"); //$_SESSION["category"]
    require("modules/commodities/templates/admin.order.all.head.php");
    require_once("templates/$theme_name/admin.all.php");

//===================Export XLS===============================================	
    if (isset($_GET["exportId"])) {
        $id = $_GET["exportId"];
        require_once 'modules/commodities/admin/import_xls.php';
        //echo "<script>window.open('/?admin=all_orders','_self');</script>";
    }
}
?>
