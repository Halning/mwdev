
$(document).ready(function() {
 function RefillByCategory(catId) {

            $('#config-wrapper-template').remove();
            $.get('/v3/pages/Products/CoplectationTemplate.aspx?catid=' + catId, function (templates) {
                $('#tempalte-container').append(templates);

                $(".property-values-selector").append($("#config-wrapper-template").tmpl({ random: getRandomInt(100000, 999999), showModificator: true, curCurrencyName: ' руб' }));
                $(".property-values-selector").find('select').selectBox();
                ShowHideRemoveConfigButton();
                buttonConfigInit();

                if ($('.property-values-selector').find('.config-wrapper .properties').length <= 0) {
                    $('.complectationGeneral').hide();

                    $('.right-block .generalonstock').prop('disabled', false);
                    $('.right-block .generalonstock').css('background', 'none');
                } else {
                    $('.complectationGeneral').show();

                    $('.configInfo .onstoreerror').hide();
                    $('.configInfo .onstore').live("change", function () {
                        $(this).closest('.configInfo').find('.onstoreerror').hide();
                        if ($(this).val() != '') {
                            if ($(this).val() >= 0)
                                RecallculateProductCount();
                            else {
                                $(this).closest('.configInfo').find('.onstoreerror').show();
                            }
                        }
                    });

                    $('.right-block .generalonstock').prop('disabled', true);
                    $('.right-block .generalonstock').css('background', 'lightgray');
                }
            });
        }
//Categories block
            $('.inp-categ-block').live(
                {
                    mouseenter: function () { $(this).addClass('inp-categ-block-hover'); },
                    mouseleave: function () { $(this).removeClass('inp-categ-block-hover'); }
                });
            
            function HideCategoryTree() {
                $('.filetree-block').hide();
            }

            $('.inp-categ-block').live("click", function () {
                if ($('.filetree-block').is(':visible')) {
                    HideCategoryTree();
                } else {
                    var container = $(this).closest(".categ-block-wrap");


                    if (container.find('.filetree-block').length == 0) {
                        container.append($(".filetree-block"));
                    }

                    $(".tree").jstree("deselect_all");
                    var categoryID = $(this).find("span").attr("category_id");
                    if (categoryID != null) {
                        $(".tree").jstree("select_node", "#node_" + categoryID);
                    }

                    container.find('.filetree-block').show();
                }
            });

            // Click outside categories block
            $(document).click(function (e) {
                if ($(e.target).parents().filter('.filetree-block').length != 1 && $(e.target).parents().filter('.categ-block-wrap').length != 1) {
                    HideCategoryTree();
                }
            });

            // Category
            if($(".tree").length > 0) {
                $(".tree")
                    .jstree({
                        "plugins": [
                            "themes", "json_data", "ui"
                        ],
                        "themes": {
                            "theme": "classic",
                            "icons": false
                        },
                        "json_data": { "data": window.tree_init != null ? window.tree_init.items : null },
                        "ui": {
                            select_limit: 1,
                            "initially_select": window.tree_init != null ? window.tree_init.current : null
                        },
                        "core": { animation: 100 }
                    })
                    .bind("select_node.jstree", function(e, data) {
                        var obj = data.rslt.obj;
                        var id = obj.attr("id").replace("node_", "");
                        obj.closest(".categ-block-wrap").find(".inp-categ-block span")
                            .attr("category_id", id)
                            .text(data.inst.get_text(obj));
                        obj.closest(".categ-block-wrap").find(".inp-categ-block .cat_ids")
                            .val(id);
                        HideCategoryTree();

                        /*if(obj.closest(".categ-block-wrap").hasClass("root-category")) {
                        
                            var currentCatId = $("#hfCurrentCatId").val();
                            if(id != currentCatId) {
                                //alert('Category Changed!!! new id-' + id);
                                $("#hfCurrentCatId").val(id);
                                $('.property-values-selector').find("select").selectBox("destroy");
                                $('.property-values-selector').html('');
                                RefillByCategory(id);
                            }
                        
                        }*/
                    });
            }

            $("#search-btn").click(function () {
                $(".treeCatalog").jstree("search",$("#searchInput").val());
            });

            // Add category
            $(".add-product-categeroy").click(function () {
                $("#add-category").before($("#add-category").html());
                if ($(".categ-prod-wrap > .inp-categ-wrap").length == 5) {
                    $(".add-product-categeroy").hide();
                }
            });
            $(".remove-product-category").live("click", function () {
                $(this).closest(".inp-categ-wrap").remove();
                if ($(".categ-prod-wrap > .inp-categ-wrap").length < 5) {
                    $(".add-product-categeroy").show();
                }
            });

});


