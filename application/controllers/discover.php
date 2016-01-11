<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Discover extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('varioml_model');
        $this->load->model('sources_model');
        $this->load->model('search_model');
        $this->load->model('settings_model');
        // If this setting is on then the discovery search cannot be accessed unless the user is logged in. 
        // Need to use a model to get config setting because of the config hook that loads settings from db (config is only loaded post controller construction)
        // TODO: Also add in further authentication to make sure the user belongs to a specific group
        $discovery_requires_login = $this->settings_model->getDiscoveryRequiresLoginSetting();
        if ($discovery_requires_login) {
            if (!$this->ion_auth->logged_in()) {
//				redirect('auth/login', 'refresh');
                redirect('admin/discovery_denied', 'refresh');
            }
        }
    }
    
    public $sources;

    public function index($network_key = '') {
        redirect("discover/proceed_to_query/query_builder");
        // Check if there's a network key supplied in the URL, if not then check if it's set in the session, if not then redirect back to the select network page
//		if ( $network_key ) {
//			$this->session->set_userdata(array('network_key' => $network_key));
//		}
//		else {
//			$network_key = $this->session->userdata('network_key');
//			if ( ! $network_key ) {
//				redirect('discover/proceed_to_query/standard_search', 'refresh');
//			}
//		}
//		$this->data['network_key'] = $network_key;
//		
//		// Check if the user is in the master network group for this network
//		$user_id = $this->ion_auth->user()->row()->id;
//		$is_user_member_of_master_network_group_for_network = json_decode(authPostRequest('', array('user_id' => $user_id, 'network_key' => $network_key), $this->config->item('auth_server') . "/api/auth_general/is_user_member_of_master_network_group_for_network"), 1);
////		error_log("is_user_member_of_master_network_group_for_network -> " . print_r($is_user_member_of_master_network_group_for_network,1));
//		$network_master_group_test = $is_user_member_of_master_network_group_for_network['is_user_member_of_master_network_group_for_network'] == '1' ? true: false;
////		error_log("network_master_group_test -> " . $network_master_group_test);
//		// Check if user is a member of the master network group, if not then don't allow to proceed further and show error message
//		if ( ! $network_master_group_test ) {
//			show_error("You are not a member of the master group for this network so cannot access any discovery interfaces. In order to search any networks you need to get an administrator to add you to the master network group for each network.");
//		}
//		
//		$this->title = "Discover";
//		$token = $this->session->userdata('Token');
//		$data = authPostRequest($token, array('network_key' => $network_key), $this->config->item('auth_server') . "/api/auth/get_all_installations_for_network");
//		$federated_installs = stripslashes($data);
//		error_log("federated_installs -> $federated_installs");
//		// Set the federated installs in the session so they can be used by variantcount
//		$this->session->set_userdata(array('federated_installs' => $federated_installs));
////		$network_key = $this->post('network_key');
////		$this->data['network_key'] = $network_key;
////		$networks = json_decode(authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_networks_installation_member_of"), 1);
////		error_log("networks -> " . print_r($networks, 1));
////		$this->data['networks'] = $networks;
//		
//		
//		$sources_options = $this->sources_model->getSources(); // Get all the available sources from db
//		$this->setSources($sources_options);
////		$this->benchmark->mark('search_end');
////		echo $this->benchmark->elapsed_time('search_start', 'search_end');
//		$this->data['sources_options'] = $sources_options;
//		$laboratory_options = $this->sources_model->getLaboratoryCategories(); // Get all available diagnostic labs
//		$source_counts = $this->sources_model->countOnlineSourceEntries(); // Get counts of variants for info box in discovery interface
//		$this->data['source_counts'] = $source_counts;
//		$this->data['laboratory_options'] = $laboratory_options;
//		$ontologiesused = $this->sources_model->getOntologiesUsedAndRoots();
//		$this->data['ontologiesused'] = $ontologiesused;
//		$jstree = $this->_generate_jstree($ontologiesused);
//		$this->data['jstree'] = $jstree;
//		
////		$this->data['gene_counts'] = $this->sources_model->countFeature('gene');
////		$this->data['ref_counts'] = $this->sources_model->countFeature('ref');
//		
//		$this->load->library('elasticsearch');
//		$check_if_running = $this->elasticsearch->check_if_running();
//		if ( ! array_key_exists( 'ok', $check_if_running) ) {
//			show_error("The discovery interface is currently not accessible as Elasticsearch is not running. Please get an administrator to start Elasticsearch and then try again.");
//		}
//		$this->_render('pages/discover');
    }

    function count($term = "", $source = "", $format = "", $mutalyzer_check = "") {
        $parameters = array('format' => 'html', 'source' => 'all');
        $this->load->library('CafeVariome/Discovery', $parameters, 'discovery');

        if ($this->input->post('term')) { // The inputs come from the form
            $term = $this->input->post('term');
            $source = $this->input->post('source');
            $lab = $this->input->post('lab');
            $mutalyzer_check = $this->_isBoolean($this->input->post('mutalyzer_check'));
            $grouping_type = $this->input->post('grouping_type');
            $from_url_query = false;
        } else {
            $term = urldecode($term);
            $mutalyzer_check = $this->_isBoolean($mutalyzer_check);
            $from_url_query = true;
        }

        $this->discovery->setFromURLQuery($from_url_query);
        $this->discovery->setTerm($term);
        $this->discovery->setFormat($format);
        $this->discovery->setSource($source);
        $this->discovery->setMutalyzerCheck($mutalyzer_check);
        $counts = $this->discovery->count();

//		if ( $this->config->item('stats')) { // If stats logging is enabled then update the stats table for this query
//			$ip = getRealIpAddr();
//			$search_stats = array(
//				'ip' => $ip,
//				'username' => $user_id,
//				'term' => $term,
//				'source' => $source,
//				'datetime' => date('d-m-Y H:i:s')
//			);
//			updateStats($search_stats, 'searchstats');
//		}

        if (empty($from_url_query)) {
            $data['term'] = $term;
        } else {
            $term = urlencode($term);
            $this->data['term'] = $term;
        }

        if (preg_match('/all/i', $source)) { // All sources specified, get descriptions
            $sources = $this->sources_model->getSources();
        } else { // Just one source, get description
            $sources = $this->sources_model->getSourceSingle($source);
        }

        if (empty($from_url_query)) {
            $data['sources_full'] = $sources;
        } else {
            $this->data['sources_full'] = $sources;
        }

        if (empty($from_url_query)) { // The query comes from the form through the website
            $this->load->view('pages/sources_table', $data); // Don't use _render as headers are already sent, html output from the view is sent back to ajax function and appended to div
        } else { // Query comes from a URL construction
            if (strtolower($format) == "html") {
                $this->_render('pages/sources_table');
            } else if (strtolower($format) == "tab") {
                $this->output->set_header("Content-Type: text/plain");
                $this->load->view('pages/sources_table_tab', $this->data);
            } else if (strtolower($format) == "json") {
                $this->output->set_content_type('application/json')->set_output(json_encode($this->data['counts']));
            } else {
                $this->_render('pages/sources_table');
            }
        }
    }

    function variantcount_curl_multi($federated_installs_array, $term) {


        // If there's some federated installs to search then go through each one and get the variant counts
        if (!empty($federated_installs_array)) {
            if (!array_key_exists('error', $federated_installs_array)) {
//				error_log("federated_installs_array -> " . print_r($federated_installs_array, 1));
                $curl_array = array();
                $ch = curl_multi_init();
                $count = 0;
                $urls = array();
                foreach ($federated_installs_array as $install) {
                    $count++;
                    $network_key = $install['network_key'];
                    error_log("MULTI NETWORK KEY -> $network_key");
                    $install_uri = $install['installation_base_url'];
                    $install_uri = rtrim($install_uri, "/");
                    $user_id = $this->ion_auth->user()->row()->id;

                    $url = $install_uri . "/discover_federated/variantcount/$term/$user_id/$network_key";
                    $urls[] = $url;
                    error_log("URL $count -----> $url");
                    $curl_array[$count] = curl_init($url);
                    curl_setopt($curl_array[$count], CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($curl_array[$count], CURLOPT_RETURNTRANSFER, 1);
                    curl_multi_add_handle($ch, $curl_array[$count]);
                }

                do {
                    curl_multi_exec($ch, $exec);
                } while ($exec > 0);

//				error_log("ERROR -> " . curl_error($ch));
                foreach ($urls as $count => $url) {
                    error_log("returned $curl_array[$count]");
                    $returned = curl_multi_getcontent($curl_array[$count]);
//					echo "$url - $returned";
                    error_log("url returned: $url - $returned");
                }


                foreach ($urls as $count => $url) {
                    curl_multi_remove_handle($ch, $curl_array[$count]);
                }

                curl_multi_close($ch);

                foreach ($urls as $count => $url) {
                    curl_close($curl_array[$count]);
                }

//					$all_counts_json = @file_get_contents($install_uri . "/discover_federated/variantcount/$term/$user_id/$network_key", false, $context);
////					$all_counts_json = @file_get_contents($install_uri . "/discover_federated/variantcount/$term/$user_id/$network_key");
////					error_log(print_r($http_response_header, 1));
//					error_log("all_counts_json -> $all_counts_json");
//
//					$all_counts = json_decode($all_counts_json, 1);
//					$federated_site_title = $all_counts['site_title'];
//					unset($all_counts['site_title']);
////					error_log("all counts decoded -> " . print_r($all_counts, 1));
//					if ( ! empty($all_counts) ) {
//						foreach ( $all_counts as $federated_source => $counts_for_source ) {
//							
//							$federated_source_name = $federated_source . "__install_$c";
//							error_log("counts for source $federated_source_name -> " . print_r($counts_for_source, 1));
////							error_log("adding to " . $federated_source);
//							$sources[$federated_source_name] = "$federated_source ($federated_site_title)";
////							error_log("sources_full adding -> " . print_r($sources, 1));
//							$data['counts'][$federated_source_name] = $counts_for_source;
//							$data['install_uri'][$federated_source_name] = $install_uri;
////							error_log("-----------> " . print_r($this->data['install_uri'], 1));
//							if ( empty($from_url_query) ) {
//								$data['source_types'][$federated_source_name] = "federated";
//							}
//							else {
//								$this->data['source_types'][$federated_source_name] = "federated";
//							}
//
//						}
//					}
//				}
            }
        }
    }

    // 
    function variantcount($term = "", $source = "", $format = "", $mutalyzer_check = "") {
//		sleep(1);
//		error_log("variantcount -> $term $source $format");
//		$this->output->enable_profiler(TRUE);
        if ($this->input->post('term')) { // The inputs come from the form
            $network = $this->input->post('network');
            error_log("network -> $network");
            $term = $this->input->post('term');
            $source = $this->input->post('source');
            $lab = $this->input->post('lab');
            $mutalyzer_check = $this->_isBoolean($this->input->post('mutalyzer_check'));
            $grouping_type = $this->input->post('grouping_type');
        } else {
            $term = urldecode($term);
            $mutalyzer_check = $this->_isBoolean($mutalyzer_check);
            $from_url_query = true;
        }
//		error_log("term: $term | source: $source | format: $format | mutalyzer_check: $mutalyzer_check");
//		$term = urlencode($term);
        if ($term) {
//			error_log("POST -> " . print_r($_POST, true));
            if (empty($from_url_query)) {
                $data['term'] = $term;
            } else {
                $term = urlencode($term);
                $this->data['term'] = $term;
            }

            $sources = array();
            if (!$this->config->item('show_sources_in_discover')) {
//				error_log("form source -> $source");
                $source = "all";
            }
            if (preg_match('/all/i', $source)) { // All sources specified, get descriptions
                $sources = $this->sources_model->getSources();
            } else { // Just one source, get description
                $sources = $this->sources_model->getSourceSingle($source);
            }
            $sources_types = $this->sources_model->getSourcesTypes();






            // Get the federated installs to search from session (set when the discovery interface first loads)
            $federated_installs = $this->session->userdata('federated_installs');
//			error_log("f -> $federated_installs");
            $federated_installs_array = json_decode($federated_installs, 1);

            // If there's some federated installs to search then go through each one and get the variant counts
            if (!empty($federated_installs_array)) {
//				$this->variantcount_curl_multi($federated_installs_array, $term);
                if (!array_key_exists('error', $federated_installs_array)) {
//					error_log("federated_installs_array -> " . print_r($federated_installs_array, 1));
                    $c = 0;

                    foreach ($federated_installs_array as $install) {
                        $c++;
                        $network_key = $install['network_key'];
                        error_log("NETWORK KEY -> $network_key");
                        $install_uri = $install['installation_base_url'];
                        $install_uri = rtrim($install_uri, "/");
                        $user_id = $this->ion_auth->user()->row()->id;
//						error_log("STARTING --> $term ---> " . $install_uri . "/discover/variantcount_federated/$term/$user_id");
//						$this->variantcount_federated($term);
//						$contents = curl_get_contents($install_uri . "/discover/variantcount_federated/$term");
                        error_log("calling -> " . $install_uri . "/discover/variantcount_federated/$term");
                        // Set the timeout for each call to federated installs to 5 seconds
                        $opts = array('http' =>
                            array(
                                'method' => 'GET',
                                'timeout' => 5
                            )
                        );
                        $context = stream_context_create($opts);


                        $all_counts_json = @file_get_contents($install_uri . "/discover_federated/variantcount/$term/$user_id/$network_key", false, $context);
//						$all_counts_json = @file_get_contents($install_uri . "/discover_federated/variantcount/$term/$user_id/$network_key");
//						error_log(print_r($http_response_header, 1));
                        error_log("all_counts_json -> $all_counts_json");

                        $all_counts = json_decode($all_counts_json, 1);
                        $federated_site_title = $all_counts['site_title'];
                        unset($all_counts['site_title']);
//						error_log("all counts decoded -> " . print_r($all_counts, 1));
                        if (!empty($all_counts)) {
                            foreach ($all_counts as $federated_source => $counts_for_source) {

                                $federated_source_name = $federated_source . "__install_$c";
                                error_log("counts for source $federated_source_name -> " . print_r($counts_for_source, 1));
//								error_log("adding to " . $federated_source);
                                $sources[$federated_source_name] = "$federated_source ($federated_site_title)";
//								error_log("sources_full adding -> " . print_r($sources, 1));
                                $data['counts'][$federated_source_name] = $counts_for_source;
                                $data['install_uri'][$federated_source_name] = $install_uri;
//								error_log("-----------> " . print_r($this->data['install_uri'], 1));
                                if (empty($from_url_query)) {
                                    $data['source_types'][$federated_source_name] = "federated";
                                } else {
                                    $this->data['source_types'][$federated_source_name] = "federated";
                                }
                            }
                        }
                    }
                }
            }

            error_log("FINISHING");
            if (empty($from_url_query)) {
                $data['sources_full'] = $sources;
            } else {
                $this->data['sources_full'] = $sources;
            }



            if (empty($from_url_query)) { // The query comes from the form through the website
                $this->load->view('pages/sources_table', $data); // Don't use _render as headers are already sent, html output from the view is sent back to ajax function and appended to div
            } else { // Query comes from a URL construction
                if (strtolower($format) == "html") {
                    $this->_render('pages/sources_table');
                } else if (strtolower($format) == "tab") {
                    $this->output->set_header("Content-Type: text/plain");
                    $this->load->view('pages/sources_table_tab', $this->data);
                } else if (strtolower($format) == "json") {
                    $this->output->set_content_type('application/json')->set_output(json_encode($this->data['counts']));
                } else {
                    $this->_render('pages/sources_table');
                }
            }
        } else {
            error_log("no search term was present");
            show_error("You must specify a search term");
        }
    }

    function query_builder_federated() {
        $this->load->library('elasticsearch');
        $check_if_running = $this->elasticsearch->check_if_running();
        if (!array_key_exists('ok', $check_if_running)) {
            show_error("The query builder interface is currently not accessible as Elasticsearch is not running. Please get an administrator to start Elasticsearch and then try again.");
        }
        $this->javascript = array('mustache.min.js', 'jquery.querybuilder.js');
        $this->css = array('jquery.querybuilder.css');

        $token = $this->session->userdata('Token');
        $data = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_all_installations_for_networks_this_installation_is_a_member_of");
        $federated_installs = json_decode(stripslashes($data), 1);
        error_log("federated_installs -> " . print_r($federated_installs, 1));

        $this->_render('query_builder/query_builder_federated');
    }

    function proceed_to_query($type) {
        
        $token = $this->session->userdata('Token');
        $networks = json_decode(authPostRequest($token, array('user_id' => $this->session->userdata('user_id')), $this->config->item('auth_server') . "/api/auth/get_networks_user_member_of"), 1);
        // $networks = json_decode(authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_networks_installation_member_of"), 1);
        $this->data['networks'] = array();

        // echo "<pre>";
        // var_dump($networks);
        // echo "</pre>";
        // return;

        foreach ($networks as $key => $value) {
            $this->data['networks'] += array($value['name'] => $value['network_key']);
        }

        $sources_options = $this->sources_model->getSources(); // Get all the available sources from db
        $this->setSources($sources_options);
        $this->data['sources_options'] = $sources_options;
        $laboratory_options = $this->sources_model->getLaboratoryCategories(); // Get all available diagnostic labs
        $source_counts = $this->sources_model->countOnlineSourceEntries(); // Get counts of variants for info box in discovery interface
        $this->data['source_counts'] = $source_counts;
        $this->data['laboratory_options'] = $laboratory_options;
        $ontologiesused = $this->sources_model->getOntologiesUsedAndRoots();
        $this->data['ontologiesused'] = $ontologiesused;
        $jstree = $this->_generate_jstree($ontologiesused);
        $this->data['jstree'] = $jstree;

        $this->data['type'] = $type;
        $this->_render('query_builder/check_for_networks');
    }

    function query_builder($network_key) {
        
//        $this->javascript = array('mustache.min.js', 'query_builder_config.js', 'query_builder.js');
//        $this->css = array('jquery.querybuilder.css');
//
////		$token = $this->session->userdata('Token');
////		$data = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_all_installations_for_networks_this_installation_is_a_member_of");
////		$federated_installs = json_decode(stripslashes($data), 1);
////		error_log("federated_installs -> " . print_r($federated_installs, 1));
//        $this->title = "Discover - Query Builder";
//        $this->_render("query_builder/main");
//        return;
        
        if ($network_key) {
            $this->session->set_userdata(array('network_key' => $network_key));
        } else {
                redirect('discover/proceed_to_query/query_builder', 'refresh');
        }

        // Check if the user is in the master network group for this network
        $user_id = $this->ion_auth->user()->row()->id;
        $is_user_member_of_master_network_group_for_network = json_decode(authPostRequest('', array('user_id' => $user_id, 'network_key' => $network_key), $this->config->item('auth_server') . "/api/auth_general/is_user_member_of_master_network_group_for_network"), 1);
//		error_log("is_user_member_of_master_network_group_for_network -> " . print_r($is_user_member_of_master_network_group_for_network,1));
        $network_master_group_test = $is_user_member_of_master_network_group_for_network['is_user_member_of_master_network_group_for_network'] == '1' ? true : false;
//		error_log("network_master_group_test -> " . $network_master_group_test);
        // Check if user is a member of the master network group, if not then don't allow to proceed further and show error message
        if (!$network_master_group_test) {
            show_error("You are not a member of the master group for this network so cannot access any discovery interfaces. In order to search any networks you need to get an administrator to add you to the master network group for each network.");
        }
        
        $this->data['network_key'] = $network_key;
        
        $token = $this->session->userdata('Token');
        $data = authPostRequest($token, array('network_key' => $network_key), $this->config->item('auth_server') . "/api/auth/get_all_installations_for_network");
        $federated_installs = stripslashes($data);
        error_log("federated_installs -> $federated_installs");
        // Set the federated installs in the session so they can be used by variantcount
        $this->session->set_userdata(array('federated_installs' => $federated_installs));

        $this->load->library('elasticsearch');
        $check_if_running = $this->elasticsearch->check_if_running();
        if (!array_key_exists('ok', $check_if_running)) {
            show_error("The query builder interface is currently not accessible as Elasticsearch is not running. Please get an administrator to start Elasticsearch and then try again.");
        }
        $this->javascript = array('mustache.min.js', 'query_builder_config.js', 'query_builder.js');
        $this->css = array('jquery.querybuilder.css');

//		$token = $this->session->userdata('Token');
//		$data = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_all_installations_for_networks_this_installation_is_a_member_of");
//		$federated_installs = json_decode(stripslashes($data), 1);
//		error_log("federated_installs -> " . print_r($federated_installs, 1));
        $this->title = "Discover - Query Builder";
        $this->_render("query_builder/main");
    }

    function get_phenotype_attributes_nr_list() {
        $this->load->model('phenotypes_model');
        $phenotype_attributes_nr_list = $this->phenotypes_model->getPhenotypeAttributesNRList();
//		print_r($phenotype_attributes_nr_list);
//		error_log(print_r($phenotype_attributes_nr_list, 1));
        echo json_encode($phenotype_attributes_nr_list);
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

    function validate_gene() {
        $gene = $this->input->post('term');
        $this->load->model('general_model');
        $does_gene_exist = $this->general_model->checkGeneExists($gene);
//		error_log("validating -> $gene -> $does_gene_exist");
        if ($does_gene_exist) {
            echo json_encode(array('status' => 'Validated', 'message' => "This is a valid gene symbol"));
        } else {
            echo json_encode(array('status' => 'Not validated', 'message' => "This is NOT a valid gene symbol, however, you may still use it in your query if you wish"));
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

    function query_builder_results_display($id, $encoded_endpoint) {
        $endpoint = base64_decode(urldecode($encoded_endpoint));
//		error_log("$id -> $encoded_endpoint -> $endpoint");
//		echo "$id -> $encoded_endpoint -> $endpoint";
        $this->load->model('query_model');
        $query_history = $this->query_model->getQueryBuilderHistorySingle($id, $endpoint);
//		print_r($query_history);
        $query_response = $query_history['query_response'];
        print_r($query_response);
    }

    function query($network = '') {

        // Check if there's a network key supplied in the URL, if not then check if it's set in the session, if not then redirect back to the select network page
//		if ( $network_key ) {
//			$this->session->set_userdata(array('network_key' => $network_key));
//		}
//		else {
//			$network_key = $this->session->userdata('network_key');
//			if ( ! $network_key ) {
//				redirect('discover/proceed_to_query/standard_search', 'refresh');
//			}
//		}
//		$this->data['network_key'] = $network_key;

        $query = $this->input->post('jsonAPI');
//        error_log("STARTING QUERY");

        $network_to_search = $query['network_to_search'];
        $this->data['network_key'] = $network_to_search;
//        error_log("network_to_search -> " . $network_to_search . " -> " . $network);


        $parameters = array('syntax' => 'elasticsearch');
        $this->load->library('CafeVariome/Query', $parameters, 'query');
        $query_statement = $this->query->parse($query);
        $term = $query_statement;

        if ($term) {
//            error_log("POST -> " . print_r($_POST, true));
            $data['term'] = $term;
//            error_log("Term: " . $term);
        } else {
            show_error("You must specify a search term");
        }
        
//        return;
        
        $sources = array();
        $sources = $this->sources_model->getSources();

        $term = urlencode($term);

        // Get the federated installs to search from session (set when the discovery interface first loads)
        $federated_installs = $this->session->userdata('federated_installs');
//		error_log("f -> $federated_installs");
        $federated_installs_array = json_decode($federated_installs, 1);

        // If there's some federated installs to search then go through each one and get the variant counts
        if (!empty($federated_installs_array)) {
//			$this->variantcount_curl_multi($federated_installs_array, $term);
            if (!array_key_exists('error', $federated_installs_array)) {
//				error_log("federated_installs_array -> " . print_r($federated_installs_array, 1));
                $c = 0;

                foreach ($federated_installs_array as $install) {
                    $c++;
                    $network_key = $install['network_key'];
//                    error_log("NETWORK KEY -> $network_key");
                    $install_uri = $install['installation_base_url'];
                    $install_uri = rtrim($install_uri, "/");
                    $user_id = $this->ion_auth->user()->row()->id;
                    // error_log("STARTING --> $term ---> " . $install_uri . "/discover_federated/variantcount/$term/$user_id/$network_key");
//					$this->variantcount_federated($term);
//					$contents = curl_get_contents($install_uri . "/discover/variantcount_federated/$term");
//                    error_log("calling -> " . $install_uri . "/discover/query_federated/$term");
//                    if($install_uri !== "http://143.210.153.155/cafevariome_client") continue;
                    // Set the timeout for each call to federated installs to 5 seconds
                    $opts = array('http' =>
                        array(
                            'method' => 'GET',
                            'timeout' => 5
                        )
                    );
                    $context = stream_context_create($opts);


                    $all_counts_json = @file_get_contents($install_uri . "/discover_federated/variantcount/$term/$user_id/$network_key", false, $context);
//					$all_counts_json = @file_get_contents($install_uri . "/discover_federated/variantcount/$term/$user_id/$network_key");
//					error_log(print_r($http_response_header, 1));
                    // error_log("all_counts_json -> $all_counts_json");
                    // error_log("--------------------------");

                    $all_counts = json_decode($all_counts_json, 1);
                    $federated_site_title = $all_counts['site_title'];
                    unset($all_counts['site_title']);
//					error_log("all counts decoded -> " . print_r($all_counts, 1));
                    if (!empty($all_counts)) {
                        foreach ($all_counts as $federated_source => $counts_for_source) {

                            $federated_source_name = $federated_source . "__install_$c";
//                            error_log("counts for source $federated_source_name -> " . print_r($counts_for_source, 1));
//							error_log("adding to " . $federated_source);
                            $sources[$federated_source_name] = "$federated_source ($federated_site_title)";
//							error_log("sources_full adding -> " . print_r($sources, 1));
                            $data['counts'][$federated_source_name] = $counts_for_source;
                            $data['install_uri'][$federated_source_name] = $install_uri;
//							error_log("-----------> " . print_r($this->data['install_uri'], 1));

                            $data['source_types'][$federated_source_name] = "federated";
                        }
                    }
                }
            }
        }

        $data['sources_full'] = $sources;

        

        $this->load->view('pages/sources_table', $data); // Don't use _render as headers are already sent, html output from the view is sent back to ajax function and appended to div
    }

    function view_variants_in_ucsc($term, $source, $sharing_policy) {
        if (preg_match('/openAccess|linkedAccess/i', $sharing_policy)) {
            if ($this->config->item('use_elasticsearch')) {
                $variants = $this->getVariantsElasticSearch($term, $source, $sharing_policy);
            } else {
                $variants = $this->getVariants($term, $source, $sharing_policy);
            }
            $region = $this->_find_most_dense_region($variants);
            $data = array();
            $data['variants'] = $variants;
            $data['term'] = $term;
            $data['source'] = $source;
            $data['sharing_policy'] = $sharing_policy;
            $this->output->set_header("Content-Type: text/plain");
            $bed_variants = $this->load->view('pages/variantsbed', $data, TRUE);
            $date_time = date("d_m_Y-H_i_s");
//			$bed_filename = $term . "_" . $source . "_" . $date_time . ".bed";
            $bed_filename = $source . "_" . $date_time . ".bed";
            if ($fp = fopen(FCPATH . "upload/" . $bed_filename, 'w')) {
                fwrite($fp, $bed_variants);
                fclose($fp);
                $ucsc_link = "http://genome.ucsc.edu/cgi-bin/hgTracks?org=human&position=$region&hgt.customText=" . base_url("upload/$bed_filename");
//				$ucsc_link = "http://genome.ucsc.edu/cgi-bin/hgTracks?org=human&position=$region&hgt.customText=http://www.cafevariome.org/cv.bed";
                redirect($ucsc_link, 'refresh');
            } else {
                show_error("Couldn't write to $bed_filename");
            }
        } elseif (preg_match('/restrictedAccess/i', $sharing_policy)) {
            show_error("Sorry, restrictedAccess variants cannot be displayed as remote tracks in UCSC. If you wish to view this data as a track in UCSC the data owner must set the variants to either openAccess or linkedAccess");
        }
    }

    function _find_most_dense_region($variants) {
        $chrs = array();
        foreach ($variants as $k => $v) {
//			error_log(print_r($v, 1));

            if (isset($v['location_ref'])) {
                if (isset($chrs[$v['location_ref']])) {
//					error_log("id -> " . $v['cafevariome_id'] . " -> " . $v['location_ref']);
                    $chrs[$v['location_ref']] ++;
//					error_log("set -> " . $chrs[$v['location_ref']]);
                } else {
                    $chrs[$v['location_ref']] = 1;
//					error_log("not set");			
                }
            }

            if (isset($v['start'])) {
                if ($v['start'] != "") {
                    $starts[$k] = $v['start'];
                }
            }
            if (isset($v['end'])) {
                if ($v['end'] != "") {
                    $end[$k] = $v['end'];
                }
            }
        }
        $start_min = min($starts);
        $start_max = max($starts);
        $chr_maxs = array_keys($chrs, max($chrs));
        $chr = $chr_maxs[0];
//		error_log("chroms -> " . print_r($chroms, 1));
//		error_log("start min -> $min, start max $max");
        $region = "$chr:$start_min-$start_max";
//		error_log("region -> $region");
        return $region;
    }

    function variants($term, $source, $sharing_policy, $format = NULL) {

        // Return error if the installation has turned off allowing record hits to be diplayed
        if ($this->config->item('disable_record_hits_display')) {
            show_error("The display of record hits has been disabled for the installation you are trying to access");
        }

//		error_log("term -> " . $term . " -> " . urldecode($term));
        $term = html_entity_decode($term);
//		$term = urldecode($term);
        $this->session->set_userdata('return_to', "discover/variants/$term/$source/$sharing_policy/$format"); // Set session return_to value so if variants are restrictedAccess the user will be directed back to the requested page after logging in (hook for this is post_controller so it is not getting called here)
        if ($term && $source && $sharing_policy) {
            $this->data['term'] = $term;
            $this->data['source'] = $source;
            $this->data['sharing_policy'] = $sharing_policy;
            $s = $this->sources_model->getSourceSingle($source);

            // Check if this source actually exists
            if (!$s) {
                show_error("The specified source does not seem to exist in this instance");
            }
            // If no format is provided then default to html table display
            if (!isset($format)) { // If no format specified set it to html as default
                $format = "html";
            }

            $sources_types = $this->sources_model->getSourcesTypes();
            $type = $sources_types[$source];


            $source_full = $s[$source];
            $this->data['source_full'] = $source_full;
//			print "term -> " . $term . " source -> " . $source . " sharing -> " . $sharing_policy;
            $lab = "";
            if ($this->config->item('stats')) { // If stats logging is enabled then update the stats table for this query
                $ip = getRealIpAddr();
                $variant_stats = array(
                    'ip' => $ip,
                    'term' => $term,
                    'source' => $source,
                    'sharing_policy' => $sharing_policy,
                    'format' => $format,
                    'datetime' => date('d-m-Y H:i:s')
                );
                updateStats($variant_stats, 'variantstats');
            }
//			$count = $this->input->post('count');
            $source_info = $this->sources_model->getSource($source);
            $source_uri = $source_info['uri'];

            if (preg_match('/openAccess/i', $sharing_policy)) {

                if ($type == "api") {
                    // If federated API then just run the search via the node, don't need to format the data like below as this is already done by the federated source so just echo it
                    $variants = $this->runAPISearchForVariants($source_uri, $source, $term, $sharing_policy, $format);
                    echo $variants;
                    exit();
                } else {
                    if ($this->config->item('use_elasticsearch')) {
                        $variants = $this->getVariantsElasticSearch($term, $source, "openAccess");
                    } else {
                        $variants = $this->getVariants($term, $source, "openAccess");
                    }
                }
                // Get the dynamic display fields that can be changed by user in settings interface
                $this->load->model('settings_model');
                $display_fields = $this->settings_model->getDisplayFieldsForSharingPolicy('openAccess');
                $this->data['display_fields'] = $display_fields;
                if (strtolower($format) == "html") {
                    error_log("hello ---------->" . print_r($variants));
                    $this->data['variants'] = $variants;
                    $this->_render('pages/variantshtml');
                }
//				elseif ( strtolower($format) == "vcf") {
//					$data = array();
//					$data['variants'] = $variants;
//					$this->output->set_header("Content-Type: text/plain");
//					$this->load->view('pages/variantsvcf', $data); // don't use _render for other formats as don't need the headers and just want plain text page
//				}
                elseif (strtolower($format) == "varioml") {
                    $source_id = $this->sources_model->getSourceIDFromName($source);
                    $source_data = $this->sources_model->getSourceSingleFull($source_id);
                    // Create and display the VarioML
                    $this->_generateVarioml($variants, $source_data);
                } elseif (strtolower($format) == "json") {
                    $variants_json = array();
                    ksort($variants);
                    foreach ($variants as $variant) {
                        foreach ($display_fields as $display_field) {
                            if (array_key_exists($display_field['name'], $variant)) {
                                if ($display_field['name'] == "cafevariome_id") {
                                    $variants_json[$this->config->item('cvid_prefix') . $variant['cafevariome_id']][$display_field['name']] = $this->config->item('cvid_prefix') . $variant[$display_field['name']];
                                } else {
                                    $variants_json[$this->config->item('cvid_prefix') . $variant['cafevariome_id']][$display_field['name']] = $variant[$display_field['name']];
                                }
                            }
                        }
                    }
                    $this->output->set_content_type('application/json')->set_output(json_encode($variants_json));
                } elseif (strtolower($format) == "lovd") {
//					$this->_render('pages/variantslovd');
                    $data = array();
                    $data['variants'] = $variants;
                    $this->output->set_header("Content-Type: text/plain");
                    $this->load->view('pages/variantslovd', $data);
                } elseif (strtolower($format) == "tab") {
                    $data = array();
                    $data['variants'] = $variants;
                    $data['display_fields'] = $display_fields;
                    $this->output->set_header("Content-Type: text/plain");
                    $this->load->view('pages/variantstab', $data);
                } elseif (strtolower($format) == "bed") {
                    $data = array();
                    $data['variants'] = $variants;
                    $data['term'] = $term;
                    $data['source'] = $source;
                    $data['sharing_policy'] = $sharing_policy;
                    $this->output->set_header("Content-Type: text/plain");
                    $this->load->view('pages/variantsbed', $data);
                } elseif (strtolower($format) == "gff") {
                    $data = array();
                    $data['variants'] = $variants;
                    $data['term'] = $term;
                    $data['source'] = $source;
                    $data['sharing_policy'] = $sharing_policy;
                    $this->output->set_header("Content-Type: text/plain");
                    $this->load->view('pages/variantsgff', $data);
                } elseif (strtolower($format) == "rss") {
                    $data['encoding'] = 'utf-8';
                    $data['feed_name'] = 'Cafe Variome';
                    $data['feed_url'] = 'http://www.cafevariome.org';
                    $data['page_description'] = 'Cafe Variome RSS Feed';
                    $data['page_language'] = 'en-ca';
                    $data['creator_email'] = 'admin@cafevariome.org';
                    $data['variants'] = $variants;

                    header("Content-Type: application/rss+xml");
                    $this->load->view('pages/variantsrss', $data);
                } elseif (strtolower($format) == "excel") {
                    $this->writeExcel($term, $source, $variants, $display_fields);
                } else {
                    show_error("Sorry, '$format' is not recognised as a output format for Cafe Variome.");
                }
            } else if (preg_match('/restrictedAccess/i', $sharing_policy)) {

                if (isset($_SERVER['PHP_AUTH_USER'])) { // Basic authentication method of accessing (used by non-local website based traffic)
//					error_log("AUTH -> " . $_SERVER['PHP_AUTH_USER']);
                    $username = $_SERVER['PHP_AUTH_USER'];
                    $password = $_SERVER['PHP_AUTH_PW'];
//					error_log(print_r($_SERVER, 1));

                    if ($type == "api") {
                        // If federated API then just run the search via the node, don't need to format the data like below as this is already done by the federated source so just echo it
                        $variants = $this->runAPISearchForVariants($source_uri, $source, $term, $sharing_policy, $format, $username, $password);
                        echo $variants;
                        exit();
                    }

                    $this->load->model('general_model');
                    $is_valid = $this->general_model->authenticateUser($username, $password);
                    if ($is_valid) {
                        $user_id = $is_valid['id'];
//						error_log("VALID -> " . print_r($is_valid, 1));
                    } else {
//						error_log("NOT VALID");
                        $response_code = "401";
                        $response_text = $this->http_response_code($response_code);
                        header("HTTP/1.0 $response_code $response_text");
                        exit();
                    }
                } else if (!$this->ion_auth->logged_in()) { // Website based login authentication
                    redirect('auth/login', 'refresh');
                }

                // Check to see whether this user has the required group level access
                // Get the ID of the source and fetch the groups that it belongs to
//				$token = $this->session->userdata('Token');
//				$returned_sources = authPostRequest($token, array('user_id' => $user_id, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_sources_for_installation_that_user_id_has_count_display_group_access_to");
//				print "$returned_sources";

                $source_id = $this->sources_model->getSourceIDFromName($source);
                $current_source_groups = $this->sources_model->getSourceGroups($source_id);
                $source_group_ids = array();
                foreach ($current_source_groups as $source_group) {
//					error_log("source group -> " . $source_group['group_id']);
                    $source_group_ids[] = $source_group['group_id'];
                }

                // Get the id of the current user (if not already obtained from the basic auth validation method) and fetch the groups that they belong to
                if (!isset($user_id)) {
//					error_log("user id not defined");
                    $user_id = $this->ion_auth->user()->row()->id;
//					error_log("userid $user_id");
                } else {
//					error_log("already got userid $user_id");
                }
                $user_group_ids = array();
                foreach ($this->ion_auth->get_users_groups($user_id)->result() as $group) {
//					echo "groupid -> " . $group->id . " groupname -> " . $group->name . " description -> " . $group->description;
//					$groups_in[] = $group->id;
                    $user_group_ids[] = $group->id;
//					error_log("user group -> " . $group->id);
                }

                // Check whether the user is a group that this source belongs to
                $diff = array_intersect($user_group_ids, $source_group_ids);
                // If the intersect array is empty it means they are not in any of the required groups so cannot directly access
                // the variants and so instead have to fill in form on the data request page
                if (empty($diff)) {
                    $this->title = "Data Request";
                    $this->form_validation->set_rules('justification', 'Justification', 'required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
//						$this->load->view('myform');
                        $this->_render('pages/requestvariants');
                    } else {
                        $justification = $_POST['justification'];
                        $source = $_POST['source'];
                        $source_full = $_POST['source_full'];
                        $term = $_POST['term'];
//						error_log("source_full -> $source_full | source -> $source | term -> $term");
                        $datetime = date('d-m-Y H:i:s');
                        $ip = getRealIpAddr();
                        $source_details = $this->sources_model->getSourceOwner($source);
                        $email = $source_details['email'];
                        $owner_name = $source_details['owner_name'];
                        $institute = "get institute for this user from database";
//						$username = $this->ion_auth->
                        $username = $this->session->userdata('username');
                        $this->load->helper('string');
                        $string = random_string('unique');
                        $user = $this->ion_auth->user($this->session->userdata('user_id'))->row();
                        $data = array(
                            'justification' => $justification,
                            'username' => $username,
                            'email' => $user->email,
                            'datetime' => $datetime,
                            'source' => $source,
                            'term' => $term,
                            'ip' => $ip,
                            'string' => $string,
                            'result' => 'pending'
                        );
                        $this->load->model('general_model');
                        $this->general_model->insertDataRequest($data);
                        // Should clear the form data
//						$this->session->set_flashdata('message', 'Data Request Success');

                        $approve_deny_uri = base_url("discover/requestaction/$string");

                        $this->load->model('messages_model');
//						$sender_id = $this->ion_auth->user()->row()->id;
                        $sender_id = "1";

                        // Get admin users and send them the request message
                        $recipients = $this->messages_model->get_admin_user_ids();
                        $this->messages_model->send_new_message($sender_id, $recipients, 'Data request', "There is a data request from the user $username for variants in $source. As a site administrator go to the <a href='" . base_url() . "admin/data_requests'>following page</a> to process the request. Alternatively you can choose to give this user group privildges for the source so that they can access all restrictedAccess records.");

                        // Get curators users and filter out which ones can curate this source then use these users for sending the request message
                        $tmp_recipients = $this->messages_model->get_curator_user_ids();
                        $recipients = array();
                        foreach ($tmp_recipients as $user_id) {
                            $query = $this->sources_model->getSourcesThatTheUserCanCurate($user_id);
//							error_log("query -> " . print_r($query, 1));
                            $can_curate_flag = FALSE;
                            foreach ($query->result() as $curate_source) {
//								error_log(print_r($curate_source, 1));
                                if ($source == $curate_source->name) {
//									error_log("$source vs " . $curate_source->name);
                                    $recipients[] = $user_id;
                                }
                            }
                        }
                        $this->messages_model->send_new_message($sender_id, $recipients, 'Data request', "There is a data request from the user $username for variants in $source. As a curator for this source go to the <a href='" . base_url() . "curate/data_requests'>following page</a> to process the request. Alternatively you can choose to give this user group privildges for the source so that they can access all restrictedAccess records.");

                        // Also send an email to the source owner (who may or may not be admin/curator), the link in the email lets them approve/refuse the request
                        $this->load->library('email');
                        $this->email->from($this->config->item('email'), 'Admin');
                        $this->email->to($email);
                        $this->email->bcc($this->config->item('email'));
                        $this->email->subject('Data request');
//						$this->email->message("You have a data request from the user $username for variants in $source. As an administrator of this source you need to add the user to a group that can access restricted variants in this source. They gave the following justification: $justification");
                        $this->email->message("You have a data request from $username. Go to $approve_deny_uri to decide what action to take.");
                        $this->email->send();

                        // TODO: Also need to send an email to the data requestor - and include the ID of the request so they can follow it up

                        $this->_render('pages/requestsuccess');
                    }
                }
                // User is a member of a group that has access to these variants so get the variants and then present access options
                else {


                    if ($this->config->item('use_elasticsearch')) {
                        $variants = $this->getVariantsElasticSearch($term, $source, "restrictedAccess");
                    } else {
                        $variants = $this->getVariants($term, $source, "restrictedAccess");
                    }

                    // Get the dynamic display fields that can be changed by user in settings interface

                    $this->load->model('settings_model');
                    $display_fields = $this->settings_model->getDisplayFieldsForSharingPolicy('restrictedAccess');
                    $this->data['display_fields'] = $display_fields;
                    if (strtolower($format) == "html") {
                        $this->data['variants'] = $variants;
                        $this->_render('pages/variantshtml');
                    }
//					elseif ( strtolower($format) == "vcf") {
//						$data = array();
//						$data['variants'] = $variants;
//						$this->output->set_header("Content-Type: text/plain");
//						$this->load->view('pages/variantsvcf', $data); // don't use _render for other formats as don't need the headers and just want plain text page
//					}
                    elseif (strtolower($format) == "varioml") {
                        $source_id = $this->sources_model->getSourceIDFromName($source);
                        $source_data = $this->sources_model->getSourceSingleFull($source_id);
                        // Create and display the VarioML
                        $this->_generateVarioml($variants, $source_data);
                    } elseif (strtolower($format) == "json") {
                        $variants_json = array();
                        ksort($variants);
                        foreach ($variants as $variant) {
                            foreach ($display_fields as $display_field) {
                                if (array_key_exists($display_field['name'], $variant)) {
                                    if ($display_field['name'] == "cafevariome_id") {
                                        $variants_json[$this->config->item('cvid_prefix') . $variant['cafevariome_id']][$display_field['name']] = $this->config->item('cvid_prefix') . $variant[$display_field['name']];
                                    } else {
                                        $variants_json[$this->config->item('cvid_prefix') . $variant['cafevariome_id']][$display_field['name']] = $variant[$display_field['name']];
                                    }
                                }
                            }
                        }
                        $this->output->set_content_type('application/json')->set_output(json_encode($variants_json));
                    } elseif (strtolower($format) == "lovd") {
//						$this->_render('pages/variantslovd');
                        $data = array();
                        $data['variants'] = $variants;
                        $this->output->set_header("Content-Type: text/plain");
                        $this->load->view('pages/variantslovd', $data);
                    } elseif (strtolower($format) == "tab") {
                        $data = array();
                        $data['variants'] = $variants;
                        $data['display_fields'] = $display_fields;
                        $this->output->set_header("Content-Type: text/plain");
                        $this->load->view('pages/variantstab', $data);
                    } elseif (strtolower($format) == "bed") {
                        $data = array();
                        $data['variants'] = $variants;
                        $data['term'] = $term;
                        $data['source'] = $source;
                        $data['sharing_policy'] = $sharing_policy;
                        $this->output->set_header("Content-Type: text/plain");
                        $this->load->view('pages/variantsbed', $data);
                    } elseif (strtolower($format) == "gff") {
                        $data = array();
                        $data['variants'] = $variants;
                        $data['term'] = $term;
                        $data['source'] = $source;
                        $data['sharing_policy'] = $sharing_policy;
                        $this->output->set_header("Content-Type: text/plain");
                        $this->load->view('pages/variantsgff', $data);
                    } elseif (strtolower($format) == "rss") {
                        $data['encoding'] = 'utf-8';
                        $data['feed_name'] = 'Cafe Variome';
                        $data['feed_url'] = 'http://www.cafevariome.org';
                        $data['page_description'] = 'Cafe Variome RSS Feed';
                        $data['page_language'] = 'en-ca';
                        $data['creator_email'] = 'admin@cafevariome.org';
                        $data['variants'] = $variants;
                        header("Content-Type: application/rss+xml");
                        $this->load->view('pages/variantsrss', $data);
                    } elseif (strtolower($format) == "excel") {
                        $this->writeExcel($term, $source, $variants, $display_fields);
                    } else {
                        show_error("Sorry, '$format' is not recognised as a output format for Cafe Variome.");
                    }
                }
            } else if (preg_match('/linkedAccess/i', $sharing_policy)) {
                if ($type == "api") {
                    // If federated API then just run the search via the node, don't need to format the data like below as this is already done by the federated source so just echo it
                    $variants = $this->runAPISearchForVariants($source_uri, $source, $term, $sharing_policy, $format);
                    echo $variants;
                    exit();
                } else {
                    if ($this->config->item('use_elasticsearch')) {
                        $variants = $this->getVariantsElasticSearch($term, $source, "linkedAccess");
                    } else {
                        $variants = $this->getVariants($term, $source, "linkedAccess");
                    }
                }

                // Get the dynamic display fields that can be changed by user in settings interface
                $this->load->model('settings_model');
                $display_fields = $this->settings_model->getDisplayFieldsForSharingPolicy('linkedAccess');
                $this->data['display_fields'] = $display_fields;
                $sources_types = $this->sources_model->getSourcesTypes();
                $type = $sources_types[$source];
                if ($type == "das") {
                    error_log("das");
                }

                $this->data['variants'] = $variants; // Pass variants array to the view//		
                if (strtolower($format) == "html") {
                    $this->_render('pages/variantshtml');
                } elseif (strtolower($format) == "bed") {
                    $data = array();
                    $data['variants'] = $variants;
                    $data['term'] = $term;
                    $data['source'] = $source;
                    $data['sharing_policy'] = $sharing_policy;
                    $this->output->set_header("Content-Type: text/plain");
                    $this->load->view('pages/variantsbed', $data);
                } elseif (strtolower($format) == "gff") {
                    $data = array();
                    $data['variants'] = $variants;
                    $data['term'] = $term;
                    $data['source'] = $source;
                    $data['sharing_policy'] = $sharing_policy;
                    $this->output->set_header("Content-Type: text/plain");
                    $this->load->view('pages/variantsgff', $data);
                } elseif (strtolower($format) == "tab") {
                    $data = array();
                    $data['variants'] = $variants;
                    $data['display_fields'] = $display_fields;
                    $this->output->set_header("Content-Type: text/plain");
                    $this->load->view('pages/variantstab', $data);
                } elseif (strtolower($format) == "excel") {
                    $this->writeExcel($term, $source, $variants, $display_fields);
                } elseif (strtolower($format) == "json") {
                    $this->output->set_content_type('application/json')->set_output(json_encode($variants));
                } else {
                    show_error("Sorry, '$format' is not recognised as a linkedAccess output format for Cafe Variome.");
                }
            }
        } else {
            show_404();
        }
    }



    function variants_federated_restricted($term, $source, $federated_install_uri) {
        

        $federated_install_uri = base64_decode(urldecode($federated_install_uri));


        $term = urldecode($term);
        $term = html_entity_decode($term);
        $this->data['term'] = $term;
        $this->data['source'] = $source;
        // $this->data['sharing_policy'] = $sharing_policy;

        // $user_id = $this->ion_auth->user()->row()->id;



        $user_logged = false;


        if ($this->ion_auth->user()->row()->id)
            {   
                $user_logged = true;
            }


 
        if ($user_logged == false)
            {   
                redirect('auth/login', 'refresh');
            }
        else {
            $user_id = $this->ion_auth->user()->row()->id;
            $sharing_policy = "restrictedAccess";
            $format = "tab";

            $this->data['sharing_policy'] = $sharing_policy;
            $this->data['format'] = $format;

            $variants = @file_get_contents($federated_install_uri . "/discover_federated/variants_json_restricted/$term/$source/$sharing_policy/$format/$user_id");
            
            $variants = json_decode($variants, 1);

            if (array_key_exists('error', $variants)) {
                show_error($variants['error']);
            }

            // $sql = "SELECT * FROM sources WHERE name = '$source'";
            // $query = $this->db->query($sql);

            $source_owner = @file_get_contents($federated_install_uri . "/discover_federated/get_source_owner/$source");
            $source_owner = json_decode($source_owner, 1);
            
            $this->_render('pages/variantstab_restricted');

            $data['variants'] = $variants;
            $data['source_owner'] = $source_owner;
            
            // $data['display_fields'] = $display_fields;
            $this->output->set_header("Content-Type: text/html");
            $this->load->view('federated/pages/variantstab_restricted', $data);
            $this->output->set_header("Content-Type: text/html");
        }

    }






    // This is the call that's used to get variants from a federated installs in a network
    // The URL is used to make the call to the variants_json function in the discover_federated controller in the federated install
    // Returned data is json which is then rendered according to the display type specified
    function variants_federated($term, $source, $federated_install_uri, $sharing_policy, $format = NULL) {
//		error_log("source -> $source -> term -> " . $term . " -> " . urldecode($term));
        $federated_install_uri = base64_decode(urldecode($federated_install_uri));
//		error_log("federated_uri -> $federated_install_uri");
//		$term = html_entity_decode($term);

        if (strtolower($sharing_policy) == "linkedaccess") {
            $format = "html"; // Only show linkedAccess as html
        }
        if ($term && $source && $sharing_policy) {
            $term = urldecode($term);
            $term = html_entity_decode($term);
            $this->data['term'] = $term;
            $this->data['source'] = $source;
            $this->data['sharing_policy'] = $sharing_policy;

            // If no format is provided then default to html table display
            if (!isset($format)) { // If no format specified set it to html as default
                $format = "html";
            }

//			$sources_types = $this->sources_model->getSourcesTypes();
//			$type = $sources_types[$source];
//			$source_full = $s[$source];
//			$this->data['source_full'] = $source_full;
            $term = rawurlencode($term);
            $user_id = $this->ion_auth->user()->row()->id;
//			echo "sending -> " . $federated_install_uri . "/discover_federated/variants/$term/$source/$sharing_policy/$format";
            $variants = @file_get_contents($federated_install_uri . "/discover_federated/variants_json/$term/$source/$sharing_policy/$format/$user_id");
//			$variants = @file_get_contents($federated_install_uri . "/discover_federated/variants/$term/$source/$sharing_policy/$format/$user_id");
   //          echo "<pre>";
			// var_dump(json_decode($variants, 1));
   //          echo "</pre>";
   //          return;

            $variants = json_decode($variants, 1); // The json of variants for this source and other info returned from federated install
            // Print error message if any returned by federated client
            // Main error currently would be that the federated installation has turned off the allow_record_hits_display setting
            

            if (array_key_exists('error', $variants)) {
                show_error($variants['error']);
            }

            // $s = $variants['source']; // The remote source name and description from json
            $display_fields = $variants['display_fields']; // The display fields set for the federated install
//			error_log("ds -> " . print_r($display_fields, 1));
            unset($variants['display_fields']); // Remove display_fields from variants array - it's now stored in separate array
            unset($variants['source']); // Remove source from variants array - it's now stored in separate array
            if (strtolower($format) == "html") {
                //TODO: make this client side
//				$variants = @file_get_contents($federated_install_uri . "/discover_federated/variants/$term/$source/$sharing_policy/$format/$user_id");
                // error_log("hello-------> " . print_r($variants, 1));
                $this->data['variants'] = $variants;
                $this->data['display_fields'] = $display_fields;
                $this->data['federated_install_uri'] = $federated_install_uri;
                $this->_render('federated/pages/variantshtml');
//				echo $variants;
            } elseif (strtolower($format) == "bed") {
                $data = array();
                $data['variants'] = $variants;
                $data['term'] = $term;
                $data['source'] = $source;
                $data['sharing_policy'] = $sharing_policy;
                $this->output->set_header("Content-Type: text/plain");
                $this->load->view('federated/pages/variantsbed', $data);
//				echo $variants;
            } elseif (strtolower($format) == "gff") {
                $data = array();
                $data['variants'] = $variants;
                $data['term'] = $term;
                $data['source'] = $source;
                $data['sharing_policy'] = $sharing_policy;
                $this->output->set_header("Content-Type: text/plain");
                $this->load->view('federated/pages/variantsgff', $data);
//				echo $variants;
            } elseif (strtolower($format) == "tab") {
                $data = array();
                $data['variants'] = $variants;
                $data['display_fields'] = $display_fields;
                $this->output->set_header("Content-Type: text/plain");
                $this->load->view('federated/pages/variantstab', $data);
                $this->output->set_header("Content-Type: text/plain");
//				echo $variants;
            } elseif (strtolower($format) == "excel") {
//				echo $variants;
                $this->writeExcelFederated($term, $source, $variants, $display_fields);
            } elseif (strtolower($format) == "json") {
                echo $variants;
//				$this->output->set_content_type('application/json')->set_output(json_encode($variants));
            } else {
                show_error("Sorry, '$format' is not recognised as a linkedAccess output format for Cafe Variome.");
            }
        } else {
            show_404();
        }
    }

    function variant_federated($cafevariome_id, $federated_install_uri) {
        $user_id = $this->ion_auth->user()->row()->id;
        $federated_install_uri = base64_decode(urldecode($federated_install_uri));
//		echo $federated_install_uri . "/discover_federated/variant_json/$cafevariome_id/$user_id";
        $data = @file_get_contents($federated_install_uri . "/discover_federated/variant_json/$cafevariome_id/$user_id");
//		echo $data;
        $variant_json = json_decode($data, 1);
//		print_r($variant_json);
        // Print error message if any returned by federated client
        // Main error currently would be that the federated installation has turned off the allow_individual_record_display setting
        if (array_key_exists('error', $variant_json)) {
            show_error($variant_json['error']);
        }

        $this->data['phenotypes'] = $variant_json['phenotypes'];
        $this->data['source_email'] = $variant_json['source_email'];
        $this->data['individual_record_display_fields'] = $variant_json['individual_record_display_fields'];
        $this->data['variant'] = $variant_json['variant'];
        $this->data['cvid_prefix'] = $variant_json['cvid_prefix'];
//		
        $this->_render('federated/pages/variant');
    }

    function delete_request($request_id) {
        // Check that this is the requesting user and that the data request has been approved
        $username = $this->session->userdata('username');
        $request = $this->sources_model->getDataRequestByID($request_id);
        if ($username != $request['username']) {
            show_error("Sorry you are not logged in as the user who requested the data so unable to download");
        }
        $this->sources_model->deleteDataRequest($request_id);
        redirect('admin/data_access', 'refresh');
    }

    function download_requested_data($request_id, $term, $source, $format) {
        // Check that this is the requesting user and that the data request has been approved
        $username = $this->session->userdata('username');
        $request = $this->sources_model->getDataRequestByID($request_id);
        if ($request['result'] != 'approved') {
            show_error("Sorry the request has not been approved, unable to download");
        }
        if ($username != $request['username']) {
            show_error("Sorry you are not logged in as the user who requested the data so unable to download");
        }

        $term = html_entity_decode($term);
        if ($this->config->item('use_elasticsearch')) {
            $variants = $this->getVariantsElasticSearch($term, $source, "restrictedAccess");
        } else {
            $variants = $this->getVariants($term, $source, "restrictedAccess");
        }

        // Get the dynamic display fields that can be changed by user in settings interface

        $this->load->model('settings_model');
        $display_fields = $this->settings_model->getDisplayFieldsForSharingPolicy('restrictedAccess');
        $this->data['display_fields'] = $display_fields;

        if (strtolower($format) == "json") {
            $variants_json = array();
            ksort($variants);
            foreach ($variants as $variant) {
                foreach ($display_fields as $display_field) {
                    if (array_key_exists($display_field['name'], $variant)) {
                        if ($display_field['name'] == "cafevariome_id") {
                            $variants_json[$this->config->item('cvid_prefix') . $variant['cafevariome_id']][$display_field['name']] = $this->config->item('cvid_prefix') . $variant[$display_field['name']];
                        } else {
                            $variants_json[$this->config->item('cvid_prefix') . $variant['cafevariome_id']][$display_field['name']] = $variant[$display_field['name']];
                        }
                    }
                }
            }
            $this->output->set_content_type('application/json')->set_output(json_encode($variants_json));
        } elseif (strtolower($format) == "tab") {
            $data = array();
            $data['variants'] = $variants;
            $data['display_fields'] = $display_fields;
            $this->output->set_header("Content-Type: text/plain");
            $this->load->view('pages/variantstab', $data);
        } elseif (strtolower($format) == "bed") {
            $data = array();
            $data['variants'] = $variants;
            $data['term'] = $term;
            $data['source'] = $source;
            $data['sharing_policy'] = $sharing_policy;
            $this->output->set_header("Content-Type: text/plain");
            $this->load->view('pages/variantsbed', $data);
        } elseif (strtolower($format) == "excel") {
            $this->writeExcel($term, $source, $variants, $display_fields);
        }
    }

    function search_history() {
        if (!$this->ion_auth->logged_in()) {
            show_error("You must be logged in to view your search history");
        } else {
            $user = $this->ion_auth->user()->row();
            $user_id = $user->id;
            $this->load->model('search_model');
            $this->data['search_history'] = $this->search_model->getSearchHistory($user_id);
            $this->_render('pages/search_history');
        }
    }

    function getVariantsElasticSearch($term, $source, $sharing_policy) {
        $term = urldecode($term);
        if (preg_match('/chr\S+:\d+\-|\.\.\d+/', $term)) { // Match chromosome region regex
            error_log("region -> $term");
            $locations = $this->_splitRegion($term);
            $variants = $this->search_model->getVariantsForRegion($locations, $source, $sharing_policy);
        } elseif (preg_match('/N\S{1}_\S+:\S+/', $term)) {
            $ref_hgvs = $this->_splitRefHGVS($term);
            $variants = $this->search_model->getVariantsForRefHGVS($ref_hgvs, $source, $sharing_policy);
        } else {
            $this->load->library('elasticsearch');
            // Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
            $es_index = $this->config->item('site_title');
            $es_index = preg_replace('/\s+/', '', $es_index);
            $es_index = strtolower($es_index);
            $this->elasticsearch->set_index($es_index);
            $this->elasticsearch->set_type("variants");
            $query = array();
            $query['size'] = 10000000;
//			error_log("term -> $term");
//			$sanitize_query = htmlentities(strip_tags( $query ));
//			error_log("sanitize -> $sanitize_query");
//			$query['query']['query_string'] = array('query' =>  "$term AND $source", 'fields' => array("source", "gene"), 'default_operator' => "AND");
//			$query['indices'] = array ('hgvs', 'gene', 'hits');
//			$query['partial_fields']['_source']['include'] = array("gene", "hgvs");
//		    "partial_fields" : {
//				"partial1" : {
//					"include" : "obj1.obj2.*",
//				}
//			}

            $this->load->model('settings_model');
            $search_fields = $this->settings_model->getSearchFields("search_fields");

            if (is_array($search_fields)) {
                $search_fields_elasticsearch = array();
                foreach ($search_fields as $fields) {
                    $search_fields_elasticsearch[] = $fields['field_name'];
                }
//				error_log("search fields -> " . print_r($search_fields, 1));
                $query['query']['bool']['must'][] = array('query_string' => array("fields" => $search_fields_elasticsearch, "query" => "$term", 'default_operator' => "AND"));
            } else {
                $query['query']['bool']['must'][] = array('query_string' => array("query" => "$term", 'default_operator' => "AND"));
            }
            $query['query']['bool']['must'][] = array('query_string' => array("query" => "$sharing_policy"));

            $query['query']['bool']['must'][] = array("term" => array("included" => 1));
//			

            print "<h4>$term AND $sharing_policy</h4>";
//			$query['query']['bool']['must'][] = array("term" => array("sharing_policy" => $sharing_policy));
            $query['query']['bool']['must'][] = array("term" => array("source" => $source));
//			$query['facets']['sharing_policy']['terms'] = array('field' => "sharing_policy");
            $query = json_encode($query);
//			error_log("query ----> $query");
            $es_data = $this->elasticsearch->query_dsl($query);
//			print_r($es_data);
            $variants = array();
            foreach ($es_data['hits']['hits'] as $hit) {
//				print_r($hit);
                $score = $hit['_score'];
                $id = $this->config->item('cvid_prefix') . $hit['_id'];
                $variants[$id] = $hit['_source'];
//				print_r($variants[$id]);
//				print "<br />";
                $phenotypes_array = array();
//				error_log(print_r($hit['_source'], 1));
                if (array_key_exists('phenotypes', $hit['_source'])) {
                    foreach ($hit['_source']['phenotypes'] as $phenotype) {
//						error_log(print_r($phenotype,1));
                        foreach ($phenotype as $phenotype_attribute => $phenotype_value) {
                            $phenotype_attribute = str_replace('_', ' ', $phenotype_attribute);
                            $phenotypes_array[] = $phenotype_attribute . ": " . $phenotype_value;
                        }
//						
                    }
                    $phenotypes_string = implode(", ", $phenotypes_array);
//					$phenotypes_string = implode ("<br /> ", $phenotypes_array);
                    $variants[$id]['phenotype'] = $phenotypes_string;
//					print "$phenotypes_string<br /><br />";
                }
            }

            
        }
        return $variants;

//		$variants = $this->search_model->getVariantsForGene($term, $source, $sharing_policy);
//		return $variants;
    }

    function getVariants($term, $source, $sharing_policy) {
        $term = urldecode($term);
        if (preg_match('/chr\S+:\d+\-|\.\.\d+/', $term)) { // Match chromosome region regex
            $locations = $this->_splitRegion($term);
            $variants = $this->search_model->getVariantsForRegion($locations, $source, $sharing_policy);
        } elseif (preg_match('/N\S+_\S+:\S+/', $term)) {
            $ref_hgvs = $this->_splitRefHGVS($term);
            $variants = $this->search_model->getVariantsForRefHGVS($ref_hgvs, $source, $sharing_policy);
        } elseif (preg_match('/N\S+_\S+/', $term)) {
            $variants = $this->search_model->getVariantsForRef($term, $source, $sharing_policy);
        } elseif (preg_match('/LRG_\S+/', $term)) {
            $variants = $this->search_model->getVariantsForLRG($term, $source, $sharing_policy);
        } elseif (preg_match('/rs\d+/', $term)) {
            $variants = $this->search_model->getVariantsFordbSNP($term, $source, $sharing_policy);
        } elseif (preg_match('/[cp]\.\S+/', $term)) {
            $variants = $this->search_model->getVariantsForHGVS($term, $source, $sharing_policy);
        } else {
            $variants = $this->search_model->getVariantsForGene($term, $source, $sharing_policy);
        }
        return $variants;
    }

    function requestaction($string) {
        if (isset($string)) {
            $this->load->model('general_model');
            $request = $this->general_model->getDataRequest($string);
            if (!empty($request)) {
                $this->data['request'] = $request;
                $this->_render('pages/requestaction');
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    function confirmrequest() {
        $result = $this->input->post('result');
        $string = $this->input->post('string');
        $this->load->model('general_model');
        $request_data = $this->general_model->getDataRequest($string);
        $email = $request_data['email'];
        $resultreason = $this->input->post('resultreason');
        $confirm['result'] = $result;
        $confirm['string'] = $string;
        $confirm['resultreason'] = $resultreason;
        $data['result'] = $result;
        $this->load->model('general_model');
        $this->general_model->updateRequestStatus($confirm);
        if ($result == "approved") {
            $username = $this->session->userdata('username');
            $this->load->library('email');
            $this->email->from($this->config->item('email'), 'Admin');
            $this->email->to($email);
//			$this->email->bcc('admin@cafevariome.org');
            $this->email->subject('Data request approved');
            $this->email->message("A data request has been approved, you can download the data from the <a href='" . base_url() . "admin/data_access'>following page (you must be logged in)</a>.");
            $this->email->send();
        }
        $this->load->view('pages/confirmrequest', $data);
    }

    function variant($cafevariome_id, $format = NULL) {

        // Show error if the install has turned off allowing display of individual records
        if ($this->config->item('disable_individual_record_display')) {
            show_error("The display of individual records has been disabled for the installation you are trying to access");
        }

        $variant = $this->sources_model->getVariant($cafevariome_id);
        $phenotypes = $this->sources_model->getPhenotypes($cafevariome_id);
        $source_email = $this->sources_model->getEmailFromSourceName($variant['source']);
        $this->load->model('settings_model');
        $individual_record_display_fields = $this->settings_model->getIndividualRecordDisplayFields();
        $this->data['individual_record_display_fields'] = $individual_record_display_fields;

        $format = strtolower($format);
        if (empty($variant)) {
            show_error("Sorry, this variant does not exist (please do not include the id prefix in the url).");
        }
        if (!$variant['active']) {
            show_error("Sorry, this variant is currently not active.");
        }
        if ($this->config->item('stats')) { // If stats logging is enabled update the count for this variant
            updateStats(array("cafevariome_id" => $cafevariome_id), 'variantcountstats');
        }

        $is_valid = "";

        if ($variant['sharing_policy'] === "openAccess") { // Variant is openAccess so can go ahead and display it
//			if ( $format == "json" ) {
////				$this->response($counts, 200);
////				$this->output->set_status_header('200');
////				$this->output->set_header("HTTP/1.1 200 OK");
//				$this->output->set_content_type('application/json')->set_output(json_encode($variant));
//			}
            if (strtolower($format) == "json") {
                $variant_json = array();
//				ksort($variant);
                foreach ($individual_record_display_fields as $individual_record_display_field) {
                    if (array_key_exists($individual_record_display_field['name'], $variant)) {
//						error_log("key -> " . $individual_record_display_field['name']);
                        if ($individual_record_display_field['name'] == "cafevariome_id") {
                            $variant_json[$this->config->item('cvid_prefix') . $variant['cafevariome_id']][$individual_record_display_field['name']] = $this->config->item('cvid_prefix') . $variant[$individual_record_display_field['name']];
                        } else {
                            $variant_json[$this->config->item('cvid_prefix') . $variant['cafevariome_id']][$individual_record_display_field['name']] = $variant[$individual_record_display_field['name']];
                        }
                    }
                }
//				error_log("variant json -> " . print_r($variant_json, 1));
                $this->output->set_content_type('application/json')->set_output(json_encode($variant_json));
            } else {
                $this->data['variant'] = $variant;
                $this->data['phenotypes'] = $phenotypes;
                $this->data['source_email'] = $source_email;
                $this->_render('pages/variant');
            }
        } elseif ($variant['sharing_policy'] === "linkedAccess") {
            if ($variant['source_url']) {
                $linkedAccessURL = $variant['source_url'];
                header("Location: $linkedAccessURL");
                exit;
//				redirect($variant['source_url'], 'location');
            } else {
//				echo "--> " . $variant['source_url'];
                show_error("There is no source URL available for this record");
            }
        } elseif ($variant['sharing_policy'] === "restrictedAccess") {
            if (isset($_SERVER['PHP_AUTH_USER'])) { // Basic authentication method of accessing (used by non-local website based traffic)
//				error_log("AUTH -> " . $_SERVER['PHP_AUTH_USER'] . " -> " . $_SERVER['PHP_AUTH_PW']);
                $username = $_SERVER['PHP_AUTH_USER'];
                $password = $_SERVER['PHP_AUTH_PW'];
//				error_log(print_r($_SERVER, 1));

                $this->load->model('general_model');
                $is_valid = $this->general_model->authenticateUser($username, $password);
                if ($is_valid) {
                    $user_id = $is_valid['id'];
//					error_log("VALID -> " . print_r($is_valid, 1));
                } else {
//					error_log("NOT VALID");
                    $response_code = "401";
                    $response_text = $this->http_response_code($response_code);
                    header("HTTP/1.0 $response_code $response_text");
                    exit();
                }
            } else if (!$this->ion_auth->logged_in()) { // Website based login authentication
                redirect('auth/login', 'refresh');
            }
            // Get the ID of the source this variant belongs to and fetch the groups that have access
            $source_id = $this->sources_model->getSourceIDFromName($variant['source']);
            $current_source_groups = $this->sources_model->getSourceGroups($source_id);
            $source_group_ids = array();
            foreach ($current_source_groups as $source_group) {
//				error_log("source group -> " . $source_group['group_id']);
                $source_group_ids[] = $source_group['group_id'];
            }

            // Get the id of the current user and fetch the groups that they belong to. N.B. If the call was made directly to the API by basic authentication then the user_id is already present so only fetch it if the call is made directly through the website
            if (!$is_valid) {
                $user_id = $this->ion_auth->user()->row()->id;
            }
            $user_group_ids = array();
            foreach ($this->ion_auth->get_users_groups($user_id)->result() as $group) {
//				echo "groupid -> " . $group->id . " groupname -> " . $group->name . " description -> " . $group->description;
//				$groups_in[] = $group->id;
                $user_group_ids[] = $group->id;
//				error_log("user group -> " . $group->id);
            }

            // Check whether the user is a group that this source belongs to
            $diff = array_intersect($user_group_ids, $source_group_ids);
            // If the intersect array is empty it means they are not in any of the required groups so cannot directly access
            // the variants. Instead generate the popup to request access
            if (empty($diff)) {
                show_error("Sorry, either this variant does not exist or you do not belong to a group that has the priviledges to access this variant.");
            } else {

                if (strtolower($format) == "json") {
                    $variant_json = array();
//					ksort($variant);
                    foreach ($individual_record_display_fields as $individual_record_display_field) {
                        if (array_key_exists($individual_record_display_field['name'], $variant)) {
//							error_log("key -> " . $individual_record_display_field['name']);
                            if ($individual_record_display_field['name'] == "cafevariome_id") {
                                $variant_json[$this->config->item('cvid_prefix') . $variant['cafevariome_id']][$individual_record_display_field['name']] = $this->config->item('cvid_prefix') . $variant[$individual_record_display_field['name']];
                            } else {
                                $variant_json[$this->config->item('cvid_prefix') . $variant['cafevariome_id']][$individual_record_display_field['name']] = $variant[$individual_record_display_field['name']];
                            }
                        }
                    }
//					error_log("variant json -> " . print_r($variant_json, 1));
                    $this->output->set_content_type('application/json')->set_output(json_encode($variant_json));
                } else {
                    $this->data['variant'] = $variant;
                    $this->data['phenotypes'] = $phenotypes;
                    $this->data['source_email'] = $source_email;
                    $this->_render('pages/variant');
                }
            }
        } else {
            show_error("This variant ($cafevariome_id) does not have a recognised sharing policy, please contact an administrator.");
        }
    }

    function phenotype($ppl_id = NULL) {
        if (empty($ppl_id)) {
            show_404();
        }

        $this->load->model('phenotypes_model');
        $phenotype = $this->phenotypes_model->getLocalPhenotype($ppl_id);
        if (empty($phenotype)) {
            show_404();
        }

        $this->data['phenotype'] = $phenotype;
        $this->_render('pages/phenotype');
    }

    function lookup() {
//		$this->load->model('search_model');
        // process posted form data
        $keyword = $this->input->post('term');
//		error_log("lookup -> " . $keyword);
        $data['response'] = 'false'; //Set default response
        $query = $this->search_model->lookupAutocomplete($keyword); //Search DB
//		error_log("got past query");
        if (!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            $json_array = array();
            foreach ($query->result() as $row) {
//				error_log("TESTING -> " . $row->term);
//				$data['message'][] = array('label' => $row->term,
//					'value' => $row->term,
//					'description' => $row->term,
//					'price' => $row->term); //Add a row to array
//				$data['message'][] = array('value' => $row->term,
//										'label' => $row->term,
//										'id'    => $row->term);
                $auto_val = $row->term;
//				$auto_val = $row->term . "      (" . $row->type . ")";
                array_push($json_array, $auto_val);
            }
        }
//		if (IS_AJAX) {


        echo json_encode($json_array); //echo json string if ajax request
//			$d = json_encode($json_array);
//			error_log($d);
//		}
//		else {
//			$this->load->view('search/index', $data); //Load html view of search results
//		}
    }

    function pheno_lookup() {
        if ($this->input->post('term')) {
            $queryString = $this->input->post('term');
            if (strlen($queryString) > 0) {
                $results = $this->search_model->lookupPhenoAutocomplete($queryString);
                echo json_encode($results);
            }
        } else {
            echo 'There should be no direct access to this script!';
        }
    }

    function return_node() {
        if ($this->input->post('ontology')) {
            $ont = $this->input->post('ontology');
            $queryString = $this->input->post('id');
            // Take care of the hash character that is present in some ids e.g. FMA
            $queryString = str_replace("XhashX", "#", $queryString);
            if (strlen($queryString) > 0) {
                $results = $this->sources_model->lookupOntNode($queryString, $ont);
                echo json_encode($results);
            }
        } else {
            echo 'There should be no direct access to this script!';
        }
    }

    function _generate_jstree($ontologiesused) {
        $jstree = '<script type="text/javascript">';
        foreach ($ontologiesused as $key => $val) {
            $openlist = '';
            $numopennodes = sizeof($val);
            for ($i = 0; $i < $numopennodes; $i++) {
                if ($i == 0) {
                    $rnode = $val[$i];
                    # Take care of hash characters that are used in some ids e.g. FMA
                    $rnode = str_replace("#", "XhashX", $rnode);
                    $openlist = '\'' . $rnode . '\'';
                } else {
                    $rnode = $val[$i];
                    # Take care of hash characters that are used in some ids e.g. FMA
                    $rnode = str_replace("#", "XhashX", $rnode);
                    $openlist = $openlist . ', \'' . $rnode . '\'';
                }
            }

            $jstree .= '
$(function () {

$("#' . $key . '_tree")
	.jstree({ 
		"themes" : {
			"theme" : "classic",
			"dots" : true,
			"icons" : false
		},
	
	
	
	
		// List of active plugins
		"plugins" : [ 
			"themes","json_data","ui" 
		],

		// uses JSON as it is most common
		"json_data" : { 
			// This tree is ajax enabled 
			"ajax" : {
				// the URL to fetch the data
				"url" : baseurl + "discover/return_node",
                                "type" : "POST",
				// the parameter is the node being loaded 
				// (may be -1, 0, or undefined when loading the root nodes)
				"data" : function (n) { 
					// the result is fed to the AJAX request `data` option
					return { 
						"ontology" : "' . $key . '", 
						//"id" : n.attr ? n.attr("id").replace("node_","") : 1
						"id" : n.attr ? n.attr("id") : 1
					}; 
				}
			}
		},
		
		"core" : { 
			// just open those two nodes up
			// as this is an AJAX enabled tree, both will be downloaded from the server
			//"initially_open" : [ "node_2" , "node_3" ] 
			"initially_open" : [ ' . $openlist . ' ],
                        "animation" : 100
		}
	})
        
        .bind("select_node.jstree", function (event, data) { 
			// `data.rslt.obj` is the jquery extended node that was clicked
			//alert(data.rslt.obj.attr("id"));
			//$("#phenotypeTreeModal").modal("hide");
			if ( $("#term").val() ) {
				$("#term").val($("#term").val() + " AND " + data.rslt.obj.attr("' . $key . '_label"));
			}
			else {
				$("#term").val(data.rslt.obj.attr("' . $key . '_label"));
			}
		})

});
';
        }
        $jstree .= '    </script>';
        return $jstree;
    }

    function variants_datatable() {
        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $sSortDir_0 = $this->input->get_post('sSortDir_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);
        $path = $this->input->get_post('path', true);
//		error_log("path -> $path sEcho -> " . $sEcho . " iDisplayStart -> " . $iDisplayStart . " iDisplayLength -> " . $iDisplayLength . " sSortDir_0 -> " . $sSortDir_0 . " iSortCol_0 -> " . $iSortCol_0 . " iSortingCols -> " . $iSortingCols . " sSearch -> " . $sSearch);
//		$path_array = explode('/', $path);
//		$path_count = count($path_array);
//		error_log("path array count -> $path_count -> " . print_r($path_array, 1));
//		if ( $path_count == 7 ) { // TODO: Had to do a bit of a hack here since linkedAccess variants don't have the display_type in the URL so do this here as a hack to add it manually as html so that the correct parts of the URI string are extracted
//			array_push($path_array, "html");
//		}
//		$display_type = array_pop($path_array);
//		$sharing_policy = array_pop($path_array);
//		$source = array_pop($path_array);
//		$term = array_pop($path_array);
        $sharing_policy = $this->input->get_post('sharing_policy', true);
        $source = $this->input->get_post('source', true);
        $term = $this->input->get_post('term', true);
//		error_log("data -> $sharing_policy $source $term");
//		if ( $this->config->item('use_elasticsearch')) {
        $variants = $this->getVariantsElasticSearch($term, $source, $sharing_policy);
//		}
//		else {
//			$variants = $this->getVariants($term, $source, $sharing_policy);
//		}
        $iTotalRecords = count($variants);

        // Ordering
        if (isset($iSortCol_0)) {
//			error_log("ordering");
            $sort = array();
            foreach ($variants as $key => $row) {
//				error_log("direction -> $sSortDir_0");
                if ($iSortCol_0 == 0) {
                    foreach ($variants as $key => $row) {
//						error_log("key -> $key | row -> $row -> " . $row['cafevariome_id']);
                        $sort[$key] = $row['cafevariome_id'];
                    }
                    if ($sSortDir_0 == "desc") {
                        array_multisort($sort, SORT_DESC, SORT_NUMERIC, $variants);
                    } elseif ($sSortDir_0 == "asc") {
                        array_multisort($sort, SORT_ASC, SORT_NUMERIC, $variants);
                    }
                } elseif ($iSortCol_0 == 1) {
                    foreach ($variants as $key => $row) {
//						error_log("key -> $key | row -> $row -> " . $row['cafevariome_id']);
                        $sort[$key] = $row['gene'];
                    }
                    if ($sSortDir_0 == "desc") {
                        array_multisort($sort, SORT_DESC, $variants);
                    } elseif ($sSortDir_0 == "asc") {
                        array_multisort($sort, SORT_ASC, $variants);
                    }
                } elseif ($iSortCol_0 == 2) {
                    foreach ($variants as $key => $row) {
//						error_log("key -> $key | row -> $row -> " . $row['cafevariome_id']);
                        $sort[$key] = $row['ref'];
                    }
                    if ($sSortDir_0 == "desc") {
                        array_multisort($sort, SORT_DESC, $variants);
                    } elseif ($sSortDir_0 == "asc") {
                        array_multisort($sort, SORT_ASC, $variants);
                    }
                } elseif ($iSortCol_0 == 3) {
                    foreach ($variants as $key => $row) {
//						error_log("key -> $key | row -> $row -> " . $row['cafevariome_id']);
                        $sort[$key] = $row['hgvs'];
                    }
                    if ($sSortDir_0 == "desc") {
                        array_multisort($sort, SORT_DESC, $variants);
                    } elseif ($sSortDir_0 == "asc") {
                        array_multisort($sort, SORT_ASC, $variants);
                    }
                } elseif ($iSortCol_0 == 4) {
                    foreach ($variants as $key => $row) {
//						error_log("key -> $key | row -> $row -> " . $row['cafevariome_id']);
                        $sort[$key] = $row['phenotype'];
                    }
                    if ($sSortDir_0 == "desc") {
                        array_multisort($sort, SORT_DESC, $variants);
                    } elseif ($sSortDir_0 == "asc") {
                        array_multisort($sort, SORT_ASC, $variants);
                    }
                } elseif ($iSortCol_0 == 5) {
                    foreach ($variants as $key => $row) {
//						error_log("key -> $key | row -> $row -> " . $row['cafevariome_id']);
                        $sort[$key] = $row['source'];
                    }
                    if ($sSortDir_0 == "desc") {
                        array_multisort($sort, SORT_DESC, $variants);
                    } elseif ($sSortDir_0 == "asc") {
                        array_multisort($sort, SORT_ASC, $variants);
                    }
                }
            }
        }

        // Filtering
        if (isset($sSearch) && !empty($sSearch)) {
//			error_log("filtering");
            $bSearchable = $this->input->get_post('bSearchable_0', true);
            if (isset($bSearchable) && $bSearchable == 'true') {
//				error_log("search -> $bSearchable -> $sSearch");
                foreach ($variants as $id => $variant) { // Go through all the variants
                    $unset_flag = 0;
                    foreach ($variant as $key => $value) { // Now search all values against the supplied search term
//						error_log("key -> $key | value -> $value");
                        if (preg_match("/$sSearch/i", $value)) { // Case insensitive regex to match the search term to the current value
                            $unset_flag = 1;
//							error_log("match $id -> $key --> $sSearch -> $value");
                        }
                    }
                    if (!$unset_flag) { // Variant didn't match the search term so remove it from the array so that it doesn't get displayed
                        unset($variants[$id]);
                    }
                }
            }
        }
        $iTotalDisplayRecords = count($variants); // Get the number of records after filtering. If there's nothing filtered then this should equal $iTotalRecords - http://datatables.net/forums/discussion/comment/2661
        // Paging
        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
//			error_log("paging -> limit in controller $iDisplayLength $iDisplayStart");
            $variant_count = 0;
            foreach ($variants as $id => $variant) {
                if ($iDisplayLength != 0) {
                    
                }
                $variant_count++;
                if ($variant_count <= $iDisplayStart) {
                    unset($variants[$id]);
                } else {
                    if ($variant_count > ($iDisplayStart + $iDisplayLength )) {
                        unset($variants[$id]);
                    }
                }
            }
        }

        // Get the dynamic display fields that can be changed by user in settings interface
        $this->load->model('settings_model');
        $display_fields = $this->settings_model->getDisplayFieldsForSharingPolicy($sharing_policy);
//		$columns = array();
//		// Set the header columns of the table
//		foreach ( $display_fields as $display_field ) {
//			if ( $display_field['name'] == "cafevariome_id" ) {
//				$columns[] = array( 
//								'sType' => 'cv',
//								'sTitle' => $display_field['visible_name'],
//								'bVisible' => 'true',
//								'bSearchable' => 'true',
//								'bSortable' => 'true'
//							);
//			}
//			else {
//				$columns[] = array( 
//								'sType' => 'string',
//								'sTitle' => $display_field['visible_name'],
//								'bVisible' => 'true',
//								'bSearchable' => 'true',
//								'bSortable' => 'true'
//							);
//			}
//		}
//		$columns = json_decode('{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Cafe Variome ID", "sType": "cv" },
//			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Gene", "sType": "string" },
//			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Reference", "sType": "string" },
//			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "HGVS", "sType": "string" },
//			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Phenotype", "sType": "string" },
//			{ "bVisible": true, "bSearchable": true, "bSortable": true, "sTitle": "Source", "sType": "string" }');
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotalRecords,
            'iTotalDisplayRecords' => $iTotalDisplayRecords,
            'aaData' => array(),
//			'columns' => $columns
        );

        // Loop through the variants that are left (if any filtering/paging has been done) and create the json for outputting to the datatable
        foreach ($variants as $variant) {
            $row = array();
            foreach ($display_fields as $display_field) {
//				error_log("display_field -> " . $display_field['name']);
                if (array_key_exists($display_field['name'], $variant)) {
                    if ($display_field['name'] == "cafevariome_id") {
//						echo "<td>" . $this->config->item('cvid_prefix') . $variant[$display_field['name']] . "</td>";
                        $cvid_link = base_url("/discover/variant/" . $variant[$display_field['name']]);
                        $row[] = "<a class='basic' href='$cvid_link' href='$cvid_link'>" . $this->config->item('cvid_prefix') . $variant[$display_field['name']] . "</a>";
                    } elseif ($display_field['name'] == "source_url") {
                        if ($variant[$display_field['name']]) {
                            $row[] = "<a href='" . $variant[$display_field['name']] . "' target='_blank' title='" . $variant[$display_field['name']] . "' ><i class='icon-share' ></i></a>";
//							$row[] = "<a href='" . $variant[$display_field['name']] . "' target='_blank'>" . $variant[$display_field['name']] . "</a>";
                        } else {
                            $row[] = "-";
                        }
                    } else {
                        $row[] = $variant[$display_field['name']];
                    }
                } else {
                    $row[] = "-";
                }
            }
//			error_log(print_r($row, 1));
            $output['aaData'][] = $row;

//			error_log("after -> " . $variant['cafevariome_id']);
//			$row[] = $this->config->item('cvid_prefix') . $variant['cafevariome_id'];
//			if (isset($variant['gene'])) {
//				$row[] = $variant['gene'];
//			}
//			else {
//				$row[] = "-";
//			}
//			
//			if (isset($variant['ref'])) {
//				$row[] = $variant['ref'];
//			}
//			else {
//				$row[] = "-";
//			}
//			
//			if (isset($variant['hgvs'])) {
//				$row[] = $variant['hgvs'];
//			}
//			else {
//				$row[] = "-";
//			}
//			
//			if ( (isset($variant['phenotype']) && ($variant['phenotype'] !== "") )) {
//				$row[] = $variant['phenotype'];
//			}
//			else {
//				$row[] = "-";
//			}
//			
//			if (isset($variant['source'])) {
//				$row[] = $variant['source'];
//			}
//			$output['aaData'][] = $row;
        }

//		error_log(print_r(json_encode($output), 1));
//		$this->output->set_header('Content-Type: application/json; charset=utf-8');
//		echo json_encode($output);

        die(json_encode($output)); // json error when echoing like on previous line if there were above a certain number of variants - this works but not sure why - php error 
//		ERROR WAS: Cannot modify header information - headers already sent by (output started at /Library/WebServer/Documents/cafevariome/application/controllers/discover.php:893
    }

    function epad_example_record_link() {
        $this->_render('pages/epad_example_record_link');
    }

    public function get_display_fields_for_datatable_head() {
        // Get the dynamic display fields that can be changed by user in settings interface
        $sharing_policy = $this->input->post('sharing_policy');
        $this->load->model('settings_model');
        $display_fields = $this->settings_model->getDisplayFieldsForSharingPolicy($sharing_policy);
//		error_log("df -> " . print_r($display_fields, 1));
        $columns = array();
        // Set the header columns of the table
        foreach ($display_fields as $display_field) {
            if ($display_field['name'] == "cafevariome_id") {
                $columns[] = array(
                    'sType' => 'cv',
                    'sTitle' => $display_field['visible_name'],
                    'bVisible' => 'true',
                    'bSearchable' => 'true',
                    'bSortable' => 'true'
                );
            } else {
                $columns[] = array(
                    'sType' => 'string',
                    'sTitle' => $display_field['visible_name'],
                    'bVisible' => 'true',
                    'bSearchable' => 'true',
                    'bSortable' => 'true'
                );
            }
        }
        $output = array(
            'aoColumns' => $columns
        );

        echo json_encode($output);
    }

    // Routed using routes.php config to display the long description for any source (primarily used for the DAS server description link)
    public function source($source) {
        $source_info = $this->sources_model->getSource($source);
//		print_r($source_info);
        $this->data['source'] = $source_info;
        $this->_render('pages/source_display');
    }

    public function sources($format = NULL) {
        $sources = $this->sources_model->getSources();
//		print_r($source_info);
        $this->data['sources'] = $sources;
        if ($format) {
            if (strtolower($format) == "html") {
                $this->data['sources'] = $sources;
                $this->_render('pages/sourceshtml');
            } elseif (strtolower($format) == "json") {
                $this->output->set_content_type('application/json')->set_output(json_encode($sources));
            } elseif (strtolower($format) == "tab") {
                $data = array();
                $data['sources'] = $sources;
                $this->output->set_header("Content-Type: text/plain");
                $this->load->view('pages/sourcestab', $data);
            } else {
                show_error("Output format not recognised");
            }
        } else {
            $this->data['sources'] = $sources;
            $this->_render('pages/sourceshtml');
//			show_error("Specify an output format for source list (e.g. html, json, tab)");
        }
    }

    public function search_fields($format = "json") {
        $this->load->model('settings_model');
        $current_search_fields = $this->settings_model->getSearchFields();
        $search_fields = array();
        // Create the search fields output array in format to display to user
        if (empty($current_search_fields)) {
            $this->load->model('general_model');
            $table_structure = $this->general_model->describeTable("variants");
            foreach ($table_structure as $fields) {
                $search_fields[] = $fields['name'];
            }
//			error_log("---> " . print_r($table_structure, 1));
//			$search_fields = array('all');
        } else {
            foreach ($current_search_fields as $search_field) {
                $search_fields[] = $search_field['field_name'];
            }
        }

        // Display the search fields in the specified format
        if (strtolower($format) == "json") {
//			echo "JSON";
            $this->output->set_content_type('application/json')->set_output(json_encode($search_fields));
        }
//		else if ( strtolower($format) == "html" ) {
//			$this->_render('pages/sources_table');
//		}
//		else if ( strtolower($format) == "tab" ) {
//			$this->output->set_header("Content-Type: text/plain");
//			$this->load->view('pages/sources_table_tab', $this->data);
//		}
        else {
            show_error("Unrecognised format");
        }
    }

    public function search_result_fields($format = "json") {
        $this->load->model('settings_model');
        $current_search_fields = $this->settings_model->getSearchFields();
        $search_fields = array();
        // Create the search fields output array in format to display to user
        if (empty($current_search_fields)) {
            $search_fields = array('all');
        } else {
            foreach ($current_search_fields as $search_field) {
                $search_fields[] = $search_field['field_name'];
            }
        }

        // Display the search fields in the specified format
        if (strtolower($format) == "json") {
//			echo "JSON";
            $this->output->set_content_type('application/json')->set_output(json_encode($search_fields));
        }
//		else if ( strtolower($format) == "html" ) {
//			$this->_render('pages/sources_table');
//		}
//		else if ( strtolower($format) == "tab" ) {
//			$this->output->set_header("Content-Type: text/plain");
//			$this->load->view('pages/sources_table_tab', $this->data);
//		}
        else {
            show_error("Unrecognised format");
        }
    }

    public function individual_record_fields($format = "json") {
        $this->load->model('settings_model');
        $current_individual_record_fields = $this->settings_model->getIndividualRecordDisplayFields();
        $individual_record_fields = array();
        // Create the search fields output array in format to display to user
//		if ( empty($current_search_fields) ) {
//			$search_fields = array('all');
//		}
        if (!empty($current_individual_record_fields)) {
            foreach ($current_individual_record_fields as $individual_record_field) {
//				print_r($individual_record_field);
                $individual_record_fields[$individual_record_field['name']] = $individual_record_field['visible_name'];
            }
        }

        // Display the individual record fields in the specified format
        if (strtolower($format) == "json") {
//			echo "JSON";
            $this->output->set_content_type('application/json')->set_output(json_encode($individual_record_fields));
        } else {
            show_error("Unrecognised format");
        }
    }

    public function search_results_fields($sharing_policy = "openAccess", $format = "json") {
        $this->load->model('settings_model');
        $current_search_results_fields = $this->settings_model->getDisplayFieldsForSharingPolicy($sharing_policy);
        $search_results_fields = array();
        if (!empty($current_search_results_fields)) {
            foreach ($current_search_results_fields as $search_results_field) {
//				print_r($individual_record_field);
                $search_results_fields[$search_results_field['name']] = $search_results_field['visible_name'];
            }
        }

        // Display the search results fields in the specified format
        if (strtolower($format) == "json") {
//			echo "JSON";
            $this->output->set_content_type('application/json')->set_output(json_encode($search_results_fields));
        } else {
            show_error("Unrecognised format");
        }
    }

    function stats() {
        $this->load->model('sources_model');

        $this->data['variant_counts'] = $this->sources_model->countOnlineSourceEntries();
        $sources = $this->sources_model->getSourcesFull();
        $this->data['sources'] = $sources;
        $this->data['gene_counts'] = $this->sources_model->countFeature('gene');
        $this->data['ref_counts'] = $this->sources_model->countFeature('ref');

        $this->_render('pages/discover_stats');
    }

    public function get_genes_list() {
        $this->load->model('sources_model');
        $data = array();
        $data['type'] = "genes";
        $data['counts'] = $this->sources_model->countFeature('gene');
        $gene_data = $this->load->view('pages/discover_stats_modal', $data, TRUE);
        echo "<p>$gene_data<p>";
    }

    public function get_reference_list() {
        $this->load->model('sources_model');
        $data = array();
        $data['type'] = "reference";
        $data['counts'] = $this->sources_model->countFeature('ref');
        $reference_data = $this->load->view('pages/discover_stats_modal', $data, TRUE);
        echo "<p>$reference_data<p>";
    }

    private function setSources($sources) {
        $this->sources = $sources;
    }

    private function getSources() {
        return $this->sources;
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
        } else { // start/end is delimited by - so split on this
            $positions = explode("-", $pieces[1]);
            $start = $positions[0];
            $end = $positions[1];
        }
        $locations['chr'] = $chr;
        $locations['start'] = $start;
        $locations['end'] = $end;
        return $locations;
    }

    private function _splitRegionDAS($region) {
        $pieces = explode(":", $region); // Split region into chr and start/ends
        $chr = $pieces[0];
        if (preg_match('/\.\./', $pieces[1])) { // start/end is delimited by .. so split on this
            $positions = explode("..", $pieces[1]);
//			print_r($positions);
            $start = $positions[0];
            $end = $positions[1];
        } else { // start/end is delimited by - so split on this
            $positions = explode("-", $pieces[1]);
            $start = $positions[0];
            $end = $positions[1];
        }
        $chr = preg_replace('/chr/', '', $chr);
        $das_location = $chr . ":" . $start . "," . $end;
        return $das_location;
    }

    private function _splitRefHGVS($term) {
        $pieces = explode(":", $term); // Split region into chr and start/ends
        $ref_hgvs = array();
        $ref_hgvs['ref'] = $pieces[0];
        $ref_hgvs['hgvs'] = $pieces[1];
        return $ref_hgvs;
    }

    // Generate VarioML using the Xml_writer CodeIgniter library taken from https://github.com/EllisLab/CodeIgniter/wiki/XML-generator-library
    private function _generateVarioml($variants, $source_data) {
        if (empty($variants)) {
            show_error("There are no variants available for this search term");
        }
        // Load XML writer library
        $this->load->library('Xml_writer');
        // Initiate class
        $xml = new Xml_writer;
        $xml->setRootName('cafe_variome');
        $xml->setRootAttributes(array('xmlns' => 'http://varioml.org/xml/1.0'));
        $xml->initiate();

        // Start source branch
        $xml->startBranch('source', array('id' => $source_data['name']));
        $xml->addNode('name', $source_data['name']);
        $xml->addNode('url', $source_data['uri']);
//		print_r($source_data);
        // Set submitter contact branch
        if (isDefined($source_data['owner_name'])) {
            $xml->startBranch('contact', array('role' => 'submitter')); // start branch contact
            $xml->addNode('name', $source_data['owner_name']);
            if (isDefined($source_data['owner_address'])) {
                $xml->addNode('address', $source_data['owner_address']);
            }
            if (isDefined($source_data['email'])) {
                $xml->addNode('email', $source_data['email'], array(), true);
            }
            if (isDefined($source_data['owner_orcid'])) {
                $xml->addNodeSimple('db_xref', array('accession' => $source_data['owner_orcid'], 'source' => 'orcid'), true);
            }
            $xml->endBranch();
        }

        // Set curator contact branch
        if (isDefined($source_data['curator_name'])) {
            $xml->startBranch('contact', array('role' => 'curator')); // start branch contact
            $xml->addNode('name', $source_data['curator_name']);
            if (isDefined($source_data['curator_address'])) {
                $xml->addNode('address', $source_data['curator_address']);
            }
            if (isDefined($source_data['curator_email'])) {
                $xml->addNode('email', $source_data['curator_email'], array(), true);
            }
            if (isDefined($source_data['curator_orcid'])) {
                $xml->addNodeSimple('db_xref', array('accession' => $source_data['curator_orcid'], 'source' => 'orcid'), true);
            }
            $xml->endBranch();
        }

        // Set producer contact branch
        if (isDefined($source_data['producer_name'])) {
            $xml->startBranch('contact', array('role' => 'producer')); // start branch contact
            $xml->addNode('name', $source_data['producer_name']);
            if (isDefined($source_data['producer_address'])) {
                $xml->addNode('address', $source_data['producer_address']);
            }
            if (isDefined($source_data['producer_email'])) {
                $xml->addNode('email', $source_data['producer_email'], array(), true);
            }
            if (isDefined($source_data['producer_orcid'])) {
                $xml->addNodeSimple('db_xref', array('accession' => $source_data['producer_orcid'], 'source' => 'orcid'), true);
            }
            $xml->endBranch();
        }

        // End source branch
        $xml->endBranch();

        foreach ($variants as $variant) {
            $ref = isset($variant['ref']) ? $variant['ref'] : '';
            $hgvs = isset($variant['hgvs']) ? $variant['hgvs'] : '';
            $gene = isset($variant['gene']) ? $variant['gene'] : '';
            $sharing_policy = isset($variant['sharing_policy']) ? $variant['sharing_policy'] : '';
            // Start branch variant
            if (isset($variant['cafevariome_id'])) {
                $xml->startBranch('variant', array('id' => $this->config->item('cvid_prefix') . $variant['cafevariome_id'], 'type' => 'DNA')); // start branch
            } else {
                $xml->startBranch('variant', array('type' => 'DNA')); // start branch
            }
            $xml->addNodeSimple('gene', array('accession' => $gene, 'source' => 'hgnc.symbol'), true);
            $xml->addNodeSimple('ref_seq', array('accession' => $ref, 'source' => 'refseq'), true);
            $xml->addNode('name', $this->xml_entities($hgvs), array('scheme' => 'HGVS'), true);
            if (isset($variant['gender']) || isset($variant['individual_id'])) {
                if (isset($variant['individual_id'])) {
                    $xml->startBranch('panel', array('id' => $variant['individual_id'])); // start branch panel
                } else {
                    $xml->startBranch('panel'); // start branch panel
                }
                if (isset($variant['gender'])) {
                    $xml->startBranch('individual'); // start branch gender
//					$xml->addNode('gender', '', array('code' => $this->_getGenderCodeFromGender($variant['gender'])), true);
                    $xml->addNodeSimple('gender', array('code' => $this->_getGenderCodeFromGender($variant['gender'])), true);
                    $xml->endBranch(); // end branch gender
                }
                if (isset($variant['phenotype'])) {
                    $xml->addNodeSimple('phenotype', array('term' => $variant['phenotype']), true);
                }
                if (isset($variant['ethnicity'])) {
                    if (preg_match('/\,/', $variant['ethnicity'])) {
//						print "match -> " . $variant['variant_id'] . "<br />";
                        $ethnic_array = explode(',', $variant['ethnicity']);
                        foreach ($ethnic_array as $ethnic) {
                            $xml->addNodeSimple('population', array('term' => $ethnic, 'type' => 'ethnic'), true);
                        }
                    } else {
                        $xml->addNodeSimple('population', array('term' => $variant['ethnicity'], 'type' => 'ethnic'), true);
                    }
                }
                $xml->endBranch(); // end branch panel
            }

            if (isset($variant['pathogenicity']) && !empty($variant['pathogenicity'])) {
                if (isset($variant['pathogenicity_list_type'])) {
                    $pathogenicity_list_type = $variant['pathogenicity_list_type'];
                } else {
                    $pathogenicity_list_type = "varioml";
                }
                $xml->addNodeSimple('pathogenicity', array('term' => $variant['pathogenicity'], 'source' => $pathogenicity_list_type), true);
            }

            if (!isset($variant['individual_id'])) {
                if (isset($variant['phenotype'])) {
                    $xml->addNodeSimple('phenotype', array('term' => $variant['phenotype']), true);
                }
            }

            if (isset($variant['protein_ref']) && !empty($variant['protein_ref'])) {
                $xml->startBranch('seq_changes'); // start seq_changes branch
                $xml->startBranch('variant', array('type' => 'AA'));
                $xml->addNodeSimple('ref_seq', array('accession' => $variant['protein_ref'], 'source' => 'refseq'), true);
                $xml->addNode('name', $this->xml_entities($variant['protein_hgvs']), array('scheme' => 'HGVS'), true);
                $xml->endBranch(); // end branch variant
                $xml->endBranch(); // end branch seq_changes
            }

            if (isset($variant['genomic_ref']) && !empty($variant['genomic_ref'])) {
                $xml->startBranch('aliases'); // start seq_changes branch
                $xml->startBranch('variant');
                $xml->addNodeSimple('ref_seq', array('accession' => $variant['genomic_ref'], 'source' => 'refseq'), true);
                $xml->addNode('name', $this->xml_entities($variant['genomic_hgvs']), array('scheme' => 'HGVS'), true);
                $xml->endBranch(); // end branch variant
                $xml->endBranch(); // end branch aliases
            }

            if (isset($variant['location_ref']) && !empty($variant['location_ref'])) {
                $xml->startBranch('location'); // start location
                if (isDefined($variant['build'])) {
                    $xml->addNodeSimple('ref_seq', array('accession' => $variant['build'], 'source' => 'ucsc'), true);
                }
                if (isDefined($variant['location_ref'])) {
                    $xml->addNode('chr', $variant['location_ref']);
                }
                if (isDefined($variant['start'])) {
                    $xml->addNode('start', $variant['start']);
                }
                if (isDefined($variant['end'])) {
                    $xml->addNode('end', $variant['end']);
                }

                $xml->endBranch(); // end location branch
            }

            $xml->addNodeSimple('sharing_policy', array('type' => $sharing_policy), true);

            if (isset($variant['date_time']) && !empty($variant['date_time'])) {
                $xml->addNode('creation_date', $variant['date_time']);
            }

            if (isset($variant['pmid'])) {
                $xml->addNodeSimple('db_xref', array('accession' => $variant['pmid'], 'source' => 'pubmed'), true);
            }

            if (isset($variant['comment']) && !empty($variant['comment'])) {
                $xml->startBranch('comment'); // start branch comment
                $xml->addNode('text', $variant['comment']);
                $xml->endBranch();
            }

            // End branch variant
            $xml->endBranch();
        }
        // Print the XML to screen
        $xml->getXml(true);
    }

    function runAPISearch($source_uri, $source, $term) {
        $term = urlencode($term);
        error_log("term -> " . $term);
        $counts = @file_get_contents($source_uri . "/discover/variantcount/$term/$node_source/json");
        error_log($source_uri . "/discover/variantcount/$term/$source/json");
        error_log("decode -> " . json_decode($counts));
        $counts = json_decode($counts, TRUE);
        $hacked_counts = array();
        if (!empty($counts)) {
            foreach ($counts as $key => $value) {
                foreach ($value as $k => $v) {
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

        if ($username) {
            $context = stream_context_create(array(
                'http' => array(
                    'header' => "Authorization: Basic " . base64_encode("$username:$password")
                )
            ));
            $variants = @file_get_contents($source_uri . "/discover/variants/$term/$node_source/$sharing_policy/$format", false, $context);
        } else {
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

    function queryall() {
        if ($this->config->item('cafevariome_central')) {
            // GET API key? MOVE THIS AND FUNCTION BELOW TO API controller
            // Get all instances that are switched on
            // First need to add function below for adding node to central (include an API key?)
            // 
        }
    }

    function add_node_to_central() {
        // Put in API????
        // If GET for federated then add an entry to the table
    }

    function xml_entities($string) {
        return strtr(
                $string, array(
            "<" => "&lt;",
            ">" => "&gt;",
            '"' => "&quot;",
            "'" => "&apos;",
            "&" => "&amp;",
                )
        );
    }

    private function writeExcel($term, $source, $variants, $display_fields) {
//		error_log("variants ---> " . print_r($variants, 1));
        $this->load->library('phpexcel/PHPExcel');
        $sheet = $this->phpexcel->getActiveSheet();

        $styleArray = array('font' => array('bold' => true));

        $total_fields = count($display_fields);

        // First of all print the header row with currently set display fields
        $letter = "A";
        foreach ($display_fields as $display_field) {
            $letter_number = $letter . "1"; // Write header to row 1
            $sheet->getColumnDimension($letter)->setAutoSize(true);
            $sheet->setCellValue($letter_number, $display_field['visible_name']);
            $sheet->getStyle($letter_number)->applyFromArray($styleArray);
            $letter++;
        }

        // Next print the actual variant data
        $row = 2; // Start outputting data from row 2 (row 1 is the header)
        foreach ($variants as $variant) {
//			error_log("v -> " . print_r($variant, 1));
//			error_log("ROW -> $row");
            $letter = "A";
            foreach ($display_fields as $display_field) {
//				error_log("starting -> " . print_r($display_field, 1));
//				error_log("starting ds -> " . $display_field['name']);
                $letter_number = $letter . $row;
//				error_log("row is $row -> $letter $letter_number");
                if (isset($variant[$display_field['name']])) {
                    if ($display_field['name'] == "cafevariome_id") {
                        $sheet->SetCellValue($letter_number, $this->config->item('cvid_prefix') . $variant['cafevariome_id']);
                    } else {
                        $sheet->SetCellValue($letter_number, $variant[$display_field['name']]);
                    }
                } else {
                    $sheet->SetCellValue($letter_number, "-");
                }
                $letter++;
            }
            $row++; // Increment the row for the next variant
        }

        $writer = new PHPExcel_Writer_Excel5($this->phpexcel);
        $site_title_filename = strtolower($this->config->item('site_title'));
        $site_title_filename = str_replace(' ', '_', $site_title_filename);
        $excel_file_name = $site_title_filename . "_" . $term . "_" . $source . ".xls";
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $excel_file_name . '"');
        $writer->save('php://output');
    }

    private function writeExcelFederated($term, $source, $variants, $display_fields) {
//		error_log("variants ---> " . print_r($variants, 1));
        $this->load->library('phpexcel/PHPExcel');
        $sheet = $this->phpexcel->getActiveSheet();

        $styleArray = array('font' => array('bold' => true));

        $total_fields = count($display_fields);

        // First of all print the header row with currently set display fields
        $letter = "A";
        foreach ($display_fields as $display_field) {
            $letter_number = $letter . "1"; // Write header to row 1
            $sheet->getColumnDimension($letter)->setAutoSize(true);
            $sheet->setCellValue($letter_number, $display_field['visible_name']);
            $sheet->getStyle($letter_number)->applyFromArray($styleArray);
            $letter++;
        }

        // Next print the actual variant data
        $row = 2; // Start outputting data from row 2 (row 1 is the header)
        foreach ($variants as $variant) {
//			error_log("v -> " . print_r($variant, 1));
//			error_log("ROW -> $row");
            $letter = "A";
            foreach ($display_fields as $display_field) {
//				error_log("starting -> " . print_r($display_field, 1));
//				error_log("starting ds -> " . $display_field['name']);
                $letter_number = $letter . $row;
//				error_log("row is $row -> $letter $letter_number");
                if (isset($variant[$display_field['name']])) {
                    if ($display_field['name'] == "cafevariome_id") {
                        $sheet->SetCellValue($letter_number, $variant['cafevariome_id']);
                    } else {
                        $sheet->SetCellValue($letter_number, $variant[$display_field['name']]);
                    }
                } else {
                    $sheet->SetCellValue($letter_number, "-");
                }
                $letter++;
            }
            $row++; // Increment the row for the next variant
        }

        $writer = new PHPExcel_Writer_Excel5($this->phpexcel);
        $site_title_filename = strtolower($this->config->item('site_title'));
        $site_title_filename = str_replace(' ', '_', $site_title_filename);
        $excel_file_name = $site_title_filename . "_" . $term . "_" . $source . ".xls";
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $excel_file_name . '"');
        $writer->save('php://output');
    }

//	private function writeExcel($term, $source, $variants) { // Old hard coded excel generator
//		
//		$this->load->library('phpexcel/PHPExcel');
//		$sheet = $this->phpexcel->getActiveSheet();
//		
//		$styleArray = array( 'font' => array( 'bold' => true ) );
//		
//		$sheet->getColumnDimension('A')->setAutoSize(true);
//		$sheet->setCellValue('A1','cafevariome_id');
//		$sheet->getStyle('A1')->applyFromArray($styleArray);
//
//		$sheet->getColumnDimension('B')->setAutoSize(true);
//		$sheet->setCellValue('B1','gene');
//		$sheet->getStyle('B1')->applyFromArray($styleArray);
//		
//		$sheet->getColumnDimension('C')->setAutoSize(true);
//		$sheet->setCellValue('C1','ref');
//		$sheet->getStyle('C1')->applyFromArray($styleArray);
//
//		$sheet->getColumnDimension('D')->setAutoSize(true);
//		$sheet->setCellValue('D1','hgvs');
//		$sheet->getStyle('D1')->applyFromArray($styleArray);
//		
//		$sheet->getColumnDimension('E')->setAutoSize(true);
//		$sheet->setCellValue('E1','phenotype');
//		$sheet->getStyle('E1')->applyFromArray($styleArray);
//		
//		$sheet->getColumnDimension('F')->setAutoSize(true);
//		$sheet->setCellValue('F1','location_ref');
//		$sheet->getStyle('F1')->applyFromArray($styleArray);
//		
//		$sheet->getColumnDimension('G')->setAutoSize(true);
//		$sheet->setCellValue('G1','start');
//		$sheet->getStyle('G1')->applyFromArray($styleArray);
//		
//		$sheet->getColumnDimension('H')->setAutoSize(true);
//		$sheet->setCellValue('H1','end');
//		$sheet->getStyle('H1')->applyFromArray($styleArray);
//		
//		$sheet->getColumnDimension('I')->setAutoSize(true);
//		$sheet->setCellValue('I1','build');
//		$sheet->getStyle('I1')->applyFromArray($styleArray);
//
//		$sheet->getColumnDimension('J')->setAutoSize(true);
//		$sheet->setCellValue('J1','source');
//		$sheet->getStyle('J1')->applyFromArray($styleArray);
//
//		$sheet->getColumnDimension('K')->setAutoSize(true);
//		$sheet->setCellValue('K1','source_url');
//		$sheet->getStyle('K1')->applyFromArray($styleArray);
//
//		$sheet->getColumnDimension('L')->setAutoSize(true);
//		$sheet->setCellValue('L1','date_time');
//		$sheet->getStyle('L1')->applyFromArray($styleArray);
//
//		$sheet->getColumnDimension('M')->setAutoSize(true);
//		$sheet->setCellValue('M1','comment');
//		$sheet->getStyle('M1')->applyFromArray($styleArray);
//
//		$sheet->getColumnDimension('N')->setAutoSize(true);
//		$sheet->setCellValue('N1','sharing_policy');
//		$sheet->getStyle('N1')->applyFromArray($styleArray);
//
//		ksort($variants);
//		$row_count = 1;
//		foreach ($variants as $variant) {
//			$row_count++;
//			$ref = isset($variant['ref']) ? $variant['ref'] : '';
//			$hgvs = isset($variant['hgvs']) ? $variant['hgvs'] : '';
//			$gene = isset($variant['gene']) ? $variant['gene'] : '';
//			$phenotype = isset($variant['phenotype']) ? $variant['phenotype'] : '';
//			$date_time = isset($variant['date_time']) ? $variant['date_time'] : '';
//			$source_url = isset($variant['source_url']) ? $variant['source_url'] : '';
//			$location_ref = isset($variant['location_ref']) ? $variant['location_ref'] : '';
//			$start = isset($variant['start']) ? $variant['start'] : '';
//			$end = isset($variant['end']) ? $variant['end'] : '';
//			$build = isset($variant['build']) ? $variant['build'] : '';
//			$sharing_policy = isset($variant['sharing_policy']) ? $variant['sharing_policy'] : '';
//			$build = isset($variant['build']) ? $variant['build'] : '';
//			$comment = isset($variant['comment']) ? $variant['comment'] : '';
//			$sheet->SetCellValue('A' . $row_count, $this->config->item('cvid_prefix') . $variant['cafevariome_id']);
//			$sheet->SetCellValue('B' . $row_count, $gene);
//			$sheet->SetCellValue('C' . $row_count, $ref);
//			$sheet->SetCellValue('D' . $row_count, $hgvs);
//			$sheet->SetCellValue('E' . $row_count, $phenotype);
//			$sheet->SetCellValue('F' . $row_count, $location_ref);
//			$sheet->SetCellValue('G' . $row_count, $start);
//			$sheet->SetCellValue('H' . $row_count, $end);
//			$sheet->SetCellValue('I' . $row_count, $build);
//			$sheet->SetCellValue('J' . $row_count, $variant['source']);
//			$sheet->SetCellValue('K' . $row_count, $source_url);
//			$sheet->SetCellValue('L' . $row_count, $date_time);
//			$sheet->SetCellValue('M' . $row_count, $comment);
//			$sheet->SetCellValue('N' . $row_count, $sharing_policy);
//		}
//
//		$writer = new PHPExcel_Writer_Excel5($this->phpexcel);
//		$excel_file_name = "cv_" . $term . "_" . $source . ".xls";
//		header('Content-type: application/vnd.ms-excel');
//		header('Content-Disposition: attachment;filename="' . $excel_file_name . '"');
//		$writer->save('php://output');
//	}

    private function _getGenderCodeFromGender($gender) {
        if (strtolower($gender) == "none available") {
            $gender_code = "0";
        } else if (strtolower($gender) == "male") {
            $gender_code = "1";
        } else if (strtolower($gender) == "female") {
            $gender_code = "2";
        } else if (strtolower($gender) == "not applicable") {
            $gender_code = "9";
        } else {
            $gender_code = "0";
        }
        return $gender_code;
    }

    private function _getGenderFromGenderCode($gender_code) {
        if ($gender_code === 0) {
            $gender = "None available";
        } else if ($gender_code === 1) {
            $gender = "Male";
        } else if ($gender_code === 2) {
            $gender = "Female";
        } else if ($gender_code === 9) {
            $gender = "Not applicable";
        } else {
            $gender = "None available";
        }
        return $gender;
    }

    function _isBoolean($value) {
        if ($value && strtolower($value) !== "false") {
            return true;
        } else {
            return false;
        }
    }

    function http_response_code($code = NULL) { // From http://www.php.net/manual/en/function.http-response-code.php
        if ($code !== NULL) {
            switch ($code) {
                case 100: $text = 'Continue';
                    break;
                case 101: $text = 'Switching Protocols';
                    break;
                case 200: $text = 'OK';
                    break;
                case 201: $text = 'Created';
                    break;
                case 202: $text = 'Accepted';
                    break;
                case 203: $text = 'Non-Authoritative Information';
                    break;
                case 204: $text = 'No Content';
                    break;
                case 205: $text = 'Reset Content';
                    break;
                case 206: $text = 'Partial Content';
                    break;
                case 300: $text = 'Multiple Choices';
                    break;
                case 301: $text = 'Moved Permanently';
                    break;
                case 302: $text = 'Moved Temporarily';
                    break;
                case 303: $text = 'See Other';
                    break;
                case 304: $text = 'Not Modified';
                    break;
                case 305: $text = 'Use Proxy';
                    break;
                case 400: $text = 'Bad Request';
                    break;
                case 401: $text = 'Unauthorized';
                    break;
                case 402: $text = 'Payment Required';
                    break;
                case 403: $text = 'Forbidden';
                    break;
                case 404: $text = 'Not Found';
                    break;
                case 405: $text = 'Method Not Allowed';
                    break;
                case 406: $text = 'Not Acceptable';
                    break;
                case 407: $text = 'Proxy Authentication Required';
                    break;
                case 408: $text = 'Request Time-out';
                    break;
                case 409: $text = 'Conflict';
                    break;
                case 410: $text = 'Gone';
                    break;
                case 411: $text = 'Length Required';
                    break;
                case 412: $text = 'Precondition Failed';
                    break;
                case 413: $text = 'Request Entity Too Large';
                    break;
                case 414: $text = 'Request-URI Too Large';
                    break;
                case 415: $text = 'Unsupported Media Type';
                    break;
                case 500: $text = 'Internal Server Error';
                    break;
                case 501: $text = 'Not Implemented';
                    break;
                case 502: $text = 'Bad Gateway';
                    break;
                case 503: $text = 'Service Unavailable';
                    break;
                case 504: $text = 'Gateway Time-out';
                    break;
                case 505: $text = 'HTTP Version not supported';
                    break;
                default:
                    exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
            }

            return $text;
        }
    }

}
