<div>
    <table class='noborder subscriber-edit' style='margin-left:3px;float:left;border:1px solid #ccc;background:#fff;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;'>
        <tr>
            <td></td>
            <td class='button' id='toolbar-save'>
                <div class='toolbar'>
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
                <div class='toolbar' data-id="{$id}" data-type="{$group}">
                    <span class='icon-32-delete' title='Удалить'>&nbsp;</span>
                    Удалить
                </div>
            </td>
        </tr>
    </table>
    <h1 style='float:left;'> Редактирование подписчиков</h1>
</div>
<table border='0' cellpadding='0' cellspacing='0' width='100%' style='margin-left:5px;'>
    <tr>
        <td>
            <form method='post' action='/?admin=subscribers_edit' name='main_form' class="subscribe-edit-form">
                <div class='classss'>
                    <input type="hidden" name="id" value="{$id}">
                    <br/>
                    Имя:
                    <br/>
                    <input name='name' id='name' style='width: 165px;' class='f_input' type='text' value='{$name}'>
                    <br/>
                    <br/>
                    E-Mail:
                    <br/>
                    <input name='email' class='f_input' type='text' style='width: 165px;' value='{$email}'>
                    <br/>
                    <br/>
                    Группа:
                    <br/>
                    <select name="group">
                        <option value="1"{$selected1}>Подписчики</option>
                        <option value="3"{$selected3}>Загружена база</option>
                        <option value="4"{$selected4}>Тестовая база</option>
                    </select>
                    </div>
            </form>
        </td>
    </tr>
</table>