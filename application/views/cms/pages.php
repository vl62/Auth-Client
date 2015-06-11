<div class="container">
	<div class="row">
		<div class="span6">  
			<ul class="breadcrumb">
				<li>
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "cms";?>">Content Management</a> <span class="divider">></span>  
				</li>
				<li class="active">Pages</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<h2>Pages</h2>
		<hr>
		<table class="table table-bordered table-striped table-hover" id="example">
			<thead>
				<tr>
					<th>Page Name</th>
					<th>Date Created</th>
					<th>Parent Menu</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php $c = 0; ?>
				<?php foreach ($pages as $page): ?>
				<?php $c++; ?>
				<tr>
					<td><?php echo $page['page_name']; ?></td>
					<td><?php echo $page['date_created']; ?></td>
					<td><?php if ( $page['parent_menu'] ) { echo $page['parent_menu']; } else { echo "No menu has been linked to this page"; } ?></td>
					<td><a href="<?php echo base_url('cms/edit_page'). "/" . $page['page_id']; ?>" rel="popover" data-content="Edit this page" data-original-title="Edit Page"><i class="icon-edit"></i></a>&nbsp;&nbsp;&nbsp;<?php if ( strtolower($page['page_name']) != "home" && strtolower($page['page_name']) != "contact" ): ?><a href="<?php echo base_url('cms/delete_page'). "/" . $page['page_id']; ?>" onclick="return confirm('Are you sure you want to delete this page (action cannot be undone)?')" rel="popover" data-content="Delete this page." data-original-title="Delete Page"></i><i class="icon-trash"></i></a><?php endif; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="muted pagination-centered"><p><small>N.B. "Home" and "Contact" are required pages and cannot be deleted, only edited.</small></p>
		<br />
		<div class="span12 pagination-centered"><a class="btn" href="<?php echo base_url('cms/add_page') ?>" data-content="Adds a new page." data-original-title="Add Page"><i class="icon-plus"></i>  Add Page</a><?php echo nbs(3); ?><a class="btn" href="<?php echo base_url('cms/menus') ?>" ><i class="icon-share-alt"></i>  Switch to Menus</a></div>
		<?php echo br(5); ?>
	</div>
</div>

