<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_federated extends MY_Controller {

	function __construct()
	{
		parent::__construct();
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
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}

//			$this->_render('auth/index');
			$this->_render('federated/auth/users');
//			$this->load->view('auth/index', $this->data);
		}
	}

	//log the user in
	function login($data = "")
	{
                if($data == "error")  echo "<script>alert('login error');</script>";
                
		if ($this->session->userdata('email')) {
			redirect('/', 'refresh');
		}
		$this->title = "Login";
		//validate form input
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{
                        
                        $data_login[0] = $this->input->post('identity');
                        $data_login[1] = $this->input->post('password');
                        $data_login[2] = (bool) $this->input->post('remember');
                        
                        $pubKey = $this->loadPubicKey();
                        $post_data = implode(",", $data_login);
                        openssl_public_encrypt($post_data, $crypted, $pubKey);

                        $data = strtr(base64_encode($crypted), '+/=', '-_~');
                        redirect("http://localhost:8888/cafevariome_server/auth_accounts/login/" . $data);                                                    
		}
		else
		{
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
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
//			$this->load->view('auth/login', $this->data);
		}
	}
        
        function login_success($cipher_session_data) {
                $raw_cipher = base64_decode(strtr($cipher_session_data, '-_~', '+/='));
                $prvKey = $this->loadPubicKey();

                openssl_public_decrypt($raw_cipher, $decrypted, $prvKey);
                $data = explode(",", $decrypted);
                
                $session_data = array(
                    'identity'             => $data[0],
                    'username'             => $data[1],
                    'email'                => $data[2],
                    'user_id'              => $data[3],
                    'old_last_login'       => $data[4],
                    'is_admin'             => $data[5]
                );
                
                $this->session->set_userdata($session_data);
                
                redirect('home');
        }
        
	//log the user out
	function logout()
	{
            	$this->title = "Logout";
                $session_data = array(
                    'identity'          => '',
                    'username'          => '',
                    'email'             => '',
                    'user_id'           => '',
                    'old_last_login'    => '',
                    'is_admin'          => '');
                $this->session->unset_userdata($session_data);
                
		redirect('home', 'refresh');
	}

	//change password
	function change_password()
	{
		$this->form_validation->set_rules('old', 'Old password', 'required');
		$this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth_federated/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			//display the form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
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

			//render
			$this->_render('federated/auth/change_password');
//			$this->load->view('auth/change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth_federated/change_password', 'refresh');
			}
		}
	}

	//forgot password
	function forgot_password()
	{
		$this->form_validation->set_rules('email', 'Email Address', 'required');
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);

			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render('federated/auth/forgot_password');
//			$this->load->view('auth/forgot_password', $this->data);
		}
		else
		{
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
	}

	//reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
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
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			
			// If admin then it was a manual activatation from the admin menu so return to the user list
			if ($this->ion_auth->is_admin()) {
				redirect('auth_federated/users', 'refresh');
			}
			// Activation was done via email by the user - direct them to the success message
			else {
				$this->_render('federated/auth/activate-success');
			}
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth_federated/forgot_password", 'refresh');
		}
	}

	//deactivate the user
	function deactivate($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('id', 'user ID', 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();
			$this->_render('federated/auth/deactivate_user');
//			$this->load->view('auth/deactivate_user', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($id != $this->input->post('id'))
//				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error('This form post did not pass our security checks.');
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			redirect('auth_federated/users', 'refresh');
		}
	}

	//delete the user
	function delete($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('id', 'user ID', 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();
			$this->_render('federated/auth/delete_user');
//			$this->load->view('auth/deactivate_user', $this->data);
		}
		else
		{
			// do we really want to deactivate?
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
					$this->ion_auth->delete_user($id);
				}
			}

			//redirect them back to the auth page
			redirect('auth_federated/users', 'refresh');
		}
	}

	function users() {
		$this->title = "Users";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth_federated', 'refresh');
		}
		$this->data['message'] = $this->session->flashdata('activation_email_unsuccessful');
		//list the users
		$this->data['users'] = $this->ion_auth->users()->result();
		foreach ($this->data['users'] as $k => $user) {
			$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
		}

		$this->_render('federated/auth/users');
	}
        
        private function loadPubicKey() {
            return file_get_contents("/Applications/MAMP/htdocs/cafevariome_client/application/controllers/rsa_key.pub");
        }
	
	//signup and register
	function signup($data = "") {
            
                if($data == "error")  echo "<script>alert('Error in registration. Try again later.');</script>";
                
		$this->title = "Registration";
		
		if ( ! $this->config->item('allow_registrations') ) {
			show_error("Sorry, registrations have been disabled for this Cafe Variome instance");
		}
		
		if (!$this->ion_auth->logged_in()) {
			//validate form input
			$this->form_validation->set_rules('username', 'Username', 'required|xss_clean|alpha_numeric|callback_username_uniquename_check');
			$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
			$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|callback_email_uniquename_check');
			$this->form_validation->set_rules('company', 'Institute Name', 'required|xss_clean');
			$this->form_validation->set_rules('orcid', 'ORCID', 'xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
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
			
			if ( $this->form_validation->run() == true) { //check to see if we are creating the user
                            
                            $data_register[0] = strtolower($this->input->post('username'));
                            $data_register[1] = $this->input->post('email');
                            $data_register[2] = $this->input->post('password');
                            $data_register[3] = $this->input->post('first_name');
                            $data_register[4] = $this->input->post('last_name');
                            $data_register[5] = $this->input->post('company');
                            $data_register[6] = $this->input->post('orcid');
                            
                            $pubKey = $this->loadPubicKey();
                            $post_data = implode(",", $data_register);
                            openssl_public_encrypt($post_data, $crypted, $pubKey);
            
                            $data = strtr(base64_encode($crypted), '+/=', '-_~');
                            redirect("http://localhost:8888/cafevariome_server/auth_accounts/register/" . $data);                            
			}
			else {
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
		}
		else {
			redirect('home', 'refresh');
		}
    }
    
    function signup_success($email = "") {
        if(!empty($email)) {
            $this->data['email'] = urldecode($email);
            $this->_render("federated/auth/signup-success");
        } else {
            echo "2"; return;
            $this->_render("federated/auth/signup-success-manual");
        }
    }
        
    function username_uniquename_check($username) {
//            $this->load->model('auth_accounts_model');
//            $res = $this->auth_accounts_model->check_user_exists("username", $username, 0);
//            if($res) {
//                $this->form_validation->set_message('username_uniquename_check', 'The %s '. $username .' already exists');
//                return false;
//            }
//            else        
//                return true;
    }
        
    function email_uniquename_check($userEmail) {
//            $this->load->model('auth_accounts_model');
//            $res = $this->auth_accounts_model->check_user_exists("email", $userEmail, 0);
//            if($res) {
//                $this->form_validation->set_message('email_uniquename_check', 'The %s '. $userEmail .' already exists');
//                return false;
//            }
//            else        
//                return true;
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
		$this->title = "Create User";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('username', 'Username', 'required|xss_clean|alpha_dash');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('company', 'Institute Name', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
		$this->form_validation->set_rules('orcid', 'ORCID', 'xss_clean');

		if ($this->form_validation->run() == true)
		{
//			$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
			$username = strtolower($this->input->post('username'));
			$email    = $this->input->post('email');
			$password = $this->input->post('password');
			$groups = $this->input->post('groups');
			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'orcid' => $this->input->post('orcid')
			);
		}
		
		if ($this->ion_auth->is_admin())
                {
			$this->data['groups'] = $this->ion_auth->getGroups();
                }
		$type = "admin"; // Used to specify that ion_auth registration function send a different email to the user since account was created through admin interface
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $groups, $type))
		{
			$user_id = $this->ion_auth->getUserIDFromUsername($username);
//			error_log("userid -> " . $user_id . " user -> " . $username);
//			$this->ion_auth->activate($user_id);
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect('auth_federated/users', 'refresh');
		}
		else
		{
			//display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			
			$this->data['username'] = array(
				'name' => 'username',
				'id' => 'username',
				'type' => 'text',
				'value' => $this->form_validation->set_value('username'),
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
	}

	//edit a user
	function edit_user($id)
	{
		$this->title = "Edit User";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		//validate form input
		$this->form_validation->set_rules('username', 'Username', 'required|xss_clean|alpha_dash');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('company', 'Institute Name', 'required|xss_clean');
		$this->form_validation->set_rules('orcid', 'ORCID', 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($id != $this->input->post('id'))
			{
				show_error('This form post did not pass our security checks.');
			}

			$data = array(
				'username' => strtolower($this->input->post('username')),
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'email'      => $this->input->post('email'),
				'company'    => $this->input->post('company'),
				'orcid'		 => $this->input->post('orcid')
			);

			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

				$data['password'] = $this->input->post('password');
			}

			// Check if there any groups selected
			if ($this->input->post('groups')) {

				// Get all the groups that this user is currently in
				$groups_in = array();
				foreach ($this->ion_auth->get_users_groups($id)->result() as $group) {
//					echo "groupid -> " . $group->id . " groupname -> " . $group->name . " description -> " . $group->description;
					$groups_in[] = $group->id;
				}

				// Find which current groups have been deselected and therefore need to be removed from this user
				$diff = array_diff($groups_in, $this->input->post('groups'));
//				print_r($diff);
				if ( ! empty($diff) ) {
					foreach ( $diff as $delete_group_id ) {
						$this->ion_auth->remove_from_group($delete_group_id, $id);
					}
				}

				// Find which groups need to be added - go through the selected groups to see if they are not in the users currently assigned groups
				foreach ($this->input->post('groups') as $group_id) {
//					error_log("sgid -> $group_id");
					if (! in_array($group_id, $groups_in)) {
						if (!$this->ion_auth->check_if_in_group($group_id, $id)) {
//							error_log("NOT IN GROUP SO ADD");
							$this->ion_auth->add_to_group($group_id, $id);
						}
					}
				}
			}
			else {
				// All groups were de-selected so remove this user from all groups - do this by passing NULL to ion_auth remove_from_group function
				$this->ion_auth->remove_from_group(NULL, $id);
			}
			
			if ($this->form_validation->run() === TRUE)
			{
				$this->ion_auth->update($user->id, $data);

				//check to see if we are creating the user
				//redirect them back to the admin page
				$this->session->set_flashdata('message', "User Saved");
				redirect("auth_federated", 'refresh');
			}
		}
		
		$this->data['groups'] = $this->ion_auth->getGroups();

		// Find which groups the user belongs to and then pass this information to the view so that these groups are pre-selected in the multiselect box
		$selected_groups = array();
		foreach ($this->ion_auth->get_users_groups($id)->result() as $group) {
//			echo "groupid -> " . $group->id . " groupname -> " . $group->name . " description -> " . $group->description;
			$selected_groups[$group->id] = $group->id;
		}

		$this->data['selected_groups'] = $selected_groups;
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
	
	
	//view user profile (for non-admin user)
	function user_profile($id)
	{
		$this->title = "User Profile";

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth_federated', 'refresh');
		}
		if (!$this->ion_auth->is_admin()) {
			if ( $this->session->userdata( 'user_id' ) != $id ) {
				show_error("You do not have the required permissions to view that user profile.");
			}
		}
		
		$user = $this->ion_auth->user($id)->row();
		$this->data['user'] = $user;

		$this->data['current_groups'] = $this->ion_auth->get_users_groups($id)->result();

		$this->_render('federated/auth/user_profile');

	}

	//edit user profile (for non-admin users)
	function user_edit_profile($id)
	{
		$this->title = "Edit Profile";

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth_federated', 'refresh');
		}
		if ( $this->session->userdata( 'user_id' ) != $id ) {
//			show_404();
			show_error("You do not have the required permissions to edit that user profile.");
		}
		$user = $this->ion_auth->user($id)->row();
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
			if ($id != $this->input->post('id'))
			{
				show_error('This form post did not pass our security checks.');
			}

			$data = array(
//				'username' => strtolower($this->input->post('username')),
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
//				'email'      => $this->input->post('email'),
				'company'    => $this->input->post('company'),
				'orcid'		 => $this->input->post('orcid')
			);

			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

				$data['password'] = $this->input->post('password');
			}
			
			if ($this->form_validation->run() === TRUE)
			{
				$this->ion_auth->update($user->id, $data);

				//check to see if we are creating the user
				//redirect them back to the admin page
				$this->session->set_flashdata('message', "User Saved");
				redirect("auth_federated/user_profile/" . $id, 'refresh');
			}
		}
		
		$this->data['groups'] = $this->ion_auth->getGroups();

		// Find which groups the user belongs to and then pass this information to the view so that these groups are pre-selected in the multiselect box
		$selected_groups = array();
		foreach ($this->ion_auth->get_users_groups($id)->result() as $group) {
//			echo "groupid -> " . $group->id . " groupname -> " . $group->name . " description -> " . $group->description;
			$selected_groups[$group->id] = $group->id;
		}

		$this->data['selected_groups'] = $selected_groups;
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
			'value' => $user->username,
			'disabled'=>'true'
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
			'value' => $user->email,
			'disabled'=>'true'
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
		$this->_render('federated/auth/user_edit_profile');
//		$this->load->view('auth/edit_user', $this->data);
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
	


}
