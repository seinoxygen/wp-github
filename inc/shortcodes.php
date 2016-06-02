<?php
/**
 * Github : https://github.com/seinoxygen/wp-github
 */

/**
 * Github Profile shortcode.
 * @param $atts
 * @return string
 */
function ghprofile_shortcode($atts) {
   $a = shortcode_atts(
      array(
        'username' => get_option('wpgithub_defaultuser', 'seinoxygen')
      ), $atts);

  // Init the cache system.
  $cache = new WpGithubCache();
  // Set custom timeout in seconds.
  $cache->timeout = get_option('wpgithub_cache_time', 600);

  $profile = $cache->get($a['username'] . '.json');
  if ($profile == NULL) {
    $github = new Github($a['username']);
    $profile = $github->get_profile();
    $cache->set($a['username'] . '.json', $profile);
  }

  $html = '<div class="wpgithub-profile">';
  $html .= '<div class="wpgithub-user">';
  $html .= '<a class="wp-github-gravatar" target="_blank" href="' . $profile->html_url . '" title="View ' . $a['username'] . '\'s Github"><img width="80" src="' . $profile->avatar_url . '" alt="View ' . $a['username'] . '\'s Github" /></a>';
  $html .= '<div class="wp-github-usr">';
  $html .= '<h3 class="wpgithub-username"><a href="' . $profile->html_url . '" title="View ' . $a['username'] . '\'s Github">' . $a['username'] . '</a></h3>';
  $html .= '<p class="wpgithub-name">' . $profile->name . '</p>';
  $html .= '<p class="wpgithub-location">' . $profile->location . '</p>';
  $html .= '</div></div>';
  $html .= '<a target="_blank" class="wpgithub-bblock" href="https://github.com/' . $a['username'] . '?tab=repositories"><span class="wpgithub-count">' . $profile->public_repos . '</span><span class="wpgithub-text">Public Repos</span></a>';
  $html .= '<a target="_blank" class="wpgithub-bblock" href="https://gist.github.com/' . $a['username'] . '"><span class="wpgithub-count">' . $profile->public_gists . '</span><span class="wpgithub-text">Public Gists</span></a>';
  $html .= '<a target="_blank" class="wpgithub-bblock" href="https://github.com/' . $a['username'] . '/followers"><span class="wpgithub-count">' . $profile->followers . '</span><span class="wpgithub-text">Followers</span></a>';
  $html .= '</div>';
  return $html;
}

add_shortcode('github-profile', 'ghprofile_shortcode');


/**
 * Repositories shortcode.
 * List repositories as a simple li
 *
 * @param $atts
 * @return string
 */
function ghrepos_shortcode($atts) {
   $a = shortcode_atts(
      array(
        'username' => get_option('wpgithub_defaultuser', 'seinoxygen'),
        'limit' => '5'
      ), $atts);

  // Init the cache system.
  $cache = new WpGithubCache();
  // Set custom timeout in seconds.
  $cache->timeout = get_option('wpgithub_cache_time', 600);

  $repositories = $cache->get($a['username'] . '.repositories.json');
  if ($repositories == NULL) {
    $github = new Github($a['username']);
    $repositories = $github->get_repositories();
    $cache->set($a['username'] . '.repositories.json', $repositories);
  }
  if(is_array($repositories)){
    $repositories = array_slice($repositories, 0, $a['limit']);
  }

  $html = '<ul class="wp-github wpg-repos">';
  foreach ($repositories as $repo) {
    $html .= '<li><a target="_blank" href="' . $repo->html_url . '" title="' . $repo->description . '">' . $repo->name . '</a></li>';
  }
  $html .= '</ul>';
  return $html;
}

add_shortcode('github-repos', 'ghrepos_shortcode');


/**
 * List Commits shortcode.
 *
 * @param $atts
 * @return string
 */
function ghcommits_shortcode($atts) {
   $a = shortcode_atts(
      array(
        'username' => get_option('wpgithub_defaultuser', 'seinoxygen'),
        'repository' => get_option('wpgithub_defaultrepo', 'wp-github'),
        'limit' => '5'
      ), $atts);

  // Init the cache system.
  $cache = new WpGithubCache();
  // Set custom timeout in seconds.
  $cache->timeout = get_option('wpgithub_cache_time', 600);
  if(empty($a['repository'])){
    $commits = $cache->get($a['username'] . '.commits.json');
  }else{
    $commits = $cache->get($a['username'] . '.' . $a['repository'] . '.commits.json');
  }


  if ($commits == NULL) {
    $github = new Github($a['username'], $a['repository']);
    $commits = $github->get_commits();
    if(empty($a['repository'])){
      $cache->set($a['username'] . '.commits.json', $commits);
    }else{
      $cache->set($a['username'] . '.' . $a['repository'] . '.commits.json', $commits);
    }


  }
  if(is_array($commits)){
    $commits = array_slice($commits, 0, $a['limit']);
  }
  $html = '<ul class="wp-github wpg-commits">';
  foreach ($commits as $commit) {
    $html .= '<li><a target="_blank" href="' . $commit->html_url . '" title="' . $commit->commit->message . '">' . $commit->commit->message . '</a></li>';
  }
  $html .= '</ul>';
  return $html;
}

add_shortcode('github-commits', 'ghcommits_shortcode');


/**
 * Repository Releases shortcode.
 * List releases as a li
 * @param $atts
 * @return string
 */
function ghreleases_shortcode($atts) {
   $a = shortcode_atts(
      array(
        'username' => get_option('wpgithub_defaultuser', 'seinoxygen'),
        'repository' => get_option('wpgithub_defaultrepo', 'wp-github'),
        'limit' => '5'
      ), $atts);

  // Init the cache system.
  $cache = new WpGithubCache();
  // Set custom timeout in seconds.
  $cache->timeout = get_option('wpgithub_cache_time', 600);

  $releases = $cache->get($a['username'] . '.' . $a['repository'] . '.releases.json');
  if ($releases == NULL) {
    $github = new Github($a['username'], $a['repository']);
    $data = $github->get_releases();
    $cache->set($a['username'] . '.' . $a['repository'] . '.releases.json', $data);
  }

  $releases = $cache->get($a['username'] . '.' . $a['repository'] . '.releases.json');

  if(is_array($releases)){
    $releases = array_slice($releases, 0, $a['limit']);

    $html = '<ul class="wp-github wpg-releases">';
    foreach ($releases as $release) {
      $html .= '<li><a target="_blank" href="' . $release->zipball_url . '" title="' . $release->name . '">' . __('Download', 'wp-github') . ' ' . $release->tag_name . '</a></li>';
    }
    $html .= '</ul>';
  } else {

    $html = 'Please refresh';
  }


  return $html;
}

add_shortcode('github-releases', 'ghreleases_shortcode');


/**
 * Repository presentation shortcode.
 * GET /repos/:owner/:repo
 * @param $atts
 * @return string
 */
function ghclone_shortcode($atts) {
   $a = shortcode_atts(
      array(
        'username' => get_option('wpgithub_defaultuser', 'seinoxygen'),
        'repository' => get_option('wpgithub_defaultrepo', 'wp-github'),
      ), $atts);

  // Init the cache system.
  $cache = new WpGithubCache();
  // Set custom timeout in seconds.
  $cache->timeout = get_option('wpgithub_cache_time', 600);

  $clone = $cache->get($a['username'] . '.' . $a['repository'] . '.clone.json');
  if ($clone == NULL) {
    $github = new Github($a['username'], $a['repository']);
    $clone = $github->get_clone();
    //var_dump($clone);
    $cache->set($a['username'] . '.' . $a['repository'] . '.clone.json', $clone);
  }
  if(is_object($clone)) {
    $html = '<ul class="wp-github wpg-repo">';
    $html .= '<li>';
    $html .= '<span>SSH URL</span> <input readonly type="text" value="' . $clone->ssh_url . '" />';
    $html .= '</li><li>';
    $html .= '<span>Clone URL</span> <input readonly type="text" value="' . $clone->clone_url . '" />';
    $html .= '</li>';
    $html .= '</ul>';
  } else {
    $html = $clone;
  }
  return $html;
}

add_shortcode('github-clone', 'ghclone_shortcode');


/**
 * Latest Release shortcode.
 *
 * @param $atts
 * @return string
 */
function ghreleaseslatest_shortcode($atts) {
   $a = shortcode_atts(
      array(
        'username' => get_option('wpgithub_defaultuser', 'seinoxygen'),
        'repository' => get_option('wpgithub_defaultrepo', 'wp-github'),
      ), $atts);

  // Init the cache system.
  $cache = new WpGithubCache();
  // Set custom timeout in seconds.
  $cache->timeout = get_option('wpgithub_cache_time', 600);

  $latest_release = $cache->get($a['username'] . '.' . $a['repository'] . '.releaseslatest.json');
  if ($latest_release == NULL) {
    $github = new Github($a['username'], $a['repository']);
    $latest_release = $github->get_latest_release();
    //var_dump($releases);
    $cache->set($a['username'] . '.' . $a['repository'] . '.releaseslatest.json', $latest_release);
  }

  if(is_object($latest_release)){
    $html = '<div class="wp-github wpg-releaselatest">';
    $html .= '<a class="wpgithub-btn" target="_blank" href="' . $latest_release->zipball_url . '" title="' . $latest_release->tag_name . '">' . __('Download', 'wp-github') . ' ' . $latest_release->tag_name . '</a>';
    $html .= ' - <a target="_blank" href="' . $latest_release->html_url . '" title="' . $latest_release->tag_name . '">' . __('Show on Github', 'wp-github') . '</a>';
    if (!empty($latest_release->body)):
      $html .= '<div class="wpgithub-description"><p>'.__('Description','wp-github').':';
      $html .= $latest_release->body;
      $html .= '</p></div>';
    endif;
    $html .= '</div>';
  } else {
    if($latest_release == '404'){
      $html = 'Release not found';
    } else{
      $html = $latest_release;
    }

  }
  return $html;
}

add_shortcode('github-releaseslatest', 'ghreleaseslatest_shortcode');


/**
 * Contents shortcode.
 * embed a github file
 * Needed username & repository & path
 * @param $atts
 * @return string
 */
function ghcontents_shortcode($atts) {
   $a = shortcode_atts(
      array(
        'username' => get_option('wpgithub_defaultuser', 'seinoxygen'),
        'repository' => get_option('wpgithub_defaultrepo', 'wp-github'),
        'filepath' => '',
        'language' => 'markup',
      ), $atts);
  //$contents = json_encode($contents);
  $file_name = str_replace('/', '.', $a['filepath']);
  // Init the cache system.
  $cache = new WpGithubCache();
  // Set custom timeout in seconds.
  $cache->timeout = get_option('wpgithub_cache_time', 600);

  $contents = $cache->get($a['username'] . '.' . $a['repository'] . '.' . $file_name . '.contents.json');
  if ($contents == NULL) {
    $github = new Github($a['username'], $a['repository'], $a['filepath']);
    $contents = $github->get_contents();
    $cache->set($a['username'] . '.' . $a['repository'] . '.' . $file_name . '.contents.json', $contents);
  }

  $html = '<pre class="wp-github line-numbers language-' . $a['language'] . '"><code class="language-' . $a['language'] . '">';
  if (!isset($contents->message)):
    $html .= base64_decode($contents->content);
  else:
    $html .= __('Error : ' . $contents->message, 'wp-github') . '<br />';
    $html .= 'User : ' . $a['username'] . '<br />Repo : ' . $a['repository'] . '<br />file : ' . $file_name;
  endif;
  //echo $a['username'] . '.' . $a['repository'] . '.'.$file_name.'.contents.json';
  $html .= '</code></pre>';
  return $html;
}

add_shortcode('github-contents', 'ghcontents_shortcode');

/**
 * Single Issue shortcode.
 * GET /repos/:owner/:repo/issues/:number
 *
 * @param $atts
 * @return string
 */
function ghissue_shortcode($atts) {
  $a = shortcode_atts(
    array(
      'username' => get_option('wpgithub_defaultuser', 'seinoxygen'),
      'repository' => get_option('wpgithub_defaultrepo', 'wp-github'),
      'number' => ''
    ), $atts);

  // Init the cache system.
  $cache = new WpGithubCache();
  // Set custom timeout in seconds.
  $cache->timeout = get_option('wpgithub_cache_time', 600);

  $issue = $cache->get($a['username'] . '.' . $a['repository'] . '.issue.'.$a['number'].'.json');
  if ($issue == NULL) {
    $github = new Github($a['username'], $a['repository'], null, $a['number']);
    $issue = $github->get_issue();
    $cache->set($a['username'] . '.' . $a['repository'] . '.issue.'.$a['number'].'.json', $issue);
  }
  if(is_object($issue)){
    $html = '<div class="wp-github wpg-issue">';
    $html .= '<span class="wp-github-state ' . $issue->state . '">' . $issue->state . '</span>';
    $html .= '<span class="wp-github-nb">#' . $issue->number . '</span>';
    $html .= '<a target="_blank" href="' . $issue->html_url . '" title="' . $issue->title . '"> ' . $issue->title . '</a>';
    $html .= '</div>';
  } else {
    $html = $issue;
  }


  return $html;
}

add_shortcode('github-issue', 'ghissue_shortcode');

/**
 * Issues shortcode.
 * GET /repos/:owner/:repo/issues
 *
 * @param $atts
 * @return string
 */
function ghissues_shortcode($atts) {
   $a = shortcode_atts(
      array(
        'username' => get_option('wpgithub_defaultuser', 'seinoxygen'),
        'repository' => get_option('wpgithub_defaultrepo', 'wp-github'),
        'limit' => '5'
      ), $atts);
  
  // Init the cache system.
  $cache = new WpGithubCache();
  // Set custom timeout in seconds.
  $cache->timeout = get_option('wpgithub_cache_time', 600);

  //if no repository
  if(empty($a['repository'])){
    $issues = $cache->get($a['username'] .'.issues.json');
  } else {
    $issues = $cache->get($a['username'] . '.' . $a['repository'] . '.issues.json');
  }

  if ($issues == NULL) {
    $github = new Github($a['username'], $a['repository']);
    $issues = $github->get_issues();
    if(empty($a['repository'])) {
      $cache->set($a['username'] . '.issues.json', $issues);
    }else {
      $cache->set($a['username'] . '.' . $a['repository'] . '.issues.json', $issues);
    }
  }
  if(is_array($issues)){
    $issues = array_slice($issues, 0, $a['limit']);
    $html = '<ul class="wp-github wpg-issues">';
    foreach ($issues as $issue) {
      $html .= '<li><span class="wp-github-state ' . $issue->state . '">' . $issue->state . '</span><span class="wp-github-nb">#' . $issue->number . '</span><a target="_blank" href="' . $issue->html_url . '" title="' . $issue->title . '"> ' . $issue->title . '</a></li>';
    }
    $html .= '</ul>';
  } else {
    $html = 'error : '.$issues;
  }
  return $html;
}

add_shortcode('github-issues', 'ghissues_shortcode');


/**
 * Issues shortcode.
 * GET /repos/:owner/:repo/issues
 *
 * @param $atts
 * @return string
 */
function ghpulls_shortcode($atts) {
  $a = shortcode_atts(
    array(
      'username' => get_option('wpgithub_defaultuser', 'seinoxygen'),
      'repository' => get_option('wpgithub_defaultrepo', 'wp-github'),
      'limit' => '5'
    ), $atts);

  // Init the cache system.
  $cache = new WpGithubCache();
  // Set custom timeout in seconds.
  $cache->timeout = get_option('wpgithub_cache_time', 600);

  $pulls = $cache->get($a['username'] . '.' . $a['repository'] . '.pulls.json');
  if ($pulls == NULL) {
    $github = new Github($a['username'], $a['repository']);
    $pulls = $github->get_pulls();
    $cache->set($a['username'] . '.' . $a['repository'] . '.pulls.json', $pulls);
  }
  if(is_array($pulls)){
    $pulls = array_slice($pulls, 0, $a['limit']);
    $html = '<ul class="wp-github wpg-pulls">';
    foreach ($pulls as $pull) {
      $html .= '<li><span class="wp-github-state ' . $pull->state . '">' . $pull->state . '</span><span class="wp-github-nb">#' . $pull->number . '</span><a target="_blank" href="' . $pull->html_url . '" title="' . $pull->title . '"> ' . $pull->title . '</a></li>';
    }
    $html .= '</ul>';
  } else {
    $html = 'error : '.$pulls;
  }
  return $html;
}

add_shortcode('github-pulls', 'ghpulls_shortcode');


/**
 * Gists shortcode.
 * @param $atts
 * @return string
 */
function ghgists_shortcode($atts) {
   $a = shortcode_atts(
      array(
        'username' => get_option('wpgithub_defaultuser', 'seinoxygen'),
        'limit' => '5'
      ), $atts);

  // Init the cache system.
  $cache = new WpGithubCache();
  // Set custom timeout in seconds.
  $cache->timeout = get_option('wpgithub_cache_time', 600);

  $gists = $cache->get($a['username'] . '.gists.json');
  if ($gists == NULL) {
    $github = new Github($a['username']);
    $gists = $github->get_gists();
    $cache->set($a['username'] . '.gists.json', $gists);
  }
  if(is_array($gists)){
    $gists = array_slice($gists, 0, $a['limit']);
  }

  $html = '<ul class="wp-github wpg-gists">';
  foreach ($gists as $gist) {
    $html .= '<li><a target="_blank" href="' . $gist->html_url . '" title="' . $gist->description . '">' . $gist->description . '</a></li>';
  }
  $html .= '</ul>';
  return $html;
}

add_shortcode('github-gists', 'ghgists_shortcode');