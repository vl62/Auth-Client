String.prototype.format = function (arguments) {
    var s = this, i = arguments.length;
    if (i === 0) return s;
    while (i--) s = s.replace(new RegExp('\\{' + i + '\\}', 'gm'), arguments[i]);
    return s;
};

function add_options(option, arguments) {
    $select = $.parseHTML(option);
    for (var arg in arguments) {
        if(arguments[arg] == "----------------------" || arguments[arg] == "not all values might be displayed")
            $($select).children('select').append("<option value='" + arguments[arg] + "' disabled>" + arguments[arg] + "</option>");
        else
            $("<option />", {value: arguments[arg], text: arguments[arg]}).appendTo($($select).children('select'));
    }
    return $select;
}

function add_options_group(option, arguments) {
    var $select = $.parseHTML(option);
    for (var key in arguments) {
        var group = $('<optgroup label="' + key + '" />');
        for (var value in arguments[key]) $("<option />", {value: arguments[key][value], text: arguments[key][value]}).appendTo(group);
        group.appendTo($($select).children('select'));
    }
    return $select;
}

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function get_badge_colour(index) {

    switch(index%6) {
        case 0:
        return "badge";
        break;

        case 1:
        return "badge badge-warning";
        break;

        case 2:
        return "badge badge-important";
        break;

        case 3:
        return "badge badge-info";
        break;

        case 4:
        return "badge badge-inverse";
        break;

        case 5:
        return "badge badge-success";
        break;
    }
}

$option = '<div class="pagination-centered {0}">\n\
                <select class="{1}" data-type="operator" {2}></select>\n\
               {3}\n\
                </div>';

$option_select2 = '<div class="pagination-centered {0}">\n\
                            <select class="input-xlarge {1}" style="margin-bottom:10px">\n\
                            <option></option>\n\
                            </select>\n\
                        </div>';

$add_remove_btn_advanced = '<div class="span2 {0} add_remove_btn_advanced" style="padding-top: 3px;">\n\
                        <button class="btn btn-mini btn-success add_advanced">\n\
                            <i class="icon-plus"></i>\n\
                        </button>\n\
                        <button class="btn btn-mini btn-danger remove_advanced hidden">\n\
                            <i class="icon-minus icon-white"></i>\n\
                        </button>\n\
                       </div>';

$type_sample = '<div id="{0}" class="row-fluid type_sample {1}" style="margin-bottom: 20px;">\n\
                    </div>';

$label = "<div class='pagination-centered span1' style='padding-top: 4px; padding-left: 40px;'><span class='{0}'>{1}</span></div>";

$advanced_count = 1;
$advanced_option_1 = ["IS", "IS LIKE", "IS NOT", "IS NOT LIKE", "---------------", "=", "≠", "<", ">", "<=", ">="];
$advanced_option_2 = ["--Select a value--", "NULL", "[Input your own value]"];

$phen_attr_with_cat_array = {
        "General and demographics": [
                                        "Cognitive disorder [latest diagnosis]",
                                        "Cognitive disorder [year of latest diagnosis]",
                                        "Age [by start of this year]", 
                                        "Gender", 
                                        "Visits [year of latest visit]"
                                    ],

        "Risk factors and biomarkers": [
                                        "Number of APOE-e4 alleles", 
                                        "Has first degree relative with AD", 
                                        "CSF amyloid 1-42 [abnormally decreased]",
                                        "CSF amyloid 1-42 [latest result]",
                                        "CSF amyloid 1-42 [year of latest result]",
                                        "CSF t-tau [abnormally increased]",
                                        "CSF t-tau [latest result]",
                                        "CSF t-tau [year of latest result]",
                                        "CSF p-tau [abnormally increased]",
                                        "CSF p-tau [latest result]",
                                        "CSF p-tau [year of latest result]",
                                        "MTA-score [latest average of L and R sides]",
                                        "MTA-score [year of latest score]",
                                        // "PET metabolism"
                                        ], 

        "Cognitive tests": [
                                        "MMSE score [latest result]", 
                                        "MMSE score [year of latest result]", 
                                        "MMSE score [decline rate per year]",  
                                        "Immediate recall z-score [latest result]", 
                                        "Immediate recall [year of latest result]", 
                                        "Immediate recall [decline per year]",
                                        "Delayed recall z-score [latest result]",
                                        "Delayed recall [year of latest result]",
                                        "Delayed recall [decline per year]"
                                        // "Memory test (date)"
                            ]
    };

var label_counter = 0;

add_symbol_advanced("advancedContainer");

function add_symbol_advanced($symbol) {
    switch ($symbol) {

        case "advancedContainer":

            $("#advancedContainer").append($type_sample.format(["advanced" + $advanced_count, ""]));

            label_counter++;
            $("#advanced" + $advanced_count).append($label.format([get_badge_colour(label_counter), String.fromCharCode(64 + label_counter)]));

            $options = add_options_group($option_select2.format(["span4", "keys advanced_keys" + $advanced_count]),  $phen_attr_with_cat_array);
            $("#advanced" + $advanced_count).append($options);

            // if ($advanced_count == 1)
            //     $("#advanced" + $advanced_count).append($option_select2.format(["span4", "keys advanced_keys" + $advanced_count]));
            // else {
            //     $options = add_options($option_select2.format(["span4", "keys advanced_keys" + $advanced_count]), advanced_keys);
            //     $("#advanced" + $advanced_count).append($options);
            // }

            $("." + ["advanced_keys" + $advanced_count]).select2({placeholder: "--Select an attribute--"});

            $options_1 = add_options($option.format(["span2", "input-small conditions", "", ""]), $advanced_option_1);
            $("#advanced" + $advanced_count).append($options_1);

            $options_2 = add_options($option.format(["span2", "input-large advanced_values\" disabled ", "", ""]), $advanced_option_2);
            $("#advanced" + $advanced_count).append($options_2);

            $("#advanced" + $advanced_count).append($add_remove_btn_advanced.format(["offset1"]));

            $advanced_count += 1;
            break;
    }
}

var advanced_keys = new Array();
var advanced_values = new Array();
var querystr;

$(document).ready(function () {

    $('#filter').keyup(function () {
        var rex = new RegExp($(this).val(), 'i');

        if($("input[name='show_all']").attr('checked') != "checked") {
            $('.searchable tr.pre_active').hide();
            $('.searchable tr.pre_active').filter(function () {
                return rex.test($(this).text());
            }).show();
        } else {
            $('.searchable tr').hide();
            $('.searchable tr').filter(function () {
                return rex.test($(this).text());
            }).show();
        }

        collapse = $(this).closest("div[id$='Container']").parent().find('.btn-collapse');
        container = $(this).closest("div[id$='Container']");
        
        collapse.removeClass("btn-success").addClass("btn-info");
        collapse.find('i').removeClass("icon-chevron-down").addClass("icon-chevron-left")
        $(collapse.parent().parent().next().collapse('hide')).removeClass("container_border");

        collapse.removeClass("btn-info").addClass("btn-success");
        collapse.find('i').removeClass("icon-chevron-left").addClass("icon-chevron-down");
        $(collapse.parent().parent().next().collapse('show')).addClass('container_border');

        if (navigator.appVersion.indexOf("MSIE 10") !== -1) {
            container.css('height', '0px');
            container.css('height', 'auto');
        }
        
    });

    $network_key = $("#network_key").val();

    // advanced
    $(document).on('change', '.keys', function () {
        $current_advanced_values = $(this).parent().parent().find('.advanced_values').prop('disabled', '').parent();
        $new_advanced_values = add_options($option.format(["span2", "input-large advanced_values", "", ""]), $advanced_option_2.concat(advanced_values[$(this).val()]));
        $current_advanced_values.replaceWith($new_advanced_values);
    });

    $(document).on('change', "select.advanced_values", function () {
        if ($(this).val() === "[Input your own value]") {
            $(this).parent().append('<div class="input-append advanced_custom_value">\n\
                                <input class="input-medium advanced_values" data-type="advanced" type="text" placeholder="Enter a value">\n\
                                <span class="add-on"><i class="icon-share-alt"></i></span>\n\
                            </div>');
            $advanced_values = $(this).remove();
        }
    });

    $(document).on('click', '.advanced_custom_value .icon-share-alt', function () {
        $(this).parent().parent().parent().append($advanced_values)
                .find('option:contains("--Select a value--")').attr('selected', 'selected')
                .parent().prev().remove();
    });

    $(document).on('click', 'button.add_advanced', function () {
        collapse = $(this).closest("div[id$='Container']").parent().find('.btn-collapse');

        $this = $(this).closest("div[id$='Container']");
        $this.removeClass('container_border');
        add_symbol_advanced($(this).closest("div[id$='Container']").attr('id'));
        $(this).closest("div[id$='Container']").find(".remove_advanced").removeClass('hidden');
        $add_btn = $(this).remove();

        collapse.removeClass("btn-success").addClass("btn-info");
        collapse.find('i').removeClass("icon-chevron-down").addClass("icon-chevron-left")
        $(collapse.parent().parent().next().collapse('hide')).removeClass("container_border");

        collapse.removeClass("btn-info").addClass("btn-success");
        collapse.find('i').removeClass("icon-chevron-left").addClass("icon-chevron-down");
        $(collapse.parent().parent().next().collapse('show')).addClass('container_border');
    });

    $(document).on('click', 'button.remove_advanced', function () {

        collapse = $(this).closest("div[id$='Container']").parent().find('.btn-collapse');
        container = $(this).closest("div[id$='Container']");

        if ($(this).closest('.row-fluid').is(":first-child")) {
            // $(this).closest('.row-fluid').next().remove();
        } else {
            // $(this).closest('.row-fluid').prev().remove();
            if ($(this).closest('.row-fluid').is(":last-child")) {
                $(this).closest('.row-fluid').prev().find(".add_remove_btn_advanced").prepend($add_btn);
            }
        }

        if ($(this).closest('.row-fluid').siblings().length === 1) {
            $(this).closest('.row-fluid').siblings('.row-fluid').find('.remove_advanced').addClass('hidden');
        }

        $(this).closest('.row-fluid').remove();

        label_counter = 0;
        $(".badge").each(function(index) {
            label_counter++;
            $(this).removeClass().addClass(get_badge_colour(label_counter));
            $(this).html(String.fromCharCode(64 + label_counter));
        });

        collapse.removeClass("btn-success").addClass("btn-info");
        collapse.find('i').removeClass("icon-chevron-down").addClass("icon-chevron-left")
        $(collapse.parent().parent().next().collapse('hide')).removeClass("container_border");

        collapse.removeClass("btn-info").addClass("btn-success");
        collapse.find('i').removeClass("icon-chevron-left").addClass("icon-chevron-down");
        $(collapse.parent().parent().next().collapse('show')).addClass('container_border');

        if (navigator.appVersion.indexOf("MSIE 10") !== -1) {
            container.css('height', '0px');
            container.css('height', 'auto');
        }
    });

    $(document).on('click', '.icon-remove-circle', function () {
        $(this).parent().siblings('input').val('').focus();
    });

    $("#reset_advanced").click(function(e) {
        e.preventDefault();
        $('#advancedContainer input').val('');
        $('#advancedContainer select').prop('selectedIndex',0);
        $("#advancedContainer .keys").select2('val', 'All');
    });

    function validate_query_string(str, alpha_max) {
        var invalid = false; 
        str = str.trim();
        arr = [];

        if(str == "") invalid = true;

        var opr = true;
        var alpha = false;

        strstr = "";
        $.each(str.split(" "), function(key, val) {
            if(val.trim() != "") strstr = strstr + " " + val.trim();
        }); 
        str = strstr.trim();

        var s = "";
        for (var i = 0, len = str.length; i < len; i++)
            if(!(str[i] == " " && i > 0 && (str[i-1] == '(' || str[i+1] == ')' || str[i-1] == ' ' || str[i+1] == ' '))) s = s + str[i];

        // console.log("s: " + s);
        str = "";
        str = s;

        $.each(str.split(" "), function(key, val) {
            // console.log(key + " " + val);
            val = val.trim();
            if(val != "") {
                console.log(val.length);
                if(val.length == 3 && val.charCodeAt(1) >=65 && val.charCodeAt(1) <= alpha_max) {
                    arr.push(val[1]);
                    alpha = true;
                    opr = false;
                } else if((val == "AND" || val == "OR") && alpha) {
                    arr.push(val);
                    opr = true;
                    alpha = false;
                } else if((val.length > 1 && ((val[0] == "(" && val.charCodeAt(val.length-1) >= 65 && val.charCodeAt(val.length-1) <= alpha_max) || (val[val.length-1] == ")" && val.charCodeAt(0) >= 65 && val.charCodeAt(0) <= alpha_max)) && opr)
                || (val.length == 1 && val.charCodeAt(0) >= 65 && val.charCodeAt(0) <= alpha_max && opr)) {
                    arr.push(val);
                    alpha = true;
                    opr = false;
                } else {
                    invalid = true;
                    console.log("Invalid: " + val);
                }
            }
        });

        if(invalid) {
            console.log("Invalid");
            return false;
        }

        // console.log(arr.join(" "));
        return arr.join(" ");
    }

    var output_query_string = "";

    $("#buildQuery_advanced").click(function () {

        var queryString = validate_query_string($("input#queryString").val().toUpperCase(), label_counter + 64);

        $("#query_result_advanced").empty();
       
        $idCount = 1;
        phe = validate_advanced("buildQueryEvent");

        if (!phe) {
            console.log("Build Query: Not Validated");
            return false;
        }

        var ambigious_error = "";

        if(!queryString) {
            $.growl.error({message: "You have not entered a valid query string."});
            console.log("Query string not validated.");
            return;
        } else {
            $("input#queryString").val(queryString);
            // console.log("validated");
            $.ajax({
                url: baseurl + 'discover/validate_query_string/',
                type: 'POST',
                dataType: 'JSON',
                data: {'query_string': queryString},
            }).done(function(data) {
                // console.log(data);
                if(data.status == "error") {
                    $.each(data.choices, function(key, value) {
                        ambigious_error += value + "\n";
                    });
                    alert(ambigious_error);
                } else if(data.status == "success") {
                    output_query_string = data.choices;
                    build_query();
                }
                // console.log(output_query_string);
            });
        }
    });
    

    function build_query() {
            $genotype_advanced = $('#logic_genotype_advanced .active').html();
        $phen_phen = $('.logic_advanced .active').html() ? $('.logic_advanced .active').html() : "";

        
        $coordinate = $sequence = $gene = $hgvs = $phen = $other = "";

        $query = "";
        $.each($("#queryString").val().split(" "), function(index, val) {
            if(val == "AND" || val == "OR")
                $query = $query + val + " ";
            else {
                if(val.length == 1)
                    $query = $query + (val.charCodeAt(0) - 64) + " ";
                else if(val[0] == '(')
                    $query = $query + val[0] + (val.charCodeAt(1) - 64) + " ";
                else
                    $query = $query + (val.charCodeAt(0) - 64) + val[1] + " ";
            }   
        });

        // console.log($query);

        $arr = {
            "queryMetadata": {
                "queryId": "<identifier>",
                "queryType": "once|periodic",
                "queryStart": "<Date, Time>",
                "queryStop": "<Date, Time>",
                "queryLabel": "<identifier>",
                "queryResultLevel": "Exists|Counts|Records",
                "submitter": {
                    "id": "SubmitterPersonID",
                    "name": "First [Middle] Last",
                    "email": "email@domain.com",
                    "institution": "AffiliationOfSubmitterPerson",
                    "urls": ["SubmitterPersonalURL", "..."]
                },
                "contact": {
                    "id": "ContactPersonID",
                    "name": "First [Middle] Last",
                    "email": "email@domain.com",
                    "institution": "AffiliationOfContactPerson",
                    "urls": ["ContactPersonURL", "..."]
                }
            },
            "query": {
                "phenotypeFeature": getJSON_advanced()
            }
        };

        $.each($arr.query, function (key, value) {
            if (value.length === 0)
                delete $arr.query[key];
        });

        if (Object.keys($arr.query).length === 0) {
            alert("You have to select at least on type in order to proceed to a query!");
            return false;
        }
        
        $.extend($arr, {"queryStatement": $query.trim(), "network_to_search": $network_key});
        console.log(JSON.stringify($arr, null, '\t'));
//        alert("queryString -> " + JSON.stringify($arr));

        $('#waiting_advanced').show(500);
        $.ajax({url: baseurl + 'discover/query/' + $network_key,
            dataType: 'html',
            delay: 200,
            type: 'POST',
            data: {'jsonAPI': $arr},
            success: function (data) {
                // console.log(data);
                // console.log(output_query_string);
                $('#waiting_advanced').hide(500);
                $("#query_result_advanced").html(data);
            }, 
            complete: function() {
                if($('input[name="create_precan_query"]').val() == "yes")
                    $("#tab-advanced #query_for_disp").append('&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="add_query" class="btn btn-warning">Save As Canned Query</button>');
            }
        });
    }

    // $(".container").append('&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="add_query" class="btn btn-warning">Display</button>');
    // $("#query_for_disp").append('&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="add_query" class="btn btn-warning">Display</button>');
    
    $("#save_query").click(function(e) {
        $source = $("select[name='save_source']").val();
        $url = $("select[name='save_source'] option:selected").attr('id');

        $case_control = $("select[name='save_case_control']").val();
        $notes = $("textarea[name='notes']").val();
        $queryString = $("#add_query").parent().html().split("&nbsp;")[0]
        if($source == -1) $("#err_source").removeClass('hide');
        else $("#err_source").addClass('hide');

        if($case_control == -1) $("#err_case_control").removeClass('hide');
        else $("#err_case_control").addClass('hide');

        if($source == -1 || $case_control == -1) return;

        $.extend($arr, {"network_key": $network_key, "base_url": $url, "queryString": $queryString, "source": $source, "case_control": $case_control, "notes": $notes});

        $.ajax({
            url: baseurl + 'discover/save_query',
            type: 'POST',
            dataType: 'JSON',
            data: {json: $arr},
        }).done(function() {
            $("#modal_add_query").modal('hide');
            if($("select[name='source']").find("option[value='" + $source + "']").length == 0)
                $("select[name='source']").append("<option value='" + $source + "'>" + $source + "</option>");
        });
    });

    function update_canned_queries(data) {
        $(".searchable > tr").remove();

        $.each(data.precan_active, function(index, val) {
            $("tbody.searchable").append('\n\
                <tr class="pre_active">\n\
                    <td><input type="radio" name="precannedQueries" value="' + val['api'] + '">' + '\n\
                        <a style="margin-left: 10px;" class="btn btn-small btn-info precan_info" href="#" rel="popover" data-content="' + (val['notes'].trim() == "" ? "No notes available" : val['notes'].trim())  + '" data-original-title=""><i class="icon-info-sign"></i></a>\n\
                    </td>\n\
                    <td><div>' + val['source'] + '</div></td>\n\
                    <td><div>' + val['case_control'] + '</div></td>\n\
                    <td><div>' + val['queryString'] + '</div></td>\n\
                    <td><div>' + val['user_email'] + '</div></td>\n\
                    <td><div>' + val['date_time'] + '</div></td>\n\
                    <td>\n\
                        <a class="btn btn-small btn-warning precan_deactivate" href="#"><i class="icon-remove"></i></a>\n\
                        <a class="btn btn-small btn-danger precan_delete" href="#"><i class="icon-trash"></i></a>\n\
                    </td>\n\
                </tr>');
        });

        if($("input[name='show_all']").attr('checked') == "checked") {
            $.each(data.precan_inactive, function(index, val) {
                $("tbody.searchable").append('\n\
                    <tr class="pre_inactive">\n\
                        <td><input type="radio" disabled name="precannedQueries" value="' + val['api'] + '">' + '\n\
                            <a style="margin-left: 10px;" class="btn btn-small btn-info precan_info" href="#" rel="popover" data-content="' + (val['notes'].trim() == "" ? "No notes available" : val['notes'].trim())  + '" data-original-title=""><i class="icon-info-sign"></i></a>\n\
                        </td>\n\
                        <td><div>' + val['source'] + '</div></td>\n\
                        <td><div>' + val['case_control'] + '</div></td>\n\
                        <td><div>' + val['queryString'] + '</div></td>\n\
                        <td><div>' + val['user_email'] + '</div></td>\n\
                        <td><div>' + val['date_time'] + '</div></td>\n\
                        <td>\n\
                            <a class="btn btn-small btn-success precan_activate" href="#"><i class="icon-ok"></i></a>\n\
                            <a class="btn btn-small btn-danger precan_delete" href="#"><i class="icon-trash"></i></a>\n\
                        </td>\n\
                    </tr>');
            });     
        } else {
            $.each(data.precan_inactive, function(index, val) {
                $("tbody.searchable").append('\n\
                    <tr class="pre_inactive hide">\n\
                        <td><input type="radio" disabled name="precannedQueries" value="' + val['api'] + '">' + '\n\
                            <a style="margin-left: 10px;" class="btn btn-small btn-info precan_info" href="#" rel="popover" data-content="' + (val['notes'].trim() == "" ? "No notes available" : val['notes'].trim())  + '" data-original-title=""><i class="icon-info-sign"></i></a>\n\
                        </td>\n\
                        <td><div>' + val['source'] + '</div></td>\n\
                        <td><div>' + val['case_control'] + '</div></td>\n\
                        <td><div>' + val['queryString'] + '</div></td>\n\
                        <td><div>' + val['user_email'] + '</div></td>\n\
                        <td><div>' + val['date_time'] + '</div></td>\n\
                        <td>\n\
                            <a class="btn btn-small btn-success precan_activate" href="#"><i class="icon-ok"></i></a>\n\
                            <a class="btn btn-small btn-danger precan_delete" href="#"><i class="icon-trash"></i></a>\n\
                        </td>\n\
                    </tr>');
            });
        }
    }

    $('.nav-tabs a').click(function(e) {

        // if ($(this).attr('id') === "isPhenotype")
        //     $parent = $(this).parent().parent().parent();
        // else
        //     $parent = $(this).parent().parent();

        // if ($(this).attr("data-collapseStatus") === "false") {
        //     $(this).removeClass("btn-info").addClass("btn-success");
        //     $(this).find('i').removeClass("icon-chevron-left").addClass("icon-chevron-down");
        //     $($(this).parent().parent().next().collapse('show')).addClass('container_border');
        //     $(this).attr("data-collapseStatus", "true");
        //     $parent.prev().children('a').removeClass('disabled');

        // } else {
        //     $collapse = true;

        //     switch ($(this).parent().parent().next().attr('id')) {
                
        //         case "phenotypeContainer":
        //             $collapse = validate_Phenotype("collapseEvent");
        //             break;
               
        //     }
        //     if ($collapse) {
        //         $(this).removeClass("btn-success").addClass("btn-info");
        //         $(this).find('i').removeClass("icon-chevron-down").addClass("icon-chevron-left");
        //         $($(this).parent().parent().next().collapse('hide')).removeClass("container_border");
        //         $(this).attr("data-collapseStatus", "false");
        //         $parent.prev().children('a').addClass('disabled');
        //     }
        // }
        
        if($(this).html() == "Precanned Query") {
            $(this).parent().parent().parent().find('.tab-content').addClass('cannedStyle');
        } else {
            $(this).parent().parent().parent().find('.tab-content').removeClass('cannedStyle');
        }
    });

    $("select[name='source']").change(function(e) {
        $source = $("select[name='source']").val();
        $case_control = $("select[name='case_control']").val();
        $show_all = $("input[name='show_all']").attr('checked') == "checked" ? 1 : 0;
        $this = $(this);
        $.ajax({
            url: baseurl + 'discover/get_precan',   
            type: 'POST',
            dataType: 'JSON',
            data: {'source': $source, 'case_control': $case_control, 'network_key': $network_key},
        }).done(function(data) {
            console.log(data);
            update_canned_queries(data);
        })
        .always(function() {
            $("[rel=popover]").popover({placement:'bottom'});
            
            collapse = $this.closest("div[id$='Container']").parent().find('.btn-collapse');
            container = $this.closest("div[id$='Container']");
            
            collapse.removeClass("btn-success").addClass("btn-info");
            collapse.find('i').removeClass("icon-chevron-down").addClass("icon-chevron-left")
            $(collapse.parent().parent().next().collapse('hide')).removeClass("container_border");

            collapse.removeClass("btn-info").addClass("btn-success");
            collapse.find('i').removeClass("icon-chevron-left").addClass("icon-chevron-down");
            $(collapse.parent().parent().next().collapse('show')).addClass('container_border');

            if (navigator.appVersion.indexOf("MSIE 10") !== -1) {
                container.css('height', '0px');
                container.css('height', 'auto');
            }
        });
    });

    $("select[name='case_control']").change(function(e) {
        $source = $("select[name='source']").val();
        $case_control = $("select[name='case_control']").val();
        $show_all = $("input[name='show_all']").attr('checked') == "checked" ? 1 : 0;
        $this = $(this);

        $.ajax({
            url: baseurl + 'discover/get_precan',   
            type: 'POST',
            dataType: 'JSON',
            data: {'source': $source, 'case_control': $case_control, 'network_key': $network_key},
        }).done(function(data) {
             console.log(data);
             update_canned_queries(data); 
        })
        .always(function() {
            $("[rel=popover]").popover({placement:'bottom'});

            collapse = $this.closest("div[id$='Container']").parent().find('.btn-collapse');
            container = $this.closest("div[id$='Container']");
            
            collapse.removeClass("btn-success").addClass("btn-info");
            collapse.find('i').removeClass("icon-chevron-down").addClass("icon-chevron-left")
            $(collapse.parent().parent().next().collapse('hide')).removeClass("container_border");

            collapse.removeClass("btn-info").addClass("btn-success");
            collapse.find('i').removeClass("icon-chevron-left").addClass("icon-chevron-down");
            $(collapse.parent().parent().next().collapse('show')).addClass('container_border');

            if (navigator.appVersion.indexOf("MSIE 10") !== -1) {
                container.css('height', '0px');
                container.css('height', 'auto');
            }
        });
    }); 
    
    
    $("input[name='show_all']").change(function(e) {
        if($(this).attr('checked') == "checked")
            $(".searchable > .pre_inactive").show();
        else
            $(".searchable > .pre_inactive").hide();

        $this = $(this);
        collapse = $this.closest("div[id$='Container']").parent().find('.btn-collapse');
        container = $this.closest("div[id$='Container']");
        
        collapse.removeClass("btn-success").addClass("btn-info");
        collapse.find('i').removeClass("icon-chevron-down").addClass("icon-chevron-left")
        $(collapse.parent().parent().next().collapse('hide')).removeClass("container_border");

        collapse.removeClass("btn-info").addClass("btn-success");
        collapse.find('i').removeClass("icon-chevron-left").addClass("icon-chevron-down");
        $(collapse.parent().parent().next().collapse('show')).addClass('container_border');

        if (navigator.appVersion.indexOf("MSIE 10") !== -1) {
            container.css('height', '0px');
            container.css('height', 'auto');
        }
    });

    $(document).on('click', '.precan_activate', function () {
        $ths = $(this);
        $source = $("select[name='source']").val();
        $case_control = $("select[name='case_control']").val();

        $dat = $(this).parent().parent();
        $api = $(this).parent().prev().find('input').val();
        $queryString = $(this).parent().parent().find('input').val();
        $.ajax({
            url: baseurl + 'discover/precan_status/',
            type: 'POST',
            dataType: 'JSON',
            data: {'status': "activate", 'source': $source, 'case_control': $case_control, 'queryString': $queryString, 'network_key': $network_key},
        }).done(function() {
            $dat = $dat.remove();
            $dat = $dat.removeClass('pre_inactive').addClass('pre_active');
            $(".searchable").prepend($dat);
            $(".searchable tr:first td input").attr('checked', false);
            $(".searchable tr:first td input").attr('disabled', false);
            $(".searchable tr:first").find('td a.precan_activate').removeClass('precan_activate').addClass('precan_deactivate').removeClass('btn-success').addClass('btn-warning').children().removeClass('icon-ok').addClass('icon-remove');
        });
        
    });

    $(document).on('click', '.precan_deactivate', function () {
        $source = $("select[name='source']").val();
        $case_control = $("select[name='case_control']").val();

        $dat = $(this).parent().parent();
        $api = $(this).parent().prev().find('input').val();
        $queryString = $(this).parent().parent().find('input').val();
         $.ajax({
            url: baseurl + 'discover/precan_status/',
            type: 'POST',
            dataType: 'JSON',
            data: {'status': "deactivate", 'source': $source, 'case_control': $case_control, 'queryString': $queryString, 'network_key': $network_key},
        }).done(function() {
            if($("input[name='show_all']").attr('checked') == "checked") {
                $dat = $dat.remove();
                $dat = $dat.removeClass('pre_active').addClass('pre_inactive');
                $(".searchable").append($dat);
                $(".searchable tr:last td input").attr('checked', false);
                $(".searchable tr:last td input").attr('disabled', true);
                $(".searchable tr:last").find('td a.precan_deactivate').removeClass('precan_deactivate').addClass('precan_activate').removeClass('btn-warning').addClass('btn-success').children().removeClass('icon-remove').addClass('icon-ok');
            } else {
                $dat = $dat.remove();
                $dat = $dat.removeClass('pre_active').addClass('pre_inactive').addClass('hide');
                $(".searchable").append($dat);
                $(".searchable tr:last td input").attr('checked', false);
                $(".searchable tr:last td input").attr('disabled', true);
                $(".searchable tr:last").find('td a.precan_deactivate').removeClass('precan_deactivate').addClass('precan_activate').removeClass('btn-warning').addClass('btn-success').children().removeClass('icon-remove').addClass('icon-ok');
            }
        });
    });

    // #precan_table > tbody > tr:nth-child(3) > td:nth-child(1) > input[type="radio"]

    $(document).on('click', '.precan_delete', function () {

        if(!confirm("Are you sure you want to delete this precanned query?")) return;

        $source = $("select[name='source']").val();
        $case_control = $("select[name='case_control']").val();

        $dat = $(this);
         $.ajax({
            url: baseurl + 'discover/precan_status/',
            type: 'POST',
            dataType: 'JSON',
            data: {'status': "delete", 'source': $source, 'case_control': $case_control, 'queryString': $(this).parent().parent().find('input').val(), 'network_key': $network_key},
        }).done(function(data) {
            $dat.parent().parent().remove();
        });
    });

    $(document).on('click', '#add_query', function () {
        $("select[name='save_source']").html('');
        $("select[name='save_source']").append('<option value="-1">Select a source</option>');
        
        $.each($("#tab-advanced a.results_source"), function(index, val) {
            var source = $(this).html();
            var url = $(this).attr('id');
            if(!$(this).parent().parent().find("a[data-content='The display of record counts has been limited to specific users for this source.']").length > 0)
                $("select[name='save_source']").append("<option class='sss' id='" + url + "' value='" + source + "'>" + source + "</option>");
        });

        $("#modal_add_query").modal('show');
    });

    function getJSON_advanced() {

        $parentId = $("#advancedContainer");
        $parentType = $parentId.attr('data-type');
        $arr = [];
        $parentId.children('.type_sample').each(function () {

            if ($(this).find('select.keys').val().trim()) {
                if($query) $query += " " + $phen_phen + " ";
                
                var operator = $(this).find('.conditions').val().toString();
                var value = "";

                if($(this).find('select.keys').val().toString() == "Age [by start of this year]") {
                    switch(operator) {
                        case "<":
                            operator = ">";
                            break;
                        case "<=":
                            operator = ">=";
                            break;
                        case ">":
                            operator = "<";
                            break;
                        case ">=":
                            operator = "<=";
                    }
                    value = new Date().getFullYear() - (parseInt($(this).find('.advanced_values').val().toString()) + 1);
                } else {
                    value = $(this).find('.advanced_values').val().toString();
                }

                $advanced = {
                    "querySegmentID": $(this).find('.badge').html().charCodeAt(0) - 64,
                    "operator": operator,
                    "phenotypeConcept": {
                        "cursivePhenotypeConcept": {"term": $(this).find('select.keys').val().toString(), "source": ""}
                    },
                    "phenotypeFeature": {"value": value, "units": "", "source": ""}
                };

                $arr.push($advanced);
            }
        });
        
        return $arr;
    }

    function validate_advanced($for) {
        $parentId = $("#advancedContainer");
        $parentType = $parentId.attr('data-type');

        if ($for === "collapseEvent") {
            if ($parentId.children('.type_sample').length === 1) {
                if ($parentId.find('select.keys').val().trim().length > 0)
                {
                    $.growl.notice({message: "Non-empty sections cannot be collapsed."});
                    return false;
                }
            } else {
                $error = false;
                $parentId.children('.type_sample').each(function () {
                    if ($(this).find('select.keys').val().trim().length > 0) {
                        $.growl.notice({message: "Non-empty sections cannot be collapsed."});
                        $error = true;
                        return false;
                    }
                });

                if ($error)
                    return false;
            }
            return true;
        } else if ($for === "buildQueryEvent") {
            $error = false;
            $parentId.children('.type_sample').each(function () {
                if ($(this).find('select.keys').val().trim().length > 0) {
                    condition_value = $(this).find('.conditions').val();
                    field_value = $(this).find('.advanced_values').val();
                    if (field_value === "--Select a value--") {
                        $.growl.error({message: "You have not entered a phenotype value(s)"});
                        $error = true;
                    } else if (!advanced_validation(condition_value, field_value)) {
                        $error = true;
                    }
                } else {
                    $.growl.error({message: "You have one or more incomplete sections"});
                    $error = true;
                }
            });
            return !$error;
        }
    }

    function advanced_validation(condition_value, field_value) {
        if (condition_value === '>' || condition_value === '<' || condition_value === '>=' || condition_value === '<='
                || condition_value === '=' || condition_value === '≠') {
            if (!isNumber(field_value)) {
                $.growl.error({message: "A numeric comparison operator was specified but the entered value is not numeric, unable to proceed with the query."});
                return false;
            }
            return true;
        }
        // Throw error if NULL query entered with anything apart from IS or IS NOT
        else if (condition_value.toLowerCase() === 'is like' || condition_value.toLowerCase() === 'is'
                || condition_value.toLowerCase() === 'is not' || condition_value.toLowerCase() === 'is not like') {
            if (condition_value.toLowerCase() === 'is like' || condition_value.toLowerCase() === 'is not like') {
                if (field_value.toUpperCase() === 'NULL') {
                    $.growl.error({message: "NULL queries are only possible with 'IS' or 'IS NOT' operators, unable to proceed with the query."});
                    return false;
                } else if (isNumber(field_value)) {
                    $.growl.warning({message: "You have specified a string comparison operator but supplied a numeric value. Query may not return proper results."});
                }
            } else {
                if (isNumber(field_value)) {
                    $.growl.warning({message: "You have specified a string comparison operator but supplied a numeric value. Query may not return proper results."});
                }
            }

            return true;
        }
    }

    $(document).on('focus', '.conditions', function () {
        $(this).find('option[value="---------------"]').attr('disabled', 'disabled');
    });
});