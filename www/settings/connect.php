<?php
mysql_connect(
    $glb["db_host"],
    $glb["db_user"],
    $glb["db_password"]
) or die ("Ошибка соединения с базой, проверьте настройки".mysql_error());
mysql_select_db($glb["db_basename"]);
mysql_query("SET NAMES UTF8");
