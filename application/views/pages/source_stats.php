<div class="container">
	<div class="row-fluid">
		<h3><?php echo $this->config->item('site_title'); ?> Source Statistics</h3>
		<hr>
		<table class="table table-bordered table-striped table-hover" id="statstable">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<!--<th>Type</th>-->
					<th>Variant Count</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($sources->result() as $source): ?>
				<?php if ( isset($variant_counts[$source->name]) ): ?>
				<tr>
					<td><a rel="popover" data-content="Click for the long description of this source (opens in a new window)." data-original-title="Source Information" href="<?php echo base_url('discover/source/' . $source->name); ?>" target="_blank"><?php echo $source->name; ?></a></td>
					<td><?php echo $source->description; ?></td>
					<!--<td>-->
						<?php 
//							echo $source->type;
						?>
					<!--</td>-->
					<td>
						<?php echo $variant_counts[$source->name]; ?>
						
					</td>
				</tr>
				<?php endif; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="row-fluid">
		<div class="span12 pagination-centered"><p><a class="btn btn-primary btn-medium" href="<?php echo base_url('discover') ?>"><i class="icon-file icon-white"></i>  Access Discovery Interface</a></p></div>
	</div>
</div>