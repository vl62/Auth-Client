<?php

require(APPPATH . '/libraries/REST_Controller.php');

class Api extends REST_Controller {
	function blastdbcmd_post() {
//		error_log("test");
		$chr = $this->post('chr');
		$start = $this->post('start');
		$end = $this->post('end');
		exec("blastdbcmd -db /Users/owen/cafevariome/data/hg19/hg19 -dbtype nucl -entry '$chr' -range $start-$end", $data);
		$this->response($data);
	}
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
		$data = array( 'ip' => $this->post('ip'), 'datetime' => $this->post('datetime'), 'term' => $this->post('term'), 'username' => $this->post('username'), 'source' => $this->post('source') );
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
	
	function checkprefix_post() {
		if ( $this->config->item('cafevariome_central') ) {
//			$ip = $_SERVER['REMOTE_ADDR'];
			$this->load->model("general_model");
			$check = $this->general_model->checkPrefix($this->post('prefix'));
//			error_log("check -> " . $check);
			if ( $check ) {
				$data = array('is_unique' => 'no');
			}
			else {
//				$this->response(NULL, 409); // 409 is a conflict error
				$data = array('is_unique' => 'yes');
			}
			$this->response($data, 200);
		}
		else {
			$this->response("This is not Cafe Variome Central");
		}
	}
	
	function insertprefix_post() {
		if ( $this->config->item('cafevariome_central') ) {
//			$ip = $_SERVER['REMOTE_ADDR'];
			$this->load->model("general_model");
			$insert_id = $this->general_model->insertPrefix($this->post('prefix'), $this->post('ip'));
		}
		else {
			$this->response("This is not Cafe Variome Central");
		}
	}
	
	function getkey_get() {
		if ( $this->config->item('cafevariome_central') ) {
			$bioportalkey = $this->config->item('bioportalkey');
			$data = array('key' => $bioportalkey);
			$this->response($data);
		}
		else {
			$this->response("This is not Cafe Variome Central");
		}
	}
	
	function newinstall_post() { // During install users can select whether CV Central is informed about new install, if set to yes then it uses this api function to send data to CV Central
		if ( $this->config->item('cafevariome_central') ) {
			$base_url = $this->post('base_url');
			$host = $this->post('host');
			$ip = $this->post('ip');
			$external_ip = $this->post('external_ip');
			$admin_email = $this->post('admin_email');
			$body = "Base_url: $base_url\nHost: $host\nIP: $ip\nExternal IP: $external_ip\nAdmin Email: $admin_email";
			cafevariomeEmail("admin@cafevariome.org", "admin@cafevariome.org", "admin@cafevariome.org", "New Cafe Variome Install", $body);
//			error_log("base_url -> " . $this->post('base_url'));
		}
		else {
			$this->response("This is not Cafe Variome Central");
		}
	}
	
//	function variant_get() {
//		if(!$this->get('id')) {  
//            $this->response(NULL, 400);  
//        }
//		$this->load->model('sources_model');
//        $variant = $this->sources_model->getVariant( $this->get('id') );
//        if($variant) {
//			if ( $variant['sharing_policy'] === "openAccess" ) {
////				echo "---> " . $variant['sharing_policy'] . "<br />";
//	            $this->response($variant, 200); // 200 being the HTTP response code
////				$this->response('restrictedAccess variant', 404);
//			}
//			else { 
//				$has_access = $this->_checkAccessToVariant($variant);
//				if ($has_access) {
//					$this->response($variant, 200);
//					
//				}
//				else {
//					$this->response('restrictedAccess variant', 404);
//				}
//			}
//        }  
//        else {
//            $this->response(NULL, 404);  
//        }
//    }

//	function laboratories_get() {
//		$this->load->model('sources_model');
//		$labs = $this->sources_model->getLaboratoryCategories();
//		if ($labs) {
//			$this->response($labs, 200); // 200 being the HTTP response code  
//		}
//		else {
//			$this->response(NULL, 404);
//		}
//	}

//	function sources_get() {
//		$this->load->model('sources_model');
//		$sources = $this->sources_model->getSources();
//		if ($sources) {
//			$this->response($sources, 200); // 200 being the HTTP response code  
//		}
//		else {
//			$this->response(NULL, 404);
//		}
//	}
	
//	function private_get() {
//		if(!$this->get('id')) {  
//            $this->response(NULL, 400);  
//        }
//		$this->load->model('sources_model');
//        $variant = $this->sources_model->getVariant( $this->get('id') ); 
//        if($variant) {  
//            $this->response($variant, 200); // 200 being the HTTP response code  
//        }  
//        else {
//            $this->response(NULL, 404);
//        }
//	}
	
//	function variants_get() {
//		if ( $this->config->item('stats')) {
//			if (in_array  ('curl', get_loaded_extensions())) {
//				$this->load->model('stats_model');
//				$ip = getRealIpAddr();
//				$api_stats = array(
//								'ip' => $ip,
//								'datetime' => date('d-m-Y H:i:s'),
//								'uri' => base_url() . $_SERVER['REQUEST_URI'] 
//							);
////				$this->stats_model->insertAPIData($api_stats);
//				$this->load->helper('cafevariome' );
//				updateStats($api_stats, 'apistats');
//			}
//		}
//	
//		$this->load->model('sources_model');
//		if ( $this->get('gene') ) {
//			// TODO ***: check whether restircted access - either exclude all from results or do a check here to see if they have access 
//			$variants = $this->sources_model->getAPIVariantsGene( $this->get('gene'), $this->get('source'), $this->get('limit'), $this->get('offset') );
//			if($variants) {  
//				$this->response($variants, 200); // 200 being the HTTP response code  
//			}  
//			else {
//				$this->response(NULL, 404);  
//			}
//		}
//		elseif ( $this->get('hgvs') || $this->get('ref') ) {
////			echo "--> " . $this->get('hgvs');
//			$variants = $this->sources_model->getAPIVariantsHGVSRef( $this->get('hgvs'), $this->get('ref'), $this->get('source'), $this->get('limit'), $this->get('offset') );
//			if($variants) {  
//				$this->response($variants, 200); // 200 being the HTTP response code  
//			}  
//			else {
//				$this->response(NULL, 404);  
//			}
//
//		}
//		else {
//			$this->response(NULL, 400);
//		}
//	}

//	function variant_post() {
//		$this->load->model('sources_model');
//		$result = $this->user_model->update($this->post('id'), array(
//			'name' => $this->post('name'),
//			'email' => $this->post('email')
//				));
//		if ($result === FALSE) {
//			$this->response(array('status' => 'failed'));
//		}
//		else {
//			$this->response(array('status' => 'success'));
//		}
//	}

//	function _checkAccessToVariant($variant) {
//		if (!$this->ion_auth->logged_in()) {
//			redirect('auth/login', 'refresh');
//		}
//		// Get the ID of the source this variant belongs to and fetch the groups that have access
//		$source_id = $this->sources_model->getSourceIDFromName($variant['source']);
//		$current_source_groups = $this->sources_model->getSourceGroups($source_id);
//		$source_group_ids = array();
//		foreach ($current_source_groups as $source_group) {
////				error_log("source group -> " . $source_group['group_id']);
//			$source_group_ids[] = $source_group['group_id'];
//		}
//
//		// Get the id of the current user and fetch the groups that they belong to
//		$user_id = $this->ion_auth->user()->row()->id;
//		$user_group_ids = array();
//		foreach ($this->ion_auth->get_users_groups($user_id)->result() as $group) {
////				echo "groupid -> " . $group->id . " groupname -> " . $group->name . " description -> " . $group->description;
////				$groups_in[] = $group->id;
//			$user_group_ids[] = $group->id;
////				error_log("user group -> " . $group->id);
//		}
//
//		// Check whether the user is a group that this source belongs to
//		$diff = array_intersect($user_group_ids, $source_group_ids);
//		// If the intersect array is empty it means they are not in any of the required groups so cannot directly access
//		// the variants. Instead generate the popup to request access
//		if ( empty($diff)) {
//			return FALSE;
//		}
//		else {
//			return TRUE;
//		}
//	}
	
}

?>
