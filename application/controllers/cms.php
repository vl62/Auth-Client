<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms extends MY_Controller {
	
	public function index() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$this->data['title'] = "Content Management";
		$this->_render('cms/dashboard');
	}

	
	public function page($page) {
//		echo "display $page";
		$this->title = ucfirst($page);
		$this->_renderDynamic($page);
	}
	
	function pages() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$this->load->model('cms_model');
		$this->data['pages'] = $this->cms_model->getPages();

		$this->_render('cms/pages');
		
	}
	
	function add_page() {
		$this->data['title'] = "Add Page";
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$this->form_validation->set_rules('page_name', 'Page Name', 'required');
		$this->form_validation->set_rules('page_content', 'Page Content', 'required');
		$this->form_validation->set_rules('menus', '', 'callback_check_menu_only_has_single_page_assigned');

		if ($this->form_validation->run() == true) {
			$page_name = $this->input->post('page_name');
			$page_content = $this->input->post('page_content');
			$parent_menu = $this->input->post('menus');
			$create_menu = $this->input->post('create_menu');

			// If the parent menu is set as home make this the default home page in the settings
//			if ( strtolower($parent_menu) == "home" ) {
//				$this->load->model('settings_model');
//				$update = array();
//				$update['name'] = "home_page_name";
//				$update['value'] = $page_name;
//				$this->settings_model->updateSetting($update);
//			}
			
			$this->load->model('cms_model');
			if ( $create_menu == "yes" ) {
				$menu_data = array('menu_name' => $page_name);
				$menu_id = $this->cms_model->insertMenu($menu_data);
				$data = array('page_name' => $page_name, 'page_content' => $page_content, 'parent_menu' => $page_name);
			}
			else {
				$data = array('page_name' => $page_name, 'page_content' => $page_content, 'parent_menu' => $parent_menu);
			}
			$page_id = $this->cms_model->insertPage($data);
//			error_log("--> $page_name $page_content");
			redirect('cms/pages', 'refresh');
		}
		else {
			//display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->load->model('cms_model');
			$this->data['menus'] = $this->cms_model->getMenus();
			$this->data['page_content'] = $this->input->post('page_content');
			$this->data['page_name'] = array(
				'name' => 'page_name',
				'id' => 'page_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('page_name')
			);
			$this->_render('cms/add_page');
		}
	}
	
	function edit_page($page_id = NULL) {
//		error_log("edit_page -> $page_id");
		$this->data['title'] = "Edit Page";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$this->load->model('cms_model');
		$this->data['page_data'] = $this->cms_model->getPageByID($page_id);
		$this->data['page_id'] = $page_id;
		$this->form_validation->set_rules('page_name', 'Page Name', 'required');
		$this->form_validation->set_rules('page_content', 'Page Content', 'required');
		if ($this->form_validation->run() == true) {
			$page_id = $this->input->post('page_id');
			$page_name = $this->input->post('page_name');
			$page_content = $this->input->post('page_content');
			$parent_menu = $this->input->post('menus');

			// If the parent menu is set as home make this the default home page in the settings
//			if ( strtolower($parent_menu) == "home" ) {
//				$this->load->model('settings_model');
//				$update = array();
//				$update['name'] = "home_page_name";
//				$update['value'] = strtolower($page_name);
//				$this->settings_model->updateSetting($update);
//			}
			
//			error_log("ok ---> $page_id -> n:$page_name c:$page_content pm:$parent_menu");
			$this->load->model('cms_model');
			$data = array('page_name' => $page_name, 'page_content' => $page_content, 'parent_menu' => $parent_menu);
			$page_id = $this->cms_model->updatePage($data, $page_id);
//			error_log("--> $page_name $page_content");
			redirect('cms/pages', 'refresh');
		}
		else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->load->model('cms_model');
			$this->data['menus'] = $this->cms_model->getMenus();
			if ( ! $page_id ) {
				$page_id = $this->input->post('page_id');
			}
//			error_log("page_id -> $page_id");
//			error_log("get page by id -> $page_id");
			$page = $this->cms_model->getPageByID($page_id);

			$this->data['page'] = $page;
			$this->data['page_name'] = array(
				'name' => 'page_name',
				'id' => 'page_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('page_name', $page['page_name'])
			);
			$this->_render('cms/edit_page');
		}
	}
	
	function delete_page($page_id) {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$this->load->model('cms_model');
		$this->cms_model->deletePage($page_id);
		redirect('cms/pages', 'refresh');
	}
	
	function menus() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$this->load->model('cms_model');
		$menus = $this->cms_model->getMenus();
		$this->data['menus'] = $menus;
		// TODO: Get page associated to this menu
		$associate_pages = array();
		foreach ( $menus as $menu ) {
			$associate_pages[$menu['menu_name']] = $this->cms_model->getPagesForMenu($menu['menu_name']);
		}
		$this->data['associate_pages'] = $associate_pages;
		$this->_render('cms/menus');
	}
	
	function add_menu() {
		$this->data['title'] = "Add Menu";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		$this->form_validation->set_rules('menu_name', 'Menu Name', 'required|callback_is_menu_unique');
		
		if ($this->form_validation->run() == true) {
			$menu_name = $this->input->post('menu_name');
			$this->load->model('cms_model');
			$menu_data = array('menu_name' => $menu_name);
			$menu_id = $this->cms_model->insertMenu($menu_data);
			redirect('cms/menus', 'refresh');
		}
		else {
			//display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->load->model('cms_model');
			$this->data['menus'] = $this->cms_model->getMenus();
			
			$this->data['menu_name'] = array(
				'name' => 'menu_name',
				'id' => 'menu_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('menu_name')
			);
			$this->_render('cms/add_menu');
		}
	}
	
	function delete_menu($menu_name) {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$this->load->model('cms_model');
		$this->cms_model->deleteMenu($menu_name);
		$this->cms_model->unlinkMenuFromPages($menu_name);
		redirect('cms/menus', 'refresh');
	}
	
	function is_menu_unique($menu_name) {
		$this->load->model('cms_model');
		$check = $this->cms_model->checkMenuExists($menu_name);
		if( ! $check ) {
//			error_log("true check");
			return TRUE;
		}
		else {
//			error_log("false check");
			$this->form_validation->set_message('is_menu_unique', 'The %s field must be unique (there is already a menu with that name)');
			return FALSE;
		}		
	}
	
	function check_menu_only_has_single_page_assigned($menu_name) {
		error_log("--> $menu_name");
		$this->load->model('cms_model');
		$check = $this->cms_model->checkMenuOnlyHasSinglePageAssigned($menu_name);
		if( ! $check ) {
//			error_log("true check");
			return TRUE;
		}
		else {
//			error_log("false check");
			$this->form_validation->set_message('check_menu_only_has_single_page_assigned', '(Currently) only a single page can be assigned to a parent menu');
			return FALSE;
		}		
	}
	
	function change_menu_order() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
//          error_log("field_names -> " . $this->input->post('field_names'));
//          error_log("orders -> " . $this->input->post('orders'));
		$this->load->model('cms_model');
		$this->cms_model->deleteMenus();
		$menu_names = json_decode($this->input->post('menu_names'));
		$orders = json_decode($this->input->post('orders'));
		$c = 0;
		foreach ($menu_names as $menu_name) {
			$c++;
			$data = array('menu_name' => $menu_name);
			$insert_id = $this->cms_model->insertMenu($data);
		}
	}
	
} 
