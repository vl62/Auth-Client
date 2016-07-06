<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class manage extends MY_Controller {

    public function index($id = 1) {
		$this->title = "Cafe Variome - Manage";

		$this->load->model('general_model');
		error_log("doing page");

		$tmp = $this->general_model->get_excluded_records($id);
		error_log(print_r($tmp[1],1));
		$this->data['record_ids'] = $tmp[1];
		$count = $tmp[0];
		error_log($count);
		if($count/10 == $id)
			$this->data['final'] = "yes";
		else
			$this->data['final'] = "no";
		$this->data['id'] = $id;
		$this->_render('pages/manage');
    }

    function include_records(){

    	$success_flag = 0;
		$this->title = "Cafe Variome - Manage";
		$this->_render('pages/include_records');
		$include_records_ids_input = $this->input->get('content', TRUE);
		$include_records_reason = $this->input->get('reason', TRUE) ? $this->input->get('reason', TRUE) : "N/A";
		error_log($include_records_reason);
		$include_records_ids = explode("\n", $include_records_ids_input);
		$user_id = $this->ion_auth->user()->row()->id;  // get user id 1st June

		echo '<div class="container">';
		foreach($include_records_ids as $value) {
			
			$query = $this->db->query("SELECT record_id FROM variants WHERE record_id = '".$value."' LIMIT 1");
			if ($value == '') continue;
			elseif ($query->num_rows() != 0) {
				$query_included  = $this->db->query("SELECT included FROM variants WHERE record_id = '".$value."' LIMIT 1");
				$included = $query_included->row();
				$included = $included->included;

				if ($included == 1) {
			 		echo '<p style="text-align: left; color:blue"><em>' . 'Already included: ' . $value . '</em></p>';
			 		$success_flag = 1;
				} else if ($included == 0) {
					$data=array('included'=>1, 'IE_date_time'=> date('Y-m-d H:i:s'));
					$this->db->where('record_id',$value);					
					if($this->db->update('variants',$data)) {
						$log_data = array(
										'record_id' => $value, 
										'action' => 'included', 
										'user' => $user_id, 
										'date_time' =>  date('Y-m-d H:i:s')
										); // added 1st june
						$this->db->insert('IElog',$log_data);  // added 1st June					
						echo '<p style="text-align: left; color:green"><em>' . 'Successfully included: ' . $value . '</em></p>';
						$success_flag = 1;
					}
				} else {
					echo '<p style="text-align: left; color:red"><em>' . 'Failed to included: ' . $value . '</em></p>';
				}
			} else {
				echo '<p style="text-align: left; color:red"><em>' . 'Failed to find: ' . $value . '</em></p>';
			}
		}
		echo '</div>';

		if ($success_flag) {
		    if (file_exists("resources/elastic_search_status_complete"))
		        unlink("resources/elastic_search_status_complete");
		    file_put_contents("resources/elastic_search_status_incomplete", "");
        } 
    }

    function exclude_records(){
    	$success_flag = 0;
		$this->title = "Cafe Variome - Manage";
		$this->_render('pages/exclude_records');
		$exclude_records_ids_input = $this->input->get('content', TRUE);
		$exclude_records_ids = explode("\n", $exclude_records_ids_input);
		$user_id = $this->ion_auth->user()->row()->id;  // get user id 1st June

		echo '<div class="container">';
		foreach($exclude_records_ids as $value) {
			
			$query = $this->db->query("SELECT record_id FROM variants WHERE record_id = '".$value."' LIMIT 1");
			
			if ($value == '') continue;
			elseif ($query->num_rows() != 0) {
				$query_included  = $this->db->query("SELECT included FROM variants WHERE record_id = '".$value."' LIMIT 1");
				$included = $query_included->row();
				$included = $included->included;

				if ($included == 0){
			 		echo '<p style="text-align: left; color:blue"><em>' . 'Already excluded: ' . $value . '</em></p>';
			 		$success_flag = 1;
				} else if ($included == 1) {
					$data=array('included'=>0, 'IE_date_time'=> date('Y-m-d H:i:s'));
					$this->db->where('record_id',$value);

					if($this->db->update('variants',$data)) {
						$log_data = array('record_id' => $value, 'action' => 'excluded', 'user' => $user_id, 'date_time' =>  date('Y-m-d H:i:s')); // added 1st june
						$this->db->insert('IElog',$log_data);  // added 1st June					
						echo '<p style="text-align: left; color:green"><em>' . 'Successfully excluded: ' . $value . '</em></p>';
						$success_flag = 1;
					}
				}
				else
					echo '<p style="text-align: left; color:red"><em>' . 'Failed to excluded: ' . $value . '</em></p>';
			}
			else {
				echo '<p style="text-align: left; color:red"><em>' . 'Failed to find: ' . $value . '</em></p>';
			}
		}
		echo '</div>';

		if ($success_flag) {
		    if (file_exists("resources/elastic_search_status_complete"))
		        unlink("resources/elastic_search_status_complete");
		    file_put_contents("resources/elastic_search_status_incomplete", "");
        } 
	}
}





