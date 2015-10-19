<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "admin/settings";?>">Settings</a> <span class="divider">></span>
				</li>
				<li class="active">Add Node</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span10 offset1 pagination-centered">
			<div class="well">
				<h1>Add Node</h1>
				<p>Please enter the node information below.</p>
				<?php echo form_open("admin/add_node"); ?>
				<div id="infoMessage"><strong><?php echo $message; ?></strong></div>
				<p>
					Node Name: <br />
					<div class="input-prepend">
						<span class="add-on"><a data-toggle="modal" data-backdrop="false" rel="popover" data-content="Specify the name of the node (must not contain spaces)" data-original-title="Node Name" ><i class="icon-question-sign"></i></a></span>
						<?php echo form_input($name); ?>
					</div>
				</p>
				<p>
					Node URI: <br />
					<div class="input-prepend">
						<span class="add-on"><a data-toggle="modal" data-backdrop="false" rel="popover" data-content="Specify the base URI of the node" data-original-title="Node URI" ><i class="icon-question-sign"></i></a></span>
						<?php echo form_input($uri); ?>
					</div>
				</p>
				<p>
					Node Key: <br />
					<div class="input-prepend input-append">
						<span class="add-on"><a data-toggle="modal" data-backdrop="false" rel="popover" data-content="Enter a unique (MD5) key for the node (use the refresh button to the right to generate a random key). N.B. This key will be propagated to all nodes so they can communicate with each other." data-original-title="Node Key" ><i class="icon-question-sign"></i></a></span>
						<?php echo form_input($key); ?>
						<span class="add-on"><a onclick="generateMD5();" rel="popover" data-content="Generate a random MD5 key by clicking this button." data-original-title="Generate Random Key" ><i class="icon-refresh"></i></a></span>
					</div>
				</p>
				<br />
				<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-file"></i>  Add Node</button><?php echo nbs(6); ?><a href="<?php echo base_url() . "admin/settings";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
