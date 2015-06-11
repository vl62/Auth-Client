<?php

require(APPPATH . '/libraries/REST_Controller.php');

//class Federated extends REST_Controller {
class Federated extends REST_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('federated_model');
	}
	
	// Federated search count variants in source using specified search term (only available if federated is enabled in settings)
	function variantcount_post() {
		if ($this->config->item('federated')) {
			
			if ($this->post('term')) {
				$this->load->model('sources_model');
				$this->load->model('search_model');
				$term = $this->post('term');
				$source = $this->post('source');
//				$grouping_type = $this->post('grouping_type');
				$source_info = $this->sources_model->getSource($source);
				$type = $source_info['type'];
//				echo "type -> $type | source -> $source | term -> $term";
				if (preg_match('/chr\S+:\d+\-|\.\.\d+/', $term)) { // Match chromosome region regex
					$locations = $this->_splitRegion($term);
//					error_log("term -> " . $term . " -> source -> " . $source);
					$counts = $this->search_model->countVariantsForRegion($locations, $source);
//					error_log(print_r($counts, 1));
					$this->response($counts, 200);
				}
				elseif (preg_match('/N\S+_\S+\:\S+/', $term)) { // Match RefSeq and HGVS
//					print "hgvs and ref<br />";
					$ref_hgvs = $this->_splitRefHGVS($term);
					$counts = $this->search_model->countVariantsForRefHGVS($ref_hgvs, $source);
					$this->response($counts, 200);
				}
				elseif (preg_match('/N\S+_\S+/', $term)) { // Match RefSeq
//					print "hgvs<br />";
					$counts = $this->search_model->countVariantsForRef($term, $source);
					$this->response($counts, 200);
				}
				elseif (preg_match('/LRG_\S+/', $term)) {
//					print "hgvs<br />";
					$counts = $this->search_model->countVariantsForLRG($term, $source);
					$this->response($counts, 200);
				}
				elseif (preg_match('/rs\d+/', $term)) {
//					print "hgvs<br />";
					$counts = $this->search_model->countVariantsFordbSNP($term, $source);
					$this->response($counts, 200);
				}
				elseif (preg_match('/[cp]\.\S+/', $term)) { // Match just hgvs description (c. or p.) - probably don't need this as it's not useful if not in context of reference
					$counts = $this->search_model->countVariantsForHGVS($term, $source);
					$this->response($counts, 200);
				}
				else { // Gene or phenotype term entered
//					error_log("term -> " . $term . " source -> " . $source);
					$counts = $this->search_model->countVariantsForGene($term, $source);
//					error_log("node $source -> " . print_r($counts, 1));
					$this->response($counts, 200);
				}
			}
		}
		else {
			$this->response('Federated querying is not enabled in this installation', 404);
		}
    }

	private function _splitRegion($region) {
		$pieces = explode(":", $region); // Split region into chr and start/ends
		$chr = $pieces[0];
		$locations = array();
		if (preg_match('/\.\./', $pieces[1])) { // start/end is delimited by .. so split on this
			$positions = explode("..", $pieces[1]);
//			print_r($positions);
			$start = $positions[0];
			$end = $positions[1];
		}
		else { // start/end is delimited by - so split on this
			$positions = explode("-", $pieces[1]);
			$start = $positions[0];
			$end = $positions[1];
		}
		$locations['chr'] = $chr;
		$locations['start'] = $start;
		$locations['end'] = $end;
		return $locations;
	}
		
	function node_create_post() {
		if ( $this->config->item('federated_head') ) {
			$data = array( 'node_name' => $this->post('node_name'), 'node_uri' => $this->post('node_uri'), 'node_key' => $this->post('node_key') );
			$insert_id = $this->federated_model->insertNode($data);
			if ( $insert_id ) {
				$this->_node_propagate_list($data);
				$this->response("Node creation was successful", 201);
				// Now need to pass this information to all nodes and update their list
			}
			else {
				$this->response("Node name must be unique", 409);
			}
		}
		else {
			$this->response("Cannot create node, this isn't the federated master", 409);
		}
	}

	private function _node_propagate_list($node_data) {
//		error_log(print_r($node_data, 1));
		$node_list = $this->_node_list();
		$node_list_json = json_encode($node_list);
//		error_log("json -> " . $node_list_json);
		foreach ( $node_list as $node ) {
//			error_log("propagate this node data -> " . $node['node_uri']);
//			error_log(print_r($node, 1));
			updateNode($node_list, $node['node_uri']);
		}
	}

	function node_create_list_post() {
		if ($this->config->item('federated')) {
			$this->federated_model->deleteNodeList();
			$node_list = $this->post();
//			error_log(print_r($_POST,1));
			$flag = 0;
			foreach ( $node_list as $node ) {
//				error_log("propagate this node data -> " . $node['node_uri'] . " " . $node['node_name']);
				$data = array( 'node_name' => $node['node_name'], 'node_uri' => $node['node_uri'], 'node_key' => $node['node_key'], 'node_status' => $node['node_status'] );
				$insert_id = $this->federated_model->insertNode($data);
//				error_log("insert id -> " . $insert_id . " -> " . $node['node_name']);
				if ( ! $insert_id ) {
					$flag = 1;
				}
			}
			if ( $flag ) {
				$this->response("Node list could not be created", 409);
			}
			else {
				$this->response("Node list was successfully created.", 200);
			}
		}
		else {
			$this->response("Federatation is currently not enabled in the Cafe Variome installation.", 401);
		}

	}
		
	private function _node_list() {
		$node_list = $this->federated_model->getNodeList();
		if ( ! empty($node_list) ) {
			return $node_list;
		}
		else {
			return false;
		}
	}
	
	function node_delete_post() {
		$data = array( 'node_name' => $this->post('node_name') );
		$this->federated_model->deleteNode($data);
	}

	function node_exists_post() {
		$node_name = $this->post('node_name');
		$node_exists = $this->federated_model->checkNodeExists($node_name);
		if ( $node_exists ) {
//			error_log("insert -> " . $insert_id);
		}
		else {
			
		}
	}
	
	function node_ping_get() {
		$this->response("ping!", 200);
	}
		
	function node_list_get() {
		$node_list = $this->federated_model->getNodeList();
		if ( ! empty($node_list) ) {
			$this->response($node_list, 200);
//			foreach ( $node_list as $node ) {
//				print $node['node_name'] . "\t" . $node['node_uri'] . "\t" . $node['node_key'] . "\n";
//			}
		}
		else {
			$this->response("Node list is empty", 409);
		}
	}
	
	private function _splitRefHGVS($term) {
		$pieces = explode(":", $term); // Split region into chr and start/ends
		$ref_hgvs = array();
		$ref_hgvs['ref'] = $pieces[0];
		$ref_hgvs['hgvs'] = $pieces[1];
		return $ref_hgvs;
	}
	
}

?>
