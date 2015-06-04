<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Users</li>
			</ul>  
		</div>  
	</div>
	<div class="page-header">
		<h2>Users</h2>
	</div>
	<div id="infoMessage"><b><?php echo $message; ?></b></div>
	<div class="row-fluid">
        <div class="span12">
				<table class="table table-bordered table-striped table-hover" id="userstable">
					<thead>
						<tr>
							<th>ID</th>
							<th>ORCID</th>
							<th>Username</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Institute</th>
							<th>Email</th>
							<th>Network Groups</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($users as $user): ?>
						<tr>
							<td><?php echo $user->id; ?></td>
							<td><?php if ($user->orcid) { echo $user->orcid; } else { echo "-"; }?></td>
							<td><?php echo $user->username; ?></td>
							<td><?php echo $user->first_name; ?></td>
							<td><?php echo $user->last_name; ?></td>
							<td><?php echo $user->company; ?></td>
							<td><?php echo $user->email; ?></td>
							<td>
								<?php if (array_key_exists($user->id, $users_groups)): ?>
								<?php foreach ($users_groups[$user->id] as $group): ?>
										<?php echo $group['group_name']; ?><br />
								<?php endforeach ?>
								<?php else: ?>
									-
								<?php endif; ?>
							</td>
							<td><?php echo ($user->active) ? anchor("auth_federated/deactivate/" . $user->id, 'Active') : anchor("auth_federated/activate/" . $user->id, 'Inactive', array('id' => $user->id, 'class' => 'activateUser')); ?></td>
							<td><a rel="popover" data-content="Create/modify/delete users" data-original-title="Edit User" href="<?php echo base_url('auth_federated/edit_user'). "/" . $user->id; ?>" ><i class="icon-edit"></i></a>&nbsp;&nbsp;&nbsp;<?php if ( $user->id != 1 ) : ?><a rel="popover" data-content="Permanently delete this user" data-original-title="Delete User" href="<?php echo base_url('auth_federated/delete'). "/" . $user->id; ?>" ></i><i class="icon-trash"></i></a><?php endif; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			
			<div class="span12 pagination-centered"><p><a href="<?php echo base_url() . "auth_federated/create_user";?>" class="btn btn-primary btn-medium" ><i class="icon-user icon-white"></i> Create new user</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "admin";?>" class="btn" ><i class="icon-home"></i> Admin Dashboard</a></p></div>
		</div>
	</div><!--/span-->
</div><!--/row-->

<hr>

</div><!--/.fluid-container-->