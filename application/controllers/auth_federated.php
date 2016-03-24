<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_federated extends MY_Controller {

	function __construct()
	{
		parent::__construct();
                
                if($this->session->userdata("controller") === "auth") {
                    show_error("Restricted Access!");
                }
                
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');

		// Load MongoDB library instead of native db driver if required
		$this->config->item('use_mongodb', 'ion_auth') ?
		$this->load->library('mongo_db') :

		$this->load->database();
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	}
        
	//redirect if needed, otherwise display the user list
	function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth_federated/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin())
		{
			//redirect them to the home page because they must be an administrator to view this
			redirect('/', 'refresh');
		}
		else
		{
			redirect('auth_federated/users', 'refresh');
		}
	}
        
	//log the user in
	function login()
	{
		if ($this->ion_auth->logged_in()) {
			redirect('/', 'refresh');
		}
                
                $fp = @fsockopen("auth.cafevariome.org", 80, $errno, $errstr, 30);
                if (!$fp) {
                    redirect(base_url("/auth/login"));
                }
                
		$this->title = "Login";
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['identity'] = array('name' => 'identity',
                        'id' => 'identity',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('identity'),
                );
                $this->data['password'] = array('name' => 'password',
                        'id' => 'password',
                        'type' => 'password',
                );
                $this->_render('federated/auth/login');
	}
        
        function validate_login() {
            
            if(! $this->input->is_ajax_request()) {
                    redirect('404');
            }

            $this->form_validation->set_rules('identity', 'Identity', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
                
            if($this->form_validation->run()) {
                echo json_encode(array('success' => 'no errors'));
                return;
            } else {
                echo json_encode(array('error' => validation_errors()));
                return;
            }
        }
        
        function login_success() {
            
            if(! $this->input->is_ajax_request()) {
                    redirect('404');
            }

            error_log("User: " . $this->input->post('email') . " has logged in || " . date("Y-m-d H:i:s"));

            // error_log("Session_data: " . print_r($this->input->post(), 1));
            
                $session_data = array(
                    'user_id'                   => $this->input->post('user_id'),
                    'ip_address'                => $this->input->post('ip_address'),
                    'username'                  => $this->input->post('username'),
                    'password'                  => $this->input->post('password'),
                    'salt'                      => $this->input->post('salt'),
                    'email'                     => $this->input->post('email'),
                    'activation_code'           => $this->input->post('activation_code'),
                    'forgotten_password_code'   => $this->input->post('forgotten_password_code'),
                    'forgotten_password_time'   => $this->input->post('forgotten_password_time'),
                    'remember_code'             => $this->input->post('remember_code'),
                    'created_on'                => $this->input->post('created_on'),
                    'old_last_login'            => $this->input->post('last_login'),
                    'active'                    => $this->input->post('active'),
                    'first_name'                => $this->input->post('first_name'),
                    'last_name'                 => $this->input->post('last_name'),
                    'company'                   => $this->input->post('company'),
                    'orcid'                     => $this->input->post('orcid'),
                    'is_admin'                  => $this->input->post('is_admin') === "admin" ? TRUE : FALSE,
                    'Token'                     => $this->input->post('Token'),
                    'controller'                => "auth_federated",
                    'email_notification'        => $this->input->post('email_notification'),
                    'query_builder_basic'       => $this->input->post('query_builder_basic'),
                    'query_builder_advanced'    => $this->input->post('query_builder_advanced'),
                    'query_builder_precan'      => $this->input->post('query_builder_precan'),
                    'view_derids'               => $this->input->post('view_derids')
                );
                
                $this->session->set_userdata($session_data);

                $result = $this->ion_auth_model->custom_register($session_data);
                
                echo json_encode(array('success' => "no error"));
                return;
                
        }
        
	//log the user out
	function logout()
	{
        if($this->session->userdata('email'))
            error_log("User: " . $this->session->userdata('email') . " has logged out || " . date("Y-m-d H:i:s"));
//                if (get_cookie('identity')) delete_cookie('identity');
//                if (get_cookie('remember_code'))    delete_cookie('remember_code');

                $this->session->destroy();
                
//                $session_data = array(
//                    'user_id'                   => '',
//                    'ip_address'                => '',
//                    'username'                  => '',
//                    'password'                  => '',
//                    'salt'                      => '',
//                    'email'                     => '',
//                    'activation_code'           => '',
//                    'forgotten_password_code'   => '',
//                    'forgotten_password_time'   => '',
//                    'remember_code'             => '',
//                    'created_on'                => '',
//                    'old_last_login'            => '',
//                    'active'                    => '',
//                    'first_name'                => '',
//                    'last_name'                 => '',
//                    'company'                   => '',
//                    'orcid'                     => '',
//                    'is_admin'                  => ''
//                );
                
                redirect('home', 'refresh');
	}

	//forgot password
	function forgot_password()
	{
                //setup the input
                $this->data['email'] = array('name' => 'email', 'id' => 'email');

                //set any errors and display the form
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
                $this->_render('federated/auth/forgot_password');
	}
        
        function validate_forgot_password() {
            
            if(! $this->input->is_ajax_request()) {
                    redirect('404');
            }
            
                $this->form_validation->set_error_delimiters('', '');
                $this->form_validation->set_rules('email', 'Email Address', 'required');
                
                if($this->form_validation->run()) {
                    echo json_encode(array('success' => 'no errors'));
                    return;
                } else {
                    echo json_encode(array('error' => validation_errors()));
                    return;
                }
                
                // get identity for that email
                $config_tables = $this->config->item('tables', 'ion_auth');
                $identity = $this->db->where('email', $this->input->post('email'))->limit('1')->get($config_tables['users'])->row();

                //run the forgotten password method to email an activation code to the user
                $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

                if ($forgotten)
                {
                        //if there were no errors
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("auth_federated/login", 'refresh'); //we should display a confirmation page here instead of the login page
                }
                else
                {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect("auth_federated/forgot_password", 'refresh');
                }
        }
        
        function success_forgot_password($email) {
            
            if(! $this->input->is_ajax_request()) {
                    redirect('404');
            }
            
            $this->data['email'] = urldecode($email);
            $this->_render("federated/auth/forgot-password-success");
        }

	//reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{       
                if(! $this->input->is_ajax_request()) {
                    redirect('404');
                }
                
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			//if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

			if ($this->form_validation->run() == false)
			{
				//display the form

				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
				'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				//render
				$this->_render('federated/auth/reset_password');
//				$this->load->view('auth/reset_password', $this->data);
			}
			else
			{
				// do we have a valid request?
				
//				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				if ($user->id != $this->input->post('user_id'))
				{

					//something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error('This form post did not pass our security checks.');

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						//if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						$this->logout();
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth_federated/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth_federated/forgot_password", 'refresh');
		}
	}

	//activate the user
	function activate($id, $code=false)
	{
            
            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
            {
                    //redirect them to the login page
                    redirect('/', 'refresh');
            }
            
            $this->session->set_userdata(array("userId" => $id));
            $this->data['id'] = $id;
            $this->_render('federated/auth/activate_user');

//		if ($code !== false)
//		{
//			$activation = $this->ion_auth->activate($id, $code);
//		}
//		else if ($this->ion_auth->is_admin())
//		{
//			$activation = $this->ion_auth->activate($id);
//		}
//
//		if ($activation)
//		{
//			$this->session->set_flashdata('message', $this->ion_auth->messages());
//			
//			// If admin then it was a manual activatation from the admin menu so return to the user list
//			if ($this->ion_auth->is_admin()) {
//				redirect('auth_federated/users', 'refresh');
//			}
//			// Activation was done via email by the user - direct them to the success message
//			else {
//				$this->_render('federated/auth/activate-success');
//			}
//		}
//		else
//		{
//			//redirect them to the forgot password page
//			$this->session->set_flashdata('message', $this->ion_auth->errors());
//			redirect("auth_federated/forgot_password", 'refresh');
//		}
	}
        
        function validate_activate() {
            if(! $this->input->is_ajax_request()) {
                    redirect('404');
            }
            
            $this->load->library('form_validation');
            $this->form_validation->set_rules('confirm', 'confirmation', 'required');
            $this->form_validation->set_rules('id', 'user ID', 'required|alpha_numeric');
            
            if (($this->input->post('confirm') == 'yes') && $this->form_validation->run() === TRUE)
            {       
                    // do we have a valid request?
                    if ($this->session->userdata("userId") != $this->input->post('id'))
                    {
                            echo json_encode(array('error' => "This form post did not pass our security checks."));
                            return;
                    }

                    // do we have the right userlevel?
                    if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
                    {       
                            $this->session->unset_userdata("userId");
                            echo json_encode(array('success' => 'no errors'));
                            return;
//                            $this->ion_auth->deactivate($id);
                    }
            } elseif(($this->input->post('confirm') == 'no') && $this->form_validation->run() === TRUE) {
                    
                    $this->session->unset_userdata("userId");
                    echo json_encode(array('error' => "no"));
                
            } else {
                    $this->session->unset_userdata("userId");
                    echo json_encode(array('error' => validation_errors()));
                    return;
            }
        }
        
	//deactivate the user
	function deactivate($id = NULL)
	{
            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
            {
                    //redirect them to the login page
                    redirect('/', 'refresh');
            }
            
                $this->session->set_userdata(array("userId" => $id));
                
//                $this->data['csrf'] = $this->_get_csrf_nonce();
//                $this->data['user'] = $this->ion_auth->user($id)->row();
                $this->data['id'] = $id;
                $this->_render('federated/auth/deactivate_user');
                
	}
        
        function validate_deactivate() {
            
            if(! $this->input->is_ajax_request()) {
                    redirect('404');
            }
            
//            $id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

            $this->load->library('form_validation');
            $this->form_validation->set_rules('confirm', 'confirmation', 'required');
            $this->form_validation->set_rules('id', 'user ID', 'required|alpha_numeric');
            
            if (($this->input->post('confirm') == 'yes') && $this->form_validation->run() === TRUE)
            {       
                    // do we have a valid request?
                    if ($this->session->userdata("userId") != $this->input->post('id'))
                    {
                            echo json_encode(array('error' => "This form post did not pass our security checks."));
                            return;
                    }

                    // do we have the right userlevel?
                    if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
                    {       
                            $this->session->unset_userdata("userId");
                            echo json_encode(array('success' => 'no errors'));
                            return;
//                            $this->ion_auth->deactivate($id);
                    }
            } elseif(($this->input->post('confirm') == 'no') && $this->form_validation->run() === TRUE) {
                    
                    $this->session->unset_userdata("userId");
                    echo json_encode(array('error' => "no"));
                
            } else {
                    $this->session->unset_userdata("userId");
                    echo json_encode(array('error' => validation_errors()));
                    return;
            }

            //redirect them back to the auth page
//            redirect('auth_federated/users', 'refresh');
        }

	//delete the user
	function delete($id = NULL)
	{
            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
            {
                redirect('/', 'refresh');
            }
//		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

                        $this->session->set_userdata(array("userId" => $id));
			// insert csrf check
//			$this->data['csrf'] = $this->_get_csrf_nonce();
//			$this->data['user'] = $this->ion_auth->user($id)->row();
                        $this->data['id'] = $id;
			$this->_render('federated/auth/delete_user');
//			$this->load->view('auth/deactivate_user', $this->data);
			// do we really want to deactivate?
			

			//redirect them back to the auth page
//			redirect('auth_federated/users', 'refresh');
	}
        
        function validate_delete() {
            
            if(! $this->input->is_ajax_request()) {
                    redirect('404');
            }
            
            $this->load->library('form_validation');
            $this->form_validation->set_rules('confirm', 'confirmation', 'required');
            $this->form_validation->set_rules('id', 'user ID', 'required|alpha_numeric');
            
            if ($this->input->post('confirm') == 'yes' && $this->form_validation->run() === TRUE)
            {
                    if ($this->session->userdata("userId") != $this->input->post('id'))
                    {
                            echo json_encode(array('error' => "This form post did not pass our security checks."));
                            return;
                    }

                    // do we have the right userlevel?
                    if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
                    {
                            $this->session->unset_userdata("userId");
                            echo json_encode(array('success' => 'no errors'));
                            return;
                    }
            } elseif(($this->input->post('confirm') == 'no') && $this->form_validation->run() === TRUE) {
            
                $this->session->unset_userdata("userId");
                echo json_encode(array('error' => "no"));
        
            } else {
                $this->session->unset_userdata("userId");
                echo json_encode(array('error' => validation_errors()));
                return;
            }
            
        }

	function users() {
		$this->title = "Users";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('/', 'refresh');
		}

		$this->data['message'] = $this->session->flashdata('activation_email_unsuccessful');
		$token = $this->session->userdata('Token');
//		error_log("token -> " . $token);
		//list the users
//		echo $this->config->item('installation_key');
//		$users = authPostRequest('', array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_users_and_network_groups_for_installation");
//		$users = authPostRequest($token, array(), $this->config->item('auth_server') . "/api/auth/get_all_users");
		$users = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_all_users_editable_by_admin_in_installation");
//		error_log("users -> $users");
		$this->data['users'] = json_decode($users);
                
//		for($i = 0; $i<count($this->data['users']); $i++) {
//			if($this->config->item('installation_key') != $this->data['users'][$i]->installation_key) {
//				$this->data['users'][$i]->id = "";
//			}
//		}
                
//		$this->data['users'] = $this->ion_auth->users()->result();
		$users_groups_data = json_decode(authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_current_network_groups_for_users_in_installation"), 1);
//		error_log("users_groups_data -> $users_groups_data");
		$users_groups = array();
		// If there were groups fetch from auth server for users then add them to the view
		if (! array_key_exists('error', $users_groups_data)) {
			foreach ( $users_groups_data as $group ) {
//				print_r($group);
				$users_groups[$group['user_id']][] = array('network_name' => $group['network_name'], 'group_id' => $group['group_id'], 'group_name' => $group['name'], 'group_description' => $group['description']);
			}
			$this->data['users_groups'] = $users_groups;
		}

		$this->_render('federated/auth/users');
	}
	
	//signup and register
	function signup() {
            
		$this->title = "Registration";
		
		if ( ! $this->config->item('allow_registrations') ) {
			show_error("Sorry, registrations have been disabled for this Cafe Variome instance");
		}
		
		if (!$this->ion_auth->logged_in()) {
			//validate form input
			
//			$this->form_validation->set_rules('captcha', 'captcha', 'required|callback_check_captcha');

			
			// setup textCAPTCHA using API from http://jrtashjian.com/2011/01/codeigniter-form-with-text-captcha/ - TODO: add in config for user defined key for API
//			try {
//				$xml = @new SimpleXMLElement('http://textcaptcha.com/api/3y8acmeigyucwgw40wgscw0gc1x3tkwc', NULL, TRUE);
//			} catch ( Exception $e ) {
//				$fallback  = '';
//				$fallback .= 'Is ice hot or cold?';
//				$fallback .= ''.md5('cold').'';
//				$fallback .= '';
//				$xml = new SimpleXMLElement($fallback);
//			}
//			// store answers in session for use later
//			$answers = array();
//			foreach( $xml->answer as $hash ) {
//				$answers[] = (string)$hash;
//			}
//			$this->session->set_userdata('captcha_answers', $answers);

			// load vars into view
//			$this->load->vars(array( 'captcha' => (string)$xml->question ));
			
                    //display the create user form
                    //set the flash data error message if there is one
                    $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
//				print_r($this->data['message']);
                    $this->data['username'] = array(
                            'name' => 'username',
                            'id' => 'username',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('username'),
                    );
                    $this->data['first_name'] = array(
                            'name' => 'first_name',
                            'id' => 'first_name',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('first_name'),
                    );
                    $this->data['last_name'] = array(
                            'name' => 'last_name',
                            'id' => 'last_name',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('last_name'),
                    );
                    $this->data['email'] = array(
                            'name' => 'email',
                            'id' => 'email',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('email'),
                    );
                    $this->data['company'] = array(
                            'name' => 'company',
                            'id' => 'company',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('company'),
                    );
                    $this->data['orcid'] = array(
                            'name' => 'orcid',
                            'id' => 'orcid',
                            'type' => 'text',
                            'value' => $this->form_validation->set_value('orcid'),
                    );
                    $this->data['password'] = array(
                            'name' => 'password',
                            'id' => 'password',
                            'type' => 'password',
                            'value' => $this->form_validation->set_value('password'),
                    );
                    $this->data['password_confirm'] = array(
                            'name' => 'password_confirm',
                            'id' => 'password_confirm',
                            'type' => 'password',
                            'value' => $this->form_validation->set_value('password_confirm'),
                    );

                    $this->_render('federated/auth/signup');
		}
		else {
			redirect('home', 'refresh');
		}
    }
    
    function validate_signup() {
        if(! $this->input->is_ajax_request()) {
                    redirect('404');
        }
        
//        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('username', 'Username', 'required|xss_clean|alpha_numeric');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
        $this->form_validation->set_rules('company', 'Institute Name', 'required|xss_clean');
        $this->form_validation->set_rules('orcid', 'ORCID', 'xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
        
        if($this->form_validation->run()) {
            echo json_encode(array('success' => 'no errors'));
            return;
        } else {
            echo json_encode(array('error' => validation_errors()));
            return;
        }
    }
    
    function signup_success($email = "") {
        
        if(! $this->input->is_ajax_request()) {
                    redirect('404');
        }
            
        if(!empty($email)) {
            $this->data['email'] = urldecode($email);
            $this->_render("federated/auth/signup-success");
        } else {
            $this->_render("federated/auth/signup-success-manual");
        }
    }
        
    function check_captcha( $string ) {
		$user_answer = md5(strtolower(trim($string)));
		$answers = $this->session->userdata('captcha_answers');
		if( in_array($user_answer, $answers) ) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('check_captcha', 'Your answer was incorrect!');
			return FALSE;
		}
	}
	
	function orcid_lookup() {
		$orcid = $this->input->post('orcid');
		$orcid_url = "http://pub.orcid.org/" . $orcid;
		$orcid_xml =  simplexml_load_file($orcid_url);
		$given_name = $orcid_xml->{'orcid-profile'}->{'orcid-bio'}->{'personal-details'}->{'given-names'};
		$family_name = $orcid_xml->{'orcid-profile'}->{'orcid-bio'}->{'personal-details'}->{'family-name'};
		$orcid_data = array();
		$orcid_data['givenname'] = (string) $given_name;
		$orcid_data['familyname'] = (string) $family_name;
//		echo json_encode($orcid_data, JSON_FORCE_OBJECT);
		echo json_encode($orcid_data);
	}

	function orcid_test($provider) {
		$this->load->library('oauth2/OAuth2');
		$provider = $this->oauth2->provider($provider, array(
			'id' => '0000-0002-3079-5989',
			'secret' => '0cd4c948-2afc-4813-a152-9ab65c079e59',
		));
		if ( ! $this->input->get('code')) { // Means that the isn't any code yet (from authorization process) so need to run this first
			error_log("here1");
			// By sending no options it'll come back here
			$options['redirect_uri'] = 'http://dev.cafevariome.org/auth/orcid_test/Orcid';
			$provider->authorize($options);
		}
		else { // There's an authorization code, need to now exchange this for an access token
			// Howzit?
			error_log("here");
			try {
				$code = $_GET['code'];
				echo "code -> $code";
				$token = $provider->access($_GET['code']);
//				$user = $provider->get_user_info($token);
				// Here you should use this information to A) look for a user B) help a new user sign up with existing data.
				// If you store it all in a cookie and redirect to a registration page this is crazy-simple.
				echo "Tokens: ";
				var_dump($token);
//				echo "\n\nUser Info: ";
//				var_dump($user);
			}
			catch (OAuth2_Exception $e) {
				show_error('That didnt work: '.$e);
			}
		}
	}
		
	//create a new user (in admin interface, see signup for standard non-admin user registration)
	function create_user()
	{       
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('/', 'refresh');
		}
                
		$this->title = "Create User";
				// Get all the available network groups for this installation
				$token = $this->session->userdata('Token');
                $groups = authPostRequest($token, array('installation_key' => $this->config->item('installation_key'), 'url' => base_url()), $this->config->item('auth_server') . "/api/auth/get_network_groups_for_installation");
//                error_log(print_r($groups, 1));
				$this->data['groups'] = json_decode($groups, TRUE);

                //display the create user form
                //set the flash data error message if there is one
                $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));


                $this->data['username'] = array(
                        'name' => 'username',
                        'id' => 'username',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('email'),
                );
                $this->data['first_name'] = array(
                        'name'  => 'first_name',
                        'id'    => 'first_name',
                        'type'  => 'text',
                        'value' => $this->form_validation->set_value('first_name'),
                );
                $this->data['last_name'] = array(
                        'name'  => 'last_name',
                        'id'    => 'last_name',
                        'type'  => 'text',
                        'value' => $this->form_validation->set_value('last_name'),
                );
                $this->data['email'] = array(
                        'name'  => 'email',
                        'id'    => 'email',
                        'type'  => 'text',
                        'value' => $this->form_validation->set_value('email'),
                );
                $this->data['company'] = array(
                        'name'  => 'company',
                        'id'    => 'company',
                        'type'  => 'text',
                        'value' => $this->form_validation->set_value('company'),
                );
                $this->data['password'] = array(
                        'name'  => 'password',
                        'id'    => 'password',
                        'type'  => 'password',
                        'value' => $this->form_validation->set_value('password'),
                );
                $this->data['password_confirm'] = array(
                        'name'  => 'password_confirm',
                        'id'    => 'password_confirm',
                        'type'  => 'password',
                        'value' => $this->form_validation->set_value('password_confirm'),
                );
                $this->data['orcid'] = array(
                        'name' => 'orcid',
                        'id' => 'orcid',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('orcid'),
                );
                $this->_render('federated/auth/create_user');
//			$this->load->view('auth/create_user', $this->data);
	}
        
        function validate_create_user() {
            
            if(! $this->input->is_ajax_request()) {
                    redirect('404');
            }
            
            //validate form input
            // $this->form_validation->set_rules('username', 'Username', 'required|xss_clean|alpha_dash');
            $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
            $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
            $this->form_validation->set_rules('company', 'Institute Name', 'required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
            $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
            $this->form_validation->set_rules('orcid', 'ORCID', 'xss_clean');
            
			
			
			
            if ($this->form_validation->run() == true)
            {   echo json_encode(array('success' => "no errors"));
                return;    
            } else {
                echo json_encode(array('error' => validation_errors()));
                return;
            }
            
        }
        
//        private function _get_server_status() {
//                if(get_headers("https://auth.cafevariome.org/")[0] === "HTTP/1.1 200 OK")
//                    return TRUE;
//                else
//                    return FALSE;
//        }
        
	//edit a user
	function edit_user($id)
	{

		$this->title = "Edit User";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth_federated', 'refresh');
		}
		
//		Local ion_auth call no longer used, need to fetch the user information for auth server via API call instead
		$token = $this->session->userdata('Token');
		$user_json = authPostRequest($token, array('user_id' => $id, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_user_by_id");
		$user = json_decode($user_json);

		if (property_exists($user, 'error')) {
			show_error('You do not have permissions to edit this user');
		}
                
//		error_log("USER -> " . print_r($user, 1));
//		$user = $this->ion_auth->user($id)->row();
		$this->session->set_userdata(array("userId" => $user->id));
                
		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['user'] = $user;

		// Get all the available network groups for this installation
		$groups = json_decode(authPostRequest($token, array('installation_key' => $this->config->item('installation_key'), 'url' => base_url()), $this->config->item('auth_server') . "/api/auth/get_network_groups_for_installation"), TRUE);
		if (! array_key_exists('error', $groups)) {
			$this->data['groups'] = $groups;
		}
		// Get all the network groups that this user from this installation is currently in so that these can be pre selected in the multiselect list
		$returned_groups = authPostRequest($token, array('user_id' => $id, 'installation_key' => $this->config->item('installation_key'), 'url' => base_url()), $this->config->item('auth_server') . "/api/auth/get_current_network_groups_for_user_in_installation");
		$tmp_selected_groups = json_decode($returned_groups, TRUE);
		$selected_groups = array();
		if (! array_key_exists('error', $tmp_selected_groups)) {
			foreach ( $tmp_selected_groups as $tmp_group ) {
				$selected_groups[$tmp_group['group_id']] = "group_description";
			}
			$this->data['selected_groups'] = $selected_groups;
		}


		
		$this->data['username'] = array(
			'name' => 'username',
			'id' => 'username',
			'type' => 'text',
			'value' => $this->form_validation->set_value('username', $user->username),
		);
		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$this->data['email'] = array(
			'name'  => 'email',
			'id'    => 'email',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('email', $user->email),
		);
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);
		$this->data['orcid'] = array(
			'name' => 'orcid',
			'id' => 'orcid',
			'type' => 'text',
			'value' => $this->form_validation->set_value('orcid', $user->orcid),
		);
		$this->_render('federated/auth/edit_user');
//		$this->load->view('auth/edit_user', $this->data);
	}
        
        function validate_edit_user() {
            
            if(! $this->input->is_ajax_request()) {
                    redirect('404');
            }
            
            //validate form input
            // $this->form_validation->set_rules('username', 'Username', 'required|xss_clean|alpha_dash');
            $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
            $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
            $this->form_validation->set_rules('company', 'Institute Name', 'required|xss_clean');
            $this->form_validation->set_rules('orcid', 'ORCID', 'xss_clean');

            if (isset($_POST) && !empty($_POST))
            {
                    // do we have a valid request?
                    if ($this->session->userdata("userId") != $this->input->post('id'))
                    {       echo json_encode(array('error' => "This form post did not pass our security checks."));
                            return;
                    } 
                    
                    if ($this->form_validation->run() === TRUE)
                    {
                        error_log("true");
                        $this->session->unset_userdata("userId");
                        echo json_encode(array('success' => 'no errors'));
                        return;
                    } else {
                        error_log("false");
                        echo json_encode(array('error' => validation_errors()));
                        return;
                    }
            }
        }
	
	
	function edit_user_network_groups_old($id) {

		$this->title = "Edit User Network Groups";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth_federated', 'refresh');
		}

//		if (property_exists($user, 'error')) {
//			show_error('You do not have permissions to edit this user');
//		}
                
//		error_log("USER -> " . print_r($user, 1));
//		$user = $this->ion_auth->user($id)->row();
//		$this->session->set_userdata(array("userId" => $user->id));
		$this->data['user_id'] = $id;
		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));


		// Get all the available network groups for this installation
		$token = $this->session->userdata('Token');
		$groups = json_decode(authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_network_groups_for_installation"), TRUE);
		if (! array_key_exists('error', $groups)) {
			$this->data['groups'] = $groups;
		}
		// Get all the network groups that this user from this installation is currently in so that these can be pre selected in the multiselect list
		$returned_groups = authPostRequest($token, array('user_id' => $id, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_current_network_groups_for_user_in_installation");
		$tmp_selected_groups = json_decode($returned_groups, TRUE);
		$selected_groups = array();
		if (! array_key_exists('error', $tmp_selected_groups)) {
			foreach ( $tmp_selected_groups as $tmp_group ) {
				$selected_groups[$tmp_group['group_id']] = "group_description";
			}
			$this->data['selected_groups'] = $selected_groups;
		}

		$this->_render('federated/auth/edit_user_network_groups');
//		$this->load->view('auth/edit_user', $this->data);
	}

    function edit_user_network_groups($id, $isMaster = false) {

        $this->title = "Edit User Network Groups";

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth_federated', 'refresh');
        }

        $this->data['user_id'] = $id;
        //display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $token = $this->session->userdata('Token');
        if($isMaster) {
            $users = authPostRequest($token, array('installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth/get_all_users_in_cafevariome");
            $users = json_decode($users, 1);
        } else {
            $users = authPostRequest($token, array('installation_key' => $this->config->item('installation_key'), 'group_id' => $id), $this->config->item('auth_server') . "/api/auth/get_all_users_in_master_network_group");
            $users = json_decode($users, 1);
        }

        $group_users = authPostRequest($token, array('installation_key' => $this->config->item('installation_key'), 'group_id' => $id), $this->config->item('auth_server') . "/api/auth/get_users_for_network_group");
        $group_users = json_decode($group_users, 1);

        $group_details = json_decode(authPostRequest($token, array('installation_key' => $this->config->item('installation_key'), 'group_id' => $id), $this->config->item('auth_server') . "/api/auth/get_network_name_and_type_for_id"), TRUE);

        $this->data['name'] = $group_details[0]['name'];   
        $this->data['group_type'] = $group_details[0]['group_type']; 

        // echo "<pre>";
        // var_dump($users);
        // var_dump($group_users);
        // echo "</pre>";
        // return;

        if(!$isMaster) {
            $this->load->model('federated_model');
            $sources = $this->federated_model->get_sources();

            $group_sources = json_decode(authPostRequest($token, array('installation_key' => $this->config->item('installation_key'), 'group_id' => $id), $this->config->item('auth_server') . "/api/auth/get_sources_for_group"), TRUE);

            // error_log(print_r($sources, 1));
            // error_log(print_r($group_sources, 1));

            $ids = [];
            foreach ($group_sources as $key => $value)
                $ids[] = $value['source_id'];

            if($ids)
                $group_sources = $this->federated_model->add_source_name_to_ids($ids);

            // error_log(print_r($group_sources, 1));

            $sources_left = []; 
            $sources_right = [];

            foreach ($sources as $key => $value)
                $sources_left[$value['source_id']] = $value['name'];

            foreach ($group_sources as $key => $value)
                $sources_right[$value['source_id']] = $value['name'];

            foreach ($sources_right as $key => $value) {
                if(array_key_exists($key, $sources_left))
                    unset($sources_left[$key]);
            }

            // error_log(print_r($sources_left, 1));
            // error_log(print_r($sources_right, 1));

            $this->data['sources_left'] = $sources_left;
            $this->data['sources_right'] = $sources_right;
        }

        if($isMaster) {
            for($i = 0; $i < count($users); $i++) {
                if($users[$i]['username'] == "admin@cafevariome") {
                    unset($users[$i]);
                    $users = array_values($users);
                }
            }
        }

        if (!array_key_exists('error', $group_users)) {
            foreach ($group_users as $group_user) {
                for($i = 0; $i < count($users); $i++) {
                    if($group_user == $users[$i]) {
                        unset($users[$i]);
                        $users = array_values($users);
                    }
                }
            }
            $this->data['group_users'] = $group_users;   
        }

        if($isMaster)
            $this->data['users'] = $users;
        else {
            if(!array_key_exists('error', $users))  $this->data['users'] = $users;
        }
        
        $this->data['isMaster'] = $isMaster;
        $this->data['user_id'] = $id;
        $this->data['installation_key'] = $this->config->item('installation_key');
        $this->_render('federated/auth/edit_network_groups_users');

    }
		
	//view user profile (for non-admin user)
	function user_profile($id)
	{
		$this->title = "User Profile";

		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
                
		if (!$this->ion_auth->is_admin()) {
                    if ( $this->session->userdata( 'user_id' ) != $id ) {
                            show_error("You do not have the required permissions to view that user profile.");
                    }
		}
		

                $user_id = $this->session->userdata("user_id");
		$this->data['user'] = json_decode(authPostRequest('', array("user_id" => $user_id), $this->config->item('auth_server') . "/api/auth_general/get_users_info"), 1);
                
		// Get all the network groups that this user from this installation is currently in so that these can be pre selected in the multiselect list
		$current_groups = json_decode(authPostRequest('', array('user_id' => $id, 'installation_key' => $this->config->item('installation_key')), $this->config->item('auth_server') . "/api/auth_general/get_current_network_groups_for_user_in_installation"));
//		print_r($current_groups);
		$this->data['current_groups'] = $current_groups;
//		$this->data['current_groups'] = $this->ion_auth->get_users_groups($id)->result();

		$this->_render('federated/auth/user_profile');

	}

	//edit user profile (for non-admin users)
	function user_edit_profile($id)
	{   
		$this->title = "Edit Profile";

		if (!$this->ion_auth->logged_in())
		{
			redirect('/', 'refresh');
		}
                
                if (!$this->ion_auth->is_admin()) {
                    if ( $this->session->userdata( 'user_id' ) != $id ) {
                            show_error("You do not have the required permissions to view that user profile.");
                    }
		}
                
                $user_id = $this->session->userdata("user_id");
		$user = json_decode(authPostRequest('', array("user_id" => $user_id), $this->config->item('auth_server') . "/api/auth_general/get_users_info"), 1);
                $this->session->set_userdata(array("userId" => $user['id']));
                
//		$this->data['groups'] = $this->ion_auth->getGroups();
//
//		// Find which groups the user belongs to and then pass this information to the view so that these groups are pre-selected in the multiselect box
//		$selected_groups = array();
//		foreach ($this->ion_auth->get_users_groups($id)->result() as $group) {
////			echo "groupid -> " . $group->id . " groupname -> " . $group->name . " description -> " . $group->description;
//			$selected_groups[$group->id] = $group->id;
//		}
//
//		$this->data['selected_groups'] = $selected_groups;
//		$this->data['current_groups'] = $this->ion_auth->get_users_groups($id)->result();

		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['user'] = $user;
		
		
		$this->data['username'] = array(
			'name' => 'username',
			'id' => 'username',
			'type' => 'text',
			'value' => $user['username'],
			'disabled'=>'true'
		);
		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user['first_name']),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user['last_name']),
		);
		$this->data['email'] = array(
			'name'  => 'email',
			'id'    => 'email',
			'type'  => 'text',
			'value' => $user['email'],
			'disabled'=>'true'
		);
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user['company']),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);
		$this->data['orcid'] = array(
			'name' => 'orcid',
			'id' => 'orcid',
			'type' => 'text',
			'value' => $this->form_validation->set_value('orcid', $user['orcid']),
		);
                
                $this->data['email_notification'] = $user['email_notification'];
                
		$this->_render('federated/auth/user_edit_profile');
//		$this->load->view('auth/edit_user', $this->data);
	}
        
        function validate_user_edit_profile() {
            
            if(! $this->input->is_ajax_request()) {
                    redirect('404');
            }

            //validate form input
            $this->form_validation->set_rules('username', 'Username', 'xss_clean|alpha_dash');
            $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
            $this->form_validation->set_rules('email', 'Email Address', 'valid_email');
            $this->form_validation->set_rules('company', 'Institute Name', 'required|xss_clean');
            $this->form_validation->set_rules('orcid', 'ORCID', 'xss_clean');

            if (isset($_POST) && !empty($_POST))
            {
                    // do we have a valid request?
                    if ($this->session->userdata("userId") != $this->input->post('id'))
                    {       echo json_encode(array('error' => "This form post did not pass our security checks."));
                            return;
                    } 
                    
                    if ($this->form_validation->run() === TRUE)
                    {
                        $this->session->unset_userdata("userId");
                        echo json_encode(array('success' => 'no errors'));
                        return;
//				$this->ion_auth->update($user->id, $data);

//				$this->session->set_flashdata('message', "User Saved");
//				redirect("auth_federated", 'refresh');
                    } else {
                        echo json_encode(array('error' => validation_errors()));
                        return;
                    }
            }
        }
	
	// Allows viewing and controlling user groups
	function user_groups() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth_federated', 'refresh');
		}
		
		$this->load->model('sources_model');
		$source_groups = $this->sources_model->getSourceGroups();
//		print_r($source_groups);
		$this->data['source_groups'] = $source_groups;
		$this->_render('federated/auth/user-groups');
	}

	// Allows viewing and controlling source groups
	function source_groups() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth_federated', 'refresh');
		}
		$this->load->model('sources_model');
		$source_groups = $this->sources_model->getSourceGroups();
//		print_r($source_groups);
		$this->data['source_groups'] = $source_groups;
		$this->_render('federated/auth/source_groups');
	}
	
	// Allows viewing and controlling source groups
	function groups() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth_federated', 'refresh');
		}

		$this->data['groups'] = $this->ion_auth->getGroupsFull();
//		$this->load->model('sources_model');
//		$source_groups = $this->sources_model->getSourceGroups();
//		print_r($source_groups);
//		$this->data['source_groups'] = $source_groups;
		$this->_render('federated/auth/groups');
	}
	
	// create a new group
	function create_group()
	{
		$this->title = "Create Group";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth_federated', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('group_name', 'Group name', 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('desc', 'Description', 'required|xss_clean');

		if ($this->form_validation->run() == TRUE)
		{
//			error_log("desc -> " .  $this->input->post('desc'));
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('desc'));
			if($new_group_id) {
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth_federated/groups", 'refresh');
			}
			else {
				$this->data['message'] = $this->ion_auth->errors();
				$this->data['group_name'] = "";
				$this->data['desc'] = "";
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				$this->_render('federated/auth/create_group');
			}
		}
		else
		{
			//display the create group form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['desc'] = array(
				'name'  => 'desc',
				'id'    => 'desc',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);
			$this->_render('federated/auth/create_group');
//			$this->load->view('auth/create_group', $this->data);
		}
	}

	//edit a group
	function edit_group($id)
	{
		// bail if no group id given
		if(!$id || empty($id))
		{
			redirect('auth_federated', 'refresh');
		}

		$this->title = "Edit Group";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth_federated', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		//validate form input
		$this->form_validation->set_rules('group_name', 'Group name', 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('group_description', 'Group Description', 'required|xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					$this->session->set_flashdata('message', "Group Saved");
					redirect("auth_federated/groups", 'refresh');
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
			}
		}

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the group to the view
		$this->data['group'] = $group;

		$this->data['group_name'] = array(
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name', $group->name),
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);
		$this->_render('federated/auth/edit_group');
//		$this->load->view('auth/edit_group', $this->data);
	}

	function delete_group($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('id', 'group ID', 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['group'] = $this->ion_auth->group($id)->row();
			$this->_render('federated/auth/delete_group');
		}
		else
		{
			// do we really want to delete?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
//				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				if ($id != $this->input->post('id'))
				{
					show_error('This form post did not pass our security checks.');
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->delete_group($id);
				}
			}

			//redirect them back to the auth page
			redirect('auth_federated/groups', 'refresh');
		}
	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

    function get_session_status() {
        if(!$this->session->userdata('Token')) {
            echo "not expired";
            return;
        }
        $token = $this->session->userdata('Token');
        $data = authPostRequest($token, array('tokenCheck' => true, 'token' => $this->session->userdata('Token'), 'user_id' => $this->session->userdata('user_id')), $this->config->item('auth_server') . "/api/auth/get_session_status");
        $data = trim($data);
        $data = trim($data, '"');
        echo trim($data);
    }
        
}
