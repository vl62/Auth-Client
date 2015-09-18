String.prototype.format = function (arguments) {
    var s = this, i = arguments.length;
    if (i === 0)
        return s;
    while (i--)
        s = s.replace(new RegExp('\\{' + i + '\\}', 'gm'), arguments[i]);
    return s;
};

function add_options(option, arguments) {
    $select = $.parseHTML(option);
    for (var arg in arguments)
        $("<option />", {value: arguments[arg], text: arguments[arg]}).appendTo($($select).children('select'));
    return $select;
}

function add_options_group(option, arguments) {
    var $select = $.parseHTML(option);
    for (var key in arguments) {
        var group = $('<optgroup label="' + key + '" />');
        for (var value in arguments[key])
            $("<option />", {value: arguments[key][value], text: arguments[key][value]}).appendTo(group);
        group.appendTo($($select).children('select'));
    }

    return $select;
}

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

var gene_keys = new Array();
$.ajax({url: baseurl + 'discover/autocomplete_query_builder/gene',
    dataType: 'json',
    delay: 200,
    type: 'POST',
    success: function (json) {
        $.each(json, function (i, value) {
            gene_keys.push(value);
        });
    },
});

$and_or_logic = '<div class="btn-group btn-toggle logic_phenotype" id="" style="margin-bottom:20px">\n\
                        <a class="btn btn-medium {0}">AND</a>\n\
                        <a class="btn btn-medium {1}">OR</a>\n\
                     </div>';

$or_logic = '<div class="row"><div class="btn-group span3 offset5 pagination-centered logic" style="padding-bottom:10px">\n\
                        <a class="btn btn-medium btn-primary disabled">OR</a>\n\
                 </div></div>';

$option = '<div class="pagination-centered {0}">\n\
                <select class="{1}" data-type="operator" {2}></select>\n\
               {3}\n\
                </div>';

$option_select2 = '<div class="pagination-centered {0}">\n\
                            <select class="input-xlarge {1}" style="margin-bottom:10px">\n\
                            <option></option>\n\
                            </select>\n\
                        </div>';

$textbox = '<div class="{0} pagination-centered">\n\
                    <div class="input-append">\n\
                        <input class="input_field textValidate {1}" data-type="{2}" type="text" placeholder="{3}">\n\
                        <span class="add-on"><i class="icon-remove-circle"></i></span>\n\
                    </div>\n\
                </div>';

$options_container = '<div class="span7 pagination-centered"></div>';

$textbox_with_label = '<div class="input-prepend input-append text_with_label {0}" style="margin-bottom:10px">\n\
                            <span class="add-on">{1}</span>\n\
                                <input class="input_field query_term {2}" type="text" placeholder="{3}">\n\
                            <span class="add-on"><i class="icon-remove-circle"></i></span>\n\
                          </div>';

$add_remove_btn = '<div class="span2 {0} add_remove_btn" style="padding-top: 3px;">\n\
                        <button class="btn btn-mini btn-success add">\n\
                            <i class="icon-plus"></i>\n\
                        </button>\n\
                        <button class="btn btn-mini btn-danger remove hidden">\n\
                            <i class="icon-minus icon-white"></i>\n\
                        </button>\n\
                       </div>';

$type_sample = '<div id="{0}" class="row-fluid type_sample {1}">\n\
                    </div>';

$row = '<div class="row-fluid pagination-centered"></div>';

$genome_count = 1;
$genome_option = ["EXACT", "EXCEED", "BEGIN_BETWEEN", "END_BETWEEN", "BEGIN_AND_END_BETWEEN", "ONLY_BEGIN_BETWEEN",
    "ONLY_END_BETWEEN", "BEGIN_AT_START", "END_AT_STOP"];
$genome_textbox_start = ["span6", "Start", "input-medium start", "2000001"];
$genome_textbox_stop = ["span6", "Stop", "input-medium stop", "2000002"];
$genome_option_chr = ["Select a chromosome", "chr1", "chr2", "chr3", "chr4", "chr5", "chr6", "chr7", "chr8", "chr9", "chr10", "chr11", "chr12", "chr13", "chr14", "chr15", "chr16", "chr17", "chr18", "chr19", "chr20", "chr21", "chr22", "chrX", "chrY"];
$genome_option_build = ["Select a build", "GRCh38", "hg38", "GRCh37", "hg19", "hg18", "NCBI Build 36.1"];

$accession_count = 1;
$accession_option = ["EXACT", "EXCEED", "BEGIN_BETWEEN", "END_BETWEEN", "BEGIN_AND_END_BETWEEN", "ONLY_BEGIN_BETWEEN",
    "ONLY_END_BETWEEN", "BEGIN_AT_START", "END_AT_STOP"];
$accession_textbox_start = ["span6", "Start", "input-medium start", "2000001"];
$accession_textbox_stop = ["span6", "Stop", "input-medium stop", "2000002"];
$accession_textbox = ["span12", "Accession.Version", "input-large acc_ver", "eg. NG_007124.1"];

$dna_count = 1;
$dna_textbox = ["span5 offset3", "Enter a DNA Sequence", "input-large dnaSequence", "eg. ATGC"];

$protein_count = 1;
$protein_textbox = ["span5 offset3", "Enter a Protein Sequence", "input-large proteinSequence", "eg. ATGC"];

$hgvs_acc_array = $accession;
$hgvs_acc_array.unshift("[Input your own accession & version]");
$hgvs_acc_array.unshift("Select an accession.version");

$geneSymbol_count = 1;
$geneSymbol_option = ["IS", "IS LIKE"];
$geneSymbol_textbox = ["span5", "Enter a gene symbol", "input-large geneSymbol", "eg. BRCA1 or BRCA*"];

$hgvs_count = 1;
$hgvs_textbox_acc_ver = ["span4 offset1", "Accession and Version", "input-medium hgvs_acc_ver", "eg. NG_007124.1"];
$hgvs_textbox = ["span4 offset1", "HGVS description", "input-medium hgvs_name", "eg. c.8167G>C"];


$phenotype_count = 1;
$phenotype_option_1 = ["IS", "IS LIKE", "IS NOT", "IS NOT LIKE", "---------------", "=", "≠", "<", ">", "<=", ">="];
$phenotype_option_2 = ["--Select a value--", "NULL", "[Input your own value]"];

add_symbol("genomeContainer");
add_symbol("accessionContainer");
add_symbol("dnaContainer");
add_symbol("proteinContainer");
add_symbol("geneSymbolContainer");
add_symbol("hgvsContainer");
add_symbol("phenotypeContainer");

function add_symbol($symbol) {
    switch ($symbol) {

        case "genomeContainer":

            if ($genome_count != 1)
                $("#genomeContainer").append($or_logic);

            $("#genomeContainer").append($($type_sample.format(["genome" + $genome_count, ""])).css({"margin-top": "10px", "margin-bottom": "10px"}));

            $options = add_options($option.format(["span3", "input-large conditions", '', "<br/><button class='btn btn-info condition-info'>Info</button>"]), $genome_option);
            $("#genome" + $genome_count).append($options);

            $options_chr = add_options($option.format(["span6", "input-medium condition_ref chr" + $genome_count, "", ""]), $genome_option_chr);
            $options_build = add_options($option.format(["span6", "input-medium condition_ref build" + $genome_count, "", ""]), $genome_option_build);

            $row2 = $($row).append($textbox_with_label.format($genome_textbox_start)).append($textbox_with_label.format($genome_textbox_stop));

            $("#genome" + $genome_count).append($($options_container)
                    .append($options_chr)
                    .append($options_build)
                    .append($row2));

            $("#genome" + $genome_count).append($add_remove_btn.format([""]));
            $genome_count += 1;

            break;
        case "accessionContainer":

            if ($accession_count != 1)
                $("#accessionContainer").append($or_logic);

            $("#accessionContainer").append($($type_sample.format(["accession" + $accession_count, ""])).css({"margin-top": "10px", "margin-bottom": "10px"}));

            $options = add_options($option.format(["span3", "input-large conditions", '', "<br/><button class='btn btn-info condition-info'>Info</button>"]), $accession_option);
            $("#accession" + $accession_count).append($options);

            $row2 = $($row).append($textbox_with_label.format($accession_textbox_start)).append($textbox_with_label.format($accession_textbox_stop));

            $("#accession" + $accession_count).append($($options_container)
                    .append($textbox_with_label.format($accession_textbox))
                    .append($row2));

            $("#accession" + $accession_count).append($add_remove_btn.format([""]));
            $accession_count += 1;

            break;
        case "dnaContainer":

            if ($dna_count != 1)
                $("#dnaContainer").append($or_logic);

            $("#dnaContainer").append($type_sample.format(["dna" + $dna_count, ""]));

            $("#dna" + $dna_count).append($textbox_with_label.format($dna_textbox));

            $("#dna" + $dna_count + " input").autocomplete({
                source: gene_keys
            });

            $("#dna" + $dna_count).append($add_remove_btn.format(["offset2"]));
            $dna_count += 1;

            break;
        case "proteinContainer":

            if ($protein_count != 1)
                $("#proteinContainer").append($or_logic);

            $("#proteinContainer").append($type_sample.format(["protein" + $protein_count, ""]));

            $("#protein" + $protein_count).append($textbox_with_label.format($protein_textbox));

            $("#protein" + $protein_count + " input").autocomplete({
                source: gene_keys
            });

            $("#protein" + $protein_count).append($add_remove_btn.format(["offset2"]));
            $protein_count += 1;

            break;

        case "geneSymbolContainer":

            if ($geneSymbol_count != 1)
                $("#geneSymbolContainer").append($or_logic);

            $("#geneSymbolContainer").append($type_sample.format(["geneSymbol" + $geneSymbol_count, ""]));

            $options = add_options($option.format(["span3", "input-medium conditions", "", ""]), $geneSymbol_option);
            $("#geneSymbol" + $geneSymbol_count).append($options);

            $("#geneSymbol" + $geneSymbol_count).append($textbox_with_label.format($geneSymbol_textbox));

            $("#geneSymbol" + $geneSymbol_count + " input").autocomplete({
                source: gene_keys
            });

            $("#geneSymbol" + $geneSymbol_count).append($add_remove_btn.format(["offset2"]));
            $geneSymbol_count += 1;

            break;

        case "hgvsContainer":

            if ($hgvs_count != 1)
                $("#hgvsContainer").append($or_logic);

            $("#hgvsContainer").append($type_sample.format(["hgvs" + $hgvs_count, ""]));

            $("#hgvs" + $hgvs_count).append($textbox_with_label.format($hgvs_textbox_acc_ver));
            $("#hgvs" + $hgvs_count).append($textbox_with_label.format($hgvs_textbox));

            $("#hgvs" + $hgvs_count).append($add_remove_btn.format([""]));
            $hgvs_count += 1;

            break;

        case "phenotypeContainer":

            if ($("#phenotypeContainer").children('.type_sample').length == 1)
                $("#phenotypeContainer").append($and_or_logic.format(["btn-primary active", "btn-default"]));
            else if ($("#phenotypeContainer").children('.type_sample').length > 1) {
                if ($("#phenotypeContainer").find('.logic_phenotype .active').html() === "AND")
                    $("#phenotypeContainer").append($and_or_logic.format(["btn-primary active", "btn-default"]));
                else
                    $("#phenotypeContainer").append($and_or_logic.format(["btn-default", "btn-primary active"]));
            }

            $("#phenotypeContainer").append($type_sample.format(["phenotype" + $phenotype_count, ""]));

            if ($phenotype_count == 1)
                $("#phenotype" + $phenotype_count).append($option_select2.format(["span4 offset1", "keys phenotype_keys" + $phenotype_count]));
            else {
                $options = add_options($option_select2.format(["span4", "keys phenotype_keys" + $phenotype_count]), phenotype_keys);
                $("#phenotype" + $phenotype_count).append($options);
            }

            $("." + ["phenotype_keys" + $phenotype_count]).select2({
                placeholder: "--Select an attribute--",
//                    allowClear: true
            });

            $options_1 = add_options($option.format(["span2", "input-medium conditions", "", ""]), $phenotype_option_1);
            $("#phenotype" + $phenotype_count).append($options_1);

            $options_2 = add_options($option.format(["span3", "input-medium phenotype_values\" disabled ", "", ""]), $phenotype_option_2);
            $("#phenotype" + $phenotype_count).append($options_2);

            $("#phenotype" + $phenotype_count).append($add_remove_btn.format([""]));

            $phenotype_count += 1;
            break;
    }
}

var phenotype_keys = new Array();
var phenotype_values = new Array();

$(document).ready(function () {
    $network_key = $("#network_key").val();
    $.ajax({url: baseurl + 'admin/get_phenotype_attributes_for_network/' + $network_key,
        dataType: 'json',
        delay: 200,
        type: 'POST',
        success: function (json) {
            $.each(json, function (attribute, value) {
                $('select.phenotype_keys1').append($('<option>').text(attribute).attr('value', attribute));
                phenotype_keys.push(attribute);
                phenotype_values[attribute] = value;
            });
            $("#loader").addClass('hide');
        }
    });

    // DNA Type

    $(document).on('click', "#genomeContainer .condition-info, #accessionContainer .condition-info", function () {
        $("#modalInfo").show();
        $(".closeModal").click(function (e) {
            e.preventDefault();
            $("#modalInfo").hide();
        });
    });

    // Hgvs

    // Phenotype
    $(document).on('change', '.keys', function () {
        $current_phenotype_values = $(this).parent().parent().find('.phenotype_values').prop('disabled', '').parent();
        $new_phenotype_values = add_options($option.format(["span2", "input-medium phenotype_values", "", ""]), $phenotype_option_2.concat(phenotype_values[$(this).val()]));
        $current_phenotype_values.replaceWith($new_phenotype_values);
    });

    $(document).on('change', "select.phenotype_values", function () {
        if ($(this).val() === "[Input your own value]") {
            $(this).parent().append('<div class="input-append phenotype_custom_value">\n\
                                <input class="input-large phenotype_values" data-type="phenotype" type="text" placeholder="Enter a value">\n\
                                <span class="add-on"><i class="icon-share-alt"></i></span>\n\
                            </div>');
            $phenotype_values = $(this).remove();
        }
    });

    $(document).on('click', '.phenotype_custom_value .icon-share-alt', function () {
        $(this).parent().parent().parent().append($phenotype_values)
                .find('option:contains("--Select a value--")').attr('selected', 'selected')
                .parent().prev().remove();
    });

    // Rest
    $(document).on('click', ".btn-collapse", function () {

        if ($(this).attr('id') === "isPhenotype")
            $parent = $(this).parent().parent().parent();
        else
            $parent = $(this).parent().parent();

        if ($(this).attr("data-collapseStatus") === "false") {
            $(this).removeClass("btn-info").addClass("btn-success");
            $(this).find('i').removeClass("icon-chevron-left").addClass("icon-chevron-down");
            $($(this).parent().parent().next().collapse('show')).addClass('container_border');
            $(this).attr("data-collapseStatus", "true");
            $parent.prev().children('a').removeClass('disabled');

        } else {
            $collapse = true;

            switch ($(this).parent().parent().next().attr('id')) {
                case "genomeContainer":
                    $collapse = validate_Genome("collapseEvent");
                    break;
                case "accessionContainer":
                    $collapse = validate_Accession("collapseEvent");
                    break;
                case "dnaContainer":
                    $collapse = validate_DNA("collapseEvent");
                    break;
                case "proteinContainer":
                    $collapse = validate_Protein("collapseEvent");
                    break;
                case "geneSymbolContainer":
                    $collapse = validate_GeneSymbol("collapseEvent");
                    break;
                case "hgvsContainer":
                    $collapse = validate_HGVS("collapseEvent");
                    break;
                case "phenotypeContainer":
                    $collapse = validate_Phenotype("collapseEvent");
                    break;
            }
            if ($collapse) {
                $(this).removeClass("btn-success").addClass("btn-info");
                $(this).find('i').removeClass("icon-chevron-down").addClass("icon-chevron-left");
                $($(this).parent().parent().next().collapse('hide')).removeClass("container_border");
                $(this).attr("data-collapseStatus", "false");
                $parent.prev().children('a').addClass('disabled');
            }
        }
    });

    $(document).on('click', 'button.add', function () {

        parentId = $(this).closest("div[id$='Container']").attr('id');
        add_symbol(parentId);
        $("#" + parentId).find(".remove").removeClass('hidden');
        $add_btn = $(this).remove();
    });

    $(document).on('click', 'button.remove', function () {

        parent = $(this).closest('.row-fluid');

        if ($(parent).is(":first-child"))
            $(parent).next().remove();
        else {
            $(parent).prev().remove();
            if ($(parent).is(":last-child"))
                parent.prev().find(".add_remove_btn").prepend($add_btn);
        }

        if ($(parent).siblings().length === 1)
            $(parent).siblings('.row-fluid').find('.remove').addClass('hidden');

        $(parent).remove();

    });

    // AND-OR Toggle Function
    $(document).on('click', ".btn-toggle", function () {
        if ($("a", this).hasClass("disabled"))
            return;
        if ($(this).find('.btn-primary').length > 0) {
            if ($(this).parent().attr('id') === "phenotypeContainer") {
                $(this).parent().find('.logic_phenotype .btn').toggleClass('active');
                $(this).parent().parent().find('.logic_phenotype .btn').toggleClass('btn-primary');
            } else {
                $(this).find('.btn').toggleClass('active');
                $(this).find('.btn').toggleClass('btn-primary');
            }
        }
    });

    $(document).on('click', '.icon-remove-circle', function () {
        $(this).parent().siblings('input').val('').focus();
    });

    $(document).on('click', ".clear_all_textbox", function () {
        $('input').val('');
    });

//        $idCount = 1;
    $("#buildQuery").click(function () {
        $("#query_result").empty();
        $('#waiting').show(500);
        $idCount = 1;

        
        d = validate_DNA("buildQueryEvent");
        g = validate_GeneSymbol("buildQueryEvent");
        h = validate_HGVS("buildQueryEvent");
        p = validate_Phenotype("buildQueryEvent");

        if (!(d && g && h && p)) {
            console.log("Build Query: Not Validated");
            return;
        }

        $dna_gene = $('#logic_dna_gene .active').html();
        $gene_hgvs = $('#logic_gene_hgvs .active').html();
        $gen_phen = $('#logic_genotype_phenotype .active').html();
        $phen_phen = $('.logic_phenotype .active').html() ? $('.logic_phenotype .active').html() : "";

        $dna = "";
        $geneSymbol = "";
        $hgvs = "";

        $genotype = "";
        $phenotype = "";

        $arr_dna = getJSON_DNA();

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
                "coordinate": $arr_dna.coordinate,
                "sequence": $arr_dna.sequence,
                "geneSymbol": getJSON_GeneSymbol(),
                "hgvsName": getJSON_HGVS(),
                "phenotypeFeature": getJSON_Phenotype(),
            }
        };

        $.each($arr.query, function (key, value) {
            if (value.length === 0)
                delete $arr.query[key];
        });

        if (Object.keys($arr.query).length === 0) {
            alert("You have to select at least on type in order to proceed to a query!");
            return;
        }

        $lastword = $dna.split(" ").splice(-1);
        if ($lastword[0] === "AND" || $lastword[0] === "OR") {
            $dna = $dna.substring(0, $dna.lastIndexOf(" "));
        }

        $lastword = $geneSymbol.split(" ").splice(-1);
        if ($lastword[0] === "AND" || $lastword[0] === "OR")
            $geneSymbol = $geneSymbol.substring(0, $geneSymbol.lastIndexOf(" "));

        $lastword = $hgvs.split(" ").splice(-1);
        if ($lastword[0] === "AND" || $lastword[0] === "OR")
            $hgvs = $hgvs.substring(0, $hgvs.lastIndexOf(" "));

        $lastword = $phenotype.split(" ").splice(-1);
        if ($lastword[0] === "AND" || $lastword[0] === "OR")
            $phenotype = $phenotype.substring(0, $phenotype.lastIndexOf(" "));

        if ($dna.trim() !== "") {
            $dna = $dna.trim().indexOf("OR") < 0 ? " " + $dna.trim() + " " : " (" + $dna.trim() + ") ";
            if ($geneSymbol.trim() === "" && $hgvs.trim() === "" && $dna.trim().indexOf('AND') > -1) {
                console.log($dna.trim());
                $dna = $dna.trim().substring(1, $dna.trim().length - 1);
                console.log($dna.trim());
            }

            $genotype += $dna;
        }

        if ($geneSymbol.trim() !== "") {
            $geneSymbol = $geneSymbol.trim().indexOf("OR") < 0 ? " " + $geneSymbol.trim() + " " : " (" + $geneSymbol.trim() + ") ";
            if ($dna.trim() === "" && $hgvs.trim() === "" && $geneSymbol.trim().indexOf('OR') > -1) {
                $geneSymbol = $geneSymbol.trim().substring(1, $geneSymbol.trim().length - 1);
            }
            if ($genotype === "") {
                $genotype += $geneSymbol;
            } else {
                $genotype += $dna_gene + $geneSymbol;
            }
        }

        if ($hgvs.trim() !== "") {
            $hgvs = $hgvs.trim().indexOf("OR") < 0 ? " " + $hgvs.trim() + " " : " (" + $hgvs.trim() + ") ";
            if ($geneSymbol.trim() === "" && $dna.trim() === "" && $hgvs.trim().indexOf('OR') > -1) {
                $hgvs = $hgvs.trim().substring(1, $hgvs.trim().length - 1);
            }
            if ($genotype === "")
                $genotype += $hgvs;
            else
                $genotype += $gene_hgvs + $hgvs;
        }

        if ($genotype.trim() !== "") {
            $genotype = ($genotype.trim().indexOf("AND") < 0 && $genotype.trim().indexOf("OR") < 0) ? " " + $genotype.trim() + " " : " (" + $genotype.trim() + ") ";
            $phenotype = ($phenotype.trim().indexOf("AND") < 0 && $phenotype.trim().indexOf("OR") < 0) ? " " + $phenotype.trim() + " " : " (" + $phenotype.trim() + ") ";

            if ($phenotype.trim() === "")
                $query = $genotype;
            else
                $query = "(" + $genotype + $gen_phen + $phenotype + ")";
        } else {
            $query = "(" + $phenotype.trim() + ")";
        }
        $query = $query.trim();
        $.extend($arr, {"queryStatement": $query, "network_to_search": $network_key});
        console.log(JSON.stringify($arr, null, '\t'));
//			alert("queryString -> " + JSON.stringify($arr));
        $.ajax({url: baseurl + 'discover/query/' + $network_key,
            dataType: 'html',
            delay: 200,
            type: 'POST',
            data: {'jsonAPI': $arr},
            success: function (data) {
//                        alert('test -> ' + data);
                $('#waiting').hide(500);
                $("#query_result").html(data);
            }
        });

    });

    function getJSON_DNA() {
        $parentId = $("#dnaContainer");
        $parentType = $parentId.attr('data-type');

        $arr = {
            coordinate: [],
            sequence: []
        }

        $parentId.children('.type_sample').each(function () {
            if (($(this).find('select.condition_ref').val().length > 0) && ($(this).find('.sequence').val().trim().length > 0)) {

                $reference = "";
                if ($(this).find('select.condition_ref').val() === "[Input your own accession & version]") {
                    $reference = $(this).find(".acc_acc").children('input').val();
//                        $acc = $(this).find(".acc_acc").children('input').val();
//                        $version = $(this).find(".acc_ver").children('input').val();
//                        $reference = $acc + "." + $version;
                } else if ($(this).find('select.condition_ref').val().toString() === "[Input your own chromosome & build]") {
                    $chr = $(this).find(".chr_chr").children('input').val();
                    $build = $(this).find(".chr_build").val();
                    $reference = $chr + "." + $build;
                } else {
                    if ($(this).find('select.condition_ref').val().substring(0, "chr".length) == "chr")
                        $reference = $(this).find('select.condition_ref').val() + "." + "hg38";
                    else
                        $reference = $(this).find('select.condition_ref').val();
                }

                $data_coordinate = {
                    "querySegmentID": $idCount,
                    "operator": $(this).find('.conditions').val().toString(),
                    "reference": {"id": $reference, "source": ""},
                    "start": $(this).find('.start').val().toString(),
                    "stop": $(this).find('.stop').val().toString(),
                };

                $dna += " (" + $idCount + " AND ";
                $idCount++;

                $data_sequence = {
                    "querySegmentID": $idCount,
                    "operator": $(this).find('.conditions').val().toString(),
                    "sequence": $(this).find('.sequence').val().toString()
                };

                $dna += "" + $idCount + ") ";
                $idCount++;

                $arr.coordinate.push($data_coordinate);
                $arr.sequence.push($data_sequence);

            } else if ($(this).find('select.condition_ref').val().length > 0) {
                $reference = "";

                if ($(this).find('select.condition_ref').val() === "[Input your own accession & version]") {
                    $reference = $(this).find(".acc_acc").children('input').val();
//                        $acc = $(this).find(".acc_acc").children('input').val();
//                        $version = $(this).find(".acc_ver").children('input').val();
//                        $reference = $acc + "." + $version;
                } else if ($(this).find('select.condition_ref').val().toString() === "[Input your own chromosome & build]") {
                    $chr = $(this).find(".chr_chr").children('input').val();
                    $build = $(this).find(".chr_build").val();
                    $reference = $chr + "." + $build;
                } else {
                    if ($(this).find('select.condition_ref').val().substring(0, "chr".length) == "chr")
                        $reference = $(this).find('select.condition_ref').val() + "." + "hg38";
                    else
                        $reference = $(this).find('select.condition_ref').val();
                }

                $data_coordinate = {
                    "querySegmentID": $idCount,
                    "operator": $(this).find('.conditions').val().toString(),
                    "reference": {"id": $reference, "source": ""},
                    "start": $(this).find('.start').val().toString(),
                    "stop": $(this).find('.stop').val().toString(),
                };

                $dna += " " + $idCount + " ";
                $idCount++;

                $arr.coordinate.push($data_coordinate);

            } else if ($(this).find('.sequence').val().trim().length > 0) {

                $data_sequence = {
                    "querySegmentID": $idCount,
                    "operator": $(this).find('.conditions').val().toString(),
                    "sequence": $(this).find('.sequence').val().toString()
                };

                $dna += " " + $idCount + " ";
                $idCount++;

                $arr.sequence.push($data_sequence);
            }

            $dna += "OR";
        });

        return $arr;
    }

    function getJSON_Phenotype() {
        $parentId = $("#phenotypeContainer");
        $parentType = $parentId.attr('data-type');

        $arr = [];
        $parentId.children('.type_sample').each(function () {
            if ($(this).find('select.keys').val().trim().length > 0) {

                $data = {
                    "querySegmentID": $idCount,
                    "operator": $(this).find('.conditions').val().toString(),
                    "phenotypeConcept": {
                        "cursivePhenotypeConcept": {"term": $(this).find('select.keys').val().toString(), "source": ""}
                    },
                    "phenotypeFeature": {"value": $(this).find('.phenotype_values').val().toString(), "units": "", "source": ""}
                };

                $arr.push($data);
                $phenotype += " " + $idCount + " " + $phen_phen;
                $idCount++;
            }
        });
        return $arr;
    }

    function getJSON_GeneSymbol() {
        $parentId = $("#geneSymbolContainer");
        $parentType = $parentId.attr('data-type');

        $arr = [];
        $parentId.children('.type_sample').each(function () {
            if ($(this).find('.textValidate').val().trim().length > 0) {

                $data = {
                    "querySegmentID": $idCount,
                    "operator": $(this).find('.conditions').val().toString(),
                    "geneSymbol": {"symbol": $(this).find('.textValidate').val().toString(), "source": ""}
                };

                $arr.push($data);

                $geneSymbol += " " + $idCount + " OR";
                $idCount++;
            }
        });
        return $arr;
    }

    function getJSON_HGVS() {
        $parentId = $("#hgvsContainer");
        $parentType = $parentId.attr('data-type');

        $arr = [];
        $parentId.children('.type_sample').each(function () {
            if ($(this).find('.textValidate').val().trim().length > 0) {

                $data = {
                    "querySegmentID": $idCount,
                    "operator": $(this).find('.conditions').val().toString(),
                    "reference": {"id": $(this).find('.hgvs_accession').val().toString(), "source": ""},
                    "hgvsName": $(this).find('.textValidate').val().toString()
                };

                $arr.push($data);

                $hgvs += " " + $idCount + " OR";
                $idCount++;
            }

        });
        return $arr;
    }

    function validate_Genome($for) {
        $parentId = $("#dnaContainer");
        $parentType = $parentId.attr('data-type');

        if ($for === "collapseEvent") {
            if ($parentId.children('.type_sample').length === 1) {
                if (($parentId.find('select.condition_ref').val().trim().length > 0) || ($parentId.find('input.sequence').val().trim().length > 0))
                {
                    $.growl.notice({message: "Non-empty sections cannot be collapsed."});
                    return false;
                }
            } else {
                $error = false;
                $parentId.children('.type_sample').each(function () {
                    if (($(this).find('select.condition_ref').val().trim().length > 0) || ($(this).find('input.sequence').val().trim().length > 0)) {
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
                if (($(this).find('select.condition_ref').val().length > 0)) {
                    $start = parseInt($(this).find('.start').val().trim(), 10);
                    $stop = parseInt($(this).find('.stop').val().trim(), 10);

                    start = $(this).find('.start').val();
                    stop = $(this).find('.stop').val();
                    seq = $(this).find('.sequence').val();
                    ref = $(this).find('.select2-chosen').html();

                    if (start !== "" && stop !== "" && seq !== "" && ref !== "--Select a reference--") {
                        if ($(this).find('.conditions').val() !== "EXACT") {
                            $.growl.error({message: "You have not set a DNA type validation to \"EXACT\" match. Cannot proceed to query."});
                            $error = true;
                            return false;
                        }
                    }

                    if (isNaN($start)) {
                        $.growl.error({message: "DNA start value(s) were empty."});
                        $error = true;
                        return false;
                    } else if (!isNumber($start)) {
                        console.log($start);
                        $.growl.error({message: "DNA start value(s) were not numeric."});
                        $error = true;
                        return false;
                    }

                    if (isNaN($stop)) {
                        $.growl.error({message: "DNA stop value(s) were empty."});
                        $error = true;
                        return false;
                    } else if (!isNumber($stop)) {
                        $.growl.error({message: "DNA stop value(s) were not numeric."});
                        $error = true;
                        return false;
                    }

                    if ($start > $stop) {
                        $.growl.error({message: "DNA stop value(s) is greater than the start value(s)."});
                        $error = true;
                        return false;
                    }

                    if ($(this).find('select.condition_ref').val() === "[Input your own accession & version]") {

                        if ($(this).find('.acc_acc input').val().trim().length === 0) {
                            $.growl.error({message: "You have not entered an accession value(s)"});
                            $error = true;
                            return false;
                        }
//                            if($(this).find('.acc_ver input').val().trim().length === 0) {
//                                $.growl.error({ message: "You have not entered an accession version value(s)" });
//                                $error = true;
//                                return false;
//                            }
                    } else if ($(this).find('select.condition_ref').val() === "[Input your own chromosome & build]") {

                        if ($(this).find('.chr_chr input').val().trim().length === 0) {
                            $.growl.error({message: "You have not entered a chromosome value(s)"});
                            $error = true;
                            return false;
                        }
                        if ($(this).find('.chr_build').val().trim().length === 0 || $(this).find('.chr_build').val() === "--Select a build--") {
                            $.growl.error({message: "You have not entered an chromosome build value(s)"});
                            $error = true;
                            return false;
                        }
                    }
                } else if ($(this).find('.sequence').val() != "") {
                    $.growl.error({message: "You have not enter a location reference value(s)"});
                    $error = true;
                    return false;
                }
            });
            return !$error;
        }
    }
    
    function validate_Accession($for) {
        $parentId = $("#dnaContainer");
        $parentType = $parentId.attr('data-type');

        if ($for === "collapseEvent") {
            if ($parentId.children('.type_sample').length === 1) {
                if (($parentId.find('select.condition_ref').val().trim().length > 0) || ($parentId.find('input.sequence').val().trim().length > 0))
                {
                    $.growl.notice({message: "Non-empty sections cannot be collapsed."});
                    return false;
                }
            } else {
                $error = false;
                $parentId.children('.type_sample').each(function () {
                    if (($(this).find('select.condition_ref').val().trim().length > 0) || ($(this).find('input.sequence').val().trim().length > 0)) {
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
                if (($(this).find('select.condition_ref').val().length > 0)) {
                    $start = parseInt($(this).find('.start').val().trim(), 10);
                    $stop = parseInt($(this).find('.stop').val().trim(), 10);

                    start = $(this).find('.start').val();
                    stop = $(this).find('.stop').val();
                    seq = $(this).find('.sequence').val();
                    ref = $(this).find('.select2-chosen').html();

                    if (start !== "" && stop !== "" && seq !== "" && ref !== "--Select a reference--") {
                        if ($(this).find('.conditions').val() !== "EXACT") {
                            $.growl.error({message: "You have not set a DNA type validation to \"EXACT\" match. Cannot proceed to query."});
                            $error = true;
                            return false;
                        }
                    }

                    if (isNaN($start)) {
                        $.growl.error({message: "DNA start value(s) were empty."});
                        $error = true;
                        return false;
                    } else if (!isNumber($start)) {
                        console.log($start);
                        $.growl.error({message: "DNA start value(s) were not numeric."});
                        $error = true;
                        return false;
                    }

                    if (isNaN($stop)) {
                        $.growl.error({message: "DNA stop value(s) were empty."});
                        $error = true;
                        return false;
                    } else if (!isNumber($stop)) {
                        $.growl.error({message: "DNA stop value(s) were not numeric."});
                        $error = true;
                        return false;
                    }

                    if ($start > $stop) {
                        $.growl.error({message: "DNA stop value(s) is greater than the start value(s)."});
                        $error = true;
                        return false;
                    }

                    if ($(this).find('select.condition_ref').val() === "[Input your own accession & version]") {

                        if ($(this).find('.acc_acc input').val().trim().length === 0) {
                            $.growl.error({message: "You have not entered an accession value(s)"});
                            $error = true;
                            return false;
                        }
//                            if($(this).find('.acc_ver input').val().trim().length === 0) {
//                                $.growl.error({ message: "You have not entered an accession version value(s)" });
//                                $error = true;
//                                return false;
//                            }
                    } else if ($(this).find('select.condition_ref').val() === "[Input your own chromosome & build]") {

                        if ($(this).find('.chr_chr input').val().trim().length === 0) {
                            $.growl.error({message: "You have not entered a chromosome value(s)"});
                            $error = true;
                            return false;
                        }
                        if ($(this).find('.chr_build').val().trim().length === 0 || $(this).find('.chr_build').val() === "--Select a build--") {
                            $.growl.error({message: "You have not entered an chromosome build value(s)"});
                            $error = true;
                            return false;
                        }
                    }
                } else if ($(this).find('.sequence').val() != "") {
                    $.growl.error({message: "You have not enter a location reference value(s)"});
                    $error = true;
                    return false;
                }
            });
            return !$error;
        }
    }

    function validate_DNA($for) {
        $parentId = $("#dnaContainer");
        $parentType = $parentId.attr('data-type');

        if ($for === "collapseEvent") {
            if ($parentId.children('.type_sample').length === 1) {
                if (($parentId.find('select.condition_ref').val().trim().length > 0) || ($parentId.find('input.sequence').val().trim().length > 0))
                {
                    $.growl.notice({message: "Non-empty sections cannot be collapsed."});
                    return false;
                }
            } else {
                $error = false;
                $parentId.children('.type_sample').each(function () {
                    if (($(this).find('select.condition_ref').val().trim().length > 0) || ($(this).find('input.sequence').val().trim().length > 0)) {
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
                if (($(this).find('select.condition_ref').val().length > 0)) {
                    $start = parseInt($(this).find('.start').val().trim(), 10);
                    $stop = parseInt($(this).find('.stop').val().trim(), 10);

                    start = $(this).find('.start').val();
                    stop = $(this).find('.stop').val();
                    seq = $(this).find('.sequence').val();
                    ref = $(this).find('.select2-chosen').html();

                    if (start !== "" && stop !== "" && seq !== "" && ref !== "--Select a reference--") {
                        if ($(this).find('.conditions').val() !== "EXACT") {
                            $.growl.error({message: "You have not set a DNA type validation to \"EXACT\" match. Cannot proceed to query."});
                            $error = true;
                            return false;
                        }
                    }

                    if (isNaN($start)) {
                        $.growl.error({message: "DNA start value(s) were empty."});
                        $error = true;
                        return false;
                    } else if (!isNumber($start)) {
                        console.log($start);
                        $.growl.error({message: "DNA start value(s) were not numeric."});
                        $error = true;
                        return false;
                    }

                    if (isNaN($stop)) {
                        $.growl.error({message: "DNA stop value(s) were empty."});
                        $error = true;
                        return false;
                    } else if (!isNumber($stop)) {
                        $.growl.error({message: "DNA stop value(s) were not numeric."});
                        $error = true;
                        return false;
                    }

                    if ($start > $stop) {
                        $.growl.error({message: "DNA stop value(s) is greater than the start value(s)."});
                        $error = true;
                        return false;
                    }

                    if ($(this).find('select.condition_ref').val() === "[Input your own accession & version]") {

                        if ($(this).find('.acc_acc input').val().trim().length === 0) {
                            $.growl.error({message: "You have not entered an accession value(s)"});
                            $error = true;
                            return false;
                        }
//                            if($(this).find('.acc_ver input').val().trim().length === 0) {
//                                $.growl.error({ message: "You have not entered an accession version value(s)" });
//                                $error = true;
//                                return false;
//                            }
                    } else if ($(this).find('select.condition_ref').val() === "[Input your own chromosome & build]") {

                        if ($(this).find('.chr_chr input').val().trim().length === 0) {
                            $.growl.error({message: "You have not entered a chromosome value(s)"});
                            $error = true;
                            return false;
                        }
                        if ($(this).find('.chr_build').val().trim().length === 0 || $(this).find('.chr_build').val() === "--Select a build--") {
                            $.growl.error({message: "You have not entered an chromosome build value(s)"});
                            $error = true;
                            return false;
                        }
                    }
                } else if ($(this).find('.sequence').val() != "") {
                    $.growl.error({message: "You have not enter a location reference value(s)"});
                    $error = true;
                    return false;
                }
            });
            return !$error;
        }
    }
    
    function validate_Protein($for) {
        $parentId = $("#dnaContainer");
        $parentType = $parentId.attr('data-type');

        if ($for === "collapseEvent") {
            if ($parentId.children('.type_sample').length === 1) {
                if (($parentId.find('select.condition_ref').val().trim().length > 0) || ($parentId.find('input.sequence').val().trim().length > 0))
                {
                    $.growl.notice({message: "Non-empty sections cannot be collapsed."});
                    return false;
                }
            } else {
                $error = false;
                $parentId.children('.type_sample').each(function () {
                    if (($(this).find('select.condition_ref').val().trim().length > 0) || ($(this).find('input.sequence').val().trim().length > 0)) {
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
                if (($(this).find('select.condition_ref').val().length > 0)) {
                    $start = parseInt($(this).find('.start').val().trim(), 10);
                    $stop = parseInt($(this).find('.stop').val().trim(), 10);

                    start = $(this).find('.start').val();
                    stop = $(this).find('.stop').val();
                    seq = $(this).find('.sequence').val();
                    ref = $(this).find('.select2-chosen').html();

                    if (start !== "" && stop !== "" && seq !== "" && ref !== "--Select a reference--") {
                        if ($(this).find('.conditions').val() !== "EXACT") {
                            $.growl.error({message: "You have not set a DNA type validation to \"EXACT\" match. Cannot proceed to query."});
                            $error = true;
                            return false;
                        }
                    }

                    if (isNaN($start)) {
                        $.growl.error({message: "DNA start value(s) were empty."});
                        $error = true;
                        return false;
                    } else if (!isNumber($start)) {
                        console.log($start);
                        $.growl.error({message: "DNA start value(s) were not numeric."});
                        $error = true;
                        return false;
                    }

                    if (isNaN($stop)) {
                        $.growl.error({message: "DNA stop value(s) were empty."});
                        $error = true;
                        return false;
                    } else if (!isNumber($stop)) {
                        $.growl.error({message: "DNA stop value(s) were not numeric."});
                        $error = true;
                        return false;
                    }

                    if ($start > $stop) {
                        $.growl.error({message: "DNA stop value(s) is greater than the start value(s)."});
                        $error = true;
                        return false;
                    }

                    if ($(this).find('select.condition_ref').val() === "[Input your own accession & version]") {

                        if ($(this).find('.acc_acc input').val().trim().length === 0) {
                            $.growl.error({message: "You have not entered an accession value(s)"});
                            $error = true;
                            return false;
                        }
//                            if($(this).find('.acc_ver input').val().trim().length === 0) {
//                                $.growl.error({ message: "You have not entered an accession version value(s)" });
//                                $error = true;
//                                return false;
//                            }
                    } else if ($(this).find('select.condition_ref').val() === "[Input your own chromosome & build]") {

                        if ($(this).find('.chr_chr input').val().trim().length === 0) {
                            $.growl.error({message: "You have not entered a chromosome value(s)"});
                            $error = true;
                            return false;
                        }
                        if ($(this).find('.chr_build').val().trim().length === 0 || $(this).find('.chr_build').val() === "--Select a build--") {
                            $.growl.error({message: "You have not entered an chromosome build value(s)"});
                            $error = true;
                            return false;
                        }
                    }
                } else if ($(this).find('.sequence').val() != "") {
                    $.growl.error({message: "You have not enter a location reference value(s)"});
                    $error = true;
                    return false;
                }
            });
            return !$error;
        }
    }

    function validate_Phenotype($for) {
        $parentId = $("#phenotypeContainer");
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
                    field_value = $(this).find('.phenotype_values').val();
                    if (field_value === "--Select a value--") {
                        $.growl.error({message: "You have not entered a phenotype value(s)"});
                    } else if (!phenotype_validation(condition_value, field_value)) {
                        $error = true;
                    }
                }
            });
            return !$error;
        }
    }

    function validate_GeneSymbol($for) {
        $parentId = $("#geneSymbolContainer");
        $parentType = $parentId.attr('data-type');

        if ($for === "collapseEvent") {
            if ($parentId.children('.type_sample').length === 1) {
                if ($parentId.find('.textValidate').val().trim().length > 0)
                {
                    $.growl.notice({message: "Non-empty sections cannot be collapsed."});
                    return false;
                }
            } else {
                $error = false;
                $parentId.children('.type_sample').each(function () {
                    if ($(this).find('.textValidate').val().trim().length > 0) {
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
            $parentId.children('.type_sample').each(function () {
                if ($(this).find('.textValidate').val().trim().length > 0) {
                    $.ajax({url: authurl + '/admin/validate_gene/',
                        dataType: 'json',
                        data: {'term': $(this).find('.textValidate').val()},
                        delay: 200,
                        type: 'POST',
                        success: function (data) {
                            if (data.status !== "Validated")
                                $.growl.warning({message: "You have not entered a valid gene symbol value(s). Query result may not be exact"});
                        }
                    });
                }
            });
            return true;
        }
    }

    function validate_HGVS($for) {
        $parentId = $("#hgvsContainer");
        $parentType = $parentId.attr('data-type');

        if ($for === "collapseEvent") {
            if ($parentId.children('.type_sample').length === 1) {
                if ($parentId.find('.textValidate').val().trim().length > 0)
                {
                    $.growl.notice({message: "Non-empty sections cannot be collapsed."});
                    return false;
                }
            } else {
                $error = false;
                $parentId.children('.type_sample').each(function () {
                    if ($(this).find('.textValidate').val().trim().length > 0) {
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
            $parentId.children('.type_sample').each(function () {
                if ($(this).find('.textValidate').val().trim().length > 0) {
                    $.ajax({url: authurl + '/admin/validate_hgvs/',
                        dataType: 'json',
                        data: {'term': $(this).find('.textValidate').val()},
                        delay: 200,
                        type: 'POST',
                        success: function (data) {
                            if (data.status !== "Validated")
                                $.growl.warning({message: "You have not entered a valid HGVS description value(s). Query result may not be exact"});
                        }
                    });
                }
            });
            return true;
        }
    }

    function phenotype_validation(condition_value, field_value) {
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