<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset3">
			<h1>Delete Source</h1>
			<br /><span class="label label-warning">Warning: Are you sure you want to delete the source '<?php echo $source; ?>'</span><br /><br />
			<?php echo form_open("admin/delete_source/" . $source_id . "/" . $source); ?>
			<p>
				<label for="confirm">Yes:</label>
				<input type="radio" name="confirm" value="yes" checked="checked" />
				<br />
				<br />
				<label>No:</label>
				<input type="radio" name="confirm" value="no" />
				<br />
				<br />
				<br />
				<span class="label label-info">Also delete all variants linked to this source (<?php echo $source; ?>):</span><br />
				<table border="0">
					<tr>
						<td>Yes:</td><td><input type="radio" name="variants" value="yes" checked="checked" /></td>
						<td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td>
						<td>No:</td><td><input type="radio" name="variants" value="no" checked="checked" /></td>
					</tr>
				</table>
			</p>
			<br />
			<?php echo form_hidden(array('source' => $source)); ?>
			<?php echo form_hidden(array('source_id' => $source_id)); ?>
			<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-remove icon-white"></i>  Delete Source</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "admin/sources";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>