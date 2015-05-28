<div class="container">
	<div class="row-fluid">
		<div class="span12 pagination-centered" id="table_container">
			<div class="well">
				<legend>Data Request</legend>
				<div id="resultDisplay">
					<?php if ( $request['result'] == "pending" ): ?>
					<p>Please approve or refuse the data request:</p>
					<div class="row">
						<p>Optional reason for approval/refusal:</p>
						<?php echo form_open();
						$data = array(
							'name'	=> 'resultreason',
							'id'	=> 'resultreason',
							'maxlength'   => '200',
							'size'        => '50',
							'style'	=> 'width:50%'
						);
						echo form_input($data);
						?>
						<br />
						<a class="btn" onclick="requestResult('approved', '<?php print $request['string']; ?>');"><i class="icon-thumbs-up"></i>  Approve</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn" onclick="requestResult('refused', '<?php print $request['string']; ?>');"><i class="icon-thumbs-down"></i>  Refuse</a>
						<?php echo form_close(); ?>
					</div>
					<?php else: ?>
						<p>This request has already been processed.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div><!--/.container-->
