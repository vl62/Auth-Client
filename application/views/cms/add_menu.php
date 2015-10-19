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
						<a href="<?php echo base_url() . "cms/menus";?>">Menus</a> <span class="divider">></span>  
					</li>
					<li class="active">Add Menu</li>
				</ul>  
			</div>  
		</div>	
		<div class="row">
			<div class="span10">
				<h3>Add Menu</h3><hr>
				<b><strong><?php echo validation_errors(); ?></strong></b>
				<?php echo form_open("cms/add_menu"); ?>
				<h4>Menu Name:</h4>
				<?php echo form_input($menu_name); ?>
			</div>
		</div>
		<div class="row">
			<div class="span4 offset2">
				<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-file icon-white"></i>  Add Menu</button></p>
			</div>
		</div>
	<!--</div>-->
	<br />
	<hr>
</div>
<?php echo form_close(); ?>
