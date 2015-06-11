<script>
    function submitForm() {


        ol = document.getElementById('ont_list');
        hi = document.getElementById('hidden_inputs');
        for (var i = 0; i < ol.options.length; i++) {
            listvalue = ol.options[i].value;
            listlabel = ol.options[i].innerHTML;
            fulldescription = listvalue + "@@" + listlabel;
            e = document.createElement("input");
            e.setAttribute("value", listvalue);
            e.setAttribute("type", "hidden");
            e.setAttribute("name", "ont_item[]");
            hi.appendChild(e);
            e = document.createElement("input");
            e.setAttribute("value", fulldescription);
            e.setAttribute("type", "hidden");
            e.setAttribute("name", "ont_full[]");
            hi.appendChild(e);
        }
        document.addvariant.submit();
    }
    
    
jQuery(document).ready(function() {
 jQuery('#uservalue').prop('disabled', true);
});

function val_listener() {
    d=jQuery('#value').val();
    if (d == "user defined"){    
    jQuery('#uservalue').prop('disabled', false);
    }
    else{
    jQuery('#uservalue').prop('disabled', true);
    jQuery('#uservalue').val('');
    }
};
</script>




<div class="container">
    <div class="row">  
        <div class="span6">  
            <ul class="breadcrumb">
                <?php if ($this->session->userdata('admin_or_curate') == "curate"): ?>
                    <li> 
                        <a href="<?php echo base_url() . "curate"; ?>">Curators Dashboard</a> <span class="divider">></span>  
                    </li>
                    <li>
                        <a href="<?php echo base_url() . "curate/variants"; ?>">Variants</a> <span class="divider">></span>
                    </li>
                <?php else: ?>
                    <li> 
                        <a href="<?php echo base_url() . "admin"; ?>">Dashboard Home</a> <span class="divider">></span>  
                    </li>
                    <li>
                        <a href="<?php echo base_url() . "admin/variants"; ?>">Variants</a> <span class="divider">></span>
                    </li>
                <?php endif; ?>
                <li class="active">Add Variant</li>
            </ul>  
        </div>  
    </div>
    <div class="row-fluid pagination-centered">
        <div class="span12">
            <h2>Add Variant to <?php echo $source; ?></h2>
            <p>Please enter the variant and associated information below.</p>
            <p>Required fields are marked (*).</p>
            <b><?php echo validation_errors(); ?></b>
            <?php
            $attributes = array('id' => 'addvariant', 'name' => 'addvariant');
            $hidden = array('source' => $source);
            echo form_open("variants/add/$source", $attributes, $hidden);
            ?>
            <div class="well pagination-centered">
                <div class="row">
                    <h3>Variant Information</h3>
                    <hr>
                </div>
                <div class="row">
                    (*) Gene: <br />
                    <?php echo form_input($gene); ?>
                </div>
                <br />
                <div class="row">
                    <div class="span4 offset2">(*) Reference sequence (e.g. NM_000088.3)</div>
                    <div class="span3">(*) HGVS nomenclature (e.g. c.79C>T):</div>
                </div>
                <div class="row var" id="var" name="var">
                    <div class="span4 offset2"><?php echo form_input($ref); ?></div>
                    <div class="span3"><?php echo form_input($hgvs); ?></div>
                    <div class="span1" id="validateresult"></div>
                    <div class="span2"><?php echo nbs(4); ?> &nbsp;&nbsp;&nbsp;&nbsp;<button onclick="mutalyzerValidate();
        return false;" class="btn btn-small" rel="popover" data-content="Click to validate the syntax of this variant using the Mutalyzer webservice." data-original-title="Validate Variant"><i class="icon-check"></i> Validate</button></div>

                </div>
                <div name="newvar" id="newvar"></div>
                <div class="row">
                    <!--					<div id="validateresult"></div>-->
                    <!--					<div class="span3 offset9"><button class="btn btn-small"><i class="icon-plus"></i> Add Additional</button></div>-->
                </div>
                <br />
                <div class="row">
                    <table align="center">
                        <tr><label for="pathogenicity_type">Pathogenicity classification type and value:</label></tr>
                        <tr>

                            <td><select name="pathogenicity_type" id="pathogenicity_type">
                                    <option value="none" selected="selected">-- Select --</option>
                                    <option value="A">LOVD</option>
                                    <option value="B">DMuDB</option>
                                    <option value="C">CMGS</option>
                                </select></td>
                        </tr>
                        <tr id="tr_A" class="pathogenicity_value">
                            <td>
                                <select name="lovd_pathogenicity_value" id="id_A">
                                    <option value="No_known_pathogenicity">No known pathogenicity</option>
                                    <option value="Probably_no_pathogenicity">Probably no pathogenicity</option>
                                    <option value="Unknown" selected="selected">Unknown</option>
                                    <option value="Probably_pathogenic">Probably pathogenic</option>
                                    <option value="Pathogenic">Pathogenic</option>
                                </select>
                            </td>
                        </tr>
                        <tr id="tr_B" class="pathogenicity_value">
                            <td>
                                <select name="dmudb_pathogenicity_value" id="id_B">
                                    <option value="Non-Pathogenic" >Non-Pathogenic</option>
                                    <option value="Probably_Not_Pathogenic">Probably Not Pathogenic</option>
                                    <option value="Not_Known" selected="selected">Not Known</option>
                                    <option value="Probably_Pathogenic">Probably Pathogenic</option>
                                    <option value="Pathogenic">Pathogenic</option>
                                    <option value="Unclassified">Unclassified</option>
                                </select>
                            </td>
                        </tr>
                        <tr id="tr_C" class="pathogenicity_value">
                            <td>
                                <select name="cmgs_pathogenicity_value" id="id_C">
                                    <option value="Class_1" >Class 1 ??? Certainly not pathogenic</option>
                                    <option value="Class_2">Class 2 ??? Unlikely to be pathogenic but cannot be formally proven</option>
                                    <option value="Class_3">Class 3 ??? Likely to be pathogenic but cannot be formally proven</option>
                                    <option value="Class_4">Class 4 - Certainly pathogenic</option>
                                    <option value="Class_5" selected="selected">Class 5 - Unknown</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <br />
                <div class="row">
                    Variant ID (local ID for variant): <br />
<?php echo form_input($variant_id); ?>
                </div>
                <br />
                <div class="row">
                    (*) Sharing Policy: <br />
                    <?php
                    $options = array(
                        'openAccess' => 'openAccess',
                        'restrictedAccess' => 'restrictedAccess',
                        'linkedAccess' => 'linkedAccess'
                    );
                    echo form_dropdown('sharing_policy', $options, 'openAccess');
                    ?>
                </div>

                <div class="row">
<!--					<div class="span2 offset8"><button class="btn btn-small" id="additionalvariant" name="additionalvariant"><i class="icon-plus"></i> Add Additional</button></div>-->
                    <div class="span2 offset10"><button class="btn btn-small" onclick="clearVariant();
        return false;" id="clearvariant" name="clearvariant" ><i class="icon-remove-sign"></i> Clear</button></div>
                </div>
            </div>

            <div class="well pagination-centered">
                <div class="row">
                    <h3>Phenotype Information</h3>
                    <hr>
                </div>



 <script>

 var selectList = function(event, ui) {
       if (ui.item == null) {
               disableOntology();
               return null;
        }
        jQuery(this).val(ui.item.label);
        enableOntology(ui.item.value);
        return false;
 }



window.sourceData = [
<?php
$bioontologies = array();
$counter = 0;
foreach ($ontologies as $identifier => $label) {
?>
        { "identifier" : "<?php echo $identifier ?>", "label": "<?php echo $label ?>" }<?php if ($counter < sizeof($ontologies) - 1) {
        echo ",";
    } ?>
    <?php
    $counter++;

//    list($versionid, $virtualid) = explode("|", $value);
//    list($name, $abbreviation) = explode("|", $label);
    $formattedlabel = $label . " (" . $identifier . ")";
    if (in_array($identifier, $usedOntologies)) {
        $bioontologies[$identifier] = $formattedlabel;
    }
}
?>
    ];
            jQuery.widget("custom.catcomplete", $.ui.autocomplete, {
        _renderMenu: function(ul, items) {
            var that = this,
                    currentCategory = "";
            $.each(items, function(index, item) {
                if (item.category != currentCategory) {
                    ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
                    currentCategory = item.category;
                }
                that._renderItemData(ul, item);
            });
        }
    });

    jQuery(function() {
        $("#phenosearch").catcomplete({
           	source: function(request, response) {
				$.ajax({url: baseurl + 'discover/pheno_lookup',
				data: {term: $("#phenosearch").val()},
				dataType: 'json',
				delay: 200,
				type: 'POST',
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
                select: function(event, ui) {
                $("#term-identifier").val(ui.item.identifier);
                $("#term-category").val(ui.item.category);
                $("#ontology-version").val(ui.item.version);
                $("#term-qualifier").val(ui.item.qualifier);
            }

        });
    });

</script>





                <div class="row">
                    <div class="span6 offset3">LocalList term / Used ontology term:</div>

                <br/>
                                       <div class="span6 offset3">
                        <input id="phenosearch" style="width:30em"/>
                        <span class="add-on"><a href="#" rel="popover" data-content="Type two characters of a term from the LocalList or a previously used ontology term to initiate the autocomplete. Once a term has been selected from the lookup, or a new term entered, click the <i>Add to description</i> button" data-original-title="Lookup LocalList and used ontology terms, or enter a new non-ontology term"><i class="icon-question-sign"></i></a></span>


                        <input type="hidden" id="term-identifier" />
                        <input type="hidden" id="term-category" />
                        <input type="hidden" id="term-qualifier" />
                        <input type="hidden" id="ontology-version" />

                    </div>
                </div>
  <br/>
<div class="row">
<div class="span6 offset3">
Value:
</div>
</div>
  <div class="row">
<div class="span6 offset3">
 
<select class="selectpicker" name="value" id="value" onchange="val_listener()" style="width:13em">
    <optgroup label="This phenotype is">
        <option>present</option>
        <option>absent</option>
        <option>unknown</option>
    </optgroup>
    <optgroup label="Something else">
        <option>null</option>
        <option>user defined</option>
    </optgroup>
</select>

<input name="uservalue" id="uservalue" type="text" placeholder="user value" style="width:13em" /> 



<span class="add-on">
<a href="#" rel="popover" data-content="Select a quality value or add your own user defined value. The default value is 'present'"  data-original-title="Add a value"><i class="icon-question-sign"></i></a>
</div>



<div class="span3">
                                            <button type="button" class="btn btn-small" onclick="addItemToList()"><i class="icon-plus"></i> Add to description</button>
                                 </div>
</div>
                <div name="newpheno" id="newpheno"></div>                          


                <div class="row">
                    <hr/>
                    <h4>BioPortal term lookup</h4>
                    <br/>
                </div>

                <?php
                if (sizeof($bioontologies) > 0) {
                    foreach ($bioontologies as $key => $val) {
                        echo '<script>
function addItemToList_' . $key . '() {
		oi = document.getElementById(\'ont_item_' . $key . '\');
		ol = document.getElementById(\'ont_list\');
		if (oi.value.length>0) {
			bp_preferred_name = document.getElementById(\'oi_' . $key . '_bioportal_preferred_name\');
			bp_concept_id = document.getElementById(\'oi_' . $key . '_bioportal_concept_id\');
			bp_ontology_id = document.getElementById(\'oi_' . $key . '_bioportal_ontology_id\');
			bp_full_id = document.getElementById(\'oi_' . $key . '_bioportal_full_id\');
			fixed_value = document.getElementById(\'value_' . $key .'\');
                        user_value = document.getElementById(\'uservalue_' . $key .'\'); 
                        myvalue=fixed_value.value;

                        if (myvalue == "user defined"){
                            if (user_value.value.length>0){
                                myvalue = user_value.value;
                           }
                           else {
                                myvalue="null";
                            }
                        }

			if (bp_ontology_id && bp_concept_id) {
				if (bp_concept_id.value.length==0) {
					alert("Sorry this concept was not found in BioPortal!");
					oi.value="";
					oi.focus();
					return
				}
				newlabel = bp_preferred_name.value + " (id:" + bp_concept_id.value + ", ontology:" + bp_ontology_id.value + ") " + myvalue;
				newvalue = bp_preferred_name.value + "|" + bp_concept_id.value + "|" + bp_ontology_id.value + "|" + myvalue;
				for(var i = 0, opts = ol.options; i < opts.length; ++i){
                                    if( opts[i].value === newvalue ){
					alert("Sorry, this concept has already been added to the list");
					oi.value="";
					oi.focus();
					return;
				}
                                }
				
				opt = new Option(newlabel, newvalue);
				ol.add(opt);
				//clear fields
				oi.value="";
				bp_preferred_name.value="";
				bp_concept_id.value="";
				bp_ontology_id.value="";
				bp_full_id.value="";
                                fixed_value.value="present";
                                user_value.value="";
                                jQuery(\'#uservalue_'.$key.'\').prop(\'disabled\', true);
			}
			else {
				alert("Sorry, your term was not added due to an unknown problem");
			}
		}
		else {
			alert("Please select an ' . $val . ' term");
		
		
		}
                return false;
	}
 
jQuery(document).ready(function() {
 jQuery(\'#uservalue_'.$key.'\').prop(\'disabled\', true);
});

function listener_' . $key . '() {
    d=jQuery(\'#value_'.$key.'\' ).val();
    if (d == "user defined"){    
    jQuery(\'#uservalue_'.$key.'\').prop(\'disabled\', false);
    }
    else{
    jQuery(\'#uservalue_'.$key.'\').prop(\'disabled\', true);
    jQuery(\'#uservalue_'.$key.'\').val(\'\');
    }
};

</script>';
                        echo"\n\n";


                        echo "<div class=\"row\"><div class=\"span6 offset3\">$val:</div></div>
    
<div class=\"row\">
    <div class=\"span6 offset3\">
    <input name=\"oi_$key\" id=\"ont_item_$key\" type=\"text\" class=\"bp_form_complete-$key-name\" style=\"width:29em\"/> 
    <span class=\"add-on\"><a href=\"#\" rel=\"popover\" data-content=\"Type the first three characters of a $val term to initiate the autocomplete. Once a term has been selected from the lookup click the <i>Add to description</i> button\"  data-original-title=\"Lookup terms from the latest version of the ontology\"><i class=\"icon-question-sign\"></i></a></span> 
    <input type=\"hidden\" id=\"version_$key\" value=\"$key\"></div>
</div>

<div class=\"row\">
    <div class=\"span6 offset3\">
    Value:
    </div>
</div>

<div class=\"row\">
<div class=\"span6 offset3\">
 
<select class=\"selectpicker\" name=\"value_$key\" id=\"value_$key\" onchange=\"listener_$key()\" style=\"width:13em\">
    <optgroup label=\"This phenotype is\">
        <option>present</option>
        <option>absent</option>
        <option>unknown</option>
    </optgroup>
    <optgroup label=\"Something else\">
         <option>null</option>
        <option>user defined</option>
    </optgroup>
</select>

<input name=\"uservalue_$key\" id=\"uservalue_$key\" type=\"text\" placeholder=\"user value\" style=\"width:13em\" /> 



<span class=\"add-on\">
<a href=\"#\" rel=\"popover\" data-content=\"Select a quality value or add your own user defined value. The default value is 'present'\"  data-original-title=\"Add a value\"><i class=\"icon-question-sign\"></i></a>
</div>
<div class=\"span3\">   
 <button type=\"button\" class=\"btn btn-small\" onclick=\"addItemToList_$key()\"><i class=\"icon-plus\"></i> Add to description</button>
     </div></div><br/>";
                    }
                } else {
                    echo "<p class=\"text-warning\">BioPortal ontologies can be added from the Admin Dashboard</p>";
                }
                ?>




                <div class="row">
                    <hr/>
                    <h4>Phenotype description:</h4>
                </div>



                <div class="row">
<?php
if (!empty($ont_list)) {
    //error_log(print_r($ont_list,1));
    echo form_dropdown('ont_list', $ont_list, '', 'size="5" name="ont_list" id="ont_list"');
} else {
    echo form_dropdown('ont_list', array(), '', 'size="5" name="ont_list" id="ont_list"');
}
?>
                    <div id="hidden_inputs"></div>			
                </div>

                <div class="row">
                    <button type="button" class="btn btn-small" onclick="deleteSelectedItem()"><i class="icon-remove-sign"></i> Delete selected term</button>&nbsp;&nbsp;
                    <button type="button" class="btn btn-small" onclick="deleteAllItems()"><i class="icon-remove-sign"></i> Delete all terms</button>&nbsp;&nbsp; 
                </div>










            </div>
            <div class="well pagination-centered">
                <div class="row">
                    <h3>Patient Information</h3>
                    <hr>
                </div>
                <div class="row">
                    Patient ID (anonymized): <br />
<?php echo form_input($individual_id); ?>
                </div>
                <div class="row">
                    Gender: <br />
                    <?php
                    $options = array(
                        'not_specified' => 'Not Specified',
                        'male' => 'Male',
                        'female' => 'Female'
                    );
                    echo form_dropdown('gender', $options, 'not_specified');
                    ?>
                </div>
                <div class="row">
                    Ethnicity: <br />
                    <?php echo form_input($ethnicity); ?>
                </div>
                <div class="row">
                    <div class="span2 offset10"><button class="btn btn-small" onclick="clearPatient();
        return false;" id="clearpatient" name="clearpatient"><i class="icon-remove-sign"></i> Clear</button></div>
                </div>
            </div>
            <div class="well pagination-centered">
                <div class="row">
                    <h3>Additional Information</h3>
                    <hr>
                </div>
                <div class="row">
                    Genomic location (chromosome, start, end):<br />
<?php echo form_input($location_ref); ?>:<?php echo form_input($start); ?>-<?php echo form_input($end); ?>
                    <br />
                    Genome build:<br />
                    <?php
                    $js = 'id="build"';
                    $options = array('hg19' => 'hg19', 'hg18' => 'hg18');
                    echo form_dropdown('build', $options, 'hg19', $js);
                    ?>
                </div>
                <br />
                <div class="row">
                    Comment: <br />
<?php echo form_textarea($comment); ?>
                </div>
                <div class="row">
                    <div class="span2 offset10"><button class="btn btn-small" onclick="clearAdditional();
        return false;" id="clearadditional" name="clearadditional"><i class="icon-remove-sign"></i> Clear</button></div>
                </div>
            </div>
            <div class="well pagination-centered">
                <div class="row">
                    <button type="button" onclick="submitForm()" class="btn btn-primary"><i class="icon-file icon-white"></i>  Add Variant</button><?php echo nbs(6); ?><button type="reset" class="btn"><i class="icon-remove-sign"></i> Clear All</button><?php echo nbs(6); ?><a href="<?php if ($this->session->userdata('admin_or_curate') == "curate") {
    echo base_url() . "curate/variants";
} else {
    echo base_url() . "admin/variants";
} ?>" class="btn" ><i class="icon-step-backward"></i> Go back</a>
                </div>
            </div>
<?php if (isset($result)) {
    echo "<p>Source was successfully added</p>";
} ?>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
