<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "feed/edit";?>">News Feed</a> <span class="divider">></span>
				</li>
				<li class="active">Add Entry</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span8 offset3">
			<h2>Add RSS Entry</h2>
			<p>Please enter the post information below.</p>
			<div id="infoMessage"><b><?php echo $message; ?></b></div>
			<?php echo form_open("feed/add_post"); ?>
			<p>
				Post Title: <br />
				<?php echo form_input($post_title); ?>
			</p>
			<p>
				Post Body: <br />
				<?php echo form_textarea($post_body); ?>
			</p>
			<p>
				Status: <br />
				<?php
				$options = array(
					'1' => 'Visible',
					'0' => 'Not-visible',
				);
				echo form_dropdown('post_visible', $options, '1');
				?>
			</p>
			<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-file"></i>  Save Entry</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "feed/edit";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>