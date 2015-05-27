<script>
$(document).ready(function() {
	// Click event listener for save button
    $("#save_tree").click(function(){
		$("#local_list_jstree").jstree("deselect_all"); // Deselect all nodes
		var first_node = $('#local_list_jstree').jstree('select_node', 'ul > li:first'); // Fetch the first node so that the json data is fetch from first node onwards
		var tree_data = $('#local_list_jstree').jstree('get_json', first_node); // Get the JSON structure for the tree
		var json_tree_data = JSON.stringify(tree_data); // Convert to JSON string that can be passed to controller and then saved in CV
		alert("save -> " + json_tree_data);
		$.ajax({url: baseurl + 'admin/save_tree_data',
			data: {tree_data: json_tree_data},
			dataType: 'html',
			delay: 200,
			type: 'POST',
			success: function() {
//				window.location = baseurl + "admin/tree";
			}
		});
    }); 
	
	// Click event listener for add node button
	$("#add_child").click(function(){
		var selected_node_text = $('#local_list_jstree').jstree('get_selected').text();
//		alert("selected -> " + selected_node);
		if ( selected_node_text != '' ) { // If there's a node selected
			var selected_node = $('#local_list_jstree').jstree('get_selected');
//			alert("parent -> " + parent);
			$("#local_list_jstree").jstree("create_node", selected_node, "inside", { "data":"New node" }, false, true);
			$("#local_list_jstree").jstree("open_node", selected_node);
		}
		else {
			alert("Select where you would like to insert the new node");
//			var parent = $('#local_list_jstree').jstree('get_selected');
//			alert("parent -> " + parent);
//			$("#local_list_jstree").jstree("create_node", parent, "before", { "data":"New node" }, false, true);
		}
	});
	
	// Click event listener for remove node button
	$("#remove_node").click(function(){
		
		var selected_node = $('#local_list_jstree').jstree('get_selected').text();
//		alert("selected -> " + selected_node);
		if ( selected_node != '' ) {
			var parent = $('#local_list_jstree').jstree('get_selected');
			$("#local_list_jstree").jstree("delete_node",parent);
//			alert("Node was deleted");
		}
		else {
			alert("Select which node you would like to remove");
		}
	});
    
	// Double click listener (http://stackoverflow.com/questions/3674625/how-can-i-attach-custom-behaviour-to-a-double-click-in-jstree)
	// http://stackoverflow.com/questions/21000150/double-click-to-edit-node-before-rename-with-jstree
	$("#local_list_jstree").delegate("a","dblclick", function(e) {
//		var selected_node_text = $("#local_list_jstree").jstree('get_selected').children('a').text();
		var selected_node_text = $("#local_list_jstree").jstree('get_text'); 
//		alert("->" + selected_node_text);
		selected_node_text = selected_node_text.replace(/(^\s+|\s+$)/g, '');
		$('#new_node_name').val(selected_node_text);
//		$('#new_node_name').empty();
		$('#renameModal').modal({backdrop: false});
		$('#renameModal').modal('show');
	});
	
	// Click event listener for saving new node name modal
	$("#save_node_name").click(function(){
		var new_node_name = $('#new_node_name').val();
//		alert("new_node_name -> " + new_node_name);
		var selected_node = $('#local_list_jstree').jstree('get_selected');
		$("#local_list_jstree").jstree("rename_node",selected_node, new_node_name);

		$('#renameModal').modal('hide');
	});
	
	// Initialize jsTree and fetch local list dynamically
	$("#local_list_jstree").jstree({
		"themes" : {
			"theme" : "classic",
			"dots" : true,
			"icons" : false
		},
		"json_data" : {
			"ajax" : {
				"url" : baseurl + "admin/tree_data",
				"data" : function (n) {
					return { id : n.attr ? n.attr("id") : 0 }; 
				}
			}
//			"data": [
//				{
//					"data" : "A node",
//					"metadata" : { id : 23 },
//					"children" : [ "Child 1", "A Child 2" ]
//				},
//				{
//					"attr" : { "id" : "li.node.id1" },
//					"data" : {
//						"title" : "Long format demo",
//						"attr" : { "href" : "#" }
//					}
//				}
//			]
		},
		"plugins" : [ "themes", "json_data", "ui", "dnd" ]
	}).bind("select_node.jstree", function (e, data) { 
//		alert(jQuery.data(data.rslt.obj[0], "id"));
	}).on('loaded.jstree', function() {
		$("#local_list_jstree").jstree('open_all');
	});
	
});

</script>


<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "admin/phenotypes";?>">Phenotypes</a> <span class="divider">></span>
				</li>
				<li class="active">Local List Tree</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="well">
				<h2>Local List Tree</h2>
				<br />
				<div class="offset3">
					<div id="add_child" class="btn btn-info" rel="popover" data-content="Adds a new node at the position that is currently selected" data-original-title="Add Node">Add Node</div>
<!--					<div id="add_node" class="btn" rel="popover" data-content="Adds a new node at the position that is currently selected, if nothing is selected the node will be inserted at the end" data-original-title="Add Node">Add Node</div> -->
					<div id="remove_node" class="btn btn-info" rel="popover" data-content="Removes the currently selected node (any children of this node will also be removed)." data-original-title="Remove Node">Remove Node</div>
					&nbsp;&nbsp;&nbsp;&nbsp;<div id="save_tree" class="btn btn-primary" rel="popover" data-content="Save the structure of your tree" data-original-title="Save Tree">Save</div>
					<br /><br />
				</div>
				<div class="offset4">
					<div id="local_list_jstree"></div>
					<br />
					<p><small>Drag and drop nodes to organise the tree.<br />Double click a node to edit the name.</small></p>
				</div>
				<br />
				
			</div>
		</div>
	</div>
</div>

<div id="renameModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel">Rename Node</h4>
	</div>
	<div class="modal-body">
		<div class="pagination-centered">
			<p>Enter new node name:</p>
			<input type="text" id="new_node_name" name="new_node_name">
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" id="save_node_name">Save</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>