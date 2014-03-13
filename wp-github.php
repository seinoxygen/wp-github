<?php
/**
 * Plugin Name: WP Github
 * Plugin URI: https://github.com/seinoxygen/wp-github
 * Description: Display users public Github repositories, commits and issues.
 * Author: Pablo Cornehl
 * Author URI: http://www.seinoxygen.com
 * Version: 1.0
 *
 * Licensed under the MIT License
 */
require dirname(__FILE__) . '/lib/cache.php';
require(dirname(__FILE__) . '/lib/github.php');

add_action('widgets_init', 'register_git_widgets');

function register_git_widgets(){
	register_widget('Widget_Repos');
	register_widget('Widget_Commits');
	register_widget('Widget_Issues');
	register_widget('Widget_Gists');
}

/*
 * Repositories widget.
 */
class Widget_Repos extends WP_Widget{
	function Widget_Repos() {
		$widget_ops = array('description' => __('Displays the repositories from a specific user.'));           
        $this->WP_Widget(false, __('Github Repositories'), $widget_ops, $control_ops);
	}
	
	function form($instance) {
		$title = $this->get_title($instance);
		$username = $this->get_username($instance);
		$project_count = $this->get_project_count($instance);

		?>
	    	<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> </label>
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
					name="<?php echo $this->get_field_name('title'); ?>" type="text" 
					value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Github Username:'); ?> </label>
					<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" 
					name="<?php echo $this->get_field_name('username'); ?>" type="text" 
					value="<?php echo $username; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('project_count'); ?>"><?php _e('Number of projects to show:'); ?> </label>
					<input id="<?php echo $this->get_field_id('project_count'); ?>" 
					name="<?php echo $this->get_field_name('project_count'); ?>" type="text" 
					value="<?php echo $project_count; ?>" size="3" />
			</p>
	    <?php
	}
	
	function widget($args, $instance) {
		extract($args);	
		
		$title = $this->get_title($instance);
		$username = $this->get_username($instance);
		$project_count = $this->get_project_count($instance);

		echo $before_widget;
		echo $before_title . $title . $after_title;
		
		$cache = new Cache();
		
		$repositories = $cache->get($username . '.repositories.json');
		if($repositories == null) {
			$github = new Github($username);
			$repositories = $github->get_repositories();
			$cache->set($username . '.repositories.json', $repositories);
		}

		if($repositories == null || count($repositories) == 0) {
			echo $username . ' does not have any public repositories.';
		} else {
			$repositories = array_slice($repositories, 0, $project_count);
			echo '<ul>';
			foreach($repositories as $repository){
		 		echo '<li><a href="'. $repository->html_url . '" title="'.$repository->description.'">' . $repository->name . '</a></li>';
			}
			echo '</ul>';
		}
		
		echo $after_widget;
	}
	
	private function get_title($instance) {
		return empty($instance['title']) ? 'My Github Projects' : apply_filters('widget_title', $instance['title']);
	}
	
	private function get_username($instance) {
		return empty($instance['username']) ? 'seinoxygen' : $instance['username'];
	}
	
	private function get_project_count($instance) {
		return empty($instance['project_count']) ? 5 : $instance['project_count'];
	}
}

/*
 * Commits widget.
 */
class Widget_Commits extends WP_Widget{
	function Widget_Commits() {
		$widget_ops = array('description' => __('Displays latests commits from a Github repository.'));           
        $this->WP_Widget(false, __('Github Commits'), $widget_ops, $control_ops);
	}
	
	function form($instance) {
		$title = $this->get_title($instance);
		$username = $this->get_username($instance);
		$repository = $this->get_repository($instance);
		$commit_count = $this->get_commit_count($instance);

		?>
	    	<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> </label>
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
					name="<?php echo $this->get_field_name('title'); ?>" type="text" 
					value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Github Username:'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" 
					name="<?php echo $this->get_field_name('username'); ?>" type="text" 
					value="<?php echo $username; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('repository'); ?>"><?php _e('Github Repository:'); ?> </label>
					<input class="widefat" id="<?php echo $this->get_field_id('repository'); ?>" 
					name="<?php echo $this->get_field_name('repository'); ?>" type="text" 
					value="<?php echo $repository; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('commit_count'); ?>"><?php _e('Number of commits to show:'); ?> </label>
					<input id="<?php echo $this->get_field_id('commit_count'); ?>" 
					name="<?php echo $this->get_field_name('commit_count'); ?>" type="text" 
					value="<?php echo $commit_count; ?>" size="3" />
			</p>
	    <?php
	}
	
	function widget($args, $instance) {
		extract($args);	
		
		$title = $this->get_title($instance);
		$username = $this->get_username($instance);
		$repository = $this->get_repository($instance);
		$commit_count = $this->get_commit_count($instance);
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		
		$cache = new Cache();
		
		$commits = $cache->get($username . '.' . $repository . '.commits.json');
		if($commits == null) {
			$github = new Github($username, $repository);
			$commits = $github->get_commits();
			$cache->set($username . '.' . $repository . '.commits.json', $commits);
		}
		
		if($commits == null || count($commits) == 0) {
			echo $username . ' does not have any public commits.';
		} else {
			$commits = array_slice($commits, 0, $commit_count);
			echo '<ul>';
			foreach($commits as $commit){
		 		echo '<li><a href="' . $commit->html_url . '" title="' . $commit->commit->message . '">' . substr($commit->sha, 0,10) . '</a></li>';
			}
			echo '</ul>';
		}
		
		echo $after_widget;
	}
	
	private function get_title($instance) {
		return empty($instance['title']) ? 'My Github Commits' : apply_filters('widget_title', $instance['title']);
	}
	
	private function get_username($instance) {
		return empty($instance['username']) ? 'seinoxygen' : $instance['username'];
	}
	
	private function get_repository($instance) {
		return empty($instance['repository']) ? 'wp-github' : $instance['repository'];
	}
	
	private function get_commit_count($instance) {
		return empty($instance['commit_count']) ? 5 : $instance['commit_count'];
	}
}

/*
 * Issues widget.
 */
class Widget_Issues extends WP_Widget{
	function Widget_Issues() {
		$widget_ops = array('description' => __('Displays latests issues from a Github repository.'));           
        $this->WP_Widget(false, __('Github Issues'), $widget_ops, $control_ops);
	}
	
	function form($instance) {
		$title = $this->get_title($instance);
		$username = $this->get_username($instance);
		$repository = $this->get_repository($instance);
		$issue_count = $this->get_issue_count($instance);

		?>
	    	<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> </label>
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
					name="<?php echo $this->get_field_name('title'); ?>" type="text" 
					value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Github Username:'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" 
					name="<?php echo $this->get_field_name('username'); ?>" type="text" 
					value="<?php echo $username; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('repository'); ?>"><?php _e('Github Repository:'); ?> </label>
					<input class="widefat" id="<?php echo $this->get_field_id('repository'); ?>" 
					name="<?php echo $this->get_field_name('repository'); ?>" type="text" 
					value="<?php echo $repository; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('issue_count'); ?>"><?php _e('Number of issues to show:'); ?> </label>
					<input id="<?php echo $this->get_field_id('issue_count'); ?>" 
					name="<?php echo $this->get_field_name('issue_count'); ?>" type="text" 
					value="<?php echo $issue_count; ?>" size="3" />
			</p>
	    <?php
	}
	
	function widget($args, $instance) {
		extract($args);	
		
		$title = $this->get_title($instance);
		$username = $this->get_username($instance);
		$repository = $this->get_repository($instance);
		$issue_count = $this->get_issue_count($instance);
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		
		$cache = new Cache();
		
		$issues = $cache->get($username . '.' . $repository . '.issues.json');
		if($issues == null) {
			$github = new Github($username, $repository);
			$issues = $github->get_issues();
			$cache->set($username . '.' . $repository . '.issues.json', $issues);
		}
		
		if($issues == null || count($issues) == 0) {
			echo $username . ' does not have any public issues.';
		} else {
			$issues = array_slice($issues, 0, $issue_count);
			echo '<ul>';
			foreach($issues as $issue){
		 		echo '<li><a href="' . $issue->html_url . '" title="' . $issue->title . '">' . $issue->title . '</a></li>';
			}
			echo '</ul>';
		}
		
		echo $after_widget;
	}
	
	private function get_title($instance) {
		return empty($instance['title']) ? 'My Github Issues' : apply_filters('widget_title', $instance['title']);
	}
	
	private function get_username($instance) {
		return empty($instance['username']) ? 'seinoxygen' : $instance['username'];
	}
	
	private function get_repository($instance) {
		return empty($instance['repository']) ? 'wp-github' : $instance['repository'];
	}
	
	private function get_issue_count($instance) {
		return empty($instance['issue_count']) ? 5 : $instance['issue_count'];
	}
}

/*
 * Gists widget.
 */
class Widget_Gists extends WP_Widget{
	function Widget_Gists() {
		$widget_ops = array('description' => __('Displays latests gists from a Github user.'));           
        $this->WP_Widget(false, __('Github Gists'), $widget_ops, $control_ops);
	}
	
	function form($instance) {
		$title = $this->get_title($instance);
		$username = $this->get_username($instance);
		$gists_count = $this->get_gists_count($instance);

		?>
	    	<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> </label>
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
					name="<?php echo $this->get_field_name('title'); ?>" type="text" 
					value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Github Username:'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" 
					name="<?php echo $this->get_field_name('username'); ?>" type="text" 
					value="<?php echo $username; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('gists_count'); ?>"><?php _e('Number of gists to show:'); ?> </label>
					<input id="<?php echo $this->get_field_id('gists_count'); ?>" 
					name="<?php echo $this->get_field_name('gists_count'); ?>" type="text" 
					value="<?php echo $gists_count; ?>" size="3" />
			</p>
	    <?php
	}
	
	function widget($args, $instance) {
		extract($args);	
		
		$title = $this->get_title($instance);
		$username = $this->get_username($instance);
		$gists_count = $this->get_gists_count($instance);
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		
		$cache = new Cache();
		
		$gists = $cache->get($username . '.gists.json');
		if($gists == null) {
			$github = new Github($username);
			$gists = $github->get_gists();
			$cache->set($username . '.gists.json', $gists);
		}
		
		if($gists == null || count($gists) == 0) {
			echo $username . ' does not have any public gists.';
		} else {
			$gists = array_slice($gists, 0, $gists_count);
			echo '<ul>';
			foreach($gists as $gist){
		 		echo '<li><a href="' . $gist->html_url . '" title="' . $gist->description . '">' . $gist->description . '</a></li>';
			}
			echo '</ul>';
		}
		
		echo $after_widget;
	}
	
	private function get_title($instance) {
		return empty($instance['title']) ? 'My Github Gists' : apply_filters('widget_title', $instance['title']);
	}
	
	private function get_username($instance) {
		return empty($instance['username']) ? 'seinoxygen' : $instance['username'];
	}
		
	private function get_gists_count($instance) {
		return empty($instance['gists_count']) ? 5 : $instance['gists_count'];
	}
}

/*
 * Repositories shortcode.
 */
function ghrepos_shortcode($atts) {
	extract( shortcode_atts(
		array(
			'username' => 'seinoxygen',
			'limit' => '5'
		), $atts )
	);
	
	$cache = new Cache();
		
	$repositories = $cache->get(username . '.repositories.json');
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
			'username' => 'seinoxygen',
			'repository' => 'wp-github',
			'limit' => '5'
		), $atts )
	);
	
	$cache = new Cache();
		
	$commits = $cache->get($username . '.' . $repository . '.commits.json');
	if($commits == null) {
		$github = new Github($username, $repository);
		$commits = $github->get_commits();
		$cache->set($username . '.' . $repository . '.commits.json', $commits);
	}

	$commits = array_slice($commits, 0, $limit);
	$html = '<ul>';
	foreach($commits as $commit){
		$html .=  '<li><a href="' . $commit->html_url . '" title="' . $commit->commit->message . '">' . substr($commit->sha, 0,10) . '</a></li>';
	}
	$html .= '</ul>';
	return $html;
}
add_shortcode('github-commits', 'ghcommits_shortcode');

/*
 * Issues shortcode.
 */
function ghissues_shortcode($atts) {
	extract( shortcode_atts(
		array(
			'username' => 'seinoxygen',
			'repository' => 'wp-github',
			'limit' => '5'
		), $atts )
	);
	
	$cache = new Cache();
		
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
			'username' => 'seinoxygen',
			'limit' => '5'
		), $atts )
	);
	
	$cache = new Cache();
		
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