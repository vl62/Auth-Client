<?php

require(APPPATH . '/libraries/REST_Controller.php');

class Auth extends REST_Controller {
	
	function __construct() {
		parent::__construct();
	}
	
	function in_group_post() {
		
	}
	
	function is_admin_post() {
		
	}
	
	function change_password_post() {
		
	}
	
	function forgot_password_post() {
		
	}
	
	function reset_password_post($code = NULL) {
		
	}
	
	function activate_post($id, $code=false) {
		
	}
	function deactivate_post($id = NULL) {
		
	}
	
	function delete_post($id = NULL) {
		
	}
	
	function users_post() {
		
	}
	
	function create_user_post() {
		
	}
	
	function edit_user_post($id) {
		
	}

	function user_profile_post($id) {
		
	}
	
	function user_edit_profile_post($id) {
		
	}
	
	function user_groups_post() {
		
	}
	
	function source_groups_post() {
		
	}
	
	function groups_post() {
		
	}
	
	function create_group_post() {
		
	}
	
	function edit_group_post($id) {
		
	}
	
	function delete_group_post($id = NULL) {
		
	}

	function get_users_for_network_post() {
		$network_key = $this->post('network_key');
		$installation_key = $this->post('installation_key');
	}
	
}

?>
