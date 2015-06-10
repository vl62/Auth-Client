<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends MY_Controller {
        
	public function __construct() {
		parent::__construct();
		$this->load->model('messages_model');
		
		if (!$this->config->item('messaging')) { // Check to see whether messaging is enabled
			show_error("The messaging system has not been enabled.");
		}
	}
	
	public function index($success = null){	
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
                
                if($success) {
                    $this->data['success_message'] = true;
                }
                
                $user_id = $this->session->userdata("user_id");
		$this->data['user_id'] = $user_id;
		$this->_render('messages/dashboard');

	}

	public function inbox() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}

		if (! $this->session->userdata('inbox_tab')) { // Set session inbox_tab to inbox if it's not already set
			$this->session->set_userdata('inbox_tab', 'inbox');
		}
		$this->data['user_id'] = $user_id = $this->session->userdata("user_id");
		$this->data['messages'] = json_decode(authPostRequest('', array("user_id" => $user_id), $this->config->item('auth_server') . "/api/auth_general/get_inbox_messages"), 1);
		$this->data['sent_messages'] = json_decode(authPostRequest('', array("user_id" => $user_id), $this->config->item('auth_server') . "/api/auth_general/get_sent_messages"), 1);

		$this->_render('messages/inbox');	
//                echo "<pre>";
//                var_dump($this->data['messages']);
//                echo "</pre>";
               
	}
	
	public function send() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}

		
		// Form input validation
//		$this->form_validation->set_rules('message-recipients', 'Message Recipients', 'required');
//		$this->form_validation->set_rules('message-subject', 'Message Subject', 'required');
//		$this->form_validation->set_rules('message-body', 'Message Body', 'required');
//
//		if ($this->form_validation->run() == true) {
//			$sender_id = $this->ion_auth->user()->row()->id;
//			$recipients = $this->input->post('message-recipients');
//			$subject = $this->input->post('message-subject');
//			$body = $this->input->post('message-body');
////			error_log("sender_id -> $sender_id | recipients -> $recipients | subject -> $subject | body -> $body");
//			if (preg_match('/\,/', $recipients)) {
//				$recipients = explode(',', $recipients);
//			}
//			$this->messages_model->send_new_message($sender_id, $recipients, $subject, $body);
////			$this->mahana_messaging->send_new_message($sender_id, $recipients, $subject, $body, $priority = PRIORITY_NORMAL);
//			$this->data['success_message'] = true;
//			$this->data['unread_messages'] = "5";
//			$message_count = $this->messages_model->get_message_count($sender_id);
//			$this->data['unread_messages'] = $message_count;
//			$this->_render('messages/dashboard');
//		}
//		else {
			//display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['recipients'] = array(
				'name' => 'message-recipients',
				'id' => 'messaging-user-input',
				'type' => 'text',
				'value' => $this->form_validation->set_value('message-recipients')
			);
			

//			if ( $this->input->post('message-recipients') ) {
//				$recipients = $this->input->post('message-recipients');
//				$participant_data = array();
//				if (preg_match('/\,/', $recipients)) {
//					$recipients = explode(',', $recipients);
//					foreach ( $recipients as $recipient ) {
//						$user = $this->ion_auth->user($recipient)->row();
//						$p['recipient_id'] = $user->id;
//						$p['username'] = $user->username;
//						$participant_data[] = $p;
//					}
//				}
//				else {
//					$user = $this->ion_auth->user($recipients)->row();
//					$p['recipient_id'] = $user->id;
//					$p['username'] = $user->username;
//					$participant_data[] = $p;
//				}
////				error_log("----> " . print_r($participant_data, 1));
//				$this->data['participant_data'] = $participant_data;
//				
//			}
			
			$this->data['subject'] = array(
				'name'  => 'message-subject',
				'id'    => 'message-subject',
				'type'  => 'text',
				'size'  => '60', 
				'placeholder' => 'Enter your subject...',
				'value' => $this->form_validation->set_value('message-subject')
			);
			
                        $this->data['user_id'] = $this->session->userdata("user_id");
                        
//                        echo "<pre>";
//                        var_dump($this->users_list);
//                        echo "</pre>";
                        
                        $this->data['users'] = json_decode(authGetRequest('', $this->config->item('auth_server') . "/api/auth_general/get_basic_user_info"));
                        
			$this->_render('messages/send');			
//		}
	}
        
        function validate_send() {
            // Form input validation
		$this->form_validation->set_rules('message-recipients', 'Message Recipients', 'required');
		$this->form_validation->set_rules('message-subject', 'Message Subject', 'required');
		$this->form_validation->set_rules('message-body', 'Message Body', 'required');

		if ($this->form_validation->run() == true) {
                    echo json_encode(array('success' => 'no errors'));
                    return;
//			$sender_id = $this->ion_auth->user()->row()->id;
//			$recipients = $this->input->post('message-recipients');
//			$subject = $this->input->post('message-subject');
//			$body = $this->input->post('message-body');
////			error_log("sender_id -> $sender_id | recipients -> $recipients | subject -> $subject | body -> $body");
//			if (preg_match('/\,/', $recipients)) {
//				$recipients = explode(',', $recipients);
//			}
//			$this->messages_model->send_new_message($sender_id, $recipients, $subject, $body);
////			$this->mahana_messaging->send_new_message($sender_id, $recipients, $subject, $body, $priority = PRIORITY_NORMAL);
//			$this->data['success_message'] = true;
//			$this->data['unread_messages'] = "5";
//			$message_count = $this->messages_model->get_message_count($sender_id);
//			$this->data['unread_messages'] = $message_count;
//                        $user_id = $this->session->userdata("user_id");
//                        $this->data['user_id'] = $user_id;
//			$this->_render('messages/dashboard');
		} else {
                    echo json_encode(array('error' => validation_errors()));
                    return;
                }
        }
	
	public function send_message() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}

//		if (!empty($_POST)) {
		if ($this->input->post('message-recipients') && $this->input->post('message-subject') && $this->input->post('message-body')) {
//			error_log("POSTED -> " . print_r($_POST, 1));
			$sender_id = $this->ion_auth->user()->row()->id;
			$recipients = $this->input->post('message-recipients');
			$subject = $this->input->post('message-subject');
			$body = $this->input->post('message-body');
//			error_log("sender_id -> $sender_id | recipients -> $recipients | subject -> $subject | body -> $body");
			if (preg_match('/\,/', $recipients)) {
				$recipients = explode(',', $recipients);
			}
			$this->messages_model->send_new_message($sender_id, $recipients, $subject, $body);
//			$this->mahana_messaging->send_new_message($sender_id, $recipients, $subject, $body, $priority = PRIORITY_NORMAL);
			$this->data['success_message'] = true;
			$this->data['unread_messages'] = "5";
			$message_count = $this->messages_model->get_message_count($sender_id);
			$this->data['unread_messages'] = $message_count;
			$this->_render('messages/dashboard');
		}
	}

	public function view($message_id, $type = NULL) { // $type can be either inbox or sent
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		
		if ( ! $message_id ) {
			show_error("No message ID was specified");
		}
		
		$user_id = $this->session->userdata("user_id");
                
                $message = json_decode(authPostRequest('', array("user_id" => $user_id, "message_id" => $message_id, "type" => $type), $this->config->item('auth_server') . "/api/auth_general/get_message"), 1);
                
		if ( $message['status'] == 1 ) { // If message is marked as unread then mark it as read
                    json_decode(authPostRequest('', array("user_id" => $user_id, "message_id" => $message_id), $this->config->item('auth_server') . "/api/auth_general/update_message_status"), 1);
		}
                
		$this->data['participants'] = json_decode(authPostRequest('', array("thread_id" => $message['thread_id']), $this->config->item('auth_server') . "/api/auth_general/get_thread_participants"), 1);
		$this->data['message'] = $message;
                $this->data['type'] = $type;
		$this->_render('messages/view');
	}
	
	public function reply($message_id) {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}

		$user_id = $this->session->userdata("user_id");
                
                $message = json_decode(authPostRequest('', array("user_id" => $user_id, "message_id" => $message_id), $this->config->item('auth_server') . "/api/auth_general/get_message"), 1);
                $thread_id = json_decode(authPostRequest('', array("message_id" => $message_id), $this->config->item('auth_server') . "/api/auth_general/get_msg_thread"), 1);
		$participants = json_decode(authPostRequest('', array("thread_id" => $thread_id), $this->config->item('auth_server') . "/api/auth_general/get_thread_participants"), 1);
//		print_r($participants);
//		error_log(print_r($participants, 1));
		$this->data['user_id'] = $user_id;
		$this->data['participants'] = $participants;
		$this->data['message_id'] = $message_id;
		$this->data['message'] = $message;
                $this->data['users'] = json_decode(authGetRequest('', $this->config->item('auth_server') . "/api/auth_general/get_basic_user_info"));
		$this->_render('messages/reply');
	}
        
	public function reply_post() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		if (!empty($_POST)) {
			$message_id = $this->input->post('message_id');
			$sender_id = $this->ion_auth->user()->row()->id;
			$subject = $this->input->post('message-subject'); // Maybe not allow them to change subject - instead add in RE:
			$body = $this->input->post('message-body');
			$recipients = $this->input->post('message-recipients');
			
                        json_decode(authPostRequest('', 
                                array("message_id" => $message_id, "sender_id" => $sender_id, "subject" => $subject, "body" => $body,
                                "recipients" => $recipients),
                                $this->config->item('auth_server') . "/api/auth_general/reply_msg"), 1);
//			$this->messages_model->reply_to_message($message_id, $sender_id, $subject, $body, $recipients);

                        redirect('messages/inbox', 'refresh');
//			$threads = $this->mahana_messaging->get_all_threads($user_id);
//			$this->data['threads'] = $threads;
//			$this->_render('messages/inbox');
		}
	}
        
	public function delete($message_id, $type) {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		$user_id = $this->session->userdata('user_id');
                $url = ($type === "inbox") ? "/api/auth_general/update_message_inbox_flag" : "/api/auth_general/update_message_outbox_flag";
                
                $is_deleted = json_decode(authPostRequest('', 
                        array("user_id" => $user_id, "message_id" => $message_id), 
                        $this->config->item('auth_server') . $url), 1);
                
//		$is_deleted = $this->messages_model->delete_message($message_id, $user_id);
		if ( $is_deleted ) {
			redirect('messages/inbox', 'refresh');
		}
		else {
			show_error("Unable to delete message");
		}
	}

	function delete_selected_messages() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		$user_id = $this->ion_auth->user()->row()->id;
		$messages = json_decode($this->input->post('messages'));
//		print_r($variants);
		$success_flag = 1;
		foreach ( $messages as $key => $id ) {
//			error_log("id -> " . $id);
			$is_deleted = $this->messages_model->delete_message($id, $user_id);
			if ( ! $is_deleted ) {
				$success_flag = 0;
			}
		}
		if ( $success_flag ) {
//			echo "Messages were successfully deleted";
			error_log("Messages were successfully deleted");
		}
		else {
//			echo "There was a problem deleting one or more variants";
			error_log("There was a problem deleting one or more messages");
		}
	}
	
	function mark_selected_messages_as_read() {
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		$user_id = $this->ion_auth->user()->row()->id;
		$messages = json_decode($this->input->post('messages'));
//		print_r($variants);
		$success_flag = 1;
		foreach ( $messages as $key => $id ) {
//			error_log("id -> " . $id);
//			$is_read = $this->messages_model->mark_message_as_read($id, $user_id);
			$is_read = $this->messages_model->update_message_status("1", $id, $user_id);
			if ( ! $is_read ) {
				$success_flag = 0;
			}
		}
		if ( $success_flag ) {
//			echo "Variants were successfully deleted";
			error_log("Messages were successfully marked as read");
		}
		else {
//			echo "There was a problem deleting one or more variants";
			error_log("There was a problem marking one or more message as read");
		}
	}
	
	// User lookup - function required for the jquery-tokeninput plugin (function defined when initializing the jquery in the header)
	function lookup_users() {
		$this->load->model('messages_model');
		$keyword = $this->input->get('q');
		$data['response'] = 'false'; //Set default response
		$query = $this->messages_model->lookupUsers($keyword); //Search DB
		if (!empty($query)) {
			$data['response'] = 'true';
			$data['message'] = array();
			$json_array = array();
			foreach ($query->result() as $row) {
				$tmp_array = array("id" => $row->id, "name" => $row->username);
				array_push($json_array, $tmp_array);
			}
		}
//		error_log(print_r(json_encode($json_array), 1));
		echo json_encode($json_array); //echo json string if ajax request
	}
	
}