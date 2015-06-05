<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Configs {
    function get_configs() {
		// Old way to get configs was this (but this only worked when it's a post-controller-construction hook and not when pre-controller)
//		$CI =& get_instance();
//		$query = $CI->db->get('settings');
		
		// New way
		require_once BASEPATH.'database/DB.php';
		$db = DB('default', true);
		$query = $db->get('settings');
		$CFG =& load_class('Config', 'core');
//		
		$c = 0;
		foreach ($query->result_array() as $row) {
//			print_r($row);
			$c++;
//			error_log("name -> " . $row['name'] . " value -> " . $row['value']);

			if ( $row['value'] == "off") {
//				error_log("FALSE name -> " . $row['name'] . " value -> " . $row['value']);
//				$CI->config->set_item($row['name'], false);
				$CFG->set_item($row['name'], false);
			}
			else {
//				$CI->config->set_item($row['name'], $row['value']);
				$CFG->set_item($row['name'], $row['value']);
			}
		}
    }
	
}

?>