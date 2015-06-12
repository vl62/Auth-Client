(function($) {
    $.fn.queryBuilder = function(options) {
        // Establish default settings
        var settings = $.extend({
			type: ['simple', 'grouped'],
            fields: ['gene', 'hgvs', 'phenotype'],
            logic: ['AND', 'OR', 'NOT'],
            conditions: ['=', '<>', '<', '<=', '>', '>=', '*', '~', '^'],
            syntax: ['ElasticSearch', 'mySQL', 'MongoDB'],
            style: ['bootstrap2', 'bootstrap3', 'standard'],
			autocomplete: ['on', 'off'],
			number_blocks_per_field : 2
        }, options);
		
		// Logic select box that separates groups
//        var logic = '<select class="input-small logic" name="logic">';
//        $.each(settings.logic, function(i, l) {
//            logic += '<option value="' + l + '">' + l + '</option>';
//        });
//        logic += '</select>';
		var l_count = 0;
		var logic = '<div class="btn-group btn-logic-group" >';
		$.each(settings.logic, function(i, l) {
			l_count++;
			if ( l_count == 1 ) {
				logic += '<a class="btn-logic btn btn-large ' + 'btn-primary' + '">' + l + '</a>';
			}
			else {
				logic += '<a class="btn-logic btn btn-large">' + l + '</a>';
			}
		});
        logic += '</div>';
		logic += '<br />'

		var statement = '';

		if ( settings.type == 'grouped' ) {
			var total_num_groups = Object.keys(settings.fields_grouped).length;
			var group_count = 0;
//			statement += '<div class="row-fluid"><div class="span8 offset2 pagination-centered"><div class="well"><button class="btn" id="create_single"><i class="icon-plus"></i> Add Single</button>&nbsp;&nbsp;<button class="btn" id="create_group"><i class="icon-plus"></i> Add Group</button></div></div></div>';
			$.each(settings.fields_grouped, function(group_name, group) {
				group_count++;
//				alert(group_count + " of " + total_num_groups);
				statement += '<br /><div class="boxed ' + group_name + '" id="' + group_name + '">';
				statement += '<div class="row-fluid"><div class="span2 pagination-left group ' + group_name + '"><h3>' + group_name + '</h3><hr></div><p></p></div>';
				var total_num_fields_in_group = Object.keys(group).length;
				var fields_in_group_count = 0;
				$.each(group, function(key, value) {
					fields_in_group_count++;
//					alert(fields_in_group_count + " of " + total_num_fields_in_group);
//					alert("key -> " + key);
					var field = value.field;
					var human_readable_field = value.human_readable_field;
					var conditions = value.conditions;
					var placeholder = value.placeholder;
					var number_blocks_for_field = value.number_blocks_for_field;
					var information = value.information;
					var sub_fields = value.sub_fields;
					
					if ( value.validation_url ) { // Validation URL has been set for this field, initialize it in the DOM using function
						validate_field(value.validation_url, field, settings.use_jquery_growl);
					}
					
					if ( value.autocomplete_url ) { // Autocomplete URL has been set for this field, initialize it in the DOM using function
						autocomplete_field(value.autocomplete_url, field, settings.use_jquery_growl);
					}

					if ( field !== 'phenotype_epad' ) {
						statement += '<div class="well ' + field + '_block">';
						statement += '<div class="row-fluid block start ' + group_name + ' ' + field + '">';
						statement += '<div class="span2 pagination-centered"><button class="btn field_information_button" rel="popover" data-content="' + information + '" data-original-title="Field Information"><i class="icon-question-sign"></i>&nbsp;' + human_readable_field + '</button></div>';
					}
					// Conditions select box
					var conditions_box = '<select class="input-medium conditions" name="conditions">';
//					conditions_box += '<option value="" selected="selected">-- Select a condition --</option>';
					$.each(conditions, function(i, condition) {
//						alert(field + " -> " + condition);
						if ( condition == '---------' ) {
							conditions_box += '<option value="' + condition + '" disabled>' + condition + '</option>';
						}
						else if ( condition == '&ne;' ) { // Use != for the value if &ne; symbol is used - having problems with the htmlencoded symbol
							conditions_box += '<option value="!=">' + condition + '</option>';
						}
						else {
							conditions_box += '<option value="' + condition + '">' + condition + '</option>';
						}
					});
					conditions_box += '</select>';

					// Check for specicial field types of phenotype_term (has an ontology drop down) or phenotype_epad for EAV type query
					if ( field == 'phenotype_term' ) {
						// http://stackoverflow.com/questions/5150571/javascript-function-that-returns-ajax-call-data
						// http://stackoverflow.com/questions/905298/jquery-storing-ajax-response-into-global-variable
						var d;
						getPhenotypeOntologies().success(function (data) {
							d = data;
						});
					
						var ontologies_box = '<select class="input-small ontologies" name="ontologies" id="ontologies">';
						ontologies_box += '<option value="freetext">Free text</option>';
						$.each(d, function(i, ontology) {
//							alert(ontology.name);
							ontologies_box += '<option value="' + ontology.abbreviation + '">' + ontology.name + '</option>';	
						});
						ontologies_box += '</select>';
						statement += '<div class="span2 pagination-centered">' + ontologies_box + '</div>';
						statement += '<div class="span2 pagination-centered">' + conditions_box + '</div>';
					}
					else if ( field == 'phenotype_epad' ) {
						statement += '<div class="well ' + field + '_block">';
						statement += '<div class="row-fluid block start ' + group_name + ' ' + field + '">';
						
						var d;
						getPhenotypeAttributesNRList().success(function (data) {
							d = data;
						});
					
						var ontologies_box = '<select class="input-xlarge attribute_terms">';
						ontologies_box += '<option></option>';
						$.each(d, function(i, ontology) {
//							alert(ontology.attribute_termName);
							if ( ontology.attribute_sourceID.toUpperCase() !== 'LOCALLIST') {
//								alert(ontology.attribute_sourceID + " -> " + ontology.attribute_termName);
								ontologies_box += '<option value="' + ontology.attribute_termName + '">' + ontology.attribute_termName + ' (Ontology:' + ontology.attribute_sourceID + ')' + '</option>';
							}
							else {
								ontologies_box += '<option value="' + ontology.attribute_termName + '">' + ontology.attribute_termName + '</option>';
							}
						});
						ontologies_box += '</select>';
						statement += '<div class="span5 pagination-centered">' + ontologies_box + '</div>';
						statement += '<div class="span2 pagination-centered">' + conditions_box + '</div>';

					}
					else {
						statement += '<div class="span3 pagination-centered">' + conditions_box + '</div>';
					}
					if ( ! placeholder ) { // Set generic help placeholder if one wasn't specified
						placeholder = 'Enter search term...';
					}
//					alert(group_name + " -> " + field + " -> " +  " conditions -> " + conditions_box);
					
					// Hack for separating out coordinates into chr start and stop
//					if ( field == 'coordinates' ) {
//						statement += '\
//							<div class="span5 pagination-centered coordinates_block"> \
//								<div class="input-prepend input-append"> \
//									<span class="add-on">Chr</span> \
//									<input class="query_term input-small chr" data-field-name="chr" type="text" placeholder="E.g. chr1"> \
//									<span class="add-on"><i class="icon-remove-circle"></i></span> \
//								</div> \
//								<div class="input-prepend input-append"> \
//									<span class="add-on">Start</span> \
//									<input class="query_term input-medium start" data-field-name="start" type="text" placeholder="E.g. 2000001"> \
//									<span class="add-on"><i class="icon-remove-circle"></i></span> \
//								</div> \
//								<div class="input-prepend input-append"> \
//									<span class="add-on">Stop</span> \
//									<input class="query_term input-medium stop" data-field-name="stop" type="text" placeholder="E.g. 2002001"> \
//									<span class="add-on"><i class="icon-remove-circle"></i></span> \
//								</div> \
//							</div> \
//							';
//					}
//					else if ( field == 'query_api_example' ) {
//						statement += '\
//							<div class="span5 pagination-centered coordinates_block"> \
//								<div class="input-prepend input-append"> \
//									<span class="add-on">Source</span> \
//									<input class="query_term input-medium source" data-field-name="source" type="text" placeholder="E.g. RefSeq" value="RefSeq"> \
//									<span class="add-on"><i class="icon-remove-circle"></i></span> \
//								</div> \
//								<div class="input-prepend input-append"> \
//									<span class="add-on">Reference</span> \
//									<input class="query_term input-medium reference" data-field-name="reference" type="text" placeholder="E.g. NC_000002.11" value="NC_000002.11" > \
//									<span class="add-on"><i class="icon-remove-circle"></i></span> \
//								</div> \
//								<div class="input-prepend input-append"> \
//									<span class="add-on">Start</span> \
//									<input class="query_term input-medium start" data-field-name="start" type="text" placeholder="E.g. 2000001" value="2000001" > \
//									<span class="add-on"><i class="icon-remove-circle"></i></span> \
//								</div> \
//								<div class="input-prepend input-append"> \
//									<span class="add-on">End</span> \
//									<input class="query_term input-medium end" data-field-name="end" type="text" placeholder="E.g. 2000002" value="2000002" > \
//									<span class="add-on"><i class="icon-remove-circle"></i></span> \
//								</div> \
//								<div class="input-prepend input-append"> \
//									<span class="add-on">Allele Sequence</span> \
//									<input class="query_term input-medium allele_sequence"  data-field-name="allele_sequence" type="text" placeholder="E.g. A" value="A,T,G,C" > \
//									<span class="add-on"><i class="icon-remove-circle"></i></span> \
//								</div> \
//							</div> \
//							';
//					}
//					else {
						if ( sub_fields ) {
							if ( sub_fields.length > 1 ) {
								statement += '<div class="span5 pagination-centered">';
								$.each(sub_fields, function(i, sub) {
									var human_readable_sub_field = sub.human_readable_field;
									var sub_field = sub.field;
									var input_size = sub.input_size;
									var sub_placeholder = sub.placeholder;
									var sub_value = sub.value;
//									alert("human_readable_sub_field -> " + human_readable_sub_field + "  sub_field -> " + sub_field + " input_size -> " + input_size);
									statement += '\
											<div class="input-prepend input-append"> \
												<span class="add-on">' + human_readable_sub_field + '</span> \
												<input class="query_term input-' + input_size + '" data-field-name="' + sub_field + '" type="text" placeholder="' + sub_placeholder + '" value="' + sub_value + '"> \
												<span class="add-on"><i class="icon-remove-circle"></i></span> \
											</div> \
										';
								});
								statement += '</div>';
							}
							else {
								alert("If you are using sub-fields in the query builder you must define more than one sub-field. If you only have one field then do not use the sub-field parameter. Email owenlancaster@gmail.com if you require help.")
								throw new Error();
							}
						}
						else {
							if ( field == 'phenotype_epad' ) {
								statement += '\
									<div class="span4 pagination-centered"> \
										<div class="epad_value_input" style="display: none; text-align: center;"> \
											<div class="input-append"> \
												<input class="query_term input-large" data-field-name="' + field + '" type="text" placeholder="' + placeholder + '"> \
												<span class="add-on add-on-epad"><i class="icon-remove-circle"></i></span> \
											</div> \
										</div> \
										<div class="epad_value_select"> \
											<select class="input-medium epad_value"> \
												<option value="">--Select a value--</option> \
												<option value="null">NULL</option> \
												<option value="enter_value">[Input your own value]</option> \
											</select> \
										</div> \
									</div> \
									';
//												<option value="apparent">Apparent</option> \
//												<option value="not_apparent">Not apparent</option> \
//												<option value="unknown">Unknown</option> \

							}
							else {
								statement += '\
									<div class="span5 pagination-centered"> \
										<div class="input-append"> \
											<input class="query_term input-xlarge" data-field-name="' + field + '" type="text" placeholder="' + placeholder + '"> \
											<span class="add-on"><i class="icon-remove-circle"></i></span> \
										</div> \
									</div> \
									';
							}
						}
//					}
					
					// Only add a add button if there is more than 1 field allowed in this block
					if ( number_blocks_for_field > 1 ) {
						statement += '\
							<div class="span1 pagination-centered add_button"> \
								<button class="btn btn-mini btn-success add" ><i class="icon-plus"></i></button> \
							</div> \
							';
					}
					
//					$('button[rel="popover"]').popover();
//					$(".add").popover({ title: '', content: "Popover Content", placement: 'left', trigger: 'hover' });
//					if ( field_number < settings.number_blocks_per_field ) {
//						statement += '<br /><div class="row-fluid"><div class="span4 offset4">' + logic + '</div></div>';
//					}
					statement += '</div>';
					

//					}
					statement += '</div>';
					if ( fields_in_group_count < total_num_fields_in_group ) { // Only add the logic separator if it's not the last field of the group
						statement += '<div class="row-fluid">' + logic + '</div>';
					}

				});
				
				statement += '</div><br />';
				
				if ( group_count < total_num_groups ) {
					statement += '<div class="row-fluid g2p-logic" id="g2p-logic">' + logic + '</div>';
				}
				
			});
			
			$(document).on('change', ".attribute_terms", function() {
				var attribute = $(this).val();
//				alert("attribute -> " + attribute + " -> " + baseurl);
//				alert($(this).parent().parent().find('.epad_value').val());
				var epad_select_obj = $(this).parent().parent().find('.epad_value');
//				alert("selected value -> " + $(this).parent().parent().find('.epad_value').prop('selectedIndex',0));
				$.ajax({url: baseurl + 'admin/non_redundant_attribute_list_epad',
					data: {attribute: attribute},
//					data: data,
					dataType: 'json',
					delay: 200,
					type: 'POST',
					success: function(data) {
//						alert(data);



						$(epad_select_obj)
							.find('option')
							.remove()
							.end()
//							.append('<option value="present">Present</option>')
//							.append('<option value="absent">Absent</option>')
							.append('<option value="">--Select a value--</option>')
//							.append('<option value="apparent">Apparent</option>')
//							.append('<option value="not_apparent">Not apparent</option>')
//							.append('<option value="unknown">Unknown</option>')
							.append('<option value="null">NULL</option>')
							.append('<option value="enter_value">[Input your own value]</option>')
							.append('<option disabled>_________</option>');

						$.each(data, function(key,value) {
//							if ( (value !== 'NULL') && (value !== 'apparent') && (value !== 'not_apparent') && (value !== 'unknown') ) {
//							if ( (value !== 'NULL') && (value !== 'null') ) {
							if ( String(value) !== 'null' ) { // Need to cast value as string to do comparison (not sure why it doesn't work if not when comparing against null)
							
//								alert(value);
								$(epad_select_obj)
									.append($("<option></option>")
									.attr("value",value)
									.text(value)); 
							}
//							alert(value);
//							var value = $( this ).find('.epad_value').val();
//							$(this).parent().parent().next().find('.epad_value').prop('selectedIndex',0);
						});
					}
				});

			});
			
			
			// Click listener for add buttons
			$(document).on('click', ".add", function() {
				// Get all the EPAD attribute terms and destroy all the select2 boxes so they can be reinitialised for it and the new cloned version
				$('.attribute_terms').each(function(i,o) {
					$(o).select2('destroy');
				});

				// Get the parent div of the add button that was clicked
//				$(".attribute_terms").select2('destroy');
//				$(this).parent().parent().parent().select2('destroy');
				var current_div = $(this).closest('.block');
				var current_group = $(this).closest('.boxed').attr('id');
				
//				alert("--> " + current_div.prevAll('.group').attr('class'));
				var current_classes = current_div.attr('class').split(' ');
				var class_of_current_div = current_classes.pop();
//				alert("current -> " + current_group + " -> " + class_of_current_div);
				
				
				var group_class_of_current_div = current_classes.pop();
				var number_blocks_for_field = settings.fields_grouped[group_class_of_current_div][class_of_current_div].number_blocks_for_field;
				
				var l_count = 0;
				var group_logic_values = settings.fields_grouped[group_class_of_current_div][class_of_current_div].logic;
				var group_logic = '<div class="row"><div class="btn-group btn-logic-internal-group" >';
				$.each(group_logic_values, function(i, l) {
					l_count++;
					if ( l_count == 1 ) {
						group_logic += '<a class="btn-logic btn btn-small ' + 'btn-primary' + '">' + l + '</a>';
					}
					else {
						group_logic += '<a class="btn-logic btn btn-small">' + l + '</a>';
					}
				});
				group_logic += '</div><br /></div>';

				var count_of_class_of_current_div = $("." + class_of_current_div).length;
				// Only add a new block if there are less than the number specified in settings for this field
				if ( count_of_class_of_current_div < number_blocks_for_field ) {
//					alert(count_of_class_of_current_div + " < " + number_blocks_for_field);
//					alert(current_div.attr('class').split(' ').pop());
//					alert($("." + class_of_current_div).length);
					// Add a new div by cloning the one that was clicked
//					current_div.clone().insertAfter(current_div);
					
//					alert($(this).closest('.block').find('input.logic').length);
//					if ( $(this).closest('.block').find('input.logic').length < 1 ) {
						// TODO: JUST REMOVED LINE BELOW FOR EPAD DEMO NEED TO PUT BACK IN AND MOVE TO THE TOP OF THE BLOCK AND ONLY HAVE ONCE
//						$(this).closest('.block').append('<div class="row-fluid"></div><div class="row-fluid"><div class="span4">' + group_logic + '</div></div>');
//					}
					// Add a remove button (only if one doesn't already exist)
					if ( $(this).closest('.block').find('button.remove').length < 1 ) {
						$(this).closest('.add').after('<button class="btn btn-mini btn-danger remove" ><i class="icon-minus icon-white"></i></button>');
						
						// TODO: needs checking as this was a last minute hack for epad demo so that newly added blocks reset to the default values instead of copying the values populated from attributes from the clone block
						current_div.next().find('.epad_value')
							.find('option')
							.remove()
							.end()
//							.append('<option value="present">Present</option>')
//							.append('<option value="absent">Absent</option>')
							.append('<option value="">--Select a value--</option>')
//							.append('<option value="apparent">Apparent</option>')
//							.append('<option value="not_apparent">Not apparent</option>')
//							.append('<option value="unknown">Unknown</option>')
							.append('<option value="null">NULL</option>')
							.append('<option value="enter_value">[Input your own value]</option>')
							.append('<option disabled>_________</option>');

					}
					current_div.clone().insertAfter(current_div);
					// Find all blocks of this time and remove all the add buttons
//					$("." + class_of_current_div).each(function() {
//						$(this).find(".add").remove();
//					});
					
//					$(this).parent("div").next().find(".add").remove();
					if ( count_of_class_of_current_div == (number_blocks_for_field - 1) ) {
						$("." + class_of_current_div).each(function() {
							// TODO: Fix the problem of the add button being removed when it shouldn't be for the last group in the interface
							$(this).find(".add").remove();
						});
						if ( settings.use_jquery_growl == 'yes' ) {
							$.growl.notice({ message: "The number of additional fields for this section is limited to " + number_blocks_for_field });
//							$.growl({ title: "Valid", message: data.message });
						}
						else {
							alert("The number of additional fields for this section is limited to " + number_blocks_for_field);
						}
					}
					else {
//						$(this).parent("div").find(".add").remove(); // Owen - removed
					}
//					$(this).remove(); // Remove the add button
//					$(".add").remove();
				
					// Re-initialize the autocomplete and validation listeners for this field type if the url's are definied in the settings
//					alert(JSON.stringify(settings['fields_grouped'][current_group][class_of_current_div])); // All keys are dynamic apart from the fields_group one so need to use quotes to access that key and access all the others with the dynamic name
					var tmp_validation_url = settings['fields_grouped'][current_group][class_of_current_div]['validation_url']
					if ( tmp_validation_url ) {
//						alert(tmp_validation_url);
						validate_field(tmp_validation_url, class_of_current_div, settings.use_jquery_growl);
					}
					var tmp_autocomplete_url = settings['fields_grouped'][current_group][class_of_current_div]['autocomplete_url'];
					if ( tmp_autocomplete_url ) {
//						alert(tmp_autocomplete_url);
						autocomplete_field(tmp_autocomplete_url, class_of_current_div, settings.use_jquery_growl);					
					}

					$(".attribute_terms").select2({
						placeholder: "--Select an attribute--",
						allowClear: true
					});

				}
				else {
				}
			});

			// Click listener for clearing the input box
			$(document).on('click', ".add-on", function() {
				$(this).prevAll('input').val('');
			});

			// Added in temporarily for EPAD demo to reset back to select box when clear button in input is clicked
			$(document).on('click', ".add-on-epad", function() {
				$(this).parent().parent().hide();
				$(this).parent().parent().next('.epad_value_select').show();
				$(this).parent().parent().next().find('.epad_value').prop('selectedIndex',0);
			});

			// Click listener for remove buttons
			$(document).on('click', ".remove", function() {
				
				// Get the top level block for this type (so it can be used to find the last remaining remove button in the block below
				var previous_block_div = $(this).parent().parent().parent();
				
				$(this).closest('.block').remove();
				$(this).closest('.block').next().find('.row').remove();

				
				// Get the info needed to get the count of the number blocks
				var current_div = $(this).closest('.block');
				var current_classes = current_div.attr('class').split(' ');
				var class_of_current_div = current_classes.pop();
				var count_of_class_of_current_div = $("." + class_of_current_div).length;
				// If there's only one block left then we need to get rid of the remove button to stop the user from deleting all the blocks
				if ( count_of_class_of_current_div == 1 ) {
					previous_block_div.find('.remove').remove(); // Remove the remove button from the remaining row as you don't want to the user to be able to delete all the rows for the block
				}
				
			});

			$(document).ready(function(){
				var flag = 0;
				$('#build_query_button').click( function() { // Listener for build query button

					flag = 1;
					var source = $("#source").val();
					var reference = $("#reference").val();
					var start = $("#start").val();
					var end = $("#end").val();
					var allele_sequence = $("#allele_sequence").val();

					var jsonfied_allele_sequence = [];
					if ( allele_sequence ) {
						if (allele_sequence.indexOf(',') > -1) {
//							string.split(',')
							var json = [];
							var to = allele_sequence;
							var toSplit = to.split(",");
							for (var i = 0; i < toSplit.length; i++) {
								jsonfied_allele_sequence.push(toSplit[i]);
							}
//							alert("json -> " + JSON.stringify(json));
						}
						else {
							jsonfied_allele_sequence.push(allele_sequence);
						}
					}

					var queryType = $("#queryType").val();
					var queryLabel = $("#queryLabel").val();
					var queryResultFormat = $("#queryResultFormat").val();
					var submitterID = $("#submitterID").val();
					var submitterName = $("#submitterName").val();
					var submitterEmail = $("#submitterEmail").val();
					var submitterInstitute = $("#submitterInstitute").val();
					
					var randletter = randomstring(5);
//					alert(randletter);
					var datenow = Date.now();
					var uniqid = randletter + datenow;					
					var query = {};
					query["queryMetadata"] = 
							{ 
								"queryId" : uniqid,
								"queryType" : queryType,
								"label" : queryLabel,
								"queryResultFormat" : queryResultFormat,
								"submitter" : {
									"id": submitterID ,
									"name" : submitterName,
									"email" : submitterEmail,
									"institution" : submitterInstitute
								}
							};
					
//					alert(JSON.stringify(query));
					
					var query_statements = {};
//					query_statements['query'] = [];
//					query_array['query'] = {};
//					alert(JSON.stringify(query_array));
					var final_query_statement = [];
					// TODO: Add in another loop to go through multiple fields for one block like in the query_api_example setup - need to also get this setup type with subfields to be parsed in the plugin here
					// *** This is the proper bit that should be used for generating the query string (bit below is just hard-coded following on from query api workshop in Amsterdam
					var group_count = 0;
					var parameter_id = 0;
					$.each(settings.fields_grouped, function(group_name, group) { // Loop through each group block (i.e. Genotype and Phenotype) defined in settings JSON
						var total_groups = Object.keys(group).length;
//						alert("TOTAL GROUPS -> " + total_groups);
						
						$.each(group, function(key, value) { // Go through each field defined in settings JSON 
							var section_query_statement = [];
							group_count++;
//							alert("group_count -> " + group_count);
							var field = value.field;
//							alert("FIELD -> " + field);
//							alert("block -> " + "." + field + "_block");
							var all_text_inputs = $("." + field + "_block" + " :input:text").length;
							var query_terms = $("." + field + "_block" + ' :input.query_term[type="text"]');
							var query_terms_count = query_terms.length;
//							alert("inputs -> " + all_text_inputs + " -> " + query_terms_count);

							var block_array = [];
//							block_array[field] = [];
							
							$.each( $("." + field + "_block"), function(i, block) {
								var total_block_sections = $('.' + field, block).length;
								var block_section_number = 0;
//								alert("TOTAL BLOCK SECTIONS -> " + total_block_sections);
								
								$('.' + field, block).each(function() {
									var field_array = {};
									
//									var query_terms = $("." + field + "_block" + ' :input.query_term[type="text"]');
//									var query_terms = $("." + field + ' :input.query_term[type="text"]');
									var condition_value = $( this ).find('.conditions').val();
//									var logic = $(this).closest('.block').next().find(".btn-logic-internal-group");
									var internal_logic_value = $(this).find(".btn-logic-internal-group").find(".btn-primary").text();

									if ( field === 'phenotype_epad') {
//										var attribute = $( this ).find('.attribute_terms').val();
										var attribute = $( this ).find('.attribute_terms').select2("val");
										var value = $( this ).find('.epad_value').val();
//										$( this ).find('.attribute_terms').val();
										if ( attribute ) {
//											alert("attribute -> " + attribute);
											field_array['attribute'] = '' + attribute;
										}
										else {
//											alert("no attribute");
										}
										
//										alert("field -> " + field + " attribute -> " + attribute + " condition_value -> " + condition_value + " value -> " + value);
									}
									
//									alert("HREF TEXT" + $(this).find('.btn-logic-internal-group').find('.btn-primary').text());
//									alert("logic selector -> " + logic);
//									alert("logic selector stringify -> " + JSON.stringify(logic));
//									var logic_value = logic.find(".btn-primary").text();
//											$('.btn-download').attr('href');
//									alert("conditions -> " + condition_value + " logic -> " + internal_logic_value);
									
									field_array['operator'] = condition_value;
									
									var query_terms = $('input.query_term[type="text"]', $(this));
									var query_terms_length = $('input.query_term[type="text"]', $(this)).length;
//									alert("LENGTH -> " + query_terms_length);

									var has_fields = 0;
									var fields_statement;
									var number_query_terms = query_terms.length;
//									alert("number_query_terms -> " + number_query_terms);
									$.each(query_terms, function(index) {
//										alert("starting index -> " + index);
										var field_value = $(this).val();
										var field_name = $(this).attr('data-field-name');
										if ( ! attribute ) {
//											alert("Not all sections of the the query have been completed, unable to proceed with query");
//											throw new Error();
										}
										if ( field_value ) {
											has_fields = 1;
//											alert("field_name -> " + field_name + " field_value -> " + field_value);
											if ( field_name === "allele_sequence" || field_name === "allele" ) { // Hack to create an array for the allele_sequence values
												var allele_sequence_array = [];
												field_value = field_value.replace(/\s+/g, "");
												if (field_value.match(/,/g) ) {
													allele_sequence_array = field_value.split(",");
												}
												else {
													allele_sequence_array.push(field_value);
												}
												field_array[field_name] = allele_sequence_array;
											}
											else {
												// Checks for epad phenotype fields that do not make sense
												if ( field === 'phenotype_epad') {
													// Throw error when numeric comparison is specified with non-numeric value
													if ( condition_value == '>' || condition_value == '<' || condition_value == '>=' || condition_value == '<=' || condition_value == '=' || condition_value == '!=' ) {
														if ( ! isNumber(field_value) ) {
//															alert("condition_value 1 -> " + condition_value + " value -> " + field_value);
															alert("A numeric comparison operator was specified but the entered value is not numeric, unable to proceed with the query.");
															$('#queryresultdiv').empty();
															throw new Error();
														}
													}
													// Throw error if NULL query entered with anything apart from IS or IS NOT
													else if (condition_value.toLowerCase() === 'is like' || condition_value.toLowerCase() === 'is' || condition_value.toLowerCase() === 'is not' || condition_value.toLowerCase() === 'is not like') {
														if (condition_value.toLowerCase() === 'is like' || condition_value.toLowerCase() === 'is not like') {
															if ( field_value.toUpperCase() === 'NULL' ) {
																alert("NULL queries are only possible with 'IS' or 'IS NOT' operators, unable to proceed with the query.");
																$('#queryresultdiv').empty();
																throw new Error();
															}
															else if ( isNumber(field_value) ) {
																var r = confirm("You have specified a string comparison operator but supplied a numeric value, proceed with query?");
																if (r == false) {
																	$('#queryresultdiv').empty();
																	throw new Error();
																}
															}
														}
														else {
															if ( isNumber(field_value) ) {
																var r = confirm("You have specified a string comparison operator but supplied a numeric value, proceed with query?");
																if (r == false) {
																	$('#queryresultdiv').empty();
																	throw new Error();
																}
															}
														}
													}
													
													if ( attribute  ) {
//														alert("field_value -> " + field_value + " attribute -> " + attribute + " field_name -> " + field_name);
														field_array[field_name] = field_value;
													}
												}
												else {
//													alert("condition_value 2 -> " + condition_value);
													field_array[field_name] = field_value;
//													alert(field_name + " -> " + field_value);
												}
											}
										}
										else if ( value ) {
//											alert("value -> " + value);
											if ( condition_value == '>' || condition_value == '<' || condition_value == '>=' || condition_value == '<=' || condition_value == '=' || condition_value == '!=' ) {
												if ( ! isNumber(value) ) {
//													alert("condition_value 3 -> " + condition_value);
													alert("A numeric comparison operator was specified but the entered value is not numeric, unable to proceed with the query.");
													$('#queryresultdiv').empty();
													throw new Error();
												}
											}
											else if (condition_value.toLowerCase() === 'is like' || condition_value.toLowerCase() === 'is' || condition_value.toLowerCase() === 'is not' || condition_value.toLowerCase() === 'is not like') {
												if (condition_value.toLowerCase() === 'is like' || condition_value.toLowerCase() === 'is not like') {
													if ( value.toUpperCase() === 'NULL' ) {
														alert("NULL queries are only possible with 'IS' or 'IS NOT' operators, unable to proceed with the query.");
														$('#queryresultdiv').empty();
														throw new Error();
													}
													else if ( isNumber(value) ) {
														var r = confirm("You have specified a string comparison operator but supplied a numeric value, proceed with query?");
														if (r == false) {
															$('#queryresultdiv').empty();
															throw new Error();
														}
													}
												}
												else {
													if ( isNumber(value) ) {
														var r = confirm("You have specified a string comparison operator but supplied a numeric value, proceed with query?");
														if (r == false) {
															$('#queryresultdiv').empty();
															throw new Error();
														}
													}
												}
											}

											if ( attribute ) {
												if ( value !== 'enter_value' ) {
													has_fields = 1;
													field_array[field_name] = value;
												}
											}
										}
										else { // There's no value or field_value so throw error
//											alert("Not all sections of the the query have been completed, unable to proceed with query");
//											throw new Error();
										}
									});
									
									if ( has_fields ) {
										block_section_number++;
										parameter_id++;
										section_query_statement.push({"parameter_id" : "(" + parameter_id + ")"});
//										section_query_statement.push({"parameter_id" : parameter_id});
										
										if ( internal_logic_value ) {
											section_query_statement.push({"internal_logic_value" : internal_logic_value});
										}
										
										field_array['parameterID'] = '' + parameter_id; // Add the parameter ID to the object as a string not a number
//										field_array['id'] = '' + parameter_id; // Add the parameter ID to the object as a string not a number
										
//										var parameter_array = {'parameterID' : parameter_id};
//										jQuery.extend(parameter_array, field_array);
										
//										
										// Add the fields to the main block object
										block_array.push( field_array );
										
									}
//									else {
//										alert("no fields for " + field);
//									}
								});
								
//								alert("NEXT LOGIC 1 -> " + $("." + field + "_block").next(".btn-logic-group").find(".btn-primary").text());
								
							});
							
							if (! $.isEmptyObject(block_array)) { // jQuery function to test for empty object (users may not have underscore.js included)
//							if ( _.isEmpty(block_array) == false ) { // Only add this block to the data structure if there's some data present (checked with underscore.js _.isEmpty function
//								alert("PRINTING BLOCK ARRAY -> " + _.isEmpty(block_array));
//								alert(JSON.stringify(block_array));
								query_statements[field] = block_array;
							}
							

//							alert(JSON.stringify(section_query_statement));
							if (! $.isEmptyObject(section_query_statement)) {
//							if ( _.isEmpty(section_query_statement) == false ) {
								final_query_statement.push(section_query_statement);
							}

							var separating_logic = $("." + field + "_block").next().find(".btn-logic-group").find(".btn-primary").text();
							if ( separating_logic ) {
//								alert("NEXT LOGIC -> " + $("." + field + "_block").next().find(".btn-logic-group").find(".btn-primary").text());
								final_query_statement.push({"separating_logic" : separating_logic});
							}
						});

						var group_separating_logic = $("." + group_name).next().next().find(".btn-logic-group").find(".btn-primary").text(); // Need next twice because of the <br /> element that comes after the group
//						var group_separating_logic = $("." + group_name).nextAll().first().find(".btn-logic-group").find(".btn-primary").text(); // Need next twice because of the <br /> element that comes after the group - http://stackoverflow.com/questions/6237673/cleanest-way-to-get-a-sibling-in-jquery
						if ( group_separating_logic ) {
							// TODO: HERE IS THE SEPARATOR
//							alert("group_name -> " + group_name + " -> " + group_separating_logic);
							final_query_statement.push({"group_separating_logic" : group_separating_logic});
						}
					});
//					alert(JSON.stringify(final_query_statement));
					if ($.isEmptyObject(final_query_statement)) {
//						alert("empty" + JSON.stringify(final_query_statement));
						alert("The required values were not entered");
						throw new Error();
					}

					
//					alert("final_query_statement type:" + jQuery.type(final_query_statement));

					/// This section is to remove trailing logic operators (both between query statements and between internal blocks) - bit hacky but can't currently figure out another way to clean up the query statement
					var last_query_statement_section = final_query_statement[final_query_statement.length-1]; // Get the last element 
					if ( jQuery.type(last_query_statement_section) === "array" ) { // This section has multiple field blocks so it's an array - again need to get last element of array
//						alert("array");
						var loop_last_query_statement_section = last_query_statement_section[last_query_statement_section.length-1];
						if ( "internal_logic_value" in loop_last_query_statement_section || "separating_logic" in last_query_statement_section ) { // If last object is logic on it's own without a parameter after then remove this trailing logic
//							alert("1 object removing");
							// Remove the trailing section from this array and also from main array then push the trimmed array back to main array
//							alert("before -> " + JSON.stringify(last_query_statement_section));
							last_query_statement_section.splice(-1,1); // Remove the trailing logic
//							alert("after -> " + JSON.stringify(last_query_statement_section));
//							final_query_statement.splice(-1,1);
//							final_query_statement.push(last_query_statement_section);
						}
//						alert(JSON.stringify(loop_last_query_statement_section));
					}
					else if ( jQuery.type(last_query_statement_section) === "object" ) {
//						if( last_query_statement_section['separating_logic'] ) {
						if ( "separating_logic" in last_query_statement_section || "internal_logic_value" in last_query_statement_section ) { // If last object is logic on it's own without a parameter after then remove this trailing logic
//							alert("2 object removing");
							final_query_statement.splice(-1,1); // Remove the trailing logic
						}
//						alert(last_query_statement_section['separating_logic']);
					}
					
					
					var query_statement_string = JSON.stringify(final_query_statement);
//					alert(query_statement_string);
//					
					// Remove/replace unwanted characters
					query_statement_string = query_statement_string.substring(1, query_statement_string.length-1); // Remove trailing characters which will be square braces
					// Remove the object curly braces and remove all the keys (just want to have the values)
					query_statement_string = query_statement_string.replace(/\{/g, "");
					query_statement_string = query_statement_string.replace(/\}/g, "");
					query_statement_string = query_statement_string.replace(/parameter_id/g, "");
					query_statement_string = query_statement_string.replace(/group_separating_logic/g, "");
					query_statement_string = query_statement_string.replace(/separating_logic/g, "");
					query_statement_string = query_statement_string.replace(/internal_logic_value/g, "");
					query_statement_string = query_statement_string.replace(/\:/g, "");
					
					// Clean up other bits and convert to normal brackets
					query_statement_string = query_statement_string.replace(/,/g, " ");
					query_statement_string = query_statement_string.replace(/\[/g, "(");
					query_statement_string = query_statement_string.replace(/\]/g, ")");
					query_statement_string = query_statement_string.replace(/\"/g, "");
					query_statement_string = query_statement_string.replace(/\\/g, "");
					
					
					// If there's single brackets in the query statement remove them 
//					if ((query_statement_string.match(/\(/g) || []).length === 1 ) {
//						query_statement_string = query_statement_string.replace(/\(/g, "");
//					}
//					if ((query_statement_string.match(/\)/g) || []).length === 1 ) {
//						query_statement_string = query_statement_string.replace(/\)/g, "");
//					}

					alert(query_statement_string);
//					alert(JSON.stringify(query_statements));
//					query_statements['queryStatement'] = query_statement_string;
					query['query'] = query_statements;


					// Add the final queryStatement to the final query structure
					query['queryStatement'] = query_statement_string;
					
//					alert(JSON.stringify(query));
					
					// Pretty print the query string with indentations
					var query_pretty = JSON.stringify(query, null, 4);  
					
					if ( flag ) { // If flag is true then there are fields with some input so can run the query
//						bootbox.dialog("<h4>Query Statement</h4><hr><div class='pagination-centered'><textarea id='final_query' style='width:80%' rows='5' >" + query_pretty + "</textarea></div>",
//							[
//								{
//									"label" : "Close",
//									"class" : "btn-mini",
//									"icon"  : "icon-remove icon-black"
//								},
//								{
//									"label" : "Save",
//									"icon" : "icon-file icon-white",
//									"class" : "btn-primary btn-mini",
//									"callback": function() {
////										console.log("Primary button");
////										window.open(baseurl + 'discover/variants/openAccess/rss');
//									}
//								},
//								{
//									"label" : "Submit",
//									"icon" : "icon-ok icon-white",
//									"class" : "btn-success",
//									"callback": function() {
										var final_query = query_pretty;
//										var final_query = $('#final_query').val();
//										alert("Final statement -> " + final_query);
										centerDiv("#ajax-loader");
										$('#ajax-loader').show(500);
										
//										var requestCallback = new MyRequestsCompleted({
//											numRequest: 3,
//										});	
//										$.when( $.ajax( "/page1.php" ), $.ajax( "/page2.php" ) ).then( myFunc, myFailure );
										var results = {};

//										$.each(settings.endpoints, function(i,u){
////											alert(u.name + " -> " + u.url);
////											var name = u.name + '';
//											$.ajax({
//												url: u.url,
//												contentType: 'application/json',
////												crossDomain: true,
////												data: {query : final_query},
//												data: final_query,
//												async: false,
//												processData: false,
//												dataType: 'html',
//												type: 'POST',
////												success: requestCallback.requestComplete(true)
//												success: function(data) {
////													alert(data);
//													results[u.name] = data;
//												}
//											});
//										});
										$('#' + settings.result_div_id).empty()
										var urls = $.makeArray(settings.endpoints); // Convert the object to an array so it can be passed to map function (http://api.jquery.com/jquery.map/)
//										alert("urls -> " + urls);
//										Using this method here to make multiple ajax calls to the supplied URLS: http://stackoverflow.com/questions/18590201/getting-multiple-ajax-with-jquery-each-when-jquery-when-all-done-do-something
										$.when.apply(null, urls.map(function (u,i) {
											alert(JSON.stringify(u) + " ---> " + u.url);
											return $.ajax({ url: u.url, data: final_query, contentType: 'application/json', dataType: 'html', type: 'POST' });
										})).then(function() {
//											alert(JSON.stringify(arguments));
											var objects = arguments; // Special jquery argument that contains returned data (bit of a hack but unsure how to do it otherwise as then then function needs the same number of parameters as were inputted in the when.apply bit, see here http://stackoverflow.com/questions/5627284/pass-in-an-array-of-deferreds-to-when)
											console.log("Resolved objects:", objects);
//											if (settings.table_output_type == 'hits') {
//												var hits = {};
												var hits = { 'all_results' : [] };
//											}
											$.each(objects, function(k,v){
//												alert(k + " -> " + v);
												
												if (typeof(v) === 'string') { // Just one results set was returned so the data is a string
//													alert("string -> " + typeof(v));
													if (settings.table_output_type == 'hits') {
//														alert("hits multiple");
														var result_set = parseQueryResultsString(k,v,settings,uniqid,final_query);
														var encoded_endpoint = encodeURIComponent(settings.endpoints[k].url);
														result_set['source_info'] = {name: settings.endpoints[k].name, endpoint: settings.endpoints[k].url, encoded_endpoint: encoded_endpoint, id: uniqid};
														hits['all_results'].push(result_set);
													}
													else if (settings.table_output_type == 'results') {
//														alert("multiple results");
														parseQueryResultsString(k,v,settings,uniqid,final_query);
													}
													else {
														parseQueryResultsString(k,v,settings,uniqid,final_query);
													}
												}
												else if (typeof(v) === 'object') { // Multiple results so data is an object
//													alert("object -> " + typeof(v));
													$.each(v, function(a,b) { // Iterate through results object
														if ( a === 0 ) { // First object is the returned query JSON
															if (settings.table_output_type == 'hits') {
//																alert("hits multiple");
//																alert("--> " + settings.endpoints[k].name);
																var result_set = parseQueryResultsString(k,b,settings,uniqid,final_query);
																var encoded_endpoint = encodeURIComponent(btoa(settings.endpoints[k].url));
//																alert(encoded_endpoint);
																result_set['source_info'] = {name: settings.endpoints[k].name, endpoint: settings.endpoints[k].url, encoded_endpoint: encoded_endpoint, id: uniqid};
//																alert("result_set -> " + JSON.stringify(result_set));
//																hits.push({result : result_set});
																hits['all_results'].push(result_set);
															}
															else if (settings.table_output_type == 'results') {
//																alert("multiple results");
																parseQueryResultsString(k,b,settings,uniqid,final_query);
															}
															else {
																alert("multiple other -> " + settings.table_output_type);
																parseQueryResultsString(k,b,settings,uniqid,final_query);
															}
														}
													});
												}
												else {
//													alert("something else -> " + typeof(v));
												}
											});
//											alert("done");
											$('#ajax-loader').hide(500);
											if (settings.table_output_type == 'hits') {
//												alert("done -> " + JSON.stringify(hits));
												var hitsTemplate = '<table class="table table-hover table-bordered table-striped" id="discovertable"><thead><tr><th align="center" class="title">Source</th><th align="center" class="title">openAccess</th><th align="center" class="title">linkedAccess</th><th align="center" class="title">restrictedAccess</th></tr></thead>';
//												for (i = 0; i < hits.length; ++i) {
//													alert("done -> " + JSON.stringify(hits[i]));
//													hitsTemplate += '{{#metadata}}<tbody><tr><td><a rel="popover" data-content="Click for a description of this source (opens in a new window)." data-original-title="Source Information" href="' + settings.endpoints[k].url + '" target="_blank">' + settings.endpoints[k].name + '</a></td><td>openAccess</td><td>{{total}}</td><td>linkedAccess</td><td>0</td><td>restrictedAccess</td><td>0</td></tbody>{{/metadata}}</table>';
													hitsTemplate += '{{#all_results}}<tbody><tr><td><a rel="popover" data-content="Click for a description of this source (opens in a new window)." data-original-title="Source Information" href="{{#source_info}}{{endpoint}}{{/source_info}}" target="_blank">{{#source_info}}{{name}}{{/source_info}}</a></td><td><a class="btn btn-success" href="' + settings.hits_display_uri  + '/{{#source_info}}{{id}}/{{encoded_endpoint}}{{/source_info}}" target="_blank">Access {{#metadata}}{{total}}{{/metadata}} hits</a></td><td><button class="btn btn-info" disabled>No hits</button></td><td><button class="btn btn-info" disabled>No hits</button></td></tr></tbody>{{/all_results}}</table>';
													
//												}
//												alert(hitsTemplate);
												var html = Mustache.to_html(hitsTemplate, hits);
//												alert(html);
												$('#' + settings.result_div_id).append(html);
												if ( settings.use_datatables === 'yes' ) {
													$('#results_table').dataTable();
												}



											}
											
//											alert("done -> " + JSON.stringify(objects));
										});
										$('#ajax-loader').hide(500);
		//								})).then(urls.map(function (data) {
		//									alert(JSON.stringify(data));
		//								}));
										
										
										
										
//										$('#' + settings.result_div_id).empty()
//										$.each(settings.endpoints, function(i,u){
//											if ( isJsonString(results[u.name]) ) { // If GenoPheno Query API response is given then create a table
////												$('#ajax-loader').hide(500);
//												var obj = jQuery.parseJSON(results[u.name]);
////												alert(typeof(obj.metadata));
//
//												if (! $.isEmptyObject(obj.metadata)) {
////												if (typeof(obj.metadata) !== 'undefined') {
//													var queryID = obj.metadata.queryID;
//													var start = obj.metadata.start;
//													var num = obj.metadata.num;
//													var total = obj.metadata.total;
//													alert("total -> " + total);
//													var resultDataTable = generateResultsDataTable(obj);
//													if ( settings.store_query_uri ) {
////														alert(settings.store_query_uri);
////														$.post( settings.store_query_uri, { query_id: queryID, total_results: total, query_statement: final_query, query_response: data } );
//													}
//												
//													var template = "<h3>" + u.name + "</h3><table class='table table-hover table-bordered table-striped' id='results_table'><thead><tr>{{#headers}}<th>{{.}}</th>{{/headers}}</tr></thead>";
//													template += "{{#results}}<tbody><tr>{{#row}}<td>{{value}}</td>{{/row}}</tr></tbody>{{/results}}</table><br /><br />";
//													var html = Mustache.to_html(template, resultDataTable);
////													var html = JSON.stringify(resultDataTable, undefined, 2);
//													$('#' + settings.result_div_id).append(html);
//													if ( settings.use_datatables === 'yes' ) {
//														$('#results_table').dataTable();
//													}
//												}
//												else {
//													alert("Unknown results format");
//												}
//
//											}
//											else { // Else it's a custom html response so just display it in the div
//												$('#' + settings.result_div_id).append(results[u.name]);
//											}
//										});



										// $('#ajax-loader').hide(500);
////										var endpoint_url = baseurl + 'discover/query';
//										var endpoint_url = $("#endpoint").val();		
//										if ( endpoint_url ) {
////											alert("SENDING QUERY TO: " + endpoint_url);
//											$.ajax({
//												url: endpoint_url,
//												contentType: 'application/json',
////												crossDomain: true,
////												data: {query : final_query},
//												data: final_query,
//												processData: false,
//												dataType: 'html',
//												type: 'post',
//												success: function(data) {
////													alert("QUERY RESPONSE: " + data);
////													window.location.reload(true);
//													if ( isJsonString(data) ) { // If GenoPheno Query API response is given then create a table
//														$('#ajax-loader').hide(500);
//														var obj = jQuery.parseJSON(data);
//														var queryID = obj.metadata.queryID;
//														var start = obj.metadata.start;
//														var num = obj.metadata.num;
//														var total = obj.metadata.total;
////														alert("total -> " + total);
//														var resultDataTable = generateResultsDataTable(obj);
//														if ( settings.store_query_uri ) {
////															alert(settings.store_query_uri);
//															$.post( settings.store_query_uri, { query_id: queryID, total_results: total, query_statement: final_query, query_response: data } );
//														}
//														var template = "<table class='table table-hover table-bordered table-striped' id='results_table'><thead><tr>{{#headers}}<th>{{.}}</th>{{/headers}}</tr></thead>";
//														template += "{{#results}}<tbody><tr>{{#row}}<td>{{value}}</td>{{/row}}</tr></tbody>{{/results}}</table><br /><br />";
//														var html = Mustache.to_html(template, resultDataTable);
////														var html = JSON.stringify(resultDataTable, undefined, 2);
//														$('#' + settings.result_div_id).empty().append(html);
//														if ( settings.use_datatables == 'yes' ) {
//															$('#results_table').dataTable();
//														}
//
//													}
//													else { // Else it's a custom html response so just display it in the div
//														$('#ajax-loader').hide(500);
//														$('#' + settings.result_div_id).empty().append(data);
//													}
//												},
//												error: function(httpRequest, textStatus, errorThrown) {
////													$('#ajax-loader').hide(500);
//													alert("ERROR: no response data was received -> " + JSON.stringify(httpRequest));
//												}
//											});
//										}
//										else {
//											alert("ERROR: No endpoint URL has been entered in the metadata, unable to send query");
//										}
										
//										console.log("Primary button");
//										window.open(baseurl + 'discover/variants/openAccess/varioml');
//										bootbox.hideAll();
//									}
//								},
//							],
//							{
//								"animate": false
//							}
//						)
					}
					else {
						alert("You must specify search terms before building your query")
					}

				
					
				});
			});
		
		}

		
		// Click listener for reset button, clear all inputs and reset all selects to first element
		$(document).on('click', ".clear_all_button", function() {
			$('input').val('');
			$("select").prop("selectedIndex", 0);
			$("#" + settings.result_div_id).empty();
			$(".attribute_terms").select2("val", "");
		});	
	
		// Click listener for logic buttons that separate groups, find the parent div of the button group then go through each button and remove the primary color then add the primary colour to the button that was clicked
		$(document).on('click', ".btn-logic", function() {
//			alert("clicked");
			var original_btn_clicked = $(this);
			
			// For the button logic types that separate groups
			var parent_btn_group_class = $(this).closest('.btn-logic-group');
			parent_btn_group_class.children().each(function(i, elm) {
				$(this).removeClass( "btn-primary" );
			});

			// For the button logic types that separate internal fields in groups
			var parent_btn_group_class = $(this).closest('.btn-logic-internal-group');
			parent_btn_group_class.children().each(function(i, elm) {
				$(this).removeClass( "btn-primary" );
			});
			
		
			original_btn_clicked.addClass("btn-primary");

		});
		
		// Change listener for select to enter own value
		$(document).on('change','.epad_value',function(){
			var selected_option = $(this).val();
			if ( selected_option === 'enter_value' ) {
//				alert("select -> " + selected_option);
				$(this).closest('.epad_value_select').hide();
				$(this).parent().prev('.epad_value_input').show();
			}
		});
		
		// Click listener for the top button selection to choose whether query should be structured g2p or p2g
		$(document).on('click', ".btn-query-type", function() {
			var original_btn_clicked = $(this);
			var parent_btn_group_class = $(this).closest('.btn-query-type-group');
			parent_btn_group_class.children().each(function(i, elm) {
				$(this).removeClass( "btn-primary" );
			});
//			alert(original_btn_clicked.attr("id"));
			original_btn_clicked.addClass("btn-primary");
			
			if ( original_btn_clicked.attr("id") == 'g2p' ) {
				$("#Genotype").insertBefore("#Phenotype");
				$("<br />").insertBefore("#Phenotype");
				$("#g2p-logic").insertBefore("#Phenotype");
				
				if ( settings.use_jquery_growl == 'yes' ) {
					$.growl.notice({ message: "Changed to genotype to phenotype query type" });
//					$.growl({ title: "Valid", message: data.message });
				}
				
			}
			else if ( original_btn_clicked.attr("id") == 'p2g' ) {
				$("#Phenotype").insertBefore("#Genotype");
				$("<br />").insertBefore("#Genotype");
				$("#g2p-logic").insertBefore("#Genotype");
				
				if ( settings.use_jquery_growl == 'yes' ) {
					$.growl.notice({ message: "Changed to phenotype to genotype query type" });
//					$.growl({ title: "Valid", message: data.message });
				}
				
			}

		});
		
		// Autocomplete lookup function
//		if ( settings.autocomplete == 'on' ) {
//			$(document).ready(function() {
//				$(function() {
//					$(".query_term").autocomplete({
//						source: function(request, response) {
//							$.ajax({url: baseurl + 'discover/lookup',
//								data: {term: $(this).find('.query_term').val()},
//								dataType: 'json',
//								delay: 200,
//								type: 'POST',
//								success: function(data) {
//									response(data);
//								}
//							});
//						},
//						minLength: 2
//					});
//				});
//			});
//		}
		
        return this.each(function() {
			var intro = '<div class="row"><h3>Select the type of query you would like to build:</h3><br /></div>';
			intro += '<div class="btn-group btn-query-type-group" >';
			intro += '<a class="btn-query-type btn-primary btn btn-large" id="g2p">Genotype to phenotype</a>';
			intro += '<a class="btn-query-type btn btn-large" id="p2g">Phenotype to genotype</a>';
			intro += '</div>';
			intro += '<hr>';
			intro += '</div>';
			
//			var intro = '';
			
//			var metadata = '<div class="row"><h4>Enter endpoint for query:</h4><br />Endpoint: <input class="endpoint input-xlarge" id="endpoint" type="text" placeholder="http://yourdomain/query_api_endpoint" ></div><hr>';
//			$(this).append(metadata);
//			$(this).append(intro);
            $(this).append(statement);
			
//			$(".attribute_terms").select2('destroy');
			$(".attribute_terms").select2({
				placeholder: "--Select an attribute--",
				allowClear: true
			});
			

			
//			$(".epad_value").select2({
//				minimumResultsForSearch: Infinity
//			});
			
			// Initially hide all the add buttons - only want to show them after something has been typed in a term box
//			$('.add_button').hide();
			
//			$('.field_information_button').popover(); // Initalize popover for field information button
//			$.each(group, function(field, conditions) {
//				var numItems = $('.' + field).length;
//				alert("num -> " + field + " -> " + numItems);
//			});

			var query_button = '';
			query_button += '\
			<br /> \
			<div class="row-fluid"> \
				<div class="span12 pagination-centered"> \
					<button class="btn btn-large clear_all_button" id="clear_all_button"><i class="icon-trash"></i> Reset</button>&nbsp;&nbsp;\
					<button class="btn btn-primary btn-large build_query_button" id="build_query_button"><i class="icon-search"></i> Build Query</button> \
				</div> \
			</div> \
			<br /><br /> \
			';
			$(this).append(query_button);
            // if ( settings.color ) {
            //     $(this).css( 'color', settings.color );
            // }


            if ($.isFunction(settings.complete)) {
                settings.complete.call(this);
            }
        });

    };

}(jQuery));

function autocomplete_field(autocomplete_url, field, use_jquery_growl) {
//	alert("url -> " + autocomplete_url);
	// Autocomplete lookup function
	$(document).ready(function() {
		
		$( "." + field + " :input" ).each(function(i, el) {
			el = $(el);
			el.autocomplete({
				source: function(request, response) {
					var id = el.attr('class');
//					alert(id);
					alert(el.parent().parent().parent().parent().find('.attribute_terms').select2("val"));
				}
			});
		});		
		
		
		
		$(function() {
//			$(".query_term").autocomplete({
			$( "." + field + " :input" ).autocomplete({
				
				source: function(request, response) {
					var autocomplete_object = this.element;
//					alert(id);
					var data = {};

					data['term'] = request.term;
//					alert("term -> " + request.term);

					if ( field == "phenotype_term" ) {
						var ontology = $( "#ontologies" ).val();
						data['ontology'] = ontology;
//						alert("ontology -> " + ontology);
					}

					if ( field == "phenotype_epad" ) {
						var attribute = $(this.element).parent().parent().parent().parent().find('.attribute_terms').select2("val");
						data['attribute'] = attribute;
//						alert($(this.element));
//						var attribute = $('.attribute_terms').select2("val");
//						alert("attribute -> " + attribute);
					}

					$.ajax({url: autocomplete_url,
//						data: {term: $(this).find('.query_term').val()},
						data: data,
						dataType: 'json',
						delay: 200,
						type: 'POST',
						success: function(data) {
							response(data);
						}
					});
				},
				minLength: 1
			});
		});
	});
}

function validate_field(validation_url, field, use_jquery_growl) {
//	if ( field == "phenotype_term" ) {
//		var selected_ontology = $( "#ontology" ).val();
//		alert("selected_ontology -> " + selected_ontology);
//	}
	$(document).ready(function() {
		$( "." + field + " :input" ).keyup(function() {
			var el = $(this);
			delay(function(){
			var term;
				if ( field == 'coordinates' ) {
					var coordinates_block = el.closest('.coordinates_block');
					var chr = coordinates_block.find('.chr').val();
					var start = coordinates_block.find('.start').val();
					var stop = coordinates_block.find('.stop').val();
					if ( chr != '' && start != '' && stop != '' ) {
						term = chr + ":" + start + "-" + stop;
					}
					else {
						term = chr;
					}
//					alert(chr + " -> " + start + " -> " + stop);
				}
				else {
					term = el.val();
				}
//				alert(value);
				var term_character_count = el.val().length;
				if ( term_character_count == 0 ) {
//					alert("term character count -> " + term_character_count);
//					el.css('border', '1px solid blue');
//					el.removeClass('style');
//					alert("now 0");
//					$('.add_button').hide();
				}
				else {
//					el.next('.add_button').show();
//					$('.add_button').show();
				}
//				alert("term character count -> " + term_character_count);
//				http://stackoverflow.com/questions/576319/reset-an-input-controls-border-color-html-javascript
				$.ajax({
					url: validation_url,
					data: { field: field, term: term },
					dataType: 'json',
//					delay: 200,
					type: 'POST',
					success: function(data) {
//						alert(data);
						el.css('border', function(){
//						el.addClass(function( index ) {
							if ( data.status == "Validated" ) {
								if ( use_jquery_growl == 'yes' ) {
//									$.growl.notice({ message: data.message });
//									$.growl({ title: "Valid", message: data.message });
								}
								return '1px solid #3c3';
								
							}
							else if (data.status == "Not validated") {
								if ( use_jquery_growl == 'yes' ) {
//									$.growl.error({ message: data.message });
								}
								return '1px solid #f00';
							}
							else {
								return '1px solid #000';
							}
						});
					}
				});

			}, 10 );
//			alert("validate_url -> " + validation_url + " -> " + field);
		});
		
		// Event listener for select text box with this class
		$( "." + field + " :text" ).focusout(function() {
//			focus++;
//			$( "#focus-count" ).text( "focusout fired: " + focus + "x" );
//			alert("lost focus -> " + field);
			var el = $(this);
			var term;
			if ( field == 'coordinates' ) {
				var coordinates_block = el.closest('.coordinates_block');
				var chr = coordinates_block.find('.chr').val();
				var start = coordinates_block.find('.start').val();
				var stop = coordinates_block.find('.stop').val();
				if ( chr != '' && start != '' && stop != '' ) {
					term = chr + ":" + start + "-" + stop;
				}
				else {
					term = chr;
				}
//				alert(chr + " -> " + start + " -> " + stop);
			}
			else {
				term = el.val();
			}
			var term_character_count = el.val().length;			
			$.ajax({
				url: validation_url,
				data: { field: field, term: term },
				dataType: 'json',
//				delay: 200,
				type: 'POST',
				success: function(data) {
//					alert(data);
					el.css('border', function(){
//					el.addClass(function( index ) {
						if ( data.status == "Validated" ) {
							if ( use_jquery_growl == 'yes' ) {
//								$.growl.notice({ message: data.message });
//								$.growl({ title: "Valid", message: data.message });
							}
//							return '1px solid #3c3';
						}
						else if (data.status == "Not validated") {
							if ( use_jquery_growl == 'yes' ) {
								if ( term_character_count > 0 ) {
									$.growl.error({ message: data.message });
								}
							}
//							return '1px solid #f00';
						}
						else {
//							return '1px solid #000';
						}
					});
				}
			});
		});
	});
}

var delay = (function(){
	var timer = 0;
	return function(callback, ms) {
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	};
})();


function getPhenotypeOntologies() {
	var url = baseurl + "admin/get_phenotype_ontologies";
	return $.ajax({
		url: url,
		type: 'GET',
		dataType: 'json',
		async: false,
		cache: false
    });
}

function getPhenotypeAttributesNRList() {
//	var url = baseurl + "admin/get_phenotype_attributes_nr_list";
	var url = baseurl + "admin/get_phenotype_attributes_nr_list_federated";
	
	return $.ajax({
		url: url,
		type: 'GET',
		dataType: 'json',
		async: false,
		cache: false
    });
}

function randomstring(L){
    var s= '';
    var randomchar=function(){
    	var n= Math.floor(Math.random()*62);
    	if(n<10) return n; //1-10
    	if(n<36) return String.fromCharCode(n+55); //A-Z
    	return String.fromCharCode(n+61); //a-z
    }
    while(s.length< L) s+= randomchar();
    return s;
}

function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

// Parse the G2P Query API JSON response into the JSON structure for Mustache so it can be displayed as a generic table
function generateResultsDataTable(obj) {
	var queryID = obj.metadata.queryID;
	var total = obj.metadata.total;
//	alert("total -> " + total);
//	dataTable['metadata'].push({'queryID' : queryID, 'total' : total });
	var dataTable = { 'metadata' : { 'queryID' : queryID, 'total' : total }, 'headers' : [], 'results' : [] };
//	var dataTable = { 'queryID' : queryID, 'total' : total, 'headers' : [], 'results' : [] };

//	var headers = [];
	var rowNum = 0;
	$.each(obj.results, function() {
		rowNum++;
		
		var rowData = {};
//		var rowKey = rowNum + '';
		var rowKey = 'row';
//		alert("key -> " + rowKey);
		rowData[rowKey] = [];
//		alert(JSON.stringify(rowData, undefined, 2));
		$.each(this, function(k, v) {
//			alert("k:" + k + " -> v:" + v);
			rowData[rowKey].push({
				'key' : k,
				'value' : v
			});
			
			if($.inArray(k, dataTable['headers']) == -1 ) {
				dataTable['headers'].push(k);
			}
			
		});

		dataTable['results'].push(rowData);
//		alert(JSON.stringify(dataTable, undefined, 2));
	});
//	dataTable['headers'].push(headers);
//	alert(JSON.stringify(dataTable['headers'], undefined, 2));
	
//	alert(JSON.stringify(dataTable, undefined, 2));
	return dataTable;
}


function parseQueryResultsString(k, results, settings, uniqid, final_query) {
//	alert(k + " -> " + results);
//	$('#' + settings.result_div_id).append(results);
//	$('#ajax-loader').hide(500);
	if ( isJsonString(results) ) {
		var obj = jQuery.parseJSON(results);
//		alert(typeof(obj));
		// alert(settings.endpoints[k].name);
//		results[settings.endpoints[k].name] = JSON.stringify(obj);
//		alert("metadata -> " + obj.metadata.href);
		if (! $.isEmptyObject(obj.metadata)) {
//		if (typeof(obj.metadata) !== 'undefined') {
			var queryID = obj.metadata.queryID;
			var start = obj.metadata.start;
			var num = obj.metadata.num;
			var total = obj.metadata.total;
			// alert("total -> " + total);
			var resultDataTable = generateResultsDataTable(obj);
			if ( settings.store_query_uri ) {
//				alert(settings.store_query_uri);
				$.post( settings.store_query_uri, { query_id: uniqid, total_results: total, query_statement: final_query, query_response: JSON.stringify(obj), endpoint: settings.endpoints[k].url } );
			}
		
		
			if (settings.table_output_type == 'hits') {
				return resultDataTable;
			}
			else if (settings.table_output_type == 'results') {
				var resultTemplate = "<h3>" + settings.endpoints[k].name + " (" + total + " hits)</h3><table class='table table-hover table-bordered table-striped' id='results_table'><thead><tr>{{#headers}}<th>{{.}}</th>{{/headers}}</tr></thead>";
				resultTemplate += "{{#results}}<tbody><tr>{{#row}}<td>{{value}}</td>{{/row}}</tr></tbody>{{/results}}</table><br /><br />";
				var html = Mustache.to_html(resultTemplate, resultDataTable);
//				var html = JSON.stringify(resultDataTable, undefined, 2);
				$('#' + settings.result_div_id).append(html);
				if ( settings.use_datatables === 'yes' ) {
					$('#results_table').dataTable();
				}
			}
			else {

			}
		}
		else {
			
		}
	}
	else { // Not JSON so just append the results to the div
//		alert("Unknown results format");
		$('#' + settings.result_div_id).append(results);
	}
}

function centerDiv(id) {
    var winH = $(window).height();
    var winW = $(window).width();

    var dialog = $(id);

    var maxheight = dialog.css("max-height");
    var maxwidth = dialog.css("max-width");

    var dialogheight = dialog.height();
    var dialogwidth = dialog.width();

    if (maxheight != "none") {
        dialogheight = Number(maxheight.replace("px", ""));
    }
    if (maxwidth != "none") {
        dialogwidth = Number(maxwidth.replace("px", ""));
    }

    dialog.css('top', winH / 2 - dialogheight / 2);
    dialog.css('left', winW / 2 - dialogwidth / 2);
}

function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}