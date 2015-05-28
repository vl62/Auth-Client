<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Install Success | Cafe Variome</title>
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
				$install_dir = str_replace('success.php', '', $install_dir);
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
						<?php if ( array_key_exists("result", $_POST)): ?>
						<div class="alert alert-success"><br /><h4>Installation was successful</h4><br /></div>
						<!--<h3>Important Steps Before Proceeding:</h3>-->
						<!--<div class="alert alert-error"><p><strong>If your RewriteBase is not "cafevariome" (i.e. your Cafe Variome URL is http://localhost/cafevariome) then you must manually set the correct value for RewriteBase in your .htaccess file:<br /><br /><?php // echo $base_dir . ".htaccess";?></strong></p></div>-->
						<!--<div class="alert alert-error"><p><strong>If this is a live site then you should fully delete the install directory:<br /><br /><?php // echo $install_dir;?></strong></p></div>-->
						<!--<p><strong>N.B. You will not be able to access your installation until these final steps are complete.</strong></p>-->
						<p>Your installation should now be accessible at <a href="<?php echo $install_url; ?>"><?php echo $install_url; ?></a></p>
						<?php else: ?>
						<div class="alert alert-error">
							<p><br /><strong>Installation was not processed correctly, please <a href="<?php echo $install_url . "install"; ?>" >try again</a>.</strong></p>
							<br /><p>If you think you have been through the installation process correctly make sure the following directory is deleted:<br /><br /><?php echo $install_dir;?></p>
							<p><br /><strong><a href="mailto:admin@cafevariome.org">Email us</a> if you are unable to resolve the problem.</strong></p>
						</div>
						<?php endif; ?>
						<?php $headers =  "From: admin@cafevariome.org" . "\r\n"; ?>
						<?php $body = "Cafe Variome was successfully installed at $install_url\n"; ?>
						<?php if (! mail("owenlancaster@gmail.com", "Cafe Variome Installation", $body, $headers)): ?>
							<div class="alert alert-info">
								<p><strong>A confirmation email could not be sent</strong>, please check whether this server can send emails.</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div id="dialog" class="modal hide fade">
			<div class="modal-header">
				<a href="#" class="close">&times;</a>
				<h3> Showing Modal </h3>
			</div>
			<div class="modal-body">
				Installing:<br />
				<div id="progressbar"><div class="progress-label">Loading...</div></div>
<!--				<div id="waiting" class="span6 pagination-centered" style="display: none;">
					<img src="./assets/img/ajax-loader.gif" title="Loader" alt="Loader" />
					<div class="progress">
						<div id="progressbar"><div class="progress-label">Loading...</div></div>
					</div>
				</div>-->
			</div>
			<div class="modal-footer">
				<a href="#" data-dismiss="modal" data-target="#myModal" class="btn primary">Close</a>
			</div>
		</div>
	</body>
</html>
