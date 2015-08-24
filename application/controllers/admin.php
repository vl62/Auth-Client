<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }

    public function index() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->data['title'] = "Admin Dashboard";
        $this->_render('admin/dashboard');
    }

    function preferences() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $this->load->helper('directory');
        $this->data['header_colour_from'] = $this->config->item('header_colour_from');
        $this->data['header_colour_to'] = $this->config->item('header_colour_to');
        $this->data['navbar_selected_tab_colour'] = $this->config->item('navbar_selected_tab_colour');
        $this->data['navbar_font_colour'] = $this->config->item('navbar_font_colour');
        $this->data['navbar_font_colour_hover'] = $this->config->item('navbar_font_colour_hover');

        if (!$this->session->userdata('preferences_tab')) { // Set session preferences_tab to background if it's not already set
            $this->session->set_userdata('preferences_tab', 'background');
        }

        // Get the current themes
        $this->load->model('preferences_model');
        $this->data['themes'] = $this->preferences_model->getThemes();

        // Get current value for the background config from database and get contents of backgrounds image directory
        $background = $this->config->item('background');
        $this->data['current_background'] = $background;
        $background_map = directory_map(BASEPATH . '../resources/images/backgrounds');
        $this->data['background_map'] = $background_map;

        // Get current value for the logo config from database and get contents of logos image directory
        $logo = $this->config->item('logo');
        $this->data['current_logo'] = $logo;
        $logo_map = directory_map(BASEPATH . '../resources/images/logos');
//		print_r($logo_map);
        $this->data['logo_map'] = $logo_map;

        $this->_render('admin/preferences');
    }

    public function upload_logo() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $filename = $_POST['title'];
        $key = 'userfile';
        $config['upload_path'] = FCPATH . 'resources/images/logos/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 1024 * 8;
        if ($filename) { // Filename was supplied by the user
            $filename = strtolower($filename);
            $filename = str_replace(' ', '_', $filename);
            $config['file_name'] = $filename;
        } else { // Convert the filename to lowercase and remove spaces
            $filename = $_FILES['userfile']['name'];
            $filename = strtolower($filename);
            $filename = str_replace(' ', '_', $filename);
            $config['file_name'] = $filename; //set file name
        }

//		$config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($key)) {
            show_error($this->upload->display_errors());
        } else {
            if (isset($_POST['resize']) && !empty($_POST['resize'])) {
                $resize = $_POST['resize'];
//			if ( $resize ) {
                if ($resize == "yes") {
                    // 200 x 98
                    $upload_data = $this->upload->data();
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = FCPATH . 'resources/images/logos/' . $upload_data['file_name'];
//					$config['create_thumb'] = TRUE;
                    $config['maintain_ratio'] = TRUE;
                    $config['master_dim'] = 'height';
                    $config['width'] = 200;
                    $config['height'] = 98;
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                }
            }
            redirect('admin/preferences', 'refresh');
        }
    }

    public function upload_background() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
//		error_log("title -> " . $_POST['title']);
        $filename = $_POST['title'];
        $key = 'userfile';
        $config['upload_path'] = FCPATH . 'resources/images/backgrounds/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 1024 * 8;
        if ($filename) { // Filename was supplied by the user
            $filename = strtolower($filename);
            $filename = str_replace(' ', '_', $filename);
            $config['file_name'] = $filename;
        } else { // Convert the filename to lowercase and remove spaces
            $filename = $_FILES['userfile']['name'];
            $filename = strtolower($filename);
            $filename = str_replace(' ', '_', $filename);
            $config['file_name'] = $filename; //set file name
        }
//		$config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($key)) {
            show_error($this->upload->display_errors());
        } else {
            redirect('admin/preferences', 'refresh');
        }
    }

    function delete_file($type, $filename) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $delete_path = FCPATH . "resources/images/$type/$filename";
//		error_log("dp -> $delete_path");
        unlink($delete_path);
        redirect('admin/preferences', 'refresh');
    }

    function settings($message = NULL) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        if (!$this->session->userdata('settings_tab')) { // Set tab to settings if it's not already set
            $this->session->set_userdata('settings_tab', 'settings');
        }

        if (!$this->session->userdata('fields_tab')) { // Set tab to settings if it's not already set
            $this->session->set_userdata('fields_tab', 'search_result');
        }

        if (!$this->session->userdata('maintenance_tab')) { // Set tab to settings if it's not already set
            $this->session->set_userdata('maintenance_tab', 'regenerate');
        }

        // Get variant table structure and display fields table structure (used for database structure edit and editing display fields tabs)
        $this->load->model('general_model');
        $this->data['table_structure'] = $this->general_model->describeTable("variants");
        $this->load->model('settings_model');
        $this->data['display_fields'] = $this->settings_model->getDisplayFields();
        $this->data['display_fields_grouped'] = $this->settings_model->getDisplayFieldsGroupBySharingPolicy();
        $this->data['individual_record_display_fields'] = $this->settings_model->getIndividualRecordDisplayFields();

        // Get search fields
        $this->data['search_fields'] = $this->settings_model->getSearchFields();

        // Check if ElasticSearch is running and pass result to view
        $this->load->library('elasticsearch');
        $check_if_running = $this->elasticsearch->check_if_running();
        if (array_key_exists('ok', $check_if_running)) {
            $is_elastic_search_running = $check_if_running['ok'];
            $this->data['is_elastic_search_running'] = $is_elastic_search_running;
        }

        // Check the status of maintenance cron job file, if it's empty then cron job won't run
        if (file_exists(FCPATH . '/resources/cron/crontab')) {
            if (filesize(FCPATH . '/resources/cron/crontab') != 0) {
                $this->data['is_maintenance_cron_enabled'] = TRUE;
            }
        }


        if ($this->config->item('federated_head')) {
            $this->load->model('federated_model');
            $node_list = $this->federated_model->getNodeList();
            $node_statuses = array();
            foreach ($node_list as $node_name => $node) {
                $node_status = $this->node_ping($node['node_uri']); // Get the status of each node by pinging them
                $node_statuses[$node['node_name']] = $node_status;
                if (!$node_status) { // If the node is down then update the node record in db
                    $this->federated_model->updateNodeList(array('node_name' => $node_name, 'node_status' => 'offline'));
                } else {
                    if ($node['node_status'] == "offline") { // If the node is up and currently marked as offline in db then update the record and set it as online
                        $this->federated_model->updateNodeList(array('node_name' => $node_name, 'node_status' => 'online'));
                    }
                }
            }
            $this->data['node_statuses'] = $node_statuses;
            $this->data['node_list'] = $node_list;
        }

        // Settings tab
        $settings = $this->_get_settings();
        $this->data['settings'] = $settings;
        // Dynamically create the validation rule for each setting based on the validation_rules field in the settings table in the db
        foreach ($settings as $setting) {
            $this->form_validation->set_rules($setting->name, $setting->name, $setting->validation_rules);
//			error_log("rule -> " . $setting->validation_rules);
        }
        if ($this->form_validation->run() == FALSE) { // Form didn't validate - render the view and the validation errors get printed there
            $this->_render('admin/settings');
        } else { // Form validated, go through each setting and update 
            $this->load->model('settings_model');
//			error_log("1 -> " . $this->input->post('variabletopass'));
            foreach ($settings as $setting) {
                if (array_key_exists($setting->name, $_POST)) { // Need this since checkboxes do not get posted by a form if they are unchecked
//					error_log("FULL -> " . $setting->name . " -> " . $_POST[$setting->name] . " VS " . $setting->value);
                    if ($_POST[$setting->name] != $setting->value) { // Only update the setting in the db if it has been changed
//						error_log($_POST[$setting->name] . " VS " . $setting->value);
//						error_log("update -> " . $setting->name);
                        $update['name'] = $setting->name;
                        $update['value'] = $_POST[$setting->name];
                        if ($setting->name == "federated") {
                            if (!$this->config->item('cafevariome_central')) {
                                $this->send_federated_switch('on');
                                error_log("on -> " . base_url());
                            }
                        }
//						$this->load->model('settings_model');
                        $this->settings_model->updateSetting($update);
                    }
                } else { // Must be a unchecked checkbox so need to deal with this here (set it to off if it isn't already set as off
                    if ($setting->value != "off") {
//						error_log("update EMPTY -> " . $setting->name);
                        $update['name'] = $setting->name;
                        $update['value'] = "off";
                        if ($setting->name == "federated") {
                            if (!$this->config->item('cafevariome_central')) {
                                $this->send_federated_switch('off');
                                error_log("off -> " . base_url());
                            }
                        }
                        $this->settings_model->updateSetting($update);
                    }
//					else {
//						error_log("on?? -> " . $setting->name);
//					}
                }
            }
            // Fetch the updated settings from the database (TODO: just repopulated the array with the new setting instead of doing another query)
            $settings = $this->_get_settings();
            $this->data['settings'] = $settings;
            $this->data['success_message'] = true;
//			$this->_export_settings(); // Write the settings to the settings.php file
            $this->_render('admin/settings');
        }
//		$this->_render('admin/settings');
    }

    function send_federated_switch($on_or_off) {
        if (!$this->config->item('cafevariome_central')) {
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
    function get_federated_switch($on_or_off, $base_url, $site_title, $site_description) {
        if ($this->config->item('cafevariome_central')) {
            $base_url = base64_decode(urldecode($base_url));
//			$external_ip = urldecode($external_ip);
//			$real_ip = urldecode($real_ip);
            $site_title = urldecode($site_title);
            $site_description = urldecode($site_description);

            $ping_result = $this->node_ping($base_url);
            error_log("central $base_url -> $on_or_off -> $ping_result");
            if ($ping_result) {
                $this->load->model('federated_model');
                // Update federated list table
                $data = array("federated_name" => $site_title, "federated_uri" => $base_url, "federated_status" => $on_or_off);
                $exists = $this->federated_model->checkFederatedURIExists($base_url);
                if ($exists) {
                    $timestamp = date("Y-m-d H:i:s");
                    $data['timestamp'] = $timestamp;
                    $this->federated_model->updatedFederated($base_url, $data);
                } else {
                    $insert_id = $this->federated_model->insertFederated($data);
                }
            }
        }
    }

    // Called from jquery function to reset the settings to default values (hardcoded here)
    function settings_default() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $settings = array(
            array('name' => 'email', 'value' => 'admin@cafevariome.org'),
            array('name' => 'twitter', 'value' => ''),
            array('name' => 'rss', 'value' => 'local'),
            array('name' => 'google_analytics', 'value' => 'UA-37141634-1'),
            array('name' => 'cvid_prefix', 'value' => 'vx'),
            array('name' => 'stats', 'value' => 'off'),
            array('name' => 'max_variants', 'value' => '30000'),
            array('name' => 'feature_table_name', 'value' => 'variants'),
            array('name' => 'messaging', 'value' => 'off'),
            array('name' => 'database_structure', 'value' => 'off'),
            array('name' => 'federated', 'value' => 'on'),
            array('name' => 'federated_head', 'value' => 'on'),
            array('name' => 'show_orcid_reminder', 'value' => 'off'),
            array('name' => 'atomserver_enabled', 'value' => 'off'),
            array('name' => 'atomserver_user', 'value' => ''),
            array('name' => 'atomserver_password', 'value' => ''),
            array('name' => 'atomserver_uri', 'value' => 'http://www.cafevariome.org/atomserver/v1/cafevariome/variants')
        );
        $this->load->model('settings_model');
        foreach ($settings as $setting) {
            error_log("--->" . print_r($setting, 1));
            $update['name'] = $setting['name'];
            $update['value'] = $setting['value'];
            error_log(print_r($update, 1));
            $this->settings_model->updateSetting($update);
        }
//		echo "done";
        error_log("DONE!!!");

        redirect('admin/settings', 'refresh');
    }

    function _get_settings() {
        $settings = $this->db->get('settings');
        $settings = $settings->result();
        return $settings;
    }

    function _export_settings() {
        $this->load->helper('file');

        $settings = $this->db->get('settings');
        $settings = $settings->result();

        $settings_file = '<?php' . "\n\n";
        foreach ($settings as $row) {
            $settings_file .= '$config[\'' . addslashes($row->name) . '\'] = \'' . addslashes($row->value) . '\';' . "\n";
        }
        write_file(BASEPATH . '../application/config/settings.php', $settings_file);
    }

    function _get_preferences() {
        $preferences = $this->db->get('preferences');
        $preferences = $preferences->result();
        return $preferences;
    }

    function _export_preferences() {
        $this->load->helper('file');

        $preferences = $this->db->get('preferences');
        $preferences = $preferences->result();

        $preferences_file = '<?php' . "\n\n";
        foreach ($preferences as $row) {
            $preferences_file .= '$config[\'' . addslashes($row->name) . '\'] = \'' . addslashes($row->value) . '\';' . "\n";
        }
        write_file(BASEPATH . '../application/config/preferences.php', $preferences_file);
    }

    function set_display_fields() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
//		error_log("field_names -> " . $this->input->post('field_names'));
//		error_log("orders -> " . $this->input->post('orders'));
        $this->load->model('settings_model');
//		$this->settings_model->deleteDisplayFields();

        $sharing_policy = $this->input->post('sharing_policy');
        $this->settings_model->deleteDisplayFieldsBySharingPolicy($sharing_policy);
        $field_names = json_decode($this->input->post('field_names'));
        $visible_field_names = json_decode($this->input->post('visible_field_names'));
        $c = 0;
        foreach ($field_names as $field_name) {
//			error_log("fn -> " . $field_name);
//			error_log("vfn -> " . $visible_field_names[$c]);
            $visible_field_name = $visible_field_names[$c];
            $c++;
            $data = array('name' => $field_name, 'visible_name' => $visible_field_name, 'order' => $c, 'type' => 'search_result', 'sharing_policy' => $sharing_policy);
            $insert_id = $this->settings_model->insertDisplayField($data);
//			echo json_encode(array($insert_id));
        }
    }

    function delete_display_field($sharing_policy, $display_field_id) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('settings_model');
        $this->settings_model->deleteDisplayField($display_field_id); // Delete the display field
//		$display_fields = $this->settings_model->getDisplayFields(); // Get all the remaining display fields
        $display_fields = $this->settings_model->getDisplayFieldsForSharingPolicy($sharing_policy);
//		$this->settings_model->deleteDisplayFields(); // Empty the display fields table
        $this->settings_model->deleteDisplayFieldsBySharingPolicy($sharing_policy);
        $c = 0;
        foreach ($display_fields as $display_field) { // Reinsert each display field to set new order
            $c++;
            $data = array('name' => $display_field['name'], 'visible_name' => $display_field['visible_name'], 'order' => $c, 'sharing_policy' => $display_field['sharing_policy'], 'type' => 'search_result');
            $insert_id = $this->settings_model->insertDisplayField($data);
        }

        redirect('admin/settings', 'refresh');
    }

    function set_individual_records() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
//		error_log("field_names -> " . $this->input->post('field_names'));
//		error_log("orders -> " . $this->input->post('orders'));
        $this->load->model('settings_model');
//		$this->settings_model->deleteDisplayFields();

        $this->settings_model->deleteIndividualRecordDisplayFields();
        $field_names = json_decode($this->input->post('field_names'));
        $visible_field_names = json_decode($this->input->post('visible_field_names'));
        $c = 0;
        foreach ($field_names as $field_name) {
//			error_log("fn -> " . $field_name);
//			error_log("vfn -> " . $visible_field_names[$c]);
            $visible_field_name = $visible_field_names[$c];
            $c++;
            $data = array('name' => $field_name, 'visible_name' => $visible_field_name, 'order' => $c, 'type' => 'individual_record');
            $insert_id = $this->settings_model->insertDisplayField($data);
        }
        echo json_encode(array($insert_id));
    }

    function delete_individual_record($display_field_id) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('settings_model');
        $this->settings_model->deleteDisplayField($display_field_id); // Delete the display field
        $display_fields = $this->settings_model->getIndividualRecordDisplayFields(); // Get all the remaining display fields
        $this->settings_model->deleteIndividualRecordDisplayFields(); // Empty the display fields table
        $c = 0;
        foreach ($display_fields as $display_field) { // Reinsert each display field to set new order
            $c++;
            $data = array('name' => $display_field['name'], 'visible_name' => $display_field['visible_name'], 'order' => $c, 'type' => 'individual_record');
            $insert_id = $this->settings_model->insertDisplayField($data);
        }

        redirect('admin/settings', 'refresh');
    }

    function change_visible_display_name() {
        $display_field_id = $this->input->post('pk');
        $visible_display_name = $this->input->post('value');
        $this->load->model('settings_model');
        $this->settings_model->updateVisibleDisplayName($display_field_id, $visible_display_name);
        redirect('admin/settings', 'refresh');
    }

    function add_individual_record() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('settings_model');

        $field_name = $this->input->post('field_name');
        $visible_field_name = $this->input->post('visible_field_name');
//		error_log("fn -> $field_name visible_field_name -> $visible_field_name");
//		$c = "20";
        $c = $this->settings_model->getCurrentHighestOrderForType('individual_record');
        $c = $c + 1;
//		error_log("COUNT -> $c");
        $data = array('name' => $field_name, 'visible_name' => $visible_field_name, 'order' => $c, 'type' => 'individual_record');
        $insert_id = $this->settings_model->insertDisplayField($data);

//		error_log("insert_id -> " . $insert_id);
        echo json_encode(array('insert_id' => $insert_id, 'highest_order' => $c));
    }

    function add_display_field() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('settings_model');

        $field_name = $this->input->post('field_name');
        $visible_field_name = $this->input->post('visible_field_name');
        $sharing_policy = $this->input->post('sharing_policy');
//		error_log("fn -> $field_name visible_field_name -> $visible_field_name");
//		$c = "20";
        $c = $this->settings_model->getCurrentHighestOrderForType('search_result');
        $c = $c + 1;
        error_log("COUNT -> $c");
        $data = array('name' => $field_name, 'visible_name' => $visible_field_name, 'order' => $c, 'type' => 'search_result', 'sharing_policy' => $sharing_policy);
        $insert_id = $this->settings_model->insertDisplayField($data);

        error_log("insert_id -> " . $insert_id);
        echo json_encode(array('insert_id' => $insert_id, 'highest_order' => $c));
    }

    function add_search_field() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('settings_model');

        $field_name = $this->input->post('field_name');
//		error_log("fn -> $field_name");
        $data = array('field_name' => $field_name);
        $insert_id = $this->settings_model->insertSearchField($data);
//		error_log("insert_id -> " . $insert_id);
        echo json_encode(array($insert_id));
    }

    function delete_search_field($search_field_id) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('settings_model');
        $this->settings_model->deleteSearchField($search_field_id); // Delete the display field

        redirect('admin/settings', 'refresh');
    }

    function background() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        if ($this->input->post('background')) {
            // Do update to value for background in config table in database
            $this->load->model('preferences_model');
            $update['name'] = "background";
            $update['value'] = $this->input->post('background');
            $this->preferences_model->updatePreference($update);
            $this->_export_preferences();
            redirect('admin/preferences', 'refresh');
        }
    }

    function header_colour() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
//		if ( $this->input->post('header_colour_from') && $this->input->post('header_colour_to')) {
        // Do update to value for background in config table in database
        $this->load->model('preferences_model');
        $update['name'] = "header_colour_from";
        $update['value'] = $this->input->post('header_colour_from');
        $this->preferences_model->updatePreference($update);
        $update['name'] = "header_colour_to";
        $update['value'] = $this->input->post('header_colour_to');
        $this->preferences_model->updatePreference($update);
        $update['name'] = "navbar_selected_tab_colour";
        $update['value'] = $this->input->post('navbar_selected_tab_colour');
        $this->preferences_model->updatePreference($update);

        $this->_export_preferences();
//			redirect('admin/preferences', 'refresh');
//		}
    }

    function change_theme() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $theme = $this->input->post('theme');
//		error_log("theme -> " . $theme);
        $this->load->model('preferences_model');
        $theme_preferences = $this->preferences_model->getTheme($theme); // Get the theme preferences for this theme from the theme table
//		error_log("t -> " . print_r($theme_preferences, 1));
        $header_colour_from = $theme_preferences['header_colour_from'];
        $header_colour_to = $theme_preferences['header_colour_to'];
        $logo = $theme_preferences['logo'];
        $background = $theme_preferences['background'];
        $navbar_font_colour = $theme_preferences['navbar_font_colour'];
        $navbar_font_colour_hover = $theme_preferences['navbar_font_colour_hover'];
        $navbar_selected_tab_colour = $theme_preferences['navbar_selected_tab_colour'];
        $font_name = $theme_preferences['font_name'];

        // Update the preferences with this theme
        $update['name'] = "header_colour_from";
        $update['value'] = $header_colour_from;
        $this->preferences_model->updatePreference($update);
        $update['name'] = "header_colour_to";
        $update['value'] = $header_colour_to;
        $this->preferences_model->updatePreference($update);
        $update['name'] = "logo";
        $update['value'] = $logo;
        $this->preferences_model->updatePreference($update);
        $update['name'] = "navbar_font_colour";
        $update['value'] = $navbar_font_colour;
        $this->preferences_model->updatePreference($update);
        $update['name'] = "navbar_font_colour_hover";
        $update['value'] = $navbar_font_colour_hover;
        $this->preferences_model->updatePreference($update);
        $update['name'] = "navbar_selected_tab_colour";
        $update['value'] = $navbar_selected_tab_colour;
        $this->preferences_model->updatePreference($update);

        $update['name'] = "background";
        $update['value'] = $background;
        $this->preferences_model->updatePreference($update);
        $update['name'] = "current_font_name";
        $update['value'] = $font_name;
        $this->preferences_model->updatePreference($update);
        $update['name'] = "current_font_link";
        $update['value'] = $font_name;
        $this->preferences_model->updatePreference($update);

        $this->_export_preferences();
    }

    function delete_theme($theme_id) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('preferences_model');
        $this->preferences_model->deleteTheme($theme_id);
        redirect('admin/preferences', 'refresh');
    }

    public function save_theme() {
//		error_log("title -> " . $_POST['title']);
        $theme_name = $_POST['theme_name'];
        $theme_name = strtolower($theme_name);
        $theme_name = str_replace(' ', '_', $theme_name);

        $background = $this->config->item('background');
        $logo = $this->config->item('logo');
        $header_colour_from = $this->config->item('header_colour_from');
        $header_colour_to = $this->config->item('header_colour_to');
        $navbar_font_colour = $this->config->item('navbar_font_colour');
        $navbar_font_colour_hover = $this->config->item('navbar_font_colour_hover');
        $navbar_selected_tab_colour = $this->config->item('navbar_selected_tab_colour');
        $font_name = $this->config->item('current_font_name');
        $this->load->model('preferences_model');

        error_log("test -> " . $this->preferences_model->isThemeNameUnique($theme_name));
        if (!$this->preferences_model->isThemeNameUnique($theme_name)) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $randomString = '';
            for ($i = 0; $i < 5; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            $theme_name = $theme_name . "_" . $randomString;
        }

        $theme_data = array('theme_name' => $theme_name,
            'background' => $background,
            'logo' => $logo,
            'header_colour_from' => $header_colour_from,
            'header_colour_to' => $header_colour_to,
            'navbar_font_colour' => $navbar_font_colour,
            'navbar_font_colour_hover' => $navbar_font_colour_hover,
            'font_name' => $font_name,
            'navbar_selected_tab_colour' => $navbar_selected_tab_colour,
        );
//		error_log("theme data -> " . print_r($theme_data, 1));

        $this->preferences_model->saveTheme($theme_data);
        redirect('admin/preferences', 'refresh');
    }

    function font() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        if ($this->input->post('font')) {
            $font_data = $this->config->item('fonts');
            $current_font_link = $this->input->post('font');
            $current_font_name = $font_data[$current_font_link];
            // Do update to value for background in config table in database
            $this->load->model('preferences_model');
            $update['name'] = "current_font_link";
            $update['value'] = $current_font_link;
            $this->preferences_model->updatePreference($update);
            $update['name'] = "current_font_name";
            $update['value'] = $current_font_name;
            $this->preferences_model->updatePreference($update);
        }

        if ($this->input->post('fontsize')) {
            $this->load->model('preferences_model');
            $update['name'] = "font_size";
            $update['value'] = $this->input->post('fontsize');
            $this->preferences_model->updatePreference($update);
        }

        if ($this->input->post('navbar_font_colour')) {
            $this->load->model('preferences_model');
            $update['name'] = "navbar_font_colour";
            $update['value'] = $this->input->post('navbar_font_colour');
            $this->preferences_model->updatePreference($update);
        }

        if ($this->input->post('navbar_font_colour_hover')) {
            $this->load->model('preferences_model');
            $update['name'] = "navbar_font_colour_hover";
            $update['value'] = $this->input->post('navbar_font_colour_hover');
            $this->preferences_model->updatePreference($update);
        }

        $this->_export_preferences();
        redirect('admin/preferences', 'refresh');
    }

    function logo() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        if ($this->input->post('logo')) {
            $this->load->model('preferences_model');
            $update['name'] = "logo";
            $update['value'] = $this->input->post('logo');
            $this->preferences_model->updatePreference($update);
            $this->_export_preferences();
            redirect('admin/preferences', 'refresh');
        }
    }

    function sources() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('sources_model');
        $this->data['variant_counts'] = $this->sources_model->countSourceEntries();
        $sources = $this->sources_model->getSourcesFull();
//		print_r($sources);
        $source_groups = array();
        foreach ($sources->result() as $source) {
//			echo $source->source_id;
            $source_group_data = $this->sources_model->getSourceGroups($source->source_id);
//			print_r($source_group_data);
//			print "group data -> " . $source_group_data['group_id'] . "<br />";
            if (!empty($source_group_data)) {
//				$source_groups[$source->source_id] = array( 'group_id' => $source_group_data['group_id'], 'group_description' => $source_group_data['group_description'] );
                $source_groups[$source->source_id] = $source_group_data;
            }
        }
        // Get all the available groups
        $this->data['groups'] = $this->ion_auth->getGroups();

        $this->data['source_groups'] = $source_groups;
        $this->data['sources'] = $sources;
        $this->_render('admin/sources');
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
            $this->_render('admin/delete_source');
        } else {
            // do we really want to delete?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($source != $this->input->post('source')) {
                    show_error('This form post did not pass our security checks.');
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                    $this->sources_model->deleteSource($source_id);
                    if ($this->input->post('variants') == 'yes') { // also delete variants for the source
                        $is_deleted = $this->sources_model->deleteVariants($source);
                    }
                }
            }
            //redirect them back to the auth page
            redirect('admin/sources', 'refresh');
        }
    }

    public function edit_source($source_id = NULL) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
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
                // Get all the groups that this source is currently in
                $current_user_groups = $this->sources_model->getSourceGroups($this->input->post('source_id'));
                $groups_in = array();
                foreach ($current_user_groups as $group_id => $group_data) {
                    $groups_in[] = $group_data['group_id'];
//					error_log($group_data['group_id']);
                    log_message('debug', "Some variable was correctly set $group_id");
                }

                // Find which current groups have been deselected and therefore need to be removed from this source
                $diff = array_diff($groups_in, $this->input->post('groups'));
//				print_r($diff);
                if (!empty($diff)) {
                    foreach ($diff as $delete_group_id) {
                        $this->sources_model->remove_sources_from_group($delete_group_id, $this->input->post('source_id'));
                    }
                }

                // Find which groups need to be added - go through the selected groups to see if they are not in the sources currently assigned groups
                foreach ($this->input->post('groups') as $group_id) {
                    if (!in_array($group_id, $groups_in)) {
                        $this->sources_model->add_to_sources_group($group_id, $this->input->post('source_id'));
                    }
                }
            } else {
                // All groups were de-selected so remove this source from all groups - do this by passing NULL to ion_auth remove_sources_from_group function
                $this->sources_model->remove_sources_from_group(NULL, $this->input->post('source_id'));
            }

            // Get the curators selected
            if ($this->input->post('curators')) {

                // Get all the curators for this source
                $current_curators = $this->sources_model->getSourceCurators($this->input->post('source_id'));
                $curators_in = array();
                foreach ($current_curators as $user_id => $source_id) {
                    $curators_in[] = $user_id;
                }

                // Find which current curators have been deselected and therefore need to be removed from this source
                $diff = array_diff($curators_in, $this->input->post('curators'));
//				error_log("diff -> " . print_r($diff, 1));
                if (!empty($diff)) {
                    foreach ($diff as $delete_user_id) {
//						error_log("delete $delete_user_id");
                        $this->sources_model->deleteCuratorFromSource($delete_user_id, $this->input->post('source_id'));
//						$this->sources_model->remove_sources_from_group($delete_group_id, $this->input->post('source_id'));
                    }
                }

//				error_log("curators current -> " . print_r($current_curators, 1));
//				error_log("curators post -> " . print_r($this->input->post('curators'), 1));
//				$this->sources_model->deleteSourceCurators($this->input->post('source_id'));
                foreach ($this->input->post('curators') as $user_id) {
                    if (!array_key_exists($user_id, $current_curators)) {
                        $curator_data = array("user_id" => $user_id, "source_id" => $this->input->post('source_id'));
                        $insert_id = $this->sources_model->insertSourceCurator($curator_data);
                        if ($insert_id) {
//							error_log("inserted curator_id -> " . $insert_id);
                        }
                    }
                }
            } else { // No curators selected, delete all for this source
                $this->sources_model->deleteSourceCurators($this->input->post('source_id'));
            }

//			echo "---> $name $uri $description $type<br />";
            redirect("admin/sources", 'refresh');
        } else {
            // Get all the users in this installation for the curator select list
            $this->data['users'] = $this->ion_auth->users()->result();
            // Get the current curators for this source
            $selected_curators = $this->sources_model->getSourceCurators($source_id);
            $this->data['selected_curators'] = $selected_curators;
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
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'style' => 'width:70%',
                'readonly' => 'true', // Don't allow the user to edit the source name
                'value' => $this->form_validation->set_value('name', $source_data['name']),
            );
            $this->data['uri'] = array(
                'name' => 'uri',
                'id' => 'uri',
                'type' => 'text',
                'style' => 'width:70%',
                'value' => $this->form_validation->set_value('uri', $source_data['uri']),
            );
            $this->data['desc'] = array(
                'name' => 'desc',
                'id' => 'desc',
                'type' => 'text',
                'style' => 'width:70%',
                'value' => $this->form_validation->set_value('desc', $source_data['description']),
            );
            $this->data['long_description'] = array(
                'name' => 'long_description',
                'id' => 'long_description',
                'type' => 'text',
                'style' => 'width:70%',
                'rows' => '5',
                'cols' => '3',
                'value' => $this->form_validation->set_value('long_description', $source_data['long_description']),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'style' => 'width:70%',
                'value' => $this->form_validation->set_value('email', $source_data['email']),
            );
            $this->data['status'] = array(
                'name' => 'status',
                'id' => 'status',
                'type' => 'select',
                'value' => $this->form_validation->set_value('status'),
            );
            $this->data['type'] = array(
                'name' => 'type',
                'id' => 'type',
                'type' => 'dropdown',
                'value' => $this->form_validation->set_value('type', $source_data['type']),
            );

            $this->_render('admin/edit_source');
        }
    }

    function add_source() {

        $this->data['title'] = "Add Source";

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        //validate form input

        $this->form_validation->set_rules('name', 'Source Name', 'required|xss_clean|alpha_dash|callback_uniquename_check');
        $this->form_validation->set_rules('owner_name', 'Owner Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Owner Email', 'valid_email|required|xss_clean');
        $this->form_validation->set_rules('uri', 'Source URI', 'required|xss_clean');
        $this->form_validation->set_rules('desc', 'Source Description', 'required|xss_clean');
        $this->form_validation->set_rules('long_description', 'Long Source Description', 'xss_clean');
        $this->form_validation->set_rules('status', 'Source Status', 'required|xss_clean');
//		$this->form_validation->set_rules('type', 'Source Type', 'required|xss_clean');
        // Get all available groups
        $this->data['groups'] = $this->ion_auth->getGroups();
        // Get all the users in this installation for the curator select list
        $this->data['users'] = $this->ion_auth->users()->result();
        if ($this->form_validation->run() == FALSE) {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['name'] = array(
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'style' => 'width:50%',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['owner_name'] = array(
                'name' => 'owner_name',
                'id' => 'owner_name',
                'type' => 'text',
                'style' => 'width:50%',
                'value' => $this->form_validation->set_value('owner_name'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'style' => 'width:50%',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['uri'] = array(
                'name' => 'uri',
                'id' => 'uri',
                'type' => 'text',
                'style' => 'width:50%',
                'value' => $this->form_validation->set_value('uri'),
            );
            $this->data['desc'] = array(
                'name' => 'desc',
                'id' => 'desc',
                'type' => 'text',
                'style' => 'width:50%',
                'value' => $this->form_validation->set_value('desc'),
            );

            $this->data['long_description'] = array(
                'name' => 'long_description',
                'id' => 'long_description',
                'type' => 'text',
                'rows' => '5',
                'cols' => '3',
                'style' => 'width:50%',
                'value' => $this->form_validation->set_value('long_description'),
            );

            $this->data['status'] = array(
                'name' => 'status',
                'id' => 'status',
                'type' => 'select',
                'value' => $this->form_validation->set_value('status'),
            );

            $this->data['type'] = array(
                'name' => 'type',
                'id' => 'type',
                'type' => 'select',
                'value' => $this->form_validation->set_value('type'),
            );
            $this->_render('admin/add_source');
        } else {
            $name = $this->input->post('name'); // Convert the source name to lowercase
            $uri = $this->input->post('uri');
            $owner_name = $this->input->post('owner_name');
            $email = $this->input->post('email');
            $description = $this->input->post('desc');
            $long_description = $this->input->post('long_description');
            $status = $this->input->post('status');
            $type = $this->input->post('type');
            $this->load->model('sources_model');

            $source_data = array("name" => $name, "owner_name" => $owner_name, "email" => $email, "uri" => $uri, "description" => $description, "long_description" => $long_description, "type" => "mysql", "status" => $status);
            $insert_id = $this->sources_model->insertSource($source_data);
            $this->data['insert_id'] = $insert_id;

            if ($this->input->post('groups')) {
                // Add the groups that were selected to this source
                foreach ($this->input->post('groups') as $group_id) {
                    $this->sources_model->add_to_sources_group($group_id, $insert_id); // Add the groups to this source using the source ID that has been created
//					error_log("add -> " . $group_id . " insert -> " .  $insert_id );
                }
            }

            if ($this->input->post('curators')) {
                foreach ($this->input->post('curators') as $user_id) {
                    $curator_data = array("user_id" => $user_id, "source_id" => $insert_id);
                    $insert_id = $this->sources_model->insertSourceCurator($curator_data);
                    if ($insert_id) {
//						error_log("inserted curator_id -> " . $insert_id);
                    }
                }
            }
            redirect("admin/sources", 'refresh');
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
            $source_data = array("name" => $clone_name, "owner_name" => $source_original['owner_name'], "email" => $source_original['email'], "uri" => $source_original['uri'], "description" => $clone_description, "long_description" => "", "type" => "mysql", "status" => "online");
            $insert_id = $this->sources_model->insertSource($source_data);
            if ($insert_id) {
                $this->load->model('general_model');

                // Using current variant table structure get the fields and swap out the original source name for the cloned source
                // End up with 2 strings comma separated that are then used in the cloneSource select and insert query to pull all the variant data 
                // from the source and then re-insert it using the new cloned source name
                $table_structure = $this->general_model->describeTable("variants");
                unset($table_structure['cafevariome_id']); // Do not want to clone the cafevariome_id so remove from array, want to create a new id for the variant instead
                $simple_table_structure = array();
                $simple_table_structure_replace = array();
                foreach ($table_structure as $field => $value) {
//					error_log("field -> " . $field);
                    if ($field == "source") {
                        $simple_table_structure_replace[] = "'$clone_name'";
                    } elseif ($field == "laboratory") {
                        $simple_table_structure_replace[] = "'$clone_name'";
                    } else {
                        $simple_table_structure_replace[] = $field;
                    }
                    $simple_table_structure[] = $field;
                }
                $fields = implode(",", $simple_table_structure);
                $fields_replace = implode(",", $simple_table_structure_replace);
//				error_log("fields -> " . $fields . " -----> " . $fields_replace);

                $clone_result = $this->sources_model->cloneSource($clone_source, $clone_name, $fields, $fields_replace);
                if ($clone_result) {
                    echo "$clone_source was successfully cloned to $clone_name";
                    $this->load->model('messages_model');
                    $user_id = $this->ion_auth->user()->row()->id;
                    $subject = "Source Successfully Cloned";
                    $body = "$clone_source was successfully cloned to $clone_name";
                    $this->messages_model->send_new_message($user_id, $user_id, $subject, $body);
                } else {
                    echo "Cloning of $clone_source to $clone_name failed";
                }
            } else {
                echo "Cloning of $clone_source to $clone_name failed";
            }
        } else {
            echo "All fields are required";
        }
    }

    function tree() {
        $this->_render('admin/tree');
    }

    function tree_data() {
        // jsTree JSON format is here http://old.jstree.com/documentation/json_data
        $this->load->model('phenotypes_model');
        $phenotype_local_list = $this->phenotypes_model->getPhenotypeLocalList();
        // Create the array that is converted to jstree json format and holds the tree data
        $tree_data = array();
        $tree_data['data'] = array('title' => 'My Local List Tree', 'icon' => '');
        $tree_data['children'] = array();
        foreach ($phenotype_local_list as $p) {
            array_push($tree_data['children'], $p['termName']);
//			$tree_data['children'] = array($p['termName']);
        }
//		print_r($phenotype_local_list);
//		error_log("data -> " . print_r($tree_data, 1));
        echo json_encode($tree_data);
    }

    function save_tree_data() {
        $this->load->model('phenotypes_model');
//		$phenotype_local_list = $this->phenotypes_model->getPhenotypeLocalList();
        $tree_data = $this->input->post('tree_data');
//		error_log("tree_data -> $tree_data");
    }

    function store_query_builder_query() {
        $query_id = $this->input->post('query_id');
        $query_statement = $this->input->post('query_statement');
        $query_response = $this->input->post('query_response');
        $total_results = $this->input->post('total_results');
        $endpoint = $this->input->post('endpoint');
//		error_log("query_id:$query_id");
//		error_log("query_statement:$query_statement");
//		error_log("query_response:$query_response");

        $data = array('query_id' => $query_id, 'total_results' => $total_results, 'query_statement' => $query_statement, 'query_response' => $query_response, 'endpoint' => $endpoint);
        $this->load->model('general_model');
        $this->general_model->insertQueryBuilderQuery($data);
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
        foreach ($node_list as $node) {
            $source_uri = $node['node_uri'] . "/discover/sources/json";
            $sources = json_decode(file_get_contents($source_uri));
            $node_source_list[$node['node_name']] = $sources;
        }
        $this->data['node_source_list'] = $node_source_list;

        // Get the current list of federated sources
        $federated_sources = $this->federated_model->getFederatedSources();
        $this->data['federated_sources'] = $federated_sources;

        $this->_render('admin/add_federated_source');
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
            $source_data = array("name" => $source_name, "uri" => $node_uri, "description" => $source_description . " ($node_name)", "type" => "api", "status" => $status);
//			error_log(print_r($source_data, 1));
            if ($status == "online") {
                $insert_id = $this->sources_model->insertSource($source_data);
                if ($insert_id) {
//					error_log("inserted " . $insert_id);
                } else {
                    error_log("couldn't insert");
                }
            } elseif ($status == "offline") {
                $this->sources_model->deleteSourceByName($source_name);
            }
        } else {
            
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
        $this->_render('admin/add_central_source');
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
            $source_data = array("name" => $source_name, "uri" => $uri, "description" => $source_description . " (CV Central)", "type" => "central", "status" => $status);
            error_log(print_r($source_data, 1));
            if ($status == "online") {
                $insert_id = $this->sources_model->insertSource($source_data);
                if ($insert_id) {
//					error_log("inserted " . $insert_id);
                } else {
                    error_log("couldn't insert");
                }
            } elseif ($status == "offline") {
                $this->sources_model->deleteSourceByName($source_name);
            }
        } else {
            
        }
    }

    function phenotypes() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $this->data['title'] = "Phenotypes";

        $this->_render('admin/phenotypes');
    }

    function phenotype_local_list() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $this->data['title'] = "Phenotype Local List";
        $this->load->model('phenotypes_model');
        $this->data['phenotype_local_list'] = $this->phenotypes_model->getPhenotypeLocalList();
        $this->_render('admin/phenotype_local_list');
    }

    function upload_phenotype_list() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $key = 'userfile';
        $config['upload_path'] = FCPATH . 'upload/';
        $config['allowed_types'] = 'txt';
        $config['max_size'] = 1024 * 8;
//		$config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($key)) {
//			error_log("error -> " . $this->upload->display_errors());
            show_error($this->upload->display_errors());
        } else {
//			error_log("it's okay");
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
            $file = FCPATH . 'upload/' . $filename;
            $this->load->model('phenotypes_model');
            $handle = fopen($file, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    // process the line read.
//					error_log("line -> " . $line);
                    $line = str_replace(array("\n", "\r"), '', $line);
                    if (strstr($line, '|')) {
                        list($termName, $termDefinition) = explode('|', $line);
                        $termName = trim($termName);
                        $termDefinition = trim($termDefinition);
                    } else {
                        $termName = $line;
                        $termDefinition = null;
                    }

                    //	$termName = str_replace(array("\n", "\r"), '', $line);
                    $termId = $termName;
                    $termId = strtolower($termId);
                    $termId = str_replace(' ', '_', $termId);
                    $termId = str_replace("\t", '_', $termId);
                    $data = array('sourceId' => 'LocalList',
                        'termId' => 'locallist/' . $termId,
                        'termName' => $termName,
                        'termDefinition' => $termDefinition
                    );
                    $is_unique = $this->phenotypes_model->checkIfPhenotypeTermExists($line);
//					error_log("is unique -> $is_unique");
                    if ($is_unique) {
                        $insert_id = $this->phenotypes_model->insertPhenotypeTerm($data);
                    }
                }
            } else {
                // error opening the file.
                error_log("couldn't open $file");
            }
            redirect('admin/phenotype_local_list', 'refresh');
        }
    }

    function add_phenotype_term() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

//		error_log("term -> " . $this->input->post('phenotype_term'));
        $terms = $this->input->post('phenotype_term');
        $term_array = explode("\n", $terms);
        foreach ($term_array as $termString) {
//			$termName = str_replace(array("\n", "\r"), '', $termName);
            if ($termString == '') { // Ignore any blank lines from the textarea
                continue;
            }
            if (strstr($termString, '|')) {
                list($termName, $termDefinition) = explode('|', $termString);
                $termName = trim($termName);
                $termDefinition = trim($termDefinition);
            } else {
                $termName = $termString;
                $termDefinition = null;
            }

            $termId = $termName;
            if (preg_match('/\[[\w\-\ ]+\]/', $termName, $match)) {
                $termQualifier = $match[0];  // this is the qualifier including surrounding brackets
                $termQualifier = substr($termQualifier, 1, -1);  // remove the brackets 
            } else {
                $termQualifier = null;
            }


            $termId = strtolower($termId);
            $termId = str_replace(' ', '_', $termId);
            $data = array('sourceId' => 'LocalList',
                'termId' => 'locallist/' . $termId,
                'termName' => $termName,
                'termDefinition' => $termDefinition,
                'qualifier' => $termQualifier
            );
            $this->load->model('phenotypes_model');
            $is_unique = $this->phenotypes_model->checkIfPhenotypeTermExists($termName);
            if ($is_unique) {
                $insert_id = $this->phenotypes_model->insertPhenotypeTerm($data);
            }
        }
        redirect('admin/phenotype_local_list', 'refresh');
    }

    function get_ontology_list($apikey) {
        $params = array('dir' => FCPATH . 'Cache');
        $this->load->library('jg_cache', $params);
        error_log("get from cache");
        $data = $this->jg_cache->get('limitedontologylist', 86400);

        if ($data === FALSE) {
            error_log("cache not present OR has expired - getting ontology list");
//			$catlist = $this->_bioportal_ontology_category($apikey,$this->config->item('bioportalcategories'));
//			$data = $this->_bioportal_ontology_list($apikey,$catlist);
            $data = $this->_bioportal_ontology_list($apikey);
            $this->jg_cache->set('limitedontologylist', $data);
        }
        return $data;
    }

//	function _bioportal_ontology_category($apikey,$bpcats) {
//		$url = "http://rest.bioontology.org/bioportal/categories?apikey=$apikey";
//        $context = stream_context_create(array(
//            'http' => array(
//                'method' => "GET",
//                'header' => "content-type: application/xml"
//            )
//        ));
//        $content = file_get_contents($url, false, $context);
//        $xml = simplexml_load_string($content);
//        
//		$results = $xml->xpath('/success/data/list/categoryBean');
//		$catlist=array();
//		foreach($results as $ontology) {
//		
//			$catlabel = $ontology->name;
//			$catid = $ontology->id;
//		
//			foreach($bpcats as $onecat){
//				if (($catlabel == $onecat)){
//					array_push($catlist, $catid);
//				}
//			}
//		}
//		//asort($list);
//		return $catlist;
//	}
//    function _bioportal_ontology_list($apikey,$catlist) {
//
//        $url = "http://rest.bioontology.org/bioportal/ontologies?apikey=$apikey";
//        $context = stream_context_create(array(
//            'http' => array(
//                'method' => "GET",
//                'header' => "content-type: application/xml"
//            )
//        ));
//        $content = file_get_contents($url, false, $context);
//
//        $xml = simplexml_load_string($content);
//        
//        foreach($catlist as $category){		
//            $pathstring="/success/data/list/ontologyBean[categoryIds/int=\"$category\"]";
//            $results = $xml->xpath(''.$pathstring.'');
//            foreach ($results as $ontology) {
//                $label = $ontology->displayLabel . "|" .$ontology->abbreviation;
//                $id = strval($ontology->id) . "|" . strval($ontology->ontologyId);
//                $list[$id] = (string) $label;
//            }
//        }
//        asort($list);
//
//        return $list;
//    }   




    function _bioportal_ontology_list($apikey) {

        $url = "http://data.bioontology.org/ontologies?apikey=$apikey";
        $content = file_get_contents($url);
        $data = json_decode($content);
        $numberofontologies = sizeof($data);
        for ($i = 0; $i < $numberofontologies; $i++) {
            $ontname = $data[$i]->name;
            $ontacronym = $data[$i]->acronym;
            $list[$ontacronym] = $ontname;
        }
        asort($list);
        return $list;
    }

    function custom_sort($a, $b) {
        return strcmp($a['label'], $b['label']);
    }

    function phenotype_ontologies() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->data['title'] = "Phenotype Ontologies";
        $this->load->model('phenotypes_model');
        $phenotype_ontologies = $this->phenotypes_model->getPhenotypeOntologies();

        $this->data['phenotype_ontologies'] = $phenotype_ontologies;
        $this->data['bioportal_api_key'] = $this->config->item('bioportalkey');
        $ontologies = $this->get_ontology_list($this->config->item('bioportalkey'));
        $this->data['ontologies'] = $ontologies;
        $this->_render('admin/phenotype_ontologies');
    }

    function add_new_ontology() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('phenotypes_model');
        $lastranking = $this->phenotypes_model->getLastRanking();
        $newranking = $lastranking + 1;
        $newont = $this->input->post('newont');

        // Prevent direct access to the page
        if (isDefined($newont)) {
            $success_flag = 1;
            list($abb, $name) = explode("|", $newont);
            $ontology_data = array(
                "abbreviation" => $abb,
                "name" => $name,
                "ranking" => $newranking);

            $ont_added = $this->phenotypes_model->insertNewOntology($ontology_data);
            if (!$ont_added) {
                $success_flag = 0;
            }

            if ($success_flag) {
//			echo "Variants were successfully deleted";
                error_log("New ontology was successfully added to db");
            } else {
//			echo "There was a problem deleting one or more variants";
                error_log("There was a problem adding the new ontology to the database");
            }
        } else {
            redirect("admin/phenotype_ontologies", 'refresh');
        }
    }

    function add_new_ontologies() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('phenotypes_model');
        $lastranking = $this->phenotypes_model->getLastRanking();
        $newranking = $lastranking + 1;
        $newonts = $this->input->post('newonts');
        foreach ($newonts as $newont) {
            $success_flag = 1;
            list($abb, $name) = explode("|", $newont[0]);
            $ontology_data = array(
                "abbreviation" => $abb,
                "name" => $name,
                "ranking" => $newranking);

            $ont_added = $this->phenotypes_model->insertNewOntology($ontology_data);
            if (!$ont_added) {
                $success_flag = 0;
            }

            if ($success_flag) {
//			echo "Variants were successfully deleted";
                error_log("New ontology was successfully added to db");
            } else {
//			echo "There was a problem deleting one or more variants";
                error_log("There was a problem adding the new ontology to the database");
            }
        }
        redirect("admin/phenotype_ontologies", 'refresh');
    }

    function delete_ontology() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('phenotypes_model');
        $ont2del = $this->input->post('ont2del');
        $success_flag = 1;
        $ontology_data = array(
            "abbreviation" => $ont2del);

        $ont_removed = $this->phenotypes_model->removeOntology($ontology_data);
        if (!$ont_removed) {
            $success_flag = 0;
        }

        if ($success_flag) {
//			echo "Variants were successfully deleted";
            error_log("Ontology was successfully removed from db");
        } else {
//			echo "There was a problem deleting one or more variants";
            error_log("There was a problem removing the ontology from the database");
        }
    }

    function delete_ll_term() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('phenotypes_model');
        $term2del = $this->input->post('term2del');
        $success_flag = 1;
        $term_data = array(
            "termId" => $term2del);

        $term_removed = $this->phenotypes_model->removeLLTerm($term_data);
        if (!$term_removed) {
            $success_flag = 0;
        }

        if ($success_flag) {
//			echo "Variants were successfully deleted";
            error_log("LocalList term was successfully removed from db");
        } else {
//			echo "There was a problem deleting one or more variants";
            error_log("There was a problem removing the LocalList term from the database");
        }
    }

    function validate_gene($gene) {
//		$gene = $this->input->post('term');
        $this->load->model('general_model');
        $does_gene_exist = $this->general_model->checkGeneExists($gene);
//		error_log("validating -> $gene -> $does_gene_exist");
        if ($does_gene_exist) {
            echo json_encode(array('status' => 'Validated', 'message' => "This is a valid gene symbol"));
        } else {
            echo json_encode(array('status' => 'Not validated', 'message' => "This is NOT a valid gene symbol, however, you may still use it in your query if you wish"));
        }
    }

    function validate_coordinates() {
        $coordinates = $this->input->post('term');
        error_log("validating -> $coordinates");
        if (preg_match_all("/^(chr[a-zA-Z0-9]{1,2}):(\d+)([-|\.\.])(\d+)/i", $coordinates, $matches)) {
            error_log("full");
//			error_log(print_r($matches, 1));
//			error_log("chr -> " . $matches[1][0] . " start -> " . $matches[2][0] . " stop -> " . $matches[4][0]);
            if ($matches[2][0] < $matches[4][0]) {
                error_log("valid");
                echo json_encode(array('status' => 'Validated', 'message' => "These are valid coordinates"));
            } else {
                error_log("not valid");
                echo json_encode(array('status' => 'Not validated', 'message' => "Your coordinate format is valid but your start coordinate is greater than your stop"));
            }
        } else if (preg_match_all("/^(chr)([a-zA-Z0-9]{1,2}$)/i", $coordinates, $matches)) {
            error_log("just chr and is matched -> " . $matches[2][0]);
            if ($matches[2][0] < 22) {
                error_log("numeric");
                echo json_encode(array('status' => 'Validated', 'message' => "This is a valid chromosome name"));
            } elseif ($matches[2][0] == "X") {
                error_log("X");
                echo json_encode(array('status' => 'Validated', 'message' => "This is a valid chromosome name"));
            } elseif ($matches[2][0] == "Y") {
                error_log("Y");
                echo json_encode(array('status' => 'Validated', 'message' => "This is a valid chromosome name"));
            } else {
                echo json_encode(array('status' => 'Not validated', 'message' => "This is NOT a valid chromosome name"));
            }
        } else {
            error_log("something else");
            echo json_encode(array('status' => 'Not validated', 'message' => "The format of your genomic coordinates is not valid, format should be as follows: chr2:40000001-40005001"));
        }
    }

    function validate_hgvs() {
        $hgvs = $this->input->post('term');
//		error_log("validating -> $hgvs");
        if (preg_match_all("/^([c|g|p])\.([-|\*]*)(\d+)([+|-]*)(\d*)(.+)/", $hgvs, $matches)) {
//			echo "validated";
            echo json_encode(array('status' => 'Validated', 'message' => "The format of your HGVS nomenclature is valid"));
        } else {
            echo json_encode(array('status' => 'Not validated', 'message' => "The format of your HGVS nomenclature is NOT valid"));
        }
    }

    function validate_phenotype() {
        $term = $this->input->post('term');
//		error_log("validating -> $term");
        if (preg_match_all("/^([c|g|p])\.([-|\*]*)(\d+)([+|-]*)(\d*)(.+)/", $hgvs, $matches)) {
//			echo "validated";
            echo json_encode(array('status' => 'Validated', 'message' => "The format of your HGVS nomenclature is valid"));
        } else {
            echo json_encode(array('status' => 'Not validated', 'message' => "The format of your HGVS nomenclature is NOT valid"));
        }
    }

    function get_phenotype_ontologies() {
        $this->load->model('phenotypes_model');
        $phenotype_ontologies = $this->phenotypes_model->getPhenotypeOntologies();
//		print_r($phenotype_ontologies);
//		error_log(print_r($phenotype_ontologies, 1));
        echo json_encode($phenotype_ontologies);
    }

    function get_record_and_phenotype_display_fields_nr_list() {
        $this->load->model('phenotypes_model');
        $phenotype_attributes_nr_list = $this->phenotypes_model->getPhenotypeAttributesNRList();
        $this->load->model('general_model');
        $variants_table_structure = $this->db->list_fields('variants');
        error_log(print_r($variants_table_structure, 1));

//		$this->data['table_structure'] = $this->general_model->describeTable("variants");
//		$this->load->model('settings_model');
//		$this->data['display_fields'] = $this->settings_model->getDisplayFields();
//		$this->data['display_fields_grouped'] = $this->settings_model->getDisplayFieldsGroupBySharingPolicy();
//		$this->data['individual_record_display_fields'] = $this->settings_model->getIndividualRecordDisplayFields();
//		print_r($phenotype_attributes_nr_list);
//		error_log(print_r($phenotype_attributes_nr_list, 1));
        echo json_encode($phenotype_attributes_nr_list);
    }

    function get_phenotype_attributes_nr_list_federated($selected_network = '') {

        $token = $this->session->userdata('Token');
        $data = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_all_installations_for_networks_this_installation_is_a_member_of");
        $federated_installs = json_decode(stripslashes($data), 1);
        error_log("federated_installs -> " . print_r($federated_installs, 1));

        if (empty($federated_installs)) {
            error_log("empty");
        }
        $tmp_unique_phenotypes = array();
        foreach ($federated_installs as $install) {
            $network_key = $install['network_key'];
//			error_log("$selected_network ----> $network_key");
            if ($selected_network == $network_key) { // If the selected network matches the current network from the federated installs list
                $install_uri = $install['installation_base_url'];
                $install_uri = rtrim($install_uri, "/");
//				error_log("install -> $install_uri");

                $opts = array('http' =>
                    array(
                        'method' => 'GET',
                        'timeout' => 5
                    )
                );
                $context = stream_context_create($opts);
                $install_phenotype_attributes_nr_list = @file_get_contents($install_uri . "/admin/get_phenotype_attributes_nr_list/", false, $context);

//				$install_phenotype_attributes_nr_list = @file_get_contents($install_uri . "/admin/get_phenotype_attributes_nr_list/");
//				error_log($install_phenotype_attributes_nr_list);
                if ($install_phenotype_attributes_nr_list) {
                    foreach (json_decode($install_phenotype_attributes_nr_list, 1) as $phenotype) {
//						print_r($phenotype);
                        $tmp_unique_phenotypes[$phenotype['attribute_termName']] = $phenotype['attribute_sourceID'];
                    }
                }
            }
        }

        // Also get the local attribute list
        $local_phenotype_attributes_nr_list = @file_get_contents(base_url() . "admin/get_phenotype_attributes_nr_list");
        foreach (json_decode($local_phenotype_attributes_nr_list, 1) as $phenotype) {
            $tmp_unique_phenotypes[$phenotype['attribute_termName']] = $phenotype['attribute_sourceID'];
        }

        $phenotype_attributes_nr_list = array();
        foreach ($tmp_unique_phenotypes as $key => $value) {
            $phenotype_attributes_nr_list[] = array('attribute_sourceID' => $value, 'attribute_termName' => $key);
        }

//		print_r($phenotype_attributes_nr_list);
//		error_log(print_r($phenotype_attributes_nr_list, 1));

        echo json_encode($phenotype_attributes_nr_list);
    }

    function get_phenotype_attributes_nr_list() {
        $this->load->model('phenotypes_model');
        $phenotype_attributes_nr_list = $this->phenotypes_model->getPhenotypeAttributesNRList();
//		print_r($phenotype_attributes_nr_list);
//		error_log(print_r($phenotype_attributes_nr_list, 1));
        echo json_encode($phenotype_attributes_nr_list);
    }

    function autocomplete_bioportal() {
        $term = rawurlencode($this->input->post('term')); // Escape the term as it might contain spaces/not allowed characters
        $ontology = $this->input->post('ontology');
//		error_log("term -> $term | ontology -> $ontology");
        $data['response'] = 'false'; //Set default response
        if ($ontology !== "freetext") {
            $bioportal_url = "http://data.bioontology.org/search?q=$term&ontologies=$ontology&suggest=true&display_links=false&include=prefLabel&apikey=" . $this->config->item('bioportalkey');
//			error_log($bioportal_url);
            $bioportal_data = json_decode(file_get_contents($bioportal_url));
//			error_log(print_r($bioportal_data->collection, 1));
            $data['response'] = 'true';
            $data['message'] = array();
            $json_array = array();
            foreach ($bioportal_data->collection as $entry) {
//				error_log(print_r($entry, 1));
                $pref_label = $entry->prefLabel;
                $term_id = $entry->{'@id'};
                array_push($json_array, $pref_label);
//				error_log("label -> $pref_label | term_id -> $term_id");
            }
            echo json_encode($json_array);
        } else { // Free text so use the internal autocomplete lookup table instead
            $this->autocomplete_query_builder('phenotype', $term);
        }
    }

    function autocomplete_query_builder($type, $term = NULL) {
        $this->load->model('search_model');
        // process posted form data
        if (!$term) {
            $term = $this->input->post('term');
        }
//		error_log("lookup -> " . $keyword);
        $data['response'] = 'false'; //Set default response

        $query = $this->search_model->lookupAutocomplete($term); //Search DB
//		error_log("got past query");
        if (!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            $json_array = array();

            foreach ($query->result() as $row) {
//				error_log(print_r($row, 1));
                if ($row->type == $type) {
                    $auto_val = $row->term;
                    array_push($json_array, $auto_val);
                }
            }
        }
        echo json_encode($json_array); //echo json string if ajax request
    }

    function autocomplete_epad($term = NULL) {
        $this->load->model('phenotypes_model');
        // process posted form data
//		error_log(print_r($term, 1));

        if (!$term) {
            $term = $this->input->post('term');
        }

        $attribute = $this->input->post('attribute');

//		error_log("term -> " . $term . " attribute -> " . $attribute);
        $data['response'] = 'false'; //Set default response
//
        $query = $this->phenotypes_model->lookupPhenotypeAutocompleteWithAttribute($attribute, $term); //Search DB

        if (!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            $json_array = array();

            foreach ($query->result() as $row) {
//				error_log(print_r($row, 1));
                $auto_val = $row->value;
                array_push($json_array, $auto_val);
            }
        }
//		error_log("----> " . json_encode($json_array));
        echo json_encode($json_array); //echo json string if ajax request
    }

    function non_redundant_attribute_list_epad() {
        $this->load->model('phenotypes_model');

        $attribute = $this->input->post('attribute');

//		error_log(" attribute -> " . $attribute);
        $data['response'] = 'false'; //Set default response
//
        $query = $this->phenotypes_model->lookupPhenotypeNonRedundantListForAttribute($attribute); //Search DB

        if (!empty($query)) {
            $json_array = array();
            foreach ($query->result() as $row) {
//				error_log(print_r($row, 1));
                if ($row->value === null)
                    continue;
                $auto_val = $row->value;
                array_push($json_array, $auto_val);
            }
        }
//		error_log("----> " . json_encode($json_array));
        echo json_encode($json_array); //echo json string if ajax request
    }

    function autosave_cell() {
//		error_log("autosaving");
        $change = $this->input->post('change');
        $row_data = $this->input->post('row_data');
//		error_log(print_r($change, 1));
//		error_log(print_r($row_data, 1));
        $id = $row_data[1];
        $id = preg_replace('/' . $this->config->item('cvid_prefix') . '/', '', $id); // Strip off id prefix as the id is store without it in the database
//		error_log("id -> $id");
        $update_data = array();
        $update_data[$change[0][1]] = $change[0][3];
//		error_log(print_r($update_data, 1));
        $this->load->model('sources_model');
        $this->sources_model->updateVariant($update_data, $id);
    }

    function reset_stats() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('stats_model');
        $this->stats_model->resetStatsTables();
    }

    // Interface for variants submitted by AtomServer - connects to AtomServer and pulls in submissions which can then be made live/deleted by the admin
    function submissions() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        if (!$this->config->item('atomserver_enabled')) {
            show_error("Sorry, the submission interface for AtomServer is not enabled.");
        }
        $this->load->model('atomserver_model');

        $submissions = $this->atomserver_model->getAtomServerData($this->config->item('atomserver_uri'), $this->config->item('atomserver_user'), $this->config->item('atomserver_password'));
        // If returned data is an array with the connect key then it means there were problems connecting to AtomServer, report this to user, otherwise carry on
        if (is_array($submissions)) {
            if (array_key_exists("connect", $submissions)) {
                show_error($submissions['connect']);
            }
        }
        $this->data['submissions'] = $submissions;
        $this->_render('admin/submissions');
    }

    //view user profile (for non-admin user)
    function data_access($user_id = '') {
        $this->title = "Data Access & Requests";

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }


        // Get the id of the current user and fetch the groups that they belong
        $user_id = $this->ion_auth->user()->row()->id;
        $user_groups = array();
        $this->load->model('sources_model');
//		$user_groups = array();
//		// Get all the sources for this installation that this user has network group access to
        $sources_array = json_decode(authPostRequest('', array('user_id' => $user_id, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_sources_assigned_to_network_groups_that_user_can_access"));
//		print_r($current_groups);
        $user_accessible_sources = array();
        foreach ($sources_array as $s) {
//			print $s->source_id . "<br />";
//			print_r($s);
            $user_accessible_sources[$s->source_id] = $this->sources_model->getSourceForID($s->source_id) . " (Network: " . $s->network_name . ")";
        }

//		print_r($user_accessible_sources);
//		foreach ($this->ion_auth->get_users_groups($user_id)->result() as $group) {
////		foreach ($current_groups as $group) {
////			print_r($group);
////			echo "groupid -> " . $group->id . " groupname -> " . $group->name . " description -> " . $group->description . "<br />";
//			$user_groups[$group->group_id] = $group->description;
//		}
//		foreach ($this->ion_auth->get_users_groups($user_id)->result() as $group) {
////			echo "groupid -> " . $group->id . " groupname -> " . $group->name . " description -> " . $group->description . "<br />";
//			$user_groups[$group->id] = $group->description;
//		}
//
//		// Find which sources this user has the required group to access
//		$user_accessible_sources = $this->sources_model->getOnlineSources($user_groups);
        $this->data['user_accessible_sources'] = $user_accessible_sources;
        $user = $this->ion_auth->user($user_id)->row();

        $this->data['data_requests'] = $this->sources_model->getDataRequests($user->username);

        $this->data['user'] = $user;
        $this->_render('auth/user_data_access');
    }

    function variants() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('sources_model');
        $this->load->model('search_model');
        $sources = $this->sources_model->getSourcesFull();
        $this->data['variant_counts'] = $this->sources_model->countSourceEntries();
//		foreach ($sources->result() as $source) {
//			echo $source->source_id . " -> " . $source->name . "<br />";
////			$variants = $this->search_model->getVariantsForSource($source->name);
//		}
        $this->data['sources'] = $sources;
        $this->_render('admin/variants');
    }

    function curate() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('sources_model');
        $this->load->model('search_model');
        $sources = $this->sources_model->getSourcesFull();
        $this->data['variant_counts'] = $this->sources_model->countSourceEntries();
//		foreach ($sources->result() as $source) {
//			echo $source->source_id . " -> " . $source->name . "<br />";
////			$variants = $this->search_model->getVariantsForSource($source->name);
//		}
        $this->data['sources'] = $sources;
        $this->_render('admin/variants');
    }

    function set_core_fields() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('general_model');
        // Get the current core fields from the table
        $core_fields = $this->general_model->getCoreFieldsAssociative();
        $this->data['core_fields'] = $core_fields;

        // Get all fields for the variants table
        $all_fields = $this->general_model->getColumnNames('variants');

        $this->data['all_fields'] = $all_fields;

        if (array_key_exists('submit', $_POST)) {
            $core_fields_update = array();
            foreach ($_POST as $key => $value) {
                if ($value) {
//					error_log("key $key -> value $value");
                    $core_fields_update[] = $key;
                }
            }
            $success = $this->general_model->setCoreFields($core_fields_update);
            if (!$success) {
//				error_log("problem setting set core fields");
                $this->data['success_message'] = false;
            } else {
                $this->data['success_message'] = true;
            }
            $core_fields = $this->general_model->getCoreFieldsAssociative();
            $this->data['core_fields'] = $core_fields;
        }

        $this->_render('admin/set_core_fields');
    }

    // Data requests interface for administrator interface
    function data_requests() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->title = "Data Access & Requests";
        $this->load->model('sources_model');
        $this->data['data_requests'] = $this->sources_model->getAllDataRequests();
        $this->_render('admin/data_requests');
    }

    // Called by javascript ajax function processDataRequest that will process approval/refusal/deletion by curator or administrator
    function process_data_request($result, $request_id) {
        if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group("curator")) { // User must either be administrator or a curator
            redirect('auth', 'refresh');
        }

        $this->load->model('sources_model');
        $this->load->model('messages_model');

        $request = $this->sources_model->getDataRequestByID($request_id);

        if (!$this->ion_auth->is_admin()) { // For users who are not admins
            if ($this->ion_auth->in_group("curator")) { // If the user is just a curator then check that they have the priviledges to process the data request for this source
                $request_source = $request['source']; // Get the source that this request is for
                // Get the id of the current user and fetch the sources that they are a curator for
                $user_id = $this->ion_auth->user()->row()->id;
                $query = $this->sources_model->getSourcesThatTheUserCanCurate($user_id);
                $can_curate_flag = FALSE;
                foreach ($query->result() as $source) {
                    if ($request_source == $source->name) {
                        $can_curate_flag = TRUE;
                    }
                }

                if (!$can_curate_flag) {
                    echo json_encode(array('error' => 'Sorry, you do not have curator priviledges for this source'));
                    exit();
//					show_error('Sorry, you do not have curator priviledges for this source');
                }
            }
        }

        $resultreason = $this->input->post('resultreason');
//		error_log($resultreason);
//		print_r($request);
        if ($result == "approved") {
            $this->sources_model->updateDataRequestResult($request_id, $result, $resultreason);

            // Request was approved, send an internal message to the requestor notifying them
            $user_id = $this->ion_auth->getUserIDFromUsername($request['username']);
            $sender_id = "1"; // Send the message from the main admin user
            $this->messages_model->send_new_message($sender_id, $user_id, 'Approved request', "A data request has been approved, you can download the data from the <a href='" . base_url() . "admin/data_access'>following page</a>.");

            // Request was approved, send an email to the requestor notifying them
            $this->load->library('email');
            $this->email->from($this->config->item('email'), 'Admin');
            $this->email->to($request['email']);
            $this->email->subject('Data request');
            $this->email->message("A data request has been approved, you can download the data from the <a href='" . base_url() . "admin/data_access'>following page (you must be logged in)</a>.");
            $this->email->send();

            echo json_encode(array('success' => 'Data request was approved'));
        } elseif ($result == "refused") {
            $this->sources_model->updateDataRequestResult($request_id, $result, $resultreason);

            // Request was refused, send an internal message to the requestor notifying them
            $user_id = $this->ion_auth->getUserIDFromUsername($request['username']);
            $sender_id = "1"; // Send the message from the main admin user
            $this->messages_model->send_new_message($sender_id, $user_id, 'Refused request', "A data request has been refused, you can view the reason why at the <a href='" . base_url() . "admin/data_access'>following page</a>.");

            // Request was refused, send an email to the requestor notifying them
            $this->load->library('email');
            $this->email->from($this->config->item('email'), 'Admin');
            $this->email->to($request['email']);
            $this->email->subject('Data request');
            $this->email->message("A data request has been refused, you can view the reason why at the <a href='" . base_url() . "admin/data_access'>following page (you must be logged in)</a>.");
            $this->email->send();

            echo json_encode(array('success' => 'Data request was refused'));
        } elseif ($result == "delete") {
            $this->sources_model->deleteDataRequest($request_id);
            echo json_encode(array('success' => 'Data request was deleted'));
        }
    }

    // Control of automated maintenance cron job - called from jquery ajax function when enabled/disabled switch in clicked settings admin interface
    function cron_control() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->library('crontab');
        $status = $this->input->post('status');
        if ($status) {
//			$cron_maintenance_file = FCPATH . "upload/cron_maintenance.php";
            $cron_maintenance_file = "cron_maintenance.php";
            if ($status == "enabled") {
                if ($fp = @fopen(FCPATH . "resources/cron/" . $cron_maintenance_file, 'w')) {
                    // Generate a unique md5 string that will be compared against the api call to make sure the call is a proper one from the cron job php cli script
                    $cron_md5 = generateMD5();

                    // Add maintenance jobs to the string that will be written to the php cli file (which is called by the cron job)
                    $cron_maintenance_data = "<?php\n";
                    $cron_maintenance_data .= "ini_set('default_socket_timeout', 3600);\n";
                    $autocomplete_cron = "file_get_contents('" . base_url() . "admin/regenerate_autocomplete/$cron_md5');";
                    $cron_maintenance_data .= $autocomplete_cron . "\n";
                    $ontology_dag_cron = "file_get_contents('" . base_url() . "admin/regenerate_ontologydag/$cron_md5');";
                    $cron_maintenance_data .= $ontology_dag_cron . "\n";
                    $elasticsearch_index_cron = "file_get_contents('" . base_url() . "admin/regenerate_elasticsearch_index/$cron_md5');";
                    $cron_maintenance_data .= $elasticsearch_index_cron . "\n";
                    fwrite($fp, $cron_maintenance_data);
                    fclose($fp);

                    // Write md5 string to the file
                    $md_fp = @fopen(FCPATH . "resources/cron/cron_md5.txt", 'w');
                    fwrite($md_fp, $cron_md5);
                    fclose($md_fp);

                    // Create the cron job and set to run at midnight (first number is minutes, second is hours)
                    $this->crontab->add_job('0 0 * * *', $cron_maintenance_file);

                    echo json_encode(array('status' => 'success', 'message' => "Cron enabled"));
                } else {
                    error_log("Unable to write to the resource/cron directory");
                    echo json_encode(array('status' => 'failed', 'message' => "Couldn't write to the following directory: " . FCPATH . "resources/cron/ in order for the cron job to work you need to make this directory writable by the web server."));
                }
            } elseif ($status == "disabled") {
                // Status is set as disabled so delete the crontab file and associated files
                unlink(FCPATH . "resources/cron/" . $cron_maintenance_file);
                unlink(FCPATH . "resources/cron/crontab");
                unlink(FCPATH . "resources/cron/cron_md5.txt");
                echo json_encode(array('status' => 'success', 'message' => "Cron disabled"));
            }
        }
    }

    // Create an blank excel spreadsheet for current variant table, which is used for variant bulk import interface
    function create_excel_sheet() {
        $this->load->model('general_model');
        $headers = $this->general_model->getColumnNames('variants');
        $this->load->library('phpexcel/PHPExcel');
        $sheet = $this->phpexcel->getActiveSheet();
        $styleArray = array('font' => array('bold' => true));
        $sheet->getCell("A1")->setValue("###DO NOT EDIT THIS ROW OR THE HEADER ROW");
        $sheet->getStyle("A1")->applyFromArray($styleArray);
        $number_headers = count($headers);
//		error_log("numberhead -> " . $number_headers);
        $lastColumn = count($headers);
        $lastColumn++;
        $row = 2; // Start printing headers at row 2
        $c = 0;
        for ($column = 'A'; $column != $lastColumn; $column++) {
            $c++;
            $sheet->getColumnDimension($column)->setAutoSize(true);
            if ($c < $lastColumn) { // Hack since the lastColumn should be in A1 format and not R1C1 format (i.e. a number), can't find a way to convert between the two
//				error_log("col -> " . $column . " -> " . $c . " header -> " . $headers[$c-1]);
                if ($headers[$c - 1] == "sharing_policy") {
                    $sheet->getCell($column . "1")->setValue("openAccess, restrictedAccess or linkedAccess");
                    $sheet->getStyle($column . "1")->applyFromArray($styleArray);
//					$sheet->getStyle('D1:D'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
                }
                if ($c === 1) {
                    $sheet->getCell($column . $row)->setValue($headers[$c - 1] . " (leave this blank)");
                } else {
                    $sheet->getCell($column . $row)->setValue($headers[$c - 1]);
                }
                $sheet->getStyle($column . $row)->applyFromArray($styleArray);
            } else {
                break;
            }
        }

        $writer = new PHPExcel_Writer_Excel5($this->phpexcel);
        $date = date('d-m-Y'); // d-m-Y H:i:s
        $excel_file_name = "cafe_variome_import_full_" . $date . ".xls";
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $excel_file_name . '"');
        $writer->save('php://output');
    }

    // Create an blank excel spreadsheet (hardcoded core fields only), which is used for variant bulk import interface
    function create_excel_sheet_core() {
        $this->load->library('phpexcel/PHPExcel');
        $sheet = $this->phpexcel->getActiveSheet();
        $styleArray = array('font' => array('bold' => true));
//		$sheet->getCell("A1")->setValue("###DO NOT EDIT THIS ROW OR THE HEADER ROW");
//		$sheet->getStyle("A1")->applyFromArray($styleArray);

        $this->load->model('general_model');
        // Get the current core fields from the table
        $core_fields = $this->general_model->getCoreFields();
        $lastColumn = count($core_fields);
        $lastColumn++;
        $row = 1; // Start printing headers at row 2 (row 1 is the description line)
//		$row = 2; // Start printing headers at row 2 (row 1 is the description line)
        $c = 0;
        for ($column = 'A'; $column != $lastColumn; $column++) {
            $c++;
            $sheet->getColumnDimension($column)->setAutoSize(true);
            if ($c < $lastColumn) { // Hack since the lastColumn should be in A1 format and not R1C1 format (i.e. a number), can't find a way to convert between the two
//				error_log("col -> " . $column . " -> " . $c . " header -> " . $core_fields[$c-1]);
                if ($core_fields[$c - 1] == "sharing_policy") { // Create a drop down list for the sharing policy options
                    $objValidation = $sheet->getCell($column . 2)->getDataValidation();
                    $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(true);
                    $objValidation->setShowInputMessage(true);
//					$objValidation->setShowErrorMessage(true);
                    $objValidation->setShowDropDown(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('Value is not in list.');
                    $objValidation->setPromptTitle('Sharing Policy');
                    $objValidation->setPrompt('Pick a sharing policy from the drop-down list if you wish to override the default sharing policy that is specified during the bulk import process.');
                    $objValidation->setFormula1('"openAccess,restrictedAccess,linkedAccess"'); // Make sure to put the list items between " and "  !!!
                }

                $sheet->getCell($column . $row)->setValue($core_fields[$c - 1]);
                $sheet->getStyle($column . $row)->applyFromArray($styleArray);
            } else {
                break;
            }
        }

//$objValidation = $objPHPExcel->getActiveSheet()->getCell('B5')->getDataValidation();
//$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
//$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
//$objValidation->setAllowBlank(false);
//$objValidation->setShowInputMessage(true);
//$objValidation->setShowErrorMessage(true);
//$objValidation->setShowDropDown(true);
//$objValidation->setErrorTitle('Input error');
//$objValidation->setError('Value is not in list.');
//$objValidation->setPromptTitle('Pick from list');
//$objValidation->setPrompt('Please pick a value from the drop-down list.');
//$objValidation->setFormula1('"Item A,Item B,Item C"');	// Make sure to put the list items between " and "  !!!



        $writer = new PHPExcel_Writer_Excel5($this->phpexcel);
        $date = date('d-m-Y'); // d-m-Y H:i:s
        $excel_file_name = "cafe_variome_import_core_" . $date . ".xls";
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $excel_file_name . '"');
        $writer->save('php://output');
    }

    function create_tab_delimited() {
        $this->load->model('general_model');
        $headers = $this->general_model->getColumnNames('variants');
        $number_headers = count($headers);
        $c = 0;
        $headers_csv = "";
        foreach ($headers as $header) {
            $c++;
            if ($c != $number_headers) {
                if ($c === 1) {
                    $headers_csv .= $header . "(leave empty)" . "\t";
                } else {
                    $headers_csv .= $header . "\t";
                }
            } else {
                $headers_csv .= $header;
            }
            error_log("header -> " . $header);
        }
        $date = date('d-m-Y'); // d-m-Y H:i:s
        $tab_file_name = "cafe_variome_import_full_" . $date . ".txt";
        $this->load->helper('download');
        force_download($tab_file_name, $headers_csv);
    }

    function create_tab_delimited_core() {
        // Get the current core fields from the table
        $this->load->model('general_model');
        $headers = $this->general_model->getCoreFields();
//		error_log("headers -> " . $headers);

        $number_headers = count($headers);
        $c = 0;
        $headers_csv = "";
        foreach ($headers as $header) {
            $c++;
            if ($c != $number_headers) {
                $headers_csv .= $header . "\t";
            } else {
                $headers_csv .= $header;
            }
            error_log("header -> " . $header);
        }
        $date = date('d-m-Y'); // d-m-Y H:i:s
        $tab_file_name = "cafe_variome_import_core_" . $date . ".txt";
        $this->load->helper('download');
        force_download($tab_file_name, $headers_csv);
    }

    function num_to_letter($num, $uppercase = FALSE) {
        $num -= 1;
        $letter = chr(($num % 26) + 97);
        $letter .= (floor($num / 26) > 0) ? str_repeat($letter, floor($num / 26)) : '';
        return ($uppercase ? strtoupper($letter) : $letter);
    }

    function stats() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->data['title'] = "Stats Dashboard";
        $this->load->model('stats_model');
        $this->load->model('sources_model');

        // Get data for and create the top search term highchart data and pass to view
        $term_data = $this->stats_model->getSearchTerms();
        $terms = json_encode($term_data['terms']);
        $term_counts = json_encode($term_data['term_counts']);
        $terms_js_data = $this->_generateColumnHighchartJS("Top Search Terms", "termschart", "Term", $terms, "Count", 'Terms', $term_counts, 'grey');
        $this->data['terms_js_data'] = $terms_js_data;

        // Get data for and create the variant counts in sources
        $variant_counts = $this->sources_model->countOnlineSourceEntries();
        $sources = $this->sources_model->getSourcesFull();
        $sources_data = array();
        $variants_data = array();
        foreach ($sources->result() as $source) {
            if (isset($variant_counts[$source->name])) {
                $sources_data[] = $source->name;
                $variants_data[] = (int) $variant_counts[$source->name];
            }
        }
        $sources_names = json_encode($sources_data);
        $variant_counts = json_encode($variants_data);
//		print_r($sources_names);
//		print "<br />";
//		print_r($variant_counts);
        $variants_js_data = $this->_generateColumnHighchartJS("Total Variants By Source", "variantschart", "Source", $sources_names, "Variant Count", 'Variants', $variant_counts, 'skyblue');
        $this->data['variants_js_data'] = $variants_js_data;

        // Get data for and create the IP locations counts and then look up the ip addresses using ipinfodb API
        // TODO: Add in config option to include API key - only display this chart if a key is defined
        $ip_data = $this->stats_model->getUniqueIPAddresses();
        $ips = $ip_data['ips'];
        $ip_counts = $ip_data['ip_counts'];
//		error_log(print_r($ip_data, 1));
        $pie_data = $ip_data['pie_data'];
        $this->load->library('curl');
        $locations_data = array();
        foreach ($ips as $ip) {
//			$geoip = "http://api.ipinfodb.com/v3/ip-city/?key=06bbf69511400b0ce7a56c2b7aa4f4185416599563a4ae34b763a9353b03ceaf&ip=$ip&format=json";
            $geoip = "http://freegeoip.net/json/$ip"; // Now using freegeoip instead of ipinfodb due to inaccuracy problems
            $geoip_result = (array) json_decode($this->curl->simple_get($geoip));
            // Check if there's a location available for the IP address, if so add the count to the locations data array
            if (isset($geoip_result['city']) && $geoip_result['city'] != "-") {
                if (array_key_exists($geoip_result['city'], $locations_data)) {
                    $locations_data[$geoip_result['city']] += $pie_data[$ip];
                } else {
                    $locations_data[$geoip_result['city']] = $pie_data[$ip];
                }
            } else { // Location doesn't exist, store the count as Unknown location
                if (array_key_exists("Unknown", $locations_data)) {
                    $locations_data["Unknown"] += $pie_data[$ip];
                } else {
                    $locations_data["Unknown"] = $pie_data[$ip];
                }
            }
        }
//		error_log(print_r($locations_data,1));
        // Create the final json array for the top visitor locations
        $pie_chart_data = array();
        foreach ($locations_data as $location_name => $location_count) {
            $pie_chart_data[] = array($location_name, $location_count);
        }
        $pie_chart_data_json = json_encode($pie_chart_data);
        $ips_js_data = $this->_generatePieHighchartJS("Top Visitor Locations", "ipschart", 'Location Count', $pie_chart_data_json);
        $this->data['ips_js_data'] = $ips_js_data;

        // Get data for and create the individual variant access counts
        $variant_data = $this->stats_model->getRecordcounts();
        $variant_ids = json_encode($variant_data['cafevariome_ids']);
        $variant_id_counts = json_encode($variant_data['cafevariome_id_counts']);
        $variant_js_data = $this->_generateColumnHighchartJS("Top Accessed Variants", "variantchart", "Cafe Variome ID", $variant_ids, "Number times accessed", 'Number times accessed', $variant_id_counts, '#FE2E2E', '12px');
        $this->data['variant_js_data'] = $variant_js_data;

        $this->_render('admin/stats');
    }

    public function stats_download($type) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('stats_model');
        $this->load->model('sources_model');
        $date = date('d-m-Y');
        if ($type == "search_terms") {
            $raw_csv_stats_data = $this->stats_model->dumpStatsTableAsCSV('stats_searches');
            $csv_file_name = "cafe_variome_search_terms_log_" . $date . ".txt";
            $this->load->helper('download');
            force_download($csv_file_name, $raw_csv_stats_data);
        } elseif ($type == "total_records") {
            // Get data for and create the variant counts in sources
            $variant_counts = $this->sources_model->countOnlineSourceEntries();
//			error_log(print_r($variant_counts));
            $raw_csv_stats_data = "source_name\tcount\n";
            foreach ($variant_counts as $source => $count) {
                $raw_csv_stats_data .= "$source\t$count\n";
            }
            $csv_file_name = "cafe_variome_total_records_per_source_log_" . $date . ".txt";
            $this->load->helper('download');
            force_download($csv_file_name, $raw_csv_stats_data);
        } elseif ($type == "accessed_records") {
            // Get data for and create the individual variant access counts
            $record_count_data = $this->stats_model->getRecordcounts();
//			error_log(print_r($record_count_data, 1));
            $c = 0;
            $raw_csv_stats_data = "record_id\tnumber_of_times_accessed\n";
            foreach ($record_count_data['cafevariome_ids'] as $id) {
                $raw_csv_stats_data .= "$id\t" . $record_count_data['cafevariome_id_counts'][$c] . "\n";
                $c++;
            }
            $csv_file_name = "cafe_variome_total_accessed_records_log_" . $date . ".txt";
            $this->load->helper('download');
            force_download($csv_file_name, $raw_csv_stats_data);
        } elseif ($type == "visitor_locations") {
//			 Get data for and create the IP locations counts and then look up the ip addresses using ipinfodb API
            $ip_data = $this->stats_model->getUniqueIPAddresses();
            $ips = $ip_data['ips'];
            $ip_counts = $ip_data['ip_counts'];
//			error_log(print_r($ip_data, 1));
            $pie_data = $ip_data['pie_data'];
            $this->load->library('curl');
            $locations_data = array();
            $raw_csv_stats_data = "location\tlocation_visit_count\n";
            foreach ($ips as $ip) {
                $geoip = "http://freegeoip.net/json/$ip"; // Now using freegeoip instead of ipinfodb due to inaccuracy problems
                $geoip_result = (array) json_decode($this->curl->simple_get($geoip));
                // Check if there's a location available for the IP address, if so add the count to the locations data array
                if (isset($geoip_result['city']) && $geoip_result['city'] != "-") {
                    if (array_key_exists($geoip_result['city'], $locations_data)) {
                        $locations_data[$geoip_result['city']] += $pie_data[$ip];
                    } else {
                        $locations_data[$geoip_result['city']] = $pie_data[$ip];
                    }
                } else { // Location doesn't exist, store the count as Unknown location
                    if (array_key_exists("Unknown", $locations_data)) {
                        $locations_data["Unknown"] += $pie_data[$ip];
                    } else {
                        $locations_data["Unknown"] = $pie_data[$ip];
                    }
                }
            }
            foreach ($locations_data as $location_name => $location_count) {
                if ($location_name == "") {
                    $raw_csv_stats_data .= "Unknown\t$location_count\n";
                } else {
                    $raw_csv_stats_data .= "$location_name\t$location_count\n";
                }
            }
            $csv_file_name = "cafe_variome_visitor_locations_log_" . $date . ".txt";
            $this->load->helper('download');
            force_download($csv_file_name, $raw_csv_stats_data);
        }
    }

    public function dash() { // Alternative dashboard using Twitter Bootstrap theme (IN PROGRESS)
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->data['title'] = "Admin Dashboard";
        $sidebar['dashboard_title'] = "dash";
        $this->data['dashboard_sidebar'] = $this->load->view("template/dashboard_sidebar", $sidebar, true); // Load the sidebar and pass as a string to the template data
        $this->_renderDashboard('admin/dash');
    }

    public function edit_db_field($field_name = NULL) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->data['title'] = "Edit Field";
        $this->load->model('general_model');
        $table_structure = $this->general_model->describeTable($this->config->item('feature_table_name'));
        //validate form input
        $this->form_validation->set_rules('name', 'Field Name', 'required|xss_clean|alpha_dash');
        $this->form_validation->set_rules('type', 'Field type', 'required|xss_clean');
        $this->form_validation->set_rules('length', 'Length/Value', 'xss_clean');
        $this->form_validation->set_rules('is_displayed', 'Displayed in view?', 'required|xss_clean');

        if ($this->form_validation->run() == true) {
            //check to see if we are creating the user
            //redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            $update_data['name'] = $this->input->post('name');
            $update_data['type'] = $this->input->post('type');
            $update_data['length'] = $this->input->post('length');
            $update_data['is_displayed'] = $this->input->post('is_displayed');
//			$this->general_model->updateField($update_data);
            redirect("admin/edit_db_field", 'refresh');
        } else {
            $field_type = $table_structure[$field_name]['type'];
            if (preg_match_all("/(\w+)\((\d+)\)/", $field_type, $matches)) {
//				print_r($matches);
                $field_type = $matches[1][0];
                $field_length = $matches[2][0];
            } else if (preg_match_all("/(\w+)/", $field_type, $matches)) {
//				print_r($matches);
                $field_type = $matches[1][0];
                $field_length = "";
            } else {
                print "couldn't match field_type<br />";
            }
            $field_type = strtoupper($field_type);
            $this->data['field_name'] = $table_structure[$field_name]['name'];
            $this->data['field_type'] = $field_type;
            $this->data['field_length'] = $field_length;

            $this->data['is_displayed_val'] = 'no';
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
//			print_r($table_structure[$field_name]);
//			print "----> " . $table_structure[$field_name]['name'] . "<br />";
            $this->data['name'] = array(
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'style' => 'width:30%',
                'value' => $this->form_validation->set_value('name', $table_structure[$field_name]['name']),
            );
            $this->data['type'] = array(
                'name' => 'type',
                'id' => 'type',
                'type' => 'dropdown',
                'value' => $this->form_validation->set_value('type', $field_type),
            );

            $this->data['length'] = array(
                'name' => 'length',
                'id' => 'length',
                'type' => 'text',
                'style' => 'width:30%',
                'value' => $this->form_validation->set_value('length', $field_length),
            );
            $this->data['is_displayed'] = array(
                'name' => 'is_displayed',
                'id' => 'is_displayed',
                'type' => 'dropdown',
                'value' => $this->form_validation->set_value('is_displayed', 'yes'),
            );

            $this->_render('admin/edit_db_field');
        }
    }

    function add_db_field() {

        $this->data['title'] = "Add Field";

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        //validate form input

        $this->form_validation->set_rules('name', 'Field Name', 'required|xss_clean|alpha_dash');
        $this->form_validation->set_rules('type', 'Field type', 'required|xss_clean');
        $this->form_validation->set_rules('length', 'Length/Value', 'xss_clean');
//		$this->form_validation->set_rules('is_displayed', 'Displayed in view?', 'required|xss_clean');
//		$this->form_validation->set_rules('type', 'Source Type', 'required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['name'] = array(
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'style' => 'width:30%',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['type'] = array(
                'name' => 'type',
                'id' => 'type',
                'type' => 'dropdown',
                'value' => $this->form_validation->set_value('type'),
            );

            $this->data['length'] = array(
                'name' => 'length',
                'id' => 'length',
                'type' => 'text',
                'style' => 'width:30%',
                'value' => $this->form_validation->set_value('length'),
            );
//			$this->data['is_displayed'] = array(
//				'name'  => 'is_displayed',
//				'id'    => 'is_displayed',
//				'type'  => 'dropdown',
//				'value' => $this->form_validation->set_value('is_displayed', 'yes'),
//			);
            $this->_render('admin/add_db_field');
        } else {
            $field_name = $this->input->post('name');
            $field_type = $this->input->post('type');
            $field_length = $this->input->post('length');
            $is_displayed = $this->input->post('is_displayed');
//			$this->load->model('general_model');
            $this->load->dbforge();
            if ($field_length) {
                $fields = array($field_name => array('type' => $field_type, 'constraint' => $field_length));
            } else {
                $fields = array($field_name => array('type' => $field_type));
            }
            $this->dbforge->add_column('variants', $fields);
            redirect("admin/settings", 'refresh');
        }
    }

    function delete_db_field($name = NULL) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('general_model');
        $this->form_validation->set_rules('confirm', 'confirmation', 'required');
        $this->form_validation->set_rules('name', 'Field Name', 'required|alpha_dash');

        if ($this->form_validation->run() == FALSE) {
            // insert csrf check
            $this->data['name'] = $name;
            $this->_render('admin/delete_db_field');
        } else {
            // do we really want to delete?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($name != $this->input->post('name')) {
                    show_error('This form post did not pass our security checks.');
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                    $this->general_model->deleteDBField($name);
                }
            }
            //redirect them back to the auth page
            redirect('admin/settings', 'refresh');
        }
    }

    function add_node() {

        $this->data['title'] = "Add Node";

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        if (!$this->config->item('federated_head')) {
            
        }
        //validate form input

        $this->form_validation->set_rules('name', 'Node Name', 'required|xss_clean|alpha_dash|callback_is_node_name_unique'); // add in validation to check that the node name doesn't exist already
        $this->form_validation->set_rules('uri', 'Node URI', 'required|xss_clean|callback_is_node_uri_unique|callback_valid_url_format|callback_node_ping'); // add in validation for url both that it exists by pinging or some kind of api check and also that it's a proper uri
        $this->form_validation->set_rules('key', 'Node Key', 'required|xss_clean|exact_length[32]|alpha_numeric|callback_is_node_key_unique');

        if ($this->form_validation->run() == FALSE) {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['name'] = array(
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'style' => 'width:40%',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['uri'] = array(
                'name' => 'uri',
                'id' => 'uri',
                'type' => 'text',
                'style' => 'width:40%',
                'value' => $this->form_validation->set_value('uri'),
            );
            $this->data['key'] = array(
                'name' => 'key',
                'id' => 'key',
                'type' => 'text',
                'style' => 'width:40%',
                'value' => $this->form_validation->set_value('key'),
            );

            $this->_render('admin/add_node');
        } else {
            $node_name = $this->input->post('name');
            $node_uri = $this->input->post('uri');
            $node_key = $this->input->post('key');
            $this->load->model('federated_model');
            $data = array('node_name' => $node_name, 'node_uri' => $node_uri, 'node_key' => $node_key, 'node_status' => "online");
            $insert_id = $this->federated_model->insertNode($data);
            if ($insert_id) {
                $node_list = $this->federated_model->getNodeList(); // Fetch the node list
                // Propagate the list to all the nodes using api
                foreach ($node_list as $node) {
                    updateNode($node_list, $node['node_uri']);
                }
            } else {
//				add in error message for user here
            }
            redirect("admin/settings", 'refresh');
        }
    }

    function delete_node($name = NULL) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('federated_model');
        $this->form_validation->set_rules('confirm', 'confirmation', 'required');
        $this->form_validation->set_rules('name', 'Node Name', 'required|alpha_dash');

        if ($this->form_validation->run() == FALSE) {
            // insert csrf check
            $this->data['name'] = $name;
            $this->_render('admin/delete_node');
        } else {
            // do we really want to delete?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($name != $this->input->post('name')) {
                    show_error('This form post did not pass our security checks.');
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                    // Do the update to the node that was just deleted
                    $node_uri = $this->federated_model->getNodeURIFromNodeName($name);
                    updateNode($node_list, $node_uri);
                    $this->federated_model->deleteNode($name); // Delete the node from the node_list table
                    $this->federated_model->deleteSourcesForNode($name); // Also delete any sources from this node that are in the sources table

                    $node_list = $this->federated_model->getNodeList();
                    // Propagate the list to all the nodes
                    foreach ($node_list as $node_name => $node) {
                        updateNode($node_list, $node['node_uri']);
                    }
                }
            }
            //redirect them back to the auth page
            redirect('admin/settings', 'refresh');
        }
    }

    function refresh_node_list() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        if ($this->config->item('federated_head')) {
            $this->load->model('federated_model');
            $node_list = $this->federated_model->getNodeList();
            // Propagate the list to all the nodes
            foreach ($node_list as $node) {
                $node_status = $this->node_ping($node['node_uri']); // Get current live status of this node
                if ($node_status) {
                    $node_list[$node['node_name']]['node_status'] = "online";
                } else {
                    $node_list[$node['node_name']]['node_status'] = "offline";
                }

                if ($node_status) { // Only make the update call to online nodes
                    updateNode($node_list, $node['node_uri']);
                }
            }
            redirect('admin/settings', 'refresh');
        }
    }

    function get_phenotype_network_values_for_attribute() {

        $network_key = $this->input->post('network_key');
        $attribute = $this->input->post('attribute');

//		error_log("attribute -> " . $attribute . " network_key -> " . $network_key);

        $this->load->model('phenotypes_model');
        $query = $this->phenotypes_model->getPhenotypeNetworkValuesForAttribute($network_key, $attribute); //Search DB

        if (!empty($query)) {
            $json_array = array();
            foreach ($query->result() as $row) {
//				error_log(print_r($row, 1));
                if ($row->value === null)
                    continue;
                $auto_val = $row->value;
                array_push($json_array, $auto_val);
            }
        }
//		error_log("----> " . json_encode($json_array));
        echo json_encode($json_array); //echo json string if ajax request
    }

    function get_phenotype_attributes_for_network($network_key) {
        $this->load->model('phenotypes_model');
        $phenotype_network_attributes_list = $this->phenotypes_model->getPhenotypeAttributesListForNetwork($network_key);
//		print_r($phenotype_network_attributes_list);
//		error_log(print_r($phenotype_network_attributes_list, 1));
        echo json_encode($phenotype_network_attributes_list);
    }

    function get_phenotype_attributes_and_values_list_federated() {
        $this->load->model('phenotypes_model');
        $sources = $this->input->post('sources');
        array_shift($sources);
//                error_log(implode(",", $sources));
        $query = $this->phenotypes_model->regeneratePhenotypeAttributesAndValues(implode(",", $sources));
//                $query = $this->phenotypes_model->regeneratePhenotypeAttributesAndValues();
        echo json_encode($query);
    }

//        function temp() {
//            $token = $this->session->userdata('Token');
//		$data = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_all_installations_for_networks_this_installation_is_a_member_of");
//		$federated_installs = json_decode(stripslashes($data), 1);
////		error_log("federated_installs -> " . print_r($federated_installs, 1));
//		$unique_networks_for_this_install = array();
//		foreach ( $federated_installs as $install ) {
//                        if(!$install['sources'])    continue;
//			$network_key = $install['network_key'];
//			$unique_networks_for_this_install[] = $network_key;
////			error_log("network ----> $network_key");
//			$install_uri = $install['installation_base_url'];
//			$install_uri = rtrim($install_uri,"/");
//			error_log("install -> $install_uri");
//                        
//                        $postdata = http_build_query(
//                            array(
//                                'sources' => array_unique(explode("|", $install['sources']))
//                            )
//                        );
//
//			$opts = array('http' =>
//				array(
//					'method'  => 'POST',
//                                        'header'  => 'Content-type: application/x-www-form-urlencoded',
//                                        'content' => $postdata,
//					'timeout' => 5 
//				)
//			);
//                        
//			$context  = stream_context_create($opts);
//			$install_phenotypes_attributes_and_values_list = @file_get_contents($install_uri . "/admin/get_phenotype_attributes_and_values_list_federated/", false, $context);
//			error_log(print_r($install_phenotypes_attributes_and_values_list, 1));
//                        error_log("-------------------------------------------------------------------------------------");
//		}
//        }
//        
//        function temp2() {
//                $token = $this->session->userdata('Token');
//		$data = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_all_installations_for_networks_this_installation_is_a_member_of");
//		$federated_installs = json_decode(stripslashes($data), 1);
////		error_log("federated_installs -> " . print_r($federated_installs, 1));
//                
//                $urls = array();
//                foreach ($federated_installs as $install) {
//                    if(!$install['sources'])    continue;
//                    $install_uri = $install['installation_base_url'];
//                    $install_uri = rtrim($install_uri,"/");
//                    $urls[] = ["url" => $install_uri . "/admin/get_phenotype_attributes_and_values_list_federated/", "sources" => $install['sources']];
//                }
//                
//                foreach($urls as $url) {
//                    error_log("install -> " .$url['url']);
//                    
//                    $ch = curl_init();
//                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//                    curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
//                    curl_setopt($ch, CURLOPT_HEADER, false);
//                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                                "Token: " . $this->session->userdata('Token'),
//                                "Access-Control-Allow-Origin: *"
//                        ));
//                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//                    curl_setopt($ch, CURLOPT_URL, $url['url']);
//                    curl_setopt($ch, CURLOPT_REFERER, $url['url']);
//                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//                    curl_setopt($ch,CURLOPT_POST, true);
//                    curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query(array("sources" => array_unique(explode("|", $url['sources'])))));
////                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
//                    curl_setopt($ch, CURLOPT_TIMEOUT, 2); //timeout in seconds
//                    $install_phenotypes_attributes_and_values_list = curl_exec($ch);
//                    error_log(print_r($install_phenotypes_attributes_and_values_list, 1));
//                    error_log("-------------------------------------------------------------------------------------");
//                    curl_close($ch);
//                }
//        }
//        
//        function temp3() {
//            $token = $this->session->userdata('Token');
//		$data = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_all_installations_for_networks_this_installation_is_a_member_of");
//		$federated_installs = json_decode(stripslashes($data), 1);
////		error_log("federated_installs -> " . print_r($federated_installs, 1));
//                
//                $urls = array();
//                foreach ($federated_installs as $install) {
//                    if(!$install['sources'])    continue;
//                    $install_uri = $install['installation_base_url'];
//                    $install_uri = rtrim($install_uri,"/");
//                    $urls[] = ["url" => $install_uri . "/admin/get_phenotype_attributes_and_values_list_federated/", "sources" => $install['sources']];
//                }
//                
//                
//                $multi = curl_multi_init();
//                $channels = array();
//                
//                foreach($urls as $url) {
//                    error_log("install -> " .$url['url']);
//                    
//                    $ch = curl_init();
//                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//                    curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
//                    curl_setopt($ch, CURLOPT_HEADER, false);
//                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                                "Token: " . $this->session->userdata('Token'),
//                                "Access-Control-Allow-Origin: *"
//                        ));
//                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//                    curl_setopt($ch, CURLOPT_URL, $url['url']);
//                    curl_setopt($ch, CURLOPT_REFERER, $url['url']);
//                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//                    curl_setopt($ch,CURLOPT_POST, true);
//                    curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query(array("sources" => array_unique(explode("|", $url['sources'])))));
////                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
//                    curl_setopt($ch, CURLOPT_TIMEOUT, 2); //timeout in seconds
//                    
//                    curl_multi_add_handle($multi, $ch);
//                    $channels[$url['url']] = $ch;
//                }
//                
//                // While we're still active, execute curl
//                $active = null;
//                do {
//                    $mrc = curl_multi_exec($multi, $active);
//                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
//
//                while ($active && $mrc == CURLM_OK) {
//                    // Wait for activity on any curl-connection
//                    if (curl_multi_select($multi) == -1) {
//                        continue;
//                    }
//
//                    // Continue to exec until curl is ready to
//                    // give us more data
//                    do {
//                        $mrc = curl_multi_exec($multi, $active);
//                    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
//                }
//
//                // Loop through the channels and retrieve the received
//                // content, then remove the handle from the multi-handle
////                foreach ($channels as $channel) {
////                    error_log(print_r($channel, 1));
////                    error_log(print_r(curl_multi_getcontent($channel), 1));
////                    curl_multi_remove_handle($multi, $channel);
////                }
//
//                // Close the multi-handle and return our results
//                curl_multi_close($multi);
//        }

    function regenerate_federated_phenotype_attributes_and_values_list() {
        $token = $this->session->userdata('Token');
        $result = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_network_and_their_sources_for_this_installation");
//        error_log(print_r(json_decode($result, 1), 1));
        
        $this->load->model('phenotypes_model');
        $this->phenotypes_model->emptyLocalPhenotypesLookup();
        delete_files("resources/phenotype_lookup_data/");
        
        foreach (json_decode($result, 1) as $row) {
//            error_log($row['network_key'] . " | " . $row['source_id']);
            $data = $this->phenotypes_model->localPhenotypesLookupValues($row['source_id'], $row['network_key']);
            $json_data = array();
            foreach ($data as $d) {
                $json_data[] = array($d['phenotype_attribute'] => rtrim($d['phenotype_values'], "|"));
            }
            file_put_contents("resources/phenotype_lookup_data/" . $row['network_key'] . ".json", json_encode($json_data));
            
        }
        
        return;
    }
    
    function get_json_for_phenotype_lookup($network_key = "2a4442db7f48bc55210fc8c0b6a8c17c") {
        echo(file_get_contents("resources/phenotype_lookup_data/" . $network_key . ".json")); 
    }

//    function temp() {
//
//        $this->load->model('phenotypes_model');
//        $this->phenotypes_model->emptyLocalPhenotypesLookup();
//        delete_files("resources/phenotype_lookup_data/");
//        
//        $arr = array(
//            array('network_key' => '5b7a1ae7ac7fa0a4a4c7cedac1982dba', 'source_id' => 7),
//            array('network_key' => '2a4442db7f48bc55210fc8c0b6a8c17c', 'source_id' => 7),
//            array('network_key' => '5b7a1ae7ac7fa0a4a4c7cedac1982dba', 'source_id' => 6),
//            array('network_key' => '2a4442db7f48bc55210fc8c0b6a8c17c', 'source_id' => 6)
//        );
//
//        foreach ($arr as $a) {
//            $data = $this->phenotypes_model->localPhenotypesLookupValues($a['source_id'], $a['network_key']);
//            $json_data = array();
//            foreach ($data as $d) {
//                $json_data[] = array($d['phenotype_attribute'] => rtrim($d['phenotype_values'], "|"));
//            }
//            file_put_contents("resources/phenotype_lookup_data/" . $a['network_key'] . ".json", json_encode($json_data));
//        }
//    }

    function regenerate_federated_phenotype_attributes_and_values_list_old() {
        $token = $this->session->userdata('Token');
        $data = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_all_installations_for_networks_this_installation_is_a_member_of");
        $federated_installs = json_decode(stripslashes($data), 1);
//                error_log("installation key ----> " . $this->config->item('installation_key'));
//		error_log("federated_installs -> " . print_r($federated_installs, 1));
        $this->load->model('phenotypes_model');
        $this->phenotypes_model->emptyNetworksPhenotypesAttributesValues();
        $unique_networks_for_this_install = array();
        foreach ($federated_installs as $install) {
            if (!$install['sources'])
                continue;
//                        error_log("sources status: " . ($install['sources'] ? $install['sources'] : "no data"));
            $network_key = $install['network_key'];
            $unique_networks_for_this_install[] = $network_key;
//			error_log("network ----> $network_key");
            $install_uri = $install['installation_base_url'];
            $install_uri = rtrim($install_uri, "/");
            error_log("install -> $install_uri");

            $postdata = http_build_query(
                    array(
                        'sources' => array_unique(explode("|", $install['sources']))
                    )
            );

            $opts = array('http' =>
                array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata,
                    'timeout' => 5
                )
            );

//                        $opts = array('http' =>
//				array(
//					'method'  => 'GET',
//					'timeout' => 5 
//				)
//			);

            $context = stream_context_create($opts);
            $install_phenotypes_attributes_and_values_list = @file_get_contents($install_uri . "/admin/get_phenotype_attributes_and_values_list_federated/", false, $context);
            error_log(print_r($install_phenotypes_attributes_and_values_list, 1));

            if ($install_phenotypes_attributes_and_values_list) {
                foreach (json_decode($install_phenotypes_attributes_and_values_list, 1) as $phenotype) {
//					error_log(print_r($phenotype, 1));
                    $insert_id = $this->phenotypes_model->insertNetworksPhenotypesAttributesValues(
                            array('network_key' => $network_key,
                                'attribute' => $phenotype['attribute_termName'],
                                'value' => $phenotype['value']
                    ));
                }
            }
        }

//		$unique_networks_for_this_install = array_unique($unique_networks_for_this_install);
//		// Also get the local attribute list and add to each network the install is a member of
//		$local_phenotype_attributes_nr_list = @file_get_contents(base_url() . "admin/get_phenotype_attributes_and_values_list_federated");
//		foreach ( $unique_networks_for_this_install as $network_key_unique ) {
////			error_log("network_key_unique -> $network_key_unique");
//			foreach ( json_decode($local_phenotype_attributes_nr_list, 1) as $phenotype ) {
////				error_log("insert -> " . $phenotype['attribute_termName'] . " -> " . $phenotype['value']);
//				$insert_id = $this->phenotypes_model->insertNetworksPhenotypesAttributesValues(
//                                        array(  'network_key' => $network_key_unique,
//                                                'attribute' => $phenotype['attribute_termName'],
//                                                'value' => $phenotype['value']
//                                            ));
//			}
//		}
    }

    function regenerate_autocomplete($md5 = NULL) {
        if ($md5) {
            $f = fopen(FCPATH . "resources/cron/cron_md5.txt", 'r');
            $file_md5 = fgets($f);
            fclose($f);
            if (strcmp($md5, $file_md5) !== 0) {
                error_log("Cannot regenerate autocomplete md5 does not match");
                exit();
            }
        } elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('general_model');
        $this->general_model->regenerateAutocomplete();
        $this->data['message'] = "Successfully regenerate autocomplete terms";
        redirect('admin/settings', 'refresh');
    }

    function regenerate_autocomplete_for_networks() {
        $token = $this->session->userdata('Token');
        $data = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_all_installations_for_networks_this_installation_is_a_member_of");
        $federated_installs = json_decode(stripslashes($data), 1);
//		error_log("federated_installs -> " . print_r($federated_installs, 1));
        $this->load->model('phenotypes_model');
        $this->phenotypes_model->emptyNetworksPhenotypesAttributesValues();
        $unique_networks_for_this_install = array();
        foreach ($federated_installs as $install) {
            $network_key = $install['network_key'];
            $unique_networks_for_this_install[] = $network_key;
//			error_log("network ----> $network_key");
            $install_uri = $install['installation_base_url'];
            $install_uri = rtrim($install_uri, "/");
//			error_log("install -> $install_uri");

            $opts = array('http' =>
                array(
                    'method' => 'GET',
                    'timeout' => 5
                )
            );
            $context = stream_context_create($opts);
            $install_phenotypes_attributes_and_values_list = @file_get_contents($install_uri . "/admin/get_phenotype_attributes_and_values_list_federated/", false, $context);

            if ($install_phenotypes_attributes_and_values_list) {
                foreach (json_decode($install_phenotypes_attributes_and_values_list, 1) as $phenotype) {
//					error_log(print_r($phenotype, 1));
                    $insert_id = $this->phenotypes_model->insertNetworksPhenotypesAttributesValues(array('network_key' => $network_key,
                        'attribute' => $phenotype['attribute_termName'],
                        'value' => $phenotype['value']
                    ));
                }
            }
        }

        $unique_networks_for_this_install = array_unique($unique_networks_for_this_install);
        // Also get the local attribute list and add to each network the install is a member of
        $local_phenotype_attributes_nr_list = @file_get_contents(base_url() . "admin/get_phenotype_attributes_and_values_list_federated");
        foreach ($unique_networks_for_this_install as $network_key_unique) {
            error_log("network_key_unique -> $network_key_unique");
            foreach (json_decode($local_phenotype_attributes_nr_list, 1) as $phenotype) {
//				error_log("insert -> " . $phenotype['attribute_termName'] . " -> " . $phenotype['value']);
                $insert_id = $this->phenotypes_model->insertNetworksPhenotypesAttributesValues(array('network_key' => $network_key_unique,
                    'attribute' => $phenotype['attribute_termName'],
                    'value' => $phenotype['value']
                ));
            }
        }
    }

    function regenerate_autocomplete_federated() {
        $this->load->model('general_model');
        $this->general_model->regenerateAutocomplete();
    }

    function regenerate_elasticsearch_index($md5 = NULL) {
        if ($md5) {
            $f = fopen(FCPATH . "resources/cron/cron_md5.txt", 'r');
            $file_md5 = fgets($f);
            fclose($f);
            if (strcmp($md5, $file_md5) !== 0) {
                error_log("Cannot regenerate elasticsearch index md5 does not match");
                exit();
            }
        } elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
//		elseif ( ! $this->input->is_cli_request() ) {
//			exit();			
//		}
        // Build the variant to phenotypes linker table
//		$this->build_variant_to_phenotypes_table();
        // Re-populate federated phenotype attribute list
//                $this->regenerate_federated_phenotype_attributes_and_values_list();

        $this->load->model('sources_model');
        $this->load->library('elasticsearch');
        // Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
        $es_index = $this->config->item('site_title');
        $es_index = preg_replace('/\s+/', '', $es_index);
        $es_index = strtolower($es_index);
        $this->elasticsearch->set_index($es_index);
        $this->elasticsearch->set_type("variants");
        $this->elasticsearch->create();
        $this->elasticsearch->delete_all();



//		$settings_data['index'] = array("number_of_shards" => 1, "number_of_replicas" => 1);
//		$settings_data['settings']['analysis']['filter']['special_character_filter'] = array("type" => "word_delimiter", "type_table" => array("# => ALPHA", "@ => ALPHA"));
//		$settings_data['settings']['analysis']['analyzer']['my_lowercaser'] = array("type" => "custom", "tokenizer" => "keyword", "filter" => array("lowercase"));
//		$settings_data['analysis']['filter']['special_character_filter'] = array("type" => "word_delimiter", "type_table" => array("# => ALPHA", "@ => ALPHA"));
//		$settings_data['analysis']['analyzer']['special_character_analyzer'] = array("type" => "custom", "tokenizer" => "whitespace", "filter" => array("lowercase", "special_character_filter"));
//		$settings_json = json_encode($settings_data);
//		error_log($settings_json);
//		$settings_result = $this->elasticsearch->settings($settings_json); // Add the settings
//		error_log(print_r($settings_result, 1));
        // Create the mapping index
        $map_data = array();

//		$settings_data['settings']['index']['analysis']['analyzer']['my_lowercaser'] = array("type" => "custom", "tokenizer" => "keyword", "filter" => array("lowercase"));

        $variants_fields = $this->db->list_fields('variants'); // Get the current columns headers for the records table
        foreach ($variants_fields as $field) {
//			error_log($field);
//			$map_data['variants']['properties']['phenotypes']['properties']['Gender']['type'] = 'long';
//			$map_data['variants']['properties']['phenotypes']['properties']['MRI_[count_of_results]']['index'] = 'not_analyzed';
            $map_data['variants']['properties'][$field]['type'] = 'multi_field';
            $map_data['variants']['properties'][$field]['fields'] = array($field . '' => array('type' => 'string', 'index' => 'analyzed', 'ignore_malformed' => 'true'), $field . '_d' => array('type' => 'double', 'index' => 'analyzed', 'ignore_malformed' => 'true'));
        }
        $this->load->model('phenotypes_model');
        $phenotype_fields = $this->phenotypes_model->getPhenotypeAttributesNRList(); // Get non redundant list of all the phenotype attributes
//		error_log(print_r($phenotype_fields, 1));
        foreach ($phenotype_fields as $field) {
//			error_log($field['attribute_termName']);
            $attribute_term = str_replace(' ', '_', $field['attribute_termName']); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
            $map_data['variants']['properties']['phenotypes']['properties'][$attribute_term]['type'] = 'multi_field';
            $map_data['variants']['properties']['phenotypes']['properties'][$attribute_term]['fields'] = array($attribute_term => array('type' => 'string', 'index' => 'analyzed', 'ignore_malformed' => 'true'), $attribute_term . '_d' => array('type' => 'double', 'index' => 'analyzed', 'ignore_malformed' => 'true'), $attribute_term . '_raw' => array('type' => 'string', 'ignore_malformed' => 'true', 'index' => 'not_analyzed')); // 'analyzer' => 'special_character_analyzer',  'index' => 'not_analyzed', 
        }
        $map_json = json_encode($map_data);
//		error_log(json_encode($map_data));
        $map_result = $this->elasticsearch->map($map_json); // Do the mapping
//		error_log(print_r($map_result, 1));

        $total_variants = $this->sources_model->countAllVariants();
//		error_log("total -> " . $total_variants . " -> " . $this->config->item('max_variants'));
        $index_result_flag = 1;
        if ($total_variants > $this->config->item('max_variants')) { // If there's more records than the max then fetch and index records in batches
            for ($i = 0; $i <= $total_variants; $i+=$this->config->item('max_variants')) {
//				error_log("i -> " . $i);
                $variant_ids = $this->sources_model->getVariantsLimitOffset($this->config->item('max_variants'), $i);
                foreach ($variant_ids as $variant_id) {
                    $cafevariome_id = $variant_id['cafevariome_id'];
//					error_log("id -> " . $cafevariome_id);
                    $index_data = $this->sources_model->getVariantWithPhenotypeJSON($cafevariome_id);
//					error_log(print_r($index_data, 1));
                    $index_result = $this->elasticsearch->add($cafevariome_id, $index_data);
//					error_log("RESULT -> " . print_r($index_result, 1));
//					if ( ! $index_result[0]->ok ) {
                    if (!$index_result) {
                        $index_result_flag = 0;
                    }
                }
            }
        } else { // Fetch all the records and index
            $variant_ids = $this->sources_model->getAllVariantIDs();
            foreach ($variant_ids as $variant_id) {
//				error_log("---> " . $variant_id['cafevariome_id']);
                $cafevariome_id = $variant_id['cafevariome_id'];
                $index_data = $this->sources_model->getVariantWithPhenotypeJSON($cafevariome_id);
//				error_log("ID $cafevariome_id END" . print_r($index_data, 1));
//				error_log(print_r($index_data, 1));
                $index_result = $this->elasticsearch->add($cafevariome_id, $index_data);
//				error_log("RESULT -> " . print_r($index_result, 1));
//				if ( ! $index_result[0]->ok ) {
                if (!$index_result) {
                    error_log("INDEXING FAILED -> $cafevariome_id -> $index_result -> " . print_r($index_result, 1));
                    error_log($index_data);
                    $index_result_flag = 0;
                }
//				else {
//					error_log("INDEXING SUCCESS -> $cafevariome_id" . print_r($index_result, 1));
//				}
            }
        }
        if ($index_result_flag) {
            echo "Successfully regenerated the ElasticSearch index";
//			$map_data['phenotypes'][] = $phenotype_array;
//			$map_data = array();
//			$map_data['variants']['properties']['phenotypes']['properties']['APOE_[most_recent_result]']['type'] = 'integer';
//			
//
        } else {
//			$map_data = array();
//			$map_data['variants']['properties']['phenotypes']['properties']['MRI_[count_of_results]']['type'] = 'long';
//			$map_data['variants']['properties']['phenotypes']['properties']['MRI_[count_of_results]']['index'] = 'no';
//			$map_json = json_encode($map_data);
//			error_log(json_encode($map_data));
//			//APOE4_[most_recent_result]
//			$map_result = $this->elasticsearch->map($map_json);
//			error_log(print_r($map_result, 1));

            echo "Failed to regenerated the ElasticSearch index, is ElasticSearch running?";
        }
    }

    function regenerate_elasticsearch_index_old($md5 = NULL) {
        if ($md5) {
            $f = fopen(FCPATH . "resources/cron/cron_md5.txt", 'r');
            $file_md5 = fgets($f);
            fclose($f);
            if (strcmp($md5, $file_md5) !== 0) {
                error_log("Cannot regenerate elasticsearch index md5 does not match");
                exit();
            }
        } elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
//		elseif ( ! $this->input->is_cli_request() ) {
//			exit();			
//		}
        // Build the variant to phenotypes linker table
        $this->build_variant_to_phenotypes_table();

        $this->load->model('sources_model');
        $this->load->library('elasticsearch');
        // Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
        $es_index = $this->config->item('site_title');
        $es_index = preg_replace('/\s+/', '', $es_index);
        $es_index = strtolower($es_index);
        $this->elasticsearch->set_index($es_index);
        $this->elasticsearch->set_type("variants");
        $this->elasticsearch->delete_all();
        $total_variants = $this->sources_model->countAllVariants();
//		error_log("total -> " . $total_variants . " -> " . $this->config->item('max_variants'));
        $index_result_flag = 1;
        if ($total_variants > $this->config->item('max_variants')) {
            for ($i = 0; $i <= $total_variants; $i+=$this->config->item('max_variants')) {
//				error_log("i -> " . $i);
                $variant_ids = $this->sources_model->getVariantsLimitOffset($this->config->item('max_variants'), $i);
                foreach ($variant_ids as $variant_id) {
                    $cafevariome_id = $variant_id['cafevariome_id'];
//					error_log("id -> " . $cafevariome_id);
                    $index_data = $this->sources_model->getVariantWithPhenotypeJSON($cafevariome_id);
                    $index_result = $this->elasticsearch->add($cafevariome_id, $index_data);
//					error_log("RESULT -> " . print_r($index_result, 1));
//					if ( ! $index_result[0]->ok ) {
                    if (!$index_result) {
                        $index_result_flag = 0;
                    }
                }
            }
        } else {
            $variant_ids = $this->sources_model->getAllVariantIDs();
            foreach ($variant_ids as $variant_id) {
//				error_log("---> " . $variant_id['cafevariome_id']);
                $cafevariome_id = $variant_id['cafevariome_id'];
                $index_data = $this->sources_model->getVariantWithPhenotypeJSON($cafevariome_id);
                $index_result = $this->elasticsearch->add($cafevariome_id, $index_data);
//				error_log("RESULT -> " . print_r($index_result, 1));
//				if ( ! $index_result[0]->ok ) {
                if (!$index_result) {
                    $index_result_flag = 0;
                }
            }
        }
        if ($index_result_flag) {
            echo "Successfully regenerated the ElasticSearch index";
        } else {
            echo "Failed to regenerated the ElasticSearch index, is ElasticSearch running?";
        }
    }

    function start_elasticsearch() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $ret = system(FCPATH . '/elasticsearch/bin/elasticsearch.bat', $retval);
        } else {
            $ret = system(FCPATH . '/elasticsearch/bin/elasticsearch', $retval);
        }
        $error_codes = array(
            "0" => "Successfully started, wait for 10 seconds then refresh this page",
            "1" => "General errors",
            "2" => "Misuse of shell builtins",
            "126" => "Cannot start due to permission problem",
            "127" => "Command not found",
            "128" => "Invalid argument to exit",
            "130" => "Script terminated by Control-C",
            "255" => "Exit status out of range"
        );

        echo $error_codes[$retval];
    }

//	function mutalyzer_ws ($call, $params) {
//	function mutalyzer_ws ($chr) {
    function mutalyzer_ws($chr, $hgvs) {
//		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth', 'refresh');
//		}
//		error_log("chr -> $chr hgvs $hgvs");
//		$mutalyzer_result = $this->mutalyzer->$call();
        $this->load->library('mutalyzer');
//		print "CHR -> $chr\n";
        $chromAccession = $this->mutalyzer->mutalyzerGetChromosomeAccession('hg19', "chr" . $chr);
//		error_log("$chr acc -> $chromAccession:$hgvs");
//		print_r($chromAccession);
//		print "$hgvs\n";
        $mutalyzer_out = @(array) $this->mutalyzer->mutalyzerConvertPositionToTranscript('hg19', $chromAccession . ":" . $hgvs);
//		error_log(print_r($mutalyzer_out, 1));
//		print_r($mutalyzer_out['string']);
        if (array_key_exists('string', $mutalyzer_out)) {
            echo json_encode($mutalyzer_out['string']);
        } else {
            echo "error:" . $chromAccession;
        }
//		$mutalyzer = $this->mutalyzer->runMutalyzer('NC_000001.10', 'g.156104965T>A');
//		print_r($mutalyzer);
//		$mutalyzer_check_result = @$this->mutalyzer->runMutalyzer($refseq, $hgvs);
    }

    function mutalyzer_spawn_process() {
//		$i = 1;
//		while ($i <= 10) {
//			echo $i++;
//			sleep(1);
//		}
        echo "foobar";
        $source = "test";
        $this->load->model('messages_model');
//		$sender_id = $this->ion_auth->user()->row()->id;
        $sender_id = "1";
        $username = $this->session->userdata('username');
//		$recipients = $this->messages_model->get_admin_user_ids();
        $recipients = "1";
        $command = "curl http://localhost/cafevariome/admin/mutalyzer_check_background/$source/yes/yes";
        $pid = shell_exec(sprintf('%s > /dev/null 2>&1 & echo $!', $command));
        $this->messages_model->send_new_message($sender_id, $recipients, 'Mutalyzer Check Complete', "Mutalyzer checking has finished for $source. You can access the mutalyzer report for this validation <a href='http://localhost/cafevariome'>here</a>");
    }

    function mutalyzer_check_background($source = "test3", $with_genomic_coordinates = "yes", $report = "yes") {
//	function mutalyzer_check_background () {
//		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth', 'refresh');
//		}
        $source = $this->input->post('source');
        $with_genomic_coordinates = $this->input->post('with_genomic_coordinates');
        $report = $this->input->post('report');

//		$source = $this->input->get('source');
//		$with_genomic_coordinates = $this->input->get('with_genomic_coordinates');
//		$report = $this->input->get('report');

        if (preg_match("/yes/", $report)) {
            error_log("report -------> $report");
            $report_flag = TRUE;
            $report_array = array();
        } else {
            $report_flag = FALSE;
        }
//		error_log("-----> $source $with_genomic_coordinates $report");
        $this->load->model('search_model');
        $this->load->model('sources_model');
        $this->load->library('mutalyzer');
        $variants = $this->search_model->getVariantsForSource($source);


//		set_time_limit(1000);
        foreach ($variants as $variant) {
            $ref = trim($variant['ref']);
            $hgvs = trim($variant['hgvs']);
            $mutalyzer_result = $this->mutalyzer->runMutalyzer($ref, $hgvs);
//			error_log("RESULT -> " . print_r($mutalyzer_result, 1));
            if (array_key_exists('chr', $mutalyzer_result) && array_key_exists('start', $mutalyzer_result) && array_key_exists('end', $mutalyzer_result)) {
                $variant_update = array('location_ref' => $mutalyzer_result['chr'], 'start' => $mutalyzer_result['start'], 'end' => $mutalyzer_result['end'], 'mutalyzer_check' => $mutalyzer_result['is_valid']);
            } else if (array_key_exists('chr', $mutalyzer_result)) {
                $variant_update = array('location_ref' => $mutalyzer_result['chr'], 'mutalyzer_check' => $mutalyzer_result['is_valid']);
            } else {
                $variant_update = array('mutalyzer_check' => $mutalyzer_result['is_valid']);
            }
//			error_log("updating -> " . print_r($variant_update, 1));

            $update_result = $this->sources_model->updateVariant($variant_update, $variant['cafevariome_id']);
//			error_log("result -> " . $update_result);

            if ($report_flag) {
                $report_array[] = array('id' => $variant['cafevariome_id'],
                    'ref' => $ref,
                    'hgvs' => $hgvs,
                    'is_valid' => $mutalyzer_result['is_valid'],
                    'summary' => $mutalyzer_result['summary'],
                    'message' => $mutalyzer_result['message'],
                    'warnings' => $mutalyzer_result['warnings'],
                    'errors' => $mutalyzer_result['errors']
                );
            }
        }

        if ($report_flag) {
//			error_log("REPORT $report");
            $excel_file = $this->_create_mutalyzer_report_file($report_array);
            echo "$excel_file";
        }
//		redirect('admin/settings', 'refresh');
    }

//	function mutalyzer_check ($source = "test3", $with_genomic_coordinates = "yes", $report = "yes") {
    function mutalyzer_check() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $source = $this->input->post('source');
        $with_genomic_coordinates = $this->input->post('with_genomic_coordinates');
        $report = $this->input->post('report');

//		$source = $this->input->get('source');
//		$with_genomic_coordinates = $this->input->get('with_genomic_coordinates');
//		$report = $this->input->get('report');

        if (preg_match("/yes/", $report)) {
            error_log("report -------> $report");
            $report_flag = TRUE;
            $report_array = array();
        } else {
            $report_flag = FALSE;
        }
//		error_log("-----> $source $with_genomic_coordinates $report");
        $this->load->model('search_model');
        $this->load->model('sources_model');
        $this->load->library('mutalyzer');
        $variants = $this->search_model->getVariantsForSource($source);


//		set_time_limit(1000);
        foreach ($variants as $variant) {
            $ref = trim($variant['ref']);
            $hgvs = trim($variant['hgvs']);
            $mutalyzer_result = $this->mutalyzer->runMutalyzer($ref, $hgvs);
//			error_log("RESULT -> " . print_r($mutalyzer_result, 1));
            if (array_key_exists('chr', $mutalyzer_result) && array_key_exists('start', $mutalyzer_result) && array_key_exists('end', $mutalyzer_result)) {
                $variant_update = array('location_ref' => $mutalyzer_result['chr'], 'start' => $mutalyzer_result['start'], 'end' => $mutalyzer_result['end'], 'mutalyzer_check' => $mutalyzer_result['is_valid']);
            } else if (array_key_exists('chr', $mutalyzer_result)) {
                $variant_update = array('location_ref' => $mutalyzer_result['chr'], 'mutalyzer_check' => $mutalyzer_result['is_valid']);
            } else {
                $variant_update = array('mutalyzer_check' => $mutalyzer_result['is_valid']);
            }
//			error_log("updating -> " . print_r($variant_update, 1));

            $update_result = $this->sources_model->updateVariant($variant_update, $variant['cafevariome_id']);
//			error_log("result -> " . $update_result);

            if ($report_flag) {
                $report_array[] = array('id' => $variant['cafevariome_id'],
                    'ref' => $ref,
                    'hgvs' => $hgvs,
                    'is_valid' => $mutalyzer_result['is_valid'],
                    'summary' => $mutalyzer_result['summary'],
                    'message' => $mutalyzer_result['message'],
                    'warnings' => $mutalyzer_result['warnings'],
                    'errors' => $mutalyzer_result['errors']
                );
            }
        }

        if ($report_flag) {
//			error_log("REPORT $report");
            $excel_file = $this->_create_mutalyzer_report_file($report_array);
            echo "$excel_file";
        }
//		redirect('admin/settings', 'refresh');
    }

    function _create_mutalyzer_report_file($report_array) {
//		fwrite($handle, $variant['cafevariome_id'] . "\t" . $ref . ":" . $hgvs . "\t" . $mutalyzer_result['is_valid'] . "\t" . $mutalyzer_result['summary'] . "\t" . $mutalyzer_result['message'] . "\t" . $mutalyzer_result['warnings'] . "\t" . $mutalyzer_result['errors'] . "\n");
        $this->load->library('phpexcel/PHPExcel');
        $sheet = $this->phpexcel->getActiveSheet();
//		$objPHPExcel = new PHPExcel();
        $styleArray = array('font' => array('bold' => true));
        $sheet->getCell("A1")->setValue("###DO NOT EDIT THIS ROW OR THE HEADER ROW");
        $sheet->getStyle("A1")->applyFromArray($styleArray);
        $excel_datetime = date('d-m-Y_H-i-s');

        // Add header
        $sheet->SetCellValue('A1', 'ID');
        $sheet->SetCellValue('B1', 'ref');
        $sheet->SetCellValue('C1', 'hgvs');
        $sheet->SetCellValue('D1', 'is_valid');
        $sheet->SetCellValue('E1', 'summary');
        $sheet->SetCellValue('F1', 'message');
        $sheet->SetCellValue('G1', 'warnings');
        $sheet->SetCellValue('H1', 'errors');

        $sheet->getStyle("A1:H1")->getFont()->setBold(true);
        $row = 1;
        foreach ($report_array as $report) {
            $row++;
            $sheet->SetCellValue('A' . $row, $report['id']);
            $sheet->SetCellValue('B' . $row, $report['ref']);
            $sheet->SetCellValue('C' . $row, $report['hgvs']);
            $sheet->SetCellValue('D' . $row, $report['is_valid']);
            $sheet->SetCellValue('E' . $row, $report['summary']);
            $sheet->SetCellValue('F' . $row, $report['message']);
            $sheet->SetCellValue('G' . $row, $report['warnings']);
            $sheet->SetCellValue('H' . $row, $report['errors']);
        }


        $sheet->getColumnDimension("A")->setAutoSize(true);
        $sheet->getColumnDimension("B")->setAutoSize(true);
        $sheet->getColumnDimension("C")->setAutoSize(true);
        $sheet->getColumnDimension("D")->setAutoSize(true);
        $sheet->getColumnDimension("E")->setAutoSize(true);
        $sheet->getColumnDimension("F")->setAutoSize(true);
        $sheet->getColumnDimension("G")->setAutoSize(true);
        $sheet->getColumnDimension("H")->setAutoSize(true);

//		$writer = new PHPExcel_Writer_Excel5($this->phpexcel);
        $writer = new PHPExcel_Writer_Excel2007($this->phpexcel);
        $date = date('d-m-Y'); // d-m-Y H:i:s
//		$excel_file = "mutalyzer_report_" . $date . ".xlsx";
//		header('Content-type: application/vnd.ms-excel');
//		header('Content-Disposition: attachment;filename="' . $excel_file . '"');
//		$excel_file = '/Library/WebServer/Documents/cafevariome/upload/' . "mutalyzer_report_" . $date . ".xlsx";
        $excel_file = FCPATH . '/upload/' . "mutalyzer_report_" . $date . ".xlsx";
        $excel_file_link = "<div class='pagination-centered'><a class='btn' href='" . base_url() . 'upload/' . "mutalyzer_report_" . $date . ".xlsx" . "'>Download Mutalyzer Report</a></div>";
//		error_log("link -> $excel_file_link");
        $writer->save($excel_file);
//		$writer->save('php://output');
        return $excel_file_link;
    }

    function regenerate_ontologydag($md5 = NULL) {
        if ($md5) {
            $f = fopen(FCPATH . "resources/cron/cron_md5.txt", 'r');
            $file_md5 = fgets($f);
            fclose($f);
            if (strcmp($md5, $file_md5) !== 0) {
                error_log("Cannot regenerate ontology dag md5 does not match");
                exit();
            }
        } elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $done = array();
        $this->load->model('general_model');
        $this->general_model->deleteDAG();
        $todo = $this->general_model->getOntologiesUsed();
        while (list($tstring, $abb) = each($todo)) {
            $abbreviation = $abb;
            list($key, $value) = explode("|", $tstring);
            if (!in_array($key, $done)) {
                $localtodo = $this->_get_parents($apikey, $abbreviation, $key, $value);
                foreach ($localtodo as $parentstring => $abbreviation) {
                    list($parentid, $parentname) = explode("|", $parentstring);
                    if (!in_array($parentid, $todo)) {
                        $this->general_model->addOntologyRelationship($abbreviation, $key, $parentid, $value);
                        if ($parentid != '1') {
                            $parentstring = $parentid . "|" . $parentname;
                            $todo[$parentstring] = $abbreviation;
                        }
                    }
                }
                array_push($done, $value);
            }
        }
        $terminal_nodes = $this->general_model->determineTerminalNodes();
        foreach ($terminal_nodes as $thisnode) {
            $this->general_model->setTerminalNode($thisnode);
        }
        $this->data['message'] = "Successfully regenerated ontology tree and search";
        redirect('admin/settings', 'refresh');
    }

    function _get_parents($apikey, $abbreviation, $termid, $termname) {
        $parseidtag = '@id';
        $apikey = $this->config->item('bioportalkey');
        $localtodo = array();
        $parseid = urlencode($termid);
        $url = "http://data.bioontology.org/ontologies/$abbreviation/classes/$parseid?apikey=$apikey&include=parents";
        error_log("$url");
        $content = file_get_contents($url);
        $data = json_decode($content);
        $child = $data->$parseidtag;
        $numberofparents = sizeof($data->parents);
        for ($i = 0; $i < $numberofparents; $i++) {
            $labelhere = property_exists($data->parents[$i], 'prefLabel');
            if ($labelhere == true) {
                $singlepid = $data->parents[$i]->$parseidtag;
                $singleplabel = $data->parents[$i]->prefLabel;
            } else {
                $singlepid = '1';
                $singleplabel = "root";
            }
            $parentstring = $singlepid . "|" . $singleplabel;
            $localtodo[$parentstring] = $abbreviation;
        }
        return $localtodo;
    }

    // Extracts all variants from the LOVD public install list - TODO: convert to CodeIgniter style and only allow CV central and admin users to run
    function lovd_list() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        require_once('/local/www/htdocs/cafevariome/php/RestRequestModified.inc.php');
        require_once('/local/www/htdocs/cafevariome/php/simplepie.inc');
        include('/local/www/htdocs/cafevariome/php/simple_html_dom.php');
        ini_set("user_agent", "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
        ini_set("max_execution_time", 0);
#		ini_set("memory_limit", "10000M");
        $con = mysql_connect("192.168.16.74", "ol8", "it3412");
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db("cafevariome_lsdb_data", $con);

        $out_con = mysql_connect("192.168.16.74", "ol8", "it3412");
        if (!$out_con) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db("cafevariome_lovd_data", $out_con);

        $query = "SELECT * FROM lovd_list";
        $result = mysql_query($query, $con);
        $c = 0;
        while ($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {
#		$c++;
            print "TEST -> $c -> " . $row['DB_location'] . "\n<br />";
            $url = $row['DB_location'];
#		$url = "https://oi.gene.le.ac.uk";
#		if (preg_match("*\.nl*", $url)) {
#			print "skipping -> $url<br>";
#			continue;
#		}
#		if (!preg_match("*grenada*", $url)) {
#			continue;
#		}

            if (preg_match("*mitolsdb*", $url)) {
                continue;
            }
            if (preg_match("/\/$/", $url)) {
                $api_url = $url . "api/rest.php/genes";
#			print "<h5>gene single -> $url $api_url</h5>";
            } else {
                $api_url = $url . "/api/rest.php/genes";
#			print "<h5>gene none -> $url $api_url</h5>";
            }
            print "<h4>$api_url</h4>\n";
            $get = new RestRequest($api_url, 'GET');
            $get->execute();
            $getresponse = $get->getResponseInfo();
            $http_code = $getresponse["http_code"];
#		print_r($get);
            if ($http_code == 200) {
                $c++;
                print "<h4>loading $api_url</h4>\n";
#			$string = file_get_contents($api_url);
#			$genes_xml = simplexml_load_file($string);
#			$genes_xml = simplexml_load_string(file_get_contents($api_url));
#			$genes_xml = simpleXML_load_file($api_url,"SimpleXMLElement",LIBXML_NOCDATA); 
                $genes_xml = simplexml_load_file($api_url);
#			print_r($genes_xml);
#			$genes_xml = simplexml_load_string($api_url);

                if ($genes_xml === false) {
                    sleep(1);
                    print "<h4>first try at reloading $api_url</h4>\n";
                    $genes_xml = simplexml_load_file($api_url);
                    if ($genes_xml === false) {
                        print "<h5>Couldn't get genes xml for $api_url</h5>\n";
                    }
                } else {
                    print "<h4>loaded $api_url</h4>\n";
                }
                foreach ($genes_xml->entry as $gene_entry) {
                    $gene = (string) $gene_entry->title;
                    $gene_content = (string) $gene_entry->content;
#				print "gcontent -> $gene_content<br>";
#				preg_match('/id:(\S+)\s+entrez_id:(\S+)\s+symbol:(\S+)\s+name:(.*)\s+chromosome_location:(\S+)\s+position_start:(\S+)\s+position_end:(\S+)\s+refseq_genomic:(\S+)\s+refseq_mrna:(\S+)\s+refseq_build:(\S+)/', $gene_content, $gene_content_extracted);				

                    if (preg_match('/id:(\S+)\s+entrez_id:(\S+)\s+symbol:(\S+)\s+name:(.*)\s+chromosome_location:(\S+)\s+position_start:(\S+)\s+position_end:(\S+)\s+refseq_genomic:(\S+)\s+refseq_mrna:(\S+)\s+refseq_build:(\S+)/', $gene_content, $gene_content_extracted)) {
                        $gene_id = $gene_content_extracted[1];
                        $entrez_id = $gene_content_extracted[2];
                        $gene_symbol = $gene_content_extracted[3];
                        $gene_name = $gene_content_extracted[4];
                        $chr_location = $gene_content_extracted[5];
                        $start = $gene_content_extracted[6];
                        $stop = $gene_content_extracted[7];
                        $ref_seq_genomic = $gene_content_extracted[8];
                        $ref_seq_mrna = $gene_content_extracted[9];
                        $ref_seq_build = $gene_content_extracted[10];
#					print "gene-content-extracted-------> $gene_id $entrez_id $gene_symbol $gene_name $chr_location $start $stop $ref_seq_genomic $ref_seq_mrna $ref_seq_build<br><br><br>";
#					print_r($gene_content_extracted);
                        $gene_author = (string) $gene_entry->author->name;
                        $gene_contributor = (string) $gene_entry->contributor->name;
                        foreach ($gene_entry->link as $linkelement) {
                            if ($linkelement['rel'] == "alternate") {
                                $gene_alt_link = $linkelement['href'];
                            }
                        }
                        $html = file_get_html($gene_alt_link);
                        if ($html && is_object($html)) {
                            $title = $html->find('title', 0)->innertext;
                            foreach ($html->find('a') as $element) {
                                if (isset($element->name)) {
                                    if (preg_match("*mailto*", $element->href)) {
                                        $email_add = $element->href;
                                        $regex_pattern = "/<a href=\"(.*)\">(.*)<\/a>/";
                                        $email_address = preg_replace("*mailto:*", '', $email_add);
                                        $email_n = $element->outertext;
                                        preg_match($regex_pattern, $email_n, $matches);
                                        $email_name = $matches[2];
#									echo "----> " . $email_address . " -> " . $email_name . '<br>';
                                    }
                                }
                            }
                            $html->clear();
                            unset($html);
                        }

#					print "gene -> $gene $entrez_id $gene_name $chr_location $start $stop $ref_seq_genomic $ref_seq_mrna $ref_seq_build $gene_author $gene_contributor $email_address $email_name $gene_alt_link $title<br>";
                        $gene_insert = mysql_query("INSERT INTO lovd_genes (`gene`, `entrez_id`, `gene_desc`, `chr_location`, `start`, `stop`, `ref_seq_genomic`, `ref_seq_mrna`, `ref_seq_build`, `gene_author`, `gene_contributor`, `email`, `email_name`, `gene_alt_link`, `title`) values ('$gene', '$entrez_id', '$gene_name', '$chr_location', '$start', '$stop', '$ref_seq_genomic', '$ref_seq_mrna', '$ref_seq_build', '$gene_author', '$gene_contributor', '$email_address', '$email_name', '$gene_alt_link', '$title')", $out_con);
                        if (!$gene_insert) {
                            print "<br>Error executing query -> " . mysql_error() . "<br>\n";
                        }
                        $gene_insert_id = mysql_insert_id();
#					print "gene insert -> $gene_insert_id<br>";

                        $contact_insert = mysql_query("INSERT INTO lovd_to_contact (`gene`, `gene_author`, `gene_contributor`, `email`, `email_name`, `gene_alt_link`, `title`) values ('$gene', '$gene_author', '$gene_contributor', '$email_address', '$email_name', '$gene_alt_link', '$title')", $out_con);
                        if (!$contact_insert) {
                            print "<br>Error executing query -> " . mysql_error() . "<br>\n";
                        }
                        if (preg_match("/\/$/", $url)) {
                            $variants_url = $url . "api/rest.php/variants/$gene";
#						print "<h5>variant single -> $url $variants_url</h5>";
                        } else {
                            $variants_url = $url . "/api/rest.php/variants/$gene";
#						print "<h5>variant none -> $url $variants_url</h5>";
                        }
#					$variants_url = $url . "/api/rest.php/variants/$gene";
#					$variant_xml = simplexml_load_file($variants_url);
                        $variant_xml = simplexml_load_string(file_get_contents($variants_url));
                        if ($variant_xml === false) {
                            sleep(1);
                            $variant_xml = simplexml_load_file($variants_url);
                            if ($variant_xml === false) {
                                print "<h5>Couldn't open variants xml for $variants_url</h5>\n";
                            }
                        } else {
                            print "<h5>loaded $variants_url</h5>\n";
                        }

                        foreach ($variant_xml->entry as $variant_entry) {
                            foreach ($variant_entry->link as $linkelement) {
                                if ($linkelement['rel'] == "alternate") {
                                    $alt_link = $linkelement['href'];
#								print "alt -> $alt_link<br>";
                                }
                                if ($linkelement['rel'] == "self") {
                                    $self_link = $linkelement['href'];
#								print "self -> $self_link<br>";
                                }
                            }
                            $variant_content = $variant_entry->content;
#						print "VARIANTRAWCONTENT: $variant_content<br>";
                            preg_match('/symbol:(\S+)\s+id:(\S+)\s+position_mRNA:(.*)\s+position_genomic:(.*)\s+Variant\/DNA:(.*)\s+Variant\/DBID:(\S+)\s+Times_reported:(.*)/', $variant_content, $variant_content_extracted);
#						$gene_symbol = $variant_content_extracted[1];
                            $variant_id = $variant_content_extracted[2];
                            $pos_mrna = $variant_content_extracted[3];
                            $pos_genomic = $variant_content_extracted[4];
                            $variant_dna = $variant_content_extracted[5];
                            $variant_dbid = $variant_content_extracted[6];
                            $times_reported = $variant_content_extracted[7];
#						print "<br>START<br>";
#						print "$gene $gene_id $entrez_id $gene_symbol $gene_name $chr_location $start $stop $ref_seq_genomic $ref_seq_mrna $ref_seq_build $gene_author $gene_contributor $gene_alt_link $title $email_address $email_name $alt_link $self_link $variant_id $pos_mrna $pos_genomic $variant_dna $variant_dbid $times_reported<br>";
#						print "END<br><br>";
#						print "variant -> $gene $variant_id $variant_dna $variant_dbid $pos_mrna $pos_genomic $alt_link $self_link $times_reported<br>";
                            $variant_insert = mysql_query("INSERT INTO lovd_variants (`auto_gene`, `gene`, `variant_id`, `variant_dna`, `variant_dbid`, `pos_mrna`, `pos_genomic`, `alt_link`, `self_link`, `times_reported`) values ( '$gene_insert_id', '$gene', '$variant_id', '$variant_dna', '$variant_dbid', '$pos_mrna', '$pos_genomic', '$alt_link', '$self_link', '$times_reported')", $out_con);
                            if (!$variant_insert) {
                                print "<br>Error executing query -> " . mysql_error() . "<br>\n";
                            }
                            $variant_insert_id = mysql_insert_id();
#						print "variant insert -> $variant_insert_id<br>";
                            getVariants($variant_dna);
                        }
                    } else {
                        print "Couldn't find for $url\n";
                    }
                }
            }

#		print "code -> $http_code<br><br>";

            if ($c >= 1) {
#			exit;
            }
        }
    }

    function getVariants($hgvs_desc) {
        $uri = "https://oi.gene.le.ac.uk/variants.php?select_db=COL1A1&action=search_all&search_Variant%2FDNA=$hgvs_desc";
#		print "URI -----> $uri<br>";
    }

    function build_variant_to_phenotypes_table() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $variants_to_phenotypes = array();
        $this->load->model('phenotypes_model');
        $phenotypes = $this->phenotypes_model->getVariantsWithPhenotypes(); // Get all the variants and their phenotypes from phenotypes table
        foreach ($phenotypes as $phenotype) {
//			print_r($phenotype);
            $cafevariome_id = $phenotype['cafevariome_id'];
//			print "START -> " . $cafevariome_id . "<br />";
//			echo "pheno -> " . $phenotype['termName'] . "<br />";

            if (array_key_exists($cafevariome_id, $variants_to_phenotypes)) { // Check if this cafevariome_id has been used before (each variant can have multiple phenotypes)
                if (!in_array($phenotype['termName'], $variants_to_phenotypes[$cafevariome_id])) {
                    array_push($variants_to_phenotypes[$cafevariome_id], $phenotype['termName']);
//					print "ALREADY ADDED -> " . $phenotype['termName'] . "<br />";
                }
            } else { // First time the cafevariome_id has been processed so initialise the phenotypes data array for the id and add the term to the array
                $variants_to_phenotypes[$cafevariome_id] = array($phenotype['termName']);
//				print "FIRST ADD -> " . $phenotype['termName'] . "<br />";
            }

//			error_log("start -> " . $phenotype['cafevariome_id']);
            $term_id = $phenotype['termId'];
            $phenotype_parents = $this->phenotypes_model->getPhenotypeDagParentFromTermID($term_id); // Get the parents for this term ID
            if (!empty($phenotype_parents)) { // If there's a phenotype parent term present then loop through (each termid might have multiple parents)
//				print_r($phenotype_parents);
                foreach ($phenotype_parents as $phenotype_parent) {
                    $parent_term_id = $phenotype_parent['parentid'];
//					echo "phenop -> " . $phenotype_parent['termname'] . "<br />";
                    if (!in_array($phenotype_parent['termname'], $variants_to_phenotypes[$cafevariome_id])) {
                        array_push($variants_to_phenotypes[$cafevariome_id], $phenotype_parent['termname']);
//						print "ADDING " . $phenotype_parent['termname'] . "<br />";
                    }
                    while ($parent_term_id) { // While there's a parent termid present
                        $phenotype_parents = $this->phenotypes_model->getPhenotypeDagParentFromTermID($parent_term_id); // Get the parents for this termid
                        if (!empty($phenotype_parents)) { // There's parents present
//							print_r($phenotype_parents);
//							print "<br />";
                            foreach ($phenotype_parents as $phenotype_parent) { // Loop through the parent terms and add them to the final linking array (if not already present)
//								echo "pheno -> " . $phenotype_parent['termname'] . "<br />";
                                if (!in_array($phenotype_parent['termname'], $variants_to_phenotypes[$cafevariome_id])) {
                                    array_push($variants_to_phenotypes[$cafevariome_id], $phenotype_parent['termname']);
//									print "ADDING " . $phenotype_parent['termname'] . "<br />";
                                }
                                $parent_term_id = $phenotype_parent['parentid']; // Set the parent term id to the current one
                            }
                        } else {
                            $parent_term_id = false; // There's no more parents so setting the parent_term_id to false will break out of the while loop in the next iteration
                        }
                    }
                }
            }
        }

//		print_r($variants_to_phenotypes);
        $this->phenotypes_model->deleteVariantsToPhenotypesTable();
        // Go through non-redundant list of phenotypes for each cafevariome_id and insert each phenotype into a row linking it to the cafevariome_id
        foreach ($variants_to_phenotypes as $cafevariome_id => $phenotypes) {
            sort($phenotypes);
            foreach ($phenotypes as $p) {
                $insert_data = array('cafevariome_id' => $cafevariome_id, 'termName' => $p);
                $insert_id = $this->phenotypes_model->insertVariantsToPhenotypes($insert_data);
            }
        }
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

    function set_current_display_fields_tab() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        if ($this->input->post('tab')) {
            $tab = $this->input->post('tab');
            $tab = str_replace('#', '', $tab);
            $this->session->set_userdata('fields_tab', $tab);
        }
    }

    function set_current_maintenance_tab() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        if ($this->input->post('tab')) {
            $tab = $this->input->post('tab');
            $tab = str_replace('#', '', $tab);
            $this->session->set_userdata('maintenance_tab', $tab);
        }
    }

    function set_current_sharing_policy_in_session_for_display_fields_tab() {
        $sharing_policy = $this->input->post('sharing_policy');
//		error_log("sp -> " . $sharing_policy);
        $this->session->set_userdata('sharing_policy', $sharing_policy);
    }

    function dash_sources() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('sources_model');
        $this->data['variant_counts'] = $this->sources_model->countSourceEntries();
        $sources = $this->sources_model->getSourcesFull();
//		print_r($sources);
        $source_groups = array();
        foreach ($sources->result() as $source) {
//			echo $source->source_id;
            $source_group_data = $this->sources_model->getSourceGroups($source->source_id);
//			print_r($source_group_data);
//			print "group data -> " . $source_group_data['group_id'] . "<br />";
            if (!empty($source_group_data)) {
//				$source_groups[$source->source_id] = array( 'group_id' => $source_group_data['group_id'], 'group_description' => $source_group_data['group_description'] );
                $source_groups[$source->source_id] = $source_group_data;
            }
        }
        $this->data['source_groups'] = $source_groups;
        $this->data['sources'] = $sources;
        $sidebar['dashboard_title'] = "Sources";
        $this->data['dashboard_sidebar'] = $this->load->view("template/dashboard_sidebar", $sidebar, true); // Load the sidebar and pass as a string to the template data
        $this->_renderDashboard('admin/sources');
    }

    // Creates the jquery for a column highchart to be displayed on the stats page
    function _generateColumnHighchartJS($chart_title, $render_to, $x_axis_title, $x_axis_labels, $y_axis_title, $series_name, $series_data, $colour = NULL, $font_size = NULL, $rotation = NULL) {
        if (!isset($font_size)) {
            $font_size = "9px";
        }
        if (!isset($rotation)) {
            $rotation = "-45";
        }
        if (!isset($colour)) {
            $colour = "blue";
        }
        $js_data = "
		$(document).ready(function() { 
			var variantsChart = new Highcharts.Chart({
				chart: {
					renderTo: '$render_to',
					type: 'column',
					zoomType: 'xy'
				},
				title: {
					text: '$chart_title'
				},
				xAxis: {
					title: {
						text: '$x_axis_title'
					},
					categories: $x_axis_labels,
					labels: {
						rotation: $rotation,
						align: 'right',
						style: {
							fontSize: '$font_size',
							fontFamily: 'Verdana, sans-serif'
						}
					}
				},
				yAxis: {
					title: {
						text: '$y_axis_title'
					}
				},
				series: [{
					showInLegend: false,
					color: '$colour',
					name: '$series_name',
					data: $series_data
				}]
			});
		});
		";
        return ($js_data);
    }

    // Creates the jquery for a column highchart to be displayed on the stats page
    function _generatePieHighchartJS($chart_title, $render_to, $series_name, $pie_chart_data) {
        $js_data = "
		$(document).ready(function() { 
			var variantsChart = new Highcharts.Chart({
				chart: {
					renderTo: '$render_to',
					type: 'pie'
				},
				title: {
					text: '$chart_title'
				},
				series: [{
					type: 'pie',
					name: '$series_name',
					data: $pie_chart_data
				}]
			});
		});
		";
        return ($js_data);
    }

    function change_sharing_policy() {
        $this->load->model('sources_model');
        if ($this->ion_auth->in_group("curator")) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
            $user_id = $this->ion_auth->user()->row()->id;
            $source_id = $this->sources_model->getSourceIDFromName($this->input->post('source'));
            $can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
            if (!$can_curate_source) {
                show_error("Sorry, you are not listed as a curator for that particular source.");
            }
        } elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        if ($this->input->post('sharing_policy') && $this->input->post('source')) {
            $sharing_policy = $this->input->post('sharing_policy');
            $source = $this->input->post('source');

            // MySQL update
            $this->sources_model->updateSourceSharingPolicy($source, $sharing_policy);

            // ElasticSearch update (if ElasticSearch is enabled)
            // There's no bulk update API in ElasticSearch (yet) so need to get all IDs in the source then update the sharing_policy for each one
            if ($this->config->item('use_elasticsearch')) {
                $this->load->library('elasticsearch');
                $check_if_running = $this->elasticsearch->check_if_running();
                if (array_key_exists('ok', $check_if_running)) {
                    $ids = $this->sources_model->getAllVariantIDsInSource($source);
                    // Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
                    $es_index = $this->config->item('site_title');
                    $es_index = preg_replace('/\s+/', '', $es_index);
                    $es_index = strtolower($es_index);
                    $this->elasticsearch->set_index($es_index);
                    $this->elasticsearch->set_type("variants");
                    foreach ($ids as $id) {
                        $cafevariome_id = $id['cafevariome_id'];
//						error_log("id -> $cafevariome_id");
                        $update = array();
                        $update['doc'] = array('sharing_policy' => $sharing_policy);
                        $update = json_encode($update);
//						error_log("update $update");
                        $update_result = $this->elasticsearch->update($cafevariome_id, $update);
//						error_log("RESULT -> " . print_r($update_result, 1));
                        if (!$update_result) {
                            $update_result_flag = 0;
                        }
                    }
                }
            }
        }
    }

    function change_link() {
        $this->load->model('sources_model');
        if ($this->ion_auth->in_group("curator")) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
            $user_id = $this->ion_auth->user()->row()->id;
            $source_id = $this->sources_model->getSourceIDFromName($this->input->post('source'));
            $can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
            if (!$can_curate_source) {
                show_error("Sorry, you are not listed as a curator for that particular source.");
            }
        } elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        if ($this->input->post('link') && $this->input->post('source')) {
            $link = $this->input->post('link');
            $source = $this->input->post('source');

            // MySQL update
            $this->sources_model->updateLink($source, $link);

            // ElasticSearch update (if ElasticSearch is enabled)
            // There's no bulk update API in ElasticSearch (yet) so need to get all IDs in the source then update the sharing_policy for each one
            if ($this->config->item('use_elasticsearch')) {
                $this->load->library('elasticsearch');
                $check_if_running = $this->elasticsearch->check_if_running();
                if (array_key_exists('ok', $check_if_running)) {
                    $ids = $this->sources_model->getAllVariantIDsInSource($source);
                    // Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
                    $es_index = $this->config->item('site_title');
                    $es_index = preg_replace('/\s+/', '', $es_index);
                    $es_index = strtolower($es_index);
                    $this->elasticsearch->set_index($es_index);
                    $this->elasticsearch->set_type("variants");
                    foreach ($ids as $id) {
                        $cafevariome_id = $id['cafevariome_id'];
//						error_log("id -> $cafevariome_id");
                        $update = array();
                        $update['doc'] = array('source_url' => $link);
                        $update = json_encode($update);
//						error_log("update $update");
                        $update_result = $this->elasticsearch->update($cafevariome_id, $update);
//						error_log("RESULT -> " . print_r($update_result, 1));
                        if (!$update_result) {
                            $update_result_flag = 0;
                        }
                    }
                }
            }
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
            if ($is_email_present) {
                echo "The email specified already has a Cafe Variome account, this functionality is for inviting users who do not yet have a Cafe Variome account. In order to share data with an existing user you should add the user to the source group via the admin interface.";
            } else {
                $selected_group = $this->input->post('selected_group');
                $md5 = generateMD5();
//				error_log("-> $email $selected_group $md5");
                $result = $this->sources_model->register_and_add_to_group($md5, $email, $selected_group);
                if ($result) {
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
//					redirect('admin/sources', 'refresh');
                } else {
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
        if ($is_valid) {
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
            $this->_render('admin/share_request');
        } else {
            show_error("The md5 token is not valid or this sharing invite has already been processed.");
        }
    }

    function share_result() {
//		print_r($_POST);
        $this->load->model('sources_model');
        $md5 = $this->input->post('md5');
        $query = $this->sources_model->is_md5_valid($md5);
        $is_valid = $query->num_rows();
        if (!$is_valid) {
            show_error("The md5 token is not valid or this sharing invite has already been processed.");
        }
//		$user = (array) $query->row();
        $user = $query->row();
        $id = $user->id;

        if ($this->input->post('result') == "confirm") {
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
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'company' => $this->input->post('company'),
                    'orcid' => $this->input->post('orcid')
                );

                //update the password if it was posted
                if ($this->input->post('password')) {
                    $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                    $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

                    $data['password'] = $this->input->post('password');
                }

                if ($this->form_validation->run() === TRUE) {
                    $this->ion_auth->update($user->id, $data);
                    $this->session->set_flashdata('message', "User Saved");
                    $activation = $this->ion_auth->activate($user->id);
                    $user = $this->ion_auth->user($user->id)->row();
                    $session_data = array(
                        'identity' => 'email',
                        'username' => $user->username,
                        'email' => $user->email,
                        'user_id' => $user->id, //everyone likes to overwrite id so we'll use user_id
                        'old_last_login' => $user->last_login
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
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name', $user->first_name),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name', $user->last_name),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email', $user->email),
                'disabled' => 'true'
            );
            $this->data['company'] = array(
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company', $user->company),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password'
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password'
            );
            $this->data['orcid'] = array(
                'name' => 'orcid',
                'id' => 'orcid',
                'type' => 'text',
                'value' => $this->form_validation->set_value('orcid', $user->orcid),
            );
            $this->_render('auth/share_invite_edit_profile');
        } elseif ($this->input->post('result') == "refuse") {
//			echo "refuse";
            $this->ion_auth->delete_user($id); // Delete the user and remove from the group they were added to
            $this->_render('auth/share_invite_refuse');
            // TODO: redirect to a view that informs that refusal was successful and if they want to register they'll have to contact the admin again or create an account
        }
    }

    function change_source_status() {
        $this->load->model('sources_model');
        if ($this->ion_auth->in_group("curator")) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
            $user_id = $this->ion_auth->user()->row()->id;
            $source_id = $this->input->post('source_id');
            $can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
            if (!$can_curate_source) {
                show_error("Sorry, you are not listed as a curator for that particular source.");
            }
        } elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        if ($this->input->post('status') && $this->input->post('source_id')) {
            $status = $this->input->post('status');
            $source_id = $this->input->post('source_id');
            $this->sources_model->updateSourceStatus($source_id, $status);
        }
    }

    public function crm() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('crm_model');
        $this->data['statuses'] = array('Attempted to contact', 'Collaborator', 'Contact in future', 'Contacted', 'Junk lead', 'Lost lead', 'Not contacted', 'Not interested', 'Piloting system', 'Shown interest');
        $this->data['crm'] = $this->crm_model->getCRMLeads();
        $this->data['email_templates'] = $this->crm_model->getCRMEmailTemplates();

        $this->_render('admin/crm');
    }

    public function crm_track($id) {
        $path = FCPATH . '/resources/images/cafevariome/1x1_transparent.png';
//		echo $path;

        $this->load->model('crm_model');
        $this->crm_model->incrementCRMLeadEmailCount($id);
//		error_log("id -> $id");

        $this->load->helper('file');
        // Dynamically display the 1x1 pixel image
        $this->output->set_content_type(get_mime_by_extension($path));
        $this->output->set_output(file_get_contents($path));
    }

    public function crm_link($link_id, $lead_email_id) {
        $this->load->model('crm_model');
        $link_url = $this->crm_model->getCRMLinkByID($link_id);
        $data = array('link_id' => $link_id, 'lead_email_id' => $lead_email_id);
        $insert_id = $this->crm_model->insertCRMLeadLinkTrack($data);
//		error_log("link -> $link_url -> $link_id -> $lead_id");
        redirect($link_url);
    }

    public function change_crm_lead_status() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $data = array();
        if ($this->input->post('add_comment') == "yes") {
            $datetime = date('d-m-Y H:i');
            $data = array('status' => $status, 'comment' => "Emailed on $datetime");
//			error_log("add comment");
        } else {
            $data = array('status' => $status);
//			error_log("add just status");
        }
        $this->load->model('crm_model');
//		error_log("id -> $id status -> $status");
        $update_result = $this->crm_model->updateCRMLeadByID($id, $data);
    }

    public function change_crm_comment($comment) {
        $datetime = date('d-m-Y_H-i-s');
        $data = array('comment' => "Emailed on $datetime");
        $this->load->model('crm_model');
//		error_log("id -> $id status -> $status");
        $update_result = $this->crm_model->updateCRMLeadByID($id, $data);
    }

    public function change_crm_lead_via_xeditable() {
//		error_log(print_r($_POST, 1));
        $id = $this->input->post('pk');
        $data = array($this->input->post('name') => $this->input->post('value'));
        $this->load->model('crm_model');
        $update_result = $this->crm_model->updateCRMLeadByID($id, $data);
    }

    public function email_crm_leads() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('crm_model');
        $ids = $this->input->post('ids');
        $email_text_template = $this->input->post('email_text');
        $email_comment = $this->input->post('email_comment');
        $status = $this->input->post('status');
//		error_log("leads -> " . print_r($ids, TRUE));
        $link_array = array(); // Array for storing the link id and the associated link (done in db lookup)
        foreach ($ids as $id) {
            $datetime = date('d-m-Y H:i:s');
            $lead = $this->crm_model->getCRMLeadByID($id);
//			error_log(print_r($lead, TRUE));
            $email_text = str_replace('%name', $lead['contact_name'], $email_text_template); // Replace the %name in email with the actual name of the person sending to
            $data = array('lead_id' => $lead['id'], 'email' => $email_text, 'date_sent' => $datetime);
            $lead_email_insert_id = $this->crm_model->insertCRMLeadEmail($data);


            if (preg_match_all("/%link_\d+/", $email_text, $matches)) {
                error_log(print_r($matches, 1));
                foreach ($matches[0] as $match) {
//					error_log("match -> $match");
                    $link_id = str_replace('%link_', '', $match);
//					error_log("link_id -> $link_id");
                    if (array_key_exists($link_id, $link_array)) {
                        $link_url = $link_array[$link_id];
                    } else {
                        $link_url = $this->crm_model->getCRMLinkByID($link_id);
//						error_log("url -> $link_id -> $link_url");
                        $link_array[$link_id] = $link_url;
                    }
                    $crm_link = base_url() . 'admin/crm_link/' . $link_id . "/" . $lead_email_insert_id;
                    $link = "<a href='$crm_link'>$link_url</a>";
//					error_log("link -> $link");
                    $email_text = str_replace($match, $link, $email_text); // Replace the %name in email with the actual name of the person sending to
                }
//				error_log("email -> $email_text");
            }

            $body = $email_text . '<img src="' . base_url() . 'admin/crm_track/' . $lead_email_insert_id . '" />';
//			cafevariomeEmail("ol8@le.ac.uk", "Owen Lancaster", $lead['email'], "Cafe Variome Collaboration", $body);
            cafevariomeEmail("ol8@le.ac.uk", "Owen Lancaster", "owenlancaster@gmail.com", "Data discovery platform for genetic variants (Cafe Variome)", $body);
            $has_been_contacted_before = $this->crm_model->checkIfCRMLeadHasBeenContacted($id);

//			error_log("has? $has_been_contacted_before");
            if (!$has_been_contacted_before) {
                $data = array('date_initial_contact' => $datetime);
                $update_result = $this->crm_model->updateCRMLeadByID($id, $data);
            }

            $data = array('date_last_contact' => $datetime);
            $update_result = $this->crm_model->updateCRMLeadByID($id, $data);
            if ($email_comment != "") {
//				error_log($email_comment);
                $data = array('comment' => $email_comment);
                $update_result = $this->crm_model->updateCRMLeadByID($id, $data);
            }

            if ($status != "") {
//				error_log($status);
                $data = array('status' => $status);
                $update_result = $this->crm_model->updateCRMLeadByID($id, $data);
            }
        }
    }

    public function delete_crm_leads() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        $this->load->model('crm_model');
        $ids = $this->input->post('ids');
//		error_log("leads -> " . print_r($ids, TRUE));
        foreach ($ids as $id) {
            $this->crm_model->deleteCRMLeadByID($id);
        }
    }

    public function get_crm_email_template() {
        $this->load->model('crm_model');
        $template_id = $this->input->post('template_id');
//		error_log("id -> $id status -> $status");
        $email_template = $this->crm_model->getCRMEmailTemplateByID($template_id);
        echo $email_template['template'];
    }

    public function generate_mock_data($limit, $source = "diagnostic_leic") {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

//		$this->load->model('general_model');
//		$genes = $this->general_model->getRandomRows('genes', $limit);
//		$refseqs = $this->general_model->getRandomRows('refseq', $limit);

        $contents = file_get_contents('http://compbio.charite.de/hudson/job/hpo.annotations.monthly/lastStableBuild/artifact/annotation/ALL_SOURCES_ALL_FREQUENCIES_genes_to_phenotype.txt');
        $records = explode("\n", $contents);
        $hpo_genes_to_phenotypes = array();
        $c = 0;
        foreach ($records as $record) {
            $c++;
            if ($c != 1) {
//				print "$record<br />";
                $row = explode("\t", $record);
                if (array_key_exists(1, $row)) {
                    $gene_symbol = $row[1];
                    $phenotype = $row[2];
                    $hpo_id = $row[3];
                    $hpo_genes_to_phenotypes[$gene_symbol][] = array('hpo_term' => $phenotype, "hpo_id" => $hpo_id);
//					print_r($hpo_genes_to_phenotypes);
//					print "<br />";
//					print "$gene_symbol -> $phenotype<br />";
                }
            }
        }

        mysql_connect("genome-mysql.cse.ucsc.edu", "genome", "");
        @mysql_select_db('hg19') or die("Unable to select database");

        $query = "SELECT name as refseq, name2 as gene, chrom, txStart, txEnd, exonStarts, exonEnds, exonCount FROM refGene WHERE name like '%NM%' ORDER BY RAND() LIMIT $limit";
//		$query = "SELECT name as refseq, name2 as gene, chrom, txStart, txEnd, exonStarts, exonEnds, exonCount FROM refGene WHERE name = 'NM_001178133'";
//		$query = "SELECT name as refseq, name2 as gene, chrom, txStart, txEnd, exonStarts, exonEnds, exonCount FROM refGene WHERE name = 'NM_001282941'";
        $result = mysql_query($query);

        while ($row = mysql_fetch_assoc($result)) {
            $bases = array('A', 'T', 'G', 'C');
            $chr = $row['chrom'];
            $gene = $row['gene'];
            $refseq = $row['refseq'];
            print "Starting $refseq<br />";
            $exon_count = $row['exonCount'];
            $exon_starts = explode(",", $row['exonStarts']);
            $exon_ends = explode(",", $row['exonEnds']);
//			print_r($exon_starts);
//			print "<br />";
            $total_exons = count($exon_starts) - 1; // Minus one because the last element of the exon_starts array is empty due to the trailing comma in UCSC
//			print "EXON $exon_count vs $total_exons<br />";

            $total_length = 0;
            for ($i = 0; $i <= $total_exons - 1; $i++) { // Minus one because the array is indexed from 0
//				print "-> " . $exon_starts[$i] . " -> " . $exon_ends[$i] . "<br />";
                $length = $exon_ends[$i] - $exon_starts[$i];
                $total_length += $length - 1;
            }

            $position = rand(1, $total_length);
            $first_base = "A";
            $second_base = "G";
            $hgvs = "c.$position" . "$first_base>$second_base";
//			$client = $this->initializeMutalyzer();
            $this->load->library('mutalyzer');
            $mutalyzer_check_result = @$this->mutalyzer->runMutalyzer($refseq, $hgvs);
//			print "1st<br />";
//			print_r($mutalyzer_check_result);
//			print "<br />";
            $message = $mutalyzer_check_result['message'];
            if (preg_match('%.*?using (.*?)\. Pleas.*?%i', $message, $matches)) {
                $refseq = $matches[1];
            } else if (preg_match('%.*?found ([ATCG]) instead%i', $message, $matches)) {
//				print_r($matches);
//				print "<br />";
                $first_base = $matches[1];
                $key = array_search($first_base, $bases);
                if (false !== $key) {
                    unset($bases[$key]);
                    $bases = array_values($bases);
                }
//				print_r($bases);
//				print "<br />";
                $second_base = $bases[rand(0, 2)];
                $hgvs = "c.$position" . "$first_base>$second_base";

                $mutalyzer_check_result = @$this->mutalyzer->runMutalyzer($refseq, $hgvs);
//				print "3rd<br />";
//				print_r($mutalyzer_check_result);
//				print "<br />";
                $message = $mutalyzer_check_result['message'];
                if (preg_match('%.*?using (.*?)\. Pleas.*?%i', $message, $matches)) {
                    $refseq = $matches[1];
                }
            } else {
                print_r($message);
                print "<br />";
            }

            $mutalyzer_check_result = @$this->mutalyzer->runMutalyzer($refseq, $hgvs);
            if (array_key_exists('start', $mutalyzer_check_result)) {
                $start = $mutalyzer_check_result['start'];
                $end = $mutalyzer_check_result['end'];
                if (array_key_exists($gene, $hpo_genes_to_phenotypes)) {
//					print "-> ";
//					print_r($hpo_genes_to_phenotypes[$gene]);
//					print "<br />";

                    $phenotypes = array();
                    $total_phenotypes_for_gene = count($hpo_genes_to_phenotypes[$gene]);
//					print "TOTAL PHENOTYPES -> $total_phenotypes_for_gene<br />";
                    if ($total_phenotypes_for_gene > 0) {

                        $this->load->model('sources_model');
                        $variant_data = array(
                            "source" => $source,
                            "laboratory" => $source,
                            "gene" => $gene,
                            "ref" => $refseq,
                            "hgvs" => $hgvs,
                            "sharing_policy" => 'openAccess',
                            "location_ref" => $chr,
                            "start" => $start,
                            "end" => $end,
                            "build" => 'hg19',
                            "comment" => 'Mock data',
                            "mutalyzer_check" => '1'
                        );
                        $cafevariome_id = $this->sources_model->insertVariants($variant_data);
                        print "INSERTED $cafevariome_id<br />";
//						print "$refseq\t$hgvs\t$gene\t$chr\t$start\t$end\t1<br />";
//						print "total pheno -> $total_phenotypes_for_gene<br />";
                        $number_of_phenotypes_to_select = rand(1, $total_phenotypes_for_gene); // Randomly pick how many phenotypes to select
                        print "Adding $number_of_phenotypes_to_select phenotypes<br />";
                        for ($j = 1; $j <= $number_of_phenotypes_to_select; $j++) {
                            $current_number_phenotypes_in_array = count($hpo_genes_to_phenotypes[$gene]); // Get current number of phenotypes in array
                            $random_phenotype_array_element = rand(0, $current_number_phenotypes_in_array - 1); // Select a random phenotype array element
                            $phenotype_array = $hpo_genes_to_phenotypes[$gene][$random_phenotype_array_element]; // Get the phenotype data for the random phenotype element
                            $hpo_term = $phenotype_array['hpo_term'];
                            $hpo_id = $phenotype_array['hpo_id'];
                            $hpo_id = str_replace(':', '_', $hpo_id);
                            $hpo_id = "http://purl.obolibrary.org/obo/" . $hpo_id;
//							print "PHENO -> $hpo_term -> $hpo_id ($random_phenotype_array_element)<br />";						
//							print "Search for $random_phenotype_array_element in ";
//							print_r($hpo_genes_to_phenotypes[$gene]);
//							print "<br />";
                            unset($hpo_genes_to_phenotypes[$gene][$random_phenotype_array_element]); // Delete this phenotype element from the array
                            $hpo_genes_to_phenotypes[$gene] = array_values($hpo_genes_to_phenotypes[$gene]); // Reset the index order of the array elements for the next random phenotype selection
//							print "KEY LOOP: <br />";
//							print_r($hpo_genes_to_phenotypes[$gene]);
//							print "<br />";
                            $phenotype_data = array('cafevariome_id' => $cafevariome_id, 'sourceId' => "HP", 'termId' => $hpo_id, 'termName' => $hpo_term);
                            $this->sources_model->insertPhenotypes($phenotype_data);
                        }
                    }
                } else {
                    "No phenotypes available for the gene this variant is in<br />";
                }
            } else {
                print "No location data for this entry<br />";
            }
//			print "<br />";
        }



//		$ucsc_ids= "'uc007aet.1', 'uc007aeu.1', 'uc007aev.1'";
//		$gene_query = "SELECT DISTINCT knownGene.name, kgXref.geneSymbol
//		FROM hg19.knownGene INNER JOIN hg19.kgXref ON
//		knownGene.name = kgXref.kgID
//		WHERE knownGene.name IN ($ucsc_ids);";
//		$result = mysql_query($gene_query);
//		while ($row = mysql_fetch_assoc($result)) {
//			print "VARIATION -> ";
//			print_r($row);
//		}
//		mysql_connect("useastdb.ensembl.org","anonymous","");
//		@mysql_select_db('homo_sapiens_variation_74_37') or die( "Unable to select database");
//		$query = "SELECT * FROM `variation` AS r1 JOIN (SELECT (RAND() * (SELECT MAX(variation_id) FROM `variation`)) AS variation_id) AS r2 WHERE r1.variation_id >= r2.variation_id ORDER BY r1.variation_id ASC LIMIT $limit";
//		$result = mysql_query($query);
//		while ($row = mysql_fetch_assoc($result)) {
//			print "VARIATION -> ";
//			print_r($row);
//			print "<br /><br />";
//			$variation_id = $row['variation_id'];
//			$query_vid = "SELECT * FROM `variation_feature` WHERE variation_id = $variation_id";
//			$result_vid = mysql_query($query_vid);
//			while ($row = mysql_fetch_assoc($result_vid)) {
//				print "VARIATION FEAUTRE -> ";
//				print_r($row);
//				print "<br /><br />";
//				$variation_feature_id = $row['variation_feature_id'];
//				$query_vfid = "SELECT * FROM `transcript_variation` WHERE variation_feature_id = $variation_feature_id";
//				$result_vfid = mysql_query($query_vfid);
//				while ($row = mysql_fetch_assoc($result_vfid)) {
//					print "TRANSCRIPT VARIATION FEAUTRE -> ";
//					print_r($row);
//					print "<br /><br />";
//				}
//			}
//		}
        mysql_close();
    }

    public function uniquename_check($name = NULL) {
        $this->load->model('sources_model');
        $returned_name = $this->sources_model->checkSourceExists($name);
        if (!$returned_name) {
//			error_log("true");
            return TRUE;
        } else {
            $this->form_validation->set_message('uniquename_check', 'The %s field must be unique (there is already a source with that name)');
//			error_log("false");
            return FALSE;
        }
    }

    function rss_check($feed) {
        if ($feed == "") { // Allows no feed to be specified - means the feed box will be removed from the home page
            return TRUE;
        }
        if ($feed == "local") { // Local specified so use the internal RSS feed system
            $feed = $this->config->item('base_url') . 'feed';
        }
        $content = @file_get_contents($feed);
//		error_log("trying $feed");
        try {
            $rss = new SimpleXmlElement($content);
        } catch (Exception $e) { /* the data provided is not valid XML */
            $this->form_validation->set_message('rss_check', 'The rss field cannot be validated');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Validate URL format
     *
     * @access  public
     * @param   string
     * @return  string
     */
    function valid_url_format($str) {
        $pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
        if (!preg_match($pattern, $str)) {
            $this->form_validation->set_message('valid_url_format', 'The URL you entered is not correctly formatted.');
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Validates that a URL is accessible. Also takes ports into consideration. 
     * Note: If you see "php_network_getaddresses: getaddrinfo failed: nodename nor servname provided, or not known" 
     *          then you are having DNS resolution issues and need to fix Apache
     *
     * @access  public
     * @param   string
     * @return  string
     */
    function url_exists($url) {
        $url_data = parse_url($url); // scheme, host, port, path, query
        if (!fsockopen($url_data['host'], isset($url_data['port']) ? $url_data['port'] : 80)) {
            $this->form_validation->set_message('url_exists', 'The URL you entered is not accessible.');
            return FALSE;
        }

        return TRUE;
    }

    // Perform a custom "ping" on the url (ping api function is in the federated.php controller)
    function node_ping($url) {
        $url = $this->add_http($url);
//		error_log("url -> " . $url);
        $url = $url . "/federated/node_ping";
        $content = @file_get_contents($url);
        if ($content) {
            error_log("content -> " . $content);
            $xml = simplexml_load_string($content);
//			error_log(print_r($xml, 1));
            $ping_result = $xml->item;
            if ($ping_result == "ping!") {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $this->form_validation->set_message('node_ping', 'The node could not be contacted.');
            return FALSE;
        }
    }

    function is_node_name_unique($node_name) {
        $this->load->model('federated_model');
        $node_exists = $this->federated_model->checkNodeExists($node_name);
        if ($node_exists) {
            $this->form_validation->set_message('is_node_name_unique', 'The node name already exists.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function is_node_uri_unique($node_uri) {
        $this->load->model('federated_model');
        $node_uri = urlencode($node_uri);
        $node_exists = $this->federated_model->checkURIExists($node_uri);
        if ($node_exists) {
            $this->form_validation->set_message('is_node_uri_unique', 'The node URI already exists.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function is_node_key_unique($node_key) {
        $this->load->model('federated_model');
        $node_exists = $this->federated_model->checkNodeKeyExists($node_key);
        if ($node_exists) {
            $this->form_validation->set_message('is_node_key_unique', 'The node key already exists.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_http($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    function remove_http($url) {
        $disallowed = array('http://', 'https://');
        foreach ($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }

    function generateMD5Node() {
        $mdstring = md5(uniqid(rand(), true));
        echo $mdstring;
    }

    public function discovery_denied() {
        $this->_render('pages/discovery_denied');
    }

    public function redirect_to_elastic_search() {
        $this->session->set_userdata(array('settings_tab' => "maintenance"));
        $this->_render("admin/settings");
    }

}
