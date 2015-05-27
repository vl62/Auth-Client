<div class="container">
	<div class="row">
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "federated_settings";?>">Federated Settings</a> <span class="divider">></span>
				</li>
				<li class="active">Network Requests</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span12 pagination-centered">
			<div class="well">
				<legend>Data Requests</legend>

				<?php // if (empty($network_requests)): ?>
				<?php if (array_key_exists('error', $network_requests)): ?>
					<p>You currently do not have any outgoing network requests.</p>
				<?php else: ?>
					<table class="table table-bordered table-striped table-hover generaltable">
						<thead>
							<tr>
								<th>ID</th>
                                <th>Network Name</th>
								<th>Justification</th>
								<th>Result Reason</th>
								<th>Result</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($network_requests as $request_id => $request): ?>
							<tr>
								<td><?php echo $request['request_id']; ?></td>
								<td><?php echo $request['network_name']; ?></td>
								<td><?php echo $request['justification']; ?></td>
								<td><?php if ( $request['resultreason'] ) { echo $request['resultreason']; } else { echo "-"; }?></td>
								<td><?php if ( $request['result'] == "approved" ) { echo '<span class="badge badge-success">Approved</span>'; } elseif ( $request['result'] == "refused" ) { echo '<span class="badge badge-inverse">Refused</span>'; } else { echo '<span class="badge">Pending</span>'; } ?></td>
								<td>
									<a href="#deleteNetworkRequestModal<?php echo $request['request_id']; ?>" data-toggle="modal" data-backdrop="false" rel='popover' data-content='Click to delete the network join request.' data-original-title='Delete Request' ><i class='icon-trash'> </i></a>
								</td>
							</tr>
				
							<?php endforeach; ?>
						</tbody>
					</table>
					
					<?php  foreach ($network_requests as $request_id => $request): ?>

					
					<div id="deleteNetworkRequestModal<?php echo $request['request_id']; ?>" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 id="myModalLabel">Delete network request?</h4>
						</div>
						<div class="modal-footer">
							<a onclick="processDataRequest('delete', '<?php echo $request['request_id']; ?>');" href="#" class="btn btn-success">Confirm</a>  
							<a href="#" class="btn" data-dismiss="modal">Close</a>  
							 <!--<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Apply</button>--> 
						</div>
					</div>
					<?php  endforeach; ?>
				<?php endif; ?>
				<br />
			</div>
		</div>
	</div>
</div>


