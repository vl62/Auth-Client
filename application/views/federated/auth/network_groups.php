<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>  
					<a href="<?php echo base_url() . "networks";?>">Networks</a> <span class="divider">></span>  
				</li>
				<li class="active">Network Groups</li>
			</ul>  
		</div>  
	</div>
	<div class="page-header">
		<h2>Network Groups</h2>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<?php if (array_key_exists('error', $groups)): ?>
			<div class="span12 pagination-centered"><p>There are currently no groups for any of the networks you belong to, create a new group for a network below.</p><br /></div>
			<?php else: ?>
			<table class="table table-bordered table-striped table-hover general">
				<thead>
					<tr>
						<th>Group Name</th>
						<th>Group Description</th>
						<th>Network Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($groups as $group): ?>
					
					<tr>
						<td><?php echo $group['name']; ?></td>
						<td><?php echo $group['description']; ?></td>
						<td><?php echo $group['network_name']; ?></td>
						<td><a href="<?php echo base_url('groups/delete_network_group'). "/" . $group['id']; ?>" ></i><i class="icon-trash"></i></a></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<?php endif; ?>
			<div class="span12 pagination-centered"><p><a href="<?php echo base_url() . "groups/create_network_group";?>" class="btn btn-primary btn-medium" ><i class="icon-th icon-white"></i> Create new network group</a></p></div>
		</div>
	</div><!--/span-->
	<div class="span10 offset1 pagination-centered"><br /><p>Network groups can be assigned to sources within you installation. Users who belong to those groups in your network are allowed access to restrictedAccess records in sources across the network.</p></div>
	<!--<div class="span12 pagination-centered"><br /><p>To assign groups, click the edit button in the <a href="<?php // echo base_url() . "auth/users";?>">users</a> or <a href="<?php // echo base_url() . "admin/sources";?>">sources</a> pages</p></div>-->
	<hr>
</div><!--/.fluid-container-->
