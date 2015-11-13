<style type="text/css">
	
	textarea {
    height: 25em;
    width: 50em;
}

<?php
    if ($this->session->flashdata('message')) {
    ?>
    <div class="message flash">
        <?php echo $this->session->flashdata('message'); ?>
    </div>
    <?php
    }
?>

</style>


<div class="container">

<h3>Enter the Encrypted IDs of the Subjects to INCLUDE from the Study</h3>

<form>
  <textarea name="content" id="content"></textarea>
  <BR>
  <input type="submit" value="Submit">
</form>

</div>