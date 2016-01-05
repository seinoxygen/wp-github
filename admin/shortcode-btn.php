<?php
add_action( 'init', 'wpgithub_buttons' );
function wpgithub_buttons() {
    add_filter( "mce_external_plugins", "wpgithub_add_buttons" );
    add_filter( 'mce_buttons', 'wpgithub_register_buttons' );
}
function wpgithub_add_buttons( $plugin_array ) {
    $plugin_array['wpgithub'] = plugins_url( 'wp-github-sc.js', __FILE__ );
    return $plugin_array;
}
function wpgithub_register_buttons( $buttons ) {
    array_push( $buttons, 'wpgithub' );
    return $buttons;
}


add_action( 'wp_ajax_get_wpgithub_shortcodes', 'get_wpgithub_shortcodes' );
add_action( 'wp_ajax_nopriv_get_wpgithub_shortcodes', 'get_wpgithub_shortcodes' );

function get_wpgithub_shortcodes() {
    if(isset($_POST['param'])){
        $param = $_POST['param'];
        $defaultusr = get_option('wpgithub_defaultuser', 'seinoxygen');
        $defaultrepo = get_option('wpgithub_defaultrepo', 'wp-github');
        $values = array(
      "profile" => "[github-profile username=\"$defaultusr\"]",
      "clone"   => "[github-clone username=\"$defaultusr\" repository=\"$defaultrepo\"]",
      "repos"   =>  "[github-repos username=\"$defaultusr\" limit=\"10\"]",
      "commits" => "[github-commits username=\"$defaultusr\" limit=\"10\"]",
      "commits10"   =>  "[github-commits username=\"$defaultusr\" repository=\"$defaultrepo\" limit=\"10\"]",
      "issues"  => "[github-issues username=\"$defaultusr\" limit=\"10\"]",
      "issues10"  => "[github-issues username=\"$defaultusr\" repository=\"$defaultrepo\" limit=\"10\"]",
      "issue" =>  "[github-issue username=\"$defaultusr\" repository=\"$defaultrepo\" number=\"14\"]",
      "pulls"   => "[github-pulls username=\"$defaultusr\" repository=\"$defaultrepo\" limit=\"10\"]",
      "gists"   => "[github-gists username=\"$defaultusr\" limit=\"10\"]",
      "releases"    => "[github-releases username=\"$defaultusr\" repository=\"$defaultrepo\" limit=\"10\"]",
      "releaseslatest"   =>  "[github-releaseslatest username=\"$defaultusr\" repository=\"$defaultrepo\" ]",
      "contents" => "[github-contents username=\"$defaultusr\" repository=\"$defaultrepo\" filepath=\"README.md\" language=\"markdown\" ]"
    );
        if(isset($values[$param])){
            echo $values[$param];
        }else{
            echo 'not found ';
        }

    }else{
        echo 'not found ';
    }


    die();
}