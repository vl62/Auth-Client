<?php
	require_once('includes/core_class.php');
	require_once('includes/Jg_cache.php');
	// Get the api key that the user supplied
	$key = $_POST["key"];
//	error_log("key -> " . $key);

	$core = new Core();
	$ontology_list = $core->get_ontology_list($key);
 
	if ( ! empty($ontology_list)) { // The key was valid and the ontology list was return
//		error_log(print_r($ontology_list, 1));
	//	$json_array = array('is_valid' => 'yes', 'key' => $key, 'ontology_list' => json_decode($ontology_list));
                error_log(print_r($ontology_list, 1));
                $json_array = array('is_valid' => 'yes', 'key' => $key, 'ontology_list' => json_decode($ontology_list));
	}
	else { // The key didn't work, fetch the global key from Cafe Variome Central  and use that instead
		$bioportalkey = $core->getBioPortalAPIKey(); // Get the universal key from the CV Central API
//		error_log("key -> $bioportalkey");
//		$ontology_list = $core->getBioPortalOntologyList($bioportalkey);
		$ontology_list = $core->get_ontology_list($bioportalkey);
		error_log(print_r($ontology_list, 1));
		if ( ! empty($ontology_list)) { 
			$json_array = array('is_valid' => 'no', 'key' => $bioportalkey, 'ontology_list' => json_decode($ontology_list));
		}
		else {
			$json_array = array('is_valid' => 'failed', 'key' => $key, 'ontology_list' => 'failed');
		}
	}
	// Echo json array back to jquery function
	echo json_encode($json_array);
?>