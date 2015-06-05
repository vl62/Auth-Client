<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Helper for shared federated auth functions

function authPostRequest($token, $data, $uri) {
//	error_log("test $token $uri");
//	error_log(print_r($data, 1));
//	$w = stream_get_wrappers();
//	echo 'openssl: ',  extension_loaded  ('openssl') ? 'yes':'no', "<br />";
//	echo 'http wrapper: ', in_array('http', $w) ? 'yes':'no', "<br />";
//	echo 'https wrapper: ', in_array('https', $w) ? 'yes':'no', "<br />";
//	echo 'wrappers: ', var_dump($w);

	
	$url = $uri . '/format/json';
	$url = preg_replace('/([^:])(\/{2,})/', '$1/', $url);
//	error_log("url -> $url");
//        echo $url;
        
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Token: $token",
		"Access-Control-Allow-Origin: *"
	));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
//	error_log($result);
    echo curl_error($ch);
    curl_close($ch);
    return $result;
	
//	
//	$url = $uri . '/format/json';
//	error_log($url);
//	$opts = array('http' =>
//		array(
//			'method'  => 'POST',
//			'header'  =>	"Content-type: application/x-www-form-urlencoded\r\n" .
//							"Token: $token\r\n" .
//							"Access-Control-Allow-Origin: *\r\n",
//			'content' => http_build_query($data)
//		)
//	);
//	$context  = stream_context_create($opts);
//	$result = file_get_contents($url, false, $context);
//	return $result;
}

function authGetRequest($token, $uri) {
	$url = $uri . '/format/json';
//	error_log("token -> $token");
//	error_log("url -> $url");
//$arrContextOptions=array(
//    ,
//);  
	$opts = array('http' =>
		array(
			'method'  => 'GET',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
						 "Token: $token\r\n" .
						 "Access-Control-Allow-Origin: *\r\n"
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
}


?>