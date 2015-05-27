<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Helper for shared federated auth functions

function authPostRequest($token, $data, $uri) {
	error_log("test $token $uri");
	error_log(print_r($data, 1));
	$url = $uri . '/format/json';
	error_log($url);
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  =>	"Content-type: application/x-www-form-urlencoded\r\n" .
							"Token: $token\r\n" .
							"Access-Control-Allow-Origin: *\r\n",
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
	
}

function authGetRequest($token, $uri) {
	$url = $uri . '/format/json';
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

function networksGetRequest($uri) {
	$url = $uri . '/format/json';
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

function networksPostRequest($data, $uri) {
	error_log(print_r($data, 1));
	$url = $uri . '/format/json';
	error_log($url);
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  =>	"Content-type: application/x-www-form-urlencoded\r\n" .
							"Token: $token\r\n" .
							"Access-Control-Allow-Origin: *\r\n",
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
	
}



?>