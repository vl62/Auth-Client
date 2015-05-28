<?php
echo "source_id\tsource_description\n";
ksort($sources);
foreach ($sources as $source => $source_desc) {
	echo "$source\t$source_desc\n";
	
}
