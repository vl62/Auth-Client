<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Install Errors | Cafe Variome</title>
		<link href="./assets/css/bootstrap.css" rel="stylesheet" media="screen">
		<link href="./assets/css/bootstrap-responsive.css" rel="stylesheet" media="screen">
		<link href="./assets/css/jquery-ui-1.10.3.custom.css" rel="stylesheet" media="screen">
		<link href="./assets/css/install.css" rel="stylesheet" media="screen">
		<link href="./assets/img/favicon.ico" rel="shortcut icon"/>
		<script src="./assets/js/jquery-1.10.2.js"></script>
		<script src="./assets/js/jquery-ui-1.10.3.custom.js"></script>
		<script src="./assets/js/jquery.validate.js"></script>
		<script src="./assets/js/bootstrap.js"></script>
		<script src="./assets/js/install.js"></script>
		<script type="text/javascript">
			<?php 
			if(isset($_SERVER['HTTP_HOST'])) {
				$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
				$base_url .= '://'. $_SERVER['HTTP_HOST'];
				$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
				$base_url = rtrim($base_url,"/");
				$paths = explode('/', $base_url);
//				$last_path = end($paths);
//				$last_path = $paths[-1];
//				$last_path = array_slice($paths, -1, 1, true);
				$end = end((explode('/', $base_url)));
				$install_url = str_replace($end, '', $base_url);
				$install_dir = __FILE__;
				$install_dir = str_replace('complete.php', '', $install_dir);
				$install_dir = rtrim($install_dir,"/");
				$base_dir = str_replace('install', '', $install_dir);

			}
			?>
			var baseurl = "<?php echo $base_url; ?>";
		</script>
		
	</head>
	<body>
		<div class="container">
			<div class="row-fluid">  
				<div id='main' class="span7 pagination-centered">
					<h2 class="muted"><img src="../resources/images/cafevariome/cafevariome-logo-full.png" /><br />Installation</h2>
					<div class="well">	

					<?php if ($_POST): ?>
						<div class="alert alert-error"><h4>There was a problem with the installation process.</h4><br /></div>
						<hr>
						<h4>The following errors were reported:</h4>
						<?php 
							foreach ( $_POST as $error_name => $error_message ) {
								echo "<div class='alert alert-info'><p><strong>Error name:</strong> $error_name<br /><br /><strong>Error message:</strong> $error_message</p></div>";
							}
						?>
						<hr>
						<div class="alert alert-success"><p>Please go back and <a href="<?php echo $install_url . "install"; ?>" >try again</a> or <a href="mailto:admin@cafevariome.org">contact us</a> if you are unable to resolve the problem.</p></div>
					<?php else: ?>
						<div class="alert alert-error">
							<h4>There was a problem with the installation process.</h4>
							<p><br /><a href="<?php echo $install_url . "install"; ?>" >Start install again</a></p>
							<p><br /><a href="mailto:admin@cafevariome.org">Email us</a> if you are unable to resolve the problem.</p>
						</div>
					<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>


