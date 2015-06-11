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
							<th>Groups</th>
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
								<?php if( empty($user->groups)) { echo "-"; }?>
								<?php foreach ($user->groups as $group): ?>
									<?php echo $group->name; ?><br />
								<?php endforeach ?>
							</td>
							<td><?php if ( $user->id != 1 ) : ?><?php echo ($user->active) ? anchor("auth/deactivate/" . $user->id, 'Active') : anchor("auth/activate/" . $user->id, 'Inactive'); ?><?php else: ?>Active<?php endif; ?></td>
							<td><a rel="popover" data-content="Create/modify/delete users" data-original-title="Edit User" href="<?php echo base_url('auth/edit_user'). "/" . $user->id; ?>" ><i class="icon-edit"></i></a>&nbsp;&nbsp;&nbsp;<?php if ( $user->id != 1 ) : ?><a rel="popover" data-content="Permanently delete this user" data-original-title="Delete User" href="<?php echo base_url('auth/delete'). "/" . $user->id; ?>" ></i><i class="icon-trash"></i></a><?php endif; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			
			<div class="span12 pagination-centered"><p><a href="<?php echo base_url() . "auth/create_user";?>" class="btn btn-primary btn-medium" ><i class="icon-user icon-white"></i> Create new user</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "admin";?>" class="btn" ><i class="icon-home"></i> Admin Dashboard</a></p></div>
		</div>
	</div><!--/span-->
</div><!--/row-->

<hr>

</div><!--/.fluid-container-->