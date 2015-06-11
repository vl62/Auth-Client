function isPresent(value) {
    ol = document.getElementById('ont_list');
    for (var i = 0; i < ol.options.length; i++) {
        // existing values in the phenotype description list are converted to lowercase to eliminate case sensitivity
        if (ol.options[i].value.toLowerCase() == value) {
            return true;
        }
    }
    return false;
}


function addItemToList() {
    oi = document.getElementById('phenosearch');
    tid = document.getElementById('term-identifier');
    tcat = document.getElementById('term-category');
    tqual = document.getElementById('term-qualifier');
    ol = document.getElementById('ont_list');
    fixed_value = document.getElementById('value');
    user_value = document.getElementById('uservalue');
    myvalue=fixed_value.value;
    
     if (myvalue == "user defined"){
        if (user_value.value.length>0){
            myvalue = user_value.value;
        }
       else {
            myvalue="null";
       }
     }
     
     if (tqual.value.length > 0) {
         qualifier=tqual.value;
     }
     else{
         qualifier="null";
     }
   
                        
    if (oi.value.length > 0) {
        bp_preferred_name = oi.value;
        
        if (bp_preferred_name.indexOf("|") !=-1){
            alert("Sorry, the term can not contain a vertical bar \(|\) character");
            return;
        }
        
        if (bp_preferred_name.indexOf("@") !=-1){
            alert("Sorry, the term can not contain an at \(@\) symbol");
            return;
        }

        // set term and ontology id
        if (tid.value.length > 0) {
            bp_concept_id = tid.value;
            bp_ontology_id = tcat.value;
        }
        else {
            // local list id is converted to lowecase
            bp_concept_id = 'locallist/' + bp_preferred_name.toLowerCase();
            bp_concept_id = bp_concept_id.replace(/\s/g, '_');
            bp_ontology_id = 'LocalList';
        }

        bp_full_id = bp_concept_id;

        if (bp_ontology_id && bp_concept_id) {
            if (bp_concept_id.length == 0) {
                alert("Sorry this concept was not found in BioPortal!");
                oi.value = "";
                oi.focus();
                return
            }
            newlabel = bp_preferred_name + " (id:" + bp_concept_id + ", ontology:" + bp_ontology_id + ") " + myvalue;
            newvalue = bp_preferred_name + "|" + bp_concept_id + "|" + bp_ontology_id + "|" + myvalue + "|" + qualifier;
            
            // convert the value string to lowercase to compare with the existing list
            for(var i = 0, opts = ol.options; i < opts.length; ++i){
                if( opts[i].value === newvalue ){
                alert("Sorry, this term has already been added to the list");
                oi.value = "";
                oi.focus();
                bp_preferred_name.value = "";
                bp_concept_id.value = "";
                bp_ontology_id.value = "";
                bp_full_id.value = "";
                tid.value = "";
                tcat.value = "";
                fixed_value.value="present";
                user_value.value="";
                tqual.value="";
                jQuery('#uservalue').prop('disabled', true);
                return;
            }
            }

            opt = new Option(newlabel, newvalue);
            ol.add(opt);
            //clear fields
            oi.value = "";
            bp_preferred_name.value = "";
            bp_concept_id.value = "";
            bp_ontology_id.value = "";
            bp_full_id.value = "";
            tid.value = "";
            tcat.value = "";
            fixed_value.value="present";
            user_value.value="";
            tqual.value="";
            jQuery('#uservalue').prop('disabled', true);
        }
        else {
            alert("Sorry, your term was not added due to an unknown problem");
        }
    }
    else {
        alert("Please select or enter a term");


    }
}



function deleteSelectedItem() {
    ol = document.getElementById('ont_list');
    selIndex = ol.selectedIndex;
    if (selIndex == -1) {
        alert("Sorry, you did not select a term to delete or there are no terms in the list");
        return;
    }

    ol.remove(selIndex);
}

function deleteAllItems() {
    ol = document.getElementById('ont_list');
    ol.options.length = 0;
}

function submitForm() {


    ol = document.getElementById('ont_list');
    hi = document.getElementById('hidden_inputs');
    for (var i = 0; i < ol.options.length; i++) {
        e = document.createElement("input");
        e.setAttribute("value", ol.options[i].value);
        e.setAttribute("type", "hidden");
        e.setAttribute("name", "ont_item[]");
        hi.appendChild(e);
        e = document.createElement("input");
        e.setAttribute("value", ol.options[i].innerHTML);
        e.setAttribute("type", "hidden");
        e.setAttribute("name", "ont_name[]");
        hi.appendChild(e);
    }
    document.main_form.submit();
}

function enableOntology(ontologyId) {
    jQuery("#ont_id").val(ontologyId);
    jQuery("#ont_item").attr("disabled", false);
    jQuery("#ont_item").val("");
    jQuery("#ont_code").attr("disabled", false);
    jQuery("#ont_code").val("");

    var tempOi = ontologyId.split("|");
    jQuery("#ont_item").attr("class", "bp_form_complete-" + tempOi[1] + "-name");
    jQuery(".ac_results").remove();
    formComplete_setup_functions();
}

function disableOntology() {
    jQuery("#ont_item").attr("disabled", true);
    jQuery("#ont_item").val("");
    jQuery("#ont_code").attr("disabled", true);
    jQuery("#ont_code").val("");
    jQuery("#ont_id").val("");
}

function changeOntology() {
    enableOntology(jQuery("#ont_id").val());
}

function checkNotEmpty() {
    var ontologyList = document.getElementById('ontology_list');

    if (ontologyList.value.length == 0) {
        disableOntology();
    }
    else {
        for (var i = 0; i < window.sourceData.length; i++) {
            var item = window.sourceData[i];
            if (item.label.toLowerCase() === ontologyList.value.toLowerCase()) {
                jQuery("#ontology_list").val(item.label);
                enableOntology(item.value);
                return;
            }
        }
        disableOntology();
    }

}
