<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('varioml_model');
		$this->load->model('sources_model');
		$this->load->model('search_model');
	}

	public function index(){
//		$this->load->library('zend', 'ZendSearch/Lucene');
//		$index = Zend_Search_Lucene::create('/Library/WebServer/Documents/my-index');
//		$doc = new Zend_Search_Lucene_Document();
//		$doc->addField(Zend_Search_Lucene_Field::Text('email', "ol8@le.ac.uk"));
//		$doc->addField(Zend_Search_Lucene_Field::Text('first_name', "Owen"));
//		$index->addDocument($doc);

		$this->load->library('zend', 'Zend/Feed');
		$this->load->library('zend', 'Zend/Search/Lucene');
		$this->load->library('zend');
		$this->zend->load('Zend/Feed');
		$this->zend->load('Zend/Search/Lucene');
		
		$source = "diagnostic";
		$variants = $this->search_model->getVariantsForSource($source);
		//Create index.   
		$index = new Zend_Search_Lucene('/Library/WebServer/Documents/cafevariome/upload/feeds_index', true);
		$doc = new Zend_Search_Lucene_Document();
		foreach ( $variants as $variant ) {
			$doc->addField(Zend_Search_Lucene_Field::Text('gene', $variant['gene']));
			$doc->addField(Zend_Search_Lucene_Field::Text('phenotype', $variant['phenotype']));
			$index->addDocument($doc);
		}
		$index->commit();
//		$doc->addField(Zend_Search_Lucene_Field::Text('email', "ol8@le.ac.uk"));
//		$index->addDocument($doc);
//		$doc->addField(Zend_Search_Lucene_Field::Text('first_name', "Owen"));
//		$index->addDocument($doc);
//		$doc->addField(Zend_Search_Lucene_Field::Text('title', 'foobar'));
//		$index->addDocument($doc);
//		$doc->addField(Zend_Search_Lucene_Field::Text('title', 'foobar test'));

		$index->addDocument($doc);

//		$feeds = array(
//			'http://www.cmjackson.net/feed/rss/',
//			'http://andrewmjackson.com/feed/rss');

		//grab each feed.   
//		foreach ($feeds as $feed) {
//			$channel = Zend_Feed::import($feed);
//			echo $channel->title() . '<br />';
//
//			//index each item.    
//			foreach ($channel->items as $item) {
//				if ($item->link() && $item->title() && $item->description()) {
//					//create an index doc.      
//					$doc = new Zend_Search_Lucene_Document();
//					$doc->addField(Zend_Search_Lucene_Field::Keyword(
//									'link', $this->sanitize($item->link())));
//					$doc->addField(Zend_Search_Lucene_Field::Text(
//									'title', $this->sanitize($item->title())));
//					$doc->addField(Zend_Search_Lucene_Field::Unstored(
//									'contents', $this->sanitize($item->description())));
//
//					echo "\tAdding: " . $item->title() . '<br />';
//					$index->addDocument($doc);
//				}
//			}
//		}

//		$index->commit();
		echo $index->count() . ' Documents indexed.<br />';
	}

	function test($query = NULL) {
		$this->load->library('zend', 'Zend/Search/Lucene');
		$this->load->library('zend');
		$this->zend->load('Zend/Search/Lucene');

		$index = new Zend_Search_Lucene('/Library/WebServer/Documents/cafevariome/upload/feeds_index');

		$query = '*cancer*';
		$hits = $index->find($query);
//		print_r($hits);
		echo 'Index contains ' . $index->count() .
		' documents.<br /><br />';
		echo 'Search for "' . $query . '" returned ' . count($hits) .
		' hits<br /><br />';

		foreach ($hits as $hit) {
//			echo $hit->title . '<br />';
//			echo $hit->email . '<br />';
			echo $hit->gene . ' gene<br />';
			echo $hit->phenotype . ' pheno<br />';
			echo 'Score: ' . sprintf('%.2f', $hit->score) . '<br />';
//			echo $hit->link . '<br /><br />';
		}
	}
	
    function sanitize($input) {
        return htmlentities(strip_tags($input));
    }

}