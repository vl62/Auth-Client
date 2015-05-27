<div class="container">
	<div class="row-fluid">
		<div class="span12 pagination-centered">
			<div class="well">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#data_access" data-toggle="tab">Data Access</a></li>
						<li><a href="#data_requests" data-toggle="tab">Data Requests</a></li>
					</ul>
					<div class="tab-content">
						<div id="data_access" class="tab-pane active">
							<legend>Data Access</legend>
							<?php if (empty($user_accessible_sources)): ?>
								<p>Sorry, you currently are not a member of any groups that can access restrictedAccess data.</p>
							<?php else: ?>
								<p>You currently have the required level of group access for the following restrictedAccess data sources. Data can either be downloaded in bulk below or searched using the <a href="<?php echo base_url(); ?>discover">discovery interface</a>.</p>
								<br />
								<table class="table table-bordered table-striped table-hover generaltable">
									<thead>
										<tr>
											<th>Source Name</th>
											<th>Bulk Download</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($user_accessible_sources as $source_id => $source_description): ?>
											<tr>
												<td><?php echo $source_description; ?></td>
												<td><a href="<?php echo base_url() . "variants/bulk_download/tab/" . $source_id; ?>">tab-delimited</a> | <a href="<?php echo base_url() . "variants/bulk_download/lovd/" . $source_id; ?>">LOVD</a> | <a href="<?php echo base_url() . "variants/bulk_download/varioml" . $source_id; ?>">VarioML</a> | <a href="<?php echo base_url() . "variants/bulk_download/vcf" . $source_id; ?>">VCF</a> | <a href="<?php echo base_url() . "variants/bulk_download/xlsx" . $source_id; ?>">xlsx</a></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php endif; ?>
						</div>
						<div id="data_requests" class="tab-pane">
							<legend>Data Requests</legend>
							<?php if (empty($data_requests)): ?>
								<p>You currently do not have any data requests to view.</p>
							<?php else: ?>
								<table class="table table-bordered table-striped table-hover generaltable">
									<thead>
										<tr>
											<th>ID</th>
											<th>Date & Time</th>
											<th>Justification</th>
											<th>Result Reason</th>
											<th>Result</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($data_requests as $request_id => $request): ?>
										<tr>
											<td><?php echo $request['request_id']; ?></td>
											<td><?php echo $request['datetime']; ?></td>
											<td><?php echo $request['justification']; ?></td>
											<td><?php if ( $request['resultreason'] ) { echo $request['resultreason']; } else { echo "-"; }?></td>
											<td><?php if ( $request['result'] == "approved" ) { echo '<span class="badge badge-success">Approved</span>'; } elseif ( $request['result'] == "refused" ) { echo '<span class="badge badge-inverse">Refused</span>'; } else { echo '<span class="badge">Pending</span>'; } ?></td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php endif; ?>
						</div>

					</div><!-- /.tab-content -->
				</div><!-- /.tabbable -->

				<br />
				<p><a href="<?php echo base_url() . "auth/user_profile/" . $user->id; ?>" class="btn btn-primary"><i class="icon-user"></i>  Back to Profile</a></p>

			</div>
		</div>
	</div>



