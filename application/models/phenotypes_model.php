<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  Phenotypes Model
*/

class Phenotypes_model extends CI_Model {

//	public function getPhenotypeLocalList () {
//                $this->db->order_by("termName", "asc");
//		$query = $this->db->get_where('primary_phenotype_lookup', array('sourceId =' => 'LocalList'));
//                error_log($this->db->last_query());
//		return $query->result_array();
//	}
        
        public function getPhenotypeLocalList () {
                $this->db->order_by("termName", "asc");
                $this->db->distinct();
                $this->db->select('primary_phenotype_lookup.id, primary_phenotype_lookup.termId, primary_phenotype_lookup.termName, primary_phenotype_lookup.termDefinition, primary_phenotype_lookup.qualifier, phenotypes.attribute_termId as sourceId');
                $this->db->from('primary_phenotype_lookup');
                $this->db->where('primary_phenotype_lookup.sourceId =', 'LocalList');
                $this->db->join('phenotypes', 'primary_phenotype_lookup.termId = phenotypes.attribute_termId', 'left');
                $query = $this->db->get();
//                error_log($this->db->last_query());
                return $query->result_array(); 
        }
        
        public function getLocalPhenotype ($ppl_id) {
		$query = $this->db->get_where('primary_phenotype_lookup', array('id =' => $ppl_id, 'sourceId =' => 'LocalList'));
                error_log($this->db->last_query());		 
		return $query->result_array();
	}
	
	public function getPhenotypeOntologies() {
		//$query = $this->db->get('ontology_list');

                $this->db->order_by("ontology_list.name", "asc");
                $this->db->distinct();
                $this->db->select('ontology_list.abbreviation, ontology_list.name, phenotypes.attribute_sourceId');
                $this->db->from('ontology_list');
                $this->db->where('ontology_list.abbreviation !=', 'LocalList');
                $this->db->join('phenotypes', 'ontology_list.abbreviation = phenotypes.attribute_sourceId', 'left');
                $query = $this->db->get();
//                error_log($this->db->last_query());
                return $query->result_array();

	}

	public function getPhenotypeAttributesNRList() { // Uses group_by instead of distinct as we want distinct on multiple columns
//		$this->db->distinct("attribute_termName");
		$this->db->select('attribute_sourceID,attribute_termName');
		$this->db->from('phenotypes');
		$this->db->group_by('attribute_sourceID,attribute_termName');
		$this->db->order_by("LOWER(attribute_termName)", "asc");
		$query = $this->db->get();
//		error_log($this->db->last_query());
//		error_log(print_r($query->result_array(), 1));
		return $query->result_array();
	}

	
	public function insertNewOntology($lookup_data) {
		$this->db->insert('ontology_list', $lookup_data);
	//	error_log($this->db->last_query());
		$insert_id = $this->db->insert_id();
		return $insert_id;
	} 
        
        public function removeOntology($ont2del) {
                $delete_ont= $this->db->delete('ontology_list', $ont2del);
		error_log($this->db->last_query());
		return $delete_ont;
	}  
        
        public function removeLLTerm($term2del) {
                $delete_term= $this->db->delete('primary_phenotype_lookup', $term2del);
		error_log($this->db->last_query());
		return $delete_term;
	}
	
        public function getLastRanking() {
                $this->db->select_max('ranking');
                $query = $this->db->get('ontology_list');
                $lastranking=$query->row('ranking');
                return $lastranking;
        }
        
	public function insertPhenotypeTerm($data) {
		$this->db->insert('primary_phenotype_lookup', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	function lookupPhenotypeAutocompleteWithAttribute($attribute, $keyword) {

		$query = $this->db->query("SELECT DISTINCT(value) FROM phenotypes WHERE attribute_termName = '$attribute' AND value LIKE '$keyword%' ORDER BY value+0 LIMIT 10");
//		$this->db->like('term', $keyword);
//		$this->db->order_by("term+0");
//		$this->db->limit(10);
//		$query = $this->db->get('term, type');
//		foreach ($query->result() as $r) {
//			error_log($r->term);
//		}
//		error_log($this->db->last_query());
		return $query;
	}

	function lookupPhenotypeNonRedundantListForAttribute($attribute) {
		$query = $this->db->query("SELECT DISTINCT(value) FROM phenotypes WHERE attribute_termName = '$attribute' ORDER BY value+0");
//		error_log($this->db->last_query());
		return $query;
	}

	function regeneratePhenotypeAttributesAndValues() {
		$query = $this->db->query("SELECT attribute_termName, value FROM phenotypes GROUP BY attribute_termName, value ORDER BY value+0 LIMIT 50")->result_array();
//		error_log($this->db->last_query());
		return $query;
	}
	
	public function checkIfPhenotypeTermExists($term) {
		$query = $this->db->get_where('primary_phenotype_lookup', array('termName' => $term, 'sourceId' => 'LocalList'));
		if ( $query->num_rows() > 0 ) {
			return false;
		}
		else {
			return true;
		}
	}
	
	public function getVariantsWithPhenotypes() {
		$query = $this->db->get('phenotypes');
		return $query->result_array();
	}
	
	public function getPhenotypeDagParentFromTermID($term_id) {
		$query = $this->db->get_where('pheno_dag', array('termid' => $term_id));
//		error_log($this->db->last_query());
		return $query->result_array();
	}
	
	public function insertVariantsToPhenotypes($variants_to_phenotypes) {
		$this->db->insert('variants_to_phenotypes', $variants_to_phenotypes);
		$insert_id = $this->db->insert_id();
		return $insert_id;

	}

	public function deleteVariantsToPhenotypesTable() {
		$this->db->truncate('variants_to_phenotypes');
	}

	
	public function checkIfPhenoDagIsPopulated() {
		$query = $this->db->get('pheno_dag');
		$row_count = $query->num_rows();
//		echo "row -> $row_count";
		if ( $row_count > 0 ) {
			return true;
		}
		else {
			return false;
		}
	}


	public function emptyNetworksPhenotypesAttributesValues() {
		$this->db->truncate('networks_phenotypes_attributes_values');
	}
	
	public function insertNetworksPhenotypesAttributesValues($data) {
		$this->db->insert('networks_phenotypes_attributes_values', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;

	}
	
}
