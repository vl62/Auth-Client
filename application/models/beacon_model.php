<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Beacon_model extends CI_Model {

	public function getBeaconResponse($chromosome, $position, $allele, $reference = null) {
		$this->db->where('location_ref', $chromosome);
		$this->db->where('start', $position);
		
		if ( $this->config->item('cafevariome_central') ) {
			$this->db->where('source !=', 'dbsnp');
		}
		
		if ( $reference ) {
			$this->db->where('build', $reference);
		}
		$query = $this->db->get('variants')->result_array();
//		print_r($query);
		return $query;
	}
	
	public function getBeaconResponseForSource($source, $chromosome, $position, $allele, $reference = null) {
		$this->db->where('location_ref', $chromosome);
		$this->db->where('start', $position);
		$this->db->where('source', $source);
		if ( $reference ) {
			$this->db->where('build', $reference);
		}
		$query = $this->db->get('variants')->result_array();
//		print_r($query);
		return $query;
	}
	
	public function getBeaconSharingPoliciesStatuses() {
		$query = $this->db->get('beacon_sharing_policies')->result_array();
//		print_r($query);
//		return $query;
		$sharing_policies_statuses = array();
		foreach ( $query as $row ) {
			$sharing_policies_statuses[$row['sharing_policy']] = $row['status']; 
		}
//		error_log(print_r($sharing_policies_statuses, 1));
		return $sharing_policies_statuses;
	}
	
	public function updateBeaconSharingPolicy($sharing_policy, $status) {
		$this->db->where('sharing_policy', $sharing_policy);
		$this->db->update('beacon_sharing_policies', array('status' => $status));
//		error_log("last -> " . $this->db->last_query());
		return $this->db->affected_rows();
	}
	
}
