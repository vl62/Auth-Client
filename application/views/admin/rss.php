<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">News Feed</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<h2>RSS News Feed</h2>
		<hr>
		<table class="table table-bordered table-striped table-hover" id="example">
			<thead>
				<tr>
					<th>Title</th>
					<th>Description</th>
					<th>Post Date</th>
					<th>Action</th>
					<th>Status</th>
				</tr>
				</thead>
				<tbody>
					<?php foreach($posts->result() as $entry): ?>
					<tr>
						<td><?php echo $entry->post_title; ?></td>
						<td><?php echo $entry->post_body; ?></td>
						<td><?php echo $entry->post_date; ?></td>
						<td><a href="<?php echo base_url('feed/edit_post'). "/" . $entry->id; ?>" rel="popover" data-content="Modify this entry" data-original-title="Edit Entry"><i class="icon-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url('feed/delete_post'). "/" . $entry->id; ?>" rel="popover" data-content="Delete this entry" data-original-title="Delete Entry"></i><i class="icon-trash"></i></a></td>
						<td><?php if ( $entry->post_visible == 1 ): ?>
							Visible
							<?php else: ?>
							Not-visible
							<?php endif;  ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<div class="span12 pagination-centered"><p><a class="btn btn-primary btn-medium" href="<?php echo base_url('feed/add_post') ?>"><i class="icon-file icon-white"></i>  Add New Entry</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "admin";?>" class="btn" ><i class="icon-home"></i> Admin Dashboard</a></p></div>
		<!--</div>-->
	</div>
</div>