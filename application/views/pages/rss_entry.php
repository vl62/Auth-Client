<div class="container">
	<div class="row-fluid">
		<div class="span8 offset2 pagination-centered">
			<div class="well">
				<legend><?php echo $entry['post_title']; ?></legend>
				<p><?php echo $entry['post_date']; ?></p>
				<hr>
				<p><?php echo $entry['post_body']; ?></p>
				<hr>
				<a href="<?php echo base_url();?>" class="btn" ><i class="icon-home"></i> Home</a>
			</div>
		</div>
	</div>
</div>
