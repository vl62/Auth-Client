<?php

class Core {

	// Function to validate the post data
	function validate_post($data)
	{
//		error_log("----> " . $data['hostname'] . $data['username'] . $data['database']);
		/* Validating the hostname, the database name and the username. The password is optional. */
		return !empty($data['hostname']) && !empty($data['username']) && !empty($data['database']);
	}

	// Function to show an error
	function show_message($type,$message) {
		return $message;
	}

	// Function to write the config file
	function write_config($data) {

		// Config path
		$template_path 	= 'config/database.php';
		$output_path 	= '../application/config/database.php';

		// Open the file
		$database_file = file_get_contents($template_path);

		$new = str_replace("%HOSTNAME%",$data['hostname'],$database_file);
		$new = str_replace("%USERNAME%",$data['username'],$new);
		$new = str_replace("%PASSWORD%",$data['password'],$new);
		$new = str_replace("%DATABASE%",$data['database'],$new);
//		$new = str_replace("%STATSDATABASE%",$data['statsdatabase'],$new);

		// Write the new database.php file
		$handle = fopen($output_path,'w+');

		// Chmod the file, in case the user forgot
		@chmod($output_path,0777);

		// Verify file permissions
		if(is_writable($output_path)) {

			// Write the file
			if(fwrite($handle,$new)) {
				return true;
			}
			else {
				$this->error_message = "Couldn't write to database config file. Try to chmod application/config/database.php file to 777 (or permissions that allow the web server to write to this file)";
				return false;
			}

		} else {
			$this->error_message = "Database config file is not writable. Try to chmod application/config/database.php file to 777 (or permissions that allow the web server to write to this file)";
			return false;
		}
	}

	function getErrorMessage() {
		return $this->error_message;
	}
	
	// Function to write the config file
	function write_username_password($data) {

		// Config path
		$sql_template_path 	= 'assets/install.sql';

		// Verify file permissions
		if(is_writable($sql_template_path)) {
			// Open the file
			$sql_file = file_get_contents($sql_template_path);

			$new  = str_replace("%HOSTNAME%",$data['hostname'],$sql_file);

			// Write the new database.php file
			$handle = fopen($output_path,'w+');

			// Chmod the file, in case the user forgot
			@chmod($output_path,0777);
			// Write the file
			if(fwrite($handle,$new)) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}

	}
	
	function initial_checks() {
		$check_array = array();
		
		// Check to see if AllowOverride All is set in apache config and that the variable HTACCESS in .htaccess is readable
		// Taken from http://stackoverflow.com/questions/10345812/php-scriptfunction-to-check-if-htaccess-is-allowed-on-server
		if ( !isset($_SERVER['HTACCESS']) ) {
			// No .htaccess support
			$error = '<div class="alert alert-error">.htaccess cannot be read by the webserver. Please make sure that "AllowOverride All" is set in your apache config</div><hr>';
			$check_array['htaccess'] = $error;
		}

		if ( ! in_array('mod_rewrite', apache_get_modules()) ) {
			$error = '<div class="alert alert-error">mod_rewrite is not enabled in apache.<br /><br /><strong>Example</strong>:<br /><br /><code>sudo a2enmod rewrite</code></div><hr>';
			$check_array['mod_rewrite'] = $error;
		}
		
		$db_config_path = '../application/config/database.php';
		if (! is_writable($db_config_path)) {
			$error = '<div class="alert alert-error">The database config file is not writable by the webserver.<br /><br /><strong>Example</strong>:<br /><br /><code>chmod 775 application/config/database.php</code></div><hr>';
			$check_array['db_config_path'] = $error;
		}
				
//		$cafevariome_config_path = '../application/config/cafevariome.php';
//		if ( ! is_writable($cafevariome_config_path)) {
//			$error = '<div class="alert alert-error">The cafevariome config file is not writable by the webserver.<br /><br /><strong>Example</strong>:<br /><br /><code>chmod 775 application/config/cafevariome.php</code></div><hr>';
//			$check_array['cafevariome_config_path'] = $error;
//		}
		
		$preferences_config_path = '../application/config/preferences.php';
		if ( ! is_writable('../application/config/preferences.php')) {
			$error = '<div class="alert alert-error">The preferences config file is not writable by the webserver.<br /><br /><strong>Example</strong>:<br /><br /><code>chmod 775 application/config/preferences.php</code></div><hr>';
			$check_array['preferences_config_path'] = $error;
		}

		$upload_path = '../upload/';
		if ( ! is_writable($upload_path)) {
			$error = '<div class="alert alert-error">The upload directory is not writable by the webserver.<br /><br /><strong>Example</strong>:<br /><br /><code>chmod -R 775 upload</code></div><hr>';
			$check_array['upload_path'] = $error;
		}
		
		$cache_path = '../Cache/';
		if ( ! is_writable($cache_path)) {
			$error = '<div class="alert alert-error">The cache directory is not writable by the webserver.<br /><br /><strong>Example</strong>:<br /><br /><code>chmod -R 775 Cache</code></div><hr>';
			$check_array['cache_path'] = $error;
		}
		
		if ( ! extension_loaded("mysql")) {
			$error = '<div class="alert alert-error">The PHP MySQL extension does not appear to be loaded. See <a href="http://stackoverflow.com/questions/8225198/phpmyadmin-the-mysql-extension-is-missing" target="_blank" >here</a> for an example of how to enable it.</div><hr>';
			$check_array['mysql_loaded'] = $error;
		
		}
		
		if ( ! extension_loaded('soap')) {
			$error = '<div class="alert alert-error">Soap is not enabled in PHP. See <a href="http://stackoverflow.com/questions/11391442/fatal-error-class-soapclient-not-found" target="_blank" >here</a> for an example of how to enable it.</div><hr>';
			$check_array['soap_loaded'] = $error;
		}
		
		if  (! extension_loaded('curl') ) {
			$error = '<div class="alert alert-error">Curl is not enabled in PHP. See <a href="http://stackoverflow.com/questions/8014482/php-curl-enable-linux" target="_blank" >here</a> for an example of how to enable it.</div><hr>';
			$check_array['curl_loaded'] = $error;
		}
		
		if (version_compare(PHP_VERSION, '5.0.0', '<')) {
//		if (version_compare(PHP_VERSION, '5.3.13') <= 0) {
			$error = '<div class="alert alert-error">PHP version is too low (' . PHP_VERSION . '), you must have PHP 5 installed.</div><hr>';
			$check_array['php_version'] = $error;
		}

		return $check_array;
	}
	
	function getBioPortalAPIKey() {
		$api_url = "http://www.cafevariome.org/api/central/getkey/format/json";
		$data = json_decode(file_get_contents($api_url));
		$bioportalkey = $data->key;
		return $bioportalkey;
	}
	
//	function getBioPortalOntologyList($key) {
//		$api_url = "http://data.bioontology.org/ontologies?apikey=$key";
//		$data = @file_get_contents($api_url); // Supress errors
////		error_log(print_r($http_response_header,1));
//		return $data;
//	}
        
	function get_ontology_list($apikey) {
		$url = "http://data.bioontology.org/ontologies?apikey=$apikey";
		$content = file_get_contents($url);
		$data = $this->_cache_ontology_list($content);         
		return $content;
	}
        
	function _cache_ontology_list($content) {
		$params = array('dir' => '../Cache');
		$cache = new JG_Cache($params);
		$data = json_decode($content);
		$numberofontologies = sizeof($data);
		for ($i = 0; $i < $numberofontologies; $i++) {
			$ontname = $data[$i]->name;
			$ontacronym = $data[$i]->acronym;
			$list[$ontacronym] = $ontname;
		}
		asort($list);
		$cache->set('fullontologylist', $list);
		return $list;
	}

		
}