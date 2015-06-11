<?php

/**
 *
 * @author owen
 */

require_once "CafeVariome.php";

class Query extends CafeVariome {

	function __construct($parameters) {
//		parent::__construct();
		$this->CI =& get_instance();
		if ( array_key_exists('syntax', $parameters) ) {
			$this->syntax = $parameters['syntax'];
		}
		else {
			$this->syntax = 'elasticsearch';
		}
	}
	
	function parse($query) {
		$query_metadata = $query->queryMetadata;
		$query_id = $query_metadata->queryId;
		$query_type = $query_metadata->queryType;
		$query_label = $query_metadata->label;
		$query_result_format = $query_metadata->queryResultFormat;
		$submitter_id = $query_metadata->submitter->id;
		$submitter_name = $query_metadata->submitter->name;
		$submitter_email = $query_metadata->submitter->email;
		$submitter_institution = $query_metadata->submitter->institution;

		$query_data = $query->query;
//		print_r($query_data);
		$query_array = array();
		foreach ( $query_data as $k => $v ) {
			foreach ( $v as $element ) {
				if ( $this->syntax == "elasticsearch" ) {
//					$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//					$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
//					print "operator -> " . htmlentities($element->operator) . "<br />";
//					error_log("operator -> " . htmlentities($element->operator) . " -> " . $element->operator);
					$element->{$k} = strtolower($element->{$k});
					if ( strtolower($element->operator) == "is" ) {
						if ( $k == 'phenotype_epad' ) {
//							print $element->parameterID . " -> " . $element->{$k} . "<br />";
//							print_r($element);
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							if ( strtolower($element->{$k}) == "null" ) {
								$query_array[$element->parameterID] =  "_missing_:" . $attribute;
							}
							else {
//								$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//								$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
								$element->{$k} = addcslashes($element->{$k},'-+=&&||><!\(\)\{\}\[\]^"~*?:\\');
								$query_array[$element->parameterID] = $attribute . "_raw:" . $element->{$k};
							}
						}
						else {
							$query_array[$element->parameterID] = $element->{$k}; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = $element->{$k}; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}
					}
					elseif ( strtolower($element->operator) == "is like" ) {
						if ( $k == 'phenotype_epad' ) {
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
//							$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//							$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
							$element->{$k} = addcslashes($element->{$k},'-+=&&||><!\(\)\{\}\[\]^"~*?:\\');
							$query_array[$element->parameterID] = $attribute . "_raw:" . "*" . $element->{$k} . "*";
						}
						else {
//							$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//							$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
							$element->{$k} = addcslashes($element->{$k},'-+=&&||><!\(\)\{\}\[\]^"~*?:\\');
							$query_array[$element->parameterID] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}
					}
					elseif ( strtolower($element->operator) == "is not" ) {
						if ( $k == 'phenotype_epad' ) {
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							

							if ( strtolower($element->{$k}) == "null" ) {
								$query_array[$element->parameterID] =  "_exists_:" . $attribute;
							}
							else {
//								error_log("TYPE -------------> " . $element->{$k});
//								if ( is_numeric($element->{$k}) ) { // Hack for NOT problem
//									$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//									$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
//									$query_array[$element->parameterID] = $attribute . ":(" . "<" . $element->{$k} . " OR >" . $element->{$k} . ")";
//
//								}
//								else {
//									$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//									$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
									$element->{$k} = addcslashes($element->{$k},'-+=&&||><!\(\)\{\}\[\]^"~*?:\\');
//									$query_array[$element->parameterID] = $attribute . ":" . "! " . $element->{$k};
//									$query_array[$element->parameterID] = "<not>" . $attribute . "_raw:* !" . $element->{$k} . "</not>";
//									$query_array[$element->parameterID] = $attribute . "_raw:* !" . $element->{$k};
									$query_array[$element->parameterID] = $attribute . "_raw:" . "(-" . $element->{$k} . ")";
//									$not_filter = $attribute . "_raw:" . $element->{$k};
//									$this->notFilter = $attribute . "_raw:" . $element->{$k};
//									$query_array[$element->parameterID] = $attribute . ":" . $element->{$k};
//								}
							}
						}
						else {
							$query_array[$element->parameterID] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}
					}
					elseif ( strtolower($element->operator) == "is not like" ) {
						if ( $k == 'phenotype_epad' ) {
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							

							if ( strtolower($element->{$k}) == "null" ) {
								$query_array[$element->parameterID] =  "_exists_:" . $attribute;
							}
							else {
//								$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
//								$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
//								$element->{$k} = preg_replace('/-|/','-',$element->{$k});
//								$element->{$k} = preg_replace('%([+\-&|!(){}[\]^"~*?:/]+)%', '\\\\$1', $element->{$k});
//								$element->{$k} = preg_replace('%([+-=]+)%', '\\\\$1', $element->{$k});
//								$elasticsearch_escaped_characters = array (+ - = && || > < ! ( ) { } [ ] ^ " ~ * ? : \ /);
								$element->{$k} = addcslashes($element->{$k},'-+=&&||><!\(\)\{\}\[\]^"~*?:\\');
								$query_array[$element->parameterID] = $attribute . "_raw:" . "(-*" . $element->{$k} . "*)";
							}
						}
						else {
							$query_array[$element->parameterID] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}
					}
					elseif ( strtolower($element->operator) == "=" ) {
						if ( $k == 'phenotype_epad' ) {
//							print $element->parameterID . " -> " . $element->{$k} . "<br />";
//							print_r($element);
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
//							$subject = '+ - = && || > < ! ( ) { } [ ] ^ " ~ * ? : \ /';
//							$result = preg_replace('%([+\-&|!(){}[\]^"~*?:/]+)%', '\\\\$1', $subject);
							if ( strtolower($element->{$k}) == "null" ) {
								$query_array[$element->parameterID] =  "_missing_:" . $attribute;
							}
							else {
								if ( is_numeric($element->{$k}) ) {
									$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
									$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
									$query_array[$element->parameterID] = $attribute . "_d:" . $element->{$k};
								}
								else { // A string value with numeric comparison shouldn't be possible as it's blocked in the query builder
									$query_array[$element->parameterID] = $attribute . ":" . $element->{$k};
								}
							}
						}
						else {
//							$element->{$k} = addcslashes($element->{$k},'-+=&&||><!\(\)\{\}\[\]^"~*?:\\');
							$query_array[$element->parameterID] = $element->{$k}; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = $element->{$k}; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}
					}
					elseif ( strtolower($element->operator) == "!=" ) {
//					elseif ( htmlentities($element->operator) == "&ne;" ) {
						if ( $k == 'phenotype_epad' ) {
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							
							if ( strtolower($element->{$k}) == "null" ) {
								$query_array[$element->parameterID] =  "_exists_:" . $attribute;
							}
							else {
//								error_log("TYPE -------------> " . $element->{$k});
								if ( is_numeric($element->{$k}) ) {
									$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
									$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
									$query_array[$element->parameterID] = $attribute . "_d:(" . "<" . $element->{$k} . " OR >" . $element->{$k} . ")";
								}
							}
						}
						else {
							$query_array[$element->parameterID] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}	
					}
					else { // Else it must be a numeric comparison >,<,>=,<=
						if ( $k == 'phenotype_epad' ) {
							$attribute = str_replace(' ', '_', $element->attribute); // Replace spaces with underscore as this is how the phenotype attribute is indexed in ElasticSearch (ElasticSearch can't handle spaces in a field name so have removed spaces and replaced with underscore)
							$attribute = str_replace('[', '\[', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							$attribute = str_replace(']', '\]', $attribute); // Escape square brackets as these are reserved in ElasticSearch
							if ( is_numeric($element->{$k}) ) {
								$element->{$k} = str_replace('-', '\-', $element->{$k}); // Escape
								$element->{$k} = str_replace('+', '\+', $element->{$k}); // Escape
								$query_array[$element->parameterID] = $attribute . "_d:" . "" . $element->operator . "" . $element->{$k};
							}
							else { // A string value with numeric comparison shouldn't be possible as it's blocked in the query builder
//								$query_array[$element->parameterID] = $attribute . ":" . "" . $element->operator . "" . $element->{$k};
								$query_array[$element->parameterID] = $attribute . ":" . " " . $element->operator . "" . $element->{$k};
							}
						}
						else {
							$query_array[$element->parameterID] = $element->{$k};
//							$query_array[$element->parameterID] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
//							$query_array[$element->id] = "*" . $element->{$k} . "*"; // Get query term using the value of the object name as the key (it's dynamic so need the curly brackets) then set this as the value in the query array and the key is the parameterID
						}	
					}
				}
			}
		}
//		print_r($query_array);

		$query_statement = $query->queryStatement;
//		print "$query_statement<br />";
//		preg_match_all('!\d+!', $query_statement, $matches);
//		error_log("query_statement -> " . $query_statement);
//		print_r($matches);
//		preg_match_all("/\[[^\]]*\]/", $query_statement, $m);
//		print "$query_statement<br />";
//		preg_match_all('/\(+(.*?)\)/', $query_statement, $matches);
//		preg_match_all("/\((.*?)\)/", $query_statement, $matches);
		preg_match_all('/\(([^\)]*)\)/', $query_statement, $matches);
//		print_r($matches);
//		print "<br />";
//		error_log(print_r($matches,1));
		$query_statement_array = array();
		foreach ( $matches[0] as $match ) {
			$match = str_replace('((','(',$match);
			$match = str_replace('))',')',$match);
			$match_no_brackets = str_replace(array('(',')'),'',$match);
//			print "MATCH -> $match<br />";
//			error_log("search: " . $match . " -> replace:" . $query_array[$match_no_brackets] . " --> " . $query_statement);
//			if ( preg_match('/\s/',$query_array[$match_no_brackets]) ) {
//				$query_section = "\"" . $query_array[$match_no_brackets] . "\"";
//			}
//			else {
//				$query_section = $query_array[$match_no_brackets];
//			}
			$query_section = $query_array[$match_no_brackets];
//			error_log($query_section);
//			print "section -> $query_section<br />";
			$query_statement = str_replace($match, $query_section, "(" . $query_statement . ")");
			$query_statement_array[] = "(" . $query_section . ")";
		}
		print_r($query_statement_array);
		$query_statement = implode(' AND ', $query_statement_array);
//		error_log($query_statement);
		$query_statement_for_display = $query_statement;
		$query_statement_for_display = str_replace('_d','',$query_statement_for_display); // Remove the appended numeric index name so that it isn't displayed to the user
		$query_statement_for_display = str_replace('_raw','',$query_statement_for_display);
		$query_statement_for_display = str_replace('_missing_','missing',$query_statement_for_display);
		$query_statement_for_display = str_replace('_exists_','exists',$query_statement_for_display);
		$query_statement_for_display = str_replace('\[','[',$query_statement_for_display);
		$query_statement_for_display = str_replace('\]',']',$query_statement_for_display);
		$query_statement_for_display = str_replace('_',' ',$query_statement_for_display);
		print "<h4>$query_statement_for_display</h4>";
		return $query_statement;
		
	}
	
	function run($term, $source) {
//		error_log("term -> $term");
//		$term = "(Long_nose:present) AND (Narrow_nasal_ridge:present)";
//		$term = "nose_length_\[cm\]:>5";
//		$term = "(nose_length_\[m\]:>=5) AND (nose_length_\[mm\]:>=6)";
//		error_log("term -> $term");
		if ($this->syntax == "elasticsearch") {
			// Get dynamic name for the ES index to try and avoid clashes with multiple instance of CV on the same server
			$es_index = $this->CI->config->item('site_title');
			$es_index = preg_replace('/\s+/', '', $es_index);
			$es_index = strtolower($es_index);
			$this->CI->elasticsearch->set_index($es_index);
			$this->CI->elasticsearch->set_type("variants");
			$query = array();
			$query['size'] = 0;
			$term = urldecode($term);

			$search_fields = $this->CI->settings_model->getSearchFields("search_fields");

			if (!empty($search_fields)) { // Specific search fields are specified in admin interface so only search on these
				$search_fields_elasticsearch = array();
				foreach ($search_fields as $fields) {
					$search_fields_elasticsearch[] = $fields['field_name'];
				}
//				error_log("search fields -> " . print_r($search_fields, 1));
				$query['query']['bool']['must'][] = array('query_string' => array("fields" => $search_fields_elasticsearch, "query" => "$term", 'default_operator' => "AND"));
			}
			else { // Otherwise search across all fields
				
//				if ( property_exists($this, 'notFilter') ) {
//					error_log("notFilter -> " . $this->notFilter);
//					$query['query']['bool']['must'][] = array('query_string' => array("query" => "$term", 'default_operator' => "AND")); // , "default_field" => "" Hack: default_field as empty because when doing apoe not M it was searching the gender field and getting back the hits for that
//					$query['query']['bool']['must_not'][] = array('query_string' => array("query" => "$this->notFilter", 'default_operator' => "AND")); // , "default_field" => "" Hack: default_field as empty because when doing apoe not M it was searching the gender field and getting back the hits for that
//				}
//				else {
					$query['query']['bool']['must'][] = array('query_string' => array("query" => "$term", 'default_operator' => "AND")); // 'analyzer' => 'not_analyzed' , "default_field" => "" Hack: default_field as empty because when doing apoe not M it was searching the gender field and getting back the hits for that
//				}
//				$query['query']['query_string'] = array("query" => "$term", 'default_operator' => "AND");
//				$query['query']['bool']['must_not'][] = array('query_string' => array("query" => "$term"));
//				$query['query']['bool']['must_not'][] = array('query_string' => array("query" => "$term", 'default_operator' => "AND"));
			}

			$query['query']['bool']['must'][] = array("term" => array("source" => $source));
			$query['facets']['sharing_policy']['terms'] = array('field' => "sharing_policy");
//			$query['filter']['not'] = array();
//			$query['query']['bool']['must'][] = array("term" => array("source" => $source));
			$query = json_encode($query);
//			error_log("query ----> $query $source");
			$es_data = $this->CI->elasticsearch->query_dsl($query);
//			error_log(print_r($es_data, 1));
			$counts = array();
//			print "SOURCE -> $source<br />";
			if ( array_key_exists('facets', $es_data) ) {
				foreach ($es_data['facets']['sharing_policy']['terms'] as $facet_sharing_policy) {
					$sp_es = $facet_sharing_policy['term'];
					if ($sp_es == "openaccess") {
						$sp_es = "openAccess";
					}
					else if ($sp_es == "restrictedaccess") {
							$sp_es = "restrictedAccess";
					}
					else if ($sp_es == "linkedaccess") {
						$sp_es = "linkedAccess";
					}

					$counts[$sp_es] = $facet_sharing_policy['count'];
//					error_log("es counts -> " . print_r($counts,1));
//					print "<br />";
				}
			}
			return $counts;
		}

		
	}
	

	function run_API($source_uri, $source, $term) {
		$this->load->model('federated_model');
		// Get the node name and then remove it from the source name - need to do this since the node name has been appended in order to make it unique for this node - in the node that is to be search it won't have this appended bit
		$node_name = $this->federated_model->getNodeNameFromNodeURI($source_uri);
		$node_source = str_replace("_" . $node_name, "", $source);
		$federated_data = array(
			'term' => $term,
			'source' => $node_source
		);
//		error_log("federated_data -> " . $term . " -> " . $source_uri . " -> " . print_r($federated_data, 1));
//		$counts = federatedAPI($source_uri, $federated_data);
		$term = urlencode($term);
//		error_log("term -> " . $term);
		$counts = @file_get_contents($source_uri . "/discover/variantcount/$term/$node_source/json");
//		error_log($source_uri . "/discover/variantcount/$term/$source/json");
//		error_log("decode -> " . json_decode($counts));
		$counts = json_decode($counts, TRUE);
		$hacked_counts = array();
		if ( ! empty($counts)) {
			foreach ( $counts as $key => $value ) {
				foreach ( $value as $k => $v) {
//					error_log("key: $k value: $v");
					$hacked_counts[$k] = $v;
				}
			}
		}
//		error_log("counts from federatedAPI -> " . print_r($counts, 1));
//		error_log("hacked -> " . print_r($hacked_counts, 1));
		return $hacked_counts;

	}
	
	
	function detect_type($element, $data) {
		switch ($element) {
			case "allele":
				echo "Running allele query -> ";
				$this->allele_query($data);
				break;
			case "geneSymbol":
				echo "Running gene symbol query -> ";
				$this->gene_symbol_query($data);
				break;
			case "green":
					echo "Your favorite color is green!";
					break;
			default:
				echo "Query type was not detected";
		}
	}
	
	function allele_query($data) {
		foreach ( $data as $allele ) {
			
//			$operator = $allele->operator;
			$operator = isset($allele->operator) ? $allele->operator : '';
						
//			$source = $allele->source;
			$source = isset($allele->source) ? $allele->source : '';
			
//			$reference = $allele->reference;
			$reference = isset($allele->reference) ? $allele->reference : '';
			
//			$start = $allele->start;
			$start = isset($allele->start) ? $allele->start : '';
			
//			$end = $allele->end;
			$end = isset($allele->end) ? $allele->end : '';
			
//			$allele_sequence = $allele->allele_sequence;
			$allele_sequence = isset($allele->allele_sequence) ? $allele->allele_sequence : '';
			
			if ( is_array($allele_sequence) ) {
				echo "ARRAY";
			}
			else {
				echo "NOT ARRAY";
			}
		}
		print_r($data);
	}

	function gene_symbol_query($data) {
		$gene_symbol = isset($allele->geneSymbol) ? $allele->geneSymbol : '';
		print_r($data);
	}
	
}

?>
