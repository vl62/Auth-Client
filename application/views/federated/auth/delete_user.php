<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset3">
			<h1>Delete User</h1>
			
			<br /><span class="label label-warning">Warning: Are you sure you want to delete the user '<?php echo $id ?>'</span><br /><br />
                        <strong id="deleteError" class="hide" style="color: red;"></strong>
                        <?php echo form_open("auth_federated/delete", array('name' => 'deleteUser')); ?>

			<p>
				<label for="confirm">Yes:</label>
				<input type="radio" name="confirm" value="yes" checked="checked" />
				<br />
				<br />
				<label for="confirm">No:</label>
				<input type="radio" name="confirm" value="no" />
			</p>
			<br />
			<?php echo form_hidden(array('id' => $id)); ?>

			<p><button type="submit" name="submit" onclick="delete_user();" class="btn btn-primary"><i class="icon-remove icon-white"></i>  Delete User</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "auth_federated/users";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>