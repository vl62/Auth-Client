<?php

require(APPPATH . '/libraries/REST_Controller.php');

class Stats extends REST_Controller {
	function loginstats_post() {
		$this->load->model('stats_model');
		$data = array( 'baseurl' => $this->post('baseurl'), 'ip' => $this->post('ip'), 'username' => $this->post('username'), 'datetime' => $this->post('datetime') );
		$this->stats_model->insertLoginData($data);
	}

	function apistats_post() {
		$this->load->model('stats_model');
		$data = array( 'ip' => $this->post('ip'), 'datetime' => $this->post('datetime'), 'uri' => $this->post('uri') );
		$this->stats_model->insertAPIData($data);
	}
	
	function searchstats_post() {
		$this->load->model('stats_model');
		$data = array( 'ip' => $this->post('ip'), 'datetime' => $this->post('datetime'), 'term' => $this->post('term'), 'user' => $this->post('username'), 'source' => $this->post('source') );
		$this->stats_model->insertSearchData($data);
	}
	
	function variantstats_post() {
		$this->load->model('stats_model');
		$data = array( 'ip' => $this->post('ip'), 'datetime' => $this->post('datetime'), 'term' => $this->post('term'), 'source' => $this->post('source'), 'sharing_policy' => $this->post('sharing_policy'), 'format' => $this->post('format') );
		$this->stats_model->insertVariantData($data);
	}
	
	function variantcountstats_post() {
		$this->load->model('stats_model');
		$cafevariome_id = $this->post('cafevariome_id');
//		error_log("increase -> $cafevariome_id");
		$this->stats_model->updateVariantCount($cafevariome_id);
	}
	
	function registrationstats_post() {
		$this->load->model('stats_model');
		$data = array( 'baseurl' => $this->post('baseurl'), 'ip' => $this->post('ip'), 'username' => $this->post('username'), 'email' => $this->post('email'), 'datetime' => $this->post('datetime') );
		$this->stats_model->insertRegistrationData($data);
	}
	
}

?>
