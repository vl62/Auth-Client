<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  AtomServer Model
*
* Author:  Owen Lancaster
* 		   ol8@leicester.ac.uk
*
*/

class Atomserver_model extends CI_Model {
	
	public function getAtomServerData ($source_uri, $atomserver_user, $atomserver_password) {
		$context = stream_context_create(array(
			'http' => array( 'header'  => "Authorization: Basic " . base64_encode("$atomserver_user:$atomserver_password") )
		));
		$submission_xml = @file_get_contents($source_uri, false, $context);
		$error = array();
		if($submission_xml === FALSE) {
			$error['connect'] = "Couldn't connect to AtomServer to retrieve submissions, check the settings and try again";
			return $error;
		}
//		print_r($submission_xml);
		$submission_data = array();
		if ( $entryxml = @simplexml_load_string($submission_xml ) ) {
			$entry_count = count($entryxml->entry);
//			echo "entries -> $entry_count<br />";
			foreach ($entryxml->entry as $entry ) {
				$id = (string) $entry->title;
				if ( $entry->content->deletion ) { // If entry has been flagged for deletion skip it and don't include
//					print "deleted $id<br />";
					continue;
				}

				$submission_data[$id]['created'] = $entry->published;
				$submission_data[$id]['updated'] = $entry->updated;
				$submission_data[$id]['author'] = $entry->content->cafe_variome->source->contact->name;
				$submission_data[$id]['email'] = $entry->content->cafe_variome->source->contact->email;
				foreach ($entry->category as $cat) {
					if ( $cat->attributes()->scheme == "sharing_policy" ) {
						$submission_data[$id]['sharing_policy'] = (string) $cat->attributes()->term;
					}
				}
				$submission_data[$id]['ref'] = $entry->content->cafe_variome->variant->ref_seq['accession'];
				$submission_data[$id]['hgvs'] = $entry->content->cafe_variome->variant->name;
				$submission_data[$id]['gene'] = $entry->content->cafe_variome->variant->gene['accession'];
				$submission_data[$id]['laboratory_id'] = $entry->content->cafe_variome->source['id'];
				$submission_data[$id]['phenotype'] = $entry->content->cafe_variome->variant->panel->phenotype['term'];
				$submission_data[$id]['phenotype_omim'] = $entry->content->cafe_variome->variant->panel->phenotype['accession'];
				$submission_data[$id]['phenotype_source'] = $entry->content->cafe_variome->variant->panel->phenotype['source'];
				$submission_data[$id]['pathogenicity'] = $entry->content->cafe_variome->variant->pathogenicity['term'];
				$submission_data[$id]['build'] = $entry->content->cafe_variome->variant->location->ref_seq['accession'];
				$submission_data[$id]['location_ref'] = $entry->content->cafe_variome->variant->location->chr;
				$submission_data[$id]['start'] = $entry->content->cafe_variome->variant->location->start; 
				$submission_data[$id]['end'] = $entry->content->cafe_variome->variant->location->end;
				

				if ( is_array($entry->content->cafe_variome->comment) ) {
//					print_r($entry->content->cafe_variome->comment->text);
//					print "array!!<br />";
				}

				if ( isset($entry->content->cafe_variome->variant->panel->individual->gender['code']) ) {
					$submission_data[$id]['gender'] = $this->_getGenderFromGenderCode($entry->content->cafe_variome->variant->panel->individual->gender['code']);
				}
				$submission_data[$id]['individual_id'] = $entry->content->cafe_variome->variant->panel['id'];
				
				foreach ($entry->content->cafe_variome->variant->seq_changes as $seq_change) {
					if ( $seq_change->variant->name->attributes()->type == "AA" ) {
						$hgvs_protein = $seq_change->variant->name;
						$submission_data[$id]['hgvs_protein'] = $seq_change->variant->name;
						$submission_data[$id]['protein_var_id'] = $seq_change->variant->attributes()->id;
						$submission_data[$id]['protein_ref_seq'] = $seq_change->variant->ref_seq['accession'];
					}
				}
				$submission_data[$id]['comment'] = $entry->content->cafe_variome->comment->text;
			}
			
//			print_r($submission_data);
			return $submission_data;
		}
		else {
			return FALSE;
		}
	}

	public function getAtomServerEntry ($id, $source_uri, $atomserver_user, $atomserver_password) {
		$context = stream_context_create(array(
			'http' => array( 'header'  => "Authorization: Basic " . base64_encode("$atomserver_user:$atomserver_password") )
		));
		$entry_uri = $source_uri . "/$id.xml";
		$entry_xml = file_get_contents($entry_uri, false, $context);
//		print_r($entry_xml);
		$entry_data = array();
		if ( $entry = @simplexml_load_string($entry_xml ) ) {
//			print_r($entry);
			$id = (string) $entry->title;
			if ( $entry->content->deletion ) { // If entry has been flagged for deletion skip it and don't include
//				print "deleted $id<br />";
				continue;
			}
			$entry_data['date_time'] = (string) $entry->published;
//			$entry_data['updated'] = $entry->updated;
//			$entry_data['author'] = $entry->content->cafe_variome->source->contact->name;
//			foreach ($entry->category as $cat) {
//				if ( $cat->attributes()->scheme == "sharing_policy" ) {
//					$entry_data['sharing_policy'] = (string) $cat->attributes()->term;
//				}
//			}
			$entry_data['source'] = "diagnostic";
			$entry_data['laboratory'] = "diagnostic";
			$entry_data['hgvs'] = (string) $entry->content->cafe_variome->variant->name;
			$entry_data['ref'] = (string) $entry->content->cafe_variome->variant->ref_seq['accession'];
			$entry_data['gene'] = (string) $entry->content->cafe_variome->variant->gene['accession'];
//			$entry_data['laboratory_id'] = (string) $entry->content->cafe_variome->source['id'];
			$entry_data['phenotype'] = (string) $entry->content->cafe_variome->variant->panel->phenotype['term'];
			$entry_data['phenotype_omim'] = (string) $entry->content->cafe_variome->variant->panel->phenotype['accession'];
//			$entry_data['phenotype_source'] = (string) $entry->content->cafe_variome->variant->panel->phenotype['source'];
			$entry_data['pathogenicity'] = (string) $entry->content->cafe_variome->variant->pathogenicity['term'];
			$entry_data['build'] = (string) $entry->content->cafe_variome->variant->location->ref_seq['accession'];
			$entry_data['location_ref'] = (string) $entry->content->cafe_variome->variant->location->chr;
			$entry_data['start'] = (string) $entry->content->cafe_variome->variant->location->start; 
			$entry_data['end'] = (string) $entry->content->cafe_variome->variant->location->end;
				
			$entry_data['individual_id'] = (string) $entry->content->cafe_variome->variant->panel['id'];
			$entry_data['sharing_policy'] = (string) $entry->content->cafe_variome->variant->sharing_policy['type'];
//			$entry_data['email'] = $entry->content->cafe_variome->source->contact->email;
//			if ( is_array($entry->content->cafe_variome->comment) ) {
//				print_r($entry->content->cafe_variome->comment->text);
//				print "array!!<br />";
//			}
			$entry_data['comment'] = (string) $entry->content->cafe_variome->comment->text;
			if ( isset($entry->content->cafe_variome->variant->panel->individual->gender['code']) ) {
				$entry_data['gender'] = $this->_getGenderFromGenderCode($entry->content->cafe_variome->variant->panel->individual->gender['code']);
			}
				
			foreach ($entry->content->cafe_variome->variant->seq_changes as $seq_change) {
				if ( $seq_change->variant->name->attributes()->type == "AA" ) {
					$entry_data['protein_hgvs'] = (string) $seq_change->variant->name;
					$entry_data['protein_ref'] = (string) $seq_change->variant->ref_seq['accession'];
				}
			}

//			print_r($submission_data);
			return $entry_data;
		}
		else {
			return FALSE;
		}
	}
	
	private function _getGenderFromGenderCode($gender_code) {
		if ( $gender_code === 0 ) {
			$gender = "None available";
		}
		else if ( $gender_code === 1 ) {
			$gender = "Male";
		}
		else if ( $gender_code === 2 ) {
			$gender = "Female";
		}
		else if ( $gender_code === 9 ) {
			$gender = "Not applicable";
		}
		else {
			$gender = "None available";
		}
		return $gender;
	}
	
	public function deleteEntry ($data, $atomserver_uri, $atomserver_user, $atomserver_password) {
		$batch_uri = $atomserver_uri . "/\$batch";

		$auth = base64_encode($atomserver_user . ':' . $atomserver_password);
		$opts = array(
			'http' => array(
				'method' => 'PUT',
				'header' => "Content-Type: text/xml\r\n".
                            "Authorization: Basic $auth" ,
				'content' => $data

			)
		);
		$context = stream_context_create($opts);
		$response = file_get_contents($batch_uri, false, $context);
//		error_log("response -> " . $response);		
//		error_log("batch -> " . $batch_uri);
//		error_log("id -> " . $data);
		return $response;
	}
	
}
