<html><head></head>
	<body>
		<p>Account Activation</p>
		<p>Thank you for signing up for an account.</p>
		<p>To allow us to verify your email address and finalize your account, please click the <?php echo anchor('auth/activate/' . $id . '/' . $activation, 'following link'); ?>.</p>
		<p>Once your email address has been verified you may login to your account using your submitted details.</p>
	</body>
</html>
