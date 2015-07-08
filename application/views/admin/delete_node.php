<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset3">
			<h1>Delete Node</h1>
			<br /><span class="label label-warning">Warning: Are you sure you want to delete node '<?php echo $name; ?>'</span><br /><br />
			<?php echo form_open("admin/delete_node/" . $name); ?>
			<p>
				<label for="confirm">Yes:</label>
				<input type="radio" name="confirm" value="yes" checked="checked" />
				<br />
				<br />
				<label for="confirm">No:</label>
				<input type="radio" name="confirm" value="no" />
			</p>
			<br />
			<?php echo form_hidden(array('name' => $name)); ?>
			<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-remove icon-white"></i>  Delete Node</button><?php echo nbs(6); ?><a href="<?php echo base_url() . "admin/settings";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>