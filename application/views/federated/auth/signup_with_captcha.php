<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span12 pagination-centered">
			<div class="well">
				<h3>Register for an account</h3>
				<hr>
				<p>Please enter your information below. Required fields are marked (*).</p><br />
				<div id="infoMessage"><b><?php echo $message; ?></b></div>

				<?php echo form_open("auth_federated/signup"); ?>
				<div class="row">
					<label>ORCID:</label>
				</div>
				<div class="row">
					<div class="input-append">
						<?php echo form_input($orcid); ?>
						<span class="add-on"><a href="#" rel="popover" data-content="Enter your ORCID to link your ID to your account. Any information available in your public ORCID profile will be used to populate the correponding fields in the registration form. If you do not have an ORCID please go to http://www.orcid.org" data-original-title="ORCID"><i class="icon-question-sign"></i></a></span>
					</div>
					<div id="orcidfetch" name="orcidfetch" >&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-small btn-primary" rel="popover" data-content="Click to fetch ORCID details." data-original-title="Fetch ORCID"><i class="icon-check"></i> Fetch ORCID details</button></div>
				</div>
				<br />
				<p>
					<label>Username: (*)</label>
					<?php echo form_input($username); ?>
				</p>
			
				<p>
					<label>First Name: (*)</label>
					<?php echo form_input($first_name); ?>
				</p>

				<p>
					<label>Last Name: (*)</label>
					<?php echo form_input($last_name); ?>
				</p>

				<p>
					<label>Institute/Laboratory/Company: (*)</label>
					<?php echo form_input($company); ?>
				</p>

				<p>
					<label>Email: (*)</label>
					<?php echo form_input($email); ?>
				</p>
<!--				<p>
					test: <br />-->
					<?php
//					$data = array(
//						'name'        => 'pwd',
//						'id'          => 'pwd',
//						'class'       => 'password_test',
//						'type'        => 'password',
//					);
//					echo form_input($data);
					?>
<!--				</p>-->
				<p>
					<label>Password: (*)</label>
					<!--class="password_test" style="float:left;"-->
					<?php echo form_input($password); ?>
				</p>

				<p>
					<label>Confirm Password: (*)</label>
					<?php echo form_input($password_confirm); ?>
				</p>
				<strong>Answer the following question:</strong> (*)<br />
				<div class="input-append">
					<div class="textfield">
						<?php echo form_label($captcha, 'captcha'); ?>
						<?php // echo form_error('captcha'); ?>
						<?php echo form_input('captcha'); ?>
						<span class="add-on"><a href="#" rel="popover" data-content="This uses the Text CAPTCHA api (http://textcaptcha.com/), a simple web service to generate text-based CAPTCHAs " data-original-title="Text CAPTCHA"><i class="icon-question-sign"></i></a></span>
					</div>
				<br />
				<br />
				</div>
				<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-user"></i>  Create User</button><?php echo nbs(6); ?><button type="reset" class="btn"><i class="icon-remove-sign"></i> Clear</button></p>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>