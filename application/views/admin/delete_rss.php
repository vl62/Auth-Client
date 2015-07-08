<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset3">
			<h1>Delete Entry</h1>
			<br /><span class="label label-warning">Warning: Are you sure you want to delete the entry '<?php echo $entry['post_title']; ?>'</span><br /><br />
			<?php echo form_open("feed/delete_post/" . $entry['id']); ?>
			<p>
				<label for="confirm">Yes:</label>
				<input type="radio" name="confirm" value="yes" checked="checked" />
				<br />
				<br />
				<label for="confirm">No:</label>
				<input type="radio" name="confirm" value="no" />
			</p>
			<br />
			<?php echo form_hidden(array('id' => $entry['id'])); ?>
			<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-remove icon-white"></i>  Delete Entry</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "feed/edit";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>