<?php

class Database {

	// Function to the database and tables and fill them with the default data
	function create_database($data) {
		// Connect to the database
		$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],'');

		// Check for errors
		if(mysqli_connect_errno()) {
			$this->error_message = "Couldn't connect to MySQL -> " . mysqli_connect_error();
			return false;
		}
		
		// Drop the database first
		if ($mysqli->query("DROP DATABASE ".$data['database']) === TRUE ) {
//			error_log("dropped db okay");
		}
		else {
//			error_log("error with drop");
//			error_log(print_r($mysqli->error,1));
//			$this->error_message = "Couldn't drop database " . $data['database'] . " (does this user have sufficient priviledges? -> " . $mysqli->error;
//			return false;
		}
		// Create the prepared statement
		if ($mysqli->query("CREATE DATABASE IF NOT EXISTS ".$data['database']) === TRUE ) {
			// Close the connection
			$mysqli->close();
			return true;
		}
		else {
			$this->error_message = "Couldn't create database -> " . $mysqli->error;
			return false;
		}

	}

	function insert_settings($data) {
		$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],$data['database']);
//		error_log("key -> " . $data['bioportalkey']);
		// Check for errors
		if(mysqli_connect_errno()) {
			$this->error_message = "Couldn't connect to MySQL -> " . mysqli_connect_error();
			return false;
		}

		$update_flag = true;
		
		
		// Update settings with bioportal api key
		$name = "bioportalkey";
		$info = "In order to use phenotype ontologies you must sign up for a BioPortal account and supply your API key here. If this is left blank you only be able to use free text for phenotypes. Sign up at http://bioportal.bioontology.org/accounts/new";
		$validation = "xss_clean";
		if ( ! empty($_POST['is_valid'])) { // bioportal api key has been validated
			$bioportalkey = $mysqli->real_escape_string($data['bioportalkey']);
		}
		else {
			$core = new Core();
			$bioportalkey = $mysqli->real_escape_string($core->getBioPortalAPIKey());
		}
		$query = 'UPDATE settings SET name = ?, value = ?, info = ?, validation_rules = ? WHERE name = ?'; // Add the user to the admin group
		if ($stmt = $mysqli->prepare($query)) {
			$stmt->bind_param("sssss", $name, $bioportalkey, $info, $validation, $name);
			if (!$stmt->execute()) {
				error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
				$stmt->close();
				$this->error_message = "Couldn't insert settings -> " . $mysqli->error;
				$update_flag = false;
			}
			else {
//				error_log("inserted bioportal key value successfully");
//				$insert_id = $mysqli->insert_id;
//				error_log("settng id -> " . $insert_id);
				$stmt->close();
			}
		}
		else {
			error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			$this->error_message = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			$update_flag = false;
		}

		// Update settings with prefix
//		error_log("updating prefix -> " . $_POST['prefix']);
		$name = "cvid_prefix";
		$info = "Prefix that is prepended to Cafe Variome IDs";
		$prefix = $mysqli->real_escape_string($data['prefix']);
		$query = 'UPDATE settings SET name = ?, value = ?, info = ?, validation_rules = ? WHERE name = ?'; // Add the user to the admin group
		if ($stmt = $mysqli->prepare($query)) {
			$stmt->bind_param("sssss", $name, $prefix, $info, $validation, $name);
			if (!$stmt->execute()) {
				error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
				$stmt->close();
				$this->error_message = "Couldn't insert settings -> " . $mysqli->error;
				$update_flag = false;
			}
			else {
//				error_log("updated prefix value successfully");
//				$insert_id = $mysqli->insert_id;
//				error_log("settng id -> " . $insert_id);
				$stmt->close();
			}
		}
		else {
			error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			$this->error_message = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			$update_flag = false;
		}
		
		// Update settings with site_title
		$name = "site_title";
		$info = "Main title for the site that will be shown in metadata.";
		$prefix = $mysqli->real_escape_string($data['sitetitle']);
		$query = 'UPDATE settings SET name = ?, value = ?, info = ?, validation_rules = ? WHERE name = ?'; // Add the user to the admin group
		if ($stmt = $mysqli->prepare($query)) {
			$stmt->bind_param("sssss", $name, $prefix, $info, $validation, $name);
			if (!$stmt->execute()) {
				error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
				$stmt->close();
				$this->error_message = "Couldn't insert settings -> " . $mysqli->error;
				$update_flag = false;
			}
			else {
//				error_log("updated prefix value successfully");
//				$insert_id = $mysqli->insert_id;
//				error_log("settng id -> " . $insert_id);
				$stmt->close();
			}
		}
		else {
			error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			$this->error_message = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			$update_flag = false;
		}
		
		// Update settings with site_description
		$name = "site_description";
		$info = "Brief description of the site that will be shown in metadata.";
		$prefix = $mysqli->real_escape_string($data['sitedescription']);
		$query = 'UPDATE settings SET name = ?, value = ?, info = ?, validation_rules = ? WHERE name = ?'; // Add the user to the admin group
		if ($stmt = $mysqli->prepare($query)) {
			$stmt->bind_param("sssss", $name, $prefix, $info, $validation, $name);
			if (!$stmt->execute()) {
				error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
				$stmt->close();
				$this->error_message = "Couldn't insert settings -> " . $mysqli->error;
				$update_flag = false;
			}
			else {
//				error_log("updated prefix value successfully");
//				$insert_id = $mysqli->insert_id;
//				error_log("settng id -> " . $insert_id);
				$stmt->close();
			}
		}
		else {
			error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			$this->error_message = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			$update_flag = false;
		}
		
		// Update settings with site_author
		$name = "site_author";
		$info = "Name of site author that will be shown in metadata.";
		$prefix = $mysqli->real_escape_string($data['siteauthor']);
		$query = 'UPDATE settings SET name = ?, value = ?, info = ?, validation_rules = ? WHERE name = ?'; // Add the user to the admin group
		if ($stmt = $mysqli->prepare($query)) {
			$stmt->bind_param("sssss", $name, $prefix, $info, $validation, $name);
			if (!$stmt->execute()) {
				error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
				$stmt->close();
				$this->error_message = "Couldn't insert settings -> " . $mysqli->error;
				$update_flag = false;
			}
			else {
//				error_log("updated prefix value successfully");
//				$insert_id = $mysqli->insert_id;
//				error_log("settng id -> " . $insert_id);
				$stmt->close();
			}
		}
		else {
			error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			$this->error_message = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			$update_flag = false;
		}
		
		// Update settings with site_author
		$name = "site_keywords";
		$info = "Site keywords metadata to help with search engine optimisation and traffic.";
		$prefix = $mysqli->real_escape_string($data['sitekeywords']);
		$query = 'UPDATE settings SET name = ?, value = ?, info = ?, validation_rules = ? WHERE name = ?'; // Add the user to the admin group
		if ($stmt = $mysqli->prepare($query)) {
			$stmt->bind_param("sssss", $name, $prefix, $info, $validation, $name);
			if (!$stmt->execute()) {
				error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
				$stmt->close();
				$this->error_message = "Couldn't insert settings -> " . $mysqli->error;
				$update_flag = false;
			}
			else {
//				error_log("updated prefix value successfully");
//				$insert_id = $mysqli->insert_id;
//				error_log("settng id -> " . $insert_id);
				$stmt->close();
			}
		}
		else {
			error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			$this->error_message = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			$update_flag = false;
		}
		
		return $update_flag;
		
	}

	function insert_installation_key($data, $installation_key) {
//		error_log("installation_key -> " . $installation_key);
		$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],$data['database']);
		// Check for errors
		if(mysqli_connect_errno()) {
			$this->error_message = "Couldn't connect to MySQL -> " . mysqli_connect_error();
			return false;
		}
		
		$update_flag = true;
		
		// Update settings with installation_key
//		error_log("updating installation_key -> " . $installation_key);
		$validation = "xss_clean";
		$name = "installation_key";
		$info = "Unique key for this installation (WARNING: do not change this value unless you know what you are doing)";
		$installation_key = $mysqli->real_escape_string($installation_key);
		$query = 'UPDATE settings SET name = ?, value = ?, info = ?, validation_rules = ? WHERE name = ?'; // Add the user to the admin group
		if ($stmt = $mysqli->prepare($query)) {
			$stmt->bind_param("sssss", $name, $installation_key, $info, $validation, $name);
			if (!$stmt->execute()) {
				error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
				$stmt->close();
				$this->error_message = "Couldn't insert settings -> " . $mysqli->error;
				$update_flag = false;
			}
			else {
//				error_log("updated installation_key value successfully");
//				$insert_id = $mysqli->insert_id;
//				error_log("settng id -> " . $insert_id);
				$stmt->close();
			}
		}
		else {
			error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			$this->error_message = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			$update_flag = false;
		}
		
		return $update_flag;
		
	}
	
	function insert_ontology_sources($data) {
		$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],$data['database']);
//		error_log("key -> " . $data['bioportalkey']);
		// Check for errors
		if(mysqli_connect_errno()) {
			$this->error_message = "Couldn't connect to MySQL -> " . mysqli_connect_error();
			return false;
		}
		
		$sources = $_POST['sources'];
//		error_log("--> " . print_r($sources, 1));
		foreach ( $sources as $source ) {
//			error_log("S --> " . print_r($source, 1));
			$insert_flag = true;
			$rawvalue = $source[0];
                        list($source_name, $abbreviation) = explode("|", $rawvalue);
                        //$source_name = $source[1];
                                                
//			error_log("source -> $abbreviation $source_name");
			$query = 'INSERT INTO ontology_list (name, abbreviation) VALUES (?, ?)'; // Add each selected ontology to the phenotype_ontologies table
			if ($stmt = $mysqli->prepare($query)) {
				$stmt->bind_param("ss", $source_name, $abbreviation);
				if (!$stmt->execute()) {
					error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
					$stmt->close();
					$this->error_message = "Couldn't insert phenotype ontology sources -> " . $mysqli->error;
					$insert_flag = false;
				} else {
//					error_log("inserted phenotype_source successfully");
					$insert_id = $mysqli->insert_id;
//					error_log("phenotype_source id -> " . $insert_id);
					$stmt->close();
				}
			}
			else {
				error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
				$this->error_message = "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
				$insert_flag = false;
			}
		}
		return $insert_flag;
	}
	
	function create_admin_user($data, $installation_key) {
		// Connect to the database
		$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],$data['database']);
		// Check for errors
		if(mysqli_connect_errno()) {
			$this->error_message = "Couldn't connect to MySQL -> " . mysqli_connect_error();
			return false;
		}
		$adminusername = $mysqli->real_escape_string($data['adminusername']);
		$adminemail = $mysqli->real_escape_string($data['adminemail']);
		// Convert password to md5
		$adminpassword = $mysqli->real_escape_string(md5($data['adminpassword']));
		$active = "1";
		$first_name = empty($data['adminfirstname']) ? $data['adminfirstname'] : 'admin';
		error_log("first: $first_name -> " . $data['adminfirstname']);
//		$first_name = "admin";
		$last_name = empty($data['adminlastname']) ? $data['adminlastname'] : 'admin';
//		$last_name = "admin";
		error_log("last: $last_name -> " . $data['adminlastname']);
		$affiliation = "admin";
		$is_admin = "1";
		
		// Create the admin user in the central auth server
		$create_admin_auth_result = $this->create_admin_user_at_cafevariome_auth_server($adminusername, $adminpassword, $adminemail, $active, $first_name, $last_name, $affiliation, $is_admin, $installation_key);
		
//		error_log("create_admin_auth_result -> " . print_r($create_admin_auth_result, 1));
		if ( array_key_exists('error', $create_admin_auth_result) ) {
			$this->error_message = "There was a problem creating the admin user at the Cafe Variome authentication server. The supplied email address must be unique across all users in the Cafe Variome universe, please re-try installation with another email address. Contact admin@cafevariome.org if the problem persists.";
			return false;
		}
		
		$query = 'INSERT INTO users (username, password, email, active, first_name, last_name, company, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'; 
		if($stmt = $mysqli->prepare($query)) {
			$stmt->bind_param("ssssssss",$adminusername, $adminpassword, $adminemail, $active, $first_name, $last_name, $affiliation, $is_admin);
			if (!$stmt->execute()) {
				error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
				$stmt->close();
				$this->error_message = "Couldn't create admin user -> " . $stmt->error;
				return false;
			}
			else { // Inserted the user okay, now need to add admin user to the admin group
//				error_log("inserted user successfully");
				$insert_id = $mysqli->insert_id;
				$admin_group_id = "1";
				
				if ( $_POST['include_data'] == "sample" ) { // If sample data is included then add the admin user to the leicester group
					if ($result = $mysqli->query("INSERT INTO users_groups (user_id, group_id) VALUES ('1', '6')")) {
//						error_log("leicester okay");
						
					}
					else {
						$this->error_message = "Couldn't insert admin user into group for sample data -> " . $mysqli->error;
						error_log("error with leicester");
						return false;
					}
				}
				
//				error_log("user insert id -> " . $insert_id);
				$query = 'INSERT INTO users_groups (user_id, group_id) VALUES (?, ?)'; // Add the user to the admin group
				if ($stmt = $mysqli->prepare($query)) {
					$stmt->bind_param("ss", $insert_id, $admin_group_id);
					if (!$stmt->execute()) {
						error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
						$stmt->close();
						return false;
					} else {
//						error_log("inserted user successfully");
						$insert_id = $mysqli->insert_id;
//						error_log("group insert id -> " . $insert_id);
						$stmt->close();
						return true;
					}
					$stmt->close();
					return true;
				}
				else {
					error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
					return false;
				}
								
			}
		}
		else {
			error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
			$this->error_message = "Couldn't create admin user -> " . $mysqli->error;
			return false;
		}
		
	}
	
	function create_admin_user_at_cafevariome_auth_server($adminusername, $adminpassword, $adminemail, $active, $first_name, $last_name, $affiliation, $is_admin, $installation_key) {
		// Create the admin user in the Cafe Variome auth server
//		$adminusername, $adminpassword, $adminemail, $active, $first_name, $last_name, $affiliation, $is_admin
//		$api_url = "http://143.210.153.155/cafevariome_server/auth_accounts/create_user";
		$api_url = "https://auth.cafevariome.org/auth_accounts/create_user";
		$data = array(	'username' => $adminusername,
						'email' => $adminemail,
						'password' => $adminpassword,
						'first_name' => $first_name,
						'last_name'  => $last_name,
						'company'    => $affiliation,
						'isadmin' => $is_admin,
						'installation_key' => $installation_key,
						'active' => "1"
			);

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
		error_log("result -> $result");
//		echo json_encode($result);
		return json_decode($result, 1);
	}
	
	// Function to create the tables and populate them with the default data
	function create_tables($data) {
		ini_set('memory_limit','1024M');
		ini_set("upload_max_filesize", "500M");

//		error_log("include_data -> " . $_POST['include_data']);
		if ( $_POST['include_data'] == "sample" ) {
			$filename = "assets/sql/install_sample.sql";
//			$filename = "assets/general_sample_data.sql";
		}
		else if ( $_POST['include_data'] == "sample_dmudb" ) {
			$filename = "assets/sql/install_dmudb.sql";
//			$filename = "assets/dmudb_sample_data.sql";
		}
		else if ( $_POST['include_data'] == "none" ) {
			$filename = "assets/sql/install.sql";
			$filename = "assets/sql/cafevariome_client_install.sql";
		}
//		error_log("filename -> $filename");
		// Connect to MySQL server - having to use standard mysql instead of mysqli here because of the potential size of the sql query - could get very big so need to process it line by line
		$link = mysql_connect($data['hostname'],$data['username'],$data['password']);
		if (!$link) {
//			die('Could not connect: ' . mysql_error());
			$this->error_message = "Couldn't connect to MySQL -> " . mysql_error();
			return false;
		}
		// Select database
		$db_selected = mysql_select_db($data['database']);
		if (!$db_selected) {
			$this->error_message = "Couldn't select database (" . $data['database'] . ") -> " . mysql_error();
			return false;
		}
		// Temporary variable, used to store current query
		$templine = '';
		// Read in entire file
		$lines = file($filename);
		// Get total number of lines and then use this to update a progress bar
		// Loop through each line
		$error_flag = 0;
		foreach ($lines as $line) {
			// Skip it if it's a comment
			if (substr($line, 0, 2) == '--' || $line == '') 
				continue;

			// Add this line to the current segment
			$templine .= $line;
			// If it has a semicolon at the end, it's the end of the query
			if (substr(trim($line), -1, 1) == ';') {
				// Perform the query
				mysql_query($templine, $link) or $error_flag = 1;
				// Reset temp variable to empty
				$templine = '';
			}
		}
		if ( $error_flag ) {
			$this->error_message = "Couldn't execute the MySQL data import correctly.";
			error_log("error flag -> " . $error_flag);
			return false;
		}
		else {
			return true;
		}
	}

	function check_for_strict_mode($data) {
		$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],$data['database']);
		$query = "SELECT @@sql_mode";
		$result = $mysqli->query($query);     
		if (!$result) {
			$this->error_message = "Couldn't execute STRICT check -> " . $mysqli->error;
			return false;
		}
		$row = $result->fetch_assoc();
//		error_log("row -> " . print_r($row, 1));
//		error_log("mode -> " . $row['@@sql_mode']);
		if ( preg_match("/STRICT/", $row['@@sql_mode'])) {
			$this->error_message = "Strict mode is enabled in MySQL, please turn it off in your my.cnf file (see <a href='http://www.mysqlfaqs.net/mysql-faqs/Client-Server-Commands/What-is-sql-mode-in-MySQL-and-how-can-we-set-it' target='_blank'>here</a>)";
			return false;
		}
		else {
			return true;
		}
	}
	
	function getErrorMessage() {
		return $this->error_message;
	}
	
}

