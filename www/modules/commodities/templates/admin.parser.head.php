<?php
$all_head = '
    <div id="interface-conteiner">
    <form action="/parser/vir_interface_selected.php" target="_blank" method="post">
        <p><input type="radio" name="visibl" value="1" checked>Опубликованные товары
        <input type="radio" name="visibl" value="0">Неопубликованные товары
        <p><input type="checkbox" name="img" value="true" class="comProp">Перезалить фото
        <input type="checkbox" name="cod" value="true" class="comProp">Перезалить код товара
        <input type="checkbox" name="name" value="true" class="comProp">Перезалить имя товара
        <input type="checkbox" name="desc" value="true" class="comProp">Перезалить описание товара
        <input type="checkbox" name="del" value="true" class="comProp">Скрыть(опубликовю.) или удалить(неопуб.)
        <p>
            <input type="text" name="add_price" value="1" class="comProp" style="width: 40px;">Повысить розничную цену
        </p>
        <div id="deleteCh">Снять выделение</div>
        <p>Выбирите бренды для проверки:</p>
        <input type="checkbox" name="fu" value="1"
                                  checked>Fashion up
        <input type="checkbox" name="sbs" value="4" style="display: none;"
                                  checked><!--SwirlBySwirl-->
        <input type="checkbox" name="car" value="5"
                                  checked>Cardo
        <input type="checkbox" name="gl" value="6"
                                  checked>Glem
        <input type="checkbox" name="len" value="7"
                                  checked>Lenida
        <input type="checkbox" name="sel" value="9"
                                  checked>Sellin
        <input type="checkbox" name="meg" value="10"
                                  checked>Meggi
        <input type="checkbox" name="alva" value="11"
                                  checked>Alva
        <input type="checkbox" name="flf" value="13"
                                  checked>flfashion
        <input type="checkbox" name="skh" value="14"
                                  checked>SK House
        <input type="checkbox" name="sev" value="16"
                                  checked>Seventeen
        <input type="checkbox" name="sl" value="17"
                                  checked>S&L
        <input type="checkbox" name="ols" value="19"
                                  checked>OlisStyle
        <input type="checkbox" name="nec" value="20"
                                  checked>Nelli-Co
        <input type="checkbox" name="fst" value="21" style="display: none;"
                                  checked><!--FStyle-->
        <input type="checkbox" name="arm" value="23"
                                  checked>Art Millano
        <input type="checkbox" name="b1" value="24"
                                  checked>B1
        <input type="checkbox" name="maj" value="25" style="display: none;"
                                  checked><!--Majaly -->
        <input type="checkbox" name="ali" value="27"
                                  checked>Aliya
        <input type="checkbox" name="crs" value="29" style="display: none;"
                                  checked><!--Crisma -->
        <input type="checkbox" name="vit" value="30"
                                  checked>Vitality
        <p><input type="checkbox" name="vik" value="31"
                                  checked>Vitality KIDS
        <input type="checkbox" name="hel" value="32"
                                  checked>Helen Laven
        <input type="checkbox" name="daj" value="33"
                                  checked>Dajs
        <input type="checkbox" name="dem" value="34" style="display: none;"
                                  checked><!--Dembo House -->
        <input type="checkbox" name="jhi" value="35"
                                  checked>Jhiva
        <input type="checkbox" name="zds" value="36"
                                  checked>Zdes
        <input type="checkbox" name="vid" value="38"
                                  checked>Vidoli
        <input type="checkbox" name="laf" value="39"
                                  checked>Lavana Fashion
        <input type="checkbox" name="ref" value="40"
                                  checked>Reform
        <input type="checkbox" name="ttt" value="41"
                                  checked>Tali Ttet
        <input type="checkbox" name="gha" value="43"
                                  checked>Ghazel
        <p><input type="submit" value="Запустить Проверщик" id="goVir"></p>
    </form>
</div>
<tr>
	<th style="width:40px;">#</th>
	<th>Имя бренда</th>
        <th>ID категории</th>
	<th>Дата</th>
	<th style="width:150px;">Ссылки категорий для паука</th>
	<th>Селектор: ссылки на товары</th>
	<th>Список ссылок на товары</th>
        <th>Наличии</th>
	<th>Цена</th>
	<th>Цена ОПТ</th>
	<th>Цвет|Размер</th>
	<th>Заголовок</th>
        <th>Код</th>
	<th>Описание</th>
        <th>Главная картинка</th>
	<th>Доп. фото</th>
	<th>Наценка</th>
	<th class="acty">Запустить парсер</th>
	<th class="acty">Импорт URL</th>
	<th class="acty">Редакт.</th>
	<th class="acty">Удалить</th>
</tr>
';
