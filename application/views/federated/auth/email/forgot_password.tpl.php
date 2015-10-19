<html><head></head>
	<body>
		<p class="article-title" align="left">Reset Your Password</p>
		<p>You have requested to reset the password for <?php echo $identity; ?></p>
		<p>Please click this link to <?php echo anchor('auth/reset_password/' . $forgotten_password_code, 'Reset Your Password'); ?>.</p>
	</body>
</html>
