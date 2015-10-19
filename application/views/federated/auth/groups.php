<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Groups</li>
			</ul>  
		</div>  
	</div>
	<div class="page-header">
		<h2>Groups</h2>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<table class="table table-bordered table-striped table-hover" id="example">
				<thead>
					<tr>
						<th>Group Name</th>
						<th>Group Description</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($groups as $group): ?>
					<tr>
						<td><?php echo $group['name']; ?></td>
						<td><?php echo $group['description']; ?></td>
						<td><?php if ( array_key_exists($group['name'], $this->config->item("protected_groups")) ): echo "Edit/delete disabled"; else: ?><a rel="tooltip" data-original-title="Default tooltip" href="<?php echo base_url('auth_federated/edit_group'). "/" . $group['id']; ?>" ><i class="icon-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url('auth_federated/delete_group'). "/" . $group['id']; ?>" ></i><i class="icon-trash"></i></a><?php endif; ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<div class="span12 pagination-centered"><p><a href="<?php echo base_url() . "auth_federated/create_group";?>" class="btn btn-primary btn-medium" ><i class="icon-th icon-white"></i> Create new group</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "admin";?>" class="btn" ><i class="icon-home"></i> Admin Dashboard</a></p></div>
		</div>
	</div><!--/span-->
	<div class="span12 pagination-centered"><br /><p>To assign groups, click the edit button in the <a href="<?php echo base_url() . "auth_federated/users";?>">users</a> or <a href="<?php echo base_url() . "admin/sources";?>">sources</a> pages</p></div>
	<hr>
</div><!--/.fluid-container-->

