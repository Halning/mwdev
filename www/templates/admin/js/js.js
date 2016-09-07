$(document).ready(function () {
    $('.cl_addition_fields .filter_selecter option').on('click', function () {
        var color = $(this).text();
        $(this).parents('div').append("<span id='show-color'></span>");

        if (color.indexOf('color') > -1) {
            color = color.replace('color', '#');

            $('#show-color').css({
                'background': color,
                'display': 'inline-block',
                'height': '40px',
                'width': '40px',
                'margin': '10px'
            });
        } else {
            $('#show-color').detach();
        }
    });

    $('.cl_addition_fields .filter_selecter option').last().click();



    $('.cl_menu1').contextMenu('myMenu1', {
        bindings: {
            'edit': function (t) {
                document.location.href = '/?admin=edit_articles_cats&categoryID=' + t.id;
            },
            'addcat': function (t) {
                document.location.href = '/?admin=add_articles_category&selected_cat=' + t.id;
            },
            'addarticle': function (t) {
                document.location.href = '/?admin=add_articles&selected_cat=' + t.id;
            },
            'delete': function (t) {
                document.location.href = '/?admin=delete_articles_category&categoryID=' + t.id;
            }
        }
    });

    $(".wrapper_prods").hover(function () {
        $(this).find(".extra_name").stop().animate({bottom: '14'}, 1100, 'easeOutBack');
    }, function () {
        $(this).find(".extra_name").stop().animate({bottom: '-161'}, 1100, 'easeInBack');
    });

    $('ul#my-menu ul').each(function (i) { // Check each submenu:
        if ($.cookie('submenuMark-' + i)) {  // If index of submenu is marked in cookies:
            $(this).show().prev().removeClass('collapsed').addClass('expanded'); // Show it (add apropriate classes)
        } else {
            $(this).hide().prev().removeClass('expanded').addClass('collapsed'); // Hide it
        }
        $(this).prev().addClass('collapsible').click(function () { // Attach an event listener
            var this_i = $('ul#my-menu ul').index($(this).next()); // The index of the submenu of the clicked link
            if ($(this).next().css('display') == 'none') {

                // When opening one submenu, we hide all same level submenus:
                $(this).parent('li').parent('ul').find('ul').each(function (j) {
                    if (j != this_i) {
                        $(this).slideUp(200, function () {
                            $(this).prev().removeClass('expanded').addClass('collapsed');
                            cookieDel($('ul#my-menu ul').index($(this)));
                        });
                    }
                });
                // :end

                $(this).next().slideDown(200, function () { // Show submenu:
                    $(this).prev().removeClass('collapsed').addClass('expanded');
                    cookieSet(this_i);
                });
            } else {
                $(this).next().slideUp(200, function () { // Hide submenu:
                    $(this).prev().removeClass('expanded').addClass('collapsed');
                    cookieDel(this_i);
                    $(this).find('ul').each(function () {
                        $(this).hide(0, cookieDel($('ul#my-menu ul').index($(this)))).prev().removeClass('expanded').addClass('collapsed');
                    });
                });
            }
            return false; // Prohibit the browser to follow the link address
        });
    });
    $('span.opener3').on('click', function (e) {
        if ($(this).parent().children('table.c2_group_hide ').hasClass('opened3') && e.target == this) {
            $(this).parent().children('table.c2_group_hide ').removeClass('opened3');

            $(this).parent().children('table.c2_group_hide ').css('display', 'none');
            $(this).html('+ ');

        } else {
            $(this).parent().children('table.c2_group_hide ').addClass('opened3');
            $(this).parent().children('table.c2_group_hide ').css('display', 'table');
            $(this).html('- ');
        }
    });

    jQuery('select#select_order_status').change(function () {
        var data = {};
        data['id'] = $(this).val();
        data['ofid'] = $(this).attr('rel');

        $.ajax({
            url: 'http://' + window.location.hostname + '/modules/commodities/admin/add_order_status.php',
            type: "GET",
            data: data,
            success: function (data) {},
            error: function (data) {
                return(false);
            }
        });
    });
    jQuery('select#select_order_status2').change(function () {
        var data = {};
        data['id2'] = $(this).val();

        data['ofid2'] = $(this).attr('rel');
        $.ajax({
            url: 'http://' + window.location.hostname + '/modules/commodities/admin/add_order_status.php',
            type: "GET",
            data: data,
            success: function (data) {},
            error: function (data) {
                return(false);
            }
        });
    });
    jQuery('select#select_status_com').change(function () {
        var data = {};
        data['id'] = $(this).val();

        data['comid'] = $(this).attr('rel');
        data['status'] = "admin";
        $.ajax({
            url: 'http://' + window.location.hostname + '/modules/commodities/admin/add_status_com.php',
            method: "GET",
            data: data,
            success: function (data) {
                // alert(data);
            },
            error: function (data) {
                return(false);
            }
        });
    });
    jQuery('select#select_group_status').change(function () {
        var data = {};
        data['id'] = $(this).val();

        data['group_id'] = $(this).attr('rel');
        $.ajax({
            url: 'http://' + window.location.hostname + '/modules/commodities/admin/add_status_group.php',
            type: "GET",
            data: data,
            success: function (data) {},
            error: function (data) {
                return(false);
            }
        });
    });
    jQuery('select.select_group_status').change(function () {
        var data = {};
        data['id'] = $(this).val();

        data['group_id'] = $(this).attr('rel');
        $.ajax({
            url: 'http://' + window.location.hostname + '/modules/commodities/admin/add_status_group21.php',
            type: "GET",
            data: data,
            success: function (data) {},
            error: function (data) {
                return(false);
            }
        });
    });

    jQuery('.in_slider').click(function () {
        var data = {};
        data['limits'] = $(this).val();
        //	alert(data['limits']);
        $.ajax({
            url: '?admin=add_status_group',
            type: "GET",
            data: data,
            success: function (data) {},
            error: function (data) {
                return(false);
            }
        })
                .done(function (lit) {
                    //	alert(lit);
                });
    });

    //js код для интерфеса запуска проверщика
    $('#deleteCh').click(function () {
        $("input[type=checkbox]").not('.comProp').prop('checked', false);
        $(this).text("Выделить все").css({backgroundColor: "#3CB371"});
        this.clicked = this.clicked === undefined ? false : !this.clicked;
        if (this.clicked) {
            $("input[type=checkbox]").not('.comProp').prop('checked', true);
            $(this).text("Снять выделение").css({backgroundColor: "#98FB98"});
        }
    });
//=========================Конец================================================

    $('.tab_nn').click(function () {
        var rel = $(this).attr('rel');
        var local = window.location.href;
        // $('.tab_nn').removeAttr('style');
        // $('.tab_nn').removeClass('but_nn_active');
        // $(this).addClass('but_nn_active');
        // $(this).css({'border-bottom':'0px solid gray', 'color':'black'});

        // $('.but_active7').removeAttr('style');
        // $('.but_active').removeAttr('style');


        if (rel == '1') {
            var params = parseQueryString();
            local = local.replace("&archive=true", "");
            local = local.replace("&p=" + params["p"], "");
            window.location = local

            // $('.but_active7').css({'display':'none'});
            // $('.but_active').css({'display':'grid'});
        }
        if (rel == '2') {

            window.location = local + "&archive=true";
            // $('.but_active7').css({'display':'grid'});
            // $('.but_active').css({'display':'none'});
        }
    });
    // Сингал чат "задать вопрос"
    //Online chat 
    // $.get('modules/commodities/admin/online_ajax.php',{stat_c:true})
    // .done(function(da){
    //     $('.stat_chat').text(da);
    //     //alert(da);
    // });  #F44336 // red 

    //background: #FFD18E;

    $.ajax({
        url: "/online/ajax/singal.php"
    })
    .done(function (data) {
      // $(".stat_chat").text(data);
       //$(".div_singal").css({"background": "#FFD18E"});
       // $(".div_singal").animate({backgroundColor: "#FFD18E"}, 300);
        // $(".div_singal").ready(function(){
        //     $(".div_singal").animate({"background-color":"red"}, 1000);
        // });
       // div_singal();
    });
    
    // Select Color

    $('.color_select').each(function(){
        var vall=$(this).val();
        var color=f_color_select(vall);
        $(this).css({"background":"rgb("+color+")", "color": "black"});
    });
    $('.discolor_select').each(function(){
        var vall=$(this).val();
        var color=f_discolor_select(vall);
        $(this).css({"background":"rgb("+color+")", "color": "white"});
    });
    $('.color_select option').each(function(){
        var vall=$(this).val();
        var color=f_color_select(vall);
        $(this).css({"background":"rgb("+color+")", "color": "black"});
    });
    $('.color_select').change(function(){
        var vall=$(this).val();
        var color=f_color_select(vall);
        $(this).css({"background":"rgb("+color+")"});
    });

    $('.color_select2').each(function(){
        var vall=$(this).val();
        var color=f_color_select2(vall);
        $(this).css({"background":"rgb("+color+")"});
    });
    $('.discolor_select2').each(function(){
        var vall=$(this).val();
        var color=f_discolor_select2(vall);
        $(this).css({"background":"rgb("+color+")"});
    });
    $('.color_select2 option').each(function(){
        var vall=$(this).val();
        var color=f_color_select2(vall);
        $(this).css({"background":"rgb("+color+")", "color": "black"});
    });
    $('.color_select2').change(function(){
        var vall=$(this).val();
        var color=f_color_select2(vall);
        $(this).css({"background":"rgb("+color+")"});
    });

    $('.color_select3').each(function(){
        var vall=$(this).val();
        var color=f_color_select3(vall);
        $(this).css({"background":"rgb("+color+")"});
    });
    $('.color_select3 option').each(function(){
        var vall=$(this).val();
        var color=f_color_select3(vall);
        $(this).css({"background":"rgb("+color+")", "color": "black"});
    });
    $('.color_select3').change(function(){
        var vall=$(this).val();
        var color=f_color_select3(vall);
        $(this).css({"background":"rgb("+color+")"});
    });

    function f_color_select(vall){
        var color='';
        if(vall==1){
            color='255, 255, 0';
        }
        if(vall==2){
            color='255, 204, 0';
        }
        if(vall==3){
            // color='255, 153, 0';
            color='169, 208, 142';
        }
        if(vall==4){
            color='84, 130, 53';
        }
        if(vall==5){
            color='255, 153, 204';
        }
        if(vall==6){
            color='255, 102, 255';
        }
        if(vall==8){
            color='255, 204, 204';
        }
        if(vall==10){
            color='255, 255, 255';
        }
        if(vall==12){
            color='107, 179, 58';
        }
        return color;
    }
    function f_discolor_select(vall){
  
        if(vall==3){
            color='169, 208, 142';
        }
        if(vall==4){
            color='84, 130, 53';
        }
        if(vall==5){
            color='255, 153, 204';
        }
        if(vall==6){
            color='255, 102, 255';
        }
        if(vall==8){
            color='255, 204, 204';
        }
        if(vall==12){
            color='107, 179, 58';
        }
        return color;
    }
    function f_color_select2(vall){
        var color='';
        if(vall==3){
            color='255, 153, 0';
        }
        if(vall==4){
            color='84, 130, 53';
        }
        if(vall==12){
            color='107, 179, 58';
        }
        return color;
    }
    function f_discolor_select2(vall){
        var color='';
        if(vall==6){
            color='255, 102, 255';
        }
        if(vall==7){
            color='204, 0, 155';
        }
        return color;
    }
    function f_color_select3(vall){
        var color='';
        if(vall==5){
            color='255, 153, 204';
        }
        if(vall==6){
            color='255, 102, 255';
        }
        if(vall==7){
            color='204, 0, 155';
        }
        if(vall==8){
            color='255, 204, 204';
        }
        return color;
    }


    $('.color_select_group').each(function(){
        var vall=$(this).val();
        var color=f_color_select_group(vall);
        $(this).css({"background":"rgb("+color+")"});
    });
    $('.discolor_select_group').each(function(){
        var vall=$(this).val();
        var color=f_discolor_select_group(vall);
        if(vall==3){
             $(this).css({"background":"rgb("+color+")", "color":"dimgrey"});
        }else{
             $(this).css({"background":"rgb("+color+")"});  
        }
      //  $(this).css({"background":"rgb("+color+")"});
    });
    $('.color_select_group option').each(function(){
        var vall=$(this).val();
        var color=f_color_select_group(vall);
        $(this).css({"background":"rgb("+color+")"});
    });
    $('.color_select_group').change(function(){
        var vall=$(this).val();
        var color=f_color_select_group(vall);
        $(this).css({"background":"rgb("+color+")"})
    });


    $('.color_select_group2').each(function(){
        var vall=$(this).val();
        var color=f_color_select_group2(vall);
        $(this).css({"background":"rgb("+color+")"})
    });
    $('.discolor_select_group2').each(function(){
        var vall=$(this).val();
        var color=f_discolor_select_group2(vall);
        $(this).css({"background":"rgb("+color+")"})
    });
    $('.color_select_group2 option').each(function(){
        var vall=$(this).val();
        var color=f_color_select_group2(vall);
        $(this).css({"background":"rgb("+color+")"});
    });
    $('.color_select_group2').change(function(){
        var vall=$(this).val();
        var color=f_color_select_group2(vall);
        $(this).css({"background":"rgb("+color+")"})
    });

    $('.color_select_group3').each(function(){
        var vall=$(this).val();
        var color=f_color_select_group3(vall);
        $(this).css({"background":"rgb("+color+")"})
    });
    $('.color_select_group3 option').each(function(){
        var vall=$(this).val();
        var color=f_color_select_group3(vall);
        $(this).css({"background":"rgb("+color+")"});
    });
    $('.color_select_group3').change(function(){
        var vall=$(this).val();
        var color=f_color_select_group3(vall);
        $(this).css({"background":"rgb("+color+")"})
    });

    function f_color_select_group(vall){
        var color='';
        if(vall==1){
            color='255, 255, 0';
        }
        if(vall==2){
            color='255, 204, 0';
        }
        if(vall==3){
            color='255, 153, 0';
        }

        if(vall==4){
            color='84, 130, 53';
        }
        if(vall==5){
            color='255, 102, 255';
        }
        if(vall==6){
            color='204, 0, 155';
        }
        if(vall==10){
            color='255, 255, 255';
        }
        return color;
    }
    function f_discolor_select_group(vall){
        var color='';

        if(vall==3){
            color='255, 255, 255';
        }
        if(vall==11){
            color='169, 208, 142';
        }
        if(vall==4){
            color='84, 130, 53';
        }
        if(vall==5){
            color='255, 102, 255';
        }
        if(vall==6){
            color='204, 0, 155';
        }
        return color;
    }
    function f_color_select_group2(vall){
        var color='';
        if(vall==3){
            color='255, 255, 255';
        }
        if(vall==4){
            color='84, 130, 53';
        }
        if(vall==11){
            color='169, 208, 142';
        }
        return color;
    }
    function f_discolor_select_group2(vall){
        var color='';
        if(vall==3){
            color='169, 208, 142';
        }
        if(vall==4){
            color='84, 130, 53';
        }
        if(vall==5){
            color='255, 102, 255';
        }
        if(vall==6){
            color='204, 0, 155';
        }
        return color;
    }
    function f_color_select_group3(vall){
        var color='';
        if(vall==4){
            color='84, 130, 53';
        }
        if(vall==5){
            color='255, 102, 255';
        }
        if(vall==6){
            color='204, 0, 155';
        }
        return color;
    }

// End color select

    //subscribe check
    $('.cl_body').on('change', '.subscribers .mess_to', function() {
        var $table = $(this).closest('.subscribers'),
            checked = false;
        $table.find('.mess_to').each(function(i, e) {
            if (e.checked) {
                checked = true;
            }
        });

        if (checked) {
            $('.cl_body .subscribers-delete').show();
            return false;
        }

        $('.cl_body .subscribers-delete').hide();
    });

    //subscribers delete by checkbox
    $('.cl_body').on('click', '.subscribers-delete', function() {
        var $tableCheckboxes = $('.cl_body .subscribers').find('.mess_to'),
            items = [],
            conf;

        $tableCheckboxes.each(function(i, e) {
            if (e.checked) {
                items.push([$(e).attr('name'), $(e).attr('value')]);
            }
        });

        conf = confirm('Вы действительно хотите удалить подписчиков('+items.length+'шт.)?');

        if (conf) {
            $.post(
                '/modules/subscribers/ajax/site.php',
                {
                    event: 'delete-subscribers',
                    items: items
                }
            ).always(function() {
                location.reload();
            });
        }
    });

    //subscriber delete single
    $('.cl_body').on('click', '#subscriber-delete, .subscriber-edit #toolbar-delete .toolbar', function(e) {
        e.preventDefault();
        var $_this = $(this),
            className = e.currentTarget.className;
        if (confirm('Вы действительно хотите удалить подписчика?')) {
            $.post(
                '/modules/subscribers/ajax/site.php',
                {
                    event: 'delete-subscribers',
                    items: [
                        [
                            $_this.data('id'),
                            $_this.data('type')
                        ]
                    ]
                }
            ).always(function() {
                console.log(className)
                if ('toolbar' === className) {
                    window.history.back();
                    return;
                }
                location.reload();
            });
        }
    });

    //subscriber save
    $('.cl_body').on('click', '.subscriber-edit #toolbar-save .toolbar', function() {
        var $form = $('.cl_body .subscribe-edit-form');
        $.post(
            '/?admin=subscribers_edit',
            $form.serialize()
        )
            .always(function() {
                window.history.back();
            });
    });

    //letter delete single
    $('.cl_body').on('click', '#letter-delete, .letter-edit #toolbar-delete .toolbar', function(e) {
        e.preventDefault();
        var $_this = $(this),
            className = e.currentTarget.className;

        if (confirm('Вы действительно хотите удалить пысьмо?')) {
            $.post(
                '/modules/subscribers/ajax/site.php',
                {
                    event: 'delete-letter',
                    id: $_this.data('id')
                }
            )
                .always(function() {
                if ('toolbar' === className) {
                    window.history.back();
                    return;
                }
                location.reload();
            });
        }
    });

    //preview letter on change letter
    $('.subscribe-send').on('change', '.block-right .template-base select', function() {
        var id = $(this).find(':selected').val();
        $.ajax({
            url: '/modules/subscribers/ajax/site.php',
            data: {
                action: 'letter-preview',
                id: id
            },
            dataType: 'HTML'
        })
            .always(function(data) {
                CKEDITOR.instances.preview1.setData(data)
            });

    });
});

function sel_pri(a) {
    $(document).ready(function () {
        var i = $("#select_pri" + a + " :selected").val();
        $(location).attr('href', "?admin=orders_to_sup20&sel=" + i + "&group=" + a);
    });
}
function div_singal(){
    // alert("singal");
    // $(".div_singal").animate({"background-color":"red"}, 1000);
}

var parseQueryString = function () {

    var str = window.location.search;
    var objURL = {};

    str.replace(
            new RegExp("([^?=&]+)(=([^&]*))?", "g"),
            function ($0, $1, $2, $3) {
                objURL[ $1 ] = $3;
            }
    );
    return objURL;
};

function cookieSet(index) {
    $.cookie('submenuMark-' + index, 'opened', {expires: null, path: '/'}); // Set mark to cookie (submenu is shown):
}
function cookieDel(index) {
    $.cookie('submenuMark-' + index, null, {expires: null, path: '/'}); // Delete mark from cookie (submenu is hidden):
}

//subscribers checkbox
function setChecked(obj)
{
    var check = document.getElementsByClassName('mess_to');
    for (var i=0; i<check.length; i++)
    {
        check[i].checked = obj.checked;
    }

    if (obj.checked) {
        $('.cl_body .subscribers-delete').show();
        return false;
    }

    $('.cl_body .subscribers-delete').hide();
}

//Функция валидации email
function validateEmail(email) {
    var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
    return pattern.test(email) && email.length > 0 ? true : false;
}

//Функция валидация форми відправлення тестових писем
function validateSendTestEmailsForm(el) {
    var $theme = $(el).find('.block-left input:first-of-type'),
        $from = $(el).find('.block-left input:nth-of-type(2)'),
        $emailFrom = $(el).find('.block-left input:nth-of-type(3)'),
        $email = $(el).find('.block-right .test-email'),
        $template = $(el).find('.block-right .template-base select'),
        error = false;

    if ('' === $theme.val()) {
        showError($theme);
        error = true;
    }

    if ('' === $from.val()) {
        showError($from);
        error = true;
    }

    if ('' === $template.find(':selected').val()) {
        showError($template);
        error = true;
    }

    if (!validateEmail($email.val())) {
        showError($email, true);
        error = true;
    }

    if (!validateEmail($emailFrom.val())) {
        showError($emailFrom, true);
        error = true;
    }

    if (error) {
        return false;
    }

    el.submit();
}

//Функция валидация форми відправлення писем подпищикам
function validateSendSubEmailsForm(el) {
    var $theme = $(el).find('.block-left input:first-of-type'),
        $from = $(el).find('.block-left input:nth-of-type(2)'),
        $template = $(el).find('.block-right .template-base select'),
        $letterBase = $(el).find('.block-right .submit-send #letter-base'),
        error = false;

    if ('' === $theme.val()) {
        showError($theme);
        error = true;
    }

    if ('' === $from.val()) {
        showError($from);
        error = true;
    }

    if ('' === $template.find(':selected').val()) {
        showError($template);
        error = true;
    }

    if ('' === $letterBase.find(':selected').val()) {
        showError($letterBase);
        error = true;
    }

    if (error) {
        return false;
    }

    if (confirm('Вы действительно хотите отправить письмо всем выбранным подписчикам?')) {
        $(el).prepend('<input type="hidden" name="sub-sending" value="1">');
        el.submit();
    }
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

function f_activ_user(user_id)
{
    document.getElementById('user_activ').value=user_id;
    document.forms.user_activ.submit();
}
