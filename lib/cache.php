<?php
/**
 * Cache
 * Author: Pablo Cornehl
 * Author URI: http://www.seinoxygen.com
 * Version: 1.0
 */
class Cache {
	private $path = null;
	public $timeout = 600;
	
	public function Cache(){
		$this->path = dirname(__FILE__) . "/../cache/";
	}
	
	/*
	 * Get cache file.
	 */
	public function get($file){
		$file = $this->path . $file;
		if (file_exists($file) && filemtime($file) + $this->timeout > time()) {
			$content = json_decode(file_get_contents($file));

			if(is_array($content)){
				return $content;
			}
		}
		return null;
	}
	
	/*
	 * Set cache file.
	 */
	public function set($file, $content){
		@file_put_contents($this->path . $file, json_encode($content));
	}
	
	/*
	 * Delete cache files.
	 */
	public function clear(){
		$files = glob($this->path . '*.json');
		foreach($files as $file){
			if(is_file($file)){
				@unlink($file);
			}
		}
	}
}
?>