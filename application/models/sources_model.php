<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  Sources Model
*
* Author:  Owen Lancaster
* 		   ol8@leicester.ac.uk
*
*/

class Sources_model extends CI_Model {

	public function getSources () {
//		$query = $this->db->get('sources');
		$query = $this->db->get_where('sources', array('status' => 'online'));
//		error_log($this->db->last_query());
		$sources_options = array();
		foreach ($query->result() as $source) {
			$sources_options[$source->name] = $source->description;
//			print $source->name . " -> " . $source->description . "<br />";
		}
		return $sources_options;
	}
	
	public function getSourcesFull () {
		$query = $this->db->get('sources')->result_array();
		return $query;
	}
	
	public function getSourcesFullOnline () {
		$query = $this->db->get_where('sources', array('status' => 'online'));
		return $query;
	}
	
	public function getSourcesForFederatedQuery () {
		$this->db->select('name, description');
		$query = $this->db->get_where('sources', array('type !=' => 'federated'))->result_array();
		return $query;
	}
	
	public function getSourceSingle ($source) {
		$query = $this->db->get_where('sources', array('name' => $source));
		$row = $query->row_array();
		$sources = array();
		if (array_key_exists('description', $row)) {
			$sources[$source] = $row['description'];
			return $sources;
		}
		else {
			return FALSE;
		}
	}
	
	public function getSourceIDFromName($source) {
		$query = $this->db->get_where('sources', array('name' => $source));
//		error_log($this->db->last_query());
		$row = $query->row_array();
		$source_id = $row['source_id'];
		return $source_id;
	}
	
	public function getSourceForID($source_id) {
		$query = $this->db->get_where('sources', array('source_id' => $source_id));
//		error_log($this->db->last_query());
		$row = $query->row_array();
		return $row['description'];
	}
	
	public function getEmailFromSourceName($source) {
		$query = $this->db->get_where('sources', array('name' => $source));
//		error_log($this->db->last_query());
		$row = $query->row_array();
		$email = $row['email'];
		return $email;
	}
	
	public function getSource ($source) {
		$query = $this->db->get_where('sources', array('name' => $source));
		$row = $query->row_array();
		return $row;
	}
	
	public function getSourceSingleFull($source_id) {
		$query = $this->db->get_where('sources', array('source_id' => $source_id));
		$row = $query->row_array();
		return $row;
	}

	function getOnlineSources($user_groups) {
		$this->db->select('sources_groups.source_id, sources_groups.id, sources.name AS source_name, sources.description AS source_description, groups.name AS group_name, groups.description AS group_description, sources_groups.group_id');
		$this->db->from('sources_groups');
		$this->db->where('status', 'online');
		$this->db->join('groups', 'sources_groups.group_id = groups.id');
		$this->db->join('sources', 'sources_groups.source_id = sources.source_id');
		$query = $this->db->get();
		$groups = array();
		foreach ( $query->result() as $r ) {
//			print_r($r);
			if (array_key_exists ( $r->group_id , $user_groups )) {
//				print "match -> " . $r->group_id . " -> " . $r->source_description . " -> " . $r->group_description . "<br />";
				$groups[$r->source_id] = $r->source_description;
			}
		}
		return $groups;
	}

	public function getOnlineSourcesSingle() {
		$query = $this->db->get_where('sources', array('status' => 'online'));
		$sources = array();
		foreach ($query->result() as $source) {
			 $sources[$source->name] = $source->name;
		}
		return $sources;
	}

	function getAllDataRequests() {
		$query = $this->db->get('data_requests')->result_array();
		return $query;
	}
	
	function getDataRequests($username) {
		$query = $this->db->get_where('data_requests', array('username' => $username))->result_array();
		return $query;
	}
	
	function getDataRequestByID($request_id) {
		$query = $this->db->get_where('data_requests', array('request_id' => $request_id));
		return $row = $query->row_array();
	}
	
	function updateDataRequestResult($request_id, $result, $resultreason) {
		$this->db->where('request_id', $request_id);
		$this->db->update('data_requests', array('result' => $result, 'resultreason' => $resultreason));
//		error_log("last -> " . $this->db->last_query());
	}
	
	function deleteDataRequest($request_id) {
		$this->db->delete('data_requests', array('request_id' => $request_id)); 
	}
	
	public function updateSource ($data) {
		$this->db->where('source_id', $data['source_id']);
		$this->db->update('sources', $data);
//		error_log("last -> " . $this->db->last_query());
	}
	
	public function updateSourceSharingPolicy ($source_name, $sharing_policy) {
		$this->db->where('source', $source_name);
		$this->db->update('variants', array('sharing_policy' => $sharing_policy));
//		error_log("last -> " . $this->db->last_query());
	}

	public function updateLink ($source_name, $link) {
		$this->db->where('source', $source_name);
		$this->db->update('variants', array('source_url' => $link));
//		error_log("last -> " . $this->db->last_query());
	}
	
	public function getAllVariantIDsInSource ($source_name) {
		$this->db->select('cafevariome_id');
		$this->db->where('source', $source_name);
		$query = $this->db->get('variants')->result_array();
//		error_log("last -> " . $this->db->last_query());
		return $query;
	}
	
	public function updateVariantSharingPolicy ($cvid, $sharing_policy) {
		$this->db->where('cafevariome_id', $cvid);
		$this->db->update('variants', array('sharing_policy' => $sharing_policy));
//		error_log("last -> " . $this->db->last_query());
		if ( $this->db->affected_rows() ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	public function updateSourceStatus ($source_id, $status) {
		$this->db->where('source_id', $source_id);
		$this->db->update('sources', array('status' => $status));
//		error_log("last -> " . $this->db->last_query());
	}
	
	public function checkSourceExists ($source) {
		$query = $this->db->get_where('sources', array('name' => $source));
		$row = $query->row_array();
		if ( array_key_exists('description', $row) ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	public function checkVariantIDExists ($variant_id) {
		$query = $this->db->get_where('variants', array('variant_id' => $variant_id));
		$count = $query->num_rows();
//		error_log("$variant_id -> $count");
		if ( $count > 0 ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}

	
	public function getSourcesTypes () {
		$query = $this->db->get('sources');
		$sources_types = array();
		foreach ($query->result() as $source) {
			$sources_types[$source->name] = $source->type;
//			print $source->name . " -> " . $source->description . "<br />";
		}
		return $sources_types;
	}
	
	public function getSourcesStatus () {
		$query = $this->db->get('sources');
		$sources_status = array();
		foreach ($query->result() as $source) {
			$sources_status[$source->name] = $source->status;
//			print $source->name . " -> " . $source->description . "<br />";
		}
		return $sources_status;
	}
	
	public function deleteSource($source_id) {
		$this->db->delete('sources', array('source_id' => $source_id)); 
	}
	
	public function deleteSourceByName($source_name) {
		$this->db->delete('sources', array('name' => $source_name)); 
	}

//	public function deleteSourceByName($source_name) {
//		$this->db->delete('sources', array('name' => $source_name, 'type' => 'das'));
//	}
	
	public function insertSource($source_data) {
//		error_log(print_r($source_data, 1));
		$this->db->insert('sources', $source_data);
//		error_log("last query -> " . $this->db->last_query());
//		error_log("error -> " . $this->db->_error_message());
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	public function insertVariants($variant_data) {
		$this->db->insert('variants', $variant_data);
//		error_log($this->db->last_query());
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
        
	public function insertPhenotypes($phenotype_data) {
		$this->db->insert('phenotypes', $phenotype_data);
//		error_log($this->db->last_query());
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
        
	public function getPrimaryLookup($conceptid) {        
		$query = $this->db->get_where('primary_phenotype_lookup', array('termId' => $conceptid));
		$row = $query->row_array();
		return $row;
	}
            
	public function insertPrimaryLookup($lookup_data) {
		$this->db->insert('primary_phenotype_lookup', $lookup_data);
//		error_log($this->db->last_query());
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}    
            
		        
//	public function getontologyvirtualids(){    
//		$this->db->distinct();
//		$this->db->where('virtualid >', '0');
//		$query = $this->db->get('ontology_list');
//                       
//		$usedOntologies=array();
//		foreach ($query->result() as $obj) {
//			$vid=$obj->virtualid;
//			array_push($usedOntologies, $vid);                      
//		}
//		return $usedOntologies;
//	}
        
        public function getontologyabbreviations(){    
		$this->db->distinct();
		$this->db->where('abbreviation !=', 'LocalList');
		$query = $this->db->get('ontology_list');
                       
		$usedOntologies=array();
		foreach ($query->result() as $obj) {
			$abbreviation=$obj->abbreviation;
			array_push($usedOntologies, $abbreviation);                      
		}
		return $usedOntologies;
	}
        
        
        public function getexistingphenotypes($variantid){
               $this->db->where('cafevariome_id', $variantid);
               $query = $this->db->get('phenotypes');
               $exitingphenotypes=array();
               foreach ($query->result() as $obj) {
                    $termid=$obj->attribute_termID;
                    $termname=$obj->attribute_termName;
                    $sourceId=$obj->attribute_sourceID;
                    $value=$obj->value;
                    if ($value ==''){
                        $value='null';
                    }
                    $displaystring=$termname ." (id:" . $termid .", ontology:". $sourceId . ") ".$value;
                    $valuestring=$termname ."|".$termid."|".$sourceId."|".$value;
                    $exitingphenotypes[$valuestring]=$displaystring;
                 //   array_push($exitingphenotypes, $displaystring);
               }
               return $exitingphenotypes;
        }
        
        public function deletePhenotype($id) {
		$this->db->delete('phenotypes', array('cafevariome_id' => $id));
        }       
       
        
    public function insertBatchVariants($variant_data) {
//		error_log("insert");
		$this->db->insert_batch('variants', $variant_data);
//		error_log($this->db->last_query());
//		$insert_id = $this->db->insert_id();
//		return $insert_id;
	}
	
	public function getHighestCVID() {
//		SELECT MAX(cafevariome_id) AS article FROM variants;
	}
	
	public function getLaboratoryCategories () {
		$this->db->distinct();
		$this->db->select('laboratory, source');
		$labs = array();
		$query = $this->db->get('variants');
		foreach ($query->result() as $source) {
			if ( $source->source == "diagnostic") {
				$labs[] = $source->laboratory;
			}
		}
		return $labs;
	}
	
	public function getVariant ($id) {
		$query = $this->db->get_where('variants', array('cafevariome_id' => $id));
		$row = $query->row_array();
		return $row;
	}
	
	// Get variant with phenotype - used for indexing elasticsearch and also updating elasticsearch index (with additional sharing_policy update change)
	public function getVariantWithPhenotypeJSON ($id) {
		$variant_query = $this->db->get_where('variants', array('cafevariome_id' => $id));
		$index_data = $variant_query->row_array();
//		error_log($variant_data);
		$phenotype_query = $this->db->get_where('phenotypes', array('cafevariome_id' => $id))->result_array();
//		error_log("test -> " . print_r($phenotype_query, 1));
		if ( ! empty($phenotype_query)) {
			error_log("not empty");
			$phenotype_array = array();
			$phenotype_array_query = array();
			foreach ( $phenotype_query as $phenotype ) {
				$attribute = str_replace(' ', '_', $phenotype['attribute_termName']); // Remove spaces from the field as ElasticSearch is unable to handle spaces
//				$attribute = $phenotype['attribute_termName']; 
				if ( $phenotype['value'] == '' ) { // In theory there should always be a value for a phenotype attribute but just in case default to present if there isn't one -- no longer needed as when indexing null in ElasticSearch the best way is to just not add the value
//					$phenotype_array[$attribute] = 'present';
//					$phenotype_array[$attribute] = 'NULL';
				}
				else {
					$phenotype_array_query['term_name'] = $phenotype['attribute_termName']; // Want to index the phenotype term name as it is so that it can be searched in standard google-like query interface		
					$phenotype_array[$attribute] = strtolower($phenotype['value']);
				}
			}
			$index_data['phenotypes'][] = $phenotype_array;
			$index_data['phenotypes'][] = $phenotype_array_query;
		}
		$index_data = json_encode($index_data);
		$index_data = str_replace( '\/', '/', $index_data ); // Don't want to escape forward slashes (as might be URLs)

		return $index_data;
		
	}
	
	// Get variant with phenotype - used for indexing elasticsearch and also updating elasticsearch index (with additional sharing_policy update change)
	public function getVariantWithPhenotypeJSON_old ($id) {
		$variant_query = $this->db->get_where('variants', array('cafevariome_id' => $id));
		$index_data = $variant_query->row_array();
//		error_log($variant_data);
//		$phenotype_query = $this->db->get_where('phenotypes', array('cafevariome_id' => $id))->result_array();
		$phenotype_query = $this->db->get_where('variants_to_phenotypes', array('cafevariome_id' => $id))->result_array();
//		error_log("test -> " . print_r($phenotype_query, 1));
		$phenotype_array = array();
		foreach ( $phenotype_query as $phenotype ) {
//			$phenotype_array['source_id'] = $phenotype['sourceId'];
//			$phenotype_array['term_id'] = $phenotype['termId'];
//			$phenotype_array['term_name'] = $phenotype['termName'];
			$phenotype_array['term_name'] = $phenotype['attribute_termName'];
			$index_data['phenotypes'][] = $phenotype_array;
		}
		
		$index_data = json_encode($index_data);
		$index_data = str_replace( '\/', '/', $index_data ); // Don't want to escape forward slashes (as might be URLs etc)
//		error_log($index_data);
		return $index_data;
		
	}
        
	public function getPhenotypes ($id) {
		$query = $this->db->get_where('phenotypes', array('cafevariome_id' => $id));
		$variantPhenotypes=array();
		foreach ($query->result() as $obj) {
			$phenoterm=$obj->attribute_termName;
			$phenosource=$obj->attribute_sourceID;
                        $termid=$obj->attribute_termID;
                        $value=$obj->value;
                        if ($value ==''){
                            $value='null';
                        }
                        if ($phenosource == 'LocalList'){
                            $query2 = $this->db->get_where('primary_phenotype_lookup', array('termId' => $termid));
                            foreach ($query2->result() as $obj2) {
                                $internalid=$obj2->id;                           
                                $displayphenoterm='<a href="../../discover/phenotype/'.$internalid.'">'.$phenoterm.'</a> ('.$phenosource.')|'.$value;
                                array_push($variantPhenotypes, $displayphenoterm);
                            }
                        }
                        else{                       
                            $displayphenoterm='<a href="'.$termid.'">'.$phenoterm.'</a> ('.$phenosource.')|'.$value;
                            array_push($variantPhenotypes, $displayphenoterm);
                        }
		}
		return $variantPhenotypes;     
   	}
        
        public function getPhenotypesList ($id) {
		$query = $this->db->get_where('phenotypes', array('cafevariome_id' => $id));
//		$variantPhenotypeString="<ul>";
		$terms = "";
		foreach ($query->result() as $obj) {
			$phenoterm=$obj->attribute_termName;
			$terms .= $phenoterm . ";";
//			$variantPhenotypeString=$variantPhenotypeString."<li>".$phenoterm."</li>";
//			error_log("$id -> $terms");
		}

		return $terms;
//		$variantPhenotypeString=$variantPhenotypeString."</ul>";
//		return $variantPhenotypeString;
   	}
	
	public function updateVariant ($data, $id) {
		$this->db->where('cafevariome_id', $id);
		$this->db->update('variants', $data);
//		error_log("last -> " . $this->db->last_query());
		return $this->db->affected_rows();
	}

	public function updateVariantByVariantID ($data, $variant_id) {
		$this->db->where('variant_id', $variant_id);
		$this->db->update('variants', $data);
//		error_log("last -> " . $this->db->last_query());
//		error_log("affected rows -> " . $this->db->affected_rows());
		return $this->db->affected_rows();		
	}
	
	public function getCafeVariomeIDForVariantID($variant_id) {
//		$this->db->select('cafevariome_id');
		$this->db->where('variant_id', $variant_id);
		$query = $this->db->get('variants');
//		error_log(print_r($query, 1));
		$row = $query->row_array();
		return $row['cafevariome_id'];
	}
	
	public function getAPIVariantsGene ($gene, $source, $limit, $offset) {
		$this->db->where('gene',$gene);
		$this->db->where('active', 1);
		$this->db->where('sharing_policy', 'openAccess');
		$this->db->where('source',$source);
		if ( $limit && $offset ) {
			$this->db->limit($limit, $offset);
		}
		else if ($limit) {
			$this->db->limit($limit);
		}
		$this->db->order_by("cafevariome_id", "desc"); 
		$query = $this->db->get('variants');
		$total_results = $query->num_rows();
		$variants = array();
		foreach ($query->result() as $variant) {
			$variant_data = array();
			foreach ( $variant as $key => $value ) {
//				error_log("$key $value<br />");
				if ( $value ) {
					if ( $key === "cafevariome_id" ) { 
						$variant_data[$key] = $this->config->item('cvid_prefix') . $value;
					}
					else {
						$variant_data[$key] = $value;
					}
				}
			}
			$uri = base_url("/discover/variant/" . $variant->cafevariome_id);
			$variant_data['uri'] = $uri;
//			$variants[$this->config->item('cvid_prefix') . $variant->cafevariome_id] = $variant_data;
			$variants[] = $variant_data;
//			$variants[$variant->cafevariome_id] = array( 'cvid' => $variant->cafevariome_id, 'gene' => $variant->gene, 'hgvs' => $variant->hgvs, 'ref' => $variant->ref ,'phenotype' => $variant->phenotype, 'uri' => $uri);
		}
		$v = array();
		$v['cafevariome'] = $variants;
//		error_log($total_results);
//		return $v;
		return $variants;
	}
	
	public function getAPIVariantsHGVSRef ($hgvs = NULL, $ref = NULL, $source, $limit, $offset) {
		
		if ( $hgvs ) {
			$this->db->where('hgvs',$hgvs);
		}
		
		if ( $ref ) {
			$this->db->where('ref',$ref);
		}
		$this->db->where('active', 1);
		$this->db->where('sharing_policy', 'openAccess');
		$this->db->where('source',$source);
		if ( $limit && $offset ) {
			$this->db->limit($limit, $offset);
		}
		else if ($limit) {
			$this->db->limit($limit);
		}
		$this->db->order_by("cafevariome_id", "desc"); 
		$query = $this->db->get('variants');
//		error_log("query -> " . $this->db->last_query());
		$total_results = $query->num_rows();
		$variants = array();
		foreach ($query->result() as $variant) {
			$variant_data = array();
			foreach ( $variant as $key => $value ) {
//				error_log("$key $value<br />");
				if ( $value ) {
					if ( $key === "cafevariome_id" ) { 
						$variant_data[$key] = $this->config->item('cvid_prefix') . $value;
					}
					else {
						$variant_data[$key] = $value;
					}
				}
			}
			$uri = base_url("/discover/variant/" . $variant->cafevariome_id);
			$variant_data['uri'] = $uri;
//			$variants[$variant->cafevariome_id] = $variant_data;
			$variants[] = $variant_data;
//			$variants[$variant->cafevariome_id] = array( 'cvid' => $variant->cafevariome_id, 'gene' => $variant->gene, 'hgvs' => $variant->hgvs, 'ref' => $variant->ref ,'phenotype' => $variant->phenotype, 'uri' => $uri);
		}
		$v = array();
		$v['cafevariome'] = $variants;
//		error_log($total_results);
		return $v;
	}

	public function deleteVariants ($source) {
		$this->db->delete('variants', array('source' => $source));
//		error_log($this->db->last_query());
	}

	public function deleteVariantByVariantID($id) {
		$this->db->delete('variants', array('variant_id' => $id));
//		error_log($this->db->last_query());
		if ( $this->db->affected_rows() ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	public function deleteVariant($id) {
		$this->db->delete('variants', array('cafevariome_id' => $id));
		if ( $this->db->affected_rows() ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}

	public function deleteVariantsMultiple($id) {
		$this->db->delete('variants', array('cafevariome_id' => $id)); 
	}
	
	function countSourceEntries() {
		$query = $this->db->query('SELECT COUNT( * ) as total, source FROM `variants` GROUP BY source');
		$source_counts = array();
		foreach ($query->result() as $r) {
			$source_counts[$r->source] = $r->total;
		}
		return $source_counts;
	}

	function countOnlineSourceEntries() {
		$sources = $this->getOnlineSourcesSingle();
		$query = $this->db->query("SELECT COUNT( * ) as total, source, active FROM `variants` WHERE active = 1 GROUP BY source");
		$source_counts = array();
		foreach ($query->result() as $r) {
			if ( isset($sources[$r->source])) {
				$source_counts[$r->source] = $r->total;
			}
		}
		return $source_counts;
	}
	
	function countAllVariants() {
		$query = $this->db->query("SELECT COUNT(*) as total FROM `variants`")->result_array();
//		error_log("counts -> " . print_r($query, 1) . " -> " . $query[0]['total']);
		return $query[0]['total'];
	}
	
	// Counts current online variants of the specified feature name e.g. gene
	function countFeature($feature_name) { 
//		SELECT COUNT( * ) AS total, gene FROM `variants` WHERE active = 1 AND source = ANY ( SELECT name FROM sources WHERE status= 'online' ) GROUP BY gene ORDER by total DESC
//		SELECT COUNT( * ) AS total, $feature_name FROM `variants` WHERE active = 1 GROUP BY $feature_name ORDER by total DESC
		$query = $this->db->query("SELECT COUNT( * ) AS total, $feature_name FROM `variants` WHERE active = 1 AND source = ANY ( SELECT name FROM sources WHERE status= 'online' ) GROUP BY $feature_name ORDER by total DESC LIMIT 50")->result_array();
		$feature_counts = array();
//		foreach ($query->result() as $r) {
////			print_r($r);
//			$feature_counts[$r->$feature_name] = $r->total;
//		}
		foreach ( $query as $feature ) {
//			print_r($feature);
			if (isset($feature[$feature_name]) && ! empty($feature[$feature_name]) ) {
				$feature_counts[$feature[$feature_name]] = $feature['total'];
			}
		}
//		print_r($query);
		return $feature_counts;
	}
			
	function countVariantsInSource($source) {
		$query = $this->db->get_where('variants', array('source' => $source));
		$count = $query->num_rows();
		return $count;
	}
	
	function cloneSource($source, $destination, $fields, $fields_replace) {
		$query = $this->db->query("INSERT INTO variants ($fields) SELECT $fields_replace FROM variants WHERE source = '$source'");
//		error_log("query -> " . $this->db->last_query());
		return $query;
	}
	
	function getSourceOwner($source) {
		$query = $this->db->get_where('sources', array('name' => $source));
		$row = $query->row_array();
		return $row;
	}
	
	function getSourceGroups($id = NULL) {
//		$this->db->select('*');
		$this->db->select('sources_groups.source_id, sources_groups.id, sources.name AS source_name, sources.description AS source_description, groups.name AS group_name, groups.description AS group_description, sources_groups.group_id');
//		$this->db->select('sources_groups.source_id, sources.name AS source_name, sources.description AS source_description, groups.name AS group_name, groups.description AS group_description');
//		$this->db->from('sources_groups, sources');
		$this->db->from('sources_groups');
//		$this->db->distinct();
		if ( isset ($id)) {
			$this->db->where('sources_groups.source_id',$id);
		}
		$this->db->join('groups', 'sources_groups.group_id = groups.id');
		$this->db->join('sources', 'sources_groups.source_id = sources.source_id');
		$query = $this->db->get();
		$groups = array();
		foreach ( $query->result() as $r ) {
			if ( isset ($id)) {
//				$groups[] = $r->group_id;
				$groups[$r->group_id] = array ( 'id' => $r->id, 'group_description' => $r->group_description, 'group_name' => $r->group_name, 'group_id' => $r->group_id );
				
			}
			else {
//				print_r($r);
				$groups[$r->id] = $r->group_description;
//				print "<br />";
			}
		}
		return $groups;
	}

	function getVariantsLimitOffset($limit, $offset) {
		$query = $this->db->get('variants', $limit, $offset)->result_array();
//		error_log("last query -> " . $this->db->last_query());
		return $query;
	}

	function getAllVariantIDs() {
		$this->db->select('cafevariome_id');
		$query = $this->db->get('variants')->result_array();
		return $query;
	}
	
	function getSourceCurators($id = NULL) {
		$query = $this->db->get_where('curators', array('source_id' => $id));
//		error_log($this->db->last_query());
		$curators = array();
		foreach ( $query->result() as $row ) {
			$curators[$row->user_id] = $row->source_id;
		}
		return $curators;
	}
        
        
        function getOntologiesUsedAndRoots() {
              //  $this->db->distinct();
                $this->db->group_by("ontology"); 
		$query = $this->db->get('pheno_dag');
           //    error_log($this->db->last_query());
		$ontologies = array();
		foreach ( $query->result() as $row ) {
                        $source=$row->ontology;
                        $query2 = $this->db->get_where('pheno_dag', array('ontology' => $source, 'parentid' => '1', 'terminalnode' => '0'));
                     //   error_log($this->db->last_query());
                        $topnodes = array();
                        foreach ( $query2->result() as $row2 ) {
                            
                            //$ontologies[$row->sourceId] = $row->sourceId;
                            array_push($topnodes,$row2->termid);
                        }
                        $ontologies[$source] = $topnodes;
                }
		return $ontologies;
	}
	
        
        function lookupOntNode($queryString,$ont) {
		$query = $this->db->query("SELECT termid, termname, terminalnode FROM pheno_dag WHERE ontology='$ont' AND parentid='$queryString' ORDER BY termname");
		foreach ($query->result() as $obj) {
                    $outputname = $obj->termname;
		    $displayname = str_replace("'", "&#8217;", $outputname);
                    $outputid = $obj->termid;
                    // Take care of the hash character that is present in some ids e.g. FMA
		    $displayid=str_replace("#","XhashX",$outputid);
                    
		    $terminalnode = $obj->terminalnode;
                    $thislabel=$ont.'_label';
		    $thisorigin=$ont.'_origin';
                    if ($terminalnode=='1'){
                        $results[] = array('attr' => array('id' => $displayid, $thislabel => $displayname, $thisorigin => $ont), 'data' => $displayname);
                    }
                    else{
                        $results[] = array('attr' => array('id' => $displayid, $thislabel => $displayname, $thisorigin => $ont), 'data' => $displayname, 'state' => "closed");
                    }
                }
                
                return $results;
	}
        
        
	function deleteSourceCurators($id = NULL) {
		$this->db->delete('curators', array('source_id' => $id));
	}
	
	function insertSourceCurator($curator_data) {
		$this->db->insert('curators', $curator_data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	function deleteCuratorFromSource($user_id, $source_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('source_id', $source_id);
		$this->db->delete('curators'); 
	}
	
	public function getSourcesThatTheUserCanCurate ($user_id) {
		$this->db->select('*');
		$this->db->from('sources');
		$this->db->join('curators', "curators.source_id = sources.source_id and curators.user_id = $user_id", 'inner');
//		SELECT * FROM sources INNER JOIN curators ON curators.source_id = sources.source_id and curators.user_id =1
		$query = $this->db->get();
//		error_log($this->db->last_query());
		return $query;
	}
	
	public function checkUserCanCurateThisSource ($source_id, $user_id) {
		$where = "source_id = '$source_id' AND user_id = '$user_id'";
		$this->db->where($where);
		$query = $this->db->get('curators');
		$count = $query->num_rows();
		return $count;
	}

	
	public function getDataRequestsForSource($source) {
		$this->db->where('source', $source); 
		$query = $this->db->get('data_requests')->result_array();
//		error_log("requests -> " . print_r($query,1));
//		$curateable_sources = array();
//		foreach ($query->result() as $source) {
////			error_log("source -> " . print_r($source, 1));
//			$curateable_sources[$source->name] = $source->description;
//		}
//		return $curateable_sources;
		return $query;
	}
	
	/**
	 * get_source_groups
	 *
	 * @return array
	 * @author Owen Lancaster
	 **/
	public function get_sources_groups($id)
	{
//		$this->trigger_events('get_sources_group');

		$query = $this->db->select($this->tables['sources_groups'].'.'.$this->join['groups'].' as id, '.$this->tables['groups'].'.name, '.$this->tables['groups'].'.description')
		                ->where($this->tables['sources_groups'].'.'.$this->join['users'], $id)
		                ->join($this->tables['groups'], $this->tables['sources_groups'].'.'.$this->join['groups'].'='.$this->tables['groups'].'.id')
		                ->get($this->tables['sources_groups']);
//		error_log("query -> " . $this->db->last_query());
		return $query;
	}

	/**
	 * add_to_group
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function add_to_sources_group($group_id, $source_id) {
		$this->db->insert('sources_groups', array ('group_id' => $group_id, 'source_id' => $source_id));
	}

	/**
	 * remove_from_group
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function remove_sources_from_group($group_ids=false, $source_id=false) {

		// source id is required
		if(empty($source_id)) {
			return FALSE;
		}

		// if group id(s) are passed remove source from the group(s)
		if( ! empty($group_ids)) {
			if(!is_array($group_ids)) {
				$group_ids = array($group_ids);
			}

			foreach($group_ids as $group_id) {
				$this->db->delete('sources_groups', array('group_id' => $group_id, 'source_id' => $source_id));
//				error_log($this->db->last_query());
			}
//			$return = TRUE;
		}
		// otherwise remove user from all groups
		else {
			$this->db->delete('sources_groups', array( 'source_id' => $source_id));
//			error_log($this->db->last_query());
			return TRUE;
		}
//		return $return;
	}

	
	public function register_and_add_to_group($password, $email, $group) {
		$result_flag = TRUE;
		$data = array(
		    'password'   => $password,
		    'email'      => $email,
//		    'ip_address' => $ip_address,
		    'created_on' => time(),
		    'last_login' => time(),
		    'active'     => "0"
		);
		$this->db->insert('users', $data);
//		error_log($this->db->last_query());
		$uid = $this->db->insert_id();
		if ( ! $uid ) {
			$result_flag = FALSE;
		}

		$group_data = array(
			'user_id' => $uid,
			'group_id' => $group
		);
		$this->db->insert('users_groups', $group_data);
//		error_log($this->db->last_query());
		$gid = $this->db->insert_id();
		if ( ! $gid ) {
			$result_flag = FALSE;
		}

		return $result_flag;
	}
	
	public function getCentralSources () {
		$query = $this->db->get_where('sources', array('type' => 'central'));
		$sources = array();
		foreach ($query->result() as $source) {
			$sources[$source->name]['source_name'] = $source->name;
			$sources[$source->name]['source_description'] = $source->description;
			$sources[$source->name]['source_uri'] = $source->uri;
		}
		return $sources;
	}
	
	public function is_email_present($email) {
		$query = $this->db->get_where('users', array('email' => $email));
		$is_email_present = $query->num_rows();
		return $is_email_present;
	}
	
	function is_md5_valid($md5) {
		$query = $this->db->get_where('users', array('password' => $md5, 'active' => "0"));
		return $query;
	}
	
}
