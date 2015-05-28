<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset3">
			<h1>Forgot Password</h1>
			<p>Please enter your email address so we can send you an email to reset your password.</p>

			<div id="infoMessage"><?php echo $message; ?></div>

			<?php echo form_open("auth/forgot_password"); ?>

			<p>
				Email Address: <br />
				<?php echo form_input($email); ?>
			</p>
			<p><button type="submit" name="submit" class="btn btn-large"><i class="icon-envelope"></i>  Submit</button></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>