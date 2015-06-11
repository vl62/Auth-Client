<html><head></head>
	<body>
		<p>Account Activation</p>
		<p>An administrator has created an account for you using this email address.</p>
		<p>To allow us to verify your email address and finalise your account, please click the <?php echo anchor('auth/activate/' . $id . '/' . $activation, 'following link'); ?>.</p>
		<p>Once your email address has been verified you will be automatically logged in.</p>
		<p>For future reference you can use the following details to login (you may change your password in your profile page):</p>
		<p>Username: <?php echo $email; ?></p>
		<p>Password: <?php echo $password; ?></p>
	</body>
</html>
