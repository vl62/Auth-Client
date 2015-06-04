<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "auth/users";?>">Users</a> <span class="divider">></span>
				</li>
				<li class="active">Create User</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span9 offset2 pagination-centered">
			<div class="well">
				<h2>Create User</h2>
				<p>Please enter the users information below. Required fields are marked (*).</p>
				
                                <strong id="createUserError" class="hide" style="color: red;"></strong>
					
				<?php echo form_open("auth_federated/create_user", array('name' => 'createUser')); ?>
				<div class="row">
					<label>ORCID:</label>
				</div>
				
				<div class="row">
					<div class="input-append">
						<?php echo form_input($orcid); ?>
						<span class="add-on"><a href="#" rel="popover" data-content="Enter your ORCID to link your ID to your Cafe Variome account. Any information available in your public ORCID profile will be used to populate the correponding fields in the Cafe Variome registration form. If you do not have an ORCID please go to http://www.orcid.org" data-original-title="ORCID"><i class="icon-question-sign"></i></a></span>
					</div>
					<button onclick="fetchORCID();return false;" class="btn btn-small btn-primary" rel="popover" data-content="Click to fetch ORCID details." data-original-title="Fetch ORCID"><i class="icon-check"></i> Fetch ORCID details</button>
					<img src="<?php echo base_url();?>resources/images/cafevariome/ajax-loader.gif" id="loading-indicator" style="display:none" />
				</div>
				<br />
				<p>
					Username: (*)<br />
					<?php echo form_input($username); ?>
				</p>
					
				<p>
					First Name: (*)<br />
					<?php echo form_input($first_name); ?>
				</p>
					
				<p>
					Last Name: (*)<br />
					<?php echo form_input($last_name); ?>
				</p>
					
				<p>
					Institute/Laboratory/Company Name: (*)<br />
					<?php echo form_input($company); ?>
				</p>
					
				<p>
					Email: (*)<br />
					<?php echo form_input($email); ?>
				</p>
					
				<p>
					Password: (*)<br />
					<?php echo form_input($password); ?>
				</p>
					
				<p>
					Confirm Password: (*)<br />
					<?php echo form_input($password_confirm); ?>
				</p>
				<?php echo form_hidden(array('installation_key' => $this->config->item('installation_key'))); ?>	
				<p>
					Add to Group (control click to select multiple): <br />
					<?php if (array_key_exists('error', $groups)): ?>
				<p><span class="label label-important">There are no network groups available to this installation. <br />A user will not be able to log in until they been assigned to at least one group.</span></p>
					<br />
					<?php else: ?>
					<?php $count = count($groups) + 1; $additional = 'size="' . $count . '"'; ?>
					<?php // echo form_multiselect('groups[]', $groups, '2', $additional); ?>
					<select size="<?php echo $count; ?>" name="groups[]"  multiple="multiple">
						<?php foreach ($groups as $group): ?>
							<option value="<?php echo $group['id'] . "," . $group['network_key']; ?>" ><?php echo $group['description'] . " (Network:" . $group['network_name'] . ")"; ?></option>
						<?php endforeach; ?>
					</select>
					<?php endif; ?>
				</p>
                                
				<p>
					<label for="isadmin">Is Admin:</label>
					<?php echo form_checkbox('isadmin', '1', FALSE, 'id="isadmin"'); ?>
				</p>
                                
                                <p><button type="submit" name="submit" onclick="create_user();" class="btn btn-primary"><i class="icon-user icon-white"></i>  Create User</button><?php echo nbs(6); ?><button type="reset" class="btn"><i class="icon-remove-sign"></i> Clear</button><?php echo nbs(6); ?><a href="<?php echo base_url() . "auth_federated/users"; ?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
			</div>
		</div>
	</div>
</div>
			<?php echo form_close(); ?>