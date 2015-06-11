<?php

$externalurl = $_POST["externalurl"];
error_log("php external -> $externalurl");
$api_url = "http://localhost/cafevariome_server/api/auth/checkexternalurl/format/json";
//$api_url = "https://auth.cafevariome.org/api/auth/checkexternalurl/format/json";
$data = array( 'externalurl' => $externalurl);
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
