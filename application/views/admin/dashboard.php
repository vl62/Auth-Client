<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li class="active">Dashboard Home</li>  
			</ul>  
		</div>  
	</div>  
	<div class="row">
		<div class="span10 offset1">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#">Data</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="offset2 span3 pagination-centered"><a href="<?php echo base_url() . "sources";?>" class="btn btn-info btn-large" rel="popover" data-content="Create/modify/delete record sources" data-original-title="Variant Sources"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-sources.png" /></a><br />Sources</div>
		<div class="span3 pagination-centered"><a href="<?php echo base_url() . "admin/variants";?>" class="btn btn-info btn-large" rel="popover" data-content="Create/modify/delete records" data-original-title="Variants"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-records.png" /></a><br />Records</div>
		<div class="span3 pagination-centered"><a href="<?php echo base_url() . "admin/phenotypes";?>" class="btn btn-info btn-large" rel="popover" data-content="Phenotype and phenotype ontology settings" data-original-title="Phenotypes"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-phenotypes.png" /></a><br />Phenotypes</div>
	</div>
	<!--<hr>-->
	<div class="row">
		<div class="span10 offset1">
			<br />
			<ul class="nav nav-tabs">
				<li class="active">
					<!--Access Control-->
					<a href="#">Access Control</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="row">
		<?php if($this->session->userdata('controller') === "auth_federated"): ?>
		<div class="offset2 span3 pagination-centered"><a href="<?php echo base_url() . "auth_federated/users";?>" class="btn btn-info btn-large" rel="popover" data-content="Create/modify/delete users" data-original-title="Users"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-users.png" /></a><br />Users</div>
		<div class="span3 pagination-centered"><a href="<?php echo base_url() . "groups";?>" class="btn btn-info btn-large" rel="popover" data-content="Create/modify/delete groups" data-original-title="User Groups"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-groups.png" /></a><br />Groups</div>
		<div class="span3 pagination-centered"><a href="<?php echo base_url() . "admin/data_requests/";?>" class="btn btn-info btn-large" rel="popover" data-content="Approve or refuse any requests for data from users." data-original-title="Curate data requests"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-data-requests.png" /></a><br />Data Requests</div>
		<?php else:?>    
		<div class="offset2 span3 pagination-centered"><a href="<?php echo base_url() . "auth/no_access";?>" class="btn btn-info btn-large disabled" rel="popover" data-content="Create/modify/delete users" data-original-title="Users"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-users.png" /></a><br />Users</div>
		<div class="span3 pagination-centered"><a href="<?php echo base_url() . "auth/no_access";?>" class="btn btn-info btn-large disabled" rel="popover" data-content="Create/modify/delete groups" data-original-title="User Groups"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-groups.png" /></a><br />Groups</div>
		<div class="span3 pagination-centered"><a href="<?php echo base_url() . "auth/no_access";?>" class="btn btn-info btn-large disabled" rel="popover" data-content="Approve or refuse any requests for data from users." data-original-title="Curate data requests"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-data-requests.png" /></a><br />Data Requests</div>
		<?php endif;?>
	</div>
	<!--<hr>-->
	<div class="row">
		<div class="span10 offset1">
			<br />
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#">Settings and Preferences</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="offset2 span3 pagination-centered"><a href="<?php echo base_url() . "admin/settings";?>" class="btn btn-info btn-large" rel="popover" data-content="Modify core system settings of your installation including regenerating ontology trees, autocomplete terms and ElasticSearch index; setting the display fields for the discovery interface; generating templates for data import." data-original-title="Settings"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-configuration.png" /></a><br />Settings</div>
		<div class="span3 pagination-centered"><a href="<?php echo base_url() . "admin/preferences";?>" class="btn btn-info btn-large" rel="popover" data-content="Modify visual appearance of your installation." data-original-title="Preferences"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-preferences.png" /></a><br />Preferences</div>
                <?php if($this->session->userdata('controller') === "auth_federated"): ?>
                    <div class="span3 pagination-centered"><a href="<?php echo base_url() . "networks";?>" class="btn btn-info btn-large" rel="popover" data-content="Configure and manage networks." data-original-title="Networks"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-federated.png" /></a><br />Networks</div>
                <?php else:?>    
                    <div class="span3 pagination-centered"><a href="<?php echo base_url() . "auth/no_access";?>" class="btn btn-info btn-large disabled" rel="popover" data-content="Configure and manage installation federation." data-original-title="Federated"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-federated.png" /></a><br />Federated</div>
                <?php endif;?>
	</div>
	<!--<hr>-->
	<div class="row">
		<div class="span10 offset1">
			<br />
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#">Content and Reports</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="offset2 span3 pagination-centered"><a href="<?php echo base_url() . "feed/edit";?>" class="btn btn-info btn-large" rel="popover" data-content="Add, delete and modify items in your public news feed on the front page." data-original-title="Feed"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-news.png" /></a><br />News Feed</div>
		<div class="span3 pagination-centered"><a href="<?php echo base_url() . "cms";?>" class="btn btn-info btn-large" rel="popover" data-content="Add, delete and modify menus and pages." data-original-title="Content Management"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-edit.png" /></a><br />Content Management</div>
		<?php if ( $this->config->item('stats')): ?>
		<div class="span3 pagination-centered"><a href="<?php echo base_url() . "admin/stats";?>" class="btn btn-info btn-large" rel="popover" data-content="Get information on usage of installation." data-original-title="Statistics"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-stats.png" /></a><br />Statistics</div>		
		<?php else: ?>
		<div class="span3 pagination-centered"><a href="#" class="btn btn-info btn-large" disabled rel="popover" data-content="Get information on usage of installation. N.B. You must have the statistic database present and the config option set to true in order to enable this feature." data-original-title="Statistics (CURRENTLY NOT ENABLED)"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-stats.png" /></a><br />Statistics</div>
		<?php endif; ?>
	</div>
	<br />
	<br />
</div>