$(document).ready(function(){
//$(function() {
//	$('.row .btn').on('click', function(e) {
	$('#variant_count_button').on('click', function(e) {
		e.preventDefault();
		var search_term = document.getElementById("search_term").value;
		var source_name = document.getElementById("source_name").value;
		var format =  $('#format :selected').text();
		var url = baseurl + "discover/variantcount/" + search_term + "/" + source_name + "/" + format;
//		alert("s -> " + url);
		$.get( url, function( data ) {
			$('#variant_count_call').empty();
			$('#variant_count_call').append('<br /><h4>Call:</h4><div class="span8 offset1"><div class="alert alert-success">' + url + '</div></div><br />');
//			alert("format -> " + format);
			if ( format == 'json' ) {
//				var variantcountdata = JSON.stringify(data);
				var variantcountdata = '<pre>' + JSON.stringify(data, null, "\t") + '</pre>';
			}
			else if ( format == 'tab' ) {
				var variantcountdata = '<pre>' + data + '</pre>';
				variantcountdata = variantcountdata.replace(/\n/g, '<br />');
//				variantcountdata = '<pre>' + variantcountdata.split("\n").join("<br />") + '</pre>';
				variantcountdata = variantcountdata.replace(/\t/g, '&nbsp;&nbsp;');
			}
			else {
				variantcountdata = data;
			}

			$('#variant_count_result').empty();
//			$( "#variantcountresult" ).html('<code>' + variantcountdata + '</code>');
			$( "#variant_count_result" ).html('<br /><br /><br /><h4>Response:</h4>' + variantcountdata);
//			$('#variantcountresult').append(variantcountdata);

		});
	});
});

$(document).ready(function(){
	$('#variants_button').on('click', function(e) {
		e.preventDefault();
		var search_term = document.getElementById("variants_search_term").value;
		var source_name = $('#variants_source_name :selected').val();
		var sharing_policy = $('#variants_sharing_policy :selected').text();
		var format =  $('#variants_format :selected').text();
		var username = document.getElementById("variants_username").value;
		var password = document.getElementById("variants_password").value;
		var url = baseurl + "discover/variants/" + search_term + "/" + source_name + "/" + sharing_policy + "/" + format;
//		alert("username " + username + " password " + password);
//		alert("s -> " + url);
		
		$.ajax ({
			type: "GET",
			url: url,
//			dataType: 'json',
			async: true,
			username: username,
			password: password,
//			data: '{ "comment" }',
			beforeSend: function (xhr) {
				xhr.setRequestHeader ("Authorization", make_base_auth(username, password)); 
			},
			success: function (data){
				
				$('#variants_call').empty();
				$('#variants_call').append('<br /><h4>Call:</h4><div class="span8 offset1"><div class="alert alert-success">' + url + '</div></div><br />');
//				alert("format -> " + format);
				if ( format == 'json' ) {
					var variantsdata = JSON.stringify(data, null, "\t");
					if(variantsdata.length > 500) variantsdata = variantsdata.substring(0,500) + "<br /><br />... OUTPUT LIMITED DUE TO SIZE";
					
					if (variantsdata == '[]'){
						variantsdata = '<pre>There is no data that satisfies the search criteria</pre>';
					}
					else {
						variantsdata = '<pre>' + variantsdata + '</pre>';
					}
				}
				else if ( format == 'tab' ) {
					var variantsdata = '<pre>' + data + '</pre>';
					var lines = variantsdata.split(/\r\n|\r|\n/);
					if(lines.length > 30) variantsdata = variantsdata.substring(0,30) + "<br /><br />... OUTPUT LIMITED DUE TO SIZE";

					variantsdata = variantsdata.replace(/\n/g, '<br />');
					variantsdata = variantsdata.replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;');
					
				}
				else if ( format == 'rss' ) {
					variantsdata = new XMLSerializer().serializeToString(variantsdata);
//					variantsdata = '<pre>' + variantsdata + "</pre>";
				}
				else {
					variantsdata = data;
					
				}



				$('#variants_result').empty();
				if ( format == 'rss' ) {
//					alert("test");
//					class="prettyprint"
//					$( "#variants_result" ).html('<br /><br /><br /><h4>Response:</h4>' + '<pre class="prettyprint">' + variantsdata + '</pre>');
//					$( "#variants_result" ).html('<pre>');
					variantsdata = vkbeautify.xml(variantsdata);
					$("#variants_result").text(variantsdata);
//					$( "#variants_result" ).html('<br /><br /><br /><h4>Response:</h4>' + variantsdata);
//					$("#variants_result").text('<pre class="prettyprint">' + formatXml(variantsdata) + '</pre>');
//					$( "#variants_result" ).html('</pre>');
				}
				else {
					$( "#variants_result" ).html('<br /><br /><br /><h4>Response:</h4>' + variantsdata);
				}
			}
		});
	});
});

$(document).ready(function(){
	$('#variant_button').on('click', function(e) {
		e.preventDefault();
		var variant_id = document.getElementById("variant_id").value;
		var format =  $('#variant_format :selected').text();
		var username = document.getElementById("variant_username").value;
		var password = document.getElementById("variant_password").value;
		var url = baseurl + "discover/variant/" + variant_id + "/" + format;
//		alert("username " + username + " password " + password);
//		alert("s -> " + url);
		$.ajax ({
			type: "GET",
			url: url,
//			dataType: 'json',
			async: true,
			username: username,
			password: password,
//			data: '{ "comment" }',
			beforeSend: function (xhr) {
				xhr.setRequestHeader ("Authorization", make_base_auth(username, password)); 
			},
			success: function (data){
				$('#variant_call').empty();
				$('#variant_call').append('<br /><h4>Call:</h4><div class="span8 offset1"><div class="alert alert-success">' + url + '</div></div><br />');
//				alert("format -> " + format);

				if ( format == 'json' ) {
					var variantdata = JSON.stringify(data, null, "\t");
//					if(variantdata.length > 500) variantdata = variantsdata.substring(0,500) + "<br /><br />... OUTPUT LIMITED DUE TO SIZE";
					variantdata = '<pre>' + variantdata + '</pre>';
				}
				else if ( format == 'tab' ) {
					var variantdata = '<pre>' + data + '</pre>';
//					var lines = variantdata.split(/\r\n|\r|\n/);
//					if(lines.length > 30) variantdata = variantdata.substring(0,30) + "<br /><br />... OUTPUT LIMITED DUE TO SIZE";

					variantdata = variantdata.replace(/\n/g, '<br />');
					variantdata = variantdata.replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;');
					
				}
				else {
					variantdata = data;
					
				}



				$('#variant_result').empty();
				$( "#variant_result" ).html('<br /><br /><br /><h4>Response:</h4>' + variantdata);
			}
		});
	});
});

$(document).ready(function(){
	$('#submit_button').on('click', function(e) {
		e.preventDefault();
		var source_name = $('#submit_source_name :selected').val();
//		var source_name = document.getElementById("submit_source").value;
		var sharing_policy = $('#submit_sharing_policy :selected').text();
		var overwrite =  $('#submit_overwrite :selected').text();
		var mutalyzer =  $('#submit_mutalyzer :selected').text();
		var username = document.getElementById("submit_username").value;
		var password = document.getElementById("submit_password").value;
		var body = $('textarea#submit_body').val();
		var url = baseurl + "api/variants/submit/source/" + source_name + "/sharing_policy/" + sharing_policy + "/overwrite/" + overwrite + "/mutalyzer/" + mutalyzer;
//		alert("body -> " + body);
//		alert("username " + username + " password " + password);
//		alert("s -> " + url);
		
		$.ajax ({
			type: "POST",
			url: url,
//			dataType: 'json',
			async: true,
//			contentType: "text/xml",
//			contentType: "application/json",
//			contentType: "text/html",
			contentType: "application/xml",
			processData: false, // Set to false so that JQuery doesn't convert the data object into a serialized parameter string, i.e. just send the raw data
			data: body,
			username: username,
			password: password,
//			data: '{ "comment" }',
			beforeSend: function (xhr) {
				xhr.setRequestHeader("Authorization", make_base_auth(username, password)); 
			},
//			beforeSend: function(xhr) {
//				xhr.setRequestHeader("Authentication", "Basic " + encodeBase64(username + ":" + password)); //May need to use "Authorization" instead
//			},
			success: function (data){
				
				$('#submit_call').empty();
				$('#submit_call').append('<br /><h4>Call:</h4><div class="span11"><div class="alert alert-success">' + url + '</div></div><br />');

//				alert("data -> " + data);
//				var submitdata = JSON.stringify(data, null, "\t");
				var submitdata = data;
				submitdata = submitdata.replace(/\\"/g, '"');
				submitdata = '<pre>' + submitdata + '</pre>';
				$('#submit_result').empty();
				$( "#submit_result" ).html('<br /><br /><br /><h4>Response (200):</h4><div class="pagination-centered">' + submitdata + '</div>');
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
//				var json = $.parseJSON(XMLHttpRequest.responseText);
				var error = XMLHttpRequest.responseText;

				$('#submit_call').empty();
				$('#submit_call').append('<br /><h4>Call:</h4><div class="span9 offset1"><div class="alert alert-success">' + url + '</div></div><br />');

//				alert("data -> " + data);
//				var submitdata = JSON.stringify(data, null, "\t");

//				error = error.replace(/\\"/g, '"');
				var error_code = XMLHttpRequest.status;
				error = '<pre>' + error + '</pre>';
				$('#submit_result').empty();
				$( "#submit_result" ).html('<br /><br /><br /><h4>Response (' + error_code + ') :</h4><div class="pagination-centered">' + error + '</div>');
				

//				alert("Error:" + json);
//				alert("Status: " + textStatus);
//				alert("Error: " + errorThrown); 
			}
		});
	});
});

$(document).ready(function(){
	$('#delete_button').on('click', function(e) {
		e.preventDefault();
//		var variant_id = document.getElementById("variant_id").value;
		var username = document.getElementById("delete_username").value;
		var password = document.getElementById("delete_password").value;
		var body = $('textarea#delete_body').val();
		var url = baseurl + "api/variants/delete";
//		var url = baseurl + "api/variants/delete/" + variant_id;
//		alert("body -> " + body);
//		alert("username " + username + " password " + password);
//		alert("s -> " + url);
		
		$.ajax ({
			type: "POST",
			url: url,
//			dataType: 'json',
			async: true,
//			contentType: "text/xml",
//			contentType: "application/xml",
			contentType: "application/json",
//			contentType: "text/html",
			processData: false, // Set to false so that JQuery doesn't convert the data object into a serialized parameter string, i.e. just send the raw data
			data: body,
			username: username,
			password: password,
//			data: '{ "comment" }',
			beforeSend: function (xhr) {
				xhr.setRequestHeader("Authorization", make_base_auth(username, password)); 
			},
//			beforeSend: function(xhr) {
//				xhr.setRequestHeader("Authentication", "Basic " + encodeBase64(username + ":" + password)); //May need to use "Authorization" instead
//			},
			success: function (data){
				
				$('#delete_call').empty();
				$('#delete_call').append('<br /><h4>Call:</h4><div class="span9 offset1"><div class="alert alert-success">' + url + '</div></div><br />');

//				alert("data -> " + data);
//				var submitdata = JSON.stringify(data, null, "\t");
				var deletedata = data;
				deletedata = deletedata.replace(/\\"/g, '"');
				deletedata = '<pre>' + deletedata + '</pre>';
				$('#delete_result').empty();
				$( "#delete_result" ).html('<br /><br /><br /><h4>Response (200):</h4><div class="pagination-centered">' + deletedata + '</div>');
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
//				var json = $.parseJSON(XMLHttpRequest.responseText);
				var error = XMLHttpRequest.responseText;

				$('#delete_call').empty();
				$('#delete_call').append('<br /><h4>Call:</h4><div class="span9 offset1"><div class="alert alert-success">' + url + '</div></div><br />');

//				alert("data -> " + data);
//				var submitdata = JSON.stringify(data, null, "\t");

//				error = error.replace(/\\"/g, '"');
				var error_code = XMLHttpRequest.status;
				error = '<pre>' + error + '</pre>';
				$('#delete_result').empty();
				$( "#delete_result" ).html('<br /><br /><br /><h4>Response (' + error_code + ') :</h4><div class="pagination-centered">' + error + '</div>');
				

//				alert("Error:" + json);
//				alert("Status: " + textStatus);
//				alert("Error: " + errorThrown); 
			}
		});
	});
});

// Autocomplete lookup function for search modal in navbar
$(document).ready(function() {
	$(function() {
		$("#variants_search_term").autocomplete({
			source: function(request, response) {
				$.ajax({url: baseurl + 'discover/lookup',
				data: {term: $("#variants_search_term").val()},
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

function formatXml (xml) {
        var reg = /(>)(<)(\/*)/g;
        var wsexp = / *(.*) +\n/g;
        var contexp = /(<.+>)(.+\n)/g;
        xml = xml.replace(reg, '$1\n$2$3').replace(wsexp, '$1\n').replace(contexp, '$1\n$2');
        var pad = 0;
        var formatted = '';
        var lines = xml.split('\n');
        var indent = 0;
        var lastType = 'other';
        // 4 types of tags - single, closing, opening, other (text, doctype, comment) - 4*4 = 16 transitions 
        var transitions = {
            'single->single': 0,
            'single->closing': -1,
            'single->opening': 0,
            'single->other': 0,
            'closing->single': 0,
            'closing->closing': -1,
            'closing->opening': 0,
            'closing->other': 0,
            'opening->single': 1,
            'opening->closing': 0,
            'opening->opening': 1,
            'opening->other': 1,
            'other->single': 0,
            'other->closing': -1,
            'other->opening': 0,
            'other->other': 0
        };

        for (var i = 0; i < lines.length; i++) {
            var ln = lines[i];
            var single = Boolean(ln.match(/<.+\/>/)); // is this line a single tag? ex. <br />
            var closing = Boolean(ln.match(/<\/.+>/)); // is this a closing tag? ex. </a>
            var opening = Boolean(ln.match(/<[^!].*>/)); // is this even a tag (that's not <!something>)
            var type = single ? 'single' : closing ? 'closing' : opening ? 'opening' : 'other';
            var fromTo = lastType + '->' + type;
            lastType = type;
            var padding = '';

            indent += transitions[fromTo];
            for (var j = 0; j < indent; j++) {
                padding += '\t';
            }
            if (fromTo == 'opening->closing')
                formatted = formatted.substr(0, formatted.length - 1) + ln + '\n'; // substr removes line break (\n) from prev loop
            else
                formatted += padding + ln + '\n';
        }

        return formatted;
    };


function make_base_auth(user, password) {
  var tok = user + ':' + password;
  var hash = btoa(tok);
  return "Basic " + hash;
}


$(document).ready(function(){
//$(function() {
//	$('.row .btn').on('click', function(e) {
	$('#search_fields_button').on('click', function(e) {
		e.preventDefault();
		var url = baseurl + "discover/search_fields";
		$.get( url, function( data ) {
			var searchfieldsdata = '<pre>' + JSON.stringify(data, null, "\t") + '</pre>';

			$('#search_fields_result').empty();
			$( "#search_fields_result" ).html('<br /><h4>Response:</h4>' + searchfieldsdata);

		});
	});
});

$(document).ready(function(){
//$(function() {
//	$('.row .btn').on('click', function(e) {
	$('#sources_button').on('click', function(e) {
		e.preventDefault();
		var format =  $('#sources_format :selected').text();
		var url = baseurl + "discover/sources/" + format;
		$.get( url, function( data ) {
			if ( format == 'json' ) {
				var sourcesdata = JSON.stringify(data, null, "\t");
				sourcesdata = '<pre>' + sourcesdata + '</pre>';
			}
			else if ( format == 'tab' ) {
				var sourcesdata = '<pre>' + data + '</pre>';
				sourcesdata = sourcesdata.replace(/\n/g, '<br />');
				sourcesdata = sourcesdata.replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;');
					
			}
			else {
				sourcesdata = data;
			}

			$('#sources_result').empty();
			$( "#sources_result" ).html('<br /><h4>Response:</h4>' + sourcesdata);

		});
	});
});

$(document).ready(function(){
//$(function() {
//	$('.row .btn').on('click', function(e) {
	$('#individual_record_fields_button').on('click', function(e) {
		e.preventDefault();
		var url = baseurl + "discover/individual_record_fields";
		$.get( url, function( data ) {
			var individualrecordfieldsdata = '<pre>' + JSON.stringify(data, null, "\t") + '</pre>';

			$('#individual_record_fields_result').empty();
			$( "#individual_record_fields_result" ).html('<br /><h4>Response:</h4>' + individualrecordfieldsdata);

		});
	});
});

$(document).ready(function(){
	$('#search_results_fields_button').on('click', function(e) {
		e.preventDefault();
		var sharing_policy = $('#search_results_fields_sharing_policy :selected').text();
		var url = baseurl + "discover/search_results_fields/" + sharing_policy;
//		alert("s -> " + url);
		
		$.ajax ({
			type: "GET",
			url: url,
//			dataType: 'json',
			async: true,
			success: function (data){
				var searchresultsfieldsdata = '<pre>' + JSON.stringify(data, null, "\t") + '</pre>';

				$('#search_results_fields_result').empty();
				$( "#search_results_fields_result" ).html('<br /><h4>Response:</h4>' + searchresultsfieldsdata);
				

			}
		});
	});
});

function custom_select_val(select_elm, prompt_text){
	var val = prompt(prompt_text, '');
	$(select_elm).prepend($('<option>', { value : val, selected: true }).text(val)); 
}
