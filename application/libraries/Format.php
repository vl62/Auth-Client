<?php
/**
 * Format class
 *
 * Help convert between various formats such as XML, JSON, CSV, etc.
 *
 * @author  	Phil Sturgeon
 * @license		http://philsturgeon.co.uk/code/dbad-license
 */
class Format {

	// Array to convert
	protected $_data = array();

	// View filename
	protected $_from_type = null;

	/**
	 * Returns an instance of the Format object.
	 *
	 *     echo $this->format->factory(array('foo' => 'bar'))->to_xml();
	 *
	 * @param   mixed  general date to be converted
	 * @param   string  data format the file was provided in
	 * @return  Factory
	 */
	public function factory($data, $from_type = null)
	{
		// Stupid stuff to emulate the "new static()" stuff in this libraries PHP 5.3 equivalent
		$class = __CLASS__;
//		error_log("from -> " . $from_type . " data -> " . $data);
//		error_log(print_r($data,true));
		return new $class($data, $from_type);
	}

	/**
	 * Do not use this directly, call factory()
	 */
	public function __construct($data = null, $from_type = null)
	{
		get_instance()->load->helper('inflector');

		// If the provided data is already formatted we should probably convert it to an array
		if ($from_type !== null)
		{
			if (method_exists($this, '_from_' . $from_type))
			{
				$data = call_user_func(array($this, '_from_' . $from_type), $data);
			}

			else
			{
				throw new Exception('Format class does not support conversion from "' . $from_type . '".');
			}
		}

		$this->_data = $data;
	}

	// FORMATING OUTPUT ---------------------------------------------------------

	public function to_array($data = null)
	{
		// If not just null, but nothing is provided
		if ($data === null and ! func_num_args())
		{
			$data = $this->_data;
		}

		$array = array();

		foreach ((array) $data as $key => $value)
		{
			if (is_object($value) or is_array($value))
			{
				$array[$key] = $this->to_array($value);
			}

			else
			{
				$array[$key] = $value;
			}
		}

		return $array;
	}

	// Format XML for output
	public function to_xml($data = null, $structure = null, $basenode = 'xml')
	{
		if ($data === null and ! func_num_args())
		{
			$data = $this->_data;
		}

		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1)
		{
			ini_set('zend.ze1_compatibility_mode', 0);
		}

		if ($structure === null)
		{
			$structure = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$basenode />");
		}

		// Force it to be something useful
		if ( ! is_array($data) AND ! is_object($data))
		{
			$data = (array) $data;
		}

		foreach ($data as $key => $value)
		{

			//change false/true to 0/1
			if(is_bool($value))
			{
				$value = (int) $value;
			}

			// no numeric keys in our xml please!
			if (is_numeric($key))
			{
				// make string key...
				$key = (singular($basenode) != $basenode) ? singular($basenode) : 'item';
			}

			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z_\-0-9]/i', '', $key);

			// if there is another array found recursively call this function
			if (is_array($value) || is_object($value))
			{
				$node = $structure->addChild($key);

				// recursive call.
				$this->to_xml($value, $node, $key);
			}

			else
			{
				// add single node.
				$value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, "UTF-8");

				$structure->addChild($key, $value);
			}
		}

		return $structure->asXML();
	}

	// Format HTML for output
	public function to_html()
	{
		$data = $this->_data;

		// Multi-dimensional array
		if (isset($data[0]) && is_array($data[0]))
		{
			$headings = array_keys($data[0]);
		}

		// Single array
		else
		{
			$headings = array_keys($data);
			$data = array($data);
		}

		$ci = get_instance();
		$ci->load->library('table');

		$ci->table->set_heading($headings);

		foreach ($data as &$row)
		{
			$ci->table->add_row($row);
		}

		return $ci->table->generate();
	}

	// Format CSV for output
	public function to_csv()
	{
		$data = $this->_data;

		// Multi-dimensional array
		if (isset($data[0]) && is_array($data[0]))
		{
			$headings = array_keys($data[0]);
		}

		// Single array
		else
		{
			$headings = array_keys($data);
			$data = array($data);
		}

		$output = implode(',', $headings).PHP_EOL;
		foreach ($data as &$row)
		{
			$output .= '"'.implode('","', $row).'"'.PHP_EOL;
		}

		return $output;
	}

	// Encode as JSON
	public function to_json()
	{
		return json_encode($this->_data);
	}

	// Encode as Serialized array
	public function to_serialized()
	{
		return serialize($this->_data);
	}

	// Output as a string representing the PHP structure
	public function to_php()
	{
		return var_export($this->_data, TRUE);
	}
	
	public function to_lovd() {

		$output = "### LOVD-version 2000-330 ### Variants/Patients ### Do not remove this line, unless importing in versions before 2.0-04 ###\n";
		$output .= '"{{ Variant/Reference }}"	"{{ Variant/Exon }}"	"{{ Variant/DNA }}"	"{{ Variant/RNA }}"	"{{ Variant/Protein }}"	"{{ Variant/Restriction_site }}"	"{{ Variant/Frequency }}"	"{{ Variant/DBID }}"	"{{ Variant/Detection/Template }}"	"{{ Variant/Detection/Technique }}"	"{{ Patient/Patient_ID }}"	"{{ Patient/Phenotype/Disease }}"	"{{ Patient/Reference }}"	"{{ Patient/Remarks }}"	"{{ Patient/Remarks_Non_Public }}"	"{{ Patient/Times_Reported }}"	"{{ ID_variantid_ }}"	"{{ ID_patientid_ }}"	"{{ ID_allele_ }}"	"{{ ID_pathogenic_ }}"	"{{ ID_status_ }}"	"{{ ID_sort_ }}"	"{{ ID_submitterid_ }}"	"{{ ID_variant_created_by_ }}"	"{{ variant_created_date_ }}"	"{{ ID_variant_edited_by_ }}"	"{{ variant_edited_date_ }}"	"{{ ID_patient_created_by_ }}"	"{{ patient_created_date_ }}"	"{{ ID_patient_edited_by_ }}"	"{{ patient_edited_date_ }}"' . "\n";
//		echo ""	"-"	"c.1885C>T"	""	"p.Arg629*"	""	""	"NIPBL_00012"	"DNA"	"Sanger sequencing"	"eWCmNQbHdF9eKcy"	"Cornelia de Lange syndrome (CDLS1)"	"{PMID15723327:PubMed};{CVvx241477:CafeVariome}"	"Imported from Cafe Variome: cafevariome_id=vx241477 | source_id=UMOP_CGM_INSA | source_name=Instituto Nacional Saude Dr. Ricardo Jorge Departamento Genetica, Centro de Genetica Medica Dr. Jacinto Magalhaes | contact_name=Jorge Oliveira | contact_email=jorge.oliveira@insa.min-saude.pt"	""	"1"	"0000012"	"0000012"	"0"	"95"	"9"	""	"00000"	"0"	"2012-10-25 14:59:47"	"1"	"2012-10-25 15:00:14"	"0"	"2012-10-25 14:59:47"	""	""

		$data = $this->_data;
		error_reporting(0); // Hack to turn off error reporting when any of the data elements aren't defined - find a better way to do this
		foreach ($data as $cvid => $row) {
			if ( $row['individual_id']) {
				$output .= '""' . "\t" . '""' . "\t" . '"' . $row['hgvs'] . '"' . "\t" . '""' . "\t" . '"' . $row['protein_hgvs'] . '"' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '"' . $row['detection_method'] . '"' . "\t" . '"' . $row['individual_id'] . '"' . "\t" . '"' . $row['phenotype'] . '"' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '"1"' . "\t" . '""' . "\t" . '"2"' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\n";
			}
			else {
				$output .= '""' . "\t" . '""' . "\t" . '"' . $row['hgvs'] . '"' . "\t" . '""' . "\t" . '"' . $row['protein_hgvs'] . '"' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '"' . $row['detection_method'] . '"' . "\t" . '"1"' . "\t" . '"' . $row['phenotype'] . '"' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '"1"' . "\t" . '""' . "\t" . '"2"' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\t" . '""' . "\n";
			}

//			
//			$output .= '"Variant/Reference"' . "\t" . '"Variant/Exon"' . "\t" . '"' . $row['hgvs'] . '"' . "\t" . '"Variant/RNA"' . "\t" . '"' . $row['protein_hgvs'] . '"' . "\t" . '"Variant/Restriction_site"' . "\t" . '"Variant/Frequency"' . "\t" . '"Variant/DBID"' . "\t" . '"Variant/Detection/Template"' . "\t" . '"' . $row['detection_method'] . '"' . "\t" . '"' . $row['individual_id'] . '"' . "\t" . '"' . $row['phenotype'] . '"' . "\t" . '"Patient/Reference"' . "\t" . '"' . $row['comment'] . '"' . "\t" . '"Patient/Remarks_Non_Public"' . "\t" . '"Patient/Times_Reported"' . "\t" . '"ID_variantid"' . "\t" . '"ID_patientid"' . "\t" . '"ID_allele"' . "\t" . '"ID_pathogenic"' . "\t" . '"ID_status"' . "\t" . '"ID_sort"' . "\t" . '"ID_submitterid"' . "\t" . '"ID_variant_created_by"' . "\t" . '"variant_created_date"' . "\t" . '"ID_variant_edited_by"' . "\t" . '"variant_edited_date"' . "\t" . '"ID_patient_created_by"' . "\t" . '"patient_created_date"' . "\t" . '"ID_patient_edited_by"' . "\t" . '"patient_edited_date"' . "\n";
		}
		// Change back to the appropriate reporting level
		if (defined('ENVIRONMENT')) {
			switch (ENVIRONMENT) {
				case 'development':
					error_reporting(E_ALL);
				break;
				case 'production':
					error_reporting(0);
				break;
			}
		}
//		$this->load->helper('download');
//		force_download('lovd.txt', $output); 
		return $output;
	}
	
	public function to_fasta() {
		error_reporting(0);
//		$output = ">test\nATGACCA";
		$data = $this->_data;
//		error_log("data -> " . print_r($data, 1));
		$output = $data[0] . "\n" . $data[1]; // fasta header is first element of array, sequence is second element
		
		// Change back to the appropriate reporting level
		if (defined('ENVIRONMENT')) {
			switch (ENVIRONMENT) {
				case 'development':
					error_reporting(E_ALL);
				break;
				case 'production':
					error_reporting(0);
				break;
			}
		}
		if ( ! $output ) {
			$output = "NA";
		}
		return $output;
	}

	public function to_tab() {
//		error_log(print_r($this->_data, 1));
		$data = $this->_data;

		// Multi-dimensional array
		if (isset($data[0]) && is_array($data[0]))
		{
			$headings = array_keys($data[0]);
//			print "MULTI\n";
		}

		// Single array
		else
		{
			$headings = array_keys($data);
//			print_r($headings);
//			print_r($data);
			$data = array($data);
//			print "SINGLE\n";
		}

		$output = implode('	', $headings).PHP_EOL;
		foreach ($data as $cvid => $row)
		{
//			print "ID -> $cvid\n";
//			print_r($row);
			$output .= implode('	', $row).PHP_EOL;
		}

		return $output;
	}
	
	// Format XML for output
	protected function _from_xml($string)
	{
		return $string ? (array) simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA) : array();
	}

	// Format CSV for output
	// This function is DODGY! Not perfect CSV support but works with my REST_Controller
	protected function _from_csv($string)
	{
		$data = array();

		// Splits
		$rows = explode("\n", trim($string));
		$headings = explode(',', array_shift($rows));
		foreach ($rows as $row)
		{
			// The substr removes " from start and end
			$data_fields = explode('","', trim(substr($row, 1, -1)));

			if (count($data_fields) == count($headings))
			{
				$data[] = array_combine($headings, $data_fields);
			}
		}

		return $data;
	}

	// Encode as JSON
	private function _from_json($string)
	{
		return json_decode(trim($string));
	}

	// Encode as Serialized array
	private function _from_serialize($string)
	{
		return unserialize(trim($string));
	}

}

/* End of file format.php */