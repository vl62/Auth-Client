<div class="container">
	<div class="row-fluid">
		<div class="span10 offset2 pagination-centered">
			<div class="well">
				<h3>User Profile</h3>
				<br />
				<table class="table table-striped table-bordered table-hover" >
					<tr>
						<td style="width: 22em;"><strong>ORCID</strong></td>
						<td><?php if ( $user['orcid'] ) { echo anchor('http://orcid.org/' . $user['orcid'], $user['orcid'], array('target' => '_blank', 'title' => "View ORCID profile")); } else { echo anchor('http://orcid.org/register', 'Register', array('target' => '_blank', 'title' => "Create ORCID profile")) . " for an ORCID or add your ORCID to your " . anchor('auth_federated/user_edit_profile/' . $user['id'], 'profile'); }?></td>
					</tr>
					<tr>
						<td><strong>Username</strong></td>
						<td><?php echo $user['username']; ?></td>
					</tr>
					<tr>
						<td><strong>First Name</strong></td>
						<td><?php echo $user['first_name']; ?></td>
					</tr>
					<tr>
						<td><strong>Last Name</strong></td>
						<td><?php echo $user['last_name']; ?></td>
					</tr>
					<tr>
						<td><strong>Institute/Laboratory/Company Name</strong></td>
						<td><?php echo $user['company']; ?></td>
					</tr>
					<tr>
						<td><strong>Email</strong></td>
						<td><?php echo $user['email']; ?></td>
					</tr>
					<tr>
						<td><strong>Current Groups</strong></td>
						<?php if (array_key_exists('error', $current_groups)): ?>
						<td>-</td>
						<?php else: ?>
						<td><?php foreach ($current_groups as $group) { echo $group->name . " (Network: " . $group->network_name . ")" . "<br />"; } ?>
						
						<?php endif; ?>
					</tr>
                                        
                                        <tr>
						<td><strong>Email Notification for Messages</strong></td>
						<td><?php echo $user['email_notification'] ? "ON" : "OFF"; ?></td>
					</tr>
				</table>
				<br />
				<!-- <p><a href="<?php echo base_url() . "auth_federated/user_edit_profile/" . $user['id']; ?>" class="btn btn-primary"><i class="icon-user"></i>  Edit Profile</a><?php echo nbs(6); ?><a href="<?php echo base_url() . "admin/data_access/" . $user['id']; ?>" class="btn"><i class="icon-download-alt"></i>  Data Access & Requests</a></p> -->
				<p><a href="<?php echo base_url() . "auth_federated/user_edit_profile/" . $user['id']; ?>" class="btn btn-primary"><i class="icon-user"></i>  Edit Profile</a><?php echo nbs(6); ?></p>
			</div>
		</div>
	</div>
</div>



