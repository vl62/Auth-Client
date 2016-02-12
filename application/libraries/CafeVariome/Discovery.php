<?php

/**
 *
 * @author owen
 */

require_once "CafeVariome.php";

class Discovery extends CafeVariome {

	function __construct($parameters) {
//		parent::__construct();
		$this->CI =& get_instance();
		if ( array_key_exists('format', $parameters) ) {
			$this->format = $parameters['format'];
		}
		else {
			$this->format = 'html';
		}
		
		if ( array_key_exists('source', $parameters) ) {
			$this->source = $parameters['source'];
		}
		else {
			$this->source = 'all';
		}
		
	}

	public function setTerm($term) {
        $this->term = $term;
	}
	
	public function getTerm() {
        return $this->term;
	}
	
	public function setSource($source) {
        $this->source = $source;
	}
	
	public function getSource() {
        return $this->source;
	}
	
	public function setFormat($format) {
        $this->format = $format;
	}
	
	public function getFormat() {
        return $this->format;
	}

	public function setMutalyzerCheck($mutalyzer_check) {
        $this->mutalyzerCheck = $mutalyzer_check;
	}
	
	public function getMutalyzerCheck() {
        return $this->mutalyzerCheck;
	}

	public function setFromURLQuery($from_url_query) {
        $this->fromURLQuery = $from_url_query;
	}

	public function getFromURLQuery() {
        return $this->fromURLQuery;
	}
	
	function count($term = "", $source = "", $format = NULL, $mutalyzer_check = NULL) {
		$term = $this->getTerm();
		$format = $this->getFormat();
		$source = $this->getSource();
		$mutalyzer_check = $this->getMutalyzerCheck();
		$from_url_query = $this->getFromURLQuery();
		
		sleep(1);
//		error_log("term: $term | source: $source | format: $format | mutalyzer_check: $mutalyzer_check");
//		$term = urlencode($term);
		if ( $term ) {
//			error_log("POST -> " . print_r($_POST, true));

			$sources = array();
			if ( ! $this->CI->config->item('show_sources_in_discover')) {
//				error_log("form source -> $source");
				$source = "all";
			}
			if (preg_match('/all/i',$source)) { // All sources specified, get descriptions
				$sources = $this->CI->sources_model->getSources();
			}
			else { // Just one source, get description
				$sources = $this->CI->sources_model->getSourceSingle($source);
			}
			$sources_types = $this->CI->sources_model->getSourcesTypes();
			$source_access_levels = array();
			foreach ($sources as $source => $description ) {
				// Check whether the user can access restrictedAccess variants in this source
				// Get the ID of the source and fetch the groups that it belongs to
//				error_log($source);
				$source_id = $this->CI->sources_model->getSourceIDFromName($source);
				$current_source_groups = $this->CI->sources_model->getSourceGroups($source_id);
				$source_group_ids = array();
				$source_info = $this->CI->sources_model->getSource($source);
				$source_uri = $source_info['uri'];
				foreach ( $current_source_groups as $source_group ) {
//					error_log("source group -> " . $source_group['group_id']);
					$source_group_ids[] = $source_group['group_id'];
				}
				if ( $this->CI->ion_auth->logged_in() ) { // Check if the user if logged in
					// If logged in then get the id of the current user and fetch the groups that they belong to
					$user_id = $this->CI->ion_auth->user()->row()->id;
					if ( $this->CI->config->item('stats')) { // If stats logging is enabled then update the stats table for this query
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
					foreach ($this->CI->ion_auth->get_users_groups($user_id)->result() as $group) {
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
							$data['access_flag'][$source] = FALSE;
							$this->data['access_flag'][$source] = FALSE;
						}
					}
					else {
//						$source_access_levels[$source] = TRUE;
						if ( empty($from_url_query) ) {
							$data['access_flag'][$source] = TRUE;
						}
						else {
							$data['access_flag'][$source] = TRUE;
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
					$node_name = $this->CI->federated_model->getNodeNameFromNodeURI($source_uri);
					$node_source = str_replace("_" . $node_name, "", $source);
//					error_log("NODE SOURCE -> " . $node_source . " SOURCE_URI -> " . $source_uri);
					$source_info = $this->CI->sources_model->getSource($source);
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
				
				if ( $this->CI->config->item('use_elasticsearch') ) {
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
							$source_info = $this->CI->sources_model->getSource($source);
							$das_location = $this->_splitRegionDAS($term);
							$uri = $source_info['uri'] . "/features?segment=" . $das_location;
							$counts = $this->CI->search_model->countDASFeaturesForRegion($uri);
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
							$counts = $this->CI->search_model->countVariantsForRefHGVS($ref_hgvs, $source, $mutalyzer_check);
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
//						error_log("counts API -> " . print_r($counts, 1));
					}
					else {
//					if (! class_exists('Elasticsearch')) {
//						$this->load->library('elasticsearch');
//						echo "class not loaded<br />";
//					}
//					$check_if_running = $this->elasticsearch->check_if_running();
//					if ( array_key_exists( 'ok', $check_if_running) ) {						
						// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
						$es_index = $this->CI->config->item('site_title');
						$es_index = preg_replace('/\s+/', '', $es_index);
						$es_index = strtolower($es_index);
						$this->CI->elasticsearch->set_index($es_index);
						$this->CI->elasticsearch->set_type("variants");
						$query = array();
						$query['size'] = 0;
						$term = urldecode($term);
//						error_log("term -> $term");
//						$sanitize_query = htmlentities(strip_tags( $query ));
//						error_log("sanitize -> $sanitize_query");
//						$query['query']['query_string'] = array('query' =>  "$term AND $source", 'fields' => array("source", "gene"));
						
						
//						$this->load->model('settings_model');
						$search_fields = $this->CI->settings_model->getSearchFields("search_fields");
						
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
						$es_data = $this->CI->elasticsearch->query_dsl($query);
						$counts = array();
//						print "SOURCE -> $source<br />";
						foreach ( $es_data['facets']['sharing_policy']['terms'] as $facet_sharing_policy ) {
							$sp_es = $facet_sharing_policy['term'];
							if ( $sp_es == "openaccess" ) {
								$sp_es = "openAccess";
							}
							else if ( $sp_es == "restrictedaccess" ) {
								if ( $data['access_flag'][$source] == TRUE ) {
									$sp_es = "openAccess";
								}
								else {
									$sp_es = "restrictedAccess";
								}
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
							$counts = $this->CI->search_model->countVariantsForRegion($locations, $source, $mutalyzer_check);
						}
						else if ( $type == "api" ) {
							$counts = $this->runAPISearch($source_uri, $source, $term);
//							error_log("uri -> " . $source_uri);
						}
						else if ( $type == "das" ) { // It's a DAS source
//							features?segment=5:1,169269
							$source_info = $this->CI->sources_model->getSource($source);
							$das_location = $this->_splitRegionDAS($term);
							$uri = $source_info['uri'] . "/features?segment=" . $das_location;
							$counts = $this->CI->search_model->countDASFeaturesForRegion($uri);
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
							$counts = $this->CI->search_model->countVariantsForRefHGVS($ref_hgvs, $source, $mutalyzer_check);
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
							$counts = $this->CI->search_model->countVariantsForRef($term, $source, $mutalyzer_check);
						}
						else if ( $type == "api" ) {
							$counts = $this->CI->runAPISearch($source_uri, $source, $term);
						}
						else if ( $type == "central" ) {
							$counts = $this->CI->runAPISearch("http://www.cafevariome.org", $central_source, $term);
						}
					}
					elseif (preg_match('/LRG_\S+/', $term)) {
						if ( $type == "mysql") {
							$counts = $this->CI->search_model->countVariantsForLRG($term, $source, $mutalyzer_check);
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
							$counts = $this->CI->search_model->countVariantsFordbSNP($term, $source, $mutalyzer_check);
						}
						else if ( $type == "api" ) {
							$counts = $this->runAPISearch($source_uri, $source, $term);
						}
						else if ( $type == "central" ) {
							$counts = $this->runAPISearch("http://www.cafevariome.org", $central_source, $term);
						}
					}
					elseif (preg_match('/[cp]\.\S+/', $term)) { // Match just hgvs description (c. or p.) - probably don't need this as it's not useful if not in context of reference
						$counts = $this->CI->search_model->countVariantsForHGVS($term, $source, $mutalyzer_check);
					}
					else { // Gene or phenotype term entered
						if ( $type == "mysql") {
							$counts = $this->CI->search_model->countVariantsForGene($term, $source, $mutalyzer_check);
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
			return $counts;

		}
		else {
			error_log("no search term was present");
			show_error("You must specify a search term");
		}
	}
	
	function runAPISearch($source_uri, $source, $term) {
		$this->load->model('federated_model');
		// Get the node name and then remove it from the source name - need to do this since the node name has been appended in order to make it unique for this node - in the node that is to be search it won't have this appended bit
		$node_name = $this->federated_model->getNodeNameFromNodeURI($source_uri);
		$node_source = str_replace("_" . $node_name, "", $source);
		$federated_data = array(
			'term' => $term,
			'source' => $node_source
		);
//		error_log("federated_data -> " . $term . " -> " . $source_uri . " -> " . print_r($federated_data, 1));
//		$counts = federatedAPI($source_uri, $federated_data);
		$term = urlencode($term);
//		error_log("term -> " . $term);
		$counts = @file_get_contents($source_uri . "/discover/variantcount/$term/$node_source/json");
//		error_log($source_uri . "/discover/variantcount/$term/$source/json");
//		error_log("decode -> " . json_decode($counts));
		$counts = json_decode($counts, TRUE);
		$hacked_counts = array();
		if ( ! empty($counts)) {
			foreach ( $counts as $key => $value ) {
				foreach ( $value as $k => $v) {
//					error_log("key: $k value: $v");
					$hacked_counts[$k] = $v;
				}
			}
		}
//		error_log("counts from federatedAPI -> " . print_r($counts, 1));
//		error_log("hacked -> " . print_r($hacked_counts, 1));
		return $hacked_counts;

	}
	
	function runAPISearchForVariants($source_uri, $source, $term, $sharing_policy, $format, $username = NULL, $password = NULL) {
		$this->load->model('federated_model');
		// Get the node name and then remove it from the source name - need to do this since the node name has been appended in order to make it unique for this node - in the node that is to be search it won't have this appended bit
		$node_name = $this->federated_model->getNodeNameFromNodeURI($source_uri);
		$node_source = str_replace("_" . $node_name, "", $source);
		$federated_data = array(
			'term' => $term,
			'source' => $node_source
		);
		$term = urlencode($term);
		
		if ( $username ) {
			$context = stream_context_create(array(
					'http' => array(
					'header'  => "Authorization: Basic " . base64_encode("$username:$password")
				)
			));
			$variants = @file_get_contents($source_uri . "/discover/variants/$term/$node_source/$sharing_policy/$format", false, $context);
		}
		else {
			$variants = @file_get_contents($source_uri . "/discover/variants/$term/$node_source/$sharing_policy/$format");
		}
//		error_log($source_uri . "/discover/variants/$term/$node_source/$sharing_policy/$format");
//		$variants = json_decode($variants, TRUE);
//		error_log("variants -> " . print_r($variants, 1));
//		echo $variants;
		return $variants;

	}
	
	function runCentralSearch($source, $term) {
		$this->load->model('federated_model');
		$source_name = str_replace("_central", "", $source);
		$central_data = array(
			'term' => $term,
			'source' => $source_name
		);
		$source_uri = "http://www.cafevariome.org";
		$counts = federatedAPI($source_uri, $central_data);
//		error_log("counts from federatedAPI -> " . print_r($counts, 1));
		return $counts;

	}
	
	function parse($query) {
		$query_metadata = $query->queryMetadata;
		$query_id = $query_metadata->queryId;
		$query_type = $query_metadata->queryType;
		$query_label = $query_metadata->label;
		$query_result_format = $query_metadata->queryResultFormat;
		$submitter_id = $query_metadata->submitter->id;
		$submitter_name = $query_metadata->submitter->name;
		$submitter_email = $query_metadata->submitter->email;
		$submitter_institution = $query_metadata->submitter->institution;

		$query_data = $query->query;
//		print_r($query_data);
		$query_array = array();
		foreach ( $query_data as $k => $v ) {
			foreach ( $v as $element ) {
				if ( $this->syntax == "elasticsearch" ) {
//					$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//					$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
//					print "operator -> " . htmlentities($element->operator) . "<br />";
//					error_log("operator -> " . htmlentities($element->operator) . " -> " . $element->operator);
					$element->{$k} = strtolower($element->{$k});
					if ( strtolower($element->operator) == "is" ) {
						if ( $k == 'phenotype_epad' ) {
//							print $element->parameterID . " -> " . $element->{$k} . "<br />";
//							print_r($element);
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							if ( strtolower($element->{$k}) == "null" ) {
								$query_array[$element->parameterID] =  "_missing_:" . $attribute;
							}
							else {
//								$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//								$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
								$element->{$k} = addcslashes($element->{$k},'-+=&&||><!\(\)\{\}\[\]^"~*?:\\');
								$query_array[$element->parameterID] = $attribute . "_raw:" . $element->{$k};
							}
						}
						else {
							$query_array[$element->parameterID] = $element->{$k}; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = $element->{$k}; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}
					}
					elseif ( strtolower($element->operator) == "is like" ) {
						if ( $k == 'phenotype_epad' ) {
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
//							$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//							$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
							$element->{$k} = addcslashes($element->{$k},'-+=&&||><!\(\)\{\}\[\]^"~*?:\\');
							$query_array[$element->parameterID] = $attribute . "_raw:" . "*" . $element->{$k} . "*";
						}
						else {
//							$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//							$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
							$element->{$k} = addcslashes($element->{$k},'-+=&&||><!\(\)\{\}\[\]^"~*?:\\');
							$query_array[$element->parameterID] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}
					}
					elseif ( strtolower($element->operator) == "is not" ) {
						if ( $k == 'phenotype_epad' ) {
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							

							if ( strtolower($element->{$k}) == "null" ) {
								$query_array[$element->parameterID] =  "_exists_:" . $attribute;
							}
							else {
//								error_log("TYPE -------------> " . $element->{$k});
//								if ( is_numeric($element->{$k}) ) { // Hack for NOT problem
//									$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//									$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
//									$query_array[$element->parameterID] = $attribute . ":(" . "<" . $element->{$k} . " OR >" . $element->{$k} . ")";
//
//								}
//								else {
//									$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//									$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
									$element->{$k} = addcslashes($element->{$k},'-+=&&||><!\(\)\{\}\[\]^"~*?:\\');
//									$query_array[$element->parameterID] = $attribute . ":" . "! " . $element->{$k};
//									$query_array[$element->parameterID] = "<not>" . $attribute . "_raw:* !" . $element->{$k} . "</not>";
//									$query_array[$element->parameterID] = $attribute . "_raw:* !" . $element->{$k};
									$query_array[$element->parameterID] = $attribute . "_raw:" . "(-" . $element->{$k} . ")";
//									$not_filter = $attribute . "_raw:" . $element->{$k};
//									$this->notFilter = $attribute . "_raw:" . $element->{$k};
//									$query_array[$element->parameterID] = $attribute . ":" . $element->{$k};
//								}
							}
						}
						else {
							$query_array[$element->parameterID] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}
					}
					elseif ( strtolower($element->operator) == "is not like" ) {
						if ( $k == 'phenotype_epad' ) {
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							

							if ( strtolower($element->{$k}) == "null" ) {
								$query_array[$element->parameterID] =  "_exists_:" . $attribute;
							}
							else {
//								$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//								$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
//								$element->{$k} = preg_replace('/-|/','-',$element->{$k});
//								$element->{$k} = preg_replace('%([+\-&|!(){}[\]^"~*?:/]+)%', '\\\\$1', $element->{$k});
//								$element->{$k} = preg_replace('%([+-=]+)%', '\\\\$1', $element->{$k});
//								$elasticsearch_escaped_characters = array (+ - = && || > < ! ( ) { } [ ] ^ " ~ * ? : \ /);
								$element->{$k} = addcslashes($element->{$k},'-+=&&||><!\(\)\{\}\[\]^"~*?:\\');
								$query_array[$element->parameterID] = $attribute . "_raw:" . "(-*" . $element->{$k} . "*)";
							}
						}
						else {
							$query_array[$element->parameterID] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}
					}
					elseif ( strtolower($element->operator) == "=" ) {
						if ( $k == 'phenotype_epad' ) {
//							print $element->parameterID . " -> " . $element->{$k} . "<br />";
//							print_r($element);
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
//							$subject = '+ - = && || > < ! ( ) { } [ ] ^ " ~ * ? : \ /';
//							$result = preg_replace('%([+\-&|!(){}[\]^"~*?:/]+)%', '\\\\$1', $subject);
							if ( strtolower($element->{$k}) == "null" ) {
								$query_array[$element->parameterID] =  "_missing_:" . $attribute;
							}
							else {
								if ( is_numeric($element->{$k}) ) {
									$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
									$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
									$query_array[$element->parameterID] = $attribute . "_d:" . $element->{$k};
								}
								else { // A string value with numeric comparison shouldn't be possible as it's blocked in the query builder
									$query_array[$element->parameterID] = $attribute . ":" . $element->{$k};
								}
							}
						}
						else {
//							$element->{$k} = addcslashes($element->{$k},'-+=&&||><!\(\)\{\}\[\]^"~*?:\\');
							$query_array[$element->parameterID] = $element->{$k}; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = $element->{$k}; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}
					}
					elseif ( strtolower($element->operator) == "!=" ) {
//					elseif ( htmlentities($element->operator) == "&ne;" ) {
						if ( $k == 'phenotype_epad' ) {
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							
							if ( strtolower($element->{$k}) == "null" ) {
								$query_array[$element->parameterID] =  "_exists_:" . $attribute;
							}
							else {
//								error_log("TYPE -------------> " . $element->{$k});
								if ( is_numeric($element->{$k}) ) {
									$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
									$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
									$query_array[$element->parameterID] = $attribute . "_d:(" . "<" . $element->{$k} . " OR >" . $element->{$k} . ")";
								}
							}
						}
						else {
							$query_array[$element->parameterID] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}	
					}
					else { // Else it must be a numeric comparison >,<,>=,<=
						if ( $k == 'phenotype_epad' ) {
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							if ( is_numeric($element->{$k}) ) {
								$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
								$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
								$query_array[$element->parameterID] = $attribute . "_d:" . "" . $element->operator . "" . $element->{$k};
							}
							else { // A string value with numeric comparison shouldn't be possible as it's blocked in the query builder
//								$query_array[$element->parameterID] = $attribute . ":" . "" . $element->operator . "" . $element->{$k};
								$query_array[$element->parameterID] = $attribute . ":" . " " . $element->operator . "" . $element->{$k};
							}
						}
						else {
							$query_array[$element->parameterID] = $element->{$k};
//							$query_array[$element->parameterID] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}	
					}
				}
			}
		}
//		print_r($query_array);

		$query_statement = $query->queryStatement;
//		print "$query_statement<br />";
//		preg_match_all('!\d+!', $query_statement, $matches);
//		error_log("query_statement -> " . $query_statement);
//		print_r($matches);
//		preg_match_all("/\[[^\]]*\]/", $query_statement, $m);
//		print "$query_statement<br />";
//		preg_match_all('/\(+(.*?)\)/', $query_statement, $matches);
//		preg_match_all("/\((.*?)\)/", $query_statement, $matches);
		preg_match_all('/\(([^\)]*)\)/', $query_statement, $matches);
//		print_r($matches);
//		print "<br />";
//		error_log(print_r($matches,1));
		$query_statement_array = array();
		foreach ( $matches[0] as $match ) {
			$match = str_replace('((','(',$match);
			$match = str_replace('))',')',$match);
			$match_no_brackets = str_replace(array('(',')'),'',$match);
//			print "MATCH -> $match<br />";
//			error_log("search: " . $match . " -> replace:" . $query_array[$match_no_brackets] . " --> " . $query_statement);
//			if ( preg_match('/\s/',$query_array[$match_no_brackets]) ) {
//				$query_section = "\"" . $query_array[$match_no_brackets] . "\"";
//			}
//			else {
//				$query_section = $query_array[$match_no_brackets];
//			}
			$query_section = $query_array[$match_no_brackets];
//			error_log($query_section);
//			print "section -> $query_section<br />";
			$query_statement = str_replace($match, $query_section, "(" . $query_statement . ")");
			$query_statement_array[] = "(" . $query_section . ")";
		}
//		print_r($query_statement_array);
		$query_statement = implode(' AND ', $query_statement_array);
//		error_log($query_statement);
		$query_statement_for_display = $query_statement;
		$query_statement_for_display = str_replace('_d','',$query_statement_for_display); // Remove the appended numeric index name so that it isn't displayed to the user
		$query_statement_for_display = str_replace('_raw','',$query_statement_for_display);
		$query_statement_for_display = str_replace('_missing_','missing',$query_statement_for_display);
		$query_statement_for_display = str_replace('_exists_','exists',$query_statement_for_display);
		$query_statement_for_display = str_replace('\[','[',$query_statement_for_display);
		$query_statement_for_display = str_replace('\]',']',$query_statement_for_display);
		$query_statement_for_display = str_replace('_',' ',$query_statement_for_display);
		print "<h4>$query_statement_for_display</h4>";
		return $query_statement;
		
	}
	
	function run($term, $source) {
//		error_log("term -> $term");
//		$term = "(Long_nose:present) AND (Narrow_nasal_ridge:present)";
//		$term = "nose_length_\[cm\]:>5";
//		$term = "(nose_length_\[m\]:>=5) AND (nose_length_\[mm\]:>=6)";
//		error_log("term -> $term");
		if ($this->syntax == "elasticsearch") {
			// Get dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
			$es_index = $this->CI->config->item('site_title');
			$es_index = preg_replace('/\s+/', '', $es_index);
			$es_index = strtolower($es_index);
			$this->CI->elasticsearch->set_index($es_index);
			$this->CI->elasticsearch->set_type("variants");
			$query = array();
			$query['size'] = 0;
			$term = urldecode($term);

			$search_fields = $this->CI->settings_model->getSearchFields("search_fields");

			if (!empty($search_fields)) { // Specific search fields are specified in admin interface so only search on these
				$search_fields_elasticsearch = array();
				foreach ($search_fields as $fields) {
					$search_fields_elasticsearch[] = $fields['field_name'];
				}
//				error_log("search fields -> " . print_r($search_fields, 1));
				$query['query']['bool']['must'][] = array('query_string' => array("fields" => $search_fields_elasticsearch, "query" => "$term", 'default_operator' => "AND"));
			}
			else { // Otherwise search across all fields
				
//				if ( property_exists($this, 'notFilter') ) {
//					error_log("notFilter -> " . $this->notFilter);
//					$query['query']['bool']['must'][] = array('query_string' => array("query" => "$term", 'default_operator' => "AND")); // , "default_field" => "" Hack: default_field as empty because when doing apoe not M it was searching the gender field and getting back the hits for that
//					$query['query']['bool']['must_not'][] = array('query_string' => array("query" => "$this->notFilter", 'default_operator' => "AND")); // , "default_field" => "" Hack: default_field as empty because when doing apoe not M it was searching the gender field and getting back the hits for that
//				}
//				else {
					$query['query']['bool']['must'][] = array('query_string' => array("query" => "$term", 'default_operator' => "AND")); // 'analyzer' => 'not_analyzed' , "default_field" => "" Hack: default_field as empty because when doing apoe not M it was searching the gender field and getting back the hits for that
//				}
//				$query['query']['query_string'] = array("query" => "$term", 'default_operator' => "AND");
//				$query['query']['bool']['must_not'][] = array('query_string' => array("query" => "$term"));
//				$query['query']['bool']['must_not'][] = array('query_string' => array("query" => "$term", 'default_operator' => "AND"));
			}

			$query['query']['bool']['must'][] = array("term" => array("source" => $source));
			$query['facets']['sharing_policy']['terms'] = array('field' => "sharing_policy");
//			$query['filter']['not'] = array();
//			$query['query']['bool']['must'][] = array("term" => array("source" => $source));
			$query = json_encode($query);
//			error_log("query ----> $query $source");
			$es_data = $this->CI->elasticsearch->query_dsl($query);
//			error_log(print_r($es_data, 1));
			$counts = array();
//			print "SOURCE -> $source<br />";
			if ( array_key_exists('facets', $es_data) ) {
				foreach ($es_data['facets']['sharing_policy']['terms'] as $facet_sharing_policy) {
					$sp_es = $facet_sharing_policy['term'];
					if ($sp_es == "openaccess") {
						$sp_es = "openAccess";
					}
					else if ($sp_es == "restrictedaccess") {
							$sp_es = "restrictedAccess";
					}
					else if ($sp_es == "linkedaccess") {
						$sp_es = "linkedAccess";
					}

					$counts[$sp_es] = $facet_sharing_policy['count'];
//					error_log("es counts -> " . print_r($counts,1));
//					print "<br />";
				}
			}
			return $counts;
		}

		
	}
	

	function run_API($source_uri, $source, $term) {
		$this->load->model('federated_model');
		// Get the node name and then remove it from the source name - need to do this since the node name has been appended in order to make it unique for this node - in the node that is to be search it won't have this appended bit
		$node_name = $this->federated_model->getNodeNameFromNodeURI($source_uri);
		$node_source = str_replace("_" . $node_name, "", $source);
		$federated_data = array(
			'term' => $term,
			'source' => $node_source
		);
//		error_log("federated_data -> " . $term . " -> " . $source_uri . " -> " . print_r($federated_data, 1));
//		$counts = federatedAPI($source_uri, $federated_data);
		$term = urlencode($term);
//		error_log("term -> " . $term);
		$counts = @file_get_contents($source_uri . "/discover/variantcount/$term/$node_source/json");
//		error_log($source_uri . "/discover/variantcount/$term/$source/json");
//		error_log("decode -> " . json_decode($counts));
		$counts = json_decode($counts, TRUE);
		$hacked_counts = array();
		if ( ! empty($counts)) {
			foreach ( $counts as $key => $value ) {
				foreach ( $value as $k => $v) {
//					error_log("key: $k value: $v");
					$hacked_counts[$k] = $v;
				}
			}
		}
//		error_log("counts from federatedAPI -> " . print_r($counts, 1));
//		error_log("hacked -> " . print_r($hacked_counts, 1));
		return $hacked_counts;

	}
	
	
	function detect_type($element, $data) {
		switch ($element) {
			case "allele":
				echo "Running allele query -> ";
				$this->allele_query($data);
				break;
			case "geneSymbol":
				echo "Running gene symbol query -> ";
				$this->gene_symbol_query($data);
				break;
			case "green":
					echo "Your favorite color is green!";
					break;
			default:
				echo "Query type was not detected";
		}
	}
	
	function allele_query($data) {
		foreach ( $data as $allele ) {
			
//			$operator = $allele->operator;
			$operator = isset($allele->operator) ? $allele->operator : '';
						
//			$source = $allele->source;
			$source = isset($allele->source) ? $allele->source : '';
			
//			$reference = $allele->reference;
			$reference = isset($allele->reference) ? $allele->reference : '';
			
//			$start = $allele->start;
			$start = isset($allele->start) ? $allele->start : '';
			
//			$end = $allele->end;
			$end = isset($allele->end) ? $allele->end : '';
			
//			$allele_sequence = $allele->allele_sequence;
			$allele_sequence = isset($allele->allele_sequence) ? $allele->allele_sequence : '';
			
			if ( is_array($allele_sequence) ) {
				echo "ARRAY";
			}
			else {
				echo "NOT ARRAY";
			}
		}
		print_r($data);
	}

	function gene_symbol_query($data) {
		$gene_symbol = isset($allele->geneSymbol) ? $allele->geneSymbol : '';
		print_r($data);
	}
	
}

?>
