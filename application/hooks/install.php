<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Install {
	function check() {
		if (is_dir(FCPATH . "install")) {
//			$this->CI =& get_instance();
//			$this->CI->load->library("config");
//			$this->CI->load->config('config');
//			$install_url = $this->CI->config->item('base_url') . "install";
//			$install_url = '<a href="' . $install_url . '">install URL</a>';
			
			if(isset($_SERVER['HTTP_HOST'])) {
				$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
				$base_url .= '://'. $_SERVER['HTTP_HOST'];
				$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
       
				// Base URI (It's different to base URL!)
				$base_uri = parse_url($base_url, PHP_URL_PATH);
				if(substr($base_uri, 0, 1) != '/') $base_uri = '/'.$base_uri;
				if(substr($base_uri, -1, 1) != '/') $base_uri .= '/';
			}
			$install_url = $base_uri . "install";
			$install_dir = FCPATH . "install";
			$install_url = '<a href="' . $install_url . '">install URL</a>';
			show_error('The install directory is still present.<br /><br /><strong>You must delete the install directory manually before you can proceed (' . $install_dir . ').</strong><br /><br />If you have not yet been through the install process (or wish to repeat it), use the following ' . $install_url );

			// Check config options set
////			print_r($this->CI);
////			$this->CI->load->config('database');
//			if ( $this->CI->config->item('email')) {
////				echo "<h2>email " . $this->CI->config->item('email') . "</h2>";
////				echo "<h2>database " . $this->CI->config['username'] . "</h2>";
//				redirect("http://143.210.152.180/cafevariome/install");
//			}
//			else {
//				print "<h2>redirect to install</h2>";
//			}
		}
	}
}

?>