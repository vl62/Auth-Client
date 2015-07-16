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
							<th colspan="2" align="center" class="title">openAccess</th>
							<th colspan="2" align="center" class="title">linkedAccess</th>
							<th colspan="2" align="center" class="title">restrictedAccess</th>
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
									<td><a rel="popover" data-content="Click for a description of this source (opens in a new window)." data-original-title="Source Information" href="<?php echo base_url('discover/source/' . $source); ?>" target="_blank"><?php echo $sources_full[$source]; ?></a></td>
								<?php else: ?>
									<td><a rel="popover" data-content="Click for a description of this source (opens in a new window)." data-original-title="Source Information" href="<?php echo base_url('discover/source/' . $federated_source); ?>" target="_blank"><?php echo $sources_full[$source]; ?></a></td>
								<?php endif; ?>
							<?php endif; ?>
							<td><?php if ( array_key_exists('openAccess', $count )) { if ( $count['openAccess'] == 0 ) { echo "0"; } elseif ( $count['openAccess'] > $this->config->item('variant_count_cutoff') ) { echo $count['openAccess']; } else { ?> <a href="#" rel="popover" data-content="<?php echo $this->config->item('variant_count_cutoff_message'); ?>" data-original-title="Records"><i class="icon-question-sign"></i></a> <?php }} else { echo "0";}?></td>
							<td> 
								<?php if ( array_key_exists('openAccess', $count )): ?>
									<?php if ( $source_types[$source] == "api" ): ?>
										<?php echo anchor($source_info[$source]['uri'] . "/discover/variants/$term/" . $node_source[$source] . "/openAccess", img(array('src' => base_url('resources/images/cafevariome/cafevariome_node.png'),'border'=>'0','alt'=>'Request Data')),array('class'=>'imglink', 'target' => '_blank', 'rel' => "popover", 'data-content' => "Click to access these records on the remote node. N.B. All access control to these records is controlled by the remote node.", 'data-original-title' => "Access Node Records")); ?>
									<?php elseif ( $source_types[$source] == "central" ): ?>
										<?php echo anchor("http://www.cafevariome.org/discover/variants/$term/" . $central_source[$source] . "/openAccess", img(array('src' => base_url('resources/images/cafevariome/cafevariome_node.png'),'border'=>'0','alt'=>'Request Data')),array('class'=>'imglink', 'target' => '_blank', 'rel' => "popover", 'data-content' => "Click to access these records in Cafe Variome Central. N.B. All access control to these records is controlled by Cafe Variome Central.", 'data-original-title' => "Access CV Central Records")); ?>
									<?php elseif ( $source_types[$source] == "federated" ): ?>
										<?php if ( $count['openAccess'] == 0 ): ?>
											<?php echo "0"; ?>
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
							<td><?php if ( array_key_exists('linkedAccess', $count )) { echo $count['linkedAccess'];} else { echo "0";}?></td>
							<td>
								<?php if ( array_key_exists('linkedAccess', $count )): ?>
									<?php if ( $source_types[$source] == "central" ): ?>
										<?php echo anchor("http://www.cafevariome.org/discover/variants/$term/" . $central_source[$source] . "/linkedAccess", img(array('src' => base_url('resources/images/cafevariome/cafevariome_node.png'),'border'=>'0','alt'=>'Request Data')),array('class'=>'imglink', 'target' => '_blank', 'rel' => "popover", 'data-content' => "Click to access these records in Cafe Variome Central. N.B. All access control to these records is controlled by Cafe Variome Central.", 'data-original-title' => "Access CV Central Records")); ?>
									<?php elseif ( $source_types[$source] == "federated" ): ?>
										<?php if ( $count['linkedAccess'] == 0 ): ?>
											<?php echo "0"; ?>
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
							<td><?php if ( array_key_exists('restrictedAccess', $count )) { if ( $count['openAccess'] == 0 ) { echo "0"; } elseif ( $count['restrictedAccess'] > $this->config->item('variant_count_cutoff') ) { echo $count['restrictedAccess'];} else { ?> <a href="#" rel="popover" data-content="<?php echo $this->config->item('variant_count_cutoff_message'); ?>" data-original-title="Records"><i class="icon-question-sign"></i></a> <?php }} else { echo "0";}?></td>
							<td>
								<?php if ( array_key_exists('restrictedAccess', $count )) : ?>
									<?php if ( $source_types[$source] == "central" ): ?>
										<?php echo anchor("http://www.cafevariome.org/discover/variants/$term/" . $central_source[$source] . "/restrictedAccess", img(array('src' => base_url('resources/images/cafevariome/cafevariome_node.png'),'border'=>'0','alt'=>'Request Data')),array('class'=>'imglink', 'target' => '_blank', 'rel' => "popover", 'data-content' => "Click to access these records in Cafe Variome Central. N.B. All access control to these records is controlled by Cafe Variome Central.", 'data-original-title' => "Access CV Central Records")); ?>
									<?php elseif ( $source_types[$source] == "federated" ): ?>
										<?php if ( $count['restrictedAccess'] == 0 ): ?>
											<?php echo "0"; ?>
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
								<?php endif; ?>
										
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

