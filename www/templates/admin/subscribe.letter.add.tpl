<div>
    <table class='noborder letter-add' style='margin-left:3px;float:left;border:1px solid #ccc;background:#fff;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;'>
        <tr>
            <td></td>
            <td class='button' id='toolbar-save'>
                <div class='toolbar' onclick="javascript:document.getElementById('letter-add-form').submit()">
                    <span class='icon-32-save' title='Сохранить'></span>
                    Сохранить
                </div>
            <td class='button' id='toolbar-cancel'>
                <div class='toolbar' onclick="window.history.back()">
                    <span class='icon-32-cancel' title='Отменить'>&nbsp;</span>
                    Отменить
                </div>
            </td>
        </tr>
    </table>
    <h1 style='float:left;'> Создание пысьма</h1>
</div>
<table border='0' cellpadding='0' cellspacing='0' width='100%' class="letter-add">
    <tr>
        <td>
            <form method='post' action='/?admin=subscribe' name='main_form' id="letter-add-form">
                <div class='classss'>
                    <input type="hidden" name="action" value="letter-add">
                    <h3>Имя:</h3>
                    <input name='name' id='name' type='text'>
                    <h3>Шаблон письма:</h3>
                    <select name="template">
                        <option value="empty">Пустой шаблон</option>
                        <option value="base">Makewear - Основной</option>
                        <option value="vitalij">Makewear - Виталик</option>
                    </select>
                    <textarea name="letter-content" id='letter-content'></textarea>
                </div>
            </form>
        </td>
    </tr>
</table>
<script type='text/javascript'>
    jQuery(document).ready(function() {
        CKEDITOR.replace(
                'letter-content',
                {
                    height : '450',
                    language : 'ru'
                }
        );
    });
</script>