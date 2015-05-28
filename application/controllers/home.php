<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	
	public function index() {
		/*
		 *set up title and keywords (if not the default in custom.php config file will be set) 
		 */
		$this->title = "Cafe Variome";
		if ($this->config->item('rss')) {
			// Get the news feed data
			$this->load->library('Rss');
			$this->rss->set_items_limit(5); // how many items to retreive from each feed
			$this->rss->set_cache_life(0); // cache life in minutes
//			$this->rss->set_cache_path('/full/path/to/cache/dir/'); // by default library used CI default cache path, or path that you set in config.php
			$this->rss->set_debug(false); // in debug mode library will output on screen useful data
			$rss_uri = $this->config->item('rss');
			if ( $rss_uri == "local") {
				$rss_uri = $this->config->item('base_url') . 'feed';
			}
			$this->data['rss_uri'] = $rss_uri;
			$this->rss->set_url(array($rss_uri));
			// return array of objects containing rss data from all feeds
			$news = $this->rss->parse();
			$this->data['news'] = $news; // Pass news data to the view
		}
		
		// Check that the user has an ORCID linked to their profile. TODO: possibly store in session variable instead of database so that alert is shown each time they re-login
		if ($this->ion_auth->logged_in()) {
			if ( $this->config->item('show_orcid_reminder')) { // Only show reminder if it's set to true in the cafevariome config file
				$this->load->model('general_model');
				$user_id = $this->session->userdata( 'user_id' );
				$this->data['user_id'] = $user_id;
				$orcid = $this->general_model->checkORCIDExists($user_id);
				if ( ! $orcid ) { // User doesn't have an ORCID linked to account
					$orcid_alert_shown = $this->general_model->getORCIDAlertShown($user_id); // Get the status of whether the ORCID alert has been shown before
//					error_log("shown -> " . $orcid_alert_shown);
					if ( ! $orcid_alert_shown ) { // ORCID alert box hasn't been shown before
						$this->data['show_orcid_alert'] = TRUE;
						$this->general_model->setORCIDAlertShown($user_id); // Set the status to say that the alert has been shown once before
					}
				}
			}
			$user_id = $this->session->userdata( 'user_id' );
			$this->data['user_id'] = $user_id;
		}
//		$this->load->model('sources_model');
//		$sources_options = $this->sources_model->getSources(); // Get all the available sources from db
//		$this->data['sources_options'] = $sources_options;
		if ( $this->config->item('cafevariome_central') ) {
			$this->_render('pages/home');
		}
		else {
			$this->_renderDynamic('home');
		}

	}
	
}