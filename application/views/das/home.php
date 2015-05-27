<div class="container">
	<div class="row-fluid pagination-centered">
		<h2>Welcome to DASIgniter v0.1</h2>
		<hr>
		<p>DASIgniter: a DAS server for PHP using the <a href="http://ellislab.com/codeigniter" target="_blank">CodeIgniter</a> framework.</p>
		<br />
		<div class="span12 pagination-centered"><p><a class="btn btn-primary btn-medium" href="<?php echo base_url('das/dsn') ?>"><i class="icon-file icon-white"></i>  DSN Request</a><?php echo nbs(6); ?><a class="btn btn-primary btn-medium" href="<?php echo base_url('das/sources') ?>"><i class="icon-file icon-white"></i>  Sources Request</a></div>
		<hr>
		<br />
<!--		<div class="row pagination-centered">-->
		<div class="span10 offset1">
		<table class="table table-bordered table-striped table-hover" id="sourcestable">
			<thead>
				<tr>
					<th>Source</th>
					<th>Mapmaster</th>
					<th>Description</th>
					<th>Capabilities</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($sources->result() as $source): ?>
				<tr>
					<td><?php echo anchor(base_url() . "das/" . $source->name, $source->name);?></td>
					<td>-</td>
					<td><?php echo $source->description; 
						if ( isset($variant_counts[$source->name]) ):
							echo " (" . $variant_counts[$source->name] . " variants)";
						else:?>
							(0 variants)
						<?php endif; ?></td>
					<td>dsn/1.0; features/1.1</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		</div>
	</div>
	<br />
</div>