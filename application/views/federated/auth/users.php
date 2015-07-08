<script type="text/javascript">

// Ajax post
//$(document).ready(function() {
////	var user_name = $("input#name").val();
////	var password = $("input#pwd").val();
//	jQuery.ajax({
//		type: "POST",
//		url: "<?php // echo rtrim($this->config->item('auth_server'),"/"); ?>" + "/api/auth/get_all_users/format/json",
//		dataType: 'html',
//		data: {name: '', pwd: ''},
//		success: function (res) {
//			if (res) {
////				alert("response -> " + JSON.stringify(res));
//				alert("response -> " + res);
////				jQuery("div#result").show();
////				jQuery("div#value").html(res.username);
////				jQuery("div#value_pwd").html(res.pwd);
//			}
//		}
//	});
//
//});
</script>
<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">All Cafe Variome Users</li>
			</ul>  
		</div>  
	</div>
	<div class="page-header">
		<h2>All Cafe Variome Users</h2>
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
								<?php if ( isset($users_groups)): ?>
									<?php if (array_key_exists($user->id, $users_groups)): ?>
									<?php foreach ($users_groups[$user->id] as $group): ?>
											<?php echo $group['group_description'] . " (Network:" . $group['network_name'] . ")"; ?><br />
									<?php endforeach ?>
									<?php endif; ?>
								<?php endif; ?>
								<a rel="popover" data-content="Edit the network groups for a user" data-original-title="Edit User Network Groups" href="<?php echo base_url('auth_federated/edit_user_network_groups'). "/" . $user->id; ?>" ><i class="icon-edit"></i></a>
							</td>	
							<?php if ( $user->editable ): ?>
							<td><?php echo ($user->active) ? anchor("auth_federated/deactivate/" . $user->id, 'Active') : anchor("auth_federated/activate/" . $user->id, 'Inactive', array('id' => $user->id, 'class' => 'activateUser')); ?></td>
							<td><a rel="popover" data-content="Create/modify/delete users" data-original-title="Edit User" href="<?php echo base_url('auth_federated/edit_user'). "/" . $user->id; ?>" ><i class="icon-edit"></i></a>&nbsp;&nbsp;&nbsp;<a rel="popover" data-content="Permanently delete this user" data-original-title="Delete User" href="<?php echo base_url('auth_federated/delete'). "/" . $user->id; ?>" ></i><i class="icon-trash"></i></a></td>
							<?php else: ?>
							<td><?php if ($user->active) { echo 'Active'; } else { echo 'Inactive'; } ?></td>
							<td><i class="icon-edit icon-grey-link" rel="popover" data-content="Unable to edit the user since you are not an admin for the installation the user was created at" data-original-title="Edit User Disabled"></i>&nbsp;&nbsp;&nbsp;<i class="icon-trash icon-grey-link" rel="popover" data-content="Unable to delete the user since you are not an admin for the installation the user was created at" data-original-title="Delete User Disabled"></i></td>
							<?php endif; ?>
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