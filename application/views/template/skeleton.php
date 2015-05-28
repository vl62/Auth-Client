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

		<link rel="stylesheet" type="text/css" href="<?php echo site_url('css');?>" />
<!--		<link rel="stylesheet" href="<?php echo base_url(CSS."bootstrap-responsive.css");?>" />-->
		<link rel="stylesheet" href="<?php echo base_url(CSS."global.css");?>" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."prettify.css");?>" />
<!--		<link rel="stylesheet" href="<?php // echo base_url(CSS."jquery.ibutton.css");?>" />-->
		<link rel="stylesheet" href="<?php echo base_url(CSS."jquery-ui.css");?>" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."fileUploader.css");?>" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."DT_bootstrap.css");?>" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."jquery.cluetip.css");?>" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."bootstrap-arrows.css");?>" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."cookiecuttr.css");?>" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."jquery.treetable.css");?>" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."jquery.treetable.theme.default.css");?>" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."jquery.switchButton.css");?>" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."bootstrap-editable.css");?>" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."spectrum.css");?>" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."select2.css");?>" />
		<link rel="stylesheet" href="<?php echo base_url(CSS."jquery.growl.css");?>" />
		
		<!-- extra CSS-->
		<?php foreach($css as $c):?>
		<link rel="stylesheet" href="<?php echo base_url().CSS.$c?>">
		<?php endforeach;?>

		<script src="<?php echo base_url(JS."libs/modernizr-2.5.3.min.js");?>"></script>

		<!-- favicon and touch icons -->
		<?php // if ( $this->config->item('cafevariome_central') ): ?>
		<link rel="shortcut icon" href="<?php echo base_url(IMAGES.'ico/favicon.ico');?>" />
		<?php // endif; ?>
		<link rel="apple-touch-icon" href="<?php echo base_url(IMAGES.'ico/apple-touch-icon-precompresse.png');?>" />
		<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url(IMAGES.'ico/apple-touch-icon-57x57-precompressed.png');?>" />
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url(IMAGES.'ico/apple-touch-icon-72x72-precompressed.png');?>" />
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url(IMAGES.'ico/apple-touch-icon-114x114-precompressed.png');?>" />

		<script type="text/javascript">
			var baseurl = "<?php print base_url(); ?>";
		</script>
	<!-- note: jstree requires the addBack function that wasn't added to jQuery until 1.8, therefore I have changed 1.7.1 to 1.8.1 (tb143) -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
        <script src="<?php echo base_url(JS."jquery.jstree.js");?>"></script>
                             
		<script>window.jQuery || document.write('<script src="<?php echo base_url(JS."libs/jquery-1.7.1.min.js");?>"><\/script>')</script>           
		<script src="<?php echo base_url(JS."libs/underscore-1.3.1.min.js");?>"></script>
               <script src="<?php  echo base_url(JS."plugins.js");?>"></script>
		<script src="<?php echo base_url(JS."script.js");?>"></script>
		<script src="<?php echo base_url(JS."cafevariome.js");?>"></script>
		<script src="<?php echo base_url(JS."bootbox.js");?>"></script>
		<script src="<?php echo base_url(JS."jquery-ui.js");?>"></script>
		<script src="<?php echo base_url(JS."bootstrap-editable.js");?>"></script>
		<script src="<?php echo base_url(JS."jquery.dataTables.js");?>"></script>
		<script src="<?php echo base_url(JS."DT_bootstrap.js");?>"></script>
<!--		<script src="<?php //echo base_url(JS."jquery.ibutton.js");?>"></script> -->
		<script src="<?php echo base_url(JS."jquery.metadata.js");?>"></script>
		<script src="<?php echo base_url(JS."spectrum.js");?>"></script> 
		<script src="<?php echo base_url(JS."jquery.cluetip.js");?>"></script>
		<script src="<?php echo base_url(JS."highcharts.js");?>"></script>
		<script src="<?php echo base_url(JS."bootstrap-arrows.js");?>"></script>
		<script src="<?php echo base_url(JS."jquery.cookiecuttr.js");?>"></script>
		<script src="<?php echo base_url(JS."jquery.cookie.js");?>"></script>
		<script src="<?php echo base_url(JS."jquery.maskedinput.js");?>"></script>
		<script src="<?php echo base_url(JS."jquery.treetable.js");?>"></script>
		<script src="<?php echo base_url(JS."jquery.switchButton.js");?>"></script>
		<script src="<?php echo base_url(JS."select2.js");?>"></script>
		<script src="<?php echo base_url(JS."jquery.dataTables.delay.min.js");?>"></script>
		<!--<script src="<?php // echo base_url(JS."jquery.hideShowPassword.min.js");?>"></script>-->
		<script src="<?php echo base_url(JS."json3.js");?>"></script> 
		<script src="<?php echo base_url(JS."/tinymce/tinymce.min.js");?>"></script>        
		<!-- tb143 -->
		<script src="<?php echo base_url(JS."phenotypeList.js");?>"></script>
		<script src="<?php echo base_url(JS."form_complete.js");?>"></script>
		
		<script src="<?php echo base_url(JS."json3.js");?>"></script>
		<script src="<?php echo base_url(JS."jquery.fileUploader.js");?>"></script>
		<script src="<?php echo base_url(JS."jquery.growl.js");?>"></script>
		
		<!-- Include Font Awesome -->
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		
		<?php if ($this->config->item('messaging')): ?>
		<script type="text/javascript" src="<?php echo base_url(JS."jquery.tokeninput.js");?>"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(CSS."token-input.css");?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(CSS."token-input-facebook.css");?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(CSS."token-input-mac.css");?>" />
		<script type="text/javascript">
			$(document).ready(function () {
				$("#messaging-user-input").tokenInput("<?php echo base_url("messages/lookup_users");?>", {
					hintText: "Type a username",
					theme: "facebook"
				});
				
//				var navheight = $('#nav_container').height();
//				var padding = navheight * 1.3;
//				alert("height -> " + navheight + " padding -> " + padding);
//				alert("change to -> " + $("body").css("padding-top"));
//				$("body").css({"padding-top":padding + "px"});
			});
		</script>
		<?php endif; ?>
		
		
		<script>
			tinymce.init({
			mode : "exact",
			elements : "page_content,txtTextArea2",
			plugins: "preview image code visualblocks link",
//			selector:'textarea',
			toolbar: "preview bold italic hr styleselect link code visualblocks image bullist numlist alignleft aligncenter alignright alignjustify undo redo",
			menubar : false,
			verify_html : false
		});
		</script>
		
	</head>
	<body>

		<?php echo $body ?>
	
		<!-- extra js-->
		<?php foreach($javascript as $js):?>
		<script defer src="<?php echo base_url().JS.$js?>"></script>
		<?php endforeach;?>
		<?php if ( $this->config->item('google_analytics') ): ?>
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', '<?php echo $this->config->item('google_analytics'); ?>']);
			_gaq.push(['_trackPageview']);
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
		<?php endif; ?>
	</body>
</html>
