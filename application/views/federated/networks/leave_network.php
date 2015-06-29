<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset3">
			<h2>Leave Network</h2>
			
			<br /><span class="label label-warning">Warning: Are you sure you want to leave the network '<?php echo $network_name; ?>'</span><br /><br />
			<?php echo form_open("networks/leave_network/$network_key/$installation_count_for_network/$network_name"); ?>

			<p>
				<label for="confirm">Yes:</label>
				<input type="radio" name="confirm" value="yes" checked="checked" />
				<br />
				<br />
				<label for="confirm">No:</label>
				<input type="radio" name="confirm" value="no" />
			</p>
			<br />
			<?php echo form_hidden(array(
					'network_name' => $network_name,
					'installation_count_for_network' => $installation_count_for_network,
					'network_name' => $network_name
				));
			?>

			<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-remove icon-white"></i>  Leave Network</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "networks";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>