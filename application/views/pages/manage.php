<style type="text/css">
	
	textarea {
    height: 25em;
    width: 50em;
}

</style>


<div class="container">

<h3>Select to INCLUDE/EXCLUDE Subjects from the Study</h3>


	<div class="row">
		<div class="span3 pagination-centered">
		<a href="<?php echo base_url() . "manage/include_subjects";?>" class="btn btn-info btn-large" rel="popover">
		<img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-manage.png" />
		</a>
		<br />Include Subjects</div>
		

		<div class="span3 pagination-centered">
		<a href="<?php echo base_url() . "manage/exclude_subjects";?>" class="btn btn-info btn-large" rel="popover">
		<img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-manage.png" />
		</a>
		<br />Exclude Subjects</div>
	</div>

<!-- <button type="button" onclick="window.location.href=window.location.href+'include_subjects'">INCLUDE Subjects</button>
<BR>
<button type="button" onclick="window.location.href=window.location.href+'exclude_subjects'">EXCLUDE Subjects</button>
 -->
</div>