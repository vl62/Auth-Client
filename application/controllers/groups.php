<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');

		// Load MongoDB library instead of native db driver if required
		$this->config->item('use_mongodb', 'ion_auth') ?
		$this->load->library('mongo_db') :

		$this->load->database();
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	}

	//redirect if needed, otherwise display the user list
	function index()
	{

//		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth_federated', 'refresh');
//		}

		
		$groups = authPostRequest('', array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_network_groups_for_installation");
//		print_r($groups);
		$this->data['groups'] = json_decode($groups, TRUE);
//		$this->data['groups'] = $this->ion_auth->getGroupsFull();
//		$this->load->model('sources_model');
//		$source_groups = $this->sources_model->getSourceGroups();
//		print_r($source_groups);
//		$this->data['source_groups'] = $source_groups;
		$this->_render('federated/auth/network_groups');
	}

	
	function create_network_group() {
		$this->title = "Create Group";

//		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
//		{
//			redirect('auth', 'refresh');
//		}

		//validate form input
		$this->form_validation->set_rules('group_name', 'Group name', 'required|alpha_dash|xss_clean|callback_unique_network_group_name_check['.$this->input->post('network').']');
		$this->form_validation->set_rules('desc', 'Description', 'required|xss_clean');
		$this->form_validation->set_rules('network', 'Network', 'xss_clean');

		if ($this->form_validation->run() == TRUE) {
			
			
//			error_log("desc -> " .  $this->input->post('desc'));
			// Create the new group
			$new_group_id = authPostRequest('', array('group_name' => $this->input->post('group_name'), 'group_description' => $this->input->post('desc'), 'network_key' => $this->input->post('network')), $this->config->item('auth_server') . "/api/auth/create_network_group");
//			error_log("new -> $new_group_id");
			if($new_group_id) {
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("groups", 'refresh');
			}
			else {
				$this->data['message'] = $this->ion_auth->errors();
				$this->data['group_name'] = "";
				$this->data['desc'] = "";
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				$this->_render('federated/auth/create_network_group');
			}
		}
		else {
			
			$getNetworks = getNetworksInstallationMemberOf(array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server'));
			$this->data['networks'] = json_decode($getNetworks, TRUE);
//			print_r($getNetworks);

			//display the create group form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['desc'] = array(
				'name'  => 'desc',
				'id'    => 'desc',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);
			$this->data['network'] = array(
				'name'  => 'network',
				'id'    => 'network',
				'type'  => 'dropdown',
				'value' => $this->form_validation->set_value('network'),
			);
			
			$this->_render('federated/auth/create_network_group');
//			$this->load->view('auth/create_group', $this->data);
		}		
	}

	public function unique_network_group_name_check($group_name, $network_key) {
		
		$group_exists = authPostRequest('', array('network_key' => $network_key, 'group_name' => $group_name), $this->config->item('auth_server') . "/api/auth/check_if_group_exists_in_network");
		error_log("group_exists -> $group_exists");
		$group_exists_bool = $group_exists === 'true'? true: false;
		if( ! $group_exists_bool) {
//			error_log("true");
			return TRUE;
		}
		else {
			$this->form_validation->set_message('unique_network_group_name_check', 'The %s field must be unique (there is already a group with that name in the network)');
//			error_log("false");
			return FALSE;
		}
	}
		
	function delete_network_group($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('id', 'group ID', 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['group_id'] = $id;
			$this->data['csrf'] = $this->_get_csrf_nonce();
//			$this->data['group'] = $this->ion_auth->group($id)->row();
			$this->_render('federated/auth/delete_network_group');
		}
		else
		{
			// do we really want to delete?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
//				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				if ($id != $this->input->post('id'))
				{
					show_error('This form post did not pass our security checks.');
				}

				// do we have the right userlevel?
//				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
//					$this->ion_auth->delete_group($id);
					authPostRequest('', array('group_id' => $id), $this->config->item('auth_server') . "/api/auth/delete_network_group");

//				}
			}

			//redirect them back to the auth page
			redirect('groups', 'refresh');
		}
	}
	
	
	
	// Allows viewing and controlling user groups
	function user_groups() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth_federated', 'refresh');
		}
		
		$this->load->model('sources_model');
		$source_groups = $this->sources_model->getSourceGroups();
//		print_r($source_groups);
		$this->data['source_groups'] = $source_groups;
		$this->_render('federated/auth/user-groups');
	}

	// Allows viewing and controlling source groups
	function source_groups() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth_federated', 'refresh');
		}
		$this->load->model('sources_model');
		$source_groups = $this->sources_model->getSourceGroups();
//		print_r($source_groups);
		$this->data['source_groups'] = $source_groups;
		$this->_render('federated/auth/source_groups');
	}
	
	// Allows viewing and controlling source groups
	function groups() {

	}
	
	// create a new group
	function create_group()
	{
		$this->title = "Create Group";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth_federated', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('group_name', 'Group name', 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('desc', 'Description', 'required|xss_clean');

		if ($this->form_validation->run() == TRUE)
		{
//			error_log("desc -> " .  $this->input->post('desc'));
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('desc'));
			if($new_group_id) {
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth_federated/groups", 'refresh');
			}
			else {
				$this->data['message'] = $this->ion_auth->errors();
				$this->data['group_name'] = "";
				$this->data['desc'] = "";
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				$this->_render('federated/auth/create_group');
			}
		}
		else
		{
			//display the create group form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['desc'] = array(
				'name'  => 'desc',
				'id'    => 'desc',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);
			$this->_render('federated/auth/create_group');
//			$this->load->view('auth/create_group', $this->data);
		}
	}

	//edit a group
	function edit_group($id)
	{
		// bail if no group id given
		if(!$id || empty($id))
		{
			redirect('auth_federated', 'refresh');
		}

		$this->title = "Edit Group";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth_federated', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		//validate form input
		$this->form_validation->set_rules('group_name', 'Group name', 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('group_description', 'Group Description', 'required|xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					$this->session->set_flashdata('message', "Group Saved");
					redirect("auth_federated/groups", 'refresh');
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
			}
		}

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the group to the view
		$this->data['group'] = $group;

		$this->data['group_name'] = array(
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name', $group->name),
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);
		$this->_render('federated/auth/edit_group');
//		$this->load->view('auth/edit_group', $this->data);
	}

	function delete_group($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('id', 'group ID', 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['group'] = $this->ion_auth->group($id)->row();
			$this->_render('federated/auth/delete_group');
		}
		else
		{
			// do we really want to delete?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
//				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				if ($id != $this->input->post('id'))
				{
					show_error('This form post did not pass our security checks.');
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->delete_group($id);
				}
			}

			//redirect them back to the auth page
			redirect('auth_federated/groups', 'refresh');
		}
	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	


}
