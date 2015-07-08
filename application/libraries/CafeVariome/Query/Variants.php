<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter Mutalyzer Class
 *
 * Use Mutalyzer webservice to validate variants
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Owen Lancaster
 * @created			04/12/2013
 */

class Variants {
    function __construct($features = array()) {
        foreach($features as $key => $value) {
            $this->$key = $value;
        }
    }
	
	function variantcount($term = "", $source = "", $format = "", $mutalyzer_check = "") {
		sleep(1);
//		error_log("variantcount -> $term $source $format");
//		$this->output->enable_profiler(TRUE);
		if ( $this->input->post('term') ) { // The inputs come from the form
			$term = $this->input->post('term');
			$source = $this->input->post('source');
			$lab = $this->input->post('lab');
			$mutalyzer_check = $this->_isBoolean($this->input->post('mutalyzer_check'));
			$grouping_type = $this->input->post('grouping_type');
		}
		else {
			$term = urldecode($term);
			$mutalyzer_check = $this->_isBoolean($mutalyzer_check);
			$from_url_query = true;
		}
//		error_log("term: $term | source: $source | format: $format | mutalyzer_check: $mutalyzer_check");
//		$term = urlencode($term);
		if ( $term ) {
//			error_log("POST -> " . print_r($_POST, true));
			if ( empty($from_url_query) ) {
				$data['term'] = $term;
			}
			else {
				$term = urlencode($term);
				$this->data['term'] = $term;
			}

			$sources = array();
			if ( ! $this->config->item('show_sources_in_discover')) {
//				error_log("form source -> $source");
				$source = "all";
			}
			if (preg_match('/all/i',$source)) { // All sources specified, get descriptions
				$sources = $this->sources_model->getSources();
			}
			else { // Just one source, get description
				$sources = $this->sources_model->getSourceSingle($source);
			}
			$sources_types = $this->sources_model->getSourcesTypes();
			if ( empty($from_url_query) ) {
				$data['sources_full'] = $sources;
			}
			else {
				$this->data['sources_full'] = $sources;
			}
			$source_access_levels = array();
			foreach ($sources as $source => $description ) {
				// Check whether the user can access restrictedAccess variants in this source
				// Get the ID of the source and fetch the groups that it belongs to
//				error_log($source);
				$source_id = $this->sources_model->getSourceIDFromName($source);
				$current_source_groups = $this->sources_model->getSourceGroups($source_id);
				$source_group_ids = array();
				$source_info = $this->sources_model->getSource($source);
				$source_uri = $source_info['uri'];
				foreach ( $current_source_groups as $source_group ) {
//					error_log("source group -> " . $source_group['group_id']);
					$source_group_ids[] = $source_group['group_id'];
				}
				if ( $this->ion_auth->logged_in() ) { // Check if the user if logged in
					// If logged in then get the id of the current user and fetch the groups that they belong to
					$user_id = $this->ion_auth->user()->row()->id;
					if ( $this->config->item('stats')) { // If stats logging is enabled then update the stats table for this query
						$ip = getRealIpAddr();
						$search_stats = array(
							'ip' => $ip,
							'username' => $user_id,
							'term' => $term,
							'source' => $source,
							'datetime' => date('d-m-Y H:i:s')
						);
						updateStats($search_stats, 'searchstats');
					}
					$user_group_ids = array();
					foreach ($this->ion_auth->get_users_groups($user_id)->result() as $group) {
//						echo "groupid -> " . $group->id . " groupname -> " . $group->name . " description -> " . $group->description;
//						$groups_in[] = $group->id;
						$user_group_ids[] = $group->id;
//						error_log("user group -> " . $group->id);
					}
					// Check whether the user is in a group that this source belongs to
					$diff = array_intersect($user_group_ids, $source_group_ids);
					if ( empty($diff)) {
						$source_access_levels[$source] = FALSE;
						if ( empty($from_url_query) ) {
							$data['access_flag'][$source] = FALSE;
						}
						else {
							$this->data['access_flag'][$source] = FALSE;
						}
						
					}
					else {
//						$source_access_levels[$source] = TRUE;
						if ( empty($from_url_query) ) {
							$data['access_flag'][$source] = TRUE;
						}
						else {
							$this->data['access_flag'][$source] = TRUE;
						}
					}
				}
				else { // User isn't logged in so set the access flag to false for all the sources
					if ( empty($from_url_query) ) {
						$data['access_flag'][$source] = FALSE;
					}
					else {
						$this->data['access_flag'][$source] = FALSE;
					}
					
					if ( $this->config->item('stats')) {
						$ip = getRealIpAddr();
						$search_stats = array(
							'ip' => $ip,
							'username' => "NULL",
							'term' => $term,
							'source' => $source,
							'datetime' => date('d-m-Y H:i:s')
						);
						updateStats($search_stats, 'searchstats');
					}
				}
				////
//				error_log("source -> $source");
				$type = $sources_types[$source];
				if ( empty($from_url_query) ) {
					$data['source_types'][$source] = $type;
				}
				else {
					$this->data['source_types'][$source] = $type;
				}
				
				if ( $type == "api" ) {
					$this->load->model('federated_model');
					// Get the node name and then remove it from the source name - need to do this since the node name has been appended in order to make it unique for this node - in the node that is to be search it won't have this appended bit
					$node_name = $this->federated_model->getNodeNameFromNodeURI($source_uri);
					$node_source = str_replace("_" . $node_name, "", $source);
//					error_log("NODE SOURCE -> " . $node_source . " SOURCE_URI -> " . $source_uri);
					$source_info = $this->sources_model->getSource($source);
					if ( empty($from_url_query) ) {
						$data['source_info'][$source] = $source_info;
						$data['node_source'][$source] = $node_source;
					}
					else {
						$this->data['source_info'][$source] = $source_info;
						$this->data['node_source'][$source] = $node_source;						
					}
				}
				
				if ( $type == "central" ) {
					$central_source = str_replace("_central", "", $source);
					if ( empty($from_url_query) ) {
						$data['central_source'][$source] = $central_source;
					}
					else {
						$this->data['central_source'][$source] = $central_source;
					}
				}
				
				if ( ! $type ) { // If there's no type for this source in the database set it as mysql for the default
					$type = "mysql";
				}
				
				if ( $this->config->item('use_elasticsearch') ) {
					if (preg_match('/chr\S+:\d+\-|\.\.\d+/', $term)) { // Match chromosome region regex
						$locations = $this->_splitRegion($term);
						if ( $type == "mysql") { // If source type is specified as mysql then use region function for variants 
							$counts = $this->search_model->countVariantsForRegion($locations, $source, $mutalyzer_check);
						}
						else if ( $type == "api" ) {
							$counts = $this->runAPISearch($source_uri, $source, $term);
//							error_log("uri -> " . $source_uri);
						}
						else if ( $type == "das" ) { // It's a DAS source
//							features?segment=5:1,169269
							$source_info = $this->sources_model->getSource($source);
							$das_location = $this->_splitRegionDAS($term);
							$uri = $source_info['uri'] . "/features?segment=" . $das_location;
							$counts = $this->search_model->countDASFeaturesForRegion($uri);
//							error_log("das -> " . $term . " source -> " . $source . " uri -> " . $uri);
						}
						else if ( $type == "central" ) { // Cafe Variome Central source
//							error_log("test -> $source $term");
							$counts = $this->runAPISearch("http://www.cafevariome.org", $central_source, $term);
						}
//						else { // TODO: Add processing for other types of variant sources e.g. VarioML (AtomServer) - separate out processing into separate count class
//							print "other type<br />";						
//						}
					}
					elseif (preg_match('/N\S+_\S+\:\S+/', $term)) { // Match RefSeq and HGVS
						if ( $type == "mysql") {
							$ref_hgvs = $this->_splitRefHGVS($term);
							$counts = $this->search_model->countVariantsForRefHGVS($ref_hgvs, $source, $mutalyzer_check);
						}
						else if ( $type == "api" ) {
							$counts = $this->runAPISearch($source_uri, $source, $term);
						}
						else if ( $type == "central" ) {
							$counts = $this->runAPISearch("http://www.cafevariome.org", $central_source, $term);
						}
					}
					else {
						
					if ( $type == "central" ) { // Cafe Variome Central source
//						error_log("test -> $source $term");
						$counts = $this->runAPISearch("http://www.cafevariome.org", $central_source, $term);
					}
					else if ( $type == "api" ) {
						$counts = $this->runAPISearch($source_uri, $source, $term);
						error_log("counts API -> " . print_r($counts, 1));
					}
					else {
//					if (! class_exists('Elasticsearch')) {
						$this->load->library('elasticsearch');
//						echo "class not loaded<br />";
//					}
//					$check_if_running = $this->elasticsearch->check_if_running();
//					if ( array_key_exists( 'ok', $check_if_running) ) {						
						// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
						$es_index = $this->config->item('site_title');
						$es_index = preg_replace('/\s+/', '', $es_index);
						$es_index = strtolower($es_index);
						$this->elasticsearch->set_index($es_index);
						$this->elasticsearch->set_type("variants");
						$query = array();
						$query['size'] = 0;
						$term = urldecode($term);
//						error_log("term -> $term");
//						$sanitize_query = htmlentities(strip_tags( $query ));
//						error_log("sanitize -> $sanitize_query");
//						$query['query']['query_string'] = array('query' =>  "$term AND $source", 'fields' => array("source", "gene"));
						
						
						$this->load->model('settings_model');
						$search_fields = $this->settings_model->getSearchFields("search_fields");
						
						if ( ! empty($search_fields) ) { // Specific search fields are specified in admin interface so only search on these
							$search_fields_elasticsearch = array();
							foreach ($search_fields as $fields) {
								$search_fields_elasticsearch[] = $fields['field_name'];
							}
//							error_log("search fields -> " . print_r($search_fields, 1));
							$query['query']['bool']['must'][] = array('query_string' => array("fields" => $search_fields_elasticsearch, "query" => "$term", 'default_operator' => "AND"));
						}
						else { // Otherwise search across all fields
							$query['query']['bool']['must'][] = array('query_string' => array("query" => "$term", 'default_operator' => "AND"));
						}
						
						$query['query']['bool']['must'][] = array("term" => array("source" => $source));
						$query['facets']['sharing_policy']['terms'] = array('field' => "sharing_policy");
						$query = json_encode($query);
//						error_log("query ----> $query");
						$es_data = $this->elasticsearch->query_dsl($query);
						$counts = array();
//						print "SOURCE -> $source<br />";
						foreach ( $es_data['facets']['sharing_policy']['terms'] as $facet_sharing_policy ) {
							$sp_es = $facet_sharing_policy['term'];
							if ( $sp_es == "openaccess" ) {
								$sp_es = "openAccess";
							}
							else if ( $sp_es == "restrictedaccess" ) {
								$sp_es = "restrictedAccess";
							}
							else if ( $sp_es == "linkedaccess" ) {
								$sp_es = "linkedAccess";
							}

							$counts[$sp_es] = $facet_sharing_policy['count'];
//							error_log("es counts -> " . print_r($counts,1));
//							print "<br />";
						}
//					}
//					else {
//						show_error("The search server is not running");
//					}
					}
					}
				}
				else {
					if (preg_match('/chr\S+:\d+\-|\.\.\d+/', $term)) { // Match chromosome region regex
						$locations = $this->_splitRegion($term);
						if ( $type == "mysql") { // If source type is specified as mysql then use region function for variants 
							$counts = $this->search_model->countVariantsForRegion($locations, $source, $mutalyzer_check);
						}
						else if ( $type == "api" ) {
							$counts = $this->runAPISearch($source_uri, $source, $term);
//							error_log("uri -> " . $source_uri);
						}
						else if ( $type == "das" ) { // It's a DAS source
//							features?segment=5:1,169269
							$source_info = $this->sources_model->getSource($source);
							$das_location = $this->_splitRegionDAS($term);
							$uri = $source_info['uri'] . "/features?segment=" . $das_location;
							$counts = $this->search_model->countDASFeaturesForRegion($uri);
//							error_log("das -> " . $term . " source -> " . $source . " uri -> " . $uri);
						}
						else if ( $type == "central" ) { // Cafe Variome Central source
//							error_log("test -> $source $term");
							$counts = $this->runAPISearch("http://www.cafevariome.org", $central_source, $term);
						}
//						else { // TODO: Add processing for other types of variant sources e.g. VarioML (AtomServer) - separate out processing into separate count class
//							print "other type<br />";						
//						}
					}
					elseif (preg_match('/N\S+_\S+\:\S+/', $term)) { // Match RefSeq and HGVS
						if ( $type == "mysql") {
							$ref_hgvs = $this->_splitRefHGVS($term);
							$counts = $this->search_model->countVariantsForRefHGVS($ref_hgvs, $source, $mutalyzer_check);
						}
						else if ( $type == "api" ) {
							$counts = $this->runAPISearch($source_uri, $source, $term);
						}
						else if ( $type == "central" ) {
							$counts = $this->runAPISearch("http://www.cafevariome.org", $central_source, $term);
						}
					}
					elseif (preg_match('/N\S+_\S+/', $term)) { // Match RefSeq
						if ( $type == "mysql") {
							$counts = $this->search_model->countVariantsForRef($term, $source, $mutalyzer_check);
						}
						else if ( $type == "api" ) {
							$counts = $this->runAPISearch($source_uri, $source, $term);
						}
						else if ( $type == "central" ) {
							$counts = $this->runAPISearch("http://www.cafevariome.org", $central_source, $term);
						}
					}
					elseif (preg_match('/LRG_\S+/', $term)) {
						if ( $type == "mysql") {
							$counts = $this->search_model->countVariantsForLRG($term, $source, $mutalyzer_check);
						}
						else if ( $type == "api" ) {
							$counts = $this->runAPISearch($source_uri, $source, $term);
						}
						else if ( $type == "central" ) {
							$counts = $this->runAPISearch("http://www.cafevariome.org", $central_source, $term);
						}
					}
					elseif (preg_match('/rs\d+/', $term)) {
						if ( $type == "mysql") {
							$counts = $this->search_model->countVariantsFordbSNP($term, $source, $mutalyzer_check);
						}
						else if ( $type == "api" ) {
							$counts = $this->runAPISearch($source_uri, $source, $term);
						}
						else if ( $type == "central" ) {
							$counts = $this->runAPISearch("http://www.cafevariome.org", $central_source, $term);
						}
					}
					elseif (preg_match('/[cp]\.\S+/', $term)) { // Match just hgvs description (c. or p.) - probably don't need this as it's not useful if not in context of reference
						$counts = $this->search_model->countVariantsForHGVS($term, $source, $mutalyzer_check);
					}
					else { // Gene or phenotype term entered
						if ( $type == "mysql") {
							$counts = $this->search_model->countVariantsForGene($term, $source, $mutalyzer_check);
//							error_log("returned $source -> " . print_r($counts, 1));
						}
						else if ( $type == "api" ) {
							$counts = $this->runAPISearch($source_uri, $source, $term);
//							error_log("returned $source -> " . print_r($counts, 1));
						}
						else if ( $type == "central" ) {
							$counts = $this->runAPISearch("http://www.cafevariome.org", $central_source, $term);
						}
					}
				}
				if ( isset ($counts) ) {
					if ( empty($from_url_query) ) {
						$data['counts'][$source] = $counts;
					}
					else {
						$this->data['counts'][$source] = $counts;
					}
				}
				else {
					if ( empty($from_url_query) ) {
						$data['counts'][$source] = array();
					}
					else {
						$this->data['counts'][$source] = array();
					}
				}
			}
//			error_log(print_r($data, 1));
			
			if ( empty($from_url_query) ) { // The query comes from the form through the website
				$this->load->view('pages/sources_table', $data); // Don't use _render as headers are already sent, html output from the view is sent back to ajax function and appended to div
			}
			else { // Query comes from a URL construction
				
				if ( strtolower($format) == "html" ) {
					$this->_render('pages/sources_table');
				}
				else if ( strtolower($format) == "tab" ) {
					$this->output->set_header("Content-Type: text/plain");
					$this->load->view('pages/sources_table_tab', $this->data);
				}
				else if ( strtolower($format) == "json" ) {
					$this->output->set_content_type('application/json')->set_output(json_encode($this->data['counts']));
				}
				else {
					$this->_render('pages/sources_table');
				}
			}
		}
		else {
			error_log("no search term was present");
			show_error("You must specify a search term");
		}
	}
	
}

/* End of file Variants.php */
/* Location: ./application/libraries/CafeVariome/Variants.php */