<?php

/**
 * Github
 * Author: Pablo Cornehl
 * Author URI: http://www.seinoxygen.com
 * Github : https://github.com/seinoxygen/wp-github
 * Version: 1.1
 */
class Github {


  private $api_url = 'https://api.github.com/';
  private $username = NULL;
  private $repository = NULL;
  private $number = NULL;
  private $contents = NULL;


  /**
   * Github constructor.
   * @param string $username
   * @param string $repository
   * @param string $contents
   */
  public function __construct($username = 'seinoxygen', $repository = '', $contents = 'README.md',$number = NULL) {
    $this->username = $username;
    $this->repository = $repository;
    $this->number = $number;
    $this->contents = $contents;
    //OAuth2 Key/Secret
    //https://developer.github.com/v3/#authentication
    //Grab auth elements
    $ci = get_option('wpgithub_clientID', '');
    $cs = get_option('wpgithub_clientSecret', '');
    $ct = get_option('wpgithub_access_token', '');
    $token =  (!empty($ct)) ?'access_token='.$ct : '';
    $url_append = (!empty($ci) && !empty($cs)) ?'client_id=' . $ci . '&client_secret=' . $cs : '';
    //prefer the Client ID & Secret if both are filled
    $auth_param = (!empty($url_append)) ? $url_append : $token;

    $this->oauth2 = $auth_param;

    /**
     * Increase execution time.
     * Sometimes long queries like fetch all issues from all repositories can kill php.
     */
    set_time_limit(90);
  }

  /**
   * Get response content from url.
   * use wp http api
   *
   * @param $path string
   * @return mixed
   */
  public function get_response($path) {

    //build URL
    if (strpos($path,'?') !== false) {
      $url = $this->api_url . $path .'&'. $this->oauth2;
    } else{
      $url = $this->api_url . $path . '?'.$this->oauth2;
    }
    $response = wp_remote_get( $url );
    if ( 200 == $response['response']['code'] ){
      return $response['body'];
    } else {
      return $response['response']['code'];
    }

  }

  /**
   * is_authenticated
   * check if user has filled credentials
   * @return bool
   */
  public function is_authenticated(){
    $contents = $this->get_response('user');
    if(is_integer($contents)){
      //user is not authenticated
      return false;
    } else {
      return true;
    }

  }

  /**
   * Return user profile.
   * @return array|mixed|null|object
   */
  public function get_profile() {
    $contents = $this->get_response('users/' . $this->username);
    if ($contents == TRUE) {
      return json_decode($contents);
    }
    return NULL;
  }


  /**
   * Return user events.
   * @return array|mixed|null|object
   */
  public function get_events() {
    $contents = $this->get_response('users/' . $this->username . '/events');
    if ($contents == TRUE) {
      return json_decode($contents);
    }
    return NULL;
  }

  /**
   * Return user repositories.
   * @return array|mixed|null|object
   */
  public function get_repositories() {
    $contents = $this->get_response('users/' . $this->username . '/repos');
    if ($contents == TRUE) {
      return json_decode($contents);
    }
    return NULL;
  }

  /**
   * Return repository commits.
   * If none is provided will fetch all commits from all public repositories from user.
   * @return array|mixed|object
   */
  public function get_commits() {
    $data = array();
    if (!empty($this->repository)) {
      $contents = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/commits');
      if ($contents == TRUE) {
        $data = array_merge($data, json_decode($contents));
      }
    }
    else {
      // Fetch all public repositories
      $repos = $this->get_repositories();

      if ($repos == TRUE) {
        // Loop through public repos and get all commits
        foreach ($repos as $repo) {
          $contents = $this->get_response('repos/' . $this->username . '/' . $repo->name . '/commits');
          if ($contents == TRUE && is_array($contents)) {
            $data = array_merge($data, json_decode($contents));
          }
          else {
            if ($contents == TRUE && !is_array($contents)) {
              $data = json_decode($contents);
            }
          }
        }
      }
      else {

      }
    }

    // Sort response array
    if (is_array($data)) {
      usort($data, array($this, 'order_commits'));
    }

    return $data;
  }

  /**
   * Return repository releases.
   * If none is provided will fetch all commits from all public repositories from user.
   * @return array|mixed|object|string
   */
  public function get_latest_release() {
    $data = '';
    if (!empty($this->repository)) {
      $contents = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/releases/latest');
      if ($contents == TRUE) {
        $data = json_decode($contents);
      }
    }
    return $data;
  }

  /**
   * get_clone
   * Return repository clone options.
   * GET /repos/:owner/:repo
   * If none is provided will fetch all commits from all public repositories from user.
   * @return array
   */
  public function get_clone() {
    $data = array();
    if (!empty($this->repository)) {
      $contents = $this->get_response('repos/' . $this->username . '/' . $this->repository);
      if ($contents == TRUE) {
        $data = json_decode($contents);
      }
    }

    else {
      // Fetch all public repositories
      $repo = $this->get_repository();
      if ($repo == TRUE) {
        $contents = $this->get_response('repos/' . $this->username . '/' . $this->repository);
        if ($contents == TRUE) {
          $data = json_decode($contents);
        }

      }
    }

    return $data;
  }

  /**
   * get_releases
   * Return repository releases.
   * If none is provided will fetch all commits from all public repositories from user.
   *
   * @return array
   */
  public function get_releases() {
    $data = array();
    if (!empty($this->repository)) {
      $contents = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/releases');
      if ($contents == TRUE) {
        $data = array_merge($data, json_decode($contents));
      }
    }

    else {
      // Fetch all public repositories
      $repos = $this->get_repositories();
      if ($repos == TRUE) {
        // Loop through public repos and get all commits
        foreach ($repos as $repo) {
          $contents = $this->get_response('repos/' . $this->username . '/' . $repo->name . '/releases');
          if ($contents == TRUE) {
            $data = array_merge($data, json_decode($contents));
          }
        }
      }
    }

    //Sort response array
    usort($data, array($this, 'order_releases'));

    return $data;
  }


  /**
   * get_contents
   * returns the contents of a file or directory in a repo
   *
   * @return array|mixed|object|string
   */
  public function get_contents() {
    $data = '';
    //GET /repos/:owner/:repo/contents/:path
    if (!empty($this->repository)) {
      $data_content = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/contents/' . $this->contents);
      if ($data_content == TRUE) {
        //Wordpress strip php tags -- what's the solution ?
        $data = json_decode($data_content);
        //trim php tags
        $data_code = str_replace('<?php', '', base64_decode($data->content));
        $data_code = str_replace('?>', '', $data_code);
        $data_code = base64_encode($data_code);
        $data->content = $data_code;
      }
    }
    return $data;
  }

  /**
   * Get repository issues.
   * GET /repos/:owner/:repo/issues
   * If none is provided will fetch all issues from all public repositories from user.
   * GET /user/issues
   *
   * @return array|mixed|object
   */
  public function get_issues() {
    $data = array();

    if (!empty($this->repository)) {

      $contents = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/issues?state=all&filter=all');
      if ($contents == TRUE) {
        $data = json_decode($contents);
      }

    } else {

      // Fetch all issues from the authenticated user
      //GET /user/issues
      if($this->is_authenticated()){
        $contents = $this->get_response('user/issues?state=all&filter=all');
        if ($contents == TRUE) {
          $data = json_decode($contents);
        }
      } else {
        $data = 'Please authenticate in wp-github settings';
      }

    }

    // Sort response array
    if(is_array($data)){
      usort($data, array($this, 'order_issues'));
    }

    return $data;
  }

  /**
   * Get repository single issue.
   * GET /repos/:owner/:repo/issues/:number
   * If none is provided will return error
   *
   * @return array|mixed|object
   */
  public function get_issue() {
    $data = array();
    if (!empty($this->number)) {
      $contents = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/issues/'.$this->number);
      if ($contents == TRUE) {
        $data = json_decode($contents);
      }

    } else {
      $data = 'issue not found';
    }

    return $data;
  }


  /**
   * Get pull request
   * GET /repos/:owner/:repo/pulls
   *
   * @return array|mixed|object
   */
  public function get_pulls() {
    $data = array();
    if (!empty($this->repository)) {
      $contents = $this->get_response('repos/' . $this->username . '/' . $this->repository . '/pulls');
      if ($contents == TRUE) {
        $data = json_decode($contents);
      }

    } else {
      // Fetch all issues
      //GET /user/issues
      $contents = $this->get_response($this->username . '/pulls');
      if ($contents == TRUE) {
        $data = json_decode($contents);
      }
    }

    // Sort response array
    if(is_array($data)){
      usort($data, array($this, 'order_issues'));
    }

    return $data;
  }

  /**
   * get_gists
   * GET users/:owner/gists
   *
   * @return array|mixed|null|object
   */
  public function get_gists() {
    $contents = $this->get_response('users/' . $this->username . '/gists');
    if ($contents == TRUE) {
      return json_decode($contents);
    }
    return NULL;
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
   * @param $a
   * @param $b
   * @return int
   */
  public function order_commits($a, $b) {
    if(is_object($a) && is_object($b)){

      $a = strtotime($a->commit->author->date);
      $b = strtotime($b->commit->author->date);
    }

    if ($a == $b) {
      return 0;
    }
    else {
      if ($a > $b) {
        return -1;
      }
      else {
        return 1;
      }
    }
  }

  /**
   * Sort commits from newer to older.
   * @param $a
   * @param $b
   * @return int
   */
  public function order_releases($a, $b) {

      $a = strtotime($a->created_at);
      $b = strtotime($b->created_at);

    if ($a == $b) {
      return 0;
    }
    else {
      if ($a > $b) {
        return -1;
      }
      else {
        return 1;
      }
    }
  }

  /**
   * Sort issues from newer to older.
   * @param $a
   * @param $b
   * @return int
   */
  public function order_issues($a, $b) {
    $a = strtotime($a->created_at);
    $b = strtotime($b->created_at);
    if ($a == $b) {
      return 0;
    }
    else {
      if ($a > $b) {
        return -1;
      }
      else {
        return 1;
      }
    }
  }
}
