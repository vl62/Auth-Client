<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Helper for shared federated auth functions

function authPostRequest($token, $data, $uri) {
//	error_log(print_r($data, 1));
//	$w = stream_get_wrappers();
//	echo 'openssl: ',  extension_loaded  ('openssl') ? 'yes':'no', "<br />";
//	echo 'http wrapper: ', in_array('http', $w) ? 'yes':'no', "<br />";
//	echo 'https wrapper: ', in_array('https', $w) ? 'yes':'no', "<br />";
//	echo 'wrappers: ', var_dump($w);

	
    $url = $uri . '/format/json';
    $url = preg_replace('/([^:])(\/{2,})/', '$1/', $url); // Strip out any double forward slashes from the url
//	error_log("url -> $url");
        
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
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
//    error_log($result);
//    error_log(curl_error($ch));
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
	$url = preg_replace('/([^:])(\/{2,})/', '$1/', $url); // Strip out any double forward slashes from the url
//	error_log("token -> $token");
//	error_log("url -> $url");

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
//        echo "<pre>";
//                        var_dump($result);
//                        echo "</pre>";
	return $result;
}

function curl_get_contents($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

?>