<?php echo $basejs?>
<?php echo $header ?>
<div id="main" role="main" class="row">
	<?php echo $content_body ?>
</div>
<?php if ( ! $this->config->item('cafevariome_central') ): ?>
	<?php if ( strtolower(end($this->uri->segments)) == "contact" ): ?>
		<?php echo $footer; ?>
	<?php endif; ?>
<?php endif; ?>
