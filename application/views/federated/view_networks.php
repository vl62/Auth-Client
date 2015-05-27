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
	
	<div class="row-fluid">
		<div class="span10">
			<div class="pagination-centered" >
				<h4>Federated Node Setup</h4>
				<a href="<?php echo base_url() . "admin/add_node"; ?>" class="btn btn-small btn-primary" rel="popover" data-content="Add a node to the federated list. N.B. The new node will be propagated to all other nodes in the list." data-original-title="Add Node" ><i class="icon-plus icon-white"></i> Add Node</a><?php if ( !empty($node_list) ): ?><?php echo nbs(6); ?><a href="<?php echo base_url() . "admin/refresh_node_list"; ?>" class="btn btn-small btn-primary" rel="popover" data-content="Re-propagate the node list across all nodes (not usually necessary)." data-original-title="Refresh Nodes" ><i class="icon-refresh icon-white"></i> Refresh Nodes</a><?php echo nbs(6); ?><a href="<?php echo base_url() . "admin/add_federated_source"; ?>" class="btn btn-small btn-primary" rel="popover" data-content="Add sources from federated nodes (also possible via Sources admin page." data-original-title="Add Federated Source" ><i class="icon-plus icon-white"></i> Add Federated Source</a><?php endif; ?>
				<hr>
				<table class="table table-bordered table-striped table-hover" id="federatedtable">
					<thead>
						<tr>
							<th>Name</th>
							<th>URI</th>
							<th>Key</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $node_list as $node ): ?>
						<tr>
							<td><?php echo $node['node_name']; ?></td>
							<td><?php echo $node['node_uri']; ?></td>
							<td><?php echo $node['node_key']; ?></td>
							<td><?php if ( $node_statuses[$node['node_name']] ): ?><a class="btn btn-success btn-small" href="#" rel="popover" data-content="The node was successfully pinged." data-original-title="Node Up" ><i class="icon-thumbs-up"></i></a><?php else: ?><a class="btn btn-danger btn-small" href="#" rel="popover" data-content="There was a problem when pinging this node." data-original-title="Node Down" ><i class="icon-thumbs-down icon-white"></i></a><?php endif; ?></td>
							<td><a href="<?php echo base_url('admin/delete_node') . "/" . $node['node_name']; ?>" rel="popover" data-content="Click to delete the node. N.B. the deletion will be propagated across all nodes)." data-original-title="Delete Node"></i><i class="icon-trash"></i></a></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<br />
</div>