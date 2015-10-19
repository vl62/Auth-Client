<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row-fluid">
		<div class="span8 offset3">
			<h1>Edit Group</h1>
			<p>Please enter the group information below.</p>

			<div id="infoMessage"><b><?php echo $message; ?></b></div>
			<?php echo form_open(current_url()); ?>

			<p>
				Group Name: <br />
				<?php echo form_input($group_name); ?>
			</p>

			<p>
				Group Description: <br />
				<?php echo form_input($group_description); ?>
			</p>

			<p><?php echo form_submit('submit', 'Save Group'); ?></p>
		</div>
	</div>
</div>
<?php echo form_close(); ?>