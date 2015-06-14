<?php
// Print out the header based on what display fields are set
// Need to do a check to see if it's the last field - if it is then don't print a tab afterwards (TODO: find a better way of doing this)
$c = 0;
$total_fields = count($display_fields);
foreach ( $display_fields as $display_field ) {
	$c++;
	if ( $c == $total_fields ) {
		echo $display_field['visible_name'] . "\n";
	}
	else {
		echo $display_field['visible_name'] . "\t";
	}
}

//echo "cafevariome_id\tgene\tref\thgvs\tphenotype\tlocation_ref\tstart\tend\tbuild\tsource\tsource_url\tdate_time\tcomment\tsharing_policy\n";
// Now go through the variants and print out the field value for the display fields that are set
ksort($variants);
foreach ($variants as $variant) {
	$c = 0;
	foreach ( $display_fields as $display_field ) {
		$c++;
//		echo "(---> $c $total_fields) -> . " .  $display_field['name'] . "\n";
		if ( array_key_exists($display_field['name'], $variant) ) {
			if ( $c == $total_fields ) {
				if ( $display_field['name'] == "cafevariome_id" ) {
					echo $variant[$display_field['name']] . "\t";
				}
				else {
					echo $variant[$display_field['name']];
				}
			}
			else {
				if ( $display_field['name'] == "cafevariome_id" ) {
					echo $variant[$display_field['name']] . "\t";
				}
				else {
					echo $variant[$display_field['name']] . "\t";
				}
			}
		}
		else {
			echo "\t";
		}
	}
	echo "\n";
//	$ref = isset($variant['ref']) ? $variant['ref'] : '';
//	$hgvs = isset($variant['hgvs']) ? $variant['hgvs'] : '';
//	$gene = isset($variant['gene']) ? $variant['gene'] : '';
//	$phenotype = isset($variant['phenotype']) ? $variant['phenotype'] : '';
//	$date_time = isset($variant['date_time']) ? $variant['date_time'] : '';
//	$source_url = isset($variant['source_url']) ? $variant['source_url'] : '';
//	$location_ref = isset($variant['location_ref']) ? $variant['location_ref'] : '';
//	$start = isset($variant['start']) ? $variant['start'] : '';
//	$end = isset($variant['end']) ? $variant['end'] : '';
//	$build = isset($variant['build']) ? $variant['build'] : '';
//	$sharing_policy = isset($variant['sharing_policy']) ? $variant['sharing_policy'] : '';
//	$build = isset($variant['build']) ? $variant['build'] : '';
//	$comment = isset($variant['comment']) ? $variant['comment'] : '';
//	echo $this->config->item('cvid_prefix') . $variant['cafevariome_id'] . "\t" . $gene . "\t" . $ref . "\t" . $hgvs . "\t" . $phenotype . "\t" . $location_ref . "\t" . $start . "\t" . $end . "\t" . $build . "\t" .  $variant['source'] . "\t" . $source_url . "\t" . $date_time . "\t" . $comment . "\t" . $sharing_policy . "\n";
}
