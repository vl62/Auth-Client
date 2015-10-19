<script>
$(document).ready(function () {
	$.cookieCuttr();
});
</script>
<div class="container">
	<div class="span10 offset1 pagination-centered">
		<?php if ( isset($show_orcid_alert) && $show_orcid_alert ): ?>
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo "You have not linked your Cafe Variome account to an ORCID. It is strongly recommended that you do so.<br /><br />If you already have an ORCID then please enter this information in your " . anchor('auth/user_edit_profile/' . $user_id, 'profile') . " page. If you do not yet have an ORCID then go to " . anchor_popup('http://orcid.org/register', 'http://orcid.org/register') . " to register.<br /><br />This reminder message will only appear once."; ?>
		</div>
		<?php endif; ?>
	</div><!--/span-->
	
	
	<div class="row-fluid">
		<?php if ($this->config->item('rss')): ?>
		

		<div class="span3">

			<div class="well sidebar-nav">
				<h4><a href="<?php echo $rss_uri; ?>"><img src="<?php echo base_url(); ?>resources/images/cafevariome/rss-16x16.png" align="top" alt="Cafe Variome"/></a>&nbsp;&nbsp;News</h4>
				<hr>
				<?php
				$number_items = count($news);
				if (isset($news) AND $number_items > 0):
					foreach ($news as $news_item):
						$pubDate = $news_item->pubDate;
						$sort_date = date('Y-m-d H:i:s', strtotime($pubDate));
						$pubDate = strftime("%d %B %Y", strtotime($pubDate));
						?>
						<div class="news">
							<p><?php echo anchor($news_item->link, $news_item->title . "..."); echo br(); echo $pubDate; ?><hr></p>
						</div>
					<?php endforeach; ?>
					<?php echo anchor($rss_uri,"Read more... " . img(array('src'=> base_url('resources/images/cafevariome/feed-icon-16.gif'),'border'=>'0','alt'=>'RSS Feed')),array('class'=>'imglink')); ?>
				<?php else: ?>
					<p>There is currently no news,  <a href="<?php echo base_url('feed/edit'); ?>">add some news</a> or turn off news in the <a href="<?php echo base_url('admin/settings'); ?>">settings tab</a> in the administrators interface.</p>
				<?php endif; ?>
				<hr>
			</div><!--/well-->
        </div><!--/span-->
        <div class="span9 pagination-centered">
		<?php else: ?>
        <div class="span12 pagination-centered">
		<?php endif; ?>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="well"> <!-- style="background: #afb3ba;" -->
						<div class="row">
							<div class="span10 offset1">
								<h4>Welcome to Cafe Variome Central</h4><hr>
								<p>Are you part of a diagnostic network, disease consortium or country-wide network and wish to make your data discoverable to others in your network, or to the wider-world?</p>
							</div>
							<div class="span10 offset1">
								<p>The Cafe Variome software can alternatively be used..</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="well"> <!-- style="background: #afb3ba;" -->
						<div class="row">
							<div class="span10 offset1">
								<a href="<?php echo base_url() . "about/inabox";?>" class="btn btn-info btn-large" style="background: #8DEEEE;"><p>Are you part of a diagnostic network, disease consortium or country-wide network and wish to make your data discoverable to others in your network, or to the wider-world?</p><p>Click to find out more...</p></a>
							</div>
						</div>
					</div>
				</div>
			</div>

			
			
			<div class="well sidebar-nav">
				<div id="this-carousel-id" class="carousel slide"><!-- class of slide for animation -->
					<div class="carousel-inner">
						<div class="item active"><!-- class of active since it's the first item -->
							<a href="<?php echo base_url() . "about/cafevariome";?>" rel="popover" data-content="Click to see more information about Cafe Variome" data-original-title="Want to know more?"><img src="<?php echo base_url(); ?>resources/images/cafevariome/carousel/carousel_1.png" alt="" /></a>
							<div class="carousel-caption">
								<p>Data discovery platform for sequence variant data</p>
							</div>
						</div>
						<div class="item">
							<a href="<?php echo base_url() . "about/features";?>" rel="popover" data-content="Click to see the key features and screenshots of Cafe Variome" data-original-title="Interface and features"><img src="<?php echo base_url(); ?>resources/images/cafevariome/carousel/carousel_2.png" alt="" /></a>
							<div class="carousel-caption">
								<p>Clean, intuitive interface for complete control over data sharing</p>
							</div>
						</div>
						<div class="item">
							<!--<a href="<?php // echo base_url() . "about/scenarios";?>" rel="popover" data-content="Click to see common scenarios for a Cafe Variome installation" data-original-title="Installation Scenarios"></a>-->
							<img src="<?php echo base_url(); ?>resources/images/cafevariome/carousel/carousel_3.png" alt="" />
							<div class="carousel-caption">
								<p>Connecting disease consortia, diagnostic networks and 3rd parties (various sharing scenarios)</p>
							</div>
						</div>
						<div class="item">
							<a href="<?php echo base_url() . "about/get";?>" rel="popover" data-content="Click to see the ways you can get and use Cafe Variome" data-original-title="Get Cafe Variome?"><img src="<?php echo base_url(); ?>resources/images/cafevariome/carousel/carousel_4.png" alt="" /></a>
							<div class="carousel-caption">
								<p>Installations can be hosted by Cafe Variome or installed locally</p>
							</div>
						</div>
					</div><!-- /.carousel-inner -->
					<!--  Next and Previous controls below, href values must reference the id for this carousel -->
					<a class="carousel-control left" href="#this-carousel-id" data-slide="prev">&lsaquo;</a>
					<a class="carousel-control right" href="#this-carousel-id" data-slide="next">&rsaquo;</a>
				</div><!-- /.carousel -->
			</div>
			<!--<div class="row-fluid">&nbsp;</div>-->
			<div class="row-fluid">
				<div class="span12">
					<div class="well">
						<?php if (!$this->ion_auth->logged_in()): ?>
						<div style="text-align: center;">
							<table align="center" border="0">
								<tr>
									<td><?php echo nbs(5); ?><a href="<?php echo base_url() . "auth/login";?>" class="btn btn-info btn-large" rel="popover" data-content="Login to your Cafe Variome account" data-original-title="Login"><img width="100" height="100" src="<?php echo base_url();?>resources/images/cafevariome/icon-login.png" /></a><?php echo nbs(5); ?></td>
									<td><?php echo nbs(5); ?><a href="<?php echo base_url() . "auth/signup";?>" class="btn btn-info btn-large" rel="popover" data-content="Sign up for a Cafe Variome account" data-original-title="Sign up"><img width="100" height="100" src="<?php echo base_url();?>resources/images/cafevariome/icon-sign-up.png" /></a><?php echo nbs(5); ?></td>
									<td><?php echo nbs(5); ?><a href="<?php echo base_url() . "variants/stats";?>" class="btn btn-info btn-large" rel="popover" data-content="See numbers of variants available for discovery in Cafe Variome sources" data-original-title="Variant Stats"><img width="100" height="100" src="<?php echo base_url();?>resources/images/cafevariome/icon-variants.png" /></a><?php echo nbs(5); ?></td>
								</tr>
								<tr>
									<td>Login</td>
									<td>Signup</td>
									<td>Source Stats</td>
								</tr>
							</table>
						</div>
						<?php else: ?>
						<div style="text-align: center;">
							<table align="center" border="0">
								<tr >
									<td><?php echo nbs(5); ?><a href="<?php echo base_url() . "variants/stats"; ?>" class="btn btn-info btn-large" rel="popover" data-content="See numbers of variants available for discovery in Cafe Variome sources" data-original-title="Variant Stats"><img width="100" height="100" src="<?php echo base_url();?>resources/images/cafevariome/icon-variants.png" /></a><?php echo nbs(5); ?></td>
									<td><?php echo nbs(5); ?><a href="<?php echo base_url() . "auth/user_profile/" . $user_id; ?>" class="btn btn-info btn-large" rel="popover" data-content="Edit the user details for your Cafe Variome account" data-original-title="Edit Profile"><img width="100" height="100" src="<?php echo base_url();?>resources/images/cafevariome/icon-profile.png" /></a><?php echo nbs(5); ?></td>
									<td><?php echo nbs(5); ?><a href="<?php echo base_url() . "discover"; ?>" class="btn btn-info btn-large" rel="popover" data-content="Start discovering variants" data-original-title="Discover Data"><img width="100" height="100" src="<?php echo base_url();?>resources/images/cafevariome/icon-discover.png" /></a><?php echo nbs(5); ?></td>
								</tr>
								<tr>
									<td>Source Stats</td>
									<td>Edit Profile</td>
									<td>Discover</td>
								</tr>
							</table>
						</div>
						<?php endif; ?>
					</div>
				</div><!--/span-->
			</div><!--/row-->
        </div><!--/span-->
	</div><!--/row-->
	<hr>
</div><!--/container-->