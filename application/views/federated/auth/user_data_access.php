<div class="container">
	<div class="row-fluid">
		<div class="span12 pagination-centered">
			<div class="well">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#data_requests" data-toggle="tab">Data Requests</a></li>
						<li ><a href="#data_access" data-toggle="tab">Data Access</a></li>
					</ul>
					<div class="tab-content">
						<div id="data_requests" class="tab-pane active">
							<legend>Data Requests</legend>
							<?php if (empty($data_requests)): ?>
								<p>You currently do not have any data requests to view.</p>
							<?php else: ?>
								<table class="table table-bordered table-striped table-hover generaltable">
									<thead>
										<tr>
<!--											<th>ID</th>-->
											<th>Date & Time</th>
											<th>Request</th>
											<th>Source</th>
											<th>Justification</th>
											<th>Result Reason</th>
											<th>Result</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($data_requests as $request_id => $request): ?>
										<tr>
											<!--<td><?php // echo $request['request_id']; ?></td>-->
											<td><?php echo $request['datetime']; ?></td>
											<td><?php echo urldecode($request['term']); ?></td>
											<td><?php echo $request['source']; ?></td>
											<td><?php echo $request['justification']; ?></td>
											<td><?php if ( $request['resultreason'] ) { echo $request['resultreason']; } else { echo "-"; }?></td>
											<td><?php if ( $request['result'] == "approved" ) { echo '<span class="badge badge-success">Approved</span>'; } elseif ( $request['result'] == "refused" ) { echo '<span class="badge badge-inverse">Refused</span>'; } else { echo '<span class="badge">Pending</span>'; } ?></td>
											<td>
												<?php if ( $request['result'] == "approved" ): ?>
												<a href="#downloadDataRequestModal<?php echo $request['request_id']; ?>" data-toggle="modal" data-backdrop="false" rel='popover' data-content='Click to choose the format you wish to download your data in. N.B. That this request will remain available for you to download records from until either you or the data owner deletes the request.' data-original-title='Download requested Data' ><i class='icon-download'> </i></a>
												<?php endif; ?>
												<a href='<?php echo base_url() . "discover/delete_request/" . $request['request_id'];?>' rel='popover' data-content='Click to delete the request, no message will be sent to the requesting user (ACTION CANNOT BE UNDONE)' data-original-title='Delete Request' ><i class='icon-trash'> </i></a>
											</td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php endif; ?>
						</div>
						<div id="data_access" class="tab-pane">
							<legend>Data Access</legend>
							<?php if (empty($user_accessible_sources)): ?>
								<p>Sorry, you currently are not a member of any groups that can access restrictedAccess data.</p>
							<?php else: ?>
								<p>You currently have the required level of group access for the following sources and will be able to directly access any restrictedAccess variants contained in them. Data can be searched using the <a href="<?php echo base_url(); ?>discover">discovery interface</a>.</p>
								<br />
								<div class="span8 offset2 pagination-centered">
									<table class="table table-bordered table-striped table-hover generaltable">
										<thead>
											<tr>
												<th>Source Name</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($user_accessible_sources as $source_id => $source_description): ?>
												<tr>
													<td><?php echo $source_description; ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php endif; ?>
						</div>
					</div><!-- /.tab-content -->
				</div><!-- /.tabbable -->

				<br />
				<p><a href="<?php echo base_url() . "auth_federated/user_profile/" . $user->id; ?>" class="btn btn-primary"><i class="icon-user"></i>  Back to Profile</a></p>

					<?php foreach ($data_requests as $request_id => $request): ?>
					<div id="downloadDataRequestModal<?php echo $request['request_id']; ?>" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel">Download approved request</h3>
						</div>
						<div class="modal-body">
							<div class="well">
								<p>Choose your preferred format:</p>
								<?php $term = htmlentities($request['term']); ?>
								<p><a href="<?php echo base_url() . "discover/download_requested_data/" . $request['request_id'] . "/" . $term . "/" . $request['source'] . "/excel";?>" class="btn btn-info">Excel</a>&nbsp;&nbsp;<a href="<?php echo base_url() . "discover/download_requested_data/" . $request['request_id'] . "/" . $term . "/" . $request['source'] . "/tab";?>" class="btn btn-info">Tab-delimited</a>&nbsp;&nbsp;<a href="<?php echo base_url() . "discover/download_requested_data/" . $request['request_id'] . "/" . $term . "/" . $request['source'] . "/json";?>" class="btn btn-info">JSON</a></p>
							</div>
						</div>
						<div class="modal-footer">
							<a onclick="processDataRequest('approved', '<?php echo $request['request_id']; ?>');" href="#" class="btn btn-success">Confirm</a>  
							<a href="#" class="btn" data-dismiss="modal">Close</a>  
							<!-- <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Apply</button> -->
						</div>
					</div>
					<?php endforeach; ?>
			</div>
		</div>
	</div>



