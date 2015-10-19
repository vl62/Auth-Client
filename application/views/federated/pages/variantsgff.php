##gff-version 3
<?php
ksort($variants);
foreach ($variants as $variant) {
	$ref = isset($variant['ref']) ? $variant['ref'] : '';
	$hgvs = isset($variant['hgvs']) ? $variant['hgvs'] : '';
	$gene = isset($variant['gene']) ? $variant['gene'] : '';
	$location_ref = isset($variant['location_ref']) ? $variant['location_ref'] : '';
	$start = isset($variant['start']) ? $variant['start'] : '';
	$end = isset($variant['end']) ? $variant['end'] : '';
	if ( $location_ref && $start && $end ) {
		echo $location_ref . "\t" . "cafevariome" . "\t" . "variant" . "\t" . $start . "\t" . $end . "\t" . "." . "\t" . "." . "\t" . "." . "\t" . "ID=" . $variant['cafevariome_id'] . ";Name=" . $variant['cafevariome_id'] . "->" . $ref . ":" . $hgvs . "\n";
	}
}
