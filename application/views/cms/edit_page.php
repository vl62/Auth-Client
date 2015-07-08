<div class="container">
	<!--<div class="well">-->
		<div class="row">  
			<div class="span7">  
				<ul class="breadcrumb">
					<li>
						<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
					</li>
					<li>  
						<a href="<?php echo base_url() . "cms";?>">Content Management</a> <span class="divider">></span>  
					</li>
					<li>
						<a href="<?php echo base_url() . "cms/pages";?>">Pages</a> <span class="divider">></span>  
					</li>
					<li class="active">Edit Page</li>
				</ul>  
			</div>  
		</div>	
		<div class="row">
			<div class="span10">
				<h3>Edit Page</h3><hr>
				<b><strong><?php echo validation_errors(); ?></strong></b>
				<?php
//				$hidden = array('page_id' => $page_id);
//				echo form_open("cms/edit_page", '', $hidden);
				echo form_open("cms/edit_page");
				echo form_hidden('page_id', $page_id);
				?>
				<h4>Page Name:</h4>
				
				<?php echo form_input($page_name); ?>
				<h4>Page Content:</h4>
				<textarea id="page_content" name="page_content" ><?php if ( array_key_exists('page_content', $page)) { echo $page['page_content']; } ?></textarea>
				<br />
					<?php if ( empty($menus)): ?>
					<p>You currently do not have any menus so cannot link this page to a menu, <a href="<?php echo base_url() . "cms/add_menu";?>">click here</a> to create a new menu</p>
					<?php else: ?>
					Select Parent Menu: <br />
					<?php
					$menu_options = array();
					$menu_options["none"] = "None"; 
					foreach ( $menus as $menu ) {
						$menu_options[$menu['menu_name']] = $menu['menu_name'];
					}
					echo form_dropdown('menus', $menu_options, $page['parent_menu']);
					?>
					<?php endif; ?>

			</div>
		</div>
		<div class="row">
			<div class="span4 offset2">
				<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-edit icon-white"></i>  Edit Page</button></p>
			</div>
		</div>
	<!--</div>-->
	<br />
	<hr>
</div>
<?php echo form_close(); ?>
