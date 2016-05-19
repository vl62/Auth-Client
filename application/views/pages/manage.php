<style type="text/css">
	
	textarea {
    height: 25em;
    width: 50em;
}

</style>


<div class="container">
	<h3>Select to INCLUDE/EXCLUDE Records from the Study</h3>
	<div class="row">
		<div class="span3 pagination-centered">
		<a href="<?php echo base_url() . "manage/include_records";?>" class="btn btn-info btn-large" rel="popover">
		<img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-manage.png" />
		</a>
		<br />Include Records</div>
		

		<div class="span3 pagination-centered">
		<a href="<?php echo base_url() . "manage/exclude_records";?>" class="btn btn-info btn-large" rel="popover">
		<img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-manage.png" />
		</a>
		<br />Exclude Records</div>
	</div> <br> <br>

	<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "admin";?>" class="btn" ><i class="icon-home"></i> Admin Dashboard</a></p>
	
	<?php if($record_ids): ?>
		<br><br><br>
		<div class="row-fluid">
			<div class="span5 offset2">
				<table class="table table-striped table-bordered">
	 				<tr>
	 					<th style="text-align: center">List of excluded record IDs</th>
	 				</tr>
	 				<?php foreach ($record_ids as $record_id): ?>
						<tr>
	 						<td><?php echo $record_id['record_id']; ?></td>
	 					</tr>
					<?php endforeach ?>
				</table>
			</div>
		</div>
		<div class="row-fluid" style="margin-bottom: 100px;">
			<div class="span2 offset2">
				<?php if($id == 1): ?>
					<a href="<?php echo base_url() . "manage/index/" . ($id-1);?>" class="btn btn-primary hidden">&lt;&lt; Prev</a>
				<?php else: ?>
					<a href="<?php echo base_url() . "manage/index/" . ($id-1);?>" class="btn btn-primary">&lt;&lt; Prev</a>
				<?php endif; ?>
			</div>
			<div class="span2 offset5">
				<?php if($final == "yes"): ?>
					<a href="<?php echo base_url() . "manage/index/" . ($id + 1);?>" class="btn btn-primary hidden">Next &gt;&gt;</a>
				<?php else: ?>
					<a href="<?php echo base_url() . "manage/index/" . ($id + 1);?>" class="btn btn-primary">Next &gt;&gt;</a>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</div>