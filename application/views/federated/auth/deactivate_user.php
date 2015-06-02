<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset3">
			<h1>Deactivate User</h1>
			<p>Are you sure you want to deactivate the user '<?php echo $id; ?>'</p>
                        
                        <strong id="deactivateError" class="hide" style="color: red;"></strong>
                        <?php echo form_open("auth_federated/deactivate", array('name' => 'deactivateUser')); ?>
			<p>
				<label for="confirm">Yes:</label>
				<input type="radio" name="confirm" value="yes" checked="checked" />
				<br />
				<br />
				<label for="confirm">No:</label>
				<input type="radio" name="confirm" value="no" />
			</p>

			<?php echo form_hidden(array('id' => $id)); ?>
			<br />
                        <p><button type="submit" name="submit" onclick="deactivate_user();" class="btn btn-primary"><i class="icon-user icon-white"></i>  Deactivate User</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "auth/users";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>