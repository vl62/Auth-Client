<?php

class Posts_model extends CI_Model {

	function getRecentPosts($limit = NULL) {
		$this->db->order_by('post_date_sort', 'desc');
		$this->db->where('post_visible', 1);
		if ( isset ($limit) ) {
			$this->db->limit($limit);
		}
		return $this->db->get('posts');
	}
	
	function getAllPosts() {
		$this->db->order_by('post_date_sort', 'desc');
		return $this->db->get('posts');
	}

	function getSinglePost($id) {
		$this->db->order_by('post_date_sort', 'desc');
		$this->db->where('id', $id);
		$query = $this->db->get('posts');
		$row = $query->row_array();
		return $row;
	}

	function updatePost($id, $data) {
		$this->db->where('id', $id);
		$this->db->update('posts', $data); 	
//		error_log($this->db->last_query());
	}
	
	function insertPost($data) {
		$this->db->insert('posts', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	function deletePost($id) {
		$this->db->delete('posts', array('id' => $id));
	}

}

?>
