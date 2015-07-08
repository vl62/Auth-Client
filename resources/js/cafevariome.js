
// Resize multiple Twitter Bootstrap wells to the same size when on the same row
$('.well-group').height(function () {
	var h = _.max($(this).closest('.row').find('.well-group, .alert'), function (elem, index, list) {
		return $(elem).height();
	});
	return $(h).height();
});

// Initialize the input masking for the ORCID ID in the signup form(s) - uses the masked input jquery plugin http://digitalbush.com/projects/masked-input-plugin/#demo
jQuery(function($){
   $("#orcid").mask("9999-9999-9999-999*");
});

//$('.well-group').css({
//    'height': $('.well-group').height()
//});

// Main popover function from Twitter Bootstrap
$(function (){
    $("[rel=popover]").popover({placement:'right', trigger:'hover', animation:'true'}); // , delay: { show: 50, hide: 50 }
});

// Main tooltip function from Twitter Bootstrap
$(function (){
	$("[rel=tooltip]").tooltip();
});

$('.tooltip-searchprefs').tooltip();

function urldecode(url) {
	return decodeURIComponent(url.replace(/\+/g, ' '));
}

function variantOpenAccessRequest (term, source, source_full, count) {
	var visible_term = urldecode(term);
	bootbox.dialog("<h4>openAccess records request</h4><hr><p>" + source_full + ": requested " + count + " records</p><p>Search term: " + visible_term + "</p><p>Choose how you would like to access these records:</p>",
	[
		{
			"label" : "Close",
			"icon"  : "icon-remove icon-black"
		},
//		{
//		"label" : "VCF",
//		"class" : "btn-primary btn-small",
//		"callback": function() {
////			console.log("Primary button");
//			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/openAccess/vcf');
//			}
//		},
		{
		"label" : "RSS",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/openAccess/rss');
			}
		},
		{
		"label" : "VarioML",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/openAccess/varioml');
			}
		},
		{
		"label" : "JSON",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/openAccess/json');
			}
		},
		{
		"label" : "LOVD",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/openAccess/lovd');
			}
		},
		{
		"label" : "Excel",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/openAccess/excel');
			}
		},
		{
		"label" : "Plain text",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/openAccess/tab');
			}
		},
		{
		"label" : "Webpage",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/openAccess/html');
			
//			$.post("discover/variants", { term: term, source: source, source_full: source_full, count: count },
//				function(data) {
////					alert("Data Loaded: " + data);
//					var win = window.open();
//					win.document.write(data);
////					$('#mutationTable').append(data);
//			});
			

			}
		}
	],
		{
			"animate": false
		}
	)
}

function variantOpenAccessRequestFederated (term, source, source_full, count, federated_install_uri) {
//	alert(federated_install_uri);
	var visible_term = urldecode(term);
	bootbox.dialog("<h4>openAccess records request</h4><hr><p>" + source_full + ": requested " + count + " records</p><p>Search term: " + visible_term + "</p><p>Choose how you would like to access these records:</p>",
	[
		{
			"label" : "Close",
			"icon"  : "icon-remove icon-black"
		},
//		{
//		"label" : "VCF",
//		"class" : "btn-primary btn-small",
//		"callback": function() {
////			console.log("Primary button");
//			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/openAccess/vcf');
//			}
//		},
		{
		"label" : "RSS",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants_federated/' + term + '/' + source +  '/' + federated_install_uri + '/openAccess/rss');
			}
		},
		{
		"label" : "VarioML",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants_federated/' + term + '/' + source +  '/' + federated_install_uri + '/openAccess/varioml');
			}
		},
		{
		"label" : "JSON",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants_federated/' + term + '/' + source +  '/' + federated_install_uri + '/openAccess/json');
			}
		},
		{
		"label" : "LOVD",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants_federated/' + term + '/' + source +  '/' + federated_install_uri + '/openAccess/lovd');
			}
		},
		{
		"label" : "Excel",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants_federated/' + term + '/' + source +  '/' + federated_install_uri + '/openAccess/excel');
			}
		},
		{
		"label" : "Plain text",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants_federated/' + term + '/' + source +  '/' + federated_install_uri + '/openAccess/tab');
			}
		},
		{
		"label" : "Webpage",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants_federated/' + term + '/' + source +  '/' + federated_install_uri + '/openAccess/html');
			
//			$.post("discover/variants", { term: term, source: source, source_full: source_full, count: count },
//				function(data) {
////					alert("Data Loaded: " + data);
//					var win = window.open();
//					win.document.write(data);
////					$('#mutationTable').append(data);
//			});
			

			}
		}
	],
		{
			"animate": false
		}
	)
}

// Click listener for resetting the statatistics button in the administrator interface
$(document).ready(function() {
	$("#reset_stats").click(function() {
		if (window.confirm('Are you sure you want to reset the statistics?')) {
			$.ajax({
				url: baseurl + 'admin/reset_stats',
//				contentType: 'application/json',
//				data: {variants: JSON.stringify(selected), source_name: source_name},
				dataType: 'html',
				type: 'POST',
				success: function(data) {
//					alert(data);
					window.location.reload(true);
				}
			});
		}

		
		
	});
});


// THIS FUNCTION IS NO LONGER USED HERE - NOW DONE IN admin/curate view as it needs to be initialised after the ajax datatable has been dynamically generated
// Select all the checkboxes function toggle for records in the curate records admin table 
// $(document).ready(function() {
//	// add multiple select / deselect functionality
//	$("#selectall").click(function() {
//		$('.case').attr('checked', this.checked);
//	});
//	// if all checkbox are selected, check the selectall checkbox  also        
//	$(".case").click(function() {
//		if ($(".case").length == $(".case:checked").length) {
//			$("#selectall").attr("checked", "checked");
//		}
//		else {
//			$("#selectall").removeAttr("checked");
//		}      
//	});
//});

// Function to delete multiple records (implemented through the curate records interface)
function deleteVariantsMultiple() {
	var selected = new Array();
	$("input:checkbox").each(function(){
		var $this = $(this);
		if($this.is(":checked")){
			var cvid = $this.attr("id");
			selected.push(cvid);
		}
	});
	var source_name = $("#source_name").val();
//	alert("data -> " + JSON.stringify(selected));
//	var variantsJSON = JSON.stringify(selected);
	$.ajax({
		url: baseurl + 'variants/delete_variants_multiple',
//		contentType: 'application/json',
		data: {variants: JSON.stringify(selected), source_name: source_name},
		dataType: 'html',
//		delay: 200,
		type: 'post',
		success: function(data) {
//			alert(data);
			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem deleting the records");
		}
	});
}

// Sets the sharing policy for multiple records (implemented through the curate records interface)
function setSharingPolicyMultiple() {
	var sharing_policy = $('#sharing_policy').val();
	var selected = new Array();
	$("input:checkbox").each(function(){
		var $this = $(this);
		if($this.is(":checked")){
			var cvid = $this.attr("id");
			selected.push(cvid);
		}
	});
	var source_name = $("#source_name").val();
	$.ajax({
		url: baseurl + 'variants/set_sharing_policy_multiple',
//		contentType: 'application/json',
		data: {sharing_policy: sharing_policy, variants: JSON.stringify(selected), source_name: source_name},
		dataType: 'html',
//		delay: 200,
		type: 'post',
		success: function(data) {
			if ( data ) { // There's an error with the changing of the sharing policy - create an alert for the user
				alert(data);
			}
			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem setting the sharing policy of the records");
		}
	});
}

// Function to delete multiple records from AtomServer (implemented through the submissions interface)
function deleteAtomServerVariantsMultiple() {
	var selected = new Array();
	$("input:checkbox").each(function(){
		var $this = $(this);
		if($this.is(":checked")){
			var id = $this.attr("id");
//			alert("id " + id);
			selected.push(id);
		}
	});
	$.ajax({
		url: baseurl + 'variants/delete_atomserver_variants_multiple',
//		contentType: 'application/json',
		data: {variants: JSON.stringify(selected)},
		dataType: 'html',
//		delay: 200,
		type: 'post',
		success: function(data) {
//			alert(data);
			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem deleting the records");
		}
	});
}

// Function to delete multiple records from AtomServer (implemented through the submissions interface)
function makeVariantsLiveMultiple() {
	var selected = new Array();
	$("input:checkbox").each(function(){
		var $this = $(this);
		if($this.is(":checked")){
			var cvid = $this.attr("id");
			selected.push(cvid);
		}
	});
//	alert("data -> " + JSON.stringify(selected));
//	var variantsJSON = JSON.stringify(selected);
	$.ajax({
		url: baseurl + 'variants/make_variants_live_multiple',
//		contentType: 'application/json',
		data: {variants: JSON.stringify(selected)},
		dataType: 'html',
//		delay: 200,
		type: 'post',
		success: function(data) {
//			alert(data);
			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem making the records live");
		}
	});
}


function variantRestrictedAccessRequest (term, source, source_full, count) {
	var visible_term = urldecode(term);
	bootbox.dialog("<h4>restrictedAccess records request</h4><hr><p>You are a member of the required group so can directly access these records</p><p>" + source_full + ": requested " + count + " records</p><p>Search term: " + visible_term + "</p><p>Choose how you would like to access these records:</p>",
	[
		{
			"label" : "Close",
			"icon"  : "icon-remove icon-black"
		},
		{
		"label" : "RSS",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/restrictedAccess/rss');
			}
		},
		{
		"label" : "VarioML",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/restrictedAccess/varioml');
			}
		},
		{
		"label" : "JSON",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/restrictedAccess/json');
			}
		},
		{
		"label" : "LOVD",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/restrictedAccess/lovd');
			}
		},
		{
		"label" : "Excel",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/restrictedAccess/excel');
			}
		},
		
		{
		"label" : "Plain text",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/restrictedAccess/tab');
			}
		},
		{
		"label" : "Webpage",
		"class" : "btn-primary btn-small",
		"callback": function() {
//			console.log("Primary button");
			window.open(baseurl + 'discover/variants/' + term + '/' + source + '/restrictedAccess/html');
			
//			$.post("discover/variants", { term: term, source: source, source_full: source_full, count: count },
//				function(data) {
////					alert("Data Loaded: " + data);
//					var win = window.open();
//					win.document.write(data);
////					$('#mutationTable').append(data);
//			});
			

			}
		}
	],
		{
			"animate": false
		}
	)
}

// Confirm bootbox dialog for a change record sharing policy 
function confirmChangeSharingPolicy(access_level, source, source_id) {
	bootbox.confirm("Are you sure you want to change all records in " + source + " to " + access_level + "?", function(confirmed) {
//		console.log("Confirmed: "+confirmed);
		if(confirmed) {
			changeSharingPolicy(access_level, source, source_id);
		}
	});
	bootbox.backdrop(false);
}

// Select change listener for updating the status of a lead in the crm leads table
$(document).ready(function(){
	$( ".crm_status" ).change(function() {
		var id = $(this).attr('id');
		var status = $(this).val();
//		alert("id -> " + id);
		$.ajax({url: baseurl + 'admin/change_crm_lead_status',
			delay: 200,
			data: {id: id, status: status},
			type: 'POST',
			dataType: 'html',
			success: function(data) {
				if (data) {
				}
				else {
				}
			}
		});
	});
});

// Click listener for update status button in modal popup
$(document).ready(function(){
	$( "#change_status_confirm" ).click(function() {
		
		var status = $("#change_status_select").val();
//		alert("status -> " + status);
		var checkedLeadIDs = $('.lead_checkbox:checkbox:checked').map(function() {
			return $(this).attr('id').replace(/_lead/g, "");
		}).get();
		var numLeads = checkedLeadIDs.length;
		if ( numLeads < 1 ) {
			$('#statusModal').modal('hide');
			alert("You have not selected any leads to perform a status update on.");
		}
//		alert("numLeads -> " + numLeads);
		jQuery.each( checkedLeadIDs, function( i, id ) {
			$("#" + id + "_lead").prop('checked', false);
			$.ajax({url: baseurl + 'admin/change_crm_lead_status',
				delay: 200,
				data: {id: id, status: status},
				type: 'POST',
				dataType: 'html',
				success: function() {
					$('#statusModal').modal('hide');
					if(!alert(numLeads + " updated")){window.location.reload();}
				}
			});
		});
	});
});

// Listener for when the status update modal opens - if there aren't any checked leads then return error alert
//$(document).ready(function(){
//	$("#statusModal").on("show", function() {    // wire up the OK button to dismiss the modal when shown
//		var checkedLeadIDs = $('.lead_checkbox:checkbox:checked').map(function() {
//			return $(this).attr('id').replace(/_lead/g, "");
//		}).get();
//		var numLeads = checkedLeadIDs.length;
//		if ( numLeads < 1 ) {
//			$('#statusModal').modal('hide');
//			alert("You have not selected any leads to perform a status update on.");
//		}
//	});
//});

$(document).ready(function(){
	$( "#select_template_button" ).click(function() {
		var template_id = $("#selected_template").val();
//		alert("template_id -> " + template_id);
		
		$.ajax({url: baseurl + 'admin/get_crm_email_template',
			delay: 200,
			data: {template_id: template_id},
			type: 'POST',
			dataType: 'html',
			success: function(data) {
				if (data) {
//					alert("data -> " + data);
//					$("textarea#email_text").text(data);
					tinyMCE.activeEditor.setContent(data);
//					tinyMCE.getInstanceById('textarea_id').setContent(s);
				}
				else {
				}
			}
		});
		
	});
});
	

// Click listener for email leads button in crm leads table
$(document).ready(function(){
	$( "#email_leads_confirm" ).click(function() {
//        var answer = confirm("Are you sure you want to email?");
//        if (answer){
			var checkedLeadIDs = $('.lead_checkbox:checkbox:checked').map(function() {
				return $(this).attr('id').replace(/_lead/g, "");
			}).get();
			var numLeads = checkedLeadIDs.length;
			if ( numLeads < 1 ) {
				$('#statusModal').modal('hide');
				alert("You have not selected any leads to perform a status update on.");
			}
//			var email_text = $("#email_text").val();
			var email_text = tinyMCE.activeEditor.getContent(); // Need to use tinymce api to get the content of the textarea
			var email_comment = $("#email_comment").val();
			var status = $("#change_status_select_in_email").val();
//			alert("status -> " + status);
//			alert("email -> " + email_text);
//			alert("email comment -> " + email_comment);
//			alert(checkedLeadIDs.join(","));
//			checkedLeadIDs = JSON.stringify(checkedLeadIDs);
//			alert("--> " + checkedLeadIDs);
			$.ajax({url: baseurl + 'admin/email_crm_leads',
				delay: 200,
				data: {ids: checkedLeadIDs, email_text: email_text, email_comment: email_comment, status: status},
				type: 'POST',
				dataType: 'html',
				success: function() {
//					jQuery.each( checkedLeadIDs, function( i, id ) {
//						$("#" + id + "_lead").prop('checked', false);					
//						$.ajax({url: baseurl + 'admin/change_crm_lead_status',
//							delay: 200,
//							data: {id: id, status: 'Contacted', 'add_comment': 'yes'},
//							type: 'POST',
//							dataType: 'html',
//							success: function(data) {
//								if (data) {
//								}
//								else {
//								}
//							}
//						});
//					});
					$('#emailModal').modal('hide');
					if(!alert(numLeads + " emails successfully sent")){window.location.reload();}
				}
			});
//		}
//		else {
//			return false;
//		}
	});
});


// Click listener for delete leads button in crm leads table
$(document).ready(function(){
	$( ".delete_leads_button" ).click(function() {
        var answer = confirm("Are you sure you want to delete?");
        if (answer){
			var checkedLeadIDs = $('.lead_checkbox:checkbox:checked').map(function() {
				return $(this).attr('id').replace(/_lead/g, "");
			}).get();
			var numLeads = checkedLeadIDs.length;
//			checkedLeadIDs = JSON.stringify(checkedLeadIDs);
//			alert(checkedLeadIDs.join(","));
//			alert("--> " + checkedLeadIDs);
			$.ajax({url: baseurl + 'admin/delete_crm_leads',
				delay: 200,
				data: {ids: checkedLeadIDs},
				type: 'POST',
				dataType: 'html',
				success: function(data) {
					if(!alert(numLeads + " lead(s) successfully deleted")){window.location.reload();}
				}
			});
		}
		else {
            return false;
        }
		
	});
});

// Regenerate autocomplete terms
function validateVariantsInSourceWithMutalyzer(source, id) {
	$('#waitingmutalyzer' + id).show(500);
	var with_genomic_coordinates = $('#mutalyzer_check_with_genomic_coordinates option:selected').val();
	var report = $('#mutalyzer_check_with_report option:selected').val();
//	alert("report -> " + report);
	// Saving report file doesn't work on AJAX post call below so instead do a get on the mutalyzer check function
//	document.location= baseurl + 'admin/mutalyzer_check/' + source + '/' + with_genomic_coordinates + '/' + report;
//	$('#waitingmutalyzer' + id).hide(500);
//	alert("Mutalyzer checking complete");
//	$('#validateVariantsModal' + id).modal('hide');

//	$.ajax({url: baseurl + 'admin/mutalyzer_check' + '/' + source + '/' + with_genomic_coordinates + '/' + report,
    $.ajax({url: baseurl + 'admin/mutalyzer_check',
		delay: 200,
		data: {source: source, with_genomic_coordinates: with_genomic_coordinates, report: report},
		type: 'POST',
//		async: false,
		success: function(data) {
			$('#waitingmutalyzer' + id).hide(500);
//			alert("Mutalyzer checking complete");
//			$('#validateVariantsModal' + id).modal('hide');
			if (data) {
				$( "div#report_download" ).html( "<p>" + data + "</p>" );
			}
			else {
//				alert("data");
				$('#validateVariantsModal' + id).modal('hide');		
			}
		}
	});
}

// Regenerate autocomplete terms
function regenerateAutocomplete() {
	$('#waiting').show(500);
    $.ajax({url: baseurl + 'admin/regenerate_autocomplete',
		delay: 200,
		type: 'POST',
		success: function() {
			$('#waiting').hide(500);
			alert("Autocomplete terms have been updated");
		}
	});
}

// Regenerate autocomplete terms
function regenerateFederatedPhenotypeAttributesAndValues() {
	$('#waiting').show(500);
    $.ajax({url: baseurl + 'admin/regenerate_federated_phenotype_attributes_and_values_list',
		delay: 200,
		type: 'POST',
		success: function() {
			$('#waiting').hide(500);
			alert("Federated phenotype attributes and values list has been updated");
		}
	});
}

// Regenerate elasticsearch index
function regenerateElasticSearchIndex() {
	$('#waiting').show(500);
    $.ajax({url: baseurl + 'admin/regenerate_elasticsearch_index',
		delay: 200,
		type: 'POST',
		dataType: 'html',
		success: function(data) {
			$('#waiting').hide(500);
			alert(data);
		}
	});
}

function startElasticSearch() {
    $.ajax({url: baseurl + 'admin/start_elasticsearch',
		delay: 200,
		type: 'POST',
		dataType: 'html',
		success: function(data) {
//			window.location.reload(true);
			alert(data);
		}
	});
}

// Regenerate autocomplete terms
function regenerateOntologyDAG() {
	$('#waiting').show(500);
    $.ajax({url: baseurl + 'admin/regenerate_ontologydag',
		delay: 200,
		type: 'POST',
		success: function() {
			$('#waiting').hide(500);
			alert("Ontology tree and search have been updated");
                }
                
    });
}


// Enable sortable rows for the 3 search results display fields tables (openAccess, restrictedAccess and linkedAccess) in the settings interface to allow ordering of fields, add event listener to reorder the rows when the they sorted by the user 
// TODO: Combine all into one function instead of repeating 3 times - should be possible - see http://stackoverflow.com/questions/8034286/jquery-sortable-selector-id-vs-class
$(function() {
	$( "#openAccesstable tbody" ).sortable({
		start : function(){
//			count = 0;
		},
		change : function(){
//			count++;
		},
		stop : function(){
//			alert(count);
//			alert("test");
			row_order = 0;
			$('#openAccesstable > tbody  > tr > td.count').each(function() { // Go through each row of table
				row_order++;
				$(this).html(row_order); // Reset the order so that the first has a value of 1, second 2 etc...
			});
			saveSearchResultsDisplayFieldsOrder();
		}
	}).disableSelection();
});


$(function() {
	$( "#restrictedAccesstable tbody" ).sortable({
		start : function(){
//			count = 0;
		},
		change : function(){
//			count++;
		},
		stop : function(){
//			alert(count);
//			alert("test");
			row_order = 0;
			$('#restrictedAccesstable > tbody  > tr > td.count').each(function() { // Go through each row of table
				row_order++;
				$(this).html(row_order); // Reset the order so that the first has a value of 1, second 2 etc...
			});
			saveSearchResultsDisplayFieldsOrder();
		}
	}).disableSelection();
});

$(function() {
	$( "#linkedAccesstable tbody" ).sortable({
		start : function(){
//			count = 0;
		},
		change : function(){
//			count++;
		},
		stop : function(){
//			alert(count);
//			alert("test");
			row_order = 0;
			$('#linkedAccesstable > tbody  > tr > td.count').each(function() { // Go through each row of table
				row_order++;
				$(this).html(row_order); // Reset the order so that the first has a value of 1, second 2 etc...
			});
			saveSearchResultsDisplayFieldsOrder();
		}
	}).disableSelection();
});

$(function() {
	$( "#individualrecordstable tbody" ).sortable({
		start : function(){
//			count = 0;
		},
		change : function(){
//			count++;
		},
		stop : function(){
//			alert(count);
//			alert("test");
			row_order = 0;
			$('#individualrecordstable > tbody  > tr > td.count').each(function() { // Go through each row of table
				row_order++;
				$(this).html(row_order); // Reset the order so that the first has a value of 1, second 2 etc...
			});
			
			saveIndividualRecordDisplayFieldsOrder();

		}
	}).disableSelection();
});

function addDisplayField() {
//	var type = $('#type option:selected').text();
	var sharing_policy = $('#sharing_policy :selected').val();
//	alert("sharing_policy -> " + sharing_policy);
	var type = $('#displaytype option:selected').val();
//	var field_name = $('#fields option:selected').text();
	var field_name = $('#displayfields option:selected').val();
	var visible_field_name = field_name.replace("_"," ");
	visible_field_name = ucwords(visible_field_name.toLowerCase());
	var count_array = $('#' + sharing_policy + 'table tr td.count').map(function(_, td) {
		return $(td).text();
	}).get(); // Get all the order counts currently in the table
//	alert("count_array -> " + count_array);
	if (count_array.length <1) {
		var new_highest_order = 1;
	}
	else {
		var largest = Math.max.apply(Math, count_array);
//		alert("count_array -> " + count_array + " largest -> " + largest);
		var new_highest_order = largest + 1;
		
	}
	
	var field_names_array = $('#' + sharing_policy + 'table tr td.fieldname').map(function(_, td) {
		return $(td).text();
	}).get(); // Get all the field names currently in the table
//	alert("values -> " + field_names_array);
	if ( $.inArray(field_name, field_names_array) == -1 ) { // Only add the field if it's not already present in the table
	//	alert("type -> " + type + " field_name -> " + field_name);

//		saveSearchResultsDisplayFieldsOrder();

		$.ajax({url: baseurl + 'admin/add_display_field',
			data: {field_name: field_name, visible_field_name: visible_field_name, sharing_policy: sharing_policy},
			dataType: 'json',
			delay: 200,
			type: 'POST',
			success: function(data) {
				if ( data ) {
//					alert("data -> " + data.insert_id + " -> " + data.highest_order + " -> " + new_highest_order);
					var field_id = data.insert_id;
//					window.location = baseurl + "admin/settings";


					var row = '<tr><td><i class="icon-move"></i></td><td class="count">' + new_highest_order + '</td><td class="fieldname">' + field_name + '</td><td class="visiblename"><a href="#" class="visiblenamevalue" data-type="text" data-pk="' + new_highest_order + '" data-url="' + baseurl + 'admin/change_visible_display_name" data-title="Enter Human Readable Name" >' + visible_field_name + '</a></td><td><a href="' + baseurl +  'admin/delete_display_field/' + sharing_policy + '/' + field_id + '" rel="popover" data-content="Hide this display field." data-original-title="Hide Display Field"></i><button type="button" class="btn btn-small">HIDE</button></a></td></tr>';
					$('#' + sharing_policy + 'table tr:last').after(row);
					$('.visiblenamevalue').editable(); // Make the new row visible_name field editable (since it has been added after the DOM was loaded

				}
				else {
					alert("Unable to add the search field");
				}
			}
		});
		
		
		

	}
	else {
		alert("The field is already present in the table, you cannot add the same field more than once.");
	}
}

// Function to save the order of the search results display fields
function saveSearchResultsDisplayFieldsOrder() {
	var sharing_policy = $('#sharing_policy :selected').val();
//	alert("sharing policy -> " + sharing_policy);
	var field_names = JSON.stringify($('#' + sharing_policy + 'table tr td.fieldname').map(function(_, td) {
		return $(td).text();
		alert("test -> " + $(td).text());
	}).get()); // Get all the field names currently in the table
	var orders = JSON.stringify($('#' + sharing_policy + 'table tr td.count').map(function(_, td) {
		return $(td).text();
//		alert("test -> " + $(td).text());
	}).get()); // Get all the orders currently in the table
	var visible_field_names = JSON.stringify($('#' + sharing_policy + 'table tr td a.visiblenamevalue').map(function(_, a) {
		return $(a).text();
//		alert("test -> " + $(a).text());
	}).get()); // Get all the visible field names currently in the table
	$.ajax({url: baseurl + 'admin/set_display_fields',
		data: {field_names: field_names, orders: orders, visible_field_names: visible_field_names, sharing_policy: sharing_policy},
		dataType: 'html',
		delay: 200,
		type: 'POST',
		success: function() {
//			window.location = baseurl + "admin/settings";
		}
	});
}

// Function to save the order of the individual record display fields
function saveIndividualRecordDisplayFieldsOrder() {
	// Save the order
	var field_names = JSON.stringify($('#individualrecordstable tr td.fieldname').map(function(_, td) {
		return $(td).text();
//		alert("test -> " + $(td).text());
	}).get()); // Get all the field names currently in the table
	var orders = JSON.stringify($('#individualrecordstable tr td.count').map(function(_, td) {
		return $(td).text();
//		alert("test -> " + $(td).text());
	}).get()); // Get all the orders currently in the table
	var visible_field_names = JSON.stringify($('#individualrecordstable tr td a.visiblenamevalue').map(function(_, a) {
		return $(a).text();
//		alert("test -> " + $(a).text());
			
	}).get()); // Get all the visible field names currently in the table
	$.ajax({url: baseurl + 'admin/set_individual_records',
		data: {field_names: field_names, orders: orders, visible_field_names: visible_field_names},
		dataType: 'html',
		delay: 200,
		type: 'POST',
		success: function() {
//			window.location = baseurl + "admin/settings";
		}
	});
	
}

// Change listener for when a sharing policy is changed in the display fields settings - also set the session to the sharing policy so it can be returned to if page is navigated away from
$(document).ready(function(){
	$( "#sharing_policy" ).change(function() {
		var sharing_policy = $('#sharing_policy :selected').val();
		$(".sharing_policies_div").hide();
		$('#' + sharing_policy).show();
//		alert( "Handler for .change() called. -> " + sharing_policy );
		$.ajax({
			type: "POST",
			data: { sharing_policy: sharing_policy },
			url: baseurl + "admin/set_current_sharing_policy_in_session_for_display_fields_tab",
			dataType: 'html',
			success: function() {
//				alert("test");
//				window.location = baseurl + "admin/settings";
			}
		});
	});
});

// Function for saving and altering the order of the display fields in the settings dashboard
$(document).ready(function(){
	$('#save_display_order').click( function() {
		var sharing_policy = $('#sharing_policy :selected').val();
//		alert("sharing policy -> " + sharing_policy);
		var field_names = JSON.stringify($('#' + sharing_policy + 'table tr td.fieldname').map(function(_, td) {
			return $(td).text();
			alert("test -> " + $(td).text());
		}).get()); // Get all the field names currently in the table

		var orders = JSON.stringify($('#' + sharing_policy + 'table tr td.count').map(function(_, td) {
			return $(td).text();
//			alert("test -> " + $(td).text());
		}).get()); // Get all the orders currently in the table

		var visible_field_names = JSON.stringify($('#' + sharing_policy + 'table tr td a.visiblenamevalue').map(function(_, a) {
			return $(a).text();
//			alert("test -> " + $(a).text());
			
		}).get()); // Get all the visible field names currently in the table

		$.ajax({url: baseurl + 'admin/set_display_fields',
			data: {field_names: field_names, orders: orders, visible_field_names: visible_field_names, sharing_policy: sharing_policy},
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function() {
				window.location = baseurl + "admin/settings";
			}
		});
	});
	
});

// Adds an individual record from settings display field table
function addIndividualRecord() {
	var field_name = $('#individual_record_field option:selected').val();
	visible_field_name = field_name.replace("_"," ");
	visible_field_name = ucwords(visible_field_name.toLowerCase());
//	alert("visible_field_name -> " + visible_field_name);	
	var count_array = $('#individualrecordstable tr td.count').map(function(_, td) {
		return $(td).text();
	}).get(); // Get all the order counts currently in the table
//	alert("count_array -> " + count_array);
	if (count_array.length <1) {
		var new_highest_order = 1;
	}
	else {
		var largest = Math.max.apply(Math, count_array);
//		alert("count_array -> " + count_array + " largest -> " + largest);
		var new_highest_order = largest + 1;
		
	}
	
	var field_names_array = $('#individualrecordstable tr td.fieldname').map(function(_, td) {
		return $(td).text();
	}).get(); // Get all the field names currently in the table
//		alert("values -> " + field_names_array);
	if ( $.inArray(field_name, field_names_array) == -1 ) { // Only add the field if it's not already present in the table
	//	alert("type -> " + type + " field_name -> " + field_name);

//		saveIndividualRecordDisplayFieldsOrder();
		$.ajax({url: baseurl + 'admin/add_individual_record',
			data: {field_name: field_name, visible_field_name: visible_field_name},
			dataType: 'json',
			delay: 200,
			type: 'POST',
			success: function(data) {
				if ( data ) {
//					alert("data -> " + data.insert_id + " -> " + data.highest_order + " -> " + new_highest_order);
					var field_id = data.insert_id;
//					window.location = baseurl + "admin/settings";

					var row = '<tr><td><i class="icon-move"></i></td><td class="count">' + new_highest_order + '</td><td class="fieldname">' + field_name + '</td><td class="visiblename"><a href="#" class="visiblenamevalue" data-type="text" data-pk="' + new_highest_order + '" data-url="' + baseurl + 'admin/change_visible_display_name" data-title="Enter Human Readable Name" >' + visible_field_name + '</a></td><td><a href="' + baseurl +  'admin/delete_individual_record/' + field_id + '" rel="popover" data-content="Hide this display field." data-original-title="Hide Display Field"><button type="button" class="btn btn-small">HIDE</button></a></td></tr>';
//		
					$('#individualrecordstable tr:last').after(row);
					$('.visiblenamevalue').editable(); // Make the new row visible_name field editable (since it has been added after the DOM was loaded
//					
				}
				else {
					alert("Unable to add the search field");
				}
			}
		});




	}
	else {
		alert("The field is already present in the table, you cannot add the same field more than once.");
	}
}

   //fix modal force focus
   $.fn.modal.Constructor.prototype.enforceFocus = function () {
      var that = this;
      $(document).on('focusin.modal', function (e) {
         if ($(e.target).hasClass('select2-input')) {
            return true;
         }

         if (that.$element[0] !== e.target && !that.$element.has(e.target).length) {
            that.$element.focus();
         }
      });
   };

// Adds an individual record from settings display field table
function addSearchField() {
	var field_name = $('#search_fields option:selected').val();
//	alert("field_name -> " + field_name);
	var field_names_array = $('#searchfieldstable tr td.fieldname').map(function(_, td) {
		return $(td).text();
	}).get(); // Get all the field names currently in the table

	if ( $.inArray(field_name, field_names_array) == -1 ) { // Only add the field if it's not already present in the table
	//	alert("type -> " + type + " field_name -> " + field_name);
		
//		alert("field_name -> " + field_name);
		$.ajax({url: baseurl + 'admin/add_search_field',
			data: {field_name: field_name},
			dataType: 'json',
			delay: 200,
			type: 'POST',
			success: function(data) {
				if ( data ) {
//					alert("data -> " + data);
					var search_field_id = data;
//					window.location = baseurl + "admin/settings";
					var row = '<tr><td class="fieldname">' + field_name + '</td><td><a href="' + baseurl +  'admin/delete_search_field/' + search_field_id + '" rel="popover" data-content="Hide search field from the search. N.B. The field may be re-added by using the form above." data-original-title="Hide Search Field"><button type="button" class="btn btn-small">HIDE</button></a></td></tr>';
//																			echo base_url('admin/delete_search_field') . "/" . $search_field['search_field_id']
					$('#searchfieldstable tr:last').after(row);
				}
				else {
					alert("Unable to add the search field");
				}
			}
		});

	}
	else {
		alert("The search field is already present in the table, you cannot add the same field more than once.");
	}
	

}

$(function(){
    $('#search-popover').editable({
//		mode: "inline"
//		showbuttons: false
//		url: baseurl + 'discover/variants',
		value: 'ru',
		source:
//			function (query, process) {
//				return $.get(baseurl + 'discover/lookup', { query: query }, function (data) {
//					return process(data.options);
//				});
//			}

			function (query, process) {
				$.ajax({
					url: baseurl + 'discover/lookup',
					type: 'POST',
					dataType: 'JSON',
					data: 'term=' + query,
					success: function(data) {
						console.log(data);
						process(data);
					}
				});
			}
			

			
	});
});

function ucwords (str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

// Function for saving and altering the order of the display fields in the settings dashboard
$(document).ready(function(){
	$('#save_individual_record_display_order').click( function() {
		var field_names = JSON.stringify($('#individualrecordstable tr td.fieldname').map(function(_, td) {
			return $(td).text();
//			alert("test -> " + $(td).text());
		}).get()); // Get all the field names currently in the table

		var orders = JSON.stringify($('#individualrecordstable tr td.count').map(function(_, td) {
			return $(td).text();
//			alert("test -> " + $(td).text());
		}).get()); // Get all the orders currently in the table

		var visible_field_names = JSON.stringify($('#individualrecordstable tr td a.visiblenamevalue').map(function(_, a) {
			return $(a).text();
//			alert("test -> " + $(a).text());
			
		}).get()); // Get all the visible field names currently in the table

		$.ajax({url: baseurl + 'admin/set_individual_records',
			data: {field_names: field_names, orders: orders, visible_field_names: visible_field_names},
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function() {
				window.location = baseurl + "admin/settings";
			}
		});
	});
});

// Enable sortable rows for menu table in CMS
$(function() {
	$( "#menus tbody" ).sortable({
		start : function(){
		//		count = 0;
		},
		change : function(){
		//               count++;
		},
		stop : function(){
			//               alert(count);
			//               alert("test");
			row_order = 0;
			var orders = [];
			$('#menus > tbody  > tr > td.count').each(function() { // Go through each row of table
				row_order++;
				$(this).html(row_order); // Reset the order so that the first has a value of 1, second 2 etc...
				orders.push($(this).html());
			});
			orders = JSON.stringify(orders);
			//               alert("orders -> " + orders);

			var menu_names = [];
			$('#menus > tbody  > tr > td.menuname').each(function() { // Go through each row of table
				menu_names.push($(this).html());
			});
			menu_names = JSON.stringify(menu_names);
			//               alert("menu_names -> " + menu_names);
			$.ajax({
				url: baseurl + 'cms/change_menu_order',
				data: {
					menu_names: menu_names, 
					orders: orders
				},
				dataType: 'html',
				delay: 200,
				type: 'POST',
				success: function() {
					window.location = baseurl + "cms/menus";
				//                         alert("Autocomplete terms have been updated");
				}
			});

		}
	}).disableSelection();
});

// Store current tab
$(function() {
	$('a[data-toggle="tab"]').on('shown', function (e) {
		var tab = $(e.target).attr('href');
		
		var pathArray = window.location.pathname.split( '/' );
		var current_page = pathArray[pathArray.length-1]; // Get the current page - so that multiple pages can use the same function to set the tab value in the session specific for a certain page
//		var url = baseurl + 'admin/set_current_' + current_page + '_tab'
//		alert("changed tab -> " + tab);
//		alert("current page -> " + current_page);
//		alert("tab url -> " + url);
		if ( tab != '#individual_record' && tab != '#search_result' && tab != '#regenerate' && tab != '#cron' ) { // Don't want to set the sub-tabs for display fields (this is done in separate listener below)
//			alert("changed tab -> " + tab);
			$.ajax({url: baseurl + 'admin/set_current_tab',
				data: {tab: tab, current_page: current_page},
				dataType: 'json',
				delay: 200,
				type: 'POST',
				success: function() {
//					response(data);
				}
			});
		}
	});
});

// Select listener for the sub tabs in display fields
$(function() {
	$('#fields a[data-toggle="tab"]').on('shown', function (e) {
		var tab = $(e.target).attr('href');
//		alert("tab -> " + tab);
		$.ajax({url: baseurl + 'admin/set_current_display_fields_tab',
			data: {tab: tab},
			dataType: 'json',
			delay: 200,
			type: 'POST',
			success: function() {
//				response(data);
			}
		});
	});
});

// Select listener for the sub tabs in maintenance tabs
$(function() {
	$('#maintenance a[data-toggle="tab"]').on('shown', function (e) {
		var tab = $(e.target).attr('href');
//		alert("tab -> " + tab);
		$.ajax({url: baseurl + 'admin/set_current_maintenance_tab',
			data: {tab: tab},
			dataType: 'json',
			delay: 200,
			type: 'POST',
			success: function() {
//				response(data);
			}
		});
	});
});


// User has confirmed they wish to change the sharing policy for the records in this source, go ahead and change
function changeSharingPolicy(source, sharing_policy_id) {
	var sharing_policy = $('#sharing_policy_' + sharing_policy_id).val();
//	var sharing_policy = "openAccess";
//	alert("source -> " + source + " sp -> " + sharing_policy + " sharing_policy_id -> " + sharing_policy_id );
    $.ajax({url: baseurl + 'admin/change_sharing_policy',
		data: {sharing_policy: sharing_policy, source: source},
		dataType: 'json',
		delay: 200,
		type: 'POST',
		success: function() {
//			response(data);
			alert("Sucessfully changed all records in " + source + " to " + sharing_policy);
			$('#sharingPolicyModal' + sharing_policy_id).modal('hide');
//			alert("test -> " + sharing_policy + " source -> " + source + " source_id" + source_id);
		}
	});
}

// User has confirmed they wish to change the contact email for all the records in this source
function changeLink(source, link_id) {
	var link = $('#link_' + link_id).val();
//	alert(link);
//	alert("source -> " + source + " link -> " + link + " link_id -> " + contact_email_id );
    $.ajax({url: baseurl + 'admin/change_link',
		data: {link: link, source: source},
		dataType: 'json',
		delay: 200,
		type: 'POST',
		success: function() {
//			response(data);
			alert("Sucessfully changed all records in " + source + " to " + link);
			$('#linkModal' + link_id).modal('hide');
//			alert("test -> " + contact_email + " source -> " + source + " source_id" + source_id);
		}
	});
}

// Function for processing a data request by admin user
function processDataRequest(result, request_id) {
	var resultreason = '';
	if ( result === 'approved' ) {
		resultreason = $('#approved_' + request_id).val();
	}
	else if ( result === 'refused' ) {
		resultreason = $('#refused_' + request_id).val();
	}
	else if ( result === 'delete' ) {
		resultreason = 'none';
	}
//	alert(request_id + " -> " + result + " -> " + resultreason);

    $.ajax({url: baseurl + 'admin/process_data_request/' + result + '/' + request_id,
		data: {resultreason: resultreason},
		dataType: 'json',
		delay: 200,
		type: 'POST',
		success: function(data) {
			if ( data.error ) {
				$('.modal').modal('hide');
				alert(data.error);
				
			}
			else if (data.success) {
				$('.modal').modal('hide');
//				alert(data.success);
//				window.location = baseurl + "admin/data_requests";
				window.location.reload(true);
			}
			else {
//				window.location = baseurl + "admin/data_requests";
				window.location.reload(true);
			}
		}
	});
}

// Add the example term that was clicked to the searh input field
$(function() {
    $('.termselect').click(function(e) {
//		alert("test");
		e.preventDefault();
		var href = $(this).text();
		$('#term').val(href);
    });
});

// Autocomplete lookup function for discovery search
$(document).ready(function() {
	$(function() {
		$("#term").autocomplete({
			source: function(request, response) {
				$.ajax({url: baseurl + 'discover/lookup',
				data: {term: $("#term").val()},
				dataType: 'json',
				delay: 200,
				type: 'POST',
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2
		});
	});
});

// Autocomplete lookup function for search modal in navbar
$(document).ready(function() {
	$(function() {
		$("#navbar_term").autocomplete({
			source: function(request, response) {
				$.ajax({url: baseurl + 'discover/lookup',
				data: {term: $("#navbar_term").val()},
				dataType: 'json',
				delay: 200,
				type: 'POST',
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2
		});
	});
});

// Autocomplete lookup function for search modal in navbar
$(document).ready(function() {
	$(function() {
		$("#search_term").autocomplete({
			source: function(request, response) {
				$.ajax({url: baseurl + 'discover/lookup',
				data: {term: $("#search_term").val()},
				dataType: 'json',
				delay: 200,
				type: 'POST',
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2
		});
	});
});

// Autocomplete lookup function for record input gene box (need to search through all genes that exist, not just those in the DB)
// NB Now the call is done to the central auth server since move to client/server so the client no longer needs to have all genes in their local database
$(document).ready(function() {
	$(function() {
		$("#gene").autocomplete({
			source: function(request, response) {
				$.ajax({url: authurl + '/variants/genelookup',
				data: {term: $("#gene").val()},
				dataType: 'json',
				delay: 200,
				type: 'POST',
				success: function(data) {
					response(data);
//					alert("data -> " + data);
				}
			});
		},
		minLength: 1
		});
	});
});

// Autocomplete lookup function for record input refseq box
// NB Now the call is done to the central auth server since move to client/server so the client no longer needs to have all refseq in their local database
$(document).ready(function() {
	$(function() {
		$("#ref").autocomplete({
			source: function(request, response) {
				$.ajax({url: authurl + '/variants/refseqlookup',
				data: {term: $("#ref").val()},
				dataType: 'json',
				delay: 200,
				type: 'POST',
				success: function(data) {
					response(data);
//					alert("data -> " + data);
				}
			});
		},
		minLength: 1
		});
	});
});

// Autocomplete lookup function for phenotype and omim term - if a gene is present in gene input then do the lookup based on the phenotypes for that gene, otherwise search through all available phenotypes
$(document).ready(function() {
	$(function() {
		$("input#phenotype").autocomplete({
			minLength: 0,
			source: function(request, response) {
				$.ajax({url: baseurl + 'variants/phenotypelookup',
					data: {phenotype: $("#phenotype").val(), gene: $("#gene").val()},
					dataType: 'json',
					delay: 200,
					type: 'POST',
					success: function(data) {
						response(data);
//						alert("data -> " + data);
					}
				});
			},
			select: function (event, ui) {
//				alert("selected -> " + ui.item.id);
				$('#phenotype_omim').val(ui.item.id);
//				return false;
			}
		}).focus(function(event, ui) {
			$(this).autocomplete("search", "");
//			alert("test");
		});
	});
});

// Disable laboratories select box unless diagnostic records option is selected
$(document).ready(function(){
	if ( $('#sources').val() === 'variants' ) {
		$('#laboratories').attr('disabled', false);
	}
	else {
		$("#laboratories").val(0);
		$('#laboratories').attr('disabled', true);
	}
});
$('#sources').change(function() {
	var value = $(this).val();
	if ( $(this).val() === 'variants' ) {
		$('#laboratories').attr('disabled', false);
	}
	else {
		$("#laboratories").val(0);
		$('#laboratories').attr('disabled', true);

	}
});

// Preview image background in modal box in preferences section of admin interface
//$("a.thumbnail").click(function(e) {
//	e.preventDefault();
//	var linkID = $(this).attr("id"); 
//	var imgSrcVal = $('img', this).attr("src");
////	alert("i -> " + imgSrcVal);
//	bootbox.modal('<img src="' + imgSrcVal + '" width="256" height="256" alt=""/>', 'Background image');
//});

// Initialize bootstrap arrows with rotate functionality (http://bootstrap-arrows.iarfhlaith.com/index.html)
$(function () {    		
	// Bootstrap Arrows
	$('.arrow, [class^=arrow-]').bootstrapArrows();			
});

// When the fetch ORCID details button is pressed then lookup the ORCID and populate the name fields in the form with the returned values from the ORCID XML
function fetchORCID() {
	var orcid = document.getElementById("orcid").value; // 0000-0002-0451-9702
	$('#loading-indicator').show();
	if ( orcid.length === 0 ) {
		alert("Please enter a valid ORCID");
	}
	else {
		var orcidURL = "http://pub.orcid.org/" + orcid;
//		alert("clicked -> " + orcidURL);

		$.ajax({url: baseurl + 'auth/orcid_lookup',
			data: {orcid: orcid},
			dataType: 'json',
			delay: 200,
			type: 'POST',
			success: function(data) {
//				response(data);
				if (data) {
//					alert("data " + data);
//					var json = $.parseJSON(data);
					var givenname = data.givenname;
					var familyname = data.familyname;
					if ( givenname ) {
//						alert("name " + givenname);
						bootbox.confirm("Data for ORCID: " + orcid + "<br /><br />Name: "+ givenname + " " + familyname + "<br /><br />Please confirm whether you would like to link this ORCID to your Cafe Variome account", function(result) {
							if (result == true) {
								$('#first_name').empty();
								$("#first_name").val(givenname);
								$('#last_name').empty();
								$("#last_name").val(familyname);
							}
						});
					}
					else {
//						alert("not defined");
					}
				}
				else {
					alert("Sorry, there was a problem accessing the ORCID webservice");

				}
			},
			error: function(httpRequest, textStatus, errorThrown) { 
//				alert("ORCID error: status=" + textStatus + ",error=" + errorThrown);
				alert("Sorry, we could not retrieve the details for that ORCID");
			}
		});
	}
}

// Initialise the fileupload jquery plugin - used for bulk import of records interface
jQuery(function($){
	$('.fileUpload').fileUploader({
		allowedExtension: 'xls|xlsx|vcf|txt|xml|sql|lovd',
		percentageInterval: [10, 20, 30, 40, 60, 80],
		afterEachUpload: function(data, status, formContainer){
			$jsonData = $.parseJSON( $(data).find('#upload_data').text() );
		}
	});
});

// Generate md5 string for a new Cafe Variome federated node
function generateMD5() {
	$.ajax({url: baseurl + 'admin/generateMD5Node',
//		data: {ref: ref, hgvs: hgvs},
		dataType: 'html',
		delay: 200,
		type: 'POST',
		success: function(data) {
//			alert("md5 " + data);
			$('#key').val(data);
		}
	});
}
// Validate button in add record clicked - check the reference and hgvs with Mutalyzer
function mutalyzerValidate() {
	var ref = document.getElementById("ref").value;
	var hgvs = document.getElementById("hgvs").value;
	if ( ref.length === 0 && hgvs.length === 0 ) {
		alert("Please enter both the reference and the HGVS nomenclature for the variant");
	}
	else {
//		alert("ref -> " + ref + " hgvs -> " + hgvs);
		$.ajax({url: baseurl + 'variants/validate',
			data: {ref: ref, hgvs: hgvs},
			dataType: 'json',
			delay: 200,
			type: 'POST',
			success: function(data) {
//				response(data);
				
				if (data) {
					var is_valid = data.is_valid;
					var message = data.message;
					var summary = data.summary;
					var errors = data.errors;
					var warnings = data.warnings;
					if ( is_valid ) {
						var chr = data.chr;
						var start = data.start;
						var end = data.end;
						var genomic_ref = data.genomic_ref;
						var genomic_hgvs = data.genomic_hgvs;
						var protein_ref = data.protein_ref;
						var protein_hgvs = data.protein_hgvs;
						if ( message ) {
//							alert("errors -> " + errors + " warnings -> " + warnings + " summary -> " + summary + " message -> " + message);
							alert("Variant was successfully validated with Mutalyzer, however there were some warnings. Either continue as normal or address the warning below and revalidate.\n\n" + "Warning: " + message);
						}
						else {
							alert("Variant was successfully validated with Mutalyzer and any available information in the form has been populated.");
						}
						// Append the mutalyzer synatax check result tick box if successful
						$('#validateresult').empty();
						$('#validateresult').append('<button class="btn btn-mini btn-success" disabled="disabled" rel="popover" data-content="Your variant reference and HGVS nomenclature passed the Mutalyzer validation check." data-original-title="Variant Is Valid"><i class="icon-ok"></i></button>');
						// Mutalyzer check passed, set the flag to 1
						$('#addvariant').append('<input type="hidden" id="mutalyzer" name="mutalyzer" value="1" />');

						// Append returned data from Mutalyzer
						$('#location_ref').val(chr);
						$('#start').val(start);
						$('#end').val(end);
						$('#build').val('hg19');
						$('#genomic_ref').val(genomic_ref);
						$('#genomic_hgvs').val(genomic_hgvs);
						$('#protein_ref').val(protein_ref);
						$('#protein_hgvs').val(protein_hgvs);
					}
					else {
						$('#validateresult').empty();
//						$('#validateresult').append('<button class="btn btn-mini btn-danger" disabled="disabled"><i class="icon-remove-sign icon-white"></i> Variant is not valid!</button>');
						$('#validateresult').append('<button class="btn btn-mini btn-danger" disabled="disabled" rel="popover" data-content="Your variant reference and HGVS nomenclature did not pass the Mutalyzer validation check." data-original-title="Variant Not Valid"><i class="icon-remove-sign icon-white"></i></button>');
						// Mutalyzer check failed, set the flag to 1
						$('#addvariant').append('<input type="hidden" id="mutalyzer" name="mutalyzer" value="0" />');
						alert("There was a problem validating the variant with Mutalyzer:\n\nError: " + message);
					}

				}
			}
		});
	}
}

// Add additional phenotype boxes if add button clicked in add variant form
function additionalPhenotype () {
//$("#additionalphenotype").click(function(e) {
//	e.preventDefault();
	$('.pheno').clone().removeClass('pheno').appendTo('#newpheno');
//	$('.validateresult').remove();
//	$('#newvar').html($('#var').html());
//	alert("test");
//});
}

// Add additional variant boxes if add button clicked in add variant form
$("#additionalvariant").click(function(e) {
	e.preventDefault();
	$('.var').clone().removeClass('var').appendTo('#newvar');
	$('.validateresult').remove();
//	$('#newvar').html($('#var').html());
//	alert("test");
});

// Clear the variant information for the add variant form when clear button for that section is clicked
//$("#clearvariant").click(function(e) {
function clearVariant() {
//	e.preventDefault();
	$("#hgvs").val("");
	$("#ref").val("");
	$("#gene").val("");
	$("#variant_id").val("");
//});
}

// Clear the phenotype information for the add variant form when clear button for that section is clicked
//$("#clearphenotype").click(function(e) {
function clearPhenotype() {
//	e.preventDefault();
	$("#phenotype").val("");
	$("#phenotype_omim").val("");
//});
}

// Clear the patient information for the add variant form when clear button for that section is clicked
function clearPatient() {
//$("#clearpatient").click(function(e) {
//	e.preventDefault();
	$("#individual_id").val("");
	$("#ethnicity").val("");
//});
}

// Clear the additional information for the add variant form when clear button for that section is clicked
function clearAdditional() {
//$("#clearadditional").click(function(e) {
//	e.preventDefault();
	$("#location_ref").val("");
	$("#start").val("");
	$("#end").val("");
	$("#build").val("");
	$("#comment").val("");
//});
}

// Set up the conditional select option box for pathogenicity type and value in add variant form
$(document).ready(function(){
	if ( $("#pathogenicity_type").val() != 'none' ) {
//		alert("pathogenicity val -> " + $("#pathogenicity_type").val());
		$('#tr_' + $("#pathogenicity_type").val() ).show();
	}
    $('#pathogenicity_type').on('change', function() {         
        $('tr.pathogenicity_value').hide();
        $('#tr_' + $(this).val() ).show();
    });
});

function requestResult(result, string){
	var resultreason = $('#resultreason').val();
	var queryString= "result=" + result + "&string=" + string + "&resultreason=" + resultreason;
	$.ajax({
		type: "POST",
		url: baseurl + "discover/confirmrequest",
		data: queryString,
		dataType: 'html',
//		data: { 'term': term, 'source' : source, 'lab' : lab},
		success: function(data) {
//			$('#waiting').hide(500);
//			$('#resultDisplay').show();
			$('#resultDisplay').empty();
			$('#resultDisplay').append(data);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("status=" + textStatus + ",error=" + errorThrown);
		}
//		complete: function () {
//			alert("complete");
//		}
	});
}

// Function for confirming whether someone wants to be part of a group for a source
function shareRequestResult(result, userid){
	var resultreason = $('#resultreason').val();
//	alert("result -> " + resultreason + " userid -> " + userid);
	var queryString= "result=" + result + "&resultreason=" + resultreason + '&userid=' + userid;
	$.ajax({
		type: "POST",
		url: baseurl + "admin/share_result",
		data: queryString,
		dataType: 'html',
//		data: { 'term': term, 'source' : source, 'lab' : lab},
		success: function(data) {
//			$('#waiting').hide(500);
//			$('#resultDisplay').show();
			$('#resultDisplay').empty();
			$('#resultDisplay').append(data);
			$('#confirmform').show();
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("status=" + textStatus + ",error=" + errorThrown);
		}
//		complete: function () {
//			alert("complete");
//		}
	});
}

// Initialize the jquery switchButton plugin for cron job enabling/disabling in settings 
 $(function() {
	$(".cron_maintenance_slider input").switchButton({
		width: 50,
		height: 20,
		button_width: 25,
		on_label: 'Cron Enabled',
		off_label: 'Cron Disabled'
	});
})

// Change listener for cron job maintenance in settings in admin interface
$(document).ready(function() {
	$('.cronenabled-crondisabled').change(function() {

		if($(this).is(':checked')) {
			status = "enabled";
		}
		else {
			status = "disabled";
		}
//		alert("status " + status);
		$.ajax({url: baseurl + 'admin/cron_control',
			data: {status: status},
			dataType: 'json',
			delay: 200,
			type: 'POST',
			success: function(data) {
//				alert(data);
//				alert("status -> " + data.status);
				if ( data.status != "success" ) {
					alert("Problem with enabling cron: " + data.message);
					$(".cron_maintenance_slider input").switchButton({ checked: false });
				}
//				else {
//					alert(data.message);
//				}
			}
		});
	});
});

// Initialize the jquery switchButton plugin for enabling beacon
 $(function() {
	$(".beacon_slider input").switchButton({
		width: 50,
		height: 20,
		button_width: 25,
		on_label: 'Enabled',
		off_label: 'Disabled'
	});
})

// Change listener for enabling/disabling beacon
$(document).ready(function() {
	$('.beaconenabled-beacondisabled').change(function() {

		if($(this).is(':checked')) {
			status = "1";
		}
		else {
			status = "0";
		}
		var sharing_policy = $(this).attr('id');
//		alert(sharing_policy + " -> status " + status);
		$.ajax({url: baseurl + 'beacon/update_status',
			data: {status: status, sharing_policy: sharing_policy},
			dataType: 'json',
			delay: 200,
			type: 'POST',
			success: function(data) {
//				alert(data);
//				alert("status -> " + data.status);
				if ( data.status != "success" ) {
					alert("Problem with enabling beacon: " + data.message);
					$("." + sharing_policy + " input").switchButton({ checked: false });
				}
//				else {
//					alert(data.message);
//				}
			}
		});
	});
});

// Show the message below if the phenotype column is enabled in the core fields for import templates
//$(document).ready(function(){
//	$("#phenotype:checkbox").change(function(){
//		if($(this).attr("checked")) {
//			alert("In ");
//		}
//		else {
//		}
//	});
//});

// Change listener for the switchButton for cafe variome central source selection (function add_central_source in admin controller)
$(document).ready(function() {
	$('.central-noncentral').change(function() {
		var central_source_name = $(this).attr('id');
		var source_name = $(this).attr('data-source_name');
		var source_description = $(this).attr('data-source_description');
//		alert("central_source_name -> " + central_source_name + " source_name -> " + source_name + " source_description -> " + source_description);
		var status;
		if($(this).is(':checked')) {
//			alert("checked -> " + node_name);
			status = "online";
		}
		else {
//			alert("not checked -> " + node_name);
			status = "offline";
		}
		$.ajax({url: baseurl + 'admin/add_central_source_to_db',
			data: {central_source_name: central_source_name, source_name: source_name, source_description: source_description, status: status},
			dataType: 'json',
			delay: 200,
			type: 'POST',
			success: function() {
//				alert("Successfully changed status to " + status);
			}
		});

	});
});

$(document).ready(function() { 
	$(function() {
		$("#generate_random_password").click(function(e) {
			e.preventDefault();
			var text = "";
			var min = 6;
			var max = 8;
			var ran = Math.floor(Math.random()*(max-min+1)+min);
			var possible = "abcdefghijklmnopqrstuvwxyz0123456789";
			for( var i=0; i < ran; i++ ) {
				text += possible.charAt(Math.floor(Math.random() * possible.length));
			}
			$('#password').val(text);
			$('#password_confirm').val(text);
			$('#random_password').html(text);
		});
	});
});

// Initialize the jquery switchButton plugin for federated source selection 
 $(function() {
	$(".federated_slider input").switchButton({
		width: 50,
		height: 20,
		button_width: 25,
		on_label: 'Online',
		off_label: 'Offline'
	});
});

// Initialize the jquery switchButton plugin for Cafe Variome central source selection 
 $(function() {
	$(".central_slider input").switchButton({
		width: 50,
		height: 20,
		button_width: 25,
		on_label: 'Online',
		off_label: 'Offline'
	});
});

// Change listener for the switchButton for federated source selection (function add_federated_source in admin controller)
$(document).ready(function() {
	$('.federated-nonfederated').change(function() {
		var node_name = $(this).attr('id');
		var source_name = $(this).attr('data-source_name');
		var source_description = $(this).attr('data-source_description');
//		alert("node_name -> " + node_name + " source_name -> " + source_name + " source_description -> " + source_description);
		var status;
		if($(this).is(':checked')) {
//			alert("checked -> " + node_name);
			status = "online";
		}
		else {
//			alert("not checked -> " + node_name);
			status = "offline";
		}
		$.ajax({url: baseurl + 'admin/add_federated_source_to_db',
			data: {node_name: node_name, source_name: source_name, source_description: source_description, status: status},
			dataType: 'json',
			delay: 200,
			type: 'POST',
			success: function() {
//				alert("Successfully changed status to " + status);
			}
		});

	});
});

// Initialize the jquery switchButton plugin for messaging system adding users to new message
 $(function() {
	$(".messages_add_users_slider input").switchButton({
		width: 50,
		height: 20,
		button_width: 25,
		on_label: 'Add',
		off_label: 'Remove'
	});
})

// Change listener for the switchButton adding/removing users to a new message
$(document).ready(function() {
	$('.add-remove').change(function() {
		var username = $(this).attr('data-username');
		var id = $(this).attr('data-id');
		if($(this).is(':checked')) {
			$('#messaging-user-input').tokenInput('add', {id: id, name: username});
		}
		else {
			$('#messaging-user-input').tokenInput('remove', {id: id});
		}
//		$.ajax({url: baseurl + 'admin/add_federated_source_to_db',
//			data: {node_name: node_name, source_name: source_name, source_description: source_description, status: status},
//			dataType: 'json',
//			delay: 200,
//			type: 'POST',
//			success: function() {
////				alert("Successfully changed status to " + status);
//			}
//		});

	});
});

// NO LONGER USED - USING switchButton INSTEAD NOW FOR EVERYTHING
// Initialize the iButton plugin for online offline select list switch
//$(document).ready(function (){
//   $(".online-offline").iButton();
//});

// Initialize the jquery switchButton plugin for on off select list switch in the settings tab
 $(function() {
	$(".settings_slider input").switchButton({
		width: 50,
		height: 20,
		button_width: 25
	});
})

// Initialize the jquery switchButton plugin for included excluded select list switch in the set core fields section of the settings interface
 $(function() {
	$(".core_fields_slider input").switchButton({
		on_label: 'Include',
		off_label: 'Exclude',
		width: 50,
		height: 20,
		button_width: 25
	});
})

// Initialize the jquery switchButton plugin for online offline select list switch in the sources admin page
 $(function() {
	$(".source_status_slider input").switchButton({
		on_label: 'Online',
		off_label: 'Offline',
		width: 50,
		height: 20,
		button_width: 25
	});
})

// Initialize the jquery switchButton plugin for included excluded select list switch in the set core fields section of the settings interface
 $(function() {
	$(".is_admin_slider input").switchButton({
		on_label: 'Admin',
		off_label: 'Not Admin',
		width: 50,
		height: 20,
		button_width: 25
	});
})

// Change listener for online offline select list switch in sources table interface
$(document).ready(function() {
	$('.online-offline').change(function() {
		var source_id = $(this).attr('id');
//		alert("sourceid -> " + source_id);
		var status;
		if($(this).is(':checked')) {
//			alert("checked -> " + source_id);
			status = "online";
		}
		else {
//			alert("not checked -> " + source_id);
			status = "offline";
		}
		$.ajax({url: baseurl + 'admin/change_source_status',
			data: {status: status, source_id: source_id},
			dataType: 'json',
			delay: 200,
			type: 'POST',
			success: function() {
//				alert("Successfully changed status to " + status);
			}
		});
	});
});

$(document).ready( function(){
    $(".cb-enable").click(function(){
        var parent = $(this).parents('.switch');
        $('.cb-disable',parent).removeClass('selected');
        $(this).addClass('selected');
        $('.checkbox',parent).attr('checked', true);
    });
    $(".cb-disable").click(function(){
        var parent = $(this).parents('.switch');
        $('.cb-enable',parent).removeClass('selected');
        $(this).addClass('selected');
        $('.checkbox',parent).attr('checked', false);
    });
});


// Delete source ajax function and delete from sources table (not used anymore - reverted to non-ajax post to do delete and a page refresh)
$(document).ready(function() { 
	$(function() {
		$(".deletesource").click(function(e) {
			
			e.preventDefault();
//			var bid = this.id; // button ID 
			var source_id = $(this).closest('tr').attr('id');
//			alert("source id -> " + source_id);
			var oTable = $('#example').dataTable();
			var row = $(this).closest("tr").get(0);
//			alert("row -> " + row);
			var queryString= "source_id=" + source_id;
//			alert("base " + baseurl);
			$.ajax({
				type: "POST",
				url: baseurl + "admin/delete_source",
				data: queryString,
//				data: { 'sourceid': source_id },
				dataType: 'html',
				success: function(data) {
//					alert("deleted source");
					oTable.fnDeleteRow(oTable.fnGetPosition(row));
					bootbox.dialog("The source was successfully deleted",
                {
                    "OK": function() {}
                }, {
                    "backdrop": false,
					"animate": false
                });
//					$('#waiting').hide(500);
//					$('#sourceDisplay').show();
//					$('#sourceDisplay').empty();
////					$('#sourceDisplay').append(data);
//					$('#sourceDisplay').append("Deleted source");
				},
				error: function(httpRequest, textStatus, errorThrown) { 
					alert("status=" + textStatus + ",error=" + errorThrown);
				}
//				complete: function () {
//					alert("complete");
//				}
			});
		});
	});
});

// Main search ajax for discovery interface
$(function() {
	$("#search").click(function(e) {
		
		e.preventDefault();
		$('#mutationDisplay').hide();
		$('#waiting').show(500);
		var network =  $('#networks :selected').attr('value');
//		alert("network -> " + network);
//		var source =  $('#sources :selected').attr('value');
		var source = "all";
//		alert("source -> " + source);
		var lab =  $('#laboratories :selected').text();
		var term = document.getElementById("term").value;
		var mutalyzer_check = $("#mutalyzer_check").is(':checked');
//		alert("mut -> " + mutalyzer_check);
		var grouping_type = $('#grouping_type').val();
//		alert("term -> " + term);
//		var pathname = window.location.pathname;
//		alert("pathname -> " + pathname + " term -> " + term);
//		window.location = 'http://localhost/cafevariome/discover/index/testfefew/';
		// http://stackoverflow.com/questions/5422265/how-can-i-pre-populate-html-form-input-fields-from-url-parameters

//		var queryString= "term=" + term + "&source=" + source + "&lab=" + lab + "&mutalyzer_check=" + mutalyzer_check + "&grouping_type=" + grouping_type;
//		alert("query -> " + queryString);
		$.ajax({
			type: "POST",
			data: { network: network, term: term, source : source, lab : lab, mutalyzer_check: mutalyzer_check, grouping_type: grouping_type },
//			data: queryString,
			url: baseurl + "discover/variantcount",
			dataType: 'html',
			success: function(data) {
//				alert("got data");
//				alert("data -> " + data);
				console.log('data -> ' + data);
				$('#waiting').hide(500);
				$('#mutationDisplay').show();
				$('#mutationDisplay').empty();
				$('#mutationDisplay').append(data);
//				return false;
				
			},
			error: function(httpRequest, textStatus, errorThrown) { 
//				alert("status=" + textStatus + ",error=" + errorThrown);
				$('#waiting').hide(500);
				alert("Please specify a search term.");
			}
//			complete: function () {
//				alert("complete");
//			}
		});
	});
});


// Search button clicked in the navbar search modal
$(function() {
	$("#navbar-search").click(function(e) {
		e.preventDefault();
		var term = document.getElementById("navbar_term").value;
		var url = baseurl + "discover/variantcount/" + term + "/all/html";
		window.location.href= url;
	});
});


$(document).ready(function(){
	$('#openGenesModal').click( function() {
		$('#genesModal').modal({backdrop: false});
		$('#genesModal').modal('show');
		$( "#geneContent" ).empty();
		$('#geneWaiting').show(500);
		$.ajax({url: baseurl + 'discover/get_genes_list',
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function(data) {
				$('#geneWaiting').hide(500);
//				alert("data -> " + data);
//				$( "#geneContent" ).empty();
				$( "#geneContent" ).append(data);
			}
		});

	});
});

$(document).ready(function(){
	$('#openReferenceModal').click( function() {
		$('#referenceModal').modal({backdrop: false});
		$('#referenceModal').modal('show');
		$( "#referenceContent" ).empty();
		$('#refWaiting').show(500);
		$.ajax({url: baseurl + 'discover/get_reference_list',
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function(data) {
				$('#refWaiting').hide(500);
//				alert("data -> " + data);
//				$( "#referenceContent" ).empty();
				$( "#referenceContent" ).append(data);
			}
		});

	});
});

// Change colour of header in admin preferences (TODO: not sure why I used ajax POST here - no need as can just post normally)
$(document).ready(function(){
	// POST for when colours are selected
	$('#pick_button').click( function() {
//		alert("from -> " + $('input.simple_color_from')[0].value + " to -> " + $('input.simple_color_to')[0].value);
		$.ajax({url: baseurl + 'admin/header_colour',
			data: {header_colour_from: $('#header_colour_from').val(), header_colour_to: $('#header_colour_to').val(), navbar_selected_tab_colour: $('#navbar_selected_tab_colour').val()},
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function() {
				window.location = baseurl + "admin/preferences";
			}
		});
	});
});

//$(document).ready(function(){
//	$('.simple_color').simpleColor();
//
//	$('.simple_color_from').simpleColor({
//			cellWidth: 12,
//			cellHeight: 12,
//			border: '1px solid #333333',
//			buttonClass: 'btn btn-small',
//			displayColorCode: true
//	});
//
//	$('.simple_color_to').simpleColor({
//			cellWidth: 12,
//			cellHeight: 12,
//			border: '1px solid #333333',
//			buttonClass: 'btn btn-small',
//			displayColorCode: true
//	});
//	// POST for when colours are selected
//	$('#pick_button').click( function() {
////		alert("from -> " + $('input.simple_color_from')[0].value + " to -> " + $('input.simple_color_to')[0].value);
//		$.ajax({url: baseurl + 'admin/header_colour',
//			data: {header_colour_from: $('input.simple_color_from')[0].value, header_colour_to: $('input.simple_color_to')[0].value},
//			dataType: 'html',
//			delay: 200,
//			type: 'POST',
//			success: function() {
//				window.location = baseurl + "admin/preferences";
//			}
//		});
//	});
//});

// Reset the header colours back to the default setting
$(document).ready(function(){
	$('#colour_default').click( function() {
//		alert("reset to default");
		$.ajax({url: baseurl + 'admin/header_colour',
			data: {header_colour_from: "#6c737e", header_colour_to: "#afb3ba"},
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function() {
				window.location = baseurl + "admin/preferences";
			}
		});
	});
});

// When a thumbnail (either background or logo) is clicked in the preferences then also select the radio button that the thumbnail is associated with
$(document).ready(function() {
	$('.background_thumbnail').click(function(e){
		e.preventDefault();
		var id = $(this).next().attr('id');
		$('#' + id).attr('checked', true);
		
		var value = $(this).next().attr('value');
//		alert("value -> " + value);
		$.ajax({url: baseurl + 'admin/background',
			data: {background: value},
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function() {
				window.location = baseurl + "admin/preferences";
			}
		});
	});
});

$(document).ready(function() {
	$('.background_thumbnail_radio').click(function(e){
		var value = $(this).attr('value');
//		alert("value -> " + value);
		$.ajax({url: baseurl + 'admin/background',
			data: {background: value},
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function() {
				window.location = baseurl + "admin/preferences";
			}
		});
	});
});


$(document).ready(function() {
	$('.logo_thumbnail').click(function(e){
		e.preventDefault();
		var id = $(this).next().attr('id');
		$('#' + id).attr('checked', true);
		
		var value = $(this).next().attr('value');
//		alert("value -> " + value);
		$.ajax({url: baseurl + 'admin/logo',
			data: {logo: value},
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function() {
				window.location = baseurl + "admin/preferences";
			}
		});
	});
});

$(document).ready(function() {
	$('.logo_thumbnail_radio').click(function(e){
		var value = $(this).attr('value');
//		alert("value -> " + value);
		$.ajax({url: baseurl + 'admin/logo',
			data: {logo: value},
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function() {
				window.location = baseurl + "admin/preferences";
			}
		});
	});
});


//$( ".thumbnail" ).dblclick(function() {
//  alert( "Handler for .dblclick() called." );
//});

// Change the theme in the preferences
$(document).ready(function(){
	$('.change_theme').click( function(e) {
		var theme = e.target.id;
//		alert("change theme -> " + e.target.id);
		$.ajax({url: baseurl + 'admin/change_theme',
			data: {theme: theme},
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function() {
				window.location = baseurl + "admin/preferences";
			}
		});
	});
});

// JQuery cluetip for displaying variant page preview in records html table
$(document).ready(function() {
  $('a.basic').cluetip({
       height: 'auto',
       width: '800px',
       arrows: true,
       showTitle: false
  });
});

// Alamut functions to show variant, documentation at:
// http://www.interactive-biosoftware.com/alamut/doc/2.2/programmatic-access.html
// http://www.interactive-biosoftware.com/alamut/Alamut-HTTP.html
function httpGetFocusOn(focus) {
	var xmlHttp = null;
	xmlHttp = new XMLHttpRequest();
	if (xmlHttp) {
		xmlHttp.open( "GET", "http://localhost:10000/show?request="+focus, true );
		xmlHttp.send(null);
	}
}

function httpGetVersion() {
	var xmlHttp = null;
	xmlHttp = new XMLHttpRequest();
	if (xmlHttp) {
		xmlHttp.open( "GET", "http://localhost:10000/version", true );
		xmlHttp.send(null);
		xmlHttp.onreadystatechange=function() {
		if (xmlHttp.readyState==4) {
			alert("Response from Alamut:\n"+xmlHttp.response);
			}
		}
	}
}

window.setTimeout(function() {
    $("#success-alert").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
//		location.reload();
    });
}, 5000);

// Reset the settings back to the default
$(document).ready(function(){
	$('#settings_default').click( function() {
//		alert("reset to default");
		$.ajax({url: baseurl + 'admin/settings_default',
			data: {header_colour_from: "#afb3ba", header_colour_to: "#6c737e"},
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function(data) {
//				alert("test -> " + data);
				window.location = baseurl + "admin/settings";
			}
		});
	});
});


// Initialize the Twitter Bootstrap carousel - used on main home page and features page
$(document).ready(function(){
    $('.carousel').carousel({
		interval: 6500
	 });
  });

// Function to delete multiple selected messages from messaging interface
function deleteSelectedMessages() {
	var selected = new Array();
	$("input:checkbox").each(function(){
		var $this = $(this);
		if($this.is(":checked")){
			if ($this.attr('id')) { // Means that the checkbox in the table header is ignored as it doesn't contain an ID
				var message_id_str = $this.attr("id");
				var message_id = message_id_str.split("_").pop();
				selected.push(message_id);
			}
		}
	});
//        console.log(selected);
//        return;
	$.ajax({
		url: authurl + '/auth_messages/delete_selected_messages',
//		contentType: 'application/json',
		data: {messages: JSON.stringify(selected), user_id : $("#user_id").attr("value")},
		dataType: 'html',
//		delay: 200,
		type: 'post',
		success: function(data) {
//			alert(data);
//			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem deleting the messages");
		}
	});
}

function deleteSelectedMessagesInbox() {
	var selected = new Array();
	$("input:checkbox").each(function(){
		var $this = $(this);
		if($this.is(":checked")){
			if ($this.attr('id')) { // Means that the checkbox in the table header is ignored as it doesn't contain an ID
				var message_id_str = $this.attr("id");
				var message_id = message_id_str.split("_").pop();
				selected.push(message_id);
			}
		}
	});
	$.ajax({
		url: authurl + '/auth_messages/delete_selected_messages_inbox',
//		contentType: 'application/json',
		data: {messages: JSON.stringify(selected), user_id : $("#user_id").attr("value")},
		dataType: 'html',
//		delay: 200,
		type: 'post',
		success: function(data) {
//			alert(data);
			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem deleting the messages");
		}
	});
}

function deleteSelectedMessagesOutbox() {
	var selected = new Array();
	$("input:checkbox").each(function(){
		var $this = $(this);
		if($this.is(":checked")){
			if ($this.attr('id')) { // Means that the checkbox in the table header is ignored as it doesn't contain an ID
				var message_id_str = $this.attr("id");
				var message_id = message_id_str.split("_").pop();
				selected.push(message_id);
			}
		}
	});
//        console.log(selected);
//        return;
	$.ajax({
		url: authurl + '/auth_messages/delete_selected_messages_outbox',
//		contentType: 'application/json',
		data: {messages: JSON.stringify(selected), user_id : $("#user_id").attr("value")},
		dataType: 'html',
//		delay: 200,
		type: 'post',
		success: function(data) {
//			alert(data);
			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem deleting the messages");
		}
	});
}

function clearSearchBox() {
	$("#term").val('');
}

// Function to mark selected messages as read in messaging interface
function markSelectedMessagesAsRead() {
	var selected = new Array();
	$("input:checkbox").each(function(){
		var $this = $(this);
		if($this.is(":checked")){
			var message_id_str = $this.attr("id");
			var message_id = message_id_str.split("_").pop();
			selected.push(message_id);
		}
	});
	$.ajax({
		url: authurl + '/auth_messages/mark_selected_messages_as_read',
//		contentType: 'application/json',
		data: {messages: JSON.stringify(selected), user_id : $("#user_id").attr("value")},
		dataType: 'html',
//		delay: 200,
		type: 'post',
		success: function(data) {
//			alert(data);
			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem marking the messages as read");
		}
	});
}

// Function to add new ontology
function addNewOntology() {
	var singleValue=$("#ontology_list option:selected").val();
        $.ajax({
		url: baseurl + 'admin/add_new_ontology',
//		contentType: 'application/json',
		data: {newont: singleValue},
		dataType: 'html',
//		delay: 200,
		type: 'post',
		success: function(data) {
//			alert(data);
			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem adding the selected ontology");
		}
	});
}

// Function to add new ontologies (multi select version)
function addNewOntologies() {
	
	// Get all the ontology sources that are selected and store as json array
	if($("#ontology_list").length != 0) {
		var newonts = $("#ontology_list :selected").map(function() {
		var arr = [];
			arr.push([$(this).val(), $(this).text()]);
			return arr;
		}).get();
		
		
        $.ajax({
			url: baseurl + 'admin/add_new_ontologies',
			data: {newonts: newonts},
			dataType: 'html',
			type: 'post',
			success: function(data) {
				window.location.reload(true);
			},
			error: function(httpRequest, textStatus, errorThrown) { 
				alert("Sorry, there was a problem adding the selected ontology");
			}
		});
	}
	else {
		alert("No ontologies were selected");
	}
	
//	var singleValue=$("#ontology_list option:selected").val();
//        $.ajax({
//		url: baseurl + 'admin/add_new_ontology',
//		data: {newont: singleValue},
//		dataType: 'html',
//		type: 'post',
//		success: function(data) {
//			window.location.reload(true);
//		},
//		error: function(httpRequest, textStatus, errorThrown) { 
//			alert("Sorry, there was a problem adding the selected ontology");
//		}
//	});
}


// Function to delete an ontology
function deleteOntology($ontAbb) {
	//var singleValue=$("#ontology_list option:selected").val();
        $.ajax({
		url: baseurl + 'admin/delete_ontology',
//		contentType: 'application/json',
		data: {ont2del: $ontAbb},
		dataType: 'html',
//		delay: 200,
		type: 'post',
		success: function(data) {
//			alert(data);
			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem removing the selected ontology");
		}
	});
}


// Function to delete a local list term
function deleteTerm($termId) {
	//var singleValue=$("#ontology_list option:selected").val();
        $.ajax({
		url: baseurl + 'admin/delete_ll_term',
//		contentType: 'application/json',
		data: {term2del: $termId},
		dataType: 'html',
//		delay: 200,
		type: 'post',
		success: function(data) {
//			alert(data);
			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem removing the selected term");
		}
	});
}


// Select all the checkboxes function toggle for records in the curate records admin table 
 $(document).ready(function() {
	// add multiple select / deselect functionality
	$(".selectallmessages").click(function() {
//		alert("table -> " + $(this).closest('table').attr("id"));
		var table_id = $(this).closest('table').attr("id");
		$('#' + table_id + ' .case').attr('checked', this.checked);
	});

	// if all checkbox are selected, check the selectallmessages checkbox  also        
	$(".case").click(function() {
		if ($(".case").length == $(".case:checked").length) {
			$(".selectallmessages").attr("checked", "checked");
		}
		else {
			$(".selectallmessages").removeAttr("checked");
		}
	});
});

function shareVariantsByEmail(shareID) {
	var email = document.getElementById("email" + shareID).value;
	// Check its a valid email address, return false if not
	var valid_email = isEmail(email);
	if ( ! valid_email ) {
		alert("The email address is not valid.")
//		$('#email'  + shareID).val("");
//		$('#shareModal' + shareID).modal('hide');
		return false;
	}
	var selected_group =  $('#groups' + shareID + ' :selected').attr('value');
//	alert("shareDiv ID -> " + shareID + " email -> " + email + " group -> " + selected_group);
	$.ajax({
		url: baseurl + 'admin/invite_to_share_source',
//		contentType: 'application/json',
		data: {email: email, selected_group: selected_group},
		dataType: 'html',
//		delay: 200,
		type: 'post',
		success: function(data) {
			alert(data);
//			$('#email'  + shareID).empty();
			$('#email'  + shareID).val("");
			$('#shareModal' + shareID).modal('hide');
//			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem when trying to share this source");
			$('#shareModal' + shareID).modal('hide');
		}
	});
}

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

// Function to delete multiple selected messages from messaging interface
function cloneSource() {
	var clone_source =  $('#clone_source :selected').attr('value');
	var clone_name = document.getElementById("clone_name").value;
	// TODO: Validate that clone_name doesn't contain any spaces and only alpha numeric characters';
	var regex = /\s+/;
	if ( regex.test(clone_name) ) {
		alert("Sorry, the destination source name must not contain spaces");
		return false;
	}
	var clone_name = clone_name.toLowerCase();
	var clone_description = document.getElementById("clone_description").value;
//	alert("-> " + clone_source + " -> " + clone_name + " -> " + clone_description);
	$.ajax({
		url: baseurl + 'admin/clone_source',
//		contentType: 'application/json',
		data: {clone_source: clone_source, clone_name: clone_name, clone_description: clone_description},
		dataType: 'html',
		type: 'post',
		success: function(data) {
			alert(data);
			window.location.reload(true);
		},
		error: function(httpRequest, textStatus, errorThrown) { 
			alert("Sorry, there was a problem cloning the source");
		}
	});
}

function join_network() {
    $callAjax = true;
    $('form[name="joinNetwork"]').submit(function(e) {
        e.preventDefault();
//        console.log($("#networks option:selected").text());
        $.ajax({url: baseurl + 'networks/process_network_join_request/',
		data: $(this).serialize(),
		dataType: 'json',
		delay: 200,
		type: 'POST',
		success: function(data) {
			if ( data.error ) {
//				alert(data.error);
				window.location.reload(true);
			}
			else if (data.success) {
//				alert("Successfully joined network");
				alert("Successfully requested to join network");
				window.location = baseurl + "networks";
//				window.location.reload(true);
			}
			else {
//				window.location = baseurl + "admin/data_requests";
				window.location.reload(true);
			}
		}
	});
    });
}

//$(function() {
//    $('#name').keyup(function(e) {
////        alert($(this).val());
//        e.preventDefault();
//        $.ajax({url: baseurl + 'federated_settings/process_network_create_request/',
//		data: $(this).serialize(),
//		dataType: 'json',
//		delay: 200,
//		type: 'POST',
//		success: function(data) {
//			if (data.success) {
////				alert(data.success);
//                            $("#errText").removeClass('hide');
//                            $("#errText").text(data.success);
//			} else {
//                            $("#errText").addClass('hide');
//                            $("#errText").text("");
//                        }
//		}
//	});
//    });
//});

//function create_network() {
//	$('form[name="createNetwork"]').submit(function(e) {
////        console.log($("#networks option:selected").text());
//        e.preventDefault();
//        
//        $.ajax({url: baseurl + 'federated_settings/validate_create_network/',
//		data: $(this).serialize(),
//		dataType: 'json',
//		delay: 200,
//		type: 'POST',
//		success: function(data) {
//                    if (data.error) {
//                            $("#createNetworkError").removeClass('hide');
//                            $("#createNetworkError").text(data.error);
//                    } else if (data.ok) {
//                            alert(data.ok);
//                            window.location = baseurl + "federated_settings";
//                    }
//		}
//	});
//    });
//}

function register_user() {
        $callAjax = true;
	$('form[name="registerUser"]').submit(function(e) {
        e.preventDefault();
        $postData = $(this).serialize();
        $.ajax({url: baseurl + 'auth_federated/validate_signup/',
		data: $postData,
		dataType: 'json',
		delay: 200,
		type: 'POST',
                async: 'false',
		success: function(data) {
                    if (data.error) {
                            $("#signupError").removeClass('hide');
                            $("#signupError").html(data.error);
                    } else if (data.success) {
                        if($callAjax)
                        {$.ajax({url: authurl + '/auth_accounts/register/',
                                data: $postData,
                                dataType: 'json',
                                delay: 200,
                                type: 'POST',
                                success: function(result) {
                                    if (result.error) {
                                            $("#signupError").removeClass('hide');
                                            $("#signupError").text(result.error);
                                    } else if (result.success) {
                                        if(result.success === "no_email")
                                            window.location = baseurl + "auth_federated/signup_success";
                                        else
                                            window.location = baseurl + "auth_federated/signup_success/" + result.success ;
                                    }
                                }
                        }); $callAjax = false;
                        }
                    }
		}
	});
    });
}

// login user
$(document).ready(function(){
    $("#loginUser").click(function(e){
        e.preventDefault();
        $.ajax({url: 'https://auth.cafevariome.org/',
		complete: function(xhr, status) {
                    if (status === "success") {
                        window.location.href = baseurl + "auth_federated/login";
                    } else {
                        window.location.href = baseurl + "auth/login";
                    }
		}
	});
    });
});

function login_user() {
        $callAjax = true;
	$('form[name="loginUser"]').submit(function(e) {
        e.preventDefault();
        $postData = $(this).serialize();
        $.ajax({url: baseurl + 'auth_federated/validate_login/',
		data: $postData,
		dataType: 'json',
		delay: 200,
		type: 'POST',
                async: 'false',
		success: function(data) {
                    if (data.error) {
                            $("#loginError").removeClass('hide');
                            $("#loginError").html(data.error);
                    } else if (data.success) {
                        if($callAjax)
                        {$.ajax({url: authurl + '/auth_accounts/login/',
                                data: $postData,
                                dataType: 'json',
                                delay: 200,
                                type: 'POST',
                                success: function(result) {
                                    if (result.error) {
//                                            alert(result.error);
                                            $("#loginError").removeClass('hide');
                                            $("#loginError").text(result.error);
                                    } else if (result.success) {
                                        $.ajax({url: baseurl + 'auth_federated/login_success/',
                                                data: result,
                                                dataType: 'json',
                                                delay: 200,
                                                type: 'POST',
                                                success: function(data) {
                                                    if (data.success) {
//                                                            alert(data.success);
                                                            window.location = baseurl;
                                                    }
                                                }
                                        });
                                    }
                                }
                        }); $callAjax = false;
                        }
                    }
		}
	});
    });
}

function login_forgot_password() {
    $callAjax = true;
    $('form[name="forgot_password"]').submit(function(e) {
        e.preventDefault();
        $postData = $(this).serialize();
        
        $.ajax({url: baseurl + 'auth_federated/validate_forgot_password/',
		data: $postData,
		dataType: 'json',
		delay: 200,
		type: 'POST',
		success: function(data) {
                    if (data.error) {
                            $("#forgotPasswordError").removeClass('hide');
                            $("#forgotPasswordError").html(data.error);
                    } else if (data.success) {
                            $.ajax({url: authurl + '/auth_accounts/forgot_password/',
                            data: $postData,
                            dataType: 'json',
                            delay: 200,
                            type: 'POST',
                            success: function(data) {
                                if (data.error) {
                                        $("#forgotPasswordError").removeClass('hide');
                                        $("#forgotPasswordError").text(data.error);
                                } else if (data.success) {
//                                        alert(data.success);
//                                        alert("Check your email to change your password and follow the instructions.");
                                        window.location = baseurl + "auth_federated/success_forgot_password/" + data.success;
                                }
                            }
                        });
                    }
		}
	});
    });
}

function create_user() {
    $callAjax = true;
    $('form[name="createUser"]').submit(function(e) {
        e.preventDefault();
        $postData = $(this).serialize();
        $.ajax({url: baseurl + 'auth_federated/validate_create_user/',
		data: $postData,
		dataType: 'json',
		delay: 200,
		type: 'POST',
		success: function(data) {
                    if (data.error) {
                            $("#createUserError").removeClass('hide');
                            $("#createUserError").html(data.error);
                    } else if (data.success) {
                        if($callAjax)
                        {$.ajax({url: authurl + '/auth_accounts/create_user/',
                                data: $postData,
                                dataType: 'json',
                                delay: 200,
                                type: 'POST',
                                success: function(result) {
                                    if (result.error) {
                                        alert(result.error);
                                            $("#createUserError").removeClass('hide');
                                            $("#createUserError").text(result.error);
                                    } else if (result.success) {
                                            alert(result.success);
                                            window.location = baseurl + "auth_federated/users";
                                    }
                                }
                        }); $callAjax = false;
                        }
                    }
		}
	});
    });
}

function edit_user() {
    $callAjax = true;
    $('form[name="editUser"]').submit(function(e) {
        e.preventDefault();
        $postData = $(this).serialize();
        $.ajax({url: baseurl + 'auth_federated/validate_edit_user/',
		data: $postData,
		dataType: 'json',
		delay: 200,
		type: 'POST',
		success: function(data) {
                    if (data.error) {
                            $("#editUserError").removeClass('hide');
                            $("#editUserError").html(data.error);
                    } else if (data.success) {
                        if($callAjax)
                        {$.ajax({url: authurl + '/auth_accounts/edit_user/',
                                data: $postData,
                                dataType: 'json',
                                delay: 200,
                                type: 'POST',
                                success: function(result) {
                                    if (result.error) {
                                            $("#editUserError").removeClass('hide');
                                            $("#editUserError").text(result.error);
                                    } else if (result.success) {
//                                            console.log(result.success);
                                            window.location = baseurl + "auth_federated/users";
                                    }
                                }
                        }); $callAjax = false;
                        }
                    }
		}
	});
    });
}

function edit_user_network_groups() {
	$callAjax = true;
    $('form[name="editUser"]').submit(function(e) {
        e.preventDefault();
        $postData = $(this).serialize();
		if($callAjax)
		$.ajax({
			url: authurl + '/auth_accounts/edit_user_network_groups/',
            data: $postData,
            dataType: 'json',
            delay: 200,
            type: 'POST',
            success: function(result) {
				if (result.error) {
					$("#editUserError").removeClass('hide');
					$("#editUserError").text(result.error);
                } else if (result.success) {
//					console.log(result.success);
                    window.location = baseurl + "auth_federated/users";
                }
            }
        });$callAjax = false;
	});

}

function activate_user() {
    $callAjax = true;
    $('form[name="activateUser"]').submit(function(e) {
        e.preventDefault();
        $postData = $(this).serialize();
        $.ajax({url: baseurl + 'auth_federated/validate_activate/',
		data: $postData,
		dataType: 'json',
		delay: 200,
		type: 'POST',
		
		success: function(data) {
                    if (data.error) {
                        if(data.error === "no") {
                            window.location = baseurl + "auth_federated/users";
                        } else {
                            $("#activateUserError").removeClass('hide');
                            $("#activateUserError").html(data.error);
                        }
                    } else if (data.success) {
                        if($callAjax)
                        {$.ajax({url: authurl + '/auth_accounts/activate_user/',
                                data: $postData,
                                dataType: 'json',
                                delay: 200,
                                type: 'POST',
//								headers: {"Token": "test-value"},
                                success: function(result) {
                                    if (result.error) {
                                            $("#activateUserError").removeClass('hide');
                                            $("#activateUserError").text(result.error);
                                    } else if (result.success) {
//                                            alert(result.success);
                                            window.location = baseurl + "auth_federated/users";
                                    }
                                }
                        }); $callAjax = false;
                        }
                    }
		}
	});
    });
}

function deactivate_user() {
    $callAjax = true;
    $('form[name="deactivateUser"]').submit(function(e) {
        e.preventDefault();
        $postData = $(this).serialize();
        $.ajax({url: baseurl + 'auth_federated/validate_deactivate/',
		data: $postData,
		dataType: 'json',
		delay: 200,
		type: 'POST',
		success: function(data) {
                    if (data.error) {
                        if(data.error === "no") {
                            window.location = baseurl + "auth_federated/users";
                        } else {
                            $("#deactivateUserError").removeClass('hide');
                            $("#deactivateUserError").html(data.error);
                        }
                    } else if (data.success) {
                        if($callAjax)
                        {$.ajax({url: authurl + '/auth_accounts/deactivate_user/',
                                data: $postData,
                                dataType: 'json',
                                delay: 200,
                                type: 'POST',
                                success: function(result) {
                                    if (result.error) {
                                            $("#deactivateUserError").removeClass('hide');
                                            $("#deactivateUserError").text(result.error);
                                    } else if (result.success) {
//                                            alert(result.success);
                                            window.location = baseurl + "auth_federated/users";
                                    }
                                }
                        }); $callAjax = false;
                        }
                    }
		}
	});
    });
}

function delete_user() {
    $callAjax = true;
    $('form[name="deleteUser"]').submit(function(e) {
        e.preventDefault();
        $postData = $(this).serialize();
        $.ajax({url: baseurl + 'auth_federated/validate_delete/',
		data: $postData,
		dataType: 'json',
		delay: 200,
		type: 'POST',
		success: function(data) {
                    if (data.error) {
                        if(data.error === "no") {
                            window.location = baseurl + "auth_federated/users";
                        } else {
                            $("#deleteError").removeClass('hide');
                            $("#deleteError").html(data.error);
                        }
                    } else if (data.success) {
                        if($callAjax)
                        {$.ajax({url: authurl + '/auth_accounts/delete_user/',
                                data: $postData,
                                dataType: 'json',
                                delay: 200,
                                type: 'POST',
                                success: function(result) {
                                    if (result.error) {
                                            $("#deleteError").removeClass('hide');
                                            $("#deleteError").text(result.error);
                                    } else if (result.success) {
//                                            alert(result.success);
                                            window.location = baseurl + "auth_federated/users";
                                    }
                                }
                        }); $callAjax = false;
                        }
                    }
		}
	});
    });
}

// activate user
//$(document).ready(function(){
//    $(".activateUser").click(function(e){
//        e.preventDefault();
//        $.ajax({url: authurl + '/auth_accounts/activate_user/',
//		data: {'id': $(this).attr('id')},
//		dataType: 'json',
//		delay: 200,
//		type: 'POST',
//		success: function(data) {
//                    if (data.error) {
//                        alert("unable to activate user. Try again.")
//                    } else if (data.success) {
//                        window.location = baseurl + "auth_federated/users";
//                    }
//		},
//	});
//    });
//});

function edit_user_profile() {
    $callAjax = true;
    $('form[name="editUserProfile"]').submit(function(e) {
        e.preventDefault();
        $postData = $(this).serialize();
        $.ajax({url: baseurl + 'auth_federated/validate_user_edit_profile/',
		data: $postData,
		dataType: 'json',
		delay: 200,
		type: 'POST',
		success: function(data) {
                    if (data.error) {
                            $("#editUserProfileError").removeClass('hide');
                            $("#editUserProfileError").html(data.error);
                    } else if (data.success) {
                        if($callAjax)
                        {$.ajax({url: authurl + '/auth_accounts/edit_user/TRUE',
                                data: $postData,
                                dataType: 'json',
                                delay: 200,
                                type: 'POST',
                                success: function(result) {
                                    if (result.error) {
                                            $("#editUserProfileError").removeClass('hide');
                                            $("#editUserProfileError").text(result.error);
                                    } else if (result.success) {
//                                            alert(result.success);
                                            window.location = baseurl + "auth_federated/user_profile/" + $('input[name=id]').val();
                                    }
                                }
                        }); $callAjax = false;
                        }
                    }
		}
	});
    });
}

function send_message() {
    $callAjax = true;
    $('form[name="sendMessage"]').submit(function(e) {
        e.preventDefault();
        $postData = $(this).serialize();
        $.ajax({url: baseurl + 'messages/validate_send/',
		data: $postData,
		dataType: 'json',
		delay: 200,
		type: 'POST',
		success: function(data) {
                    if (data.error) {
                            $("#sendMessage").removeClass('hide');
                            $("#sendMessage").html(data.error);
                    } else if (data.success) {
//                        alert(data.success);
                        if($callAjax)
                        {$.ajax({url: authurl + '/auth_messages/send_email/',
                                data: $postData,
                                dataType: 'json',
                                delay: 200,
                                type: 'POST',
                                success: function(result) {
                                    if (result.error) {
                                            $("#sendMessage").removeClass('hide');
                                            $("#sendMessage").text(result.error);
                                    } else if (result.success) {
//                                            console(result.success);
											 window.location = baseurl + "messages";
//                                            window.location = baseurl + "messages/index/" + result.unread_count;
                                    }
                                }
                        }); $callAjax = false;
                        }
                    }
		}
	});
    });
}