<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Authcheck {
	
	// TODO: optional check to only allow certain user groups to access the site/specific pages
	function is_logged_in() {
		$this->CI =& get_instance();
		$this->CI->load->library("ion_auth");
		$this->CI->load->helper('url');
		$current = $this->CI->uri->uri_string();
//		$segment = $this->CI->uri->segment(2);
		// TODO: Add check (and add setting in db & schema) to see whether the setting for login check is switched on
		if ( strtolower($current) != "css" ) { // Ignore it if the css controller is being called
//			if ( $this->config->item('discovery_requires_login')) {
				if (!$this->CI->ion_auth->logged_in()) {
//					echo "current -> $current";
//					if ( strtolower($current) != "auth/login" && strtolower($current) != "auth/signup") {
					if ( ! preg_match("/.*(login|signup|forgot_password|reset_password|feed|activate).*/", strtolower($current)) ) {
//						show_error("Sorry, in order to access content this site requires you to be <a href='" . base_url() . "auth/login" . "'>logged in</a><br />");
						redirect('auth/login', 'refresh');
					}
//					else {
////						$current_output = $this->CI->output->get_output(); // use this if it's an display_override hook
////						echo $current_output;
//						error_log("okay to display");
//					}
				}
//			}
		}
	}
	
}

?>