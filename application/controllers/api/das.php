<?php

require(APPPATH . '/libraries/REST_Controller.php');

class Das extends REST_Controller {

//	function dasadd_post() {
//		if ( $this->config->item('dasigniter')) {
//			$this->load->model('sources_model');
//			error_log("source -> " . $this->post('source') . " uri -> " . $this->post('uri'));
//			$data = array( 'name' => $this->post('source'), 'uri' => $this->post('uri'), 'description' => $this->post('description'), 'type' => 'das', 'status' => 'online' );
//			$insert_id = $this->sources_model->insertSource($data);
////			error_log("insert -> " . $insert_id);
//		}
//		else {
//			$this->response("DASIgniter is not enabled.");
//		}
//	}
//	
//	function dasdelete_post() {
//		if ( $this->config->item('dasigniter')) {
//			$this->load->model('sources_model');
//			error_log("source -> " . $this->post('source'));
//			$source_name = $this->post('source');
//			$insert_id = $this->sources_model->deleteSourceByName($source_name);
//		}
//		else {
//			$this->response("DASIgniter is not enabled.");
//		}
//	}
	
}

?>
