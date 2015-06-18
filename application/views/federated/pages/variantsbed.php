track name="<?php echo $sharing_policy; ?> Variants (<?php echo $term; ?><?php if ( $this->config->item('show_sources_in_discover')){ echo " in $source"; } ?>)" description="<?php echo $this->config->item('site_name'); ?> <?php echo $sharing_policy; ?> Variants (<?php echo $term; ?><?php if ( $this->config->item('show_sources_in_discover')){ echo " in $source"; } ?>)" visibility=3
<?php
ksort($variants);
foreach ($variants as $variant) {
	$ref = isset($variant['ref']) ? $variant['ref'] : '';
	$hgvs = isset($variant['hgvs']) ? $variant['hgvs'] : '';
	$gene = isset($variant['gene']) ? $variant['gene'] : '';
	$location_ref = isset($variant['location_ref']) ? $variant['location_ref'] : '';
	$start = isset($variant['start']) ? $variant['start'] : '';
	$end = isset($variant['end']) ? $variant['end'] : '';
	// Only print the variant if there's genomic coordinates available
	if ( $location_ref && $start && $end ) {
		if ( $start < $end ) {
			echo $location_ref . "\t" . $start . "\t" . $end . "\tID=" . $variant['cafevariome_id'] . ";HGVS=" . $ref . ":" . $hgvs . "\n";
		}
		else {
			echo $location_ref . "\t" . $end . "\t" . $start . "\tID=" . $variant['cafevariome_id'] . ";HGVS=" . $ref . ":" . $hgvs . "\n";
		}
	}
}
