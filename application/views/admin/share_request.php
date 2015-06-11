<div class="container">
	<div class="row-fluid">
		<div class="span12 pagination-centered" id="table_container">
			<div class="well">
				<legend>Share Invite</legend>
				<div id="resultDisplay">
					<div class="row">
						<?php $attributes = array('id' => 'share_result'); ?>
						<?php 
						$hidden = array(
							'md5' => $md5,
							'email' => $user['email']
							);
						?>
						<?php echo form_open("admin/share_result", $attributes, $hidden); ?>
						<select name="result" name="result">
							<option value="confirm">I confirm </option>
							<option value="refuse">I refuse </option>
						</select>
						<br /><br />
						the sharing invite with the following (optional) reason:
						<br /><br />

						<?php $data = array(
							'name'	=> 'resultreason',
							'id'	=> 'resultreason',
							'maxlength'   => '200',
							'size'        => '50',
							'style'	=> 'width:50%'
						);
						echo form_input($data);
						?>
						<br />
						<button type="submit" name="submit" class="btn btn-primary"><i class="icon-file icon-white"></i>  Submit</button>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!--/.container-->
