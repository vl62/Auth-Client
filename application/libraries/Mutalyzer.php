<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter Mutalyzer Class
 *
 * Use Mutalyzer webservice to validate variants
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Owen Lancaster
 * @created			04/12/2013
 */

class Mutalyzer {
    function __construct() {
		$URL = 'https://mutalyzer.nl/services/?wsdl';
		$wsdl_test = htmlentities(file_get_contents($URL)); // Test that the wsdl is present
		if ( $wsdl_test ) {
			$options = array('features' => SOAP_SINGLE_ELEMENT_ARRAYS);
			$client = new SoapClient($URL, $options);
		}
		else {
			$client = false;
		}
		$this->client = $client;

    }

	function runMutalyzer($ref, $hgvs) {
		$mutalyzer_data = array();
		$variant = urldecode($ref . ":" . $hgvs);
//		print "variant -> $variant<br />";
//		error_log("variant $variant");
		$result = $this->client->runMutalyzer(array('variant' => $variant))->runMutalyzerResult;
		$result_array = (array) $result;
		$errors = (int) $result_array['errors'];
		$warnings = (int) $result_array['warnings'];
		$messages = (array) $result_array['messages'];
		$mutalyzer_data['warnings'] = $warnings;
		$mutalyzer_data['errors'] = $errors;

		if ( ($warnings > 0 && $errors === 0) || ($warnings === 0 && $errors === 0 ) ) { // No errors or warnings, or some warnings (okay to proceed but return the warning message to the user)
//			print "warnings $warnings | errors $errors<br />";

			if ($warnings > 0) { // Get the warning message
				foreach ( $messages as $key => $value ) {
					foreach ( $value as $message ) {
						$message = (array) $message;
						$mutalyzer_data['message'] = $message['message'];
					}
				}
			}
			else { // Still need the message key declared as it throws errors when printing in other bits of code if it's not
				$mutalyzer_data['message'] = '';
			}
			// Set valid flag and summary
			$mutalyzer_data['is_valid'] = 1;
			$mutalyzer_data['summary'] = $result_array['summary'];

			// Get the chromosome from the transcript reference (need to strip off any version number)
			$ref_no_version = preg_replace('/\..*/i', '', $ref);
			$chr = $this->client->getchromName(array('build' => 'hg19', 'acc' => $ref_no_version))->getchromNameResult;
			$mutalyzer_data['chr'] = $chr;

			if ( $warnings === 0 ) { // Can only get the following data if there are no warnings
				// Get the genomic start and end coordinates from the hgvs description
				$mappingInfo_result = (array) $this->client->mappingInfo(array('LOVD_ver' => '2', 'build' => 'hg19', 'accNo' => $ref, 'variant' => urldecode($hgvs)))->mappingInfoResult;
				if ( array_key_exists('start_g', $mappingInfo_result) ) {
					$start = $mappingInfo_result['start_g'];
					$end = $mappingInfo_result['end_g'];
					$mutalyzer_data['start'] = $start;
					$mutalyzer_data['end'] = $end;
				}

				// Get the genomic hgvs description from the transcript hgvs description
//				$conversion_array = (array) $this->mutalyzerConvertPositionToTranscript($client, $variant); // Get all the transcript HGVS for this genomic HGVS
//				foreach ($conversion_array as $key => $value) {
//					$genomic_ref_hgvs = $this->_splitRefHGVS($value[0]);
//					$mutalyzer_data['genomic_ref'] = $genomic_ref_hgvs['ref'];
//					$mutalyzer_data['genomic_hgvs'] = $genomic_ref_hgvs['hgvs'];
//				}
			}

			// Get the protein HGVS description from the transcription hgvs description
//			$proteins = (array) $result_array['proteinDescriptions'];
//			foreach ($proteins as $p) {
//				foreach ($p as $protein_description) {
//					$protein_ref_hgvs = $this->_splitRefHGVS($protein_description);
//					$protein_ref = $protein_ref_hgvs['ref'];
//					$protein_hgvs = $protein_ref_hgvs['hgvs'];
//					$protein_ref = preg_replace('/\(.*\)/i', '', $protein_ref);
//					$mutalyzer_data['protein_hgvs'] = $protein_hgvs;
//					$mutalyzer_data['protein_ref'] = $protein_ref;
//				}
//			}
		}
		else { // 
			$mutalyzer_data['is_valid'] = 0;
			$mutalyzer_data['summary'] = $result_array['summary'];

			if ($errors > 0) { // Get the warning message
				foreach ( $messages as $key => $value ) {
					foreach ( $value as $message ) {
						$message = (array) $message;
						$mutalyzer_data['message'] = $message['message'];
//						print "message " . $message['message'] . "<br />";
					}
				}
			}			
		}

//		$mutalyzer_data_json = json_encode($mutalyzer_data);
		return $mutalyzer_data;
//		echo $mutalyzer_data_json;
	}

	
	// Mutalyzer webservice lookup for valid HGVS nomenclature
	function checkSyntax($ref, $hgvs) {
		$URL = 'https://mutalyzer.nl/services/?wsdl';
		$variant = $ref . ":" . $hgvs;
		// http://www.dotvoid.com/2008/10/soap-structures-in-php/
		$options = array('features' => SOAP_SINGLE_ELEMENT_ARRAYS);



		$result = $this->client->checkSyntax(array('variant' => $variant))
                  ->checkSyntaxResult;

		if ($result->valid) {
//			error_log($variant . " -> valid");
			return TRUE;
		}
		else {
//			error_log($variant . " -> not valid");
			RETURN FALSE;
		}
		if (isset($result->messages->SoapMessage)) {
//	        echo '<p>Messages:<ol>';
			foreach ($result->messages->SoapMessage as $message) {
//				echo '<li><code>'.htmlentities($message->errorcode).'</code>: ';
//				echo htmlentities($message->message);
			}
//			echo '</ol>';
		}
    }
	
	// Mutalyzer webservice lookup to get transcripts that overlap a chromosomal position
	function mutalyzerGetTranscriptsFromPosition($build, $chr, $start) {
		$result = $this->client->getTranscripts(array('build' => $build, 'chrom' => $chr, 'pos' => $start ))->getTranscriptsResult;
		return $result;
    }
	
	function mutalyzerGetGeneNameForTranscript($build, $transcript) {
		$result = $this->client->getGeneName(array('build' => $build, 'accno' => $transcript ))->getGeneNameResult;
		return $result;
	}
	
	function mutalyzerConvertPositionToTranscript($build, $chr_hgvs) {
		$result = $this->client->numberConversion(array('build' => $build, 'variant' => $chr_hgvs ))->numberConversionResult;
		return $result;
	}
	
	function mutalyzerGetChromosomeAccession($build, $chr) {
		$result = $this->client->chromAccession(array('build' => $build, 'name' => $chr))->chromAccessionResult;
		return $result;
	}
	
}

/* End of file Mutalyzer.php */
/* Location: ./application/libraries/Mutalyzer.php */