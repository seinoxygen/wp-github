<?php
/**
 * Github
 * Author: Pablo Cornehl
 * Author URI: http://www.seinoxygen.com
 * Version: 1.0
 */
class Github {
	private $api_url = 'https://api.github.com/';
	private $username = null;
	private $repository = null;
	
	public function Github($username = 'seinoxygen', $repository = 'wp-github') {
		$this->username = $username;
		$this->repository = $repository;		
	}
		
	public function get_response($path){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->api_url . $path);
		curl_setopt($ch, CURLOPT_USERAGENT, 'seinoxygen');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPGET, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
	
	public function get_repositories(){
		$contents = $this->get_response('users/' . $this->username . '/repos');
		if($contents == true) {
		 	return json_decode($contents);
		}
		return null;
	}
	
	public function get_commits(){
		$contents = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/commits');
		if($contents == true) {
		 	return json_decode($contents);
		}
		return null;
	}
	
	public function get_issues(){
		$contents = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/issues');
		if($contents == true) {
		 	return json_decode($contents);
		}
		return null;
	}
	
	public function get_gists(){
		$contents = $this->get_response('users/' . $this->username . '/gists');
		if($contents == true) {
		 	return json_decode($contents);
		}
		return null;
	}
	
	public function get_username() {
		return $this->username;
	}
	
	public function get_repository() {
		return $this->repository;
	}
}
?>