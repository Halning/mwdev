$(document).ready(function () {
    var pj = 0;
    var pj2 = 0;
    setInterval(function () {
        $.getJSON("modules/commodities/admin/parser_ajax.php").done(function (up_inter) {
            for (var i = 0; i < up_inter.length; i++) {
                var id = up_inter[i]["i"];
                var p = parseInt(up_inter[i]["p"]);
                $("#check_count" + id).text(up_inter[i]["a"]);
                $("#update_count" + id).text(up_inter[i]["b"]);
                $("#par_hide" + id).text(up_inter[i]["par_hide"]);
                $("#par_start_time" + id).text(up_inter[i]["start_time"]);
                $("#par_end_time" + id).text(up_inter[i]["end_time"]);
                $("#par_time_darution" + id).text(up_inter[i]["time_duration"]);
                $("#par_check" + id).text(up_inter[i]["a"]);
                $("#par_update" + id).text(up_inter[i]["b"]);
                $("#ani2_prog" + id).text(p + "%");
                $("#ani_prog" + id).css({"width": p + "%"});
                $("#up_date" + id).text(up_inter[i]["data"]);
                if (p < 100) {
                    switch (pj % 3) {
                        case 0:
                            $("#pro" + id).text("...");
                            $("#pro" + id).css({"margin-right": "5px"});
                            break;
                        case 1:
                            $("#pro" + id).text("..");
                            $("#pro" + id).css({"margin-right": "9px"});
                            break;
                        case 2:
                            $("#pro" + id).text(".  ");
                            $("#pro" + id).css({"margin-right": "13px"});
                            break;
                    }
                    pj++;
                }
                var a_date = up_inter[i]["a_data"];
                if (a_date > "0") {
                    $("#add_date" + id).text(a_date);
                }
                var ap = parseInt(up_inter[i]["ap"]);
                $("#new_com" + id).text(up_inter[i]["an"]);
                $("#par_new_url" + id).text(up_inter[i]["an"]);
                $("#par_import" + id).text(up_inter[i]["an"]);
                $("#add2_prog" + id).text(ap + "%");
                $("#add_prog" + id).css({"width": ap + "%"});
                if (ap < 100) {
                    switch (pj2 % 3) {
                        case 0:
                            $("#pro2" + id).text("...");
                            $("#pro2" + id).css({"margin-right": "5px"});
                            break;
                        case 1:
                            $("#pro2" + id).text(".. ");
                            $("#pro2" + id).css({"margin-right": "9px"});
                            break;
                        case 2:
                            $("#pro2" + id).text(".  ");
                            $("#pro2" + id).css({"margin-right": "13px"});
                            break;
                    }
                    pj2++;
                }
                $("#text" + id).html(up_inter[i]["txt"]);
            }
        });
    }, 5000);
});