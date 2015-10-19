<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span10">
			<h2>Secure Sign-in</h2>
			<h3>The application "<?php echo $client_name; ?>" wants to connect to your account</h3>
			<p>If you click approve you will be redirected back to the application and it will be able to securely access your information and perform actions on your behalf.</p>

			<p>If you click deny then you will be redirected back to the application and there will be no exchange of data. You are free to approve this application again at a later date.</p>

			<?php echo form_open('oauth/authorise'); ?>
			<br />
			<p>
				<button type="submit" class="btn" value="Approve" name="doauth">Approve</button> or
				<button type="submit" class="btn" value="Deny" name="doauth">Deny</button>
			</p>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>