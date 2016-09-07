var google;


$(document).ready(function () {
    var $pjaxCabCont = $('#pjax-profile-content');

    //open actionpopup  if user entry first time
    if (1 == $pjaxCabCont.data('salut')) {
        openDiscountGift();
    }

    //init tooltip for autocomplite city
    $("#autocomplete_city_profile").tooltip({
        disabled: true,
        content: "<div class='custom-border-tooltip'>Выбирете город из списка!&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>",
        hide: {effect: "explode", duration: 300},
        tooltipClass: "custom-tooltip-styling",
        track: true
    });

    //left-menu for all
    $('#profile-conteiner-full .left-menu button').click(function () {
        $('#profile-conteiner-full .left-menu ul').toggle('slide', {direction: 'up'}, 500);
        var windowWidth = $(window).width();
        if (windowWidth <= 850) {
            $('.content_block article:first-child:not(.sunken)').animate({'margin-top': '+=250px'}, 500, function () {
                $(this).addClass('sunken');
            });
            $('.content_block article.sunken').animate({'margin-top': '-=250px'}, 500, function () {
                $(this).removeClass('sunken');
            });
        }
    });

    //INIT depends for location
    //init all depends location
    if (window.location.pathname.match('\/profile')) {
        var autocompleteCityProfile = new google.maps.places.Autocomplete(document.getElementById('autocomplete_city_profile'), {language: 'ru'}),
                autocompleteAddressProfile = new google.maps.places.Autocomplete(document.getElementById('autocom_adres_profile'), {language: 'ru'}),
                $profileWar = $('#profile-warehouse'),
                $profileDelMet = $("#profile-delivery-method");
        //init country radio and styler and google
        $("#select-country-radio-profile").buttonset();
        $profileDelMet.styler({singleSelectzIndex: 1});
        $('#profile-warehouse').styler({singleSelectzIndex: 1});
        setTimeout(function () {
            if ($profileDelMet.data('delivery') === 3 && $('#select-country-radio-profile input:checked').val() === '1') {
                $('#autocomplete_city_profile').trigger('blur');
            } else {
                $('#profile-warehouse-styler').hide();
                $('#write_warehouse_profile').removeClass('hidden');
            }
            if ($profileWar.data('placeholder') === '') {
                $profileWar.data('placeholder', 'Номер склада и улица *');
            }
        }, 1);

    } else if (window.location.pathname.match('\/wish')) {
        //init hover for commodity-one
        $(".profile-wishes-container .product").each(function () {
            var
                    $self = $(this),
                    colHeight = $self.find(".product__info__bottom").find(".big-column").height(),
                    bigCol = $self.find(".product__info__bottom").find(".big-column");
            bigCol.attr("data-height", colHeight);
            $self.find(".product__info__bottom").find(".column").height("0");
            $self.on({
                mouseenter: function () {
                    var thisColumnData = bigCol.attr("data-height");
                    var thisColumn = $self.find(".product__info__bottom").find(".column");
                    thisColumn.css({opacity: "1"}).height(thisColumnData);
                    $self.addClass("hovered");
                },
                mouseleave: function () {
                    $self.find(".product__info__bottom").find(".column").css({opacity: "0"}).height("0");
                    $self.removeClass("hovered");
                    $(".product").css("overflow", "hidden");
                }
            });
        });
    }

    //PROFILE-PROFILE
    //choice of the buyer's country
    $pjaxCabCont.on('change', '#select-country-radio-profile', function () {
        var countryVal = $('#select-country-radio-profile input:checked').val();
        if (countryVal === '1') {
            $("#profile-phone").inputmask({'mask': "+38(999)999-99-99", 'autoUnmask': true});//маска
            var restrictions = {country: 'ua'};
        } else {
            $("#profile-phone").inputmask({'mask': "+7(999)999-99-99", 'autoUnmask': true});//маска
            var restrictions = {country: 'ru'};
        }
        $('#profile-delivery-method').data('placeholder', 'Выберете перевозчик');
        $('#profile-warehouse-styler').hide();
        $("#write_warehouse_profile").removeClass('hidden');
        autocompleteCityProfile.setComponentRestrictions(restrictions);
        autocompleteCityProfile.setTypes(['(cities)']);
        autocompleteAddressProfile.setComponentRestrictions(restrictions);
        autocompleteAddressProfile.setTypes(['address']);
        var properties = {
            'basket': 'selectCountryAndCheckout',
            'country': countryVal};
        selectCountryAndCheckoutProfile(properties);
    });
    $('#select-country-radio-profile').trigger('change');

    //зміна пароля
    $('.profile-profil-container').on('submit', '#change-password-form', function (e) {
        $('#password-btn').attr('disabled', "disabled");
        var _this = $(this);
        $.post(
                '/modules/users/ajax/user.php',
                'action=change-password&' + _this.serialize()
                )
                .done(function (data) {
                    data = JSON.parse(data);
                    if (data.success === 1) {
                        _this.html('');
                        _this.prepend($('<span>', {
                            class: 'success-message',
                            text: data.message,
                            css: {opacity: 1}
                        }).fadeIn(1500));
                        _this.closest('.inform_block')
                                .css({'padding': '23px 0 23px'});
                    } else {
                        _this.find('input')
                                .first()
                                .val('')
                                .focus();
                        $('#password-btn').removeAttr("disabled");
                    }
                    magnificPopupOpenError(data.message);
                })
                .fail(function () {
                    magnificPopupOpenError('Извините, сейчас мы не можем обработать Ваш запрос');
                });
        return false;
    });

    //проверка на валидность полей формы
    $pjaxCabCont.on('change', '.password-field', function () {
        var
                $self = $(this),
                $thisComForm = $self.closest('#change-password-form'),
                $btnComForm = $('#password-btn'),
                $newPas = $('#change-password-form input[name="new-password"]'),
                $conNewPas = $('#change-password-form input[name="confirm-new-password"]');

        //проверяем заполненые поля password-necessary
        var sizeField = $('.password-necessary').size(), i = 0;
        $('.password-necessary').each(function () {
            if ($(this).val() !== '') {
                i += 1;
            }
        });
        if (i !== sizeField) {
            $newPas.closest('.form-group')
                    .removeClass('has-error, has-success');
            $conNewPas.closest('.form-group')
                    .removeClass('has-error, has-success');
            return false;
        }

        if ($conNewPas.val() === $newPas.val() && $newPas.val() !== "" && $conNewPas.val() !== '') {
            $newPas.closest('.form-group')
                    .removeClass('has-error')
                    .addClass('has-success');
            $conNewPas.closest('.form-group').removeClass('has-error')
                    .addClass('has-success');
            checkNecessaryFieldsPassword($btnComForm, $thisComForm);
        } else {
            $newPas.closest('.form-group').addClass('has-error')
                    .removeClass('has-success');
            $conNewPas.closest('.form-group').addClass('has-error')
                    .removeClass('has-success');
            $btnComForm.attr('disabled', "disabled");
        }
    });

    //проверяем заполненые поля при наведении мыши на кнопку отправления
    $pjaxCabCont.on('mouseenter', '#profil-submit', function () {
        var
                $thisComForm = $(this).closest('#change-password-form'),
                $btnComForm = $(this).find('#password-btn'),
                $newPas = $('#change-password-form input[name="new-password"]'),
                $conNewPas = $('#change-password-form input[name="confirm-new-password"]');

        if ($conNewPas.val() === $newPas.val() && $newPas.val() !== "") {
            checkNecessaryFieldsPassword($btnComForm, $thisComForm);
        } else {
            $btnComForm.attr('disabled', "disabled");
        }
    });

    //update user field
    $pjaxCabCont.on('change', '.input_block input:not([type="password"],#autocomplete_city_profile, #autocom_adres_profile), #profile-delivery-method, #profile-warehouse', function () {
        var
                $self = $(this),
                value = $self.val(),
                name = $self.attr('name');

        if (name === 'delivery') {
            deliveryMethod(value);
        }

        $self.closest('.form-group').addClass('has-success');
        if (name === 'phone') {
            var tph = parseInt(value.length);
            if (tph !== 10) {
                $self
                        .closest('.form-group')
                        .addClass('has-error')
                        .removeClass('has-success');
                return false;
            }
        }

        updateUser(value, name);
    });

    //check for filling the form fields google autocomplete cities
    $pjaxCabCont.on('blur', '#autocomplete_city_profile', function () {
        var countryVal = $('#select-country-radio-profile input:checked').val();
        setTimeout(function () {
            var $self = $('#autocomplete_city_profile');
            var userCityInput = $self.val().replace('Україна', 'Украина');
            var checkCountry = (countryVal === '1') ? userCityInput.replace('Україна', 'Украина').indexOf('Украина') : userCityInput.indexOf('Россия');
            if (checkCountry === -1) {
                $self
                        .tooltip("enable")
                        .tooltip("open")
                        .closest('.form-group')
                        .addClass('has-error')
                        .removeClass('has-success');
            } else {
                $self
                        .tooltip("close")
                        .tooltip("disable")
                        .closest('.form-group')
                        .removeClass('has-error')
                        .addClass('has-success');
                updateUser(userCityInput, 'city');
                getNovaPostaProfile();
            }
        }, 100);
    });

    //check for filling the form fields google autocomplete Streets
    $pjaxCabCont.on('blur', '#autocom_adres_profile', function () {
        var $self = $('#autocom_adres_profile');
        setTimeout(function () {
            var
                    value = $self.val(),
                    name = $self.attr('name');

            $self.closest('.form-group').addClass('has-success');
            updateUser(value, name);
        }, 100);
    });

    //PROFILE-WISHES
    //PROFILE-MAIN

    //PROFILE-WATCHES
    //delete button
    $('.profile-watches-container').on(
            'click',
            '.observations-product .observations-close-btn',
            function () {
                var $block = $(this).closest('.observations-product'),
                        $wathcedAmount = $('#profile-conteiner-full .left-menu #watch-menu-item>span'),
                        $contentBlock = $(this).closest('.content_block');
                $.post(
                        '/modules/commodities/ajax/watch_price.php',
                        {
                            id: $block.find('.brand-info .rating-small')
                                    .data('id'),
                            email: $('.write-us-form .row__input>input').val(),
                            event: 'del'
                        }
                )
                        .done(function (data) {
                            data = JSON.parse(data);
                            if (data.success === 1) {
                                $wathcedAmount.text(parseInt($wathcedAmount.text()) - 1);
                                //magnificPopupOpenError('Удалено из листа наблюдений');
                                $block.remove();
                                if (0 === $contentBlock.find('.observations-product').length) {
                                    $contentBlock.append('<p id="empty-watch">ЛИСТ НАБЛЮДЕНИЙ ПУСТ</p>');
                                }
                            }
                        });
            });

    //init slider
    $('.profil-slider').slick({
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 5,
        //variableWidth: true,
        autoplaySpeed: 3000,
        dots: true,
        responsive: [
            {
                breakpoint: 1234,
                settings: {
                    slidesToScroll: 4,
                    variableWidth: true,
                    infinite: true
                }
            },
            {
                breakpoint: 1050,
                settings: {
                    centerMode: true,
                    variableWidth: true,
                    infinite: true
                }
            },
            {
                breakpoint: 850,
                settings: {
                    variableWidth: true,
                    centerMode: true,
                    infinite: true
                }
            },
            {
                breakpoint: 650,
                settings: {
                    variableWidth: true,
                    centerMode: true,
                    dots: false
                }
            },
            {
                breakpoint: 480,
                settings: {
                    variableWidth: true,
                    centerMode: true,
                    dots: false
                }
            }
        ]
    });



    (function ($) {
        $.fn.rte = function () {
            var that = this;
            this.find('th').each(function (index) {
                index++;
                that.find('tr td:nth-child(' + index + ')').attr('data-title', that.find('th:nth-child(' + index + ')').text());
            });
        };
    })(jQuery);

    $('.rte').rte();

    $('.slider').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    infinite: true,
                    dots: false
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }]
    });
    $(".tab_item").not(":first").hide();
    $(".wrapper .tab").click(function () {
        $(".wrapper .tab").removeClass("active").eq($(this).index()).addClass("active");
        $(".tab_item").hide().eq($(this).index()).fadeIn();
    }).eq(0).addClass("active");

    $('.social-vkl').click(function (e) {
        $(this).find('i').toggle();
        $(this).find('img').toggleClass('opacity-2');
        $(this).toggleClass('bg-color');
        return false;
    });

    $('.rte-first').show();
    $('.click-arrow').click(function (e) {
        $('.table-block').find('.slide-block').slideToggle();
        $('.table-block').find('.rte-first').show();
        $('.see-more-link').toggle();
    });

    $('.rte-second').hide();
    $('.see-more-link').click(function () {
        $('.rte-second').slideToggle();
    });

    $('.click-arrow').click(function () {
        $(this).find('.gray-arrow-down').toggle();
        $(this).find('.gray-arrow-up').toggle();
        return false;
    });

    $('.see-more-link').click(function () {
        $('.see-more-1').toggle();
        $('.see-more-2').toggle();
    });

    $('.item_hov').click(function (e) {
        $(this).find('.arrow-down').toggle();
        $(this).find('.arrow-up').toggle();
        $(this).find('.hide_hov').slideToggle();
        $(this).toggleClass('white-color');
        return false;
    });

    (function ($) {
        $.fn.rte = function () {
            var that = this;
            this.find('th').each(function (index) {
                index++;
                that.find('tr td:nth-child(' + index + ')').attr('data-title', that.find('th:nth-child(' + index + ')').text());
            });
        };
    })(jQuery);

    //FUNCTIONS
    //PROFILE-PROFILE
    /**
     * Вся работат с Api NovaPosta
     *
     * @returns {Boolean}
     */
    function  getNovaPostaProfile() {
        var $autocompleteCity = $('#autocomplete_city_profile'),
                userCityInput = $autocompleteCity.val().replace('Україна', 'Украина');
        var userCityArr = userCityInput.split(','),
                userRegionArr = userCityArr[1].trim().split(' ');
        var userCity = userCityArr[0].trim(),
                userRegion = userRegionArr[0].trim();
        getWarehouseNovaPosta({
            'basket': 'getWarehouseNovaPosta',
            'userCity': userCity,
            'userRegion': userRegion
        });
    }

    /**
     * Функция выбора отделений для новой почты
     *
     * @param {object} properties
     * @returns {undefined} json from php
     */
    function getWarehouseNovaPosta(properties) {
        var $autocomWarehouse = $('#profile-warehouse'),
                $writeWarehouse = $("#write_warehouse_profile");
        $.getJSON("/modules/basket_fastOrder/ajax/ajax_basket.php", properties)
                .done(function (data) {
                    if (data.error === 0) {
                        $('#profile-warehouse option:not(:first)').remove();
                        jQuery.each(data.warehouses, function (id, val) {
                            $("<option></option>", {val: val.DescriptionRu, text: val.DescriptionRu}).appendTo($autocomWarehouse);
                        });
                        $autocomWarehouse.trigger('refresh').data('placeholder', 'Номер склада и улица *');
                        $autocomWarehouse.styler('destroy');
                        $('#write_warehouse_profile').addClass('hidden');
                        $autocomWarehouse.show(500);
                    } else {
                        magnificPopupOpenError('В вашем городе не найдены отделения, введите отделение в ручную!');
                        $('#profile-warehouse-styler').hide();
                        $writeWarehouse.removeClass('hidden');
                    }
                })
                .fail(function () {
                    magnificPopupOpenError('Сервер не отвечает!');
                });
    }
});

//FUNCTIONS
/**
 *
 * @param {type} value
 * @param {type} name
 * @returns {undefined}
 */
function updateUser(value, name) {
    $.post(
            '/modules/users/ajax/user.php',
            {
                action: 'update-field',
                name: name,
                value: value
            }
    );
}

// Функция Выбор страны
function selectCountryAndCheckoutProfile(properties) {
    var $cabDevMet = $('#profile-delivery-method');
    $.getJSON("/modules/basket_fastOrder/ajax/ajax_basket.php", properties)
            .done(function (data) {
                if (data.error === 0) {
                    $('#profile-delivery-method option:not(:first)').remove();
                    jQuery.each(data.delivery, function (id, val) {
                        $("<option></option>", {val: id, text: val}).appendTo($cabDevMet);
                        if (id == $cabDevMet.data('delivery')) {
                            $cabDevMet.data('placeholder', val);
                        }
                    });
                    $cabDevMet.trigger('refresh');
                    $cabDevMet.styler('destroy');
                } else {
                    magnificPopupOpenError('Во время выбора страны произошла ошибка! Попробуйте еще раз');
                }
            })
            .fail(function () {
                magnificPopupOpenError('Сервер не отвечает!');
            });
}

//проверяем заполненые ли обязательные поля для активации кнопки
function checkNecessaryFieldsPassword($btnComForm, $thisComForm) {

    //проверяем заполненые поля password-necessary
    var sizeEmpty = $thisComForm.find('.password-necessary').size(), i = 0;
    $thisComForm.find('.password-necessary').each(function () {
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

/**
 * choise deleveri Method
 *
 * @param {string} idDeltvery
 * @returns {undefined}
 */
function deliveryMethod(idDeltvery) {
    switch (idDeltvery) {
        case '':
            return false;
            break;
        case '3':
            $('#autocomplete_city_profile').trigger('blur');
            $('#profile-warehouse').data('placeholder', 'Номер склада и улица *');
            break;
        default:
            $('#profile-warehouse-styler').hide();
            $("#write_warehouse_profile").removeClass('hidden');
            break;
    }
}

/**
 * open add gift to balance
 *
 * @returns {undefined}
 */
function openDiscountGift() {
    $('.open-popup-link').magnificPopup({
        type: 'inline',
        midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
        closeOnContentClick: true
    });
    $('.open-popup-link')[0].click();
}

/*=============================================================================*/
/*Х.З. что это*/
$('.notif-amount').text($('.payment-notifications-table tr').length - 1);

if ($('.payment-notifications-table tr').length > 5) {
    $('.show-all-notif').show();
}

$('.show-all-notif').click(function () {
    if ($('.profile-payment-container article.article-with-table .inform_block').is('.restored')) {
        $('.profile-payment-container article.article-with-table .inform_block').removeClass('restored').animate({'max-height': '-=' + (($('.payment-notifications-table tr').length - 5) * 40) + 'px'}, 500);
    } else {
        $('.profile-payment-container article.article-with-table .inform_block').addClass('restored').animate({'max-height': '+=' + (($('.payment-notifications-table tr').length - 5) * 40) + 'px'}, 500);
    }
    // $('.profile-payment-container article.article-with-table .inform_block:not(.restored)').addClass('restored').animate({'max-height': '+=' + (($('.payment-notifications-table tr').length - 5) * 40) + 'px'}, 500);
    // $('.profile-payment-container article.article-with-table .inform_block.restored').removeClass('restored').animate({'max-height': '-=' + (($('.payment-notifications-table tr').length - 5) * 40) + 'px'}, 500);
});

$('.rotate').click(function () {
    rotation += 5;
    $(this).rotate(rotation);
});

$('input[name="orders"').click(function () {
    $('tr').removeClass('active-row');
    $(this).parent().parent().addClass('active-row');
});