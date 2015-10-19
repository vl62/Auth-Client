<rss version="2.0"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:admin="http://webns.net/mvcb/"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:content="http://purl.org/rss/1.0/modules/content/">
	<channel>
		<title><?php echo $feed_name; ?></title>
		<link><?php echo $feed_url; ?></link>
		<description><?php echo $page_description; ?></description>
		<dc:creator><?php echo $creator_email; ?></dc:creator>
		<dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
		<admin:generatorAgent rdf:resource="http://www.cafevariome.org/" />
		<?php foreach ($variants as $variant): ?>
		<item>
			<title><?php echo $this->config->item('cvid_prefix') . $variant['cafevariome_id']; ?></title>
			<link><?php echo site_url('discover/variant/' . $variant['cafevariome_id']); ?></link>
			<guid><?php echo site_url('discover/variant/' . $variant['cafevariome_id']); ?></guid>
			<description>
				<?php if ( isset($variant['ref']) ) { echo $variant['ref'] . ":" . $variant['hgvs'] . " (" . $variant['gene'] . ")"; } else { echo $variant['hgvs'] . " (" . $variant['gene'] . ")"; }?>
			</description>
			<?php if ( isset($variant['date_time']) ): ?>
			<pubDate><?php echo $variant['date_time']; ?></pubDate>
			<?php endif; ?>
			<summary type="html">
				<?php  if ( isset($variant['ref']) ) { echo $this->config->item('cvid_prefix') . $variant['cafevariome_id'] . "<br />" . $variant['gene'] . "<br />" . $variant['ref'] . "<br />" . $variant['hgvs'] . "<br />" . $variant['sharing_policy']; } else { echo $this->config->item('cvid_prefix') . $variant['cafevariome_id'] . "<br />" . $variant['gene'] . "<br />" . $variant['hgvs'] . "<br />" . $variant['sharing_policy']; } ?>
			</summary>
		</item>
		<?php endforeach; ?>
	</channel>
</rss> 
