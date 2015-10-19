<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset3">
			<h1>Activate User</h1>
			<p>Are you sure you want to activate the user '<?php echo $id; ?>'</p>
                        
                        <strong id="activateError" class="hide" style="color: red;"></strong>
                        <?php echo form_open("auth_federated/activate", array('name' => 'activateUser')); ?>
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
                        <p><button type="submit" name="submit" onclick="activate_user();" class="btn btn-primary"><i class="icon-user icon-white"></i>  Activate User</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "auth_federated/users";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>