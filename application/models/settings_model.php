<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  Settings Model
*
* Author:  Owen Lancaster
* 		   ol8@leicester.ac.uk
*
*/

class Settings_model extends CI_Model {

	public function getSearchFields() {
		$query = $this->db->get('search_fields')->result_array();
//		error_log("fields -> " . print_r($query, 1));
		return $query;
	}
	
	public function getDisplayFields() {
		$query = $this->db->get('display_fields')->result_array();
//		error_log("fields -> " . print_r($query, 1));
		return $query;
	}
	
	public function getDisplayFieldsForSharingPolicy($sharing_policy) {
		$query = $this->db->get_where('display_fields', array('sharing_policy' => $sharing_policy, 'type' => 'search_result'))->result_array();
		// error_log("fields -> " . print_r($query, 1));
		return $query;
	}
	
	
	public function getDisplayFieldsGroupBySharingPolicy() {
//		$query = $this->db->get('display_fields')->result_array();
		$query = $this->db->get_where('display_fields', array('type' => 'search_result'))->result_array();
		$display_fields = array();
		foreach ( $query as $display_field ) {
			$display_fields[$display_field['sharing_policy']][$display_field['display_field_id']]['display_field_id'] = $display_field['display_field_id'];
			$display_fields[$display_field['sharing_policy']][$display_field['display_field_id']]['name'] = $display_field['name'];
			$display_fields[$display_field['sharing_policy']][$display_field['display_field_id']]['visible_name'] = $display_field['visible_name'];
			$display_fields[$display_field['sharing_policy']][$display_field['display_field_id']]['order'] = $display_field['order'];
			$display_fields[$display_field['sharing_policy']][$display_field['display_field_id']]['sharing_policy'] = $display_field['sharing_policy'];
			$display_fields[$display_field['sharing_policy']][$display_field['display_field_id']]['type'] = $display_field['type'];
		}
		return $display_fields;
	}
	
	public function getIndividualRecordDisplayFields() {
		$query = $this->db->get_where('display_fields', array('type' => 'individual_record'))->result_array();
		return $query;
	}
	
	public function getDiscoveryRequiresLoginSetting() {
		$query = $this->db->get_where('settings', array('name' => 'discovery_requires_login'));
		$row = $query->row_array();
//		error_log("row -> " . print_r($row, 1));
		if ( $row['value'] == "on") {
//			error_log("return true");
			return true;
		}
		else {
//			error_log("return false");
			return false;
		}
	}
	
	public function updateSetting ($update) {
		$data = array( 'value' => $update['value'] );
		$this->db->where('name', $update['name']);
		$this->db->update('settings', $data);
//		error_log("update setting -> " . $this->db->last_query());
	}

	public function insertDisplayField($data) {
		$this->db->insert('display_fields', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	public function deleteDisplayFields() {
//		$this->db->empty_table('display_fields');
		$this->db->truncate('display_fields');
	}
	
	public function deleteIndividualRecordDisplayFields() {
		$this->db->where('type', 'individual_record');
		$this->db->delete('display_fields'); 
	}

	public function deleteDisplayFieldsBySharingPolicy($sharing_policy) {
		$this->db->where('sharing_policy', $sharing_policy);
		$this->db->where('type', 'search_result');
		$this->db->delete('display_fields'); 
	}
	
	public function deleteDisplayField($display_field_id) {
		$this->db->delete('display_fields', array('display_field_id' => $display_field_id)); 
	}
	
	public function deleteSearchField($search_field_id) {
		$this->db->delete('search_fields', array('search_field_id' => $search_field_id)); 
	}
	
	public function insertSearchField($data) {
		$this->db->insert('search_fields', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	public function getCurrentHighestOrderForType($type) {
		$this->db->select_max('`order`');
		$this->db->where('type', $type);
		$query = $this->db->get('display_fields')->row();
//		error_log("count -> " . $query->order);
		$highest = $query->order;
		return $highest;
	}
	
	public function updateVisibleDisplayName($display_field_id,	$visible_display_name) {
		$data = array( 'visible_name' => $visible_display_name );
		$this->db->where('display_field_id', $display_field_id);
		$this->db->update('display_fields', $data);
	}
	
}
