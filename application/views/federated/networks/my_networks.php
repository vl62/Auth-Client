<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Networks</li>
			</ul>  
		</div>  
	</div>
	
	<div class="row-fluid">
		<div class="span12">
			<div class="pagination-centered" >
				<div class="well">
					<h3>My Networks</h3>
					<p>
						<a href="<?php echo base_url() . "networks/create_network"; ?>" class="btn btn-primary" > Create a new network</a>
						<a href="<?php echo base_url() . "networks/join_network"; ?>" class="btn btn-primary" > Join an existing network</a>
					</p>
					<hr>
					<?php if ( ! $networks ): ?>
					<p>You are currently not a member of any networks.</p>
					<?php else: ?>
					<table class="table table-bordered table-striped table-hover general">
						<thead>
							<tr>
								<th>Network ID</th>
								<th>Network Name</th>
								<th>Installations</th>
								<th>Total Installation Count</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $networks as $network ):  ?>
							<tr>
								<td><?php echo $network['network_id']; ?></td>
								<td><?php echo $network['network_name']; ?></td>
								<td>
									<?php foreach ( $installations_for_networks[$network['network_key']] as $installation ): ?>
									<a href="<?php echo $installation['installation_base_url'];?>"><?php echo $installation['installation_base_url']; ?></a><br />
									<?php endforeach; ?>
								</td>
								<td><?php echo $installation_count_for_networks[$network['network_key']]; ?></td>
								<td><a href="<?php echo base_url('networks/leave_network') . "/" . $network['network_key'] . "/" . $installation_count_for_networks[$network['network_key']] . "/" . $network['network_name']; ?>" rel="popover" data-content="Click to leave the network. N.B. this action cannot be undone and you will need to request to join the network again. If you are the last member of the network then this network will be permanently deleted." data-original-title="Leave Network"></i><i class="icon-remove"></i></a></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				
					<br />
					<div id="infoMessage"><h4><strong><?php echo $this->session->flashdata('message'); ?></strong></h4></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<br />
</div>