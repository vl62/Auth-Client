<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CafeVariome {
    function __construct() {
		$this->CI =& get_instance();
    }
	
	
	function check_user_permission_for_source($source) {
		$permissions = "";
		// Check whether the user can access restrictedAccess variants in this source
		// Get the ID of the source and fetch the groups that it belongs to
//		error_log($source);
		$source_id = $this->CI->sources_model->getSourceIDFromName($source);
		$current_source_groups = $this->CI->sources_model->getSourceGroups($source_id);
		$source_group_ids = array();
		$source_info = $this->CI->sources_model->getSource($source);
		$source_uri = $source_info['uri'];
		foreach ($current_source_groups as $source_group) {
//			error_log("source group -> " . $source_group['group_id']);
			$source_group_ids[] = $source_group['group_id'];
		}
		if ($this->CI->ion_auth->logged_in()) { // Check if the user if logged in
			// If logged in then get the id of the current user and fetch the groups that they belong to
			$user_id = $this->CI->ion_auth->user()->row()->id;
			$user_group_ids = array();
			foreach ($this->CI->ion_auth->get_users_groups($user_id)->result() as $group) {
//				echo "groupid -> " . $group->id . " groupname -> " . $group->name . " description -> " . $group->description;
//				$groups_in[] = $group->id;
				$user_group_ids[] = $group->id;
//				error_log("user group -> " . $group->id);
			}
			// Check whether the user is in a group that this source belongs to
			$diff = array_intersect($user_group_ids, $source_group_ids);
			if (empty($diff)) {
				$permission = FALSE;
			}
			else {
				$permission = TRUE;
			}
		}
		else { // User isn't logged in so set the access flag to false for all the sources
			$permission = FALSE;

		}
//		$this->permissions = $permissions;
		return $permission;
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

	private function _splitRegionDAS($region) {
		$pieces = explode(":", $region); // Split region into chr and start/ends
		$chr = $pieces[0];
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
		$chr = preg_replace( '/chr/', '', $chr );
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
	

}
