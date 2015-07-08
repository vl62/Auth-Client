<?php

require(APPPATH . '/libraries/REST_Controller.php');

class Central extends REST_Controller {
	
	function create_network_post() {
		if ( $this->config->item('cafevariome_central') ) {
//			$ip = $_SERVER['REMOTE_ADDR'];
			$network_key = generateMD5();
			$network_name = $this->post('network_name');
			$installation_key = $this->post('installation_key');
			$installation_base_url = $this->post('installation_base_url');
			$data = array ( 
							'network_name' => $network_name,
							'network_key' => $network_key,
							'network_type' => 'federated'
						);
			$this->load->model("federated_model");
			$is_network_name_unique = $this->federated_model->isNetworkNameUnique($network_name);
			if ( $is_network_name_unique ) {
				$network_id = $this->federated_model->createNetwork($data);
				$this->federated_model->addInstallationToNetwork(array("installation_base_url" => $installation_base_url, "installation_key" => $installation_key, "network_key" => $network_key));
				$data = array('network_key' => $network_key);
				$this->response($data, 200);
			}
			else {
				$this->response(array("error" => "Network name is not unique"));
			}
		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	function check_network_exists_post() {
		if ( $this->config->item('cafevariome_central') ) {
			$network_name = $this->post('network_name');
//			error_log($network_name);
			$this->load->model("network_model");
				
			if($this->network_model->checkNetworkExists($network_name)) {
				$this->response(false, 200);
			} else {
				$this->response(true, 200);
			}
				
				
		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	
	
	function get_networks_get() {
		if ( $this->config->item('cafevariome_central') ) {
			$this->load->model("federated_model");
			$networks = $this->federated_model->getNetworks();
//			print_r($networks);
			if ( ! empty($networks) ) {
//				error_log('not empty -> ' . print_r($networks, 1));
				$this->response($networks, 200);
			}
			else {
//				error_log("no networks exist");
				$this->response(array("error" => "No networks exist"));
			}
		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	function get_networks_installation_not_a_member_of_post() {
		if ( $this->config->item('cafevariome_central') ) {
			$installation_key = $this->post('installation_key');
			$this->load->model("federated_model");
			$networks_installation_not_a_member_of = $this->federated_model->getNetworksInstallationNotAMemberOf($installation_key);
			if ( ! empty($networks_installation_not_a_member_of) ) {
//				error_log('not empty -> ' . print_r($networks_installation_not_a_member_of, 1));
				$this->response($networks_installation_not_a_member_of, 200);
			}
			else {
//				error_log("no networks exist");
				$this->response(array("error" => "Installation is a member of all available networks or there are no networks to join"));
			}

		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	function get_networks_installation_member_of_post() {
		if ( $this->config->item('cafevariome_central') ) {
			$installation_key = $this->post('installation_key');
			$this->load->model("federated_model");
			$networks_installation_member_of = $this->federated_model->getNetworksInstallationMemberOf($installation_key);
//			error_log(print_r($networks_installation_member_of, 1));
			if ( ! empty($networks_installation_member_of) ) {
//				error_log('not empty -> ' . print_r($networks_installation_not_a_member_of, 1));
				$this->response($networks_installation_member_of, 200);
			}

		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	function get_network_requests_for_installation_post() {
		if ( $this->config->item('cafevariome_central') ) {
			$installation_key = $this->post('installation_key');
//			error_log($installation_key);
			$this->load->model("network_model");
			$network_requests_for_installation = $this->network_model->getNetworkRequestsForInstallation($installation_key);
//			error_log(print_r($network_requests_for_installation, 1));
			if ( ! empty($network_requests_for_installation) ) {
//				error_log('not empty -> ' . print_r($networks, 1));
				$this->response($network_requests_for_installation, 200);
			}
			else {
//				error_log("no networks exist");
				$this->response(array("error" => "No network requests for this installation"));
			}

		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	function get_network_requests_for_networks_this_installation_belongs_to_post() {
		if ( $this->config->item('cafevariome_central') ) {
			$installation_key = $this->post('installation_key');
//			error_log($installation_key);
			$this->load->model("network_model");
			$network_requests_for_installation = $this->network_model->getNetworkRequestsForNetworksThisInstallationBelongsTo($installation_key);
//			error_log(print_r($network_requests_for_installation, 1));
			if ( ! empty($network_requests_for_installation) ) {
//				error_log('not empty -> ' . print_r($networks, 1));
				$this->response($network_requests_for_installation, 200);
			}
			else {
//				error_log("no networks exist");
				$this->response(array("error" => "No network requests for this installation"));
			}

		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	
	function count_number_of_installations_for_network_post() {
		if ( $this->config->item('cafevariome_central') ) {
			$network_key = $this->post('network_key');
			$this->load->model("network_model");
			$installation_count_for_network = $this->network_model->countNumberOfInstallationsForNetwork($network_key);
//			error_log(print_r($installation_count_for_network, 1));
			if ( ! empty($installation_count_for_network) ) {
//				error_log('not empty -> ' . print_r($networks_installation_not_a_member_of, 1));
				$this->response($installation_count_for_network, 200);
			}
		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}

	
	function join_network_post() {
		if ( $this->config->item('cafevariome_central') ) {
			$justification = $this->post('justification');
			$installation_key = $this->post('installation_key');
			$network_key = $this->post('network_key');
			$this->load->model("network_model");
			$network_name = $this->network_model->getNetworkNameFromNetworkKey($network_key);
			$username = $this->post('username');
			$email = $this->post('email');
			$data = array ( 
							'username' => $username,
							'email' => $email,
							'network_name' => $network_name,
							'network_key' => $network_key,
							'justification' => $justification,
							'installation_key' => $installation_key,
							'result' => 'pending'
						);
			$this->load->model("federated_model");
			$network_join_request_id = json_encode($this->federated_model->addNetworkJoinRequest($data));
			if ( $network_join_request_id ) {
				$this->response(array('network_request_id' => $network_join_request_id), 200);
			}
			else {
				$this->response(array("error" => "Unable to make join network request"), 200);
			}
		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	function leave_network_post() {
		if ( $this->config->item('cafevariome_central') ) {
			$installation_key = $this->post('installation_key');
			$network_key = $this->post('network_key');
			$installation_count_for_network = $this->post('installation_count_for_network');
			$this->load->model("network_model");
			$leave_network_success = $this->network_model->leaveNetwork($installation_key, $network_key);

			if ( $installation_count_for_network == 1 ) {
				error_log("last installation");
				$delete_network_success = $this->network_model->deleteNetwork($network_key);
			}
			else {
				error_log("more than one installation");
			}
			
			if ( ! empty($leave_network_success) ) {
//				error_log('not empty -> ' . print_r($networks_installation_not_a_member_of, 1));
				$this->response($leave_network_success, 200);
			}
			else {
//				error_log("no networks exist");
				$this->response(array("error" => "Unable to leave network"));
			}

		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	function send_message_and_email_to_all_administrators_for_a_network_post() {
		$network_key = $this->post('network_key');
		$this->load->model("network_model");
		$base_urls = $this->network_model->getBaseURLsForAllInstallationsInANetwork($network_key);
//		error_log(print_r($base_urls, 1));
		foreach ( $base_urls as $base_url ) {
			error_log($base_url['installation_base_url']);
		}
		
		
		$this->response($base_urls, 200);
			
//		$this->load->model('messages_model');
//		$sender_id = $this->ion_auth->user()->row()->id;
//		$recipients = $this->input->post('message-recipients');
//		$subject = $this->input->post('message-subject');
//		$body = $this->input->post('message-body');
////		error_log("sender_id -> $sender_id | recipients -> $recipients | subject -> $subject | body -> $body");
//		if (preg_match('/\,/', $recipients)) {
//			$recipients = explode(',', $recipients);
//		}
//		$this->messages_model->send_new_message($sender_id, $recipients, $subject, $body);

	}
	
	function is_installation_part_of_network_post() {
		if ( $this->config->item('cafevariome_central') ) {
			$installation_key = $this->post('installation_key');
			$network_key = $this->post('network_key');
			$this->load->model("network_model");
			$is_installation_part_of_network = $this->network_model->isInstallationPartOfNetwork($installation_key, $network_key);
//			error_log("is -> " . $is_installation_part_of_network);
			if ( $is_installation_part_of_network ) {
				$this->response(array('is_installation_part_of_network' => true), 200);
			}
			else {
				$this->response(array("is_installation_part_of_network" => false), 200);
			}
		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	function add_installation_post() {
//		if ( $this->config->item('cafevariome_central') ) {
//			$ip = $_SERVER['REMOTE_ADDR'];
			$installation_key = $this->post('installation_key');
			$installation_name = $this->post('installation_name');
			$data = array ( 
							'network_name' => $network_name,
							'network_key' => $network_key,
							'network_type' => 'federated'
						);
			$this->load->model("federated_model");
			$insert_id = $this->federated_model->addInstallation(array("installation_key" => $installation_key, "installation_name" => $installation_name));
			if ( $insert_id ) {
				$data = array('insert_id' => $insert_id);
				$this->response($data, 200);
			}
			else {
				$this->response(array("error" => "Unable to add installation"));
			}
//		}
//		else {
//			$this->response(array("This is not Cafe Variome Central"));
//		}
	}
	
	
	function checkprefix_post() {
		if ( $this->config->item('cafevariome_central') ) {
//			$ip = $_SERVER['REMOTE_ADDR'];
			$this->load->model("general_model");
			$check = $this->general_model->checkPrefix($this->post('prefix'));
//			error_log("check -> " . $check);
			if ( $check ) {
				$data = array('is_unique' => 'no');
			}
			else {
//				$this->response(NULL, 409); // 409 is a conflict error
				$data = array('is_unique' => 'yes');
			}
			$this->response($data, 200);
		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	function insertprefix_post() {
		if ( $this->config->item('cafevariome_central') ) {
//			$ip = $_SERVER['REMOTE_ADDR'];
			$this->load->model("general_model");
			$insert_id = $this->general_model->insertPrefix($this->post('prefix'), $this->post('ip'));
		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	function getkey_get() {
		if ( $this->config->item('cafevariome_central') ) {
			$bioportalkey = $this->config->item('bioportalkey');
			$data = array('key' => $bioportalkey);
			$this->response($data);
		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	function newinstall_post() { // During install users can select whether CV Central is informed about new install, if set to yes then it uses this api function to send data to CV Central
		if ( $this->config->item('cafevariome_central') ) {
			$base_url = $this->post('base_url');
			$host = $this->post('host');
			$ip = $this->post('ip');
			$external_ip = $this->post('external_ip');
			$admin_email = $this->post('admin_email');
			$body = "Base_url: $base_url\nHost: $host\nIP: $ip\nExternal IP: $external_ip\nAdmin Email: $admin_email";
			cafevariomeEmail("admin@cafevariome.org", "admin@cafevariome.org", "admin@cafevariome.org", "New Cafe Variome Install", $body);
//			error_log("base_url -> " . $this->post('base_url'));
		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
	function blastdbcmd_post() {
		$chr = $this->post('chr');
		$start = $this->post('start');
		$end = $this->post('end');
//		error_log("test -> $chr $start $end");
		exec("/usr/local/ncbi/blast/bin/blastdbcmd -db /Users/owen/cafevariome/data/hg19/hg19 -dbtype nucl -entry '$chr' -range $start-$end", $data);
		$this->response($data);
	}

	function omim_get() {
		if ( $this->config->item('cafevariome_central') ) {
			$gene = $this->get('gene');
			$this->load->model("general_model");
			$omim_data = $this->general_model->getOMIMFromGene($gene);
			$this->response($omim_data);
		}
		else {
			$this->response(array("This is not Cafe Variome Central"));
		}
	}
	
}

?>
