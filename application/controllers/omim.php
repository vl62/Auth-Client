<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Temporary controller to replicate the for old API call to OMIM which is still used by Gensearch
// This call has been moved to the main API controller for CV central 

class Omim extends MY_Controller {
	public function genesymbol($gene, $format = 'tab') {
//		if ( $this->config->item('cafevariome_central') ) {
			$this->load->model("general_model");
			$omim_data = $this->general_model->getOMIMFromGene($gene);
//			print_r($omim_data);
			
			if ( strtolower($format) == "json") {
				$this->output->set_content_type('application/json')->set_output(json_encode($omim_data));
			}
			elseif ( strtolower($format) == "tab") {
				$this->output->set_header("Content-Type: text/plain");
				foreach ( $omim_data as $omim ) {
					print $omim['gene'] . "\t" . $omim['disorder'] . "\t" . $omim['omim_id'] . "\n";
				}
			}

//		}
//		else {
//			$this->response(array("This is not Cafe Variome Central"));
//		}
	}
}