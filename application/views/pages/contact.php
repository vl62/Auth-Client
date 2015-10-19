<div class="container">
<!--<div class="container-fluid">-->
	<div class="row-fluid">
         <div class="span4 pagination-centered">
			<div class="well-group">
				<h4>Contact</h4><hr>
				Get in touch with your questions, comments and suggestions at:
				<?php echo br(2); ?>
				<?php echo mailto($this->config->item('email'));?>
				<?php echo br(2); ?>
				<?php if ($this->config->item('twitter')):
				echo anchor("http://www.twitter.com/" . $this->config->item('twitter'), img(base_url('resources/images/cafevariome/twitter.png')), array('target'=> '_blank', 'title' => 'Follow us on Twitter'));
				echo br(2);
				endif; ?>
				<?php 
				if ($this->config->item('rss')) {
					if ( $this->config->item('rss') == "local" ) { // local feed is specified in settings
						$rss_link = "feed";
					}
					else {
						$rss_link = $this->config->item('rss');
					}
					echo anchor($rss_link, img(base_url('resources/images/cafevariome/rss.png')), array('target'=> '_blank', 'title' => 'RSS Feed'));
				}
				?>
			</div>
		</div>
		<div class="span8 pagination-centered">
			<div class="well-group">
				<h4>Mailing List</h4><hr>
				<p>If you would like to be added to the Cafe Variome mailing list, please enter your details below (N.B. email addresses will not be handed on to others):</p>
				<?php if ( isset($success_message) ) { echo "<div id='success-alert' class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>&times;</button><h4>You were successfully subscribed to the mailing list</h4></div>"; } ?>
				<p>
					<?php
						echo '<b>' . validation_errors() . '</b>';
						echo form_open('about/contact');
					?>
					<label>Full Name:</label>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span>
						<?php
						$fullname_data = array('name' => 'fullname', 'id' => 'fullname', 'value' => set_value('fullname'));
						echo form_input($fullname_data);
						?>
					</div>
				</p>
				<p>
					<label>Email:</label>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-envelope"></i></span>
						<?php 
						$email_data = array('name' => 'email', 'id' => 'email', 'value' => set_value('email') );
						echo form_input($email_data);
						?>
					</div>
				</p>
				<br />
				<button type="submit" class="btn"><i class="icon-plus"></i> Add to list</button>
				</form>
			</div>
        </div>
	</div>
	<div class="row-fluid">
		<div class="span12 pagination-centered">
			<div class="well">
				<h4>Affiliations</h4><hr>
				<?php echo anchor_popup("http://www.le.ac.uk/genetics", img(base_url('resources/images/affiliations_partners/uol_logo.png'))); ?>
				<?php echo nbs(3); ?>
				<?php echo anchor_popup("http://www.gen2phen.org", img(base_url('resources/images/affiliations_partners/Gen2Phen_transparent.png'))); ?>
				<?php echo nbs(3); ?>
				<?php echo anchor_popup("http://www.phenosystems.com", img(base_url('resources/images/affiliations_partners/phenosystems.png'))); ?>
			</div>
		</div>
	</div>
	<hr>
</div><!--/.fluid-container-->