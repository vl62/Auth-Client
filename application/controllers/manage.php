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

		echo '<div class="container">';

		foreach($include_subjects_ids as $value) {
			
			$query = $this->db->query("SELECT record_id FROM variants WHERE record_id = '".$value."'");
			if ($value == '') {
				continue;
			}
			elseif ($query->num_rows() != 0) {

				$data=array('included'=>1, 'IE_date_time'=> date('Y-m-d H:i:s'));
				$this->db->where('record_id',$value);
				

				if($this->db->update('variants',$data)){
					echo '<p style="text-align: left; color:green"><em>' . 'Successfully included: ' . $value . '</em></p>';
				}else
					{
					echo '<p style="text-align: left; color:red"><em>' . 'Failed to included: ' . $value . '</em></p>';
						}
			}
			else {
				echo '<p style="text-align: left; color:red"><em>' . 'Failed to find: ' . $value . '</em></p>';
					}
			}
			echo '</div>';
    }

    function exclude_subjects(){


		$this->title = "Cafe Variome - Manage";
		$this->_render('pages/exclude_subjects');

		$exclude_subjects_ids_input = $this->input->get('content', TRUE);
		
		$exclude_subjects_ids = explode("\n", $exclude_subjects_ids_input);

		echo '<div class="container">';

		foreach($exclude_subjects_ids as $value) {
			
			$query = $this->db->query("SELECT record_id FROM variants WHERE record_id = '".$value."'");
			
			if ($value == '') {
				continue;
			}
			elseif ($query->num_rows() != 0) {

				$data=array('included'=>0, 'IE_date_time'=>date('Y-m-d H:i:s'));
				$this->db->where('record_id',$value);
				

				if($this->db->update('variants',$data)){
					echo '<p style="text-align: left; color:green"><em>' . 'Successfully excluded: ' . $value . '</em></p>';
				}else
					{
					echo '<p style="text-align: left; color:red"><em>' . 'Failed to excluded: ' . $value . '</em></p>';
					}
			}
			else {
				echo '<p style="text-align: left; color:red"><em>' . 'Failed to find: ' . $value . '</em></p>';
					}
			}

			echo '</div>';

	}

}





