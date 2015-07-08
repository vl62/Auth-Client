<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset3">
			<h1>Delete Variants</h1>
			<br /><span class="label label-warning">Warning: Are you sure you want to delete all variants for '<?php echo $source; ?>'</span><br /><br />
			<?php echo form_open("variants/delete/" . $source); ?>
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
			<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-remove icon-white"></i>  Delete Variants</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php if ($this->session->userdata('admin_or_curate') == "curate") { echo base_url() . "curate/variants"; } else { echo base_url() . "admin/variants"; }?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>