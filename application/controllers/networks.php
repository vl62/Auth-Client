<?php

class Networks extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('federated_model');
	}
	
	public function index() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
	
		$this->data['title'] = "Networks";
		$token = $this->session->userdata('Token');
		$data = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_networks_installation_member_of_with_other_installation_details");
		$installations_for_networks = json_decode($data, true);
		$this->data['networks'] = $installations_for_networks;
		
//		$networks = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_networks_installation_member_of");
//		$data = json_decode($networks, true);
//		print_r($installations_for_networks);
//		$installation_count_for_networks = array();
//		$installations_for_networks = array();
//		// Loop through each network and get the count of the installations for that network (only if this installation is part of a network
//		if ( $data ) {
//			foreach ($data as $network) {
//				$installations = json_decode(authPostRequest($token, array('network_key' => $network['network_key']), $this->config->item('auth_server') . "/api/auth/get_installations_for_network"), 1);
//				error_log(print_r($installations, 1));
//				$count = count($installations);
////				$count = json_decode(authPostRequest($token, array('network_key' => $network['network_key']), $this->config->item('auth_server') . "/api/auth/count_number_of_installations_for_network"), 1);
//				$installations_for_networks[$network['network_key']] = $installations;
//				$installation_count_for_networks[$network['network_key']] = $count;
//			}
//			
//			$this->data['installations_for_networks'] = $installations_for_networks;
//			$this->data['installation_count_for_networks'] = $installation_count_for_networks;
//		}
		
//		$this->data['networks'] = $data;
		
		$this->_render('federated/networks/my_networks');
	}
	
	function create_network() {
		$this->data['title'] = "Create Network";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		// Validate form input
		$this->form_validation->set_rules('name', 'Network Name', 'required|xss_clean|alpha_dash|callback_uniquename_check');
		$this->form_validation->set_message("required", "requried");
		$this->form_validation->set_message('uniquename_check', 'The network name already exists.');
                
		if ($this->form_validation->run() == FALSE) {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

                    $this->data['name'] = array(
                            'name'  => 'name',
                            'id'    => 'name',
                            'type'  => 'text',
                            'style' => 'width:50%',
                            'value' => $this->form_validation->set_value('name'),
                    );

                    $this->_render('federated/networks/create_network');

		}
		else {
			$name = strtolower($this->input->post('name')); // Convert the network name to lowercase
//			print "$name";
//			$base_url = urlencode(base64_encode(base_url()));
			$token = $this->session->userdata('Token');
			$base_url = base_url();
			$network = authPostRequest($token, array('installation_base_url' => $base_url, 'network_name' => $name, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/create_network");
//			$data = json_decode(createNetwork(array('installation_base_url' => $base_url, 'network_name' => $name, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server')));
//			echo "create network:<br />";
			error_log(print_r($network,1));
			$this->session->set_flashdata('message', "Successfully created network $name");
			redirect("networks", 'refresh');
		}		
	}
	

	
	
//	function create_network() {
//		
//            $this->data['title'] = "Add Network";
//
//            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//                    redirect('auth', 'refresh');
//            }
//                // Validate form input
////                $this->form_validation->set_rules('name', 'Network Name', 'required|xss_clean|alpha_dash|callback_uniquename_check');
////                $this->form_validation->set_message("required", "requried");
//                
//		// Get all available groups
//		$this->data['groups'] = $this->ion_auth->getGroups();
//                // Get all the users in this installation for the curator select list
//		$this->data['users'] = $this->ion_auth->users()->result();
//		if ($this->form_validation->run() == FALSE) {
//                    $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
//
//                    $this->data['name'] = array(
//                            'name'  => 'name',
//                            'id'    => 'name',
//                            'type'  => 'text',
//                            'style' => 'width:50%',
//                            'value' => $this->form_validation->set_value('name'),
//                    );
//
//                    $this->_render('federated/create_network');
//
//		}
//		else {
//			$name = strtolower($this->input->post('name')); // Convert the network name to lowercase
//			
////			redirect("federated_settings", 'refresh');
//                        
//                        $data = json_decode(createNetwork(array('network_name' => $name, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server')));
//                        echo "create network:<br />";
//                        print_r($data);
//		}
//	}
        
//        function validate_create_network() {
//             // Validate form input
//            $network_name = strtolower($this->input->post('name'));
//            $this->form_validation->set_rules('name', 'Network Name', 'required|xss_clean|alpha_dash|callback_uniquename_check');
//            $this->form_validation->set_message('uniquename_check', 'The network name "' . $network_name . '" already exists.');
//            $this->form_validation->set_error_delimiters('', '');
//            
//            if($this->form_validation->run()) {
//                json_decode(createNetwork(array('network_name' => $network_name, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server')));
//                
//                echo json_encode(array('ok' => 'Network "' . $network_name . '" Created Successfully.'));
//                return;
//            } else {
//                echo json_encode(array('error' => validation_errors()));
//                return;
//            }
//        }
//        
		function uniquename_check($network_name) {
			$this->load->model('network_model');
			// Make API call to the central auth server to see whether this network name is already existing, if it does then an error will be given in create network form based on the return from this callback
//			$data = json_decode(checkNetworkExists(array('network_name' => $network_name), $this->config->item('auth_server')));
			$token = $this->session->userdata('Token');
			$data = json_decode(authPostRequest($token, array('network_name' => $network_name), $this->config->item('auth_server') . "/api/auth/check_network_exists"));
//			check_network_exists
//			error_log("data -> " . $data);
            $bool_test = $data === 'true'? true: false; // Need to convert the true/false string returned by the API call to boolean and then test
            if( $bool_test ) {
				return false;
			}
			else {
				return true;
			}
		}
        
        function process_network_create_request() {

            $network_name = $this->input->post('name');
            $this->load->model('network_model');
            
            if($this->network_model->checkNetworkExists($network_name))
                echo json_encode(array('success' => 'Network exists'));
            else
                echo json_encode(array('fine' => ''));
            
        }
        
        function join_network() {
			$this->data['title'] = "Join Network";

            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
                    redirect('auth', 'refresh');
            }
            
            if (!isset($_POST['networks'])) {
                $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

                $this->data['justification'] = array(
                            'name'  => 'justification',
                            'id'    => 'justification',
                            'type'  => 'text',
                            'rows'  => '5',
                            'cols'  => '3',
                            'style' => 'width:50%',
                            'value' => $this->form_validation->set_value('justification'),
                    );
                $token = $this->session->userdata('Token');
				$networks = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_networks_installation_not_a_member_of");
//				$networks = getNetworksInstallationNotMemberOf(array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server'));
//				error_log("$token getnetworks -> $networks -> " . $this->config->item('installation_key'));
				$data = json_decode($networks, true);
//				error_log("decoded -> " . print_r($data, 1));
//				$jsonp_decode = $this->jsonp_decode($data);
                $this->data['networks'] = $data;
                $this->_render('federated/networks/join_network');

            }
        }
        
        function process_network_join_request() {
            $result['network_key'] = $this->input->post('networks');
            $result['justification'] = $this->input->post('justification');
			
			$result['installation_key'] = $this->config->item('installation_key');
            $user = $this->ion_auth->user($this->session->userdata( 'user_id' ))->row();
            $result['username'] = $user->username;
            $result['email'] = $user->email;
			$result['auth_server'] = $this->config->item('auth_server');
//			error_log("post -> " . $this->input->post('networks') . " install -> " . $this->config->item('installation_key'));
//			$networks = authPostRequest('', $result, $this->config->item('auth_server') . "/api/auth/get_networks_installation_not_a_member_of");
//			$base_url = base_url();
//			$token = $this->session->userdata('Token');
//			$networks = authPostRequest($token, array('network_key' => $this->input->post('networks'), 'installation_key' => $this->config->item('installation_key'), 'installation_base_url' => $base_url), $this->config->item('auth_server') . "/api/auth/join_network");
//			echo $networks;
			
			$token = $this->session->userdata('Token');
			$networks = authPostRequest($token, $result, $this->config->item('auth_server') . "/api/auth/join_network_request");
			error_log("networks -> $networks");
			$data = json_decode($networks, 1);
//			$data = json_decode(joinNetwork($result, $this->config->item('auth_server')));
                
            if (array_key_exists('network_request_id', $data)) {
                echo json_encode(array('success' => 'Network join request has been sent!'));
            } else {
                echo json_encode(array('error' => 'Sorry, unable to process request. Retry!'));
            }
        }
        
        function jsonp_decode($jsonp, $assoc = false) { // PHP 5.3 adds depth as third parameter to json_decode
            $jsonLiterals = array('true' => 1, 'false' => 1, 'null');
            if(preg_match('/^[^[{"\d]/', $jsonp) && !isset($jsonLiterals[$jsonp])) { // we have JSONP
               $jsonp = substr($jsonp, strpos($jsonp, '('));
            }
            
            return json_decode(trim($jsonp,'();'), $assoc);
        }
        
        function network_requests_incoming() {
            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
				redirect('auth', 'refresh');
            }
			$this->title = "Network Requests";
            $this->load->model('network_model');
			
//			$network_requests = getNetworkRequestsForNetworksThisInstallationBelongsTo(array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server'));
			$token = $this->session->userdata('Token');
			$network_requests = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_network_requests_for_networks_this_installation_belongs_to");
			$data = json_decode($network_requests, true);
			

//			print_r($data);
			$this->data['network_requests'] = $data;
			$this->_render('federated/networks/network_requests_incoming');
        }
		
        function network_requests_outgoing() {
            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
				redirect('auth', 'refresh');
            }
			$this->title = "Network Requests";
            $this->load->model('network_model');

			$token = $this->session->userdata('Token');
			$network_requests = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_network_requests_for_installation");
//			$network_requests = getNetworkRequestsForInstallation(array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server'));
			$data = json_decode($network_requests, true);
			$this->data['network_requests'] = $data;
			$this->_render('federated/networks/network_requests_outgoing');
        }
        
        function process_network_request($result, $request_id) {
            
        }

	function assign_network_key() {
		if ( ! $this->config->item('cafevariome_central') ) {
		}
	}
	

	//delete the user
	function leave_network($network_key, $installation_count_for_network, $network_name) {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}		

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['network_key'] = $network_key;
			$this->data['installation_count_for_network'] = $installation_count_for_network;
			$this->data['network_name'] = $network_name;
			$this->_render('federated/networks/leave_network');
//			$this->load->view('auth/deactivate_user', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes') {
				$token = $this->session->userdata('Token');
				$data = authPostRequest($token, array('installation_count_for_network' => $installation_count_for_network, 'network_key' => $network_key, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/leave_network");
				$leave_network_success = json_decode($data, true);
				error_log(print_r($leave_network_success,1));
				if ( $leave_network_success['error'] ) {
					$this->session->set_flashdata('message', 'Leaving network failed');
				}
				else {
					$this->session->set_flashdata('message', 'Successfully left network');
				}
				redirect("networks", 'refresh');

			}

			//redirect them back to the auth page
			redirect('networks', 'refresh');
		}
	}
	
	function sleave_network($network_key, $installation_count_for_network) {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$token = $this->session->userdata('Token');
		$data = authPostRequest($token, array('installation_count_for_network' => $installation_count_for_network, 'network_key' => $network_key, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/leave_network");

//		$data = leaveNetwork(array(), $this->config->item('auth_server'));

//		print_r($leave_network_success);
		$leave_network_success = json_decode($data, true);
		error_log(print_r($leave_network_success,1));
		if ( $leave_network_success['error'] ) {
			$this->session->set_flashdata('message', 'Leaving network failed');
		}
		else {
			$this->session->set_flashdata('message', 'Successfully left network');
		}
		redirect("networks", 'refresh');
	}
	
	function view_networks_old() {
		$this->load->model('federated_model');
		$node_list = $this->federated_model->getNodeList();
		$node_statuses = array();
		foreach ( $node_list as $node_name => $node ) {
			$node_status = $this->node_ping($node['node_uri']); // Get the status of each node by pinging them
			$node_statuses[$node['node_name']] = $node_status;
			if ( ! $node_status ) { // If the node is down then update the node record in db
				$this->federated_model->updateNodeList(array('node_name' => $node_name, 'node_status' => 'offline'));
			}
			else {
				if ( $node['node_status'] == "offline" ) { // If the node is up and currently marked as offline in db then update the record and set it as online
					$this->federated_model->updateNodeList(array('node_name' => $node_name, 'node_status' => 'online'));
				}
			}
		}
		$this->data['node_statuses'] = $node_statuses;
		$this->data['node_list'] = $node_list;
		$this->_render('federated/networks/view_networks');
	}
	
	function send_federated_switch($on_or_off) {
		if ( ! $this->config->item('cafevariome_central') ) {
			$base_url = urlencode(base64_encode(base_url())); // Need to base64 encode as cannot pass a url as a controller parameter
//			$external_ip = urlencode(getExternalIP());
//			$real_ip = urlencode(getRealIpAddr());
			$site_title = urlencode($this->config->item('site_title'));
			$site_description = urlencode($this->config->item('site_description'));
			$result = file_get_contents("http://143.210.153.155/cafevariome/admin/get_federated_switch/$on_or_off/$base_url/$site_title/$site_description");
//			$result = file_get_contents("http://143.210.153.155/cafevariome/admin/get_federated_switch/$on_or_off/$base_url/$external_ip/$real_ip/$site_title/$site_description");
		}
	}

//	function get_federated_switch($on_or_off, $base_url, $external_ip, $real_ip, $site_title, $site_description ) {
	function get_federated_switch($on_or_off, $base_url, $site_title, $site_description ) {
		if ( $this->config->item('cafevariome_central') ) {
			$base_url = base64_decode(urldecode($base_url));
//			$external_ip = urldecode($external_ip);
//			$real_ip = urldecode($real_ip);
			$site_title = urldecode($site_title);
			$site_description = urldecode($site_description);
			
			$ping_result = $this->node_ping($base_url);
			error_log("central $base_url -> $on_or_off -> $ping_result");
			if ( $ping_result ) {
				$this->load->model('federated_model');
				// Update federated list table
				$data = array ( "federated_name" => $site_title, "federated_uri" => $base_url, "federated_status" => $on_or_off );
				$exists = $this->federated_model->checkFederatedURIExists($base_url);
				if ( $exists ) {
					$timestamp = date("Y-m-d H:i:s");
					$data['timestamp'] = $timestamp;
					$this->federated_model->updatedFederated($base_url, $data);
				}
				else {
					$insert_id = $this->federated_model->insertFederated($data);
				}
			}
		}
	}
}
?>
