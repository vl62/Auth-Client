<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  Sources Model
*
* Author:  Owen Lancaster
* 		   ol8@leicester.ac.uk
*
*/

class Network_model extends CI_Model {

    function getAllNetworkRequests() {
            $query = $this->db->get('network_requests')->result_array();
            return $query;
    }
	
    function getNetworkNameFromNetworkKey($network_key) {
            $query = $this->db->get_where('networks', array('network_key' => $network_key));
            $row = $query->row_array();
            $network_name = $row['network_name'];
//		error_log(print_r($row,1));
            return $network_name;
    }
    
    function checkNetworkExists($network_name) {
        $query = $this->db->get_where('networks',array('network_name' => $network_name));
//		error_log("last -> " . $this->db->last_query());
		if ($query->num_rows() > 0) {
			return true;
		}
		else {
			return false;
		}
	}
	
	function isInstallationPartOfNetwork($installation_key, $network_key) {
		$query = $this->db->get_where('installation_networks', array('installation_key' => $installation_key, 'network_key' => $network_key));
		if ($query->num_rows() > 0){
			return true;
		}
		else {
			return false;
		}
	}
	
	function getNetworksForInstallation($installation_key) {
		$query = $this->db->get_where('installation_networks', array('installation_key' => $installation_key))->result_array();
		return $query;
	}

	function getNetworkRequestsForInstallation($installation_key) {
		$query = $this->db->get_where('network_requests', array('installation_key' => $installation_key))->result_array();
//		error_log(print_r($query, 1));
		return $query;
	}
	
	function getNetworkRequestsForNetworksThisInstallationBelongsTo($installation_key) {
//		TODO: get networks for this installation from the installation_networks table and join to network requests table
		
		$this->db->select('*');
		$this->db->from('installation_networks');
		$this->db->join('network_requests', 'network_requests.network_key = installation_networks.network_key');
		$this->db->where('installation_key', $installation_key);


		error_log(print_r($query, 1));
//		return $query;
	}
	
	function leaveNetwork($installation_key, $network_key) {
		$this->db->where('installation_key', $installation_key);
		$this->db->where('network_key', $network_key);
		$this->db->delete('installation_networks');
//		error_log("last -> " . $this->db->last_query());
		if ( $this->db->affected_rows() ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	function deleteNetwork($network_key) {
		$this->db->where('network_key', $network_key);
		$this->db->delete('networks');
//		error_log("last -> " . $this->db->last_query());
		if ( $this->db->affected_rows() ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
		
	}
	
	function countNumberOfInstallationsForNetwork($network_key = '') {
		$this->db->select('network_key, COUNT(network_key) as total');
		$this->db->group_by('network_key');
		$this->db->order_by('total', 'desc');
		if ( $network_key ) {
			$this->db->where('network_key', $network_key);
		}
		$query = $this->db->get('installation_networks')->result_array();
//		error_log("$network_key --> " . print_r($query, 1));
		return $query;
	}
	
	function getBaseURLsForAllInstallationsInANetwork($network_key) {
		$this->db->select('installation_base_url');
		$this->db->where('network_key', $network_key);
		$query = $this->db->get('installation_networks')->result_array();
		return $query;
	}
	
	
	
}
