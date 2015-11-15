<?php
/**
 * Plugin Name: WP Github
 * Plugin URI: https://github.com/seinoxygen/wp-github
 * Description: Display users Github public repositories, commits, issues and gists.
 * Author: Pablo Cornehl
 * Author URI: http://www.seinoxygen.com
 * Version: 1.2.1
 *
 * Licensed under the MIT License
 */
require dirname(__FILE__) . '/lib/cache.php';
require(dirname(__FILE__) . '/lib/github.php');
require(dirname(__FILE__) . '/inc/shortcodes.php');
require(dirname(__FILE__) . '/inc/widgets.php');

// Init General Style
add_action('wp_enqueue_scripts', 'wpgithub_style', 20);
function wpgithub_style(){
	wp_enqueue_style('wp-github', plugin_dir_url(__FILE__).'wp-github.css');
	
	// If custom stylesheet exists load it.
	$custom = plugin_dir_path( __FILE__ ).'custom.css';
	if(file_exists($custom)){
		wp_enqueue_style('wp-github-custom', plugin_dir_url(__FILE__).'custom.css');
	}
}

// Admin 
add_action('admin_menu','wpgithub_plugin_menu');
add_action('admin_init', 'wpgithub_register_settings' );

function wpgithub_plugin_menu(){
    add_options_page('WP Github Options', 'WP Github', 'manage_options', 'wp-github', 'wpgithub_plugin_options');
}

function wpgithub_register_settings() {
	//register our settings
	register_setting('wp-github', 'wpgithub_cache_time', 'wpgithub_validate_int');
	register_setting('wp-github', 'wpgithub_clear_cache', 'wpgithub_clearcache' );
} 

function wpgithub_plugin_options(){
    include('admin/options.php');
}

function wpgithub_clearcache($input){
	if($input == 1){
		foreach(glob(plugin_dir_path( __FILE__ )."cache/*.json") as $file){
			unlink($file);
		}
		add_settings_error('wpgithub_clear_cache',esc_attr('settings_updated'),'Cache has been cleared.','updated');
	}
}

function wpgithub_validate_int($input) {
	return intval($input); // return validated input
}

