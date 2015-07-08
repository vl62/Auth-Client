<script>
	$(document).ready(function(){
		$( "#create_menu_div" ).show();
		$( "#menus" ).change(function() {
			if ( $('#menus :selected').val() == "none") {
//				alert("selected -> " + $('#menus :selected').val());
				$( "#create_menu_div" ).show();
			}
			else {
				$( "#create_menu_div" ).hide();
				$("#create_menu").val('no');
			}
		});
	});
</script>

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
					<li class="active">Add Page</li>
				</ul>  
			</div>  
		</div>	
		<div class="row">
			<div class="span10">
				<h3>Add Page</h3><hr>
				<b><strong><?php echo validation_errors(); ?></strong></b>
				<?php echo form_open("cms/add_page"); ?>
				
				<h4>Page Name:</h4>
				
				<?php echo form_input($page_name); ?>
				<h4>Page Content:</h4>
				<textarea id="page_content" name="page_content" ><?php echo $page_content; ?></textarea>
				<br />
					<?php if ( empty($menus)): ?>
					<p>You currently do not have any menus so cannot link this page to a menu, <a href="<?php echo base_url() . "cms/add_menu";?>">click here</a> to create a new menu</p>
					<p>Alternatively, select whether you would like create a menu item with the same name and link this page to it:<br />
					<select name="create_menu">
						<option value="yes">Yes</option>
						<option value="no" selected="selected">No</option>
					</select>
					<?php else: ?>
					Select Parent Menu: <br />
					<select id="menus" name="menus">
						<option value="none">None</option>
						<?php foreach ( $menus as $menu ): ?>
						<?php if ( strtolower($menu['menu_name']) != 'home' && strtolower($menu['menu_name']) != 'discover' ): ?>
						<option value="<?php echo $menu['menu_name']; ?>"><?php echo $menu['menu_name']; ?></option>
						<?php endif; ?>
						<?php endforeach; ?>
					</select>
					<div id="create_menu_div">
						<p>Choose whether you would like create a menu item with the same name and link this page to it (parent menu selection will be ignored if this is set to yes):<br />
						<select id="create_menu" name="create_menu">
							<option value="yes">Yes</option>
							<option value="no" selected="selected">No</option>
						</select>
					</div>
					<?php endif; ?>

			</div>
		</div>
		<div class="row">
			<div class="span4 offset2">
				<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-file icon-white"></i>  Add Page</button></p>
			</div>
		</div>
	<!--</div>-->
	<br />
	<hr>
</div>
<?php echo form_close(); ?>
