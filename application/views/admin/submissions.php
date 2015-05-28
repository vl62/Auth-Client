<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Submissions</li>
			</ul>  
		</div>  
	</div> 
	<div class="row-fluid">
		<div class="span12 pagination-centered" id="table_container">
			<div class="well">
				<?php if(empty($submissions)): ?>
				<h4>Sorry, there are no submissions</h4>
				<?php else: ?>
				<h3>AtomServer Submissions</h3> 
				<div class="span7 offset2 pagination-centered"><div class="well"><p><button onclick="if(confirm('Are you sure you want to make the selected variants live?')) makeVariantsLiveMultiple();return false;" class="btn btn-success btn-small" rel="popover" data-content="Entry is made live in the main Cafe Variome database and the entry is flagged as deleted in the AtomServer feed (cannot be undone)." data-original-title="Make Variants Live"><i class="icon-ok"></i>  Make Selected Live</button><?php echo nbs(6); ?><button onclick="if(confirm('Are you sure you want to delete the selected variants?')) deleteAtomServerVariantsMultiple();return false;" class="btn btn-primary btn-small" rel="popover" data-content="Flags the submitted entry as deleted in the AtomServer feed (cannot be undone)." data-original-title="Delete Variants"><i class="icon-remove"></i>  Delete Selected</button><?php echo nbs(6); ?><button class="btn btn-info btn-small" rel="popover" data-content="Your Cafe Variome installation has the AtomServer submission system enabled. This interface allows you to curate the variants that are submitted to AtomServer, you may either make variants live or delete them. If you make variants live they will be transferred into your primary variant database table flagged as deleted in AtomServer." data-original-title="AtomServer Submission help"><i class="icon-question-sign"></i>  Help!</button></p></div></div>
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="submissionstable">
					<thead>
						<tr>
							<th><input type="checkbox" id="selectall" /></th>
							<th>Submission ID</th>
							<th>Gene</th>
							<th>Entry Content</th>
							<th>Created</th>
						</tr>
					</thead>
					<tbody>
						<?php
						ksort($submissions);
						$c = 0;
						foreach ( $submissions as $id => $variant ): // Loop through each submission ID
//							print "--> $id<br />";
							$c++;
						?>
						<tr>
							<td><?php $data = array( 'class' => 'case', 'name' => 'case', 'id' => $id, 'value' => $id, 'checked' => FALSE, 'style' => 'margin:10px',); echo form_checkbox($data); ?></td>
							<?php $cvid_link = base_url("/discover/variant/" . $id); ?>
							<td id="<?php echo $id; ?>"><?php echo $id; ?></td>
							<td><?php if ($variant['gene']) { echo $variant['gene']; } else { echo "-"; } ?></td>
							<td><button class="btn btn-info btn-small" href="#variantInfoModal<?php echo $c; ?>" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Click to view the variant information for this submission" data-original-title="AtomServer Data Content" ><i class="icon-info-sign"></i></button></td>
							<td><?php if ($variant['created']) { $pubDate = strftime("%d-%m-%Y %H:%M:%S", strtotime($variant['created'])); echo $pubDate; } else { echo "-"; } ?></td>
						</tr>
						<div id="variantInfoModal<?php echo $c; ?>" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h4 align="center" id="myModalLabel">Variants <?php echo $id; ?></h4>
							</div>
							<div class="modal-body">
								<div class="well">
									<?php foreach ( $variant as $key => $value ) { if ( isset($value) && $value != "" ) { print "<strong>$key:</strong> $value<br />"; } } ?>
								</div>
							</div>
							<div class="modal-footer">
								<a href="#" class="btn" data-dismiss="modal">Close</a>  
							</div>
						</div>
						<?php endforeach ?>
					</tbody>
				</table>
				<br />
				<?php endif; ?>
			</div>
		</div>
	</div>
</div><!--/.container-->
<div id="sharingPolicyModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Sharing Policy</h3>
	</div>
	<div class="modal-body">
		<div class="well">
			<p>Choose the sharing policy level you would like to set your selected variants to:</p><hr>
			<?php 
			$js = 'id="sharing_policy"'; 
			$options = array( 'openAccess'  => 'OpenAccess',
									'restrictedAccess'    => 'restrictedAccess',
									'linkedAccess'   => 'linkedAccess' );

			echo form_dropdown('sharing_policy', $options, 'openAccess', $js); ?>
		</div>
	</div>
	<div class="modal-footer">
		<a onclick="setSharingPolicyMultiple();" href="#" class="btn btn-success">Confirm</a>  
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
		<!-- <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Apply</button> -->
	</div>
</div>
	
<div id="addVariantsModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 align="center" id="myModalLabel">Add Variants To <?php echo $source_full; ?></h4>
	</div>
	<div class="modal-body">
		<div class="well">
			<p align="center">Select how you would like to import variants for this source:</p><hr>
			<p align="center"><a href="<?php echo base_url() . "variants/add/" . $source; ?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Manually enter variants</a><br /><br /><i>Use a form to manually enter variants one by one.</i></p>
			<hr>
			<p align="center"><a href="<?php echo base_url() . "variants/import/" . $source; ?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Bulk import variants</a><br /><br /><i>Use a bulk import tool to upload multiple variants at once (various formats accepted).</i></p>
			<hr>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>