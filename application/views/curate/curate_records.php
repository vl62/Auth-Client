<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "curate";?>">Curator Dashboard</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "curate/records";?>">Records</a> <span class="divider">></span>
				</li>
				<li class="active">Curate</li>
			</ul>  
		</div>  
	</div>
	<input type="hidden" id="source_name" name="source_name" value="<?php echo $source; ?>" >
	<div class="row-fluid">
		<div class="span12 pagination-centered" id="table_container">
			<div class="well">
				<?php if(empty($records)): ?>
				<h4>Sorry, there are no records present in this source</h4>
				<?php else: ?>
				<h3>Curate <?php echo $source_full; ?></h3> 
				<div class="span7 offset2 pagination-centered"><div class="well"><p><button onclick="if(confirm('Are you sure you want to delete the selected records?')) deleteVariantsMultiple();return false;" class="btn btn-primary btn-small" rel="popover" data-content="Use the checkboxes in the curate table to select multiple records, or delete one record at a time by using the trash action icon." data-original-title="Delete Multiple Records"><i class="icon-remove"></i>  Delete Selected</button><?php echo nbs(6); ?><button href="#sharingPolicyModal" data-toggle="modal" data-backdrop="false" class="btn btn-primary  btn-small" rel="popover" data-content="Use the checkboxes in the curate table to select records and click to choose which sharing policy those records should be set to." data-original-title="Set Sharing Policies"><i class="icon-share-alt"></i>  Set Sharing Policy</button><?php echo nbs(6); ?><a href="#addVariantsModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add records to this source" data-original-title="Import Records"  class="btn btn-small btn-primary" ><i class="icon-plus"></i> Add Records</a></p></div></div>
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="variantscuratetable">
					<thead>
						<tr>
							<th><input type="checkbox" id="selectall" /></th>
							<th>Cafe Variome ID</th>
							<th>Gene</th>
							<th>HGVS</th>
							<th>Reference</th>
							<th>Phenotype(s)</th>
							<th>Sharing Policy</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						ksort($records);
						$c = 0;
						foreach ( $records as $record ):
							$c++;
						?>
						<tr>
							<td><?php $data = array( 'class' => 'case', 'name' => 'case', 'id' => $record['cafevariome_id'], 'value' => $record['cafevariome_id'], 'checked' => FALSE, 'style' => 'margin:10px',); echo form_checkbox($data); ?></td>
							<?php $cvid_link = base_url("/discover/variant/" . $record['cafevariome_id']); ?>
							<td id="<?php echo $this->config->item('cvid_prefix') . $record['cafevariome_id']; ?>"><a href="<?php echo $cvid_link; ?>" rel="http://www.cafevariome.org/php/variant.php?crid=<?php echo $record['cafevariome_id']; ?>"><?php echo $this->config->item('cvid_prefix') . $record['cafevariome_id']; ?></a></td>
							<td><?php if ($record['gene']) { echo $record['gene']; } else { echo "-"; } ?></td>
							<td><?php if ($record['hgvs']) { echo $record['hgvs']; } else { echo "-"; } ?></td>
							<td><?php if ($record['ref']) { echo $record['ref']; } else { echo "-"; } ?></td>
							<td><?php if ($record['phenotype']) { echo $record['phenotype']; } else { echo "-"; } ?></td>
							<td><?php if ($record['sharing_policy']) { echo $record['sharing_policy']; } else { echo "-"; } ?></td>
							<td><a href="<?php echo base_url('variants/edit_variant'). "/" . $record['source'] . "/" . $record['cafevariome_id']; ?>" rel="popover" data-content="Edit the fields of this record" data-original-title="Edit Record"><i class="icon-edit"></i></a><?php echo nbs(3); ?><a href="<?php echo base_url('variants/delete_variant'). "/" . $record['source'] . "/" . $record['cafevariome_id']; ?>" rel="popover" data-content="Delete this record." data-original-title="Delete Record"></i><i class="icon-trash"></i></a></td>
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				<br />
				<div class="span7 offset2 pagination-centered"><div class="well"><p><button onclick="if(confirm('Are you sure you want to delete the selected records?')) deleteVariantsMultiple();return false;" class="btn btn-primary  btn-small" rel="popover" data-content="Use the checkboxes in the curate table to select multiple records, or delete one record at a time by using the trash action icon." data-original-title="Delete Multiple Records"><i class="icon-remove"></i>  Delete Selected</button><?php echo nbs(6); ?><button href="#sharingPolicyModal" data-toggle="modal" data-backdrop="false" class="btn btn-primary btn-small" rel="popover" data-content="Use the checkboxes in the curate table to select records and click to choose which sharing policy those records should be set to." data-original-title="Set Sharing Policies"><i class="icon-share-alt"></i>  Set Sharing Policy</button><?php echo nbs(6); ?><a href="#addVariantsModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add records to this source" data-original-title="Import Records" class="btn btn-small btn-primary" ><i class="icon-plus"></i> Add Records</a></p></div></div>
				<br /><br /><br /><br />
				<?php endif; ?>
			</div>
		</div>
	</div>
</div><!--/.container-->
<div id="sharingPolicyModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Sharing Policy</h3>
	</div>
	<div class="modal-body">
		<div class="well">
			<p>Choose the sharing policy level you would like to set your selected records to:</p><hr>
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
		<h4 align="center" id="myModalLabel">Add Records To <?php echo $source_full; ?></h4>
	</div>
	<div class="modal-body">
		<div class="well">
			<p align="center">Select how you would like to import records for this source:</p><hr>
			<p align="center"><a href="<?php echo base_url() . "variants/add/" . $source; ?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Manually enter records</a><br /><br /><i>Use a form to manually enter records one by one.</i></p>
			<hr>
			<p align="center"><a href="<?php echo base_url() . "variants/import/" . $source; ?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Bulk import records</a><br /><br /><i>Use a bulk import tool to upload multiple records at once (various formats accepted).</i></p>
			<hr>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>