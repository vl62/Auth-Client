<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Records</li>
			</ul>  
		</div>  
	</div> 
	<div class="row-fluid">
		<h2>Records</h2>
		<hr>
		<table class="table table-bordered table-striped table-hover" id="variantsadmintable">
			<thead>
				<tr>
					<th>Source Name</th>
					<th>Source Description</th>
					<th>Record Count</th>
					<th>Action</th>
					<!--<th>Sharing Policy</th>-->
				</tr>
			</thead>
			<tbody>
				<?php $c = 0; ?>
				<?php $total_variants = 0; ?>
				<?php foreach ($sources->result() as $source): ?>
				<?php $c++; ?>
				<tr id="row<?php echo $source->source_id; ?>">
					<td><?php echo $source->name; ?></td>
					<td><?php echo $source->description; ?></td>
					<?php if ( $source->type != "api" && $source->type != "central" ): ?>
					<td>
						<?php if ( isset($variant_counts[$source->name]) ) { echo $variant_counts[$source->name]; $total_variants += $variant_counts[$source->name]; } else { echo "0"; }?>
					</td>
					<td>
						<?php if ( isset($variant_counts[$source->name]) && $variant_counts[$source->name] <= $this->config->item('max_variants') ): ?>
						<a href="#addVariantsModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add records to this source" data-original-title="Import Records" ><i class="icon-plus"></i></a>&nbsp;&nbsp;<a href="<?php echo base_url() . "variants/curate/" . $source->name;?>" rel="popover" data-content="Modify individual records for this source" data-original-title="Edit Records" ><i class="fa fa-pencil" style="color:black"></i></a>&nbsp;&nbsp;<a href="#validateVariantsModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Validate the HGVS nomenclature of variants with Mutalyzer" data-original-title="Validate Variants" ><i class="icon-check"></i></a>&nbsp;&nbsp;<a href="#linkModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Changes the link address for ALL records in this source (clicking brings up selection window). If you want to change the link for individual records, click on the curate/edit action." data-original-title="Set Link Address"><i class="fa fa-link" style="color:black"></i></a>&nbsp;&nbsp;<a href="#sharingPolicyModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Changes the sharing policy for ALL records in this source (clicking brings up selection window). If you want to change the sharing policy for individual records, click on the curate/edit action." data-original-title="Set Sharing Policy"><i class="icon-share"></i></a>&nbsp;&nbsp;<a href="<?php echo base_url() . "variants/delete/" . $source->name;?>" rel="popover" data-content="Delete ALL variants for this source" data-original-title="Remove Variants"><i class="icon-trash"></i></a>
						<?php elseif ( isset($variant_counts[$source->name]) && $variant_counts[$source->name] > $this->config->item('max_variants') ): ?>
						<a href="#addVariantsModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add records to this source" data-original-title="Import Records" ><i class="icon-plus"></i></a>&nbsp;&nbsp;<a href="#" rel="popover" data-content="Curating variants in sources with more than <?php echo $this->config->item('max_variants'); ?> records is currently not supported." data-original-title="Edit Records" ><i class="fa fa-pencil" style="color:black"></i></a>&nbsp;&nbsp;<a href="#linkModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Changes the link address for ALL records in this source (clicking brings up selection window). If you want to change the link address for individual records, click on the curate/edit action." data-original-title="Set Link Address"><i class="fa fa-link" style="color:black"></i></a>&nbsp;&nbsp;<a href="#sharingPolicyModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Changes the sharing policy for ALL records in this source (clicking brings up selection window). If you want to change the sharing policy for individual records, click on the curate/edit action." data-original-title="Set Sharing Policy"><i class="icon-share"></i></a>&nbsp;&nbsp;<a href="<?php echo base_url() . "variants/delete/" . $source->name;?>" rel="popover" data-content="Delete all records for this source" data-original-title="Delete Records"><i class="icon-trash"></i></a>
						<?php else: ?>
						<a href="#addVariantsModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add records to this source" data-original-title="Import Records" ><i class="icon-plus"></i></a>
						<?php endif; ?>
					</td>
					<?php // if ( isset($variant_counts[$source->name]) && $variant_counts[$source->name] > 0 ): ?>
					<!--<td>-->
						<!--<button class="btn btn-primary btn-small" href="#sharingPolicyModal<?php // echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Changes the sharing policy for ALL records in this source (clicking brings up selection window). If you want to change the sharing policy for individual records, click on the curate/edit action." data-original-title="Set Sharing Policy">Set Sharing Policy</button>-->
						<!--<button class="btn btn-primary btn-small" href="#linkModal<?php // echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Changes the link address for ALL records in this source (clicking brings up selection window). If you want to change the link address for individual records, click on the curate/edit action." data-original-title="Set Link Address">Set Link Address</button>-->
					<!--</td>-->
					<?php // else: ?>
<!--					<td>
						<button class="btn btn-primary btn-small disabled" data-toggle="button" rel="popover" data-content="There are currently no records in this source" data-original-title="Set Sharing Policy">Set Sharing Policy</button>
						<button class="btn btn-primary btn-small disabled" data-toggle="button" rel="popover" data-content="There are currently no records in this source" data-original-title="Set Sharing Policy">Set Link Address</button>
					</td>-->
					<?php // endif; ?>
					<?php else: ?>
					<td><a href="#" rel="popover" data-content="Data not available for federated or central sources." data-original-title="Cannot View" ><i class="icon-minus-sign"></i></a></td>
					<td><a href="#" rel="popover" data-content="Data not available for federated or central sources." data-original-title="Cannot View" ><i class="icon-minus-sign"></i></a></td>
					<td><a href="#" rel="popover" data-content="Data not available for federated or central sources." data-original-title="Cannot View" ><i class="icon-minus-sign"></i></a></td>
					<?php endif; ?>
					
				</tr>
				<?php endforeach; ?>
			</tbody>
			<!--<tfoot>-->
				<!--<tr>-->
					<!--<th></th>-->
					<!--<th></th>-->
					<!--<th>Total: <?php // echo $total_variants; ?></th>-->
					<!--<th></th>-->
					<!--<th></th>-->
				<!--</tr>-->
			<!--</tfoot>-->
		</table>
		<br />
		<?php $c = 0; ?>
		<?php foreach ($sources->result() as $source): ?>
		<?php $c++; ?>		
		<div id="addVariantsModal<?php echo $c; ?>" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 align="center" id="myModalLabel">Add Records To <?php echo $source->description; ?></h4>
			</div>
			<div class="modal-body">
				<div class="well">
					<!-- <p align="center">Select how you would like to import records for this source:</p><hr>
					<p align="center"><a href="<?php echo base_url() . "variants/add/" . $source->name;?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Manually enter records</a><br /><br /><i>Use a form to manually enter records one by one.</i></p> -->
					<hr>
					<p align="center"><a href="<?php echo base_url() . "variants/import/" . $source->name; ?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Bulk import records</a><br /><br /><i>Use a bulk import tool to upload multiple records at once (various formats accepted).</i></p>
					<hr>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
			</div>
		</div>
		<div id="validateVariantsModal<?php echo $c; ?>" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 align="center" id="myModalLabel">Validate records in <?php echo $source->description; ?></h4>
			</div>
			<div class="modal-body">
				<div class="well" id="report_download">
					<h4 align="center">You are about to validate all records in this source with the Mutalyzer webservice.</h4>
					<!--<br /><p align="center">Fetch genomic coordinates for all variants:</p>-->
					<?php
//						$options = array(
//						     'yes' => 'yes',
//						     'no' => 'no'
//						);
					?>
					<!--<p align="center">-->
					<?php
//						echo form_dropdown('mutalyzer_check_with_genomic_coordinates', $options, 'yes', 'id="mutalyzer_check_with_genomic_coordinates"');
					?>
					<!--</p>-->
					<br /><p align="center">Generate a Mutalyzer validation report (Excel):</p>
					<?php
						$options = array(
						     'yes' => 'yes',
						     'no' => 'no'
						);
					?>
					<p align="center">
					<?php
						echo form_dropdown('mutalyzer_check_with_report', $options, 'yes', 'id="mutalyzer_check_with_report"');
					?>
					</p>
					<div id="waitingmutalyzer<?php echo $c; ?>" class="span2 offset1 pagination-centered" style="display: none;">
						<p align="center"><img src="<?php echo base_url("resources/images/cafevariome/ajax-loader-alt.gif");?>" title="Loader" alt="Loader" /></p>
						<br />
					</div>
					<p align="center"><button onclick="validateVariantsInSourceWithMutalyzer('<?php echo $source->name;?>', '<?php echo $c;?>')" type="button" id="validate_variants_in_source_with_mutalyzer" class="btn btn-success">Go!</button></p>

				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>  
			</div>
		</div>
		<div id="sharingPolicyModal<?php echo $c; ?>" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Sharing Policy</h3>
			</div>
			<div class="modal-body">
				<div class="well">
					<p>Choose the sharing policy level you would like to set ALL records in <?php echo $source->name; ?> to:</p><hr>
					<?php
					$js = 'id="sharing_policy_' . $c . '"';
					$options = array('openAccess' => 'openAccess',
									 'restrictedAccess' => 'restrictedAccess',
									 'linkedAccess' => 'linkedAccess');
					echo form_dropdown('sharing_policy', $options, 'openAccess', $js);
					?>
					<br /><p>N.B. You can set the sharing policy on a fine grained per variant basis by clicking the edit action for this source.</p>
				</div>
			</div>
			<div class="modal-footer">
				<a onclick="changeSharingPolicy('<?php echo $source->name;?>', '<?php echo $c;?>');" href="#" class="btn btn-success">Confirm</a>  
				<a href="#" class="btn" data-dismiss="modal">Close</a>  
				<!-- <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Apply</button> -->
			</div>
		</div>
		<div id="linkModal<?php echo $c; ?>" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Link Address</h3>
			</div>
			<div class="modal-body">
				<div class="well">
					<p>Enter the link you would like to set ALL records in <?php echo $source->name; ?> to:</p><hr>
					<?php
					$data = array(
						'name'        => 'link',
						'id'          => 'link_' . $c
					);
					echo form_input($data);
					?>
				</div>
			</div>
			<div class="modal-footer">
				<a onclick="changeLink('<?php echo $source->name;?>', '<?php echo $c;?>');" href="#" class="btn btn-success">Confirm</a>  
				<a href="#" class="btn" data-dismiss="modal">Close</a>  
				<!-- <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Apply</button> -->
			</div>
		</div>
		<?php endforeach; ?>
		<div class="span12 pagination-centered"><a class="btn btn-primary btn-medium" href="<?php echo base_url('sources') ?>"><i class="icon-file icon-white"></i>  Edit sources</a><?php echo nbs(6); ?><?php if ( $this->config->item('atomserver_enabled') ): ?><a class="btn btn-primary btn-medium" href="<?php echo base_url() . "admin/submissions";?>" rel="popover" data-content="Delete or approve records that have been submitted through AtomServer" data-original-title="Variant Submissions"><i class="icon-file icon-white"></i>  Submissions</a><?php echo nbs(6); ?><?php endif; ?><a href="<?php echo base_url() . "admin";?>" class="btn" ><i class="icon-home"></i> Admin Dashboard</a></div>
		<br /><br /><br />
	</div>
</div>

