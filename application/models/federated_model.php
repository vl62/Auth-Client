<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  Federated Model
*
* Author:  Owen Lancaster
* 		   ol8@leicester.ac.uk
*
*/

class Federated_model extends CI_Model {
	
	public function createNetwork ($data) {
		$this->db->insert('networks', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	public function getNetworks() {
		$query = $this->db->get('networks')->result_array();
		return $query;
	}

	public function getNetworksInstallationNotAMemberOf($installation_key) {
//		$this->db->where('installation_networks !=', $installation_key);
//		$this->db->join('installation_networks', 'installation_networks.network_key = networks.network_key');
		$installation_networks = $this->db->get('installation_networks')->result_array();
		$installation_networks_in = array();
		foreach ( $installation_networks as $in ) {
//			error_log($in['installation_key'] . " vs " . $installation_key);
			if ( $in['installation_key'] == $installation_key ) {
				$installation_networks_in[$in['network_key']] = $in['installation_key'];
			}
		}
		
		$this->db->where('result', 'pending');
		$network_requests = $this->db->get('network_requests')->result_array();
//		error_log(print_r($query, 1));
		$network_requests_sent = array();
		foreach ( $network_requests as $nr ) {
//			error_log($nr['installation_key'] . " vs " . $installation_key);
			if ( $nr['installation_key'] == $installation_key ) {
				$network_requests_sent[$nr['network_key']] = $nr['installation_key'];
			}
		}
		
//		error_log(print_r($installation_networks_in, 1));
		$networks = $this->db->get('networks')->result_array();
		$networks_not_member_of = array();
		foreach ( $networks as $network ) {
			
			// Filter out networks that the installation is already a member of
			$i_flag = 0;
//			error_log("starting network");
			foreach ( $installation_networks_in as $n_key => $i_key ) {
//				error_log("$i_key $n_key -----> " . $network['network_key']);
				if ( $n_key == $network['network_key'] ) {
//					error_log("FLAG!");
					$i_flag = 1;
				}
			}
			if ( ! $i_flag ) {
				$networks_not_member_of[] = $network;
			}
			
			// Filter out networks that the installation has already requested to join
//			$n_flag = 0;
////			error_log("starting network");
//			foreach ( $network_requests_sent as $n_key => $i_key ) {
////				error_log("$i_key $n_key -----> " . $network['network_key']);
//				if ( $n_key == $network['network_key'] ) {
//					$n_flag = 1;
//				}
//			}
//			if ( ! $n_flag ) {
//				$networks_not_member_of[] = $network;
//			}
			
		}
//		error_log(print_r($networks_not_member_of, 1));
		return $networks_not_member_of;
	}

	
	public function getNetworksInstallationMemberOf($installation_key) {
		$this->db->join('networks', 'networks.network_key = installation_networks.network_key');
		$query = $this->db->get_where('installation_networks', array('installation_key' => $installation_key))->result_array();
		return $query;
	}
	
	public function addNetworkJoinRequest($data) {
		$this->db->insert('network_requests', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	
	public function isNetworkNameUnique($network_name) {
		$this->db->where('network_name', $network_name);
		$query = $this->db->get('networks');
		if ($query->num_rows() > 0){
			return false;
		}
		else {
			return true;
		}
	}

	public function addInstallation ($data) {
		$this->db->insert('installations', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	public function addInstallationToNetwork ($data) {
		$this->db->insert('installation_networks', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	public function deleteNode($node_name) {
		$this->db->delete('node_list', array('node_name' => $node_name)); 
	}
	
	public function deleteSourcesForNode($node_name) {
		$this->db->query("DELETE FROM sources WHERE `name` LIKE '%$node_name%'");
//		error_log("last -> " . $this->db->last_query());
//		$this->db->like('name', $node_name);
//		$this->db->delete('node_list', array('node_name' => $node_name)); 
		
		
	}

	public function deleteNodeList() {
//		$this->db->empty_table('node_list');
		$this->db->truncate('node_list');
	}
	
	public function getNodeList() {
		$query = $this->db->get('node_list');
		$node_list = array();
		foreach ($query->result() as $row) {
//			echo $row->node_name . "\t" . $row->node_uri . "\t" . $row->node_key . "\n";
			$node_list[$row->node_name]['node_name'] = $row->node_name;
			$node_list[$row->node_name]['node_uri'] = $row->node_uri;
			$node_list[$row->node_name]['node_key'] = $row->node_key;
			$node_list[$row->node_name]['node_status'] = $row->node_status;
		}
		return $node_list;
	}
	
	public function updateNodeList($data) {
		$this->db->where('node_name', $data['node_name']);
		$this->db->update('node_list', $data);
	}
		
	public function checkNodeExists($node_name) {
		$query = $this->db->get_where('node_list', array('node_name' => $node_name));
		if ($query->num_rows() > 0){
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	public function checkURIExists($node_uri) {
		$node_uri = urldecode($node_uri);
		$query = $this->db->get_where('node_list', array('node_uri' => $node_uri));
		if ($query->num_rows() > 0){
			return TRUE;
		}
		else {
			return FALSE;
		}
	}

	public function checkFederatedURIExists($uri) {
		$node_uri = urldecode($node_uri);
		$query = $this->db->get_where('federated', array('federated_uri' => $uri));
		if ($query->num_rows() > 0){
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	public function updatedFederated($base_url, $data) {
		$this->db->where('federated_uri', $base_url);
		$this->db->update('federated', $data);
	}
	
	public function insertFederated($data) {
		$this->db->insert('federated', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	
	public function checkNodeKeyExists($node_key) {
		$query = $this->db->get_where('node_list', array('node_key' => $node_key));
		if ($query->num_rows() > 0){
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	public function getNodeURIFromNodeName($node_name) {
		$query = $this->db->get_where('node_list', array('node_name' => $node_name));
//		error_log($this->db->last_query());
		if ($query->num_rows() > 0) {
			$row = $query->row(); 
//			error_log(print_r($row, 1));
//			error_log("row uri -> " . $row->node_uri);
			return $row->node_uri;
		}
		else {
//			error_log("no results");
			return FALSE;
		}
	}
	
	public function getNodeNameFromNodeURI($node_uri) {
		$query = $this->db->get_where('node_list', array('node_uri' => $node_uri));
//		error_log($this->db->last_query());
		if ($query->num_rows() > 0) {
			$row = $query->row(); 
//			error_log(print_r($row, 1));
//			error_log("row uri -> " . $row->node_uri);
			return $row->node_name;
		}
		else {
//			error_log("no results");
			return FALSE;
		}
	}
	
	public function getFederatedSources () {
		$query = $this->db->get_where('sources', array('type' => 'api'));
		$sources = array();
		foreach ($query->result() as $source) {
			$sources[$source->name]['source_name'] = $source->name;
			$sources[$source->name]['source_description'] = $source->description;
			$sources[$source->name]['source_uri'] = $source->uri;
		}
		return $sources;
	}

	public function get_sources() {
		return $this->db->select('source_id, name')->from('sources')->get()->result_array();
	}

	public function add_source_name_to_ids($ids) {
		return $this->db->select('source_id, name')->from('sources')->where_in('source_id', $ids)->get()->result_array();
	}

	public function get_variant_cutoff() {
		return $this->db->select("value")->from('settings')->where('name', 'variant_count_cutoff')->get()->result_array()[0]['value'];
	}
	

}
