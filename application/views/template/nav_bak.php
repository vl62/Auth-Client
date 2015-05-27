<?php
$current_page = ucfirst($this->router->fetch_class());
$current_controller = $this->router->fetch_class();
?>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
        <div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url() . "resources/images/logos/" . $this->config->item('logo');?>"></a>
            <p class="navbar-text pull-right">
				<?php if (!$this->ion_auth->logged_in()): ?>
					<a href="<?php echo base_url() . "auth/login";?>" class="navbar-link">Login</a> | <a href="<?php echo base_url() . "auth/signup";?>" class="navbar-link">Register</a>
				<?php else: ?>
					<?php $user_id = $this->session->userdata( 'user_id' ); ?>
					<?php if ( $this->ion_auth->is_admin()): ?>
					<a href="<?php echo base_url(); ?>admin" class="navbar-link">Admin</a> | 
				<?php endif; ?>
					<a href="<?php echo base_url() . "auth/user_profile/" . $user_id;?>" class="navbar-link">Profile</a> | <a href="<?php echo base_url() . "auth/logout";?>" class="navbar-link">Logout</a>
				<?php endif; ?>
            </p>

			<ul class="nav">  
				<li <?php if (strcasecmp($current_controller, "home") == 0) { print 'class="active"';} ?>><a href="<?php echo base_url(); ?>home">Home</a></li>
				<li <?php if (strcasecmp($current_controller, "share") == 0) { print 'class="active"';} ?>><a href="<?php echo base_url(); ?>share">Share</a></li>
				<li <?php if (strcasecmp($current_controller, "discover") == 0) { print 'class="active"';} ?>><a href="<?php echo base_url(); ?>discover">Discover</a></li>
				<li class="dropdown">  
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">About<b class="caret"></b></a>  
					<ul class="dropdown-menu">  
						<li><a href="<?php echo base_url(); ?>about/api">API</a></li>
						<li><a href="<?php echo base_url(); ?>about/cafevariome">Cafe Variome</a></li>
						<li><a href="<?php echo base_url(); ?>about/faq">FAQ</a></li> 
						<li><a href="<?php echo base_url(); ?>about/gensearch">Gensearch</a></li>
						<li><a href="<?php echo base_url(); ?>about/inabox">In-a-box</a></li>
						<li><a href="<?php echo base_url(); ?>about/varioml">VarioML</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo base_url(); ?>about/contact">Contact</a></li>
					</ul>  
				</li>  
				<?php if ( $this->ion_auth->is_admin()): ?>
					<!--<li <?php if (strcasecmp($current_controller, "admin") == 0) { print 'class="active"';} ?>><a href="<?php echo base_url(); ?>admin">&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Admin</a></li>-->
				<?php endif; ?>
			</ul>
        </div>
	</div>
</div>