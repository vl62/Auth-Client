<div class="container">
	<div class="row">
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "curate";?>">Curator Dashboard</a> <span class="divider">></span>  
				</li>
				<li class="active">Sources</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<h2>Curate Sources</h2>
		<hr>
		<table class="table table-bordered table-striped table-hover" id="sourcestable">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<!--<th>Type</th>-->
					<th>Record Count</th>
					<th>Assigned Group(s)</th>
					<th>Action</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php $c = 0; ?>
				<?php foreach ($sources->result() as $source): ?>
				<?php $c++; ?>
				<tr id="<?php echo $source->source_id; ?>">	
					<td><?php echo $source->name; ?></td>
					<td><?php echo $source->description; ?></td>
					<!--<td>-->
						<?php 
//							echo $source->type;
						?>
					<!--</td>-->
					<td>
						<?php if ( $source->type != "api" ): ?>
							<?php
							if ( isset($record_counts[$source->name]) ):
								echo $record_counts[$source->name];
							else:?>
								<a href="#addVariantsModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add records to this source" data-original-title="Import Records" ><i class="icon-plus"></i></a>
							<?php endif; ?>
						<?php else: ?>
								<a href="#" rel="popover" data-content="You cannot edit or import records for a federated source. This must be done via the source installation." data-original-title="Import Records" ><i class="icon-minus-sign"></i></a>
						<?php endif; ?>
					</td>
					<td>
						<?php
						if (array_key_exists($source->source_id, $source_groups)):
							foreach ($source_groups[$source->source_id] as $group):
								echo $group['group_name'] . "<br />";
//								echo anchor("auth/edit_group/" . $group['group_id'], $group['group_description']);
							endforeach;
						else:
							echo "No groups assigned";
						endif;
						?>
					</td>
					<td><a href="<?php echo base_url('curate/edit_source'). "/" . $source->source_id; ?>" rel="popover" data-content="Modify the information for this source" data-original-title="Edit Source"><i class="icon-edit"></i></a></td>
					<td>
						<?php if ( $source->status == "online" ): ?>
<!--						<input type="checkbox" id="online-offline" name="online-offline" class="{labelOn: 'Online', labelOff: 'Offline'} online-offline" checked/>-->
						<div class="slider source_status_slider" >
							<input id="<?php echo $source->source_id; ?>" name="<?php echo $source->source_id; ?>" class="online-offline" type="checkbox" checked>
						</div>
						<?php elseif ( $source->status == "offline" ): ?>
<!--						<input type="checkbox" id="online-offline" name="online-offline" class="{labelOn: 'Online', labelOff: 'Offline'} online-offline" unchecked/>-->
						<div class="slider source_status_slider" >						
							<input id="<?php echo $source->source_id; ?>" name="<?php echo $source->source_id; ?>" class="online-offline" type="checkbox" unchecked>
						</div>

						<?php endif; ?>

						
					</td>
				</tr>
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
				<?php endforeach; ?>
			</tbody>
		</table>
		<div id="sourceDisplay"></div>
		<br />
		<div class="span12 pagination-centered"><p><a class="btn btn-success btn-medium" href="<?php echo base_url('curate/records') ?>"><i class="icon-edit icon-white"></i>  Curate records in sources</a><?php echo nbs(6); ?><a href="<?php echo base_url() . "curate";?>" class="btn" ><i class="icon-home"></i> Curator Dashboard</a></p></div>
	</div>
</div>