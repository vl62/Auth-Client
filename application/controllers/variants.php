<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Variants extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('sources_model');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	}

	//delete the variants
	function delete($source = NULL) {
		// Check whether the user is either an admin or a curator that has the required permissions to do this action
		do {
			if (!$this->ion_auth->logged_in() ) {
				redirect('auth', 'refresh');
			}
			elseif ($this->ion_auth->is_admin()) {
				break;
			}
			if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
				$user_id = $this->ion_auth->user()->row()->id;
				$source_id = $this->sources_model->getSourceIDFromName($source);
				$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
				if ( ! $can_curate_source ) {
					show_error("Sorry, you are not listed as a curator for that particular source.");
				}
			}
		} while (0);

//		if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
//			$user_id = $this->ion_auth->user()->row()->id;
//			$source_id = $this->sources_model->getSourceIDFromName($source);
//			$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
//			if ( ! $can_curate_source ) {
//				show_error("Sorry, you are not listed as a curator for that particular source.");
//			}
//		}
//		elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth', 'refresh');
//		}
		
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('source', 'Source Name', 'required|alpha_dash');

		if ($this->form_validation->run() == FALSE) {
			// insert csrf check
			$this->data['source'] = $source;
			$this->_render('admin/delete_variants');
		}
		else {
			// do we really want to delete?
			if ($this->input->post('confirm') == 'yes') {
				// do we have a valid request?
				if ($source != $this->input->post('source')) {
					show_error('This form post did not pass our security checks.');
				}

				// do we have the right userlevel?
				do {
					if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
//                                            $is_deleted = $this->sources_model->delete_variants_and_phenotypes($source);
                                                $is_deleted = $this->sources_model->deleteVariants($source);
						
						// ElasticSearch delete by query (if ElasticSearch is enabled and running)
						// http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/docs-delete-by-query.html
						if ( $this->config->item('use_elasticsearch') ) { 
							$this->load->library('elasticsearch');
							$check_if_running = $this->elasticsearch->check_if_running();
							if ( array_key_exists( 'ok', $check_if_running) ) {
								// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
								$es_index = $this->config->item('site_title');
								$es_index = preg_replace('/\s+/', '', $es_index);
								$es_index = strtolower($es_index);
								$this->elasticsearch->set_index($es_index);
								$this->elasticsearch->set_type("variants");
//								error_log("id -> $id");
								$delete_query = array();
								$delete_query['term'] = array('source' => $source);
								$delete_query = json_encode($delete_query);
//								error_log("delete -> " . print_r($delete_query, 1));
								$delete_result = $this->elasticsearch->delete_by_query($delete_query);
//								error_log("RESULT -> " . print_r($delete_result, 1));
							}
						}
						
						break;
					}
					if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
						$user_id = $this->ion_auth->user()->row()->id;
						$source_id = $this->sources_model->getSourceIDFromName($source);
						$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
						if ( ! $can_curate_source ) {
							show_error("Sorry, you are not listed as a curator for that particular source.");
						}
//                                                $is_deleted = $this->sources_model->delete_variants_and_phenotypes($source);
						$is_deleted = $this->sources_model->deleteVariants($source);
						
						// ElasticSearch delete by query (if ElasticSearch is enabled and running)
						// http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/docs-delete-by-query.html
						if ( $this->config->item('use_elasticsearch') ) { 
							$this->load->library('elasticsearch');
							$check_if_running = $this->elasticsearch->check_if_running();
							if ( array_key_exists( 'ok', $check_if_running) ) {
								// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
								$es_index = $this->config->item('site_title');
								$es_index = preg_replace('/\s+/', '', $es_index);
								$es_index = strtolower($es_index);
								$this->elasticsearch->set_index($es_index);
								$this->elasticsearch->set_type("variants");
//								error_log("id -> $id");
								$delete_query = array();
								$delete_query['term'] = array('source' => $source);
								$delete_query = json_encode($delete_query);
//								error_log("delete -> " . print_r($delete_query, 1));
								$delete_result = $this->elasticsearch->delete_by_query($delete_query);
//								error_log("RESULT -> " . print_r($delete_result, 1));
							}
						}

						break;
					}
				} while (0);
//				if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
//					$user_id = $this->ion_auth->user()->row()->id;
//					$source_id = $this->sources_model->getSourceIDFromName($source);
//					$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
//					if ( ! $can_curate_source ) {
//						show_error("Sorry, you are not listed as a curator for that particular source.");
//					}
//					$is_deleted = $this->sources_model->deleteVariants($source);
//				}
//				elseif ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
//					$is_deleted = $this->sources_model->deleteVariants($source);
//				}
			}
			if ($this->session->userdata('admin_or_curate') == "curate") { // If the user is performing this as a curator, redirect to correct page
				redirect('curate/variants', 'refresh');
			}
			else {
				redirect('admin/variants', 'refresh');
			}
		}
	}

	function delete_variants_multiple() {
		// Check whether the user is either an admin or a curator that has the required permissions to do this action
		do {
			if (!$this->ion_auth->logged_in()) {
				redirect('auth', 'refresh');
			}
			if ($this->ion_auth->is_admin()) {
				break;
			}
			if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
				$user_id = $this->ion_auth->user()->row()->id;
				$source_id = $this->sources_model->getSourceIDFromName($this->input->post('source_name'));
				$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
				if ( ! $can_curate_source ) {
					show_error("Sorry, you are not listed as a curator for that particular source.");
				}
			}

		} while (0);
//		if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
//			$user_id = $this->ion_auth->user()->row()->id;
//			$source_id = $this->sources_model->getSourceIDFromName($this->input->post('source_name'));
//			$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
//			if ( ! $can_curate_source ) {
//				show_error("Sorry, you are not listed as a curator for that particular source.");
//			}
//		}
//		elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth', 'refresh');
//		}
		$variants = json_decode($this->input->post('variants'));
//		print_r($variants);
		$success_flag = 1;
		foreach ( $variants as $key => $id ) {
//			error_log("id -> " . $id);
//                        $is_deleted = $this->sources_model->deleteVariantPenotype($id);
			$is_deleted = $this->sources_model->deleteVariant($id);
			
			// ElasticSearch update (if ElasticSearch is enabled and running)
			if ( $this->config->item('use_elasticsearch') ) { 
				$this->load->library('elasticsearch');
				$check_if_running = $this->elasticsearch->check_if_running();
				if ( array_key_exists( 'ok', $check_if_running) ) {
					// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
					$es_index = $this->config->item('site_title');
					$es_index = preg_replace('/\s+/', '', $es_index);
					$es_index = strtolower($es_index);
					$this->elasticsearch->set_index($es_index);
					$this->elasticsearch->set_type("variants");
//					error_log("id -> $id");
					$delete_result = $this->elasticsearch->delete($id);
//					error_log("RESULT -> " . print_r($delete_result, 1));
				}
			}
			
			if ( ! $is_deleted ) {
				$success_flag = 0;
			}
		}
		if ( $success_flag ) {
			echo "Variants were successfully deleted";
		}
		else {
			echo "There was a problem deleting one or more variants";
		}
	}
	
	function set_sharing_policy_multiple()  {
		// Check whether the user is either an admin or a curator that has the required permissions to do this action
		do {
			if (!$this->ion_auth->logged_in()) {
				redirect('auth', 'refresh');
			}
			if ($this->ion_auth->is_admin()) {
				break;
			}
			if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
				$user_id = $this->ion_auth->user()->row()->id;
				$source_id = $this->sources_model->getSourceIDFromName($this->input->post('source_name'));
				$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
				if ( ! $can_curate_source ) {
					show_error("Sorry, you are not listed as a curator for that particular source.");
				}
			}
		} while (0);
//		if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
//			$user_id = $this->ion_auth->user()->row()->id;
//			$source_id = $this->sources_model->getSourceIDFromName($this->input->post('source_name'));
//			$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
//			if ( ! $can_curate_source ) {
//				show_error("Sorry, you are not listed as a curator for that particular source.");
//			}
//		}
//		elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth', 'refresh');
//		}
		
		
//		if ( $this->config->item('use_elasticsearch') ) { 
//			$this->load->library('elasticsearch');
//		}
		
		$sharing_policy = $this->input->post('sharing_policy');
//		error_log("sp -> " . $sharing_policy);
		$variants = json_decode($this->input->post('variants'));
		$success_flag = 1;
		error_log("variants -> " . print_r($variants, 1));
		foreach ( $variants as $key => $id ) {
			if ( $id === "selectall" ) { // Hack to ignore the select all checkbox ID
				continue;
			}
//			error_log("id -> " . $id);
			$was_updated = $this->sources_model->updateVariantSharingPolicy($id, $sharing_policy);
//			error_log("updated flag -> " . $was_updated);
			if ( ! $was_updated ) {
				$success_flag = 0;
			}
			
			// ElasticSearch update (if ElasticSearch is enabled and running)
			if ( $this->config->item('use_elasticsearch') ) { 
				$this->load->library('elasticsearch');
//				$check_if_running = $this->elasticsearch->check_if_running(); // Commented out this check for now as the check takes took long and so when going through the loop if fails and will skip some variants - need to find a fix
//				if ( array_key_exists( 'ok', $check_if_running) ) {
					// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
					$es_index = $this->config->item('site_title');
					$es_index = preg_replace('/\s+/', '', $es_index);
					$es_index = strtolower($es_index);
					$this->elasticsearch->set_index($es_index);
					$this->elasticsearch->set_type("variants");
//					error_log("id -> $id");
					$update_data['sharing_policy'] = $sharing_policy;
					$update = array();
					$update['doc'] = $update_data;
					$update = json_encode($update);
					error_log("update $update");
					$update_result = $this->elasticsearch->update($id, $update);
//					error_log("RESULT -> " . print_r($update_result, 1));
					if ( ! $update_result ) {
						$success_flag = 0;
					}
//				}
			}
			
			
			
		}
		if ( ! $success_flag ) {
			echo "There was a problem updating the sharing policy for one or more variants.";
		}
//		else {
//			echo "Sharing policy was successfully updated.";
//		}
		
		

		
	}

	function delete_atomserver_variants_multiple() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$this->load->model('atomserver_model');
		$variants = json_decode($this->input->post('variants'));
//		print_r($variants);
		$success_flag = 1;
		
		$atomserver_xml = '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:asbatch="http://atomserver.org/namespaces/1.0/batch">';
		
		foreach ( $variants as $key => $id ) {
//			error_log("id -> " . $id);
			$atomserver_xml .= '<entry xmlns:ha="http://atomserver.org/namespaces/atomserver/1.0/">';
			$atomserver_xml .= '<asbatch:operation type="delete"/>';
			$atomserver_xml .= '<link href="/atomserver/v1/cafevariome/variants/' . $id . '.xml/*" rel="edit"/>';
			$atomserver_xml .= '</entry>';

		}
		$atomserver_xml .= '</feed>';
		$is_deleted = $this->atomserver_model->deleteEntry($atomserver_xml, $this->config->item('atomserver_uri'), $this->config->item('atomserver_user'), $this->config->item('atomserver_password'));
		if ( ! $is_deleted ) {
			$success_flag = 0;
		}

		if ( $success_flag ) {
			echo "Variants were successfully deleted";
		}
		else {
			echo "There was a problem deleting one or more variants";
		}
	}

	function make_variants_live_multiple() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$this->load->model('atomserver_model');
		$variants = json_decode($this->input->post('variants'));
//		print_r($variants);
		$success_flag = 1;
		
		$atomserver_xml = '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:asbatch="http://atomserver.org/namespaces/1.0/batch">';
		
		foreach ( $variants as $key => $id ) {
//			error_log("id -> " . $id);
			$entry_data = $this->atomserver_model->getAtomServerEntry($id, $this->config->item('atomserver_uri'), $this->config->item('atomserver_user'), $this->config->item('atomserver_password'));
//			error_log(print_r($entry_data, TRUE));

			$insert_id = $this->sources_model->insertVariants($entry_data);
			if (!$insert_id) {
				$success_flag = 0;
			}
//			error_log("insert -> " . $insert_id);
			$atomserver_xml .= '<entry xmlns:ha="http://atomserver.org/namespaces/atomserver/1.0/">';
			$atomserver_xml .= '<asbatch:operation type="delete"/>';
			$atomserver_xml .= '<link href="/atomserver/v1/cafevariome/variants/' . $id . '.xml/*" rel="edit"/>';
			$atomserver_xml .= '</entry>';

		}
		$atomserver_xml .= '</feed>';
		$is_deleted = $this->atomserver_model->deleteEntry($atomserver_xml, $this->config->item('atomserver_uri'), $this->config->item('atomserver_user'), $this->config->item('atomserver_password'));
		if ( ! $is_deleted ) {
			$success_flag = 0;
		}

		if ( $success_flag ) {
			echo "Variants were successfully activated";
		}
		else {
			echo "There was a problem activating one or more variants";
		}
	}
	
	function import($source) {
		// Check whether the user is either an admin or a curator that has the required permissions to do this action
		do {
			if (!$this->ion_auth->logged_in()) {
				redirect('auth', 'refresh');
			}
			if ($this->ion_auth->is_admin()) {
				break;
			}
			if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
				$user_id = $this->ion_auth->user()->row()->id;
				$source_id = $this->sources_model->getSourceIDFromName($source);
				$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
				if ( ! $can_curate_source ) {
					show_error("Sorry, you are not listed as a curator for that particular source.");
				}
			}
		} while (0);
//		if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
//			$user_id = $this->ion_auth->user()->row()->id;
//			$source_id = $this->sources_model->getSourceIDFromName($source);
//			$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
//			if ( ! $can_curate_source ) {
//				show_error("Sorry, you are not listed as a curator for that particular source.");
//			}
//		}
//		elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth', 'refresh');
//		}

		$this->data['source'] = $source;
		$this->_render('admin/add_variants');
	}

	function add($source = NULL) {
        // Check whether the user is either an admin or a curator that has the required permissions to do this action
        do {
            if (!$this->ion_auth->logged_in()) {
                redirect('auth', 'refresh');
            }
            if ($this->ion_auth->is_admin()) {
                break;
            }
            if ($this->ion_auth->in_group("curator")) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
                $user_id = $this->ion_auth->user()->row()->id;
                $source_id = $this->sources_model->getSourceIDFromName($source);
                $can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
                if (!$can_curate_source) {
                    show_error("Sorry, you are not listed as a curator for that particular source.");
                }
            }
        } while (0);
//		if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
//			$user_id = $this->ion_auth->user()->row()->id;
//			$source_id = $this->sources_model->getSourceIDFromName($source);
//			$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
//			if ( ! $can_curate_source ) {
//				show_error("Sorry, you are not listed as a curator for that particular source.");
//			}
//		}
//		elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth', 'refresh');
//		}

        $this->data['source'] = $source;
        $this->data['title'] = "Add Variant";

//		cafevariome_id, variant_id, source, laboratory, gene, ref, hgvs, phenotype, phenotype_omim, individual_id, gender
//		ethnicity, location_ref, start, end, build, pathogenicity, pathogenicity_list_type, detection_method, comment, sharing_policy, mutalyzer_check
//		source_url, date_time, auto_id, source_id

        $this->form_validation->set_rules('gene', 'Gene Symbol', 'xss_clean'); // required
        $this->form_validation->set_rules('ref', 'Reference Sequence', 'xss_clean'); // required
        $this->form_validation->set_rules('hgvs', 'HGVS nomenclature', 'xss_clean'); // required
        $this->form_validation->set_rules('variant_id', 'Variant ID', 'required|xss_clean');
        $this->form_validation->set_rules('sharing_policy', 'Sharing Policy', 'required|xss_clean');
        $this->form_validation->set_rules('ont_list', 'Phenotype description', 'xss_clean');



        $this->form_validation->set_rules('individual_id', 'Patient ID', 'xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'xss_clean');
        $this->form_validation->set_rules('ethnicity', 'Ethnicity', 'xss_clean');
        $this->form_validation->set_rules('location_ref', 'Chromosome', 'xss_clean');
        $this->form_validation->set_rules('start', 'Start', 'xss_clean');
        $this->form_validation->set_rules('end', 'End', 'xss_clean');
        $this->form_validation->set_rules('comment', 'Comment', 'xss_clean');
        $this->form_validation->set_rules('mutalyzer', 'Mutalyzer Check', 'xss_clean'); // required
        $this->form_validation->set_message('required', 'The %s field is required.');
        if ($this->form_validation->run() == FALSE) {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            if ($source == "") {
                $this->data['source'] = $this->input->post('source');
            }
            $this->data['gene'] = array(
                'name' => 'gene',
                'id' => 'gene',
                'type' => 'gene',
                'value' => $this->form_validation->set_value('gene'),
                'style' => 'text-align:center',
            );
            $this->data['ref'] = array(
                'name' => 'ref',
                'id' => 'ref',
                'type' => 'text',
                'value' => $this->form_validation->set_value('ref'),
                'style' => 'text-align:center',
            );
            $this->data['hgvs'] = array(
                'name' => 'hgvs',
                'id' => 'hgvs',
                'type' => 'text',
                'value' => $this->form_validation->set_value('hgvs'),
                'style' => 'text-align:center',
            );

            $this->data['variant_id'] = array(
                'name' => 'variant_id',
                'id' => 'variant_id',
                'type' => 'text',
                'value' => $this->form_validation->set_value('variant_id'),
                'style' => 'text-align:center',
            );

            $this->data['sharing_policy'] = array(
                'name' => 'sharing_policy',
                'id' => 'sharing_policy',
                'type' => 'dropdown',
                'value' => $this->form_validation->set_value('sharing_policy'),
            );


            $this->data['individual_id'] = array(
                'name' => 'individual_id',
                'id' => 'individual_id',
                'type' => 'text',
                'value' => $this->form_validation->set_value('individual_id'),
                'style' => 'text-align:center',
            );

            $this->data['gender'] = array(
                'name' => 'gender',
                'id' => 'gender',
                'type' => 'dropdown',
                'value' => $this->form_validation->set_value('gender'),
            );

            $this->data['ethnicity'] = array(
                'name' => 'ethnicity',
                'id' => 'ethnicity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('ethnicity'),
                'style' => 'text-align:center',
            );

            $this->data['location_ref'] = array(
                'name' => 'location_ref',
                'id' => 'location_ref',
                'type' => 'text',
                'value' => $this->form_validation->set_value('location_ref'),
                'style' => 'width:10%; text-align:center',
            );

            $this->data['start'] = array(
                'name' => 'start',
                'id' => 'start',
                'type' => 'text',
                'value' => $this->form_validation->set_value('start'),
                'style' => 'width:15%; text-align:center',
            );

            $this->data['end'] = array(
                'name' => 'end',
                'id' => 'end',
                'type' => 'text',
                'value' => $this->form_validation->set_value('end'),
                'style' => 'width:15%; text-align:center',
            );

            $this->data['build'] = array(
                'name' => 'build',
                'id' => 'build',
                'type' => 'dropdown',
                'style' => 'width:10%',
                'size' => '2',
                'value' => $this->form_validation->set_value('end'),
            );

            $this->data['comment'] = array(
                'name' => 'comment',
                'id' => 'comment',
                'type' => 'text',
                'rows' => '4',
                'cols' => '40',
                'value' => $this->form_validation->set_value('comment'),
            );

//            $this->data['ont_list'] = array(
//                'name' => 'ont_list',
//                'id' => 'ont_list',
//                'type' => 'dropdown',
//                'value' => $this->form_validation->set_value('ont_list')
//                
//            );

			if ($this->input->post('ont_full')) {
				$phenodesc=array();
				$full_phenotype_list = $this->input->post('ont_full');
				foreach ($full_phenotype_list as $item) {
					list($value, $label) = explode("@@", $item);
					$phenodesc[$value] = $label;
				}
                
				$this->data['ont_list'] = $phenodesc;
				//error_log(print_r($this->input->post('ont_full'),1));
			}
            
//			$usedOntologies = $this->sources_model->getontologyvirtualids();
			$usedOntologies = $this->sources_model->getontologyabbreviations();
			$this->data['usedOntologies'] = $usedOntologies;

			$ontologies = $this->get_ontology_list($this->config->item('bioportalkey'));
			$this->data['ontologies'] = $ontologies;

			$this->_render('admin/add_variant');
        }
		else {
            $gene = $this->input->post('gene');
            $ref = $this->input->post('ref');
            $hgvs = $this->input->post('hgvs');
            $variant_id = $this->input->post('variant_id');
            $sharing_policy = $this->input->post('sharing_policy');
            $phenotype_list = $this->input->post('ont_item');
            $individual_id = $this->input->post('individual_id');
            $gender = $this->input->post('gender');
            $ethnicity = $this->input->post('ethnicity');
            $location_ref = $this->input->post('location_ref');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $build = $this->input->post('build');
            $comment = $this->input->post('comment');
            $mutalyzer_check = $this->input->post('mutalyzer');
            $date_time = date("j M Y H:i A");
            $source_id = $this->sources_model->getSourceIDFromName($source);
            $variant_data = array(
                "source" => $source,
                "laboratory" => $source,
                "gene" => $gene,
                "ref" => $ref,
                "hgvs" => $hgvs,
                "variant_id" => $variant_id,
                "sharing_policy" => $sharing_policy,
                "individual_id" => $individual_id,
                "gender" => $gender,
                "ethnicity" => $ethnicity,
                "location_ref" => $location_ref,
                "start" => $start,
                "end" => $end,
                "build" => $build,
                "comment" => $comment,
                "mutalyzer_check" => $mutalyzer_check,
                "date_time" => $date_time);

//			error_log("insert -> " . print_r($variant_data, 1));
            $insert_id = $this->sources_model->insertVariants($variant_data);
//			error_log("insert_id -> $insert_id");
			$index_data = array();
			$index_data = $variant_data; // Variants for ElasticSearch indexing
			$index_data['cafevariome_id'] = $insert_id; // Add cafevariome ID as this isn't present in the variant data array since ID is generated from autoincrement value
			$phenotype_array = array(); // Phenotypes for ElasticSearch indexing
                        if ($phenotype_list){
			foreach ($phenotype_list as $item) {
//				list($name, $cid, $vid, $userval, $qual) = explode("|", $item);
							   $items = explode("|", $item);
                        	   $name = $items[0];
                        	   $cid = $items[1];
                        	   $vid = $items[2];
                        	   $userval = $items[3];
                        	   $qual = "";
                        	   if ( count($items) == 5 ) {
                        			$qual = $items[4];
                        	   }                                
                                // Decide the type of the value
                                if ($userval =='null'){
                                    $userval=null;
                                    $type='qualityValue';
                                }
                               elseif (($userval=='present')||($userval=='absent')||($userval=='unknown')){
                                    $type='quality';
                                }
                                elseif(is_numeric($userval)){
                                    $type='numeric';
                                }
                                else{
                                    $type='qualityValue';
                                }
                                
                                if ($qual=='awol'){
                                    $qual=null;
                                }
                                elseif ($qual=='null'){  // not found in primary pheno lookup, so new term.  Try to find qaulifier in square brackets.
                                    if (preg_match('/\[[\w\-\ ]+\]/',$name,$match)) {
                                        $qual = $match[0];  // this is the qualifier including surrounding brackets
                                        $qual = substr($qual,1,-1);  // remove the brackets 
                                     }
                                    else{
                                         $qual = null;
                                    }
                                    
                                }
                                
				$phenotype_data = array(
					"attribute_sourceID" => $vid,
					"attribute_termID" => $cid,
					"attribute_termName" => $name,
                                        "value" => $userval,
                                        "attribute_qualifier" => $qual,
                                        "type" => $type,
					"cafevariome_id" => $insert_id);

				$phenotype_insert_id = $this->sources_model->insertPhenotypes($phenotype_data);

				$pl = $this->sources_model->getPrimaryLookup($cid);
				if (!$pl) {            
					$lookup_data = array(
						"sourceId" => $vid,
						"termId" => $cid,
						"termName" => $name,
                                                "qualifier" => $qual);

					$lookup_insert_id = $this->sources_model->insertPrimaryLookup($lookup_data);
				}
				

				
//				error_log("phenotype_data -> " . print_r($phenotype_data, 1));
				$attribute = str_replace(' ', '_', $name); // Remove spaces from the field as ElasticSearch is unable to handle spaces				

//				$map_data['variants']['properties']['phenotypes']['properties'][$attribute]['type'] = 'multi_field';
//				$map_data['variants']['properties']['phenotypes']['properties'][$attribute]['fields'] = array($attribute => array('type' => 'string', 'index' => 'analyzed', 'ignore_malformed' => 'true'), $attribute . '_d' => array('type' => 'double', 'index' => 'analyzed', 'ignore_malformed' => 'true'), $attribute . '_raw' => array('type' => 'string', 'ignore_malformed' => 'true', 'index' => 'not_analyzed')); // 'analyzer' => 'special_character_analyzer',  'index' => 'not_analyzed', 
//				$map_json = json_encode($map_data);
//				$map_result = $this->elasticsearch->map($map_json); // Do the mapping

				
				$phenotype_array_query['term_name'] = $name; // Want to index the phenotype term name so that it can be searched in standard query interface		
				$phenotype_array[$attribute] = strtolower($userval); // Also index the phenotype term and value in the index for querying in query builder
				// TODO: there is a problem with the multi-index in the query builder so need to regenerate the index manually (fix might be to look at mapping and add the new phenotype field with the different multi types but would need to check if the mappings already exist for that term already first)
				$index_data['phenotypes'][] = $phenotype_array;
				$index_data['phenotypes'][] = $phenotype_array_query;
				
//				error_log("index_data -> " . print_r($index_data, 1));
			}
                        }
			
			// ElasticSearch insert (if ElasticSearch is enabled and running)
			$index_data = json_encode($index_data);
//			error_log("index -> $index_data");
			if ( $this->config->item('use_elasticsearch') ) {
				$this->load->library('elasticsearch');
				$check_if_running = $this->elasticsearch->check_if_running();
				if ( array_key_exists( 'ok', $check_if_running) ) {
					// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
					$es_index = $this->config->item('site_title');
					$es_index = preg_replace('/\s+/', '', $es_index);
					$es_index = strtolower($es_index);
					$this->elasticsearch->set_index($es_index);
					$this->elasticsearch->set_type("variants");

					$index_result = $this->elasticsearch->add($insert_id, $index_data);
//					error_log("RESULT -> " . print_r($index_result, 1));
//					if ( ! $index_result[0]->ok ) {
					if ( ! $index_result ) {
						$index_result_flag = 0;
					}
//					error_log("RESULT -> " . print_r($delete_result, 1));
				}
			}
			
			$this->data['insert_id'] = $insert_id;
			if ($this->session->userdata('admin_or_curate') == "curate") {
				redirect("curate/variants", 'refresh');
			} 
			else {
				redirect("admin/variants", 'refresh');
			}
        }
    }

	function add_handsontable($source = NULL) {
		$this->javascript = array('handsontable.full.min.js', 'handsontable.select2-editor.js');
		$this->css = array('handsontable.full.min.css');

        // Check whether the user is either an admin or a curator that has the required permissions to do this action
        do {
            if (!$this->ion_auth->logged_in()) {
                redirect('auth', 'refresh');
            }
            if ($this->ion_auth->is_admin()) {
                break;
            }
            if ($this->ion_auth->in_group("curator")) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
                $user_id = $this->ion_auth->user()->row()->id;
                $source_id = $this->sources_model->getSourceIDFromName($source);
                $can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
                if (!$can_curate_source) {
                    show_error("Sorry, you are not listed as a curator for that particular source.");
                }
            }
        } while (0);
		
		$this->load->model('search_model');
		$this->data['variants'] = $this->search_model->getVariantsForSource($source);
        $this->data['source'] = $source;
        $this->data['title'] = "Add Variant";
		
		$this->load->model('general_model');
		$this->data['table_structure'] = $this->general_model->describeTable("variants");
		$this->data['core_fields'] = $this->general_model->getCoreFieldsAssociative();

		$this->_render('admin/add_variant_handsontable');
    }
	
	function _indexVariantElasticSearch($insert_id, $data) {
		// ElasticSearch insert (if ElasticSearch is enabled and running)
		$index_data = $data;
		$index_data['cafevariome_id'] = $insert_id;
		$index_data = json_encode($index_data);
//		error_log("index -> $index_data");
//		if ( $this->config->item('use_elasticsearch') ) {
//			$this->load->library('elasticsearch');
//			$check_if_running = $this->elasticsearch->check_if_running();
//			if ( array_key_exists( 'ok', $check_if_running) ) {
				// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
				$es_index = $this->config->item('site_title');
				$es_index = preg_replace('/\s+/', '', $es_index);
				$es_index = strtolower($es_index);
				$this->elasticsearch->set_index($es_index);
				$this->elasticsearch->set_type("variants");
				$index_result = $this->elasticsearch->add($insert_id, $index_data);
//				error_log("RESULT -> " . print_r($index_result, 1));
				return $index_result;
//			}
//			else {
//				return false;
//			}
//		}
//		else {
//			return false;
//		}
	}
	
    function get_ontology_list($apikey) {

        $params = array('dir' => FCPATH . 'Cache');
        $this->load->library('jg_cache', $params);
        error_log("get from cache");
        $data = $this->jg_cache->get('fullontologylist', 86400);

        if ($data === FALSE) {
            error_log("cache not present OR has expired - getting ontology list");
            $data = $this->_bioportal_ontology_list($apikey);
            $this->jg_cache->set('fullontologylist', $data);
        }
        return $data;
    }
    

//    function _bioportal_ontology_list($apikey) {
//
//        $url = "http://rest.bioontology.org/bioportal/ontologies?apikey=$apikey";
//        $context = stream_context_create(array(
//            'http' => array(
//                'method' => "GET",
//                'header' => "content-type: application/xml"
//            )
//        ));
//        $content = file_get_contents($url, false, $context);
//
//        $xml = simplexml_load_string($content);
//        $results = $xml->xpath('/success/data/list/ontologyBean');
//
//        foreach ($results as $ontology) {
//
//            $label = $ontology->displayLabel. "|" .$ontology->abbreviation;
//            $id = strval($ontology->id) . "|" . strval($ontology->ontologyId);
//            $list[$id] = (string) $label;
//        }
//        asort($list);
//
//        return $list;
//    }
    
    
    function _bioportal_ontology_list($apikey) {

        $url = "http://data.bioontology.org/ontologies?apikey=$apikey";
        $content = file_get_contents($url);
        $data = json_decode($content);
        $numberofontologies = sizeof($data);
        for ($i = 0; $i < $numberofontologies; $i++) {
            $ontname = $data[$i]->name;
            $ontacronym = $data[$i]->acronym;
            $list[$ontacronym] = $ontname;
        }
        asort($list);
        return $list;
    }    
    
    

    function custom_sort($a, $b) {
        return strcmp($a['label'], $b['label']);
    }

    function curate($source = NULL) {
		// Check whether the user is either an admin or a curator that has the required permissions to do this action
		do {
			if (!$this->ion_auth->logged_in() ) {
				redirect('auth', 'refresh');
			}
			if ($this->ion_auth->is_admin() ) {
				break;
			}
			if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
				$user_id = $this->ion_auth->user()->row()->id;
				$source_id = $this->sources_model->getSourceIDFromName($source);
				$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
				if ( ! $can_curate_source ) {
					show_error("Sorry, you are not listed as a curator for that particular source.");
				}
			}
		} while (0);


//		if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
//			$user_id = $this->ion_auth->user()->row()->id;
//			$source_id = $this->sources_model->getSourceIDFromName($source);
//			$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
//			if ( ! $can_curate_source ) {
//				show_error("Sorry, you are not listed as a curator for that particular source.");
//			}
//		}
//		elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin() ) {
//			redirect('auth', 'refresh');
//		}

		
		if ( ! isset($source)) {
			show_error("You must specify a source.");
		}
		$this->load->model('search_model');
		$count = $this->sources_model->countVariantsInSource($source);
		if ( $count <= $this->config->item('max_variants')) {
			$s = $this->sources_model->getSourceSingle($source);
			$source_full = $s[$source];
			$this->data['source_full'] = $source_full;
			$this->data['source'] = $source;
			$variants = $this->search_model->getVariantsForSource($source);
			$this->data['variants'] = $variants;
			$this->_render('admin/curate');
		}
		else {
			show_error("Cannot curate individual variants for this source - max number of variants to display has been exceeded");
		}
	}

	function curate_datatable() {
		
		// Check whether the user is either an admin or a curator that has the required permissions to do this action
		do {
			if (!$this->ion_auth->logged_in() ) {
				redirect('auth', 'refresh');
			}
			if ($this->ion_auth->is_admin() ) {
				break;
			}
			if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
				$user_id = $this->ion_auth->user()->row()->id;
				$source_id = $this->sources_model->getSourceIDFromName($source);
				$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
				if ( ! $can_curate_source ) {
					show_error("Sorry, you are not listed as a curator for that particular source.");
				}
			}
		} while (0);
		
        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
		$sSortDir_0 = $this->input->get_post('sSortDir_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);
//		$path = $this->input->get_post('path', true);
//		error_log("path -> $path sEcho -> " . $sEcho . " iDisplayStart -> " . $iDisplayStart . " iDisplayLength -> " . $iDisplayLength . " sSortDir_0 -> " . $sSortDir_0 . " iSortCol_0 -> " . $iSortCol_0 . " iSortingCols -> " . $iSortingCols . " sSearch -> " . $sSearch);
//		$path_array = explode('/', $path);
		// /cafevariome/variants/curate/diagnostic_sheff
//		$source = array_pop($path_array);
		$source = $this->input->get_post('source', true);
//		error_log("data -> $source");
		
		$this->load->model('search_model');
		$count = $this->sources_model->countVariantsInSource($source);
		$s = $this->sources_model->getSourceSingle($source);
		$source_full = $s[$source];
		$this->data['source_full'] = $source_full;
		$this->data['source'] = $source;
		$variants = $this->search_model->getVariantsForSourceWithPhenotypes($source);
		$iTotalRecords = count($variants);
		
		// Ordering
		if (isset($iSortCol_0)) {
//			error_log("ordering");
			$sort = array();
			foreach ($variants as $key => $row) {
//				error_log("direction -> $sSortDir_0 $iSortCol_0");
//				$phenotypes = $this->sources_model->getPhenotypesList($row['cafevariome_id']);
				if ( $iSortCol_0 == 1 ) {
					foreach ($variants as $key => $row) {
//						error_log("key -> $key | row -> $row -> " . $row['cafevariome_id']);
						$sort[$key] = $row['cafevariome_id'];
					}
					if ( $sSortDir_0 == "desc" ) {
						array_multisort($sort, SORT_DESC, SORT_NUMERIC, $variants);
					}
					elseif ( $sSortDir_0 == "asc" ) {
						array_multisort($sort, SORT_ASC, SORT_NUMERIC, $variants);
					}
				}
				elseif ( $iSortCol_0 == 2 ) {
					foreach ($variants as $key => $row) {
//						error_log("key -> $key | row -> $row -> " . $row['cafevariome_id']);
						$sort[$key] = $row['gene'];
					}
					if ( $sSortDir_0 == "desc" ) {
						array_multisort($sort, SORT_DESC, $variants);
					}
					elseif ( $sSortDir_0 == "asc" ) {
						array_multisort($sort, SORT_ASC, $variants);
					}
				}
				elseif ( $iSortCol_0 == 3 ) {
					foreach ($variants as $key => $row) {
//						error_log("key -> $key | row -> $row -> " . $row['cafevariome_id']);
						$sort[$key] = $row['ref'];
					}
					if ( $sSortDir_0 == "desc" ) {
						array_multisort($sort, SORT_DESC, $variants);
					}
					elseif ( $sSortDir_0 == "asc" ) {
						array_multisort($sort, SORT_ASC, $variants);
					}
				}
				elseif ( $iSortCol_0 == 4 ) {
					foreach ($variants as $key => $row) {
//						error_log("key -> $key | row -> $row -> " . $row['cafevariome_id']);
						$sort[$key] = $row['hgvs'];
					}
					if ( $sSortDir_0 == "desc" ) {
						array_multisort($sort, SORT_DESC, $variants);
					}
					elseif ( $sSortDir_0 == "asc" ) {
						array_multisort($sort, SORT_ASC, $variants);
					}
				}
				elseif ( $iSortCol_0 == 5 ) {
					foreach ($variants as $key => $row) {
//						error_log("key -> $key | row -> $row -> " . $row['cafevariome_id']);
						$sort[$key] = $row['phenotype'];
//						error_log("key -> $key | row -> $row -> " . $phenotypes);
//						$sort[$key] = $phenotypes;
					}
					if ( $sSortDir_0 == "desc" ) {
						array_multisort($sort, SORT_DESC, $variants);
					}
					elseif ( $sSortDir_0 == "asc" ) {
						array_multisort($sort, SORT_ASC, $variants);
					}
				}
				elseif ( $iSortCol_0 == 6 ) {
					foreach ($variants as $key => $row) {
//						error_log("key -> $key | row -> $row -> " . $row['cafevariome_id']);
						$sort[$key] = $row['sharing_policy'];
					}
					if ( $sSortDir_0 == "desc" ) {
						array_multisort($sort, SORT_DESC, $variants);
					}
					elseif ( $sSortDir_0 == "asc" ) {
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
				foreach ( $variants as $id => $variant ) { // Go through all the variants
					$unset_flag = 0;
					foreach ( $variant as $key => $value ) { // Now search all values against the supplied search term
//						error_log("key -> $key | value -> $value");
						if (preg_match("/$sSearch/i", $value)) { // Case insensitive regex to match the search term to the current value
							$unset_flag = 1;
//							error_log("match $id -> $key --> $sSearch -> $value");
						}
					}
					if ( ! $unset_flag ) { // Variant didn't match the search term so remove it from the array so that it doesn't get displayed
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
				if ( $iDisplayLength != 0 ) {
				
				}
				$variant_count++;
				if ( $variant_count <= $iDisplayStart ) {
					unset($variants[$id]);
				}
				else {
					if ( $variant_count > ($iDisplayStart + $iDisplayLength )) {
						unset($variants[$id]);
					}
				}
			}
		}
		
		// Output
		$output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotalRecords,
            'iTotalDisplayRecords' => $iTotalDisplayRecords,
            'aaData' => array()
        );

		// Loop through the variants that are left (if any filtering/paging has been done) and create the json for outputting to the datatable
		foreach ( $variants as $variant ) {
                    
			# get phenotype for a variant
//			$phenotypes = $this->sources_model->getPhenotypesList($variant['cafevariome_id']);
			
			$row = array();
//			error_log("after -> " . $variant['cafevariome_id']);
			
			$data = array( 'class' => 'case', 'name' => 'case', 'id' => $variant['cafevariome_id'], 'value' => $variant['cafevariome_id'], 'checked' => FALSE, 'style' => 'margin:10px',);
			$row[] = form_checkbox($data);
			
			$cvid_link = base_url("/discover/variant/" . $variant['cafevariome_id']);
			$row[] = "<a class='basic' href='$cvid_link' href='$cvid_link'>" . $this->config->item('cvid_prefix') . $variant['cafevariome_id'] . "</a>";
//			$row[] = $this->config->item('cvid_prefix') . $variant['cafevariome_id'];
			if (isset($variant['gene'])) {
				$row[] = $variant['gene'];
			}
			else {
				$row[] = "-";
			}
			
			if (isset($variant['ref'])) {
				$row[] = $variant['ref'];
			}
			else {
				$row[] = "-";
			}
			
			if (isset($variant['hgvs'])) {
				$row[] = $variant['hgvs'];
			}
			else {
				$row[] = "-";
			}
			
			//if ( (isset($variant['phenotype']) && ($variant['phenotype'] !== "") )) {
			//	$row[] = $variant['phenotype'];
                                
			if ( isset($variant['phenotype']) && $variant['phenotype'] !== "" ) {
//				error_log("variant -> " . print_r($variant, 1));
//				$row[] = $phenotypes;
				$row[] = $variant['phenotype'];
			}
			else {
				$row[] = "-";
			}
			
			if (isset($variant['sharing_policy'])) {
				$row[] = $variant['sharing_policy'];
			}
			else {
				$row[] = "-";
			}
			
			$row[] = "<a href='" . base_url('variants/edit_variant'). "/" . $variant['source'] . "/" . $variant['cafevariome_id'] . "' rel='popover' data-content='Edit the fields of this variant' data-original-title='Edit Variant'><i class='fa fa-pencil' style='color: black;'></i></a>" . nbs(3) . "<a href='" . base_url('variants/delete_variant'). "/" . $variant['source'] . "/" . $variant['cafevariome_id'] . "' rel='popover' data-content='Delete this variant.' data-original-title='Delete Variant'></i><i class='icon-trash'></i></a>";
			
			$output['aaData'][] = $row;
		}

//		error_log(print_r(json_encode($output), 1));
//		$this->output->set_header('Content-Type: application/json; charset=utf-8');
//		echo json_encode($output);
		
		die(json_encode($output)); // json error when echoing like on previous line if there were above a certain number of variants - this works but not sure why - php error 
//		ERROR WAS: Cannot modify header information - headers already sent by (output started at /Library/WebServer/Documents/cafevariome/application/controllers/discover.php:893


	}
	
	function delete_variant($source = NULL, $id = NULL) {
		// Check whether the user is either an admin or a curator that has the required permissions to do this action
		do {
			if (!$this->ion_auth->logged_in() ) {
				redirect('auth', 'refresh');
			}
			if ($this->ion_auth->is_admin()) {
				break;
			}
			if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
				$user_id = $this->ion_auth->user()->row()->id;
				$source_id = $this->sources_model->getSourceIDFromName($source);
				$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
				if ( ! $can_curate_source ) {
					show_error("Sorry, you are not listed as a curator for that particular source.");
				}
			}

		} while (0);
//		if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
//			$user_id = $this->ion_auth->user()->row()->id;
//			$source_id = $this->sources_model->getSourceIDFromName($source);
//			$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
//			if ( ! $can_curate_source ) {
//				show_error("Sorry, you are not listed as a curator for that particular source.");
//			}
//		}
//		elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth', 'refresh');
//		}
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('source', 'Source Name', 'required|alpha_dash');

		if ($this->form_validation->run() == FALSE) {
			$this->data['id'] = $id;
			$this->data['source'] = $source;
			$this->_render('admin/delete_variant');
		}
		else {
			// do we really want to delete?
			if ($this->input->post('confirm') == 'yes') {
				// do we have a valid request?
				if ($id != $this->input->post('id')) {
					show_error('This form post did not pass our security checks.');
				}

				// do we have the right userlevel?
				do {
					if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
//                                                $is_deleted = $this->sources_model->deleteVariantPenotype($id);
						$is_deleted = $this->sources_model->deleteVariant($id);
						
						// ElasticSearch update (if ElasticSearch is enabled and running)
						if ( $this->config->item('use_elasticsearch') ) { 
							$this->load->library('elasticsearch');
							$check_if_running = $this->elasticsearch->check_if_running();
							if ( array_key_exists( 'ok', $check_if_running) ) {
								// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
								$es_index = $this->config->item('site_title');
								$es_index = preg_replace('/\s+/', '', $es_index);
								$es_index = strtolower($es_index);
								$this->elasticsearch->set_index($es_index);
								$this->elasticsearch->set_type("variants");
//								error_log("id -> $id");
								$delete_result = $this->elasticsearch->delete($id);
//								error_log("RESULT -> " . print_r($delete_result, 1));
							}
						}
						
						break;
					}
					if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
						$user_id = $this->ion_auth->user()->row()->id;
						$source_id = $this->sources_model->getSourceIDFromName($this->input->post('source_name'));
						$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
						if ( ! $can_curate_source ) {
							show_error("Sorry, you are not listed as a curator for that particular source.");
						}
//                                                $is_deleted = $this->sources_model->deleteVariantPenotype($id);
						$is_deleted = $this->sources_model->deleteVariant($id);
						
						// ElasticSearch update (if ElasticSearch is enabled and running)
						if ( $this->config->item('use_elasticsearch') ) { 
							$this->load->library('elasticsearch');
							$check_if_running = $this->elasticsearch->check_if_running();
							if ( array_key_exists( 'ok', $check_if_running) ) {
								// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
								$es_index = $this->config->item('site_title');
								$es_index = preg_replace('/\s+/', '', $es_index);
								$es_index = strtolower($es_index);
								$this->elasticsearch->set_index($es_index);
								$this->elasticsearch->set_type("variants");
//								error_log("id -> $id");
								$delete_result = $this->elasticsearch->delete($id);
//								error_log("RESULT -> " . print_r($delete_result, 1));
							}
						}
						
						break;
					}
				} while (0);
			}
			if ($this->session->userdata('admin_or_curate') == "curate") {
				redirect('curate/curate_variants/' . $source, 'refresh');
			}
			else {
				//redirect them back to the curate page for the source
				redirect('variants/curate/' . $source, 'refresh');
			}
		}
	}
	
	public function edit_variant($source = NULL, $id = NULL) {
		// Check whether the user is either an admin or a curator that has the required permissions to do this action
		do {
			if (!$this->ion_auth->logged_in()) {
				redirect('auth', 'refresh');
			}
			if ($this->ion_auth->is_admin()) {
				break;
			}
			if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
				$user_id = $this->ion_auth->user()->row()->id;
				$source_id = $this->sources_model->getSourceIDFromName($source);
				$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
				if ( ! $can_curate_source ) {
					show_error("Sorry, you are not listed as a curator for that particular source.");
				}
			}
		} while (0);

//		if ( $this->ion_auth->in_group("curator") ) { // Since this is a shared function for curators and admin check that the curator is a curator for this source
//			$user_id = $this->ion_auth->user()->row()->id;
//			$source_id = $this->sources_model->getSourceIDFromName($source);
//			$can_curate_source = $this->sources_model->checkUserCanCurateThisSource($source_id, $user_id);
//			if ( ! $can_curate_source ) {
//				show_error("Sorry, you are not listed as a curator for that particular source.");
//			}
//		}
//		elseif (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			redirect('auth', 'refresh');
//		}

		
		$this->data['source'] = $source;
		$this->data['id'] = $id;
		$this->data['title'] = "Edit Variant";

		//validate form input
		$this->form_validation->set_rules('gene', 'Gene Symbol', 'xss_clean'); // required
		$this->form_validation->set_rules('ref', 'Reference Sequence', 'xss_clean'); // required
		$this->form_validation->set_rules('hgvs', 'HGVS nomenclature', 'xss_clean'); // required
		$this->form_validation->set_rules('sharing_policy', 'Sharing Policy', 'required|xss_clean');
		$this->form_validation->set_rules('ont_list', 'Phenotype description', 'xss_clean');
                
		$this->form_validation->set_rules('individual_id', 'Patient ID', 'xss_clean');
		$this->form_validation->set_rules('gender', 'Gender', 'xss_clean');
		$this->form_validation->set_rules('ethnicity', 'Ethnicity', 'xss_clean');
		$this->form_validation->set_rules('location_ref', 'Chromosome', 'xss_clean');
		$this->form_validation->set_rules('start', 'Start', 'xss_clean');
		$this->form_validation->set_rules('end', 'End', 'xss_clean');
		$this->form_validation->set_rules('comment', 'Comment', 'xss_clean');
		$this->form_validation->set_rules('mutalyzer', 'Mutalyzer Check', 'xss_clean'); // required
		$this->form_validation->set_message('required', 'The %s field is required.');

		if ($this->form_validation->run() == true) {
			//check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			$update_data['gene'] = $this->input->post('gene');
			$update_data['ref'] = $this->input->post('ref');
			$update_data['hgvs'] = $this->input->post('hgvs');
			$update_data['variant_id'] = $this->input->post('variant_id');
			$update_data['sharing_policy'] = $this->input->post('sharing_policy');
                        $phenotype_list = $this->input->post('ont_item');                   
			$update_data['individual_id'] = $this->input->post('individual_id');
			$update_data['gender'] = $this->input->post('gender');
			$update_data['ethnicity'] = $this->input->post('ethnicity');
			$update_data['location_ref'] = $this->input->post('location_ref');
			$update_data['start'] = $this->input->post('start');
			$update_data['end'] = $this->input->post('end');
			$update_data['comment'] = $this->input->post('comment');
			$this->sources_model->updateVariant($update_data, $id);
			
                        
                        // Update Phenotypes - delete existing annotations and add (again) the new annotations 
                        $this->sources_model->deletePhenotype($id);
                        
                        if ($phenotype_list){
                        foreach ($phenotype_list as $item) {
//								error_log('item -> ' . print_r($item, 1));
                        	   	// list($name, $cid, $vid, $userval, $qual) = explode("|", $item);
							   $items = explode("|", $item);
                        	   $name = $items[0];
                        	   $cid = $items[1];
                        	   $vid = $items[2];
                        	   $userval = $items[3];
                        	   $qual = "";
                        	   if ( count($items) == 5 ) {
                        			$qual = $items[4];
                        	   }
                                
                               // Decide the type of the value
                                if ($userval =='null'){
                                    $userval=null;
                                    $type='qualityValue';
                                }
                               elseif (($userval=='present')||($userval=='absent')||($userval=='unknown')){
                                    $type='quality';
                                }
                                elseif(is_numeric($userval)){
                                    $type='numeric';
                                }
                                else{
                                    $type='qualityValue';
                                }
                                
                                if ($qual=='awol'){
                                    $qual=null;
                                }
                                elseif ($qual=='null'){  // not found in primary pheno lookup, so new term.  Try to find qaulifier in square brackets.
                                    if (preg_match('/\[[\w\-\ ]+\]/',$name,$match)) {
                                        $qual = $match[0];  // this is the qualifier including surrounding brackets
                                        $qual = substr($qual,1,-1);  // remove the brackets 
                                     }
                                    else{
                                         $qual = null;
                                    }
                                    
                                }
                                                    
				$phenotype_data = array(
					"attribute_sourceID" => $vid,
					"attribute_termID" => $cid,
					"attribute_termName" => $name,
                                        "value" => $userval,
                                        "attribute_qualifier" => $qual,
                                        "type" => $type,
					"cafevariome_id" => $id);

				$phenotype_insert_id = $this->sources_model->insertPhenotypes($phenotype_data);

				$pl = $this->sources_model->getPrimaryLookup($cid);
				if (!$pl) {
					$lookup_data = array(
						"sourceId" => $vid,
						"termId" => $cid,
						"termName" => $name,
                                                 "qualifier" => $qual);

					$lookup_insert_id = $this->sources_model->insertPrimaryLookup($lookup_data);
				}
				
				
//				error_log("phenotype_data -> " . print_r($phenotype_data, 1));
				$attribute = str_replace(' ', '_', $name); // Remove spaces from the field as ElasticSearch is unable to handle spaces				

//				$map_data['variants']['properties']['phenotypes']['properties'][$attribute]['type'] = 'multi_field';
//				$map_data['variants']['properties']['phenotypes']['properties'][$attribute]['fields'] = array($attribute => array('type' => 'string', 'index' => 'analyzed', 'ignore_malformed' => 'true'), $attribute . '_d' => array('type' => 'double', 'index' => 'analyzed', 'ignore_malformed' => 'true'), $attribute . '_raw' => array('type' => 'string', 'ignore_malformed' => 'true', 'index' => 'not_analyzed')); // 'analyzer' => 'special_character_analyzer',  'index' => 'not_analyzed', 
//				$map_json = json_encode($map_data);
//				$map_result = $this->elasticsearch->map($map_json); // Do the mapping

				$phenotype_array_query['term_name'] = $name; // Want to index the phenotype term name so that it can be searched in standard google-like query interface		
				$phenotype_array[$attribute] = strtolower($userval); // Also index the phenotype term and value in the index for querying in query builder
				// TODO: there is a problem with the multi-index in the query builder so need to regenerate the index manually (fix might be to look at mapping and add the new phenotype field with the different multi types but would need to check if the mappings already exist for that term already first)
				$update_data['phenotypes'][] = $phenotype_array;
				$update_data['phenotypes'][] = $phenotype_array_query;

				
			}
                       }
                        
                                                
				
			// ElasticSearch update (if ElasticSearch is enabled and running)
			if ( $this->config->item('use_elasticsearch') ) { 
				$this->load->library('elasticsearch');
				$check_if_running = $this->elasticsearch->check_if_running();
				if ( array_key_exists( 'ok', $check_if_running) ) {
					// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
					$es_index = $this->config->item('site_title');
					$es_index = preg_replace('/\s+/', '', $es_index);
					$es_index = strtolower($es_index);
					$this->elasticsearch->set_index($es_index);
					$this->elasticsearch->set_type("variants");
//					error_log("id -> $id");
					$update = array();
					$update['doc'] = $update_data;
					$update = json_encode($update);
//					error_log("update $id $update");
					$update_result = $this->elasticsearch->update($id, $update);
//					error_log("RESULT -> " . print_r($update_result, 1));
					if ( ! $update_result ) {
						$update_result_flag = 0;
					}
				}
			}
			
			redirect("variants/curate/" . $source, 'refresh');
		}
		else {
			$variant_data = $this->sources_model->getVariant($id);
			$this->data['variant_data'] = $variant_data;
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['gene'] = array(
				'name'  => 'gene',
				'id'    => 'gene',
				'type'  => 'gene',
				'value' => $this->form_validation->set_value('gene', $variant_data['gene']),
				'style' => 'text-align:center',
			);
			$this->data['ref'] = array(
				'name'  => 'ref',
				'id'    => 'ref',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('ref', $variant_data['ref']),
				'style' => 'text-align:center',
			);
			$this->data['hgvs'] = array(
				'name'  => 'hgvs',
				'id'    => 'hgvs',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('hgvs', $variant_data['hgvs']),
				'style' => 'text-align:center',
			);
			
			$this->data['variant_id'] = array(
				'name'  => 'variant_id',
				'id'    => 'variant_id',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('variant_id', $variant_data['variant_id']),
				'style' => 'text-align:center',
			);
			
			$this->data['sharing_policy'] = array(
				'name'  => 'sharing_policy',
				'id'    => 'sharing_policy',
				'type'  => 'dropdown',
				'value' => $this->form_validation->set_value('sharing_policy', $variant_data['sharing_policy']),
			);
			
			$this->data['phenotype'] = array(
				'name'  => 'phenotype',
				'id'    => 'phenotype',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phenotype', $variant_data['phenotype']),
				'style' => 'width:90%; text-align:center',
			);
		
			$this->data['individual_id'] = array(
				'name'  => 'individual_id',
				'id'    => 'individual_id',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('individual_id', $variant_data['individual_id']),
				'style' => 'text-align:center',
			);
			
			$this->data['gender'] = array(
				'name'  => 'gender',
				'id'    => 'gender',
				'type'  => 'dropdown',
				'value' => $this->form_validation->set_value('gender', $variant_data['gender']),
			);
			
			$this->data['ethnicity'] = array(
				'name'  => 'ethnicity',
				'id'    => 'ethnicity',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('ethnicity', $variant_data['ethnicity']),
				'style' => 'text-align:center',
			);
			
			$this->data['location_ref'] = array(
				'name'  => 'location_ref',
				'id'    => 'location_ref',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('location_ref', $variant_data['location_ref']),
				'style' => 'width:10%; text-align:center',
			);

			$this->data['start'] = array(
				'name'  => 'start',
				'id'    => 'start',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('start', $variant_data['start']),
				'style' => 'width:15%; text-align:center',
			);
			
			$this->data['end'] = array(
				'name'  => 'end',
				'id'    => 'end',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('end', $variant_data['end']),
				'style' => 'width:15%; text-align:center',
			);
			
			$this->data['build'] = array(
				'name'  => 'build',
				'id'    => 'build',
				'type'  => 'dropdown',
				'style' => 'width:10%',
				'size' => '2',
				'value' => $this->form_validation->set_value('end', $variant_data['end']),
			);

			$this->data['comment'] = array(
				'name'  => 'comment',
				'id'    => 'comment',
				'type'  => 'text',
				'rows' => '4',
				'cols'=> '40',
				'value' => $this->form_validation->set_value('comment', $variant_data['comment']),
			);
                        
                        ////// Deal with refreshing the phenotype description list if the page fails validation
                        if($this->input->post('ont_full')){
                            $phenodesc=array();
                            $full_phenotype_list = $this->input->post('ont_full');
                            foreach ($full_phenotype_list as $item) {
                                list($value, $label) = explode("@@", $item);
                                $phenodesc[$value] = $label;
                            }
                            $this->data['ont_list'] = $phenodesc;
                            //error_log(print_r($this->input->post('ont_full'),1));
                        }
                        ///////
                        
                        $usedOntologies = $this->sources_model->getontologyabbreviations();
                        $this->data['usedOntologies'] = $usedOntologies;

                        $ontologies = $this->get_ontology_list($this->config->item('bioportalkey'));
                        $this->data['ontologies'] = $ontologies;
                        
                        
                        $existing_phenotypes = $this->sources_model->getexistingphenotypes($id);
                        $this->data['existing_phenotypes'] = $existing_phenotypes;

			$this->_render('admin/edit_variant');

		}
	}
	
	function do_upload($source) {
		$config['upload_path'] = FCPATH . 'upload/';
//		error_log(FCPATH . '/upload/');
		$config['allowed_types'] = 'xls|xlsx|vcf|txt|xml|sql|lovd';
//		$config['max_size'] = '1000';
//		$config['max_width'] = '1024';
//		$config['max_height'] = '768';

		if ($this->input->post('sharing_policy')) {
			$sharing_policy = $this->input->post('sharing_policy');
		}
		else {
			$sharing_policy = "openAccess";
		}
		
		$mutalyzer_check = $this->input->post('mutalyzer_check');
		
		$fileformat = $this->input->post('fileformat');

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload()) {
			echo '<div id="status">error</div>';
			echo '<div id="message">' . $this->upload->display_errors() . '</div>';
		}
		else {
			$data = array('upload_data' => $this->upload->data());
//			error_log($data['upload_data']['file_name'] . " -> " . $data['upload_data']['full_path'] . " -> " . $data['upload_data']['file_ext']);
			$source_id = $this->sources_model->getSourceIDFromName($source);
			$validate_results = $this->_validateFile($data['upload_data']['full_path'], $data['upload_data']['file_ext'], $source, $source_id, $sharing_policy, $fileformat, $mutalyzer_check);
			if ( $validate_results['result_flag'] ) {
				echo '<div id="status">success</div>';
				//then output your message (optional)
				echo '<div id="message">' . $data['upload_data']['file_name'] . ' SUCCESSFULLY IMPORTED.</div>';
				//pass the data to js
				echo '<div id="upload_data">' . json_encode($data) . '</div>';
			}
			else {
//				error_log(print_r($validate_results, true));
				echo '<div id="status">success</div>';
				//then output your message (optional)
				echo '<div id="message">' . $data['upload_data']['file_name'] . ' IMPORT FAILED: ' . $validate_results['error'] . '</div>';
				//pass the data to js
				echo '<div id="upload_data">' . json_encode($data) . '</div>';				
			}
		}
	}
	
	function _validateFile($file, $extension, $source, $source_id, $sharing_policy, $fileformat, $mutalyzer_check) {
		$date_time = date("j M Y H:i A");
		$return_data = array();
		$return_data['result_flag'] = 1;
//		error_log("extension -> " . $extension);
//		error_log("mutalyzer_check -> $mutalyzer_check");
		if ( $mutalyzer_check == "yes" ) {
			$client = $this->initializeMutalyzer(); // Initialize Mutalyzer if user said yes to checking
			$path_parts = pathinfo($file);
			$mutalyzer_log_filename = $path_parts['dirname'] . "/" . $path_parts['filename'] . ".log";
//			if (is_writable($mutalyzer_log_filename)) {
//				error_log("writable -> $mutalyzer_log_filename");
				$mutalyzer_log = fopen($mutalyzer_log_filename,"w");
				echo fwrite($mutalyzer_log,"Errors\tWarnings\tHGVS\tLine_Number\tMessage\tMutalyzer_URL\n");
//			}
//			else {
//				error_log("not writable -> $mutalyzer_log_filename");
//			}
		}
		
		if ( $this->config->item('use_elasticsearch') ) {
			$this->load->library('elasticsearch');
		}
		
		if ( $fileformat == 'tab_core' ) {
			
			// Check it's a text file, if it's not then throw error and report to the user
//			$mime_type = mime_content_type($file);
//			error_log("mime type -> " . $mime_type);
//			if (! preg_match("/text/i", $mime_type)) {
//				$return_data['result_flag'] = 0;
//				$return_data['error'] = "The input file doesn't seem to be a text file, please check the file and make sure you specify the correct file format.";
//				return $return_data;
//			}
			
			$escaped_file = escapeshellcmd($file); // Escape any brackets etc in the file and count number of lines
			$number_lines = exec("wc -l < $escaped_file");
			$number_lines = preg_replace( '/\s+/', '', $number_lines );
//			error_log("num -> $number_lines");
			if ($number_lines < $this->config->item('max_variants')) {
				$handle = fopen($file, "r");
				$c = 0;
				$this->load->model('general_model');
//				$headers = $this->general_model->getCoreFields();
				// Initialize core fields array which is populated from import template headers
				$core_fields = array();
				// Get all the fields that are in the main feature table (used for checking whether all fields in the import template actually exist in the database
				$variant_table_fields = $this->general_model->describeTable($this->config->item('feature_table_name'));
				//loop through the csv file and insert into database
				while (($data = fgetcsv($handle, 0, "\t")) !== FALSE) {
					$c++;
					if ( $c === 1 ) { // Get the header line
						foreach ( $data as $column ) {
							$column_count++;
							if ( $column ) { // Only store if there's data for this column
								
								$cell_value = (string) $column;
								if (preg_match('/phenotype\:(.*)/', $cell_value, $phenotype_match)) {
//									error_log('match -> ' . print_r($phenotype_match, 1));
									$core_fields[] = $phenotype_match[0]; // Store the header name
//									error_log("okay $cell_value");
								}
								else {
									$core_fields[] = $column; // Store the header name
									if ( ! array_key_exists($column, $variant_table_fields)) { // Check whether this header is defined in the core fields table, return an error if not since there's a mismatch
//										error_log("HEADER CELL ->  $cell_value");
										$return_data['result_flag'] = 0;
										$return_data['error'] = "There are headers in your import template that do not match up to a field in the " . $this->config->item('feature_table_name') . " table. Import cannot proceed, email admin@cafevariome.org if you cannot resolve this problem.";
										return $return_data;
									}
								}
							}
						}
					}
					else { // All other lines are actual data
						$column_count = 0;
						$insert_data = array();
						foreach ( $data as $column ) {
							$column_count++;
							$current_header = $core_fields[$column_count-1];
							
							if ( $current_header == "variant_id" ) { // Do a check to see if this is the variant_id column (it may or may not have data)
								if ( ! $column ) { // If it is then check to see there's a value present, if not then return error
//									error_log("no value for $row_count $column_count");
									if ( $this->config->item('all_records_require_an_id') ) { // If the setting is set to on then check whether there's a record ID present for each record
										$return_data['result_flag'] = 0;
										$return_data['error'] = "All records require a record ID, there are some records in the import data that do not have a record ID, please add record IDs to all records and re-try the import.";
										return $return_data;
									}
								}
								else {
									$insert_data[$current_header] = $column;
								}
							}
							elseif ( $column ) { // Only store if there's data for this column
								$column = trim($column); // Remove whitespace from start and end of the string
								
								$cell_value = trim($column);
//								error_log("value -> $cell_value");
								if (preg_match('/phenotype:(.*)/i', $current_header, $phenotype_match)) {
									error_log("phenotype -> " . print_r($phenotype_match, 1) . " -> $cell_value");
									$phenotype_attribute = preg_replace('/phenotype\:/', '', $phenotype_match[0]);
									$phenotype_value = $cell_value;
									error_log("attribute -> $phenotype_attribute --- value -> $phenotype_value");
									// TODO: Add this into the EAV table as it's a phenotype
									$phenotype_data_array = $this->_parse_phenotype_data_eav($phenotype_attribute, $phenotype_value);
//									error_log(print_r($phenotype_data_array, 1));
									$phenotype_insert_data[] = $phenotype_data_array['phenotype_insert_data'];
									$primary_phenotype_lookup_data[] = $phenotype_data_array['primary_phenotype_lookup_data'];
								}
								elseif ($current_header == "phenotype") { // If it's a phenotype field
//									error_log("STARTING $column_count -> $column");
									$phenotype_data_array = $this->_parse_phenotype_data($column);
									$phenotype_insert_data = $phenotype_data_array['phenotype_insert_data'];
									$primary_phenotype_lookup_data = $phenotype_data_array['primary_phenotype_lookup_data'];
								}
								else {
									$insert_data[$current_header] = $column;
								}
							}
						}
						if ( ! empty($insert_data) ) {
							// Override the source with the source based on the import page (hopefully avoids errors of not inputting the right source in the templates)
							$insert_data['source'] = $source;
							$insert_data['laboratory'] = $source;
							if ( ! array_key_exists('sharing_policy', $insert_data) ) {
								$insert_data['sharing_policy'] = $sharing_policy;
							}
							$insert_id = $this->sources_model->insertVariants($insert_data);
							if (!$insert_id) {
								$return_data['result_flag'] = 0;
								$return_data['error'] = "MySQL insert was unsuccessful";
							}
							
							// If there's phenotype data then insert it
							if ( ! empty ($phenotype_insert_data) ) {
//								error_log("INSERT -> " . print_r($phenotype_insert_data, 1));
								$phenotype_array = array();
								foreach ($phenotype_insert_data as $phenotype_data) {
									$phenotype_data['cafevariome_id'] = $insert_id; // Add the ID of the variant so that the phenotype can be linked to it
									$phenotype_insert_id = $this->sources_model->insertPhenotypes($phenotype_data);
									
									// Get the term name and add it to the insert_data array that is indexed in ElasticSearch
									$phenotype_array['term_name'] = $phenotype_data['attribute_termName'];
									$insert_data['phenotypes'][] = $phenotype_array;
									
								}
							}
						
							// Check if there's anything to insert into primary_phenotype_lookup table
							if ( ! empty ($primary_phenotype_lookup_data) ) {
								foreach ( $primary_phenotype_lookup_data as $primary_phenotype_lookup ) { // Go through each insert row
//									error_log("s");
									$pl = $this->sources_model->getPrimaryLookup($primary_phenotype_lookup['termId']); // Check if the termId is unique
									if (!$pl) { // If the termId doesn't exist in the table then insert it
										$lookup_data = array(
											"sourceId" => $primary_phenotype_lookup['sourceId'],
											"termId" => $primary_phenotype_lookup['termId'],
											"termName" => $primary_phenotype_lookup['termName']
										);
										$lookup_insert_id = $this->sources_model->insertPrimaryLookup($lookup_data);
									}
								}
							}
							
							if ( $this->config->item('use_elasticsearch') ) {
								$elastic_search_index_result = $this->_indexVariantElasticSearch($insert_id, $insert_data);
							}
							
						}
					}
				}
			}
			else {
				$return_data['result_flag'] = 0;
				$return_data['error'] = "Too many variants in input file (max is " . $this->config->item('max_variants') . " and you have $number_lines)";
				// TODO: convert the file to a format that can be used with LOAD DATA INFILE mysql command
			}
			return $return_data;
		}
		else if ( $fileformat == 'xml' ) {
			error_log("got xml");
		}
		else if ( $fileformat == 'excel_core' ) {
//			error_log("got core xls");
			ini_set('memory_limit','1024M');
			$this->load->library('phpexcel/PHPExcel');
			
			$excel_type = PHPExcel_IOFactory::identify($file); // Identify the file type, check it's actually an Excel file, throw an error if not
			if (! preg_match("/Excel/i", $excel_type)) {
				$return_data['result_flag'] = 0;
				$return_data['error'] = "The input file doesn't seem to be in Excel format, please check the file and make sure you specify the correct file format.";
				return $return_data;
			}
			
			$objPHPExcel = PHPExcel_IOFactory::load($file); // Factory should auto guess the Excel type
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
//			error_log(print_r($sheetData, 1));
			$row_count = 0;
			$this->load->model('general_model');
			// Initialize core fields array which is populated later from import template headers
			$core_fields = array();
			// Get all the fields that are in the main records table (used for checking whether all fields in the import template actually exist in the database
			$variant_table_fields = $this->general_model->describeTable($this->config->item('feature_table_name'));
//			error_log(print_r($variant_table_fields,1));


			
			foreach ( $sheetData as $row ) {
				$row_count++;
//				error_log("row -> " . $row_count);
				if ( $row_count === 1 ) { // It's the first row of the sheet - validate the headers and store for later
					foreach ( $row as $column ) {
						if ( $column ) {
							
							$cell_value = (string) $column;
							if (preg_match('/phenotype\:(.*)/', $cell_value, $phenotype_match)) {
//								error_log('match -> ' . print_r($phenotype_match, 1));
								$core_fields[] = $phenotype_match[0]; // Store the header name
//								error_log("okay $cell_value");
							}
							else {
								$core_fields[] = $cell_value; // Store the header name
								if ( ! array_key_exists($cell_value, $variant_table_fields)) { // Check whether this header is defined in the core fields table, return an error if not since there's a mismatch
//									error_log("HEADER CELL ->  $cell_value");
									$return_data['result_flag'] = 0;
									$return_data['error'] = "There are headers in your import template that do not match up to a field in the " . $this->config->item('feature_table_name') . " table. Import cannot proceed, email admin@cafevariome.org if you cannot resolve this problem.";
									return $return_data;
								}
							}
						}
					}
				}
				else { // All other lines are actual data
					$column_count = 0;
					$insert_data = array();
					$phenotype_insert_data = array();
					$primary_phenotype_lookup_data = array();
					foreach ( $row as $column ) {
						$column_count++;
						$current_header = (string) $core_fields[$column_count-1]; // Match this column to the correct header name (stored above)
//						error_log("header -> " . $current_header);

						if ( $current_header == "variant_id" ) { // Do a check to see if this is the variant_id column (it may or may not have data)
							if ( ! $column ) { // If it is then check to see there's a value present, if not then return error
//								error_log("no value for $row_count $column_count");
								if ( $this->config->item('all_records_require_an_id') ) { // If the setting is set to on then check whether there's a record ID present for each record
									$return_data['result_flag'] = 0;
									$return_data['error'] = "All records require a record ID, there are some records in the import data that do not have a record ID, please add record IDs to all records and re-try the import.";
									return $return_data;
								}
							}
							else {
								$insert_data[$current_header] = $cell_value;
							}
						}
						elseif ( $column ) { // Check to see there's a value present for this column
							$cell_value = (string) $column;
							$cell_value = trim($cell_value);
//							error_log("value -> $cell_value");
							if (preg_match('/phenotype:(.*)/i', $current_header, $phenotype_match)) {
//								error_log("phenotype -> " . print_r($phenotype_match, 1) . " -> $cell_value");
								$phenotype_attribute = preg_replace('/phenotype\:/', '', $phenotype_match[0]);
								$phenotype_value = $cell_value;
//								error_log("attribute -> $phenotype_attribute --- value -> $phenotype_value");
								// TODO: Add this into the EAV table as it's a phenotype
								$phenotype_data_array = $this->_parse_phenotype_data_eav($phenotype_attribute, $phenotype_value);
//								error_log(print_r($phenotype_data_array, 1));
								$phenotype_insert_data[] = $phenotype_data_array['phenotype_insert_data'];
								$primary_phenotype_lookup_data[] = $phenotype_data_array['primary_phenotype_lookup_data'];
								
							}
							elseif ( $current_header == "phenotype" ) { // If it's a phenotype field
//								error_log("STARTING $row_count -> $cell_value");
								$phenotype_data_array = $this->_parse_phenotype_data($cell_value);
								$phenotype_insert_data = $phenotype_data_array['phenotype_insert_data'];
								$primary_phenotype_lookup_data = $phenotype_data_array['primary_phenotype_lookup_data'];
							}
							else {
								$insert_data[$current_header] = $cell_value;
							}
						}
//						error_log("$row $row_count -> $column");
					}

					if ( ! empty($insert_data) ) {
						// Override the source with the source based on the import page (hopefully avoids errors of not inputting the right source)
						$insert_data['source'] = $source;
						$insert_data['laboratory'] = $source;
						if ( ! array_key_exists('sharing_policy', $insert_data) ) {
							$insert_data['sharing_policy'] = $sharing_policy;
						}
//						error_log(print_r($insert_data, 1));
						$insert_id = $this->sources_model->insertVariants($insert_data);
						
						// If there's phenotype data then insert it
						if ( ! empty ($phenotype_insert_data) ) {
//							error_log("INSERT -> " . print_r($phenotype_insert_data, 1));
							$phenotype_array = array();
							foreach ($phenotype_insert_data as $phenotype_data) {
								$phenotype_data['cafevariome_id'] = $insert_id; // Add the ID of the variant so that the phenotype can be linked to it
//								error_log("before -> " . print_r($phenotype_data, 1));
								$phenotype_insert_id = $this->sources_model->insertPhenotypes($phenotype_data);
//								error_log("$insert_id -> $phenotype_insert_id");
								// Get the term name and add it to the insert_data array that is indexed in ElasticSearch
								$phenotype_array['term_name'] = $phenotype_data['attribute_termName'];
								$insert_data['phenotypes'][] = $phenotype_array;

								
								
							}
							
							
						}
						
						// Check if there's anything to insert into primary_phenotype_lookup table
						if ( ! empty ($primary_phenotype_lookup_data) ) {
							foreach ( $primary_phenotype_lookup_data as $primary_phenotype_lookup ) { // Go through each insert row

								$pl = $this->sources_model->getPrimaryLookup($primary_phenotype_lookup['termId']); // Check if the termId is unique
								if (!$pl) { // If the termId doesn't exist in the table then insert it
									$lookup_data = array(
										"sourceId" => $primary_phenotype_lookup['sourceId'],
										"termId" => $primary_phenotype_lookup['termId'],
										"termName" => $primary_phenotype_lookup['termName']
									);
									$lookup_insert_id = $this->sources_model->insertPrimaryLookup($lookup_data);
								}
							}
						}
						
//						error_log("insert id -> " . $insert_id);
						if (!$insert_id) {
							$return_data['result_flag'] = 0;
							$return_data['error'] = "MySQL insert was unsuccessful";
						}
						if ( $this->config->item('use_elasticsearch') ) {
							$elastic_search_index_result = $this->_indexVariantElasticSearch($insert_id, $insert_data);
						}
					

						
//						$index_data = array();
//						$index_data = $insert_data;
//						$index_data['cafevariome_id'] = $insert_id;
//						$index_data = json_encode($index_data);
//						error_log("index -> $index_data");
//						if ( $this->config->item('use_elasticsearch') ) {
////							$check_if_running = $this->elasticsearch->check_if_running();
////							if ( array_key_exists( 'ok', $check_if_running) ) {
								// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
//								$es_index = $this->config->item('site_title');
//								$es_index = preg_replace('/\s+/', '', $es_index);
//								$es_index = strtolower($es_index);
//								$this->elasticsearch->set_index($es_index);
//								$this->elasticsearch->set_type("variants");
//								$index_result = $this->elasticsearch->add($insert_id, $index_data);
//								error_log("RESULT -> " . print_r($index_result, 1));
////							}
//						}
						
					}
				}
			}
			return $return_data;
		}
		
		else if ( $fileformat == 'phenotype_test' ) {
//			error_log("got epad");
			ini_set('memory_limit','1024M');
			$this->load->library('phpexcel/PHPExcel');
			
			$excel_type = PHPExcel_IOFactory::identify($file); // Identify the file type, check it's actually an Excel file, throw an error if not
			if (! preg_match("/Excel/i", $excel_type)) {
				$return_data['result_flag'] = 0;
				$return_data['error'] = "The input file doesn't seem to be in Excel format, please check the file and make sure you specify the correct file format.";
				return $return_data;
			}
			
			$objPHPExcel = PHPExcel_IOFactory::load($file); // Factory should auto guess the Excel type
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
//			error_log(print_r($sheetData, 1));
			$row_count = 0;
			$this->load->model('general_model');
			// Initialize core fields array which is populated later from import template headers
			$core_fields = array();

			foreach ( $sheetData as $row ) {
				$row_count++;
				error_log("row -> " . $row_count);
				if ( $row_count === 1 ) { // It's the first row of the sheet - validate the headers and store for later
					foreach ( $row as $column ) {
						if ( $column ) {
							$cell_value = (string) $column;
							$core_fields[] = $cell_value; // Store the header name
						}

					}
				}
				else { // All other lines are actual data
					$column_count = 0;
					$insert_data = array();
					$phenotype_insert_data = array();
					$primary_phenotype_lookup_data = array();
					$present_flags = array();
					foreach ( $row as $column ) {
						$column_count++;
						$current_header = (string) $core_fields[$column_count-1]; // Match this column to the correct header name (stored above)
//						error_log("header -> " . $current_header);
						$cell_value = (string) $column;
						$cell_value = trim($cell_value);

						// Ignore any empty cells - for some reason PHPExcel is parsing empty rows so it's possible that empty records could get insert - check this here to prevent that happening
//						$value = $cell_value == '' ? NULL : $cell_value;
						if ( $cell_value == '' ) {
							continue;
							// error_log("$value NULL for $current_header $row_count:$column_count");
						}
						
						$sourceId = "LocalList";
						$termName = $current_header;
//						error_log("cell -> $current_header -> $cell_value");
						
						// Match the generic phenotype with attribute then qualifier in square brackets
						if ( preg_match('/(.*?) \[(.*?)\]/i', $current_header, $match)) {
//							error_log("match ---> " . print_r($match, 1));
							$termName = $match[1];
							$qualifier = $match[2];
//							error_log("match -> " . $match . " termName -> " . $termName . " qualifier -> " . $qualifier);
							$sourceId = "LocalList";
									
							$full_termName = $termName . " [$qualifier]";
							$termId = "locallist/" . $termName . " [$qualifier]";
							$termId = strtolower($termId);
							$termId = str_replace(' ', '_', $termId);
							$type = 'quality';
//							$value = $cell_value;
							$value = $cell_value == '' ? NULL : $cell_value;
							$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $full_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
							$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $full_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

						}
						elseif ( $current_header == "individual_id" ) { // Add the individual_id to the core table insert data
							$insert_data[$current_header] = $cell_value;
						}
						else {
//							$insert_data[$current_header] = $cell_value;
							$phenotype_attribute = $current_header;
							$phenotype_value = $cell_value;
//							error_log("attribute -> $phenotype_attribute --- value -> $phenotype_value");
							// TODO: Add this into the EAV table as it's a phenotype
							$phenotype_data_array = $this->_parse_phenotype_data_eav($phenotype_attribute, $phenotype_value);
//							error_log(print_r($phenotype_data_array, 1));
							$phenotype_insert_data[] = $phenotype_data_array['phenotype_insert_data'];
							$primary_phenotype_lookup_data[] = $phenotype_data_array['primary_phenotype_lookup_data'];
						}
					}

					if ( ! empty($insert_data) ) {
//						error_log(print_r($insert_data, 1));
						// Override the source with the source based on the import page (hopefully avoids errors of not inputting the right source)
						$insert_data['source'] = $source;
						$insert_data['laboratory'] = $source;
						if ( ! array_key_exists('sharing_policy', $insert_data) ) {
							$insert_data['sharing_policy'] = $sharing_policy;
						}
//						error_log(print_r($insert_data, 1));
						$insert_id = $this->sources_model->insertVariants($insert_data);

						// If there's phenotype data then insert it
						if ( ! empty ($phenotype_insert_data) ) {
//							error_log("INSERT -> " . print_r($phenotype_insert_data, 1));
							$phenotype_array = array();
							foreach ($phenotype_insert_data as $phenotype_data) {
//								error_log("phenotype_data $insert_id -> " . print_r($phenotype_data, 1));
								$phenotype_data['cafevariome_id'] = $insert_id; // Add the ID of the variant so that the phenotype can be linked to it
								$phenotype_insert_id = $this->sources_model->insertPhenotypes($phenotype_data);
								// Get the term name and add it to the insert_data array that is indexed in ElasticSearch
//								$phenotype_array['term_name'] = $phenotype_data['attribute_termName'];
//								$insert_data['phenotypes'][] = $phenotype_array;

								
								
							}
							
							
						}
						
						// Check if there's anything to insert into primary_phenotype_lookup table
						if ( ! empty ($primary_phenotype_lookup_data) ) {
							foreach ( $primary_phenotype_lookup_data as $primary_phenotype_lookup ) { // Go through each insert row

								$pl = $this->sources_model->getPrimaryLookup($primary_phenotype_lookup['termId']); // Check if the termId is unique
								if (!$pl) { // If the termId doesn't exist in the table then insert it
									$lookup_data = array(
										"sourceId" => $primary_phenotype_lookup['sourceId'],
										"termId" => $primary_phenotype_lookup['termId'],
										"termName" => $primary_phenotype_lookup['termName'],
										"qualifier" => $primary_phenotype_lookup['qualifier']
									);
									$lookup_insert_id = $this->sources_model->insertPrimaryLookup($lookup_data);
								}
							}
						}
						
//						error_log("insert id -> " . $insert_id);
						if (!$insert_id) {
							$return_data['result_flag'] = 0;
							$return_data['error'] = "MySQL insert was unsuccessful";
						}
						if ( $this->config->item('use_elasticsearch') ) {
//							error_log("ID -> $insert_id -> " . print_r($insert_data, 1));
//							$elastic_search_index_result = $this->_indexVariantElasticSearch($insert_id, $insert_data);
							$this->load->library('elasticsearch');
							// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
							$es_index = $this->config->item('site_title');
							$es_index = preg_replace('/\s+/', '', $es_index);
							$es_index = strtolower($es_index);
							$this->elasticsearch->set_index($es_index);
							$this->elasticsearch->set_type("variants");
							$index_data = $this->sources_model->getVariantWithPhenotypeJSON($insert_id);
							$index_result = $this->elasticsearch->add($insert_id, $index_data);	
						}
					}
				}
			}
			return $return_data;
		}	
		
		else if ( $fileformat == 'epad' ) {
//			error_log("got epad");
			ini_set('memory_limit','1024M');
			$this->load->library('phpexcel/PHPExcel');
			
			$excel_type = PHPExcel_IOFactory::identify($file); // Identify the file type, check it's actually an Excel file, throw an error if not
			if (! preg_match("/Excel/i", $excel_type)) {
				$return_data['result_flag'] = 0;
				$return_data['error'] = "The input file doesn't seem to be in Excel format, please check the file and make sure you specify the correct file format.";
				return $return_data;
			}
			
			$objPHPExcel = PHPExcel_IOFactory::load($file); // Factory should auto guess the Excel type
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
//			error_log(print_r($sheetData, 1));
			$row_count = 0;
			$this->load->model('general_model');
			// Initialize core fields array which is populated later from import template headers
			$core_fields = array();

			foreach ( $sheetData as $row ) {
				$row_count++;
//				error_log("row -> " . $row_count);
				if ( $row_count === 1 ) { // It's the first row of the sheet - validate the headers and store for later
					foreach ( $row as $column ) {
						if ( $column ) {
							$cell_value = (string) $column;
							$core_fields[] = $cell_value; // Store the header name
						}

					}
				}
				else { // All other lines are actual data
					$column_count = 0;
					$insert_data = array();
					$phenotype_insert_data = array();
					$primary_phenotype_lookup_data = array();
					$present_flags = array();
					foreach ( $row as $column ) {
						$column_count++;
						$current_header = (string) $core_fields[$column_count-1]; // Match this column to the correct header name (stored above)
//						error_log("header -> " . $current_header);
						$cell_value = (string) $column;
						$cell_value = trim($cell_value);

						// Ignore any empty cells - for some reason PHPExcel is parsing empty rows so it's possible that empty records could get insert - check this here to prevent that happening
//						$value = $cell_value == '' ? NULL : $cell_value;
						if ( $cell_value == '' ) {
							continue;
							// error_log("$value NULL for $current_header $row_count:$column_count");
						}
						
						$sourceId = "LocalList";
						$termName = $current_header;
//						error_log("cell -> $current_header -> $cell_value");
						
						// Match the generic phenotype with attribute then qualifier in square brackets
						if ( preg_match('/(.*?) \[(.*?)\]/i', $current_header, $match)) {
//							error_log("match ---> " . print_r($match, 1));
							$termName = $match[1];
							$qualifier = $match[2];
//							error_log("match -> " . $match . " termName -> " . $termName . " qualifier -> " . $qualifier);
							$sourceId = "LocalList";
									
							$full_termName = $termName . " [$qualifier]";
							$termId = "locallist/" . $termName . " [$qualifier]";
							$termId = strtolower($termId);
							$termId = str_replace(' ', '_', $termId);
							$type = 'quality';
//							$value = $cell_value;
							$value = $cell_value == '' ? NULL : $cell_value;
							$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $full_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
							$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $full_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

						}
						elseif ( $current_header == "Subject_ID" ) { // Add the individual_id to the core table insert data
							$insert_data[$current_header] = $cell_value;
						}
						elseif ( $current_header == "Cohort_Prefix" ) { // Add the individual_id to the core table insert data
							$insert_data[$current_header] = $cell_value;
						}
						elseif ( $current_header == "Date_Harmonized" ) { // Add the individual_id to the core table insert data
							$insert_data[$current_header] = $cell_value;
						}
						else {
//							$insert_data[$current_header] = $cell_value;
							$phenotype_attribute = $current_header;
							$phenotype_value = $cell_value;
//							error_log("attribute -> $phenotype_attribute --- value -> $phenotype_value");
							// TODO: Add this into the EAV table as it's a phenotype
							$phenotype_data_array = $this->_parse_phenotype_data_eav($phenotype_attribute, $phenotype_value);
//							error_log(print_r($phenotype_data_array, 1));
							$phenotype_insert_data[] = $phenotype_data_array['phenotype_insert_data'];
							$primary_phenotype_lookup_data[] = $phenotype_data_array['primary_phenotype_lookup_data'];
						}
					}

					if ( ! empty($insert_data) ) {
//						error_log(print_r($insert_data, 1));
						// Override the source with the source based on the import page (hopefully avoids errors of not inputting the right source)
						$insert_data['source'] = $source;
						$insert_data['laboratory'] = $source;
						if ( ! array_key_exists('sharing_policy', $insert_data) ) {
							$insert_data['sharing_policy'] = $sharing_policy;
						}
//						error_log(print_r($insert_data, 1));
						$insert_id = $this->sources_model->insertVariants($insert_data);

						// If there's phenotype data then insert it
						if ( ! empty ($phenotype_insert_data) ) {
//							error_log("INSERT -> " . print_r($phenotype_insert_data, 1));
							$phenotype_array = array();
							foreach ($phenotype_insert_data as $phenotype_data) {
//								error_log("phenotype_data $insert_id -> " . print_r($phenotype_data, 1));
								$phenotype_data['cafevariome_id'] = $insert_id; // Add the ID of the variant so that the phenotype can be linked to it
								$phenotype_insert_id = $this->sources_model->insertPhenotypes($phenotype_data);
								// Get the term name and add it to the insert_data array that is indexed in ElasticSearch
//								$phenotype_array['term_name'] = $phenotype_data['attribute_termName'];
//								$insert_data['phenotypes'][] = $phenotype_array;

								
								
							}
							
							
						}
						
						// Check if there's anything to insert into primary_phenotype_lookup table
						if ( ! empty ($primary_phenotype_lookup_data) ) {
							foreach ( $primary_phenotype_lookup_data as $primary_phenotype_lookup ) { // Go through each insert row

								$pl = $this->sources_model->getPrimaryLookup($primary_phenotype_lookup['termId']); // Check if the termId is unique
								if (!$pl) { // If the termId doesn't exist in the table then insert it
									$lookup_data = array(
										"sourceId" => $primary_phenotype_lookup['sourceId'],
										"termId" => $primary_phenotype_lookup['termId'],
										"termName" => $primary_phenotype_lookup['termName'],
										"qualifier" => $primary_phenotype_lookup['qualifier']
									);
									$lookup_insert_id = $this->sources_model->insertPrimaryLookup($lookup_data);
								}
							}
						}
						
//						error_log("insert id -> " . $insert_id);
						if (!$insert_id) {
							$return_data['result_flag'] = 0;
							$return_data['error'] = "MySQL insert was unsuccessful";
						}
						if ( $this->config->item('use_elasticsearch') ) {
//							error_log("ID -> $insert_id -> " . print_r($insert_data, 1));
//							$elastic_search_index_result = $this->_indexVariantElasticSearch($insert_id, $insert_data);
							$this->load->library('elasticsearch');
							// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
							$es_index = $this->config->item('site_title');
							$es_index = preg_replace('/\s+/', '', $es_index);
							$es_index = strtolower($es_index);
							$this->elasticsearch->set_index($es_index);
							$this->elasticsearch->set_type("variants");
							$index_data = $this->sources_model->getVariantWithPhenotypeJSON($insert_id);
							$index_result = $this->elasticsearch->add($insert_id, $index_data);	
						}
					}
				}
			}
			return $return_data;
		}		
		else if ( $fileformat == 'epad_old' ) {
//			error_log("got epad");
			ini_set('memory_limit','1024M');
			$this->load->library('phpexcel/PHPExcel');
			
			$excel_type = PHPExcel_IOFactory::identify($file); // Identify the file type, check it's actually an Excel file, throw an error if not
			if (! preg_match("/Excel/i", $excel_type)) {
				$return_data['result_flag'] = 0;
				$return_data['error'] = "The input file doesn't seem to be in Excel format, please check the file and make sure you specify the correct file format.";
				return $return_data;
			}
			
			$objPHPExcel = PHPExcel_IOFactory::load($file); // Factory should auto guess the Excel type
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
//			error_log(print_r($sheetData, 1));
			$row_count = 0;
			$this->load->model('general_model');
			// Initialize core fields array which is populated later from import template headers
			$core_fields = array();
			// Get all the fields that are in the main feature table (used for checking whether all fields in the import template actually exist in the database
//			$variant_table_fields = $this->general_model->describeTable($this->config->item('feature_table_name'));
//			error_log(print_r($variant_table_fields,1));
//			$epad_fields = array('individual_id' => 'individual_id', 'TimeOfBirth' => 'month:year', 'gender' => '"M" or "F"', 'MMSE(number of tests)' => 'integer(0-100)', 'MMSE(most recent)' => 'integer(0-30)', 'APOE4' => 'integer(0-2)', 'MRI(number of tests)' => 'integer(0-100)', 'ADAS-COG(number of tests)' => 'integer(0-100)', 'ADAS-COG(most recent)' => 'integer(0-100)', 'AmyloidBetaTest(number of tests)' => 'integer(0-100)', 'AmyloidBetaTest(CSF, most recent)' => 'month:year', 'AmyloidPET(number of tests)' => 'integer(0-100)', 'AmyloidPET(most recent)' => 'month:year', 'MostRecentVisit(most recent)' => '[mm]:year' );
//			$epad_fields = array('gene'	=> 'gene', 'ref' => 'ref', 'hgvs' => 'hgvs', 'ethnicity' => 'ethnicity', 'location_ref' => 'location_ref', 'start' => 'start', 'end' => 'end', 'individual_id' => 'individual_id', 'source_url' => 'source_url', 'TimeOfBirth' => 'month:year', 'Gender' => '"M" or "F"', 'MMSE(number of tests)' => 'integer(0-100)', 'MMSE(most recent)' => 'integer(0-30)', 'APOE4' => 'integer(0-2)', 'MRI(number of tests)' => 'integer(0-100)', 'ADAS-COG(number of tests)' => 'integer(0-100)', 'ADAS-COG(most recent)' => 'integer(0-100)', 'AmyloidBetaPositive' => 'AmyloidBetaPositive', 'AmyloidBetaTest(CSF, most recent)' => 'month:year', 'AmyloidPET(number of tests)' => 'integer(0-100)', 'AmyloidPET(most recent)' => 'month:year', 'MostRecentVisit(most recent)' => '[mm]:year' );
			$epad_fields = array('individual_id' => 'individual_id', 'ethnicity' => 'ethnicity', 'source_url' => 'source_url', 'TimeOfBirth' => 'month/year', 'Gender' => '"M" or "F"', 'MMSE(number of tests)' => 'integer(0-100)', 'MMSE(most recent result)' => 'integer(0-30)', 'APOE4(most recent result)' => 'integer(0-2)', 'MRI(number of tests)' => 'integer(0-100)', 'ADAS-COG(number of tests)' => 'integer(0-100)', 'ADAS-COG(most recent result)' => 'integer(0-100)', 'AmyloidBetaPresence(number of tests)' => 'AmyloidBetaPresence(number of tests)', 'AmyloidBetaPresence(when last tested)' => 'AmyloidBetaPresence(when last tested)', 'AmyloidBetaPresence(most recent result)' => 'AmyloidBetaPresence(most recent result)', 'AmyloidPET(number of tests)' => 'AmyloidPET(number of tests)', 'AmyloidPET(when last tested)' => 'AmyloidPET(when last tested)', 'MostRecentVisit(when)' => 'MostRecentVisit(when)' );
																																																																																																																			
			foreach ( $sheetData as $row ) {
				$row_count++;
//				error_log("row -> " . $row_count);
				if ( $row_count === 1 ) { // It's the first row of the sheet - validate the headers and store for later
					foreach ( $row as $column ) {
						if ( $column ) {
							$cell_value = (string) $column;
							$core_fields[] = $cell_value; // Store the header name
							if ( ! array_key_exists($cell_value, $epad_fields)) { // Check whether this header is defined in the core fields table, return an error if not since there's a mismatch
//								error_log("HEADER CELL ->  $cell_value");
								$return_data['result_flag'] = 0;
								$return_data['error'] = "There are headers in your import template that do not match up to a field in the " . $this->config->item('feature_table_name') . " table. Import cannot proceed, email admin@cafevariome.org if you cannot resolve this problem.";
								return $return_data;
							}
						}

					}
				}
				else { // All other lines are actual data
					$column_count = 0;
					$insert_data = array();
					$phenotype_insert_data = array();
					$primary_phenotype_lookup_data = array();
					$present_flags = array();
					foreach ( $row as $column ) {
						$column_count++;
//						if ( $column ) {
							$current_header = (string) $core_fields[$column_count-1]; // Match this column to the correct header name (stored above)
//							error_log("header -> " . $current_header);
							$cell_value = (string) $column;
							$cell_value = trim($cell_value);

//							$value = $cell_value == '' ? NULL : $cell_value;
//							if ( $cell_value == '' ) {
//								error_log("$value NULL for $current_header $row_count:$column_count");
//							}
//							else {
//								error_log("$value present for $current_header $row_count:$column_count");
//							}
							$sourceId = "LocalList";
							$termName = $current_header;
//							error_log("cell -> $current_header -> $cell_value");
							if ( $current_header == "TimeOfBirth" ) {

								$sourceId = "LocalList";
								
								// Add age years phenotype
								$age_years = $this->getDateInterval($cell_value, 'epad', 'years');
//								error_log("age ($cell_value) years -> " . $age_years);
								$new_termName = "Age [years]";
								$termId = "locallist/" . "Age [years]";
								$termId = strtolower($termId);
								$termId = str_replace(' ', '_', $termId);
								$qualifier = 'years';
								$type = 'quality';
//								$value = $age_years;
								$value = $cell_value == '' ? NULL : $age_years;
								$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
								$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

								// Add age months phenotype
								$age_months = $this->getDateInterval($cell_value, 'epad', 'months');
//								error_log("age ($cell_value) months -> " . $age_months);
								$new_termName = "Age [months]";
								$termId = "locallist/" . "Age [months]";
								$termId = strtolower($termId);
								$termId = str_replace(' ', '_', $termId);
								$qualifier = 'months';
								$type = 'quality';
//								$value = $age_months;
								$value = $cell_value == '' ? NULL : $age_months;
								$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
								$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

								
								// Add age present phenotype
//								$new_termName = "Age [result exists]";
//								$termId = "locallist/" . 'Age [result exists]';
//								$termId = strtolower($termId);
//								$termId = str_replace(' ', '_', $termId);
//								$type = 'quality';
//								$qualifier = 'result exists';
////								$value = 'present';
//								$value = $cell_value == '' ? 'absent' : 'present';
//								$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
//								$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
								
							}
							elseif ( $current_header == "Gender" ) {

								$sourceId = "LocalList";
								// Add Gender phenotype
								$termId = "locallist/" . $termName;
								$termId = strtolower($termId);
								$termId = str_replace(' ', '_', $termId);
								$qualifier = '';
								$type = 'quality';
//								$value = $cell_value;
								$value = $cell_value == '' ? NULL : $cell_value;
								$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
								$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $termName, 'termDefinition' => '', 'qualifier' => $qualifier);

								// Also insert into variants table
								if ( $cell_value == 'M' ) {
									$insert_data[$current_header] = "Male";
//									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $termName, 'attribute_qualifier' => $qualifier, 'value' => 'Male', 'type' => $type);

								}
								elseif ( $cell_value == 'F' ) {
									$insert_data[$current_header] = "Female";
//									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $termName, 'attribute_qualifier' => $qualifier, 'value' => 'Female', 'type' => $type);

								}
								
								// Add Gender present phenotype
//								$new_termName = $termName . " [result exists]";
//								$termId = "locallist/" . $termName . ' [result exists]';
//								$termId = strtolower($termId);
//								$termId = str_replace(' ', '_', $termId);
//								$type = 'quality';
//								$qualifier = 'result exists';
////								$value = 'present';
//								$value = $cell_value == '' ? 'absent' : 'present';
//								$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
//								$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
							}	
							elseif ( $current_header == "MMSE(number of tests)" ) {
								if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
									$match = $match[0];
									$match = str_replace(array( '(', ')' ), '', $match);
									$termName = str_replace(array( '(', ')' ), '', $termName);
									$termName = str_replace($match, '', $termName);
//									error_log("match -> " . $match . " termName -> " . $termName);
									$sourceId = "LocalList";
									
									//Add count phenotype
									$new_termName = $termName . " [count of results]";
									$termId = "locallist/" . $termName . " [count of results]";
									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$type = 'quality';
									$qualifier = 'count of results';
//									$value = $cell_value;
									$value = $cell_value == '' ? NULL : $cell_value;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

									// Add results exist phenotype
									if ( ! $present_flags[$termName]) {
										$new_termName = $termName . " [results exist]";
										$termId = "locallist/" . $termName . " [results exist]";
										$termId = strtolower($termId);
										$termId = str_replace(' ', '_', $termId);
										$type = 'quality';
										$qualifier = 'results exist';
//										$value = 'present';
//										$value = $cell_value == '' ? 'absent' : 'present';
										$value = $cell_value == '' ? NULL : 'Y';
										$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
										$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
										$present_flags[$termName] = 1;
									}
								}

							}
							elseif ( $current_header == "MMSE(most recent result)" ) {
								if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
									$match = $match[0];
									$match = str_replace(array( '(', ')' ), '', $match);
									$termName = str_replace(array( '(', ')' ), '', $termName);
									$termName = str_replace($match, '', $termName);
//									error_log("match -> " . $match . " termName -> " . $termName);
									$sourceId = "LocalList";
									
									//Add count phenotype
									$new_termName = $termName . " [most recent result]";
									$termId = "locallist/" . $termName . " [most recent result]";
									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$type = 'quality';
									$qualifier = 'most recent result';
//									$value = $cell_value;
									$value = $cell_value == '' ? NULL : $cell_value;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

									// Add present phenotype
									if ( ! $present_flags[$termName]) {
										$new_termName = $termName . " [results exist]";
										$termId = "locallist/" . $termName . " [results exist]";
										$termId = strtolower($termId);
										$termId = str_replace(' ', '_', $termId);
										$type = 'quality';
										$qualifier = 'results exist';
//										$value = 'present';
//										$value = $cell_value == '' ? 'absent' : 'present';
										$value = $cell_value == '' ? NULL : 'Y';
										$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
										$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
										$present_flags[$termName] = 1;
									}
								}
							}
							elseif ( $current_header == "APOE4(most recent result)" ) {
								if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
									$match = $match[0];
									$match = str_replace(array( '(', ')' ), '', $match);
									$termName = str_replace(array( '(', ')' ), '', $termName);
									$termName = str_replace($match, '', $termName);
//									error_log("match -> " . $match . " termName -> " . $termName);
									$sourceId = "LocalList";
									// Add APOE4 phenotype
									$new_termName = $termName . " [most recent result]";
									$termId = "locallist/" . $termName . " [most recent result]";

									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$qualifier = 'most recent result';
									$type = 'quality';
//									$value = $cell_value;
									$value = $cell_value == '' ? NULL : $cell_value;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

									// Add APOE present phenotype
//									$new_termName = $termName . " [result exists]";
//									$termId = "locallist/" . $termName . ' [result exists]';
//									$termId = strtolower($termId);
//									$termId = str_replace(' ', '_', $termId);
//									$type = 'quality';
//									$qualifier = 'result exists';
////									$value = 'present';
//									$value = $cell_value == '' ? 'absent' : 'present';
//									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
//									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
								}
							}
							elseif ( $current_header == "MRI(number of tests)" ) {
								if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
									$match = $match[0];
									$match = str_replace(array( '(', ')' ), '', $match);
									$termName = str_replace(array( '(', ')' ), '', $termName);
									$termName = str_replace($match, '', $termName);
//									error_log("match -> " . $match . " termName -> " . $termName);
									$sourceId = "LocalList";
									
									if ( preg_match('/(\d+)X/i', $cell_value, $match) ) { // Special type of field value to indicate it's present (but no count given)
										// Add present phenotype
//										error_log(print_r($match, 1));
//										if ( $match[1] >= 1 ) {
											if ( ! $present_flags[$termName]) {
												$new_termName = $termName . " [results exist]";
												$termId = "locallist/" . $termName . " [results exist]";
												$termId = strtolower($termId);
												$termId = str_replace(' ', '_', $termId);
												$type = 'quality';
												$qualifier = 'results exist';
//												$value = 'present';
												
												$value = $cell_value == 1 ? 'Y' : 'N';
//												$value = 'Y';
												$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
												$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
												$present_flags[$termName] = 1;
											}
//										}
									}
									else {

										//Add count phenotype
										$new_termName = $termName . " [count of results]";
										$termId = "locallist/" . $termName . " [count of results]";
										$termId = strtolower($termId);
										$termId = str_replace(' ', '_', $termId);
										$type = 'quality';
										$qualifier = 'count of results';
//										$value = $cell_value;
										$value = $cell_value == '' ? NULL : $cell_value;
										$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
										$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

										// Add present phenotype
										if ( ! $present_flags[$termName]) {
											$new_termName = $termName . " [results exist]";
											$termId = "locallist/" . $termName . " [results exist]";
											$termId = strtolower($termId);
											$termId = str_replace(' ', '_', $termId);
											$type = 'quality';
											$qualifier = 'results exist';
//											$value = 'present';
//											$value = $cell_value == '' ? 'absent' : 'present';
											$value = $cell_value == '' ? NULL : 'Y';
											$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
											$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
											$present_flags[$termName] = 1;
										}
									}
								}
							}
							elseif ( $current_header == "ADAS-COG(number of tests)" ) {
								if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
									$match = $match[0];
									$match = str_replace(array( '(', ')' ), '', $match);
									$termName = str_replace(array( '(', ')' ), '', $termName);
									$termName = str_replace($match, '', $termName);
//									error_log("match -> " . $match . " termName -> " . $termName);
									$sourceId = "LocalList";
									
									//Add count phenotype
									$new_termName = $termName . " [count of results]";
									$termId = "locallist/" . $termName . " [count of results]";
									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$type = 'quality';
									$qualifier = 'count of results';
//									$value = $cell_value;
									$value = $cell_value == '' ? NULL : $cell_value;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

									// Add present phenotype
									if ( ! $present_flags[$termName]) {
										$new_termName = $termName . " [results exist]";
										$termId = "locallist/" . $termName . " [results exist]";
										$termId = strtolower($termId);
										$termId = str_replace(' ', '_', $termId);
										$type = 'quality';
										$qualifier = 'results exist';
//										$value = 'present';
//										$value = $cell_value == '' ? 'absent' : 'present';
										$value = $cell_value == '' ? NULL : 'Y';
										$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
										$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
										$present_flags[$termName] = 1;
									}
									
								}
							}
							elseif ( $current_header == "ADAS-COG(most recent result)" ) {
								if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
									$match = $match[0];
									$match = str_replace(array( '(', ')' ), '', $match);
									$termName = str_replace(array( '(', ')' ), '', $termName);
									$termName = str_replace($match, '', $termName);
//									error_log("match -> " . $match . " termName -> " . $termName);
									$sourceId = "LocalList";
									
									//Add count phenotype
									$new_termName = $termName . " [most recent result]";
									$termId = "locallist/" . $termName . " [most recent result]";
									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$type = 'quality';
									$qualifier = 'most recent result';
//									$value = $cell_value;
									$value = $cell_value == '' ? NULL : $cell_value;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

									// Add present phenotype
									if ( ! $present_flags[$termName]) {
										$new_termName = $termName . " [results exist]";
										$termId = "locallist/" . $termName . " [results exist]";
										$termId = strtolower($termId);
										$termId = str_replace(' ', '_', $termId);
										$type = 'quality';
										$qualifier = 'results exist';
//										$value = 'present';
//										$value = $cell_value == '' ? 'absent' : 'present';
										$value = $cell_value == '' ? NULL : 'Y';
										$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
										$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
										$present_flags[$termName] = 1;
									}
									
								}
							}
							elseif ( $current_header == "AmyloidBetaPresence(number of tests)" ) {
								if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
									$match = $match[0];
									$match = str_replace(array( '(', ')' ), '', $match);
									$termName = str_replace(array( '(', ')' ), '', $termName);
									$termName = str_replace($match, '', $termName);
//									error_log("match -> " . $match . " termName -> " . $termName);
									$sourceId = "LocalList";
									
									//Add count phenotype
									$new_termName = $termName . " [count of results]";
									$termId = "locallist/" . $termName . " [count of results]";
									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$type = 'quality';
									$qualifier = 'count of results';
//									$value = $cell_value;
									$value = $cell_value == '' ? NULL : $cell_value;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

									// Add present phenotype
									if ( ! $present_flags[$termName]) {
										$new_termName = $termName . " [results exist]";
										$termId = "locallist/" . $termName . " [results exist]";
										$termId = strtolower($termId);
										$termId = str_replace(' ', '_', $termId);
										$type = 'quality';
										$qualifier = 'results exist';
//										$value = 'present';
//										$value = $cell_value == '' ? 'absent' : 'present';
										$value = $cell_value == '' ? NULL : 'Y';
										$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
										$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
										$present_flags[$termName] = 1;
									}
									
								}
							}

							elseif ( $current_header == "AmyloidBetaPresence(when last tested)" ) {
								
								if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
									$match = $match[0];
									$match = str_replace(array( '(', ')' ), '', $match);
									$termName = str_replace(array( '(', ')' ), '', $termName);
									$termName = str_replace($match, '', $termName);
//									error_log("match -> " . $match . " termName -> " . $termName);
									$sourceId = "LocalList";

									//Add MostRecentAmyloidBetaTest [years ago last tested] phenotype
									$most_recent_years = $this->getDateInterval($cell_value, 'epad', 'years');
									$termId = "locallist/" . "AmyloidBetaPresence [years ago last tested]";
									$new_termName = "AmyloidBetaPresence [years ago last tested]";
									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$type = 'quality';
									$qualifier = 'years ago last tested';
									$value = $most_recent_years;
									$value = $cell_value == '' ? NULL : $most_recent_years;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

									//Add MostRecentAmyloidBetaTest [months] phenotype
									$most_recent_months = $this->getDateInterval($cell_value, 'epad', 'months');
									$termId = "locallist/" . "AmyloidBetaPresence [months ago last tested]";
									$new_termName = "AmyloidBetaPresence [months ago last tested]";
									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$type = 'quality';
									$qualifier = 'months ago last tested';
//									$value = $most_recent_months;
									$value = $cell_value == '' ? NULL : $most_recent_months;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

								}
							}
							
							elseif ( $current_header == "AmyloidBetaPresence(most recent result)" ) {
								if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
									$match = $match[0];
									$match = str_replace(array( '(', ')' ), '', $match);
									$termName = str_replace(array( '(', ')' ), '', $termName);
									$termName = str_replace($match, '', $termName);
//									error_log("match -> " . $match . " termName -> " . $termName);
									$sourceId = "LocalList";
									
									//Add count phenotype
									$new_termName = $termName . " [most recent result]";
									$termId = "locallist/" . $termName . " [most recent result]";
									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$type = 'definitive';
									$qualifier = 'most recent result';
//									$value = $cell_value;
									$value = $cell_value == '' ? NULL : $cell_value;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

									// Add present phenotype
									if ( ! $present_flags[$termName]) {
										$new_termName = $termName . " [results exist]";
										$termId = "locallist/" . $termName . " [results exist]";
										$termId = strtolower($termId);
										$termId = str_replace(' ', '_', $termId);
										$type = 'quality';
										$qualifier = 'results exist';
//										$value = 'present';
//										$value = $cell_value == '' ? 'absent' : 'present';
										$value = $cell_value == '' ? NULL : 'Y';
										$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
										$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
										$present_flags[$termName] = 1;
									}
									
								}

								
							}
							
							elseif ( $current_header == "AmyloidPET(number of tests)" ) {
								if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
									$match = $match[0];
									$match = str_replace(array( '(', ')' ), '', $match);
									$termName = str_replace(array( '(', ')' ), '', $termName);
									$termName = str_replace($match, '', $termName);
//									error_log("match -> " . $match . " termName -> " . $termName);
									$sourceId = "LocalList";

									if ( preg_match('/(\d+)X/i', $cell_value, $match) ) { // Special type of field value to indicate it's present (but no count given)
										// Add present phenotype
										error_log(print_r($match, 1));
//										if ( $match[1] >= 1 ) {
											if ( ! $present_flags[$termName]) {
												$new_termName = $termName . " [results exist]";
												$termId = "locallist/" . $termName . " [results exist]";
												$termId = strtolower($termId);
												$termId = str_replace(' ', '_', $termId);
												$type = 'quality';
												$qualifier = 'results exist';
//												$value = 'present';
//												$value = $cell_value == '' ? 'absent' : 'present';
												$value = $cell_value == 1 ? 'Y' : 'N';
//												$value = 'Y';
												$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
												$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
												$present_flags[$termName] = 1;
											}
//										}
									}
									else {
										//Add count phenotype
										$new_termName = $termName . " [count of results]";
										$termId = "locallist/" . $termName . " [count of results]";
										$termId = strtolower($termId);
										$termId = str_replace(' ', '_', $termId);
										$type = 'quality';
										$qualifier = 'count of results';
//										$value = $cell_value;
										$value = $cell_value == '' ? NULL : $cell_value;
										$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
//										error_log(print_r(array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type),1));
										$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $termName, 'termDefinition' => '', 'qualifier' => $qualifier);

										// Add present phenotype
										if ( ! $present_flags[$termName]) {
											$new_termName = $termName . " [results exist]";
											$termId = "locallist/" . $termName . " [results exist]";
											$termId = strtolower($termId);
											$termId = str_replace(' ', '_', $termId);
											$type = 'quality';
											$qualifier = 'results exist';
//											$value = 'present';
//											$value = $cell_value == '' ? 'absent' : 'present';
											$value = $cell_value == '' ? NULL : 'Y';
											$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
											$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
											$present_flags[$termName] = 1;
										}
									}
									
								}
							}
							elseif ( $current_header == "AmyloidPET(when last tested)" ) {

								if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
									$match = $match[0];
									$match = str_replace(array( '(', ')' ), '', $match);
									$termName = str_replace(array( '(', ')' ), '', $termName);
									$termName = str_replace($match, '', $termName);
//									error_log("match -> " . $match . " termName -> " . $termName);
									$sourceId = "LocalList";
									
									//Add AmyloidPET [years ago last tested] phenotype
									$most_recent_years = $this->getDateInterval($cell_value, 'epad', 'years');
									$new_termName = "AmyloidPET [years ago last tested]";
									$termId = "locallist/" . "AmyloidPET [years ago last tested]";
									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$type = 'quality';
									$qualifier = 'years ago last tested';
//									$value = $most_recent_years;
									$value = $cell_value == '' ? NULL : $most_recent_years;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

									//Add AmyloidPET [months ago last tested] phenotype
									$most_recent_months = $this->getDateInterval($cell_value, 'epad', 'months');
									$new_termName = "AmyloidPET [months ago last tested]";
									$termId = "locallist/" . "AmyloidPET [months ago last tested]";
									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$type = 'quality';
									$qualifier = 'months ago last tested';
//									$value = $most_recent_months;
									$value = $cell_value == '' ? NULL : $most_recent_months;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

									
									// Add present phenotype
//									if ( ! $present_flags[$termName]) {
//										$new_termName = $termName . " [result exists]";
//										$termId = "locallist/" . $termName . " [result exists]";
//										$termId = strtolower($termId);
//										$termId = str_replace(' ', '_', $termId);
//										$type = 'quality';
//										$qualifier = 'result exists';
////										$value = 'present';
////										$value = $cell_value == '' ? 'absent' : 'present';
//										$value = $cell_value == '' ? NULL : 'Y';
//										$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
//										$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
//										$present_flags[$termName] = 1;
//									}
									
								}
								
								
							}
							elseif ( $current_header == "MostRecentVisit(when)" ) {

								if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
									$match = $match[0];
									$match = str_replace(array( '(', ')' ), '', $match);
									$termName = str_replace(array( '(', ')' ), '', $termName);
									$termName = str_replace($match, '', $termName);
//									error_log("match -> " . $match . " termName -> " . $termName);
									$sourceId = "LocalList";
									
									//Add MostRecentVisit [years ago] phenotype
									$most_recent_years = $this->getDateInterval($cell_value, 'epad', 'years');
//									error_log("MostRecentAmyloidPET years -> $cell_value -> $most_recent_years");
									$new_termName = $termName . " [years ago]";
									$termId = "locallist/" . $termName . " [years ago]";
									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$type = 'quality';
									$qualifier = 'years ago';
//									$value = $most_recent_years;
									$value = $cell_value == '' ? NULL : $most_recent_years;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

									//Add MostRecentAmyloidPET [months ago] phenotype
									$most_recent_months = $this->getDateInterval($cell_value, 'epad', 'months');
//									error_log("MostRecentAmyloidPET months -> $cell_value -> $most_recent_months");
									$new_termName = $termName . " [months ago]";
									$termId = "locallist/" . $termName . " [months ago]";
									$termId = strtolower($termId);
									$termId = str_replace(' ', '_', $termId);
									$type = 'quality';
									$qualifier = 'months ago';
//									$value = $most_recent_months;
									$value = $cell_value == '' ? NULL : $most_recent_months;
									$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
									$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);

									// Add present phenotype
//									if ( ! $present_flags[$termName]) {
//										$new_termName = $termName . " [result exists]";
//										$termId = "locallist/" . $termName . " [result exists]";
//										$termId = strtolower($termId);
//										$termId = str_replace(' ', '_', $termId);
//										$type = 'quality';
//										$qualifier = 'result exists';
////										$value = 'present';
//										$value = $cell_value == '' ? 'absent' : 'present';
//										$value = $cell_value == '' ? NULL : 'Y';
//										$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $new_termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
//										$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $new_termName, 'termDefinition' => '', 'qualifier' => $qualifier);
//										$present_flags[$termName] = 1;
//									}
									
								}
					
							}
							else {
								$insert_data[$current_header] = $cell_value;
							}
//						}
//						else { // Cell is empty so need to treat as NULL value
//							$current_header = (string) $core_fields[$column_count-1]; // Match this column to the correct header name (stored above)
////							error_log("header -> " . $current_header);
//							$cell_value = (string) $column;
//							$cell_value = trim($cell_value);
//
////							$termName = $current_header;
//						
//							error_log("$current_header -> $row -> $row_count -> $column -> $cell_value");
//						}
					}

					if ( ! empty($insert_data) ) {
//						error_log(print_r($insert_data, 1));
						// Override the source with the source based on the import page (hopefully avoids errors of not inputting the right source)
						$insert_data['source'] = $source;
						$insert_data['laboratory'] = $source;
						if ( ! array_key_exists('sharing_policy', $insert_data) ) {
							$insert_data['sharing_policy'] = $sharing_policy;
						}
//						error_log(print_r($insert_data, 1));
						$insert_id = $this->sources_model->insertVariants($insert_data);
						
						// If there's phenotype data then insert it
						if ( ! empty ($phenotype_insert_data) ) {
//							error_log("INSERT -> " . print_r($phenotype_insert_data, 1));
							$phenotype_array = array();
							foreach ($phenotype_insert_data as $phenotype_data) {
//								error_log("phenotype_data $insert_id -> " . print_r($phenotype_data, 1));
								$phenotype_data['cafevariome_id'] = $insert_id; // Add the ID of the variant so that the phenotype can be linked to it
								$phenotype_insert_id = $this->sources_model->insertPhenotypes($phenotype_data);
								// Get the term name and add it to the insert_data array that is indexed in ElasticSearch
//								$phenotype_array['term_name'] = $phenotype_data['attribute_termName'];
//								$insert_data['phenotypes'][] = $phenotype_array;

								
								
							}
							
							
						}
						
						// Check if there's anything to insert into primary_phenotype_lookup table
						if ( ! empty ($primary_phenotype_lookup_data) ) {
							foreach ( $primary_phenotype_lookup_data as $primary_phenotype_lookup ) { // Go through each insert row

								$pl = $this->sources_model->getPrimaryLookup($primary_phenotype_lookup['termId']); // Check if the termId is unique
								if (!$pl) { // If the termId doesn't exist in the table then insert it
									$lookup_data = array(
										"sourceId" => $primary_phenotype_lookup['sourceId'],
										"termId" => $primary_phenotype_lookup['termId'],
										"termName" => $primary_phenotype_lookup['termName'],
										"qualifier" => $primary_phenotype_lookup['qualifier']
									);
									$lookup_insert_id = $this->sources_model->insertPrimaryLookup($lookup_data);
								}
							}
						}
						
//						error_log("insert id -> " . $insert_id);
						if (!$insert_id) {
							$return_data['result_flag'] = 0;
							$return_data['error'] = "MySQL insert was unsuccessful";
						}
						if ( $this->config->item('use_elasticsearch') ) {
//							error_log("ID -> $insert_id -> " . print_r($insert_data, 1));
//							$elastic_search_index_result = $this->_indexVariantElasticSearch($insert_id, $insert_data);
							$this->load->library('elasticsearch');
							// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
							$es_index = $this->config->item('site_title');
							$es_index = preg_replace('/\s+/', '', $es_index);
							$es_index = strtolower($es_index);
							$this->elasticsearch->set_index($es_index);
							$this->elasticsearch->set_type("variants");
							$index_data = $this->sources_model->getVariantWithPhenotypeJSON($insert_id);
							$index_result = $this->elasticsearch->add($insert_id, $index_data);	
						}
					}
				}
			}
			return $return_data;
		}
		else if ( $fileformat == 'epad_new' ) {
//			error_log("got epad");
			ini_set('memory_limit','1024M');
			$this->load->library('phpexcel/PHPExcel');
			
			$excel_type = PHPExcel_IOFactory::identify($file); // Identify the file type, check it's actually an Excel file, throw an error if not
			if (! preg_match("/Excel/i", $excel_type)) {
				$return_data['result_flag'] = 0;
				$return_data['error'] = "The input file doesn't seem to be in Excel format, please check the file and make sure you specify the correct file format.";
				return $return_data;
			}
			
			$objPHPExcel = PHPExcel_IOFactory::load($file); // Factory should auto guess the Excel type
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
//			error_log(print_r($sheetData, 1));
			$row_count = 0;
			$this->load->model('general_model');
			// Initialize core fields array which is populated later from import template headers
			$core_fields = array();
			// Get all the fields that are in the main feature table (used for checking whether all fields in the import template actually exist in the database
//			$variant_table_fields = $this->general_model->describeTable($this->config->item('feature_table_name'));
//			error_log(print_r($variant_table_fields,1));
//			$epad_fields = array('individual_id' => 'individual_id', 'TimeOfBirth' => 'month:year', 'gender' => '"M" or "F"', 'MMSE(number of tests)' => 'integer(0-100)', 'MMSE(most recent)' => 'integer(0-30)', 'APOE4' => 'integer(0-2)', 'MRI(number of tests)' => 'integer(0-100)', 'ADAS-COG(number of tests)' => 'integer(0-100)', 'ADAS-COG(most recent)' => 'integer(0-100)', 'AmyloidBetaTest(number of tests)' => 'integer(0-100)', 'AmyloidBetaTest(CSF, most recent)' => 'month:year', 'AmyloidPET(number of tests)' => 'integer(0-100)', 'AmyloidPET(most recent)' => 'month:year', 'MostRecentVisit(most recent)' => '[mm]:year' );
			$epad_fields = array('gene'	=> 'gene', 'ref' => 'ref', 'hgvs' => 'hgvs', 'ethnicity' => 'ethnicity', 'location_ref' => 'location_ref', 'start' => 'start', 'end' => 'end', 'individual_id' => 'individual_id', 'source_url' => 'source_url', 'TimeOfBirth' => 'month:year', 'Gender' => '"M" or "F"', 'MMSE(number of tests)' => 'integer(0-100)', 'MMSE(most recent)' => 'integer(0-30)', 'APOE4' => 'integer(0-2)', 'MRI(number of tests)' => 'integer(0-100)', 'ADAS-COG(number of tests)' => 'integer(0-100)', 'ADAS-COG(most recent)' => 'integer(0-100)', 'AmyloidBetaPositive' => 'AmyloidBetaPositive', 'AmyloidBetaTest(CSF, most recent)' => 'month:year', 'AmyloidPET(number of tests)' => 'integer(0-100)', 'AmyloidPET(most recent)' => 'month:year', 'MostRecentVisit(most recent)' => '[mm]:year' );
			
			foreach ( $sheetData as $row ) {
				$row_count++;
//				error_log("row -> " . $row_count);
				if ( $row_count === 1 ) { // It's the first row of the sheet - validate the headers and store for later
					foreach ( $row as $column ) {
						if ( $column ) {
							$cell_value = (string) $column;
							$core_fields[] = $cell_value; // Store the header name
//							if ( ! array_key_exists($cell_value, $epad_fields)) { // Check whether this header is defined in the core fields table, return an error if not since there's a mismatch
//								error_log("HEADER CELL ->  $cell_value");
//								$return_data['result_flag'] = 0;
//								$return_data['error'] = "There are headers in your import template that do not match up to a field in the " . $this->config->item('feature_table_name') . " table. Import cannot proceed, email admin@cafevariome.org if you cannot resolve this problem.";
//								return $return_data;
//							}
						}

					}
				}
				else { // All other lines are actual data
					$column_count = 0;
					$insert_data = array();
					$phenotype_insert_data = array();
					$primary_phenotype_lookup_data = array();
					$present_flags = array();
					foreach ( $row as $column ) {
						$column_count++;

						$current_header = (string) $core_fields[$column_count-1]; // Match this column to the correct header name (stored above)
//						error_log("header -> " . $current_header);
						$cell_value = (string) $column;
						$cell_value = trim($cell_value);
						if ( $current_header == "regnr_fu" ) {
							$insert_data['individual_id'] = $cell_value;
						}
						else {
							$termName = $current_header;
//							error_log("cell -> $current_header -> $cell_value");
							$sourceId = "LocalList";
							// Add phenotype
							$termId = "locallist/" . $termName;
							$termId = strtolower($termId);
							$termId = str_replace(' ', '_', $termId);
							$qualifier = '';
							$type = 'quality';
//							$value = $cell_value;
							$value = $cell_value == '' ? NULL : $cell_value;
							$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
							$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $termName, 'termDefinition' => '', 'qualifier' => $qualifier);
						}
					
					}
					$insert_data['source_url'] = "http://epad.cafevariome.org/discover/epad_example_record_link";
					if ( ! empty($insert_data) ) {
//						error_log(print_r($insert_data, 1));
						// Override the source with the source based on the import page (hopefully avoids errors of not inputting the right source)
						$insert_data['source'] = $source;
						$insert_data['laboratory'] = $source;
						if ( ! array_key_exists('sharing_policy', $insert_data) ) {
							$insert_data['sharing_policy'] = $sharing_policy;
						}
//						error_log(print_r($insert_data, 1));
						$insert_id = $this->sources_model->insertVariants($insert_data);
						
						// If there's phenotype data then insert it
						if ( ! empty ($phenotype_insert_data) ) {
//							error_log("INSERT -> " . print_r($phenotype_insert_data, 1));
							$phenotype_array = array();
							foreach ($phenotype_insert_data as $phenotype_data) {
//								error_log("phenotype_data $insert_id -> " . print_r($phenotype_data, 1));
								$phenotype_data['cafevariome_id'] = $insert_id; // Add the ID of the variant so that the phenotype can be linked to it
								$phenotype_insert_id = $this->sources_model->insertPhenotypes($phenotype_data);
								// Get the term name and add it to the insert_data array that is indexed in ElasticSearch
//								$phenotype_array['term_name'] = $phenotype_data['attribute_termName'];
//								$insert_data['phenotypes'][] = $phenotype_array;

								
								
							}
							
							
						}
						
						// Check if there's anything to insert into primary_phenotype_lookup table
						if ( ! empty ($primary_phenotype_lookup_data) ) {
							foreach ( $primary_phenotype_lookup_data as $primary_phenotype_lookup ) { // Go through each insert row

								$pl = $this->sources_model->getPrimaryLookup($primary_phenotype_lookup['termId']); // Check if the termId is unique
								if (!$pl) { // If the termId doesn't exist in the table then insert it
									$lookup_data = array(
										"sourceId" => $primary_phenotype_lookup['sourceId'],
										"termId" => $primary_phenotype_lookup['termId'],
										"termName" => $primary_phenotype_lookup['termName'],
										"qualifier" => $primary_phenotype_lookup['qualifier']
									);
									$lookup_insert_id = $this->sources_model->insertPrimaryLookup($lookup_data);
								}
							}
						}
						
//						error_log("insert id -> " . $insert_id);
						if (!$insert_id) {
							$return_data['result_flag'] = 0;
							$return_data['error'] = "MySQL insert was unsuccessful";
						}
						if ( $this->config->item('use_elasticsearch') ) {
//							error_log("ID -> $insert_id -> " . print_r($insert_data, 1));
//							$elastic_search_index_result = $this->_indexVariantElasticSearch($insert_id, $insert_data);
							$this->load->library('elasticsearch');
							// Create dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
							$es_index = $this->config->item('site_title');
							$es_index = preg_replace('/\s+/', '', $es_index);
							$es_index = strtolower($es_index);
							$this->elasticsearch->set_index($es_index);
							$this->elasticsearch->set_type("variants");

							$index_data = $this->sources_model->getVariantWithPhenotypeJSON($insert_id);
							$index_result = $this->elasticsearch->add($insert_id, $index_data);
							
						}
						
					}
				}
			}
			return $return_data;
		}
		elseif ( $fileformat == 'dmudb' ) {
			
			// Check it's a text file, if it's not then throw error and report to the user
//			$mime_type = mime_content_type($file);
//			error_log("mime type -> " . $mime_type);
//			if (! preg_match("/text/i", $mime_type)) {
//				$return_data['result_flag'] = 0;
//				$return_data['error'] = "The input file doesn't seem to be a text file, please check the file and make sure you specify the correct file format.";
//				return $return_data;
//			}
			
			$escaped_file = escapeshellcmd($file); // Escape any brackets etc in the file and count number of lines
			$number_lines = exec("wc -l < $escaped_file");
			$number_lines = preg_replace( '/\s+/', '', $number_lines );
//			error_log("num -> $number_lines");
			if ($number_lines < $this->config->item('max_variants')) {
				$handle = fopen($file, "r");
				$c = 0;
				$mutalyzer_flag = false;
				$failed_mutalyzer_line_numbers = array();

				//loop through the csv file and insert into database
				while (($data = fgetcsv($handle, 0, "\t")) !== FALSE) {
					$c++;
					$column_count = 0;
					$mutalyzer_check_result = "0";
					$insert_data = array();
					foreach ( $data as $column ) {
						$column_count++;
						if ( $column ) { // Only store if there's data for this column
							if ( $column_count == 1 ) {
								$chr = $column;
								$chr = ltrim($chr, '0');
								$chr = "chr" . $chr;
								$insert_data['location_ref'] = $chr;
							}
							elseif ( $column_count == 2 ) {
								$insert_data['start'] = $column;
							}
							elseif ( $column_count == 3 ) {
								$insert_data['end'] = $column;
							}
							elseif ( $column_count == 4 ) {
								$insert_data['gene'] = $column;
							}
							elseif ( $column_count == 5 ) {
								$insert_data['ref'] = $column;
							}
							elseif ( $column_count == 6 ) {
								$insert_data['hgvs'] = $column;
							}
							elseif ( $column_count == 7 ) {
								$insert_data['protein_hgvs'] = $column;
							}
							elseif ( $column_count == 8 ) {
								$insert_data['source_url'] = $column;
							}
							
//							elseif ( $column_count == 2 ) {
//								preg_match('/(\S+)\:(\S+)/', $column, $matches);
//								$ref = $matches[1];
//								$hgvs = $matches[2];
//								$insert_data['ref'] = $ref;
//								$insert_data['hgvs'] = $hgvs;
//								if ( $mutalyzer_check == "yes" ) {
//									if ( $client ) {
//										// Check that the variant is valid
//										$variant = urldecode($ref . ":" . $hgvs); // Escape any funny characters
//										$result = $client->runMutalyzer(array('variant' => $variant))->runMutalyzerResult;
//										$result_array = (array) $result;
//										$errors = (int) $result_array['errors'];
//										$warnings = (int) $result_array['warnings'];
//										$messages = (array) $result_array['messages'];
//										if ( $warnings === 0 && $errors === 0  ) { // No errors or warnings
//											$mutalyzer_check_result = "1";
////											error_log("$ref $hgvs -> $mutalyzer_check_result");
//										}
//										else {
//											foreach ( $messages as $key => $value ) {
//												foreach ( $value as $message ) {
//													$message = (array) $message;
////													error_log("HGVS -> $variant");
//													$mutalyzer_message = $errors . "\t" . $warnings . "\t" . $ref . ":" . $hgvs . "\t$c\t" . $message['message'] . "\thttps://mutalyzer.nl/check?name=" . $ref . ":" . $hgvs . "\n";
//													echo fwrite($mutalyzer_log,$mutalyzer_message);
//												}
//											}
//										}
//										$insert_data['mutalyzer_check'] = $mutalyzer_check_result;
//										// Get the chr start and end from Mutalyzer webservice
//										$ref_no_version = preg_replace('/\..*/i', '', $ref);
//										$chr = $client->getchromName(array('build' => 'hg19', 'acc' => $ref_no_version))->getchromNameResult;
//										$mappingInfo_result = (array) $client->mappingInfo(array('LOVD_ver' => '2', 'build' => 'hg19', 'accNo' => $ref, 'variant' => urldecode($hgvs)))->mappingInfoResult;
//										if ( ! array_key_exists( 'errorcode', $mappingInfo_result ) ) {
//											$start = $mappingInfo_result['start_g'];
//											$end = $mappingInfo_result['end_g'];
//											$insert_data['location_ref'] = $chr;
//											$insert_data['start'] = $start;
//											$insert_data['end'] = $end;
//										}
//										else {
//											$mutalyzer_flag = true;
//											$failed_mutalyzer_line_numbers[] = $c;
//										}
//									}
//									else {
//										$return_data['result_flag'] = 0;
//										$return_data['error'] = "There was a problem accessing the Mutalyzer webservice";
//									}
//									
////									$result = $this->_runMutalyzer($matches[1], $matches[2]);
////									error_log("ref -> " . $matches[1] . " hgvs -> " . $matches[2] . " result -> " . print_r($mappingInfo_result, 1));
//								}		
//							}
//							elseif ( $column_count == 3 ) {
//								$insert_data['source_url'] = $column;
//							}
//							error_log("data -> " . print_r($insert_data, 1));
						}
					}
					if ( ! empty($insert_data) ) {
						// Override the source with the source based on the import page (hopefully avoids errors of not inputting the right source in the templates)
						$insert_data['source'] = $source;
						$insert_data['laboratory'] = $source;
						if ( ! array_key_exists('sharing_policy', $insert_data) ) {
							$insert_data['sharing_policy'] = $sharing_policy;
						}
						$insert_id = $this->sources_model->insertVariants($insert_data);
						if (!$insert_id) {
							$return_data['result_flag'] = 0;
							$return_data['error'] = "MySQL insert was unsuccessful";
						}
					}
//					else {
//						$return_data['result_flag'] = 0;
//						$return_data['error'] = "No data was extracted from your import template";						
//					}
					
					if ( $mutalyzer_flag ) {
						$failed_mutalyzer_line_numbers_string = implode(', ', $failed_mutalyzer_line_numbers);
						$return_data['result_flag'] = 0;
						$mutalyzer_log_link = base_url("/upload/" . $path_parts['filename'] . ".log");
						$return_data['error'] = "Some variants did not validate with Mutalyzer so some genomic coordinates could not be obtained. These variants have still been imported, however, you should check the log file ($mutalyzer_log_link) for a list of what these Mutalyzer errors were.";
//						$return_data['error'] = "There was a problem fetching genomic coordinates for one or more variants (line numbers: $failed_mutalyzer_line_numbers_string) due to problems with the HGVS nomenclature, you should check these manually with Mutalyzer. These variants have been inserted but do not have any genomic coordinates.";
					}

					
				}
			}
			else {
				$return_data['result_flag'] = 0;
				$return_data['error'] = "Too many variants in input file (max is " . $this->config->item('max_variants') . " and you have $number_lines)";
				// TODO: convert the file to a format that can be used with LOAD DATA INFILE mysql command
			}
			return $return_data;
		}
		
		else if ( $fileformat == 'vcf' ) {
//			error_log("got vcf -> " . $file);
			$perl_path = exec("which perl");
			if ( ! $perl_path ) {
				$return_data['result_flag'] = 0;
				$return_data['error'] = "Couldn't get path of perl interpreter, perl must be installed in order to import vcf variants";
				return $return_data;
			}
			$pwd = exec("pwd");
			$vcf_to_tab = $perl_path . " -I $pwd" . "/resources/scripts " . $pwd . "/resources/scripts/vcf-query $file -f '%CHROM:%POS %REF %ALT\n'";
//			error_log("cmd -> " . $vcf_to_tab);
			$data = shell_exec($vcf_to_tab);
			$client = $this->initializeMutalyzer();
			$count_lines_array = explode("\n", $data);
			$total_variants = count($count_lines_array)-1;
			if ($total_variants < $this->config->item('max_variants')) {
//				error_log("TOTAL VARIANTS -> " . $total_variants);
				foreach (preg_split("/((\r?\n)|(\r\n?))/", $data) as $line) {
					preg_match_all("/(\d+):(\d+)\s+(\S+)\s+(\S+)/", $line, $matches);
					$chr = "chr" . $matches[1][0];
					$start = $matches[2][0];
					$allele_from = $matches[3][0];
					$allele_to = $matches[4][0];
					error_log("line -> " . $line);
					error_log("chr -> " . $chr);

					if ((strlen($allele_from) == 1) && (strlen($allele_to) == 1)) {
						$chr_accession = $this->mutalyzerGetChromosomeAccession($client, $chr); // Get the NC_ accession from chr
//						error_log("chr acc -> " . $chr_accession);
						$chr_hgvs = "$chr_accession:g.$start$allele_from>$allele_to";
//						error_log("position -> " . $chr_hgvs);
						$result = $this->mutalyzerConvertPositionToTranscript($client, $chr_hgvs); // Get all the transcript HGVS for this genomic HGVS
						$result_array = (array) $result;
						if (!empty($result_array)) { // Means there are transcripts available (if not this variant may not be in a gene)
							$transcript_count = 0;
							foreach ($result as $h) {
								foreach ($h as $hgvs) { // Could be more than one transcript HGVS description
									$transcript_count++;
									$ref_hgvs = $this->_splitRefHGVS($hgvs);
									$ref = $ref_hgvs['ref'];
//									error_log("HGVS -> " . $hgvs);
									if ($transcript_count == 1) { // Also get the gene name (only need to get it once since it's the same for all the transcripts)
										$gene = $this->mutalyzerGetGeneNameForTranscript($client, $ref);
									}
								}
							}
//							error_log("gene -> " . $gene);

							$import_data = array(
								'source' => $source,
								'laboratory' => $source,
								'gene' => $gene,
//								'LRG' => $data[2],
								'ref' => $ref_hgvs['ref'],
								'hgvs' => $ref_hgvs['hgvs'],
								'genomic_ref' => $chr_accession,
								'genomic_hgvs' => "$start$allele_from>$allele_to",
//								'individual_id' => "foobar",
								'location_ref' => $chr,
								'start' => $start,
								'end' => $start,
								'build' => "hg19",
								'comment' => "VCF Import",
								'sharing_policy' => $sharing_policy,
								'date_time' => $date_time
							);

							$insert_id = $this->sources_model->insertVariants($import_data);
							if (!$insert_id) {
								$return_data['result_flag'] = 0;
								$return_data['error'] = "MySQL insert was unsuccessful";
//								error_log("failed insert");
							}
						}
					}
					else {
						
					}
//					error_log("$chr $start $allele_from $allele_to");
//					if ( $chr && $start ) {
//						$transcripts = $this->mutalyzerGetTranscriptsFromPosition($client, $chr, $start);
////						error_log(print_r($transcripts, true));
//						foreach ( $transcripts as $t ) {
//							foreach ( $t as $transcript ) {
////								error_log(print_r($transcript, true));
//								$gene = $this->mutalyzerGetGeneNameForTranscript($client, $transcript);
//								error_log("$transcript -> $gene");
//							}
//						}
//					}
				}
			}
			else {
				$return_data['result_flag'] = 0;
				$return_data['error'] = "Too many variants in input file (max is " . $this->config->item('max_variants') . " and you have $total_variants)";
			}
			return $return_data;
		}
		
		else if ( $fileformat == 'nl' ) {
//			error_log("got vcf -> " . $file);
			//http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=snp&id=202240453&retmode=xml
			$perl_path = exec("which perl");
			if ( ! $perl_path ) {
				$return_data['result_flag'] = 0;
				$return_data['error'] = "Couldn't get path of perl interpreter, perl must be installed in order to import vcf variants";
				return $return_data;
			}
			$pwd = exec("pwd");
			$vcf_to_tab = $perl_path . " -I $pwd" . "/resources/scripts " . $pwd . "/resources/scripts/vcf-query $file -f '%CHROM:%POS %REF %ALT %ID %INFO\n'";
//			error_log("cmd -> " . $vcf_to_tab);
			$data = shell_exec($vcf_to_tab);
			$client = $this->initializeMutalyzer();
			$count_lines_array = explode("\n", $data);
			$total_variants = count($count_lines_array)-1;
			if ($total_variants < $this->config->item('max_variants')) {
//				error_log("TOTAL VARIANTS -> " . $total_variants);
				$mutalyzer_flag = false;
				$failed_mutalyzer_line_numbers = array();
				$c = 0;
//				error_log("data -> " . $data);
				foreach (preg_split("/((\r?\n)|(\r\n?))/", $data) as $line) {
					error_log("LINE -> $line");
					preg_match_all("/(\d+):(\d+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)/", $line, $matches);
					$chr = "chr" . $matches[1][0];
					$start = $matches[2][0];
					$allele_from = $matches[3][0];
					$allele_to = $matches[4][0];
					$dbsnp_id = $matches[5][0];
					$info = $matches[6][0];
					$c++;
//					error_log("line -> " . $line);
//					error_log("chr -> " . $chr);
					
//					if ((strlen($allele_from) == 1) && (strlen($allele_to) == 1)) {
						error_log("GET $chr");
						$chr_accession = $this->mutalyzerGetChromosomeAccession($client, $chr); // Get the NC_ accession from chr
//						error_log("chr acc -> " . $chr_accession);
						$chr_hgvs = "$chr_accession:g.$start$allele_from>$allele_to";
//						error_log("position -> " . $chr_hgvs);
						$result = $this->mutalyzerConvertPositionToTranscript($client, $chr_hgvs); // Get all the transcript HGVS for this genomic HGVS
						$result_array = (array) $result;
						if (!empty($result_array)) { // Means there are transcripts available (if not this variant may not be in a gene)
							$transcript_count = 0;
							foreach ($result as $h) {
								foreach ($h as $hgvs) { // Could be more than one transcript HGVS description
									$transcript_count++;
									$ref_hgvs = $this->_splitRefHGVS($hgvs);
									$ref = $ref_hgvs['ref'];
//									error_log("HGVS -> " . $hgvs);
									if ($transcript_count == 1) { // Also get the gene name (only need to get it once since it's the same for all the transcripts)
										$gene = $this->mutalyzerGetGeneNameForTranscript($client, $ref);
									}
								}
							}
//							error_log("gene -> " . $gene);
							$mutalyzer_check_result = "0";
							if ( $mutalyzer_check == "yes" ) {
								if ( $client ) {
									// Check that the variant is valid
									$variant = urldecode($ref_hgvs['ref'] . ":" . $ref_hgvs['hgvs']); // Escape any funny characters
									
//									error_log("running $variant");
									try { // Catch any fatal errors e.g. XR_110845.1:n.-2739G>A gives an internal error with mutalyzer
										$result = $client->runMutalyzer(array('variant' => $variant))->runMutalyzerResult;
									}
									catch (SoapFault $exception) {
										error_log("ERROR -> " . $exception->getMessage());
									}
//									$result = $client->runMutalyzer(array('variant' => $variant))->runMutalyzerResult;
									$result_array = (array) $result;
									$errors = (int) $result_array['errors'];
									$warnings = (int) $result_array['warnings'];
									$messages = (array) $result_array['messages'];
									if ( $warnings === 0 && $errors === 0  ) { // No errors or warnings
										$mutalyzer_check_result = "1";
//										error_log("$ref $hgvs -> $mutalyzer_check_result");
									}
									else {
										$mutalyzer_flag = true;
										foreach ( $messages as $key => $value ) {
											foreach ( $value as $message ) {
												$message = (array) $message;
//												error_log("HGVS -> $variant");
												$mutalyzer_message = $errors . "\t" . $warnings . "\t" . $ref_hgvs['ref'] . ":" . $ref_hgvs['hgvs'] . "\t$c\t" . $message['message'] . "\thttps://mutalyzer.nl/check?name=" . $ref_hgvs['ref'] . ":" . $ref_hgvs['hgvs'] . "\n";
												echo fwrite($mutalyzer_log,$mutalyzer_message);
											}
										}
									}
								}
							}
							$from_range = $start - 10;
							$to_range = $start + 10;
							$blastdbcmd = "/usr/local/ncbi/blast/bin/blastdbcmd -db /Users/owen/cafevariome/data/hg19/hg19 -dbtype nucl -entry '$chr' -range $from_range-$to_range";
							$flanking_data = shell_exec($blastdbcmd);
							error_log("$dbsnp_id -> $chr_accession $from_range $to_range -> flanking $flanking_data $allele_from > $allele_to -> $info");
							//http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=snp&id=202240453&retmode=xml
							$import_data = array(
								'source' => $source,
								'laboratory' => $source,
								'gene' => $gene,
//								'LRG' => $data[2],
								'ref' => $ref_hgvs['ref'],
								'hgvs' => $ref_hgvs['hgvs'],
								'genomic_ref' => $chr_accession,
								'genomic_hgvs' => "$start$allele_from>$allele_to",
//								'individual_id' => "foobar",
								'location_ref' => $chr,
								'start' => $start,
								'end' => $start,
								'build' => "hg19",
								'dbsnp_id' => $dbsnp_id,
								'mutalyzer_check' => $mutalyzer_check_result,
								'comment' => "VCF Import $info",
								'sharing_policy' => $sharing_policy,
								'date_time' => $date_time
							);

							$insert_id = $this->sources_model->insertVariants($import_data);
							if (!$insert_id) {
								$return_data['result_flag'] = 0;
								$return_data['error'] = "MySQL insert was unsuccessful";
//								error_log("failed insert");
							}
						}
//					}
//					else {
//						error_log("not a SNP");
//					}
//					error_log("$chr $start $allele_from $allele_to");
//					if ( $chr && $start ) {
//						$transcripts = $this->mutalyzerGetTranscriptsFromPosition($client, $chr, $start);
////						error_log(print_r($transcripts, true));
//						foreach ( $transcripts as $t ) {
//							foreach ( $t as $transcript ) {
////								error_log(print_r($transcript, true));
//								$gene = $this->mutalyzerGetGeneNameForTranscript($client, $transcript);
//								error_log("$transcript -> $gene");
//							}
//						}
//					}
				}
				if ( $mutalyzer_flag ) {
					$failed_mutalyzer_line_numbers_string = implode(', ', $failed_mutalyzer_line_numbers);
					$return_data['result_flag'] = 0;
					$mutalyzer_log_link = base_url("/upload/" . $path_parts['filename'] . ".log");
					$return_data['error'] = "Some variants did not validate with Mutalyzer so some genomic coordinates could not be obtained. These variants have still been imported, however, you should check the log file ($mutalyzer_log_link) for a list of what these Mutalyzer errors were.";
//					$return_data['error'] = "There was a problem fetching genomic coordinates for one or more variants (line numbers: $failed_mutalyzer_line_numbers_string) due to problems with the HGVS nomenclature, you should check these manually with Mutalyzer. These variants have been inserted but do not have any genomic coordinates.";
				}
			}
			else {
				$return_data['result_flag'] = 0;
				$return_data['error'] = "Too many variants in input file (max is " . $this->config->item('max_variants') . " and you have $total_variants)";
			}
			return $return_data;
		}
		else if ( $fileformat == 'lovd2' ) {
			// TODO: Need to get the fields right and also import pathogenicity and convert the code e.g. 99
//			echo "### LOVD-version 2000-330 ### Variants/Patients ### Do not remove this line, unless importing in versions before 2.0-04 ###\n";
//			echo '"{{ Variant/Reference }}"	"{{ Variant/Exon }}"	"{{ Variant/DNA }}"	"{{ Variant/RNA }}"	"{{ Variant/Protein }}"	"{{ Variant/Restriction_site }}"	"{{ Variant/Frequency }}"	"{{ Variant/DBID }}"	"{{ Variant/Detection/Template }}"	"{{ Variant/Detection/Technique }}"	"{{ Patient/Patient_ID }}"	"{{ Patient/Phenotype/Disease }}"	"{{ Patient/Reference }}"	"{{ Patient/Remarks }}"	"{{ Patient/Remarks_Non_Public }}"	"{{ Patient/Times_Reported }}"	"{{ ID_variantid_ }}"	"{{ ID_patientid_ }}"	"{{ ID_allele_ }}"	"{{ ID_pathogenic_ }}"	"{{ ID_status_ }}"	"{{ ID_sort_ }}"	"{{ ID_submitterid_ }}"	"{{ ID_variant_created_by_ }}"	"{{ variant_created_date_ }}"	"{{ ID_variant_edited_by_ }}"	"{{ variant_edited_date_ }}"	"{{ ID_patient_created_by_ }}"	"{{ patient_created_date_ }}"	"{{ ID_patient_edited_by_ }}"	"{{ patient_edited_date_ }}"' . "\n";
			// TODO: Validate that the file looks like an LOVD2 file - correct number of headers etc
			$c = 0;
			$handle = fopen($file,"r");
			$row = 0;
			while (($data = fgetcsv($handle, 0, "\t", '"')) !== FALSE) {
				$row++;
				if ( $row === 1 ) { // Skip the header line (first line with LOVD version will be automatically not parsed since not of right csv format)
					continue;
				}
				else if ( $row === 2 ) { // Skip the header line (first line with LOVD version will be automatically not parsed since not of right csv format)
					continue;
				}
				else {
//					error_log(print_r($data, 1));
					$number_columns = count($data);
					error_log("number columns -> $number_columns");
//					if ($data[2]) {
//						error_log("data -> " . $data[2] . " row -> " . $row);
						$import_data = array(
							'source' => $source,
							'laboratory' => $source,
							'gene' => $data[3],
							'ref' => $data[4],
							'hgvs' => $data[5],
							'phenotype' => $data[6],
							'individual_id' => $data[7],
							'gender' => $data[8],
							'location_ref' => $data[9],
							'start' => $data[10],
							'end' => $data[11],
							'build' => $data[12],
							'comment' => $data[13],
							'sharing_policy' => $sharing_policy,
							'source_url' => $data[16],
							'date_time' => $date_time,
						);
//						error_log(print_r($import_data, 1));
						$insert_id = $this->sources_model->insertVariants($import_data);
						if (!$insert_id) {
							$return_data['result_flag'] = 0;
							$return_data['error'] = "MySQL insert was unsuccessful";
//							error_log("failed insert");
						}
//					}
//					else {
//						error_log("other");
//					}
				}
			}
			return $return_data;
		}
		
		elseif ( $fileformat == 'lovd2_new' ) {
			
			// Check it's a text file, if it's not then throw error and report to the user
//			$mime_type = mime_content_type($file);
//			error_log("mime type -> " . $mime_type);
//			if (! preg_match("/text/i", $mime_type)) {
//				$return_data['result_flag'] = 0;
//				$return_data['error'] = "The input file doesn't seem to be a text file, please check the file and make sure you specify the correct file format.";
//				return $return_data;
//			}
			
			$escaped_file = escapeshellcmd($file); // Escape any brackets etc in the file and count number of lines
			$number_lines = exec("wc -l < $escaped_file");
			$number_lines = preg_replace( '/\s+/', '', $number_lines );
//			error_log("num -> $number_lines");
			if ($number_lines < $this->config->item('max_variants')) {
				$handle = fopen($file, "r");
				$c = 0;
				$this->load->model('general_model');
//				$headers = $this->general_model->getCoreFields();
				// Initialize core fields array which is populated from import template headers
				$core_fields = array();
				// Get all the fields that are in the main feature table (used for checking whether all fields in the import template actually exist in the database
				$variant_table_fields = $this->general_model->describeTable($this->config->item('feature_table_name'));
				//loop through the csv file and insert into database
				while (($data = fgetcsv($handle, 0, "\t")) !== FALSE) {
					$c++;
					if ( $c === 1 ) { // Get the header line
						foreach ( $data as $column ) {
							$column_count++;
							if ( $column ) { // Only store if there's data for this column
								$core_fields[] = $column; // Store the header name
								if ( ! array_key_exists($column, $variant_table_fields)) { // Check whether this header is defined in the core fields table, return an error if not since there's a mismatch
//									error_log("HEADER CELL ->  $cell_value");
									$return_data['result_flag'] = 0;
									$return_data['error'] = "There are headers in your import template that do not match up to a field in the " . $this->config->item('feature_table_name') . " table. Import cannot proceed, email admin@cafevariome.org if you cannot resolve this problem.";
									return $return_data;
								}
							}
						}
					}
					else { // All other lines are actual data
						$column_count = 0;
						$insert_data = array();
						foreach ( $data as $column ) {
							$column_count++;
							if ( $column ) { // Only store if there's data for this column
								$column = trim($column); // Remove whitespace from start and end of the string
								$insert_data[$core_fields[$column_count-1]] = $column;
							}
						}
						if ( ! empty($insert_data) ) {
							// Override the source with the source based on the import page (hopefully avoids errors of not inputting the right source in the templates)
							$insert_data['source'] = $source;
							$insert_data['laboratory'] = $source;
							if ( ! array_key_exists('sharing_policy', $insert_data) ) {
								$insert_data['sharing_policy'] = $sharing_policy;
							}
							$insert_id = $this->sources_model->insertVariants($insert_data);
							if (!$insert_id) {
								$return_data['result_flag'] = 0;
								$return_data['error'] = "MySQL insert was unsuccessful";
							}
						}
					}
				}
			}
			else {
				$return_data['result_flag'] = 0;
				$return_data['error'] = "Too many variants in input file (max is " . $this->config->item('max_variants') . " and you have $number_lines)";
				// TODO: convert the file to a format that can be used with LOAD DATA INFILE mysql command
			}
			return $return_data;
		}
		
		
		else {
			error_log("something else -> " . $extension . "|");
		}
	}

	function getDateInterval($date, $pattern = 'eu', $type = 'years') {
//		error_log("date -> " . $date);
		$patterns = array(
			'eu'    => 'd/m/Y',
			'mysql' => 'Y-m-d',
			'us'    => 'm/d/Y',
			'epad'  => 'm/Y'
		);

		$now      = new DateTime();
		$in       = DateTime::createFromFormat($patterns[$pattern], $date);
		$interval = $now->diff($in);
//		error_log("interval -> " . print_r($interval, 1));
		
		if ( $type == 'years' ) {
			$age = $interval->y;
		}
		elseif ( $type == 'months' ) {
			$age = ($interval->y * 12) + $interval->m;
		}
//		error_log("date -> " . $date . " age -> " . $age . " type -> " . $type);
		return $age;
	}
	
	function _parse_phenotype_data_eav($phenotype_attribute, $phenotype_value) {
		error_log("$phenotype_attribute -> $phenotype_value");
		// Decide the type of the value
		if ($phenotype_value == 'null') {
			$phenotype_value = null;
			$type = 'qualityValue';
		}
		elseif (($phenotype_value == 'present') || ($phenotype_value == 'absent') || ($phenotype_value == 'unknown')) {
			$type = 'quality';
		}
		elseif (is_numeric($phenotype_value)) {
			$type = 'numeric';
		}
		else {
			$type = 'qualityValue';
		}

		if ($qual == 'awol') {
			$qual = null;
		}
		elseif ($qual == 'null') {  // not found in primary pheno lookup, so new term.  Try to find qaulifier in square brackets.
			if (preg_match('/\[[\w\-\ ]+\]/', $name, $match)) {
				$qual = $match[0];  // this is the qualifier including surrounding brackets
				$qual = substr($qual, 1, -1);  // remove the brackets 
			}
			else {
				$qual = null;
			}
		}


		
		$termName = $phenotype_attribute;
		$termName = trim($termName);
		$sourceId = "LocalList";
		$termId = "locallist/" . $termName;
		$termId = strtolower($termId);
		$termId = str_replace(' ', '_', $termId);
		
		$phenotype_insert_data = array(
			"attribute_sourceID" => $sourceId,
			"attribute_termID" => $termId,
			"attribute_termName" => $termName,
			"value" => $phenotype_value,
			"attribute_qualifier" => $qual,
			"type" => $type
		);
		
		$primary_phenotype_lookup_data = array(
			"sourceId" => $sourceId,
			"termId" => $termId,
			"termName" => $termName,
			"qualifier" => $qual
		);
		
		return( array('phenotype_insert_data' => $phenotype_insert_data, 'primary_phenotype_lookup_data' => $primary_phenotype_lookup_data));
		
	}
	
	function _parse_phenotype_data($current_header, $cell_value) {
		$present_flag = 0;
		if (preg_match("/\|\|/", $cell_value)) { // Check if || is present if so then there's more than one phenotype
//			error_log("pheno -> $cell_value");
			$phenotypes = explode('||', $cell_value);
//			error_log("phenos -> " . print_r($phenotypes, 1));
			foreach ($phenotypes as $phenotype) { // Go through each phenotype
//				error_log("PHENOTYPE -> $phenotype");
				if (preg_match("/\|/", $phenotype)) { // If there's a single | then it means it's using a phenotype ontology
					$ontology_phenotypes = explode('|', $phenotype);
					$sourceId = $ontology_phenotypes[0];
					$sourceId = trim($sourceId);
					$termId = $ontology_phenotypes[1];
					$termId = trim($termId);
					$termName = $ontology_phenotypes[2];
					$termName = trim($termName);
//					error_log("ONT -> $sourceId -> $termId -> $termName");
				} else { // Otherwise it's a single local list ontology term
					$termName = $phenotype;
					$termName = trim($termName);
					$sourceId = "LocalList";
					$termId = "locallist/" . $termName;
					$termId = strtolower($termId);
					$termId = str_replace(' ', '_', $termId);
//					error_log("LOCAL -> $local_list_term");
				}
				$primary_phenotype_lookup_data = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $termName);
				$phenotype_insert_data = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $termName);
			}
		}
		else { // There's just a single phenotype
			if (preg_match("/\|/", $cell_value)) { // It's an ontology phenotype
				$ontology_phenotypes = explode('|', $cell_value);
				$sourceId = $ontology_phenotypes[0];
				$sourceId = trim($sourceId);
				$termId = $ontology_phenotypes[1];
				$termId = trim($termId);
				$termName = $ontology_phenotypes[2];
				$termName = trim($termName);
			}
			else { // Single local list phenotype

				$termName = $current_header;

//				if ( preg_match('/(?<=\()(.+)(?=\))/is', $termName, $match)) {
				if ( preg_match('/\((.*?)\)/i', $termName, $match)) {
					$match = $match[0];
					error_log("match -> " . $match);
				}
				else {
					error_log("no match -> $termName");
				}
								
				$sourceId = "LocalList";
				$termId = "locallist/" . $termName;
				$termId = strtolower($termId);
				$termId = str_replace(' ', '_', $termId);
				$type = 'quality';
				$qualifier = '';
				$value = $cell_value;

				if (strcasecmp($value, 'present') == 0) {
					$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $termName, 'attribute_qualifier' => $qualifier, 'value' => 'present', 'type' => $type);
					$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $termName, 'termDefinition' => '', 'qualifier' => $qualifier);
					$present_flag = 1;
				}
				else {
					$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $termName, 'attribute_qualifier' => $qualifier, 'value' => $value, 'type' => $type);
					$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $termName, 'termDefinition' => '', 'qualifier' => $qualifier);
				}
				
			}
			
			if ( ! $present ) {
				
			}
			
		}
		return( array('phenotype_insert_data' => $phenotype_insert_data, 'primary_phenotype_lookup_data' => $primary_phenotype_lookup_data));
	}
	
	function _parse_phenotype_data_old($cell_value) {
		if (preg_match("/\|\|/", $cell_value)) { // Check if || is present if so then there's more than one phenotype
//			error_log("pheno -> $cell_value");
			$phenotypes = explode('||', $cell_value);
//			error_log("phenos -> " . print_r($phenotypes, 1));
			foreach ($phenotypes as $phenotype) { // Go through each phenotype
//				error_log("PHENOTYPE -> $phenotype");
				if (preg_match("/\|/", $phenotype)) { // If there's a single | then it means it's using a phenotype ontology
					$ontology_phenotypes = explode('|', $phenotype);
					$sourceId = $ontology_phenotypes[0];
					$sourceId = trim($sourceId);
					$termId = $ontology_phenotypes[1];
					$termId = trim($termId);
					$termName = $ontology_phenotypes[2];
					$termName = trim($termName);
//					error_log("ONT -> $sourceId -> $termId -> $termName");
				} else { // Otherwise it's a single local list ontology term
					$termName = $phenotype;
					$termName = trim($termName);
					$sourceId = "LocalList";
					$termId = "locallist/" . $termName;
					$termId = strtolower($termId);
					$termId = str_replace(' ', '_', $termId);
//					error_log("LOCAL -> $local_list_term");
				}
				$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $termName);
				$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $termName);
			}
		}
		else { // There's just a single phenotype
			if (preg_match("/\|/", $cell_value)) { // It's an ontology phenotype
				$ontology_phenotypes = explode('|', $cell_value);
				$sourceId = $ontology_phenotypes[0];
				$sourceId = trim($sourceId);
				$termId = $ontology_phenotypes[1];
				$termId = trim($termId);
				$termName = $ontology_phenotypes[2];
				$termName = trim($termName);
			} else { // Single local list phenotype
				$termName = $cell_value;
				$termName = trim($termName);
				$sourceId = "LocalList";
				$termId = "locallist/" . $termName;
				$termId = strtolower($termId);
				$termId = str_replace(' ', '_', $termId);
			}
			$phenotype_insert_data[] = array('attribute_sourceID' => $sourceId, 'attribute_termID' => $termId, 'attribute_termName' => $termName);
			$primary_phenotype_lookup_data[] = array('sourceId' => $sourceId, 'termId' => $termId, 'termName' => $termName);
		}
		return( array('phenotype_insert_data' => $phenotype_insert_data, 'primary_phenotype_lookup_data' => $primary_phenotype_lookup_data));
	}

	function stats() {
		$this->load->model('sources_model');
		$this->data['variant_counts'] = $this->sources_model->countOnlineSourceEntries();
		$sources = $this->sources_model->getSourcesFull();
		$this->data['sources'] = $sources;
		$this->_render('pages/source_stats');
	}
	
	// Autocomplete lookup for gene input box in add new variant form
	function genelookup() {
		$this->load->model('general_model');
		// process posted form data
		$keyword = $this->input->post('term');
		$data['response'] = 'false'; //Set default response
		$query = $this->general_model->lookupGenesAutocomplete($keyword); //Search DB
		if (!empty($query)) {
			$data['response'] = 'true';
			$data['message'] = array();
			$json_array = array();
			foreach ($query->result() as $row) {
				$auto_val = $row->gene_symbol;
				array_push($json_array, $auto_val);
			}
		}
		echo json_encode($json_array); //echo json string if ajax request
	}

	// Autocomplete lookup for refseq input box in add new variant form
	function refseqlookup() {
		$this->load->model('general_model');
		// process posted form data
		$keyword = $this->input->post('term');
		$data['response'] = 'false'; //Set default response
		$query = $this->general_model->lookupRefSeqAutocomplete($keyword); //Search DB
		if (!empty($query)) {
			$data['response'] = 'true';
			$data['message'] = array();
			$json_array = array();
			foreach ($query->result() as $row) {
				$auto_val = $row->accession;
				array_push($json_array, $auto_val);
			}
		}
		echo json_encode($json_array); //echo json string if ajax request
	}
	
//	// Autocomplete lookup for phenotype input box in add new variant form
//	function phenotypelookup() {
//		$this->load->model('general_model');
//		// process posted form data
//		$phenotype = $this->input->post('phenotype');
//		$gene = $this->input->post('gene');
//		if ( $phenotype ) {
//			if ( $gene ) { // If there's a gene specified then lookup the phenotypes for this gene
////				error_log("gene -> " . $gene);
//				$query = $this->general_model->lookupPhenotypeWithGeneAutocomplete($phenotype, $gene); //Search DB
//				if (!empty($query)) {
//					$data['response'] = 'true';
//					$data['message'] = array();
//					$json_array = array();
//					foreach ($query->result() as $row) {
////						$auto_val = $row->disorder;
////						error_log("-> " . $row->disorder);
//						$json_array[] = array( 'id' => $row->omim_id, 'value' => $row->disorder, 'omim' => $row->omim_id );
////						array_push($json_array, $auto_val);
//					}
//				}
//				echo json_encode($json_array); //echo json string if ajax request
//			}
//			else { // No gene specified - just do a normal autocomplete search through all phenotypes in db
//				$query = $this->general_model->lookupPhenotypeAutocomplete($phenotype); //Search DB
//				if (!empty($query)) {
//					$data['response'] = 'true';
//					$data['message'] = array();
//					$json_array = array();
//					foreach ($query->result() as $row) {
////						$auto_val = $row->disorder;
//						$json_array[] = array( 'id' => $row->omim_id, 'value' => $row->disorder, 'omim' => $row->omim_id );
////						array_push($json_array, $auto_val);
//					}
//				}
//				echo json_encode($json_array); //echo json string if ajax request
//			}
//		}
//	}
	
	private function _splitRefHGVS($term) {
		$pieces = explode(":", $term); // Split region into chr and start/ends
		$ref_hgvs = array();
		$ref_hgvs['ref'] = $pieces[0];
		$ref_hgvs['hgvs'] = $pieces[1];
		return $ref_hgvs;
	}
	
	// Validate the HGVS nomenclature using mutalyzer - used in jquery ajax call when validate button is clicked
	function validate() {
		$ref = $this->input->post('ref');
		$hgvs = $this->input->post('hgvs');
		if ( $ref && $hgvs ) {
//			error_log("got ref -> " . $ref . " hgvs -> " . $hgvs);
			$result = $this->_runMutalyzer($ref, $hgvs);
//			error_log("result -> " . $result);
			echo "$result";
		}
	}
	
	function initializeMutalyzer() {
		$URL = 'https://mutalyzer.nl/services/?wsdl';
		$wsdl_test = htmlentities(file_get_contents($URL)); // Test that the wsdl is present
		if ( $wsdl_test ) {
			$options = array('features' => SOAP_SINGLE_ELEMENT_ARRAYS);
			$client = new SoapClient($URL, $options);
		}
		else {
			$client = false;
		}
		return $client;
	}
	
	function _runMutalyzer($ref, $hgvs) {
		$mutalyzer_data = array();
		$client = $this->initializeMutalyzer();
		$variant = urldecode($ref . ":" . $hgvs);
//		print "variant -> $variant<br />";
		$result = $client->runMutalyzer(array('variant' => $variant))->runMutalyzerResult;
		$result_array = (array) $result;
		$errors = (int) $result_array['errors'];
		$warnings = (int) $result_array['warnings'];
		$messages = (array) $result_array['messages'];
		$mutalyzer_data['warnings'] = $warnings;
		$mutalyzer_data['errors'] = $errors;

		if ( ($warnings > 0 && $errors === 0) || ($warnings === 0 && $errors === 0 ) ) { // No errors or warnings, or some warnings (okay to proceed but return the warning message to the user)
//			print "warnings $warnings | errors $errors<br />";

			if ($warnings > 0) { // Get the warning message
				foreach ( $messages as $key => $value ) {
					foreach ( $value as $message ) {
						$message = (array) $message;
						$mutalyzer_data['message'] = $message['message'];
					}
				}
			}
			// Set valid flag and summary
			$mutalyzer_data['is_valid'] = 1;
			$mutalyzer_data['summary'] = $result_array['summary'];

			// Get the chromosome from the transcript reference (need to strip off any version number)
			$ref_no_version = preg_replace('/\..*/i', '', $ref);
			$chr = $client->getchromName(array('build' => 'hg19', 'acc' => $ref_no_version))->getchromNameResult;
			$mutalyzer_data['chr'] = $chr;

			if ( $warnings === 0 ) { // Can only get the following data if there are no warnings
				// Get the genomic start and end coordinates from the hgvs description
				$mappingInfo_result = (array) $client->mappingInfo(array('LOVD_ver' => '2', 'build' => 'hg19', 'accNo' => $ref, 'variant' => urldecode($hgvs)))->mappingInfoResult;
				$start = $mappingInfo_result['start_g'];
				$end = $mappingInfo_result['end_g'];
				$mutalyzer_data['start'] = $start;
				$mutalyzer_data['end'] = $end;

				// Get the genomic hgvs description from the transcript hgvs description
				$conversion_array = (array) $this->mutalyzerConvertPositionToTranscript($client, $variant); // Get all the transcript HGVS for this genomic HGVS
				foreach ($conversion_array as $key => $value) {
					$genomic_ref_hgvs = $this->_splitRefHGVS($value[0]);
					$mutalyzer_data['genomic_ref'] = $genomic_ref_hgvs['ref'];
					$mutalyzer_data['genomic_hgvs'] = $genomic_ref_hgvs['hgvs'];
				}
			}

			// Get the protein HGVS description from the transcription hgvs description
			$proteins = (array) $result_array['proteinDescriptions'];
			foreach ($proteins as $p) {
				foreach ($p as $protein_description) {
					$protein_ref_hgvs = $this->_splitRefHGVS($protein_description);
					$protein_ref = $protein_ref_hgvs['ref'];
					$protein_hgvs = $protein_ref_hgvs['hgvs'];
					$protein_ref = preg_replace('/\(.*\)/i', '', $protein_ref);
					$mutalyzer_data['protein_hgvs'] = $protein_hgvs;
					$mutalyzer_data['protein_ref'] = $protein_ref;
				}
			}
		}
		else { // 
			$mutalyzer_data['is_valid'] = 0;
			$mutalyzer_data['summary'] = $result_array['summary'];

			if ($errors > 0) { // Get the warning message
				foreach ( $messages as $key => $value ) {
					foreach ( $value as $message ) {
						$message = (array) $message;
						$mutalyzer_data['message'] = $message['message'];
//						print "message " . $message['message'] . "<br />";
					}
				}
			}			
		}

		$mutalyzer_data_json = json_encode($mutalyzer_data);
		echo $mutalyzer_data_json;
	}
	
	// Mutalyzer webservice lookup for valid HGVS nomenclature
	function checkSyntax($ref, $hgvs) {
		$URL = 'https://mutalyzer.nl/services/?wsdl';
		$variant = $ref . ":" . $hgvs;
		// http://www.dotvoid.com/2008/10/soap-structures-in-php/
		$options = array('features' => SOAP_SINGLE_ELEMENT_ARRAYS);

		$client = new SoapClient($URL, $options);

		$result = $client->checkSyntax(array('variant' => $variant))
                  ->checkSyntaxResult;

		if ($result->valid) {
//			error_log($variant . " -> valid");
			return TRUE;
		}
		else {
//			error_log($variant . " -> not valid");
			RETURN FALSE;
		}
		if (isset($result->messages->SoapMessage)) {
//	        echo '<p>Messages:<ol>';
			foreach ($result->messages->SoapMessage as $message) {
//				echo '<li><code>'.htmlentities($message->errorcode).'</code>: ';
//				echo htmlentities($message->message);
			}
//			echo '</ol>';
		}
    }
	
	// Mutalyzer webservice lookup to get transcripts that overlap a chromosomal position
	function mutalyzerGetTranscriptsFromPosition($client, $chr, $start) {
		$result = $client->getTranscripts(array('build' => 'hg19', 'chrom' => $chr, 'pos' => $start ))->getTranscriptsResult;
		return $result;
    }
	
	function mutalyzerGetGeneNameForTranscript($client, $transcript) {
		$result = $client->getGeneName(array('build' => 'hg19', 'accno' => $transcript ))->getGeneNameResult;
		return $result;
	}
	
	function mutalyzerConvertPositionToTranscript($client, $chr_hgvs) {
		$result = $client->numberConversion(array('build' => 'hg19', 'variant' => $chr_hgvs ))->numberConversionResult;
		return $result;
	}
	
	function mutalyzerGetChromosomeAccession($client, $chr) {
		$result = $client->chromAccession(array('build' => 'hg19', 'name' => $chr))->chromAccessionResult;
		return $result;
	}

}
