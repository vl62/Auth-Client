<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset2 pagination-centered">
			<div class="well">
				<h3>Edit Profile</h3>
				<hr>
				<p>Please edit your profile information below.</p>
				<strong id="editUserProfileError" class="hide" style="color: red;"></strong>
				<?php echo form_open("auth_federated/user_edit_profile", array('name' => 'editUserProfile')); ?>
				<div class="row">
					<label>ORCID:</label>
				</div>
				<div class="row">
					<div class="input-append">
						<?php echo form_input($orcid); ?>
						<span class="add-on"><a href="#" rel="popover" data-content="Enter your ORCID to link your ID to your account. Any information available in your public ORCID profile will be used to populate the correponding fields in the registration form. If you do not have an ORCID please go to http://www.orcid.org" data-original-title="ORCID"><i class="icon-question-sign"></i></a></span>
					</div>
					<button onclick="fetchORCID();return false;" class="btn btn-small btn-primary" rel="popover" data-content="Click to fetch ORCID details." data-original-title="Fetch ORCID"><i class="icon-check"></i> Fetch ORCID details</button>
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
                                
                                <p>
					<label>Email Notification for Messages:</label>
                                        <input type="checkbox" name="email_notification" value="ON" <?php if($email_notification) echo "checked";?>>
				</p>
			</div>
			<?php echo form_hidden('id', $user['id']); ?>
			<?php echo form_hidden($csrf); ?>
                    <p><button type="submit" onclick="edit_user_profile();" name="submit" class="btn btn-primary"><i class="icon-user"></i>  Save Profile</button><?php echo nbs(6); ?><a href="<?php echo base_url() . "auth_federated/users";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
		</div>
	</div>
</div>



<?php echo form_close(); ?>