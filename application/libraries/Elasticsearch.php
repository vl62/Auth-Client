<?php defined('BASEPATH') OR exit('No direct script access allowed');
/***********************************************************************************
 * PHP Class for Elasticsearch

 * Date: 18th December 2011

 * Authors: Niranjan Uma Shankar, Prasanna Venkatesan 

 * Thanks to Alf Eaton (http://hublog.hubmed.org) for the first version

 * For any queries, mail us at niranjan.u@computer.org or prasanna@computer.org

 * Please use as you wish at your own risk
 
 * Modified by Owen Lancaster (extra functions and changes for Cafe Variome)

************************************************************************************/

class Elasticsearch {

	function __construct($server = 'http://localhost:9200') {
		$this->server = $server;
	}

	//This function returns the elasticsearch results
	function call($path, $http = array(), $simple = NULL) {
//		error_log("call -> " . $this->server . '/' . $this->index . '/' . $path);
//		error_log("http -> " . print_r($http, 1));
		$content = @file_get_contents($this->server . '/' . $this->index . '/' . $path, NULL, stream_context_create(array('http' => $http)));
//		error_log("call -> " . $this->server . '/' . $this->index . '/' . $path);
//		error_log("content -> " . print_r($content, 1));
		if ($content === FALSE) {
			if ( $simple ) {
				return false;
			}
			else {
				return array(null, 0);
			}
		}
		else {
			if ( $simple ) {
				return true;
			}
			else {
				return json_decode($content, true);
//				return json_decode($content);
//				return $content;
//				return array(json_decode($content), 1);				
			}
		}
	}

	//curl -X PUT http://localhost:9200/{INDEX}/
	// This function is to create an index
	function create() {
		$this->call(NULL, array('method' => 'PUT', 'header' => "Content-Type: application/x-www-form-urlencoded\r\n"));
	}

	//curl -X GET http://localhost:9200/{INDEX}/_status
	function status() {
		return $this->call('_status');
	}

	//curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_count -d {matchAll:{}}
	function count() {
//		return $this->call($this->type . '/_count', array('method' => 'GET', 'content' => '{ matchAll:{} }'));
		return $this->call('/_count', array('method' => 'GET', 'content' => '{ matchAll:{} }'));
	}

	function count_dsl() {
//		return $this->call($this->type . '/_count', array('method' => 'GET', 'content' => '{ matchAll:{} }'));
		return $this->call('/_count', array('method' => 'GET', 'content' => '{ matchAll:{} }'));
	}
	
	function check_if_running() {
//		return $this->call($this->type . '/_count', array('method' => 'GET', 'content' => '{ matchAll:{} }'));
		return $this->call('', array('method' => 'GET'));
	}
	
	//curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/_mapping -d ...
	function map($data) {
		return $this->call($this->type . '/_mapping', array('method' => 'PUT', 'content' => $data));
	}

	//curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/_settings -d ...
	function settings($data) {
//		return $this->call($this->type . '/_settings', array('method' => 'PUT', 'content' => $data));
		return $this->call('', array('method' => 'PUT', 'content' => $data));
	}
	
	//curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/{ID} -d ...
	function add($id, $data) {
//		error_log("type -> " . $this->type . " index -> " . $this->index);
//		error_log("ADD $id -> " . print_r($data, 1));
		return $this->call($this->type . '/' . $id, array('method' => 'PUT', 'header' => "Content-Type: application/x-www-form-urlencoded\r\n", 'content' => $data), true);
	}

	function set_type($type) {
		$this->type = $type;
	}
	
	function set_index($index) {
		$this->index = $index;
	}
	
	//curl -X DELETE http://localhost:9200/{INDEX}/
	//Delete an indexed item by ID
	function delete($id) {
		return $this->call($this->type . '/' . $id, array('method' => 'DELETE'));
	}
	
	function delete_all() {
//		curl -XDELETE 'http://localhost:9200/{INDEX}/'
		return $this->call($this->type, array('method' => 'DELETE'));
	}
	
	function delete_by_query($q) {
		return $this->call($this->type . '/_query', array('method' => 'DELETE', 'header' => "Content-Type: application/x-www-form-urlencoded\r\n", 'content' => $q));
	}
	
	function shutdown() {
//		curl -XDELETE 'http://localhost:9200/{INDEX}/'
		return $this->call("_shutdown", array('method' => 'POST'));
	}

	function update($id, $data) {
//		error_log("type -> " . $this->type . " index -> " . $this->index);
//		error_log("ADD $id -> " . print_r($data, 1));
		//http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/docs-update.html
		return $this->call($this->type . '/' . $id . "/_update", array('method' => 'POST', 'header' => "Content-Type: application/x-www-form-urlencoded\r\n", 'content' => $data), true);
	}
	
	//curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_search?q= ...
	function query($q) {
		return $this->call($this->type . '/_search?' . http_build_query(array('q' => $q)));
	}

	function query_dsl($q) {
		return $this->call($this->type . '/_search', array('method' => 'GET', 'header' => "Content-Type: application/x-www-form-urlencoded\r\n", 'content' => $q));
	}
	
	function query_wresultSize($q, $size = 999) {
		return $this->call($this->type . '/_search?' . http_build_query(array('q' => $q, 'size' => $size)));
	}

	function query_all($q) {
		return $this->call('_search?' . http_build_query(array('q' => $q)));
	}

	function query_all_wresultSize($q, $size = 999) {
		return $this->call('_search?' . http_build_query(array('q' => $q, 'size' => $size)));
	}

	function query_highlight($q) {
		return $this->call($this->type . '/_search?' . http_build_query(array('q' => $q)), array('header' => "Content-Type: application/x-www-form-urlencoded\r\n", 'content' => '{"highlight":{"fields":{"field_1":{"pre_tags" : ["<b style=\"background-color:#C8C8C8\">"], "post_tags" : ["</b>"]}, "field_2":{"pre_tags" : ["<b style=\"background-color:#C8C8C8\">"], "post_tags" : ["</b>"]}}}}'));
	}

}
?>
