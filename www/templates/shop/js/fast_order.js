$(document).ready(function () {
    var $body = $('body');

    //init tooltip for all fast order
    $(".fast-order").tooltip({
        disabled: true,
        content: "<div class='custom-border-tooltip'>Быстрым заказом можно заказать не более одной единицы!</div>",
        hide: {effect: "explode", duration: 300},
        tooltipClass: "custom-tooltip-styling",
        track: true
    });

    //show tooltip when count of item not one
    $body.on('click', ".fast-order", function () {
        if ($('.product-body').length) {
            if ($(this)
                    .closest('.product-body')
                    .find('.counter-group input')
                    .val() !== '1') {
                $(this)
                        .tooltip("enable")
                        .tooltip("open");
            }
            return false;
        }
        if ($(this)
                .closest('.product__info__bottom')
                .find('.counter-group input')
                .val() !== '1') {
            $(this)
                    .tooltip("enable")
                    .tooltip("open");
        }
    });

    //проверка на валидность полей формы
    $body.on('change', '.fast-order-field', function () {
        var $thisFormGroup = $(this).closest('.form-group'),
                $thisComForm = $(this).closest('.fast-order-form'),
                $btnComForm = $('#fast-order-btn');
        var value = $(this).val(), name = $(this).attr('name');

        if (name === 'fast_order_email') {
            if (value !== '') { // check mail.
                var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
                if (!pattern.test(value)) {
                    $thisFormGroup.addClass('has-error');
                    $thisFormGroup.removeClass('has-success');
                    $btnComForm.attr('disabled', "disabled");
                    return false;
                }
            }
        } else if (name === 'fast_order_phone') {
            var tph = parseInt(value.length);
            if (tph !== 10) {
                $(this).closest('.form-group').addClass('has-error');
                $(this).closest('.form-group').removeClass('has-success');
                $btnComForm.attr('disabled', "disabled");
                return false;
            }
        }

        if (value === "") {
            $thisFormGroup.removeClass('has-success');
            $thisFormGroup.addClass('has-error');
        } else {
            $thisFormGroup.removeClass('has-error');
            $thisFormGroup.addClass('has-success');
        }

        checkNecessaryFieldsFastOrder($btnComForm, $thisComForm);
    });

    //проверяем заполненые поля при наведении мыши на кнопку отправления
    $body.on('mouseenter', '#fast-order-submit', function () {
        var $thisComForm = $(this).closest('.fast-order-form'),
                $btnComForm = $(this).find('#fast-order-btn');
        checkNecessaryFieldsFastOrder($btnComForm, $thisComForm);
    });

    //For block commdity send
    $body.on('click', "#fast-order-btn", function (e) {
        e.preventDefault();
        $(this).attr('disabled', "disabled");
        var $orderWrap = $(this).closest('.order-wrap');
        var
                count = $("#fast-order-count").attr('rel'),
                comId = $("#fast-order-submit").attr("rel"),
                color = $('#fast-order-color span').text(),
                size = $('#fast-order-size span').text(),
                deliveryMethodName = $("#fast-order-delivery-method-styler .jq-selectbox__dropdown li.selected").text();

        $orderWrap.animate({opacity: 0.5}, 500);

        $.ajax({
            url: "/modules/basket_fastOrder/ajax/fastorder_ajax.php",
            type: 'POST',
            dataType: 'JSON',
            data: jQuery(".fast-order-form").serialize() +
                    '&'
                    + $.param({
                        'fast_order_count': count,
                        'fast_order_comid': comId,
                        'fast_order_color': color,
                        'fast_order_size': size,
                        'delivery_method_name': deliveryMethodName
                    })
        }).done(function (data) {
            if (data.success == 1) {
                $('#fastOrder #fast-order-title, #fastOrder form, #fastOrder #fast-order-product').hide();
                $('#fastOrder .custom-border').append(data.finalPage);
                initTooltipCommisionFastOrder();
                $orderWrap.animate({opacity: 1}, 300);
            } else {
                $('#fastOrder #fast-order-title, #fastOrder form, #fastOrder #fast-order-product').hide();
                $('#fastOrder .custom-border').append('<p id="no-size-color" class="text-center">Операция не удалась, попробуйте позже!</p>');
            }
        }).fail(function () {
            $('#fastOrder #fast-order-title, #fastOrder form, #fastOrder #fast-order-product').hide();
            $('#fastOrder .custom-border').append('<p id="no-size-color" class="text-center">Сервер не отвечает!</p>');
        }).always(function () {
            $orderWrap.animate({opacity: 1}, 300);
        });
    });

    //choice of the buyer's country
    $body.on('click', '.fast-order-country', function () {
        var countryVal = $(this).val();
        var properties = {
            'basket': 'selectCountryAndCheckout',
            'country': countryVal};
        selectCountryAndCheckoutFastOrder(properties);
    });

    //слайд добовление коментария в быстром заказе
    $("p#fast-order-comment").click(function () {
        $(this).next().slideToggle();
    });
});



//проверяем заполненые ли обязательные поля для активации кнопки
function checkNecessaryFieldsFastOrder($btnComForm, $thisComForm) {
    $('#fast-order-delivery-method-styler').removeClass('fast-order-necessary fast-order-field');

    //проверяем заполненые поля fast-order
    var sizeEmpty = $thisComForm.find('.fast-order-necessary').size(), i = 0;
    $thisComForm.find('.fast-order-necessary').each(function () {
        if ($(this).val() !== '') {
            i += 1;
        }
    });
    if (i === sizeEmpty) {
        $btnComForm.removeAttr("disabled");
    } else {
        $btnComForm.attr('disabled', "disabled");
    }
}

// Функция Выбор страны
function selectCountryAndCheckoutFastOrder(properties) {
    if (properties.country == 1) {
        $("#fast-order-phone").inputmask({'mask': "+38(999)999-99-99", 'autoUnmask': true});//маска
    } else {
        $("#fast-order-phone").inputmask({'mask': "+7(999)999-99-99", 'autoUnmask': true});//маска
    }
    $.getJSON("/modules/basket_fastOrder/ajax/ajax_basket.php", properties)
            .done(function (data) {
                if (data.error == 0) {
                    $('#fast-order-delivery-method option:not(:first)').remove();
                    jQuery.each(data.delivery, function (id, val) {
                        $("<option></option>", {val: id, text: val}).appendTo($('#fast-order-delivery-method'));
                    });
                    $('#fast-order-delivery-method').trigger('refresh');
                    $('#fast-order-delivery-method').styler('destroy');
                } else {
                    magnificPopupOpenError('Во время выбора страны произошла ошибка! Попробуйте еще раз');
                }
            })
            .fail(function () {
                magnificPopupOpenError('Сервер не отвечает!');
            });
}

// Функция ошибки popup
function magnificPopupOpenError(text) {
    $.magnificPopup.open({
        items: {
            src: '<div id="white-popup"><div class="custom-border-tooltip">' + text + '</div></div>',
            type: 'inline'
        }
    });
}

// Функция инициализации всплывающего окна на комиссии
function initTooltipCommisionFastOrder() {
    $('.fasr-order-commision').tooltip({
        content: "<div class='custom-border-tooltip'>Дополнительные 3% оплачиваються в качестве компенсации затрат на логистику и " +
                "банковских комиссий при осуществлении переводов</div>",
        hide: {effect: "explode", duration: 300},
        //position: {my: "left+15 center", at: "right center"},
        tooltipClass: "custom-tooltip-styling",
        track: true
    });
}