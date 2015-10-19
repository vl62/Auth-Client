<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  Query Model
*
* Author:  Owen Lancaster
* 		   ol8@leicester.ac.uk
*
*/

class Query_model extends CI_Model {

	function countVariantsForRegion($locations, $source, $mutalyzer_check = NULL) {
		$chr = $locations['chr'];
		$start = $locations['start'];
		$end = $locations['end'];
//		print "source -> $source <br />";
		$this->db->select('sharing_policy, count(*) as count');
		$this->db->where('source', $source);
		$this->db->where('location_ref', $chr);
		$this->db->where('start >=', $start);
		$this->db->where('end <=', $end);
		$this->db->where('active', 1);
		if ( $mutalyzer_check === true ) {
			$this->db->where('mutalyzer_check', 1);
		}
		$this->db->group_by('sharing_policy');
		$query = $this->db->get('variants');
//		$query = $this->db->query("SELECT `sharing_policy`, count(*) FROM (`variants`) WHERE `source` = '$source' AND `location_ref` = '$chr' AND `start` >= '$start' AND `end` <= '$end' GROUP BY `sharing_policy`");
//		error_log($this->db->last_query());
		
		$sharing_counts = array();
		foreach ($query->result() as $r) {
//			print_r($r);
//			print "$source -> " . $r->sharing_policy . " -> " . $r->count . "<br />";
			$sharing_counts[$r->sharing_policy] = $r->count;
		}

		return($sharing_counts);
	}
	
	function getVariantsForRegion($locations, $source, $sharing_policy = NULL) {
		$chr = $locations['chr'];
		$start = $locations['start'];
		$end = $locations['end'];
//		print "source -> $source <br />";
		$this->db->where('source', $source);
		$this->db->where('location_ref', $chr);
		$this->db->where('start >=', $start);
		$this->db->where('end <=', $end);
		$this->db->where('active', 1);
		if ( isset ($sharing_policy)) {
			$this->db->where('sharing_policy', $sharing_policy);
		}
		else {
			$this->db->where('sharing_policy', 'openAccess');
		}
		$query = $this->db->get('variants');
//		error_log($this->db->last_query());
		$variants = array();
		// Store all the returned columns and associated value in the array
		foreach ($query->result() as $variant) {
			$variant_data = array();
			foreach ( $variant as $key => $value ) {
//				error_log("$key $value<br />");
//				if ( $value ) {
					$variant_data[$key] = $value;
//				}
			}
			$uri = base_url("/discover/variant/" . $variant->cafevariome_id);
			$variant_data['uri'] = $uri;
			$variants[$this->config->item('cvid_prefix') . $variant->cafevariome_id] = $variant_data;
//			$variants[$variant->cafevariome_id] = array( 'cvid' => $variant->cafevariome_id, 'gene' => $variant->gene, 'hgvs' => $variant->hgvs, 'ref' => $variant->ref ,'phenotype' => $variant->phenotype, 'uri' => $uri);
		}
//		print_r($variants);
		return $variants;
	}

	function getVariantsForGene($gene, $source, $sharing_policy) {
//		print "source -> $source <br />";
		$this->db->where('source', $source);
		$this->db->where('gene', $gene);
		$this->db->where('active', 1);
		if ( isset ($sharing_policy)) {
			$this->db->where('sharing_policy', $sharing_policy);
		}
		else {
			$this->db->where('sharing_policy', 'openAccess');
		}
		
		$query = $this->db->get('variants');
		
		$variants = array();
		foreach ($query->result() as $variant) {
			$variant_data = array();
			foreach ( $variant as $key => $value ) {
//				error_log("$key $value<br />");
				if ( $value ) {
					$variant_data[$key] = $value;
				}
			}
			$uri = base_url("/discover/variant/" . $variant->cafevariome_id);
			$variant_data['uri'] = $uri;
			$variants[$this->config->item('cvid_prefix') . $variant->cafevariome_id] = $variant_data;
		}
//		foreach ($query->result() as $r) {
//			$variants[$r->cafevariome_id] = array('cafevariome_id' => $r->cafevariome_id, 'gene' => $r->gene, 
//												  'ref' => $r->ref, 'hgvs' => $r->hgvs, 'phenotype' => $r->phenotype, 
//												  'phenotype_omim' => $r->phenotype_omim, 'location_ref' => $r->location_ref, 
//												  'start' => $r->start, 'end' => $r->end, 'build' => $r->build, 
//												  'sharing_policy' => $r->sharing_policy, 'source' => $r->source,
//												  'source_url' => $r->source_url, 'comment' => $r->comment,
//												  'individual_id' => $r->individual_id, 'gender' => $r->gender,
//												  'date_time' => $r->date_time);
//		}
//		print_r($variants);
		return $variants;
	}
	
	function countVariantsForGene($gene, $source, $mutalyzer_check = NULL) {
		$this->db->select('sharing_policy, count(*) as count');
		$this->db->where('source', $source);
		$this->db->where('gene', $gene);
		$this->db->where('active', 1);
		if ( $mutalyzer_check === true ) {
			$this->db->where('mutalyzer_check', 1);
		}
		$this->db->group_by('sharing_policy');
		$query = $this->db->get('variants');
//		error_log($this->db->last_query());
//		$query = $this->db->query("SELECT `sharing_policy`, count(*) FROM (`variants`) WHERE `source` = '$source' AND `location_ref` = '$chr' AND `start` >= '$start' AND `end` <= '$end' GROUP BY `sharing_policy`");
		$sharing_counts = array();
		foreach ($query->result() as $r) {
//			print_r($r);
//			print "$source -> " . $r->sharing_policy . " -> " . $r->count . "<br />";
			$sharing_counts[$r->sharing_policy] = $r->count;
		}
//		error_log($this->db->last_query());
		return($sharing_counts);
	}

	function countVariantsForLRG($lrg, $source, $mutalyzer_check = NULL) {
		$this->db->select('sharing_policy, count(*) as count');
		$this->db->where('source', $source);
		$this->db->where('LRG', $lrg);
		$this->db->where('active', 1);
		if ( $mutalyzer_check === true ) {
			$this->db->where('mutalyzer_check', 1);
		}
		$this->db->group_by('sharing_policy');
		$query = $this->db->get('variants');
		$sharing_counts = array();
		foreach ($query->result() as $r) {
			$sharing_counts[$r->sharing_policy] = $r->count;
		}
		return($sharing_counts);
	}

	function getVariantsForLRG($lrg, $source, $sharing_policy = NULL) {
//		print "source -> $source <br />";
		$this->db->where('source', $source);
		$this->db->where('LRG', $lrg);
		$this->db->where('active', 1);
		if ( isset ($sharing_policy)) {
			$this->db->where('sharing_policy', $sharing_policy);
		}
		else {
			$this->db->where('sharing_policy', 'openAccess');
		}
		$query = $this->db->get('variants');
		$variants = array();
		foreach ($query->result() as $variant) {
			$variant_data = array();
			foreach ( $variant as $key => $value ) {
//				error_log("$key $value<br />");
				if ( $value ) {
					$variant_data[$key] = $value;
				}
			}
			$uri = base_url("/discover/variant/" . $variant->cafevariome_id);
			$variant_data['uri'] = $uri;
			$variants[$this->config->item('cvid_prefix') . $variant->cafevariome_id] = $variant_data;
		}
		return $variants;
	}

	function countVariantsFordbSNP($rsid, $source, $mutalyzer_check = NULL) {
		$this->db->select('sharing_policy, count(*) as count');
		$this->db->where('source', $source);
		$this->db->where('dbsnp_id', $rsid);
		$this->db->where('active', 1);
		if ( $mutalyzer_check === true ) {
			$this->db->where('mutalyzer_check', 1);
		}
		$this->db->group_by('sharing_policy');
		$query = $this->db->get('variants');
		$sharing_counts = array();
		foreach ($query->result() as $r) {
			$sharing_counts[$r->sharing_policy] = $r->count;
		}
		return($sharing_counts);
	}

	function getVariantsFordbSNP($rsid, $source, $sharing_policy = NULL) {
//		print "source -> $source <br />";
		$this->db->where('source', $source);
		$this->db->where('dbsnp_id', $rsid);
		$this->db->where('active', 1);
		if ( isset ($sharing_policy)) {
			$this->db->where('sharing_policy', $sharing_policy);
		}
		else {
			$this->db->where('sharing_policy', 'openAccess');
		}
		$query = $this->db->get('variants');
		$variants = array();
		foreach ($query->result() as $variant) {
			$variant_data = array();
			foreach ( $variant as $key => $value ) {
//				error_log("$key $value<br />");
				if ( $value ) {
					$variant_data[$key] = $value;
				}
			}
			$uri = base_url("/discover/variant/" . $variant->cafevariome_id);
			$variant_data['uri'] = $uri;
			$variants[$this->config->item('cvid_prefix') . $variant->cafevariome_id] = $variant_data;
		}
		return $variants;
	}
	
	function countVariantsForRefHGVS($ref_hgvs, $source, $mutalyzer_check = NULL) {
		$this->db->select('sharing_policy, count(*) as count');
		$this->db->where('source', $source);
		$this->db->where('ref', $ref_hgvs['ref']);
		$this->db->where('hgvs', urldecode($ref_hgvs['hgvs']));
		$this->db->where('active', 1);
		if ( $mutalyzer_check === true ) {
			$this->db->where('mutalyzer_check', 1);
		}
		$this->db->group_by('sharing_policy');
		$query = $this->db->get('variants');
//		$query = $this->db->query("SELECT `sharing_policy`, count(*) FROM (`variants`) WHERE `source` = '$source' AND `location_ref` = '$chr' AND `start` >= '$start' AND `end` <= '$end' GROUP BY `sharing_policy`");
		$sharing_counts = array();
		foreach ($query->result() as $r) {
//			print_r($r);
//			print "$source -> " . $r->sharing_policy . " -> " . $r->count . "<br />";
			$sharing_counts[$r->sharing_policy] = $r->count;
		}
//		error_log($this->db->last_query());
		return($sharing_counts);
	}

	function getVariantsForRefHGVS($ref_hgvs, $source, $sharing_policy = NULL) {
//		print "source -> $source <br />";
		$this->db->where('source', $source);
		$this->db->where('ref', $ref_hgvs['ref']);
		$this->db->where('hgvs', urldecode($ref_hgvs['hgvs']));
		$this->db->where('active', 1);
		if ( isset ($sharing_policy)) {
			$this->db->where('sharing_policy', $sharing_policy);
		}
		else {
			$this->db->where('sharing_policy', 'openAccess');
		}
		$query = $this->db->get('variants');
//		error_log($this->db->last_query());
		$variants = array();
		foreach ($query->result() as $variant) {
			$variant_data = array();
			foreach ( $variant as $key => $value ) {
				if ( $value ) {
					$variant_data[$key] = $value;
				}
			}
			$uri = base_url("/discover/variant/" . $variant->cafevariome_id);
			$variant_data['uri'] = $uri;
			$variants[$this->config->item('cvid_prefix') . $variant->cafevariome_id] = $variant_data;
		}

		return $variants;
	}
	
	function countVariantsForRef($ref, $source, $mutalyzer_check = NULL) {
		$this->db->select('sharing_policy, count(*) as count');
		$this->db->where('source', $source);
		$this->db->where('ref', $ref);
		$this->db->where('active', 1);
		if ( $mutalyzer_check === true ) {
			$this->db->where('mutalyzer_check', 1);
		}
		$this->db->group_by('sharing_policy');
		$query = $this->db->get('variants');
//		$query = $this->db->query("SELECT `sharing_policy`, count(*) FROM (`variants`) WHERE `source` = '$source' AND `location_ref` = '$chr' AND `start` >= '$start' AND `end` <= '$end' GROUP BY `sharing_policy`");
		$sharing_counts = array();
		foreach ($query->result() as $r) {
//			print_r($r);
//			print "$source -> " . $r->sharing_policy . " -> " . $r->count . "<br />";
			$sharing_counts[$r->sharing_policy] = $r->count;
		}
//		error_log($this->db->last_query());
		return($sharing_counts);
	}

	function getVariantsForRef($ref, $source, $sharing_policy = NULL) {
//		print "source -> $source <br />";
		$this->db->where('source', $source);
		$this->db->where('ref', $ref);
		$this->db->where('active', 1);
		if ( isset ($sharing_policy)) {
			$this->db->where('sharing_policy', $sharing_policy);
		}
		else {
			$this->db->where('sharing_policy', 'openAccess');
		}
		$query = $this->db->get('variants');
		$variants = array();
		foreach ($query->result() as $variant) {
			$variant_data = array();
			foreach ( $variant as $key => $value ) {
				if ( $value ) {
					$variant_data[$key] = $value;
				}
			}
			$uri = base_url("/discover/variant/" . $variant->cafevariome_id);
			$variant_data['uri'] = $uri;
			$variants[$this->config->item('cvid_prefix') . $variant->cafevariome_id] = $variant_data;
		}

		return $variants;
	}

	function countVariantsForHGVS($hgvs, $source, $mutalyzer_check = NULL) {
		$this->db->select('sharing_policy, count(*) as count');
		$this->db->where('source', $source);
		$this->db->where('hgvs', $hgvs);
		$this->db->where('active', 1);
		if ( $mutalyzer_check === true ) {
			$this->db->where('mutalyzer_check', 1);
		}
		$this->db->group_by('sharing_policy');
		$query = $this->db->get('variants');
//		$query = $this->db->query("SELECT `sharing_policy`, count(*) FROM (`variants`) WHERE `source` = '$source' AND `location_ref` = '$chr' AND `start` >= '$start' AND `end` <= '$end' GROUP BY `sharing_policy`");
		$sharing_counts = array();
		foreach ($query->result() as $r) {
//			print_r($r);
//			print "$source -> " . $r->sharing_policy . " -> " . $r->count . "<br />";
			$sharing_counts[$r->sharing_policy] = $r->count;
		}
//		error_log($this->db->last_query());
		return($sharing_counts);
	}

	function getVariantsForHGVS($hgvs, $source, $sharing_policy = NULL) {
//		print "source -> $source <br />";
		$this->db->where('source', $source);
		$this->db->where('hgvs', $hgvs);
		$this->db->where('active', 1);
		if ( isset ($sharing_policy)) {
			$this->db->where('sharing_policy', $sharing_policy);
		}
		else {
			$this->db->where('sharing_policy', 'openAccess');
		}
		$query = $this->db->get('variants');
		$variants = array();
		foreach ($query->result() as $variant) {
			$variant_data = array();
			foreach ( $variant as $key => $value ) {
				if ( $value ) {
					$variant_data[$key] = $value;
				}
			}
			$uri = base_url("/discover/variant/" . $variant->cafevariome_id);
			$variant_data['uri'] = $uri;
			$variants[$this->config->item('cvid_prefix') . $variant->cafevariome_id] = $variant_data;
		}

		return $variants;
	}

	function countDASFeaturesForRegion($uri) {
		$das_xml =  simplexml_load_file($uri);
		$sharing_counts = array();
		$count = 0;
		foreach ( $das_xml->GFF->SEGMENT->FEATURE as $feature ) {
			$count++;
			$type = $feature->TYPE;
			$method = $feature->METHOD;
			$id = $feature->ID; // in SNPedia the ID is lowercase - XML is case sensitive so need to find a way around this
			$start = $feature->START;
			$end = $feature->END;
			$score = $feature->SCORE;
			$orientation = $feature->ORIENTATION;
			$phase = $feature->PHASE;
			$note = $feature->NOTE;
			$link = $feature->LINK;
//			error_log("$id $start $end $score $orientation $note $link $type $method $phase");
//			error_log(print_r($feature, 1));
		}
		if ( $count ) { // Only add the count to array if there's some features present
			$sharing_counts['linkedAccess'] = $count;
		}
		return($sharing_counts);
	}
	
	function getDASFeaturesForRegion($uri) {
		$das_xml =  simplexml_load_file($uri);
		$features = array();
		
		$count = 0;
		foreach ( $das_xml->GFF->SEGMENT->FEATURE as $feature ) {
			$count++;
			$feature_data = array();
			$type = $feature->TYPE;
			$method = $feature->METHOD;
			$id = $feature->ID; // in SNPedia the ID is lowercase - XML is case sensitive so need to find a way around this
			$start = $feature->START;
			$end = $feature->END;
			$score = $feature->SCORE;
			$orientation = $feature->ORIENTATION;
			$phase = $feature->PHASE;
			$note = $feature->NOTE;
			$link = $feature->LINK;
			$feature_data['type'] = $type;
			$feature_data['method'] = $method;
			$feature_data['start'] = $start;
			$feature_data['end'] = $end;
			$feature_data['score'] = $score;
			$feature_data['orientation'] = $orientation;
			$feature_data['phase'] = $phase;
			$feature_data['note'] = $note;
			$feature_data['link'] = $link;
			if ( $id ) {
				$features[$id] = $feature_data;
			}
			else {
				$id = MD5(microtime()); // Create a random ID if one doesn't exist in the DAS xml
				$features[$id] = $feature_data;
			}
//			error_log(print_r($feature, 1));
			
		}
		
		return $features;
	}
	
	function getVariantsForSource($source) {
//		print "source -> $source <br />";
		$this->db->where('source', $source);
		$this->db->where('active', 1);
		$query = $this->db->get('variants');
		$variants = array();
		foreach ($query->result() as $r) {
//			error_log("r -> " . print_r($r, 1));
			$variants[$r->cafevariome_id] = (array) $r;

//			$variants[$r->cafevariome_id] = array('cafevariome_id' => $r->cafevariome_id, 'gene' => $r->gene, 
//												  'ref' => $r->ref, 'hgvs' => $r->hgvs, 'phenotype' => $r->phenotype, 
//												  'location_ref' => $r->location_ref, 
//												  'start' => $r->start, 'end' => $r->end, 'build' => $r->build, 
//												  'sharing_policy' => $r->sharing_policy, 'source' => $r->source,
//												  'source_url' => $r->source_url, 'comment' => $r->comment,
//												  'individual_id' => $r->individual_id, 'gender' => $r->gender,
//												  'date_time' => $r->date_time);
		}
//		print_r($variants);
		return $variants;
	}
	
	function getVariantsForSourceWithPhenotypes($source) {
//		print "source -> $source <br />";
		$this->db->where('source', $source);
		$this->db->where('active', 1);
		$query = $this->db->get('variants');
		$variants = array();
		foreach ($query->result() as $r) {
//			error_log("r -> " . print_r($r, 1));
			$variant = (array) $r;
			$q = $this->db->get_where('phenotypes', array('cafevariome_id' => $r->cafevariome_id));
			

			if ( $q->num_rows() > 0 ) {
//				$variantPhenotypeString="<ul style='list-style-type: none;'>";
				$variantPhenotypeString="<ul>";
				foreach ($q->result() as $obj) {
					$phenoterm=$obj->attribute_termName;
                                        $phenoval=$obj->value;
					$variantPhenotypeString=$variantPhenotypeString."<li>".$phenoterm."; ".$phenoval."</li>";
				}
				$variantPhenotypeString=$variantPhenotypeString."</ul>";
//				error_log("> " . $variantPhenotypeString);
				$variant['phenotype'] = $variantPhenotypeString;
			}
			
//			$terms = "";
//			foreach ($q->result() as $obj) {
//				$phenoterm=$obj->termName;
//				$terms .= $phenoterm . ";";
//			}
//			if ( $terms !== "") {
////				error_log("adding -> " . $terms);
//				$variant['phenotype'] = $terms;
//			}
			
			$variants[$r->cafevariome_id] = $variant;

			

			
//			$variants[$r->cafevariome_id] = array('cafevariome_id' => $r->cafevariome_id, 'gene' => $r->gene, 
//												  'ref' => $r->ref, 'hgvs' => $r->hgvs, 'phenotype' => $r->phenotype, 
//												  'location_ref' => $r->location_ref, 
//												  'start' => $r->start, 'end' => $r->end, 'build' => $r->build, 
//												  'sharing_policy' => $r->sharing_policy, 'source' => $r->source,
//												  'source_url' => $r->source_url, 'comment' => $r->comment,
//												  'individual_id' => $r->individual_id, 'gender' => $r->gender,
//												  'date_time' => $r->date_time);
		}
//		print_r($variants);
		return $variants;
	}
	
    function fulltextSearch($terms) {
        $sql = "SELECT *, MATCH(phenotype) AGAINST ('dysplasia') AS score 
		FROM variants
		WHERE MATCH(phenotype) AGAINST ('dysplasia')";
        $query = $this->db->query($sql, array($terms, $terms));
        return $query->result();
    }
	
	function getSearchHistory($user_id) {
		$query = $this->db->get_where('stats_searches', array('user' => $user_id))->result_array();
//		error_log("last -> " . $this->db->last_query());
		return $query;

	}
	
	function lookupAutocomplete($keyword) {
		$query = $this->db->query("SELECT * FROM autocomplete WHERE term LIKE '$keyword%' ORDER BY term+0 LIMIT 10");
//		$this->db->like('term', $keyword);
//		$this->db->order_by("term+0");
//		$this->db->limit(10);
//		$query = $this->db->get('term, type');
//		foreach ($query->result() as $r) {
//			error_log($r->term);
//		}
		return $query;
	}
	
        function lookupPhenoAutocomplete($queryString) {
		$query = $this->db->query("SELECT * FROM primary_phenotype_lookup, ontology_list WHERE primary_phenotype_lookup.sourceId=ontology_list.abbreviation AND termName LIKE '%$queryString%' ORDER BY ranking");
		foreach ($query->result() as $obj) {
                    $outputname = $obj->termName;
		    $displayname = str_replace("'", "&#8217;", $outputname);
		    $outputtype = $obj->sourceId;
		    $outputid = $obj->termId;
                    $outputqualifier = $obj->qualifier;
                    if ($outputqualifier ==''){
                        $outputqualifier='awol';
                    }
                    $results[] = array('label' => $outputname, 'category' => $outputtype, 'identifier' => $outputid,  'qualifier' => $outputqualifier);
                }
                
                return $results;
        }
	public function getQueryBuilderHistorySingle ($query_id, $endpoint) {
		$query = $this->db->get_where('query_builder_history', array('query_id' => $query_id, 'endpoint' => $endpoint));
		$row = $query->row_array();
//		echo $this->db->last_query() . "\n";
		return $row;

	}
        
}
