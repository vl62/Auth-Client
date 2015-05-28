<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Current {
	function set_current_page() {
		$this->CI =& get_instance();
		$this->CI->load->library("session");
		$this->CI->load->helper('url');
		$current = $this->CI->uri->uri_string();
		
//		error_log("CONTROLLER: -> " . $this->CI->uri->segment(1));
		if ( $this->CI->uri->segment(1) == "curate" ) {
			$this->CI->session->set_userdata('admin_or_curate', 'curate');
//			error_log("CURATE: -> " . $this->CI->uri->segment(1));
		}
		elseif ( $this->CI->uri->segment(1) == "admin" ) {
			$this->CI->session->set_userdata('admin_or_curate', 'admin');
//			error_log("ADMIN: -> " . $this->CI->uri->segment(1));
		} 
		error_log("current -> " . $current);
		// Try to get the current page so it can be used in the session variable return_to
		// Currently a bit hacky - want to ignore the css controller that gets called on every page and also the feed page
		// Also some issues with the main search as the variantcount needs post data so will go to a blank page otherwise, instead redirecting to main discover page (better than nothing))
		if ( ! preg_match("/varioml/i", $current)) {
			if ( $current != "orcid_lookup" ) {
				if ( $current != "css" ) {
					if ( $current != "auth/login" ) {
						if ( $current != "feed" ) {
							if ( $current == "discover/variantcount" && $current == "discover/get_display_fields_for_datatable_head") { // Hack - can't redirect to this page in the controller as it's data sento to the div for the ajax function when counting the number of variants - just go back to main discover page (not great, but better than nothing)
								$this->CI->session->set_userdata('return_to', 'discover');
//								error_log("c variantcount -> " . $this->CI->session->userdata('return_to'));
							}
							else {
								$this->CI->session->set_userdata('return_to', $current);
//								error_log("c -> " . $this->CI->session->userdata('return_to'));
							}
						}
					}
				}
			}
		}
	}
}

?>