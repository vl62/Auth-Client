<?php

echo "Source\topenAccess\trestrictedAccess\tlinkedAccess\n";
ksort($counts);
foreach ( $counts as $source => $count ) {
	echo $source . "\t";
	
	if ( array_key_exists('openAccess', $count )) {
		if ( $count['openAccess'] > $this->config->item('variant_count_cutoff') ) {
			echo $count['openAccess'];
		}
		else {
			echo $this->config->item('variant_count_cutoff_message');
		}
	}
	else {
		echo "0";
	}

	echo "\t";
							
	if ( array_key_exists('restrictedAccess', $count )) {
		if ( $count['restrictedAccess'] > $this->config->item('variant_count_cutoff') ) {
			echo $count['restrictedAccess'];
		}
		else {
			echo $this->config->item('variant_count_cutoff_message');
		}
	}
	else {
		echo "0";
	}

	echo "\t";
	
	if ( array_key_exists('linkedAccess', $count )) {
		echo $count['linkedAccess'];
	}
	else {
		echo "0";
	}
	
	echo "\n";

}
