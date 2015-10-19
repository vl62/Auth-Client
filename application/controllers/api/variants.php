<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class Variants extends REST_Controller {
//class Variants extends MY_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
	}
	
	function submit_post() {
		$source_destination = $this->get('source') ?: 'temp';
		$sharing_policy = $this->get('sharing_policy') ?: 'openAccess';
		$overwrite_ids = $this->get('overwrite') ?: 'off';
		$mutalyzer_check = $this->get('mutalyzer') ?: 'off';

		// Remove spaces and convert source name to lowercase
		$source_destination = urldecode($source_destination);
		$source_destination = strtolower($source_destination);
		$source_destination = preg_replace('/\s+/', '_', $source_destination);
//		error_log("POST $source_destination $sharing_policy");

		if (!isset($_SERVER['PHP_AUTH_USER'])) { // Check basic authentication is set in the request headers
			header('WWW-Authenticate: Basic realm="Cafe Variome"');
			header('HTTP/1.0 401 Unauthorized');
			echo 'The correct username/email and password must be supplied';
			exit;
		}
		else {
			$this->load->model('general_model');
			$username = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];
//			error_log("username $username password $password");
			$is_valid = $this->general_model->authenticateUser($username, $password);
			if ( $is_valid ) { // The username/email and password are valid
				if (!$this->ion_auth->is_admin($is_valid['id'])) { // The user is not a member of the admin group
					// TODO: Add option in settings to allow any user to submit and not just admin
					$body = array ('error' => "User is valid but is not an admin");
					echo json_encode($body);
					$response_code = "401";
					$response_text = $this->http_response_code($response_code);
					header("HTTP/1.0 $response_code $response_text");
					exit();
				}
				$this->load->model('sources_model');
				// Check if source exists, if not then create it on the fly
				$does_source_exist = $this->sources_model->checkSourceExists($source_destination);
				if ( ! $does_source_exist ) {
					$source_data = array ( "name" => $source_destination, "owner_name" => '', "email" => '', "uri" => '', "description" => $source_destination, "long_description" => $source_destination, "type" => "mysql", "status" => "online");
					$insert_id = $this->sources_model->insertSource($source_data);
				}
				$content = file_get_contents('php://input');
				$type = $this->detect_content_type($content);
				$variant_count = 0;
				$insert_flag = 1;
//				echo "type -> $type -> content -> $content";
				// TODO: For all content types use Mutalyzer to validate the variant and set flag (also try and get genomic coords
				if ( $type == "xml") {
					$xml = @simplexml_load_string($content);
//					print_r($xml);
					if ( $xml->Mutation ) { // Alamut XML is detected
//						error_log(print_r($xml, 1));
						$return_data = $this->_parseAlamutXML($xml, $source_destination, $sharing_policy, $overwrite_ids, $mutalyzer_check);
//						error_log(print_r($return_data, 1));
						$variant_count = $return_data['variant_count'];
						$insert_flag = $return_data['insert_flag'];
						$update_flag = $return_data['update_flag'];
						$successful_inserts = $return_data['successful_inserts'];
						$successful_updates = $return_data['successful_updates'];
					}
					else if ( $xml->cafe_variome ) { // VarioML is detected
						$return_data = $this->_parseVarioML($xml, $source_destination, $sharing_policy, $overwrite_ids, $mutalyzer_check);
						$variant_count = $return_data['variant_count'];
						$insert_flag = $return_data['insert_flag'];
						$successful_inserts = $return_data['successful_inserts'];
					}
					else { // XML type is not recognised
						$failed_message = array("error" => "XML was provided but unable to detect what type of XML (Alamut and VarioML are accepted)" );
						echo json_encode($failed_message);
						$response_code = "400";
						$response_text = $this->http_response_code($response_code);
						header("HTTP/1.0 $response_code $response_text");
						exit();
					}
				}
				else if ( $type == "json" ) {
//					$json = json_decode($content, true); //decode to associative array
					$json = json_decode($content);
//					print_r($json);
					$return_data = $this->_parseAlamutJSON($json, $source_destination, $sharing_policy, $overwrite_ids, $mutalyzer_check);
					$variant_count = $return_data['variant_count'];
					$insert_flag = $return_data['insert_flag'];
					$update_flag = $return_data['update_flag'];
					$successful_inserts = $return_data['successful_inserts'];
					$successful_updates = $return_data['successful_updates'];
				}
				else if ( $type == "lovd" ) {
					$json = json_decode($content);
//					error_log(print_r($json, 1));
					$return_data = $this->_parseLOVDJSON($json, $source_destination, $sharing_policy, $overwrite_ids, $mutalyzer_check);
//					error_log("return -> " . print_r($return_data, 1));
					$variant_count = $return_data['variant_count'];
					$insert_flag = $return_data['insert_flag'];
					$update_flag = $return_data['update_flag'];
					$successful_inserts = $return_data['successful_inserts'];
					$successful_updates = $return_data['successful_updates'];
				}
				else if ( $type == "tab" ) {
					foreach(preg_split("/((\r?\n)|(\r\n?))/", $content) as $line){
//						error_log("line -> $line");
						$fields = explode("\t", $line);
						foreach ( $fields as $field ) {
							error_log("field -> $field");
						}
					}
				}
				else if ( $type == "csv" ) {
					foreach(preg_split("/((\r?\n)|(\r\n?))/", $content) as $line){
//						error_log("line -> $line");
						$fields = explode(",", $line);
						foreach ( $fields as $field ) {
							error_log("field -> $field");
						}
					}
				}
				else {
					$failed_message = array("error" => "Unable to detect content type" );
					echo json_encode($failed_message);
					$this->output->set_status_header('400');
					exit();
				}
//				error_log("update $update_flag insert $insert_flag");

				$success_message = array("inserted" => $successful_inserts, "updated" => $successful_updates, "total" => $variant_count);
//				error_log("success message -> " . print_r($success_message, 1));
				echo json_encode($success_message);
				$this->output->set_status_header('200');


			}
			else {
				error_log("authentication failed");
				$body = array ('error' => "Username or password are incorrect");
				echo json_encode($body);
//				exit();
			}
		}
		
		
		
//		$data = array('returned source: '. $source_destination);
//		$this->response($data);
	}
	
	function submit_old($source_destination = 'temp', $sharing_policy = 'restrictedAccess', $overwrite_ids = 'overwrite_on', $mutalyzer_check = 'mutalyzer_off') { // Set source to temp as default and sharing policy to restrictedAccess
		// Remove spaces and convert source name to lowercase
		$source_destination = urldecode($source_destination);
		
		$source_destination = strtolower($source_destination);
//		$source_destination = str_replace(' ', '_', $source_destination);
		$source_destination = preg_replace('/\s+/', '_', $source_destination);
//		error_log("POST $source_destination $sharing_policy");

		if (!isset($_SERVER['PHP_AUTH_USER'])) { // Check basic authentication is set in the request headers
			header('WWW-Authenticate: Basic realm="Cafe Variome"');
			header('HTTP/1.0 401 Unauthorized');
			echo 'The correct username/email and password must be supplied';
			exit;
		}
		else {
			$this->load->model('general_model');
			$username = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];
//			error_log("username $username password $password");
			$is_valid = $this->general_model->authenticateUser($username, $password);
			if ( $is_valid ) { // The username/email and password are valid
				if (!$this->ion_auth->is_admin($is_valid['id'])) { // The user is not a member of the admin group
					// TODO: Add option in settings to allow any user to submit and not just admin
					$body = array ('error' => "User is valid but is not an admin");
					echo json_encode($body);
					$this->output->set_status_header('401');
					exit();
				}
				$this->load->model('sources_model');
				// Check if source exists, if not then create it
				$does_source_exist = $this->sources_model->checkSourceExists($source_destination);
				if ( ! $does_source_exist ) {
					$source_data = array ( "name" => $source_destination, "owner_name" => '', "email" => '', "uri" => '', "description" => $source_destination, "long_description" => $source_destination, "type" => "mysql", "status" => "online");
					$insert_id = $this->sources_model->insertSource($source_data);
				}
				$content = file_get_contents('php://input');
				$type = $this->detect_content_type($content);
				$variant_count = 0;
				$insert_flag = 1;
//				echo "type -> $type -> content -> $content";
				// TODO: For all content types use Mutalyzer to validate the variant and set flag (also try and get genomic coords
				if ( $type == "xml") {
					$xml = @simplexml_load_string($content);
//					print_r($xml);
					if ( $xml->Mutation ) { // Alamut XML is detected
						$return_data = $this->_parseAlamutXML($xml, $source_destination, $sharing_policy, $overwrite_ids, $mutalyzer_check);
						$variant_count = $return_data['variant_count'];
						$insert_flag = $return_data['insert_flag'];
						$update_flag = $return_data['update_flag'];
						$fail_flag = $return_data['fail_flag'];
						$successful_inserts = $return_data['successful_inserts'];
						$successful_updates = $return_data['successful_updates'];
						$fails = $return_data['fails'];
						$details = $return_data['details'];
					}
					else if ( $xml->cafe_variome ) { // VarioML is detected
						$return_data = $this->_parseVarioML($xml, $source_destination, $sharing_policy, $overwrite_ids, $mutalyzer_check);
						$variant_count = $return_data['variant_count'];
						$insert_flag = $return_data['insert_flag'];
						$successful_inserts = $return_data['successful_inserts'];
					}
					else { // XML type is not recognised
						$failed_message = array("error" => "XML was provided but unable to detect what type of XML (Alamut and VarioML are accepted)" );
						echo json_encode($failed_message);
						$this->output->set_status_header('400');
						exit();
					}
				}
				else if ( $type == "json" ) {
//					$json = json_decode($content, true); //decode to associative array
					$json = json_decode($content);
//					print_r($json);
					// TODO: Check for JSON types - e.g. Alamut
					$return_data = $this->_parseAlamutJSON($json, $source_destination, $sharing_policy, $overwrite_ids, $mutalyzer_check);
					$variant_count = $return_data['variant_count'];
					$insert_flag = $return_data['insert_flag'];
					$update_flag = $return_data['update_flag'];
					$successful_inserts = $return_data['successful_inserts'];
					$successful_updates = $return_data['successful_updates'];
				}
				else if ( $type == "tab" ) {
					foreach(preg_split("/((\r?\n)|(\r\n?))/", $content) as $line){
//						error_log("line -> $line");
						$fields = explode("\t", $line);
						foreach ( $fields as $field ) {
							error_log("field -> $field");
						}
					}
				}
				else if ( $type == "csv" ) {
					foreach(preg_split("/((\r?\n)|(\r\n?))/", $content) as $line){
//						error_log("line -> $line");
						$fields = explode(",", $line);
						foreach ( $fields as $field ) {
							error_log("field -> $field");
						}
					}
				}
				else {
					$failed_message = array("error" => "Unable to detect content type" );
					echo json_encode($failed_message);
					$this->output->set_status_header('400');
					exit();
				}
//				error_log("update $update_flag insert $insert_flag");

				if ( empty($details)) {
					$success_message = array("inserted" => $successful_inserts, "updated" => $successful_updates, 'failed' => $fails, "total" => $variant_count);
				}
				else {
					$success_message = array("inserted" => $successful_inserts, "updated" => $successful_updates, 'failed' => $fails, "total" => $variant_count, "details" => $details);
				}
				
				echo json_encode($success_message);
				$this->output->set_status_header('200');
	
			}
			else {
				error_log("authentication failed");
				$body = array ('error' => "Username or password are incorrect");
				echo json_encode($body);
				$response_code = "401";
				$response_text = $this->http_response_code($response_code);
				header("HTTP/1.0 $response_code $response_text");

//				exit();
			}
		}
	}
	
	function delete_post() {
		if (!isset($_SERVER['PHP_AUTH_USER'])) { // Check basic authentication is set in the request headers
			header('WWW-Authenticate: Basic realm="Cafe Variome"');
			header('HTTP/1.0 401 Unauthorized');
			echo 'The correct username/email and password must be supplied';
			exit;
		}
		else {
			$this->load->model('general_model');
			$username = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];
//			error_log("username $username password $password");
			$is_valid = $this->general_model->authenticateUser($username, $password);
			if ( $is_valid ) { // The username/email and password are valid
				if (!$this->ion_auth->is_admin($is_valid['id'])) { // The user is not a member of the admin group
					// TODO: Add option in settings to allow any user to submit and not just admin
					$body = array ('error' => "User is valid but is not an admin");
					echo json_encode($body);
					$response_code = "401";
					$response_text = $this->http_response_code($response_code);
					header("HTTP/1.0 $response_code $response_text");
					exit();
				}
				
				$id = $this->get('id'); // Get ID from URL parameters (it's possible to just specify a single ID like this instead of a list of IDs in the body)
				
				$this->load->model('sources_model');
				$content = file_get_contents('php://input');
				$type = $this->detect_content_type($content);
				$variant_count = 0;
				$delete_count = 0;
//				error_log("TYPE -> $type");

				if ( $type == "json" ) {
					$json = json_decode($content);
					foreach ( $json as $variant_id ) {
						$is_deleted = $this->sources_model->deleteVariantByVariantID($variant_id);
						$variant_count++;
						error_log("variant_id -> $variant_id");
						if ( $is_deleted ) {
							$delete_count++;
						}
					}
					
				}
				else if ( $type == "xml" ) {
					
				}
				else if ( $type == "tab" ) {
					foreach(preg_split("/((\r?\n)|(\r\n?))/", $content) as $line){
//						error_log("line -> $line");
						$fields = explode("\t", $line);
						foreach ( $fields as $field ) {
							error_log("field -> $field");
						}
					}
				}
				else if ( $type == "csv" ) {
					foreach(preg_split("/((\r?\n)|(\r\n?))/", $content) as $line){
//						error_log("line -> $line");
						$fields = explode(",", $line);
						foreach ( $fields as $field ) {
							error_log("field -> $field");
						}
					}
				}
				elseif ( ! empty($id) ) { // Single ID specified in the URL e.g. id/{id here}
					$is_deleted = $this->sources_model->deleteVariantByVariantID($id);
					$variant_count++;
					error_log("variant_id -> $id");
					if ( $is_deleted ) {
						$delete_count++;
					}					
				}
				else {
					$failed_message = array("error" => "Unable to detect content type" );
					echo json_encode($failed_message);
					$response_code = "400";
					$response_text = $this->http_response_code($response_code);
					header("HTTP/1.0 $response_code $response_text");
					exit();
				}
				
				$success_message = array("deleted" => $delete_count, "total" => $variant_count);
				echo json_encode($success_message);
				$response_code = "200";
				$response_text = $this->http_response_code($response_code);
				header("HTTP/1.0 $response_code $response_text");	

			}
			else {
//				error_log("authentication failed");
				$body = array ('error' => "Username or password are incorrect");
				echo json_encode($body);
				$response_code = "401";
				$response_text = $this->http_response_code($response_code);
				header("HTTP/1.0 $response_code $response_text");

//				exit();
			}
		}
	}
	
	function _parseAlamutJSON($json, $source_destination, $sharing_policy, $overwrite_ids, $mutalyzer_check) {
		$variant_count = 0;
		$insert_flag = 0;
		$update_flag = 0;
		$successful_inserts = 0;
		$successful_updates = 0;
		// Get source information
		foreach ($json->sources as $source) {
//			print_r($source);
			$source_id = $source->id;
			$source_name = $source->name;

			if ( isset($source->urls)) {
				$source_urls_array = array();
				foreach ($source->urls as $source_url) {
					$source_urls_array[] = $source_url;
				}
				$source_urls = implode('|', $source_urls_array);
			}
			else {
				$source_urls = $source->uri;
			}
			
//			$contacts_array = array();
			foreach ($source->contacts as $contact) {
//				$contacts_array[]['name'] = $contact->name;
//				$contacts_array[]['email'] = $contact->email;
				$name = $contact->name;
				if ( isset($contact->email) ) {
					$email = $contact->email;
				}
			}
//			$contacts = implode('|', $contacts_array);
//			echo "$source_name $source_urls -> $name $email";
		}

		// Get variant information
//		print_r($json->variants);
		$mutalyzer_check_result = "0";
		foreach ($json->variants as $variant) {
			$variant_count++;
			foreach ($variant->genes as $gene) {
				$gene_name = $gene->accession;
			}
			$ref = $variant->ref_seq->accession;
			$hgvs = $variant->name->string;
			
			if ( $mutalyzer_check == "on" ) {
				$result = runMutalyzer($ref, $hgvs);
//				error_log("result -> " . print_r($result, 1));
				$mutalyzer_check_result = $result['is_valid'];
			}
			
			$sharing_policy = $variant->sharing_policy->type;
			$variant_id = $variant->id;
//			foreach ( $variant->comments as $comment ) {
//			}
//			print_r($variant);

			$variant_data = array(
				"source" => $source_destination,
				"laboratory" => $source_destination,
				"gene" => $gene_name,
				"ref" => $ref,
				"hgvs" => $hgvs,
				"variant_id" => $variant_id,
				"sharing_policy" => $sharing_policy,
//				"individual_id" => $individual_id,
//				"gender" => $gender,
//				"ethnicity" => $ethnicity,
//				"location_ref" => $location_ref,
//				"start" => $start,
//				"end" => $end,
//				"build" => $build,
//				"comment" => $comment,
				"mutalyzer_check" => $mutalyzer_check_result,
//				"date_time" => $date_time,
			);

			$insert_id = 0;
			$update_id = 0;
			if ( $overwrite_ids == "off" ) {
//				if ( ! $does_variant_id_exist ) {
					$insert_id = $this->sources_model->insertVariants($variant_data);
//				}
			}
			else if ( $overwrite_ids == "on" ){
				$does_variant_id_exist = $this->sources_model->checkVariantIDExists($variant_id);
				if ( ! $does_variant_id_exist ) {
					$insert_id = $this->sources_model->insertVariants($variant_data);
				}
				else {
					$update_id = $this->sources_model->updateVariantByVariantID($variant_data, $variant_id);
				}
			} 
			
			if ( $update_id ) {
				$successful_updates++;
//				error_log("no update id");
				$update_flag = 1;
			}
//			else {
//				$successful_updates++;
//				error_log("update id so increment $successful_updates");
//			}
			
			if ($insert_id) {
				$successful_inserts++;
				$insert_flag = 1;
			}
		}
		$return_data = array('insert_flag' => $insert_flag, 'update_flag' => $update_flag, 'variant_count' => $variant_count, 'successful_inserts' => $successful_inserts, 'successful_updates' => $successful_updates);
		return $return_data;
	}

	function _parseLOVDJSON($json, $source_destination, $sharing_policy, $overwrite_ids, $mutalyzer_check) {
		$variant_count = 0;
		$insert_flag = 0;
		$update_flag = 0;
		$successful_inserts = 0;
		$successful_updates = 0;
		
		// Excluded submitters from the LOVD export interface
		$exclude_submitters = $json->exclude;

		// Get the base_url for forming the link to the LOVD record
		$base_url = $json->base_url;

		// Get variant information
		$mutalyzer_check_result = "0";
		foreach ($json->data as $variant) {
//			error_log("----> " . print_r($variant, 1));

			$submitter_id = $variant->submitterid;
			// error_log("submitter_id -> $submitter_id");
			$exclude_flag = FALSE;
			// Check that this submitter hasn't been excluded in the LOVD export interface, if it has then skip it
			if ( ! empty ($exclude_submitters )) {
				foreach ( $exclude_submitters as $s ) {
					if ( $s == $submitter_id ) {
						// error_log("$submitter_id vs $s");
						$exclude_flag = TRUE;
					}
					// else {
						// $exclude_flag = FALSE;
						// error_log("$submitter_id vs $s FALSE");
					// }
				}

			}
			if ( $exclude_flag ) {
				// error_log("skip for $submitter_id");
				continue;
			}
			
			$variant_count++;
			// error_log("variant_count -> " . $variant_count);
			$gene_name = $variant->gene;
			$ref = $variant->refseq_mrna;
			$hgvs = $variant->DNA;



			// Use the patient ID combined with variant ID for the variant ID for CV as in LOVD the variant ID is not unique so need to combine with the patient ID
//			$variant_id = $variant->variantid;
			$variant_id = $variant->variantid . "_" . $variant->patientid;
			$pathogenicity = $variant->pathogenicity;
			$individual_id = $variant->patientid;
			$variant_data = array(
				"source" => $source_destination,
				"laboratory" => $source_destination,
				"gene" => $gene_name,
				"ref" => $ref,
				"hgvs" => $hgvs,
				"variant_id" => $variant_id,
				"sharing_policy" => $sharing_policy,
				"pathogenicity" => $pathogenicity,
				"individual_id" => $individual_id,
//				"gender" => $gender,
//				"ethnicity" => $ethnicity,
//				"location_ref" => $location_ref,
//				"start" => $start,
//				"end" => $end,
//				"build" => $build,
//				"comment" => "$submitter_id",
				"source_url" =>  $base_url . "/variants.php?select_db=" . $gene_name . "&action=view&view=" . $variant->patientid . "%2C" . $variant->variantid . "%2C0",
//				"mutalyzer_check" => $mutalyzer_check_result,
//				"date_time" => $date_time,
			);
			
			
			if ( $mutalyzer_check == "on" ) {
				$this->load->library('mutalyzer');
				$result = $this->mutalyzer->runMutalyzer($ref, $hgvs);
//				error_log("result -> " . print_r($result, 1));
				$mutalyzer_check_result = $result['is_valid'];
				$variant_data['mutalyzer_check_result'] = $mutalyzer_check_result;
			}
			
//			error_log(print_r($variant_data, 1));
			$insert_id = 0;
			$update_id = 0;
			if ( $overwrite_ids == "off" ) {
//				if ( ! $does_variant_id_exist ) {
					$insert_id = $this->sources_model->insertVariants($variant_data);
//					error_log("vid insert(o) -> $insert_id");
//				}
			}
			elseif ( $overwrite_ids == "on" ){
				$does_variant_id_exist = $this->sources_model->checkVariantIDExists($variant_id);
				if ( ! $does_variant_id_exist ) {
					$insert_id = $this->sources_model->insertVariants($variant_data);

//					error_log("vid -> $variant_id -> insertid -> $insert_id");

					if ( $variant->phenotype ) {
//						error_log("PHENOTYPE -> " . $variant->phenotype);
						$termId = $variant->phenotype;
						$termId = strtolower($termId);
						$termId = str_replace(' ', '_', $termId);
						$termId = str_replace("\t", '_', $termId);
						$termId = "locallist/" . $termId;
						$phenotype_data = array(
							"sourceId" => "LocalList",
							"termId" => $termId,
							"termName" => $variant->phenotype,
							"cafevariome_id" => $insert_id);
//
						$phenotype_insert_id = $this->sources_model->insertPhenotypes($phenotype_data);
						$pl = $this->sources_model->getPrimaryLookup($termId);
						if (!$pl) {
							$lookup_data = array(
								"sourceId" => "LocalList",
								"termId" => $termId,
								"termName" => $variant->phenotype);
							$lookup_insert_id = $this->sources_model->insertPrimaryLookup($lookup_data);
						}
					}
					
					

				}
				else {
//					error_log(print_r($variant_data, 1));
					$update_id = $this->sources_model->updateVariantByVariantID($variant_data, $variant_id);
//					error_log("vid update -> $variant_id");
				}
			} 
			
			if ( $update_id ) {
				$successful_updates++;
//				error_log("no update id");
				$update_flag = 1;
			}
//			else {
//				$successful_updates++;
//				error_log("update id so increment $successful_updates");
//			}
			
			if ($insert_id) {
				$successful_inserts++;
				$insert_flag = 1;
			}
		}
		$return_data = array('insert_flag' => $insert_flag, 'update_flag' => $update_flag, 'variant_count' => $variant_count, 'successful_inserts' => $successful_inserts, 'successful_updates' => $successful_updates);
		return $return_data;
	}
	
	function _parseAlamutXML($xml, $source_destination, $sharing_policy, $overwrite_ids, $mutalyzer_check) {
		$variant_count = 0;
		$insert_flag = 0;
		$update_flag = 0;
		$fail_flag = 0;
		$successful_inserts = 0;
		$successful_updates = 0;
		$fails = 0;
		$mutalyzer_check_result = "0";
		$details = array();
		$this->load->library('elasticsearch');
		
		foreach ($xml as $mutation) {
//			print_r($mutation);
			$mutation_id = (string) $mutation['id'];
			$version = (string) $mutation['version'];
			$organism = (string) $mutation['organism'];
			$ref_assembly = (string) $mutation['refAssembly'];
			$chr = (string) $mutation['chr'];
			$gene_name = (string) $mutation['geneSym'];
//			echo "MUT -> $mutation_id $gene_name";
			$variant = $mutation->Variant;
//			error_log("TYPE -> " . gettype($variant) . " POS -> " . $variant->pos);
			$type = $variant['type'];
			$start = "";
			$end = "";
			
			if (isset($variant['pos'])) {
				$start = (string) $variant['pos'];
				$end = $start;				
			}
			else if (isset($variant['from'])) {
				$start = (string) $variant['from'];
				$end = (string) $variant['to'];				
			}
			else {
				error_log("No genomic start stop in Alamut XML");
//				error_log(print_r($variant, 1));
			}
			
//			if ($type == "Deletion") {
//				$start = (string) $variant['from'];
//				$end = (string) $variant['to'];
////				print "$chr $start $end\n";
//			}
//			else if ($type == "Substitution") {
//				$start = (string) $variant['pos'];
//				$end = $start;
//				$base_from = (string) $variant['baseFrom'];
//				$base_to = (string) $variant['baseTo'];
////				print "$chr $start $base_from $base_to\n";
//			}
//			else if ($type == "Duplication") {
//				$start = (string) $variant['from'];
//				$end = (string) $variant['to'];
//			}
			
			$hgvs_g = (string) $variant->gNomen['val'];
//			print_r($variant->Nomenclature);
			$ref = (string) $variant->Nomenclature['refSeq'];
			$hgvs = (string) $variant->Nomenclature->cNomen['val'];
			$hgvs_r = (string) $variant->Nomenclature->rNomen['val'];
			$hgvs_p = (string) $variant->Nomenclature->pNomen['val'];
			$pathogenicity_list_type = (string) $mutation->Classification['val'];
			$pathogenicity = (string) $mutation->Pathogenic['val'];
			$note = (string) $mutation->Note['val'];
			
			if ( $mutalyzer_check == "on" ) {
				$this->load->library('Mutalyzer');
				$result = $this->mutalyzer->runMutalyzer($ref, $hgvs);
//				error_log("result -> " . print_r($result, 1));
				$mutalyzer_check_result = $result['is_valid'];
			}
			
			foreach ( $mutation->Occurrences as $occurrences ) {
//				print_r($occurrences);
				if ( isset($occurrences->Occurrence)) { // There are patients - want to insert a separate variant for each patient
//					print_r($mutation->Occurrences);
					$rna_analysis_paragraphs = "";
					$phenotype_paragraphs = "";
					$comment_paragraphs = "";
					$patient = "";
					foreach ( $occurrences as $occurrence ) {
						$variant_count++;
						$created = (string) $occurrence->Created['date'] . " " . $occurrence->Created['time'];
						$updated = (string) $occurrence->Updated['date'] . " " . $occurrence->Updated['time'];
						$patient = (string) $occurrence->Patient;
						$family = (string) $occurrence->Family;
					
						$rna_analysis = (string) $occurrences->RNAAnalysis;
						$phenotype = (string) $occurrence->Phenotype;
						$comment = (string) $occurrence->Comment;
					
						if ( $rna_analysis ) {
							$rna_analysis_paragraphs = $this->_getAllParagraphsFromAlamutHTML($rna_analysis);
//							print "r -> $rna_analysis_paragraphs\n";
						}
					
						if ( $phenotype ) {
							$phenotype_paragraphs = $this->_getAllParagraphsFromAlamutHTML($phenotype);
//							print "p -> $phenotype_paragraphs\n";
						}
					
						if ( $comment ) {
							$comment_paragraphs = $this->_getAllParagraphsFromAlamutHTML($comment);
//							print "c -> $comment_paragraphs\n";
						}
						
						$variant_data = array(
							"source" => $source_destination,
							"laboratory" => $source_destination,
							"variant_id" => $mutation_id,
							"gene" => $gene_name,
							"ref" => $ref,
							"hgvs" => $hgvs,
//							"genomic_ref" => $ref,
							"genomic_hgvs" => $hgvs_g,
//							"protein_ref" => $ref,
							"protein_hgvs" => $hgvs_p,
							"sharing_policy" => $sharing_policy,
							"phenotype" => $phenotype_paragraphs,
							"individual_id" => $patient,
//							"gender" => $gender,
							"pathogenicity" => $pathogenicity,
							"pathogenicity_list_type" => $pathogenicity_list_type,
							"location_ref" => $chr,
							"start" => $start,
							"end" => $end,
							"build" => $ref_assembly,
							"comment" => $note . " " . $comment_paragraphs,
							"mutalyzer_check" => $mutalyzer_check_result,
//							"date_time" => $date_time,
						);

//						print_r($variant_data);			
			
						$insert_id = 0;
						$update_id = 0;
						if ( $overwrite_ids == "off" ) {
//							if ( ! $does_variant_id_exist ) {
								$insert_id = $this->sources_model->insertVariants($variant_data);
								$index_result = $this->_addVariantToElasticSearchIndex($variant_data, $insert_id);
//								error_log("RESULT -> " . print_r($index_result, 1));

//							}
						}
						else if ( $overwrite_ids == "on" ){
							$does_variant_id_exist = $this->sources_model->checkVariantIDExists($mutation_id);
//							$insert_id = $this->sources_model->insertVariant($variant_id);
							if ( ! $does_variant_id_exist ) {
								$insert_id = $this->sources_model->insertVariants($variant_data);
								$index_result = $this->_addVariantToElasticSearchIndex($variant_data, $insert_id);
//								error_log("RESULT -> " . print_r($index_result, 1));

							}
							else {
								$update_id = $this->sources_model->updateVariantByVariantID($variant_data, $mutation_id);
								$update_result = $this->_updateVariantInElasticSearchIndex($variant_data, $mutation_id);
							}
						}
						
						$details[] = $variant_data;
						if ( $update_id ) {
							$successful_updates++;
//							error_log("no update id");
							$update_flag = 1;
							$updated_id = $this->sources_model->getCafeVariomeIDForVariantID($mutation_id);
							$variant_data = array('cafevariome_id' => $this->config->item('cvid_prefix') . $updated_id) + $variant_data; // Add CVID to the start of the array
							$details[] = array('operation_type' => 'update', 'cafevariome_id' => $this->config->item('cvid_prefix') . $updated_id, 'data' => $variant_data);
						}
//						else {
//							$successful_updates++;
//							error_log("update id so increment $successful_updates");
//						}
			
						if ($insert_id) {
							$successful_inserts++;
							$insert_flag = 1;
							$variant_data = array('cafevariome_id' => $this->config->item('cvid_prefix') . $insert_id) + $variant_data; // Add CVID to the start of the array
							$details[] = array('operation_type' => 'insert', 'cafevariome_id' => $this->config->item('cvid_prefix') . $insert_id, 'data' => $variant_data);
						}
						
					}
				}
				else { // Only 1 patient (or none reported) so just insert variant once
					$variant_count++;
					$variant_data = array(
						"source" => $source_destination,
						"laboratory" => $source_destination,
						"variant_id" => $mutation_id,
						"gene" => $gene_name,
						"ref" => $ref,
						"hgvs" => $hgvs,
//						"genomic_ref" => $ref,
						"genomic_hgvs" => $hgvs_g,
//						"protein_ref" => $ref,
						"protein_hgvs" => $hgvs_p,
						"sharing_policy" => $sharing_policy,
//						"phenotype" => $phenotype_paragraphs,
//						"individual_id" => $patient,
//						"gender" => $gender,
						"pathogenicity" => $pathogenicity,
						"pathogenicity_list_type" => $pathogenicity_list_type,
						"location_ref" => $chr,
						"start" => $start,
						"end" => $end,
						"build" => $ref_assembly,
						"comment" => $note,
						"mutalyzer_check" => $mutalyzer_check_result,
//						"date_time" => $date_time,
					);
//					error_log("data -> " . print_r($variant_data, 1));
//					print_r($variant_data);			
			
					$insert_id = 0;
					$update_id = 0;
					if ( $overwrite_ids == "off" ) {
//						if ( ! $does_variant_id_exist ) {
							$insert_id = $this->sources_model->insertVariants($variant_data);
							$index_result = $this->_addVariantToElasticSearchIndex($variant_data, $insert_id);
//							error_log("RESULT -> " . print_r($index_result, 1));
//							if ( ! $index_result[0]->ok ) {
							if ( ! $index_result ) {
								$index_result_flag = 0;
							}

							
//						}
					}
					else if ( $overwrite_ids == "on" ){
						$does_variant_id_exist = $this->sources_model->checkVariantIDExists($mutation_id);
//						$insert_id = $this->sources_model->insertVariant($variant_id);
						if ( ! $does_variant_id_exist ) {
							$insert_id = $this->sources_model->insertVariants($variant_data);
							$index_result = $this->_addVariantToElasticSearchIndex($variant_data, $insert_id);
//							error_log("RESULT -> " . print_r($index_result, 1));

						}
						else {
							$update_id = $this->sources_model->updateVariantByVariantID($variant_data, $mutation_id);
							$update_result = $this->_updateVariantInElasticSearchIndex($variant_data, $mutation_id);
							
						}
					} 
			
					
					if ( $update_id ) {
						$successful_updates++;
						$update_flag = 1;
						$updated_id = $this->sources_model->getCafeVariomeIDForVariantID($mutation_id);
						$variant_data = array('cafevariome_id' => $this->config->item('cvid_prefix') . $updated_id) + $variant_data; // Add CVID to the start of the array
						$details[] = array('operation_type' => 'update', 'cafevariome_id' => $this->config->item('cvid_prefix') . $updated_id, 'data' => $variant_data);
					}
//					else {
//						$successful_updates++;
//						error_log("update id so increment $successful_updates");
//					}
			
					if ($insert_id) {
						$successful_inserts++;
						$insert_flag = 1;
						$variant_data = array('cafevariome_id' => $this->config->item('cvid_prefix') . $insert_id) + $variant_data; // Add CVID to the start of the array
						$details[] = array('operation_type' => 'insert', 'cafevariome_id' => $this->config->item('cvid_prefix') . $insert_id, 'data' => $variant_data);
					}
				}
			}
			
			

			
		}
		$return_data = array('insert_flag' => $insert_flag, 'update_flag' => $update_flag, 'fail_flag' => $fail_flag, 'variant_count' => $variant_count, 'successful_inserts' => $successful_inserts, 'successful_updates' => $successful_updates, 'fails' => $fails, 'details' => $details);
		return $return_data;
	}
	
	function _addVariantToElasticSearchIndex($index_data, $insert_id) {
		// ElasticSearch insert (if ElasticSearch is enabled and running)
		$index_data['cafevariome_id'] = $insert_id;
		$index_data = json_encode($index_data);
//		error_log("index -> $index_data");
		if ( $this->config->item('use_elasticsearch') ) {
			$this->load->library('elasticsearch');
//			$check_if_running = $this->elasticsearch->check_if_running();
//			if ( array_key_exists( 'ok', $check_if_running) ) {
				// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
				$es_index = $this->config->item('site_title');
				$es_index = preg_replace('/\s+/', '', $es_index);
				$es_index = strtolower($es_index);
				$this->elasticsearch->set_index($es_index);
				$this->elasticsearch->set_type("variants");
				$index_result = $this->elasticsearch->add($insert_id, $index_data);
//				error_log("result -> $index_result");
				return $index_result;
//			}
//			else {
//				error_log("elasticsearch is not running");
//			}
		}
	}
	
	function _updateVariantInElasticSearchIndex($update_data, $id) {
		// ElasticSearch update (if ElasticSearch is enabled and running)
		if ( $this->config->item('use_elasticsearch') ) { 
			$this->load->library('elasticsearch');
			$check_if_running = $this->elasticsearch->check_if_running();
			if ( array_key_exists( 'ok', $check_if_running) ) {
				// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
				$es_index = $this->config->item('site_title');
				$es_index = preg_replace('/\s+/', '', $es_index);
				$es_index = strtolower($es_index);
				$this->elasticsearch->set_index($es_index);
				$this->elasticsearch->set_type("variants");
//				error_log("id -> $id");
				$update = array();
				$update['doc'] = $update_data;
				$update = json_encode($update);
//				error_log("update $id -> $update");
				$update_result = $this->elasticsearch->update($id, $update);
				return $update_result;
			}
		}
	}
	
	function _getAllParagraphsFromAlamutHTML($string) {
		$doc = new DOMDocument();
		$doc->loadHTML($string);
		$paragraphs = array();
		foreach($doc->getElementsByTagName('p') as $paragraph) {
			$paragraphs[] = $paragraph->textContent;
		}
		return implode(" ", $paragraphs);
	}
	
	function _parseVarioML($xml, $source_destination, $sharing_policy, $overwrite_ids, $mutalyzer_check) {
		$variant_count = 0;
		$insert_flag = 1;

//		$xml = $content;
//		$doc = new DOMDocument();
//		$doc->loadXML($xml);
//		$xml = $doc->saveXML();
//		echo $xml;
		// TODO: parsing VarioML in model isn't working - need to finish it off (already got code for atomserver parsing?) and return an array of variants to insert
//		$this->load->model('varioml_model');
//		$variants_data = $this->varioml_model->parseVarioML($xml);
//		$this->load->model('sources_model');
		// TODO: When inserted then make the variant inactive
//		foreach ( $variants_data as $variant ) {
//			$insert_id = $this->sources_model->insertVariants($variant);
//		}
//		<variant id="{5a4a5af9-693c-4771-a6ad-2cd2d851e7fe}"
	}
	
	function detect_content_type($content) {
		// Check if json
//		error_log("content -> $content");
		if ( $json = json_decode($content) != NULL ) {
//			echo "json!";
			$json_array = json_decode($content);
			if ( $json_array->type == "lovd" ) {
				$type = "lovd";
			}
			else {
				$type = "json";
			}
			return $type;
		}

		// Check if XML (suppress all errors)
		libxml_use_internal_errors(true);
		$xml = simplexml_load_string($content);
		if ($xml===FALSE) {
//			echo "not XML!";
//			foreach(libxml_get_errors() as $error) {
//				echo "\t", $error->message;
//			}
		}
		else {
			$type = "xml";
			return $type;
		}
		// re-enable errors
		libxml_use_internal_errors(false);
		
		// Check for tab delimited
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $content) as $line){
//			error_log("line -> $line");
			$fields = explode("\t", $line);
			if(count($fields)>1) { // Line was exploded successfully so must be tab delimited
				$type = "tab";
				return $type;
			}
			$fields = explode(",", $line);
			if(count($fields)>1) { // Line was exploded successfully so must be comma delimited
				$type = "csv";
				return $type;
			}
		}
		
		// Check for other data types here (tab delimited, Excel)
		
		return FALSE;
	}
	
}