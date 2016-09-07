<script type='text/javascript' src='/templates/admin/js/tabs.js'></script>
<div  class="subscribe-send">
    <h2>Отправка рекламной рассылки</h2>
    <form method="POST" action="/?admin=subscribe" id="subscribe-send-form">
        <input type="hidden" name="action" value="send">
        <div class="block-left">
            <h3>Тема:</h3>
            <input name="theme" type="text" maxlength="128">
            <h3>От кого (Имя):</h3>
            <input name="from" type="text" maxlength="30" value="Makewear">
            <h3>От кого (Почта):</h3>
            <input name="from-email" type="email" maxlength="64" value="info@makewear.com.ua">
            <h3>Предпросмотр</h3>
            <div class="pre-view">
                <textarea id="preview1" name="letter-content"></textarea>
                <script type='text/javascript'>
                    jQuery(document).ready(function() {
                        CKEDITOR.replace(
                                'preview1',
                                {
                                    height : '500',
                                    language : 'ru'
                                }
                        );
                        //CKEDITOR.config.readOnly = true;
                    });
                </script>
            </div>
        </div>
        <div class="block-right">
            <div class="template-base">
                <h3>Письмо:</h3>
                <select name="template">
                    <option disabled selected value="">Выберите письмо</option>
                    {$lettersOptions}
                </select>
            </div>
            <div class="submit-send section">
                <ul class="tabs">
                    <li class="current">Тестовое письмо</li>
                    <li>Отправить подписчикам</li>
                </ul>
                <div class="box visible">
                    <input class="test-email" name="test-mail-1" type="email" maxlength="64" placeholder="test emil 1">
                    <input name="test-mail-2" type="email" maxlength="64" placeholder="test emil 2">
                    <input name="test-mail-3" type="email" maxlength="64" placeholder="test emil 3">
                    <button type="button" onclick="validateSendTestEmailsForm(document.getElementById('subscribe-send-form'))">Отправить тестовое письмо</button>
                </div>
                <div class="box">
                    <h3>Клиентская база:</h3>
                    <select id="letter-base" name="letter-base">
                        <option disabled selected value="">Выберите базу подписчиков</option>
                        {$basesOptions}
                    </select>
                    <button type="button" onclick="validateSendSubEmailsForm(document.getElementById('subscribe-send-form'))">Отправить</button>
                </div>
            </div>
        </div>
    </form>
</div>
