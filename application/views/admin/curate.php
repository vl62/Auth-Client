<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">
				<?php if ($this->session->userdata('admin_or_curate') == "curate"): ?>
				<li>  
					<a href="<?php echo base_url() . "curate";?>">Curator Dashboard</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "curate/variants";?>">Records</a> <span class="divider">></span>
				</li>				
				<?php else: ?>
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "admin/variants";?>">Records</a> <span class="divider">></span>
				</li>
				<?php endif; ?>
				<li class="active">Edit</li>
			</ul>  
		</div>  
	</div>
	<input type="hidden" id="source_name" name="source_name" value="<?php echo $source; ?>" > <!-- Need to post source name so it can be passed back to delete_variant function in variants controller and used for lookup of whether curator has required access level -->
	<div class="row-fluid">
		<div class="span12 pagination-centered" id="table_container">
			<div class="well">
				<?php if(empty($variants)): ?>
				<h4>Sorry, there are no variants present in this source</h4>
				<?php else: ?>
				<h3>Edit <?php echo $source_full; ?></h3> 
				<div class="span7 offset2 pagination-centered"><div class="well"><p><button onclick="if(confirm('Are you sure you want to delete the selected variants?')) deleteVariantsMultiple();return false;" class="btn btn-primary btn-small" rel="popover" data-content="Use the checkboxes in the curate table to select multiple variants, or delete one variant at a time by using the trash action icon." data-original-title="Delete Multiple Variants"><i class="icon-remove"></i>  Delete Selected</button><?php echo nbs(6); ?><button href="#sharingPolicyModal" data-toggle="modal" data-backdrop="false" class="btn btn-primary  btn-small" rel="popover" data-content="Use the checkboxes in the curate table to select variants and click to choose which sharing policy those variants should be set to." data-original-title="Set Sharing Policies"><i class="icon-share-alt"></i>  Set Sharing Policy</button><?php echo nbs(6); ?><a href="#addVariantsModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add variants to this source" data-original-title="Import Variants"  class="btn btn-small btn-primary" ><i class="icon-plus"></i> Add Variants</a></p></div></div>
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="variantscuratetable">
					<thead>
						<tr>

						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				<br />
				<div class="span7 offset2 pagination-centered"><div class="well"><p><button onclick="if(confirm('Are you sure you want to delete the selected variants?')) deleteVariantsMultiple();return false;" class="btn btn-primary  btn-small" rel="popover" data-content="Use the checkboxes in the curate table to select multiple variants, or delete one variant at a time by using the trash action icon." data-original-title="Delete Multiple Variants"><i class="icon-remove"></i>  Delete Selected</button><?php echo nbs(6); ?><button href="#sharingPolicyModal" data-toggle="modal" data-backdrop="false" class="btn btn-primary btn-small" rel="popover" data-content="Use the checkboxes in the curate table to select variants and click to choose which sharing policy those variants should be set to." data-original-title="Set Sharing Policies"><i class="icon-share-alt"></i>  Set Sharing Policy</button><?php echo nbs(6); ?><a href="#addVariantsModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add variants to this source" data-original-title="Import Variants" class="btn btn-small btn-primary" ><i class="icon-plus"></i> Add Variants</a></p></div></div>
				<br /><br /><br /><br />
				<?php endif; ?>
			</div>
		</div>
	</div>
</div><!--/.container-->

<input type="hidden" id="source" name="source" value="<?php echo $source; ?>">


<script>
// Initialise the click listener for the ajax datatable (was done in the main cafevariome.js file but need to do here since the select all checkbox is added dynamically by datatables after dom load
$(document).ready(function() {
	// add multiple select / deselect functionality
	$("#selectall").click(function() {
		$('.case').attr('checked', this.checked);
	});
	// if all checkbox are selected, check the selectall checkbox  also        
	$(".case").click(function() {
		if ($(".case").length == $(".case:checked").length) {
			$("#selectall").attr("checked", "checked");
		}
		else {
			$("#selectall").removeAttr("checked");
		}      
	});
});
</script>
<div id="sharingPolicyModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Sharing Policy</h3>
	</div>
	<div class="modal-body">
		<div class="well">
			<p>Choose the sharing policy level you would like to set your selected variants to:</p><hr>
			<?php 
			$js = 'id="sharing_policy"'; 
			$options = array( 'openAccess'  => 'OpenAccess',
									'restrictedAccess'    => 'restrictedAccess',
									'linkedAccess'   => 'linkedAccess' );

			echo form_dropdown('sharing_policy', $options, 'openAccess', $js); ?>
		</div>
	</div>
	<div class="modal-footer">
		<a onclick="setSharingPolicyMultiple();" href="#" class="btn btn-success">Confirm</a>  
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
		<!-- <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Apply</button> -->
	</div>
</div>
	
<div id="addVariantsModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 align="center" id="myModalLabel">Add Variants To <?php echo $source_full; ?></h4>
	</div>
	<div class="modal-body">
		<div class="well">
			<p align="center">Select how you would like to import variants for this source:</p><hr>
			<p align="center"><a href="<?php echo base_url() . "variants/add/" . $source; ?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Manually enter variants</a><br /><br /><i>Use a form to manually enter variants one by one.</i></p>
			<hr>
			<p align="center"><a href="<?php echo base_url() . "variants/import/" . $source; ?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Bulk import variants</a><br /><br /><i>Use a bulk import tool to upload multiple variants at once (various formats accepted).</i></p>
			<hr>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>