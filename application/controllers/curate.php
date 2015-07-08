<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Curate extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	}
	
	public function index() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group("curator")) {
			redirect('auth', 'refresh');
		}
		$this->data['user_id'] = $user_id = $this->ion_auth->user()->row()->id;
		$this->data['title'] = "Curator Dashboard";
		$this->_render('curate/dashboard');
	}
	

	function sources() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group("curator")) {
			redirect('auth', 'refresh');
		}
		
		// Get the id of the current user and fetch the sources that they are a curator for
		$user_id = $this->ion_auth->user()->row()->id;
		$this->load->model('sources_model');
//		error_log("user email -> " . $user_email);
		$curateable_sources = $this->sources_model->getSourcesThatTheUserCanCurate($user_id);
		$this->data['sources'] = $curateable_sources;
		
		// Get the full source details for each source
		foreach ($curateable_sources->result() as $source) {
			$source_group_data = $this->sources_model->getSourceGroups($source->source_id);
			if(! empty($source_group_data)) {
				$source_groups[$source->source_id] = $source_group_data;	
			}
		}
		if ( ! empty($source_groups) ) { 
			$this->data['source_groups'] = $source_groups;
		}
		$this->data['record_counts'] = $this->sources_model->countSourceEntries();
//		$this->data['sources'] = $sources;
		$this->_render('curate/sources');
	}
	
	public function edit_source($source_id = NULL) {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group("curator")) {
			redirect('auth', 'refresh');
		}
		$this->load->model('sources_model');

		$this->data['source_id'] = $source_id;
		$this->data['title'] = "Edit Source";
		

		//validate form input
		$this->form_validation->set_rules('name', 'Source Name', 'required|xss_clean|alpha_dash');
		$this->form_validation->set_rules('uri', 'Source URI', 'required|xss_clean');
		$this->form_validation->set_rules('desc', 'Source Description', 'required|xss_clean');
		$this->form_validation->set_rules('long_description', 'Long Source Description', 'xss_clean');
		$this->form_validation->set_rules('status', 'Source Status', 'required|xss_clean');

		if ($this->form_validation->run() == true) {
			// Check if this curator is actually set as a curator for this source
			$user_id = $this->ion_auth->user()->row()->id;
			$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($this->input->post('source_id'), $user_id);
			if ( ! $can_curate_source ) {
				show_error("Sorry, you are not listed as a curator for that particular source.");
			}
			//check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			$update_data['source_id'] = $this->input->post('source_id');
			$update_data['name'] = $this->input->post('name');
			$update_data['uri'] = $this->input->post('uri');
			$update_data['description'] = $this->input->post('desc');
			$update_data['long_description'] = $this->input->post('long_description');
			$update_data['status'] = $this->input->post('status');
			$this->sources_model->updateSource($update_data);
			
			// Check if there any groups selected
			if ($this->input->post('groups')) {
				// Get all the groups that this source is currently in
				$current_user_groups = $this->sources_model->getSourceGroups($this->input->post('source_id'));
				$groups_in = array();
				foreach ( $current_user_groups as $group_id => $group_data ) {
					$groups_in[] = $group_data['group_id'];
//					error_log($group_data['group_id']);
				}
				
				// Find which current groups have been deselected and therefore need to be removed from this source
				$diff = array_diff($groups_in, $this->input->post('groups'));
//				print_r($diff);
				if ( ! empty($diff) ) {
					foreach ( $diff as $delete_group_id ) {
						$this->sources_model->remove_sources_from_group($delete_group_id, $this->input->post('source_id'));
					}
				}

				// Find which groups need to be added - go through the selected groups to see if they are not in the sources currently assigned groups
				foreach ($this->input->post('groups') as $group_id) {
					if (! in_array($group_id, $groups_in)) {
						$this->sources_model->add_to_sources_group($group_id, $this->input->post('source_id'));
					}
				}
	
			}
			else {
				// All groups were de-selected so remove this source from all groups - do this by passing NULL to ion_auth remove_sources_from_group function
				$this->sources_model->remove_sources_from_group(NULL, $this->input->post('source_id'));
			}
			
			
//			echo "---> $name $uri $description $type<br />";
			redirect("curate/sources", 'refresh');
		}
		else {
			// Check if this curator is actually set as a curator for this source
			$user_id = $this->ion_auth->user()->row()->id;

			$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);

			if ( ! $can_curate_source ) {
				show_error("Sorry, you are not listed as a curator for that particular source.");
			}
			// Get all the available groups for the multiselect list
			$this->data['groups'] = $this->ion_auth->getGroups();
			// Get the groups that this source belongs to so that these can be pre selected in the multiselect list
			$selected_groups = $this->sources_model->getSourceGroups($source_id);
			$this->data['selected_groups'] = $selected_groups;
			// Get all the data for this source
			$source_data = $this->sources_model->getSourceSingleFull($source_id);
			$this->data['source_data'] = $source_data;
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['name'] = array(
				'name'     => 'name',
				'id'       => 'name',
				'type'     => 'text',
				'style'    => 'width:70%',
				'readonly' =>'true',
				'value'    => $this->form_validation->set_value('name', $source_data['name']),
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
			$this->data['status'] = array(
				'name'  => 'status',
				'id'    => 'status',
				'type'  => 'select',
				'value' => $this->form_validation->set_value('status'),
			);

			$this->_render('curate/edit_source');

		}
	}
	
	//view user profile (for non-admin user)
	function data_requests($user_id = NULL) {
		$this->title = "Curate Data Requests";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group("curator")) {
			redirect('auth', 'refresh');
		}

		// Get the id of the current user and fetch the sources that they are a curator for
		$user_id = $this->ion_auth->user()->row()->id;
		$this->load->model('sources_model');
		$query = $this->sources_model->getSourcesThatTheUserCanCurate($user_id);

//		$curateable_sources = array();
		$data_requests = array();
		$source_groups = array();
		foreach ($query->result() as $source) {
//			error_log("source -> " . print_r($source, 1));
//			$curateable_sources[$source->name] = $source->description;
			$data_requests[$source->name] = $this->sources_model->getDataRequestsForSource($source->name);
			$source_groups[$source->name] = $this->sources_model->getSourceGroups($source->source_id);
			
		}
		$this->data['source_groups'] = $source_groups;
		$this->data['data_requests'] = $data_requests;

		// Fetch the groups that they belong
//		$user_groups = array();
//		foreach ($this->ion_auth->get_users_groups($user_id)->result() as $group) {
////			echo "groupid -> " . $group->id . " groupname -> " . $group->name . " description -> " . $group->description . "<br />";
//			$user_groups[$group->id] = $group->description;
//		}
//		// Find which sources this user has the required group to access
//		$user_accessible_sources = $this->sources_model->getOnlineSources($user_groups);
//		$this->data['user_accessible_sources'] = $user_accessible_sources;
//		$user = $this->ion_auth->user($user_id)->row();
//		
//		$this->data['data_requests'] = $this->sources_model->getDataRequests($user->username);
		
//		$this->data['user'] = $user;
		$this->_render('curate/curator_data_requests');

	}

	
	function records() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group("curator")) {
			redirect('auth', 'refresh');
		}
		$this->load->model('sources_model');

		// Get the id of the current user and fetch the sources that they are a curator for
		$user_id = $this->ion_auth->user()->row()->id;
		$curateable_sources = $this->sources_model->getSourcesThatTheUserCanCurate($user_id);
		$this->data['sources'] = $curateable_sources;
		
		$this->data['record_counts'] = $this->sources_model->countSourceEntries();

		$this->_render('curate/records');
	}

	function curate_records($source = NULL) {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group("curator") ) {
			redirect('auth', 'refresh');
		}
		if ( ! isset($source)) {
			show_error("You must specify a source.");
		}
		
		// Get the id of the current user and fetch the sources that they are a curator for
		// Then check whether they are a curator of this source
		$this->load->model('sources_model');
		$user_id = $this->ion_auth->user()->row()->id;
		$query = $this->sources_model->getSourcesThatTheUserCanCurate($user_id);
		$curateable_sources = array();
		foreach ($query->result() as $s) {
//			error_log("source -> " . print_r($s, 1));
			$curateable_sources[$s->name] = $s->description;
		}
		
		if (! array_key_exists($source, $curateable_sources)) {
			show_error("Sorry, you are not a curator of this source.");
		}
		
		$this->load->model('search_model');
		$count = $this->sources_model->countVariantsInSource($source);
		if ( $count <= $this->config->item('max_variants')) {
			$s = $this->sources_model->getSourceSingle($source);
			$source_full = $s[$source];
			$this->data['source_full'] = $source_full;
			$this->data['source'] = $source;
			$records = $this->search_model->getVariantsForSource($source);
			$this->data['records'] = $records;
			$this->_render('curate/curate_records');
		}
		else {
			show_error("Cannot curate individual records for this source - max number of records to display has been exceeded");
		}
	}
	
	function import_templates() {
		$this->_render('curate/import_templates');
	}
	
	// Dynamically set the current tab in the session - data comes from jquery ajax function that listens for a tab change and then passes the page and tab name which is set here in the session
	function set_current_tab() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		if ($this->input->post('tab') && $this->input->post('current_page')) {
			$tab = $this->input->post('tab');
			$tab = str_replace('#', '', $tab);
			$current_page = $this->input->post('current_page');
//			error_log("tab -> " . $tab . " current_page -> " . $current_page);
			$tab_name = $current_page . "_tab";
			$this->session->set_userdata($tab_name, $tab);
		}
	}
	
	function change_sharing_policy() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		if ($this->input->post('sharing_policy') && $this->input->post('source')) {
			$sharing_policy = $this->input->post('sharing_policy');
			$source = $this->input->post('source');
			$this->load->model('sources_model');
			$this->sources_model->updateSourceSharingPolicy($source, $sharing_policy);
		}
	}

	function change_source_status() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		if ($this->input->post('status') && $this->input->post('source_id')) {
			$status = $this->input->post('status');
			$source_id = $this->input->post('source_id');
			$this->load->model('sources_model');
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
	
	public function generateMD5() {
		$mdstring = md5(uniqid(rand(), true));
		echo $mdstring;
	}
	
	
}