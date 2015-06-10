<?php
$current_cms_view = $this->session->userdata('current_cms_view');
$current_page = ucfirst($this->router->fetch_class());
$current_controller = $this->router->fetch_class();
if ( strtolower($current_page) == "discover" || strtolower($current_page) == "auth" || strtolower($current_page) == "admin" || strtolower($current_page) == "messages" ) {
	$current_cms_view = "";
}
?>

<div class="navbar navbar-inverse navbar-fixed-top">
<!--<div class="navbar navbar-default navbar-fixed-top">-->
	<div class="navbar-inner">
        <div class="container" id="nav_container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<?php if ( ! $this->config->item('cafevariome_central') ): ?>
			<a class="brand" href="<?php echo base_url() . "pages/home"; ?>"><img src="<?php echo base_url() . "resources/images/logos/" . $this->config->item('logo');?>"></a>
			<?php else: ?>
			<a class="brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url() . "resources/images/logos/" . $this->config->item('logo');?>"></a>
			<?php endif; ?>
            <p class="navbar-text pull-right">
				
<!--				<a href="#" id="myID" data-placement="bottom" >the popover link</a>
				<div id="myformIdorClassTitle" class="hide">some title</div>
				<div id="myFormIdorClassForm" class="hide">
					<form action="">
						 my form 
					</form>
				</div>-->
				
				<!--<a style="border-bottom: none;" href="<?php // echo base_url() . "discover"; ?>" data-toggle="modal" data-backdrop="false" data-content="Click to enter a search term for data discovery." data-original-title="Discovery Search" class="btn btn-small btn-success search-navbar" ><i class="icon-search icon-white"></i></a>&nbsp;&nbsp;-->
				<!--<a style="border-bottom: none;" href="#searchBarModal" data-toggle="modal" data-backdrop="false" data-content="Click to enter a search term for data discovery." data-original-title="Discovery Search" class="btn btn-small btn-success search-navbar" ><i class="icon-search icon-white"></i></a>&nbsp;&nbsp;-->
				<!--<a data-type="typeahead" style="border-bottom: none;" data-title="Enter a search term to discover data" class="btn btn-small btn-success search-navbar" id="search-popover" href="#"><i class="icon-search icon-white"></i></a>&nbsp;&nbsp;-->
				<!--<a title="Discover data" class="btn btn-small btn-success search-navbar" data-container="body" data-placement="bottom" rel="popover" data-content="Click to discover data" data-original-title="Discover Data" id="search-popover" href="#"><i class="icon-search icon-white"></i></a>&nbsp;&nbsp;-->
				<?php if (! $this->ion_auth->logged_in()): ?>	
					<?php if ( ! preg_match('/login/i', $this->uri->rsegment(2))): ?>
                                                <a class="btn btn-small" href="<?php echo base_url() . "auth_federated/login";?>" class="navbar-link" id="loginUser">Login</a>
						<?php if ( $this->config->item('allow_registrations') ): ?>
							<?php if ( ! preg_match('/signup/i', $this->uri->rsegment(2))): ?>
							 <a class="btn btn-small btn-primary" href="<?php echo base_url() . "auth_federated/signup";?>" class="navbar-link">Sign up</a>
							<?php endif; ?>
						<?php endif; ?>
					<?php else: ?>
						<?php if ( $this->config->item('allow_registrations') ): ?>
							<?php if ( ! preg_match('/signup/i', $this->uri->rsegment(2))): ?>
							<a class="btn btn-small btn-primary" href="<?php echo base_url() . "auth_federated/signup";?>" class="navbar-link">Sign up</a>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				<?php else: ?>
					<?php $user_id = $this->session->userdata( 'user_id' ); ?>
					<?php if ( (($this->session->userdata( 'controller' ) === "auth_federated") && $this->session->userdata( 'is_admin' )) || ($this->ion_auth->is_admin())): ?>
					<a data-placement="bottom" rel="popover" data-content="Administrator Dashboard" class="btn btn-small btn-primary" href="<?php echo base_url(); ?>admin" class="navbar-link"><i class="icon-cog icon-white"></i></a>  
					<?php endif; ?>
					<?php if ($this->ion_auth->in_group("curator")): ?>
					<a data-placement="bottom" rel="popover" data-content="Curator Dashboard" class="btn btn-small btn-primary" href="<?php echo base_url(); ?>curate" class="navbar-link"><i class="icon-edit icon-white"></i></a> 
					<?php endif; ?>
					<?php if ($this->config->item('messaging')): ?>
					<a data-placement="bottom" rel="popover" data-content="Messaging Dashboard" class="btn btn-small btn-primary" href="<?php echo base_url(); ?>messages" class="navbar-link"><i class="icon-envelope icon-white" ></i><small id="msgUnreadCount"></small></a> 
					<?php endif; ?>
					<a data-placement="bottom" rel="popover" data-content="User Profile" class="btn btn-small btn-primary" href="<?php echo base_url() . "auth_federated/user_profile/" . $user_id;?>" class="navbar-link"><i class="icon-user icon-white" ></i></a> <a class="btn btn-small btn-primary" href="<?php if($this->session->userdata('controller') === "auth_federated") echo base_url() . "auth_federated/logout"; else echo base_url() . "auth/logout"; ?>" class="navbar-link">Logout</a>
				<?php endif; ?>
            </p>

			<ul class="nav">
				<?php if ( ! $this->config->item('cafevariome_central') ): ?>
					<?php $this->load->model('cms_model'); ?>
					<?php foreach ( $menus as $menu_item ): ?>
						<?php $pages_for_menu = $this->cms_model->getPagesForMenu($menu_item['menu_name']);?>
						<?php if (preg_match('/\|/', $pages_for_menu)): ?>
							<?php $pages = explode('|', $pages_for_menu); ?>
							<?php foreach ( $pages as $page ): ?>
								<?php //echo "PAGE -> $page<br />"; ?>
							<?php endforeach; ?>
						<?php else: ?>
							<?php if ( strtolower($menu_item['menu_name']) == "discover" ): ?>
								<li <?php if ( strtolower($current_page) == "discover" ) { print 'class="active"'; } ?>><a href="<?php echo base_url() . "discover/"; ?>"><?php echo ucwords($menu_item['menu_name']); ?></a></li>
							<?php else: ?>
								<li <?php if (strtolower($current_cms_view) == strtolower($menu_item['menu_name'])) { print 'class="active"'; } ?>><a href="<?php echo base_url() . "pages/" . strtolower($menu_item['menu_name']); ?>"><?php echo ucwords($menu_item['menu_name']); ?></a></li>
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php else: ?>
				<li <?php if (strcasecmp($current_controller, "home") == 0) { print 'class="active"';} ?>><a href="<?php echo base_url(); ?>home">Home</a></li>
				<?php if ( $this->config->item('cafevariome_central') ): ?><li <?php if (strcasecmp($current_controller, "share") == 0) { print 'class="active"';} ?>><a href="<?php echo base_url(); ?>share">Share</a></li><?php endif; ?>
				<li <?php if (strcasecmp($current_controller, "discover") == 0) { print 'class="active"';} ?>><a href="<?php echo base_url(); ?>discover">Discover</a></li>
				<li class="dropdown">  
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">About<b class="caret"></b></a>  
					<ul class="dropdown-menu">  
						<li><a href="<?php echo base_url(); ?>about/cafevariome">Cafe Variome</a></li>
						<?php if ( $this->config->item('cafevariome_central') ): ?><li><a href="<?php echo base_url(); ?>about/disclaimer">Disclaimer</a></li><?php endif; ?>
						<li><a href="<?php echo base_url(); ?>about/faq">FAQ</a></li> 
						<li><a href="<?php echo base_url(); ?>about/features">Features</a></li>
						<li><a href="<?php echo base_url(); ?>about/gensearch">Gensearch</a></li>
						<li><a href="<?php echo base_url(); ?>variants/stats">Statistics</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo base_url(); ?>about/contact">Contact</a></li>
					</ul>  
				</li>
				<?php endif; ?>
				<?php if ( $this->ion_auth->is_admin()): ?>
					<!--<li <?php if (strcasecmp($current_controller, "admin") == 0) { print 'class="active"';} ?>><a href="<?php echo base_url(); ?>admin">&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Admin</a></li>-->
				<?php endif; ?>
			</ul>
        </div>
	</div>
</div>

<div id="searchBarModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Data Discovery Search</h3>
	</div>
	<div class="modal-body">
		<h4>Enter a search term:</h4>
		<?php
			$search_data = array('name' => 'term', 'id' => 'navbar_term', 'class'=>"input-xlarge search-query term", 'placeholder' => "Start typing a search term..." ); 
			echo form_input($search_data);
		?>
		<p></p>
		<p><small><a href="<?php echo base_url("discover");?>" >Access full discovery interface (includes examples and phenotype tree)</a></small></p>
		<?php if ($this->ion_auth->logged_in()): ?><p><small><a href="<?php echo base_url('/discover/search_history'); ?>">View search history</a></p></small><?php endif; ?>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-primary" id="navbar-search"><i class="icon-search"></i> Discover Variants</button>
	</div>
</div>

