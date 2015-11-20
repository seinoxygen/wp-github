<?php
/**
 * Github
 * Author: Pablo Cornehl
 * Author URI: http://www.seinoxygen.com
 * Version: 1.1
 */
class Github {
	private $api_url = 'https://api.github.com/';
	private $username = null;
	private $repository = null;
	private $contents = null;

	
	public function __construct($username = 'seinoxygen', $repository = 'wp-github', $contents = 'README.md') {
		$this->username = $username;
		$this->repository = $repository;
		$this->contents = $contents;
		//OAuth2 Key/Secret
		//https://developer.github.com/v3/#authentication
		$ci = get_option('wpgithub_clientID', '');
		$cs = get_option('wpgithub_clientSecret', '');
		if(!empty($ci) && !empty($cs)){
			$url_append = '?client_id='.$ci.'&client_secret='.$cs;
		} else {
			$url_append = '';
		}

		$this->oauth2 = $url_append;
		
		/**
		 * Increase execution time.
		 * Sometimes long queries like fetch all issues from all repositories can kill php.
		 */
		set_time_limit(90);
	}
	
	/**
	 * Get response content from url.
	 * 
	 * @param	$path String
	 */
	public function get_response($path){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->api_url . $path. $this->oauth2);
		curl_setopt($ch, CURLOPT_USERAGENT, 'wp-github');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPGET, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
	
	/**
	 * Return user profile.
	 */
	public function get_profile(){
		$contents = $this->get_response('users/' . $this->username);
		if($contents == true) {
		 	return json_decode($contents);
		}
		return null;
	}
	
	/**
	 * Return user events.
	 */
	public function get_events(){
		$contents = $this->get_response('users/' . $this->username . '/events');
		if($contents == true) {
		 	return json_decode($contents);
		}
		return null;
	}
	
	/**
	 * Return user repositories.
	 */
	public function get_repositories(){
		$contents = $this->get_response('users/' . $this->username . '/repos');
		if($contents == true) {
		 	return json_decode($contents);
		}
		return null;
	}
	
	/**
	 * Return repository commits. If none is provided will fetch all commits from all public repositories from user.
	 */
	public function get_commits(){
		$data = array();
		if(!empty($this->repository)){
			$contents = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/commits');
			if($contents == true) {
				$data = array_merge($data, json_decode($contents));
			}
		}
		else{
			// Fetch all public repositories
			$repos = $this->get_repositories();

			if($repos == true ) {
				// Loop through public repos and get all commits
				foreach($repos as $repo){
					$contents = $this->get_response('repos/' . $this->username . '/' . $repo->name . '/commits');
					if($contents == true) {
						$data = array_merge($data, json_decode($contents));
					}
				}
			}else{

			}
		}
		
		// Sort response array
		usort($data, array($this, 'order_commits'));
		
		return $data;
	}
			/**
	 * Return repository releases. If none is provided will fetch all commits from all public repositories from user.
	 */
	public function get_latest_release(){
		$data = '';
		if(!empty($this->repository)){
			$contents = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/releases/latest');
			if($contents == true) {
				$data = json_decode($contents);
			}
		}
		return $data;
	}
	
	
		/**
	 * Return repository releases. If none is provided will fetch all commits from all public repositories from user.
	 */
	public function get_releases(){
		$data = array();
		if(!empty($this->repository)){
			$contents = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/releases');
			if($contents == true) {
				$data = array_merge($data, json_decode($contents));
			}
		}
		
		else{
			// Fetch all public repositories
			$repos = $this->get_repositories();
			if($repos == true) {
				// Loop through public repos and get all commits
				foreach($repos as $repo){
					$contents = $this->get_response('repos/' . $this->username . '/' . $repo->name . '/releases');
					if($contents == true) {
						$data = array_merge($data, json_decode($contents));
					}
				}
			}
		}
	
		 //Sort response array
		 usort($data, array($this, 'order_commits'));
		
		return $data;
	}

	/*
	 * returns the contents of a file or directory in a repo
	 * */
	public function get_contents(){
		$data = '';
		//GET /repos/:owner/:repo/contents/:path
		if(!empty($this->repository)){
			$data_content = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/contents/'.$this->contents);
			if($data_content == true) {
				//Wordpress strip php tags -- what's the solution ?
				$data = json_decode($data_content);
			}
		}
		return $data;
	}
	/**
	 * Return repository issues. If none is provided will fetch all issues from all public repositories from user.
	 */
	public function get_issues(){
		$data = array();
		if(!empty($this->repository)){
			$contents = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/issues');
			if($contents == true) {
				$data = json_decode($contents);
			}
		}
		else{
			// Fetch all public repositories
			$repos = $this->get_repositories();
			if($repos == true) {
				// Loop through public repos and get all issues
				foreach($repos as $repo){
					$contents = $this->get_response('repos/' . $this->username . '/' . $repo->name . '/issues');
					if($contents == true) {
						$data = array_merge($data, json_decode($contents));
					}
				}
			}
		}
		
		// Sort response array
		usort($data, array($this, 'order_issues'));
		
		return $data;
	}
	
	public function get_gists(){
		$contents = $this->get_response('users/' . $this->username . '/gists');
		if($contents == true) {
		 	return json_decode($contents);
		}
		return null;
	}
	
	/**
	 * Get username.
	 */
	public function get_username() {
		return $this->username;
	}
	
	/**
	 * Get repository.
	 */
	public function get_repository() {
		return $this->repository;
	}
		
	/**
	 * Sort commits from newer to older.
	 */
	public function order_commits($a, $b){
		$a = strtotime($a->commit->author->date);
		$b = strtotime($b->commit->author->date);
		if ($a == $b){
			return 0;
		}
		else if ($a > $b){
			return -1;
		}
		else {            
			return 1;
		}
	}
	
	/**
	 * Sort issues from newer to older.
	 */
	public function order_issues($a, $b){
		$a = strtotime($a->created_at);
		$b = strtotime($b->created_at);
		if ($a == $b){
			return 0;
		}
		else if ($a > $b){
			return -1;
		}
		else {            
			return 1;
		}
	}
}
?>