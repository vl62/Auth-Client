<div class="container">
	<div class="row-fluid">
		<div class="span12 pagination-centered">
			<div class="well">
				<legend><strong>Source: </strong><?php echo $source['description']; ?></legend>
				<p><?php echo nl2br($source['long_description']); ?></p>
				<p>Source link: <?php echo anchor($source['uri'], $source['uri']); ?></p>
				<p>Source contact email: <a href="mailto:<?php echo $source['email'];?>" ><?php echo $source['email']; ?></a></p>
				<hr>
				<a href="<?php echo base_url();?>" class="btn" ><i class="icon-home"></i> Home</a>
			</div>
		</div>
	</div>
</div>
