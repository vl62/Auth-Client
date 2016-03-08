<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Groups</li>
			</ul>  
		</div>  
	</div>
	<div class="page-header">
		<h2>Network Groups</h2>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<ul class="nav nav-tabs">
                <li class="active" style="width: 50%;"><a href="#tab-sdg" data-toggle="tab">Source Display Group</a></li>
                <li style="width: 50%;"><a href="#tab-cdg" data-toggle="tab">Count Display Group</a></li>
            </ul>

            <div class="tab-content">
                <div id="tab-sdg" class="tab-pane active">
                	<?php if (array_key_exists('error', $groups)): ?>
					<div class="span12 pagination-centered"><p>There are currently no groups for any of the networks you belong to, create a new group for a network below.</p><br /></div>
					<?php else: ?>
					<table class="table table-bordered table-striped table-hover general">
						<thead>
							<tr>
								<th>Group Name</th>
								<th>Group Description</th>
								<th>Network Name</th>
								<th>Group Type</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($groups as $group): ?>
							<?php if($group['group_type'] == "master") continue; ?>
							<?php if($group['group_type'] == "count_display") continue; ?>
							<tr>
								<td><?php echo $group['name']; ?></td>
								<td><?php echo $group['description']; ?></td>
								<td><?php echo $group['network_name']; ?></td>
								<td><?php echo $group['group_type']; ?></td>
								<td>
								<?php $isMaster = $group['group_type'] == "master"; ?>
								<a rel="popover" data-content="Add/Remove users for this network group" data-original-title="Edit User Network Groups" href="<?php echo base_url('auth_federated/edit_user_network_groups') . '/' . $group['id'] . '/' . $isMaster?>" ><i class="icon-edit"></i></a>
								&nbsp;&nbsp;
									<?php if ( $group['group_type'] == "master" ): ?>
										<i class="icon-trash icon-grey-link" rel="popover" data-content="Unable to delete master network group" data-original-title="Delete Network Group"></i>
										<!-- Unable to delete master network group -->
									<?php elseif ( $group['number_of_sources'] > 0 ): ?>
										<i class="icon-trash icon-grey-link" rel="popover" data-content="Unable to delete group with sources assigned" data-original-title="Delete Network Group"></i>
										<!-- Unable to delete group with sources assigned -->
									<?php else: ?>
										<a href="<?php echo base_url('groups/delete_network_group'). "/" . $group['id']; ?>" ></i><i class="icon-trash"></i></a></td>
									<?php endif; ?>

							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
					<?php endif; ?>
                </div>

                <div id="tab-cdg" class="tab-pane">
                	<?php if (array_key_exists('error', $groups)): ?>
					<div class="span12 pagination-centered"><p>There are currently no groups for any of the networks you belong to, create a new group for a network below.</p><br /></div>
					<?php else: ?>
					<table class="table table-bordered table-striped table-hover general">
						<thead>
							<tr>
								<th>Group Name</th>
								<th>Group Description</th>
								<th>Network Name</th>
								<th>Group Type</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($groups as $group): ?>
							<?php if($group['group_type'] == "master") continue; ?>
							<?php if($group['group_type'] == "source_display") continue; ?>
							<tr>
								<td><?php echo $group['name']; ?></td>
								<td><?php echo $group['description']; ?></td>
								<td><?php echo $group['network_name']; ?></td>
								<td><?php echo $group['group_type']; ?></td>
								<td>
								<?php $isMaster = $group['group_type'] == "master"; ?>
								<a rel="popover" data-content="Add/Remove users for this network group" data-original-title="Edit User Network Groups" href="<?php echo base_url('auth_federated/edit_user_network_groups') . '/' . $group['id'] . '/' . $isMaster?>" ><i class="icon-edit"></i></a>
								&nbsp;&nbsp;
									<?php if ( $group['group_type'] == "master" ): ?>
										<i class="icon-trash icon-grey-link" rel="popover" data-content="Unable to delete master network group" data-original-title="Delete Network Group"></i>
										<!-- Unable to delete master network group -->
									<?php elseif ( $group['number_of_sources'] > 0 ): ?>
										<i class="icon-trash icon-grey-link" rel="popover" data-content="Unable to delete group with sources assigned" data-original-title="Delete Network Group"></i>
										<!-- Unable to delete group with sources assigned -->
									<?php else: ?>
										<a href="<?php echo base_url('groups/delete_network_group'). "/" . $group['id']; ?>" ></i><i class="icon-trash"></i></a></td>
									<?php endif; ?>

							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
					<?php endif; ?>
                </div>
            </div>
		</div>
	</div><!--/span-->
	<div class="span12 pagination-centered"><p><a href="<?php echo base_url() . "groups/create_network_group";?>" class="btn btn-primary btn-medium" ><i class="icon-th icon-white"></i> Create new network group</a></p></div>
	
	<div class="span10 offset1 pagination-centered"><br /><p>Network groups can be assigned to sources within you installation. Users who belong to those groups in your network are allowed access to restrictedAccess records in sources across the network.</p></div>
	<!--<div class="span12 pagination-centered"><br /><p>To assign groups, click the edit button in the <a href="<?php // echo base_url() . "auth/users";?>">users</a> or <a href="<?php // echo base_url() . "admin/sources";?>">sources</a> pages</p></div>-->
	<!-- <div class="span10 offset1 pagination-centered"><div id="infoMessage"><strong><h4><?php echo $this->session->flashdata('message'); ?></h4></strong></div></div> -->
	<hr>
</div><!--/.fluid-container-->

