<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
	
	//Page info
	protected $data = Array();
	protected $pageName = FALSE;
	protected $template = "main";
	protected $hasNav = TRUE;
	//Page contents
	protected $javascript = array();
	protected $css = array();
	protected $fonts = array();
	//Page Meta
	protected $title = FALSE;
	protected $description = FALSE;
	protected $keywords = FALSE;
	protected $author = FALSE;
	
	function __construct() {
		
		parent::__construct();
		$this->data["uri_segment_1"] = $this->uri->segment(1);
		$this->data["uri_segment_2"] = $this->uri->segment(2);
		$this->pageName = strToLower(get_class($this));
		// Check if user is logged in
//		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
//			$page = $this->pageName;
////			echo "PAGE $page -> " . $this->uri->segment(1) . " -> " . $this->uri->segment(2);
////			if ( strtolower($view) != "auth/login" && strtolower($view) != "auth/signup") {
//			if ( $this->uri->segment(2) != "login" && $this->uri->segment(2) != "signup") {
////				show_error("Sorry, the submission interface for AtomServer is not enabled. <button class='btn'>test</button>");
//			}
//			else {
////				error_log("something else");
//			}
//		}
	}

	protected function _render($view) {
		$this->title = $this->config->item('site_title');
		$this->description = $this->config->item('site_description');
		$this->keywords = $this->config->item('site_keywords');
		$this->author = $this->config->item('site_author');

		//static
		$toTpl["javascript"] = $this->javascript;
		$toTpl["css"] = $this->css;
		$toTpl["fonts"] = $this->fonts;
		
		//meta
		$toTpl["title"] = $this->title;
		$toTpl["description"] = $this->description;
		$toTpl["keywords"] = $this->keywords;
		$toTpl["author"] = $this->author;

		//data
		$toBody["content_body"] = $this->load->view($view,array_merge($this->data,$toTpl),true);
		
		//nav menu
		if($this->hasNav){
			
			// Get dynamic menus from CMS from database and pass to nav template
			$this->load->model('cms_model');
			$toMenu['menus'] = $this->cms_model->getMenus();
			if ($this->config->item('messaging')) {
				if ( $this->ion_auth->logged_in() ) {
					$this->load->model('messages_model');
					$user_id = $this->ion_auth->user()->row()->id;
					$unread_messages = $this->messages_model->get_message_count($user_id);
					$toMenu['unread_messages'] = $unread_messages;
				}
			}
			$this->load->helper("nav");
			$toMenu["pageName"] = $this->pageName;
			$toHeader["nav"] = $this->load->view("template/nav",$toMenu,true);
		}
		$toHeader["basejs"] = $this->load->view("template/basejs",$this->data,true);
		
		$toBody["header"] = $this->load->view("template/header",$toHeader,true);
		$toBody["footer"] = $this->load->view("template/footer",'',true);
		
		$toTpl["body"] = $this->load->view("template/".$this->template,$toBody,true);
		
		//render view
//		setCurrentURL(); // Owen - added to set current url for redirecting - doing this in a hook now instead
		$this->load->view("template/skeleton",$toTpl);
		
	}
	
	protected function _renderDynamic($view) {
		$this->session->set_userdata('current_cms_view', $view);
		
		$this->title = $this->config->item('site_title');
		$this->description = $this->config->item('site_description');
		$this->keywords = $this->config->item('site_keywords');
		$this->author = $this->config->item('site_author');

		//static
		$toTpl["javascript"] = $this->javascript;
		$toTpl["css"] = $this->css;
		$toTpl["fonts"] = $this->fonts;
		
		//meta
		$toTpl["title"] = $this->title;
		$toTpl["description"] = $this->description;
		$toTpl["keywords"] = $this->keywords;
		$toTpl["author"] = $this->author;

		//data
		$this->load->model('cms_model');
		$pages_for_menu = $this->cms_model->getPagesForMenu($view);
		
		if (preg_match('/\|/', $pages_for_menu)) {
			$pages = explode('|', $pages_for_menu);
			foreach ( $pages as $page ) {
				$page_view = strtolower($page);
				//echo "PAGE -> $page<br />";
			}
		}
		else {
			$page_view = strtolower($pages_for_menu);
		}
		
		
		$page = $this->cms_model->getPage($page_view);
		
		if ( array_key_exists('page_content', $page)) {
			if ( strtolower($view) == "home" ) {
				if ($this->config->item('rss')) {
					// Get the news feed data
					$this->load->library('Rss');
					$this->rss->set_items_limit(4); // how many items to retreive from each feed
					$this->rss->set_cache_life(0); // cache life in minutes
//					$this->rss->set_cache_path('/full/path/to/cache/dir/'); // by default library used CI default cache path, or path that you set in config.php
					$this->rss->set_debug(false); // in debug mode library will output on screen useful data
					$rss_uri = $this->config->item('rss');
					if ( $rss_uri == "local") {
						$rss_uri = $this->config->item('base_url') . 'feed';
					}
					$data['rss_uri'] = $rss_uri;
					$this->rss->set_url(array($rss_uri));
					// return array of objects containing rss data from all feeds
					$data['news'] = $this->rss->parse();
					$news = $this->load->view('cms/news', $data, TRUE);
//					$toBody["content_body"] = '<div class="container"><div class="row-fluid"><div class="span3"><div class="row">&nbsp;</div><div class="row">&nbsp;</div><div class="row">&nbsp;</div>' . $news . '</div><div class="span9 pagination-centered">' . $page['page_content'] . '</div></div></div>';
					$toBody["content_body"] = '<div class="container"><div class="row-fluid"><div class="span3"><div class="row">&nbsp;</div>' . $news . '</div><div class="span9 pagination-centered">' . $page['page_content'] . '</div></div></div>';
				}
				else {
					$toBody["content_body"] = '<div class="container">' . $page['page_content'] . '</div>';
				}
				
			}
			else {
				$toBody["content_body"] = '<div class="container">' . $page['page_content'] . '</div>';
			}
		}
		else {
			$toBody["content_body"] = '<div class="container"><p style="text-align: center;">No page has been linked to this menu item.<br /><br />An admin user needs to do this through the content management administrators interface.</p></div>';
		}

//		$toBody["content_body"] = $this->load->view($view,array_merge($this->data,$toTpl),true);
		
		//nav menu
		if($this->hasNav){
			$this->load->helper("nav");
			
			// Get dynamic menus from CMS from database and pass to nav template
			$this->load->model('cms_model');
			$toMenu['menus'] = $this->cms_model->getMenus();
			if ($this->config->item('messaging')) {
				if ( $this->ion_auth->logged_in() ) {
					$this->load->model('messages_model');
					$user_id = $this->ion_auth->user()->row()->id;
					$unread_messages = $this->messages_model->get_message_count($user_id);
					$toMenu['unread_messages'] = $unread_messages;
				}
			}
			$toMenu["pageName"] = $this->pageName;
			$toHeader["nav"] = $this->load->view("template/nav",$toMenu,true);
		}
		$toHeader["basejs"] = $this->load->view("template/basejs",$this->data,true);
		
		$toBody["header"] = $this->load->view("template/header",$toHeader,true);
		$toBody["footer"] = $this->load->view("template/footer",'',true);
		
		$toTpl["body"] = $this->load->view("template/".$this->template,$toBody,true);
		
		//render view
//		setCurrentURL(); // Owen - added to set current url for redirecting - doing this in a hook now instead
		$this->load->view("template/skeleton",$toTpl);
		
	}
	
	protected function _renderDashboard($view) {
		//static
		$toTpl["javascript"] = $this->javascript;
		$toTpl["css"] = $this->css;
		$toTpl["fonts"] = $this->fonts;
		
		//meta
		$toTpl["title"] = $this->title;
		$toTpl["description"] = $this->description;
		$toTpl["keywords"] = $this->keywords;
		$toTpl["author"] = $this->author;

		//data
		$toBody["content_body"] = $this->load->view($view,array_merge($this->data,$toTpl),true);
		
		//nav menu
		if($this->hasNav){
			$this->load->helper("nav");
			$toMenu["pageName"] = $this->pageName;
			$toHeader["nav"] = $this->load->view("template/nav",$toMenu,true);
		}
		$toHeader["basejs"] = $this->load->view("template/basejs",$this->data,true);
		
		$toBody["header"] = $this->load->view("template/header",$toHeader,true);
		$toBody["footer"] = $this->load->view("template/footer",'',true);
		$toTpl["body"] = $this->load->view("template/".$this->template,$toBody,true);
		
		//render view
		setCurrentURL();
		$this->load->view("template/skeleton_dashboard",$toTpl);
		
	}
	
}
