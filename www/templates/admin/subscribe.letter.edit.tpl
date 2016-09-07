<div>
    <table class='noborder letter-edit' style='margin-left:3px;float:left;border:1px solid #ccc;background:#fff;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;'>
        <tr>
            <td></td>
            <td class='button' id='toolbar-save'>
                <div class='toolbar'  onclick="javascript:document.getElementById('letter-edit-form').submit()">
                    <span class='icon-32-save' title='Сохранить'></span>
                    Сохранить
                </div>
            <td class='button' id='toolbar-cancel'>
                <div class='toolbar' onclick="window.history.back()">
                    <span class='icon-32-cancel' title='Отменить'>&nbsp;</span>
                    Отменить
                </div>
            </td>
            <td class='button' id='toolbar-delete'>
                <div class='toolbar' data-id="{$id}">
                    <span class='icon-32-delete' title='Удалить'>&nbsp;</span>
                    Удалить
                </div>
            </td>
        </tr>
    </table>
    <h1 style='float:left;'> Редактирование письма</h1>
</div>
<table border='0' cellpadding='0' cellspacing='0' width='100%' style='margin-left:5px;'>
    <tr>
        <td>
            <form method='post' action='/?admin=subscribe' name='main_form' id="letter-edit-form">
                <div class='classss'>
                    <input type="hidden" name="id" value="{$id}">
                    <input type="hidden" name="action" value="letter-edit">
                    <br/>
                    Имя:
                    <br/>
                    <input name='name' id='name' style='width: 165px;' class='f_input' type='text' value='{$name}'>
                    <textarea name="letter-content" id='letter-content'>{$letterContent}</textarea>
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