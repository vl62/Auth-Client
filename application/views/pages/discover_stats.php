<div class="container">
	<div class="row-fluid">
		<div class="span8 offset2 pagination-centered">
			<div class="well">
				<h4>Genes (20 highest occurences)</h4>
				<table class="table table-bordered table-striped table-hover general">
					<thead>
						<tr>
							<th>Gene</th>
							<th>Variant Count</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($gene_counts as $gene => $count): ?>
						<tr>
							<td><?php echo $gene; ?></td>
							<td>
								<?php echo $count; ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<br />
	<div class="row-fluid">
		<div class="span8 offset2 pagination-centered">
			<div class="well">
				<h4>Reference Sequences (20 highest occurences)</h4>
				<table class="table table-bordered table-striped table-hover general">
					<thead>
						<tr>
							<th>Reference Sequence</th>
							<th>Variant Count</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($ref_counts as $ref => $count): ?>
						<tr>
							<td><?php echo $ref; ?></td>
							<td>
								<?php echo $count; ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 pagination-centered"><p><a class="btn btn-primary btn-medium" href="<?php echo base_url('discover') ?>"><i class="icon-file icon-white"></i>  Access Discovery Interface</a></p></div>
	</div>
</div>