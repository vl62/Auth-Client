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
					<a href="<?php echo $rss_uri; ?>" ><em>Read more...</em></a>
				<?php else: ?>
					<p>There is currently no news,  <a href="<?php echo base_url('feed/edit'); ?>">add some news</a> or turn off news in the <a href="<?php echo base_url('admin/settings'); ?>">settings tab</a> in the administrators interface.</p>
				<?php endif; ?>
				<hr>
			</div><!--/well-->
        
