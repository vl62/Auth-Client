<style type="text/css">
	
textarea {
    height: 25em;
    width: 50em;
}

</style>

<div class="container">
	<h3>Submit the Records IDs to EXCLUDE them from the Study</h3>

	<form method="post">
		<textarea name="content"></textarea> <br>

		<h3>Reason for exclusion (optional)</h3>
  		<textarea name="reason" id="reason" style="width: 700px; height: 200px;" maxlength="512"></textarea><br>
		<input type="submit" value="Submit"> <em>(max. 512 characters)</em>
	</form> <br> <br>

  <p><a href="<?php echo base_url() . "manage";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
</div>