<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  Preferences Model
*
* Author:  Owen Lancaster
* 		   ol8@leicester.ac.uk
*
*/

class Preferences_model extends CI_Model {

	public function updatePreference ($update) {

//		error_log("update -> " . $update['name'] . " -> " . $update['value']);
		$data = array( 'value' => $update['value'] );

		$this->db->where('name', $update['name']);
		$this->db->update('preferences', $data);
	}
	
	public function getThemes () {
		$query = $this->db->get('themes')->result_array();
		return $query;
	}

	public function getTheme ($theme_name) {
		$query = $this->db->get_where('themes', array('theme_name' => $theme_name));
		$row = $query->row_array();
		return $row;
	}
	
	public function deleteTheme ($theme_id) {
		$this->db->delete('themes', array('theme_id' => $theme_id)); 
	}
	
	public function saveTheme ($theme_data) {
		$this->db->insert('themes', $theme_data); 
	}
	
	public function isThemeNameUnique($theme_name) {
		$this->db->where('theme_name', $theme_name);
		$query = $this->db->get('themes');
		if ($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}

}
