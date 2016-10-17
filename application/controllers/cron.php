<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cron extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function get_new_logs($date_time) {
		error_log($date_time);
		$this->load->model('network_model');
		echo json_encode($this->network_model->get_new_logs($date_time));
	}

	public function get_derids() {
		$this->load->model('network_model');
		echo json_encode($this->network_model->get_derids());
	}
}