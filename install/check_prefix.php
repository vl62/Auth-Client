<?php

$prefix = $_POST["prefix"];

//$api_url = "http://localhost/cafevariome/api/central/checkprefix/format/json";
$api_url = "http://www.cafevariome.org/api/central/checkprefix/format/json";
$data = array( 'prefix' => $prefix);
if ( empty($errors) ) {
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($api_url, false, $context);
	echo json_encode($result);
}

?>
