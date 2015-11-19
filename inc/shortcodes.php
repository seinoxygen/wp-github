<?php
/*
 * Profile shortcode.
 */
function ghprofile_shortcode($atts) {
	extract( shortcode_atts(
		array(
			'username' => get_option('wpgithub_defaultuser', 'payzen')
		), $atts )
	);
	
	// Init the cache system.
	$cache = new WpGithubCache();
	// Set custom timeout in seconds.
	$cache->timeout = get_option('wpgithub_cache_time', 600);
		
	$profile = $cache->get($username . '.json');
	if($profile == null) {
		$github = new Github($username);
		$profile = $github->get_profile();
		$cache->set($username . '.json', $profile);
	}
	
	$html = '<div class="wpgithub-profile">';
	$html .=  '<div class="wpgithub-user">';
	$html .=  '<a href="'. $profile->html_url . '" title="View ' . $username . '\'s Github"><img src="http://gravatar.com/avatar/' . $profile->gravatar_id . '?s=56" alt="View ' . $username . '\'s Github" /></a>';
	$html .=  '<h3 class="wpgithub-username"><a href="'. $profile->html_url . '" title="View ' . $username . '\'s Github">' . $username . '</a></h3>';
	$html .=  '<p class="wpgithub-name">' . $profile->name . '</p>';
	$html .=  '<p class="wpgithub-location">' . $profile->location . '</p>';
	$html .=  '</div>';
	$html .=  '<a class="wpgithub-bblock" href="https://github.com/' . $username . '?tab=repositories"><span class="wpgithub-count">' . $profile->public_repos . '</span><span class="wpgithub-text">Public Repos</span></a>';
	$html .=  '<a class="wpgithub-bblock" href="https://gist.github.com/' . $username . '"><span class="wpgithub-count">' . $profile->public_gists . '</span><span class="wpgithub-text">Public Gists</span></a>';
	$html .=  '<a class="wpgithub-bblock" href="https://github.com/' . $username . '/followers"><span class="wpgithub-count">' . $profile->followers . '</span><span class="wpgithub-text">Followers</span></a>';
	$html .= '</div>';
	return $html;
}
add_shortcode('github-profile', 'ghprofile_shortcode');

/*
 * Repositories shortcode.
 */
function ghrepos_shortcode($atts) {
	extract( shortcode_atts(
		array(
			'username' => get_option('wpgithub_defaultuser', 'payzen'),
			'limit' => '5'
		), $atts )
	);
	
	// Init the cache system.
	$cache = new WpGithubCache();
	// Set custom timeout in seconds.
	$cache->timeout = get_option('wpgithub_cache_time', 600);
		
	$repositories = $cache->get($username . '.repositories.json');
	if($repositories == null) {
		$github = new Github($username);
		$repositories = $github->get_repositories();
		$cache->set($username . '.repositories.json', $repositories);
	}
	
	$repositories = array_slice($repositories, 0, $limit);
	$html = '<ul>';
	foreach($repositories as $repository){
		$html .=  '<li><a href="'. $repository->html_url . '" title="'.$repository->description.'">' . $repository->name . '</a></li>';
	}
	$html .= '</ul>';
	return $html;
}
add_shortcode('github-repos', 'ghrepos_shortcode');

/*
 * Commits shortcode.
 */
function ghcommits_shortcode($atts) {
	extract( shortcode_atts(
		array(
			'username' => get_option('wpgithub_defaultuser', 'payzen'),
			'repository' => '',
			'limit' => '5'
		), $atts )
	);
	
	// Init the cache system.
	$cache = new WpGithubCache();
	// Set custom timeout in seconds.
	$cache->timeout = get_option('wpgithub_cache_time', 600);
		
	$commits = $cache->get($username . '.' . $repository . '.commits.json');
	if($commits == null) {
		$github = new Github($username, $repository);
		$commits = $github->get_commits();
		$cache->set($username . '.' . $repository . '.commits.json', $commits);
	}

	$commits = array_slice($commits, 0, $limit);
	$html = '<ul>';
	foreach($commits as $commit){
		$html .=  '<li><a href="' . $commit->html_url . '" title="' . $commit->commit->message . '">' . $commit->commit->message . '</a></li>';
	}
	$html .= '</ul>';
	return $html;
}
add_shortcode('github-commits', 'ghcommits_shortcode');

/*
 * Releases shortcode.
 */
function ghreleases_shortcode($atts) {
	extract( shortcode_atts(
		array(
			'username' => get_option('wpgithub_defaultuser', 'payzen'),
			'repository' => '',
			'limit' => '5'
		), $atts )
	);
	
	// Init the cache system.
	$cache = new WpGithubCache();
	// Set custom timeout in seconds.
	$cache->timeout = get_option('wpgithub_cache_time', 600);
		
	$releases = $cache->get($username . '.' . $repository . '.releases.json');
	if($releases == null) {
		$github = new Github($username, $repository);
		$releases = $github->get_releases();
		//var_dump($releases);
		$cache->set($username . '.' . $repository . '.releases.json', $releases);
	}

	$releases = array_slice($releases, 0, $limit);
	$html = '<ul>';
	foreach($releases as $release){
		$html .=  '<li><a target="_blank" href="' . $release->zipball_url . '" title="' . $release->name . '">'.__('Download','wp-github').' ' . $release->tag_name . '</a></li>';
	}
	$html .= '</ul>';
	return $html;
}
add_shortcode('github-releases', 'ghreleases_shortcode');


/*
 * Releases shortcode.
 */
function ghreleaseslatest_shortcode($atts) {
	extract( shortcode_atts(
		array(
			'username' => 'yahoo',
			'repository' => 'pure',
		), $atts )
	);
	
	// Init the cache system.
	$cache = new WpGithubCache();
	// Set custom timeout in seconds.
	$cache->timeout = get_option('wpgithub_cache_time', 600);
		
	$latest_release = $cache->get($username . '.' . $repository . '.releaseslatest.json');
	if($latest_release == null) {
		$github = new Github($username, $repository);
		$latest_release = $github->get_latest_release();
		//var_dump($releases);
		$cache->set($username . '.' . $repository . '.releaseslatest.json', $latest_release);
	}

	$html = '<ul>';
	$html .= '<li>';
	$html .= '<a class="wpgithub-btn" target="_blank" href="' . $latest_release->zipball_url . '" title="' . $latest_release->tag_name . '">'.__('Download','wp-github').' ' . $latest_release->tag_name . '</a>';
	$html .= ' - <a target="_blank" href="' . $latest_release->html_url . '" title="' . $latest_release->tag_name . '">'.__('Show on Github','wp-github').'</a>';
	if(!empty($latest_release->body)):
		$html .= '<div class="wpgithub-description"><p>Description:';
		$html .= $latest_release->body;
		$html .= '</p></div>';
	endif;
	$html .= '</li>';

	$html .= '</ul>';
	return $html;
}
add_shortcode('github-releaseslatest', 'ghreleaseslatest_shortcode');


/*
 * Contents shortcode.
 * @param username & repository & path
 */
function ghcontents_shortcode($atts) {
	extract( shortcode_atts(
					array(
							'username' => 'yahoo',
							'repository' => 'pure',
							'contents'	=> 'README.md'
					), $atts )
	);
	//$contents = json_encode($contents);
	$file_name =  str_replace('/','.',$contents);
	// Init the cache system.
	$cache = new WpGithubCache();
	// Set custom timeout in seconds.
	$cache->timeout = get_option('wpgithub_cache_time', 600);

	$data_cache = $cache->get($username . '.' . $repository . '.'.$file_name.'.contents.json');
	if($data_cache == null) {
		$github = new Github($username, $repository, $contents);
		$contents = $github->get_contents();
		$cache->set($username . '.' . $repository . '.'.$file_name.'.contents.json', $contents);
	}

	$html = '<pre><code>';
		if(isset($contents)):
			//var_dump($contents);
			$html .= base64_decode($contents->content);
		else:
			$html .= __('File not found, please check your path','wp-github');
		endif;
	//echo $username . '.' . $repository . '.'.$file_name.'.contents.json';
	$html .= '</code></pre>';
	return $html;
}
add_shortcode('github-contents', 'ghcontents_shortcode');

/*
 * Issues shortcode.
 */
function ghissues_shortcode($atts) {
	extract( shortcode_atts(
		array(
			'username' => get_option('wpgithub_defaultuser', 'payzen'),
			'repository' => '',
			'limit' => '5'
		), $atts )
	);
	
	// Init the cache system.
	$cache = new WpGithubCache();
	// Set custom timeout in seconds.
	$cache->timeout = get_option('wpgithub_cache_time', 600);
		
	$issues = $cache->get($username . '.' . $repository . '.issues.json');
	if($issues == null) {
		$github = new Github($username, $repository);
		$issues = $github->get_issues();
		$cache->set($username . '.' . $repository . '.issues.json', $issues);
	}
	
	$issues = array_slice($issues, 0, $limit);
	$html = '<ul>';
	foreach($issues as $issue){
		$html .=  '<li><a href="' . $issue->html_url . '" title="' . $issue->title . '">' . $issue->title . '</a></li>';
	}
	$html .= '</ul>';
	return $html;
}
add_shortcode('github-issues', 'ghissues_shortcode');

/*
 * Gists shortcode.
 */
function ghgists_shortcode($atts) {
	extract( shortcode_atts(
		array(
			'username' => get_option('wpgithub_defaultuser', 'payzen'),
			'limit' => '5'
		), $atts )
	);
	
	// Init the cache system.
	$cache = new WpGithubCache();
	// Set custom timeout in seconds.
	$cache->timeout = get_option('wpgithub_cache_time', 600);
		
	$gists = $cache->get($username . '.gists.json');
	if($gists == null) {
		$github = new Github($username, $repository);
		$gists = $github->get_gists();
		$cache->set($username . '.gists.json', $gists);
	}
	
	$gists = array_slice($gists, 0, $limit);
	$html = '<ul>';
	foreach($gists as $gist){
		$html .=  '<li><a href="' . $gist->html_url . '" title="' . $gist->description . '">' . $gist->description . '</a></li>';
	}
	$html .= '</ul>';
	return $html;
}
add_shortcode('github-gists', 'ghgists_shortcode');