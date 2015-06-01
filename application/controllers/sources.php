<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sources extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	}
	
	public function index() {
//		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth', 'refresh');
//		}

		$this->load->model('sources_model');
		$this->data['variant_counts'] = $this->sources_model->countSourceEntries();
		$sources = $this->sources_model->getSourcesFull();
//		print_r($sources, 1);
		$source_groups = array();
		$source_ids_array = array();
		$source_network_groups = array();
		foreach ($sources->result() as $source) {
//			echo $source->source_id;
//			// TODO: Instead of making multiple API calls to central server then instead get the source IDs into array and pass that way
			// Get all the network groups that this source from this installation is currently in
			$returned_groups = authPostRequest('', array('source_id' => $source->source_id, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_current_network_groups_for_source_in_installation");
			$tmp_selected_groups = json_decode($returned_groups, TRUE);
			if ( !array_key_exists('error', $tmp_selected_groups) ) {
//				print_r($tmp_selected_groups);
				$this->data['source_network_groups'][$source->source_id] = $tmp_selected_groups;
			}
			$source_ids_array[] = $source->source_id;
			$source_group_data = $this->sources_model->getSourceGroups($source->source_id);
//			print_r($source_group_data);
//			print "group data -> " . $source_group_data['group_id'] . "<br />";
			if(! empty($source_group_data)) {
//				$source_groups[$source->source_id] = array( 'group_id' => $source_group_data['group_id'], 'group_description' => $source_group_data['group_description'] );
				$source_groups[$source->source_id] = $source_group_data;	
			}
		}
//			$group_post_data = implode("|", $group_data_array);
//				error_log("group data string to send -> $group_post_data");
		
		// Get all the available groups
//		$this->data['groups'] = $this->ion_auth->getGroups();
		
		$this->data['source_groups'] = $source_groups;
		$this->data['sources'] = $sources;
		$this->_render('sources/sources');
	}
	
	function delete_source($source_id = NULL, $source = NULL) {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$this->load->model('sources_model');
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('source', 'Source Name', 'required|alpha_dash');

		if ($this->form_validation->run() == FALSE) {
			// insert csrf check
			$this->data['source_id'] = $source_id;
			$this->data['source'] = $source;
			$this->_render('sources/delete_source');
		}
		else {
			// do we really want to delete?
			if ($this->input->post('confirm') == 'yes') {
				// do we have a valid request?
				if ($source != $this->input->post('source')) {
					show_error('This form post did not pass our security checks.');
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
					$this->sources_model->deleteSource($source_id);
					if ( $this->input->post('variants') == 'yes' ) { // also delete variants for the source
						$is_deleted = $this->sources_model->deleteVariants($source);
					}
				}
			}
			//redirect them back to the auth page
			redirect('sources', 'refresh');
		}
	}
	
	public function edit_source($source_id = NULL) {
//		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth', 'refresh');
//		}
//		if ( ! isset($source_id)) {
//			print "You must specify a source id to edit";
//			show_404();
//		}
		
		$this->data['source_id'] = $source_id;
		$this->data['title'] = "Edit Source";
		$this->load->model('sources_model');

		//validate form input
		$this->form_validation->set_rules('name', 'Source Name', 'required|xss_clean|alpha_dash');
		$this->form_validation->set_rules('uri', 'Source URI', 'required|xss_clean');
		$this->form_validation->set_rules('desc', 'Source Description', 'required|xss_clean');
		$this->form_validation->set_rules('long_description', 'Long Source Description', 'xss_clean');
		$this->form_validation->set_rules('email', 'Owner Email', 'valid_email|required|xss_clean');
		$this->form_validation->set_rules('type', 'Source Type', 'xss_clean');
		$this->form_validation->set_rules('status', 'Source Status', 'required|xss_clean');

		if ($this->form_validation->run() == true) {
			//check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			$update_data['source_id'] = $this->input->post('source_id');
			$update_data['name'] = $this->input->post('name');
			$update_data['email'] = $this->input->post('email');
			$update_data['uri'] = $this->input->post('uri');
			$update_data['description'] = $this->input->post('desc');
			$update_data['long_description'] = $this->input->post('long_description');
			$update_data['type'] = $this->input->post('type');
			$update_data['status'] = $this->input->post('status');
			$this->sources_model->updateSource($update_data);
			
			// Check if there any groups selected
			if ($this->input->post('groups')) {
				$group_data_array = array();
				foreach ($this->input->post('groups') as $group_data) {
					// Need to explode the group multi select to get the group_id and the network_key since the value is comma separated as I needed to pass both in the value
					$group_data_array[] = $group_data;
				}
				// Create the post string that will get sent
				// Each group will be a comma separated variable (first the group ID and then the network_key)
				// if multiple groups are selected then they'll be delimited by a | which will be exploded auth server side
				$group_post_data = implode("|", $group_data_array);
//				error_log("group data string to send -> $group_post_data");
				// Make API to auth central for the source for this installation for the network groups
				$groups = authPostRequest('testedtoken', array('group_post_data' => $group_post_data, 'source_id' => $this->input->post('source_id'), 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/modify_current_network_groups_for_source_in_installation");
					

				
			}
			else {
//				error_log("no groups selected");
				$groups = authPostRequest('testedtoken', array('group_post_data' => 'null', 'source_id' => $this->input->post('source_id'), 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/modify_current_network_groups_for_source_in_installation");
				// All groups were de-selected so remove this source from all groups - do this by passing NULL to ion_auth remove_sources_from_group function
//				$this->sources_model->remove_sources_from_group(NULL, $this->input->post('source_id'));
			}
			
			// Get the curators selected
//			if ($this->input->post('curators')) {
//				// Get all the curators for this source
//				$current_curators = $this->sources_model->getSourceCurators($this->input->post('source_id'));
//				$curators_in = array();
//				foreach ( $current_curators as $user_id => $source_id ) {
//					$curators_in[] = $user_id;
//				}
//				// Find which current curators have been deselected and therefore need to be removed from this source
//				$diff = array_diff($curators_in, $this->input->post('curators'));
////				error_log("diff -> " . print_r($diff, 1));
//				if ( ! empty($diff) ) {
//					foreach ( $diff as $delete_user_id ) {
////						error_log("delete $delete_user_id");
//						$this->sources_model->deleteCuratorFromSource($delete_user_id, $this->input->post('source_id'));
////						$this->sources_model->remove_sources_from_group($delete_group_id, $this->input->post('source_id'));
//					}
//				}
////				error_log("curators current -> " . print_r($current_curators, 1));
////				error_log("curators post -> " . print_r($this->input->post('curators'), 1));
////				$this->sources_model->deleteSourceCurators($this->input->post('source_id'));
//				foreach ($this->input->post('curators') as $user_id) {
//					if ( ! array_key_exists($user_id, $current_curators)) {
//						$curator_data = array("user_id" => $user_id, "source_id" => $this->input->post('source_id'));
//						$insert_id = $this->sources_model->insertSourceCurator($curator_data);
//						if ( $insert_id ) {
////							error_log("inserted curator_id -> " . $insert_id);
//						}
//					}
//				}
//			}
//			else { // No curators selected, delete all for this source
//				$this->sources_model->deleteSourceCurators($this->input->post('source_id'));
//			}
			
//			echo "---> $name $uri $description $type<br />";
			redirect("sources", 'refresh');
		}
		else {
			// Get all the users in this installation for the curator select list
//			$this->data['users'] = $this->ion_auth->users()->result();
//			// Get the current curators for this source
//			$selected_curators = $this->sources_model->getSourceCurators($source_id);
//			$this->data['selected_curators'] = $selected_curators;

			// Get all available groups for the networks this installation is a member of from auth central for multi select list
			$groups = authPostRequest('', array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_network_groups_for_installation");
//			print_r($groups);
			$this->data['groups'] = json_decode($groups, TRUE);

			// Get all the network groups that this source from this installation is currently in so that these can be pre selected in the multiselect list
			$returned_groups = authPostRequest('', array('source_id' => $source_id, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_current_network_groups_for_source_in_installation");
			$tmp_selected_groups = json_decode($returned_groups, TRUE);
			$selected_groups = array();
			if (! array_key_exists('error', $tmp_selected_groups)) {
				foreach ( $tmp_selected_groups as $tmp_group ) {
					$selected_groups[$tmp_group['group_id']] = "group_description";
				}
			}
			$this->data['selected_groups'] = $selected_groups;
			
			// Get all the data for this source
			$source_data = $this->sources_model->getSourceSingleFull($source_id);
			$this->data['source_data'] = $source_data;
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['name'] = array(
				'name'  => 'name',
				'id'    => 'name',
				'type'  => 'text',
				'style' => 'width:70%',
				'readonly'=>'true', // Don't allow the user to edit the source name
				'value' => $this->form_validation->set_value('name', $source_data['name']),
			);
			$this->data['uri'] = array(
				'name'  => 'uri',
				'id'    => 'uri',
				'type'  => 'text',
				'style' => 'width:70%',
				'value' => $this->form_validation->set_value('uri', $source_data['uri']),
			);
			$this->data['desc'] = array(
				'name'  => 'desc',
				'id'    => 'desc',
				'type'  => 'text',
				'style' => 'width:70%',
				'value' => $this->form_validation->set_value('desc', $source_data['description']),
			);
			$this->data['long_description'] = array(
				'name'  => 'long_description',
				'id'    => 'long_description',
				'type'  => 'text',
				'style' => 'width:70%',
				'rows'  => '5',
				'cols'  => '3',
				'value' => $this->form_validation->set_value('long_description', $source_data['long_description']),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'style' => 'width:70%',
				'value' => $this->form_validation->set_value('email', $source_data['email']),
			);
			$this->data['status'] = array(
				'name'  => 'status',
				'id'    => 'status',
				'type'  => 'select',
				'value' => $this->form_validation->set_value('status'),
			);
			$this->data['type'] = array(
				'name'  => 'type',
				'id'    => 'type',
				'type'  => 'dropdown',
				'value' => $this->form_validation->set_value('type', $source_data['type']),
			);

			$this->_render('sources/edit_source');

		}
	}
	
	function add_source() {
		
		$this->data['title'] = "Add Source";

//		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth', 'refresh');
//		}

		//validate form input
		
		$this->form_validation->set_rules('name', 'Source Name', 'required|xss_clean|alpha_dash|callback_uniquename_check');
		$this->form_validation->set_rules('owner_name', 'Owner Name', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Owner Email', 'valid_email|required|xss_clean');
		$this->form_validation->set_rules('uri', 'Source URI', 'required|xss_clean');
		$this->form_validation->set_rules('desc', 'Source Description', 'required|xss_clean');
		$this->form_validation->set_rules('long_description', 'Long Source Description', 'xss_clean');
		$this->form_validation->set_rules('status', 'Source Status', 'required|xss_clean');
//		$this->form_validation->set_rules('type', 'Source Type', 'required|xss_clean');

		// Get all available groups for the networks this installation is a member of
		$groups = authPostRequest('', array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_network_groups_for_installation");
//		print_r($groups);
		$this->data['groups'] = json_decode($groups, TRUE);
		
//		$this->data['groups'] = $this->ion_auth->getGroups();
		// Get all the users in this installation for the curator select list
//		$this->data['users'] = $this->ion_auth->users()->result();
		if ($this->form_validation->run() == FALSE) {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['name'] = array(
				'name'  => 'name',
				'id'    => 'name',
				'type'  => 'text',
				'style' => 'width:50%',
				'value' => $this->form_validation->set_value('name'),
			);
			$this->data['owner_name'] = array(
				'name'  => 'owner_name',
				'id'    => 'owner_name',
				'type'  => 'text',
				'style' => 'width:50%',
				'value' => $this->form_validation->set_value('owner_name'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'style' => 'width:50%',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['uri'] = array(
				'name'  => 'uri',
				'id'    => 'uri',
				'type'  => 'text',
				'style' => 'width:50%',
				'value' => $this->form_validation->set_value('uri'),
			);
			$this->data['desc'] = array(
				'name'  => 'desc',
				'id'    => 'desc',
				'type'  => 'text',
				'style' => 'width:50%',
				'value' => $this->form_validation->set_value('desc'),
			);
			
			$this->data['long_description'] = array(
				'name'  => 'long_description',
				'id'    => 'long_description',
				'type'  => 'text',
				'rows'  => '5',
				'cols'  => '3',
				'style' => 'width:50%',
				'value' => $this->form_validation->set_value('long_description'),
			);
			
			$this->data['status'] = array(
				'name'  => 'status',
				'id'    => 'status',
				'type'  => 'select',
				'value' => $this->form_validation->set_value('status'),
			);
			
			$this->data['type'] = array(
				'name'  => 'type',
				'id'    => 'type',
				'type'  => 'select',
				'value' => $this->form_validation->set_value('type'),
			);
			$this->_render('sources/add_source');

		}
		else {
			$name = $this->input->post('name'); // Convert the source name to lowercase
			$uri = $this->input->post('uri');
			$owner_name = $this->input->post('owner_name');
			$email = $this->input->post('email');
			$description = $this->input->post('desc');
			$long_description = $this->input->post('long_description');
			$status = $this->input->post('status');
			$type = $this->input->post('type');
			$this->load->model('sources_model');
			
			$source_data = array ( "name" => $name, "owner_name" => $owner_name, "email" => $email, "uri" => $uri, "description" => $description, "long_description" => $long_description, "type" => "mysql", "status" => $status);
			$insert_id = $this->sources_model->insertSource($source_data);
			$this->data['insert_id'] = $insert_id;
			
			if ($this->input->post('groups')) {
				// Add the groups that were selected to this source
				foreach ($this->input->post('groups') as $group_data) {
					// Need to explode the group multi select to get the group_id and the network_key since the value is comma separated as I needed to pass both in the value
					$groups_exploded = explode(',', $group_data);
					$group_id = $groups_exploded[0];
					$network_key = $groups_exploded[1];
					// Add the new source for this installation to the network group via central auth API call
					$groups = authPostRequest('', array('group_id' => $group_id, 'source_id' => $insert_id, 'installation_key' => $this->config->item('installation_key'), 'network_key' => $network_key), $this->config->item('auth_server') . "/api/auth/add_source_from_installation_to_network_group");
					
//					$this->sources_model->add_to_sources_group($group_id, $insert_id); // Add the groups to this source using the source ID that has been created
//					error_log("add -> " . $group_id . " insert -> " .  $insert_id );
				}
			}
			
//			if ($this->input->post('curators')) {
//				foreach ($this->input->post('curators') as $user_id) {
//					$curator_data = array("user_id" => $user_id, "source_id" => $insert_id);
//					$insert_id = $this->sources_model->insertSourceCurator($curator_data);
//					if ( $insert_id ) {
////						error_log("inserted curator_id -> " . $insert_id);
//					}
//				}
//			}
			redirect("sources", 'refresh');
		}
	}

	function clone_source() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		if ($this->input->post('clone_source') && $this->input->post('clone_name') && $this->input->post('clone_description')) {
			$clone_source = $this->input->post('clone_source');
			$clone_name = $this->input->post('clone_name');
			$clone_description = $this->input->post('clone_description');
//			error_log("$clone_source $clone_name $clone_description");
			$this->load->model('sources_model');
			$source_original = $this->sources_model->getSource($clone_source);
			$source_data = array ( "name" => $clone_name, "owner_name" => $source_original['owner_name'], "email" => $source_original['email'], "uri" => $source_original['uri'], "description" => $clone_description, "long_description" => "", "type" => "mysql", "status" => "online");
			$insert_id = $this->sources_model->insertSource($source_data);
			if ( $insert_id ) {
				$this->load->model('general_model');
				
				// Using current variant table structure get the fields and swap out the original source name for the cloned source
				// End up with 2 strings comma separated that are then used in the cloneSource select and insert query to pull all the variant data 
				// from the source and then re-insert it using the new cloned source name
				$table_structure = $this->general_model->describeTable("variants");
				unset($table_structure['cafevariome_id']); // Do not want to clone the cafevariome_id so remove from array, want to create a new id for the variant instead
				$simple_table_structure = array();
				$simple_table_structure_replace = array();
				foreach ( $table_structure as $field => $value ) {
//					error_log("field -> " . $field);
					if ( $field == "source" ) {
						$simple_table_structure_replace[] = "'$clone_name'";
					}
					elseif ( $field == "laboratory" ) {
						$simple_table_structure_replace[] = "'$clone_name'";
					}
					else {
						$simple_table_structure_replace[] = $field;
					}
					$simple_table_structure[] = $field;
				}
				$fields = implode(",", $simple_table_structure);
				$fields_replace = implode(",", $simple_table_structure_replace);
//				error_log("fields -> " . $fields . " -----> " . $fields_replace);
				
				$clone_result = $this->sources_model->cloneSource($clone_source, $clone_name, $fields, $fields_replace);
				if ( $clone_result ) {
					echo "$clone_source was successfully cloned to $clone_name";
					$this->load->model('messages_model');
					$user_id = $this->ion_auth->user()->row()->id;
					$subject = "Source Successfully Cloned";
					$body = "$clone_source was successfully cloned to $clone_name";
					$this->messages_model->send_new_message($user_id, $user_id, $subject, $body);
				}
				else {
					echo "Cloning of $clone_source to $clone_name failed";
				}
				
			}
			else {
				echo "Cloning of $clone_source to $clone_name failed";
			}

		}
		else {
			echo "All fields are required";
		}
	}
		
	function add_federated_source() {

		$this->data['title'] = "Add Federated Source";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}		
		$this->load->model('federated_model');
		$node_list = $this->federated_model->getNodeList(); // Fetch the node list
		$this->data['node_list'] = $node_list;
		$node_source_list = array();
		foreach ( $node_list as $node ) {
			$source_uri = $node['node_uri'] . "/discover/sources/json";
			$sources = json_decode(file_get_contents($source_uri));
			$node_source_list[$node['node_name']] = $sources;
			
		}
		$this->data['node_source_list'] = $node_source_list;
		
		// Get the current list of federated sources
		$federated_sources = $this->federated_model->getFederatedSources();
		$this->data['federated_sources'] = $federated_sources;
		
		$this->_render('sources/add_federated_source');

	}

	function add_federated_source_to_db() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		if ($this->input->post('node_name') && $this->input->post('status')) {
			$node_name = $this->input->post('node_name');
			$status = $this->input->post('status');
			$source_name = $this->input->post('source_name') . "_" . $node_name;
			$source_description = $this->input->post('source_description');
//			error_log("source_name -> " . $source_name);
			$this->load->model('federated_model');
			$node_uri = $this->federated_model->getNodeURIFromNodeName($node_name);
			$this->load->model('sources_model');
			$source_data = array ( "name" => $source_name, "uri" => $node_uri, "description" => $source_description . " ($node_name)", "type" => "api", "status" => $status);
//			error_log(print_r($source_data, 1));
			if ( $status == "online" ) {
				$insert_id = $this->sources_model->insertSource($source_data);
				if ( $insert_id ) {
//					error_log("inserted " . $insert_id);
				}
                else {
                    error_log("couldn't insert");
                }
			}
			elseif ( $status == "offline") {
				$this->sources_model->deleteSourceByName($source_name);
			}
		
		}
		else {
			
		}
	}
	
	function add_central_source() {

		$this->data['title'] = "Add Cafe Variome Central Source";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}		
		$json_url = "http://www.cafevariome.org/discover/sources/json";
		$json = file_get_contents($json_url);
		$source_data = json_decode($json, TRUE);
		$this->data['sources'] = $source_data;
		$this->load->model('sources_model');
//		$node_list = $this->federated_model->getNodeList(); // Fetch the node list
//		$this->data['node_list'] = $node_list;
//		$node_source_list = array();
//		foreach ( $node_list as $node ) {
//			$source_uri = $node['node_uri'] . "/discover/sources/json";
//			$sources = json_decode(file_get_contents($source_uri));
//			$node_source_list[$node['node_name']] = $sources;
//			
//		}
//		$this->data['node_source_list'] = $node_source_list;
//		
		// Get the current list of central sources
		$central_sources = $this->sources_model->getCentralSources();
		$this->data['central_sources'] = $central_sources;
//		
		$this->_render('sources/add_central_source');

	}
	
	function add_central_source_to_db() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		if ($this->input->post('source_name') && $this->input->post('status')) {
			$central_source_name = $this->input->post('central_source_name');
			$status = $this->input->post('status');
			$source_name = $this->input->post('source_name') . "_central";
			$source_description = $this->input->post('source_description');
//			error_log("source_name -> " . $source_name);
			$uri = "http://www.cafevariome.org/discover/source/" . $this->input->post('source_name');
			$this->load->model('sources_model');
			$source_data = array ( "name" => $source_name, "uri" => $uri, "description" => $source_description . " (CV Central)", "type" => "central", "status" => $status);
			error_log(print_r($source_data, 1));
			if ( $status == "online" ) {
				$insert_id = $this->sources_model->insertSource($source_data);
				if ( $insert_id ) {
//					error_log("inserted " . $insert_id);
				}
                else {
                    error_log("couldn't insert");
                }
			}
			elseif ( $status == "offline") {
				$this->sources_model->deleteSourceByName($source_name);
			}
		
		}
		else {
			
		}
	}

	function invite_to_share_source() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		if ($this->input->post('email') && $this->input->post('selected_group')) {
			$this->load->model('sources_model');
			$email = $this->input->post('email');
			$is_email_present = $this->sources_model->is_email_present($email);
			// TODO: Check it's not an email address that is already registered **** If already registered give link to users page and say they can add them 
			if ( $is_email_present ) {
				echo "The email specified already has a Cafe Variome account, this functionality is for inviting users who do not yet have a Cafe Variome account. In order to share data with an existing user you should add the user to the source group via the admin interface.";
			}
			else {
				$selected_group = $this->input->post('selected_group');
				$md5 = generateMD5();
//				error_log("-> $email $selected_group $md5");
				$result = $this->sources_model->register_and_add_to_group($md5, $email, $selected_group);
				if ( $result ) {
					$group = $this->ion_auth->group($selected_group)->row();
					$group_name = $group->name;
					$from_name = "Cafe Variome Admin";
					$subject = "Invitation to share";
					$invite_link = $this->config->item('base_url') . "admin/share_request/$md5";
					$message = "Hello\r\n\r\n<br /><br />You have been invited to join the $group_name group on Cafe Variome, this will enable you to access all restrictedAccess variants that belong to this group.\r\n\r\n<br /><br />You can accept or refuse this sharing invite using the <a href='$invite_link' >following link</a>.\r\n\r\n<br /><br />If you accept then you will need to fill in some basic details in order to create your account.\r\n\r\n<br /><br />Best Regards\r\n\r\n<br /><br />Cafe Variome<br />";
					cafevariomeEmail($this->config->item('email'), $from_name, $email, $subject, $message);
					echo "Invite email was successfully sent.";
					$this->load->model('messages_model');
					// TODO: Get group name from the ID and use this instead of the ID

//					error_log("----> group -> " . print_r($group, 1));
					$user_id = $this->ion_auth->user()->row()->id;
					$subject = "Sharing invite sent";
					$body = "A sharing invite was sent to $email inviting them to signup to Cafe Variome and become a member of the $group_name group. Once they have registered they will be able to access all restrictedAccess variants for sources that belong to the $group_name group. If this was a mistake you should delete the invited user via the user section of the admin interface.";
					$this->messages_model->send_new_message($user_id, $user_id, $subject, $body);
//					redirect('sources/sources', 'refresh');
				}
				else {
					echo "There was a problem inviting the user to share this source";
				}
				// TODO: Page directed to in the email link should force them to add all details and so they can't change email, after submitting automatically log them in
			}
		}
	}
	
	function share_request($md5) {
//		echo "share -> $md5<br />";
		$this->load->model('sources_model');
		$query = $this->sources_model->is_md5_valid($md5);
		$is_valid = $query->num_rows();
//		error_log("valid -> " . $is_valid);
		if ( $is_valid ) {
//			error_log("valid!");
//			print_r($query);
			$user = (array) $query->row();
//			print_r($user);
			$user_group = $this->ion_auth->get_users_groups($user['id'])->result();
			$this->data['user_group'] = $user_group;
			$this->data['user'] = $user;
			$this->data['md5'] = $md5;
//			print_r($user_group);
			// Lookup what group has been shared
			// Present user with a confirm/deny you want to be able to share the group data
			// E.g. you have been invited to share restrictedAccess variants that belong to the X group, please confirm or deny this request
			// If they confirm then go to registration page and when register activate account log them in and email and message the group owner to say it was confirmed
			// If deny then email the group owner and say they refuse and then delete the entry in users table
			$this->_render('sources/share_request');
		}
		else {
			show_error("The md5 token is not valid or this sharing invite has already been processed.");
		}
	}
	
	function share_result() {
//		print_r($_POST);
		$this->load->model('sources_model');
		$md5 = $this->input->post('md5');
		$query = $this->sources_model->is_md5_valid($md5);
		$is_valid = $query->num_rows();
		if ( ! $is_valid ) {
			show_error("The md5 token is not valid or this sharing invite has already been processed.");
		}
//		$user = (array) $query->row();
		$user = $query->row();
		$id = $user->id;

		if ( $this->input->post('result') == "confirm" ) {
//			echo "confirm";
			$this->data['md5'] = $md5;
			$this->data['result'] = "confirm";
//			print_r($user);
			//validate form input
			$this->form_validation->set_rules('username', 'Username', 'required|xss_clean|alpha_dash');
			$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
			$this->form_validation->set_rules('email', 'Email Address', 'valid_email');
			$this->form_validation->set_rules('company', 'Institute Name', 'required|xss_clean');
			$this->form_validation->set_rules('orcid', 'ORCID', 'xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');


			if (isset($_POST) && !empty($_POST)) {
				// do we have a valid request?
//				if ($id != $this->input->post('id'))
//				{
//					show_error('This form post did not pass our security checks.');
//				}

				$data = array(
					'username' => strtolower($this->input->post('username')),
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'email'      => $this->input->post('email'),
					'company'    => $this->input->post('company'),
					'orcid'		 => $this->input->post('orcid')
				);

				//update the password if it was posted
				if ($this->input->post('password'))
				{
					$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
					$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');	

					$data['password'] = $this->input->post('password');
				}
			
				if ($this->form_validation->run() === TRUE)
				{
					$this->ion_auth->update($user->id, $data);
					$this->session->set_flashdata('message', "User Saved");
					$activation = $this->ion_auth->activate($user->id);
					$user = $this->ion_auth->user($user->id)->row();
					$session_data = array(
					    'identity'             => 'email',
					    'username'             => $user->username,
					    'email'                => $user->email,
					    'user_id'              => $user->id, //everyone likes to overwrite id so we'll use user_id
					    'old_last_login'       => $user->last_login
					);
					$this->session->set_userdata($session_data);
					redirect("auth/user_profile/" . $id, 'refresh');
					// TODO: Activate the account and log the user in then redirect to profile
				}
			}
		
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			//pass the user to the view
			$this->data['user'] = $user;	

			$this->data['username'] = array(
				'name' => 'username',
				'id' => 'username',
				'type' => 'text',
				'value' => $this->form_validation->set_value('username', $user->username)
			);
			$this->data['first_name'] = array(
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name', $user->first_name),
			);
			$this->data['last_name'] = array(
				'name'  => 'last_name',
				'id'    => 'last_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('last_name', $user->last_name),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email', $user->email),
				'disabled'=>'true'
			);
			$this->data['company'] = array(
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company', $user->company),
			);
			$this->data['password'] = array(
				'name' => 'password',
				'id'   => 'password',
				'type' => 'password'
			);
			$this->data['password_confirm'] = array(
				'name' => 'password_confirm',
				'id'   => 'password_confirm',
				'type' => 'password'
			);
			$this->data['orcid'] = array(
				'name' => 'orcid',
				'id' => 'orcid',
				'type' => 'text',
				'value' => $this->form_validation->set_value('orcid', $user->orcid),
			);
			$this->_render('auth/share_invite_edit_profile');
		}
		elseif ( $this->input->post('result') == "refuse" ) {
//			echo "refuse";
			$this->ion_auth->delete_user($id); // Delete the user and remove from the group they were added to
			$this->_render('auth/share_invite_refuse');
			// TODO: redirect to a view that informs that refusal was successful and if they want to register they'll have to contact the admin again or create an account
		}
	}
	
	function change_source_status() {
		$this->load->model('sources_model');
		if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
			$user_id = $this->ion_auth->user()->row()->id;
			$source_id = $this->input->post('source_id');
			$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
			if ( ! $can_curate_source ) {
				show_error("Sorry, you are not listed as a curator for that particular source.");
			}
		}
		elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		if ($this->input->post('status') && $this->input->post('source_id')) {
			$status = $this->input->post('status');
			$source_id = $this->input->post('source_id');
			$this->sources_model->updateSourceStatus($source_id, $status);
		}
	}

	public function uniquename_check($name = NULL) {
		$this->load->model('sources_model');
		$returned_name = $this->sources_model->checkSourceExists($name);
		if( ! $returned_name) {
//			error_log("true");
			return TRUE;
		}
		else {
			$this->form_validation->set_message('uniquename_check', 'The %s field must be unique (there is already a source with that name)');
//			error_log("false");
			return FALSE;
		}
	}

}
