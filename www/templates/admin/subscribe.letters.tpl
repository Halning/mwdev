<table width='100%'>
    <tr>
        <td>
            <h2>Письма<h2>
        </td>
    </tr>
    <tr>
        <td>
            {$pages}
            <table class='tab_all sortable letters-list'>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                </tr>
                {$allLines}
            </table>
        </td>
    </tr>
</table>
<div class='button-add-item'>
    <a href="/?admin=subscribe&action=add-letter">
        <span></span>
        Добавить письмо
    </a>
</div>
