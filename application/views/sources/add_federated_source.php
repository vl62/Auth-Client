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
				<li class="active">Add Federated Source</li>
			</ul>  
		</div>  
	</div>
	<?php if ($this->config->item('federated')): ?>
	<div class="row-fluid">
		<div class="span12">
			<div class="pagination-centered" >
				<h4>Federated Node Sources</h4>
				<table class="table table-bordered table-striped table-hover" id="addfederatedsourcetable">
					<thead>
						<tr>
							<th>Node Name</th>
							<th>Current Sources</th>
							<th>Edit Source List</th>
						</tr>
					</thead>
					<tbody>
						<?php $c = 0; ?>
						<?php foreach ($node_list as $node): ?>
							<?php 
							$url = rtrim(base_url(),"/");
							if ( $node['node_uri'] == $url ) {
//								error_log("node_uri -> " . $node['node_uri'] . " vs " . $url);
								continue;
							}
							$c++;
							if ( $node['node_status'] == "online"): ?>
							<tr id="<?php echo $node['node_name']; ?>">
								<td><?php echo $node['node_name']; ?></td>
								<td>
								<?php
									if ( ! empty ($federated_sources ) ) {
										$flag = 0;
										foreach ( $federated_sources as $source ) {
											if ( $source['source_uri'] == $node['node_uri'] ) {
												echo $source['source_name'] . "<br />";
												$flag = 1;
											}
										}
										if ( ! $flag ) {
											echo "No sources have been added from this node, click the edit icon to modify.<br />";
										}
									}
									else {
										echo "No sources have been added from this node, click the edit icon to modify.<br />";
									}
								?>
								</td>
								<td><a href="#federated_modal<?php echo $c; ?>" rel="popover" data-content="Edit sources from this federated node" data-original-title="Edit Federated Sources" data-toggle="modal"><i class="icon-edit"></i></a></td>
							</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					</tbody>
				</table>
						<?php $number_of_nodes = count($node_list); ?>
						<?php if ( $number_of_nodes == 0 ): ?>
						<br /><div class='alert alert-info'><p>You have not added any nodes so federated sources cannot be added or edited. You first need to add nodes via the "Federated" tab after clicking "Edit Federated Settings" button below.</p></div>
						<?php endif; ?>

				<?php $c = 0; ?>
				<?php foreach ($node_list as $node):
					$url = rtrim(base_url(),"/");
					if ( $node['node_uri'] == $url ) {
//						error_log("node_uri -> " . $node['node_uri'] . " vs " . $url);
						continue;
					}
					$c++;
					if ( $node['node_status'] == "online"): ?>
					<script>
						// Change listener for when the modal is closed - refresh the window so the current sources list is updated
						$(document).ready(function() {
							$('#federated_modal<?php echo $c; ?>').on('hidden', function () {
								location.reload();
							});
						});
					</script>
					<div class="modal fade" id="federated_modal<?php echo $c; ?>">
						<div class="modal-header">
							<a class="close" data-dismiss="modal">&times;</a>
							<h3><?php echo $node['node_name']; ?></h3>
						</div>
						<div class="modal-body">
							<?php if ( ! empty ($node_source_list[$node['node_name']] ) ): ?>
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th>Source Name</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									
								<?php foreach ( $node_source_list[$node['node_name']] as $source_name => $source ): ?>
									<tr>
										<td><?php echo $source; ?></td>
										<?php $federated_source_name = $source_name . "_" . $node['node_name']; ?>
										<?php if (array_key_exists($federated_source_name, $federated_sources)): ?>
										<td>
											<div class="slider federated_slider" >
												<input type="checkbox" data-source_name="<?php echo $source_name; ?>" data-source_description="<?php echo $source; ?>" id="<?php echo $node['node_name']; ?>" name="federated-nonfederated" class="federated-nonfederated" checked/>
											</div>
										</td>
										<?php else: ?>
										<td>
											<div class="slider federated_slider" >
												<input type="checkbox" data-source_name="<?php echo $source_name; ?>" data-source_description="<?php echo $source; ?>" id="<?php echo $node['node_name']; ?>" name="federated-nonfederated" class="federated-nonfederated" />
											</div>
										</td>
										<?php endif; ?>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
							<hr><p>To add a source from a federated node use the slider switch and set it to "Online". The source will be then added to your sources list.</p>
							<?php else: ?>
								No sources available for this node<br />
							<?php endif; ?>
						</div>
						<div class="modal-footer">
							<a href="#" class="btn" data-dismiss="modal">Close</a>
						</div>
					</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12 pagination-centered">
			<a class="btn btn-primary btn-medium" href="<?php echo base_url('admin/settings#federated') ?>"><i class="icon-file icon-white"></i>  Edit Federated Settings</a>
		</div>
	</div>
	<?php else: ?>
	<div class="row-fluid">
		<div class="span12">
			<div class="pagination-centered" >
				<h4>Federation is not enabled in this Cafe Variome instance.</h4>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
