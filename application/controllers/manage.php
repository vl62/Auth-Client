<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class manage extends MY_Controller {

    // public function __construct() {
    //     parent::__construct();
    // }

    public function index(){


		$this->title = "Cafe Variome - Manage";
		$this->_render('pages/manage');

    }


    function include_subjects(){


		$this->title = "Cafe Variome - Manage";
		$this->_render('pages/include_subjects');
	
		$include_subjects_ids_input = $this->input->get('content', TRUE);
		
		$include_subjects_ids = explode("\n", $include_subjects_ids_input);
		
		// foreach($include_subjects_ids as $value) {
  // 			echo $value;
  // 			echo '<br>';
		// }
		         


    }

    function exclude_subjects(){


		$this->title = "Cafe Variome - Manage";
		$this->_render('pages/exclude_subjects');

    }



}

?>



