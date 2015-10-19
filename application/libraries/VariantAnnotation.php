<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter Variant Annotation Class
 *
 * Various methods for variant annotation
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Owen Lancaster
 * @created			04/12/2013
 */

class VariantAnnotation {
    function __construct() {
//		$URL = 'https://mutalyzer.nl/services/?wsdl';
//		$wsdl_test = htmlentities(file_get_contents($URL)); // Test that the wsdl is present
//		if ( $wsdl_test ) {
//			$options = array('features' => SOAP_SINGLE_ELEMENT_ARRAYS);
//			$client = new SoapClient($URL, $options);
//		}
//		else {
//			$client = false;
//		}
//		$this->client = $client;
    }

	/**
	 * Get inferred information about a variant from its HGVS name ...
	 * @param string $hgvs
	 * 	HGVS name for a variant
	 * @return Ambigous <string, boolean>
	 * 	Array contained inferred data from the HGVS name
	 * 	Keys: refseq_type, utr, position, intron, intron_pos, basechange, variant_class, dna_type, coding
	*/
	function hgvs_get_information(string $hgvs) {

		// Sometimes HGVS name can be passed HTML encoded. Convert to clean text.
		$hgvs = decode_entities($hgvs);
		$variant_info = array();
		if (preg_match_all("/^([c|g])\.([-|\*]*)(\d+)([+|-]*)(\d*)(.+)/", $hgvs, $matches)) {
			// Mapping
			$variant_info['refseq_type'] = $matches[1][0];
			$variant_info['utr'] = $matches[2][0];
			$variant_info['position'] = $matches[3][0];
			$variant_info['intron'] = $matches[4][0];
			$variant_info['intron_pos'] = $matches[5][0];
			$variant_info['basechange'] = $matches[6][0];
		}

		// TODO Classify variant by Indel, Transition, Transversion
		if (preg_match("/delins/", $hgvs)) {
			$variant_info['variant_class'] = 'deletion / insertion';
		}
		elseif (preg_match("/del/", $hgvs)) {
			$variant_info['variant_class'] = 'deletion';
		}
		elseif (preg_match("/ins/", $hgvs)) {
			$variant_info['variant_class'] = 'insertion';
		}
		elseif (preg_match("/dup/", $hgvs)) {
			$variant_info['variant_class'] = 'duplication';
		}
		elseif (preg_match("/(A>G|G>A|C>T|T>C)/", $hgvs)) {
			$variant_info['variant_class'] = 'transition';
		}
		elseif (preg_match("/(A>C|A>T|C>A|C>G|T>A|T>G|G>C|G>T)/", $hgvs)) {
			$variant_info['variant_class'] = 'transversion';
		}
		elseif (preg_match("/inv/", $hgvs)) {
			$variant_info['variant_class'] = 'inversion';
		}
		else {
			$variant_info['variant_class'] = 'other';
		}

		// Attempt to determine the *type* of DNA this variant falls in
		$variant_info['dna_type'] = 'unknown';
		//$variant_info['coding'] = 'non-coding';
		if (($variant_info['utr'] == '-') and ( $variant_info['refseq_type'] == 'c')) {
			$variant_info['dna_type'] = "5' UTR";
		}
		elseif (($variant_info['utr'] == '*') and ( $variant_info['refseq_type'] == 'c')) {
			$variant_info['dna_type'] = "3' UTR";
		}
		else {
			if (($variant_info['intron']) and ( $variant_info['refseq_type'] == 'c')) {
				$variant_info['dna_type'] = 'intron';
			}
			elseif ($variant_info['refseq_type'] == 'c') {
				$variant_info['dna_type'] = 'exon';
				$variant_info['coding'] = TRUE;
			}
		}
		return $variant_info;
	}
	
	function hgvs_parse(string $hgvs) {

		// Sometimes HGVS name can be passed HTML encoded. Convert to clean text.
		$hgvs = decode_entities($hgvs);
		$variant_info = array();
		if (preg_match_all("/^([c|g])\.([-|\*]*)(\d+)([+|-]*)(\d*)(.+)/", $hgvs, $matches)) {
			// Mapping
			$variant_info['refseq_type'] = $matches[1][0];
			$variant_info['utr'] = $matches[2][0];
			$variant_info['position'] = $matches[3][0];
			$variant_info['intron'] = $matches[4][0];
			$variant_info['intron_pos'] = $matches[5][0];
			$variant_info['basechange'] = $matches[6][0];
		}
		return $variant_info;
	}

}
