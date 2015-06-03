<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Helper for shared Cafe Variome functions
// TODO: helper is getting a bit big/disorganised need refactor into a Cafe Variome library

function updateStats($data, $api_name) {
	$url = base_url('api/stats/' . $api_name);
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = @file_get_contents($url, false, $context); // Suppress errors for this as some server configs won't allow this and will return a 500 error
}

// No longer used - performance issues with curl_exec on LAMP so using above alternative to post data
function updateStatsCurl($data, $api_name) {
	$url = base_url('api/stats/' . $api_name);
	//open connection
	$ch = curl_init();
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
	//execute post
	$result = curl_exec($ch);
	//close connection
	curl_close($ch);
}

function updateNode($data, $node_uri) {
	$url = $node_uri . '/federated/node_create_list/';
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
}

function createNetwork($data, $central_uri) {
	$url = $central_uri . '/api/auth/create_network/format/json';
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
	
}

function checkNetworkExists($data, $central_uri) {
	$url = $central_uri . '/api/auth/check_network_exists/format/json';
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
	
}

function joinNetwork($data, $central_uri) {
	$url = $central_uri . '/api/auth/join_network/format/json';
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
	
}

function getNetworks($central_uri) {
	$url = $central_uri . '/api/auth/get_networks/format/json';
	$opts = array('http' =>
		array(
			'method'  => 'GET',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
}

function getNetworkRequestsForInstallation($data, $central_uri) {
	$url = $central_uri . '/api/auth/get_network_requests_for_installation/format/json';
	
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
}

function getNetworkRequestsForNetworksThisInstallationBelongsTo($data, $central_uri) {
	$url = $central_uri . '/api/auth/get_network_requests_for_networks_this_installation_belongs_to/format/json';
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
}



function isInstallationPartOfNetwork($data, $central_uri) {
	$url = $central_uri . '/api/auth/is_installation_part_of_network/format/json';
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
}

function viewNetworkRequestsForInstallation($data, $central_uri) {
	$url = $central_uri . '/api/auth/view_network_requests_for_installation/format/json';
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
}

function leaveNetwork($data, $central_uri) {
	$url = $central_uri . '/api/auth/leave_network/format/json';
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context);
	return $result;
}

function getRealIpAddr() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) { //check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { //to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function getExternalIP() {
	$external_ip = file_get_contents('http://ipecho.net/plain');
	return $external_ip;
}

function cafevariomeEmail($from_email, $from_name, $to_email, $subject, $message, $cc = NULL, $bcc = NULL) {
	$ci = get_instance();
	$ci->load->library('email');
	$ci->email->from($from_email, $from_name);
	$ci->email->to($to_email);
//	$ci->email->cc('another@another-example.com');
//	$ci->email->bcc('them@their-example.com');
	$ci->email->subject($subject);
	$ci->email->message($message);
	$ci->email->send();
}

function setCurrentURL() {
	$ci =& get_instance();
	$ci->load->helper('url');
	$ci->load->library('session');
//	$current = $ci->uri->uri_string();
//	$current = $ci->config->site_url().$ci->uri->uri_string();
	$current = current_url();
	$ci->session->set_userdata('return_to', $current);
//	error_log("c -> " . $ci->session->userdata('return_to'));
}

function getBioPortalAPIKey() {
	$api_url = "http://www.cafevariome.org/api/getkey/format/json";
	$data = json_decode(file_get_contents($api_url));
	$bioportalkey = $data->key;
	$CI = get_instance();
	$CI->load->model('general_model');
	$current_bioportalkey = $CI->general_model->getBioPortalAPIKey();
	
	if ( $current_bioportalkey != $bioportalkey ) {
		error_log("not equal");
		$CI->general_model->updateBioPortalAPIKey($bioportalkey);
	}
	else {
		error_log("equal");
	}
}

function isDefined($string) {
	if ( isset($string) && !empty($string)) {
		return TRUE;
	}
	else {
		return FALSE;
	}
}

function isNotDefined($string) {
	if ( ! isset($string) && empty($string)) {
		return TRUE;
	}
	else {
		return FALSE;
	}
}

function federatedAPI($uri, $federated_data) {
	$uri = $uri . "/federated/variantcount/format/json";
//	error_log("uri --> " . $uri);
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'content' => http_build_query($federated_data)
		)
	);
//	error_log("query -> " . print_r($federated_data, 1));
	$context  = stream_context_create($opts);
	$result = file_get_contents($uri, false, $context);
//	error_log("RESULT -> " . print_r($result,1));
	$result = json_decode($result);
	$result = objectToArray($result);
//	error_log("results ");
//	error_log(print_r($result, 1));
	return ($result);
}

function removeEmptyLines($string) {
	return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string);
}

function objectToArray($d) {
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
		/*
		 * Return array converted to object
		 * Using __FUNCTION__ (Magic constant)
		 * for recursive call
		 */
		return array_map(__FUNCTION__, $d);
	} else {
		// Return array
		return $d;
	}
}

function isValidMD5($md5 ='') {
    return preg_match('/^[a-f0-9]{32}$/', $md5);
}

 function XMLToJson($url) {

	$fileContents= file_get_contents($url);
	$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
	$fileContents = trim(str_replace('"', "'", $fileContents));
	$simpleXml = simplexml_load_string($fileContents);
	$json = json_encode($simpleXml);
	return $json;
}

function generateMD5() {
	$mdstring = md5(uniqid(rand(), true));
	return $mdstring;
}	

function prettyPrintJSON( $json ) {
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
}

function json_format($json) {
  if (!is_string($json)) {
    if (phpversion() && phpversion() >= 5.4) {
      return json_encode($json, JSON_PRETTY_PRINT);
    }
    $json = json_encode($json);
  }
  $result      = '';
  $pos         = 0;               // indentation level
  $strLen      = strlen($json);
  $indentStr   = "\t";
  $newLine     = "\n";
  $prevChar    = '';
  $outOfQuotes = true;
  for ($i = 0; $i < $strLen; $i++) {
    // Speedup: copy blocks of input which don't matter re string detection and formatting.
    $copyLen = strcspn($json, $outOfQuotes ? " \t\r\n\",:[{}]" : "\\\"", $i);
    if ($copyLen >= 1) {
      $copyStr = substr($json, $i, $copyLen);
      // Also reset the tracker for escapes: we won't be hitting any right now
      // and the next round is the first time an 'escape' character can be seen again at the input.
      $prevChar = '';
      $result .= $copyStr;
      $i += $copyLen - 1;      // correct for the for(;;) loop
      continue;
    }
    
    // Grab the next character in the string
    $char = substr($json, $i, 1);
    
    // Are we inside a quoted string encountering an escape sequence?
    if (!$outOfQuotes && $prevChar === '\\') {
      // Add the escaped character to the result string and ignore it for the string enter/exit detection:
      $result .= $char;
      $prevChar = '';
      continue;
    }
    // Are we entering/exiting a quoted string?
    if ($char === '"' && $prevChar !== '\\') {
      $outOfQuotes = !$outOfQuotes;
    }
    // If this character is the end of an element,
    // output a new line and indent the next line
    else if ($outOfQuotes && ($char === '}' || $char === ']')) {
      $result .= $newLine;
      $pos--;
      for ($j = 0; $j < $pos; $j++) {
        $result .= $indentStr;
      }
    }
    // eat all non-essential whitespace in the input as we do our own here and it would only mess up our process
    else if ($outOfQuotes && false !== strpos(" \t\r\n", $char)) {
      continue;
    }
    // Add the character to the result string
    $result .= $char;
    // always add a space after a field colon:
    if ($outOfQuotes && $char === ':') {
      $result .= ' ';
    }
    // If the last character was the beginning of an element,
    // output a new line and indent the next line
    else if ($outOfQuotes && ($char === ',' || $char === '{' || $char === '[')) {
      $result .= $newLine;
      if ($char === '{' || $char === '[') {
        $pos++;
      }
      for ($j = 0; $j < $pos; $j++) {
        $result .= $indentStr;
      }
    }
    $prevChar = $char;
  }
  return $result;
}

?>