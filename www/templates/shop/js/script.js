/**
 * Основной скрипт js
 * 1) Все обьекты, модули и переменные
 * 2) Вызов функции при загрузке скрипта и init некоторых плагинов для всех елементов
 * 3) Все события header и footer
 * 4) события center
 */
window.brandCategories = {};
window.categoryBrands = {};

//1-Обьект определяет мобильник или нет
var isMobile = {
    Android: function () {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function () {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function () {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function () {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function () {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function () {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};
$(document).ready(function () {

    //1-часто используемые обьекты Dom
    var $body = $('body');

    //2-вызов функции с текущим курсом
    getCurrency();

    //2-init popups
    initPopupsFromComBlock();

    //2-init slider brands
    runBrandsSlick();

    //2-меню категории и брендов в header не для мобильных
    brandDesctop();

    //2-изменяем высоту описания
    setTimeout(function () {
        if ($(window).outerWidth() >= 768 && $(window).outerWidth() <= 1234) {
            var productCardHeight = $(".product-card").outerHeight(),
                    productInfoHeight = $(".product-info").outerHeight(),
                    coeficient = productCardHeight - productInfoHeight;
            $(".product-card .insets-wrap").height(coeficient - 35);
        }
    }, 10);

    //2-завжди підгружати мои желания
    getMyWish();

    //2- залежності від текучої сторінки підгружаєм відповідні функції
    if (location.pathname == '/') {
        checkAndHideOptPrices();
        getBrandCategories();
        redirectToCatalogueByFilter();
    } else if (location.pathname.match('\/c[0-9]{2,}') &&
            location.pathname.match('&menu')
            ) {
        getCategoryBrands();
        tabFieldsForCategories();
        redirectToCatalogueByFilter();
    } else if (location.pathname.match('^/about-company/$')) {
        getComments($('.coments-conteiner'));
    } else if (location.pathname.match('^\/product\/[0-9]{3,4}')) {
        getComments($('.users-reviews'));
        checkActiveWatch();
        checkAndHideOptPrices();
    } else if (location.pathname.match('^\/search')) {
        pagination();
        getAllAfterPjax();
        checkAndHideOptPrices();
    } else if (location.pathname.match('^\/myaccount\/wish')) {
        checkAndHideOptPrices();
    } else if (location.pathname.match('^/akcionnie-predlojeniya/')) {
        var actionKeyArr = location.href.replace('/', '').split('#');

        switch (actionKeyArr[1].replace('/', '')) {
            case '150gr':
                $('.action-tabs__tab:nth(1) a').tab('show');
                break;
            case 'discount':
                $('.action-tabs__tab:nth(2) a').tab('show');
            default:
                break;
        }
    }

    //3-Применяем плагин стайлер для всех select
    $("select:not(.season select, .order select, #page-commodities-count select, #profile-delivery-method)").styler();
    $(".product .jq-selectbox__select").click(function () {
        $(".product.hovered").css("overflow", "visible");
    });

    //3-plagin inputmask для всех input
    $(":input").inputmask();

    //3-Показывает слайдер (контакты) верхняя навигация слева
    $('.top-nav li:last-child a').on('click', function (e) {
        e.preventDefault();
        var $topLineBd = $('.top-line-bg');

        $(this).closest('li').toggleClass('active');
        $('.contacts-top').slideToggle();

        if ($topLineBd.css('position') === 'fixed') {
            $topLineBd.css({position: "static"});
        } else {
            $topLineBd.css({position: "fixed"});
        }

        scrollToElem($(this), 700, 10);
    });

    //3-форма контакты(напишите нам) отпрака на сервер
    $('.write-us-form').on('click', ' .row__input a', function () {
        var error = false,
                $form = $('.contacts-top .write-us-form'),
                $email = $(this).prev('input'),
                $message = $(this)
                .closest('form')
                .find('textarea');

        if (!validateEmail($email.val())) {
            showError($email, true);
            error = true;
        }

        if ($message.val() === '') {
            showError($message);
            error = true;
        }

        if (error) {
            return false;
        }
        $form.find('form').slideUp(1000);

        $.ajax({
            url: '/modules/comments/ajax/message.php',
            type: 'GET',
            data: $form.find('form').serialize(),
            dataType: 'json',
            success: function (data) {
                if (data.success === 1) {
                    $form.find('form').remove();
                    $form.append($('<p>', {
                        text: data.message,
                        class: 'message-result'
                    }));
                } else {
                    $form.find('form')
                            .slideDown(1000)
                            .find('textarea')
                            .val('');
                }
                magnificPopupOpenError(data.message);
            }
        });
    });

    //3-Скролл для выбора категорий в поиске
    $('.all-categories p').on('click', function () {
        $(".all-categories__in").slideToggle(300);
        $(".all-categories__in").mCustomScrollbar({
            axis: "y",
            scrollButtons: {enable: true}
        });
    });

    //3-Присваеваем значение категории форме поиска
    $('.all-categories__in ul li a').on('click', function () {
        $(".all-categories__in").slideToggle(300);
        $('.all-categories p').text($(this).data('val'));
        $('input#search-cat-id').val($(this).data('val')).hide();
    });

    //3-регистрация вход
    $body.on('click', "#sign-in, #sing-up", function () {
        $(this).next().toggleClass('active');
        $(this).toggleClass('active');
        var thisId = $(this).attr('id');
        if (thisId === 'sign-in') {
            $('#sing-up').next().removeClass('active');
            $('#sing-up').removeClass('active');
        } else {
            $('#sign-in').next().removeClass('active');
            $('#sing-in').removeClass('active');
        }
    });

    //3-reg
    $body.on('click', '#click-regestration', function () {
        scrollToElem('.header-bottom', 200, 10);
        $('#sing-up').trigger('click');
    });

    //click banner slider - platie
    $body.on('click', '#gift-slider #click-podarok', function () {
        location.pathname = '/catalog/platie-v-podarok';
    });

    //3-Свернуть форму входа и регистрации при клике в любую точку
    $body.click(function (event) {
        var event = event || window.event;
        var ET = event.target || event.srcElement;
        var
                selfPt = $(".pop-title"),
                actionReg = $('#gift-slider'),
                selfTpPo = $(".top-popups .popup-open"),
                selfAc = $('.all-categories');

        if (!$(ET).closest(selfPt).length && !$(ET).closest(actionReg).length) {
            if ($('.custom-hidden-2').hasClass('active')) {
                $('.custom-hidden-2').removeClass('active');
                $('.pop-title a').removeClass('active');
            }
        }

        //Свернуть меню валют или языков при клике в любую точку
        if (!$(ET).closest(selfTpPo).length) {
            if ($('.currency-wrap .d-table').css("display") === "block") {
                $(selfTpPo).next().slideUp(0);
                function removeClass() {
                    $(selfTpPo).parent().removeClass('opened');
                }
                setTimeout(removeClass, 0);
            } else if ($('.language-wrap .d-table').css("display") === "block") {
                $(selfTpPo).next().slideUp(0);
                function removeClass() {
                    $(selfTpPo).parent().removeClass('opened');
                }
                setTimeout(removeClass, 0);
            }
        }

        //Сворачиваем Скролл категории для поиска при клике в любую часть окна
        if (!$(ET).closest(selfAc).length) {
            if ($(".all-categories__in").css("display") === "block") {
                $(".all-categories__in").slideToggle(300);
                $body.css("overflow-y", "auto");
            }
        }
        //event.stopPropagation();
    });

    //3-сховати лоадер
    $body.on('click', '#loading-spinner', function () {
        hideLoading();
    });

    //3-незнаю
    $body.click(function (event) {
        var event = event || window.event;
        var ET = event.target || event.srcElement;
        if ($(ET).closest(".nav-wrap").length)
            return;
        //event.stopPropagation();
    });

    //3-Свернуть развернуть меню валют и языков
    $('.top-popups .popup-open').click(function () {
        var self = $(this);
        if ($(this).next().css("display") === "none") {
            $(this).parent().addClass('opened');
            $(this).next().slideDown(50);
        } else {
            $(this).next().slideUp(50);
            function removeClass() {
                $(self).parent().removeClass('opened');
            }
            setTimeout(removeClass, 0);
        }
    });

    //3-Выбрать валюту и перезагрузить страницу
    $(".currency-wrap").on('click', '.d-table a', function () {
        var self = $(this);
        $(".currency-wrap .d-table a").each(function () {
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
            }
        });
        self.addClass("active");
        var rel = self.attr("rel");
        $.ajax({
            method: 'POST',
            url: window.location.href,
            data: {cur: rel}
        }).done(function () {
            location.reload();
        }).fail(function () {
            magnificPopupOpenError('Сервер не отвечает!');
        });
    });

    //3-для мобильных устройств
    if (isMobile.any()) {

        //клик на мои желания корзину и войти регистрация??????
        $(".out").on("click tap", function () {
            if ($(this).parent().find(".custom-hidden").css("display") === "none") {
                $(this).parent().find(".out").removeClass("active");
                $(this).parent().find(".custom-hidden").css("display", "block");
            } else {
                $(this).parent().find(".out").addClass("active");
                $(this).parent().find(".custom-hidden").css("display", "none");
            }
        });

        //категории  в поиске ????
        $(".all-categories").on("click tap", function () {
            setTimeout(function () {
                if ($(".all-categories__in").css("display") === "block") {
                    $("body").css("overflow-y", "hidden");
                } else {
                    $("body").css("overflow-y", "auto");
                }
            }, 500);
        });
    }

    //3-выбрать язык и перевести страницу !!!НЕ РАБОТАЕТ!!!
    $(".language-wrap .d-table a").click(function () {
        $(".language-wrap .d-table a").each(function () {
            if ($(this).hasClass("active"))
                ;
            $(this).removeClass("active");
        });
        $(this).addClass("active");
        var lng = $(this).text();
        lng = lng.replace(/ /g, '');
        var txt = $(".top-menu").html();
        var lang = "en";
        /*$.ajax({
         url: "https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20150928T083330Z.021e22287438394a.4fa733b785564ada1f38e1d681c85541f310020e&lang=" + lang + "&text=" + txt + "&format=html"
         })
         .done(function (data) {
         $(".top-menu").html(data['text']);
         alert(data['text']);
         //$("#lang2").text(data['lang']+"="+data['text']);
         });*/
    });

    //3-Переключение картинки бренда в меню брендов
    $('.sub-menu-in-cat1 .brand-columns>p a').on('mouseenter', function (e) {
        var self = $(this);
        e.preventDefault();
        var brandId = self.attr('data-attr');
        self.closest('.sub-menu-in-cat1').find('p').removeClass('active');
        self
                .addClass('active')
                .closest('.sub-menu-in-cat1')
                .find('.brand-img .brand-logo')
                .attr('id', 'category' + brandId);
    });

    //3 - subscribe tooltip on click for subscribe input
    $('footer .input-wrap').on('click', 'input', function () {
        $(this).tooltip('disable').tooltip('close');
    });

    //3 - subscribe key 'enter'
    $('footer .input-wrap').on('keyup', 'input', function (e) {
        if (e.keyCode == 13) {
            $('footer .input-wrap .btn-green').click();
        }
    });

    //3-subscribe
    $('footer').on('click', '.input-wrap .btn-green', function (e) {
        e.preventDefault();
        var $email = $(this).prev();
        if (validateEmail($email.val())) {
            $.ajax({
                method: 'POST',
                url: '/subscribe/',
                dataType: 'json',
                data: {
                    email: $email.val(),
                    action: 'subscribe'
                }
            })
                    .done(function (data) {
                        if (1 === data.success) {
                            magnificPopupOpenError('Спасибо, вы подписались!');
                        }
                    })
                    .always(function () {
                        $email
                                .val('')
                                .tooltip('disable')
                                .tooltip('close');
                    });
        } else {
            showErrorTooltip($email, 'Неверный email');
            showError($email, true);
        }
    });

    //4-main slider
    if (!isMobile.any()) {
        //4-baner and comments slider
        $('#comments-slider, #gift-slider').slick({
            dots: true,
            arrows: false,
            autoplay: true,
            autoplaySpeed: 7000,
            rtl: true
        });
        $('#banner-slider').slick({
            arrows: false,
            autoplay: true,
            autoplaySpeed: 4000,
            rtl: true
        });

        $('.top-slider:not(.profil-slider)').slick({
            slidesToShow: 6,
            slidesToScroll: 3,
            autoplaySpeed: 3000,
            autoplay: true,
            dots: true,
            responsive: [
                {
                    breakpoint: 1234,
                    settings: {
                        slidesToScroll: 5,
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
                        centerMode: true
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
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
    } else {
        $('.top-slider:not(.profil-slider), #comments-slider, #gift-slider, #banner-slider').addClass('hidden');
    }

    //4-HOVER карточки товара в главном слайдере
    $(".top-slider .product").each(function () {
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
                $(".top-slider .slick-list").css({"z-index": "10"});
                $self.addClass("hovered");
            },
            mouseleave: function () {
                $self.find(".product__info__bottom").find(".column").css({opacity: "0"}).height("0");
                $(".top-slider .slick-list").css({"z-index": "0"});
                $self.removeClass("hovered");
                $(".product").css("overflow", "hidden");
            }
        });
    });

    //4-add option to every sizes select, which have atr data.val
    $('.color-set').each(function () {
        if ($(this).find(':selected').data('val') != undefined) {
            self = this;
            sizes = $(this).find(':selected').data('val').toString().indexOf(',') > 0 ?
                    $(this).find(':selected').data('val').split(',') :
                    [$(this).find(':selected').data('val').split(',').toString()];
            jQuery.each(sizes, function (id, val) {
                $("<option></option>",
                        {value: val, text: val}).appendTo($(self).next('select'));
            });
        }
    });

    //4-on chnge color - changes sizes related to it color
    $('select.color-set').on('change', function () {
        var sizesString = $(this).find(':selected').data('val').toString();
        if (sizesString != undefined) {
            self = this;
            sizes = sizesString.indexOf(',') > 0 ?
                    sizesString.split(',') :
                    [sizesString];
            $(self).parent('.color-set').next('div').children('select').html('').trigger('refresh');
            jQuery.each(sizes, function (id, val) {
                $("<option></option>",
                        {value: val, text: val}).appendTo($(self).parent('.color-set').next('div').children('select'));
            });
            $(self).parent('.color-set').next('div').children('select').trigger('refresh');
        }
    });

    //4-on chnge color - changes sizes related to it color, for product card.
    $(".col-md-5 .jqselect:nth-child(3) select").on('change', function () {
        if ($(this).find(':selected').data('val') != undefined) {

            //take sizes from data-val and explode it to array.
            sizes = $(this).find(':selected').data('val').toString().indexOf(',') > 0 ?
                    $(this).find(':selected').data('val').split(',') :
                    [$(this).find(':selected').data('val').toString()];
            $(".col-md-5 .jqselect:nth-child(4) select").html('').trigger('refresh');

            //for each data-val sets option in sizes select.
            jQuery.each(sizes, function (id, val) {
                $("<option></option>",
                        {value: val, text: val}).appendTo($(".col-md-5 .jqselect:nth-child(4) select"));
            });
            $(".col-md-5 .jqselect:nth-child(4) select").trigger('refresh');
        }
    });

    //4-Минус единица товара
    $body.on('click', '.minus-btn', function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        //for fast order count 1
        if (count === 1) {
            disableAnableFastOrder($(this), '#fastOrder', '#9d9d9d');
        }

        $input.val(count);
        $input.change();
        return false;
    });

    //4-Плюс единица товара
    $body.on('click', '.plus-btn', function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) + 1;
        count = count > 99 ? 99 : count;
        //for fast order count 1
        if (count !== 1) {
            disableAnableFastOrder($(this), '', '#BEBEBE');
        }
        $input.val(count);
        $input.change();
        return false;
    });

    //4-Ошибка если не введено количество
    $body.on('focusout', '.counter-group input', function () {
        var self = $(this);

        if (validateQuantityInt(self.val()) === 0) {
            showError(self);
            self.val(1);
        } else {
            self.closest('.product__info__bottom').find('.add-to-cart').attr("href", '#addToCartPopup');
            self.closest('.product__info__bottom').find('.fast-order').attr("href", '#fastOrder');
        }
        //for fast order count 1
        if (self.val() !== '1') {
            disableAnableFastOrder($(this), '', '#BEBEBE');
            return false;
        }
        disableAnableFastOrder($(this), '#fastOrder', '#9d9d9d');
    });

    $body.on('focusin', '.counter-group input', function () {
        $(this).closest('.product__info__bottom').find('.add-to-cart , .fast-order').attr("href", '');
    });

    //4-Hover для сердечка и цыфры
    $('.number a').on({
        mouseenter: function () {
            $(this).closest('.info__top__column').find('.icon-heart').css({backgroundPosition: "-176px -59px"});
            $(this).css({color: "#57aab5"});
        },
        mouseleave: function () {
            $(this).closest('.info__top__column').find('.icon-heart').css({backgroundPosition: ""});
            $(this).css({color: ""});
        }
    });
    $('.icon-heart').on({
        mouseenter: function () {
            $(this).closest('.like').next().find('a').css({color: "#57aab5"});
        },
        mouseleave: function () {
            $(this).closest('.like').next().find('a').css({color: ""});
        }
    });

    //4-popup инфо о бренде
    $('.about-brand').magnificPopup({
        removalDelay: 500,
        callbacks: {
            beforeOpen: function () {
                this.st.mainClass = this.st.el.attr('data-effect');
            },
            afterClose: function () {
                $(".brand-item").removeClass("hovered");
            }
        },
        midClick: true
    });

    //4-Слайдер главного и доп фото на странице товара
    $('.big-product-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.big-product-slider-in-popup'
    });
    var ImgCount = $('.small-product-slider').children('div').length;
    if (ImgCount !== 1) {
        $('.small-product-slider').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: '.big-product-slider',
            focusOnSelect: true
        });
    } else {
        $('.small-product-slider').css({display: "none"});
    }

    //4-Popup плагин для фото
    $('.gallery').each(function () { // the containers for all your galleries
        $(this).magnificPopup({
            delegate: 'a', // the selector for gallery item
            type: 'image',
            gallery: {
                enabled: true
            },
            mainClass: 'mfp-with-zoom', // this class is for CSS animation below

            zoom: {
                enabled: true, // By default it's false, so don't forget to enable it
                duration: 300, // duration of the effect, in milliseconds
                easing: 'ease-in-out', // CSS transition easing function

                // The "opener" function should return the element from which popup will be zoomed in
                // and to which popup will be scaled down
                // By defailt it looks for an image tag:
                opener: function (openerElement) {
                    // openerElement is the element on which popup was initialized, in this case its <a> tag
                    // you don't need to add "opener" option if this code matches your needs, it's defailt one.
                    return openerElement.is('img') ? openerElement : openerElement.find('img');
                }
            }
        });
    });

    //4-Jquery Image Zoom Plugin главной картинке не для мобильных
    if (!isMobile.any()) {
        $body.on('mouseenter', ".gallery .slick-active img", function () {
            $(this).elevateZoom({
                zoomWindowWidth: 300,
                zoomWindowHeight: 450,
                easing: true,
                tint: true,
                tintColour: '#FFFAFA',
                tintOpacity: 0.5,
                scrollZoom: true,
                cursor: "crosshair"
            });
        });

        //Ставим дефолтный zoom при переключении фоток
        $(".small-slide-img").click(function () {
            $('.zoomContainer').remove();
        });
        $(".small-product-slider").click(function () {
            $('.zoomContainer').remove();
        });
    }

    //4-Скролл для описания товара
    $(".product-card .all-insets").mCustomScrollbar({
        axis: "y",
        scrollButtons: {enable: true}
    });

    //4-скролл для коментов в о компании
    $(".comment-company-lines").mCustomScrollbar({
        axis: "y",
        scrollButtons: {enable: true}
    });

    //TODO - закоминтив бо не працює попап мои желания на страниц товара
    //4-Poupup следить за ценой ,мои желания
    /*$('.btns-group .btns-group-popup').magnificPopup({
     removalDelay: 500, //delay removal by X to allow out-animation
     callbacks: {
     beforeOpen: function () {
     this.st.mainClass = this.st.el.attr('data-effect');
     }
     },
     midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
     });*/

    //4-Устанавливаем обрабочик событий изменение окна браузера
    $(window).resize(function () {
        brandDesctop();
        setTimeout(function () {
            if ($(window).outerWidth() >= 768 && $(window).outerWidth() <= 1234) {
                var productCardHeight = $(".product-card").outerHeight(),
                        productInfoHeight = $(".product-info").outerHeight(),
                        coeficient = productCardHeight - productInfoHeight;
                $(".product-card .insets-wrap").height(coeficient - 35);
            }
        }, 10);

        //Jquery Image Zoom Plugin не для мобил
        if (!isMobile.any()) {
            $('.zoomContainer').remove();
            $(".gallery .slick-active img").trigger('mouseenter');
        }
    });

    //4-Comments
    //4-проверяем заполнены ли обязательные поля формы при загрузке страницы
    //4-для зарегистрированных пользователей
    $('.comment-field').each(function () {
        if ($(this).val() !== '') {
            $(this).closest('.form-group').addClass('has-success');
        }
    });

    //4-Event for clicking on to-write-answer-to-review button (Dominic)
    $body.on('click', '.toggle-answer', function () {
        $(this).toggleClass('active');
        $(this).closest('.comments-wrap').next().toggle();
        $('.answers').hide();
        $('.show-answers').removeClass('active');
    });

    //4-Event for clicking on to-show-answers-to-review button (Dominic)
    $body.on('click', '.show-answers', function () {
        $(this).toggleClass('active');
        $(this).closest('.comments-wrap').next().next().toggle();
        $('.write-answer').hide();
        $('.toggle-answer').removeClass('active');
    });

    //4-проверяем заполненые поля
    $body.on('change', '.comment-field', function () {
        var $thisFormGroup = $(this).closest('.form-group'),
                $thisComForm = $(this).closest('.coments-form'),
                $btnComForm = $('.btn-coments-form');
        var value = $(this).val();

        if ($(this).attr('name') === 'email') {
            if (value !== '') { // check mail.
                var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
                if (!pattern.test(value)) {
                    $thisFormGroup.addClass('has-error');
                    $thisFormGroup.removeClass('has-success');
                    $btnComForm.attr('disabled', "disabled");
                    return false;
                }
            }
        }

        if (value == "") {
            $thisFormGroup.removeClass('has-success');
            $thisFormGroup.addClass('has-error');
        } else {
            $thisFormGroup.removeClass('has-error');
            $thisFormGroup.addClass('has-success');
        }

        checkNecessaryFieldsForComments($btnComForm, $thisComForm);
    });

    //4-проверяем заполненые поля при наведении мыши на кнопку отправления
    $body.on('mouseenter', '.submit', function () {
        var $thisComForm = $(this).closest('.coments-form'),
                $btnComForm = $(this).find('.btn-coments-form');
        checkNecessaryFieldsForComments($btnComForm, $thisComForm);
    });

    //4-comments form
    $body.on('click', '.btn-coments-form', function (e) {
        e.preventDefault();
        var $review = $('.write-review'),
                slider = false,
                id = $review.find('.rating').data('id');

        $review.children('div, form').slideUp();
        $review.prev('h4').slideUp();
        $review.prepend($('<p>', {
            class: 'success-message',
            text: '...loading',
            css: {opacity: 1}
        }).fadeIn(1500));

        setTimeout(function () {
            $('.success-message').css({'opacity': 2})
        }, 300)

        //якщо слайдер, то берем ід продукта з слайдера
        if (
                $.magnificPopup.instance.isOpen &&
                $.magnificPopup.instance.currItem.src === '#writeReview'
                ) {
            slider = true;
            id = $.magnificPopup.instance.currItem.el.children('ul').data('id');
            $.magnificPopup.instance.currItem.el
                    .attr('href', '')
                    .children('ul')
                    .removeClass('available');
        }

        $.ajax({
            url: '/modules/comments/ajax/comments.php',
            data: {
                'user_name': $('#comment-user-name').val(),
                'user_email': $('#comment-user-email').val(),
                'item_id': id,
                'rating': $review.find('.rating .active').length,
                'comment': $review.find('textarea[name="coment"]').val(),
                flaw: $review.find('textarea[name="limitations"]').val(),
                advantage: $review.find('textarea[name="dignity"]').val()
            },
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $review.children('p')
                        .html(data.lines);
                setTimeout(function () {
                    if (slider) {
                        $.magnificPopup.close();
                    }
                    $review.children('div, form').slideDown(800);
                    $review.children('p').fadeOut(1500).remove();
                }, 5000);

                $review.find('textarea').val('');
            }
        });
    });

    //4-ответ к коментар.
    $body.on('click', '.write-answer button', function (e) {
        e.preventDefault();

        var $writeAnswers = $(this).closest('.write-answer');

        //скриваєм форму відповіді і показуєм всі відповіді.
        $writeAnswers.find('form')
                .hide(800);

        $.ajax({
            url: '/modules/comments/ajax/comments.php',
            type: 'POST',
            data: {
                item_id: $('.write-review .rating').data('id'),
                comment: $writeAnswers.find('.coments-textarea textarea').val(),
                user_name: $writeAnswers.find("#comment-user-name").val(),
                parent_id: $(this).data('id')
            },
            dataType: "json",
            success: function (data) {

                $writeAnswers.children('.comments-wrap')
                        .append($('<p>', {
                            class: 'success-message',
                            text: data.lines
                        }));

                setTimeout(function () {
                    $writeAnswers.children('.comments-wrap')
                            .children('p')
                            .remove();
                    $writeAnswers.find('form').show(800);
                }, 4000);

                //очищаєм форму
                $writeAnswers.find('.coments-textarea textarea').val('');
            }
        });
    });

    //4-redirect to comments from slider to product card
    if (window.location.href.match('html#comments$')) {
        $(".col-md-6 .tabs-wrap .my-inset").removeClass("active");
        $('.col-md-6 .tabs-wrap .my-inset:nth-child(3)').addClass("active");
        $('.col-md-6 .tabs-wrap .all-insets').fadeOut(100, function () {
            $(this).removeClass("active-inset");
            $('.tabs-wrap .open-inset3').fadeIn(100, function () {
                $(this).addClass("active-inset");
            });
        });
    }

    //4-Event for clicking on to-write-review button (Dominic)
    $body.on('click', '.toggle-review', function () {
        $('.users-reviews>.tab-head').toggle();
        $('.users-reviews>.comments-wrap').toggle();
        $('.write-review').toggle();
        $('.show-answers').removeClass('active');
        $('.answers').hide();
        $('.toggle-answer').removeClass('active');
        $('.write-answer').hide();
    });

    //4-event оставить отзив на страниц товара
    $('.slider-col').on('click', '.rating-info a', function (e) {
        e.preventDefault();

        //отображает форму отрпавки отзыва
        $(".tabs-wrap .my-inset").removeClass("active");
        $('.tabs-wrap .my-inset:nth-child(3)').addClass("active");
        var insetIsOn = $('.tabs-wrap .my-inset:nth-child(3)').attr("data-inset");
        $(".tabs-wrap .active-inset").fadeOut(100, function () {
            $(this).removeClass("active-inset");
            $(".tabs-wrap").find("." + insetIsOn).fadeIn(100, function () {
                $(this).addClass("active-inset");
            });
        });

        $(".col-md-6 .write-review").show();
        $('.users-reviews>.tab-head').hide();
        $('.users-reviews>.comments-wrap').hide();
        $('.answers').hide();
        $('.write-answer').hide();
    });

    //4-tabs field
    $(".tabs-wrap .all-insets").hide();
    $(".tabs-wrap .all-insets").first().show().addClass("active-inset");
    $(".tabs-wrap .my-inset").on("click", function () {
        $(".tabs-wrap .my-inset").removeClass("active");
        $(this).addClass("active");
        var insetIsOn = $(this).attr("data-inset");
        $(".tabs-wrap .active-inset").fadeOut(100, function () {
            $(this).removeClass("active-inset");
            $(".tabs-wrap").find("." + insetIsOn).fadeIn(100, function () {
                $(this).addClass("active-inset");
            });
        });
    });

    //4-подчеркивание ссылки в строке брендов "Смотреть все бренды"
    $('.main-container .main-content .brands .title .see-all-brands').mouseenter(function () {
        $('.main-container .main-content .brands .title .see-all-brands a').css('text-decoration', 'underline');
    });
    $('.main-container .main-content .brands .title .see-all-brands').mouseleave(function () {
        $('.main-container .main-content .brands .title .see-all-brands a').css('text-decoration', 'none');
    });

    //4-rating stars
    $body.on(
            'mouseenter',
            '.tab-head .rating li, .slick-slider .rating-small li,' +
            '.coments-form .rating li, .catalog-conteiner .rating-small li,' +
            '.profile-wishes-container .rating-small li',
            function () {
                if (!$(this).parent('ul').hasClass('available')) {
                    return false;
                }
                if ($(this).is(':hover')) {
                    $(this).addClass("active");
                    function active(event) {
                        if (event.prev()[0] != undefined) {
                            event.prev().addClass("active");
                            active(event.prev());
                        }
                    }

                    function deactive(event) {
                        if (event.next()[0] != undefined) {
                            event.next().removeClass("active");
                            deactive(event.next());
                        }
                    }

                    active($(this));
                    deactive($(this));
                }
            });

    //4-onmouse live rating ul, повертає рейтинг в початкове значення
    $body.on(
            'mouseleave',
            '.rating-small, .coments-form .rating, .write-review .rating',
            function () {
                if ($(this).hasClass('available')) {
                    var rating = $(this).data('rating');
                    $(this).children('li')
                            .removeClass('active')
                            .each(function (index) {
                                if (index < rating) {
                                    $(this).addClass('active');
                                } else {
                                    return false;
                                }
                            }
                            );
                }
            });

    //4-оброка кліка по рейтингу (деактивуєм) на сторінці о компании
    $('.company .coments-form ').on('click', '.rating.available', function () {
        $(this).removeClass("available");
    });

    //4-оброка кліка по rating stars на гс в слайдері брендів
    $('.brand-item').on('click', '.rating-small', function () {
        if ($(this).hasClass('available')) {
            var rating = $(this).find('li.active').length,
                    id = $(this).closest('.brand-item').attr('rel');
            $.getJSON(
                    '/modules/commodities/ajax/rating.php',
                    {
                        type: 'brand',
                        id: id,
                        score: rating
                    }
            );
            $(this).removeClass('available');
        }
    });

    //4-bind click event, on rating stars
    $body.on(
            'click',
            '.tab-head .rating li, .commodity-one .rating-small.available, .catalog-conteiner .rating-small.available,' +
            '.profile-wishes-container .rating-small.available',
            function () {
                if ($(this).hasClass('available')) {
                    var rating = $(this).find('li.active').length;
                    $('.write-review .rating li').removeClass('active').each(function (index) {
                        if (index < rating) {
                            $(this).addClass('active');
                        } else {
                            return;
                        }
                    }
                    );
                    if (!$(this).hasClass('rating-small')) {
                        $(this).removeClass('available');
                    }
                } else {
                    setTimeout(function () {
                        $.magnificPopup.close();
                    }, 1);
                }
                $(this).children('li').each(function (index) {
                    if (index < rating) {
                        $(this).addClass('active');
                    } else {
                        return;
                    }
                });
                if ($(this).parent('ul').hasClass('available')) {
                    $(this).parent('ul').removeClass('available');
                }
            });

    //4-hover слайдер брендов
    var isPopup;
    $('.brand-item').live({
        mouseenter: function () {
            var thisBrand = $(this);
            isPopup = false;
            addOpenClass(thisBrand);
            thisBrand.addClass('hovered');
            $('.about-brand').on('click', function () {
                return isPopup = true;
            });
        },
        mouseleave: function () {
            if (!isPopup) {
                var thisBrand = $(this);
                thisBrand.removeClass('hovered');
            }
        }
    });

    //4-попап вибору категорій, вибору брендів
    $body.on('click', function (event) {
        var event = event || window.event;
        var ET = event.target || event.srcElement;
        if ($(ET).hasClass("link-categoties")) {

            //видаляє попередні категрії
            $('.item-wrap .super_item').remove();

            // в залежності від поточної сторінки,
            if ($(ET).next().data('id') === undefined) {

                //якщо сторінка одежда товар, то передаєм ще url
                var link = $(ET)
                        .closest('.catalog_item')
                        .find('.catalog_image>a')
                        .attr('href');
                setBrandCategories(
                        $(ET).data('id'),
                        true,
                        link
                        );
            } else {

                //якщо сторінка брендів або головна
                var link = $(ET)
                        .closest('.brand-item')
                        .children('a')
                        .attr('href');
                setBrandCategories(
                        $(ET).next().data('id'),
                        false,
                        link
                        );
            }

            $(".super_nav_1").slideUp(300, function () {
                var elementCoords = $(ET).offset();
                if ($("body").width() < 520) {
                    if (elementCoords.left < 57) {
                        $(".super_nav_1").css({"top": (elementCoords.top + 22), "left": 12}).slideDown(300);
                    } else if (elementCoords.left > ($("body").width() - 212)) {
                        $(".super_nav_1").css({"top": (elementCoords.top + 22), "left": ($("body").width() - 257)}).slideDown(300);
                    } else {
                        $(".super_nav_1").css({"top": (elementCoords.top + 22), "left": (elementCoords.left - 45)}).slideDown(300);
                    }
                } else {
                    if (elementCoords.left < 170) {
                        $(".super_nav_1").css({"top": (elementCoords.top + 22), "left": 12}).slideDown(300);
                    } else if (elementCoords.left > ($("body").width() - 344)) {
                        $(".super_nav_1").css({"top": (elementCoords.top + 22), "left": ($("body").width() - 502)}).slideDown(300);
                    } else {
                        $(".super_nav_1").css({"top": (elementCoords.top + 22), "left": (elementCoords.left - 158)}).slideDown(300);
                    }
                }
            });
        } else if ($(ET).closest(".super_nav_1").length)
            return;
        else if ($(ET).hasClass("mfp-content")) {
            return;
        } else {
            $(".super_nav_1").slideUp(300);
            $(".brand-item").removeClass("opened");
        }
        //event.stopPropagation();
    });

    //4-выделить все и очистить все в категория в слайдере брендов
    $(".super_seting .choose-all").click(function () {
        $(".super_item input").attr("checked", true);
    });
    $(".super_seting .clear-all").click(function () {
        $(".super_item input").attr("checked", false);
    });

    //переключение рекомендации и недавно просмотренные в карточке товара
    $(".recomended-title span").click(function () {
        $(".recomended-title span").removeClass("active");
        $(this).addClass("active");
        var sliderType = $(this).attr("data-slider");
        $(".recomended-wrap .in-prod-page").each(function () {
            if ($(this).hasClass(sliderType)) {
                $(this).show();
                $(this).css({"z-index": "1", "position": "relative", "overflow": "visible"});
            } else {
                $(this).hide();
            }
        });
    });
    setTimeout(function () {
        $(".last-seeing").css({"z-index": "-1", "position": "absolute", "overflow": "hidden"});
    }, 2000);

    //4-get brand info
    $('.about-brand').click(function () {
        var id = $(this).data('id');
        $.ajax({
            url: "/modules/commodities/ajax/get_brand_info.php",
            type: "GET",
            data: {'id': id},
            dataType: "json",
            success: function (result) {
                if (!result.error) {
                    $('#aboutBrand .text-wrap').html(result.text);
                }
            }
        });
    });


    //poupup like - OK button
    $('#wishesPopup').on('click', '.btn', function () {
        $.magnificPopup.close();
    });

    //4-like
    $body.on('click', ".like_event, .product__like", function (e) {
        e.preventDefault();
        var link = $(this),
                linkNumber = $(this).parent().prev().find('a'),
                id = link.data('id'),
                type = link.data('type');

        if (link.children('i').hasClass('active')) {
            $('#wishesPopup .popup-title p').text('Удалено из моих желаний');
        } else {
            $('#wishesPopup .popup-title p').text('ДОБАВЛЕНО В МОИ ЖЕЛАНИЯ');
        }

        $.ajax({
            url: "/modules/commodities/ajax/like.php",
            type: "POST",
            data: {
                id: id,
                type: type
            },
            dataType: "json",
            success: function (data) {

                //если на сервере не произойло ошибки то обновляем количество лайков на странице
                if (data.success === 1) {
                    if (data.active) {

                        // меняем  лайк в обычное состоянее
                        link.children('i').removeClass('active');
                        linkNumber.children('i').removeClass('active');
                        if (type == 'c') {
                            getMyWish();
                        }
                    } else {

                        // помечаем лайк как "понравившийся"
                        link.children('i').addClass('active');
                        linkNumber.children('i').addClass('active');
                        if (type == 'c') {
                            getMyWish();
                        }
                    }
                    if (type == 'b') {
                        $('.number span', link.offsetParent()).text(data.count);
                    }
                } else {
                    //alert(result.message);
                }
            }
        });
    });

    //4-мои желания редирект в лк
    $('.tech-info__item-desire').on('click', '.out', function (e) {
        e.preventDefault();
        if ($('#username').length) {
            location.href = '/myaccount/wish/';
        } else {
            magnificPopupOpenError('Вы не зарегистрированы,' +
                    'для того чтобы перейти в личный кабинет, ' +
                    'пожалуйста, зарегистрируйтесь.');
        }
    });

    //4-мои желания вибрать все в випадающем списке
    $body.on('click', '.choose-all', function (e) {
        e.preventDefault();
        $('.wish_check').attr('checked', 'checked');
    });

    //4-мои желания снять видиления в випд. спике
    $body.on('click', '.remove-selection', function (e) {
        e.preventDefault();
        $('.wish_check').removeAttr('checked');
    });

    //мои желания удалить виделение в вип. списке
    $body.on('click', '.but_del_wish', function () {
        var comIds = '0';
        $('.wish_check').each(function () {
            if ($(this).prop('checked')) {
                var id = $(this).attr('rel');
                $('#pro_like' + id + ' i').removeClass('active');
                comIds += ',' + id;
            }
        });
        comIds = comIds.replace('0,', '');
        getMyWish(comIds);
    });

    //4-popup следить за ценой
    $('.watch-price').magnificPopup({
        removalDelay: 500,
        callbacks: {
            beforeOpen: function () {

                var $popup = $('#watchPrice'),
                        email = $popup.find('.message-mail').text();
                if (this.st.el.closest('li').hasClass('checked')) {
                    if (email.length > 0) {
                        $popup.find('.popup-title')
                                .show()
                                .children('p')
                                .text('Удалено из листа наблюдений');
                        $popup.find('.message').hide();
                        unWatchPrice(email);
                    }
                } else {
                    if (email.length > 0) {
                        $popup.find('.popup-title p').text('Добавлено в лист наблюденний');
                        $popup.find('.message:first-of-type').show();
                        $popup.find('.message:nth-of-type(2)').hide();
                        watchPrice(email);
                    } else {
                        $popup.find('.message:first-of-type, .popup-title').hide();
                        $popup.find('.message:nth-of-type(2)').show();
                    }
                }
            }
        },
        midClick: true
    });

    //4-следить за ценой - ОК button
    $('#watchPrice').on('click', 'button[type="submit"]', function () {
        var $email = $(this).closest('#watchPrice').find('input');
        if ($email.is(':visible')) {
            if (!validateEmail($email.val())) {
                showError($email, true)
                return;
            }
            $('.message-mail').text($email.val());
            watchPrice($email.val());
        }
        $.magnificPopup.close();
    });

    //редирект похожие товар в карточке товара
    $(".one-similar-wrap").click(function () {
        var href = $(this).attr("data-href");
        $(location).attr("href", "http://" + window.location.hostname + href);
    });

    //init tooltip for login/registration form fields
    $('.tech-info__item-enter .form-enter input, footer .input-wrap input').tooltip({
        disabled: true,
        tooltipClass: 'custom-error-styling',
    });

    //disable tooltip on click for login/registration form fields
    $('.tech-info__item-enter .form-enter').on('click', 'input', function () {
        $(this).tooltip('disable').tooltip('close');
    });

    //4-registration keys 'enter/escape'
    $('.tech-info__item-enter .form-sign-up').on('keyup', 'input', function (e) {
        if (e.keyCode == 13) {
            $('.tech-info__item-enter .form-enter .but_registr').click();
        } else if (27 == e.keyCode) {
            var $popTitle = $(this).closest('.pop-title');
            $popTitle.find('.custom-hidden-2').removeClass('active');
            $popTitle.find('a').removeClass('active');
        }
    });

    //4-registration
    $('.tech-info__item-enter .form-enter').on('click', '.but_registr', function (e) {
        e.preventDefault();
        var $email = $('#r_email'),
                $pass = $('#r_pass'),
                $pass2 = $('#r_pass2'),
                $firstName = $('#r-first-name'),
                $lastName = $('#r-last-name'),
                $radio = $('.row__radio').find('input'),
                usersStatus = '';

        $radio.each(function () {
            if ($(this).is(':checked')) {
                usersStatus = $(this).val();
            }
        });
        if ($firstName.val() === '') {
            showError($firstName);
            showErrorTooltip($firstName, 'Поле "Имя" не должно быть пустым');
            return false;
        }
        if ($lastName.val() === '') {
            showErrorTooltip($lastName, 'Поле "Фамилия" не должно быть пустым');
            showError($lastName);
            return false;
        }
        if (!validateEmail($email.val())) {
            showError($email, true);
            showErrorTooltip($email, 'Неверный email');
            return false;
        }
        if ($pass.val().length < 5) {
            showErrorTooltip($pass, 'Пароль должен содержать не менее 5 символов');
            showError($pass);
            return false;
        }
        if ($pass.val().length < 5) {
            showErrorTooltip($pass2, 'Пароль должен содержать не менее 5 символов');
            showError($pass2);
            return false;
        }
        if ($pass.val() !== $pass2.val()) {
            showErrorTooltip($pass, 'Пароли не совпадают');
            showError($pass2, true);
            showError($pass, true);
            return false;
        }
        //var check = $("#agree").prop("checked");
        showLoading();
        if ($('.custom-hidden-2').hasClass('active')) {
            $('.custom-hidden-2').removeClass('active');
            $('.pop-title a').removeClass('active');
        }
        $.ajax({
            method: 'POST',
            url: '/modules/users/ajax/registration.php',
            dataType: 'json',
            data: {
                first_name: $firstName.val(),
                last_name: $lastName.val(),
                email: $email.val(),
                pass: $pass.val(),
                status: usersStatus,
                action: 'register',
                'g-recaptcha-response': grecaptcha.getResponse()
            }
        })
                .done(function (data) {
                    hideLoading();
                    magnificPopupOpenError(data.message);
                    if (1 === data.success) {
                        $firstName.closest('form').trigger('reset');
                    }
                })
                .fail(function () {
                    hideLoading();
                });
    });

    //4-login keys 'enter/escape'
    $('.tech-info__item-enter .form-sign-in').on('keyup', 'input', function (e) {
        if (e.keyCode == 13) {
            $('.tech-info__item-enter .form-enter .but_sign').click();
        } else if (27 == e.keyCode) {
            var $popTitle = $(this).closest('.pop-title');
            $popTitle.find('.custom-hidden-2').removeClass('active');
            $popTitle.find('a').removeClass('active');
        }
    });

    //4-login
    $('.tech-info__item-enter .form-enter').on('click', '.but_sign', function () {
        var $email = $('#s_email'),
                $pass = $('#s_pass'),
                check = 0;

        if (!validateEmail($email.val())) {
            showError($email, true);
            showErrorTooltip($email, 'Неверный email')
            return false;
        }

        if ($pass.val() == '' || $pass.val().length < 5) {
            showError($pass, true);
            showErrorTooltip($pass, 'Пароль должен содержать не менее 5 символов');
            return false;
        }

        if ($('#save').prop('checked')) {
            check = 1;
        }
        showLoading();
        $.ajax({
            method: 'POST',
            url: '/modules/users/ajax/registration.php',
            dataType: 'json',
            data: {
                email: $email.val(),
                pass: $pass.val(),
                remember_me: check,
                action: 'sign_in'
            }
        })
                .done(function (data) {
                    if (1 === data.success) {
                        location.reload();
                    } else {
                        hideLoading();
                        magnificPopupOpenError(data.message);
                    }
                })
                .fail(function () {
                    hideLoading();
                });
    });

    //4-logout
    $('#s_quit').click(function () {
        $.ajax({
            method: 'GET',
            url: '/modules/users/ajax/registration.php',
            data: {action: 'quit'},
            dataType: 'json',
            success: function (data) {
                if (data.success === 1) {
                    $(location).attr('href', 'http://' + window.location.hostname);
                }
            }
        });
    });


//==============================================================================
//                             Макса
//==============================================================================
    //Макса искать бренд на главной странице в строке брендов
    $(".brand-search input").keyup(function (event) {
        if (event.which != 38 && event.which != 40 && event.which != 13) {
            var ss = $(this).val();
            // alert(ss);
            $.ajax({
                method: "GET",
                dataType: "json",
                url: "modules/commodities/ajax/sea_brenda.php",
                data: {sea: ss}
            })
                    .done(function (ddd) {
                        var txt = "";
                        for (i = 0; i < ddd.length; i++) {
                            var ee = ddd[i]['cat_name'].replace(/ /g, "%");
                            txt += "<span class='sel_brenda' data-change='select" + i + "' id='change_sel" + ddd[i]["cat_id"] + "' rel=" + ddd[i]["cat_id"] + " rel2=" + ee + " >" + ddd[i]['cat_name'] + "</span><br>";
                        }
                        window.onload = function () {
                            ruun();
                        };
                        if (ss == "") {
                            $("#sea_txt").html("");
                            $("#sea_txt").hide();
                        } else {
                            $("#sea_txt").html(txt);
                            $("#sea_txt").show();
                        }
                    });
            $("#sea_txt").attr("rel", -1);
        }
        // alert(event.which);
        if (event.which == 38) {
            keyUpDown(1);
        }
        if (event.which == 40) {
            keyUpDown(2);
        }
        if (event.which == 13) {
            $(".sel_brenda").each(function () {
                if ($(this).hasClass("active_sel_brenda")) {
                    $(".brand-search input").val($(this).text());
                    $("#sea_txt").css({"display": "none"});
                }
            });
            var rel = $(".search-btn").attr("rel");
            $(".brands-slider").empty();
            $(".brands-slider").removeClass("slick-initialized");
            $(".brands-slider").removeClass("slick-slider");
            push_html_brands(rel, 0, 1);
            runBrandsSlick();
            if ($(".brand-search input").val() == "") {
                get_ajax("a_all");
            }
        }
    });

    // макс кнопка поиска бренда
    $(".search-btn").click(function () {
        var rel = $(this).attr("rel");
        $(".brands-slider").empty();
        $(".brands-slider").removeClass("slick-initialized");
        $(".brands-slider").removeClass("slick-slider");
        push_html_brands(rel, 0, 1, 0);
        runBrandsSlick();
        $(".a_href").removeClass("active-push");
        if ($(".brand-search input").val() == "") {
            get_ajax("a_all");
        }
    });

    $(".brand-search input").click(function () {
        $(this).css({"color": "black"});
        clear_brends();
        if ($(this).val() == "") {
            get_ajax("a_all");
        }
        $(".a_href").removeClass("active-push");
    });
    $("#sea_txt").click(function (event) {
        var e = event.target.id;
        var e2 = $("#" + e).attr("rel2");
        var ee = e2.replace(/%/g, " ");
        $(".brand-search input").val(ee);
        $("#sea_txt").hide();
        var r = $("#" + e).attr("rel");
        $(".search-btn").attr("rel", r);
    });
    $(".icon-favorite, .icon-star-circle").css({"cursor": "pointer"});
    $(".icon-favorite").click(function () {
        get_ajax("like");
        clear_brends();
        $(".a_href").removeClass("active-push");
    });
    $(".icon-star-circle").click(function () {
        get_ajax("star");
        clear_brends();
        $(".a_href").removeClass("active-push");
    });

    $(".a_href").click(function () {
        var s = $(this).text();
        if ($(this).hasClass("active-push")) {
            $(this).removeClass("active-push");
            $(".brand-item").css({"display": "block"});
            $(".brands-slider .slick-slide").css({"display": "block"});
        } else {
            $(".a_href").removeClass("active-push");
            $(this).addClass("active-push");
            var rel = $(this).attr("rel");
            get_ajax(rel);
        }
        clear_brends();
    });
});

//==============================================================================
//                          Функции
//==============================================================================

//получаем курс
function getCurrency() {
    $.ajax({
        url: "/modules/content/ajax/get_currency.php",
        type: 'GET',
        dataType: 'HTML'})
            .done(function (data) {
                if (data) {
                    $('.top-popups .currency-wrap .d-table a').remove();
                    $('.top-popups .currency-wrap .d-table').append(data);
                } else {
                    magnificPopupOpenError('Во время загрузки валюты произошла ошибка!');
                }
            }).fail(function () {
        magnificPopupOpenError('Сервер не отвечает!');
    });
}

//main menu бренды категории не для мобил
function brandDesctop() {
    if ($(window).outerWidth() >= 767) {
        brands();
        $(".show-more").click(function () {
            $(this).parent().parent().addClass("one-column");
            var counterNum = $(this).parent().addClass("opened").find("li");
            brands(counterNum);
        });
        $(".back").click(function () {
            $(this).parent().parent().removeClass("one-column");
            $(".brands-names").removeClass("opened").show(100);
            $(".show-more").show();
            $(".back").hide();
            var counterNum = undefined;
            brands(counterNum);
            var parentCol = $(this);
            setTimeout(function () {
                parentCol.parent().parent().removeClass("one-column");
            }, 10);
        });
        $('header .top-menu ul .first-level').on('mouseleave', function () {
            $(this).children('div').children('div').removeClass("one-column");
            $(".brands-names").removeClass("opened").show(100);
            $(".show-more").show();
            $(".back").hide();
            var counterNum = undefined;
            brands(counterNum);
        });
    }
}

//вспомогательная функция к brandDesctop()
function brands(counterNum) {
    var counter;
    $(".brands-names").each(function () {
        var brandCount;
        if (counterNum == undefined) {
            counter = 8;
            brandCount = $(this).find("li").hide();
            if (brandCount.length <= 8) {
                $(this).find(".show-more").hide();
                $(this).find(".show-more-empty").show();
            }
        } else {
            counter = counterNum.length;
            brandCount = counterNum;
            if ($(this).hasClass("opened")) {
                $(this).find(".back").show();
                $(this).find(".show-more").hide();
            } else {
                $(this).hide(100);
                $(this).find(".show-more").show();
                $(this).find(".show-more-empty").hide();
            }
        }
        for (var i = 0; i < counter; i++) {
            $(brandCount[i]).show();
        }
    });
}

//init popups from commondity block(slider, catalog)
function initPopupsFromComBlock() {

    //popup Быстрый заказ
    $('.fast-order').magnificPopup({
        removalDelay: 500, //delay removal by X to allow out-animation
        callbacks: {
            beforeOpen: function () {

                //bootstrap options
                $("#fast-order-country-radio").buttonset();
                var properties = {
                    'basket': 'selectCountryAndCheckout',
                    'country': $('#fast-order-country-radio input:checked').val()};
                selectCountryAndCheckoutFastOrder(properties);
                $('#confirmation-page-conteiner').remove();

                this.st.mainClass = this.st.el.attr('data-effect');
                var quantity = 1;
                $('#fast-order-color, #fast-order-size').addClass('hidden');

                //for full com
                if (this.st.el.hasClass('quick-add')) {
                    var id = $(".write-review ul.rating").data('id');
                    if ($('.counter-group input').val().length && validateQuantityInt($('.counter-group input').val()) > 0) {
                        quantity = $('.counter-group input').val();
                    }
                    var price = $(".rozn-price b").text(),
                            optPrice = $(".opt-price b").text();
                    if (quantity > 9 && optPrice !== "0") {
                        price = optPrice;
                    }
                    var colorLength = $(".col-md-5 select.color-set option").length,
                            sizeLength = $(".col-md-5 select.sel_size option").length,
                            color = $(".col-md-5 select.color-set").find(':selected').val(),
                            size = $(".col-md-5 select.sel_size").find(':selected').val(),
                            src = $('.small-slide-img img').attr('src'),
                            brandName = $('.open-inset1 p:nth-child(1)').text().replace('Бренд:', ''),
                            productName = $('.product-head .title h3').text();
                } else {

                    //якщо з слайдера
                    var id = this.st.el.data('id'), price = 0;
                    if ($("#count_" + id).length && validateQuantityInt($("#count_" + id).val()) > 0) {
                        quantity = $("#count_" + id).val();
                    }
                    var price = $("#pri_rozn_" + id).text(),
                            optPrice = $("#pri_opt_" + id).text();
                    if (quantity > 9 && optPrice !== "0") {
                        price = optPrice;
                    }
                    var sizeLength = this.st.el.parent().find('.sel_size option').length,
                            colorLength = this.st.el.parent().find('.color-set option').length,
                            color = this.st.el.parent().find('.color-set :selected').val(),
                            size = this.st.el.parent().find('.sel_size :selected').val(),
                            src = this.st.el.closest('.product').find('.product__image img').attr('src'),
                            brandName = this.st.el.closest('.product__info').find('p.title').text(),
                            productName = this.st.el.closest('.product__info').find('.column:nth-child(1)>p:nth-child(1)').text();
                }

                // якщо є селект для кольору то провіряєм чи колір вибрано.
                if (colorLength > 1) {
                    if (color.length === 0) {
                        $('#fastOrder #fast-order-title, #fastOrder form, #fastOrder #fast-order-product').hide();
                        if (!$('#no-size-color').length) {
                            $('#fastOrder .custom-border').append('<p id="no-size-color" class="text-center">Цвет не выбран</p>');
                        }
                        return false;
                    } else {
                        $('#fast-order-color').removeClass('hidden');
                        $('#fast-order-color span').text(color);
                    }
                }

                // якщо є селект для розміру то провіряєм чи розмір вибрано.
                if (sizeLength > 0) {
                    if (size.length === 0) {
                        $('#fastOrder #fast-order-title, #fastOrder form, #fastOrder #fast-order-product').hide();
                        if (!$('#no-size-color').length) {
                            $('#fastOrder .custom-border').append('<p id="no-size-color" class="text-center">Размер не выбран</p>');
                        }
                        return false;
                    } else {
                        $('#fast-order-size').removeClass('hidden');
                        $('#fast-order-size span').text(size);
                    }
                }
                //Быстрый заказ только для одной ед. если закоментировать будет для большего количества
                quantity = 1;

                $("#fast-order-submit").attr("rel", id);
                $("#fast-order-price b").text(price * quantity);
                $("#fast-order-price b").attr("rel", price);
                $("#fast-order-count span").text(quantity);
                $("#fast-order-count").attr("rel", quantity);
                $('#fast-order-image').attr('src', src);
                $('#fast-order-brend').text(brandName);
                $('#fast-order-name').text(productName);
            },
            afterClose: function () {
                $('#fastOrder #no-size-color').remove();
                $('#fastOrder #fast-order-title, #fastOrder form, #fastOrder #fast-order-product').show();
            }
        },
        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    });

    //popup отзыв
    $('.review-popup').magnificPopup({
        removalDelay: 500, //delay removal by X to allow out-animation
        callbacks: {
            beforeOpen: function () {
                this.st.mainClass = this.st.el.attr('data-effect');
            }
        },
        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    });

    //popup лайка
    $('.product__like').magnificPopup({
        removalDelay: 500, //delay removal by X to allow out-animation
        callbacks: {
            beforeOpen: function () {
                this.st.mainClass = this.st.el.attr('data-effect');
            },
            afterClose: function () {
                var $contentBlock = this.st.el.closest('.content_block');
                if (this.st.el.closest('#pjax-profile-content').length) {
                    this.st.el.closest('.commodity-one').remove();
                }
                if (0 === $contentBlock.find('.commodity-one').length) {
                    $contentBlock.append('<p id="empty-wish">СПИСОК ЖЕЛАНИЙ ПУСТ</p>');
                }
            }
        },
        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    });

    //poupup мои желания карточка товара
    $('.icon-2').magnificPopup({
        removalDelay: 500,
        callbacks: {
            beforeOpen: function () {
                var _this = this.st.el;
                this.st.mainClass = _this.attr('data-effect');

                if (_this.parent('li').hasClass('checked')) {
                    $('#wishesPopup .popup-title p').text('Удалено из моих желаний');
                } else {
                    $('#wishesPopup .popup-title p').text('ДОБАВЛЕНО В МОИ ЖЕЛАНИЯ');
                }
                $.ajax({
                    url: '/modules/commodities/ajax/like.php',
                    type: 'POST',
                    data: {
                        id: $('.product-info .rating').data('id'),
                        type: 'c'
                    },
                    dataType: 'json'
                })
                        .done(function (data) {
                            if (data.success === 1) {
                                if (data.active) {
                                    _this.text('в мои желания');
                                    _this.closest('li').removeClass('checked');
                                    getMyWish();
                                } else {
                                    _this.text('добавлено');
                                    _this.closest('li').addClass('checked');
                                    getMyWish();
                                }
                            }
                        });
            }
        },
        midClick: true
    });

    //popup добовление в корзину
    $('.add-to-cart').magnificPopup({
        removalDelay: 500,
        callbacks: {
            beforeOpen: function () {

                //перед тим як popup появиься ініціалізуєм дані та добавляєм товар в корзину ajaxom.
                var quantity = 1;
                if (this.st.el.find('i').length == 0) {

                    //якщо карточка товара
                    var id = $(".write-review ul.rating").data('id');
                    if ($('.counter-group input').val().length && validateQuantityInt($('.counter-group input').val()) > 0) {
                        quantity = $('.counter-group input').val();
                    }
                    var summ = quantity * $('.rozn-price b').text();
                    var colorLength = $(".col-md-5 select.color-set option").length;
                    var sizeLength = $(".col-md-5 select.sel_size option").length;
                    var color = $(".col-md-5 select.color-set").find(':selected').val();
                    var size = $(".col-md-5 select.sel_size").find(':selected').val();
                } else if (this.st.el.find('i').length == 1) {

                    //якщо з слайдера
                    var id = this.st.el.data('id');
                    if (this.st.el.parent().prev().find('input').length && validateQuantityInt(this.st.el.parent().prev().find('input').val()) > 0) {
                        quantity = this.st.el.parent().prev().find('input').val();
                    }
                    var summ = quantity * this.st.el.parents('.product__info__bottom').prev().find('.column:nth-child(2) p:nth-child(2) b').text();
                    var sizeLength = this.st.el.parent().prev().find('.sel_size option').length;
                    var colorLength = this.st.el.parent().prev().find('.color-set option').length;
                    var color = this.st.el.parent().prev().find('.color-set :selected').val();
                    var size = this.st.el.parent().prev().find('.sel_size :selected').val();
                }
                $('#addToCartPopup .d-table-cell:nth-child(2) b').text(quantity);
                $('#addToCartPopup .d-table-cell:nth-child(3) b').text(summ);
                var properties = {
                    'basket': 'addtobasket',
                    'itemcount': quantity,
                    'basket_com_id': id
                };

                // якщо є селект для кольору то провіряєм чи колір вибрано.
                if (colorLength > 1) {
                    if (color.length == 0) {
                        $('#addToCartPopup .custom-border div, #addToCartPopup .custom-border button, #addToCartPopup .custom-border a').hide();
                        $('#addToCartPopup .custom-border').append('<p>Цвет не выбран</p>');
                        return;
                    } else {
                        properties.color = color;
                    }
                }

                // якщо є селект для розміру то провіряєм чи розмір вибрано.
                if (sizeLength > 0) {
                    if (size.length == 0) {
                        $('#addToCartPopup .custom-border div, #addToCartPopup .custom-border button, #addToCartPopup .custom-border a').hide();
                        $('#addToCartPopup .custom-border').append('<p>Размер не выбран</p>');
                        return;
                    } else {
                        properties.size = size;
                    }
                }

                //якщо з слайдера то сетим дані для popup
                if (this.st.el.find('i').length == 1) {
                    $('#addToCartPopup .d-table-cell p').text(this.st.el.parents('.product__info__bottom').prev().children('.title').text());
                    $('#addToCartPopup  span').text(
                            this.st.el.parents('.product__info__bottom').prev().find('.column:nth-child(1) p:nth-child(1)').text()
                            + ' ' + this.st.el.parents('.product__info__bottom').prev().find('.column:nth-child(1) p:nth-child(2)').text()

                            );
                }
                addToBasket(properties);
            },
            afterClose: function () {
                $('#addToCartPopup .custom-border>p').remove();
                $('#addToCartPopup .custom-border div, #addToCartPopup .custom-border button, #addToCartPopup .custom-border a').show();
            }
        },
        midClick: true,
        closeOnContentClick: true
    });
}

// init слайдер брендов
function runBrandsSlick() {
    if (!isMobile.any()) {
        $('.brands-slider').slick({
            slidesToShow: 6,
            slidesToScroll: 6,
            autoplay: false,
            autoplaySpeed: 4000,
            dots: true,
            responsive: [
                {
                    breakpoint: 1234,
                    settings: {
                        slidesToScroll: 5,
                        variableWidth: true,
                        infinite: true
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToScroll: 4,
                        variableWidth: true,
                        infinite: true
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        centerMode: true,
                        variableWidth: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        centerMode: true,
                        variableWidth: true,
                        dots: false
                    }
                }
            ]
        });
    } else {
        $('.brands-slider, .brands').addClass('hidden');
    }
}

//проверяем заполненые ли обязательные поля для активации кнопки
function checkNecessaryFieldsForComments($btnComForm, $thisComForm) {

    //проверяем заполненые поля отзыва
    var sizeEmpty = $thisComForm.find('.comment-necessary').size(), i = 0;
    $thisComForm.find('.comment-necessary').each(function () {
        if ($(this).val() !== '') {
            i += 1;
        }
    });
    if (i === sizeEmpty) {
        $btnComForm.removeAttr("disabled");
    } else {
        $btnComForm.attr('disabled', "disabled");
    }

    //проверяем заполненые поля ответить
    var sizeEmptyAnswer = $thisComForm.find('.answer-necessary').size(), i = 0;
    $thisComForm.find('.answer-necessary').each(function () {
        if ($(this).val() !== '') {
            i += 1;
        }
    });
    if (i === sizeEmptyAnswer) {
        $thisComForm.find('.btn-answer-form').removeAttr("disabled");
    } else {
        $thisComForm.find('.btn-answer-form').attr('disabled', "disabled");
    }
}

//open categories from brands slider
function addOpenClass(thisBrand) {
    thisBrand.find(".link-categoties").click(function () {
        $(".brand-item").removeClass("opened");
        thisBrand.addClass("opened");
    });
}

//check and hide opt price if it equal 0 or rozn price
function checkAndHideOptPrices() {

    //for com full
    var productPath = window.location.pathname.match('^\/product\/[0-9]{3,4}');
    if (productPath) {
        var optPrice = $('.product-body .opt-price b').text();
        var roznPrice = $('.product-body .rozn-price b').text();
        if (optPrice == 0 || optPrice == roznPrice) {
            $('.product-body .opt-price b').parents('.d-table-row').hide();
        }
        if (roznPrice == 0) {
            $('.product-body .rozn-price b').parents('.d-table-row').hide();
        }
    }

    //for com block
    $('.product__info__top').find('.column:nth-child(2) p:first-child').each(function () {
        var rozn = $(this).next().children('b').text();
        if ($.inArray($(this).children('b').text(), [0, rozn, '0']) >= 0) {
            $(this).hide();
        }
        if (rozn == 0) {
            $(this).next().hide();
        }
    });

    // for similar com on com full
    $('.one-similar-wrap').each(function () {
        var optPrice = $(this).find('.green-text').text();
        if (optPrice === '0' || optPrice === $(this).find('div:nth-child(2) > div:nth-child(2) > div:nth-child(2) > div:nth-child(2) b').text()) {
            $(this).find('.green-text').closest('.d-table-cell').hide();
        }
    });

    // check and hide new stiker
    $('.stiker-new').each(function () {
        var stikerNew = $(this).closest('.stickers').data('hide');
        if (stikerNew === 0) {
            $(this).hide();
        }
    });

    // check and hide gift stiker
    $('.stiker-gift').each(function () {
        var stikerGift = $(this).closest('.stickers').data('hide'),
                stikerNew = $(this)
                .closest('.product')
                .find('.stiker-new')
                .closest('.stickers')
                .data('hide');
        if (stikerGift === 0) {
            $(this).hide();
        } else if (stikerNew === 0) {
            $(this).removeClass('top-2-posision').addClass('top-1-posision');
        }
        if (stikerGift !== 0) {
            $(this).tooltip({
                content: "<div class='custom-border-tooltip'>Добавь этот товар в заказ, сумма которого равна или больше 1000 грн и получи его в подарок</div>",
                hide: {effect: "explode", duration: 500},
                tooltipClass: "custom-tooltip-styling",
                track: true
            });
            $(this).closest('.product').find('.column').tooltip({
                content: "<div class='custom-border-tooltip'>Добавь этот товар в заказ, сумма которого равна или больше 1000 грн и получи его в подарок</div>",
                hide: {effect: "explode", duration: 500},
                tooltipClass: "custom-tooltip-styling",
                track: true
            });
        }
    });

    // check and hide save stiker
    $('.stiker-save').each(function () {
        var stikerPr = $(this).find('.stiker-text').text(),
                stikerNew = $(this)
                .closest('.product')
                .find('.stiker-new')
                .closest('.stickers')
                .data('hide'),
                stikerGift = $(this)
                .closest('.product')
                .find('.stiker-gift')
                .closest('.stickers')
                .data('hide');

        if (stikerPr === '-0%' || stikerPr === '-100%') {
            $(this).hide();
        } else if (stikerNew === 0 && stikerGift === 0) {
            $(this).removeClass('top-3-posision').addClass('top-1-posision');
        } else if ((stikerNew !== 0 && stikerGift === 0) || (stikerNew === 0 && stikerGift !== 0)) {
            $(this).removeClass('top-3-posision').addClass('top-2-posision');
        }
    });
}

//гет усіх категорій усіх брендів, запис їх в глобальну змінну
function getBrandCategories() {
    $.getJSON(
            "/modules/content/ajax/get_brand_cats.php",
            function (data) {
                if (data) {
                    $.each(data, function (j, brand) {
                        window.brandCategories[j] = brand;
                    });
                }
            }
    );
}

//гет усіх брендів усіх категорій, запис їх в глобальну змінну
function getCategoryBrands() {
    $.getJSON(
            "/modules/content/ajax/get_brand_cats.php",
            {products: 1},
            function (data) {
                if (data) {
                    $('.main-container.commodities .catalog_item a').css({visibility: 'visible'});
                    $.each(data, function (j, category) {
                        window.categoryBrands[j] = category;
                    });
                }
            }
    );
}

// обработчик табів на сторінці товарів
function tabFieldsForCategories() {

    $(".tabs-wrap-category .all-insets").hide();
    var menu = location.pathname.match('&menu=([a-z]{4,11})'),
            gender;

    if (location.pathname.match('mujsk')) {
        gender = '-men';
    } else if (location.pathname.match('detsk') ||
            location.pathname.match('dlya_malchikov') ||
            location.pathname.match('dlya_devochek')
            ) {
        gender = '-children';
    } else {
        gender = '-women';
    }

    if (menu !== null && Object.keys(menu).length > 1) {
        var insetDefault = menu[1] == 'brands' ? 'brands-all' : menu[1] + gender;
        $('.tabs-wrap-category .all-insets.' + insetDefault)
                .show()
                .addClass("active-inset");
    } else {
        $(".tabs-wrap-category .all-insets")
                .first()
                .show()
                .addClass("active-inset");
    }

    $('.main-container.commodities')
            .on(
                    'click',
                    '.tabs-wrap-category .top .my-inset, .tabs-wrap-category .side .my-inset',
                    function () {

                        $(this)
                                .addClass("active")
                                .siblings()
                                .removeClass("active");

                        var insetTop = $('.tabs-wrap-category .top .my-inset.active').attr("data-inset");
                        var insetIsOn = insetTop == 'brands-all' ?
                                '.tabs-wrap-category .' + insetTop :
                                '.tabs-wrap-category .' +
                                insetTop +
                                '-' +
                                $('.tabs-wrap-category .side .my-inset.active').attr("data-inset");

                        $(".tabs-wrap-category .active-inset")
                                .fadeOut(100, function () {
                                    $(this).removeClass("active-inset");
                                    $(insetIsOn)
                                            .fadeIn(100, function () {
                                                $(this).addClass("active-inset");
                                            });
                                });
                    });
}

//обработчик кнопки ПРИМЕНИТЬ на сторінці товар-одежда
function redirectToCatalogueByFilter() {

    $('.super_nav_1.good').on(
            'click',
            '.super_seting li:last-child a',
            function (e) {
                e.preventDefault();
                var category = '',
                        url = $('.item-wrap')
                        .data('url')
                        .slice(0, -1),
                        $checkedElements = $('.super_nav_1 .item-wrap').find('input:checked');


                /*кожний вибраний чекбокс добавляєм до фільтрів
                 і генегуєм url, якщо не вибраний жоден, то
                 залишаєм без фільтрів
                 */
                if ($checkedElements.length > 0) {
                    $checkedElements.each(function (n, element) {
                        category += ',' + $(element).val();
                    });

                    var data = '&action=filter&category=' + category.replace(',', '');
                    url += encodeURIComponent(data) + '/';
                } else {
                    url += '/';
                }

                location.href = url;
            }
    );
}

//get comments for product by ajax
function getComments($element) {
    $.getJSON(
            '/modules/comments/ajax/comments.php',
            {
                'item_id': $element.find('.rating').data('id') !== undefined ? $element.find('.rating').data('id') : 1
            },
            function (data) {
                if (data.success === 1) {
                    $element.append(data.lines);
                    $('.tabs-wrap .my-inset:nth-child(3) span').text('Отзывы (' + data.count + ')');
                    $('.rating-info a').html('<span>Оставить отзыв</span> (' + data.count + ')');
                } else {
                    $element.append(
                            '<div class="comments-wrap">' +
                            '    <p class="success-message">Ваш отзыв будет первым!</p>' +
                            '</div>'
                            );
                }
            });
}

//мои желания
function getMyWish(ids) {
    $.ajax({
        url: "/modules/commodities/ajax/get_my_wish.php",
        type: "GET",
        dataType: 'json',
        data: {
            delete: ids
        },
        success: function (data) {
            if (data.success === 1) {
                $("#my-wish").html('').prepend(data.html);
                $(".amount-wish").text(data.count);
            }
        }
    });
}

//watch
function watchPrice(email) {
    var id = $(".write-review ul.rating").data('id');
    $.ajax({
        url: "/modules/commodities/ajax/watch_price.php",
        type: "POST",
        data: {id: id, email: email, event: 'add'},
        dataType: "json",
        success: function (data) {
            if (data.success === 1) {
                $('a.icon-1').text('Добавлено').parent('li').addClass('checked');
            }
        }
    });
}

//unwatch
function unWatchPrice(email) {
    var id = $(".write-review ul.rating").data('id');
    $.ajax({
        url: "/modules/commodities/ajax/watch_price.php",
        type: "POST",
        data: {id: id, email: email, event: 'del'}, // Передаем ID нашей статьи
        dataType: "json",
        success: function (data) {
            if (data.success === 1) {
                $('a.icon-1').text('следить за ценой').parent('li').removeClass('checked');
            }
        }
    });
}

//check commodity on watch
function checkActiveWatch() {
    var email = $(".message-mail").text();
    if (email.length > 0) {
        var id = $(".write-review ul.rating").data('id');
        $.ajax({
            url: "/modules/commodities/ajax/watch_price.php",
            type: "POST",
            data: {id: id, email: email, event: 'check'},
            dataType: "json",
            success: function (data) {
                if (data.active === 1) {
                    $('a.icon-1').text('Добавлено').parent('li').addClass('checked');
                }
            }
        });
    }
}


//генерує чекбокси категорій
function setBrandCategories(id, tovary, url) {

    $('.item-wrap').data('url', url);

    if (tovary === true) {
        var items = window.categoryBrands;
    } else {
        var items = window.brandCategories;
    }

    if (items.hasOwnProperty(id)) {
        $.each(items[id], function (j, brand) {
            j++;
            $('.item-wrap').append("<div class='super_item'>"
                    + "<input value='" + brand.id + "' id='checkbox" + j + "' type='checkbox' class='checkbox'>"
                    + "<label for='checkbox" + j + "' class='label'>"
                    + brand.name + " (" + brand.count + ")</label></div>"
                    );
        });
    }
}

/**
 * Disable or anable posibylity make fast order
 *
 * @param {object} $obj
 * @param {string} href
 * @param {string} color
 * @returns {undefined}
 */
function disableAnableFastOrder($obj, href, color) {
    if ($('.product-body').length) {
        $obj
                .closest('.product-body')
                .find('.fast-order')
                .attr('href', href)
                .css({backgroundColor: color});
        $(".fast-order")
                .tooltip("close")
                .tooltip("disable");
        return false;
    }
    $obj
            .closest('.product__info__bottom')
            .find('.fast-order')
            .attr('href', href)
            .css({backgroundColor: color});
    $(".fast-order")
            .tooltip("close")
            .tooltip("disable");
}
//==============================================================================
//                   Вспомогательные функции
//==============================================================================


// Функция ошибки popup
function magnificPopupOpenError(text) {
    $.magnificPopup.open({
        items: {
            src: '<div id="white-popup"><div class="custom-border-tooltip">' + text + '</div></div>',
            type: 'inline'
        }
    });
}

//Функция валидации email
function validateEmail(email) {
    var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
    result = pattern.test(email) && email.length > 0 ? true : false;
    return result;
}

//Функция валидация форм поиска
function validateForm(el) {
    var $input = $(el).find('input:first-child');
    if ($input.val() === '') {
        showError($input);
        return false;
    }
    el.submit();
}

//Функция показа ошибок
function showError(el, clear) {
    $(el).css({'border': '1px solid #ff0000'});
    setTimeout(function () {
        $(el).css({'border': '1px solid #ccc'});
        if (clear == true) {
            $(el).val('').text('');
        }
    }, 600);
}

//Функция показа ошибок
function showErrorTooltip(el, content) {
    el
            .tooltip('option', 'content', '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> ' +
                    content)
            .tooltip('enable')
            .tooltip('open');
}

//Функция показа success
function showSuccess(el) {
    $(el).css({'border': '1px solid green'});
    setTimeout(function () {
        $(el).css({'border': '1px solid #ccc'});
    }, 600);
}

//Функция валидации количества товара
function validateQuantityInt(value) {
    var sizeCount = $('.sel_size').find(':selected').data('count'),
            result = 0;
    sizeCount = (sizeCount !== undefined) ? sizeCount : 100;

    value = parseInt(value, 10);
    if (typeof (value) === "number" && value < sizeCount && value > 0) {
        result = value;
    }
    return result;
}

//показує загрузу
function showLoading() {
    $('body').prepend('<div class="mfp-bg mfp-zoom-in mfp-ready" id="loading-spinner"></div>');
    $('.progress-loading').show();
}

//ховає загрузку
function hideLoading() {
    $('#loading-spinner').remove();
    $('.progress-loading').hide();
}

//Функция скролинга по элементу
function scrollToElem(elem, speed, top) {
    var destination = $(elem).offset().top - top;
    $('body, html').animate({scrollTop: destination}, speed); //1100 - скорость
}

//Функция strstr php
function strstr(haystack, needle, bool) {
    var pos = 0;
    haystack += '';
    pos = haystack.indexOf(needle);
    if (pos == -1) {
        return false;
    } else {
        if (bool) {
            return haystack.substr(0, pos);
        } else {
            return haystack.slice(pos);
        }
    }
}

/*============================================================================*/
//==============================================================================
//                          Функции Макса
//==============================================================================
function keyUpDown(ad) {
    var len = $("#sea_txt .sel_brenda").length;
    var ii = $("#sea_txt").attr("rel");
    if (ad == 1) {
        ii--;
    } else if (ad == 2) {
        ii++;
    }
    if (ii == len) {
        ii = 0;
    }
    if (ii <= -1) {
        ii = len - 1;
    }

    $("#sea_txt").attr("rel", ii);
    $("#sea_txt span").removeClass("active_sel_brenda");
    $("#sea_txt span[data-change='select" + ii + "']").addClass("active_sel_brenda");
    var rr = $("#sea_txt span[data-change='select" + ii + "']").attr("rel");
    $(".search-btn").attr("rel", rr);
    // $("#sea_txt").attr("rel",ii);
}

function butPerson(rerson) {
    // console.log("Person: "+rerson);
    get_ajax(rerson);
    clear_brends();
    $(".a_href").removeClass("active-push");
}
function push_html_brands(b_id, i, len, chh) {
    var ttt = "";
    ttt += '  <div class="brand-item brand-slider-' + b_id + '" rel="' + b_id + '">';
    //  ttt += '    <img src="/template/shop/image/categories/' + b_id + '/main.jpg" alt="">';
    ttt += '    <div class="info">';
    ttt += '        <div class="info__top clearfix">';
    ttt += '            <div class="info__top__column">';
    ttt += '                <p class="name catt_n' + b_id + '">';
    // ttt+=cat_name;
    ttt += '                </p>';
    ttt += '                <p class="quantity">';
    ttt += '                    Товаров: <span id="ccount' + b_id + '"></span>';
    ttt += '                </p>';
    ttt += '            </div>';
    ttt += '            <div class="info__top__column">';
    ttt += '                <p class="like">';
    ttt += '                    <a href="" class="like_event" data-id="' + b_id + '" data-type="b">';
    ttt += '                        <i class="icon icon-heart "></i>';
    ttt += '                   </a>';
    ttt += '                </p>';
    ttt += '                <p class="number nnum' + b_id + '">';
    ttt += '                    <span></span>';
    ttt += '                </p>';
    ttt += '            </div>';
    ttt += '        </div>';
    ttt += '        <div class="info__bottom">';
    ttt += '            <div class="rating-info">';
    ttt += '                <ul class="rating-small">';
    ttt += '                   <li></li><li></li><li></li><li></li><li></li>';
    ttt += '                </ul>';
    ttt += '            </div>';
    ttt += '            <div class="about">';
    ttt += '                <a class="link-categoties">';
    ttt += '                    выбрать категории';
    ttt += '                </a>';
    ttt += '                <a href="#aboutBrand" class="about-brand" data-id="' + b_id + '" data-effect="mfp-zoom-in">';
    ttt += '                </a>';
    ttt += '            </div>';
    ttt += '        </div>';
    ttt += '    </div>';
    ttt += '</div>';
    var up_t = "";
    if (len <= 6) {
        up_t += "<div>" + ttt + "</div>";
        $(".brands-slider").append(up_t);
        // if(chh=="all"){
        //     $(".brands-slider div").addClass("size_brends");
        // }
    } else {
        if (chh == "all") {
            var siz = parseInt($(".see-all-brands").attr("rel-id"));
            var ff = $(".see-all-brands").attr("rel-flag");
            //alert($(".brands-slider").attr("rel-id"));
            if (ff == 0) {
                up_t += "<div id='uu" + i + "' >" + ttt + "</div>";
                $(".brands-slider").append(up_t);
                if ((siz - 1) == i) {
                    ff = 1;
                    $(".see-all-brands").attr("rel-flag", 1);
                }
            } else {
                up_t += ttt;
                $("#uu" + (i % siz)).append(up_t);
            }
        } else {
            if ((i % 2) == 0) {
                up_t += "<div id='uu" + i + "' >" + ttt + "</div>";
                $(".brands-slider").append(up_t);
            } else {
                up_t += ttt;
                $("#uu" + (i - 1)).append(up_t);
            }
        }
    }

    $.ajax({
        method: "GET",
        url: "http://" + window.location.hostname + "/modules/commodities/ajax/sea_brenda.php",
        data: {commodity: true, rel: b_id},
        dataType: "json",
        success: function (data) {
            cat_name = data["cat_name"];
            $(".catt_n" + data["id"]).text(data["cat_name"]);
            $("#ccount" + data["id"]).text(data["count"]);
            $(".nnum" + data["id"] + " span").text(data["like"]);
            if (data["like"] > 0) {
                $(".brand-slider-" + data["id"] + " .icon-heart").addClass("active");
            }

            if (data["rating"] > 0) {
                $(".brand-slider-" + data["id"] + " .rating-small li").each(function (i) {
                    if (i < data["rating"]) {
                        $(this).addClass("active");
                    }
                });

            }

        }
    });
}

function get_ajax(chh) {
    $.ajax({
        method: "GET",
        dataType: "json",
        url: "http://" + window.location.hostname + "/modules/commodities/ajax/sea_brenda.php",
        data: {ch_sea: chh}
    }).done(function (data) {
        $(".brands-slider").empty();
        var a = 0;
        for (i = 0; i < data.length; i++) {
            // a+=","+data[i];
            push_html_brands(data[i], i, data.length, chh);
            a = i;
        }
        $(".brands-slider").removeClass("slick-initialized");
        $(".brands-slider").removeClass("slick-slider");
        runBrandsSlick();
    });
}

function clear_brends() {
    $(".brand-item").css({"display": "block"});
    $(".brands-slider .slick-slide").css({"display": "block"});
    $(".brands-category .slick-prev").css({"display": "block"});
    $(".brands-category .slick-next").css({"display": "block"});
    $(".brands-category .slick-dots").css({"display": "block"});
    $(".item_brr").remove();
}
