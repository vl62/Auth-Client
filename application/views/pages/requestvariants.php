<div class="container">
	<div class="row-fluid">
		<div class="span12 pagination-centered" id="table_container">
			<div class="well">
				<h3>Request Access to:</h3><h4><?php echo urldecode($term); ?> variants in <?php echo $source_full; ?></h4>
				<p>You do not have the required group level access to view these variants.</p><p>If you wish to request access, please give your justification to the owner:</p>
				<?php 
				echo validation_errors();
//				echo form_open('discover/requestsuccess');
				echo form_open();
				$data = array(
					'name'	=> 'justification',
					'id'	=> 'justification',
					'rows'	=> '8',
					'cols'	=> '10',
					'style'	=> 'width:50%',
					'value' => set_value('justification')
				);
				echo form_textarea($data);
				echo form_hidden('term', $term);
				echo form_hidden('source', $source);
				echo form_hidden('source_full', $source_full);
				?>
				<br />
				<button type="submit" class="btn"><i class="icon-envelope"></i>  Request Data</button>
				<?php echo form_close(); ?>
				<br /><br /><p>You will receive an email informing you the data owner's decision.</p>
			</div>
		</div>
	</div>
</div><!--/.container-->
