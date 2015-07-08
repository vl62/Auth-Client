<?php

class Beacon extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('beacon_model');
	}
	
	public function response() {

		if ( ! $this->config->item('federated')) {
			show_error("Sorry this installation does not have federation enabled");
//			$this->output->set_status_header('401');
//			$error = array('error' => 'Sorry this installation does not have federation enabled');
//			$this->output->set_content_type('application/json')->set_output(json_encode($error));
//			echo json_encode($error);
//			exit();
		}

		//TODO: Log IP address of incoming request
		$chromosome = $this->input->get('chrom', TRUE);
		$position = $this->input->get('pos', TRUE);
		$allele = $this->input->get('allele', TRUE);
		$reference = $this->input->get('ref', TRUE);
		$key = $this->input->get('key', TRUE);
		if ( $key != "cafevariome" ) {
//			show_error("You do not have permission to query this Cafe Variome Beacon");
			$this->output->set_status_header('401');
			exit();
		}
		
		$site_title = strtolower(str_replace(' ', '_', $this->config->item('site_title')));
		$beacon_response[] = array('id' => $site_title, 'name' => $this->config->item('site_description'));
		$beacon_response[] = array('allele' => $allele, 'chromosome' => $chromosome, 'position' => $position, 'reference' => $reference);
		
		$this->load->model("beacon_model");
		
		$sharing_policies_statuses = $this->beacon_model->getBeaconSharingPoliciesStatuses();
		
		$query = $this->beacon_model->getBeaconResponse($chromosome, $position, $allele, $reference);
		// true if there's a record existing, false if there's not
//		$response = empty($query) ? 'false' : 'true';
		
		$flag = 0;
		$openAccess_flag = 0;
		$linkedAccess_flag = 0;
		$restrictedAccess_flag = 0;

		// Hack for CV central for dbsnp source to convert the position to zero based for dbsnp so that correct position is queried
		if ( $this->config->item('cafevariome_central') ) {
			$position_zero_based = $position - 1;
			$dbsnp_query = $this->beacon_model->getBeaconResponseForSource('dbsnp', $chromosome, $position_zero_based, $allele, $reference);
//			error_log('dbsnp central -> ' . print_r($dbsnp_query, 1));
			$query = array_merge($query,$dbsnp_query); 
		}
		
		foreach ( $query as $hit ) {
//			print_r($hit);
			$hgvs = $hit['hgvs'];
			if (preg_match_all("/^([c|g])\.([-|\*]*)(\d+)([+|-]*)(\d*)(\S+)>(\S+)/", $hgvs, $matches)) {
//				print_r($matches);
//				print "----> " . $matches[7][0] . "<br />";
				if ( $matches[7][0] == $allele ) {
					
					if ( $hit['sharing_policy'] == "openAccess" ) {
						if ( $sharing_policies_statuses['openAccess'] ) {
							$openAccess_flag = 1;
							$flag = 1;
						}
					}
					elseif ( $hit['sharing_policy'] == "linkedAccess" ) {
						if ( $sharing_policies_statuses['linkedAccess'] ) {
							$linkedAccess_flag = 1;
							$flag = 1;
						}
					}
					elseif ( $hit['sharing_policy'] == "restrictedAccess" ) {
						if ( $sharing_policies_statuses['restrictedAccess'] ) {
							$restrictedAccess_flag = 1;
							$flag = 1;
						}
					}
				}
			}
		}
		// true if there's a record existing with the specified allele, false if there's not
		$response = $flag ? 'true' : 'false';
		$openAccess_response = $openAccess_flag ? 'true' : 'false';
		$linkedAccess_response = $linkedAccess_flag ? 'true' : 'false';
		$restrictedAccess_response = $restrictedAccess_flag ? 'true' : 'false';
		
		

		$beacon_response[] = array('beacon_visibility' => array('openAccess' => $sharing_policies_statuses['openAccess'], 'linkedAccess' => $sharing_policies_statuses['linkedAccess'], 'restrictedAccess' => $sharing_policies_statuses['restrictedAccess']));

		// If any sharing policies aren't enabled then set the response to unknown

		if ( ! $sharing_policies_statuses['openAccess'] ) {
			$openAccess_response = 'unknown';
		}
	
		if ( ! $sharing_policies_statuses['linkedAccess'] ) {
			$linkedAccess_response = 'unknown';
		}
		
		if ( ! $sharing_policies_statuses['restrictedAccess'] ) {
			$restrictedAccess_response = 'unknown';
		}
		
		// Only return the aggregate beacon response if there's at least one sharing_policy status switched to 1
		if ( ! $sharing_policies_statuses['openAccess'] && ! $sharing_policies_statuses['restrictedAccess'] && ! $sharing_policies_statuses['linkedAccess'] ) {
			$beacon_response[] = array('response' => 'unknown');
		}
		else {
			$beacon_response[] = array('response' => $response);			
		}
		
		$beacon_response[] = array('extended_response' => array('openAccess' => $openAccess_response, 'linkedAccess' => $linkedAccess_response, 'restrictedAccess' => $restrictedAccess_response));
		$this->output->set_content_type('application/json')->set_output(json_format(json_encode($beacon_response)));
		
	}
	
	function settings($message = NULL) {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$this->data['sharing_policies_statuses'] = $this->beacon_model->getBeaconSharingPoliciesStatuses();
		$this->_render('beacon/settings');

	}
	
	// Update beacon status for a sharing policy - called from jquery function when enabled/disabled switches are clicked in the beacons settings
	function update_status() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		
		// Retrieve the POST values for this update request
		$status = $this->input->post('status');
		$sharing_policy = $this->input->post('sharing_policy');
//		error_log($sharing_policy . " -> " . $status);

		// Update the status for this sharing policy in the settings for this local instance
		$update_result = $this->beacon_model->updateBeaconSharingPolicy($sharing_policy, $status);
//		error_log("update status -> $update_result");
		if ( $update_result ) {
			echo json_encode(array('status' => 'success', 'message' => "$sharing_policy beacon $status"));
		}
		else {
			echo json_encode(array('status' => 'failed', 'message' => "$sharing_policy beacon $status"));
		}
		
		// Update the status at the main head beacon		
		$update_result = $this->_update_head_beacon_status($sharing_policy, $status);
		
	}
	
	// Update the beacon status for sharing policy in the brookeslab beacon - POST
	function _update_head_beacon_status($sharing_policy, $status) {
		$base_url = base_url();
		$data = array ('sharing_policy' => $sharing_policy, 'status' => $status, 'name' => $this->config->item('site_title'), 'url' => $base_url );
		$url = "http://beacon.cafevariome.org/update_status.php"; // URL of Tim's main CV beacon aggregator
		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($opts);
		$result = @file_get_contents($url, false, $context); // Suppress errors for this as some server configs won't allow this and will return a 500 error
//		error_log("--> " . print_r($result),1);
		return $result;
	}
	
	
}

?>
