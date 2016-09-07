<table width='100%'>
    <tr>
        <td>
            <h2>Блоки товаров<h2>
        </td>
    </tr>
    <tr>
        <td>
            {$pages}
            <table class='tab_all sortable letters-list'>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Описание</th>
                    <th>Url</th>
                    <th>Количество товаров</th>
                </tr>
                {$allLines}
            </table>
        </td>
    </tr>
</table>
<div class='button-add-item'>
    <a href="/?admin=products-blocks&action=add-block">
        <span></span>
        Добавить блок
    </a>
</div>
