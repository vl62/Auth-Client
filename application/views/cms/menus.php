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
				<li class="active">Menus</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<h2>Menus</h2>
		<hr>
		<table class="table table-bordered table-striped table-hover" id="menus">
			<thead>
				<tr>
					<th>Change Order</th>
					<th>Order</th>
					<th>Menu Name</th>
					<th>Associated Page(s)</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php $c = 0; ?>
				<?php usort($menus, function($a, $b) { return strcmp($a['menu_id'], $b['menu_id']); }); ?>
				<?php foreach ($menus as $menu): ?>
				<?php $c++; ?>
				<tr>
					<td><i class="icon-move"></i> </td>
					<td class="count"><?php echo $menu['menu_id']; ?></td>
					<td class="menuname"><?php echo $menu['menu_name']; ?></td>
					<?php if ( strtolower($menu['menu_name']) != "home" && strtolower($menu['menu_name']) != "discover" && strtolower($menu['menu_name']) != "contact" ): ?>
					<td><?php echo $associate_pages[$menu['menu_name']]; ?></td>
					<?php else: ?>
					<td><i class="icon-ban-circle"></i></td>
					<?php endif; ?>
					<?php if ( strtolower($menu['menu_name']) != "home" && strtolower($menu['menu_name']) != "discover" && strtolower($menu['menu_name']) != "contact" ): ?>
					<td><a href="<?php echo base_url('cms/delete_menu'). "/" . $menu['menu_name']; ?>" onclick="return confirm('Are you sure? (WARNING: The menu will be unlinked from any pages that are currently associated with it)')" rel="popover" data-content="Delete this menu. WARNING: The menu will be unlinked from any pages that are currently associated with it" data-original-title="Delete Menu"></i><i class="icon-trash"></i></a></td>
					<?php else: ?>
					<td><i class="icon-ban-circle"></i></td>
					<?php endif; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="muted pagination-centered"><p><small>N.B. The <i class="icon-ban-circle"></i> icon means that "Home", "Discover" & "Contact" menus cannot be deleted. The "Discover" menu has fixed content (i.e. the search interface) but the "Home" and "Contact" content can be changed in the pages interface.</small></p></div>
		<div id="sourceDisplay"></div>
		<br />
		<div class="span12 pagination-centered"><a class="btn" href="<?php echo base_url('cms/add_menu') ?>" data-content="Adds a new menu." data-original-title="Add Menu"><i class="icon-plus"></i>  Add Menu</a><?php echo nbs(3); ?><a class="btn" href="<?php echo base_url('cms/pages') ?>" ><i class="icon-share-alt"></i>  Switch to Pages</a></div>
		<?php echo br(5); ?>
	</div>
</div>

