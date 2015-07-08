<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Css extends MY_Controller {
	public function index(){	
		$mtype = "text/css";
		header("Content-Type: $mtype");
		$template = $this->config->item('template'); // Allows user to specify a different template css file - configured in the settings table & interface. The value of this setting is used to load the file by this name the in views/css/ directory
		if ( ! $template ) {
			$template = "default";
		}
//		error_log("css -> " . 'pages/css/' . $template);
		$this->load->view('css/' . $template);
	}
}