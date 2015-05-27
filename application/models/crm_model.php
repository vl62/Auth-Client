<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  CRM Model
*
* Author:  Owen Lancaster
* 		   ol8@leicester.ac.uk
*
*/

class Crm_model extends CI_Model {

	public function getCRMLeads() {
		$query = $this->db->get('crm')->result_array();
		return $query;
	}
	
	public function getCRMLeadByID($id) {
//		$query = $this->db->get('crm')->result_array();
		$query = $this->db->get_where('crm', array('id' => $id));
//		error_log("last -> " . $this->db->last_query());
		$row = $query->row_array();
		return $row;
	}
	
	public function getCRMEmailTemplateByID($id) {
//		$query = $this->db->get('crm')->result_array();
		$query = $this->db->get_where('crm_lead_email_templates', array('template_id' => $id));
//		error_log("last -> " . $this->db->last_query());
		$row = $query->row_array();
		return $row;
	}
	
	public function getCRMEmailTemplates() {
		$query = $this->db->get('crm_lead_email_templates')->result_array();
		return $query;
	}
	
	public function deleteCRMLeadByID($id) {
		$this->db->delete('crm', array('id' => $id)); 
	}
	
	public function insertCRMLeadEmail($data) {
		$this->db->insert('crm_lead_emails', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	public function insertCRMLeadLinkTrack($data) {
		$this->db->insert('crm_lead_link_track', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	
	public function updateCRMLeadByID ($id, $data) {
		$this->db->where('id', $id);
		$this->db->update('crm', $data); 
//		error_log(print_r($this->db->last_query()));
		return $this->db->affected_rows();
	}
	
	public function incrementCRMLeadEmailCount($id) {
//		UPDATE crm_lead_emails SET number_of_times_opened = number_of_times_opened + 1 WHERE lead_email_id = 1
		// Active record syntax for this is from here: http://stackoverflow.com/questions/6373564/increment-field-of-mysql-database-using-codeigniters-active-record-syntax
		$this->db->where('lead_email_id', $id);
		$this->db->set('number_of_times_opened', 'number_of_times_opened+1', FALSE);
		$this->db->update('crm_lead_emails');
		
	}
	
	public function getCRMLinkByID($id) {
		$query = $this->db->get_where('crm_links', array('link_id' => $id));
//		error_log("last -> " . $this->db->last_query());
		$row = $query->row_array();
		return $row['link_url'];
	}
	
	public function checkIfCRMLeadHasBeenContacted($id) {
		$query = $this->db->get_where('crm', array('id' => $id));
//		error_log("last -> " . $this->db->last_query());
		$row = $query->row_array();
		if ( $row['date_initial_contact'] == "" ) {
			return false;
		}
		else {
			return true;
		}
	}
	
}
