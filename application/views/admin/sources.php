<div class="container">
	<div class="row">
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Sources</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<h2>Sources</h2>
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
				<?php if ( $source->type == "api"): ?>
				<tr id="<?php echo $source->source_id; ?>" style="background-color:#57FEFF">
				<?php else: ?>
				<tr id="<?php echo $source->source_id; ?>">	
				<?php endif; ?>
					<td><?php echo $source->name; ?></td>
					<td><?php echo $source->description; ?></td>
					<!--<td>-->
						<?php 
//							echo $source->type;
						?>
					<!--</td>-->
					<td>
						<?php if ( $source->type != "api" && $source->type != "central" ): ?>
							<?php
							if ( isset($variant_counts[$source->name]) ):
								echo $variant_counts[$source->name];
							else:?>
								<a href="#addVariantsModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add records to this source" data-original-title="Import Records" ><i class="icon-plus"></i></a>
							<?php endif; ?>
						<?php else: ?>
								<a href="#" rel="popover" data-content="You cannot edit or import records for a federated source. This must be done via the source installation." data-original-title="Import Records" ><i class="icon-minus-sign"></i></a>
						<?php endif; ?>
					</td>
					<td>
						<?php
						if ( $source->type != "api" && $source->type != "central" ):
							if (array_key_exists($source->source_id, $source_groups)):
								foreach ($source_groups[$source->source_id] as $group):
									echo $group['group_name'] . "<br />";
//									echo anchor("auth/edit_group/" . $group['group_id'], $group['group_description']);
								endforeach;
							else:
								echo "No groups assigned";
							endif;
						else:?>
							<a href="#" rel="popover" data-content="You cannot assign groups federated source. All access to these records is controlled via the source installation." data-original-title="Cannot Edit Groups" ><i class="icon-minus-sign"></i></a>
						<?php endif; ?>
					</td>
					<td><?php if ( $source->type != "api" && $source->type != "central" ): ?><a href="#shareModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Invite a user to become a member of a group that has pre-approved access to restrictedAccess records in this source." data-original-title="Share Source"><i class="icon-share"></i></a>&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url('admin/edit_source'). "/" . $source->source_id; ?>" rel="popover" data-content="Modify curators, groups general information for this source" data-original-title="Edit Source"><i class="icon-edit"></i></a>&nbsp;&nbsp;&nbsp;<?php endif; ?><a href="<?php echo base_url('admin/delete_source'). "/" . $source->source_id . "/" . $source->name; ?>" rel="popover" data-content="Delete the source entry. N.B. records related to this source will not be deleted from the database." data-original-title="Delete Source"></i><i class="icon-trash"></i></a></td>
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
							<p align="center"><a href="<?php echo base_url() . "variants/add/" . $source->name;?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Manually enter records</a><br /><br /><i>Use a form to manually enter variants one by one.</i></p>
							<hr>
							<p align="center"><a href="<?php echo base_url() . "variants/import/" . $source->name; ?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Bulk import records</a><br /><br /><i>Use a bulk import tool to upload multiple records at once (various formats accepted).</i></p>
							<hr>
						</div>
					</div>
					<div class="modal-footer">
						<a href="#" class="btn" data-dismiss="modal">Close</a>  
					</div>
				</div>

				<div id="shareModal<?php echo $c; ?>" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 align="center" id="myModalLabel">Share <?php echo $source->description; ?></h4>
					</div>
					<div class="modal-body">
						<div class="well">
							<?php if (array_key_exists($source->source_id, $source_groups)): ?>
								<p align="center">Enter the email address and select which group the invited user will be added to:</p><hr>
								Email:<br />
								<input id="email<?php echo $c; ?>" name="email<?php echo $c; ?>" type="text" /><br /><br />
								Add to group:<br />
								
								<select name="groups<?php echo $c; ?>" id="groups<?php echo $c; ?>" >
								<?php foreach ($source_groups[$source->source_id] as $group): ?>
									<option value="<?php echo $group['group_id']; ?>"><?php echo $group['group_name']; ?></option>
								<?php endforeach; ?>
								</select>
								<br /><br /><p>N.B. If you want to add an existing user to a source group, go to the <a href="<?php echo base_url('auth/users'); ?>">edit users</a> admin page. To assign groups to this source go to the <a href="<?php echo base_url('admin/edit_source'). "/" . $source->source_id; ?>">edit source</a> admin page.</p>
							<?php else: ?>
								No groups have been assigned to this source, click the button below to edit the source and assign groups:<br /><br />
								<a href="<?php echo base_url('admin/edit_source'). "/" . $source->source_id; ?>" rel="popover" data-content="Modify curators, groups general information for this source" data-original-title="Edit Source"><i class="icon-edit"></i></a>
							<?php endif; ?>
							<div id="shareDiv<?php echo $c; ?>"></div>
						</div>
					</div>
					<div class="modal-footer">
						<a href="#" onclick="shareVariantsByEmail('<?php echo $c; ?>');" class="btn btn-success">Share</a>
						<a href="#" class="btn" data-dismiss="modal">Close</a>  
					</div>
				</div>
			
				<?php endforeach; ?>
			</tbody>
		</table>
		<div id="sourceDisplay"></div>
		<br />
		<div class="span12 pagination-centered"><a class="btn btn-primary btn-medium" href="#addSourceModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Fill in a form to add a new source to your installation." data-original-title="Add Source"><i class="icon-file icon-white"></i>  Add source</a><?php echo nbs(6); ?><a class="btn btn-primary btn-medium" href="#cloneSourceModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Copy all records from a source into a new source." data-original-title="Clone Source"><i class="icon-file icon-white"></i>  Clone source</a><?php echo nbs(6); ?><a class="btn btn-primary btn-medium" href="<?php echo base_url('admin/variants') ?>" data-content="Switches to the records admin interface to allow you to modify records." data-original-title="Edit Records"><i class="icon-edit icon-white"></i>  Edit records</a><?php echo nbs(6); ?><a href="<?php echo base_url() . "admin";?>" class="btn" ><i class="icon-home"></i> Admin Dashboard</a></div>
		<?php echo br(5); ?>
	</div>
</div>

<div id="cloneSourceModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 align="center" id="myModalLabel">Clone records from a source into a new source</h4>
	</div>
	<div class="modal-body">
		<div class="well">
			<table border="0">
				<tr>
					<td><strong>Clone source:</strong></td>
					<td>
						<select name="clone_source" id="clone_source" >
						<?php foreach ($sources->result() as $source): ?>
							<option value="<?php echo $source->name; ?>" ><?php echo $source->description; ?></option>
						<?php endforeach; ?>
						</select>
					</td>
				<tr><td><hr></td></tr>
				<tr>
					<td><strong>Destination source name:</strong></td>
					<td><input id="clone_name" name="clone_name" type="text" /></td>
				</tr>
				<tr>
					<td><strong>Destination source description:</strong>&nbsp;&nbsp;</td>
					<td><input id="clone_description" name="clone_description" type="text" /></td>
				</tr>
			</table>
			<hr>
			<p>N.B. All other metadata from the original source will be copied and left unchanged, e.g. source owner, email etc. However, assigned groups and curators will NOT be copied to the new source and must be edited in the source details as normal.</p>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" onclick="cloneSource();" class="btn btn-success">Clone</a>
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>

<div id="addSourceModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 align="center" id="myModalLabel">Add a new source</h4>
	</div>
	<div class="modal-body">
		<div class="well">
			<p align="center"><br /><a class="btn btn-primary btn-medium" href="<?php echo base_url('admin/add_source') ?>" ><i class="icon-file icon-white"></i>  Add local source</a><br /><br /><small>Create a new source in your local installation to which records can be added.</small></p>
			<?php if ( $this->config->item('federated') ): ?>
			<hr>
			<p align="center"><br /><a class="btn btn-primary btn-medium" href="<?php echo base_url('admin/add_federated_source') ?>" ><i class="icon-file icon-white"></i>  Add federated source</a><br /><br /><small>Select which federated sources are discoverable, N.B. you must have set up federated source details in the settings page of the admin dashboard.</small></p>
			<?php endif; ?>
			<?php if ( ! $this->config->item('cafevariome_central') ): ?>
			<hr>
			<p align="center"><br /><a class="btn btn-primary btn-medium" href="<?php echo base_url('admin/add_central_source') ?>" ><i class="icon-file icon-white"></i>  Add Cafe Variome Central source</a><br /><br /><small>Cafe Variome Central contains a number of sources of public records such as dbSNP, 1000 genomes project etc. You can add these sources and make records discoverable through your own Cafe Variome instance search interface by clicking this button and selecting which sources you want to add/remove.</small></p>
			<?php endif; ?>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>