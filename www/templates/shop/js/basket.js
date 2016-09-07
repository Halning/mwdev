var
        commisionPrecent = 0.03, google;

$(document).ready(function () {
    var
            $body = $('body'),
            pjaxUrlBasket = "http://" + location.hostname + "/basket/",
            //init google maps if location basket
            autocompleteCity = new google.maps.places.Autocomplete(document.getElementById('autocomplete_city'), {language: 'ru'}),
            autocompleteAddress = new google.maps.places.Autocomplete(document.getElementById('autocom_adres'), {
                types: ['address'],
                componentRestrictions: {country: 'ua'}
            });

    //init functions
    //performance of functions
    initTooltip();
    $(window).resize(function () {
        insetsHeight();
    });
    insetsHeight();
    getMiniBasket(0);
    initDiscountGift();
    initPlGiftTooltip();

    //go To Full Basket Pjax
    $body.on('click', '.pjax-basket', function (e) {
        e.preventDefault();
        $.pjax({url: pjaxUrlBasket, container: '#pjax-conteiner-main', scrollTo: false, timeout: 20000});
    });

    //Clear basket
    $body.on('click', '.submit-clear-all', function () {
        scrollToElem('.main-content', 500, 10);
        cleanBasket();
    });

    //remove item from the basket
    $body.on('click', '.basket-com-delete', function () {
        var comId = $(this).closest('tr').attr('class');

        // pl gift
        var plGiftExist = $('.basket-table').data('plgift');
        if (plGiftExist == comId) {
            setTimeout(function () {
                $.pjax({url: pjaxUrlBasket, container: '#pjax-conteiner-main', scrollTo: false, timeout: 20000});
            }, 1000);
        }
        // end

        var properties = {
            'basket': 'deleteitemfrombasket',
            'basket_com_id': comId,
            'plGiftExist': plGiftExist};
        deleteItemFromBasket(properties, $(this));
    });

    //add or subtract the amount of goods
    $body.on('click', '.basket-minus-btn', function () {
        var comId = $(this).attr("name");
        var $input = $(this).parent().find('#' + comId);
        var countItem = parseInt($input.val()) - 1;

        // pl gift
        var plGiftExist = $('.basket-table').data('plgift');
        // end

        if (countItem < 1) {
            showError($input);
        } else {
            var properties = {
                'basket': 'updateCountItem',
                'basket_com_id': comId,
                'arithmetic': 'minus',
                'countItem': countItem,
                'plGiftExist': plGiftExist};
            updateCountItem(properties);
        }
    });
    $body.on('click', '.basket-plus-btn', function () {
        var comId = $(this).attr("name");
        var $input = $(this).parent().find('#' + comId);
        var countItem = parseInt($input.val()) + 1;

        // pl gift
        var plGiftExist = $('.basket-table').data('plgift');
        // end

        if (validateQuantityInt(countItem) === 0) {
            showError($input);
        } else {
            var properties = {
                'basket': 'updateCountItem',
                'basket_com_id': comId,
                'arithmetic': 'plus',
                'countItem': countItem,
                'plGiftExist': plGiftExist};
            updateCountItem(properties);
        }
    });

    //enter the quantity of the goods
    $body.on('focusout', '.basket-com-count', function () {
        var comId = $(this).attr("id");
        var countItem = $(this).val();

        // pl gift
        var plGiftExist = $('.basket-table').data('plgift');
        // end

        if (validateQuantityInt(countItem) === 0) {
            showError(this);
            $(this).val($(this).data("count"));
        } else {
            var properties = {
                'basket': 'updateCountItem',
                'basket_com_id': comId,
                'arithmetic': 'multiPlus',
                'countItem': countItem,
                'plGiftExist': plGiftExist};
            updateCountItem(properties);
        }
    });

    //toggle order form registration
    $body.on('click', '.submit-show', function () {
        var $selectCountryRadio = $('#select-country-radio');

        $(this).attr('disabled', 'disabled');
        //initTooltipCommision();
        $selectCountryRadio.trigger('change', [false]);
        warAdrSelectHide();
        $selectCountryRadio.buttonset();

        if ($('#autocomplete_city').val() !== '') {
            $('#autocomplete_city').trigger('blur', ['noSaveField']);
        }

        $('.cl-basket-field:not(#autocomplete_city)').each(function () {
            if ($(this).val() !== '') {
                $(this).trigger('change', ['noSaveField']);
            }
        });

        setTimeout(function () {
            scrollToElem('.country-selector', 500, 10);
        }, 600);

        $('.basket-form').slideDown(500);
    });

    //choice of the buyer's country
    $body.on('change', '#select-country-radio', function (event, emptyCity) {
        var countryVal = $('#select-country-radio input:checked').val(),
                $autocompleteCity = $('#autocomplete_city');
        if (countryVal === '1') {
            $("#phone").inputmask({'mask': "+38(999)999-99-99", 'autoUnmask': true}); //маска
            var restrictions = {country: 'ua'};
        } else {
            $("#phone").inputmask({'mask': "+7(999)999-99-99", 'autoUnmask': true}); //маска
            var restrictions = {country: 'ru'};
        }

        if (emptyCity !== false) {
            $autocompleteCity
                    .val('')
                    .closest('.form-group')
                    .removeClass('has-success has-feedback has-error')
                    .find('.glyphicon-ok')
                    .removeClass('show')
                    .addClass('hidden');
            warAdrSelectHide();
        }

        autocompleteCity.setComponentRestrictions(restrictions);
        autocompleteCity.setTypes(['(cities)']);
        var properties = {
            'basket': 'selectCountryAndCheckout',
            'country': countryVal};
        selectCountryAndCheckout(properties);
    });

    //choice of mode of delivery
    $body.on('change', '#id-delivery-method', function (event, id) {

        if (id) {
            $(this).val(id);
        }

        var $self = $(this),
                value = $self.val();
        switch (value) {
            case '':
                return false;
                break;
            case '2':
                var $writeAdres = $("#autocom_adres");
                warAdrSelectHide();
                warAdrSelectShow($writeAdres);
                checkNecessaryField($writeAdres);
                $('#autocomplete_city').removeClass('cl-necessary');
                break;
            case '3':
                if (getNovaPosta() === false) {
                    return false;
                }
                break;
            case '4':
            case '5':
            case '6':
            case '7':
                var $writeWarehouse = $("#write_warehouse");
                warAdrSelectHide();
                warAdrSelectShow($writeWarehouse);
                checkNecessaryField($writeWarehouse);
                $('#autocomplete_city').removeClass('cl-necessary');
                break;
            default:
                alert('Я таких доставок не знаю');
                break;
        }

        if (id !== 0) {
            setTimeout(function () {
                $self.next('.jq-selectbox__select').css({borderColor: "#3c763d"});
                $self.next('.jq-selectbox__select').find('.jq-selectbox__select-text').css({color: "#000"});
                changeFullSumDelevoryCommition(value);
            }, 500);
        }
    });

    //choice of mode of autocom_warehouse
    $body.on('change', '#autocom_warehouse', function () {
        var $self = $(this);
        setTimeout(function () {
            $self.next('.jq-selectbox__select').css({borderColor: "#3c763d"});
            $self.next('.jq-selectbox__select').find('.jq-selectbox__select-text').css({color: "#000"});
            changeFullSumDelevoryCommition($("#id-delivery-method").val());
        }, 100);
    });

    //check for filling the form fields google autocomplete
    $body.on('blur', '#autocomplete_city', function (event, saveField) {
        var countryVal = $('#select-country-radio input:checked').val();
        setTimeout(function () {
            var $self = $('#autocomplete_city');
            var userCityInput = $self.val().replace('Україна', 'Украина');
            var checkCountry = (countryVal === '1') ? userCityInput.replace('Україна', 'Украина').indexOf('Украина') : userCityInput.indexOf('Россия');
            if (checkCountry === -1) {
                setErrorField($self);
            } else {
                checkNecessaryField($self);
                if (saveField !== 'noSaveField') {
                    var properties = {
                        'basket': 'saveFieldsValue',
                        'value': userCityInput,
                        'fieldName': "city"};
                    saveFieldsValue(properties);
                    if ($("#id-delivery-method").val() === '3') {
                        getNovaPosta();
                    }
                }
            }
        }, 300);
    });

    //check for filling the form fields
    $body.on('change', '.cl-basket-field:not(#autocomplete_city)', function (event, saveField) {
        $('#id-delivery-method-styler').removeClass('cl-necessary cl-basket-field');
        var
                $self = $(this),
                name = "";
        if ($self.attr('type') === 'email') {
            name = 'email';
            var emailVal = $self.val();
            if (emailVal !== '') { // check mail.
                var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
                if (!pattern.test(emailVal)) {
                    setErrorField($self);
                    return false;
                }
            }
        } else if ($self.attr('id') === 'phone') {
            name = 'phone';
            //for mobile
            if ($self.val() === null) {
                return false;
            }

            var tph = parseInt($self.val().length);
            if (tph !== 10) {
                setErrorField($self);
                return false;
            }
        }

        checkNecessaryField($self);

        if (saveField !== 'noSaveField' && saveField !== 0) {
            var thisAttrName = $self.attr('name');
            switch (thisAttrName) {
                case 'basket_user_name':
                    name = "realname";
                    break;
                case 'basket_user_city':
                    name = "city";
                    break;
                case 'basket_user_comments':
                    name = "comments";
                    break;
            }
            if (name !== '') {
                var properties = {
                    'basket': 'saveFieldsValue',
                    'value': $self.val(),
                    'fieldName': name};
                saveFieldsValue(properties);
            }
        }
    });

    //order shipping
    $body.on('click', '.btn-submit-order', function (e) {
        scrollToElem('.main-content', 300, 10);
        e.preventDefault();

        //убираем name у basket_user_warehouse которые скрыты
        $('.control-hide').each(function () {
            if ($(this).css('display') === 'none') {
                $(this).find('.war-adr-select').attr('name', '');
            }
        });
        sendOrder();
    });

    //проверяем заполненые поля при наведении мыши на кнопку отправления
    $body.on('mouseenter', '#check-nesesary__field', function () {
        $('.cl-basket-field:not(#autocomplete_city)').each(function () {
            checkNecessaryField($(this));
        });
    });
});
//==============================================================================
//                            Функции
//==============================================================================

//Функция добавления товара в корзину
function addToBasket(properties) {
    $(".tech-info__item-basket").addClass("opened");
    $.getJSON("/modules/basket_fastOrder/ajax/ajax_basket.php", properties)
            .done(function (data) {
                if (data.error === 0) {
                    getMiniBasket(1);
                } else {
                    magnificPopupOpenError('Во время доьовления в корзину произошла ошибка! Попробуйте еще раз');
                }
            })
            .fail(function () {
                magnificPopupOpenError('Сервер не отвечает!');
            });
}

//Функция-показать маленькую корзину
function getMiniBasket(show) {
    $.getJSON("/modules/basket_fastOrder/ajax/ajax_basket.php", {'basket': 'miniBasket'})
            .done(function (data) {
                var panel;
                if (data.error === 0) {
                    var countMiniBasket = $(".hidden-basket").size();
                    if (countMiniBasket === 1) {
                        $(".hidden-basket").remove();
                    }
                    $('.tech-info__item-basket .number').empty().text(data.qty);
                    $('.tech-info__item-basket .summ').empty().text(data.sum);
                    panel = data.panel;
                } else {
                    magnificPopupOpenError('Во время отображения мини-корзины произошла ошибка!');
                }
                $(".tech-info__item-basket").append(panel);
                $('.custom-hidden.hidden-basket .custom-border .scroll-basket').mCustomScrollbar({
                    axis: "y",
                    scrollButtons: {enable: true}
                });
                if (show == 1) {
                    setTimeout(function () {
                        $(".tech-info__item-basket").removeClass("opened");
                    }, 3500);
                }
            })
            .fail(function () {
                magnificPopupOpenError('Сервер не отвечает!');
            });
}

//Функция полной очистки корзины
function cleanBasket() {
    $.getJSON("/modules/basket_fastOrder/ajax/ajax_basket.php", {'basket': 'basketclean'})
            .done(function (data) {
                if (data.error === 0) {
                    showEmptyBasket();
                    getMiniBasket(0);
                } else {
                    magnificPopupOpenError('Во время очистки корзины произошла ошибка! Попробуйте еще раз');
                }
            })
            .fail(function () {
                magnificPopupOpenError('Сервер не отвечает!');
            });
}

// Функция удаления одного товара из корзины
function deleteItemFromBasket(properties, deleteObj) {
    $.getJSON("/modules/basket_fastOrder/ajax/ajax_basket.php", properties)
            .done(function (data) {
                if (data.error === 0) {
                    var countTrBasket = $('.basket-table tr').size();
                    if (countTrBasket > 3) {
                        deleteObj.closest('.' + data.comId).hide('explode', 450).remove();
                        setTimeout(function () {
                            getNewPriceAndCountItem(data);
                        }, 500);
                    }
                    if (countTrBasket === 3) {
                        showEmptyBasket();
                    }
                    getMiniBasket(0);
                    setTimeout(function () {
                        changeFullSumDelevoryCommition($("#id-delivery-method").val());
                    }, 550);
                } else {
                    magnificPopupOpenError('Во время удаления товара произошла ошибка! Попробуйте еще раз');
                }
            })
            .fail(function () {
                magnificPopupOpenError('Сервер не отвечает!');
            });
}

// Функция уменьшения или уменьшения количества товара в корзине
function updateCountItem(properties) {
    $.getJSON("/modules/basket_fastOrder/ajax/ajax_basket.php", properties)
            .done(function (data) {
                var $input = $('.basket-minus-btn').parent().find('#' + data.comId);
                if (data.error === 0) {
                    var countItem = data.countItem;
                    if (data.limitSizeCount === 1) {
                        showError($input);
                        magnificPopupOpenError('Доступно только ' + countItem + ' ед.');
                    }
                    getNewPriceAndCountItem(data);
                    $('#' + data.comId).val(countItem);
                    $('#' + data.comId).data('count', countItem);
                    getMiniBasket(0);
                    changeFullSumDelevoryCommition($("#id-delivery-method").val());
                } else {
                    showError($input);
                    $input.val($input.data("count"));
                    magnificPopupOpenError('Во время изменения количества произошла ошибка! Попробуйте еще раз');
                }
            })
            .fail(function () {
                magnificPopupOpenError('Сервер не отвечает!');
            });
}

// Функция Выбор страны
function selectCountryAndCheckout(properties) {
    var $basketDevMet = $('#id-delivery-method'),
            existDelevory = String($basketDevMet.data('delivery'));
    $.getJSON("/modules/basket_fastOrder/ajax/ajax_basket.php", properties)
            .done(function (data) {
                if (data.error === 0) {
                    $('#id-delivery-method option:not(:first)').remove();
                    if (Object.keys(data.delivery).indexOf(existDelevory) !== -1) {
                        jQuery.each(data.delivery, function (id, val) {
                            $("<option></option>", {val: id, text: val}).appendTo($basketDevMet);
                            if (id === existDelevory) {
                                $basketDevMet.data('placeholder', val);
                                $basketDevMet.trigger('change', [id]);
                            }
                        });
                    } else {
                        jQuery.each(data.delivery, function (id, val) {
                            $("<option></option>", {val: id, text: val}).appendTo($basketDevMet);
                        });
                        $basketDevMet.data('placeholder', "Способ доставки товара *");
                        $basketDevMet.trigger('change', [0]);
                    }

                    $basketDevMet.trigger('refresh');
                    $basketDevMet.styler('destroy');
                    changeFullSumDelevoryCommition(data.deliveryMethod);
                } else {
                    magnificPopupOpenError('Во время выбора страны произошла ошибка! Попробуйте еще раз');
                }
            })
            .fail(function () {
                magnificPopupOpenError('Сервер не отвечает!');
            });
}

// Функция сохранения введеных пользователем данных
function saveFieldsValue(properties) {
    $.getJSON("/modules/basket_fastOrder/ajax/ajax_basket.php", properties)
            .done(function (data) {
                if (data.error !== 0) {
                    magnificPopupOpenError('Во время сохранения поля произошла ошибка! Попробуйте еще раз');
                }
            })
            .fail(function () {
                magnificPopupOpenError('Сервер не отвечает!');
            });
}

// Функция изменения окончательной цены
function changeFullSumDelevoryCommition(deliveryMethod) {
    $.getJSON("/modules/basket_fastOrder/ajax/ajax_basket.php", {'basket': 'getCurrency'})
            .done(function (data) {
                if (data.error === 0) {
                    var sumPrice = $('#res-sum').attr('value'),
                            countItem = $('#res-num').attr('value');
                    //var commision = Math.ceil(sumPrice * commisionPrecent);
                    var develoryPrice = 0;
                    if (deliveryMethod === '3' || deliveryMethod === '4' || deliveryMethod === '5') {
                        if (countItem <= 2) {
                            develoryPrice = 25 * data.currency;
                        } else if (countItem <= 4) {
                            develoryPrice = 30 * data.currency;
                        } else if (countItem <= 10) {
                            develoryPrice = 40 * data.currency;
                        } else if (countItem <= 20) {
                            develoryPrice = 50 * data.currency;
                        } else if (countItem <= 30) {
                            develoryPrice = 65 * data.currency;
                        } else if (countItem <= 60) {
                            develoryPrice = 85 * data.currency;
                        }
                    } else if (deliveryMethod === '6' || deliveryMethod === '7') {
                        var curGrnforDol = 100 / (data.baxCurrency * 100);
                        develoryPrice = (curGrnforDol * data.currency) * countItem;
                    } else if (deliveryMethod === '2' /*&& sumPrice < 1500 * data.currency*/) {
                        develoryPrice = 50 * data.currency;
                    }
                    develoryPrice = Math.ceil(develoryPrice);
                    $('#delivery-price span').text(develoryPrice);
                    //$('#commision span').text(commision);

                    // discountGift
                    if (countItem > 4 && $('#discountGift').data('exist') !== 0) {
                        var dataValNew = Math.ceil($('#discountGift').data('gift'));
                        $('#discountGift').data('val', dataValNew);
                        $('#discountGift span').text(dataValNew);
                        initDiscountGift();
                    } else {
                        $('#discountGift')
                                .data('val', 0)
                                .closest('tr')
                                .addClass('hidden');
                    }
                    var discountGift = $('#discountGift').data('val');

                    $('#full-price span').text(parseInt(develoryPrice) /*+ commision*/ + parseInt(sumPrice) - parseInt(discountGift));
                } else {
                    magnificPopupOpenError('Ошибка! Попробуйте еще раз');
                }
            })
            .fail(function () {
                magnificPopupOpenError('Сервер не отвечает!');
            });
}

// Функция оформления заказа
function sendOrder() {
    $.ajax({
        url: "/modules/basket_fastOrder/ajax/ajax_basket.php",
        type: 'POST',
        dataType: 'JSON',
        data: jQuery(".basket-form").serialize(),
        beforeSend: function () {
            $('.basket-conteiner').html('<div class="main-container"><div class="main-content row main-width animate-conteiner"><div id="animate-conteiner"></div></div></div>');
            $('.animate-conteiner').css({padding: "150px 100px"});
            myAnimat();
        },
        success: function (data) {
            if (data.error === 0) {
                $('.basket-conteiner').children().remove();
                $('.basket-conteiner').append(data.finalPageOrder);
                //initTooltipCommision();
                getMiniBasket(0);
                insetsHeight();
            } else {
                magnificPopupOpenError('Во время отправки письма произошла ошибка! Попробуйте еще раз');
            }
            $('#animate-conteiner').remove();
        },
        error: function () {
            magnificPopupOpenError('Сервер не отвечает!');
        }
    });
}

// Функция вызов пустой корзины
function showEmptyBasket() {
    $('.basket-table, .btn, .basket-form').remove();
    $.ajax({
        url: "/modules/basket_fastOrder/ajax/ajax_basket.php",
        type: 'GET',
        dataType: 'JSON',
        data: {'basket': 'getEmptyBasket'},
        beforeSend: function () {
            $('.basket-conteiner').html('<div class="main-container"><div class="main-content row main-width animate-conteiner"><div id="animate-conteiner"></div></div></div>');
            $('.animate-conteiner').css({padding: "270px 100px"});
            myAnimat();
        },
        success: function (data) {
            if (data) {
                var dataNew = strstr(data.panel, '<!--parse-->', true);
                var dataNewAjax = strstr(dataNew, '<div class="basket-empty-conteiner">');
                $('.basket-conteiner').children().remove();
                $('.basket-conteiner').append(dataNewAjax);
                $('.animate-conteiner').css({opacity: "0", padding: "0px 100px"}).animate({padding: "0px", opacity: "1"}, 300);
                $('#animate-conteiner').remove();
            }
        },
        error: function () {
            magnificPopupOpenError('Сервер не отвечает!');
        }
    });
}

/**
 * Вся работат с Api NovaPosta
 * @returns {Boolean}
 */
function  getNovaPosta() {
    var autocompleteCity = $('#autocomplete_city'),
            userCityInput = autocompleteCity.val().replace('Україна', 'Украина');
    $('#autocom_warehouse').val('');
    if (userCityInput === '' || userCityInput.replace('Україна', 'Украина').indexOf('Украина') === -1) {
        magnificPopupOpenError('Выбирете город из списка!');
        return false;
    } else {
        var userCityArr = userCityInput.split(','),
                userRegionArr = userCityArr[1].trim().split(' ');
        var userCity = userCityArr[0].trim(),
                userRegion = userRegionArr[0].trim();
        autocompleteCity.addClass('cl-necessary');
        getWarehouseNovaPosta({
            'basket': 'getWarehouseNovaPosta',
            'userCity': userCity,
            'userRegion': userRegion
        });
    }
}

//Функция выбора отделений для новой почты
function getWarehouseNovaPosta(properties) {
    var $autocomWarehouse = $('#autocom_warehouse'),
            $writeWarehouse = $("#write_warehouse");
    $.getJSON("/modules/basket_fastOrder/ajax/ajax_basket.php", properties)
            .done(function (data) {
                if (data.error === 0) {
                    $('#autocom_warehouse option:not(:first)').remove();
                    jQuery.each(data.warehouses, function (id, val) {
                        $("<option></option>", {val: val.DescriptionRu, text: val.DescriptionRu}).appendTo($autocomWarehouse);
                    });
                    $autocomWarehouse.trigger('refresh');
                    $autocomWarehouse.styler('destroy');
                    warAdrSelectHide();
                    warAdrSelectShow($autocomWarehouse);
                    checkNecessaryField($autocomWarehouse);
                } else {
                    magnificPopupOpenError('В вашем городе не найдены отделения, введите отделение в ручную!');
                    warAdrSelectHide();
                    warAdrSelectShow($writeWarehouse);
                    checkNecessaryField($writeWarehouse);
                }
            })
            .fail(function () {
                magnificPopupOpenError('Сервер не отвечает!');
            });
}

/**
 * Скрываем все поля control-hide выбора улицы или доставки перевозчиком
 * @returns {undefined}
 */
function warAdrSelectHide() {
    $('.war-adr-select')
            .removeClass('cl-necessary')
            .closest('.control-hide')
            .hide();
}

/**
 * Показываем поля в зависимости от метода доставки
 * @param {type} $obj какой control-hide показать
 * @returns {undefined}
 */
function warAdrSelectShow($obj) {
    $obj
            .addClass('cl-necessary')
            .closest('.control-hide')
            .show(500);
}

//Функция проверки заполнения обязательных полей
function checkNecessaryField($self) {
    if ($self.val() !== '') {
        $self
                .closest('.form-group')
                .removeClass('has-error')
                .addClass('has-success has-feedback');
        $self
                .next('.glyphicon-ok')
                .removeClass('hidden')
                .addClass('show');
    } else if ($self.hasClass('cl-necessary')) {
        $self
                .closest('.form-group')
                .addClass('has-error')
                .removeClass('has-success has-feedback');
        $self
                .next('.glyphicon-ok')
                .addClass('hidden')
                .removeClass('show');
    } else {
        $self.closest('.form-group').removeClass('has-success has-feedback');
    }
    var sizeEmpty = $('.basket-form').find('.cl-necessary').size(), i = 0;
    $('.basket-form').find('.cl-necessary').each(function () {
        if ($(this).val() !== '') {
            i += 1;
        }
    });
    if (i === sizeEmpty) {
        $('.btn-submit-order').removeAttr("disabled");
    } else {
        $('.btn-submit-order').attr('disabled', "disabled");
    }
}

/**
 * Неправильное заполнено поле , выключаем кнопку отправить
 * @param {type} $self - поле
 * @returns {undefined}
 */
function setErrorField($self) {
    $self.closest('.form-group').addClass('has-error');
    $self.closest('.form-group').removeClass('has-success has-feedback');
    $self.next('.glyphicon-ok').addClass('hidden');
    $('.btn-submit-order').attr('disabled', "disabled");
}

// Функция инициализации всплывающего окна на Количестве
function initTooltip() {
    $('#th-count').tooltip({
        content: "<div class='custom-border-tooltip'>Оптовые цены от 5 ед. общего количества</div>",
        hide: {effect: "explode", duration: 500},
        //position: {my: "left+15 center", at: "right center"},
        tooltipClass: "custom-tooltip-styling",
        track: true
    });
}

// Функция инициализации всплывающего окна на комиссии
function initTooltipCommision() {
    $('.commision').tooltip({
        content: "<div class='custom-border-tooltip'>Дополнительные 3% оплачиваются в качестве компенсации затрат на логистику и " +
                "банковских комиссий при осуществлении переводов</div>",
        hide: {effect: "explode", duration: 300},
        //position: {my: "left+15 center", at: "right center"},
        tooltipClass: "custom-tooltip-styling",
        track: true
    });
}

/**
 * show 150 грн action
 * 
 * @returns {undefined}
 */
function initDiscountGift() {
    if ($('#discountGift').data('exist') !== 0) {
        $('#discountGift').closest('tr').removeClass('hidden');
    }
}

// pl gift
function initPlGiftTooltip() {
    $('.string-gift').each(function () {
        if ($(this).text() !== '') {
            $(this).tooltip({
                content: "<div class='custom-border-tooltip'>Этот товар подпадает под акцию 'Платье в подарок' если сумма заказа равна или больше 1000 грн.</div>",
                hide: {effect: "explode", duration: 500},
                tooltipClass: "custom-tooltip-styling",
                track: true
            });
        }
    });
}
// end

// Функция добавления и удаления прокрутки таблицы корзины
function insetsHeight() {
    (function ($) {
        if ($(".basket-conteiner .table-res").length) {
            if ($(window).outerWidth() < 768) {
                $(".basket-conteiner .table-res").mCustomScrollbar({
                    axis: "x",
                    scrollButtons: {enable: true}
                });
            } else {
                $(".basket-conteiner .table-res").mCustomScrollbar("destroy");
            }
        }
    })(jQuery);
}

// Функция изменения цены и количества товаров
function getNewPriceAndCountItem(data) {
    var currency = $('#currency').text();
    var totalCount = 0;
    var totalSum = 0;
    for (var key in data.priceBasket) {
        $('#price-' + key)
                .text(Math.ceil(data.priceBasket[key] * data.currency))
                .append(' <i id="currency">' + currency + '</i>');
    }
    
    for (var key in data.countItemArr) {

        // pl gift if 1 
        if (data.plGift[key] === 1) {
            $('#sum-price-' + key)
                    .text(parseInt(parseInt(data.countItemArr[key]) - data.plGift[key]) * parseInt($('#price-' + key).text()))
                    .append(' <i>' + currency + '</i>');
            $('#total-price__old--' + key)
                    .text(parseInt(parseInt(data.countItemArr[key])) * parseInt($('#price-' + key).text()))
                    .append(' <i>' + currency + '</i>')
                    .removeClass('hidden');
        } else {
            $('#sum-price-' + key)
                    .text(parseInt(parseInt(data.countItemArr[key])) * parseInt($('#price-' + key).text()))
                    .append(' <i>' + currency + '</i>');
            $('#total-price__old--' + key).addClass('hidden');
        }

        $('.basket-plus-btn').parent().find('#' + key).val(parseInt(data.countItemArr[key]));
        totalCount += parseInt(data.countItemArr[key]);
    }
    $('#res-num').text(parseInt(totalCount));
    $('#res-num').attr('value', totalCount);
    for (var i = 0; i < $('.total-price').length; i++) {
        totalSum += parseInt($('tr').find('.total-price').eq(i - 1).text());
    }
    $('#res-sum').text(totalSum + ' ' + currency);
    $('#res-sum').attr('value', totalSum);
}

// Функция анимации загрузки
function myAnimat() {
    var setAnimate, flag = 0;
    var $animateConteiner = $('#animate-conteiner'),
            t = 0,
            H = 128, // Высота кадра и самого контейнера
            sprH = 3840, // Высота спрайта
            speed = 25; // Скорость анимации
    if (flag === 0) {
        flag = 1;
        setAnimate = setInterval(function () {
            t += H;
            if (t === sprH)
                t = 0;
            $animateConteiner.css({'background-position': '0 -' + t + 'px'});
        }, speed);
    }
}

//pjax
$('#pjax-conteiner-main')
        .on('pjax:start', function () {

            if ($.pjax.options.container !== '#pjax-container') {
                $(this).html('<div class="main-container"><div class="main-content row main-width animate-conteiner"><div id="animate-conteiner"></div></div></div>');
                $('.animate-conteiner').css({padding: "270px 100px"});
            } else {
                showLoading();
            }
            scrollToElem('.main-content', 300, 10);
            myAnimat();
        })
        .on('pjax:end', function () {
            if (location.pathname.match('\/c[0-9]') && $.pjax.options.container !== '#pjax-container') {
                pagination();
                showFilters();
                getAllAfterPjax();
                setFiltersState();
                checkAndHideOptPrices();
            } else if ((location.pathname.match('\/c[0-9]') && $.pjax.options.container === '#pjax-container') ||
                    location.pathname.match('^\/search') ||
                    location.pathname.match('^\/catalog')
                    ) {
                pagination();
                getAllAfterPjax();
                checkAndHideOptPrices();
            } else if (location.pathname.match('\/basket/')) {
                autocompleteCity = new google.maps.places.Autocomplete(document.getElementById('autocomplete_city'), {language: 'ru'});
                autocompleteAddress = new google.maps.places.Autocomplete(document.getElementById('autocom_adres'), {
                    types: ['address'],
                    componentRestrictions: {country: 'ua'}
                });
                initTooltip();
                insetsHeight();
                getMiniBasket(0);
                initDiscountGift();
                initPlGiftTooltip();
            }
            $(this).css({opacity: "0", padding: "0px 100px"}).animate({padding: "0px", opacity: "1"}, 300);
            $('#animate-conteiner').remove();
            hideLoading();
        })
        .on('pjax:popstate', function () {
            $(".super_nav_1").slideUp(300);
        });
