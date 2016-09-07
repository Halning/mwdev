$(document).ready(function () {
    var $body = $('body');

    //performance of functions
    if (window.location.pathname.match('\/c[0-9]')) {
        pagination();
        showFilters();
        getAllAfterPjax();
        setFiltersState();
        checkAndHideOptPrices();
    } else if (location.pathname.match('\/catalog')) {
        pagination();
        getAllAfterPjax();
        checkAndHideOptPrices();
    }

    //per page
    $body.on('change', '#page-commodities-count', function () {

        if (window.location.pathname.match('\/c[0-9]')) {
            getFilteredPage();
            return;
        } else if (location.pathname.match('^\/search\/')) {

            //якщо сторінка результату пошуку і перша сторінка
            // то формуєм нову строку запиту і пергружаєм pjaxom
            var url = '/search&search=' +
                    $('#search-status .search-text').text() +
                    '&search_cat_name=' +
                    $('#search-status .search-category-name').text() +
                    '&page=1&perPage=' +
                    $('#page-commodities-count').find(':selected').val();

        } else if (location.pathname.match('^\/search')) {

            //якщо сторінка результату пошуку і строка запиту вже сформована
            //то змінюєм перпейдж і пергружаєм pjaxom
            var url = strstr(location.pathname, 'page', true) +
                    'page=1&perPage=' + $('#page-commodities-count').find(':selected').val();
        } else if (location.pathname.match('\/catalog')) {

            var url = location.pathname.match('\/catalog\/[a-z-]+') +
                    '/page/=' + $('#page-commodities-count').find(':selected').val();
            $.pjax({url: url, container: '#pjax-conteiner-main', timeout: 20000});
            return;
        }


        $('.pagination').empty();
        $('.pagination').removeData('twbs-pagination');
        $('.pagination').unbind('page');
        $.pjax({url: url, container: '#pjax-container', timeout: 20000});
    });

    //filters color
    $body.on('click', '.selection-color', function (e) {
        $('.color-box').each(function (e) {
            if (e > 3) {
                $(this).toggle();
            }
        });
    });

    //filters size
    $body.on('click', '.selection-size', function (e) {
        var boxWidthAll = 0, boxWidth = "";
        $('.size-box').each(function (e) {
            boxWidth = parseInt($(this).css('width'));
            boxWidthAll += boxWidth;
            if (boxWidthAll > 110) {
                $(this).toggle();
            }
        });
    });

    //filters size
    $body.on('click', '.size-box', function () {
        if ($(this).hasClass('checked')) {
            $(this).removeClass('checked');
        } else {
            $(this).addClass('checked');
        }
        $('.size-box').each(function (e) {
            var boxWidthAll = 0, boxWidth = "";
            $('.size-box').each(function (e) {
                boxWidth = parseInt($(this).css('width'));
                boxWidthAll += boxWidth;
                if (boxWidthAll > 110) {
                    $(this).toggle();
                }
            });
        });
        getFilteredPage();
    });

    //filters colors
    $body.on('click', '.color-box', function () {
        $(this).toggleClass('checked');
        getFilteredPage();
    });

    //filters season and order
    $body.on('change', '.season select, .order select', function () {
        getFilteredPage();
    });

    //filters category
    $body.on('click', '.list-category *, .list-brands *', function (e) {
        e.preventDefault();
        if ($(this).closest('.checkbox').find('input').is(':checked')) {
            $(this).closest('.checkbox').find('input').removeAttr('checked');
        } else {
            $(this).closest('.checkbox').find('input').attr('checked', 'checked');
        }
        getFilteredPage();
    });

    //remove filters on filters state
    $body.on('click', '.filt-state .glyphicon-remove', function () {
        if ($(this).hasClass("price-remove")) {
            $('#slider').removeClass('checked');
            $(this)
                    .closest('.filt-state')
                    .find('span')
                    .addClass('hidden');
        } else if ($(this).hasClass("color-remove")) {
            var idRemove = $(this).data('id');
            $('.color-box.checked').each(function () {
                if (idRemove === $(this).data('id')) {
                    $(this).removeClass('checked');
                }
            });
        } else if ($(this).hasClass("size-remove")) {
            var valueRemove = $(this).data('value');
            console.log(valueRemove);
            $('.size-box.checked').each(function () {
                if (valueRemove === $(this).data('value')) {
                    $(this).removeClass('checked');
                }
            });
        } else if ($(this).hasClass("wiew-remove")) {
            var idRemove = $(this).data('id');
            console.log($(this).data('id'));
            $('.left-menu-products .category:checked').each(function () {
                if (idRemove === $(this).data('id')) {
                    $(this).removeAttr("checked");
                }
            });
            $('.left-menu-products .brands:checked').each(function () {
                if (idRemove === $(this).data('id')) {
                    $(this).removeAttr("checked");
                }
            });
        }
        getFilteredPage();
    });

    //filters categories tree toggle
    $body.on(
            'click',
            '.catalog-conteiner .catalog-left-menu .filters-toggle',
            function () {
                $(this)
                        .next('ul')
                        .toggle();
            });

    $body.on('click', '.catalog-conteiner .catalog-left-menu>.glyphicon', function () {
        $(this)
                .toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top')
                .siblings('div')
                .toggle();
    });

    /**
     * clicking imitation link for label::after bottom menu
     */
    $body.on('click', '.left-menu-products div:not(.list-brands, .list-category) label', function () {
        $(this).find('a')[0].click();
    });
});

//FUNCTIONS
//функція - читає всі вибрані фільтри на сторінці і відплавляє pjax запрос
function getFilteredPage() {
    var data = '';
    var filters = {
        action: 'filter',
        perPage: $('#page-commodities-count').find(':selected').val(),
        color: '',
        price: '',
        priceM: parseInt($("#slider").slider("option", 'min')) + '-' + parseInt($("#slider").slider("option", 'max')),
        category: '',
        season: $('.season select').find(':selected').val(),
        sizes: '',
        order: $('.filters.order select').find(':selected').val()
    };

    $('div.list-category').each(function () {
        if ($(this).find('input').is(':checked')) {
            filters.category += ',' + $(this).find('a').data('id');
        }
    });
    $('div.list-brands').each(function () {
        if ($(this).find('input').is(':checked')) {
            filters.category += ',' + $(this).find('a').data('id');
        }
    });
    if ($('#slider').hasClass('checked')) {
        filters.price = parseInt($("#slider").slider("values", 0)) + '-' + parseInt($("#slider").slider("values", 1));
    }
    $('.color-box.checked').each(function () {
        filters.color += ',' + $(this).data('id');
    });
    $('.selection-size .size-box.checked').each(function () {
        filters.sizes += ',' + $(this).data('value');
    });
    if (
            filters.color != '' ||
            filters.category != '' ||
            filters.season != '' ||
            filters.sizes != '' ||
            filters.order != '' ||
            filters.price != ''
            ) {
        $.each(filters, function (key, value) {
            if (value != '') {
                value = key === 'season' || key === 'price' ? value : value.replace(',', '');
                data += '&' + key + '=' + value;
            }
        });
    } else {
        data = '';
    }
    var url = '/c'
            + $('.pagination').data('id')
            + '-'
            + $('.pagination').data('alias')
            + encodeURIComponent(data)
            + '/';
    $.pjax({url: url, container: '#pjax-conteiner-main', timeout: 20000});
}

//pagination
function pagination() {
    $('.pagination').twbsPagination({
        initiateStartPageClick: false,
        first: 'Первая',
        last: 'Последняя',
        next: '&raquo;',
        prev: '&laquo;',
        totalPages: $('.product').first().data('count'),
        startPage: $('.product').first().data('page'),
        visiblePages: 5,
        onPageClick: function (event, page) {

            //якщо сторінка результату пошуку то іншу строку запиту
            if (location.pathname.match('^\/search')) {
                var url = '/search&search=' +
                        $('#search-status .search-text').text() +
                        '&search_cat_name=' +
                        $('#search-status .search-category-name').text() +
                        '&page=' + page + '&perPage=' +
                        $('#page-commodities-count').find(':selected').val();
            } else if (location.pathname.match('^\/catalog')) {
                var url = location.pathname.match('\/catalog\/[a-z-]+') +
                        '/page/' + page + '=' + $('#page-commodities-count').find(':selected').val();
            } else {

                //якщо сторінка каталоку
                var path = location.pathname.substr(location.pathname.indexOf(encodeURIComponent($(this).data('alias')))),
                        url = '/c' + $(this).data('id') + '-' + page + path;
            }

            $.pjax({url: url, container: '#pjax-container', timeout: 20000});
        }
    });
}

//render top filters
function showFilters() {
    var visibleCount = 0;
    $('.color-box').each(function (e) {
        if (e > 3) {
            $(this).hide();
        }
        if ($(this).css('display') === "block") {
            visibleCount += 1;
        }
        if ($(this).hasClass('checked')) {
            $(this).find('span').removeClass('hidden');
        }
    });
    $('.color-box').eq(visibleCount - 1).css({marginRight: "28px"});

    var boxWidthAll = 0, boxWidth = "",
            visibleCountSize = 0, marginRight = 0;
    $('.size-box').each(function (e) {
        boxWidth = parseInt($(this).css('width'));
        boxWidthAll += boxWidth;
        if (boxWidthAll > 110) {
            $(this).hide();
            boxWidthAll -= boxWidth;
        }
        if ($(this).css('display') === "inline-block") {
            visibleCountSize += 1;
        }
    });
    marginRight = 145 - (boxWidthAll + parseInt($('.size-box').eq(visibleCountSize).css('width')));
    $('.size-box').eq(visibleCountSize - 1).css({marginRight: marginRight + "px"});

    $("#slider").slider({
        min: parseInt($('input#minCost').data('value')),
        max: parseInt($("input#maxCost").data('value')),
        values: [
            parseInt($('input#minCost').val()),
            parseInt($("input#maxCost").val())
        ],
        range: true,
        stop: function () {
            $(this).addClass('checked');
            getFilteredPage();
        },
        slide: function () {
            $("input#minCost").val($("#slider").slider("values", 0));
            $("input#maxCost").val($("#slider").slider("values", 1));
        }
    });
}

//show selected filtes in filters state
function setFiltersState() {
    var showMyWishState = 0;
    if ($('#slider').hasClass('checked')) {
        $('.price-state').find('span').removeClass('hidden');
        showMyWishState = 1;
        $('.price-state').removeClass('hidden');
        $('.price-state').addClass('active');
    }
    $('.color-box.checked').each(function (e) {
        if (e === 0) {
            showMyWishState = 1;
            $('.color-state').removeClass('hidden');
            $('.color-state').addClass('active');
        }
        $('.color-state').append('<span>' + $(this).data('name') +
                '</span><span data-id="' + $(this).data('id') + '" class="glyphicon glyphicon-remove color-remove"></span>');
    });
    $('.size-box.checked').each(function (e) {
        if (e === 0) {
            showMyWishState = 1;
            $('.size-state').removeClass('hidden');
            $('.size-state').addClass('active');
        }
        $('.size-state').append('<span>' + $(this).text() +
                '</span><span data-value="' + $(this).data('value') + '" class="glyphicon glyphicon-remove size-remove"></span>');
    });
    $('.left-menu-products .category:checked').each(function (e) {
        if (e === 0) {
            showMyWishState = 1;
            $('.wiew-state').removeClass('hidden');
            $('.wiew-state>p').text('категории:');
            $('.wiew-state').addClass('active');
        }
        $('.wiew-state').append('<span>' + $(this).data('name') +
                '</span><span data-id="' + $(this).data('id') + '" class="glyphicon glyphicon-remove wiew-remove"></span>');
    });
    $('.left-menu-products .brands:checked').each(function (e) {
        if (e === 0) {
            showMyWishState = 1;
            $('.wiew-state').removeClass('hidden');
            $('.wiew-state>p').text('бренды:');
            $('.wiew-state').addClass('active');
        }
        $('.wiew-state').append('<span>' + $(this).data('name') +
                '</span><span data-id="' + $(this).data('id') + '" class="glyphicon glyphicon-remove wiew-remove"></span>');
    });
    if (showMyWishState === 1) {
        $('.my-wish-state').removeClass('hidden');
    } else {
        $('.my-wish-state').addClass('hidden');
    }
}

//getAllAfterPjax
function getAllAfterPjax() {

    //select styler
    $('.season select, .order select, #page-commodities-count select').styler({singleSelectzIndex: 1});
    $("select:not(.season select, .order select, #page-commodities-count select, #profile-delivery-method)").styler('destroy');
    $(".product .jq-selectbox__select").click(function () {
        $(".product.hovered").css("overflow", "visible");
    });

    //hover of commondity card
    $("#pjax-container .product").each(function () {
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

    //init tooltip for all fast order
    $(".fast-order").tooltip({
        disabled: true,
        content: "<div class='custom-border-tooltip'>Быстрым заказом можно заказать не более одной единицы!</div>",
        hide: {effect: "explode", duration: 300},
        tooltipClass: "custom-tooltip-styling",
        track: true
    });

    //get popups
    initPopupsFromComBlock();
}
