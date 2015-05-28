<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Upload extends CI_Upload {

	function testup($field = 'userfile') {
//		error_log("blahblah");
//		error_log("-> " . $_FILES[$field]['name']);
//		$filename = $_FILES[$field]['name'];
		$filename = $this->_prep_filename($_FILES[$field]['name']);
		$file_ext = $this->get_extension($this->file_name);
//		error_log("filename -> " . $filename . " file_ext -> " . $file_ext);
	}
	
	function mupload($configs, $files) {
		if (count($configs) != count($files)) {
			return 'array_count_wrong';
		}
		$retArr = array();
		for ($i = 0, $j = count($files); $i < $j; $i++) {
			$this->initialize($configs[$i]);
			if (!$this->do_upload($files[$i])) {
				array_push($retArr, $this->display_errors());
			} else {
				array_push($retArr, 'OK');
			}
		}
		return($retArr);
	}
}