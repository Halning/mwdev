<div class="row">   
    <form method="post" action="/send-confirm-email/" id="email-form" class="col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-3 col-lg-4 col-md-4 col-sm-4 col-xs-6" role="form">
        <p class="text-center lead">Введите свой Email</p>
        <div class="form-group">
            <input name="email" type="email" placeholder="Email" id="email-field" class="form-control email-necessary">
        </div>    
        <div id="submit">
            <button id="btn-email-form" class="btn btn-lg btn-block btn-primary" disabled="disabled" value="">Отправить</button>   
        </div>
    </form>
</div>
<style type="text/css">
    #email-form {
        margin-top: 150px;
        font-family: "CenturyGothic";
    }
    #email-form button {
        width: 100%;
        background: #5baeb9;
        border: none;
        font-size: 14pt;
        text-transform: uppercase;
        font-weight: 700;
        padding: 15px 0;
        cursor: pointer;
        margin-bottom: 20px;
    }
</style>
<script type="text/javascript">
    (function ($) {
        var $body = $('body');

        //проверяем заполненые поля
        $body.on('change', '#email-field', function () {
            var $thisFormGroup = $(this).closest('.form-group'),
                    $btnComForm = $('#btn-email-form');
            var value = $(this).val();

            if (value !== '') { // check mail.
                var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
                if (!pattern.test(value)) {
                    $thisFormGroup.addClass('has-error');
                    $thisFormGroup.removeClass('has-success');
                    $btnComForm.attr('disabled', "disabled");
                    return false;
                } else {
                    $thisFormGroup.removeClass('has-error');
                    $thisFormGroup.addClass('has-success');
                    $btnComForm.removeAttr("disabled");
                }
            }
        });

        //проверяем заполненые поля при наведении мыши на кнопку отправления
        $body.on('mouseenter', '#submit', function () {
            $('#email-field').trigger('change');
        });

    })(jQuery);
</script>