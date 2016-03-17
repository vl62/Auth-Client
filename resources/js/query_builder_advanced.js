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

var label_counter = 0;

add_symbol_advanced("advancedContainer");

function add_symbol_advanced($symbol) {
    switch ($symbol) {

        case "advancedContainer":

            $("#advancedContainer").append($type_sample.format(["advanced" + $advanced_count, ""]));

            label_counter++;
            $("#advanced" + $advanced_count).append($label.format([get_badge_colour(label_counter), String.fromCharCode(64 + label_counter)]));

            if ($advanced_count == 1)
                $("#advanced" + $advanced_count).append($option_select2.format(["span4", "keys advanced_keys" + $advanced_count]));
            else {
                $options = add_options($option_select2.format(["span4", "keys advanced_keys" + $advanced_count]), advanced_keys);
                $("#advanced" + $advanced_count).append($options);
            }

            $("." + ["advanced_keys" + $advanced_count]).select2({placeholder: "--Select an attribute--"});

            $options_1 = add_options($option.format(["span2", "input-medium conditions", "", ""]), $advanced_option_1);
            $("#advanced" + $advanced_count).append($options_1);

            $options_2 = add_options($option.format(["span2", "input-medium advanced_values\" disabled ", "", ""]), $advanced_option_2);
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

    // $('#queryString').on('input', function(evt) {
    //     $(this).val(function (_, val) {
    //         return val.toUpperCase();
    //     });
    // });

    $("#loader").addClass('hide');
    $network_key = $("#network_key").val();
    $.ajax({url: baseurl + 'admin/get_phenotype_attributes_for_network/' + $network_key,
        dataType: 'json',
        delay: 200,
        type: 'POST',
        success: function (json) {
            $.each(json, function (attribute, value) {
                $('select.advanced_keys1').append($('<option>').text(attribute).attr('value', attribute));
                advanced_keys.push(attribute);
                var index = value.indexOf("not all values displayed");
                if(index != -1) {
                    value.splice(index, 1);
                    value.push("----------------------", "not all values might be displayed");
                }
                
                advanced_values[attribute] = value;
            });
            $("#loader").addClass('hide');
        }
    });

    // advanced
    $(document).on('change', '.keys', function () {
        $current_advanced_values = $(this).parent().parent().find('.advanced_values').prop('disabled', '').parent();
        $new_advanced_values = add_options($option.format(["span2", "input-medium advanced_values", "", ""]), $advanced_option_2.concat(advanced_values[$(this).val()]));
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

    // Rest
    // $(document).on('click', ".btn-collapse", function () {
    //     $parent = $(this).parent().parent().parent();

    //     if ($(this).attr("data-collapseStatus") === "false") {
    //         $(this).removeClass("btn-info").addClass("btn-success");
    //         $(this).find('i').removeClass("icon-chevron-left").addClass("icon-chevron-down");
    //         $($(this).parent().parent().next().collapse('show')).addClass('container_border');
    //         $(this).attr("data-collapseStatus", "true");
    //         $parent.prev().children('a').removeClass('disabled');

    //     } else {
    //         $collapse = true;
    //         $collapse = validate_advanced("collapseEvent");
    //         if ($collapse) {
    //             $(this).removeClass("btn-success").addClass("btn-info");
    //             $(this).find('i').removeClass("icon-chevron-down").addClass("icon-chevron-left");
    //             $($(this).parent().parent().next().collapse('hide')).removeClass("container_border");
    //             $(this).attr("data-collapseStatus", "false");
    //             $parent.prev().children('a').addClass('disabled');
    //         }
    //     }
    // });

    $(document).on('click', 'button.add_advanced', function () {

        parentId = $(this).closest("div[id$='Container']").attr('id');
        add_symbol_advanced(parentId);
        $("#" + parentId).find(".remove_advanced").removeClass('hidden');
        $add_btn = $(this).remove();
    });

    $(document).on('click', 'button.remove_advanced', function () {

        // parent = $(this).closest('.row-fluid');

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
    });

    // AND-OR Toggle Function
    // $(document).on('click', ".btn-toggle", function () {
    //     if ($("a", this).hasClass("disabled"))
    //         return;
    //     if ($(this).find('.btn-primary').length > 0) {
    //         if ($(this).parent().attr('id') === "advancedContainer") {
    //             $(this).parent().find('.logic_advanced .btn').toggleClass('active');
    //             $(this).parent().parent().find('.logic_advanced .btn').toggleClass('btn-primary');
    //         } else {
    //             $(this).find('.btn').toggleClass('active');
    //             $(this).find('.btn').toggleClass('btn-primary');
    //         }
    //     }
    // });

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
        console.log("trim: " + str);
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
                $('#waiting_advanced').hide(500);
                $("#query_result_advanced").html(data);
                $("#query_result_advanced h4").html(output_query_string);
            }
        });
    }

    function getJSON_advanced() {

        $parentId = $("#advancedContainer");
        $parentType = $parentId.attr('data-type');
        $arr = [];
        $parentId.children('.type_sample').each(function () {

            if ($(this).find('select.keys').val().trim()) {
                if($query) $query += " " + $phen_phen + " ";
                
                $advanced = {
                    "querySegmentID": $(this).find('.badge').html().charCodeAt(0) - 64,
                    "operator": $(this).find('.conditions').val().toString(),
                    "phenotypeConcept": {
                        "cursivePhenotypeConcept": {"term": $(this).find('select.keys').val().toString(), "source": ""}
                    },
                    "phenotypeFeature": {"value": $(this).find('.advanced_values').val().toString(), "units": "", "source": ""}
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