<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  General Model
*
* Author:  Owen Lancaster
* 		   ol8@leicester.ac.uk
*
*/

class General_model extends CI_Model {

	public function insertInABoxData ($data) {
		$this->db->insert('inabox_downloads', $data);
	}
	
	public function insertMailingListData ($data) {
		$this->db->insert('mailing_list', $data);
	}
	
	public function insertDataRequest ($data) {
		$this->db->insert('data_requests', $data);
	}
	
	public function getDataRequest ($string) {
		$query = $this->db->get_where('data_requests', array('string' => $string));
		$row = $query->row_array();
		return $row;
	}

	public function getBioPortalAPIKey () {
		$query = $this->db->get_where('settings', array('name' => 'bioportalkey'));
		$row = $query->row_array();
		$key = $row['value'];
//		error_log("key -> $key");
		return $key;
	}
	
	public function updateBioPortalAPIKey ($new_key) {
		$update = array ('value' => $new_key);
		$this->db->where('name', 'bioportalkey');
		$this->db->update('settings', $update); 
	}
	
	public function checkRequestExists ($source) {
		$query = $this->db->get_where('data_requests', array('source' => $source));
		$row = $query->row_array();
		return $row;
	}
	
	public function checkGeneExists($gene) {
		$query = $this->db->get_where('genes', array('gene_symbol' => $gene));
		$count = $query->num_rows();
//		error_log("$variant_id -> $count");
		if ( $count > 0 ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	public function updateRequestStatus ($data) {
		$update = array ('result' => $data['result'], 'resultreason' => $data['resultreason']);
		$this->db->where('string', $data['string']);
		$this->db->update('data_requests', $update); 
	}

	public function lookupGenesAutocomplete($keyword) {
		$query = $this->db->query("SELECT * FROM genes WHERE gene_symbol LIKE '$keyword%' ORDER BY gene_symbol+0 LIMIT 10");
//		error_log("last -> " . $this->db->last_query());
		return $query;
	}

	public function lookupRefSeqAutocomplete($keyword) {
		$query = $this->db->query("SELECT * FROM refseq WHERE accession LIKE '$keyword%' ORDER BY accession+0 LIMIT 10");
//		error_log("last -> " . $this->db->last_query());
		return $query;
	}
	
	public function lookupPhenotypeAutocomplete($phenotype) {
		$query = $this->db->query("SELECT * FROM gene2omim WHERE disorder LIKE '$phenotype%' GROUP BY disorder LIMIT 10");
//		error_log("last -> " . $this->db->last_query());
		return $query;
	}

	public function lookupPhenotypeWithGeneAutocomplete($phenotype, $gene) {
		$query = $this->db->query("SELECT * FROM gene2omim WHERE gene = '$gene' GROUP BY disorder");
//		error_log("last -> " . $this->db->last_query());
		return $query;
	}
	
	public function getColumnNames($table) {
		$query = $this->db->query("SHOW columns FROM $table")->result_array();
		$headers = array();
		foreach ( $query as $key => $value ) {
			$headers[] = $value['Field'];
		}
//		error_log(print_r($query, 1));
		return $headers;
		return $query;
	}
	
	public function checkORCIDExists($user_id) {
		$query = $this->db->get_where('users', array('id' => $user_id));
		$row = $query->row_array();
		if ( $row['orcid'] ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}

	public function getRandomRows($table, $number_rows) {
		if ( $table == "refseq" ) {
			$query = $this->db->query("SELECT * FROM $table WHERE accession like '%NM%\.%' ORDER BY RAND() LIMIT $number_rows")->result_array();
		}
		else {
			$query = $this->db->query("SELECT * FROM $table ORDER BY RAND() LIMIT $number_rows")->result_array();
		}
		print_r($query);
		print "<br /><br />";
//		error_log("last -> " . $this->db->last_query());
		return $query;
	}
	
	public function setORCIDAlertShown($user_id) {
		$data = array ( 'user_id' => $user_id, 'alert_shown' => '1' );
		$this->db->insert('orcid_alert', $data);
	}

	public function getORCIDAlertShown($user_id) {
		$query = $this->db->get_where('orcid_alert', array('user_id' => $user_id));
//		error_log("last -> " . $this->db->last_query());
		$row = $query->row_array();
//		print_r($row);
		if ( isset($row['alert_shown']) ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	public function describeTable($table) {
		$query = $this->db->query("DESCRIBE $table");
//		print_r($query);
		$table_structure = array();
		foreach ($query->result() as $row) {
//			print_r($row);
			$fields['name']  = $row->Field;
			$fields['type']  = $row->Type;
			$fields['null']  = $row->Null;
			$fields['key']   = $row->Key;
			$fields['extra'] = $row->Extra;
			$table_structure[$fields['name']] = $fields;
		}
//		print_r($table_structure);
//		error_log("last -> " . $this->db->last_query());
//		return $query;
		return $table_structure;
	}

	public function deleteDBField($name) {
		$query = $this->db->query("ALTER TABLE variants DROP $name");
//		print_r($query);
		return $query;
	}
	
	public function addDBField($name) {
		$query = $this->db->query("ALTER TABLE variants DROP $name");
//		print_r($query);
		return $query;
	}

	public function alterDBFieldType($name, $type) {
		$query = $this->db->query("ALTER TABLE info MODIFY column dept int(11)");
		return $query;
	}

	public function regenerateAutocomplete() {
		$this->db->query("DELETE FROM autocomplete");
		$this->db->query("INSERT INTO autocomplete (term, type) SELECT DISTINCT gene, 'gene' FROM variants WHERE gene != ''");
		$this->db->query("INSERT INTO autocomplete (term, type) SELECT DISTINCT LRG, 'LRG' FROM variants WHERE LRG != ''");
		$this->db->query("INSERT INTO autocomplete (term, type) SELECT DISTINCT ref, 'ref' FROM variants WHERE ref != ''");
//		$this->db->query("INSERT INTO autocomplete (term, type) SELECT DISTINCT attribute_termName, 'phenotype' FROM phenotypes WHERE attribute_termName != ''");
		$this->db->query("INSERT INTO autocomplete (term, type) SELECT DISTINCT CONCAT(attribute_termName, ':', value), 'phenotype' FROM phenotypes WHERE attribute_termName != ''");
//		$this->db->query("INSERT INTO autocomplete (term, type) SELECT DISTINCT termName, 'phenotype' FROM primary_phenotype_lookup WHERE termName != ''");
	}
        
	public function deleteDAG() {
		$this->db->query("TRUNCATE table pheno_dag");
	}
   
	public function getOntologiesUsed(){
		$query=$this->db->query("SELECT DISTINCT attribute_sourceID, attribute_termID, attribute_termName from phenotypes where attribute_sourceID !='LocalList'");
		$todo=array();
		foreach ($query->result() as $obj) {
			$source=$obj->attribute_sourceID;
			$termid=$obj->attribute_termID;
			$termname = $obj->attribute_termName;
			$termstring=$termid."|".$termname;
			$todo[$termstring]=$source;
		}
		return $todo;
	}

	public function addOntologyRelationship($abbreviation,$key,$parentid,$value){
		$this->db->query("INSERT INTO pheno_dag (ontology, termid, parentid, termname) VALUES ('$abbreviation','$key', '$parentid', '$value')");
	}
        
	public function determineTerminalNodes(){
		$funcarray=array();
		$query=$this->db->query("SELECT id FROM pheno_dag WHERE termid NOT IN (SELECT parentid FROM pheno_dag)");
		foreach ($query->result() as $obj) {
			array_push($funcarray,$obj->id);
		}
		return $funcarray;
	}
        
        
	public function setTerminalNode($thisnode){
		$this->db->query("UPDATE pheno_dag SET terminalnode='1' where id ='$thisnode'");
	}
        
 	
	public function getCoreFields() {
//		$this->db->select('core_field_name');
		$query = $this->db->get('core_fields');
		$core_fields = array();
		foreach ($query->result() as $row) {
			$core_fields[]  = $row->core_field_name;
		}
		return $core_fields;
	}

	public function getCoreFieldsAssociative() {
//		$this->db->select('core_field_name');
		$query = $this->db->get('core_fields');
		$core_fields = array();
		foreach ($query->result() as $row) {
			$core_fields[$row->core_field_name]  = $row->core_field_name;
		}
		return $core_fields;
	}
	
	public function setCoreFields($core_fields) {
		$this->db->empty_table('core_fields');
		$status = true;
		$data = array();
		foreach ( $core_fields as $core_field ) {
			$data = array( 'core_field_name' => $core_field );
			$this->db->insert('core_fields', $data);
			$insert_id = $this->db->insert_id();
			if ( ! $insert_id ) {
				$status = false;
			}
		}
		return $status;
		
	}

	public function insertQueryBuilderQuery ($data) {
		$this->db->insert('query_builder_history', $data);
	}
	
	public function checkPrefix($prefix) {
		$query = $this->db->get_where('prefixes', array('prefix' => $prefix));
//		error_log(print_r($this->db->last_query()));
		if ($query->num_rows() > 0) {
			return true;
		}
		else {
			return false;
		}
	}
	
	public function insertPrefix($prefix, $ip) {
		$data = array(
			'prefix' => $prefix,
			'ip' => $ip
		);
		$this->db->insert('prefixes', $data); 
		return $this->db->insert_id();
	}
	
	public function authenticateUser($username, $password) {
		$md5_password = md5($password);
		if(filter_var($username, FILTER_VALIDATE_EMAIL)) { // If an email address was entered use this for query
			$query = $this->db->select('username, email, id, password, active, last_login')
			                  ->where('email', $username)
			                  ->limit(1)
			                  ->get('users');
		}
		else { // Otherwise use the username in the query
			$query = $this->db->select('username, email, id, password, active, last_login')
			                  ->where('username', $username)
			                  ->limit(1)
			                  ->get('users');
		}
		$row = $query->row_array();
		if (array_key_exists('password', $row)) {
			if ( $row['password'] == $md5_password ) {
//				error_log("last -> " . $this->db->last_query());
//				error_log(print_r($row, 1));
//				return TRUE;
				return $row;
			}
			else {
				return FALSE;
			}
		}
		else {
			return FALSE;
		}
	}

	public function getOMIMFromGene ($gene) {
		$query = $this->db->get_where('gene2omim', array('gene' => $gene))->result_array();
		return $query;
	}

	public function get_excluded_records($mul = 1) {
		$ids = $this->db->select('record_id')->from('variants')->where('included', '0')->limit($mul > 0 ? 10*$mul : 0, $mul > 0 ? 10*$mul-1 : 0)->get()->result_array();
		$counts = $this->db->select('count(record_id)')->from('variants')->where('included', '0')->get()->result_array();
		error_log(print_r($counts, 1));
		return array($counts[0]['count(record_id)'], $ids);
	}
	
}
