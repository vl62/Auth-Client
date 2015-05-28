<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset3">
			<h1>Change Password</h1>

			<div id="infoMessage"><?php echo $message; ?></div>

			<?php echo form_open('auth/reset_password/' . $code); ?>

			<p>
				New Password (at least <?php echo $min_password_length; ?> characters long): <br />
				<?php echo form_input($new_password); ?>
			</p>

			<p>
				Confirm New Password: <br />
				<?php echo form_input($new_password_confirm); ?>
			</p>

			<?php echo form_input($user_id); ?>
			<?php echo form_hidden($csrf); ?>
			
			<p><button type="submit" name="submit" class="btn btn-large"><i class="icon-edit"></i>  Change</button></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>