<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "admin/sources";?>">Sources</a> <span class="divider">></span>
				</li>
				<li class="active">Add Central Source</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="pagination-centered" >
				<h3>Available Cafe Variome Central Sources</h3>
				<?php if ( ! empty ($sources) ): ?>
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Source Name</th>
							<th>Source Description</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>		
						<?php foreach ( $sources as $source_name => $source_description ): ?>
						<tr>
							<td><?php echo $source_name; ?></td>
							<td><?php echo $source_description; ?></td>
							<?php $central_source_name = $source_name . "_central"; ?>
							<?php if (array_key_exists($central_source_name, $central_sources)): ?>
							<td>
								<div class="slider central_slider" >
									<input type="checkbox" data-source_name="<?php echo $source_name; ?>" data-source_description="<?php echo $source_description; ?>" id="<?php echo $central_source_name; ?>" name="central-noncentral" class="central-noncentral" checked/>
								</div>
							</td>
							<?php else: ?>
							<td>
								<div class="slider central_slider" >
									<input type="checkbox" data-source_name="<?php echo $source_name; ?>" data-source_description="<?php echo $source_description; ?>" id="<?php echo $central_source_name; ?>" name="central-noncentral" class="central-noncentral" />
								</div>
							</td>
							<?php endif; ?>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<hr><p>To add a source from Cafe Variome Central move a slider switch to the "Online" position, setting a switch to "Offline" will delete the source from your sources list. The source will be then added to your sources list and variants will be discoverable through the variant discovery interface.</p>
				<?php else: ?>
					No sources are currently available from Cafe Variome Central<br />
				<?php endif; ?>
				<?php echo nbs(3); ?>
			</div>
		</div>
	</div>
</div>
