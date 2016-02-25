<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset2 pagination-centered">
			<div class="well">
				<h1>Login</h1>
				<p>Please login with your email address below.</p>
				<strong id="loginError" class="hide" style="color: red;"></strong>
				<?php echo form_open("auth_federated/signup", array('name' => 'loginUser')); ?>
				<?php echo form_hidden(array('installation_key' => $this->config->item('installation_key'))); ?>	
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
				<p><button type="submit" name="submit" onclick="login_user();" class="btn btn-large"><i class="icon-user"></i>  Login</button></p>
				<p><a href="forgot_password">Forgot your password?</a></p>
				<!-- <?php if ( $this->config->item('allow_registrations') ): ?><p><a href="signup">Register for a new account?</a></p><?php endif; ?> -->
				<?php if ( false ): ?><p><a href="signup">Register for a new account?</a></p><?php endif; ?>
				<!--<p><a href="openid">Login with OpenID</a>-->
			</div>
		</div>
	</div>
</div>
<?php echo form_close();?>