<div class="container">
	<div class="row-fluid">
		<div class="span12 pagination-centered">
			<div class="well">
				<?php if ( ! empty($search_history)): ?>
				<table class="table table-bordered table-striped table-hover general">
					<thead>
						<tr>
							<th>Search Term</th>
							<th>Source</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $search_history as $row ): ?>
							<tr>
								<td><?php echo $row['term']; ?></td>
								<td><?php echo $row['source']; ?></td>
								<td><?php echo $row['datetime']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else: ?>
				<p>No search history to display, you have not performed any searches</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div><!--/.container-->
