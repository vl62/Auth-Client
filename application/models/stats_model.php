<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  Stats Model
*
* Author:  Owen Lancaster
* 		   ol8@leicester.ac.uk
*
*/

class Stats_model extends CI_Model {

//	function __construct() {
//        parent::__construct();
//        $this->stats = $this->load->database('stats', TRUE);            
//    }
	
	public function insertLoginData ($data) {
		$this->db->insert('stats_logins', $data);
	}
	
	public function insertRegistrationData ($data) {
		$this->db->insert('stats_registrations', $data);
	}
	
	public function insertAPIData ($data) {
		$this->db->insert('stats_api', $data);
	}
	
	public function insertSearchData ($data) {
		$this->db->insert('stats_searches', $data);
	}
	
	public function dumpStatsTableAsCSV($table) {
		$this->load->dbutil();
		$query = $this->db->query("SELECT * FROM $table");
//		error_log($this->dbutil->csv_from_result($query));
		return $this->dbutil->csv_from_result($query);
	}
	
	public function getSearchTerms () {
		$query = $this->db->query('SELECT COUNT( * ) as total, term FROM `stats_searches` GROUP BY term ORDER BY total desc LIMIT 10');
		$term_counts = array();
		$terms = array();
		foreach ($query->result() as $r) {
			$term_counts[] = (int)$r->total;
			$terms[] = $r->term;
		}
		$term_data = array();
		$term_data['terms'] = $terms;
		$term_data['term_counts'] = $term_counts;
		return $term_data;
	}
	
	public function getUniqueIPAddresses () {
//		$this->stats->select('ip');
//		$this->db->distinct();
//		$this->db->limit(10);
//		$query = $this->stats->get('searches');
		$query = $this->db->query('SELECT COUNT( * ) as total, ip FROM `stats_searches` GROUP BY ip ORDER BY total desc LIMIT 10');
		$ips = array();
		$ip_counts = array();
		$pie_data = array();
		foreach ($query->result() as $r) {
			$ips[] = $r->ip;
			$ip_counts[] = (int)$r->total;
			$pie_data[$r->ip] = (int)$r->total;
		}
		$ip_data = array();
		$ip_data['ips'] = $ips;
		$ip_data['ip_counts'] = $ip_counts;
		$ip_data['pie_data'] = $pie_data;
		return $ip_data;
	}
	
	public function insertVariantData ($data) {
		$this->db->insert('stats_variants', $data);
	}
	
	public function updateVariantCount ($cafevariome_id) {
		$this->db->query("INSERT INTO stats_variant (cafevariome_id, count) VALUES ($cafevariome_id, '1') ON DUPLICATE KEY UPDATE count = count + 1");
//		insert into favs (user, item) values (2, 3) on duplicate key update count = count + 1;
//		error_log("query -> " . print_r($this->db->last_query(),1));
	}

	public function getRecordCounts() {
		$this->db->order_by("count", "desc"); 
		$query = $this->db->get('stats_variant');
		$variant_data = array();
		$cafevariome_ids = array();
		$cafevariome_id_counts = array();
		foreach ($query->result() as $r) {
			$cafevariome_ids[] = $this->config->item('cvid_prefix') . $r->cafevariome_id;
			$cafevariome_id_counts[] = (int)$r->count;
		}
		$variant_data['cafevariome_ids'] = $cafevariome_ids;
		$variant_data['cafevariome_id_counts'] = $cafevariome_id_counts;
		return $variant_data;
	}
	
	public function resetStatsTables() {
		$this->db->truncate('stats_searches');
		$this->db->truncate('stats_variants');
		$this->db->truncate('stats_variant');
		
	}
	
}
