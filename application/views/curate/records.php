<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "curate";?>">Curator Dashboard</a> <span class="divider">></span>  
				</li>
				<li class="active">Records</li>
			</ul>  
		</div>  
	</div> 
	<div class="row-fluid">
		<h2>Curate Records</h2>
		<hr>
		<table class="table table-bordered table-striped table-hover" id="variantsadmintable">
			<thead>
				<tr>
					<th>Source Name</th>
					<th>Source Description</th>
					<th>Record Count</th>
					<th>Action</th>
					<th>Sharing Policy</th>
				</tr>
			</thead>
			<tbody>
				<?php $c = 0; ?>
				<?php foreach ($sources->result() as $source): ?>
				<?php $c++; ?>
				<tr id="row<?php echo $source->source_id; ?>">
					<td><?php echo $source->name; ?></td>
					<td><?php echo $source->description; ?></td>
					<td>
						<?php if ( isset($record_counts[$source->name]) ) { echo $record_counts[$source->name]; } else { echo "0"; }?>
					</td>
					<td>
						<?php if ( isset($record_counts[$source->name]) && $record_counts[$source->name] <= $this->config->item('max_variants') ): ?>
						<a href="#addVariantsModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add records to this source" data-original-title="Import Records" ><i class="icon-plus"></i></a>&nbsp;&nbsp;<a href="<?php echo base_url() . "curate/curate_records/" . $source->name;?>" rel="popover" data-content="Modify individual records for this source" data-original-title="Curate Records" ><i class="icon-edit"></i></a>&nbsp;&nbsp;<a href="<?php echo base_url() . "variants/delete/" . $source->name;?>" rel="popover" data-content="Delete ALL records for this source" data-original-title="Remove Records"><i class="icon-trash"></i></a>
						<?php elseif ( isset($record_counts[$source->name]) && $record_counts[$source->name] > $this->config->item('max_variants') ): ?>
						<a href="#addVariantsModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add records to this source" data-original-title="Import Records" ><i class="icon-plus"></i></a>&nbsp;&nbsp;<a href="#" rel="popover" data-content="Curating records in sources with more than <?php echo $this->config->item('max_variants'); ?> records is currently not supported." data-original-title="Curate Records" ><i class="icon-edit"></i></a>&nbsp;&nbsp;<a href="<?php echo base_url() . "variants/delete/" . $source->name;?>" rel="popover" data-content="Delete all records for this source" data-original-title="Remove Records"><i class="icon-trash"></i></a>
						<?php else: ?>
						<a href="#addVariantsModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add records to this source" data-original-title="Import Records" ><i class="icon-plus"></i></a>
						<?php endif; ?>
					</td>
					<?php if ( isset($record_counts[$source->name]) && $record_counts[$source->name] > 0 ): ?>
					<td>
						<button class="btn btn-primary btn-small" href="#sharingPolicyModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Changes the sharing policy for ALL records in this source (clicking brings up selection window). If you want to change the sharing policy for individual records, click on the curate/edit action." data-original-title="Set Sharing Policy">Set Sharing Policy</button>
					</td>
					<?php else: ?>
					<td>
						<button class="btn btn-primary btn-small disabled" data-toggle="button" rel="popover" data-content="There are currently no records in this source" data-original-title="Empty record source">Set Sharing Policy</button>
					</td>
					<?php endif; ?>
				</tr>

				<?php endforeach; ?>
			</tbody>
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
					<p align="center">Select how you would like to import records for this source:</p><hr>
					<p align="center"><a href="<?php echo base_url() . "variants/add/" . $source->name;?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Manually enter records</a><br /><br /><i>Use a form to manually enter records one by one.</i></p>
					<hr>
					<p align="center"><a href="<?php echo base_url() . "variants/import/" . $source->name; ?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Bulk import records</a><br /><br /><i>Use a bulk import tool to upload multiple records at once (various formats accepted).</i></p>
					<hr>
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
					$options = array('openAccess' => 'OpenAccess',
									 'restrictedAccess' => 'restrictedAccess',
									 'linkedAccess' => 'linkedAccess');
					echo form_dropdown('sharing_policy', $options, 'openAccess', $js);
					?>
					<br /><p>N.B. You can set the sharing policy on a fine grained per record basis by clicking the edit action for this source.</p>
				</div>
			</div>
			<div class="modal-footer">
				<a onclick="changeSharingPolicy('<?php echo $source->name;?>', '<?php echo $c;?>');" href="#" class="btn btn-success">Confirm</a>  
				<a href="#" class="btn" data-dismiss="modal">Close</a>  
				<!-- <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Apply</button> -->
			</div>
		</div>
		<?php endforeach; ?>
		<div class="span12 pagination-centered"><p><a class="btn btn-primary btn-medium" href="<?php echo base_url('curate/sources') ?>"><i class="icon-file icon-white"></i>  Curate sources</a><?php echo nbs(6); ?><a href="<?php echo base_url() . "curate";?>" class="btn" ><i class="icon-home"></i> Curators Dashboard</a></p></div>
	</div>
</div>

