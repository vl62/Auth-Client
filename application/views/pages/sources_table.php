<script type="text/javascript">
// Add popover function from Twitter Bootstrap (can't load it from the main cafevariome.js for the div that this table is displayed in)
$(function (){
	$("[rel=popover]").popover({placement:'right', trigger:'hover', animation:'true', delay: { show: 50, hide: 300 }});
});
</script>
<div class="container">
	<div class="row-fluid">
		<div class="span12 pagination-centered">
			<div class="well-group">
				<?php if ( empty($counts)): ?>
				<p>There is no data present in this installation! Data needs to be added through the administrator interface.</p>
				<?php else: ?>
				<?php if ( ! $this->config->item('show_sources_in_discover')): ?>
				<!--<h3>Variant Counts</h3><hr>-->
				<?php endif; ?>
				<table class="table table-hover table-bordered table-striped" id="discovertable">
					<thead>
						<tr>
							<?php if ( $this->config->item('show_sources_in_discover')): ?>
							<th align="center" class="title">Source</th>
							<?php endif; ?>
							<th colspan="2" align="center" class="title">Open Access</th>
							<th colspan="2" align="center" class="title">Linked Access</th>
							<th colspan="2" align="center" class="title">Restricted Access</th>
						</tr>
					</thead>
					<tbody>
						<?php
						ksort($counts);
//						error_log("view counts -> " . print_r($counts, 1));
//						error_log("sources_full -> " . print_r($sources_full, 1));
						foreach ( $counts as $source => $count ):
						?>
						<tr>
							<?php if ( $this->config->item('show_sources_in_discover')): ?>
								<?php $federated_source = preg_replace('/__install.*/', '', $source); ?>
								<?php if ( $source_types[$source] == "federated" ): ?>
									<td><a><?php echo $sources_full[$source]; ?></a></td>
								<?php else: ?>
									<td><a><?php echo $sources_full[$source]; ?></a></td>
								<?php endif; ?>
							<?php endif; ?>
							<td>
								<?php if ( array_key_exists('openAccess', $count )): ?>
									<?php if ( $count['openAccess'] === 0 ): ?>
										<?php echo "0"; ?>
									<?php elseif ( $count['openAccess'] === "BLOCKED" ): ?>
											<a href="#" rel="popover" data-content="The display of record counts has been limited to specific users for this source." data-original-title="Unable to view counts"><i class="fa fa-ban fa-2x"></i></a>
									<?php elseif ( $count['openAccess'] > $this->config->item('variant_count_cutoff') ): ?>
										<?php echo $count['openAccess']; ?>
									<?php else: ?>
										<a href="#" rel="popover" data-content="<?php echo $this->config->item('variant_count_cutoff_message'); ?>" data-original-title="Records"><i class="icon-question-sign"></i></a>
									<?php endif; ?>
								<?php else: ?>
									<?php echo "0"; ?>
								<?php endif; ?>
							</td>
							<td> 
								<?php if ( array_key_exists('openAccess', $count )): ?>
									<?php if ( $source_types[$source] == "central" ): ?>
										<?php echo anchor("http://www.cafevariome.org/discover/variants/$term/" . $central_source[$source] . "/openAccess", img(array('src' => base_url('resources/images/cafevariome/cafevariome_node.png'),'border'=>'0','alt'=>'Request Data')),array('class'=>'imglink', 'target' => '_blank', 'rel' => "popover", 'data-content' => "Click to access these records in Cafe Variome Central. N.B. All access control to these records is controlled by Cafe Variome Central.", 'data-original-title' => "Access CV Central Records")); ?>
									<?php elseif ( $source_types[$source] == "federated" ): ?>
										<?php if ( $count['openAccess'] === 0 ): ?>
											<!-- <?php echo "0"; ?> -->
											<a rel="popover" data-content="Sorry, there are no records of this type available." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/cross.png'));?></a>
										<?php elseif ( $count['openAccess'] === "BLOCKED" ): ?>
											<a href="#" rel="popover" data-content="The display of record counts has been limited to specific users for this source." data-original-title="Unable to view counts"><i class="fa fa-ban fa-2x"></i></a>
										<?php elseif ( $count['openAccess'] > $this->config->item('variant_count_cutoff') ): ?>
											<a rel="popover" data-content="Click to access these records." data-original-title="Access Records"> <input type="image" onclick="javascript:variantOpenAccessRequestFederated('<?php echo urlencode($term);?>', '<?php echo $federated_source;?>', '<?php echo $sources_full[$source];?>', '<?php echo $count['openAccess'];?>', '<?php echo urlencode(base64_encode($install_uri[$source])); ?>')" src="<?php echo base_url('resources/images/cafevariome/request.png');?>"></a>
										<?php else: ?>
											<a href="#" rel="popover" data-content="<?php echo $this->config->item('variant_count_cutoff_message'); ?>" data-original-title="Records"><i class="icon-question-sign"></i></a>
										<?php endif; ?>
									<?php else: ?>
										<?php if ( $count['openAccess'] > $this->config->item('variant_count_cutoff') ): ?>
											<a rel="popover" data-content="Click to access these records." data-original-title="Access Records"> <input type="image" onclick="javascript:variantOpenAccessRequest('<?php echo urlencode($term);?>', '<?php echo $source;?>', '<?php echo $sources_full[$source];?>', '<?php echo $count['openAccess'];?>')" src="<?php echo base_url('resources/images/cafevariome/request.png');?>"></a>
										<?php else: ?>
											<a href="#" rel="popover" data-content="<?php echo $this->config->item('variant_count_cutoff_message'); ?>" data-original-title="Records"><i class="icon-question-sign"></i></a>
										<?php endif; ?>
									<?php endif; ?>
								<?php else: ?>
									<a rel="popover" data-content="Sorry, there are no records of this type available." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/cross.png'));?></a>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( array_key_exists('linkedAccess', $count )): ?>
									<?php if ( $count['linkedAccess'] === 0 ): ?>
										<?php echo "0"; ?>
									<?php elseif ( $count['linkedAccess'] === "BLOCKED" ): ?>
											<a href="#" rel="popover" data-content="The display of record counts has been limited to specific users for this source." data-original-title="Unable to view counts"><i class="fa fa-ban fa-2x"></i></a>
									<?php elseif ( $count['linkedAccess'] > $this->config->item('variant_count_cutoff') ): ?>
										<?php echo $count['linkedAccess']; ?>
									<?php else: ?>
										<a href="#" rel="popover" data-content="<?php echo $this->config->item('variant_count_cutoff_message'); ?>" data-original-title="Records"><i class="icon-question-sign"></i></a>
									<?php endif; ?>
								<?php else: ?>
									<?php echo "0"; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( array_key_exists('linkedAccess', $count )): ?>
									<?php if ( $source_types[$source] == "central" ): ?>
										<?php echo anchor("http://www.cafevariome.org/discover/variants/$term/" . $central_source[$source] . "/linkedAccess", img(array('src' => base_url('resources/images/cafevariome/cafevariome_node.png'),'border'=>'0','alt'=>'Request Data')),array('class'=>'imglink', 'target' => '_blank', 'rel' => "popover", 'data-content' => "Click to access these records in Cafe Variome Central. N.B. All access control to these records is controlled by Cafe Variome Central.", 'data-original-title' => "Access CV Central Records")); ?>
									<?php elseif ( $source_types[$source] == "federated" ): ?>
										<?php if ( $count['linkedAccess'] === 0 ): ?>
											<!-- <?php echo "0"; ?> -->
											<a rel="popover" data-content="Sorry, there are no records of this type available." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/cross.png'));?></a>
										<?php elseif ( $count['linkedAccess'] === "BLOCKED" ): ?>
											<a href="#" rel="popover" data-content="The display of record counts has been limited to specific users for this source." data-original-title="Unable to view counts"><i class="fa fa-ban fa-2x"></i></a>
										<?php else: ?>
											<a href="<?php echo base_url(); ?>discover/variants_federated/<?php echo urlencode($term); ?>/<?php echo $federated_source;?>/<?php echo urlencode(base64_encode($install_uri[$source])); ?>/linkedAccess" target="_blank" rel="popover" data-content="Click to access these records." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/request.png'));?></a>
										<?php endif; ?>
									<?php else: ?>
										<a href="<?php echo base_url(); ?>discover/variants/<?php echo urlencode($term); ?>/<?php echo $source;?>/linkedAccess" target="_blank" rel="popover" data-content="Click to access these records." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/request.png'));?></a>
									<?php endif; ?>
								<?php else: ?>
									<a rel="popover" data-content="Sorry, there are no records of this type available." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/cross.png'));?></a>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( array_key_exists('restrictedAccess', $count )): ?>
									<?php if ( $count['restrictedAccess'] === 0 ): ?>
										<?php echo "0"; ?>
									<?php elseif ( $count['restrictedAccess'] === "BLOCKED" ): ?>
										<a href="#" rel="popover" data-content="The display of record counts has been limited to specific users for this source." data-original-title="Unable to view counts"><i class="fa fa-ban fa-2x"></i></a>
									<?php elseif ( $count['restrictedAccess'] > $this->config->item('variant_count_cutoff') ): ?>
										<?php echo $count['restrictedAccess']; ?>
									<?php else: ?>
										<a href="#" rel="popover" data-content="<?php echo $this->config->item('variant_count_cutoff_message'); ?>" data-original-title="Records"><i class="icon-question-sign"></i></a> 
									<?php endif; ?>
								<?php else: ?>
									<?php echo "0"; ?>
								<?php endif; ?>
							</td>
							<td> 
							
							<?php if ( array_key_exists('restrictedAccess', $count )): ?>
									<?php if(!$view_derids): ?>
										<?php if ( $count['restrictedAccess'] === 0 ): ?>
											<!-- <?php echo "0"; ?> -->
											<a rel="popover" data-content="Sorry, there are no records of this type available." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/cross.png'));?></a>
										<?php else: ?>
											<a href="#" rel="popover" data-content="The display of DerIDs has been limited to specific users for this source." data-original-title="Unable to view DerIDs"><i class="fa fa-ban fa-2x"></i></a>
										<?php endif; ?>
										<!-- <a rel="popover" data-content="Sorry, there are no records of this type available." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/cross.png'));?></a> -->
										
									<?php elseif ( $source_types[$source] == "central" ): ?>
										<?php echo anchor("http://www.cafevariome.org/discover/variants/$term/" . $central_source[$source] . "/openAccess", img(array('src' => base_url('resources/images/cafevariome/cafevariome_node.png'),'border'=>'0','alt'=>'Request Data')),array('class'=>'imglink', 'target' => '_blank', 'rel' => "popover", 'data-content' => "Click to access these records in Cafe Variome Central. N.B. All access control to these records is controlled by Cafe Variome Central.", 'data-original-title' => "Access CV Central Records")); ?>
									<?php elseif ( $source_types[$source] == "federated" ): ?>
										<?php if ( $count['restrictedAccess'] === 0 ): ?>
											<!-- <?php echo "0"; ?> -->
											<a rel="popover" data-content="Sorry, there are no records of this type available." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/cross.png'));?></a>
										<?php elseif ( $count['restrictedAccess'] === "BLOCKED" ): ?>
											<a href="#" rel="popover" data-content="The display of record counts has been limited to specific users for this source." data-original-title="Unable to view counts"><i class="fa fa-ban fa-2x"></i></a>
										<?php elseif ( $count['restrictedAccess'] > $this->config->item('variant_count_cutoff') ): ?>
											<a href="<?php echo base_url();?>discover/variants_federated_restricted/<?php echo urlencode($term);?>/<?php echo $federated_source;?>/<?php echo urlencode(base64_encode($install_uri[$source]));?>" target="_blank" rel="popover" data-content="Click to view the DerIDs and email address of the source owner." data-original-title="Get DerIDs"> <?php echo img(base_url('resources/images/cafevariome/request.png'));?></a>
										<?php else: ?>
											<a href="#" rel="popover" data-content="<?php echo $this->config->item('variant_count_cutoff_message'); ?>" data-original-title="Records"><i class="icon-question-sign"></i></a>
										<?php endif; ?>
									<?php else: ?>
										<?php if ( $count['restrictedAccess'] > $this->config->item('variant_count_cutoff') ): ?>
											<a rel="popover" data-content="Click to access these records." data-original-title="Access Records"> <input type="image" onclick="javascript:variantOpenAccessRequest('<?php echo urlencode($term);?>', '<?php echo $source;?>', '<?php echo $sources_full[$source];?>', '<?php echo $count['openAccess'];?>')" src="<?php echo base_url('resources/images/cafevariome/request.png');?>"></a>
										<?php else: ?>
											<a href="#" rel="popover" data-content="<?php echo $this->config->item('variant_count_cutoff_message'); ?>" data-original-title="Records"><i class="icon-question-sign"></i></a>
										<?php endif; ?>
									<?php endif; ?>
								<?php else: ?>
									<a rel="popover" data-content="Sorry, there are no records of this type available." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/cross.png'));?></a>
								<?php endif; ?>
							
							
							<!--
							<?php if ( array_key_exists('restrictedAccess', $count )) : ?>
								<?php if ( $count['restrictedAccess'] > 0 ): ?>

									<a href="<?php echo base_url();?>discover/variants_federated_restricted/<?php echo urlencode($term);?>/<?php echo $federated_source;?>/<?php echo urlencode(base64_encode($install_uri[$source]));?>" target="_blank" rel="popover" data-content="Click to view the DerIDs and email address of the source owner." data-original-title="Get DerIDs"> <?php echo img(base_url('resources/images/cafevariome/request.png'));?></a>
									<!-- <a class="show_admin_emails" href="<?php echo $install_uri[$source]?>" target="_blank" rel="popover" data-content="Click to view the admin email id's of this source." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/request.png'));?></a> -->
								
							<!--
								<?php elseif ( $count['restrictedAccess'] == 0 ): ?>
									<?php echo "0"; ?>
								<?php else: ?>
									<a rel="popover" data-content="Sorry, there are no records of this type available." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/cross.png'));?></a>
								<?php endif; ?>
								<?php else: ?>
									<a rel="popover" data-content="Sorry, there are no records of this type available." data-original-title="Get DerIDs"> <?php echo img(base_url('resources/images/cafevariome/cross.png'));?></a>
							<?php endif; ?>
							-->
							<!-- old version	 
							<?php if(false) {
								if ( array_key_exists('restrictedAccess', $count )) : ?>
									<?php if ( $source_types[$source] == "central" ): ?>
										<?php echo anchor("http://www.cafevariome.org/discover/variants/$term/" . $central_source[$source] . "/restrictedAccess", img(array('src' => base_url('resources/images/cafevariome/cafevariome_node.png'),'border'=>'0','alt'=>'Request Data')),array('class'=>'imglink', 'target' => '_blank', 'rel' => "popover", 'data-content' => "Click to access these records in Cafe Variome Central. N.B. All access control to these records is controlled by Cafe Variome Central.", 'data-original-title' => "Access CV Central Records")); ?>
									<?php elseif ( $source_types[$source] == "federated" ): ?>
										<?php if ( $count['restrictedAccess'] === 0 ): ?>
											<?php echo "0"; ?>
										<?php elseif ( $count['restrictedAccess'] === "BLOCKED" ): ?>
											<a href="#" rel="popover" data-content="The display of record counts has been limited to specific users for this source." data-original-title="Unable to view counts"><i class="fa fa-ban fa-2x"></i></a>
										<?php elseif ( $count['restrictedAccess'] > $this->config->item('variant_count_cutoff') ): ?>
											<?php echo anchor("discover/variants/" . urlencode($term) . "/$source/restrictedAccess", img(array('src' => base_url('resources/images/cafevariome/request-icon.png'),'border'=>'0','alt'=>'Request Data')),array('class'=>'imglink', 'target' => '_blank', 'rel' => "popover", 'data-content' => "Click to request access to these records (requires login).", 'data-original-title' => "Access Records")); ?>
										<?php else: ?>
											<a href="#" rel="popover" data-content="<?php echo $this->config->item('variant_count_cutoff_message'); ?>" data-original-title="Records"><i class="icon-question-sign"></i></a>
										<?php endif; ?>

									<?php else: ?>
										<?php if ( $count['restrictedAccess'] > $this->config->item('variant_count_cutoff') ): ?>
											<?php echo anchor("discover/variants/" . urlencode($term) . "/$source/restrictedAccess", img(array('src' => base_url('resources/images/cafevariome/request-icon.png'),'border'=>'0','alt'=>'Request Data')),array('class'=>'imglink', 'target' => '_blank', 'rel' => "popover", 'data-content' => "Click to request access to these records (requires login).", 'data-original-title' => "Access Records")); ?>
										<?php else: ?>
											<a href="#" rel="popover" data-content="<?php echo $this->config->item('variant_count_cutoff_message'); ?>" data-original-title="Records"><i class="icon-question-sign"></i></a>
										<?php endif; ?>
									<?php endif; ?>

								<?php else: ?>
									<a rel="popover" data-content="Sorry, there are no records of this type available." data-original-title="Access Records"> <?php echo img(base_url('resources/images/cafevariome/cross.png'));?></a>
								<?php endif;
								} ?>
							-->			
							</td>
						<?php
						endforeach;
//						echo anchor("discover/variants/$term/$source/restrictedAccess", img(array('src'=> base_url('resources/images/cafevariome/request-icon.png'),'border'=>'0','alt'=>'Request Data')),array('class'=>'imglink', 'target' => '_blank'));
//						echo anchor("discover/variants/$term/$source/restrictedAccess", img(base_url('resources/images/cafevariome/request-icon.png')));
						?>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
				<br />
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<hr>

<div class="modal hide fade in" style="display: none;" id="modalEmailInfo" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close closeModal" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">List of Admin Email Id's</h4>
                </div>
                <div class="modal-body" id="email_list">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default closeModal" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

