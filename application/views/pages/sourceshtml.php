<div class="container">
	<div class="row-fluid">
		<h3>Online Cafe Variome Sources</h3>
		<hr>
		<table class="table table-bordered table-striped table-hover" id="sourcestable">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($sources as $source => $source_desc): ?>
				<tr>
					<td><?php echo $source; ?></td>
					<td><?php echo $source_desc; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<br />
	</div>
</div>