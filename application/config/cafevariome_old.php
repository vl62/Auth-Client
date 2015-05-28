<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Cafe Variome Configuration
|--------------------------------------------------------------------------
|
| Configuation for Cafe Variome
|
*/

$config['email'] = "admin@cafevariome.org";
$config['twitter'] = ""; // If Twitter username is set then Twitter icon link appears in contact page
$config['rss'] = $this->config['base_url'] . 'feed'; // If set then rss feed will appear in a side bar on home page and link in contact page. Leave blank to turn off news section
$config['google_analytics'] = "UA-37141634-1"; // Add your Google Analytics web-property ID to add tracking js code - takes form of UA-XXXXX-Y or UA-XXXXX-YY
$config['cvid_prefix'] = "vx"; // Prefix for primary ID for variants
$config['stats'] = false; // If this is set to true then you must have the stats database structure present to enable reporting
$config['settings'] = true; // If set to true then the settings in the admin interface is enabled 
$config['max_variants'] = "30000"; // Max number of variants allowed for import via bulk import interface
$config['feature_table_name'] = "variants"; // specify the key mysql table that is used for the features (leave as variants if you don't know what this is)
$config['messaging'] = true;
$config['database_structure'] = true; // If set to true then the interface for editing database structure is enabled
// Need to group these in multidimensional array
$config['federated'] = true; // If set to true then the federated API is enabled and allows open discovery of variants
$config['federated_head'] = true; // Sets this install as a head that contains the master list of federated installs and enables the federated interface for adding nodes. The list will get propagated to nodes via the head

$config['show_orcid_reminder'] = false; // After a user logs in shows a reminder (one time only) that they haven't added their ORCID to their profile
$config['atomserver_enabled'] = false; // If set to true then you must have AtomServer installed at the specified URI below
$config['atomserver_user'] = "";
$config['atomserver_password'] = "";
//$config['atomserver_uri'] = $this->config['base_url'] . "atomserver/v1/cafevariome/variants";
$config['atomserver_uri'] = "http://www.cafevariome.org/atomserver/v1/cafevariome/variants";
$config['fonts'] = array(	"Muli" => "Muli",
							"Cabin" => "Cabin",
							"Raleway" => "Raleway",
							"Cantarell" => "Cantarell",
							"Nunito" => "Nunito",
							"Lora" => "Lora",
							"Ubuntu" => "Ubuntu" );
$config['core_fields'] = array("variant_id", "gene", "LRG", "ref", "hgvs", "phenotype", "individual_id", "gender", "ethnicity", "pathogenicity", "location_ref" , "start", "end", "build", "source_url", "comment", "sharing_policy"); // Core fields that are used to generate the core templates for excel and tab-delimited import. N.B. These field names must exactly match the field names that are in the main variants table (case sensitive)
$config['protected_fields'] = array ("cafevariome_id");
$config['protected_groups'] = array ("admin" => "admin", "curator" => "curator", "general" => "general");