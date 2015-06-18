<?php

$externalurl = $_POST["externalurl"];
error_log("checking external url -> $externalurl");
$api_url = "http://localhost/cafevariome_server/api/auth_general/checkexternalurl/format/json";
//$api_url = "https://auth.cafevariome.org/api/auth_general/checkexternalurl/format/json";
$data = array( 'externalurl' => $externalurl);


$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_HEADER, false);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//	"Token: $token",
//	"Access-Control-Allow-Origin: *"
//));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_REFERER, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
$result = curl_exec($ch);
//error_log($result);
//error_log(curl_error($ch));
curl_close($ch);
echo json_encode($result);
//return $result;


//if ( empty($errors) ) {
//	$opts = array('http' =>
//		array(
//			'method'  => 'POST',
//			'header'  => 'Content-type: application/x-www-form-urlencoded',
//			'content' => http_build_query($data)
//		)
//	);
//	$context  = stream_context_create($opts);
//	$result = file_get_contents($api_url, false, $context);
//	echo json_encode($result);
//}

?>
