// Show the progress modal when the ajax install is started 
//$(document).ready(function() {
//	$(document).ajaxStart(function() {
//		$('#dialog').modal('show');
//	});
//});

 
function startInstall() {
	
	if ( $('#adminstats').is(":checked") ) {
//		alert("checked");
		var adminstats = "yes";
	}
	else {
//		alert("not checked");
		var adminstats = "no";
	}
	
	if ( $('#is_valid').val() ) {
		var is_valid = $('#is_valid').val();
	}
	
	// Get all the ontology sources that are selected and store as json array
	if($("#sources_select").length != 0) {
		var sources = $("#sources_select :selected").map(function() {
		var arr = [];
			arr.push([$(this).val(), $(this).text()]);
			return arr;
		}).get();
//		for(var i = 0; i < items.length; i++) {
//			alert(items[i]);
//		}
	}
	
	$.ajax({
		type: "POST",
		url: baseurl + "index.php",
//		dataType: 'html',
		dataType: 'json',
		data: {'hostname': $('#hostname').val(), 'username' : $('#username').val(), 'password' : $('#password').val(), 'database' : $('#database').val(), 'adminusername' : $('#adminusername').val(), 'adminpassword' : $('#adminpassword').val(), 'adminemail' : $('#adminemail').val(), 'adminfirstname' : $('#adminfirstname').val(), 'adminlastname' : $('#adminlastname').val(), 'include_data' : $("#sampledata").val(), 'adminstats' : adminstats, 'bioportalkey' : $('#bioportalkey').val(), 'is_valid' : is_valid, 'sources' : sources, 'prefix': $('#prefix').val(), 'externalurl': $('#externalurl').val(), 'sitetitle': $('#sitetitle').val(), 'sitedescription': $('#sitedescription').val(), 'siteauthor': $('#siteauthor').val(), 'sitekeywords': $('#sitekeywords').val()},
		start: $('#dialog').modal('show'),
//		progress: function(update) {
//			alert("update");
//		},
//		progress: function(e) {
//			alert("progress");
//			console.log("progress -> " + e);
//			alert("progressing " + e);
//		},
//		xhr: function() {
//			console.log("xhr test");
//		},
//		beforeSend: function(XMLHttpRequest) {
//			console.log("before send test");
//		},
		success: function(data) {
			var progressbar = $( "#progressbar" ),
			progressLabel = $( ".progress-label" );
			if ( data.length == 0 ) { // Error JSON array is empty
				progressbar.progressbar( "value", 0 );
				progressLabel.text( "Complete!" );
				$("#status_info").html("<p>Installation complete, click close to proceed.</p>");
				$("#close_footer").show();
				// No errors so redirect to the success page
//				window.location = baseurl + "success.php";
				var urlToPost= baseurl + "success.php";
				var successJSON = {"result": "success"};
				post_to_url(urlToPost, successJSON);
			}
			else { // There are errors so want to report these to the user
//				var JSONdata = JSON.stringify(data);
//				alert("data -> " + JSONdata);
				// Create a form and post the returned errors to the error page. Taken from here:
				// http://stackoverflow.com/questions/133925/javascript-post-request-like-a-form-submit
				var urlToPost= baseurl + "error.php";
				post_to_url(urlToPost, data);
//				window.location = baseurl + "error.php";
			}
		},
		error: function(httpRequest, textStatus, errorThrown) {
//			alert("status=" + textStatus + ",error=" + errorThrown);
//			window.location = baseurl + "error.php";
			
//			$('#waiting').hide(500);
//			alert("Please specify a search term.");
		}
//		complete: function () {
//			alert("complete");
//		}
	});
}

function post_to_url(path, params) {
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", path);

	for(var key in params) {
		if(params.hasOwnProperty(key)) {
			var hiddenField = document.createElement("input");
			hiddenField.setAttribute("type", "hidden");
			hiddenField.setAttribute("name", key);
			hiddenField.setAttribute("value", params[key]);
			form.appendChild(hiddenField);
		}
	}

	document.body.appendChild(form);
	form.submit();
}

//$(document).ready(function() {
//	$('#dialog').on('hidden', function () {
//		location.reload();
//		window.location = baseurl + "success.php";
////		alert("test");
//	});
//});

$(function() {
	var progressbar = $( "#progressbar" ),
	progressLabel = $( ".progress-label" );
    $( "#progressbar" ).progressbar({
      value: false
    });
	progressbar.progressbar( "option", "value", false );
	$("#close_header").hide();
	$("#close_footer").hide();
});

// http://stackoverflow.com/questions/4365738/how-to-access-php-session-variables-from-jquery-function-in-a-js-file

//$(function() {
//	var progressbar = $( "#progressbar" ),
//	progressLabel = $( ".progress-label" );
// 
//	progressbar.progressbar({
//		value: false,
//		change: function() {
//			progressLabel.text( progressbar.progressbar( "value" ) + "%" );
//		},
//		complete: function() {
//			progressbar.progressbar( "value", 0 );
//			progressLabel.text( "Complete!" );
//			
//			
//		}
//	});
// 
//	function progress() {
//		var val = progressbar.progressbar( "value" ) || 0;
// 
//		progressbar.progressbar( "value", val + 1 );
// 
//		if ( val < 99 ) {
//			setTimeout( progress, 100 );
//		}
//	}
// 
//	setTimeout( progress, 3000 );
//});

$( document ).ready(function() {
    $('.btnPrev').hide();
});

$(function() {
  $('.btnNext').on('click', function() {        
    if(isLastTab()) {
		startInstall();
//      alert('submitting the form...');
	}
    else {
      nextTab();  
	}
  });
  $('a[data-toggle="tab"]').on('shown', function (e) {
    isLastTab();
  });
});

$(function() {
  $('.btnPrev').on('click', function() {        
    if(isFirstTab()) {
      alert('submitting the form...');
	}
    else {
//	  $('#btnPrev').show();
      prevTab();  
	}
  });
  $('a[data-toggle="tab"]').on('shown', function (e) {
    isFirstTab();
  });
});

function prevTab() {
  var e = $('#tab li.active').prev().find('a[data-toggle="tab"]');  
  if(e.length > 0) {
	  e.click();
  }  
  isFirstTab();
}

function nextTab() {
  var e = $('#tab li.active').next().find('a[data-toggle="tab"]');  
  if (e.length > 0) {
	  e.click();
  }  
  isLastTab();
}

function isLastTab() {
	var e = $('#tab li:last').hasClass('active'); 
	var nextString = "Next <i class='icon-arrow-right icon-white'></i>";
	if( e ) {
		var installString = "<i class='icon-ok-circle icon-white'></i> Install";
		//	  $('.btnNext').text('Install');
		$('.btnNext').html(installString);
		$('.btnNext').removeClass('btn-primary').addClass('btn-success');
		var isEmail = IsEmail($('#adminemail').val());
//		var is_prefix_unique = $('#is_prefix_unique').val();
//		if ( is_prefix_unique === "yes") {
//			alert("test");
//		}
		
		if ( $('#adminstats').is(":checked") ) { // See if the notify checkbox is checked, if so then get the status and whether it's unique across CV installs
			var adminusername_no_whitespace = $('#adminusername').val().replace(/\s+/g, '');
			$('#adminusername').val(adminusername_no_whitespace);
			if ( isEmail && $('#hostname').val() && $('#username').val() && $('#password').val() && $('#database').val() && $('#adminusername').val() && $('#adminemail').val() && $('#adminpassword').val() && $('#is_prefix_unique').val()) {
				$('.btnNext').show();
				$("#complete").html("<div class='alert'><p>All required fields have been completed, you may now proceed with the installation.</p></div>");
			}
			else {
				$('.btnNext').hide();
				$("#complete").html("<div class='alert alert-error'><p>Some required fields are not complete, installation cannot be started until you correct the errors. Please check the table below and complete the required information.</p></div>");
			}
		}
		else {
			var adminusername_no_whitespace = $('#adminusername').val().replace(/\s+/g, '');
			$('#adminusername').val(adminusername_no_whitespace);
			if ( isEmail && $('#hostname').val() && $('#username').val() && $('#password').val() && $('#database').val() && $('#adminusername').val() && $('#adminemail').val() && $('#adminpassword').val() ) {
				$('.btnNext').show();
				$("#complete").html("<div class='alert'><p>All required fields have been completed, you may now proceed with the installation.</p></div>");
			}
			else {
				$('.btnNext').hide();
				$("#complete").html("<div class='alert alert-error'><p>Some required fields are not complete, installation cannot be started until you correct the errors. Please check the table below and complete the required information.</p></div>");
			}
		}
		
		var hostnameStatus = ($('#hostname').val()) ? "<td style='text-align: center;'><span class='badge badge-success'><i class='icon-thumbs-up icon-white'></i></span></td>" : "<td style='text-align: center;'><span class='badge badge-important'><i class='icon-thumbs-down icon-white'></i></span></td>", complete = false;
		var usernameStatus = ($('#username').val()) ? "<td style='text-align: center;'><span class='badge badge-success'><i class='icon-thumbs-up icon-white'></i></span></td>" : "<td style='text-align: center;'><span class='badge badge-important'><i class='icon-thumbs-down icon-white'></i></span></td>", complete = false;
		var passwordStatus = ($('#password').val()) ? "<td style='text-align: center;'><span class='badge badge-success'><i class='icon-thumbs-up icon-white'></i></span></td>" : "<td style='text-align: center;'><span class='badge badge-important'><i class='icon-thumbs-down icon-white'></i></span></td>", complete = false;
		var databaseStatus = ($('#database').val()) ? "<td style='text-align: center;'><span class='badge badge-success'><i class='icon-thumbs-up icon-white'></i></span></td>" : "<td style='text-align: center;'><span class='badge badge-important'><i class='icon-thumbs-down icon-white'></i></span></td>", complete = false;
		var adminusernameStatus = ($('#adminusername').val()) ? "<td style='text-align: center;'><span class='badge badge-success'><i class='icon-thumbs-up icon-white'></i></span></td>" : "<td style='text-align: center;'><span class='badge badge-important'><i class='icon-thumbs-down icon-white'></i></span></td>", complete = false;
		var adminemailStatus = ($('#adminemail').val()) ? "<td style='text-align: center;'><span class='badge badge-success'><i class='icon-thumbs-up icon-white'></i></span></td>" : "<td style='text-align: center;'><span class='badge badge-important'><i class='icon-thumbs-down icon-white'></i></span></td>", complete = false;
		if ( ! isEmail ) {
			adminemailStatus = "<td style='text-align: center;'><span class='badge badge-important'><i title='Not a valid email address' class='icon-thumbs-down icon-white'></i></span></td>"
		}
		var adminpasswordStatus = ($('#adminpassword').val()) ? "<td style='text-align: center;'><span class='badge badge-success'><i class='icon-thumbs-up icon-white'></i></span></td>" : "<td style='text-align: center;'><span class='badge badge-important'><i class='icon-thumbs-down icon-white'></i></span></td>", complete = false;
		var password = $('#password').val().replace(/./gi, "*");
		var adminpassword = $('#adminpassword').val().replace(/./gi, "*");
		var externalurlStatus = ($('#is_externalurl_valid').val()) ? "<td style='text-align: center;'><span class='badge badge-success'><i class='icon-thumbs-up icon-white'></i></span></td>" : "<td style='text-align: center;'><span class='badge badge-important'><i class='icon-thumbs-down icon-white'></i></span></td>", complete = false;
		if ( $('#adminstats').is(":checked") ) { // Append the validation status of the prefix if notify button is unchecked
			var prefixStatus = ($('#is_prefix_unique').val()) ? "<td style='text-align: center;'><span class='badge badge-success'><i class='icon-thumbs-up icon-white'></i></span></td>" : "<td style='text-align: center;'><span class='badge badge-important'><i class='icon-thumbs-down icon-white'></i></span></td>", complete = false;
			
			$("#finalise-settings").html("<table class='table-centered table table-hover table-bordered'><thead><tr><th>Setting</th><th>Value</th><th>Status</th></tr></thead><tbody>" + "<tr><td>Hostname</td><td>" + $('#hostname').val() + "</td>" + hostnameStatus + "</tr>" + "<tr><td>MySQL Username</td><td>" + $('#username').val() + "</td>" + usernameStatus + "</tr>" + "<tr><td>MySQL Password</td><td>" + password + "</td>" + passwordStatus + "</tr>" + "<tr><td>MySQL Database</td><td>" + $('#database').val() + "</td>" + databaseStatus + "</tr>" + "<tr><td>Admin Username</td><td>" + $('#adminusername').val() + "</td>" + adminusernameStatus + "</tr><tr><td>Admin Email</td><td>" + $('#adminemail').val() + "</td>" + adminemailStatus + "</tr><tr><td>Admin Password</td><td>" + adminpassword + "</td>" + adminpasswordStatus + "</tr><tr><td>Variant ID Prefix</td><td>" + $('#prefix').val() + "</td>" + prefixStatus + "</tr>" + "<tr><td>External URL</td><td>" + $('#externalurl').val() + "</td>" + externalurlStatus + "</tr>" + "</tbody></table><br />");
		}
		else { // Otherwise don't include the validation status for the prefix (as it isn't going to get reported to CV Central
			$("#finalise-settings").html("<table class='table-centered table table-hover table-bordered'><thead><tr><th>Setting</th><th>Value</th><th>Status</th></tr></thead><tbody>" + "<tr><td>Hostname</td><td>" + $('#hostname').val() + "</td>" + hostnameStatus + "</tr>" + "<tr><td>MySQL Username</td><td>" + $('#username').val() + "</td>" + usernameStatus + "</tr>" + "<tr><td>MySQL Password</td><td>" + password + "</td>" + passwordStatus + "</tr>" + "<tr><td>MySQL Database</td><td>" + $('#database').val() + "</td>" + databaseStatus + "</tr>" + "<tr><td>Admin Username</td><td>" + $('#adminusername').val() + "</td>" + adminusernameStatus + "</tr><tr><td>Admin Email</td><td>" + $('#adminemail').val() + "</td>" + adminemailStatus + "</tr><tr><td>Admin Password</td><td>" + adminpassword + "</td>" + adminpasswordStatus + "</tr>" + "<tr><td>External URL</td><td>" + $('#externalurl').val() + "</td>" + externalurlStatus + "</tr>" + "</tbody></table><br />");
			
		}
	}
	else {
		$('.btnPrev').show();
		$('.btnNext').removeClass('btn-success').addClass('btn-primary');
//		$('.btnNext').text("Next");
		$('.btnNext').html(nextString);
		$('.btnNext').show();
	} 
	return e;
}

function isFirstTab() {
  var e = $('#tab li:first').hasClass('active');
  var prevString = "<i class='icon-arrow-left icon-white'></i> Prev";
  if( e ) {
	  $('.btnPrev').hide();
  }
  else {
	  $('.btnPrev').show();
//	  $('.btnPrev').text("Prev");
	  $('.btnPrev').html(prevString);
  } 
  return e;
}

// Initialise Twitter Bootstrap tooltips - target all elements with the rel property set to tooltip
$(document).ready(function() {
	$('[rel=tooltip]').tooltip();
});

// Main popover function from Twitter Bootstrap
$(function (){
	$("[rel=popover]").popover({placement:'left', trigger:'hover', animation:'true'}); // , delay: { show: 50, hide: 50 }
});


function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

// If the notify cafe variome central checkbox is clicked then recheck that all the options have been filled in (if unchecked then it means that the prefix isn't required and so need to update the final tab to add the install button
$(document).ready(function() {
	$("#adminstats").click(function() {
		isLastTab();
	});
});


// Validate API key button clicked - check if the key is valid and append ontology selection 
$(document).ready(function() {
	$("#validate_button").click(function(e) {

		e.preventDefault();
//        $.blockUI({ css: { 
//            border: 'none', 
//            padding: '15px', 
//            backgroundColor: '#000', 
//            '-webkit-border-radius': '10px', 
//            '-moz-border-radius': '10px', 
//            opacity: .5, 
//            color: '#fff' 
//        } });

		$('#waiting').show();
		$('#apivalidateresult').empty();

		var key = document.getElementById("bioportalkey").value;
		if ( key.length === 0 ) {
			$('#apivalidateresult').empty();
			alert("Please enter an API key");
		}
		else {
//			alert("key -> " + key + " -> " + baseurl);
			$('#waiting').show();
			$.ajax({
				url: baseurl + 'validate_key.php',
				data: {
					key: key
				},
				dataType: 'json',
//				delay: 200,
				type: 'POST',
				success: function(data) {
					$('#waiting').hide(500);
					if (data) {
						var is_valid = data.is_valid;
						var api_key = data.key;
//						alert("valid -> " + is_valid);
						if ( is_valid == 'yes') {
							var ontology_list = data.ontology_list;
							ontology_list.sort(compare); // Sort the list alphabetically
//							alert(typeof(sources));
							$('#apivalidateresult').empty();
							$('#apivalidateresult').append('<br /><button class="btn btn-mini btn-success" disabled="disabled" rel="popover" data-content="API key was valid." data-original-title="API Key Is Valid"><i class="icon-ok"></i></button>');
							$('#apivalidateresult').append('<br /><br /><p>Select the ontologies you want to use in your Cafe Variome install:</p>');
							$('#apivalidateresult').append('<select id="sources_select" size="15" style="width: 500px;" multiple></select>');
							$('#apivalidateresult').append('<input type="hidden" id="is_valid" name="is_valid" value="1" />');
							// Loop through the returned sources and append them as options in the multi select list
							$.each( ontology_list, function( key, value ) {
//								alert( key + ": " + value );
								$('#sources_select')
									.append($("<option></option>")
									.attr("value",value.name+"|"+value.acronym)
									.text(value.name + " (" + value.acronym + ")"));
							});
							$("#sources_select").select2({
								placeholder: "Click here to view and search through available ontologies"
							});
						}
						else if ( is_valid == 'no') {
							var ontology_list = data.ontology_list;
							ontology_list.sort(compare); // Sort the list alphabetically
							$('#apivalidateresult').empty();
							$('#bioportalkey').val(api_key);
							$('#apivalidateresult').append('<br /><strong>Key is not valid, Cafe Variome Central key is being used instead.</strong><br /><br /><button class="btn btn-mini btn-danger" disabled="disabled" rel="popover" data-content="API key is invalid." data-original-title="API key Not Valid"><i class="icon-remove-sign icon-white"></i></button>');
							$('#apivalidateresult').append('<br /><br /><p>Select the ontologies you want to use in your Cafe Variome install:</p>');
							$('#apivalidateresult').append('<select id="sources_select" size="50" style="width: 500px;" multiple></select>');
							$('#apivalidateresult').append('<input type="hidden" id="is_valid" name="is_valid" value="1" />');
							// Loop through the returned sources and append them as options in the multi select list
							$.each( ontology_list, function( key, value ) {
//								alert( key + ": " + value );
								$('#sources_select')
									.append($("<option></option>")
									.attr("value",value.acronym)
//									.text(value.name));
									.text(value.name + " (" + value.acronym + ")"));
									
							});
							$("#sources_select").select2({
								placeholder: "Click here to view and search through available ontologies"
							});

						}
						else if ( is_valid == 'failed') {
							$('#apivalidateresult').empty();
							$('#apivalidateresult').append('<br /><p>The BioPortal ontology list could not be accessed, the API key may be invalid. Try again or continue with install and add your API key and select ontology list after installation through the admin dashboard.</p>');
						}
						else {
							$('#apivalidateresult').empty();
							$('#apivalidateresult').append('<br /><p>Unknown error</p>');
						}
					}
				},
				async: true
			}).fail(function (error) {
				// error 
			});
		}
//		return false;
	});
});

// Alphabetically sort function for ontology list
function compare(a,b) {
	if (a.name < b.name)
		return -1;
	if (a.name > b.name)
		return 1;
	return 0;
}

// Validate prefix ID button was clicked - check if the prefix is unique across all CV installs using CV central web service to check this
$(document).ready(function() {
	$("#external_url_button").click(function(e) {
		e.preventDefault();

		var externalurl = document.getElementById("externalurl").value;
		if ( externalurl.length === 0 ) {
			$('#externalurlvalidateresult').empty();
			alert("Please enter an external URL");
		}
		
//		if (externalurl.search(/^[a-zA-Z]+$/)) {
//			alert("Only letters of the alphabet (upper or lower case) are allowed.");
//		}
//		
//		if (externalurl.length > 3) {
//			alert("The prefix length must be 3 characters or less");
//		}
		
		else {
			$.ajax({
				url: baseurl + 'check_external_url.php',
				data: {
					externalurl: externalurl
				},
				dataType: 'json',
//				delay: 200,
				type: 'POST',
				success: function(data) {
					if (data) {
//						JSON.stringify(data);
//						alert("test -> " + data);
						var data_json = jQuery.parseJSON( data );
						var is_valid = data_json.is_valid;
//						alert("is_valid -> " + is_valid);
						if ( is_valid == 'yes') {
							$('#externalurlvalidateresult').empty();
							$('#externalurlvalidateresult').append('<br /><button class="btn btn-mini btn-success" disabled="disabled" rel="popover" data-content="This external URL is contactable by Cafe Variome authentication server." data-original-title="Valid external URL"><i class="icon-ok"></i></button>');
							$('#externalurlvalidateresult').append('<input type="hidden" id="is_externalurl_valid" name="is_externalurl_valid" value="1" />');
						}
						else if ( is_valid == 'no') {
							$('#externalurlvalidateresult').empty();
							$('#externalurlvalidateresult').append('<br /><button class="btn btn-mini btn-danger" disabled="disabled" rel="popover" data-content="This external URL is not contactable by Cafe Variome authentication server." data-original-title="Invalid external URL"><i class="icon-remove-sign icon-white"></i></button>');
							alert("The external URL is not contactable from the Cafe Variome authentication server, please enter a valid external url.");
						}
						else {
							$('#externalurlvalidateresult').empty();
							$('#externalurlvalidateresult').append('<br /><p>Unknown error</p>');
						}
					}
				},
				async: true
			}).fail(function (error) {
				// error
			});
		}
//		return false;
	});
});

// Remove prefix validation if any key is pressed (to avoid user deleting or changing the ID after it has been validated
$(document).ready(function() {
	$('#externalurl').keyup(function() {
//		alert("Key up detected");
		$('#externalurlvalidateresult').empty();
	});
});

// Validate prefix ID button was clicked - check if the prefix is unique across all CV installs using CV central web service to check this
$(document).ready(function() {
	$("#prefix_validate_button").click(function(e) {
		e.preventDefault();

		var prefix = document.getElementById("prefix").value;
		if ( prefix.length === 0 ) {
			$('#prefixvalidateresult').empty();
			alert("Please enter a prefix");
		}
		
		if (prefix.search(/^[a-zA-Z]+$/)) {
			alert("Only letters of the alphabet (upper or lower case) are allowed.");
		}
		
		if (prefix.length > 3) {
			alert("The prefix length must be 3 characters or less");
		}
		
		else {
//			alert("prefix -> " + prefix);
			$.ajax({
				url: baseurl + 'check_prefix.php',
				data: {
					prefix: prefix
				},
				dataType: 'json',
//				delay: 200,
				type: 'POST',
				success: function(data) {
					if (data) {
//						JSON.stringify(data);
//						alert("test -> " + data);
						var data_json = jQuery.parseJSON( data );
						var is_unique = data_json.is_unique;
						if ( is_unique == 'yes') {
							$('#prefixvalidateresult').empty();
							$('#prefixvalidateresult').append('<br /><button class="btn btn-mini btn-success" disabled="disabled" rel="popover" data-content="This prefix ID is unique across all Cafe Variome installations." data-original-title="Unique Prefix"><i class="icon-ok"></i></button>');
							$('#prefixvalidateresult').append('<input type="hidden" id="is_prefix_unique" name="is_prefix_unique" value="1" />');
						}
						else if ( is_unique == 'no') {
							$('#prefixvalidateresult').empty();
							$('#prefixvalidateresult').append('<br /><button class="btn btn-mini btn-danger" disabled="disabled" rel="popover" data-content="This prefix ID is NOT unique across all Cafe Variome installations." data-original-title="Non-unique Prefix"><i class="icon-remove-sign icon-white"></i></button>');
							alert("The prefix is not unique across all Cafe Variome installs, please try another prefix.");
						}
						else {
							$('#prefixvalidateresult').empty();
							$('#prefixvalidateresult').append('<br /><p>Unknown error</p>');
						}
					}
				},
				async: true
			}).fail(function (error) {
				// error
			});
		}
//		return false;
	});
});

// Remove prefix validation if any key is pressed (to avoid user deleting or changing the ID after it has been validated
$(document).ready(function() {
	$('#prefix').keyup(function() {
//		alert("Key up detected");
		$('#prefixvalidateresult').empty();
	});
});

// Remove bioportal key validation if any key is pressed
$(document).ready(function() {
	$('#bioportalkey').keyup(function() {
//		alert("Key up detected");
		$('#apivalidateresult').empty();
	});
});
