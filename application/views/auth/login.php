<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset2 pagination-centered">
			<div class="well">
                            <p style="color: red">Login Services are temporarily limited</p>
                            <p style="color: red">Only local data will be available</p>
				<h1>Login</h1>
				<p>Please login with your email address below.</p>
				<div id="infoMessage"><b><?php echo $message;?></b></div>
				<?php echo form_open("auth/login");?>
				<p>
					<label for="identity">Email:</label>
					<?php echo form_input($identity); ?>
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