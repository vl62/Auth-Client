<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Das extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('sources_model');
		$this->load->model('search_model');
	}

	public function index(){
		if ( ! $this->config->item('dasigniter')) {
			show_error("DASIgniter is currently not enabled in this Cafe Variome instance");
		}
		$this->load->model('sources_model');
		$this->data['variant_counts'] = $this->sources_model->countSourceEntries();
		$sources = $this->sources_model->getSourcesFullOnline();
		$this->data['sources'] = $sources;
		$this->_render('das/home');
	}

	public function sources() {
		if ( ! $this->config->item('dasigniter')) {
			show_error("DASIgniter is currently not enabled in this Cafe Variome instance");
		}
		$this->data['variant_counts'] = $this->sources_model->countSourceEntries();
		$sources = $this->sources_model->getSourcesFullOnline();
		$data = array();
		$data['sources'] = $sources;
		// Set headers as specified in DAS protocol - http://www.biodas.org/documents/spec-1.6.html#response
		// TODO: Set status code automatically based on whether the request is successful - i.e. catch any errors and set correct status
		$this->output->set_header("Content-Type: text/xml");
		$this->output->set_header("X-DAS-Version: DAS/1.6");
		$this->output->set_header("X-DAS-Status: 200");
		$this->output->set_header("X-DAS-Capabilities: features/1.0");
		$this->output->set_header("X-DAS-Server: DASIgniter");
		$this->output->set_header("Access-Control-Allow-Origin: *");
		$this->output->set_header("Access-Control-Expose-Header: X-DAS-Verion, X-DAS-Status, X-DAS-Capabilities, X-DAS-Server");
		
		// Need to create this here and pass to view - problems with writing these tags directly in the view
		$xml_init = "<?xml version='1.0' standalone=\"no\" ?>";
		$stylesheet_init = "<?xml-stylesheet type=\"text/xsl\" href=\"" . base_url() . "/resources/stylesheets/xsl_sources.xsl" . "\"?>";
		$data['xml_init'] = $xml_init;
		$data['stylesheet_init'] = $stylesheet_init;
		
		$this->load->view('das/sources', $data);
	}
	
	// Everything is routed to this function unless it's one of the ones in routes.php configuration
	function source($source, $type = NULL) {
		if ( ! $this->config->item('dasigniter')) {
			show_error("DASIgniter is currently not enabled in this Cafe Variome instance");
		}

		if ( isset($type)) {
			if ( $type == "features") {
				if ( $this->input->get('segment') ) {
					$this->features($source, $this->input->get('segment'));
				}
			}
		}
		else {
			$data = array();
			$data['source'] = $source;
			$this->output->set_header("Content-Type: text/xml");
			$this->output->set_header("X-DAS-Version: DAS/1.6");
			$this->output->set_header("X-DAS-Status: 200");
			$this->output->set_header("X-DAS-Capabilities: features/1.0");
			$this->output->set_header("X-DAS-Server: DASIgniter");
			$this->output->set_header("Access-Control-Allow-Origin: *");
			$this->output->set_header("Access-Control-Expose-Header: X-DAS-Verion, X-DAS-Status, X-DAS-Capabilities, X-DAS-Server");

			// Need to create this here and pass to view - problems with writing these tags directly in the view
			$xml_init = "<?xml version='1.0' standalone=\"no\" ?>";
			$stylesheet_init = "<?xml-stylesheet type=\"text/xsl\" href=\"" . base_url() . "/resources/stylesheets/xsl_sources.xsl" . "\"?>";
			$data['xml_init'] = $xml_init;
			$data['stylesheet_init'] = $stylesheet_init;
			
			$this->load->view('das/source', $data);
		}
	}
	
	function features ($source, $segment) {
		if ( ! $this->config->item('dasigniter')) {
			show_error("DASIgniter is currently not enabled in this Cafe Variome instance");
		}

		$data = array();
//		$locations = array();
		$locations = $this->_splitRegion($segment);
//		error_log("locations -> " . print_r($locations, 1));
		$variants_openAccess = $this->search_model->getVariantsForRegion($locations, $source, 'openAccess');
		$variants_linkedAccess = $this->search_model->getVariantsForRegion($locations, $source, 'linkedAccess');
		$variants = array_merge($variants_openAccess, $variants_linkedAccess);
		$this->output->set_header("Content-Type: text/xml");
		$this->output->set_header("X-DAS-Version: DAS/1.6");
		$this->output->set_header("X-DAS-Status: 200");
		$this->output->set_header("X-DAS-Capabilities: features/1.0");
		$this->output->set_header("X-DAS-Server: DASIgniter");
		$this->output->set_header("Access-Control-Allow-Origin: *");
		$this->output->set_header("Access-Control-Expose-Header: X-DAS-Verion, X-DAS-Status, X-DAS-Capabilities, X-DAS-Server");
		$data['segment'] = $segment;
		$data['locations'] = $locations;
		$data['variants'] = $variants;
		$data['source'] = $source;
		
		// Need to create this here and pass to view - problems with writing these tags directly in the view
		$xml_init = "<?xml version='1.0' standalone=\"no\" ?>";
		$stylesheet_init = "<?xml-stylesheet type=\"text/xsl\" href=\"" . base_url() . "/resources/stylesheets/xsl_features.xsl" . "\"?>";
		$data['xml_init'] = $xml_init;
		$data['stylesheet_init'] = $stylesheet_init;
		
		$this->load->view('das/features', $data);	
	}
	
	function dsn () {
		if ( ! $this->config->item('dasigniter')) {
			show_error("DASIgniter is currently not enabled in this Cafe Variome instance");
		}

		$sources = $this->sources_model->getSourcesFullOnline();
		$data = array();
		$data['sources'] = $sources;
		$this->output->set_header("Content-Type: text/xml");
		$this->output->set_header("X-DAS-Version: DAS/1.6");
		$this->output->set_header("X-DAS-Status: 200");
		$this->output->set_header("X-DAS-Capabilities: features/1.0");
		$this->output->set_header("X-DAS-Server: DASIgniter");
		$this->output->set_header("Access-Control-Allow-Origin: *");
		$this->output->set_header("Access-Control-Expose-Header: X-DAS-Verion, X-DAS-Status, X-DAS-Capabilities, X-DAS-Server");
		
		// Need to create this here and pass to view - problems with writing these tags directly in the view
		$xml_init = "<?xml version='1.0' standalone=\"no\" ?>";
		$stylesheet_init = "<?xml-stylesheet type=\"text/xsl\" href=\"" . base_url() . "/resources/stylesheets/xsl_dsn.xsl" . "\"?>";
		$data['xml_init'] = $xml_init;
		$data['stylesheet_init'] = $stylesheet_init;	
		
		$this->load->view('das/dsn', $data);			
	}

	public function stylesheet(){
		if ( ! $this->config->item('dasigniter')) {
			show_error("DASIgniter is currently not enabled in this Cafe Variome instance");
		}
		$mtype = "text/css";
		header("Content-Type: $mtype");
		$this->load->view('das/stylesheet');
	}
	
	private function _splitRegion($region) {
		$pieces = explode(":", $region); // Split region into chr and start/ends
		$chr = $pieces[0];
		$locations = array();
		if (preg_match('/\,/', $pieces[1])) { // start/end is delimited by , so split on this
			$positions = explode(",", $pieces[1]);
//			print_r($positions);
			$start = $positions[0];
			$end = $positions[1];
		}
//		else { // start/end is delimited by - so split on this
//			$positions = explode("-", $pieces[1]);
//			$start = $positions[0];
//			$end = $positions[1];
//		}
		$locations['chr'] = "chr" . $chr;
		$locations['start'] = $start;
		$locations['end'] = $end;
		return $locations;
	}

}

?>
