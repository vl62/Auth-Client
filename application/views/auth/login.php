<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset2 pagination-centered">
			<div class="well">
				<h1>Login</h1>
				<p>Please login with your email address below.</p>
				<div id="infoMessage"><b><?php echo $message;?></b></div>
				<?php echo form_open("auth/login");?>
				<p>
					<label for="identity">Email:</label>
					<?php echo form_input($identity); ?>
				</p>
				<p>
					<label for="password">Password:</label>
					<?php echo form_input($password); ?>
				</p>
				<p>
					<label for="remember">Remember Me:</label>
					<?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
				</p>
				<p><button type="submit" name="submit" class="btn btn-large"><i class="icon-user"></i>  Login</button></p>
				<p><a href="forgot_password">Forgot your password?</a></p>
				<?php if ( $this->config->item('allow_registrations') ): ?><p><a href="signup">Register for a new account?</a></p><?php endif; ?>
				<!--<p><a href="openid">Login with OpenID</a>-->
			</div>
		</div>
	</div>
</div>
<?php echo form_close();?>