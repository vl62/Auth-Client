<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "auth/groups";?>">Groups</a> <span class="divider">></span>
				</li>
				<li class="active">Create Group</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span8 offset3">
			<h1>Create Group</h1>
			<p>Please enter the group information below.</p>
			<div id="infoMessage"><b><?php echo $message; ?></b></div>

			<?php echo form_open("auth/create_group"); ?>

			<p>
				Group Name: <br />
				<?php echo form_input($group_name); ?>
			</p>

			<p>
				Description: <br />
				<?php echo form_input($desc); ?>
			</p>

			<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-th icon-white"></i>  Create Group</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "auth/groups";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>