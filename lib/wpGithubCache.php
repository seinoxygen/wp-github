<?php
/**
 * WpGithubCache
 * Author: Pablo Cornehl
 * Author URI: http://www.seinoxygen.com
 * Github : https://github.com/seinoxygen/wp-github
 * Version: 1.0
 */
class WpGithubCache {


  private $path = NULL;
  public $timeout = 600;

  /**
   * WpGithubCache constructor.
   */
  public function __construct() {
    $this->path = dirname(__FILE__) . "/../cache/";
  }


  /**
   * Get cache file.
   * @param $file
   * @return array|mixed|null|object
   */
  public function get($file) {
    $file = $this->path . $file;
    $file_age = filemtime($file) + $this->timeout;
    $file_timed_out = intval($file_age - time());
    if (file_exists($file) && $file_timed_out > 0) {
      $content = json_decode(file_get_contents($file));
      if (is_array($content)) {
        return $content;
      }
    }
    return NULL;
  }


  /**
   * Set cache file.
   * create static file
   * @param $file
   * @param $content
   */
  public function set($file, $content) {
    @file_put_contents($this->path . $file, json_encode($content));
  }


  /**
   * clear
   * delete all files
   */
  public function clear() {
    $files = glob($this->path . '*.json');
    foreach ($files as $file) {
      if (is_file($file)) {
        @unlink($file);
      }
    }
  }
}
