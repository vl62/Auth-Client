<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Cafe Variome Configuration
|--------------------------------------------------------------------------
|
| Configuation for Cafe Variome
|
| These are settings we don't want to appear in the settings interface
| and that should remain static and not altered by the user.
|
|
*/

$config['fonts'] = array(	"Muli" => "Muli",
							"Cabin" => "Cabin",
							"Roboto" => "Roboto",
							"Lato" => "Lato",
							"Raleway" => "Raleway",
							"Cantarell" => "Cantarell",
							"Nunito" => "Nunito",
							"Lora" => "Lora",
							"OpenSans" => "OpenSans",
							"Ubuntu" => "Ubuntu" );
//$config['core_fields'] = array("variant_id", "gene", "LRG", "ref", "hgvs", "phenotype", "individual_id", "gender", "ethnicity", "pathogenicity", "location_ref" , "start", "end", "build", "source_url", "comment", "sharing_policy"); // Core fields that are used to generate the core templates for excel and tab-delimited import. N.B. These field names must exactly match the field names that are in the main variants table (case sensitive)
$config['protected_fields'] = array ("cafevariome_id", "variant_id", "sharing_policy"); // "sharing_policy"
$config['protected_groups'] = array ("admin" => "admin", "curator" => "curator", "general" => "general");

// BioPortal ontology categories that will be displayed in the Admin list
$config['bioportalcategories'] = array ("Phenotype","Health");
