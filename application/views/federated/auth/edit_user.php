<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "auth_federated/users";?>">Users</a> <span class="divider">></span>
				</li>
				<li class="active">Edit User</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span8 offset2 pagination-centered">
			<div class="well">
				<h1>Edit User</h1>

				<p>Please enter the users information below.</p>

				<strong id="editUserError" class="hide" style="color: red;"></strong>
				<?php echo form_open("auth_federated/edit_user", array('name' => 'editUser')); ?>
				<div class="row">
					<label>ORCID:</label>
				</div>
				<div class="row">
					<div class="input-append">
						<?php echo form_input($orcid); ?>
						<span class="add-on"><a href="#" rel="popover" data-content="Enter your ORCID to link your ID to your account. Any information available in your public ORCID profile will be used to populate the correponding fields in the registration form. If you do not have an ORCID please go to http://www.orcid.org" data-original-title="ORCID"><i class="icon-question-sign"></i></a></span>
					</div>
					<button onclick="fetchORCID(); return false;" class="btn btn-small btn-primary" rel="popover" data-content="Click to fetch ORCID details." data-original-title="Fetch ORCID"><i class="icon-check"></i> Fetch ORCID details</button>
				</div>
				<br />
				<p>
					<label>Username:</label>
					<?php echo form_input($username); ?>
				</p>
				<p>
					First Name: <br />
					<?php echo form_input($first_name); ?>
				</p>

				<p>
					Last Name: <br />
					<?php echo form_input($last_name); ?>
				</p>

				<p>
					Institute/Laboratory/Company Name: <br />
					<?php echo form_input($company); ?>
				</p>
				<p>
					<label>Email:</label>
					<?php echo form_input($email); ?>
				</p>
				<p>
					Password: (if changing password)<br />
					<?php echo form_input($password); ?>
				</p>

				<p>
					Confirm Password: (if changing password)<br />
					<?php echo form_input($password_confirm); ?>
				</p>
				<?php echo form_hidden(array('installation_key' => $this->config->item('installation_key'))); ?>	
				<p>
					<?php if ( isset($groups)): ?>
					<?php error_log(print_r($groups, 1)); ?>
					Assign user to groups that given them openAccess to restrictedAccess records<br />(control click to select multiple): <br />
					<?php 
						$group_count = count($groups) + 1;
//						$curator_count = count($users) + 1;
					?>
					<select size="<?php echo $group_count; ?>" name="groups[]"  multiple="multiple">
						<?php foreach ($groups as $group ): ?>
							<option value="<?php echo $group['id'] . "," . $group['network_key'] . ""; ?>" <?php if (isset($selected_groups)) { if (array_key_exists($group['id'], $selected_groups)) { echo 'selected="selected"'; }} ?>><?php echo $group['description'] . " (Network:" . $group['network_name'] . ")"; ?></option>
						<?php endforeach; ?>
					</select>
					<?php else: ?>
					<p><span class="label label-important">There are no network groups available to this installation. <br />A user will not be able to log in until they been assigned to at least one group.</span></p>
					<?php endif; ?>
				</p>
				<p>
				<div class="is_admin_slider span5 offset3" >
					<label for="isadmin">Is Admin:</label>
					<input type="checkbox" name="isadmin" value="isadmin" <?php if($user->is_admin)  echo "checked"; else echo "";?>/>
				</div>
				</p>
                <br /><br /><br />	
				<?php echo form_hidden('id', $user->id); ?>
				<?php echo form_hidden($csrf); ?>
				<p><button type="submit" onclick="edit_user();" name="submit" class="btn btn-primary"><i class="icon-user"></i>  Save User</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "auth/users"; ?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
			</div>
		</div>
	</div>
</div>



<?php echo form_close(); ?>