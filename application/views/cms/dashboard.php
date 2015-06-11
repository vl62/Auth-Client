<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">
				<li>
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Content Management</li>  
			</ul>  
		</div>  
	</div>
		
	<?php if ( isset($success_message) ) { echo "<div class='row'><div class='span6 offset2 pagination-centered'><div id='success-alert' class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>&times;</button><p>Message was successfully sent!</p></div></div></div>"; } ?>
	<br /><br />
	<div class="row">
		<div class="span6 pagination-centered"><a href="<?php echo base_url() . "cms/pages";?>" class="btn btn-info btn-large" rel="popover" data-content="Create and edit page content" data-original-title="Create & Edit Pages"><img width="100" height="100" src="<?php echo base_url();?>resources/images/cafevariome/icon-edit-pages.png" /></a><br />Create & Edit Pages</div>
		<div class="span6 pagination-centered"><a href="<?php echo base_url() . "cms/menus";?>" class="btn btn-info btn-large" rel="popover" data-content="Create and edit menu items" data-original-title="Create & Edit Menus"><img width="100" height="100" src="<?php echo base_url();?>resources/images/cafevariome/icon-edit-menus.png" /></a><br />Create & Edit Menus</div>

	</div>
	<br /><br />

	<br />
</div>