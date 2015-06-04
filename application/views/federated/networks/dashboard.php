<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">
				<li>
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Federated Settings</li>
			</ul>  
		</div>  
	</div>
	<br />
	<div class="row">
		<div class="span4 pagination-centered"><a href="<?php echo base_url() . "networks/create_network";?>" class="btn btn-info btn-large" rel="popover" data-content="Create a new network" data-original-title="Create Network"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-federated-create.png" /></a><br />Create Network</div>
		<div class="span4 pagination-centered"><a href="<?php echo base_url() . "networks/join_network";?>" class="btn btn-info btn-large" rel="popover" data-content="Join an existing network" data-original-title="Join Network"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-federated-join.png" /></a><br />Join Network</div>
		<div class="span4 pagination-centered"><a href="<?php echo base_url() . "networks/my_networks";?>" class="btn btn-info btn-large" rel="popover" data-content="View current networks you are a member of" data-original-title="My Networks"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-federated-view.png" /></a><br />My Networks</div>
	</div>
	<br />
	<br />
	<div class="row">
		<div class="span4 pagination-centered"><a href="<?php echo base_url() . "networks/network_requests_incoming";?>" class="btn btn-info btn-large" rel="popover" data-content="View incoming network requests" data-original-title="Incoming Network Requests"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-network-requests.png" /></a><br />Incoming Network Requests</div>
		<div class="span4 pagination-centered"><a href="<?php echo base_url() . "networks/network_requests_outgoing";?>" class="btn btn-info btn-large" rel="popover" data-content="View outgoing network requests" data-original-title="Outgoing Network Requests"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-network-requests.png" /></a><br />Outgoing Network Requests</div>
	</div>
	<br /><br />
	<br />
</div>