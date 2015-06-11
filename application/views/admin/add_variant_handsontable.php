<?php 
//print_r($variants);
//print_r($core_fields);

$columns = array();
$col_headers = array();
$header_width = array();
//$col_headers[] = "Select";
$col_headers[] = "ID";
//$columns[] = array('data' => "Select", 'type' => 'checkbox');
//$header_width[] = "60";
$columns[] = array('data' => "ID", 'type' => 'numeric');
$header_width[] = "60";
foreach ( $core_fields as $k => $v ) {
	
	if ( $k == 'sharing_policy' ) {
		$columns[] = array('data' => $v, 'type' => 'dropdown', 'source' => array('openAccess', 'linkedAccess', 'restrictedAccess'));
	}
//	elseif ( $k == 'phenotype' ) {
//					{
//                    data: "UserName", // from datasource
//                    editor: 'select2',
//                    select2Options: { // these options are the select2 initialization options 
//                        data: optionsList,
//                        dropdownAutoWidth: true,
//                        allowClear: true,
//                        width: 'resolve'
//                    }	
//		https://github.com/trebuchetty/Handsontable-select2-editor
//		$columns[] = array('data' => $v, 'editor' => 'select2', 'select2Options');
//	}
	else {
		$columns[] = array('data' => $v);
	}
	$col_headers[] = $v;
	$textstrlen = strlen($v);
	$imageFontInt = 5;
	$width = $textstrlen * imagefontwidth($imageFontInt);
	$header_width[] = $width;
//	$header_width[] = "60";
}
//foreach ( $table_structure as $header ) {
//	$col_headers[] = $header['name'];
//}

$data_final = array();

$row_headers = array();
foreach ( $variants as $id => $variant ) {
	$row_headers[] = array('type' => 'checkbox');
	$row = array();
	$row['Select'] = 'false';
	$row['ID'] = $this->config->item('cvid_prefix') . $id;

	foreach ( $core_fields as $k => $v ) {
		$row[$k] = $variant[$v];
	}
	$data_final[] = $row;
}



?>

<script>
$(document).ready(function () {
var data = <?php echo json_encode($data_final); ?>,
	searchFiled = document.getElementById('search_field'),
	container = document.getElementById('add_variant_handsontable'),
	exampleConsole = document.getElementById('example1console'),
    autosave = document.getElementById('autosave'),
    load = document.getElementById('load'),
    save = document.getElementById('save'),
    autosaveNotification,
	hot;
	
	hot = new Handsontable(container, {
		data: data,
		colHeaders: <?php echo json_encode($col_headers); ?>,
		rowHeaders: true,
		colWidths: <?php echo json_encode($header_width); ?>,
		minSpareRows: 1,
		columns: <?php echo json_encode($columns); ?>,
//		contextMenu: true,
		contextMenu: ['row_above', 'row_below', 'remove_row', 'undo', 'redo', 'commentsAddEdit', 'commentsRemove'],
		manualColumnResize: true,
		manualRowResize: true,
		search: true,
		fillHandle: true,
		comments: true,
		afterChange: function (change, source) {
			if (source === 'loadData') {
				return; //don't save this change
			}
			if (!autosave.checked) {
				return;
			}
			
//			alert(JSON.stringify(change) + " -> " + this.getDataAtRow(change[0][0]));
			if ( change[0][1] !== "Select" ) { // Ignore the changes to the checkbox
				var url = baseurl + 'admin/autosave_cell';
				$.ajax({
					url: url,
//					contentType: 'application/json',
					data: {change : change, row_data : this.getDataAtRow(change[0][0])},
					dataType: 'json',
					type: 'post',
					success: function(data) {
//						alert("QUERY RESPONSE: " + data);
//						window.location.reload(true);
					},
					error: function(httpRequest, textStatus, errorThrown) {
//						$('#ajax-loader').hide(500);
						alert("ERROR: no response data was received -> " + JSON.stringify(httpRequest));
					}
				});
			}
			
//			alert("autosave");
//			clearTimeout(autosaveNotification);
//			ajax('json/save.json', 'GET', JSON.stringify({data: change}), function (data) {
//				exampleConsole.innerText  = 'Autosaved (' + change.length + ' ' + 'cell' + (change.length > 1 ? 's' : '') + ')';
//				autosaveNotification = setTimeout(function() {
//					exampleConsole.innerText ='Changes will be autosaved';
//				}, 1000);
//			});
		},
		afterCreateRow: function (index, amount) {
			alert("change -> " + index + " -> " + amount);
		},
		cells: function (row, col, prop) {
			var cellProperties = {};
//			if (row === 0 || this.instance.getData()[row][col] === 'readOnly') {
//				cellProperties.readOnly = true; // make cell read-only if it is first row or the text reads 'readOnly'
//			}
//			if (row === 0) {
//				cellProperties.renderer = firstRowRenderer; // uses function directly
//			}
			
			if (col === 0) {
//				cellProperties.readOnly = true;
			}
			
//			else {
//				cellProperties.renderer = "negativeValueRenderer"; // uses lookup map
//			}

			return cellProperties;
		}
	});
	
//	hot.updateSettings({
//		contextMenu: {
//			callback: function (key, options) {
//				if (key === 'about') {
//					setTimeout(function () {
//						//timeout is used to make sure the menu collapsed before alert is shown
//						alert("This is a context menu with default and custom options mixed");
//					}, 100);
//				}
//			},
//			items: {
//				"row_above": {
//					disabled: function () {
//						//if first row, disable this option
//						return (hot.getSelected()[0]===0);
//					}
//				},
//				"row_below": {},
//				"hsep1": "---------",
//				"remove_row": {
//					name: 'Remove this row, ok?',
//					disabled: function () {
//						//if first row, disable this option
//						return  (hot.getSelected()[0] === 0);
//					}
//				},
//				"hsep2": "---------",
//				"about": {name: 'About this menu'}
//			}
//		}
//	});
	
//	Handsontable.Dom.addEvent(save, 'click', function() {
//		// save all cell's data
//		ajax('json/save.json', 'GET', JSON.stringify({data: hot.getData()}), function (res) {
//			var response = JSON.parse(res.response);
//  
//			if (response.result === 'ok') {
//				exampleConsole.innerText = 'Data saved';
//			}
//			else {
//				exampleConsole.innerText = 'Save error';
//			}
//		});
//	});

//	Handsontable.hooks.add('afterChange', function(changes, source) {
//		alert("Changed -> " + JSON.stringify(changes));
//		var endpoint_url = baseurl + 'discover/query';
//		$.ajax({
//			url: endpoint_url,
//			contentType: 'application/json',
//			data: {query : final_query},
//			dataType: 'html',
//			type: 'post',
//			success: function(data) {
////				alert("QUERY RESPONSE: " + data);
////				window.location.reload(true);
//			},
//			error: function(httpRequest, textStatus, errorThrown) {
////				$('#ajax-loader').hide(500);
//				alert("ERROR: no response data was received -> " + JSON.stringify(httpRequest));
//			}
//		});
//	});

	Handsontable.Dom.addEvent(autosave, 'click', function() {
		if (autosave.checked) {
			exampleConsole.innerText = 'Changes will be autosaved';
		}
		else {
			exampleConsole.innerText ='Changes will not be autosaved';
		}
	});

	Handsontable.Dom.addEvent(searchFiled, 'keyup', function (event) {
		var queryResult = hot.search.query(this.value);
		console.log(queryResult);
		alert("t");
		hot.render();
	});

//	Handsontable.hooks.add('afterRemoveRow', function(index, amount) {
//		alert("Removed row -> " + index + " -> " + amount);
//	});
//
//	Handsontable.hooks.add('afterCreateRow', function(index, amount) {
//		alert("Added new row -> " + index + " -> " + amount);
//	});
	

	function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
		td.style.fontWeight = 'bold';
		td.style.color = 'black';
		td.style.background = '#CEC';
	}
	
	
	function onlyExactMatch(queryStr, value) {
		return queryStr.toString() === value.toString();
	}


	

});

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
            <div class="well pagination-centered">
				<p>
					<!--<button class="btn" name="save" id="save">Save All</button>-->
					<!--style="display:none"-->
				<div><input type="checkbox" name="autosave" id="autosave" checked="checked" autocomplete="off" > Autosave</div>
				</p>
				<div class="row-fluid pagination-centered"><div class="span4 offset4"><pre id="example1console" class="console">Changes will be autosaved</pre></div></div>
                <div class="row">
					<input id="search_field" type="search" placeholder="Search">
					<div class="span11">
						<div id="add_variant_handsontable" class="handsontable" style="width: 910px; overflow: auto" data-originalstyle="width: 940px; height: 200px; overflow: auto"></div>
					</div>
				</div>
				<div class="row">
					<button name="dump" >Dump data to console</button>
				</div>
					<?php echo br(10); ?>
				</div>
			</div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
