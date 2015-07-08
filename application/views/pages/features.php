<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span12 pagination-centered">
			<div class="well">
				<div id="this-carousel-id" class="carousel slide"><!-- class of slide for animation -->
					<div class="carousel-inner">
						<div class="item active"><!-- class of active since it's the first item -->
							<h3>Administrator Dashboard</h3>
							<img src="<?php echo base_url(); ?>resources/images/cafevariome/screenshots/admin_dashboard.png" alt="" />
							<div class="carousel-caption">
								<p>Clean, intuitive administrators interface gives complete control over your installation</p>
							</div>
						</div>
						<div class="item">
							<h3>Sources</h3>
							<img src="<?php echo base_url(); ?>resources/images/cafevariome/screenshots/sources.png" alt="" />
							<div class="carousel-caption">
								<p>Create sources in order to group together labs/networks and subsequently control access to these sources</p>
							</div>
						</div>
						<div class="item">
							<h3>Variants</h3>
							<img src="<?php echo base_url(); ?>resources/images/cafevariome/screenshots/variants.png" alt="" />
							<div class="carousel-caption">
								<p>Fine grained access control allows setting sharing levels down to individual variant level</p>
							</div>
						</div>
						<div class="item">
							<h3>Bulk Variant Import</h3>
							<img src="<?php echo base_url(); ?>resources/images/cafevariome/screenshots/import.png" alt="" />
							<div class="carousel-caption">
								<p>Bulk importer for variants offers flexible import of data in a variety of formats (e.g. Excel, tab-delimited, VCF)</p>
							</div>
						</div>
						<div class="item">
							<h3>Customise your installation</h3>
							<img src="<?php echo base_url(); ?>resources/images/cafevariome/screenshots/preferences.png" alt="" />
							<div class="carousel-caption">
								<p>Customise the visual appearance including logo, fonts, background and header colours</p>
							</div>
						</div>
						<div class="item">
							<h3>Variant Discovery</h3>
							<img src="<?php echo base_url(); ?>resources/images/cafevariome/screenshots/discovery.png" alt="" />
							<div class="carousel-caption">
								<p>A powerful search system enables open discovery of variants across sources.</p>
							</div>
						</div>
<!--						<div class="item">
							<h3>Users</h3>
							<img src="<?php // echo base_url(); ?>resources/images/cafevariome/screenshots/users.png" alt="" />
							<div class="carousel-caption">
								<p>Create users</p>
							</div>
						</div>-->
						<div class="item">
							<h3>Users and Groups</h3>
							<!--<p>Have variants you only want to share with a small group of people/labs? Just create a group, add users, and start sharing.</p>-->
							<img src="<?php echo base_url(); ?>resources/images/cafevariome/screenshots/users_groups.png" alt="" />
							<div class="carousel-caption">
								<p>A comprehensive access control system allows creating custom groups to share variants between specific labs/networks. Group members have pre-approved access to restrictedAccess variants in a source</p>
							</div>
						</div>
						<div class="item">
							<h3>DAS Server</h3>
							<img src="<?php echo base_url(); ?>resources/images/cafevariome/screenshots/das.png" alt="" />
							<div class="carousel-caption">
								<p>Built in DAS server allows you to view variants as tracks in common genome browsers</p>
							</div>
						</div>
					</div><!-- /.carousel-inner -->
					<!--  Next and Previous controls below, href values must reference the id for this carousel -->
					<a class="carousel-control left" href="#this-carousel-id" data-slide="prev">&lsaquo;</a>
					<a class="carousel-control right" href="#this-carousel-id" data-slide="next">&rsaquo;</a>
				</div><!-- /.carousel -->
				<!--http://www.webresourcesdepot.com/20-beautiful-resources-that-complement-twitter-bootstrap/-->
				<p>Please <?php echo mailto($this->config->item('email'), "contact us");?> if you wish to trial Cafe Variome.</p>
			</div>
		</div>
	</div>
	<hr>
</div><!--/.fluid-container-->