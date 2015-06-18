/* Set the defaults for DataTables initialisation */
$.extend( true, $.fn.dataTable.defaults, {
	"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
	"sPaginationType": "bootstrap",
	"oLanguage": {
		"sLengthMenu": "_MENU_ records per page"
	}
} );


/* Default class modification */
$.extend( $.fn.dataTableExt.oStdClasses, {
	"sWrapper": "dataTables_wrapper form-inline"
} );


/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings ) {
	return {
		"iStart":         oSettings._iDisplayStart,
		"iEnd":           oSettings.fnDisplayEnd(),
		"iLength":        oSettings._iDisplayLength,
		"iTotal":         oSettings.fnRecordsTotal(),
		"iFilteredTotal": oSettings.fnRecordsDisplay(),
		"iPage":          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
		"iTotalPages":    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
	};
};


/* Bootstrap style pagination control */
$.extend( $.fn.dataTableExt.oPagination, {
	"bootstrap": {
		"fnInit": function( oSettings, nPaging, fnDraw ) {
			var oLang = oSettings.oLanguage.oPaginate;
			var fnClickHandler = function ( e ) {
				e.preventDefault();
				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
					fnDraw( oSettings );
				}
			};

			$(nPaging).addClass('pagination').append(
				'<ul>'+
					'<li class="prev disabled"><a href="#">&larr; '+oLang.sPrevious+'</a></li>'+
					'<li class="next disabled"><a href="#">'+oLang.sNext+' &rarr; </a></li>'+
				'</ul>'
			);
			var els = $('a', nPaging);
			$(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
			$(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
		},

		"fnUpdate": function ( oSettings, fnDraw ) {
			var iListLength = 5;
			var oPaging = oSettings.oInstance.fnPagingInfo();
			var an = oSettings.aanFeatures.p;
			var i, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);

			if ( oPaging.iTotalPages < iListLength) {
				iStart = 1;
				iEnd = oPaging.iTotalPages;
			}
			else if ( oPaging.iPage <= iHalf ) {
				iStart = 1;
				iEnd = iListLength;
			} else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
				iStart = oPaging.iTotalPages - iListLength + 1;
				iEnd = oPaging.iTotalPages;
			} else {
				iStart = oPaging.iPage - iHalf + 1;
				iEnd = iStart + iListLength - 1;
			}

			for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
				// Remove the middle elements
				$('li:gt(0)', an[i]).filter(':not(:last)').remove();

				// Add the new list items and their event handlers
				for ( j=iStart ; j<=iEnd ; j++ ) {
					sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
					$('<li '+sClass+'><a href="#">'+j+'</a></li>')
						.insertBefore( $('li:last', an[i])[0] )
						.bind('click', function (e) {
							e.preventDefault();
							oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
							fnDraw( oSettings );
						} );
				}

				// Add / remove disabled classes from the static elements
				if ( oPaging.iPage === 0 ) {
					$('li:first', an[i]).addClass('disabled');
				} else {
					$('li:first', an[i]).removeClass('disabled');
				}

				if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
					$('li:last', an[i]).addClass('disabled');
				} else {
					$('li:last', an[i]).removeClass('disabled');
				}
			}
		}
	}
} );


/*
 * TableTools Bootstrap compatibility
 * Required TableTools 2.1+
 */
if ( $.fn.DataTable.TableTools ) {
	// Set the classes that TableTools uses to something suitable for Bootstrap
	$.extend( true, $.fn.DataTable.TableTools.classes, {
		"container": "DTTT btn-group",
		"buttons": {
			"normal": "btn",
			"disabled": "disabled"
		},
		"collection": {
			"container": "DTTT_dropdown dropdown-menu",
			"buttons": {
				"normal": "",
				"disabled": "disabled"
			}
		},
		"print": {
			"info": "DTTT_print_info modal"
		},
		"select": {
			"row": "active"
		}
	} );

	// Have the collection use a bootstrap compatible dropdown
	$.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
		"collection": {
			"container": "ul",
			"button": "li",
			"liner": "a"
		}
	} );
}


/* Table initialisation */
$(document).ready(function() {
	$('#example').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"sPaginationType": "bootstrap",
		"bStateSave": true,
		"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
		}, 
		"aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]]
//		"oTableTools": {
//			"sSwfPath": "media/swf/copy_csv_xls_pdf.swf"
//		}
	} );
} );

/* General (class) Table initialisation */
$(document).ready(function() {
	$('.general').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"sPaginationType": "bootstrap",
		"bStateSave": true,
		"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
		}, 
		"aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]]
//		"oTableTools": {
//			"sSwfPath": "media/swf/copy_csv_xls_pdf.swf"
//		}
	} );
} );

/* Sources Table  */
$(document).ready(function() {
	$('#userstable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": true, // Disable pagination
		"bStateSave": true,
		"aoColumns": [
						{ "sType": "numeric" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "bSortable": false, "bSearchable": false }
				]
	} );
} );


/* Sources Table  */
$(document).ready(function() {
	$('#sourcestable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": false, // Disable pagination
		"bStateSave": true,
		"aoColumns": [
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "numeric" },
						{ "sType": "string" },
						{ "bSortable": false, "bSearchable": false },
						{ "bSortable": false, "bSearchable": false }
				]
	} );
} );

/* Source Stats Table  */
$(document).ready(function() {
	$('#statstable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": false, // Disable pagination
		"bStateSave": true,
		"aoColumns": [
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "numeric" }
				]
	} );
} );

/* CRM Leads Table  */
$(document).ready(function() {
	var table = $('#crmtable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": true, // Disable pagination
		"bStateSave": true,
		"aoColumns": [
						{ "bSortable": false, "bSearchable": false },
						{ "sType": "numeric" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" }
				],
		"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
//			$('.editable').editable();
			$(".editable").editable({
				mode: "inline"
			});	

		}

	} );
	
//    $("#crmtable tfoot th").each( function ( i ) {
//        var select = $('<select><option value=""></option></select>')
//            .appendTo( $(this).empty() )
//            .on( 'change', function () {
//                var val = $(this).val();
// 
//                table.column( i )
//                    .search( val ? '^'+$(this).val()+'$' : val, true, false )
//                    .draw();
//            } );
// 
//        table.column( i ).data().unique().sort().each( function ( d, j ) {
//            select.append( '<option value="'+d+'">'+d+'</option>' )
//        } );
//    } );
	
} );



/* Phenotype Ontology Table  */
$(document).ready(function() {
	$('#phenotypeontologytable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": false, // Disable pagination
		"bStateSave": true,
		"aoColumns": [
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" }
				]
	});
});

/* Phenotype Local List Table  */
$(document).ready(function() {
	$('#phenotypelocallisttable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"sPaginationType": "bootstrap",
		"bStateSave": true,
		"aoColumns": [
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" }
				]
	});
});

/* Variants Table  */
$(document).ready(function() {
	// Custom sort for Cafe Variome IDs
	jQuery.fn.dataTableExt.oSort['cv-asc']  = function(a,b) {
		var x = $(a).text().match(/\d+/);
		var y = $(b).text().match(/\d+/);
		return x - y;
	};
	jQuery.fn.dataTableExt.oSort['cv-desc'] = function(a,b) {
		var x = $(a).text().match(/\d+/);
		var y = $(b).text().match(/\d+/);	
		return y - x;
	};
	
	$('#variantstable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bStateSave": true,
		"bSortClasses": false,
		"bProcessing": true,
		"aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
		"aoColumns": [
						{ "sType": "cv" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" }
					 ]
	});
} );

// Get the dynamic display fields for the table head first, then call the datatable function with these fields as the aoColumn so they are dynamically created
// The table data is then dynamically retrieved in the variants_datatable php function based on the display fields again and rendered
$(document).ready(function() {
//	var sSource = baseurl + 'discover/variants_datatable/';
	var sharing_policy = $('input#sharing_policy').val();
	var term = $('input#term').val();
	var source = $('input#source').val();
//	alert("sharing_policy -> " + sharing_policy + " term -> " + term + " source -> " + source);
	$.ajax({
		dataType: "json",
		type: "POST",
		data: {sharing_policy: sharing_policy},
		url: baseurl + 'discover/get_display_fields_for_datatable_head/',
		"success": function(data) {
//			alert("json test -> " + JSON.stringify(data));
//			var aoColumns = JSON.stringify(data.aoColumns);
			var aoColumns = data.aoColumns;
//			var columns = eval( '('+json+')' );
			jQuery.fn.dataTableExt.oSort['cv-asc']  = function(a,b) {
				var x = $(a).text().match(/\d+/);
				var y = $(b).text().match(/\d+/);
				return x - y;
			};
			jQuery.fn.dataTableExt.oSort['cv-desc'] = function(a, b) {
				var x = $(a).text().match(/\d+/);
				var y = $(b).text().match(/\d+/);
				return y - x;
			};
//			alert("aoColumns -> " + aoColumns);
//			console.log("aoColumns -> " + aoColumns);
//			alert("url -> " + baseurl + 'discover/variants_datatable/');
			var pathname = window.location.pathname;
//			alert("pathname -> " + pathname);
			$('#variantspagination').dataTable({
//				"sScrollY": "400px",
				"sScrollX": "100%",
//				"sScrollXInner": "110%",
				"bScrollCollapse": true,
				"bPaginate": true,
				"bServerSide": true,
				"sServerMethod": "POST",
				"bStateSave": true,
				"bProcessing": true,
						"oLanguage": {
					"sProcessing": "*** Variants are being loaded please wait ***"
				},
				"sServerMethod": "GET",
				"sAjaxSource": baseurl + 'discover/variants_datatable/',
				"fnServerParams": function(aoData) {
					aoData.push(
							{"name": "sharing_policy", "value": sharing_policy},
							{"name": "term", "value": term},
							{"name": "source", "value": source},
							{"name": "path", "value": pathname}
					);
				},
				"iDisplayLength": 10,
				"aoColumns": aoColumns,
//				[
//					{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Cafe Variome ID", "sType": "cv" },
//					{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Gene", "sType": "string" },
//					{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Reference", "sType": "string" },
//					{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "HGVS", "sType": "string" },
//					{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Phenotype", "sType": "string" },
//					{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Source", "sType": "string" }
//				],
				"aLengthMenu": [[5, 10, 20, 50, 75, 100, 200, 500, 1000], [5, 10, 20, 50, 75, 100, 200, 500, 1000]],
				"aaSorting": [[0, 'asc']]

			});
		
		}
	});
});


// Get the dynamic display fields for the table head first, then call the datatable function with these fields as the aoColumn so they are dynamically created
// The table data is then dynamically retrieved in the variants_datatable php function based on the display fields again and rendered
$(document).ready(function() {
//	var sSource = baseurl + 'discover/variants_datatable/';
	var sharing_policy = $('input#sharing_policy').val();
	var term = $('input#term').val();
	var source = $('input#source').val();
//	alert("sharing_policy -> " + sharing_policy + " term -> " + term + " source -> " + source);
	$.ajax({
		dataType: "json",
		type: "POST",
		data: {sharing_policy: sharing_policy},
		url: baseurl + 'discover/get_display_fields_for_datatable_head/',
		"success": function(data) {
//			alert("json test -> " + JSON.stringify(data));
//			var aoColumns = JSON.stringify(data.aoColumns);
			var aoColumns = data.aoColumns;
//			var columns = eval( '('+json+')' );
			jQuery.fn.dataTableExt.oSort['cv-asc']  = function(a,b) {
				var x = $(a).text().match(/\d+/);
				var y = $(b).text().match(/\d+/);
				return x - y;
			};
			jQuery.fn.dataTableExt.oSort['cv-desc'] = function(a, b) {
				var x = $(a).text().match(/\d+/);
				var y = $(b).text().match(/\d+/);
				return y - x;
			};
//			alert("aoColumns -> " + aoColumns);
//			console.log("aoColumns -> " + aoColumns);
//			alert("url -> " + baseurl + 'discover/variants_datatable/');
			var pathname = window.location.pathname;
//			alert("pathname -> " + pathname);
			$('#variantspaginationfederated').dataTable({
//				"sScrollY": "400px",
				"sScrollX": "100%",
//				"sScrollXInner": "110%",
				"bScrollCollapse": true,
				"bPaginate": true,
				"bServerSide": true,
				"sServerMethod": "POST",
				"bStateSave": true,
				"bProcessing": true,
						"oLanguage": {
					"sProcessing": "*** Variants are being loaded please wait ***"
				},
				"sServerMethod": "GET",
				"sAjaxSource": baseurl + 'discover_federated/variants_datatable/',
				"fnServerParams": function(aoData) {
					aoData.push(
							{"name": "sharing_policy", "value": sharing_policy},
							{"name": "term", "value": term},
							{"name": "source", "value": source},
							{"name": "path", "value": pathname}
					);
				},
				"iDisplayLength": 10,
				"aoColumns": aoColumns,
//				[
//					{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Cafe Variome ID", "sType": "cv" },
//					{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Gene", "sType": "string" },
//					{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Reference", "sType": "string" },
//					{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "HGVS", "sType": "string" },
//					{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Phenotype", "sType": "string" },
//					{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Source", "sType": "string" }
//				],
				"aLengthMenu": [[5, 10, 20, 50, 75, 100, 200, 500, 1000], [5, 10, 20, 50, 75, 100, 200, 500, 1000]],
				"aaSorting": [[0, 'asc']]

			});
		
		}
	});
});


//$(document).ready(function() {
//	
//	jQuery.fn.dataTableExt.oSort['cv-asc']  = function(a,b) {
//		var x = $(a).text().match(/\d+/);
//		var y = $(b).text().match(/\d+/);
//		return x - y;
//	};
//	jQuery.fn.dataTableExt.oSort['cv-desc'] = function(a,b) {
//		var x = $(a).text().match(/\d+/);
//		var y = $(b).text().match(/\d+/);	
//		return y - x;
//	};
//	
//	var pathname = window.location.pathname;
//	var oTable = $('#variantspagination').dataTable( {
////		"sScrollY": "400px",
//		"bPaginate": true,
//		"bServerSide": true,		
//		"bProcessing": true,
//		"bStateSave": true,
//		"bProcessing": true,
//		"oLanguage": {
//			"sProcessing": "*** Variants are being loaded please wait ***"
//		},
//		"sServerMethod": "GET",
////		"sPaginationType": "full_numbers",
//		"sAjaxSource": baseurl + 'discover/variants_datatable/',
//
////		"fnServerData": function (sSource, aoData, fnCallback) {
////			$.getJSON( sSource, aoData, function (json) { 
////				/* Get server data callback */
////				for(var i = 0; i < json.hiddenColumns.length; i++) {
////					patternsTable.fnSetColumnVis(json.hiddenColumns[i], false);
////				}
////				fnCallback(json)
////			});
////		},
//		"fnServerData": function ( sSource, aoData, fnCallback ) {
////			$.ajax({
////				dataType: "json",
////				url: sSource,
////				data: aoData,
////				"success": function(json) {
//////					alert("json -> " + JSON.stringify(json.columns));
////					var columns = [];
////					columns = JSON.stringify(json.columns);
////					
//////					oTable.fnSetColumnVis( 2, true);
//////					$('#variantspagination').dataTable( { "aoColumns": JSON.stringify(json.columns) });
////					fnCallback(json);
////				}
////			});
//			
//			$.getJSON( sSource, aoData, function (json) {
////				alert("json -> " + JSON.stringify(json.columns));
////				alert("sSource -> " + JSON.stringify(sSource));
////				alert("aoData -> " + JSON.stringify(aoData));
//				var cols = JSON.stringify(json.columns);
//				fnCallback(json);
//			});
//		},
//		"fnServerParams": function ( aoData ) {
//			aoData.push( { "name": "path","value": pathname }
////						 { "name": "columns", "value": JSON.stringify(columns)}
//					   );
//		},
//		"iDisplayLength": 10,
//		"aoColumns": 
////			columns
//		[
//			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Cafe Variome ID", "sType": "cv" },
//			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Gene", "sType": "string" },
//			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Reference", "sType": "string" },
//			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "HGVS", "sType": "string" },
//			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Phenotype", "sType": "string" },
//			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Source", "sType": "string" }
//		],
////		"aoColumns": [
////						{ "sType": "cv" },
////						{ "sType": "string" },
////						{ "sType": "string" },
////						{ "sType": "string" },
////						{ "sType": "string" },
////						{ "sType": "string" }
////					 ]
//		"aLengthMenu": [[5, 10, 20, 50, 75, 100, 200, 500, 1000], [5, 10, 20, 50, 75, 100, 200, 500, 1000]],
//		"aaSorting": [[0, 'asc']]
//
//	});
//});

/* Curate Variants Table  */
$(document).ready(function() {
	
	var source = $('input#source').val();
//	alert("source -> " + source);
	// Custom sort for Cafe Variome IDs
	jQuery.fn.dataTableExt.oSort['cv-asc']  = function(a,b) {
		var x = $(a).text().match(/\d+/);
		var y = $(b).text().match(/\d+/);
		return x - y;
	};
	jQuery.fn.dataTableExt.oSort['cv-desc'] = function(a,b) {
		var x = $(a).text().match(/\d+/);
		var y = $(b).text().match(/\d+/);	
		return y - x;
	};

	var pathname = window.location.pathname;
	$('#variantscuratetable').dataTable( {
		"sScrollX": "100%",
//		"sScrollXInner": "110%",
		"bScrollCollapse": true,
		"bPaginate": true,
		"bServerSide": true,		
		"bProcessing": true,
		"bStateSave": true,
		"bProcessing": true,
		"oLanguage": {
			"sProcessing": "*** Variants are being loaded please wait ***"
		},
		"sServerMethod": "GET",
//		"sPaginationType": "full_numbers",
		"sAjaxSource": baseurl + 'variants/curate_datatable/',
		"fnServerParams": function ( aoData ) {
			aoData.push(
							{"name": "source", "value": source},
							{"name": "path", "value": pathname}
						);
		},
		"iDisplayLength": 10,
		"aLengthMenu": [[5, 10, 20, 50, 75, 100, 200, 500, 1000], [5, 10, 20, 50, 75, 100, 200, 500, 1000]],
		"aaSorting": [[0, 'asc']],
		"aoColumns": [
			{ "bVisible": true, "bSearchable": false, "bSortable": false, "sTitle": "<input type='checkbox' id='selectall' />", "sType": "string" },
			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Cafe Variome ID", "sType": "cv" },
			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Gene", "sType": "string" },
			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Reference", "sType": "string" },
			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "HGVS", "sType": "string" },
			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Phenotype", "sType": "string" },
			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Sharing Policy", "sType": "string" },
			{ "bVisible": true, "bSearchable": false, "bSortable": false, "sTitle": "Actions", "sType": "string" }
		]
	});
	
});

	

///* Curate Variants Table  */
//$(document).ready(function() {
//	// Custom sort for Cafe Variome IDs
//	jQuery.fn.dataTableExt.oSort['cv-asc']  = function(a,b) {
//		var x = $(a).text().match(/\d+/);
//		var y = $(b).text().match(/\d+/);
//		return x - y;
//	};
//	jQuery.fn.dataTableExt.oSort['cv-desc'] = function(a,b) {
//		var x = $(a).text().match(/\d+/);
//		var y = $(b).text().match(/\d+/);	
//		return y - x;
//	};
//	
//	$('#variantscuratetable').dataTable( {
//		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
//		"bStateSave": true,
//		"bSortClasses": false,
//		"bProcessing": true,
//		"aLengthMenu": [[5, 10, 25, 50, 100, 200, 500, -1], [5, 10, 25, 50, 100, 200, 500, "All"]],
//		"aoColumns": [
//						null,
//						{ "sType": "cv" },
//						{ "sType": "string" },
//						{ "sType": "string" },
//						{ "sType": "string" },
//						{ "sType": "string" },
//						{ "sType": "string" },
//						{ "sType": "string" }
//					 ]
//	});
//} );

/* Curate Variants Table  */
$(document).ready(function() {	
	$('#submissionstable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bStateSave": true,
		"bSortClasses": false,
		"bProcessing": true,
		"aLengthMenu": [[5, 10, 25, 50, 100, 200, 500, -1], [5, 10, 25, 50, 100, 200, 500, "All"]],
		"aoColumns": [
						null,
						{ "sType": "string" },
						{ "sType": "string" },
						null,
						{ "sType": "string" },
					 ]
	});
} );

/* Variants Admin Table  */
/* Curate Variants Table  */
$(document).ready(function() {
	$('#variantsadmintable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bStateSave": true,
		"bSortClasses": false,
		"bPaginate": false,
		"bProcessing": true,
//		"aLengthMenu": [[5, 10, 25, 50, 100, 200, 500, -1], [5, 10, 25, 50, 100, 200, 500, "All"]],
//		"iDisplayLength" : -1,
		"aoColumns": [
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "bSortable": false, "bSearchable": false },
						{ "bSortable": false, "bSearchable": false }
					 ]
	});
} );

/* Discover Table  */
$(document).ready(function() {
	$('#discovertable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": false, // Disable pagination
		"bStateSave": true
	} );
} );

/* General Table  */
$(document).ready(function() {
	$('.generaltable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": false, // Disable pagination
		"bStateSave": true
	} );
} );

/* General Table  */
$(document).ready(function() {
	$('.generaltablewithpagination').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": true, // Disable pagination
		"bStateSave": true
	} );
} );

/* DB Structure Table  */
$(document).ready(function() {
	$('#dbstructuretable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": false, // Disable pagination
		"bStateSave": true,
		"aoColumns": [
						{ "sType": "string" },
						{ "sType": "string" },
						{ "bSortable": false, "bSearchable": false }
				]
	} );
} );

/* Display Fields Table  */
//$(document).ready(function() {
//	$('.displayfieldstable').dataTable( {
//		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
//		"bPaginate": false, // Disable pagination
//		"bStateSave": true,
//		"bFilter": false,
//		"bInfo": false,
//		"aoColumns": [
//						{ "bSortable": false, "bSearchable": false },
//						{ "bSortable": false, "bSearchable": false },
//						{ "bSortable": false, "bSearchable": false },
//						{ "bSortable": false, "bSearchable": false },
//						{ "bSortable": false, "bSearchable": false }
//				]
//	} );
//} );

/* Add Federated Source Table  */
$(document).ready(function() {
	$('#addfederatedsourcetable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": false, // Disable pagination
		"bStateSave": true,
		"aoColumns": [
						{ "sType": "string" },
						null,
						null
				]
	} );
} );

/* Federated Node Setup Table  */
$(document).ready(function() {
	$('#federatedtable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": false, // Disable pagination
		"bStateSave": true,
		"aoColumns": [
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						null,
						null
				]
	} );
} );

/* Settings Table  */
$(document).ready(function() {
	$('#settingstable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": false, // Disable pagination
		"bStateSave": true,
		"aoColumns": [
						{ "bSortable": false, "bSearchable": false },
						{ "sType": "string" },
						{ "bSortable": false, "bSearchable": false }
				]
	} );
} );

/* Messages Inbox Table  */
$(document).ready(function() {
	$('#inboxtable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": false, // Disable pagination
		"bStateSave": true,
		"aoColumns": [
						{ "bSortable": false, "bSearchable": false },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "bSortable": false, "bSearchable": false },
						{ "bSortable": false, "bSearchable": false }
				]
	} );
} );

/* Sent Mail Table  */
$(document).ready(function() {
	$('#sentmailtable').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"bPaginate": false, // Disable pagination
		"bStateSave": true,
		"aoColumns": [
						{ "bSortable": false, "bSearchable": false },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" },
						{ "sType": "string" }
				]
	} );
} );