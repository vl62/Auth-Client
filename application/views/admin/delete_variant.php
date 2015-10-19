<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset3">
			<h1>Delete Variant</h1>
			<br /><span class="label label-warning">Warning: Are you sure you want to delete variant '<?php echo $this->config->item('cvid_prefix') . $id; ?>'</span><br /><br />
			<?php echo form_open("variants/delete_variant/" . $source . "/" . $id); ?>
			<p>
				<label for="confirm">Yes:</label>
				<input type="radio" name="confirm" value="yes" checked="checked" />
				<br />
				<br />
				<label for="confirm">No:</label>
				<input type="radio" name="confirm" value="no" />
			</p>
			<br />
			<?php echo form_hidden(array('source' => $source)); ?>
			<?php echo form_hidden(array('id' => $id)); ?>
			<input type="hidden" id="source_name" name="source_name" value="<?php echo $source; ?>" > <!-- Need to post source name so it can be passed back to delete_variant function in variants controller and used for lookup of whether curator has required access level -->
			<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i>  Confirm</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "variants/curate/$source";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>