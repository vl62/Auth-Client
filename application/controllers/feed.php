<?php

class Feed extends MY_Controller {

	function Feed() {
		parent::__construct();
		$this->load->model('posts_model', '', TRUE);
		$this->load->helper('xml');
	}

	function index() {
		$data['encoding'] = 'utf-8';
		$data['feed_name'] = 'Cafe Variome';
		$data['feed_url'] = 'http://www.cafevariome.org';
		$data['page_description'] = 'Cafe Variome RSS Feed';
		$data['page_language'] = 'en-ca';
		$data['creator_email'] = 'admin@cafevariome.org';
		$data['posts'] = $this->posts_model->getRecentPosts();
		header("Content-Type: application/rss+xml");
		$this->load->view('pages/rss', $data);
	}
	
	function edit() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		$this->data['posts'] = $this->posts_model->getAllPosts();
		$this->_render('admin/rss');
	}

	public function edit_post($entry_id = NULL) {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		
		$this->data['entry_id'] = $entry_id;
		$this->data['title'] = "Edit Entry";

		//validate form input
		$this->form_validation->set_rules('post_title', 'Post Title', 'required|xss_clean');
		$this->form_validation->set_rules('post_body', 'Post Text', 'required|xss_clean');
		$this->form_validation->set_rules('post_date', 'Post Date', 'required|xss_clean');
		$this->form_validation->set_rules('post_visible', 'Post Visible', 'required|xss_clean');

		if ($this->form_validation->run() == true) {
			//check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			$update_data['post_title'] = $this->input->post('post_title');
			$update_data['post_body'] = $this->input->post('post_body');
			$update_data['post_date'] = $this->input->post('post_date');
			$update_data['post_date_sort'] = date('Y-m-d H:i:s', strtotime($this->input->post('post_date')));
			$update_data['post_visible'] = $this->input->post('post_visible');
			// Do the update with the post data for this post
			$this->posts_model->updatePost($this->input->post('entry_id'), $update_data);
			
			redirect("feed/edit", 'refresh');
		}
		else {
			$entry_data = $this->posts_model->getSinglePost($entry_id);
			$this->data['entry'] = $entry_data;

			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['post_title'] = array(
				'name'  => 'post_title',
				'id'    => 'post_title',
				'type'  => 'text',
				'style' => 'width:70%',
				'value' => $this->form_validation->set_value('post_title', $entry_data['post_title']),
			);
			$this->data['post_body'] = array(
				'name'  => 'post_body',
				'id'    => 'post_body',
				'type'  => 'text',
				'size'  => '50',
				'style' => 'width:70%',
				'value' => $this->form_validation->set_value('post_body', $entry_data['post_body']),
			);
			$this->data['post_date'] = array(
				'name'  => 'post_date',
				'id'    => 'post_date',
				'type'  => 'text',
				'style' => 'width:50%',
				'value' => $this->form_validation->set_value('post_date', $entry_data['post_date']),
			);
			$this->data['post_visible'] = array(
				'name'  => 'post_visible',
				'id'    => 'post_visible',
				'type'  => 'dropdown',
				'value' => $this->form_validation->set_value('post_visible', $entry_data['post_visible']),
			);

			$this->_render('admin/edit_rss');

		}
	}

	public function add_post() {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}
		
		$this->data['title'] = "Add Entry";

		//validate form input
		$this->form_validation->set_rules('post_title', 'Post Title', 'required|xss_clean');
		$this->form_validation->set_rules('post_body', 'Post Text', 'required|xss_clean');
		$this->form_validation->set_rules('post_visible', 'Post Visible', 'required|xss_clean');

		
		if ($this->form_validation->run() == FALSE) {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['post_title'] = array(
				'name'  => 'post_title',
				'id'    => 'post_title',
				'type'  => 'text',
				'style' => 'width:70%',
				'value' => $this->form_validation->set_value('post_title'),
			);
			$this->data['post_body'] = array(
				'name'  => 'post_body',
				'id'    => 'post_body',
				'type'  => 'text',
				'size'  => '50',
				'style' => 'width:70%',
				'value' => $this->form_validation->set_value('post_body'),
			);
			$this->data['post_visible'] = array(
				'name'  => 'post_visible',
				'id'    => 'post_visible',
				'type'  => 'dropdown',
				'value' => $this->form_validation->set_value('post_visible'),
			);
			$this->_render('admin/add_rss');
		}
		else {
			
			$this->load->model('posts_model');
			$post_title = $this->input->post('post_title');
			$post_body = $this->input->post('post_body');
			$post_visible = $this->input->post('post_visible');
			$post_date = date('D, d M Y H:i:s T');
			$post_date_sort = date('Y-m-d H:i:s');
			$entry_data = array ( 'post_title' => $post_title, 'post_body' => $post_body, 'post_visible' => $post_visible, 'post_date' => $post_date, 'post_date_sort' => $post_date_sort );
			// Do the insert with the post data
			$insert_id = $this->posts_model->insertPost($entry_data);
			$this->data['insert_id'] = $insert_id;
			$this->_render('admin/add_rss');
			redirect("feed/edit", 'refresh');

		}
	}

	//delete the post
	function delete_post($id = NULL) {
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('id', 'entry ID', 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE) {
			// insert csrf check
			$this->data['entry'] = $this->posts_model->getSinglePost($id);
			$this->_render('admin/delete_rss');
		}
		else {
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes') {
				// do we have a valid request?
				if ($id != $this->input->post('id')) {
					show_error('This form post did not pass our security checks.');
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
					$this->posts_model->deletePost($id);
				}
			}
			//redirect them back to the auth page
			redirect('feed/edit', 'refresh');
		}
	}
	
	function entry($id) {
		$post_data = $this->posts_model->getSinglePost($id);
		$this->data['entry'] = $post_data;
		$this->_render('pages/rss_entry');
	}
}

?>