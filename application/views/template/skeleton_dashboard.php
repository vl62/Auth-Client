<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?php echo $title ?></title>

		<meta name="viewport" content="width=device-width">
		<meta name="keywords" content="<?php echo $keywords ?>" />
		<meta name="author" content="<?php echo $author ?>" />
		<meta name="description" content="<?php echo $description ?>" />

		<!-- start: Mobile Specific -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- end: Mobile Specific -->
	
		<!-- start: CSS -->
		<link id="bootstrap-style" href="<?php echo base_url(CSS."dashboard/bootstrap.css");?>" rel="stylesheet">
		<link href="<?php echo base_url(CSS."dashboard/bootstrap-responsive.css");?>" rel="stylesheet">
		<link id="base-style" href="<?php echo base_url(CSS."dashboard/style.css");?>" rel="stylesheet">
		<link id="base-style-responsive" href="<?php echo base_url(CSS."dashboard/style-responsive.css");?>" rel="stylesheet">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
	
		<!-- end: CSS -->
	

		<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<link id="ie-style" href="css/ie.css" rel="stylesheet">
		<![endif]-->
	
		<!--[if IE 9]>
			<link id="ie9style" href="css/ie9.css" rel="stylesheet">
		<![endif]-->
		
		<!-- start: Favicon -->
		<link rel="shortcut icon" href="<?php echo base_url(IMAGES.'ico/favicon.ico');?>" />
		<!-- end: Favicon -->
	</head>
	<body>
		<?php echo $body ?>
		<script src="<?php echo base_url(JS."dashboard/jquery-1.7.2.min.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery-ui-1.8.21.custom.min.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/modernizr.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/bootstrap.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.cookie.js");?>"></script>
		<script src='<?php echo base_url(JS."dashboard/fullcalendar.min.js");?>'></script>
		<script src='<?php echo base_url(JS."dashboard/jquery.dataTables.min.js");?>'></script>
		<script src="<?php echo base_url(JS."dashboard/excanvas.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.flot.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.flot.pie.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.flot.stack.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.flot.resize.min.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.chosen.min.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.uniform.min.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.cleditor.min.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.noty.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.elfinder.min.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.raty.min.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.iphone.toggle.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.uploadify-3.1.min.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.gritter.min.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.imagesloaded.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.masonry.min.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.knob.modified.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/jquery.sparkline.min.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/counter.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/retina.js");?>"></script>
		<script src="<?php echo base_url(JS."dashboard/custom.js");?>"></script>
	<!-- end: JavaScript-->
	</body>
</html>
